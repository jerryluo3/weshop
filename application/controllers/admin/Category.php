<?php
class Category extends MY_Controller {
	private $cates;
	private $res;
		
    public function __construct()
    {
        parent::__construct();
		$this->load->library('cate');
        $this->load->model(admin_url().'category_model');
		$this->load->library('form_validation');
    }
	
	public function index(){
		
		$data['title'] = '栏目管理';
		$data = $this->category_model->get_category();
		$this->load->view(admin_url().'category', $data);
	}
	
	
	
	public function mod(){
		$act = $this->uri->segment(3);
		$data['title'] = $this->actionType($act)."栏目";
		//$data['username'] = $this->session->username;
		$data['act'] = $act;
		
		if($act == "mod"){
			$cat_id = intval($this->uri->segment(4));
			$data['list'] = $this->category_model->getonecate($cat_id);
			if(isset($data['list']['cat_fid']) && $data['list']['cat_fid'] > 0){
				$data['flist'] = $this->category_model->getonecate($data['list']['cat_fid']);
			}
			$fid = isset($data['list']['cat_fid']) && $data['list']['cat_fid'] > 0 ? $data['list']['cat_fid'] : 0;
			$data['catesoption'] = $this->category_model->getcats('',1,$_SESSION['user_purview'],$fid);
		}
		
		$this->load->view(admin_url().'category_mod', $data);
	}
	
	public function add(){
		//$data = array();
		$data['act'] = "add";
		//$data['list']['cat_title'] = '';
		$data['catesoption'] = $this->category_model->getcats();
		$this->load->view(admin_url().'category_mod',$data);
	}
	
	public function del(){
		//$data = array();
		$id = $this->uri->segment(4);
		$res = $this->category_model->del($id);
		
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."category/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."category/index",'2');
		}
	}
	
	public function edit(){
		$this->form_validation->set_rules('cat_name', 'cat_name', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			$data['title'] = "操作失败";
			$data['content'] = "操作失败";
			$data['result'] = "warning";
			$data['all_cat'] = $this->category_model->getcats();
			$data['url'] = site_url(admin_url()."category/");
			$this->load->view(admin_url().'category_mod', $data);
		}
		else
		{
			$res = $this->category_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."category/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."category/index",'2');
			}
		}
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