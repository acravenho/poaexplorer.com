<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {
	public function __construct()
	{
       	parent::__construct();
        $this->load->model('Api_model');
        $this->load->library('pagination');
   	}
	public function index()
	{
		$limit_per_page = 50;
        $start_index = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
        $total_records = $this->Api_model->get_total_accounts();        

        
        $data['total_accounts'] = $total_records;
        $data['total_balance']  = $this->Api_model->get_total_wallet_balances();
		
		
		if ($total_records > 0) 
        {
            $data["rich"] = $this->Api_model->get_richlist($limit_per_page, $start_index);
             
            $config['base_url'] = base_url() . 'accounts/index';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 3;
            
			$config['full_tag_open'] = '<ul class="pagination" style="float:right; margin-top:0;">';
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
             
            $this->pagination->initialize($config);
             
            $data["links"] = $this->pagination->create_links();
        }
		

		
		
		$data['title'] = 'POA Network Accounts List';
		$data['main'] = 'accounts/view';
		$this->load->view('template/file', $data);
	}
}
