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
        
        public function get_trans($limit = 100, $start_index = 0) {
	        $this->db->order_by('tid', 'DESC');
	        $this->db->limit($limit, $start_index);
	        
	        $sql = $this->db->get('transactions');
	        if($sql->num_rows() > 0) {
		        $results = array();
		        foreach($sql->result() as $row) {
			        $row->to_contract = $this->is_contract($row->to);
			        $row->from_contract = $this->is_contract($row->from);
			        $row->to_name = $this->wallet_name($row->to);
			        $row->from_name = $this->wallet_name($row->from);
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
		        $rows = array();
		        foreach($sql->result() as $row)
		        {
			        $validator = $this->get_validator($row->validator);
			        $row->name = $validator->name;
			        $rows[] = $row;
		        }
		        return $rows;
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
        
        public function wallet_name($wallet) {
	        $sql = $this->db->get_where('wallets', array('wallet' => $wallet));
	        if($sql->num_rows() > 0) {
		        $row = $sql->row();
		        return $row->name;
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
        
        
        public function get_transactions_by_address($limit = 20, $start = 0) {
	        
	        $address = $this->uri->segment(3);
	        $this->db->limit($limit, $start);
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
			        $row->to_name = $this->wallet_name($row->to);
			        $row->from_name = $this->wallet_name($row->from);
			        $results[] = $row;
		        }
		        return $results;
	        } else {
		        return false;
	        }
        }
        
         public function get_total_balances($exclude = true) {
	       $this->db->select('SUM(balance) AS totalBalance', FALSE);
	       if($exclude == true)
	       {
	       	$this->db->where('exclude', 0);
	       }
	       
	       $sql = $this->db->get('wallets');
	       if($sql->num_rows() > 0) {
	       return $sql->row();
	       }
        }
        
        public function get_richlist($limit = 20, $start = 0) {
	        $this->db->order_by('balance', 'DESC');
	        $this->db->limit($limit, $start);
	        $sql = $this->db->get('wallets');
	        if($sql->num_rows() > 0) {
		        return $sql->result();
	        }
	        return false;
        }
        
        public function get_wallet($wallet) {
	        $this->db->where('wallet', $wallet);
	        $sql = $this->db->get('wallets');
	        if($sql->num_rows() > 0) {
		        return $sql->row();
	        }
	        return false;
        }
        
        public function total_wallets() {
	        $this->db->where('balance >', '0');
	        $sql = $this->db->get('wallets');
	        return $sql->num_rows();
	       
        }
        public function total_contracts() {
	        $this->db->where('contract', '1');
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
        
        public function get_total_wallet_balances()
        {
	        $this->db->select_sum('balance');
	        $this->db->where('balance >', 0);
	        $sql = $this->db->get('wallets');
	        $row = $sql->row();
	        
	        return $row->balance;
        }
        
        
        public function get_total_accounts()
        {
	        $this->db->select('wid');
	        $this->db->where('balance >', 0);
	        $sql = $this->db->get('wallets');
	        return $sql->num_rows();
	        
	    }
        
        public function get_total_transactions($address = NULL)
        {
	        if($address == NULL)
	        {
		        $this->db->select('tid');
		        $q = $this->db->get('transactions');
		        return $q->num_rows();
		    }
		    else
		    {
			    $this->db->select('tid');
			    $this->db->where('to', $address);
				$this->db->or_where('from', $address);
				$this->db->or_where('creates', $address);
				$sql = $this->db->get('transactions');
				return $sql->num_rows();
		    }
        }
        
        
        public function get_total_blocks_validator($address = NULL) 
        {
	        if(empty($address)) {
		        return false;
	        }
	        
	        $this->db->from('blocks');
	        $this->db->where('validator', $address);
	        $sql = $this->db->get();
	        return $sql->num_rows();
        }
        
        public function get_blocks_by_validator($limit = 20, $start = 0) 
        {
	        $address = $this->uri->segment(3);
	        $this->db->order_by('blocknum', 'DESC');
	        $this->db->limit($limit, $start);
	        $this->db->where('validator', $address);
	        $sql = $this->db->get('blocks');
	        if($sql->num_rows() > 0) {
		        return $sql->result();
	        }
	        return false;
        }
        
        public function get_validator($address = NULL)
        {
	        $address = (!empty($address) ? $address : $this->uri->segment(3));
	        $this->db->where('address', $address);
	        $sql = $this->db->get('validators');
	        if($sql->num_rows() > 0) {
		        return $sql->row();
	        }
	        return false;
        }
        
        public function get_transaction_by_hash($hash)
        {
	        $this->db->where('txid', $hash);
	        $sql = $this->db->get('transactions');
	        if($sql->num_rows() > 0)
	        {
		        return $sql->row();
	        }
	        return false;
        }
        
        
        public function get_internal_transactions_account()
        {
	        $address = $this->uri->segment(3);
	        $this->db->where('to', $address);
	        $this->db->or_where('from', $address);
	        $this->db->or_where('address', $address);
	        $this->db->order_by('id', 'DESC');
	        $sql = $this->db->get('internal_transactions');
	        if($sql->num_rows() > 0)
	        {
		        $results = array();
		        foreach($sql->result() as $row) {
			        $trans = $this->get_transaction_by_hash($row->parent);
			        $value = number_format($trans->transactionValue,18);
			        if(number_format($row->value, 18) > $value)
			        {
				        $row->age   = $trans->time;
				        $row->block = $trans->blockNumber;
				        $results[] = $row;
				    }
		        }
		        return $results;
	        }
        }
        
        public function circulating_supply($block = NULL)
        {
	        $block = ($block == NULL ? $this->last_block() : $block);
	        $start     = 252460800;
	        $twenty    = $start - ($start * .2) + $block;
	        return $twenty;
	        
        }
        
        public function total_supply($block = NULL)
        {
	        $block = ($block == NULL ? $this->last_block() : $block);
	        $start     = 252460800;
	        return $start + $block;
	        
        }
        
        public function network_stats()
        {
	        $data = json_decode(file_get_contents('https://tuo2uaw74i.execute-api.us-east-1.amazonaws.com/POA/blockInfo'));
	        $aveBlockTime    = $data->Item->stat->M->avgBlockTime->N;
	        $lastBlockTime   = $data->Item->stat->M->lastBlockTimestamp->N;
	        $lastBlockNumber = $data->Item->stat->M->lastBlockNumber->N;
	        
	        $array = array(
		      'average_block_time' => $aveBlockTime,
		      'last_block_timestamp' => $lastBlockTime,
		      'last_block_number' => $lastBlockNumber,
		      'circulating_supply' => $this->circulating_supply($lastBlockNumber),
		      'total_supply'       => $this->total_supply($lastBlockNumber)
		        
	        );
	        
	        
	        
	        return $array;
        }
        
        public function get_balance($address) {
	        $this->db->where('wallet', $address);
	        $sql = $this->db->get('wallets');
	        if($sql->num_rows() > 0)
	        {
		        $row = $sql->row();
		        return $row->balance;
	        }
	        else
	        {
		        return 0;
	        }
        }
        
        public function get_transaction_count($address) {
	        $this->db->where('to', $address);
	        $this->db->or_where('from', $address);
	        $this->db->or_where('creates', $address);
	        $sql = $this->db->get('transactions');
	        return $sql->num_rows();
        }
        
        public function balance() {
	        $address = $this->uri->segment(3);
	        if(!empty($address))
	        {
		        $status = 'ok';
		        $balance      = _balance($address);
		        $transactions = $this->get_transaction_count($address);
		        return array(
			        'status' => $status,
			        'address' => $address,
			        'balance' => $balance,
			        'transactions_count' => $transactions
		        );
	        }
	        else
	        {
		        $status = 'error';
		        $message = 'Address not found!';
		        return array(
			      'status' => $status,
			      'message' => $message  
		        );
	        }
	        
        }

}