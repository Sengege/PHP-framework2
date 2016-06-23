<?php error_reporting(E_ALL);/*include database connection*/require_once('scripts/databaseConnect.php');require_once('scripts/functions/functions.php');/* Start HTML*/startHTML("Shall we Study?",false);?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" style="background-image: url(img/promo-students.jpg);
background-size: 100% 100%;
background-repeat: no-repeat;" >
      <div class="container">
        <span style="  padding:5px;  color: #000000;     font-size: 5.2em;     font-weight: bold;     background: rgba(255,255,255,0.5);  ">Ready to Study?</span>
        <p>Find a study group today etc ....</p>
        <p><p><a class="btn btn-lg btn-success" href="addStudent2.php" role="button">Sign up today</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>About</h2>
          <p>Shall we Study? is the worlds best online social study partner. Create Study Groups, Organize meetings, keep your timetable up to date, and much more. We aim to enhance your student experience in order to acheive more and benefit from your colleagues! </p>
                 </div>
        <div class="col-md-4">
          <h2>How it works</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
       </div>
        <div class="col-md-4">
          <h2>Benefits</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
        </div>
      </div>

      <hr>

      
    </div> <!-- /container -->



	</script>


	<script type="text/template" id="aboutUs-page-template">
	
    <nav class="navbar" style="margin-bottom: 40px;">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.html"><img src="img/logo.png" alt="logo"/></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" action="groups.html">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <div class="container">
 
          <h2>About</h2>
          <p>Study With me about details</p>
          
    </div> <!-- /container -->
	</script>
	
	
	<script type="text/template" id="group-list-template">
		<h1>Groups This User Is In:</h1>
		{{ _.each(sGroups, function(group){ }}
			<a href="#/groups/{{= group['groupID'] }}">{{= group['groupName'] }}</a> <br />
		{{ }); }}

		<h1>Suggested Groups For This User:</h1>
		{{ _.each(iGroups, function(group){ }}
			<a href="#/groups/{{= group['groupID'] }}">{{= group['groupName'] }}</a> <br />
		{{ }); }}
	</script>




	<script type="text/templpate" id="group-view-template">
		<h1>{{= group['name'] }}</h1>

		<div id="details">
			{{= group['name'] }}
			{{= group['description'] }}
			{{= members.length }} members:

			{{ _.each(members, function(member){ }}
				{{= member['name'] }} <br />
			{{ }); }}
		</div>
	</script>
	<?php error_reporting(E_ALL);require_once('scripts/functions/functions.php');/* end HTML*/footerHTML();?>
