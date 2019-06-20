<?php
/*﻿<!-- BEGIN FOOTER -->*/
$theme_url = base_url().getThemeName(); 
$uriseg=uri_string();
$uri=explode('/',$uriseg);
$method = $this->uri->segment(1);
$fun =   $this->uri->segment(2);
$total_project = get_user_total_project(); 
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
if($date_arr_java[$site_setting_date]=='dd M,yyyy'){
    $size=11;
}else{
    $size=10;
}
$new_date = date("Y-m-d H:i:s");
date_default_timezone_set($this->session->userdata("User_timezone")); 
$date_to_timezone = date($site_setting_date,strtotime(toDateNewTime($new_date)));
date_default_timezone_set("UTC"); 
$s3_display_url = $this->config->item('s3_display_url');
	$bucket = $this->config->item('bucket_name');
        
    
?>

<div class="footer">
	<div class="footer-inner"> &copy; <?php echo date('Y'); ?> Schedullo. All rights reserved. </div>

    <?php if($method == "kanban" || $method == "calendar"){

		$completed_id = $this->config->item('completed_id');
		$is_administrator = $this->session->userdata('is_administrator');
		$is_manager = $this->session->userdata('is_manager');
		//pr($last_rember_values);die;
		if($last_rember_values){
			$kanban_project_id = $last_rember_values->kanban_project_id;
			$calender_project_id = $last_rember_values->calender_project_id;
			$rem_task_status_id = $last_rember_values->task_status_id;
			$due_task = $last_rember_values->due_task;
			$kanban_team_user_id = $last_rember_values->kanban_team_user_id;
			$calender_team_user_id = $last_rember_values->calender_team_user_id;
			$sidbar_collapsed = $last_rember_values->sidbar_collapsed;
			$last_calender_view = $last_rember_values->last_calender_view;
			$calender_sorting = $last_rember_values->calender_sorting;
			$user_color_id = $last_rember_values->user_color_id;
			$cal_user_color_id = $last_rember_values->cal_user_color_id;
		} else {
			$kanban_project_id = '';
			$calender_project_id = '';
			$rem_task_status_id = '';
			$due_task = '';
			$kanban_team_user_id = '';
			$calender_team_user_id = '';
			$sidbar_collapsed = '0';
			$last_calender_view = '1';
			$calender_sorting = '1';
			$user_color_id = '0';
			$cal_user_color_id = '0';
		}


		 ?>

		<div class="footer-filter">
	  		<script type="text/javascript">
	  		$(document).ready(function(){
                                                              
	  			$("#timer_task_title").hide();
                              
                                
                                


				elem = $("input[name='left_task_status_id[]']");
				if($("#left_task_status_all").prop("checked") == true){
					for(i=0;i<elem.length;i++){
						$(elem[i]).prop("checked",true);
						$(elem[i]).closest('span').addClass('checked');
					}
					$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
					$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
				}else{
					$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
					$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
					$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
				}

				if($("#user_color_id").val() != 0){
					$("#user_color_id").parents('li').children('a').addClass('filter_selected');
					$("#user_color_id").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#user_color_id").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#user_color_id").parents('li').children('a').removeClass('filter_selected');
					$("#user_color_id").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#user_color_id").parents('li').children('a').children('i').addClass('filtericon');
				}


				if($("#cal_user_color_id").val() != 0){
					$("#cal_user_color_id").parents('li').children('a').addClass('filter_selected');
					$("#cal_user_color_id").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#cal_user_color_id").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#cal_user_color_id").parents('li').children('a').removeClass('filter_selected');
					$("#cal_user_color_id").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#cal_user_color_id").parents('li').children('a').children('i').addClass('filtericon');
				}

                                if($("#calender_project_id").val() != 'all'){
					$("#calender_project_id").parents('li').children('a').addClass('filter_selected');
					$("#calender_project_id").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#calender_project_id").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#calender_project_id").parents('li').children('a').removeClass('filter_selected');
					$("#calender_project_id").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#calender_project_id").parents('li').children('a').children('i').addClass('filtericon');
				}

				if($("#calender_team_user_id").val() != <?php echo get_authenticateUserID();?>){
					$("#calender_team_user_id").parents('li').children('a').addClass('filter_selected');
					$("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#calender_team_user_id").parents('li').children('a').removeClass('filter_selected');
					$("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon');
				}

                                if($("#kanban_project_id").val() != 'all'){
					$("#kanban_project_id").parents('li').children('a').addClass('filter_selected');
					$("#kanban_project_id").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#kanban_project_id").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#kanban_project_id").parents('li').children('a').removeClass('filter_selected');
					$("#kanban_project_id").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#kanban_project_id").parents('li').children('a').children('i').addClass('filtericon');
				}

				if($("#kanban_team_user_id").val() != <?php echo get_authenticateUserID();?>){
					$("#kanban_team_user_id").parents('li').children('a').addClass('filter_selected');
					$("#kanban_team_user_id").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#kanban_team_user_id").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#kanban_team_user_id").parents('li').children('a').removeClass('filter_selected');
					$("#kanban_team_user_id").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#kanban_team_user_id").parents('li').children('a').children('i').addClass('filtericon');
				}

				if($("#due_task").val() != 'all'){
					$("#due_task").parents('li').children('a').addClass('filter_selected');
					$("#due_task").parents('li').children('a').children('i').addClass('filtericon-red');
					$("#due_task").parents('li').children('a').children('i').removeClass('filtericon');
				}else{
					$("#due_task").parents('li').children('a').removeClass('filter_selected');
					$("#due_task").parents('li').children('a').children('i').removeClass('filtericon-red');
					$("#due_task").parents('li').children('a').children('i').addClass('filtericon');
				}
				if(<?php echo $calender_sorting; ?> != '1'){
					$("#calender_sorting").parents('li').children('a').addClass('filter_selected');
					$("#calender_sorting").parents('li').children('a').children('i').addClass('sortingicon-red');
					$("#calender_sorting").parents('li').children('a').children('i').removeClass('sortingicon');
                                       <?php if($fun == "weekView" || $fun == "NextFiveDayView"){ ?>
                                        toastr.options = {
                                            "closeButton": true,
                                            "debug": false,
                                            "positionClass": "toast-top-right",
                                            "onclick": null,
                                            "showDuration": "5000",
                                            "hideDuration": "5000",
                                            "timeOut": "5000",
                                            "extendedTimeOut": "1000",
                                            "showEasing": "swing",
                                            "hideEasing": "linear",
                                            "showMethod": "fadeIn",
                                            "hideMethod": "fadeOut"
                                          }
                                          toastr.warning('Set the filter Sort by to Manual to enable drag & drop.','Drag & Drop of tasks is disabled.');
                                       <?php } ?>
                                }else{
					$("#calender_sorting").parents('li').children('a').removeClass('filter_selected');
					$("#calender_sorting").parents('li').children('a').children('i').removeClass('sortingicon-red');
					$("#calender_sorting").parents('li').children('a').children('i').addClass('sortingicon');
				}

				$("#calender_sorting").change(function(){
					var id = $(this).val();
                                        $('#common-sortbybox').hide();
					$('#dvLoading').fadeIn('slow');
					$.ajax({
						type : 'post',
						url : '<?php echo site_url("calendar/saveSortingTask");?>',
						data : {id : id,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php if(isset($active_menu)){ echo $active_menu; } else { echo ""; }?>'},
						success : function(responsedata){
							$("#sjcalendar").html(responsedata);
							if($("#calender_sorting").val() != '1'){
								$("#calender_sorting").parents('li').children('a').addClass('filter_selected');
								$("#calender_sorting").parents('li').children('a').children('i').addClass('sortingicon-red');
								$("#calender_sorting").parents('li').children('a').children('i').removeClass('sortingicon');
                                                                <?php if($fun == "weekView" || $fun == "NextFiveDayView"){ ?>
                                                                toastr.options = {
                                                                    "closeButton": true,
                                                                    "debug": false,
                                                                    "positionClass": "toast-top-right",
                                                                    "onclick": null,
                                                                    "showDuration": "5000",
                                                                    "hideDuration": "5000",
                                                                    "timeOut": "5000",
                                                                    "extendedTimeOut": "1000",
                                                                    "showEasing": "swing",
                                                                    "hideEasing": "linear",
                                                                    "showMethod": "fadeIn",
                                                                    "hideMethod": "fadeOut"
                                                                  }
                                                                  toastr.warning('Set the filter Sort by to Manual to enable drag & drop.','Drag & Drop of tasks is disabled.');
                                                                <?php } ?>
							}else{
								$("#calender_sorting").parents('li').children('a').removeClass('filter_selected');
								$("#calender_sorting").parents('li').children('a').children('i').removeClass('sortingicon-red');
								$("#calender_sorting").parents('li').children('a').children('i').addClass('sortingicon');
							}
							var theight = $(window).height();

							var ptc = parseInt(200)*parseInt(100)/parseInt(theight);

							var m = parseInt(theight) - parseInt("240");

							$(".minhightweek").css("height",ptc);


							var scoll_h = parseInt(m) -parseInt("100");

							$('.scroll_cal_week').slimScroll({
								color: '#17A3E9',
								height : m,
						 	    wheelStep: 20,
						 	    showOnHover:true

						 	});
			            	$('#dvLoading').fadeOut('slow');

						}
					});
				});


				 <?php if($method == "kanban"){ ?>
				 	//alert("kanban");
				$("#user_color_id").change(function(){
					var user_color_id = $(this).val();
					var str = $('#last_remember').serialize();
					$('#dvLoading').fadeIn('slow');
					$.ajax({
						type : 'post',
						url : '<?php echo site_url("kanban/searchDueTask"); ?>',
						data : $('#last_remember').serialize(),
						success : function(data){
							$("#kanban_view").html(data);
							if(user_color_id != 0){
								$("#user_color_id").parents('li').children('a').addClass('filter_selected');
								$("#user_color_id").parents('li').children('a').children('i').addClass('filtericon-red');
								$("#user_color_id").parents('li').children('a').children('i').removeClass('filtericon');
							}else{
								$("#user_color_id").parents('li').children('a').removeClass('filter_selected');
								$("#user_color_id").parents('li').children('a').children('i').removeClass('filtericon-red');
								$("#user_color_id").parents('li').children('a').children('i').addClass('filtericon');
							}
                                                        $('#common-colorbox').hide();
			            	$('#dvLoading').fadeOut('slow');
                                        
						}
					});
				});




				<?php } ?>
				 <?php if($method == "calendar"){

				 	if($fun == "weekView" || $fun == "NextFiveDayView"){
				 	?>

					$("#cal_user_color_id").change(function(){
						var cal_user_color_id = $(this).val();
						var str = $('#last_remember').serialize();
						$('#dvLoading').fadeIn('slow');
						$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchWeekTask"); ?>',
							data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php echo $active_menu;?>'},
							success : function(data){
								$("#sjcalendar").html(data);
								if(cal_user_color_id != 0){
									$("#cal_user_color_id").parents('li').children('a').addClass('filter_selected');
									$("#cal_user_color_id").parents('li').children('a').children('i').addClass('filtericon-red');
									$("#cal_user_color_id").parents('li').children('a').children('i').removeClass('filtericon');
								}else{
									$("#cal_user_color_id").parents('li').children('a').removeClass('filter_selected');
									$("#cal_user_color_id").parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#cal_user_color_id").parents('li').children('a').children('i').addClass('filtericon');
								}
								var theight = $(window).height();

							var ptc = parseInt(200)*parseInt(100)/parseInt(theight);

							var m = parseInt(theight) - parseInt("240");

							$(".minhightweek").css("height",ptc);


							var scoll_h = parseInt(m) -parseInt("100");

							$('.scroll_cal_week').slimScroll({
								color: '#17A3E9',
								height : m,
						 	    wheelStep: 20,
						 	    showOnHover:true

						 	});
                                                        $('#common-colorbox').hide();
				            	$('#dvLoading').fadeOut('slow');
							}
						});
					});

				<?php }else{ ?>

					$("#cal_user_color_id").change(function(){
						var cal_user_color_id = $(this).val();
						var str = $('#last_remember').serialize();
						$('#dvLoading').fadeIn('slow');
						$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchTask"); ?>',
							data : {str:str,year:$("#year").val(),month:$("#month").val()},
							success : function(data){
								$("#sjcalendar").html(data);
								if(cal_user_color_id != 0){
									$("#cal_user_color_id").parents('li').children('a').addClass('filter_selected');
									$("#cal_user_color_id").parents('li').children('a').children('i').addClass('filtericon-red');
									$("#cal_user_color_id").parents('li').children('a').children('i').removeClass('filtericon');
								}else{
									$("#cal_user_color_id").parents('li').children('a').removeClass('filter_selected');
									$("#cal_user_color_id").parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#cal_user_color_id").parents('li').children('a').children('i').addClass('filtericon');
								}
                                                                $('#common-colorbox').hide();
				            	$('#dvLoading').fadeOut('slow');
							}
						});
					});

					<?php } } ?>
			});





			//***********************************
			function sortbyshowhide()
		     {
				//var div = $("#common-projbox").show();
				if($('#common-sortbybox').is(':visible')){
	        		$('#common-sortbybox').hide();
	    		} else {
	       			$('#common-sortbybox').show();
	       		}
	       		$('#common-projbox').hide();
				$("#common-statusbox").hide();
				$("#common-duedatebox").hide();
				$("#common-teambox").hide();
				$("#common-calendbox").hide();
				$('#common-timerbox').hide();
				$('#common-colorbox').hide();
		     }
		  	// ************************************
			 function projshowhide()
		     {
				$('#common-sortbybox').hide();
				if($('#common-projbox').is(':visible')){
	        		$('#common-projbox').hide();
	    		} else {
	       			$('#common-projbox').show();
	       		}
				$("#common-statusbox").hide();
				$("#common-duedatebox").hide();
				$("#common-teambox").hide();
				$("#common-calendbox").hide();
				$('#common-timerbox').hide();
				$('#common-colorbox').hide();
		     }

		   // ************************************
			 function statusshowhide()
		     {
		     	$('#common-sortbybox').hide();
				$("#common-projbox").hide();
				if($('#common-statusbox').is(':visible')){
	        		$('#common-statusbox').hide();
	    		} else {
	       			$('#common-statusbox').show();
	       		}
				$("#common-duedatebox").hide();
				$("#common-teambox").hide();
				$("#common-calendbox").hide();
				$('#common-timerbox').hide();
				$('#common-colorbox').hide();
		     }

		     //*********************
		     function duedateshowhide()
		     {
		     	$('#common-sortbybox').hide();
		     	$("#common-projbox").hide();
				$("#common-statusbox").hide();
				if($('#common-duedatebox').is(':visible')){
	        		$('#common-duedatebox').hide();
	    		} else {
	       			$('#common-duedatebox').show();
	       		}
				$("#common-teambox").hide();
				$("#common-calendbox").hide();
				$('#common-timerbox').hide();
				$('#common-colorbox').hide();
		     }
		    // ************************************
			 function mytemshowhide()
		     {
		     	$('#common-sortbybox').hide();
				$("#common-projbox").hide();
				$("#common-statusbox").hide();
				$("#common-duedatebox").hide();
				if($('#common-teambox').is(':visible')){
	        		$('#common-teambox').hide();
	    		} else {
	       			$('#common-teambox').show();
	       		}
				$("#common-calendbox").hide();
				$('#common-timerbox').hide();
				$('#common-colorbox').hide();
			}
			function usercolorshowhide()
			{
				$('#common-sortbybox').hide();
				$("#common-projbox").hide();
				$("#common-statusbox").hide();
				$("#common-duedatebox").hide();
				$('#common-teambox').hide();
				if($('#common-colorbox').is(':visible')){
	        		$('#common-colorbox').hide();
	    		} else {
	       			$('#common-colorbox').show();
	       		}
				$("#common-calendbox").hide();
				$('#common-timerbox').hide();
			}

			//************************
			function calshowhide()
			{
				$('#common-sortbybox').hide();
				$("#common-projbox").hide();
				$("#common-statusbox").hide();
				$("#common-duedatebox").hide();
				$("#common-teambox").hide();
				if($('#common-calendbox').is(':visible')){
	        		$('#common-calendbox').hide();
	    		} else {
	       			$('#common-calendbox').show();
	       		}
	       		$('#common-timerbox').hide();
	       		$('#common-colorbox').hide();
			}

			//*************
			

			function setchecked(id){
				var id=id.value;
				//alert(id)
			    if($("#kanban_project_id").val() == 'all'){
                                $("#kanban_team_user_id").val("");
                                }
                                $('#common-projbox').hide();
                            if(id=='all'){
                                 $('#dvLoading').fadeIn('slow');
                                 $.ajax({

                                        type:'post',
                                        url: '<?php echo site_url("kanban/get_kanban_project_team");?>',
                                        data: {id:id},
                                        success: function(data){
                                            $("#common-teambox").html(data);
                                            $('#dvLoading').fadeOut('slow');
                                        }

                                    });
                            }
                            else
                            {
                                 $('#dvLoading').fadeIn('slow');
                                $.ajax({

                                        type:'post',
                                        url: '<?php echo site_url("kanban/get_kanban_project_team");?>',
                                        data: {id:id},
                                        success: function(data){
                                            $("#common-teambox").html(data);
                                            $('#dvLoading').fadeOut('slow');
                                        }

                                    });

                            }

		        if(id!=''){
                         $('#dvLoading').fadeIn('slow');
		        	$.ajax({
						type : 'post',
						url : '<?php echo site_url('kanban/searchDueTask');?>',
						data : $('#last_remember').serialize(),
						success : function(data){
							$("#kanban_view").html(data);
							if($("#kanban_project_id").val() != 'all'){
                                                                $("#kanban_project_id").parents('li').children('a').addClass('filter_selected');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').addClass('filtericon-red');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').removeClass('filtericon');
                                                        }else{
                                                                $("#kanban_project_id").parents('li').children('a').removeClass('filter_selected');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').removeClass('filtericon-red');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').addClass('filtericon');
                                                        }

							// $('.scroll1').slimScroll({
								// color: '#17A3E9',
								// height : '160',
						 	    // wheelStep: 12,
						 	    // showOnHover : true
						 	// });
							$('#dvLoading').fadeOut('slow');
						}
					});
		        } else {
                         $('#dvLoading').fadeIn('slow');
		        	$.ajax({
						type : 'post',
						url : '<?php echo site_url('kanban/searchDueTask');?>',
						data : $('#last_remember').serialize(),
						success : function(data){
							$("#kanban_view").html(data);
							if($("#kanban_project_id").val() != 'all'){
                                                                $("#kanban_project_id").parents('li').children('a').addClass('filter_selected');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').addClass('filtericon-red');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').removeClass('filtericon');
                                                        }else{
                                                                $("#kanban_project_id").parents('li').children('a').removeClass('filter_selected');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').removeClass('filtericon-red');
                                                                $("#kanban_project_id").parents('li').children('a').children('i').addClass('filtericon');
                                                        }
							// $('.scroll1').slimScroll({
							// color: '#17A3E9',
							// height : '160',
					 	    // wheelStep: 12,
					 	    // showOnHover : true
						 // });
							$('#dvLoading').fadeOut('slow');
						}
					});
		        }
                        
			}

			function setUnchecked(id){

				var val = [];
				$('input[name="kanban_project_id[]"]:checkbox:checked').each(function(i){
		           val[i] = $(this).val();
			    });
				if($("#kanban_project_"+id).is(":checked")){
					//val[i] = $(this).val();
				} else {
					$("#kanban_project_all").prop("checked", false);
					$("#kanban_project_all").closest("span").removeClass("checked");
				}
                                $('#common-teambox').hide();
				$('#dvLoading').fadeIn('slow');
                                $.ajax({

                                        type:'post',
                                        url: '<?php echo site_url("kanban/get_kanban_project_team");?>',
                                        data: {id:val},
                                        success: function(data){
                                            $("#common-teambox").html(data);
                                        }

                                    });

		        if(val!=''){
		        	$.ajax({
						type : 'post',
						url : '<?php echo site_url('kanban/searchDueTask');?>',
						data : $('#last_remember').serialize(),
						success : function(data){
							$("#kanban_view").html(data);
							if($("#kanban_project_all").prop("checked") == true){
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
							} else {
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
							}
							// $('.scroll1').slimScroll({
								// color: '#17A3E9',
								// height : '160',
						 	    // wheelStep: 12,
						 	    // showOnHover : true
						 	// });
							$('#dvLoading').fadeOut('slow');
						}
					});
		        } else {
		        	$.ajax({
						type : 'post',
						url : '<?php echo site_url('kanban/searchDueTask');?>',
						data : $('#last_remember').serialize(),
						success : function(data){
							$("#kanban_view").html(data);
							if($("#kanban_project_all").prop("checked") == true){
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
							} else {
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
								$("#kanban_project_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
							}
							// $('.scroll1').slimScroll({
							// color: '#17A3E9',
							// height : '160',
					 	    // wheelStep: 12,
					 	    // showOnHover : true
						 // });
							$('#dvLoading').fadeOut('slow');
						}
					});
		        }
			}

			function setCalProjectchecked(id){
				var id = id.value;
				//alert(id.value)
                                if($("#calender_project_id").val() == 'all'){
                                            $("#calender_team_user_id").val("");
                                    }
			    var str = $('#last_remember').serialize();
//				alert(str)

                        <?php if($fun == "weekView" || $fun == "NextFiveDayView"){ ?>
                       $('#dvLoading').fadeIn('slow');
                            //alert($("#calender_project_all").val())
                       $.ajax({
                            type:'post',
                            url: '<?php echo site_url("calendar/get_project");?>',
                            data: {id:id,view:'weekView'},
                            success: function(data){
                                $("#common-teambox").html(data);
                                $('#dvLoading').fadeOut('slow');
                            }

                        });

                        <?php }else{ ?>
                            $('#dvLoading').fadeIn('slow');
                            //alert($("#calender_project_all").val())
                       $.ajax({
                            type:'post',
                            url: '<?php echo site_url("calendar/get_project");?>',
                            data: {id:id,view:'myCalendar'},
                            success: function(data){
                                $("#common-teambox").html(data);
                                $('#dvLoading').fadeOut('slow');
                            }

                        });

                        <?php }?>
		        <?php if($fun == "myCalendar"){ ?>
                                        $('#dvLoading').fadeIn('slow');
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchTask"); ?>',
							data : {str:str,year:$("#year").val(),month:$("#month").val()},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#calender_project_id").val() != 'all'){
                                                                        $("#calender_project_id").parents('li').children('a').addClass('filter_selected');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').addClass('filtericon-red');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').removeClass('filtericon');
                                                                }else{
                                                                        $("#calender_project_id").parents('li').children('a').removeClass('filter_selected');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').removeClass('filtericon-red');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').addClass('filtericon');
                                                                }
                                                                $('#dvLoading').fadeOut('slow');
							}
						});

		        <?php } elseif($fun == "weekView" || $fun == "NextFiveDayView"){ ?>
                                    $('#dvLoading').fadeIn('slow');
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchWeekTask"); ?>',
							data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php echo $active_menu;?>'},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#calender_project_id").val() != 'all'){
                                                                        $("#calender_project_id").parents('li').children('a').addClass('filter_selected');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').addClass('filtericon-red');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').removeClass('filtericon');
                                                                }else{
                                                                        $("#calender_project_id").parents('li').children('a').removeClass('filter_selected');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').removeClass('filtericon-red');
                                                                        $("#calender_project_id").parents('li').children('a').children('i').addClass('filtericon');
                                                                }
								var theight = $(window).height();

							var ptc = parseInt(200)*parseInt(100)/parseInt(theight);

							var m = parseInt(theight) - parseInt("240");

							$(".minhightweek").css("height",ptc);


							var scoll_h = parseInt(m) -parseInt("100");

							$('.scroll_cal_week').slimScroll({
								color: '#17A3E9',
								height : m,
						 	    wheelStep: 20,
						 	    showOnHover:true

						 	});
			                	$('#dvLoading').fadeOut('slow');
							}
						});


		        <?php } ?>
                        $('#common-projbox').hide();
			}



			function setCalStatuschecked(elemName){
				elem = $("input[name='"+elemName+"']");
				if($("#left_task_status_all").prop("checked") == true){
					for(i=0;i<elem.length;i++){
						$(elem[i]).prop("checked",true);
						$(elem[i]).closest('span').addClass('checked');
					}
				} else {
					for(i=0;i<elem.length;i++){
						$(elem[i]).prop("checked",false);
						$(elem[i]).closest('span').removeClass('checked');
					}
				}
				var val = [];
				$('input[name="left_task_status_id[]"]:checkbox:checked').each(function(i){
		           val[i] = $(this).val();
			    });
			    var str = $('#last_remember').serialize();

		        $('#dvLoading').fadeIn('slow');
		        <?php if($fun == "myCalendar"){ ?>
			        if(val!=''){
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchTask"); ?>',
							data : {str:str,year:$("#year").val(),month:$("#month").val()},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        } else {
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchTask"); ?>',
							data : {str:str,year:$("#year").val(),month:$("#month").val()},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        }
		        <?php } elseif($fun == "weekView" || $fun == "NextFiveDayView"){ ?>
			        if(val!=''){
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchWeekTask"); ?>',
							data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php echo $active_menu;?>'},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
								var theight = $(window).height();

							var ptc = parseInt(200)*parseInt(100)/parseInt(theight);

							var m = parseInt(theight) - parseInt("240");

							$(".minhightweek").css("height",ptc);


							var scoll_h = parseInt(m) -parseInt("100");

							$('.scroll_cal_week').slimScroll({
								color: '#17A3E9',
								height : m,
						 	    wheelStep: 20,
						 	    showOnHover:true

						 	});
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        } else {
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchWeekTask"); ?>',
							data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php echo $active_menu;?>'},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
								var theight = $(window).height();

							var ptc = parseInt(200)*parseInt(100)/parseInt(theight);

							var m = parseInt(theight) - parseInt("240");

							$(".minhightweek").css("height",ptc);


							var scoll_h = parseInt(m) -parseInt("100");

							$('.scroll_cal_week').slimScroll({
								color: '#17A3E9',
								height : m,
						 	    wheelStep: 20,
						 	    showOnHover:true

						 	});
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        }
		        <?php } ?>
                    $('#common-statusbox').hide();
			}

			function setCalStatusUnchecked(id){
				var val = [];
				$('input[name="left_task_status_id[]"]:checkbox:checked').each(function(i){
		           val[i] = $(this).val();
			    });
				if($("#left_task_status_"+id).is(":checked")){
					//val[i] = $(this).val();

				} else {
					$("#left_task_status_all").prop("checked", false);
					$("#left_task_status_all").closest("span").removeClass("checked");
				}
				var str = $('#last_remember').serialize();

		        $('#dvLoading').fadeIn('slow');
		        <?php if($fun == "myCalendar"){ ?>
			        if(val!=''){
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchTask"); ?>',
							data : {str:str,year:$("#year").val(),month:$("#month").val()},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        } else {
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchTask"); ?>',
							data : {str:str,year:$("#year").val(),month:$("#month").val()},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        }
		        <?php } elseif($fun == "weekView" || $fun == "NextFiveDayView"){ ?>
			        if(val!=''){
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchWeekTask"); ?>',
							data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php echo $active_menu;?>'},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
								var theight = $(window).height();

							var ptc = parseInt(200)*parseInt(100)/parseInt(theight);

							var m = parseInt(theight) - parseInt("240");

							$(".minhightweek").css("height",ptc);


							var scoll_h = parseInt(m) -parseInt("100");

							$('.scroll_cal_week').slimScroll({
								color: '#17A3E9',
								height : m,
						 	    wheelStep: 20,
						 	    showOnHover:true

						 	});
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        } else {
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url("calendar/searchWeekTask"); ?>',
							data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php echo $active_menu;?>'},
							success : function(data){
								$("#sjcalendar").html(data);
								if($("#left_task_status_all").prop("checked") == true){
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').removeClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon');
								} else {
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').addClass('filter_selected');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
									$("#left_task_status_all").parents('li').parents('ul').parents('li').children('a').children('i').removeClass('filtericon');
								}
								var theight = $(window).height();

							var ptc = parseInt(200)*parseInt(100)/parseInt(theight);

							var m = parseInt(theight) - parseInt("240");

							$(".minhightweek").css("height",ptc);


							var scoll_h = parseInt(m) -parseInt("100");

							$('.scroll_cal_week').slimScroll({
								color: '#17A3E9',
								height : m,
						 	    wheelStep: 20,
						 	    showOnHover:true

						 	});
			                	$('#dvLoading').fadeOut('slow');
							}
						});
			        }
		        <?php } ?>
                    $('#common-statusbox').hide();
				}

				function setDueDatechecked(elemName){
					elem = $("input[name='"+elemName+"']");
					if($("#due_date_all").prop("checked") == true){
						for(i=1;i<elem.length;i++){
							//$(elem[i]).prop("checked",true);
							//$(elem[i]).closest('span').addClass('checked');
							$(elem[i]).prop("checked",false);
							$(elem[i]).closest('span').removeClass('checked');
						}
					} else {
						for(i=0;i<elem.length;i++){
							$(elem[i]).prop("checked",false);
							$(elem[i]).closest('span').removeClass('checked');
						}
					}
					var val = [];
					$('input[name="due_task[]"]:checkbox:checked').each(function(i){
			           val[i] = $(this).val();
				    });
				    var str = $('#last_remember').serialize();
                                    $('#common-duedatebox').hide();
			        $('#dvLoading').fadeIn('slow');
			        if(val!=''){
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url('kanban/searchDueTask');?>',
							data : $('#last_remember').serialize(),
							success : function(data){
								$("#kanban_view").html(data);
								// $('.scroll1').slimScroll({
								// color: '#17A3E9',
								// height : '160',
						 	    // wheelStep: 12,
						 	    // showOnHover : true
							 // });
								$('#dvLoading').fadeOut('slow');
							}
						});
			        } else {
			        	$.ajax({
							type : 'post',
							url : '<?php echo site_url('kanban/searchDueTask');?>',
							data : $('#last_remember').serialize(),
							success : function(data){
								$("#kanban_view").html(data);
								// $('.scroll1').slimScroll({
								// color: '#17A3E9',
								// height : '160',
						 	    // wheelStep: 12,
						 	    // showOnHover : true
							 // });
								$('#dvLoading').fadeOut('slow');
							}
						});
			        }
                                
				}

  			</script>
		 <div class="filter-group">
		  	<form name="last_remember" id="last_remember" action="" style="margin: 0px;"/>
			<ul class="list-unstyled">
				<?php
				if($method == "calendar" && ($fun == 'weekView' || $fun == 'NextFiveDayView')){
					?>
					<li id="week_last_remeber">
						<a href="javascript:void(0)" onClick="sortbyshowhide()"> <i class="stripicon sortingicon"> </i> Sort By </a>
						 <div class="profilterbx" id="common-sortbybox" style="display:none;">
						 	<div class="filter-listing">
								<select tabindex="1" id="calender_sorting" name="calender_sorting" class="col-md-10 m-wrap no-margin">
  									<option value="1" <?php if($calender_sorting == '1'){ echo 'selected="selected"'; } ?> >Manual Sorting</option>
									<option value="2" <?php if($calender_sorting == '2'){ echo 'selected="selected"'; } ?> >By Priority</option>
									<option value="3" <?php if($calender_sorting == '3'){ echo 'selected="selected"'; } ?> >By Due Date</option>
									<option value="4" <?php if($calender_sorting == '4'){ echo 'selected="selected"'; } ?> >By Expected Time</option>
								</select>
							</div>
						 </div>
					</li>
					<?php
				}?>
				<?php
				if($method == "kanban" && $fun == "myKanban")
		 		{
		 			$user_projects = get_user_projects(get_authenticateUserID());
//		 			if($kanban_project_id){
//  						$kanban_project_ids = explode(',', $kanban_project_id);
//  					} else {
//  						$kanban_project_ids = array('all');
//  					}
//


  					?>
					<li> <a href="javascript:void(0)" onClick="projshowhide()"> <i class="stripicon filtericon"> </i>Project </a>
						 <div class="profilterbx" id="common-projbox" style="display:none;">
						 	<div class="filter-listing">
								<select class="col-md-10 m-wrap no-margin" name="kanban_project_id" id="kanban_project_id" tabindex="1" onchange="setchecked(this)">
                                                                    <option  value="all" selected='selected'> All</option>
                                                                        <?php
                                                                        if($user_projects){
                                                                                foreach($user_projects as $user_pr){ ?>
                                                                        <option  value="<?php echo $user_pr->project_id;?>" > <?php echo $user_pr->project_title; ?></option>
                                                                        <?php }
                                                                        } ?>
                                                                </select>
							</div>
						 </div>
					</li>
                                         <?php if(!empty($user_projects)){?>
                                    <?php foreach($user_projects as $users_pro){?>
                                    <input type="hidden" value="<?php echo get_project_subsection_id($users_pro->project_id);?>" id="subsection_<?php echo $users_pro->project_id;?>" />
                                 <?php   }
                                        }
                                        ?>   
				<?php $user_colors = $color_codes;?>

					<li>
						<a href="javascript:void(0)" onClick="usercolorshowhide()"><i class="stripicon filtericon"> </i> Colour </a>
						 <div class="profilterbx" id="common-colorbox" style="display:none;">
						 	<div class="filter-listing">
								 <select class="col-md-10 m-wrap no-margin" name="user_color_id" id="user_color_id" tabindex="1">
								 	<option value="0" <?php if($user_color_id == '0'){ echo 'selected="selected"'; }?> >All</option>
								 	<?php if($user_colors){
											foreach($user_colors as $uc){
									?>
												<option value="<?php echo $uc->user_color_id;?>" <?php if($uc->user_color_id == $user_color_id){ echo 'selected="selected"'; }?> ><?php echo $uc->name;?></option>
									<?php }
									} ?>
								</select>
							</div>
						 </div>
					</li>


			<?php } ?>
			<?php
			if($method == "calendar" && ($fun == 'myCalendar' || $fun == 'weekView' || $fun == 'NextFiveDayView')){
				$user_projects = get_user_projects(get_authenticateUserID());

  				?>
  				<li> <a href="javascript:void(0)" onClick="projshowhide()"> <i class="stripicon filtericon"> </i> Project </a>
					 <div class="profilterbx" id="common-projbox" style="display:none;">
					 	<div class="filter-listing">
							<select class="col-md-10 m-wrap no-margin" name="calender_project_id" id="calender_project_id" tabindex="1" onchange="setCalProjectchecked(this)">
                                                            <option   value="all" <?php if($calender_project_id == 'all'){echo 'selected="selected"'; }?> > All</option>
								<?php
								if($user_projects){
			  						foreach($user_projects as $user_pr){ ?>
                                                                <option  value="<?php echo $user_pr->project_id;?>" <?php if($calender_project_id == $user_pr->project_id){ echo 'selected="selected"'; }?> > <?php echo $user_pr->project_title; ?></option>
								<?php }
								} ?>
							</select>
						</div>
					 </div>
                                    <?php if(!empty($user_projects)){?>
                                    <?php foreach($user_projects as $users_pro){?>
                                    <input type="hidden" value="<?php echo get_project_subsection_id($users_pro->project_id);?>" id="subsection_<?php echo $users_pro->project_id;?>" />
                                 <?php   }
                                        }
                                        ?>
				</li>


				<?php $user_colors = $color_codes;?>

					<li>
						<a href="javascript:void(0)" onClick="usercolorshowhide()"><i class="stripicon filtericon"> </i>  Colour </a>
						 <div class="profilterbx" id="common-colorbox" style="display:none;">
						 	<div class="filter-listing">
								 <select class="col-md-10 m-wrap no-margin" name="cal_user_color_id" id="cal_user_color_id" tabindex="1">
								 	<option value="0" <?php if($cal_user_color_id == '0'){ echo 'selected="selected"'; }?> >All</option>
								 	<?php if($user_colors!='0'){
											foreach($user_colors as $uc){
									?>
												<option value="<?php echo $uc->user_color_id;?>" <?php if($uc->user_color_id == $cal_user_color_id){ echo 'selected="selected"'; }?> ><?php echo $uc->name;?></option>
									<?php }
									} ?>
								</select>
							</div>
						 </div>
					</li>

  			<?php } ?>
  			<?php
	       	 if($fun == "myCalendar" || $fun == "weekView" || $fun == "NextFiveDayView")
			 {
			 	if($task_status){
					if($rem_task_status_id){
						$task_st_ids = explode(',', $rem_task_status_id);
					} else {
						$task_st_ids = array('all');
					}
	       	 ?>

				<li> <a href="javascript:void(0)" onClick="statusshowhide()"> <i class="stripicon filtericon"> </i> Status </a>
					 <div class="profilterbx" id="common-statusbox" style="display:none;">
					 	<div class="filter-listing ">
							<ul class="filter-content list-unstyled">
                                                            <li> <label class="checkbox"><input type="checkbox" class="newcheckbox_task" name="left_task_status_id[]" id="left_task_status_all" onclick="setCalStatuschecked('left_task_status_id[]')" value="all" <?php if(in_array('all',$task_st_ids)){ echo "checked='checked'"; } ?> /> All	</label></li>
								<?php foreach($task_status as $sta){ ?>
                                                            <li> <label class="checkbox"><input type="checkbox" class="newcheckbox_task" name="left_task_status_id[]" id="left_task_status_<?php echo $sta->task_status_id;?>" onclick="setCalStatusUnchecked('<?php echo $sta->task_status_id;?>')" <?php if(in_array($sta->task_status_id,$task_st_ids)){ echo 'checked="checked"'; }?> value="<?php echo $sta->task_status_id;?>" /> <?php echo $sta->task_status_name; ?>	</label></li>
								<?php } ?>
						 	</ul>
						</div>
					 </div>
				</li>
			<?php }
			} ?>
			<?php
       	 	if($method == "kanban" && $fun == "myKanban")
		 	{
			 	//if($due_task_data){
			 		//$due_tasks = explode(',', $due_task_data);
			 	//} else {
			 		//$due_tasks = array();
			 	//}
			?>

				<li>
						<a href="javascript:void(0)" onClick="duedateshowhide()"> <i class="stripicon filtericon"> </i> Due Date </a>
						 <div class="profilterbx" id="common-duedatebox" style="display:none;">
						 	<div class="filter-listing">
								 <select class="col-md-10 m-wrap no-margin" name="due_task" id="due_task" tabindex="1">

								 	<option value="all" <?php if($due_task == 'all'){ echo 'selected="selected"'; }?> > All </option>
								 	<option value="today" <?php if($due_task == 'today'){ echo 'selected="selected"'; }?> > Today </option>
								 	<option value="this_week" <?php if($due_task == 'this_week'){ echo 'selected="selected"'; }?> > This Week </option>
								 	<option value="next_week" <?php if($due_task == 'next_week'){ echo 'selected="selected"'; }?> > Next Week </option>
								 	<option value="this_month" <?php if($due_task == 'this_month'){ echo 'selected="selected"'; }?> > Due in ['<?php echo date('F'); ?>'] </option>
								 	<option value="next_month" <?php if($due_task == 'next_month'){ echo 'selected="selected"'; }?> > Due in ['<?php echo date('F', strtotime('+1 month')); ?>'] </option>
								 	<option value="next_to_next_month" <?php if($due_task == 'next_to_next_month'){ echo 'selected="selected"'; }?> > Due in ['<?php echo date('F', strtotime('+2 month')); ?>'] </option>
								 	<option value="next_ninty" <?php if($due_task == 'next_ninty'){ echo 'selected="selected"'; }?> > Due next 90 days </option>
								 	<option value="this_year" <?php if($due_task == 'this_year'){ echo 'selected="selected"'; }?> > This Year</option>
								 	<option value="next_year" <?php if($due_task == 'next_year'){ echo 'selected="selected"'; }?> > Next Year</option>
								 	<option value="overdue" <?php if($due_task == 'overdue'){ echo 'selected="selected"'; }?>>Overdue</option>
								 	<?php /*if($users){
											foreach($users as $u){
									?>
												<option value="<?php echo $u->user_id;?>" <?php if($u->user_id == $kanban_team_user_id){ echo 'selected="selected"'; }?> ><?php if($u->user_id == get_authenticateUserID()){ echo "My Task";} else { echo $u->first_name.' '.$u->last_name;}?></option>
									<?php }
									}*/ ?>
								</select>
							</div>
						 </div>
					</li>



       	 	<?php } ?>
