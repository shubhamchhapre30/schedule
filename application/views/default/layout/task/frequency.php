

<?php
$theme_url = base_url().getThemeName();
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
$Weekly_week_day = array();
$default_format = $this->config->item('company_default_format');
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
?>


<script>
	var SIDEURL = '<?php echo site_url(); ?>';
	var DEFAULT_FORMAT = '<?php echo $date_arr_java[$default_format]; ?>';

	$(document).ready(function(){
	
		 //For all datepicker Sj//
	  $('.input-append.date').datepicker({
			startDate: <?php echo $start_date_picker;?>,
	      format: '<?php echo $date_arr_java[$default_format]; ?>',
              autoClose:true
	      
        });
   		
   		
	 //End//
	 	
		$("#one_off").click(function(){
			$("#recurrence_div").css("display","none");
		});
		
		
		
		$.validator.addMethod("greaterThan", 
			function(value, element, params) {
				if($("#hdn_no_end_date").val() == "1"){
					return true;
				}
				var start_date = $('#start_on_date_picker').datepicker('getDate');
				var end_date = $("#datepicker_end_by").datepicker('getDate');
				//alert(start_date+"===="+end_date);
				if (!/Invalid|NaN/.test(end_date)) {
			        return end_date >= start_date;
			    }
				return (Number($('#end_by_date').val()) >= Number($('#start_on_date').val())); 
			},'Must be greater than or equal to start on date.');
		
		
		$("#end_after_recurrence").keyup(function(){
			var val = $(this).val();
			if(val){
				
				$.ajax({
					type : 'post',
					url : '<?php echo site_url('task/set_end_date_from_recurrence'); ?>',
					data : $('#frm_add_recurrence').serialize(),
					success : function(data){
						var data = jQuery.parseJSON(data);
						//alert(data.end_after_recurrence);
						if(data.end_after_recurrence!='0'){
							$("#end_after_recurrence").val(data.end_after_recurrence);
							$("#start_on_date").val(data.start_date);
							if(data.start_date){
								$('#start_on_date_picker').datepicker("update", data.start_date);
							}
							$("#start_on_date_picker").datepicker('refresh');
							if(data.end_date){
							   	$("#end_by_date").val(data.end_date);
							   	$('#datepicker_end_by').datepicker("update", data.end_date);
								$("#datepicker_end_by").datepicker('refresh');
								
								
								$("#no_end_date3").closest("span").removeClass("checked");
								$("#no_end_date1").closest("span").removeClass("checked");
								$("#no_end_date2").closest("span").addClass("checked");
								
								$("input[name='no_end_date']").removeAttr("checked","checked");
								$("input[name='no_end_date']").prop('checked', false);
								$("#no_end_date2").attr("checked","checked");
								$("#no_end_date2").prop('checked', true);
								$("#hdn_no_end_date").val("2");
								
								
						  	}
						  	
						  	var name = "end_after_recurrence";
							var id = $(this).attr('id');
							$("#"+id+"_loading").show();
							if($("#task_id").val() == "" || $("#task_id").val().indexOf("child")>=0){
								var value = $("#frm_task_general").serialize();
							} else {
								var value = $("#frm_add_recurrence").serialize();
							}
							
							$.ajax({
						    	type : 'post',
						    	url : SIDE_URL+"task/saveTask",
						    	data : {name:name, value : value, task_id:$("#task_id").val(),task_scheduled_date:$("#task_scheduled_date").val(),redirect_page:$("#redirect_page").val()},
						    	success:function(data){
						    		$("#task_id").val(data);
						    		$("#allocation_task_id").val(data);
					            	$("#pre_task_id").val(data);
					            	$("#step_task_id").val(data);
					            	$("#files_task_id").val(data);
					            	$("#comment_task_id").val(data);
					            	$("#freq_task_id").val(data);
					            	$("#search_task_id").val(data);
						    		$("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled",false);
						    		$("#"+id+"_loading").hide();
						    		
						    	}
						    });
						} else {
							$("#end_after_recurrence").val('');
							$("#start_on_date").val('');
							$("#end_by_date").val('');
							$("#no_end_date2").closest("span").removeClass("checked");
							$("#no_end_date3").closest("span").removeClass("checked");
							$("#no_end_date1").closest("span").addClass("checked");
							$("input[name='no_end_date']").removeAttr("checked","checked");
							$("input[name='no_end_date']").prop('checked', false);
							$("#no_end_date1").attr("checked","checked");
							$("#no_end_date1").prop('checked', true);
							
							$('#dvLoading').fadeOut('slow');
							alertify.alert("Oops! There is no possibility to occur recurrence , Can you try again?");
						}
					}
				});
			}
		});
		
		$('#datepicker_end_by').datepicker({
			startDate : $("#start_on_date").val(),
			format : '<?php echo $date_arr_java[$default_format]; ?>',
                        autoClose:true
   		}).on('changeDate', function(date){
   			
   			$.ajax({
   				type : 'post',
				url : '<?php echo site_url('task/set_end_after_recurrence'); ?>',
				data : $('#frm_add_recurrence').serialize(),
				success : function(data){
					var data = jQuery.parseJSON(data);
					
					if(data.end_after_recurrence!='0'){
						$("#end_after_recurrence").val(data.end_after_recurrence);
						$("#start_on_date").val(data.start_date);
						if(data.start_date){
						$('#start_on_date_picker').datepicker("update", data.start_date);
						}
						$("#start_on_date_picker").datepicker('refresh');
						
						$("#no_end_date2").closest("span").removeClass("checked");
						$("#no_end_date1").closest("span").removeClass("checked");
						$("#no_end_date3").closest("span").addClass("checked");
						$("input[name='no_end_date']").removeAttr("checked","checked");
						$("input[name='no_end_date']").prop('checked', false);
						$("#no_end_date3").attr("checked","checked");
						$("#no_end_date3").prop('checked', true);
						$("#hdn_no_end_date").val("3");
						
					} else {
						$("#end_after_recurrence").val('');
						$("#start_on_date").val('');
						$("#end_by_date").val('');
						$("#no_end_date2").closest("span").removeClass("checked");
						$("#no_end_date3").closest("span").removeClass("checked");
						$("#no_end_date1").closest("span").addClass("checked");
						$("input[name='no_end_date']").removeAttr("checked","checked");
						$("input[name='no_end_date']").prop('checked', false);
						$("#no_end_date1").attr("checked","checked");
						$("#no_end_date1").prop('checked', true);
						$("#hdn_no_end_date").val("1");
						alertify.alert("Oops! There is no possibility to occur recurrence , Can you try again?");
					}
				}
   			});
   		});
   		
	});
	
	
