<?php 
$completed_id = $this->config->item('completed_id');
$user_colors = $color_codes;

$company_flags = $this->config->item('company_flags');
$actaul_time_on = '0';
$allow_past_task = "1";
if($company_flags){
	$actaul_time_on = $company_flags['actual_time_on'];
	$allow_past_task = $company_flags['allow_past_task'];
}
$user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id'));

?>

<link href="<?php echo base_url(); ?>/calender4/style/calender_style.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url().getThemename(); ?>/assets/js/monthly-calendar-popover.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>


<script type="text/javascript">

$(function(){
	popover();
   
});
</script> 
<script>
	$(document).ready(function(){
		App.init();
		$("#current_page").val('FiveWeekView');
		$('.scroll').slimScroll({
			color: '#17A3E9',
			height : '120px',
	 	    wheelStep: 100,
	 	    
	 	});
	 	$(".full_task div").addClass("before_timer");
	 	
	});
	function opendeletefiveweek(task_id,master_task_id,task_due_date,date){
		$("#delete_series span").removeClass("checked");
		$("#delete_ocuurence span").removeClass("checked");
		$("#delete_series").attr("onclick","delete_rightClick_taskfiveweek('"+master_task_id+"','"+task_due_date+"','"+date+"','series','"+task_id+"')");
		$("#delete_ocuurence").attr("onclick","delete_rightClick_taskfiveweek('"+task_id+"','"+task_due_date+"','"+date+"')");
		$("#delete_task").modal("show");
	}
	
	function delete_rightClick_taskfiveweek(id,task_due_date,date,from,task_id){
		
		var from = from || 1;
		var task_id1 = task_id || id;
		
		var orig_data = $('#task_data_'+id).val();
		
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/delete_task");?>',
			data : { task_id : id, task_data :orig_data,  due_date : task_due_date, year:$("#year").val(),month:$("#month").val(),from:from},
			success : function(data){
				$("#task_"+task_id1).remove();
				
				var came_from_estimate = get_minutes($("#estimate_time_"+date).html());
				var cam_capacity = $("#capacity_time_"+date).html();
				var h_index = cam_capacity.indexOf("h");
				var cam_capacity_time = cam_capacity.substr(0,h_index);
	      		
	      		var scope_time = $("#task_est_"+task_id1).html();
	        	if(scope_time){
	        		var scope_time_estimate = get_minutes(scope_time);
	        	} else {
	        		var scope_time_estimate = '0';
	        	}
				
				var task_type = $("#task_type_"+id).val();
				
				if($("#"+date+" .taskbox").length == 0) {
					$("#task_list_"+date).remove();
					$("#task_info_"+date).remove();
				} else {
					
					if(task_type){
						task_type1 = task_type.split(",");
						if(task_type1[0] == "1"){
							var came_from_complete = $("#completed_"+date).html();
							if(came_from_complete>0){
								$("#completed_"+date).html(parseInt(came_from_complete)-1);
							}
							if(task_type1[1]!="undefined"){
								var came_from_schedule = $("#scheduled_"+date).html();
								if(came_from_schedule>0){
									$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
								}
							}
							if(task_type1[2]!="undefined"){
								var came_from_due = $("#due_"+date).html();
								if(came_from_due>0){
									$("#due_"+date).html(parseInt(came_from_due)-1);
								}
							}
						} else if(task_type1[0] == "2"){
							var came_from_overdued = $("#overdued_"+date).html();
							if(came_from_overdued>0){
								$("#overdued_"+date).html(parseInt(came_from_overdued)-1);
							}
							if(task_type1[1]!="undefined"){
								var came_from_schedule = $("#scheduled_"+date).html();
								if(came_from_schedule>0){
									$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
								}
							}
						} else {
							if(task_type1[0] == "3" ){
								var came_from_schedule = $("#scheduled_"+date).html();
								if(came_from_schedule>0){
									$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
								}
							}
							if(task_type1[1]!="undefined"){
								var came_from_due = $("#due_"+date).html();
								if(came_from_due>0){
									$("#due_"+date).html(parseInt(came_from_due)-1);
								}
							} 
						}
					}
					
					var came_min = parseInt(came_from_estimate)-parseInt(scope_time_estimate);
					var came_estimate = hoursminutes(came_min);
					$("#estimate_time_"+date).html(came_estimate);
					
					$("#estimate_time_"+date).removeAttr('class');
					if(came_min>(cam_capacity_time*60)){
						$("#estimate_time_"+date).attr('class','commonlabel redlabel');
					} else {
						$("#estimate_time_"+date).attr('class','commonlabel');
					}
					
					
				}
				
				
				if($("#"+date+" .taskbox").length == 0) {
					$("#task_list_"+date).remove();
					$("#task_info_"+date).remove();
				}
				if(from == "series"){
					var Length_task = parseInt($(".month_master_"+id).length);
					
					for(i=0;i<Length_task;i++){
						
						var parent_div_id = $(".month_master_"+id).parent('div').attr('id');
						var task_id2 = $(".month_master_"+id).attr('id');
						$("#"+task_id2).remove();
						
						var came_from_estimate = get_minutes($("#estimate_time_"+parent_div_id).html());
						var cam_capacity = $("#capacity_time_"+parent_div_id).html();
						var h_index = cam_capacity.indexOf("h");
						var cam_capacity_time = cam_capacity.substr(0,h_index);
			      		
			      		var scope_time = $("#task_est_"+id).html();
			        	if(scope_time){
			        		var scope_time_estimate = get_minutes(scope_time);
			        	} else {
			        		var scope_time_estimate = '0';
			        	}
						
						var task_type = $("#task_type_"+id).val();
						
						if($("#"+parent_div_id+" .taskbox").length == 0) {
							$("#task_list_"+parent_div_id).remove();
							$("#task_info_"+parent_div_id).remove();
						} else {
							
							if(task_type){
								task_type1 = task_type.split(",");
								if(task_type1[0] == "1"){
									var came_from_complete = $("#completed_"+parent_div_id).html();
									if(came_from_complete>0){
										$("#completed_"+parent_div_id).html(parseInt(came_from_complete)-1);
									}
									if(task_type1[1]!="undefined"){
										var came_from_schedule = $("#scheduled_"+parent_div_id).html();
										if(came_from_schedule>0){
											$("#scheduled_"+parent_div_id).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[2]!="undefined"){
										var came_from_due = $("#due_"+parent_div_id).html();
										if(came_from_due>0){
											$("#due_"+parent_div_id).html(parseInt(came_from_due)-1);
										}
									}
								} else if(task_type1[0] == "2"){
									var came_from_overdued = $("#overdued_"+parent_div_id).html();
									if(came_from_overdued>0){
										$("#overdued_"+parent_div_id).html(parseInt(came_from_overdued)-1);
									}
									if(task_type1[1]!="undefined"){
										var came_from_schedule = $("#scheduled_"+parent_div_id).html();
										if(came_from_schedule>0){
											$("#scheduled_"+parent_div_id).html(parseInt(came_from_schedule)-1);
										}
									}
								} else {
									if(task_type1[0] == "3" ){
										var came_from_schedule = $("#scheduled_"+parent_div_id).html();
										if(came_from_schedule>0){
											$("#scheduled_"+parent_div_id).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[1]!="undefined"){
										var came_from_due = $("#due_"+parent_div_id).html();
										if(came_from_due>0){
											$("#due_"+parent_div_id).html(parseInt(came_from_due)-1);
										}
									} 
								}
							}
							
							var came_min = parseInt(came_from_estimate)-parseInt(scope_time_estimate);
							var came_estimate = hoursminutes(came_min);
							$("#estimate_time_"+parent_div_id).html(came_estimate);
							
							$("#estimate_time_"+parent_div_id).removeAttr('class');
							if(came_min>(cam_capacity_time*60)){
								$("#estimate_time_"+parent_div_id).attr('class','commonlabel redlabel');
							} else {
								$("#estimate_time_"+parent_div_id).attr('class','commonlabel');
							}
							
							
						}
						
					}
				
				}
				$("#delete_task").modal("hide");
				//$('#dvLoading').fadeOut('slow');
			}
		});
	}
	
	function set_task_color_fiveweek(task_id,user_color_id,date){
		var scope_id = task_id;
		scope_id = scope_id.replace('task_', '');
		var orig_data = $('#task_data_'+scope_id).val();
		//$('#dvLoading').fadeIn('slow');
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/set_task_color");?>',
			data : { color_id : user_color_id, task_id : task_id,task_data : orig_data  },
			success : function(responseData){
				if(task_id.indexOf('child')<0){
					
					var responseData = jQuery.parseJSON(responseData);
					if(($("#cal_user_color_id").val() == user_color_id) || ($("#cal_user_color_id").val() == "0")){
						$("#task_"+task_id).css("background-color",responseData.color_code);
						$("#task_"+task_id).css("border","1px solid "+responseData.outside_color_code);
					} else {
						var came_from_estimate = get_minutes($("#estimate_time_"+date).html());
						var cam_capacity = $("#capacity_time_"+date).html();
	
						var h_index = cam_capacity.indexOf("h");
						var cam_capacity_time = cam_capacity.substr(0,h_index);
			      		
			      		var scope_time = $("#task_est_"+task_id).html();
			        	if(scope_time){
			        		var scope_time_estimate = get_minutes(scope_time);
			        	} else {
			        		var scope_time_estimate = '0';
			        	}
			        	
						var task_type = $("#task_type_"+task_id).val();
						
						$("#task_"+task_id).remove();
						
						if($("#"+date+" .taskbox").length == 0) {
							$("#task_list_"+date).remove();
							$("#task_info_"+date).remove();
						} else {
							
							if(task_type){
								task_type1 = task_type.split(",");
								if(task_type1[0] == "1"){
									var came_from_complete = $("#completed_"+date).html();
									if(came_from_complete>0){
										$("#completed_"+date).html(parseInt(came_from_complete)-1);
									}
									if(task_type1[1]!=undefined){
										var came_from_schedule = $("#scheduled_"+date).html();
										if(came_from_schedule>0){
											$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[2]!=undefined){
										var came_from_due = $("#due_"+date).html();
										if(came_from_due>0){
											$("#due_"+date).html(parseInt(came_from_due)-1);
										}
									}
								} else if(task_type1[0] == "2"){
									var came_from_overdued = $("#overdued_"+date).html();
									if(came_from_overdued>0){
										$("#overdued_"+date).html(parseInt(came_from_overdued)-1);
									}
									if(task_type1[1]!=undefined){
										var came_from_schedule = $("#scheduled_"+date).html();
										if(came_from_schedule>0){
											$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
										}
									}
								} else {
									if(task_type1[0] == "3" ){
										var came_from_schedule = $("#scheduled_"+date).html();
										if(came_from_schedule>0){
											$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[1]!=undefined){
										var came_from_due = $("#due_"+date).html();
										if(came_from_due>0){
											$("#due_"+date).html(parseInt(came_from_due)-1);
										}
									} 
								}
							}
							
							
							var came_min = parseInt(came_from_estimate)-parseInt(scope_time_estimate);
							var came_estimate = hoursminutes(came_min);
							$("#estimate_time_"+date).html(came_estimate);
							
							$("#estimate_time_"+date).removeAttr('class');
							if(came_min>(cam_capacity_time*60)){
								$("#estimate_time_"+date).attr('class','commonlabel redlabel');
							} else {
								$("#estimate_time_"+date).attr('class','commonlabel');
							}
						}
					}
				} else {
					$("#task_"+task_id).replaceWith(responseData);
				}
			}
		});
	}
	
	function move_task_fiveweek(selected_date,locked_due_date,task_id,task_due_date){
		function pad(s) { return (s < 10) ? '0' + s : s; }
		var d = new Date(selected_date);
		var sel_date = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
		
		var is_locked = locked_due_date;
		if(is_locked == "1"){
			var droppd_dt = [d.getFullYear(), pad(d.getMonth()+1), pad(d.getDate())].join('-');
			if( (new Date(droppd_dt).getTime() > new Date(task_due_date).getTime()))
			{
				alertify.alert("Sorry, you can only move the task into prior or equal due date");
	      		return false;
			}
		}
		//$('#dvLoading').fadeIn('slow');
		
		var orig_data = $('#task_data_'+task_id).val();
		
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/move_task");?>',
			data : {task_id : task_id,task_data : orig_data, due_date : task_due_date, sel_date : sel_date,redirect : '<?php echo $from_page;?>', from_redirect : "fiveweek"},
			success : function(responsedata){
				<?php if($from_page == 'weekView' || $from_page == "NextFiveDayView"){ ?>
					$("#sjcalendar").html(responsedata);
				<?php } else { ?>
					$("#calendar").html(responsedata);
				<?php } ?> 
				//$('#dvLoading').fadeOut('slow');
			}
		});
	}
	
	function rightClickDeleteFiveWeek(task_id,task_due_date,date){
		var ans = "Are you sure, you want to delete this task?";
		alertify.confirm(ans,function(r){
			if (r == true) {
				var orig_data = $('#task_data_'+task_id).val();
				$.ajax({
					type : 'post',
					url : '<?php echo site_url("calendar/delete_task");?>',
					data : { task_id : task_id, task_data:orig_data, due_date : task_due_date, year:$("#year").val(),month:$("#month").val(), from_redirect : "fiveweek"},
					success : function(data){
						$("#task_"+task_id).remove();
						if($("#"+date+" .taskbox").length == 0) {
							$("#task_list_"+date).remove();
							$("#task_info_"+date).remove();
						}
						var came_from_estimate = get_minutes($("#estimate_time_"+date).html());
						var cam_capacity = $("#capacity_time_"+date).html();
						var h_index = cam_capacity.indexOf("h");
						var cam_capacity_time = cam_capacity.substr(0,h_index);
			      		
			      		var scope_time = $("#task_est_"+task_id).html();
			        	if(scope_time){
			        		var scope_time_estimate = get_minutes(scope_time);
			        	} else {
			        		var scope_time_estimate = '0';
			        	}
						
						var task_type = $("#task_type_"+task_id).val();
						
						if($("#"+date+" .taskbox").length == 0) {
							$("#task_list_"+date).remove();
							$("#task_info_"+date).remove();
						} else {
							
							if(task_type){
								task_type1 = task_type.split(",");
								if(task_type1[0] == "1"){
									var came_from_complete = $("#completed_"+date).html();
									if(came_from_complete>0){
										$("#completed_"+date).html(parseInt(came_from_complete)-1);
									}
									if(task_type1[1]!="undefined"){
										var came_from_schedule = $("#scheduled_"+date).html();
										if(came_from_schedule>0){
											$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[2]!="undefined"){
										var came_from_due = $("#due_"+date).html();
										if(came_from_due>0){
											$("#due_"+date).html(parseInt(came_from_due)-1);
										}
									}
								} else if(task_type1[0] == "2"){
									var came_from_overdued = $("#overdued_"+date).html();
									if(came_from_overdued>0){
										$("#overdued_"+date).html(parseInt(came_from_overdued)-1);
									}
									if(task_type1[1]!="undefined"){
										var came_from_schedule = $("#scheduled_"+date).html();
										if(came_from_schedule>0){
											$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
										}
									}
								} else {
									if(task_type1[0] == "3" ){
										var came_from_schedule = $("#scheduled_"+date).html();
										if(came_from_schedule>0){
											$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[1]!="undefined"){
										var came_from_due = $("#due_"+date).html();
										if(came_from_due>0){
											$("#due_"+date).html(parseInt(came_from_due)-1);
										}
									} 
								}
							}
							
							
							var came_min = parseInt(came_from_estimate)-parseInt(scope_time_estimate);
							var came_estimate = hoursminutes(came_min);
							$("#estimate_time_"+date).html(came_estimate);
							
							$("#estimate_time_"+date).removeAttr('class');
							if(came_min>(cam_capacity_time*60)){
								$("#estimate_time_"+date).attr('class','commonlabel redlabel');
							} else {
								$("#estimate_time_"+date).attr('class','commonlabel');
							}
						}
						
						
						//$('#dvLoading').fadeOut('slow');
					}
				});
			} else {
			    return false;
			}
		});
	}
	
	function set_priority(task_id,value){
	
		$.ajax({
			type : 'post',
			url : '<?php echo site_url('calendar/set_priority');?>',
			data : { value : value, task_id : task_id, post_data : $("#task_data_"+task_id).val(),year:$("#year").val(),month:$("#month").val()},
			success : function(data){
				if(data == "done"){
					$("#task_"+task_id).removeClass("caliconNone");
					$("#task_"+task_id).removeClass("caliconLow");
					$("#task_"+task_id).removeClass("caliconMedium");
					$("#task_"+task_id).removeClass("caliconHigh");
					if(value == "High"){
						$("#task_"+task_id).addClass("caliconHigh");
					} else if(value == "Medium"){
						$("#task_"+task_id).addClass("caliconMedium");
					} else if(value == 'Low'){
						$("#task_"+task_id).addClass("caliconLow");
					} else {
						
					}
				} else {
					$("#task_"+task_id).replaceWith(data);
				}
			}
		});
	}
	
	function rightClickSetDueDate(selected_date,task_id,date){
		function pad(s) { return (s < 10) ? '0' + s : s; }
		var d = new Date(selected_date);
		var sel_date = [d.getFullYear(), pad(d.getMonth()+1), pad(d.getDate())].join('-');
		
		
		var orig_data = $('#task_data_'+task_id).val();
		$.ajax({
			type: 'post',
			url: '<?php echo site_url("calendar/set_task_due_date");?>',
			data : {task_id : task_id, due_date : sel_date, task_data : orig_data, year:$("#year").val(),month:$("#month").val()},
			async : false,
			success : function(data){
				$("#task_"+task_id).replaceWith(data);
				var today_date = '<?php echo date("Y-m-d");?>';
				var task_type = $("#task_type_"+task_id).val();
				
				if(task_type){
					task_type1 = task_type.split(",");
					if(task_type1[0] == "1"){
						var came_from_complete = $("#completed_"+date).html();
						if(came_from_complete>0){
							$("#completed_"+date).html(parseInt(came_from_complete)-1);
						}
						if(task_type1[1]!="undefined"){
							var came_from_schedule = $("#scheduled_"+date).html();
							if(came_from_schedule>0){
								$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
							}
						}
						if(task_type1[2]!="undefined"){
							var came_from_due = $("#due_"+date).html();
							if(came_from_due>0){
								$("#due_"+date).html(parseInt(came_from_due)-1);
							}
						}
					} else if(task_type1[0] == "2"){
						var came_from_overdued = $("#overdued_"+date).html();
						if(came_from_overdued>0){
							$("#overdued_"+date).html(parseInt(came_from_overdued)-1);
							if(parseInt($("#overdued_"+date).html()) == 0){
								$("#overdued_"+date).removeClass("txtred");
							}
						}
						if(task_type1[1]!="undefined"){
							var came_from_schedule = $("#scheduled_"+date).html();
							if(came_from_schedule>0){
								$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
							}
						}
					} else {
						if(task_type1[0] == "3" ){
							var came_from_schedule = $("#scheduled_"+date).html();
							if(came_from_schedule>0){
								$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
							}
						}
						if(task_type1[1]!="undefined"){
							var came_from_due = $("#due_"+date).html();
							if(came_from_due>0){
								$("#due_"+date).html(parseInt(came_from_due)-1);
							}
						}
					}
				}
				if($("#task_status_"+task_id).val() == "Completed"){
					$("#completed_"+date).html(parseInt($("#completed_"+date).html())+1);
					$("#task_type_"+task_id).val(1);
					$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
					if($("#task_type_"+task_id).val() == "1"){
						$("#task_type_"+task_id).val(1,3);
					} else {
						$("#task_type_"+task_id).val(3);
					}
					
					if(Date.parse(sel_date) == Date.parse(today_date)){
						$("#due_"+date).html(parseInt($("#due_"+date).html())+1);
						if($("#task_type_"+task_id).val() == "1,3"){
							$("#task_type_"+task_id).val(1,3,4);
						} else {
							$("#task_type_"+task_id).val(3,4);
						}
					}
				} else if(Date.parse(sel_date)<Date.parse(today_date)){
					$("#overdued_"+date).html(parseInt($("#overdued_"+date).html())+1);
					if(parseInt($("#overdued_"+date).html()) > 0){
						$("#overdued_"+date).addClass("txtred");
					}
					$("#task_type_"+task_id).val(2);
					$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
					if($("#task_type_"+task_id).val() == "2"){
						$("#task_type_"+task_id).val(2,3);
					} else {
						$("#task_type_"+task_id).val(3);
					}
					
				} else {
					$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
					$("#task_type_"+task_id).val(3);
					if(Date.parse(sel_date)==Date.parse(today_date)){
						$("#due_"+date).html(parseInt($("#due_"+date).html())+1);
						if($("#task_type_"+task_id).val() == "3"){
							$("#task_type_"+task_id).val(3,4);
						} else {
							$("#task_type_"+task_id).val(4);
						}
					}
				}
				
			}
		});
	}
	
	function rightClickChangeStatus(task_id,status_id,status_name,date,task_due_date,dependency_status){
		if(dependency_status === 'red')
		{
			alertify.alert('You cannot change status of the main task as its dependent tasks are still not completed.');
			return false;
		}
		var old_status = $("#task_status_"+task_id).val();
		var orig_data = $('#task_data_'+task_id).val();
		if('<?php echo $actaul_time_on; ?>' == "1"){
			if(status_id == '<?php echo $completed_id;?>'){
				var scope_time = $("#task_spent_"+task_id).val();
				if(scope_time){
					var scope_time_spent = scope_time;
				} else {
					var scope_time_spent = "0";
				}
				
				if(scope_time_spent == "0"){
		    		$("#task_actual_time_task_id").val(task_id);
		    		$("#task_actual_time_task_data").val($("#task_data_"+task_id).val());
		    		$("#task_actual_time").val("");
		    		$("#task_actual_time_hour").val("");
		    		$("#task_actual_time_min").val("");
		    		$("#actual_time_task").modal("show");
		    		return false;
		    	}
			}
		}
		
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/change_status");?>',
			data : {task_id : task_id, status_id:status_id,task_data : orig_data, year:$("#year").val(),month:$("#month").val()},
			async : false,
			success : function(data){
				if(old_status!=status_name){
					var today_date = '<?php echo date("Y-m-d");?>';
					var task_type = $("#task_type_"+task_id).val();
					
					if(task_type){
						task_type1 = task_type.split(",");
						if(task_type1[0] == "1"){
							var came_from_complete = $("#completed_"+date).html();
							if(came_from_complete>0){
								$("#completed_"+date).html(parseInt(came_from_complete)-1);
							}
							if(task_type1[1]!="undefined"){
								var came_from_schedule = $("#scheduled_"+date).html();
								if(came_from_schedule>0){
									$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
								}
							}
							if(task_type1[2]!="undefined"){
								var came_from_due = $("#due_"+date).html();
								if(came_from_due>0){
									$("#due_"+date).html(parseInt(came_from_due)-1);
								}
							}
						} else if(task_type1[0] == "2"){
							var came_from_overdued = $("#overdued_"+date).html();
							if(came_from_overdued>0){
								$("#overdued_"+date).html(parseInt(came_from_overdued)-1);
								if(parseInt($("#overdued_"+date).html()) == 0){
									$("#overdued_"+date).removeClass("txtred");
								}
							}
							if(task_type1[1]!="undefined"){
								var came_from_schedule = $("#scheduled_"+date).html();
								if(came_from_schedule>0){
									$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
								}
							}
						} else {
							if(task_type1[0] == "3" ){
								var came_from_schedule = $("#scheduled_"+date).html();
								if(came_from_schedule>0){
									$("#scheduled_"+date).html(parseInt(came_from_schedule)-1);
								}
							}
							if(task_type1[1]!="undefined"){
								var came_from_due = $("#due_"+date).html();
								if(came_from_due>0){
									$("#due_"+date).html(parseInt(came_from_due)-1);
								}
							}
						}
					}
					if(status_name == "Completed"){
						$("#completed_"+date).html(parseInt($("#completed_"+date).html())+1);
						$("#task_type_"+task_id).val(1);
						$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
						if($("#task_type_"+task_id).val() == "1"){
							$("#task_type_"+task_id).val(1,3);
						} else {
							$("#task_type_"+task_id).val(3);
						}
						
						if(Date.parse(task_due_date) == Date.parse(today_date)){
							$("#due_"+date).html(parseInt($("#due_"+date).html())+1);
							if($("#task_type_"+task_id).val() == "1,3"){
								$("#task_type_"+task_id).val(1,3,4);
							} else {
								$("#task_type_"+task_id).val(3,4);
							}
						}
					} else if(Date.parse(task_due_date)<Date.parse(today_date)){
						$("#overdued_"+date).html(parseInt($("#overdued_"+date).html())+1);
						if(parseInt($("#overdued_"+date).html()) > 0){
							$("#overdued_"+date).addClass("txtred");
						}
						$("#task_type_"+task_id).val(2);
						$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
						if($("#task_type_"+task_id).val() == "2"){
							$("#task_type_"+task_id).val(2,3);
						} else {
							$("#task_type_"+task_id).val(3);
						}
						
					} else {
						$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
						$("#task_type_"+task_id).val(3);
						if(Date.parse(task_due_date)==Date.parse(today_date)){
							$("#due_"+date).html(parseInt($("#due_"+date).html())+1);
							if($("#task_type_"+task_id).val() == "3"){
								$("#task_type_"+task_id).val(3,4);
							} else {
								$("#task_type_"+task_id).val(4);
							}
						}
					}
				}
				$("#task_"+task_id).replaceWith(data);
			}
		});
	}
	
	function RightClickChangeSwimlane(task_id,swimlane_id){
		
		var orig_data = $('#task_data_'+task_id).val();
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/change_swimlane");?>',
			data : {task_id : task_id, swimlane_id:swimlane_id,task_data : orig_data, year:$("#year").val(),month:$("#month").val()},
			async : false,
			success : function(data){
				if(data == "done"){
					
				} else {
					$("#task_"+task_id).replaceWith(data);
				}
			}
		});
	}

	function insert_watchlist(array_data){
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/save_watch_list");?>',
			data : { data : array_data , year:$("#year").val(),month:$("#month").val() },
			success : function(responseData){
				array_data = jQuery.parseJSON(array_data);
				$("#task_"+array_data.task_id).replaceWith(responseData);
			}
		});
	}
	
	function delete_watchlist(array_data){
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/delete_watch_list"); ?>',
			data : { data : array_data, year:$("#year").val(),month:$("#month").val() },
			success : function(responseData){
				array_data = jQuery.parseJSON(array_data);
				$("#task_"+array_data.task_id).replaceWith(responseData);
			}
		});
	}
	function context_fiveweek(task_id,locked_due_date,task_due_date,date,month_start_date,month_end_date,master_task_id,is_master_deleted,chk_watch_list,task_owner_id,dependency_status){
		if(chk_watch_list!='0'){
			if(task_owner_id == LOG_USER_ID){
				context.attach('#task_'+task_id, [
					{text: '<i class="stripicon set-color"></i>Set a colour', href : 'javascript:void(0);', subMenu: [
						<?php if($user_colors){
			        		foreach($user_colors as $color){ ?>
						{text: '<?php echo ucwords($color->name)."RGB".$color->color_code;?>', href : 'javascript:void(0);', action: function(){
							set_task_color_fiveweek(task_id,'<?php echo $color->user_color_id;?>',date);
						}},
						<?php }
						} ?>
					]},
					{text: '<i class="stripicon priority"></i>Set Priority', href : 'javascript:void(0)', subMenu: [
						{text: 'None', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'None');
						}},
						{text: 'LowRGB#48a593', href : 'javascript:void(0)',  action: function(){
							set_priority(task_id,'Low');
						}},
						{text: 'MediumRGB#eeb269', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'Medium');
						}},
						{text: 'HighRGB#dc5753', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'High');
						}}
					]},
					{text : '<i class="stripicon watch-list"></i>Remove from Watch List', href : 'javascript:void(0)', action : function(){
						delete_watchlist($("#task_data_"+task_id).val());
					}},
					{text: '<i class="stripicon due-date"></i>Set due date', autoHide: true, href: 'javascript:void(0)', action:function(){
						$(this).datepicker("show").on('changeDate', function(selected_date) {
							$(this).datepicker('hide');
							rightClickSetDueDate(selected_date.date,task_id,date);
						});
					}},
					{text : '<i class="stripicon change-status"></i>Change Status', href:'javascript:void(0)', subMenu:[
						<?php 
							 if($task_status){
							 	foreach($task_status as $tStatus){
							 		?>
							 		{text:'<?php echo $tStatus->task_status_name;?>', href:'javascript:void(0)', action: function(){
							 			rightClickChangeStatus(task_id,'<?php echo $tStatus->task_status_id;?>','<?php echo $tStatus->task_status_name;?>',date,task_due_date,dependency_status);
							 		}},
							 		<?php
							 	}
							 }
						?>
					]},
					{text : '<i class="stripicon swimlane"></i>Change swimlane', href:'javascript:void(0)', subMenu:[
						<?php
							
							if($user_swimlanes){
								foreach($user_swimlanes as $swimlane){
									?>
									{text: '<?php echo $swimlane->swimlanes_name;?>', href:'javascript:void(0)', action:function(){
										RightClickChangeSwimlane(task_id,'<?php echo $swimlane->swimlanes_id;?>');
									}},
									<?php
								}
							}
						?>
					]},
					{text: '<i class="stripicon move-task"></i>Move the task', href : 'javascript:void(0);', id :'test_'+task_id, action:function(){
						//alert(this.id);
						//$(this).datepicker();
						$(this).datepicker("show").on('changeDate', function(selected_date) {
						
							move_task_fiveweek(selected_date.date,locked_due_date,task_id,task_due_date);
							
						});
						//$(this).focus();
					}},
					{text: '<i class="stripicon add-comment"></i>Add comment', href : 'javascript:void(0);', action: function(){
						var scope_id = task_id;
						scope_id = scope_id.replace('task_', '');
						var orig_data = $('#task_data_'+scope_id).val();
						openpopup(task_id,orig_data);
					}},
					{text: '<i class="stripicon icondelete delete-task"></i>Delete task', href : 'javascript:void(0);', action: function(){
						if(master_task_id == '0' || is_master_deleted == '1'){
							rightClickDeleteFiveWeek(task_id,task_due_date,date);
						} else {
							opendeletefiveweek(task_id,master_task_id,task_due_date,date);
						}
					}}
				]);
			} else {
				context.attach('#task_'+task_id, [
					{text: '<i class="stripicon set-color"></i>Set a colour', href : 'javascript:void(0);', subMenu: [
						<?php if($user_colors){
			        		foreach($user_colors as $color){ ?>
						{text: '<?php echo ucwords($color->name)."RGB".$color->color_code;?>', href : 'javascript:void(0);', action: function(){
							set_task_color_fiveweek(task_id,'<?php echo $color->user_color_id;?>',date);
						}},
						<?php }
						} ?>
					]},
					{text: '<i class="stripicon priority"></i>Set Priority', href : 'javascript:void(0)', subMenu: [
						{text: 'None', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'None');
						}},
						{text: 'LowRGB#48a593', href : 'javascript:void(0)',  action: function(){
							set_priority(task_id,'Low');
						}},
						{text: 'MediumRGB#eeb269', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'Medium');
						}},
						{text: 'HighRGB#dc5753', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'High');
						}}
					]},
					{text : '<i class="stripicon watch-list"></i>Remove from Watch List', href : 'javascript:void(0)', action : function(){
						delete_watchlist($("#task_data_"+task_id).val());
					}},
					{text: '<i class="stripicon due-date"></i>Set due date', autoHide: true, href: 'javascript:void(0)', action:function(){
						$(this).datepicker("show").on('changeDate', function(selected_date) {
							$(this).datepicker('hide');
							rightClickSetDueDate(selected_date.date,task_id,date);
						});
					}},
					{text : '<i class="stripicon change-status"></i>Change Status', href:'javascript:void(0)', subMenu:[
						<?php 
							 if($task_status){
							 	foreach($task_status as $tStatus){
							 		?>
							 		{text:'<?php echo $tStatus->task_status_name;?>', href:'javascript:void(0)', action: function(){
							 			rightClickChangeStatus(task_id,'<?php echo $tStatus->task_status_id;?>','<?php echo $tStatus->task_status_name;?>',date,task_due_date,dependency_status);
							 		}},
							 		<?php
							 	}
							 }
						?>
					]},
					{text : '<i class="stripicon swimlane"></i>Change swimlane', href:'javascript:void(0)', subMenu:[
						<?php
							
							if($user_swimlanes){
								foreach($user_swimlanes as $swimlane){
									?>
									{text: '<?php echo $swimlane->swimlanes_name;?>', href:'javascript:void(0)', action:function(){
										RightClickChangeSwimlane(task_id,'<?php echo $swimlane->swimlanes_id;?>');
									}},
									<?php
								}
							}
						?>
					]},
					{text: '<i class="stripicon move-task"></i>Move the task', href : 'javascript:void(0);', id :'test_'+task_id, action:function(){
						//alert(this.id);
						//$(this).datepicker();
						$(this).datepicker("show").on('changeDate', function(selected_date) {
						
							move_task_fiveweek(selected_date.date,locked_due_date,task_id,task_due_date);
							
						});
						//$(this).focus();
					}},
					{text: '<i class="stripicon add-comment"></i>Add comment', href : 'javascript:void(0);', action: function(){
						var scope_id = task_id;
						scope_id = scope_id.replace('task_', '');
						var orig_data = $('#task_data_'+scope_id).val();
						openpopup(task_id,orig_data);
					}}
				]);
			}
			
		} else {
			if(task_owner_id == LOG_USER_ID){
				context.attach('#task_'+task_id, [
					{text: '<i class="stripicon set-color"></i>Set a colour', href : 'javascript:void(0);', subMenu: [
						<?php if($user_colors){
			        		foreach($user_colors as $color){ ?>
						{text: '<?php echo ucwords($color->name)."RGB".$color->color_code;?>', href : 'javascript:void(0);', action: function(){
							set_task_color_fiveweek(task_id,'<?php echo $color->user_color_id;?>',date);
						}},
						<?php }
						} ?>
					]},
					{text: '<i class="stripicon priority"></i>Set Priority', href : 'javascript:void(0)', subMenu: [
						{text: 'None', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'None');
						}},
						{text: 'LowRGB#48a593', href : 'javascript:void(0)',  action: function(){
							set_priority(task_id,'Low');
						}},
						{text: 'MediumRGB#eeb269', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'Medium');
						}},
						{text: 'HighRGB#dc5753', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'High');
						}}
					]},
					{text : '<i class="stripicon watch-list"></i>Add to Watch List', href : 'javascript:void(0)', action : function(){
						insert_watchlist($("#task_data_"+task_id).val());
					}},
					{text: '<i class="stripicon due-date"></i>Set due date', autoHide: true, href: 'javascript:void(0)', action:function(){
						$(this).datepicker("show").on('changeDate', function(selected_date) {
							$(this).datepicker('hide');
							rightClickSetDueDate(selected_date.date,task_id,date);
						});
					}},
					{text : '<i class="stripicon change-status"></i>Change Status', href:'javascript:void(0)', subMenu:[
						<?php 
							  
							 if($task_status){
							 	foreach($task_status as $tStatus){
							 		?>
							 		{text:'<?php echo $tStatus->task_status_name;?>', href:'javascript:void(0)', action: function(){
							 			rightClickChangeStatus(task_id,'<?php echo $tStatus->task_status_id;?>','<?php echo $tStatus->task_status_name;?>',date,task_due_date,dependency_status);
							 		}},
							 		<?php
							 	}
							 }
						?>
					]},
					{text : '<i class="stripicon swimlane"></i>Change swimlane', href:'javascript:void(0)', subMenu:[
						<?php
							if($user_swimlanes){
								foreach($user_swimlanes as $swimlane){
									?>
									{text: '<?php echo $swimlane->swimlanes_name;?>', href:'javascript:void(0)', action:function(){
										RightClickChangeSwimlane(task_id,'<?php echo $swimlane->swimlanes_id;?>');
									}},
									<?php
								}
							}
						?>
					]},
					{text: '<i class="stripicon move-task"></i>Move the task', href : 'javascript:void(0);', id :'test_'+task_id, action:function(){
						//alert(this.id);
						//$(this).datepicker();
						$(this).datepicker("show").on('changeDate', function(selected_date) {
						
							move_task_fiveweek(selected_date.date,locked_due_date,task_id,task_due_date);
							
						});
						//$(this).focus();
					}},
					{text: '<i class="stripicon add-comment"></i>Add comment', href : 'javascript:void(0);', action: function(){
						var scope_id = task_id;
						scope_id = scope_id.replace('task_', '');
						var orig_data = $('#task_data_'+scope_id).val();
						openpopup(task_id,orig_data);
					}},
					{text: '<i class="stripicon icondelete delete-task"></i>Delete task', href : 'javascript:void(0);', action: function(){
						if(master_task_id == '0' || is_master_deleted == '1'){
							rightClickDeleteFiveWeek(task_id,task_due_date,date);
						} else {
							opendeletefiveweek(task_id,master_task_id,task_due_date,date);
						}
					}}
				]);
			} else {
				context.attach('#task_'+task_id, [
					{text: '<i class="stripicon set-color"></i>Set a colour', href : 'javascript:void(0);', subMenu: [
						<?php if($user_colors){
			        		foreach($user_colors as $color){ ?>
						{text: '<?php echo ucwords($color->name)."RGB".$color->color_code;?>', href : 'javascript:void(0);', action: function(){
							set_task_color_fiveweek(task_id,'<?php echo $color->user_color_id;?>',date);
						}},
						<?php }
						} ?>
					]},
					{text: '<i class="stripicon priority"></i>Set Priority', href : 'javascript:void(0)', subMenu: [
						{text: 'None', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'None');
						}},
						{text: 'LowRGB#48a593', href : 'javascript:void(0)',  action: function(){
							set_priority(task_id,'Low');
						}},
						{text: 'MediumRGB#eeb269', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'Medium');
						}},
						{text: 'HighRGB#dc5753', href : 'javascript:void(0)', action: function(){
							set_priority(task_id,'High');
						}}
					]},
					{text : '<i class="stripicon watch-list"></i>Add to Watch List', href : 'javascript:void(0)', action : function(){
						insert_watchlist($("#task_data_"+task_id).val());
					}},
					{text: '<i class="stripicon due-date"></i>Set due date', autoHide: true, href: 'javascript:void(0)', action:function(){
						$(this).datepicker("show").on('changeDate', function(selected_date) {
							$(this).datepicker('hide');
							rightClickSetDueDate(selected_date.date,task_id,date);
						});
					}},
					{text : '<i class="stripicon change-status"></i>Change Status', href:'javascript:void(0)', subMenu:[
						<?php 
							  
							 if($task_status){
							 	foreach($task_status as $tStatus){
							 		?>
							 		{text:'<?php echo $tStatus->task_status_name;?>', href:'javascript:void(0)', action: function(){
							 			rightClickChangeStatus(task_id,'<?php echo $tStatus->task_status_id;?>','<?php echo $tStatus->task_status_name;?>',date,task_due_date,dependency_status);
							 		}},
							 		<?php
							 	}
							 }
						?>
					]},
					{text : '<i class="stripicon swimlane"></i>Change swimlane', href:'javascript:void(0)', subMenu:[
						<?php
							if($user_swimlanes){
								foreach($user_swimlanes as $swimlane){
									?>
									{text: '<?php echo $swimlane->swimlanes_name;?>', href:'javascript:void(0)', action:function(){
										RightClickChangeSwimlane(task_id,'<?php echo $swimlane->swimlanes_id;?>');
									}},
									<?php
								}
							}
						?>
					]},
					{text: '<i class="stripicon move-task"></i>Move the task', href : 'javascript:void(0);', id :'test_'+task_id, action:function(){
						//alert(this.id);
						//$(this).datepicker();
						$(this).datepicker("show").on('changeDate', function(selected_date) {
						
							move_task_fiveweek(selected_date.date,locked_due_date,task_id,task_due_date);
							
						});
						//$(this).focus();
					}},
					{text: '<i class="stripicon add-comment"></i>Add comment', href : 'javascript:void(0);', action: function(){
						var scope_id = task_id;
						scope_id = scope_id.replace('task_', '');
						var orig_data = $('#task_data_'+scope_id).val();
						openpopup(task_id,orig_data);
					}}
				]);
			}
			
		}
		
	}
