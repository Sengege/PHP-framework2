<?php 
/*redirect if user is logges in*/
global $student;
if($student->userExists()) { header('Location: dashboard_cn.php'); die(); } 

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">	<title>学习同道</title>
	
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
					
					<li><a href="#" class="scroll" data-content="about">应用简介</a></li>
					<li><a href="#" class="scroll" data-content="app">下载手机App</a></li>
					<li><a href="#" class="scroll" data-content="contact">联系我们</a></li>
					<li><a href="#" id="loginBox">登录</a></li>
				  
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
									  <span>我想要学习</span>				
					            		<span class="cd-words-wrapper">					
					              		<b class="is-visible">...</b>					
							                <b>计算机</b>					
							                <b>数学</b>					
							                <b>英语</b>
											
					             		 </span>			
					             	 </h2>			
										<h3>
											<span>加入我们吧，在这里你会找到成上万志同道合的朋友</span>			
										</h3>		
					      </section>
				<p ><button class="btn btn-success btn-lg scroll" id="findOutMore" data-content="about">查看详情</button></p>
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
				<h2 class="featurette-heading">由学生开发 <span class="text-muted">... 供学生使用</span></h2>
				<p class="lead">学习同道是一款由郑州轻工业学院与英国龙比亚大学学生联合项目小组开发的应用. 用于建立网路上的学习小组，使用户可以就一个学习科目或主题和世界各地的学生组成学习小组，让合作跨越国界，让学习不再孤独.</p>
				<p><button type="button" class="btn btn-default btn-lg scroll" data-content="about2" >更多</button></p>
			</div>
		</div>
		<hr class="featurette-divider" style="border:1px solid #FFF" id="about2">
		<div class="row featurette">
			<div class="col-md-7">
				<h2 class="featurette-heading">想要和他人一起学习么?</h2>
				<p class="lead">现在就来提高你的学习效率! 学习同道可以让你自由的创建或加入学习小组，与世界各地学生一起学习</p>
				<p><a href="/register" class="btn btn-default btn-lg" >免费注册</a></p>
			</div>
			<div class="col-md-5">
				<div class="text-center featurette-heading" style="font-size: 15em; color:#2980b9; vertical-align:middle;"><i class="fa fa-group"></i></div>
			</div>
		</div>
		
		<hr class="featurette-divider" style="border:1px solid #FFF" id="app">
		<div class="row featurette">
		<div class="col-md-7">
			<h2 class="featurette-heading">移动客户端!  <span class="text-muted">现在下载</span></h2>
			<p class="lead">使用手机体验应用</p>
			<p><a class="btn btn-success btn-lg" href="http://zzuli.atux.co.uk/swm.apk" target="blank"><i class="fa fa-android"></i>电脑下载</a> <button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#myModal"><i class="fa fa-qrcode"></i> 二维码下载</button></p>
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
				<h2 class="featurette-heading">遇到问题?  <span class="text-muted">请让我们知道</span></h2>

				<form role="form">
				<div class="col-sm-12">

				<div class="form-group">
					
					<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" class="form-control" name="InputName" id="InputName" placeholder="您的名字" >    
					</div>
					</div>
					<div class="form-group">
					
					<div class="input-group">
					<span class="input-group-addon"><b>@</b></span>
					<input type="email" class="form-control" id="InputEmailFirst" name="InputEmail" placeholder="您的邮箱" >

					</div>
					</div>
					<div class="form-group">

					<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-comment"></i></span>
					<textarea name="InputMessage" id="InputMessage" class="form-control" rows="2" placeholder="您的问题"></textarea>

					</div>
					</div>
					<input type="submit" name="submit" id="submit" value="提交" class="btn btn-info pull-right">
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
        <h4 class="modal-title" id="myModalLabel">下载应用 - 请扫描二维码</h4>
      </div>
      <div class="modal-body">
        <img class="img-responsive center-block" src="/img/app-qr.png" alt="QR Code">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
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
						window.location.href = "/dashboard_cn.php";
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
                title: "<strong>登录账户</strong>",
                message: '<div class="row">  ' +
                    '<div class="col-md-12"> ' +
                    '<form id="loginForm" class="form-horizontal"> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-md-4 control-label" for="username">用户名/邮箱</label> ' +
                    '<div class="col-md-4"> ' +
                    '<input id="username" name="username" type="text" placeholder="用户名/邮箱" class="form-control input-md"> ' +
                    '</div> ' +
                    '</div> ' +
					'<div class="form-group"> ' +
					'<label class="col-md-4 control-label" for="name">密码</label> ' +
                    '<div class="col-md-4"> ' +
                    '<input id="password" name="password" type="password" placeholder="密码" class="form-control input-md"> ' +
                    '</div> ' +
					'<button type="submit" style="display:none"></button>'+
                    '</form> </div> <a href="#" id="resetPassword">忘记密码?</a><div id="loginMessage"></div>  </div>',
                buttons: {
                    login: {
                        label: "登录",
                        className: "btn-success",
                        callback: function () {
                            $("#loginForm").submit();
							return false; /* False keeps dialogueopen */
                        }
					},
					cancel: {
						label: "取消",
						className: "btn-danger"
						
					
					}
                
				}
			});
		}
	</script>
	
<?php footerHTML_CN();?>