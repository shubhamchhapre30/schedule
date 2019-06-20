function opendelete(a, b, c, d, e) {
    $("#delete_series span").removeClass("checked"),
            $("#delete_ocuurence span").removeClass("checked"),
            $("#delete_future span").removeClass("checked"), 
            $("#delete_series").attr("onclick", "delete_rightClick_task('" + b + "','" + c + "','" + d + "','" + e + "','series','" + a + "')"), 
            $("#delete_future").attr("onclick", "delete_rightClick_task('" + b + "','" + c + "','" + d + "','" + e + "','future','" + a + "')"), 
            $("#delete_ocuurence").attr("onclick", "delete_rightClick_task('" + a + "','" + c + "','" + d + "','" + e + "','ocuurence','')"),
            $("#delete_task").modal("show")
}

function delete_rightClick_task(a, b, c, d, e, f) {
    
    var e = e || 1,
        g = f || a,
        h = $("#task_data_" + a).val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/delete_task",
        data: {
            task_id: a,
            task_data: h,
            due_date: b,
            redirect: c,
            start_date: $("#week_start_date").val(),
            end_date: $("#week_end_date").val(),
            action: $("#week_action").val(),
            active_menu: c,
            from: e,
            current_date: $("#current_date").val()
        },
        success: function(b) { 
            if ($("#est_" + d).length > 0) { 
                 var k = $("#task_estimate_time_" + a).val();
                 var l = $("#task_spent_time_" + a).val();
                var h = $("#task_time_" + a).html();
                var j="";
                if(!k)
                {
                    if(h) j = h.split("/")
                         k = get_minutes(j[0]),
                        l = get_minutes(j[1]);
                }
                var c1 = parseInt($("#capacity_"+d).attr('data-time'));
                    var e1 = $("#est_"+d).attr('data-time');
                    var s1 = $("#spent_"+d).attr('data-time');
                    var edif = parseInt(e1) - parseInt(k),
                            sdef = parseInt(s1) - parseInt(l);
                    $('#progress_'+d).empty();
                    $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/update_progress_bar",
                    data: {
                        id: d,
                        capacity: c1,
                        estimate_time: edif,
                        spent_time: sdef,
                        title: 'Capacity: '+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(edif)+'<br>Time Spent: '+hoursminutes(sdef)
                    },
                    success: function(progress) {
                        $('#progress_'+d).html(progress)
                    }
                });
            }
            if ("ocuurence" == e && $("#main_" + g).remove(), "future" == e) {
                var q = parseInt($(".week_master_" + a).length);
                var jsonObj = [];
                var r1 = $(".week_master_" + a).parent("div").attr("id"),
                        r1 = r1.replace("week_", ""),
                        s1 = $(".week_master_" + a).attr("id"),
                        t1 = s1.substring(s1.lastIndexOf("_") + 1);
                for (i = 0; i < q; i++) {
                    var r = $(".week_master_" + a).parent("div").attr("id"),
                        r = r.replace("week_", ""),
                        s = $(".week_master_" + a).attr("id"),
                        t = s.substring(s.lastIndexOf("_") + 1); 
                    if (0 == t) {
                        var u = Number(i) + Number(t),
                            v = "main_child_" + a + "_" + u,
                            w = v.substring(v.lastIndexOf("_") + 1);
                        w > b && $("#" + v).remove()
                    }else if(b){ 
                        //var ll = t1;
                       var lll = Number(t1) + Number(i);
                      // console.log(lll);
                        if(lll>b){
                        var u =  lll,
                            v = "main_child_" + a + "_" + u;
                            //w = v.substring(v.lastIndexOf("_") + 1);
                        $("#" + v).remove();
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
     
                if(((iDate.getTime()/1000)>(current_date.getTime()/1000))&& r !=d)
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
                                title: 'Capacity: '+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(edif)+'<br>Time Spent: '+hoursminutes(sdef)
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
            if ("series" == e) {
                $("#main_" + g).remove();
                var q = parseInt($(".week_master_" + a).length);
                var jsonObj = [];
                for (var i = 0; i < q; i++) {
                    var r = $(".week_master_" + a).parent("div").attr("id"); 
                    r = r.replace("week_", "");
                    var s = $(".week_master_" + a).attr("id");
                    var day_id = r ;
                    var c1 = parseInt($("#capacity_"+r).attr('data-time'));
                    var e1 = $("#est_"+r).attr('data-time');
                    var s1 = $("#spent_"+r).attr('data-time');
                    var edif = parseInt(e1) - parseInt(k),
                        sdef = parseInt(s1) - parseInt(l);
                    $("#" + s).remove();
                    var data = {
                        id: r,
                        capacity: c1,
                        estimate_time: edif,
                        spent_time: sdef,
                        title: 'Capacity: '+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(edif)+'<br>Time Spent: '+hoursminutes(sdef)
                    }
                    jsonObj.push(data);
                   
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
            $("#delete_task").modal("hide")
        }
    })
}





function right_click_delete(a, b, c, d) {
    var e = "Are you sure, you want to delete this task?";
//    alertify.confirm(e, function(e) {
//        if (1 == e) {
            var f = $("#task_data_" + a).val();
            $.ajax({
                type: "post",
                url: SIDE_URL + "calendar/delete_task",
                data: {
                    task_id: a,
                    task_data: f,
                    due_date: b,
                    redirect: c,
                    start_date: $("#week_start_date").val(),
                    end_date: $("#week_end_date").val(),
                    action: $("#week_action").val(),
                    active_menu: c,
                    current_date: $("#current_date").val(),
                    form: "delete"
                },
                success: function(data) {
                    var data = jQuery.parseJSON(data);
                    var c1 = parseInt($("#capacity_"+d).attr('data-time'));
                    var e1 = parseInt($("#est_"+d).attr('data-time'));
                    var s1 = parseInt($("#spent_"+d).attr('data-time'));
                    var f = $("#task_time_" + a).html();
                    if (f) var g = f.split("/"),
                        h = get_minutes(g[0]),
                        i = get_minutes(g[1]);
                    var edif = parseInt(e1) - parseInt(h),
                            sdef = parseInt(s1) - parseInt(i);
                    $('#progress_'+d).empty(), $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/update_progress_bar",
                    data: {
                        id: d,
                        capacity: c1,
                        estimate_time: edif,
                        spent_time: sdef,
                        title: 'Capacity:'+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(edif)+'<br>Time Spent: '+hoursminutes(sdef)
                    },
                    success: function(progress) {
                        $('#progress_'+d).html(progress)
                    }
                });
                     $("#main_" + a).remove();
                     toastr['success']("Task '"+data.task_title+"' has been deleted.", "");
                }
            })
//        }
//    })
}





