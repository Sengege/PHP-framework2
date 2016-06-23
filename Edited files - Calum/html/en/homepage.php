
<?php 
/*redirect if user is logges in*/
global $student;
if($student->userExists()) { header('Location: dashboard.php'); die(); } 
/* Start HTML*/
startHTML("Shall we Study?",false);
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
<div class="section white" id="test" style="background: url('img/polygon.jpg'); background-image: url(img/promo-students.jpg); background-size:cover;">
    <div class="container">
		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron" style="padding:30px 0px 60px 0px;background:none;" >
			<div class="container" style="text-align:center">
				<span style="  padding:5px; margin:5px 0px; color: #000000;     font-size: 5.2em;     font-weight: bold;     background: rgba(255,255,255,0.5);  ">Ready to Study?</span>
				<p><span style="  padding:5px; line-height:1.65em; color: #000000;  background: rgba(255,255,255,0.5);  ">Find a group to help you study</span></p>
				<p ><button class="btn btn-frontpage btn-danger" onclick="window.location.href='addStudent2.php'">Sign Up Today</button></a>
			</div>
		</div> 
	
	<!-- <div style="  text-align: center;   width: 300px;   margin: auto;"><a href="addStudent2.php"><img src="img/joinus.png" class="img-responsive"></a> 	</div> -->
		   
	</div> <!-- /container -->
</div>

<div class="section yellow">
    <div class="container">
		<h2 class="bigHeader">About Us</h2>
		<div style="  text-align: center;   width: 300px;   margin: auto;"><img src="img/study.png" class="img-responsive"> 	</div>
		<p class="bigPara">Shall we Study? is the worlds best online social study partner. Create Study Groups, Organize meetings, keep your timetable up to date, and much more. We aim to enhance your student experience in order to achieve more and benefit from your colleagues!</p>
	   
	</div> <!-- /container -->
</div>

<div class="section aqua">
    <div class="container">
    <h2 class="bigHeader">Benefits</h2>
	<div style="  text-align: center;   width: 300px;   margin: auto;"><img src="img/graph.png" class="img-responsive"> 	</div>
    <p class="bigPara">Maximise your learning potential by studying with others. Blah blah blah </p>
    </div> <!-- /container -->
</div>



	
	
	
	




	
<?php footerHTML();?>