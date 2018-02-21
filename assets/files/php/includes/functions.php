<?php
	
	function checkscript($name) {
		global $link;
		$sql = "SELECT * FROM scraping WHERE name = '$name' LIMIT 1";
		$res = $link->query($sql);
		if($res->num_rows > 0)
		{
			$row = $res->fetch_assoc();
			if($row['status'] == 0)
			{
				$sql = "UPDATE scraping SET status = '1' WHERE name = '$name' LIMIT 1";
				$upd = $link->query($sql);
				return false;
			}
			else 
			{
				return true;
			}
		}
		else
		{
			$sql = "INSERT INTO scraping (name, status) VALUES ('$name', '1')";
			$link->query($sql);
			return false;
		}
	}
	
	function scriptoff($name) {
		global $link;
		$sql = "UPDATE scraping SET status = '0' WHERE name = '$name' LIMIT 1";
		$link->query($sql);
		
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