</script>
<script src="<?php echo $theme_url;?>/assets/js/task-frequency.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<div class="portlet">
	<div class="portlet-body  form">
		<div class='alert alert-success' id="update_freq__msg" style="display:none;" ><a class='closemsg' data-dismiss='alert'></a><span>Task Frequency is updated successfully.</span></div>
		<div class="horizontal-form">
			
			
			<form name="frm_add_recurrence" id="frm_add_recurrence">
				
				<!-- ***************** -->
				<div class="popuphight">
				<!-- ***************** -->	
					<div class="no_task_msg" style="display: none;">
						<div class='task_not_found_msg'><span>Please save the task before adding this.</span></div>
					</div>
					
					<div class="normal_div">
						<div id="frquency_for_master_msg" style="display: none;">
							<div class="portlet">
								
								<div class="portlet-body  form">
									<h3 class="no-margin"> Occurence  </h3>
								</div>
								<div class="portlet">
									<div class="portlet-body  form flip-scroll">
										
										<div class="form-group">
											<label class="control-label">Series task is not exist, so you can not create frequency for the task.</label>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						
						<div id="frquency_disable" style="display: none;">
							
							<div class="portlet">
								<div class="portlet-body  form">
									<h3 class="no-margin"> Occurence  </h3>
								</div>
								<div class="portlet">
									<div class="portlet-body  form flip-scroll">
										
										<div class="form-group">
											<label class="control-label">To modify the frequency of the recurring task, please edit the Serie. <a href="javascript://" onclick="set_new_task_data()">Click here</a> to edit the serie.</label>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						
						<div id="frquency_normal">
							<div class="form-group">
								<div class="controls relative-position">                                                
									<label class="radio">
										<input class="onsub task-chk-input" type="radio" name="frequency_type" id="one_off" value="one_off" checked="checked" />
										One Off
									</label>
									<label class="radio">
										<input class="onsub task-chk-input" type="radio" name="frequency_type" id="recurrence" value="recurrence" />
									  	Recurrence
									</label> 
									<span class="input-load" id="frequency_type_loading"></span> 
								</div>
							</div>
							
							<div id="recurrence_div" style="display:none;">				
								<div class="lightgrey-box margin-bottom-20">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<div class="controls relative-position">
													<label class="radio line">
														<input class="onsub task-chk-input" type="radio" name="recurrence_type" id="daily_chk" value="1"  />
														Daily
													</label>
													<label class="radio line">
														<input class="onsub task-chk-input" type="radio" name="recurrence_type" id="weekly_chk" value="2" />
														Weekly
													</label>  
													<label class="radio line">
														<input class="onsub task-chk-input" type="radio" name="recurrence_type" id="monthly_chk" value="3" />
														Monthly
													</label>  
													<label class="radio line">
														<input class="onsub task-chk-input" type="radio" name="recurrence_type" id="yearly_chk" value="4" />
														Yearly
													</label> 
													<span class="input-load" id="recurrence_type_loading"></span> 
												</div>
											</div>
									    </div>
									    <div class="col-md-9" id="daily_div" style="display:none;">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<div class="controls clearfix relative-position">
															<div class="set-box1">
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="Daily_every_weekday" id="Daily_every_weekday"  value="0" />
																	Every
																</label>
															</div>
														 	<div class="set-box2">
																<input class="onsub task-input" type="text" name="Daily_every_day" id="Daily_every_day" value="" class="m-wrap adj-recm-wrap " style="border-radius: 4px;padding: 5px;"/> <span class="txtlabl"> day(s)   </span>
														 	</div>
															<span class="input-load" id="Daily_every_day_loading"></span>
														</div>
												 	</div>
													 
													<div class="form-group">
														<div class="controls clearfix relative-position">
															<div class="set-box1">
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="Daily_every_weekday" id="Daily_every_weekday2"  value="1" />
																	Every
																</label>
															</div>
														 	<div class="set-box2">
																<input type="text" name="Daily_every_week_day" id="Daily_every_week_day" value="" class="m-wrap adj-recm-wrap task-input" /> <span class="txtlabl"> weekday(s)   </span>
														 	</div>
															<span class="input-load" id="Daily_every_week_day_loading"></span>
														</div>
												 	</div>
													
												</div>
											</div>
										</div>
										
										<div class="col-md-9" id="weekly_div" style="display: none;">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<div class="controls clearfix relative-position">
															<div class="set-box1">
																<label class="radio line">
																	Recur every
																</label>
															</div>
														 	<div class="set-box2">
																<input  type="text" placeholder="1" name="Weekly_every_week_no" id="Weekly_every_week_no" value="" class="m-wrap adj-recm-wrap task-input" /> <span class="txtlabl"> week(s) on :    </span>
														 	</div>
														</div>
												 	</div>
													 
													<div class="form-group">
														<div class="controls clearfix">
															<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="checkbox" name="Weekly_week_day[]" id="weekly_week_day_1" value="1" />
																	Monday
																</label>
															</div>
															
															<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="checkbox" name="Weekly_week_day[]" id="weekly_week_day_2" value="2" />
																	Tuesday
																</label>
															</div>
															
														   	<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="checkbox" name="Weekly_week_day[]" id="weekly_week_day_3" value="3" />
																	Wednesday
																</label>
															</div>
															
															<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="checkbox" name="Weekly_week_day[]" id="weekly_week_day_4" value="4" />
																	Thursday
																</label>
															</div>
															
														</div>
												 	</div> 	 
													 
													<div class="form-group">
														<div class="controls clearfix relative-position">
															
															<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="checkbox" name="Weekly_week_day[]" id="weekly_week_day_5" value="5" />
																	Friday
																</label>
															</div>
															
															<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="checkbox" name="Weekly_week_day[]" id="weekly_week_day_6" value="6" />
																	Saturday
																</label>
															</div>
															
														   	<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="checkbox" name="Weekly_week_day[]" id="weekly_week_day_7" value="7" />
																	Sunday
																</label>
															</div>
															<span class="input-load" id="Weekly_week_day_loading"></span>
														</div>
													 </div>
												</div>
											</div>
										</div>
										<div class="col-md-9" id="monthly_div" style="display: none;">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group" >
														<div class="controls clearfix">
															<div class="set-box1">
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="monthly_radios" id="monthly_radios1" value="1" />
																	Day
																</label>
															</div>
															<div id="monthly_op1">
															 	<div class="set-box2">
																	<input type="text" placeholder="" name="Monthly_op1_1" id="Monthly_op1_1" value="" class="m-wrap adj-recm-wrap task-input" /> <span class="txtlabl"> of every    </span>
															 	</div>
																<div class="set-box2">
																	<input type="text" placeholder=" " name="Monthly_op1_2" id="Monthly_op1_2" value="" class="m-wrap adj-recm-wrap task-input" /> <span class="txtlabl"> month(s)    </span>
															 	</div>
														 	</div>
														</div>
												 	</div>
													 
													<div class="form-group" >
														<div class="controls clearfix">
															<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="monthly_radios" id="monthly_radios2" value="2" />
																	The
																</label>
															</div>
														   	<div class="set-box2" id="monthly_op2">
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Monthly_op2_1" id="Monthly_op2_1" tabindex="1">
																	<option value="first" >First</option>
																	<option value="second" >Second</option>
																	<option value="third" >Third</option>
																	<option value="fourth" >Fourth</option>
																	<option value="last" >Last</option>
																</select>
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Monthly_op2_2" id="Monthly_op2_2" tabindex="1">
																	<option value="Monday">Monday</option>
																	<option value="Tuesday">Tuesday</option>
																	<option value="Wednesday">Wednesday</option>
																	<option value="Thursday">Thursday</option>
																	<option value="Friday">Friday</option>
																	<option value="Saturday">Saturday</option>
																	<option value="Sunday">Sunday</option>
																</select>
																<input type="text" placeholder="1" name="Monthly_op2_3" id="Monthly_op2_3" value="" class="m-wrap adj-recm-wrap task-input" />  <span class="txtlabl"> month(s)    </span>
														 	</div>
														</div>
												 	</div> 	 
													 
													<div class="form-group">
														<div class="controls clearfix">
															<div class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="monthly_radios" id="monthly_radios3" value="3" />
																	Working day
																</label>
															</div>
															<div class="set-box2">
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Monthly_op3_1" id="Monthly_op3_1" tabindex="1">
																	<?php 
																	for($i=-5;$i<17;$i++){
																		if($i == 0){
																			
																		} else { ?>
																		<option value="<?php echo $i;?>"><?php echo $i;?></option>
																	<?php }
																	 } ?>
																</select>
																<span class="txtlabl">of every </span>
															</div>	
														 	<div class="set-box2">		
																<input type="text" placeholder="1" name="Monthly_op3_2" id="Monthly_op3_2" value="" class="m-wrap adj-recm-wrap task-input" />  <span class="txtlabl"> month(s)    </span>
														 	</div>
														</div>
													 </div>
												</div>
											</div>
										</div>
										<div class="col-md-9" id="yearly_div" style="display: none;">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<div class="controls clearfix">
															<div class="set-box1">
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="yearly_radios" id="yearly_radios1" value="1" />
																	Recur every
																</label>
															</div>
														 	<div class="set-box2">
																<input type="text" placeholder=" " name="Yearly_op1" id="Yearly_op1" value="" class="m-wrap adj-recm-wrap task-input" /> <span class="txtlabl"> year(s)   </span>
														 	</div>
														</div>
												 	</div>
													 
													<div class="form-group">
														<div class="controls clearfix">
															<div  class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="yearly_radios" id="yearly_radios2" value="2" />
																	on 
																</label>
															</div>
														   	<div class="set-box2">
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Yearly_op2_1" id="Yearly_op2_1" tabindex="1">
																	<option value="1">January</option>
																	<option value="2">February</option>
																	<option value="3">March</option>
																	<option value="4">April</option>
																	<option value="5">May</option>
																	<option value="6">June</option>
																	<option value="7">July</option>
																	<option value="8">August</option>
																	<option value="9">September</option>
																	<option value="10">October</option>
																	<option value="11">November</option>
																	<option value="12">December</option>
																</select>
																<input type="text" placeholder="1" name="Yearly_op2_2" id="Yearly_op2_2" value="" class="m-wrap adj-recm-wrap task-input" />
														 	</div>
														</div>
												 	</div> 	 
													 
													<div class="form-group">
														<div class="controls clearfix">
															<div class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="yearly_radios" id="yearly_radios3" value="3" />
																	The
																</label>
															</div>
														  	<div class="set-box2">
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Yearly_op3_1" id="Yearly_op3_1" tabindex="1">
																	<option value="first" >First</option>
																	<option value="second">Second</option>
																	<option value="third">Third</option>
																	<option value="fourth">Fourth</option>
																	<option value="last">Last</option>
																</select>
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Yearly_op3_2" id="Yearly_op3_2" tabindex="1">
																	<option value="Monday">Monday</option>
																	<option value="Tuesday">Tuesday</option>
																	<option value="Wednesday">Wednesday</option>
																	<option value="Thursday">Thursday</option>
																	<option value="Friday" >Friday</option>
																	<option value="Saturday">Saturday</option>
																	<option value="Sunday">Sunday</option>
																	
																</select>
																<span class="txtlabl">of &nbsp;</span>
															</div>	
														 	<div class="set-box2">		
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Yearly_op3_3" id="Yearly_op3_3" tabindex="1">
																	<option value="January">January</option>
																	<option value="February">February</option>
																	<option value="March">March</option>
																	<option value="April">April</option>
																	<option value="May" >May</option>
																	<option value="June">June</option>
																	<option value="July">July</option>
																	<option value="August">August</option>
																	<option value="September">September</option>
																	<option value="October">October</option>
																	<option value="November">November</option>
																	<option value="December">December</option>
																</select>
														 	</div>
														</div>
													 </div>
													 
													 <div class="form-group">
														<div class="controls clearfix">
															<div class="set-box1" >
																<label class="radio line">
																	<input class="onsub task-chk-input" type="radio" name="yearly_radios" id="yearly_radios4" value="4" />
																	The working day
																</label>
															</div>
														  	<div class="set-box2">
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Yearly_op4_1" id="Yearly_op4_1" tabindex="1">
																	<?php 
																	for($i=-5;$i<17;$i++){
																		if($i == 0){
																			
																		} else { ?>
																		<option value="<?php echo $i;?>"><?php echo $i;?></option>
																	<?php }
																	 } ?>
																</select>
																<span class="txtlabl">of &nbsp;</span>
																<select class="m-wrap adj-recm-wrap2 no-margin task-input" name="Yearly_op4_2" id="Yearly_op4_2" tabindex="1">
																	<option value="January" >January</option>
																	<option value="February">February</option>
																	<option value="March">March</option>
																	<option value="April">April</option>
																	<option value="May">May</option>
																	<option value="June">June</option>
																	<option value="July" >July</option>
																	<option value="August">August</option>
																	<option value="September">September</option>
																	<option value="October">October</option>
																	<option value="November">November</option>
																	<option value="December">December</option>
																</select>
																
															</div>	
														 	
														</div>
													 </div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<h6 class="heading6 no-padding">  <strong> Range of Recurrence  </strong>  </h6>
								<div class="row">
									<div class="col-md-6 ">
										<div class="form-group no-margin">
											<label class="control-label">Start on<span class="required">*</span></label> 
											<div class="controls">
												<div id="start_on_date_picker" class="input-append date date-picker" data-date="" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
													<input class="onsub m-wrap m-ctrl-medium task-input task-input frequency-change" name="start_on_date" id="start_on_date" size="16" type="text" value="" /><span class="add-on"><i class="icon-calendar taskppicn"></i></span>
                                                                                                        
												</div>
                                                                                            <input type="hidden" name='is_start_date' id="is_start_date" value='0'  />
											</div>
										</div>
									</div> 
								</div>
								
								<div class="row" style="padding-bottom: 2px;">
									<div class="col-md-3">
										<div class="form-group">
											<div class="controls">
												<label class="radio line">
													<input class="onsub task-chk-input" type="radio" name="no_end_date_val" id="no_end_date1" value="1" />
													No End Date
												</label>
											</div>
										</div>	 
									</div>
								</div>
								
								<div class="row" style="padding-bottom: 2px;">
									<div class="col-md-2">
										<div class="form-group">
											<div class="controls">
												<label class="radio line">
													<input class="onsub task-chk-input" type="radio" name="no_end_date_val" id="no_end_date2" value="2" />
													End After
												</label>
											</div>
										</div>	 
									</div>
									<div class="col-md-5">
										<input type="text" placeholder="1" name="end_after_recurrence" id="end_after_recurrence" value="" class="m-wrap adj-recm-wrap" /> <span class="txtlabl"> Recurrence    </span>
									</div>
								</div>
								
								<div class="row" style="padding-bottom: 2px;">
									<div class="col-md-2">
										<div class="form-group">
											<div class="controls">
												<label class="radio line">
													<input class="onsub task-chk-input" type="radio" name="no_end_date_val" id="no_end_date3" value="3" />
													End By
												</label>
												
											
											</div>
										</div>	 
									</div>
									
									<div class="col-md-5" id="chk_end_by">
										<div class="input-append date date-picker" id="datepicker_end_by" data-date="<?php echo date($default_format);?>" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
										<input class="onsub m-wrap m-ctrl-medium task-input task-input frequency-change" data-date-orientation="bottom auto"  name="end_by_date" id="end_by_date" size="16" type="text" value="" /><span class="add-on"><i class="icon-calendar taskppicn"></i></span>
									</div>
									</div>
									<input type="hidden" name="no_end_date" id="hdn_no_end_date" value="" />
								</div>
							</div>
						</div>
					</div>
				</div>
								 	
					<input type="hidden" name="task_id" id="freq_task_id" value="" />
					<input type="hidden" name="task_pre_due_date" id="task_pre_due_date" value="" />
			</form>
		</div> 
   	</div>
</div>

