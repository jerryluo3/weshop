<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Card extends MY_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model(admin_url().'card_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = $this->card_model->getCardList();
		
		$this->load->view(admin_url().'card',$data);
	}
	
	public function build(){
		
		$data = array();
		
		$this->load->view(admin_url().'card_build',$data);
			
	}
	
	public function search()
	{   
		
	
		$str = $this->uri->segment(4);
		$arr = !empty($str) ? explode('-',$str) : array();
		
		if(!empty($arr)){
			if(isset($arr[0]) && !empty($arr[0])){
				$this->db->where('( card_number like "%'.$arr[0].'%" OR phone like "%'.$arr[0].'%")');
				$data['title'] = $arr[0];
			}
			if($arr[1] != 99){
				$this->db->where('card_state',$arr[1]);
				$data['status'] = $arr[1];	
			}
				
		}
		
		$data['card_list'] = $this->db->select('cards.*,users.id,users.phone')->order_by('card_id DESC')->limit(100)->join('users','users.id = cards.card_uid','left')->get('cards')->result_array();
		//echo $this->db->last_query();
		$data['page_list'] = '';

	   $this->load->view(admin_url().'card',$data);

	}
	
	
	public function ewm(){
		
		$id = $this->uri->segment(4);
		$id = $id > 0 ? $id : 1;
		$row = $this->db->where(array('card_id'=>$id))->get('cards')->row_array();
		
		$path = 'data/card/';		//文件存放路径
		$logo = 'data/'.admin_url().'images/logo_ewm.png';				//准备好的logo图片
		$words = site_url('cart/recharge/'.$row['card_id']);		//二维码内容 
		$level = 'M';		//容错级别 L M Q H
		$size = 10;			//生成图片大小 
		$ext = 'png';		//文件格式
		
		$timg = $path.$id.'.'.$ext;
		
		if(!file_exists($timg)){			
			$data['ewm'] = $this->buildEwm($id,$words,$level,$size,$path,$logo,$ext);		
		}else{
			$data['ewm'] = 	$timg;
		}
		
		$this->load->view(admin_url().'card_ewm',$data);
			
	}
	
	public function updateQR(){
		set_time_limit(0);
		$s_id = 2009;
		$e_id = 4008;
		for($i=$s_id; $i<$e_id; $i++){
			$row = $this->db->where(array('card_id'=>$i))->get('cards')->row_array();
			
			$path = 'data/card/';		//文件存放路径
			$logo = 'data/'.admin_url().'images/logo_ewm.png';				//准备好的logo图片
			$words = site_url('cart/recharge/'.$row['card_id']);		//二维码内容 
			$level = 'M';		//容错级别 L M Q H
			$size = 10;			//生成图片大小 
			$ext = 'png';		//文件格式
			
			$timg = $path.$i.'.'.$ext;
			
			if(!file_exists($timg)){			
				$ewm = $this->buildEwm($i,$words,$level,$size,$path,$logo,$ext);		
			}else{
				$ewm = 	$timg;
			}
		}
	}
	
	
	public function exportCard(){
		
		$data = array();
		$this->load->view(admin_url().'card_export',$data);	
	}
	
	
	public function buildEwm($id,$words,$errorCorrectionLevel,$matrixPointSize = 10,$path,$logo,$ext='png'){
		$this->load->helper('phpqrcode');
		
		$start_img = $path.$id.'_s.'.$ext;
		$end_img = $path.$id.'.'.$ext;
		
		//生成二维码图片 
		QRcode::png($words, $start_img, $errorCorrectionLevel, $matrixPointSize, 2); 		
		$QR = $start_img;//已经生成的原始二维码图 
		 
		if ($logo !== FALSE) { 
		 $QR = imagecreatefromstring(file_get_contents($QR)); 
		 $logo = imagecreatefromstring(file_get_contents($logo)); 
		 $QR_width = imagesx($QR);//二维码图片宽度 
		 $QR_height = imagesy($QR);//二维码图片高度 
		 $logo_width = imagesx($logo);//logo图片宽度 
		 $logo_height = imagesy($logo);//logo图片高度 
		 $logo_qr_width = $QR_width / 5; 
		 $scale = $logo_width/$logo_qr_width; 
		 $logo_qr_height = $logo_height/$scale; 
		 $from_width = ($QR_width - $logo_qr_width) / 2; 
		 //重新组合图片并调整大小 
		 imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
		 $logo_qr_height, $logo_width, $logo_height); 
		 
		}
		unlink($start_img);
		//输出图片 
		imagepng($QR, $end_img);
		
		return $end_img;
			
	}
	
	
	
	
}
