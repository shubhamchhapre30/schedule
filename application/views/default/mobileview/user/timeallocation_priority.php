<?php 
	$none = round(taskByPriority('None'));
	$low = round(taskByPriority('Low'));
	$medium = round(taskByPriority('Medium'));
	$high = round(taskByPriority('High'));
//echo "<pre>";print_r($allocationtime);die;?>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawVisualization3);
      function drawVisualization3() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
         <?php //if($none!='0' || $low!='0' || $medium!='0' || $high!='0'){ ?>
          ,['None',     <?php echo $none;?>]
          ,['Low',      <?php echo $low;?>]
          ,['Medium',  <?php echo $medium;?>]
          ,['High', <?php echo $high;?>]
           <?php //} ?>
          
        ]);
		//alert(data.success);
        var options = {
          title: 'My Time Allocation Today',
          legend: { position: 'bottom' }, 
          width:'100%',
          height:'100%',
          fill:"#e5e9ec"
        };

        var chart = new google.visualization.PieChart(document.getElementById('ajax_priority'));

        chart.draw(data, options);
       
      }
</script>