function add_task_title(dayid,task_create_date)
{
    $("#task_allocated_user_id").val(LOG_USER_ID);
    var a = $("#task_project_id").val() || $("#general_project_id").val(),
        b = $("#section_id").val() || $("#task_section_id").val();
        a = a || 0;
    var c = $("#task_category_id").val(),
        d = $("#task_sub_category_id").val() || 0,
        e = $("#task_allocated_user_id").val(),
        f = $("#task_swimlane_id").val() || $("#genral_swimlane_id").val();
    var redirect_page = $("#redirect_page").val()
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/add_task_ajax",
        data: {
            project_id: a,
            section_id: b,
            parent_id: c,
            sub_id: d,
            user_id: e,
            swimlane_id: f
        },
        async: !1,
        success: function(data) {
            var b = jQuery.parseJSON(data);
            "all" == $("#calender_project_id").val()  && ($("#general_project_id").val(0), $("#task_project_id").val(0), $("#task_subsection_id").val(0)),
            "all" != $("#calender_project_id").val() && void 0 != $("#calender_project_id").val() && ($("#general_project_id").val($("#calender_project_id").val()), $("#task_project_id").val($("#calender_project_id").val()), $("#task_subsection_id").val($("#subsection_" + $("#calender_project_id").val()).val()));
            if($("#general_project_id").val()!='0'){
            $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/get_customer_id_by_project_id",
                    data: {
                        project_id:$("#general_project_id").val(),
                    },
                    async: !1,
                    success: function(a) {
                        $("#allocated_customer_id").val(a);
                    },
                    error:function(a){
                        console.log("ajax request not recived.")
                    }
                })
            }else{
                $("#allocated_customer_id").val('');
            }   
            if(b.is_customer_user == 1){
                 $("#allocated_customer_id").val(b.user_customer_id);
            }
            var input='<div id="add_task_new_'+dayid+'" ><input type="hidden" name="task_input_data_" id="task_input_data_'+dayid+'" value="task_priority=None&task_owner_id='+LOG_USER_ID+'&hdn_locked_due_date=0&hdn_is_personal=0&task_time_estimate_hour=0&task_time_estimate_min=0&old_task_time_estimate_min=0&task_time_spent_hour=0&task_time_spent_min=0&old_task_time_spent_hour=0&old_task_time_spent_min=0&tmp_task_due_date='+task_create_date+'&old_task_due_date='+task_create_date+'&old_task_status_id=&task_scheduled_date='+task_create_date+'&task_orig_scheduled_date=&task_orig_due_date=&redirect_page='+redirect_page+'&kanban_order=&calender_order=&genral_swimlane_id='+DEFAULT_SWIMLANE+'&master_task_id=0&strtotime_scheduled_date='+dayid+'&task_id=&old_task_id=&from=&task_subsection_id='+ $("#task_subsection_id").val()+'&task_section_id=0&general_project_id='+$("#general_project_id").val()+'&allocated_customer_id='+$("#allocated_customer_id").val()+'">';
             input += '<input type="text" class="m-wrap add_task_new" name="task_title"  id="task_input_'+dayid+'" onkeydown="Javascript: if (event.keyCode==13||event.keyCode==9) task_add_event(this.id,this.value,event.keyCode);" style="padding-top:0px;"/>';
             input += "</div>";
            
           // $("#icon_addTask_"+dayid).css("display","none");
	$("#icon_addTask_"+dayid).replaceWith(input);
        $("#task_input_"+dayid).focus();
           
        }
    });
   
}

function add_task_kanban(swimlane_id,status_id)
{
    
    var a = $("#task_project_id").val() || $("#general_project_id").val(),
        b = $("#section_id").val() || $("#task_section_id").val();
    a = a || 0;
    var c = $("#task_category_id").val(),
        d = $("#task_sub_category_id").val() || 0,
        e = $("#task_allocated_user_id").val(),
        f = $("#task_swimlane_id").val() || $("#genral_swimlane_id").val();
    var redirect_page = $("#redirect_page").val()
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/add_task_ajax",
        data: {
            project_id: a,
            section_id: b,
            parent_id: c,
            sub_id: d,
            user_id: e,
            swimlane_id: f
        },
        async: !1,
        success: function(b) {
                                                                                                                                    
            b = jQuery.parseJSON(b);
            "all" == $("#kanban_project_id").val()  && ($("#general_project_id").val(0), $("#task_project_id").val(0), $("#task_subsection_id").val(0)),
            "all" != $("#kanban_project_id").val() && void 0 != $("#kanban_project_id").val() && ($("#general_project_id").val($("#kanban_project_id").val()), $("#task_project_id").val($("#kanban_project_id").val()), $("#task_subsection_id").val($("#subsection_" + $("#kanban_project_id").val()).val()));
            
            if($("#general_project_id").val()!='0'){
                $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/get_customer_id_by_project_id",
                        data: {
                            project_id:$("#general_project_id").val(),
                        },
                        async: !1,
                        success: function(a) {
                            $("#allocated_customer_id").val(a);
                        },
                        error:function(a){
                            console.log("ajax request not recived.")
                        }
                    })
            }else{
                $("#allocated_customer_id").val('');
            }   
            
            if(b.is_customer_user == 1){
                 $("#allocated_customer_id").val(b.user_customer_id);
            }
            var input ='<div id="add_task_new_'+swimlane_id+'_'+status_id+'" ><input type="hidden" name="task_input_data_" id="task_input_data_'+swimlane_id+'_'+status_id+'" value="task_priority=None&task_owner_id='+LOG_USER_ID+'&hdn_locked_due_date=&hdn_is_personal=0&task_time_estimate_hour=0&task_time_estimate_min=0&old_task_time_estimate_min=0&task_time_spent_hour=0&task_time_spent_min=0&old_task_time_spent_hour=0&old_task_time_spent_min=0&tmp_task_due_date=&old_task_due_date=&old_task_status_id='+status_id+'&task_scheduled_date=&task_orig_scheduled_date=&task_orig_due_date=&redirect_page='+redirect_page+'&kanban_order=&calender_order=&genral_swimlane_id='+swimlane_id+'&master_task_id=0&strtotime_scheduled_date=&task_id=&old_task_id=&from=&task_subsection_id='+$("#task_subsection_id").val()+'&task_section_id=0&general_project_id='+$("#general_project_id").val()+'&allocated_customer_id='+$("#allocated_customer_id").val()+'">';
                input += '<input type="text" class="m-wrap add_task_new" name="task_title"  id="task_input_'+swimlane_id+'_'+status_id+'" onkeydown="Javascript: if (event.keyCode==13||event.keyCode==9) task_add_event(this.id,this.value,event.keyCode)"/></div>';
            $("#icon_addTask_"+swimlane_id+'_'+status_id).replaceWith(input);
            $("#task_input_"+swimlane_id+'_'+status_id).focus();
        }
    });
   
}
function task_add_event(id,title,eventkey) 
{
    
    var t = $("#redirect_page").val();
    title =  encodeURIComponent(title);
    if(title != '' && title != "0")
    {
        if(t == 'from_kanban')
        {
            var id1=id.replace('task_input_','');
            var task_data = $('#task_input_data_'+id1).val();
            var datastring='task_title='+title+'&'+task_data;
            var j=id1.split("_");
            if(j) {
                var swimlane_id = j[0],
                 status_id = j[1];
            }else{ 
                var swimlane_id = "0",
                 status_id = "0";
            }
            $("#"+id).blur();
             $.ajax({
                            type: "post",
                            url: SIDE_URL + "task/saveTask",
                            data: {
                                name: "task_title",
                                value: datastring,
                                task_id:'' ,
                                task_scheduled_date: $("#task_scheduled_date").val(),
                                redirect_page: $("#redirect_page").val(),
                                
                            },
                            async: !1,
                            success: function(a) {
                               
                                if(eventkey == 9)
                                {
                                    $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "kanban/set_update_task",
                                    data: {
                                        task_id: a,
                                        start_date: $("#week_start_date").val(),
                                        end_date: $("#week_end_date").val(),
                                        action: $("#week_action").val(),
                                        active_menu: $("#redirect_page").val(),
                                        color_menu :$("#kanban_color_menu").val()
                                    },
                                    async: !1,
                                    success: function(taskdiv) { 
                                         $("#task_status_" + status_id + "_" + swimlane_id).prepend(taskdiv);
                                    }
                                });

                                }else
                                {
                                    edit_task(this,a,'');
                                }
                            }
                        })
            
        }
        else if(t == 'weekView' || 'NextFiveDay')
        {
            var dayid=id.replace('task_input_','');
            var task_data = $('#task_input_data_'+dayid).val();
            var datastring='task_title='+title+'&'+task_data;
            $("#"+id).blur();
            $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/saveTask",
                        data: {
                            name: "task_title",
                            value: datastring,
                            task_id:'' ,
                            task_scheduled_date: $("#task_scheduled_date").val(),
                            redirect_page: $("#redirect_page").val()
                        },
                        async: !1,
                        success: function(a) {
                            //$("#task_id").val(a), $("#allocation_task_id").val(a), $("#pre_task_id").val(a), $("#step_task_id").val(a), $("#files_task_id").val(a), $("#link_files_task_id").val(a), $("#comment_task_id").val(a), $("#freq_task_id").val(a), $("#search_task_id").val(a), $("#main_task_due_date").val($("#tmp_task_due_date").val())
                        if(eventkey == 9)
                        {
                             
                        $.ajax({
                            type: "post",
                            url: SIDE_URL + "calendar/set_weekly_update_task",
                            data: {
                                task_id: a,
                                start_date: $("#week_start_date").val(),
                                end_date: $("#week_end_date").val(),
                                action: $("#week_action").val(),
                                active_menu: $("#redirect_page").val(),
                                color_menu :$("#task_color_menu").val()
                            },
                            async: !1,
                            success: function(taskdiv) { 
                                if($("#other_user_task").is(":checked")){ 
                                    if(($("#task_allocated_user_id").val() == $("#calender_team_user_id").val()) && $("#week_" + dayid).find("#divide_"+dayid).length){
                                        $("#week_" + dayid).find("#divide_"+dayid).before(taskdiv);
                                    }else{
                                        $("#week_" + dayid).find("#add_newTask_"+dayid).before(taskdiv);
                                    }
                                }else{
                                    $("#week_" + dayid).find("#add_newTask_"+dayid).before(taskdiv);
                                }   
                               // $('#'+id).remove();
                            }
                        });
                        
                        }
                        else
                        {
                            edit_task(this,a,'');
                        }
                        }
                  })
       }
    }
    else
    alertify.alert("Please Enter Task title!");
}

function add_task_ajax() {
    var a = $("#task_project_id").val() || $("#general_project_id").val(),
        b = $("#section_id").val() || $("#task_section_id").val();
    a = a || 0;
    var c = $("#task_category_id").val(),
        d = $("#task_sub_category_id").val() || 0,
        e = $("#task_allocated_user_id").val(),
        f = $("#task_swimlane_id").val() || $("#genral_swimlane_id").val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/add_task_ajax",
        data: {
            project_id: a,
            section_id: b,
            parent_id: c,
            sub_id: d,
            user_id: e,
            swimlane_id: f
        },
        async: !1,
        success: function(b) { 
            b = jQuery.parseJSON(b);
            if(a!='0'){
                $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/get_customer_id_by_project_id",
                        data: {
                            project_id:$("#general_project_id").val(),
                        },
                        async: !1,
                        success: function(a) {
                            $("#allocated_customer_id").val(a);
                            $("#customer_id").find("option[value='"+a+"']").prop("selected", "selected");
                            $('#customer_id').trigger('chosen:updated');
                            $('#customer_id').prop('disabled', true).trigger("chosen:updated");
                        },
                        error:function(a){
                            console.log("ajax request not recived.")
                        }
                    })
            }else{
                if(b.is_customer_user == '1'){
                    $("#task_allocated_user_id").prop('disabled', true).trigger("chosen:updated"); 
                    $("#customer_id").prop('disabled', true).trigger("chosen:updated"); 
                }else{
                    $("#customer_id").prop('disabled', false).trigger("chosen:updated"); 
                    $("#task_allocated_user_id").prop('disabled', false).trigger("chosen:updated"); 
                }
            }
            
            var c = "";
            0 == a && (c += '<select class="col-md-11 m-wrap no-margin task-input allocation-change radius-b" name="section_id1" id="section_id1" tabindex="1" disabled="disabled" ><option value="">Project Section</option></select>'), "" != b.sections && (c += '<select class="col-md-11 m-wrap no-margin task-input radius-b" name="section_id1" id="section_id1" tabindex="1" >', $.map(b.sections, function(a) {
                c += b.section_id == a.section_id ? '<option value="' + a.section_id + '" selected="selected">' + a.section_name + "</option>" : '<option value="' + a.section_id + '" >' + a.section_name + "</option>"
            }), c += "</select>"), $("#section_div").html(c);
            var d = "";
            "" != b.sub_category ? (d += '<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_sub_category_id" id="task_sub_category_id" tabindex="5" >', $.map(b.sub_category, function(a) {
                d += b.sub_id == a.category_id ? '<option value="' + a.category_id + '" selected="selected">' + a.category_name + "</option>" : '<option value="' + a.category_id + '" >' + a.category_name + "</option>"
            }), d += "</select>") : ("1" == IS_ADMIN && "0" == b.is_sub_category_exist ? (d += '<div class="input-icon right">', d += '<i onclick="window.open("' + SIDE_URL + 'settings/index#company_setting_tab_4","_blank");"  class="stripicon help"></i>', d += '<input class="m-wrap col-md-11 " disabled="disabled" name="task_sub_category_id" value="Add Sub Category" type="text" placeholder="Add sub category" />', d += "</div>") : d += '<select class="col-md-11 radius-b m-wrap no-margin" disabled="disabled" name="task_sub_category_id" id="task_sub_category_id" tabindex="5"><option value="0" >Please select</option></select>', d += '<input type="hidden" name="task_sub_category_id" id="task_sub_category_id" value="0" />'), d += '<span class="input-load" id="task_sub_category_id_loading"></span>', $("#updated_subCategory").html(d);
            var e = "";
            if ("" != b.divisions) {
                e += '<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();">';
                var f = b.divisions.length;
                "1" == f && "General" == b.divisions[0].devision_title ? e += '<option value="' + b.divisions[0].division_id + '" selected="selected">' + b.divisions[0].devision_title + "</option>" : $.map(b.divisions, function(a) {
                    e += '<option value="' + a.division_id + '">' + a.devision_title + "</option>"
                }), e += "</select>"
            } else e += '<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" disabled="disabled" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();"></select>';
            e += '<span class="input-load" id="task_division_id_loading"></span>', $("#updatedDivision").html(e);
            var g = "";
            g += '<select class=" m-wrap no-margin col-md-11 task-input width350 radius-b" name="task_swimlane_id" id="task_swimlane_id" onchange="set_swimlane();" tabindex="1" ' + b.type + ">", "" != b.user_swimlanes && $.map(b.user_swimlanes, function(a) {
                g += b.swimlane_id == a.swimlanes_id ? a.swimlane_status == 'deactive' ? '<option value="' + a.swimlanes_id + '" selected="selected" disabled="disabled" >' + a.swimlanes_name + "</option>" :'<option value="' + a.swimlanes_id + '" selected="selected" >' + a.swimlanes_name + "</option>" : a.swimlane_status == 'deactive'?'<option value="' + a.swimlanes_id + '" disabled="disabled">' + a.swimlanes_name + "</option>":'<option value="' + a.swimlanes_id + '" >' + a.swimlanes_name + "</option>"
            }), g += "</select>", g += '<input type="hidden" name="task_swimlane_id" id="hdn_swimlane_id" value="' + b.swimlanes_id + '" />', $("#updated_user_swimlanes").html(g);
            var h = "",
                i = "",
                j = "";
            if ("0" != b.users) {
                var k = 0;
                $.map(b.users, function(a) {
                    k++, a.user_id == LOG_USER_ID ? (h += '<option value="' + a.user_id + '" selected="selected">' + a.first_name + " " + a.last_name + "</option>",i += '<option value="' + a.user_id + '" selected="selected">' + a.first_name + " " + a.last_name,i += a.is_customer_user==1?" (external)":"",i+=  "</option>") : (h += '<option value="' + a.user_id + '" >' + a.first_name + " " + a.last_name + "</option>",i += '<option value="' + a.user_id + '" >' + a.first_name + " " + a.last_name ,i += a.is_customer_user==1?" (external)":"",i += "</option>", j += '<li><input class="checkbox1" type="checkbox" name="task_allocated_user_id[]" value="' + a.user_id + '">' + a.first_name + " " + a.last_name + "</li>")
                }), k > 1 && (h += '<option value="multiple_people" id="multiple_people_id">Multiple People...</option>')
            }
            $("#updated_users").show(), $("#updated_users_multiple").hide(), $(".chk-container").html(j), App.init(), $("#task_allocated_user_id").html(h), $("#task_allocated_user_id").trigger("chosen:updated"), $("#depent_task_allocated_user_id").html(i), $("#depent_task_allocated_user_id").trigger("chosen:updated"), $("input[name='task_allocated_user_id[]']").on("change", function() {
                $("#is_multi_changed").val(1)
            }), $("#task_division_id").multiselect({
                buttonWidth: "349px"
            }), $("#task_division_id").multiselect("selectAll", !1), $("#task_division_id").multiselect("updateButtonText"), $(".task_multiselect").multiselect("refresh");
            var ss ='<option value="0">Please Select</option>';
            if("0" != b.project_list){
                $.map(b.project_list, function(a) {
                   b.project_id == a.project_id ? ss += '<option value="' + a.project_id + '" selected="selected">' + a.project_title + "</option>" :ss += '<option value="' + a.project_id + '" >' + a.project_title + "</option>";
                });
            }
            $("#task_project_id").html(ss);
        }
    })
    editor1()
}

function close_popup_general() {
    
    if ("" == $("#task_title").val()) return setTimeout(function() {
        $("#alertify").hide(), $("#alertify-cover").css("position", "relative")
    }, 1), $("#full-width").modal("hide"), !1
    $("#full-width").on('hidden.bs.modal', function(){
    $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
})
}

function multipleAllocation_tasks() {
    var a = [],
        b = [];
    if ($("input[name='task_allocated_user_id[]']:checkbox:checked").each(function(b) {
            a[b] = $(this).val()
        }), $("input[name='task_allocated_user_id[]']:checkbox:not(:checked)").each(function(a) {
            b[a] = $(this).val()
        }), "" != a) {
        var c = $("#task_swimlane_id").val() || $("#genral_swimlane_id").val();
        $.ajax({
            type: "post",
            url: SIDE_URL + "task/assign_task",
            data: {
                task_allocated_user_id: a,
                task_id: $("#task_id").val(),
                swimlane_id: c
            },
            async: !1,
            success: function(a) {
                var b = jQuery.parseJSON(a),
                    c = "";
                if ("" != b.divisions) {
                    c += '<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();">';
                    var d = b.divisions.length;
                    "1" == d && "General" == b.divisions[0].devision_title ? c += '<option value="' + b.divisions[0].division_id + '" selected="selected">' + b.divisions[0].devision_title + "</option>" : $.map(b.divisions, function(a) {
                        c += '<option value="' + a.division_id + '">' + a.devision_title + "</option>"
                    }), c += "</select>"
                } else c += '<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" disabled="disabled" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();"></select>';
                c += '<span class="input-load" id="task_division_id_loading"></span>', $("#updatedDivision").html(c);
                var e = "";
                e += '<select class=" m-wrap no-margin col-md-11 task-input width350 radius-b" name="task_swimlane_id" id="task_swimlane_id" tabindex="1" ' + b.type + ">", "" != b.user_swimlanes && $.map(b.user_swimlanes, function(a) {
                    e += b.swimlane_id == a.swimlanes_id ? '<option value="' + a.swimlanes_id + '" selected="selected">' + a.swimlanes_name + "</option>" : '<option value="' + a.swimlanes_id + '" >' + a.swimlanes_name + "</option>"
                }), e += "</select>",  e += '<input type="hidden" name="task_swimlane_id" id="hdn_swimlane_id" value="' + b.swimlanes_id + '" />', $("#updated_user_swimlanes").html(e), $("#task_division_id").multiselect({
                    buttonWidth: "349px"
                }), $("#task_division_id").multiselect("selectAll", !1), $("#task_division_id").multiselect("updateButtonText"), $(".task_multiselect").multiselect("refresh")
            }
        })
    }
    "" != b && $.ajax({
        type: "post",
        url: SIDE_URL + "task/unassign_task",
        data: {
            task_allocated_user_id: b,
            task_id: $("#task_id").val()
        },
        async: !1,
        success: function(a) {}
    });
    "" != a && $.ajax({
        type: "post",
        url: SIDE_URL + "task/send_allocation_mail",
        data: {
            task_allocated_user_id: a,
            task_id: $("#task_id").val()
        },
        async: 1,
        success: function(data) {}
    })
}

function chk_validation() {
    var a = $("#task_title").val();
    if (a) {
        var b = RegExp.prototype.test.bind(/(<([^>]+))/i),
            c = a.replace(/\s/g, "");
        if (c.length < 2) $("#alertify").show(), alertify.alert("Please add more than two character length of task title.", function(a) {
            return $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $(".task-tab-pane").removeClass("active"), $("#task_tab_1").addClass("active"), $("#task_title").focus(), $("#alertify").hide(), !1
        });
        else {
            if (!b(a)) return !0;
            $("#alertify").show(), alertify.alert("Please enter valid characters.", function(a) {
                return $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $(".task-tab-pane").removeClass("active"), $("#task_tab_1").addClass("active"), $("#task_title").focus(), $("#alertify").hide(), !1
            })
        }
    } else $("#alertify").show(), alertify.alert("Please insert task title to save task.", function(a) {
        return $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $(".task-tab-pane").removeClass("active"), $("#task_tab_1").addClass("active"), $("#task_title").focus(), $("#alertify").hide(), !1
    })
}

function chk_greater_zero(a) {
    return !a || 0 != a || ($("#alertify").show(), alertify.alert("Value must be greater than 0"), !1)
}

function validate(a) {
    var a = a.replace(":", ""),
        b = a.length;
    return b <= 5 && /^(([0-9\s\[\](\)\:\/\\(\/)\/)])+$)/.test(a)
}

function setAllocation(a) {
    var a = a || $("#task_allocated_user_id").val(),
        b = $("#task_allocated_user_id").val();
    $("#" + b + "_loading").show();
    var c = "task_allocated_user_id";
    if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var d = $("#frm_task_general").serialize();
    else var d = a;
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/saveTask",
        data: {
            name: c,
            value: d,
            task_id: $("#task_id").val(),
            task_scheduled_date: $("#task_scheduled_date").val(),
            redirect_page: $("#redirect_page").val()
        },
        async: !1,
        success: function(a) {
            return $("#task_id").val(a), $("#allocation_task_id").val(a), $("#pre_task_id").val(a), $("#step_task_id").val(a), $("#files_task_id").val(a), $("#link_files_task_id").val(a), $("#comment_task_id").val(a), $("#freq_task_id").val(a), $("#search_task_id").val(a), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("input[name='task_category_id']").is(":disabled") && $("#task_sub_category_id").attr("disabled", !0), 13 == $("#event").val() && ($(".save_close").trigger("click"), $("#event").val("")), $("#" + b + "_loading").hide(), !0
        }
    })
}

function setDivisionDepartment(a) {
    var a = a || "",
        b = $("#task_allocated_user_id").val();
    b && $.ajax({
        type: "post",
        url: SIDE_URL + "task/setDivisionDepartment",
        data: {
            user_id: b
        },
        async: !1,
        success: function(b) {
            if ($("#updatedDivision").html(b), $("#task_division_id").multiselect({
                    buttonWidth: "349px"
                }), $("#task_division_id").multiselect("selectAll", !1), $("#task_division_id").multiselect("updateButtonText"), $(".task_multiselect").multiselect("refresh"), $("#updatedDivision .task-input").change(function() {
                    var a = $(this).attr("id");
                    $("#" + a + "_loading").show();
                    var b = $(this).attr("name");
                    if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var c = $("#frm_task_general").serialize();
                    else var c = $(this).val();
                    $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/saveTask",
                        data: {
                            name: b,
                            value: c,
                            task_id: $("#task_id").val(),
                            task_scheduled_date: $("#task_scheduled_date").val(),
                            redirect_page: $("#redirect_page").val()
                        },
                        async: !1,
                        success: function(b) {
                            $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#" + a + "_loading").hide()
                        }
                    })
                }), "allocation" == a) {
                if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var c = $("#frm_task_general").serialize();
                else var c = $("#task_division_id").val();
                $("#task_division_id_loading").show(), $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: "task_division_id[]",
                        value: c,
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(a) {
                        $("#task_id").val(a), $("#allocation_task_id").val(a), $("#pre_task_id").val(a), $("#step_task_id").val(a), $("#files_task_id").val(a), $("#link_files_task_id").val(a), $("#comment_task_id").val(a), $("#freq_task_id").val(a), $("#search_task_id").val(a), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#task_division_id_loading").hide()
                    }
                })
            }
            get_department_by_division(a)
        }
    })
}

function chk_personal() {
    $("#is_personal").is(":checked") ? ($("#task_allocated_user_id").val(LOG_USER_ID), $("#task_allocated_user_id").trigger("chosen:updated"), $("#task_allocated_user_id").attr("readonly"), $("#task_owner_id_val").val(LOG_USER_NAME), $("#task_owner_id").val(LOG_USER_ID), $("#task_owner_id").attr("readonly"), $("#hdn_is_personal").val("1")) : ($("#task_allocated_user_id").val(""), $("#task_allocated_user_id").trigger("chosen:updated"), $("#hdn_is_personal").val(""))
}

function set_project_section() { 
    var a = [];
    $("input[name='task_allocated_user_id[]']:checkbox").each(function(b) {
        a[b] = $(this).val()
    }), "" != a && $.ajax({
        type: "post",
        url: SIDE_URL + "task/unassign_task",
        data: {
            task_allocated_user_id: a,
            task_id: $("#task_id").val()
        },
        async: !1,
        success: function(a) {}
    });
    var b = $("#task_project_id").val() || $("#general_project_id").val(),
        c = $("#section_id").val() || $("#task_section_id").val();
    b = b || 0, $.ajax({
        type: "post",
        url: SIDE_URL + "task/get_project_sections",
        data: {
            project_id: b,
            section_id: c
        },
        async: !1,
        success: function(a) {
            a = jQuery.parseJSON(a);
            var redirect_page = $("#redirect_page").val();
            var c = "";
            0 == b && (c += '<select class="col-md-11 m-wrap no-margin task-input allocation-change radius-b" name="section_id1" id="section_id1" tabindex="1" disabled="disabled" ><option value="">Project Section</option></select>'), "" != a.sections && (c += '<select class="col-md-11 m-wrap no-margin task-input radius-b" name="section_id1" id="section_id1" tabindex="1" >', $.map(a.sections, function(b) {
                c += a.section_id == b.section_id ? '<option value="' + b.section_id + '" selected="selected">' + b.section_name + "</option>" : '<option value="' + b.section_id + '" >' + b.section_name + "</option>"
            }), c += "</select>"), $("#section_div").html(c);
            var d = "",
                e = "",
                f = "";
            if ("0" != a.users) {
                var g = 0;
                $.map(a.users, function(a) {
                    var userID = LOG_USER_ID;
                    if((redirect_page == 'weekView' || redirect_page == 'NextFiveDay' || redirect_page == 'from_calendar') && $("#calender_team_user_id").val() !='#'){
                         userID = $("#calender_team_user_id").val();
                    }
                    g++, a.user_id == userID  ? (d += '<option value="' + a.user_id + '" selected="selected">' + a.first_name + " " + a.last_name + "</option>",e += '<option value="' + a.user_id + '" selected="selected">' + a.first_name + " " + a.last_name, e += a.is_customer_user==1?" (external)":"",e+= "</option>") : (d += '<option value="' + a.user_id + '" >' + a.first_name + " " + a.last_name + "</option>",e += '<option value="' + a.user_id + '" >' + a.first_name + " " + a.last_name, e += a.is_customer_user==1?" (external)":"",e+= "</option>", f += '<li><input class="checkbox1" type="checkbox" name="task_allocated_user_id[]" value="' + a.user_id + '">' + a.first_name + " " + a.last_name + "</li>")
                }),  g > 1 && (d += '<option value="multiple_people" id="multiple_people_id">Multiple People...</option>')
            }
            $("#updated_users").show(), $("#updated_users_multiple").hide(), $(".chk-container").html(f), App.init(), $("#task_allocated_user_id").html(d), $("#task_allocated_user_id").trigger("chosen:updated"), $("#depent_task_allocated_user_id").html(e), $("#depent_task_allocated_user_id").trigger("chosen:updated"), $("input[name='task_allocated_user_id[]']").on("change", function() {
                $("#is_multi_changed").val(1)
            });
            if(b == 0 && a.is_external_user == 1){
                $("#task_allocated_user_id").prop('disabled', true).trigger("chosen:updated");
            }else{
                $("#task_allocated_user_id").prop('disabled', false).trigger("chosen:updated");
            }
        }
    })
    
    b = b || 0, $.ajax({
        type: "post",
        url: SIDE_URL + "task/get_project_customer",
        data: {
            project_id: b,
            task_id: $("#task_id").val()
        },
        async: !1,
        success: function(a) {
            $("#customer_id").find("option[value='"+a+"']").prop("selected", "selected");
            $('#customer_id').trigger('chosen:updated');
            if(b != 0){
                $('#customer_id').prop('disabled', true).trigger("chosen:updated");
            }else{
               $('#customer_id').prop('disabled', false).trigger("chosen:updated"); 
            }
        },
        error:function(a){
            console.log("ajax request not recived.")
        }
    })
    
}

