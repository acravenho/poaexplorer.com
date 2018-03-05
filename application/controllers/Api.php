<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
       	parent::__construct();
        $this->load->model('Api_model');
   	}
	
	public function getblocks() {
		
		$data['last'] = $this->Api_model->last_block();
		$this->load->view('api/blocks', $data);
		
	}
	
	public function network() {
		$data['stats'] = $this->Api_model->network_stats();
		echo json_encode($data['stats']);
	}
	
	public function balance() {
		$this->load->helper('web3');
		$data['balance'] = $this->Api_model->balance();
		echo json_encode($data['balance']);
	}
	
	
	public function post() {
		$this->Api_model->post_data();
	}
	
	public function addblock() {
		$this->Api_model->addblock();
	}
	
	public function lastblock() {
		echo $this->Api_model->get_last_block_found();
	}
}
