<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'video_model');
		$this->load->model(admin_url().'videocategory_model');
		$this->load->library('form_validation');
		$this->load->helper('myfun');
	}
	/**
	 * setting
	 */
	public function index()
	{
		$data = $this->video_model->getVideoList();
		$data['catesoption'] = $this->videocategory_model->getcats('',1,$_SESSION['user_purview'],0);
		$this->load->view(admin_url().'video',$data);
	}	
	
	public function mod()
	{
		$id = $this->uri->segment(4);
		$act = $id > 0 ? 'mod' : 'add';
		$data['act'] = $act;
		if($act == "mod"){
			$data['row'] = $this->video_model->getonevideo();
		}
		$cid = isset($data['row']['cid']) ? $data['row']['cid'] : '';
		$data['catesoption'] = $this->videocategory_model->getcats('',1,$_SESSION['user_purview'],$cid);
		
		$this->load->view(admin_url().'video_mod', $data);
	}
	
	public function edit(){
		$this->form_validation->set_rules('cid', '类别', 'required');
		$this->form_validation->set_rules('title', '标题', 'required');

				
		if ($this->form_validation->run() === FALSE){
			$this->load->view(admin_url().'products_mod', $data);
		}else{
			$res = $this->video_model->update();
			if($res['err'] == ''){
				 $this->formTips('操作成功!',admin_url()."products/index",'2');
			}else{
				$this->formTips($res['err'],admin_url()."products/index",'2');
			}
		}
		
	}
	
	
	public function del(){
		$res = $this->video_model->del();
		echo $res;
		
	}
	
	
	public function search()
	{   
		
		$str = urldecode($this->uri->segment(4));
		$arr = !empty($str) ? explode('-',$str) : array();
		$cid = isset($arr[1]) ? $arr[1] : '';
		
		$data['catesoption'] = $this->videocategory_model->getcats('',1,$_SESSION['user_purview'],$cid);
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				$str = htmlspecialchars_decode($arr[0]);
				$this->db->like('title',$str);
				$data['title'] = $arr[0];
			}
			if($arr[1] > 0){
				$this->db->where('cid',$arr[1]);	
				$data['cid'] = $arr[1];		
			}
				
		}
	
	
		$data['video_list'] = $this->db->order_by('id','DESC')->limit(100)->join('videocategory','videocategory.cat_id = video.cid','left')->get('video')->result_array();
		//echo $this->db->last_query();
		$data['page_list'] = '';

	   $this->load->view(admin_url().'video',$data);
	    

	}
	
	public function choose(){
		
		$data = array();
		
		$this->load->view(admin_url().'products_choose', $data);
			
	}
	
	public function choosegoods(){
		
		$data = $this->video_model->getSearchGoodsList();
		
		$this->load->view(admin_url().'products_choose', $data);
			
	}
	
	public function copyProducts(){
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		
		$this->load->view(admin_url().'products_copy', $data);	
	}
	
	public function buhuo(){
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		
		$this->load->view(admin_url().'products_buhuo', $data);	
	}
	
	public function buhuodan(){
		//$data['buhuodan_list'] = $this->db->order_by('b_id desc')->join('luo_manager','luo_manager.id = luo_products_buhuo.b_uid','left')->join('luo_store','luo_store.cat_id = luo_products_buhuo.b_storeid','left')->get('luo_products_buhuo')->result_array();
		$data = $this->video_model->getBuhuodanList();
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		
		$this->load->view(admin_url().'products_buhuodan', $data);	
	}
	
	public function printBHD(){
		$b_id = intval($this->uri->segment(4));
		if($b_id > 0){
			$buhuodan = $this->db->where(array('b_id'=>$b_id))->join('luo_store','luo_store.cat_id = luo_products_buhuo.b_storeid','left')->get('luo_products_buhuo')->row_array();
			$storeid = $buhuodan['b_storeid'];
			$product_list = $this->db->where(array('storeid'=>$storeid,'state'=>1))->order_by('pid desc')->get('luo_products')->result_array();
			$data['product_list'] = $product_list;
			$data['buhuodan'] = $buhuodan;
		}else{
			$data['product_list'] = array();
			$data['buhuodan'] = array();	
		}
		
		$this->load->view(admin_url().'products_buhuodan_print', $data);	
		
	}
	
	public function exportProducts(){
		
		$data['store_list'] = $this->db->order_by('cat_id desc')->get('luo_store')->result_array();
		$this->load->view(admin_url().'products_export', $data);	
	}
	
	public function exportProductsSubmit(){
		ini_set("memory_limit","512M");
		
		$storeid = intval($this->uri->segment(4));
		$store = $this->db->where(array('cat_id'=>$storeid))->get('luo_store')->row_array();
		
		$product_list = $this->db->where(array('storeid'=>$storeid))->order_by('pid','DESC')->get('luo_products')->result_array();
		
		$this -> load -> library('PHPExcel');
        $this -> load -> library('PHPExcel/IOFactory');
		
		$objPHPExcel = new PHPExcel();
        $objPHPExcel -> getProperties() -> setTitle("export") -> setDescription("none");

        $objPHPExcel -> setActiveSheetIndex(0); 
        
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(0, 1, $store['cat_name']);
		$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, 1, '');
		
        // Fetching the table data
        $row = 2;
		
        foreach($product_list as $list)
        {
            $objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(0, $row, $list['title']);
			$objPHPExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, $row, $list['stocks']);

            $row++;
        } 

        $objPHPExcel -> setActiveSheetIndex(0);

        $objWriter = IOFactory :: createWriter($objPHPExcel, 'Excel5'); 
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . time() . rand(1000,9999) . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter -> save('php://output');
	}
	
	
	public function refreshQR(){
		$ids = $this->uri->segment(4);
		$ids_arr = explode("_",$ids);
		foreach($ids_arr as $id){
			$row = $this->db->where(array('pid'=>$id))->get('luo_products')->row_array();
		
			$path = 'data/ewm/';		//文件存放路径
			$logo = 'data/'.admin_url().'images/logo_ewm.png';				//准备好的logo图片
			$words = site_url('cart/index/'.$row['storeid'].'/'.$row['pid']);		//二维码内容 
			$level = 'M';		//容错级别 L M Q H
			$size = 10;			//生成图片大小 
			$ext = 'png';		//文件格式
			
			$timg = $path.$id.'.'.$ext;
			
			if(!file_exists($timg)){			
				$ewm = $this->buildEwm($id,$words,$level,$size,$path,$logo,$ext);		
			}else{
				$ewm = 	$timg;
			}
			p($ewm);	
		}
		p('更新完成 ');die();
	}
	
	
	public function ewm(){
		
		$id = $this->uri->segment(4);
		$id = $id > 0 ? $id : 1;
		$row = $this->db->where(array('pid'=>$id))->get('luo_products')->row_array();
		
		$path = 'data/ewm/';		//文件存放路径
		$logo = 'data/'.admin_url().'images/logo_ewm.png';				//准备好的logo图片
		$words = site_url('cart/index/'.$row['storeid'].'/'.$row['pid']);		//二维码内容 
		$level = 'M';		//容错级别 L M Q H
		$size = 20;			//生成图片大小 
		$ext = 'png';		//文件格式
		
		$timg = $path.$id.'.'.$ext;
		
		if(!file_exists($timg)){			
			$data['ewm'] = $this->buildEwm($id,$words,$level,$size,$path,$logo,$ext);		
		}else{
			$data['ewm'] = 	$timg;
		}
		
		$this->load->view(admin_url().'products_ewm',$data);
			
	}
	
	
	public function buildEwm($id,$words,$errorCorrectionLevel,$matrixPointSize = 10,$path,$logo,$ext='png'){
		$this->load->helper('phpqrcode');
		
		//$start_img = $path.$id.'_s.'.$ext;
		$start_img = $path.$id.'_s.'.$ext;
		$end_img = $path.$id.'.'.$ext;
		
		//生成二维码图片 
		QRcode::png($words, $end_img, $errorCorrectionLevel, $matrixPointSize, 2); 		
		$QR = $end_img;//已经生成的原始二维码图 
		 
		//if ($logo !== FALSE) { 
//		 $QR = imagecreatefromstring(file_get_contents($QR)); 
//		 $logo = imagecreatefromstring(file_get_contents($logo)); 
//		 $QR_width = imagesx($QR);//二维码图片宽度 
//		 $QR_height = imagesy($QR);//二维码图片高度 
//		 $logo_width = imagesx($logo);//logo图片宽度 
//		 $logo_height = imagesy($logo);//logo图片高度 
//		 $logo_qr_width = $QR_width / 5; 
//		 $scale = $logo_width/$logo_qr_width; 
//		 $logo_qr_height = $logo_height/$scale; 
//		 $from_width = ($QR_width - $logo_qr_width) / 2; 
//		 //重新组合图片并调整大小 
//		 imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
//		 $logo_qr_height, $logo_width, $logo_height); 
//		 
//		}
		//输出图片 
		//imagepng($QR, $end_img);
		
		return $end_img;
			
	}
	
	
	public function downloadQR(){
		
		
		$ids = $this->uri->segment(4);
		$ids = str_replace('_',',',$ids);
		if(!empty($ids)){
			$this->db->where("pid in($ids)");	
		}
		$datalist = $this->db->get('luo_products')->result_array();
		$filename = 'data/'.date('YmdHis').'.zip';
		if(!file_exists($filename)){
			$zip = new ZipArchive();
			if($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE){
				exit('无法打开文件，或者文件创建失败');	
			}
			foreach($datalist as $list){
				$val = 'data/ewm/'.$list['pid'].'.png';
				if(file_exists($val)){
					$ntitle = iconv ( 'UTF-8', 'GB2312', $list['title']);
					
					$zip->addFile( $val, $ntitle.'.png');
					//$zip->addFile( $val, basename($val));	
				}
			}
			$zip->close();//关闭 
		}
		if(!file_exists($filename)){
			exit("无法找到文件");	
		}
		
		header("Cache-Control: public"); 
		header("Content-Description: File Transfer"); 
		header('Content-disposition: attachment; filename='.basename($filename)); //文件名  
		header("Content-Type: application/zip"); //zip格式的  
		header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件  
		header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小  
		@readfile($filename);
		unlink($filename);
	
	}
	
	public function updateSalesNums(){
		
		$products_list  = $this->db->where(array('state'=>1))->get('luo_products')->result_array();
		foreach($products_list as $list){
			$tnums = $this->db->select('count(snum) as nums')->where(array('goodsid'=>$list['goodsid'],'storeid'=>$list['storeid']))->get('orders')->row_array();
			$this->db->update('luo_products',array('sales_nums'=>$tnums['nums']),array('pid'=>$list['pid']));	
		}
		echo '操作完成';
		
			
	}

	
	
	
}
