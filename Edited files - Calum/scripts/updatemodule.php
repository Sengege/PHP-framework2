<?php
include 'prepend.php';
error_reporting(E_ALL);

function updatemodule() {
	global $db;
	global $student;
	global $noerrors;
	
	// If database connect details are incorrect
	if($noerrors <> 0)
	{	
		echo json_encode(array("result"=>"Failed","error"=>"No connection"));		
		return;	
	}	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	
	
	if(isset($jsonData->school)) { $school = $jsonData->school; } else { $school = ''; }
	if(isset($jsonData->studying)) { $module = $jsonData->studying; } else { $module = Array(); }
	

	$registrationDate = date("Y-m-d H:i:s");
	// JSON Message Array
	$jsonMessage = array();
	
	// INSERT group SQL STATEMENT
	$updateQuery = "UPDATE `students` SET `schoolID` = :schoolID WHERE `studentID` = :studentID";
	$q = $db->prepare($updateQuery);
	$q->bindParam(':schoolID', $school);
	$q->bindParam(':studentID', $student->userID);
	
	// If student insert execute is successful
	if($q->execute())
	{
		
		//remove modules in studying
		
		$s = $db->prepare("DELETE from `studying` WHERE studentID = :student");
		$s->bindParam(':student', $student->userID);
		$s->execute(); 
		
		//insert modules to studying
		$s = $db->prepare("INSERT INTO `studying`(`studentID`, `moduleID`) VALUES (:studentID,:moduleID)");

		foreach($module AS $studyingWan) {
			$s->bindParam(':studentID', $student->userID);
			//Changed this
			$s->bindParam(':moduleID', $studyingWan);
			$s->execute();
		}
		
		$jsonMessage["result"] = "Successful";
	    // echo JSON Message
		echo json_encode($jsonMessage);
			
	}
	else
	{
		echo json_encode(array("result"=>"Failed","error"=>"Disnae work"));		
	}
}

		?>