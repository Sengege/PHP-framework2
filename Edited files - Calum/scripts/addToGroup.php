<?php

function addStudentToGroup($json) {
	global $db;
	global $noerrors;
	
	// If database connect details are incorrect
	if($noerrors <> 0)
	{	
		echo json_encode(array("error"=>"No connection"));		
		return;	
	}	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	

	
	// Server Side Validation
	$errors = array();
	if($jsonData->students == '') { $errors[] = "Students to add are Required";  } 



	// If validation errors exist - display errors
	if(COUNT($errors)>0)
	{
		$errorMessage = array();
		$errorMessage["errorsFound"] = COUNT($errors);
		$errorMessage["errors"] = array();
		foreach($errors AS $error)
		{
			$errorMessage["errors"][] = $error;
		}
		echo json_encode($errorMessage);		
		return;
	}

	$studentsToAdd = $jsonData->students;
	$currentGroup = 1;
	
	// JSON Message Array
	$jsonMessage = array();
	
	$q = $db->prepare("INSERT INTO `studying`(`studentID`, `groupID`) VALUES (:studentID,:groupID)");
		// Add students to group membership
		for($i=0;$i<COUNT($studentsToAdd);$i++)
		{
			$q->bindParam(':studentID', $studentID);
			$q->bindParam(':groupID', $studentsToAdd[$i]);
			if($q->execute()) {
		$jsonMessage["result"] = "add student successful";
	}
	else
	{
		// Return unsuccessful json message
		$jsonMessage["result"] = " add student unsuccessful";	
	}
		}
	
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}

?>
