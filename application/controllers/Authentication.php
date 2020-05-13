<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Authentication extends REST_Controller {

    public function __construct() { 
    	 
        parent::__construct();
        
        // Load the user model
        $this->load->model('user');
    }
    
    public function login_post() {
        // Get the post data
        $email = $this->post('email');
        $password = $this->post('password');
        
        // Validate the post data
        if(!empty($email) && !empty($password)){
            
            // Check if any user exists with the given credentials
            $con['returnType'] = 'single';
            $con['conditions'] = array(
                'user_email' => $email,
                'user_password' => md5($password),
                
            );
            $user = $this->user->getRows($con);
            
            if($user){
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'User login successful.',
                    'data' => $user
                ], REST_Controller::HTTP_OK);
            }else{
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response([
                    'status' => FALSE,
                    'message' => "Wrong email or password.",
                    'data' => $user
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => "Provide email and password.",
               
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


   

    public function registration_post() {
        $user_fullname = strip_tags($this->post('user_fullname'));                            
        $user_email = strip_tags($this->post('user_email'));                                           
        $user_phone = strip_tags($this->post('user_phone'));
        $user_bdate = strip_tags($this->post('user_bdate'));
        $user_city = strip_tags($this->post('user_city'));
        $user_password = strip_tags($this->post('user_password'));
        $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = substr(str_shuffle($set), 0, 12);
       
         
        if( !empty($user_fullname) && !empty($user_email) && !empty($user_phone) && !empty($user_city) && !empty($user_password) && !empty($user_bdate)){
            
            // Check if the given email already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'user_email' => $user_email,
                
            );

             $con1['returnType'] = 'count';
            $con1['conditions'] = array(
                 'user_phone' =>$user_phone
                
            );

          
                
            $userCount = $this->user->getRows($con);
            $userCount1 = $this->user->getRows1($con1);
           
            
            if($userCount > 0){
                // Set the response and exit
                $this->response([
                        'status' => FALSE,
                        'message' => 'The given email already exists.',
                       
                    ], REST_Controller::HTTP_BAD_REQUEST);
               
            }


            else if($userCount1 >0) {
            	 $this->response([
                        'status' => FALSE,
                        'message' => 'The given Phone Number already exists.',
                       
                    ], REST_Controller::HTTP_BAD_REQUEST);

            }
            else{
                $this->load->library('session');
          $otp = rand(1000, 9999);
          
          $this->session->set_userdata('otp',$otp);
          $this->session->set_userdata('mob',$user_phone);
          $timeout = time();
          $this->session->set_userdata('timeout',$timeout);
                // Insert user data
                $userData = array(
                    'user_fullname' => $user_fullname,
                    'user_email' => $user_email,
                   
                    'user_password' => md5($user_password),
                    'user_phone' => $user_phone,
                    'user_bdate' => $user_bdate,
                    'user_city' =>$user_city,
                    'varification_code'=>$code,
                    
                );

                $insert = $this->user->insert($userData);
                $id = $this->db->insert_id();
                $this->session->set_userdata('users',$id);
                
                // Check if the user data is inserted
                if($insert){
                    
              $this->response([
                     'status' => TRUE,
                     'otp' =>$otp,
                     'phone_number'=>$user_phone,
                      'message' => 'User added successfully ',
                        
                     ], REST_Controller::HTTP_OK);

                    }
          
                }
            }
        
        else{
            // Set the response and exit
             $this->response([
                        'status' => FALSE,
                        'message' => 'Provide complete user info to add.',
                       
                    ], REST_Controller::HTTP_BAD_REQUEST);
            
        }
    }
    

public function generateOtp_post(){
    $this->load->library('session');
    $phone_number = $this->post('phone');
    $this->session->set_userdata('mob',$phone_number);
     $otp = rand(1000, 9999);
          
          $this->session->set_userdata('otp',$otp);
          $timeout = time();
          $this->session->set_userdata('timeout',$timeout);
          $this->response([
                        'status' => TRUE,
                        'message' => 'Your Otp is Valid for 60 seconds',
                        'otp' =>$otp,
                        'phone_number'=>$phone_number
                       
                    ], REST_Controller::HTTP_OK); 
}



   public function otpVarification_post(){
       $this->load->library('session');
          $otp = $this->post('otp');
          $mobile = $this->post('mobile');
         
          if(!empty($this->session->userdata('timeout'))) {
          if(time()-$this->session->userdata('timeout')>= 60){
                $this->response([
                        'status' => FALSE,
                        'message' => 'otp expire',
                       
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    $this->session->unset_userdata('otp');
                    $this->session->unset_userdata('timeout');
             
          }
          
          else {
            //   $this->response([
            //             'status' => TRUE,
            //             'message' => 'otp not expaired',
            //             'testing' =>time()-$this->session->userdata('timeout')
                       
            //         ], REST_Controller::HTTP_BAD_REQUEST);
            if(!empty($this->session->userdata('otp'))){
                if($this->session->userdata('otp') == $otp && $this->session->userdata('mob') == $mobile) {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Registration Successfully',
                       
                    ], REST_Controller::HTTP_BAD_REQUEST); 
                    
                    
                    $this->session->unset_userdata('otp');
                    $this->session->unset_userdata('mob');
                    $this->session->unset_userdata('timeout');
                    
                    if(!empty($this->session->userdata('users'))) {
                        $user_id = $this->session->userdata('users');
                    $this->db->where('user_id',$user_id);
                    $data =array('mobile_verified'=>1);
                    $this->db->update('registers', $data);
                    
                    }
                }
                
                else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'invalid Otp',
                       
                    ], REST_Controller::HTTP_BAD_REQUEST); 
                }
            }
          }
             
          }
          else {
               $this->response([
                        'status' => FALSE,
                        'message' => 'Otp Not Found',
                       
                    ], REST_Controller::HTTP_BAD_REQUEST); 
          }
        
       
   }
   
   
    public function user_get($id = 0) {
        // Returns all the users data if the id not specified,
        // Otherwise, a single user will be returned.
        $con = $id?array('id' => $id):'';
        $users = $this->user->getRows($con);
        
        // Check if the user data exists
        if(!empty($users)){
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response($users, REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'No user was found.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }



    
   

    public function ResetPassword_post(){
        $email = $this->input->post('email');
        $available = $this->user->checkEmailAvailable($email);
        
        if(!$available){
            // Set the response and exit
             $this->response([
                    'status' => FALSE,
                    'message' => 'Unknown Email address.'
                ],  REST_Controller::HTTP_BAD_REQUEST);
            
        }
        else {

               //config
      $config['protocol'] = 'smtp';
      $config['smtp_host'] = 'ssl://smtp.googlemail.com';
      $config['smtp_port'] = 465;
      $config['smtp_user'] = 'ganguramonline@gmail.com';
      $config['smtp_pass'] = 'ganguram@123';
      $config['mailtype'] = 'html';
      $this->email->set_header('Content-Type', 'text/html');
      
      $this->load->library('email', $config);
      $this->email->from('ganguramonline@gmail.com', 'Identification');
      $this->email->to($email);
      $this->email->subject('Reset Password Link');
      //$Otp = mt_rand(100000, 999999);
      $Link = base_url()."login/resetPassword548377043/".base64_encode($email);
      $this->email->message('<html>
        <head>
          <title>Reset Password Link</title>
        </head>
        <body>          
          <p>Your Account:</p>
          <p>Email: '.$email.'</p>          
          <p>Please click the link below to reset your password.</p>
          <h4><a href='.$Link.'>Reset password</a></h4>
        </body>
        </html>');

  
        
      if ($this->email->send()) {
       
             $this->response([
                    'status' => TRUE,
                    'message' => 'Varificatin link has send to your email'
                ],  REST_Controller::HTTP_OK); 
      } else {
       // echo $Link;
       $this->response([
                    'status' => FALSE,
                    'message' => 'unable to send Email '
                ],  REST_Controller::HTTP_BAD_REQUEST); 
      }
    

            
        }


    }




}