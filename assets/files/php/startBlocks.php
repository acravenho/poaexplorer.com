<?php
	require 'includes/database.php';
	require 'includes/functions.php';
	$scriptname = 'update previous transactions';
	
	if(checkscript($scriptname) == true) {
		die();
	} 
	
	$poa = new Ethereum('https://core.poa.network', '');
	
	$blockStart = get_start_block();
	$blockEnd   = $blockStart + 100;
	
	for($i = $blockStart; $i <= $blockEnd; $i++)
	{
		$blockInt = (string) $i;
		$block = $poa->eth_getBlockByNumber($blockInt);
		
		update_block_info($block);
		
		if(!empty($block->transactions))
		{
			update_transaction_history($block);
		}
		
		//echo '<pre>'; print_r($block); echo '</pre>';
		
		update_blocks($i);
	}
	
	
	
	
	
	scriptoff($scriptname);
	
	
	function update_transaction_history($block)
	{
		global $link;
		global $poa;
		
		$timestamp  = decode_hex($block->timestamp); 
		
		foreach($block->transactions as $t)
		{
			if(check_transaction($t) == true)
			{
				update_transaction($t, $timestamp);
			}
			else
			{
				insert_transaction($t, $timestamp);
			}
		}
		
		
	}
	
	function check_transaction($transaction)
	{
		global $link;
		$hash = $transaction->hash;
		$sql = "SELECT tid FROM transactions WHERE txid = '$hash' LIMIT 1";
		$res = $link->query($sql);
		
		if($res->num_rows > 0)
		{
			return true;
		}
		return false;
	}
	
	function update_transaction($transaction, $timestamp)
	{
		global $link;
		global $poa;
		
		$hash     = $transaction->hash;
		$gas      = decode_hex($transaction->gas);
		$receipt  = $poa->eth_getTransactionReceipt($hash);
		$status   = decode_hex($receipt->status);
		$gasUsed  = decode_hex($receipt->gasUsed);
		$gasPrice = wei_to_ether(decode_hex($transaction->gasPrice));
		
		$sql = "UPDATE transactions SET `time` = '$timestamp', `status` = '$status', `gas` = '$gas', `gasPrice` = '$gasPrice', `gasUsed` = '$gasUsed' WHERE `txid` = '$hash' LIMIT 1";
		$link->query($sql);
	}
	
	function insert_transaction($transaction, $timestamp)
	{
		global $link;
		global $poa;
		
		$hash        = $transaction->hash;
		$value       = wei_to_ether(decode_hex($transaction->value));
		$creates     = $transaction->creates;
		$to          = $transaction->to;
		$tfrom       = $transaction->from;
		$blockHash   = $transaction->blockHash;
		$blockNumber = decode_hex($transaction->blockNumber);
		$gas         = decode_hex($transaction->gas);
		$gasPrice    = wei_to_ether(decode_hex($transaction->gasPrice));
		$nonce       = decode_hex($transaction->nonce);
		
		$receipt     = $poa->eth_getTransactionReceipt($hash);
		$status      = decode_hex($receipt->status);
		$gasUsed     = decode_hex($receipt->gasUsed);
		
		$sql = "INSERT INTO transactions (
			`to`,
			`from`,
			`creates`,
			`blockHash`,
			`blockNumber`,
			`txid`,
			`transactionValue`,
			`gas`,
			`gasUsed`,
			`gasPrice`,
			`status`,
			`nonce`,
			`time`
			) VALUES (
			'".prepareData($to)."', 
			'".prepareData($tfrom)."',
			'".prepareData($creates)."',
			'".prepareData($blockHash)."',
			'".prepareData($blockNumber)."',
			'".prepareData($hash)."',
			'".prepareData($value)."',
			'".prepareData($gas)."',
			'".prepareData($gasUsed)."',
			'".prepareData($gasPrice)."',
			'".prepareData($status)."',
			'".prepareData($nonce)."',
			'".prepareData($timestamp)."')";
			
		$link->query($sql);
		
		
	}
	
	
	function update_block_info($block)
	{
		global $link;
		$timestamp  = decode_hex($block->timestamp);
		$author     = $block->author;
		$transCount = count($block->transactions);
		$blocknum   = decode_hex($block->number);
		
		$sql = "SELECT * FROM blocks WHERE blocknum = '$blocknum'";
		$res = $link->query($sql);
		
		if($res->num_rows > 0)
		{
			$sql = "UPDATE blocks SET timestamp = '$timestamp', validator = '$author', transactions = '$transCount' WHERE blocknum = '$blocknum'";
			$link->query($sql);
		}
		else
		{
			$sql = "INSERT INTO blocks (`blocknum`, `timestamp`, `validator`, `transactions`) VALUES ('".prepareData($blocknum)."', '".prepareData($timestamp)."', '".prepareData($author)."', '".prepareData($transCount)."')";
			$link->query($sql);
		}
		
	}
	
	function update_blocks($block)
	{
		global $link;
		$sql = "INSERT INTO increment (`block`) VALUES ('$block')";
		$link->query($sql);
	}
	
	
	
	function get_start_block()
	{
		global $link;
		$sql = "SELECT * FROM increment ORDER BY id DESC LIMIT 1";
		$res = $link->query($sql);
		
		if($res->num_rows > 0)
		{
			$b = $res->fetch_assoc();
			$blockStart = $b['block'] + 1;
		}
		else
		{
			$blockStart = 1;
		}
		return $blockStart;
		
	}