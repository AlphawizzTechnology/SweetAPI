<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Products extends REST_Controller {

    public function __construct() { 
		
        parent::__construct();
        
        // Load the user model
        $this->load->model('Products_Model');
    }

    public function products_get($id){
        // Validate the post data
        if(!empty($id)){
            $product = $this->Products_Model->SingleProduct($id);
           
            if(!empty($product)) {
        
            $array = explode(",",$product['thumbnails_image']);
            
            $product['images'] =  $array;
        
            }
            
            
            if($product){
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'Single Products',
                    'data' => $product
                ], REST_Controller::HTTP_OK);
            }
            else{
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response([
                    'status' => FALSE,
                    'message' => 'Invalid Product Id.'
                    
                ], REST_Controller::HTTP_BAD_REQUEST);
                
            }
        }

        else{
            // Set the response and exit
            $this->response("Provide an id", REST_Controller::HTTP_BAD_REQUEST);
        }
        

    }


    public function Reviews_post()
    {
        $id = $this->post('product_id');

        if(!empty($id)){
            $product = $this->Products_Model->Get_User_Reviews($id);
            
            if($product){
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'Single Products',
                    'Products' => $product
                ], REST_Controller::HTTP_OK);
            }
            else{
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                 $this->response([
                    'status' => FALSE,
                    'message' => 'Rreview Not Available.',
                   
                ], REST_Controller::HTTP_OK);
                
            }
        }
        else{
            // Set the response and exit
            $this->response([
                    'status' => FALSE,
                    'message' => 'Provide an id',
                    
                ], REST_Controller::HTTP_BAD_REQUEST);
            
        }
        

    }

     public function AddReviews_post(){
        // if(!empty($this->session->userdata('user_id'))){
        $product_id =  $this->input->post('product_id');
        $user_id    =  $this->input->post('user_id');
        $rating     =  $this->input->post('rating');
        $comment    =  $this->input->post('comments');

        $review_data = array('product_id'=>$product_id,'user_id'=>$user_id,'rating'=>$rating,'comment'=>$comment);
         if($product_id != '' && $user_id != '' && $rating != '' && $comment != ''  ){
        if($this->Products_Model->Post_User_Reviews($review_data) == 1){
            $this->response([
                         'status' => TRUE,
                         'message' => 'Review Inserted.',
                         
                          ], REST_Controller::HTTP_OK); 
        }

        else if($this->Products_Model->Post_User_Reviews($review_data) == 2){
            $this->response([
                         'status' => TRUE,
                         'message' => 'Review Updated.',
                         
                          ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Unable To Add Reviews',
                
                 ], REST_Controller::HTTP_BAD_REQUEST);
        }
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Provide Proper information',
                
                 ], REST_Controller::HTTP_OK);

        }

    }


    public function Banner_get()
    {
        $data = $this->Products_Model->Banner();
        if($data){
            $this->response([
	         'status' => TRUE,
	         'message' => 'Banner Images',
	         'data' =>$data
	          ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Banner Images',
                
                 ], REST_Controller::HTTP_OK); 
        }
    }




    public function HomeProducts_get()
    {
        $data = $this->Products_Model->HomeProducts();
          
        
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Home Products',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Product Not Found',
                
                 ], REST_Controller::HTTP_OK); 
        }
    }
    
    
    
    public function GetOffers_get(){
        
        $this->db->select('products.*,product_review.comment,product_review.rating');
        $this->db->from('offers');
        $this->db->join('products','products.product_id=offers.product_id');
        $this->db->join('registers','registers.user_id=offers.user_id');
        $this->db->join('product_review','product_review.product_id=products.product_id','left');
        $data = $this->db->get()->result_array();
         for($i=0;$i<count($data);$i++){
                if($data[$i]['comment'] == NULL){
                    $data[$i]['comment'] = '';
                }
                if($data[$i]['rating'] == NULL){
                    $data[$i]['rating'] = 0;
                }
            }
            
        
        $this->response([
                'status' => TRUE,
                'message' => 'Home Products',
                'Product' => $data
                 ], REST_Controller::HTTP_OK); 
        
        
    }
    

    public function Category_get(){
        $data = $this->Products_Model->GetAllCategory(); 
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Categories list',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
    }

    
    public function search_post(){
        $content = $this->post('content');
        $data = $this->Products_Model->search($content); 
        
         for($i=0;$i<count($data);$i++){
                if($data[$i]['comment'] == NULL){
                    $data[$i]['comment'] = '';
                }
                if($data[$i]['rating'] == NULL){
                    $data[$i]['rating'] = 0;
                }
            }
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Search Products',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        } 
        else {
        	$this->response([
                'status' => FALSE,
                'message' => 'Product Not Found',
                
                 ], REST_Controller::HTTP_OK); 
        }
    }


    public function ProductByState_post(){
        $id = $this->post('state_id');
    	$data = $this->Products_Model->GetProductByCategoryId($id);
    	 
    	  for($i=0;$i<count($data);$i++) {
            $array = explode(",",$data[$i]['thumbnails_image']);
            
            $data[$i]['images'] =  $array;
        }
    	
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Products',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
        
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Products Not Found'
                 ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

    public function State_get(){
        $data = $this->Products_Model->GetAllState(); 
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'State List',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
    }

    public function ProductByCategory_post(){
        $id = $this->post('id');
        $data = $this->Products_Model->GetProductById($id); 
        for($i=0;$i<count($data);$i++){
                if($data[$i]['comment'] == NULL){
                    $data[$i]['comment'] = '';
                }
                if($data[$i]['rating'] == NULL){
                    $data[$i]['rating'] = 0;
                }
            }
            
        for($i=0;$i<count($data);$i++) {
            $array = explode(",",$data[$i]['thumbnails_image']);
            
            $data[$i]['images'] =  $array;
        }
        
         if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Products',
                "base_url" => 'https://ganguram.com/uploads/thum_image',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
        else {
           $this->response([
                'status' => FALSE,
                'message' => 'Product Not available in this category',
               
                 ], REST_Controller::HTTP_BAD_REQUEST);  
        }
    }


    public function getAllProduct_post(){
        $id = $this->post('location_id');
        
        $data = $this->Products_Model->GetAllProductById($id); 
        
        if(!empty($data)) {
        for($i=0;$i<count($data);$i++){
            $array = explode(",",$data[$i]['thumbnails_image']);
            
            $data[$i]['images'] =  $array;
        }
            }
            
            for($i=0;$i<count($data);$i++){
                if($data[$i]['comment'] == NULL){
                    $data[$i]['comment'] = '';
                }
                if($data[$i]['rating'] == NULL){
                    $data[$i]['rating'] = 0;
                }
            }

        if($data){
           $this->response([
           'status' => TRUE,
           'message' => 'Products',
           'data' => $data
            ], REST_Controller::HTTP_OK); 
       }
       else {
           $this->response([
           'status' => FALSE,
           'message' => 'Products Not Found',
           
            ], REST_Controller::HTTP_BAD_REQUEST); 
       }

    }

    public function Filters_Post(){
        
        $filter_id = $this->post('filter_id');
        $data = $this->Products_Model->ProductSorting($filter_id);

        if($data){
             for($i=0;$i<count($data);$i++){
            $array = explode(",",$data[$i]['thumbnails_image']);
            
            $data[$i]['images'] =  $array;
        }
            $this->response([
                'status' => TRUE,
                'message' => 'Products Filter',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
        
        else {
           $this->response([
            'status' => FALSE,
            'message' => 'Invalid argument',
            
             ], REST_Controller::HTTP_OK); 
        }
 
    }

    public function RangeFilter_post(){
        $starting = $this->input->post('starting');
        $ending = $this->input->post('ending');
        
        $data = $this->Products_Model->RangeFilter($starting,$ending);
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Products',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Not Found'
               
                 ],REST_Controller::HTTP_BAD_REQUEST);   
        }
    }

    public function RatingFilter_get($id){
        $data = $this->Products_Model->rating_filter($id);
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Rating Products',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Product Not Found'
               
                 ],REST_Controller::HTTP_BAD_REQUEST);   
        }
    }

    public function PincodeFilter_get($pincode){
        $data = $this->Products_Model->pincode_filter($pincode);
        if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Rating Products',
                'data' => $data
                 ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'Product Not Found'
               
                 ],REST_Controller::HTTP_BAD_REQUEST);   
        }

    }

    public function addWishList_post(){
       $product_id = $this->input->post('product_id');
       $user_id = $this->input->post('user_id');
       $wishlist_data = array('product_id'=>$product_id,'user_id'=>$user_id);
       $data = $this->Products_Model->addWishList($wishlist_data);
       if($data){
            $this->response([
                'status' => TRUE,
                'message' => $data,
                
                 ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => $data
               
                 ],REST_Controller::HTTP_BAD_REQUEST);   
        }
    }
    
    public function removeWishlist_post(){
        $product_id = $this->input->post('product_id');
       $user_id = $this->input->post('user_id');
       
       $condition = array('product_id'=>$product_id,'user_id'=>$user_id);
       $this->db->where($condition);
       $this->db->delete('product_wishlist');
       if($this->db->affected_rows() == 1){
            $this->response([
                'status' => TRUE,
                'message' => 'wishlist removed successfully'
               
                 ],REST_Controller::HTTP_OK);   
       }
       else {
            $this->response([
                'status' => FALSE,
                'message' => 'Wishlist not found'
               
                 ],REST_Controller::HTTP_OK);   
       }
       
        
    }
    
    

    public function getWishList_post(){
       
       $user_id = $this->input->post('user_id');
       $wishlist_data = array('user_id'=>$user_id);
       $data = $this->Products_Model->getWishList($wishlist_data);
       
        for($i=0;$i<count($data);$i++){
                if($data[$i]['comment'] == NULL){
                    $data[$i]['comment'] = '';
                }
                if($data[$i]['rating'] == NULL){
                    $data[$i]['rating'] = 0;
                }
            }
            
       if($data){
            $this->response([
                'status' => TRUE,
                'message' => 'Your wishlist',
                'data' =>$data
                 ], REST_Controller::HTTP_OK); 
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'wishlist not found'
               
                 ],REST_Controller::HTTP_BAD_REQUEST);   
        }
    }


}