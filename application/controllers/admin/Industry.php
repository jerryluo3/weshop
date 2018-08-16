<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Industry extends MY_Controller {	

	public function __construct(){
		parent::__construct();		
		$this->load->model(admin_url().'industry_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->industry_model->getIndustryList();		
		$this->load->view(admin_url().'industry',$data);
	}
	
	public function mod()
	{
		$industry_id = $this->uri->segment(4);
		$act = $industry_id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['row'] = $this->industry_model->getoneindustry();
		}
		$data['cate_list'] = $this->industry_model->getcates();
		
		$this->load->view(admin_url().'industry_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('iname', '名称', 'required');
		$this->form_validation->set_rules('irank', '排序ID', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data['cate_list'] = $this->industry_model->getcates();
			$this->load->view(admin_url().'industry_mod', $data);
		}else{
			$res = $this->industry_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."industry/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."industry/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->industry_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."industry/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."industry/index",'2');
		}
	}
	
	
}
