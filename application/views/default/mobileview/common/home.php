<div class="wrapper row2 grey-block">
	<div class="mainpage-container">
		<div class="page-container">
  			<div class="container">
			 	<div class="title-block margin-bottom-40">
					<!--<div class="text-center margin-bottom-20 animated bounceIn"> <img  src="img/logo-icon.png" alt="logoicon" />  </div>-->
					<!--<h2 class="title-heading text-upper"><span> Home </span>   </h2>-->
					<button type="submit" onClick=location.href="<?php echo base_url('task/add_ind_task');?>" class="btn blue medium "> New Task </button>
				 </div>
				
				<div class="">
					<div class="home-list">
						<ul class="list-unstyled">
							<li> <a href="<?php echo site_url('user/today_tasks');?>"> <i class="stripicon todolisticon"> </i> Today's To Do List  </a> </li> 
							<li> <a href="<?php echo site_url('user/mywatchlist');?>"> <i class="stripicon mywatchicon"> </i> My Watch List  </a> </li> 
							<li> <a href="<?php echo site_url('task/kanban');?>"> <i class="stripicon kanbanicon"> </i> Kanban View  </a> </li> 
							<li> <a href="javascript://"> <i class="stripicon weeklycalicon"> </i> Weekly Calendar View  </a> </li> 
							<li> <a href="<?php echo site_url('project/list_project');?>"> <i class="stripicon myprojecticon"> </i> My Project  </a> </li> 
							<li> <a href="<?php echo site_url('user/dashboard_menu');?>"> <i class="stripicon dashboardicon"> </i> Dashboard  </a> </li> 
						</ul>
					</div>
			  </div>
			 	 <!--<hr class="hrline"> 
				 <div class="text-center margin-top-20 margin-bottom-20">
				 	
				 </div>-->
			 
       
 			</div> <!-- /container -->
		</div>
	</div>
</div>