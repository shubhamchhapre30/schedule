<?php if($project_tasks){
									foreach ($project_tasks as $p){
										
										$tmp = (array) $p;
										if(!empty($tmp)){
										
										$name = get_user_name($p->task_allocated_user_id);
										//$user_name = ucwords($name->first_name)." ".ucwords($name->last_name[0]).".";
										$user_name = ($name)?ucwords($name->first_name." ".$name->last_name[0]).".":"N/A";
										if($p->task_priority=='None'){ $tsk_st = "";}
										if($p->task_priority=='Low'){ $tsk_st = "greennoticon";}
										if($p->task_priority=='Medium'){ $tsk_st = "yellownoticon";}
										if($p->task_priority=='High'){ $tsk_st = "rednoticon";} 
										
										$task_status_completed_id = get_task_status_id_by_name('Completed');
										if($p->task_status_id == $task_status_completed_id){
											$ts = "c_on";
										}else{
											$ts = "";
										}
								
										
								?>
								
									<tr id="task_id_<?php echo $p->task_id;?>">
										<th scope="row" width="16px;">
											<div class="checkboxes">
												<label class="label_check <?php echo $ts;?>" id="status_<?php echo $p->task_id;?>" for="task_status_<?php echo $p->task_id;?>">
												<input onclick="changestatus('<?php echo $p->task_status_id;?>','<?php echo $p->task_id;?>');" name="task_status" id="task_status_<?php echo $p->task_id;?>" value="" type="checkbox" <?php if($p->task_status_id == $task_status_completed_id){ ?> checked="" <?php }else{ ?>  <?php }  ?> > &nbsp; </label>
												
											</div>
										</th>
										<td>
											<div title="<?php echo $p->task_description;?>" ><a href="<?php echo site_url('task/view_task/'.base64_encode($p->task_id));?>"> <?php echo (strlen($p->task_title) > 40)?substr(ucwords($p->task_title),0, 37).'...':ucwords($p->task_title);?></a> </div>
											<div class="txt-heading2"> Created by : <?php echo ucwords($user_name);?></div>
											<?php 
												if($p->task_scheduled_date != '0000-00-00'  ){
													$due_dt = date($site_setting_date,strtotime($p->task_scheduled_date));
												}  else {
													$due_dt = 'N/A';
												}									
											?>
											<div class="txt-heading2">Due Date : <?php echo $due_dt;?> </div>
											
										 </td>
										<td class="text-right"><i class="stripicon <?php echo $tsk_st;?>"> </i> </td>
									  </tr>						
								<?php }  }	}else{?>
									<tr> 
										<td colspan="4">No tasks Available</td>	
									</tr>
									
									<?php } ?>