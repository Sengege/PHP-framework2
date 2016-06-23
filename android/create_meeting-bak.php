<?php
	//require_once('databaseConnect.php');
	require_once('../scripts/databaseConnect.php');
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
	//$startTime = date("Y-m-d H:i:s",$date);
	
	$startTime=date('Y-m-d H:i:s',strtotime($date)); //这个的输出是 2011-01-09
	
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
		$out['result']='successful';
		
	}else{
		$out['result']='failed';
	}
	echo json_encode($out);