<?php

function getAllMessages($groupID) {
	global $db;
	global $student;
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
	
	/*VALIDATION GROUP - including private groups*/
	$groupQ = $db->prepare("SELECT * FROM `groups` WHERE `groupID` = :groupID");
	$groupQ->bindParam(":groupID",$groupID);
	$groupQ->execute();
	if($groupQ->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Group does not exist"));
		return;	
	}
	// Check if is private
	$groupData = $groupQ->fetch();
	if($groupData['type'] == 'private')
	{
		// Check student is member of private group
		$q = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID AND `studentID` = :studentID");
		$q->bindParam(":groupID",$groupID);
		$q->bindParam(":studentID",$student->userID);
		$q->execute();
		if($q->rowCount() != 1)
		{
			echo json_encode(array("result"=>"failed","message"=>"Not member of private group"));
			return;
		}
		
	}
	
	// JSON Message Array
	$jsonMessage = array();
	$getMessages = $db->prepare("SELECT * FROM `group_message` a INNER JOIN `students` b ON a.studentID = b.studentID WHERE a.groupID = :groupID");
	$getMessages->bindParam(":groupID",$groupID);
	$getMessages->execute();
	
	
	$fetchMessages = $getMessages->fetchAll();
	
	$jsonMessage['result'] = 'Successful';
	$jsonMessage['messageNumber'] = $getMessages->rowCount();
	$jsonMessage['messages'] = array();
	
	foreach($fetchMessages AS $message)
	{
		$messageData = array();
		$messageData["ID"] = $message['messageID'];
		$messageData["firstName"] = $message['first_name'];
		$messageData["lastName"] = $message['last_name'];
		$messageData["postDate"] = chatDateFormat($message['post_date']);
		$messageData["message"] = $message['message'];
		// Add object to messages array
		$jsonMessage["messages"][] = $messageData;
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}

function checkNewMessages($groupID,$lastMessageID)
{
	global $db;
	global $student;
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
	
	/*VALIDATION GROUP - including private groups*/
	$groupQ = $db->prepare("SELECT * FROM `groups` WHERE `groupID` = :groupID");
	$groupQ->bindParam(":groupID",$groupID);
	$groupQ->execute();
	if($groupQ->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Group does not exist"));
		return;	
	}
	// Check if is private
	$groupData = $groupQ->fetch();
	if($groupData['type'] == 'private')
	{
		// Check student is member of private group
		$q = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID AND `studentID` = :studentID");
		$q->bindParam(":groupID",$groupID);
		$q->bindParam(":studentID",$student->userID);
		$q->execute();
		if($q->rowCount() != 1)
		{
			echo json_encode(array("result"=>"failed","message"=>"Not member of private group"));
			return;
		}
		
	}
	
	$getLastMessage = $db->prepare("SELECT * FROM `group_message` WHERE `messageID` = :messageID AND `groupID` = :groupID");
	$getLastMessage->bindParam(":messageID",$lastMessageID);
	$getLastMessage->bindParam(":groupID",$groupID);
	$getLastMessage->execute();
	
	if($getLastMessage->rowCount() == 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Last message does not exist"));
		return;
	}
	// JSON Message Array
	$jsonMessage = array();
	
	// Fetch last message and its time of post
	$fetchLastMessage = $getLastMessage->fetch();
	$lastMessageTime = $fetchLastMessage['post_date'];
	
	// Find new messages after last message
	$newMessages = $db->prepare("SELECT * FROM `group_message` a INNER JOIN `students` b ON a.studentID = b.studentID WHERE a.groupID = :groupID AND a.post_date > :lastPostDate");
	$newMessages->bindParam(":groupID",$groupID);
	$newMessages->bindParam(":lastPostDate",$lastMessageTime);
	$newMessages->execute();
	
	$fetchMessages = $newMessages->fetchAll();
	
	$jsonMessage['result'] = 'Successful';
	$jsonMessage['newMessageNumber'] = $newMessages->rowCount();
	$jsonMessage['messages'] = array();
	
	foreach($fetchMessages AS $message)
	{
		$messageData = array();
		$messageData["ID"] = $message['messageID'];
		$messageData["firstName"] = $message['first_name'];
		$messageData["lastName"] = $message['last_name'];
		$messageData["postDate"] = chatDateFormat($message['post_date']);
		$messageData["message"] = $message['message'];
		// Add object to messages array
		$jsonMessage["messages"][] = $messageData;
	}

	// echo JSON Message
	echo json_encode($jsonMessage);

}

function addMessage()
{
	global $db;
	global $student;
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
	$message = '';
	if(isset($jsonData->groupID)) { $groupID = $jsonData->groupID;}
	if(isset($jsonData->message)) { $message = htmlspecialchars($jsonData->message);}
	
	if(strlen($message) == 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"No message provided"));
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
	
	// Check if user is member of group
	$checkMembership = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID AND `studentID` = :studentID");
	$checkMembership->bindParam(":groupID",$groupID);
	$checkMembership->bindParam(":studentID",$student->userID);
	$checkMembership->execute();
	
	if($checkMembership->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Not a member of this group"));
		return;
	}

	
	
	$jsonMessage = array();
	
	// Add new message
	$postDate = date("Y-m-d H:i:s");
	$addMessage = $db->prepare("INSERT INTO `group_message`(`groupID`, `studentID`, `message`, `post_date`) VALUES (:groupID,:studentID,:message,:postDate)");
	$addMessage->bindParam(":groupID",$groupID);
	$addMessage->bindParam(":studentID",$student->userID);
	$addMessage->bindParam(":message",$message);
	$addMessage->bindParam(":postDate",$postDate);
	
	if($addMessage->execute())
	{
		$jsonMessage['result'] = "Successful";
		
	}
	else
	{
		$jsonMessage['result'] = "Successful";
		$jsonMessage['message'] = "Database Error";
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
 


}

// Returns Date format for chat messages
function chatDateFormat($date)
{
	$startOfDay = strtotime("midnight");
	$postDate = strtotime($date);
	$return = '';												
	if( $postDate < $startOfDay)
	{
		// Not Today
		$return = date("jS M, g:i a",$postDate);
	}
	else
	{
		// Today
		$return = date("g:i a",$postDate);
	}
	
	return $return;
}
?>