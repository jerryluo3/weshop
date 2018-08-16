<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wechat extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'wechat_model');
		$this->load->library('form_validation');
	}

	public function setting()
	{
		$wxconfig_url = 'application/cache/static/wxconfigs.php';
		if(file_exists($wxconfig_url)){
			require_once $wxconfig_url;	
			$data['wxconfigs'] = $wxconfig;
		}else{
			$data['wxconfigs'] = $this->wechat_model->getWeChatConfigs();
		}
		
		$this->load->view(admin_url().'wechat_settings',$data);
	}
	
	
	public function menus()
	{
		$menu_list = 'application/cache/static/menu_list.php';
		if(file_exists($menu_list)){
			require_once $menu_list;	
			$data['menu_list'] = $menu_list;
		}else{
			$menus_arr = $this->wechat_model->getWeChatMenuList();
			if(!empty($menus_arr)){
				$data['menu_list'] = $this->resetMenusData($menus_arr);	
			}else{
				$data['menu_list'] = array();	
			}
		}
		
		
		$this->load->view(admin_url().'wechat_menu',$data);
	}
	
	public function modMenu()
	{
		$id = $this->uri->segment(4);
		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		if($act == "mod"){
			$data['row'] = $this->wechat_model->getOneMenu();
		}
		$data['cate_list'] = $this->db->where(array('fid'=>0))->order_by('rank asc,id asc')->get('wechat_menu')->result_array();
		
		$this->load->view(admin_url().'wechat_menu_mod',$data);
	}
	
	public function resetMenusData($arr){
		$nr = array();
		foreach($arr as $ar){
			if($ar['fid'] == 0){
				$nr[$ar['id']] = $ar;	
			}else{
				$nr[$ar['fid']]['subs'][] = $ar;	
			}
		}
		return $nr;
	}
	
	public function reply(){
		$act = $this->uri->segment(4);
		
		switch($act){
			case "subscribe":$this->replySubscribe();
		}	
	}
	
	public function replySubscribe(){
		$data['row'] = $this->db->order_by('id desc')->get('wechat_reply_subscribe')->row_array();
		
		$this->load->view(admin_url().'wechat_reply_subscribe',$data);	
	}
	
	
	
	
}
