<?php
	require 'includes/database.php';
	
	
	$sql = "SELECT * FROM transactions";
	$res = $link->query($sql);
	if($res->num_rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
         	if(check_wallet($row['to']) == true) {
	         	insert_wallet($row['to']);
         	}
         	
         	if(check_wallet($row['from']) == true) {
	         	insert_wallet($row['from']);
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
		$sql = "INSERT INTO wallets (wallet) VALUES ('".prepareData($wallet)."')";
		if(!$link->query($sql)) {
			printf("Errormessage: %s\n", mysqli_error($link));
		}
	}
	
	
	function prepareData($data) {
		return trim($data);
	}
	
	