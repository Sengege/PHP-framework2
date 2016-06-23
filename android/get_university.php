<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
		$sql="select * from university";
		$q = $db->prepare($sql);
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