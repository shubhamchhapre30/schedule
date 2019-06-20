function collapse(t) {
    $("#tr_" + t).toggle()
}

function expand_div(t) {
    var a = document.getElementById("expand_div_" + t);
    "none" !== a.style.display ? (a.style.display = "none", $("#expand_div_symbol_" + t).html('<i class="icon-cstexpand"> </i>')) : (a.style.display = "block", $("#expand_div_symbol_" + t).html('<i class="icon-cstcompress"> </i>'))
}

function chek_step(a,b) {
    var comsteps = $("#stepcom_" + b).html();
    var steps = parseInt(comsteps);
    var task_data = $("#task_data_" + b).val();
    $.ajax({
        type: "post",
        url: SIDEURL + "kanban/set_step_complete",
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
            else
            {
                steps++;
                $("#step_class_" + a).addClass("step-complete-class");
                $("#stepcom_" + b).html(steps);
            }
        }
    })
}


function update_status_complete(t, a) {
    if ("1" == ACTUAL_TIME_ON && a == COMP_status_id && getCookie('timer_task_id') != t.task_id) {
        var e = $("#task_time_" + t.task_id).html();
        if (e) var s = e.split("/"),
            i = get_minutes(s[1]);
        else var i = "0";
        if ("0" == i) return $("#task_actual_time_task_id").val(t.task_id), $("#task_actual_time").val(""), $("#task_actual_time_hour").val(""), $("#task_actual_time_min").val(""), $("#actual_time_task").modal("show"),$("#actual_time_task").on("shown.bs.modal", function() {
        $("#task_actual_time").focus()
    }), !1
    }
    var _ = $("#status_time_" + t.task_status_id).html();
    if (_) var r = get_minutes($("#status_time_" + t.task_status_id + " .hrlft").html()),
        n = get_minutes($("#status_time_" + t.task_status_id + " .hrrlt").html());
    else var r = "0",
        n = "0";
    var d = $("#status_time_" + a).html();
    if (d) var l = get_minutes($("#status_time_" + a + " .hrlft").html()),
        o = get_minutes($("#status_time_" + a + " .hrrlt").html());
    else var l = "0",
        o = "0";
    var e = $("#task_time_" + t.task_id).html();
    if (e) var s = e.split("/"),
        u = get_minutes(s[0]),
        i = get_minutes(s[1]);
    else var u = "0",
        i = "0";
    if(a == COMPLETED_ID && getCookie('timer_task_id') == t.task_id)
    {
        end_task_timer();
    }
    $.ajax({
        type: "post",
        url: SIDEURL + "kanban/update_status",
        data: {
            data: t,
            status: a
        },
        success: function(e) {
            var e = jQuery.parseJSON(e),
                s = $("#task_count_hide_" + t.task_status_id + "_" + t.swimlane_id).html();
            $("#task_count_hide_" + t.task_status_id + "_" + t.swimlane_id).html(parseInt(s) - 1);
            var _ = $("#task_count_hide_" + a + "_" + t.swimlane_id).html();
            $("#task_count_hide_" + a + "_" + t.swimlane_id).html(parseInt(_) + 1);
            var d = hoursminutes(parseInt(r) - parseInt(u)),
                m = hoursminutes(parseInt(n) - parseInt(i)),
                c = "<span id='Estimate_time_" + t.task_status_id + "' class='hrlft tooltips' data-original-title='Estimate Time'>" + d + "</span><span id='spent_time_" + t.task_status_id + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + m + "</span>";
            $("#status_time_" + t.task_status_id).html(c);
            var p = hoursminutes(parseInt(l) + parseInt(u)),
                k = hoursminutes(parseInt(o) + parseInt(i)),
                h = "<span id='Estimate_time_" + a + "'  class='hrlft tooltips' data-original-title='Estimate Time'>" + p + "</span><span id='spent_time_" + a + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + k + "</span>";
            $("#status_time_" + a).html(h);
            var v = $("#completed_loadMore_limit" + a + t.swimlane_id).val(),
                f = parseInt(v) + parseInt("1");
            $("#completed_loadMore_limit" + a + t.swimlane_id).val(f), $("#main_" + t.task_id).remove(), $.ajax({
                type: "post",
                url: SIDEURL + "kanban/set_update_task",
                data: {
                    task_id: e.id,
                    color_menu : $("#kanban_color_menu").val()
                },
                success: function(s) {
                    var i = s,
                        _ = COMP_status_id,
                        r = READY_status_id;
                    App.init(), _ == a ? ("0" != t.master_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/next_noncompleted_recurrence",
                        data: {
                            task_id: t.master_task_id
                        },
                        success: function(t) {
                            t && (t = jQuery.parseJSON(t), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(a) {
                                    $("#task_status_" + t.task_status_id + "_" + t.swimlane_id).prepend(a)
                                }
                            }))
                        }
                    }), "0" != t.prerequisite_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: t.prerequisite_task_id
                        },
                        success: function(a) {
                            a && (a = jQuery.parseJSON(a), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.prerequisite_task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(s) {
                                    a.main_task_status_id == a.task_status_id ? $("#main_" + t.prerequisite_task_id).length && ($("#main_" + t.prerequisite_task_id).replaceWith(s), "red" == a.completed_depencencies ? $("#up_status_" + e.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + e.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#task_status_" + a.task_status_id + "_" + t.swimlane_id).prepend(s), $("#main_" + t.prerequisite_task_id).remove(), "red" == a.completed_depencencies ? $("#up_status_" + e.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + e.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                }
                            }))
                        }
                    }), $("#task_status_" + _ + "_" + t.swimlane_id).prepend(i)) : ("0" != t.master_task_id && $(".kanban_master_" + t.master_task_id).remove(), "0" != t.prerequisite_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: t.prerequisite_task_id
                        },
                        success: function(a) {
                            a && (a = jQuery.parseJSON(a), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.prerequisite_task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(e) {
                                    a.main_task_status_id == a.task_status_id ? $("#main_" + t.prerequisite_task_id).length && ($("#main_" + t.prerequisite_task_id).replaceWith(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + t.prerequisite_task_id).remove(), $("#task_status_" + a.task_status_id + "_" + t.swimlane_id).prepend(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                }
                            }))
                        }
                    }), $("#task_status_" + r + "_" + t.swimlane_id).prepend(i))
                }
            })
        }
    })
}

