<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_Pagination_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        // Load the database library
        $this->load->database();
        
        $this->userTbl = 'products';
        $this->review_table = 'product_review';
    }

    public function Pagination($page=0){

    }

    public function count(){
    	$this->db->select('*');
    	$this->db->from('products');
    	$data = $this->db->get()->result();
    	$count = count($data);
    	return $count;

    }

}
