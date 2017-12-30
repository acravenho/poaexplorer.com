<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }
    
	public function index()
	{
		$string = trim($this->input->post('hash'));
		$count  = strlen($string);
		if(strlen($string) > 42) {
			redirect('/txid/search/'.$string, 'refresh');
		} elseif(strlen($string) == 42) {
			redirect('/address/search/'.$string, 'refresh');
		} elseif(is_numeric($string)) {
			redirect('/blocks/block/'.$string, 'refresh');
		} else {
			redirect('/', 'refresh');
		}
		
	}
}
