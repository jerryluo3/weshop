<?php
class Feedback_model extends CI_Model {
	
	
	public function getFeedbackList()
	{
		
		
		$page_size = 20;
		$total_nums = $this->db->count_all_results('feedback');
		//echo $this->db->last_query();
		$page = intval($this->uri->segment(3));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		
		$data['feedback_list'] = $this->db->order_by('id','DESC')->limit($page_size,$page)->get('feedback')->result_array();
		$data['total_nums'] = $total_nums;
		$data['page'] = ($page/$page_size) + 1;
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
	
	public function getonefeedback(){
		$id = $this->uri->segment(4);
		if(!$id) return '';
		return $this->db->where(array('id'=>$id))->get('feedback')->row_array();
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
			$this->db->delete('feedback', array('id' => $id));
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
				'base_url'       => base_url(admin_url().'feedback/index/'),
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 3,
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