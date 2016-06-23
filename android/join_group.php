<?php
require_once('../scripts/databaseConnect.php');
require_once('../scripts/class/studentClass.php');
$groupID=$_GET['groupID'];
$userID=$_GET['studentID'];
$student=new student($userID);
if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
// Find Group
	$findGroup = $db->prepare("SELECT * FROM `groups` WHERE `groupID` = :groupID");
	$findGroup->bindParam(':groupID',$groupID);
	$findGroup->execute();
	
	// Group does not exist
	if($findGroup->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Group does not exist"));
		return;
	}
	
	// Is group private?
	$groupData = $findGroup->fetch();
	if($groupData['type'] == 'private')
	{
		echo json_encode(array("result"=>"failed","message"=>"This group is private use other method"));
		return;
	}
	
// If user is already assigned
	foreach($student->assignedGroups() AS $assigned)
	{
		if($assigned['groupID'] == $groupID)
		{
			echo json_encode(array("result"=>"failed","message"=>"Already assigned to this group"));
			return;
		}
	}
	
	$time=time(); 
	$dateJoined=date("Y-m-d H:i:s",$time); 

	
	//echo $dateJoined;
	// JSON Message array
	$jsonArray = array();
	
	// Assign User to group
	$assign = $db->prepare("INSERT INTO `group_membership`(`groupID`, `studentID`, `dateJoined`) VALUES (:groupID,:studentID,:dateJoined)");
	$assign->bindParam(":groupID",$groupID);
	$assign->bindParam(":studentID",$student->userID);
	$assign->bindParam(":dateJoined",$dateJoined);
	$result = $assign->execute();
	
	if($result)
	{
		$jsonArray["result"] = "successful";
	}
	else
	{
		$jsonArray["result"] = "failed";
		$jsonArray["message"] = "Database Error";
	}
	
	
	// Output JSON Message
	echo json_encode($jsonArray);



?>