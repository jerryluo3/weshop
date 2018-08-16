<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribution extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'distribution_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->distribution_model->getDistributionList();
		$this->load->view(admin_url().'distribution',$data);
	}
	
	public function cash(){
		$data = $this->distribution_model->getCashList();
		$this->load->view(admin_url().'distribution_cash',$data);	
	}
	
	public function tixian(){
		$data = $this->distribution_model->getTixianList();
		$this->load->view(admin_url().'distribution_tixian',$data);	
	}
	
	public function jxs(){
		$data = $this->distribution_model->getJxsList();
		$this->load->view(admin_url().'distribution_jxs',$data);	
	}

}
