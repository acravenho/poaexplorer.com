<?php
	require 'includes/database.php';
	$poa = new Ethereum('https://core.poa.network', '');
	
	$sql = "SELECT * FROM wallets ORDER BY wid ASC";
	$res = $link->query($sql);
	if($res->num_rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
         	if(is_contract($row['wallet']) == true) {
	         	echo $row['wallet'];
	         	$upd = "UPDATE wallets SET contract = '1' WHERE wid = '".prepareData($row['wid'])."'";
	         	$link->query($upd);
         	}
        }
	}
	
	
	$link->close();
	
	
	function is_contract($wallet) {
		global $poa;
		$code = $poa->eth_getCode($wallet);
		
		if($code=='0x') {
			return false;
		}
		return true;
	}
	
	
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
	