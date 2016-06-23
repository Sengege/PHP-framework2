<?php

function getGroupInfo($groupID)
{
	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	
	$jsonArray = array();
	
	
	if(getGroupData($groupID)->rowCount()== 1)
	{
		//GROUP DATA
		$group = getGroupData($groupID)->fetch(PDO::FETCH_ASSOC);
		$jsonArray["groupData"] = array(
			"ID"=>$group['groupID'],
			"group_name"=>$group['groupName'],
			"group_description"=>$group['groupDescription'],
			"group_type"=>$group['type'],
			"module_ID"=>$group['moduleID'],
			"module_code"=>$group['module_code'],
			"module_name"=>$group['module_name']
			
		);
		
		// GROUP ADMIN 
		$jsonArray["groupAdmin"] = getStudentData($group['adminID'])->fetch(PDO::FETCH_ASSOC);
		
		// GROUP MEMBERS
		$jsonArray["groupMembers"] = array("totalMembers"=>getGroupMembers($groupID)->rowCount(),"memberData" => getGroupMembers($groupID)->fetchAll(PDO::FETCH_ASSOC));
		
		// MEETING DATA
		
		$meetings = getMeetingData($groupID)->fetchAll();
		$meetingData = array();
		foreach($meetings AS $meeting)
		{
			$roomArray = array();
			if($meeting['roomID'] != 'null')
			{
				$roomArray = array(
					"roomBooked" => "true",
					"roomID" => $meeting['roomID'],
					"roomNumber" => $meeting['room_number']);
			}
			else
			{
				$roomArray = array("roomBooked" => "false");
			}
			
			$studentsAttending = getAttendingMeeting($meeting['meetingID']);
			
			$meetingData[] = array(
				"ID" => $meeting['meetingID'],
				"time" => $meeting['time'],
				"duration" => $meeting['duration'],
				"roomData" => $roomArray,
				"facilitator" => getStudentData($meeting['facilitatorID'])->fetch(PDO::FETCH_ASSOC),
				"studentsAttending" => array("attendingNumber"=>$studentsAttending->rowCount(),"attendingData"=>$studentsAttending->fetchAll(PDO::FETCH_ASSOC))
			);	
		}
		
		$jsonArray["meetings"] = array("totalMeetings"=>getMeetingData($groupID)->rowCount(),"meetingData"=>$meetingData);
		
	
		
	}
	

	
	
	
	// Output JSON Message
	echo json_encode($jsonArray);
}

?>