<!--                  Footer changes for removing duplication of admin name in user list. -->
       	 	<?php
       	 	if($method == "kanban" && $fun == "myKanban"){
       	 		if($is_administrator=='1' || $is_manager == '1' || $total_project > 0){
       	 			$users = get_users_under_managers();
				 ?>
				 	<li>
						<a href="javascript:void(0)" onClick="mytemshowhide()"><i class="stripicon filtericon"> </i>  My Team </a>
						 <div class="profilterbx" id="common-teambox" style="display:none;">
						 	<div class="filter-listing">
								 <select class="col-md-12 m-wrap no-margin radius-b" name="kanban_team_user_id" id="kanban_team_user_id" tabindex="1">
								 	<option value="<?php echo $this->session->userdata('user_id');?>" <?php if($this->session->userdata('user_id') == $kanban_team_user_id){ echo 'selected="selected"'; }?> ><?php echo "My Task";?></option>
								 	<?php if($users){
											foreach($users as $u){
                                                                                            if($u->user_id != $kanban_team_user_id){
									?>
												<option value="<?php echo $u->user_id;?>" ><?php echo $u->first_name.' '.$u->last_name;?></option>
									<?php }
                                                                             }
									} ?>
								</select>
							</div>
						 </div>
					</li>
       	 	<?php } else {}
       	 	} ?>
       	 	<?php
       	  	if($method == "calendar" && ($fun == "myCalendar" || $fun == "weekView" || $fun == "NextFiveDayView")){
                         
       	  		if($is_administrator=='1' || $is_manager == '1' || $total_project > 0){
       	  		$users = get_users_under_managers();
                        $team_users = get_user_under_project($calender_project_id);
                ?>
       	  			<li>
						<a href="javascript:void(0)" onClick="mytemshowhide()"> <i class="stripicon filtericon"> </i> My Team </a>
						 <div class="profilterbx" id="common-teambox" style="display:none;">
						 	<div class="filter-listing">
								 <select class="col-md-10 m-wrap no-margin" name="calender_team_user_id" id="calender_team_user_id" tabindex="1">
								 	<?php if( $calender_project_id != 'all' ){?>
                                                                            <option value="<?php echo $this->session->userdata('user_id');?>" <?php if($this->session->userdata('user_id') == $calender_team_user_id){ echo 'selected="selected"'; }?> ><?php echo "My Task";?></option>
                                                                            <option value="#" <?php if($calender_team_user_id == '0'){ echo 'selected="selected"';} ?> ><?php echo "Project Team";?></option>
                                                                            <?php foreach($team_users as $user){ ?>
                                                                            <?php if($user->user_id != get_authenticateUserID()){ ?>
                                                                                 <option value = <?php echo $user->user_id;?> <?php if($calender_team_user_id == $user->user_id){ echo 'selected="selected"';} ?> ><?php echo $user->first_name." ".$user->last_name;?></option>
                                                                            <?php    }
                                                                            }
                                                                            }
                                                                        else{?>
                                                                        <option value="<?php echo $this->session->userdata('user_id');?>" <?php if($this->session->userdata('user_id') == $calender_team_user_id){echo 'selected="selected"';}?> ><?php echo "My Task";?></option>
								 	<?php if($users){
										foreach($users as $u){
                                                                                    if($u->user_id != get_authenticateUserID()){
									?>
                                                                                    <option value="<?php echo $u->user_id;?>" <?php if($u->user_id == $calender_team_user_id){echo 'selected="selected"';}?> ><?php  echo $u->first_name.' '.$u->last_name; ?></option>
									<?php }
                                                                            }
                                                                        }}?>
								</select>
							</div>
						 </div>
					</li>
       	  	<?php } else {}
			}
			?>
			 <?php
	       	 if($fun == "myCalendar" || $fun == "weekView" || $fun == "NextFiveDayView")
			 {
	       	 ?>
<!--				<li> <a href="javascript:void(0)" onClick="calshowhide()"> <i class="stripicon filtericon"> </i>Calendar </a>
					<div class="calenbx" id="common-calendbox" style="display:none;">
					 	<ul class="filter-content list-unstyled">
                                                    <div id="datetimepicker12" ></div>
   	 						<input type="hidden" name="calender_date" id="calender_date" value="" />-->

<script type="text/javascript">
                                             cal_fill();   
                                              function cal_fill(){ 
                                                 
						        $(function () {
						        	$('#datetimepicker12 ,.datetimepicker123').datepicker({
						                startDate: -Infinity,
                                                                                                                orientation: 'bottom left',
						                format : 'yyyy-mm-dd',
                                                                                                                autoclose:true,
                                                                                                                beforeShowDay: function(date){
                                                                                                                    if(date.getDay()==1){
                                                                                                                        return true;
                                                                                                                    }else{
                                                                                                                        return false;
                                                                                                                    }
                                                                                                                }
                                                                                                                
						            }).datepicker('setDate', $("#week_start_date").val()).on('changeDate', function(date) {
						            	function pad(s) { return (s < 10) ? '0' + s : s; }
										var d = new Date(date.date);
										var sel_date = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
                                                                                $('#common-calendbox').hide();
										$('#dvLoading').fadeIn('slow');
										$("#redirect_page").val('FiveWeekView');
						            	<?php if($fun == "myCalendar"){ ?>
						            		$.ajax({
												type : 'post',
												url : '<?php echo site_url("calendar/FiveWeekView"); ?>',
												data : {date:sel_date,from:'monthly',year:$("#year").val(),month:$("#month").val(),redirect:'myCalender'},
												success : function(data){
													$("#month_last_remeber").show();
													$("#week_last_remeber").hide();
													$("#sjcalendar").html(data);
													$("#calender_date").parents('ul').parents('li').children('a').addClass('filter_selected');
													$("#calender_date").parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');

									            	$('#dvLoading').fadeOut('slow');

									            	$(".sortable").sortable({
														items: '> div:not(.unsorttd)',
														revert: true,
												        forcePlaceholderSize: true,
												        connectWith: 'div',
												        scroll: false,
												   		placeholder: "drag-place-holder",
												 		scrollSensitivity: 10,
												   	   	scrollSpeed: 40,
												    	tolerance: "pointer",
												       	dropOnEmpty: true,
												      	forcePlaceholderSize: true,
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
																data:{'order':order,'date':date, 'scope_id':scope_id, task_data : orig_data, 'from' : 'ajax'},
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
																		//$("#task_info_"+date).remove();
																	}

																	//$('#dvLoading').fadeOut('slow');
																},
															})
													    },
													    stop: function (e, ui) {

												        },

												        receive: function( e, ui ) {


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
																			data : {task_id : responsedata.id, year:$("#year").val(),month:$("#month").val()},
																			success : function(task_detal){
																				$("#task_"+scope_id).replaceWith(task_detal);
																			}
																		});
																	}

																	//$('#dvLoading').fadeOut('slow');
																},
															})
												        },

												        cursor: 'move',

													}).disableSelection();


												}
											});
					            		<?php } elseif($fun == "weekView" || $fun == "NextFiveDayView"){ ?>
					            			$.ajax({
												type : 'post',
												url : '<?php echo site_url("calendar/filterWeek"); ?>',
												data : {date:sel_date,from:'weekly',start_date:'<?php echo $start_date;?>',end_date:'<?php echo $end_date;?>',redirect:'<?php echo $fun;?>'},
												success : function(data){
													$("#month_last_remeber").show();
													$("#week_last_remeber").hide();
													$("#sjcalendar").html(data);
													$("#calender_date").parents('ul').parents('li').children('a').addClass('filter_selected');
													$("#calender_date").parents('ul').parents('li').children('a').children('i').addClass('filtericon-red');
													var theight = $(window).height();

													//alert("main-"+theight);
													//alert($( document ).height()- 80);

													var ptc = parseInt(200)*parseInt(100)/parseInt(theight);
													//alert(ptc);
													var m = parseInt(theight) - parseInt("200");
													//(theight);
													//alert("main sub-"+m);

													$(".minhightweek").css("height",ptc);


													var scoll_h = parseInt(m) -parseInt("100");
													//alert("main scroll-"+scoll_h);

													//alert(scoll_h);
													$('.scroll_cal_week').slimScroll({
														color: '#17A3E9',
														height : m,
												 	    wheelStep: 20,
												 	    showOnHover:true

												 	});
									            	$('#dvLoading').fadeOut('slow');
												}
											});
					            		<?php } ?>
						            });
						        });
