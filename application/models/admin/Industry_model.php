<?php
class Industry_model extends CI_Model {
	
	
	public function getIndustryList()
	{
		
		$page_size = 20;
		$total_nums = $this->db->count_all_results('company_industry');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['industry_list'] = $this->db->order_by('iid','DESC')->limit($page_size,$page)->get('company_industry')->result_array();
		$data['total_nums'] = $total_nums;
		$data['page'] = intval($page/$page_size)+1;
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
	
	public function getoneindustry(){
		$industry_id = $this->uri->segment(4);
		if(!$industry_id) return '';
		return $this->db->where(array('iid'=>$industry_id))->get('company_industry')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('irank asc')->get('company_industry')->result_array();	
	}
	
	public function update()
	{
		$action = $this->input->post('action');
		$ifid = intval($this->input->post('ifid'));
		$iname = $this->input->post('iname');
		$irank = $this->input->post('irank');
		$industry_id = intval($this->input->post('iid'));
	
		$data = array(
			'ifid' => $ifid,
			'iname' => $iname,
			'irank' => $irank
		);
		
		
		if($action == 'add'){
			$this->db->insert('company_industry',$data);
		}
		if($action == 'mod'){
			$this->db->update('company_industry',$data,array('iid'=>$industry_id));	
		}
		
		$data['err'] = '';
		return $data;
	}
	
	public function del()
	{
		$industry_id = $this->uri->segment(4);
		if($industry_id > 0){		
			$this->db->delete('company_industry', array('iid' => $industry_id));
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
				'base_url'       => base_url(admin_url().'industry/'),
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