//function openpopup(t) {
//    $("#right_task_comment").val(""), $("#comments_right").modal("show"), $("#comments_right").on("shown.bs.modal", function() {
//        $(this).find("#right_task_comment").focus()
//    }), $("#right_comment_task_id").val(t)
//}

function loop(t) {
    $.each(t, function(t, a) {
        if ($.isPlainObject(a) || $.isArray(a)) {
            var e = !!$.isPlainObject(a);
            console.log(t + (e ? "{" : "[")), loop(a), console.log(e ? "{" : "]")
        } else console.log(t + "->" + a)
    })
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
                $("#timer_task_id").val(e), $.ajax({
                    type: "post",
                    url: SIDEURL + "kanban/set_update_task",
                    data: {
                        task_id: t,
                        color_menu: $("#kanban_color_menu").val()
                    },
                    success: function(t) {
                        App.init(), $("#main_" + a).replaceWith(t);
                        var s = $("#or_color_" + e).val();
                        $("#task_" + e).css("border", "1px dashed " + s)
                    }
                }), $("#dvLoading").fadeOut("slow")
            }
        });
        var s = 0
    } else {
        $("#timer_task_id").val(a);
        var l = $("#or_color_" + a).val();
        $("#task_" + a).css("border", "1px dashed " + l)
    }
    $("#task_com_status").val(_), $(".full_task div").addClass("before_timer"), $(".comm-box > a").removeClass("after_timer_on"), setTimeout(function() {
        chk_task_selected(e, s)
    }, 2e3)
}

function task_ex_pos(t) {
    $.ajax({
        type: "post",
        url: SIDEURL + "kanban/save_task_pos",
        data: {
            data: t
        },
        success: function(a) {
            "done" == a || $("#main_" + t.task_id).replaceWith(a)
        }
    })
}


function right_click_delete(t, a, e) {
    var s = "Are you sure, you want to delete this task?";
//    alertify.confirm(s, function(s) {
//        1 == s && 
                $.ajax({
            type: "post",
            url: SIDE_URL + "kanban/delete_task",
            data: {
                task_id: t
            },
            success: function(s) {
                var i = $("#status_time_" + a).html();
                if (i) var _ = get_minutes($("#status_time_" + a + " .hrlft").html()),
                    r = get_minutes($("#status_time_" + a + " .hrrlt").html());
                else var _ = "0",
                    r = "0";
                var n = $("#task_time_" + t).html();
                if (n) var d = n.split("/"),
                    l = get_minutes(d[0]),
                    o = get_minutes(d[1]);
                else var l = "0",
                    o = "0";
                var u = hoursminutes(parseInt(_) - parseInt(l)),
                    m = hoursminutes(parseInt(r) - parseInt(o)),
                    c = "<span id='Estimate_time_" + a + "' class='hrlft tooltips' data-original-title='Estimate Time'>" + u + "</span><span class='hrrlt tooltips' data-original-title='Spent Time'>" + m + "</span>",
                    p = $("#task_count_hide_" + a + "_" + e).html();
                $("#task_count_hide_" + a + "_" + e).html(parseInt(p) - 1), $("#status_time_" + a).html(c), $("#main_" + t).remove();
            }
        })
//    })
}

