/*!
 * Validation Script for New Member
 *
 * Sends new member data to server via AJAX
 * Creates session storage if successful
 * 
 * 
 */

$(function() {
	$("#register").submit(function(e) { e.preventDefault(); })
	
	$("#register").validate({
		rules: { 
			fname: { required: true }, 
			lname: { required: true },
			address1: { required: true },
			town: { required: true },
			postcode: { required: true },
			phone: { required: true },
			username: { required: true, minlength: 5 },
			email: { required:true, email:true},
			password: { required:true, minlength: 8 }
			},
		messages: { 
			fname: { required: "First name is required" }, 
			lname: { required: "Surname name is required" },
			address1: { required: "First line of your address is required" },
			town: { required: "Town name is required" },
			postcode: { required: "Postcode is required" },
			phone: { required: "Please provide a telephone number" },
			username: { required: "Username is required"  , minlength: "Username requires to be at least 5 characters" },
			email: { required: "Email address is required"},
			password: { required: "Password is required"  , minlength: "Password requires to be at least 8 characters" }
			
			},
		
		submitHandler: function(form) 
		{ 
			var data = {};
			var d = $("#register").serializeArray();
			// Reformat data object before converting to JSON
			for(i=0;i<d.length;i++)
			{
				data[d[i].name] = d[i].value;
			}
			var jsonData = JSON.stringify(data);

			
			$.ajax({
				type: 'POST',
				contentType: 'application/json',
				url: 'scripts/hubtickets/register',
				dataType: "json",
				data: jsonData,
				success: function(data){
					
					if(!data.errorcode)
					{
						// Set Session Storage
						sessionStorage.loggedIn = true;
						sessionStorage.userID = data.userID;
						sessionStorage.username = data.user;
						sessionStorage.fname = data.fname;
						sessionStorage.lname = data.lname;
						sessionStorage.email = data.email;
						sessionStorage.memcat = data.memcat;
						// Redirect to homepage
						window.location.replace("index.html");
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
								message = "Database Error";
								break;
							case 3:
								message = "SQL Error";
								break;
							case 4:
								message = 'Failed validation';
								break;
							default:
								message = 'Unknown';
							
						}
						alert("Ne Member Failed\n\nReason: "+message);
					}
					
					
				},
				error: function(jqXHR, textStatus){
					alert('Register error: ' + textStatus);
				}
			});
			
		}
		
	});
	
	
})