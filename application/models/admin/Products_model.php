<?php
class Products_model extends CI_Model {
	
	
	public function getproductsList()
	{
				
		$total_nums = $this->db->count_all_results('products');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;

		$data['products_list'] = $this->db->order_by('id','DESC')->limit($page_size,$offset)->join('productscategory','productscategory.cat_id = products.cid','left')->get('products')->result_array();

		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');

		
		return $data;
	}
	
	
	public function getoneproducts(){
		$id = $this->uri->segment(4);
		if(!$id) return '';
		return $this->db->where(array('id'=>$id))->get('products')->row_array();
	}
	
	
	public function del()
	{
		$id = $this->uri->segment(4);
		if($id > 0){		
			$this->db->delete('products', array('id' => $id));
			$data = 'ok';
		}else{
			$data = '参数出错';	
		}
		
		return $data;
		//$this->db->insert('configs');
	}
	
    
    
}