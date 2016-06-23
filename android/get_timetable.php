<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	$studentID=$_GET['studentID'];
	if($studentID==''){
		$result['result']="failed";
		$result['message']="illegal parameter";
		echo json_encode($result);
		return;
		
	}
	
	// Query
	$query =  "SELECT * FROM `Schedules` WHERE studentID=?";
	$stmt = $db->prepare($query);
	$stmt->execute(array($studentID));
	$data=$stmt->fetchAll(PDO::FETCH_ASSOC);
	$result=array();
	if(empty($data)){
		$result['result']="failed";
		$result['message']="No timetable data be find.";
		
	}else{
		 		$result['result']="successful";
				$result['data']=$data;
		
		
	}
	echo json_encode($result);