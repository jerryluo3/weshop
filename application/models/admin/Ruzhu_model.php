<?php
class Ruzhu_model extends CI_Model {
	
	
	public function getRuzhuList()
	{
		
		$total_nums = $this->db->count_all_results('ruzhu');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['ruzhu_list'] = $this->db->order_by('shop_id','DESC')->limit($page_size,$offset)->join('member','member.mem_id = ruzhu.shop_uid','left')->get('ruzhu')->result_array();
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
	
	
    
    
}