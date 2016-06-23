<?php
require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{	
		echo json_encode(array("error"=>"No connection"));		
		return;	
	}else{
		echo "DB successful";
		
	}

?>