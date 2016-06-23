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
	$studentID=$_GET['studentID'];
	$isAdmin=$_GET['isAdmin'];
		
	if($studentID==""){echo json_encode(array("result"=>"failed","message"=>"Request student"));
		return;}
	if($isAdmin==""){echo json_encode(array("result"=>"failed","message"=>"Request isAdmin"));
		return;}
	$getGroupsql = "select groupID from group_membership where studentID=? ORDER BY  groupID DESC ";
	$result = $db->prepare($getGroupsql);
	$result->execute(array($studentID));
	$mycreated=array();
	$myadded=array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		
		$groupinfosql="select * from groups where groupID = ? ";
		$groupinfo = $db->prepare($groupinfosql);
		$groupinfo->execute(array($row['groupID']));
		$group=$groupinfo->fetch(PDO::FETCH_ASSOC);
		
		//echo json_encode($group);
		
		//得到管理员ID
		$getadmin = "select first_name,last_name,username,email from students where studentID = ?";
		$q = $db->prepare($getadmin);
		$q->execute(array($group['adminID']));
		
		//GET groups Tagscloud
		
		
		$gettag="SELECT Tag.name_CN FROM  `Tag_Group` INNER JOIN Tag ON Tag_Group.TagID = Tag.TagID and groupID=?";
		$s=$db->prepare($gettag);
		$s->execute(array($row['groupID']));
		$tags=array();
		while($tag=$s->fetch(PDO::FETCH_ASSOC)){
			$tags[]=$tag;
		}
		
		$rate=calculateRating($row['groupID']);
		
	
		if($group['adminID']==$studentID){
			$groupinf=$group;
			$groupinf['tags']=$tags;
			$groupinf['rate']=$rate;
			$groupinf['adminInfo']=$q->fetch(PDO::FETCH_ASSOC);
			$mycreated[]=$groupinf;
		}else{

			$groupinf=$group;
			$groupinf['tags']=$tags;
			$groupinf['rate']=$rate;
			$groupinf['adminInfo']=$q->fetch(PDO::FETCH_ASSOC);
			
			$myadded[]=$groupinf;
		
		
		}
		
	}
	$out['result']='successful';
	if($isAdmin=='0'){
		$out['data']=$myadded;
	}else{
		$out['data']=$mycreated;;
	}

	
	echo json_encode($out);
	