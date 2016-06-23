<?php
	require_once('../scripts/databaseConnect.php');
	require_once('ratingSystem.php');
	require_once('../scripts/class/studentClass.php');
	//require_once('databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	$tagID=$_GET['tagID'];
	
	if($tagID==""){
		echo json_encode(array("result"=>"failed","message"=>"Param request"));
		return;
	}
	
	$getGroupsql = "select groupID from tag_group where tagID=?";
	$result = $db->prepare($getGroupsql);
	if($result->execute(array($tagID))){
	
	$rs=array();
	$data=array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		//echo var_dump($row);
		$groupssql="select * from groups where groupID=:groupID";
		$getgroups = $db->prepare($groupssql);
		$getgroups->bindParam(":groupID", $row['groupID']);
		$getgroups->execute();
		
		while($row=$getgroups->fetch(PDO::FETCH_ASSOC)){
		//echo var_dump($row);
		$getadmin = "select * from students where studentID = ?";
		$q = $db->prepare($getadmin);
		$q->execute(array($row['adminID']));
		$groupInfo=$row;
		$groupInfo['adminInfo']=$q->fetch(PDO::FETCH_ASSOC);	
		
		$gettag="SELECT tag.name_CN FROM  `tag_group` INNER JOIN tag ON tag_group.tagID = tag.tagID and groupID=?";
		$s=$db->prepare($gettag);
		$s->execute(array($row['groupID']));
		$tags=array();
		
		$rate=calculateRating($row['groupID']);
		while($tag=$s->fetch(PDO::FETCH_ASSOC)){
			$tags[]=$tag;
		}
		$groupInfo['rate']=$rate;
		$groupInfo['tags']=$tags;
		$data[]=$groupInfo;
		}
		
		
		
		
	
		
	}
	
	$rs['result']="successful";
	$rs['data']=$data;
}	else{
	$rs['result']="failed";
	$rs['message']="Can not running serach";
	
}
	
	

	
	echo json_encode($rs);
	
	
	
	
	

	?>