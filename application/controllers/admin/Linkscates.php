<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linkscates extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'linkscates_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->linkscates_model->getLinkscatesList();		
		$this->load->view(admin_url().'linkscates',$data);
	}
	
	public function mod()
	{
		$ads_id = $this->uri->segment(4);
		$act = $ads_id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['row'] = $this->linkscates_model->getonelinkscates();
			$data['cate_list'] = $this->linkscates_model->getcates();		
		}
		
		$this->load->view(admin_url().'linkscates_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('cat_name', 'cat_name', 'required');
				
		if ($this->form_validation->run() === FALSE){
			$this->load->view(admin_url().'linkscates/', $data);
		}else{
			$res = $this->linkscates_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."linkscates/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."linkscates/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->linkscates_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."linkscates/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."linkscates/index",'2');
		}
	}
	
	
	
	
}
