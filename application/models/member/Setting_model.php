<?php
class Setting_model extends CI_Model {
	
	
	public function update(){
	
		$this->db->update('ruzhu',array('shop_password'=>md5($this->input->post('password'))),array('shop_id'=>$_SESSION['member_id']));


		$data['err'] = '';
		return $data;
		//$this->db->insert('configs');
	}
    
}