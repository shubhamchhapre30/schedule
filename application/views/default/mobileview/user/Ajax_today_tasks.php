<?php
							 if(!empty($todolist)){
								foreach($todolist as $t){
                    			
								$task_status_completed_id = get_task_status_id_by_name('Completed');
								
								if($t['task_status_id'] == $task_status_completed_id){
										$ts = "c_on";
									}else{
										$ts = "";
									}
									
									if($t['task_priority']=='None'){ $tsk_st = "";}
									if($t['task_priority']=='Low'){ $tsk_st = "greennoticon";}
									if($t['task_priority']=='Medium'){ $tsk_st = "yellownoticon";}
									if($t['task_priority']=='High'){ $tsk_st = "rednoticon";}
							
		                    	 	if($t['task_due_date']!= '0000-00-00' ){
										$due_dt = date($site_setting_date,strtotime($t['task_due_date']));
									} else {
										$due_dt = "N/A";
									}?>
							  <tr id="task_id_<?php echo $t['task_id'];?>">
								<th scope="row" width="16px;">
									<div class="checkboxes">
										<label class="label_check <?php echo $ts;?>" id="status_<?php echo $t['task_id'];?>" for="task_status_<?php echo $t['task_id'];?>">
										<input onclick="changestatus('<?php echo $t['task_status_id'];?>','<?php echo $t['task_id'];?>');" name="task_status" id="task_status_<?php echo $t['task_id'];?>" value="" type="checkbox" <?php if($t['task_status_id'] == $task_status_completed_id){ ?> checked="" <?php }else{ ?>  <?php }  ?> > &nbsp; </label>
										
									</div>
								</th>
								<td>
									<div title="<?php echo $t['task_description'];?>" ><a href="<?php echo ($t['task_project_id']=='0')?site_url('task/view_task/'.base64_encode($t['task_id'])):site_url('task/view_task/'.base64_encode($t['task_id']));?>" > <?php echo (strlen($t['task_title']) > 40)?substr(ucwords($t['task_title']),0, 37).'...':ucwords($t['task_title']);?> </a></div>
									<!--<div class="txt-heading2"> 11:11</div>-->
									<div class="txt-heading2"> Due date : <?php echo $due_dt;?> </div>
								 </td>
								  <?php 
                        foreach($task_status as $ts){
                        	//pr($task_status);die;
                        if($ts->task_status_id == $t['task_status_id']){
                        	
							$name = get_user_name($t['task_allocated_user_id']);
									
									 
                        		
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
								<td class="text-left"> <div class="status-bx <?php echo $tsk_clr;?>"> <?php echo $ts->task_status_name;?> </div> <i class="stripicon <?php echo $tsk_st;?>"> </i> </td>
								<?php } } ?>
							  </tr>
							  
							  <?php } }else{ ?>
							  	<tr>
							  		<td colspan="4"> No task Availabe</td>
							  	</tr>
							  	
							  	<?php } ?> 