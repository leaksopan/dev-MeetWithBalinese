<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Quiz Controller
 * 
 * Controller untuk mengelola quiz dan pertanyaan untuk menentukan kasta
 * 
 * @package     MeetWithBalinese
 * @subpackage  Controllers
 * @category    Quiz
 * @author      MeetWithBalinese Team
 */
class Quiz extends CI_Controller {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        $this->load->model(['quiz_model', 'user_model']);
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session']);
        $this->load->database();
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    /**
     * Halaman quiz
     */
    public function index() {
        // Ambil semua pertanyaan quiz
        $data['questions'] = $this->quiz_model->get_questions();
        
        // Load view
        $this->load->view('templates/header');
        $this->load->view('quiz/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Proses submit jawaban quiz
     */
    public function submit() {
        // Cek apakah ada POST data
        if ($this->input->post()) {
            $user_id = $this->session->userdata('user_id');
            $answers = $this->input->post('answers');
            
            // Log untuk debugging
            log_message('debug', 'Quiz submitted by user_id: ' . $user_id . ', answers: ' . json_encode($answers));
            
            // Validasi data
            if (empty($answers) || !is_array($answers)) {
                $this->session->set_flashdata('error', 'Harap jawab semua pertanyaan.');
                log_message('debug', 'Quiz submission failed: no answers or not an array');
                redirect('quiz');
            }
            
            // Simpan jawaban
            $saved = $this->quiz_model->save_answers($user_id, $answers);
            
            if (!$saved) {
                $this->session->set_flashdata('error', 'Gagal menyimpan jawaban. Silakan coba lagi.');
                log_message('debug', 'Quiz submission failed: could not save answers');
                redirect('quiz');
            }
            
            // Hitung hasil kasta
            $kasta_id = $this->quiz_model->calculate_kasta_result($user_id);
            log_message('debug', 'Kasta result calculated for user_id: ' . $user_id . ', kasta_id: ' . $kasta_id);
            
            // Load model match untuk recalculate match scores
            $this->load->model('match_model');
            
            // Update match scores untuk user ini
            $this->db->where('user_id_1', $user_id)->or_where('user_id_2', $user_id);
            $this->db->delete('matches');
            log_message('debug', 'Deleted old matches for user_id: ' . $user_id);
            
            // Redirect langsung ke halaman matches
            $this->session->set_flashdata('success', 'Quiz berhasil diselesaikan! Kasta dan kecocokan telah diperbarui.');
            redirect('matches');
        } else {
            log_message('debug', 'Quiz submission failed: no POST data');
            redirect('quiz');
        }
    }

    /**
     * Halaman hasil quiz
     */
    public function result() {
        $user_id = $this->session->userdata('user_id');
        
        // Ambil hasil kasta user
        if ($this->db->table_exists('userkastaresult')) {
            $result = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
        } else {
            $result = null;
        }
        
        if (!$result) {
            $this->session->set_flashdata('error', 'Anda belum mengikuti quiz.');
            redirect('quiz');
        }
        
        // Ambil detail kasta
        $data['kasta'] = $this->quiz_model->get_kasta_detail($result->kasta_id);
        $data['confidence_score'] = $result->confidence_score;
        
        // Ambil informasi posisi linear
        $this->load->model('match_model');
        
        // Gunakan linear_position dari database jika ada, atau hitung jika belum ada
        $linear_position = isset($result->linear_position) ? $result->linear_position : $this->match_model->get_user_sub_kasta($user_id);
        
        if ($linear_position) {
            $data['linear_position'] = $linear_position;
            $data['linear_position_name'] = $this->match_model->get_kasta_name_from_position($linear_position);
            
            // Simpan informasi linear_position jika belum ada di database
            if (!isset($result->linear_position)) {
                $this->db->where('user_id', $user_id);
                $this->db->update('userkastaresult', ['linear_position' => $linear_position]);
            }
        }
        
        // Load view
        $this->load->view('templates/header');
        $this->load->view('quiz/result', $data);
        $this->load->view('templates/footer');
    }
} 