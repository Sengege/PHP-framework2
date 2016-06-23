<?php

/* Functions related to front end */

function startHTML($title,$loggedIn)
{
	include_once(dirname(__FILE__)."/../../html/en/header.php");
}

/* Functions related to front end */

function footerHTML()
{
	include_once(dirname(__FILE__)."/../../html/en/footer.php");
}

function startHTML_CN($title)
{
	include_once(dirname(__FILE__)."/../../html/cn/header.php");
}

/* Functions related to front end */

function footerHTML_CN()
{
	include_once(dirname(__FILE__)."/../../html/cn/footer.php");
}




?>