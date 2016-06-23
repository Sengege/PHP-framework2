<?php
    include 'prepend.php';
    error_reporting(E_ALL);
    
    $notificationType = $_POST['notificationType'];
    $groupID = $_POST['groupID'];
    $studentID = $_POST['studentID'];
    $meetingID = $_POST['meetingID'];
    addNotification($notificationType, $groupID, $studentID, $meetingID);
    
function addNotification($notificationType, $groupID, $studentID, $meetingID) {
    
	global $db;
	global $noerrors;
	global $student;
	
	if($meetingID == ''){
	$meetingID = null;
	}
	
	if($noerrors <> 0)
	{
		//echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	//$request = Slim\Slim::getInstance()->request();
	//$body = $request->getBody();
	//$jsonData = json_decode($request->getBody());
	
	// Server Side Validation
	$errors = array();
	if($studentID == '') { $errors[] = "studentID Required";  } 
	if($groupID == '') { $errors[] = "Group ID Required";  }
	if($notificationType == '') { $errors[] = "Type of notification Required";  }

	

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
		//echo json_encode($errorMessage);		
		//return;
	}
	
	$notifyingMembers = array();
	
	if($meetingID != null){
	    //get all attendees of the meeting excluding notifying attendee
		$getMeetingMembersQuery = "SELECT `studentID` FROM `meeting_attending` WHERE  `meetingID` = :meetingID AND `studentID` != :studentID ";
	    $meetingMembers = $db->prepare($getMeetingMembersQuery);
	    $meetingMembers->bindParam(':meetingID', $meetingID);
	    $meetingMembers->bindParam(':studentID', $studentID);
        $meetingMembers->execute();
        $notifyingMembers = $meetingMembers->fetchAll();
        
    }
	else{
	   
         // Get all users of that group excluding notifying member
	    $getGroupMembersQuery = "SELECT `studentID` FROM `group_membership` WHERE  `groupID` = :groupID AND `studentID` != :studentID";
    	$groupMembers = $db->prepare($getGroupMembersQuery);
	    $groupMembers->bindParam(':groupID', $groupID);
	    $groupMembers->bindParam(':studentID', $studentID);
	    $groupMembers->execute();
        $notifyingMembers = $groupMembers->fetchAll();
        
	}
	
	//insert 
	$insertNotification = "INSERT INTO `notifications`(`notificationDate`, `groupID`, `meetingID`, `notificationType`, `notifyingStudent`) VALUES (now(), :groupID, :meetingID, :notificationType, :notifyingStudent)";
	$q = $db->prepare($insertNotification);
	$q->bindParam(':groupID', $groupID);
	$q->bindParam(':meetingID', $meetingID);
	$q->bindParam(':notificationType', $notificationType);
	$q->bindParam(':notifyingStudent', $studentID);
	// JSON Message Array
	$jsonMessage = array();
	
	// If student insert execute is successful
	if($q->execute())
	{
		
		// Get Last Insert ID
		$notificationID = $db->lastInsertId();
		
		// Add userNotification row
		$s = $db->prepare("INSERT INTO `user_notifications`(`studentID`, `notificationID`,`notificationRead`) VALUES (:studentID,:notificationID,0)");
		
		
		// If students have been chosen - add them to user notification
		foreach($notifyingMembers AS $notifying)
		{
		$s->bindParam(':studentID', $notifying['studentID']);
		$s->bindParam(':notificationID', $notificationID);
		$s->execute();
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