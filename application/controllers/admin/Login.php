<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {	

	public function __construct()
    {
        parent::__construct();
		$this->load->helper('cookie');
		$this->load->library('form_validation');
		$this->load->model(admin_url().'login_model');
    }
	
	/**
	 * 登录控制器
	 */
	public function index()
	{
		
		$userlogininfo = get_cookie('userlogininfo_zj');
		
		$data['userlogininfo'] = json_decode($userlogininfo,true);
		
		$this->load->view(admin_url().'login',$data);
	}
	
	/**
	 * 登录
	 */
	public function login()
	{	
		
		//$data['captcha'] = $this->getCaptcha();
	
		$data['title'] = '管理中心登录';
	
		$this->form_validation->set_rules('username', '用户名', 'required');
		$this->form_validation->set_rules('password', '密码', 'required');
		//$this->form_validation->set_rules('safecode', '验证码', 'required');
		
	
		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view(admin_url().'login', $data);
	
		}
		else
		{
			$res = $this->login_model->check_login();

			if($res['err'] == ''){
				 $this->formTips('登录成功!',admin_url()."home/index",'2');
			}else{
				
				$this->formTips($res['err'],admin_url()."login/index",'2');
			}
		}
	}
	
	public function quit(){
		$this->session->sess_destroy();	
		$this->formTips('退出成功!',admin_url()."login/index",'2');
	}
	
	
	public function formTips($tips="",$url="/",$refreshTime="1"){

		$data = array(
			'Tips'=> $tips,
			'url'=> $url,
			'refreshTime'=> $refreshTime
		);
		$this->load->view(admin_url().'formTips',$data);
	}
	
	
	/**
	 * 验证码
	 */
	public function code(){
		$config = array(
			'width'	=>	116,
			'height'=>	46,
			'bgColor'=>	'#68b71a',
			'fontColor'=>'#ffffff',
			'codeLen'=>	4,
			'fontSize'=>16
			);
		$this->load->library('code', $config);

		$this->code->show();

	}
	
}
