<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'feedback_model');
		$this->load->library('form_validation');
		$this->load->helper('myfun');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->feedback_model->getFeedbackList();	
		$this->load->view(admin_url().'feedback',$data);
	}
	
	
	public function del(){
		$res = $this->feedback_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."feedback/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."feedback/index",'2');
		}
	}
	
	//搜索文章列表
	public function search()
	{   
		
	    $this->load->library('pagination');
		$page_size = 20;
		$cid = $this->input->post('cid');
		$ncid = $this->uri->segment(4);
		$cid = $cid > 0 ? $cid : $ncid;
		$scid = $cid > 0 ? $cid : 0;
		$title = $this->input->post('title');
		$ntitle = urldecode($this->uri->segment(5));
		//echo $ntitle;
		$mtitle = !empty($title) ? $title : ( !empty($ntitle) ? $ntitle : '' );
		//echo $mtitle;
		$stitle = !empty($title) ? $title : ( !empty($ntitle) ? $ntitle : 0 );
		//echo $cid.",".$stitle;
		
		
	    $config = array(
				'base_url'       => site_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/search/'.$scid.'/'.$stitle,
				'total_rows'     => $this->content_model->search_content_nums($cid,$mtitle),
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 6,
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
				 'content_list'  => $this->content_model->search_content($cid,$mtitle,$config['per_page'],$this->uri->segment(6)),
				 'total_nums'  => $this->content_model->search_content_nums($cid,$mtitle),
				 'catesoption'   => $this->category_model->getcats('',1,$_SESSION['user_purview'],$cid)
	     ); 
		 $page = intval($this->uri->segment(6));
		 $data['page'] = ($page/$page_size) + 1;
		 $data['title'] = $mtitle;

	   $this->load->view(admin_url().'content',$data);

	}
	
	
}
