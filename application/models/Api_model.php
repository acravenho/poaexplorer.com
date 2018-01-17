<?php
	class Api_model extends CI_Model {

        public $title;
        public $content;
        public $date;

        public function __construct()
        {
                parent::__construct();
				$this->load->database();
        }
        
        public function get_transactions() {
	        $sql = $this->db->get('transactions')->limit(10);
	        if($sql->num_rows() > 0) {
		        return $sql->result();
	        } else {
		        return false;
	        }
        }
        
        public function last_block() {
	        $this->db->order_by('blocknum', 'DESC');
	        $this->db->limit(1);
	        $sql = $this->db->get('blocks');
	        if($sql->num_rows() > 0) {
		        $row = $sql->row();
		        return $row->blocknum;
	        } else {
		        return 0;
	        }
	        
        }
        
        
        public function post_data() {
	        $txid      = $this->input->post('txid');
	        $blockHash = $this->input->post('blockHash');
	        $blockNumber = $this->input->post('blockNumber');
	        $to = $this->input->post('to');
	        $from = $this->input->post('tfrom');
	        $value = $this->input->post('transactionValue');
	        $gas = $this->input->post('gas');
	        $gasPrice = $this->input->post('gasPrice');
	        $nonce = $this->input->post('nonce');
	        
	        $array = array(
		        'txid' => $txid,
		        'blockHash' => $blockHash,
		        'blockNumber' => $blockNumber,
		        'to' => $to,
		        'from' => $from,
		        'transactionValue' => $value,
		        'gas' => $gas,
		        'gasPrice' => $gasPrice,
		        'nonce' => $nonce
	        );
	        
	        
	        $this->db->where('txid', $txid);
	        $sql = $this->db->get('transactions');
	        if($sql->num_rows() == 0) {
		        $this->db->insert('transactions', $array);
	        }
	        
	        
	        
	        
        }
        
        public function get_trans($limit = 100) {
	        $this->db->order_by('tid', 'DESC');
	        $this->db->limit($limit);
	        
	        $sql = $this->db->get('transactions');
	        if($sql->num_rows() > 0) {
		        $results = array();
		        foreach($sql->result() as $row) {
			        $row->to_contract = $this->is_contract($row->to);
			        $row->from_contract = $this->is_contract($row->from);
			        $results[] = $row;
		        }
		        return $results;
	        } else {
		        return false;
	        }
        }
        
        public function get_main_blocks($limit = 5)
        {
	        $this->db->order_by('id', 'DESC');
	        $this->db->limit($limit);
	        
	        $sql = $this->db->get('blocks');
	        if($sql->num_rows() > 0) {
		        return $sql->result();
	        } else {
		        return false;
	        }
        }
        
        public function is_contract($wallet) {
	        $sql = $this->db->get_where('wallets', array('wallet' => $wallet));
	        if($sql->num_rows() > 0) {
		        $row = $sql->row();
		        return $row->contract;
	        }
        }
        
        public function contract_creation($address)
        {
	        $sql = $this->db->get_where('transactions', array('creates' => $address));
	        if($sql->num_rows() > 0)
	        {
		        return $sql->row();
	        }
	        return false;
        }
        
        public function get_last_block_found() {
	        $this->db->order_by('blocknum', 'DESC');
	        $this->db->limit(1);
	        $sql = $this->db->get('blocks');
	        if($sql->num_rows() > 0) {
		        $row = $sql->row();
		        return $row->blocknum;
	        }
        }
        
        public function get_blocks() {
	        $this->db->select('blockNumber,txid, COUNT(tid) as trans');
	        $this->db->group_by('blockNumber');
	        $this->db->order_by('blockNumber', 'DESC');
	        $this->db->limit(100);
	        
	        $sql = $this->db->get('transactions');
	        if($sql->num_rows() > 0) {
		        return $sql->result();
	        } else {
		        return false;
	        }
        }
        
        
        public function get_transactions_by_address() {
	        $address = $this->uri->segment(3);
	        $this->db->where('to', $address);
	        $this->db->or_where('from', $address);
	        $this->db->or_where('creates', $address);
	        $this->db->order_by('blockNumber', 'DESC');
	        $sql = $this->db->get('transactions');
	        if($sql->num_rows() > 0) {
		        $results = array();
		        foreach($sql->result() as $row) {
			        $row->to_contract = $this->is_contract($row->to);
			        $row->from_contract = $this->is_contract($row->from);
			        $results[] = $row;
		        }
		        return $results;
	        } else {
		        return false;
	        }
        }
        
        public function get_total_balances() {
	        $this->db->select('SUM(balance) AS totalBalance', FALSE);
	        $sql = $this->db->get('wallets');
	        if($sql->num_rows() > 0) {
		        return $sql->row();
	        }
        }
        
        public function get_richlist() {
	        $this->db->order_by('balance', 'DESC');
	        $this->db->limit(100);
	        $sql = $this->db->get('wallets');
	        if($sql->num_rows() > 0) {
		        return $sql->result();
	        }
	        return false;
        }
        
        public function total_wallets() {
	        $this->db->where('balance >', '0');
	        $sql = $this->db->get('wallets');
	        return $sql->num_rows();
	       
        }
        
        
        public function addblock() {
	        $block = $this->input->post('block');
	        $this->db->insert('blocks', array('blocknum' => $block));
        }
        
        public function get_nodes() {
	        $this->db->group_by('author');
        }

}