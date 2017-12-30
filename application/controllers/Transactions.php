<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {
	
	public function __construct()
	{
       	parent::__construct();
        $this->load->model('Api_model');
   	}
	
	public function index()
	{
		
		$data['transactions'] = $this->Api_model->get_trans();
		
		$data['title'] = 'POA Network Explorer - Recent Blocks';
		$data['main'] = 'transactions/view';
		$this->load->view('template/file', $data);
	}
	
	
}
