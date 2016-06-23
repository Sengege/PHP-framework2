$(document).ready(function(){
	$.getJSON("http://dev.atux.co.uk/da/get_university.php",function(data1){
		for(var i = 0;i<data1['data'].length;i++){
			$(".selectbox").append("<option value=' "+data1['data'][i].universityID+"'>"+data1['data'][i].name+"</option>");
      		$(".selectbox2").append("<option value=' "+data1['data'][i].universityID+"'>"+data1['data'][i].name+"</option>");
		}
	});

	$(".selectbox").change(function() {
      var uniID = $('.selectbox').val().trim();
      $("tbody").empty();
      $.getJSON('http://202.196.1.141/~tristan/get_school.php', {universityID: uniID}, function(data2){
  		for(var j = 0; j<data2['data'].length;j++ ){
  			$("tbody").append("<tr><td>"+data2['data'][j].schoolID+"</td><td>"+data2['data'][j].name+"</td></tr>");
  		}
      });
    });
	
	
	
	
	$("#sub").click(function(){
		
		var strname1=$(".selectbox2").val();
		var strname2=$("#cname").val();
		$.ajax({
			type:'GET',              
			url:'http://dev.atux.co.uk/da/addSchool.php?name='+strname2+'&universityID='+strname1,
        
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


 /* function subform(){
	 var strname1 = document.getElementById("uname").value;
     var strname2 = document.getElementById("loc").value;
	 document.getElementById("myformid").action="http://202.196.1.141/~tristan/tj/addUniversity.phphttp://202.196.1.141/~tristan/tj/addCampus.php?universityID="+strname+"&campus_name="+strname2;
	 document.getElementById("myformid").submit();
	}*/