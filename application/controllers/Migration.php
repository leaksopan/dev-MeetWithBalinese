<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration Controller
 * 
 * Controller untuk mengelola migrasi database
 * 
 * @package     MeetWithBalinese
 * @subpackage  Controllers
 * @category    Migration
 * @author      MeetWithBalinese Team
 */
class Migration extends CI_Controller {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'file']);
        $this->load->library(['session']);
        $this->load->database();
        
        // Hanya admin yang bisa akses
        if ($this->session->userdata('user_role') != 'admin') {
            $this->session->set_flashdata('error', 'Hanya admin yang bisa mengakses halaman ini.');
            redirect('home');
        }
    }

    /**
     * Halaman utama
     */
    public function index() {
        $data['migrations'] = $this->get_available_migrations();
        
        $this->load->view('templates/header');
        $this->load->view('migration/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Jalankan migrasi tertentu
     * 
     * @param string $migration_file Nama file migrasi
     */
    public function run($migration_file = '') {
        if (empty($migration_file)) {
            $this->session->set_flashdata('error', 'File migrasi tidak ditemukan.');
            redirect('migration');
        }

        $migration_path = APPPATH . 'migrations/' . $migration_file;
        
        if (!file_exists($migration_path)) {
            $this->session->set_flashdata('error', 'File migrasi tidak ditemukan.');
            redirect('migration');
        }

        // Baca file SQL
        $sql = file_get_contents($migration_path);
        
        // Jalankan SQL
        $queries = explode(';', $sql);
        $success = true;
        $error_messages = [];
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                try {
                    $this->db->query($query);
                } catch (Exception $e) {
                    $success = false;
                    $error_messages[] = $e->getMessage();
                }
            }
        }

        if ($success) {
            $this->session->set_flashdata('success', 'Migrasi berhasil dijalankan.');
        } else {
            $this->session->set_flashdata('error', 'Migrasi gagal: ' . implode(', ', $error_messages));
        }
        
        redirect('migration');
    }

    /**
     * Jalankan migrasi add_linear_position
     */
    public function add_updated_at() {
        $this->run('add_updated_at_column.sql');
    }

    /**
     * Mendapatkan daftar file migrasi yang tersedia
     * 
     * @return array Daftar file migrasi
     */
    private function get_available_migrations() {
        $migrations = [];
        $migration_path = APPPATH . 'migrations/';
        
        if (is_dir($migration_path)) {
            $files = scandir($migration_path);
            
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                    $migrations[] = $file;
                }
            }
        }
        
        return $migrations;
    }
} 