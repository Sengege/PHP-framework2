<?php

// User ID
$userID = 1;

function getGroups()
{
	global $db;
	global $noerrors;
	global $userID;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}

	// JSON Message array
	$jsonArray = array();
	
	// Number of assigned groups
	$jsonArray["group_number"]= COUNT(assignedGroups());
	// Collect data from assigned groups
	foreach(assignedGroups() AS $group)
	{
		$jsonArray["groups"][] = array(
			"ID"=>$group['groupID'],
			"group_name"=>$group['groupName'],
			"group_description"=>$group['groupDescription'],
			"group_type"=>$group['type'],
			"module_ID"=>$group['moduleID'],
			"module_code"=>$group['module_code'],
			"module_name"=>$group['module_name']
			
			);
	}
	// Number of suggested groups
	$jsonArray["suggested_number"] = COUNT(suggestedGroups());
	// Suggested Groups Data
	$jsonArray["suggestedGroups"] = suggestedGroups();
	
	
	// Output JSON Message
	echo json_encode($jsonArray);
}
?>