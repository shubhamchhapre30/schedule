<script type="text/javascript" src="<?php echo base_url().getThemename();?>/assets/js/jsapi"></script>
<script type="text/javascript">

// chart for timer task spent
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawVisualization);

    function drawVisualization() {
        // Some raw data (not necessarily accurate)
    	var data = google.visualization.arrayToDataTable([
        	['Date', 'Spent time per day'],
     		<?php 
     		for($i=6;$i>=0;$i--){
     			
     			$dt = date("M j",strtotime("-".$i." days",toDateUserTimeStamp(date("Y-m-d"))));
				
				$time = user_total_spent_time_per_day(date("Y-m-d",strtotime("-".$i." days"))); ?>
			['<?php echo $dt;?>',  <?php echo $time;?>],
			<?php   } ?>
      	]);

		var options = {
      		title : 'Time spent last 7 days',
      		width : 720,
      		height : 400,
      		vAxis: {title: 'Time spent (In minutes)'},
      		hAxis: {title: 'Last 7 days (dates)'},
      		seriesType: 'bars',
      		series: {5: {type: 'line'}}
    	};

    	var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    	chart.draw(data, options);
  	}
	
	// pie chart for intrruption of last 7 days
	google.setOnLoadCallback(drawChart);
  	function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Interruptions', 'Total of last 7 days'],
          ['Need to leave',    <?php echo get_user_interruptions('Need to leave');?>],
          ['Meeting',      <?php echo get_user_interruptions('Meeting');?>],
          ['Phone call',  <?php echo get_user_interruptions('Phone call');?>],
          ['Email', <?php echo get_user_interruptions('Email');?>],
          ['Co-worker interruption',    <?php echo get_user_interruptions('Co-worker interruption');?>],
          ['Others', <?php echo get_user_interruptions('Others');?>]
        ]);

        var options = {
          title: 'Last 7 days Interruptions',
          width:750,
          height:250
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
	}
	  
	  
	// BAR CHART FOR number of interruptions per day by type for the past 7 days 
	google.setOnLoadCallback(drawVisualization1);
	
	function drawVisualization1() {
        // Some raw data (not necessarily accurate)
    	var data = google.visualization.arrayToDataTable([
        	['Month', 'Need to leave', 'Meeting', 'Phone call', 'Email', 'Co-worker interruption', 'Others'],
        	<?php for($i=6;$i>=0;$i--){
        		
		   		$dt = date("M j",strtotime("-".$i." days",toDateUserTimeStamp(date("Y-m-d")))); 
				
		   		
				$pass_dt = date("Y-m-d",strtotime("-".$i." days"));
				 ?>
			['<?php echo $dt;?>',  <?php echo user_total_interruptions('Need to leave',$pass_dt);?>, <?php echo user_total_interruptions('Meeting',$pass_dt);?>, <?php echo user_total_interruptions('Phone call',$pass_dt);?>, <?php echo user_total_interruptions('Email',$pass_dt);?>, <?php echo user_total_interruptions('Co-worker interruption',$pass_dt);?>, <?php echo user_total_interruptions('Others',$pass_dt);?>],
			<?php   } ?>
      	]);

    	var options = {
      		title : 'Number of interruptions per day by type for the last 7 days',
//      		vAxis: {title: 'Last 7 days (dates)'},
      		hAxis: {title: 'Number of interruptions'},
      		width:700,
          	height:250,
      		isStacked: true
    	};

    	var chart = new google.visualization.BarChart(document.getElementById('chart_div1'));
    	chart.draw(data, options);
  	}   
    
</script>


 <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h3>Statistics</h3>
</div>
<div class="modal-body">
       <div class="row" style="border-radius: 0 0 5px 5px;background-color: #fff; height: 500px;overflow-y: scroll;">
	<!-- BEGIN PAGE CONTENT-->
	<div class="taskmain-container">
            <div class="user-block" style="overflow: hidden;border-radius: 0 0 5px 5px;">
       		<div class="row">
				<div class="col-md-12 ">
					<div class="usertabs">
						<div class="tabbable tabbable-custom">
                                                    <ul class="nav nav-tabs" style="background-color: #fff;margin-bottom: 0px;padding: 2px;">
								<li class="active"><a href="#foot_tab_1" data-toggle="tab">Timer</a></li>
								<li><a  href="#foot_tab_2" data-toggle="tab">Interruptions</a></li>
					 		</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="foot_tab_1">
									<div class="portlet">
										<div id="chart_div"></div>
									</div>
								</div> <!-- Tab 1 -->
								<div class="tab-pane" id="foot_tab_2">
									<div class="portlet">
										<div id="piechart"></div>
                                                                                <div id="chart_div1" style="width:100%;"></div>
										
									</div>
								</div> <!-- Tab 2 -->
					 		</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<!-- END PAGE CONTENT-->
 </div>
         </div>
     </div>