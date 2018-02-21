<?php
	require 'includes/database.php';
	require 'includes/functions.php';
	$scriptname = 'check internal addresses';
	
	if(checkscript($scriptname) == true) {
		die();
	} 
	
	$poa = new Ethereum('https://core.poa.network', '');
	
	$random = rand(0,2);
	$array  = array('to', 'from', 'address');
	$rgroup = $array[$random];
	
	
	$sql = "SELECT * FROM internal_transactions GROUP BY `$rgroup`";
	$res = $link->query($sql);
	
	if($res->num_rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
			
			if(!empty($row["$rgroup"]))
			{
				if(strlen($row["$rgroup"]) == 42)
				{
					if(check_wallet($row["$rgroup"]) == true) {
			         	insert_wallet($row["$rgroup"]);
		         	}	
				}
	        }
         	
        }
	}
	
	scriptoff($scriptname);
	
	
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
	
	
	
	
	