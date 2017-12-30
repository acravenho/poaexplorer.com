<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Txid extends CI_Controller {

	public function search()
	{
		$data['txid'] = $this->uri->segment(3);
		
		if(empty($data['txid'])) {
			show_404();
		} 
		
		$data['scripts'] = '<script>getTransaction("'.$data["txid"].'"); </script>';
		$data['title'] = 'POA Network Explorer - TXID: '.$data['txid'] ;
		$data['main'] = 'search/txid';
		$this->load->view('template/file', $data);
	}
}
