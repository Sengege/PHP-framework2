//for table two

var arr1= new Array();
var arr2= new Array();

$(function(){

$.ajax({
		type: 'GET',               //提交类型为GET
		url: 'http://dev.atux.co.uk/da/get_majorcount.php',         
		dataType:'json',             //请求类型为json
			
		//成功获取到服务器端的响应用后，将向页面上的table中添加元素
		success:function(json){
           
			/*var li = '<tr><th>major</th>';
			var str = '<tr><th>Number</th>';*/
			$.each(json,function(index,array){ //遍历json数据列
			   /* li+='<td>'+array['tag_name']+'</td>';
			    str+='<td>'+array['numbers']+'</td>';	*/
			  arr1[index]=array['tag_name']
       			  arr2[index]=  array['numbers']
			});
			/*li+='</tr>';
			str+='</tr>';
			//将构建的li追加到table元素中
			$("#list table").append(li+str);*/





			$("#major1").after('<td>'+arr1[0]+'</td><td>'+arr1[1]+'</td><td>'+arr1[2]+'</td><td>'+arr1[3]+'</td><td>'+arr1[4]+'</td><td>'+arr1[5]+'</td><td>'+arr1[6]+'</td><td>'+arr1[7]+'</td><td>'+arr1[8]+'</td><td>'+arr1[9]+'</td><td>'+arr1[10]+'</td><td>'+arr1[11]+'</td><td>'+arr1[12]+'</td><td>'+arr1[13]+'</td>');
			$("#Num1").after('<td>'+arr2[0]+'</td><td>'+arr2[1]+'</td><td>'+arr2[2]+'</td><td>'+arr2[3]+'</td><td>'+arr2[4]+'</td><td>'+arr2[5]+'</td><td>'+arr2[6]+'</td><td>'+arr2[7]+'</td><td>'+arr2[8]+'</td><td>'+arr2[9]+'</td><td>'+arr2[10]+'</td><td>'+arr2[11]+'</td><td>'+arr2[12]+'</td><td>'+arr2[13]+'</td>');
			$("#major2").after('<td>'+arr1[14]+'</td><td>'+arr1[15]+'</td><td>'+arr1[16]+'</td><td>'+arr1[17]+'</td><td>'+arr1[18]+'</td><td>'+arr1[19]+'</td><td>'+arr1[20]+'</td><td>'+arr1[21]+'</td><td>'+arr1[22]+'</td><td>'+arr1[23]+'</td><td>'+arr1[24]+'</td><td>'+arr1[25]+'</td><td>'+arr1[26]+'</td><td>'+arr1[27]+'</td>');
             		$("#Num2").after('<td>'+arr2[14]+'</td><td>'+arr2[15]+'</td><td>'+arr2[16]+'</td><td>'+arr2[17]+'</td><td>'+arr2[18]+'</td><td>'+arr2[19]+'</td><td>'+arr2[20]+'</td><td>'+arr2[21]+'</td><td>'+arr2[22]+'</td><td>'+arr2[23]+'</td><td>'+arr2[24]+'</td><td>'+arr2[25]+'</td><td>'+arr2[26]+'</td><td>'+arr2[27]+'</td>');
	         },
		
		
		error:function(){
			alert("数据加载失败");			
		}
		
	});
});