function setUserSwimlanes(a) {
    var a = a || "",
        b = $("#task_allocated_user_id").val(),
        c = $("#task_swimlane_id").val() || $("#genral_swimlane_id").val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/setUserSwimlanes",
        data: {
            user_id: b,
            swimlane_id: c
        },
        async: !1,
        success: function(b) {
            if ($("#updated_user_swimlanes").html(b), $("#updated_user_swimlanes .task-input").change(function() {
                    var a = $(this).attr("id");
                    $("#" + a + "_loading").show();
                    var b = $(this).attr("name");
                    if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var c = $("#frm_task_general").serialize();
                    else var c = $("#task_swimlane_id").val();
                    $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/saveTask",
                        data: {
                            name: b,
                            value: c,
                            task_id: $("#task_id").val(),
                            task_scheduled_date: $("#task_scheduled_date").val(),
                            redirect_page: $("#redirect_page").val()
                        },
                        async: !1,
                        success: function(b) {
                            $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#" + a + "_loading").hide()
                        }
                    })
                }), "allocation" == a) {
                if ($("#task_swimlane_id").val()) var c = $("#task_swimlane_id").val();
                else var c = $("#hdn_swimlane_id").val();
                if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var d = $("#frm_task_general").serialize();
                else var d = c;
                 $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: "task_swimlane_id",
                        value: d,
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(a) {
                        $("#task_id").val(a), $("#allocation_task_id").val(a), $("#pre_task_id").val(a), $("#step_task_id").val(a), $("#files_task_id").val(a), $("#link_files_task_id").val(a), $("#comment_task_id").val(a), $("#freq_task_id").val(a), $("#search_task_id").val(a), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1);
                    }
                })
            }
        }
    })
}

function setSubCategory() {
    var a = $("#task_category_id").val(),
        b = $("#task_sub_category_id").val() || 0;
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/setSubCategory",
        data: {
            parent_id: a,
            sub_id: b
        },
        async: !1,
        success: function(a) {
            $("#updated_subCategory").html(a)
        },
        error: function(a) {
            console.log("Ajax request not recieved!")
        }
    })
}

