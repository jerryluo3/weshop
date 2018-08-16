<?php
class Video_model extends CI_Model {
	
	
	public function getVideoList()
	{
				
		$total_nums = $this->db->count_all_results('video');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;

		$data['video_list'] = $this->db->order_by('id','DESC')->limit($page_size,$offset)->join('videocategory','videocategory.cat_id = video.cid','left')->get('video')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');

		
		return $data;
	}
	
	
	public function getonevideo(){
		$id = $this->uri->segment(4);
		if(!$id) return '';
		return $this->db->where(array('id'=>$id))->get('video')->row_array();
	}
	

	
	public function update()
	{
		$action = $this->input->post('action');
		$cid = intval($this->input->post('cid'));
		$title = $this->input->post('title');
		$goodsid = $this->input->post('goodsid');
		$addtime = strtotime($this->input->post('addtime'));
		$addtime = $addtime > 0 ? $addtime : time();
		
		$pid = intval($this->input->post('pid'));
	
		$data = array(
			'storeid' 		=> $cid,
			'title' 	=> $title,
			'goodsid' 	=> $goodsid,
			'goodsewm' 	=> '',
			'addtime'	=> $addtime
			
		);
		
		$data['state'] = 1;
		
		if($action == 'add'){
			$this->db->insert('luo_products',$data);
		}
		if($action == 'mod'){
			$this->db->update('luo_products',$data,array('pid'=>$pid));	
		}
		
		$data['err'] = '';
		return $data;
		//$this->db->insert('configs');
	}
	
	
	
	
	public function del()
	{
		$id = $this->uri->segment(4);
		if($id > 0){		
			$this->db->delete('video', array('id' => $id));
			$data = 'ok';
		}else{
			$data = '参数出错';	
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
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 4,
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
	}
	
	
	public function getSearchList(){
		
		
		
		$cid = $this->input->post('cid');
		$title = $this->input->post('title');
		$data['cid']  = $cid;
		$data['title']  = $title;
		
		
		
		if($cid > 0){
			 $this->db->where('cid',$cid);	 
		}
		if($title){
			 $this->db->like('title',$title);	 
		}
		$total_nums = $this->db->count_all_results('video');

		$page_size = 20;
		$page = intval($this->uri->segment(4));
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		
		if($cid > 0){
			 $this->db->where('cid',$cid);	 
		}
		if($title){
			 $this->db->like('title',$title);	 
		}
		$data['video_list'] = $this->db->order_by('pid','DESC')->limit($page_size,$page)->join('videocategory','videocategory.cat_id = video.cid','left')->get('video')->result_array();

		$data['total_nums'] = $total_nums;
		$data['page'] = ($page/$page_size) + 1;
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
    
    
}