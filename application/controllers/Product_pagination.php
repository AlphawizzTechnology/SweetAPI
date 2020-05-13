<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Product_pagination extends REST_Controller {

    public function __construct() { 
    	 
        parent::__construct();
        
        // Load the user model
        $this->load->model('Product_Pagination_Model');
    }

    public function Pagination_get($id){
       $id = $id-1;
    $total_rows = $this->Product_Pagination_Model->count();
    $per_page = 10; 
    $this->db->select('*');
    $this->db->from('products');
    $this->db->order_by('product_id','asc');
    $starting = 1+($id*10);
    $limit = 10;

    $this->db->limit($limit,$starting);
    $result = $this->db->get()->result();
    
    if($result){
    	$this->response([
                    'status' => TRUE,
                    'message' => "Pagination.",
                    'page' => $id+1,
                    'data' => $result
                ], REST_Controller::HTTP_OK);
    }
    else { 
    	       $this->response([
    	            'status' => FALSE,
                    'message' => "Product Not Found", 
                ], REST_Controller::HTTP_BAD_REQUEST);
    }
   
	}
    
    }
