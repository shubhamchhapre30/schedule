<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<script type="text/javascript">
	
	google.setOnLoadCallback(drawChart);
      function drawChart() {

       var data = new google.visualization.DataTable();
		    data.addColumn('string', 'Task');
		    data.addColumn('number', 'Hours per Day');
		    data.addColumn({type: 'string', role: 'tooltip'});
		    data.addRows([
		        ['Allocated', <?php echo $allocated;?>, '<?php echo minutesToTime($allocated);?>'],
		        ['Not Allocated', <?php echo $nonallocated;?>, '<?php echo minutesToTime($nonallocated);?>']
		    ]);
		
		var options = {
          //title: 'Time Allocation for Today',
          legend: { position: 'bottom' },
          width:'100%',
          height:'100%'
        };

        var chart = new google.visualization.PieChart(document.getElementById('teampiechart'));

        chart.draw(data, options);
               
     
      //window.onresize = function(){ chart.draw(data, options);};
       
      }
</script>
<div class="text-center chartdiv ajax_team_time_data" id="teampiechart"></div>
<div class="text-center chartdiv ajax_team_time_data" id="teampiechart1" style="display: none"></div>