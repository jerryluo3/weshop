<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

	/**
	 * AJAX UPDATE
	 *
	 */
	public function index()
	{
		$act = $this->input->post('act');
		echo $act;
	}
	
	public function state()
	{
		$state = $this->input->post('state');
		$id = $this->input->post('id');
		$row = $this->db->where(array('id'=>$id))->get("content")->row_array();
		if(!empty($row) && $row['state'] == $state){
			$nstate = $state == 1 ? 0 : 1;
			$this->db->update('content',array('state'=>$nstate),'id='.$id);
			$data['success'] = 'true';
			$data['msg'] = '操作成功';
			$data['nstate'] = $nstate;	
			echo json_encode($data);
		}
	}
	
	
	public function iscommend()
	{
		$iscommend = $this->input->post('iscommend');
		$id = $this->input->post('id');
		
		
		$row = $this->db->where(array('com_id'=>$id))->get("company")->row_array();
		if(!empty($row) && $row['com_iscommend'] == $iscommend){
			$niscommend = $iscommend == 1 ? 0 : 1;
			$this->db->update('company',array('com_iscommend'=>$niscommend),'com_id='.$id);
			$data['success'] = 'true';
			$data['msg'] = '操作成功';
			$data['niscommend'] = $niscommend;	
			echo json_encode($data);
		}
	}
	
	public function company(){
		$rowvalue = $this->input->post('rowvalue');
		$rowname = $this->input->post('rowname');
		$id = $this->input->post('id');	
		
		$row = $this->db->where(array('com_id'=>$id))->get("company")->row_array();		
		if(!empty($row) && $row['com_'.$rowname] == $rowvalue){
			$result = $rowvalue == 1 ? 0 : 1;
			$nname = 'com_'.$rowname;
			$this->db->update('company',array($nname=>$result),'com_id='.$id);
			//echo $this->db->last_query();
			$data['success'] = 'true';
			$data['msg'] = '操作成功';
			$data['result'] = $result;	
			echo json_encode($data);
		}
	}
	
	public function rank()
	{
		$rank = $this->input->post('rank');
		$id = $this->input->post('id');
		
		
		$row = $this->db->where(array('com_id'=>$id))->get("company")->row_array();
		if(!empty($row)){
			$this->db->update('company',array('com_rank'=>$rank),'com_id='.$id);
			$data['success'] = 'true';
			$data['msg'] = '操作成功';
			$data['nrank'] = $rank;	
			echo json_encode($data);
		}
	}
	
	public function shopproducts()
	{
		$this->load->helper('url_helper');
		$this->load->helper('url');
		$this->load->helper('myfun');
		$mid = $this->input->post('mid');
		$cid = $this->input->post('cid');
		$str = '';
		
		$products_list = $this->db->where(array('mid'=>$mid,'cid'=>$cid))->order_by('pid DESC')->limit(9)->get("company_products")->result_array();
		if(!empty($products_list)){
			foreach($products_list as $products){
				$str .= '<li><a href="'.site_url('company/shop/'.$mid.'/pview/'.$products['pid']).'"><img src="'.$products['picture'].'" /></a><p><a href="'.site_url('company/shop/'.$mid.'/pview/'.$products['pid']).'">'.utf_substr($products['title'],50).'</a></p></li>';
			}
		}
		$str = '<ul>'.$str.'</ul>';
		$data['success'] = 'true';
		$data['msg'] = '操作成功';
		$data['result'] = $str;
		echo json_encode($data);
	}
	
	
	public function sendmessage(){
		$to_id = $this->input->post('to_id');
		$content = $this->input->post('content');	
		$addtime = time();
		$state = 0;
		$from_id = $this->input->post('from_id');
		
		$arr = array(
			'from_id' 	=> $from_id,
			'to_id' 	=> $to_id,
			'content' 	=> $content,
			'addtime' 	=> $addtime,
			'state' 	=> $state
		);
		$this->db->insert('message',$arr);
		$data['success'] = 'true';
		//$data['msg'] = '操作成功';
		$data['result'] = 1;	
		echo json_encode($data);
			
	}
	
	public function checkusername(){
		$username = $this->input->post('username');
		
		$member = $this->db->where(array('mem_uname'=>$username))->get('member')->row_array();
		if(!empty($member)){
			$data['success'] = 'false';
			$data['msg'] = '用户名已存在';
			$data['result'] = 0;
		}else{
			$data['success'] = 'true';
			$data['msg'] = '可以使用';
			$data['result'] = 1;	
		}	
		echo json_encode($data);
			
	}
	
	public function feedback(){
		$uname = $this->input->post('uname');
		$mobile = $this->input->post('mobile');
		$email = $this->input->post('email');
		$content = $this->input->post('content');
		
		if(!empty($uname) && !empty($mobile) && !empty($content)){
			$arr = array(
				'uname' => $uname,
				'mobile' => $mobile,
				'email' => $email,
				'content' => $content,
				'addtime' => time()
			);
			$this->db->insert('feedback',$arr);
			$data['result'] = 1;
		}else{
			$data['result'] = '带*号为必填项';	
		}
		
		echo json_encode($data);
	}
	
	public function walletPay(){
		$uid = $this->input->post('uid');
		$oid = $this->input->post('oid');
		
		//$uid = 363;
		//$oid = '2017102798981011';
		
		$userinfo = $this->db->where(array('uid'=>$uid))->get('usersinfos')->row_array();
		$orders_list = $this->db->where(array('onumber'=>$oid))->get('orders')->result_array();
		
		$total_price = 0;
		foreach($orders_list as $olist){
			$total_price = $total_price + $olist['total_price']; 	
		}
		
		if(!empty($userinfo) && $total_price > 0){
			$usermoney = $userinfo['usermoney'];
			if($usermoney >= $total_price){
				$n_money = $usermoney - $total_price;
				//修改用户余额
				$this->db->update('usersinfos',array('usermoney'=>$n_money),array('uid'=>$uid));
				//写入资金明细
				$desc = '支付订单'.$oid;
				$addtime = time();
				$arr = array(
					'uid'		=>	$uid,
					'type'		=>	0,
					'amount'	=>	$total_price,
					's_money'	=>	$usermoney,
					'e_money'	=>	$n_money,
					'desc'	=>	$desc,
					'addtime'	=>	$addtime					
				);
				$this->db->insert('recharges',$arr);
				
				//修改订单状态
				$this->db->update('orders',array('status'=>4,'zhifu_type'=>2),array('onumber'=>$oid));
				
				//更新库存
				foreach($orders_list as $olist){
					$goodsid = $olist['goodsid'];
					$storeid = $olist['storeid'];
					$prow = $this->db->where(array('goodsid'=>$goodsid,'storeid'=>$storeid))->get('luo_products')->row_array();
					if($prow){
						$stocks = $prow['stocks']-$olist['snum'];
						$sales_nums = $prow['sales_nums']-$olist['snum'];
						$this->db->update('luo_products',array('stocks'=>$stocks,'sales_nums'=>$sales_nums),array('pid'=>$prow['pid']));	
					}
				}
				
				
				$data['status'] = 200;
			
			}else{
				$data['status'] = 1;	
			}
		}else{
			$data['status'] = 2;	
		}
		
		echo json_encode($data);
		
	}
	
	public function getuserinfo(){
		$uid = $this->input->post('uid');
		if($uid > 0){
			$row = $this->db->where(array('uid'=>$uid))->get('usersinfos')->row_array();
			$data['usermoney'] = $row['usermoney'];
			$data['result'] = 1;
		}else{
			$data['result'] = 2;	
		}
		echo json_encode($data);
	}
	
	public function userRecharge(){
		
		$card_number = $this->input->post('card_number');
		$card_pass = $this->input->post('card_pass');
		$phone = $this->input->post('phone');
		$rephone = $this->input->post('rephone');
		
		
		//$card_number = 'qy88881010';
//		$card_pass = '852814';
//		$phone = '15888324224';
//		$rephone = '15888324224';
	
		
		
		$data = array();
		if(empty($card_number) || strlen($card_number) != 10){
			$data['status'] = 1;	
		}else if( empty($card_pass) || strlen($card_pass) != 6){
			$data['status'] = 2;	
		}else if( empty($phone) || strlen($phone) != 11 ){
			$data['status'] = 3;	
		}else if( empty($rephone) || strlen($rephone) != 11){
			$data['status']	= 4;
		}else if( $phone !== $rephone){
			$data['status']	= 5;	
		}else{
			$user = $this->db->where(array('phone'=>$phone))->order_by('id desc')->get('users')	->row_array();
			
		
			if(empty($user)){
				$data['status'] = 6;	
			}else{
				$card = $this->db->where(array('card_number'=>$card_number,'card_usetime'=>0))->order_by('card_id desc')->get('cards')->row_array();
				
				if(empty($card)){
					$data['status'] = 7;	
				}else{
					
					if($card_pass == $card['card_pass']){
						
						//修改用户钱包金额
						$user_info = $this->db->where(array('uid'=>$user['id']))->get('usersinfos')->row_array();
						
						if(empty($user_info)){
							
							$user_info_arr = array(
								'uid'	=>	$user['id'],
								'sex'	=>	0,
								'gift_card_id'	=>	0
							);
							
							$this->db->insert('usersinfos',$user_info_arr);
							$user_info = $this->db->where(array('uid'=>$user['id']))->get('usersinfos')->row_array();
							
						}
						
						if($user_info['gift_card_id'] > 0){
							$data['status'] = 9;		//已经过赠送充值卡
						}else{
							$s_money = $user_info['usermoney'];
							$e_money = $s_money + $card['card_amount'];
							$this->db->update('usersinfos',array('usermoney'=>$e_money,'gift_card_id'=>$card['card_id']),array('uid'=>$user['id']));
							//写入充值明细
							$desc = $card_number.'充值';
							$addtime = time();
							$recharge_arr = array(
								'uid'		=>	$user['id'],
								'type'		=>	1,						//1:充值 ， 0:消费
								'amount'	=>	$card['card_amount'],	//充值金额
								'desc'		=>	$desc,					//备注
								's_money'	=>	$s_money,				//充值前金额
								'e_money'	=>	$e_money,				//充值后金额
								'addtime'	=>	$addtime
							);
							$this->db->insert('recharges',$recharge_arr);
							
							//修改充值卡状态
							$this->db->update('cards',array('card_uid'=>$user['id'],'card_usetime'=>$addtime,'card_usephone'=>$phone),array('card_id'=>$card['card_id']));
							
							$data['status'] = 200;
						}
						
					}
				}
			}
		}
		
		echo json_encode($data);
			
	}
	
	public function orderlist(){
		$odate = $this->input->post('odate');
		$odate = !empty($odate) ? $odate : date('Y-m-d');
		
		$order_list = $this->db->where(array('odate'=>$odate,'state'=>1))->get('hospital_order_info')->result_array();
		if(!empty($order_list)){
			$data['result'] = 1;
			$str = '';
			foreach($order_list as $list){
				$tstate = $list['state'] == 1 ? '正常' : '已取消';
				$tk = $list['state'] == 1 ? '' : 'style="color:#aaa;"';
				$str .= '<li><a '.$tk.' href="'.site_url('record/'.$list['id']).'">'.$list['uname'].'　　'.$list['odate'].'　　'.$tstate.'<i></i></a></li>';
			}
			$data['records'] = $str;
		}else{
			$data['records'] = '';
			$data['result'] = '没找到预订记录';	
		}
		echo json_encode($data);	
	}
	
	public function cancelorder(){
		$id = $this->input->post('id');	
		
		$this->db->update('hospital_order_info',array('state'=>0),array('id'=>$id));
		$data['result'] = 1;
		echo json_encode($data);	
	}
	
	public function checkUserLogin(){
		
		$user = $this->input->post('user');
		$pass = $this->input->post('pass');
		
		
		$row = $this->db->where(array('username'=>$user,'password'=>md5($pass)))->get('manager')->row_array();
		
		if($row){
			$data['result'] = 1;	
		}else{
			$data['result'] = '账号密码信息有误';	
		}
		echo json_encode($data);	
	}
	
	
	public function option(){
		$k = intval($this->input->post('k'));
		$type = intval($this->input->post('type'));   //0:减少  1：增加
		$price = $this->input->post('price');	
		
		//$k = 0;
//		$type = 1;
//		$price = 8.00;
		
		$cart_list = $this->cart->get_cart();
		
		
		if(isset($cart_list[$k]) ){
			$trow = $cart_list[$k];
			if($type == 1){//增加
				$product = $this->db->where(array('pid'=>$trow['pid']))->get('luo_products')->row_array();
				//if($product['stocks'] > $trow['num']){
					$this->cart->update($k,1);
				//}else{
					//$data['err'] = '库存不足';
//					$data['result'] = 0;
//					echo json_encode($data);
//					exit();	
//				}
			}else{
				$this->cart->update($k,-1);
			}
			$total_price = $this->cart->total_cart();
			$data['total_price'] = $type == 1 ? $total_price + $price : $total_price - $price;
			$data['err'] = '';
			
		}else{
			$data['err'] = '参数出错';	
		}
		
		echo json_encode($data);
		
	}
	
	public function getTotalPrice(){
		$data['total_price'] = $this->cart->total_cart();
		$data['err'] = '';
		echo json_encode($data);
	}
	
	public function delCart(){
		$k = intval($this->input->post('k'));	
		$cart_list = $this->cart->get_cart();
		if(isset($cart_list[$k]) ){
			$this->cart->del_cart($k);
			$data['total_price'] = $this->cart->total_cart();
			$data['err'] = '';
		}else{
			$data['err'] = '参数出错';		
		}
		
		echo json_encode($data);
	}
	
	public function shareInfo(){
		$phone = $this->input->post('phone');
		$phone = '363';
		
		if(!empty($phone)){
			$arr = array(
				'phone'	=>	$phone,
				'addtime'	=> time()
			);
			$this->db->insert('wexin_share',$arr);
			$data['result'] = 1;
		}else{
			$data['result'] = 0;	
		}
		echo json_encode($data);
	}
	
	
	public function importWeiyuan(){
		date_default_timezone_set('Asia/ShangHai');
		$this->load->library ( array('PHPExcel','PHPExcel/IOFactory'));
		
		
		$uploadfile = 'data/file/weiyuan.xls';//获取上传成功的Excel
		$reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
		$PHPExcel = $reader->load($uploadfile); // 载入excel文件
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumm = $sheet->getHighestColumn(); // 取得总列数
		
		
		//p($uploadfile);
		 
		/** 循环读取每个单元格的数据 */
		for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
			//p($sheet->getCell($column));die();
			$dataset = array();
			for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
				$dataset[] = $sheet->getCell($column.$row)->getValue();
				//$v = $sheet->getCell($column.$row)->getValue() == "/" ? '' : $sheet->getCell($column.$row)->getValue();	
				//echo $v."<br />";
			}
			$arr = array();
			
			//p($dataset);die();
			$pass = md5('123456');
			$addtime = time();
			$state = 1;
			if($dataset[0] > 0){
				
				//写入补货明细
				$arr = array(
	
					'wy_name'		=> $dataset[2],
					'wy_pass'		=> $pass,
					'wy_zhiwu'		=> $dataset[1],
					'wy_danwei'		=> $dataset[3],
					'wy_zhicheng'	=> $dataset[4],
					'wy_addtime'	=> $addtime,
					'wy_state'		=> $state
				);
				$this->db->insert('luo_weiyuan',$arr);

			}
			
		}	
	}
	
	public function forumSubmit(){
		$uid = $_SESSION['wy_id'];
		$title = $this->input->post('title');
		$content = $this->input->post('content');
		
		$arr = array(
			'f_uid'		=>	$uid,
			'f_title'	=>	$title,
			'f_content'	=>	$content,
			'f_state'	=>	1,
			'f_addtime'	=>	time(),
		);
		$this->db->insert('forum',$arr);
		
		$data['status'] = 200;
		echo json_encode($data);
	}
	
	public function threadSubmit(){
		$uid = $_SESSION['wy_id'];
		$f_id = $this->input->post('f_id');
		$fp_content = $this->input->post('fp_content');
		
		$arr = array(
			'fp_uid'		=>	$uid,
			'fp_fid'		=>	$f_id,
			'fp_content'	=>	$fp_content,
			'fp_state'		=>	1,
			'fp_addtime'	=>	time(),
		);
		$this->db->insert('forum_post',$arr);
		
		$thread = $this->db->where(array('f_id'=>$f_id))->join('weiyuan','weiyuan.wy_id = forum.f_uid','left')->get('forum')->row_array();
		$n_nums = $thread['f_nums'] + 1;
		$this->db->update('forum',array('f_nums'=>$n_nums),array('f_id'=>$f_id));
		
		$data['status'] = 200;
		echo json_encode($data);	
	}
	
	public function userLogin(){
		$login_user = $this->input->post('login_user');
		$login_pass = $this->input->post('login_pass');	
		
		$user = $this->db->where(array('wy_name'=>$login_user,'wy_pass'=>md5($login_pass)))->get('weiyuan')->row_array();
		
		if($user){			
			$this->session->set_userdata($user);
			$data['status'] = 200;	
		}else{
			$data['status'] = 1;	
		}
		echo json_encode($data);
	}
	
	public function loginQuit(){
		$this->session->sess_destroy();
		$data['status'] = 200;	
		echo json_encode($data);
	}
	

	
}
