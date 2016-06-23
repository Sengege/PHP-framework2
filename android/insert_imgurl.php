<?php
require_once('../scripts/databaseConnect.php');
//require_once('databaseConnect.php');
if($noerrors <> 0)
{
	echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
	return;
}
$studentID=$_GET['studentID'];
$address=$_GET['address'];
if($studentID==""){
	echo json_encode(array("result"=>"failed","message"=>"No studentID"));
	return;
	}
if($address==""){
	echo json_encode(array("result"=>"failed","message"=>"No address"));
	return;
	}

$sql="update students SET profile_url = :address WHERE studentID = :studentID";

$q=$db->prepare($sql);
$q->bindParam(":address",$address);
$q->bindParam(":studentID",$studentID);
if($q->execute()){
	if($q->rowCount()==1){
	echo json_encode(array("result"=>"successful","address"=>$address));
	return;
	}else{
	echo json_encode(array("result"=>"failed","message"=>"no find student"));
	return;
	}
}else{
	echo json_encode(array("result"=>"failed","message"=>"no insert successful"));
	return;
}