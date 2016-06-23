<?php


function removeStudentFromGroup() {
	global $db;
	global $noerrors;
	global $student;
	
	// If database connect details are incorrect
	if($noerrors <> 0)
	{	
		echo json_encode(array("error"=>"No connection"));		
		return;	
	}	
	$request = Slim\Slim::getInstance()->request();
	//$body = $request->getBody();
	//$jsonData = json_decode($request->getBody());
	$jsonData = array (
	  'studentID'=>$request->post('studentID'), 
	  'groupID'=>$request->post('groupID')
	 );
	
	$studentToRemove = "";// $jsonData->studentID;
	$currentGroup = 1;
	
	// Server Side Validation
	$errors = array();
	if(isset($jsonData['studentID'])){$studentToRemove = $jsonData['studentID'];} 
	     
    if ($studentToRemove == "") {
        $errors[] = "Students to remove are Required";  
    } 

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

	// JSON Message Array
	$jsonMessage = array();
		
	$q = $db->prepare("DELETE FROM `group_membership` WHERE `groupID`=:groupID AND `studentID`=:studentID");
        //"DELETE FROM `group_membership`(`groupID`, `studentID`) VALUES (:groupID,:studentID)");
		// Add students to group to remove
			$q->bindParam(':studentID', $studentToRemove);
			$q->bindParam(':groupID', $jsonData['groupID']);
			if($q->execute()) {
		$jsonMessage["result"] = "Student has left the group.";
        }
	        else
	    {
    		// Return unsuccessful json message
    		$jsonMessage["result"] = " remove student unsuccessful";	
	    }
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}

function adminOpenGroup() {
	global $db;
	global $noerrors;
	global $student;
	
	// If database connect details are incorrect
	if($noerrors <> 0)
	{	
		echo json_encode(array("error"=>"No connection"));		
		return;	
	}	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = array(
	   'groupID'=>$request->post('groupID')
	   );
	
	// Server Side Validation
	$errors = array();
	if(isset($jsonData['groupID'])) { $groupOpen = $jsonData['groupID'];} 



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

	//$currentGroup = 1;
	
	// JSON Message Array
	$jsonMessage = array();
	
	$openQuery = "UPDATE `groups` SET `active`=1 WHERE `groupID` = :groupID";
	$q = $db->prepare($openQuery);
	$q->bindParam(':groupID', $groupOpen);
	if($q->execute()) {
		$jsonMessage["result"] = "You have reopened the group.";
		addNotificationInc("Group Reactivated", $jsonData['groupID'], $student->userID, "");
	}
	else
	{
		// Return unsuccessful json message
		$jsonMessage["result"] = " Group reamins closed";	
	}
	

	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}

function adminCloseGroup() {
	global $db;
	global $noerrors;
	global $student;
	
	// If database connect details are incorrect
	if($noerrors <> 0)
	{	
		echo json_encode(array("error"=>"No connection"));		
		return;	
	}	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = array(
	   'groupID'=>$request->post('groupID')
	   );
	
	// Server Side Validation
	$errors = array();
	if(isset($jsonData['groupID'])) { $groupClose = $jsonData['groupID'];} 



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

	//$currentGroup = 1;
	
	// JSON Message Array
	$jsonMessage = array();
	
	$closeQuery = "UPDATE `groups` SET `active`=0 WHERE `groupID` = :groupID";
	$q = $db->prepare($closeQuery);
	$q->bindParam(':groupID', $groupClose);
	if($q->execute()) {
		$jsonMessage["result"] = "Admin has closed the Group.";
		addNotificationInc("Group Defunct", $jsonData['groupID'], $student->userID, "");
	}
	else
	{
		// Return unsuccessful json message
		$jsonMessage["result"] = " Group still open";	
	}
	

	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}


?>