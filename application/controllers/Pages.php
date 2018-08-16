<?php
class Pages extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->helper('myfun');
		$this->load->model('home_model');
		$this->load->library('form_validation');
    }
	
    public function view($page = 'index')
	{
		//$this->output->cache(60);
		if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
		
		//$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
//		$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
//		if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap') || $this->uri->segment(1) == 'wap'){
//			$Loaction = 'wap';
//		}else{
//			$Loaction = 'pages';
//		}
		$Loaction = 'pages';
		$data = $this->getPageType($page);
		$data['sysconfig'] = $this->getSysConfigs();
		$this->load->view($Loaction.'/'.$page, $data);
	}
	
	public function getPageType($page='home'){
		switch($page){
			case 'home':return $this->pageHome();break;
			case 'about':return $this->pageAbout();break;
			case 'query':return $this->pageQuery();break;
			case 'project':return $this->pageProject();break;
			case 'industry':return $this->pageIndustry();break;
			case 'culture':return $this->pageCulture();break;
			case 'team':return $this->pageTeam();break;
			case 'teamview':return $this->pageTeamview();break;
			case 'article':return $this->pageArticle();break;
			case 'articleview':return $this->pageArticleview();break;
			case 'talent':return $this->pageTalent();break;
			case 'contact':return $this->pageContact();break;
			case 'search':return $this->pageSearch();break;	
			default:return $this->pageHome();break;
		}	
	}
	
	
	public function test(){
		
	}
	
	
	public function pageHome(){
		$data = $this->home_model->home();	
		return $data;
	}
	
	public function pageAbout(){
		$data = $this->home_model->about();	
		return $data;
	}
	
	public function pageQuery(){
		$data = $this->home_model->query();
		return $data;
	}
	
	public function pageIndustry(){
		$data = $this->home_model->industry();	
		return $data;
	}
	
	public function pageCulture(){
		$data = $this->home_model->culture();	
		return $data;
	}
	
	public function pageTeam(){
		$data = $this->home_model->team();	
		return $data;
	}
	public function pageTeamview(){
		$data = $this->home_model->teamview();	
		return $data;
	}
	
	
	public function pageArticle(){
		$data = $this->home_model->article();	
		return $data;
	}
	
	public function pageArticleview(){
		$data = $this->home_model->articleview();	
		
		return $data;
	}
	
	public function pageTalent(){
		$data = $this->home_model->talent();
		return $data;
	}
	
	public function pageContact(){
		$data = $this->home_model->contact();
		return $data;
	}
	
	
	public function search()
	{   
	    $this->load->library('pagination');
	    $config = array(
				'base_url'       => site_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2),
				'total_rows'     => $this->home_model->search_content_nums($this->input->post('cid'),$this->input->post('keys')),
				'per_page'       => 20,
				'num_links'      => 1,
				'first_link'     => '<span>首页</span>',
				'last_link'      => '<span>末页</span>',
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
		
	    $data=array(
				 'article_list'       => $this->home_model->search_content($this->input->post('cid'),$this->input->post('keys'),$config['per_page'],$this->uri->segment(3)),
				 'article_nums'  => $this->home_model->search_content_nums($this->input->post('cid'),$this->input->post('title')),
				// 'catesoption'    => $this->category_model->getcats('',1)
	     ); 

	   $this->load->view('pages/search',$data);

	}
	
	//所有图片和忝缩略图
	public function getALLthumb($id=''){
		if(!empty($id)){
			if(is_array($id)){
				$this->db->where_in('id',$id);	
			}else{
				$this->db->where(array('id'=>$id));	
			}
		}
		//$this->db->where(array('id <'=>83));
		$clist = $this->db->order_by('id asc')->get('content')->result_array();
		
		if(!empty($clist)){
			foreach($clist as $list){
				if(!empty($list['picture'])){
					$config = array();
					$thumb = substr($list['picture'],0,strlen($list['picture'])-4).'_thumb'.substr($list['picture'],-4);
					if(!file_exists($thumb)){
	
						$config['image_library'] = 'gd2';
						$config['source_image'] = $list['picture'];
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = TRUE;
						$config['width']     = 250;
						$config['height']   = 180;
						$this->load->library('image_lib', $config);
						//$this->image_lib->resize();	
						if ( ! $this->image_lib->resize()){
							echo $this->image_lib->display_errors();
						}else{
							echo $thumb."<br>";	
						}	
					}	
				}
			}
		}
	}
	
	
	
	//调用系统设置
	public function getSysConfigs(){
		 $this->load->database();
		 $query = $this->db->get('luo_configs');
		 return $query->result_array();
	}
}