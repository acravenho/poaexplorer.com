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
		$this->load->library('pagination');
		
		$data = array();
        $limit_per_page = 30;
        $start_index = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
        $total_records = $this->Api_model->get_total_transactions();
        
        if ($total_records > 0) 
        {
            $data["transactions"] = $this->Api_model->get_trans($limit_per_page, $start_index);
             
            $config['base_url'] = base_url() . 'transactions/index';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 3;
            
			$config['full_tag_open'] = '<ul class="pagination" style="float:right;">';
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
		
		$data['title'] = 'POA Network Explorer - Recent Transactions';
		$data['main'] = 'transactions/view';
		$this->load->view('template/file', $data);
	}
	
	
}
