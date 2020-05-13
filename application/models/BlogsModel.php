<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class BlogsModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        // Load the database library
        $this->load->database();
        
        
    }

    public function AddComments($data)
    {
        if($this->db->insert('user_comments',$data)){
            return true;
        }
        else {
            return false;
        }
    }

    public function GetAllComments(){
        $this->db->select('*');
        $this->db->from('user_comments');
        $this->db->order_by('id','desc');
        $result = $this->db->get()->result_array();

        return $result;
    }


}