<?php

function getGroups()
{
	global $student;
	global $db;
	global $noerrors;
	global $userID;
	
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

	// JSON Message array
	$jsonArray = array();
	
	// Number of assigned groups
	$jsonArray["group_number"]= COUNT($student->assignedGroups());
	
	// Collect data from assigned groups
	foreach($student->assignedGroups() AS $group)
	{
		$jsonArray["groups"][] = array(
			"ID"=>$group['groupID'],
			"group_name"=>$group['groupName'],
			"group_description"=> urlencode($group['groupDescription']),
			"group_type"=>$group['type'],
			"module_ID"=>$group['moduleID'],
			"module_code"=>$group['module_code'],
			"module_name"=>$group['module_name']
			
			);
	}
	// Number of suggested groups
	$jsonArray["suggested_number"] = COUNT($student->suggestedGroups());
	// Suggested Groups Data
	$jsonArray["suggestedGroups"] = $student->suggestedGroups();
	
	
	// Output JSON Message
	echo json_encode($jsonArray);
}
?>