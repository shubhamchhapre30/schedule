<?php
$theme_url = base_url().getThemeName(); 
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
$members = get_company_users();
//echo $task_priority;die;

//echo date(default_date_format(),strtotime("2015-01-1"));die;
?>
<script src="<?php echo $theme_url;?>/assets/js/common<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		
 	$('#task_due_date').datepicker({
		format: '<?php echo $date_arr_java[default_date_format()]; ?>',
                autoclose: true,
                
	});
		
		
	$("#locked_due_date").on('switchChange.bootstrapSwitch',function(){
			if($("#locked_due_date").is(":checked")){
				$("#hdn_locked_due_date").val('1');
			} else {
				$("#hdn_locked_due_date").val('');
			}
		});
	});
	
	
	
</script>
<link href="<?php echo $theme_url;?>/mobile-js-css/css/bootstrap-switch.css" rel="stylesheet">





<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			 	 <!--
				   <div class="page-title margin-bottom-25">
									   <h2 class="text-left"> <?php //echo ($task_id=='')?'New Task':'Edit Task';?> </h2>
								   </div>-->
				  
			  
				 <!--<div class="border-bx">-->
				  <div class="horizontal-form">
					 	<!-- BEGIN FORM-->
					 	<?php if($error!=''){
							?>
							<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
							<?php
						}?>
						<?php   $attributes = array('name'=>'frm_addtask', 'id' => 'frm_addtask');
								echo form_open('task/add_ind_task', $attributes); 
						?>
							<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										 <label class="control-label">Title : </label> 
										<div class="controls">
											<input type="text" id="task_title" value="<?php echo $task_title;?>"  name="task_title" placeholder="Task Title" class="m-wrap fullwd " />
										 </div>
										 <!--<span class="chr">Char left :- <i id="ch14"><?php echo CMT_TEXT_SIZE;?></i></span>-->
									
        </div>
									
									<div class="control-group">
										 <label class="control-label">Description :</label> 
										<div class="controls">
											<textarea class="m-wrap fullwd" id="task_description" name="task_description" rows="3"><?php echo $task_description;?></textarea>
										</div>
										<!--<span class="chr">Char left :- <i id="ch13">10000</i></span>-->
									</div>
									<div class="control-group">
										 <label class="control-label">Priority :</label> 
										<div class="controls">
											 <select class="fullwd m-wrap" id="task_priority" name="task_priority" tabindex="1">
											 	<option value="None"  <?php if($task_priority=='None'){ echo 'selected="selected"'; } ?> > None </option>
											 	<option value="Low"  <?php if($task_priority=='Low'){ echo 'selected="selected"'; } ?> > Low </option>
											 	<option value="Medium"  <?php if($task_priority=='Medium'){ echo 'selected="selected"'; } ?> > Medium </option>
											 	<option value="High"  <?php if($task_priority=='High'){ echo 'selected="selected"'; } ?> > High</option>
											 	<?php /*if($priority!='0'){
											 		foreach ($priority as $tp) {
											 	?>
												<option value="<?php echo $tp->task_priority;?>"  <?php if($tp->task_priority == $task_priority){ echo 'selected="selected"'; } ?> > <?php echo $tp->task_priority;?> </option>
												<?php  } }	*/?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										 <label class="control-label">Status :</label> 
										<div class="controls">
											 <select class="fullwd m-wrap" id="task_status_id" name="task_status_id" tabindex="1">
									<?php 
											$task_status = get_taskStatus($this->session->userdata("company_id"),"Active");
											if($task_status){
												foreach($task_status as $ts){
													?>
													<option value="<?php echo $ts->task_status_id;?>" <?php if($task_status_id == $ts->task_status_id){ echo "selected='selected'"; }?> ><?php echo $ts->task_status_name;?></option>
													<?php
												}
											}
											?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										 <label class="control-label">Color :</label> 
										<div class="controls">
											 <select class="fullwd m-wrap" id="task_color_id" name="task_color_id" tabindex="1">
											 	<option value="0">Please select</option>
											 	<?php if(isset($color_codes) && $color_codes != ''){ ?>
													<?php foreach($color_codes as $color){ ?>
													<option < value="<?php echo $color->user_color_id;?>" <?php if($task_id==''){ if($default_color == $color->user_color_id){ echo "selected='selected'"; }}else{ if($color_id!='0'){  if($color_id == $color->user_color_id){ echo "selected='selected'"; }}else{}} ?> > <?php echo $color->name; ?> </option>

													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
        
									<div class="control-group">
										 <label class="control-label">Category :</label> 
										<div class="controls">
											 <select class="fullwd m-wrap" name="task_category_id" id="task_category_id" tabindex="1">
												<option value="">Select Task Category</option>
													<?php if(isset($main_category) && $main_category!=''){
														foreach($main_category as $cat){
															?>
															<option value="<?php echo $cat->category_id ?>" <?php if($cat->category_id == $task_category_id){ echo 'selected="selected"'; } ?> ><?php echo $cat->category_name; ?></option>
															<?php
														}
													}?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										 <label class="control-label">Sub Category :</label> 
										<div class="controls" id="updated_subCategory" >
											 <select class="fullwd m-wrap" name="task_sub_category_id" id="task_sub_category_id" tabindex="1">
												<option value="">Select Sub Category</option>
												<?php if(isset($sub_category) && $sub_category!=''){
														foreach($sub_category as $sub_cat){
															?>
															<option value="<?php echo $sub_cat->category_id ?>" <?php if($sub_cat->category_id == $task_sub_category_id){ echo 'selected="selected"'; } ?> ><?php echo $sub_cat->category_name; ?></option>
															<?php
														}
													}?>
											</select>
										</div>
									</div>
									<?php 
										if($task_due_date != '0000-00-00' && $task_due_date!=''){
											
											//echo $task_due_date."====";die;
											$due_dt = date(default_date_format(),strtotime(str_replace("/", "-", $task_due_date)));
										} else {
											//echo "else";die;
											$due_dt = date(default_date_format());
										} 
									?>
									
									<div class="control-group clearfix">
										 <label class="control-label">Due Date : </label> 
										<div class="controls set-controls">
											<input type="text" placeholder="Due date" name="task_due_date" id="task_due_date"  value="<?php echo ($due_dt!="0000-00-00")?$due_dt:'';?>" class="m-wrap fullwd " />
										 </div>
										<span> <i class="stripicon calgreyicon"> </i> </span>
									</div>
									
									 <div class="control-group">
										 <label class="control-label">Assign to : </label> 
										<div class="controls">
											<!--<input type="text" placeholder="" class="m-wrap fullwd " />-->
											<select class="span11 m-wrap fullwd" name="task_allocated_user_id" id="task_allocated_user_id" tabindex="1">
											<option value=""> select user</option>
											<?php
												if(isset($members) && $members != ''){
													foreach($members as $u){
														?>
														<option value="<?php echo $u->user_id;?>" <?php if($u->user_id == $task_allocated_user_id){ echo 'selected="selected"'; }?> > <?php echo $u->first_name.' '.$u->last_name; ?> </option>
														<?php
													}
												} 
											?>
											</select>
										 </div>
									</div>
									
									<div class="control-group">
										 <label class="control-label">Projects : </label> 
										<div class="controls">
											<!--<input type="text" placeholder="" class="m-wrap fullwd " />-->
											<select class="span11 m-wrap fullwd" name="task_project_id" id="task_project_id" tabindex="1" onchange="set_project_section();" >
											<option value="">Link to project</option>
											<?php
												if(isset($user_projects) && $user_projects != ''){
													foreach($user_projects as $project){
														?>
														<option value="<?php echo $project->project_id;?>" <?php if($project->project_id == $task_project_id){ echo 'selected="selected"'; }?> > <?php echo $project->project_title; ?> </option>
														<?php
													}
												} 
											?>
											</select>
										 </div>
									</div>
									
									<div class="control-group">
										 <label class="control-label">Section : </label> 
										<div class="controls" id="section_div" >
											<!--<input type="text" placeholder="" class="m-wrap fullwd " />-->
											<select class="span11 m-wrap fullwd" name="section_id" id="section_id" tabindex="1">
											<option value=""> select section</option>
											<?php
												/*
												if(isset($section) && $section != ''){
																									foreach($section as $u){
																										?>
																										<option value="<?php echo $u->section_id;?>" <?php if($u->section_id == $section_id){ echo 'selected="selected"'; }?> > <?php echo $u->section_name; ?> </option>
																										<?php
																									}
																								}*/
												 
											?>
											</select>
										 </div>
									</div>
									
									 <!--
									 <div class="control-group">
																			  <label class="control-label">Time Estimate : </label> 
																			 <div class="controls">
																				 <input type="text" placeholder=" " class="m-wrap fullwd " />
																			  </div>
																		 </div>-->
									 <div class="control-group">
									  <label class="control-label">Time Estimate : </label> 
										 <div class="controls">
											 <input type="text" id="task_time_estimate" name="task_time_estimate" placeholder="0h" value="<?php echo $task_time_estimate;?>" class="m-wrap margin-bottom-10 " />
											 <input  name="task_time_estimate_hour" value="<?php echo $task_time_estimate_hour;?>" type="hidden" />
											<input name="task_time_estimate_min" value="<?php echo $task_time_estimate_min;?>" type="hidden" />
											<input  name="old_task_time_estimate_hour" value="" type="hidden" />
											<input name="old_task_time_estimate_min" value="" type="hidden" />
											<input type="hidden" name="is_edited1" value="" />
											 
											 <!--<input type="text" id="task_time_estimate_min" name="task_time_estimate_min" placeholder=" Minutes" value="<?php echo $task_time_estimate_min;?>" class="m-wrap margin-bottom-10 " />-->
											 
										  </div>
										  </div>
										  
									<?php if($task_id!=''){ ?>	  
									<div class="control-group">
									  <label class="control-label">Time Spent : </label> 
										 <div class="controls">
											 <input type="text" id="task_time_spent" name="task_time_spent" placeholder="0h" value="<?php echo $task_time_spent;?>" class="m-wrap margin-bottom-10 " />
											 <input  name="task_time_spent_hour" value="<?php echo $task_time_spent_hour;?>" type="hidden" />
											<input name="task_time_spent_min" value="<?php echo $task_time_spent_min;?>" type="hidden" />
											<input  name="old_task_time_spent_hour" value="" type="hidden" />
											<input name="old_task_time_spent_min" value="" type="hidden" />
											<input type="hidden" name="is_edited" value="" />
											 
											 <!--<input type="text" id="task_time_estimate_min" name="task_time_estimate_min" placeholder=" Minutes" value="<?php echo $task_time_estimate_min;?>" class="m-wrap margin-bottom-10 " />-->
											 
										  </div>
										  </div>
									<?php } ?>
									  
									 <div class="control-group">
										 <label class="control-label-left addtaskfontsize">Private :</label> 
										<div class="controls-left">
											<input name="is_personal" id="is_personal" onclick="chk_personal();" value="1" <?php if($is_personal == '1'){ echo 'checked="checked"'; } ?> type="checkbox" >
											
										 </div>
									</div>
									
									<div class="control-group">
										 <label class="control-label-left addtaskfontsize">Lock Due Date :</label> 
										<div id="loc_due_dt" class="controls-left">
											<input name="locked_due_date" id="locked_due_date" value="1" <?php if($locked_due_date == '1'){ echo "checked='checked'"; } ?> type="checkbox" >
											<input name="hdn_locked_due_date" id="hdn_locked_due_date" value="" type="hidden" >	
										 </div>
									</div> 
								</div>
							</div>
								
							  <div class="control-group">
								<div class="controls text-center margin-top-20">
									
									<input type="hidden" id="task_id" name="task_id" value="<?php echo $task_id;?>" />
									<input type="hidden" id="task_owner_id" name="task_owner_id" value="<?php echo get_authenticateUserID();?>" />
									<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo isset($redirect_page)?$redirect_page:'from_kanban'; ?>" />
									
									 <button type="submit" class="btn blue btn-mid"> <i class="stripicon correcticon"> </i> <?php echo ($task_id=='')?'Save':'Update';?> </button>
									 <?php if($task_id!=''){ ?>
									 <button type="button" onclick="goBack();" class="btn blue btn-mid"> <i class="stripicon backicon"> </i> Cancel </button>
									 <?php }else{ ?>
									 	<button type="button" onclick="goBack();"  class="btn blue btn-mid"> <i class="stripicon backicon"> </i> Cancel </button>
									 	<?php } ?>
								 </div>
								 
							</div>
						</form>
						<!-- END FORM-->  
					</div>
				<!-- </div>-->
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>






<script src="<?php echo $theme_url; ?>/js/jquery.tinylimiter.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript">
	$(document).ready(function(){		
		var is_edited1 = $("input[name='is_edited1']").val();
		
		$("input[name='task_time_spent']").on('change', function() {
			  is_edited = '1';
		});
		
		$("input[name='task_time_estimate']").on('change', function() {
			  is_edited1 = '1';
		});
		
		
		var form1 = $('#frm_addtask');
	    var error1 = $('.alert-error', form1);
	    var success1 = $('.alert-success', form1);
		    
		$('#frm_addtask').validate({
			errorElement: 'span', //default input error message container
	        errorClass: 'help-inline', // default input error message class
	        focusInvalid: true, // do not focus the last invalid input
	        ignore: "",
			rules: {
               "task_title": {
	                  required: true,
	                  rangelength: [3, 300]
              },
              "task_description": {
                  rangelength: [0, 10000]
              },
	    	  
	           "task_time_estimate_hour" : {
	           		number : true
	           },
	    	  
	           "task_time_estimate_min":{
	           			number : true
	           }
	           
            },
           errorPlacement: function (error, element) {

                if (element.attr("name") == "task_due_date" || element.attr("name") == "task_time_estimate_hour" || element.attr("name") == "task_time_estimate_min") { // for chosen elements, need to insert the error after the chosen container
                    error.appendTo( element.parent("div") );
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            //perform an AJAX post to ajax.php
            submitHandler: function (form1) {
	            success1.show();
	            error1.hide();
	            $("button[type=submit]").prop("disabled",true);
	            form1.submit();
	        }
		});
		 
		
		$("#task_category_id").change(function(){
			var parent_div_id = $(this).val();
			$('#dvLoading').fadeIn('slow');
			$.ajax({
	            type: 'post',
	            url : '<?php echo site_url("project/setSubCategory"); ?>',
	            data: {parent_id : parent_div_id},
	            success: function(responseData) {
	                $("#updated_subCategory").html(responseData);
	                $('#dvLoading').fadeOut('slow');
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	                $('#dvLoading').fadeOut('slow');
	            }
	        });
		});
		
		
		
		$("input[name='task_time_estimate']").blur(function(){
			var val = $(this).val();
			var val_clone = val;
			
			if(val && is_edited1=='1'){
				if(validate(val) == true )   ///&& (!$('#manual_reason').hasClass('in'))
				{
		              var splitval = val.split(":");
		              var splitval_clone = val.split(":");
	
						if(splitval.length==2){
							var h = splitval[0];
							var m = splitval[1];
							if(m >= 60){
								var mm1 = parseInt(m / 60);
								var mm2 = m % 60;
								
								var hh = +h + +mm1;
								var mm = mm2;
								
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(hh);
								$("input[name='task_time_estimate_min']").val(mm);
								
							}else{
								var hh = h;
								var mm = m;
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(hh);
								$("input[name='task_time_estimate_min']").val(mm);
							}
						}
						
						if(val.length>=1 && val.length <=2)
						{
							if(val >= 60){
								var hh = parseInt(val / 60);
								var mm = val % 60;
		
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(hh);
								$("input[name='task_time_estimate_min']").val(mm);
							}else{
								var mm = val;
								var time = mm + "m";
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(0);
								$("input[name='task_time_estimate_min']").val(mm);
							}
						}
						if(val.length==3 && splitval.length!=2)
						{
							var digits = new Array();
							var digits= (""+val).split("");
							if((digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])>=60)
							{
								var additional = 1;
								var sum = [];
								var mm =  (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])-60;
								var hh = +digits[val.length-val.length]+ +additional;
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(hh);
								$("input[name='task_time_estimate_min']").val(mm);
								
							}else{
								var mm = (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)]);
								var hh = digits[val.length-val.length];
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(hh);
								$("input[name='task_time_estimate_min']").val(mm);
							}
						}
						
						if(val.length==4 && splitval.length!=2)
						{
							var digits = new Array();
							var digits= (""+val).split("");
							if((digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])>=60)
							{
								var additional = 1;
								var sum = [];
								var mm =  (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])-60;
								var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)])+ +additional;
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(hh);
								$("input[name='task_time_estimate_min']").val(mm);
								
							}else{
								
								var mm = (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)]);
								var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)]);
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("input[name='task_time_estimate']").val(time);
								$("input[name='task_time_estimate_hour']").val(hh);
								$("input[name='task_time_estimate_min']").val(mm);
							}
						}
						if(val.length>=5 && splitval.length!=2){
							$("input[name='task_time_estimate']").val('');
							alertify.alert('maximum 4 digits allowed');
						}
					}else{
							$("input[name='task_time_estimate']").val('');
							alertify.alert('your inserted value is not correct, please insert correct value');
					}
				}
				
		});
		
		$("input[name='task_time_spent']").blur(function(){
				var val = $(this).val();
				var val_clone = val;
				var is_edited = 1;
				
				var splitval = val.split(":");
				var splitval_clone = val.split(":");
				
				var old_time = parseInt($("#task_time_spent_hour").val()*60)+parseInt($("#task_time_spent_min").val());
			
				if(val){
					if(is_edited=='1'){
						if(validate(val) == true){
							is_edited = '0';
				         			
							if(splitval.length==2){
								var h = splitval[0];
								var m = splitval[1];
								if(m >= 60){
									var mm1 = parseInt(m / 60);
									var mm2 = m % 60;
			
									var hh = +h + +mm1;
									var mm = mm2;
			
									if(hh==0){
										var time = mm+"m";
									}else if(mm==0){
										var time = hh+"h";
									} else {
										var time = hh + "h "+ mm+"m";
									}
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
								} else {
									var hh = h;
									var mm = m;
									if(hh==0){
										var time = mm+"m";
									}else if(mm==0){
										var time = hh+"h";
									} else {
										var time = hh + "h "+ mm+"m";
									}
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
								}
							}
							if(val.length>=1 && val.length <=2){
								if(val >= 60){
									var hh = parseInt(val / 60);
									var mm = val % 60;
			
									if(hh==0){
										var time = mm+"m";
									}else if(mm==0){
										var time = hh+"h";
									}else{
										var time = hh + "h "+ mm+"m";
									}
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
								}else{
									var mm = val;
									var time = mm + "m";
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
								}
							}
							if(val.length==3 && splitval.length!=2)
							{
								var digits = new Array();
								var digits= (""+val).split("");
								if((digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])>=60)
								{
									var additional = 1;
									var sum = [];
									var mm =  (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])-60;
									var hh = +digits[val.length-val.length]+ +additional;
									if(hh==0){
										var time = mm+"m";
									}else if(mm==0){
										var time = hh+"h";
									}else{
										var time = hh + "h "+ mm+"m";
									}
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
			
								}else{
									var mm = (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)]);
									var hh = digits[val.length-val.length];
									if(hh==0){
										var time = mm+"m";
									}else if(mm==0){
										var time = hh+"h";
									}else{
										var time = hh + "h "+ mm+"m";
									}
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
								}
							}
			
							if(val.length==4 && splitval.length!=2)
							{
								var digits = new Array();
								var digits= (""+val).split("");
								if((digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])>=60)
								{
									var additional = 1;
									var sum = [];
									var mm =  (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])-60;
									var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)])+ +additional;
									if(hh==0){
										var time = mm+"m";
									}else if(mm==0){
										var time = hh+"h";
									}else{
										var time = hh + "h "+ mm+"m";
									}
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
								}else{
			
									var mm = (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)]);
									var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)]);
									if(hh==0){
										var time = mm+"m";
									}else if(mm==0){
										var time = hh+"h";
									}else{
										var time = hh + "h "+ mm+"m";
									}
									$("input[name='task_time_spent']").val(time);
									$("input[name='task_time_spent_hour']").val(hh);
									$("input[name='task_time_spent_min']").val(mm);
									//a(1);
								}
							}
							if(val.length>=5 && splitval.length!=2){
								$("input[name='task_time_spent']").val('');
								$("input[name='task_time_spent_hour']").val('0');
								$("input[name='task_time_spent_min']").val('0');
								is_edited = '1';
								$("#alertify").show();
								alertify.alert('maximum 4 digits allowed');
								$("#task_time_spent_loading").hide();
								return false;
							}
						}else{
							if(old_time == get_minutes(val)){
								$("#task_time_spent_loading").hide();
							} else {
								$("input[name='task_time_spent']").val('');
								$("input[name='task_time_spent_hour']").val('0');
								$("input[name='task_time_spent_min']").val('0');
								is_edited = '1';
								$("#alertify").show();
			         			alertify.alert('your inserted value is not correct, please insert correct value', function (e) {
									$("#task_time_spent").focus();
									return false;
								});
								$("#task_time_spent_loading").hide();
								return false;
			         			
			         		}
						}
					}
				}else{
					$("input[name='task_time_spent']").val('');
					$("input[name='task_time_spent_hour']").val('0');
					$("input[name='task_time_spent_min']").val('0');
				}
			});
		
		
		
});

