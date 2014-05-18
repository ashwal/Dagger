<?
	include("prepend.php");

	//Create database
	$conn->exec("CREATE DATABASE `$Post`;
	                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
	                GRANT ALL ON `$Post`.* TO '$user'@'localhost';
	                FLUSH PRIVILEGES;") 
	        or die(print_r($conn->errorInfo(), true));

	//Posts table
	$conn->exec("CREATE TABLE Posts
				(id MEDIUMINT NOT NULL AUTO_INCREMENT,
				title TEXT,
				text TEXT,
				PRIMARY KEY (id))") 
	        or die(print_r($conn->errorInfo(), true));


?>