<?php
	require 'includes/database.php';
	$poa = new Ethereum('https://core.poa.network', '');
	
	$sql = "SELECT * FROM transactions ORDER BY tid DESC LIMIT 1000";
	$res = $link->query($sql);
	if($res->num_rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
			
			
         	if(check_wallet($row['to']) == true) {
	         	insert_wallet($row['to']);
         	}
         	
         	if(check_wallet($row['from']) == true) {
	         	insert_wallet($row['from']);
         	}
         	
         	if(!empty($row['creates']))
         	{
	         	if(check_wallet($row['creates']) == true) {
		         	insert_wallet($row['creates']);
	         	} 
	         }
         	
         	
       	}
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
		$is_contract = is_contract($wallet);
		$sql = "INSERT INTO wallets (wallet, contract) VALUES ('".prepareData($wallet)."', '".prepareData($is_contract)."')";
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
	
	
	function prepareData($data) {
		return trim($data);
	}
	
	