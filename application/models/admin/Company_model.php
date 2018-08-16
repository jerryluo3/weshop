<?php
class Company_model extends CI_Model {
	
	
	public function getCompanyList()
	{
		
		$page_size = 20;
		$cids = get_checkpur_cids('company',$_SESSION['user_purview'],$_SESSION['user_group']);
		if($cids == -1){
			$this->db->where(array('com_state'=>$cids));	
		}else if($cids == ''){
			
		}else{
			$nstr = json_decode($cids,true);
			if(!empty($nstr['grade'])){
				$this->db->where_in('com_state',$nstr['grade']);
			}
		}
		$total_nums = $this->db->count_all_results('company');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;
		$data['page'] = intval($page/$page_size)+1;
		if($cids == -1){
			$this->db->where(array('com_state'=>$cids));	
		}else if($cids == ''){
			
		}else{
			$nstr = json_decode($cids,true);
			if(!empty($nstr['grade'])){
				$this->db->where_in('com_state',$nstr['grade']);
			}
		}
		$data['company_list'] = $this->db->order_by('com_id','DESC')->limit($page_size,$page)->join('member','member.mem_id = company.mid')->get('company')->result_array();
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		
		return $data;
	}
	
	public function companycheck()
	{
			
		$id = intval($this->input->post('com_id'));
		$state = intval($this->input->post('state'));
		$com_state = intval($this->input->post('com_state'));
		$content = $this->input->post('content');

		
		$row = $this->db->where(array('com_id'=>$id))->get('company')->row_array();

		if(!empty($row)){
			if($row['com_state'] == $state && $com_state <= 3){
				if($com_state == -3){//修改会员状态，无法登录
					$this->db->update('member',array('mem_state'=>-1),array('mem_id'=>$row['mid']));
				}
				if($com_state == -5){//解除清退封存
					$this->db->update('member',array('mem_state'=>0),array('mem_id'=>$row['mid']));
					$com_state = 0;
				}
				//$nstate = $row['com_state']+1;
				$this->db->update('company',array('com_state'=>$com_state),array('com_id'=>$id));
				
				$from_id = 0;
				$to_id = $row['mid'];
				//0:系统消息，1:内容审核，2企业审核，3：推送审核
				$cid = 2;
				$this->sendmessage($from_id,$to_id,$content,$cid,$com_state);
				
				//添加企业审核日志
				$this->add_company_check_log($id,$com_state,$content);
				
				$arr['err'] = '';	
			}else{
				$arr['err'] = '此信信已审核过，请不要重复操作';	
			}	
		}else{
			$arr['err'] = '请确认此信息是否存在';
		}
		return $arr;
		
	}
	
	public function add_company_check_log($id,$nstate,$content){
		if($id > 0){
			$addtime = time();
			$mid = $_SESSION['user_id'];
			$data = array(
				'cid' 		=> $id,
				'mid' 		=> $mid,
				'cstate' 	=> $nstate,
				'content' 	=> $content,
				'addtime'	=> $addtime
			);
			$this->db->insert('company_checklog',$data);
		}	
	}
	
	public function getCompanyChecklogList(){
		$id = intval($this->uri->segment(4));
		
		$data['log_list'] = $this->db->where(array('cid'=>$id))->order_by('company_checklog.id desc')->join('manager','manager.id = company_checklog.mid')->get('company_checklog')->result_array();
		return $data;
			
	}

	
	
	public function getCompanyBlackList()
	{
		
		$page_size = 20;
		$total_nums = $this->db->count_all_results('company_black');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;
		$data['page'] = intval($page/$page_size)+1;
		$data['company_list'] = $this->db->order_by('id','DESC')->limit($page_size,$page)->get('company_black')->result_array();
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		
		return $data;
	}
	
	public function getonecompanyblack(){
		$id = $this->uri->segment(4);
		if(!$id) return '';
		return $this->db->where(array('id'=>$id))->get('company_black')->row_array();
	}
	
