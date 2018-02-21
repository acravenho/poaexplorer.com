<?php
	require 'includes/database.php';
	require 'includes/functions.php';
	
	$scriptname = 'internal transactions';
	
	if(checkscript($scriptname) == true) {
		die('Program Died');
	} 
	
	
	$poa = new Ethereum('https://core-trace.poa.network', '');
	
	
	$sql = "SELECT * FROM internal_transactions ORDER BY id DESC LIMIT 1";
	$res = $link->query($sql);
	
	if($res->num_rows > 0)
	{
		$r = $res->fetch_assoc();
		$txid = $r['parent'];
		
		$sql = "SELECT * FROM transactions WHERE txid = '".$txid."' LIMIT 1";
		$res = $link->query($sql);
		
		if($res->num_rows > 0)
		{
			$r = $res->fetch_assoc();
			$tid = $r['tid'];
		}
	}
	else
	{
		$tid = 0;
	}
	
	
		
		
	$sql = "SELECT txid FROM transactions WHERE tid > '".$tid."' LIMIT 1000";
	$res = $link->query($sql);
			
	if($res->num_rows > 0)
	{
		while($row = $res->fetch_array())
		{
			$internal_transaction_data = get_internal_transaction($row['txid']);
			if(!empty($internal_transaction_data))
			{
				check_internal_tranaction($internal_transaction_data, $row['txid']);
			}
		}
	}
	
	scriptoff($scriptname);
	
	
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
		global $poa;
		$internal = $poa->eth_sendTrace("trace_replayTransaction", array("$txid", array('trace')));
		
		if(!empty($internal))
		{
			return $internal;
		}
		return false;
	}
	
	
	
	
	
	