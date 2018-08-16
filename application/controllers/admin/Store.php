<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'store_model');
		$this->load->library('form_validation');
		$this->load->helper('myfun');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->store_model->getStoreList();
		$this->load->view(admin_url().'store',$data);
	}
	
	public function mod()
	{
		$id = $this->uri->segment(4);
		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		if($act == "mod"){
			$data['row'] = $this->store_model->getonestore();
		}
		
		$this->load->view(admin_url().'store_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('info[cat_name]', '标题', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data = array();
			$this->load->view(admin_url().'store_mod', $data);
		}else{
			$res = $this->store_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."store/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."store/index",'2');
			}
		}
		
	}
		
	public function del(){
		$res = $this->store_model->del();
		echo $res;
	}
	

	public function search()
	{   
		
		$data = $this->store_model->getSearchList();
		
		$this->load->view(admin_url().'store',$data);
	    

	}
	
	
}
