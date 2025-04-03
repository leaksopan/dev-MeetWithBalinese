<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home Controller
 * 
 * Controller untuk halaman utama
 * 
 * @package     MeetWithBalinese
 * @subpackage  Controllers
 * @category    Home
 * @author      MeetWithBalinese Team
 */
class Home extends CI_Controller {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(['url']);
        $this->load->library(['session']);
    }

    /**
     * Halaman utama
     */
    public function index() {
        // Jika belum login, redirect ke halaman login
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
        
        // Ambil data user dari session
        $data['user'] = $this->session->userdata();
        
        // Load view
        $this->load->view('templates/header');
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer');
    }
} 