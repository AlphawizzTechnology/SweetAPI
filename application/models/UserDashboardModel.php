<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserDashboardModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        // Load the database library
        $this->load->database();
        
        $this->userTbl = 'products';
        $this->review_table = 'product_review';
    }

    
    public function getUserProfile($id){
      $this->db->select('*');
      $this->db->from('registers');
      $this->db->where('user_id',$id);
      $query = $this->db->get()->result_array();
      return $query;
    }


    public function addAddress($address_data){
     $this->db->insert('user_address',$address_data);
     return true;
    }
    


    public function updateAddress($address_data,$user_id){
      $this->db->where('id',$user_id);
      if($this->db->update('user_address',$address_data)){
        if ($this->db->affected_rows() === 1) {
          return true;
          } else {
          return false;
          }
        }
        
      }
      
      

    public function getAddress($user_id) {
      $this->db->select('*');
      $this->db->from('user_address');
      $this->db->where('user_id',$user_id);
      $result = $this->db->get()->result_array();
      return $result;
    }
    

   public function delete_address($id){
       
       if($this->db->delete('user_address', array('id' => $id))){
           if ($this->db->affected_rows() === 1) {
          return true;
          } else {
          return false;
          }
       }
       
   }
   
  public function updateUserProfile($arrays,$user_id){
     
      $this->db->where('User_id',$user_id);
      $this->db->update('registers',$arrays);
      if ($this->db->affected_rows() === 1) {
          return true;
          } else {
          return false;
          }
      
  }
  
   public function getOrders($id){
    $this->db->select('products.*');
    $this->db->from('products'); 
    $this->db->join('sale_items b', 'b.product_id=products.product_id');
    $this->db->join('sale c', 'c.sale_id=b.sale_id');
    $this->db->where('c.user_id',$id);
    return $this->db->get()->result_array();
    
    }

}