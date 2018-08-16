<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {	
	
	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'content_model');
		$this->load->model(admin_url().'setting_model');
		$this->load->model(admin_url().'category_model');
		$this->load->library('form_validation');
	}
	/**
	 * 后台默认控制器
	 */
	public function index()
	{
		$data = array();
		
		//$data['configs'] = $this->setting_model->get_configs();
		
		$this->load->view(admin_url().'index',$data);
	}

}
