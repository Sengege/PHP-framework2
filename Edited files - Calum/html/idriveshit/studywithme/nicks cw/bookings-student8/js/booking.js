$(document).ready(function(){

	var userID = sessionStorage.userID;
	loadPurchasedTickets();

	// function to load purchased tickets
	function loadPurchasedTickets()
	{
			if(userID != null)
			{
			$.ajax({
				dataType: "json",
				url: 'scripts/hubtickets/getPurchasedTickets/'+userID,
				success: function(data){
					
					if(!data.error)
					{
						// Store seat data in array				
						var number = parseInt(data.ticketNumber);
					
						if(number > 0)
						{
							$("#bookingMessage").empty();
							$("#bookingMessage").append("You have "+number+" purchased ticket(s). Please see below. You may cancel your seats at any time");
							
							var tickets = data.tickets;
							var html = '';
							
							for(i=0;i<tickets.length;i++)
							{
								html += '<div class="ticket t'+tickets[i].ID+'"><span>'+tickets[i].seatnum+'</span><a href="#'+tickets[i].ID+'" class="removeTicket">X</a></div>';
							}
							$("#bookingData").empty();
							$("#bookingData").append(html);
							
						}
						else
						
						{
							$("#bookingMessage").empty();
							$("#bookingMessage").append("You have no purched tickets. Theres still time to buy some!");
						}
					}
					else
					{
						alert("error");
					}
				}
			}); /* end of ajax call */
			}
			else
			{
				$("#bookingMessage").empty();
				$("#bookingMessage").append("Sorry you need to be logged in to see this page");
			}
	}
		
	// Function for remove ticket
	$("#bookingData").on('click', ".removeTicket",function(e){
		e.preventDefault();
		
		var buttonContainer = $(this).parent('.ticket'); 
		
		// highlight yellow
		buttonContainer.css('background','yellow');
		
		var ticketID = $(this).attr('href').replace('#','');
		var removeCheck = confirm("Are you sure you want to remove this ticket?");
		if(removeCheck)
		{
			var data = {};
			data.userID = sessionStorage.userID;
			data.ticketID = ticketID;
			var jsonData = JSON.stringify(data);

			
			$.ajax({
				type: 'POST',
				contentType: 'application/json',
				url: 'scripts/hubtickets/removePurchasedTickets',
				dataType: "json",
				data: jsonData,
				success: function(data){
					if(!data.error)
					{
						buttonContainer.hide('fast',function(){ $(this).remove(); loadPurchasedTickets(); });
					}
				}
			})
		}
		else
		{
			buttonContainer.removeAttr('style');
		}
		
	})

})
