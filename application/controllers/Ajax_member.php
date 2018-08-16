<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_member extends MY_Controller {

	/**
	 * AJAX UPDATE
	 *
	 */
		
	public function discountMod(){
		
		$action = $this->input->post('action');
		$id = intval($this->input->post('id'));
		$arr = $_POST;
		unset($arr['action']);
		unset($arr['id']);
		
		$arr['endtime'] = $arr['endtime'] > 0 ? strtotime($arr['endtime']) : 0;
		
		if($action == 'add'){			
			$arr['addtime'] = time();
			$arr['leftnums'] = $arr['nums'];
			$arr['mid'] = $_SESSION['member_id'];
			$this->db->insert('discount',$arr);
		}
		if($action == 'mod'){
			$this->db->update('discount',$arr,array('id'=>$id));	
		}

		$data['status'] = 200;
		echo json_encode($data);
	}
	
		
	public function productsMod(){
		
		$action = $this->input->post('action');
		$id = intval($this->input->post('id'));
		
		$arr = $_POST;		
		$pictures = json_encode($arr['pictures']);
		$arr['pics'] = $pictures;
		unset($arr['action']);
		unset($arr['id']);
		unset($arr['pictures']);

		if($action == 'add'){			
			$arr['addtime'] = time();
			$arr['uid'] = $_SESSION['member_id'];
			$this->db->insert('products',$arr);
		}
		if($action == 'mod'){
			$this->db->update('products',$arr,array('id'=>$id));	
		}

		$data['status'] = 200;
		echo json_encode($data);
	}
		
	
	
}
