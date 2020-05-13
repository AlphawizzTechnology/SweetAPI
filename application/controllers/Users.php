<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Users extends REST_Controller {

	public function __construct() { 
	parent::__construct();

	$this->load->Model('BlogsModel');
	}


    // public function getOrderedProduct($id){

    // $this->db->select('sale.*,products.*');
    // $this->db->from('product'); 
    // $this->db->join('sale_items', 'sale_items.sale_id=sale.sale_id');
    // $this->db->join('products', 'products.product_id=', 'left');
    // $this->db->where('c.album_id',$id);
    // $this->db->order_by('c.track_title','asc');         
    // $query = $this->db->get(); 
    // if($query->num_rows() != 0)
    // {
    //     return $query->result_array();
    // }
    // else
    // {
    //     return false;
    // }
    // }

   public function AddShippingAddress_post(){
    $address1 = $this->input->post('address1');
    $address2 = $this->input->post('address2');
    $city = $this->input->post('city');
    $state = $this->input->post('state');
    $pincode = $this->input->post('pincode');
    $User_id = $this->input->post('User_id');

    $address_data  = array('User_id'=>$User_id,'address1'=>$address1,'address2'=>$address2,'City'=>$city,'State'=>$state,'Pincode'=>$pincode);
    echo json_encode($address_data);

   }
	

}

