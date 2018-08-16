<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'company_model');
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->company_model->getCompanyList();
		$data['state'] = 0;
		$data['industrys']	= $this->company_model->getindustry_option();
		$this->load->view(admin_url().'company',$data);
	}
	
	public function companyview()
	{
		
		$data = $this->company_model->getonecompany();	
		$this->load->view(admin_url().'company_view',$data);
	}
	
	public function companycheckview()
	{
		
		$data = $this->company_model->getonecompany();	
		$this->load->view(admin_url().'company_checkview',$data);
	}
	
	public function companycheck()
	{
		
		$res = $this->company_model->companycheck();
		if($res['err'] == ''){
			 $data['tips'] = '操作成功';
			 $this->load->view(admin_url().'companycheck_result', $data);
		}else{
			$data['tips'] = '操作失败，'.$res['err'];
			$this->load->view(admin_url().'companycheck_result', $data);
		}
	}
	
	public function checklog(){
		
		$data = $this->company_model->getCompanyChecklogList();	
		$this->load->view(admin_url().'company_checklog_view',$data);
	}
	
	public function black()
	{
		
		$data = $this->company_model->getCompanyBlackList();	
		$data['industrys']	= $this->company_model->getindustry_option();
		$this->load->view(admin_url().'company_black',$data);
	}
	
	public function blackmod()
	{
		$id = $this->uri->segment(4);
		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		if($act == "mod"){
			$data['row'] = $this->company_model->getonecompanyblack();
		}
		
		$this->load->view(admin_url().'companyblack_mod', $data);
	}
	
	public function blackedit(){
		$this->form_validation->set_rules('companyname', '公司名称', 'required|min_length[6]');
		
		if ($this->form_validation->run() === FALSE){
			$data['id'] = $this->input->post('id');
			$this->load->view(admin_url().'companyblack_mod', $data);
		}else{
			$res = $this->company_model->blackupdate();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."company/black",'2');
			}else{
				$this->formTips($res['err'],admin_url()."company/black",'2');
			}
		}
	}
	
	public function blackdel(){
		$res = $this->company_model->blackdel();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."company/black",'2');
		}else{
			$this->formTips($res['err'],admin_url()."company/black",'2');
		}
	}
	
	public function blackimport(){
		$data['title'] = '黑名单导入';
		$act = $this->uri->segment(4);
		
		$this->form_validation->set_rules('userfile', '导入文件', 'required');
		
		if ($act != 'doimport'){
			$this->load->view(admin_url().'blackimport', $data);
		}else{
			$this->company_model->importblackcompany();
			$this->formTips('操作成功!',admin_url()."company/blackimport",'2');
		}
		
					
	}
	
	
	public function import(){
		$data['title'] = '企业导入';
		$act = $this->uri->segment(4);
		
		$this->form_validation->set_rules('userfile', '导入文件', 'required');
		
		if ($act != 'doimport'){
			$this->load->view(admin_url().'companyimport', $data);
		}else{
			$this->company_model->importcompany();
			$this->formTips('操作成功!',admin_url()."company/import",'2');
		}
		
					
	}	
	
	public function send()
	{
		
		$data = $this->company_model->getCompanySendList();
		$this->load->view(admin_url().'company_send',$data);
	}
	
	public function sendview()
	{
		
		$data = $this->company_model->getonecompanysend();	
		$this->load->view(admin_url().'company_sendview',$data);
	}
	
	public function sendcheckview()
	{
		
		$data = $this->company_model->getonecompanysend();	
		$this->load->view(admin_url().'company_sendcheckview',$data);
	}
	
	public function sendcheck()
	{
		
		$res = $this->company_model->sendcheck();
		if($res['err'] == ''){
			 $data['tips'] = '操作成功';
			 $this->load->view(admin_url().'companycheck_result', $data);
		}else{
			$data['tips'] = '操作失败，'.$res['err'];
			$this->load->view(admin_url().'companycheck_result', $data);
		}
	}
	
	public function sendchecklog(){
		
		$data = $this->company_model->getCompanySendChecklogList();	
		$this->load->view(admin_url().'company_sendchecklog_view',$data);
	}
	
	public function sendmessage(){
		$data = $this->company_model->getonecompany();
		$this->load->view(admin_url().'sendmessage',$data);
	}
	
	public function sendmessageok(){
		$this->form_validation->set_rules('content', '消息内容', 'required|min_length[6]');
		
		
		
		if ($this->form_validation->run() === FALSE){
			$com_id = $this->input->post('to_id');
			if(!$com_id) return '';
			$data = $this->db->where(array('com_id'=>$com_id))->get('company')->row_array();
			$this->load->view(admin_url().'sendmessage', $data);
		}else{
			$res = $this->company_model->sendmessageok();
			if($res['err'] == ''){
				 $data['tips'] = '操作成功';
				 $this->load->view(admin_url().'sendmessage_result', $data);
			}else{
				$data['tips'] = '操作失败，'.$res['err'];
				$this->load->view(admin_url().'sendmessage_result', $data);
			}
		}
		
	}
	
	
	
	
	public function del(){
		$res = $this->company_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."company/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."company/index",'2');
		}
	}
	
	public function repass(){
		$data = $this->company_model->getonecompany();	
		$this->load->view(admin_url().'company_repass',$data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('newpass', '新密码', 'required|min_length[6]');
		$this->form_validation->set_rules('renewpass', '确认新密码', 'required|min_length[6]');
		
		if ($this->form_validation->run() === FALSE){
			$data['com_id'] = $this->input->post('com_id');
			$this->load->view(admin_url().'company_repass', $data);
		}else{
			$res = $this->company_model->update();
			if($res['err'] == ''){
				 $data['tips'] = '操作成功';
				 $this->load->view(admin_url().'company_repass_result', $data);
			}else{
				$data['tips'] = '操作失败，'.$res['err'];
				$this->load->view(admin_url().'company_repass_result', $data);
			}
		}
	}
	
	
	//搜索企业列表
	public function search()
	{   
		
	    $this->load->library('pagination');
		$page_size = 20;
		$cid = $this->input->post('cid');
		$ncid = $this->uri->segment(4);
		$cid = $cid > 0 ? $cid : $ncid;
		$scid = $cid > 0 ? $cid : 0;
		
		
		$title = $this->input->post('title');
		$state = intval($this->input->post('state'));
		
		$nstate = $this->uri->segment(5);
		$state = isset($nstate) ? $nstate : $state;

		$ntitle = urldecode($this->uri->segment(6));
		//echo $ntitle;
		$mtitle = !empty($title) ? $title : ( !empty($ntitle) ? $ntitle : '' );
		//echo $mtitle;
		$stitle = !empty($title) ? $title : ( !empty($ntitle) ? $ntitle : 0 );
		//echo $cid.",".$stitle;
		
		
	    $config = array(
				'base_url'       => site_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/search/'.$scid.'/'.$state.'/'.$stitle,
				'total_rows'     => $this->company_model->search_content_nums($cid,$state,$mtitle),
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 7,
				'full_tag_open'  => "<ul class='paginList'>",//关闭标签
				'full_tag_close' => '</ul>',
				'num_tag_open'   => '<li class="paginItem">',	//数字html
				'num_tag_close'  => '</li>',	//当前页html
				'cur_tag_open'   => '<li class="paginItem current"><a href="javascript:;">',
				'cur_tag_close'  => '</a></li>',
				'next_tag_open'  => '<li class="paginItem">',	//上一页下一页html
				'next_tag_close' => '</li>',
				'prev_tag_open'  => '<li class="paginItem">',
				'prev_tag_close' => '</li>',
				'prev_link'      => '<span class="pagepre"></span>',
				'next_link'      => '<span class="pagenxt"></span>'
	   );
	    $this->pagination->initialize($config);
		
	    $data=array(
				 'company_list'  => $this->company_model->search_content($cid,$state,$mtitle,$config['per_page'],$this->uri->segment(7)),
				 'total_nums'  => $this->company_model->search_content_nums($cid,$state,$mtitle),
				 'industrys'   => $this->company_model->getindustry_option($cid)
	     ); 
		 $page = intval($this->uri->segment(7));
		 $data['page'] = ($page/$page_size) + 1;
		 $data['title'] = $mtitle;
		 $data['state'] = $state;

	   $this->load->view(admin_url().'company',$data);

	}
	
	
}
