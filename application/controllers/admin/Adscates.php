<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adscates extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'adscates_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->adscates_model->getAdscatesList();		
		$this->load->view(admin_url().'adscates',$data);
	}
	
	public function mod()
	{
		$ads_id = $this->uri->segment(4);
		$act = $ads_id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['row'] = $this->adscates_model->getoneadscates();
			$data['cate_list'] = $this->adscates_model->getcates();		
		}
		
		$this->load->view(admin_url().'adscates_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('cat_name', 'cat_name', 'required');
				
		if ($this->form_validation->run() === FALSE){
			$this->load->view(admin_url().'adscates/', $data);
		}else{
			$res = $this->adscates_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."adscates/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."adscates/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->adscates_model->del();
		echo $res;
	}
	
	
	
	
}
