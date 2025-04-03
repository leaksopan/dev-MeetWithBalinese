<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller
 * 
 * Controller dasar untuk semua controller di aplikasi
 * Menambahkan properties untuk menghindari lint error
 * 
 * @package     MeetWithBalinese
 * @subpackage  Core
 * @category    Controller
 * @author      MeetWithBalinese Team
 */
class MY_Controller extends CI_Controller {
    
    /**
     * @var CI_DB_query_builder
     */
    public $db;
    
    /**
     * @var CI_Session
     */
    public $session;
    
    /**
     * @var CI_Input
     */
    public $input;
    
    /**
     * @var CI_Form_validation
     */
    public $form_validation;
    
    /**
     * @var User_model
     */
    public $user_model;
    
    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
    }
} 