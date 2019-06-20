<?php $theme_url = base_url().getThemeName();
$default_format = $this->config->item('company_default_format');
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
$company_flags = $this->config->item('compay_flags');
$allow_past_task = "1";
if($company_flags){
	$allow_past_task = $company_flags['allow_past_task'];
}
if($allow_past_task == "1"){
	$start_date_picker = "-Infinity";
} else {
	$start_date_picker = "this.date";
}
$cat = get_company_sub_category($this->session->userdata('company_id'));
if($cat){
	$is_sub_category_exist = "1";
} else {
	$is_sub_category_exist = "0";
}
//$customers=  getCustomerList();
 ?>
<style>
    .wysihtml5-sandbox{
        display: block !important;
        height:70px !important;
        resize: both !important;
    }
</style>
<!--<script src="<?php echo $theme_url;?>/assets/scripts/components-editors.min.js" type="text/javascript"></script>-->
<script type="text/javascript">
//jQuery(document).ready(function(){ComponentsEditors.init()});
	$(document).ready(function(){
		
		$('.input-append.date').datepicker({
	  		startDate: <?php echo $start_date_picker;?>,
                        format: '<?php echo $date_arr_java[$default_format]; ?>',
                        autoclose: true,
	   	});
	   	
		
		if($("#redirect_page").val() == "from_kanban" || $("#redirect_page").val() == 'from_teamdashboard' || $("#redirect_page").val() == 'from_dashboard' || $("#redirect_page").val() == 'from_project'){
			$("#task_due_date_div").datepicker({
				startDate : <?php echo $start_date_picker;?>,
				format : '<?php echo $date_arr_java[$default_format]; ?>',
                                autoclose: true,
	   		}).on('changeDate', function(date){
	   			function pad(s) { return (s < 10) ? '0' + s : s; }
				var due_date = date.date;
				
				var format = '<?php echo $default_format;?>';
				if(format == 'd M,Y'){
					var m_names = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
					due_date = pad(due_date.getDate())+" "+m_names[due_date.getMonth()]+", "+due_date.getFullYear();
				} else if(format == 'd/m/Y'){
					due_date = pad(due_date.getDate())+"/"+pad(due_date.getMonth()+1)+"/"+due_date.getFullYear();
				} else if(format == 'Y/m/d'){
					due_date = due_date.getFullYear()+"/"+pad(due_date.getMonth()+1)+"/"+pad(due_date.getDate());
				} else if(format == 'd-m-Y'){
					due_date = pad(due_date.getDate())+"-"+pad(due_date.getMonth()+1)+"-"+due_date.getFullYear();
				} else {
					due_date = due_date.getFullYear()+"-"+pad(due_date.getMonth()+1)+"-"+pad(due_date.getDate());
				} 
				$("#task_scheduled_date").val(due_date);
				$("#start_on_date").val(due_date);
				$(this).datepicker('hide');
				this.focus();
	   		});
		} else {
			$("#task_due_date_div").datepicker({
		  		startDate: <?php echo $start_date_picker; ?>,
                                format: '<?php echo $date_arr_java[$default_format]; ?>',
                                autoclose: true,
		   	});
		}
		

	});
	
