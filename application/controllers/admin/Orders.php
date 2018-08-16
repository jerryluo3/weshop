<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'orders_model');
		$this->load->library('form_validation');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->orders_model->getOrdersList();
		$this->load->view(admin_url().'orders',$data);
	}
	
	public function ziti()
	{
		$data = $this->orders_model->getZitiOrdersList();
		$this->load->view(admin_url().'orders',$data);
	}
	
	public function shouhou()
	{
		$data = $this->orders_model->getShouhouOrdersList();
		$this->load->view(admin_url().'orders',$data);
	}	
	
	public function del(){
		$res = $this->orders_model->del();
		if($res['err'] == ''){
			 $this->formTips('操作成功!',admin_url()."orders/index",'2');
		}else{
			$this->formTips($res['err'],admin_url()."orders/index",'2');
		}
	}
	
	//微信支付未支付订单查询
	public function wepayUnpaidOrder(){
		
		$data = array();
		
		$this->load->view(admin_url().'orders_unpaid',$data);
	}
	
	public function wepayOrderQuery(){
		
		
		require_once "application/third_party/wepay/lib/WxPay.Api.php";
		require_once "application/third_party/wepay/WxPay.JsApiPay.php";
		
		$oid = $this->input->post('oid');
		$out_trade_no = WxPayConfig::MCHID.$oid;
		
		$input = new WxPayOrderQuery();
		$input->SetOut_trade_no($out_trade_no);
		//$input->SetTransaction_id($out_trade_no);
		$res = WxPayApi::orderQuery($input);


		if(isset($res['result_code']) && $res['result_code'] == 'SUCCESS'){
			$data['res'] = $res;
		}		
		echo "<script>go('". admin_url()."orders/orders_queryresult"."');</script>";	
	}
	
	
	//搜索
	public function search()
	{   
		
	    $str = $this->uri->segment(4);
		$arr = !empty($str) ? explode('-',$str) : array();
		$page_size = 20;
		$page = intval($this->uri->segment(5));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		
		
		$offset = ($page-1)*$page_size;
		
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				//$this->db->like('onumber',$arr[0]);	
				//$this->db->or_like('orders.title',$arr[0]);
				$a = urldecode($arr[0]);					
				$this->db->where('( onumber like "%'.$a.'%" OR orders.title like "%'.$a.'%")');
				$data['title'] = $a;
			}
			if($arr[1] != 99){
				$this->db->where('orders.status',$arr[1]);
				$data['status'] = $arr[1];	
			}
			if($arr[2] != 99){
				$this->db->where('orders.zhifu_type',$arr[2]);		
				$data['zhifu_type'] = $arr[2];	
			}
			if($arr[3] != 99){
				$this->db->where('orders.storeid',$arr[3]);	
				$data['storeid'] = $arr[3];		
			}
			if($arr[4] > 0){
				$stime = $arr[4];
				$this->db->where('orders.closing_time >=',$stime);	
				$data['stime'] = $arr[4];		
			}
			if($arr[5] > 0){
				$etime = $arr[5]-1;
				$this->db->where('orders.closing_time <',$etime);	
				$data['etime'] = $arr[5];		
			}
				
		}
		$total_nums = $this->db->count_all_results('orders');
		$data['total_nums'] = $total_nums;
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				//$this->db->like('onumber',$arr[0]);	
				//$this->db->or_like('orders.title',$arr[0]);	
				$a = urldecode($arr[0]);					
				$this->db->where('( onumber like "%'.$a.'%" OR orders.title like "%'.$a.'%")');
				$data['title'] = $a;
			}
			if($arr[1] != 99){
				$this->db->where('orders.status',$arr[1]);
				$data['status'] = $arr[1];	
			}
			if($arr[2] != 99){
				$this->db->where('orders.zhifu_type',$arr[2]);		
				$data['zhifu_type'] = $arr[2];	
			}
			if($arr[3] != 99){
				$this->db->where('orders.storeid',$arr[3]);	
				$data['storeid'] = $arr[3];		
			}
			if($arr[4] > 0){
				$stime = $arr[4];
				$this->db->where('orders.closing_time >=',$stime);	
				$data['stime'] = $arr[4];		
			}
			if($arr[5] > 0){
				$etime = $arr[5];
				$this->db->where('orders.closing_time <',$etime);	
				$data['etime'] = $arr[5];		
			}
				
		}
				
		$data['orders_list'] = $this->db->select('orders.*,luo_store.*,users.id as users_id,users.phone')->order_by('id','DESC')->limit($page_size,$offset)->join('luo_store','luo_store.cat_id = orders.storeid','left')->join('users','users.id = orders.uid','left')->get('orders')->result_array();
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				//$this->db->like('onumber',$arr[0]);	
				//$this->db->or_like('orders.title',$arr[0]);	
				$a = urldecode($arr[0]);					
				$this->db->where('( onumber like "%'.$a.'%" OR orders.title like "%'.$a.'%")');
				$data['title'] = $a;
			}
			if($arr[1] != 99){
				$this->db->where('orders.status',$arr[1]);
				$data['status'] = $arr[1];	
			}
			if($arr[2] != 99){
				$this->db->where('orders.zhifu_type',$arr[2]);		
				$data['zhifu_type'] = $arr[2];	
			}
			if($arr[3] != 99){
				$this->db->where('orders.storeid',$arr[3]);	
				$data['storeid'] = $arr[3];		
			}
			if($arr[4] > 0){
				$stime = $arr[4];
				$this->db->where('orders.closing_time >=',$stime);	
				$data['stime'] = $arr[4];		
			}
			if($arr[5] > 0){
				$etime = $arr[5];
				$this->db->where('orders.closing_time <',$etime);	
				$data['etime'] = $arr[5];		
			}
				
		}
		$data['all_amount'] = $this->db->select('sum(total_price) as amount')->order_by('id','DESC')->get('orders')->row_array();
		//echo $this->db->last_query();

		//分页
		$page_url = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/{page}';
		$tpage = new page($total_nums,$page_size,$now_page,$page_url);
		$data['page_list'] = $tpage->myde_write('page');
		

	   $this->load->view(admin_url().'orders',$data);

	}
	
	//订单统计
	public function analysis(){
		
		//过去一周订单
		$time = time();
		for($i=0;$i<10;$i++){
			$tday = strtotime(date('Y-m-d',strtotime("-$i day")));
			$ni = $i+1;
			$nday = strtotime(date('Y-m-d',strtotime("-$ni day")));
			
			$trow = $this->db->select('count(id) as counts,sum(total_price) as amount')->where(array('closing_time >= '=>$nday,'closing_time <'=>$tday,'status '=>4))->get('orders')->row_array();
			//echo $this->db->last_query();
			$data['lastweek'][] = $trow;
		}
		
		//echo date('Y-m-d H:i:s','1509517380');
		//echo date('Y-m-d H:i:s','1509603780');
		
		//今天订单
		$ntday = strtotime(date('Y-m-d'));
		//p(date('Y-m-d',$ntday));
		$data['today'] = $this->db->select('count(id) as counts,sum(total_price) as amount')->where(array('closing_time >= '=>$ntday,'status '=>4))->get('orders')->row_array();
		
		
		//本周订单
		$nweek = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
		$data['week'] = $this->db->select('count(id) as counts,sum(total_price) as amount')->where(array('closing_time >= '=>$nweek,'status '=>4))->get('orders')->row_array();
		//本月订单
		$nmonth = strtotime(date("Y-m-01"));

		$data['month'] = $this->db->select('count(id) as counts,sum(total_price) as amount')->where(array('closing_time >= '=>$nmonth,'status '=>4))->get('orders')->row_array();


		$this->load->view(admin_url().'analysis',$data);	
	}
	
	//销售排行
	public function ranking(){
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		$this_year = strtotime(date('Y-m-01'));
		$this->db->where(array('closing_time >='=>$this_year,'orders.status'=>4));
		$data['ranking_list'] = $this->db->select('goodsid,sum(snum) as snums,subposts.id,subposts.id,subposts.title,subposts.image,subposts.price')->order_by('snums desc')->group_by('goodsid')->limit(100)->join('subposts','subposts.id = orders.goodsid')->get('orders')->result_array();	
		//echo $this->db->last_query();
		$this->load->view(admin_url().'ranking',$data);
			
	}
	
	
	//搜索
	public function rankingsearch()
	{   
		
	    $str = $this->uri->segment(4);
		$arr = !empty($str) ? explode('-',$str) : array();
		$page_size = 20;
		$page = intval($this->uri->segment(5));
		$page = $page > 0 ? $page : 1;
		$data['page'] = $now_page = $page;
		
		
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				//$this->db->like('onumber',$arr[0]);	
				//$this->db->or_like('orders.title',$arr[0]);
				$a = urldecode($arr[0]);					
				$this->db->where('( orders.title like "%'.$a.'%")');
				$data['title'] = $a;
			}
			if($arr[1] != 99){
				$this->db->where('storeid',$arr[1]);	
				$data['storeid'] = $arr[1];		
			}
			if($arr[2] > 0){
				$stime = $arr[2];
				$this->db->where('closing_time >=',$stime);	
				$data['stime'] = $arr[2];		
			}
			if($arr[3] > 0){
				$etime = $arr[3]-1;
				$this->db->where('closing_time <',$etime);	
				$data['etime'] = $arr[3];		
			}
				
		}
		$this->db->where(array('orders.status'=>4));
		$data['ranking_list'] = $this->db->select('goodsid,closing_time,storeid,sum(snum) as snums,subposts.id,subposts.id,subposts.title,subposts.image,subposts.price')->order_by('snums desc')->group_by('goodsid')->limit(100)->join('subposts','subposts.id = orders.goodsid')->get('orders')->result_array();
		

		

	   $this->load->view(admin_url().'ranking',$data);

	}
	
	public function storeOrder(){
		
		$store_list = $this->db->where('cat_state > 0')->order_by('cat_id desc')->get('luo_store')->result_array();
		
		$store = array();
		$order_nums = array();
		$order_price = array();
		foreach($store_list as $list){
			$store[] = $list['cat_name'];
			$today = strtotime(date('Y-m-d'));
			$this->db->where('closing_time >= '.$today);
			$t_nums = $this->db->select('count(*) as counts,sum(total_price) as total_prices')->where(array('storeid'=>$list['cat_id']))->get('orders')->row_array();
			$order_nums[] = $t_nums['counts'];
			$order_price[] = $t_nums['total_prices'];
		}
		$data['store_list'] = json_encode($store); 
		$data['order_nums'] = json_encode($order_nums); 
		$data['order_price'] = json_encode($order_price); 
		$this->load->view(admin_url().'storeOrder',$data);
			
	}
	
	
	//搜索
	public function storeOrderSearch()	{   
		
	    $str = $this->uri->segment(4);
		$arr = !empty($str) ? explode('-',$str) : array();
		
		$store_list = $this->db->where('cat_state > 0')->order_by('cat_id desc')->get('luo_store')->result_array();
		
		
		
				
		$store = array();
		$order_nums = array();
		$order_price = array();
		foreach($store_list as $list){
			$store[] = $list['cat_name'];
			$today = strtotime(date('Y-m-d'));
			if(!empty($arr)){
				if($arr[0] > 0){
					$stime = $arr[0];
					$this->db->where('closing_time >=',$stime);	
					$data['stime'] = $arr[0];		
				}
				if($arr[1] > 0){
					$etime = $arr[1]-1;
					$this->db->where('closing_time <',$etime);	
					$data['etime'] = $arr[1];		
				}
					
			}else{
				$this->db->where('closing_time >= '.$today);
			}
			$t_nums = $this->db->select('count(*) as counts,sum(total_price) as total_prices')->where(array('storeid'=>$list['cat_id']))->get('orders')->row_array();
			$order_nums[] = $t_nums['counts'];
			$order_price[] = $t_nums['total_prices'];
		}
		$data['store_list'] = json_encode($store); 
		$data['order_nums'] = json_encode($order_nums); 
		$data['order_price'] = json_encode($order_price); 
		$this->load->view(admin_url().'storeOrder',$data);

	}
	
	public function timeOrder(){
		
		$store_list = $this->db->where('cat_state > 0')->order_by('cat_id desc')->get('luo_store')->result_array();
		
		$store = array();
		$order_nums = array();
		$order_price = array();
		$order_time = array();
		
		$today = strtotime(date('Y-m-d'));
		for($i = 0; $i<24; $i++){
			$j = $i+1;
			$stime = $today + $i*3600;
			$etime = $today + $j*3600;
			$res = $this->db->select('count(*) as counts,sum(total_price) as total_prices')->where(array('closing_time >='=>$stime,'closing_time <'=>$etime))->get('orders')->row_array();
			//p($this->db->last_query());
			$order_nums[] = $res['counts'];
			$order_price[] = intval($res['total_prices']);
			$order_time[] = $j;
		}
		
		
		$data['store_list'] = $store_list; 
		$data['order_nums'] = json_encode($order_nums); 
		$data['order_price'] = json_encode($order_price); 
		$data['order_time'] = json_encode($order_time); 
		

		
		$this->load->view(admin_url().'timeOrder',$data);
			
	}
	
	//搜索
	public function timeOrderSearch()	{   
		
	    $str = $this->uri->segment(4);
		$arr = !empty($str) ? explode('-',$str) : array();
		
		$store_list = $this->db->where('cat_state > 0')->order_by('cat_id desc')->get('luo_store')->result_array();
		
		
		if(!empty($arr)){
			if($arr[0] > 0){
				$today = $arr[0];
				$data['stime'] = $arr[0];		
			}else{
				$today = strtotime(date('Y-m-d'));	
			}
			if($arr[1] > 0){
				$storeid = $arr[1];
				$data['storeid'] = $arr[1];	
			}
				
		}else{
			$today = strtotime(date('Y-m-d'));
		}
		
		

		for($i = 0; $i<24; $i++){
			$j = $i+1;
			$stime = ($today + $i*3600);
			$etime = ($today + $j*3600);

			$res = $this->db->select('count(*) as counts,sum(total_price) as total_prices')->where(array('closing_time >='=>$stime,'closing_time <'=>$etime))->get('orders')->row_array();
			//p($this->db->last_query());
			$order_nums[] = $res['counts'];
			$order_price[] = intval($res['total_prices']);
			$order_time[] = $j;
		}
		
		$data['store_list'] = $store_list; 
		$data['order_nums'] = json_encode($order_nums); 
		$data['order_price'] = json_encode($order_price); 
		$data['order_time'] = json_encode($order_time); 
		
		$this->load->view(admin_url().'timeOrder',$data);

	}
	
	
	public function exportOrder(){
		ini_set("memory_limit","512M");
		$str = $this->uri->segment(4);
		$arr = !empty($str) ? explode('-',$str) : array();
		
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				//$this->db->like('onumber',$arr[0]);	
				//$this->db->or_like('orders.title',$arr[0]);	
				$a = urldecode($arr[0]);					
				$this->db->where('( onumber like "%'.$a.'%" OR orders.title like "%'.$a.'%")');
				$data['title'] = $a;
			}
			if($arr[1] != 99){
				$this->db->where('orders.status',$arr[1]);
				$data['status'] = $arr[1];	
			}
			if($arr[2] != 99){
				$this->db->where('orders.zhifu_type',$arr[2]);		
				$data['zhifu_type'] = $arr[2];	
			}
			if($arr[3] != 99){
				$this->db->where('orders.storeid',$arr[3]);	
				$data['storeid'] = $arr[3];		
			}
			if(intval($arr[4]) > 0){
				$stime = $arr[4];
				$this->db->where('orders.closing_time >=',$stime);	
				$data['stime'] = $arr[4];		
			}
			if(intval($arr[5]) > 0){
				$etime = $arr[5];
				$this->db->where('orders.closing_time <',$etime);	
				$data['etime'] = $arr[5];		
			}
				
		}
			
		$orders_list = $this->db->select('orders.*,luo_store.*,users.id as users_id,users.phone')->order_by('id','DESC')->join('luo_store','luo_store.cat_id = orders.storeid','left')->join('users','users.id = orders.uid','left')->get('orders')->result_array();
		

		
		$this -> load -> library('PHPExcel');
        $this -> load -> library('PHPExcel/IOFactory');
		
		$objPHPExcel = new PHPExcel();
        $objPHPExcel -> getProperties() -> setTitle("export") -> setDescription("none");

        $objPHPExcel -> setActiveSheetIndex(0); 
        
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(0, 1, 'ID');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, 1, '订单号');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(2, 1, '商品');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(3, 1, '数量');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(4, 1, '总价');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(5, 1, '成交时间');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(6, 1, '状态');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(7, 1, '支付方式');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(8, 1, '门店');
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(9, 1, '手机');
		
        // Fetching the table data
        $row = 2;
		
        foreach($orders_list as $list)
        {
			$paytype = $list['zhifu_type'] == 1 ? '微信' : ($list['zhifu_type'] == 2 ? '余额' : '支付宝');
            $objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(0, $row, $list['id']);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, $row, $list['onumber']);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(2, $row, $list['title']);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(3, $row, $list['snum']);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(4, $row, $list['total_price']);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(5, $row, date('Y-m-d H:i:s',$list['closing_time']));
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(6, $row, getOrderStatus($list['status']));
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(7, $row, $paytype);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(8, $row, $list['cat_name']);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(9, $row, $list['phone']);

            $row++;
        } 

        $objPHPExcel -> setActiveSheetIndex(0);

        $objWriter = IOFactory :: createWriter($objPHPExcel, 'Excel5'); 
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . time() . rand(1000,9999) . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter -> save('php://output');
		
		$data['status'] = 200;
		echo json_encode($data);
		
	}
	
	//活动订单优惠返现到余额
	public function huodongOrderRecharge(){
		$stime = strtotime('2017-11-10 08:00:00');
		$etime = strtotime('2017-11-10 18:00:00');
		$order_list = $this->db->where(array('closing_time >= '=>$stime,'closing_time <='=>$etime,'status'=>4))->get('orders')->result_array();
		foreach($order_list as $list){
			//用户信息
			$user_info = $this->db->where(array('uid'=>$list['uid']))->get('usersinfos')->row_array();			
			if(empty($user_info)){
				$user_arr = array(
					'uid'	=>	$list['uid']
				);
				$this->db->insert('usersinfos',$user_arr);
			}
			
			$uid = $list['uid'];
			$amount = $list['total_price']*0.5;
			$desc = $list['onumber'].'优惠返现';
			$s_money = $user_info['usermoney'] > 0 ? $user_info['usermoney'] : 0;
			$e_money = $s_money + $amount;
			$addtime = time();
			//插入钱包日志
			$recharge_arr = array(
				'uid'		=>	$uid,
				'type'		=>	1,						//1:充值 ， 0:消费
				'amount'	=>	$amount,	//充值金额
				'desc'		=>	$desc,					//备注
				's_money'	=>	$s_money,				//充值前金额
				'e_money'	=>	$e_money,				//充值后金额
				'addtime'	=>	$addtime
			);
			$this->db->insert('recharges',$recharge_arr);
			//修改钱包金额
			$this->db->update('usersinfos',array('usermoney'=>$e_money),array('uid'=>$uid));
			
		}
		echo '操作成功';
		
	}
	
	//活动订单优惠返现到余额手机号码
	public function huodongOrderRechargePhone(){
		$stime = strtotime('2017-11-10 08:00:00');
		$etime = strtotime('2017-11-10 18:00:00');
		$order_list = $this->db->select('orders.id,orders.uid,orders.closing_time,orders.status,users.id as u_id,users.status as u_status,users.phone')->where(array('closing_time >= '=>$stime,'closing_time <='=>$etime,'orders.status'=>4))->join('users','users.id = orders.uid')->get('orders')->result_array();
		foreach($order_list as $list){
			echo $list['phone'].'<br>';			
		}
		echo '操作成功';
		
	}
	
	
}
