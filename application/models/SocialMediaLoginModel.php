<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class SocialMediaLoginModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        // Load the database library
        $this->load->database();
        
        
    }
    
    public function saveUser($data){
        $this->db->select('*');
        $this->db->from('registers');
        $this->db->where($data);
        $result = $this->db->get()->result_array();
        if(!empty($result)) {
        $count = count($result);
        $id = $result;
        }
        else {
            $count = 0;
        }
        if($count>0){
            $data1 = $id;
            // $datas['status'] = '1';
            // $datas['result'] = 'inserted successfully';
        }
        else {
        if($this->db->insert('registers',$data)){
             $insertId = $this->db->insert_id();
             $q = $this->db->get_where('registers', array('user_id' => $insertId));
             $data1 = $q->result_array();
        //   $datas['status'] = TRUE;
        //     $datas['result'] = 'inserted successfully';
          }
    
     }
    //  $object = (object) $datas;
         return $data1;
}

}