</script>
<div class="portlet">
									 <div class="portlet-body form">
									 	
									 	<div class='alert alert-error' id="error_msg" style="display:none;"><a class='closemsg' data-dismiss='alert'></a><span id="error_msg_val"></span></div>
										<div class='alert alert-success' id="update_msg" style="display:none;" ><a class='closemsg' data-dismiss='alert'></a><span>Task successfully updated.</span></div>
									 	<div class='alert alert-success' id="insert_msg" style="display:none;"><a class='closemsg' data-dismiss='alert'></a><span>Task successfully created.</span></div>
									 	
										<div class="horizontal-form">
											<form name="frm_task_general" id="frm_task_general" action="" >
											
												<div class="popuphight popup_height">
											 		<div class="row">
														<div class="col-md-12 ">
															<div class="form-group">
																<label class="control-label" for="firstName">Task Name<span class="required">*</span></label>
																<div class="controls relative-position">
																	<input type="text"  name="task_title" id="task_title" value="" class="m-wrap col-md-12 task-input" placeholder="" tabindex="1">
                                                                                                                                        <!--<span class="input-load" id="task_title_loading"></span>-->
																</div>
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-12 ">
															<div class="form-group paddding-5">
																<label class="control-label" for="firstName">Task Description</label>
																<div class="controls relative-position editor">
																	<textarea rows="3" name="task_description" id="task_description" class="m-wrap col-md-12 task-input ui-resizable desc_editor" tabindex="2"></textarea>
																	<!--<span class="input-load desc-load" id="task_description_loading"></span>-->
																  </div>
															</div>
															<!--<span class="chr">Char left :- <i id="ch">10000</i></span>-->
														</div>
													</div>	
													
													<div class="row">
														<div class="col-md-6">
															<div class="form-group paddding-5">
																<label class="control-label">Task Category</label> 
																<div class="controls relative-position">
																	<?php if(isset($main_category) && $main_category!=''){ ?>
																		<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_category_id" id="task_category_id" tabindex="3" onchange="setSubCategory();">
																			<option value="0">Please Select</option>
																		<?php foreach($main_category as $cat){
																			?>
																			<option value="<?php echo $cat->category_id ?>"  ><?php echo $cat->category_name; ?></option>
																			<?php
																		} ?>
																		</select>
																	<?php } else {
																		if($this->session->userdata("is_administrator")){ ?>
																			<div class="input-icon right">
																				<i onclick="window.open('<?php echo site_url("settings/index#company_setting_tab_4");?>','_blank');"  class="stripicon help"></i>
																				<input class="m-wrap col-md-11" disabled="disabled" name="task_category_id" value="Add Category" type="text" placeholder="Add new category" />
																			</div>
																	<?php } else { ?>
																		<select class="col-md-11 m-wrap no-margin task-input radius-b" disabled="disabled" name="task_category_id" id="task_category_id" tabindex="3" onchange="setSubCategory();">
																			<option value="0" disabled="disabled">Please select</option>
																		</select>
																	<?php } ?>
																	<input type="hidden" name="task_category_id" id="task_category_id" value="0" />
																	<?php } ?>
																	<!--<span class="input-load" id="task_category_id_loading"></span>-->
																</div>
															</div>
														</div>	
													
														<div class="col-md-6">
															<div class="form-group paddding-5">
																<label class="control-label">Priority</label> 
																<input type="hidden" name="task_priority" id="hdn_task_priority" value="" />
																<div class="controls relative-position">
																	<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_priority" id="task_priority" tabindex="4">
																		<option value="None" ><i class="stripicon iconnone"> </i> None</option>
																		<option value="Low" ><i class="stripicon iconlow"> </i> Low</option>
																		<option value="Medium" ><i class="stripicon iconmedium"> </i> Medium</option>
																		<option value="High" ><i class="stripicon iconhigh"> </i> High</option>
																	</select>
																	<!--<span class="input-load" id="task_priority_loading"></span>-->
																</div>
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-6">
															<div class="form-group paddding-5">
																<label class="control-label">Sub Category</label> 
																<div class="controls relative-position" id="updated_subCategory">
																	<?php if(isset($sub_category) && $sub_category!=''){ ?>
																		<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_sub_category_id" id="task_sub_category_id" tabindex="5" >
																			<!-- <option value="0">Please Select</option> -->
																		<?php foreach($sub_category as $sub_cat){
																			?>
																			<option value="<?php echo $sub_cat->category_id ?>" ><?php echo $sub_cat->category_name; ?></option>
																			<?php
																		} ?>
																		</select>
																	<?php } else {
																		if($this->session->userdata("is_administrator") && $is_sub_category_exist=="0"){ ?>
																			<div class="input-icon right">
																				<i onclick="window.open('<?php echo site_url("settings/index#company_setting_tab_4");?>','_blank');"  class="stripicon help"></i>
																				<input class="m-wrap col-md-11" disabled="disabled" name="task_sub_category_id" value="Add Sub Category" type="text" placeholder="Add sub category" />
																			</div>
																	<?php } else { ?>
																		<select class="col-md-11 m-wrap no-margin radius-b" disabled="disabled" name="task_sub_category_id" id="task_sub_category_id" tabindex="5">
																			<option value="0" >Please select</option>
																		</select>
																	<?php } ?>
																	<input type="hidden" name="task_sub_category_id" id="task_sub_category_id" value="0" />
																<?php } ?>
																<!--<span class="input-load" id="task_sub_category_id_loading"></span>-->
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group paddding-5">
																<label class="control-label">Status</label> 
																<div class="controls relative-position">
																	<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_status_id" id="task_status_id" tabindex="6">
																		<?php 
																		if(isset($task_status) && $task_status !=''){
																			foreach($task_status as $ts){
																				?>
																				<option value="<?php echo $ts->task_status_id;?>" ><?php echo $ts->task_status_name;?></option>
																				<?php
																			}
																		}
																		?>
																	</select>
																	<!--<span class="input-load" id="task_status_id_loading"></span>-->
																</div>
															</div>
														</div>
														
													</div>
													<div class="row" >
														<div class="col-md-6" >
															<div class="form-group paddding-5">
																<label class="control-label">Colour</label> 
																<div class="controls relative-position">
																	<?php if(isset($color_codes) && $color_codes != ''){ ?>
																		<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_color_id" id="task_color_id" tabindex="7">
																			<option value="0">Please select</option>
																			<?php foreach($color_codes as $color){ ?>
																				<option value="<?php echo $color->user_color_id;?>"  > <?php echo $color->name; ?> </option>
																			<?php } ?>
                                                                                                                                                                <option value="0">None</option>
																		</select>
																	<?php } else { ?>
																		<?php if(isset($is_color_exist) && $is_color_exist =="1"){ ?>
																			<div class="input-icon right">
																				<i onclick="window.open('<?php echo site_url("user/colors");?>','_blank');"  class="stripicon help"></i>
																				<input class="m-wrap col-md-11" disabled="disabled" name="task_color_id" value="Change color settings" type="text" placeholder="Change color settings" />
																			</div>
																		<?php } else { ?>
																			<select class="col-md-11 m-wrap no-margin radius-b" disabled="disabled" name="task_color_id" tabindex="7">
																				<option value="0" disabled="disabled">Please select</option>
																			</select>
																		<?php } ?>
																		<input type="hidden" name="task_color_id" id="task_color_id" value="0" />
																	<?php } ?>
																	<!--<span class="input-load" id="task_color_id_loading"></span>-->
																</div>
															</div>
														</div>
														<!--/span-->
														<!--/span-->
														
														 <div class="col-md-6 ">
														 	<div class="form-group no-margin paddding-5">
																<label class="control-label">Due Date</label> 
																<div class="controls relative-position">
																	<div id="task_due_date_div" class="input-append date date-picker" data-date="<?php echo date($default_format);?>" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
																		<input class="m-wrap m-ctrl-medium task-input col-md-7 "  placeholder="Due date" name="task_due_date" id="task_due_date" size="16" type="text" value="" tabindex="8"  /><span class="add-on"><i class="icon-calendar taskppicn"></i></span>
																	</div>
																	
																	<!--<input type="hidden" name="task_due_date" id="hdn_task_due_date" value="" />-->
																	
																	<!--<span class="input-load due-date-load" id="task_due_date_loading"></span>-->
																</div>
															</div>
														</div>
														
														<!--/span-->
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group paddding-5">
																 <label class="control-label">Task Owner</label> 
																<div class="controls">
																	<div class="input-icon right">
