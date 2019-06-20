<?php 
								if(!empty($teamtodolist)){
									//echo "here";die;
									foreach ($teamtodolist as $t) {
										
										$name = get_user_name($t['task_allocated_user_id']);
										$user_name = $name->first_name." ".$name->last_name[0].".";
										
										if($t['task_scheduled_date']!= '0000-00-00' ){
											$due_dt = $t['task_scheduled_date'];
										} else {
											$due_dt = $t['task_due_date'];
										}
										
										if($t['task_priority']=='None'){ $tsk_st = "";}
										if($t['task_priority']=='Low'){ $tsk_st = "greennoticon";}
										if($t['task_priority']=='Medium'){ $tsk_st = "yellownoticon";}
										if($t['task_priority']=='High'){ $tsk_st = "rednoticon";} 
								?>
										
									
							  <tr>
								 
								<td>
									<div title="<?php echo $t['task_description'];?> class="txt-heading1"> <a href="<?php echo ($t['task_project_id']=='0')?site_url('task/view_task/'.base64_encode($t['task_id'])):site_url('task/view_task/'.base64_encode($t['task_id']));?>" ><?php echo (strlen($t['task_title']) > 27)?substr(ucwords($t['task_title']),0, 24).'...':ucwords($t['task_title']);?></a></div>
									 
									<div class="txt-heading2">Allocated to : &nbsp;<?php echo $user_name;?> </div>
								 </td>
								 
								 <?php 
			                        foreach($task_status as $ts){
			                        if($ts->task_status_id == $t['task_status_id']){
			                        		
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
								<!--<td class="text-left"> <div class="status-bx green"> Ready </div> <i class="stripicon rednoticon"> </i> </td>-->
							  </tr>
							  
							  <?php }	}else{  ?>  
							<tr>
								<td colspan="2">
									<div class="txt-heading1"> No records found. </div>
								 </td>
							</tr>
							  	
							  	<?php } ?>