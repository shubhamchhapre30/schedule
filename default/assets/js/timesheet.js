$(document).ready(function(){
        $(document).on("click","#new_timesheet",function(){ 
            $.ajax({
		type : 'post',
		url : SITE_URL +'timesheet/max_date',
                success : function(e){ 
                    var data = jQuery.parseJSON(e); 
                    var view = '<option value="">Select user</option>';
                    if(data.user_list != '0' ){ 
                        $.each( data.user_list, function( i, value ) {
                            if(data.user_access == 'user'){
                                view += '<option value="'+data.user_list[i].user_id+'" selected="selected">' + data.user_list[i].first_name + ' '+ data.user_list[i].last_name+'</option>'
                            }else{
                                var select = '';
                                if(data.login_user == data.user_list[i].user_id){
                                  select = 'selected="selected"';   
                                }
                                view += '<option value="'+data.user_list[i].user_id+'" '+select+'>' + data.user_list[i].first_name + ' '+ data.user_list[i].last_name+'</option>'
                            }
                        });
                    }
                    if(data.user_access == 'admin' || data.user_access == 'manager'){
                        $("#showuserlist").css('display','block');
                    }
                    $("#timesheet_to_another").html(view);
                    $("#timesheet_fromdate").val(data.max_date);   
                    $(".input-append.date.date-picker.dd").datepicker('setDate',data.max_date);
                    $("#to_date-error").remove();
                    $("#timesheet_todate").val('');
                    $("#newtimesheet").modal("show");
                    $('#newtimesheet').on('shown.bs.modal', function () {
                    });  
                }
            });
        });
    
        $(".change_customer_timesheet").on('change',function(){ 
            var view = $(".change_customer_timesheet").val();
            $('#dvLoading').fadeIn('slow');
            $.ajax({
                    type : 'post',
                    url : SITE_URL +'timesheet/timesheet_filter',
                    data :{
                        view:view,
                        timesheet_id :$("#hidden_timesheet_id").val()
                    },
                    success : function(e){
                       // console.log(e);
                        $("#change_timesheet_view").replaceWith(e);
                        if(view == 'time'){
                            $("#total_counts").replaceWith('<b id="total_counts">'+$("#hidden_total_time").val()+'</b>');
                        }else if(view == 'revenue'){
                            $("#total_counts").replaceWith('<b id="total_counts">'+$("#hidden_revenue").val()+'</b>');
                        }else{
                            $("#total_counts").replaceWith('<b id="total_counts">'+$("#hidden_cost").val()+'</b>');
                        }
                        $('#dvLoading').fadeOut('slow');
                    },
                    error : function(e){
                        console.log('Ajax request not found');
                        $('#dvLoading').fadeOut('slow');
                    }
            });
        });          
    
        $(document).on('click','#cancel_timesheet_popup',function(){
            $("#timesheet_task_popup").modal("hide");
        });
        $(document).on('click','#save_timesheet_task',function(){
            var form = $("#update_task_timesheet").serialize();
            
            $.ajax({
                type : 'post',
                url : SITE_URL +'timesheet/update_task_data',
                data:{
                    form:form,
                    date:$("#now_date").val(),
                    customer_id:$("#now_customer_id").val(),
                    timesheet_id:$("#hidden_timesheet_id").val()
                },
                success : function(e){
                    var change_date_task = $("#now_date").val();
                    var customer_id = $("#now_customer_id").val();
                    var data = jQuery.parseJSON(e);
                    var text = '';
                    var time = $("#hidden_date_time_"+change_date_task+'_'+customer_id).val();
                    var total_day_time = $("#hidden_date_time_"+change_date_task).val();
                    var specific_customer = $("#hidden_specific_customer_time_"+customer_id).val();
                    var overall_time = $("#hidden_overall_time").val();
                    text +='<td onclick="open_popup(\''+change_date_task+'\',\''+customer_id+'\');" id="timesheet_date_'+change_date_task+'_'+customer_id+'" >',
                    text +=data.total_time;
                    if(data.exception_flag =='1'){
                     text +='<i class="stripicon iconhigh"></i>';
                    }
                    if(data.day_change_flag =='1'){
                     text +='<i class="fa fa-check" style="color: #0de40d !important;"></i>';
                    }
                    text +='</td>'; 
                    $("#timesheet_date_"+change_date_task+"_"+customer_id).replaceWith(text);
                    
                    // column wise total time 
                    total_day_time -= time;
                    var new_time = total_day_time + data.current_time;
                    var hours = Math.floor(new_time / 60);          
                    var minutes = (new_time % 60); 
                    $("#total_time_"+change_date_task).text(hours+':'+(minutes==0?'00':minutes));
                    
                    
                    //row wise total time
                    specific_customer -= time;
                    var cus_time = specific_customer + data.current_time;
                    var hours1 = Math.floor(cus_time / 60);          
                    var minutes1 = (cus_time % 60); 
                    $("#specific_customer_total_"+customer_id).text(hours1+':'+(minutes1==0?'00':minutes1));
                   
                    
                    //change overall timesheet time
                    
                    overall_time -= time;
                    var overtime = overall_time + data.current_time;
                    var hours2 = Math.floor(overtime / 60);          
                    var minutes2 = (overtime % 60); 
                    $("#overall_total").text(hours2+':'+(minutes2==0?'00':minutes2));
                   
                    
                    $("#days_chnaged").text(data.total_changed_days);
                    $("#total_counts").text(hours2+'h'+(minutes2==0?'00m':minutes2+'m'));
                    $("#hidden_overall_time").val(overtime);
                    $("#hidden_date_time_"+change_date_task+'_'+customer_id).val(data.current_time);
                    $("#hidden_date_time_"+change_date_task).val(new_time);
                    $("#hidden_specific_customer_time_"+customer_id).val(cus_time);
                    $("#timesheet_task_popup").modal("hide");
                }
            });
        });
        
        $("#approver_comments").on('focusout',function(){
            var comment = $(this).val();
            var comment_id = $("#approver_comments_id").val();
            $.ajax({
                type : 'post',
                url : SITE_URL +'timesheet/add_approver_comment',
                data:{
                    comment_id : comment_id,
                    comment : comment,
                    timesheet_id : $("#hidden_timesheet_id").val(),
                    timesheet_user_id:$("#hidden_timesheet_user_id").val()
                },
                success : function(e){
                    $("#approver_comments_id").val(e);
                },
                error :function(e){
                    console.log('Ajax request not found');
                }
            });
        });
         
        $(".change_timesheet_list").on('change',function(){
                        var form = $("#filter_timesheet").serialize();
                        $.ajax({
                            type : 'post',
                            url : SITE_URL +'timesheet/sort_timesheets',
                            data:{
                                form:form
                            },
                            success : function(e){
                                if(e == "1"){
				    $("#alertify").show();
				    alertify.alert("End date must be greater than or equal to start date.", function (a) {
                                        $("#timesheet_end_date").focus(); $("#alertify").hide();$("#alertify-cover").css("position","relative");
                                        return false;
				    });
                                    return false;
				} else{
                                    $("#footer_pagination").remove(),
                                    $("#replace_table").html(e),
                                    $("#timesheet_viewtable1").dataTable({
                                                    order: [
                                                        [1, "asc"]
                                                    ],
                                                    columnDefs: [ {
                                                    "targets": 5,
                                                    "orderable": false
                                                    },{
                                                    "targets": 6,
                                                    "orderable": false
                                                    },{
                                                    "targets": 0,
                                                    "orderable": false
                                                    },{
                                                    "targets": 7,
                                                    "orderable": false
                                                    }  ],
                                                    paging: !1,
                                                    bFilter: !1,
                                                    searching: !1,
                                                    bLengthChange: !1,
                                                    info: !1,
                                                    language: {
                                                        emptyTable: "No Records found."
                                                    }
                                    }),$('#dvLoading').fadeOut('slow');
                                }
                            },
                            error :function(e){
                                console.log('Ajax request not found');
                            }
                        });
        });
});

