<?php global $student; ?>
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
	<!-- time date picker -->
	<link href="/css/datetime-picker/jquery.timepicker.css" rel="stylesheet">
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
	
	<nav class="navbar navbar-custom navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/index.php"><img src="/img/logo2.png" class="img-responsive" alt="Study With Me"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
		
		  <ul class="nav navbar-nav navbar-right">
				<li class="active"><a href="/index.php">Home</a></li>
				<li><a href="#about.php">About</a></li>
				<li><a href="#contact.php">Contact</a></li>
				
              <li class="dropdown account">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Account<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
			  
				<?php if($student->userExists()) { ?>
					<li><a href="dashboard.php" >My Dashboard</a></li>
					<li><a href="#" id="logOut">Log Out</a></li>	
				<?php } else { ?>
					<li><a href="#" id="loginBox">Login</a></li>
					<li><a href="addStudent2.php">Sign up</a></li>
				<?php } ?>
			</ul>
            </li>
          </ul>
	
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
	
	<script>
		$('#loginBox').click( function() { loginBox(); } );
		$('#logOut').click( function() { logOut(); });
		
		function logOut(){
			$.get( "/scripts/student/logout", function( data ) {
				var jData = jQuery.parseJSON(data);
				if(jData.result)
				{
					bootbox.alert("You have been successfully logged out", function() {
						window.location.href = "/";
					});
				}
				else
				{
					bootbox.alert("Opps something went wrong"); 
				}
			})
		}
		$('body').on("submit","#loginForm",function(e){
			
			e.preventDefault();
			var username = $('#username').val();
			var password = $('#password').val();
			
			$.post( "/scripts/student/login/", { username: username, password: password } ,function( data ) {
				console.log(data);
				var jData = jQuery.parseJSON(data)
				
				if(jData.result)
				{
					if(jData.result == 'successful')
					{
						$("#loginMessage").empty();
						window.location.href = "dashboard.php";
					}
					else{
						html = '<span class="error">'+jData.message+'</span>';
						$("#loginMessage").empty();
						$("#loginMessage").append(html);
					}
					
				}
				else
				{
					html = '<span class="error">Something went wrong!</span>';
					$("#loginMessage").empty();
					$("#loginMessage").append(html);
				}
			});
		})
		
							
			
							
		function loginBox(){
			bootbox.dialog({
                title: "<strong>Login to Study With Me</strong>",
                message: '<div class="row">  ' +
                    '<div class="col-md-12"> ' +
                    '<form id="loginForm" class="form-horizontal"> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-md-4 control-label" for="username">Username/Email</label> ' +
                    '<div class="col-md-4"> ' +
                    '<input id="username" name="username" type="text" placeholder="Username/Email" class="form-control input-md"> ' +
                    '</div> ' +
                    '</div> ' +
					'<div class="form-group"> ' +
					'<label class="col-md-4 control-label" for="name">Password</label> ' +
                    '<div class="col-md-4"> ' +
                    '<input id="password" name="password" type="password" placeholder="password" class="form-control input-md"> ' +
                    '</div> ' +
					'<button type="submit" style="display:none"></button>'+
                    '</form> </div> <div id="loginMessage"></div>  </div>',
                buttons: {
                    login: {
                        label: "Login",
                        className: "btn-success",
                        callback: function () {
                            $("#loginForm").submit();
							return false; /* False keeps dialogueopen */
                        }
					},
					cancel: {
						label: "Cancel",
						className: "btn-danger"
						
					
					}
                
				}
			});
		}
	</script>