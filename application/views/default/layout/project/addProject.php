<?php 
$theme =  getThemeName();
	$theme_url = base_url().getThemeName();
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	if($date_arr_java[$site_setting_date]=='dd M,yyyy'){
            $size=11;
        }else{
            $size=10;
        }
        $default_format = $this->config->item('company_default_format'); 
        $dat='';
        foreach ($date_arr_java  as $v=>$val){
            if($v==$default_format){
                $dat=$val;
            }
        }
        
        $new_date = date("Y-m-d H:i:s");
        date_default_timezone_set($this->session->userdata("User_timezone")); 
        $date_to_timezone = date($site_setting_date,strtotime(toDateNewTime($new_date)));
	$com_off_days = get_company_offdays();
	$s3_display_url = $this->config->item('s3_display_url');
	$bucket = $this->config->item('bucket_name');
	$task_status_completed_id = $this->config->item('completed_id');
	        
        $completed_id = $this->config->item('completed_id');
	$company_flags = $this->config->item('company_flags');
        $actaul_time_on = '0';
        $allow_past_task = "1";
        if($company_flags){
                $actaul_time_on = $company_flags['actual_time_on'];
                $allow_past_task = $company_flags['allow_past_task'];
        }
        if($allow_past_task == "1"){
                $start_date_picker = "-Infinity";
        } else {
                $start_date_picker = "this.date";
        }
        ini_set('max_execution_time', 300);
        if($project_id!=0){
            $total_chargeable_task = $this->project_model->get_all_chargeable_project_task($project_id);
            $total_task = $this->project_model->get_all_project_task($project_id);
           // pr($total_chargeable_task); die();
        }

                        $estimated_revenue = 0;
                        $estimated_cost = 0;
                        $committed_revenue = 0;
                        $committed_cost = 0;
                        $non_chargeable_category = get_non_chargeable_category($this->session->userdata('company_id'));
                        $non_billable_time = 0;
                        $non_billable_cost = 0;
                        $estimated_profit = 0;
                        $estimated_margin = 0;
                        $committed_profit = 0;
                        $committed_margin = 0;
                        $total_estimated_minute = 0;
                        if(!empty($total_chargeable_task)){
                            foreach($total_chargeable_task as $task){
                                $total_estimated_minute += $task['task_time_estimate'];
                                $estimated_revenue += $task['estimated_total_charge'];
                                if($task['task_status_id']==$task_status_completed_id){
                                   $committed_revenue += $task['actual_total_charge'];
                                }
                            }
                        }
                       // echo $total_estimated_minute; die();
                        if(!empty($total_task)){
                         
                            foreach($total_task as $task){
                                   $employee_rate = $task['cost_per_hour'];
                                    $estimated_cost += round(($task['task_time_estimate']*$employee_rate)/60,2);
                                        if($task['task_status_id']==$task_status_completed_id){
                                           // $committed_revenue += $task['actual_total_charge'];
                                            $committed_cost += round(($task['task_time_spent']*$employee_rate)/60,2);
                                        }
                               
                            }
                             foreach($total_task as $task){
                                 $employee_rate = $task['cost_per_hour'];
                                 if(!empty($non_chargeable_category)){
                                    foreach($non_chargeable_category as $category){
                                       if($category->category_id == $task['task_category_id'] && $task['task_status_id']==$task_status_completed_id){
                                           $non_billable_cost += round(($task['task_time_spent']*$employee_rate)/60,2);
                                           $non_billable_time += $task['task_time_spent'];

                                       }
                                   }
                                 }

                             }
                        
                             if($project_fixed_price !='0'){
                                 $estimated_revenue = $project_fixed_price;
                             }else if($project_base_rate !='0'){
                                 $estimated_revenue = round(($total_estimated_minute * $project_base_rate)/60,2);
                             }else{
                                 $estimated_revenue = $estimated_revenue;
                             }

                          //  $estimated_profit = $estimated_revenue - $estimated_cost;
                            if($estimated_revenue==0){
                                $estimated_profit = $estimated_revenue - $estimated_cost;
                                $estimated_margin = 0;
                            }else{
                                $estimated_profit = $estimated_revenue - $estimated_cost;
                                $estimated_margin = round(($estimated_profit/$estimated_revenue)*100,2);
                            }


                            $committed_revenue = $committed_revenue;
                            $committed_cost = $committed_cost;
                           // $committed_profit = $committed_revenue - $committed_cost;
                             if($committed_revenue==0){
                                 $committed_profit = $committed_revenue - $committed_cost;
                                 $committed_margin = 0;
                             }else{
                                 $committed_profit = $committed_revenue - $committed_cost;
                                 $committed_margin = round(($committed_profit/ $committed_revenue)*100, 2);

                             }

                            $hours = intval($non_billable_time/60);
                            $minutes = $non_billable_time - ($hours * 60);

                            $total_non_billable_time = $hours.".".$minutes."h";
                            $non_billable_cost = $non_billable_cost;
                            $non_billable_time = $total_non_billable_time;
                        }  
        
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemename();?>/assets/plugins/chosen-bootstrap/chosen/chosen.css?Ver=<?php echo VERSION;?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/css/bootstrap-editable.css?Ver=<?php echo VERSION;?>" />
<script src="<?php echo $theme_url;?>/assets/plugins/jquery.mockjax.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/js/bootstrap-editable.min.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/scripts/form-editable.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemename();?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.js?Ver=<?php echo VERSION;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/css/context.standalone.css?Ver=<?php echo VERSION;?>">
<script src="<?php echo $theme_url;?>/js/context.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript">
        var status = '';
	var ACTIVE_MENU = 'from_project';
	var ACTUAL_TIME_ON = '<?php echo $actaul_time_on; ?>';
	var COMPLETED_ID = '<?php echo $task_status_completed_id;?>';
	var START_DATE_PICKER = '<?php echo $start_date_picker;?>';
	var DATE_ARR = '<?php echo $date_arr_java[$default_format]; ?>';
	var S3_DISPLAY_URL = '<?php echo $s3_display_url;?>';
</script>
<script type="text/javascript">


function showhide()
{
        $('#common-sortbybox').hide();
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
                $( "#common-timerbox" ).draggable();
                $("#is_timer_on").val("1");
        }
        $('#common-colorbox').hide();
}
$(function(){
	$("#redirect_page").val('from_project');
	prjbrowseClicked();
         $(".full-width div").addClass("before_timer");
	$(".close_cmt").click(function(){
		$("#comments").modal("hide");
	});
});
function right_click_delete(a, b, c, d) {
    var s = "Are you sure, you want to delete this task?";
//    alertify.confirm(s, function(s) {
//        1 == s && 
                $.ajax({
            type: "post",
            url: SIDE_URL + "kanban/delete_task",
            data: {
                task_id: a
            },
                success: function(r) {
                    var r = jQuery.parseJSON(r);
                     $("#task_tasksort_" + a).remove(), 
//                             alertify.set("notifier", "position", "top-right"), 
//                             alertify.log("Task has been deleted successfully.")
                     toastr['success']("Task '"+r.task_title+"' has been deleted.", "");
                }
            })
//        })
}
function opendelete(a, e, t, l) {
    $("#delete_series span").removeClass("checked"), $("#delete_ocuurence span").removeClass("checked"), $("#delete_future span").removeClass("checked"), $("#delete_series").attr("onclick", "delete_rightClick_task('" + e + "','" + t + "','" + l + "','series','" + a + "')"), $("#delete_ocuurence").attr("onclick", "delete_rightClick_task('" + a + "','" + t + "','" + l + "')"), $("#delete_future").attr("onclick", "delete_rightClick_task('" + e + "','" + t + "','" + l + "','future','" + a + "')"), $("#delete_task").modal("show")
}
function delete_rightClick_task(a, e, t, l, i) {
    var l = l || 1,
        i = i || a;
    $.ajax({
        type: "post",
        url: SIDEURL + "kanban/delete_task",
        data: {
            task_id: a,
            from: l
        },
        success: function(a) {
            if ("done" == a) $("#delete_task").modal("hide"), alertify.set("notifier", "position", "top-right"), alertify.log("Task has been deleted successfully.");
            else {
                $("#task_tasksort_" + i).remove(), $("#delete_task").modal("hide"), alertify.set("notifier", "position", "top-right"), alertify.log("Task has been deleted successfully.")
            }
        }
    })
}
 
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#project_status,#division_id, #department_id").chosen({
            "disable_search": true
        });
        $("#select_task_status,#select_task_assign").chosen({
            "disable_search": true,
            width: "85% !important"
        });
       $("#project_title1").focus();
       $(document).on('click','#project_title1',function(){
           var new_title1 = $("#old_project_title").val();
           var new_title = '';  
            new_title += '<input type="text"  class="txt-style radius-b " id="project_title1" name="project_title1"  value="'+new_title1+'"/>';
            new_title += '<button type="button" id="save_title" name="save_title" class="btn blue txtbold title_input" style="line-height: 20px !important;"> <i class="icon-ok"></i> </button>';
            new_title += '<button type="button" id="cancel_title" name="cancel_title" class="btn  txtbold title_input" style="line-height: 20px !important;"> <i class="icon-remove"></i> </button>';
            new_title += '<span class=" input-load setting-select-load" id="project_title_loading"></span>';
            new_title += '<input type="hidden" id="old_project_title" name="old_project_title" value="'+new_title1+'" />';
            $("#project_id_1").html(new_title);
            (function($){
                $.fn.focusTextToEnd = function(){
                    this.focus();
                    var $thisVal = this.val();
                    this.val('').val($thisVal);
                    return this;
                }
            }(jQuery));

            $('#project_title1').focusTextToEnd();
            
            $("#project_title1").focus();
           
       });
       
       $(document).on('click','#save_title',function(){ 
           var title = $("#project_title1").val();
           
           if(title !==''){
                        $.ajax({
                                type : 'post',
                                url : '<?php echo site_url("project/project_update");?>',
                                data : {project_id : $("#check_project_id").val(),name:'project_title',value:title},
				success : function(data){ 
                                            if(data =='0'){
                                                var new_title = '';   
                                                          //  new_title += '<div class=" col-md-6 controls relative-position" id="pro_id1" style=" margin-bottom: 2px !important;margin-top:2px;">';
                                                            new_title += '<a href="javascript:void(0)" class="txt-style edit_title  default_color" id="project_title1" name="project_title1" >'+title+'</a>';
                                                            new_title += '<span class=" input-load setting-select-load" id="project_title_loading"></span>';
                                                            new_title += '<input type="hidden" id="old_project_title" name="old_project_title" value="'+title+'" />';
                                                          //  new_title += '</div>';
                                                            
                                                            $("#project_id_1").html(new_title);
                                                            $('#dvLoading').fadeOut('slow');
                                            }else{
                                                 var new_title = '';  
                                                        new_title += '<a href="javascript:void(0)" class="txt-style edit_title  default_color" id="project_title1" name="project_title1" >'+title+'</a>';
                                                        new_title += '<span class=" input-load setting-select-load" id="project_title_loading"></span>';
                                                        new_title += '<input type="hidden" id="old_project_title" name="old_project_title" value="'+title+'" />';
                                                        
                                                        $("#project_id_1").html(new_title);
                                                        $(".main_project").val(data);
							$('#check_project_id').val(data);
                                                        $("#project_status").removeAttr('disabled');
                                                        $("#project_start_date").removeAttr('disabled');
                                                        $("#project_end_date").removeAttr('disabled');
                                                        $("#project_desc").removeAttr('disabled');
                                                        $("#division_id").removeAttr('disabled');
                                                        $("#department_id").removeAttr('disabled');
                                                        $("#project_customer_id").removeAttr('disabled');
                                                        $("#project_section").removeAttr('disabled');
                                                        $("#project_comment").removeAttr('disabled');
                                                        $("#select_task_status").removeAttr('disabled');
                                                        $("#select_task_assign").removeAttr('disabled');
                                                        
                                                        $.ajax({
                                                                type : 'post',
                                                                url : '<?php echo site_url("project/defaultMember");?>',
                                                                data : {project_id : data},
                                                                success : function(responseData){
                                                                        $("#memberlist").html(responseData);
                                                                        $('#dvLoading').fadeOut('slow');

                                                                }
                                                        });
                                                        
                                                        
                                                        $.ajax({
                                                                type : 'post',
                                                                url : '<?php echo site_url("project/get_default_section_id");?>',
                                                                data : {project_id : data},
                                                                success : function(section){ 
                                                                        $.ajax({
                                                                                type : 'post',
                                                                                url : '<?php echo site_url("project/defaultSection");?>',
                                                                                data : {project_id : data,section_id :section},
                                                                                success : function(responseData){
                                                                                        $("#task_result").html(responseData);
                                                                                        $('#dvLoading').fadeOut('slow');

                                                                                }
                                                                        });
                                                                }
                                                        });
                                                        
                                                        callhistory(data);
                                                        
						}
						
					},
                                        error:function(data){
                                            console.log("Ajax request not recived.");
                                            $('#dvLoading').fadeOut('slow');
                                        }
                                     
                                        });
           }else{
               
               alertify.alert("Please enter project title.");
               
           }
           
       });
       
       $(document).on("click","#cancel_title",function(){ 
      
        
         var title = $("#project_title1").val();
                var new_title = '';   
              //  new_title += '<div class=" col-md-6 controls relative-position" id="project_id_1" style=" margin-bottom: 2px !important;margin-top:2px;">';
                new_title += '<a href="javascript:void(0)" class="txt-style edit_title default_color" id="project_title1" name="project_title1" >'+title+'</a>';
                new_title += '<span class=" input-load setting-select-load" id="project_title_loading"></span>';
                new_title += '<input type="hidden" id="old_project_title" name="old_project_title" value="'+title+'" />';
                
                $("#project_id_1").html(new_title);
       });
    });


</script>
<script type="text/javascript">

 function stripslashes(str) {
 return str.replace(/\\'/g,'\'').replace(/\"/g,'"').replace(/\\\\/g,'\\').replace(/\\0/g,'\0');
}
function save_task_for_timer(t, a, e, s, i, _) {
    if ($(t).hasClass("before_timer")) return !1;
    
    var r = $("#timer_task_id").val();
    if (r) {
        var n = $("#or_color_" + r).val();
        $("#task_" + r).css("border", "1px solid " + n)
    }
    if ("1" != i) {
        var d = $("#task_data_" + a).val();
        $("#dvLoading").fadeIn("slow"), $.ajax({
            type: "post",
            url: SIDEURL + "kanban/save_task",
            data: {
                post_data: d
            },
            success: function(t) {
                var e = t;
                var g = $("#select_task_assign").val(),
                h = $("#select_task_status").val();
                $("#timer_task_id").val(e),  $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    data: {
                                        task_id: e,
                                        type: h,
                                        user_id: g
                                    },
                                    async: !1,
                                    success: function(rs) { 
                                        App.init(), $("#task_tasksort_" + a).replaceWith(rs);
                                    }
                                }), $("#dvLoading").fadeOut("slow")
            }
        });
        var s = 0
    } else {
        $("#timer_task_id").val(a);
    }
           $("#task_com_status").val(_), 
           $(".full-width div").addClass("before_timer"),    
           $(".task_tasksort ul> li > div> a").removeClass("after_timer_on"), 
           $(".task_tasksort ul >li >em >div> a").removeClass("after_timer_on");
            setTimeout(function() {
        chk_task_selected(e, s)
    }, 2e3)
}
function prjlinkClicked(){
	$("#prj_file_name1").val('');
	$("#prj_file_link").val('');
	$("#prj_file_name1").show();
	$("#prj_file_link").show();
	$("#prj_upload-link-btn").show();
}

function prjbrowseClicked(){
	$("#prj_file_name1").hide();
	$("#prj_file_link").hide();
	$("#prj_upload-link-btn").hide();
        
}


function replacelinkClicked(){
	$("#replace_file_name").val('');
	$("#replace_file_link").val('');
	$("#replace_file_name").show();
	$("#replace_file_link").show();
	$("#replace-upload-link-btn").show();
}

function replacebrowseClicked(){
	$("#replace_file_name").hide();
	$("#replace_file_link").hide();
	$("#replace-upload-link-btn").hide();
}

function comments_html(task_id){
	if(task_id){
		$("#comment_list_task_id").val(task_id);
		$.ajax({
			url : SIDE_URL+"kanban/commets_html",
			data : {task_id : task_id},
			cache: false,
		    dataType: "json",
			success : function(responseData){
				var cmt_str = '';
				$.map(responseData.task.comments,function(comment){
					cmt_str +='<li class="light"><div class="userimg">';
					if(comment.file_exist && comment.profile_image!=''){
						cmt_str += '<img src="'+S3_DISPLAY_URL+'upload/user/'+comment.profile_image+'" alt="img" class="img-circle" />';
					} else {
						cmt_str += '<img src="'+S3_DISPLAY_URL+'upload/user/no_image.jpg" alt="img" class="img-circle" />';
					}
					cmt_str += '</div><div class="userdetail" style="width: 90%;">';
					cmt_str += '<div class="usertxt">'+comment.first_name+' '+comment.last_name;
					cmt_str += '</div>';
					cmt_str += '<p class="usertxt2"> A '+comment.time_ago+'</p>';
					cmt_str += '<p id="orig_comment_'+comment.task_comment_id+'" class="wrap">'+comment.task_comment+'</p></div><div class="clearfix"> </div></li>';
				});



				$("#comments_html").html(cmt_str);

				$("#comments").modal("show");
			}
		});
	}

}

