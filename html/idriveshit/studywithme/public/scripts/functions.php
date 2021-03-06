<?php

/*
*
* Functions File
* Contains SQL Functions
*
*/

	
	function assignedGroups()
	{
		/* Returns assigned group data from database */
		global $db;
		global $userID;
		
		// Find Groups assigned to
		$query = "SELECT * FROM `group_connect` a INNER JOIN `groups` b ON a.groupID = b.groupID 
		INNER JOIN `modules` c ON c.moduleID = b.moduleID WHERE a.studentID = ?";
		
		$q = $db->prepare($query);
		$q->execute(array($userID));
		$groupData = $q->fetchAll();
		
		return $groupData;
	}
	
	function currentlyStudying()
	{
		/* Returns modules currently studying  */
		global $db;
		global $userID;
		$studying = array();
		
		// Store modules that student studies
		$query = "SELECT `moduleID` FROM `studying` WHERE `studentID` = ?";
		$q = $db->prepare($query);
		$q->execute(array($userID));
		
		foreach($q->fetchAll() AS $s)
		{
			$studying[] = $s['moduleID'];
		}
		return $studying;
	}
	
	function suggestedGroups()
	{
		/* Returns group data related to currently studying */
		global $db;
		global $userID;
		$suggestedArray = array();
		
		foreach(currentlyStudying() AS $module)
		{
			$query = "SELECT *,(SELECT COUNT(*) FROM group_connect b WHERE b.studentID = :studentID AND b.groupID = a.groupID) AS joined  FROM `groups` a INNER JOIN `modules` c ON c.moduleID = a.moduleID WHERE a.moduleID = :moduleID HAVING `joined` = '0'";
			$q = $db->prepare($query);
			$q->bindParam("studentID", $userID); 
			$q->bindParam("moduleID", $module);
			$q->execute();
			
			if($q->rowCount() == 1)
			{
				$suggestedData = $q->fetch();
				$suggestedArray[] = array(
					"ID"=>$suggestedData['groupID'],
					"group_name"=>$suggestedData['groupName'],
					"group_description"=>$suggestedData['groupDescription'],
					"group_type"=>$suggestedData['type'],
					"module_ID"=>$suggestedData['moduleID'],
					"module_code"=>$suggestedData['module_code'],
					"module_name"=>$suggestedData['module_name']
				);
				
			}
			
		}
		
		return $suggestedArray;
	}
	
	function getStudentData($studentID)
	{
		/* Return single student name and email data */
		global $db;
				
		$query = "SELECT studentID,first_name,last_name,email FROM `students` WHERE `studentID` = ?";
		$q = $db->prepare($query);
		$q->execute(array($studentID));
		
		return $q;
		
	}
	
	function getGroupData($groupID)
	{
		/* Returns group data of any group*/
		global $db;
				
		// Find Group
		$query = "SELECT * FROM `groups` a 	INNER JOIN `modules` b ON b.moduleID = a.moduleID WHERE a.groupID = ?";
		$q = $db->prepare($query);
		$q->execute(array($groupID));
		
		return $q;
	}
	
	function getGroupMembers($groupID)
	{
		/* Returns group data of any group*/
		global $db;
				
		// Find Group
		$query = "SELECT b.studentID,b.first_name,b.last_name,b.email FROM `group_connect` a INNER JOIN `students` b ON a.studentID = b.studentID WHERE a.groupID = ?";
		$q = $db->prepare($query);
		$q->execute(array($groupID));
		
		return $q;
	}
	
	function getMeetingData($groupID)
	{
		/* Returns group data of any group*/
		global $db;
		
		$query = "SELECT * FROM `meetings` a LEFT JOIN `rooms` b ON a.roomID = b.roomID WHERE a.groupID = ?"; 
		$q = $db->prepare($query);
		$q->execute(array($groupID));
		
		return $q;
		
	}
	
	function getAttendingMeeting($meetingID)
	{
		/* Returns group data of any group*/
		global $db;
		
		$query = "SELECT b.studentID,b.first_name,b.last_name,b.email FROM `meeting_connect` a INNER JOIN `students` b ON a.studentID = b.studentID WHERE a.meetingID = ?"; 
		$q = $db->prepare($query);
		$q->execute(array($meetingID));
		
		return $q;
		
	
	}
	
?>

