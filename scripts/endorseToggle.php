<?php

function endorseToggle(){

global $student;
global $db;
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

    $groupID = '';
	$endorseID = '';
	if(isset($jsonData->groupID)) { $groupID = $jsonData->groupID;}
	else{
	echo json_encode(array("result"=>"failed","message"=>"No Group to endorse for"));
		return;
	}
	if(isset($jsonData->endorseID)) { $endorseID = $jsonData->endorseID;}
	else {
	    echo json_encode(array("result"=>"failed","message"=>"No User to endorse"));
		return;
		}
	
	// Check group Exists
	$checkGroup = $db->prepare("SELECT * FROM `groups` WHERE `groupID` = :groupID");
	$checkGroup->bindParam(":groupID",$groupID);
	$checkGroup->execute();
	
	if($checkGroup->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Group does not exist"));
		return;
	}
	
	// Check if student is member of group
	$checkMembership = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID AND `studentID` = :studentID");
	$checkMembership->bindParam(":groupID",$groupID);
	$checkMembership->bindParam(":studentID",$student->userID);
	$checkMembership->execute();
	
	if($checkMembership->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Not a member of this group"));
		return;
	}
	
	// Check if user is member of group
	$checkeMembership = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID AND `studentID` = :endorseID");
	$checkeMembership->bindParam(":groupID",$groupID);
	$checkeMembership->bindParam(":endorseID",$endorseID);
	$checkeMembership->execute();
	
	if($checkeMembership->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Endorsee is not a member of this group"));
		return;
	}
	
	//check if endorsement exists
	$checkExisting = $db->prepare("SELECT * FROM  `endorsements` WHERE `endorsedBy` = :studentID AND `studentID` = :endorseID AND `groupID` = :groupID");
	$checkExisting->bindParam(":groupID", $groupID);
	$checkExisting->bindParam(":studentID", $student->userID);
	$checkExisting->bindParam(":endorseID", $endorseID);
	$checkExisting->execute();
	
	if($checkExisting->rowCount() == 1)
	{
	
	$jsonMessage = array();
	
	// remove endorsement
	$removeEndorsement = $db->prepare("DELETE FROM `endorsements` WHERE `groupID` = :groupID AND `studentID` = :endorseID AND `endorsedBy` = :studentID");
	$removeEndorsement->bindParam(":groupID",$groupID);
	$removeEndorsement->bindParam(":studentID",$student->userID);
	$removeEndorsement->bindParam(":endorseID",$endorseID);
	
	if($addEndorsement->execute())
	{
		$jsonMessage['result'] = "Successful";
		
	}
	else
	{
		$jsonMessage['result'] = "unsuccessful";
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
	
	}
	else{
	
	$jsonMessage = array();
	
	// Add new endorsement
	$addEndorsement = $db->prepare("INSERT INTO `endorsements`(`groupID`, `studentID`, `endorsedBy`) VALUES (:groupID, :endorseID, :studentID)");
	$addEndorsement->bindParam(":groupID",$groupID);
	$addEndorsement->bindParam(":studentID",$student->userID);
	$addEndorsement->bindParam(":endorseID",$endorseID);
	
	if($addEndorsement->execute())
	{
		$jsonMessage['result'] = "Successful";
		
	}
	else
	{
		$jsonMessage['result'] = "Unsuccessful";
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
	}
}


?>