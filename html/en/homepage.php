<?php 
/*redirect if user is logges in*/
global $student;
if($student->userExists()) { header('Location: dashboard.php'); die(); } 

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">	<title>Study With Me</title>
	
	<!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/font-awesome.css" rel="stylesheet">
	<link href="/css/carousel.css" rel="stylesheet">
	<!-- Chosen -->	
	<link rel="stylesheet" href="/css/chosen.css">	
	
	<!-- time date picker -->
	<link href="/css/datetime-picker/jquery.timepicker.css" rel="stylesheet">
	<!-- Custom Style Sheet -->
	<link href="/css/style.css" rel="stylesheet">
	<style>
		.error { color:#f00; }
	</style>
	<script type="text/javascript" src="/js/jQuery.js"></script>
	<script src="/js/bootbox.min.js"></script>
	<script type="text/javascript" src="/js/titleanimation.js"></script>
	<link href="/css/titlestyle.css" rel="stylesheet"> 
	<style>
		html,body{height:100%;}
			#back-to-top {
	   
		opacity: 0;
		padding: 7px;
	  position: fixed;
	  bottom: 40px;
	  right: 40px;
	  z-index: 9999;
	  color: #A6A6A6;
	  transition: opacity 0.2s ease-out;
	  font-size: 4em;
	}
	#back-to-top:hover {
		
	}
	#back-to-top.show {
		opacity: 1;
	}
	#content {
		height: 2000px;
	}
	</style>
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
	
	

<div id="mainpix" style="height:100%;  background: url(/img/slide1.jpg) rgb(238, 238, 238);  background-size: cover;">
	
		<nav class="navbar navbar-home navbar-static-top">
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
					
					<li><a href="#" class="scroll" data-content="about">About</a></li>
					<li><a href="#" class="scroll" data-content="app">App</a></li>
					<li><a href="#" class="scroll" data-content="contact">Contact</a></li>
					<li><a href="#" id="loginBox">Login</a></li>
				  
			  </ul>
		
			</div><!--/.navbar-collapse -->
		  </div>
		</nav>
	
	
		<div class="container">
			<div style="padding-top:80px; padding-bottom:40px; text-align:center;">
				
					<section class="cd-intro">		
								        <!-- CB: Tried to make the writing more prominent 			
								        but it looks a bit sketchy. Anything can be read 			
								        in white with a black border -->			
								     <h2 class="cd-headline clip is-full-width">
									  <span>I want to Study</span>				
					            		<span class="cd-words-wrapper">					
					              		<b class="is-visible">...</b>					
							                <b>Computing</b>					
							                <b>Maths</b>					
							                <b>English</b>
											<b>with you!</b>
					             		 </span>			
					             	 </h2>			
										<h3>
											<span>Join thousands of other Students and form the Study group you want.</span>			
										</h3>		
					      </section>
				<p ><button class="btn btn-success btn-lg scroll" id="findOutMore" data-content="about">Find Out More</button></p>
			</div>
		</div>
	
