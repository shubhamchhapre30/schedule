<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			   <div class="page-controler clearfix margin-bottom-20">
				 		 <div class="pull-left"> 
							<a href="<?php echo site_url('user/dashboard_menu');?>" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div> 
						<!--<div class="pull-right"> 
							<div class="btn-group btn-control-action">
								  <button type="button" class="btn blue btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									By Priority <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu">
									<li><a href="#">Action</a></li>
									<li><a href="#">Another action</a></li>
									<li><a href="#">Something else here</a></li>
									 
								  </ul>
								</div>
							 
						</div>-->
				 </div>
				 
				 <div class="margin-bottom-20 text-center">
				 	<div class="btn-group btn-control-action">
								  <!--
								  <button type="button" class="btn blue btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	  This Month <span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu">
																	  <li><a href="#">Action</a></li>
																	  <li><a href="#">Another action</a></li>
																	  <li><a href="#">Something else here</a></li>
																																			</ul>-->
							<select name="by_period" id="by_period" onchange="filterbyperiod(this.value)" class="btn blue btn-sm" tabindex="1">
								<option value="this_week">This Week</option>
								<option value="next_week">Next Week</option>
								<option value="this_month">This Month</option>
								<option value="this_week">Next Month</option>
							</select>
								</div>
				 </div>
				
				<div class="grey-block">
					 <div class="graph text-center">
					 	<!--<img src="img/graph-2.png" class="img-responsive" alt="img"/>-->
					 	<div class="text-center chartdiv" id="teamtimetask_thisweek"></div>
					 	<div style="display: none;" class="customtable scroll" id="teamtimetask_nextweek"></div>
					 	<div style="display: none;" class="customtable scroll" id="teamtimetask_thismonth"></div>
					 	<div style="display: none;" class="customtable scroll" id="teamtimetask_nextmonth"></div>
					 	
					 	
					 </div>
				</div>
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<script type="text/javascript">
	
	// time this week for team
	
	  google.setOnLoadCallback(drawVisualization);
      function drawVisualization() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
          <?php  if(isset($team_thisweek) && $team_thisweek !=''){
          	foreach ($team_thisweek as $a){
          		
          ?>
          ,['<?php echo get_category_name($a->task_category_id);?>',     <?php echo $a->tasktime;?>]
				  
			 <?php  } } ?>
          
        ]);
		//alert(data.success);
        var options = {
          title: 'Team Allocation By Category',
          legend: { position: 'bottom' }, 
          width:'100%',
          height:'100%',
          fill:"#e5e9ec"
        };

        var chart = new google.visualization.PieChart(document.getElementById('teamtimetask_thisweek'));

        chart.draw(data, options);
       
      }
      
      // time next week for team
      
      google.setOnLoadCallback(drawVisualization1);
      function drawVisualization1() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
          <?php  if(isset($team_nextweek) && $team_nextweek !=''){
          	foreach ($team_nextweek as $a){
          		
				//print_r($team_thisweek);
          ?>
          ,['<?php echo get_category_name($a->task_category_id);?>',     <?php echo $a->tasktime;?>]
				  
			 <?php  } } ?>
          
        ]);
		//alert(data.success);
        var options = {
          title: 'Team Allocation By Category',
          legend: { position: 'bottom' }, 
          width:'100%',
          height:'100%',
          fill:"#e5e9ec"
        };

        var chart = new google.visualization.PieChart(document.getElementById('teamtimetask_nextweek'));

        chart.draw(data, options);
       
      }
      
      // time this month for team
      
      google.setOnLoadCallback(drawVisualization2);
      function drawVisualization2() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
          <?php  if(isset($team_thismonth) && $team_thismonth !=''){
          	foreach ($team_thismonth as $a){
          		
				//print_r($team_thisweek);
          ?>
          ,['<?php echo get_category_name($a->task_category_id);?>',     <?php echo $a->tasktime;?>]
				  
			 <?php  } } ?>
          
        ]);
		//alert(data.success);
        var options = {
          title: 'Team Allocation By Category',
          legend: { position: 'bottom' }, 
          width:'100%',
          height:'100%',
          fill:"#e5e9ec"
        };

        var chart = new google.visualization.PieChart(document.getElementById('teamtimetask_thismonth'));

        chart.draw(data, options);
       
      }
      
      // time next month for team
      
      google.setOnLoadCallback(drawVisualization3);
      function drawVisualization3() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
          <?php  if(isset($team_nextmonth) && $team_nextmonth !=''){
          	foreach ($team_nextmonth as $a){
          		
				//print_r($team_thisweek);
          ?>
          ,['<?php echo get_category_name($a->task_category_id);?>',     <?php echo $a->tasktime;?>]
				  
			 <?php  } } ?>
          
        ]);
		//alert(data.success);
        var options = {
          title: 'Team Allocation By Category',
          legend: { position: 'bottom' }, 
          width:'100%',
          height:'100%',
          fill:"#e5e9ec"
        };

        var chart = new google.visualization.PieChart(document.getElementById('teamtimetask_nextmonth'));

        chart.draw(data, options);
       
      }
      
</script>

<script type="text/javascript">
	
	function filterbyperiod(type)
	{
		var id = type;
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/team_allocation_by_category"); ?>',
				data : {id:id},
				timeout: 500, 
				success : function(data){
					if(id=="this_week")
					{
						google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization});
						$('#teamtimetask_thisweek').css("display","block");
						$('#teamtimetask_nextweek').css("display","none");
						$('#teamtimetask_thismonth').css("display","none");
						$('#teamtimetask_nextmonth').css("display","none");
						
					}
					if(id=="next_week")
					{
						google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization1});
						$('#teamtimetask_thisweek').css("display","none");
						$('#teamtimetask_nextweek').css("display","block");
						$('#teamtimetask_thismonth').css("display","none");
						$('#teamtimetask_nextmonth').css("display","none");
						
					}
					if(id=="this_month")
					{
						google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization2});
						$('#teamtimetask_thisweek').css("display","none");
						$('#teamtimetask_nextweek').css("display","none");
						$('#teamtimetask_thismonth').css("display","block");
						$('#teamtimetask_nextmonth').css("display","none");
						
					}
					if(id=="next_month")
					{
						google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization2});
						$('#teamtimetask_thisweek').css("display","none");
						$('#teamtimetask_nextweek').css("display","none");
						$('#teamtimetask_thismonth').css("display","none");
						$('#teamtimetask_nextmonth').css("display","block");
						
					}
					
					$('#dvLoading').fadeOut('slow');
				},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});
				
		}else{
			alertify.alert('Please select atleast one criteria..!!');
		}
	}
</script>