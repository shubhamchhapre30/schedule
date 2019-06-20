function open_backlog(){
    $.ajax({
            type : 'post',
            url : SIDE_URL+"task/get_user_back_log_task",
            success : function(data){
                $("#task_list").html(data);
                $("#back_log").modal('show');
            },
            error:function(data){
            }
    });
}


function change_view(e) {
    if ("NextFiveDay" == ACTIVE_MENU) var a = SIDE_URL + "calendar/NextFiveDay_ajx";
    else var a = SIDE_URL + "calendar/weekview_ajx";
    $.ajax({
        type: "POST",
        url: a,
        data: {
            mydate: e,
            last_day: $("#last_day").val()
        },
        success: function(e) {
            $("#sjcalendar").html(e);
            var a = $(window).height(),
                t = parseInt(200) * parseInt(100) / parseInt(a),
                s = parseInt(a) - parseInt("240");
            $(".minhightweek").css("height", t), parseInt(s) - parseInt("100"), $(".scroll_cal_week").slimScroll({
                color: "#17A3E9",
                height: s,
                wheelStep: 20,
                showOnHover: !0
            })
        }
    })
}
$(function() {
    App.init(), $("#month_last_remeber").hide(), $("#week_last_remeber").show(), $(".full_task div").addClass("before_timer"), "FiveWeekView" == $("#current_page").val() ? $("#redirect_page").val($("#current_page").val()) : $("#redirect_page").val(ACTIVE_MENU);
    var e = $("#timer_task_id").val();
    if (e) {
        var a = $("#or_color_" + e).val();
        $("#task_" + e).css("border", "1px dashed " + a)
    }
    $(".sortable").sortable({
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
        helper: function(e, a) {
            return $(a).clone().addClass("dragging")
        },
        start: function(e, a) {},
        update: function(e, a) {
            var t = $(this).attr("id"),
                s = a.item.show().attr("id");
            s = s.replace("main_", "");
            var r = $("#task_data_" + s).val(),
                _ = $("#" + t).sortable("serialize");
            t = t.replace("week_", "");
            var l = SIDE_URL + "calendar/setWeekOrder";
            $(".sortable").sortable("disable"), $("#main_" + s).addClass("pulsate"), $.ajax({
                url: l,
                type: "POST",
                data: {
                    order: _,
                    status: t,
                    scope_id: s,
                    task_data: r,
                    start_date: $("#week_start_date").val(),
                    end_date: $("#week_end_date").val(),
                    action: $("#week_action").val(),
                    active_menu: ACTIVE_MENU,
                    color_menu :$("#task_color_menu").val()
                },
                success: function(e) {
                    if ("no_data" != e) {
                        var e = jQuery.parseJSON(e);
                        $.ajax({
                            type: "post",
                            url: SIDE_URL + "calendar/set_weekly_update_task",
                            data: {
                                task_id: e.id,
                                start_date: $("#week_start_date").val(),
                                end_date: $("#week_end_date").val(),
                                action: $("#week_action").val(),
                                active_menu: ACTIVE_MENU,
                                color_menu: $("#task_color_menu").val()
                            },
                            success: function(e) {
                                $("#main_" + s).replaceWith(e), $("body").removeClass("custom")
                            }
                        })
                    }
                    $(".sortable").sortable("enable"), $("#main_" + s).removeClass("pulsate")
                }
            })
        },
        stop: function(e, a) {},
        receive: function(e, a) {
            var t = $(this).attr("id");
            $("#" + t).sortable("serialize"), t = t.replace("week_", "");
            var s = a.item.show().attr("id");
            s = s.replace("main_", "");
            var r = $("#task_data_" + s).val(),
                _ = a.sender[0].id;
            _ = _.replace("week_", "");
                d = this.id;
            d = d.replace("week_", "");
            1 == $("#week_" + d + " .task_div").length && $("#week_" + d + " .space").remove();
            var c = $("#task_time_" + s).html();
            if (c) var v = c.split("/"),
                u = get_minutes(v[0]),
                p = get_minutes(v[1]);
            else var u = "0",
                p = "0";
            var m = $("#hdn_locked_due_date_" + s).val();
            if ("1" == m) {
                var k = $("#hdn_due_date_" + s).val();
                if (d > k) {
                    $(a.sender).sortable("cancel"), $(this).sortable("refresh");
                    var h = $("#week_" + _).sortable("serialize");
                    return $.ajax({
                        url: SIDE_URL + "calendar/setWeekOrder",
                        type: "POST",
                        data: {
                            order: h,
                            status: _,
                            scope_id: s,
                            task_data: r,
                            start_date: $("#week_start_date").val(),
                            end_date: $("#week_end_date").val(),
                            action: $("#week_action").val(),
                            active_menu: ACTIVE_MENU,
                            color_menu :$("#task_color_menu").val()
                        },
                        success: function(e) {
                            if ("no_data" != e) {
                                var e = jQuery.parseJSON(e);
                                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "calendar/set_weekly_update_task",
                                    data: {
                                        task_id: e.id,
                                        start_date: $("#week_start_date").val(),
                                        end_date: $("#week_end_date").val(),
                                        action: $("#week_action").val(),
                                        active_menu: ACTIVE_MENU,
                                        color_menu: $("#task_color_menu").val()
                                    },
                                    success: function(e) {
                                        $("#main_" + s).replaceWith(e), $("body").removeClass("custom");
                             
                                    }
                                })
                            }
                            $(".sortable").sortable("enable"), $("#main_" + s).removeClass("pulsate")
                        }
                    }), void alertify.alert("Sorry, you can only move the task into prior or equal due date")
                }
            }
            $(".sortable").sortable("disable"), $("#main_" + s).addClass("pulsate");
            var w = SIDE_URL + "calendar/UpdateWeekScope";
            $.ajax({
                url: w,
                type: "POST",
                data: {
                    task_data: r,
                    scope_id: s,
                    status: t,
                    start_date: $("#week_start_date").val(),
                    end_date: $("#week_end_date").val(),
                    action: $("#week_action").val(),
                    active_menu: ACTIVE_MENU,
                    color_menu :$("#task_color_menu").val()
                },
                success: function(e) {
                    var e = jQuery.parseJSON(e);
                               var c1 = parseInt($("#capacity_"+ _).attr('data-time'));
                                var e1 = parseInt($("#est_"+ _).attr('data-time'));
                                var s1 = parseInt($("#spent_"+ _).attr('data-time'));
                                var edif = parseInt(e1) - parseInt(u),
                                        sdef = parseInt(s1) - parseInt(p);
                                var c2 = parseInt($("#capacity_"+d).attr('data-time'));
                                var e2 = parseInt($("#est_"+d).attr('data-time'));
                                var s2 = parseInt($("#spent_"+d).attr('data-time'));
                                var enew = parseInt(e2)+parseInt(u);
                                var snew = parseInt(s2) + parseInt(p);
                                $('#progress_'+ _).empty(), $.ajax({
                                type: "post",
                                url: SIDE_URL + "calendar/update_progress_bar",
                                data: {
                                    id: _,
                                    capacity: c1,
                                    estimate_time: edif,
                                    spent_time: sdef,
                                    title: 'Capacity:'+hoursminutes(c1)+'<br>Estimate Time'+hoursminutes(edif)+'<br>Spent Time:'+hoursminutes(sdef)
                                },
                                success: function(progress) {
                                    $('#progress_'+ _).html(progress)
                                }
                            });
                             $('#progress_'+d).empty(), $.ajax({
                                type: "post",
                                url: SIDE_URL + "calendar/update_progress_bar",
                                data: {
                                    id: d,
                                    capacity: c2,
                                    estimate_time: enew,
                                    spent_time: snew,
                                    title: 'Capacity:'+hoursminutes(c2)+'<br>Estimate Time'+hoursminutes(enew)+'<br>Spent Time:'+hoursminutes(snew)
                                },
                                success: function(progress1) {
                                    $('#progress_'+d).html(progress1)
                                }
                            });
                    $.ajax({
                        type: "post",
                        url: SIDE_URL + "calendar/set_weekly_update_task",
                        data: {
                            task_id: e.id,
                            start_date: $("#week_start_date").val(),
                            end_date: $("#week_end_date").val(),
                            action: $("#week_action").val(),
                            active_menu: ACTIVE_MENU,
                            color_menu: $("#task_color_menu").val()
                        },
                        success: function(a) {
                            if ($("#main_" + s).replaceWith(a), $("#main_" + e.id).addClass("pulsate"), $("#week_" + d + " #main_" + e.id).length) {
                                0 == $("#week_" + _ + " .task_div").length && $("#week_" + _).prepend("<div class='space'></div>");
                                        
                            }
                            $(".sortable").sortable("enable"), $("#main_" + e.id).removeClass("pulsate")
                        }
                    })
                }
            })
        },
        cursor: ""
    }).disableSelection()
});
