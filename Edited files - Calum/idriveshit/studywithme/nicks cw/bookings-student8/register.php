<!DOCTYPE html>
<html>
<head>
 <title>Create an Account</title>
 <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
 <script src="js/validation/jquery.validate.js"></script>
 <script type="text/javascript" src="js/register.js"></script>
 
 
 <link rel="stylesheet" href="css/bookings.css" type="text/css" />
</head>
<body>
 <div id='header'>
  <div>
   <img src="images/tickets.png" width='253px' height='202px' style="float:left"/>
   <div style="margin-top:2px;float:left">
    <img src="images/hubtickets.gif" alt='hubtickets.gif' />
    <div id="nav">
     <a href="index.html">Home</a> 
    </div>
   <div class="clearfix"></div>
   </div>
  </div>
  <div class="clearfix"></div>
 </div>
 <div class="clearfix"></div>
    <div id="maincontainer" class="registerForm">
     <p>Use the form below to create a new account - you will then be redirected back to the Home page.</p> 
     <form id="register" method="post" action='#scripts/sendUserDetails.php'>
      <fieldset>
       <legend>Create Account</legend>
		<div class="left-col">
		   <label for="fname">First Name:&nbsp;</label>
		   <input autofocus name="fname" type="text" id="fname" class="textEntry" required placeholder="First Name"/>
		   <label for="lname">Last Name:&nbsp;</label>
		   <input name="lname" type="text" id="lname" class="textEntry" required placeholder="Last Name"/>
		   <label for="address1">Address1:&nbsp;</label>
		   <input name="address1" type="text" id="address1" class="textEntry" required placeholder="Address"/>
		   <label for="address2">Address2:&nbsp;</label>
		   <input name="address2" type="text" id="address2" class="textEntry" required placeholder="Address" />
		   <label for="town">Town:&nbsp;</label>
		   <input name="town" type="text" id="town" class="textEntry" required placeholder="Town"/>
		   <label for="postcode">Post Code:&nbsp;</label>
		   <input name="postcode" type="text" id="postcode" class="textEntry" required placeholder="Post Code"/>
		   <label for="phone">Phone:&nbsp;</label>
		   <input name="phone" placeholder="Phone" type="text" id="phone" class="textEntry" required/>
		</div>
		<div class="right-col">
		   <label for="username">User Name:&nbsp;</label>
		   <input name="username" type="text" id="username" class="textEntry" required placeholder="User Name"/>
		   <label for="email">Email:&nbsp;</label>
		   <input name="email" type="email" id="email" class="textEntry" required placeholder="Email"/>
		   <label for="memcat">Member Type:&nbsp;</label>
		   <select name="memcat">
				<option value="1">Standard</option>
				<option value="2">Friend</option>
				<option value="3">Premier</option>
				
			</select>
		   
		   <label for="password">Password:&nbsp;</label>
		   <input name="password" placeholder="Password" type="password" id="password" class="passwordEntry" required/>
		   
		   <input type="submit" value="Create Account" />
		</div>
		<div class="clearfix"></div>
      </fieldset>
      
     </form>    
    </div>
</body>
</html>
