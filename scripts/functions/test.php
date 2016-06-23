<?php
include('../prepend.php');
/* test script */

getFreeRooms();

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
	
	//$request = Slim\Slim::getInstance()->request();
	//$body = $request->getBody();
	//$jsonData = json_decode($request->getBody());
	
	$json = '
		{
			"meetingDate": "12/5/2015",
			"meetingTime": "12:00",
			"duration": "60",
			"seats":"300"
		}
	';
	$jsonData = json_decode($json);
	
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
		$SQLp2 = " AND c.seat_capacity >= :seatCapacity "; /* used if seat number is specified*/
		$SQLp3 = " HAVING roomBooked = '0' ORDER BY c.seat_capacity ";
		
			
		if($seats > 0) { $SQL = $SQLp1.$SQLp2.$SQLp3; } else { $SQL = $SQLp1.$SQLp3; }
		
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