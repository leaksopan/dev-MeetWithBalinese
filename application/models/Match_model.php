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

            // Ambil hasil kasta user termasuk posisi linear
            $user_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
            if (!$user_kasta) {
                log_message('debug', 'User kasta result not found, using default kasta');
                // Generate default kasta dan posisi linear
                $user_kasta = new stdClass();
                $user_kasta->kasta_id = 1; // Default: Brahmana
                $user_kasta->linear_position = 12; // Default: Brahmana Utama
                $user_kasta->confidence_score = 0; // User belum mengisi quiz
            }

            // Ambil user answers untuk clustering
            $this->db->select('q.question_id, ao.option_id, ao.kasta_indicator, ao.weight');
            $this->db->from('useranswers ua');
            $this->db->join('questions q', 'ua.question_id = q.question_id');
            $this->db->join('answeroptions ao', 'ua.selected_option_id = ao.option_id');
            $this->db->where('ua.user_id', $user_id);
            $user_answers = $this->db->get()->result();
            
            // Simpan jawaban pengguna dalam array untuk perbandingan
            $user_answer_map = array();
            $user_profile = array(
                1 => 0, // Brahmana weight
                2 => 0, // Ksatria weight
                3 => 0, // Waisya weight
                4 => 0  // Sudra weight
            );
            
            // Buat profil pengguna berdasarkan jawaban
            foreach ($user_answers as $answer) {
                $user_answer_map[$answer->question_id] = $answer->option_id;
                $user_profile[$answer->kasta_indicator] += $answer->weight;
            }
            
            // Normalisasi profil pengguna (jika ada jawaban)
            $total_weight = array_sum($user_profile);
            if ($total_weight > 0) {
                foreach ($user_profile as $kasta => $weight) {
                    $user_profile[$kasta] = $weight / $total_weight;
                }
            }
            
            // Gender yang dicari (lawan jenis)
            $target_gender = ($user->gender == 'Male') ? 'Female' : 'Male';

            // Cek apakah ada pengguna lain untuk dicocokkan
            $this->db->where('user_id !=', $user_id);
            // Filter hanya lawan jenis
            $this->db->where('gender', $target_gender);
            $users_count = $this->db->count_all_results('users');
            
            if ($users_count == 0) {
                log_message('debug', 'No other users found, using empty matches array');
                return [];
            }
            
            // Ambil linear position untuk pengelompokan
            $linear_position = isset($user_kasta->linear_position) ? $user_kasta->linear_position : 6;
            
            // Mencari pengguna dengan posisi linear yang dekat (maksimal 2 tingkat perbedaan)
            $this->db->select('u.*, ukr.kasta_id, ukr.confidence_score, ukr.linear_position');
            $this->db->from('users u');
            $this->db->join('userkastaresult ukr', 'u.user_id = ukr.user_id', 'inner');
            $this->db->where('u.user_id !=', $user_id);
            // Filter hanya lawan jenis
            $this->db->where('u.gender', $target_gender);
            $this->db->where('ukr.linear_position IS NOT NULL'); // Pastikan linear_position tidak NULL
            $this->db->where('ukr.linear_position >=', $linear_position - 2);
            $this->db->where('ukr.linear_position <=', $linear_position + 2);
            $this->db->limit($limit / 2, $offset);
            $close_position_users = $this->db->get()->result();
            log_message('debug', 'Query close position: ' . $this->db->last_query());
            log_message('debug', 'Found ' . count($close_position_users) . ' users with close linear position');

            // Mencari pengguna dengan posisi linear yang lebih jauh
            $this->db->select('u.*, ukr.kasta_id, ukr.confidence_score, ukr.linear_position');
            $this->db->from('users u');
            $this->db->join('userkastaresult ukr', 'u.user_id = ukr.user_id', 'inner');
            $this->db->where('u.user_id !=', $user_id);
            // Filter hanya lawan jenis
            $this->db->where('u.gender', $target_gender);
            $this->db->where('ukr.linear_position IS NOT NULL'); // Pastikan linear_position tidak NULL
            $this->db->where("(ukr.linear_position < " . ($linear_position - 2) . " OR ukr.linear_position > " . ($linear_position + 2) . ")");
            $this->db->limit($limit / 2, $offset);
            $distant_position_users = $this->db->get()->result();
            log_message('debug', 'Query distant position: ' . $this->db->last_query());
            log_message('debug', 'Found ' . count($distant_position_users) . ' users with distant linear position');

            // Gabungkan hasil
            $all_matches = array_merge($close_position_users, $distant_position_users);
            
            // Jika masih tidak ada hasil, coba cari berdasarkan kasta utama
            if (empty($all_matches)) {
                log_message('debug', 'No matches found based on linear position, falling back to kasta_id');
                
                // Cari pengguna dengan kasta yang sama dan gender lawan jenis
                $this->db->select('u.*, ukr.kasta_id, ukr.confidence_score, ukr.linear_position');
                $this->db->from('users u');
                $this->db->join('userkastaresult ukr', 'u.user_id = ukr.user_id', 'left');
                $this->db->where('u.user_id !=', $user_id);
                // Filter hanya lawan jenis
                $this->db->where('u.gender', $target_gender);
                $this->db->order_by('RAND()');
                $this->db->limit($limit, $offset);
                $all_matches = $this->db->get()->result();
                log_message('debug', 'Fallback query: ' . $this->db->last_query());
                log_message('debug', 'Found ' . count($all_matches) . ' users with fallback method');
            }
            
            // Jika masih tidak ada hasil, kembalikan array kosong
            if (empty($all_matches)) {
                log_message('debug', 'No matches found after queries, returning empty array');
                return [];
            }

            // Hitung match score untuk setiap user menggunakan posisi linear dan rumus baru
            foreach ($all_matches as $key => $match) {
                // Default posisi jika tidak ada
                if (empty($match->linear_position)) {
                    // Coba hitung posisi dari profil
                    $match_position = $this->get_user_sub_kasta($match->user_id);
                    if ($match_position === false) {
                        // Cek apakah user punya hasil kasta (pernah mengisi quiz)
                        $user_has_result = $this->db->get_where('userkastaresult', ['user_id' => $match->user_id])->row();
                        
                        if (!$user_has_result) {
                            // User belum pernah mengisi quiz, set match_score = 1 (minimal 1%)
                            $all_matches[$key]->match_score = 1;
                            // Simpan hasil match ke database
                            $this->save_match($user_id, $match->user_id, 1);
                            continue; // Lanjut ke match berikutnya
                        }
                        
                        // Gunakan default berdasarkan kasta jika user sudah pernah quiz
                        $match_position = 12 - (($match->kasta_id - 1) * 3); // Default posisi tertinggi di kasta
                    }
                    $match->linear_position = $match_position;
                }
                
                // Hitung perbedaan posisi
                $position_diff = abs($linear_position - $match->linear_position);
                
                // Hitung match score dasar berdasarkan perbedaan posisi
                // Setiap perbedaan 1 tingkat = pengurangan 8.33%
                $base_match_score = 100 - ($position_diff * 8.33);
                
                // Pastikan dalam range 1-100
                $base_match_score = max(1, min(100, $base_match_score));
                
                // Terapkan faktor scaling berdasarkan kasta dan gender
                $scaling_factor = $this->calculate_kasta_scaling_factor($linear_position, $match->linear_position, $user->gender);
                $match_score = $base_match_score * $scaling_factor;
                
                // Pastikan skor match selalu antara 1 dan 99 (tidak ada yang mutlak)
                $match_score = max(1, min(99, $match_score));
                
                // Simpan ke objek hasil dengan keterangan tambahan
                $all_matches[$key]->base_score = $base_match_score;
                $all_matches[$key]->scaling_factor = $scaling_factor;
                $all_matches[$key]->match_score = $match_score;
                $all_matches[$key]->scaling_description = $this->get_scaling_description($scaling_factor, $linear_position, $match->linear_position, $user->gender);
                
                // Simpan hasil match ke database
                $this->save_match($user_id, $match->user_id, $match_score, $scaling_factor, $this->get_scaling_description($scaling_factor, $linear_position, $match->linear_position, $user->gender));
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
     * @param float $scaling_factor Faktor scaling (optional)
     * @param string $scaling_description Deskripsi scaling (optional)
     * @return int|bool ID match atau FALSE jika gagal
     */
    public function save_match($user_id_1, $user_id_2, $match_score, $scaling_factor = null, $scaling_description = null) {
        try {
            // Cek apakah match sudah ada
            $this->db->where('(user_id_1 = ' . $user_id_1 . ' AND user_id_2 = ' . $user_id_2 . ')');
            $this->db->or_where('(user_id_1 = ' . $user_id_2 . ' AND user_id_2 = ' . $user_id_1 . ')');
            $existing_match = $this->db->get('matches')->row();

            // Siapkan data yang akan disimpan/diupdate
            $data = ['match_score' => $match_score];
            
            // Tambahkan data scaling jika disediakan
            if ($scaling_factor !== null) {
                $data['scaling_factor'] = $scaling_factor;
            }
            
            if ($scaling_description !== null) {
                $data['scaling_description'] = $scaling_description;
            }

            if ($existing_match) {
                // Update match yang sudah ada
                $this->db->where('match_id', $existing_match->match_id);
                $this->db->update('matches', $data);
                return $existing_match->match_id;
            } else {
                // Buat match baru
                $data['user_id_1'] = $user_id_1;
                $data['user_id_2'] = $user_id_2;
                $data['status'] = 'pending';
                
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
            
            // Jika belum ada di database, generate match baru dengan metode clustering
            // calculate_match_score_with_clustering sudah otomatis menyimpan ke database
            $match_score = $this->calculate_match_score_with_clustering($user_id_1, $user_id_2);
            
            // Ambil data match yang baru dibuat
            $this->db->where('(user_id_1 = ' . $user_id_1 . ' AND user_id_2 = ' . $user_id_2 . ')');
            $this->db->or_where('(user_id_1 = ' . $user_id_2 . ' AND user_id_2 = ' . $user_id_1 . ')');
            $match = $this->db->get('matches')->row();
            
            if ($match) {
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

    /**
     * Menghitung match score berdasarkan profil kasta menggunakan metode clustering
     * 
     * @param int $user1_id ID pengguna pertama
     * @param int $user2_id ID pengguna kedua
     * @param array $user1_profile Profil kasta user pertama (optional)
     * @return float Match score (0-100)
     */
    private function calculate_match_score_with_clustering($user1_id, $user2_id, $user1_profile = null) {
        try {
            // Ambil data gender untuk user pertama dan kedua
            $user1 = $this->db->get_where('users', ['user_id' => $user1_id])->row();
            $user2 = $this->db->get_where('users', ['user_id' => $user2_id])->row();
            
            if (!$user1 || !$user2) {
                return 0; // User tidak ditemukan
            }
            
            // Pastikan hanya lawan jenis yang bisa dicocokkan
            if ($user1->gender == $user2->gender) {
                log_message('debug', "Cannot match users with same gender: $user1_id and $user2_id");
                return 1; // Kembalikan nilai minimal 1% untuk match score
            }
            
            // Jika profil user pertama tidak disediakan, ambil dari database
            if ($user1_profile === null) {
                // Ambil jawaban user pertama
                $this->db->select('q.question_id, ao.option_id, ao.kasta_indicator, ao.weight');
                $this->db->from('useranswers ua');
                $this->db->join('questions q', 'ua.question_id = q.question_id');
                $this->db->join('answeroptions ao', 'ua.selected_option_id = ao.option_id');
                $this->db->where('ua.user_id', $user1_id);
                $user1_answers = $this->db->get()->result();
                
                // Buat profil user pertama
                $user1_profile = array(
                    1 => 0, // Brahmana weight
                    2 => 0, // Ksatria weight
                    3 => 0, // Waisya weight
                    4 => 0  // Sudra weight
                );
                
                // Isi profil user pertama
                foreach ($user1_answers as $answer) {
                    $user1_profile[$answer->kasta_indicator] += $answer->weight;
                }
                
                // Normalisasi profil user pertama (jika ada jawaban)
                $total_weight = array_sum($user1_profile);
                if ($total_weight > 0) {
                    foreach ($user1_profile as $kasta => $weight) {
                        $user1_profile[$kasta] = $weight / $total_weight;
                    }
                }
            }
            
            // Ambil jawaban user kedua
            $this->db->select('q.question_id, ao.option_id, ao.kasta_indicator, ao.weight');
            $this->db->from('useranswers ua');
            $this->db->join('questions q', 'ua.question_id = q.question_id');
            $this->db->join('answeroptions ao', 'ua.selected_option_id = ao.option_id');
            $this->db->where('ua.user_id', $user2_id);
            $user2_answers = $this->db->get()->result();
            
            // Buat profil user kedua
            $user2_profile = array(
                1 => 0, // Brahmana weight
                2 => 0, // Ksatria weight
                3 => 0, // Waisya weight
                4 => 0  // Sudra weight
            );
            
            // Isi profil user kedua
            foreach ($user2_answers as $answer) {
                $user2_profile[$answer->kasta_indicator] += $answer->weight;
            }
            
            // Normalisasi profil user kedua (jika ada jawaban)
            $total_weight = array_sum($user2_profile);
            if ($total_weight > 0) {
                foreach ($user2_profile as $kasta => $weight) {
                    $user2_profile[$kasta] = $weight / $total_weight;
                }
            }
            
            // Hitung posisi dalam skala 1-12 untuk kedua user
            $user1_position = $this->calculate_linear_position($user1_profile);
            $user2_position = $this->calculate_linear_position($user2_profile);
            
            // Hitung perbedaan posisi
            $position_diff = abs($user1_position - $user2_position);
            
            // Hitung match score dasar berdasarkan perbedaan posisi
            // Setiap perbedaan 1 tingkat = pengurangan 8.33%
            $base_match_score = 100 - ($position_diff * 8.33);
            
            // Pastikan dalam range 0-100
            $base_match_score = max(0, min(100, $base_match_score));
            
            // Terapkan faktor scaling berdasarkan kasta dan gender
            $scaling_factor = $this->calculate_kasta_scaling_factor($user1_position, $user2_position, $user1->gender);
            $match_score = $base_match_score * $scaling_factor;
            
            // Pastikan skor match selalu antara 1 dan 99 (tidak ada yang mutlak)
            $match_score = max(1, min(99, $match_score));
            
            // Simpan hasil scaling ke database
            $scaling_description = $this->get_scaling_description($scaling_factor, $user1_position, $user2_position, $user1->gender);
            $this->save_match($user1_id, $user2_id, $match_score, $scaling_factor, $scaling_description);
            
            return $match_score;
            
        } catch (Exception $e) {
            log_message('error', 'Error in calculate_match_score_with_clustering: ' . $e->getMessage());
            return 1; // Kembalikan nilai minimal 1% jika terjadi error
        }
    }
    
    /**
     * Menghitung posisi kasta dalam skala linear 1-12
     * 
     * @param array $profile Profil kasta pengguna (proporsi tiap kasta)
     * @return int Posisi dalam skala 1-12 (Brahmana Utama = 12, Sudra Dasar = 1)
     */
    private function calculate_linear_position($profile) {
        // Tentukan kasta dominan
        $dominant_kasta = 0;
        $dominant_proportion = 0;
        
        foreach ($profile as $kasta_id => $proportion) {
            if ($proportion > $dominant_proportion) {
                $dominant_kasta = $kasta_id;
                $dominant_proportion = $proportion;
            }
        }
        
        // Cek jika Sudra (kasta_id=4) memiliki proporsi tertinggi, prioritaskan
        if (isset($profile[4]) && $profile[4] >= 0.4) {
            $dominant_kasta = 4;
            $dominant_proportion = $profile[4];
        }
        
        // Konversi kasta dominan ke range posisi
        // Brahmana (kasta_id=1): posisi 10-12
        // Ksatria (kasta_id=2): posisi 7-9
        // Waisya (kasta_id=3): posisi 4-6
        // Sudra (kasta_id=4): posisi 1-3
        $base_position = 12 - (($dominant_kasta - 1) * 3);
        
        // Hitung sub-posisi berdasarkan proporsi kasta dominan
        if ($dominant_proportion >= 0.7) {
            // Dominasi kuat = level tertinggi (Utama)
            return $base_position;
        } else if ($dominant_proportion >= 0.5) {
            // Dominasi sedang = level menengah (Umum)
            return $base_position - 1;
        } else {
            // Dominasi lemah = level terendah (Dasar)
            return $base_position - 2;
        }
    }
    
    /**
     * Menghitung sub-kasta berdasarkan proporsi terhadap kasta utama
     * 
     * @param array $profile Profil kasta pengguna
     * @param int $dominant_kasta_id ID kasta dominan
     * @param float $dominant_weight Bobot kasta dominan
     * @return int Sub-kasta (1-12)
     */
    private function calculate_sub_kasta($profile, $dominant_kasta_id, $dominant_weight) {
        // Nilai dasarnya adalah (kasta_id - 1) * 3 + 1 sampai 3
        $base_sub_kasta = ($dominant_kasta_id - 1) * 3 + 1;
        
        // Hitung dominasi kasta utama terhadap total
        // Semakin dominan, semakin tinggi sub-kastanya (dalam range 3)
        if ($dominant_weight >= 0.8) {
            // Sangat dominan - sub-kasta tertinggi
            return $base_sub_kasta + 2;
        } else if ($dominant_weight >= 0.6) {
            // Cukup dominan - sub-kasta menengah
            return $base_sub_kasta + 1;
        } else {
            // Kurang dominan - sub-kasta terendah
            return $base_sub_kasta;
        }
    }
    
    /**
     * Mendapatkan nama sub-kasta berdasarkan ID sub-kasta
     * 
     * @param int $sub_kasta_id ID sub-kasta (1-12)
     * @return string Nama sub-kasta
     */
    public function get_sub_kasta_name($sub_kasta_id) {
        // Ambil data dari database
        $sub_kasta = $this->db->get_where('sub_kasta', ['sub_kasta_id' => $sub_kasta_id])->row();
        
        if ($sub_kasta) {
            return $sub_kasta->sub_kasta_name;
        }
        
        // Fallback jika data tidak ditemukan di database
        // Kasta utama (1-4)
        $main_kasta_id = ceil($sub_kasta_id / 3);
        
        // Level dalam kasta (1-3)
        $level_in_kasta = $sub_kasta_id - (($main_kasta_id - 1) * 3);
        
        // Dapatkan nama kasta utama
        $kasta_names = [
            1 => 'Brahmana',
            2 => 'Ksatria',
            3 => 'Waisya',
            4 => 'Sudra'
        ];
        
        $main_kasta_name = $kasta_names[$main_kasta_id] ?? 'Unknown';
        
        // Buat nama lengkap sub-kasta
        $level_names = [
            1 => 'Dasar',    // Dasar
            2 => 'Umum',     // Menengah
            3 => 'Utama'     // Tinggi
        ];
        
        $level_name = $level_names[$level_in_kasta] ?? '';
        
        return $main_kasta_name . ' ' . $level_name;
    }
    
    /**
     * Mendapatkan detail sub-kasta berdasarkan ID sub-kasta
     * 
     * @param int $sub_kasta_id ID sub-kasta (1-12)
     * @return object|null Data sub-kasta
     */
    public function get_sub_kasta_detail($sub_kasta_id) {
        return $this->db->get_where('sub_kasta', ['sub_kasta_id' => $sub_kasta_id])->row();
    }
    
    /**
     * Mendapatkan posisi kasta pengguna dalam skala 1-12
     * 
     * @param int $user_id ID pengguna
     * @return int|false Posisi dalam skala 1-12 (Brahmana Utama = 12, Sudra Dasar = 1)
     */
    public function get_user_sub_kasta($user_id) {
        try {
            // Pertama, cek jika sudah ada di database
            $user_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
            if ($user_kasta && isset($user_kasta->linear_position) && $user_kasta->linear_position !== null) {
                return $user_kasta->linear_position;
            }
            
            // Jika tidak ada di database atau linear_position masih NULL, hitung dari jawaban
            // Dapatkan jawaban user
            $this->db->select('q.question_id, ao.option_id, ao.kasta_indicator, ao.weight');
            $this->db->from('useranswers ua');
            $this->db->join('questions q', 'ua.question_id = q.question_id');
            $this->db->join('answeroptions ao', 'ua.selected_option_id = ao.option_id');
            $this->db->where('ua.user_id', $user_id);
            $user_answers = $this->db->get()->result();
            
            // Jika tidak ada jawaban, return false
            if (empty($user_answers)) {
                return false;
            }
            
            // Buat profil user
            $user_profile = array(
                1 => 0, // Brahmana weight
                2 => 0, // Ksatria weight
                3 => 0, // Waisya weight
                4 => 0  // Sudra weight
            );
            
            // Isi profil user
            foreach ($user_answers as $answer) {
                $user_profile[$answer->kasta_indicator] += $answer->weight;
            }
            
            // Normalisasi profil user
            $total_weight = array_sum($user_profile);
            if ($total_weight <= 0) {
                return false;
            }
            
            foreach ($user_profile as $kasta => $weight) {
                $user_profile[$kasta] = $weight / $total_weight;
            }
            
            // Hitung posisi kasta
            $linear_position = $this->calculate_linear_position($user_profile);
            
            // Simpan hasil ke database
            if ($user_kasta) {
                $this->db->where('user_id', $user_id);
                $this->db->update('userkastaresult', ['linear_position' => $linear_position]);
            }
            
            return $linear_position;
            
        } catch (Exception $e) {
            log_message('error', 'Error in get_user_sub_kasta: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mendapatkan nama kasta berdasarkan posisi dalam skala 1-12
     * 
     * @param int $position Posisi dalam skala 1-12
     * @return string Nama kasta
     */
    public function get_kasta_name_from_position($position) {
        // Convert position (1-12) to kasta name
        $kasta_names = [
            // Sudra (1-3)
            1 => 'Sudra Dasar',
            2 => 'Sudra Umum',
            3 => 'Sudra Utama',
            // Waisya (4-6)
            4 => 'Waisya Dasar',
            5 => 'Waisya Umum',
            6 => 'Waisya Utama',
            // Ksatria (7-9)
            7 => 'Ksatria Dasar',
            8 => 'Ksatria Umum',
            9 => 'Ksatria Utama',
            // Brahmana (10-12)
            10 => 'Brahmana Dasar',
            11 => 'Brahmana Umum',
            12 => 'Brahmana Utama'
        ];
        
        return $kasta_names[$position] ?? 'Tidak diketahui';
    }

    /**
     * Mendapatkan match score antara dua pengguna
     * 
     * @param int $user1_id ID pengguna pertama
     * @param int $user2_id ID pengguna kedua
     * @return float Match score (0-100)
     */
    public function get_match_score($user1_id, $user2_id) {
        try {
            // Cek apakah match sudah ada di database
            $this->db->where('(user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)', 
                        [$user1_id, $user2_id, $user2_id, $user1_id]);
            $match = $this->db->get('matches')->row();
            
            // Jika match sudah ada, return match_score
            if ($match) {
                return $match->match_score;
            }
            
            // Ambil data gender untuk kedua user
            $user1 = $this->db->get_where('users', ['user_id' => $user1_id])->row();
            $user2 = $this->db->get_where('users', ['user_id' => $user2_id])->row();
            
            if (!$user1 || !$user2) {
                log_message('debug', "One of the users not found: $user1_id or $user2_id");
                return 1; // Nilai minimum jika user tidak ditemukan
            }
            
            // Pastikan hanya lawan jenis yang bisa dicocokkan
            if ($user1->gender == $user2->gender) {
                log_message('debug', "Cannot match users with same gender: $user1_id and $user2_id");
                // Simpan hasil match ke database dengan nilai minimal
                $this->save_match($user1_id, $user2_id, 1);
                return 1; // Nilai minimum 1% untuk match score
            }
            
            // Jika belum ada, hitung berdasarkan posisi linear
            $user1_position = $this->get_user_sub_kasta($user1_id);
            $user2_position = $this->get_user_sub_kasta($user2_id);
            
            // Jika salah satu posisi tidak tersedia, gunakan kasta_id untuk perhitungan fallback
            if ($user1_position === false || $user2_position === false) {
                log_message('debug', "Linear position not available for one of the users, using fallback method.");
                
                // Ambil kasta dari database
                $user1_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user1_id])->row();
                $user2_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user2_id])->row();
                
                // Jika salah satu user belum memiliki kasta, kembalikan nilai minimal 1%
                if (!$user1_kasta || !$user2_kasta) {
                    // Simpan hasil match ke database
                    $this->save_match($user1_id, $user2_id, 1);
                    return 1; // Nilai minimum 1% (tidak ada yang mutlak)
                }
                
                // Gunakan kasta_id untuk perhitungan dasar
                $same_kasta = ($user1_kasta->kasta_id == $user2_kasta->kasta_id);
                $match_score = $same_kasta ? 80 : 50;
                
                // Pastikan dalam range 1-99
                $match_score = max(1, min(99, $match_score));
                
                // Simpan hasil match ke database
                $this->save_match($user1_id, $user2_id, $match_score);
                
                return $match_score;
            }
            
            // Hitung perbedaan posisi (0-11 tingkat)
            $position_diff = abs($user1_position - $user2_position);
            
            // Hitung match score berdasarkan perbedaan posisi
            // Setiap perbedaan 1 tingkat = pengurangan 8.33%
            $match_score = 100 - ($position_diff * 8.33);
            
            // Pastikan dalam range 1-99 (tidak ada yang mutlak)
            $match_score = max(1, min(99, $match_score));
            
            // Ambil gender user1 untuk perhitungan scaling
            $user1 = $this->db->get_where('users', ['user_id' => $user1_id])->row();
            if ($user1) {
                // Terapkan faktor scaling
                $scaling_factor = $this->calculate_kasta_scaling_factor($user1_position, $user2_position, $user1->gender);
                $match_score = $match_score * $scaling_factor;
                
                // Pastikan dalam range 1-99 lagi
                $match_score = max(1, min(99, $match_score));
                
                // Simpan dengan deskripsi scaling
                $scaling_description = $this->get_scaling_description($scaling_factor, $user1_position, $user2_position, $user1->gender);
                $this->save_match($user1_id, $user2_id, $match_score, $scaling_factor, $scaling_description);
            } else {
                // Simpan hasil match ke database tanpa scaling
                $this->save_match($user1_id, $user2_id, $match_score);
            }
            
            return $match_score;
        } catch (Exception $e) {
            log_message('error', 'Error in get_match_score: ' . $e->getMessage());
            return 50; // Default match score jika terjadi error
        }
    }

    /**
     * Menghitung faktor scaling berdasarkan perbedaan kasta dan gender
     * 
     * @param int $user_linear_position Posisi linear pengguna (1-12)
     * @param int $match_linear_position Posisi linear calon match (1-12)
     * @param string $user_gender Gender pengguna ('Male' atau 'Female')
     * @return float Faktor scaling (0.01-0.99) untuk pengaruh kasta pada match score
     */
    private function calculate_kasta_scaling_factor($user_linear_position, $match_linear_position, $user_gender) {
        // Hitung selisih posisi (nilai positif = match memiliki kasta lebih tinggi)
        $position_diff = $match_linear_position - $user_linear_position;
        
        // Jika user adalah laki-laki dan match adalah perempuan dengan kasta lebih tinggi
        if ($user_gender == 'Male' && $position_diff > 0) {
            // Semakin tinggi perbedaan kasta, semakin rendah peluang match
            // Formula: 1 - (perbedaan_kasta * faktor_penurunan)
            $reduction_factor = 0.07; // 10% penurunan per tingkat kasta (diturunkan dari 15%)
            $scaling = 1 - ($position_diff * $reduction_factor);
            // Pastikan tidak kurang dari 0.01 (1%) dan tidak lebih dari 0.99 (99%)
            return max(0.01, min(0.99, $scaling));
        }
        
        // Jika user adalah perempuan dan match adalah laki-laki dengan kasta lebih rendah
        else if ($user_gender == 'Female' && $position_diff < 0) {
            // Perempuan dengan kasta tinggi memiliki pengurangan peluang lebih kecil untuk match dengan laki-laki kasta lebih rendah
            $reduction_factor = 0.1; // 10% penurunan per tingkat kasta
            $scaling = 1 - (abs($position_diff) * $reduction_factor);
            // Pastikan tidak kurang dari 0.01 (1%) dan tidak lebih dari 0.99 (99%)
            return max(0.01, min(0.99, $scaling));
        }
        
        // Untuk kasus lainnya (kasta sama atau perempuan dengan kasta lebih rendah)
        return 0.99; // 99% maksimum, tidak ada yang mutlak
    }

    /**
     * Mendapatkan deskripsi dari faktor scaling untuk display di frontend
     * 
     * @param float $scaling_factor Faktor scaling (0.01-0.99)
     * @param int $user_linear_position Posisi linear pengguna
     * @param int $match_linear_position Posisi linear calon match
     * @param string $user_gender Gender pengguna ('Male' atau 'Female')
     * @return string Deskripsi faktor scaling
     */
    public function get_scaling_description($scaling_factor, $user_linear_position, $match_linear_position, $user_gender) {
        // Hitung selisih posisi
        $position_diff = $match_linear_position - $user_linear_position;
        $abs_diff = abs($position_diff);
        
        // Jika tidak ada perbedaan kasta
        if ($position_diff == 0) {
            return "Posisi kasta setara (peluang match 99%)";
        }
        
        // Jika laki-laki mencari perempuan dengan kasta lebih tinggi
        if ($user_gender == 'Male' && $position_diff > 0) {
            $user_kasta = $this->get_kasta_name_from_position($user_linear_position);
            $match_kasta = $this->get_kasta_name_from_position($match_linear_position);
            $percentage = round((1 - $scaling_factor) * 100);
            
            return "Sebagai $user_kasta mencari $match_kasta (${abs_diff} tingkat lebih tinggi), peluang match berkurang ${percentage}% (10% per tingkat)";
        }
        
        // Jika perempuan mencari laki-laki dengan kasta lebih rendah
        else if ($user_gender == 'Female' && $position_diff < 0) {
            $user_kasta = $this->get_kasta_name_from_position($user_linear_position);
            $match_kasta = $this->get_kasta_name_from_position($match_linear_position);
            $percentage = round((1 - $scaling_factor) * 100);
            
            return "Sebagai $user_kasta mencari $match_kasta (${abs_diff} tingkat lebih rendah), peluang match berkurang ${percentage}% (10% per tingkat)";
        }
        
        // Kasus lainnya
        else {
            if ($position_diff > 0) {
                return "Match memiliki kasta ${abs_diff} tingkat lebih tinggi (max 99% match)";
            } else {
                return "Match memiliki kasta ${abs_diff} tingkat lebih rendah (max 99% match)";
            }
        }
    }

    /**
     * Menghitung ulang posisi kasta user tanpa clustering
     * 
     * @param int $user_id ID pengguna
     * @return int|false Posisi baru dalam skala 1-12 atau false jika gagal
     */
    public function recalculate_user_kasta($user_id) {
        try {
            // Dapatkan jawaban user
            $this->db->select('q.question_id, ao.option_id, ao.kasta_indicator, ao.weight');
            $this->db->from('useranswers ua');
            $this->db->join('questions q', 'ua.question_id = q.question_id');
            $this->db->join('answeroptions ao', 'ua.selected_option_id = ao.option_id');
            $this->db->where('ua.user_id', $user_id);
            $user_answers = $this->db->get()->result();
            
            // Jika tidak ada jawaban, return false
            if (empty($user_answers)) {
                return false;
            }
            
            // Buat profil user
            $user_profile = array(
                1 => 0, // Brahmana weight
                2 => 0, // Ksatria weight
                3 => 0, // Waisya weight
                4 => 0  // Sudra weight
            );
            
            // Isi profil user
            foreach ($user_answers as $answer) {
                // Berikan bobot ekstra untuk kasta Sudra
                if ($answer->kasta_indicator == 4) {
                    $user_profile[$answer->kasta_indicator] += $answer->weight * 1.5;
                } else {
                    $user_profile[$answer->kasta_indicator] += $answer->weight;
                }
            }
            
            // Log profil sebelum normalisasi
            log_message('debug', 'User profile before normalization: ' . json_encode($user_profile));
            
            // Normalisasi profil user
            $total_weight = array_sum($user_profile);
            if ($total_weight <= 0) {
                return false;
            }
            
            foreach ($user_profile as $kasta => $weight) {
                $user_profile[$kasta] = $weight / $total_weight;
            }
            
            // Log profil setelah normalisasi
            log_message('debug', 'User profile after normalization: ' . json_encode($user_profile));
            
            // Hitung posisi kasta menggunakan fungsi yang sudah dimodifikasi
            $linear_position = $this->calculate_linear_position($user_profile);
            
            // Log hasil posisi linear
            log_message('debug', 'Calculated linear position: ' . $linear_position);
            
            // Simpan hasil ke database
            $user_kasta = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
            if ($user_kasta) {
                $this->db->where('user_id', $user_id);
                $this->db->update('userkastaresult', ['linear_position' => $linear_position]);
            } else {
                // Jika belum ada hasil kasta, buat baru
                $predicted_kasta = 4; // Default ke Sudra jika tidak dapat ditentukan
                if ($linear_position >= 10) {
                    $predicted_kasta = 1; // Brahmana
                } else if ($linear_position >= 7) {
                    $predicted_kasta = 2; // Ksatria
                } else if ($linear_position >= 4) {
                    $predicted_kasta = 3; // Waisya
                }
                
                $this->db->insert('userkastaresult', [
                    'user_id' => $user_id,
                    'kasta_id' => $predicted_kasta,
                    'confidence_score' => 100 * $user_profile[$predicted_kasta],
                    'linear_position' => $linear_position,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            return $linear_position;
            
        } catch (Exception $e) {
            log_message('error', 'Error in recalculate_user_kasta: ' . $e->getMessage());
            return false;
        }
    }
} 