function get_department_by_division(a, b) {
    var c = "",
        d = a || $("#task_division_id").val();
    if (a && "allocation" == a) var c = "allocation",
        d = $("#task_division_id").val();
    var b = b || 0;
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/get_department_by_division",
        data: {
            division_id: d,
            dept_ids: b
        },
        async: !1,
        success: function(a) {
            if ($("#filtered_dep").html(a), $("#task_department_id").multiselect({
                    buttonWidth: "349px"
                }), $("#task_department_id").multiselect("selectAll", !1), $("#task_department_id").multiselect("updateButtonText"), $(".task_multiselect").multiselect("refresh"), "allocation" == c) {
                if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var b = $("#frm_task_general").serialize();
                else var b = $("#task_department_id").val();
                $("#task_department_id_loading").show(), $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: "task_department_id[]",
                        value: b,
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(a) {
                        $("#task_id").val(a), $("#allocation_task_id").val(a), $("#pre_task_id").val(a), $("#step_task_id").val(a), $("#files_task_id").val(a), $("#link_files_task_id").val(a), $("#comment_task_id").val(a), $("#freq_task_id").val(a), $("#search_task_id").val(a), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#task_department_id_loading").hide()
                    }
                })
            }
            $("#filtered_dep .task-input").change(function() {
                var a = $(this).attr("id");
                $("#" + a + "_loading").show();
                var b = $(this).attr("name");
                if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var c = $("#frm_task_general").serialize();
                else var c = $(this).val();
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: b,
                        value: c,
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(b) {
                        $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#" + a + "_loading").hide()
                    }
                })
            })
        },
        error: function(a) {
            console.log("Ajax request not recieved!")
        }
    })
}
function delete_task() { 
 
 if("series" == $("#from").val() && ('weekView'==$("#redirect_page").val()||'from_calendar' == $("#redirect_page").val() || 'NextFiveDay'== $("#redirect_page").val() || 'from_kanban' == $("#redirect_page").val())){
      $("#full-width").modal("hide");
      $("#full-width").on('hidden.bs.modal', function(){
    $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
})
      $("input[name='series_option']").parent().removeClass("checked");
      $("#series_task_deletion").modal("show");
    }
 else{ 
    var a = "Are you sure that you want to delete this task?";
   $('#delete_task_').confirmation('show').on('confirmed.bs.confirmation',function(){
            var b = $("#task_id").val();
            b && $.ajax({
                type: "post",
                url: SIDE_URL + "task/delete_task",
                data: {
                    task_id: b,
                    post_data: $("#task_data_" + b).val(),
                    from: $("#from").val()
                },
                async: !1,
                success: function(data) { 
                    var data = jQuery.parseJSON(data);
                    if ("series" == $("#from").val()) "from_kanban" == $("#redirect_page").val() ? $(".kanban_master_" + b).remove() : "from_customer" == $("#redirect_page").val()? $("tr[id^='listtask_child_"+b+"' ]").remove() : "weekView" == $("#redirect_page").val() || "NextFiveDay" == $("#redirect_page").val() ? $(".week_master_" + $("#task_id").val()).parent("div").map(function() {
                        var a = this.id,
                            b = $("#" + a).children("div").attr("id");
                        a = a.replace("week_", "");
                        var c = b.replace("main_", ""),
                            d = get_minutes($("#est_" + a).html()),
                            e = get_minutes($("#spent_" + a).html()),
                            f = $("#task_time_" + c).html();
                        if (f) var g = f.split("/"),
                            h = get_minutes(g[0]),
                            i = get_minutes(g[1]);
                        else var h = "0",
                            i = "0";
                        $("#" + b).remove();
                        var j = get_minutes($("#capacity_" + a).html()),
                            k = parseInt(d) - parseInt(h),
                            l = hoursminutes(k),
                            m = hoursminutes(parseInt(e) - parseInt(i));
                        k > j ? $("#est_" + a).addClass("red") : $("#est_" + a).removeClass("red"), $("#est_" + a).html(l), $("#spent_" + a).html(m)
                    }).get() : "from_calendar" == $("#redirect_page").val() || "FiveWeekView" == $("#redirect_page").val() ? $(".month_master_" + b).parent("div").map(function() {
                        var a = this.id,
                            b = $("#" + a).children("div").attr("id"),
                            c = b.replace("task_", ""),
                            d = get_minutes($("#estimate_time_" + a).html()),
                            e = $("#capacity_time_" + a).html(),
                            f = e.indexOf("h"),
                            g = e.substr(0, f),
                            h = $("#task_est_" + c).html();
                        if (h) var i = get_minutes(h);
                        else var i = "0";
                        var j = $("#task_type_" + c).val();
                        if ($("#" + b).remove(), 0 == $("#" + a + " .taskbox").length) $("#task_list_" + a).remove(), $("#task_info_" + a).remove();
                        else {
                            if (j)
                                if (task_type1 = j.split(","), "1" == task_type1[0]) {
                                    var k = $("#completed_" + a).html();
                                    if (k > 0 && $("#completed_" + a).html(parseInt(k) - 1), void 0 != task_type1[1]) {
                                        var l = $("#scheduled_" + a).html();
                                        l > 0 && $("#scheduled_" + a).html(parseInt(l) - 1)
                                    }
                                    if (void 0 != task_type1[2]) {
                                        var m = $("#due_" + a).html();
                                        m > 0 && $("#due_" + a).html(parseInt(m) - 1)
                                    }
                                } else if ("2" == task_type1[0]) {
                                var n = $("#overdued_" + a).html();
                                if (n > 0 && $("#overdued_" + a).html(parseInt(n) - 1), void 0 != task_type1[1]) {
                                    var l = $("#scheduled_" + a).html();
                                    l > 0 && $("#scheduled_" + a).html(parseInt(l) - 1)
                                }
                            } else {
                                if ("3" == task_type1[0]) {
                                    var l = $("#scheduled_" + a).html();
                                    l > 0 && $("#scheduled_" + a).html(parseInt(l) - 1)
                                }
                                if (void 0 != task_type1[1]) {
                                    var m = $("#due_" + a).html();
                                    m > 0 && $("#due_" + a).html(parseInt(m) - 1)
                                }
                            }
                            var o = parseInt(d) - parseInt(i),
                                p = hoursminutes(o);
                            $("#estimate_time_" + a).html(p), $("#estimate_time_" + a).removeAttr("class"), o > 60 * g ? $("#estimate_time_" + a).attr("class", "commonlabel redlabel") : $("#estimate_time_" + a).attr("class", "commonlabel")
                        }
                    }).get() : "from_dashboard" == $("#redirect_page").val() ? $(".dashboard_master_" + b).remove() : "from_teamdashboard" == $("#redirect_page").val() ? $(".teamdashboard_master_" + b).remove() : "from_project" == $("#redirect_page").val() && $(".project_master_task_" + b).remove();
                    else if ("from_calendar" == $("#redirect_page").val() || "FiveWeekView" == $("#redirect_page").val()) {
                        var c = $("#task_" + b).parent("div").attr("id"),
                            d = get_minutes($("#estimate_time_" + c).html()),
                            e = $("#capacity_time_" + c).html(),
                            f = e.indexOf("h"),
                            g = e.substr(0, f),
                            h = $("#task_est_" + b).html();
                        if (h) var i = get_minutes(h);
                        else var i = "0";
                        var j = $("#task_type_" + b).val();
                        if ($("#task_" + b).remove(), 0 == $("#" + c + " .taskbox").length) $("#task_list_" + c).remove(), $("#task_info_" + c).remove();
                        else {
                            if (j)
                                if (task_type1 = j.split(","), "1" == task_type1[0]) {
                                    var k = $("#completed_" + c).html();
                                    if (k > 0 && $("#completed_" + c).html(parseInt(k) - 1), "undefined" != task_type1[1]) {
                                        var l = $("#scheduled_" + c).html();
                                        l > 0 && $("#scheduled_" + c).html(parseInt(l) - 1)
                                    }
                                    if ("undefined" != task_type1[2]) {
                                        var m = $("#due_" + c).html();
                                        m > 0 && $("#due_" + c).html(parseInt(m) - 1)
                                    }
                                } else if ("2" == task_type1[0]) {
                                var n = $("#overdued_" + c).html();
                                if (n > 0 && $("#overdued_" + c).html(parseInt(n) - 1), "undefined" != task_type1[1]) {
                                    var l = $("#scheduled_" + c).html();
                                    l > 0 && $("#scheduled_" + c).html(parseInt(l) - 1)
                                }
                            } else {
                                if ("3" == task_type1[0]) {
                                    var l = $("#scheduled_" + c).html();
                                    l > 0 && $("#scheduled_" + c).html(parseInt(l) - 1)
                                }
                                if ("undefined" != task_type1[1]) {
                                    var m = $("#due_" + c).html();
                                    m > 0 && $("#due_" + c).html(parseInt(m) - 1)
                                }
                            }
                            var o = parseInt(d) - parseInt(i),
                                p = hoursminutes(o);
                            $("#estimate_time_" + c).html(p), $("#estimate_time_" + c).removeAttr("class"), o > 60 * g ? $("#estimate_time_" + c).attr("class", "commonlabel redlabel") : $("#estimate_time_" + c).attr("class", "commonlabel")
                        }
                    } else if ("weekView" == $("#redirect_page").val() || "NextFiveDay" == $("#redirect_page").val()) {
						 var c1 = parseInt($("#capacity_"+$("#strtotime_scheduled_date").val()).attr('data-time'));
                                var e1 = $("#est_"+$("#strtotime_scheduled_date").val()).attr('data-time');
                                var s1 = $("#spent_"+$("#strtotime_scheduled_date").val()).attr('data-time');
                        var h = $("#task_time_" + b).html();
                        if (h) var r = h.split("/"),
                            i = get_minutes(r[0]),
                            s = get_minutes(r[1]);
                        else var i = "0",
                            s = "0";
                        var e11 = parseInt(e1) - parseInt(i),
                            s11 = parseInt(s1) - parseInt(s);
                       // u > t ? $("#est_" + $("#strtotime_scheduled_date").val()).addClass("red") : $("#est_" + $("#strtotime_scheduled_date").val()).removeClass("red"), $("#est_" + $("#strtotime_scheduled_date").val()).html(p), $("#spent_" + $("#strtotime_scheduled_date").val()).html(v), 
					   $("#main_" + b).remove(),$.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/update_progress_bar",
                    data: {
                        id: $("#strtotime_scheduled_date").val(),
                        capacity: c1,
                        estimate_time: e11,
                        spent_time: s11,
                        title: 'Capacity: '+hoursminutes(c1)+'<br>Estimate Time'+hoursminutes(e11)+'<br>Time Spent: '+hoursminutes(s11)
                    },
                    success: function(progress) {
                        $('#progress_'+$("#strtotime_scheduled_date").val()).html(progress)
                    }
                });
                    } else if ("from_kanban" == $("#redirect_page").val()) {
                        var w = $("#status_time_" + $("#task_status_id").val()).html();
                        if (w) var d = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrlft").html()),
                            q = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrrlt").html());
                        else var d = "0",
                            q = "0";
                        var h = $("#task_time_" + b).html();
                        if (h) var r = h.split("/"),
                            i = get_minutes(r[0]),
                            s = get_minutes(r[1]);
                        else var i = "0",
                            s = "0";
                        var p = hoursminutes(parseInt(d) - parseInt(i)),
                            v = hoursminutes(parseInt(q) - parseInt(s)),
                            x = "<span class='hrlft tooltips' data-original-title='Estimate Time'>" + p + "</span><span class='hrrlt tooltips' data-original-title='Spent Time'>" + v + "</span>",
                            y = $("#task_count_hide_" + $("#task_status_id").val() + "_" + $("#task_swimlane_id").val()).html();
                        $("#task_count_hide_" + $("#task_status_id").val() + "_" + $("#task_swimlane_id").val()).html(parseInt(y) - 1), $("#status_time_" + $("#task_status_id").val()).html(x), $("#main_" + b).remove()
                    }else if ("from_customer" == $("#redirect_page").val()) {
                         $("#listtask_" + b).remove()
                    }
                    if ("from_teamdashboard" == $("#redirect_page").val()) $("#teamtodo_" + b).remove(), $("#teampending_" + b).remove(), $("#teamoverdue_" + b).remove(), $.ajax({
                        type: "post",
                        url: SIDE_URL + "user/teamdashcharttime",
                        data: {
                            mytask: TEAM_MY_TASK,
                            teamtask: TEAM_TASK
                        },
                        async: !1,
                        success: function(a) {
                            $(".ajax_team_time_data").html(a), google.load("visualization", "1", {
                                packages: ["corechart"],
                                callback: drawChart
                            })
                        },
                        error: function(a) {
                            console.log("Ajax request not recieved!")
                        }
                    }), $.ajax({
                        type: "post",
                        url: SIDE_URL + "user/teamdashchartcategory",
                        data: {
                            taskByCat_tot: TASK_BY_CAT_TOT
                        },
                        async: !1,
                        success: function(a) {
                            $(".ajax_team_category_data").html(a), google.load("visualization", "1", {
                                packages: ["corechart"],
                                callback: drawChartcat
                            })
                        },
                        error: function(a) {
                            console.log("Ajax request not recieved!")
                        }
                    }), $.ajax({
                        type: "post",
                        url: SIDE_URL + "user/taskteam_previousweek",
                        data: {
                            user_id: LOG_USER_ID
                        },
                        async: !1,
                        success: function(a) {
                            $("#sortableItem_3").html(a)
                        },
                        error: function(a) {
                            console.log("Ajax request not recieved!")
                        }
                    });
                    else if ("from_dashboard" == $("#redirect_page").val()) $("#todo_" + b).remove(), $("#watch" + b).remove(), $("#last_login_" + b).remove(), $.ajax({
                        type: "post",
                        url: SIDE_URL + "user/dashboardchart",
                        data: {
                            none: DASHBOARD_NONE,
                            low: DASHBOARD_LOW,
                            medium: DASHBOARD_MEDIUM,
                            high: DASHBOARD_HIGH
                        },
                        async: !1,
                        success: function(a) {
                            AmCharts.isReady = !0, $(".ajax_category_data").html(""), $(".ajax_category_data").html(a)
                        },
                        error: function(a) {
                            console.log("Ajax request not recieved!")
                        }
                    }), $.ajax({
                        type: "post",
                        url: SIDE_URL + "user/task_previousweek",
                        data: {
                            user_id: LOG_USER_ID
                        },
                        async: !1,
                        success: function(a) {
                            $("#sortableItem_3").html(a)
                        },
                        error: function(a) {
                            console.log("Ajax request not recieved!")
                        }
                    });
                    else if ("from_project" == $("#redirect_page").val()) {
                        $("#task_tasksort_" + b).remove();
                        var z = $("#typefilter1 li.active").attr("id");
                        $.ajax({
                            type: "post",
                            url: SIDE_URL + "project/task_counter",
                            data: {
                                user_id: $("#select_task").val(),
                                project_id: $("#general_project_id").val(),
                                type: $("#typefilter1 li.active").attr("id")
                            },
                            async: !1,
                            success: function(a) {
                                $("#task_counter").html(a), $("#typefilter1 li").removeClass("active"), $("#typefilter1 li[id=" + z + "]").addClass("active")
                            },
                            error: function(a) {
                                console.log("Ajax request not recieved!")
                            }
                        })
                    }
                    
                    $("#full-width").modal("hide"),$("#full-width").on('hidden.bs.modal', function(){
    $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
}),
//        alertify.set("notifier", "position", "top-right"), alertify.log("Task deleted successfully")
            toastr['success']("Task '"+data.task_title+"' has been deleted.", "");
                }
            })
        }
    )
}
}
$(function() {
    var ft = '0';
    $("#task_title").blur(function() {
        "" == $("#task_title").val() && ($("#alertify").show(), alertify.alert("Please insert task title to save task.", function(a) {
            return $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $(".task-tab-pane").removeClass("active"), $("#task_tab_1").addClass("active"), $("#task_title").focus(), $("#alertify").hide(), !1
        }))
    }), $(".task-input").on("change", function() {
        var a = $(this).attr("id"),
            b = COMPLETED_ID;
        if ("task_due_date" == a) {
            if ($(this).val() == $("#tmp_task_due_date").val()) return !1;
            $("#tmp_task_due_date").val($(this).val())
        }
        if ("task_status_id" == a) {
            if ("0" == $("#task_time_spent_hour").val() && "0" == $("#task_time_spent_min").val() && "1" == ACTUAL_TIME_ON && $("#task_status_id").val() == b) {
                var c = "Please enter Time Spent greater than 0.";
                return alertify.confirm(c, function(a) {
                    return 1 == a ? ($("#task_time_spent").focus(), $("#task_time_spent").blur(function() {
                        if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var a = $("#frm_task_general").serialize();
                        else var a = $("#task_status_id").val();
                        $.ajax({
                            type: "post",
                            url: SIDE_URL + "task/saveTask",
                            data: {
                                name: "task_status_id",
                                value: a,
                                task_id: $("#task_id").val(),
                                task_scheduled_date: $("#task_scheduled_date").val(),
                                redirect_page: $("#redirect_page").val()
                            },
                            async: !1,
                            success: function(a) {
                                $("#task_id").val(a), $("#allocation_task_id").val(a), $("#pre_task_id").val(a), $("#step_task_id").val(a), $("#files_task_id").val(a), $("#link_files_task_id").val(a), $("#comment_task_id").val(a), $("#freq_task_id").val(a), $("#search_task_id").val(a), $("#main_task_due_date").val($("#tmp_task_due_date").val())
                            }
                        })
                    }), !1) : ($("#task_status_id").val($("#old_task_status_id").val()), !1)
                }), !1
            }
        } else if ("task_title" == a) {
            var d = chk_validation();
            if (!d) return !1
        } else if ("Daily_every_day" == a || "Daily_every_week_day" == a || "Weekly_every_week_no" == a || "Monthly_op1_1" == a || "Monthly_op1_2" == a || "Monthly_op2_3" == a || "Monthly_op3_2" == a || "Yearly_op1" == a || "end_by_date" == a || "start_on_date" == a) {
            var e = chk_greater_zero($(this).val());
            if (!e) return !1;
            if(a=='start_on_date')
                $('#is_start_date').val('1');
            Frequency_ajax();
            $('#is_start_date').val('0');
            ft = '1';
         }
         if(ft == '0')
            $("#" + a + "_loading").show();
        var f = $(this).attr("name");
        if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var g = $("#frm_task_general").serialize();
        else if ("Daily_every_day" == a || "Daily_every_week_day" == a || "Weekly_every_week_no" == a || "Monthly_op1_1" == a || "Monthly_op1_2" == a || "Monthly_op2_3" == a || "Monthly_op3_2" == a || "Yearly_op1" == a || "end_by_date" == a || "Monthly_op2_1" == a || "Monthly_op2_2" == a || "Monthly_op3_1" == a || "Yearly_op2_1" == a || "Yearly_op2_2" == a || "Yearly_op3_1" == a || "Yearly_op3_2" == a || "Yearly_op3_3" == a || "Yearly_op4_1" == a || "Yearly_op4_2" == a || "start_on_date" == a || "end_by_date" == a) {
            if(a=='start_on_date')
                $('#is_start_date').val('1');
            Frequency_ajax(); 
            var  g = $("#frm_add_recurrence").serialize();
            $('#is_start_date').val('0');
            ft = '1';
        } else if ("task_time_estimate" == a) {
            var h = $("#" + a).val(),
                i = h,
                j = 1,
                k = parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
            if (i) {
                if ("1" == j)
                    if (1 == validate(i)) {
                        var l = i.split(":");
                        if (i.split(":"), 2 == l.length) {
                            var m = l[0],
                                n = l[1];
                            if (n >= 60) {
                                var o = parseInt(n / 60),
                                    p = n % 60,
                                    q = +m + +o,
                                    r = p;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(q), $("#task_time_estimate_min").val(r)
                            } else {
                                var q = m,
                                    r = n;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(q), $("#task_time_estimate_min").val(r)
                            }
                        }
                        if (i.length >= 1 && i.length <= 2)
                            if (i >= 60) {
                                var q = parseInt(i / 60),
                                    r = i % 60;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(q), $("#task_time_estimate_min").val(r)
                            } else {
                                var r = i,
                                    h = r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(0), $("#task_time_estimate_min").val(r)
                            }
                        if (3 == i.length && 2 != l.length) {
                            var s = new Array,
                                s = ("" + i).split("");
                            if (s[i.length - (i.length - 1)] + s[i.length - (i.length - 2)] >= 60) {
                                var t = 1,
                                    r = s[i.length - (i.length - 1)] + s[i.length - (i.length - 2)] - 60,
                                    q = +s[i.length - i.length] + +t;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(q), $("#task_time_estimate_min").val(r)
                            } else {
                                var r = s[i.length - (i.length - 1)] + s[i.length - (i.length - 2)],
                                    q = s[i.length - i.length];
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(q), $("#task_time_estimate_min").val(r)
                            }
                        }
                        if (4 == i.length && 2 != l.length) {
                            var s = new Array,
                                s = ("" + i).split("");
                            if (s[i.length - (i.length - 2)] + s[i.length - (i.length - 3)] >= 60) {
                                var t = 1,
                                    r = s[i.length - (i.length - 2)] + s[i.length - (i.length - 3)] - 60,
                                    q = +(s[i.length - i.length] + s[i.length - (i.length - 1)]) + +t;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(q), $("#task_time_estimate_min").val(r)
                            } else {
                                var r = s[i.length - (i.length - 2)] + s[i.length - (i.length - 3)],
                                    q = +(s[i.length - i.length] + s[i.length - (i.length - 1)]);
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_estimate").val(h), $("#task_time_estimate_hour").val(q), $("#task_time_estimate_min").val(r)
                            }
                        }
                        if (i.length >= 5 && 2 != l.length) return $("#task_time_estimate").val(""), $("#task_time_estimate_hour").val("0"), $("#task_time_estimate_min").val("0"), $("#alertify").show(), alertify.alert("maximum 4 digits allowed"), $("#task_time_estimate").focus(), $("#" + a + "_loading").hide(), !1
                    } else {
                        if (k != get_minutes(i)) return $("#task_time_estimate").val(""), $("#task_time_estimate_hour").val("0"), $("#task_time_estimate_min").val("0"), $("#alertify").show(), alertify.alert("your inserted value is not correct, please insert correct value", function() {
                            return $("#task_time_estimate").focus(), $("#" + a + "_loading").hide(), !1
                        }), $("#task_time_estimate").focus(), $("#" + a + "_loading").hide(), !1;
                        $("#" + a + "_loading").hide()
                    }
            } else $("#task_time_estimate").val(""), $("#task_time_estimate_hour").val("0"), $("#task_time_estimate_min").val("0");
            var u = $("input[name='" + f + "_hour']").val(),
                v = $("input[name='" + f + "_min']").val(),
                g = 60 * parseInt(u) + parseInt(v)
        } else if ("task_time_spent" == a) {
            var h = $("#" + a).val(),
                i = h,
                w = 1,
                l = i.split(":"),
                k = (i.split(":"), parseInt(60 * $("#task_time_spent_hour").val()) + parseInt($("#task_time_spent_min").val()));
            if (i) {
                if ("1" == w)
                    if (1 == validate(i)) {
                        if (w = "0", 2 == l.length) {
                            var m = l[0],
                                n = l[1];
                            if (n >= 60) {
                                var o = parseInt(n / 60),
                                    p = n % 60,
                                    q = +m + +o,
                                    r = p;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(q), $("#task_time_spent_min").val(r)
                            } else {
                                var q = m,
                                    r = n;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(q), $("#task_time_spent_min").val(r)
                            }
                        }
                        if (i.length >= 1 && i.length <= 2)
                            if (i >= 60) {
                                var q = parseInt(i / 60),
                                    r = i % 60;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(q), $("#task_time_spent_min").val(r)
                            } else {
                                var r = i,
                                    h = r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(0), $("#task_time_spent_min").val(r)
                            }
                        if (3 == i.length && 2 != l.length) {
                            var s = new Array,
                                s = ("" + i).split("");
                            if (s[i.length - (i.length - 1)] + s[i.length - (i.length - 2)] >= 60) {
                                var t = 1,
                                    r = s[i.length - (i.length - 1)] + s[i.length - (i.length - 2)] - 60,
                                    q = +s[i.length - i.length] + +t;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(q), $("#task_time_spent_min").val(r)
                            } else {
                                var r = s[i.length - (i.length - 1)] + s[i.length - (i.length - 2)],
                                    q = s[i.length - i.length];
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(q), $("#task_time_spent_min").val(r)
                            }
                        }
                        if (4 == i.length && 2 != l.length) {
                            var s = new Array,
                                s = ("" + i).split("");
                            if (s[i.length - (i.length - 2)] + s[i.length - (i.length - 3)] >= 60) {
                                var t = 1,
                                    r = s[i.length - (i.length - 2)] + s[i.length - (i.length - 3)] - 60,
                                    q = +(s[i.length - i.length] + s[i.length - (i.length - 1)]) + +t;
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(q), $("#task_time_spent_min").val(r)
                            } else {
                                var r = s[i.length - (i.length - 2)] + s[i.length - (i.length - 3)],
                                    q = +(s[i.length - i.length] + s[i.length - (i.length - 1)]);
                                if (0 == q) var h = r + "m";
                                else if (0 == r) var h = q + "h";
                                else var h = q + "h " + r + "m";
                                $("#task_time_spent").val(h), $("#task_time_spent_hour").val(q), $("#task_time_spent_min").val(r)
                            }
                        }
                        if (i.length >= 5 && 2 != l.length) return $("#task_time_spent").val(""), $("#task_time_spent_hour").val("0"), $("#task_time_spent_min").val("0"), w = "1", $("#alertify").show(), alertify.alert("maximum 4 digits allowed"), $("#task_time_spent_loading").hide(), !1
                    } else {
                        if (k != get_minutes(i)) return $("#task_time_spent").val(""), $("#task_time_spent_hour").val("0"), $("#task_time_spent_min").val("0"), w = "1", $("#alertify").show(), alertify.alert("your inserted value is not correct, please insert correct value", function(a) {
                            return $("#task_time_spent").focus(), !1
                        }), $("#task_time_spent_loading").hide(), !1;
                        $("#task_time_spent_loading").hide()
                    }
            } else $("#task_time_spent").val(""), $("#task_time_spent_hour").val("0"), $("#task_time_spent_min").val("0");
            var u = $("input[name='" + f + "_hour']").val(),
                v = $("input[name='" + f + "_min']").val(),
                g = 60 * parseInt(u) + parseInt(v);
            if ("0" == $("#task_time_spent_hour").val() && "0" == $("#task_time_spent_min").val()) {
                var b = COMPLETED_ID;
                if ("1" == ACTUAL_TIME_ON && $("#task_status_id").val() == b) {
                    var c = "Please add spent time more than 0 for completed task.";
                    return alertify.confirm(c, function(b) {
                        if (1 == b) return $("#" + a + "_loading").hide(), $("#task_time_spent").focus(), !1;
                        $("#task_time_spent_hour").val($("#old_task_time_spent_hour").val()), $("#task_time_spent_min").val($("#old_task_time_spent_min").val());
                        var c = $("#task_time_spent_hour").val() + "h " + $("#task_time_spent_min").val() + "m";
                        return $("#task_time_spent").val(c), $("#" + a + "_loading").hide(), !1
                    }), !1
                }
            }
        } else {
            g = $(this).val();
        } 
        var x = "", 
            y = "";
        if ("task_category_id" == f) var x = $("#task_sub_category_id").val();
        else if ("task_division_id[]" == f) var x = $("#task_department_id").val();
        else if ("task_project_id" == f) var x = $("#section_id1").val(),y = $("#task_allocated_user_id").val();
        else if ("customer_id"==f) var x = $("#customer_id").val();        
        if (ft == '1') 
        {  
         setTimeout(function(){  
            var z= $("#new_start_date").val();
            $.ajax({
                type: "post",
                url: SIDE_URL + "task/saveTask",
                data: {
                    name: f,
                    value: $("#frm_add_recurrence").serialize(),
                    task_id: $("#task_id").val(),
                    task_scheduled_date: $("#task_scheduled_date").val(),
                    redirect_page: $("#redirect_page").val(),
                    sub_val: x,
                    sub_val2: y,
                    sub_val3:z
                },
                async: !1,
                success: function(b) { ft ='0';
                    return $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent,#customer_id").attr("disabled", !1), $("input[name='task_category_id']").is(":disabled") && $("#task_sub_category_id").attr("disabled", !0), 13 == $("#event").val() && ($(".save_close").trigger("click"), $("#event").val("")),  !0
                }
            });
         }, 1000);
        }else{
            $.ajax({
                 type: "post",
                 url: SIDE_URL + "task/saveTask",
                 data: {
                     name: f,
                     value: g,
                     task_id: $("#task_id").val(),
                     task_scheduled_date: $("#task_scheduled_date").val(),
                     redirect_page: $("#redirect_page").val(),
                     sub_val: x,
                     sub_val2: y
                 },
                 async: !1,
                 success: function(b) {
                     return $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent,#customer_id").attr("disabled", !1), $("input[name='task_category_id']").is(":disabled") && $("#task_sub_category_id").attr("disabled", !0), 13 == $("#event").val() && ($(".save_close").trigger("click"), $("#event").val("")), $("#" + a + "_loading").hide(), !0
                 }
             });
        }
    }), $(".task-chk-input").on("click", function() {
        var a = $(this).attr("name"),
            b = $(this).attr("id");
        if ("frequency_type" == a ? (recuurence_click()) : "recurrence_type" == a ? (recurrence_type_click(), "1" == $(this).val() ? daily_chk_click() : "2" == $(this).val() ? weekly_chk_click() : "3" == $(this).val() ? monthly_chk_click() : "4" == $(this).val() && yearly_chk_click()) : "Daily_every_weekday" == a ? ("1" == $(this).val() ? Daily_op2_click() : Daily_op1_click()) : "Weekly_week_day[]" == a ? (a = "Weekly_week_day") : "monthly_radios" == a ? "1" == $(this).val() ? Monthly_op1_click() : "2" == $(this).val() ? Monthly_op2_click() : "3" == $(this).val() && Monthly_op3_click() : "yearly_radios" == a ? "1" == $(this).val() ? Yearly_op1_click() : "2" == $(this).val() ? Yearly_op2_click() : "3" == $(this).val() ? Yearly_op3_click() : "4" == $(this).val() && Yearly_op4_click() : "no_end_date_val" == a && (a = "no_end_date", "1" == $(this).val() ? NoEndDate1() : "2" == $(this).val() ? NoEndDate2() : "3" == $(this).val() && NoEndDate3()),  "" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var c = $("#frm_task_general").serialize();
        else if ("frequency_type" == a || "recurrence_type" == a || "Daily_every_weekday" == a || "Weekly_week_day" == a || "monthly_radios" == a || "yearly_radios" == a || "no_end_date" == a) var c = $("#frm_add_recurrence").serialize(), ft = '1';
        else if ("locked_due_date" == a) {
            $("#locked_due_date").is(":checked") ? $("#hdn_locked_due_date").val("1") : $("#hdn_locked_due_date").val("");
            var c = $("#hdn_locked_due_date").val()
        } else if ("is_personal" == a) {
            $("#is_personal").is(":checked") ? $("#hdn_is_personal").val("1") : $("#hdn_is_personal").val("");
            var c = $("#hdn_is_personal").val()
        } else var c = $(this).val();
        if (ft == '1') 
        {
            setTimeout(function(){  
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: a,
                        value: $("#frm_add_recurrence").serialize(),
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(c) { ft = '0';
                        $("#task_id").val(c), $("#allocation_task_id").val(c), $("#pre_task_id").val(c), $("#step_task_id").val(c), $("#files_task_id").val(c), $("#link_files_task_id").val(c), $("#comment_task_id").val(c), $("#freq_task_id").val(c), $("#search_task_id").val(c), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("input[name='task_category_id']").is(":disabled") && $("#task_sub_category_id").attr("disabled", !0), 13 == $("#event").val() && ($(".save_close").trigger("click"), $("#event").val(""))
                    }
                });
            }, 1000);
        }else{
            $.ajax({
                 type: "post",
                 url: SIDE_URL + "task/saveTask",
                 data: {
                     name: a,
                     value: c,
                     task_id: $("#task_id").val(),
                     task_scheduled_date: $("#task_scheduled_date").val(),
                     redirect_page: $("#redirect_page").val()
                 },
                 async: !1,
                 success: function(c) {
                     $("#task_id").val(c), $("#allocation_task_id").val(c), $("#pre_task_id").val(c), $("#step_task_id").val(c), $("#files_task_id").val(c), $("#link_files_task_id").val(c), $("#comment_task_id").val(c), $("#freq_task_id").val(c), $("#search_task_id").val(c), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("input[name='task_category_id']").is(":disabled") && $("#task_sub_category_id").attr("disabled", !0), 13 == $("#event").val() && ($(".save_close").trigger("click"), $("#event").val("")), $("#" + b + "_loading").hide(), $("#" + a + "_loading").hide()
                 }
             });
        }
    }), $(".up,.down").click(function() {
        var a = $(this).parents("tr:first");
        $(this).is(".up") ? a.insertBefore(a.prev()) : a.insertAfter(a.next())
    }), $("#history_tab").click(function() {
        var a = $("#task_id").val();
        a && $.ajax({
            type: "post",
            url: SIDE_URL + "task/ajax_history",
            data: {
                task_id: $("#task_id").val()
            },
            async: !1,
            success: function(a) {
                $("#updated_history").html(a)
            }
        })
    }), $("#file_tab").click(function() {
        var a = $("#task_id").val();
        var b = $("#task_data_"+a).val();
         a && $.ajax({
            type: "post",
            url: SIDE_URL + "task/ajax_files",
            data: {
                task_id: $("#task_id").val(),
                task_data :b
            },
            async: !1,
            success: function(a) {
                $("#updated_files").html(a)
            }
        })
    }), $("#cmt_tab").click(function() {
        var a = $("#task_id").val();
        a && $.ajax({
            type: "post",
            url: SIDE_URL + "task/ajax_comments",
            data: {
                task_id: $("#task_id").val()
            },
            async: !1,
            success: function(b) {
                $("#updated_task_comments").html(b), $("#comment_task_id").val(a)
            }
        })
    }), $("#dependent_tab").click(function() {
        $("#personal_task_msg").hide(), $("#depent_normal").show(), is_task_personal = "0", $("#is_personal").is(":checked") && (is_task_personal = "1"), "1" == is_task_personal && ($("#personal_task_msg").show(), $("#depent_normal").hide())
    }), $(".save_close, .save_close_cross").on("click", function() {
        1 == $("#is_multi_changed").val() && multipleAllocation_tasks();
        var a = COMPLETED_ID;
        if ("1" == ACTUAL_TIME_ON && $("#task_status_id").val() == a && "0" == $("#task_time_spent_hour").val() && "0" == $("#task_time_spent_min").val()) return $("#alertify").show(), alertify.alert("Please enter Time Spent greater than 0."), !1;
        if ("" == $("#task_title").val()) return $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $(".task-tab-pane").removeClass("active"), $("#task_tab_1").addClass("active"), $("#task_title").focus(), !1;
        $("#is_edited").val("0"), $("#is_edited1").val("0");
        var b = $("#redirect_page").val(),
            c = $("#task_id").val(),
            d = c.indexOf("child");
        if (d < 0 && $("#task_id").val()) {
            if (1 == $("#is_multi_changed").val()) {
                var e = [];
                $("input[name='task_allocated_user_id[]']:checkbox:checked").each(function(a) {
                    e[a] = $(this).val()
                }), "" != e && $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/set_multiallocation_tasks",
                    data: {
                        task_id: $("#task_id").val()
                    },
                    async: !1,
                    success: function(c) {
                        c = jQuery.parseJSON(c), $.map(c, function(c) {
                            if ("from_kanban" == b) c.task_allocated_user_id == $("#kanban_team_user_id").val() && $.ajax({
                                type: "post",
                                url: SIDE_URL + "kanban/set_update_task",
                                data: {
                                    task_id: c.task_id,
                                    color_menu : $("#kanban_color_menu").val()
                                },
                                async: !1,
                                success: function(a) {
                                    $("#main_" + c.task_id).length && $("#main_" + c.task_id).remove(), $("#task_status_" + READY_ID + "_" + DEFAULT_SWIMLANE).prepend(a)
                                }
                            });
                            else if ("weekView" == b || "NextFiveDay" == b) c.task_allocated_user_id == $("#calender_team_user_id").val() && $.ajax({
                                type: "post",
                                url: SIDE_URL + "calendar/set_weekly_update_task",
                                data: {
                                    task_id: c.task_id,
                                    start_date: $("#week_start_date").val(),
                                    end_date: $("#week_end_date").val(),
                                    action: $("#week_action").val(),
                                    active_menu: $("#redirect_page").val(),
                                    color_menu :$("#task_color_menu").val()
                                },
                                async: !1,
                                success: function(a) { 
                                    $("#main_" + c.task_id).length && $("#main_" + c.task_id).remove(), 0 == $("#week_" + c.task_scheduled_date + " .task_div").length && $("#week_" + c.task_scheduled_date + " .space").remove(), $("#week_" + c.task_scheduled_date).prepend(a)
                                }
                            });
                            else if ("from_calendar" == b || "FiveWeekView" == b) {
                                if (c.task_allocated_user_id == $("#calender_team_user_id").val()) {
                                    if (0 == $("#task_list_" + c.task_scheduled_date).length) var d = 0;
                                    else var d = 1;
                                    if (0 == d)
                                        if ("from_calendar" == b) {
                                            var e = $("#td_" + c.task_scheduled_date + " .weekday-txt").html();
                                            e = e.replace("WD ", ""), $.ajax({
                                                type: "post",
                                                url: SIDE_URL + "calendar/monthly_day_view",
                                                data: {
                                                    date: c.task_scheduled_date,
                                                    task_id: c.task_id,
                                                    year: $("#year").val(),
                                                    month: $("#month").val(),
                                                    wd: e,
                                                    color_menu: $("#monthly_color_menu").val()
                                                },
                                                async: !1,
                                                success: function(a) {
                                                    App.init(), $("#td_" + c.task_scheduled_date).html(a), $("body").tooltip({
                                                        selector: ".tooltips"
                                                    })
                                                }
                                            })
                                        } else "FiveWeekView" == b && $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "calendar/monthly_day_view",
                                            data: {
                                                date: c.task_scheduled_date,
                                                task_id: c.task_id,
                                                year: $("#year").val(),
                                                month: $("#month").val(),
                                                from: "ajax",
                                                color_menu: $("#monthly_color_menu").val()
                                            },
                                            async: !1,
                                            success: function(a) {
                                                App.init(), $("#td_" + c.task_scheduled_date).html(a), $("body").tooltip({
                                                    selector: ".tooltips"
                                                })
                                            }
                                        });
                                    else $.ajax({
                                        type: "post",
                                        url: SIDE_URL + "calendar/set_update_task",
                                        data: {
                                            task_id: c.task_id,
                                            year: $("#year").val(),
                                            month: $("#month").val(),
                                            color_menu: $("#monthly_color_menu").val()
                                        },
                                        async: !1,
                                        success: function(b) {
                                            if ($("#task_" + c.task_id).length && $("#task_" + c.task_id).remove(), b) {
                                                $("#" + c.task_scheduled_date).prepend(b), a == c.task_status_id ? ($("#completed_" + c.task_scheduled_date).html(parseInt($("#completed_" + c.task_scheduled_date).html()) + 1), $("#scheduled_" + c.task_scheduled_date).html(parseInt($("#scheduled_" + c.task_scheduled_date).html()) + 1), c.task_due_date_time == c.task_scheduled_date && $("#due_" + c.task_scheduled_date).html(parseInt($("#due_" + c.task_scheduled_date).html()) + 1)) : c.task_due_date_time < c.today_time ? ($("#overdued_" + c.task_scheduled_date).html(parseInt($("#overdued_" + c.task_scheduled_date).html()) + 1), $("#scheduled_" + c.task_scheduled_date).html(parseInt($("#scheduled_" + c.task_scheduled_date).html()) + 1)) : ($("#scheduled_" + c.task_scheduled_date).html(parseInt($("#scheduled_" + c.task_scheduled_date).html()) + 1), c.task_due_date_time == c.task_scheduled_date && $("#due_" + c.task_scheduled_date).html(parseInt($("#due_" + c.task_scheduled_date).html()) + 1));
                                                var d = get_minutes($("#estimate_time_" + c.task_scheduled_date).html());
                                                if (d) var e = parseInt(d) + parseInt(c.task_time_estimate);
                                                else var e = parseInt(c.task_time_estimate);
                                                var f = hoursminutes(e);
                                                $("#estimate_time_" + c.task_scheduled_date).html(f);
                                                var g = $("#capacity_time_" + c.task_scheduled_date).html(),
                                                    h = g.indexOf("h"),
                                                    i = g.substr(0, h);
                                                $("#estimate_time_" + c.task_scheduled_date).removeAttr("class"), e > 60 * i ? $("#estimate_time_" + c.task_scheduled_date).attr("class", "commonlabel redlabel") : $("#estimate_time_" + c.task_scheduled_date).attr("class", "commonlabel"), popover()
                                            }
                                            $("body").tooltip({
                                                selector: ".tooltips"
                                            })
                                        }
                                    })
                                }
                            } else if ("from_teamdashboard" == b || "from_dashboard" == b) {
                                var f = c.task_id;
                                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "dashboardtask/set_update_task",
                                    data: {
                                        task_id: f,
                                        redirect_page: $("#redirect_page").val(),
                                        type: $("#dashboard_priority").val(),
                                        duration: $("#dashboard_duration").val()
                                    },
                                    async: !1,
                                    success: function(a) {
                                        function b(a) {
                                            return a.substr(0, 1).toUpperCase() + a.substr(1)
                                        }
                                        if (a = jQuery.parseJSON(a), a.task_data.task_scheduled_date, today_date = a.today_date, "from_teamdashboard" == $("#redirect_page").val())
                                            if (a.task_data.task_status_id != COMPLETED_ID)
                                                if ("1" == a.task_data.is_personal || "assign_other" == a.assign_status) $("#teamtodo_" + a.task_data.task_id).length && ($("#teamtodo_" + a.task_data.task_id).remove(), $("#teamtodo_" + a.task_data.task_id).hide()), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teampending_" + a.task_data.task_id).length && ($("#teampending_" + a.task_data.task_id).remove(), $("#teampending_" + a.task_data.task_id).hide()), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teamoverdue_" + a.task_data.task_id).length && ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide()), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                else {
                                                    if (1 == a.is_div_valid) {
                                                        var c = a.task_data.task_title;
                                                        if (c > 25) var d = c.substring(0, 22) + "...";
                                                        else var d = c;
                                                        var e = a.task_status_name;
                                                        status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                        var f = "";
                                                        if (f += '<tr id="teamtodo_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a></td>" : '<a  data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>", f += '<td class="teamdoDueDatepicker" id="teamDoDue_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="teamSchedulledDatepicker" id="teamSchedulled_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<td><span class="label label-' + status_class + '">' + e + "</span></td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teamtodo_" + a.task_data.task_id).length) $("#teamtodo_" + a.task_data.task_id).replaceWith(f), $(".teamdoDueDatepicker").datepicker({
                                                            startDate: -(1 / 0),
                                                            format: JAVASCRIPT_DATE_FORMAT
                                                        }).on("changeDate", function(a) {
                                                            $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                        }), $(".teamSchedulledDatepicker").datepicker({
                                                            startDate: -(1 / 0),
                                                            format: JAVASCRIPT_DATE_FORMAT
                                                        }).on("changeDate", function(a) {
                                                            $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                        });
                                                        else {
                                                            if ($("#teamtodolist tr td.dataTables_empty").length && $("#teamtodolist tr td.dataTables_empty").remove(), $("#teamtodolist").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                            else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                            var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                                            $(h).attr("id", "teamtodo_" + a.task_data.task_id), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(2)").addClass("teamdoDueDatepicker"), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(2)").attr("id", "teamDoDue_" + a.task_data.task_id), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(3)").addClass("teamSchedulledDatepicker"), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(3)").attr("id", "teamSchedulled_" + a.task_data.task_id), $(".teamdoDueDatepicker").datepicker({
                                                                startDate: -(1 / 0),
                                                                format: JAVASCRIPT_DATE_FORMAT
                                                            }).on("changeDate", function(a) {
                                                                $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                            }), $(".teamSchedulledDatepicker").datepicker({
                                                                startDate: -(1 / 0),
                                                                format: JAVASCRIPT_DATE_FORMAT
                                                            }).on("changeDate", function(a) {
                                                                $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                            })
                                                        }
                                                    } else $("#teamtodo_" + a.task_data.task_id).length && ($("#teamtodo_" + a.task_data.task_id).remove(), $("#teamtodo_" + a.task_data.task_id).hide()), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                    if (a.today_date) {
                                                        var c = a.task_data.task_title;
                                                        var cname = a.task_data.customer_name;
                                                        if (cname) var d = c+' ('+cname+')';
                                                        else var d = c;
                                                        var e = a.task_status_name;
                                                        status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                        var f = "";
                                                        if (f += '<tr id="teampending_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>",f +='<td><span class="label label-sm label-' + status_class + '">' + e + '</span></td>' ,f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += "<td>" ,f += a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>',f += "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teampending_" + a.task_data.task_id).length) $("#teampending_" + a.task_data.task_id).replaceWith(f);
                                                        else {
                                                            if ($("#teampending_list tr td.dataTables_empty").length && $("#teampending_list tr td.dataTables_empty").remove(), $("#teampending_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                            else var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                            var h = $("#filtertab2").dataTable().fnGetNodes(g);
                                                            $(h).attr("id", "teampending_" + a.task_data.task_id)
                                                        }
                                                    } else $("#teampending_" + a.task_data.task_id).length && ($("#teampending_" + a.task_data.task_id).remove(), $("#teampending_" + a.task_data.task_id).hide()), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                    if (a.today_date > a.strtotime_due_date) {
                                                        var c = a.task_data.task_title;
                                                        if (c.length > 25) var d = c.substring(0, 22) + "...";
                                                        else var d = c;
                                                        var e = a.task_status_name;
                                                        status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                        var f = "";
                                                        if (f += '<tr id="teamoverdue_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>", f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += "<td>" + a.delay + "</td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teamoverdue_" + a.task_data.task_id).length) "1" != a.task_data.is_personal ? $("#teamoverdue_" + a.task_data.task_id).replaceWith(f) : ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide());
                                                        else {
                                                            if ($("#teamoverdue_list tr td.dataTables_empty").length && $("#teamoverdue_list tr td.dataTables_empty").remove(), $("#teamoverdue_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.task_data.task_priority]);
                                                            else var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.task_data.task_priority]);
                                                            var h = $("#filtertab3").dataTable().fnGetNodes(g);
                                                            $(h).attr("id", "teamoverdue_" + a.task_data.task_id)
                                                        }
                                                    } else $("#teamoverdue_" + a.task_data.task_id).length && ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide()), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
                                                }
                                        else $("#teamtodo_" + a.task_data.task_id).length && ($("#teamtodo_" + a.task_data.task_id).remove(), $("#teamtodo_" + a.task_data.task_id).hide()), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teampending_" + a.task_data.task_id).length && ($("#teampending_" + a.task_data.task_id).remove(), $("#teampending_" + a.task_data.task_id).hide()), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teamoverdue_" + a.task_data.task_id).length && ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide()), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                        else {
                                            if ($("#watch" + a.task_data.task_id).length) {
                                                var e = a.task_status_name;
                                                status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                var i = "";
                                                i += '<tr id="watch' + a.task_data.task_id + '" role="row" class="odd">', i += '<td title="' + a.task_data.task_description + '" class="sorting_1">', i += "1" == a.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\')" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a></td>", i += "</td>", i += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", i += '<td class="hidden-480">' , i +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', i +=  "</td>", i += '<td><span class="label label-' + status_class + '">' + e + "</span></td>", i += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\'' + a.watch_id + "','" + a.task_data.task_id + '\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>', $("#watch" + a.task_data.task_id).replaceWith(i)
                                            }
                                            if ($("#last_login_" + a.task_data.task_id).length) {
                                                var j = "";
                                                j += '<tr id="last_login_' + a.task_data.task_id + '" role="row" class="odd">', j += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips dashboard_master_' + a.task_data.master_task_id + '" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\')" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips dashboard_master_' + a.task_data.master_task_id + '" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a></td>", j += '</td><td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", j += "<td>" + a.task_data.task_priority + "</td>", j += "</tr>", $("#last_login_" + a.task_data.task_id).replaceWith(j)
                                            }
                                            if (a.task_data.task_status_id != COMPLETED_ID)
                                                if ("assign_other" == a.assign_status) $("#todo_" + $("#task_id").val()).remove(), $("#todo_" + $("#task_id").val()).hide(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#last_login_" + $("#task_id").val()).remove(), $("#teamoverdue_" + $("#task_id").val()).remove(), $("#teampending_" + $("#task_id").val()).remove(), $("#teamtodo_" + $("#task_id").val()).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                else if (1 == a.is_div_valid) {
                                                var c = a.task_data.task_title;
                                                if (c > 40) var d = c.substring(0, 37) + "...";
                                                else var d = c;
                                                var e = a.task_status_name;
                                                status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                var f = "";
                                                if (f += '<tr id="todo_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '" class="sorting_1">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" data-dismiss="modal" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)" >' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(c) + "</a></td>", f += '<td class="todoDueDatepicker" id="toDoDue_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="todoSchedulledDatepicker" id="schedulled_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<td><span class="label label-' + status_class + '">' + e + '</span></td><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" /></tr>', $("#todo_" + a.task_data.task_id).length) $("#todo_" + a.task_data.task_id).replaceWith(f), $(".todoSchedulledDatepicker").datepicker({
                                                    startDate: -(1 / 0),
                                                    format: JAVASCRIPT_DATE_FORMAT
                                                }).on("changeDate", function(a) {
                                                    $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                }), $(".todoDueDatepicker").datepicker({
                                                    startDate: -(1 / 0),
                                                    format: JAVASCRIPT_DATE_FORMAT
                                                }).on("changeDate", function(a) {
                                                    $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                });
                                                else {
                                                    if ($("#todolist tr td.dataTables_empty").length && $("#todolist tr td.dataTables_empty").remove(), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                    else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                    var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                                    $(h).attr("id", "todo_" + a.task_data.task_id), $("#todo_" + a.task_data.task_id + " td:nth-child(2)").addClass("todoDueDatepicker"), $("#todo_" + a.task_data.task_id + " td:nth-child(2)").attr("id", "toDoDue_" + a.task_data.task_id), $("#todo_" + a.task_data.task_id + " td:nth-child(3)").addClass("todoSchedulledDatepicker"), $("#todo_" + a.task_data.task_id + " td:nth-child(3)").attr("id", "schedulled_" + a.task_data.task_id), $(".todoSchedulledDatepicker").datepicker({
                                                        startDate: -(1 / 0),
                                                        format: JAVASCRIPT_DATE_FORMAT
                                                    }).on("changeDate", function(a) {
                                                        $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                    }), $(".todoDueDatepicker").datepicker({
                                                        startDate: -(1 / 0),
                                                        format: JAVASCRIPT_DATE_FORMAT
                                                    }).on("changeDate", function(a) {
                                                        $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                    })
                                                }
                                            } else $("#todo_" + a.task_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                            else $("#todo_" + a.task_data.task_id).length && $("#todo_" + a.task_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
                                            if (a.today_date) {console.log('333')
                                                var c = a.task_data.task_title;
                                                var cname = a.task_data.customer_name;
                                                if (cname) var d = c+' ('+cname+')';
                                                else var d = c;
                                                var e = a.task_status_name;
                                                status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                var f = "";
                                                if (f += '<tr id="teampending_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>",f +='<td><span class="label label-sm label-' + status_class + '">' + e + '</span></td>' ,f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += "<td>" ,f += a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>',f += "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teampending_" + a.task_data.task_id).length) $("#teampending_" + a.task_data.task_id).replaceWith(f);
                                                else {
                                                    if ($("#teampending_list tr td.dataTables_empty").length && $("#teampending_list tr td.dataTables_empty").remove(), $("#teampending_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab5").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + '<span class="label label-sm label-' + status_class + '">' + e + '</span>',a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                    else var g = $("#filtertab5").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" +'<span class="label label-sm label-' + status_class + '">' + e + '</span>', a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                    var h = $("#filtertab5").dataTable().fnGetNodes(g);
                                                    $(h).attr("id", "teampending_" + a.task_data.task_id)
                                                }
                                            } else $("#teampending_" + a.task_data.task_id).length && $("#teampending_" + a.task_data.task_id).remove(), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                        }
                                        "from_teamdashboard" == $("#redirect_page").val() ? ($.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/teamdashcharttime",
                                            data: {
                                                mytask: TEAM_MY_TASK,
                                                teamtask: TEAM_TASK
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $(".ajax_team_time_data").html(a), google.load("visualization", "1", {
                                                    packages: ["corechart"],
                                                    callback: drawChart
                                                })
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }), $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/teamdashchartcategory",
                                            data: {
                                                taskByCat_tot: TASK_BY_CAT_TOT
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $(".ajax_team_category_data").html(a), google.load("visualization", "1", {
                                                    packages: ["corechart"],
                                                    callback: drawChartcat
                                                })
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }), $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/taskteam_previousweek",
                                            data: {
                                                user_id: LOG_USER_ID
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $("#sortableItem_3").html(a)
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        })) : "from_dashboard" == $("#redirect_page").val() && ($.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/dashboardchart",
                                            data: {
                                                none: DASHBOARD_NONE,
                                                low: DASHBOARD_LOW,
                                                medium: DASHBOARD_MEDIUM,
                                                high: DASHBOARD_HIGH
                                            },
                                            async: !1,
                                            success: function(a) {
                                                AmCharts.isReady = !0, $(".ajax_category_data").html(""), $(".ajax_category_data").html(a)
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }), $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/task_previousweek",
                                            data: {
                                                user_id: LOG_USER_ID
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $("#sortableItem_3").html(a)
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }))
                                    }
                                })
                            } else if ("from_project" == b) {
                                var g = $("#select_task_assign").val(),
                                    h = $("#select_task_status").val();
                                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    data: {
                                        task_id: c.task_id,
                                        type: h,
                                        user_id: g
                                    },
                                    async: !1,
                                    success: function(a) { 
                                        App.init(), a ? ($("#task_tasksort_" + c.task_id).length && $("#task_tasksort_" + c.task_id).remove(), 0 == $("#task_tasksort_" + c.task_id + " ul").length && ("0" != c.section_id ? $("#taskmove_" + c.subsection_id + "_" + c.section_id).append(a) : $("#panel-body1_" + c.subsection_id + " div.task_tasksort").length ? $("#panel-body1_" + c.subsection_id + " div.task_tasksort:last").after(a) : $("#panel-body1_" + c.subsection_id + " div.add_new_task_div").before(a))) : $("#task_tasksort_" + c.task_id).remove(), $("#typefilter1 li").removeClass("active"), $("#typefilter1 li[id=" + h + "]").addClass("active")
                                    }
                                })
                            }
                        })
                    }
                })
            }
            
            var oe = $("#task_estimate_time_" + $("#task_id").val()).val();
            var os = $("#task_spent_time_" + $("#task_id").val()).val();
            if ("0" != $("#is_dependency_added").val() && $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/set_dependent_tasks",
                    data: {
                        task_id: $("#task_id").val()
                    },
                    async: !1,
                    success: function(c) {
                        c = jQuery.parseJSON(c), $.map(c, function(c) {
                            if ("from_kanban" == b) $.ajax({
                                type: "post",
                                url: SIDE_URL + "kanban/set_update_task",
                                data: {
                                    task_id: c.task_id,
                                    color_menu : $("#kanban_color_menu").val()
                                },
                                async: !1,
                                success: function(a) { 
                                    $("#main_" + c.task_id).length && $("#main_" + c.task_id).remove(), $("#task_status_" + c.task_status_id + "_" + c.swimlane_id).prepend(a)
                                }
                            });
                            else if ("weekView" == b || "NextFiveDay" == b) $.ajax({
                                type: "post",
                                url: SIDE_URL + "calendar/set_weekly_update_task",
                                data: {
                                    task_id: c.task_id,
                                    start_date: $("#week_start_date").val(),
                                    end_date: $("#week_end_date").val(),
                                    action: $("#week_action").val(),
                                    active_menu: $("#redirect_page").val(),
                                    color_menu :$("#task_color_menu").val()
                                },
                                async: !1,
                                success: function(a) { 
                                    $("#main_" + c.task_id).length && $("#main_" + c.task_id).remove(), 0 == $("#week_" + c.task_scheduled_date + " .task_div").length && $("#week_" + c.task_scheduled_date + " .space").remove(), $("#week_" + c.task_scheduled_date).prepend(a)
                                }
                            });
                            else if ("from_calendar" == b || "FiveWeekView" == b) {
                                if (0 == $("#task_list_" + c.task_scheduled_date).length) var d = 0;
                                else var d = 1;
                                if (0 == d)
                                    if ("from_calendar" == b) {
                                        var e = $("#td_" + c.task_scheduled_date + " .weekday-txt").html();
                                        e = e.replace("WD ", ""), $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "calendar/monthly_day_view",
                                            data: {
                                                date: c.task_scheduled_date,
                                                task_id: c.task_id,
                                                year: $("#year").val(),
                                                month: $("#month").val(),
                                                wd: e,
                                                color_menu: $("#monthly_color_menu").val()
                                            },
                                            async: !1,
                                            success: function(a) {
                                                App.init(), $("#td_" + c.task_scheduled_date).html(a), $("body").tooltip({
                                                    selector: ".tooltips"
                                                })
                                            }
                                        })
                                    } else "FiveWeekView" == b && $.ajax({
                                        type: "post",
                                        url: SIDE_URL + "calendar/monthly_day_view",
                                        data: {
                                            date: c.task_scheduled_date,
                                            task_id: c.task_id,
                                            year: $("#year").val(),
                                            month: $("#month").val(),
                                            from: "ajax",
                                            color_menu: $("#monthly_color_menu").val()
                                        },
                                        async: !1,
                                        success: function(a) {
                                            App.init(), $("#td_" + c.task_scheduled_date).html(a), $("body").tooltip({
                                                selector: ".tooltips"
                                            })
                                        }
                                    });
                                else $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "calendar/set_update_task",
                                    data: {
                                        task_id: c.task_id,
                                        year: $("#year").val(),
                                        month: $("#month").val(),
                                        color_menu: $("#monthly_color_menu").val()
                                    },
                                    async: !1,
                                    success: function(b) {
                                        if ($("#task_" + c.task_id).length && $("#task_" + c.task_id).remove(), b) {
                                            $("#" + c.task_scheduled_date).prepend(b), a == c.task_status_id ? ($("#completed_" + c.task_scheduled_date).html(parseInt($("#completed_" + c.task_scheduled_date).html()) + 1), $("#scheduled_" + c.task_scheduled_date).html(parseInt($("#scheduled_" + c.task_scheduled_date).html()) + 1), c.task_due_date_time == c.task_scheduled_date && $("#due_" + c.task_scheduled_date).html(parseInt($("#due_" + c.task_scheduled_date).html()) + 1)) : c.task_due_date_time < c.today_time ? ($("#overdued_" + c.task_scheduled_date).html(parseInt($("#overdued_" + c.task_scheduled_date).html()) + 1), $("#scheduled_" + c.task_scheduled_date).html(parseInt($("#scheduled_" + c.task_scheduled_date).html()) + 1)) : ($("#scheduled_" + c.task_scheduled_date).html(parseInt($("#scheduled_" + c.task_scheduled_date).html()) + 1), c.task_due_date_time == c.task_scheduled_date && $("#due_" + c.task_scheduled_date).html(parseInt($("#due_" + c.task_scheduled_date).html()) + 1));
                                            var d = get_minutes($("#estimate_time_" + c.task_scheduled_date).html());
                                            if (d) var e = parseInt(d) + parseInt(c.task_time_estimate);
                                            else var e = parseInt(c.task_time_estimate);
                                            var f = hoursminutes(e);
                                            $("#estimate_time_" + c.task_scheduled_date).html(f);
                                            var g = $("#capacity_time_" + c.task_scheduled_date).html(),
                                                h = g.indexOf("h"),
                                                i = g.substr(0, h);
                                            $("#estimate_time_" + c.task_scheduled_date).removeAttr("class"), e > 60 * i ? $("#estimate_time_" + c.task_scheduled_date).attr("class", "commonlabel redlabel") : $("#estimate_time_" + c.task_scheduled_date).attr("class", "commonlabel"), popover()
                                        }
                                        $("body").tooltip({
                                            selector: ".tooltips"
                                        })
                                    }
                                })
                            } else if ("from_teamdashboard" == b || "from_dashboard" == b) {
                                var f = c.task_id;
                                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "dashboardtask/set_update_task",
                                    data: {
                                        task_id: f,
                                        redirect_page: $("#redirect_page").val(),
                                        type: $("#dashboard_priority").val(),
                                        duration: $("#dashboard_duration").val()
                                    },
                                    async: !1,
                                    success: function(a) {
                                        function b(a) {
                                            return a.substr(0, 1).toUpperCase() + a.substr(1)
                                        }
                                        if (a = jQuery.parseJSON(a), a.task_data.task_scheduled_date, today_date = a.today_date, "from_teamdashboard" == $("#redirect_page").val())
                                            if (a.task_data.task_status_id != COMPLETED_ID)
                                                if ("1" == a.task_data.is_personal || "assign_other" == a.assign_status) $("#teamtodo_" + a.task_data.task_id).length && ($("#teamtodo_" + a.task_data.task_id).remove(), $("#teamtodo_" + a.task_data.task_id).hide()), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teampending_" + a.task_data.task_id).length && ($("#teampending_" + a.task_data.task_id).remove(), $("#teampending_" + a.task_data.task_id).hide()), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teamoverdue_" + a.task_data.task_id).length && ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide()), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                else {
                                                    if (1 == a.is_div_valid) {
                                                        var c = a.task_data.task_title;
                                                        if (c > 25) var d = c.substring(0, 22) + "...";
                                                        else var d = c;
                                                        var e = a.task_status_name;
                                                        status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                        var f = "";
                                                        if (f += '<tr id="teamtodo_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a></td>" : '<a  data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>", f += '<td class="teamdoDueDatepicker" id="teamDoDue_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="teamSchedulledDatepicker" id="teamSchedulled_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<td><span class="label label-' + status_class + '">' + e + "</span></td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teamtodo_" + a.task_data.task_id).length) $("#teamtodo_" + a.task_data.task_id).replaceWith(f), $(".teamdoDueDatepicker").datepicker({
                                                            startDate: -(1 / 0),
                                                            format: JAVASCRIPT_DATE_FORMAT
                                                        }).on("changeDate", function(a) {
                                                            $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                        }), $(".teamSchedulledDatepicker").datepicker({
                                                            startDate: -(1 / 0),
                                                            format: JAVASCRIPT_DATE_FORMAT
                                                        }).on("changeDate", function(a) {
                                                            $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                        });
                                                        else {
                                                            if ($("#teamtodolist tr td.dataTables_empty").length && $("#teamtodolist tr td.dataTables_empty").remove(), $("#teamtodolist").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                            else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                            var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                                            $(h).attr("id", "teamtodo_" + a.task_data.task_id), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(2)").addClass("teamdoDueDatepicker"), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(2)").attr("id", "teamDoDue_" + a.task_data.task_id), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(3)").addClass("teamSchedulledDatepicker"), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(3)").attr("id", "teamSchedulled_" + a.task_data.task_id), $(".teamdoDueDatepicker").datepicker({
                                                                startDate: -(1 / 0),
                                                                format: JAVASCRIPT_DATE_FORMAT
                                                            }).on("changeDate", function(a) {
                                                                $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                            }), $(".teamSchedulledDatepicker").datepicker({
                                                                startDate: -(1 / 0),
                                                                format: JAVASCRIPT_DATE_FORMAT
                                                            }).on("changeDate", function(a) {
                                                                $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                            })
                                                        }
                                                    } else $("#teamtodo_" + a.task_data.task_id).length && ($("#teamtodo_" + a.task_data.task_id).remove(), $("#teamtodo_" + a.task_data.task_id).hide()), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                    if (a.today_date) {
                                                        var c = a.task_data.task_title;
                                                        var cname = a.task_data.customer_name;
                                                        if (cname) var d = c+' ('+cname+')';
                                                        else var d = c;
                                                        var e = a.task_status_name;
                                                        status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                        var f = "";
                                                        if (f += '<tr id="teampending_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>",f +='<td><span class="label label-sm label-' + status_class + '">' + e + '</span></td>' ,f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += "<td>" ,f += a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>',f += "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teampending_" + a.task_data.task_id).length) $("#teampending_" + a.task_data.task_id).replaceWith(f);
                                                        else {
                                                            if ($("#teampending_list tr td.dataTables_empty").length && $("#teampending_list tr td.dataTables_empty").remove(), $("#teampending_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                            else var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                            var h = $("#filtertab2").dataTable().fnGetNodes(g);
                                                            $(h).attr("id", "teampending_" + a.task_data.task_id)
                                                        }
                                                    } else $("#teampending_" + a.task_data.task_id).length && ($("#teampending_" + a.task_data.task_id).remove(), $("#teampending_" + a.task_data.task_id).hide()), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                    if (a.today_date > a.strtotime_due_date) {
                                                        var c = a.task_data.task_title;
                                                        if (c.length > 25) var d = c.substring(0, 22) + "...";
                                                        else var d = c;
                                                        var e = a.task_status_name;
                                                        status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                        var f = "";
                                                        if (f += '<tr id="teamoverdue_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>", f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += "<td>" + a.delay + "</td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teamoverdue_" + a.task_data.task_id).length) "1" != a.task_data.is_personal ? $("#teamoverdue_" + a.task_data.task_id).replaceWith(f) : ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide());
                                                        else {
                                                            if ($("#teamoverdue_list tr td.dataTables_empty").length && $("#teamoverdue_list tr td.dataTables_empty").remove(), $("#teamoverdue_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.task_data.task_priority]);
                                                            else var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.task_data.task_priority]);
                                                            var h = $("#filtertab3").dataTable().fnGetNodes(g);
                                                            $(h).attr("id", "teamoverdue_" + a.task_data.task_id)
                                                        }
                                                    } else $("#teamoverdue_" + a.task_data.task_id).length && ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide()), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
                                                }
                                        else $("#teamtodo_" + a.task_data.task_id).length && ($("#teamtodo_" + a.task_data.task_id).remove(), $("#teamtodo_" + a.task_data.task_id).hide()), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teampending_" + a.task_data.task_id).length && ($("#teampending_" + a.task_data.task_id).remove(), $("#teampending_" + a.task_data.task_id).hide()), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teamoverdue_" + a.task_data.task_id).length && ($("#teamoverdue_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).hide()), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                        else {
                                            if ($("#watch" + a.task_data.task_id).length) {
                                                var e = a.task_status_name;
                                                status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                var i = "";
                                                i += '<tr id="watch' + a.task_data.task_id + '" role="row" class="odd">', i += '<td title="' + a.task_data.task_description + '" class="sorting_1">', i += "1" == a.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\')" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a></td>", i += "</td>", i += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", i += '<td class="hidden-480">' , i +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', i +=  "</td>", i += '<td><span class="label label-' + status_class + '">' + e + "</span></td>", i += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\'' + a.watch_id + "','" + a.task_data.task_id + '\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>', $("#watch" + a.task_data.task_id).replaceWith(i)
                                            }
                                            if ($("#last_login_" + a.task_data.task_id).length) {
                                                var j = "";
                                                j += '<tr id="last_login_' + a.task_data.task_id + '" role="row" class="odd">', j += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips dashboard_master_' + a.task_data.master_task_id + '" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\')" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips dashboard_master_' + a.task_data.master_task_id + '" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a></td>", j += '</td><td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", j += "<td>" + a.task_data.task_priority + "</td>", j += "</tr>", $("#last_login_" + a.task_data.task_id).replaceWith(j)
                                            }
                                            if (a.task_data.task_status_id != COMPLETED_ID)
                                                if ("assign_other" == a.assign_status) $("#todo_" + $("#task_id").val()).remove(), $("#todo_" + $("#task_id").val()).hide(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#last_login_" + $("#task_id").val()).remove(), $("#teamoverdue_" + $("#task_id").val()).remove(), $("#teampending_" + $("#task_id").val()).remove(), $("#teamtodo_" + $("#task_id").val()).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                                else if (1 == a.is_div_valid) {
                                                var c = a.task_data.task_title;
                                                if (c > 40) var d = c.substring(0, 37) + "...";
                                                else var d = c;
                                                var e = a.task_status_name;
                                                status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                var f = "";
                                                if (f += '<tr id="todo_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '" class="sorting_1">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" data-dismiss="modal" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)" >' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(c) + "</a></td>", f += '<td class="todoDueDatepicker" id="toDoDue_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="todoSchedulledDatepicker" id="schedulled_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<td><span class="label label-' + status_class + '">' + e + '</span></td><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" /></tr>', $("#todo_" + a.task_data.task_id).length) $("#todo_" + a.task_data.task_id).replaceWith(f), $(".todoSchedulledDatepicker").datepicker({
                                                    startDate: -(1 / 0),
                                                    format: JAVASCRIPT_DATE_FORMAT
                                                }).on("changeDate", function(a) {
                                                    $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                }), $(".todoDueDatepicker").datepicker({
                                                    startDate: -(1 / 0),
                                                    format: JAVASCRIPT_DATE_FORMAT
                                                }).on("changeDate", function(a) {
                                                    $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                });
                                                else {
                                                    if ($("#todolist tr td.dataTables_empty").length && $("#todolist tr td.dataTables_empty").remove(), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                    else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                                    var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                                    $(h).attr("id", "todo_" + a.task_data.task_id), $("#todo_" + a.task_data.task_id + " td:nth-child(2)").addClass("todoDueDatepicker"), $("#todo_" + a.task_data.task_id + " td:nth-child(2)").attr("id", "toDoDue_" + a.task_data.task_id), $("#todo_" + a.task_data.task_id + " td:nth-child(3)").addClass("todoSchedulledDatepicker"), $("#todo_" + a.task_data.task_id + " td:nth-child(3)").attr("id", "schedulled_" + a.task_data.task_id), $(".todoSchedulledDatepicker").datepicker({
                                                        startDate: -(1 / 0),
                                                        format: JAVASCRIPT_DATE_FORMAT
                                                    }).on("changeDate", function(a) {
                                                        $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                    }), $(".todoDueDatepicker").datepicker({
                                                        startDate: -(1 / 0),
                                                        format: JAVASCRIPT_DATE_FORMAT
                                                    }).on("changeDate", function(a) {
                                                        $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                                    })
                                                }
                                            } else $("#todo_" + a.task_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                            else $("#todo_" + a.task_data.task_id).length && $("#todo_" + a.task_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
                                            if (a.today_date) {
                                                var c = a.task_data.task_title;
                                                var cname = a.task_data.customer_name;
                                                if (cname) var d = c+' ('+cname+')';
                                                else var d = c;
                                                var e = a.task_status_name;
                                                status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                                var f = "";
                                                if (f += '<tr id="teampending_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>",f +='<td><span class="label label-sm label-' + status_class + '">' + e + '</span></td>' ,f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += "<td>" ,f += a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>',f += "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teampending_" + a.task_data.task_id).length) $("#teampending_" + a.task_data.task_id).replaceWith(f);
                                                else {
                                                    if ($("#teampending_list tr td.dataTables_empty").length && $("#teampending_list tr td.dataTables_empty").remove(), $("#teampending_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab5").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + '<span class="label label-sm label-' + status_class + '">' + e + '</span>',a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                    else var g = $("#filtertab5").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" +'<span class="label label-sm label-' + status_class + '">' + e + '</span>', a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                    var h = $("#filtertab5").dataTable().fnGetNodes(g);
                                                    $(h).attr("id", "teampending_" + a.task_data.task_id)
                                                }
                                            } else $("#teampending_" + a.task_data.task_id).length && $("#teampending_" + a.task_data.task_id).remove(), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                        }
                                        "from_teamdashboard" == $("#redirect_page").val() ? ($.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/teamdashcharttime",
                                            data: {
                                                mytask: TEAM_MY_TASK,
                                                teamtask: TEAM_TASK
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $(".ajax_team_time_data").html(a), google.load("visualization", "1", {
                                                    packages: ["corechart"],
                                                    callback: drawChart
                                                })
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }), $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/teamdashchartcategory",
                                            data: {
                                                taskByCat_tot: TASK_BY_CAT_TOT
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $(".ajax_team_category_data").html(a), google.load("visualization", "1", {
                                                    packages: ["corechart"],
                                                    callback: drawChartcat
                                                })
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }), $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/taskteam_previousweek",
                                            data: {
                                                user_id: LOG_USER_ID
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $("#sortableItem_3").html(a)
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        })) : "from_dashboard" == $("#redirect_page").val() && ($.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/dashboardchart",
                                            data: {
                                                none: DASHBOARD_NONE,
                                                low: DASHBOARD_LOW,
                                                medium: DASHBOARD_MEDIUM,
                                                high: DASHBOARD_HIGH
                                            },
                                            async: !1,
                                            success: function(a) {
                                                AmCharts.isReady = !0, $(".ajax_category_data").html(""), $(".ajax_category_data").html(a)
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }), $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "user/task_previousweek",
                                            data: {
                                                user_id: LOG_USER_ID
                                            },
                                            async: !1,
                                            success: function(a) {
                                                $("#sortableItem_3").html(a)
                                            },
                                            error: function(a) {
                                                console.log("Ajax request not recieved!")
                                            }
                                        }))
                                    }
                                })
                            } else if ("from_project" == b) {
                                var g = $("#select_task_assign").val(),
                                    h = $("#select_task_status").val();
                                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    data: {
                                        task_id: c.task_id,
                                        type: h,
                                        user_id: g
                                    },
                                    async: !1,
                                    success: function(a) { 
                                        App.init(), a ? ($("#task_tasksort_" + c.task_id).length && $("#task_tasksort_" + c.task_id).remove(), 0 == $("#task_tasksort_" + c.task_id + " ul").length && ("0" != c.section_id ? $("#taskmove_" + c.subsection_id + "_" + c.section_id).append(a) : $("#panel-body1_" + c.subsection_id + " div.task_tasksort").length ? $("#panel-body1_" + c.subsection_id + " div.task_tasksort:last").after(a) : $("#panel-body1_" + c.subsection_id + " div.add_new_task_div").before(a))) : $("#task_tasksort_" + c.task_id).remove(), $("#typefilter1 li").removeClass("active"), $("#typefilter1 li[id=" + h + "]").addClass("active")
                                    }
                                })
                            }
                        })
                    }
                }), "from_kanban" == b) {
                if (1 == $("#recurrence").prop("checked")) var f = "child_" + $("#task_id").val();
                else var f = $("#task_id").val();
                "0" != $("#master_task_id").val() && ($("#task_status_id").val() == a ? $.ajax({
                    type: "post",
                    url: SIDE_URL + "kanban/next_noncompleted_recurrence",
                    data: {
                        task_id: $("#master_task_id").val()
                    },
                    async: !1,
                    success: function(a) {
                        a && (a = jQuery.parseJSON(a), $.ajax({
                            type: "post",
                            url: SIDE_URL + "kanban/set_update_task",
                            data: {
                                task_id: a.task_id,
                                color_menu : $("#kanban_color_menu").val()
                            },
                            async: !1,
                            success: function(b) { 
                                $("#main_" + a.task_id).length && $("#main_" + a.task_id).remove(), $("#task_status_" + a.task_status_id + "_" + a.swimlane_id).prepend(b)
                            }
                        }))
                    }
                }) : $("#old_task_status_id").val() == a && $(".kanban_master_" + $("#master_task_id").val()).remove()), "0" != $("#prerequisite_task_id").val() && ($("#task_status_id").val() == a ? $.ajax({
                    type: "post",
                    url: SIDEURL + "kanban/check_completed_dependency",
                    data: {
                        task_id: $("#prerequisite_task_id").val()||'0'
                    },
                    success: function(a) {
                        a && (a = jQuery.parseJSON(a), $.ajax({
                            type: "post",
                            url: SIDEURL + "kanban/set_update_task",
                            data: {
                                task_id: $("#prerequisite_task_id").val()|| '0',
                                color_menu : $("#kanban_color_menu").val()
                            },
                            success: function(b) { 
                                a.main_task_status_id == a.task_status_id ? $("#main_" + $("#prerequisite_task_id").val()).length && ($("#main_" + $("#prerequisite_task_id").val()).replaceWith(b), "red" == a.completed_depencencies ? $("#up_status_" + $("#prerequisite_task_id").val()).find("input").attr("disabled", "disabled") : $("#up_status_" + $("#prerequisite_task_id").val()).find("input").removeAttr("disabled", "disabled")) : ($("#task_status_" + a.task_status_id + "_" + $("#task_swimlane_id").val()).prepend(b), $("#main_" + $("#prerequisite_task_id").val()).remove(), "red" == a.completed_depencencies ? $("#up_status_" + $("#prerequisite_task_id").val()).find("input").attr("disabled", "disabled") : $("#up_status_" + $("#prerequisite_task_id").val()).find("input").removeAttr("disabled", "disabled"))
                            }
                        }))
                    }
                }) : $.ajax({
                    type: "post",
                    url: SIDEURL + "kanban/check_completed_dependency",
                    data: {
                        task_id: $("#prerequisite_task_id").val()||'0'
                    },
                    success: function(a) {
//                        a && (a = jQuery.parseJSON(a), $.ajax({
//                            type: "post",
//                            url: SIDEURL + "kanban/set_update_task",
//                            data: {
//                                task_id: $("#prerequisite_task_id").val()||'0',
//                                color_menu : $("#kanban_color_menu").val()
//                            },
//                            success: function(b) { alert("5")
//                                a.main_task_status_id == a.task_status_id ? $("#main_" + $("#prerequisite_task_id").val()).length && ($("#main_" + $("#prerequisite_task_id").val()).replaceWith(b), "red" == a.completed_depencencies ? $("#up_status_" + $("#prerequisite_task_id").val()).find("input").attr("disabled", "disabled") : $("#up_status_" + $("#prerequisite_task_id").val()).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + $("#prerequisite_task_id").val()).remove(), $("#task_status_" + a.task_status_id + "_" + $("#task_swimlane_id").val()).prepend(b), "red" == a.completed_depencencies ? $("#up_status_" + $("#prerequisite_task_id").val()).find("input").attr("disabled", "disabled") : $("#up_status_" + $("#prerequisite_task_id").val()).find("input").removeAttr("disabled", "disabled"))
//                            }
//                        }))
                    }
                })), $.ajax({
                    type: "post",
                    url: SIDE_URL + "kanban/set_update_task",
                    data: {
                        task_id: f,
                        color_menu : $("#kanban_color_menu").val()
                    },
                    async: !1, 
                    success: function(b) { 
                        if (f.indexOf("child") >= 0 && $("#main_" + f).remove(), "series" == $("#from").val());
                        else if (b) { 
                            if ($("#task_swimlane_id").val()) var c = $("#task_swimlane_id").val();
                            else var c = $("#genral_swimlane_id").val();
                            if ($("#old_task_status_id").val() != $("#task_status_id").val()) { 
                                $("#main_" + f).remove();
                                var d = $("#status_time_" + $("#task_status_id").val()).html(),
                                    e = $("#status_time_" + $("#task_status_id").val() + " .hrlft").html(),
                                    g = $("#status_time_" + $("#task_status_id").val() + " .hrrlt").html();
                                if (d) var h = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrlft").html()),
                                    i = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrrlt").html());
                                else var h = "0",
                                    i = "0";
                                var j = 60 * parseInt($("#old_task_time_spent_hour").val()) + parseInt($("#old_task_time_spent_min").val()),
                                    k = 60 * parseInt($("#old_task_time_estimate_hour").val()) + parseInt($("#old_task_time_estimate_min").val());
                                if ($("#task_time_estimate_hour").val() != $("#old_task_time_estimate_hour").val() || $("#task_time_estimate_min").val() != $("#old_task_time_estimate_hour").val()) {
                                    var l = 60 * parseInt($("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val()),
                                        h = parseInt(h) - parseInt(k),
                                        e = hoursminutes(parseInt(h) + parseInt(l));
                                    $("#old_task_time_estimate_hour").val($("#task_time_estimate_hour").val()), $("#old_task_time_estimate_min").val($("#task_time_estimate_min").val())
                                }
                                if ($("#task_time_spent_hour").val() != $("#old_task_time_spent_hour").val() || $("#task_time_spent_min").val() != $("#old_task_time_spent_min").val()) {
                                    var m = 60 * parseInt($("#task_time_spent_hour").val()) + parseInt($("#task_time_spent_min").val());
                                    i = parseInt(i) - parseInt(j);
                                    var g = hoursminutes(parseInt(i) + parseInt(m));
                                    $("#old_task_time_spent_hour").val($("#task_time_spent_hour").val()), $("#old_task_time_spent_min").val($("#task_time_spent_min").val())
                                }
                                var n = "<span class='hrlft tooltips' id='Estimate_time_" + $("#task_status_id").val() + "' data-original-title='Estimate Time'>" + e + "</span><span class='hrrlt tooltips' id='spent_time_" + $("#task_status_id").val() + "' data-original-title='Spent Time'>" + g + "</span>";
                                if ($("#status_time_" + $("#task_status_id").val()).html(n), a == $("#task_status_id").val()) {
                                    var o = $("#completed_loadMore_limit" + $("#task_status_id").val() + c).val(),
                                        p = parseInt(o) + parseInt("1");
                                    $("#completed_loadMore_limit" + $("#task_status_id").val() + c).val(p)
                                }
                                $("#task_status_" + $("#task_status_id").val() + "_" + c).prepend(b)
                            } else if ($("#task_id").val() == $("#old_task_id").val())
                                if (0 == $("#main_" + $("#task_id").val() + " .dragbox").length) { 
                                    $("#task_status_" + $("#task_status_id").val() + "_" + c).prepend(b);
                                    var d = $("#status_time_" + $("#task_status_id").val()).html(),
                                        e = $("#status_time_" + $("#task_status_id").val() + " .hrlft").html(),
                                        g = $("#status_time_" + $("#task_status_id").val() + " .hrrlt").html();
                                    if (d) var h = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrlft").html()),
                                        i = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrrlt").html());
                                    else var h = "0",
                                        i = "0";
                                    var j = 60 * parseInt($("#old_task_time_spent_hour").val()) + parseInt($("#old_task_time_spent_min").val()),
                                        k = 60 * parseInt($("#old_task_time_estimate_hour").val()) + parseInt($("#old_task_time_estimate_min").val());
                                    if ($("#task_time_estimate_hour").val() != $("#old_task_time_estimate_hour").val() || $("#task_time_estimate_min").val() != $("#old_task_time_estimate_hour").val()) {
                                        var l = 60 * parseInt($("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val()),
                                            h = parseInt(h) - parseInt(k),
                                            e = hoursminutes(parseInt(h) + parseInt(l));
                                        $("#old_task_time_estimate_hour").val($("#task_time_estimate_hour").val()), $("#old_task_time_estimate_min").val($("#task_time_estimate_min").val())
                                    }
                                    if ($("#task_time_spent_hour").val() != $("#old_task_time_spent_hour").val() || $("#task_time_spent_min").val() != $("#old_task_time_spent_min").val()) {
                                        var m = 60 * parseInt($("#task_time_spent_hour").val()) + parseInt($("#task_time_spent_min").val());
                                        i = parseInt(i) - parseInt(j);
                                        var g = hoursminutes(parseInt(i) + parseInt(m));
                                        $("#old_task_time_spent_hour").val($("#task_time_spent_hour").val()), $("#old_task_time_spent_min").val($("#task_time_spent_min").val())
                                    }
                                    var n = "<span class='hrlft tooltips' id='Estimate_time_" + $("#task_status_id").val() + "' data-original-title='Estimate Time'>" + e + "</span><span class='hrrlt tooltips' id='spent_time_" + $("#task_status_id").val() + "' data-original-title='Spent Time'>" + g + "</span>";
                                    if ($("#status_time_" + $("#task_status_id").val()).html(n), a == $("#task_status_id").val()) {
                                        var o = $("#completed_loadMore_limit" + $("#task_status_id").val() + c).val(),
                                            p = parseInt(o) + parseInt("1");
                                        $("#completed_loadMore_limit" + $("#task_status_id").val() + c).val(p)
                                    }
                                } else $("#genral_swimlane_id").val() != c ? ($("#main_" + $("#task_id").val()).remove(), $("#task_status_" + $("#task_status_id").val() + "_" + c).prepend(b)) : $("#main_" + $("#task_id").val()).replaceWith(b);
                            else if ($("#old_task_id").val()) $("#main_" + $("#old_task_id").val()).replaceWith(b);
                            else { 
                                $("#task_status_" + $("#task_status_id").val() + "_" + c).prepend(b);
                                var d = $("#status_time_" + $("#task_status_id").val()).html(),
                                    e = $("#status_time_" + $("#task_status_id").val() + " .hrlft").html(),
                                    g = $("#status_time_" + $("#task_status_id").val() + " .hrrlt").html();
                                if (d) var h = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrlft").html()),
                                    i = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrrlt").html());
                                else var h = "0",
                                    i = "0";
                                var j = 60 * parseInt($("#old_task_time_spent_hour").val()) + parseInt($("#old_task_time_spent_min").val()),
                                    k = 60 * parseInt($("#old_task_time_estimate_hour").val()) + parseInt($("#old_task_time_estimate_min").val());
                                if ($("#task_time_estimate_hour").val() != $("#old_task_time_estimate_hour").val() || $("#task_time_estimate_min").val() != $("#old_task_time_estimate_hour").val()) {
                                    var l = 60 * parseInt($("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val()),
                                        h = parseInt(h) - parseInt(k),
                                        e = hoursminutes(parseInt(h) + parseInt(l));
                                    $("#old_task_time_estimate_hour").val($("#task_time_estimate_hour").val()), $("#old_task_time_estimate_min").val($("#task_time_estimate_min").val())
                                }
                                if ($("#task_time_spent_hour").val() != $("#old_task_time_spent_hour").val() || $("#task_time_spent_min").val() != $("#old_task_time_spent_min").val()) {
                                    var m = 60 * parseInt($("#task_time_spent_hour").val()) + parseInt($("#task_time_spent_min").val());
                                    i = parseInt(i) - parseInt(j);
                                    var g = hoursminutes(parseInt(i) + parseInt(m));
                                    $("#old_task_time_spent_hour").val($("#task_time_spent_hour").val()), $("#old_task_time_spent_min").val($("#task_time_spent_min").val())
                                }
                                var n = "<span class='hrlft tooltips' id='Estimate_time_" + $("#task_status_id").val() + "' data-original-title='Estimate Time'>" + e + "</span><span class='hrrlt tooltips' id='spent_time_" + $("#task_status_id").val() + "' data-original-title='Spent Time'>" + g + "</span>";
                                if ($("#status_time_" + $("#task_status_id").val()).html(n), a == $("#task_status_id").val()) {
                                    var o = $("#completed_loadMore_limit" + $("#task_status_id").val() + c).val(),
                                        p = parseInt(o) + parseInt("1");
                                    $("#completed_loadMore_limit" + $("#task_status_id").val() + c).val(p)
                                }
                            }
                        } else if ($("#task_allocated_user_id").val() == $("#kanban_team_user_id").val()) { 
                            var d = $("#status_time_" + $("#task_status_id").val()).html();
                            if (d) var h = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrlft").html()),
                                i = get_minutes($("#status_time_" + $("#task_status_id").val() + " .hrrlt").html());
                            else var h = "0",
                                i = "0";
                            var l = parseInt(60 * parseInt($("#task_time_estimate_hour").val())) + parseInt($("#task_time_estimate_min").val()),
                                m = parseInt(60 * parseInt($("#task_time_spent_hour").val())) + parseInt($("#task_time_spent_min").val()),
                                e = hoursminutes(parseInt(h) - parseInt(l)),
                                g = hoursminutes(parseInt(i) - parseInt(m)),
                                n = "<span class='hrlft tooltips' data-original-title='Estimate Time'>" + e + "</span><span class='hrrlt tooltips' data-original-title='Spent Time'>" + g + "</span>",
                                q = $("#task_count_hide_" + $("#task_status_id").val() + "_" + c).html();
                            $("#task_count_hide_" + $("#task_status_id").val() + "_" + c).html(parseInt(q) - 1), $("#status_time_" + $("#task_status_id").val()).html(n)
                        } else $("#main_" + f).remove();
                        $("body").tooltip({
                            selector: ".tooltips"
                        })
                    }
                })
            } else if ("weekView" == b || "NextFiveDay" == b) "series" == $("#from").val() && $(".week_master_" + $("#task_id").val()).parent("div").map(function() {
                var active_menu = b;
            var a = this.id;
                $("#" + a).children("div").each(function() {
                    $("div[id^=main_child_" + $("#task_id").val() + "_]") && $("div[id^=main_child_" + $("#task_id").val() + "_]").remove()
                }), a = a.replace("week_", "");
								
            }).get(), 1 == $("#recurrence").prop("checked") ? $.ajax({
                type: "post",
                url: SIDE_URL + "calendar/set_weekly_update_div_for_task",
                data: {
                    task_id: $("#task_id").val(),
                    start_date: $("#week_start_date").val(),
                    end_date: $("#week_end_date").val(),
                    action: $("#week_action").val(),
                    active_menu: $("#redirect_page").val(),
                    color_menu :$("#task_color_menu").val()
                },
                async: !1,
                success: function(b) { 
                    if(!oe)
                        oe="0";
                    if(!os)
                        os="0";
                    if (b = jQuery.parseJSON(b), $("#main_" + $("#task_id").val()).length) { 
                        var e1 = $("#est_"+$("#strtotime_scheduled_date").val()).attr('data-time');
                         var s1 = $("#spent_"+$("#strtotime_scheduled_date").val()).attr('data-time');
                        var e = $("#task_time_" + $("#task_id").val()).html();
                        if (e) var f = e.split("/"),
                            g = get_minutes(f[0]),
                            h = get_minutes(f[1]);
                        else var g = "0",
                            h = "0";
                        var e1 = parseInt(e1) - parseInt(g),
                            s1 = parseInt(s1) - parseInt(h),
                            c1 = parseInt($("#capacity_" + $("#strtotime_scheduled_date").val()).attr('data-time'));
			 $('#progress_'+$("#strtotime_scheduled_date").val()).empty(), $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "calendar/update_progress_bar",
                                    data: {
                                        id: $("#strtotime_scheduled_date").val(),
                                        capacity: c1,
                                        estimate_time: e1,
                                        spent_time: s1,
                                        title: 'Capacity: '+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(e1)+'<br>Time Spent: '+hoursminutes(s1)
                                    },
                                    success: function(progress) {
                                        $('#progress_'+$("#strtotime_scheduled_date").val()).html(progress)
                                    }
                                });
				$("#main_" + $("#task_id").val()).remove();
                    } 
                    var first_name = b.first_name;
                    var last_name = b.last_name;
                    var image_url = b.image;
                    b && $.map(b, function(b) { 
                        setTimeout(function(){if ($("#week_" + b.div_id).length) {
                            var c = "";
                            "0" == b.re_data.task_time_estimate && "0" == b.re_data.task_time_spent && (c = "display:none;");
                            var d = hoursminutes(b.re_data.task_time_estimate),
                                e = hoursminutes(b.re_data.task_time_spent),
                                f = (b.strtotime_scheduled_date, ACTIVE_MENU),
                                g = b.strtotime_start_date,
                                h = b.strtotime_end_date,
                                i = "";
                            i = "Low" == b.re_data.task_priority ? "green1" : "Medium" == b.re_data.task_priority ? "yellow1" : "High" == b.re_data.task_priority ? "red1" : "";
                            var j = "";
                            j = "1" == b.is_completed ? 'class="checked"' : "";
                            var k = b.task_status_name,
                                l = "";
                            l += '<div class="task_div week_master_' + b.re_data.master_task_id + " " + i + '  before_timer" id="main_' + b.re_data.task_id + '" onclick="save_task_for_timer(this,\'' + b.re_data.task_id + "','" + b.re_data.task_title + "','" + b.re_data.task_time_spent + "','0','" + b.re_data.completed_depencencies + "');\">",
                            l += "<div oncontextmenu=\"context_menu('"+b.context_menu + "')\">",
                            l += "<style>#main_" + b.re_data.task_id + " .comm-box.whitebox .comm-title, #main_" + b.re_data.task_id + " .comm-box.whitebox .comm-desc,#main_" + b.re_data.task_id + " .comm-box.whitebox .com-brdbtm,#main_" + b.re_data.task_id + " .comm-box.whitebox .commicon-list{border-bottom:1px dashed " + b.outside_color_code + ";}</style>",
                            l += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + b.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + b.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + b.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + b.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + b.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + b.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + b.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + b.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;0&quot;,&quot;subsection_order&quot;:&quot;0&quot;,&quot;task_order&quot;:&quot;0&quot;,&quot;task_title&quot;:&quot;" + b.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + b.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + b.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + b.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + b.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + b.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + b.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + b.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + b.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + b.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + b.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + b.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + b.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + b.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + b.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + b.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + b.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + b.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + b.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + b.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + b.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + b.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + b.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + b.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + b.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + b.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + b.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + b.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + b.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + b.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + b.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + b.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + b.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + b.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + b.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + b.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + b.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + b.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + b.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + b.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + b.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + b.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + b.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + b.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + b.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + b.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + b.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + b.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + b.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + b.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + b.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + b.re_data.is_deleted + "&quot;,&quot;swimlane_id&quot;:&quot;" + b.re_data.swimlane_id + "&quot;,&quot;color_id&quot;:&quot;" + b.re_data.color_id + "&quot;,&quot;kanban_order&quot;:&quot;" + b.re_data.kanban_order + "&quot;,&quot;calender_order&quot;:&quot;" + b.re_data.calender_order + "&quot;,&quot;task_ex_pos&quot;:&quot;" + b.re_data.task_ex_pos + "&quot;,&quot;cost_per_hour&quot;:&quot;" + b.re_data.cost_per_hour + "&quot;,&quot;cost&quot;:&quot;" + b.re_data.cost + "&quot;,&quot;charge_out_rate&quot;:&quot;" + b.re_data.charge_out_rate + "&quot;,&quot;estimated_total_charge&quot;:&quot;" + b.re_data.estimated_total_charge + "&quot;,&quot;actual_total_charge&quot;:&quot;" + b.re_data.actual_total_charge + "&quot;,&quot;customer_id&quot;:&quot;" + b.re_data.customer_id +'&quot;}" id="task_data_' + b.re_data.task_id + '">',
                            l += '<input type="hidden" value="' + b.div_id + '" id="hdn_due_date_' + b.re_data.task_id + '">', 
                            l += '<input type="hidden" value="' + b.re_data.locked_due_date + '" id="hdn_locked_due_date_' + b.re_data.task_id + '">',
                            l += '<input type="hidden" value="' + b.outside_color_code + '" name="or_color_id" id="or_color_' + b.re_data.task_id + '">',
                            l += '<input type="hidden" value="' + b.re_data.task_time_estimate + '" name="task_estimate_time" id="task_estimate_time_' + b.re_data.master_task_id + '">',
                            l += '<input type="hidden" value="' + b.re_data.task_time_spent + '" name="task_spent_time" id="task_spent_time_' + b.re_data.master_task_id + '">',
                            l += '<div style="border : solid 1px ' + b.outside_color_code + ';" id="task_' + b.re_data.task_id + '" class="dragbox before_timer"><div style="background-color: ' + b.color_code + '" class="comm-box whitebox disabled_sort before_timer">', 
                            l += '<a href="javascript:void(0)" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + "');\" >",
                            l += '<div class="comm-title clearfix before_timer"><div class="comtitle-LFD before_timer">';
                             if(b.re_data.task_owner_id != b.re_data.task_allocated_user_id){
                            l += '<img class="tooltips profile-image_task" data-placement="left" data-original-title="'+first_name+' '+last_name+'" alt="" src="'+image_url+'" />   ';
                             }
                             l += '<div id="task_time_' + b.re_data.task_id + '" class="comttime before_timer" style="' + c + '"> ' + d + "/" + e + "</div>";
                            if(b.re_data.project_title){
                            l += b.re_data.project_title +' - '+ b.re_data.task_title + "</div></div></a>";
                            }else{
                            l += b.re_data.task_title + "</div></div></a>";
                            }
                            l += '<div style="display:none;" id="expand_div_' + b.re_data.task_id + '" class="before_timer"><div class="comm-desc"><p> '+ b.re_data.task_description +' </p></div><div class="duedate com-brdbtm before_timer"><div class="before_timer"> Due : ' + b.user_due_date + "  </div>";
                            if(b.steps){
                            l += '<div style="border-top:1px dashed #e5e9ec;margin-top:7px;font-size:12px !important;"><div class="comm-step before_timer" style="margin-top: 6px;"><div class="form-group before_timer">';
                            var t_steps = 0;
                            var c_steps = 0;
                             $.each(b.steps, function(i, item) {
                                 t_steps++;
                               var  stp_cl = '';
                               var check='';
					if(b.steps[i].is_completed == '1'){
                                            c_steps++;
						stp_cl = 'step-complete-class';
                                                check="checked";
						}
                             l += '<label class="checkbox step_change '+ stp_cl+'" id="step_class_'+ b.steps[i].task_step_id+'">';
			     l += '<div class="checker "><span class="'+check+'"><input type="checkbox" name="step_chk"  onclick="chek_step(\''+ b.steps[i].task_step_id+ "','"+b.re_data.task_id+"');\" ",
			     l += 'value="'+ b.steps[i].task_step_id+'" checked="'+check+'"></span></div>'+b.steps[i].step_title;
			     l += '</label>';
                                });
                            l += "</div></div></div>";
                            }
                            l +="</div></div>";
                            l += '<div class="commicon-list clearfix before_timer"> <ul class="unstyled">';
                            if(b.re_data.frequency_type == 'one_off'){
                            l += '<li class="no-bottom-space"><a href="javascript:void(0);" data-placement="right" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + "','task_tab_5');\" ",
                            l += 'data-original-title="Task disconnected from series" class="tooltips" ><i class="nonrecurring_icon"> </i></a></li>';    
                            }else{
                            l += '<li class="no-bottom-space"><a href="javascript:void(0);" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + "','task_tab_5');\"  data-placement='right' data-original-title='Recurring task' class='tooltips' ><i class='icon-refresh wvicn'> </i></a></li>";
                            }
                            if(b.re_data.comments != '0'){
                                l += '<li class="no-bottom-space"><a href="javascript:void(0);" data-placement="right" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + "','task_tab_7');\" ",
                                        l+='data-original-title="Comments" class="tooltips" ><i class="icon-comment-alt wvicn"> </i><sup>'+b.re_data.comments+'</sup></a></li>';
                            }
                            if(b.re_data.is_personal != '0'){
                                l += '<li class="no-bottom-space"><a href="javascript:void(0);" data-placement="right" data-original-title="Private task" class="tooltips" ><i class="icon-eye-slash wvicn"> </i></a></li>';
                            }
                            if(b.re_data.files != '0'){
                                l += '<li class="no-bottom-space"><a href="javascript:void(0);" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + "','task_tab_6');\" ",
                                        l +=  'data-placement="right" data-original-title="Task Files" class="tooltips" ><i class="icon-paperclip wvicn"> </i><sup>'+b.re_data.files+'</sup></a></li>';
                            }
                            if(b.re_data.ts != '0'){
                                l += '<li class="no-bottom-space"><a href="javascript:void(0);" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + "','task_tab_4');\" ",
                                        l += 'data-placement="right" data-original-title="Task Steps" class="tooltips" ><i class="icon-list-ul wvicn"> </i><sup><span id="stepcom_'+b.re_data.task_id+'" >'+c_steps+' </span>/'+t_steps+'</sup></a></li>';
                            }
                            l += '</ul></div>';
                            l += '<div class="commicon-list clearfix before_timer"> <ul class="unstyled">';
                            l += '<li><span class="label-status label-' + k.replace(/ /g, "") + '">' + k + "</span></li> ";
                            if(b.total_active_swimlane > 1){
                            l += '<li><span class="label-status label-Greylight" >' + b.swimlane_name + "</span></li> ";
                            }   
                            if(b.customer)
                            {
                                l += '<li><span class="label-status label-Greylight" >' + b.customer.customer_name + "</span></li> ";
                            }
                            l += '<li class="chkbox new no-bottom-space"> <a onclick="expand_div(\'' + b.re_data.task_id + "');task_ex_pos({&quot;task_id&quot;:&quot;" + b.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + b.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + b.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + b.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + b.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + b.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + b.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + b.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;0&quot;,&quot;subsection_order&quot;:&quot;0&quot;,&quot;task_order&quot;:&quot;0&quot;,&quot;task_title&quot;:&quot;" + b.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + b.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + b.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + b.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + b.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + b.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + b.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + b.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + b.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + b.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + b.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + b.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + b.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + b.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + b.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + b.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + b.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + b.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + b.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + b.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + b.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + b.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + b.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + b.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + b.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + b.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + b.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + b.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + b.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + b.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + b.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + b.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + b.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + b.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + b.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + b.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + b.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + b.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + b.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + b.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + b.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + b.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + b.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + b.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + b.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + b.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + b.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + b.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + b.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + b.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + b.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + b.re_data.is_deleted + "&quot;,&quot;swimlane_id&quot;:&quot;" + b.re_data.swimlane_id + "&quot;,&quot;color_id&quot;:&quot;" + b.re_data.color_id + "&quot;,&quot;kanban_order&quot;:&quot;" + b.re_data.kanban_order + "&quot;,&quot;calender_order&quot;:&quot;" + b.re_data.calender_order + "&quot;,&quot;task_ex_pos&quot;:&quot;" + b.re_data.task_ex_pos + "&quot;,&quot;cost_per_hour&quot;:&quot;" + b.re_data.cost_per_hour + "&quot;,&quot;cost&quot;:&quot;" + b.re_data.cost + "&quot;,&quot;charge_out_rate&quot;:&quot;" + b.re_data.charge_out_rate + "&quot;,&quot;estimated_total_charge&quot;:&quot;" + b.re_data.estimated_total_charge + "&quot;,&quot;actual_total_charge&quot;:&quot;" + b.re_data.actual_total_charge + "&quot;,&quot;customer_id&quot;:&quot;" + b.re_data.customer_id + '&quot;})" id="expand_div_symbol_' + b.re_data.task_id + '" href="javascript:void(0);">  <i class="icon-cstcompress"> </i>  </a> </li>', 
                            l += '<li id="up_status_' + b.re_data.task_id + '" class="chkbox new margin-bottom-3"><label class="checkbox"><div class="checker before_timer"><span ' + j + '><input type="checkbox" value="" onclick="update_status_complete({&quot;task_id&quot;:&quot;' + b.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + b.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + b.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + b.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + b.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + b.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + b.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + b.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;0&quot;,&quot;subsection_order&quot;:&quot;0&quot;,&quot;task_order&quot;:&quot;0&quot;,&quot;task_title&quot;:&quot;" + b.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + b.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + b.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + b.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + b.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + b.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + b.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + b.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + b.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + b.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + b.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + b.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + b.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + b.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + b.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + b.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + b.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + b.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + b.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + b.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + b.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + b.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + b.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + b.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + b.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + b.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + b.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + b.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + b.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + b.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + b.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + b.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + b.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + b.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + b.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + b.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + b.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + b.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + b.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + b.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + b.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + b.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + b.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + b.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + b.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + b.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + b.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + b.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + b.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + b.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + b.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + b.re_data.is_deleted + "&quot;,&quot;swimlane_id&quot;:&quot;" + b.re_data.swimlane_id + "&quot;,&quot;color_id&quot;:&quot;" + b.re_data.color_id + "&quot;,&quot;kanban_order&quot;:&quot;" + b.re_data.kanban_order + "&quot;,&quot;calender_order&quot;:&quot;" + b.re_data.calender_order + "&quot;,&quot;task_ex_pos&quot;:&quot;" + b.re_data.task_ex_pos + "&quot;,&quot;cost_per_hour&quot;:&quot;" + b.re_data.cost_per_hour + "&quot;,&quot;cost&quot;:&quot;" + b.re_data.cost + "&quot;,&quot;charge_out_rate&quot;:&quot;" + b.re_data.charge_out_rate + "&quot;,&quot;estimated_total_charge&quot;:&quot;" + b.re_data.estimated_total_charge + "&quot;,&quot;actual_total_charge&quot;:&quot;" + b.re_data.actual_total_charge + "&quot;,&quot;customer_id&quot;:&quot;" + b.re_data.customer_id + "&quot;},'" + a + "');\"></span></div></label></li>",
                            l += "</ul></div></div></div></div></div>";
                            
                            var e1 = $("#est_"+b.div_id).attr('data-time');
                            var s1 = $("#spent_"+b.div_id).attr('data-time');
							
                            var o = b.re_data.task_time_estimate,
                                p = b.re_data.task_time_spent;
                            var ee1 = parseInt(e1) + parseInt(o)-parseInt(oe),
                                    ss1 = parseInt(s1) + parseInt(p)-parseInt(os);
                            
                            $("#week_" + b.div_id).length ? 0 == $("#main_" + b.re_data.task_id).length ? $("#week_" + b.div_id).find("#add_newTask_"+b.div_id).before(l) : $("#main_" + b.re_data.task_id).replaceWith(l) : 1 == $("#week_" + b.div_id + " .task_div").length && $("#week_" + b.div_id + " .space").remove();
					
                            //alert(b.div_id);
                            
                                c1 = parseInt($("#capacity_" + b.div_id).attr('data-time'));
//                                if($("#strtotime_scheduled_date").val() != b.div_id){
                                if(ee1 == parseInt(e1) && ss1 == parseInt(s1)){}else if(ee1==0 && parseInt(e1)==0 && ss1==0 && parseInt(s1)==0){}else{
				$('#progress_'+b.div_id).empty(), $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "calendar/update_progress_bar",
                                    data: {
                                        id: b.div_id,
                                        capacity: c1,
                                        estimate_time: ee1,
                                        spent_time: ss1,
                                        title: 'Capacity: '+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(ee1)+'<br>Time Spent: '+hoursminutes(ss1)
                                    },
                                    success: function(progress) {
                                        $('#progress_'+b.div_id).html(progress)
                                    }
                                });
                            }
                        }},200)
                    }), $("body").tooltip({
                        selector: ".tooltips"
                    })
                }
            }) : $.ajax({
                type: "post",
                url: SIDE_URL + "calendar/set_weekly_update_task",
                data: {
                    task_id: $("#task_id").val(),
                    start_date: $("#week_start_date").val(),
                    end_date: $("#week_end_date").val(),
                    action: $("#week_action").val(),
                    active_menu: $("#redirect_page").val(),
                    color_menu :$("#task_color_menu").val()
                },
                async: !1,
                success: function(a) { 
                    if (App.init(), a) {
                       var c1 = parseInt($("#capacity_"+$("#strtotime_scheduled_date").val()).attr('data-time'));
			var e1 = parseInt($("#est_"+$("#strtotime_scheduled_date").val()).attr('data-time'));
			var s1 = parseInt($("#spent_"+$("#strtotime_scheduled_date").val()).attr('data-time'));
                        var g = 60 * parseInt($("#old_task_time_spent_hour").val()) + parseInt($("#old_task_time_spent_min").val()),
                            h = 60 * parseInt($("#old_task_time_estimate_hour").val()) + parseInt($("#old_task_time_estimate_min").val());
                        if ($("#task_time_estimate_hour").val() != $("#old_task_time_estimate_hour").val() || $("#task_time_estimate_min").val() != $("#old_task_time_estimate_hour").val()) {
                            var i = 60 * parseInt($("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
                            e1 = parseInt(e1) - parseInt(h), e1 = parseInt(e1) + parseInt(i), b = hoursminutes(d), $("#old_task_time_estimate_hour").val($("#task_time_estimate_hour").val()), $("#old_task_time_estimate_min").val($("#task_time_estimate_min").val())
                        }
                        if ($("#task_time_spent_hour").val() != $("#old_task_time_spent_hour").val() || $("#task_time_spent_min").val() != $("#old_task_time_spent_min").val()) {
                            var j = 60 * parseInt($("#task_time_spent_hour").val()) + parseInt($("#task_time_spent_min").val());
                            s1 = parseInt(s1) - parseInt(g), s1 = parseInt(s1) + parseInt(j), $("#old_task_time_spent_hour").val($("#task_time_spent_hour").val()), $("#old_task_time_spent_min").val($("#task_time_spent_min").val())
                        }
                        if($("#task_allocated_user_id").val() == $("#calender_team_user_id").val()){
                         $('#progress_'+$("#strtotime_scheduled_date").val()).empty(), $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "calendar/update_progress_bar",
                                    data: {
                                        id: $("#strtotime_scheduled_date").val(),
                                        capacity: c1,
                                        estimate_time: e1,
                                        spent_time: s1,
                                        title: 'Capacity: '+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(e1)+'<br>Time Spent: '+hoursminutes(s1)
                                    },
                                    success: function(progress) {
                                        $('#progress_'+$("#strtotime_scheduled_date").val()).html(progress)
                                    }
                                })
                        }
				$("#old_task_id").val() && ($("#old_task_id").val() == $("#task_id").val() ? ("#main_" + $("#task_id").val()).length && $("#main_" + $("#task_id").val()).replaceWith(a) : $("#main_" + $("#old_task_id").val()).replaceWith(a)), $("#main_" + $("#task_id").val()).length ? $("#main_" + $("#task_id").val()).replaceWith(a) : (0 == $("#week_" + $("#strtotime_scheduled_date").val() + " .task_div").length && $("#week_" + $("#strtotime_scheduled_date").val() + " .space").remove(),($("#other_user_task").is(':checked'))?($("#task_allocated_user_id").val() == $("#calender_team_user_id").val())?($("#divide_"+$("#strtotime_scheduled_date").val()).length)?$("#divide_"+$("#strtotime_scheduled_date").val()).before(a):$("#week_" + $("#strtotime_scheduled_date").val()).find("#add_newTask_"+$("#strtotime_scheduled_date").val()).before(a):$("#week_" + $("#strtotime_scheduled_date").val()).find("#add_newTask_"+$("#strtotime_scheduled_date").val()).before(a):$("#week_" + $("#strtotime_scheduled_date").val()).find("#add_newTask_"+$("#strtotime_scheduled_date").val()).before(a));
								
                    } else if ($("#task_allocated_user_id").val() == $("#calender_team_user_id").val()) {
                        var l = get_minutes($("#est_" + $("#strtotime_scheduled_date").val()).html()),
                            m = get_minutes($("#spent_" + $("#strtotime_scheduled_date").val()).html()),
                            i = parseInt(60 * parseInt($("#old_task_time_estimate_hour").val())) + parseInt($("#old_task_time_estimate_min").val()),
                            j = parseInt(60 * parseInt($("#old_task_time_spent_hour").val())) + parseInt($("#old_task_time_spent_min").val()),
                            n = get_minutes($("#capacity_" + $("#strtotime_scheduled_date").val()).html()),
                            o = parseInt(l) - parseInt(i),
                            p = hoursminutes(o),
                            q = hoursminutes(parseInt(m) - parseInt(j));
                        o > n ? $("#est_" + $("#strtotime_scheduled_date").val()).addClass("red") : $("#est_" + $("#strtotime_scheduled_date").val()).removeClass("red"), $("#est_" + $("#strtotime_scheduled_date").val()).html(p), $("#spent_" + $("#strtotime_scheduled_date").val()).html(q), $("#main_" + $("#task_id").val()).remove()
                    } else $("#main_" + $("#task_id").val()).remove();
                    "0" != $("#prerequisite_task_id").val() && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: $("#prerequisite_task_id").val()||'0'
                        },
                        success: function(a) {
                        }
                    }), $("body").tooltip({
                        selector: ".tooltips"
                    })
                }
            });
            else if ("from_calendar" == b || "FiveWeekView" == b)
                if ("series" == $("#from").val() && $(".month_master_" + $("#task_id").val()).parent("div").map(function() {
					
                        var a = this.id,
                            b = $("#" + a).children("div").attr("id"),
                            c = b.replace("task_", ""),
                            d = get_minutes($("#estimate_time_" + a).html()),
                            e = $("#capacity_time_" + a).html(),
                            f = e.indexOf("h"),
                            g = e.substr(0, f),
                            h = $("#task_est_" + c).html();
                        if (h) var i = get_minutes(h);
                        else var i = "0";
                        var j = $("#task_type_" + c).val();
                        if ($("#" + b).remove(), 0 == $("#" + a + " .taskbox").length) $("#task_list_" + a).remove(), $("#task_info_" + a).remove();
                        else {
                            if (j)
                                if (task_type1 = j.split(","), "1" == task_type1[0]) {
                                    var k = $("#completed_" + a).html();
                                    if (k > 0 && $("#completed_" + a).html(parseInt(k) - 1), void 0 != task_type1[1]) {
                                        var l = $("#scheduled_" + a).html();
                                        l > 0 && $("#scheduled_" + a).html(parseInt(l) - 1)
                                    }
                                    if (void 0 != task_type1[2]) {
                                        var m = $("#due_" + a).html();
                                        m > 0 && $("#due_" + a).html(parseInt(m) - 1)
                                    }
                                } else if ("2" == task_type1[0]) {
                                var n = $("#overdued_" + a).html();
                                if (n > 0 && $("#overdued_" + a).html(parseInt(n) - 1), void 0 != task_type1[1]) {
                                    var l = $("#scheduled_" + a).html();
                                    l > 0 && $("#scheduled_" + a).html(parseInt(l) - 1)
                                }
                            } else {
                                if ("3" == task_type1[0]) {
                                    var l = $("#scheduled_" + a).html();
                                    l > 0 && $("#scheduled_" + a).html(parseInt(l) - 1)
                                }
                                if (void 0 != task_type1[1]) {
                                    var m = $("#due_" + a).html();
                                    m > 0 && $("#due_" + a).html(parseInt(m) - 1)
                                }
                            }
                            var o = parseInt(d) - parseInt(i),
                                p = hoursminutes(o);
                            $("#estimate_time_" + a).html(p), $("#estimate_time_" + a).removeAttr("class"), o > 60 * g ? $("#estimate_time_" + a).attr("class", "commonlabel redlabel") : $("#estimate_time_" + a).attr("class", "commonlabel")
                        }
                    }).get(), 1 == $("#recurrence").prop("checked")) $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/set_monthly_update_div_for_task",
                    data: {
                        task_id: $("#task_id").val(),
                        year: $("#year").val(),
                        month: $("#month").val()
                    },
                    async: !1,
                    success: function(b) { 
                        if (b = jQuery.parseJSON(b), $("#task_" + $("#task_id").val()).length) {
							
                            var c = $("#task_type_" + $("#task_id").val()).val();
                            if ($("#task_" + $("#task_id").val()).remove(), 0 == $("#" + $("#strtotime_scheduled_date").val() + " .taskbox").length) $("#task_list_" + $("#strtotime_scheduled_date").val()).remove(), $("#task_info_" + $("#strtotime_scheduled_date").val()).remove();
                            else {
                                if (c)
                                    if (task_type1 = c.split(","), "1" == task_type1[0]) {
                                        var d = $("#completed_" + $("#strtotime_scheduled_date").val()).html();
                                        if (d > 0 && $("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt(d) - 1), "undefined" != task_type1[1]) {
                                            var e = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            e > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(e) - 1)
                                        }
                                        if ("undefined" != task_type1[2]) {
                                            var f = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                            f > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(f) - 1)
                                        }
                                    } else if ("2" == task_type1[0]) {
                                    var g = $("#overdued_" + $("#strtotime_scheduled_date").val()).html();
                                    if (g > 0 && $("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt(g) - 1), "undefined" != task_type1[1]) {
                                        var e = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                        e > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(e) - 1)
                                    }
                                } else {
                                    if ("3" == task_type1[0]) {
                                        var e = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                        e > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(e) - 1)
                                    }
                                    if ("undefined" != task_type1[1]) {
                                        var f = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                        f > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(f) - 1)
                                    }
                                }
                                var h = get_minutes($("#estimate_time_" + $("#strtotime_scheduled_date").val()).html()),
                                    i = $("#capacity_time_" + $("#strtotime_scheduled_date").val()).html(),
                                    j = i.indexOf("h"),
                                    k = i.substr(0, j),
                                    l = 60 * parseInt($("#old_task_time_estimate_hour").val()) + parseInt($("#old_task_time_estimate_min").val());
                                if (h) var m = parseInt(h) - parseInt(l) + (parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val()));
                                else var m = parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
                                var n = hoursminutes(m);
                                $("#estimate_time_" + $("#strtotime_scheduled_date").val()).html(n), $("#estimate_time_" + $("#strtotime_scheduled_date").val()).removeAttr("class"), m > 60 * k ? $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel redlabel") : $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel")
                            }
                        }
                        b && $.map(b, function(b) {
                            if (0 == $("#task_" + b.re_data.task_id).length) {
                                if (0 == $("#task_list_" + b.div_id).length) var c = 0;
                                else var c = 1;
                                var d = (b.tmezone_time, b.task_due_date_time),
                                    e = b.tmezone_day,
                                    f = $("#td_" + b.div_id + " .weekday-txt").html();
                                if (b.re_data.project_title) var g = b.re_data.project_title + " " + b.re_data.task_title;
                                else var g = b.re_data.task_title;
                                var h = b.strtotime_start_date,
                                    i = b.strtotime_end_date,
                                    j = "";
                                "0" == b.re_data.task_time_estimate && (j = "display:none;");
                                var k = "";
                                "0" == b.re_data.locked_due_date && (k = "display:none;");
                                var l = g;
                                "" == j && "" == k ? l.length > 18 && (l = l.substring(0, 16) + "..") : "" != j && "" == k ? l.length > 24 && (l = l.substring(0, 22) + "..") : "" == j && "" != k ? l.length > 18 && (l = l.substring(0, 16) + "..") : l.length > 26 && (l = l.substring(0, 24) + "..");
                                var m = 0,
                                    n = 0,
                                    o = 0,
                                    p = 0,
                                    q = "0";
                                b.re_data.task_status_id == a ? (m = parseInt(m) + 1, n = parseInt(n) + 1, q = "1,3", b.task_due_date_time == b.div_id && (o = parseInt(o) + 1, q = "1,3,4")) : b.task_due_date_time < b.today_time ? (p = parseInt(p) + 1, n = parseInt(n) + 1, q = "2,3") : (n = parseInt(n) + 1, q = "3", b.task_due_date_time == b.div_id && (o = parseInt(o) + 1, q = "3,4"));
                                var r = "";
                                if (q) {
                                    each_task_type_val = q.split(","), r = "";
                                    for (var s = 0; s < each_task_type_val.length; s++) r += "task_type_" + each_task_type_val[s] + " "
                                }
                                var t = "";
                                if (0 != p && (t = "txtred"), 0 == c) {
                                    var u = "";
                                    u += '<div class="td-date unsorttd"><span class="weekday-txt"> ' + f + "</span> " + e + "<a onclick=\"add_task('" + b.tmezone_time + "','" + b.tmezone_scheduled_date + '\');" href="javascript:void(0);"><i class="calenderstrip caladdicon"> </i> </a> </div>', u += '<div id="task_list_' + b.div_id + '" class="task-list unsorttd" style="display: block;"><ul>', u += '<li><ul><li><div class="commonlabel">Capacity&nbsp;&nbsp;</div></li><li><div id="capacity_time_' + b.div_id + '" class="commonlabel"> ' + hoursminutes(b.user_capacity) + " </div></li></ul></li>", u += '<li><ul><li><div class="commonlabel">Allocated</div></li><li><div id="estimate_time_' + b.div_id + '" class="commonlabel">' + hoursminutes(b.re_data.task_time_estimate) + "</div></li></ul></li>", u += "</ul></div>", u += '<div id="task_info_' + b.div_id + '" class="task-info unsorttd" style="display: block;"><ul>', u += '<li><span class="tasklab-info">Overdue : </span><span id="overdued_' + b.div_id + '" class="task-num ' + t + ' overduehover"> ' + p + " </span></li>", u += '<li><span class="tasklab-info">Due : </span><span id="due_' + b.div_id + '" class="task-num duehover"> ' + o + " </span></li>", u += '<li><span class="tasklab-info">Completed : </span><span id="completed_' + b.div_id + '" class="task-num completedhover"> ' + m + " </span></li>", u += '<li><span class="tasklab-info">Scheduled :</span><span id="scheduled_' + b.div_id + '" class="task-num scheduledhover"> ' + n + " </span></li>", u += "</ul></div>", u += '<div style="padding-bottom: 10px;" id="' + b.div_id + '" class="task-lable ' + b.sort_class + ' full_task scroll_calender">', u += '<div id="task_' + b.re_data.task_id + '" style="background-color:' + b.color_code + "; border:1px solid " + b.outside_color_code + ';" class="taskbox calicon' + b.re_data.task_priority + " " + r + " month_master_" + b.re_data.master_task_id + '  before_timer" onclick="save_task_for_timer(this,\'' + b.re_data.task_id + "','" + b.re_data.task_title + "','" + b.re_data.task_time_spent + "','0','" + b.re_data.completed_depencencies + "');\">", u += "<div oncontextmenu=\"context_menu('" +b.context_menu+"');\">", u += '<a href="javascript:void(0)" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + '\');" data-original-title="' + g + '" class="tooltips "><span class="task-desc settaskdes">' + l + '</span><p class="task-hrs">', u += '<i style="' + k + '" class="stripicon lockicon"></i>', u += '<span id="task_est_' + b.re_data.task_id + '" class="task-hrs" style="' + j + '">' + hoursminutes(b.re_data.task_time_estimate) + "</span></p>", u += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + b.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + b.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + b.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + b.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + b.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + b.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + b.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + b.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + b.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + b.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + b.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + b.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + b.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + b.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + b.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + b.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + b.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + b.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + b.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + b.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + b.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + b.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + b.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + b.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + b.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + b.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + b.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + b.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + b.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + b.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + b.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + b.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + b.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + b.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + b.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + b.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + b.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + b.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + b.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + b.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + b.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + b.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + b.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + b.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + b.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + b.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + b.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + b.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + b.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + b.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + b.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + b.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + b.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + b.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + b.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + b.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + b.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + b.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + b.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + b.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + b.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + b.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + b.re_data.is_deleted + "&quot;,&quot;swimlane_id&quot;:&quot;" + b.re_data.swimlane_id + "&quot;,&quot;color_id&quot;:&quot;" + b.re_data.color_id + "&quot;,&quot;kanban_order&quot;:&quot;" + b.re_data.kanban_order + "&quot;,&quot;calender_order&quot;:&quot;" + b.re_data.calender_order + "&quot;,&quot;task_ex_pos&quot;:&quot;" + b.re_data.task_ex_pos + "&quot;,&quot;customer_id&quot;:&quot;" + b.re_data.customer_id+ "&quot;,&quot;cost_per_hour&quot;:&quot;" + b.re_data.cost_per_hour + "&quot;,&quot;charge_out_rate&quot;:&quot;" + b.re_data.charge_out_rate+ "&quot;,&quot;cost&quot;:&quot;" + b.re_data.cost + "&quot;,&quot;charge_out_rate&quot;:&quot;"+ b.re_data.charge_out_rate + "&quot;,&quot;estimated_total_charge&quot;:&quot;" + b.re_data.estimated_total_charge + "&quot;,&quot;billed_time&quot;:&quot;" + b.re_data.billed_time + "&quot;,&quot;actual_total_charge&quot;:&quot;" + b.re_data.actual_total_charge +  '&quot;}" id="task_data_' + b.re_data.task_id + '">', u += '<input type="hidden" value="' + d + '" id="hdn_due_date_' + b.re_data.task_id + '"><input type="hidden" value="' + b.re_data.locked_due_date + '" id="hdn_locked_due_date_' + b.re_data.task_id + '">', u += '<input type="hidden" value="' + b.outside_color_code + '" name="or_color_id" id="or_color_' + b.re_data.task_id + '"><input type="hidden" value="' + q + '" name="task_type" id="task_type_' + b.re_data.task_id + '"><input type="hidden" id="task_spent_' + b.re_data.task_id + '" name="task_spent_time" value="' + b.re_data.task_time_spent + '" /><input type="hidden" id="task_status_' + b.re_data.task_id + '" name="task_status_name" value="' + b.status_name + '">', u += '<div class="clearfix before_timer"> </div></a></div></div></div>', $("#td_" + b.div_id).html(u);
                                    var v = parseInt(b.re_data.task_time_estimate)
                                } else {
                                    var u = "";
                                    u += '<div id="task_' + b.re_data.task_id + '" style="background-color:' + b.color_code + "; border:1px solid " + b.outside_color_code + ';" class="taskbox calicon' + b.re_data.task_priority + " " + r + " month_master_" + b.re_data.master_task_id + '  before_timer" onclick="save_task_for_timer(this,\'' + b.re_data.task_id + "','" + b.re_data.task_title + "','" + b.re_data.task_time_spent + "','0','" + b.re_data.completed_depencencies + "');\">", u += "<div oncontextmenu=\"context_menu('" + b.context_menu+ "');\">", u += '<a href="javascript:void(0)" onclick="open_seris(this,\'' + b.re_data.task_id + "','" + b.re_data.master_task_id + "','" + b.is_chk + '\');" data-original-title="' + g + '" class="tooltips "><span class="task-desc settaskdes">' + l + '</span><p class="task-hrs">', u += '<i style="' + k + '" class="stripicon lockicon"></i>', u += '<span id="task_est_' + b.re_data.task_id + '" class="task-hrs" style="' + j + '">' + hoursminutes(b.re_data.task_time_estimate) + "</span></p>", u += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + b.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + b.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + b.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + b.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + b.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + b.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + b.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + b.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + b.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + b.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + b.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + b.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + b.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + b.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + b.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + b.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + b.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + b.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + b.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + b.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + b.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + b.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + b.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + b.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + b.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + b.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + b.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + b.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + b.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + b.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + b.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + b.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + b.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + b.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + b.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + b.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + b.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + b.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + b.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + b.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + b.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + b.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + b.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + b.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + b.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + b.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + b.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + b.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + b.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + b.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + b.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + b.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + b.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + b.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + b.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + b.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + b.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + b.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + b.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + b.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + b.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + b.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + b.re_data.is_deleted + "&quot;,&quot;swimlane_id&quot;:&quot;" + b.re_data.swimlane_id + "&quot;,&quot;color_id&quot;:&quot;" + b.re_data.color_id + "&quot;,&quot;kanban_order&quot;:&quot;" + b.re_data.kanban_order + "&quot;,&quot;calender_order&quot;:&quot;" + b.re_data.calender_order + "&quot;,&quot;task_ex_pos&quot;:&quot;" + b.re_data.task_ex_pos + "&quot;,&quot;customer_id&quot;:&quot;" + b.re_data.customer_id+ "&quot;,&quot;cost_per_hour&quot;:&quot;" + b.re_data.cost_per_hour + "&quot;,&quot;charge_out_rate&quot;:&quot;" + b.re_data.charge_out_rate+ "&quot;,&quot;cost&quot;:&quot;" + b.re_data.cost + "&quot;,&quot;charge_out_rate&quot;:&quot;"+ b.re_data.charge_out_rate + "&quot;,&quot;estimated_total_charge&quot;:&quot;" + b.re_data.estimated_total_charge + "&quot;,&quot;billed_time&quot;:&quot;" + b.re_data.billed_time + "&quot;,&quot;actual_total_charge&quot;:&quot;" + b.re_data.actual_total_charge+ '&quot;}" id="task_data_' + b.re_data.task_id + '">', u += '<input type="hidden" value="' + d + '" id="hdn_due_date_' + b.re_data.task_id + '"><input type="hidden" value="' + b.re_data.locked_due_date + '" id="hdn_locked_due_date_' + b.re_data.task_id + '">', u += '<input type="hidden" value="' + b.outside_color_code + '" name="or_color_id" id="or_color_' + b.re_data.task_id + '"><input type="hidden" value="' + q + '" name="task_type" id="task_type_' + b.re_data.task_id + '"><input type="hidden" id="task_spent_' + b.re_data.task_id + '" name="task_spent_time" value="' + b.re_data.task_time_spent + '" /><input type="hidden" id="task_status_' + b.re_data.task_id + '" name="task_status_name" value="' + b.status_name + '">', u += '<div class="clearfix before_timer"> </div></a></div></div>', $("#" + b.div_id).append(u), a == b.re_data.task_status_id ? ($("#completed_" + b.div_id).html(parseInt($("#completed_" + b.div_id).html()) + 1), $("#scheduled_" + b.div_id).html(parseInt($("#scheduled_" + b.div_id).html()) + 1), b.task_due_date_time == b.div_id && $("#due_" + b.div_id).html(parseInt($("#due_" + b.div_id).html()) + 1)) : b.task_due_date_time < b.today_time ? ($("#overdued_" + b.div_id).html(parseInt($("#overdued_" + b.div_id).html()) + 1), $("#scheduled_" + b.div_id).html(parseInt($("#scheduled_" + b.div_id).html()) + 1)) : ($("#scheduled_" + b.div_id).html(parseInt($("#scheduled_" + b.div_id).html()) + 1), b.task_due_date_time == b.div_id && $("#due_" + b.div_id).html(parseInt($("#due_" + b.div_id).html()) + 1));
                                    var w = get_minutes($("#estimate_time_" + b.div_id).html());
                                    if (w) var v = parseInt(w) + parseInt(b.re_data.task_time_estimate);
                                    else var v = parseInt(b.re_data.task_time_estimate);
                                    var x = hoursminutes(v);
                                    $("#estimate_time_" + b.div_id).html(x)
                                }
                                $("#show_capacity").is(":checked") ? $(".task-list").css("display", "block") : $(".task-list").css("display", "none"), $("#show_summary").is(":checked") ? $(".task-info").css("display", "block") : $(".task-info").css("display", "none"), $("#show_task").is(":checked") ? ($(".task-lable").css("display", "block"), $(".scroll_calender").slimScroll({
                                    color: "#17A3E9",
                                    height: "120px",
                                    wheelStep: 100
                                })) : ($(".task-lable").css("display", "none"), $(".scroll_calender").slimScroll({
                                    destroy: !0
                                }));
                                var y = $("#capacity_time_" + b.div_id).html(),
                                    z = y.indexOf("h"),
                                    A = y.substr(0, z);
                                $("#estimate_time_" + b.div_id).removeAttr("class"), v > 60 * A ? $("#estimate_time_" + b.div_id).attr("class", "commonlabel redlabel") : $("#estimate_time_" + b.div_id).attr("class", "commonlabel")
                            }
                        }), popover(), $("body").tooltip({
                            selector: ".tooltips"
                        })
                    }
                });
                else {
                    if (0 == $("#task_list_" + $("#strtotime_scheduled_date").val()).length) var g = 0;
                    else var g = 1;
                    if (0 == g) {
                        if ("FiveWeekView" == b) $.ajax({
                            type: "post",
                            url: SIDE_URL + "calendar/monthly_day_view",
                            data: {
                                date: $("#strtotime_scheduled_date").val(),
                                task_id: $("#task_id").val(),
                                year: $("#year").val(),
                                month: $("#month").val(),
                                from: "ajax",
                                color_menu: $("#monthly_color_menu").val()
                            },
                            async: !1,
                            success: function(a) {
                                $("#task_" + $("#task_id").val()).remove(), App.init(), $("#td_" + $("#strtotime_scheduled_date").val()).html(a), $("body").tooltip({
                                    selector: ".tooltips"
                                }), $("#full-width").modal("hide"),$("#full-width").on('hidden.bs.modal', function(){
                                    $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
                                })
                            }
                        });
                        else if ("from_calendar" == b) {
                            var h = $("#td_" + $("#strtotime_scheduled_date").val() + " .weekday-txt").html();
                            h = h.replace("WD ", ""), $.ajax({
                                type: "post",
                                url: SIDE_URL + "calendar/monthly_day_view",
                                data: {
                                    date: $("#strtotime_scheduled_date").val(),
                                    task_id: $("#task_id").val(),
                                    year: $("#year").val(),
                                    month: $("#month").val(),
                                    wd: h,
                                    color_menu: $("#monthly_color_menu").val()
                                },
                                async: !1,
                                success: function(a) {
                                    $("#task_" + $("#task_id").val()).remove(), App.init(), $("#td_" + $("#strtotime_scheduled_date").val()).html(a), $("body").tooltip({
                                        selector: ".tooltips"
                                    })
                                }
                            })
                        }
                    } else $.ajax({
                        type: "post",
                        url: SIDE_URL + "calendar/set_update_task",
                        data: {
                            task_id: $("#task_id").val(),
                            year: $("#year").val(),
                            month: $("#month").val(),
                            color_menu: $("#monthly_color_menu").val()
                        },
                        async: !1,
                        success: function(b) {
                            function c(a) {
                                return a < 10 ? "0" + a : a
                            }

                            function c(a) {
                                return a < 10 ? "0" + a : a
                            }

                            function c(a) {
                                return a < 10 ? "0" + a : a
                            }

                            function c(a) {
                                return a < 10 ? "0" + a : a
                            }
                            if (App.init(), b)
                                if ($("#task_" + $("#task_id").val()).length) {
                                    var d = $("#task_due_date").val();
                                    if (d.indexOf("/") > 0 && "2" == d.indexOf("/")) {
                                        var e = d.split("/");
                                        d = e[2] + "-" + e[1] + "-" + e[0]
                                    } else if (d.indexOf("-") > 0 && "2" == d.indexOf("-")) {
                                        var e = d.split("-");
                                        d = e[2] + "-" + e[1] + "-" + e[0]
                                    } else {
                                        var e = new Date(d);
                                        d = e.getFullYear() + "-" + c(e.getMonth() + 1) + "-" + c(e.getDate())
                                    }
                                    var f = $("#task_scheduled_date").val();
                                    if (f.indexOf("/") > 0 && "2" == f.indexOf("/")) {
                                        var e = f.split("/");
                                        f = e[2] + "-" + e[1] + "-" + e[0]
                                    } else if (f.indexOf("-") > 0 && "2" == f.indexOf("-")) {
                                        var e = f.split("-");
                                        f = e[2] + "-" + e[1] + "-" + e[0]
                                    } else {
                                        var e = new Date(f);
                                        f = e.getFullYear() + "-" + c(e.getMonth() + 1) + "-" + c(e.getDate())
                                    }
                                    var g = $("#task_type_" + $("#task_id").val()).val();
                                    if (g)
                                        if (task_type1 = g.split(","), "1" == task_type1[0]) {
                                            var h = $("#completed_" + $("#strtotime_scheduled_date").val()).html();
                                            if (h > 0 && $("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt(h) - 1), "undefined" != task_type1[1]) {
                                                var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                                i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                            }
                                            if ("undefined" != task_type1[2]) {
                                                var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                                j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                            }
                                        } else if ("2" == task_type1[0]) {
                                        var k = $("#overdued_" + $("#strtotime_scheduled_date").val()).html();
                                        if (k > 0 && $("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt(k) - 1), "undefined" != task_type1[1]) {
                                            var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                        }
                                    } else {
                                        if ("3" == task_type1[0]) {
                                            var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                        }
                                        if ("undefined" != task_type1[1]) {
                                            var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                            j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                        }
                                    }
                                    var l = new Date;
                                    l = l.getFullYear() + "-" + c(l.getMonth() + 1) + "-" + c(l.getDate()), a == $("#task_status_id").val() ? ($("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#completed_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(1), $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), "1" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(1, 3) : $("#task_type_" + $("#task_id").val()).val(3), d == f && ($("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#due_" + $("#strtotime_scheduled_date").val()).html()) + 1), "1,3" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(1, 3, 4) : $("#task_type_" + $("#task_id").val()).val(3, 4))) : d < l ? ($("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#overdued_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(2), $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), "2" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(2, 3) : $("#task_type_" + $("#task_id").val()).val(3)) : ($("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(3), d == f && ($("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#due_" + $("#strtotime_scheduled_date").val()).html()) + 1), "3" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(3, 4) : $("#task_type_" + $("#task_id").val()).val(4)));
                                    var m = get_minutes($("#estimate_time_" + $("#strtotime_scheduled_date").val()).html()),
                                        n = $("#capacity_time_" + $("#strtotime_scheduled_date").val()).html(),
                                        o = n.indexOf("h"),
                                        p = n.substr(0, o),
                                        q = 60 * parseInt($("#old_task_time_estimate_hour").val()) + parseInt($("#old_task_time_estimate_min").val());
                                    if (m) var r = parseInt(m) - parseInt(q) + (parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val()));
                                    else var r = parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
                                    var s = hoursminutes(r);
                                    $("#estimate_time_" + $("#strtotime_scheduled_date").val()).html(s), $("#estimate_time_" + $("#strtotime_scheduled_date").val()).removeAttr("class"), r > 60 * p ? $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel redlabel") : $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel"), $("#task_" + $("#task_id").val()).replaceWith(b)
                                } else if ($("#old_task_id").val() == $("#task_id").val())
                                if (0 == $("#task_" + $("#task_id").val() + " a.tooltips").length) {
                                    var d = $("#task_due_date").val();
                                    if (d.indexOf("/") > 0 && "2" == d.indexOf("/")) {
                                        var e = d.split("/");
                                        d = e[2] + "-" + e[1] + "-" + e[0]
                                    } else if (d.indexOf("-") > 0 && "2" == d.indexOf("-")) {
                                        var e = d.split("-");
                                        d = e[2] + "-" + e[1] + "-" + e[0]
                                    } else {
                                        var e = new Date(d);
                                        d = e.getFullYear() + "-" + c(e.getMonth() + 1) + "-" + c(e.getDate())
                                    }
                                    var f = $("#task_scheduled_date").val();
                                    if (f.indexOf("/") > 0 && "2" == f.indexOf("/")) {
                                        var e = f.split("/");
                                        f = e[2] + "-" + e[1] + "-" + e[0]
                                    } else if (f.indexOf("-") > 0 && "2" == f.indexOf("-")) {
                                        var e = f.split("-");
                                        f = e[2] + "-" + e[1] + "-" + e[0]
                                    } else {
                                        var e = new Date(f);
                                        f = e.getFullYear() + "-" + c(e.getMonth() + 1) + "-" + c(e.getDate())
                                    }
                                    var g = $("#task_type_" + $("#task_id").val()).val();
                                    if (g)
                                        if (task_type1 = g.split(","), "1" == task_type1[0]) {
                                            var h = $("#completed_" + $("#strtotime_scheduled_date").val()).html();
                                            if (h > 0 && $("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt(h) - 1), "undefined" != task_type1[1]) {
                                                var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                                i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                            }
                                            if ("undefined" != task_type1[2]) {
                                                var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                                j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                            }
                                        } else if ("2" == task_type1[0]) {
                                        var k = $("#overdued_" + $("#strtotime_scheduled_date").val()).html();
                                        if (k > 0 && $("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt(k) - 1), "undefined" != task_type1[1]) {
                                            var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                        }
                                    } else {
                                        if ("3" == task_type1[0]) {
                                            var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                        }
                                        if ("undefined" != task_type1[1]) {
                                            var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                            j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                        }
                                    }
                                    var l = new Date;
                                    l = l.getFullYear() + "-" + c(l.getMonth() + 1) + "-" + c(l.getDate()), a == $("#task_status_id").val() ? ($("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#completed_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(1), $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), "1" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(1, 3) : $("#task_type_" + $("#task_id").val()).val(3), d == f && ($("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#due_" + $("#strtotime_scheduled_date").val()).html()) + 1), "1,3" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(1, 3, 4) : $("#task_type_" + $("#task_id").val()).val(3, 4))) : d < l ? ($("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#overdued_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(2), $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), "2" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(2, 3) : $("#task_type_" + $("#task_id").val()).val(3)) : ($("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(3), d == f && ($("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#due_" + $("#strtotime_scheduled_date").val()).html()) + 1), "3" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(3, 4) : $("#task_type_" + $("#task_id").val()).val(4)));
                                    var m = get_minutes($("#estimate_time_" + $("#strtotime_scheduled_date").val()).html()),
                                        n = $("#capacity_time_" + $("#strtotime_scheduled_date").val()).html(),
                                        o = n.indexOf("h"),
                                        p = n.substr(0, o);
                                    if (m) var r = parseInt(m) + parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
                                    else var r = parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
                                    var s = hoursminutes(r);
                                    $("#estimate_time_" + $("#strtotime_scheduled_date").val()).html(s), $("#estimate_time_" + $("#strtotime_scheduled_date").val()).removeAttr("class"), r > 60 * p ? $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel redlabel") : $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel"), $("#" + $("#strtotime_scheduled_date").val()).append(b)
                                } else $("#task_" + $("#task_id").val()).replaceWith(b);
                            else if ($("#old_task_id").val()) $("#task_" + $("#old_task_id").val()).replaceWith(b);
                            else {
                                var d = $("#task_due_date").val();
                                if (d.indexOf("/") > 0 && "2" == d.indexOf("/")) {
                                    var e = d.split("/");
                                    d = e[2] + "-" + e[1] + "-" + e[0]
                                } else if (d.indexOf("-") > 0 && "2" == d.indexOf("-")) {
                                    var e = d.split("-");
                                    d = e[2] + "-" + e[1] + "-" + e[0]
                                } else {
                                    var e = new Date(d);
                                    d = e.getFullYear() + "-" + c(e.getMonth() + 1) + "-" + c(e.getDate())
                                }
                                var f = $("#task_scheduled_date").val();
                                if (f.indexOf("/") > 0 && "2" == f.indexOf("/")) {
                                    var e = f.split("/");
                                    f = e[2] + "-" + e[1] + "-" + e[0]
                                } else if (f.indexOf("-") > 0 && "2" == f.indexOf("-")) {
                                    var e = f.split("-");
                                    f = e[2] + "-" + e[1] + "-" + e[0]
                                } else {
                                    var e = new Date(f);
                                    f = e.getFullYear() + "-" + c(e.getMonth() + 1) + "-" + c(e.getDate())
                                }
                                var g = $("#task_type_" + $("#task_id").val()).val();
                                if (g)
                                    if (task_type1 = g.split(","), "1" == task_type1[0]) {
                                        var h = $("#completed_" + $("#strtotime_scheduled_date").val()).html();
                                        if (h > 0 && $("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt(h) - 1), "undefined" != task_type1[1]) {
                                            var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                        }
                                        if ("undefined" != task_type1[2]) {
                                            var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                            j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                        }
                                    } else if ("2" == task_type1[0]) {
                                    var k = $("#overdued_" + $("#strtotime_scheduled_date").val()).html();
                                    if (k > 0 && $("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt(k) - 1), "undefined" != task_type1[1]) {
                                        var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                        i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                    }
                                } else {
                                    if ("3" == task_type1[0]) {
                                        var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                        i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                    }
                                    if ("undefined" != task_type1[1]) {
                                        var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                        j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                    }
                                }
                                var l = new Date;
                                l = l.getFullYear() + "-" + c(l.getMonth() + 1) + "-" + c(l.getDate()), a == $("#task_status_id").val() ? ($("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#completed_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(1), $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), "1" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(1, 3) : $("#task_type_" + $("#task_id").val()).val(3), d == f && ($("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#due_" + $("#strtotime_scheduled_date").val()).html()) + 1), "1,3" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(1, 3, 4) : $("#task_type_" + $("#task_id").val()).val(3, 4))) : d < l ? ($("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#overdued_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(2), $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), "2" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(2, 3) : $("#task_type_" + $("#task_id").val()).val(3)) : ($("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#scheduled_" + $("#strtotime_scheduled_date").val()).html()) + 1), $("#task_type_" + $("#task_id").val()).val(3), d == f && ($("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt($("#due_" + $("#strtotime_scheduled_date").val()).html()) + 1), "3" == $("#task_type_" + $("#task_id").val()).val() ? $("#task_type_" + $("#task_id").val()).val(3, 4) : $("#task_type_" + $("#task_id").val()).val(4)));
                                var m = get_minutes($("#estimate_time_" + $("#strtotime_scheduled_date").val()).html()),
                                    n = $("#capacity_time_" + $("#strtotime_scheduled_date").val()).html(),
                                    o = n.indexOf("h"),
                                    p = n.substr(0, o);
                                if (m) var r = parseInt(m) + parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
                                else var r = parseInt(60 * $("#task_time_estimate_hour").val()) + parseInt($("#task_time_estimate_min").val());
                                var s = hoursminutes(r);
                                $("#estimate_time_" + $("#strtotime_scheduled_date").val()).html(s), $("#estimate_time_" + $("#strtotime_scheduled_date").val()).removeAttr("class"), r > 60 * p ? $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel redlabel") : $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel"), $("#" + $("#strtotime_scheduled_date").val()).append(b)
                            } else if ($("#task_allocated_user_id").val() == $("#calender_team_user_id").val()) {
                                var t = get_minutes($("#estimate_time_" + $("#strtotime_scheduled_date").val()).html()),
                                    u = $("#capacity_time_" + $("#strtotime_scheduled_date").val()).html(),
                                    v = u.indexOf("h"),
                                    w = u.substr(0, v),
                                    x = parseInt(60 * parseInt($("#task_time_estimate_hour").val())) + parseInt($("#task_time_estimate_min").val()),
                                    d = $("#task_due_date").val();
                                if (d.indexOf("/") > 0 && "2" == d.indexOf("/")) {
                                    var e = d.split("/");
                                    d = e[2] + "-" + e[1] + "-" + e[0]
                                } else if (d.indexOf("-") > 0 && "2" == d.indexOf("-")) {
                                    var e = d.split("-");
                                    d = e[2] + "-" + e[1] + "-" + e[0]
                                } else {
                                    var e = new Date(d);
                                    d = e.getFullYear() + "-" + c(e.getMonth() + 1) + "-" + c(e.getDate())
                                }
                                var l = new Date;
                                l = l.getFullYear() + "-" + (l.getMonth() + 1) + "-" + l.getDate();
                                var g = "0";
                                if (a == $("#task_status_id").val() ? (g = "1", g = "1" == g ? "1,3" : "3", Date.parse(new Date(d)) / 1e3 == $("#strtotime_scheduled_date").val() && (g = "1,3" == g ? "1,3,4" : "3,4")) : Date.parse(new Date(d)) < Date.parse(new Date(l)) ? (g = "2", g = "2" == g ? "2,3" : "3") : (g = "3", Date.parse(new Date(d)) / 1e3 == $("#strtotime_scheduled_date").val() && (g = "3" == g ? "3,4" : "4")), $("#task_" + $("#task_id").val()).remove(), 0 == $("#" + $("#strtotime_scheduled_date").val() + " .taskbox").length) $("#task_list_" + $("#strtotime_scheduled_date").val()).remove(), $("#task_info_" + $("#strtotime_scheduled_date").val()).remove();
                                else {
                                    if (g)
                                        if (task_type1 = g.split(","), "1" == task_type1[0]) {
                                            var h = $("#completed_" + $("#strtotime_scheduled_date").val()).html();
                                            if (h > 0 && $("#completed_" + $("#strtotime_scheduled_date").val()).html(parseInt(h) - 1), "undefined" != task_type1[1]) {
                                                var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                                i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                            }
                                            if ("undefined" != task_type1[2]) {
                                                var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                                j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                            }
                                        } else if ("2" == task_type1[0]) {
                                        var k = $("#overdued_" + $("#strtotime_scheduled_date").val()).html();
                                        if (k > 0 && $("#overdued_" + $("#strtotime_scheduled_date").val()).html(parseInt(k) - 1), "undefined" != task_type1[1]) {
                                            var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                        }
                                    } else {
                                        if ("3" == task_type1[0]) {
                                            var i = $("#scheduled_" + $("#strtotime_scheduled_date").val()).html();
                                            i > 0 && $("#scheduled_" + $("#strtotime_scheduled_date").val()).html(parseInt(i) - 1)
                                        }
                                        if ("undefined" != task_type1[1]) {
                                            var j = $("#due_" + $("#strtotime_scheduled_date").val()).html();
                                            j > 0 && $("#due_" + $("#strtotime_scheduled_date").val()).html(parseInt(j) - 1)
                                        }
                                    }
                                    var y = parseInt(t) - parseInt(x),
                                        z = hoursminutes(y);
                                    $("#estimate_time_" + $("#strtotime_scheduled_date").val()).html(z), $("#estimate_time_" + $("#strtotime_scheduled_date").val()).removeAttr("class"), y > 60 * w ? $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel redlabel") : $("#estimate_time_" + $("#strtotime_scheduled_date").val()).attr("class", "commonlabel")
                                }
                            } else $("#task_" + $("#task_id").val()).remove(), 0 == $("#" + $("#strtotime_scheduled_date").val() + " .taskbox").length && ($("#task_list_" + $("#strtotime_scheduled_date").val()).remove(), $("#task_info_" + $("#strtotime_scheduled_date").val()).remove());
                            $("body").tooltip({
                                selector: ".tooltips"
                            })
                        }
                    })
                }
            else if ("from_dashboard" == b || "from_teamdashboard" == b)
                if (1 == $("#recurrence").prop("checked")) $.ajax({
                    type: "post",
                    url: SIDE_URL + "dashboardtask/set_recurrence_update_task",
                    data: {
                        task_id: $("#task_id").val(),
                        duration: $("#dashboard_duration").val(),
                        priority: $("#dashboard_priority").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(a) {
                        function b(a) {
                            return a.substr(0, 1).toUpperCase() + a.substr(1)
                        }
                        a = jQuery.parseJSON(a), $.map(a, function(a) {
                            if (a.re_data.task_scheduled_date, a.today_date, "from_teamdashboard" == $("#redirect_page").val()) {
                                if (a.re_data.task_status_id != COMPLETED_ID)
                                    if ("1" == a.re_data.is_personal || "assign_other" == a.assign_status) $("#teamtodo_" + a.re_data.task_id).length && $("#teamtodo_" + a.re_data.task_id).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teampending_" + a.re_data.task_id).length && $("#teampending_" + a.re_data.task_id).remove(), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teamoverdue_" + a.re_data.task_id).length && $("#teamoverdue_" + a.re_data.task_id).remove(), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                    else {
                                        if ($("#teamtodo_" + a.re_data.master_task_id).length && $("#teamtodo_" + a.re_data.master_task_id).remove(), 1 == a.is_div_valid) {
                                            var c = a.re_data.task_title;
                                            if (c.length > 25) var d = c.substring(0, 22) + "...";
                                            else var d = c;
                                            var e = a.task_status_name;
                                            status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                            var f = "";
                                            if (f += '<tr id="teamtodo_' + a.re_data.task_id + '" role="row" class="even">', f += '<td title="' + a.re_data.task_description + '">', f += "1" == a.is_master_deleted || "0" == a.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.re_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.re_data.task_id + "','" + a.re_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>", f += '<td class="teamdoDueDatepicker" id="teamDoDue_' + a.re_data.task_id + '"><span class="hidden">' + a.re_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="teamSchedulledDatepicker" id="teamSchedulled_' + a.re_data.task_id + '"><span class="hidden">' + a.re_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.re_data.task_priority + "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<td><span class="label label-' + status_class + '">' + e + "</span></td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" id="task_data_' + a.re_data.task_id + '">', f += "</tr>", $("#teamtodo_" + a.re_data.task_id).length) $("#teamtodo_" + a.re_data.task_id).replaceWith(f), $(".teamdoDueDatepicker").datepicker({
                                                startDate: -(1 / 0),
                                                format: JAVASCRIPT_DATE_FORMAT
                                            }).on("changeDate", function(a) {
                                                $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                            }), $(".teamSchedulledDatepicker").datepicker({
                                                startDate: -(1 / 0),
                                                format: JAVASCRIPT_DATE_FORMAT
                                            }).on("changeDate", function(a) {
                                                $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                            });
                                            else {
                                                if ($("#teamtodolist tr td.dataTables_empty").length && $("#teamtodolist tr td.dataTables_empty").remove(), $("#teamtodolist").append(f), "1" == a.re_data.is_master_deleted || "0" == a.re_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.re_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.re_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.re_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.re_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />']);
                                                else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.re_data.task_id + "','" + a.re_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", '<span class="hidden">' + a.re_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.re_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.re_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />']);
                                                var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                                $(h).attr("id", "teamtodo_" + a.re_data.task_id), $("#teamtodo_" + a.re_data.task_id + " td:nth-child(2)").addClass("teamdoDueDatepicker"), $("#teamtodo_" + a.re_data.task_id + " td:nth-child(2)").attr("id", "teamDoDue_" + a.re_data.task_id), $("#teamtodo_" + a.re_data.task_id + " td:nth-child(3)").addClass("teamSchedulledDatepicker"), $("#teamtodo_" + a.re_data.task_id + " td:nth-child(3)").attr("id", "teamSchedulled_" + a.re_data.task_id), $(".teamdoDueDatepicker").datepicker({
                                                    startDate: -(1 / 0),
                                                    format: JAVASCRIPT_DATE_FORMAT
                                                }).on("changeDate", function(a) {
                                                    $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"));
                                                }), $(".teamSchedulledDatepicker").datepicker({
                                                    startDate: -(1 / 0),
                                                    format: JAVASCRIPT_DATE_FORMAT
                                                }).on("changeDate", function(a) {
                                                    $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                                })
                                            }
                                        } else $("#teamtodo_" + a.re_data.task_id).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                        if (a.today_date) {
                                            var c = a.re_data.task_title;
                                            var cname = a.task_data.customer_name;
                                            if (cname) var d = c+' ('+cname+')';
                                            else var d = c;
                                            var e = a.task_status_name;
                                            status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                            var f = "";
                                            if (f += '<tr id="teampending_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>",f +='<td><span class="label label-sm label-' + status_class + '">' + e + '</span></td>' ,f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += "<td>" ,f += a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>',f += "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teampending_" + a.task_data.task_id).length) $("#teampending_" + a.re_data.task_id).replaceWith(f);
                                            else {
                                                if ($("#teampending_list tr td.dataTables_empty").length && $("#teampending_list tr td.dataTables_empty").remove(), $("#teampending_list").append(f), "1" == a.re_data.is_master_deleted || "0" == a.re_data.master_task_id) var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.re_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.re_data.task_due_date + "</span>" + a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                else var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.re_data.task_id + "','" + a.re_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.re_data.task_due_date + "</span>" + a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                                var h = $("#filtertab2").dataTable().fnGetNodes(g);
                                                $(h).attr("id", "teampending_" + a.re_data.task_id)
                                            }
                                        }
                                        if (a.today_date > a.strtotime_due_date) {
                                            var c = a.re_data.task_title;
                                            if (c.length > 25) var d = c.substring(0, 22) + "...";
                                            else var d = c;
                                            var e = a.task_status_name;
                                            status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                            var f = "";
                                            if (f += '<tr id="teamoverdue_' + a.re_data.task_id + '" role="row" class="even">', f += '<td title="' + a.re_data.task_description + '">', f += "1" == a.is_master_deleted || "0" == a.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.re_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.re_data.task_id + "','" + a.re_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", f += '<td><span class="hidden">' + a.re_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += '<td class="hidden-480">' + b(a.re_data.first_name) + " " + b(a.re_data.last_name.charAt(0)) + ".</td>", f += "<td>" + a.delay + "</td>", f += "<td>" + a.re_data.task_priority + "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" id="task_data_' + a.re_data.task_id + '">', f += "</tr>", $("#teamoverdue_" + a.re_data.task_id).length) $("#teamoverdue_" + a.re_data.task_id).replaceWith(f);
                                            else {
                                                if ($("#teamoverdue_list tr td.dataTables_empty").length && $("#teamoverdue_list tr td.dataTables_empty").remove(), $("#teamoverdue_list").append(f), "1" == a.re_data.is_master_deleted || "0" == a.re_data.master_task_id) var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.re_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.re_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.re_data.task_priority]);
                                                else var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.re_data.task_id + "','" + a.re_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.re_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.re_data.task_priority]);
                                                var h = $("#filtertab3").dataTable().fnGetNodes(g);
                                                $(h).attr("id", "teamoverdue_" + a.re_data.task_id)
                                            }
                                        }
                                    }
                            } else if ($("#todo_" + a.re_data.master_task_id).length && $("#todo_" + a.re_data.master_task_id).hide(), "assign_other" == a.assign_status) $("#todo_" + a.re_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#last_login_" + a.re_data.task_id).remove();
                            else if (a.re_data.task_status_id != COMPLETED_ID)
                                if (1 == a.is_div_valid) {
                                    var c = a.re_data.task_title;
                                    if (c > 40) var d = c.substring(0, 37) + "...";
                                    else var d = c;
                                    var e = a.task_status_name;
                                    status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                    var f = "";
                                    if (f += '<tr id="todo_' + a.re_data.task_id + '" role="row" class="even">', f += '<td title="' + a.re_data.task_description + '" class="sorting_1">', f += "1" == a.is_master_deleted || "0" == a.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.re_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.re_data.task_id + "','" + a.re_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", f += '<td class="todoDueDatepicker" id="toDoDue_' + a.re_data.task_id + '"><span class="hidden">' + a.re_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="todoSchedulledDatepicker" id="schedulled_' + a.re_data.task_id + '"><span class="hidden">' + a.re_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.re_data.task_priority + "</td>", f += '<td><span class="label label-' + status_class + '">' + e + '</span></td><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" /></tr>', $("#todo_" + a.re_data.task_id).length) $("#todo_" + a.re_data.task_id).replaceWith(f), $(".todoSchedulledDatepicker").datepicker({
                                        startDate: -(1 / 0),
                                        format: JAVASCRIPT_DATE_FORMAT
                                    }).on("changeDate", function(a) {
                                        $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                    }), $(".todoDueDatepicker").datepicker({
                                        startDate: -(1 / 0),
                                        format: JAVASCRIPT_DATE_FORMAT
                                    }).on("changeDate", function(a) {
                                        $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                    });
                                    else {
                                        if ($("#todolist tr td.dataTables_empty").length && $("#todolist tr td.dataTables_empty").remove(), $("#todolist").append(f), "1" == a.re_data.is_master_deleted || "0" == a.re_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.re_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.re_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.re_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.re_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />']);
                                        else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.re_data.task_id + "','" + a.re_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(d) + "</a>", '<span class="hidden">' + a.re_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.re_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.re_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.re_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.re_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.re_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.re_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.re_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.re_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.re_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.re_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.re_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.re_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.re_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.re_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.re_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.re_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.re_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.re_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.re_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.re_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.re_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.re_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.re_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.re_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.re_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.re_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.re_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.re_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.re_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.re_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.re_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.re_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.re_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.re_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.re_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.re_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.re_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.re_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.re_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.re_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.re_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.re_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.re_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.re_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.re_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.re_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.re_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.re_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.re_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.re_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.re_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.re_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.re_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.re_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.re_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.re_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.re_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.re_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.re_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.re_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.re_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.re_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.re_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.re_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.re_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.re_data.is_deleted + '&quot;}" />']);
                                        var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                        $(h).attr("id", "todo_" + a.re_data.task_id), $("#todo_" + a.re_data.task_id + " td:nth-child(2)").addClass("todoDueDatepicker"), $("#todo_" + a.re_data.task_id + " td:nth-child(2)").attr("id", "toDoDue_" + a.re_data.task_id), $("#todo_" + a.re_data.task_id + " td:nth-child(3)").addClass("todoSchedulledDatepicker"), $("#todo_" + a.re_data.task_id + " td:nth-child(3)").attr("id", "schedulled_" + a.re_data.task_id), $(".todoSchedulledDatepicker").datepicker({
                                            startDate: -(1 / 0),
                                            format: JAVASCRIPT_DATE_FORMAT
                                        }).on("changeDate", function(a) {
                                            $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                        }), $(".todoDueDatepicker").datepicker({
                                            startDate: -(1 / 0),
                                            format: JAVASCRIPT_DATE_FORMAT
                                        }).on("changeDate", function(a) {
                                            $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                        })
                                    }
                                } else $("#todo_" + a.re_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
                        }), $.ajax({
                            type: "post",
                            url: SIDE_URL + "dashboardtask/set_update_task",
                            data: {
                                task_id: $("#task_id").val(),
                                redirect_page: $("#redirect_page").val()
                            },
                            async: !1,
                            success: function(a) {
                                function b(a) {
                                    return a.substr(0, 1).toUpperCase() + a.substr(1)
                                }
                                if (a = jQuery.parseJSON(a), $("#watch" + $("#task_id").val()).length) {
                                    var c = a.task_status_name;
                                    status_class = c.toLowerCase(), status_class = status_class.replace(" ", "");
                                    var d = "";
                                    d += '<tr id="watch' + a.task_data.task_id + '" role="row" class="odd">', d += '<td title="' + a.task_data.task_description + '" class="sorting_1">', d += "1" == a.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>", d += "</td>", d += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", d += '<td class="hidden-480">' , d +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', d +=  "</td>", d += '<td><span class="label label-' + status_class + '">' + c + "</span></td>", d += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\'' + a.watch_id + "','" + a.task_data.task_id + '\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>', $("#watch" + $("#task_id").val()).replaceWith(d)
                                }
                                if ($("#last_login_" + $("#task_id").val()).length) {
                                    var e = "";
                                    e += '<tr id="last_login_' + a.task_data.task_id + '" role="row" class="odd">', e += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(a.task_data.task_title) + "</a>", e += '</td><td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", e += "<td>" + a.task_data.task_priority + "</td>", e += "</tr>", $("#last_login_" + $("#task_id").val()).replaceWith(e)
                                }
                            }
                        }), "from_teamdashboard" == $("#redirect_page").val() ? ($.ajax({
                            type: "post",
                            url: SIDE_URL + "user/teamdashcharttime",
                            data: {
                                mytask: TEAM_MY_TASK,
                                teamtask: TEAM_TASK
                            },
                            async: !1,
                            success: function(a) {
                                $(".ajax_team_time_data").html(a), google.load("visualization", "1", {
                                    packages: ["corechart"],
                                    callback: drawChart
                                })
                            },
                            error: function(a) {
                                console.log("Ajax request not recieved!")
                            }
                        }), $.ajax({
                            type: "post",
                            url: SIDE_URL + "user/teamdashchartcategory",
                            data: {
                                taskByCat_tot: TASK_BY_CAT_TOT
                            },
                            async: !1,
                            success: function(a) {
                                $(".ajax_team_category_data").html(a), google.load("visualization", "1", {
                                    packages: ["corechart"],
                                    callback: drawChartcat
                                })
                            },
                            error: function(a) {
                                console.log("Ajax request not recieved!")
                            }
                        }), $.ajax({
                            type: "post",
                            url: SIDE_URL + "user/taskteam_previousweek",
                            data: {
                                user_id: LOG_USER_ID
                            },
                            async: !1,
                            success: function(a) {
                                $("#sortableItem_3").html(a)
                            },
                            error: function(a) {
                                console.log("Ajax request not recieved!")
                            }
                        })) : "from_dashboard" == $("#redirect_page").val() && ($.ajax({
                            type: "post",
                            url: SIDE_URL + "user/dashboardchart",
                            data: {
                                none: DASHBOARD_NONE,
                                low: DASHBOARD_LOW,
                                medium: DASHBOARD_MEDIUM,
                                high: DASHBOARD_HIGH
                            },
                            async: !1,
                            success: function(a) {
                                AmCharts.isReady = !0, $(".ajax_category_data").html(""), $(".ajax_category_data").html(a)
                            },
                            error: function(a) {
                                console.log("Ajax request not recieved!")
                            }
                        }), $.ajax({
                            type: "post",
                            url: SIDE_URL + "user/task_previousweek",
                            data: {
                                user_id: LOG_USER_ID
                            },
                            async: !1,
                            success: function(a) {
                                $("#sortableItem_3").html(a)
                            },
                            error: function(a) {
                                console.log("Ajax request not recieved!")
                            }
                        })), $("body").tooltip({
                            selector: ".tooltips"
                        })
                    }
                });
                else {
                    var f = $("#task_id").val();
                    $.ajax({
                        type: "post",
                        url: SIDE_URL + "dashboardtask/set_update_task",
                        data: {
                            task_id: f,
                            redirect_page: $("#redirect_page").val(),
                            type: $("#dashboard_priority").val(),
                            duration: $("#dashboard_duration").val()
                        },
                        async: !1,
                        success: function(a) {
                            function b(a) {
                                return a.substr(0, 1).toUpperCase() + a.substr(1)
                            }
                            if (a = jQuery.parseJSON(a), a.task_data.task_scheduled_date, a.today_date, "from_teamdashboard" == $("#redirect_page").val())
                                if (a.task_data.task_status_id != COMPLETED_ID)
                                    if ("1" == a.task_data.is_personal || "assign_other" == a.task_data.assign_status) $("#teamtodo_" + a.task_data.task_id).length && $("#teamtodo_" + a.task_data.task_id).remove(), $("#teampending_" + a.task_data.task_id).length && $("#teampending_" + a.task_data.task_id).remove(), $("#teamoverdue_" + a.task_data.task_id).length && $("#teamoverdue_" + a.task_data.task_id).remove();
                                    else if (a.task_data.task_allocated_user_id != LOG_USER_ID) {
                                if (1 == a.is_div_valid) {
                                    var c = a.task_data.task_title;
                                    if (c.length > 25) var d = c.substring(0, 22) + "...";
                                    else var d = c;
                                    var e = a.task_status_name;
                                    status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                    var f = "";
                                    if (f += '<tr id="teamtodo_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>", f += '<td class="teamdoDueDatepicker" id="teamDoDue_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="teamSchedulledDatepicker" id="teamSchedulled_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<td><span class="label label-' + status_class + '">' + e + "</span></td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teamtodo_" + a.task_data.task_id).length) $("#teamtodo_" + a.task_data.task_id).replaceWith(f), $(".teamdoDueDatepicker").datepicker({
                                        startDate: -(1 / 0),
                                        format: JAVASCRIPT_DATE_FORMAT
                                    }).on("changeDate", function(a) {
                                        $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                    }), $(".teamSchedulledDatepicker").datepicker({
                                        startDate: -(1 / 0),
                                        format: JAVASCRIPT_DATE_FORMAT
                                    }).on("changeDate", function(a) {
                                        $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                    });
                                    else {
                                        if ($("#teamtodolist tr td.dataTables_empty").length && $("#teamtodolist tr td.dataTables_empty").remove(), $("#teamtodolist").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                        else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                        var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                        $(h).attr("id", "teamtodo_" + a.task_data.task_id), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(2)").addClass("teamdoDueDatepicker"), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(2)").attr("id", "teamDoDue_" + a.task_data.task_id), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(3)").addClass("teamSchedulledDatepicker"), $("#teamtodo_" + a.task_data.task_id + " td:nth-child(3)").attr("id", "teamSchedulled_" + a.task_data.task_id), $(".teamdoDueDatepicker").datepicker({
                                            startDate: -(1 / 0),
                                            format: JAVASCRIPT_DATE_FORMAT
                                        }).on("changeDate", function(a) {
                                            $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                        }), $(".teamSchedulledDatepicker").datepicker({
                                            startDate: -(1 / 0),
                                            format: JAVASCRIPT_DATE_FORMAT
                                        }).on("changeDate", function(a) {
                                            $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                        })
                                    }
                                } else $("#teamtodo_" + a.task_data.task_id).length && $("#teamtodo_" + a.task_data.task_id).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                if (a.today_date) {
                                    var c = a.task_data.task_title;
                                    var cname = a.task_data.customer_name;
                                    if (cname) var d = c+' ('+cname+')';
                                    else var d = c;
                                    var e = a.task_status_name;
                                    status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                    var f = "";
                                    if (f += '<tr id="teampending_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>",f +='<td><span class="label label-sm label-' + status_class + '">' + e + '</span></td>' ,f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += "<td>" ,f += a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>',f += "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teampending_" + a.task_data.task_id).length) $("#teampending_" + a.task_data.task_id).replaceWith(f);
                                    else {
                                        if ($("#teampending_list tr td.dataTables_empty").length && $("#teampending_list tr td.dataTables_empty").remove(), $("#teampending_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + e,a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                        else var g = $("#filtertab2").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" +'<span class="label label-sm label-' + status_class + '">' + e + '</span>', a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                        var h = $("#filtertab2").dataTable().fnGetNodes(g);
                                        $(h).attr("id", "teampending_" + a.task_data.task_id)
                                    }
                                } else $("#teampending_" + a.task_data.task_id).length && $("#teampending_" + a.task_data.task_id).remove(), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                if (a.today_date > a.strtotime_due_date) {
                                    var c = a.task_data.task_title;
                                    if (c.length > 25) var d = c.substring(0, 22) + "...";
                                    else var d = c;
                                    var e = a.task_status_name;
                                    status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                    var f = "";
                                    if (f += '<tr id="teamoverdue_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>", f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += "<td>" + a.delay + "</td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teamoverdue_" + a.task_data.task_id).length) "1" != a.task_data.is_personal ? $("#teamoverdue_" + a.task_data.task_id).replaceWith(f) : $("#teamoverdue_" + a.task_data.task_id).remove();
                                    else {
                                        if ($("#teamoverdue_list tr td.dataTables_empty").length && $("#teamoverdue_list tr td.dataTables_empty").remove(), $("#teamoverdue_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.task_data.task_priority]);
                                        else var g = $("#filtertab3").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date, a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', a.delay, a.task_data.task_priority]);
                                        var h = $("#filtertab3").dataTable().fnGetNodes(g);
                                        $(h).attr("id", "teamoverdue_" + a.task_data.task_id)
                                    }
                                } else $("#teamoverdue_" + a.task_data.task_id).length && $("#teamoverdue_" + a.task_data.task_id).remove(), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
                            } else $("#teamtodo_" + a.task_data.task_id).length && $("#teamtodo_" + a.task_data.task_id).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teampending_" + a.task_data.task_id).length && $("#teampending_" + a.task_data.task_id).remove(), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teamoverdue_" + a.task_data.task_id).length && $("#teamoverdue_" + a.task_data.task_id).remove(), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                            else $("#teamtodo_" + a.task_data.task_id).length && $("#teamtodo_" + a.task_data.task_id).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teampending_" + a.task_data.task_id).length && $("#teampending_" + a.task_data.task_id).remove(), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#teamoverdue_" + a.task_data.task_id).length && $("#teamoverdue_" + a.task_data.task_id).remove(), 0 == $("#teamoverdue_list tr td").length && $("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                            else {
                                if ($("#watch" + $("#task_id").val()).length) {
                                    var e = a.task_status_name;
                                    status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                    var i = "";
                                    i += '<tr id="watch' + a.task_data.task_id + '" role="row" class="odd">', i += '<td title="' + a.task_data.task_description + '" class="sorting_1">', i += "1" == a.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\')" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(a.task_data.task_title) + "</a></td>", i += "</td>", i += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", i += '<td class="hidden-480">' , i +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', i +=  "</td>", i += '<td><span class="label label-' + status_class + '">' + e + "</span></td>", i += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\'' + a.watch_id + "','" + a.task_data.task_id + '\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>', $("#watch" + $("#task_id").val()).replaceWith(i)
                                }
                                if ($("#last_login_" + $("#task_id").val()).length) {
                                    var j = "";
                                    j += '<tr id="last_login_' + a.task_data.task_id + '" role="row" class="odd">', j += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips dashboard_master_' + a.task_data.master_task_id + '" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>" : '<td class="sorting_1"><a  data-placement="right" data-original-title="' + a.task_data.task_title + '" class="tooltips dashboard_master_' + a.task_data.master_task_id + '" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(a.task_data.task_title) + "</a>", j += '</td><td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", j += "<td>" + a.task_data.task_priority + "</td>", j += "</tr>", $("#last_login_" + $("#task_id").val()).replaceWith(j)
                                }
                                if ("assign_other" == a.assign_status) $("#todo_" + $("#task_id").val()).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#last_login_" + $("#task_id").val()).remove(), $("#teamoverdue_" + $("#task_id").val()).remove(), $("#teamtodo_" + $("#task_id").val()).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                else if (a.task_data.task_status_id != COMPLETED_ID)
                                    if (1 == a.is_div_valid) {
                                        var c = a.task_data.task_title;
                                        if (c > 40) var d = c.substring(0, 37) + "...";
                                        else var d = c;
                                        var e = a.task_status_name;
                                        status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                        var f = "";
                                        if (f += '<tr id="todo_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '" class="sorting_1">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" data-dismiss="modal" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)" >' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a>", f += '<td class="todoDueDatepicker" id="toDoDue_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span></td>", f += '<td class="todoSchedulledDatepicker" id="schedulled_' + a.task_data.task_id + '"><span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span></td>", f += "<td>" + a.task_data.task_priority + "</td>", f += '<td><span class="label label-' + status_class + '">' + e + '</span></td><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" /></tr>', $("#todo_" + a.task_data.task_id).length) $("#todo_" + a.task_data.task_id).replaceWith(f), $(".todoSchedulledDatepicker").datepicker({
                                            startDate: -(1 / 0),
                                            format: JAVASCRIPT_DATE_FORMAT
                                        }).on("changeDate", function(a) {
                                            $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                        }), $(".todoDueDatepicker").datepicker({
                                            startDate: -(1 / 0),
                                            format: JAVASCRIPT_DATE_FORMAT
                                        }).on("changeDate", function(a) {
                                            $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                        });
                                        else {
                                            if ($("#todolist tr td.dataTables_empty").length && $("#todolist tr td.dataTables_empty").remove(), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                            else var g = $("#filtertab1").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a>", '<span class="hidden">' + a.task_data.task_due_date + '</span><span class="date_edit">' + a.user_due_date + "</span>", '<span class="hidden">' + a.task_data.task_scheduled_date + '</span><span class="date_edit">' + a.user_scheduled_date + "</span>", a.task_data.task_priority, '<span class="label label-sm label-' + status_class + '">' + e + '</span><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />']);
                                            var h = $("#filtertab1").dataTable().fnGetNodes(g);
                                            $(h).attr("id", "todo_" + a.task_data.task_id), $("#todo_" + a.task_data.task_id + " td:nth-child(2)").addClass("todoDueDatepicker"), $("#todo_" + a.task_data.task_id + " td:nth-child(2)").attr("id", "toDoDue_" + a.task_data.task_id), $("#todo_" + a.task_data.task_id + " td:nth-child(3)").addClass("todoSchedulledDatepicker"), $("#todo_" + a.task_data.task_id + " td:nth-child(3)").attr("id", "schedulled_" + a.task_data.task_id), $(".todoSchedulledDatepicker").datepicker({
                                                startDate: -(1 / 0),
                                                format: JAVASCRIPT_DATE_FORMAT
                                            }).on("changeDate", function(a) {
                                                $(this).datepicker("hide"), updateSchedulledDate(a.date, $(this).attr("id"))
                                            }), $(".todoDueDatepicker").datepicker({
                                                startDate: -(1 / 0),
                                                format: JAVASCRIPT_DATE_FORMAT
                                            }).on("changeDate", function(a) {
                                                $(this).datepicker("hide"), updateDueDate(a.date, $(this).attr("id"))
                                            })
                                        }
                                    } else $("#todo_" + a.task_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                                else $("#todo_" + a.task_data.task_id).length && $("#todo_" + a.task_data.task_id).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
                                if (a.today_date) {
                                    var c = a.task_data.task_title;
                                    var cname = a.task_data.customer_name;
                                    if (cname) var d = c+' ('+cname+')';
                                    else var d = c;
                                    var e = a.task_status_name;
                                    status_class = e.toLowerCase(), status_class = status_class.replace(" ", "");
                                    var f = "";
                                    if (f += '<tr id="teampending_' + a.task_data.task_id + '" role="row" class="even">', f += '<td title="' + a.task_data.task_description + '">', f += "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + "</a></td>" : '<a  data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + b(c) + "</a></td>",f +='<td><span class="label label-sm label-' + status_class + '">' + e + '</span></td>' ,f += '<td><span class="hidden">' + a.task_data.task_due_date + "</span>" + a.user_due_date + "</td>", f += "<td>" ,f += a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>',f += "</td>", f += '<td class="hidden-480">' , f +=  a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>', f +=  "</td>", f += '<input type="hidden" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" id="task_data_' + a.task_data.task_id + '">', f += "</tr>", $("#teampending_" + a.task_data.task_id).length) $("#teampending_" + a.task_data.task_id).replaceWith(f);
                                    else {
                                        if ($("#teampending_list tr td.dataTables_empty").length && $("#teampending_list tr td.dataTables_empty").remove(), $("#teampending_list").append(f), "1" == a.task_data.is_master_deleted || "0" == a.task_data.master_task_id) var g = $("#filtertab5").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + c + '" class="tooltips" onclick="edit_task(this,\'' + a.task_data.task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + d + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" + '<span class="label label-sm label-' + status_class + '">' + e + '</span>',a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                        else var g = $("#filtertab5").dataTable().fnAddData(['<a data-placement="right" data-original-title="' + d + '" class="tooltips" onclick="open_seris(this,\'' + a.task_data.task_id + "','" + a.task_data.master_task_id + "','" + a.is_chk + '\');" href="javascript:void(0)">' + b(d) + '</a><input type="hidden" id="task_data_' + a.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + a.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + a.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + a.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + a.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + a.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + a.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + a.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + a.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + a.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + a.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + a.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + a.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + a.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + a.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + a.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + a.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + a.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + a.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + a.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + a.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + a.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + a.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + a.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + a.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + a.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + a.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + a.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + a.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + a.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + a.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + a.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + a.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + a.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + a.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + a.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + a.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + a.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + a.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + a.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + a.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + a.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + a.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + a.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + a.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + a.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + a.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + a.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + a.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + a.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + a.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + a.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + a.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + a.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + a.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + a.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + a.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + a.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + a.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + a.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + a.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + a.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + a.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + a.task_data.is_deleted + '&quot;}" />', '<span class="hidden">' + a.task_data.task_due_date + "</span>" +'<span class="label label-sm label-' + status_class + '">' + e + '</span>', a.user_due_date, a.task_owner_image!=''?'<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_owner_name+'" alt="" src="'+a.task_owner_image+'" />   ':'<span class="tooltips" data-original-title="'+a.task_owner_name+'" data-letters="'+a.task_owner_image_name+'"></span>', a.task_allocated_user_image!='' ? '<img class="tooltips capacity_images" data-placement="left" data-original-title="'+a.task_allocated_user_name+'" alt="" src="'+a.task_allocated_user_image+'" />   ':'<span class="tooltips" data-original-title="'+ a.task_allocated_user_name +'" data-letters="'+a.task_allocated_user_image_name+'"></span>']);
                                        var h = $("#filtertab5").dataTable().fnGetNodes(g);
                                        $(h).attr("id", "teampending_" + a.task_data.task_id)
                                    }
                                } else $("#teampending_" + a.task_data.task_id).length && $("#teampending_" + a.task_data.task_id).remove(), 0 == $("#teampending_list tr td").length && $("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
                            }
                            "from_teamdashboard" == $("#redirect_page").val() ? ($.ajax({
                                type: "post",
                                url: SIDE_URL + "user/teamdashcharttime",
                                data: {
                                    mytask: TEAM_MY_TASK,
                                    teamtask: TEAM_TASK
                                },
                                async: !1,
                                success: function(a) {
                                    $(".ajax_team_time_data").html(a), google.load("visualization", "1", {
                                        packages: ["corechart"],
                                        callback: drawChart
                                    })
                                },
                                error: function(a) {
                                    console.log("Ajax request not recieved!")
                                }
                            }), $.ajax({
                                type: "post",
                                url: SIDE_URL + "user/teamdashchartcategory",
                                data: {
                                    taskByCat_tot: TASK_BY_CAT_TOT
                                },
                                async: !1,
                                success: function(a) {
                                    $(".ajax_team_category_data").html(a), google.load("visualization", "1", {
                                        packages: ["corechart"],
                                        callback: drawChartcat
                                    })
                                },
                                error: function(a) {
                                    console.log("Ajax request not recieved!")
                                }
                            }), $.ajax({
                                type: "post",
                                url: SIDE_URL + "user/taskteam_previousweek",
                                data: {
                                    user_id: LOG_USER_ID
                                },
                                async: !1,
                                success: function(a) {
                                    $("#sortableItem_3").html(a)
                                },
                                error: function(a) {
                                    console.log("Ajax request not recieved!")
                                }
                            })) : "from_dashboard" == $("#redirect_page").val() && ($.ajax({
                                type: "post",
                                url: SIDE_URL + "user/dashboardchart",
                                data: {
                                    none: DASHBOARD_NONE,
                                    low: DASHBOARD_LOW,
                                    medium: DASHBOARD_MEDIUM,
                                    high: DASHBOARD_HIGH
                                },
                                async: !1,
                                success: function(a) {
                                    AmCharts.isReady = !0, $(".ajax_category_data").html(""), $(".ajax_category_data").html(a)
                                },
                                error: function(a) {
                                    console.log("Ajax request not recieved!")
                                }
                            }), $.ajax({
                                type: "post",
                                url: SIDE_URL + "user/task_previousweek",
                                data: {
                                    user_id: LOG_USER_ID
                                },
                                async: !1,
                                success: function(a) {
                                    $("#sortableItem_3").html(a)
                                },
                                error: function(a) {
                                    console.log("Ajax request not recieved!")
                                }
                            })), $("body").tooltip({
                                selector: ".tooltips"
                            })
                        }
                    })
                }
            else if ("from_project" == b) {
                var i = $("#select_task").val(),
                    j = $("#typefilter1 li.active").attr("id");
                if ($("#task_id").val()) {
                    if (1 == $("#recurrence").prop("checked")) var f = "child_" + $("#task_id").val();
                    else var f = $("#task_id").val();
                    "0" != $("#master_task_id").val() && $.ajax({
                        type: "post",
                        url: SIDE_URL + "project/next_noncompleted_recurrence",
                        data: {
                            task_id: $("#master_task_id").val()
                        },
                        async: !1,
                        success: function(a) {
                            if (a) {
                                a = jQuery.parseJSON(a);
                                var b = $("#select_task_assign").val(),
                                    c = $("#select_task_status").val();
                                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    async: !1,
                                    data: {
                                        task_id: a.task_id,
                                        type: c,
                                        user_id: b
                                    },
                                    success: function(a) { 
                                        App.init(), a ? ($("#task_tasksort_" + $("#task_id").val()).length && $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "project/set_update_task",
                                            data: {
                                                task_id: $("#task_id").val(),
                                                type: c,
                                                user_id: b
                                            },
                                            async: !1,
                                            success: function(a) { 
                                                $("#task_tasksort_" + $("#task_id").val()).replaceWith(a)
                                            }
                                        }), "0" != $("#task_section_id").val() ? $("#taskmove_" + $("#task_subsection_id").val() + "_" + $("#task_section_id").val()).append(a) : $("#panel-body1_" + $("#task_subsection_id").val() + " div.add_new_task_div").before(a)) : $("#task_tasksort_" + $("#task_id").val()).remove()
                                    }
                                })
                            }
                            $("#typefilter1 li").removeClass("active"), $("#typefilter1 li[id=" + c + "]").addClass("active")
                        }
                    });
                    var i = $("#select_task_assign").val(),
                        j = $("#select_task_status").val();
                    $.ajax({
                        type: "post",
                        url: SIDE_URL + "project/set_update_task",
                        data: {
                            task_id: f,
                            type: j,
                            user_id: i
                        },
                        async: !1,
                        success: function(a) {
                           
                            f.indexOf("child") >= 0 && $("#main_" + $("#task_id").val()).remove(), 
                                    App.init(); 
                                      if(0 == $("#task_tasksort_" + $("#task_id").val() + " ul").length)
                                        { 
                                         ("0" != $("#hidden_section_id").val() ? $("#" + $("#taskmove_" + $("#task_subsection_id").val() + "_" + $("#hidden_section_id").val()).children().last().attr('id')).before(a) : $("#panel-body1_" + $("#task_subsection_id").val() + " div.add_new_task_div").before(a))
                                         } 
                                    a ? $("#old_task_id").val() ? $("#old_task_id").val() == $("#task_id").val() ? $("#task_tasksort_" + $("#task_id").val()).length && $("#task_tasksort_" + $("#task_id").val()).replaceWith(a) : $("#task_tasksort_" + $("#old_task_id").val()).replaceWith(a) : $("#task_tasksort_" + $("#task_id").val()).length ? $("#task_tasksort_" + $("#task_id").val()).replaceWith(a) : 0 == $("#task_tasksort_" + $("#task_id").val() + " ul").length && ("0" != $("#task_section_id").val() ? $("#" + $("#taskmove_" + $("#task_subsection_id").val() + "_" + $("#hidden_section_id").val()).children().last().attr('id')).before(a) : $("#panel-body1_" + $("#task_subsection_id").val() + " div.add_new_task_div").before(a)) : $("#task_tasksort_" + $("#task_id").val()).remove(), $("#typefilter1 li").removeClass("active"), $("#typefilter1 li[id=" + j + "]").addClass("active")
                        }
                    })
                }
                var j = $("#typefilter1 li.active").attr("id");
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "project/task_counter",
                    data: {
                        user_id: i,
                        project_id: $("#general_project_id").val(),
                        type: j
                    },
                    async: !1,
                    success: function(a) {
                        $("#task_counter").html(a), $("#typefilter1 li").removeClass("active"), $("#typefilter1 li[id=" + j + "]").addClass("active")
                    },
                    error: function(a) {
                        console.log("Ajax request not recieved!")
                    }
                })
            }
            else if ("from_customer"== b){ 
                var old_task_id= $("#old_task_id").val();
                var from = $("#from").val();
                var child_task_id = $("#child_task_id").val();
                
                        $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "customer/set_update_task",
                                    data: {
                                        task_id: c,
                                        redirect_page : b,
                                        customer_id:$("#hide_customer_id").val()
                                        },
                                    success: function(data) {
//                                        if(from=='series'){
//                                              $("#listtask_" + child_task_id).length ? $("#listtask_" + child_task_id).replaceWith(data) : '';
//                                        }else{
//                                              $("#listtask_" + old_task_id).length ? $("#listtask_" + old_task_id).replaceWith(data) : $("#taskTable > tbody").prepend(data);
//                                        }
                                        $('#task_filter_option').show(),filter();
                                        
                                    },
                                    error: function(e) {
                                        console.log("Ajax request not recieved!")
                                    }
                            });
                
            }
            $("#full-width").modal("hide")
            $("#full-width").on('hidden.bs.modal', function(){
                $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
            })
        }
    }), $("#frm_task_general :input,#frm_task_general select,#task_due_date").on("keypress", function(a) {
        if (0 == $("#task_description").is(":focus") && 13 === a.keyCode) {
            a.preventDefault(), $("#event").val(13);
            var b = $(this).attr("class");
            b.indexOf("task-input") != -1 ? $(this).change() : b.indexOf("task-chk-input") != -1 && $(this).click()
        }
    }), $('form[name="frm_add_allocation"] :input,form[name="frm_add_allocation"] select').on("keypress", function(a) {
        if (13 === a.keyCode) {
            a.preventDefault(), $("#event").val(13);
            var b = $(this).attr("class");
            b.indexOf("task-input") != -1 ? $(this).change() : b.indexOf("task-chk-input") != -1 && $(this).click()
        }
    }), $('form[name="frm_add_recurrence"] :input,form[name="frm_add_recurrence"] select').on("keypress", function(a) {
        if (13 === a.keyCode) {
            a.preventDefault(), $("#event").val(13);
            var b = $(this).attr("class");
            b.indexOf("task-input") != -1 ? $(this).change() : b.indexOf("task-chk-input") != -1 && $(this).click()
        }
    }), $("#task_time_estimate,#task_time_spent").on("keypress", function(a) {
        if (13 === a.keyCode) {
            a.preventDefault(), $("#event").val(13);
            var b = $(this).attr("class");
            b.indexOf("task-input") != -1 && $(this).blur()
        }
    }), $("#selecctall").click(function() {
        $(".checkbox1").prop("checked", !0), $(".checkbox1").closest("span").addClass("checked"), $("#is_multi_changed").val(1)
    }), $("#none").click(function() {
        $(".checkbox1").prop("checked", !1), $(".checkbox1").closest("span").removeClass("checked"), $("#is_multi_changed").val(1)
    }), $("input[name='task_allocated_user_id[]']").on("change", function() {
        $("#is_multi_changed").val(1)
    })
});

