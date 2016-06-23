<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{	
		echo json_encode(array("result"=>"Failed","error"=>"No connection"));		
		return;	
	}	
	$studentID=$_GET['studentID'];
	$content=$_GET['content'];
	$postDate=date("Y-m-d H:i:s");
	
	
	//sql prepare;
	$sql="insert into feedback ('studentID','content','postDate') values (:studentID,:content,:postDate) ";
	$r=$db->prepare($sql);
	$r->bindParam(':studentID',$studentID);
	$r->bindParam(':content',$content);
	$r->bindParam(':postDate',$postDate);
	
	if($r->execute()){
	
		echo json_encode(array("result"=>"successful"));		
		return;
	}else{
		echo json_encode(array("result"=>"Failed","error"=>"run time error"));		
		return;
	}
	