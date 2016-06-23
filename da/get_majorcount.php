<?php

require_once('../scripts/databaseConnect.php');
if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
$sql="select TagID from tag";
$r=$db->prepare($sql);
$r->execute();
$popgroup=array();
while($row=$r->fetch(PDO::FETCH_ASSOC)){


$sql = "select tag_group.TagID,tag_group.groupID,tag.name_CN from tag_group INNER JOIN tag on tag_group.TagID=tag.TagID and tag.TagID=:TagID";
$s=$db->prepare($sql);
$s->bindParam(":TagID",$row['TagID']);
if($s->execute()){
	$count=$s->fetchALL(PDO::FETCH_ASSOC);
	
	if(count($count)>0){
		$info['tag_name']=$count[0]['name_CN'];
		$info['numbers']=count($count);
		$popgroup[]=$info;
	}
	
}

}

echo json_encode($popgroup);