<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
		$universityID=$_GET['universityID'];
		$schoolID=$_GET['schoolID'];
		if($universityID==""){
				echo json_encode(array("result"=>"failed","message"=>"Request Param"));
			return;
			
		}
		if($schoolID==""){
				echo json_encode(array("result"=>"failed","message"=>"Request Param"));
			return;
			
		}
		$sql="select * from module where universityID=:universityID and schoolID=:schoolID";
		$q = $db->prepare($sql);
		$q->bindParam(":universityID",$universityID);
		$q->bindParam(":schoolID",$schoolID);
		$q->execute();
		$rs=$q->fetchALL(PDO::FETCH_ASSOC);
		
		if($rs){
			$result['result']='successful';
			$result['data']=$rs;
			echo json_encode($result);
			
		}else{
			$result['result']='failed';
			
			echo json_encode($result);
			
		}