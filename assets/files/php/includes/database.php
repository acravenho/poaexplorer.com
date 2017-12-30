<?php
	// include the class file
	require 'ethereum.php';
	
	$link = mysqli_connect("localhost", "root", "root", "poaexplo_data");

	if (!$link) {
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	    exit;
	}