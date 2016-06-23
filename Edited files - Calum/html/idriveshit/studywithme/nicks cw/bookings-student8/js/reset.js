/*

 JQuery to reset database

*/


$(document).ready(function(){
	$('.resetDatabase').click(function(e) {
		e.preventDefault();
		
		var confirmReset = confirm("Are you sure you want to reset the database?");
		
		if(confirmReset)
		{
			var myurl="scripts/hubtickets/resetDatabase";
			$.ajax({
				type: "GET",
				dataType:'json',
				url: myurl,
				success: function(data){
					
					if(!data.errorcode)
					{
						alert("Database has been reset");
						sessionStorage.clear();
						window.location.replace("index.html");
					}
					else 
					{
						var message = '';
						switch(data.errorcode)
						{
							case 1:
								message = "Database Error";
								break;
							case 2:
								message = "Database Error";
								break;
							case 3:
								message = "SQL Error";
								break;
							case 4:
								message = 'Reset SQL file not found';
								break;
							default:
								message = 'Unknown';
							
						}
						alert("Reset Failed\n\nReason: "+message);
					}
				}
				
			})
			
		}
	})

})