<?php
class Setting_model extends CI_Model {
	

	public function getbasicinfo(){
		return $this->db->order_by('id desc')->get('weixin_basicinfo')->row_array();
	}
	
	public function infoupdate(){
		$id = $this->input->post('id');
		$arr = array(
			'wxname' => $this->input->post('wxname'),
			'wxid' => $this->input->post('wxid'),
			'weixin' => $this->input->post('weixin'),
			'avatar' => $this->input->post('avatar'),
			'appid' => $this->input->post('appid'),
			'appsecret' => $this->input->post('appsecret'),
			'msgtype' => $this->input->post('msgtype'),
			'aeskey' => $this->input->post('aeskey'),
			'weixintype' => $this->input->post('weixintype'),
			'province' => $this->input->post('province'),
			'city' => $this->input->post('city'),
			'content' => $this->input->post('content')
		);
		$row = $this->db->where(array('id'=>$id))->get('weixin_basicinfo')->row_array();
		if(!empty($row)){
			$this->db->update('weixin_basicinfo',$arr,array('id'=>$row['id']));
		}else{
			$this->db->insert('weixin_basicinfo',$arr);	
		}
		$data['err'] = '';
		return $data;
	}
	
	public function gettextlist(){
		$page_size = 20;
		$total_nums = $this->db->count_all_results('weixin_text');
		$page = intval($this->uri->segment(3));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['text_list'] = $this->db->order_by('id','DESC')->limit($page_size,$page)->get('weixin_text')->result_array();
		$data['total_nums'] = $total_nums;
		$data['page'] = $page;
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		return $data;
	}
	
	public function gettextinfo(){
		$id = intval($this->uri->segment(5));
		if(empty($id)) return array();
		return $this->db->where(array('id'=>$id))->get('weixin_text')->row_array();
	}
	
	public function textupdate(){
		$id = $this->input->post('id');
		$arr = array(
			'title' => $this->input->post('title'),
			'content' => $this->input->post('content')
		);
		$row = $this->db->where(array('id'=>$id))->get('weixin_text')->row_array();
		if(!empty($row)){
			$this->db->update('weixin_text',$arr,array('id'=>$row['id']));
		}else{
			$arr['addtime'] = time();
			$this->db->insert('weixin_text',$arr);	
		}
		$data['err'] = '';
		return $data;
	}
	
	public function textdel(){
		$id = intval($this->uri->segment(6));
		if(!empty($id)){
			$this->db->delete('weixin_text',array('id'=>$id));
			$data['err'] = '操作成功';
		}else{
			$data['err'] = '参数出错';	
		}		
		return $data;
	}
	
	public function pagelist($total_nums,$page_size){
		$this->load->library('pagination');

	    $config = array(
				'base_url'       => base_url(admin_url().'weixin/setting/text/'),
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