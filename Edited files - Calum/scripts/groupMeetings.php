<?php

function cancelAttendMeeting($meetingID)
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
	
	// Check if already attending meeting
	$attendingCheck = $db->prepare("SELECT * FROM `meeting_attending` WHERE `meetingID` = :meetingID AND `studentID` = :studentID");
	$attendingCheck->bindParam(":meetingID",$meetingID);
	$attendingCheck->bindParam(":studentID",$student->userID);
	$attendingCheck->execute();
	if($attendingCheck->rowCount() != 1)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Not attending this meeting"));		
		return;
	}
	
	// Remove attendance
	$removeAttend = $db->prepare("DELETE FROM `meeting_attending` WHERE `meetingID` = :meetingID AND `studentID` = :studentID");
	$removeAttend->bindParam(":meetingID",$meetingID);
	$removeAttend->bindParam(":studentID",$student->userID);
	if($removeAttend->execute())
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

function attendMeeting($meetingID)
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
	
	// Check meeting exists
	$meetingCheck = $db->prepare("SELECT * FROM `meetings` WHERE `meetingID` = :meetingID");
	$meetingCheck->bindParam(":meetingID",$meetingID);
	$meetingCheck->execute();
	$fetchMeeting = $meetingCheck->fetch();
	if($meetingCheck->rowCount() != 1)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Meeting does not exist"));		
		return;	
	}
	if(!isGroupMember($fetchMeeting['groupID']))
	{
		echo json_encode(array("result"=>"Failed","message"=>"Not member of group"));		
		return;
	}
	
	// Check if already attending meeting
	$attendingCheck = $db->prepare("SELECT * FROM `meeting_attending` WHERE `meetingID` = :meetingID AND `studentID` = :studentID");
	$attendingCheck->bindParam(":meetingID",$meetingID);
	$attendingCheck->bindParam(":studentID",$student->userID);
	$attendingCheck->execute();
	if($attendingCheck->rowCount() != 0)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Already attending"));		
		return;
	}
	
	// ADD meeting attend row
	$insert = $db->prepare("INSERT INTO `meeting_attending`(`meetingID`, `studentID`) VALUES (:meetingID,:studentID)");
	$insert->bindParam(":meetingID",$meetingID);
	$insert->bindParam(":studentID",$student->userID);
	if($insert->execute())
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


