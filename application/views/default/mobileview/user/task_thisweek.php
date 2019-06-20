<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			   <div class="page-controler clearfix margin-bottom-20">
				 		 <div class="pull-left"> 
							<a href="<?php echo site_url('user/dashboard_menu');?>" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div> 
					 
				 </div>
				 
			 	<div class="lighgrey-block ">
					<div class="progress-list progress-loop">
						<ul class="list-unstyled">
							<?php if($task_thisweek!='0'){
								$format = '%02d:%02d'; 
                				foreach ($task_thisweek as $t) {
							
								if($user_time!='0'){
									
									if($t["day"] =='Monday'){
							
									$is_availabe = $user_time->MON_closed;
									
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
									$prog = minutesToHourMinutes($user_time->MON_hours);
									$ration = ($user_time->MON_hours!='0')?(round($t["task_time"]/($user_time->MON_hours)*100)):0;
									$prog_clr = ($ration > 50)?'red':'blue';
									
									$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									
									 
								}
								if($t["day"] =='Tuesday'){
										
									$is_availabe = $user_time->TUE_closed;
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
									$prog = minutesToHourMinutes($user_time->TUE_hours);
									$ration = ($user_time->TUE_hours!='0')?(round($t["task_time"]/($user_time->TUE_hours)*100)):0;
									$prog_clr = ($ration > 50)?'red':'blue';
									
									$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									
								}
								if($t["day"] =='Wednesday'){
										
									$is_availabe = $user_time->WED_closed;
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
									$prog = minutesToHourMinutes($user_time->WED_hours);
									$ration = ($user_time->WED_hours!='0')?(round($t["task_time"]/($user_time->WED_hours)*100)):0;
									$prog_clr = ($ration > 50)?'red':'blue';
									
									$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									 
								}
								if($t["day"] =='Thursday'){
										
									$is_availabe = $user_time->THU_closed;
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
									$prog = minutesToHourMinutes($user_time->THU_hours);
									$ration = ($user_time->THU_hours!='0')?(round($t["task_time"]/($user_time->THU_hours)*100)):0;
									$prog_clr = ($ration > 50)?'red':'blue';
									
									$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									 
								}
								if($t["day"] =='Friday'){
										
									$is_availabe = $user_time->FRI_closed;
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
									$prog = minutesToHourMinutes($user_time->FRI_hours);
									$ration = ($user_time->FRI_hours!='0')?(round($t["task_time"]/($user_time->FRI_hours)*100)):0;
									$prog_clr = ($ration > 50)?'red':'blue';
									
									$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									 
								}
								if($t["day"] =='Saturday'){
										
									$is_availabe = $user_time->SAT_closed;
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
									$prog = minutesToHourMinutes($user_time->SAT_hours);
									$ration = ($user_time->SAT_hours!='0')?(round($t["task_time"]/($user_time->SAT_hours)*100)):0;
									$prog_clr = ($ration > 50)?'red':'blue';
									
									$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									
								}
								if($t["day"] =='Sunday'){
										
									$is_availabe = $user_time->SUN_closed;
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
									$prog = minutesToHourMinutes($user_time->SUN_hours);
									$ration = ($user_time->SUN_hours!='0')?(round($t["task_time"]/($user_time->SUN_hours)*100)):0;
									$prog_clr = ($ration > 50)?'red':'blue';
									
									$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
										 
								}
								 ?>
								
								<li> 
									<div class="progess-title"><?php echo $t["day"];?> <span> <?php echo $time_with_format;?> / <?php echo $prog;?></span> </div>
									<div class="progress progress-<?php echo $prog_clr;?>">
										<div class="bar" style="width: <?php echo $ration;?>%;"></div>
									  </div>
								 </li>
								 
								 <?php } } } ?>
							
						</ul>
					</div>
				</div>
				
			 
				
				  
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>