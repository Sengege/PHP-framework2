<?php

require_once('../scripts/databaseConnect.php');
if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
$sql="select roomID from rooms where campusID=6";
$r=$db->prepare($sql);
$r->execute();
$poprooms=array();
while($row=$r->fetch(PDO::FETCH_ASSOC)){
	
	$sql = "select rooms.description from room_booking INNER JOIN rooms ON room_booking.roomID=rooms.roomID and room_booking.roomID=?";
	$s=$db->prepare($sql);
	$s->execute(array($row['roomID']));

	$result=$s->fetchALL(PDO::FETCH_ASSOC);

	if(count($result)>0){
		$room['name']=$result[0]['description'];
		$room['bookstime']=count($result);
		$poprooms[]=$room;
	}
}
	echo json_encode(array("result"=>"successful","message"=>$poprooms));