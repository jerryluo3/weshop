<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membertype extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'membertype_model');
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->membertype_model->getMembertypeList();		
		$this->load->view(admin_url().'membertype',$data);
	}
	
	public function mod()
	{
		$membertype_id = $this->uri->segment(4);
		$act = $membertype_id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['list'] = $this->membertype_model->getonemembertype();
		}
		
		$this->load->view(admin_url().'membertype_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('title', '类型名称', 'required');
		$this->form_validation->set_rules('rank', '排序ID', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data['cate_list'] = $this->membertype_model->getcates();
			$this->load->view(admin_url().'membertype_mod', $data);
		}else{
			$res = $this->membertype_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."membertype/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."membertype/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->membertype_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."membertype/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."membertype/index",'2');
		}
	}
	
	
}
