$(document).ready(function(){
  
	
	// Declare global object
	var hubTickets = {};
	
	// Declare variables
	hubTickets.loggedIn = false
	hubTickets.memberType = '';
	hubTickets.userID = '';
	hubTickets.seatsChosen = [];
	hubTickets.storageCompatible = false;
	
	// Check if session storage available 
	if(typeof(Storage) !== "undefined") { hubTickets.storageCompatible = true }
	
	// On page load check session storage for chosen seats 
	if(hubTickets.storageCompatible) {
		if(sessionStorage.seatsChosen != null){
			if(sessionStorage.seatsChosen.length > 0)
			{
				hubTickets.seatsChosen = sessionStorage.seatsChosen.split(",");
			}
		}
	}
	
	/*! *********** !*/ 
	/*! ONPAGE LOAD !*/
	/*! *********** !*/ 
	
	// Functions must be in order
	updateLoginData();
	updateMenu();
	updateTicketName();
	updateSeatsTable();
	updateMyTickets();
	
	$("#myTickets").show();
	
	
	
	/*! ********************************* !*/ 
	/*! SEAT SELECTION & DRAGGABLE EVENTS !*/
	/*! ********************************* !*/ 
	
	// Make available seats draggable
	$( "#seatTable img" ).draggable({ 
		revert: true, 
		cancel: ".chosen",
		start: function() {
			$("#left").animate({backgroundColor: "rgb(255, 252, 0)"});
			$("#left").prepend('<p style="text-align:center; font-weight:bold;" id="dragMessage">Drag Here</p>');
		},
		stop: function() {
			$("#left").animate({backgroundColor: "rgb(255, 255, 255)"});
			$("#dragMessage").remove();
		}
	});
	
	// If draggable seat is dropped in #left box add seat
	$( "#left" ).droppable({
		drop: function( event, ui ) {
			if($.inArray( "seat", ui.draggable.context.classList ) >= 0)
			{
				var seatNumber = ui.draggable.context.title;
				addSeat(seatNumber);
				var seatImg = $("#seatTable img[title="+seatNumber+"]");
				seatImg.addClass("chosen");
				seatImg.attr("src","images/mine.gif");
			}
		},
		out: function(event, ui){
			if($.inArray( "seatNumber", ui.draggable.context.classList ) >= 0)
			{
				var seatNumber = ui.draggable.context.textContent;
				removeSeat(seatNumber);
				var seatImg = $("#seatTable img[title="+seatNumber+"]");
				seatImg.removeClass("chosen");
				seatImg.attr("src","images/available.gif");
			}
		}
    });
	
	$("#seatTable").on('click','.seat',function(){
		if(!$(this).hasClass('chosen'))
		{
			$(this).attr("src","images/mine.gif");
			$(this).addClass("chosen");
			var seatNumber = $( this ).attr( "title" );
			addSeat(seatNumber);
		}
		else
		{
			$(this).attr("src","images/available.gif");
			$(this).removeClass("chosen");
			var seatNumber = $( this ).attr( "title" );
			removeSeat(seatNumber);
		}
	})
	
	
	$("#seatTable").on('mouseover','.seat',function(){
		if(!$(this).hasClass('chosen'))
		{
			$(this).attr("src","images/mine.gif");
		}
	})
	$("#seatTable").on('mouseout','.seat',function(){
		if(!$(this).hasClass('chosen'))
		{
			$(this).attr("src","images/available.gif");
		}
	})
	
	
	
	
	
	
	
	
	
	
	
	

	/*! **************** !*/ 
	/*! ON SUBMIT EVENTS !*/
	/*! **************** !*/ 
	
	$("#logform").submit(function(e){
		e.preventDefault();
		var username = $(this).find("input[name=username]").val();
		var password = $(this).find("input[name=password]").val();
		
		if($.trim(username).length > 0 && $.trim(password).length > 0)
		{
			$("#loginMessage").remove();
			// Send login data to server
			var myurl="scripts/hubtickets/login/"+username+'/'+password;
			$.ajax({
				type: "GET",
				dataType:'json',
				url: myurl,
				success: function(data){
					
					if(!data.errorcode)
					{
						//Set Session Storage
						sessionStorage.loggedIn = true;
						sessionStorage.userID = data.ID;
						sessionStorage.username = data.username;
						sessionStorage.fname = data.fname;
						sessionStorage.lname = data.lname;
						sessionStorage.email = data.email;
						sessionStorage.memcat = data.memcat;
						
						updateLoginData();
						
						// Update Menu
						$("#loginMessage").remove();
						$("#loginputs").fadeOut(500);
						$(".anonymous").fadeOut(500,function(){ $(".member").fadeIn(500); });
						
						// Reset login Form
						$('#logform')[0].reset()
						
						// Update Tickets
						updateMyTickets();
						updateTicketName();
					}
					else
					{
						var errorCode = data.errorcode;
						var errorMessage = '';
						switch(errorCode)
						{
							case 1 :
								errorMessage = "Database Error";
								break;
							case 2 :
								errorMessage = "SQL Error";
								break;
							case 3 :
								errorMessage = "Login details are incorrect";
								break;
						}
						
						// Display error message
						$("#logform").after('<label id="loginMessage" class="error">'+errorMessage+'</label>');
					}
				}
			})
		}
		else
		{
			$("#loginMessage").remove();
			$(this).after('<label id="loginMessage" class="error">Please provide username and password</label>');
		}
		
		
	})
	
	
	
	
	/*! **************** !*/ 
	/*! ON CLICK EVENTS  !*/
	/*! **************** !*/ 
	
	// Cancel Selection of seats
	$("#cancelSelection").click(function(e){
		//Remove chosen class and image 
		$("img.chosen").each(function(index,element){
			$(element).removeClass("chosen");
			$(element).attr("src","images/available.gif");
		})
		// Clear seatChosen array and update #myTickets
		hubTickets.seatsChosen = [];
		// update session storage
		sessionStorage.seatsChosen = hubTickets.seatsChosen;
		updateMyTickets();
	})
	
	$(".loginLink").click(function(e){
		e.preventDefault();
		$("#loginputs").fadeIn(500);
	})
	
	// When logout link is pressed
	$(".logOut").click(function(e){
		e.preventDefault();
		logOut();
	})
	
	$("#myTickets").on('click', "#proceedToCheckOut", function(){
		// Create object with necessary information to purchase
		var object = {};
		object.userID = hubTickets.userID
		object.seats = hubTickets.seatsChosen;
		var jsonData = JSON.stringify(object);
		
		
		$.ajax({
				type: 'POST',
				contentType: 'application/json',
				url: 'scripts/hubtickets/purchase',
				dataType: "json",
				data: jsonData,
				success: function(data){
					
					if(!data.errorcode)
					{
						// Store session storage - purchased tickets
						if (hubTickets.storageCompatible)
						{
							sessionStorage.purchasedTickets = data.purchased;
						}
						// Redirect to thank you page
						window.location.href = "thanx.php";
						
					}
					else
					{
						
						// Error Handling
						var message = '';
						switch(data.errorcode)
						{
							case 1:
								message = "Database Error";
								break;
							case 2:
								message = "SQL Error";
								break;
							case 3:
								message = "Unfortunately the seats you requested are no longer available";
								break;
							default:
								message = 'Unknown';
							
						}
						alert("Purchase Seats Failed\n\nReason: "+message);
					}
					
					
				},
				error: function(jqXHR, textStatus){
					alert("Sorry there was an error connecting to server, please try again later");
				}
			});
		
	})
	
	
	/*! **************** !*/ 
	/*! FUNCTIONS        !*/
	/*! **************** !*/ 
  
  // function to update menu depending if user is logged in 
	function updateMenu()
	{
		if(hubTickets.loggedIn)
		{
			// Display members menu
			$(".anonymous").hide();
			$(".member").show();
		}
		else
		{
			// Display members menu
			$(".anonymous").show();
			$(".member").hide();
		}
	}
	
	

	
	
	// function checks session storage and updates My Tickets Name
	function updateTicketName()
	{
		if(hubTickets.loggedIn) {
			// Personalise My Tickets section
			var fname = sessionStorage.fname;
			var lname = sessionStorage.lname;
			$("#salutation").empty();
			$("#salutation").append(fname+" "+lname+"'s Tickets");
		}
		else
		{
			$("#salutation").empty();
			$("#salutation").append("My Tickets");
		}	
	}

	function updateSeatsTable(){
		// Get seat availability from server
		$.ajax({
			dataType: "json",
			url: 'scripts/hubtickets/getSeats',
			success: function(data){
					
				if(data.seats)
				{
					// Store seat data in array				
					var seats = data.seats;
					
					
					// Foreach seat image in theseatTable 
					$("#seatTable td img").each(function(index,element){
						
						// Add title to image
						$(element).attr('title', seats[index][0]);
						
						//Remove attributes
						$(element).removeAttr("onmouseover");
						$(element).removeAttr("onmouseout");
						$(element).removeAttr("onclick");
						
						// If seat is available
						if(seats[index][1] == 1 )
						{
							// Add class and title to seat img
							$(element).addClass("seat");
							// Check if seat has already been chosen
							if($.inArray( seats[index][0], hubTickets.seatsChosen ) >= 0)
							{
								$(element).addClass("chosen");
								$(element).attr("src","images/mine.gif");
							}
						}
						else
						{
							// if chosen seat is now unavailable remove from chosenseats array 
							if($.inArray( seats[index][0], hubTickets.seatsChosen ) >= 0)
							{
								removeSeat(seats[index][0]);
								
							}
							$(element).attr("src","images/taken.gif");
							$(element).addClass("taken");
						}
					})
					
				}
				else
				{
					// Error Handling
						var message = '';
						switch(data.errorcode)
						{
							case 1:
								message = "Database Error";
								break;
							case 2:
								message = "SQL Error";
								break;
							default:
								message = 'Unknown';
							
						}
						alert("Get Seats Failed\n\nReason: "+message);
				}
			}
		});
	}
	
	function updateMyTickets()
	{
		hubTickets.seatsChosen.sort();
		$("#myTickets").empty();
		
		if(hubTickets.seatsChosen.length > 0)
		{
			// Add draggable tickets to My Tickets div
			for(i=0;i<hubTickets.seatsChosen.length;i++)
			{
				$("<div/>", {
				   "class": "seatNumber" ,
					text: hubTickets.seatsChosen[i],
					style: "background: #AAB5FF;padding: 2px 4px;margin: 5px;display: inline-block;cursor:pointer"
				  }).draggable({revert: true})
				  .appendTo("#myTickets");

			}
		
			// Calculate standard cost of tickets
			var totalCost = hubTickets.seatsChosen.length * 35;
			var discount = 0;
			
			if (hubTickets.loggedIn) {
				
				// Display checkout button
				$("#myTickets").append('<p></p><button id="proceedToCheckOut">Checkout!</button>');
				
				// Check membership type and set discount
				switch(hubTickets.memberType)
				{
					case "Friend":
						discount = 10;
						break;
					case "Premier":
						discount = 15;
						break;
					default:
				}
			}
			else
			{
				// Display login/register if not logged in
				$("#myTickets").append('<p></p>Please login or <a href="register.php">register</a>');
			}
			
			// Display Price
			if(discount > 0)
			{
				discountCost = parseFloat(totalCost - (totalCost/100 * discount)).toFixed(2);
				$("#myTickets").append('<p style="font-weight:bold">The total cost is : <s>&pound;'+totalCost+'</s> <span style="color:green;">&pound;'+discountCost+'</span><p>');
			}
			else
			{
				$("#myTickets").append('<p style="font-weight:bold">The total cost is : &pound;'+totalCost+'<p>');
			}
			
		}
	}
	function addSeat(seat)
	{
		if($.inArray(seat,hubTickets.seatsChosen) == -1)
		{
			// Add seat to array
			hubTickets.seatsChosen.push(seat);
			// Update session storage
			sessionStorage.seatsChosen = hubTickets.seatsChosen;
			// Update My Tickets
			updateMyTickets();
		}
	}
	function removeSeat(seat)
	{
		// Remove seat from array
		hubTickets.seatsChosen.splice($.inArray(seat, hubTickets.seatsChosen),1);
		// Update session storage
		sessionStorage.seatsChosen = hubTickets.seatsChosen;
		// Update My Tickets
		updateMyTickets();
	}
	
		
	// Function to LogOut - Remove session storage and return to normal
	function logOut()
	{
		// Delete User Session Storage 
		delete sessionStorage.loggedIn;
		delete sessionStorage.userID;
		delete sessionStorage.username;
		delete sessionStorage.fname;
		delete sessionStorage.lname;
		delete sessionStorage.email;
		delete sessionStorage.memcat;
		
		updateLoginData();
		updateTicketName();
		updateMyTickets();
		$(".member").fadeOut(500,function(){ $(".anonymous").fadeIn(500); });
	}	
	
	function updateLoginData()
	{
		if(hubTickets.storageCompatible) {
			if (sessionStorage.loggedIn) {
				hubTickets.loggedIn = true;
				hubTickets.memberType = sessionStorage.memcat;
				hubTickets.userID = sessionStorage.userID;
			}
			else
			{
				hubTickets.loggedIn = false;
				hubTickets.memberType = '';
			}
		}
	}
	
  
});