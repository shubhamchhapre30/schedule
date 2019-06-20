<select onchange="checkstatus(this.value);"  class="m-wrap project-select wid50" id="project_status" name="project_status" tabindex="1" >
	<option value="Open" <?php if($project_status == 'Open'){ echo 'selected="selected"'; } ?>>Open</option>
	<option value="Complete" <?php if($project_status == 'Complete'){ echo 'selected="selected"'; } ?>>Completed</option>
	<option value="On_hold" <?php if($project_status == 'On_hold'){ echo 'selected="selected"'; } ?>>On Hold</option>
	<option value="Cancelled" <?php if($project_status == 'cancelled'){ echo 'selected="selected"'; } ?>>Cancelled</option>
</select>