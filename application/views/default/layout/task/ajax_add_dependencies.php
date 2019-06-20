<?php 
	//print_r($task['dependencies']);die;
	if(isset($task['dependencies']) && $task['dependencies'] != ''){
		foreach($task['dependencies'] as $dependent){
			$status_name = getStatusName($dependent['task_status_id']);
			?>
			
			<tr>
				<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php echo $dependent['task_id']; ?></td>
				<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php echo $dependent['task_title']; ?></td>
				<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php echo usernameById($dependent['task_allocated_user_id']); ?></td>
				<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php if($dependent['task_due_date'] != '0000-00-00'){ echo date($site_setting_date,strtotime(str_replace(array("/"," ",","), "-", $dependent['task_due_date']))); } else { echo ''; }?></td>
				<td> <span class="label label-<?php echo str_replace(' ', '', $status_name);?>"><?php echo $status_name;?></span> </td>
				<td>
                                        <a href='javascript://' onclick="remove_task_dependency('<?php echo $dependent['task_id']; ?>');" id="remove_task_dependency_<?php echo $dependent['task_id']; ?>" class='tooltips' data-placement='top' data-original-title='Click to Un-link the task' ><i class='icon-unlink'></i></a>
                                        <?php if($dependent['task_owner_id'] == get_authenticateUserID()){ ?> 
					<a href="javascript://" class='tooltips' data-placement='top' data-original-title='Click to Delete the dependency task' onclick="delete_dependent_task('<?php echo $dependent['task_id']; ?>');" id="delete_dependent_task_<?php echo $dependent['task_id']; ?>"> <i class="icon-trash stngicn"></i> </a>
					<?php } ?>
				</td>
			</tr>
			<?php
		}
	} else {?>
		<tr><td colspan="6">No record available.</td></tr>
  <?php } ?>
                <script>
                $(document).ready(function(){
                    $('.tooltips').tooltip();
                })
                </script>