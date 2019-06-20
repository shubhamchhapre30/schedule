<?php 
	$theme = getThemeName();
?>
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
									  By Priority <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
									  <li><a href="#">Action</a></li>
									  <li><a href="#">Another action</a></li>
									  <li><a href="#">Something else here</a></li>
																																			</ul>-->
								  
							<select name="by_type" id="by_type" onchange="filterbytype(this.value)" class="btn blue btn-sm" tabindex="1">
								<option value="priority">Priority</option>
								<option value="category">Category</option>
								<option value="project">Project</option>
							</select>
								</div>
				 </div>
				
				<div class="grey-block margin-bottom-30">
					 <div class="graph text-center">
					 	<div class="text-center chartdiv" id="allocationchart"></div>
					 	<div style="display: none;" class="customtable scroll" id="ajax_priority">
			          	 <?php echo $this->load->view($theme.'/mobileview/user/timeallocation_priority.php');?>
			          	 </div>
					 	<div style="display: none;" class="customtable scroll" id="ajax_category">
			          	 <?php echo $this->load->view($theme.'/mobileview/user/timeallocation_category.php');?>
			          	 </div>
			          	 <div style="display: none;" class="customtable scroll" id="ajax_project">
			          	 <?php echo $this->load->view($theme.'/mobileview/user/timeallocation_project.php');?>
			          	 </div>
					 </div>
				</div>
				
				 <p class="text-center pera1 margin-top-20"> Tasks with no Estimated Time : <?php echo $totalNonEstTask;?>  </p>
				 
			  	  
			 </div> <!-- /container -->
		</div>
	</div>
</div>
<?php 
	$none = round(taskByPriority('None'));
	$low = round(taskByPriority('Low'));
	$medium = round(taskByPriority('Medium'));
	$high = round(taskByPriority('High'));


//echo "<pre>";print_r($allocationtime);die;?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<script type="text/javascript">
	
	google.setOnLoadCallback(drawChart);
      function drawChart() {

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

        var chart = new google.visualization.PieChart(document.getElementById('allocationchart'));

        chart.draw(data, options);
       
      }
      
      google.setOnLoadCallback(drawVisualization2);
      function drawVisualization2() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
          <?php  if($allocationtime_project!='0'){
          	foreach ($allocationtime_project as $a){
          		
				//print_r($a);
          ?>
          ,['<?php echo getProjectName($a->task_project_id);?>',     <?php echo $a->tasktime;?>]
				  
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

        var chart = new google.visualization.PieChart(document.getElementById('ajax_project'));

        chart.draw(data, options);
       
      }
      
       google.setOnLoadCallback(drawVisualization1);
      function drawVisualization1() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
          <?php  if($allocationtime_category!='0'){
          	foreach ($allocationtime_category as $a){
          		
				//print_r($a);
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

<script type="text/javascript">
	
	function filterbytype(type)
	{
		var id = type;
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/filterbytype"); ?>',
				data : {id:id},
				timeout: 500, 
				success : function(data){
					if(id=="priority")
					{
						//$('#allocationchart').css("display","block");
						google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization3});
						$("#ajax_priority").html(data);
						$('#allocationchart').css("display","none");
						$('#ajax_priority').css("display","block");
						$('#ajax_category').css("display","none");
						$('#ajax_project').css("display","none");
						//$('#dvLoading').fadeOut('slow');
					}
					if(id=="category")
					{
						google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization1});
						$("#ajax_category").html(data);
						$('#allocationchart').css("display","none");
						$('#ajax_priority').css("display","none");
						$('#ajax_category').css("display","block");
						$('#ajax_project').css("display","none");						
						$('#dvLoading').fadeOut('slow');
					}
					if(id=="project")
					{
						google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization2});
						$("#ajax_project").html(data);
						$('#allocationchart').css("display","none");
						$('#ajax_priority').css("display","none");
						$('#ajax_category').css("display","none");
						$('#ajax_project').css("display","block");
						//$('#dvLoading').fadeOut('slow');
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