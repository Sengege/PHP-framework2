<?php 
error_reporting(E_ALL);


/* Start CHINESE HTML*/
startHTML_CN("Shall we Study?");
?>

<script>
	$(document).ready(function(){
	
	var photos = ['/img/polygon.jpg','/img/promo-students.jpg','/img/promo-students2.jpg'];
	i = 0;
	

	(function loop() {
		$('#test').delay(5000).css("background-image", "url("+photos[i]+")").fadeIn(2000, loop);
		if(i+1 < photos.length) { i++; } else { i = 0; }
	}
	()
	);

    
});
</script>
<div class="section white" id="test" style="background: url('/img/polygon.jpg'); background-image: url(/img/promo-students.jpg); background-size:cover;">
    <div class="container">
		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron" style="padding:30px 0px 60px 0px;background:none;" >
			<div class="container" style="text-align:center">
				<span style="  padding:5px; margin:5px 0px; color: #000000;     font-size: 5.2em;     font-weight: bold;     background: rgba(255,255,255,0.5);  ">准备留学？</span>
				<p><span style="  padding:5px; line-height:1.65em; color: #000000;  background: rgba(255,255,255,0.5);  ">查找一组帮你学习</span></p>
				<p ><button class="btn btn-frontpage btn-danger" onclick="window.location.href='addStudent2.php'">立即注册</button></a>
			</div>
		</div> 
	
	<!-- <div style="  text-align: center;   width: 300px;   margin: auto;"><a href="addStudent2.php"><img src="img/joinus.png" class="img-responsive"></a> 	</div> -->
		   
	</div> <!-- /container -->
</div>

<div class="section yellow">
    <div class="container">
		<h2 class="bigheader">关于我们</h2>
		<div style="  text-align: center;   width: 300px;   margin: auto;"><img src="img/study.png" class="img-responsive"> 	</div>
		<p class="bigPara">建立研究组，组织会议，让您的时间表是最新的，等等。我们的目标是加强以实现您更多的学生的经验，并从你的工作中获益</p>
	   
	</div> <!-- /container -->
</div>

<div class="section aqua">
    <div class="container">
    <h2 class="bigHeader">好处</h2>
	<div style="  text-align: center;   width: 300px;   margin: auto;"><img src="img/graph.png" class="img-responsive"> 	</div>
    <pclass="bigPara">通过与他人学习最大化你的学习潜能。</p>
    </div> <!-- /container -->
</div>



	
	
	
	




	
<?php footerHTML();?>