</script>
<?php 
$task_list = "display:none;";
$task_info = "display:none;";
$task_lable = "display:none;";
if(isset($show_cal_view) && $show_cal_view!=''){
	$show_cal_arr = explode(",", $show_cal_view);
	if(in_array("1",$show_cal_arr)){
		$task_list = "display:block;";
	}
	
	if(in_array("2",$show_cal_arr)){
		$task_info = "display:block;";
	} 
	
	if(in_array("3",$show_cal_arr)){
		$task_lable = "display:block;";
	} 
}
date_default_timezone_set($this->session->userdata("User_timezone"));
$default_format = $site_setting_date; 
?>
<div id="calendar">
	<table class="month">
		<tbody>
			<tr>
				<th colspan="7">
					<div class="cal-currentdate">
						<?php if($from_view == "weekly"){ ?>
							<a href="javascript:void(0)" onclick="change_view('<?php echo $ajax_start_date."#".$ajax_end_date."#prev"; ?>');"> <i class="stripicon mycalprev"> </i> </a>
						<?php  } else { ?>
							<a class="calprev" title="Previous" href="javascript:__doPostBack('view','monthly','<?php echo $ajax_year;?>','<?php echo $ajax_month-1;?>','<?php echo date("d");?>')"><i class="calenderstrip calprev"> </i></a>
						<?php } ?>
						<?php echo date("d-M-Y",strtotime(str_replace(array("/"," ",","), "-", $start_date))) ?> - <?php echo date("d-M-Y",strtotime(str_replace(array("/"," ",","), "-", $end_date))) ?>						
						<?php if($from_view == "weekly"){ ?>
							<a href="javascript:void(0)" onclick="change_view('<?php echo $ajax_start_date."#".$ajax_end_date."#next"; ?>');"> <i class="stripicon mycalnext"> </i> </a>
						<?php  } else { ?>
							<a class="calnext" title="Next" href="javascript:__doPostBack('view','monthly','<?php echo $ajax_year;?>','<?php echo $ajax_month+1;?>','<?php echo date("d");?>')"><i class="calenderstrip calnext"> </i></a>
						<?php } ?>
						<div class="calendar-filter">
							<ul class="list-unstyled">
                                                            <li  class="datetimepicker_month_view" onclick="cal_fill()"><i class="stripicon filtericon"> </i><span>Calendar</sapn></li>
								<li> <a href="<?php echo site_url('calendar/weekView');?>" class="tooltips "  data-placement="bottom" data-original-title="Weekly View" onclick="save_last_calender_view('1');"> <i class="stripicon weekicon"> </i> </a> </li>
								<li> <a href="<?php echo site_url('calendar/NextFiveDayView');?>" class="tooltips " data-placement="bottom" data-original-title="Next-Five Day View" onclick="save_last_calender_view('2');"> <i class="stripicon dayicon"> </i> </a> </li>
								<li class="active"> <a class="tooltips " data-placement="bottom" data-original-title="Monthly View" href="<?php echo site_url('calendar/myCalendar');?>" onclick="save_last_calender_view('3');"> <i class="stripicon monthicon"> </i> </a> </li>
							</ul>
						</div>
						
						<input type="hidden" name="start_date" id="week_start_date" value="<?php echo $ajax_start_date;?>" />
						<input type="hidden" name="week_end_date" id="week_end_date" value="<?php echo $ajax_end_date; ?>" />
						<input type="hidden" name="action" id="week_action" value="<?php if(isset($action) && $action!=''){ echo $action; } else { echo ''; } ?>" />
						<input type="hidden" name="year" id="year" value="<?php echo $ajax_year;?>" />
						<input type="hidden" name="month" id="month" value="<?php echo $ajax_month;?>" />
						
						<?php if($from_page == 'myCalendar'){
							$from_re_page = 'from_calendar';
						} else if($from_page == 'weekView'){
							$from_re_page = 'weekView';
						} else if($from_page == 'NextFiveDayView'){
							$from_re_page = 'NextFiveDay';
						} else {
							$from_re_page = '';
						}?>
						<input type="hidden" name="from_page" id="from_page" value="<?php echo $from_re_page;?>" />
					</div>
				</th>
			</tr>
			<tr class="tr_days">
				<td class="th">Monday</td>
				<td class="th">Tuesday</td>
				<td class="th">Wednesday</td>
				<td class="th">Thursday</td>
				<td class="th">Friday</td>
				<td class="th">Saturday</td>
				<td class="th">Sunday</td>
			</tr>
			<?php  $FiveWeekData = get_calender_weekly_tasks($start_date,$end_date,$calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,$calender_sorting,$completed_id);
				$month_start_date = $start_date;
				$month_end_date = $end_date;
				//company off days array
				$off_days_arr = array();
				$off_days = get_company_offdays();
				if($off_days!=''){
					$off_days_arr = explode(',', $off_days);
				}

				
				if($date_arr){
					$capacity = getUserCapacity($this->session->userdata('Temp_calendar_user_id'));
					if($capacity){
						$Mon_capacity = $capacity['MON_hours'];
						$Tue_capacity = $capacity['TUE_hours'];
						$Wed_capacity = $capacity['WED_hours'];
						$Thu_capacity = $capacity['THU_hours'];
						$Fri_capacity = $capacity['FRI_hours'];
						$Sat_capacity = $capacity['FRI_hours'];
						$Sun_capacity = $capacity['SUN_hours'];
					}
					
					for($a=0;$a<count($date_arr);$a++){
						$str = '';
						$task_list_str = '';
						$event_str = '';
						$allocated = 0; 
						$due = 0;
						$overdue = 0;
						$completed = 0;
						$schedulled = 0;
						$labalclass = '';
						$tdstyle = '';
						if($allow_past_task == "0" && strtotime(date('Y-m-d'))>strtotime(str_replace(array("/"," ",","), "-", $date_arr[$a]))){ 
							$href = '<a href="javascript:void(0);" style="opacity: 0.5; display:none;" > <i class="stripicon addblueicon" > </i> </a>';
						} else { 
							$href = '<a onclick="add_task(\''.strtotime($date_arr[$a]).'\',\''.date($default_format,strtotime($date_arr[$a])).'\');" href="javascript:void(0);"><i class="stripicon addblueicon" > </i> </a>';
						} 
						$sort_class = 'sortable';
						if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date_arr[$a])))),$off_days_arr)){
							$sort_class = 'unsorttd';
							//$href = '<a href="javascript:void(0);" style="opacity: 0.5;" > <i class="stripicon addblueicon" > </i> </a>';
						}
						$day = date("D",strtotime(str_replace(array("/"," ",","), "-", $date_arr[$a])));
						if($day == "Mon"){$capacity = $Mon_capacity;} else if($day == "Tue"){$capacity = $Tue_capacity;} else if($day == "Wed"){$capacity = $Wed_capacity;} else if($day == "Thu"){$capacity=$Thu_capacity;} else if($day=='Fri'){$capacity=$Fri_capacity;} else if($day=="Sat"){$capacity=$Sat_capacity;}else {$capacity=$Sun_capacity;} 
						if($capacity == '0'){
							$tdstyle = 'style="background-color:#CED2D8;"';
						}
						if(isset($FiveWeekData[$date_arr[$a]]) && !empty($FiveWeekData[$date_arr[$a]])){
							
							foreach($FiveWeekData[$date_arr[$a]] as $date){
								$project_name = '';
								$move_class = '';
								if(strpos($date['task_id'],'child') !== false) {
								    $chk = "0";
								} else {
									$chk = "1";
								}
								$is_master_deleted = $date['tm'];
								?>
								<script type="text/javascript">
									$(document).ready(function(){
										//alert("RAM");
										
										context_fiveweek('<?php echo $date['task_id'];?>','<?php echo $date['locked_due_date'];?>','<?php echo date("Y-m-d",strtotime($date['task_due_date']));?>','<?php echo strtotime($date_arr[$a]);?>','<?php echo $month_start_date;?>','<?php echo $month_end_date;?>','<?php echo $date['master_task_id'];?>','<?php echo $is_master_deleted;?>','<?php echo $date['watch'];?>','<?php echo $date['task_owner_id'];?>','<?php echo $completed_depencencies;?>')
										
										
									});
									
									</script>
									<?php 
								$full_title = $date['task_title'];
								$title = $date['task_title'];
								if($date['task_project_id']){
									$project_name = $date['project_title'];
									$title = $project_name.' - '.$full_title;
									$full_title = $project_name.' - '.$full_title;
								}
								
								$task_type = "0"; 
								if($date['task_status_id']==$completed_id){
									$completed += 1; // total completed task
									$task_type = "1";
									$schedulled += 1;
									if($task_type == "1"){
										$task_type = "1,3";
									} else {
										$task_type = "3";
									}
									if(strtotime($date['task_due_date']) == strtotime($date_arr[$a])){
										$due += 1;
										if($task_type == "1,3"){
											$task_type = "1,3,4";
										} else {
											$task_type = "3,4";
										}
									}
								} else if($date['task_due_date'] < date('Y-m-d')){
									$overdue += 1;
									$task_type = "2";
									$schedulled += 1;
									if($task_type == "2"){
										$task_type = "2,3";
									} else {
										$task_type = "3";
									}
								} else {
									$schedulled += 1;
									if($task_type == "1"){
										$task_type = "1,3";
									} else {
										$task_type = "3";
									}
									if(strtotime($date['task_due_date']) == strtotime($date_arr[$a])){
										$due += 1;
										if($task_type == "1,3"){
											$task_type = "1,3,4";
										} else {
											$task_type = "3,4";
										}
									}
								}
								
								
								$task_type_class = '';
								if($task_type){
									$task_type_val = explode(",", $task_type);
									$task_type_class = '';
									for($x=0;$x<count($task_type_val);$x++){
										$task_type_class .= "task_type_".$task_type_val[$x]." ";
									}
								}
								
								if($date['color_code']){
									$color_code = $date['color_code'];
								} else {
									$color_code = '#fff';
								}
								if($date['outside_color_code']){
									$outside_code = $date['outside_color_code'];
								} else {
									$outside_code = '#e5e9ec';
								}
								
								if($chk == "1"){
									$dependencies = $date['tpp'];
									if($date['tpp']!='0' && $date['completed_depencencies']=="0"){
										$completed_depencencies = "green";
									} else if($date['tpp']=='0' && $date['completed_depencencies']=="0"){
										$completed_depencencies = "green";
									} else {
										$completed_depencencies = "red";
									}
								} else {
									$dependencies = '';
									$completed_depencencies = "";
								}
								if($completed_depencencies === 'red'){
									$move_class = 'unsorttd';
								}
								$cl = '';
								if($date['task_time_estimate'] == '0'){
									$cl = 'display:none;';
								}
								
								$cl3 = "";
								if($date['locked_due_date'] == "0"){
									$cl3 = 'display:none;';
								}
								
								if($cl == "" && $cl3 == ""){
									if(strlen($title) > 18) {
									    $title = substr($title, 0, 16).'..'; 
									}
								} else if($cl!="" && $cl3 == ""){
									if(strlen($title) > 24) {
									    $title = substr($title, 0, 22).'..'; 
									}
								} else if($cl=="" && $cl3!=""){
									if(strlen($title) > 18) {
									    $title = substr($title, 0, 16).'..'; 
									}
								} else {
									if(strlen($title) > 26) {
									    $title = substr($title, 0, 24).'..'; 
									}
								}
								
								if($date['is_personal'] == '1' && $date['task_owner_id'] != get_authenticateUserID()){
									$task_list_str .= '<div class="taskbox calicon'.$date['task_priority'].' unsorttd '.$task_type_class.'" style="background-color:'.$color_code.'; border:1px solid '.$outside_code.';">
														<span class="task-desc"> Busy </span>
														<div class="clearfix"> </div></div>';
								} else {

									$task_list_str .= '<div onclick="save_task_for_timer(this,\''.$date['task_id'].'\',\''.addslashes($date['task_title']).'\',\''.$date['task_time_spent'].'\',\''.$chk.'\',\''.$completed_depencencies.'\');" class="taskbox  calicon'.$date['task_priority'].' '.$task_type_class.' month_master_'.$date['master_task_id'].' '.$move_class.'" style="background-color:'.$color_code.';border:1px solid '.$outside_code.';" id="task_'.$date['task_id'].'">';
									if($date['master_task_id']=='0' || $is_master_deleted == '1'){
										$task_list_str .= '<a class="tooltips " data-original-title="'.$full_title.'"  href="javascript:void(0)" onclick="edit_task(this,\''.$date['task_id'].'\',\''.$chk.'\')">';
									} else {
										$task_list_str .= '<a onclick="open_seris(this,\''.$date['task_id'].'\',\''.$date['master_task_id'].'\',\''.$chk.'\');" href="javascript:void(0)" class="tooltips " data-original-title="'.$full_title.'" >';
									}
									$task_list_str .= '<span class="task-desc">'.$title.'</span>
														<p class="task-hrs">
														<i style="'.$cl3.'" class="stripicon lockicon"></i>
														<span class="task-hrs" id="task_est_'.$date['task_id'].'" style="'.$cl.'"> '.minutesToTime($date['task_time_estimate']).' </span>
														</p>
														<input type="hidden" id="task_data_'.$date['task_id'].'" value="'.htmlspecialchars(json_encode($date)).'" />
														<input type="hidden" id="hdn_due_date_'.$date['task_id'].'" value="'.strtotime($date['task_due_date']).'" />
														<input type="hidden" id="hdn_locked_due_date_'.$date['task_id'].'" value="'.$date['locked_due_date'].'" />
														<input type="hidden" id="or_color_'.$date['task_id'].'" name="or_color_id" value="'.$outside_code.'" />
														<input type="hidden" id="task_type_'.$date['task_id'].'" name="task_type" value="'.$task_type.'" />

														<input type="hidden" id="task_spent_'.$date['task_id'].'" name="task_spent_time" value="'.$date['task_time_spent'].'" />
														<input type="hidden" id="task_status_'.$date['task_id'].'"  name="task_status_name" value="'.$date['task_status_name'].'" />

														<div class="clearfix"> </div></a>
														</div>';
								}
								$allocated += $date['task_time_estimate'];
						
								
								
								if($allocated > ($capacity)){
									$labalclass = 'redlabel';
								}
							}
							
							$overdueClass = "";
							if($overdue){
								$overdueClass = "txtred";
							}
							$event_str .= '<div class="td-date unsorttd">'.date($site_setting_date,strtotime(str_replace("/", "-", $date_arr[$a]))).'
											'.$href.' 
										</div>
										<div class="task-list unsorttd" id="task_list_'.strtotime($date_arr[$a]).'" style="'.$task_list.'" >
											<ul>
												<li> 
													<ul>
														<li> <div class="commonlabel">Capacity&nbsp;</div> </li>
														<li>  <div class="commonlabel" id="capacity_time_'.strtotime($date_arr[$a]).'"> '.minutesToTime($capacity).' </div></li>
													</ul>
												<li> 
													<ul>
														<li> <div class="commonlabel">Allocated</div> </li>
														<li>  <div class="commonlabel '.$labalclass.'" id="estimate_time_'.strtotime($date_arr[$a]).'"> '.minutesToTime($allocated).' </div></li>
													</ul>
												</li>
											</ul>
										</div>
										<div class="task-info unsorttd" id="task_info_'.strtotime($date_arr[$a]).'" style="'.$task_info.'">
											<ul>
												<li> 
													<span class="tasklab-info">Overdue : </span>
													<span class="task-num '.$overdueClass.' overduehover" id="overdued_'.strtotime($date_arr[$a]).'"> '.$overdue.' </span>
												</li>
												<li> 
													<span class="tasklab-info">Due : </span>
													<span class="task-num duehover" id="due_'.strtotime($date_arr[$a]).'"> '.$due.' </span>
												</li>
												<li> 
													<span class="tasklab-info">Completed : </span>
													<span class="task-num completedhover" id="completed_'.strtotime($date_arr[$a]).'"> '.$completed.' </span>
												</li>
												<li> 
													<span class="tasklab-info">Scheduled :</span>
													<span class="task-num scheduledhover" id="scheduled_'.strtotime($date_arr[$a]).'"> '.$schedulled.' </span>
												</li>
											</ul>
										</div>
										<div class="task-lable '.$sort_class.' full_task scroll" id="'.strtotime($date_arr[$a]).'" '.$task_lable.'" style="padding-bottom:10px;">'.$task_list_str;
					
								$event_str .= '</div>';
								
							//	<span class="task-num" id="scheduled_'.strtotime($date[$a]).'"> '.$schedulled.' </span>
							
						} else {
							$event_str .= '<div class="td-date  '.$sort_class.' full_task scroll" id="'.strtotime($date_arr[$a]).'"  style="padding-bottom:20px;"> '.date($site_setting_date,strtotime(str_replace(array("/"," ",","), "-", $date_arr[$a]))).' 
											'.$href.'
												
										</div>';
						}
						
						if($a%7 == '0'){
							$str.='<tr>';
							$b = 0;
						} 
						$str .= '<td id="td_'.strtotime($date_arr[$a]).'" class="td" '.$tdstyle.'>'.$event_str.'</td>';
						if($a%7 == '0'){
							$b++;
						}
						if($a%7 == '0' && $b!='1'){
							$str .= '</tr>';
						}
						echo $str;
					}
				
			} ?>
		</tbody>
	</table>
</div>
<?php date_default_timezone_set("UTC"); ?>