function RightClickChangeSwimlane(a, b, c) {
    var d = a;
    d = d.replace("task_", "");
    var e = $("#task_data_" + d).val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/change_swimlane",
        data: {
            task_id: a,
            swimlane_id: b,
            task_data: e,
            redirect: c,
            start_date: $("#week_start_date").val(),
            end_date: $("#week_end_date").val(),
            action: $("#week_action").val()
        },
        async: !1,
        success: function(a) {
            "done" == a || $("#main_" + d).replaceWith(a)
        }
    })
}

function chek_step(a, b) {
    var comsteps = $("#stepcom_" + b).html();
    var steps = parseInt(comsteps);
    var task_data = $("#task_data_" + b).val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "kanban/set_step_complete",
        data: {
            step_id: a,
            task_id: b,
            post_data: task_data 
        },
        success: function(c) {
            if(c==1)
            {
                steps--;
                $("#step_class_" + a).removeClass("step-complete-class");
                $("#stepcom_" + b).html(steps);
            }
            else if(c==0)
            {
                steps++;
                $("#step_class_" + a).addClass("step-complete-class");
                $("#stepcom_" + b).html(steps);
            }
			else
            {
                  $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/set_weekly_update_task",
                    data: {
                        task_id: c,
                        start_date: $("#week_start_date").val(),
                        end_date: $("#week_end_date").val(),
                        action: $("#week_action").val(),
                        active_menu: ACTIVE_MENU,
                        color_menu: $("#task_color_menu").val()
                    },
                    success: function(re) {
                        $("#main_" + b).replaceWith(re)
                    }
                })
            }
        }
    })
}



