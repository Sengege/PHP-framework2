line = [];
line2 = [];
line3 = [];
$.getJSON("/da/get_count.php",function(data){
    for(i in data.data.groupsNum){
    line.push(data['data'].groupsNum[i]);
    }
    for(m in data.data.meetingsNum){
    line2.push(data['data'].meetingsNum[m]);
    }
    for(n in data.data.studentsNum){
    line3.push(data['data'].studentsNum[n]);
    }
})
require.config({
    paths: {
        echarts: 'js/build/dist'
    }
});

 require(
    [
        'echarts',
        'echarts/chart/line' 
    ],
function (ec) {
            var myChart1 = ec.init(document.getElementById('chart1')); 
            var option = {
                title: {
                    text: "",
                    x: "center",
                    textStyle: {
                        fontSize:24
                    }

                },
                tooltip: {
                    show: true
                },
                legend: {
                    data:['group','User','activities'],
                    x: "center"

                },
                
                toolbox: {
                    show : true,
                    feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                    }
                },
                
                
                xAxis : [
                    {
                        type : 'category',
                        data : ["one","Two","Three","Four","Five","Six"]
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        "name":"group",
                        "type":"line",
                        "data":line
                    },
                    {
                        "name":"User",
                        "type":"line",
                        "data":line2
                    },
                    {
                        "name":"activities",
                        "type":"line",
                        "data":line3
                    }
                ]
            };

        myChart1.setOption(option); 
    }
);

        


        require(
            [
                'echarts',
                'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
            ],
            function (ec) {
                // 基于准备好的dom，初始化echarts图表
                var myChart2 = ec.init(document.getElementById('chart2')); 
                
                var option = {
                    tooltip: {
                        show: true
                    },
                    legend: {
                        data:['major']
                    },
					
					toolbox: {
                    	show : true,
                    	feature : {
                    	mark : {show: true},
                    	dataView : {show: true, readOnly: false},
                    	magicType : {show: true, type: ['line', 'bar']},
                    	restore : {show: true},
                    	saveAsImage : {show: true}
                    	}
               		},
					
					
					
					
                    xAxis : [
                        {
                            type : 'category',
                            data : [arr1[0],arr1[1],arr1[2],arr1[3],arr1[4],arr1[5],arr1[6]]
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            "name":"major",
                            "type":"bar",
                            "data":[arr2[0],arr2[1],arr2[2],arr2[3],arr2[4],arr2[5],arr2[6]]
                        }
                    ]
                };
        // 为echarts对象加载数据 
        myChart2.setOption(option); 
    }
);





pie3 = [];
pie4 = [];
$.getJSON("http://dev.atux.co.uk/da/get_booking.php",function(data3){
    for(var q = 0;q<data3['message'].length;q++){
        pie3.push(data3['message'][q].name);
        pie4.push(data3['message'][q].bookstime);
    }
});

  require(
    [
        'echarts',
        'echarts/chart/pie' 

    ],
    function (ec) {
        var myChart3 = ec.init(document.getElementById('chart3'));
        
        
option = {
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient : 'vertical',
        x : 'left',
        data:[pie3[0],pie3[1],pie3[2]]
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'center',
                        max: 1548
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    series : [
        {
            type:'pie',
            radius : ['50%', '70%'],
            itemStyle : {
                normal : {
                    label : {
                        show : false
                    },
                    labelLine : {
                        show : false
                    }
                },
                emphasis : {
                    label : {
                        show : true,
                        position : 'center',
                        textStyle : {
                            fontSize : '30',
                            fontWeight : 'bold'
                        }
                    }
                }
            },
            data:[
                {value:pie4[0], name:pie3[0]},
                {value:pie4[1], name:pie3[1]},
                {value:pie4[2], name:pie3[2]}
            ]
        }
    ]
};

        myChart3.setOption(option); 
    }
);


        
        
         
         
         
