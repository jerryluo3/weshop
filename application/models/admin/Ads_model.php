<?php
class Ads_model extends CI_Model {
	
	
	public function getAdsList()
	{
		
		$total_nums = $this->db->count_all_results('ads');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['ads_list'] = $this->db->order_by('cat_id DESC')->limit($page_size,$offset)->join('ads_category','ads_category.cat_id = ads.ads_cid')->get('ads')->result_array();
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	
	public function getoneads(){
		$ads_id = $this->uri->segment(4);
		if(!$ads_id) return '';
		return $this->db->where(array('ads_id'=>$ads_id))->get('ads')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('cat_rank desc')->get('ads_category')->result_array();	
	}
	
	
	
	public function del()
	{
		$ads_id = $this->uri->segment(4);
		if($ads_id > 0){		
			$this->db->delete('ads', array('ads_id' => $ads_id));
			$data = 'ok';
		}else{
			$data = '参数出错';	
		}
		
		return $data;
		//$this->db->insert('configs');
	}
	
	
	
	/******广告类别******/
	
	public function getAdscatesList()
	{
		
		$cid = intval($this->uri->segment(3));
		$page_size = 20;
		if($cid > 0){
			$this->db->where(array('ads_cid'=>$cid));	
		}
		$total_nums = $this->db->count_all_results('ads');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		if($cid > 0){
			$this->db->where(array('ads_cid'=>$cid));	
		}
		$data['adscates_list'] = $this->db->order_by('ads_id','DESC')->limit($page_size,$page)->get('ads_cates')->result_array();
		$data['total_nums'] = $total_nums;
		$data['page'] = $page;
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
    
    
}