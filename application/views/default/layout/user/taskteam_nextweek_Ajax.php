<div class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">My Team's Time This Week</div>
              </div>
              <div class="portlet-body minimumhight portlet-minhgt">
                <div>
                	
                	<?php if($task_nextweekteam!='0'){
                		$format = '%02d:%02d';
						  foreach ($task_nextweekteam as $t) {
                			?>
							
					
                  
                  	<?php 
                  		if($t["day"] =='Monday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($MON_hours);
								$ration = ($MON_hours!='0')?(round($t["task_time"]/($MON_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								 
							}
							if($t["day"] =='Tuesday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($TUE_hours);
								$ration = ($TUE_hours!='0')?(round($t["task_time"]/($TUE_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Wednesday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($WED_hours);
								$ration = ($WED_hours!='0')?(round($t["task_time"]/($WED_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Thursday'){
								if($t["task_time"]!='0'){
										$estimate_hours = intval($t["task_time"]/60);
										$estimate_minutes = ($t["task_time"] % 60);
									}else{
										$estimate_hours = 0;
										$estimate_minutes = 0;
									}
								$prog = minutesToHourMinutes($THU_hours);
								$ration = ($THU_hours!='0')?(round($t["task_time"]/($THU_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Friday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($FRI_hours);
								$ration = ($FRI_hours!='0')?(round($t["task_time"]/($FRI_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Saturday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($SAT_hours);
								$ration = ($SAT_hours!='0')?(round($t["task_time"]/($SAT_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Sunday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($SUN_hours);
								$ration = ($SUN_hours!='0')?(round($t["task_time"]/($SUN_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
							}
						    ?>
						  
					<div class="progress-loop margin-top-10 clearfix">	 
                    <div style="float:left;width:20%"> <span> <?php echo $t["day"];?> </span></div>
                    <div style="width:60%;float:left;">
                      <div class="progress">
                        <div style="width: <?php echo $ration;?>%; background-color:<?php echo $prog_clr;?>" class="progress-bar" role="progressbar"></div>
                      </div>
                    </div>
                    <div class="text-right"> <span ><?php echo $time_with_format;?> / <?php echo $prog;?> </span></div>
                     </div>
                    <?php } }else{ ?>
                		<div class="progress-loop margin-top-20 clearfix txt-clr"> No task Available</div>
                		
                	<?php 	} ?>
              </div>
              <div>
                  <div class="text-right "> <a onclick="getPreviousweek();" href="javascript:;" class="btn btn-common-blue"><i class="stripicon iconleftarro"></i> Previous Week  </a> </div>
                </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
