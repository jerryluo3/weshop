<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'content_model');
		$this->load->model(admin_url().'category_model');
		$this->load->library('form_validation');
		$this->load->helper('myfun');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->content_model->getContentList();	
		$data['catesoption'] = $this->category_model->getcats('',1,$_SESSION['user_purview']);	
		$this->load->view(admin_url().'content',$data);
	}
	
	public function mod()
	{
		$id = $this->uri->segment(4);
		$page = intval($this->uri->segment(5));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $page;

		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		if($act == "mod"){
			$data['row'] = $this->content_model->getonecontent();
		}
		$cid = isset($data['row']['cid']) ? $data['row']['cid'] : '';
		$data['catesoption'] = $this->category_model->getcats('',1,$_SESSION['user_purview'],$cid);
		$data['cate_list'] = $this->content_model->getcates();
		
		$this->load->view(admin_url().'content_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('cid', '类别', 'required');
		$this->form_validation->set_rules('title', '标题', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$data['cate_list'] = $this->content_model->getcates();
			$this->load->view(admin_url().'content_mod', $data);
		}else{
			$res = $this->content_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."content/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."content/index",'2');
			}
		}
		
	}
	
	public function contentcheckview()
	{
		
		$data = $this->content_model->getonecontent();	
		$this->load->view(admin_url().'content_checkview',$data);
	}
	
	public function contentcheck()
	{
		
		$res = $this->content_model->contentcheck();
		if($res['err'] == ''){
			 $data['tips'] = '操作成功';
			 $this->load->view(admin_url().'companycheck_result', $data);
		}else{
			$data['tips'] = '操作失败，'.$res['err'];
			$this->load->view(admin_url().'companycheck_result', $data);
		}
	}
	
	public function checkall()
	{
		
		$res = $this->content_model->contentcheckall();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."content",'2');
		}else{
			$this->formTips($res['err'],admin_url()."content",'2');
		}
	}
	
	public function checklog(){
		
		$data = $this->content_model->getContentChecklogList();	
		$this->load->view(admin_url().'content_checklog_view',$data);
	}
	
	public function del(){		
		$res = $this->content_model->del();
		echo $res;
	}
	
	//搜索文章列表
	public function search()
	{   
		$str = mb_convert_encoding($this->uri->segment(4), "UTF-8", "GBK"); 
		$arr = !empty($str) ? explode('-',$str) : array();
		$cid = isset($arr[1]) ? $arr[1] : '';

		
		$data['page'] = intval($this->uri->segment(5));
		
		$data['catesoption'] = $this->category_model->getcats('',1,$_SESSION['user_purview'],$cid);
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				$str = urldecode($arr[0]);
				$this->db->like('title',$str);
				$data['title'] = $arr[0];
			}
			if($arr[1] > 0){
				$this->db->where('content.cid',$arr[1]);	
				$data['cid'] = $arr[1];		
			}
				
		}
		
		$total_nums = $this->db->count_all_results('content');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;
		$data['page'] = $page;
		
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				$str = urldecode($arr[0]);
				$this->db->like('title',$str);
				$data['title'] = $arr[0];
			}
			if($arr[1] > 0){
				$this->db->where('content.cid',$arr[1]);	
				$data['cid'] = $arr[1];		
			}
				
		}
		
		$data['content_list'] = $this->db->order_by('id DESC')->limit($page_size,$offset)->join('category','category.cat_id = content.cid','left')->get('content')->result_array();
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
	   $this->load->view(admin_url().'content',$data);
	    

	}
	
	
}