function getMeetings($groupID)
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
	
	if(!isGroupMember($groupID))
	{
		
		echo json_encode(array("result"=>"Failed","message"=>"Not member of this group"));		
		return;
	}
	
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
			$studentData = array("studentID" =>$s['studentID'],"firstName" =>$s['first_name'],"lastName" =>$s['last_name']);
			$meetingData['attendees'][] =  $studentData;
		}
		$meetingData['facilitator'] = array("studentID" =>$meeting['studentID'],"firstName" =>$meeting['first_name'],"lastName" =>$meeting['last_name']);
		$meetingData['meetingDay'] = date("l", strtotime($meeting['time']));
		$meetingData['meetingDateShort'] = date("d/m/Y", strtotime($meeting['time']));
		$meetingData['meetingDateLong'] = date("jS F Y", strtotime($meeting['time']));
		$meetingData['startTime'] = date("g:ia", strtotime($meeting['time']));
		$endTime = strtotime('+ '.intval($meeting['duration']).' minutes',strtotime($meeting['time']));
		$meetingData['endTime'] = date("g:ia", $endTime);
		$meetingData['room'] = array("roomID" => $meeting['roomID'],"roomName"=>$meeting['room_number']);
		if($endTime < time()) { $meetingData['meetingFinished'] = true; } else { $meetingData['meetingFinished'] = false; }
		$jsonMessage['meetings'][] = $meetingData;
		
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}
function addMeeting() {
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
	
	
	
	$groupID = 0;
	$meetingName = '';
	$agenda = '';
	$date = '';
	$time = '';
	$duration = 0;
	$roomBooking = false;
	$roomID = 0;
	
	
	// Store values from JSON OBject
	
	if(isset($jsonData->meetingName)) { $meetingName = $jsonData->meetingName; }
	if(isset($jsonData->agenda)) { $agenda = $jsonData->agenda; }
	if(isset($jsonData->groupID)) { $groupID = intval($jsonData->groupID); }
	if(isset($jsonData->meetingDate)) { $date = $jsonData->meetingDate; }
	if(isset($jsonData->meetingTime)) { $time = $jsonData->meetingTime; }
	if(isset($jsonData->duration)) { $duration = intval($jsonData->duration); }
	if(isset($jsonData->roomBooking)) { $roomBooking = $jsonData->roomBooking; }
	if(isset($jsonData->roomID)) { $roomID = intval($jsonData->roomID); }
	
	
	
	// Server Side Validation
	$errors = array();
	if($meetingName == '') { $errors[] = "meetingName";  } 
	if($agenda == '') { $errors[] = "agenda";  } 
	if($groupID == 0) { $errors[] = "groupID";  } 
	if($date == '') { $errors[] = "meetingDate";  } 
	if($time == '') { $errors[] = "meetingTime";  } 
	if($duration == 0) { $errors[] = "duration";  } 
	if(($roomBooking) && $roomID == 0) { $errors[] = "roomID";  } 
	
	
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
	
	
	// Check group exists
	$groupCheck = $db->prepare("SELECT * FROM `groups` WHERE `groupID` = :groupID");
	$groupCheck->bindParam(":groupID",$groupID);
	$groupCheck->execute();
	if($groupCheck->rowCount() != 1)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Group does not exist"));		
		return;	
	}
	// Check student is admin
	$groupData = $groupCheck->fetch();
	if($groupData['adminID'] != $student->userID)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Not admin of group"));		
		return;
	}
	
	// Make start and end timestamps with date,time and duration
	$timeStampStart = mktime(intval($timeBits[0]),intval($timeBits[1]),0,intval($dateBits[1]),intval($dateBits[0]),intval($dateBits[2]));
	$timeStampEnd = $timeStampStart + ($duration*60);
	$startTime = date("Y-m-d H:i:s",$timeStampStart);
	$endTime = date("Y-m-d H:i:s",$timeStampEnd);
		
	if($roomBooking)
	{
		// Check Room ID exists and is part of university of student
		$checkRoom = $db->prepare("SELECT * FROM `rooms` a INNER JOIN `campus` b ON a.campusID = b.campusID WHERE a.roomID = :roomID AND b.universityID = :universityID");
		$checkRoom->bindParam(":roomID",$roomID);
		$checkRoom->bindParam(":universityID",$student->universityID);
		$checkRoom->execute();
		
		
		if($checkRoom->rowCount() != 1)
		{
			echo json_encode(array("result"=>"Failed","message"=>"Room ID does not exist"));		
			return;
		}
		
		// Check if room is free for time and duration of meeting
		
		$SQL = "
			SELECT *, DATE_ADD(b.time, INTERVAL b.duration MINUTE) AS endTime 
			FROM `room_booking` a 
			INNER JOIN `meetings` b ON a.meetingID = b.meetingID
			WHERE b.time < :endTime AND a.roomID = :roomID
			HAVING endTime > :startTime
			";
		$checkBooking = $db->prepare($SQL);
		$checkBooking->bindParam(":roomID",$roomID);
		$checkBooking->bindParam(":startTime",$startTime);
		$checkBooking->bindParam(":endTime",$endTime);
		$checkBooking->execute();
		
		if($checkBooking->rowCount() != 0)
		{
			echo json_encode(array("result"=>"Failed","message"=>"Room is not available"));		
			return;
		}
		
	}
	
	// PASSED VALIDATION - Create meeting row and booking row if needed
	
	// JSON Message Array
	$jsonMessage = array();	
	
	// INSERT MEETING SQL STATEMENT
	$insertQuery = "INSERT INTO `meetings`(`groupID`, `meetingName`,`agenda`,`facilitatorID`, `time`, `duration`) VALUES (:groupID,:meetingName,:agenda,:facilitator,:time,:duration)";
	$q = $db->prepare($insertQuery);
	$q->bindParam(':groupID', $groupID);
	$q->bindParam(':meetingName', $meetingName);
	$q->bindParam(':agenda', $agenda);
	$q->bindParam(':facilitator', $student->userID);
	$q->bindParam(':time', $startTime);
	$q->bindParam(':duration', $duration);
		
	
	
	// If student insert execute is successful
	if($q->execute())
	{
		if($roomBooking)
		{
			// Get Last Insert ID
			$meetingID = $db->lastInsertId();
		
			// INSERT ROOM BOOKING
			$s = $db->prepare("INSERT INTO `room_booking`(`meetingID`, `roomID`) VALUES (:meetingID,:roomID)");
			$s->bindParam(':meetingID', $meetingID);
			$s->bindParam(':roomID', $roomID);
			if($s->execute())
			{
				$jsonMessage["result"] = "Successful";
			}
			else
			{
				// Return unsuccessful json message
				$jsonMessage["result"] = "Failed";
				$jsonMessage["message"] = "Database Failed (Room)";
			}
		}
		else
		{
			$jsonMessage["result"] = "Successful";
		}		
	}
	else
	{
		// Return unsuccessful json message
		$jsonMessage["result"] = "Failed";
		$jsonMessage["message"] = "Database Failed (Meeting)";		
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}

