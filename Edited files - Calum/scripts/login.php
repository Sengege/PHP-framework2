<?php

function login() {
	global $db;
	global $student;
	global $noerrors;
	global $app;
	$returnMessage = "";

	// If database connect details are incorrect
	if($noerrors != 0) {	
		echo json_encode(array("result"=>"failed","message"=>"No connection"));		
		return;	
	}	

	if($student->userExists())
	{
		$returnMessage = '{"result": "failed", "message": "You are already logged in."}';
		echo $returnMessage;
		return;
	}
	/*
	if (isset($_SESSION['username'])) {
		$returnMessage = '{"error": "You are already logged in."}';
		echo $returnMessage;
		return;
	}
	*/
	
	//$request = Slim\Slim::getInstance()->request();
	$username = $app->request()->post('username');
	$password = $app->request()->post('password');
	
	
	// Query
	$query =  "SELECT * FROM `students` WHERE (`username` = :username) OR (`email` = :username)";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':password', $password);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$salt = $row['salt'];
	$secure_password = hash('sha256', $salt.$password, false);

	if (!$row) { // Nothing found
		$returnMessage = '{"result":"failed","message": "No account for this username. Please try again."}';
	} else if ($row['secure_password'] == $secure_password){
		$_SESSION['studentID'] = $row['studentID'];
		$returnMessage = '{"result":"successful" }';
	} else if ($row['secure_password'] != $secure_password) {
		$returnMessage = '{"result":"failed","message": "Please check your password and try again."}';
	}

	echo $returnMessage;
}

?>