<?php
$uriseg=uri_string();
$theme_url = base_url().getThemeName();
$uri=explode('/',$uriseg);
$method = $this->uri->segment(1); 
$fun =   $this->uri->segment(2);
$last_rember_values = get_user_last_rember_values();
 
$is_manager = "0";
if(get_users_under_manager()!="0"){
	$is_manager = "1";
}
?>
<div class="wrapper row2">
	<div class="mainpage-container">
		
		<div class="page-container ">
			<a href="<?php echo site_url('home/main');?>" class="btn blue btn-sm margin-left-10"> <i class="stripicon backicon"> </i> Back </a>
			<div class="container">
				 <div class="page-title margin-bottom-25">
				 	<h2 class="text-center"> My Dashboard </h2>
				 </div>
				 <div class="grey-block margin-bottom-30">
					<div class="dashborad-list">
						<ul class="list-unstyled">
							<li> <a href="<?php echo site_url('user/task_thisweek');?>">My Time This Week </a> </li> 
							<li> <a href="<?php echo site_url('user/task_today');?>">My Time Today </a> </li> 
						 </ul>
					</div>
				 </div>
				 <?php
          
           			if($is_manager == "1"){ 
          		?>
				  <div class="page-title margin-bottom-25">
				 	<h2 class="text-center"> Team  Dashboard  </h2>
				 </div>
				 <div class="grey-block">
					<div class="dashborad-list">
						<ul class="list-unstyled">
							<li> <a href="<?php echo site_url('user/team_task_due');?>">My Team's Tasks Due Today </a> </li> 
							<li> <a href="<?php echo site_url('user/overdue_task');?>">Overdue Tasks </a> </li> 
							<li> <a href="<?php echo site_url('user/team_time_thisweek');?>">My Team's Time This Week </a> </li> 
							<li> <a href="<?php echo site_url('user/team_allocation_by_category');?>">Team Allocation by Category </a> </li> 
						 </ul>
					</div>
				 </div>
				 <?php } ?>
			 </div> <!-- /container -->
		</div>
	</div>
</div>