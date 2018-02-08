<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validators extends CI_Controller {

	public function address()
	{
		$this->load->model('Api_model');
		$this->load->library('pagination');
		
		$data['address'] = $this->uri->segment(3);
		
		if(empty($data['address'])) {
			show_404();
		} 
		
		$first = substr($data['address'], 0, 2);
		
		if($first !== '0x') {
			redirect(base_url().'/validators/address/0x'.$data['address']);
		}
		
		$data['validator'] = $this->Api_model->get_validator();
		
		if(empty($data['validator']))
		{
			redirect(base_url().'/search/address/'.$data['address']);
		}
		
		$limit_per_page = 20;
        $start_index = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);
        $total_records = $this->Api_model->get_total_blocks_validator($data['address']);
        
        $data['total_blocks'] = $total_records;
        
        if ($total_records > 0) 
        {
            $data["blocks"] = $this->Api_model->get_blocks_by_validator($limit_per_page, $start_index);
            
            $config['base_url'] = base_url() . 'validators/address/'.$data['address'];
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
		

		
		$data['title'] = 'POA Network Explorer - Validator: '.$data['address'] ;
		$data['main'] = 'validators/address';
		$this->load->view('template/file', $data);
	}
}
