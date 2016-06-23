<?php
	require_once('../scripts/databaseConnect.php');
	//require_once('databaseConnect.php');
	//require_once('class/studentClass.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	$meetingName=$_GET['meetingName'];
	$agenda=$_GET['agenda'];
	$groupID=$_GET['groupID'];
	$facilitatorID=$_GET['facilitatorID'];
	$date=$_GET['date'];
	$roomID=$_GET['roomID'];
	//$startTime = date("Y-m-d H:i:s",$date);
	
	$startTime=date('Y-m-d H:i:s',strtotime($date)); //���������� 2011-01-09
	
	$duration='120';
	
	$insertQuery = "INSERT INTO `meetings`(`groupID`, `meetingName`,`agenda`,`facilitatorID`, `time`, `duration`) VALUES (:groupID,:meetingName,:agenda,:facilitator,:time,:duration)";
	$q = $db->prepare($insertQuery);
	$q->bindParam(':groupID', $groupID);
	$q->bindParam(':meetingName', $meetingName);
	$q->bindParam(':agenda', $agenda);
	$q->bindParam(':facilitator', $facilitatorID);
	$q->bindParam(':time', $startTime);
	$q->bindParam(':duration', $duration);
		
	
	
	// If student insert execute is successful
	
	$out=array();
	if($q->execute())
	{
		$meetingID = $db->lastInsertId();
		$sql = "INSERT INTO `studywithme`.`room_booking` (`bookingID`, `meetingID`, `roomID`, `numberOfSeats`) VALUES (NULL, :meetingID, :roomID, '0');";
		//$sql="insert into room_booking ('roomID','meetingID') values (,)";
		$r = $db->prepare($sql);
		$r->bindParam(":roomID", $roomID);
		$r->bindParam(":meetingID", $meetingID);
		if($r->execute()){
			$out['booking']='successful';
		}else{
			echo var_dump($r->errorInfo());
		}
		
		$out['result']='successful';
		
	}else{
		$out['result']='failed';
	}
	echo json_encode($out);