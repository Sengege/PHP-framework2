<?php
    include 'prepend.php';
    error_reporting(E_ALL);
    
    $notificationID = $_POST['notificationID'];
    readNotification($notificationID);

    
    
    function readNotification($notificationID) {
    	global $db;
    	global $noerrors;
    	global $student;
    	
    	$studentID = $student-> userID; //already got this!
    	//echo $studentID;
    	//echo $notificationID;
    	
    	if($noerrors <> 0)
    	{
    		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
    		return;
    	}
    	
    	//$request = Slim\Slim::getInstance()->request();
    	//$body = $request->getBody();
    	//$jsonData = json_decode($request->getBody());
    	
    
    	
    	//logic
    	//get the details for the notification
    	$getNotificationDetailsQuery = "SELECT *  FROM `notifications` WHERE `notificationID` = :notificationID";
    	$getNotificationDetails = $db->prepare($getNotificationDetailsQuery);
    	$getNotificationDetails->bindParam(':notificationID', $notificationID);
    	$getNotificationDetails->execute();
    	$notificationDetails = $getNotificationDetails->fetchAll();

        foreach($notificationDetails as $notification){
    	$groupID = $notification['groupID'];
    	$meetingID = $notification['meetingID'];
    	$notificationType = $notification['notificationType'];
    	}
    	
    	
    		// Server Side Validation
    	$errors = array();
    	if($studentID == '') { $errors[] = "studentID Required";  } 
    	if($groupID == '') { $errors[] = "GroupID Required";  } 
    	if($notificationType == '') { $errors[] = "type Required";  } 
    	if($notificationID == '') { $errors[] = "notificationID Required";  }
    
    	
    
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
    	
    	$markReadList = array();
    	
    	//get notifications that are the same type in the same group/meeting if not null for the signed in student
    	if($meetingID != null){
    	    //get similar meeting notification for this user
    		$getSimilarMeetingNotificationQuery = "SELECT a.notificationID FROM  `notifications` a INNER JOIN  `user_notifications` b ON a.notificationID = b.notificationID WHERE a.meetingID = :meetingID AND b.studentID = :studentID AND a.notificationType =  :notificationType";
    	    $getSimilarMeetingNotification = $db->prepare($getSimilarMeetingNotificationQuery);
    	    $getSimilarMeetingNotification->bindParam(':meetingID', $meetingID);
    	    $getSimilarMeetingNotification->bindParam(':studentID', $studentID);
    	    $getSimilarMeetingNotification->bindParam(':notificationType', $notificationType);
            $getSimilarMeetingNotification->execute();
            $markReadList = $getSimilarMeetingNotification->fetchAll();
        }
    	else{
             //get similar group notification for this user
    		$getSimilarGroupNotificationQuery = "SELECT a.notificationID FROM  `notifications` a INNER JOIN  `user_notifications` b ON a.notificationID = b.notificationID WHERE a.groupID = :groupID AND b.studentID = :studentID AND a.notificationType =  :notificationType";
    	    $getSimilarGroupNotification = $db->prepare($getSimilarGroupNotificationQuery);
    	    $getSimilarGroupNotification->bindParam(':groupID', $groupID);
    	    $getSimilarGroupNotification->bindParam(':studentID', $studentID);
    	    $getSimilarGroupNotification->bindParam(':notificationType', $notificationType);
            $getSimilarGroupNotification->execute();
            $markReadList = $getSimilarGroupNotification->fetchAll();
    	}
    	
    	$jsonMessage = array();
    	
    	//for all of these notification ID's update the read value to 1
    		
    		// Add userNotification row
    		$s = $db->prepare("UPDATE `user_notifications` SET `notificationRead`= 1 WHERE `notificationID` = :notificationID AND `studentID` = :studentID");
    		
    		
    		
    		// If students have been chosen - add them to user notification
    		foreach($markReadList AS $notification)
    		{
    		$s->bindParam(':notificationID', $notification['notificationID']);
    		$s->bindParam(':studentID', $studentID);
    		
    		if($s->execute()){
    		// Return Success Message
    		$jsonMessage["result"] = "successful";
    		}
    		else{
    		// Return Success Message
    		$jsonMessage["result"] = "unsuccessful";
    		}
    	
    		}
    	
    	// echo JSON Message
    	echo json_encode($jsonMessage);
    	
    }
        
?>