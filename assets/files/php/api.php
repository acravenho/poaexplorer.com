<?php
	set_time_limit(180);
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	require 'includes/database.php';
	require 'includes/functions.php';
	
	$scriptname = 'getBlocks';
	
	if(checkscript($scriptname) == true) {
		die('Already running');
	} 
	
	$time_start = microtime(true);
	
	
	for($t =1; $t<= 50; $t++) {
		if(calculate_time($time_start) > 50) {
			scriptoff($scriptname);
			$link->close();
			die();
		}
		
	
		$sql = "SELECT * FROM blocks ORDER BY blocknum DESC LIMIT 1";
		$res = $link->query($sql);
		if($res->num_rows > 0) {
			$row = $res->fetch_assoc();
			$startBlock = $row['blocknum'] + 1;
		}
		
			
		
		$endBlock = $startBlock + 400;
		$endBlock = (string) $endBlock;
		
		$poa   = new Ethereum('https://core.poa.network', '');
		$blockHeight = $poa->eth_blockNumber(TRUE);
		
		$finishBlock = ($blockHeight > $endBlock ? $endBlock : $blockHeight);
	
		for($x=$startBlock; $x < $finishBlock; $x++) {
			get_block_info($x);	
		}
		sleep(1);
	}
	
	scriptoff($scriptname);
	
	$link->close();
	
	function calculate_time($start) {
		$current = microtime(true);
		$seconds = ($current - $start);
		return $seconds;
	}
	
	function get_block_info($block) {
		global $poa;
		global $link;
		
		
		$blockInt = '0x'.dechex($block);
		$actualBlock = hexdec($blockInt);
	
		$block = $poa->eth_getBlockByNumber("$blockInt");
		
		$timestamp  = decode_hex($block->timestamp);
		$author     = $block->author;
		$difficulty = $block->difficulty;
		$gasUsed    = $block->gasUsed;
		$blockHash  = $block->hash;
		$size       = $block->size;
		$totalDifficulty = $block->totalDifficulty;
		$transactions    = $block->transactions;
		$transactionsCount = count($transactions);
		
		$sqlint = "INSERT INTO blocks (blocknum, timestamp, validator, transactions) VALUES ('".$actualBlock."', '".$timestamp."', '".$author."', '".$transactionsCount."')";
		$link->query($sqlint);
		
		if(!empty($transactions)) {
			for($i = 0; $i < count($transactions); $i++) {
				$data = array();
				$data['hash'] = $transactions[$i]->hash;
				$data['value'] = wei_to_ether(decode_hex($transactions[$i]->value));
				$data['to'] = $transactions[$i]->to;
				$data['creates'] = $transactions[$i]->creates;
				$data['tfrom'] = $transactions[$i]->from;
				$data['blockHash'] = $transactions[$i]->blockHash;
				$data['blockNumber'] = decode_hex($transactions[$i]->blockNumber);
				$data['gas'] = decode_hex($transactions[$i]->gas);
				$data['gasPrice'] = wei_to_ether(decode_hex($transactions[$i]->gasPrice));
				$data['nonce'] = decode_hex($transactions[$i]->nonce);
				
				insert_transaction($timestamp, $data);
			}
		}
	}
	
	
	
	function insert_transaction ($timestamp, $data) {
		global $link;
		
		
		$sil = "SELECT txid FROM transactions WHERE txid = '".$data['hash']."' LIMIT 1";
		$ret = $link->query($sil);
		
		if($ret->num_rows === 0) {
			$now = strtotime("now");
			$ins = "INSERT INTO transactions (`to`, `from`, `blockHash`, `creates`, `blockNumber`, `txid`, `transactionValue`, `gas`, `gasPrice`, `nonce`, `time`) VALUES ('".prepareData($data['to'])."', '".prepareData($data['tfrom'])."', '".prepareData($data['blockHash'])."', '".prepareData($data['creates'])."', '".prepareData($data['blockNumber'])."', '".prepareData($data['hash'])."', '".prepareData($data['value'])."', '".prepareData($data['gas'])."', '".prepareData($data['gasPrice'])."', '".prepareData($data['nonce'])."', '".$timestamp."')";
			if(!$link->query($ins)) {
				printf("Errormessage: %s\n", mysqli_error($link));
			}
			
			
			process_internal_transaction($data);
			check_receipt($data);
			
			if(!empty($data['to'])) {
				if(check_wallet($data['to']) == true) {
		         	insert_wallet($data['to']);
	         	} else {
		         	update_wallet($data['to']);
	         	}
	         }
         	
         	if(check_wallet($data['tfrom']) == true) {
	         	insert_wallet($data['tfrom']);
         	} else {
	         	update_wallet($data['tfrom']);
         	}
         	
         	if(!empty($data['creates']))
         	{
	         	if(check_wallet($data['creates']) == true) {
		         	insert_wallet($data['creates']);
	         	}
	         }
			
		}
	}
	
	function process_internal_transaction($data) {
		$internal_transaction_data = get_internal_transaction($data['hash']);
		if(!empty($internal_transaction_data))
		{
			check_internal_tranaction($internal_transaction_data, $data['hash']);
		}
	}
	
	
	function check_receipt($data) {
		global $poa;
		global $link;
		$hash     = $data['hash'];
		$gas      = decode_hex($data['gas']);
		$receipt  = $poa->eth_getTransactionReceipt($data['hash']);
		$status   = decode_hex($receipt->status);
		$gasUsed  = decode_hex($receipt->gasUsed);
		$gasPrice = wei_to_ether(decode_hex($data['gasPrice']));
	
		$sql = "UPDATE transactions SET `status` = '$status', `gas` = '$gas', `gasPrice` = '$gasPrice', `gasUsed` = '$gasUsed' WHERE `txid` = '$hash' LIMIT 1";
		$link->query($sql);
	}
	
	
	function check_internal_tranaction($array, $txid)
	{
		if(!empty($array))
		{
			foreach($array->trace as $trace)
			{
				if($trace->subtraces >= 0)
				{
					insert_internal_transaction($trace, $txid);
				}
			}
		}
	}
	
	function insert_internal_transaction($array, $txid)
	{
		
		global $link;
		$data['parent']  = $txid;
		$data['call']    = $array->type;
		$data['from']    = $array->action->from;
		$data['init']    = $array->action->init;
		$data['to']      = $array->action->to;
		$data['address'] = $array->result->address;
		$data['input']   = $array->action->input;
		$data['code']    = $array->result->code;
		$data['value']   = wei_to_ether(decode_hex($array->action->value));
		$data['gas']     = wei_to_ether(decode_hex($array->action->gas));
		$data['gasUsed'] = wei_to_ether(decode_hex($array->result->gasUsed));
		$data['output']  = $array->result->output;
		$data['subtraces'] = $array->subtraces;
		
		//echo '<pre>'; print_r($data); echo '</pre>';
		//echo '<pre>'; print_r($array); echo '</pre>';
		//echo $txid . ' '.$value.'<br />';
		
		$insert = "INSERT INTO internal_transactions (`parent`, `call`, `from`, `init`, `gas`, `input`, `code`, `to`, `address`, `value`, `gasUsed`, `output`, `subtrace`) VALUES ('".prepareData($data['parent'])."', '".prepareData($data['call'])."', '".prepareData($data['from'])."', '".prepareData($data['init'])."', '".prepareData($data['gas'])."', '".prepareData($data['input'])."', '".prepareData($data['code'])."', '".prepareData($data['to'])."', '".prepareData($data['address'])."', '".prepareData($data['value'])."', '".prepareData($data['gasUsed'])."', '".prepareData($data['output'])."', '".prepareData($data['subtraces'])."')";
		
		if(!$link->query($insert)) {
			printf("Errormessage: %s\n", mysqli_error($link));
		}
		
	}
	
	
	function get_internal_transaction($txid)
	{
		$trace = new Ethereum('https://core-trace.poa.network', '');
		$internal = $trace->eth_sendTrace("trace_replayTransaction", array("$txid", array('trace')));
		
		if(!empty($internal))
		{
			return $internal;
		}
		return false;
	}
	
	function check_wallet($wallet) {
		global $link;
		if(empty($wallet)) {
			return false;
		}
		$sql = "SELECT * FROM wallets WHERE wallet = '".prepareData($wallet)."' LIMIT 1";
        $results = $link->query($sql);
        if($results->num_rows === 0) {
	        return true;
        }
        return false;
	}
	
	function insert_wallet($wallet) {
		global $link;
		global $poa;
		$time = strtotime("now");
		$balance = wei_to_ether($poa->eth_getBalance($wallet, 'latest', TRUE));
		$is_contract = is_contract($wallet);
		$sql = "INSERT INTO wallets (wallet, balance, time, contract) VALUES ('".prepareData($wallet)."', '".prepareData($balance)."', '".prepareData($time)."', '".prepareData($is_contract)."')";
		if(!$link->query($sql)) {
			printf("Errormessage: %s\n", mysqli_error($link));
		}
	}
	
	function update_wallet($wallet) {
		global $link;
		global $poa;
		$time = strtotime("now");
		$balance = wei_to_ether($poa->eth_getBalance($wallet, 'latest', TRUE));
		$sql = "UPDATE wallets SET balance = '$balance', time = '$time' WHERE wallet = '$wallet' LIMIT 1";
		if(!$link->query($sql)) {
			printf("Errormessage: %s\n", mysqli_error($link));
		}
	}
	
	function is_contract($wallet) {
		global $poa;
		$code = $poa->eth_getCode($wallet);
		
		if($code=='0x') {
			return 0;
		}
		return 1;
	}
	
	
	