<?php date_default_timezone_set($this->session->userdata("User_timezone"));

$company_flags = $this->config->item('company_flags');
$allow_past_task = "1";
if($company_flags){
	$allow_past_task = $company_flags['allow_past_task'];
}
 ?>

<script src='<?php echo base_url().getThemename(); ?>/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?Ver=<?php echo VERSION;?>'></script>

<script type='text/javascript'>

var status = '';
$(document).ready(function(){
	
	$(".full_task div").addClass("before_timer");

	
	if($("#show_capacity").is(':checked')){
			$(".task-list").css('display','block');
		} else {
			$(".task-list").css('display','none');
		}
		
		if($("#show_summary").is(':checked')){
			$(".task-info").css('display','block');
		} else {
			$(".task-info").css('display','none');
		}
		
		if($("#show_task").is(':checked')){
			$(".task-lable").css('display','block');
			//$(".task-lable").css('display','block');
			$('.scroll_calender').slimScroll({
				color: '#17A3E9',
				height : '120px',
		 	    wheelStep: 100,
			});
		} else {
			$(".task-lable").css('display','none');
			$('.scroll_calender').slimScroll({
		        destroy:true
		    });
		}
	
	
	popover();
	
	$(".sortable").sortable({
		items: '> div:not(.unsorttd)',
		revert: true,
        forcePlaceholderSize: true,
        connectWith: 'div',
        scroll: false,
   		placeholder: "drag-place-holder",
 		scrollSensitivity: 100,
   	   	scrollSpeed: 40,
    	tolerance: "pointer",
       	dropOnEmpty: true,
      	helper: function (event, element) {
            return $(element).clone().addClass('dragging');
        },
        start: function (e, ui) { 
           
        },
         update : function (e, ui) { 
        	
        	var date = $(this).attr('id');
        	
        	var order = $('#'+date).sortable('serialize'); 
        	
        	var scope_id = ui.item.show().attr('id');
        	//alert(scope_id);
        	scope_id = scope_id.replace('task_', '');
        	
        	var orig_data = $('#task_data_'+scope_id).val();
			
			var URL = '<?php echo site_url('calendar/setOrder') ?>';
	      	//$('#dvLoading').fadeIn('slow');
	      	
	      	if($("#redirect_page").val() == "FiveWeekView"){
	      		$.ajax({
					url:URL,
					type:'POST',
					data:{'order':order,'date':date, 'scope_id':scope_id, task_data : orig_data, 'from' : 'ajax'},
					success : function(responsedata) {
						
						if(order){
							if(responsedata == "no_data"){
								var responsedata = jQuery.parseJSON(responsedata);
								
								$.ajax({
									type : 'post',
									url : '<?php echo site_url("calendar/set_update_task");?>',
									data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),color_menu:$("#monthly_color_menu").val()},
									success : function(task_detal){
										$("#task_"+scope_id).replaceWith(task_detal);
									}
								});
								
							}
						} else {
							//$("#task_list_"+date).remove();
							//$("#task_info_"+date).remove();
						}
						
						//$('#dvLoading').fadeOut('slow');
					},
				});
	      	} else {
	      	
				$.ajax({
					url:URL,
					type:'POST',
					data:{'order':order,'date':date, 'scope_id':scope_id, task_data : orig_data},
					success : function(responsedata) {
						//alert(scope_id+"====="+order);
						if(order){
							if(responsedata == "no_data"){
								var responsedata = jQuery.parseJSON(responsedata);
								
								$.ajax({
									type : 'post',
									url : '<?php echo site_url("calendar/set_update_task");?>',
									data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),color_menu:$("#monthly_color_menu").val()},
									success : function(task_detal){
										$("#task_"+scope_id).replaceWith(task_detal);
									}
								});
								
							}
						} else {
							//$("#task_list_"+date).remove();
						//	$("#task_info_"+date).remove();
						}
						
						//$('#dvLoading').fadeOut('slow');
					},
				});
			}
	    }, 
	    stop: function (e, ui) {
          //  self.sendUpdatedIndex(ui.item);
          
        },
		
        receive: function( e, ui ) { 
        	
        	var date = $(this).attr('id');
        	//alert(date);
        //	var date1 = date.toLocaleString()
        	
        	//alert(date1);
        	
        	//	date1 = new Date(date*1000);
        	//function pad(s) { return (s < 10) ? '0' + s : s; }
			//date1 = date1.getFullYear()+"-"+pad(date1.getMonth()+1)+"-"+pad(date1.getDate());
        	
        	var order = $('#'+date).sortable('serialize'); 
        	
        	var scope_id = ui.item.show().attr('id');
        	
        	//alert(scope_id+"====>");
        	
        	scope_id = scope_id.replace('task_', '');
        	
        	var orig_data = $('#task_data_'+scope_id).val();
        	
        	
        	var came_from_id = ui.sender[0].id;
        	var came_from_estimate = get_minutes($("#estimate_time_"+came_from_id).html());
        	
        	var cam_capacity = $("#capacity_time_"+came_from_id).html();
			var h_index = cam_capacity.indexOf("h");
			var cam_capacity_time = cam_capacity.substr(0,h_index);
      		
      		var dropped_id = this.id;
      		if($("#task_list_"+dropped_id).length == 0) {
      			var is_data_available = 0;
      		} else {
      			var dropped_estimate = get_minutes($("#estimate_time_"+dropped_id).html());
      		
	      		var dropped_capacity = $("#capacity_time_"+dropped_id).html();
				var d_h_index = dropped_capacity.indexOf("h");
				var dropped_capacity_time = dropped_capacity.substr(0,d_h_index);
				var is_data_available = 1;
      		}
      		
      		var scope_locked = $("#hdn_locked_due_date_"+scope_id).val();
      		
        	if(scope_locked == "1"){
        		var scope_due_date = $("#hdn_due_date_"+scope_id).val();
	        	if(dropped_id>scope_due_date){
	        		$(ui.sender).sortable('cancel');
	        		$(this).sortable("refresh");
	        		var order2 = $('#'+came_from_id).sortable('serialize'); 
	        		$.ajax({
						url:'<?php echo site_url('calendar/setOrder') ?>',
						type:'POST',
						data:{'order':order2,'date':came_from_id, 'scope_id':scope_id, task_data : orig_data},
						success : function(responsedata) {
							
							if(order){
								if(responsedata == "no_data"){
									var responsedata = jQuery.parseJSON(responsedata);
									
									$.ajax({
										type : 'post',
										url : '<?php echo site_url("calendar/set_update_task");?>',
										data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),color_menu:$("#monthly_color_menu").val()},
										success : function(task_detal){
											$("#task_"+scope_id).replaceWith(task_detal);
										}
									});
									
								}
							} else {
								//$("#task_list_"+date).remove();
							//	$("#task_info_"+date).remove();
							}
							
							//$('#dvLoading').fadeOut('slow');
							$('.sortable').sortable("enable");  
						   $("#task_"+scope_id).removeClass("pulsate");
						},
					});
	        		alertify.alert("Sorry, you can only move the task into prior or equal due date");
		      		return;
	        	}	
        	}
      		
	   		var scope_time = $("#task_est_"+scope_id).html();
        	if(scope_time){
        		var scope_time_estimate = get_minutes(scope_time);
        	} else {
        		var scope_time_estimate = '0';
        	}
        	
        	var URL = '<?php echo site_url('calendar/UpdateScope') ?>';
        	//$('#dvLoading').fadeIn('slow');
        	
        	if($("#redirect_page").val() == "FiveWeekView"){
						$.ajax({
																url:URL,
																type:'POST',
																data:{'task_data': orig_data,'scope_id':scope_id, 'date':date, 'from' : 'ajax'},
																success : function(responsedata) {
																	var responsedata = jQuery.parseJSON(responsedata);
																	
																	if($("#"+came_from_id+" .taskbox").length == 0) {
																		$("#task_list_"+came_from_id).remove();
																		$("#task_info_"+came_from_id).remove();
																	} else {
																		
																		var came_from_schedule = $("#scheduled_"+came_from_id).html();
																		if(came_from_schedule>0){
																			$("#scheduled_"+came_from_id).html(parseInt(came_from_schedule)-1);
																		}
																		
																		var came_min = parseInt(came_from_estimate)-parseInt(scope_time_estimate);
																		var came_estimate = hoursminutes(came_min);
																		$("#estimate_time_"+came_from_id).html(came_estimate);
																		
																		$("#estimate_time_"+came_from_id).removeAttr('class');
																			if(came_min>(cam_capacity_time*60)){
																				$("#estimate_time_"+came_from_id).attr('class','commonlabel redlabel');
																			} else {
																				$("#estimate_time_"+came_from_id).attr('class','commonlabel');
																			}
																		}
																	
																	if(is_data_available == 0){
																		
																		$.ajax({
																			type : 'post',
																			url : '<?php echo site_url("calendar/monthly_day_view");?>',
																			data : {date : date, task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),'from':'ajax'},
																			success : function(data){
																				$("#td_"+date).html(data);
																			}
																		});
																	} else {
																		
																		var dropped_schedule = $("#scheduled_"+dropped_id).html();
																		$("#scheduled_"+dropped_id).html(parseInt(dropped_schedule)+1);
																		
																		var dropped_min = parseInt(dropped_estimate)+parseInt(scope_time_estimate);
																		var dropped_est = hoursminutes(dropped_min);
																		$("#estimate_time_"+dropped_id).html(dropped_est);
																		
																		$("#estimate_time_"+dropped_id).removeAttr('class');
																		if(dropped_min>(dropped_capacity_time*60)){
																			$("#estimate_time_"+dropped_id).attr('class','commonlabel redlabel');
																		} else {
																			$("#estimate_time_"+dropped_id).attr('class','commonlabel');
																		}
																		
																		
																		$.ajax({
																			type : 'post',
																			url : '<?php echo site_url("calendar/set_update_task");?>',
																			data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),color_menu:$("#monthly_color_menu").val()},
																			success : function(task_detal){
																				$("#task_"+scope_id).replaceWith(task_detal);
																			}
																		});
																	}
																	
																	//$('#dvLoading').fadeOut('slow');
																},
															});
			} else {
				$.ajax({
					url:URL,
					type:'POST',
					data:{'task_data': orig_data,'scope_id':scope_id, 'date':date},
					success : function(responsedata) {
						var responsedata = jQuery.parseJSON(responsedata);
						
						var task_type = $("#task_type_"+scope_id).val();
						
						if($("#"+came_from_id+" .taskbox").length == 0) {
							$("#task_list_"+came_from_id).remove();
							$("#task_info_"+came_from_id).remove();
						} else {
							
							if(task_type){
								task_type1 = task_type.split(",");
								if(task_type1[0] == "1"){
									var came_from_complete = $("#completed_"+came_from_id).html();
									if(came_from_complete>0){
										$("#completed_"+came_from_id).html(parseInt(came_from_complete)-1);
									}
									if(task_type1[1]!=undefined){
										var came_from_schedule = $("#scheduled_"+came_from_id).html();
										if(came_from_schedule>0){
											$("#scheduled_"+came_from_id).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[2]!=undefined){
										var came_from_due = $("#due_"+came_from_id).html();
										if(came_from_due>0){
											$("#due_"+came_from_id).html(parseInt(came_from_due)-1);
										}
									}
								} else if(task_type1[0] == "2"){
									var came_from_overdued = $("#overdued_"+came_from_id).html();
									if(came_from_overdued>0){
										$("#overdued_"+came_from_id).html(parseInt(came_from_overdued)-1);
									}
									if(task_type1[1]!=undefined){
										var came_from_schedule = $("#scheduled_"+came_from_id).html();
										if(came_from_schedule>0){
											$("#scheduled_"+came_from_id).html(parseInt(came_from_schedule)-1);
										}
									}
								} else {
									if(task_type1[0] == "3" ){
										var came_from_schedule = $("#scheduled_"+came_from_id).html();
										if(came_from_schedule>0){
											$("#scheduled_"+came_from_id).html(parseInt(came_from_schedule)-1);
										}
									}
									if(task_type1[1]!=undefined){
										var came_from_due = $("#due_"+came_from_id).html();
										if(came_from_due>0){
											$("#due_"+came_from_id).html(parseInt(came_from_due)-1);
										}
									} 
								}
							}
							
							var came_min = parseInt(came_from_estimate)-parseInt(scope_time_estimate);
							var came_estimate = hoursminutes(came_min);
							$("#estimate_time_"+came_from_id).html(came_estimate);
							
							$("#estimate_time_"+came_from_id).removeAttr('class');
							if(came_min>(cam_capacity_time*60)){
								$("#estimate_time_"+came_from_id).attr('class','commonlabel redlabel');
							} else {
								$("#estimate_time_"+came_from_id).attr('class','commonlabel');
							}
						}
						//alert(date);
						//alert(is_data_available);
						
						if(is_data_available == 0){
							var wd = $("#td_"+date+" .weekday-txt").html();
							wd = wd.replace('WD ','');
							$.ajax({
								type : 'post',
								url : '<?php echo site_url("calendar/monthly_day_view");?>',
								data : {date : date, task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),'wd':wd},
								success : function(data){
									$("#td_"+date).html(data);
								}
							});
						} else {
							
							var today_date_time = responsedata.today_date_time;
							
							if(task_type){
								task_type1 = task_type.split(",");
								if(task_type1[0] == "1"){
									var dropped_completed = $("#completed_"+dropped_id).html();
									$("#completed_"+dropped_id).html(parseInt(dropped_completed)+1);
									var dropped_schedule = $("#scheduled_"+dropped_id).html();
									$("#scheduled_"+dropped_id).html(parseInt(dropped_schedule)+1);
									if(responsedata.task_due_date == dropped_id){
										var dropped_due = $("#due_"+dropped_id).html();
										$("#due_"+dropped_id).html(parseInt(dropped_due)+1);
									}
								} else if(responsedata.task_due_date<today_date_time){
									var dropped_overdued = $("#overdued_"+dropped_id).html();
									$("#overdued_"+dropped_id).html(parseInt(dropped_overdued)+1);
									var dropped_schedule = $("#scheduled_"+dropped_id).html();
									$("#scheduled_"+dropped_id).html(parseInt(dropped_schedule)+1);
								} else {
									var dropped_schedule = $("#scheduled_"+dropped_id).html();
										$("#scheduled_"+dropped_id).html(parseInt(dropped_schedule)+1);
									if(responsedata.task_due_date == dropped_id){
										var dropped_due = $("#due_"+dropped_id).html();
										$("#due_"+dropped_id).html(parseInt(dropped_due)+1);
									}
								}
							}
							
							var dropped_min = parseInt(dropped_estimate)+parseInt(scope_time_estimate);
							var dropped_est = hoursminutes(dropped_min);
							$("#estimate_time_"+dropped_id).html(dropped_est);
							
							$("#estimate_time_"+dropped_id).removeAttr('class');
							if(dropped_min>(dropped_capacity_time*60)){
								$("#estimate_time_"+dropped_id).attr('class','commonlabel redlabel');
							} else {
								$("#estimate_time_"+dropped_id).attr('class','commonlabel');
							}
							
							
							$.ajax({
								type : 'post',
								url : '<?php echo site_url("calendar/set_update_task");?>',
								data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),color_menu:$("#monthly_color_menu").val()},
								success : function(task_detal){
									$("#task_"+scope_id).replaceWith(task_detal);
								}
							});
						}
						
					}
				});		
			}
        },

        cursor: 'move',
       
	}).disableSelection();
	
});
</script>
<?php 


