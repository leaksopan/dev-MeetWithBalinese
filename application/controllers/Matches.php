<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Matches Controller
 * 
 * Controller untuk mengelola pencocokan pengguna
 * 
 * @package     MeetWithBalinese
 * @subpackage  Controllers
 * @category    Matches
 * @author      MeetWithBalinese Team
 */
class Matches extends CI_Controller {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        $this->load->model(['match_model', 'user_model']);
        $this->load->helper(['url', 'form']);
        $this->load->library(['session']);
        $this->load->database();
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    /**
     * Halaman utama matches
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        // Cek apakah user sudah mengikuti quiz
        $result = null;
        
        try {
            // Cek apakah tabel userkastaresult ada
            $tables = $this->db->list_tables();
            $kasta_result_table_exists = in_array('userkastaresult', array_map('strtolower', $tables));
            
            if ($kasta_result_table_exists) {
                $result = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
            }
            
            // Ambil daftar matches dari model
            $data['matches'] = $this->match_model->find_matches($user_id);
            
            // Periksa jika tidak ada kecocokan atau user belum mengisi quiz
            if (empty($data['matches'])) {
                $this->session->set_flashdata('info', 'Belum ada pengguna lain yang cocok dengan Anda. Silakan coba lagi nanti.');
            } else if (!$result) {
                $this->session->set_flashdata('info', 'Anda belum mengikuti quiz, tapi berikut beberapa kecocokan potensial untuk Anda.');
            }
            
            // Debug info
            log_message('debug', 'Match data for user ' . $user_id . ': ' . json_encode($data['matches']));
            
            // Load view
            $this->load->view('templates/header');
            $this->load->view('matches/index', $data);
            $this->load->view('templates/footer');
        } catch (Exception $e) {
            log_message('error', 'Error in Matches controller: ' . $e->getMessage());
            
            // Jika terjadi error, tampilkan pesan
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
            redirect('home');
        }
    }

    /**
     * Halaman detail match
     * 
     * @param int $match_user_id ID pengguna yang di-match
     */
    public function detail($match_user_id) {
        $user_id = $this->session->userdata('user_id');
        
        // Validasi parameter
        if (!$match_user_id || !is_numeric($match_user_id)) {
            redirect('matches');
        }
        
        // Ambil data user yang di-match
        $data['match_user'] = $this->user_model->get_user_by_id($match_user_id);
        
        if (!$data['match_user']) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('matches');
        }
        
        // Ambil data match dari model
        $data['match'] = $this->match_model->get_match_by_users($user_id, $match_user_id);
        
        // Load view
        $this->load->view('templates/header');
        $this->load->view('matches/detail', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Aksi menerima match
     * 
     * @param int $match_id ID match
     */
    public function accept($match_id) {
        // Validasi parameter
        if (!$match_id || !is_numeric($match_id)) {
            redirect('matches');
        }
        
        // Update status match
        $this->match_model->update_match_status($match_id, 'accepted');
        
        $this->session->set_flashdata('success', 'Match diterima.');
        redirect('matches');
    }

    /**
     * Aksi menolak match
     * 
     * @param int $match_id ID match
     */
    public function reject($match_id) {
        // Validasi parameter
        if (!$match_id || !is_numeric($match_id)) {
            redirect('matches');
        }
        
        // Update status match
        $this->match_model->update_match_status($match_id, 'rejected');
        
        $this->session->set_flashdata('success', 'Match ditolak.');
        redirect('matches');
    }
} 