//                                                        for month view datepicker
                                                        $(function(){
                                                                   $(".datetimepicker_month_view").datepicker({
                                                                       startView: 1,   
                                                                       minViewMode: 1,
                                                                       maxViewMode: 1,
                                                                       autoclose:true
                                                                     
                                                                       
                                                                   }). on('changeMonth',function(date) {
                                                                       function pad(s) { return (s < 10) ? '0' + s : s; }
                                                                                      var d = new Date(date.date);
                                                  __doPostBack('view', 'monthly',  d.getFullYear(),  pad(d.getMonth()+1), '29');
                                                
                                                          });
                                                        
                                                        });
                                                        
                                                        }
						    </script>
   	 						
<!--					 	</ul>
					 </div>
				</li>-->
				<?php } ?>
			</ul>
			</form>
		</div>
		 <input type="hidden" name="action" id="week_action" value="<?php if(isset($action) && $action!=''){ echo $action; } else { echo ''; } ?>" />
	   </div>
    	
    	<div class="timerbtn" >
	  		&nbsp;
	  	</div>
	  	
        <div id="work_log" style=" background: #fff;" class="modal work_popup fade" tabindex="-1" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3>Work log</h3>
			</div>
            <form id="work_log_filter" name="" >
            <div style=" margin: 10px " class="form-group m-b-sm">
                    <label style=" float: left; padding:0 10px ;" class="control-label " ><span>Date From :</span></label>
								<div class="controls">
									<div class="datLT">
										<div class="input-append date date-picker" data-date="<?php echo $date_to_timezone;?>" data-date-format="<?php echo $date_arr_java[$site_setting_date]; ?>" data-date-viewmode="years">
                                                                                    <input name="from_date" id="from_date" class="m-wrap m-ctrl-medium setHourErr" size="16" type="text" value="" style="width:175px;" maxlength="<?php echo $size;?>" autocomplete="off"/><span  class="add-on"><i style=" width: 24px; height: 24px;" class="stripicon icocaledr"></i></span>
                                                                                    <input type="hidden" id="hide_from_date" value=""/>
                                                                                </div>
									</div>
									<div class="dattxt" style=""> To </div>
									<div class="datLT">
										<div class="input-append date date-picker" data-date="<?php echo $date_to_timezone;?>" data-date-format="<?php echo $date_arr_java[$site_setting_date]; ?>" data-date-viewmode="years">
                                                                                    <input name="to_date" id="to_date" class="m-wrap m-ctrl-medium setHourErr " size="16" type="text" value="" style="width:175px;" maxlength="<?php echo $size;?>" autocomplete="off"/><span  class="add-on"><i style=" width: 24px; height: 24px;" class="stripicon icocaledr"></i></span>
                                                                                    <input type="hidden" id="hide_to_date" value=""/>
                                                                                </div>
									</div>
                                                                        <button  onclick="return filter_log();"  class="btn btn-common-blue" style="margin-left: 15px;"> Search </button>
                                                                        <button  onclick="get_work_log();" type="button" class="btn btn-common-blue" style="margin-left: 25px;"> Reset </button>
									
								</div>
                   
							</div>
        </form>
            
			<div class="modal-body">
                            
           
                            
                            <div style=" clear: both;"  class="scroll_log row" style="overflow:hidden; border-radius: 0 0 5px 5px;background-color: #fff;">
					<div class="col-md-12" id="log_data">

					</div>
			            </div>
			</div>
		</div>


        <div id="statistics" class="modal custom_modal_width fade" tabindex="-1">
           <?php
            
                                        $this->load->view($theme.'/layout/common/ajax_statistics.php');
                                ?>
         </div>

            <script>
                

     	function get_work_log(){
     		$('#dvLoading').fadeIn('slow');
                $('#from_date').val('');
                $('#to_date').val('');
			 $.ajax({
				  type : 'post',
				  url : '<?php echo site_url("task/get_user_work_log");?>',
				  success : function(data){
					  $("#log_data").html(data);
					  $('#dvLoading').fadeOut('slow');
				  }
			  });
     	}
        function filter_log(){
                var from_date=$('#from_date').val();
                var to_date=$('#to_date').val();
                    if(from_date == '' || to_date == '')
                    {
                         alertify.alert("Please select a date range");
                         return false;
                          //$('#work_log_filter').reset();
                     }
                    else
                    {
                        $('#work_log_filter').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            
            ignore: "",
			rules : {
				"to_date" : {
					greaterThan : true
				}
			},
       		errorPlacement: function (error, element) {
				if (element.attr("name") == "from_date" || element.attr("name") == "to_date" ) { // for chosen elements, need to insert the error after the chosen container
                    error.appendTo( element.parent("div") );
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                } 
   
            },
		 	submitHandler: function () {

                      $('#dvLoading').fadeIn('slow');
			 $.ajax({
				  type : 'post',
				  url : '<?php echo site_url("task/get_user_work_log");?>',
                                  data :$("#work_log_filter").serialize(),
				  success : function(data){
					  $("#log_data").html(data);
					  $('#dvLoading').fadeOut('slow');
				  }
			  });
                    }
                    });
     	}
        
                         
        }

     	function get_statistics(){
     		$('#dvLoading').fadeIn('slow');
     		$.ajax({
     			type : 'post',
     			url : '<?php echo site_url("task/get_statistics");?>',
     			success : function(data){
     				$("#statistics").html(data);
     				google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization});
     				google.load("visualization", "1", {"packages": ["corechart"], "callback": drawChart});
     				google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization1});
     				$('#dvLoading').fadeOut('slow');
     			}
     		});
     	}
  		</script>
	<?php } ?>
</div>
<!-- END FOOTER -->
<style>
.toast {
    opacity: 1 !important;
}
</style>