function save_timesheet(){
    var from_date = $("#timesheet_fromdate").val();
    var to_date = $("#timesheet_todate").val();
    var user_id = $("#timesheet_to_another").val();
            if(from_date == '' || to_date == '' || user_id == ''){
                alertify.alert("All fields are required.");
                return false;
            }else{
                $('#dvLoading').fadeIn('slow');
		$.ajax({
			type : 'post',
			url : SITE_URL +'timesheet/create_timesheet',
                        data :$("#timesheet_data").serialize(),
			success : function(e){
                            var data = jQuery.parseJSON(e);
                            if(data.error_code =='1'){
                                $("#to_date-error").remove();
                               $("#timesheet_todate").parent().append(data.error_msg);
                               $("#timesheet_todate").focus();
                            }else{
                                $("#to_date-error").remove();
                                var view = '';
                                view +='<tr id="id_'+data.timesheet.timesheet_id+'">',
                                view +='<td><input type="checkbox" name="timesheet_check_'+data.timesheet.timesheet_id+'" id="timesheet_check_'+data.timesheet.timesheet_id+'" value="" disabled="disabled"/></td>',        
                                view +='<td><a href="javascript:void(0);" onclick="open_timesheet('+data.timesheet.timesheet_id+');">'+data.timesheet.first_name+' '+data.timesheet.last_name+'</a></td>',
                                view +='<td><a href="javascript:void(0);" onclick="open_timesheet('+data.timesheet.timesheet_id+');">'+data.from_date+'</a></td>',
                                view +='<td><a href="javascript:void(0);" onclick="open_timesheet('+data.timesheet.timesheet_id+');">'+data.to_date+'</a></td>',
                                view +='<td style="text-transform: capitalize;" ><a href="javascript:void(0);" onclick="open_timesheet('+data.timesheet.timesheet_id+');">'+data.timesheet.timesheet_status+'</a></td>',
                                view +='<td><a href="javascript:void(0);" onclick="open_timesheet('+data.timesheet.timesheet_id+');">-</a></td>',
                                view +='<td><a href="javascript:void(0);" onclick="open_timesheet('+data.timesheet.timesheet_id+');">'+data.total_hours+'</a></td>',
                                view +='<td>',
                                view +='<form  method="POST" action="'+SITE_URL+'timesheet/showtimesheet" name="myForm_'+data.timesheet.timesheet_id+'" id="myForm_'+data.timesheet.timesheet_id+'" >',
                                view +='<input type="hidden" name="timesheet_id" id="timesheet_id" value="'+data.timesheet.timesheet_id+'" />',
                                view +='</form>',
                                view +='<a href="javascript:void(0);" onclick="open_timesheet('+data.timesheet.timesheet_id+');"><i class="icon-pencil tmsticn"  style="transform: scale(0.75);"></i> </a>' ,
                                view +='<a href="javascript:void(0);" onclick="delete_timesheet('+data.timesheet.timesheet_id+');" id="delete_timesheet_'+data.timesheet.timesheet_id+'"> <i class="icon-trash tmsticn" style="transform: scale(0.75);"></i> </a>',
                                view +='</td></tr>';
                                //console.log(view);
                                ($(".dataTables_empty").length == '1')? $(".dataTables_empty").parent().remove():'';
                                $("#timesheet_list").append(view);
                                $("#newtimesheet").modal("hide");
                            }
                            $('#dvLoading').fadeOut('slow');
			},
                        error : function(e){
                            $('#dvLoading').fadeOut('slow');
                            console.log("Ajax request not found.");
                        }
		});
            }
    
}

