<?php
class Productscategory extends MY_Controller {
	private $cates;
	private $res;
		
    public function __construct()
    {
        parent::__construct();
		$this->load->library('productscate');
        $this->load->model(admin_url().'productscategory_model');
		$this->load->library('form_validation');
    }
	
	public function index(){
		
		$data = $this->productscategory_model->get_category();
		$this->load->view(admin_url().'productscategory', $data);
	}
	
	
	
	public function mod(){
		$act = $this->uri->segment(4);
		//$data['username'] = $this->session->username;
		$act = $act == 'add' ? 'add' : 'mod';
		$data['act'] = $act;
		
		if($act == "mod"){
			$cat_id = intval($this->uri->segment(4));
			$data['list'] = $this->productscategory_model->getonecate($cat_id);
			if(isset($data['list']['cat_fid']) && $data['list']['cat_fid'] > 0){
				$data['flist'] = $this->productscategory_model->getonecate($data['list']['cat_fid']);
			}			
		}
		$fid = isset($data['list']['cat_fid']) && $data['list']['cat_fid'] > 0 ? $data['list']['cat_fid'] : 0;
		$data['catesoption'] = $this->productscategory_model->getcats('',1,$_SESSION['user_purview'],$fid);
		
		$this->load->view(admin_url().'productscategory_mod', $data);
	}
	
	
	public function del(){
		//$data = array();
		$id = $this->uri->segment(4);
		$res = $this->productscategory_model->del($id);
		
		echo $res;
	}
	
	
    
}