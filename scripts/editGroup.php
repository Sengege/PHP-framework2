<?php

function editGroup() {
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

	$groupID = 0;
	$groupName = '';
	$groupDescription = '';
	if(isset($jsonData->groupID)) { $groupID = intval($jsonData->groupID); } 
	if(isset($jsonData->groupName)) { $groupName = htmlspecialchars($jsonData->groupName); } 
	if(isset($jsonData->groupDescription)) { $groupDescription = $jsonData->groupDescription; }
	
	
	// Server Side Validation
	$errors = array();
	if($groupName == '') { $errors[] = "Group Name Required";  } 
	if($groupID == '') { $errors[] = "Group ID Required";  }
	if($groupDescription == '') { $errors[] = "Group Description Required";  }

	

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
	
	// Check group exists
	$groupCheck = $db->prepare("SELECT * FROM `groups` WHERE `groupID` = :groupID");
	$groupCheck->bindParam(":groupID",$groupID);
	$groupCheck->execute();
	if($groupCheck->rowCount() != 1)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Group does not exist"));		
		return;	
	}
		
	//Check user is authorised to edit group - NOT GROUP ADMIN
	if(!isGroupAdmin($groupID))
	{
		echo json_encode(array("result"=>"Failed","message"=>"Not authorised to delete"));		
		return;
	}

	
	// INSERT group SQL STATEMENT
	$updateQuery = "UPDATE `groups` SET `groupName` = :groupName, `groupDescription` = :groupDescription WHERE `groupID` = :groupID"; 
	$q = $db->prepare($updateQuery);
	$q->bindParam(':groupID', $groupID);
	$q->bindParam(':groupName', $groupName);
	$q->bindParam(':groupDescription', $groupDescription);

	
	// If student insert execute is successful
	if($q->execute())
	{
		echo json_encode(array("result"=>"Successful"));		
		return;	
	}
	else
	{
		echo json_encode(array("result"=>"Failed","message"=>"Database Error"));		
		return;
	}
	
	
}

?>