function task_ex_pos(a) {
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/save_task_pos",
        data: {
            data: a,
            redirect: ACTIVE_MENU,
            start_date: $("#week_start_date").val(),
            end_date: $("#week_end_date").val(),
            action: $("#week_action").val(),
            active_menu: ACTIVE_MENU,
            color_menu:$("#task_color_menu").val()
        },
        success: function(b) {
            "done" == b || $("#main_" + a.task_id).replaceWith(b)
        }
    })
}



function expand_div(a) {
    var b = document.getElementById("expand_div_" + a);
    "none" !== b.style.display ? (b.style.display = "none", $("#expand_div_symbol_" + a).html('<i class="icon-cstexpand"> </i>')) : (b.style.display = "block", $("#expand_div_symbol_" + a).html('<i class="icon-cstcompress"> </i>'))
}

function update_status_complete(a, b) {
    if ("1" == ACTUAL_TIME_ON && b == COMPLETED_ID && getCookie('timer_task_id') != a.task_id) {
        var c = $("#task_time_" + a.task_id).html();
        if (c) var d = c.split("/"),
            e = get_minutes(d[1]);
        else var e = "0";
        if ("0" == e) return $("#task_actual_time_task_id").val(a.task_id), $("#task_actual_time_task_data").val($("#task_data_" + a.task_id).val()), $("#task_actual_time").val(""), $("#task_actual_time_hour").val(""), $("#task_actual_time_min").val(""), $("#actual_time_task").modal("show"),$("#actual_time_task").on("shown.bs.modal", function() {
        $("#task_actual_time").focus()
    }), !1
    }
    var st = b;
    if(b == COMPLETED_ID && getCookie('timer_task_id') == a.task_id)
    {
        end_task_timer();
    }
    $("body").addClass("custom"), $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/update_status",
        data: {
            data: a,
            status: b,
            start_date: $("#week_start_date").val(),
            end_date: $("#week_end_date").val(),
            action: $("#week_action").val(),
            active_menu: ACTIVE_MENU,
            color_menu:$("#task_color_menu").val()
        },
        success: function(b) {
            App.init(), $("#main_" + a.task_id).replaceWith(b), $("body").removeClass("custom"), "0" != a.prerequisite_task_id && $.ajax({
                type: "post",
                url: SIDEURL + "kanban/check_completed_dependency",
                data: {
                    task_id: a.prerequisite_task_id
                },
                success: function(b) {
                    b && (b = jQuery.parseJSON(b), $.ajax({
                        type: "post",
                        url: SIDEURL + "calendar/set_weekly_update_task",
                        data: {
                            task_id: a.prerequisite_task_id,
                            start_date: $("#week_start_date").val(),
                            end_date: $("#week_end_date").val(),
                            action: $("#week_action").val(),
                            active_menu: ACTIVE_MENU,
                            color_menu:$("#task_color_menu").val()
                        },
                        success: function(c) {
                            b.main_task_status_id == b.task_status_id ? $("#main_" + a.prerequisite_task_id).length && ($("#main_" + a.prerequisite_task_id).replaceWith(c), "red" == b.completed_depencencies ? $("#up_status_" + a.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + a.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + a.prerequisite_task_id).replaceWith(c), "red" == b.completed_depencencies ? $("#up_status_" + a.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + a.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                        }
                    }))
                }
            })
        }
    })
}



function save_last_calender_view(a) {
    a && $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/save_calender_view",
        data: {
            val: a
        },
        success: function(a) {}
    })
}

