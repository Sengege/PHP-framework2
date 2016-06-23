<?php
	require_once('/scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	

	$email=$_GET['email'];
	$password=$_GET['password'];
	
	$loginsql="select * from students where email=? and password =?";
	$q=$db->prepare($loginsql);
	$q->execute(array($email,$password));
	$rs=$q->fetch(PDO::FETCH_ASSOC);
	$result=array();
	if($rs){
		$result['result']='successful';
		$result['data']=$rs;
		
		}else{
			$result['result']='failed';
		}
		
	echo json_encode($result);


	


?>