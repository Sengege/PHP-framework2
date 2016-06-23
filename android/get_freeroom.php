<?php
	require_once('../scripts/databaseConnect.php');
	//require_once('databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	$week=$_GET['week'];    			//第几周
	$weekDay=$_GET['weekDay'];			//周几
	$building=$_GET['building_code'];	//哪栋楼
	//$startclass=$_GET['classes'];       //第几节
	$campus=$_GET['campusID']; 			 //哪个校区
	
	
	
	$sql="select * from rooms where campusID = :campusID and room_number LIKE :building";

	$q = $db->prepare($sql);
	$q->bindParam(":campusID",$campus);
	$q->bindValue(":building",''.$building.'%', PDO::PARAM_STR);
	
	

	
	$finded=array();

	if($q->execute()){
	
	while($rs=$q->fetch(PDO::FETCH_ASSOC)){
		
		/*$sql="select * from class_shedule where roomID = :roomID and weekly = :weekDay";
		
		$search=$db->prepare($sql);
		$search->bindParam(":roomID",$rs['roomID']);
		$search->bindParam(":weekDay",$weekDay);
		$search->execute();
		$result=$search->fetchAll(PDO::FETCH_ASSOC);
		if($result){
		
		}else{
			*/
			$finded[]=$rs;
		//
		}
		$out['result']='successful';
		$out['data']=$finded;
//	}
	}else{
		$out['result']='failed';
		//$out['data']=$finded;
	}
	
	
	echo json_encode($out);
	
	
	?>