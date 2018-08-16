<?php
class Manager_model extends CI_Model {

	public $da = array();
    public function __construct()
    {
        $this->load->database();
		$this->load->library('session');
    }
	
	public function getManagerList($id = FALSE,$num = FALSE, $offset = FALSE)
	{
		$total_nums = $this->db->count_all_results('manager');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		$data['total_nums'] = $total_nums;


		$data['manager_list'] = $this->db->select('manager.*,mtype.id as mid,mtype.title,mtype.purview')->order_by('id desc')->limit($page_size,$offset)->join('mtype','mtype.id = manager.usergroup','left')->get('manager')->result_array();

		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		return $data;
	}
	
	public function getmtypelist($id = FALSE,$num = FALSE, $offset = FALSE)
	{
		
		if($id){
			$data = $this->db->get_where('mtype',array('id'=> $id) )->row_array();
		}else{
			$data = $this->db->order_by('rank asc,id asc')->get('mtype',$num, $offset)->result_array();
		}
		return $data;
	}
	
	
	public function getonerow($id){
		if(empty($id)) return "";
		//$query = $this->db->query("select a.*,b.id as bid,b.title as mtitle from manager a left join mtype b on a.usergroup = b.id where a.id=".$id);

		return $this->db->where(array('manager.id'=>$id))->select('manager.id,manager.username,manager.realname,manager.usergroup,manager.rank,manager.state,mtype.id as bid,mtype.title')->join('mtype','mtype.id = manager.usergroup','left')->get('manager')->row_array();
	}
	
	
	
	public function update()
	{
		//$this->load->helper('url');		
		
		$id = $this->input->post('id');
		$action = $this->input->post('action');
		
		$usergroup 	= $this->input->post('usergroup');
		$username 	= $this->input->post('username');
		$password 	= $this->input->post('password');
		$repassword = $this->input->post('repassword');
		$rank 		= $this->input->post('rank');
		$realname 	= $this->input->post('realname');
		$state 		= $this->input->post('state');
		$addtime 	= time();
		
		
		$data = array(
			'usergroup' 			=> $usergroup,
			'username' 				=> $username,
			'realname' 				=> $realname,
			'rank' 					=> $rank,
			'state' 				=> $state
		);

		if($action == "add"){
			$data['regip'] = $this->input->ip_address();
			$data['regtime'] = 	$addtime;
			$data['password'] = md5($password);
		}
		
		
		if($action == "mod" && $id > 0){
			if(!empty($password)){
				$data['password'] = md5($password);
			}
			//$data['password'] = md5($password) == $oldpass || empty($password) ? $oldpass : md5($password);
			$where = "id=".$id;
			return $this->db->update('manager', $data, $where);					
		}
		if($action == "add"){
			return $this->db->insert('manager', $data);
		}
	}
		
	public function del($ids){
		//删除
		$id = $this->uri->segment(4);
		if($id > 0){		
			$this->db->delete('manager', array('id' => $id));
			$data['err'] = '';
		}else{
			$data['err'] = '参数出错';	
		}
		
		return $data;
	}
	
	public function deletec($data)
	{
		$this->db->where_in('id',$data);
		$this->db->delete('Manager');  
		return $this->db->affected_rows();
	}
	
	public function movec($cid,$data)
	{
		$this->db->where_in('id',$data);
		$this->db->update('Manager',array('cid'=>$cid));  
		return $this->db->affected_rows();
	}
	
	
	public function pagelist($total_nums,$page_size){
		$this->load->library('pagination');

	    $config = array(
				'base_url'       => base_url(admin_url().$this->uri->segment(2).'/'.$this->uri->segment(3).'/'),
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 4,
				'full_tag_open'  => "<ul class='pagination pagination-sm no-margin pull-right'>",//关闭标签
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
	
	
    
    
}