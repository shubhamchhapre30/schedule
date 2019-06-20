<?php 
$theme_url = base_url().getThemeName(); 
$cont = $this->uri->segment(1); 
$fun = $this->uri->segment(2);
date_default_timezone_set($this->session->userdata("User_timezone"));
$user_colors = $color_codes; 
$user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id'));
?>

<script src='<?php echo base_url().getThemename(); ?>/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?Ver=<?php echo VERSION;?>'></script>
<script type="text/javascript" src="<?php echo base_url().getThemename(); ?>/assets/plugins/prettify.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemename(); ?>/assets/plugins/jquery.slimscroll.js?Ver=<?php echo VERSION;?>"></script> 
<script src="<?php echo $theme_url ?>/assets/js/monthly-calendar-popover.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript">


$(function(){
	
 	$("#month_last_remeber").show();
	$("#week_last_remeber").hide();
 	$(".full_task div").addClass("before_timer");
 	
	var pre_id = $("#timer_task_id").val();
	if(pre_id){
		var bor_pr = $("#or_color_"+pre_id).val();
		$("#task_"+pre_id).css("border","1px dashed "+bor_pr);
	}
});


</script> 

<script type="text/javascript">
  $(document).ready(function(){
      var a = $(window).height(),
          s = parseInt(a) - parseInt("240");
    $(".monthly-calendar_css").slimScroll({
               height: s,
               wheelStep: 20,
               color: 'rgb(23, 163, 233)'
            });
})
$(function(){
	popover();
   
});

</script> 
<!--<script src="<?php echo base_url().getThemename(); ?>/assets/scripts/app.js?Ver=<?php echo VERSION;?>"></script>-->

<script type='text/javascript'>

var status = '';
	$(document).ready(function(){
		App.init();
	//alert("Upeksha");
	//alert($("#show_capacity").is(':checked'));
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
        	
        	scope_id = scope_id.replace('task_', '');
        	
        	var orig_data = $('#task_data_'+scope_id).val();
			
			var URL = '<?php echo site_url('calendar/setOrder') ?>';
	      	//$('#dvLoading').fadeIn('slow');
	      	
			$.ajax({
				url:URL,
				type:'POST',
				data:{'order':order,'date':date, 'scope_id':scope_id, task_data : orig_data},
				success : function(responsedata) {
					
					if(order){
						if(responsedata == "no_data"){
							var responsedata = jQuery.parseJSON(responsedata);
							
							$.ajax({
								type : 'post',
								url : '<?php echo site_url("calendar/set_update_task");?>',
								data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val()},
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
			})
	    }, 
	    stop: function (e, ui) {
            
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
										data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val()},
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
							data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val()},
							success : function(task_detal){
								$("#task_"+scope_id).replaceWith(task_detal);
							}
						});
					}
					
				}
			});		
        },

        cursor: 'move',
       
	}).disableSelection();
	
	
});
function JumpToDate()
{
	var jump_day   = (document.getElementById('jump_day')) ? document.getElementById('jump_day').value : '';
	var jump_month = (document.getElementById('jump_month')) ? document.getElementById('jump_month').value : '';
	var jump_year  = (document.getElementById('jump_year')) ? document.getElementById('jump_year').value : '';
	var view_type  = (document.getElementById('view_type')) ? document.getElementById('view_type').value : '';
						
	__doPostBack('view', view_type, jump_year, jump_month, jump_day);
 }
				
function __doPostBack(action, view_type, year, month, day)
{		$('#dvLoading').fadeIn('slow');
	var action    = (action != null) ? action : 'view';
	var view_type = (view_type != null) ? view_type : 'monthly';
	var year      = (year != null) ? year : '2014';
	var month     = (month != null) ? month : '07';
	var day       = (day != null) ? day : '23';
   
   
	var calendar_url =  '<?php echo base_url().'calendar/calendarview_ajx' ?>?action='+action+'&view_type='+view_type+'&year='+year+'&month='+month+'&day='+day;		
	
	$.ajax({
            type: "POST",          
            url: calendar_url,
            success: function(data) {//alert(html);
                $("#sjcalendar").html(data);
                $('#dvLoading').fadeOut('slow');
            }
        });
}
</script>

		           <input type="hidden" name="year" id="year" value="<?php echo $year;?>" />
					<input type="hidden" name="month" id="month" value="<?php echo $month; ?>" />
		              <?php 
	  				
					require_once(getcwd ()."/calender4/calendar.class.php");
					// create calendar object
                    $objCalendar = new Calendar2();
    
				    ## +---------------------------------------------------------------------------+
				    ## | 2. General Settings:                                                      | 
				    ## +---------------------------------------------------------------------------+

				    ## *** set calendar width and height
				    $objCalendar->SetCalendarDimensions("800px", "500px");
				    ## *** set week day name length - "short" or "long"
				    $objCalendar->SetWeekDayNameLength("long");
				    ## *** set start day of week: from 1 (Sanday) to 7 (Saturday)
				    $objCalendar->SetWeekStartedDay("1");
				    
				
				    ## +---------------------------------------------------------------------------+
				    ## | 3. Draw Calendar:                                                         | 
				    ## +---------------------------------------------------------------------------+
				   
				    $objCalendar->Show($calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,$capacity,$this->config->item('company_default_format'),$this->config->item('company_flags'),$this->config->item('completed_id'));
					date_default_timezone_set("UTC");
	  				?>
	  			

    <!-- END PAGE CONTAINER-->
	
	