function delete_series_task(){ 
    $("#series_task_deletion").modal("hide");
    var id= $("#task_id").val();
    var from=$("input[name='series_option']:checked").val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/delete_task_series",
        data: {
            task_id:id,
            current_date :$("#current_date").val(),
            start_date: $("#week_start_date").val(),
            end_date: $("#week_end_date").val(),
            action: $("#week_action").val(),
            redirect :$("#redirect_page").val(),
            active_menu:$("#redirect_page").val(),
            from: from
        },
        success:function(rdata){ 
            var rdata = jQuery.parseJSON(rdata);
            if ("series" == from && ('weekView'==$("#redirect_page").val() || 'NextFiveDay' == $("#redirect_page").val()) ) {
                var jsonObj = [];
		var k = $("#task_estimate_time_" + id).val();
                var l = $("#task_spent_time_" + id).val();
                var h = $("#task_time_" + id).html();
                var j=""; 
                if(!k)
                {
                    if(h) j = h.split("/"),
                         k = get_minutes(j[0]),
                        l = get_minutes(j[1]);
			else k = "0",
                 l = "0";
                }
		$("#main_" + id).remove();
                var v = parseInt($(".week_master_" + id).length);
                for (i = 0; i < v; i++) {
                    var g = $(".week_master_" + id).parent("div").attr("id");
                    g = g.replace("week_", "");
                    var w = $(".week_master_" + id).attr("id");
                    $("#" + w).remove();
                    
                 var c1 = parseInt($("#capacity_"+g).attr('data-time'));
                    var e1 = $("#est_"+g).attr('data-time');
                    var s1 = $("#spent_"+g).attr('data-time');
                    var edif = parseInt(e1) - parseInt(k),
                        sdef = parseInt(s1) - parseInt(l);
		    var data = {
                        id: g,
                        capacity: c1,
                        estimate_time: edif,
                        spent_time: sdef,
                        title: 'Capacity: '+hoursminutes(c1)+'<br>Estimate Time: '+hoursminutes(edif)+'<br>Time Spent: '+hoursminutes(sdef)
                    }
                    jsonObj.push(data);
                    //u > m ? $("#est_" + g).addClass("red") : $("#est_" + g).removeClass("red"), $("#est_" + g).html(p), $("#spent_" + g).html(k)
                }
		$.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/update_multiple_progress_bar",
                    data: {
                        data:JSON.stringify(jsonObj)
                    },
                    success: function(pr) { //console.log('progress_'+JSON.stringify(pr));
                        $.each($.parseJSON(pr), function(key,value){
                            $('#progress_'+value.id).html(value.html);
                        });
                    },error:function(){
                        console.log("ajax request not received!");
                       
                    }
                });
            }
            if ("future" == from && ('weekView'==$("#redirect_page").val() || 'NextFiveDay' == $("#redirect_page").val())){
               var jsonObj = [];
                //$("#main_" + id).remove();
                var k = $("#task_estimate_time_" + id).val();
                var l = $("#task_spent_time_" + id).val();
                var h = $("#task_time_" + id).html();
                var j="";
                if(!k)
                {
                    if(h) j = h.split("/"),
                         k = get_minutes(j[0]),
                        l = get_minutes(j[1]);
                    else k = "0",
                        l = "0";
                }
                var v = parseInt($(".week_master_" + id).length);
                var g1 = $(".week_master_" + id).parent("div").attr("id"),
                      g1 = g1.replace("week_", ""),
                      w1 = $(".week_master_" + id).attr("id"),
                     y1 = w1.substring(w1.lastIndexOf("_") + 1);
                  for (i = 0; i < v; i++) { 
                    var g = $(".week_master_" + id).parent("div").attr("id"),
                        g = g.replace("week_", ""),
                        w = $(".week_master_" + id).attr("id"),
                       y = w.substring(w.lastIndexOf("_") + 1);
                        if(y==0){
                                var I = Number(i) + Number(y);
                                var b = "main_child_" + id + "_" + I;
                                var E = b.substring(b.lastIndexOf("_") + 1);
                                 if (E > days ) $("#" + b).remove();
                        }else if(days){ 
                                var lll = Number(y1) + Number(i);
                                if(lll>days){
                                var I = lll;
                                var b = "main_child_" + id + "_" + I;
                                    //w = v.substring(v.lastIndexOf("_") + 1);
                                $("#" + b).remove();
                                }
                        }else{
                                
                            }
		}
 
		var start_date1 = $("#week_start_date").val();
                var end_date1 = $("#week_end_date").val();
                var current_date = new Date($("#current_date").val());
                var startDate = new Date(start_date1);
                var endDate = new Date(end_date1);

            for (var iDate = startDate; iDate <= endDate; iDate.setDate(iDate.getDate() + 1)){
                var getdate = iDate.getFullYear()+'-'+("0" + (iDate.getMonth()+1)).slice(-2)+'-'+("0" + (iDate.getDate())).slice(-2);
                var r = $("#day_strtotime_"+getdate).val();
     
                if(((iDate.getTime()/1000)>(current_date.getTime()/1000)))
                { 
                    var c1 = parseInt($("#capacity_"+r).attr('data-time'));
                    var e1 = $("#est_"+r).attr('data-time');
                    var s1 = $("#spent_"+r).attr('data-time');
                    var edif = parseInt(e1) - parseInt(k),
                        sdef = parseInt(s1) - parseInt(l);
                    var data = {
                                id: r,
                                capacity: c1,
                                estimate_time: edif,
                                spent_time: sdef,
                                title: 'Capacity: '+hoursminutes(c1)+'<br>Estimate Time: '+hoursminutes(edif)+'<br>Time Spent: '+hoursminutes(sdef)
                                }
                    jsonObj.push(data);
                }
            }
				
                  $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/update_multiple_progress_bar",
                    data: {
                        data:JSON.stringify(jsonObj)
                    },
                    success: function(pr) {
                        $.each($.parseJSON(pr), function(key,value){
                            $('#progress_'+value.id).html(value.html);
                        });
                    },error:function(){
                        console.log("ajax request not received!");
                       
                    }
                });
            }
            /**
             * below two condition for delete task from calendar.
             */
            if(from == "series" && 'from_calendar'==$("#redirect_page").val() ){
                            $("#task_"+id).remove();
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
                        
                        if(from == "future" && 'from_calendar'==$("#redirect_page").val()){
                           
                                var task_pos =days;
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
            $("#delete_task").modal("hide"), toastr['success']("Task '"+rdata.task_title+"' has been deleted.", "");
        }
    });
}

