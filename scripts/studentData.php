<?php
	function getAllStudentData()
	{
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
		
		$jsonMessage = array();
		
		$jsonMessage["studentID"] = $student->userID;
		$jsonMessage["universityID"] = $student->universityID;
		$jsonMessage["schoolID"] = $student->schoolID;
		$jsonMessage["universityName"] = $student->universityName;
		$jsonMessage["schoolName"] = $student->schoolName;
		$jsonMessage["language"] = $student->language;
		$jsonMessage["firstName"] = $student->firstName;
		// Last Name only shown on english students
		if($student->language == 'EN') { $jsonMessage["lastName"] = $student->lastName; }
		$jsonMessage["DOB"] = $student->DOB;
		$jsonMessage["bio"] = urlencode($student->bio);
		$jsonMessage["email"] = $student->email;
		$jsonMessage["username"] = $student->username;
		$jsonMessage["active"] = $student->active;
		
		
		foreach($student->currentlyStudyingFull() AS $study)
		{
			$studying = array();
			$studying["moduleID"] = $study['moduleID'];
			$studying["moduleName"] = $study['moduleName'];
			$studying["moduleCode"] = $study['moduleCode'];
			$jsonMessage["studying"][] = $studying;
		}
		
		foreach($student->assignedGroups() AS $group)
		{
			$assisgnedGroup = array();
			$assisgnedGroup["groupID"] = $group['groupID'];
			$assisgnedGroup["groupName"] = $group['groupName'];
			$assisgnedGroup["groupDescription"] = urlencode($group['groupDescription']);
			$assisgnedGroup["type"] = $group['type'];
			$assisgnedGroup["dateJoined"] = $group['dateJoined'];
			$assisgnedGroup["moduleID"] = $group['moduleID'];
			$assisgnedGroup["moduleName"] = $group['module_name'];
			if($group['adminID'] == $student->userID) {	$assisgnedGroup["admin"] = true; } else { $assisgnedGroup["admin"] = false; }
			
			$jsonMessage["groups"][] = $assisgnedGroup;
		}
		
		// Echo json code
		header('Content-Type: text/html; charset=utf-8');
		echo json_encode($jsonMessage);
		
	}
?>