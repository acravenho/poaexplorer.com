<?php
	require 'includes/database.php';
	require 'includes/functions.php';
	
	$target_time = strtotime("-30 minutes");
	$now_time    = strtotime("now");
	
	$scriptname = 'update balances';
	
	if(checkscript($scriptname) == true) {
		die();
	} 
	
	$sql = "SELECT * FROM wallets WHERE time < '$target_time' LIMIT 200";
	$res = $link->query($sql);
	if($res->num_rows > 0) {
		
		$poa = new Ethereum('https://core-trace.poa.network', '');
		
		
		
		while($row = mysqli_fetch_assoc($res)) {
			$balance = wei_to_ether($poa->eth_getBalance($row['wallet'], 'latest', TRUE));
         	
         	update_wallet($row['wallet'], $balance);
         	
         	
       	}
	}
	
	scriptoff($scriptname);
	
	
	function update_wallet($wallet, $balance) {
		global $now_time;
		global $link;
		$sql = "UPDATE wallets SET balance = '$balance', time = '$now_time' WHERE wallet = '$wallet' LIMIT 1";
		$link->query($sql);
	}
	
	
	
