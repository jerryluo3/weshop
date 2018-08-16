<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'setting_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data['configs'] = $this->setting_model->get_configs();		
		$this->load->view(admin_url().'setting',$data);
	}
	
	public function update(){
		$this->form_validation->set_rules('sitename', '网站名称', 'required');
		$this->form_validation->set_rules('sitetitle', '网站标题', 'required');
	
		if ($this->form_validation->run() === FALSE){
			$this->load->view(admin_url().'setting/', $data);
		}else{
			$res = $this->setting_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."home/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."home/index",'2');
			}
		}	
	}
}
