<?php
	
require_once('../scripts/databaseConnect.php');
	
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	
	$page=$_GET['page'];
	if($page==''){
		echo json_encode(array("result"=>"failed","message"=>"page can't be Null"));
		return;
	}
	$countQuery="select * from groups";
	$result = $db->prepare($countQuery);
	$result->execute(); 
	$total=count($result->fetchAll());
	

	
	$pagenum=ceil($total/5);      //获得总页数 pagenum

	
	//假如传入的页数参数apge 大于总页数 pagenum，则显示错误信息
	If($page>$pagenum){
		echo json_encode(array("result"=>"failed","message"=>"Can Not Found The page ."));
		return;
     }
	
	

	
	
	$offset=($page-1)*5;
	
	//
	$getGroupsql = "select * from groups ORDER BY  `groups`.`groupID` DESC LIMIT ".$offset.", 5";
	$result = $db->prepare($getGroupsql);
	$result->execute();
	$rs=array();
	$data=array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$getadmin = "select first_name,last_name,username,email from students where studentID = ?";
		$q = $db->prepare($getadmin);
		$q->execute(array($row['adminID']));
		//$admin['adminInfo']=$q->fetch(PDO::FETCH_ASSOC);
		$groupInfo=$row;
		$groupInfo['adminInfo']=$q->fetch(PDO::FETCH_ASSOC);
		$data[]=$groupInfo;
		//$data['adminInfo']=$q->fetch(PDO::FETCH_ASSOC);
	}
	
	

	$rs['result']="successful";
	$rs['data']=$data;
	

	
	echo json_encode($rs);
	
	
	
	
	

	?>