<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 * 
 * Controller untuk pengelolaan autentikasi (login, register, logout)
 * 
 * @package     MeetWithBalinese
 * @subpackage  Controllers
 * @category    Auth
 * @author      MeetWithBalinese Team
 */
class Auth extends MY_Controller {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        // Model, library dan helper sudah dimuat melalui autoload
    }

    /**
     * Halaman login
     */
    public function index() {
        // Redirect ke halaman home jika sudah login
        if ($this->session->userdata('user_id')) {
            redirect('home');
        }
        
        $this->load->view('auth/login');
    }

    /**
     * Proses login
     */
    public function login() {
        // Set aturan validasi
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke form login dengan error
            $this->load->view('auth/login');
        } else {
            // Ambil input dari form
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Cek login
            $user = $this->user_model->login($email, $password);

            if ($user) {
                // Set session data
                $user_data = [
                    'user_id' => $user->user_id,
                    'email' => $user->email,
                    'instagram' => $user->instagram,
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($user_data);

                // Redirect ke halaman utama
                redirect('home');
            } else {
                // Login gagal
                $this->session->set_flashdata('error', 'Email atau password salah');
                $this->load->view('auth/login');
            }
        }
    }

    /**
     * Halaman registrasi
     */
    public function register() {
        // Redirect ke halaman home jika sudah login
        if ($this->session->userdata('user_id')) {
            redirect('home');
        }
        
        $this->load->view('auth/register');
    }

    /**
     * Proses registrasi
     */
    public function process_register() {
        // Set aturan validasi
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('instagram', 'Instagram', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('age', 'Age', 'trim|required|numeric');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke form register dengan error
            $this->load->view('auth/register');
        } else {
            // Ambil input dari form
            $data = [
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'instagram' => $this->input->post('instagram'),
                'username' => $this->input->post('username'),
                'age' => $this->input->post('age'),
                'gender' => $this->input->post('gender')
            ];

            // Cek email exists secara manual
            $this->load->database();
            $existing_email = $this->db->get_where('users', ['email' => $data['email']])->row();
            if ($existing_email) {
                $this->session->set_flashdata('error', 'Email sudah terdaftar. Silakan gunakan email lain.');
                $this->load->view('auth/register');
                return;
            }

            // Simpan data user baru
            $user_id = $this->user_model->register($data);

            if ($user_id) {
                // Set pesan sukses
                $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
                redirect('auth');
            } else {
                // Registrasi gagal
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
                $this->load->view('auth/register');
            }
        }
    }

    /**
     * Proses logout
     */
    public function logout() {
        // Hapus semua data session
        $this->session->sess_destroy();

        // Redirect ke halaman login
        redirect('auth');
    }
} 