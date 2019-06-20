
<?php $theme_url = base_url().getThemeName(); ?>
<!-- <script src="<?php echo $theme_url; ?>/assets/scripts/app.js?Ver=<?php echo VERSION;?>"></script> -->

<script type="text/javascript">
$(function(){
 $('.scroll_steps').slimScroll({
 	  
	  color: '#17A3E9',
	   wheelStep: 20,
	   height:370
  });

});
</script> 

<script>
	$(document).ready(function(){
		
		 App.init();
		 
		
		 $("#add_step_title").on("keypress", function(e){
			 if( e.keyCode === 13 ) {
				e.preventDefault();
				$("#add_task_step").trigger("click");
			}
		 });
		
		 
		 
		$(".up,.down").click(function(){
	        var row = $(this).parents("tr:first");
	        if ($(this).is(".up")) {
	            row.insertBefore(row.prev());
	        } else {
	            row.insertAfter(row.next());
	        } 
	    });
	    
	   
	    $("#add_task_step").click(function(){ 
	    	var step_title = $("#add_step_title").val();
                var frequency_option = $("#recurring_type").val();
                var id= $("#step_task_id").val();
                var data=jQuery.parseJSON($("#task_data_" + id).val());
	    	if(step_title == ''){
	    		$("#alertify").show();
	    		alertify.alert('Please enter add step');
	    		return false;
	    	} else {
                    if(frequency_option == 'occurrence')
                    { 
	    		$.ajax({
		            type: 'post',
		            url : '<?php echo site_url("task/steps_occurrence"); ?>',
		            data: { post_data: data,step_title : step_title, task_id : $("#step_task_id").val(), task_step_id : $("#task_step_id").val(),option:frequency_option},
		            async : false,
		            success: function(responseData){ 
                                
                                responseData = jQuery.parseJSON(responseData);
                                $("#task_id").val(responseData['task_id']), $("#allocation_task_id").val(responseData['task_id']), $("#pre_task_id").val(responseData['task_id']), $("#step_task_id").val(responseData['task_id']), $("#files_task_id").val(responseData['task_id']), $("#link_files_task_id").val(responseData['task_id']), $("#comment_task_id").val(responseData['task_id']), $("#freq_task_id").val(responseData['task_id']), $("#search_task_id").val(responseData['task_id']);
                                $("#step_task_id").val(responseData['task_id']);
                                $("#task_data_" + responseData['task_id']).val(responseData.task_data);
                                var step_str = '';
		            	var i = 0;
						$.map(responseData.task.steps, function(step){ 
							
							var checked_str = '';
							if(step.is_completed == '1'){
								checked_str = "checked='checked'";
							}
							step_str += '<tr>';
							step_str += '<script type="text/javascript">$(document).ready(function(){$("#step_title_'+step.task_step_id+'").editable({url: "'+SIDE_URL+'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});';
							step_str += '<\/script>';
							step_str += '<td><label class="checkbox newcheckbox_task"><input type="checkbox" name="is_completed[]" id="'+step.task_step_id+'" value="1" '+checked_str+' /></label></td>';
							step_str += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_'+step.task_step_id+'" data-type="text" data-pk="1" data-original-title="'+step.step_title+'">'+step.step_title+'</a></td>';
							step_str += '<td>';
								if(step.step_added_by == LOG_USER_ID){
									step_str += '<a href="javascript:;" onclick="delete_step(\''+step.task_step_id+'\')" id="delete_step_'+step.task_step_id+'"> <i class="icon-trash taskppstp"></i> </a>';
								}  
							step_str += '<a href="javascript:;" class="up"><i class="icon-arrow-down taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>';
							step_str += '<input type="hidden" name="step_title[]" value="'+step.step_title+'" />';
							step_str += '<input type="hidden" name="seq[]" value="'+step.step_sequence+'" />';
							step_str += '<input type="hidden" name="ids[]" value="'+step.task_step_id+'" />';
							step_str += '<input type="hidden" name="added_by[]" value="'+step.step_added_by+'" />';
							step_str += '</tr>';
							i++;
						});
						if(step_str){
							step_str += '<input type="hidden" name="total" id="total" value="'+i+'" />';
						}
						
						$("#total").val(i);
						
						$("#updated_steps").html(step_str);
						App.init();
						
						$(".up,.down").click(function(){
					        var row = $(this).parents("tr:first");
					        if ($(this).is(".up")) {
					            row.insertBefore(row.prev());
					            $("#frm_steps").submit();
					        } else {
					            row.insertAfter(row.next());
					            $("#frm_steps").submit();
					        } 
					    });
						
						 $("input[name='is_completed[]']").click(function(){
					    	$("#frm_steps").submit();
					    });
						
		            	$("#add_step_title").val('');
		            	$("#task_step_id").val('');
		            	$("#add_step_title").blur(function(){$("#alertify").hide();$("#alertify-cover").css("position","relative");});
		            	 $.ajax({
                                type: "post",
                                url: SIDEURL + "calendar/set_weekly_update_task",
                                data: {
                                    task_id: responseData['task_id'],
                                    start_date: $("#week_start_date").val(),
                                    end_date: $("#week_end_date").val(),
                                    action: $("#week_action").val(),
                                    active_menu: ACTIVE_MENU,
                                    color_menu: $("#task_color_menu").val()
                                },
                                success: function(b) { 
                                    $( "#main_"+id ).replaceWith(b);
                                }
                            });
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
		        });
                       
	    	}
                else
                {    
                        $.ajax({
		            type: 'post',
		            url : '<?php echo site_url("task/steps"); ?>',
		            data: {step_title : step_title, task_id : $("#step_task_id").val(), task_step_id : $("#task_step_id").val(),option:frequency_option},
		            async : false,
		            success: function(responseData){
		            	responseData = jQuery.parseJSON(responseData);
		            	var step_str = '';
		            	var i = 0;
						$.map(responseData.task.steps, function(step){ 
							
							var checked_str = '';
							if(step.is_completed == '1'){
								checked_str = "checked='checked'";
							}
							step_str += '<tr>';
							step_str += '<script type="text/javascript">$(document).ready(function(){$("#step_title_'+step.task_step_id+'").editable({url: "'+SIDE_URL+'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});';
							step_str += '<\/script>';
							step_str += '<td><label class="checkbox newcheckbox_task"><input type="checkbox" name="is_completed[]" id="'+step.task_step_id+'" value="1" '+checked_str+' /></label></td>';
							step_str += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_'+step.task_step_id+'" data-type="text" data-pk="1" data-original-title="'+step.step_title+'">'+step.step_title+'</a></td>';
							step_str += '<td>';
								if(step.step_added_by == LOG_USER_ID){
									step_str += '<a href="javascript:;" onclick="delete_step(\''+step.task_step_id+'\')" id="delete_step_'+step.task_step_id+'"> <i class="icon-trash taskppstp"></i> </a>';
								}  
							step_str += '<a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>';
							step_str += '<input type="hidden" name="step_title[]" value="'+step.step_title+'" />';
							step_str += '<input type="hidden" name="seq[]" value="'+step.step_sequence+'" />';
							step_str += '<input type="hidden" name="ids[]" value="'+step.task_step_id+'" />';
							step_str += '<input type="hidden" name="added_by[]" value="'+step.step_added_by+'" />';
							step_str += '</tr>';
							i++;
						});
						if(step_str){
							step_str += '<input type="hidden" name="total" id="total" value="'+i+'" />';
						}
						
						$("#total").val(i);
						
						$("#updated_steps").html(step_str);
						App.init();
						
						$(".up,.down").click(function(){
					        var row = $(this).parents("tr:first");
					        if ($(this).is(".up")) {
					            row.insertBefore(row.prev());
					            $("#frm_steps").submit();
					        } else {
					            row.insertAfter(row.next());
					            $("#frm_steps").submit();
					        } 
					    });
						
						 $("input[name='is_completed[]']").click(function(){
					    	$("#frm_steps").submit();
					    });
						
		            	$("#add_step_title").val('');
		            	$("#task_step_id").val('');
		            	$("#add_step_title").blur(function(){$("#alertify").hide();$("#alertify-cover").css("position","relative");});
		            	
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
		        });
                }
             }
	    	
	    });
	    
	    $("#frm_steps").submit(function(){
	    	var str = $(this).serialize();
                var frequency_option = $("#recurring_type").val();
	    	var arr_check = [];
	    	var arr_uncheck = [];
                var id= $("#step_task_id").val();
                var data=jQuery.parseJSON($("#task_data_" + id).val());
	    	var test = document.getElementsByName('is_completed[]');
	    	for (var i=0, n=test.length;i<n;i++) {
			  	if (test[i].checked) 
			  	{
			  		arr_check.push($(test[i]).attr("id"));
			  	} else {
			  		arr_uncheck.push($(test[i]).attr("id"));
			  	}
			}
                if(frequency_option == 'occurrence'){
                     $.ajax({
                        type: 'post',
                        url : '<?php echo site_url("task/update_step_status_completed"); ?>',
                        data: {str:str,check_array:arr_check,uncheck_arry:arr_uncheck,post_data: data},
                        async : false,
                        success: function(responseData) {
                            responseData = jQuery.parseJSON(responseData); 
                            $("#step_task_id").val(responseData['task_id']);
		            	var step_str = '';
		            	var i = 0;
						$.map(responseData.task.steps, function(step){ 
							
							var checked_str = '';
							if(step.is_completed == '1'){
								checked_str = "checked='checked'";
							}
							step_str += '<tr>';
							step_str += '<script type="text/javascript">$(document).ready(function(){$("#step_title_'+step.task_step_id+'").editable({url: "'+SIDE_URL+'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});';
							step_str += '<\/script>';
							step_str += '<td><label class="checkbox newcheckbox_task"><input type="checkbox" name="is_completed[]" id="'+step.task_step_id+'" value="1" '+checked_str+' /></label></td>';
							step_str += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_'+step.task_step_id+'" data-type="text" data-pk="1" data-original-title="'+step.step_title+'">'+step.step_title+'</a></td>';
							step_str += '<td>';
								if(step.step_added_by == LOG_USER_ID){
									step_str += '<a href="javascript:;" onclick="delete_step(\''+step.task_step_id+'\')" id="delete_step_'+step.task_step_id+'"> <i class="icon-trash taskppstp"></i> </a>';
								}  
							step_str += '<a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>';
							step_str += '<input type="hidden" name="step_title[]" value="'+step.step_title+'" />';
							step_str += '<input type="hidden" name="seq[]" value="'+step.step_sequence+'" />';
							step_str += '<input type="hidden" name="ids[]" value="'+step.task_step_id+'" />';
							step_str += '<input type="hidden" name="added_by[]" value="'+step.step_added_by+'" />';
							step_str += '</tr>';
							i++;
						});
						if(step_str){
							step_str += '<input type="hidden" name="total" id="total" value="'+i+'" />';
						}
						
						$("#total").val(i);
						
						$("#updated_steps").html(step_str);
						App.init();
						
						$(".up,.down").click(function(){
					        var row = $(this).parents("tr:first");
					        if ($(this).is(".up")) {
					            row.insertBefore(row.prev());
					            $("#frm_steps").submit();
					        } else {
					            row.insertAfter(row.next());
					            $("#frm_steps").submit();
					        } 
					    });
						
						 $("input[name='is_completed[]']").click(function(){
					    	$("#frm_steps").submit();
					    });
                                           $.ajax({
                                                type: "post",
                                                url: SIDEURL + "calendar/set_weekly_update_task",
                                                data: {
                                                    task_id: responseData['task_id'],
                                                    start_date: $("#week_start_date").val(),
                                                    end_date: $("#week_end_date").val(),
                                                    action: $("#week_action").val(),
                                                    active_menu: ACTIVE_MENU,
                                                    calendar_color_menu: $("#task_color_menu").val()
                                                },
                                                success: function(b) { 
                                                    $( "#main_"+id ).replaceWith(b);
                                                }
                                            }); 
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	                $('#dvLoading').fadeOut('slow');
	            }
                    });
                }else{
                    
                    $.ajax({
                        type: 'post',
                        url : '<?php echo site_url("task/set_task_seq"); ?>',
                        data: {str:str,check_array:arr_check,uncheck_arry:arr_uncheck,post_data: data},
                        async : false,
                        success: function(responseData) {
                            responseData = jQuery.parseJSON(responseData);
		            	var step_str = '';
		            	var i = 0;
						$.map(responseData.task.steps, function(step){ 
							
							var checked_str = '';
							if(step.is_completed == '1'){
								checked_str = "checked='checked'";
							}
							step_str += '<tr>';
							step_str += '<script type="text/javascript">$(document).ready(function(){$("#step_title_'+step.task_step_id+'").editable({url: "'+SIDE_URL+'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});';
							step_str += '<\/script>';
							step_str += '<td><label class="checkbox newcheckbox_task"><input type="checkbox" name="is_completed[]" id="'+step.task_step_id+'" value="1" '+checked_str+' /></label></td>';
							step_str += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_'+step.task_step_id+'" data-type="text" data-pk="1" data-original-title="'+step.step_title+'">'+step.step_title+'</a></td>';
							step_str += '<td>';
								if(step.step_added_by == LOG_USER_ID){
									step_str += '<a href="javascript:;" onclick="delete_step(\''+step.task_step_id+'\')" id="delete_step_'+step.task_step_id+'" > <i class="icon-trash taskppstp"></i> </a>';
								}  
							step_str += '<a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>';
							step_str += '<input type="hidden" name="step_title[]" value="'+step.step_title+'" />';
							step_str += '<input type="hidden" name="seq[]" value="'+step.step_sequence+'" />';
							step_str += '<input type="hidden" name="ids[]" value="'+step.task_step_id+'" />';
							step_str += '<input type="hidden" name="added_by[]" value="'+step.step_added_by+'" />';
							step_str += '</tr>';
							i++;
						});
						if(step_str){
							step_str += '<input type="hidden" name="total" id="total" value="'+i+'" />';
						}
						
						$("#total").val(i);
						
						$("#updated_steps").html(step_str);
						App.init();
						
						$(".up,.down").click(function(){
					        var row = $(this).parents("tr:first");
					        if ($(this).is(".up")) {
					            row.insertBefore(row.prev());
					            $("#frm_steps").submit();
					        } else {
					            row.insertAfter(row.next());
					            $("#frm_steps").submit();
					        } 
					    });
						
						 $("input[name='is_completed[]']").click(function(){
					    	$("#frm_steps").submit();
					    });
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	                $('#dvLoading').fadeOut('slow');
	            }
	        });
                }
	        return false;
		});
		
	});
	
	
	function delete_step(id){
		var ans = "Are you sure, you want to delete this step?";
		$('#delete_step_'+id).confirmation('show').on('confirmed.bs.confirmation',function(){
			$.ajax({
	            type: 'post',
	            url : '<?php echo site_url("task/delete_step"); ?>',
	            data: {task_step_id : id, task_id : $("#step_task_id").val()},
	            async : false,
	            success: function(responseData) {
	            	responseData = jQuery.parseJSON(responseData);
		            	var step_str = '';
		            	var i = 0;
						$.map(responseData.task.steps, function(step){ 
							
							var checked_str = '';
							if(step.is_completed == '1'){
								checked_str = "checked='checked'";
							}
							step_str += '<tr>';
							step_str += '<script type="text/javascript">$(document).ready(function(){$("#step_title_'+step.task_step_id+'").editable({url: "'+SIDE_URL+'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});';
							step_str += '<\/script>';
							step_str += '<td><label class="checkbox newcheckbox_task"><input type="checkbox" name="is_completed[]" id="'+step.task_step_id+'" value="1" '+checked_str+' /></label></td>';
							step_str += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_'+step.task_step_id+'" data-type="text" data-pk="1" data-original-title="'+step.step_title+'">'+step.step_title+'</a></td>';
							step_str += '<td>';
								if(step.step_added_by == LOG_USER_ID){
									step_str += '<a href="javascript:;" onclick="delete_step(\''+step.task_step_id+'\')" id="delete_step_'+step.task_step_id+'"> <i class="icon-trash taskppstp"></i> </a>';
								}  
							step_str += '<a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>';
							step_str += '<input type="hidden" name="step_title[]" value="'+step.step_title+'" />';
							step_str += '<input type="hidden" name="seq[]" value="'+step.step_sequence+'" />';
							step_str += '<input type="hidden" name="ids[]" value="'+step.task_step_id+'" />';
							step_str += '<input type="hidden" name="added_by[]" value="'+step.step_added_by+'" />';
							step_str += '</tr>';
							i++;
						});
						if(step_str){
							step_str += '<input type="hidden" name="total" id="total" value="'+i+'" />';
						}
						
						$("#total").val(i);
						
						$("#updated_steps").html(step_str);
						App.init();
						
						$(".up,.down").click(function(){
					        var row = $(this).parents("tr:first");
					        if ($(this).is(".up")) {
					            row.insertBefore(row.prev());
					            $("#frm_steps").submit();
					        } else {
					            row.insertAfter(row.next());
					            $("#frm_steps").submit();
					        } 
					    });
						
						 $("input[name='is_completed[]']").click(function(){
					    	$("#frm_steps").submit();
					    });
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	            }
	        });
	 	});
	}
	
	function set_completed(id){
		$.ajax({
            type: 'post',
            url : '<?php echo site_url("task/update_step"); ?>',
            data: {task_step_id : id, task_id : $("#step_task_id").val()},
            async : false,
            success: function(responseData) {
        		responseData = jQuery.parseJSON(responseData);
	            	var step_str = '';
	            	var i = 0;
					$.map(responseData.task.steps, function(step){ 
						
						var checked_str = '';
						if(step.is_completed == '1'){
							checked_str = "checked='checked'";
						}
						step_str += '<tr>';
						step_str += '<script type="text/javascript">$(document).ready(function(){$("#step_title_'+step.task_step_id+'").editable({url: "'+SIDE_URL+'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});';
						step_str += '<\/script>';
						step_str += '<td><label class="checkbox newcheckbox_task"><input type="checkbox" name="is_completed[]" id="'+step.task_step_id+'" value="1" '+checked_str+' /></label></td>';
						step_str += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_'+step.task_step_id+'" data-type="text" data-pk="1" data-original-title="'+step.step_title+'">'+step.step_title+'</a></td>';
						step_str += '<td>';
							if(step.step_added_by == LOG_USER_ID){
								step_str += '<a href="javascript:;" onclick="delete_step(\''+step.task_step_id+'\')" id="delete_step_'+step.task_step_id+'"> <i class="icon-trash taskppstp"></i> </a>';
							}  
						step_str += '<a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>';
						step_str += '<input type="hidden" name="step_title[]" value="'+step.step_title+'" />';
						step_str += '<input type="hidden" name="seq[]" value="'+step.step_sequence+'" />';
						step_str += '<input type="hidden" name="ids[]" value="'+step.task_step_id+'" />';
						step_str += '<input type="hidden" name="added_by[]" value="'+step.step_added_by+'" />';
						step_str += '</tr>';
						i++;
					});
					if(step_str){
						step_str += '<input type="hidden" name="total" id="total" value="'+i+'" />';
					}
					
					$("#total").val(i);
					
					$("#updated_steps").html(step_str);
					App.init();
					
					$(".up,.down").click(function(){
				        var row = $(this).parents("tr:first");
				        if ($(this).is(".up")) {
				            row.insertBefore(row.prev());
				            $("#frm_steps").submit();
				        } else {
				            row.insertAfter(row.next());
				            $("#frm_steps").submit();
				        } 
				    });
					
					 $("input[name='is_completed[]']").click(function(){
				    	$("#frm_steps").submit();
				    });
            },
            error: function(responseData){
                console.log('Ajax request not recieved!');
            }
        });
	}
</script>



<div class="portlet">
									 
	<div class="portlet-body form">
			 <div class="horizontal-form">
			 	 <form name="frm_steps" id="frm_steps" >
			 	<!-- ***************** -->
			<div class="popuphight">
			<!-- ***************** -->	
			<div class="no_task_msg" style="display: none;">
				<div class='task_not_found_msg'><span>Please save the task before adding this.</span></div>
			</div>
			<div class="normal_div">
				
			 	    
					 <div class="customtable table-scrollable scroll_steps">
					 	
						<table class="table table-striped table-hover table-condensed flip-content">
						<thead class="flip-content">
						  <tr>
							<th width="4%">&nbsp;</th>
							<th>Steps</th>
							<th width="22%">Action</th>
							 </tr>
						</thead>
						<tbody id="updated_steps">
							<?php 
							$i = 0;
							if(isset($task['steps']) && $task['steps'] != ''){
								foreach($task['steps'] as $steps){
									?>
									<tr>
										<script type="text/javascript">
											$(document).ready(function(){
												$('#step_title_<?php echo $steps["task_step_id"]; ?>').editable({
											            url: '<?php echo site_url("task/update_steps");?>',
											            type: 'post',
											            pk: 1,
											            mode: 'inline',
											            showbuttons: true,
											            validate: function (value) {
											            	
											              	if ($.trim(value) == ''){ return 'This field is required'};
											              	
											            },
											            success : function(responseData){
											            	$("#updated_steps").html(responseData);
											            }
											            
											        });
											});
										</script>
										<td>
											<label class="checkbox">
												<input type="checkbox" name="is_completed[]" id="<?php echo $steps["task_step_id"];?>" value="1" <?php if($steps['is_completed'] == '1'){ echo 'checked="checked"'; } ?> /></label> 
											
											</td>
										<td><a href="javascript:void(0)" class="txt-style" id="step_title_<?php echo $steps["task_step_id"]; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $steps['step_title'];?>"><?php echo $steps['step_title'];?></a></td>
										<td> 
											 <?php if($steps['step_added_by'] == get_authenticateUserID()){ ?> 
											 <a href="javascript:void(0)" onclick="delete_step('<?php echo $steps['task_step_id'];?>')" id="delete_step_<?php echo $steps['task_step_id'];?>"> <i class="icon-trash taskppstp"></i> </a>
											 <?php } ?>  
											 <a href="javascript:void(0)" class="up upup"><i class="icon-arrow-up taskppstp"></i> up</a> 
											 <a href="javascript:void(0)" class="down dfown"><i class="icon-arrow-down taskppstp">down</i></a> 
										</td>
										 <input type="hidden" name="step_title[]" value="<?php echo $steps['step_title'];?>" />
										<input type="hidden" name="seq[]" value="<?php echo $steps['step_sequence'];?>" />
										<input type="hidden" name="ids[]" value="<?php echo $steps['task_step_id'];?>" />
										<input type="hidden" name="added_by[]" value="<?php echo $steps['step_added_by'];?>" />
									</tr>
									
									<?php
									$i++;
								}
							} else { ?>
								<tr><td colspan="3">No Record Available.</td></tr>
						  <?php } 
							 ?>
							 <input type="hidden" name="total" id="total" value="<?php echo $i; ?>" />
						</tbody>
						</table>
						
		   				</div>
		   			
					  <div class="row">
							<div class="col-md-12 ">
								<div class="form-group">
									<label class="control-label" for="firstName">Add Step </label>
									<div class="controls relative-position">
										<input type="text" name="add_step_title" id="add_step_title" class="m-wrap col-md-5" placeholder="" value="">
										<input type="hidden" name="task_id" id="step_task_id" value="" />
										<input type="hidden" id="task_step_id" value="" />
										<a href="javascript://" id="add_task_step" class="btn btn-common-blue margin-left-5"> Add </a>
										<span class="input-load" id="add_step_title_loading"></span>
									</div>
								</div>
							</div>
							 
						</div>
						
						
					
					</div>
					</div>
					<div class="clearfix"></div>
					
		 	
		</form>
   
</div>
</div>
</div>
