<?php
	require 'includes/database.php';
	
	$sql = "SELECT * FROM blocks ORDER BY blocknum DESC LIMIT 1";
	$res = $link->query($sql);
	if($res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$startBlock = $row['blocknum'] + 1;
	}
	
	$endBlock = $startBlock + 1000;
	$endBlock = (string) $endBlock;
	
	$poa = new Ethereum('https://core.poa.network', '');
	$blockHeight = $poa->eth_blockNumber(TRUE);
	
	
	for($x=$startBlock; $x< $blockHeight; $x++) {
		
		$blockInt = (string) $x;
	
		
		
		$transactionCount = intval($poa->eth_getBlockTransactionCountByNumber($blockInt));
		$block = $poa->eth_getBlockByNumber($blockInt);
		
		
		$timestamp  = decode_hex($block->timestamp);
		$author     = $block->author;
		$difficulty = $block->difficulty;
		$gasUsed    = $block->gasUsed;
		$blockHash  = $block->hash;
		$size       = $block->size;
		$totalDifficulty = $block->totalDifficulty;
		$transactions    = $block->transactions;
		$transactionsCount = count($transactions);
		
		if(!empty($transactions)) {
			for($i = 0; $i < count($transactions); $i++) {
				$data = array();
			
				
				$data['hash'] = $transactions[$i]->hash;
				$data['value'] = wei_to_ether(decode_hex($transactions[$i]->value));
				$data['to'] = $transactions[$i]->to;
				$data['tfrom'] = $transactions[$i]->from;
				$data['blockHash'] = $transactions[$i]->blockHash;
				$data['blockNumber'] = decode_hex($transactions[$i]->blockNumber);
				$data['gas'] = decode_hex($transactions[$i]->gas);
				$data['gasPrice'] = wei_to_ether(decode_hex($transactions[$i]->gasPrice));
				$data['nonce'] = decode_hex($transactions[$i]->nonce);
				
				
				
				
				
				$sil = "SELECT txid FROM transactions WHERE txid = '".$data['hash']."' LIMIT 1";
				$ret = $link->query($sil);
				
				if($ret->num_rows === 0) {
					$now = strtotime("now");
					$ins = "INSERT INTO transactions (`to`, `from`, `blockHash`, `blockNumber`, `txid`, `transactionValue`, `gas`, `gasPrice`, `nonce`, `time`) VALUES ('".prepareData($data['to'])."', '".prepareData($data['tfrom'])."', '".prepareData($data['blockHash'])."', '".prepareData($data['blockNumber'])."', '".prepareData($data['hash'])."', '".prepareData($data['value'])."', '".prepareData($data['gas'])."', '".prepareData($data['gasPrice'])."', '".prepareData($data['nonce'])."', '".$now."')";
					if(!$link->query($ins)) {
						printf("Errormessage: %s\n", mysqli_error($link));
					}
				} else {
					echo 'Already Found';
				}
			}
		}
		
		$sqlint = "INSERT INTO blocks (blocknum, timestamp, validator, transactions) VALUES ('".$blockInt."', '".$timestamp."', '".$author."', '".$transactionCount."')";
		$link->query($sqlint);
	}
	
	
	
	
	
	
	$link->close();
	
	
	function prepareData($data) {
		return trim($data);
	}
	
	
	function decode_hex($input)
	{
		if(substr($input, 0, 2) == '0x')
			$input = substr($input, 2);
		
		if(preg_match('/[a-f0-9]+/', $input))
			return hexdec($input);
			
		return $input;
	}
	
	function wei_to_ether($unit) {
		return $unit / pow(10,18);
	}
	