<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	

	$email=$_GET['email'];
	$password=$_GET['password'];
	
	if($email==''){

			$result['result']='failed';
			$result['message']='Request username !';
			echo json_encode($result);
			return;
		
	}

	if($password==''){
		$result['result']='failed';
		$result['message']="Request pass word";
		echo json_encode($result);
		return;
	}
	
	

	$query =  "SELECT * FROM `students` WHERE (`username` = :username) OR (`email` = :username)";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':username', $email);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$salt = $row['salt'];
	$secure_password = hash('sha256', $salt.$password, false);
	

	
	if(!$row){
		
		$result['result']='failed';
		$result['message']='Not find user';

		
		
	}else if ($row['secure_password'] == $secure_password){
		$result['result']='successful';
		$result['data']=$row;
	} else if ($row['secure_password'] != $secure_password) {
		$result['result']='failed';
		$result['message']="Please check your password and try again.";
		


	}else{
		$result['result']='failed';
		$result['message']="Network error have loged";
	}
		
	echo json_encode($result);

	


?>