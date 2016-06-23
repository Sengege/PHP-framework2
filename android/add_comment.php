<?php
require_once('../scripts/databaseConnect.php');
//require_once('databaseConnect.php');
if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
$groupID=$_GET['groupID'];
$userID=$_GET['studentID'];
$message=$_GET['message'];
if($groupID==''){echo json_encode(array("result"=>"failed","message"=>"No groupID find"));
		return;}
if($userID==''){echo json_encode(array("result"=>"failed","message"=>"No userID"));
		return;}
if($message==''){echo json_encode(array("result"=>"failed","message"=>"No message"));
		return;}



$time=time(); 
$post_date=date("Y-m-d H:i:s",$time); 
// comments to database
	$push = $db->prepare("INSERT INTO `group_message`(`groupID`, `studentID`, `message`,`post_date`) VALUES (:groupID,:studentID,:message,:post_date)");
	$push->bindParam(":groupID",$groupID);
	$push->bindParam(":studentID",$userID);
	$push->bindParam(":message",$message);
	$push->bindParam(":post_date",$post_date);
	$result = $push->execute();
		if($result)
	{
		$jsonArray["result"] = "successful";
	}
	else
	{
		$jsonArray["result"] = "failed";
		$jsonArray["message"] = "Not result";
	}
	
	
	// Output JSON Message
	echo json_encode($jsonArray);