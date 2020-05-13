<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class User_Dashboard extends REST_Controller {

	public function __construct() { 
    parent::__construct();
    $this->load->model('UserDashboardModel');
    }
    

    public function getUserProfile_post(){
     $id = $this->post('user_id');
     $data = $this->UserDashboardModel->getUserProfile($id);
        if(!empty($data)) {
        $this->response([
            'status' => TRUE,
            'message' => 'Profile Data.',
            'data' => $data
        ], REST_Controller::HTTP_OK);
    }
        else {
        $this->response([
            'status' => FALSE,
            'message' => 'User Not Found.',
            
        ], REST_Controller::HTTP_OK);
        }

    }



    public function updateUserProfile_post(){
     $name = $this->post('name');
     $email = $this->post('email');
     $number = $this->post('number');
     
     $user_id = $this->post('user_id');
     
     
     $config['upload_path'] = 'uploads';
        $config['allowed_types'] = '*';
        $config['max_filename'] = '255';
        
        $config['max_size'] = '1024'; //1 MB
        
        if (!empty($_FILES['file']['name'])) {
            if (0 < $_FILES['file']['error']) {
                echo 'Error during file upload' . $_FILES['file']['error'];
            } else {
                 
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('file')) {
                       // echo $this->upload->display_errors();
                    } else {
                        //echo 'File successfully uploaded : uploads/' . $_FILES['file']['name'];
                    }
                
            }
        }
        
        if(!empty($_FILES['file']['name'])){
            
        
        $image = $_FILES['file']['name'];
        if($image == NULL){
            $image = '';
        }
        }
        else {
            $image = '';
        }
     $update_data =array('user_fullname'=>$name,'user_email'=>$email,'user_phone'=>$number,'user_image'=>$image);
      if($this->UserDashboardModel->updateUserProfile($update_data,$user_id)){
            $this->response([
            'status' => TRUE,
            'message' => 'Updated Successfully.',
            'data' =>$update_data
            
        ], REST_Controller::HTTP_OK);
      }
      
      else {
            $this->response([
            'status' => FALSE,
            'message' => 'Unable To Update.',
            
        ], REST_Controller::HTTP_OK);
      }
     
    }
    

public function testing_post(){
    //upload file
        
}

    public function saveAddress_post(){
        $Address1 = strip_tags($this->post('address1'));                            
        $Address2 = strip_tags($this->post('address2'));                                           
        $City = strip_tags($this->post('city'));
        $State = strip_tags($this->post('state'));
        $Pincode = strip_tags($this->post('pincode'));
        $User_id = strip_tags($this->post('user_id'));
        if(!empty($Address1) && !empty($Address2) && !empty($City) && !empty($State) &&!empty($Pincode) && !empty($User_id)) {
        $address_data = array('User_id'=>$User_id,'Address1'=>$Address1,'Address2'=>$Address2,'City'=>$City,'State'=>$State,'Pincode'=>$Pincode);
       
            if($this->UserDashboardModel->addAddress($address_data)){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Address Inserted Successfully.',
                   
                ], REST_Controller::HTTP_OK);
            }
            else {
                $this->response([
                    'status' => false,
                    'message' => 'Unable To Insert Successfully.',
                   
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else {
            $this->response([
                    'status' => false,
                    'message' => 'Validation error.',
                   
                ], REST_Controller::HTTP_BAD_REQUEST);
        }
            
    }

    

    public function getAddress_post(){
        
        $user_id = strip_tags($this->post('user_id'));
        $data = $this->UserDashboardModel->getAddress($user_id);
        if(!empty($data)) {
            $this->response([
                'status' => TRUE,
                'message' => 'User Shipping Address.',
                'data' => $data
            ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Address Not Found.',
                
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
        
    }
    


    public function updateAddress_post(){
        $Address1 = strip_tags($this->post('address1'));                            
        $Address2 = strip_tags($this->post('address2'));                                           
        $City = strip_tags($this->post('city'));
        $State = strip_tags($this->post('state'));
        $Pincode = strip_tags($this->post('pincode'));
        $User_id = strip_tags($this->post('address_id'));

        $address_data = array('Address1'=>$Address1,'Address2'=>$Address2,'City'=>$City,'State'=>$State,'Pincode'=>$Pincode);
        
            if($this->UserDashboardModel->updateAddress($address_data,$User_id)){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Address Updated Successfully.',
                   
                ], REST_Controller::HTTP_OK);
            }

            else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Unable To Update .',
                   
                ], REST_Controller::HTTP_BAD_REQUEST);
            } 
    }
    
    
    
    public function deleteAddress_post(){
        //  $id  = $this->delete('User_id');
        $id = $this->post('address_id');
         if(!empty($id)) {
         if($this->UserDashboardModel->delete_address($id)){
            
           $this->response([
                    'status' => TRUE,
                    'message' => 'Address Deleted Successfully.',
                   
                ], REST_Controller::HTTP_OK);   
         }
         else {
              $this->response([
                    'status' => FALSE,
                    'message' => 'Address Not Found.',
                   
                ], REST_Controller::HTTP_OK);      
         }
    }
    else {
         $this->response([
                    'status' => TRUE,
                    'message' => 'Address Deleted Successfully.',
                   
                ], REST_Controller::HTTP_OK);   
    }
    }
    
     public function getOrder_post() {
         $id = $this->post('user_id');
        $data = $this->UserDashboardModel->getOrders($id);
        if(!empty($data)) {
            $this->response([
                'status' => TRUE,
                'message' => 'Your Ordered Products.',
                'data' => $data
            ], REST_Controller::HTTP_OK);
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Not Ordered Yet.',
               
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


} 


