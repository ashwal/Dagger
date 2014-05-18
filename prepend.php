<?php
	$username = "root";
	$password = "root";
	$hostname = "localhost"; 
	
	try {
		$conn = new PDO('mysql:host='.$hostname.';dbname=Smarter', $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

?>