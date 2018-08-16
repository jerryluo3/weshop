<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ads extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'ads_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->ads_model->getAdsList();		
		$this->load->view(admin_url().'ads',$data);
	}
	
	public function mod()
	{
		$ads_id = $this->uri->segment(4);
		$act = $ads_id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['row'] = $this->ads_model->getoneads();
		}
		$data['cate_list'] = $this->ads_model->getcates();
		
		$this->load->view(admin_url().'ads_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('ads_cid', '广告类别', 'required');
		$this->form_validation->set_rules('ads_title', '广告名称', 'required');
		$this->form_validation->set_rules('ads_picture', '广告图片', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data['cate_list'] = $this->ads_model->getcates();
			$this->load->view(admin_url().'ads_mod', $data);
		}else{
			$res = $this->ads_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."ads/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."ads/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->ads_model->del();
		echo $res;
	}
	
	
}
