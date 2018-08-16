<?php
class Setting_model extends CI_Model {
	
	public function get_configs()
    {
        return $this->db->get('configs')->result_array();
    }
	
	public function update(){
	
		$data = array(
			'sitename' => $this->input->post('sitename'),
			'sitetitle' => $this->input->post('sitetitle'),
			'sitekeywords' => $this->input->post('sitekeywords'),
			'sitedescription' => $this->input->post('sitedescription'),
			'siteurl' => $this->input->post('siteurl'),
			'author' => $this->input->post('author'),
			'source' => $this->input->post('source')
		);
		foreach($data as $k=>$v){
			//$this->db->query("update {pre}configs set cfg_value = '".$v."' where cfg_en_title = '".$k."'");
			$this->db->update('configs',array('cfg_value'=>$v),array('cfg_en_title'=>$k));
			
		}
		$this->makeSetting();
		$data['err'] = '';
		return $data;
		//$this->db->insert('configs');
	}
	
	//生成配置缓存文件
	public function makeSetting(){

		$arr = $this->db->get('configs')->result_array();
		if(is_array($arr) && !empty($arr)){
			foreach($arr as $k=>$c){
				$Narr[$c['id']] = $c;
			}
		}else{
			$Narr = array();	
		}
		//$catesoption = $this->cate->resetCategoryData($cates,'','','');	
		$FilePath = 'application/cache/static/setting.php';
		$content = '<?php $sysconfig=';

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