function timesheet_deletion(id){
    var s = "Are you sure, you want to delete this timesheet?";
    $('#delete').confirmation('show').on('confirmed.bs.confirmation',function(){
                $("#dvLoading").fadeIn("slow");$.ajax({
            type : 'post',
	    url : SITE_URL +'timesheet/delete_timesheet',
            data:{
                id :id
            },
	    success : function(e){
                window.location.href = SIDE_URL+"timesheet/index";
                $('#dvLoading').fadeOut('slow');
            },
            error :function(e){
                console.log('Ajax request not found');
                $('#dvLoading').fadeOut('slow');
            }
        });
    });
}

function delete_timesheet(id){
    var s = "Are you sure, you want to delete this timesheet?";
    $('#delete_timesheet_'+id).confirmation('show').on('confirmed.bs.confirmation',function(){
                $("#dvLoading").fadeIn("slow");
                $.ajax({
            type : 'post',
	    url : SITE_URL +'timesheet/delete_timesheet',
            data:{
                id :id
            },
	    success : function(e){
                $("#id_" + id).remove();
            },
            error :function(e){
                console.log('Ajax request not found');
            }
        });
    });
                        
}

function open_timesheet(id){
    document.getElementById("myForm_"+id).submit();
}

function open_popup(date,customer_id){
  var timesheet_status = $("#hidden_timesheet_status").val();
  if(timesheet_status == 'draft'){
    $.ajax({
            type : 'post',
	    url : SITE_URL +'timesheet/get_task_popup_data',
            data:{
                date :date,
                customer_id : customer_id,
                timesheet_user_id : $("#hidden_timesheet_user_id").val()
            },
	    success : function(e){
                var data = jQuery.parseJSON(e);
                var view ='';
                view +='<tbody id="timesheet_task_list">';
                if(data.tasks!= '0' ){ 
                    $.each( data.tasks, function( i, value ) {
                        view +='<tr>',
                        view +='<td style="text-align:left">'+data.tasks[i].task_title+'</td>';
                        if(data.tasks[i].project_title == null){
                            view +='<td style="text-align:left">-</td>';
                        }else{
                            view +='<td style="text-align:left">'+data.tasks[i].project_title+'</td>';
                        }
                        view +='<td>'+Math.floor(data.tasks[i].task_time_estimate / 60)+'h'+(data.tasks[i].task_time_estimate % 60)+'m'+'</td>',
                        view +='<td>'+Math.floor(data.tasks[i].task_time_spent / 60)+'h'+(data.tasks[i].task_time_spent % 60)+'m'+'</td>',
                        view +='<td><input style= "width: 95px;" class="check_field" id="'+data.tasks[i].task_id+'" type="text" name="'+data.tasks[i].task_id+'" value="'+Math.floor(data.tasks[i].billed_time / 60)+'h'+(data.tasks[i].billed_time % 60)+'m'+'"/>',
                        view +='<input class="check_field_'+data.tasks[i].task_id+'" type="hidden" name="hiden_data" value="'+Math.floor(data.tasks[i].billed_time / 60)+'h'+(data.tasks[i].billed_time % 60)+'m'+'"/></td>',
                        view +='</tr>'

                      });
                }else{ 
                    view +='<tr><td colspan="5">No record Found..</td></tr>';
                }
                  view +='</tbody>';
                 // console.log(view);
                 if(data.tasks =='0'){
                     $("#save_timesheet_task").css('display','none');
                 }else{
                     $("#save_timesheet_task").css('display','block');
                 }
                $("#now_date").val(data.now_date);
                $("#now_customer_id").val(customer_id);
                $("#timesheet_task_list").replaceWith(view);
                $("#popup_header").text("Day : "+data.date);
                $("#timesheet_task_popup").modal('show');
                $('#dvLoading').fadeOut('slow');
            },
            error :function(e){
                console.log('Ajax request not found');
                $('#dvLoading').fadeOut('slow');
            }
        });
    }
}

