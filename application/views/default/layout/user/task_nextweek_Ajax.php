<div class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">My Time Next Week</div>
              </div>
              <div class="portlet-body portlet-minhgt minimumhight ">
                <div>
                	<?php if($task_nextweek!='0'){
                		
						$format = '%02d:%02d'; 
                		foreach ($task_nextweek as $t) {
                		 if($user_time!='0'){ 
                    	
							if($t["day"] =='Monday'){
						
								$is_availabe = $user_time->MON_closed;
								
								
								$estimate_hours = intval($t["task_time"]/60);
								//$estimate_minutes = $t["task_time"] - ($estimate_hours * 60);
								$estimate_minutes = ($t["task_time"] % 60);
								//$prog = $user_time->MON_hours;
								$prog = minutesToHourMinutes($user_time->MON_hours);
								$ration = ($user_time->MON_hours!='0')?(round($t["task_time"]/($user_time->MON_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								
								//$time_with_format =  gmdate("H:i", ($t["task_time"] * 60));
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
								 
							}
							if($t["day"] =='Tuesday'){
									
								$is_availabe = $user_time->TUE_closed;
								//$format = '%02d:%02d';
								$estimate_hours = intval($t["task_time"]/60);
								//$estimate_minutes = $t["task_time"] - ($estimate_hours * 60);
								$estimate_minutes = ($t["task_time"] % 60);
								//$prog = $user_time->TUE_hours;
								$prog = minutesToHourMinutes($user_time->TUE_hours);
								
								$ration = ($user_time->TUE_hours!='0')?(round($t["task_time"]/($user_time->TUE_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								
								//$time_with_format =  gmdate("H:i", ($t["task_time"] * 60));
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
								
							}
							if($t["day"] =='Wednesday'){
									
								$is_availabe = $user_time->WED_closed;
								//$format = '%02d:%02d';
								$estimate_hours = intval($t["task_time"]/60);
								//$estimate_minutes = $t["task_time"] - ($estimate_hours * 60);
								$estimate_minutes = ($t["task_time"] % 60);
								//$prog = $user_time->WED_hours;
								$prog = minutesToHourMinutes($user_time->WED_hours);
								
								$ration = ($user_time->WED_hours!='0')?(round($t["task_time"]/($user_time->WED_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								
								//$time_with_format =  gmdate("H:i", ($t["task_time"] * 60));
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
								 
							}
							if($t["day"] =='Thursday'){
									
								$is_availabe = $user_time->THU_closed;
								//$format = '%02d:%02d';
								$estimate_hours = intval($t["task_time"]/60);
								//$estimate_minutes = $t["task_time"] - ($estimate_hours * 60);
								$estimate_minutes = ($t["task_time"] % 60);
								//$prog = $user_time->THU_hours;
								$prog = minutesToHourMinutes($user_time->THU_hours);
								
								$ration = ($user_time->THU_hours!='0')?(round($t["task_time"]/($user_time->THU_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								 
								 //$time_with_format =  gmdate("H:i", ($t["task_time"] * 60));
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
							}
							if($t["day"] =='Friday'){
									
								$is_availabe = $user_time->FRI_closed;
								//$format = '%02d:%02d';
								$estimate_hours = intval($t["task_time"]/60);
								//$estimate_minutes = $t["task_time"] - ($estimate_hours * 60);
								$estimate_minutes = ($t["task_time"] % 60);
								//$prog = $user_time->FRI_hours;
								$prog = minutesToHourMinutes($user_time->FRI_hours);
								
								$ration = ($user_time->FRI_hours!='0')?(round($t["task_time"]/($user_time->FRI_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								
								//$time_with_format =  gmdate("H:i", ($t["task_time"] * 60));
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
								 
							}
							if($t["day"] =='Saturday'){
									
								$is_availabe = $user_time->SAT_closed;
								//$format = '%02d:%02d';
								$estimate_hours = intval($t["task_time"]/60);
								//$estimate_minutes = $t["task_time"] - ($estimate_hours * 60);
								$estimate_minutes = ($t["task_time"] % 60);
								//$prog = $user_time->SAT_hours;
								$prog = minutesToHourMinutes($user_time->SAT_hours);
								
								$ration = ($user_time->SAT_hours!='0')?(round($t["task_time"]/($user_time->SAT_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								
								//$time_with_format =  gmdate("H:i", ($t["task_time"] * 60));
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
								
							}
							if($t["day"] =='Sunday'){
									
								$is_availabe = $user_time->SUN_closed;
								//$format = '%02d:%02d';
								$estimate_hours = intval($t["task_time"]/60);
								//$estimate_minutes = $t["task_time"] - ($estimate_hours * 60);
								$estimate_minutes = ($t["task_time"] % 60);
								//$prog = $user_time->SUN_hours;
								$prog = minutesToHourMinutes($user_time->SUN_hours);
								
								$ration = ($user_time->SUN_hours!='0')?(round($t["task_time"]/($user_time->SUN_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								
								//$time_with_format =  gmdate("H:i", ($t["task_time"] * 60));
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
									 
							}
						   ?>
						
				<div class="progress-loop margin-top-10 clearfix">
                    <div style="float:left;width:20%"> <span> <?php echo $t["day"];?> </span></div>
                    <div style="width:60%;float:left;">
                      <div class="progress">
                        <div style="width: <?php echo $ration;?>%; background-color:<?php echo $prog_clr; ?>" class="progress-bar" role="progressbar"></div>
                      </div>
                    </div>
                    <div class="text-right"> <span ><?php echo $time_with_format;?> / <?php echo $prog;?> </span></div>
                     </div>
                    <?php } } }else { ?>
                  	<div class="progress-loop margin-top-20 clearfix txt-clr" > No task Available</div>
                  	<?php } ?>
                
              </div>
              <div>
                  <div class="text-right "> <a onclick="getPreviousweek();" href="javascript:;" class="btn btn-common-blue"><i class="stripicon iconleftarro "></i> Previous Week  </a> </div>
                </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
