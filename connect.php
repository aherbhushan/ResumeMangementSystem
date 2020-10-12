<?php
$host = "localhost";
$username = "root";
$pwd = "";
$dbname = "info";

	$pdo = new PDO("mysql:host=localhost;dbname=info",$username,$pwd); 

	 if (!$pdo) {
	 	echo  "faioed to connect";
	 }
?>