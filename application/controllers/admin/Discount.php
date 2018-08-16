<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'discount_model');
		$this->load->model(admin_url().'discountcategory_model');
		$this->load->library('form_validation');
		$this->load->helper('myfun');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->discount_model->getdiscountList();
		$data['catesoption'] = $this->discountcategory_model->getcats('',1,$_SESSION['user_purview'],0);
		$this->load->view(admin_url().'discount',$data);
	}	
	
	public function mod()
	{
		$id = $this->uri->segment(4);
		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		if($act == "mod"){
			$data['row'] = $this->discount_model->getonediscount();
		}
		$cid = isset($data['row']['cid']) ? $data['row']['cid'] : '';
		$data['catesoption'] = $this->discountcategory_model->getcats('',1,$_SESSION['user_purview'],$cid);
		
		$this->load->view(admin_url().'discount_mod', $data);
	}
	
	public function del(){
		$res = $this->discount_model->del();
		echo $res;
		
	}
	
	
	public function search()
	{   
		
		$str = urldecode($this->uri->segment(4));
		$arr = !empty($str) ? explode('-',$str) : array();
		$cid = isset($arr[1]) ? $arr[1] : '';
		
		$data['catesoption'] = $this->discountcategory_model->getcats('',1,$_SESSION['user_purview'],$cid);
		
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
	
	
		$data['discount_list'] = $this->db->order_by('id','DESC')->limit(100)->join('discountcategory','discountcategory.cat_id = discount.cid','left')->get('discount')->result_array();
		//echo $this->db->last_query();
		$data['page_list'] = '';

	   $this->load->view(admin_url().'discount',$data);
	    

	}
	
	
}
