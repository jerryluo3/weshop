<?php

class Uploadmyfile extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {
		
		$picid = $this->uri->segment(3);
		$picid = empty($picid) ? 'picture' : $picid;
		$data['picid'] = $picid;
        $this->load->view('upload_form', array('error' => ' ' ,'picid' => $picid ));
    }

    public function do_upload()
    {
        $config['upload_path']      = './data/uploads/';
        $config['allowed_types']    = 'gif|jpg|png';
		$config['file_name']    = time();
        $config['max_size']     = 10*1024;
        $config['max_width']        = 3000;
        $config['max_height']       = 3000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
			
			$picid = $this->input->post('inputid');
			
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('upload_form', $error);
			
        }
        else
        {
			$upload_data = $this->upload->data();
			$uploadfile = "data/uploads/".$upload_data['orig_name'];
			$config['image_library'] = 'gd2';
			$config['source_image'] = $uploadfile;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']     = 350;
			$config['height']   = 200;
			$this->load->library('image_lib', $config);
			
			
			//$this->image_lib->resize();	
			if ( ! $this->image_lib->resize()){
				echo $this->image_lib->display_errors();
			}
            $data = array('upload_data' => $this->upload->data(),'picid' => $this->input->post('inputid'));

            $this->load->view('upload_success', $data);
        }
    }
	
}
?>