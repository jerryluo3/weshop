<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'products_model');
		$this->load->model(admin_url().'productscategory_model');
		$this->load->library('form_validation');
		$this->load->helper('myfun');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->products_model->getproductsList();
		$data['catesoption'] = $this->productscategory_model->getcats('',1,$_SESSION['user_purview'],0);
		$this->load->view(admin_url().'products',$data);
	}	
	
	public function mod()
	{
		$id = $this->uri->segment(4);
		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		if($act == "mod"){
			$data['row'] = $this->products_model->getoneproducts();
		}
		$cid = isset($data['row']['cid']) ? $data['row']['cid'] : '';
		$data['catesoption'] = $this->productscategory_model->getcats('',1,$_SESSION['user_purview'],$cid);
		
		$this->load->view(admin_url().'products_mod', $data);
	}
	
	public function del(){
		$res = $this->products_model->del();
		echo $res;
		
	}
	
	
	public function search()
	{   
		
		$str = urldecode($this->uri->segment(4));
		$arr = !empty($str) ? explode('-',$str) : array();
		$cid = isset($arr[1]) ? $arr[1] : '';
		
		$data['catesoption'] = $this->productscategory_model->getcats('',1,$_SESSION['user_purview'],$cid);
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				$str = htmlspecialchars_decode($arr[0]);
				$this->db->like('title',$str);
				$data['title'] = $arr[0];
			}
			if($arr[1] > 0){
				$this->db->where('cid',$arr[1]);	
				$data['cid'] = $arr[1];		
			}
				
		}
	
	
		$data['products_list'] = $this->db->order_by('id','DESC')->limit(100)->join('productscategory','productscategory.cat_id = products.cid','left')->get('products')->result_array();
		//echo $this->db->last_query();
		$data['page_list'] = '';

	   $this->load->view(admin_url().'products',$data);
	    

	}
	
	
}
