<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawVisualization1);
      function drawVisualization1() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
          <?php  if($allocationtime!='0'){
          	foreach ($allocationtime as $a){
          ?>
          ,['<?php echo get_category_name($a->task_category_id);?>',     <?php echo $a->tasktime;?>]
				  
			 <?php  } } ?>
          
        ]);
		//alert(data.success);
        var options = {
          title: 'My Time Allocation Today',
          legend: { position: 'bottom' }, 
          width:'100%',
          height:'100%',
          fill:"#e5e9ec"
        };

        var chart = new google.visualization.PieChart(document.getElementById('ajax_category'));

        chart.draw(data, options);
       
      }
</script>