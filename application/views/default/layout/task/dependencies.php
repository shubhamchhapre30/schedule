<link rel="stylesheet" href="<?php echo base_url().getThemename();?>/assets/css/jquery-ui.css?Ver=<?php echo VERSION;?>">

<script>
$(function() {
    $( "#task_name" ).autocomplete({ 
        source: function(request, response) {
	    $.getJSON('<?php echo site_url("task/listtask");?>', { term : request.term , searchDate: $('#search_date').val(),main_task_id : $('#search_task_id').val() }, 
	              response);
	  }
    });
    $('#task_name').on('autocompleteselect', function (e, ui) {
    	$('#dep_task_id').val(ui.item.id);
        $.ajax({
		type: 'post',
		url : '<?php echo site_url("task/search_dependency"); ?>',
		data: $('#frm_search_dependency').serialize(),
		success: function(responseData) {
                    $("#updated_dependencies").html(responseData);
                    $("#task_name").val('');
                    $('#search_date').val('');
                    $('#dvLoading').fadeOut('slow');
		},
		error: function(responseData){
                    console.log('Ajax request not recieved!');
                    $('#dvLoading').fadeOut('slow');
		}
	});
    });
});
</script>
<div class="portlet" style="overflow: visible;" >
	  <div class="portlet-body form">
	  	<?php if(isset($error) && $error!=''){ 
 		?>
		<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
		<?php
 	}?>
		 <div class="horizontal-form">
		 	<!-- ***************** -->
			<div class="popuphight">
			<!-- ***************** -->	
			<div class="no_task_msg" style="display: none;">
				<div class='task_not_found_msg'><span>Please save the task before adding this.</span></div>
			</div>
			<div id="personal_task_msg" style="display: none;">
				Private tasks cannot have dependencies.
			</div>
			<div class="normal_div" id="depent_normal">
				<?php 
				
					$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
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
					<?php 
					if(isset($task['general']['task_due_date']) && $task['general']['task_due_date'] != '0000-00-00'){
						$due_dt = date('Y-m-d',strtotime($task['general']['task_due_date']));
					} else {
						$due_dt = '';
					} 
									
					$date_1 = 	date($default_format,strtotime($due_dt));					
					?>
					<script type="text/javascript">
					$(function(){
						
					 $('.scroll_dependency').slimScroll({
					 	  color: '#17A3E9',
						  wheelStep: 20,
						  height:230
					  });
					  	
					});
					</script> 
					
					<script type="text/javascript">
						$(document).ready(function(){
						 
						 //For all datepicker Sj//
						 
						 $('.chos').chosen();
					    var $elem = $('.chzn-container');

					    $(".chzn-container").toggle(function () { 
					        $elem.attr('style', $elem.attr('style') + '; ' + 'position: absolute !important');
					    }, function () {
					        $elem.attr('style', $elem.attr('style') + '; ' + 'position: relative !important');
					    });
						
					   $('#dependent_task_due_date_picker').datepicker({
					   		startDate: <?php echo $start_date_picker; ?>,
						  	format: '<?php echo $date_arr_java[$default_format]; ?>',
                                                        autoclose: true,
						  	 
					   });
					   
					     $('#serach_date_picker').datepicker({
                                                        startDate: -Infinity,
						  	format: '<?php echo $date_arr_java[$default_format]; ?>',
                                                        autoclose: true,
						  	
					   });
					 
				 		$('#search_date').blur(function(){
						    $("#task_name").val('');
						});
						
						
						
						
					 	
						 //End//
							$('#frm_search_dependency').validate({
								rules: {
					               "task_name": {
						                  maxlength : 50
						              }
					            },
					            //perform an AJAX post to ajax.php
					            submitHandler: function() {
					            	$.ajax({
							            type: 'post',
							            url : '<?php echo site_url("task/search_dependency"); ?>',
							            data: $('#frm_search_dependency').serialize(),
							            success: function(responseData) {
							            	$("#updated_dependencies").html(responseData);
							            },
							            error: function(responseData){
							                console.log('Ajax request not recieved!');
							            }
							        });
					            }
							});
							
							var today = new Date();
					    	var dd = today.getDate();
					    	var mm = today.getMonth()+1; //January is 0!
					
					    	var yyyy = today.getFullYear();
					    	if(dd<10){
						        dd='0'+dd
						    } 
						    if(mm<10){
						        mm='0'+mm
						    } 
						    var today_date = yyyy+'-'+mm+'-'+dd;
						    
						   	var isAfterStartDate = function(startDateStr, endDateStr) {
								if(endDateStr){
									from = startDateStr.split("-");
									f = new Date(from[0], from[1], from[2]);
									
									to = endDateStr.split("-");
									t = new Date(to[0], to[1], to[2]);
									
									//alert(endDateStr);
									//alert(new Date(endDateStr).getTime());
								//	alert(new Date(startDateStr).getTime()+"fdsf"+new Date(endDateStr).getTime());
									//if(f>=t){
										if(new Date(startDateStr).getTime() > new Date(endDateStr).getTime()){
											//alert("gfg");
										return true;
									} else {
										return false;
									}
								} else {
									return true;
								}
					            
					
					        };
							$.validator.addMethod("lessThan", function(value, element) {
								
							//if(new Date(today_date).getTime() > new Date($('#main_task_due_date').val()).getTime()){
								//alert(today_date+"----"+$('#main_task_due_date').val());
								//alert(new Date($('#main_task_due_date').val()).getTime());
								//alert(new Date(today_date).getTime());
								if(today_date>$('#main_task_due_date').val()){
								//	if(new Date(today_date).getTime() > new Date($('#main_task_due_date').val()).getTime()){
									return true;
								}
							//	return isAfterStartDate($('#main_task_due_date').val(), value);
					    	});
							$('#dependent_task_due_date').blur(function(){
							    $("#frm_add_dependency").validate();
							});
							$('#frm_add_dependency').validate({
								rules: {
					               "task_title": {
						                  required: true,
						                  maxlength : 50
						              },/*
					                "task_allocated_user_id" : {
					                	required : true
					                },*/
					                "dependent_task_due_date" : {
					                	// lessThan: true
					                }
					            },
					            messages : {
					            	"dependent_task_due_date" : {
					            		lessThan : "Must be less than main task due date."
					            	}
					            },
					            errorPlacement: function (error, element) {
					
					                if (element.attr("name") == "dependent_task_due_date" ) { // for chosen elements, need to insert the error after the chosen container
					                    error.appendTo( element.parent("div") );
					                } else {
					                    error.insertAfter(element); // for other inputs, just perform default behavior
					                }
					            },
					            
					            //perform an AJAX post to ajax.php
					            submitHandler: function() {
					            	$("#is_dependency_added").val("0");
					            	$.ajax({
							            type: 'post',
							            url : '<?php echo site_url("task/dependencies"); ?>',
							            data: $('#frm_add_dependency').serialize(),
							            success: function(responseData) {
							            	$("#updated_dependencies").html(responseData);
							            	$("#depen_task_title").val('');
							            	$("#depent_task_allocated_user_id").val('');
							            	$("#depent_task_allocated_user_id").trigger("liszt:updated"); 
							            	$("#dependent_task_due_date").val('');
							            	$("#task_status_id").val("<?php echo get_task_status_id_by_name("Not Ready");?>");
							            	$("#task_status_id").attr("disabled","disabled");
							            	$("#is_dependency_added").val("1");
							            },
							            error: function(responseData){
							                console.log('Ajax request not recieved!');
							            }
							        });
					            }
							});
							
							
							
						});
						
						function delete_dependent_task(val){
							var ans = "Are you sure, you want to delete dependency?";
                                                        $('#delete_dependent_task_'+val).confirmation({placement: 'bottom'});
							$('#delete_dependent_task_'+val).confirmation('show').on('confirmed.bs.confirmation',function(){
								$.ajax({
                                                                    type: 'post',
                                                                    url : '<?php echo site_url("task/delete_dependent_task"); ?>',
                                                                    data: {'dependent_task_id' : val, 'task_id' : $("#pre_task_id").val() },
                                                                    success: function(responseData) {
                                                                        $("#updated_dependencies").html(responseData);
                                                                        if($('#updated_dependencies tr > td').length == "1"){
                                                                                $("#task_status_id").removeAttr("disabled","disabled");
                                                                                $("#is_dependency_added").val("0");
                                                                        }
                                                                        if($("#main_"+val).length){
                                                                                $("#main_"+val).remove();	
                                                                        }
                                                                        if($("#task_"+val).length){
                                                                                $("#task_"+val).remove();	
                                                                        }
                                                                    },
                                                                    error: function(responseData){
                                                                        console.log('Ajax request not recieved!');
                                                                    }
                                                                });
						    });
						}
						
                                                function remove_task_dependency(task_id){
                                                    var ans = "Are you sure, you want to un-link dependency?";
                                                    $('#remove_task_dependency_'+task_id).confirmation({placement: 'bottom'});
							$('#remove_task_dependency_'+task_id).confirmation('show').on('confirmed.bs.confirmation',function(){
								$.ajax({
                                                                    type: 'post',
                                                                    url : '<?php echo site_url("task/remove_task_dependency"); ?>',
                                                                    data: {'dependent_task_id' : task_id, 'task_id' : $("#pre_task_id").val() },
                                                                    success: function(responseData) {
                                                                        $("#updated_dependencies").html(responseData);
                                                                        if($('#updated_dependencies tr > td').length == "1"){
                                                                                $("#task_status_id").removeAttr("disabled","disabled");
                                                                                $("#is_dependency_added").val("0");
                                                                        }
                                                                    },
                                                                    error: function(responseData){
                                                                        console.log('Ajax request not recieved!');
                                                                    }
                                                                });
						    });
                                                }
						
					</script>
		 	  <form name="frm_search_dependency" id="frm_search_dependency" action="">
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<div class="controls">
								<input type="text" id="task_name" name="task_name" class="m-wrap col-md-12 tags" placeholder="Search Tasks by entering Name">
								
							</div>
						</div>
					</div>
					
					 <div class="col-md-5">
						<div class="form-group no-margin">
							<div class="controls">
								<div  class="input-append date date-picker left_log" data-date="<?php echo date($default_format);?>" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
                                                                    <input class="m-wrap m-ctrl-medium desc" placeholder="filter tasks by due date" name="search_date" id="search_date" size="16" type="text" value="" style="width:200px;"/><span class="add-on tags"><i class="icon-calendar taskppicn"></i></span>
									<!--<a href="#" class="btn blue margin-left-5"> Search </a>--> 
									
								</div>
								
							</div>
						</div>
					</div> 
					
					<div>
						<div class="form-group no-margin">
							<div class="controls">
								<input type="hidden" name="search_task_id" id="search_task_id" value="" />
								<input type="hidden" name="dep_task_id" id="dep_task_id" value="" />
								<!--<button type="submit" class="btn blue margin-left-5" >Search</button>-->
							</div>
						</div>
					</div>
					
				</div>
			</form>	 
				 <div class="customtable table-scrollable scroll_dependency">
					<table class="table table-striped table-hover table-condensed flip-content">
					<thead class="flip-content">
					  <tr>
						<th>ID</th>
						<th>Task</th>
						<th>Allocated</th>
						<th>Due Date</th>
						<th>Status</th>
						<th>Action</th>
					  </tr>
					</thead>
					<tbody id="updated_dependencies">
						<?php  
						//print_r($task['dependencies']);die;
						if(isset($task['dependencies']) && $task['dependencies'] != ''){
							foreach($task['dependencies'] as $dependent){
								$status_name = $dependent['task_status_name'];
								?>
								
								<tr>
									<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php echo $dependent['task_id']; ?></td>
									<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php echo $dependent['task_title']; ?></td>
									<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php echo usernameById($dependent['task_allocated_user_id']); ?></td>
									<td onclick="set_new_task_data('<?php echo $dependent["task_id"];?>')"><?php if($dependent['task_due_date'] != '0000-00-00'){ echo date($site_setting_date,strtotime(str_replace(array("/"," ",","), "-", $dependent['task_due_date']))); } else { echo ''; }?></td>
									<td> <span class="label label-<?php echo strtolower(str_replace(' ', '', $status_name));?>"><?php echo $status_name;?></span> </td>
									<td>
                                                                            <a href='javascript://' class='tooltips' data-placement='top' data-original-title='Click to Un-link the task' ><i class='icon-unlink'></i></a>
                                                                            <?php if($dependent['task_owner_id'] == get_authenticateUserID()){ ?> 
										<a href="javascript://" class='tooltips' data-placement='top' data-original-title='Click to Delete the dependency task' onclick="delete_dependent_task('<?php echo $dependent['task_id']; ?>');" id="delete_dependent_task_<?php echo $dependent['task_id']; ?>"> <i class="icon-trash stngicn"></i> </a>
										<?php } ?>
									</td>
								</tr>
								<?php
							}
						} else {?>
							<tr><td colspan="6">No record available.</td></tr>
					  <?php } ?>
					</tbody>
					</table>
	   				</div>
	   		<form name="frm_add_dependency" id="frm_add_dependency" action="" >
                            <div class="row" >
					<div class="col-md-12 ">
						<div class="form-group">
							<label class="control-label" for="firstName">Quick Add </label>
							<label class="control-label" for="TaskName">Task Name<span class="required">*</span></label>
							<div class="controls">
								<input type="text" id="depen_task_title" name="task_title" class="m-wrap col-md-12" placeholder="">
							</div>
						</div>
					</div>
				</div>
				
	  			<div class="row" style="margin-top:5px">
					<div class="col-md-6 ">
						<div class="form-group">
							<label class="control-label" for="firstName">Allocated </label>
							<div class="controls" id="user_allocated_list">
								<select class="span11 m-wrap no-margin chosen width350" name="task_allocated_user_id" id="depent_task_allocated_user_id" tabindex="1">
									<option value="">Please select</option>
									<?php 
										if(isset($users) && $users != ''){
											foreach($users as $u){
												?>
												<option value="<?php echo $u->user_id;?>" > <?php echo $u->first_name.' '.$u->last_name; if($u->is_customer_user == '1')echo '(external)';?> </option>
												<?php
											}
										} 
									?>
								</select>
							</div>
						</div>
					</div>
					<!--/span-->
					 <div class="col-md-4 ">
						<div class="form-group">
							<label class="control-label" for="lastName">Due Date </label>
							<div class="controls">
								<div class="input-append date date-picker dependent_task_due_date_picker" data-date="<?php echo date($default_format);?>" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
                                                                    <input class="m-wrap m-ctrl-medium" name="dependent_task_due_date" id="dependent_task_due_date" size="16" type="text" value="" style="width:200px;"/><span class="add-on"><i class="icon-calendar taskppicn"></i></span>
								 </div>
							</div>
						</div>
					</div> 
                                        <div class="col-md-1 margin-top-20" style="margin-top:22px;">
						<input type="hidden" name="main_task_due_date" id="main_task_due_date" value="<?php echo $due_dt;?>" />
			 			<input type="hidden" name="task_id" id="pre_task_id" value="" />
						<button type="submit" class="btn btn-common-blue">Add </button>
					</div>
					<!--/span-->
				</div>	
				
			</form>
			</div>	
			
			</div>
			<div class="clearfix"></div>
			
		
	</div>
 </div>
 </div>
