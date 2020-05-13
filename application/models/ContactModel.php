<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        // Load the database library
        $this->load->database();
    }
        
        public function sendEmail($send_data){
        

         $config['protocol'] = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.googlemail.com';
         $config['smtp_port'] = 465;
         $config['smtp_user'] = 'ganguramonline@gmail.com';
         $config['smtp_pass'] = 'ganguram@123';
         $config['smtp_timeout']=5;
           $config['starttls']=true;
             $config['newline']=5;
         $config['mailtype'] = '\r\n';
       

         $this->load->library('email', $config);
         $this->email->set_header('Content-Type', 'text/html');
         $this->email->from('ganguramonline@gmail.com');
         $this->email->to('ratans18@gmail.com'); 
         $this->email->subject($send_data['subject']);
         $this->email->message($send_data['message']);
         
        if($this->email->send()){
         
          echo 'your enquiry send successfully';
          
        }
        else {
          echo $this->email->print_debugger();


        }

        
    }
}