$(document).ready(function(){
        $("#frm_add_comment_from_list").validate({
		rules : {
			"task_comment_list" : {
				required : true
			}
		},
		submitHandler:function(){
			$("#cmts_list_submit").attr("disabled","disabled");
			$("#task_comment_list_loading").show();
			var cmt_task_id = $("#comment_list_task_id").val();
			$.ajax({
				type : 'post',
				url : SIDE_URL+"kanban/add_comment_from_list",
				data : $("#frm_add_comment_from_list").serialize(),
				success : function(responseData){
					responseData = jQuery.parseJSON(responseData);
					var cmt_str = '';
					$.map(responseData.task.comments,function(comment){
						cmt_str +='<li class="light"><div class="userimg">';
						if(comment.file_exist && comment.profile_image!=''){
							cmt_str += '<img src="'+S3_DISPLAY_URL+'upload/user/'+comment.profile_image+'" alt="img" class="img-circle" />';
						} else {
							cmt_str += '<img src="'+S3_DISPLAY_URL+'upload/user/no_image.jpg" alt="img" class="img-circle" />';
						}
						cmt_str += '</div><div class="userdetail" style="width: 90%;">';
						cmt_str += '<div class="usertxt">'+comment.first_name+' '+comment.last_name;
						cmt_str += '</div>';
						cmt_str += '<p class="usertxt2"> A '+comment.time_ago+'</p>';
						cmt_str += '<p id="orig_comment_'+comment.task_comment_id+'" class="wrap">'+comment.task_comment+'</p></div><div class="clearfix"> </div></li>';
					});

					$("#comments_html").html(cmt_str);
					$("#task_comment_list").val('');
					$("#task_comment_list_loading").hide();
			        $("#cmts_list_submit").removeAttr("disabled","disabled");
				}
			});
		}
	});

   

	

        $("#right_cmt").validate({
		rules : {
			"right_task_comment" : {
				required : true
			}
		},
		submitHandler:function(){
			$("#right_cmt_btn").attr("disabled","disabled");
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("kanban/add_comment");?>',
				data : $("#right_cmt").serialize(),
				success : function(data){
					$("#kanban_view").html(data);
					$("#comments_right").css('display','none');
					$("#comments_right").attr('class','modal hide fade');
					$("#comments_right").attr('aria-hidden','true');
					$("#right_cmt_btn").removeAttr("disabled","disabled");
					$(".modal-backdrop").remove();
					$("body").attr('class','page-header-fixed');
					$('#dvLoading').fadeOut('slow');

				}
			});
		}
	});



	if($('#check_project_id').val() != 0){
		getdepartment('<?php echo $division_id;?>','<?php echo $department_id;?>');

        }

 /*       $(".edit_title").click(function(){
               $("#pro_id").css("margin-bottom","px");
              
        });*/

	$('#project_title').editable({       
                                url: '<?php echo site_url('project/project_update');?>',
				params:{project_id : $('#check_project_id').val()},
				type: 'post',
				pk: 1,
				mode: 'inline',
				showbuttons: true,
				async:false,
				validate: function (value) {
					if ($.trim(value) == ''){ return 'This field is required';};
					var regex = /^[a-zA-Z0-9 &'-]*$/;
					if(!regex.test($.trim(value))){ return 'Please enter valid project title.'; };
					if (($.trim(value).length <= 3)){ return 'The title lenght can not be less than 3 characters';}
					if(($.trim(value).length >= 50)){return 'The title lenght can not be more than 50 characters';}
					},
				success : function(data){ 
                                            if(data =='0'){

                                            }else{
                                                        $(".main_project").val(data);
							$('#check_project_id').val(data);
                                                        $("#project_status").removeAttr('disabled');
                                                        $("#project_start_date").removeAttr('disabled');
                                                        $("#project_end_date").removeAttr('disabled');
                                                        $("#project_desc").removeAttr('disabled');
                                                        $("#division_id").removeAttr('disabled');
                                                        $("#department_id").removeAttr('disabled');
                                                        $("#project_customer_id").removeAttr('disabled');
                                                        $("#project_section").removeAttr('disabled');
                                                        $("#project_comment").removeAttr('disabled');
                                                        $("#select_task_status").removeAttr('disabled');
                                                        $("#select_task_assign").removeAttr('disabled');
                                                        
                                                        $.ajax({
                                                                type : 'post',
                                                                url : '<?php echo site_url("project/defaultMember");?>',
                                                                data : {project_id : data},
                                                                success : function(responseData){
                                                                        $("#memberlist").html(responseData);
                                                                        $('#dvLoading').fadeOut('slow');

                                                                }
                                                        });
                                                        
                                                        
                                                        $.ajax({
                                                                type : 'post',
                                                                url : '<?php echo site_url("project/get_default_section_id");?>',
                                                                data : {project_id : data},
                                                                success : function(section){ 
                                                                        $.ajax({
                                                                                type : 'post',
                                                                                url : '<?php echo site_url("project/defaultSection");?>',
                                                                                data : {project_id : data,section_id :section},
                                                                                success : function(responseData){
                                                                                        $("#task_result").html(responseData);
                                                                                        $('#dvLoading').fadeOut('slow');

                                                                                }
                                                                        });
                                                                }
                                                        });
                                                        
                                                        callhistory(data);
                                                        
                                                        
						}
						
					}
					
			});

  $("#save_general").click(function(){
	  $('#dvLoading').fadeIn('slow');
	  var serializedForm = $('#form_general').serializeArray();
	  serializedForm.push({name:'project_id', value:$('#check_project_id').val()});
	for(var i =0, len = serializedForm.length;i<len;i++){
	  serializedForm[i].value = $.trim(serializedForm[i].value);
	}
	$.ajax({
			type : 'post',
			url : '<?php echo site_url('project/project_update');?>',
			data : serializedForm,
			async:false,
			success : function(data){
				$('#dvLoading').fadeOut('slow');
				if(data =='0'){
					alertify.log('Saved Successfully!');
				}else{
					$('#check_project_id').val(data);
				}
				var check_project_id = $('#check_project_id').val();
			}
		});  
  });

        $(".project-select").change(function(){
			var id = $(this).attr('id');
			$("#"+id+"_loading").show();
			var name = $(this).attr('name');
			var value = $(this).val();
			if(value.trim()){
				if($('#check_project_id').val() != '0'){
					if(id =='project_end_date'){
						if($('#project_start_date') ==''){
                                                                $("#alertify").show();
                                                                alertify.alert("Start Date can not be empty.", function (e) {
								$("#"+id).focus(); $("#alertify").hide();$("#alertify-cover").css("position","relative");
								return false;
                                                                });
                                                            return false;
						}else{
							$.ajax({
								type : 'post',
								url : SIDE_URL+"project/is_date_greater",
								data : {value :value,project_start_date:$('#project_start_date').val()},
								async:false,
								success : function(data){
									if(data == "1"){
										$("#alertify").show();
							    		alertify.alert("Start date can not be greater than end date.", function (e) {
											$("#"+id).focus(); $("#alertify").hide();$("#alertify-cover").css("position","relative");
											return false;
										});
					    				return false;
									} /* else {
										$.ajax({
								    		type : 'post',
								    		url : '<?php echo site_url('project/project_update');?>',
											data : $('#form_general').serialize(),
								    		async:false,
								    		success : function(data){
								    			if(data =='0'){

												}else{
													$('#check_project_id').val(data);
												}
												var check_project_id = $('#check_project_id').val();
                                                                                                $("#"+id+"_loading").hide();
								    		}
								    	});

								    } */
								}
							});
						}
					}else if(id == 'project_desc'){
								if (value.trim() == ''){
										$("#alertify").show();
                                                                                alertify.alert("This field is required.", function (e) {
										$("#"+id).focus(); $("#alertify").hide();$("#alertify-cover").css("position","relative");
										return false;
									});
                                                                        return false;
                                                                }
								if (value.trim().length <= 3){
                                                                                $("#alertify").show();
                                                                                alertify.alert("The title lenght can not be less than 3 characters.", function (e) {
										$("#"+id).focus(); $("#alertify").hide();$("#alertify-cover").css("position","relative");
										return false;
                                                                                });
                                                                    return false;
                                                                }
								if(value.trim().length >= 40000){
                                                                                $("#alertify").show();
                                                                                alertify.alert("The title lenght can not be more than 40000 characters.", function (e) {
										$("#"+id).focus(); $("#alertify").hide();$("#alertify-cover").css("position","relative");
										return false;
									});
                                                                    return false;
                                                                }
                                                                if(value == 'Complete'){
                                                                        $("#old_"+id).val(value);
                                                                }/* else{
                                                                        $.ajax({
                                                                                type : 'post',
                                                                                url : '<?php echo site_url('project/project_update');?>',
                                                                                data : {name:name, value:value,project_id:$('#check_project_id').val()},
                                                                                async:false,
                                                                                success : function(data){
                                                                                        if(data =='0'){

                                                                                        }else{
                                                                                                $('#check_project_id').val(data);
                                                                                        }
                                                                                        var check_project_id = $('#check_project_id').val();
                                                                                        $("#"+id+"_loading").hide();
                                                                                        $("#old_"+id).val(value);
                                                                                }
                                                                        });

								} */
					}else{
						if(value == 'Complete'){
							$("#old_"+id).val(value);
						}/* else{
							$.ajax({
								type : 'post',
								url : '<?php echo site_url('project/project_update');?>',
								data : {name:name, value:value,project_id:$('#check_project_id').val()},
								async:false,
								success : function(data){
									if(data =='0'){

									}else{
										$('#check_project_id').val(data);
									}
									var check_project_id = $('#check_project_id').val();
                                                                        $("#"+id+"_loading").hide();
									$("#old_"+id).val(value);
								}
							});

						} */
					}
				}else{
                                                $("#alertify").show();
                                                alertify.alert("This field is required.", function (e) {
                                                    $("#"+id).focus();
                                                    $("#"+id).val($("#old_"+id).val());
                                                    $("#alertify").hide();$("#alertify-cover").css("position","relative");
                                                    return false;
                                                });
				}
				$('.date-picker').datepicker('hide');
			}else {
                                        $("#alertify").show();
                                        alertify.alert("This field is required.", function (e) {
                                                $("#"+id).focus();
                                                $("#"+id).val($("#old_"+id).val());
                                                $("#alertify").hide();$("#alertify-cover").css("position","relative");
                                                return false;
                                        });
                        }
						$("#"+id+"_loading").hide();
		});

    	$('#cmt121').validate({
			rules: {
                                "project_comment": {
                                        required: true,
                                        rangelength: [3, <?php echo CMT_TEXT_SIZE;?>]
                                     }
                        },
                        messages : {
                                  "project_comment" : {
                                          message : "This field is required."
                                  }
                        },
                        submitHandler:function(){
                            $('#dvLoading').fadeIn('slow');
                                            $.ajax({
                                        type: 'post',
                                        url : '<?php echo site_url("project/add_comment"); ?>',
                                        data: $('#cmt121').serialize(),
                                        success: function(responseData) {
                                            $('#cmt').modal('hide');

                                            $("#listcmt").html(responseData);
                                            $('#project_comment').val('');
                                            $("#ch,#ch1").val('');
                                            $('#dvLoading').fadeOut('slow');
                                            alertify.set('notifier','position', 'top-right');
                                            alertify.log("Comment added successfully");
                                        },
                                        error: function(responseData){
                                            console.log('Ajax request not recieved!');
                                            $('#dvLoading').fadeOut('slow');
                                        }
                                    });
                    }
    	});

        $('#complete_task_tab').validate({
        errorElement: 'span', 
        errorClass: 'help-inline', 
        focusInvalid: false, 
        ignore: "",
        rules: {
            task_status: {
                required: true
           },

           submitHandler: function (form1) {
	            success1.show();
	            error1.hide();
	            $("button[type=submit]").prop("disabled",true);
	            form1.submit();
	        }
        }
    });

        $('#complete_task_tab').click(function(){

    	if($('#task_status').val()!=''){
		$.ajax({
				type: 'POST',
				url : "<?php echo site_url('project/complete_task') ?>",
				data:{task_status:$("#task_status").val(),project_id:$('#check_project_id').val()},

				success : function(data) {

					if(data=='not done'){
						 alertify.alert("Tasks of the project are already completed");
						 $('#task_status').val('');
						 $('#complete_task').modal('hide');
					}else{
						alertify.alert("Your action performed successfully .");
						$('#task_status').val('');
						$('#complete_task').modal('hide');
					}
				},

			});
		}else{
			alertify.alert('Please Select action field')
		}

		});

        $('.status_close').click(function(){

		 	$("div.id_100 select").val("<?php echo $project_status;?>");
		 	$('#task_status').val('');
		 	$('#complete_task').modal('hide');
		 });
	});

	function removeUser(project_users_id,user_id,project_id)
	{
		var ans = "Are you sure, you want to delete  user?";
		alertify.confirm(ans,function(chk){

                    if(chk){

			$('#dvLoading').fadeIn('slow');
			$.ajax({
			url : "<?php echo site_url('project/deleteUser') ?>/"+project_users_id+"/"+user_id+"/"+project_id,
			cache: false,
			success: function(responseData) {
		            	if(responseData!='not done'){
			            	$("#memberlist").html(responseData);
			            	$('#dvLoading').fadeOut('slow');
                                        toastr['success']("User successfully deleted from project.", "");
		            	}else{
                                    toastr['error']("The user can not be deleted as some tasks are assiged to allocated user.", "");
		            		$('#dvLoading').fadeOut('slow');
		            	}
		            },
				});

			}else{
            	return false;
        	}
        });
	}

	function datapass(sub_id,s_id,s_name)
	{       
                s_name = decodeURIComponent(s_name);
		$("#general_project_id").val($('#check_project_id').val());
		$("#task_project_id").val($('#check_project_id').val());
		$("#dependent_project_id").val($('#check_project_id').val());
		$("#task_subsection_id").val(sub_id);
		$("#task_section_id").val(s_id);
		$("#master_task_id").val('0');
		$("#dep_subsection_id").val($("#task_subsection_id").val());
		$("#dep_section_id").val($("#task_section_id").val());
		$("#task_project_div").html('<div class="input-icon right"><i class="stripicon iconlink"></i><input readonly="readonly" class="m-wrap span11 valid" id="task_project_title" name="project_title" value="" type="text" placeholder="Link to Project" aria-invalid="false"></div><input type="hidden" id="task_project_id" name="task_project_id" value="<?php echo $project_id;?>" />');
		$("#section_div").html('<div class="input-icon right"><i class="stripicon iconlink"></i><input class="m-wrap span11 valid" name="task_section_name" id="task_section_name" readonly="readonly" value="" type="text" placeholder="Project section" aria-invalid="false"></div><input type="hidden" name="section_id" value="'+s_id+'" />');
		$("#task_project_title").val('<?php echo htmlspecialchars($project_title, ENT_QUOTES);?>');
		$("#task_section_name").val(s_name);

	}


function checkstatus(status)
{
	if(status=='Complete')
	{
		$('#complete_task').modal();
	}
}

function getdepartment(id,did)
{
	$.ajax({
		url:'<?php echo site_url('project/getDepartment') ?>/'+id+'/'+did,
		beforeSend:function(){ $('#department_id').html('<option >Loading....</option>');},
		success:function(res){
			$('#department_id').html(res);
                                                  $('#department_id').trigger("chosen:updated");
		},

	});

}

function removeCmt(cmt_id)
{
	var check_project_id = $('#check_project_id').val();
	var ans = "Are you sure, you want to delete Comment?";
	$('#removeCmt_'+cmt_id).confirmation('show').on('confirmed.bs.confirmation',function(){
                        $('#dvLoading').fadeIn('slow');
			$.ajax({
                                url : "<?php echo site_url('project/deleteComment') ?>/"+cmt_id+"/"+check_project_id,
                                cache: false,
                                success: function(responseData) {
                                        $("#listcmt").html(responseData);
                                        $('#dvLoading').fadeOut('slow');

                                },
                                error: function(responseData){
                                        console.log('Ajax request not recieved!');
                                        $('#dvLoading').fadeOut('slow');
                                }
			});
    	});

}

function callhistory(id)
{
	$('#dvLoading').fadeIn('slow');
	$.ajax({
			type: 'POST',
			url : "<?php echo site_url('project/history') ?>",
			data:{project_id:id},
			cache: false,
			async:false,
			success: function(responseData) {
                            $("#CommentsList").html(responseData);
                            $('#dvLoading').fadeOut('slow');

                        },
                        error: function(responseData){
                            console.log('Ajax request not recieved!');
                            $('#dvLoading').fadeOut('slow');
                        }
	});
}

</script>

<script type="text/javascript">
	$(document).ready(function(){

		$('.rplc').click(function(){
		var file_id = $('#rep_fil').val();

		$.validator.addMethod('filesize', function(value, element, param) {

			return this.optional(element) || (element.files[0].size <= param)
		});


		$('#frm_project_files_replace').validate({ 
					rules: {
						"project_file_replace": {
							  required: true,
							  filesize : 4194304 
						 }
				  },
				  messages : {
						  "project_file_replace" : {
							  filesize : "Please upload file size less than 4MB."
						  }
				  },
							errorPlacement: function (error, element) {
						  if (element.attr("name") == "project_file_replace") { 
							error.appendTo( element.parent("span").parent("div") );
						} else {
							error.insertAfter(element); 
						}
				  },
				  submitHandler: function() {
						var file = $("#project_file_replace").files[0].size;
						if(file > 4194304){
							alertify.alert("Please upload file size less than 4MB.");
							$("#change").css('display','block');
							return false;
						}

						$('#dvLoading').fadeIn('slow');
						  var data = new FormData($("#frm_project_files_replace")[0]);
						 $.ajax({

							type: 'post',
							url : '<?php echo site_url("project/files_replace"); ?>',
							data: data,
							processData: false,
							contentType: false,
							success: function(responseData) {
								$('#updated_project_files').html(responseData);
								$("#browse_r").css('display','block');
								$("#change_r").css('display','none');
								$("#icon_r").css('display','none');
								$(".fileupload-preview").html('');
								$("#frm_project_files").get(0).reset();
								$('#task_file-replace').modal('hide');
								$('#dvLoading').fadeOut('slow');
								alertify.set('notifier','position', 'top-right');
								// log will hide after 10 seconds
								alertify.log("File replaced successfully");
									  },
							error: function(responseData){
								console.log('Ajax request not recieved!');
								$('#dvLoading').fadeOut('slow');
							}
						});
					}
				});





	});

	});

	function setval(id)
	{
		$('#rep_fil').val(id);
		$('#rep_fil_link').val(id);
		replacebrowseClicked();

	}
</script>

<script type="text/javascript">
	$(document).ready(function(){


		var form3 = $('#users');

            var error1 = $('.alert-error', form3);
            var success1 = $('.alert-success', form3);

    	$('#users').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: true, 
			rules: {
                                "user_id": {
                                        required: true
                                }
                              },
                        messages : {
                                    "user_id" : {
                                            message : "This field is required."
                                    }
                          },
                          errorPlacement: function (error, element) {
				error.insertAfter(element); // for other inputs, just perform default behavior
                                $("#user_id-error").css("margin-left","230px");
                          },
                        submitHandler:function(){ 
                                 var is_owner = '';
                                if($("#another_project_owner").is(':checked')){
                                    is_owner = '1';   
                                }else{
                                    is_owner = '0';
                                }
                                
                              $('#dvLoading').fadeIn('slow');
                                              $.ajax({
                                                    type: 'post',
                                                    url : '<?php echo site_url("project/add_memeber"); ?>',
                                                    data: $('#users').serialize()+"&is_owner="+is_owner,
                                                    success: function(responseData) {
                                                        $("#memberlist").html(responseData);
                                                        $('#dvLoading').fadeOut('slow');
                                                        $('#user_id').val('');
                                                        $('#users_list').modal('hide');
                                                        toastr['success']("User successfully added to the project.", "");
                                                    },
                                                    error: function(responseData){
                                                        console.log('Ajax request not recieved!');
                                                        $('#dvLoading').fadeOut('slow');
                                                    }
                                             });
                        }
    	});

		$.validator.addMethod('filesize', function(value, element, param) {
		     return this.optional(element) || (element.files[0].size <= param)
		});

                $("#drag_area").dropzone({
                    url : '<?php echo site_url("project/project_files"); ?>',
                    dragover : function(a){
                        cur_time = Math.round(new Date().getTime());
                        $('#drag_message_project').show();
                    },
                    dragleave : function(a){
                        if((new Date().getTime() - cur_time) >= 4){
                        $('#drag_message_project').hide();
                        }
                    },
                    drop : function(a){
                        $('#drag_message_project').hide();
                    },
                    paramName :"project_file",
                    params : {
                        project_id : $("#files_project_id").val()
                    },
                    addedfile:function(b){
                        var file_name = b.name;
                        var fileExtension = file_name.substring(file_name.lastIndexOf('.') + 1); 
                        var html = '';
                            html += '<tr id="demo_data"><td width="8%" class="text-center">';
                            if(fileExtension == 'csv'){
                                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/csv.png" />';
                            } else if(fileExtension == 'pdf'){ 
                                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/pdf.png" />';
                            } else if(fileExtension == 'xls' || fileExtension == 'xlsx' || fileExtension == 'xl'){ 
                                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/excel.png" />';
                            } else if(fileExtension == 'doc' || fileExtension == 'docx' || fileExtension == 'word'){ 
                                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/icon2.png" />';
                            } else if(fileExtension == 'png' || fileExtension == 'jpe' || fileExtension == 'jpg' || fileExtension == 'jpeg' || fileExtension == 'gif' || fileExtension == 'bmp' || fileExtension == 'jpeg'){ 
                                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/images.jpg" />';
                            } else { 
                                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/document_icon.png" />';
                            } 
                            html +='</td>',
                            html +='<td>',
                            html +=file_name,
                            html +='</td>',
                            html +='<td width="15%">',
                            html +='<div class="progress" style="margin-bottom:0px !important;">',
                            html +='<div class="progress-bar progress-bar-success progress-bar bg-success progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30px;">',
                            html +='</div></div>';
                            html +='</td></tr>';
                            $("#prj-n-file").remove();
                            $('#updated_project_files').prepend(html);
                    },
                    sending:function(a,b,c){
                        $('#dvLoading').fadeIn('slow');
                    },
                    success: function(b,responseData) {
                      $('#updated_project_files').html(responseData);
                      $("#browse-prj").css('display','block');
                      $("#change-prj").css('display','none');
                      $("#icon-prj").css('display','none');
                      $(".fileupload-preview").html('');
                      $('#dvLoading').fadeOut('slow');
                                     
                           },
                        error: function(responseData){ 
                            console.log('Ajax request not recieved!');
                            $('#dvLoading').fadeOut('slow');

                        }
})

    	$("#project_file").change(function(){ 
    		var file = this.files[0].size;
    		if(file > 4194304){
    			alertify.alert("Please upload file size less than 4MB.");
    			$("#change").css('display','block');
    			return false;
    		}
    		//alert(file);
    		var data = new FormData($("#frm_project_files")[0]);
      		$('#dvLoading').fadeIn('slow');
                $.ajax({
                    type: 'post',
                    url : '<?php echo site_url("project/project_files"); ?>',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(responseData) {
                                $('#updated_project_files').html(responseData);
                                $("#browse-prj").css('display','block');
                                $("#change-prj").css('display','none');
                                $("#icon-prj").css('display','none');
                                $(".fileupload-preview").html('');
                                $('#dvLoading').fadeOut('slow');
                    },
                    error: function(responseData){
                        console.log('Ajax request not recieved!');
                        $('#dvLoading').fadeOut('slow');
                    }
                });
    	});

    	$("#prj_upload-link-btn").on("click",function(){
    		var name = $("#prj_file_name1").val();
    		var link = $("#prj_file_link").val();

    		if(link == ''){
    			alertify.alert("Please enter file link.");
    			return false;
    		}

    		if(name == ''){
    			alertify.alert("Please enter file name.");
    			return false;
    		}

    		if(link!='' && name!=''){
    			$('#dvLoading').fadeIn('slow');
	            $.ajax({
	                type: 'post',
	                url : '<?php echo site_url("project/uplaodLinkFiles"); ?>',
	                data: $("#prj_frm_upload_link").serialize(),
			async : false,
	                success: function(responseData) {
	                	$('#updated_project_files').html(responseData);
	                	$("#prj_file_name1").val("");
				$("#prj_file_link").val('');
                              
				$("#prj-n-file").hide();
	                	$('#dvLoading').fadeOut('slow');
                                
	                },
	                error: function(responseData){
	                    console.log('Ajax request not recieved!');
	                    $('#dvLoading').fadeOut('slow');
	                }
	            });
    		}

    	});

    	$("#project_file_replace").change(function(){ 
                            var file = this.files[0].size;
                            if(file > 4194304){
				alertify.alert("Please upload file size less than 4MB.");
				$("#change").css('display','block');
				return false;
                            }
				$('#dvLoading').fadeIn('slow');
			  	var data = new FormData($("#frm_project_files_replace")[0]);
				$.ajax({
                                        type: 'post',
                                        url : '<?php echo site_url("project/files_replace"); ?>',
                                        data: data,
                                        processData: false,
                                        contentType: false,
                                        success: function(responseData) {
                                                $('#updated_project_files').html(responseData);
                                                $("#browse_r").css('display','block');
                                                $("#change_r").css('display','none');
                                                $("#icon_r").css('display','none');
                                                $(".fileupload-preview").html('');
                                                $("#frm_project_files").get(0).reset();
                                                $('#task_file-replace').modal('hide');
                                                $('#dvLoading').fadeOut('slow');
                                                alertify.set('notifier','position', 'top-right');
                                                // log will hide after 10 seconds
                                                alertify.log("File replaced successfully");

                                        },

                                        error: function(responseData){
                                                console.log('Ajax request not recieved!');
                                                $('#dvLoading').fadeOut('slow');
                                        }
                                });

 		});

 	$("#replace-upload-link-btn").on("click",function(){
    		var name = $("#replace_file_name").val();
    		var link = $("#replace_file_link").val();

    		if(link == ''){
    			alertify.alert("Please enter file link.");
    			return false;
    		}

    		if(name == ''){
    			alertify.alert("Please enter file name.");
    			return false;
    		}

    		if(link!='' && name!=''){
    			$('#dvLoading').fadeIn('slow');
	            $.ajax({
	                type: 'post',
	                url : '<?php echo site_url("project/uplaodLinkFilesReplace"); ?>',
	                data: $("#frm_replace_upload_link").serialize(),
			        async : false,
	                success: function(responseData) {
	                	$('#updated_project_files').html(responseData);
	                	$("#replace_file_name").val('');
						$("#replace_file_link").val('');
						$("#prj-n-file").hide();
						$('#task_file-replace').modal('hide');
	                	$('#dvLoading').fadeOut('slow');
	                	alertify.set('notifier','position', 'top-right');
						// log will hide after 10 seconds
						alertify.log("File replaced successfully");


	                },
	                error: function(responseData){
	                    console.log('Ajax request not recieved!');
	                    $('#dvLoading').fadeOut('slow');
	                }
	            });
    		}

    	});


	});

	function listmem(id)
	{ 
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("project/memberlist"); ?>',
			data : {project_id : id},
			success: function(responseData) {
	                	$('#replacemem').html(responseData);
                                $("#users_list").modal('show');

	                },
	                error: function(responseData){
	                    console.log('Ajax request not recieved!');
	                }
		});
	}



	function memberList(id)
	{
		$.ajax({
			type : 'post',
			url : '<?php echo site_url("project/memberView"); ?>',
			data : {project_id : $('#check_project_id').val()},
			success: function(responseData) {
            	$('#tab_3').html(responseData);
            },
            error: function(responseData){
                console.log('Ajax request not recieved!');
            }
		});
	}

	function delete_project_file(id){

		var ans = "Are you sure, you want to delete  File?";
		$('#project_file_'+id).confirmation('show').on('confirmed.bs.confirmation',function(){
	       	$('#dvLoading').fadeIn('slow');
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("project/delete_project_file"); ?>',
				data : {task_file_id : id, project_id : $("#files_project_id").val(),tab:$("#tab").val()},
				success: function(responseData) {
		                	$('#updated_project_files').html(responseData);
					$('#dvLoading').fadeOut('slow');
					alertify.set('notifier','position', 'top-right');
					alertify.log("File deleted successfully");

		                },
		                error: function(responseData){
		                    console.log('Ajax request not recieved!');
		                    $('#dvLoading').fadeOut('slow');
		                }
			});
                });
	}