function submit_for_approval(id){
    var s = "Are you sure, you want to submit this timesheet for approval?";
    $("#alertify").show(), alertify.confirm(s, function(s) {
        return 1 == s && ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type : 'post',
	    url : SITE_URL +'timesheet/submit_timesheet',
            data:{
                timesheet_id: id,
                comment_id : $("#timesheet_comment_id").val(),
                comment : $("#save_timesheet_comment").val(),
                timesheet_user_id : $("#hidden_timesheet_user_id").val()
            },
	    success : function(e){
                $("#timesheet_comment_id").val(e);
                $("#timesheet_status").text('Submitted');
                $("#save_timesheet_comment").prop('disabled','disabled');
                $("#approver_comments").prop("disabled",'disabled');
                $("#draft").prop('disabled','disabled');
                $("#delete").prop('disabled','disabled');
                $("#approval").prop('disabled','disabled');
                if(APPROVER_ID =='0'){
                 $("#approve").css("display","block");
                }
                $("#recall").css('display','block');
                $("#hidden_timesheet_status").val('submitted');
                $('#dvLoading').fadeOut('slow');
            },
            error :function(e){
                console.log('Ajax request not found');
                $('#dvLoading').fadeOut('slow');
            }
        }));
    });
}

function timesheet_approve(id){
    var s = "Are you sure, you want to approve this timesheet?";
    $("#alertify").show(), alertify.confirm(s, function(s) {
        return 1 == s && ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type : 'post',
	    url : SITE_URL +'timesheet/approve_timesheet',
            data:{
                timesheet_id: id
            },
	    success : function(e){
                $("#timesheet_status").text('Approved');
                $("#save_timesheet_comment").prop('disabled','disabled');
                $("#approver_comments").prop('disabled','disabled');
                $("#draft").css('display','none');
                $("#delete").css('display','none');
                $("#approval").css('display','none');
                $("#recall").css('display','none');
                $("#return_to_draft").css('display','block');
                $("#approve").css("display","none");
                $("#hidden_timesheet_status").val('approved');
                $('#dvLoading').fadeOut('slow');
            },
            error :function(e){
                console.log('Ajax request not found');
                $('#dvLoading').fadeOut('slow');
            }
        }));
    });
}

