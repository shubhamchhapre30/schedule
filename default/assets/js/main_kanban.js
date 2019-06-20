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
            if ("done" == a) $("#delete_task").modal("hide");
            else {
                var l = $("#status_time_" + e).html();
                if (l) var r = get_minutes($("#status_time_" + e + " .hrlft").html()),
                    _ = get_minutes($("#status_time_" + e + " .hrrlt").html());
                else var r = "0",
                    _ = "0";
                var s = $("#task_time_" + i).html();
                if (s) var n = s.split("/"),
                    h = get_minutes(n[0]),
                    m = get_minutes(n[1]);
                else var h = "0",
                    m = "0";
                var v = hoursminutes(parseInt(r) - parseInt(h)),
                    u = hoursminutes(parseInt(_) - parseInt(m)),
                    g = "<span class='hrlft tooltips' data-original-title='Estimate Time'>" + v + "</span><span class='hrrlt tooltips' data-original-title='Spent Time'>" + u + "</span>",
                    c = $("#task_count_hide_" + e + "_" + t).html();
                $("#task_count_hide_" + e + "_" + t).html(parseInt(c) - 1), $("#status_time_" + e).html(g), $("#main_" + i).remove(), $("#delete_task").modal("hide");
            }
        }
    })
}
$(function() {
    $("#redirect_page").val("from_kanban"), $("#task_actual_time").on("keydown", function(a) {
        if (13 === a.keyCode) {
            a.preventDefault();
            var e = $("#task_actual_time").blur();
            e[0].value && $("#frm_actual_time").submit()
        }
        else if (9 === a.keyCode) {
            var b = $("#task_actual_time").blur();
            setTimeout(function() { b[0].value && $("#frm_actual_time").find('.txtbold').first().focus(); }, 10); 
        }
    }), $("#task_actual_time").blur(function() {
        var a = $(this).val(),
            e = a.split(":");
        if (a)
            if (parseInt(a) > 0)
                if (1 == validate(a)) {
                    if (2 == e.length) {
                        var t = e[0],
                            l = e[1];
                        if (l >= 60) {
                            var i = parseInt(l / 60),
                                r = l % 60,
                                _ = +t + +i,
                                s = r;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(_), $("#task_actual_time_min").val(s)
                        } else {
                            var _ = t,
                                s = l;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(_), $("#task_actual_time_min").val(s)
                        }
                    }
                    if (a.length >= 1 && a.length <= 2)
                        if (a >= 60) {
                            var _ = parseInt(a / 60),
                                s = a % 60;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(_), $("#task_actual_time_min").val(s)
                        } else {
                            var s = a,
                                n = s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(0), $("#task_actual_time_min").val(s)
                        }
                    if (3 == a.length && 2 != e.length) {
                        var h = new Array,
                            h = ("" + a).split("");
                        if (h[a.length - (a.length - 1)] + h[a.length - (a.length - 2)] >= 60) {
                            var m = 1,
                                s = h[a.length - (a.length - 1)] + h[a.length - (a.length - 2)] - 60,
                                _ = +h[a.length - a.length] + +m;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(_), $("#task_actual_time_min").val(s)
                        } else {
                            var s = h[a.length - (a.length - 1)] + h[a.length - (a.length - 2)],
                                _ = h[a.length - a.length];
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(_), $("#task_actual_time_min").val(s)
                        }
                    }
                    if (4 == a.length && 2 != e.length) {
                        var h = new Array,
                            h = ("" + a).split("");
                        if (h[a.length - (a.length - 2)] + h[a.length - (a.length - 3)] >= 60) {
                            var m = 1,
                                s = h[a.length - (a.length - 2)] + h[a.length - (a.length - 3)] - 60,
                                _ = +(h[a.length - a.length] + h[a.length - (a.length - 1)]) + +m;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(_), $("#task_actual_time_min").val(s)
                        } else {
                            var s = h[a.length - (a.length - 2)] + h[a.length - (a.length - 3)],
                                _ = +(h[a.length - a.length] + h[a.length - (a.length - 1)]);
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time").val(n), $("#task_actual_time_hour").val(_), $("#task_actual_time_min").val(s)
                        }
                    }
                    a.length >= 5 && 2 != e.length && ($("input[name='task_time_spent']").val(""), alertify.alert("maximum 4 digits allowed"))
                } else $("#task_actual_time").val(""), alertify.alert("your inserted value is not correct, please insert correct value");
        else $("#task_actual_time").val(""), alertify.alert("Please enter greater than 0 time.")
    }), $("#task_actual_time_drag").on("keypress", function(a) {
        if (13 === a.keyCode) {
            a.preventDefault();
            var e = $("#task_actual_time_drag").blur();
            e[0].value && $("#frm_actual_time_drag").submit()
        }
    }), $("#task_actual_time_drag").blur(function() {
        var a = $(this).val(),
            e = a.split(":");
        if (a)
            if (parseInt(a) > 0)
                if (1 == validate(a)) {
                    if (2 == e.length) {
                        var t = e[0],
                            l = e[1];
                        if (l >= 60) {
                            var i = parseInt(l / 60),
                                r = l % 60,
                                _ = +t + +i,
                                s = r;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(_), $("#task_actual_time_min_drag").val(s)
                        } else {
                            var _ = t,
                                s = l;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(_), $("#task_actual_time_min_drag").val(s)
                        }
                    }
                    if (a.length >= 1 && a.length <= 2)
                        if (a >= 60) {
                            var _ = parseInt(a / 60),
                                s = a % 60;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(_), $("#task_actual_time_min_drag").val(s)
                        } else {
                            var s = a,
                                n = s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(0), $("#task_actual_time_min_drag").val(s)
                        }
                    if (3 == a.length && 2 != e.length) {
                        var h = new Array,
                            h = ("" + a).split("");
                        if (h[a.length - (a.length - 1)] + h[a.length - (a.length - 2)] >= 60) {
                            var m = 1,
                                s = h[a.length - (a.length - 1)] + h[a.length - (a.length - 2)] - 60,
                                _ = +h[a.length - a.length] + +m;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(_), $("#task_actual_time_min_drag").val(s)
                        } else {
                            var s = h[a.length - (a.length - 1)] + h[a.length - (a.length - 2)],
                                _ = h[a.length - a.length];
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(_), $("#task_actual_time_min_drag").val(s)
                        }
                    }
                    if (4 == a.length && 2 != e.length) {
                        var h = new Array,
                            h = ("" + a).split("");
                        if (h[a.length - (a.length - 2)] + h[a.length - (a.length - 3)] >= 60) {
                            var m = 1,
                                s = h[a.length - (a.length - 2)] + h[a.length - (a.length - 3)] - 60,
                                _ = +(h[a.length - a.length] + h[a.length - (a.length - 1)]) + +m;
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(_), $("#task_actual_time_min_drag").val(s)
                        } else {
                            var s = h[a.length - (a.length - 2)] + h[a.length - (a.length - 3)],
                                _ = +(h[a.length - a.length] + h[a.length - (a.length - 1)]);
                            if (0 == _) var n = s + "m";
                            else if (0 == s) var n = _ + "h";
                            else var n = _ + "h " + s + "m";
                            $("#task_actual_time_drag").val(n), $("#task_actual_time_hour_drag").val(_), $("#task_actual_time_min_drag").val(s)
                        }
                    }
                    a.length >= 5 && 2 != e.length && ($("#task_actual_time_drag").val(""), alertify.alert("maximum 4 digits allowed"))
                } else $("#task_actual_time_drag").val(""), alertify.alert("your inserted value is not correct, please insert correct value");
        else $("#task_actual_time_drag").val(""), alertify.alert("Please enter greater than 0 time.")
    }), $("#kanban_team_user_id").change(function() {
        $('#common-teambox').hide(), $("#dvLoading").fadeIn("slow"), $(this).val(), $.ajax({
            type: "post",
            url: SIDEURL + "kanban/searchDueTask",
            data: $("#last_remember").serialize(),
            success: function(a) {
                $("#kanban_view").html(a), $(".scroll1").slimScroll({
                    color: "#17A3E9",
                    height: "160",
                    wheelStep: 12,
                    showOnHover: !0
                }), $("#kanban_team_user_id").val() != LOGID ? ($("#kanban_team_user_id").parents("li").children("a").addClass("filter_selected"), $("#kanban_team_user_id").parents("li").children("a").children("i").addClass("filtericon-red"), $("#kanban_team_user_id").parents("li").children("a").children("i").removeClass("filtericon")) : ($("#kanban_team_user_id").parents("li").children("a").removeClass("filter_selected"), $("#kanban_team_user_id").parents("li").children("a").children("i").removeClass("filtericon-red"), $("#kanban_team_user_id").parents("li").children("a").children("i").addClass("filtericon")), $("#dvLoading").fadeOut("slow")
            }
        })
    }), $(".full_task div").addClass("before_timer"), $("#current_page").val("kanban")
});