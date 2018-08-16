<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cate {
	private $tablename;
	private $cat_id;
	private $cat_fid;
	private $cat_name;
	private $cat_rank;
	private $cat_level;
	private $cat_display;
	private $level = 0;
	private $str;
	

     public function __construct($arr = array())
	{ 
		
		//通过引用的方式赋给变量来初始化原始的CodeIgniter对象
		$this->CI = &get_instance();
		//初始化表参数
		
		$this->tableName = (isset($arr['tableName'])) ? $arr['tableName'] : 'category';
		$this->cat_id = (isset($arr['cat_id'])) ? $arr['cat_id'] : 'cat_id';
		$this->cat_fid = (isset($arr['cat_fid'])) ? $arr['cat_fid'] : 'cat_fid';
		$this->cat_name = (isset($arr['cat_name'])) ? $arr['cat_name'] : 'cat_name';
		$this->cat_rank = (isset($arr['cat_rank'])) ? $arr['cat_rank'] : 'cat_rank';
		//$this->content = (isset($arr['content'])) ? $arr['content'] : 'content';
		$this->cat_level = (isset($arr['cat_level'])) ? $arr['cat_level'] : 'cat_level';
		$this->cat_display = (isset($arr['cat_display'])) ? $arr['cat_display'] : 'cat_display';

		
	}
	
	public function resetCategoryData($arr,$optionlist,$purview='',$selectedid=''){	

		if(is_array($arr) && !empty($arr)){
			foreach($arr as $k=>$c){
				$Narr[$c['cat_id']] = $c;
			}
		}else{
			$Narr = array();	
		}
		$this->setCateData($Narr,$optionlist,$purview,$selectedid);
		return $this->str;
	}
	
	
	
	public function setCateData($arr,$optionlist,$purview='',$selectedid=''){
			
			//$darr = empty($t_arr) ? $arr : $t_arr;
			
			if(!empty($arr)){
				foreach($arr as $k=>$res){
					if(isset($arr[$k])){
						$temp_warp =$res['cat_depth'] > 0 ? str_repeat("　",$res['cat_depth']*2-1) : '';
						if($optionlist == 1){//下拉框
							$selected = $res['cat_id'] == $selectedid ? 'selected' : '';
							$this->str .= '<option value="'.$res['cat_id'].'" '.$selected.'>'.$temp_warp.'├'.$res['cat_name'].'</option>';	
						}else if($optionlist == 2){//列表
							$this->str .= '<li><a href="javascript:void(0);" onClick="setDropdown(\'fid_dropdownMenu\',\'cat_fid\','.$res['cat_id'].',\''.$res['cat_name'].'\');">'.$temp_warp.'┣ '.$res['cat_name'].'</a></li>';
						}else if($optionlist == 3){//权限列表
							$ck = '';
							$checkgrade = 5;  //5级审核
							if(!empty($purview)){
								$pur_arr = explode(",",$purview);
								
								for($c=1;$c <= $checkgrade;$c++){
									$contentcheck_check = in_array('contentcheck'.$res['cat_id'].'_'.$c,$pur_arr) ? 'checked' : '';
									$ck .= 	'　<input type="checkbox" name="purview[]" value="contentcheck'.$res['cat_id'].'_'.$c.'" id="contentcheck'.$res['cat_id'].'_'.$c.'" '.$contentcheck_check.'>'.$this->get_check_grade_name($c);
								}
								
								$add_check = in_array('contentadd'.$res['cat_id'],$pur_arr) ? 'checked' : '';
								$mod_check = in_array('contentmod'.$res['cat_id'],$pur_arr) ? 'checked' : '';
								$del_check = in_array('contentdel'.$res['cat_id'],$pur_arr) ? 'checked' : '';
								
								
								$this->str .= '<li><label>&nbsp;</label><cite>'.$temp_warp.$res['cat_name'].'　　<input type="checkbox" name="purview[]" value="contentadd'.$res['cat_id'].'" id="contentadd'.$res['cat_id'].'" '.$add_check.'>添加　<input type="checkbox" name="purview[]" value="contentmod'.$res['cat_id'].'" id="contentmod'.$res['cat_id'].'" '.$mod_check.'>修改　<input type="checkbox" name="purview[]" value="contentdel'.$res['cat_id'].'" id="contentdel'.$res['cat_id'].'" '.$del_check.'>删除　'.$ck.'</cite></li>';
							}else{
								for($c=1;$c <= $checkgrade;$c++){
									$ck .= 	'　<input type="checkbox" name="purview[]" value="contentcheck'.$res['cat_id'].'_'.$c.'" id="contentcheck'.$res['cat_id'].'_'.$c.'">'.$this->get_check_grade_name($c);
								}
								$this->str .= '<li><label>&nbsp;</label><cite>'.$temp_warp.$res['cat_name'].'　　<input type="checkbox" name="purview[]" value="contentadd'.$res['cat_id'].'" id="contentadd'.$res['cat_id'].'">添加　<input type="checkbox" name="purview[]" value="contentmod'.$res['cat_id'].'" id="contentmod'.$res['cat_id'].'">修改　<input type="checkbox" name="purview[]" value="contentdel'.$res['cat_id'].'" id="contentdel'.$res['cat_id'].'">删除　'.$ck.'</cite></li>';
							}
						}else{//后台栏目列表
							$mod_name = 'category';
							$mod_op = '<a href="javascript:;" onclick="openModel(\''.admin_url().'category/mod/'.$res['cat_id'].'\', \'编辑商品信息\');"><i class="fa fa-edit"></i></a>';
							$del_op = '　<a href="javascript:;" onClick="delmsg(\''.admin_url().'category/del/'.$res['cat_id'].'\', this, '.$res['cat_id'].');"><i class="fa fa-trash"></i></a>';
						$this->str .= '<tr>
									  <td>'.$temp_warp.'├ '.$res['cat_name'].'</td>
									  <td>'.$res['cat_fid'].'</td>
									  <td>'.$res['cat_img'].'</td>
									  <td>'.$res['cat_son'].'</td>
									  <td>'.$res['cat_rank'].'</td>
									  <td>'.$mod_op.$del_op.'</td>
									</tr>';
						}
						if(!empty($res['cat_son'])){
							$son_arr = explode(",",$res['cat_son']);
							foreach($son_arr as $son){
								if(isset($arr[$son])){
									$t_arr[$son] = $arr[$son];
									unset($arr[$son]);
								}
							}
							if(!empty($t_arr)){
								$this->setCateData($t_arr,$optionlist,$purview,$selectedid);
							}
							$t_arr = array();
						}
					}
					
				}
			}
	}
	
	function get_check_grade_name($c){
		switch($c){
			case 1:$str='一级审核';break;
			case 2:$str='二级审核';break;
			case 3:$str='三级审核';break;
			case 4:$str='四级审核';break;
			case 5:$str='五级审核';break;
			default:$str='一级审核';break;	
		}
		return $str;	
	}
	
	//public function setCateData($arr,$resArr,$optionlist=false){
//		if($optionlist){//下拉框
//			foreach($resArr as $res){
//				if(is_array($res)){
//					$this->level++;
//					resetCategoryData($arr,$res,$optionlist);	
//				}else{
//					$temp_warp = $this->level > 0 ? str_repeat("　",$this->level*2) : 0;
//					$this->str .= '<option value="'.$res['cat_id'].'">'.$temp_warp.'├'.$res['cat_name'].'</option>';	
//				}
//			}
//		}else{
//			foreach($resArr as $res){
//				if(is_array($res)){
//					$this->setCateData($arr,$res,$optionlist);
//				}else{
//					$temp_warp = $arr[$res['cat_id']]['cat_depth'] > 0 ? str_repeat("　",$arr[$res['cat_id']]['cat_depth']*2) : 0;
//					$this->str .= '<tr>
//							  <td>'.$temp_warp.'├ '.$arr[$res['cat_id']]['cat_name'].'</td>
//							  <td>'.$arr[$res['cat_id']]['cat_fid'].'</td>
//							  <td>'.$arr[$res['cat_id']]['cat_img'].'</td>
//							  <td>'.$arr[$res['cat_id']]['cat_son'].'</td>
//							  <td>'.$arr[$res['cat_id']]['cat_rank'].'</td>
//							  <td>操作</td>
//							</tr>';	
//				}
//				
//			}
//			
//		}
//	}
	
	public function index(){
			echo "aa";
	}
}

