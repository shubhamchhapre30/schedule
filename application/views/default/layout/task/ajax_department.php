<?php if(isset($departments) && $departments !=""){ ?>
  	<select class="col-md-11 m-wrap no-margin task_multiselect task-input" multiple name="task_department_id[]" id="task_department_id" tabindex="1">
	<?php $count = count((array)$departments); 
	if($count == "1" && $departments[0]->department_title == "General"){ ?>
		<option value="<?php echo $departments[0]->department_id;?>" selected="selected"> <?php echo $departments[0]->department_title; ?> </option>
		
	<?php } else {
		foreach($departments as $dept){
		 ?>
		<option value="<?php echo $dept->department_id;?>" <?php  if(in_array($dept->department_id,$dept_ids)) { echo 'selected="selected"'; } ?> > <?php echo $dept->department_title; ?> </option>
	<?php } 
		} ?>
	</select>
<?php } else { ?>
	<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" disabled="disabled" multiple name="task_department_id[]" id="task_department_id" tabindex="1">
	</select>
 <?php } ?>
