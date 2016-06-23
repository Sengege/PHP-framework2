<?php
	require_once('../scripts/databaseConnect.php');
	//require_once('databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	

	$universityID=$_GET['universityID'];
	$campus_name=$_GET['campus_name'];
	
	
	if($universityID==""){
		echo json_encode(array("result"=>"failed","message"=>"parametre request"));
		return;
		
	}
	if($campus_name==""){
		echo json_encode(array("result"=>"failed","message"=>"parametre request"));
		return;
	}
	
	$sql = "INSERT INTO `studywithme`.`campus` (`campusID`, `universityID`, `campus_name`) VALUES (NULL, :universityID,:campus_name);";
	$r = $db->prepare($sql);
	$r->bindParam(":universityID", $universityID);
	$r->bindParam(":campus_name", $campus_name);
	
	if($r->execute()){
		echo json_encode(array("result"=>"successful"));
	}else{
		echo json_encode(array("result"=>"failed","message"=>"execute failed!"));
	}

