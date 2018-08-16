<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'member_model');
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->member_model->getMemberList();
		$this->load->view(admin_url().'member',$data);
	}
	
	public function consume()
	{
		$data = $this->member_model->getMemberConsumeList();
		$this->load->view(admin_url().'member_consume',$data);
	}
	
	public function recharge()
	{
		$data = $this->member_model->getMemberRechargeList();
		$this->load->view(admin_url().'member_recharge',$data);
	}
	
	public function setting(){
		$this->form_validation->set_rules('username', '用户名', 'required');
		
		if ($this->form_validation->run() === FALSE){
			$data = array();
			$this->load->view(admin_url().'member_setting', $data);
		}else{
			$res = $this->member_model->updatesetting();
			if($res['err'] == ''){
				$this->formTips('操作成功!',admin_url()."member/setting",'2');
			}else{
				$this->formTips($res['err'],admin_url()."member/setting",'2');
			}
		}	
	}
	
	public function del(){
		$res = $this->member_model->del();
		//if($res['err'] == ''){
//			 $this->formTips('操作成功!',admin_url()."member/index",'2');
//		}else{
//			$this->formTips($res['err'],admin_url()."member/index",'2');
//		}
	}
	
	public function repass(){
		$data = $this->member_model->getonemember();	
		$this->load->view(admin_url().'member_repass',$data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('newpass', '新密码', 'required|min_length[6]');
		$this->form_validation->set_rules('renewpass', '确认新密码', 'required|min_length[6]');
		
		if ($this->form_validation->run() === FALSE){
			$data['com_id'] = $this->input->post('com_id');
			$this->load->view(admin_url().'member_repass', $data);
		}else{
			$res = $this->member_model->update();
			if($res['err'] == ''){
				 $data['tips'] = '操作成功';
				 $this->load->view(admin_url().'member_repass_result', $data);
			}else{
				$data['tips'] = '操作失败，'.$res['err'];
				$this->load->view(admin_url().'member_repass_result', $data);
			}
		}
	}
	
	
	public function search()
	{   
		
	    $title = $this->uri->segment(4);
		
		if(!empty($title)){
			$this->db->like('phone',$title);	
			$this->db->or_like('username',$title);	
		}
		$data['member_list'] = $this->db->order_by('id desc')->limit(100)->get('users')->result_array();
		$data['page_list'] = '';

	   $this->load->view(admin_url().'member',$data);

	}
	
	public function exportMember(){
		
		$data = array();
		$this->load->view(admin_url().'member_export',$data);	
	}
	
	public function onrecharge(){
		
		$data = array();
		$this->load->view(admin_url().'member_onrecharge',$data);	
	}
	
	
}
