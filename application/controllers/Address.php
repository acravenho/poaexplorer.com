<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends CI_Controller {
	
	public function _remap( $method )
    {
		$this->load->model('Api_model');
		$this->load->library('pagination');
		
		$data = array();
		$data['address'] = $this->uri->segment(2);
		if(empty($data['address'])) {
			show_404();
		}
		
		$data['wallet'] = $this->Api_model->get_wallet($data['address']);
		
        $limit_per_page = 20;
        $start_index = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
        $total_records = $this->Api_model->get_total_transactions($data['address']);
        
        $data['total_transactions'] = $total_records;
		
		
		if ($total_records > 0) 
        {
            $data["transactions"] = $this->Api_model->get_transactions_by_address($limit_per_page, $start_index);
             
            $config['base_url'] = base_url() . 'address/'.$data['address'];
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
		
		
		$data['internal_transactions'] = $this->Api_model->get_internal_transactions_account();
		
		
		//$data['transactions'] = $this->Api_model->get_transactions_by_address();
		$data['contract']     = $this->Api_model->is_contract($data['address']);
		if($data['contract'] > 0)
		{
			$data['contract_creation'] = $this->Api_model->contract_creation($data['address']);
			$data['verified_contract'] = $this->Api_model->verified_contracts($data['address']);
		}
		
		$data['scripts'] = '<script>addressBalance("'.$data["address"].'");  transactionCount("'.$data["address"].'"); '.($data['contract'] == 1 ? 'getContractCode("'.$data['address'].'");' : '').' $("#myTabs a").click(function (e) {e.preventDefault();$(this).tab("show");});</script>';
		$data['title'] = 'POA Network Explorer - '.$data['address'] ;
		$data['main'] = 'search/address';
		$this->load->view('template/file', $data);
	}
	
	public function search()
	{
		$this->load->model('Api_model');
		$this->load->library('pagination');
		
		$data = array();
		$data['address'] = $this->uri->segment(3);
		if(empty($data['address'])) {
			show_404();
		}
		
		$data['wallet'] = $this->Api_model->get_wallet($data['address']);
		
        $limit_per_page = 20;
        $start_index = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);
        $total_records = $this->Api_model->get_total_transactions($data['address']);
        
        $data['total_transactions'] = $total_records;
		
		
		if ($total_records > 0) 
        {
            $data["transactions"] = $this->Api_model->get_transactions_by_address($limit_per_page, $start_index);
             
            $config['base_url'] = base_url() . 'address/search/'.$data['address'];
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 4;
            
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
		
		
		$data['internal_transactions'] = $this->Api_model->get_internal_transactions_account();
		
		
		//$data['transactions'] = $this->Api_model->get_transactions_by_address();
		$data['contract']     = $this->Api_model->is_contract($data['address']);
		if($data['contract'] > 0)
		{
			$data['contract_creation'] = $this->Api_model->contract_creation($data['address']);
			$data['verified_contract'] = $this->Api_model->verified_contracts($data['address']);
		}
		
		$data['scripts'] = '<script>addressBalance("'.$data["address"].'");  transactionCount("'.$data["address"].'"); '.($data['contract'] == 1 ? 'getContractCode("'.$data['address'].'");' : '').' $("#myTabs a").click(function (e) {e.preventDefault();$(this).tab("show");});</script>';
		$data['title'] = 'POA Network Explorer - '.$data['address'] ;
		$data['main'] = 'search/address';
		$this->load->view('template/file', $data);
	}
	
	
}