</script>

<script type="text/javascript">



	function loadMore()
	{
		var total_data=parseInt('<?php echo @$total_history ?>');
		var offset=parseInt($('#offset').val());

		$('#hid').remove();
		$('#dvLoading').fadeIn('slow');
		if(total_data>offset)
		{
			$.ajax({
				url:'<?php echo site_url('project/history') ?>',
				type:'POST',
				data:{offset:offset,project_id:$('#check_project_id').val(),tab:$("#tab").val()},
				success:function(data)
				{
					if(data){
						$('#append_data').append(data);
						$('#dvLoading').fadeOut('slow');
					}else{
						$('#moreDiv').html('<input type="button"  alt="view-btn" class="btn red txtbold br-radius margin-bottom-20" value="No More">');
						$('#dvLoading').fadeOut('slow');
					}
				}

			});
		}else{
			$('#moreDiv').html('<input type="button" alt="view-btn" class="btn red txtbold br-radius margin-bottom-20" value="No More">');
			$('#dvLoading').fadeOut('slow');
		}
	}

	function changeSecName(id)
	{
                                    $('#panel-body_'+id).toggle();
                                       $('#ch_sec_'+id).find('.expand_sections i').toggleClass("icon-chevron-right  icon-chevron-down");
		$('#chngNm_'+id).modal();
		$('#sec_name').on('shown.bs.modal', function () {
    		$('#section_name_'+id).focus();
                                   
		});
                 
	}

	function changeSubSecName(id)
	{
                                 
            $("#ch_subsec_"+id).siblings(".panel-body").toggle();
            $('#ch_subsec_'+id).find('.expand_sections i').toggleClass("icon-chevron-right  icon-chevron-down");
            $('#chngSubNm_'+id).modal();
            $('#subsec_name').on('shown.bs.modal', function () {
            $('#subsection_name_'+id).focus();
            });

	}


	function resultSection(id,project_id)
	{
               /* var id = $("#select_task").val();

		if(id!=''){
			$('#dvLoading').fadeIn('slow');
			$.ajax({
	            type: 'post',
	            url : '<?php echo site_url("project/task_result"); ?>',
	           	data:{user_id:id,project_id:project_id,user_id:id,type:filter},
	            success: function(responseData) {
	            	$("#task_result").html(responseData);
	            	$('#dvLoading').fadeOut('slow');

	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	                	//$('#sec_name').modal();
	                $('#dvLoading').fadeOut('slow');
	            }
	        });

			

			}else{
				alertify.alert("Please select option");
			}*/
	}



	function createSubSection(section_id,section_name,project_id)
	{               
                    var sec_name = $("#project_subsection_"+section_id).val(); 
                    if(sec_name!=''){

                                $('#dvLoading').fadeIn('slow');
                                $.ajax({
                                        url : "<?php echo site_url('project/createSubSection');?>",
                                        data:{section_id:section_id,project_id:$('#check_project_id').val(),section_name:sec_name},
                                        type:'POST',
                                        success: function(responseData) {
                                                    if(responseData !='fail'){
                                                        $("#task_result").html(responseData);
                                                        $('.sechide1').modal('hide');
                                                        $('#set_subsection_name').val('');
                                                        $('#dvLoading').fadeOut('slow');
                                                        return false;
                                                    }else{
                                                        $('#set_subsection_name').val('');
                                                        $('#dvLoading').fadeOut('slow');
                                                        alertify.alert('Twice sub section name already exist.');
                                                        return false;
                                                    }
                                            },
                                            error: function(responseData){
                                                console.log('Ajax request not recieved!');
                                                $('#dvLoading').fadeOut('slow');
                                                return false;
                                            }

                                                });
                            }else{
                                        alertify.alert('Sub Section name can not be empty.')
                                }
	}


	function delete_subsection(sub_id,taskcount)
	{
                                  $("#ch_subsec_"+sub_id).siblings(".panel-body").toggle();
		var check_project_id = $('#check_project_id').val();
        if(taskcount){
            var ans = "this action would delete all tasks present in this section";
        }else{
            var ans = "Are you sure, you want to delete  subsection?";
        }
		$('#delete_subsection_'+sub_id).confirmation({placement:'bottom'});
		$('#delete_subsection_'+sub_id).confirmation('show').on('confirmed.bs.confirmation',function(){

			$('#dvLoading').fadeIn('slow');
			$.ajax({
			url : "<?php echo site_url('project/deleteSubSection') ?>/"+sub_id+"/"+$('#check_project_id').val(),
			cache: false,
			success: function(responseData) {

						$("#Subtab_"+sub_id).hide("slow");
						$("#set_subsection_name").val('');
		            	$('#dvLoading').fadeOut('slow');

		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});
                });
	}

	function delete_section(s_id,taskcount)
	{
                                $('#panel-body_'+s_id).toggle();
                                 $('#ch_sec_'+s_id).find('.expand_sections i').toggleClass("icon-chevron-right  icon-chevron-down");
                                
		var check_project_id = $('#check_project_id').val();
        if(taskcount){
            var ans = "this action would delete all tasks present in this section";
        }else{
          var ans = "Are you sure, you want to delete  Section?";  
        }
        console.log('#delete_section_'+s_id);
		
		$('#delete_section_'+s_id).confirmation('show').on('confirmed.bs.confirmation',function(){

			$('#dvLoading').fadeIn('slow');
			$.ajax({
			url : "<?php echo site_url('project/deleteSection') ?>/"+s_id+"/"+$('#check_project_id').val(),
			cache: false,
			success: function(responseData) {
						$("#Stab_"+s_id).hide("slow");
		            	$('#dvLoading').fadeOut('slow');
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
				});
    	});
	}

	function createSection(project_id)
	{

		$('.sechide').modal("show");
                $("#set_section_name").focus();
		$('.sechide').on('shown.bs.modal', function () {
    		$('#set_section_name').focus();
                });


	}

</script>

<script type="text/javascript">
	$(document).ready(function(){


	$("#select_task_status").on("change",function(){
		var id = this.value;
                $('#dvLoading').fadeIn('slow');
			$.ajax({
                                type: 'post',
                                url : '<?php echo site_url("project/task_result"); ?>',
                                data:{project_id:$('#check_project_id').val(),type:id,user_id:$("#select_task_assign").val()},
                                success: function(responseData) {
                                    $("#task_result").html(responseData);
                                    $('.sechide').modal('hide');
                                    $('#set_section_name').val('');
                                    $('#dvLoading').fadeOut('slow');

                                },
                                error: function(responseData){
                                    console.log('Ajax request not recieved!');
                                    $('#dvLoading').fadeOut('slow');
                                }
                        });
        });

        $("#select_task_assign").on("change",function(){
                var id = this.value;
                $('#dvLoading').fadeIn('slow');
			$.ajax({
                                type: 'post',
                                url : '<?php echo site_url("project/task_result"); ?>',
                                data:{project_id:$('#check_project_id').val(),type:$("#select_task_status").val(),user_id:id},
                                success: function(responseData) {
                                    $("#task_result").html(responseData);
                                    $('.sechide').modal('hide');
                                    $('#set_section_name').val('');
                                    $('#dvLoading').fadeOut('slow');

                                },
                                error: function(responseData){
                                    console.log('Ajax request not recieved!');
                                    $('#dvLoading').fadeOut('slow');
                                }
                        });

        });
	$(document).on("click","#name_section",function(){ 
				var sec_name = $("#project_section").val();
				var id = $( "#select_task option:selected").val();
				

				if(sec_name!=''){
					$('#dvLoading').fadeIn('slow');
					$.ajax({
						url : "<?php echo site_url('project/createSection');?>",
						data:{project_id:$('#check_project_id').val(),section_name:$("#project_section").val()},
						type:'POST',
						success: function(responseData) {

							if(responseData !='fail'){
                                                            $("#task_result").html(responseData);
                                                            $('.sechide').modal('hide');
                                                            $('#set_section_name').val('');
                                                            $('#dvLoading').fadeOut('slow');
                                                            return false;
                                                        }else{
                                                            $('#set_section_name').val('');
                                                            $('#dvLoading').fadeOut('slow');
                                                            alertify.alert('Twice section name already exist.');
                                                            return false;
                                                        }

                                                },
                                                error: function(responseData){
                                                    console.log('Ajax request not recieved!');
                                                    $('#dvLoading').fadeOut('slow');
                                                }
                                            });
                                }else{
					alertify.alert('Section name can not be empty.')
				}

		});

        

	});

	
</script>



<script src='<?php echo $theme_url; ?>/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?Ver=<?php echo VERSION;?>'></script>
<script type="text/javascript">
	var tempFlag = 0;
	$(document).ready(function(){
//calltasklist();
     var tempFlag = 0;
	//	var flag = 0;
		$(".panel-body,.panel-body1").sortable({
		items: '> :not(.unsorttd)',
		 revert: true,
                forcePlaceholderSize: true,
        connectWith: '.panel-body,.panel-body1',
        scroll: true,
   		placeholder: "drag-place-holder",
   	   scrollSpeed: 10,
    	tolerance: "pointer",
       dropOnEmpty: true,
      helper: function (event, element) {
            return $(element).clone().addClass('dragging');
        },
        start: function (e, ui) {

        },

         update : function (e, ui) {

         	var status = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');
        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
                var status = $(this).attr('class');
        	if(ui.sender){
                }
        	var status = $(this).attr('id');
        	var main_cls = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');
      		var sub_task = ui.item[0].parentElement.parentElement.id;
        	var sub_task1 = sub_task.split("_");
        	var sub_task2 = sub_task1[0];

        	if(ui.item[0].nextElementSibling === null){
	      		tempFlag = 1;
	      		$(ui.sender).sortable('cancel');
	      		alertify.alert("Sorry, you can not place task outside to section scope.");
	      		return false;
			}else{
				var sub_task3 = ui.item[0].nextElementSibling.id;
			}
        	var sub_task4 = sub_task3.split("_");
        	var sub_task5 = sub_task4[0]+"_"+sub_task4[1];

        	var sub_task6 = ui.item[0].parentElement.attributes[0].ownerElement.id;

        	var sub_task7 = ui.item[0].parentElement.id;
        	var sub_task8 = sub_task7.split("_");
        	var sub_task9 = sub_task8[0];

        	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];


        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];

	      	var URL = '<?php echo site_url('project/setOrder') ?>';
	      	var id = 'all';
	      	var project_id = $('#check_project_id').val();
	      	if((sub_task2 == 'Subtab' && myvar == 'Subtab')){
	      		tempFlag = 1;
	      		$(ui.sender).sortable('cancel');
	      		alertify.alert("Sorry, you can not place sub section in another sub section.");
	      		return false;
			}
			else if((mysec != sub_task6) && (((sub_task9 !='panel-body' && sub_task9 !='') && sub_task5 == 'task_tasksort' && myvar == 'Subtab')) || (((sub_task9 !='panel-body' && sub_task9 !='') && sub_task5 == '_undefined' && myvar == 'Subtab'))){

				if(sub_task9!='panel-body1' && sub_task5 == '_undefined'){

					tempFlag = 1;
					$(this).sortable('cancel');
					alertify.alert("Sorry, you can not place sub section between section tasks.");
					return false;
				}else{

				tempFlag = 0;

				$.ajax({
					url:URL,
					type:'POST',
					data:{'order':order,'status':status,'scope_id':scope_id,'task_data' : orig_data,'project_id':$('#check_project_id').val()},
					 processData: true,
					success : function(data) {
						resultSection(id,project_id);
					},
				})

			}

			}else{

				tempFlag = 0;
				$.ajax({
					url:URL,
					type:'POST',
					data:{'order':order,'status':status,'scope_id':scope_id,'task_data' : orig_data,'project_id':$('#check_project_id').val()},
					 processData: true,
					success : function(data) {
					},
				})
			}
	    },
	    stop: function (e, ui) {
            var status = $(this).attr('id');
            /*console.log(ui);
            alert("stop"+tempFlag);
            alert("New position: " + ui.item());*/
        },

        receive: function( e, ui ) {
        	/*alert("inside recever -1"+$("#tempflag").val());
        	alert("RAM"+tempFlag);*/
        	if(tempFlag != '1'){
        	var status = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');
        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
        	var URL = '<?php echo site_url('project/UpdateScope') ?>';
        	var mysec = this.id;
        	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];

        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];

        	var id = 'all';
	      	var project_id = $('#check_project_id').val();
			$.ajax({
				url:URL,
				type:'POST',
				 processData: true,
				data:{'scope_id':scope_id, 'status':status,'order':order,'task_data' : orig_data,'project_id': $('#check_project_id').val()},
				success : function(data) {
				},
			})

			}else{
			      tempFlag = "";
			}
        },

        cursor: 'move',

	}).disableSelection();

	$(".panel-body,.panel-body1").find("input,select").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
  e.stopImmediatePropagation();
});


	tempFlag = 0;
	$(".srtab").sortable({


		items: '> :not(.unsorttd)',
		 revert: true,
        forcePlaceholderSize: true,
        connectWith: '.task_section',
        scroll: true,
   		placeholder: "drag-place-holder",
   	   scrollSpeed: 10,
    	tolerance: "pointer",
       dropOnEmpty: true,

      helper: function (event, element) {
            return $(element).clone().addClass('dragging');
        },
        start: function (e, ui) {

        },
         update : function (e, ui) {
           var status = $(this).attr('class');
        	if(ui.sender){
                }
        	var status = $(this).attr('id');
        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
        	var order = $('#'+status).sortable('serialize');
        	var sub_task = ui.item[0].parentElement.parentElement.id
        	var sub_task1 = sub_task.split("_");
        	var sub_task2 = sub_task1[0];
	    	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];
        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];
	      	var URL = '<?php echo site_url('project/setOrder') ?>';

	      	var id = 'all';
	      	var project_id = $('#check_project_id').val();

	      	if((sub_task2 == 'Subtab' && myvar == 'Subtab')){
	      		tempFlag = 1;
	      		$(ui.sender).sortable('cancel');
	      		alertify.alert("Sorry, you can not place sub section in another sub section.");
	      		return false;

			}else if((sub_task2 == 'Stab' && myvar == 'Subtab')){
				tempFlag = 1;
	      		$(this).sortable('cancel');
	      		alertify.alert("Sorry, you can not place sub section between section tasks.");
	      		return false;

			}else{

				tempFlag =0;

				$.ajax({
				url:URL,
				type:'POST',
				data:{'order':order,'status':status,'task_data':orig_data,'project_id':$('#check_project_id').val(),'scope_id':scope_id},
				 processData: true,
				success : function(data) {
				},
			})
			}
	    },
	    stop: function (e, ui) {


            var status = $(this).attr('id');
        },

        receive: function( e, ui ) {
        	if(tempFlag !='1'){
        	var status = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');
        	status = status.replace('scope_', '');
        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
        	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];
        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];
        	var URL = '<?php echo site_url('project/UpdateScope') ?>';

        	var id = 'all';
	      	var project_id = $('#check_project_id').val();
			$.ajax({
				url:URL,
				type:'POST',
				 processData: true,
				data:{'scope_id':scope_id, 'status':status,'order':order,'task_data' : orig_data,'project_id':$('#check_project_id').val()},
				success : function(data) {
				},
			})

		}else{
				tempFlag = 0;
			}
        },

        cursor: 'move',

	}).disableSelection();

	$(".tab-pane").find("input,select").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
  e.stopImmediatePropagation();
}); 
	});





	function calltasklist()
	{
		var tempFlag = 0;
		$(".panel-body,.panel-body1").sortable({
		items: '> :not(.unsorttd)',
		 revert: true,
        forcePlaceholderSize: true,
        connectWith: '.panel-body,.panel-body1',
        scroll: true,
   		placeholder: "drag-place-holder",
   	   scrollSpeed: 10,
    	tolerance: "pointer",
       dropOnEmpty: true,
      /*forcePlaceholderSize: true,
       disable: false ,
      handle: '.gray',
     handle: '.tasksort',*/

      helper: function (event, element) {
            return $(element).clone().addClass('dragging');
        },
        start: function (e, ui) {

        },

         update : function (e, ui) {

         	var status = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');
        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
           var status = $(this).attr('class');
        	if(ui.sender){
                }
        	var status = $(this).attr('id');
        	var main_cls = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');
      		var sub_task = ui.item[0].parentElement.parentElement.id;
        	var sub_task1 = sub_task.split("_");
        	var sub_task2 = sub_task1[0];
        	if(ui.item[0].nextElementSibling === null){
	      		tempFlag = 1;
	      		$(ui.sender).sortable('cancel');
	      		alertify.alert("Sorry, you can not place task outside to section scope.");
	      		return false;
			}else{
				var sub_task3 = ui.item[0].nextElementSibling.id;
			}
        	var sub_task4 = sub_task3.split("_");
        	var sub_task5 = sub_task4[0]+"_"+sub_task4[1];

        	var sub_task6 = ui.item[0].parentElement.attributes[0].ownerElement.id;

        	var sub_task7 = ui.item[0].parentElement.id;
        	var sub_task8 = sub_task7.split("_");
        	var sub_task9 = sub_task8[0];

        	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];


        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];

	      	var URL = '<?php echo site_url('project/setOrder') ?>';
	      	var id = 'all';
	      	var project_id = $('#check_project_id').val();

	      	if((sub_task2 == 'Subtab' && myvar == 'Subtab')){
	      		tempFlag = 1;
	      		$(ui.sender).sortable('cancel');
	      		alertify.alert("Sorry, you can not place sub section in another sub section.");
	      		return false;
			}
			else if((mysec != sub_task6) && (((sub_task9 !='panel-body' && sub_task9 !='') && sub_task5 == 'task_tasksort' && myvar == 'Subtab')) || (((sub_task9 !='panel-body' && sub_task9 !='') && sub_task5 == '_undefined' && myvar == 'Subtab'))){

				if(sub_task9!='panel-body1' && sub_task5 == '_undefined'){

					tempFlag = 1;
					$(this).sortable('cancel');
					alertify.alert("Sorry, you can not place sub section between section tasks.");
					return false;
				}else{

				tempFlag = 0;

				$.ajax({
					url:URL,
					type:'POST',
					data:{'order':order,'status':status,'scope_id':scope_id,'task_data':orig_data,'project_id':$('#check_project_id').val()},
					 processData: true,
					success : function(data) {

						resultSection(id,project_id);

					},
				})

			}

			}else{

				tempFlag = 0;
				$.ajax({
					url:URL,
					type:'POST',
					data:{'order':order,'status':status,'scope_id':scope_id,'task_data':orig_data,'project_id':$('#check_project_id').val()},
					 processData: true,
					success : function(data) {
					},
				})
			}
	    },
	    stop: function (e, ui) {

            var status = $(this).attr('id');
        },

        receive: function( e, ui ) {
        	if(tempFlag != '1'){
        	var status = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');
        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
        	var URL = '<?php echo site_url('project/UpdateScope') ?>';
        	var mysec = this.id;
        	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];

        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];

			var id = 'all';
	      	var project_id = $('#check_project_id').val();
			$.ajax({
				url:URL,
				type:'POST',
				 processData: true,
				data:{'scope_id':scope_id, 'status':status,'order':order,'task_data' : orig_data,'project_id':$('#check_project_id').val()},
				success : function(data) {
				},
			})

			}else{
			      tempFlag = "";
			}
        },

        cursor: 'move',

	}).disableSelection();

	$(".panel-body,.panel-body1").find("input,select").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                e.stopImmediatePropagation();
              });


	tempFlag = 0;
	$(".srtab").sortable({


		items: '> :not(.unsorttd)',
		revert: true,
                forcePlaceholderSize: true,
                connectWith: '.task_section',
                scroll: true,
   		placeholder: "drag-place-holder",
                scrollSpeed: 10,
                tolerance: "pointer",
                dropOnEmpty: true,

      helper: function (event, element) {
            return $(element).clone().addClass('dragging');
        },
        start: function (e, ui) {

        },
         update : function (e, ui) {
           var status = $(this).attr('class');
        	if(ui.sender){
                }
        	var status = $(this).attr('id');
        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
        	var order = $('#'+status).sortable('serialize');
        	var sub_task = ui.item[0].parentElement.parentElement.id
        	var sub_task1 = sub_task.split("_");
        	var sub_task2 = sub_task1[0];
	    	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];
        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];

	      	var URL = '<?php echo site_url('project/setOrder') ?>';

	      	var id = 'all';
	      	var project_id = $('#check_project_id').val();

	      	if((sub_task2 == 'Subtab' && myvar == 'Subtab')){
	      		tempFlag = 1;
	      		$(ui.sender).sortable('cancel');
	      		alertify.alert("Sorry, you can not place sub section in another sub section.");
	      		return false;

			}else if((sub_task2 == 'Stab' && myvar == 'Subtab')){
				tempFlag = 1;
	      		$(this).sortable('cancel');
	      		alertify.alert("Sorry, you can not place sub section between section tasks.");
	      		return false;

			}else{

				tempFlag =0;

				$.ajax({
				url:URL,
				type:'POST',
				data:{'order':order,'status':status,'task_data':orig_data,'project_id':$('#check_project_id').val(),'scope_id':scope_id},
				 processData: true,
				success : function(data) {
				},
			})
			}
	    },
	    stop: function (e, ui) {


            var status = $(this).attr('id');
        },

        receive: function( e, ui ) {
        	if(tempFlag !='1'){

        	var status = $(this).attr('id');
        	var order = $('#'+status).sortable('serialize');


        	status = status.replace('scope_', '');

        	var scope_id = ui.item.show().attr('id');
        	var orig_data = $('#task_data_'+scope_id).val();
        	var mysec = this.id;
        	var secres = mysec.split("_");
        	var secres1 = secres[0];
        	var mystr = ui.item[0].id;
        	var myarr = mystr.split("_");
			var myvar = myarr[0];
        	var URL = '<?php echo site_url('project/UpdateScope') ?>';

        	var id = 'all';
	      	var project_id = $('#check_project_id').val();
			$.ajax({
				url:URL,
				type:'POST',
				 processData: true,
				data:{'scope_id':scope_id, 'status':status,'order':order,'task_data' : orig_data,'project_id':<?php echo ($project_id!='')?$project_id:'0';?>},
				success : function(data) {
				},
			})

		}else{
				tempFlag = 0;
			}
        },

        cursor: 'move',

	}).disableSelection();

	$(".tab-pane").find("input,select").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
        e.stopImmediatePropagation();
      });
	}

