<?php
class Content_model extends CI_Model {
	
	
	public function getContentList()
	{
		$total_nums = $this->db->count_all_results('content');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;
		$data['page'] = $page;

		$data['content_list'] = $this->db->order_by('id DESC')->limit($page_size,$offset)->join('category','category.cat_id = content.cid','left')->get('content')->result_array();
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		
		return $data;
	}
	
	
	public function getonecontent(){
		$id = $this->uri->segment(4);
		if(!$id) return '';
		return $this->db->where(array('id'=>$id))->get('content')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('cat_rank desc')->get('category')->result_array();	
	}
	
	public function update()
	{
		$action = $this->input->post('action');
		$cid = intval($this->input->post('cid'));
		$title = $this->input->post('title');
		$picture = $this->input->post('picture');
		$tags = $this->input->post('tags');
		$addtime = strtotime($this->input->post('addtime'));
		$addtime = $addtime > 0 ? $addtime : time();
		$author = $this->input->post('author');
		$source = $this->input->post('source');
		$rank = intval($this->input->post('rank'));
		$content = $this->input->post('content');
		$iscommend = intval($this->input->post('iscommend'));
		$id = intval($this->input->post('id'));
	
		$data = array(
			'cid' 		=> $cid,
			'title' 	=> $title,
			'picture' 	=> $picture,
			'tags' 		=> $tags,
			'addtime'	=> $addtime,
			'author'	=> $author,
			'source' 	=> $source,
			'content' 	=> $content,
			'iscommend' => $iscommend,
			'rank' 		=> $rank
		);

		$data['state'] = 1;
		
		if($action == 'add'){
			$this->db->insert('content',$data);
		}
		if($action == 'mod'){
			$row = $this->db->where(array('id'=>$id))->get('content')->row_array();
			if($row['state'] < 0){
				//添加内容日志
				$this->add_content_check_log($id,0,'完成修改');	
			}
			$this->db->update('content',$data,array('id'=>$id));	
		}
		
		$data['err'] = '';
		return $data;
		//$this->db->insert('configs');
	}
	
	
	public function contentcheck()
	{
		
		$id = intval($this->input->post('id'));
		$state = intval($this->input->post('state'));
		$nstate = intval($this->input->post('com_state'));
		$content = $this->input->post('content');

		
		$row = $this->db->where(array('id'=>$id))->get('content')->row_array();

		if(!empty($row)){
			if($row['state'] == $state){

				if($nstate > 3){
					$arr['err'] = '此内容已获得最高级审核，请不要重复操作';
				}else{
					$this->db->update('content',array('state'=>$nstate),array('id'=>$id));
					
					//添加内容审核日志
					$this->add_content_check_log($id,$nstate,$content);
						
					$arr['err'] = '';
				}
			}else{
				$arr['err'] = '此信信已审核过，请不要重复操作';	
			}	
		}else{
			$arr['err'] = '请确认此信息是否存在';
		}
		return $arr;
		
	}
	
	public function contentcheckall()
	{
		
		$ids = $this->input->post('id');
		if(!empty($ids)){
			foreach($ids as $id){
				$row = $this->db->where(array('id'=>$id))->get('content')->row_array();	
				if(!empty($row)){
					$nstate = $row['state']+1;
					if($nstate <= 3){
						$this->db->update('content',array('state'=>$nstate),array('id'=>$id));						
						//添加内容审核日志
						$this->add_content_check_log($id,$nstate,'通过');
							
						$arr['err'] = '';
					}	
				}else{
					$arr['err'] = '请确认此信息是否存在';
				}
			}
		}else{
			$arr['err'] = '请选择需要审核的内容';	
		}
		
		

		
		return $arr;
		
	}
	
	public function add_content_check_log($id,$nstate,$content){
		if($id > 0){
			$addtime = time();
			$mid = $_SESSION['user_id'];
			$data = array(
				'cid' 		=> $id,
				'mid' 		=> $mid,
				'cstate' 	=> $nstate,
				'content' 	=> $content,
				'addtime'	=> $addtime
			);
			$this->db->insert('content_checklog',$data);
		}	
	}
	
	public function getContentChecklogList(){
		$id = intval($this->uri->segment(4));
		
		$data['log_list'] = $this->db->where(array('cid'=>$id))->order_by('content_checklog.id desc')->join('manager','manager.id = content_checklog.mid')->get('content_checklog')->result_array();
		return $data;
			
	}
	
	
	public function del()
	{
		$id = $this->uri->segment(4);
		if($id > 0){		
			$this->db->delete('content', array('id' => $id));
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
				'base_url'       => base_url(admin_url().'content/index/'),
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
	
	
	//搜索
   public function search_content($cid = FALSE,$title = FALSE,$num,$offset)
	{
        $this->db->order_by('id', 'DESC');
        $this->db->join('category', 'category.cat_id = content.cid','left');
		 if($cid){
		    $this->db->where('content.cid',$cid);
		 }
		 if($title){
			 $this->db->like('content.title',$title);	 
		 }
		 
		$query = $this->db->get('content',$num, $offset);
		return $query->result_array();
		
	} 
	
	//搜索条件查询条数
     public function search_content_nums($cid = FALSE,$title = FALSE)
	{

        $this->db->join('category', 'category.cat_id = content.cid','left');
		 if($cid){
		    $this->db->where('content.cid',$cid);
		 }
		 if($title){
			 $this->db->like('content.title',$title);	 
		 }
		 
		return $this->db->count_all_results('content');
	
		
	}
	
    
    
}