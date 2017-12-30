<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contracts extends CI_Controller {

	public function index()
	{
		
		$data['scripts'] = '<script>contractInit(); </script>';
		$data['title'] = 'POA Network Explorer - Contracts';
		$data['main'] = 'contracts/view';
		$this->load->view('template/file', $data);
	}
}
