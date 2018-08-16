<?php
class Links_model extends CI_Model {
	
	
	public function getLinksList()
	{
				
		$total_nums = $this->db->count_all_results('links');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['links_list'] = $this->db->order_by('links_id DESC')->limit($page_size,$offset)->join('links_category','links_category.cat_id = links.links_cid','left')->get('links')->result_array();
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		return $data;
	}
	
	
	public function getonelinks(){
		$links_id = $this->uri->segment(4);
		if(!$links_id) return '';
		return $this->db->where(array('links_id'=>$links_id))->get('links')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('cat_rank desc')->get('links_category')->result_array();	
	}
	
	public function update()
	{
		$action = $this->input->post('action');
		$links_cid = intval($this->input->post('links_cid'));
		$links_title = $this->input->post('links_title');
		$links_picture = $this->input->post('links_picture');
		$links_url = prep_url($this->input->post('links_url'));
		//if(substr($links_url,0,4) != 'http'){
//			$links_url = 'http://'.$links_url;	
//		}
		$links_starttime = strtotime($this->input->post('links_starttime'));
		$links_endtime = strtotime($this->input->post('links_endtime'));
		$cat_rank = intval($this->input->post('cat_rank'));
		$links_id = intval($this->input->post('links_id'));
	
		$data = array(
			'links_cid' => $links_cid,
			'links_title' => $links_title,
			'links_picture' => $links_picture,
			'links_url'	 => $links_url,
			'links_starttime' => $links_starttime,
			'links_endtime' => $links_endtime,
			'links_rank' => $cat_rank
		);
		
		$data['links_state'] = 1;
		
		
		
		if($action == 'add'){
			$data['links_addtime'] = time();;
			$this->db->insert('links',$data);
		}
		if($action == 'mod'){
			$this->db->update('links',$data,array('links_id'=>$links_id));	
		}
		
		$data['err'] = '';
		return $data;
		//$this->db->insert('configs');
	}
	
	public function del()
	{
		$links_id = $this->uri->segment(4);
		if($links_id > 0){		
			$this->db->delete('links', array('links_id' => $links_id));
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
				'base_url'       => base_url(admin_url().'links/'.$this->uri->segment(3)),
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_links'      => 5,
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
	
	
	/******广告类别******/
	
	public function getAdscatesList()
	{
		
		$cid = intval($this->uri->segment(3));
		$page_size = 20;
		if($cid > 0){
			$this->db->where(array('links_cid'=>$cid));	
		}
		$total_nums = $this->db->count_all_results('links');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		if($cid > 0){
			$this->db->where(array('links_cid'=>$cid));	
		}
		$data['linkscates_list'] = $this->db->order_by('links_id','DESC')->limit($page_size,$page)->get('links_cates')->result_array();
		$data['total_nums'] = $total_nums;
		$data['page'] = $page;
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
    
    
}