function move_right(t, a, e, s, i) {
    if ("red" == t) return alertify.alert("You can not move the task as its dependent tasks are not completed yet."), !1;
    if ("1" == ACTUAL_TIME_ON && i == e) {
        var _ = $("#task_time_" + a).html();
        if (_) var r = _.split("/"),
            n = get_minutes(r[1]);
        else var n = "0";
        if ("0" == n) return $("#task_actual_time_task_id").val(a), $("#task_actual_time").val(""), $("#task_actual_time_hour").val(""), $("#task_actual_time_min").val(""), $("#actual_time_task").modal("show"), !1
    }
    var d = $("#status_time_" + e).html();
    if (d) var l = get_minutes($("#status_time_" + e + " .hrlft").html()),
        o = get_minutes($("#status_time_" + e + " .hrrlt").html());
    else var l = "0",
        o = "0";
    var _ = $("#task_time_" + a).html();
    if (_) var r = _.split("/"),
        u = get_minutes(r[0]),
        n = get_minutes(r[1]);
    else var u = "0",
        n = "0";
    $.ajax({
        type: "post",
        url: SIDE_URL + "kanban/moveRight",
        data: {
            task_id: a,
            task_status_id: e
        },
        success: function(t) {
            var t = jQuery.parseJSON(t),
                i = $("#task_count_hide_" + e + "_" + s).html();
            $("#task_count_hide_" + e + "_" + s).html(parseInt(i) - 1);
            var _ = $("#task_count_hide_" + t.status_id + "_" + s).html();
            $("#task_count_hide_" + t.status_id + "_" + s).html(parseInt(_) + 1);
            var r = $("#status_time_" + t.status_id).html();
            if (r) var d = get_minutes($("#status_time_" + t.status_id + " .hrlft").html()),
                m = get_minutes($("#status_time_" + t.status_id + " .hrrlt").html());
            else var d = "0",
                m = "0";
            var c = hoursminutes(parseInt(l) - parseInt(u)),
                p = hoursminutes(parseInt(o) - parseInt(n)),
                k = "<span id='Estimate_time_" + e + "' class='hrlft tooltips' data-original-title='Estimate Time'>" + c + "</span><span id='spent_time_" + e + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + p + "</span>";
            $("#status_time_" + e).html(k);
            var h = hoursminutes(parseInt(d) + parseInt(u)),
                v = hoursminutes(parseInt(m) + parseInt(n)),
                f = "<span id='Estimate_time_" + t.status_id + "'  class='hrlft tooltips' data-original-title='Estimate Time'>" + h + "</span><span id='spent_time_" + t.status_id + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + v + "</span>",
                b = $("#completed_loadMore_limit" + t.status_id + s).val(),
                g = parseInt(b) + parseInt("1");
            $("#completed_loadMore_limit" + t.status_id + s).val(g), $("#status_time_" + t.status_id).html(f), $("#main_" + a).remove(), $.ajax({
                type: "post",
                url: SIDE_URL + "kanban/set_update_task",
                data: {
                    task_id: t.task_id,
                    color_menu: $("#kanban_color_menu").val()
                },
                success: function(a) {
                    var e = a;
                    App.init(), t.status_id == COMP_status_id ? ("0" != t.master_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/next_noncompleted_recurrence",
                        data: {
                            task_id: t.master_task_id
                        },
                        success: function(t) {
                            t && (t = jQuery.parseJSON(t), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(a) {
                                    $("#task_status_" + t.task_status_id + "_" + t.swimlane_id).prepend(a)
                                }
                            }))
                        }
                    }), "0" != t.prerequisite_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: t.prerequisite_task_id
                        },
                        success: function(a) {
                            a && (a = jQuery.parseJSON(a), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.prerequisite_task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(e) {
                                    a.main_task_status_id == a.task_status_id ? $("#main_" + t.prerequisite_task_id).length && ($("#main_" + t.prerequisite_task_id).replaceWith(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#task_status_" + a.task_status_id + "_" + t.swimlane_id).prepend(e), $("#main_" + t.prerequisite_task_id).remove(), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                }
                            }))
                        }
                    }), $("#task_status_" + t.status_id + "_" + t.swimlane_id).prepend(e)) : ("0" != t.master_task_id && $(".kanban_master_" + t.master_task_id).remove(), "0" != t.prerequisite_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: t.prerequisite_task_id
                        },
                        success: function(a) {
                            a && (a = jQuery.parseJSON(a), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.prerequisite_task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(e) {
                                    a.main_task_status_id == a.task_status_id ? $("#main_" + t.prerequisite_task_id).length && ($("#main_" + t.prerequisite_task_id).replaceWith(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + t.prerequisite_task_id).remove(), $("#task_status_" + a.task_status_id + "_" + t.swimlane_id).prepend(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                }
                            }))
                        }
                    }), $("#task_status_" + t.status_id + "_" + t.swimlane_id).prepend(e))
                }
            })
        }
    })
}

