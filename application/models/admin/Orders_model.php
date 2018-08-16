<?php
class Orders_model extends CI_Model {
	
	
	public function getOrdersList()
	{
		
		$total_nums = $this->db->count_all_results('orders');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['orders_list'] = $this->db->order_by('id','DESC')->limit($page_size,$offset)->join('member_address','member_address.a_id = orders.aid','left')->join('member','member.mem_id = orders.uid','left')->get('orders')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	
	public function getZitiOrdersList()
	{
		
		$total_nums = $this->db->where(array('ptype'=>1))->count_all_results('orders');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['orders_list'] = $this->db->where(array('ptype'=>1))->order_by('id','DESC')->limit($page_size,$offset)->join('member_address','member_address.a_id = orders.aid','left')->join('member','member.mem_id = orders.uid','left')->get('orders')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	public function getShouhouOrdersList()
	{
		
		$total_nums = $this->db->where(array('status'=>4))->count_all_results('orders');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['orders_list'] = $this->db->where(array('status'=>4))->order_by('id','DESC')->limit($page_size,$offset)->join('member_address','member_address.a_id = orders.aid','left')->join('member','member.mem_id = orders.uid','left')->get('orders')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	public function del()
	{
		$id = $this->uri->segment(4);
		if($id > 0){
			$this->db->delete('order', array('id' => $id));
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
				'base_url'       => base_url(admin_url().$this->uri->segment(2).'/index'),
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
	
	
	//搜索
   public function search_content($cid = FALSE,$state ,$title = FALSE,$num,$offset)
	{
        $this->db->order_by('id', 'DESC');
		
		 if($title){
			 $this->db->like('oid',$title);	 
		 }
		 
		 //$this->db->where('member.mem_state',$state);
		$query = $this->db->get('order',$num, $offset);

		return $query->result_array();
		
	} 
	
	//搜索条件查询条数
     public function search_content_nums($cid = FALSE,$state,$title = FALSE)
	{
		 if($title){
			 $this->db->like('oid',$title);	 
		 }
		 //$this->db->where('member.mem_state',$state);
		
		return $this->db->count_all_results('order');
	
		
	}
	
    
    
}