<?php
class Productscategory_model extends CI_Model {

	public $da = array();
    public function __construct()
    {
		$this->load->library('productscate');
    }
	
	 public function get_category($slug = FALSE)
    {
		//$cids = implode(",",$cids_arr);

		$cates = $this->db->order_by('cat_id','ASC')->get('productscategory')->result_array();
		//echo $this->db->last_query();
		$data['cates'] = $this->productscate->resetCategoryData($cates,0,$_SESSION['user_purview']);
		
		
		
		return $data;
		
    }
	
	
	
	
	public function getcats($ids='',$optionlist=2,$purview=array(),$selectedid=''){
		
		
		//$cids = get_purview_cids();
		//if($_SESSION['user_group'] != 1){
//			$this->db->where_in('cat_id',$cids);
//		}
		if(!empty($ids)){
			$this->db->where_in('cat_id',$ids);	
		}
		$cates = $this->db->get("productscategory")->result_array();
		$catesoption = $this->productscate->resetCategoryData($cates,$optionlist,$purview,$selectedid);
		return $catesoption;
	}
	
	//public function getfrow($fid){
//		if(empty(intval($fid))) return "";
//		$query = "select * from {pre}category where cat_fid = ".$fid;
//		return $query->result_array();
//	}
	
	public function getonecate($id){
		if(empty($id)) return "";		
		return $this->db->where(array('cat_id'=>$id))->get('productscategory')->row_array();
	}
	
	
	public function update()
	{
		//$this->load->helper('url');		
		
		$cat_id = $this->input->post('cat_id');
		$action = $this->input->post('action');
		
		$fid = $this->input->post('cat_fid');
		$cat_name = $this->input->post('cat_name');
		$cat_img = $this->input->post('cat_img');
		$cat_seotitle = $this->input->post('cat_seotitle');
		$cat_seokeywords = $this->input->post('cat_seokeywords');
		$cat_seodescription = $this->input->post('cat_seodescription');
		
		if($fid > 0){
			$frow = $this->db->where(array('cat_id'=>$fid))->get('productscategory')->row_array();
			$cat_depth = $frow['cat_depth']+1;
		}else{
			$cat_depth = 0;	
		}
		//$this->updateCateSon($fid);
		
		$data = array(
			'cat_fid' 				=> $fid,
			'cat_name' 				=> $cat_name,
			'cat_img' 				=> $cat_img,
			'cat_depth' 			=> $cat_depth,
			'cat_seotitle'			=> $cat_seotitle,
			'cat_seokeywords' 		=> $cat_seokeywords,
			'cat_seodescription' 	=> $cat_seodescription
		);
		
		
		if($action == "mod" && $cat_id > 0){
			//判断有没有修改栏目
			$ofrow = $this->db->where(array('cat_id'=>$cat_id))->get('productscategory')->row_array();
			
			if($ofrow['cat_fid'] !== $fid){//如果有修改栏目，更新上级子栏目
				$this->updateCateSon($fid,$cat_id,0);
				$this->updateCateSon($ofrow['cat_fid'],$cat_id,1);
			}	
			
			$where = "cat_id=".$cat_id;
			$this->db->update('productscategory', $data, $where);
			
					
		}else{
			$this->db->insert('productscategory', $data);
			$temp_cat_id = $this->db->insert_id();
			$this->updateCateSon($fid,$temp_cat_id,0);
		}
		
		$this->makeFclass();
	}
	
	public function updateCateSon($fid,$id,$type=0){
		
		if($fid > 0){			
			$frow = $this->db->where(array('cat_id'=>$fid))->get('productscategory')->row_array();
			$temp_son = $frow['cat_son'];
			$temp_son_arr = !empty($temp_son) ? explode(",",$temp_son) : array();
			
			
			if($type ==0){//添加子栏目
				
				if(!in_array($id,$temp_son_arr)){
					$temp_son_arr[] = $id;//加入当前添加的栏目ID
					
					$temp_son_str = implode(",",$temp_son_arr);
					
					$data = array('cat_son'=>$temp_son_str);				
					$where = "cat_id=".$fid;
					$this->db->update('productscategory', $data, $where);
				}
				if($frow['cat_fid'] > 0){
					$this->updateCateSon($frow['cat_fid'],$id,$type);
				}
			}else{//删除子栏目
				foreach($temp_son_arr as $t=> $ta){
					if($ta == $id){
						unset($temp_son_arr[$t]);	
					}
				}
				$temp_son_str = implode(",",$temp_son_arr);
				$data = array('cat_son'=>$temp_son_str);
				$where = "cat_id=".$fid;
				$this->db->update('productscategory', $data, $where);
				if($frow['cat_fid'] > 0){
					$this->updateCateSon($frow['cat_fid'],$id,$type);
				}
			}
		}
	}
	
	public function del($ids){
		//删除
		if(empty($ids)) return "";
		$frow = $this->db->where(array('cat_id'=>$ids))->get('productscategory')->row_array();
		if(isset($frow['cat_fid']) && $frow['cat_fid'] > 0){
			$this->updateCateSon($frow['cat_fid'],$ids,1);
		}
		$this->db->delete('productscategory', array('cat_id' => $ids));
		$data['err'] = '';
		return $data;
	}
	
	//生成栏目缓存文件
	public function makeFclass(){
	
		$arr = $this->db->get('category')->result_array();
		if(is_array($arr) && !empty($arr)){
			foreach($arr as $k=>$c){
				$Narr[$c['cat_id']] = $c;
			}
		}else{
			$Narr = array();	
		}
		//$catesoption = $this->cate->resetCategoryData($cates,'','','');	
		$FilePath = 'application/cache/static/fclass.php';
		$content = '<?php ';
		$content .= '$fclass=';
	
		$content .= var_export($Narr,true);
	
		$content .= ';?>';
		if(!is_file($FilePath)){
			
			write_file($FilePath, $content);
		}else{
			delete_files($FilePath);
			write_file($FilePath, $content);
		}
	
	}
	
	
    
    
}