<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Blogs extends REST_Controller {

    public function __construct() { 
    parent::__construct();

    $this->load->Model('BlogsModel');
    }


    public function Addcomments_post(){
        $name = $this->input->post('name');
        
        $comment = $this->input->post('comment');
        $user_id = $this->input->post('user_id');
        $email = $this->input->post('email');
        if($name != '' &&  $comment != '' && $user_id != '' && $email != '') {
        $comment_data = array('user_id'=>$user_id,'name'=>$name,'email'=>$email,'comment'=>$comment);

        if($this->BlogsModel->AddComments($comment_data)){
          $this->response([
                    'status' => TRUE,
                    'message' => 'Comment added successfully',
                    
                ], REST_Controller::HTTP_OK);
        }
        else {
           
        }

     }
     else {
         $this->response([
                    'status' => False,
                    'message' => 'Provide Proper Information',
                    
                ], REST_Controller::HTTP_BAD_REQUEST);

     }

    }


    public function GetAllcomments_get(){
        $data = $this->BlogsModel->GetAllComments();

        if(!empty($data)){
          $this->response([
                    'status' => TRUE,
                    'message' => 'Comments',
                    'data' =>$data
                ], REST_Controller::HTTP_OK);
        }
        else {
            $this->response([
                    'status' => FALSE,
                    'message' => 'Comment Not Found',
                    
                ], REST_Controller::HTTP_BAD_REQUEST);
        }

    }

}

    