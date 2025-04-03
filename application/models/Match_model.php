<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Match Model
 * 
 * Model untuk mengelola pencocokan antar pengguna
 * 
 * @package     MeetWithBalinese
 * @subpackage  Models
 * @category    Match
 * @author      MeetWithBalinese Team
 */
class Match_model extends CI_Model {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Mencari pengguna yang cocok untuk user tertentu
     * 
     * @param int $user_id ID pengguna
     * @param int $limit Jumlah maksimal hasil
     * @param int $offset Offset untuk pagination
     * @return array Daftar pengguna yang cocok
     */
    public function find_matches($user_id, $limit = 10, $offset = 0) {
        try {
            // Pastikan koneksi ke database
            $this->db->query("SELECT 1");
            
            // Ambil data user
            $user = $this->db->get_where('users', ['user_id' => $user_id])->row();
            if (!$user) {
                log_message('debug', 'User not found, using empty matches array');
                return [];
            }

            // Ambil hasil kasta user
            $user_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
            if (!$user_kasta) {
                log_message('debug', 'User kasta result not found, using default kasta');
                // Generate default kasta (bukan random)
                $user_kasta = new stdClass();
                $user_kasta->kasta_id = 1; // Default: Brahmana
                $user_kasta->confidence_score = 0; // User belum mengisi quiz
            }

            // Gender yang dicari (lawan jenis)
            $target_gender = ($user->gender == 'Male') ? 'Female' : 'Male';

            // Cek apakah ada pengguna lain untuk dicocokkan
            $this->db->where('user_id !=', $user_id);
            $users_count = $this->db->count_all_results('users');
            
            if ($users_count == 0) {
                log_message('debug', 'No other users found, using empty matches array');
                return [];
            }
            
            // Cari pengguna lain dengan kasta yang sama (prioritas utama)
            $this->db->select('u.*, ukr.kasta_id, ukr.confidence_score');
            $this->db->from('users u');
            $this->db->join('userkastaresult ukr', 'u.user_id = ukr.user_id', 'inner'); // Hanya pengguna yang sudah mengisi quiz
            $this->db->where('u.user_id !=', $user_id);
            $this->db->where('ukr.kasta_id', $user_kasta->kasta_id);
            $this->db->limit($limit / 2, $offset);
            $same_kasta_users = $this->db->get()->result();
            log_message('debug', 'Found ' . count($same_kasta_users) . ' users with same kasta');

            // Cari pengguna lain dengan kasta berbeda
            $this->db->select('u.*, ukr.kasta_id, ukr.confidence_score');
            $this->db->from('users u');
            $this->db->join('userkastaresult ukr', 'u.user_id = ukr.user_id', 'inner'); // Hanya pengguna yang sudah mengisi quiz
            $this->db->where('u.user_id !=', $user_id);
            $this->db->where('ukr.kasta_id !=', $user_kasta->kasta_id);
            $this->db->limit($limit / 2, $offset);
            $different_kasta_users = $this->db->get()->result();
            log_message('debug', 'Found ' . count($different_kasta_users) . ' users with different kasta');

            // Gabungkan hasil
            $all_matches = array_merge($same_kasta_users, $different_kasta_users);
            
            // Jika masih tidak ada hasil, kembalikan array kosong
            if (empty($all_matches)) {
                log_message('debug', 'No matches found after queries, returning empty array');
                return [];
            }

            // Hitung match score untuk setiap user
            foreach ($all_matches as $key => $match) {
                // Default kasta jika tidak ada
                if (empty($match->kasta_id)) {
                    $match->kasta_id = 1; // Default: Brahmana
                }
                
                // Cek apakah user memiliki kasta
                if (!$user_kasta || !isset($user_kasta->kasta_id) || $user_kasta->confidence_score === 0 || $match->confidence_score === 0) {
                    // User belum mengisi quiz, set match_score ke 0
                    $match_score = 0;
                } else if ($match->kasta_id == $user_kasta->kasta_id) {
                    // Kasta sama = 100% match
                    $match_score = 100;
                } else {
                    // Semakin jauh perbedaan kasta, semakin rendah match score
                    $kasta_diff = abs($match->kasta_id - $user_kasta->kasta_id);
                    $match_score = 100 - ($kasta_diff * 20); // 80%, 60%, 40%
                }
                
                // Pastikan dalam range 0-100
                $match_score = max(0, min(100, $match_score));
                
                // Simpan ke objek hasil
                $all_matches[$key]->match_score = $match_score;
                
                // Simpan hasil match ke database
                $this->save_match($user_id, $match->user_id, $match_score);
            }

            // Urutkan berdasarkan match score (tertinggi dulu)
            usort($all_matches, function($a, $b) {
                return $b->match_score - $a->match_score;
            });

            // Tambahkan flag best_match untuk yang tertinggi
            if (!empty($all_matches)) {
                $all_matches[0]->is_best_match = true;
            }
            
            log_message('debug', 'Returning ' . count($all_matches) . ' matches for user_id: ' . $user_id);
            return $all_matches;
        } catch (Exception $e) {
            log_message('error', 'Database error in find_matches: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Simpan hasil pencocokan
     * 
     * @param int $user_id_1 ID pengguna pertama
     * @param int $user_id_2 ID pengguna kedua
     * @param float $match_score Skor kecocokan
     * @return int|bool ID match atau FALSE jika gagal
     */
    public function save_match($user_id_1, $user_id_2, $match_score) {
        try {
            // Cek apakah match sudah ada
            $this->db->where('(user_id_1 = ' . $user_id_1 . ' AND user_id_2 = ' . $user_id_2 . ')');
            $this->db->or_where('(user_id_1 = ' . $user_id_2 . ' AND user_id_2 = ' . $user_id_1 . ')');
            $existing_match = $this->db->get('matches')->row();

            if ($existing_match) {
                // Update match yang sudah ada
                $this->db->where('match_id', $existing_match->match_id);
                $this->db->update('matches', ['match_score' => $match_score]);
                return $existing_match->match_id;
            } else {
                // Buat match baru
                $data = [
                    'user_id_1' => $user_id_1,
                    'user_id_2' => $user_id_2,
                    'match_score' => $match_score,
                    'status' => 'pending'
                ];
                $this->db->insert('matches', $data);
                return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
            }
        } catch (Exception $e) {
            log_message('error', 'Database error in save_match: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Dapatkan data kecocokan antara dua user
     * 
     * @param int $user_id_1 ID pengguna pertama
     * @param int $user_id_2 ID pengguna kedua
     * @return object|false Data kecocokan atau FALSE jika tidak ditemukan
     */
    public function get_match_by_users($user_id_1, $user_id_2) {
        try {
            // Cek apakah match sudah ada di database
            $this->db->where('(user_id_1 = ' . $user_id_1 . ' AND user_id_2 = ' . $user_id_2 . ')');
            $this->db->or_where('(user_id_1 = ' . $user_id_2 . ' AND user_id_2 = ' . $user_id_1 . ')');
            $match = $this->db->get('matches')->row();
            
            if ($match) {
                return $match;
            }
            
            // Jika belum ada di database, generate match baru
            // Ambil data kasta kedua user
            $user1_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user_id_1])->row();
            $user2_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user_id_2])->row();
            
            // Set default kasta jika tidak ada
            if (!$user1_kasta) {
                $user1_kasta = new stdClass();
                $user1_kasta->kasta_id = 1; // Default: Brahmana
                $user1_kasta->confidence_score = 0; // User belum mengisi quiz
                log_message('debug', 'No kasta found for user_id ' . $user_id_1 . ', using default kasta');
            }
            
            if (!$user2_kasta) {
                $user2_kasta = new stdClass();
                $user2_kasta->kasta_id = 1; // Default: Brahmana
                $user2_kasta->confidence_score = 0; // User belum mengisi quiz
                log_message('debug', 'No kasta found for user_id ' . $user_id_2 . ', using default kasta');
            }
            
            // Jika salah satu user belum mengisi quiz, set match_score ke 0
            if ($user1_kasta->confidence_score === 0 || $user2_kasta->confidence_score === 0) {
                $match_score = 0;
            }
            // Hitung match score berdasarkan kasta
            else if ($user1_kasta->kasta_id == $user2_kasta->kasta_id) {
                // Kasta sama = 100% match
                $match_score = 100;
            } else {
                // Semakin jauh perbedaan kasta, semakin rendah match score
                $kasta_diff = abs($user1_kasta->kasta_id - $user2_kasta->kasta_id);
                $match_score = 100 - ($kasta_diff * 20); // 80%, 60%, 40%
            }
            
            // Pastikan dalam range 0-100
            $match_score = max(0, min(100, $match_score));
            
            // Simpan ke database
            $data = [
                'user_id_1' => $user_id_1,
                'user_id_2' => $user_id_2,
                'match_score' => $match_score,
                'status' => 'pending'
            ];
            
            $this->db->insert('matches', $data);
            $match_id = $this->db->insert_id();
            
            if ($match_id) {
                $data['match_id'] = $match_id;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                
                // Convert array to object
                $match = (object)$data;
                return $match;
            }
            
            return false;
        } catch (Exception $e) {
            log_message('error', 'Error in get_match_by_users: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update status pencocokan
     * 
     * @param int $match_id ID pencocokan
     * @param string $status Status baru ('accepted', 'rejected')
     * @return bool TRUE jika berhasil, FALSE jika gagal
     */
    public function update_match_status($match_id, $status) {
        try {
            $this->db->where('match_id', $match_id);
            $this->db->update('matches', ['status' => $status]);
            return ($this->db->affected_rows() > 0);
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     * Dapatkan semua match untuk user tertentu
     * 
     * @param int $user_id ID pengguna
     * @param string $status Filter berdasarkan status
     * @return array Daftar match
     */
    public function get_user_matches($user_id, $status = null) {
        try {
            $this->db->where('user_id_1', $user_id);
            $this->db->or_where('user_id_2', $user_id);
            
            if ($status) {
                $this->db->where('status', $status);
            }
            
            return $this->db->get('matches')->result();
        } catch (Exception $e) {
            return [];
        }
    }
} 