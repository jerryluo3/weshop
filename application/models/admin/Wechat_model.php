<?php
class Wechat_model extends CI_Model {
	
	
	public function getWeChatConfigs(){
		$data = $this->db->order_by('id desc')->get('wechat_configs')->row_array();
		return $data;
	}
	
	
	public function getMemberList()
	{
		
		
		$total_nums = $this->db->count_all_results('users');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['member_list'] = $this->db->order_by('id DESC')->limit($page_size,$offset)->get('users')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	public function getWeChatMenuList()
	{
		
		$data = $this->db->order_by('rank asc,id asc')->get('wechat_menu')->result_array();		
		return $data;
	}
	
	public function getOneMenu(){
		$id = intval($this->uri->segment(4));
		if($id > 0){
			return $this->db->where(array('id'=>$id))->get('wechat_menu')->row_array();	
		}else{
			return array();	
		}	
	}
    
}