</div>

    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing" >
		<hr class="featurette-divider" style="border:1px solid #FFF" id="about" >
		<div class="row featurette">
			<div class="col-md-5">
			<div class="text-center featurette-heading" style="font-size: 15em; color:#e74c3c;"><i class="fa fa-book"></i></div>
			</div>
			<div class="col-md-7">
				<h2 class="featurette-heading">Designed by students <span class="text-muted">... for students</span></h2>
				<p class="lead">Study With Me was created by an international group project between Edinburgh Napier University and Zhengzhou University. We found that while study groups and focus groups already existed, you were required to know the right people in order to join them. Study With Me was designed to solve this problem.</p>
				<p><button type="button" class="btn btn-default btn-lg scroll" data-content="about2" >More</button></p>
			</div>
		</div>
		<hr class="featurette-divider" style="border:1px solid #FFF" id="about2">
		<div class="row featurette">
			<div class="col-md-7">
				<h2 class="featurette-heading">Want to study with others?</h2>
				<p class="lead">Improve your studying experience today! Study With Me is a educational tool which allows you to easily create and join study groups with other students. Set up meetings and book free rooms at your university.</p>
				<p><a href="/register" class="btn btn-default btn-lg" >Sign Up - It's Free!</a></p>
			</div>
			<div class="col-md-5">
				<div class="text-center featurette-heading" style="font-size: 15em; color:#2980b9; vertical-align:middle;"><i class="fa fa-group"></i></div>
			</div>
		</div>
		
		<hr class="featurette-divider" style="border:1px solid #FFF" id="app">
		<div class="row featurette">
		<div class="col-md-7">
			<h2 class="featurette-heading">We have an app too!  <span class="text-muted">Download it today</span></h2>
			<p class="lead">Enjoy the same experience on your phone</p>
			<p><a class="btn btn-success btn-lg" href="http://zzuli.atux.co.uk/swm.apk" target="blank"><i class="fa fa-android"></i> Get the App</a> <button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#myModal"><i class="fa fa-qrcode"></i> QR Code</button></p>
		</div>
		<div class="col-md-5">
			<div class="text-center" style="font-size: 15em; color:#27ae60;"><i class="fa fa-android"></i></div>
		</div>
		</div>
		<hr class="featurette-divider" style="border:1px solid #FFF" id="contact">
		
		<div class="row featurette">
			<div class="col-md-5">
				<div class="text-center featurette-heading" style="font-size: 15em; color:#2980b9;"><i class="fa fa-comments"></i></div>
			</div>
			<div class="col-md-7">
				<h2 class="featurette-heading">Got a question?  <span class="text-muted">Let us know</span></h2>

				<form role="form">
				<div class="col-sm-12">

				<div class="form-group">
					
					<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" class="form-control" name="InputName" id="InputName" placeholder="Your Name" >    
					</div>
					</div>
					<div class="form-group">
					
					<div class="input-group">
					<span class="input-group-addon"><b>@</b></span>
					<input type="email" class="form-control" id="InputEmailFirst" name="InputEmail" placeholder="Your Email" >

					</div>
					</div>
					<div class="form-group">

					<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-comment"></i></span>
					<textarea name="InputMessage" id="InputMessage" class="form-control" rows="2" placeholder="Your message"></textarea>

					</div>
					</div>
					<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
					</div>
				</form>
			</div>
		</div>
		<hr class="featurette-divider" style="border:1px solid #FFF">
  




   

    </div><!-- /.container -->






<!-- Modal for QR code -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Get the App - Scan the code</h4>
      </div>
      <div class="modal-body">
        <img class="img-responsive center-block" src="/img/app-qr.png" alt="QR Code">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<a href="#" id="back-to-top" title="Back to top"><i class="fa fa-arrow-circle-up fa-3"></i></a>
<script >
$( document ).ready(function() {
	$(".scroll").click(function(e){
		e.preventDefault();
		var ID = $(this).data("content");
		var distance = $('#'+ID).offset().top;
		$("html, body").animate({ scrollTop: distance  }, 1000);
	})
	
	if ($('#back-to-top').length) {
    var scrollTrigger = 100, // px
        backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $('#back-to-top').addClass('show');
            } else {
                $('#back-to-top').removeClass('show');
            }
        };
    backToTop();
    $(window).on('scroll', function () {
        backToTop();
    });
    $('#back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });
}
})
</script>
<script>
		$('#loginBox').click( function(e) { e.preventDefault(); loginBox(); } );
				
		$('body').on("click","#resetPassword",function(e){
			e.preventDefault();
			bootbox.prompt("Please enter your email address", function(result) {
				if(result === null) { return false; }
				else
				{
					var json = JSON.stringify({ email: result });
					console.log(json);
					$.post( "/scripts/student/resetPassword/request", json ,function( data ) {
						
						var jData = jQuery.parseJSON(data)
				
						if(jData.result)
						{
							if(jData.result == 'Successful')
							{
								bootbox.alert("A reset password link has been sent to your email",function(){  $(".bootbox.modal.bootbox-prompt").remove(); return true; });
							}
							else{
								bootbox.alert(jData.message);
							}
							
						}
						else
						{
							bootbox.alert("Something went wrong!");
						}
					});
					return false;
				}
			})
		})
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
						window.location.href = "/dashboard.php";
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
                    '</form> </div> <a href="#" id="resetPassword">Forgot Password?</a><div id="loginMessage"></div>  </div>',
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
	
<?php footerHTML();?>