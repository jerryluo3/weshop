<?php
class Home_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
	public function home(){
		$data = $this->headerSubMenu();

		
		//广告
		$data['index_banner'] = $this->db->where(array('ads_cid'=>1))->order_by('ads_rank asc,ads_id asc')->limit(5)->get('ads')->result_array();
		$data['mini_banner'] = $this->db->where(array('ads_cid'=>2))->order_by('ads_rank asc,ads_id asc')->limit(1)->get('ads')->row_array();
		
		$data['news_list'] = $this->db->order_by('rank asc,id desc')->limit(6)->get('content')->result_array();
		$data['announce_list'] = $this->db->where_in('cid',array(8))->order_by('rank asc,id desc')->limit(5)->get('content')->result_array();
		$data['pic_list'] = $this->db->where('iscommend = 1 and picture != ""')->order_by('rank asc,id desc')->limit(5)->get('content')->result_array();
		$data['n1_list'] = $this->db->where_in('cid in (3)')->order_by('rank asc,id desc')->limit(9)->get('content')->result_array();
		$data['n2_list'] = $this->db->where('cid in (9,10,11,20)')->order_by('rank asc,id desc')->limit(9)->get('content')->result_array();
		$data['n3_list'] = $this->db->where_in('cid',array(12,13))->order_by('rank asc,id desc')->limit(9)->get('content')->result_array();
		
		return $data;
	}
	
	public function query(){
		$data = $this->headerSubMenu();
		
		return $data;
	}
	
	function headerSubMenu(){
		//PC
		$data['cat1_list'] = $this->db->where(array('cat_fid'=>1))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat2_list'] = $this->db->where(array('cat_fid'=>2))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat3_list'] = $this->db->where(array('cat_fid'=>3))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat4_list'] = $this->db->where(array('cat_fid'=>4))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat5_list'] = $this->db->where(array('cat_fid'=>5))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cat6_list'] = $this->db->where(array('cat_fid'=>6))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		
		
		return $data;	
	}
	
	function about(){
		$data = $this->headerSubMenu();
		$cid = intval($this->uri->segment(2));	
		$cid = $cid > 0 ? $cid : 14;

		//PC
		$data['cat_list'] = $this->db->where(array('cat_fid'=>6))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['row'] = $this->db->where(array('cid'=>$cid))->order_by('rank asc,id desc')->join('category','category.cat_id = content.cid','left')->get('content')->row_array();	
		
		$data['cid'] = $cid;
		

		return $data;	
	}
	
	
	function culture(){
		$data = $this->headerSubMenu();
		$cid = intval($this->uri->segment(2));	
		$cid = $cid > 0 ? $cid : 21;

		$data['clist'] = $this->db->where(array('cat_fid'=>7))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['cid'] = $cid;
		
		$data['row'] = $this->db->where(array('cid'=>$cid))->order_by('rank asc,id desc')->get('content')->row_array();
		
		
		$data['ads'] = $this->db->where(array('ads_cid'=>7))->order_by('ads_id desc')->get('ads')->row_array();
		$data['mads'] = $this->db->where(array('ads_cid'=>16))->order_by('ads_id desc')->get('ads')->row_array();
		

		return $data;	
	}
	
	function team(){
		$data = $this->headerSubMenu();
		$cid = intval($this->uri->segment(2));	
		$cid = $cid > 0 ? $cid : 22;

		$data['clist'] = $this->db->where(array('cat_fid'=>7))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		//$data['team_list'] = $this->db->where(array('cid'=>$cid))->order_by('rank asc,id desc')->get('content')->row_array();
		$data['cid'] = $cid;
		
		
		$sons = $this->db->where('cat_id',$cid)->get('category')->row_array();
		$t_sons = $sons['cat_son'];
		if(!empty($t_sons)){
			$cids = explode(",",$t_sons);
		}else{
			$cids[] = $cid;	
		}
	
		$page_size = 5;
		$this->db->where(array('state = '=>1));
		$total_nums = $this->db->where_in('cid',$cids)->count_all_results('content');
		$page = intval($this->uri->segment(3));
		//$page = $page > 0 ? $page : 1;
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$this->db->where(array('state = '=>1));
		$data['team_list'] = $this->db->where_in('cid',$cids)->join('category','category.cat_id = content.cid','left')->order_by('id','DESC')->limit($page_size,$page)->get('content')->result_array();
		//echo $this->db->last_query();
		$data['totalnums'] = $total_nums;		
		
		$this->pagelist($total_nums,$page_size,$cid);

		
		$data['ads'] = $this->db->where(array('ads_cid'=>7))->order_by('ads_id desc')->get('ads')->row_array();
		$data['mads'] = $this->db->where(array('ads_cid'=>16))->order_by('ads_id desc')->get('ads')->row_array();
		

		return $data;	
	}
	
	
	public function teamview(){
		$data = $this->headerSubMenu();
		
		$id = intval($this->uri->segment(2));
		$this->addCount($id);
		if($id > 0){
			//$query = $this->db->query('select a.*,b.cat_id,b.cat_name from luo_content a left join luo_category b on a.cid = b.cat_id where a.id = '.$id);
        	//$data['clist'] = $query->result_array();
			$row = $this->db->where(array('id'=>$id,'state '=>1))->join('category','category.cat_id=content.cid')->get('content')->row_array();
			
			if(!empty($row)){
				$sons = $this->db->where('cat_id',$row['cid'])->get('category')->row_array();
				if(!empty($sons)){
					$t_sons = $sons['cat_son'];
					//获取上级目录信息,主要用于SITEPATH
					if($sons['cat_fid'] > 0){
						$data['crow_fid'] = $this->db->where('cat_id',$sons['cat_fid'])->get('category')->result_array();
					}
					$data['crow'] = $sons;
				}
				$t_sons = $sons['cat_son'];
			}else{
				p('访问内容未通过审核，暂时无法访问');die();	
			}
			
			if(!empty($t_sons)){
				$cids = explode(",",$t_sons);
			}else{
				$cids[] = $row['cid'];	
			}
			if(!empty($t_sons)){
				$data['cat_list'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else if(empty($t_sons) && ($sons['cat_fid'] > 0)){
				$data['cat_list'] = $this->db->where_in('cat_fid',$sons['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else{
				$data['cat_list'] = array();	
			}
			$prev_array = array('state '=>1,'cid ' => $row['cid'], 'id <' => $id);
			$data['prev'] = $this->db->where($prev_array)->order_by('id DESC')->limit(1)->get('content')->row_array();
			$next_array = array('state '=>1,'cid ' => $row['cid'], 'id >' => $id);
			$data['next'] = $this->db->where($next_array)->order_by('id ASC')->limit(1)->get('content')->row_array();
			$data['cid']  = $row['cid'];
			$data['row']  = $row;
			
			//$data['article_list'] = $this->db->where('cid in (3,4,5)')->order_by('rank asc,id desc')->limit(4)->get('content')->result_array();	
			
			$data['ads'] = $this->db->where(array('ads_cid'=>7))->order_by('ads_id desc')->get('ads')->row_array();
			$data['mads'] = $this->db->where(array('ads_cid'=>16))->order_by('ads_id desc')->get('ads')->row_array();
			
		}else{
			return array();	
		}
		
		
		
		return $data;
	}
	
	
	function talent(){
		$data = $this->headerSubMenu();
		$cid = intval($this->uri->segment(2));	
		$cid = $cid > 0 ? $cid : 23;

		$data['clist'] = $this->db->where(array('cat_fid'=>8))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['row'] = $this->db->where(array('cid'=>$cid))->order_by('rank asc,id desc')->get('content')->row_array();
		$data['cid'] = $cid;
		
		$data['ads'] = $this->db->where(array('ads_cid'=>8))->order_by('ads_id desc')->get('ads')->row_array();
		$data['mads'] = $this->db->where(array('ads_cid'=>17))->order_by('ads_id desc')->get('ads')->row_array();
		

		return $data;	
	}
	
	function contact(){
		$data = $this->headerSubMenu();
		$cid = intval($this->uri->segment(2));	
		$cid = $cid > 0 ? $cid : 25;

		$data['clist'] = $this->db->where(array('cat_fid'=>9))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['row'] = $this->db->where(array('cid'=>$cid))->order_by('rank asc,id desc')->get('content')->row_array();
		$data['cid'] = $cid;
		
		$data['ads'] = $this->db->where(array('ads_cid'=>8))->order_by('ads_id desc')->get('ads')->row_array();
		
		$data['mads'] = $this->db->where(array('ads_cid'=>17))->order_by('ads_id desc')->get('ads')->row_array();
		

		return $data;	
	}
	
	function project(){
		$data = $this->headerSubMenu();
		$cid = intval($this->uri->segment(2));	
		$cid = $cid > 0 ? $cid : 10;
		
		$ads_cid = $cid == 10 ? 3 : 4;
				
		$data['project_banner'] = $this->db->where(array('ads_cid'=>$ads_cid))->order_by('ads_rank asc,ads_id asc')->limit(1)->get('ads')->result_array();
		$data['project_list'] = $this->db->where(array('cid'=>$cid))->order_by('rank asc,id asc')->get('content')->result_array();
		
		$ads_m_cid = $cid == 29 ? 11 : 12;
		$mcid = $cid;
		
		$data['project_m_banner'] = $this->db->where('ads_cid in (11,12)')->order_by('ads_rank asc,ads_id asc')->limit(5)->get('ads')->result_array();

		$data['project_m_list'] = $this->db->where(array('cid'=>$mcid))->order_by('rank asc,id asc')->get('content')->result_array();
		
		$data['cid'] = $cid;
		

		return $data;	
	}
	
	public function industry(){
		$data = $this->headerSubMenu();
		$cid = intval($this->uri->segment(2));	
		$cid = $cid > 0 ? $cid : 36;
		
		$data['clist'] = $this->db->where(array('cat_fid'=>4))->order_by('cat_rank asc,cat_id asc')->get('category')->result_array();
		$data['row'] = $this->db->where(array('cid'=>$cid))->order_by('rank asc,id desc')->get('content')->row_array();
		$data['cid'] = $cid;
		
		$data['ads'] = $this->db->where(array('ads_cid'=>4))->order_by('ads_id desc')->get('ads')->row_array();
		
		$data['mads'] = $this->db->where(array('ads_cid'=>13))->order_by('ads_id desc')->get('ads')->row_array();
		


		$data['cid'] = $cid;
		
		
		$data['ads'] = $this->db->where(array('ads_cid'=>5))->order_by('ads_id desc')->get('ads')->row_array();
		
		
		
		return $data;
	}
	

	
	
	public function article(){
		$data = $this->headerSubMenu();
		
		$cid = intval($this->uri->segment(2));
		$cid = $cid > 0 ? $cid : 1;
		$data['cid'] = $cid;
		
		$sons = $this->db->where('cat_id',$cid)->get('category')->row_array();
		$t_sons = $sons['cat_son'];
		if(!empty($t_sons)){
			$cids = explode(",",$t_sons);
		}else{
			$cids[] = $cid;	
		}
		//获取上级目录信息,主要用于SITEPATH
		if($sons['cat_fid'] > 0){
			$data['crow_fid'] = $this->db->where('cat_id',$sons['cat_fid'])->get('category')->result_array();
		}
		$data['row'] = $sons;

		
		
		if(!empty($t_sons)){
			$data['cat_list'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
		}else if(empty($t_sons) && ($sons['cat_fid'] > 0)){
			$data['cat_list'] = $this->db->where_in('cat_fid',$sons['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
		}else{
			$data['cat_list'] = array();	
		}	
		
		
		$page_size = 10;
		$this->db->where(array('state = '=>1));
		$total_nums = $this->db->where_in('cid',$cids)->count_all_results('content');
		$page = intval($this->uri->segment(3));
		//$page = $page > 0 ? $page : 1;
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$this->db->where(array('state = '=>1));
		$data['article_list'] = $this->db->where_in('cid',$cids)->join('category','category.cat_id = content.cid','left')->order_by('id','DESC')->limit($page_size,$page)->get('content')->result_array();
		//echo $this->db->last_query();
		$data['totalnums'] = $total_nums;
		
		
		$this->pagelist($total_nums,$page_size,$cid);
		
		
		return $data;
	}
	
	public function articleview(){
		$data = $this->headerSubMenu();
		
		$id = intval($this->uri->segment(2));
		$this->addCount($id);
		if($id > 0){
			//$query = $this->db->query('select a.*,b.cat_id,b.cat_name from luo_content a left join luo_category b on a.cid = b.cat_id where a.id = '.$id);
        	//$data['clist'] = $query->result_array();
			$row = $this->db->where(array('id'=>$id))->join('category','category.cat_id=content.cid','left')->get('content')->row_array();
			
			if(!empty($row)){
				$sons = $this->db->where('cat_id',$row['cid'])->get('category')->row_array();
				if(!empty($sons)){
					$t_sons = $sons['cat_son'];
					//获取上级目录信息,主要用于SITEPATH
					if($sons['cat_fid'] > 0){
						$data['crow_fid'] = $this->db->where('cat_id',$sons['cat_fid'])->get('category')->result_array();
					}
					$data['crow'] = $sons;
				}
				$t_sons = $sons['cat_son'];
			}else{
				p('访问内容未通过审核，暂时无法访问');die();	
			}
			
			if(!empty($t_sons)){
				$cids = explode(",",$t_sons);
			}else{
				$cids[] = $row['cid'];	
			}
			if(!empty($t_sons)){
				$data['cat_list'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else if(empty($t_sons) && ($sons['cat_fid'] > 0)){
				$data['cat_list'] = $this->db->where_in('cat_fid',$sons['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else{
				$data['cat_list'] = array();	
			}
			$prev_array = array('state '=>1,'cid ' => $row['cid'], 'id <' => $id);
			$data['prev'] = $this->db->where($prev_array)->order_by('id DESC')->limit(1)->get('content')->row_array();
			$next_array = array('state '=>1,'cid ' => $row['cid'], 'id >' => $id);
			$data['next'] = $this->db->where($next_array)->order_by('id ASC')->limit(1)->get('content')->row_array();
			$data['cid']  = $row['cid'];
			$data['row']  = $row;
			
			//$data['article_list'] = $this->db->where('cid in (3,4,5)')->order_by('rank asc,id desc')->limit(4)->get('content')->result_array();	
			
			$data['ads'] = $this->db->where(array('ads_cid'=>18))->order_by('ads_id desc')->get('ads')->row_array();
			$data['mads'] = $this->db->where(array('ads_cid'=>14))->order_by('ads_id desc')->get('ads')->row_array();
			
		}else{
			return array();	
		}
		
		
		
		return $data;
	}
	
	public function getcontent(){
		
		$id = intval($this->uri->segment(2));
		$this->addCount($id);
		if($id > 0){
			//$query = $this->db->query('select a.*,b.cat_id,b.cat_name from luo_content a left join luo_category b on a.cid = b.cat_id where a.id = '.$id);
        	//$data['clist'] = $query->result_array();
			$row = $this->db->where(array('id'=>$id,'state >= '=>3))->join('category','category.cat_id=content.cid')->get('content')->result_array();
			if(!empty($row)){
				$sons = $this->db->where('cat_id',$row[0]['cid'])->get('category')->result_array();
				$t_sons = $sons[0]['cat_son'];
				//获取上级目录信息,主要用于SITEPATH
				if($sons[0]['cat_fid'] > 0){
					$data['crow_fid'] = $this->db->where('cat_id',$sons[0]['cat_fid'])->get('category')->result_array();
				}
				$data['crow'] = $sons;	
			}
			$t_sons = $sons[0]['cat_son'];
			if(!empty($t_sons)){
				$cids = explode(",",$t_sons);
			}else{
				$cids[] = $row[0]['cid'];	
			}
			if(!empty($t_sons)){
				$data['cat_list'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else if(empty($t_sons) && ($sons[0]['cat_fid'] > 0)){
				$data['cat_list'] = $this->db->where_in('cat_fid',$sons[0]['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else{
				$data['cat_list'] = array();	
			}
			$this->db->where(array('state >= '=>3));
			$data['hotnews_list'] = $this->db->where_in('cid',$row[0]['cid'])->order_by('count DESC')->limit(10)->get('content')->result_array();
			$prev_array = array('state >= '=>3,'cid =' => $row[0]['cid'], 'id <' => $id);
			$data['prev'] = $this->db->where($prev_array)->order_by('id DESC')->limit(1)->get('content')->result_array();
			$next_array = array('state >= '=>3,'cid =' => $row[0]['cid'], 'id >' => $id);
			$data['next'] = $this->db->where($next_array)->order_by('id ASC')->limit(1)->get('content')->result_array();
			$data['cid']  = $row[0]['cid'];
			$data['row']  = $row[0];
		}else{
			return array();	
		}
		
		
		
		return $data;
	}
	
	
	public function addCount($id){
		$contentrow = $this->db->where(array('state '=>1,'id'=>$id))->get('content')->row_array();
		if(!empty($contentrow)){
			$count = $contentrow['count']+1;
			$data = array(
				'count' => $count
			);
			$this->db->update('content',$data,array('id'=>$id));
		}
	}
	
	
	public function products(){
		$data = $this->headerSubMenu();
		
		$cid = intval($this->uri->segment(2));
		$cid = $cid > 0 ? $cid : 2;

		$sons = $this->db->where('cat_id',$cid)->get('category')->row_array();
		$t_sons = $sons['cat_son'];
		if(!empty($t_sons)){
			$cids = explode(",",$t_sons);
		}else{
			$cids[] = $cid;	
		}
		//获取上级目录信息,主要用于SITEPATH
		if($sons['cat_fid'] > 0){
			$data['crow_fid'] = $this->db->where('cat_id',$sons['cat_fid'])->get('category')->row_array();
		}
		$data['crow'] = $sons;
		
		
		if(!empty($t_sons)){
			$data['clist'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
		}else if(empty($t_sons) && ($sons['cat_fid'] > 0)){
			$data['clist'] = $this->db->where_in('cat_fid',$sons['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
		}else{
			$data['clist'] = array();	
		}	
		


		$data['cid'] = $cid;
		
		
		$page_size = 20;
		$total_nums = $this->db->where_in('cid',$cids)->count_all_results('content');
		$page = intval($this->uri->segment(3));
		//$page = $page > 0 ? $page : 1;
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['product_list'] = $this->db->where_in('cid',$cids)->join('category','category.cat_id = content.cid','left')->order_by('rank asc,id desc')->limit($page_size,$page)->get('content')->result_array();

		//echo $this->db->last_query();
		
		$this->pagelist($total_nums,$page_size,$cid);
		
		
		return $data;
	}
	
	public function productsview(){
		$data = $this->headerSubMenu();
		$id = intval($this->uri->segment(2));
		$this->addCount($id);
		if($id > 0){
			$row = $this->db->where('id',$id)->join('category','category.cat_id=content.cid')->get('content')->row_array();
			if(!empty($row)){
				$sons = $this->db->where('cat_id',$row['cid'])->get('category')->row_array();
				$t_sons = $sons['cat_son'];
				//获取上级目录信息,主要用于SITEPATH
				if($sons['cat_fid'] > 0){
					$data['crow_fid'] = $this->db->where('cat_id',$sons['cat_fid'])->get('category')->row_array();
				}
				$data['crow'] = $sons;	
			}
			$t_sons = $sons['cat_son'];
			if(!empty($t_sons)){
				$cids = explode(",",$t_sons);
			}else{
				$cids[] = $row['cid'];	
			}
			if(!empty($t_sons)){
				$data['clist'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else if(empty($t_sons) && ($sons['cat_fid'] > 0)){
				$data['clist'] = $this->db->where_in('cat_fid',$sons['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else{
				$data['clist'] = array();	
			}
			
			$prev_array = array('state '=>1,'cid ' => $row['cid'], 'id <' => $id);
			$data['prev'] = $this->db->where($prev_array)->order_by('id DESC')->limit(1)->get('content')->row_array();
			$next_array = array('state '=>1,'cid ' => $row['cid'], 'id >' => $id);
			$data['next'] = $this->db->where($next_array)->order_by('id ASC')->limit(1)->get('content')->row_array();
			
			$data['cid']  = $row['cid'];
			$data['row']  = $row;
		}else{
			return array();	
		}		
		

		return $data;
	}
	
	public function support(){
		$data = $this->headerSubMenu();
		
		$cid = intval($this->uri->segment(2));
		$cid = $cid > 0 ? $cid : 3;
		$cid = 3;

		$sons = $this->db->where('cat_id',$cid)->get('category')->row_array();
		$t_sons = $sons['cat_son'];
		if(!empty($t_sons)){
			$cids = explode(",",$t_sons);
		}else{
			$cids[] = $cid;	
		}
		
		foreach($cids as $c){
			$key_name = 'support'.$c.'_list';
			$data[$key_name] = $this->db->where(array('cid'=>$c))->limit(10)->get('content')->result_array();
		}

		
		return $data;
	}
	
	public function cases(){
		$data = $this->headerSubMenu();
		
		$cid = intval($this->uri->segment(2));
		$cid = $cid > 0 ? $cid : 4;
		$sons = $this->db->where('cat_id',$cid)->get('category')->row_array();
		$t_sons = $sons['cat_son'];
		if(!empty($t_sons)){
			$cids = explode(",",$t_sons);
		}else{
			$cids[] = $cid;	
		}
		//获取上级目录信息,主要用于SITEPATH
		if($sons['cat_fid'] > 0){
			$data['crow_fid'] = $this->db->where('cat_id',$sons['cat_fid'])->get('category')->row_array();
		}
		$data['crow'] = $sons;
		
		
		if(!empty($t_sons)){
			$data['clist'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
		}else if(empty($t_sons) && ($sons['cat_fid'] > 0)){
			$data['clist'] = $this->db->where_in('cat_fid',$sons['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
		}else{
			$data['clist'] = array();	
		}	
		


		$data['cid'] = $cid;
		
		$page_size = 15;
		$total_nums = $this->db->where_in('cid',$cids)->count_all_results('content');
		$page = intval($this->uri->segment(3));
		//$page = $page > 0 ? $page : 1;
		
		$offset = ($page-1)*$page_size;
		$offset = $offset > 0 ? $offset : 1;
		$data['product_list'] = $this->db->where_in('cid',$cids)->order_by('rank asc,id desc')->limit($page_size,$page)->get('content')->result_array();
		
		//echo $this->db->last_query();
		
		$this->pagelist($total_nums,$page_size,$cid);
		
		
		return $data;
	}
	
	public function casesview(){
		$data = $this->headerSubMenu();
		$id = intval($this->uri->segment(2));
		$this->addCount($id);
		if($id > 0){
			$row = $this->db->where('id',$id)->join('category','category.cat_id=content.cid')->get('content')->row_array();
			if(!empty($row)){
				$sons = $this->db->where('cat_id',$row['cid'])->get('category')->row_array();
				$t_sons = $sons['cat_son'];
				//获取上级目录信息,主要用于SITEPATH
				if($sons['cat_fid'] > 0){
					$data['crow_fid'] = $this->db->where('cat_id',$sons['cat_fid'])->get('category')->row_array();
				}
				$data['crow'] = $sons;	
			}
			$t_sons = $sons['cat_son'];
			if(!empty($t_sons)){
				$cids = explode(",",$t_sons);
			}else{
				$cids[] = $row['cid'];	
			}
			if(!empty($t_sons)){
				$data['clist'] = $this->db->where_in('cat_id',$cids)->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else if(empty($t_sons) && ($sons['cat_fid'] > 0)){
				$data['clist'] = $this->db->where_in('cat_fid',$sons['cat_fid'])->order_by('cat_rank DESC,cat_id ASC')->get('category')->result_array();	
			}else{
				$data['clist'] = array();	
			}
			
			$prev_array = array('state '=>1,'cid ' => $row['cid'], 'id <' => $id);
			$data['prev'] = $this->db->where($prev_array)->order_by('id DESC')->limit(1)->get('content')->row_array();
			$next_array = array('state '=>1,'cid ' => $row['cid'], 'id >' => $id);
			$data['next'] = $this->db->where($next_array)->order_by('id ASC')->limit(1)->get('content')->row_array();
			
			$data['cid']  = $row['cid'];
			$data['row']  = $row;
		}else{
			return array();	
		}		
		
		
		return $data;
	}
	
	
	
	public function pagelist($total_nums,$page_size,$cid){
		$this->load->library('pagination');
	    $config = array(
				'base_url'       => site_url().'/'.$this->uri->segment(1).'/'.$cid,
				'total_rows'     => $total_nums,
				'per_page'       => $page_size,
				'num_links'      => 5,
				'first_link'     => false,
				'last_link'      => false,
				'uri_segment'    => 3,
				'full_tag_open'  => "<ul class='pagination'>",//关闭标签
				'full_tag_close' => '</ul>',
				'num_tag_open'   => '<li>',	//数字html
				'num_tag_close'  => '</li>',	//当前页html
				'cur_tag_open'   => "<li class='active'><a href='javascript:void(0),'>",
				'cur_tag_close'  => "</a></li>",
				'next_tag_open'  => '<li>',	//上一页下一页html
				'next_tag_close' => '</li>',
				'prev_tag_open'  => '<li>',
				'prev_tag_close' => '</li>',
				'prev_link'      => '<span>上页</span>',
				'next_link'      => '<span>下页</span>'
	   );
	    
	   
	    $this->pagination->initialize($config);
	}
	
	
	public function getPageFootCats(){
		$data['about_cats'] = $this->getcats(1);
		$data['news_cats'] = $this->getcats(2);
		$data['products_cats'] = $this->getcats(3);
		$data['case_cats'] = $this->getcats(4);
		return $data;	
	}
	
	public function getcats($fid=0){
		
		$query = $this->db->query('select * from luo_category where cat_fid = '.$fid);
        return $query->result_array();	
	}
	
    public function get_news($slug = FALSE)
    {
        if ($slug === FALSE)
        {
            $query = $this->db->get('news');
            return $query->result_array();
        }
    
        $query = $this->db->get_where('news', array('slug' => $slug));
        return $query->row_array();
    }
	
	//搜索
   public function search_content($cid = FALSE,$title = FALSE,$num,$offset)
	{
        $this->db->order_by('id', 'DESC');
        //$this->db->join('category', 'category.cat_id = content.cid','left');
		 //if($cid){
		  //  $this->db->where('content.cid',$cid);
		 //}
		 if($title){
			 $this->db->like('content.title',$title);	 
		 }
		$query = $this->db->get('content',$num, $offset);
		return $query->result_array();
		
	} 
	
	//搜索条件查询条数
     public function search_content_nums($cid = FALSE,$title = FALSE)
	{

        //$this->db->join('category', 'category.cat_id = content.cid','left');
//		 if($cid){
//		    $this->db->where('content.cid',$cid);
//		 }
		 if($title){
			 $this->db->like('content.title',$title);	 
		 }
		return $this->db->count_all_results('content');
	
		
	}
}