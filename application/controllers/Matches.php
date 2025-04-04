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
        
        if (!$user_id) {
            redirect('auth/login');
        }
        
        // Cek apakah user sudah mengikuti quiz
        $result = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
        if (!$result) {
            $this->session->set_flashdata('error', 'Anda harus mengikuti quiz terlebih dahulu sebelum melihat matches.');
            redirect('quiz');
        }
        
        // Load model match
        $this->load->model('match_model');
        
        try {
            // Cek apakah tabel userkastaresult ada
            $tables = $this->db->list_tables();
            $kasta_result_table_exists = in_array('userkastaresult', array_map('strtolower', $tables));
            
            if ($kasta_result_table_exists) {
                $result = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
            }
            
            // Ambil daftar matches dari model
            $data['matches'] = $this->match_model->find_matches($user_id);
            
            // Ambil posisi linear user
            if (isset($result->linear_position)) {
                $user_linear_position = $result->linear_position;
            } else {
                $user_linear_position = $this->match_model->get_user_sub_kasta($user_id);
            }
            
            if ($user_linear_position) {
                $data['user_linear_position'] = $user_linear_position;
                $data['user_linear_position_name'] = $this->match_model->get_kasta_name_from_position($user_linear_position);
            }
            
            // Tambahkan informasi posisi linear untuk setiap match
            if (!empty($data['matches'])) {
                foreach ($data['matches'] as $key => $match) {
                    // Gunakan linear_position dari database jika ada
                    if (isset($match->linear_position)) {
                        $match_linear_position = $match->linear_position;
                    } else {
                        $match_linear_position = $this->match_model->get_user_sub_kasta($match->user_id);
                    }
                    
                    if ($match_linear_position) {
                        $data['matches'][$key]->linear_position = $match_linear_position;
                        $data['matches'][$key]->linear_position_name = $this->match_model->get_kasta_name_from_position($match_linear_position);
                    }
                }
            }
            
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
            log_message('error', 'Error in Matches/index: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memuat data kecocokan.');
            redirect('dashboard');
        }
    }

    /**
     * Halaman detail match
     * 
     * @param int $match_user_id ID pengguna yang dicocokkan
     */
    public function detail($match_user_id) {
        $user_id = $this->session->userdata('user_id');
        
        if (!$user_id) {
            redirect('auth/login');
        }
        
        // Cek apakah user sudah mengikuti quiz
        $result = $this->db->get_where('userkastaresult', ['user_id' => $user_id])->row();
        if (!$result) {
            $this->session->set_flashdata('error', 'Anda harus mengikuti quiz terlebih dahulu sebelum melihat detail matches.');
            redirect('quiz');
        }
        
        // Load model user
        $this->load->model('user_model');
        $this->load->model('match_model');
        
        // Ambil info pengguna yang dicocokkan
        $data['match_user'] = $this->user_model->get_user_detail($match_user_id);
        
        if (!$data['match_user']) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('matches');
        }
        
        // Hitung match score
        $match_score = $this->match_model->get_match_score($user_id, $match_user_id);
        $data['match_score'] = $match_score;
        
        // Tambahkan info posisi linear
        $match_linear_position = $this->match_model->get_user_sub_kasta($match_user_id);
        if ($match_linear_position) {
            $data['match_user_linear_position'] = $match_linear_position;
            $data['match_user_linear_position_name'] = $this->match_model->get_kasta_name_from_position($match_linear_position);
        }
        
        // Ambil info posisi linear dari pengguna saat ini
        $user_linear_position = $this->match_model->get_user_sub_kasta($user_id);
        if ($user_linear_position) {
            $data['user_linear_position'] = $user_linear_position;
            $data['user_linear_position_name'] = $this->match_model->get_kasta_name_from_position($user_linear_position);
        }
        
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

    /**
     * Halaman debug untuk melihat status posisi linear
     */
    public function debug() {
        // Hanya admin yang bisa mengakses halaman ini
        if ($this->session->userdata('user_role') != 'admin') {
            redirect('matches');
        }
        
        $this->load->model('match_model');
        
        // Ambil semua user dengan hasil kasta
        $this->db->select('u.user_id, u.username, u.gender, ukr.kasta_id, ukr.confidence_score, ukr.linear_position');
        $this->db->from('users u');
        $this->db->join('userkastaresult ukr', 'u.user_id = ukr.user_id', 'left');
        $data['users'] = $this->db->get()->result();
        
        // Untuk setiap user, tambahkan informasi nama kasta dan posisi linear
        foreach ($data['users'] as $key => $user) {
            // Load model quiz untuk mendapatkan nama kasta
            $this->load->model('quiz_model');
            $data['users'][$key]->kasta_name = '';
            
            if (isset($user->kasta_id)) {
                $kasta = $this->quiz_model->get_kasta_detail($user->kasta_id);
                $data['users'][$key]->kasta_name = $kasta->kasta_name;
            }
            
            // Tambahkan nama posisi linear jika ada
            if (isset($user->linear_position) && $user->linear_position !== null) {
                $data['users'][$key]->linear_position_name = $this->match_model->get_kasta_name_from_position($user->linear_position);
            } else {
                $data['users'][$key]->linear_position_name = 'Belum dihitung';
            }
        }
        
        // Load view
        $this->load->view('templates/header');
        $this->load->view('matches/debug', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Recalculate posisi linear user
     * 
     * @param int $user_id ID user
     */
    public function recalculate($user_id) {
        // Pastikan user_id valid
        if (!$user_id || !is_numeric($user_id)) {
            $this->session->set_flashdata('error', 'User ID tidak valid.');
            redirect('matches/debug');
        }
        
        // Load model
        $this->load->model('match_model');
        
        // Force recalculate dengan menghapus nilai linear_position yang ada
        $this->db->where('user_id', $user_id);
        $this->db->update('userkastaresult', ['linear_position' => null]);
        
        // Hitung ulang posisi linear tanpa clustering
        $linear_position = $this->match_model->recalculate_user_kasta($user_id);
        
        if ($linear_position !== false) {
            // Ambil nama kasta
            $kasta_name = $this->match_model->get_kasta_name_from_position($linear_position);
            $this->session->set_flashdata('success', 'Posisi linear berhasil dihitung ulang: ' . $linear_position . ' (' . $kasta_name . ')');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghitung ulang posisi linear.');
        }
        
        redirect('matches/debug');
    }
} 