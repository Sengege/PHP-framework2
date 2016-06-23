<?php
header("Content-type: application/json");
require_once('../scripts/databaseConnect.php');
require_once('../scripts/class/studentClass.php');
$groupID=$_GET['groupID'];
$studentID=$_GET['studentID'];
$student=new student($studentID);
getMeetings($groupID,$db,$student,$noerrors);
function getMeetings($groupID,$db,$student,$noerrors)
{
	global $db;
	global $student;
	global $noerrors;
	
	// If database connect details are incorrect
	if($noerrors <> 0)
	{	
		echo json_encode(array("result"=>"Failed","error"=>"No connection"));		
		return;	
	}	
	
	if(!$student->userExists())
	{
		echo json_encode(array("result"=>"failed","message"=>"User not logged in"));
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
	// Private group check
	/*
	if(!isGroupMember($groupID))
	{
		
		echo json_encode(array("result"=>"Failed","message"=>"Not member of this group"));		
		return;
	}*/
	
	// JSON Message Array
	$jsonMessage = array();
	
	//Query get all meeting (LEFT JOIN) booking and rooms tables
	$meetingSQL = "SELECT *,a.meetingID AS meetingUID FROM `meetings` a 
	INNER JOIN `students` b ON a.facilitatorID = b.studentID 
	LEFT  JOIN `room_booking` c ON c.meetingID = a.meetingID
	LEFT JOIN `rooms` d ON c.roomID = d.roomID
	WHERE a.groupID = :groupID
	ORDER BY a.time DESC";
	$meetings = $db->prepare($meetingSQL);
	$meetings->bindParam(":groupID",$groupID);
	$meetings->execute();
	$fetchMeetings = $meetings->fetchAll();
	
	$jsonMessage['result'] = "Successful";
	$jsonMessage['meetingNumber'] = $meetings->rowCount();
	$jsonMessage['meetings'] = array();
	
	foreach($fetchMeetings AS $meeting)
	{
		// Object to store meeting data
		$meetingData = array();
		
		// Query to get students attending meeting
		$attending = $db->prepare("SELECT * FROM `meeting_attending` a INNER JOIN `students` b ON a.studentID = b.studentID WHERE a.meetingID = :meetingID");
		$attending->bindParam(":meetingID",$meeting['meetingUID']);
		$attending->execute();
		
		
		$meetingData['meetingID'] = $meeting['meetingUID'];
		$meetingData['name'] = $meeting['meetingName'];
		$meetingData['agenda'] = $meeting['agenda'];
		$meetingData['facilitator'] = array("studentID" =>$meeting['studentID'],"firstName" =>$meeting['first_name'],"lastName" =>$meeting['last_name']);
		$meetingData['attendingNumber'] = $attending->rowCount();
		$meetingData['attendees'] = array();
		$meetingData['isAttending'] = false;
		// Add attendees
		foreach($attending->fetchAll() AS $s)
		{
			if($s['studentID'] == $student->userID) { $meetingData['isAttending'] = true; }
			$studentData = array("studentID" =>$s['studentID'],"first_name" =>$s['first_name'],"last_name" =>$s['last_name']);
			$meetingData['attendees'][] =  $studentData;
		}
		$meetingData['facilitator'] = array("studentID" =>$meeting['studentID'],"first_name" =>$meeting['first_name'],"last_name" =>$meeting['last_name']);
		$meetingData['meetingDay'] = date("l", strtotime($meeting['time']));
		$meetingData['meetingDateShort'] = date("d/m/Y", strtotime($meeting['time']));
		$meetingData['meetingDateLong'] = date("jS F Y", strtotime($meeting['time']));
		$meetingData['startTime'] = date("g:ia", strtotime($meeting['time']));
		$endTime = strtotime('+ '.intval($meeting['duration']).' minutes',strtotime($meeting['time']));
		$meetingData['endTime'] = date("g:ia", $endTime);
		$meetingData['room'] = array("roomID" => $meeting['roomID'],"description"=>$meeting['description']);
		if($endTime < time()) { $meetingData['meetingFinished'] = true; } else { $meetingData['meetingFinished'] = false; }
		$jsonMessage['meetings'][] = $meetingData;
		
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}
?>