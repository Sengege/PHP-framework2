<?php
	header("Content-type: application/json");
	require_once('../scripts/databaseConnect.php');
	require_once('ratingSystem.php');
	require_once('../scripts/class/studentClass.php');
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
		$groupInfo=$row;
		$groupInfo['adminInfo']=$q->fetch(PDO::FETCH_ASSOC);
		
		$rate=calculateRating($row['groupID']);
		
		//GET groups Tagscloud
		
		
		$gettag="SELECT Tag.name_CN FROM  `Tag_Group` INNER JOIN Tag ON Tag_Group.TagID = Tag.TagID and groupID=?";
		$s=$db->prepare($gettag);
		$s->execute(array($row['groupID']));
		$tags=array();
		while($tag=$s->fetch(PDO::FETCH_ASSOC)){
			$tags[]=$tag;
		}
		$groupInfo['tags']=$tags;
		$groupInfo['rate']=$rate;
		$data[]=$groupInfo;
	}
	
	
	

	$rs['result']="successful";
	$rs['data']=$data;
	

	
	echo json_encode($rs);
	
	
	
	
	

	?>