function return_to_draft(id){
    var s = "Are you sure, you want to save this timesheet as draft?";
    $("#alertify").show(), alertify.confirm(s, function(s) {
        return 1 == s && ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type : 'post',
	    url : SITE_URL +'timesheet/return_to_draft',
            data:{
                timesheet_id: id
            },
	    success : function(e){
                $("#timesheet_status").text('Draft');
                $("#draft").css('display','block');
                if(LOGIN_USER_ID == $("#hidden_timesheet_user_id").val()){
                    $("#delete").css('display','block');
                    $("#save_timesheet_comment").removeProp('disabled');
                }
                $("#return_to_draft").css('display','none');
                $("#approve").css("display","block");
                $("#approver_comments").removeProp('disabled');
                $("#hidden_timesheet_status").val('draft');
                $('#dvLoading').fadeOut('slow');
            },
            error :function(e){
                console.log('Ajax request not found');
                $('#dvLoading').fadeOut('slow');
            }
        }));
    });
}

function excel_generate(){
    var checkboxes = document.getElementsByName('timesheet_check[]');
    var vals = [];
        for (var i=0, n=checkboxes.length;i<n;i++){
            if (checkboxes[i].checked){
                vals.push(checkboxes[i].value);
            }
        } 
        if(vals ==''){
            toastr['warning']("Please select a timesheet for export.","");
            return false;
        }else{
            $("#dvLoading").fadeIn("slow");
             window.open(
                    SITE_URL +'timesheet/export_timesheets?timesheet_ids='+vals,
                    '_blank' // <- This is what makes it open in a new window.
              );
            for (var i=0, n=checkboxes.length;i<n;i++){
                if (checkboxes[i].checked){
                    $("#id_"+checkboxes[i].value).css('display','none');
                }
            }   
              
            $("#dvLoading").fadeOut("slow");
        }

}

function save_as_draft(id){
    
            var comment = $("#save_timesheet_comment").val();
            var comment_id = $("#timesheet_comment_id").val();
            $("#dvLoading").fadeIn("slow");
            $.ajax({
                type : 'post',
                url : SITE_URL +'timesheet/add_timesheet_comment',
                data:{
                    comment_id : comment_id,
                    comment : comment,
                    timesheet_id : id,
                    timesheet_user_id : $("#hidden_timesheet_user_id").val()
                },
                success : function(e){
                    $("#timesheet_comment_id").val(e);
                    $('#dvLoading').fadeOut('slow');
                },
                error :function(e){
                    console.log('Ajax request not found');
                    $('#dvLoading').fadeOut('slow');
                }
            });
}

