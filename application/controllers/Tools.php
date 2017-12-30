<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {

	public function verify()
	{
		$data['title'] = 'POA Network Explorer - Verify Smart Contract';
		$data['main'] = 'tools/verify';
		$this->load->view('template/file', $data);
	}
	
	public function api()
	{
		$data['title'] = 'POA Network Explorer - Api';
		$data['main'] = 'tools/api';
		$this->load->view('template/file', $data);
	}
	
	public function opcode()
	{
		$data['title'] = 'POA Network Explorer - Byte Code to OPCode';
		$data['main'] = 'tools/opcode';
		$this->load->view('template/file', $data);
	}
}
