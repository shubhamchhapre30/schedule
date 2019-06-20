<link href="<?php echo base_url(); ?>calender4/style/calender_style.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>

<?php
$theme_url = base_url().getThemeName();
$cont = $this->uri->segment(1);
$fun = $this->uri->segment(2);
$user_colors = $color_codes;
$default_format = $site_setting_date;
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
date_default_timezone_set($this->session->userdata("User_timezone"));
$company_flags = $this->config->item('company_flags');
$actaul_time_on = '0';
$allow_past_task = "1";
if($company_flags){
	$actaul_time_on = $company_flags['actual_time_on'];
	$allow_past_task = $company_flags['allow_past_task'];
}
$user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id'));
?>



<script type="text/javascript">
    $(document).ready(function(){
        var a = $(window).height(),
            s = parseInt(a) - parseInt("240");
        $(".monthly-calendar_css").slimScroll({
            height: s,
            color: '#17A3E9',
            wheelStep: 10
        });
    });
var baseUrl='<?php echo base_url(); ?>';
</script>
<link href="<?php echo $theme_url; ?>/assets/css/jquery.qtip.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
<script src="<?php echo $theme_url ?>/assets/js/jquery.qtip.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo $theme_url ?>/assets/js/monthly-calendar-popover.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript">

$(function(){

	if($("#current_page").val()=="FiveWeekView"){
		$("#redirect_page").val($("#current_page").val());
	} else {
		$("#redirect_page").val('from_calendar');
	}


 	$(".full_task div").addClass("before_timer");

   popover();

   $('#task_actual_time').on('keypress', function( e ) {
		if( e.keyCode === 13 ) {
			e.preventDefault();
        	var a =$('#task_actual_time').blur()
        	if(a[0].value){
        		$("#frm_actual_time").submit();
        	} else {

        	}
        }
	});

   $("#task_actual_time").blur(function(){
		var val = $(this).val();

		var splitval = val.split(":");

		if(val){
			if(parseInt(val)>0){
				if(validate(val) == true) //   && (!$('#manual_reason').hasClass('in'))
				{
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
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(hh);
							$("#task_actual_time_min").val(mm);
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
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(hh);
							$("#task_actual_time_min").val(mm);
						}
					}
					if(val.length>=1 && val.length <=2)
					{
						if(val >= 60){
							var hh = parseInt(val / 60);
							var mm = val % 60;

							if(hh==0){
								var time = mm+"m";
							} else if(mm==0){
								var time = hh+"h";
							} else{
								var time = hh + "h "+ mm+"m";
							}
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(hh);
							$("#task_actual_time_min").val(mm);
						}else{
							var mm = val;
							var time = mm + "m";
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(0);
							$("#task_actual_time_min").val(mm);
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
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(hh);
							$("#task_actual_time_min").val(mm);

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
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(hh);
							$("#task_actual_time_min").val(mm);
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
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(hh);
							$("#task_actual_time_min").val(mm);

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
							$("#task_actual_time").val(time);
							$("#task_actual_time_hour").val(hh);
							$("#task_actual_time_min").val(mm);
						}
					}
					if(val.length>=5 && splitval.length!=2){
						$("input[name='task_time_spent']").val('');
						is_edited = '1';
						alertify.alert('maximum 4 digits allowed');
					}
				} else {
					$("#task_actual_time").val('');
					alertify.alert('your inserted value is not correct, please insert correct value');
				}
			} else {
				$("#task_actual_time").val('');
				alertify.alert('Please enter greater than 0 time.');
			}
		}
	});

   $("#frm_actual_time").validate({
		rules : {
			"task_actual_time" : {
				required : true
			}
		},
		errorPlacement: function (error, element) {
			error.insertAfter( element.parent("div") );
		},
		submitHandler:function(){
			var task_actual_time_task_id = $("#task_actual_time_task_id").val();
			$.ajax({
				type : 'post',
				url : '<?php echo site_url('calendar/add_actual_time');?>',
				data : $("#frm_actual_time").serialize()+ "&color_menu=" + $("#monthly_color_menu").val(),
				success : function(data){

					$("#task_"+task_actual_time_task_id).replaceWith(data);

					$("#actual_time_task").modal("hide");

				}
			});
		}
	});

   $(".close_actual_time_task").click(function(){
		var task_actual_time_task_id = $("#task_actual_time_task_id").val();
		$("#task_"+task_actual_time_task_id).find("input[type='checkbox']").prop('checked',false);
		$("#task_"+task_actual_time_task_id).find("span").removeClass('checked');
		$("#actual_time_task").modal("hide");
	});

});
</script>


