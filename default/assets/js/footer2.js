$(document).ready(function(){
	elem = $("input[name='kanban_project_id[]']");
	if($("#kanban_project_all").prop("checked") == true){
		for(i=0;i<elem.length;i++){
			$(elem[i]).prop("checked",true);
			$(elem[i]).closest('span').addClass('checked');
		}
	} 
	
	elem = $("input[name='calender_project_id[]']");
	if($("#calender_project_all").prop("checked") == true){
		for(i=0;i<elem.length;i++){
			$(elem[i]).prop("checked",true);
			$(elem[i]).closest('span').addClass('checked');
		}
	} 
	
	elem = $("input[name='left_task_status_id[]']");
	if($("#left_task_status_all").prop("checked") == true){
		for(i=0;i<elem.length;i++){
			$(elem[i]).prop("checked",true);
			$(elem[i]).closest('span').addClass('checked');
		}
	}
	
	if(SIDEBAR_COLLAPSED == '0'){
		$('body').attr('class','page-header-fixed');
	 } else { 
		$('body').attr('class','page-header-fixed page-sidebar-closed');
	}
	
	$('#burgericon').click(function(){
		if($('body').hasClass('page-sidebar-closed')){
			var collapsed_val = '1';
		} else {
			var collapsed_val = '0';
		}
		
		$.ajax({
			type : 'post',
			url : SIDEURL+'home/save_left_collapse',
			data : { collapsed : collapsed_val},
			success : function(){
				
			}
		});
	});
});


// ************************************
 function projshowhide()
 {
	//var div = $("#common-projbox").show();
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
     }
  
   // ************************************
 function statusshowhide()
 {
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
	
 }
 
 //*********************
 function duedateshowhide()
 {
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
 }
// ************************************
 function mytemshowhide()
 {
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
}

//************************
function calshowhide()
{
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
}

//*************
function showhide()
{
	$("#common-projbox").hide();
	$("#common-statusbox").hide();
	$("#common-duedatebox").hide();
	$("#common-teambox").hide();
	$('#common-calendbox').hide();
	if($('#common-timerbox').is(':visible')){
		$('#common-timerbox').hide();
		$("#is_timer_on").val("0");
	} else {
		$('#common-timerbox').show();
		$("#is_timer_on").val("1");
	}
	
}

function setchecked(elemName){
	elem = $("input[name='"+elemName+"']");
	if($("#kanban_project_all").prop("checked") == true){
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
	$('input[name="kanban_project_id[]"]:checkbox:checked').each(function(i){
       val[i] = $(this).val();
    });
    $('#dvLoading').fadeIn('slow');
    if(val!=''){
    	$.ajax({
			type : 'post',
			url : SIDEURL+'kanban/searchDueTask',
			data : $('#last_remember').serialize(),
			success : function(data){
				$("#kanban_view").html(data);
				$('.scroll1').slimScroll({
					color: '#17A3E9',
					height : '160',
			 	    wheelStep: 12,
			 	    showOnHover : true
			 	});
				$('#dvLoading').fadeOut('slow');
			}
		});	
    } else {
    	$.ajax({
			type : 'post',
			url : SIDEURL+'kanban/searchDueTask',
			data : $('#last_remember').serialize(),
			success : function(data){
				$("#kanban_view").html(data);
				$('.scroll1').slimScroll({
				color: '#17A3E9',
				height : '160',
		 	    wheelStep: 12,
		 	    showOnHover : true
			 });
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
	$('#dvLoading').fadeIn('slow');
    if(val!=''){
    	$.ajax({
			type : 'post',
			url : SIDEURL+'kanban/searchDueTask',
			data : $('#last_remember').serialize(),
			success : function(data){
				$("#kanban_view").html(data);
				$('.scroll1').slimScroll({
					color: '#17A3E9',
					height : '160',
			 	    wheelStep: 12,
			 	    showOnHover : true
			 	});
				$('#dvLoading').fadeOut('slow');
			}
		});	
    } else {
    	$.ajax({
			type : 'post',
			url : SIDEURL+'kanban/searchDueTask',
			data : $('#last_remember').serialize(),
			success : function(data){
				$("#kanban_view").html(data);
				$('.scroll1').slimScroll({
				color: '#17A3E9',
				height : '160',
		 	    wheelStep: 12,
		 	    showOnHover : true
			 });
				$('#dvLoading').fadeOut('slow');
			}
		});
    }
}

function setCalProjectchecked(elemName){
	elem = $("input[name='"+elemName+"']");
	if($("#calender_project_all").prop("checked") == true){
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
	$('input[name="calender_project_id[]"]:checkbox:checked').each(function(i){
       val[i] = $(this).val();
    });
    var str = $('#last_remember').serialize();
	
    $('#dvLoading').fadeIn('slow');
   	if(FUN == "myCalender"){
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});
        }
    } else if(FUN == "weekView" || FUN == "NextFiveDayView"){ 
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});
        }
     }
}

function setCalProjectUnchecked(id){
	var val = [];
	$('input[name="calender_project_id[]"]:checkbox:checked').each(function(i){
       val[i] = $(this).val();
    });
	if($("#calender_project_"+id).is(":checked")){
		//val[i] = $(this).val();
		
	} else {
		$("#calender_project_all").prop("checked", false);
		$("#calender_project_all").closest("span").removeClass("checked");
	}
	var str = $('#last_remember').serialize();
	
    $('#dvLoading').fadeIn('slow');
    if(FUN == "myCalender"){ 
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});
        }
    } else if(FUN == "weekView" || FUN == "NextFiveDayView"){
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});
        }
    }
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
    if(FUN == "myCalender"){
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});
        }
    } else if(FUN == "weekView" || FUN == "NextFiveDayView"){ 
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');	
				}
			});
        }
    }
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
    if(FUN == "myCalender"){
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchTask",
				data : {str:str,year:$("#year").val(),month:$("#month").val()},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});
        }
    } else if(FUN == "weekView" || FUN == "NextFiveDayView"){
        if(val!=''){
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');
				}
			});	
        } else {
        	$.ajax({
				type : 'post',
				url : SIDEURL+"calender/searchWeekTask",
				data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
				success : function(data){
					$("#sjcalendar").html(data);
                	$('#dvLoading').fadeOut('slow');	
				}
			});
        }
    }
}