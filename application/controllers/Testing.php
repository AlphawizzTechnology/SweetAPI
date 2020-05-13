<?php

class Testing extends CI_Controller {
public function index(){
		  
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_port'] = 465;
        $config['smtp_user'] = 'ganguramonline@gmail.com';
        $config['smtp_pass'] = 'ganguram@123';
        $config['mailtype'] = 'html';
        
        
        $this->load->library('email', $config);
   
        $this->email->from('ganguramonline@gmail.com', 'ganguram');
        $this->email->to('ratans18@gmail.com'); 
        
        $message = 'this is a message';
        $subject = 'this is a subject';
        $this->email->subject($subject);
        $this->email->message($message);  

        if($this->email->send()){
         
          echo 'your enquiry send successfully';
          
        }
        else {
          echo $this->email->print_debugger();

        

        }
}
}