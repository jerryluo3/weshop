<?php
class Mtype_model extends CI_Model {
	
	
	public function getMtypeList()
	{
				
		$total_nums = $this->db->count_all_results('luo_mtype');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		$data['total_nums'] = $total_nums;


		$data['mtype_list'] = $this->db->order_by('rank asc,id asc')->limit($page_size,$offset)->get('luo_mtype')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	
	public function getonemtype(){
		$id = $this->uri->segment(4);
		if(!$id) return '';
		return $this->db->where(array('id'=>$id))->get('luo_mtype')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('rank desc')->get('luo_mtype')->result_array();	
	}
	
	public function update()
	{
		$id = $this->input->post('id');
		$action = $this->input->post('action');
		
		$title = $this->input->post('title');
		$rank = $this->input->post('rank');
		$purview = $this->input->post('purview');
		$purview = !empty($purview) ? implode(",",$purview) : '';
		$grade = $this->input->post('grade');
		$grade = empty($grade) ? 0 : $grade;
		$addtime = time();
		

		
		
		$data = array(
			'title' 				=> $title,
			'purview' 				=> $purview,
			'rank' 					=> $rank
			
		);
		
		//p($data);die();
		
		if($action == "add"){
			$data['addtime'] = 	$addtime;
		}
		
		if($action == "mod" && $id > 0){
			$where = "id=".$id;
			$this->db->update('luo_mtype', $data, $where);					
		}
		if($action == "add"){
			$this->db->insert('luo_mtype', $data);
		}
		
	}
	
	public function del()
	{
		$id = $this->uri->segment(4);
		if($id > 0){		
			$this->db->delete('luo_mtype', array('id' => $id));
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
				'base_url'       => base_url(admin_url().'mtype/'),
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