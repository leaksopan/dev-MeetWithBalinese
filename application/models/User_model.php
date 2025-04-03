<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Model
 * 
 * Model untuk mengelola data pengguna
 * 
 * @package     MeetWithBalinese
 * @subpackage  Models
 * @category    User
 * @author      MeetWithBalinese Team
 */
class User_model extends CI_Model {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Register pengguna baru
     * 
     * @param array $data Data pengguna
     * @return int|bool ID pengguna atau FALSE jika gagal
     */
    public function register($data) {
        // Cek apakah email sudah ada
        $existing_email = $this->db->get_where('users', ['email' => $data['email']])->row();
        if ($existing_email) {
            log_message('error', 'Email already exists: ' . $data['email']);
            return FALSE;
        }
        
        $this->db->insert('users', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Cek login user
     * 
     * @param string $email Email pengguna
     * @param string $password Password pengguna
     * @return object|bool Data pengguna atau FALSE jika gagal
     */
    public function login($email, $password) {
        $this->db->where('email', $email);
        $user = $this->db->get('users')->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return FALSE;
    }

    /**
     * Dapatkan data pengguna berdasarkan ID
     * 
     * @param int $user_id ID pengguna
     * @return object|bool Data pengguna atau FALSE jika tidak ditemukan
     */
    public function get_user_by_id($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('users');
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Update data pengguna
     * 
     * @param int $user_id ID pengguna
     * @param array $data Data untuk diupdate
     * @return bool TRUE jika berhasil, FALSE jika gagal
     */
    public function update_user($user_id, $data) {
        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
        return ($this->db->affected_rows() > 0);
    }

    /**
     * Dapatkan semua pengguna dengan filter
     * 
     * @param array $filters Filter untuk query
     * @return array Daftar pengguna
     */
    public function get_all_users($filters = array()) {
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        return $this->db->get('users')->result();
    }
} 