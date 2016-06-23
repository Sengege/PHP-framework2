<?php

function logOut()
{
	session_destroy();
	echo json_encode(array("result"=>"successful"));
}

?>