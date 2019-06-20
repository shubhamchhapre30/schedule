<script type="text/javascript">
	$(document).ready(function(){
		App.init();
	});
</script>

<?php 
																	
				$task_detail = getTaskDetail($sub->section_id,$sub->main_section,$project_id);
				$task_status_completed_id = $this->config->item('completed_id');
				$today = date($site_setting_date);
				if($task_detail!='0'){
				foreach ($task_detail as $td) {
					
					
				
				 ?>
				<div>
					<ul class="clearfix">
						<li><i class="stripicon gray-toggle"></i></li>
						<li><input onclick="changeTaskStatus('<?php echo $td->task_id;?>','<?php echo $td->subsection_id;?>','<?php echo $td->section_id;?>','<?php echo $td->task_time_spent;?>');" name="task_status" id="pr_task_status_<?php echo $td->task_id;?>" value="" type="checkbox" <?php if($td->task_status_id == $task_status_completed_id){ ?> checked="checked" <?php } ?> ></li>
						<li><p class="roundbox-gray"><?php echo ucwords($td->first_name)." ".ucwords($td->last_name);?><!--Vincent Motte-->	</p></li>
						<li><a data-toggle="modal" href="#full-width" id="edit_task_<?php echo $td->task_id;?>" data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a></li>
						
						<?php 											
							if($td->task_scheduled_date != '0000-00-00' ){
								$due_dt = date($site_setting_date,strtotime($td->task_scheduled_date));
							} elseif($td->task_due_date != '0000-00-00' ){
								$due_dt = date($site_setting_date,strtotime($td->task_due_date));
							} else {
								$due_dt = '0000-00-00';
							}
						?>	
						<?php									
							if($due_dt!="0000-00-00"){
							if($td->task_status_id != $task_status_completed_id && ($due_dt < $today)){
							?>
							<li><p class="red">(due date : <?php echo $due_dt;?>)</p></li>
							
							<?php }else{ ?>
							<li><p>(due date : <?php echo $due_dt;?>)</p></li>	
						<?php } }?>
					</ul>
				
				
				</div>
				
				
				
<script type="text/javascript">
	$(document).ready(function(){
		
		$("#edit_task_<?php echo $td->task_id;?>").click(function(){
			
			$(".tab_chk").attr('data-toggle','tab');
			$("#none").removeAttr('checked','checked');
			$("#none").parent('span').removeAttr('class','checked');
			$("#redirect_page").val('from_project');
			$("#task_id").val('<?php echo $td->task_id;?>');
			$("#task_title").val("<?php echo $td->task_title;?>");
			$("#task_section_id").val('<?php echo $td->section_id;?>');
			$("#task_section_name").val('<?php echo $s->section_name;?>');
			$("#task_subsection_id").val('<?php echo $td->subsection_id;?>');
			$("#task_description").val('<?php echo $td->task_description;?>');
			$("#task_category_id").val('<?php echo $td->task_category_id;?>');
			$("#task_sub_category_id").val('<?php echo $td->task_sub_category_id;?>');
			$("#task_due_date").val('<?php echo ($td->task_due_date!='0000-00-00')?date($site_setting_date,strtotime($td->task_due_date)):date('');?>');
			$("#task_color_id").val('<?php echo get_user_task_color($td->task_id,get_authenticateUserID());?>');
			//$("#task_color_code").val('<?php //echo $td->task_color_code;?>');
			$("#task_staff_level_id").val('<?php echo $td->task_staff_level_id;?>');
			$("#task_owner_id").val('<?php echo usernameById($td->task_owner_id);?>');
			$("#task_allocated_user_id").val('<?php echo $td->task_allocated_user_id;?>');
			$("#task_allocated_user_id").trigger("liszt:updated"); 
			<?php if($td->is_personal == '1'){ ?>
				$("#is_personal").attr('checked', 'checked');
				$("#is_personal").parent('span').attr('class','checked');
			<?php } ?>
			<?php if($td->locked_due_date == '1'){ ?>
				$("#locked_due_date").attr('checked', 'checked');
				$("#locked_due_date").parent('span').attr('class','checked');
			<?php } ?>
			$("#task_priority").val('<?php echo $td->task_priority;?>');
			
			$('#hdn_task_priority').val("<?php echo $td->task_priority;?>");
			<?php $total_task_time_spent_minute = $td->task_time_spent;
			$spent_hours = intval($total_task_time_spent_minute/60);
			$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
			$td->task_time_spent_hour = $spent_hours;
			$td->task_time_spent_min = $spent_minutes;
			
			
			$total_task_time_estimate_minute = $td->task_time_estimate;
			$estimate_hours = intval($total_task_time_estimate_minute/60);
			$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
			$td->task_time_estimate_hour = $estimate_hours;
			$td->task_time_estimate_min = $estimate_minutes; ?>
			$("#task_time_spent_hour").val('<?php echo $td->task_time_spent_hour;?>');
			$("#task_time_spent_min").val('<?php echo $td->task_time_spent_min;?>');
			$("#task_time_estimate_hour").val('<?php echo $td->task_time_estimate_hour;?>');
			$("#task_time_estimate_min").val('<?php echo $td->task_time_estimate_min;?>');
			$("#task_status_id").val('<?php echo $td->task_status_id;?>');
			$("#old_task_status_id").val('<?php echo $td->task_status_id;?>');
			$("#master_task_id").val('<?php echo $td->master_task_id;?>');	
			var hidValue = '<?php echo $td->task_division_id;?>';
			var selectedOptions = hidValue.split(",");
		    for(var i in selectedOptions) {
		        var optionVal = selectedOptions[i];
		        $("#task_division_id").find("option[value="+optionVal+"]").prop("selected", "selected");
		    }
		    $("#task_division_id").multiselect('refresh');
		    
		    var hidValue1 = '<?php echo $td->task_department_id;?>';
			var selectedOptions1 = hidValue1.split(",");
		    for(var i in selectedOptions1) {
		        var optionVal1 = selectedOptions1[i];
		        $("#task_department_id").find("option[value="+optionVal1+"]").prop("selected", "selected");
		    }
		    $("#task_department_id").multiselect('refresh');
		    
		    var hidValue2 = '<?php echo $td->task_skill_id;?>';
			var selectedOptions2 = hidValue2.split(",");
		    for(var i in selectedOptions2) {
		        var optionVal2 = selectedOptions2[i];
		        $("#task_skill_id").find("option[value="+optionVal2+"]").prop("selected", "selected");
		    }
		    $("#task_skill_id").multiselect('refresh');
		    
		    <?php if(($td->task_owner_id != $td->task_allocated_user_id) && ($td->task_allocated_user_id == get_authenticateUserID())){
		    		if($td->locked_due_date == '1'){
		    			?>
		    			$("#locked_due_date").attr('disabled',true);
		    			$("#task_due_date").attr('disabled',true);
		    			$("#hdn_task_due_date").val('<?php echo ($td->task_due_date!='0000-00-00')?date($site_setting_date,strtotime($td->task_due_date)):date('0000-00-00');?>');
		    			<?php
		    		}
		    } ?>
		    <?php 
		    	$managers = get_managers_of_users($td->task_allocated_user_id);
				if($managers){
					$manager_ids = array();
					foreach($managers as $man){
						$manager_ids[] = $man->manager_id; 
					}
					if(in_array(get_authenticateUserID(),$manager_ids)){
						?>
						$("#task_title").attr("readonly",true);
						$("#task_description").attr("readonly",true);
						<?php
					}
				}
		    ?>	
				
		});
		
		$("#edit_series_<?php echo $td->master_task_id;?>").click(function(){
			$(".tab_chk").attr('data-toggle','tab');
			$("#none").removeAttr('checked','checked');
			$("#redirect_page").val('from_project');
			$("#none").parent('span').removeAttr('class','checked');
			$("#task_id").val('<?php echo $td->task_id;?>');
			$("#task_title").val("<?php echo $td->task_title;?>");
			$("#task_description").val('<?php echo $td->task_description;?>');
			$("#task_category_id").val('<?php echo $td->task_category_id;?>');
			$("#task_sub_category_id").val('<?php echo $td->task_sub_category_id;?>');
			
			$("#task_due_date").val('<?php echo ($td->task_due_date!='0000-00-00')?date($site_setting_date,strtotime($td->task_due_date)):date($site_setting_date);?>');
			
			//$("#task_color_code").val('<?php //echo $td->task_color_code;?>');
			$("#task_color_id").val('<?php echo get_user_task_color($td->task_id,get_authenticateUserID());?>');
			$("#task_staff_level_id").val('<?php echo $td->task_staff_level_id;?>');
			$("#task_owner_id").val('<?php echo usernameById($td->task_owner_id);?>');
			$("#task_allocated_user_id").val('<?php echo $td->task_allocated_user_id;?>');
			$("#task_allocated_user_id").trigger("liszt:updated"); 
			<?php if($td->is_personal == '1'){ ?>
				$("#is_personal").attr('checked', 'checked');
				$("#is_personal").parent('span').attr('class','checked');
			<?php } ?>
			<?php if($td->locked_due_date == '1'){ ?>
				$("#locked_due_date").attr('checked', 'checked');
				$("#locked_due_date").parent('span').attr('class','checked');
			<?php } ?>
			$("#task_priority").val('<?php echo $td->task_priority;?>');
			$('#hdn_task_priority').val("<?php echo $td->task_priority;?>");
			<?php $total_task_time_spent_minute = $td->task_time_spent;
			$spent_hours = intval($total_task_time_spent_minute/60);
			$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
			$td->task_time_spent_hour = $spent_hours;
			$td->task_time_spent_min = $spent_minutes;
			
			
			$total_task_time_estimate_minute = $td->task_time_estimate;
			$estimate_hours = intval($total_task_time_estimate_minute/60);
			$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
			$td->task_time_estimate_hour = $estimate_hours;
			$td->task_time_estimate_min = $estimate_minutes; ?>
			$("#task_time_spent_hour").val('<?php echo $td->task_time_spent_hour;?>');
			$("#task_time_spent_min").val('<?php echo $td->task_time_spent_min;?>');
			$("#task_time_estimate_hour").val('<?php echo $td->task_time_estimate_hour;?>');
			$("#task_time_estimate_min").val('<?php echo $td->task_time_estimate_min;?>');
			$("#task_status_id").val('<?php echo $td->task_status_id;?>');
			$("#old_task_status_id").val('<?php echo $td->task_status_id;?>');
			var hidValue = '<?php echo $td->task_division_id;?>';
			var selectedOptions = hidValue.split(",");
		    for(var i in selectedOptions) {
		        var optionVal = selectedOptions[i];
		        $("#task_division_id").find("option[value="+optionVal+"]").prop("selected", "selected");
		    }
		    $("#task_division_id").multiselect('refresh');
		    
		    var hidValue1 = '<?php echo $td->task_department_id;?>';
			var selectedOptions1 = hidValue1.split(",");
		    for(var i in selectedOptions1) {
		        var optionVal1 = selectedOptions1[i];
		        $("#task_department_id").find("option[value="+optionVal1+"]").prop("selected", "selected");
		    }
		    $("#task_department_id").multiselect('refresh');
		    
		    var hidValue2 = '<?php echo $td->task_skill_id;?>';
			var selectedOptions2 = hidValue2.split(",");
		    for(var i in selectedOptions2) {
		        var optionVal2 = selectedOptions2[i];
		        $("#task_skill_id").find("option[value="+optionVal2+"]").prop("selected", "selected");
		    }
		    $("#task_skill_id").multiselect('refresh');
		    
		     <?php if(($td->task_owner_id != $td->task_allocated_user_id) && ($td->task_allocated_user_id == get_authenticateUserID())){
		    		if($td->locked_due_date == '1'){
		    			?>
		    			$("#locked_due_date").attr('disabled',true);
		    			$("#task_due_date").attr('disabled',true);
		    			$("#hdn_task_due_date").val('<?php echo ($td->task_due_date!='0000-00-00')?date($site_setting_date,strtotime($td->task_due_date)):date('0000-00-00');?>');
		    			<?php
		    		}
		    } ?>
		    <?php 
		    	$managers = get_managers_of_users($td->task_allocated_user_id);
				if($managers){
					$manager_ids = array();
					foreach($managers as $man){
						$manager_ids[] = $man->manager_id; 
					}
					if(in_array(get_authenticateUserID(),$manager_ids)){
						?>
						$("#task_title").attr("readonly",true);
						$("#task_description").attr("readonly",true);
						<?php
					}
				}
		    ?>
					
		});
		});
		

	
	</script>																
				
				<?php } }?>