$last_rember_values = get_user_last_rember_values();
$show_cal_view = $last_rember_values->show_cal_view;
if(isset($show_cal_view) && $show_cal_view!=''){
	$show_cal_arr = explode(",", $show_cal_view);
	if(in_array("1",$show_cal_arr)){
		$task_list = "display:block;";
	} else {
		$task_list = "display:none;";
	}
	
	if(in_array("2",$show_cal_arr)){
		$task_info = "display:block;";
	} else {
		$task_info = "display:none;";
	}
	
	if(in_array("3",$show_cal_arr)){
		$task_lable = "display:block;";
	} else {
		$task_lable = "display:none;";
	}
}?>

<?php
$default_format = $this->config->item('company_default_format'); 
$off_days_arr = array();
$off_days = get_company_offdays();
if($off_days!=''){
	$off_days_arr = explode(',', $off_days);
}
 
$date = date('Y-m-d',strtotime(str_replace(array("/"," ",","), "-", $date)));
$day = date('D',strtotime(str_replace(array("/"," ",","), "-", $date)));
$actday = date('j',strtotime($date));

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



if($allow_past_task == "0" && strtotime(date('Y-m-d'))>strtotime(str_replace(array("/"," ",","), "-", $date))){
	$href = '<a href="javascript:void(0);" style="opacity:0.5; display:none;"> <i class="calenderstrip caladdicon"> </i> </a>';
} else {
	$href = '<a onclick="add_task(\''.strtotime($date).'\',\''.date($default_format,strtotime($date)).'\');" href="javascript:void(0);"> <i class="calenderstrip caladdicon"> </i> </a>';
}

