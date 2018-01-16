<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
       	parent::__construct();
        $this->load->model('Api_model');
   	}
	public function index()
	{
		$data['totalBalance'] = $this->Api_model->get_total_balances();
		$data['totalWallets'] = $this->Api_model->total_wallets();
		$data['transactions'] = $this->Api_model->get_trans(5);
		$data['blocks']       = $this->Api_model->get_main_blocks(5);
		
		$data['scripts'] = '<script>getBlockNumber();  </script>';
		$data['title'] = 'POA Network (POA) Explorer';
		$data['main'] = 'homepage';
		$this->load->view('template/file', $data);
	}
}