function set_swimlane(){
        
   //   $("#task_swimlane_id_loading").show();
     $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/saveTask",
                        data: {
                            name: $("#task_swimlane_id").attr('name'),
                            value: $("#task_swimlane_id").val(),
                            task_id: $("#task_id").val(),
                            task_scheduled_date: $("#task_scheduled_date").val(),
                            redirect_page: $("#redirect_page").val()
                        },
                        async: !1,
                        success: function(b) {
                            $("#task_id").val(b), 
                            $("#allocation_task_id").val(b),
                            $("#pre_task_id").val(b),
                            $("#step_task_id").val(b), 
                            $("#files_task_id").val(b),
                            $("#link_files_task_id").val(b), 
                            $("#comment_task_id").val(b), 
                            $("#freq_task_id").val(b), 
                            $("#search_task_id").val(b), 
                            $("#main_task_due_date").val($("#tmp_task_due_date").val()), 
                            $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1)
                        }
            });
}

function delete_kanban_task(){
    
    var id= $("#task_id").val();
    var from=$("input[name='series_option']:checked").val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/delete_task_series",
        data: {
            task_id:id,
            current_date :$("#current_date").val(),
            from:from
         },
        success:function(data){
            var data = jQuery.parseJSON(data);
            if(data.response=='done'){
                $("#main_child_" +id).remove();
                $("#series_task_deletion").modal("hide");
            }else{
                $("#series_task_deletion").modal("hide");
            }
            toastr['success']("Task '"+data.task_title+"' has been deleted.", "");
          }
        });
}