	public function blackupdate()
	{
		$action = $this->input->post('action');
		$companyname = $this->input->post('companyname');
		$content = $this->input->post('content');
		$id = intval($this->input->post('id'));
		
		$row = $this->db->like('companyname',$companyname)->get('company_black')->row_array();
		if(!empty($row)){
			$arr['err'] = '此公司已在黑名单中，请不要重复添加';
			return $arr;	
		}
		
		$data = array(
			'companyname' => $companyname,
			'content' => $content
		);
		
		
		if($action == 'add'){
			$data['addtime'] = time();
			$this->db->insert('company_black',$data);
		}
		if($action == 'mod'){
			$this->db->update('company_black',$data,array('id'=>$id));	
		}
		
		$arr['err'] = '';
		return $arr;
	}
	
	public function blackdel()
	{
		$com_id = $this->uri->segment(4);
		if($com_id > 0){
			$this->db->delete('company_black', array('id' => $com_id));
			$data['err'] = '';
		}else{
			$data['err'] = '参数出错';	
		}
		
		return $data;
		//$this->db->insert('configs');
	}
	
	public function importblackcompany(){
		date_default_timezone_set('Asia/ShangHai');
		$this->load->library ( array('PHPExcel','PHPExcel/IOFactory'));
		//$this->load->helper(array('form', 'url'));
		//////上传Excel
		$config ['upload_path'] = './data/uploadfile/';
		$config ['allowed_types'] = 'txt|xls|xlsx|xl';
		$config ['max_size'] = '2000';
		$config ['file_name'] = date('Ymdhis');
		$this->load->library('upload', $config);

		
		if ( ! $this->upload->do_upload('userfile')){
			$error = array ('error' => $this->upload->display_errors () );
			echo $error ['error'];
			echo '<a href="'.site_url(admin_url().'company/blackimport').'">返回</a>';
			exit ();
		} else {
			$data = array ('upload_data' => $this->upload->data () );
			$file_name = $data ['upload_data'] ['file_name'];
		}
		
		$uploadfile = './data/uploadfile/'.$file_name;//获取上传成功的Excel
		$reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
		$PHPExcel = $reader->load($uploadfile); // 载入excel文件
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumm = $sheet->getHighestColumn(); // 取得总列数
		 
		/** 循环读取每个单元格的数据 */
		for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
			//p($sheet->getCell($column));die();
			for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
				$dataset[] = $sheet->getCell($column.$row)->getValue();
				//$v = $sheet->getCell($column.$row)->getValue() == "/" ? '' : $sheet->getCell($column.$row)->getValue();	
				//echo $v."<br />";
			}

