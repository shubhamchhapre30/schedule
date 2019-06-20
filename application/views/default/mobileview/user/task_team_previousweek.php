<?php if($task_thisweekteam!='0'){
                			$format = '%02d:%02d'; 
						 	foreach ($task_thisweekteam as $t) {
						 		 
		                  	 if($user_time!=''){
		                  		
									if($t["day"] =='Monday'){
											
										$is_availabe = $user_time->MON_closed;
											if($t["task_time"]!='0'){
												$estimate_hours = intval($t["task_time"]/60);
												$estimate_minutes = ($t["task_time"] % 60);
											}else{
												$estimate_hours = 0;
												$estimate_minutes = 0;
											}
										$prog = minutesToHourMinutes($MON_hours);
										$ration = ($MON_hours!='0')?(round($t["task_time"]/($MON_hours)*100)):0;
										$prog_clr = ($ration > 50)?'red':'blue';
										
										$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
										 
									}
									if($t["day"] =='Tuesday'){
										$is_availabe = $user_time->TUE_closed;
										if($t["task_time"]!='0'){
												$estimate_hours = intval($t["task_time"]/60);
												$estimate_minutes = ($t["task_time"] % 60);
											}else{
												$estimate_hours = 0;
												$estimate_minutes = 0;
											}
										$prog = minutesToHourMinutes($TUE_hours);
										$ration = ($TUE_hours!='0')?(round($t["task_time"]/($TUE_hours)*100)):0;
										$prog_clr = ($ration > 50)?'red':'blue';
										
										$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
										
									}
									if($t["day"] =='Wednesday'){
											
										$is_availabe = $user_time->WED_closed;
										if($t["task_time"]!='0'){
												$estimate_hours = intval($t["task_time"]/60);
												$estimate_minutes = ($t["task_time"] % 60);
											}else{
												$estimate_hours = 0;
												$estimate_minutes = 0;
											}
										$prog = minutesToHourMinutes($WED_hours);
										$ration = ($WED_hours!='0')?(round($t["task_time"]/($WED_hours)*100)):0;
										$prog_clr = ($ration > 50)?'red':'blue';
										
										$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									}
									if($t["day"] =='Thursday'){
											
										$is_availabe = $user_time->THU_closed;
										if($t["task_time"]!='0'){
												$estimate_hours = intval($t["task_time"]/60);
												$estimate_minutes = ($t["task_time"] % 60);
											}else{
												$estimate_hours = 0;
												$estimate_minutes = 0;
											}
										$prog = minutesToHourMinutes($THU_hours);
										$ration = ($THU_hours!='0')?(round($t["task_time"]/($THU_hours)*100)):0;
										$prog_clr = ($ration > 50)?'red':'blue';
										
										$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
										
									}
									if($t["day"] =='Friday'){
											
										$is_availabe = $user_time->FRI_closed;
										if($t["task_time"]!='0'){
												$estimate_hours = intval($t["task_time"]/60);
												$estimate_minutes = ($t["task_time"] % 60);
											}else{
												$estimate_hours = 0;
												$estimate_minutes = 0;
											}
										$prog = minutesToHourMinutes($FRI_hours);
										$ration = ($FRI_hours!='0')?(round($t["task_time"]/($FRI_hours)*100)):0;
										$prog_clr = ($ration > 50)?'red':'blue';
										
										$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									}
									if($t["day"] =='Saturday'){
											
										$is_availabe = $user_time->SAT_closed;
										if($t["task_time"]!='0'){
												$estimate_hours = intval($t["task_time"]/60);
												$estimate_minutes = ($t["task_time"] % 60);
											}else{
												$estimate_hours = 0;
												$estimate_minutes = 0;
											}
										$prog = minutesToHourMinutes($SAT_hours);
										$ration = ($SAT_hours!='0')?(round($t["task_time"]/($SAT_hours)*100)):0;
										$prog_clr = ($ration > 50)?'red':'blue';
										
										$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									}
									if($t["day"] =='Sunday'){
											
										$is_availabe = $user_time->SUN_closed;
										if($t["task_time"]!='0'){
												$estimate_hours = intval($t["task_time"]/60);
												$estimate_minutes = ($t["task_time"] % 60);
											}else{
												$estimate_hours = 0;
												$estimate_minutes = 0;
											}
										$prog = minutesToHourMinutes($SUN_hours);
										$ration = ($SUN_hours!='0')?(round($t["task_time"]/($SUN_hours)*100)):0;
										$prog_clr = ($ration > 50)?'red':'blue';
										
										$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
									}
						   		?>
							<li> 
								<div class="progess-title"><?php echo $t["day"];?> <span><?php echo $time_with_format;?> / <?php echo $prog;?></span> </div>
								<div class="progress progress-<?php echo $prog_clr;?>">
									<div class="bar" style="width: <?php echo $ration;?>%;"></div>
								  </div>
							 </li>
							 
							 
							  <?php	} } }else{ ?>
                		<td colspan="2">
									<div class="txt-heading1"> No records Available. </div>
								 </td>
                		
                	<?php 	} ?>