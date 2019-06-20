<script type="text/javascript">
$(document).ready(function(){
	App.init();
});

</script>

<?php 
	$ready = get_task_status_id_by_name('Ready');
	$completed = $this->config->item('completed_id');
	/*
	if($kanban['task_status_id'] == $not_ready){
																					?>
																					<li class="chkbox"> <label class="checkbox">
																						<input type="checkbox" value="" /> 
																					 </label></li> 
																					<?php
																				}*/
	 
	if($kanban['task_status_id'] == $completed){
		?>
		<label class="checkbox">
			<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($kanban)) ?>,'<?php echo $ready;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" checked="checked" /> 
		 </label> 
		<?php
	} else {
		?>
		<label class="checkbox">
			<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($kanban)) ?>,'<?php echo $completed;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" /> 
		 </label>
		<?php
	}
?>