<!--																		<i class="stripicon iconowaner"></i>-->
																		<input class="m-wrap col-md-11" disabled="disabled" name="task_owner_id_val" id="task_owner_id_val" value="<?php echo $this->session->userdata('username');  ?>" type="text" placeholder="Task Owner" tabindex="9" />
																		<input name="task_owner_id" id="task_owner_id" value="<?php echo get_authenticateUserID(); ?>" type="hidden" placeholder="Task Owner" />    
																	</div>
																</div>
															</div>
														</div>
														
														<div class="col-md-3">
															<div class="form-group paddding-5">
																<label class="control-label">&nbsp;</label>
																<div class="controls relative-position">
                                                                                                                                    <input class="task-chk-input pull-left newcheckbox_task" type="checkbox" name="locked_due_date" id="locked_due_date" value="1" tabindex="10" /><p> Locked Due date</p>
																	<input type="hidden" name="hdn_locked_due_date" id="hdn_locked_due_date" value="" />
																	<!--<span class="input-load lock-load" id="locked_due_date_loading"></span>-->
																</div>
															</div>
														</div>
														<div class="col-md-3">	
															<div class="form-group paddding-5">
																<label class="control-label">&nbsp;</label> 
																<div class="controls relative-position">
                                                                                                                                    <input class="task-chk-input pull-left newcheckbox_task" type="checkbox" name="is_personal" id="is_personal" onclick="chk_personal();" value="1" tabindex="11" /><p> Private</p>
																	<input type="hidden" name="hdn_is_personal" id="hdn_is_personal" value="" /> 
																	<!--<span class="input-load chk-load" id="is_personal_loading"></span>-->
																</div>
															</div>
														</div> 
														 
													</div>
                                                                                               
													<input name="task_time_estimate_hour" id="task_time_estimate_hour" value="" type="hidden" />
													<input name="task_time_estimate_min" id="task_time_estimate_min" value="" type="hidden" />
													<input name="old_task_time_estimate_hour" id="old_task_time_estimate_hour" value="" type="hidden" />
													<input name="old_task_time_estimate_min" id="old_task_time_estimate_min" value="" type="hidden" />
													
													
													<input name="task_time_spent_hour" id="task_time_spent_hour" value="" type="hidden" />
													<input name="task_time_spent_min" id="task_time_spent_min" value="" type="hidden" />
													<input name="old_task_time_spent_hour" id="old_task_time_spent_hour" value="" type="hidden" />
													<input name="old_task_time_spent_min" id="old_task_time_spent_min" value="" type="hidden" />
												</div>
												
												<input type="hidden" name="tmp_task_due_date" id="tmp_task_due_date" value="" />
												<input type="hidden" name="old_task_due_date" id="old_task_due_date" value="" />
											 	<input type="hidden" name="old_task_status_id" id="old_task_status_id" value="" />
									 			<input type="hidden" name="task_scheduled_date" id="task_scheduled_date" value="" />
									 			<input type="hidden" name="task_orig_scheduled_date" id="task_orig_scheduled_date" value="" />
									 			<input type="hidden" name="task_orig_due_date" id="task_orig_due_date" value="" />
									 			<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo isset($redirect_page)?$redirect_page:''; ?>" />
									 			<input type="hidden" name="kanban_order" id="kanban_order" value="" />
									 			<input type="hidden" name="calender_order" id="calender_order" value="" />
									 			<input type="hidden" name="genral_swimlane_id" id="genral_swimlane_id" value="<?php echo get_default_swimlane(get_authenticateUserID()); ?>" />
									 			<input type="hidden" name="master_task_id" id="master_task_id" value="0" />
									 			
									 			<input type="hidden" name="strtotime_scheduled_date" id="strtotime_scheduled_date" value="" />
									 			<input type="hidden" name="task_id" id="task_id" value="" />
									 			<input type="hidden" name="old_task_id" id="old_task_id" value="" />
									 			<input type="hidden" name="from" id="from" value="" />
									 			
									 			<input type="hidden" name="task_subsection_id" id="task_subsection_id" value="" />
												<input type="hidden" name="task_section_id" id="task_section_id" value="" />
												<input type="hidden" name="general_project_id" id="general_project_id" value="" /> 
												<input type="hidden" name="allocated_customer_id" id="allocated_customer_id" value=""/>	
                                                                                                <input type="hidden" name="hidden_section_id" id="hidden_section_id" value=""/>	
												</form>
											 
											</div>
											
									</div>
								 </div>
