<?php

function addGroup() {
	global $db;
	global $student;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	if(!$student->userExists())
	{
		echo json_encode(array("result"=>"failed","message"=>"User not logged in"));
		return;
	}
	
	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());

	if(isset($jsonData->groupName)) { $groupName = $jsonData->groupName; } else { $groupName = ''; } 
	if(isset($jsonData->groupModule)) { $groupModule = $jsonData->groupModule; } else { $groupModule = ''; } 
	if(isset($jsonData->groupDescription)) { $groupDescription = $jsonData->groupDescription; } else { $groupDescription = ''; } 
	if(isset($jsonData->oneOff)) { $oneOff = $jsonData->oneOff; } else { $oneOff = ''; } 
	if(isset($jsonData->groupType)) { $groupType = $jsonData->groupType; } else { $groupType = ''; } 
    $students = array();
	if(isset($jsonData->students)) { 
		if (is_array($jsonData->students)) {$students = $jsonData->students; } 
	}
	$tags = array();
	if(isset($jsonData->tags)) { 
		if (is_array($jsonData->tags)) {$tags = $jsonData->tags; } 
	}
	
	// Server Side Validation
	$errors = array();
	if($groupName == '') { $errors[] = "Group Name Required";  } 
	if($groupModule == '') { $errors[] = "Group Module Required";  }
	if($groupDescription == '') { $errors[] = "Group Description Required";  }
	if($oneOff == '') { $errors[] = "Group oneoff Required";  }
	if($groupType == '') { $errors[] = "Group Type Required";  }
	

	// If validation errors exist - display errors
	if(COUNT($errors)>0)
	{
		$errorMessage = array();
		$errorMessage["result"] = 'Failed';
		$errorMessage["message"] = 'validation';
		$errorMessage["errorsFound"] = COUNT($errors);
		$errorMessage["errors"] = array();
		foreach($errors AS $error)
		{
			$errorMessage["errors"][] = $error;
		}
		echo json_encode($errorMessage);		
		return;
	}
	

	$registrationDate = date("Y-m-d H:i:s");
	
	// INSERT group SQL STATEMENT
	$insertQuery = "INSERT INTO `groups`(`moduleID`, `adminID`, `groupName`, `groupDescription`, `type`, `createdDate`, `one_off`,`active`) VALUES (:moduleID, :adminID, :groupName, :groupDescription, :groupType, :createdDate, :oneOff,'1')";
	$q = $db->prepare($insertQuery);
	$q->bindParam(':moduleID', $jsonData->groupModule);
	$q->bindParam(':adminID', $student->userID);
	$q->bindParam(':groupName', $jsonData->groupName);
	$q->bindParam(':groupDescription', $jsonData->groupDescription);
	$q->bindParam(':groupType', $jsonData->groupType);
	$q->bindParam(':oneOff', $jsonData->oneOff);
	$q->bindParam(':createdDate', $registrationDate);

	
	// JSON Message Array
	$jsonMessage = array();
	
	// If student insert execute is successful
	if($q->execute())
	{
		
		// Get Last Insert ID
		$groupID = $db->lastInsertId();
		$adminID = $student->userID;
		
		// Add Group Membership row
		$s = $db->prepare("INSERT INTO `group_membership`(`studentID`, `groupID`,`dateJoined`) VALUES (:adminID,:groupID,:dateJoined)");
		
		
		$s->bindParam(':adminID', $adminID);
		$s->bindParam(':groupID', $groupID);
		$s->bindParam(':dateJoined', $registrationDate);
		$s->execute(); 
		
		// If students have been chosen - add them to group_membership
		foreach($students AS $student)
		{
			$s->bindParam(':adminID', $student);
			$s->bindParam(':groupID', $groupID);
			$s->bindParam(':dateJoined', $registrationDate);
			$s->execute();
		}
		
		//sql for add tags
		$t = $db->prepare("INSERT INTO `Tag_Group`(`TagID`,`groupID`) VALUES (:tagID, :groupID)");
		
		//add tags if any
		foreach($tags AS $tag)
		{
		$t->bindParam(':tagID', $tag);
		$t->bindParam(':groupID', $groupID);
		$t->execute();
		}
		// Return Success Message
		$jsonMessage["result"] = "successful";
		
	}
	else
	{
		// Return unsuccessful json message
		$jsonMessage["result"] = "unsuccessful";
		
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}

?>