</script>
<script src="<?php echo base_url().getThemeName();?>/assets/js/timer.jquery.js?Ver=<?php echo VERSION;?>"></script>

<input type="hidden" name="check_project_id" id="check_project_id" value="<?php echo $project_id;?>"/>
<div class="container-fluid rmBg page-background" style="overflow-x: hidden">
    <div class="mainpage-container edit_prj">
        <div class="row" style="padding-left: 10px; margin-right: -2.011%; ">
	 <div class="col-md-12 snglrw"  >
            <a href="listProject"  class="pull-left margin5" style="padding-bottom:  0px; "> Return to Project List</a>
	 </div>
        </div>
                        <!--Project module header-->
                    <div class="user-block edit_prj_block">
                        <!-- Page Title Row -->
                            <div class="row" style="margin-left: 11px !important; margin-right: -6px">
							
                                            <!-- Project name-->
                                            <div class="col-md-5" style="padding-left: 0px;padding-right: 0px;">
                                                <div class="blue-title project_title_header">
                                                    <div class="row" style="height: 30px;">
                                                        <div class="form-group" >
                                                              
                                                            <?php if($project_title==''){?>
    <!--                                                                    <label class=" col-md-3 control-label" style="padding-left: 0px; padding-top: 5px; color:#FFFFFF"><b>Project Name</b> <span class="required">*</span></label>-->
                                                                        <div class=" col-md-12 controls relative-position" id="project_id_1" style=" margin-bottom: 2px !important;">
                                                                            <input type="text"  class="txt-style radius-b " id="project_title1" name="project_title1"  autofocus value="" />
                                                                            <button type="button" id="save_title" name="save_title" class="btn blue txtbold title_input" style="line-height: 20px !important;"> <i class="icon-ok"></i> </button>
                                                                            <button type="button" id="cancel_title" name="cancel_title" class="btn  txtbold title_input" style="line-height: 20px !important;"> <i class="icon-remove"></i> </button>
                                                                        <span class="input-load setting-select-load" id="project_title_loading"></span>
                                                                        <input type="hidden" id="old_project_title" name="old_project_title" value="<?php echo $project_title;?>" />
                                                                        </div>
                                                            <?php }else{ ?>
    <!--                                                                    <label class=" col-md-3 control-label" style="padding-left: 0px; padding-top: 5px; color:#FFFFFF"><b>Project Name</b> <span class="required">*</span></label>-->
                                                                        <div class=" col-md-12 controls relative-position" id="pro_id">
                                                                            <a href="javascript:void(0)" class="txt-style edit_title default_color" id="project_title" name="project_title" data-type="text" data-pk="1" data-original-title="<?php echo $project_title;?>" ><?php echo $project_title;?></a>
                                                                        <span class=" input-load setting-select-load" id="project_title_loading"></span>
                                                                        <input type="hidden" id="old_project_title" name="old_project_title" value="<?php echo $project_title;?>" />
                                                                        </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--Filter Task status -->
                                            <div class="col-md-3" style="padding-left: 0px;padding-right: 0px;">
                                                    <div class="blue-title project_header_option" >
                                                            <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="col-md-5 control-label default_color" style="padding-left:75px; padding-top: 5px; "><b>Status</b></label>

                                                                            <div class="col-md-7 ">
                                                                                <select id="select_task_status" name="select_task_"  class="form-control radius-b" tabindex="1"  <?php if($project_id == '0'){echo 'disabled="disabled"';}?> >
                                                                                            <option value="opt">Open Tasks</option>
                                                                                            <option value="ut">Upcoming Tasks</option>
                                                                                            <option value="tt">Today's Tasks</option>
                                                                                            <option value="ot">Overdue Tasks</option>
                                                                                            <option value="all">All Tasks</option>
                                                                                </select>
                                                                            </div>

                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            <!-- Filter Assigned To -->
                                            <div class="col-md-4" style="padding-left: 0px; ">
                                                    <div class="blue-title project_header_option" >
                                                            <div class="row">
                                                                   <?php 	$users =get_company_users_project($project_id); ?>
                                                                    <!--<div class="form-group">-->
                                                                    <label class="col-md-4 control-label default_color" style="padding-left: 26px; padding-right: 0px; padding-top: 5px; "><b>Assigned To</b></label>

                                                                    <div class="col-md-4" style="padding-left: 0px;margin-left: 0px;" >

                                                                            <select id="select_task_assign" name="select_task"  class="form-control radius-b" tabindex="1" <?php if($project_id == '0'){echo 'disabled="disabled"';}?> >
                                                                                    <option value="all">Any User</option>
                                                                                    <option value="<?php echo get_authenticateUserID();?>">Me</option>
                                                                                    <?php if($users){
                                                                                            foreach($users as $u){
                                                                                    ?>
                                                                                    <option value="<?php echo $u->user_id;?>" <?php if($u->user_id == get_authenticateUserID()){ echo 'selected="selected"';}?> ><?php echo $u->first_name.' '.$u->last_name;?></option>
                                                                                            <?php
                                                                                                    }
                                                                                            }
                                                                                            ?>
                                                                            </select>
                                                                    </div>
                                                                    <div class="col-md-4" style="padding-top: 8px;margin-left: -1px;padding-left: 30px;">
                                                                        <a href="javascript:void(0);" data-placement="top" data-original-title="Timer" class="tooltips" onClick="showhide()" style="color: #fff;border: 1px white solid;border-radius: 5px;padding: 4px 5px 2px 6px;"> <i class="icon-time"  style="font-size:18px;"> </i> <span style="margin-left:0px;font-weight: bold;font-size: 14px;">Timer</span></a>
                                                                    </div>

                                                            </div>
                                                    </div>
                                            </div>
                            </div>

                        

			<!-- Main Page -->
			<div class="row"  style="margin-right: 0px;">
				<!-- 70% column  -->
				<div class="col-md-8" style="padding-top: 10px;">
                                        <div class="portlet-body form">
                                            
                                        </div> 
                                        <div class="tab-pane  active" id="tab_2">
                                        <!--Section & subsection-->
						 <div class="">
                                                    <div class="portlet-body form ">
                                                        <div class="form-horizontal">
							   
							 	<div class="">
							 		<div class="usertabs">
										<div  class="tabbable tabbable-custom">
                                                                                        <div class="clearfix"></div>
                                                                                        <div class=" accrodian" id="task_result" style="padding-left: 16px;">

												 <div class="tab-pane srtab active" id="panel-body_srtab">
												 	<?php
                                                                                                                                                                                                   
												 	 if($section!=''){
														foreach($section as $s){

															$tasktot = total_task($s->section_id);
													 ?>
													<div class="sortable border margin-bottom-15 full-width tasks_tab" id="Stab_<?php echo $s->section_id;?>">
														<div id="ch_sec_<?php echo $s->section_id;?>"  class="panel-heading pointer">
                                                                                                                                                                                                                                     <a <?php if($is_owner!='0'){ ?> class="changesecname"  sec_type="section" sec_prj_id="<?php echo $s->project_id;?>" data-pk="<?php echo $s->section_id;?>" <?php }else{?> <?php } ?> ><?php echo $s->section_name;?></a>
                                                                                                                                                                                                                                    <span class="expand_sections" style="float:left"><i class="icon-chevron-down default_color"></i></span>
                                                                                                                                                                                                        <a onclick="delete_section('<?php echo $s->section_id;?>');" href="javascript:void(0)" ><i class="icon-trash prjicn" id="delete_section_<?php echo $s->section_id;?>"></i></a>
                                                                                                                                                                                                                   </div>
														<div class="panel-body" id="panel-body_<?php echo $s->section_id;?>">
															<?php
															 if($subSection[$s->section_id]!='0'){
																foreach ($subSection[$s->section_id] as $sub) {
																if($sub!='0'){
																$tasktot1 = total_sub_task($sub->section_id);

															?>

                                                                                                                    <div id="Subtab_<?php echo $sub->section_id;?>" class="border margin-bottom-15 ">
																<div id="ch_subsec_<?php echo $sub->section_id;?>" class="panel-heading gray pointer">
																<a <?php if($is_owner!='0' ){ ?>  class="changesecname"  sec_type="subsection" sec_prj_id="<?php echo $sub->project_id;?>" data-pk="<?php echo $sub->section_id;?>"  <?php }else{?> <?php } ?> ><?php echo $sub->section_name;?></a>
                                                                                                                                                                                                                                                                <span class="expand_sections" style="float:left"><i class="icon-chevron-down default_color"></i></span>
                                                                                                                                                                                                                                                              <?php if($is_owner!='0'){ ?>
																	<a onclick="delete_subsection('<?php echo $sub->section_id;?>');" href="javascript:void(0)" ><i class="icon-trash prjicn" id="delete_subsection_<?php echo $sub->section_id;?>"></i></a>
                                                                                                                                                                                                                                                              <?php } ?>
																</div>

																<div id="taskmove_<?php echo $sub->section_id;?>_<?php echo $sub->main_section;?>" class="panel-body panel-body_<?php echo $s->section_id;?>">
                                                                                                                                <div id="chngSubNm_<?php echo $sub->section_id;?>" class="modal model-size pro-change fade" tabindex="-1" >
                                                                                                                                    <div class="portlet">
                                                                                                                                            <div class="portlet-body  form flip-scroll">
                                                                                                                                                    <div class="modal-header">
                                                                                                                                                            <button type="button" class="close cmt_close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                                                                            <h3>Sub Section Name</h3>
                                                                                                                                                    </div>
                                                                                                                                                    <div>
                                                                                                                                                                    <div class="addcomment-block">
                                                                                                                                                                            <div class="row">
                                                                                                                                                                                    <div class="col-md-12 ">
                                                                                                                                                                                            <div class="form-group">
                                                                                                                                                                                                    <label class="control-label" > <strong> Sub Section Name : </strong><span class="required">*</span></label>
                                                                                                                                                                                                    <div class="controls">
                                                                                                                                                                                                            <input type="text" class="m-wrap" id="subsection_name_<?php echo $sub->section_id;?>" name="subsection_name_<?php echo $sub->section_id;?>" value="<?php echo $sub->section_name;?>" />
                                                                                                                                                                                                      </div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <div class="pull-right">
                                                                                                                                                                                                    <input type="hidden" name="section_id" id="section_id" value="<?php echo $sub->section_id;?>" />
                                                                                                                                                                                                    <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                                                                                                                                                                    <input type="hidden" name="tab" id="tab" value="tab_1" />
                                                                                                                                                                                                    <button type="button" id="subsection_submit_<?php echo $sub->section_id;?>" name="section_submit" class="btn blue txtbold"> Submit </button>
                                                                                                                                                                                            </div>
                                                                                                                                                                                    </div>
                                                                                                                                                                             </div>
                                                                                                                                                                    </div>
                                                                                                                                                    </div>
                                                                                                                                            </div>
                                                                                                                                    </div>
                                                                                                                                </div>
    <script type="text/javascript">

	$(document).ready(function(){

	$('#chngSubNm_'+<?php echo $sub->section_id;?>).on( 'keypress', function( e ) {
        if( e.keyCode === 13 ) {
            e.preventDefault();
            $("#subsection_submit_"+<?php echo $sub->section_id;?>).trigger('click');
        }
    });



	$('#subsection_submit_'+<?php echo $sub->section_id;?>).click(function(){

    	var id = $("#select_task_assign").val();
	var filter = $("#select_task_status").val();

    	if($('#subsection_name_'+<?php echo $sub->section_id;?>).val()!=''){
                                    var iconClass=$("#ch_subsec_"+<?php echo $sub->section_id;?>+" .expand_sections i").attr('class');
    		$('#dvLoading').fadeIn('slow');
			$.ajax({
					type: 'POST',
					url : "<?php echo site_url('project/update_sectionName') ?>",
					data:{section_name:$("#subsection_name_"+<?php echo $sub->section_id;?>).val(),section_id:<?php echo $sub->section_id;?>,tab:'tab_1',project_id:<?php echo $sub->project_id;?>,type:'subsection',user_id:id,filter:filter,iconclass:iconClass},
					success : function(data) {
						if(data!=''){

						$('#chngSubNm_'+<?php echo $sub->section_id;?>).modal('hide');
						$("#ch_subsec_"+<?php echo $sub->section_id;?>).html(data);
						$('#dvLoading').fadeOut('slow');

						}else{
							$('#dvLoading').fadeOut('slow');

						}
					},

				});
			}else{
				alertify.alert('Field can not be empty');
			}

		});


	});

	</script>     
                                                                                                                                <?php  
																$task_detail = getTaskDetail($sub->section_id,$sub->main_section,$project_id,$task_status_completed_id);
                                                                                                                         
                                                                                                                                    if($task_detail!='0'){

																	foreach ($task_detail as $td) {
                                        
																	$tmp = (array) $td;
																	if(!empty($tmp)){
																	date_default_timezone_set($this->session->userdata("User_timezone"));
																	$today = date($site_setting_date);
																	if($td->master_task_id){
																		$is_master_deleted = chk_master_task_id_deleted($td->master_task_id);
																	} else {
																		$is_master_deleted = 0;
																	}
																	if($td->task_due_date != '0000-00-00'  ){
																		$due_dt = date($site_setting_date,strtotime($td->task_due_date));
																	} else{
																		$due_dt = "N/A";
																	}
																	if($type =='ut'){
																		$con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) > strtotime(str_replace(array("/"," ",","),"-", $today)));
																		$due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
																		$completed = '1 == 1';
																	}else if($type =='tt'){
																		$con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) == strtotime(str_replace(array("/"," ",","),"-", $today)));
																		$due_not = strtotime($td->task_due_date)!= strtotime('0000-00-00');
																		$completed = '1 == 1';
																	}else if($type == 'ot'){
																		$con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) < strtotime(str_replace(array("/"," ",","),"-", $today)));
																		$due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
																		$completed = $task_status_completed_id==$td->task_status_id;
																	}else if($type =='all'){
																		$con = '1 == 1';
																		$due_not = '1 == 1';
																		$completed = '1 == 1';
																	}else if($type =='opt'){
                                                                                                                                            $con = '1 == 1';	
                                                                                                                                            $due_not = '1 == 1';
                                                                                                                                            $completed =$task_status_completed_id==$td->task_status_id;
                                                                                                                                        }
																	if (strpos($td->task_id,'child') !== false) {
																	    $chks = "0";
																	} else {
																		$chks = "1";
																	}
                                                                                                                                        if($chks == "1"){
																	$dependencies = $td->tpp;
																	if($td->tpp!='0' && $td->completed_depencencies=="0"){
																		$completed_depencencies = "green";
																	} else if($td->tpp=='0' && $td->completed_depencencies=="0"){
																		$completed_depencencies = "green";
																	} else {
																		$completed_depencencies = "red";
																	}
                                                                                                                                        } else {
                                                                                                                                                $dependencies = '';
                                                                                                                                                $completed_depencencies = "";
                                                                                                                                        }
																	if(($user_id =='all' || $user_id == $td->task_allocated_user_id) && $con && $due_not && $completed!='1'){
                                                                                                                                            if($td->task_due_date == '0000-00-00'){
                                                                                                                                                $due_date1 = '';
                                                                                                                                            }else{ 
                                                                                                                                                $due_date1 =date("m-d-Y",strtotime($td->task_due_date));
                                                                                                                                            }
                                                                                                                                            if($td->task_scheduled_date == '0000-00-00'){
                                                                                                                                                $scheduled_date = '';
                                                                                                                                            }else{ 
                                                                                                                                                $scheduled_date =date("m-d-Y",strtotime($td->task_scheduled_date));
                                                                                                                                            }
                                                                                                                                            $report_user_list_id = '0';
                                                                                                                                            if(isset($all_report_user) && !empty($all_report_user)){
                                                                                                                                                foreach($all_report_user as $val ){
                                                                                                                                                    if($val['user_id'] == $td->task_owner_id){
                                                                                                                                                       $report_user_list_id = '1';  
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                            
                                                                                                                                            $jsonarray=array(
                                                                                                                                                "task_status" =>$task_status,
                                                                                                                                                "user_colors" =>$color_codes,
                                                                                                                                                "user_swimlanes" =>$swimlanes,
                                                                                                                                                "task_id" =>$td->task_id,
                                                                                                                                                "locked_due_date" => $td->locked_due_date,
                                                                                                                                                "task_due_date" =>$due_date1,
                                                                                                                                                "task_scheduled_date" =>$scheduled_date,
                                                                                                                                                "date" =>strtotime(date('Y-m-d')), 
                                                                                                                                                "active_menu" =>'from_project',
                                                                                                                                                "start_date" =>'',
                                                                                                                                                "end_date" =>'',
                                                                                                                                                "master_task_id" =>$td->master_task_id,
                                                                                                                                                "is_master_deleted" =>chk_master_task_id_deleted($td->master_task_id),
                                                                                                                                                "chk_watch_list" =>'',
                                                                                                                                                "task_owner_id" =>$td->task_owner_id,
                                                                                                                                                "completed_depencencies" =>'',
                                                                                                                                                "color_menu" =>'',
                                                                                                                                                "swimlane_id" =>'',
                                                                                                                                                "task_status_id" => $td->task_status_id,
                                                                                                                                                "before_status_id" => '',
                                                                                                                                                "customer_id" =>'',
                                                                                                                                                 "report_user_list_id" => $report_user_list_id
                                                                                                                                            );
                                       
																	 ?>
                                                                                                                                        <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){ 
                                                                                                                                           $disabled = 'disabled="disabled"';
                                                                                                                                          }else{
                                                                                                                                           $disabled = '';
                                                                                                                                          }?>

																	<div onclick="save_task_for_timer(this,'<?php echo $td->task_id;?>','<?php echo addslashes($td->task_title);?>','<?php echo $td->task_time_spent;?>','<?php echo $chks;?>','<?php echo $completed_depencencies;?>');" class="task_tasksort project_master_task_<?php echo $td->master_task_id;?>" id="task_tasksort_<?php echo $td->task_id;?>" >
																		<ul class="clearfix cst_ul" <?php echo $disabled; ?> >
																			<li  style="margin-top:7px;"><i class="icon-align-justify prjicn"></i></li>
                                                                                                                                                        <li><input class="projectTask" <?php echo $disabled; ?> onclick="changeTaskStatus('<?php echo $td->task_id;?>','<?php echo $td->subsection_id;?>','<?php echo $td->section_id;?>','<?php echo $td->task_time_spent;?>');" name="task_status" id="pr_task_status_<?php echo $td->task_id;?>" value="" type="checkbox" <?php if($td->task_status_id == $task_status_completed_id){ ?> checked="checked" <?php } ?> ></li>
																			
                                                                                                                                                        <li style="margin-top:5px;"><?php if($td->task_status_id == $task_status_completed_id){?><em class="linethrought"><?php }else{?> <?php }?>
																				<?php if($td->master_task_id == '0' || $is_master_deleted=="1"){ ?>
                                                                                                                                                                <div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?> oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php } ?> >
                                                                                                                                                                <a id="tskname_<?=$td->task_id?>" data-toggle="modal" href="javascript:void(0)"  <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?>onclick="edit_task(this,'<?php echo $td->task_id;?>','<?php echo $chks;?>')" <?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
																				</div><?php } else { ?>
                                                                                                                                                                <div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?>oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php } ?> >
                                                                                                                                                                        <a id="tskname_<?=$td->task_id?>" data-toggle="modal" href="javascript:void(0)" <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?> onclick="open_seris(this,'<?php echo $td->task_id;?>','<?php echo $td->master_task_id;?>','<?php echo $chks;?>')" <?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
                                                                                                                                                                </div><?php } ?>
																				<?php if($td->task_status_id == $task_status_completed_id){?></em><?php }else{?> <?php }?>
                                                                                                                                                        </li>
                                                                                                                                                        <li ><?php if($task_status){
                                                                                                                                                            echo'<span class="updttskstatus">( Status: ';
                                                                                                                                                            foreach($task_status as $v){
                                                                                                                                                                if($v->task_status_id==$td->task_status_id){ ?>
                                                                                                                                                                   <a href="javascript:void(0)"  class="task_status_editable"  data-value="<?php echo $v->task_status_id;?>" data-type="select" data-pk="<?=$td->task_id?>" data-original-title="Task Status" data-emptytext="Status" ><?php echo $v->task_status_name;?></a>
                                                                                                                                                            <?php    }
                                                                                                                                                            }
                                                                                                                                                            echo'</span>';
                                                                                                                                                            }?>
                                                                                                                                                        </li>
                                                                                                                                                        <li><span class="updtallcuserl"> 
                                                                                                                                                                - Allocated to  <a href="javascript:void(0)"  class="task_allocated_user_editable"    data-type="select" data-pk="<?=$td->task_id?>" data-original-title="Select User" data-value="<?=$td->task_allocated_user_id?>" data-emptytext="User" ><?php echo  ucwords($td->first_name)." ".ucwords($td->last_name);?></a>
                                                                                                                                                            </span></li>
                                                                                                                                               
                                                                                                                                        <?php
                                                                                                                                        if($td->task_due_date!="0000-00-00" && $due_dt != 'N/A'){
                                                                                                                                            if($td->task_status_id != $task_status_completed_id && (strtotime(str_replace(array("/"," ",","), "-", $due_dt)) < strtotime(str_replace(array("/"," ",","),"-", $today))))
                                                                                                                                               { ?>
                                                                                                                                                            <li><span class="red_date" >- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date'  data-format="<?=$dat?>"class="task_due_date_editable" > <?php echo $due_dt;?></a> </span>) </li>
                                                                                                                                                <?php }else if($td->task_status_id == $task_status_completed_id){ ?>
                                                                                                                                                            <li>- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' data-format="<?=$dat?>"  class="task_due_date_editable" > <?php echo $due_dt;?></a> )</li>
<!--                                                                                                                                                <li><em class="linethrought">- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' data-format="<?=$dat?>"  class="task_due_date_editable" > <?php echo $due_dt;?></a> )</em></li>-->
                                                                                                                                                <?php }else{?>
                                                                                                                                                <li >- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' data-format="<?=$dat?>" class="task_due_date_editable" > <?php echo $due_dt;?></a> )</li>
                                                                                                                                                <?php } }else{?>
                                                                                                                                                <li >- <a href="#" data-pk="<?=$td->task_id?>" data-type='date'  data-format="<?=$dat?>" class="task_due_date_editable" > No Due Date </a> )</li>
                                                                                                                                                <?php }	?>
                                                                                                                                                <?php if($td->locked_due_date){ ?><li><a class="tooltips" data-placement="right" data-original-title="Due date is locked" href="javascript:void(0);"><i class="icon-lock  prjicn prtoppos"> </i></a></li><?php } ?>
                                                                                                                                                <?php if($td->task_priority == "Low"){
                                                                                                                                                echo "<li><a class='tooltips' data-placement='right' data-original-title='Low Priority' href='javascript:void(0);'><i class='icon-warning-sign  prjicn iconlow'></i></a></li>";
                                                                                                                                                } else if($td->task_priority == "Medium"){
                                                                                                                                                echo "<li><a class='tooltips' data-placement='right' data-original-title='Medium Priority' href='javascript:void(0);'><i class='icon-warning-sign  prjicn iconmedium'></i></a></li>";
                                                                                                                                                } else if($td->task_priority == "High"){
                                                                                                                                                echo "<li><a class='tooltips' data-placement='right' data-original-title='High Priority' href='javascript:void(0);'><i class='icon-warning-sign  prjicn iconhigh'></i></a></li>";
                                                                                                                                                } else {
                                                                                                                                                }
                                                                                                                                                if($chks == '1'){
                                                                                                                                                if($td->comments){ ?>
                                                                                                                                                        <li><a class="tooltips" data-placement="right" data-original-title="Comments"onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_7')"  href="javascript:void(0);"><i class="icon-comment-alt prjicn"></i><sup><?=$td->comments?></sup></a></li>
                                                                                                                                                <?php } }?>
                                                                                                                                                
                                                                                                                                        <?php if($td->files){ ?><li><a class="tooltips" data-placement="right" data-original-title="Task Files" href="javascript:void(0);" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_6')" ><i class=" icon-paperclip prjicn"> </i><sup><?=$td->files?></sup></a></li><?php } ?>
                                                                                                                                        <?php if($td->steps){ ?>
                                                                                                                                        <li><a class="tooltips" data-placement="right" data-original-title="Task Steps" href="javascript:void(0);" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_4')"><i class="icon-list-ul prjicn"> </i><sup><?=$td->steps?></sup></a></li>
                                                                                                                                        <?php }?>
                                                                                                                                </ul>

															<input type="hidden" id="task_data_<?php echo $td->task_id;?>" value="<?php echo htmlspecialchars(json_encode($td)); ?>" />
														</div>
                                                                                                                                     
                                                        <?php } } } }

                                                          ?>
															<div> </div>
															<div class="unsorttd" > </div>

															<button onclick="my_custom_task_edit('<?php echo $project_id;?>','<?php echo strtotime(date($default_format));?>','<?php echo $sub->section_id;?>','<?php echo $sub->main_section;?>','<?php echo rawurlencode($s->section_name);?>');datapass('<?php echo $sub->section_id;?>','<?php echo $sub->main_section;?>','<?php echo rawurlencode($s->section_name);?>');" href="javascript:void(0)" type="button" name="task" name="task" class="btn-new green unsorttd" id="pro_button_<?php echo $sub->section_id;?>" style="min-width: 0px !important;">Add Task</button>
														</div>
												</div>
                                                                            <?php } }
                                                                                } ?>

			
                                                                                <div class="panel-body1" id="panel-body1_<?php echo $s->section_id;?>">
                                                                                <?php //if($is_owner!='0'){ ?>
                                                                                    <div class=" unsorttd">
                                                                                                <div class="row">

                                                                                                        <div class="col-md-6" style="padding-top: 3px;">
                                                                                                                <div class="form-group">
                                                                                                                        <div class="controls">
                                                                                                                            <input type="text" name="project_subsection_<?php echo $s->section_id;?>" id="project_subsection_<?php echo $s->section_id;?>" class="col-md-12 m-wrap" placeholder="Enter sub-section name"/>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                    <div class="col-md-6 pull-left" style="padding-top:5px">
                                                                                                                <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                                                                                <input type="hidden" name="project_section_id" value="" id="project_section_id"/>
                                                                                                                <button type="button" class="subsection_btn btn-new unsorttd"   onclick="createSubSection('<?php echo $s->section_id;?>','<?php echo rawurlencode($s->section_name);?>','<?php echo $project_id;?>');">Add Sub Section</button>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
            <!--                                                                   
                                                                                <?php //} ?>
                                                                                <!-- main task settings start here -->

                                                                                    <?php
                                                                                    $task_detail = getTaskDetail($s->section_id,'0',$project_id,$task_status_completed_id);
                                                                                    if($task_detail){
                                                                                            foreach ($task_detail as $td) {
                                                                                                    $tmp = (array) $td;
                                                                                                    if(!empty($tmp)){

                                                                                                                     
                                                                                                                    date_default_timezone_set($this->session->userdata("User_timezone"));
                                                                                                                    $today = date($site_setting_date);
                                                                                                                    //date_default_timezone_set("UTC");

                                                                                                                    if($td->master_task_id){
                                                                                                                    $is_master_deleted = chk_master_task_id_deleted($td->master_task_id);
                                                                                                                    } else {
                                                                                                                            $is_master_deleted = 0;
                                                                                                                    }
                                                                                                                    if($td->task_due_date != '0000-00-00'  ){

                                                                                                                            $due_dt = date($site_setting_date,strtotime($td->task_due_date));
                                                                                                                            //echo $due_dt;die;
                                                                                                                    } else{

                                                                                                                            $due_dt = "N/A";
                                                                                                                    }

                                                                                                                    if($type =='ut'){
                                                                                                                            $con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) > strtotime(str_replace(array("/"," ",","),"-", $today)));
                                                                                                                            $due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
                                                                                                                            $completed = '1 == 1';
                                                                                                                            //echo "cd";
                                                                                                                    }else if($type =='tt'){
                                                                                                                            $con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) == strtotime(str_replace(array("/"," ",","),"-", $today)));
                                                                                                                            $due_not = strtotime($td->task_due_date)!= strtotime('0000-00-00');
                                                                                                                            $completed = '1 == 1';
                                                                                                                    }else if($type == 'ot'){
                                                                                                                            $con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) < strtotime(str_replace(array("/"," ",","),"-", $today)));
                                                                                                                            $due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
                                                                                                                            $completed = $task_status_completed_id==$td->task_status_id;
                                                                                                                    }else if($type =='all'){
                                                                                                                            $con = '1 == 1';
                                                                                                                            $due_not = '1 == 1';
                                                                                                                            $completed = '1 == 1';
                                                                                                                    }else if($type =='opt'){
                                                                                                                            $con = '1 == 1';	
                                                                                                                            $due_not = '1 == 1';
                                                                                                                            $completed =$task_status_completed_id==$td->task_status_id;
                                                                                                                    }

                                                                                                                    if (strpos($td->task_id,'child') !== false) {
                                                                                                                        $chks = "0";
                                                                                                                    } else {
                                                                                                                        $chks = "1";
                                                                                                                    }
                                                                                                                    if($chks == "1"){
                                                                                                                        $dependencies = $td->tpp;
                                                                                                                        if($td->tpp!='0' && $td->completed_depencencies=="0"){
                                                                                                                                $completed_depencencies = "green";
                                                                                                                        } else if($td->tpp=='0' && $td->completed_depencencies=="0"){
                                                                                                                                $completed_depencencies = "green";
                                                                                                                        } else {
                                                                                                                                $completed_depencencies = "red";
                                                                                                                        }
                                                                                                                        } else {
                                                                                                                                $dependencies = '';
                                                                                                                                $completed_depencencies = "";
                                                                                                                        }

                                                                                                                    if(($user_id =='all' || $user_id == $td->task_allocated_user_id) && $con && $due_not && $completed!='1'){
                                                                                                                        if($td->task_due_date == '0000-00-00'){
                                                                                                                            $due_date1 = '';
                                                                                                                        }else{ 
                                                                                                                            $due_date1 =date("m-d-Y",strtotime($td->task_due_date));
                                                                                                                        }
                                                                                                                        if($td->task_scheduled_date == '0000-00-00'){
                                                                                                                            $scheduled_date = '';
                                                                                                                        }else{ 
                                                                                                                            $scheduled_date =date("m-d-Y",strtotime($td->task_scheduled_date));
                                                                                                                        }   
                                                                                                                        $report_user_list_id='0';
                                                                                                                        if(isset($all_report_user) && !empty($all_report_user)){
                                                                                                                            foreach($all_report_user as $val ){
                                                                                                                                if($val['user_id']==$td->task_owner_id){
                                                                                                                                   $report_user_list_id = '1';  
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                         
                                                                                                                                            $jsonarray=array(
                                                                                                                                                "task_status" =>$task_status,
                                                                                                                                                "user_colors" =>$color_codes,
                                                                                                                                                "user_swimlanes" =>$swimlanes,
                                                                                                                                                "task_id" =>$td->task_id,
                                                                                                                                                "locked_due_date" => $td->locked_due_date,
                                                                                                                                                "task_due_date" =>$due_date1,
                                                                                                                                                "task_scheduled_date" =>$scheduled_date,
                                                                                                                                                "date" =>strtotime(date('Y-m-d')), 
                                                                                                                                                "active_menu" =>'from_project',
                                                                                                                                                "start_date" =>'',
                                                                                                                                                "end_date" =>'',
                                                                                                                                                "master_task_id" =>$td->master_task_id,
                                                                                                                                                "is_master_deleted" =>chk_master_task_id_deleted($td->master_task_id),
                                                                                                                                                "chk_watch_list" =>'',
                                                                                                                                                "task_owner_id" =>$td->task_owner_id,
                                                                                                                                                "completed_depencencies" =>'',
                                                                                                                                                "color_menu" =>'',
                                                                                                                                                "swimlane_id" =>'',
                                                                                                                                                "task_status_id" => $td->task_status_id,
                                                                                                                                                "before_status_id" => '',
                                                                                                                                                "customer_id" =>'',
                                                                                                                                                "report_user_list_id" => $report_user_list_id
                                                                                                                                            );
                                                                                                                                        ?>
                                                                                                                                        <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){ 
                                                                                                                                           $disabled = 'disabled="disabled"';
                                                                                                                                          }else{
                                                                                                                                           $disabled = '';
                                                                                                                                          }?>

                                                                                                            <div onclick="save_task_for_timer(this,'<?php echo $td->task_id;?>','<?php echo addslashes($td->task_title);?>','<?php echo $td->task_time_spent;?>','<?php echo $chks;?>','<?php echo $completed_depencencies;?>');" id="task_tasksort_<?php echo $td->task_id;?>" class=" task_tasksort project_master_task_<?php echo $td->master_task_id;?>">
                                                                                                                    <ul class="clearfix cst_ul"  >
                                                                                                                            <li style="margin-top:7px;"><i class="icon-align-justify prjicn"></i></li>

                                                                                                                            <li><input class="projectTask" onclick="changeTaskStatus('<?php echo $td->task_id;?>','<?php echo $td->subsection_id;?>','<?php echo $td->section_id;?>','<?php echo $td->task_time_spent;?>');" name="task_status" id="pr_task_status_<?php echo $td->task_id;?>" value="" type="checkbox" <?php echo $disabled; ?> <?php if($td->task_status_id == $task_status_completed_id){ ?> checked="checked" <?php } ?> ></li>

                                                                                                                            
                                                                                                                            <li style="margin-top:5px;"><?php if($td->task_status_id == $task_status_completed_id){?><em class="linethrought"><?php }else{?> <?php }?>
                                                                                                                                    <?php if($td->master_task_id == '0' || $is_master_deleted=="1"){ ?>

                                                                                                                        <div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?>oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php } ?> >
                                                                                                                            <a  id="tskname_<?=$td->task_id;?>"  data-toggle="modal" href="javascript:void(0)" <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?> onclick="edit_task(this,'<?php echo $td->task_id;?>','<?php echo $chks;?>')"<?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
                                                                                                            </div><?php } else { ?><div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?>oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php  }?> >

                                                                                                            <a data-toggle="modal" <?php echo $disabled; ?> href="javascript:void(0)" <?php if($this->session->userdata('is_customer_user') == '1' & $td->task_allocated_user_id != get_authenticateUserID()) {}else{?> onclick="open_seris(this,'<?php echo $td->task_id;?>','<?php echo $td->master_task_id;?>','<?php echo $chks;?>')" <?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title); ?></a>
                                                                                                                                </div><?php } ?>
                                                                                                            <?php if($td->task_status_id == $task_status_completed_id){?></em><?php }else{?> <?php }?>
                                                                                                                      <span class=" span_updtsk" id="<?=$td->task_id?>" data-tname="<?php echo ucwords($td->task_title);?>"></span>
                                                                                                                            </li>
                                                                                                                            <li ><?php if($task_status){
                                                                                                                                                            echo'<span class="updttskstatus">( Status: ';
                                                                                                                                                            foreach($task_status as $v){
                                                                                                                                                                if($v->task_status_id==$td->task_status_id){ ?>
                                                                                                                                                                   <a href="javascript:void(0)" class="task_status_editable" data-value="<?php echo $v->task_status_id;?>"  data-type="select" data-pk="<?=$td->task_id?>" data-original-title="Task Status" data-emptytext="Status" ><?php echo $v->task_status_name;?></a>
                                                                                                                                                            <?php    }
                                                                                                                                                            }
                                                                                                                                                            echo'</span>';
                                                                                                                                                            }?>
                                                                                                                                                        </li>
                                                                                                                                                        <li><span class="updtallcuserl"> 
                                                                                                                                                              - Allocated to  <a href="javascript:void(0)" class="task_allocated_user_editable" data-value="<?=$td->task_allocated_user_id?>"   data-type="select" data-pk="<?=$td->task_id?>" data-original-title="Select User" data-emptytext="User" ><?php echo  ucwords($td->first_name)." ".ucwords($td->last_name);?></a>
                                                                                                                                                            </span></li>
                                                                                                                                               
                                                                                                                                        <?php
                                                                                                                                        if($td->task_due_date!="0000-00-00" && $due_dt != 'N/A'){
				                                                                      if($td->task_status_id != $task_status_completed_id && (strtotime(str_replace(array("/"," ",","), "-", $due_dt)) < strtotime(str_replace(array("/"," ",","),"-", $today))))
                                                                                                                                               { ?>
                                                                                                                                                            <li><span class="red_date">- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> </span> )</li>
                                                                                                                                                <?php }else if($td->task_status_id == $task_status_completed_id){ ?>
                                                                                                                                                <li><em class="linethrought">- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> )</em></li>
                                                                                                                                                <?php }else{?>
                                                                                                                                                <li >- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> )</li>
                                                                                                                                                <?php } }else{?>
                                                                                                                                                <li >- <a href="#" data-pk="<?=$td->task_id?>" data-type='date'  class="task_due_date_editable" data-format="<?=$dat?>"> No Due Date </a> )</li>
                                                                                                                    <?php } ?>
                                                                                                                        <?php if($td->locked_due_date){ ?><li><a class="tooltips" data-placement="right" data-original-title="Due date is locked" href="javascript:void(0);"><i class="icon-lock  prjicn prtoppos"> </i></a></li><?php } ?>
                                                                                                    <?php if($td->task_priority == "Low"){
                                                                                                            echo "<li><a class='tooltips' data-placement='right' data-original-title='Low Priority' href='javascript:void(0);'><i class=' icon-warning-sign prjicn iconlow'></i></a></li>";
                                                                                                    } else if($td->task_priority == "Medium"){
                                                                                                            echo "<li><a class='tooltips' data-placement='right' data-original-title='Medium Priority' href='javascript:void(0);'><i class='icon-warning-sign prjicn iconmedium'></i></a></li>";
                                                                                                    } else if($td->task_priority == "High"){
                                                                                                            echo "<li><a class='tooltips' data-placement='right' data-original-title='High Priority' href='javascript:void(0);'><i class=' icon-warning-sign prjicn iconhigh'></i></a></li>";
                                                                                                    } else {

                                                                                                    }?>
                                                                                                        <?php if($chks == '1'){
                                                                                                                    if($td->comments){ ?>
                                                                                                                            <li><a class="tooltips" data-placement="right" data-original-title="Comments" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_7')" href="javascript:void(0);"><i class="icon-comment-alt  prjicn "> </i><sup><?=$td->comments?></sup></a></li>
                                                                                                                    <?php }
                                                                                                            }?>
                                                                                                      <?php if($td->files){ ?><li><a class="tooltips" data-placement="right" data-original-title="Task Files" href="javascript:void(0);" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_6')" ><i class="icon-paperclip  prjicn"> </i><sup><?=$td->files?></sup></a></li><?php } ?>
                                                                                                        <?php if($td->steps){ ?> <li><a class="tooltips" data-placement="right" data-original-title="Task Steps" href="javascript:void(0);" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_4')" ><i class="prjicn icon-list-ul"> </i><sup><?=$td->steps?></sup></a></li>
                                                                                                            <?php }?>


                                                                                        </ul>

                                                                                                <input type="hidden" id="task_data_<?php echo $td->task_id;?>" value="<?php echo htmlspecialchars(json_encode($td)); ?>" />
                                                                            </div>



                                                                                            <?php } } } }

                                                                                            ?>
                                                                    <div> </div>
                                                                    <div class="list-unsorttd" > </div>
                                                                    <!-- main task settings end here -->

                                                                    <div  class="margin-top-10 add_new_task_div subsectionbtn_cls unsorttd">
                                                                        <button onclick="my_custom_task_edit('<?php echo $project_id;?>','<?php echo strtotime(date($default_format));?>','<?php echo $s->section_id;?>','0','<?php echo rawurlencode($s->section_name);?>');datapass('<?php echo $s->section_id;?>','0','<?php echo rawurlencode($s->section_name);?>');" href="javascript:void(0)" type="button" name="task"  class="btn-new green unsorttd" id="pro_button_<?php echo $s->section_id;?>" style="min-width: 0px !important;">Add Task</button>
                                                                        
                                                                    </div>
                                                                    
                                                                                                                <div id="chngNm_<?php echo $s->section_id;?>" class="modal model-size pro-change fade" tabindex="-1" >
                                                                                                                    <div class="portlet">
                                                                                                                            <div class="portlet-body  form flip-scroll">
                                                                                                                                    <div class="modal-header">
                                                                                                                                            <button type="button" class="close cmt_close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                                                            <h3>Section Name</h3>
                                                                                                                                    </div>
                                                                                                                                    <div>
                                                                                                                                                    <div class="addcomment-block">
                                                                                                                                                            <div class="row">
                                                                                                                                                                    <div class="col-md-12 ">
                                                                                                                                                                            <div class="form-group">
                                                                                                                                                                                    <label class="control-label" > <strong> Section Name : </strong><span class="required">*</span></label>
                                                                                                                                                                                    <div  class="controls">
                                                                                                                                                                                            <input type="text" class="m-wrap" id="section_name_<?php echo $s->section_id;?>" name="section_name_<?php echo $s->section_id;?>" value="<?php echo $s->section_name;?>" />
                                                                                                                                                                                      </div>
                                                                                                                                                                            </div>
                                                                                                                                                                            <div class="pull-right">
                                                                                                                                                                                    <input type="hidden" name="section_id" id="section_id" value="<?php echo $s->section_id;?>" />
                                                                                                                                                                                    <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                                                                                                                                                    <input type="hidden" name="tab" id="tab" value="tab_1" />
                                                                                                                                                                                    <button type="button" id="section_submit_<?php echo $s->section_id;?>" name="section_submit" class="btn blue txtbold"> Submit </button>
                                                                                                                                                                            </div>
                                                                                                                                                                    </div>
                                                                                                                                                             </div>
                                                                                                                                                    </div>
                                                                                                                                    </div>
                                                                                                                            </div>
                                                                                                                    </div>
                                                                                                            </div> 
