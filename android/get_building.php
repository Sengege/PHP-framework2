<?php
	require_once('../scripts/databaseConnect.php');
	//require_once('databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	$universityID=$_GET['universityID'];
	$campusID=$_GET['campusID'];
	if($universityID==''){
		$rs=get_building($campusID,$db);
		$out['result']='successful';
		$out['data']=$rs;
		
	}else{
		$rs=get_campus($universityID,$db);
		
		$out['result']='successful';
		$out['data']=$rs;
	}
	
	echo json_encode($out);
	
	function get_building($campusID,$db){
		$sql = "SELECT * FROM `buildings` WHERE campusID= :campusID ";
		$q=$db->prepare($sql);
		$q->bindParam(":campusID",$campusID);
		if($q->execute()){
			$rs=$q->fetchALL(PDO::FETCH_ASSOC);
			
			return $rs;
		}else{
			$rs['result']="failed";
			return $rs;
		}
		
	
	}
	
	
	
	
	function get_campus($universityID,$db){
		$sql = "SELECT * FROM `campus` WHERE universityID= :universityID ";
		$q=$db->prepare($sql);
		$q->bindParam(":universityID",$universityID);
		if($q->execute()){
			$rs=$q->fetchALL(PDO::FETCH_ASSOC);
			return $rs;
		}else{
			$rs['result']="failed";
			return $rs;
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*function get_building(){
	
	$sql = "SELECT campus.campus_name,building.building_code,building.building_name FROM `campus` INNER JOIN building ON campus.campusID = building.campusID LIMIT 0, 30 ";
	$q = $db->prepare($sql);
	if($q->execute()){
		$rs=$q->fetchALL(PDO::FETCH_ASSOC);
		$result['result']='successful';
		$result['data']=$rs;
		
	}else{
		$result['result']='failed';
	
	}
	echo json_encode($result);
	}*/