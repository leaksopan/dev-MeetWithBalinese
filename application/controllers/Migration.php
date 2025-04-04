<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration Controller
 * 
 * Controller untuk melakukan migrasi database
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
        $this->load->database();
    }
    
    /**
     * Index
     * 
     * Menampilkan daftar migrasi yang tersedia
     */
    public function index() {
        echo "<h1>Migrations</h1>";
        echo "<ul>";
        echo "<li><a href='" . site_url('migration/add_linear_position') . "'>Add Linear Position Column</a></li>";
        echo "</ul>";
    }
    
    /**
     * Menambahkan kolom linear_position ke tabel userkastaresult
     */
    public function add_linear_position() {
        echo "<h1>Migration: Add Linear Position Column</h1>";
        
        try {
            // Cek apakah kolom sudah ada
            $query = $this->db->query("SHOW COLUMNS FROM userkastaresult LIKE 'linear_position'");
            $exists = ($query->num_rows() > 0);
            
            if ($exists) {
                echo "<p>Column 'linear_position' already exists.</p>";
            } else {
                // Tambah kolom
                $this->db->query("ALTER TABLE userkastaresult ADD COLUMN linear_position INT DEFAULT NULL AFTER confidence_score");
                echo "<p>Column 'linear_position' added successfully.</p>";
                
                // Update data yang sudah ada
                echo "<p>Updating existing data...</p>";
                
                // Load model untuk update
                $this->load->model('match_model');
                
                // Ambil semua user yang memiliki hasil kasta
                $users = $this->db->get('userkastaresult')->result();
                
                $updated = 0;
                foreach ($users as $user) {
                    // Hitung posisi linear
                    $linear_position = $this->match_model->get_user_sub_kasta($user->user_id);
                    
                    if ($linear_position !== false) {
                        // Update record
                        $this->db->where('user_id', $user->user_id);
                        $this->db->update('userkastaresult', ['linear_position' => $linear_position]);
                        $updated++;
                    }
                }
                
                echo "<p>Updated linear position for {$updated} users.</p>";
            }
            
            echo "<p><a href='" . site_url('migration') . "'>Back to migrations</a></p>";
            
        } catch (Exception $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
} 