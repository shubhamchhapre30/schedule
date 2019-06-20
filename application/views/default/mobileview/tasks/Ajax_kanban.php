<?php if($task_kanban!=''){
			foreach ($task_kanban as $t) {
				
				$task_status_completed_id = get_task_status_id_by_name('Completed');
								
				if($t->task_status_id == $task_status_completed_id){
						$ts1 = "c_on";
				}else{
					$ts1 = "";
				}
				
				if($t->task_priority=='None'){ $tsk_st = "";}
				if($t->task_priority=='Low'){ $tsk_st = "greennoticon";}
				if($t->task_priority=='Medium'){ $tsk_st = "yellownoticon";}
				if($t->task_priority=='High'){ $tsk_st = "rednoticon";} 
			?>	
		  <tr class="event_block">
			 <td scope="row" width="16px;">
				<div class="checkboxes">
					<label class="label_check <?php echo $ts1;?>" id="status_<?php echo $t->task_id;?>" for="task_status_<?php echo $t->task_id;?>">
					<input onclick="changestatus('<?php echo $t->task_status_id;?>','<?php echo $t->task_id;?>');" name="task_status" id="task_status_<?php echo $t->task_id;?>" value="" type="checkbox" <?php if($t->task_status_id == $task_status_completed_id){ ?> checked="" <?php }else{ ?>  <?php }  ?> > &nbsp; </label>
					
				</div>
			</td>
			<td>
				<div><a href="<?php echo site_url('task/view_task/'.base64_encode($t->task_id));?>"> <?php echo (strlen($t->task_title) > 40)?substr(ucwords($t->task_title),0, 37).'...':ucwords($t->task_title);?></a> </div>
				<?php 
					if($t->task_scheduled_date != '0000-00-00'  ){
						$due_dt = date($site_setting_date,strtotime($t->task_scheduled_date));
					}  else {
						$due_dt = 'N/A';
					}									
				?>
				 
				<div class="txt-heading2">Due date : <?php echo $due_dt;?> </div>
			 </td>
			
			
		        <td class="text-left"><i class="stripicon <?php echo $tsk_st;?>"> </i></td>
		       
		  </tr>
		  <?php } } ?>