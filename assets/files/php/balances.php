<?php
	require 'includes/database.php';
	
	
	
	$sql = "SELECT * FROM wallets";
	$res = $link->query($sql);
	if($res->num_rows > 0) {
		
		$poa = new Ethereum('https://core.poa.network', '');
		
		
		
		while($row = mysqli_fetch_assoc($res)) {
         	$balance = wei_to_ether($poa->eth_getBalance($row['wallet'], 'latest', TRUE));
         	
         	update_wallet($row['wallet'], $balance);
         	
         	
       	}
	}
	
	
	function update_wallet($wallet, $balance) {
		global $link;
		$sql = "UPDATE wallets SET balance = '$balance' WHERE wallet = '$wallet' LIMIT 1";
		$link->query($sql);
	}
	
	
	function wei_to_ether($unit) {
		return $unit / pow(10,18);
	}