$sort_class = 'sortable';
if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr)){
	$sort_class = 'unsorttd';
	//$href = '<a href="javascript:void(0);" style="opacity: 0.5;" > <i class="stripicon addblueicon" > </i> </a>';
}
$capacity = get_user_capacity($day,get_authenticateUserID());
if($capacity == '0'){
	$tdstyle = 'style="background-color:#CED2D8;"';
}
if(isset($order_task) && $order_task!='')
{	
	
	
	
	for($j=0;$j<count($order_task);$j++)
	{
		$color = '#ccc';
		$project_name = '';
		$outside_color = '#ccc';
		$move_class = '';
		$chk = chk_task_exists($order_task[$j]['task_id']);
		
		$firstday = date("w",strtotime($year."-".$month."-01"));
		$lastday = date("w",strtotime(date("Y-m-t",strtotime($year."-".$month."-01"))));
		
	    if ($firstday == 0) $firstday = 7;
		$first_empty_days = $firstday-(get_default_day_no_of_company()-1);
		if($first_empty_days<0){
			$first_empty_days = 7 + $first_empty_days;
		}
		
		$last_empty_day = (get_default_day_no_of_company() - $lastday) - 2;
		if($last_empty_day<0){
			$last_empty_day = 7 + $last_empty_day;
		}
		
		$month_start_date = date("Y-m-d",strtotime("-".$first_empty_days." days",strtotime($year."-".$month."-01")));
		$month_end_date = date("Y-m-d",strtotime("+".$last_empty_day." days",strtotime(date("Y-m-t",strtotime($year."-".$month."-01")))));				
		
		  ?>
		
			<?php 
		$full_title = $order_task[$j]['task_title'];
		$title = $order_task[$j]['task_title'];
		if($order_task[$j]['task_project_id']){
			$project_name = get_project_name($order_task[$j]['task_project_id']);
			$title = $project_name.' - '.$full_title;
			$full_title = $project_name.' - '.$full_title;
		}
		
		
		$task_type = "0";
		if($order_task[$j]['task_status_id']==$this->config->item('completed_id')){
			$completed += 1; // total completed task
			$task_type = "1";
			$schedulled += 1;
			if($task_type == "1"){
				$task_type = "1,3";
			} else {
				$task_type = "3";
			}
			if(strtotime($order_task[$j]['task_due_date']) == strtotime($date)){
				$due += 1;
				if($task_type == "1,3"){
					$task_type = "1,3,4";
				} else {
					$task_type = "3,4";
				}
			}
		} else if($order_task[$j]['task_due_date'] < date('Y-m-d')){
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
			$task_type = "3";
			if(strtotime($order_task[$j]['task_due_date']) == strtotime($date)){
				$due += 1;
				if($task_type == "3"){
					$task_type = "3,4";
				} else {
					$task_type = "4";
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
			
		/*
		if($order_task[$j]['task_color_code']){
					$color = $order_task[$j]['task_color_code'];
				}*/
				
		$chk = chk_task_exists($order_task[$j]['task_id']);
		
		if(get_task_color_code($order_task[$j]['color_id'])){
			$color = get_task_color_code($order_task[$j]['color_id']);
		}
		if(get_outside_color_code($order_task[$j]['color_id'])){
			$outside_color = get_outside_color_code($order_task[$j]['color_id']);
		}
                
                if($color_menu=='false'){
                    $color="#fff";
                    $outside_color="#e5e9ec";
                }
		
		$completed_depencencies = chk_dependency_status($order_task[$j]['task_id'],$this->config->item('completed_id'));
		if($completed_depencencies === 'red'){
			$move_class = 'unsorttd';
		}
		$cl = '';
		if($order_task[$j]['task_time_estimate'] == '0'){
			$cl = 'display:none;';
		}
		
		$cl3 = "";
		if($order_task[$j]['locked_due_date'] == "0"){
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
		
		if($order_task[$j]['is_personal'] == '1' && $order_task[$j]['task_owner_id'] != get_authenticateUserID()){
			$task_list_str .= '<div class="taskbox calicon'.$order_task[$j]['task_priority'].' unsorttd '.$task_type_class.' " style="background-color:'.$color.'; border:1px solid '.$outside_color.';">
						<div oncontextmenu="context_menu(\''.$order_task[$j]['task_id'].'\',\''.$order_task[$j]['locked_due_date'].'\',\''.change_date_format($order_task[$j]['task_due_date']).'\',\''.strtotime(str_replace(array("/"," ",","), "-", $date)).'\',\''.strtotime(str_replace(array("/"," ",","), "-", $month_start_date)).'\',\''.strtotime(str_replace(array("/"," ",","), "-", $month_end_date)).'\',\''.$order_task[$j]['master_task_id'].'\',\''.chk_master_task_id_deleted($order_task[$j]['master_task_id']).'\',\''.check_my_watch_list($order_task[$j]['task_id'],get_authenticateUserID()).'\',\''.$order_task[$j]['task_owner_id'].'\',\''.$completed_depencencies.'\',\''.$color_menu.'\');">
						<span class="task-desc"> Busy </span><div class="clearfix"> </div></div></div>';
		} else {
			$task_list_str .= '<div onclick="save_task_for_timer(this,\''.addslashes($order_task[$j]['task_id']).'\',\''.$order_task[$j]['task_title'].'\',\''.$order_task[$j]['task_time_spent'].'\',\''.$chk.'\',\''.$completed_depencencies.'\');" class="taskbox calicon'.$order_task[$j]['task_priority'].' month_master_'.$order_task[$j]['master_task_id'].' '.$move_class.' '.$task_type_class.' " style="background-color:'.$color.'; border:1px solid '.$outside_color.';" id="task_'.$order_task[$j]['task_id'].'">';
			$task_list_str .= '<div oncontextmenu="context_menu(\''.$order_task[$j]['task_id'].'\',\''.$order_task[$j]['locked_due_date'].'\',\''.change_date_format($order_task[$j]['task_due_date']).'\',\''.strtotime(str_replace(array("/"," ",","), "-", $date)).'\',\''.strtotime(str_replace(array("/"," ",","), "-", $month_start_date)).'\',\''.strtotime(str_replace(array("/"," ",","), "-", $month_end_date)).'\',\''.$order_task[$j]['master_task_id'].'\',\''.chk_master_task_id_deleted($order_task[$j]['master_task_id']).'\',\''.check_my_watch_list($order_task[$j]['task_id'],get_authenticateUserID()).'\',\''.$order_task[$j]['task_owner_id'].'\',\''.$completed_depencencies.'\',\''.$color_menu.'\');">';
			if($order_task[$j]['master_task_id']=='0'){
				$task_list_str .= '<a class="tooltips " data-original-title="'.$full_title.'" href="javascript:void(0)" onclick="edit_task(this,\''.$order_task[$j]['task_id'].'\',\''.$chk.'\')">';
			} else {
				$task_list_str .= '<a class="tooltips " data-original-title="'.$full_title.'" onclick="open_seris(this,\''.$order_task[$j]['task_id'].'\',\''.$order_task[$j]['master_task_id'].'\',\''.$chk.'\');" href="javascript:void(0)">';
			}
			$task_list_str .= '<span class="task-desc"> '.$title.'</span>
					<p class="task-hrs">
					<i style="'.$cl3.'" class="stripicon lockicon"></i>
					<span id="task_est_'.$order_task[$j]['task_id'].'" class="task-hrs" style="'.$cl.'"> '.minutesToTime($order_task[$j]['task_time_estimate']).' </span>
					</p>
                                        <input type="hidden" id="monthly_color_menu" value="<?php echo $color_menu;?>"/>
					<input type="hidden" id="task_data_'.$order_task[$j]['task_id'].'" value="'.htmlspecialchars(json_encode($order_task[$j])).'" />
					<input type="hidden" id="hdn_due_date_'.$order_task[$j]['task_id'].'" value="'.strtotime(str_replace(array("/"," ",","), "-", $order_task[$j]['task_due_date'])).'" />
					<input type="hidden" id="hdn_locked_due_date_'.$order_task[$j]['task_id'].'" value="'.$order_task[$j]['locked_due_date'].'" />
					<input type="hidden" id="or_color_'.$order_task[$j]['task_id'].'" name="or_color_id" value="'.$outside_color.'" />
					<input type="hidden" id="task_type_'.$order_task[$j]['task_id'].'" name="task_type" value="'.$task_type.'" />

					<input type="hidden" id="task_spent_'.$order_task[$j]['task_id'].'" name="task_spent_time" value="'.$order_task[$j]['task_time_spent'].'" />
					<input type="hidden" id="task_status_'.$order_task[$j]['task_id'].'" name="task_status_name" value="'.get_task_status_name_by_id($order_task[$j]['task_status_id']).'" />

					<div class="clearfix"> </div></a>
					</div>
				</div>
				';
			}
			$allocated += $order_task[$j]['task_time_estimate'];
						
			
			
			if($allocated > ($capacity)){
				$labalclass = 'redlabel';
			}
		
	}
	
	if($from == 'ajax'){
		$event_str .= '<div class="td-date unsorttd"> '.date($default_format,strtotime(str_replace(array("/"," ",","), "-", $date))).' '.$href.' </div>';
	} else {
		$event_str .= '<div class="td-date unsorttd"> <span class="weekday-txt"> WD'.$wd.'   </span> '.$actday.' '.$href.' </div>';
	}
	
	$overdueClass = "";
	if($overdue){
		$overdueClass = "txtred";
	}
	$event_str .= '<div class="task-list unsorttd" id="task_list_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'">
						<ul>
							<li> 
								<ul>
									<li> <div class="commonlabel">Capacity&nbsp;</div> </li>
									<li>  <div class="commonlabel" id="capacity_time_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"> '.minutesToTime($capacity).' </div></li>
								</ul>
							
							<li> 
								<ul>
									<li> <div class="commonlabel">Allocated</div> </li>
									<li>  <div class="commonlabel '.$labalclass.'" id="estimate_time_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"> '.minutesToTime($allocated).' </div></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="task-info unsorttd" id="task_info_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'">
						<ul><li> 
								<span class="tasklab-info">Overdue : </span>
								<span class="task-num '.$overdueClass.' overduehover" id="overdued_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"> '.$overdue.' </span>
							</li>
							<li> 
								<span class="tasklab-info">Due : </span>
								<span class="task-num duehover" id="due_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"> '.$due.' </span>
							</li>
							<li> 
								<span class="tasklab-info">Completed : </span>
								<span class="task-num completedhover" id="completed_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"> '.$completed.' </span>
							</li>
							<li> 
								<span class="tasklab-info">Scheduled :</span>
								<span class="task-num scheduledhover" id="scheduled_'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"> '.$schedulled.' </span>
							</li>
						</ul>
					</div>
					<div class="task-lable '.$sort_class.' full_task scroll_calender" id="'.strtotime(str_replace(array("/"," ",","), "-", $date)).'" style="padding-bottom:10px;">'.$task_list_str;
		
		$event_str .= '</div>';
		echo $event_str;
} else {
	$event_str = '';
	if($from == 'ajax'){
		$event_str = '<div class="td-date full_task  '.$sort_class.'" id="'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"  style="padding-bottom:20px;"> '.$date.' '.$href.' </div>';
	} else {
		$event_str = '<div class="td-date  '.$sort_class.'" id="'.strtotime(str_replace(array("/"," ",","), "-", $date)).'"  style="padding-bottom:20px;"> <span class="weekday-txt"> WD'.$wd.'   </span> '.$actday.' '.$href.' </div>';
	}
	
	echo $event_str;
}

?>