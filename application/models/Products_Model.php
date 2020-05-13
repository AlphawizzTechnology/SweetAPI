<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        // Load the database library
        $this->load->database();
        
        $this->userTbl = 'products';
        $this->review_table = 'product_review';
    }

    public function SingleProduct($id)
    {
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('product_id',$id);
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }

    public function Get_User_Reviews($id)
    {
        $this->db->select('*');
        $this->db->from($this->review_table);
        $this->db->where('product_id',$id);
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }

    public function Post_User_Reviews($review_data){
       $this->status = 0;
       $this->db->select('*');
       $this->db->from($this->review_table);
       $this->db->where(array('product_id'=>$review_data['product_id'],'user_id'=>$review_data['user_id']));
       $query = $this->db->get()->result();
       if(!empty($review_data)){
       if(count($query) == 0){
        if($this->db->insert($this->review_table,$review_data)){
            $this->status = 1;
            
        }
        else {
           $this->status = 0;
        }
    }
    else {
        if($this->db->update($this->review_table,$review_data)){
            $this->status = 2;
        }
        else {
            $this->status = 0;
        }
    }
      } 
      else {
        $this->status = 0;
      }  
        
        return $this->status;
    }



    public function Banner(){
        $this->db->select('product_url');
        $this->db->from('banner1');
        $this->db->order_by('id','desc');
        $this->db->limit(4);
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }

    public function HomeProducts(){
        $this->db->select('*');
        $this->db->from('products');
        // $this->db->order_by('id','desc');
        $this->db->limit(5);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;

    }

    public function GetAllCategory(){
        $this->db->select('*');
        $this->db->from('categories');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;


    }

   public function GetAllState(){
        $this->db->select('*');
        $this->db->from('States');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;


    }
    

    public function GetProductByCategoryId($id){

         

        $this->db->select('*');
        $this->db->from('products');
        if($id == 9){
            
        }
        else {
        $this->db->where('State_code',$id);
        }
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;

    }

    public function addWishList($data){
        $this->db->select('*');
      $this->db->from('product_wishlist');
      $this->db->where($data);
      $query = $this->db->get();
      $result = $query->result_array();
      if(count($result)>0){
        $this->db->where($data);
        
      if($this->db->delete('product_wishlist')){
        return 'Removed from wishlist';
      }
      else {
        
      }
      }
      else {
      if($this->db->insert('product_wishlist',$data)){
        return 'added to wishlist';
      }
      else {
        
      }

    }
    }

     public function getWishList($data){
      $this->db->select('products.*,product_wishlist.user_id,product_review.comment,product_review.rating');
      $this->db->from('product_wishlist');
      $this->db->join('products', 'products.product_id = product_wishlist.product_id','left');
      $this->db->join('product_review','product_review.product_id=products.product_id','left');
      
      $this->db->where('product_wishlist.user_id', $data['user_id']); 
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }


     public function search($search){
         $this->db->select('products.*,product_review.comment,product_review.rating');
         $this->db->from('products');
         $this->db->join('product_review','product_review.product_id=products.product_id','left');
        
         if($search){
            $query = $this->db->like('product_name', $search);
            $this->db->or_like('product_description', $search);
            $this->db->or_like('product_pincode', $search);
            $query = $this->db->get();
            $result = $query->result_array();
        }
        return !empty($result)?$result:false;
   }


    


    public function GetProductById($id){
         $this->db->select('products.*,product_review.comment,product_review.rating');
         $this->db->from('products');
         $this->db->join('product_review','product_review.product_id=products.product_id','left');
         
        
        $this->db->where('category_id',$id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    public function GetAllProductById($id){
        
        $this->db->select('products.*,product_review.comment,product_review.rating');
        $this->db->from('products');
        $this->db->join('product_review','product_review.product_id=products.product_id','left');
        $this->db->group_by('products.product_id');
        if(!empty($id)){
            if($id == 8){
                $this->db->where('State_code',8);
            }
            else if($id == 2) {
                $this->db->where('State_code',2);
            }
            else {
                $this->db->where('State_code',$id);
            }
            
        }
        
        else {
           $this->db->where('State_code',2); 
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    public function ProductSorting($id){
        if($id == 1){
            $this->db->select('products.*');
            $this->db->distinct();
            $this->db->from('products');
            $this->db->join('sale_items', 'sale_items.product_id = products.product_id');
        }
        else if($id == 2){
            $this->db->select('*');
            $this->db->from('products');
            $this->db->order_by('product_id','desc');
            $this->db->limit(9);
        }
        else if($id == 3){
            $this->db->select('*');
            $this->db->from('products');
            $this->db->order_by('price','asc');
        }
        else if($id == 4) {
            $this->db->select('*');
            $this->db->from('products');
            $this->db->order_by('price','desc');
        }
        else {  }
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function RangeFilter($f,$l){
        $this->db->select("*");
        $this->db->from('products');
        $this->db->where('price >=', $f);
        $this->db->where('price <=',$l);
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function rating_filter($id){
       $this->db->select('*');
       $this->db->from('product_review');
       $this->db->where('rating',$id);
       $result = $this->db->get()->result_array();
        return $result;
    }

    public function pincode_filter($pincode){
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('product_pincode',$pincode);
        $result = $this->db->get()->result_array();
        return $result;
    }



}
