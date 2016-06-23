<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
		
	$tag=$_GET['tag'];
	
	$tagID=get_tagID($tag,$db);
	echo $tagID;
	
	function get_tagID($tag,$db){
		$tagsql="select tagID from tag where name_CN=? or name_EN=?";
		$q = $db->prepare($tagsql);
		$q->execute(array($tag,$tag));
		$rs=$q->fetch(PDO::FETCH_ASSOC);
		if($rs){
			$tagID=$rs['tagID'];
		}else{
			$taginsert="insert into`tag`(`name_CN`, `name_EN`, `tag_categoryID`) VALUES (:name_CN, :name_EN, :tag_categoryID)";
			$insert = $db->prepare($taginsert);
			$insert->bindParam(':name_CN', $tag);
			$insert->bindParam(':name_EN', $tag);
			$insert->bindParam(':tag_categoryID', 7);
			if($insert->execute())
		{
			$tagID = $db->lastInsertId();
		}else{
			$tagID=0;
		}
		
		return $tagID;
	}
	