$(document).ready(function(){
    $(document).on("change","#section_id1",function() { 
                var a = $(this).attr("id");
                $("#" + a + "_loading").show();
                var b = "subsection_id";
                if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var c = $("#frm_task_general").serialize();
                else var c = $(this).val();
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: b,
                        value: c,
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(b) {
                        $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#" + a + "_loading").hide()
                    }
                })
            });
            $(document).on("blur",".add_task_new",function() { 
                var id=$(this).attr('id');
                id=id.replace('task_input_',''); 
                var redirect_page = $("#redirect_page").val();
                var task_date = $("#task_create_date_"+id).val();
                var text = '';
                if(redirect_page == 'from_kanban'){
                    var res = id.split("_");
                    text += '<div  onclick="add_task_kanban('+res+');" class="red new_addTask before_timer"  id="icon_addTask_'+id+'" >',
                    text += '<i class="icon-plus task_adding_icon" ></i>',
                    text += ' </div>';
                }else{
                    text += '<div  onclick="add_task_title(\''+id+'\',\''+task_date+'\');" class="red new_addTask" id="icon_addTask_'+id+'">',
                    text += '<i class="icon-plus task_adding_icon" ></i>',
                    text += ' </div>';
                }    
              //  console.log(text);
                $('#add_task_new_'+id).replaceWith(text);
            });
            
            $(document).on('change',"#updated_subCategory .task-input",function() { 
                var a = $(this).attr("id");
                $("#" + a + "_loading").show();
                var b = $(this).attr("name");
                if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var c = $("#frm_task_general").serialize();
                else var c = $(this).val();
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: b,
                        value: c,
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val()
                    },
                    async: !1,
                    success: function(b) { 
                        $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#" + a + "_loading").hide()
                    }
                });
            });
            $('#add_to_watch').click(function(){
                var value = 0;
                var addC = 'green';
                var removeC = 'red';
                var addT = 'Add to Watch List';
                if($(this).attr('class').indexOf('green') != -1)
                {
                    value = 1;
                    addC = 'red';
                    removeC = 'green';
                    addT = 'Remove from Watch List';
                }
                var c = $("#task_id").val(),
            d = c.indexOf("child");
            if ("" == $("#task_id").val() || $("#task_id").val().indexOf("child") >= 0) var data = $("#frm_task_general").serialize();
                        else var data = value;
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "task/saveTask",
                    data: {
                        name: 'watch_list',
                        value: data,
                        task_id: $("#task_id").val(),
                        task_scheduled_date: $("#task_scheduled_date").val(),
                        redirect_page: $("#redirect_page").val(),
                        sub_val:value
                    },
                    async: !1,
                    success: function(b) { 
                        $('#add_to_watch').removeClass(removeC),$('#add_to_watch').addClass(addC),$('#add_to_watch').html(addT);
                        $("#task_id").val(b), $("#allocation_task_id").val(b), $("#pre_task_id").val(b), $("#step_task_id").val(b), $("#files_task_id").val(b), $("#link_files_task_id").val(b), $("#comment_task_id").val(b), $("#freq_task_id").val(b), $("#search_task_id").val(b), $("#main_task_due_date").val($("#tmp_task_due_date").val()), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#" + a + "_loading").hide()
                    }
                });
            });				
});
/****************************************************************************csrtm-r*******************************************************************/
/*C0llapse Menu 06/04/2017 */
$(document).ready(function(){
        $('.section_editable').editable({
           url: SIDE_URL+"project/update_sectionName",
           inputclass: 'Section_edit',
           type: 'post',
           pk: 1,
           mode: 'inline',
           showbuttons: true,
           validate: function (value) {
                if ($.trim(value) == ''){ return 'This field is required';};
           },
           success : function(DivisionData){
           }
       });
          $('.subsection_editable').editable({
           url: SIDE_URL+"project/update_sectionName",
           inputclass: 'Subsection_edit',
           type: 'post',
           pk: 1,
           mode: 'inline',
           showbuttons: true,
           validate: function (value) {
                if ($.trim(value) == ''){ return 'This field is required';};
           },
           success : function(DivisionData){
           }
       });
       
       $(document).on("click","div.ui-sortable",function(){
           if ($(".txt-section-task").is(":focus")) {
               var value = $(".txt-section-task").val();
               if(value !=""){
                  cstm_task_add_event($(".txt-section-task").attr('id'),'');
                  
                    $(".txt-section-task").val("");
               }
          }
           
       });
       
       $(document).on("blur",".txt-section-task",function(e){
          
               var value = $(".txt-section-task").val();
               if(value !=""){
                   cstm_task_add_event($(this).attr('id'),'');
                   
                    $(".txt-section-task").val("");
               }else{}
       });
             
});
 var task_data='';
 var rdy_id='';
