<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blocks extends CI_Controller {
	public function __construct()
	{
       	parent::__construct();
        $this->load->model('Api_model');
   	}
   	
	public function index()
	{
		$data['blocks'] = $this->Api_model->get_blocks();
		
		$data['title'] = 'POA Network Explorer - Recent Blocks';
		$data['main'] = 'blocks/view';
		$this->load->view('template/file', $data);
	}
	
	public function block() {
		$data['block'] = $this->uri->segment(3);
		
		if(empty($data['block'])) {
			show_404();
		}
		
		$data['scripts'] = '<script>getBlockInfo('.$data['block'].'); </script>';
		$data['title'] = 'POA Network Explorer - Block '.$data['block'];
		$data['main'] = 'blocks/block';
		$this->load->view('template/file', $data);
		
	}
}
