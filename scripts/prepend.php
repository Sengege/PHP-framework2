<?php

// Start session storage
if(!isset($_SESSION)){
session_start();	
}


// Set Local Time
date_default_timezone_set("Europe/London");

// include DB connection
require_once('databaseConnect.php');

// include student Class - must be before database connection
require_once('class/studentClass.php');

// Declare Student Class
$sessionID = 0;
if(isset($_SESSION['studentID'])) {$sessionID = $_SESSION['studentID']; }
$student = new Student($sessionID);

?>