<script type="text/javascript">

                                $(document).ready(function(){

                                $('#chngNm_'+<?php echo  $s->section_id;?>).on( 'keypress', function( e ) {
                                if( e.keyCode === 13 ) {
                                    e.preventDefault();
                                    $("#section_submit_"+<?php echo  $s->section_id;?>).trigger('click');
                                }
                            });

                                $('#section_submit_'+<?php echo $s->section_id;?>).click(function(){

                                        var id = $("#select_task_assign").val();
                                        var filter = $("#select_task_status").val();

                                if($('#section_name_'+<?php echo $s->section_id;?>).val()!=''){
                                    var iconClass=$("#ch_sec_"+<?php echo $s->section_id;?>+" .expand_sections i").attr('class');

                                        $('#dvLoading').fadeIn('slow');
                                                $.ajax({
                                                                type: 'POST',
                                                                url : "<?php echo site_url('project/update_sectionName') ?>",
                                                                data:{section_name:$("#section_name_"+<?php echo $s->section_id;?>).val(),section_id:<?php echo $s->section_id;?>,project_id:<?php echo $s->project_id;?>,type:'section',user_id:id,filter:filter,iconclass:iconClass},

                                                                success : function(data) {
                                                                        if(data!=''){

                                                                        $('#chngNm_'+<?php echo $s->section_id;?>).modal('hide');
                                                                        $("#ch_sec_"+<?php echo $s->section_id;?>).html(data);
                                                                        $('#dvLoading').fadeOut('slow');
                                                                        return false;

                                                                        }else{
                                                                                $('#dvLoading').fadeOut('slow');
                                                                                return false;

                                                                        }
                                                                },

                                                        });
                                                }else{
                                                        alertify.alert('Field can not be empty')
                                                }

                                        });

                                        

                                });
