<?php
class Adscates_model extends CI_Model {
	
	
	/******广告类别******/
	
	public function getAdscatesList()
	{

		
		$total_nums = $this->db->count_all_results('ads_category');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['adscates_list'] = $this->db->order_by('cat_id DESC')->limit($page_size,$offset)->get('ads_category')->result_array();
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	public function getoneadscates(){
		$cat_id = $this->uri->segment(4);
		if(!$cat_id) return '';
		return $this->db->where(array('cat_id'=>$cat_id))->get('ads_category')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('cat_rank desc')->get('ads_category')->result_array();	
	}
	
	public function update()
	{
		$action = $this->input->post('action');
		$cat_fid = intval($this->input->post('cat_fid'));
		$cat_name = $this->input->post('cat_name');
		$cat_rank = intval($this->input->post('cat_rank'));
		$cat_id = intval($this->input->post('cat_id'));
	
		$data = array(
			'cat_fid' => $cat_fid,
			'cat_name' => $cat_name,
			'cat_rank' => $cat_rank
		);
		if($action == 'add'){
			$this->db->insert('ads_category',$data);
		}
		if($action == 'mod'){
			$this->db->update('ads_category',$data,array('cat_id'=>$cat_id));	
		}
		
		$data['err'] = '';
		return $data;
		//$this->db->insert('configs');
	}
	
	public function del()
	{
		$cat_id = $this->uri->segment(4);
		if($cat_id > 0){		
			$this->db->delete('ads_category', array('cat_id' => $cat_id));
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
				'base_url'       => base_url(admin_url().'ads/'),
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 3,
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