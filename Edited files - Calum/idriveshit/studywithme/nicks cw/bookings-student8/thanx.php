<!DOCTYPE html>
<html>
<head>
 <title>Purchase Confirmation</title>
 <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

 <link rel="stylesheet" href="css/bookings.css" type="text/css" />
</head>
<body>
 <div id='header'>
  <div>
   <img src="images/tickets.png" width='253px' height='202px' style="float:left"/>
   <div style="margin-top:2px;float:left">
    <img src="images/hubtickets.gif" alt='hubtickets.gif' />
    <div id="nav">
     <a title='Back to Home Page' href="index.html">Back to Hub Tickets</a>
    </div>
    <div style="clear:both"></div>
   </div>
  </div>
  <div style="clear:both"></div>
  <div style="margin-left:2%;margin-top:2%;">
   <h4>
    Thank you <span id='username' style='color:DarkBlue'></span> for purchasing the following seat tickets from Hub Tickets. Your tickets should arrive at your registered address 
    within five working days.
   </h4>
   <h4>
    Purchased Seats: <span id='pseats' style='color:DarkBlue'></span>
   </h4>
  </div>
 </div>
 <script>
 
	var purchasedTickets = [];
	
	if(typeof(Storage) !== "undefined") {
		if(sessionStorage.purchasedTickets != null){
			purchasedTickets = sessionStorage.purchasedTickets.split(",");
		}
	}
	
	if(purchasedTickets.length > 0 )
	{
		var purchasedHTML = '';
		for(i=0;i<purchasedTickets.length;i++)
		{
			purchasedHTML += '<p>'+purchasedTickets[i]+'</p>';
		}
		// Display user data on thanks page
		$("#username").append(sessionStorage.fname);
		$("#pseats").html(purchasedHTML);
		
		// Remove purchased tickets sessionStorage key
		delete sessionStorage.purchasedTickets;
	}
	else
	{
		// Redirect to homepage
		window.location.href = "index.html";
	}
	
  
 </script>
</body>
</html>


