<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Payment extends REST_Controller {

	public function __construct() { 
	parent::__construct();
	$this->load->database();
	$this->load->library('session');
	}
	
	
	public function Billing_post(){
	    $firstname = $this->post('firstname');
	    $lastname = $this->post('lastname');
	    $address = $this->post('address');
	    $phone = $this->post('phone');
	    $zip_code = $this->post('zip_code');
	    $city = $this->post('city');
	    $state = $this->post('state');
	    $email = $this->post('email');
	    $shipping = $this->post('shipping');
	    $device_id = $this->post('device_id');
	    $user_id = $this->post('user_id');
	    $subtotal = $this->post('subtotal');
	    $p = $this->post('products');
	    $Products = json_decode($p, true);
	    
	    
	    $new_address = $address.' '.$city.' '.$state;
        $insert_data=array('user_id'=>$user_id,'on_date'=>date("Y-m-d"),'delivery_time_from'=>date("h:i:sa"),'delivery_address'=>$new_address,
        'total_amount'=>$subtotal,
        'First_name'=>$firstname,'Last_name'=>$lastname,'email'=>$email,'shipping_charge'=>$shipping,'zip_code'=>$zip_code);
        
        $this->db->insert('sale',$insert_data);
        $latest_sale_id = $this->db->insert_id();
        
        
        //print_r($insert_data);exit;
	   // var_dump($products);exit;
	    $Products = json_decode($p, true);
	   
	    
	    foreach($Products as $data){
	        
	        $sale_items = array('sale_id'=>$latest_sale_id,'product_id'=>$data['product_id'],'qty'=>$data['quantity']);
	        
	        if($sale_items){
                            $this->db->insert('sale_items',$sale_items);
                          }
	        
	    }
	    
	   
	    $this->response([
                'status' => TRUE,
                'message' => 'Order Addedd Successfully.',
                'invoice_id' =>$latest_sale_id
                
            ], REST_Controller::HTTP_OK); 
	 
	    
	}
	
	
// 	public function NotificationCount_get(){
// 	    if(!empty($this->session->userdata('notification_count'))){
// 	        $count = $this->session->userdata('notification_count');
// 	        $n = $count+1;
// 	        $this->session->set_userdata('notification_count',$n);
// 	    } 
// 	   else {
// 	       $this->session->set_userdata('notification_count',0);
// 	       $n = $this->session->userdata('notification_count');
// 	   }
// 	    $this->response([
//                 'status' => TRUE,
//                 'message' => 'Order Addedd Successfully.',
//                 'count' =>$this->session->userdata('notification_count')
                
//             ], REST_Controller::HTTP_OK); 
	   
// 	}
	
	
	public function Notification_get(){
	    //API URL of FCM
	   
	   
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAA3_r5dh0:APA91bFsuThYbLZgHz1-imRNx0sU0_gi5FX85ACYUUFyhjNw8noT2vTpMK8BLy46ahs-RhgMnTtDSAiK0gJwL4rxixdkqdg3XreRTQiTeKujb2gNFFPieQGkC0AfZfJ9Is9ABnLg2sRS';
        $device_id = 'f6XldFlANio:APA91bEi81Npk2yhzkMBo5OnW7bCoskF0DmPsy-AVNKGEwQ6H2O27LEu_i4-4XcBpeAuTLhhaXiVMVn-sU7Z9eO7ek3-SpZ1KlbfIO-gVH0Ws4CBT2I034kRTE1agcGqkCHrvSjGTn0D';
        
        $fields = array (
            
        'registration_ids' => array (
        $device_id
        ),
        'data' => array (
        "message" => 'Hello I am fine'
        )
        
        );
        
        //header includes Content type and api key
        $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        if(json_decode($result)->success == 1){
           $this->response([
                'status' => TRUE,
                'message' => 'Notification Send Successfully.',
               
                
            ], REST_Controller::HTTP_OK);   
        }
        else {
             $this->response([
                'status' => FALSE,
                'message' => json_decode($result),
                
                
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
        
        return $result;
	}
	
	    
	
	
	
	

  public function confirmPayment_post(){
      
    $sale_id = $this->post('invoice_id');
    $user_id = $this->post('user_id');
    $payment_method = $this->post('payment_method');
    $device_id = $this->post('device_id');
    $condition_array= array('sale_id'=>$sale_id,'user_id'=>$user_id);
    $update_data = array('is_paid'=>1,'payment_method'=>$payment_method);
    $message = '';
    $this->db->where($condition_array);
    $this->db->update('sale',$update_data);
    if($this->db->affected_rows() > 0){
        
        
        $message = 'payment done successfully';
        
         if(!empty($this->session->userdata('count'))){
            $count = $this->session->userdata('count');
            $newCount = $count+1;
            
            //echo $newCount;
            $this->session->set_userdata('count',$newCount);
        }
        
        else {
            
            $this->session->set_userdata('count',1);
        }
        
        $count = $this->session->userdata('count');
        
        $this->response([
                'status' => TRUE,
                'message' => 'Payment Done Successfully .',
                
                
            ], REST_Controller::HTTP_OK); 
        
        
    }
    
  else {
      $message = 'payment failed';
      $this->response([
                'status' => FALSE,
                'message' => 'Payment already Done .',
                
            ], REST_Controller::HTTP_BAD_REQUEST); 
            
  }
        
    //  $this->response([
    //             'status' => TRUE,
    //             'message' => 'Payment Successfully Done.',
    //             'count' =>$count
                
    //         ], REST_Controller::HTTP_OK); 
            
            if(!empty($device_id)){
            
            $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAA3_r5dh0:APA91bFsuThYbLZgHz1-imRNx0sU0_gi5FX85ACYUUFyhjNw8noT2vTpMK8BLy46ahs-RhgMnTtDSAiK0gJwL4rxixdkqdg3XreRTQiTeKujb2gNFFPieQGkC0AfZfJ9Is9ABnLg2sRS';
        
        
        $fields = array (
            
        'registration_ids' => array (
        $device_id
        ),
        'data' => array (
        "message" => $message
        )
        
        );
        
        //header includes Content type and api key
        $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        
        
     if(json_decode($result)->success == 1){
         date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
         $date = date('d/m/Y H:i:s');
         $d = explode(' ',$date)[0];
         
         $t = explode(' ',$date)[1];
        $notification_data  = array('user_id'=>$user_id,'notification'=>$message,'invoice_id'=>$sale_id,'status'=>1,'device_id'=>$device_id,'date'=>$d,'time'=>$t);
        $this->db->insert('notification_list',$notification_data);
            
            
        }
        else {
             
        }
        
        
        return $result;
            }
            else {
                $this->response([
                'status' => TRUE,
                'message' =>'Payment sucessfully done. provide device id for notification',
                
                
            ], REST_Controller::HTTP_OK); 
            }
            
  
  }
  
  public function testing_get(){
        echo $this->session->userdata('count');
  }
  
  
  public function GetNotificationList_post(){
      $user_id = $this->post('user_id');
      $this->db->select('*');
      $this->db->from('notification_list');
      $this->db->where('user_id',$user_id);
      $data = $this->db->get()->result_array();
      if(!empty($data)){
       $this->response([
                'status' => TRUE,
                'data' => $data,
                
            ]); 
      }
      else {
          $this->response([
                'status' => FALSE,
                'message'=>'Notification not found'
                
            ], REST_Controller::HTTP_OK); 
      }
  }
  
  public function GetAllNotification_post(){
      $user_id = $this->post('user_id');
      $this->db->select('*');
      $this->db->from('notification_list');
      $where_condition = array('user_id'=>$user_id,'watched'=>0);
      $this->db->where($where_condition);
      $count = count($this->db->get()->result_array());
      $this->session->set_userdata('notification_count',$count);
      $counts = $this->session->userdata('notification_count');
    
      $this->response([
                'status' => TRUE,
                'count'=> $counts
            ], REST_Controller::HTTP_OK); 
  }


  
  public function clearNotification_post(){
      $user_id = $this->post('user_id');
      $update_data = array('watched'=>1);
      $this->db->where('user_id',$user_id);
      $this->db->update('notification_list',$update_data);
       $this->response([
                'status' => TRUE,
                'count'=>0
                
                
            ], REST_Controller::HTTP_OK);
  }
  

  
  
  public function GetMyInvoice_post(){
      
      $invoice_id = $this->post('Invoice_id');
      
      
      $this->db->select('*');
      $this->db->from('sale_items');
      $this->db->join('sale','sale.sale_id = sale_items.sale_id');
      $this->db->join('products','products.product_id = sale_items.product_id');
      
      $this->db->where('sale_items.sale_id',$invoice_id);
      
      
      $data = $this->db->get()->result_array();
      //   echo $this->db->last_query();exit;
      for($i=0;$i<count($data);$i++){
            $array = explode(",",$data[$i]['thumbnails_image']);
            
            $data[$i]['images'] =  $array;
        }
      if(!empty($data)) {
       $this->response([
                'status' => TRUE,
                'message' => 'Your Order.',
                'data' => $data
                
            ], REST_Controller::HTTP_OK); 
      }
      
      else {
           $this->response([
                'status' => FALSE,
                'message' => 'Order Not Found.',
                
                
            ], REST_Controller::HTTP_BAD_REQUEST); 
      }
      
  }
  
  
   public function GetMyAllInvoice_post(){
      $id = $this->post('User_id');
      
      
      $this->db->select('*');
      $this->db->from('sale_items');
      $this->db->join('sale','sale.sale_id = sale_items.sale_id');
      $this->db->join('products','products.product_id = sale_items.product_id');
      $this->db->where('sale.user_id',$id); 
      $data = $this->db->get()->result_array();
    //   echo $this->db->last_query();exit;
      if(!empty($data)) {
       $this->response([
                'status' => TRUE,
                'message' => 'Your Order.',
                'data' => $data
                
            ], REST_Controller::HTTP_OK); 
      }
      
      else {
           $this->response([
                'status' => FALSE,
                'message' => 'Order Not Found.',
                
                
            ], REST_Controller::HTTP_BAD_REQUEST); 
      }
      
  }
  
  

}