function save_task_for_timer(a, b, c, d, e, f) {
    if ($(a).hasClass("before_timer")) return !1;
    var g = $("#timer_task_id").val();
    if (g) {
        var h = $("#or_color_" + g).val();
        $("#task_" + g).css("border", "1px solid " + h)
    }
    if ("1" != e) {
        var i = $("#task_data_" + b).val();
        $("#dvLoading").fadeIn("slow"), $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/save_task",
            data: {
                post_data: i,
                scope_id: b
            },
            success: function(a) {
                var c = a;
                $("#timer_task_id").val(c), $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/set_weekly_update_task",
                    data: {
                        task_id: a,
                        start_date: $("#week_start_date").val(),
                        end_date: $("#week_end_date").val(),
                        action: $("#week_action").val(),
                        active_menu: ACTIVE_MENU,
                        color_menu:$("#task_color_menu").val()
                    },
                    success: function(a) {
                        $("#main_" + b).replaceWith(a);
                        var d = $("#or_color_" + c).val();
                        $("#task_" + c).css("border", "1px dashed " + d)
                    }
                }), $("#dvLoading").fadeOut("slow")
            }
        });
        var d = 0
    } else {
        $("#timer_task_id").val(b);
        var j = $("#or_color_" + b).val();
        $("#task_" + b).css("border", "1px dashed " + j)
    }
    $("#task_com_status").val(f), $(".full_task div").addClass("before_timer"), $(".comm-box > a").removeClass("after_timer_on"), setTimeout(function() {
        chk_task_selected(c, d)
    }, 2e3)
}

function comments_html(a) {
    a && ($("#comment_list_task_id").val(a), $.ajax({
        url: SIDE_URL + "kanban/commets_html",
        data: {
            task_id: a
        },
        cache: !1,
        dataType: "json",
        success: function(a) {
            var b = "";
            $.map(a.task.comments, function(a) {
                b += '<li class="light"><div class="userimg">', b += a.file_exist && "" != a.profile_image ? '<img src="' + S3_DISPLAY_URL + "upload/user/" + a.profile_image + '" alt="img" class="img-circle" />' : '<img src="' + S3_DISPLAY_URL + 'upload/user/no_image.jpg" alt="img" class="img-circle" />', b += '</div><div class="userdetail" style="width: 90%;">', b += '<div class="usertxt">' + a.first_name + " " + a.last_name, b += "</div>", b += '<p class="usertxt2"> A ' + a.time_ago + "</p>", b += '<p id="orig_comment_' + a.task_comment_id + '" class="wrap">' + a.task_comment + '</p></div><div class="clearfix"> </div></li>'
            }), $("#comments_html").html(b), $("#comments_add").modal("show"), $("#comments_add").on("shown.bs.modal", function() {
                $(this).find("#task_comment_list").focus()
            })
        }
    }))
}

function dependency_html(a) {
    a && $.ajax({
        url: SIDE_URL + "kanban/dependency_html",
        data: {
            task_id: a
        },
        cache: !1,
        dataType: "json",
        success: function(a) {
            var b = "";
            $.map(a.task.dependencies, function(a) {
                b += "<tr>", b += "<td>" + a.task_title + "</td>", b += "<td>" + a.first_name + " " + a.last_name + "</td>", b += '<td><span class="label label-sm label-' + a.task_status_name.replace(/\s/g, "") + '">' + a.task_status_name + "</span></td>", b += "</tr>"
            }), $("#dependency_html").html(b), $("#dependency").modal("show")
        }
    })
}