function pagination(page){
    $("#dvLoading").fadeIn("slow");
            $.ajax({
                type : 'post',
                url : SITE_URL +'timesheet/pagination',
                data:{
                    page_no : page
                },
                success : function(e){
                    $("#replace_table").html(e);
                    $("#pagination li").removeClass('active');
                    $("#"+page).addClass('active');
                    $("#timesheet_viewtable1").dataTable({
                        order: [
                                [1, "asc"]
                                ],
                        columnDefs: [ {
                                "targets": 5,
                                "orderable": false
                                },{
                                "targets": 6,
                                "orderable": false
                                },{
                                "targets": 0,
                                "orderable": false
                                },{
                                "targets": 7,
                                "orderable": false
                                }  ],
                        paging: !1,
                        bFilter: !1,
                        searching: !1,
                        bLengthChange: !1,
                        info: !1,
                        language: {
                                emptyTable: "No Records found."
                        }
                    });
                    $('#dvLoading').fadeOut('slow');
                },
                error :function(e){
                    console.log('Ajax request not found');
                    $('#dvLoading').fadeOut('slow');
                }
            });
}

function timesheet_recall(timesheet_id){
    var s = "Are you sure, you want to recall this timesheet?";
    $("#alertify").show(), alertify.confirm(s, function(s) {
        return 1 == s && ($("#dvLoading").fadeIn("slow"), void $.ajax({
                type : 'post',
                url : SITE_URL +'timesheet/timesheet_recall',
                data:{
                    timesheet_id : timesheet_id,
                    timesheet_user_id:$("#hidden_timesheet_user_id").val()
                },
                success : function(e){
                    
                    $("#timesheet_status").text('Draft');
                    $("#draft").css('display','block');
                    $("#draft").removeProp('disabled');
                    
                    if(LOGIN_USER_ID == $("#hidden_timesheet_user_id").val()){
                        $("#save_timesheet_comment").removeProp('disabled');
                        $("#approval").css('display','block');
                        $("#approval").removeProp('disabled');
                    }
                    $("#delete").css('display','block');
                    $("#delete").removeProp('disabled');
                    
                    if(LOGIN_USER_ID != $("#hidden_timesheet_user_id").val() ){
                        $("#approve").css("display","block");
                    }
                    $("#recall").css('display','none');
                    $("#hidden_timesheet_status").val('draft');
                    $('#dvLoading').fadeOut('slow');
                },
                error :function(e){
                    console.log('Ajax request not found');
                    $('#dvLoading').fadeOut('slow');
                }
            }));
        });
}

$(document).ready(function(){
    $(document).on('blur','.check_field',function(){
                var val = $(this).val();
		var splitval = val.split(":");
                var id = $(this).attr("id");
                var data = $(".check_field_"+id).val();
                if(val != data){
                    if(validate(val) == true){
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
							$(this).val(time);
							
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
							$(this).val(time);
							
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
							$(this).val(time);
							
						}else{
							var mm = val;
							var time = mm + "m";
							$(this).val(time);
							
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
							$(this).val(time);
						
							
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
							$(this).val(time);
							
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
							$(this).val(time);
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
							$(this).val(time);
						}
					}
					if(val.length>=5 && splitval.length!=2){
                                                $(this).val('');
                                                //$(this).focus();
						alertify.alert('maximum 4 digits allowed');
					}
                    }else {
                    	$(this).val('');
                        //$(this).focus();
			alertify.alert('your inserted value is not correct, please insert correct value');
		    }
		}
	});
});
$(document).on('keypress','.check_field',function (e) {
    var k = e.keyCode || e.which;
    if (k == 13) { 
        return false; // !!!
    }
});

