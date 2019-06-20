<?php date_default_timezone_set($this->session->userdata("User_timezone"));?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemename();?>/assets/js/jsapi"></script>
<script type="text/javascript">




// chart for timer task spent
	google.load("visualization", "1", {packages:["corechart"]});
	// BAR CHART FOR number of interruptions per day by type for the past 7 days 
	google.setOnLoadCallback(drawVisualization1);
	
	function drawVisualization1() {
        // Some raw data (not necessarily accurate)
    	var data = google.visualization.arrayToDataTable([
    		<?php $categories = get_company_category($this->session->userdata('company_id'),'Active'); ?>
        	['Date'
        	<?php if($categories){
        		foreach($categories as $cat){
        			echo ','."'$cat->category_name'";
        		}
        	}?>
        	 ],
        	<?php 
        	if(isset($reports_data) && $reports_data!=''){
        	foreach($reports_data as $row){
		   		$dt = date($site_setting_date,strtotime($row['task_true_date'])); 
				
				 ?>
			['<?php echo $dt;?>'
				<?php if($categories){
					foreach($categories as $cat){
						echo ',';
						if($cat->category_id == $row['task_category_id']){
							echo $row['actual_time']/60;
						} else {
							echo '0';
						}
					}
				}?>
			],
			<?php  } } ?>
      	]);

    	var options = {
      		title : 'Actual time by category over a period of time',
      		vAxis: {title: 'Actual time in hours'},
      		hAxis: {title: 'Dates'},
      		width:950,
          	height:400,
      		seriesType: 'bars',
      		series: {5: {type: 'line'}}
    	};

    	// Instantiate and draw our chart, passing in some options.
        var chart_div = document.getElementById('pie_chart_div');
        var chart = new google.visualization.ComboChart(chart_div);

        // Wait for the chart to finish drawing before calling the getIm geURI() method.
        google.visualization.events.addListener(chart, 'ready', function ()      {
         chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
         
         var image =  chart_div.innerHTML;
         
         $(document).ready(function(){

      
         $.post("<?php echo site_url("reports/chart_html") ?>", 
                    {
                        id: chart.getImageURI()
                       
                    }, 
                    function(data) {
                    	//alert(data.result);
                    	
                      
                        
                    });
});
        });

        chart.draw(data, options);
      }

      // Make the charts responsive
      $(document).ready(function(){
        $(window).resize(function(){
         	drawVisualization1();
        });
      });
  	   
    
</script>
<div id="pie_chart_div"></div>