function my_custom_task_edit(project_id,date_format,sub_sec_id,sec_id,name){
    name = typeof name !== 'undefined' ? name : '';
   task_data=project_id+'&'+date_format+'&'+sub_sec_id+'&'+sec_id+'&'+name;
  var redirect_page = $("#redirect_page").val();
   var input='';
     input+='<div id="add_tsk_'+sub_sec_id+'" class="cstm_flds_task add-section-task-txt-container" >';
     input+='<input type="text"  class="col-md-4 m-wrap txt-section-task"  placeholder="Add a Task Name" id="task_inp_ttl_'+sub_sec_id+'" name="task_inp_ttl'+sub_sec_id+'"  onkeydown="Javascript: if (event.keyCode==13||event.keyCode==9) cstm_task_add_event(this.id,event.keyCode);" >';
        for (var key in sttsar) {
                   if (sttsar.hasOwnProperty(key)) {
                          var val = sttsar[key];
                              
                              if(val['task_status_name']=='Ready'){
                                 
                                 rdy_id= val['task_status_id'];
                              }else{
                                
                              }
                        }
                    }       
  
     input+='<input type="hidden" name="task_input_data_" id="task_input_data_'+sub_sec_id+'" value="task_priority=None&task_owner_id='+LOG_USER_ID+'&hdn_locked_due_date=0&hdn_is_personal=0&task_time_estimate_hour=0&task_time_estimate_min=0&old_task_time_estimate_min=0&task_time_spent_hour=0&task_time_spent_min=0&old_task_time_spent_hour=0&old_task_time_spent_min=0&task_orig_scheduled_date=&task_orig_due_date=&redirect_page='+redirect_page+'&kanban_order=&calender_order=&genral_swimlane_id='+DEFAULT_SWIMLANE+'&master_task_id=0&strtotime_scheduled_date='+date_format+'&task_id=&old_task_id=&from=&task_subsection_id='+sub_sec_id+'&task_section_id='+sec_id+'&general_project_id='+project_id+'&allocated_customer_id='+PROJECT_CUSTOMER_ID+'">';
     input+='</div>';
     $("#pro_button_"+sub_sec_id).replaceWith(input);
     $("#add_tsk"+sub_sec_id).focus();
     $("#task_inp_ttl_"+sub_sec_id).focus();
      $( function() {
         $( ".add_task_due_date" ).datepicker({
             "setDate": new Date(),
             format:'dd/mm/yyyy',
             autoclose: true
         });
    } );
     
}

