<?php
require_once('../scripts/databaseConnect.php');
if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	


$sql = "SELECT students.studentID,students.first_name,students.last_name,school.name,university.name,students.email FROM (`students` INNER JOIN school on students.schoolID=school.schoolID) INNER JOIN university on students.universityID=university.universityID";

$r=$db->prepare($sql);

$r->execute();

$result=$r->fetchALL(PDO::FETCH_ASSOC);

echo json_encode($result);
