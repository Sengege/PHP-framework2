<?php

function deleteMeeting()
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
	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());

	$meetingID = 0;
	if(isset($jsonData->meetingID)) { $meetingID = intval($jsonData->meetingID); }
	
	if($meetingID == 0) { 
		echo json_encode(array("result"=>"Failed","error"=>"MeetingID missing"));		
		return;	
	} 
	
	
	
	// Check meeting exists
	$meetingCheck = $db->prepare("SELECT * FROM `meetings` WHERE `meetingID` = :meetingID");
	$meetingCheck->bindParam(":meetingID",$meetingID);
	$meetingCheck->execute();
	if($meetingCheck->rowCount() != 1)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Meeting does not exist"));		
		return;	
	}
	// Check student is admin
	$meetingData = $meetingCheck->fetch();
	
	//Check user is authorised to edit meeting - NOT GROUP ADMIN
	if(!isGroupAdmin($meetingData['groupID']))
	{
		echo json_encode(array("result"=>"Failed","message"=>"Not authorised to delete"));		
		return;
	}
	
	// Check if meeting has passed
	if(strtotime($meetingData['time']) < time())
	{
		echo json_encode(array("result"=>"Failed","message"=>"Can't delete passed meetings"));		
		return;
	}
	
	// Remove all attendees and meeting
	$db->beginTransaction();
	$sqlErrors = 0;
	// delete meeting_attending record
	$deleteAttendees = $db->prepare("DELETE FROM `meeting_attending` WHERE `meetingID` = :meetingID");
	$deleteAttendees->bindParam(":meetingID",$meetingID);
	if(!$deleteAttendees->execute()) { $sqlErrors++; }
	
	// Delete room booking is any 
	$deleteRoomBooking = $db->prepare("DELETE FROM `room_booking` WHERE `meetingID` = :meetingID");
	$deleteRoomBooking->bindParam(":meetingID",$meetingID);
	if(!$deleteRoomBooking->execute()) { $sqlErrors++; }
	
	// Delete notifications //
	//Find all notifications
	$notifications = $db->prepare("SELECT * FROM `notifications` WHERE `meetingID` = :meetingID");
	$notifications->bindParam(":meetingID",$meetingID);
	$notifications->execute();
	// Delete statements
	$deleteNotification = $db->prepare("DELETE FROM `notifications` WHERE `meetingID` = :meetingID");
	$deleteUserNotification = $db->prepare("DELETE FROM `user_notifications` WHERE `notificationID` = :notificationID");
	//Delete all user notifications
	foreach($notifications->fetchAll() AS $n)
	{
		$deleteUserNotification->bindParam(":notificationID", $n['notificationID']);
		$deleteUserNotification->execute();
	}
	// Delete notification
	$deleteNotification->bindParam(":meetingID", $meetingID);
	if(!$deleteNotification->execute()) { $sqlErrors++;}
	
	
	
	// Delete meeting	
	$deleteMeeting = $db->prepare("DELETE FROM `meetings` WHERE `meetingID` = :meetingID");
	$deleteMeeting->bindParam(":meetingID",$meetingID);
	if(!$deleteMeeting->execute()) { $sqlErrors++; }

	// If no SQL errors commit else rollback
	if($sqlErrors == 0)
	{
		$db->commit();
		addNotificationInc("Meeting Cancelled", $meetingData['groupID'], $student->userID, "");
		echo json_encode(array("result"=>"Successful"));
		return;
	}
	else
	{
		$db->rollBack();
		echo json_encode(array("result"=>"Failed","message"=>"Database Error"));
		return;
	}
	
}
function editMeeting()
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
	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	
	/*
	$change = '
	{"meetingID":"14","meetingName":"ldff","agenda":"ffghfjhfhgfjghfh","meetingDate":"22/05/2015","meetingTime":"01:00","currentRoomID":"","duration":150}
	';
	$jsonData = json_decode($change);
	*/
	$meetingID = 0;
	$meetingName = '';
	$agenda = '';
	$date = '';
	$time = '';
	$duration = 0;
	$roomChange = false;
	$roomCancel = false;
	$roomID = 0;
	
	
	// Store values from JSON OBject
	
	if(isset($jsonData->meetingName)) { $meetingName = $jsonData->meetingName; }
	if(isset($jsonData->agenda)) { $agenda = $jsonData->agenda; }
	if(isset($jsonData->meetingID)) { $meetingID = intval($jsonData->meetingID); }
	if(isset($jsonData->meetingDate)) { $date = $jsonData->meetingDate; }
	if(isset($jsonData->meetingTime)) { $time = $jsonData->meetingTime; }
	if(isset($jsonData->duration)) { $duration = intval($jsonData->duration); }
	if(isset($jsonData->roomChange)) { $roomChange = $jsonData->roomChange; }
	if(isset($jsonData->roomCancel)) { $roomCancel = $jsonData->roomCancel; }
	if(isset($jsonData->roomID)) { $roomID = intval($jsonData->roomID); }
	
	
	
	// Server Side Validation
	$errors = array();
	if($meetingName == '') { $errors[] = "meetingName";  } 
	if($agenda == '') { $errors[] = "agenda";  } 
	if($meetingID == 0) { $errors[] = "meetingID";  } 
	if($date == '') { $errors[] = "meetingDate";  } 
	if($time == '') { $errors[] = "meetingTime";  } 
	if($duration == 0) { $errors[] = "duration";  } 
	if(($roomChange) && $roomID == 0) { $errors[] = "roomID";  } 
	
	// If validation errors exist - display errors
	if(COUNT($errors) >0)
	{
		$errorMessage = array();
		$errorMessage["result"] = "Failed";
		$errorMessage["message"] = "Minimum core fields required";
		$errorMessage["missing"] = array();
		foreach($errors AS $error)
		{
			$errorMessage["missing"][] = $error;
		}
		echo json_encode($errorMessage);		
		return;
	}
	
	// Check meeting exists
	$meetingCheck = $db->prepare("SELECT * FROM `meetings` WHERE `meetingID` = :meetingID");
	$meetingCheck->bindParam(":meetingID",$meetingID);
	$meetingCheck->execute();
	if($meetingCheck->rowCount() != 1)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Meeting does not exist"));		
		return;	
	}
	// Check student is admin
	$meetingData = $meetingCheck->fetch();
	
	//Check user is authorised to edit meeting - NOT GROUP ADMIN
	if(!isGroupAdmin($meetingData['groupID']))
	{
		echo json_encode(array("result"=>"Failed","message"=>"Not authorised to edit"));		
		return;
	}
	// Check date and time are in correct format
	$dateBits = explode("/",$date);
	$timeBits = explode(":",$time);
	$validateFormat = false;
	if(!(COUNT($dateBits) == 3 && COUNT($timeBits) == 2)) 
	{ 
		echo json_encode(array("result"=>"Failed","message"=>"invalid date format"));
		return;	
	}
	
	// Check date is valid
	if(!checkdate( intval($dateBits[1]), intval($dateBits[0]) , intval($dateBits[2]) ))
	{
		echo json_encode(array("result"=>"Failed","message"=>"invalid date"));
		return;
	}
	
	// Make start and end timestamps with date,time and duration
	$timeStampStart = mktime(intval($timeBits[0]),intval($timeBits[1]),0,intval($dateBits[1]),intval($dateBits[0]),intval($dateBits[2]));
	$timeStampEnd = $timeStampStart + ($duration*60);
	$startTime = date("Y-m-d H:i:s",$timeStampStart);
	$endTime = date("Y-m-d H:i:s",$timeStampEnd);
	
	if($timeStampStart < time())
	{
		echo json_encode(array("result"=>"Failed","message"=>"Meeting date must be in future"));
		return;
	}
	
	// Remove Room booking if requested
	if($roomCancel)
	{
		$deleteRoomBooking = $db->prepare("DELETE FROM `room_booking` WHERE `meetingID` = :meetingID");
		$deleteRoomBooking->bindParam(":meetingID",$meetingID);
		$deleteRoomBooking->execute(); 
	}
	
	// Check room is booked
	$findRoomBooking = $db->prepare("SELECT * FROM `room_booking` WHERE `meetingID` = :meetingID");
	$findRoomBooking->bindParam(":meetingID",$meetingID);
	$findRoomBooking->execute();
	$fetchBooking = $findRoomBooking->fetch();
	
	
	
	
	// No Room booking - Quick update - no checks required
	if($findRoomBooking->rowCount() == 0 && (!$roomChange))
	{
		$updateMeeting = $db->prepare("UPDATE `meetings` SET `meetingName` = :meetingName,`agenda` =:agenda, `time`= :startTime,`duration`= :duration WHERE `meetingID` = :meetingID");
		$updateMeeting->bindParam(":meetingName",$meetingName);
		$updateMeeting->bindParam(":agenda",$agenda);
		$updateMeeting->bindParam(":startTime",$startTime);
		$updateMeeting->bindParam(":duration",$duration);
		$updateMeeting->bindParam(":meetingID",$meetingID);
		if($updateMeeting->execute())
		{
			echo json_encode(array("result"=>"Successful"));
			return;
		}
		else
		{
			echo json_encode(array("result"=>"Failed","message"=>"Database Error"));
			return;
		}
	}
	else
	{
		//Room is booked
		// IF no room change
		if(!$roomChange)
		{
			
			if(isRoomFree($fetchBooking['roomID'],$startTime,$endTime,$fetchBooking['bookingID']))
			{
				$updateMeeting = $db->prepare("UPDATE `meetings` SET `meetingName` = :meetingName,`agenda` =:agenda, `time`= :startTime,`duration`= :duration WHERE `meetingID` = :meetingID");
				$updateMeeting->bindParam(":meetingName",$meetingName);
				$updateMeeting->bindParam(":agenda",$agenda);
				$updateMeeting->bindParam(":startTime",$startTime);
				$updateMeeting->bindParam(":duration",$duration);
				$updateMeeting->bindParam(":meetingID",$meetingID);
				if($updateMeeting->execute())
				{
					echo json_encode(array("result"=>"Successful"));
					return;
				}
				else
				{
					echo json_encode(array("result"=>"Failed","message"=>"Database Error"));
					return;
				}
			}
			else
			{
				echo json_encode(array("result"=>"Failed","message"=>"Room not available for new time"));
				return;
			}
		}
		else
		{
			if(isRoomFree($roomID,$startTime,$endTime))
			{
				
				// Start DB transaction
				$db->beginTransaction();
				$sqlErrors = 0;
				// update meeting record
				$updateMeeting = $db->prepare("UPDATE `meetings` SET `meetingName` = :meetingName,`agenda` =:agenda, `time`= :startTime,`duration`= :duration WHERE `meetingID` = :meetingID");
				$updateMeeting->bindParam(":meetingName",$meetingName);
				$updateMeeting->bindParam(":agenda",$agenda);
				$updateMeeting->bindParam(":startTime",$startTime);
				$updateMeeting->bindParam(":duration",$duration);
				$updateMeeting->bindParam(":meetingID",$meetingID);
				if(!$updateMeeting->execute()) { $sqlErrors++; }
				
				if($findRoomBooking->rowCount() == 1)
				{
					// Update existing room_booking record
					$updateMeeting = $db->prepare("UPDATE `room_booking` SET `roomID` = :roomID WHERE `bookingID` = :bookingID");
					$updateMeeting->bindParam(":bookingID",$fetchBooking['bookingID']);
					$updateMeeting->bindParam(":roomID",$roomID);
					if(!$updateMeeting->execute()) { $sqlErrors++; }
				}
				else
				{
					// Insert new room booking
					$insertBooking = $db->prepare("INSERT INTO `room_booking`(`meetingID`, `roomID`) VALUES (:meetingID,:roomID)");
					$insertBooking->bindParam(":meetingID",$meetingID);
					$insertBooking->bindParam(":roomID",$roomID);
					if(!$insertBooking->execute()) { $sqlErrors++; }
				}
				
			
				// If no SQL errors commit else rollback
				if($sqlErrors == 0)
				{
					$db->commit();
					echo json_encode(array("result"=>"Successful"));
					return;
				}
				else
				{
					$db->rollBack();
					echo json_encode(array("result"=>"Failed","message"=>"Database Error"));
					return;
				}
			}
			else
			{
				echo json_encode(array("result"=>"Failed","message"=>"New room not available for new time"));
				return;
			}
		}
		 
	}
	
	
	
}
// Booking ID is optional - 
function isRoomFree($roomID,$startTime,$endTime,$bookingID = 0)
{
	global $student;
	global $db;
	$SQL1 = "
			SELECT *, DATE_ADD(b.time, INTERVAL b.duration MINUTE) AS endTime 
			FROM `room_booking` a 
			INNER JOIN `meetings` b ON a.meetingID = b.meetingID
			WHERE b.time < :endTime AND a.roomID = :roomID";
	$SQL2 = " AND a.bookingID != :bookingID ";
	$SQL3 = " HAVING endTime > :startTime ";
	
	if($bookingID != 0) { $SQLstatement = $SQL1.$SQL2.$SQL3; } else { $SQLstatement = $SQL1.$SQL3; }
		
		$checkBooking = $db->prepare($SQLstatement);
		$checkBooking->bindParam(":roomID",$roomID);
		$checkBooking->bindParam(":startTime",$startTime);
	if($bookingID != 0) { $checkBooking->bindParam(":bookingID",$bookingID); }
		$checkBooking->bindParam(":endTime",$endTime);
		$checkBooking->execute();
	$return = true;
		if($checkBooking->rowCount() == 1) { $return = false;}
	return $return;
}
?>