</script>
                                                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                                    <?php } } ?>
                                                                    

								</div>
                                                                                            <div class="col-md-12">
                                                                                                    <div class="row">

                                                                                                            <div class="col-md-6" style="padding-top: 3px;">
                                                                                                                    <div class="form-group">
                                                                                                                            <div class="controls">
                                                                                                                                <input type="text" name="project_section" id="project_section" class="col-md-12 m-wrap" placeholder="Enter section name" <?php if($project_id == '0'){echo 'disabled="disabled"';}?>/>
                                                                                                                            </div>
                                                                                                                    </div>
                                                                                                            </div>
                                                                                                            <div class="col-md-6 pull-left" style="padding-top:5px">
                                                                                                                    <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                                                                                    <button type="button" class="btn blue btn-new unsorttd" id="name_section" name="name_section">Add Section</button>
                                                                                                            </div>

                                                                                                    </div>
                                                                                            </div>
                                                                                        </div>
										</div>
									</div>

                                                                        

									<!-- form ends-->
								</div>
							 	
							 </div>
							 <div class="clearfix"></div>
						    </div>

						</div>
                                              
                                            <!-- Section & sub-section end here-->
                                        </div>
                                </div>
                                <!-- 30% column -->
				<div class="col-md-4 prj_info" style="background: #F0F0F0">
                                    
					<!-- Project info collapse -->
                                        <div class="row " style="margin-top: 10px; " onclick="expand_project_data('1')" >
                                            <div class="col-md-12 panel-heading_pro ">
                                                <a href="javascript:void(0);" >
                                                    <span id="expand_1"><i class="icon-chevron-right default_color" ></i></span>
                                                    <label class="control-label default_color" >General Info</label>
                                                </a>
                                                
                                                <input type="hidden" name="collapse1" id="collapse1" value="1"/>
                                            </div>
                                        </div>
                                        
                                        <div id="project_info_collapse" style="display:none">
                                        <form name="form_general" id="form_general">
                                        
					<!-- Project description -->
                                        <div class="form-group relative-position cstgeninfo">
                                            <div class="row">
                                                <label class="col-md-10 control-label"><b>Description </b><span class="req">*</span></label>
                                                    <div class="col-md-12 relative-position" >
                                                            <textarea class="m-wrap col-md-12 project-select" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'readonly';}?> name="project_desc" id="project_desc" <?php echo($project_id!='0')?($is_owner=='0')?'readonly="readonly"':'':'';?> rows="4" <?php if($project_id == '0'){echo 'disabled="disabled"';}?>><?php echo $project_desc; ?></textarea><span class="add-on"><!--<i class="stripicon gray-edit"></i>--></span>
                                                            <span class="input-load project-textarea-load" id="project_desc_loading"></span>
                                                           
                                                    </div>
                                            </div>
                                        </div>
					<?php
                                            date_default_timezone_set($this->session->userdata("User_timezone"));
                                            $today_date = date($default_format);
                                            if($project_start_date){
                                            $project_start_date = date($default_format,strtotime(str_replace(array("/"," ",","), "-", $project_start_date)));}
                                        ?>

					<!-- Project Start and End Date -->
					<div class="row">
						<div class="col-md-6">
                                                         <label class="control-label"><b>Start Date</b> <span class="required">*</span></label>
                                                         <div class="input-append date date-picker prj-start-date relative-position" data-date="" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
									<div class="row">
                                                                            <div class="col-md-9" style="padding-right: 0px !important;">
                                                                                    <input class="m-wrap m-ctrl-medium setdterr project-select form-control" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'disabled="disabled"';}?> name="project_start_date" id="project_start_date" <?php if($project_id == '0'){echo 'disabled="disabled"';}?> size="16" type="text" value="<?php if($project_start_date){ echo $project_start_date;}else{ echo $today_date;} ?>" style="padding-right: 0px;"/>
										</div>
											<span class="add-on"><i class="icon-calendar taskppicn" style="color: #000 !important;"></i></span>
                                                                            <span class="input-load setting-select-load" id="project_start_date_loading"></span>
                                                                            
									</div>
                                                         </div>
                                                </div>
						<?php
                                                    date_default_timezone_set($this->session->userdata("User_timezone"));
                                                    $today_date = date($default_format);
                                                    if($project_end_date){
                                                    $project_end_date = date(default_date_format(),strtotime(str_replace(array("/"," ",","), "-", $project_end_date)));}
                                                ?>

						<div class="col-md-6">
                                                        <label class="control-label"><b>End Date </b><span class="required">*</span></label>
								<div class="input-append date date-picker prj-end-date " data-date="" data-date-viewmode="years">
                                                                    <div class="row">
                                                                        <div class="col-md-9" style="padding-right: 0px !important;">
										<input class="m-wrap m-ctrl-medium setdterr project-select  form-control" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'disabled="disabled"';}?> name="project_end_date" id="project_end_date" <?php if($project_id == '0'){echo 'disabled="disabled"';}?> size="16" type="text" value="<?php if($project_end_date){ echo $project_end_date;}else{ echo $today_date;} ?>" style="padding-right: 0px;"/>
										</div>
										<span class="add-on"><i class="icon-calendar taskppicn"></i></span>
                                                                                <span class="input-load setting-select-load" id="project_end_date_loading"></span>
                                                                             
                                                                    </div>
								</div>
						</div>
					</div>

					<!-- Status field -->
					<div class="row">
						<div class="col-md-10">
                                                    <label class="control-label"><b>Status</b> <span class="required">*</span></label><br>
								<select onchange="checkstatus(this.value);" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'disabled="disabled"';}?> class="form-control m-wrap radius-b project-select" name="project_status" id="project_status" tabindex="1" <?php if($project_id == '0'){echo 'disabled="disabled"';}?>  >
									<option value="Open" <?php if($project_status == 'Open'){ echo 'selected="selected"'; } ?>>Open</option>
										<?php if($project_id!='0'){ ?>
										<option value="Complete" <?php if($project_status == 'Complete'){ echo 'selected="selected"'; } ?>>Completed</option>
										<option value="On_hold" <?php if($project_status == 'On_hold'){ echo 'selected="selected"'; } ?>>On Hold</option>
										<option value="Cancelled" <?php if($project_status == 'cancelled'){ echo 'selected="selected"'; } ?>>Cancelled</option>
										<?php } ?>
								</select>
							<span class="input-load project-select-load" id="project_status_loading"></span>
						</div>
					</div>

					<!-- Division field -->
					<div class="row">
						<div class="col-md-10">
                                                    <label class="control-label"><b>Division</b></label> <br>
							<select onchange="getdepartment(this.value,'<?php echo $department_id;?>');" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'disabled="disabled"';}?> class="form-control radius-b m-wrap" name="division_id" id="division_id" tabindex="1" <?php if($project_id == '0'){echo 'disabled="disabled"';}?> >
								<option value="">-- Select Division --</option>
									<?php if($division){
										foreach ($division as $row) { ?>
											<option value="<?php echo $row->division_id;?>" <?php if($row->division_id == $division_id){ echo 'selected="selected"'; } ?>><?php echo $row->devision_title; ?></option>
									<?php	} } ?>
							</select>
                                                        <span class="input-load project-div-load" id="division_id_loading"></span>
						</div>
					</div>

					<!-- Department field -->
					<div class="row">
						<div class="col-md-10">
                                                    <label class="control-label first"><b>Department </b> </label><br>
							<select class="form-control m-wrap radius-b" name="department_id" id="department_id" tabindex="1" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'disabled="disabled"';}?>
								<?php if($project_id == '0'){echo 'disabled="disabled"';}?> >
								<option value="">-- Select Department--</option>
									<?php if($department){
										foreach($department as $row){ ?>
											<option value="<?php echo $row->department_id;?>" <?php if($row->department_id == $department_id){ echo 'selected="selected"'; } ?>><?php echo $row->department_title; ?></option>
									<?php } } ?>
							</select>
							<span class="input-load project-div-load" id="department_id_loading"></span>
                                                </div>
					</div>
  
					<!-- Customer field -->
                                        <div class="row" >
						<div class="col-md-11">
							<?php if($this->session->userdata('customer_module_activation')=='1'){ ?>
                                                            <label class="control-label first"><b>Customer</b></label>
                                                            <div class="controls first ">
                                                                <?php if(isset($customers) && $customers != ''){ ?>
                                                                        <select class="m-wrap no-margin col-md-11  radius-b chosen project-select" name="project_customer_id" id="project_customer_id" tabindex="5" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'disabled="disabled"';}?> >                                                                                                                                 <option value="0"  >Please select</option>
                                                                                  <?php foreach($customers as $row){ ?>
                                                                                           <option value="<?php echo $row->customer_id;?>" <?php if($row->customer_id == $customer_id){ echo 'selected="selected"'; } ?> > <?php echo $row->customer_name; ?> </option>
                                                                                  <?php } ?>
                                                                        </select>
                                                                <?php } else { ?>
                                                                       <select class="m-wrap no-margin col-md-11  chosen" disabled="disabled" name="project_customer_id" tabindex="5" >
                                                                                <option value="0" disabled="disabled">Please select</option>
                                                                       </select>
                                                                <?php } ?>
                                                            </div>
                                                           
                                                        <?php }?>
						</div>
                                            
					</div>
					<!-- Button-->
					</br>
					 <div class="row" >
					    <div class="col-md-12">
						 <div class="controls first">
                                                    <button type="button" class="btn blue btn-new unsorttd pull-right" id="save_general" name="save_general" <?php if($this->session->userdata('is_customer_user') == '1'){ echo 'disabled="disabled"' . 'style="padding: 6px 12px;"';}?>>Save</button>
                                                </div>
                                            </div>
					</div>
					</form>
                                    </div>      
                                       
                                        <!-- project info end-->
                                        
                                        <!-- Project Finances info -->
                                        <?php if($this->session->userdata('pricing_module_status')=='1' && $is_owner == '1' && $this->session->userdata('is_customer_user') == '0'){?>
                                        <div class="row " style="margin-top: 10px;" onclick="expand_project_data('6')">
                                            <div class="col-md-12 panel-heading_pro ">
                                                <a href="javascript:void(0);"  >
                                                    <span id="expand_6"><i class="icon-chevron-right default_color" ></i></span>
                                                    <label class="control-label default_color" >Finance </label>
                                                </a>
                                                
                                                <input type="hidden" name="collapse6" id="collapse6" value="1"/>
                                            </div>
                                        </div>
                                        <div id="project_finance_info" style="display:none">
                                            <div class="row">
                                                <div class="col-md-12" style="padding-top:5px">
                                                    <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                    <button type="button" class="btn blue btn-new unsorttd pull-right" id="finance_refresh" name="finance_refresh" style="margin-right: 8px !important;">Refresh</button>
                                                </div>
                                                <div class="col-md-12" style="padding-top: 5px;">
                                                    <label class="control-label col-md-6" ><b>Estimated Revenue</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($estimated_revenue, '.') == false) { echo $estimated_revenue.'.00';}else {echo $estimated_revenue;} ?> </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Estimated Cost</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($estimated_cost, '.') == false) { echo $estimated_cost.'.00';}else {echo $estimated_cost;} ?></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Estimated Profit</b></label>
                                                    <label class="control-label col-md-3" ><?php echo $estimated_margin.'%'; ?></label>
                                                    <label class="control-label col-md-3" <?php if($estimated_profit<0){ echo "style='color:red'";}?> ><?php echo $this->session->userdata('currency');?><?php if (strpos($estimated_profit, '.') == false) { echo $estimated_profit.'.00';}else {echo $estimated_profit;}?></label>
                                                </div>
                                                <div class="col-md-12" style="padding-top:15px;">
                                                    <label class="control-label col-md-6" ><b>Committed Revenue</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($committed_revenue, '.') == false) { echo $committed_revenue.'.00';}else {echo $committed_revenue;} ?></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Non Billable Time / Cost</b></label>
                                                    <label class="control-label col-md-1" ></label>
                                                    <label class="control-label col-md-5" ><?php echo $non_billable_time .' /  '?><?php echo $this->session->userdata('currency');?><?php if (strpos($non_billable_cost, '.') == false) { echo $non_billable_cost.'.00';}else {echo $non_billable_cost;}?> </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Committed Cost</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($committed_cost, '.') == false) { echo $committed_cost.'.00';}else {echo $committed_cost;}?> </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Committed Profit</b></label>
                                                    <label class="control-label col-md-3" ><?php echo $committed_margin.'%';?></label>
                                                    <label class="control-label col-md-3" <?php if($committed_profit<0){ echo "style='color:red'";}?> ><?php echo $this->session->userdata('currency');?><?php if (strpos($committed_profit, '.') == false) { echo $committed_profit.'.00';}else {echo $committed_profit;}?></label>
                                                </div>
                                                <div class="col-md-12" style="padding-top:10px;">
                                                    <label class="control-label col-md-6" ><b>Project base hourly rate</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <span class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><a herf="#" data-name="project_base_rate" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount"  data-type="text" data-pk="1" id="edit_project_charge_rate"><?php if($project_base_rate!='0'){echo $project_base_rate;}?></a></span>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Project Fixed price</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <span class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><a herf="#" data-name="project_fixed_price" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount"  data-type="text" data-pk="1" id="edit_project_fixed_charge_rate"><?php if($project_fixed_price!='0'){echo $project_fixed_price;}?></a></span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <?php }?>
                                        <!-- project finance info end-->
                                        <!-- People info collapse-->
                                       
                                        <div class="row " style="margin-top: 10px;"  onclick="expand_project_data('2')">
                                            <div class="col-md-12 panel-heading_pro">
                                                <a href="javascript:void(0);" >
                                                    <span id="expand_2"><i class="icon-chevron-right default_color" ></i></span>
                                                    <label class="control-label default_color" >Project Team</label>
                                                </a>
                                                
                                                <input type="hidden" name="collapse2" id="collapse2" value="1"/>
                                            </div>
                                        </div>
                                        
                                        
					<!-- People info end -->
                                        
                                        <div id="project_team_collapse" style="display:none">
                                            <div class="row" >
                                                <div class="col-md-12" style="padding-top: 30px;">
						   <div class="people-list" >
							<ul id="memberlist" class="list-unstyled">
                                                            <?php if($members!=""){
                                                                $proj_admin = get_project_info($project_id);
                                                                foreach ($members as $mem) {
                                                                    $is_allocated = get_project_allocated($project_id,$mem->user_id);
                                                                    $name = 'upload/user/'.$mem->profile_image;
                                                                    if(($mem->profile_image != '' || $mem->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) {
                                                                        $src_member =  $s3_display_url.'upload/user/'.$mem->profile_image;
                                                                    } else {
                                                                         $src_member = $s3_display_url.'upload/user/no_image.jpg';
                                                                    } ?>
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function(){
                                                                            $('#edit_member_projectrate_<?php echo $mem->project_users_id;?>').editable({
                                                                                    url: SIDE_URL + "project/update_project_member_rate",
                                                                                    params:{project_user_id : <?php echo $mem->project_users_id;?>,user_id:<?php echo $mem->user_id;?>,project_id:<?php echo $mem->project_id;?>},
                                                                                    type: "post",
                                                                                    pk: 1,
                                                                                    mode: "popup",
                                                                                    showbuttons: !0,
                                                                                    validate: function(e) {

                                                                                          var s = /^[0-9 .]*$/;
                                                                                          return s.test($.trim(e)) ? void 0 : "Please enter only number."
                                                                                    },
                                                                                    success: function() {}  
                                                                            });      
                                                                        });
                                                                    </script>
                                                                    <li class="customer-user_li">
                                                                        <div class="people-block">
                                                                            <div class="people-img">
                                                                                <img src="<?php echo $src_member;?>" alt="photo1" class="img-polaroid img-circle" >
                                                                                    <?php if($proj_admin['project_added_by'] != $mem->user_id && $is_allocated <= '0' && $is_owner == '1'){ ?>
                                                                                            <a onclick="removeUser('<?php echo $mem->project_users_id;?>','<?php echo $mem->user_id;?>','<?php echo $mem->project_id;?>');" href="javascript:void(0)" >
                                                                                            <i class="stripicon iconredcolse" style="right: 57px !important"></i></a>
                                                                                    <?php } ?>
                                                                            </div>
                                                                            <p> <?php echo ucwords($mem->first_name)." ".ucwords($mem->last_name);?>  </p>
                                                                            <?php if($mem->is_customer_user == 1){ echo "<p>(External)</p>"; }else{?>
                                                                            <?php if($is_owner == '1'){?>
                                                                            <div><input type="checkbox" name="new_project_owner" id="new_project_owner" value="<?php echo $mem->project_users_id.'&'.$mem->user_id; ?>"  <?php if($mem->is_project_owner == '1'){ echo "checked='checked'"; } ?> /> <span style="display: inline;position: relative;top: -2px;">Admin</span></div>
                                                                            <?php }else{ 
                                                                                echo($mem->is_project_owner == '1')?"<span>  Admin </span>":"<span>  &nbsp; </span>";
                                                                            } } ?>
                                                                            <?php if($this->session->userdata('pricing_module_status')=='1' && $is_owner == '1'){?>
                                                                            <p><span>Project rate</span></p>
                                                                            <span><?php echo $this->session->userdata('currency');?><a href="#" data-name="project_rate" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-type="text" data-pk="1" class="txt-style edit_title" id="edit_member_projectrate_<?php echo $mem->project_users_id;?>"><?php if($mem->project_rate!='0'){echo $mem->project_rate;}?></a></span>
                                                                            <?php }?>
                                                                        </div>
                                                                    </li>
                                                                <?php }	}?>
                                                                <?php
                                                                    if($is_owner=='1'){ ?>
                                                                    <li class="customer-user_li">
                                                                        <div class="people-block">
                                                                        <a onclick="listmem('<?php echo $project_id;?>');" class="btn" data-toggle="modal"
                                                                        href="#" style="height: 88px; vertical-align: text-bottom; color: darkgray"
                                                                        id="add_users_<?php echo $project_id;?>" data-dismiss="modal" ><br/>Click to <br/>Add Users</a>
                                                                        </div>
                                                                     </li>
                                                               <?php } ?>
                                                        </ul>
                                                    </div>
							
                                                    <!-- only if the user is owner, show the button -->
							<!--<?php
								if($is_owner=='1'){ ?>
									<div class="form-group">
									<a onclick="listmem('<?php echo $project_id;?>');" class="btn blue" data-toggle="modal" href="#users_list" id="add_users_<?php echo $project_id;?>" data-dismiss="modal" ><i class="stripicon icoplus"></i>Add Users</a>
									</div>
							<?php } ?>-->
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!--  Comment section start  -->
                                        <div class="row " style="margin-top: 10px;" onclick="expand_project_data('4')">
                                            <div class="col-md-12 panel-heading_pro">
                                                <a href="javascript:void(0);"  >
                                                    <span id="expand_4"><i class="icon-chevron-right default_color" ></i></span>
                                                    <label class="control-label default_color" >Comment</label>
                                                </a>
                                                
                                                <input type="hidden" name="collapse4" id="collapse4" value="1"/>
                                            </div>
                                        </div>
                                        
                                        <div id="project_comment_div" style="display:none">
                                            
                                             <div class="row" style="margin-top: 10px; margin-left: 10px; margin-right: 0px;">
                                               <div class="col-md-12">
                                                    <div class="row" style="margin-bottom">
                                                        <form name="comment_form" id="cmt121" action="">
                                                            <div class="col-md-10" style="padding-left: 15px;">
                                                                <div class="form-group">
                                                                    <div class="controls">
                                                                        <textarea rows="3" maxlength="<?php echo CMT_TEXT_SIZE;?>" name="project_comment" id="project_comment" class="col-md-12 m-wrap" placeholder="Add a comment." <?php if($project_id == '0'){echo 'disabled="disabled"';}?>></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 pull-right" style="margin-top:21px;padding-left: 0px;">
                                                                <input type="hidden" class = "main_project" name="project_id" id="project_id" value="<?php echo $project_id; ?>" />
                                                                <button type="submit" class="btn blue txtbold" >Add</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> 
                                             <hr>
                                            <div id="listcmt" style="padding-top: 10px;padding-right: 10px;">
						<?php if($comments){
							foreach ($comments as $cmt) {
							    $user = get_user_info($cmt->comment_addeby);
							    $name = 'upload/user/'.$user->profile_image;
                                                            if(($user->profile_image != '' || $user->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) {
                                                               $src =  $s3_display_url.'upload/user/'.$user->profile_image;
                                                            } else {
                                                               $src = $s3_display_url.'upload/user/no_image.jpg';
                                                            } ?>
                                                            <div class="row" >
                                                                <div class="col-md-2">
                                                                    <img class="img-responsive comment-img-new" alt="" src="<?php echo $src;?>" alt="" />
                                                                </div>
                                                                <div class="col-md-10 well well-sm">
                                                                    <label class="comment-label "><b><?php echo ucwords($user->first_name)." ".ucwords($user->last_name);?></b> added <?php echo time_ago($cmt->comment_added_date); ?></label>
                                                                    <div class="position-relative">
                                                                        <div class="comment-msgblock">
                                                                            <p class="comment-label"><?php echo $cmt->task_comment;?></p>
                                                                        </div>
                                                                        <?php if($cmt->comment_addeby == get_authenticateUserID()){ ?>
                                                                                <a href="javascript:void(0)" onclick="removeCmt('<?php echo $cmt->task_comment_id;?>')"  >
                                                                                <i class="icon-trash prjcmt" id="removeCmt_<?php echo $cmt->task_comment_id;?>"></i></a>
                                                                                <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
			  		        <?php } } ?>
                                            </div>
                                        </div>
                                        
                                        <!-- comment section end here -->
                                        
                                        <!-- file section start -->
                                        <div class="row " style="margin-top: 10px;" onclick="expand_project_data('5')" >
                                            <div class="col-md-12 panel-heading_pro ">
                                                <a href="javascript:void(0);" >
                                                    <span id="expand_5"><i class="icon-chevron-right default_color" ></i></span>
                                                    <label class="control-label default_color" >Files</label>
                                                </a>
                                                
                                                <input type="hidden" name="collapse5" id="collapse5" value="1"/>
                                            </div>
                                        </div>
                                        
                                        <div id="project_file_div" style="display:none">
                                            <div class="row" style="margin-top: 0px; margin-left: 20px;margin-right: 0px;">
                                            
                                                        <div class="portlet-body  form">
                                                            <div class="table-toolbar"> </div>
                                                            <div class="row" style="margin-top:5px">
                                                                <div class="col-md-12 ">
                                                                    <label class="control-label bld col-md-4" style="padding-top: 5px;">Add File : <span class="required">*</span></label>
                                                                    <div class=" col-md-8" style="padding-left: 0px;">
                                                                        <div class="fileupload fileupload-new task-file-dv" data-provides="fileupload" >
                                                                            <form name="frm_project_files" id="frm_project_files" action="" enctype="multipart/form-data">
                                                                                <div class="input-append">
                                                                                    <div class="uneditable-input" style="display: none;">
                                                                                        <i id="icon-prj" class="icon-file fileupload-exists"></i>
                                                                                        <span class="fileupload-preview"></span>
                                                                                    </div>
                                                                                    <span class="btn blue btn-file browse-btn" onclick="prjbrowseClicked();">
                                                                                        <span id="browse-prj" class="fileupload-new">Browse</span>
                                                                                        <span id="change-prj" class="fileupload-exists">Change</span>
                                                                                        <input type="file" name="project_file" id="project_file" class="default" />
                                                                                    </span>
                                                                                </div>
                                                                                <input type="hidden" class = "main_project" name="project_id" id="files_project_id" value="<?php echo $project_id;?>" />
                                                                            </form>
                                                                        </div>
                                                                        <span style="color:#333;">OR</span>
                                                                        <div class="btn blue link-btn" onclick="prjlinkClicked();">Link</div>
                                                                     </div>
                                                                </div>
                                                                <div class="col-md-12"  id="link_form">
                                                                                <form class="frm_upload_link col-md-12" name="prj_frm_upload_link" id="prj_frm_upload_link" enctype="multipart/form-data" style="margin-top:5px">
                                                                                    <input type="text"  name="prj_file_name1" id="prj_file_name1" value="" class="m-wrap col-md-4" placeholder="File Name" tabindex="1" style="margin-right: 2px;">
                                                                                    <input type="text"  name="prj_file_link" id="prj_file_link" value="" class="m-wrap col-md-4" placeholder="File Link" tabindex="1" style="margin-right: 2px;">
                                                                                    <input type="hidden" class = "main_project" name="project_id" id="files_project_id" value="<?php echo $project_id; ?>" />
                                                                                    <input type="hidden" id="tab" name="tab" value="" >
                                                                                    <button type="button" class="btn blue col-md-3" id="prj_upload-link-btn">Add Link</button>
                                                                                </form>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                            </div>
                                            <hr>    
                                            <div  class="customtable table-scrollable form-horizontal " style="padding-top:5px" id="drag_area">
                                                <div id="drag_message_project" style="display: none;padding-top: 10% !important;border: 2px dashed #b7afaf;padding: 5%;font-size: 25px;color: #b5abab;position: absolute;width: 94%;height: 15%;background: #ecebeb;z-index: 1">Drop Files here to upload</div>
                                                <table class="table table-striped  table-hover table-condensed flip-content">
                                                    <thead class="flip-content">
                                                        <tr>
                                                            <th>Doc Type</th>
                                                            <th>File Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="updated_project_files">
                                                       <?php
                                                       if(isset($files) && $files!= ''){
                                                          foreach($files as $files){
                                                            if($files['file_link']){ ?>
                                                                <tr>
                                                                    <td width="8%" class="text-center">
                                                                        <a href="<?php echo $files['file_link'];?>" target="_blank">
                                                                           <img src="<?php echo base_url().getThemeName();?>/assets/img/link.png" />
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <p class="txt-normal"><strong><a href="<?php echo $files['file_link'];?>" target="_blank"><?php echo $files['task_file_name'];?></a></strong></p>
                                                                        <p> by <?php echo $files['first_name']." ".$files['last_name']; ?> a <?php echo time_ago($files['file_date_added']);?>.   </p>
                                                                    </td>
                                                                    <td width="15%">
                                                                        <?php if($files['file_added_by']==get_authenticateUserID()){?>
                                                                            <a onclick="setval('<?php echo $files['task_file_id'];?>');" data-toggle="modal" href="#task_file-replace" data-dismiss="modal" ><i class="stripicon iconrefresh" style="transform: scale(0.75); "></i></a>
                                                                            <a href="javascript:;" onclick="delete_project_file('<?php echo $files['task_file_id'];?>')" id="project_file_<?php echo $files['task_file_id'];?>"> <i class="icon-trash tmsticn"></i></a>
                                                                        <?php }?>
                                                                    </td>
                                                                </tr>
                                                            <?php } else {
                                                                    $name = 'upload/task_project_files/'.$files['task_file_name'];
                                                                    $chk = $this->s3->getObjectInfo($bucket,$name);
                                                                    if($chk)
                                                                    {
                                                                        $info = new SplFileInfo($s3_display_url.'upload/task_project_files/'.$files['task_file_name']);
                                                                        $ext = $info->getExtension();
                                                                        ?>
                                                                        <tr>
                                                                            <td width="8%" class="text-center">
                                                                                <a href="<?php echo $s3_display_url.'upload/task_project_files/'.$files['task_file_name'];?>" target="_blank">
                                                                                    <?php if($ext == 'csv'){ ?>
                                                                                       <img src="<?php echo base_url().getThemeName();?>/assets/img/csv.png" />
                                                                                    <?php }elseif($ext == 'pdf'){ ?>
                                                                                       <img src="<?php echo base_url().getThemeName();?>/assets/img/pdf.png" />
                                                                                    <?php }elseif($ext == 'xls' || $ext == 'xlsx' || $ext == 'xl'){ ?>
                                                                                       <img src="<?php echo base_url().getThemeName();?>/assets/img/excel.png" />
                                                                                    <?php }elseif($ext == 'doc' || $ext == 'docx' || $ext == 'word'){ ?>
                                                                                       <img src="<?php echo base_url().getThemeName();?>/assets/img/icon2.png" />
                                                                                    <?php }elseif($ext == 'png' || $ext == 'jpe' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'bmp' || $ext == 'jpeg'){ ?>
                                                                                       <img src="<?php echo base_url().getThemeName();?>/assets/img/images.jpg" />
                                                                                    <?php }else { ?>
                                                                                       <img src="<?php echo base_url().getThemeName();?>/assets/img/document_icon.png" />
                                                                                    <?php }?>
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                <p class="txt-normal"><strong><a href="<?php echo $s3_display_url.'upload/task_project_files/'.$files['task_file_name'];?>" target="_blank"><?php echo ($files['file_title']!='')?$files['file_title']:$files['task_file_name'];?></a></strong></p>
                                                                                <p> by <?php echo $files['first_name']." ".$files['last_name']; ?> a <?php echo time_ago($files['file_date_added']);?>.  <?php if($chk['size'] < 1024){ echo $chk['size'].' bytes'; } else { echo round($chk['size']/1024).'KB'; } ?> </p>
                                                                            </td>
                                                                            <td width="15%">
                                                                                <a href="<?php echo $s3_display_url.'upload/task_project_files/'.$files['task_file_name'];?>" target="_blank"> <i class="stripicon icondownlaod" style="transform: scale(0.75); "></i> </a>
                                                                                <?php if($files['file_added_by']==get_authenticateUserID()){?>
                                                                                    <a onclick="setval('<?php echo $files['task_file_id'];?>');" data-toggle="modal" href="#task_file-replace" data-dismiss="modal" ><i class="stripicon iconrefresh" style="transform: scale(0.75); "></i></a>
                                                                                    <a href="javascript:;" onclick="delete_project_file('<?php echo $files['task_file_id'];?>')" id="project_file_<?php echo $files['task_file_id'];?>"> <i class="icon-trash tmsticn"></i></a>
                                                                                <?php }?>
                                                                            </td>
                                                                        </tr>

                                                                        <?php } } } } else { ?>
                                                                            <tr id="prj-n-file"><td colspan="3">Drag & drop your files here to upload.</td></tr>
                                                                            <?php } ?>

                                                        </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                        
                                        <!-- file section end -->
                                        
                                        <!-- project history start here-->
                                        
                                        <div class="row " style="margin-top: 10px; margin-bottom: 5px;" onclick="expand_project_data('3')">
                                            <div class="col-md-12 panel-heading_pro">
                                                <a href="javascript:void(0);"  >
                                                    <span id="expand_3"><i class="icon-chevron-right default_color" ></i></span>
                                                    <label class="control-label default_color" >History</label>
                                                </a>
                                                
                                                <input type="hidden" name="collapse3" id="collapse3" value="1"/>
                                            </div>
                                        </div>

                                        <div id="project_history" style="display:none">
                                            <div class="row" style="padding-top: 0px;">
                                                    <div class="col-md-12" style="padding-left:10px;">
                                                   
<!--                                                            <label class="bld"> History </label>-->
                                                                    <div class="portlet-body  form flip-scroll">
                                                                            <div class="form-horizontal" id="CommentsList">
                                                                                    <?php  $this->load->view(getThemename().'/layout/project/listComments_Ajax',TRUE); ?>
                                                                            </div>
                                                                            <!-- Display more button-->
                                                                            <div class="padding-bottom-15 center" id="moreDiv" style="<?php echo ($total_history<=$limit?'display:none':'') ?>">
                                                                                <input type="button"  alt="view-btn" class="btn sml blue txtbold br-radius " onclick="loadMore()" value="Load More">
                                                                            </div>
                                                                    </div>
                                                    </div>
                                            </div>
                                        </div>
                                        
                                        <!-- history end here-->

				</div>
			</div>
                    </div>
    </div>
</div>

 
<script type="text/javascript">
	function changeTaskStatus(task_id,task_subsection_id,task_section_id,scope_time_spent){
                       
		if($("#pr_task_status_"+task_id).prop("checked") == true){
                                             
			var status_id = '<?php echo $task_status_completed_id;?>';
                        var timer_status = 'stop';
                  
			if('<?php echo $actaul_time_on; ?>' == "1" && getCookie('timer_task_id') != task_id){
                                            
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
		} else {
			var status_id = '<?php echo get_task_status_id_by_name("Ready");?>';
                        var timer_status = '';
		}
		if(task_id){
			var orig_data = $('#task_data_'+task_id).val();
			var id = $("#select_task_assign").val();
			var filter = $("#select_task_status").val();
			$.ajax({
				type : 'post',
				data : {task_id : task_id, status_id : status_id, post_data : orig_data, user_id:id, type:filter},
				url : '<?php echo site_url('project/changeTaskStatus');?>',
				success: function(data){
					App.init();
                                        if(timer_status == 'stop' && getCookie('timer_task_id') == task_id)
                                        {
                                           end_task_timer();
                                        }
                                        
					if(data){
						if($("#task_tasksort_"+task_id).length) {
							$("#task_tasksort_"+task_id).replaceWith(data);
						}
					} else {
						$("#task_tasksort_"+task_id).remove();
					}
                                        

					orig_data = jQuery.parseJSON(orig_data);

					if(orig_data.master_task_id !='0'){
						$.ajax({
							type : 'post',
							url : '<?php echo site_url('project/next_noncompleted_recurrence');?>',
							data : {task_id : orig_data.master_task_id},
							success : function(task_detal2){

								if(task_detal2){

									task_detal2 = jQuery.parseJSON(task_detal2);
									var id = $("#select_task_assign").val();
                                                                        var filter = $("#select_task_status").val();

									$.ajax({
										type : 'post',
										url : '<?php echo site_url('project/set_update_task');?>',
										data : {task_id : task_detal2.task_id,type:filter,user_id:id},
										success : function(taskData){

											App.init();
											if(taskData){
		           								if($("#task_tasksort_"+task_id).length) {
		           									$.ajax({
														type : 'post',
														url : '<?php echo site_url('project/set_update_task');?>',
														data : {task_id : task_id,type:filter,user_id:id},
														success : function(taskData2){
															$("#task_tasksort_"+task_id).replaceWith(taskData2);
														}
													});
		           								}

		           								if(task_section_id !='0'){
													$("#taskmove_"+task_subsection_id+"_"+task_section_id).append(taskData);
												} else {
													$("#panel-body1_"+task_subsection_id+" div.add_new_task_div").before(taskData);
												}


	           								} else {
	           									$("#task_tasksort_"+task_id).remove();
	           								}
										}
									});
								}
								$('#typefilter1 li').removeClass('active');
						 		$('#typefilter1 li[id='+filter+']').addClass('active');
							}
						});
					}
				}
			});
		}
	}

	$(document).ready(function(){
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
					if(validate(val) == true) 
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
				var orig_data = $('#task_data_'+task_actual_time_task_id).val();
				var id = $("#select_task_assign").val();
                                var filter = $("#select_task_status").val();
				$.ajax({
					type : 'post',
					url : '<?php echo site_url("project/add_actual_time");?>',
					data : {str : $("#frm_actual_time").serialize(), user_id:id, type:filter},
					success : function(data){

						$("#task_tasksort_"+task_actual_time_task_id).replaceWith(data);

						$("#actual_time_task").modal("hide");


						orig_data = jQuery.parseJSON(orig_data);

						if(orig_data.master_task_id !='0'){
							$.ajax({
								type : 'post',
								url : '<?php echo site_url('project/next_noncompleted_recurrence');?>',
								data : {task_id : orig_data.master_task_id},
								success : function(task_detal2){

									if(task_detal2){

										task_detal2 = jQuery.parseJSON(task_detal2);
										var id = $("#select_task_assign").val();
                                                                                var filter = $("#select_task_status").val();

										$.ajax({
											type : 'post',
											url : '<?php echo site_url('project/set_update_task');?>',
											data : {task_id : task_detal2.task_id,type:filter,user_id:id},
											success : function(taskData){

												App.init();
												if(taskData){
			           								if($("#task_tasksort_"+task_actual_time_task_id).length) {
			           									$.ajax({
															type : 'post',
															url : '<?php echo site_url('project/set_update_task');?>',
															data : {task_id : task_actual_time_task_id,type:filter,user_id:id},
															success :function(taskData2){
																$("#task_tasksort_"+task_actual_time_task_id).replaceWith(taskData2);
															}
														});
			           								}

			           								if(orig_data.section_id !='0'){
														$("#taskmove_"+orig_data.subsection_id+"_"+orig_data.section_id).append(taskData);
													} else {
														$("#panel-body1_"+orig_data.subsection_id+" div.add_new_task_div").before(taskData);
													}


		           								} else {
		           									$("#task_tasksort_"+task_actual_time_task_id).remove();
		           								}
											}
										});
									}
									$('#typefilter1 li').removeClass('active');
							 		$('#typefilter1 li[id='+filter+']').addClass('active');
								}
							});
						}

					}
				});
			}
		});

		$(".close_actual_time_task").click(function(){
			var task_actual_time_task_id = $("#task_actual_time_task_id").val();
			$("#task_tasksort_"+task_actual_time_task_id).find("input[type='checkbox']").prop('checked',false);
			$("#task_tasksort_"+task_actual_time_task_id).find("span").removeClass('checked');
			$("#actual_time_task").modal("hide");
		});
	});

