<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Links extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'links_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->links_model->getLinksList();		
		$this->load->view(admin_url().'links',$data);
	}
	
	public function mod()
	{
		$links_id = $this->uri->segment(4);
		$act = $links_id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['row'] = $this->links_model->getonelinks();
		}
		$data['cate_list'] = $this->links_model->getcates();
		
		$this->load->view(admin_url().'links_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('links_cid', '广告类别', 'required');
		$this->form_validation->set_rules('links_title', '广告名称', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data['cate_list'] = $this->links_model->getcates();
			$this->load->view(admin_url().'links_mod', $data);
		}else{
			$res = $this->links_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."links/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."links/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->links_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."links/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."links/index",'2');
		}
	}
	
	
}
