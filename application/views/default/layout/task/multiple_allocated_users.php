
<script type="text/javascript">
$(document).ready(function(){ 
	App.init();
	
});
</script>

<?php if(isset($users)){
	
		foreach($users as $user){
			if($user->user_id != $task_owner_id){
				if(in_array($user->user_id, $is_multiallocation_task)){
					?>
					<li><input class="checkbox1" type="checkbox" name="task_allocated_user_id[]" checked="checked" value="<?php echo $user->user_id;?>"><?php echo $user->first_name." ".$user->last_name;?> (<?php echo $status_name[$user->user_id];?>)</li>
					<?php
				}
			}
		}
		foreach($users as $user){
			if($user->user_id != $task_owner_id){
				if(!in_array($user->user_id, $is_multiallocation_task)){
					?>
					<li><input class="checkbox1" type="checkbox" name="task_allocated_user_id[]" value="<?php echo $user->user_id;?>"><?php echo $user->first_name." ".$user->last_name;?></li>
					<?php
				}
			}
		}
} ?>
		