</script>



        
        
<div id="users_list" class="modal model-size pro-change fade" tabindex="-1" >
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				<div class="modal-header">
					<button type="button" class="close cmt_close" data-dismiss="modal" aria-hidden="true"></button>
					<h3>Users</h3>
				</div>
				<div class="modal-body">
					<form name="users" id="users" action="">
                                                <div class="addcomment-block" style="padding:15px !important">
							<div class="row">
								<div class="col-md-12 ">
									<div id="replacemem" class="form-group">
									</div>
									<div class="col-md-12 paddTop20 pull-left">
										<input type="hidden" class = "main_project" name="project_id" id="project_id" value="<?php echo $project_id;  ?>" />
                                                                                <button type="submit" class="sm btn blue txtbold"> Add User </button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
</div>  

<div id="task_file-replace" class="modal replace-file fade" tabindex="-1">
    <div class="portlet">
        <div class="portlet-body  form ">
            <div class="modal-header">
                <button type="button" class="close cmt_close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>Replace files</h3>
            </div>
            <div>
                <div class="addcomment-block">
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label class="control-label_custome">Add File : <span class="required">*</span></label>
                                <div class="controls margin-left-10">
                                    <div class="fileupload fileupload-new task-file-dv" data-provides="fileupload">
                                        <form name="frm_project_files_replace" id="frm_project_files_replace" enctype="multipart/form-data">
                                            <div class="input-append">
                                                <div class="uneditable-input"  style="display: none;">
                                                    <i id="icon_r" class="icon-file fileupload-exists"></i>
                                                    <span class="fileupload-preview"></span>
                                                </div>
                                                <span class="btn blue btn-file browse-btn" onclick="replacebrowseClicked();">
                                                    <span id="browse_r" class="fileupload-new">Browse</span>
                                                    <span id="change_r" class="fileupload-exists">Change</span>
                                                    <input type="file" name="project_file_replace" id="project_file_replace" class="default" />
                                                </span>
                                            </div>
                                            <input type="hidden" name="task_file_id" id="task_file_id" value="<?php echo $files['task_file_id'];?>"  />
                                            <input type="hidden" class = "main_project" name="project_id" id="project_id" value="<?php echo $project_id; ?>"  />
                                            <input type="hidden" id="rep_fil" name="rep_fil" value="" >
                                        </form>
                                    </div>
                                    <span>OR</span>
                                    <div class="btn blue link-btn" onclick="replacelinkClicked();">Link</div>
                                    <form class="frm_upload_link col-md-12" name="frm_replace_upload_link" id="frm_replace_upload_link" enctype="multipart/form-data">
                                        <input type="text" name="replace_file_name" id="replace_file_name" value="" class="m-wrap" placeholder="File Name" tabindex="1">
                                        <input type="text"  name="replace_file_link" id="replace_file_link" value="" class="m-wrap" placeholder="File Link" tabindex="1">
                                        <input type="hidden" name="task_file_id" id="task_file_id" value="<?php echo $files['task_file_id'];?>"  />
                                        <input type="hidden" class = "main_project" name="project_id" id="project_id" value="<?php echo $project_id;?>"  />
                                        <input type="hidden" id="tab" name="tab" value="tab_3" >
                                        <input type="hidden" id="rep_fil_link" name="rep_fil" value="" >
                                        <button type="button" class="btn blue" id="replace-upload-link-btn">Add Link</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--project completion model-->


