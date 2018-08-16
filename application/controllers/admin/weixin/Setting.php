<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'weixin/setting_model');	
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	
	
	public function info(){
		$this->form_validation->set_rules('wxname', '公众号名称', 'required');
		$this->form_validation->set_rules('wxid', '公众号原始id', 'required');
		$this->form_validation->set_rules('weixin', '微信号', 'required');
		$this->form_validation->set_rules('province', '省', 'required');
		$this->form_validation->set_rules('city', '市', 'required');
	
		if ($this->form_validation->run() === FALSE){
			$data['info'] = $this->setting_model->getbasicinfo();
			$this->load->view(admin_url().'weixin/setting/info', $data);
		}else{
			$res = $this->setting_model->infoupdate();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."weixin/setting/info",'2');
			}else{
				$this->formTips($res['err'],admin_url()."weixin/setting/info",'2');
			}
		}	
	}
	//文本回复列表
	public function text(){
		$data = $this->setting_model->gettextlist();
		$this->load->view(admin_url().'weixin/setting/text', $data);
	}
	//文本回复编辑
	public function textmod(){
		$action = $this->uri->segment(4);
		$edit = $this->uri->segment(5);
		$act = $edit == 'add' ? 'add' : 'mod';
		$data['act'] = $act;
		
		
		if($edit == 'del'){//添加修改
			$res = $this->setting_model->textdel();
			$this->formTips($res['err'],admin_url()."weixin/setting/text",'2');	
		}elseif($action == 'textmod'){
			if($edit == 'edit'){
				$this->form_validation->set_rules('title', '关键字', 'trim|required');
				$this->form_validation->set_rules('content', '回复内容', 'required');	
				
				if ($this->form_validation->run() === FALSE){
					$data['info'] = $this->setting_model->getbasicinfo();
					$this->load->view(admin_url().'weixin/setting/text_mod', $data);
				}else{
					$res = $this->setting_model->textupdate();
					if($res['err'] == ''){
						 $this->formTips('操作成功!',admin_url()."weixin/setting/text",'2');
					}else{
						$this->formTips($res['err'],admin_url()."weixin/setting/text",'2');
					}
				}
			}else{
				$data['info'] = $this->setting_model->gettextinfo();		
				$this->load->view(admin_url().'weixin/setting/text_mod', $data);	
			}
		}
		
		
		
		
	}
}