function comments_html(t) {
    t && ($("#comment_list_task_id").val(t), $.ajax({
        url: SIDEURL + "kanban/commets_html",
        data: {
            task_id: t
        },
        cache: !1,
        dataType: "json",
        success: function(t) {
            var a = "";
            $.map(t.task.comments, function(t) {
                a += '<li class="light"><div class="userimg">', a += t.file_exist && "" != t.profile_image ? '<img src="' + S3_DISPLAY_URL + "upload/user/" + t.profile_image + '" alt="img" class="img-circle" />' : '<img src="' + S3_DISPLAY_URL + 'upload/user/no_image.jpg" alt="img" class="img-circle" />', a += '</div><div class="userdetail" style="width: 90%;">', a += '<div class="usertxt">' + t.first_name + " " + t.last_name, a += "</div>", a += '<p class="usertxt2"> A ' + t.time_ago + "</p>", a += '<p id="orig_comment_' + t.task_comment_id + '" class="wrap">' + t.task_comment + '</p></div><div class="clearfix"> </div></li>'
            }), $("#comments_html").html(a), $("#comments_add").modal("show"), $("#comments_add").on("shown.bs.modal", function() {
                $(this).find("#task_comment_list").focus()
            })
        }
    }))
}

function dependency_html(t) {
    t && $.ajax({
        url: SIDEURL + "kanban/dependency_html",
        data: {
            task_id: t
        },
        cache: !1,
        dataType: "json",
        success: function(t) {
            var a = "";
            $.map(t.task.dependencies, function(t) {
                a += "<tr>", a += "<td>" + t.task_title + "</td>", a += "<td>" + t.first_name + " " + t.last_name + "</td>", a += '<td><span class="label label-sm label-' + t.task_status_name.replace(/\s/g, "") + '">' + t.task_status_name + "</span></td>", a += "</tr>"
            }), $("#dependency_html").html(a), $("#dependency").modal("show")
        }
    })
}

function recurring_html(t) {
    t && $.ajax({
        type: "post",
        url: SIDEURL + "kanban/recurring_html",
        data: {
            task_id: t
        },
        success: function(t) {
            $("#recurring").html(t), $("#recurring").modal("show")
        }
    })
}

