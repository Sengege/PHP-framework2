<?php global $student; 

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">	<title><?php echo $title; ?></title>
	
	<!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/font-awesome.css" rel="stylesheet">
	<!-- Chosen -->	
	<link rel="stylesheet" href="/css/chosen.css">	
	<!-- jquery UI -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<!-- Custom Style Sheet -->
	<link href="/css/style.css" rel="stylesheet">
	<style>
		.error { color:#f00; }
	</style>
	<script type="text/javascript" src="/js/jQuery.js"></script>
	<script src="/js/bootbox.min.js"></script>
	
	<!--CB: Resources for Title animation-->	
	<!-- <link rel="stylesheet" href="css/titlestyle.css">   -->
	<!--Resource style -->	
	<!-- <script src="js/modernizr.js"></script> Modernizr -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	

<script>
		$( document ).ready(function() {
			$('#logOut').click( function(e) { e.preventDefault(); logOut(); });
		})
		
		function logOut(){
			$.get( "/scripts/student/logout", function( data ) {
				var jData = jQuery.parseJSON(data);
				if(jData.result)
				{
					bootbox.alert("您已成功登出", function() {
						window.location.href = "/";
					});
				}
				else
				{
					bootbox.alert("注销失败，请重试！"); 
				}
			})
		}
</script>