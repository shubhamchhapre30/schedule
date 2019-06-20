<?php if($watchlist !='0'){
									
									foreach ($watchlist as $w) {
										
										if($w->task_scheduled_date!= '0000-00-00' ){
											$due_dt = $w->task_scheduled_date;
										} else {
											$due_dt = $w->task_due_date;
										}
										
										if($w->task_priority=='None'){ $tsk_st = "";}
										if($w->task_priority=='Low'){ $tsk_st = "greennoticon";}
										if($w->task_priority=='Medium'){ $tsk_st = "yellownoticon";}
										if($w->task_priority=='High'){ $tsk_st = "rednoticon";} 
										?>
										
							<tr> 
								<td>
									<!--
									<div title="<?php echo $w->task_description;?>" class="txt-heading1"><?php echo (strlen($w->task_title) > 27)?substr(ucwords($w->task_title),0, 24).'...':ucwords($w->task_title);?> </div>
																		<div class="txt-heading2"> Allocated to : <?php echo $w->first_name." ".$w->last_name;?> </div>-->
									<div title="<?php echo $w->task_description;?> class="txt-heading1"> <a href="<?php echo ($w->task_project_id=='0')?site_url('task/view_task/'.base64_encode($w->task_id)):site_url('task/view_task/'.base64_encode($w->task_id));?>" ><?php echo (strlen($w->task_title) > 27)?substr(ucwords($w->task_title),0, 24).'...':ucwords($w->task_title);?></a></div>	
									
									<div class="txt-heading2"> <?php echo date($site_setting_date,strtotime($due_dt));?> </div>
								 </td>
								 
								 <?php 
			                        foreach($task_status as $ts){
			                        if($ts->task_status_id == $w->task_status_id){
			                        		
			                        	if($ts->task_status_name=='Not Ready')
										{
											$tsk_clr = "red";
										}
										if($ts->task_status_name=='Ready')
										{
											$tsk_clr = "green";
										}
										if($ts->task_status_name=='In Progress')
										{
											$tsk_clr = "black";
										}
										
										if( $ts->task_status_name!='In Progress' && $ts->task_status_name!='Ready' && $ts->task_status_name!='Not Ready')
										{
											$tsk_clr = "parrot";
										}
										
			                        ?>
			                        <td class="text-left"><div class="status-bx <?php echo $tsk_clr;?>"><?php echo $ts->task_status_name;?> </div> <i class="stripicon <?php echo $tsk_st;?>"> </i></td>
			                        <?php } } ?>
								</tr>
								<?php }
								}else{ ?>  
							<tr>
								<td colspan="2">
									<div class="txt-heading1"> No watchlist records. </div>
								 </td>
							</tr>			
						<?php } ?>