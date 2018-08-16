<?php
class Login_model extends CI_Model {
	
	function check_login(){
		
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		//$safecode = $this->input->post('safecode');
		
		if(!isset($_SESSION)){
			session_start();
		}
		//if(strtoupper($safecode) != $_SESSION['code']){
//			$data['err'] = "验证码错误";
//			return $data;	
//		}

		
		$row = $this->db->where(array('shop_name'=>$username))->limit(1)->get("ruzhu")->row_array();

		if(!empty($row)){
			if( (!empty($row['shop_password']) && $row['shop_password'] == md5($password)) || ( empty($row['shop_password']) && $row['shop_mobile'] == $password ) ){
				if($row['shop_state'] == 1){
					
					
					$cookieData = $sessionData = array(
						'member_name'	=> $username,
						'member_password'	=> $password,
						'member_id'		=> $row['shop_id'],
						'member_logintime' => time()
					);
					
					$this->session->set_userdata($sessionData);
					$remeber = $this->input->post('remeber');
					
					
					if($remeber == 1){
						$this->load->helper('cookie');
						$sessionData['remeber'] = $remeber;
						set_cookie("memberlogininfo",json_encode($sessionData),86400*30);
					}else{
						set_cookie("memberlogininfo",'',3600);	
					}
					$data['err'] = "";
					return $data;
				}else{
					$data['err'] = "此用户未通过审核";
					return $data;	
				}		
			}else{
				$data['err'] = "用户名或密码出错";	
				return $data;	
			}
			
		}else{
			$data['err'] = "用户名或密码出错";
			return $data;
		}
		
	}
    
    
}