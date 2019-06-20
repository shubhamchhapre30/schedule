<?php if(isset($divisions) && $divisions !=""){ ?>
  	<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();">
	<?php $count = count((array)$divisions); 
	if($count == "1" && $divisions[0]->devision_title == "General"){ ?>
		<option value="<?php echo $divisions[0]->division_id;?>" selected="selected"> <?php echo $divisions[0]->devision_title; ?> </option>
		
	<?php } else {
		foreach($divisions as $div){
		 ?>
		<option value="<?php echo $div->division_id;?>" <?php if(isset($task['general']['task_division_id']) && $task['general']['task_division_id']!= ''){ if(in_array($div->division_id, $task['general']['task_division_id'])) { echo 'selected="selected"'; } }?> > <?php echo $div->devision_title; ?> </option>
	<?php } 
		} ?>
	</select>
<?php } else { ?>
	<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" disabled="disabled" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();">
	</select>
 <?php } ?>
<span class="input-load" id="task_division_id_loading"></span>