			$data = array(

				'companyname'	=> $dataset[0],
				'companyjgdm'	=> $dataset[1],
				'companyaddr'	=> $dataset[2],
				'addtime'	=> time()
			);
			$this->db->insert('company_black',$data);
		}
		
		unlink($uploadfile);//删除临时Excel	
	}
	
	
	public function importcompany(){
		date_default_timezone_set('Asia/ShangHai');
		$this->load->library ( array('PHPExcel','PHPExcel/IOFactory'));
		//$this->load->helper(array('form', 'url'));
		//////上传Excel
		$config ['upload_path'] = './data/uploadfile/';
		$config ['allowed_types'] = 'txt|xls|xlsx|xl';
		$config ['max_size'] = '2000';
		$config ['file_name'] = date('Ymdhis');
		$this->load->library('upload', $config);

		
		if ( ! $this->upload->do_upload('userfile')){
			$error = array ('error' => $this->upload->display_errors () );
			echo $error ['error'];
			echo '<a href="'.site_url(admin_url().'company/import').'">返回</a>';
			exit ();
		} else {
			$data = array ('upload_data' => $this->upload->data () );
			$file_name = $data ['upload_data'] ['file_name'];
		}
		
		$uploadfile = './data/uploadfile/'.$file_name;//获取上传成功的Excel
		$reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
		$PHPExcel = $reader->load($uploadfile); // 载入excel文件
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumm = $sheet->getHighestColumn(); // 取得总列数
		 
		/** 循环读取每个单元格的数据 */
		for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
			//p($sheet->getCell($column));die();
			for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
				$dataset[] = $sheet->getCell($column.$row)->getValue();
				$v = $sheet->getCell($column.$row)->getValue() == "/" ? '' : $sheet->getCell($column.$row)->getValue();
				
				
				
				//echo $v."<br />";
			}

			//添加member账号
			$uname = $this->getRandUserName();
			$static_pass = "123456";
			$salt = mt_rand(1000,9999);
			$upass = md5($static_pass.$salt);
			$arr = array(
				'mem_uname' => $uname,
				'mem_upass' => $upass,
				'mem_salt' => $salt,
				'mem_regip' => $this->input->ip_address(),
				'mem_regtime' => time()
			);
			$this->db->insert('member',$arr);
			$mid = $this->db->insert_id();
			//将excle数据插入到company表
			
			$data = array(
				'mid'	=> $mid,
				'com_type'	=> 1,	//导入企业
				'com_hangye'	=> $dataset[0],
				'com_brand'	=> $dataset[1],
				'com_url'	=> $dataset[3],
				'com_jgdm'	=> $dataset[4],
				'com_addr'	=> $dataset[5],
				'com_phone'	=> $dataset[6],
				'com_email'	=> $dataset[7],
				'com_contacter'	=> $dataset[8],
				'com_addtime'	=> time()
			);
			$this->db->insert('company',$data);
			
			//添加简介
			$tabout = str_replace('/','',$dataset[2]);
			if(!empty($tabout)){
				$about_arr = array(
					'mid'	=> $mid,
					'content'	=> $dataset[2]
				);
				$this->db->insert('company_about',$about_arr);	
			}
		}
		
		unlink($uploadfile);//删除临时Excel	
	}
	
	private function getRandUserName($len=6){
		$arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
 
		$str = '';
		$arr_len = count($arr);
		for ($i = 0; $i < $len; $i++)
		{
			$rand = mt_rand(0, $arr_len-1);
			$str.=$arr[$rand];
		}
		
		$user = $this->db->where(array('mem_uname'=>$str))->get('member')->row_array();
		if(!empty($user)){
			getRandUserName();	
		}else{
			return $str;	
		}		
	}
	
	
	public function getCompanySendList()
	{
		
		$page_size = 20;
		$cids = get_checkpur_cids('send',$_SESSION['user_purview'],$_SESSION['user_group']);
		if($cids == -1){
			$this->db->where(array('state'=>$cids));	
		}else if($cids == ''){
			
		}else{
			$nstr = json_decode($cids,true);
			if(!empty($nstr['grade'])){
				$this->db->where_in('state',$nstr['grade']);
			}
		}
		$this->db->join('member','member.mem_id = company_send.mid')->join('company','member.mem_id = company.mid');
		$total_nums = $this->db->count_all_results('company_send');
		$page = intval($this->uri->segment(4));
		//$page = $page > 0 ? $page : 1;
		
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['total_nums'] = $total_nums;
		$data['page'] = intval($page/$page_size)+1;
		if($cids == -1){
			$this->db->where(array('state'=>$cids));	
		}else if($cids == ''){
			
		}else{
			$nstr = json_decode($cids,true);
			if(!empty($nstr['grade'])){
				$this->db->where_in('state',$nstr['grade']);
			}
		}
		$this->db->join('member','member.mem_id = company_send.mid')->join('company','member.mem_id = company.mid');
		$data['send_list'] = $this->db->order_by('sid','DESC')->limit($page_size,$page)->get('company_send')->result_array();
		//echo $this->db->last_query();
		$this->pagelist($total_nums,$page_size);
		
		
		return $data;
	}
	
	public function getonecompanysend(){
		$com_id = $this->uri->segment(4);
		if(!$com_id) return '';
		return $this->db->where(array('sid'=>$com_id))->get('company_send')->row_array();
	}
	
	public function sendcheck()
	{
		
		$sid = intval($this->input->post('sid'));
		$state = intval($this->input->post('state'));
		$nstate = intval($this->input->post('com_state'));
		$content = $this->input->post('content');
		
		$row = $this->db->where(array('sid'=>$sid))->get('company_send')->row_array();
		if(!empty($row)){
			
			
			if($row['state'] == $state && $nstate <= 3){
				
				$this->db->update('company_send',array('state'=>$nstate),array('sid'=>$sid));
				//添加推送审核日志
				$this->add_company_sendcheck_log($sid,$nstate,$content);
				
				$arr['err'] = '';	
			}else{
				$arr['err'] = '此信信已审核过，请不要重复操作';	
			}	
		}else{
			$arr['err'] = '请确认此信息是否存在';
		}
		return $arr;
		
	}
	
	public function add_company_sendcheck_log($id,$nstate,$content){
		if($id > 0){
			$addtime = time();
			$mid = $_SESSION['user_id'];
			$data = array(
				'cid' 		=> $id,
				'mid' 		=> $mid,
				'cstate' 	=> $nstate,
				'content' 	=> $content,
				'addtime'	=> $addtime
			);
			$this->db->insert('company_sendchecklog',$data);
		}	
	}
	
	public function getCompanySendChecklogList(){
		$id = intval($this->uri->segment(4));
		
		$data['log_list'] = $this->db->where(array('cid'=>$id))->order_by('company_sendchecklog.id desc')->join('manager','manager.id = company_sendchecklog.mid')->get('company_sendchecklog')->result_array();
		return $data;
			
	}
	
	public function sendmessageok()
	{
		$from_id = intval($this->input->post('from_id'));
		$to_id = intval($this->input->post('to_id'));
		$content = $this->input->post('content');	
		$addtime = time();
		$state = 0;
				
		$data = array(
			'from_id' 	=> $from_id,
			'to_id' 	=> $to_id,
			'cid' 		=> $to_id,	//0:系统消息，1:内容审核，2企业审核，3：推送审核
			'type' 		=> 0,		//0：系统消息，1：一审核，2：二审，3：三审
			'content' 	=> $content,
			'addtime' 	=> $addtime,
			'state' 	=> $state
		);		
		$this->db->insert('message',$data);
		
		$arr['err'] = '';
		return $arr;
	}
	
	
	public function getonecompany(){
		$com_id = $this->uri->segment(4);
		if(!$com_id) return '';
		return $this->db->where(array('com_id'=>$com_id))->get('company')->row_array();
	}
	
	public function getcates(){
		return $this->db->order_by('cat_rank desc')->get('category')->result_array();	
	}
	
	public function getindustry_option($cid=''){
		$str = '';
		$industry_list = $this->db->order_by('irank ASC,iid ASC')->get('company_industry')->result_array();	
		foreach($industry_list as $list){
			$selected = $list['iid'] == $cid ? 'selected="selected"' : '';
			$str .= '<option value="'.$list['iid'].'" '.$selected.'>'.$list['iname'].'</option>';	
		}
		return $str;
	}
	
	public function update()
	{
		$action = $this->input->post('action');
		$newpass = $this->input->post('newpass');
		$renewpass = $this->input->post('renewpass');
		$com_id = intval($this->input->post('com_id'));
		
		
		if($newpass == $renewpass && $com_id > 0){
			$row = $this->db->where(array('com_id'=>$com_id))->join('member','member.mem_id = company.mid')->get('company')->row_array();
			if(!empty($row)){
				$n_pass = md5($newpass.$row['mem_salt']);
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
	
	public function del()
	{
		$com_id = $this->uri->segment(4);
		if($com_id > 0){
			$this->db->delete('company', array('com_id' => $com_id));
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
				'base_url'       => base_url(admin_url().'company/index/'),
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 4,
				'full_tag_open'  => "<ul class='paginList'>",//关闭标签
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
	
	
	//搜索
   public function search_content($cid = FALSE,$state ,$title = FALSE,$num,$offset)
	{
        $this->db->order_by('com_id', 'DESC');
        $this->db->join('member','member.mem_id = company.mid');
		 if($cid){
		    $this->db->where('company.com_industry',$cid);
		 }
		 if($title){
			 $this->db->like('company.com_name',$title);	 
		 }
		 
		 $this->db->where('company.com_state',$state);
		$query = $this->db->get('company',$num, $offset);

		return $query->result_array();
		
	} 
	
	//搜索条件查询条数
     public function search_content_nums($cid = FALSE,$state,$title = FALSE)
	{
		$this->db->join('member','member.mem_id = company.mid');
		 if($cid){
		    $this->db->where('company.com_industry',$cid);
		 }
		 if($title){
			 $this->db->like('company.com_name',$title);	 
		 }
		 $this->db->where('company.com_state',$state);
		
		return $this->db->count_all_results('company');
	
		
	}
	
    
    
}