<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_admin extends MY_Controller {

	/**
	 * AJAX UPDATE
	 *
	 */
	 
		 
	public function managerMod(){
		
		$usergroup 		=	$this->input->post('usergroup');
		$username 		=	$this->input->post('username');
		$realname 		=	$this->input->post('realname');
		$password 		=	$this->input->post('password');
		$repassword 	= 	$this->input->post('repassword');
		$rank			= 	intval($this->input->post('rank'));
		$state			= 	intval($this->input->post('state'));
		$action 		= 	$this->input->post('action');	
		$id 			= 	intval($this->input->post('id'));
		
		$arr = array(
			'usergroup'		=>		$usergroup,
			'username'		=>		$username,
			'realname'		=>		$realname,
			'rank'			=>		$rank,
			'state' 		=> 		$state
		);
		
		if($action == 'add'){
			$arr['regip'] 		= 	$this->input->ip_address();
			$arr['regtime'] 	= 	$addtime;
			$arr['password'] 	= 	md5($password);
		}
		
		if($action == "mod" && $id > 0){
			if(!empty($password)){
				$data['password'] = md5($password);
			}
			$this->db->update('luo_manager', $arr, array('id'=>$id));					
		}
		if($action == "add"){
			$this->db->insert('luo_manager', $arr);
		}
		$data['err']	=	'';
		echo json_encode($data);
		
	}
	
	
	public function catMod(){
		
		$cat_id = $this->input->post('cat_id');
		$action = $this->input->post('action');
		
		$fid = intval($this->input->post('cat_fid'));
		$cat_name = $this->input->post('cat_name');
		$cat_img = $this->input->post('cat_img');
		
		if($fid > 0){
			$frow = $this->db->where(array('cat_id'=>$fid))->get('category')->row_array();
			$cat_depth = $frow['cat_depth']+1;
		}else{
			$cat_depth = 0;	
		}
		//$this->updateCateSon($fid);
		
		$data = array(
			'cat_fid' 				=> $fid,
			'cat_name' 				=> $cat_name,
			'cat_img' 				=> $cat_img,
			'cat_depth' 			=> $cat_depth
		);
		
		
		if($action == "mod" && $cat_id > 0){
			//判断有没有修改栏目
			$ofrow = $this->db->where(array('cat_id'=>$cat_id))->get('category')->row_array();
			
			if($ofrow['cat_fid'] !== $fid){//如果有修改栏目，更新上级子栏目
				$this->updateCateSon($fid,$cat_id,0);
				$this->updateCateSon($ofrow['cat_fid'],$cat_id,1);
			}	
			
			$where = "cat_id=".$cat_id;
			$this->db->update('category', $data, $where);
			
					
		}else{
			$this->db->insert('category', $data);
			$temp_cat_id = $this->db->insert_id();
			$this->updateCateSon($fid,$temp_cat_id,0);
		}
		
		$data['status'] = 200;
		echo json_encode($data);

	}
	
	public function updateCateSon($fid,$id,$type=0){
		
		if($fid > 0){			
			$frow = $this->db->where(array('cat_id'=>$fid))->get('category')->row_array();
			$temp_son = $frow['cat_son'];
			$temp_son_arr = !empty($temp_son) ? explode(",",$temp_son) : array();
			
			
			if($type ==0){//添加子栏目
				
				if(!in_array($id,$temp_son_arr)){
					$temp_son_arr[] = $id;//加入当前添加的栏目ID
					
					$temp_son_str = implode(",",$temp_son_arr);
					
					$data = array('cat_son'=>$temp_son_str);				
					$where = "cat_id=".$fid;
					$this->db->update('category', $data, $where);
				}
				if($frow['cat_fid'] > 0){
					$this->updateCateSon($frow['cat_fid'],$id,$type);
				}
			}else{//删除子栏目
				foreach($temp_son_arr as $t=> $ta){
					if($ta == $id){
						unset($temp_son_arr[$t]);	
					}
				}
				$temp_son_str = implode(",",$temp_son_arr);
				$data = array('cat_son'=>$temp_son_str);
				$where = "cat_id=".$fid;
				$this->db->update('category', $data, $where);
				if($frow['cat_fid'] > 0){
					$this->updateCateSon($frow['cat_fid'],$id,$type);
				}
			}
		}
	}
	
	public function contentMod(){
		
		$action = $this->input->post('action');
		$id = intval($this->input->post('id'));
		$arr = $_POST;
		unset($arr['action']);
		unset($arr['id']);
		
		$addtime = $this->input->post('addtime');
		$arr['addtime'] = !empty($addtime) ? strtotime($addtime) : time();
		if($action == 'add'){
			$this->db->insert('content',$arr);
		}
		if($action == 'mod'){			
			$this->db->update('content',$arr,array('id'=>$id));	
		}

		$data['status'] = 200;
		echo json_encode($data);
	}
	
	public function updateContentIscommend(){
		$gid = intval($this->input->post('gid'));
		$iscommend = intval($this->input->post('iscommend'));
		$n_iscommend = $iscommend == 1 ? 0 : 1;
		$this->db->update('content',array('iscommend'=>$n_iscommend),array('id'=>$gid));
		$data['status'] = 200;
		echo json_encode($data);	
	}
	
	
	
	public function adsCatesMod(){
		
		$action  = $this->input->post('action');
		$cat_id  = $this->input->post('cat_id');
		$cat_fid  = intval($this->input->post('cat_fid'));
		$cat_name  = $this->input->post('cat_name');
		$cat_rank  = $this->input->post('cat_rank');
		
		$arr = array(
			'cat_fid'		=>	$cat_fid,
			'cat_name'		=>	$cat_name,
			'cat_rank'		=>	$cat_rank
		);
		
		if($action == 'add'){
			$this->db->insert('ads_category',$arr);	
		}else{
			$this->db->update('ads_category',$arr,array('cat_id'=>$cat_id));	
		}
		
		$data['status'] = 200;
		echo json_encode($data);
			
	}
	
	public function adsMod(){
		$action  = $this->input->post('action');
		$ads_id  = $this->input->post('ads_id');
		$ads_cid  = intval($this->input->post('ads_cid'));
		$ads_title  = $this->input->post('ads_title');
		$ads_picture  = $this->input->post('ads_picture');
		$ads_url  = $this->input->post('ads_url');
		$stime = $this->input->post('ads_starttime');
		$ads_starttime  = !empty($stime) ? strtotime($stime) : 0;
		$etime = $this->input->post('ads_endtime');
		$ads_endtime  = !empty($etime) ? strtotime($etime) : 0;
		$ads_state  = $this->input->post('ads_state');
		$ads_rank  = $this->input->post('ads_rank');
		
		//$action = 'add';
//		$ads_cid  = 1;
//		$ads_title  = '1111';
//		$ads_picture  = 'data/uploads/1512615990.jpg';
//		$ads_url  = 'http://www.qiyue99.com/';
//		$stime = 0;
//		$ads_starttime  = !empty($stime) ? strtotime($stime) : 0;
//		$etime = 0;
//		$ads_endtime  = !empty($etime) ? strtotime($etime) : 0;
//		$ads_rank  = 100;

		//$aa = $_POST;
		
		$arr = array(
			'ads_cid'		=>	$ads_cid,
			'ads_title'		=>	$ads_title,
			'ads_picture'	=>	$ads_picture,
			'ads_url'		=>	$ads_url,
			'ads_starttime'	=>	$ads_starttime,
			'ads_endtime'	=>	$ads_endtime,
			'ads_state'		=>	$ads_state,
			'ads_rank'		=>	$ads_rank
		);
		
		if($action == 'add'){
			$this->db->insert('ads',$arr);	
		}else{
			$this->db->update('ads',$arr,array('ads_id'=>$ads_id));	
		}
		
		$data['status'] = 200;
		//$data['result'] = json_encode($arr);
		echo json_encode($data);	
	}
	
	public function linksCatesMod(){
		
		$action  = $this->input->post('action');
		$cat_id  = $this->input->post('cat_id');
		$cat_fid  = intval($this->input->post('cat_fid'));
		$cat_name  = $this->input->post('cat_name');
		$cat_rank  = $this->input->post('cat_rank');
		
		$arr = array(
			'cat_fid'		=>	$cat_fid,
			'cat_name'		=>	$cat_name,
			'cat_rank'		=>	$cat_rank
		);
		
		if($action == 'add'){
			$this->db->insert('links_category',$arr);	
		}else{
			$this->db->update('links_category',$arr,array('cat_id'=>$cat_id));	
		}
		
		$data['status'] = 200;
		echo json_encode($data);
			
	}
	
	public function linksMod(){
		$action  = $this->input->post('action');
		$links_id  = $this->input->post('links_id');
		$links_cid  = intval($this->input->post('links_cid'));
		$links_title  = $this->input->post('links_title');
		$links_picture  = $this->input->post('links_picture');
		$links_url  = $this->input->post('links_url');
		$stime = $this->input->post('links_starttime');
		$links_starttime  = !empty($stime) ? strtotime($stime) : 0;
		$etime = $this->input->post('links_endtime');
		$links_endtime  = !empty($etime) ? strtotime($etime) : 0;
		$links_state  = $this->input->post('links_state');
		$links_rank  = $this->input->post('links_rank');
		
		//$action = 'add';
//		$ads_cid  = 1;
//		$ads_title  = '1111';
//		$ads_picture  = 'data/uploads/1512615990.jpg';
//		$ads_url  = 'http://www.qiyue99.com/';
//		$stime = 0;
//		$ads_starttime  = !empty($stime) ? strtotime($stime) : 0;
//		$etime = 0;
//		$ads_endtime  = !empty($etime) ? strtotime($etime) : 0;
//		$ads_rank  = 100;

		//$aa = $_POST;
		
		$arr = array(
			'links_cid'		=>	$links_cid,
			'links_title'		=>	$links_title,
			'links_picture'	=>	$links_picture,
			'links_url'		=>	$links_url,
			'links_starttime'	=>	$links_starttime,
			'links_endtime'	=>	$links_endtime,
			'links_state'		=>	$links_state,
			'links_rank'		=>	$links_rank
		);
		
		if($action == 'add'){
			$this->db->insert('ads',$arr);	
		}else{
			$this->db->update('ads',$arr,array('ads_id'=>$ads_id));	
		}
		
		$data['status'] = 200;
		//$data['result'] = json_encode($arr);
		echo json_encode($data);	
	}
	
	public function checkRuzhu(){
		$id = $this->input->post('id');
		$this->db->update('ruzhu',array('shop_state'=>1),array('shop_id'=>$id));	
		$data['status'] = 200;
		echo json_encode($data);
	}
	
	
	
}
