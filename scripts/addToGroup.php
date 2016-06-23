<?php

function addStudentToGroup() {
	global $db;
	global $student;
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
	
	
	if(isset($jsonData->group)) { $groupID = $jsonData->group; } else { $groupID = ''; }
	if(isset($jsonData->usersToAdd)) { $studentsToAdd = $jsonData->usersToAdd; } else { $studentsToAdd = Array(); }

	// JSON Message Array
	$jsonMessage = array();

	// If student insert execute is successful
		
		//remove from group
		
		$s = $db->prepare("DELETE FROM `group_membership` WHERE `groupID`=:groupID AND `studentID`!=:studentID");
		$s->bindParam(':studentID', $student->userID);
		$s->bindParam(':groupID', $groupID);
	    if($s->execute()) {
	
		$q = $db->prepare("INSERT INTO `group_membership`(`studentID`, `groupID`) VALUES (:studentID,:groupID)");

		foreach($studentsToAdd AS $addStudent) {
			$q->bindParam(':groupID', $groupID);
			$q->bindParam(':studentID', $addStudent);
			$q->execute();
		}
		
        addNotificationInc("New Members", $groupID, $student->userID, "");
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