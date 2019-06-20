<?php if(isset($departments) && $departments!=''){
	foreach($departments as $dept){
		?>
		<option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_title;?></option>
		<?php
	}
}?>