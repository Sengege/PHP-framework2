$(document).ready(function(){
	$.getJSON("http://dev.atux.co.uk/da/get_room.php",function(data){
		for(var i = 0; i < data.length;i++){
			$('tbody').append(
				"<tr><td>"+data[i].roomID+
				"</td><td>"+data[i].campusID+
				"</td><td>"+data[i].room_number+
				"</td><td>"+data[i].description+
				"</td><td>"+data[i].seat_capacity+
				"</td></tr>");
		}
	});
});