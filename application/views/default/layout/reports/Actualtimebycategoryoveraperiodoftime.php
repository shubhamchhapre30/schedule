<?php date_default_timezone_set($this->session->userdata("User_timezone"));?>
<script type="text/javascript" src="<?php echo base_url().getThemename();?>/assets/js/jsapi"></script>
<script type="text/javascript">
	
// chart for timer task spent
	google.load("visualization", "1", {packages:["corechart"]});
	// BAR CHART FOR number of interruptions per day by type for the past 7 days 
	google.setOnLoadCallback(drawVisualization1);
	
	function drawVisualization1() {
        // Some raw data (not necessarily accurate)
    	var data = google.visualization.arrayToDataTable([
    		<?php //$categories = get_company_category($this->session->userdata('company_id'),'Active'); ?>
        	['Date'
        	<?php if($categories){
        		foreach($categories as $cat){
        			echo ','."'$cat->category_name'";
        		}
        	}?>
        	 ],
        	<?php  $pre_dt = '';
        	if(isset($reports_data) && $reports_data!=''){
        		
        	foreach($reports_data as $row){
		   		$dt = date($site_setting_date,strtotime($row['task_true_date'])); 
				$a = "['".$dt."'";
				
				if($categories){
					foreach($categories as $cat){
						$a .= ',';
						$mycal = explode(',',$row['mycal']);
						$mytime = explode(',', $row['mytime']);
						
						////for($i=0;$i<count($mycal);$i++){
						//	if($cat->category_id == $mycal[$i]){
							//	$a .= $mytime[$i]/60;
							//} 
							// else {
								// $a .= '0';
							// }
							//pr($row);
							//echo $cat->task_category_id;
							$position = array_search($cat->task_category_id, $mycal);
							//echo "position: ".$position;
							if ($position !== false) {
							    $a .= round($mytime[$position]/60, 2);
							} else {
							   $a .= 0;
							}
							
							//die;
						//}
					}
				}
				
				$a .="],"; 
				
				?>
				
			
			<?php  
             echo $a;
                }
			
			 } ?>
      	]);

    	var options = {
      		title : 'Actual time by category over a period of time',
      		vAxis: {title: 'Actual time in hours'},
      		hAxis: {title: 'Dates'},
      		width:800,
          	height:400,
      		seriesType: 'bars',
      		series: {5: {type: 'line'}}
    	};

    	var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
    	//chart.draw(data, options);
    	
    	var chart_div = document.getElementById('chart_img_div');
        //var chart = new google.visualization.ComboChart(chart_div);

        // Wait for the chart to finish drawing before calling the getIm geURI() method.
        google.visualization.events.addListener(chart, 'ready', function ()      {
         chart_div.innerHTML = chart.getImageURI();
        });

        chart.draw(data, options);
  	}   
    
</script>
<div id="chart_div1"></div>
<div id="chart_img_div" style="display: none;"></div>