<script src='<?php echo $theme_url; ?>/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?Ver=<?php echo VERSION;?>'></script>

<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/css/context.standalone.css?Ver=<?php echo VERSION;?>">
<script src="<?php echo $theme_url;?>/js/context.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>

<script type='text/javascript'>

var status = '';
	$(document).ready(function(){
$(document).on('hidden.bs.modal',"#comments_right", function(){
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove();
        });
	context.init({preventDoubleContext: false});

	context.settings({compress: true});

	$("#right_task_comment").limiter(<?php echo CMT_TEXT_SIZE;?>, $('#ch_cmt'));
	//alert("test");
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
            $("[data-toggle=tooltip]").tooltip('hide');
            return $(element).clone().addClass('dragging');
        },
        start: function (e, ui) {
                $("[data-toggle=tooltip]").tooltip('hide');
        },
         update : function (e, ui) {
$("[data-toggle=tooltip]").tooltip('hide');
        	var date = $(this).attr('id');

        	var order = $('#'+date).sortable('serialize');
        	//alert(order);
        	var scope_id = ui.item.show().attr('id');

        	scope_id = scope_id.replace('task_', '');

        	var orig_data = $('#task_data_'+scope_id).val();

			var URL = '<?php echo site_url('calendar/setOrder') ?>';
	      	//$('#dvLoading').fadeIn('slow');

	      	 $('.sortable').sortable("disable");
	      	$("#task_"+scope_id).addClass("pulsate");

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
								data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),month_color_menu:$("#monthly_color_menu").val()},
								success : function(task_detal){
									$("#task_"+scope_id).replaceWith(task_detal);
								}
							});

						}
					} else {
					}

					$('.sortable').sortable("enable");
				   $("#task_"+scope_id).removeClass("pulsate");
				},
			});
	    },
	    stop: function (e, ui) {
$("[data-toggle=tooltip]").tooltip('hide');
        },

        receive: function( e, ui ) {
$("[data-toggle=tooltip]").tooltip('hide');
        	var date = $(this).attr('id');


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
										data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),month_color_menu:$("#monthly_color_menu").val()},
										success : function(task_detal){
											$("#task_"+scope_id).replaceWith(task_detal);
										}
									});

								}
							} else {
							}

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

        	$('.sortable').sortable("disable");
	      	$("#task_"+scope_id).addClass("pulsate");
        	var URL = '<?php echo site_url('calendar/UpdateScope') ?>';
			$.ajax({
				url:URL,
				type:'POST',
				data:{'task_data': orig_data,'scope_id':scope_id, 'date':date},
				success : function(responsedata) { 
					var responsedata = jQuery.parseJSON(responsedata);

				    var task_type = $("#task_type_"+scope_id).val();

//					if($("#"+came_from_id+" .taskbox").length == 0) {
//						$("#task_list_"+came_from_id).remove();
//						$("#task_info_"+came_from_id).remove();
//					} else {

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
					

					if(is_data_available == 0){
						var wd = $("#td_"+date+" .weekday-txt").html();
						wd = wd.replace('WD ','');
						$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/monthly_day_view");?>',
							data : {date : date, task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),'wd':wd,color_menu:$("#monthly_color_menu").val()},
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
							data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val(),month_color_menu:$("#monthly_color_menu").val()},
							success : function(task_detal){
								$("#task_"+scope_id).replaceWith(task_detal);
							}
						});
					}
					$("#task_"+responsedata.id).addClass("pulsate");
					$('.sortable').sortable("enable");
					$("#task_"+responsedata.id).removeClass("pulsate");
				}
			});
        },

        cursor: 'move',

	}).disableSelection();

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
		$('.scroll_calender').slimScroll({
			color: '#17A3E9',
			height : '120px',
	 	    wheelStep: 15,

	 	});

	} else {
		$(".task-lable").css('display','none');
		$('.scroll_calender').slimScroll({
				destroy:true
		 });
	}





	$("#calender_team_user_id").change(function(){

		var user_id = $(this).val();
		var str = $('#last_remember').serialize();
		$('#dvLoading').fadeIn('slow');
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/searchTask"); ?>',
			data : {str:str,year:$("#year").val(),month:$("#month").val()},
			success : function(data){
				$("#sjcalendar").html(data);


				if($("#calender_team_user_id").val() != <?php echo get_authenticateUserID();?>){
					$("#calender_team_user_id").parents('li').children('a').addClass('filter_selected');
					$("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#calender_team_user_id").parents('li').children('a').removeClass('filter_selected');
					$("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon');
				}

				var CAL_SESSION_ID = $("#calender_team_user_id").val();
            	$('#dvLoading').fadeOut('slow');
			}
		});
	});

	$(".right_cmt_close").click(function(){
		$("#comments_right").modal("hide");
                $("#comments_right").on('hidden.bs.modal', function(){
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
        });
	});




	$("#right_cmt").validate({
		rules : {
			"right_task_comment" : {
				required : true
			}
		},
		submitHandler:function(){
			$("#right_cmt_btn").attr("disabled","disabled");
			var cmt_task_id = $("#right_comment_task_id").val();
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("calendar/add_comment");?>',
				data : $("#right_cmt").serialize()+ "&color_menu=" + $("#monthly_color_menu").val(),
				success : function(data){
					$("#task_"+cmt_task_id).replaceWith(data);
					$("#right_cmt_btn").removeAttr("disabled","disabled");
					$("#comments_right").modal("hide");
                                        $("#comments_right").on('hidden.bs.modal', function(){
                                            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
                                        })
				}
			});
		}
	});

	$('input[name="show_cal_view[]"]').click(function(){
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

		var val = [];
        $('input[name="show_cal_view[]"]:checkbox:checked').each(function(i){
          val[i] = $(this).val();
        });
       $('#dvLoading').fadeIn('slow');
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("calendar/saveShowTask"); ?>',
				data : $('#last_remember_calender').serialize(),
				success : function(data){
					$('#dvLoading').fadeOut('slow');
				}
			});
        } else {
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("calendar/saveShowTask"); ?>',
				data : $('#last_remember_calender').serialize(),
				success : function(data){
					$('#dvLoading').fadeOut('slow');
				}
			});
        }
	});




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
{		

	var action    = (action != null) ? action : 'view';
	var view_type = (view_type != null) ? view_type : 'monthly';
	var year      = (year != null) ? year : '2014';
	var month     = (month != null) ? month : '07';
	var day       = (day != null) ? day : '23';

    $('#dvLoading').fadeIn('slow');

	var calendar_url =  '<?php echo base_url().'calendar/calendarview_ajx' ?>?action='+action+'&view_type='+view_type+'&year='+year+'&month='+month+'&day='+day;

	$.ajax({
            type: "POST",
            url: calendar_url,
            success: function(data) {
                $("#sjcalendar").html(data);
                $('#dvLoading').fadeOut('slow');
                 cal_fill();
            }
        });
}


