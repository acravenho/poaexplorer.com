<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Richlist extends CI_Controller {
	public function __construct()
	{
       	parent::__construct();
        $this->load->model('Api_model');
   	}
	public function index()
	{
		$data['rich'] = $this->Api_model->get_richlist();
		
		$data['title'] = 'POA Network Rich List';
		$data['main'] = 'richlist/view';
		$this->load->view('template/file', $data);
	}
}
