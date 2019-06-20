<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<script type="text/javascript">
	
	google.setOnLoadCallback(drawChartcat);
      function drawChartcat() {

        var datacat = new google.visualization.DataTable();
		    datacat.addColumn('string', 'category');
		    datacat.addColumn('number', 'Hours per Day');
		    datacat.addColumn({type: 'string', role: 'tooltip'});
		    datacat.addRows([
		    	<?php if($taskByCat!='0'){
          	foreach ($taskByCat as $t) {
          		//pr($t);
				 ?>
		        ['<?php echo ($t['task_category_id']!='0')?get_category_name($t['task_category_id']):'No Category';?>', <?php echo $t['task_time_estimate'];?>, '<?php echo minutesToTime($t['task_time_estimate']);?>'],
		         <?php } }?>
		        
		    ]);
		    
		var options = {
          //title: 'Time Allocation by Category',
          legend: { position: 'bottom' },
          width:'100%',
          height:'100%'
        };

        var chartcat = new google.visualization.PieChart(document.getElementById('piechartcat'));

        chartcat.draw(datacat, options);
      
      // window.onresize = function(){ chartcat.draw(datacat, options);};  
       
      }
</script>
<div class="text-center chartdiv ajax_team_category_data" id="piechartcat" ></div>
<div class="text-center chartdiv ajax_team_category_data" id="piechartcat1" style="display: none" ></div>