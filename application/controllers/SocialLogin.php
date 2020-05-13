<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class SocialLogin extends REST_Controller {

    public function __construct() { 
    	 
        parent::__construct();
        
        // Load the user model
        $this->load->model('SocialMediaLoginModel');
    }

    public function Login_post(){
        
        $name = $this->post('name');
        $email = $this->post('email');
        $social = $this->post('social');
        
        
        if(!empty($name) && !empty($email)) {
        $social_data = array('user_fullname'=>$name,'user_email'=>$email,'social'=>$social);
        $result = $this->SocialMediaLoginModel->saveUser($social_data);
        // if($result['status'] == 1){
            $this->response([
                'status' => TRUE,
                'message' => 'Data Inserted Successfully.',
                'data' =>$result[0]
                
            ], REST_Controller::HTTP_OK);
        // }
        
      
    }
    
    else {
        
            $this->response([
                'status' => false,
                'message' => 'Provide Proper Information',
                
                
            ], REST_Controller::HTTP_BAD_REQUEST);
        
    }
}

}