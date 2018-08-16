<?php
class Downfilecates extends MY_Controller {
	private $cates;
	private $res;
		
    public function __construct()
    {
        parent::__construct();
		$this->load->library('cate',array('tableName'=>'downfile_category'));
        $this->load->model(admin_url().'Downfilecates_model');
		$this->load->library('form_validation');
    }
	
	public function index(){
		
		$data['title'] = '栏目管理';
		$data = $this->Downfilecates_model->get_category();
		$this->load->view(admin_url().'downfilecates', $data);
	}
	
	
	
	public function mod(){
		$act = $this->uri->segment(3);
		$data['title'] = $this->actionType($act)."栏目";
		//$data['username'] = $this->session->username;
		$data['act'] = $act;
		
		if($act == "mod"){
			$cat_id = intval($this->uri->segment(4));
			$data['list'] = $this->Downfilecates_model->getonecate($cat_id);
			if(isset($data['list']['cat_fid']) && $data['list']['cat_fid'] > 0){
				$data['flist'] = $this->Downfilecates_model->getonecate($data['list']['cat_fid']);
			}
			$fid = isset($data['list']['cat_fid']) && $data['list']['cat_fid'] > 0 ? $data['list']['cat_fid'] : 0;
			$data['catesoption'] = $this->Downfilecates_model->getcats('',1,$_SESSION['user_purview'],$fid);
		}
		
		$this->load->view(admin_url().'downfilecates_mod', $data);
	}
	
	public function makeFclass(){
		$this->Downfilecates_model->makeFclass();	
	}
	
	public function add(){
		//$data = array();
		$data['act'] = "add";
		//$data['list']['cat_title'] = '';
		$data['catesoption'] = $this->Downfilecates_model->getcats();
		$this->load->view(admin_url().'downfilecates_mod',$data);
	}
	
	public function del(){
		//$data = array();
		$id = $this->uri->segment(4);
		$res = $this->Downfilecates_model->del($id);
		
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."downfilecates/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."downfilecates/index",'2');
		}
	}
	
	public function edit(){
		$this->form_validation->set_rules('cat_name', 'cat_name', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			$data['title'] = "操作失败";
			$data['content'] = "操作失败";
			$data['result'] = "warning";
			$data['all_cat'] = $this->Downfilecates_model->getcats();
			$data['url'] = site_url(admin_url()."downfilecates/");
			$this->load->view(admin_url().'header', $data);
			$this->load->view(admin_url().'dialog',$data);
			$this->load->view(admin_url().'footer');
		}
		else
		{
			$res = $this->Downfilecates_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."downfilecates/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."downfilecates/index",'2');
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