function recurring_html(a) {
    a && $.ajax({
        type: "post",
        url: SIDE_URL + "kanban/recurring_html",
        data: {
            task_id: a
        },
        success: function(a) {
            $("#recurring").html(a), $("#recurring").modal("show")
        }
    })
}
$(function() {
    $(document).on('hidden.bs.modal',"#comments_right", function(){
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove();
        });
    var a = $(window).height(),
        b = parseInt(200) * parseInt(100) / parseInt(a),
        c = parseInt(a) - parseInt("240"); 
    $(".minhightweek").css("height", b), parseInt(c) - parseInt("100"), $(".scroll_cal_week").slimScroll({
        color: "#17A3E9",
        height: c,
        wheelStep: 20,
        showOnHover: !0
    }), context.init({
        preventDoubleContext: !1
    }), context.settings({
        compress: !0
    }), $(".scroll_cmt").slimScroll({
        color: "#17A3E9",
        height: "250px",
        wheelStep: 100
    }), "NextFiveDay" == ACTIVE_MENU ? $("#current_page").val("NextFiveDayWeekView") : $("#current_page").val("weekview"), $(".input-append.date").datepicker({
        startDate: START_DATE_PICKER,
        format: DATE_ARR
    }), $('input[name="show_cal_view[]"]').click(function() {
        $("#show_capacity").is(":checked") ? $(".task-list").css("display", "block") : $(".task-list").css("display", "none"), $("#show_summary").is(":checked") ? $(".task-info").css("display", "block") : $(".task-info").css("display", "none"), $("#show_task").is(":checked") ? ($(".task-lable").css("display", "block"), $(".scroll_calender").slimScroll({
            color: "#17A3E9",
            height: "120px",
            wheelStep: 100
        })) : ($(".task-lable").css("display", "none"), $(".scroll_calender").slimScroll({
            destroy: !0
        }));
        var a = [];
        $('input[name="show_cal_view[]"]:checkbox:checked').each(function(b) {
            a[b] = $(this).val()
        }), $("#dvLoading").fadeIn("slow"), "" != a ? $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/saveShowTask",
            data: $("#last_remember_calender").serialize(),
            success: function(a) {
                $("#dvLoading").fadeOut("slow")
            }
        }) : $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/saveShowTask",
            data: $("#last_remember_calender").serialize(),
            success: function(a) {
                $("#dvLoading").fadeOut("slow")
            }
        })
    }), $("#calender_team_user_id").change(function() {
        var a = ($(this).val(), $("#last_remember").serialize());
        $('#common-teambox').hide();
        $("#dvLoading").fadeIn("slow"), $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/searchWeekTask",
            data: {
                str: a,
                start_date: $("#week_start_date").val(),
                end_date: $("#week_end_date").val(),
                action: $("#week_action").val(),
                active_menu: ACTIVE_MENU,
                color_menu:$("#task_color_menu").val()
            },
            success: function(a) {
                $("#sjcalendar").html(a), $("#calender_team_user_id").val() != LOG_USER_ID ? ($("#calender_team_user_id").parents("li").children("a").addClass("filter_selected"), $("#calender_team_user_id").parents("li").children("a").children("i").addClass("filtericon-red"), $("#calender_team_user_id").parents("li").children("a").children("i").removeClass("filtericon")) : ($("#calender_team_user_id").parents("li").children("a").removeClass("filter_selected"), $("#calender_team_user_id").parents("li").children("a").children("i").removeClass("filtericon-red"), $("#calender_team_user_id").parents("li").children("a").children("i").addClass("filtericon"));
                var b = $(window).height(),
                    c = parseInt(200) * parseInt(100) / parseInt(b),
                    d = parseInt(b) - parseInt("240");
                $(".minhightweek").css("height", c), parseInt(d) - parseInt("100"), $(".scroll_cal_week").slimScroll({
                    color: "#17A3E9",
                    height: d,
                    wheelStep: 20,
                    showOnHover: !0
                }), $("#dvLoading").fadeOut("slow")
            }
        })
    }), $(".right_cmt_close").click(function() {
        $("#comments_right").modal("hide");
        $("#comments_right").on('hidden.bs.modal', function(){
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove();
        })
    }), $("#right_cmt").validate({
        rules: {
            right_task_comment: {
                required: !0
            }
        },
        submitHandler: function() {
            $("#right_cmt_btn").attr("disabled", "disabled");
            var a = $("#right_comment_task_id").val();
            $.ajax({
                type: "post",
                url: SIDE_URL + "calendar/add_comment",
                data: $("#right_cmt").serialize()+ "&color_menu=" + $("#task_color_menu").val(),
                success: function(b) {
                    $("#main_" + a).replaceWith(b), $("#right_cmt_btn").removeAttr("disabled", "disabled"), $("#comments_right").modal("hide"),
                            $("#comments_right").on('hidden.bs.modal', function(){
                            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
                        })
                }
            })
        }
    }), $("#frm_add_comment_from_list").validate({
        rules: {
            task_comment_list: {
                required: !0
            }
        },
        submitHandler: function() {
            $("#cmts_list_submit").attr("disabled", "disabled"), $("#task_comment_list_loading").show(), $("#comment_list_task_id").val(), $.ajax({
                type: "post",
                url: SIDE_URL + "kanban/add_comment_from_list",
                data: $("#frm_add_comment_from_list").serialize(),
                success: function(a) {
                    a = jQuery.parseJSON(a);
                    var b = "";
                    $.map(a.task.comments, function(a) {
                        b += '<li class="light"><div class="userimg">', b += a.file_exist && "" != a.profile_image ? '<img src="' + S3_DISPLAY_URL + "upload/user/" + a.profile_image + '" alt="img" class="img-circle" />' : '<img src="' + S3_DISPLAY_URL + 'upload/user/no_image.jpg" alt="img" class="img-circle" />', b += '</div><div class="userdetail" style="width: 90%;">', b += '<div class="usertxt">' + a.first_name + " " + a.last_name, b += "</div>", b += '<p class="usertxt2"> A ' + a.time_ago + "</p>", b += '<p id="orig_comment_' + a.task_comment_id + '" class="wrap">' + a.task_comment + '</p></div><div class="clearfix"> </div></li>'
                    }), $("#comments_html").html(b), $("#task_comment_list").val(""), $("#task_comment_list_loading").hide(), $("#cmts_list_submit").removeAttr("disabled", "disabled"), $("#comments_add").modal("hide"), alertify.set("notifier", "position", "top-right"), alertify.log("Comment added successfully")
                }
            })
        }
    }), $("#task_actual_time").on("keydown", function(a) {
        if (13 === a.keyCode) {
            a.preventDefault();
            var b = $("#task_actual_time").blur();
            b[0].value && $("#frm_actual_time").submit()
        }
        else if (9 === a.keyCode) {
            
            //a.preventDefault();
            var b = $("#task_actual_time").blur();
            setTimeout(function() { b[0].value && $("#frm_actual_time").find('.txtbold').first().focus(); }, 10); 
        }
    }), $("#task_actual_time").blur(function() {
        var a = $(this).val(),
            b = a.split(":");
        if (a)
            if (parseInt(a) > 0)
                if (1 == validate(a)) {
                    if (2 == b.length) {
                        var c = b[0],
                            d = b[1];
                        if (d >= 60) {
                            var e = parseInt(d / 60),
                                f = d % 60,
                                g = +c + +e,
                                h = f;
                            if (0 == g) var i = h + "m";
                            else var i = g + "h " + h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(g), $("#task_actual_time_min").val(h)
                        } else {
                            var g = c,
                                h = d;
                            if (0 == g) var i = h + "m";
                            else var i = g + "h " + h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(g), $("#task_actual_time_min").val(h)
                        }
                    }
                    if (a.length >= 1 && a.length <= 2)
                        if (a >= 60) {
                            var g = parseInt(a / 60),
                                h = a % 60;
                            if (0 == g) var i = h + "m";
                            else var i = g + "h " + h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(g), $("#task_actual_time_min").val(h)
                        } else {
                            var h = a,
                                i = h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(0), $("#task_actual_time_min").val(h)
                        }
                    if (3 == a.length && 2 != b.length) {
                        var j = new Array,
                            j = ("" + a).split("");
                        if (j[a.length - (a.length - 1)] + j[a.length - (a.length - 2)] >= 60) {
                            var k = 1,
                                h = j[a.length - (a.length - 1)] + j[a.length - (a.length - 2)] - 60,
                                g = +j[a.length - a.length] + +k;
                            if (0 == g) var i = h + "m";
                            else var i = g + "h " + h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(g), $("#task_actual_time_min").val(h)
                        } else {
                            var h = j[a.length - (a.length - 1)] + j[a.length - (a.length - 2)],
                                g = j[a.length - a.length];
                            if (0 == g) var i = h + "m";
                            else var i = g + "h " + h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(g), $("#task_actual_time_min").val(h)
                        }
                    }
                    if (4 == a.length && 2 != b.length) {
                        var j = new Array,
                            j = ("" + a).split("");
                        if (j[a.length - (a.length - 2)] + j[a.length - (a.length - 3)] >= 60) {
                            var k = 1,
                                h = j[a.length - (a.length - 2)] + j[a.length - (a.length - 3)] - 60,
                                g = +(j[a.length - a.length] + j[a.length - (a.length - 1)]) + +k;
                            if (0 == g) var i = h + "m";
                            else var i = g + "h " + h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(g), $("#task_actual_time_min").val(h)
                        } else {
                            var h = j[a.length - (a.length - 2)] + j[a.length - (a.length - 3)],
                                g = +(j[a.length - a.length] + j[a.length - (a.length - 1)]);
                            if (0 == g) var i = h + "m";
                            else var i = g + "h " + h + "m";
                            $("#task_actual_time").val(i), $("#task_actual_time_hour").val(g), $("#task_actual_time_min").val(h)
                        }
                    }
                    a.length >= 5 && 2 != b.length && ($("input[name='task_time_spent']").val(""), is_edited = "1", alertify.alert("maximum 4 digits allowed"))
                } else $("#task_actual_time").val(""), alertify.alert("your inserted value is not correct, please insert correct value");
        else $("#task_actual_time").val(""), alertify.alert("Please enter greater than 0 time.")
    }), $("#frm_actual_time").validate({
        rules: {
            task_actual_time: {
                required: true
            }
        },
        errorPlacement: function(a, b) {
            a.insertAfter(b.parent("div"))
        },
        submitHandler: function() {
            var a = $("#task_actual_time_task_id").val();
            var g = $("#task_data_" + a).val();
            $.ajax({
                type: "post",
                url: SIDE_URL + "calendar/add_actual_time",
                data: $("#frm_actual_time").serialize()+ "&color_menu=" + $("#task_color_menu").val(),
                success: function(b) {
                    var c = $("#main_" + a).parent("div").attr("id");
                    c = c.replace("week_", "");
                    var e = parseInt(60 * parseInt($("#task_actual_time_hour").val())) + parseInt($("#task_actual_time_min").val());
                    var c1 = $("#capacity_"+c).attr('data-time');
                    var e1 = $("#est_"+c).attr('data-time');
                    var s1 = $("#spent_"+c).attr('data-time');
                    var ss1 = parseInt(s1) + parseInt(e);
                    var ee1 = parseInt(e1);
                    var cc1 = parseInt(c1);
                    $.ajax({
                            type: "post",
                            url: SIDE_URL + "calendar/update_progress_bar",
                            data: {
                                    id: c,
                                    capacity: cc1,
                                    estimate_time: ee1,
                                    spent_time: ss1,
                                    title: 'Capacity: '+hoursminutes(cc1)+'<br>Time Estimate: '+hoursminutes(ee1)+'<br>Time Spent: '+hoursminutes(ss1)
                            },
                            success: function(progress) {
                                    $('#progress_'+c).html(progress)
                            }
                    });
                    //$("#spent_" + c).html(f),
                            $("#main_" + a).replaceWith(b);
//                    var g = $("#task_data_" + a).val();
                    g = jQuery.parseJSON(g), "0" != b.prerequisite_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: g.prerequisite_task_id
                        },
                        success: function(a) {
                            a && (a = jQuery.parseJSON(a), $.ajax({
                                type: "post",
                                url: SIDEURL + "calendar/set_weekly_update_task",
                                data: {
                                    task_id: g.prerequisite_task_id,
                                    start_date: $("#week_start_date").val(),
                                    end_date: $("#week_end_date").val(),
                                    action: $("#week_action").val(),
                                    active_menu: ACTIVE_MENU,
                                    color_menu:$("#task_color_menu").val()
                                },
                                success: function(b) {
                                    a.main_task_status_id == a.task_status_id ? $("#main_" + g.prerequisite_task_id).length && ($("#main_" + g.prerequisite_task_id).replaceWith(b), "red" == a.completed_depencencies ? $("#up_status_" + g.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + g.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + g.prerequisite_task_id).replaceWith(b), "red" == a.completed_depencencies ? $("#up_status_" + g.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + g.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                }
                            }))
                        }
                    }), $("#actual_time_task").modal("hide")
                }
            })
        }
    }), $(".close_actual_time_task").click(function() {
        var a = $("#task_actual_time_task_id").val();
        $("#task_" + a).find("input[type='checkbox']").prop("checked", !1), $("#task_" + a).find("span").removeClass("checked"), $("#actual_time_task").modal("hide")
    }), $(".close_cmt").click(function() {
        $("#comments_add").modal("hide")
    })
});
