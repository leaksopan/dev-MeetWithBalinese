<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Quiz Model
 * 
 * Model untuk mengelola data quiz dan pertanyaan
 * 
 * @package     MeetWithBalinese
 * @subpackage  Models
 * @category    Quiz
 * @author      MeetWithBalinese Team
 */
class Quiz_model extends CI_Model {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Mendapatkan semua pertanyaan aktif
     * 
     * @return array Daftar pertanyaan dengan opsi jawaban
     */
    public function get_questions() {
        // Mencoba query database
        try {
            // Cek apakah tabel questions ada dengan query langsung ke information_schema
            $query = $this->db->query("SELECT COUNT(*) as count FROM information_schema.tables 
                                      WHERE table_schema = DATABASE() 
                                      AND table_name = 'questions'");
            $result = $query->row();
            
            if ($result->count == 0) {
                // Tabel tidak ada, kembalikan data dummy
                return $this->get_dummy_questions();
            }
            
            // Cek apakah tabel answeroptions ada
            $query = $this->db->query("SELECT COUNT(*) as count FROM information_schema.tables 
                                      WHERE table_schema = DATABASE() 
                                      AND table_name = 'answeroptions'");
            $result = $query->row();
            
            if ($result->count == 0) {
                // Tabel tidak ada, kembalikan data dummy
                return $this->get_dummy_questions();
            }
            
            // Jika tabel ada, ambil pertanyaan
            $this->db->where('is_active', 1);
            $questions = $this->db->get('questions')->result();
            
            // Jika tidak ada pertanyaan, kembalikan data dummy
            if (empty($questions)) {
                return $this->get_dummy_questions();
            }

            // Ambil opsi jawaban untuk setiap pertanyaan
            foreach ($questions as $key => $question) {
                $this->db->where('question_id', $question->question_id);
                $questions[$key]->options = $this->db->get('answeroptions')->result();
            }

            return $questions;
        } catch (Exception $e) {
            // Kembalikan data dummy jika terjadi error
            log_message('error', 'Database error in get_questions: ' . $e->getMessage());
            return $this->get_dummy_questions();
        }
    }
    
    /**
     * Mendapatkan pertanyaan dummy untuk development
     * 
     * @return array Daftar pertanyaan dummy
     */
    private function get_dummy_questions() {
        $questions = [];
        
        // Buat 4 pertanyaan dummy
        for ($i = 1; $i <= 4; $i++) {
            $question = new stdClass();
            $question->question_id = $i;
            $question->question_text = "Pertanyaan dummy #$i untuk testing?";
            $question->is_active = 1;
            $question->options = [];
            
            // Buat 4 opsi jawaban per pertanyaan
            for ($j = 1; $j <= 4; $j++) {
                $option = new stdClass();
                $option->option_id = ($i-1) * 4 + $j;
                $option->question_id = $i;
                $option->option_text = "Opsi jawaban #$j untuk pertanyaan #$i";
                $option->kasta_indicator = $j;
                $option->weight = 2;
                
                $question->options[] = $option;
            }
            
            $questions[] = $question;
        }
        
        return $questions;
    }

    /**
     * Simpan jawaban pengguna
     * 
     * @param int $user_id ID pengguna
     * @param array $answers Array jawaban (question_id => option_id)
     * @return bool TRUE jika berhasil, FALSE jika gagal
     */
    public function save_answers($user_id, $answers) {
        $this->load->database();
        $success = true;
        
        // Log untuk debugging
        log_message('debug', 'Attempting to save answers for user_id: ' . $user_id . ', answers: ' . json_encode($answers));
        
        // Hapus jawaban lama jika ada
        $this->db->where('user_id', $user_id);
        $this->db->delete('useranswers');
        log_message('debug', 'Deleted old answers for user_id: ' . $user_id);
        
        try {
            // Simpan jawaban baru
            foreach ($answers as $question_id => $option_id) {
                $data = array(
                    'user_id' => $user_id,
                    'question_id' => $question_id,
                    'selected_option_id' => $option_id
                );
                
                log_message('debug', 'Inserting answer: ' . json_encode($data));
                if (!$this->db->insert('useranswers', $data)) {
                    $success = false;
                    log_message('error', 'Failed to insert answer: ' . $this->db->error()['message']);
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Exception when saving answers: ' . $e->getMessage());
            $success = false;
        }
        
        // Hitung dan simpan hasil kasta jika berhasil menyimpan jawaban
        if ($success) {
            $this->calculate_kasta_result($user_id);
        }
        
        return $success;
    }

    /**
     * Hitung hasil kasta berdasarkan jawaban user
     * 
     * @param int $user_id ID pengguna
     * @return int|bool ID kasta atau FALSE jika gagal
     */
    public function calculate_kasta_result($user_id) {
        $this->load->database();
        
        // Log untuk debugging
        log_message('debug', 'Calculating kasta result for user_id: ' . $user_id);
        
        // Default kasta jika terjadi error
        $default_kasta = 1; // Default: Brahmana
        
        try {
            // Ambil semua jawaban user dengan bobot dan indikator kasta
            $this->db->select('answeroptions.kasta_indicator, answeroptions.weight');
            $this->db->from('useranswers');
            $this->db->join('answeroptions', 'useranswers.selected_option_id = answeroptions.option_id');
            $this->db->where('useranswers.user_id', $user_id);
            $user_answers = $this->db->get()->result();
            
            log_message('debug', 'Found ' . count($user_answers) . ' answers for user_id: ' . $user_id);
            
            // Jika tidak ada jawaban, beri kasta default
            if (empty($user_answers)) {
                $result_data = array(
                    'user_id' => $user_id,
                    'kasta_id' => $default_kasta,
                    'confidence_score' => 0
                );
                
                // Simpan hasilnya ke userkastaresult
                $this->db->where('user_id', $user_id);
                $existing = $this->db->get('userkastaresult')->row();
                
                if ($existing) {
                    $this->db->where('user_id', $user_id);
                    $this->db->update('userkastaresult', $result_data);
                } else {
                    $this->db->insert('userkastaresult', $result_data);
                }
                log_message('debug', 'No answers found, saved default kasta for user_id: ' . $user_id);
                
                return $default_kasta;
            }

            // Hitung total bobot untuk setiap kasta
            $kasta_scores = array(
                1 => 0, // Brahmana
                2 => 0, // Ksatria
                3 => 0, // Waisya
                4 => 0  // Sudra
            );

            $total_weight = 0;
            
            foreach ($user_answers as $answer) {
                $kasta_scores[$answer->kasta_indicator] += $answer->weight;
                $total_weight += $answer->weight;
            }

            // Tentukan kasta dengan skor tertinggi
            $max_score = 0;
            $predicted_kasta = 0;
            
            foreach ($kasta_scores as $kasta_id => $score) {
                if ($score > $max_score) {
                    $max_score = $score;
                    $predicted_kasta = $kasta_id;
                }
            }

            // Jika tidak ada kasta yang terprediksi, beri default
            if ($predicted_kasta == 0) {
                $predicted_kasta = $default_kasta;
            }

            // Hitung confidence score (0-100)
            $confidence_score = ($total_weight > 0) ? ($max_score / $total_weight) * 100 : 0;

            // Normalisasi profil pengguna untuk perhitungan posisi linear
            $user_profile = array();
            foreach ($kasta_scores as $kasta_id => $score) {
                $user_profile[$kasta_id] = ($total_weight > 0) ? $score / $total_weight : 0;
            }
            
            // Load model Match_model untuk akses ke fungsi perhitungan posisi
            $this->load->model('match_model');
            
            // Buat profil pengguna berdasarkan persentase bobot kasta
            $user_profile = array();
            foreach ($kasta_scores as $kasta_id => $score) {
                $user_profile[$kasta_id] = ($total_weight > 0) ? $score / $total_weight : 0;
            }
            
            // Reset linear position to force recalculation
            $this->db->where('user_id', $user_id);
            $this->db->update('userkastaresult', ['linear_position' => null]);
            
            // Hitung posisi linear baru
            $linear_position = $this->match_model->recalculate_user_kasta($user_id);
            
            // Jika linear_position masih null, gunakan get_user_sub_kasta
            if ($linear_position === false) {
                $linear_position = $this->match_model->get_user_sub_kasta($user_id);
            }
            
            // Hitung sub-kasta
            $dominant_kasta_id = $predicted_kasta;
            $dominant_weight = ($total_weight > 0) ? $max_score / $total_weight : 0;
            
            // Simpan hasil ke database
            $result_data = array(
                'user_id' => $user_id,
                'kasta_id' => $predicted_kasta,
                'confidence_score' => $confidence_score,
                'linear_position' => $linear_position,
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Simpan hasilnya ke userkastaresult
            $this->db->where('user_id', $user_id);
            $existing = $this->db->get('userkastaresult')->row();
            
            if ($existing) {
                $this->db->where('user_id', $user_id);
                $this->db->update('userkastaresult', $result_data);
            } else {
                $result_data['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('userkastaresult', $result_data);
            }
            
            log_message('debug', 'Calculated and saved kasta result for user_id: ' . $user_id . ' with kasta_id: ' . $predicted_kasta . ' and linear_position: ' . $linear_position);
            
            return $predicted_kasta;
        } catch (Exception $e) {
            log_message('error', 'Error in calculate_kasta_result: ' . $e->getMessage());
            return $default_kasta;
        }
    }

    /**
     * Dapatkan detail kasta berdasarkan ID
     * 
     * @param int $kasta_id ID kasta
     * @return object Data kasta
     */
    public function get_kasta_detail($kasta_id) {
        $this->load->database();
        
        try {
            $this->db->where('kasta_id', $kasta_id);
            $kasta = $this->db->get('kastacategory')->row();
            
            // Jika tidak ada data kasta, kembalikan data dummy
            if (!$kasta) {
                return $this->get_dummy_kasta($kasta_id);
            }
            
            return $kasta;
        } catch (Exception $e) {
            log_message('error', 'Error getting kasta detail: ' . $e->getMessage());
            return $this->get_dummy_kasta($kasta_id);
        }
    }
    
    /**
     * Mendapatkan data kasta dummy
     * 
     * @param int $kasta_id ID kasta
     * @return object Data kasta dummy
     */
    private function get_dummy_kasta($kasta_id) {
        $kasta_names = [
            1 => 'Brahmana',
            2 => 'Ksatria',
            3 => 'Waisya',
            4 => 'Sudra'
        ];
        
        $kasta_descriptions = [
            1 => 'Kasta tertinggi yang berkaitan dengan spiritualitas dan pendidikan.',
            2 => 'Kasta yang berkaitan dengan kepemimpinan dan kekuasaan.',
            3 => 'Kasta yang berkaitan dengan perdagangan dan ekonomi.',
            4 => 'Kasta yang berkaitan dengan pekerja dan masyarakat umum.'
        ];
        
        $kasta = new stdClass();
        $kasta->kasta_id = $kasta_id;
        $kasta->kasta_name = $kasta_names[$kasta_id] ?? 'Unknown';
        $kasta->description = $kasta_descriptions[$kasta_id] ?? 'Tidak ada deskripsi';
        
        return $kasta;
    }

    /**
     * Mendapatkan jawaban user dari database
     * 
     * @param int $user_id ID pengguna
     * @return array Jawaban pengguna
     */
    public function get_user_answers($user_id) {
        try {
            $this->db->select('q.question_id, ao.option_id, ao.kasta_indicator, ao.weight');
            $this->db->from('useranswers ua');
            $this->db->join('questions q', 'ua.question_id = q.question_id');
            $this->db->join('answeroptions ao', 'ua.selected_option_id = ao.option_id');
            $this->db->where('ua.user_id', $user_id);
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_user_answers: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Simpan hasil kasta pengguna
     * 
     * @param int $user_id ID pengguna
     * @param array $user_answers Jawaban pengguna (hasil dari get_user_answers)
     * @param int $default_kasta Kasta default jika tidak dapat diprediksi
     * @return bool Hasil operasi
     */
    public function save_kasta_result($user_id, $user_answers = null, $default_kasta = 2) {
        try {
            // Jika user_answers tidak disediakan, ambil dari database
            if ($user_answers === null) {
                $user_answers = $this->get_user_answers($user_id);
            }
            
            // Jika tidak ada jawaban, kembalikan false
            if (empty($user_answers)) {
                return false;
            }
            
            // Menggunakan calculate_kasta_result untuk menghindari duplikasi kode
            $kasta_id = $this->calculate_kasta_result($user_id);
            
            return ($kasta_id !== false);
        } catch (Exception $e) {
            log_message('error', 'Error in save_kasta_result: ' . $e->getMessage());
            return false;
        }
    }
} 