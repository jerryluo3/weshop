<?php
class Manager extends MY_Controller {
		
    public function __construct()
    {
        parent::__construct();
        $this->load->model(admin_url().'manager_model');
		$this->load->library('form_validation');
    }
	
	public function index(){
		$data = $this->manager_model->getManagerList();
		
		$this->load->view(admin_url().'manager', $data);
	}
	
	
	
	public function mod(){
		$act = $this->uri->segment(4);
		$data['title'] = $this->actionType($act)."管理员";
		//$data['username'] = $this->session->username;
		$act = $act > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		
		if($act == "mod"){
			$id = intval($this->uri->segment(4));
			$data['row'] = $this->manager_model->getonerow($id);			
		}
		$data['cate_list'] = $this->manager_model->getmtypelist();
		
		$this->load->view(admin_url().'manager_mod', $data);
	}
	
	public function add(){
		//$data = array();
		$data['act'] = "add";
		//$data['list']['cat_title'] = '';
		$data['cate_list'] = $this->Manager_model->getmtypelist();
	
		$this->load->view(admin_url().'manager_mod',$data);
	}
	
	public function del(){
		//$data = array();
		$id = $this->uri->segment(4);
		
		$this->Manager_model->del($id);
		//$this->formTips('操作成功!',admin_url()."Manager/index",'2');
	}
	
	public function edit(){
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('rank', 'rank', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			$this->formTips('操作失败!',admin_url()."manager/index",'2');
		}
		else
		{
			$this->Manager_model->update();
			$this->formTips('操作成功!',admin_url()."manager/index",'2');
		}
	}
	
	//批量删除 移动
	public function batch_operation()
	{
		$cid=$this->input->post('cid');
		$data=$this->input->post('id');
		
		if($this->input->post('submit_delall')){//批量删除
			if(!$data){
			   $this->formTips('没有选择删除项!',admin_url()."Manager/index",'2');
			} else {
				 $query = $this->Manager_model->deletec($data);
				 if($query){
				   $this->formTips('删除成功!',admin_url()."Manager/index",'2');
				 }  
			 }
		}
			if($this->input->post('submit_move')){//批量移动
			          if(!$cid || !$data){
						$this->formTips('没有选择移动项!',"Manager/index",'2');
						
					  }else{
						  $query = $this->Manager_model->movec($cid,$data);
						  if($query){
							  $this->formTips('移动成功!',admin_url()."Manager/index",'2');
							  
						  } 
					  }	
			}

	}
	
	//搜索文章列表
	public function search()
	{   
	    $this->load->library('pagination');
	    $config = array(
				'base_url'       => site_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2),
				'total_rows'     => $this->content_model->search_content_nums($this->input->post('cid'),$this->input->post('title')),
				'per_page'       => 14,
				'num_Manager'      => 5,
				'first_link'     => FALSE,
				'last_link'      => FALSE,
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
				'prev_link'      => "<i class='iconfont'>&#xe63e;</i>",
				'next_link'      => "<i class='iconfont'>&#xe63b;</i>"
	   );
	    $this->pagination->initialize($config);
		
	    $data=array(
				 'newslist'       => $this->content_model->search_content($this->input->post('cid'),$this->input->post('title'),$config['per_page'],$this->uri->segment(3)),
				 'article_nums'  => $this->content_model->search_content_nums($this->input->post('cid'),$this->input->post('title')),
				 'catesoption'    => $this->category_model->getcats('',1)
	     ); 

	   $this->load->view(admin_url().'Manager',$data);

	}
	
	
	
	private function actionType($act){
		switch($act){
			case "add": return "添加";break;
			case "update": return "修改";break;
			case "del": return "删除";break;
			case "delall": return "批量删除";break;
			case "check": return "审核";break;
			case "checkall": return "批量审核";break;
			default: return "添加";break;
		}	
	}
	
	
    
}