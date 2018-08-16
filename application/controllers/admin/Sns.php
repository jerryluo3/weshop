<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sns extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'sns_model');
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->sns_model->getSnsList();
		$this->load->view(admin_url().'sns',$data);
	}
	
	public function sendSingle()
	{
		$data = array();
		$this->load->view(admin_url().'snsSingle',$data);
	}	
	
	public function del(){
		$res = $this->sns_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."orders/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."orders/index",'2');
		}
	}
	
	
	//搜索
	public function search()
	{   
		
	    $str = $this->uri->segment(4);
		$arr = !empty($str) ? explode('-',$str) : array();
		$page_size = 20;
		$page = intval($this->uri->segment(5));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		
		
		$offset = ($page-1)*$page_size;
		
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				//$this->db->like('onumber',$arr[0]);	
				//$this->db->or_like('orders.title',$arr[0]);
				$a = urldecode($arr[0]);					
				$this->db->where('( onumber like "%'.$a.'%" OR orders.title like "%'.$a.'%")');
				$data['title'] = $a;
			}
			if($arr[1] != 99){
				$this->db->where('orders.status',$arr[1]);
				$data['status'] = $arr[1];	
			}
			if($arr[2] != 99){
				$this->db->where('orders.zhifu_type',$arr[2]);		
				$data['zhifu_type'] = $arr[2];	
			}
			if($arr[3] != 99){
				$this->db->where('orders.storeid',$arr[3]);	
				$data['storeid'] = $arr[3];		
			}
			if($arr[4] > 0){
				$stime = $arr[4];
				$this->db->where('orders.closing_time >=',$stime);	
				$data['stime'] = $arr[4];		
			}
			if($arr[5] > 0){
				$etime = $arr[5]-1;
				$this->db->where('orders.closing_time <',$etime);	
				$data['etime'] = $arr[5];		
			}
				
		}
		$total_nums = $this->db->count_all_results('orders');
		$data['total_nums'] = $total_nums;
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				//$this->db->like('onumber',$arr[0]);	
				//$this->db->or_like('orders.title',$arr[0]);	
				$a = urldecode($arr[0]);					
				$this->db->where('( onumber like "%'.$a.'%" OR orders.title like "%'.$a.'%")');
				$data['title'] = $a;
			}
			if($arr[1] != 99){
				$this->db->where('orders.status',$arr[1]);
				$data['status'] = $arr[1];	
			}
			if($arr[2] != 99){
				$this->db->where('orders.zhifu_type',$arr[2]);		
				$data['zhifu_type'] = $arr[2];	
			}
			if($arr[3] != 99){
				$this->db->where('orders.storeid',$arr[3]);	
				$data['storeid'] = $arr[3];		
			}
			if($arr[4] > 0){
				$stime = $arr[4];
				$this->db->where('orders.closing_time >=',$stime);	
				$data['stime'] = $arr[4];		
			}
			if($arr[5] > 0){
				$etime = $arr[5];
				$this->db->where('orders.closing_time <',$etime);	
				$data['etime'] = $arr[5];		
			}
				
		}
				
		$data['orders_list'] = $this->db->select('orders.*,luo_store.*,users.id as users_id,users.phone')->order_by('id','DESC')->limit($page_size,$offset)->join('luo_store','luo_store.cat_id = orders.storeid','left')->join('users','users.id = orders.uid','left')->get('orders')->result_array();
		//echo $this->db->last_query();

		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		

	   $this->load->view(admin_url().'orders',$data);

	}
	
	
	
}
