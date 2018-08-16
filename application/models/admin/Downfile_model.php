<?php
class Downfile_model extends CI_Model {
	
	
	public function getDownfileList()
	{
		
		$cid = intval($this->uri->segment(3));
		$page_size = 20;
		if($cid > 0){
			$this->db->where(array('cid'=>$cid));	
		}
		$total_nums = $this->db->count_all_results('Downfile');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		if($cid > 0){
			$this->db->where(array('cid'=>$cid));	
		}
		$data['downfile_list'] = $this->db->order_by('id DESC')->limit($page_size,$page)->join('downfile_category','downfile_category.cat_id = Downfile.cid')->get('Downfile')->result_array();
		$data['total_nums'] = $total_nums;
		$data['page'] = $page;
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
	
	public function getoneDownfile(){
		$downfile_id = $this->uri->segment(4);
		if(!$downfile_id) return '';
		return $this->db->where(array('id'=>$downfile_id))->get('downfile')->row_array();
	}
	
	public function getcats($ids='',$optionlist=2,$purview=array(),$selectedid=''){
		
		$cids = get_purview_cids();
		if($_SESSION['user_group'] != 1){
			$this->db->where_in('cat_id',$cids);
		}
		if(!empty($ids)){
			$this->db->where_in('cat_id',$ids);	
		}
		$cates = $this->db->get("downfile_category")->result_array();

		$catesoption = $this->cate->resetCategoryData($cates,$optionlist,$purview,$selectedid);
		return $catesoption;
	}
	
	public function getcidList($fid=0){
		return $this->db->where(array('cat_fid'=>$fid))->order_by('cat_rank asc')->get('downfile_category')->result_array();	
	}
	
	public function update()
	{
		$action = $this->input->post('action');
		$cid = intval($this->input->post('cid'));
		$cidx = intval($this->input->post('cidx'));
		$fname = $this->input->post('fname');
		$fclass = $this->input->post('fclass');
		$fsource = $this->input->post('fsource');
		$fdown = $this->input->post('fdown');
		$fmem1 = intval($this->input->post('fmem1'));
		$fmem2 = intval($this->input->post('fmem2'));
		$fmem3 = intval($this->input->post('fmem3'));
		$fiscommend = intval($this->input->post('fiscommend'));
		$fchk = intval($this->input->post('fchk'));
		$pic = $this->input->post('pic');
		$content = $this->input->post('content');
		$rank = intval($this->input->post('rank'));
		
		$id = intval($this->input->post('id'));
	
		$data = array(
			'cid' => $cid,
			'cidx' => $cidx,
			'fname' => $fname,
			'fclass'	 => $fclass,
			'fsource' => $fsource,
			'fdown' => $fdown,
			'fmem1' => $fmem1,
			'fmem2' => $fmem2,
			'fmem3' => $fmem3,
			'fiscommend' => $fiscommend,
			'fchk' => $fchk,
			'pic' => $pic,
			'fcontent' => $content,
			'rank' => $rank
		);
		

		
		
		if($action == 'add'){
			$data['addtime'] = time();
			$this->db->insert('downfile',$data);
		}
		if($action == 'mod'){
			$this->db->update('downfile',$data,array('id'=>$id));	
		}
		

		
		$data['err'] = '';
		return $data;
		//$this->db->insert('configs');
	}
	
	public function del()
	{
		$Downfile_id = $this->uri->segment(4);
		if($Downfile_id > 0){		
			$this->db->delete('Downfile', array('Downfile_id' => $Downfile_id));
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
				'base_url'       => base_url(admin_url().$this->uri->segment(2).'/index/'),
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_Downfile'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 4,
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
	}
	
	//搜索
   public function search_content($cid = FALSE,$state ,$title = FALSE,$num,$offset)
	{
        $this->db->order_by('id', 'DESC');
		
		 if($title){
			 $this->db->like('fname',$title);	 
		 }
		 
		 //$this->db->where('member.mem_state',$state);
		$query = $this->db->get('downfile',$num, $offset);

		return $query->result_array();
		
	} 
	
	//搜索条件查询条数
     public function search_content_nums($cid = FALSE,$state,$title = FALSE)
	{
		 if($title){
			 $this->db->like('fname',$title); 
		 }
		 //$this->db->where('member.mem_state',$state);
		
		return $this->db->count_all_results('downfile');
	
		
	}

    
    
}