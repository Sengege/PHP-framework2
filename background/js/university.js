$(document).ready(function(){
	$.getJSON("http://dev.atux.co.uk/da/get_university.php",function(data){
		for (var i=0; i<data['data'].length; i++){
			$('tbody').append('<tr><td>'+data['data'][i].universityID+'</td><td>'+data['data'][i].name+'</td><td>'+data['data'][i].location+'</td></tr>');
		}
	});
	
	
	
	
	
$("#sub").click(function(){
		
		var strname=$("#uname").val();
		var strloc=$("#loc").val();
		$.ajax({
			type:'GET',              
			url:'http://dev.atux.co.uk/da/addUniversity.php?name='+strname+"&location="+strloc,         
			dataType:'json',             		
		    success:function(json){
			   alert("successful!");
		    },		
			error:function(){
				alert("Add failure");			
			}
	    });

		
	});
	
});


	/*function subform(){
	 var strname = document.getElementById("uname").value;
     var strloc = document.getElementById("loc").value;
	 document.getElementById("myformid").action="http://202.196.1.141/~tristan/tj/addUniversity.php?name="+strname+"&location="+strloc;
	 document.getElementById("myformid").submit();
	}*/