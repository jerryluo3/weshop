<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	*/
	public function __construct()
	{
		parent::__construct();
		
		$admin_url = $this->uri->segment(1);
		$site_admin_url = admin_url();
		if($admin_url.'/' == $site_admin_url){
			$user_name = $this->session->userdata('user_name');
			$user_id = $this->session->userdata('user_id');
			if(!$user_name || !$user_id){
				redirect(admin_url().'login/index');	
			}
		}
		$method = $this->uri->segment(2);
		if($admin_url == 'member' && $method != 'login' && $method != 'register'){
			$user_name = $this->session->userdata('member_name');
			$user_id = $this->session->userdata('member_id');
			if(!$user_name || !$user_id){
				redirect('member/login');
			}
		}
		
		$this->load->helper('page');

		
		
	}
	
	public function formTips($tips="",$url="/",$refreshTime="1"){

		$data = array(
			'Tips'=> $tips,
			'url'=> $url,
			'refreshTime'=> $refreshTime
		);
		$this->load->view(admin_url().'formTips',$data);
	}

	
	function utf_substr($str,$len){
		if(strlen($str) > $len){
			for($i=0;$i<$len;$i++){
				$temp_str=substr($str,0,1);
				if(ord($temp_str) > 127){
					$i++;
					if($i<$len){
						$new_str[]=substr($str,0,3);
						$str=substr($str,3);
					}
				}else{
					$new_str[]=substr($str,0,1);
					$str=substr($str,1);
				}
			}
			$t_str = join($new_str);
			return $t_str.'...';
		}else{
			return $str;
		}
		
		
	}
	
	function format_content($str){
		$str = htmlspecialchars(strip_tags(stripslashes($str)));
		$str = str_replace('&amp;','',$str);
		$str = str_replace('nbsp;','',$str);
		$str = str_replace(' ','',$str);
		return $str;
	}
	
}
