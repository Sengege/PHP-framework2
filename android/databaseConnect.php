<?php

/** Database Connect** Establish a connection to database*/




// Database Connect Variables


$hostname = "localhost";
$username = "typhoon";
$password = "typhoon";

// Database Error Variable
$noerrors = 0;// Make connection to database with PDO and store connection in $db

try {	$db = new PDO("mysql:host=$hostname;dbname=studywithme;charset=utf8", $username, $password);}
catch (PDOException $e) {	
// If database connection error - noerrors set to 1	
	$noerrors = 1;
}



?>