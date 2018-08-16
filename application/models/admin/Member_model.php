<?php
class Member_model extends CI_Model {
	
	
	public function getMemberList()
	{
		
		
		$total_nums = $this->db->count_all_results('users');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['member_list'] = $this->db->order_by('id DESC')->limit($page_size,$offset)->get('users')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	public function getMemberRechargeList()
	{
		
		
		$total_nums = $this->db->where(array('type'=>1))->count_all_results('recharges');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['recharge_list'] = $this->db->select('recharges.*,users.id as users_id,users.phone,users.username')->where(array('recharges.type'=>1))->order_by('id DESC')->limit($page_size,$offset)->join('users','users.id = recharges.uid')->get('recharges')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	public function getMemberConsumeList()
	{
		
		
		$total_nums = $this->db->where(array('type'=>0))->count_all_results('recharges');
		$page_size = 20;
		$page = intval($this->uri->segment(4));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		$offset = ($page-1)*$page_size;
		//$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;


		$data['consume_list'] = $this->db->select('recharges.*,users.id as users_id,users.phone,users.username')->where(array('recharges.type'=>0))->order_by('id DESC')->limit($page_size,$offset)->join('users','users.id = recharges.uid')->get('recharges')->result_array();
		//echo $this->db->last_query();
		//$this->pagelist($total_nums,$page_size);
		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		
		
		return $data;
	}
	
	
	
	
	public function getonemember(){
		$mem_id = $this->uri->segment(4);
		if(!$mem_id) return '';
		return $this->db->where(array('id'=>$id))->get('users')->row_array();
	}
	
	public function update()
	{
		$action = $this->input->post('action');
		$newpass = $this->input->post('newpass');
		$renewpass = $this->input->post('renewpass');
		$mem_id = intval($this->input->post('mem_id'));
		
		
		if($newpass == $renewpass && $mem_id > 0){
			$row = $this->db->where(array('mem_id'=>$mem_id))->get('member')->row_array();
			if(!empty($row)){
				$n_pass = sha1($newpass);
				$this->db->update('member',array('mem_upass'=>$n_pass));
				$data['err'] = '';
			}else{
				$data['err'] = '参数出错';	
			}
		}else{
			$data['err'] = '两次密码输入不一致或参数出错';	
		}	
		
		return $data;
	}
	
	
	public function updatesetting()
	{
		$action = $this->input->post('action');
		$username = $this->input->post('username');
		$usetime = $this->input->post('usetime');
		$times = intval($this->input->post('times'));
		
		
		//$user=$db->getonerow(get_sql("select * from wanyiwang_member where username='".$username."'"));
		$user = $this->db->where(array('mem_uname'=>$username))->get('member')->row_array();

		if(!$user){
			$data['err'] = '会员不存在';
		}else{
			if($usetime>0){
				if($user['mem_expires']>time()&&$user['mem_type']==1){
				  $expires=$user['mem_expires']+$usetime*24*3600;
				}else{
				  $expires=time()+$usetime*24*3600;
				}
			}else{
				$expires=$user['mem_expires'];
			}
			$type=1;
			 $member_record=array(
				 'mem_type'=>$type,
				 'mem_expires'=>$expires,
			 );
			$this->db->update('member',$member_record,array('mem_id'=>$user['mem_id']));
			$data['err'] = '';
		}		
		
		return $data;
	}
	
	public function del()
	{
		$id = $this->uri->segment(4);
		if($id > 0){
			$this->db->delete('users', array('id' => $id));
			$data['err'] = '';
		}else{
			$data['err'] = '参数出错';	
		}
		
		return $data;
		//$this->db->insert('configs');
	}
	
	public function sendmessage($from_id,$to_id,$content,$cid=0,$type=0){
		
		$addtime = time();
		$state = 0;
				
		$data = array(
			'from_id' 	=> $from_id,
			'to_id' 	=> $to_id,
			'cid' 		=> $cid,	//0:系统消息，1:内容审核，2企业审核，3：推送审核
			'type' 		=> $type,		//0：系统消息，1：一审核，2：二审，3：三审
			'content' 	=> $content,
			'addtime' 	=> $addtime,
			'state' 	=> $state
		);		
		$this->db->insert('message',$data);
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