$(document).ready(function(){
	$.getJSON("http://dev.atux.co.uk/da/get_booking.php",function(data3){
		for(var i=0; i < data3['message'].length;i++){
			$("#tr1").append("<th>"+data3['message'][i].name)
			$("#tr2").append("<td>"+data3['message'][i].bookstime);
		}
	});
});