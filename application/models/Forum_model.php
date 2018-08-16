<?php
class Forum_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
	public function getThreadList(){
		$data = $this->headerSubMenu();
		
		$total_nums = $this->db->count_all_results('forum');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['forum_list'] = $this->db->order_by('f_id DESC')->limit($page_size,$offset)->join('weiyuan','weiyuan.wy_id = forum.f_uid','left')->get('forum')->result_array();
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		return $data;	
	}
	
	public function getOneThread(){
		$data = $this->headerSubMenu();
		
		$f_id = intval($this->uri->segment(3));
		
		$data['thread'] = $thread = $this->db->where(array('f_id'=>$f_id))->join('weiyuan','weiyuan.wy_id = forum.f_uid','left')->get('forum')->row_array();
		$n_count = $thread['f_counts'] + 1;
		$this->db->update('forum',array('f_counts'=>$n_count),array('f_id'=>$f_id));
		$data['post_list'] = $this->db->where(array('fp_fid'=>$f_id))->order_by('fp_id asc')->join('weiyuan','weiyuan.wy_id = forum_post.fp_uid','left')->get('forum_post')->result_array();
		
		return $data;
			
	}
	
	function headerSubMenu(){
		//PC
		$data['cat1_list'] = $this->db->where(array('cat_fid'=>1))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat2_list'] = $this->db->where(array('cat_fid'=>2))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat3_list'] = $this->db->where(array('cat_fid'=>3))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat4_list'] = $this->db->where(array('cat_fid'=>4))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat5_list'] = $this->db->where(array('cat_fid'=>5))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat6_list'] = $this->db->where(array('cat_fid'=>6))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		
		
		return $data;	
	}
	
	
}