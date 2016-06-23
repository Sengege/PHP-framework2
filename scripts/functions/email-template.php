<?php

/* 
HTML Email Template
-Generates HTML to wrap around message - uses templates found in email directory

$lang "en" - English or "cn" - Chinese
*/

define( 'ROOT_DIR', dirname(__FILE__) );

function generateEmailHTML($title,$header,$body,$lang)
{
	ob_start();
	include( ROOT_DIR.'/../../email/templates/generic_v1/generic_template.phtml' );
	$output = ob_get_clean();
	return $output;
}