<?php
		                    	 if($last_login_task!='0'){
		                    		foreach ($last_login_task as $l) {
		                    			
									if($l['task_scheduled_date']!= '0000-00-00' ){
										$due_dt = $l['task_scheduled_date'];
									} else {
										$due_dt = $l['task_due_date'];
									}
									
									$name = get_user_name($l['task_allocated_user_id']);
									
									if($l['task_priority']=='None'){ $tsk_st = "";}
									if($l['task_priority']=='Low'){ $tsk_st = "greennoticon";}
									if($l['task_priority']=='Medium'){ $tsk_st = "yellownoticon";}
									if($l['task_priority']=='High'){ $tsk_st = "rednoticon";} 
									
									$task_status_completed_id = get_task_status_id_by_name('Completed');
									
									if($l['task_status_id'] == $task_status_completed_id){
										$ts = "c_on";
									}else{
										$ts = "";
									}
								
		                    	?>
							  <tr id="task_id_<?php echo $l['task_id'];?>">
								<th scope="row" width="16px;">
									<div class="checkboxes">
										<label class="label_check <?php echo $ts;?>" id="status_<?php echo $l['task_id'];?>" for="task_status_<?php echo $l['task_id'];?>">
										<input onclick="changestatus('<?php echo $l['task_status_id'];?>','<?php echo $l['task_id'];?>');" name="task_status" id="task_status_<?php echo $l['task_id'];?>" value="" type="checkbox" <?php if($l['task_status_id'] == $task_status_completed_id){ ?> checked="" <?php }else{ ?>  <?php }  ?> > &nbsp; </label>
										
									</div>
								</th>
								<td>
									<div class="txt-heading1"> <?php echo (strlen($l['task_title']) > 35)?substr(ucwords($l['task_title']),0,32).'...':ucwords($l['task_title']);?> </div>
									<div class="txt-heading2"> Created by : <?php echo ucwords($name->first_name)." ".ucwords($name->last_name);?></div>
									<div class="txt-heading2"><?php echo date($site_setting_date,strtotime($due_dt));?> </div>
								 </td>
								<td class="text-right"><i class="stripicon <?php echo $tsk_st;?>"> </i> </td>
							  </tr>
							  
							  <?php } }else{ ?>
							  	<tr>
							  		<td colspan="4"> No task Availabe</td>
							  	</tr>
								
							  <?php } ?>