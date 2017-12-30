<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends CI_Controller {

	public function search()
	{
		$this->load->model('Api_model');
		
		
		
		$data['address'] = $this->uri->segment(3);
		if(empty($data['address'])) {
			show_404();
		}
		
		$data['transactions'] = $this->Api_model->get_transactions_by_address();
		
		$data['scripts'] = '<script>addressBalance("'.$data["address"].'");  transactionCount("'.$data["address"].'"); </script>';
		$data['title'] = 'POA Network Explorer - '.$data['address'] ;
		$data['main'] = 'search/address';
		$this->load->view('template/file', $data);
	}
	
	public function search2()
	{
		$this->load->model('Api_model');
		
		
		
		$data['address'] = $this->uri->segment(3);
		if(empty($data['address'])) {
			show_404();
		}
		
		$data['transactions'] = $this->Api_model->get_transactions_by_address();
		
		$data['scripts'] = '<script>addressBalance("'.$data["address"].'");  transactionCount("'.$data["address"].'"); </script>';
		$data['title'] = 'POA Network Explorer - '.$data['address'] ;
		$data['main'] = 'search/address2';
		$this->load->view('template/file', $data);
	}
}
