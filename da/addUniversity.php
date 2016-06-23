<?php
	require_once('../scripts/databaseConnect.php');
	//require_once('databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	$name=$_GET['name'];
	$location=$_GET['location'];
	$sql = "INSERT INTO university (`name`, `location`) VALUES (:name,:location);";
	$r = $db->prepare($sql);
	$r->bindParam(":name", $name);
	$r->bindParam(":location", $location);
	
	if($r->execute()){
		echo json_encode(array("result"=>"successful"));
		return;
	}else{
		echo json_encode(array("result"=>"failed","message"=>"execute failed!"));
		return;
	}

	