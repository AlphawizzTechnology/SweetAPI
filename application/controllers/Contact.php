<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Contact extends REST_Controller {

	public function __construct() { 
	parent::__construct();

	$this->load->Model('ContactModel');
	}
	
	
	

	public function contact_email_post(){
		 
      
	}
	

}


        
        
       
       
        // $message = '<h2>Contact Email</h2>';
        // $message .= '<div class="container">
        // <label>Email Address:</label>
        // <p>'.$send_data['email'].'</p>
        // </div>';
        

