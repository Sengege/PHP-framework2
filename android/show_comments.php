<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}

	$groupID=$_GET['groupID'];
	$page=$_GET['page'];
	//分页处理开始
	if($page==''){
		echo json_encode(array("result"=>"failed","message"=>"page can't be Null"));
		return;
	}
	$countQuery="select * from group_message where groupID=?";
	$result = $db->prepare($countQuery);
	$result->execute(array($groupID)); 
	$total=count($result->fetchAll());
	$pagenum=ceil($total/5);      //获得总页数 pagenum

	
	//假如传入的页数参数apge 大于总页数 pagenum，则显示错误信息
	if($page>$pagenum){
		echo json_encode(array("result"=>"failed","message"=>"Can Not Found The page ."));
		return;
     }
	//分页处理结束
	
	$offset=($page-1)*5;

	
	
	
	
	
	
	
	
	
	
	
	$sql="select * from `group_message` where groupID = :groupID ORDER BY  `messageID`  DESC LIMIT ".$offset.",5";
	
	
	$pop=$db->prepare($sql);
	
	
	
	$pop->bindParam(":groupID",$groupID);
	if($pop->execute()){
	
	$rs=array();
	$data=array();
		while($row = $pop->fetch(PDO::FETCH_ASSOC)){
		
		$getuser = "select * from students where studentID = ?";
		$q = $db->prepare($getuser);
		$q->execute(array($row['studentID']));
		$message=$row;
		$message['studentInfo']=$q->fetch(PDO::FETCH_ASSOC);
		$data[]=$message;
		}
	}else{
		echo 'error';
		return;
	}
	
	$rs['result']="successful";
	$rs['data']=$data;
	
		echo json_encode($rs);