function openpopup(task_id,orig_data){
	$("#right_task_comment").val('');
	$("#comments_right").modal("show");
	$("#right_comment_task_id").val(task_id);
    $("#comments_right").on('shown.bs.modal', function(){
        jQuery().wysihtml5&&$("#right_task_comment").size()>0&&$("#right_task_comment").wysihtml5({stylesheets:["../default/assets/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]})
		$(this).find('#right_task_comment').focus();
	});
	$('#task_data').val(orig_data);
}
function save_last_calender_view(val){
	if(val){
		$.ajax({
			type : 'post',
			url : '<?php echo site_url('calendar/save_calender_view'); ?>',
			data : {val : val},
			success : function(data){
			}
		});
	}
}

function save_task_for_timer(obj,id,title,time,chk){
	if($(obj).hasClass("before_timer")){
		return false;
	}
	if(chk != '1'){

		var post_data = $("#task_data_"+id).val();
		$('#dvLoading').fadeIn('slow');
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/save_task");?>',
			data : { post_data : post_data, scope_id : id},
			success : function(task_id){
				var task_id1 = task_id;
				$("#timer_task_id").val(task_id1);

				$.ajax({
					type : 'post',
					url : '<?php echo site_url("calendar/set_update_task");?>',
					data : {task_id : task_id, year:$("#year").val(),month:$("#month").val(),month_color_menu:$("#monthly_color_menu").val()},
					success : function(task_detal){
						$("#task_"+id).replaceWith(task_detal);
						var bor = $("#or_color_"+task_id1).val();
						$("#task_"+task_id1).css("border","1px dashed "+bor);
					}
				});
				$('#dvLoading').fadeOut('slow');
			}
		});
		var time = 0;
	} else {
		$("#timer_task_id").val(id);
		var bor = $("#or_color_"+id).val();
		$("#task_"+id).css("border","1px dashed "+bor);
	}

	$(".full_task div").addClass("before_timer");
	$(".taskbox > a").removeClass("after_timer_on");


	var inner_a = $("#"+obj.id).find('a').attr('href');
	$(inner_a).modal('hide');


	setTimeout(function(){
		chk_task_selected(title,time);
	}, 2000);

}


function opendelete(task_id,master_task_id,task_due_date,active_menu,date){ 
	$("#delete_series span").removeClass("checked");
	$("#delete_ocuurence span").removeClass("checked");
        $("#delete_future span").removeClass("checked");
	$("#delete_series").attr("onclick","delete_rightClick_task('"+master_task_id+"','"+task_due_date+"','"+date+"','series','"+task_id+"')");
	$("#delete_ocuurence").attr("onclick","delete_rightClick_task('"+task_id+"','"+task_due_date+"','"+date+"','ocuurence','"+"')");
        $("#delete_future").attr("onclick","delete_rightClick_task('"+master_task_id+"','"+task_due_date+"','"+date+"','future','"+task_id+"')");
	$("#delete_task").modal("show");
}

function delete_rightClick_task(id,task_due_date,date,from,task_id){

	var from = from || 1;
	var task_id1 = task_id || id;

	var orig_data = $('#task_data_'+id).val();


	$.ajax({
		type : 'post',
		url : '<?php echo site_url("calendar/delete_task");?>',
		data : { task_id : id, task_data:orig_data, due_date : task_due_date, year:$("#year").val(),month:$("#month").val(),from:from,current_date:$("#current_date").val()},
		success : function(data){
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

			var task_type = $("#task_type_"+task_id1).val();
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
			if(from == "ocuurence")
                        {
                            $("#task_"+task_id1).remove();
                        }
			if(from == "future"){

                                var task_pos =data;
				var Length_task = parseInt($(".month_master_"+id).length);

				for(i=0;i<Length_task;i++){
					var parent_div_id = $(".month_master_"+id).parent('div').attr('id');
                                        var task_id2 = $(".month_master_"+id).attr('id');
                                        var task_pos2 = task_id2.substring(task_id2.lastIndexOf("_") + 1);
                                        var first_task_pos = Number(i) + Number(task_pos2);
                                        var new_id = "task_child_"+id+"_"+first_task_pos;
                                        var new_pos = new_id.substring(new_id.lastIndexOf("_")+1);
					if(parseInt(new_pos) > task_pos){
                                            $("#"+new_id).remove();
                                        }
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
								if(task_type1[1]!=undefined){
									var came_from_schedule = $("#scheduled_"+parent_div_id).html();
									if(came_from_schedule>0){
										$("#scheduled_"+parent_div_id).html(parseInt(came_from_schedule)-1);
									}
								}
								if(task_type1[2]!=undefined){
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
								if(task_type1[1]!=undefined){
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
								if(task_type1[1]!=undefined){
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
			if(from == "series"){
                            $("#task_"+task_id1).remove();
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
								if(task_type1[1]!=undefined){
									var came_from_schedule = $("#scheduled_"+parent_div_id).html();
									if(came_from_schedule>0){
										$("#scheduled_"+parent_div_id).html(parseInt(came_from_schedule)-1);
									}
								}
								if(task_type1[2]!=undefined){
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
								if(task_type1[1]!=undefined){
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
								if(task_type1[1]!=undefined){
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
			alertify.set('notifier','position', 'top-right');
			alertify.log("Task has been deleted successfully.");
		}
	});
}


function move_task1(selected_date,locked_due_date,date,task_id,task_due_date,month_start_date,month_end_date){

	function pad(s) { return (s < 10) ? '0' + s : s; }
	var d = new Date(selected_date);

	var sel_date = [d.getFullYear(), pad(d.getMonth()+1), pad(d.getDate())].join('-');

	var is_locked = locked_due_date;
	if(is_locked == "1"){
		var droppd_dt = [d.getFullYear(), pad(d.getMonth()+1), pad(d.getDate())].join('-');
		if( (new Date(droppd_dt).getTime() > new Date(task_due_date).getTime()))
		{
			alertify.alert("Sorry, you can only move the task into prior or equal due date");
      		return false;
		}
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

	var orig_data = $('#task_data_'+task_id).val();

	$.ajax({
		type : 'post',
		url : '<?php echo site_url("calendar/move_task");?>',
		data : {task_id : task_id, due_date : task_due_date, sel_date : sel_date, task_data : orig_data},
		success : function(responsedata){
                        
			var responsedata = jQuery.parseJSON(responsedata);

			if(responsedata.date != date){
				var task_type = $("#task_type_"+task_id).val();
				$("#task_"+task_id).remove();
				if((responsedata.date >= month_start_date) && (responsedata.date <= month_end_date)){

					if($("#task_list_"+responsedata.date).length == 0) {
		      			var is_data_available = 0;
		      		} else {
		      			var dropped_estimate = get_minutes($("#estimate_time_"+responsedata.date).html());
			      		var dropped_capacity = $("#capacity_time_"+responsedata.date).html();
						var d_h_index = dropped_capacity.indexOf("h");
						var dropped_capacity_time = dropped_capacity.substr(0,d_h_index);
						var is_data_available = 1;
		      		}

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

					if(is_data_available == 1){
						

						var today_date_time = '<?php echo strtotime(date("Y-m-d"));?>';

						if(task_type){
							task_type1 = task_type.split(",");
							if(task_type1[0] == "1"){
								var dropped_completed = $("#completed_"+responsedata.date).html();
								$("#completed_"+responsedata.date).html(parseInt(dropped_completed)+1);
								var dropped_schedule = $("#scheduled_"+responsedata.date).html();
								$("#scheduled_"+responsedata.date).html(parseInt(dropped_schedule)+1);
								if(responsedata.date == today_date_time){
									var dropped_due = $("#due_"+responsedata.date).html();
									$("#due_"+responsedata.date).html(parseInt(dropped_due)+1);
								}
							} else if(responsedata.date<today_date_time){
								var dropped_overdued = $("#overdued_"+responsedata.date).html();
								$("#overdued_"+responsedata.date).html(parseInt(dropped_overdued)+1);
								var dropped_schedule = $("#scheduled_"+responsedata.date).html();
								$("#scheduled_"+responsedata.date).html(parseInt(dropped_schedule)+1);
							} else {
								var dropped_schedule = $("#scheduled_"+responsedata.date).html();
								$("#scheduled_"+responsedata.date).html(parseInt(dropped_schedule)+1);
								if(responsedata.date == today_date_time){
									var dropped_due = $("#due_"+responsedata.date).html();
									$("#due_"+responsedata.date).html(parseInt(dropped_due)+1);
								}
							}
						}

						var dropped_min = parseInt(dropped_estimate)+parseInt(scope_time_estimate);
						var dropped_est = hoursminutes(dropped_min);
						$("#estimate_time_"+responsedata.date).html(dropped_est);

						$("#estimate_time_"+responsedata.date).removeAttr('class');
						if(dropped_min>(dropped_capacity_time*60)){
							$("#estimate_time_"+responsedata.date).attr('class','commonlabel redlabel');
						} else {
							$("#estimate_time_"+responsedata.date).attr('class','commonlabel');
						}
					}
		      	}

				if(is_data_available == 0){
					var wd = $("#td_"+responsedata.date+" .weekday-txt").html();
					wd = wd.replace('WD ','');
					$.ajax({
						type : 'post',
						url : '<?php echo site_url("calendar/monthly_day_view");?>',
						data : {date : responsedata.date, task_id : responsedata.task_id, year:$("#year").val(),month:$("#month").val(),'wd':wd,color_menu:$("#monthly_color_menu").val()},
						success : function(data){
							$("#td_"+responsedata.date).html(data);
						}
					});
				} else {
					$.ajax({
						type : 'post',
						url : '<?php echo site_url("calendar/set_update_task");?>',
						data : {task_id : responsedata.task_id, year:$("#year").val(),month:$("#month").val(),month_color_menu:$("#monthly_color_menu").val()},
						success : function(task_detal){
							$("#"+responsedata.date).append(task_detal);
						}
					});

				}
			}
		}
	});
}

function right_click_delete(task_id,task_due_date,active_menu,date){
	var ans = "Are you sure, you want to delete this task?";
//	alertify.confirm(ans,function(r){
//		if (r == true) {

			var orig_data = $('#task_data_'+task_id).val();

			$.ajax({
				type : 'post',
				url : '<?php echo site_url("calendar/delete_task");?>',
				data : { task_id : task_id, task_data: orig_data, due_date : task_due_date, year:$("#year").val(),month:$("#month").val(),current_date:$("#current_date").val(),form:"delete"},
				success : function(data){
                                    var data = jQuery.parseJSON(data);

                                                                                
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
//					alertify.set('notifier','position', 'top-right');
//					alertify.log("Task has been deleted successfully.");
                                        toastr['success']("Task '"+data.task_title+"' has been deleted.", "");
				}
			});
//		} else {
//		    return false;
//		}
//	});
}

/*function set_priority(task_id,value){
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
}*/

function rightClickChangeStatus(task_id,status_id,status_name,date,task_due_date,dependency_status){
	if(dependency_status === 'red')
	{
		alertify.alert('You cannot change status of the main task as its dependent tasks are still not completed.');
		return false;
	}

	var old_status = $("#task_status_"+task_id).val();
	var orig_data = $('#task_data_'+task_id).val();

	if('<?php echo $actaul_time_on; ?>' == "1"){
		if(status_id == '<?php echo $this->config->item('completed_id');?>'){

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
		data : {task_id : task_id, status_id:status_id,task_data : orig_data, year:$("#year").val(),month:$("#month").val(),color_menu:$("#monthly_color_menu").val()},
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
				var task_du_date_time = $("#hdn_due_date_"+task_id).val();
				if(status_name == "Completed"){
					$("#completed_"+date).html(parseInt($("#completed_"+date).html())+1);
					$("#task_type_"+task_id).val(1);
					$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
					if($("#task_type_"+task_id).val() == "1"){
						$("#task_type_"+task_id).val(1,3);
					} else {
						$("#task_type_"+task_id).val(3);
					}

					if(task_du_date_time == date){
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
					if(task_du_date_time==date){
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
			alertify.set('notifier','position', 'top-right');
			alertify.log("Task status has been changed successfully.");
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
			alertify.set('notifier','position', 'top-right');
			alertify.log("Swimlane has been changed successfully.");
		}
	});
}


function rightClickSetDueDate(selected_date,task_id,date,task_due_date){
	function pad(s) { return (s < 10) ? '0' + s : s; }
	var d = new Date(selected_date);
	var sel_date = [d.getFullYear(), pad(d.getMonth()+1), pad(d.getDate())].join('-');

	var a = new Date(date*1000);
	var due = [a.getFullYear(), pad(a.getMonth()+1), pad(a.getDate())].join('-');


	var orig_data = $('#task_data_'+task_id).val();
	$.ajax({
		type: 'post',
		url: '<?php echo site_url("calendar/set_task_due_date");?>',
		data : {task_id : task_id, due_date : sel_date, task_data : orig_data, year:$("#year").val(),month:$("#month").val()},
		async : false,
		success : function(data){
			data = jQuery.parseJSON(data);


				$.ajax({
					type : 'post',
					url : '<?php echo site_url("calendar/set_update_task");?>',
					data : {task_id : data.task_id, year:$("#year").val(),month:$("#month").val(),month_color_menu:$("#monthly_color_menu").val()},
					async:false,
					success : function(task_detal){
						$("#task_"+task_id).replaceWith(task_detal);
						task_id = data.task_id;
					}
				});

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

			var task_du_date_time = $("#hdn_due_date_"+task_id).val();
			if($("#task_status_"+task_id).val() == "Completed"){
				$("#completed_"+date).html(parseInt($("#completed_"+date).html())+1);
				$("#task_type_"+task_id).val(1);
				$("#scheduled_"+date).html(parseInt($("#scheduled_"+date).html())+1);
				if($("#task_type_"+task_id).val() == "1"){
					$("#task_type_"+task_id).val(1,3);
				} else {
					$("#task_type_"+task_id).val(3);
				}

				if(sel_date == due){
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
				if(sel_date == due){
					$("#due_"+date).html(parseInt($("#due_"+date).html())+1);
					if($("#task_type_"+task_id).val() == "3"){
						$("#task_type_"+task_id).val(3,4);
					} else {
						$("#task_type_"+task_id).val(4);
					}
				}
			}
			alertify.set('notifier','position', 'top-right');
			alertify.log("Task due date has been changed successfully.");
		}
	});
}



</script>

<div class="container-fluid page-background" style="padding-right: 20px;padding-left: 20px;">
	<div class="mainpage-container">

		<div class="user-block">
			 <div class="row">
				<div class="col-md-12">

					<!-- ############################## -->
                                        <div class="calendartop clearfix form-horizontal" style="margin-right: 5px;">

							<form name="last_remember_calender" id="last_remember_calender" class="no-margin" action="" />
								 <div class="form-group no-margin">
								 	<?php
								 		if($last_rember_values){
											$show_cal_view = $last_rember_values->show_cal_view;
											$calender_sorting = $last_rember_values->calender_sorting;
										} else {
											$show_cal_view = '';
											$calender_sorting = '1';
										}
										if($show_cal_view){
									 		$show_cal = explode(',', $show_cal_view);
									 	} else {
									 		$show_cal = array();
									 	}
									?>
									<div class="row">
										<div class="col-md-6" id="month_last_remeber">
											<div class="controls">
												<label class="checkbox">
                                                                                                    <input type="checkbox" class="newcheckbox_task" id="show_capacity" <?php if(in_array('1', $show_cal)){ echo 'checked="checked"'; } ?> name="show_cal_view[]" value="1" /> Show Capacity
												</label>
												<label class="checkbox">
                                                                                                    <input type="checkbox" class="newcheckbox_task" id="show_summary" <?php if(in_array('2', $show_cal)){ echo 'checked="checked"'; } ?> name="show_cal_view[]" value="2" /> Show Summary
												</label>
												<label class="checkbox">
                                                                                                    <input type="checkbox" class="newcheckbox_task" id="show_task" <?php if(in_array('3', $show_cal)){ echo 'checked="checked"'; } ?> name="show_cal_view[]" value="3" /> Show Task
												</label>
											</div>
										</div>

									</div>
								</div>
							</form>

							</div>
					<!-- ############################# -->


		             <div id="sjcalendar" style="overflow-x: hidden;">

                                        <input type="hidden" name="year" id="year" value="<?php echo $year;?>" />
					<input type="hidden" name="month" id="month" value="<?php echo $month; ?>" />
                                        <input type="hidden" name="current_date" id="current_date" value="<?php echo date("Y-m-d") ?>"/>
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
				    ## *** set start day of week: from 1 (Sunday) to 7 (Saturday)
				    $objCalendar->SetWeekStartedDay("1");


					## +---------------------------------------------------------------------------+
				    ## | 3. Draw Calendar:                                                         |
				    ## +---------------------------------------------------------------------------+
				    //echo "========>".$this->session->userdata("User_timezone");

					$objCalendar->Show($calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,$capacity,$default_format,$company_flags,$this->config->item('completed_id'));
					date_default_timezone_set("UTC");
	  				?>

	  			</div>
					</div>
				</div>
			</div>
      </div>



	<div id="comments_right" class="modal model-size fade commentbox-size" tabindex="-1" >
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				<div class="modal-header">
					<button type="button" class="close right_cmt_close" data-dismiss="modal" aria-hidden="true"></button>
					<h3>Comments</h3>
				</div>
				<div>
					<form name="right_cmt" id="right_cmt" action="" >
						<div class="addcomment-block">
							<div class="row">
								<div class="col-md-12 ">
									<div class="form-group">
										<label class="control-label" for="firstName"> <strong> Add Comment :<span class="required">*</span> </strong></label>
										<div class="controls">
											<textarea rows="3" name="right_task_comment" maxlength="<?php echo CMT_TEXT_SIZE;?>" id="right_task_comment" class="col-md-12 m-wrap"></textarea>
										  </div>
									</div>
									<span class="chr">Char left :- <i id="ch_cmt"><?php echo CMT_TEXT_SIZE;?></i></span>
                                                                        <div class="pull-right" style="margin-top:10px;">
										<input type="hidden" name="redirect_page" value="myCalender" />
										<input type="hidden" name="task_data" id="task_data" value="" />
										<input type="hidden" name="task_id" id="right_comment_task_id" value="" />
										<button type="submit" id="right_cmt_btn" class="btn blue txtbold"> Add Comments </button>
									</div>
								</div>
							 </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="delete_task" class="modal model-size pro-change fade" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Delete Task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
                            <div class="portlet-body  form flip-scroll" style="padding: 10px;">

					<div class="form-group">
                                            <label class="control-label col-md-12" style="padding-left:0px;">Do you want to delete the series, this occurence or only future tasks?</label>
					        <div class="controls">
							<label class="radio">
								<a id="delete_series" href="javascript:void(0);" ><input type="radio" value="" ></a>Task Series
							</label>
							<label class="radio">
								<a id="delete_ocuurence" href="javascript:void(0);" ><input type="radio" value="" ></a>Task Occurrence
							</label>
                                                        <label class="radio">
								<a id="delete_future" href="javascript:void(0);" ><input type="radio" value="" ></a>Future Tasks
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="actual_time_task" class="modal model-size actual-time fade customecontainer" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close close_actual_time_task" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Actual time of task  </h3>
		</div>
		<div class="modal-body">
                    <div class="portlet" style="padding:10px;">
				<div class="portlet-body  form flip-scroll">
					<form name="frm_actual_time" id="frm_actual_time" method="post">
						<div class="form-group">
							<label class="control-label">Enter Actual Time : </label>
							<div class="controls">
								<input class="onsub m-wrap m-ctrl-small small_input" name="task_actual_time" id="task_actual_time" placeholder="0h" value="" type="text"  tabindex="1" /><span class="word_set">time (ex. 130 for 1h30)</span>
								<input type="hidden" name="task_actual_time_hour" id="task_actual_time_hour" value="" />
								<input type="hidden" name="task_actual_time_min" id="task_actual_time_min" value="" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<input type="hidden" name="task_id" id="task_actual_time_task_id" value="" />
								<input type="hidden" name="task_data" id="task_actual_time_task_data" value="" />
								<input type="hidden" name="redirect_page" id="task_actual_time_redirect_page" value="" />
								<button type="submit" class="btn blue txtbold"> Save </button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="series_task_deletion" class="modal model-size pro-change fade" tabindex="-1">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3> Delete Task  </h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
						<div class="portlet-body  form flip-scroll">

							<div class="form-group"  style="padding:10px;">
                                                            <label class="control-label col-md-12" style="padding-left:0px;">Do you want to delete the entire series or future tasks only?</label>

								<div class="controls">
									<label class="radio ">
                                                                           <input type="radio" name="series_option" value="series" onclick="delete_series_task()">Task Series
									</label>
									<label class="radio ">
                                                                          <input type="radio" name="series_option" value="future" onclick="delete_series_task()">Future Tasks
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

    <!-- END PAGE CONTAINER-->
</div>