$(document).keyup(function(e) {
		    if (e.which == 27) {
		        var asd= task_data.split("&");
                        var text='';
                        text='<button onclick="my_custom_task_edit(\''+asd[0]+'\',\''+asd[1]+'\',\''+asd[2]+'\',\''+asd[3]+'\',\''+asd[4]+'\');datapass(\''+asd[2]+'\',\''+asd[3]+'\',\''+asd[4]+'\');" href="javascript:void(0)" type="button" name="task" class="btn-new green unsorttd addtskbtn" id="pro_button_'+asd[2]+'" style="min-width: 0px !important;">Add Task</button>';
                        $("#add_tsk_"+asd[2]).replaceWith(text);
		    }
});

function cstm_task_add_event(id,eventkey){
 
    var ids=id.split('_');
    var id=ids[3];
     
   var title=$('#task_inp_ttl_'+id).val();
   var status=$('#task_status_id_'+id).val() ?$('#task_status_id_'+id).val() :rdy_id;
   var duedate='';
  title =  encodeURIComponent(title);
    if(title != '' && title != "0")
    {       var taskhdn_data=$("#task_input_data_"+id).val();
        var atd= task_data.split("&");
            var datastring='task_title='+title+'&old_task_status_id='+status+'&tmp_task_due_date='+duedate+'&old_task_due_date='+duedate+'&task_scheduled_date='+duedate+'&'+taskhdn_data;
            $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/saveTask",
                        data: {
                            name: "task_title",
                            value: datastring,
                            task_id:'' ,
                            task_scheduled_date: duedate,
                            redirect_page: $("#redirect_page").val()
                        },
                        async: !1,
                        success: function(tid) {
                         
                        if(eventkey == 13)
                        {
                             
                            edit_task(this,tid,'');
                            window.setTimeout(function ()
                                       {
                                           
                                           $('#task_inp_ttl_'+id).focus();
                                       }, 0);
                                
                        }
                        else
                        {
                           
                            var g = LOG_USER_ID,
                             h = status;
                                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    data: {
                                        task_id: tid,
                                        type: h,
                                        user_id: g
                                    },
                                    async: !1,
                                    success: function(a) { 
                                     if(atd[3]!= 0){
                                       $("#" + $("#taskmove_" + atd[2] + "_" + atd[3]).children().last().attr('id')).before(a);
                                    
                                     }else{
                                      
                                      $("#panel-body1_" +atd[2] + " div.add_new_task_div").before(a) 
                                       
                                   }
    }
                                });
                            
                            
                            
                            
                         
                        }
                        }
                  });
                  //call function for Add task New 
                                  
                                   $('#task_inp_ttl_'+id).val('');
                                   $('#task_status_id_'+id).val(rdy_id);
                                    $('#task_inp_ddt_'+id).val('');
                                      
                                    window.setTimeout(function ()
                                       {
                                           $('#task_inp_ttl_'+id).focus();
                                       }, 0);                                      
                        
       }
       else{
           alertify.alert("Please Enter Task title!"); 
        
    }
        
    }
    var ComponentsEditors=function(){var t=function(){jQuery().wysihtml5&&$(".desc_editor").size()>0&&$(".desc_editor").wysihtml5({stylesheets:["../default/assets/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]})},s=function(){jQuery().wysihtml5&&$(".comment_editor").size()>0&&$(".comment_editor").wysihtml5({stylesheets:["../default/assets/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]})};return{init:function(){t(),s()}}}();

$(document).ready(function(){
    $(document).on("click","#other_user_task",function(){ 
        if($(this).is(':checked')){
            var a = '1';
        }else{
            var a = '0';
        }
        $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/set_show_other_user_task",
            data: {
                other_user_task:a
            },
            async: !1,
            success: function (a) {
                change_view($("#week_start_date").val()+"#"+$("#week_end_date").val()+"#current");
            }
        });
    });
});