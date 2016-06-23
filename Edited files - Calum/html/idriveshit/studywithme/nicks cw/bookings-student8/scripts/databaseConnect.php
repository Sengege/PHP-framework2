<?php
/*
* Database Connect
*
* Establish a connection to database
*/

// Database Connect Variables
$hostname = "localhost";
$username = "root";
$password = "";

// Database Error Variable
$noerrors = 0;

// Make connection to database with PDO and store connection in $db
try {
	$db = new PDO("mysql:host=$hostname;dbname=bookings", $username, $password);
}
catch (PDOException $e) {
	// If database connection error - noerrors set to 1
	$noerrors = 1;
}