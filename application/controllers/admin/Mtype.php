<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mtype extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'mtype_model');
		$this->load->model(admin_url().'category_model');
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->mtype_model->getMtypeList();		
		$this->load->view(admin_url().'mtype',$data);
	}
	
	public function mod()
	{
		$id = intval($this->uri->segment(4));
		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['list'] = $this->mtype_model->getonemtype();
		}else{
			$data['list'] = array();	
		}
		//$purview = !empty($data['list']['purview']) ? $data['list']['purview'] : '';
//		$data['catesoption'] = $this->category_model->getcats('',3,$purview);
//		$data['cate_list'] = $this->mtype_model->getcates();
		
		$this->load->view(admin_url().'mtype_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('title', '管理员类型名称', 'required');
		$this->form_validation->set_rules('rank', '管理员排序ID', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data['cate_list'] = $this->mtype_model->getcates();
			$this->load->view(admin_url().'mtype_mod', $data);
		}else{
			$res = $this->mtype_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."mtype/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."mtype/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->mtype_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."mtype/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."mtype/index",'2');
		}
	}
	
	
}
