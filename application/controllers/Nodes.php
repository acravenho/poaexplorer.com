<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nodes extends CI_Controller {
	public function __construct()
	{
       	parent::__construct();
        $this->load->model('Api_model');
   	}
   	
	public function index()
	{
		$data['nodes'] = $this->Api_model->get_nodes();
		$data['title'] = 'POA Network Explorer - Nodes';
		$data['main'] = 'nodes/view';
		$this->load->view('template/file', $data);
	}
}