function scrolled(t, a, e) {
    if (t.offsetHeight + t.scrollTop == t.scrollHeight) {
        var s = "completed_loadMore_" + a + e,
            i = $("#" + s).attr("data-over");
        "1" != i && $("#" + s).show()
    } else {
        var s = "completed_loadMore_" + a + e;
        $("#" + s).hide()
    }
}
var status = "";
$(document).ready(function() {
    $(document).on('hidden.bs.modal',"#comments_right", function(){
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
        });
    $(".close_cmt").click(function() {
        $("#comments_add").modal("hide")
    }), context.init({
        preventDoubleContext: !1
    }), context.settings({
        compress: !0
    }), $("#due_task").change(function() {
        $("#common-duedatebox").hide(),
        $("#dvLoading").fadeIn("slow"), $(this).val(), $.ajax({
            type: "post",
            url: SIDEURL + "kanban/searchDueTask",
            data: $("#last_remember").serialize(),
            success: function(t) {
                $("#due_task").unbind("change"), $("#kanban_view").html(t), "all" != $("#due_task").val() ? ($("#due_task").parents("li").children("a").addClass("filter_selected"), $("#due_task").parents("li").children("a").children("i").addClass("filtericon-red"), $("#due_task").parents("li").children("a").children("i").removeClass("filtericon")) : ($("#due_task").parents("li").children("a").removeClass("filter_selected"), $("#due_task").parents("li").children("a").children("i").removeClass("filtericon-red"), $("#due_task").parents("li").children("a").children("i").addClass("filtericon")), $(".scroll1").slimScroll({
                    color: "#17A3E9",
                    height: "160",
                    wheelStep: 12,
                    showOnHover: !0
                }), $("#dvLoading").fadeOut("slow")
            }
        })
    }), $("#frm_actual_time").validate({
        rules: {
            task_actual_time: {
                required: !0
            }
        },
        errorPlacement: function(t, a) {
            t.insertAfter(a.parent("div"))
        },
        submitHandler: function() {
            var t = $("#task_actual_time_task_id").val();
            $.ajax({
                type: "post",
                url: SIDEURL + "kanban/add_actual_time",
                data: $("#frm_actual_time").serialize(),
                success: function(a) {
                    var e = $("#task_data_" + t).val();
                    e = jQuery.parseJSON(e);
                    var s = $("#task_count_hide_" + e.task_status_id + "_" + e.swimlane_id).html();
                    $("#task_count_hide_" + e.task_status_id + "_" + e.swimlane_id).html(parseInt(s) - 1);
                    var i = $("#task_count_hide_" + COMP_status_id + "_" + e.swimlane_id).html();
                    $("#task_count_hide_" + COMP_status_id + "_" + e.swimlane_id).html(parseInt(i) + 1);
                    var _ = $("#status_time_" + COMP_status_id).html();
                    if (_) var r = get_minutes($("#status_time_" + COMP_status_id + " .hrrlt").html());
                    else var r = "0";
                    var n = parseInt(60 * parseInt($("#task_actual_time_hour").val())) + parseInt($("#task_actual_time_min").val()),
                        d = hoursminutes(parseInt(r) + parseInt(n));
                    $("#status_time_" + COMP_status_id + " .hrrlt").html(d);
                    var l = $("#completed_loadMore_limit" + COMP_status_id + e.swimlane_id).val(),
                        o = parseInt(l) + parseInt("1");
                    $("#completed_loadMore_limit" + COMP_status_id + e.swimlane_id).val(o);
                    var a = jQuery.parseJSON(a);
                    $("#main_" + t).remove(), $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/set_task",
                        data: {
                            task_id: a.task_id,
                            color_menu: $("#kanban_color_menu").val()
                        },
                        success: function(t) {
                            "0" != a.master_task_id && $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/next_noncompleted_recurrence",
                                data: {
                                    task_id: a.master_task_id
                                },
                                success: function(t) {
                                    t && (t = jQuery.parseJSON(t), $.ajax({
                                        type: "post",
                                        url: SIDEURL + "kanban/set_update_task",
                                        data: {
                                            task_id: t.task_id,
                                            color_menu: $("#kanban_color_menu").val()
                                        },
                                        success: function(a) {
                                            $("#task_status_" + t.task_status_id + "_" + t.swimlane_id).prepend(a)
                                        }
                                    }))
                                }
                            }), "0" != a.prerequisite_task_id && $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/check_completed_dependency",
                                data: {
                                    task_id: a.prerequisite_task_id
                                },
                                success: function(t) {
                                    t && (t = jQuery.parseJSON(t), $.ajax({
                                        type: "post",
                                        url: SIDEURL + "kanban/set_update_task",
                                        data: {
                                            task_id: a.prerequisite_task_id,
                                            color_menu: $("#kanban_color_menu").val()
                                        },
                                        success: function(e) {
                                            t.main_task_status_id == t.task_status_id ? $("#main_" + a.prerequisite_task_id).length && ($("#main_" + a.prerequisite_task_id).replaceWith(e), "red" == t.completed_depencencies ? $("#up_status_" + a.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + a.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + a.prerequisite_task_id).remove(), $("#task_status_" + t.task_status_id + "_" + a.swimlane_id).prepend(e), "red" == t.completed_depencencies ? $("#up_status_" + a.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + a.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                        }
                                    }))
                                }
                            }), $("#task_status_" + COMP_status_id + "_" + e.swimlane_id).prepend(t)
                        }
                    }), $("#actual_time_task").modal("hide")
                }
            })
        }
    }), $(".close_actual_time_task").click(function() {
        var t = $("#task_actual_time_task_id").val();
        $("#task_" + t).find("input[type='checkbox']").prop("checked", !1), $("#task_" + t).find("span").removeClass("checked"), $("#actual_time_task").modal("hide")
    }), $("#frm_actual_time_drag").validate({
        rules: {
            task_actual_time: {
                required: !0
            }
        },
        errorPlacement: function(t, a) {
            t.insertAfter(a.parent("div"))
        },
        submitHandler: function() {
            $("#task_actual_time_task_id_drag").val(), $.ajax({
                type: "post",
                url: SIDEURL + "kanban/add_actual_time_drag",
                data: $("#frm_actual_time_drag").serialize(),
                success: function(t) {
                    var t = jQuery.parseJSON(t);
                    $("#main_" + t.task_id).remove(), $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/set_task",
                        data: {
                            task_id: t.task_id,
                            color_menu: $("#kanban_color_menu").val()
                        },
                        success: function(a) {
                            var e = $("#actual_time_task_came_from_id").val(),
                                s = e.split("_"),
                                i = s[2],
                                _ = s[3],
                                r = $("#task_count_hide_" + i + "_" + _).html();
                            $("#task_count_hide_" + i + "_" + _).html(parseInt(r) - 1);
                            var n = $("#actual_time_task_dropped_id").val(),
                                d = n.split("_"),
                                l = d[2],
                                o = d[3],
                                u = $("#task_count_hide_" + l + "_" + o).html();
                            $("#task_count_hide_" + l + "_" + o).html(parseInt(u) + 1);
                            var m = $("#status_time_" + l).html();
                            if (m) var c = (m.split("/"), get_minutes($("#status_time_" + l + " .hrrlt").html()));
                            else var c = "0";
                            var p = parseInt(60 * parseInt($("#task_actual_time_hour_drag").val())) + parseInt($("#task_actual_time_min_drag").val()),
                                k = hoursminutes(parseInt(c) + parseInt(p));
                            $("#spent_time_" + l).html(k);
                            var h = $("#completed_loadMore_limit" + l + o).val(),
                                v = parseInt(h) + parseInt("1");
                            $("#completed_loadMore_limit" + l + o).val(v), "0" != t.master_task_id && $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/next_noncompleted_recurrence",
                                data: {
                                    task_id: t.master_task_id
                                },
                                success: function(t) {
                                    t && (t = jQuery.parseJSON(t), $.ajax({
                                        type: "post",
                                        url: SIDEURL + "kanban/set_update_task",
                                        data: {
                                            task_id: t.task_id,
                                            color_menu: $("#kanban_color_menu").val()
                                        },
                                        success: function(a) {
                                            $("#task_status_" + t.task_status_id + "_" + t.swimlane_id).prepend(a)
                                        }
                                    }))
                                }
                            }), "0" != t.prerequisite_task_id && $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/check_completed_dependency",
                                data: {
                                    task_id: t.prerequisite_task_id
                                },
                                success: function(a) {
                                    a && (a = jQuery.parseJSON(a), $.ajax({
                                        type: "post",
                                        url: SIDEURL + "kanban/set_update_task",
                                        data: {
                                            task_id: t.prerequisite_task_id,
                                            color_menu: $("#kanban_color_menu").val()
                                        },
                                        success: function(e) {
                                            a.main_task_status_id == a.task_status_id ? $("#main_" + t.prerequisite_task_id).length && ($("#main_" + t.prerequisite_task_id).replaceWith(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + t.prerequisite_task_id).remove(), $("#task_status_" + a.task_status_id + "_" + t.swimlane_id).prepend(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                        }
                                    }))
                                }
                            }), "0" == t.task_kanban_order || "1" == t.task_kanban_order ? $("#" + $("#actual_time_task_dropped_id").val()).prepend(a) : $("#" + $("#actual_time_task_dropped_id").val() + " > div").length >= t.kanban_order_after ? $("#" + $("#actual_time_task_dropped_id").val() + " > div:nth-child(" + t.kanban_order_after + ")").after(a) : $("#" + $("#actual_time_task_dropped_id").val()).append(a)
                        }
                    }), $(".sortable").sortable("refresh"), $("#actual_time_task_drag").modal("hide")
                }
            })
        }
    }), $(".full_task div").addClass("before_timer"), $("#right_task_comment").limiter(CMT_TEXT_SIZE, $("#ch_cmt")), $(".task_anchor").click(function() {
        var t = this.id,
            a = t.split("_"),
            e = $("#th_hide_" + a[1]).html();
        $("#status_time_" + a[1]).hide(), $(".td_" + a[1]).hide(), $("#td_" + a[1]).addClass("board-collapsedColumnNameCell column-collapsed"), $("#th_show_" + a[1]).addClass("board-collapsedColumnNameCell column-collapsed"), $("#tdhideme_" + a[1]).show(), $("#td_" + a[1]).hide(), $("#th_hide_" + a[1]).html(""), $(".td_hide_" + a[1] + " .collapse_text").html(e), $(".td_hide_" + a[1]).css("width", "10px"), $("#th_hide_" + a[1]).css("width", "10px"), $(".td_hide_" + a[1]).show(), $("#th_" + a[1]).hide()
    }), $(".task_hide_anchor").click(function() {
        var t = this.id,
            a = t.split("_");
        $("#status_time_" + a[2]).show(), $(".td_" + a[2]).show(), $("#td_" + a[2]).removeClass("board-collapsedColumnNameCell column-collapsed"), $("#th_show_" + a[2]).removeClass("board-collapsedColumnNameCell column-collapsed"), $("#tdhideme_" + a[2]).hide(), $("#td_" + a[2]).show();
        var e = $("#collapse_" + a[2]).html();
        $("#th_hide_" + a[2]).html(e), $(".td_hide_" + a[1] + " .collapse_text").html(""), $(".td_hide_" + a[2]).hide(), $("#th_" + a[2]).show()
    }), $(".sortable").sortable({
        items: "> div:not(.unsorttd)",
        revert: !0,
        forcePlaceholderSize: !0,
        connectWith: "div",
        scroll: !1,
        placeholder: "drag-place-holder",
        scrollSensitivity: 10,
        scrollSpeed: 40,
        tolerance: "pointer",
        dropOnEmpty: !0,
        //forcePlaceholderSize: !0,
        helper: function(t, a) {
            return $(a).clone().addClass("dragging")
        },
        start: function(t, a) {},
        update: function(t, a) {
            var e = $(this).attr("id"),
                s = (e.split("_"), a.item.show().attr("id"));
            s = s.replace("main_", "");
            var i = $("#task_data_" + s).val(),
                _ = $("#" + e).sortable("serialize"),
                r = SIDEURL + "kanban/setOrder";
            $(".sortable").sortable("disable"), $("#main_" + s).addClass("pulsate"), $.ajax({
                url: r,
                type: "POST",
                data: {
                    order: _,
                    status: e,
                    scope_id: s,
                    task_data: i
                },
                success: function(t) {
                    var t = jQuery.parseJSON(t);
                    s != t.id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/set_update_task",
                        data: {
                            task_id: t.id,
                            color_menu: $("#kanban_color_menu").val()
                        },
                        success: function(t) {
                            App.init(), $("#main_" + s).replaceWith(t)
                        }
                    }), $("#main_" + s).removeClass("pulsate"), $(".sortable").sortable("enable")
                }
            })
        },
        stop: function(t, a) {},
        receive: function(t, a) {
            var e = $(this).attr("id"),
                s = $("#" + e).sortable("serialize"),
                i = a.sender[0].id,
                _ = i.split("_"),
                r = _[2],
                n = _[3],
                d = $("#status_time_" + r).html();
            if (d) var l = get_minutes($("#status_time_" + r + " .hrlft").html()),
                o = get_minutes($("#status_time_" + r + " .hrrlt").html());
            else var l = "0",
                o = "0";
            var u = this.id,
                m = u.split("_"),
                c = m[2],
                p = m[3],
                k = $("#status_time_" + c).html();
            if ("1" == ACTUAL_TIME_ON && c == COMP_status_id) {
                var h = a.item.show().attr("id");
                h = h.replace("main_", "");
                var v = $("#task_data_" + h).val(),
                    f = $("#task_time_" + h).html();
                if (f) var b = f.split("/"),
                    g = get_minutes(b[1]);
                else var g = "0";
                if ("0" == g) {
                    var I = $("#" + i).sortable("serialize");
                    $(a.sender).sortable("cancel"), $(this).sortable("refresh");
                    var y = $("#" + i).sortable("serialize");
                    $(this).sortable("refresh"), $.ajax({
                        url: SIDEURL + "kanban/setOrder",
                        type: "POST",
                        data: {
                            order: y,
                            status: i,
                            scope_id: h,
                            task_data: v
                        },
                        async: !1,
                        success: function(t) {
                            var t = jQuery.parseJSON(t);
                            return h != t.id && $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                async: !1,
                                success: function(t) {
                                    App.init(), $("#main_" + h).replaceWith(t), $(".sortable").sortable("refresh"), $("#main_" + h).removeClass("pulsate")
                                }
                            }), $("#task_actual_time_task_id_drag").val(h), $("#task_actual_time_drag").val(""), $("#task_actual_time_hour_drag").val(""), $("#task_actual_time_min_drag").val(""), $("#actual_time_task_came_from_orders").val(I), $("#actual_time_task_dropped_orders").val(s), $("#actual_time_task_came_from_id").val(i), $("#actual_time_task_dropped_id").val(u), $("#actual_time_task_drag").modal("show"), !1
                        }
                    })
                }
            }
            if (k) var S = (k.split("/"), get_minutes($("#status_time_" + c + " .hrlft").html())),
                x = get_minutes($("#status_time_" + c + " .hrrlt").html());
            else var S = "0",
                x = "0";
            e = e.replace("scope_", "");
            var h = a.item.show().attr("id");
            h = h.replace("main_", "");
            var v = $("#task_data_" + h).val(),
                j = SIDEURL + "kanban/UpdateScope",
                f = $("#task_time_" + h).html();
            if (f) var b = f.split("/"),
                q = get_minutes(b[0]),
                g = get_minutes(b[1]);
            else var q = "0",
                g = "0";
            $(".sortable").sortable("disable"), $("#main_" + h).addClass("pulsate"), $.ajax({
                type: "post",
                url: j,
                data: {
                    task_data: v,
                    scope_id: h,
                    status: e,
                    order: s
                },
                async: !1,
                success: function(t) {
                    var t = jQuery.parseJSON(t);
                    $("#main_" + t.id).removeClass("pulsate");
                    var a = $("#task_count_hide_" + r + "_" + n).html();
                    $("#task_count_hide_" + r + "_" + n).html(parseInt(a) - 1);
                    var e = $("#task_count_hide_" + c + "_" + p).html();
                    if ($("#task_count_hide_" + c + "_" + p).html(parseInt(e) + 1), r != c) {
                        var s = hoursminutes(parseInt(l) - parseInt(q)),
                            i = hoursminutes(parseInt(o) - parseInt(g)),
                            _ = "<span class='hrlft tooltips' id='Estimate_time_" + r + "' data-original-title='Estimate Time'>" + s + "</span><span id='spent_time_" + r + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + i + "</span>";
                        $("#status_time_" + r).html(_);
                        var d = hoursminutes(parseInt(S) + parseInt(q)),
                            u = hoursminutes(parseInt(x) + parseInt(g)),
                            m = "<span class='hrlft tooltips' id='Estimate_time_" + c + "' data-original-title='Estimate Time'>" + d + "</span><span id='spent_time_" + c + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + u + "</span>";
                        $("#status_time_" + c).html(m);
                        var k = $("#completed_loadMore_limit" + c + p).val(),
                            v = parseInt(k) + parseInt("1");
                        $("#completed_loadMore_limit" + c + p).val(v)
                    }
                    c == COMP_status_id ? ("0" != t.master_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/next_noncompleted_recurrence",
                        data: {
                            task_id: t.master_task_id
                        },
                        success: function(t) {
                            t && (t = jQuery.parseJSON(t), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(a) {
                                    $("#task_status_" + t.task_status_id + "_" + t.swimlane_id).prepend(a)
                                }
                            }))
                        }
                    }), "0" != t.prerequisite_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: t.prerequisite_task_id
                        },
                        success: function(a) {
                            a && (a = jQuery.parseJSON(a), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.prerequisite_task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(e) {
                                    a.main_task_status_id == a.task_status_id ? $("#main_" + t.prerequisite_task_id).length && ($("#main_" + t.prerequisite_task_id).replaceWith(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#task_status_" + a.task_status_id + "_" + t.swimlane_id).prepend(e), $("#main_" + t.prerequisite_task_id).remove())
                                }
                            }))
                        }
                    })) : ("0" != t.master_task_id && r == COMP_status_id && $(".kanban_master_" + t.master_task_id).remove(), "0" != t.prerequisite_task_id && $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/check_completed_dependency",
                        data: {
                            task_id: t.prerequisite_task_id
                        },
                        success: function(a) {
                            a && (a = jQuery.parseJSON(a), $.ajax({
                                type: "post",
                                url: SIDEURL + "kanban/set_update_task",
                                data: {
                                    task_id: t.prerequisite_task_id,
                                    color_menu: $("#kanban_color_menu").val()
                                },
                                success: function(e) {
                                    a.main_task_status_id == a.task_status_id ? $("#main_" + t.prerequisite_task_id).length && ($("#main_" + t.prerequisite_task_id).replaceWith(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + t.prerequisite_task_id).remove(), $("#task_status_" + a.task_status_id + "_" + t.swimlane_id).prepend(e), "red" == a.completed_depencencies ? $("#up_status_" + t.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + t.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                                }
                            }))
                        }
                    })), $.ajax({
                        type: "post",
                        url: SIDEURL + "kanban/set_update_task",
                        data: {
                            task_id: t.id,
                            color_menu: $("#kanban_color_menu").val()
                        },
                        success: function(t) {
                            App.init(), $("#main_" + h).replaceWith(t)
                        }
                    }), $(".sortable").sortable("enable"), $("#main_" + t.id).removeClass("pulsate");
                }
            })
        },
        cursor: "move"
    }).disableSelection(), $('[data-toggle="tooltip"]').tooltip(), $("#right_cmt").validate({
        rules: {
            right_task_comment: {
                required: !0
            }
        },
        submitHandler: function() {
            $("#right_cmt_btn").attr("disabled", "disabled");
            var t = $("#right_comment_task_id").val();
            $.ajax({
                type: "post",
                url: SIDEURL + "kanban/add_comment",
                data: $("#right_cmt").serialize() + "&color_menu=" + $("#kanban_color_menu").val(),
                success: function(a) {
                    $("#main_" + t).replaceWith(a), $("#right_cmt_btn").removeAttr("disabled", "disabled"), $("#comments_right").modal("hide"),
                            $("#comments_right").on('hidden.bs.modal', function(){
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
        })
                }
            })
        }
    }), $(".right_cmt_close").click(function() {
        $("#comments_right").modal("hide");
        $("#comments_right").on('hidden.bs.modal', function(){
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove()
        })
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
                success: function(t) {
                    $("#task_comment_list").val(""), $("#task_comment_list_loading").hide(), $("#cmts_list_submit").removeAttr("disabled", "disabled"), $("#comments_add").modal("hide"), alertify.set("notifier", "position", "top-right"), alertify.log("Comment added successfully")
                }
            })
        }
    }), $(".completed_loadMore").click(function() {
        $(this).css("opacity", "0.7"), $(this).off("click.disabled");
        var t = $(this).attr("data-status"),
            a = $(this).attr("data-swim");
        $("#flash").fadeIn(400).html('<img src="ajax-loader.gif" align="absmiddle"> <span class="loading">Loading Comment...</span>');
        var e = $("#Estimate_time_" + t).html(),
            s = $("#spent_time_" + t).html(),
            i = $("#completed_loadMore_limit" + t + a).val(),
            _ = parseInt(i) + parseInt("10");
        $("#completed_loadMore_limit" + t + a).val(_), $.ajax({
            type: "post",
            url: SIDEURL + "kanban/completed_loadmore",
            data: {
                swimlane_id: a,
                status_id: t,
                limit_complete: i,
                estimate: e,
                spent: s
            },
            success: function(e) {
                var s = "task_status_" + t + "_" + a,
                    i = e.split("RGB");
                if ("sj" === i[0].trim()) $("#completed_loadMore_" + t + a).attr("data-over", "1"), $("#completed_loadMore_" + t + a).hide();
                else {
                    $("#" + s).append(i[0]).focus();
                    var _ = $("#" + s).prop("scrollHeight") + "px";
                    $(".scroll1").slimScroll({
                        scrollTo: _,
                        height: "200px",
                        start: "bottom"
                    }), setTimeout(function() {
                        $("#td_" + t).find(".scroll1").slimscroll({
                            scrollTo: _
                        }), $("#" + s).find(".pulsate").removeClass()
                    }, 4e3), $("#completed_loadMore_" + t + a).css("opacity", "1"), $("#completed_loadMore_" + t + a).on("click.disabled", !1), $("#completed_loadMore_" + t + a).hide()
                }
                $("#td_" + t).html(i[1]), 0 != i[2] && $("#task_count_hide_" + t + "_" + a).html(i[2])
            }
        })
    })
});