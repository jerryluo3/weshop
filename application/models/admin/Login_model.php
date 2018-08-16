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

		
		$row = $this->db->where(array('username'=>$username,'password'=>md5($password)))->join('mtype','manager.usergroup=mtype.id')->limit(1)->get("manager")->row_array();

		if(!empty($row)){
			if($row['state'] == 1){
				$logincounts = $row['logincounts']+1;
				$data = array(
					'lastloginip'  	=> $row['loginip'],
					'loginip'       => $this->input->ip_address(),
					'lastlogintime' => $row['logintime'],
					'logintime' 	=> time(),
					'logincounts' 	=> $logincounts
				);
				$this->db->update('manager',$data,array('id'=>$row['id']));
				
				
				$cookieData = $sessionData = array(
					'user_name'	=> $username,
					'user_password'	=> $password,
					'user_id'		=> $row['id'],
					'user_name'	=> $username,
					'user_group'	=> $row['usergroup'],
					'user_purview'	=> explode(',',$row['purview']),
					'user_logintime' => time(),
					'user_lastlogintime' => $row['logintime']
				);
				
				$this->session->set_userdata($sessionData);
				$remeber = $this->input->post('remeber');
				
				
				if($remeber == 1){
					$this->load->helper('cookie');
					$sessionData['remeber'] = $remeber;
					set_cookie("userlogininfo_zj",json_encode($sessionData),86400*30);
				}else{
					set_cookie("userlogininfo_zj",'',3600);	
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
		
	}
    
    
}