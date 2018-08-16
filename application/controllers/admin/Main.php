<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {	
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('calendar');
    }
	
	/**
	 * top
	 */
	public function index()
	{		
	
		//p($data);die();
		
		
		$data['content_list'] = $this->db->order_by('id desc')->limit(10)->get('content')->result_array();
		
		$this->load->view(admin_url().'main',$data);
	}
}