function create_invoice(){
    var checkboxes = document.getElementsByName('timesheet_check[]');
    var vals = [];
        for (var i=0, n=checkboxes.length;i<n;i++){
            if (checkboxes[i].checked){
                vals.push(checkboxes[i].value);
            }
        } 
        if(vals ==''){
            alertify.alert("Please select a timesheet for export.");
            return false;
        }else{
          
            $.ajax({
                type: 'get',
                url: SITE_URL + 'timesheet/get_timesheet_customer_list?timesheet_ids='+vals,
                success: function (e) {
                    var data = jQuery.parseJSON(e);
                    if(data.customerlist == '0'){
                        toastr['error']("Selected timesheets doesn't contain any customer to export in xero.", "");
                    }else if(data.customerlist == '1'){
                        toastr['error']("Selected timesheets are already exported in xero.", "");
                    }else{
                        var view = '<option value="all">All</option>';
                        if (data.customerlist) {
                            $.each(data.customerlist, function (i, value) {
                                view += '<option value="' + data.customerlist[i].customer_id + '">' + data.customerlist[i].customer_name+'</option>'
                            });
                        }
                        $("#selected_customer_invoice").html(view);
                        $("#XeroTimesheetExport").modal('show');
                    }
                },
                error: function (e) {
                    console.log('Ajax request not found');
                }
            });
        }
}

$(document).ready(function(){
    $("#xero_invoice_option").on("click",function(){
        var is_project_separte = $("#is_projecr_separate").prop('checked')?1:0;
        var customer_id = $("#selected_customer_invoice").val();
        var checkboxes = document.getElementsByName('timesheet_check[]');
        var vals = [];
        for (var i=0, n=checkboxes.length;i<n;i++){
            if (checkboxes[i].checked){
                vals.push(checkboxes[i].value);
            }
        } 
        $("#XeroTimesheetExport").modal('hide');
        $("#dvLoading").fadeIn("slow");
        $.ajax({
            url: SIDE_URL + 'xero/testLinks?invoice=1&method=post&customers='+customer_id+'&separte_project='+is_project_separte+'&timesheets_id=' + vals,
            success: function (data) {
                var data = jQuery.parseJSON(data);
                if (data['success'] == 'success') {
                    $.ajax({
                        url: SIDE_URL + "timesheet/chk_timesheet_exported?timesheet_ids="+vals,
                        success: function (d) {
                            var data = jQuery.parseJSON(d);
                            if(data){
                                $.each(data, function (i, value) {
                                    if(value == '0'){
                                        $("#id_"+i).remove();
                                    }else{
                                        $("#status_"+i).text('Partially Exported');
                                    }
                                });
                                toastr['success']("Timesheet has been exported sucessfully.", "");
                                $('#dvLoading').fadeOut('slow');
                            }
                        }
                    });
                }else if (data['error_code'] == 'error') {
                    if (data['error_message'] == 'token_expired') {
                                toastr['error']("Access token has been Expired.Please reauthorize.","");
                    } else if (data['error_message'] == 'token_rejected') {
                        $.ajax({
                            url: SIDE_URL + "settings/update_xero_integration",
                            type: "post",
                            data: {
                                status: '0'
                            },
                            success: function (data) {
                                        toastr['error']("Schedullo has not been granted access to Xero.","");
                            }
                        });
                    } else if (data['error_message'] == '') {
                        var other_info = jQuery.parseJSON(data['other_info']);
                        if (other_info.Type == 'ValidationException') {
                            var message = data['other_msg'];
                                    toastr['error'](message,"");
                        }

                    } 
                    $('#dvLoading').fadeOut('slow');
                } 
            }
        });
    });
});



function cancel_export(timesheet_id){
    var s = "Please confirm that you to change the status of the timesheet to Approved.";
    $("#alertify").show(), alertify.confirm(s, function(s) {
        return 1 == s && ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type : 'post',
	    url : SITE_URL +'timesheet/xancel_timesheet_export',
            data:{
                timesheet_id: timesheet_id
            },
	    success : function(e){
                $("#timesheet_status").text('Approved');
                $("#export_cancel").css('display','none');
                $("#return_to_draft").css('display','block');
                $("#hidden_timesheet_status").val('approved');
                $('#dvLoading').fadeOut('slow');
            },
            error :function(e){
                console.log('Ajax request not found');
                $('#dvLoading').fadeOut('slow');
            }
        }));
    });
}