<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum extends MY_Controller {

	/**
	 * Forum
	 *
	 */
	 
	public function __construct(){
		parent::__construct();
		$this->load->model('forum_model');	
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$data = $this->forum_model->getThreadList();
		$data['sysconfig'] = $this->getSysConfigs();		
		$this->load->view('pages/forum',$data);		
	}
	
	public function add()
	{
		$data = $this->forum_model->headerSubMenu();
		$data['sysconfig'] = $this->getSysConfigs();		
		$this->load->view('pages/forum_add',$data);		
	}
	
	public function thread()
	{
		$data = $this->forum_model->getOneThread();
		$data['sysconfig'] = $this->getSysConfigs();		
		$this->load->view('pages/forum_thread',$data);		
	}
	
	public function login()
	{
		$data = $this->forum_model->headerSubMenu();
		$data['sysconfig'] = $this->getSysConfigs();		
		$this->load->view('pages/forum_login',$data);		
	}
	
	
	//调用系统设置
	public function getSysConfigs(){
		 $this->load->database();
		 $query = $this->db->get('luo_configs');
		 return $query->result_array();
	}
	
	
	
}
