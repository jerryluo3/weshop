<?php
class Card_model extends CI_Model {
	
	
	
	public function getCardList()
	{
		
		
		$total_nums = $this->db->count_all_results('cards');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['card_list'] = $this->db->select('cards.*,users.id,users.phone')->order_by('card_id DESC')->limit($page_size,$offset)->join('users','users.id = cards.card_uid','left')->get('cards')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	
	public function getOneCard(){
		$id = intval($this->uri->segment(4));
		if($id > 0){
			return $this->db->where(array('card_id'=>$id))->get('cards')->row_array();	
		}else{
			return array();	
		}	
	}
    
}