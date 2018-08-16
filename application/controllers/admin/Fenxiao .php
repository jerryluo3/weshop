<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fenxiao extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		//$this->load->model(admin_url().'fenxiao_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		p('123');die();
		$data['fenxiao'] = $this->db->order_by('mem_id desc')->get('member')->row_array();
		$this->load->view(admin_url().'fenxiao',$data);
	}

}