function chk_personal(){
		if($("#is_personal").is(":checked")){
			$("#task_allocated_user_id").val('<?php echo get_authenticateUserID(); ?>');
			$("#task_allocated_user_id").attr('readonly');
			$("#task_owner_id_val").val('<?php echo usernameById(get_authenticateUserID());?>');
			$("#task_owner_id").val('<?php echo get_authenticateUserID();?>');
			$("#task_owner_id").attr('readonly');
		} else {
			$("#task_allocated_user_id").val('');
			$("#task_owner_id_val").val('<?php if($task_id!=''){ echo usernameById($task_owner_id); } else { echo usernameById(get_authenticateUserID()); } ?>');
			$("#task_owner_id").val('<?php if($task_id!='' ){ echo $task_owner_id; } else { echo get_authenticateUserID(); } ?>');
		}
	}
	
	function validate(v)
	{
		var v = v.replace(":","")
		var len = v.length;
		
		if(len <= 5){
		    return /^(([0-9\s\[\](\)\:/\\(/)/)])+$)/.test(v);	
		}else{
			return false;
		}
	}
	
	function set_project_section(){
		var project_id = $("#task_project_id").val();
		$('#dvLoading').fadeIn('slow');
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("task/get_project_sections");?>',
			data : {project_id : project_id},
			success : function(resposeData){
				$("#section_div").html(resposeData);
				$('#dvLoading').fadeOut('slow');
			}
		});
	}
</script>
<!--<script src="<?php echo $theme_url;?>/mobile-js-css/js/jquery.min.js?Ver=<?php echo VERSION;?>"></script>-->
<script src="<?php echo $theme_url;?>/mobile-js-css/js/highlight.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/mobile-js-css/js/bootstrap-switch.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/scripts/form-components.js?Ver=<?php echo VERSION;?>"></script> 
<script src="<?php echo $theme_url; ?>/assets/scripts/app.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/mobile-js-css/js/main.js?Ver=<?php echo VERSION;?>"></script>