<div id="complete_task" class="modal project_complete_task_popup fade" tabindex="-1"  >
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				<div class="modal-header">
					<button type="button" class="close status_close" data-dismiss="modal" aria-hidden="true"></button>
					<h3>Task</h3>
				</div>
				<div>
						<div class="addcomment-block">
							<div class="row">
								<div class="col-md-12 ">
									<div class="form-group">
                                                                                <label class="control-label col-md-3" style="padding-top: 5px;"> <strong> Action : <span class="required">*</span></strong></label>
										<div class="controls">
											<select class="m-wrap col-md-6 radius-b" id="task_status" name="task_status" tabindex="1" >
												<option value="">-- Select --</option>
												<option value="Unlink" >Unlink tasks from the project</option>
												<option value="Close" >Close open tasks</option>
												<option value="Keep" >Keep tasks open</option>
											</select>
										</div>
									</div>
                                                                        <div class="pull-right col-md-4" style="margin-top:13px;">
										<button type="button" id='complete_task_tab' name="complete_task_tab" class="btn blue txtbold"> Submit </button>
									</div>
								</div>
							 </div>
						</div>
				</div>
			</div>
		</div>
</div>

<!--Model popup for getting time when user click on checkbox for task completion.-->

<div id="actual_time_task" class="modal project_actual_time_popup fade customecontainer" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close close_actual_time_task" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Actual time of task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
				<div class="portlet-body  form flip-scroll">
					<form name="frm_actual_time" id="frm_actual_time" method="post">
                                                <div class="form-group col-md-12" style="margin-top: 10px;">
                                                    <label class="control-label col-md-4" style="margin-top: 6px;padding-left: 13px !important;">Enter Actual Time : </label>
							<div class="controls col-md-8">
								<input class="onsub m-wrap m-ctrl-small small_input" name="task_actual_time" id="task_actual_time" placeholder="0h" value="" type="text"  tabindex="1" /><span class="word_set">time(ex. 130 for 1h30)</span>
								<input type="hidden" name="task_actual_time_hour" id="task_actual_time_hour" value="" />
								<input type="hidden" name="task_actual_time_min" id="task_actual_time_min" value="" />
							</div>
						</div>
                                                <div class="col-md-12" style="margin:8px;">
							<div class="col-md-6">
								<input type="hidden" name="task_id" id="task_actual_time_task_id" value="" />
								<input type="hidden" name="task_data" id="task_actual_time_task_data" value="" />
								<button type="submit" class="btn blue txtbold"> Save </button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
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
                                                                        <button  onclick="return filter_log();"  class="btn blue txtbold" style="margin-left: 15px;"> Search </button>
                                                                        <button  onclick="get_work_log();" type="button" class="btn blue txtbold" style="margin-left: 25px;"> Reset </button>
									
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
    <?php   $this->load->view($theme.'/layout/common/ajax_statistics.php'); ?>
</div>
<div id="delete_task" class="modal model-size pro-change fade" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Delete Task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
				<div class="portlet-body  form flip-scroll">

                                    <div class="form-group" style="padding:10px">
                                        <label class="control-label col-md-12" style="padding-left:0px;">Do you want to delete the series, this occurence or only future tasks?</label>
						<label class="control-label">Select :</label>
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

 <script>
                var TOTAL_TIME = 0;
  		$(document).ready(function(){
                        $(function(){
				$('.scroll_log').slimScroll({
				color: '#000',
				height : '500px',
                                wheelStep: 100

			 });
                     });
  			$(window).bind('beforeunload', function(){
  				if($("#is_timer_popup").val() == '1'){
					return 'Are you sure, you want to leave this page? Data you have entered may not be save.';
				}
			});
			$(function(){
				$('.reason_scroll').slimScroll({
				color: '#000',
				height : '200px',
		 	    wheelStep: 100

			 });

			});
                });
   
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
                     }
                    else
                    {
                        $('#work_log_filter').validate({
                        errorElement: 'span', 
                        errorClass: 'help-inline', 
                        focusInvalid: true, 

                        ignore: "",
                                    rules : {
                                            "to_date" : {
                                                    greaterThan : true
                                            }
                                    },
                            errorPlacement: function (error, element) {
                                            if (element.attr("name") == "from_date" || element.attr("name") == "to_date" ) { 
                                error.appendTo( element.parent("div") );
                            } else {
                                error.insertAfter(element); 
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
<script type="text/javascript">
        $(document).ready(function(){
            inlin_edit();
             trunc_task_title();
                $(document).on("click","#finance_refresh",function(){
                    $('#dvLoading').fadeIn('slow');
                    $.ajax({
			type : 'post',
			url : '<?php echo site_url("project/update_project_finance_view");?>',
			data :{ project_id : $("#check_project_id").val()},
			success : function(data){ 
				$("#project_finance_info").html(data);
				$('#dvLoading').fadeOut('slow');
                        },
                        error:function(data){
                            console.log("Ajax request not received.");
                            $('#dvLoading').fadeOut('slow');
                        }
			});
                });
                
                $('#edit_project_charge_rate').editable({
                    url: SIDE_URL + "project/update_project_rate",
                    params:{project_id : $("#check_project_id").val()},
                    type: "post",
                    pk: 1,
                    mode: "popup",
                    showbuttons: !0,
                    validate: function(e) {
                            var s = /^[0-9 .]*$/;
                            return s.test($.trim(e)) ? void 0 : "Please enter only number."
                    },
                    success: function() {}  
                });      
                
                $('#edit_project_fixed_charge_rate').editable({
                    url: SIDE_URL + "project/update_project_rate",
                    params:{project_id : $("#check_project_id").val()},
                    type: "post",
                    pk: 1,
                    mode: "popup",
                    showbuttons: !0,
                    validate: function(e) {
                            var s = /^[0-9 ]*$/;
                            return s.test($.trim(e)) ? void 0 : "Please enter only number."
                    },
                    success: function() {}  
                }); 
                $('.changesecname').editable({
                    url: SIDE_URL + "project/update_sectionName",
                    params:function(params){
                          var data = {};
                                   data['section_id']=params.pk;
                                  data['project_id']=$(this).attr("sec_prj_id");
                                  data['section_name']=params.value;
                                  data['type']=$(this).attr("sec_type");
                                  data['user_id']=$("#select_task_assign").val();
                                  data['filter']= $("#select_task_status").val();
                          return data;
                 
                    },
                    type: "text",
                    mode: "inline",
                    inputclass:"changesectionname",
                    showbuttons: !0,
                    validate: function(e) {
                    },
                    success: function() {}  
                }); 
                
                $(document).on("click","#new_project_owner",function(){
                        var is_admin = '';
                        if($(this).is(':checked')){
                            is_admin = '1';   
                        }else{
                            is_admin = '0';
                        }
                        
                        $.ajax({
                            type : 'post',
                            url : '<?php echo site_url("project/set_as_project_admin");?>',
                            data :{ 
                                project_id : $("#check_project_id").val(),
                                is_admin : is_admin,
                                user_info : $(this).val()
                            },
                            success : function(data){ 
                            },
                            error:function(data){
                                console.log("Ajax request not received.");
                                
                            }
			});
                        
                });
        });
</script>

<script>
  $(".panel-heading").on('click',exp_col);
  function exp_col(){
  $(this).siblings(".panel-body").toggle();
  $(this).find('.expand_sections i').toggleClass("icon-chevron-down");
   $(this).find('.expand_sections i').toggleClass("icon-chevron-right");

  }
 var PROJECT_CUSTOMER_ID ='<?php echo $customer_id?>';
 var sttsar=<?php echo json_encode($task_status);?>;
 var tsk_cmpltd_id=<?=$completed_id?>;
      function inlin_edit()
      { 
          var p_id=$("#check_project_id").val();
           var jsonObj=[];
        for (var key in sttsar) {
                        if (sttsar.hasOwnProperty(key)) {
                          var val = sttsar[key];
                           var opt={};
                        opt['value']=val['task_status_id'];
                          opt['text']=val['task_status_name'];
                         jsonObj.push(opt);       
                        }
                    }
    
         $('.task_status_editable').editable({
                    url: SIDE_URL + "task/saveTask ",
                    params: function(params) {
                                                                        var data = {};
                                                                          
                                                                          if(tsk_cmpltd_id==params.value){
                                                                              $("#pr_task_status_"+params.pk).trigger("click");
                                                                              if($("#task_actual_time").val()>0){
                                                                                  data['name'] ='task_status_id';
                                                                                   data['value'] = params.value;
                                                                              }
                                                                               
                                                                          }else{
                                                                              data['name'] ='task_status_id';
                                                                                 data['value'] = params.value;
                                                                          }
                                                                      
                                                                        data['task_id'] = params.pk;
                                                                        data['redirect_page']= $("#redirect_page").val();
                                                                       return data;
                                                                    },
                    type: "post",
                    mode: "inline",
                    inputclass: 'inline_task_stts_r',
                    source:jsonObj,
                    showbuttons:false,
                    success: function() {}  
                }); 
               
                
                $('.task_allocated_user_editable').editable({
                  
                   url: SIDE_URL + "task/saveTask ",
                   source:function(){
                        var jsonrtrn='';
                       $.ajax({
                           url:"<?=base_url()?>project/fetch_member_list/"+p_id,
                           type: 'GET',
                            global: false,
                            async: false,
                            dataType: 'json',
                            success: function (ars) { 
                                jsonrtrn=ars;
                                          
                            }
                       });
                 return jsonrtrn;
                   },
                    params: function(params) {
                                                                        var data = {};
                                                                          data['name'] ='task_allocated_user_id';
                                                                        data['value'] = params.value;
                                                                        data['task_id'] = params.pk;
                                                                        data['redirect_page']= $("#redirect_page").val();
                                                                       return data;
                                                                    },
                    type: "post",
                    mode: "inline",
                    inputclass: 'inline_task_allcted_r',
                     showbuttons:false,
                    success: function() {}  
                    
                }); 
                var tskdue = {};
                $('.task_due_date_editable').editable({
                         url: SIDE_URL + "task/saveTask ",
                          params: function(params) {
                                                                        
                                                                          tskdue['name'] ='task_due_date';
                                                                        tskdue['value'] = params.value;
                                                                        tskdue['task_id'] = params.pk;
                                                                        tskdue['redirect_page']= $("#redirect_page").val();
                                                                       return tskdue;
                                                                    },
                            inputclass : "cstmdatepick",
                            mode:"popup",
                             datepicker: {
                                        weekStart: 1
                                   }, 
                            showbuttons:false,
                            placement: function (context, source) {
                              var popupHeight = 340;
                              if(($(window).scrollTop() + popupHeight) > $(source).offset().top){
                                return "right";
                              } else {
                                return "top";
                              }
                          },
                              success:function(){
                                    var g = $("#select_task_assign").val();
                                      var h = $("#select_task_status").val();
                                   $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    data: {
                                        task_id: tskdue['task_id'],
                                        redirect_page : $("#redirect_page").val(),
	                        type: h,
                                        user_id: g
                                        },
                                    success: function(taskdiv) {
                                        App.init(), $("#task_tasksort_" + tskdue['task_id']).replaceWith(taskdiv)
                                    },
                                    error: function(e) {
                                        console.log("Ajax request not recieved!")
                                    }
                            });
                              }
                      
                      });
                     
      }
      function trunc_task_title(){
     
                        $(".cst_ul").each(function(){
                        
                                var ulWidth = 0;
                                 var li_count=0;
                                        $(this).children('li').each(function() {
                                                 ulWidth = ulWidth + $(this).width();
                                                 li_count++;
                                            }); 
                                              
                          if('750'<= ulWidth){ 
                            
                                    var owidth=300;
                                    var nwidth =owidth-50*(li_count - 6);
                                        $(this).addClass("large-task-title"); 
                                       
                                        
                                      $(this).children('li:nth-child(3)').find('a').css({'width' :nwidth+'px','display':'inline-block' , 'white-space': 'nowrap',  'overflow': 'hidden','text-overflow': 'ellipsis'});
                                  }
                    }); 

      }
     
</script>
