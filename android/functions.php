<?php
// Set $username, $password and $dbname to your details
$dbuser = 'typhoon';
$dbpass = 'typhoon';
$dbname = 'studywithme';

	

	
	//登录函数   接收link  和 email password 参数  成功返回用户信息 失败返回0
	function act_login($link, $email,$password) {
		

	$sql = "select * from students where email= '" . $email .  "' and password = '" . $password. "'";
	$result = $link->query($sql);
	
	if ($result->num_rows > 0) {
		$userinfo = $result->fetch_assoc();
		return json_encode($userinfo);
	}
	$result=array("state" => "false");
	return json_encode($result);
	
	}
	
?>