function getFreeRooms()
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
	
		
	$date = '';
	$time = '';
	$duration = 0;
	$seats = 0;
	
	
	
	// Store values from JSON OBject
	
	
	if(isset($jsonData->meetingDate)) { $date = $jsonData->meetingDate; }
	if(isset($jsonData->meetingTime)) { $time = $jsonData->meetingTime; }
	if(isset($jsonData->duration)) { $duration = intval($jsonData->duration); }
	if(isset($jsonData->seats)) { $seats = intval($jsonData->seats); }

	
		
	// Server Side Validation
	$errors = array();
	if($date == '') { $errors[] = "meetingDate";  } 
	if($time == '') { $errors[] = "meetingTime";  } 
	if($duration == 0) { $errors[] = "duration";  } 
	
	
	
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
	
	$jsonMessage = array();
	
	// Find Rooms that are free
	$SQLp1 = "
		SELECT *,
		(
			SELECT COUNT(*) 
			FROM `room_booking` a 
			INNER JOIN `meetings` b ON a.meetingID = b.meetingID
			WHERE b.time < :endTime AND a.roomID = c.roomID
			AND DATE_ADD(b.time, INTERVAL b.duration MINUTE) > :startTime
		) AS roomBooked
		FROM `rooms` c
		INNER JOIN `campus` d ON c.campusID = d.campusID
		WHERE d.universityID = :universityID ";
		$SQLp2 = " AND c.seat_capacity > 0 "; /* used if seat number is NOT specified*/
		$SQLp3 = " AND c.seat_capacity >= :seatCapacity "; /* used if seat number is specified*/
		
		$SQLp4 = " HAVING roomBooked = '0' ORDER BY c.seat_capacity ";
		
			
		if($seats > 0) { $SQL = $SQLp1.$SQLp3.$SQLp4; } else { $SQL = $SQLp1.$SQLp2.$SQLp4; }
		
		$checkRooms = $db->prepare($SQL);
		$checkRooms->bindParam(":universityID",$student->universityID);
		$checkRooms->bindParam(":startTime",$startTime);
		$checkRooms->bindParam(":endTime",$endTime);
		if($seats > 0) { $checkRooms->bindParam(":seatCapacity",$seats); }
		if($checkRooms->execute())
		{
			$jsonMessage['result'] = "Successful";
			$jsonMessage['numberRooms'] = $checkRooms->rowCount();
			
			$campus = array();
			foreach($checkRooms->fetchAll() AS $rooms)
			{
				$campusName = $rooms['campus_name'];
				$campus[$campusName][] = array("roomID"=>$rooms['roomID'],"roomName"=> $rooms['room_number'],"capacity"=>$rooms['seat_capacity']); 
			}
			$jsonMessage['roomData'] = $campus;
			
		}
		else
		{
			$jsonMessage['result'] = "Failed";
			$jsonMessage['message'] = "Database Failure";
		}
		
		echo json_encode($jsonMessage);
		


}

?>