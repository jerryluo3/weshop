<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downfile extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'downfile_model');	
		$this->load->library('form_validation');
		$this->load->library('cate');
		
	}
	/**
	 * setting
	 */
	public function index()
	{
		
		$data = $this->downfile_model->getDownfileList();
		$fclass = 'application/cache/static/fclass.php';
		if(is_file($fclass)){
			require_once $fclass;
			$data['fclass'] = $fclass;
		}else{
			$data['fclass'] = array();	
		}		
		$this->load->view(admin_url().'Downfile',$data);
	}
	
	public function mod()
	{
		$Downfile_id = $this->uri->segment(4);
		$act = $Downfile_id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$data['row'] = $this->downfile_model->getoneDownfile();
			
			$id = intval($this->uri->segment(4));
			$data['list'] = $this->downfile_model->getoneDownfile($id);
			//$fid = isset($data['list']['cat_fid']) && $data['list']['cat_fid'] > 0 ? $data['list']['cat_fid'] : 0;
			//$data['catesoption'] = $this->downfile_model->getcats('',1,$_SESSION['user_purview'],$data['list']['cidx']);
			$data['cidx_list'] = $this->downfile_model->getcidList($data['list']['cid']);
			
		}
		$data['cid_list'] = $this->downfile_model->getcidList();
		
		$this->load->view(admin_url().'Downfile_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('cid', '资料类别', 'required');
		$this->form_validation->set_rules('cidx', '资料二级类别', 'required');
		$this->form_validation->set_rules('fname', '资料标题', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data['cid_list'] = $this->downfile_model->getcidList();
			$this->load->view(admin_url().'downfile_mod', $data);
		}else{
			$res = $this->downfile_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."Downfile/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."Downfile/index",'2');
			}
		}
		
	}
	
	public function del(){
		$res = $this->downfile_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."Downfile/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."Downfile/index",'2');
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
				'total_rows'     => $this->downfile_model->search_content_nums($cid,$state,$mtitle),
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 7,
				'full_tag_open'  => "<ul class='pagination'>",//关闭标签
				'full_tag_close' => '</ul>',
				'num_tag_open'   => '<li>',	//数字html
				'num_tag_close'  => '</li>',	//当前页html
				'cur_tag_open'   => "<li class='active'><a href='javascript:void(0),'>",
				'cur_tag_close'  => "</a></li>",
				'next_tag_open'  => '<li>',	//上一页下一页html
				'next_tag_close' => '</li>',
				'prev_tag_open'  => '<li>',
				'prev_tag_close' => '</li>',
				'prev_link'      => '<span class="pagepre">上页</span>',
				'next_link'      => '<span class="pagenxt">下页</span>'
	   );
	    $this->pagination->initialize($config);
		
	    $data=array(
				 'downfile_list'  => $this->downfile_model->search_content($cid,$state,$mtitle,$config['per_page'],$this->uri->segment(7)),
				 'total_nums'  => $this->downfile_model->search_content_nums($cid,$state,$mtitle)
	     ); 
		 $page = intval($this->uri->segment(7));
		 $data['page'] = ($page/$page_size) + 1;
		 $data['title'] = $mtitle;
		 $data['state'] = $state;
		 
		 $fclass = 'application/cache/static/fclass.php';
		if(is_file($fclass)){
			require_once $fclass;
			$data['fclass'] = $fclass;
		}

	   $this->load->view(admin_url().'downfile',$data);

	}
	
	
}
