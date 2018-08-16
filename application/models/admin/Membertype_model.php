<?php
class Membertype_model extends CI_Model {
	
	
	public function getMembertypeList()
	{
		
		$cid = intval($this->uri->segment(3));
		$page_size = 20;
		$total_nums = $this->db->count_all_results('member_type');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['membertype_list'] = $this->db->order_by('mt_id','DESC')->limit($page_size,$page)->get('member_type')->result_array();
		$data['total_nums'] = $total_nums;
		$data['page'] = $page;
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
	
	public function getoneMembertype(){
		$id = $this->uri->segment(4);
		if(!$id) return '';
		return $this->db->where(array('mt_id'=>$id))->get('member_type')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('mt_rank desc')->get('member_type')->result_array();	
	}
	
	public function update()
	{
		$id = $this->input->post('id');
		$action = $this->input->post('action');
		
		$title = $this->input->post('title');
		$rank = $this->input->post('rank');
		$addtime = time();
		

		
		
		$data = array(
			'mt_title' 				=> $title,
			'mt_rank' 					=> $rank
		);
		
		//p($data);die();
		
		if($action == "add"){
			$data['mt_addtime'] = 	$addtime;
		}
		
		if($action == "mod" && $id > 0){
			$where = "mt_id=".$id;
			$this->db->update('member_type', $data, $where);					
		}
		if($action == "add"){
			$this->db->insert('member_type', $data);
		}
		
	}
	
	public function del()
	{
		$id = $this->uri->segment(4);
		if($id > 0){		
			$this->db->delete('member_type', array('mt_id' => $id));
			$data['err'] = '';
		}else{
			$data['err'] = '参数出错';	
		}
		
		return $data;
		//$this->db->insert('configs');
	}
	
	public function pagelist($total_nums,$page_size){
		$this->load->library('pagination');

	    $config = array(
				'base_url'       => base_url(admin_url().'membertype/'),
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 4,
				'full_tag_open'  => "<ul class='paginations'>",//关闭标签
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
	}
    
    
}