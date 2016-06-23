<?php
	require_once('../scripts/databaseConnect.php');


	$data['groupsNum']=getGroupNum($db);

	$data['meetingsNum']=getMeetingNum($db);
	$data['studentsNum']=getStudentNum($db);

	echo json_encode(array("result"=>"successful","data"=>$data));
	
function getGroupNum($db){
	$groupssql="select * from groups where abs(datediff(now(),createdDate))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['1']=$count;
	}
	$groupssql="select *  from groups where abs(datediff(date_add(now(), INTERVAL -7 day) ,createdDate))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['2']=$count;
	}
	
	$groupssql="select *  from groups where abs(datediff(date_add(now(), INTERVAL -14 day),createdDate))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['3']=$count;
	}
	$groupssql="select *  from groups where abs(datediff(date_add(now(), INTERVAL -21 day),createdDate))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['4']=$count;
	}
	
	$groupssql="select *  from groups where abs(datediff(date_add(now(), INTERVAL -28 day),createdDate))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['5']=$count;
	}
	

	$groupssql="select *  from groups where abs(datediff(date_add(now(), INTERVAL -35 day),createdDate))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['6']=$count;
	}
	

	
	
	
	return $Months;
	
	
	
	
}

function getMeetingNum($db){
	$groupssql="select * from meetings where abs(datediff(now(),time))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['1']=$count;
	}
	$groupssql="select *  from  meetings where abs(datediff(date_add(now(), INTERVAL -7 day) ,time))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['2']=$count;
	}
	
	$groupssql="select *  from  meetings where abs(datediff(date_add(now(), INTERVAL -14 day),time))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['3']=$count;
	}
	$groupssql="select *  from  meetings where abs(datediff(date_add(now(), INTERVAL -21 day),time))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['4']=$count;
	}
	
	$groupssql="select *  from  meetings where abs(datediff(date_add(now(), INTERVAL -28 day),time))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['5']=$count;
	}
	

	$groupssql="select *  from  meetings where abs(datediff(date_add(now(), INTERVAL -35 day),time))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['6']=$count;
	}
	

	
	
	
	return $Months;
	
	
	
}
function getStudentNum($db){
	
	
	$groupssql="select * from students where abs(datediff(now(),dateJoined))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['1']=$count;
	}
	$groupssql="select *  from  students where abs(datediff(date_add(now(), INTERVAL -7 day) ,dateJoined))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['2']=$count;
	}
	
	$groupssql="select *  from  students where abs(datediff(date_add(now(), INTERVAL -14 day),dateJoined))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['3']=$count;
	}
	$groupssql="select *  from  students where abs(datediff(date_add(now(), INTERVAL -21 day),dateJoined))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['4']=$count;
	}
	
	$groupssql="select *  from  students where abs(datediff(date_add(now(), INTERVAL -28 day),dateJoined))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['5']=$count;
	}
	

	$groupssql="select *  from students where abs(datediff(date_add(now(), INTERVAL -35 day),dateJoined))<=7";
	$q = $db->prepare($groupssql);
	if($q->execute()){
	$count=count($q->fetchALL(PDO::FETCH_ASSOC));
	
	$Months['6']=$count;
	}
	


	
	
	return $Months;
	
	
}
	
