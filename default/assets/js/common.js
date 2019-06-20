/**
 * returns the cookie value by index
 * @param {type} cname
 * @returns cookiee value
 * 
 */
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
/**
 * function sets the cookie 
 * @param {type} cname [Cookie index]
 * @param {type} cvalue [Cookie value]
 * @param {type} exdays [duration]
 */
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


/**
	 * Convert a number of seconds into an object of hours, minutes and seconds
	 * @param  {Number} sec [Number of seconds]
	 * @return {Object}     [An object with hours, minutes and seconds representation of the given seconds]
	 */
function sec2TimeObj(sec) {
		var hours = 0, minutes = Math.floor(sec / 60), seconds;

		// Hours
		if (sec >= 3600) {
			hours = Math.floor(sec / 3600);
		}
		if (hours < 10) {
			hours = '0' + hours;
		}
		// Minutes
		if (sec >= 3600) {
			minutes = Math.floor(sec % 3600 / 60);
		}
		// Prepend 0 to minutes under 10
		if (minutes < 10) {
			minutes = '0' + minutes;
		}
		// Seconds
		seconds = sec % 60;
		// Prepend 0 to seconds under 10
		if (seconds < 10 ) {
			seconds = '0' + seconds;
		}

		return {
			hours: hours,
			minutes: minutes,
			seconds: seconds
		};
	}
function set_color(a, b, c, d) {
    var ac=c;
    var e = a;
    e = e.replace("task_", "");
    var f = $("#task_data_" + e).val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/set_task_color",
        data: {
            color_id: b,
            task_id: a,
            task_data: f,
            redirect: c,
            action: $("#week_action").val(),
            active_menu: c
        },
        success: function(c) {
            
            if (a.indexOf("child") < 0) {
                var c = jQuery.parseJSON(c);
                if(ac == 'from_calendar')
                {
                    if(($("#cal_user_color_id").val() == b) || ($("#cal_user_color_id").val() == "0")){
					$("#task_"+a).css("background-color",c.color_code);
					$("#task_"+a).css("border","1px solid "+c.outside_color_code);
				} 
                }
                else
                {
                if ($("#cal_user_color_id").val() == b || "0" == $("#cal_user_color_id").val()) $("#task_" + a + " .comm-box.whitebox").css("background-color", c.color_code), $("#task_" + a).css("border", "1px solid " + c.outside_color_code), $("#task_" + a + " .comm-box.whitebox .comm-title,#task_" + a + " .comm-box.whitebox .comm-desc,#task_" + a + " .comm-box.whitebox .com-brdbtm,#task_" + a + " .comm-box.whitebox .commicon-list").css("border-bottom", "1px dashed " + c.outside_color_code);
                else if ($("#user_color_id").val() == b || "0" == $("#user_color_id").val()) $("#task_" + a + " .comm-box.whitebox").css("background-color", c.color_code), $("#task_" + a).css("border", "1px solid " + c.outside_color_code), $("#task_" + a + " .comm-box.whitebox .comm-title,#task_" + a + " .comm-box.whitebox .comm-desc,#task_" + a + " .comm-box.whitebox .com-brdbtm,#task_" + a + " .comm-box.whitebox .commicon-list").css("border-bottom", "1px dashed " + c.outside_color_code);
                else {
                    var e = d,
                        f = get_minutes($("#est_" + e).html()),
                        g = get_minutes($("#spent_" + e).html()),
                        h = $("#task_time_" + a).html();
                    if (h) var i = h.split("/"),
                        j = get_minutes(i[0]),
                        k = get_minutes(i[1]);
                    else var j = "0",
                        k = "0";
                    $("#main_" + a).remove();
                    var l = get_minutes($("#capacity_" + e).html()),
                        m = parseInt(f) - parseInt(j),
                        n = hoursminutes(m),
                        o = hoursminutes(parseInt(g) - parseInt(k));
                    m > l ? $("#est_" + e).addClass("red") : $("#est_" + e).removeClass("red"), $("#est_" + e).html(n), $("#spent_" + e).html(o)
                }
            }
            } else if(ac == 'from_calendar')
                $("#task_" + a).replaceWith(c);
                else $("#main_" + a).replaceWith(c)
        }
    })
}

function set_priority(a, b) {
   // $("#dvLoading").fadeIn("slow");
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/set_priority",
        data: {
            value: b,
            task_id: a,
            post_data: $("#task_data_" + a).val(),
            active_menu: ACTIVE_MENU,
            redirect: ACTIVE_MENU
        },
        success: function(c) {
            
            if(ACTIVE_MENU == 'weekView' || ACTIVE_MENU == 'NextFiveDay')
                "done" == c ? ($("#main_" + a).removeClass("red1"), $("#main_" + a).removeClass("yellow1"), $("#main_" + a).removeClass("green1"), "High" == b ? $("#main_" + a).addClass("red1") : "Medium" == b ? $("#main_" + a).addClass("yellow1") : "Low" == b && $("#main_" + a).addClass("green1")) : $("#main_" + a).replaceWith(c)
            else if(ACTIVE_MENU == 'from_project')
            {
				var g = $("#select_task_assign").val(),
                    h = $("#select_task_status").val();
                $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    data: {
                                        task_id: a,
                                        redirect_page : ACTIVE_MENU,
										type: h,
                                        user_id: g
                                        },
                                    success: function(taskdiv) {
                                        App.init(), $("#task_tasksort_" + a).replaceWith(taskdiv)
                                    },
                                    error: function(e) {
                                        console.log("Ajax request not recieved!")
                                    }
                            });
            }
            else{
                $("#task_"+a).removeClass("caliconNone");
				$("#task_"+a).removeClass("caliconLow");
				$("#task_"+a).removeClass("caliconMedium");
				$("#task_"+a).removeClass("caliconHigh");
				if(b == "High"){
					$("#task_"+a).addClass("caliconHigh");
				} else if(b == "Medium"){
					$("#task_"+a).addClass("caliconMedium");
				} else if(b == 'Low'){
					$("#task_"+a).addClass("caliconLow");
				} else if(c == 'done'){
                                    $("#task_" + a).replaceWith(c)
				}
            }
                
        }
    })
   // $("#dvLoading").fadeOut("slow");
}
function insert_watchlist(a,b) {
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/save_watch_list",
        data: {
            data: a,
            redirect: b,
            active_menu: ACTIVE_MENU,
            color_menu:$("#task_color_menu").val()
        },
        success: function(b) {
            a = jQuery.parseJSON(a), $("#main_" + a.task_id).replaceWith(b),$("#task_" + a.task_id).replaceWith(b)
        }
    })
}

function delete_watchlist(a,b) {
     $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/delete_watch_list",
        data: {
            data: a,
            redirect: b,
            active_menu: b,
            color_menu:$("#task_color_menu").val()
        },
        success: function(b) {
             a = jQuery.parseJSON(a), $("#main_" + a.task_id).replaceWith(b),$("#task_" + a.task_id).replaceWith(b)
        }
    })
}
function openpopup(a, b) {
    $("#right_task_comment").val(""), $("#comments_right").modal("show"), $("#right_comment_task_id").val(a), $("#comments_right").on("shown.bs.modal", function() {
        jQuery().wysihtml5&&$("#right_task_comment").size()>0&&$("#right_task_comment").wysihtml5({stylesheets:["../default/assets/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]})
        $(this).find("#right_task_comment").focus()
    }), $("#task_data").val(b)
}
function copy_task(tdd,id, ac, sd, ed, date, ldd, cd, tsid, sid, bsid) {
  
	if(ac == 'weekView' || ac == 'NextFiveDay')
	{
		var d = new Date();
		var a = new Date();
			function i(a) {
			return 10 > a ? "0" + a : a
		}
		var j = new Date(a),
			k = [i(j.getDate()), i(j.getMonth() + 1), j.getFullYear()].join("-"),
			l = tdd;
		if ("1" == l) {
			var m = [j.getFullYear(), i(j.getMonth() + 1), i(j.getDate())].join("-");
			if (new Date(m).getTime() > new Date(c).getTime()) return alertify.alert("Sorry, you can only move the task into prior or equal due date"), !1
		}
			var p = $("#task_time_" + id).html();
		if (p) var q = p.split("/"),
			r = get_minutes(q[0]),
			s = get_minutes(q[1]);
		else var r = "0",
			s = "0";
		var t = $("#task_data_" + id).val();
		$.ajax({
			type: "post",
			url: SIDE_URL + "calendar/copy_task",
			data: {
				task_id: id,
			task_due_date: tdd,
			task_data:t
			},
			success: function(c) {
                            var c = jQuery.parseJSON(c);
			var c1 = parseInt($("#capacity_"+date).attr('data-time'));
						var e1 = parseInt($("#est_"+date).attr('data-time'));
						var s1 = parseInt($("#spent_"+date).attr('data-time'));
						var edif = parseInt(e1) + parseInt(r),
								sdef = parseInt(s1) + parseInt(s);
						//p > m ? $("#est_" + date).addClass("red") : $("#est_" + date).removeClass("red"), $("#est_" + date).html(q), $("#spent_" + date).html(t)
						$('#progress_'+date).empty(), $.ajax({
						type: "post",
						url: SIDE_URL + "calendar/update_progress_bar",
						data: {
							id: date,
							capacity: c1,
							estimate_time: edif,
							spent_time: sdef,
							title: 'Capacity: '+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(edif)+'<br>Time Spent: '+hoursminutes(sdef)
						},
						success: function(progress) {
							$('#progress_'+date).html(progress)
						}
					});
					
				   $.ajax({
						type: "post",
						url: SIDE_URL + "calendar/set_weekly_update_task",
						data: {
							task_id: c.task_id,
							start_date: $("#week_start_date").val(),
							end_date: $("#week_end_date").val(),
							action: $("#week_action").val(),
							active_menu: ac,
							color_menu:$("#task_color_menu").val()
						},
						success: function(b) {
				$("#week_" + date).find("#add_newTask_"+date).before(b)
						}
					})
				}
		})
	}
	else if( ac == 'from_calendar')
	{
		var task_id = id;
	var d = new Date();
	var a = new Date();
	    function i(a) {
        return 10 > a ? "0" + a : a
    }
    var j = new Date(a),
        k = [i(j.getDate()), i(j.getMonth() + 1), j.getFullYear()].join("-"),
        l = tdd;
    if ("1" == l) {
        var m = [j.getFullYear(), i(j.getMonth() + 1), i(j.getDate())].join("-");
        if (new Date(m).getTime() > new Date(c).getTime()) return alertify.alert("Sorry, you can only move the task into prior or equal due date"), !1
    }
   
    var t = $("#task_data_" + id).val();
	
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/copy_task",
        data: {
            task_id: id,
			task_due_date: tdd,
			task_data:t
        },
        success: function(c) {
            var c = jQuery.parseJSON(c);
			var task_type = $("#task_type_"+task_id).val();
					if($("#task_list_"+date).length == 0) {
		      			var is_data_available = 0;
		      		} else {
		      			var dropped_estimate = get_minutes($("#estimate_time_"+date).html());
			      		var dropped_capacity = $("#capacity_time_"+date).html();
						var d_h_index = dropped_capacity.indexOf("h");
						var dropped_capacity_time = dropped_capacity.substr(0,d_h_index);
						var is_data_available = 1;
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
					if(is_data_available == 1){
						

						var today_date_time = '<?php echo strtotime(date("Y-m-d"));?>';

						if(task_type){
							task_type1 = task_type.split(",");
							if(task_type1[0] == "1"){
								var dropped_completed = $("#completed_"+date).html();
								$("#completed_"+date).html(parseInt(dropped_completed)+1);
								var dropped_schedule = $("#scheduled_"+date).html();
								$("#scheduled_"+date).html(parseInt(dropped_schedule)+1);
								if(date == today_date_time){
									var dropped_due = $("#due_"+date).html();
									$("#due_"+date).html(parseInt(dropped_due)+1);
								}
							} else if(date<today_date_time){
								var dropped_overdued = $("#overdued_"+date).html();
								$("#overdued_"+date).html(parseInt(dropped_overdued)+1);
								var dropped_schedule = $("#scheduled_"+date).html();
								$("#scheduled_"+date).html(parseInt(dropped_schedule)+1);
							} else {
								var dropped_schedule = $("#scheduled_"+date).html();
								$("#scheduled_"+date).html(parseInt(dropped_schedule)+1);
								if(date == today_date_time){
									var dropped_due = $("#due_"+date).html();
									$("#due_"+date).html(parseInt(dropped_due)+1);
								}
							}
						}

						var dropped_min = parseInt(dropped_estimate)+parseInt(scope_time_estimate);
						var dropped_est = hoursminutes(dropped_min);
						$("#estimate_time_"+date).html(dropped_est);

						$("#estimate_time_"+date).removeAttr('class');
						if(dropped_min>(dropped_capacity_time*60)){
							$("#estimate_time_"+date).attr('class','commonlabel redlabel');
						} else {
							$("#estimate_time_"+date).attr('class','commonlabel');
						}
					}

				if(is_data_available == 0){
					var wd = $("#td_"+date+" .weekday-txt").html();
					wd = wd.replace('WD ','');
					$.ajax({
						type : 'post',
						url : SIDE_URL + "calendar/monthly_day_view",
						data : {date : date, task_id : c.task_id, year:$("#year").val(),month:$("#month").val(),'wd':wd,color_menu:$("#monthly_color_menu").val()},
						success : function(data){
							$("#td_"+date).html(data);
						}
					});
				} else {
					$.ajax({
						type : 'post',
						url : SIDE_URL + "calendar/set_update_task",
						data : {task_id : c.task_id, year:$("#year").val(),month:$("#month").val(),month_color_menu:$("#monthly_color_menu").val()},
						success : function(task_detal){
							$("#"+date).append(task_detal);
						}
					});

				}
            }
    })
	
	}
	else if(ac == 'from_kanban')
	{
    if ("1" == ACTUAL_TIME_ON && bsid == tsid) {
        var _ = $("#task_time_" + id).html();
        if (_) var r = _.split("/"),
            n = get_minutes(r[1]);
        else var n = "0";
        if ("0" == n) return $("#task_actual_time_task_id").val(id), $("#task_actual_time").val(""), $("#task_actual_time_hour").val(""), $("#task_actual_time_min").val(""), $("#actual_time_task").modal("show"), !1
    }
    var d = $("#status_time_" + tsid).html();
    if (d) var l = get_minutes($("#status_time_" + tsid + " .hrlft").html()),
        o = get_minutes($("#status_time_" + tsid + " .hrrlt").html());
    else var l = "0",
        o = "0";
    var _ = $("#task_time_" + id).html();
    if (_) var r = _.split("/"),
        u = get_minutes(r[0]),
        n = get_minutes(r[1]);
    else var u = "0",
        n = "0";
		var task_data = $("#task_data_" + id).val();
     $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/copy_task",
        data: {
            task_id: id,
            task_status_id: tsid,
	    task_data: task_data
        },
        success: function(t) {
            var t = jQuery.parseJSON(t);
                var i = $("#task_count_hide_" + tsid + "_" + sid).html();
            $("#task_count_hide_" + tsid + "_" + sid).html(parseInt(i) - 1);
            var _ = $("#task_count_hide_" + t.status_id + "_" + sid).html();
            $("#task_count_hide_" + t.status_id + "_" + sid).html(parseInt(_) + 1);
            var r = $("#status_time_" + t.status_id).html();
            if (r) var d = get_minutes($("#status_time_" + t.status_id + " .hrlft").html()),
                m = get_minutes($("#status_time_" + t.status_id + " .hrrlt").html());
            else var d = "0",
                m = "0";
            var c = hoursminutes(parseInt(l) - parseInt(u)),
                p = hoursminutes(parseInt(o) - parseInt(n)),
                k = "<span id='Estimate_time_" + tsid + "' class='hrlft tooltips' data-original-title='Estimate Time'>" + c + "</span><span id='spent_time_" + tsid + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + p + "</span>";
            $("#status_time_" + tsid).html(k);
            var h = hoursminutes(parseInt(d) + parseInt(u)),
                v = hoursminutes(parseInt(m) + parseInt(n)),
                f = "<span id='Estimate_time_" + t.status_id + "'  class='hrlft tooltips' data-original-title='Estimate Time'>" + h + "</span><span id='spent_time_" + t.status_id + "' class='hrrlt tooltips' data-original-title='Spent Time'>" + v + "</span>",
                b = $("#completed_loadMore_limit" + t.status_id + sid).val(),
                g = parseInt(b) + parseInt("1");
            $("#completed_loadMore_limit" + t.status_id + sid).val(g), $("#status_time_" + t.status_id).html(f), $.ajax({
                type: "post",
                url: SIDE_URL + "kanban/set_update_task",
                data: {
                    task_id: t.task_id,
                    color_menu: $("#kanban_color_menu").val()
                },
                success: function(a) { 
                    var e = a;
                    $("#task_status_" + t.status_id + "_" + t.swimlane_id).append(e);
                }
            })
        }
    })
	
	}
        else if(ac == 'from_customer')
        {
          
		var t = $("#task_data_" + id).val();
		$.ajax({
			type: "post",
			url: SIDE_URL + "calendar/copy_task",
			data: {
				task_id: id,
			task_due_date: tdd,
			task_data:t
			},
			success: function(c) {
                            var c = jQuery.parseJSON(c);
                             $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "customer/set_update_task",
                                    data: {
                                        task_id: c.task_id,
                                        redirect_page : ac,
                                        customer_id:$("#hide_customer_id").val()
                                        },
                                    success: function(taskdiv) {
                                        $("#taskTable > tbody").prepend(taskdiv);
                                    },
                                    error: function(e) {
                                        console.log("Ajax request not recieved!")
                                    }
                            });
					
				  
			}
		})  
        }
        else if(ac == 'from_project')
        {
          
		var t = $("#task_data_" + id).val();
		var g = $("#select_task_assign").val(),
            h = $("#select_task_status").val();
		$.ajax({
			type: "post",
			url: SIDE_URL + "calendar/copy_task",
			data: {
				task_id: id,
			task_due_date: tdd,
			task_data:t
			},
			success: function(c) {
                            var c = jQuery.parseJSON(c);
                             $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "project/set_update_task",
                                    data: {
                                        task_id: c.task_id,
                                        redirect_page : ac,
                                        type: h,
                                        user_id: g
                                        },
                                    success: function(taskdiv) {
                                        App.init(), $("#task_tasksort_" + id).after(taskdiv)
                                    },
                                    error: function(e) {
                                        console.log("Ajax request not recieved!")
                                    }
                            });
					
				  
			}
		})  
        }
}
function move_task(a, b, c, d, e, f, g, h) {
    if(f == 'weekView' || f == 'NextFiveDay')
    {
    function i(a) {
        return 10 > a ? "0" + a : a
    }
    var j = new Date(a),
        k = [i(j.getDate()), i(j.getMonth() + 1), j.getFullYear()].join("-"),
        l = b;
    if ("1" == l) {
        var m = [j.getFullYear(), i(j.getMonth() + 1), i(j.getDate())].join("-");
        if (new Date(m).getTime() > new Date(c).getTime()) return alertify.alert("Sorry, you can only move the task into prior or equal due date"), !1
    }
    var n = get_minutes($("#est_" + d).html()),
        o = get_minutes($("#spent_" + d).html()),
        p = $("#task_time_" + e).html();
    if (p) var q = p.split("/"),
        r = get_minutes(q[0]),
        s = get_minutes(q[1]);
    else var r = "0",
        s = "0";
    var t = $("#task_data_" + e).val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/move_task",
        data: {
            task_id: e,
            task_data: t,
            due_date: c,
            sel_date: k,
            redirect: f,
            start_date: $("#week_start_date").val(),
            end_date: $("#week_end_date").val(),
            action: $("#week_action").val(),
            active_menu: f
        },
        success: function(a) {
            var a = jQuery.parseJSON(a);
            if (a.date != d) {
                
                    var c1 = parseInt($("#capacity_"+d).attr('data-time'));
                    var e1 = parseInt($("#est_"+d).attr('data-time'));
                    var s1 = parseInt($("#spent_"+d).attr('data-time'));
                    var edif = parseInt(e1) - parseInt(r),
                            sdef = parseInt(s1) - parseInt(s);
                    
                    $('#progress_'+d).empty(), $.ajax({
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
                if (a.date >= g && a.date <= h) {
                    var c2 = parseInt($("#capacity_"+a.date).attr('data-time'));
                    var e2 = parseInt($("#est_"+a.date).attr('data-time'));
                    var s2 = parseInt($("#spent_"+a.date).attr('data-time'));
                    var enew = parseInt(e2)+parseInt(r);
                    var snew = parseInt(s2) + parseInt(s);
                 $('#progress_'+a.date).empty(), $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/update_progress_bar",
                    data: {
                        id: a.date,
                        capacity: c2,
                        estimate_time: enew,
                        spent_time: snew,
                        title: 'Capacity: '+hoursminutes(c2)+'<br>Time Estimate: '+hoursminutes(enew)+'<br>Time Spent: '+hoursminutes(snew)
                    },
                    success: function(progress1) {
                        $('#progress_'+a.date).html(progress1)
                    }
                })

                }
                $("#main_" + e).remove(), $.ajax({
                    type: "post",
                    url: SIDE_URL + "calendar/set_weekly_update_task",
                    data: {
                        task_id: a.task_id,
                        start_date: $("#week_start_date").val(),
                        end_date: $("#week_end_date").val(),
                        action: $("#week_action").val(),
                        active_menu: f,
                        color_menu:$("#task_color_menu").val()
                    },
                    success: function(b) { 
                        0 == $("#week_" + a.date + " .task_div").length && $("#week_" + a.date + " .space").remove(), $("#week_" + a.date).find("#add_newTask_"+a.date).before(b)
                    }
                })
            }
        }
    })
    }
    else if(f == 'from_customer')
    {
        $.ajax({
        type: "post",
        url: SIDE_URL + "calendar/move_task",
        data: {
            task_id: e,
            task_data: t,
            due_date: c,
            sel_date: a,
            redirect: f,
            active_menu: f
        },
        success: function(a) {
            var a = jQuery.parseJSON(a);
            $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "customer/set_update_task",
                                    data: {
                                        task_id: a.task_id,
                                        redirect_page : f,
                                        customer_id:$("#hide_customer_id").val()
                                        },
                                    success: function(taskdiv) {
                                        $("#listtask_" + e).length ? $("#listtask_" + e).replaceWith(taskdiv) : '';
                                    },
                                    error: function(e) {
                                        console.log("Ajax request not recieved!")
                                    }
                            });
        }
        });

    }
}
function rightClickChangeStatus(a, b, c, d) {
    if(c == 'weekView' || c == 'NextFiveDay' || c == 'from_calendar')
    {
        if ("red" == d) return alertify.alert("Main task not allow to change status as its dependent task not completed yet."), !1;
        var e = a;
        e = e.replace("task_", "");
        var f = $("#task_data_" + e).val();
        if ("1" == ACTUAL_TIME_ON && b == COMPLETED_ID) {
            var g = $("#task_time_" + a).html();
            if (g) var h = g.split("/"),
                i = get_minutes(h[1]);
            else var i = "0";
            if ("0" == i) return $("#task_actual_time_task_id").val(a), $("#task_actual_time_task_data").val($("#task_data_" + a).val()), $("#task_actual_time").val(""), $("#task_actual_time_hour").val(""), $("#task_actual_time_min").val(""), $("#actual_time_task").modal("show"),$("#actual_time_task").on("shown.bs.modal", function() {
        $("#task_actual_time").focus()
    }), !1
        }
        $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/change_status",
            data: {
                task_id: a,
                status_id: b,
                task_data: f,
                redirect: c,
                start_date: $("#week_start_date").val(),
                end_date: $("#week_end_date").val(),
                action: $("#week_action").val(),
                color_menu:$("#task_color_menu").val()
            },
            async: !1,
            success: function(a) {
                $("#main_" + e).replaceWith(a);
                var b = jQuery.parseJSON(f);
                "0" != b.prerequisite_task_id && $.ajax({
                    type: "post",
                    url: SIDEURL + "kanban/check_completed_dependency",
                    data: {
                        task_id: b.prerequisite_task_id
                    },
                    success: function(a) {
                        a && (a = jQuery.parseJSON(a), $.ajax({
                            type: "post",
                            url: SIDEURL + "calendar/set_weekly_update_task",
                            data: {
                                task_id: b.prerequisite_task_id,
                                start_date: $("#week_start_date").val(),
                                end_date: $("#week_end_date").val(),
                                action: $("#week_action").val(),
                                active_menu: ACTIVE_MENU,
                                color_menu:$("#task_color_menu").val()
                            },
                            success: function(c) {
                                a.main_task_status_id == a.task_status_id ? $("#main_" + b.prerequisite_task_id).length && ($("#main_" + b.prerequisite_task_id).replaceWith(c), "red" == a.completed_depencencies ? $("#up_status_" + b.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + b.prerequisite_task_id).find("input").removeAttr("disabled", "disabled")) : ($("#main_" + b.prerequisite_task_id).replaceWith(c), "red" == a.completed_depencencies ? $("#up_status_" + b.prerequisite_task_id).find("input").attr("disabled", "disabled") : $("#up_status_" + b.prerequisite_task_id).find("input").removeAttr("disabled", "disabled"))
                            }
                        }))
                    }
                })
            }
        })
    }
    else if(c == 'from_customer')
    {
        if ("red" == d) return alertify.alert("Main task not allow to change status as its dependent task not completed yet."), !1;
        var e = a;
        e = e.replace("task_", "");
        var f = $("#task_data_" + e).val();
        if ("1" == ACTUAL_TIME_ON && b == COMPLETED_ID) {
            var g = $("#task_time_" + a).html();
            if (g) var h = g.split("/"),
                i = get_minutes(h[1]);
            else var i = "0";
            if ("0" == i) return $("#task_actual_time_task_id").val(a), $("#task_actual_time_task_data").val($("#task_data_" + a).val()), $("#task_actual_time").val(""), $("#task_actual_time_hour").val(""), $("#task_actual_time_min").val(""), $("#actual_time_task").modal("show"), $("#actual_time_task").on("shown.bs.modal", function() {
        $("#task_actual_time").focus()
    }),!1
        }
        $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/change_status",
            data: {
                task_id: a,
                status_id: b,
                task_data: f,
                redirect: c,
                start_date: $("#week_start_date").val(),
                end_date: $("#week_end_date").val(),
                action: $("#week_action").val(),
                color_menu:$("#task_color_menu").val()
            },
            async: !1,
            success: function(data) {
                $("#listtask_" + a).replaceWith(data);
            }
            }) 
    }
    else if(c == 'from_project')
    {
        if ("red" == d) return alertify.alert("Main task not allow to change status as its dependent task not completed yet."), !1;
        var e = a;
        e = e.replace("task_", "");
        var f = $("#task_data_" + e).val();
        if ("1" == ACTUAL_TIME_ON && b == COMPLETED_ID) {
            var g = $("#task_time_" + a).html();
            if (g) var h = g.split("/"),
                i = get_minutes(h[1]);
            else var i = "0";
            if ("0" == i) return $("#task_actual_time_task_id").val(a), $("#task_actual_time_task_data").val($("#task_data_" + a).val()), $("#task_actual_time").val(""), $("#task_actual_time_hour").val(""), $("#task_actual_time_min").val(""), $("#actual_time_task").modal("show"),$("#actual_time_task").on("shown.bs.modal", function() {
        $("#task_actual_time").focus()
    }), !1
        }
        $.ajax({
            type: "post",
            url: SIDE_URL + "calendar/change_status",
            data: {
                task_id: a,
                status_id: b,
                task_data: f,
                redirect: c,
                start_date: $("#week_start_date").val(),
                end_date: $("#week_end_date").val(),
                action: $("#week_action").val(),
                color_menu:$("#task_color_menu").val()
            },
            async: !1,
            success: function(data) {
                App.init(), $("#task_tasksort_" + a).length ? $("#task_tasksort_" + a).replaceWith(data):''
            }
            }) 
    }
}
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function context_menu(obj){ 
			var object1 = jQuery.parseJSON(obj);
                        var color = [];
                            jQuery.each(object1.user_colors, function(i,item) {
					color.push({text:capitalizeFirstLetter(object1.user_colors[i].name)+"RGB"+object1.user_colors[i].color_code, href : 'javascript:void(0);', action: function(){
						set_color(object1.task_id,object1.user_colors[i].user_color_id,object1.active_menu,object1.date);
					}}
                            )});
                            color.push({text: 'None', href : 'javascript:void(0);', action: function(){
                                        set_color(object1.task_id,'0',object1.active_menu,object1.date);
                                        }})
                                    var task_status = [];
                            jQuery.each(object1.task_status, function(i,item) {
					task_status.push({text:object1.task_status[i].task_status_name, href:'javascript:void(0)', action: function(){
						 			rightClickChangeStatus(object1.task_id,object1.task_status[i].task_status_id,object1.active_menu,object1.dependency_status);
						 		}}
                            )});
                        var user_swimlanes = [];
                                    jQuery.each(object1.user_swimlanes, function(i,item) {
                                                user_swimlanes.push({text: object1.user_swimlanes[i].swimlanes_name, href:'javascript:void(0)', action:function(){
									RightClickChangeSwimlane(object1.task_id,object1.user_swimlanes[i].swimlanes_id,object1.active_menu);
								}}
                                    )});
                        var context1 = [];
                       
                         
       if(object1.active_menu == 'weekView' || object1.active_menu == 'NextFiveDay' || object1.active_menu == 'from_calendar' || object1.active_menu == 'from_kanban')
        {  
            if(object1.color_menu == "true"){
                context1.push({text: '<i class="stripicon set-color"></i>Set a colour', href : 'javascript:void(0);', subMenu: color});
            }
                 context1.push(
				{text: '<i class="fa fa-exclamation-circle right_click_priority"></i>Set Priority', href : 'javascript:void(0)', subMenu: [
					{text: 'None', href : 'javascript:void(0)', action: function(){
						set_priority(object1.task_id,'None');
					}},
					{text: 'LowRGB#48a593', href : 'javascript:void(0)',  action: function(){
						set_priority(object1.task_id,'Low');
					}},
					{text: 'MediumRGB#eeb269', href : 'javascript:void(0)', action: function(){
						set_priority(object1.task_id,'Medium');
					}},
					{text: 'HighRGB#dc5753', href : 'javascript:void(0)', action: function(){
						set_priority(object1.task_id,'High');
					}}
				]});
				if(object1.chk_watch_list != "0"){
                                        context1.push({text : '<i class="fa fa-star-o right_click_watch_list"></i>Remove from Watch List', href : 'javascript:void(0)', action : function(){
                                                                        delete_watchlist($("#task_data_"+object1.task_id).val(),object1.active_menu);
                                                                }});
                                 } 
                                else
                                {
                                     context1.push({text : '<i class="fa fa-star-o right_click_watch_list"></i>Add to Watch List', href : 'javascript:void(0)', action : function(){
                                                                insert_watchlist($("#task_data_"+object1.task_id).val(),object1.active_menu);
                                                        }});
                                }
				context1.push({text: '<i class="stripicon due-date"></i>Set due date', autoHide: true, id:'due_date',href: 'javascript:void(0)', action:function(){
                                            $(this).datepicker("setDate",object1.task_due_date);
                                            $(this).datepicker("show").on('changeDate', function(e) {
                                                e.stopImmediatePropagation();                                            
                                           
						$(this).datepicker('hide');
						function pad(s) { return (s < 10) ? '0' + s : s; }
						//var d = new Date(selected_date.date);
						var sel_date = $(this).data('datepicker').getFormattedDate('mm-dd-yyyy');
						var scope_id = object1.task_id;
						scope_id = scope_id.replace('task_', '');
						var orig_data = $('#task_data_'+scope_id).val();
                                                if(sel_date != object1.task_due_date){
                                                        var select_date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
                                                        $.ajax({
                                                                type: 'post',
                                                                url: SIDE_URL + "calendar/set_task_due_date",
                                                                data : {task_id : scope_id, due_date : select_date, task_data : orig_data, redirect:object1.active_menu, start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),color_menu:$("#task_color_menu").val()},
                                                                async : false,
                                                                success : function(data){
                                                                    if(object1.active_menu == 'from_customer')
                                                                        $("#listtask_" + scope_id).length ? $("#listtask_" + scope_id).replaceWith(data) : '';
                                                                    else
                                                                        $("#main_"+scope_id).replaceWith(data);
                                                                    
                                                                        $(".dropdown-menu").css("display","none"); 
                                                                }
                                                        });
                                                }
					});
				}});
				if(object1.color_menu == 'true' && object1.active_menu != 'from_kanban')
                                {
                                    context1.push(
				{text : '<i class="icon-circle-arrow-down right_click_swimlane"></i>Change Status', href:'javascript:void(0)', subMenu:task_status},
				{text : '<i class="icon-columns right_click_swimlane"></i>Change swimlane', href:'javascript:void(0)', subMenu:user_swimlanes}
			);
                                }
                                else if(object1.color_menu == 'false' && object1.active_menu != 'from_kanban')
                                {
                                    context1.push({text : '<i class="icon-circle-arrow-down right_click_swimlane"></i>Change Status', href:'javascript:void(0)', subMenu:task_status}); 
                                }
				context1.push({text: '<i class="icon-copy right_click_copy_task"></i>Copy task', href : 'javascript:void(0);', id :'copy_'+object1.task_id, action:function(){
						copy_task(object1.task_due_date,object1.task_id,object1.active_menu,object1.start_date,object1.end_date,object1.date,object1.locked_due_date, object1.completed_depencencies,object1.task_status_id,object1.swimlane_id,object1.before_status_id);
						
				}});
                             
                                if(object1.active_menu == 'from_kanban')
                                 {
                                     context1.push({text: '<i class="icon-random right_click_move_task"></i>Move the task', href : 'javascript:void(0);', subMenu: [

                                                         {text: 'Right', href : 'javascript:void(0)', action: function(){
                                                                 move_right(object1.completed_depencencies,object1.task_id,object1.task_status_id,object1.swimlane_id,object1.before_status_id);
                                                         }}
                                                 ]});
                                 }
                                 else
                                  { if(object1.active_menu == 'from_calendar')
                                      {
                                          context1.push({text: '<i class="icon-random right_click_move_task"></i>Move the task', href : 'javascript:void(0);', id :'schedule_date', action:function(){
                                                            $(this).datepicker("setDate",object1.task_scheduled_date);
                                                            $(this).datepicker("show").on('changeDate', function(e) {
                                                                e.stopImmediatePropagation();                                            
                                                                var selected_date = $(this).data('datepicker').getFormattedDate('mm-dd-yyyy');
                                                                if(selected_date != object1.task_scheduled_date){
                                                                    var select_date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
                                                                     $(this).datepicker('hide');
                                                                    move_task1(select_date,object1.locked_due_date,object1.date,object1.task_id,object1.task_due_date,object1.start_date,object1.end_date);
                                                                    $(".dropdown-menu").css("display","none");
                                                                }
                                                            });
                                                            }});
                                      }
                                      else
                                      {
                                        context1.push({text: '<i class="icon-random right_click_move_task"></i>Move the task', href : 'javascript:void(0);', id :'schedule_date', action:function(){
                                                            $(this).datepicker("setDate",object1.task_scheduled_date);
                                                            $(this).datepicker("show").on('changeDate', function(e) {
                                                                e.stopImmediatePropagation();                                            
                                                                var selected_date = $(this).data('datepicker').getFormattedDate('mm-dd-yyyy');
                                                                if(selected_date != object1.task_scheduled_date){
                                                                    var select_date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
                                                                     $(this).datepicker('hide');
                                                                    move_task(select_date,object1.locked_due_date,object1.task_due_date,object1.date,object1.task_id,object1.active_menu,object1.start_date,object1.end_date);
                                                                    $(".dropdown-menu").css("display","none");
                                                                }
                                                            });
				}});
                        }
                }
                                
				context1.push({text: '<i class="icon-comment-alt right_click_comment"></i>Add comment', href : 'javascript:void(0);', action: function(){
					var scope_id = object1.task_id;
					scope_id = scope_id.replace('task_', '');
					var orig_data = $('#task_data_'+scope_id).val();
					openpopup(object1.task_id,orig_data);
				}}
			);
                
            }
            if(object1.active_menu == 'from_customer')
            { 
               context1.push({text: '<i class="stripicon due-date"></i>Set due date', autoHide: true, id:'due_date',href: 'javascript:void(0)', action:function(){
                                           
                                            $(this).datepicker("setDate",object1.task_due_date);
                                            $(this).datepicker("show").on('changeDate', function(e) {
                                                e.stopImmediatePropagation();                                            
                                           
						$(this).datepicker('hide');
						function pad(s) { return (s < 10) ? '0' + s : s; }
						//var d = new Date(selected_date.date);
						var sel_date = $(this).data('datepicker').getFormattedDate('mm-dd-yyyy');
						var scope_id = object1.task_id;
						scope_id = scope_id.replace('task_', '');
						var orig_data = $('#task_data_'+scope_id).val();
                                                if(sel_date != object1.task_due_date){
                                                        var select_date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
                                                        $.ajax({
                                                                type: 'post',
                                                                url: SIDE_URL + "calendar/set_task_due_date",
                                                                data : {task_id : scope_id, due_date : select_date, task_data : orig_data, redirect:object1.active_menu, start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),color_menu:$("#task_color_menu").val()},
                                                                async : false,
                                                                success : function(data){
                                                                if(object1.active_menu == 'from_customer')
                                                                        $("#listtask_" + scope_id).length ? $("#listtask_" + scope_id).replaceWith(data) : '';
                                                                    else
                                                                        $("#main_"+scope_id).replaceWith(data);
                                                                        $(".dropdown-menu").css("display","none"); 
                                                                }
                                                        });
                                                }
					});
				}},
                            {text : '<i class="icon-circle-arrow-down right_click_swimlane"></i>Change Status', href:'javascript:void(0)', subMenu:task_status},
				
				{text: '<i class="icon-copy right_click_copy_task"></i>Copy task', href : 'javascript:void(0);', id :'copy_'+object1.task_id, action:function(){
						copy_task(object1.task_due_date,object1.task_id,object1.active_menu,object1.start_date,object1.end_date,object1.date,object1.locked_due_date, object1.completed_depencencies,object1.task_status_id,object1.swimlane_id,object1.before_status_id);
						
				}},
                            {text: '<i class="icon-random right_click_move_task"></i>Move the task', href : 'javascript:void(0);', id :'schedule_date', action:function(){
                                                           
                                            $(this).datepicker("setDate",object1.task_scheduled_date);
                                                            $(this).datepicker("show").on('changeDate', function(e) {
                                                                e.stopImmediatePropagation();                                            
                                                                var selected_date = $(this).data('datepicker').getFormattedDate('mm-dd-yyyy');
                                                                if(selected_date != object1.task_scheduled_date){
                                                                    var select_date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
                                                                     $(this).datepicker('hide');
                                                                    move_task(select_date,object1.locked_due_date,object1.task_due_date,object1.date,object1.task_id,object1.active_menu,object1.start_date,object1.end_date);
                                                                    $(".dropdown-menu").css("display","none");
                                                                }
                                                            });
				}})
                            
                            
            }
                if(object1.active_menu == 'from_project')
            { 
                context1.push(
				{text: '<i class="fa fa-exclamation-circle right_click_priority"></i>Set Priority', href : 'javascript:void(0)', subMenu: [
					{text: 'None', href : 'javascript:void(0)', action: function(){
						set_priority(object1.task_id,'None');
					}},
					{text: 'LowRGB#48a593', href : 'javascript:void(0)',  action: function(){
						set_priority(object1.task_id,'Low');
					}},
					{text: 'MediumRGB#eeb269', href : 'javascript:void(0)', action: function(){
						set_priority(object1.task_id,'Medium');
					}},
					{text: 'HighRGB#dc5753', href : 'javascript:void(0)', action: function(){
						set_priority(object1.task_id,'High');
					}}
				]});
               context1.push({text: '<i class="stripicon due-date"></i>Set due date', autoHide: true, id:'due_date',href: 'javascript:void(0)', action:function(){
                                            $(this).datepicker("setDate",object1.task_due_date);
                                            $(this).datepicker("show").on('changeDate', function(e) {
                                                e.stopImmediatePropagation();                                            
                                           
						$(this).datepicker('hide');
						function pad(s) { return (s < 10) ? '0' + s : s; }
						//var d = new Date(selected_date.date);
						var sel_date = $(this).data('datepicker').getFormattedDate('mm-dd-yyyy');
						var scope_id = object1.task_id;
						scope_id = scope_id.replace('task_', '');
						var orig_data = $('#task_data_'+scope_id).val();
                                                if(sel_date != object1.task_due_date){
                                                        var select_date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
                                                        $.ajax({
                                                                type: 'post',
                                                                url: SIDE_URL + "calendar/set_task_due_date",
                                                                data : {task_id : scope_id, due_date : select_date, task_data : orig_data, redirect:object1.active_menu, start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),color_menu:$("#task_color_menu").val()},
                                                                async : false,
                                                                success : function(data){
                                                                    App.init(), $("#task_tasksort_" + scope_id).length ? $("#task_tasksort_" + scope_id).replaceWith(data):'';$(".dropdown-menu").css("display","none"); 
                                                                }
                                                        });
                                                }
					});
				}},
                            {text : '<i class="icon-circle-arrow-down right_click_swimlane"></i>Change Status', href:'javascript:void(0)', subMenu:task_status},
				
				{text: '<i class="icon-copy right_click_copy_task"></i>Copy task', href : 'javascript:void(0);', id :'copy_'+object1.task_id, action:function(){
						copy_task(object1.task_due_date,object1.task_id,object1.active_menu,object1.start_date,object1.end_date,object1.date,object1.locked_due_date, object1.completed_depencencies,object1.task_status_id,object1.swimlane_id,object1.before_status_id);
						
				}})
                            
                            
            }
                if(object1.task_owner_id == LOG_USER_ID || object1.report_user_list_id==1)
                {
                    context1.push({text: '<i class="icon-trash right_click_delete"></i>Delete task', class:'right_click_delete',id:'right_click_delete_'+object1.task_id, href : 'javascript:void(0);', action: function(){
                             $('#'+this.id).confirmation({placement:'right'});
                             $('#'+this.id).confirmation('show').on('confirmed.bs.confirmation',function(){
                                if(object1.master_task_id == '0' || object1.is_master_deleted == '1'){
                                    right_click_delete(object1.task_id,object1.task_due_date,object1.active_menu,object1.date);
                                } else {
                                        opendelete(object1.task_id,object1.master_task_id,object1.task_due_date,object1.active_menu,object1.date);
                                }
                         });                         
                    }});
                }
            
	context.attach('#task_'+object1.task_id, context1);

							}
						

function get_minutes(e) {
    if (e) {
        var a = e.indexOf("h"),
            t = e.indexOf("m");
        if (0 > a) var s = "0",
            _ = e.substr(0, t);
        else if (0 > t) {
            t = "0";
            var _ = "0",
                s = e.substr(0, a)
        } else var s = e.substr(0, a),
            _ = e.substr(parseInt(a) + 1, parseInt(t) - (parseInt(a) + 1));
        var d = 60 * parseInt(s) + parseInt(_);
        return d
    }
}

function hoursminutes(e) {
    var a = Math.floor(e / 60),
        t = e - 60 * a;
    return "0" == a && "0" == t ? "0m" : "0" != a && "0" == t ? a + "h" : "0" == a && "0" != t ? t + "m" : a + "h" + t + "m"
}

function add_task(e, a) { 
    $("#full-width").modal({
        backdrop: "static",
        keyboard: !1,
        show: !1
    });
    var t = $("#redirect_page").val();
    
            $("#customer_id").find("option[value='0']").prop("selected", "selected");
        $('#customer_id').trigger('chosen:updated');
    $("#task_allocated_user_id").find("option[value='"+LOG_USER_ID+"']").prop("selected", "selected");
        $('#task_allocated_user_id').trigger('chosen:updated');            
    $("#task_swimlane_id").find("option[value="+DEFAULT_SWIMLANE+"]").prop("selected", "selected");            
    if ($("#updated_dependencies").html('<tr><td colspan="6">No record available.</td></tr>'), $("#updated_steps").html('<tr><td colspan="3">No Record Available.</td></tr>'), $("#updated_files").html('<tr><td colspan="3">No Records available.</td></tr>'), $("#updated_task_comments").html(""), $("#scroll_history ul").html("<li>No Record Available.</li>"), "from_dashboard" == t || "from_teamdashboard" == t || "from_calendar" == t || "FiveWeekView" == t || "weekView" == t || "NextFiveDay" == t ? ($("#task_due_date").val(a), $("#old_task_due_date").val(a), $("#tmp_task_due_date").val(a), $("#task_due_date_div").datepicker("update", a), $("#task_due_date_div").datepicker("refresh"), $("#task_scheduled_date").val(a), $("#start_on_date").val(a), $("#start_on_date_picker").datepicker("update", a), $("#start_on_date_picker").datepicker("refresh"), $("#strtotime_scheduled_date").val(e)) : ($("#task_due_date").val(""), $("#old_task_due_date").val(""), $("#tmp_task_due_date").val(""), $("#task_due_date_div").datepicker("refresh"), $("#task_scheduled_date").val(""), $("#start_on_date").val(a), $("#start_on_date_picker").datepicker("update", a), $("#start_on_date_picker").datepicker("refresh"), $("#strtotime_scheduled_date").val("")), "from_project" == t ? ($("#general_project_id").val(e), $("#task_project_id").val(e), $("#dependent_project_id").val(e)) : ($("#general_project_id").val(0), $("#task_project_id").val(0), $("#dependent_project_id").val(0), $("#task_section_id").val(0), $("#dep_subsection_id").val(0), $("#dep_section_id").val(0)), $("#section_id").val(0), "from_kanban" == t) $("#task_status_id").val(e), $("#old_task_status_id").val(e);
    else {
        var s = READY_ID;
        $("#task_status_id").val(s), $("#old_task_status_id").val(s)
    }
    $("#multiple_people_id").show(), "from_calendar" == t || "FiveWeekView" == t || "weekView" == t || "NextFiveDay" == t ? ($("#task_allocated_user_id").val($("#calender_team_user_id").val()), $("#task_allocated_user_id").trigger("chosen:updated")) : "from_kanban" == t ? ($("#task_allocated_user_id").val($("#kanban_team_user_id").val()), $("#task_allocated_user_id").trigger("chosen:updated")) : ($("#task_allocated_user_id").val(LOG_USER_ID), $("#task_allocated_user_id").trigger("chosen:updated")), "from_kanban" == t && ($("#task_swimlane_id").val(a), $("#genral_swimlane_id").val(a)), $("#master_task_id").val("0"), $("#task_id").val(""), $("#old_task_id").val(""), $("#pre_task_id").val(""), $("#search_task_id").val(""), $("#step_task_id").val(""), $("#freq_task_id").val(""), $("#files_task_id").val(""),$("#file_task_data").val(''), $("#link_files_task_id").val(""),$("#link_file_task_data").val('') ,$("#comment_task_id").val(""), $("#task_title").val(""), $("#task_description").val(""), $("#task_category_id").val(0), $("#task_sub_category_id").val(0), $("#task_color_id").val(DEFAULT_COLOR),$("#customer_id").val(0), $("#task_staff_level_id").val(0), $("#task_owner_id_val").val(USERNAME), $("#task_owner_id").val(LOG_USER_ID), $("#allocation_task_id").val(""), $("#prerequisite_task_id").val(""), $("#from").val(""), $("#task_time_spent").val(""), $("#task_time_spent_hour").val(0), $("#task_time_spent_min").val(0), $("#task_time_estimate").val(""), $("#task_time_estimate_hour").val(0), $("#task_time_estimate_min").val(0), $("#old_task_time_estimate_hour").val(0), $("#old_task_time_estimate_min").val(0), $("#old_task_time_spent_hour").val(0), $("#old_task_time_spent_min").val(0), $("#task_skill_id").val(""), $("#task_division_id").multiselect("refresh"), $("#task_department_id").multiselect("refresh"), $("#task_skill_id").multiselect("refresh"), $("#total").val(0), $("#is_personal").removeAttr("checked", "checked"), $("#is_personal").parent("span").removeAttr("class", "checked"), $("#hdn_is_personal").val(0), $("#hdn_task_priority").val("None"), $("#task_priority").val("None"), $("#locked_due_date").removeAttr("checked", "checked"), $("#locked_due_date").parent("span").removeAttr("class", "checked"), $("#hdn_locked_due_date").val(0), $(".task_multiselect").multiselect("refresh"), $(".multiselect-container li").removeAttr("class"), $(".multiselect-container li span").removeAttr("class"), $("#full-width").modal("show"), $("#full-width").on("shown.bs.modal", function() {
        $("#task_title").focus()
    }), $("#recurrence").closest("span").removeClass("checked"), $("#recurrence").prop("checked", !1), $("#one_off").closest("span").addClass("checked"), $("#one_off").prop("checked", !0), $("#recurrence_div").hide(), $("#frquency_for_master_msg").hide(), $("#frquency_disable").hide(), $("#frquency_normal").show(), $(".save_close").show(), $(".alert").hide(), $("#updated_users").show(), $("#updated_users_multiple").hide(), $(".delete_task_btn").hide(), $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $(".task-tab-pane").removeClass("active"), $("#task_tab_1").addClass("active"), $("#task_due_date").attr("disabled", !1), $("#task_due_date_div").attr("disabled", !1), $("#task_due_date_div").css("pointer-events", ""), $("#task_category_id").attr("disabled", !1), $("#task_sub_category_id").attr("disabled", !1), $("#task_title").attr("disabled", !1), $("#task_description").attr("disabled", !1), add_task_ajax(), $("#is_multi_changed").val(0), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent,#customer_id").attr("disabled", !0), 
            "all" != $("#calender_project_id").val() && void 0 != $("#calender_project_id").val() && ($("#general_project_id").val($("#calender_project_id").val()), $("#task_project_id").val($("#calender_project_id").val()), $("#task_subsection_id").val($("#subsection_" + $("#calender_project_id").val()).val())),
            "all" != $("#kanban_project_id").val() && void 0 != $("#kanban_project_id").val() && ($("#general_project_id").val($("#kanban_project_id").val()), $("#task_project_id").val($("#kanban_project_id").val()), $("#task_subsection_id").val($("#subsection_" + $("#kanban_project_id").val()).val()))
    if($("#general_project_id").val()!='0'){   
   // if(project_id!='0'){
      $.ajax({
        url: SIDE_URL + "task/project_team",
        type: "post",
        data: {
            project_id:$("#general_project_id").val()
        },
        success: function(a) {
            var a = jQuery.parseJSON(a); 
            var d = "";
             var e = "";
             var f = "";
            if ("0" != a.users) {
                var g = 0;
                $.map(a.users, function(a) {
                    g++, a.user_id == LOG_USER_ID ? (d += '<option value="' + a.user_id + '" selected="selected">' + a.first_name + " " + a.last_name + "</option>",e += '<option value="' + a.user_id + '" selected="selected">' + a.first_name + " " + a.last_name,e += a.is_customer_user==1?" (external)":"",e+= "</option>") : (d += '<option value="' + a.user_id + '" >' + a.first_name + " " + a.last_name + "</option>",e += '<option value="' + a.user_id + '" >' + a.first_name + " " + a.last_name,e += a.is_customer_user==1?" (external)":"",e+= "</option>", f += '<li><input class="checkbox1" type="checkbox" name="task_allocated_user_id[]" value="' + a.user_id + '">' + a.first_name + " " + a.last_name + "</li>")
                }), g > 1 && (d += '<option value="multiple_people" id="multiple_people_id">Multiple People...</option>')
            }
           $("#updated_users").show(), $("#updated_users_multiple").hide(), $(".chk-container").html(f), App.init(), $("#task_allocated_user_id").html(d), $("#task_allocated_user_id").trigger("chosen:updated"), $("#depent_task_allocated_user_id").html(e), $("#depent_task_allocated_user_id").trigger("chosen:updated"), $("input[name='task_allocated_user_id[]']").on("change", function() {
                $("#is_multi_changed").val(1)
            });
            }
        });
    }
    
        if($("#active_menu").val() == 'from_customer'){ 
                $("#allocated_customer_id").val(e);
                $("#customer_id").find("option[value='"+e+"']").prop("selected", "selected");
                $('#customer_id').trigger('chosen:updated');
            }
}

function activaTab(tab){
  $('[href="#' + tab + '"]').tab('show');
};

function edit_task(e, a, t, tab) {
	var task_id=a;
	 if(t=="0"){ 
                $("#recurring_type").val('occurrence');
            }else{
                $("#recurring_type").val('');
            }
	if(tab==''){
            tab='task_tab_1';
        }
        
    $("#manual_reason").modal("hide"), $(".taskmain-container").removeAttr("style"), $("#type").val($("#typefilter1 li.active").attr("id")), $("#is_edited").val("0"), $("#is_edited1").val("0");
    var s = "";
    if ("series" == t) var s = "series";
    else var t = t || 1;
    if ($(e).hasClass("after_timer_on")) return !1;
    if ($(e).parent("div").hasClass("after_timer_on")) return !1;
    if ("1" != t) var _ = $("#task_data_" + a).val();
    else var _ = "";
    var d = "",
        l = "";
    $.ajax({
        url: SIDE_URL + "task/task_data",
        type: "post",
        data: {
            task_id: a,
            post_data: _,
            recurring_type:t
        },
        success: function(e) {
            function a(e) {
                return 10 > e ? "0" + e : e
            }

            function a(e) {
                return 10 > e ? "0" + e : e
            }

            function a(e) {
                return 10 > e ? "0" + e : e
            }
            var e = jQuery.parseJSON(e); 
            var c = "";
            0 == a && (c += '<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_color_id" id="task_color_id" tabindex="7"><option value="0" selected="selected">Please select</option>'), "" != e.color_codes && (c += '<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_color_id" id="task_color_id" tabindex="7"><option value="0">Please select</option>', $.map(e.color_codes, function(a) {
                c += e.task.general.color_id ==  a.user_color_id? '<option value="' + a.user_color_id + '" selected="selected">' + a.name + "</option>" : '<option value="' + a.user_color_id + '" >' + a.name + "</option>"
            }), c += "</select>"), $("#task_color_id").html(c);
            $("#allocated_customer_id").val(e.task.general.customer_id);
            if(e.task.general.customer_id!=''){ 
                $("#customer_id").find("option[value='" + e.task.general.customer_id + "']").prop("selected", "selected");
                $('#customer_id').trigger('chosen:updated');
            }else{
                $("#customer_id").find("option[value='0']").prop("selected", "selected");
                $('#customer_id').trigger('chosen:updated');
            }
            $("#task_swimlane_id").find("option[value='"+e.task.general.swimlane_id+"']").prop("selected", "selected");
            if(e.task.general.watch == 1)
                $('#add_to_watch').removeClass('green'),$('#add_to_watch').addClass('red'),$('#add_to_watch').html('Remove from Watch List');
            else
                $('#add_to_watch').removeClass('red'),$('#add_to_watch').addClass('green'),$('#add_to_watch').html('Add to Watch List');
            $("#series").modal("hide"), $(".alert").hide(), clear_form_elements("#frm_task_general"), clear_form_elements("#frm_search_dependency"), clear_form_elements("#frm_steps"), clear_form_elements("#frm_add_dependency"), clear_form_elements("#frm_add_allocation"), clear_form_elements("#frm_add_comment"), clear_form_elements("#frm_task_files"), $("#full-width").modal({
                backdrop: "static",
                keyboard: !1,
                show: !1
            }), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent,#customer_id").attr("disabled", !1), $("#task_id").val(e.task.general.task_id), $("#old_task_id").val(e.task.general.task_id), $("#pre_task_id").val(e.task.general.task_id), $("#step_task_id").val(e.task.general.task_id), $("#files_task_id").val(e.task.general.task_id),$("#file_task_data").val($("#task_data_"+e.task.general.task_id).val()), $("#link_files_task_id").val(e.task.general.task_id),$("#link_file_task_data").val($("#task_data_"+e.task.general.task_id).val()), $("#comment_task_id").val(e.task.general.task_id), $("#freq_task_id").val(e.task.general.task_id), $("#search_task_id").val(e.task.general.task_id), $("#allocation_task_id").val(e.task.general.task_id), $("#prerequisite_task_id").val(e.task.general.prerequisite_task_id), $("#from").val(s), $(".alert").hide(), $("#updated_dependencies").html('<tr><td colspan="6">No record available.</td></tr>'), $("#updated_steps").html('<tr><td colspan="3">No Record Available.</td></tr>'), $("#updated_files").html('<tr><td colspan="3">No Records available.</td></tr>'), $("#updated_task_comments").html(""), $("#scroll_history ul").html("<li>No Record Available.</li>"), $(".task-tab-pane").removeClass("active"), $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $("#task_tab_1").addClass("active"), $("#task_title").focus(), $("#is_personal").prop("checked", !1), $("#is_personal").parent("span").removeAttr("class", "checked"), $("#task_title").val(e.task.general.task_title), $("#task_description").val(e.task.general.task_description), "1" == e.task.general.is_personal ? ($("#is_personal").prop("checked", !0), $("#is_personal").parent("span").attr("class", "checked")) : ($("#is_personal").prop("checked", !1), $("#is_personal").parent("span").removeAttr("class", "checked")), $("#hdn_is_personal").val(e.task.general.is_personal), $("#task_priority").val(e.task.general.task_priority), $("#hdn_task_priority").val(e.task.general.task_priority), "1" == e.task.general.locked_due_date ? ($("#locked_due_date").prop("checked", !0), $("#locked_due_date").parent("span").attr("class", "checked")) : ($("#locked_due_date").prop("checked", !1), $("#locked_due_date").parent("span").removeAttr("class", "checked")), $("#hdn_locked_due_date").val(e.task.general.locked_due_date), e.task.general.task_owner_id == LOG_USER_ID || e.task.report_user_list_id==1 ? ($("#multiple_people_id").show(), $(".delete_task_btn").show()) : ($("#multiple_people_id").hide(), $(".delete_task_btn").hide()), $("#task_category_id").val(e.task.general.task_category_id), $("#task_sub_category_id").val(e.task.general.task_sub_category_id);
            var t = e.task.general.user_task_due_date;
            $("#task_due_date").val(t), $("#old_task_due_date").val(t), $("#tmp_task_due_date").val(t), t && $("#task_due_date_div").datepicker("update", t), $("#customer_id").val(e.task.general.customer_id),$("#task_color_id").val(e.task.general.color_id), $("#task_staff_level_id").val(e.task.general.task_staff_level_id),  $("#task_owner_id").val(e.task.general.task_owner_id), $("#task_allocated_user_id").val(e.task.general.task_allocated_user_id), $("#task_allocated_user_id").trigger("chosen:updated"), $("#task_project_id").val(e.task.general.task_project_id), $("#general_project_id").val(e.task.general.task_project_id), $("#task_project_title").val(e.task.general.project_title), $("#task_section_name").val(e.task.general.section_name),$("#hidden_section_id").val(e.task.general.section_id), $("#task_section_id").val(e.task.general.subsection_id), $("#task_subsection_id").val(e.task.general.subsection_id), $("#section_id").val(e.task.general.subsection_id), $("#task_time_spent_hour").val(e.task.general.task_time_spent_hour), $("#task_time_spent_min").val(e.task.general.task_time_spent_min), $("#old_task_time_spent_hour").val(e.task.general.task_time_spent_hour), $("#old_task_time_spent_min").val(e.task.general.task_time_spent_min), $("#task_time_spent").val(e.task.general.task_time_spent), $("#task_time_estimate_hour").val(e.task.general.task_time_estimate_hour), $("#task_time_estimate_min").val(e.task.general.task_time_estimate_min), $("#old_task_time_estimate_hour").val(e.task.general.task_time_estimate_hour), $("#old_task_time_estimate_min").val(e.task.general.task_time_estimate_min), $("#task_time_estimate").val(e.task.general.task_time_estimate), $("#task_status_id").val(e.task.general.task_status_id), $("#old_task_status_id").val(e.task.general.task_status_id), "red" == e.task.general.is_dependency_exist ? ($("#task_status_id").attr("disabled", "disabled"), $("#is_dependency_added").val("1")) : ($("#task_status_id").removeAttr("disabled", "disabled"), $("#is_dependency_added").val("0")), $("#master_task_id").val(e.task.general.master_task_id), $("#task_swimlane_id").val(e.task.general.swimlane_id), $("#kanban_order").val(e.task.general.kanban_order), $("#calender_order").val(e.task.general.calender_order), $("#task_scheduled_date").val(e.task.general.task_scheduled_date);
            var _ = e.task.general.strtotime_scheduled_date;
            if ($("#strtotime_scheduled_date").val(_), $("#task_orig_scheduled_date").val(e.task.general.task_orig_scheduled_date), $("#task_orig_due_date").val(e.task.general.task_orig_due_date), e.task.general.task_division_id) {
                get_department_by_division(e.task.general.task_division_id, e.task.general.task_department_id);
                var r = e.task.general.task_division_id;
                $(".multiselect-container li span").find("input:checkbox").closest("span").removeClass("checked");
                for (var i in r) {
                    var n = r[i];
                    $("#task_division_id").find("option[value=" + n + "]").prop("selected", "selected"), $(".multiselect-container li span").find("input:checkbox[value=" + n + "]").closest("span").addClass("checked")
                }
                $("#task_division_id").multiselect("refresh")
            } else $(".multiselect-container li span").find("input:checkbox").closest("span").removeClass("checked"), $("#task_division_id").multiselect("refresh");
            if (e.task.general.task_department_id) {
                var o = e.task.general.task_department_id;
                for (var i in o) {
                    var c = o[i];
                    $("#task_department_id").find("option[value=" + c + "]").prop("selected", "selected"), $(".multiselect-container li span").find("input:checkbox[value=" + c + "]").closest("span").addClass("checked")
                }
                $("#task_department_id").multiselect("refresh")
            } else $("#task_department_id").multiselect("refresh");
            if (e.task.general.task_skill_id) {
                var k = e.task.general.task_skill_id;
                for (var i in k) {
                    var p = k[i];
                    $("#task_skill_id").find("option[value=" + p + "]").prop("selected", "selected"), $(".multiselect-container li span").find("input:checkbox[value=" + p + "]").closest("span").addClass("checked")
                }
                $("#task_skill_id").multiselect("refresh")
            } else $("#task_skill_id").multiselect("refresh");
            $("#task_allocated_user_id").val(e.task.general.task_allocated_user_id),  $("#genral_swimlane_id").val(e.task.general.swimlane_id), $("#is_multi_changed").val(0), e.is_multiallocation_task ? multiple_people_html(e.task.general.task_project_id) : (add_task_ajax(), $("#updated_users").show(), $("#updated_users_multiple").hide()), "from_project" == $("#redirect_page").val() && ($("#task_project_div").html('<div class="input-icon right"><i class="stripicon iconlink"></i><input readonly="readonly" class="m-wrap span11 valid" id="task_project_title" name="project_title" value="" type="text" placeholder="Link to Project" aria-invalid="false"></div><input type="hidden" name="task_project_id" value="' + e.task.general.task_project_id + '" />'), $("#section_div").html('<div class="input-icon right"><i class="stripicon iconlink"></i><input class="m-wrap span11 valid" name="task_section_name" id="task_section_name" readonly="readonly" value="" type="text" placeholder="Project section" aria-invalid="false"></div><input type="hidden" name="section_id" value="' + e.task.general.subsection_id + '" />'), $("#task_project_title").val(e.task.general.project_title), $("#task_section_name").val(e.task.general.section_name)), $("#task_allocated_user_id").val(e.task.general.task_allocated_user_id), e.task.general.task_allocated_user_id != LOG_USER_ID && $("#task_swimlane_id").attr("disabled", !0), e.task.general.task_owner_id != e.task.general.task_allocated_user_id && e.task.general.task_allocated_user_id == LOG_USER_ID && "1" == e.task.general.locked_due_date && ($("#locked_due_date").attr("disabled", !0), $("#task_due_date").attr("disabled", !0), $("#task_due_date_div").attr("disabled", !0), $("#task_due_date_div").css("pointer-events", "none"), $("#hdn_task_due_date").val(e.task.general.task_due_date)), "0" != e.task.general.multi_allocation_task_id && e.task.general.task_owner_id != e.task.general.task_allocated_user_id ? ($("#task_due_date").attr("disabled", !0), $("#task_due_date_div").attr("disabled", !0), $("#task_due_date_div").css("pointer-events", "none"), $("#hdn_task_due_date").val(e.task.general.task_due_date), $("#task_category_id").attr("disabled", !0), $("#task_sub_category_id").attr("disabled", !0), $("#task_title").attr("disabled", !0), $("#task_description").attr("disabled", !0), $("#multiple_people_id").hide()) : ($("#task_due_date").attr("disabled", !1), $("#task_due_date_div").attr("disabled", !1), $("#task_due_date_div").css("pointer-events", ""), $("#task_category_id").attr("disabled", !1), $("#task_sub_category_id").attr("disabled", !1), $("#task_title").attr("disabled", !1), $("#task_description").attr("disabled", !1)), $("#task_allocated_user_id").trigger("chosen:updated"), $.map(e.task.dependencies, function(a) {
                function t(e) {
                    return 10 > e ? "0" + e : e
                }
                var s = "";
                
                if ("0000-00-00" != a.task_due_date) {
                    s = a.task_due_date;
                    var s = new Date(a.task_due_date),
                        _ = DEFAULT_DATE_FOMAT;
                    if ("d M,Y" == _) {
                        var l = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                        s = t(s.getDate()) + " " + l[s.getMonth()] + ", " + s.getFullYear()
                    } else s = "d/m/Y" == _ ? t(s.getDate()) + "/" + t(s.getMonth() + 1) + "/" + s.getFullYear() : "m/d/Y" == _ ? t(s.getMonth() + 1)+ "/" + t(s.getDate()) + "/" + s.getFullYear()  : "d-m-Y" == _ ? t(s.getDate()) + "-" + t(s.getMonth() + 1) + "-" + s.getFullYear() : s.getFullYear() + "-" + t(s.getMonth() + 1) + "-" + t(s.getDate())
                }
                d += "<tr>", d += "<td onclick=\"set_new_task_data('" + a.task_id + "','" + e.task.general.task_project_id + "')\">" + a.task_id + "</td>", d += "<td onclick=\"set_new_task_data('" + a.task_id + "','" + e.task.general.task_project_id + "')\">" + a.task_title + "</td>", d += "<td onclick=\"set_new_task_data('" + a.task_id + "','" + e.task.general.task_project_id + "')\">" + a.first_name + " " + a.last_name + "</td>", d += "<td onclick=\"set_new_task_data('" + a.task_id + "','" + e.task.general.task_project_id + "')\">" + s + "</td>", d += '<td><span class="label label-' + a.task_status_name.replace(/\s/g, "") + '">' + a.task_status_name + "</span></td>", d += "<td><a href='javascript:void(0)' class='tooltips' data-placement='top' data-original-title='Click to Un-link the task' onclick='remove_task_dependency(\"" + a.task_id + "\");' id='remove_task_dependency_"+ a.task_id +"' ><i class='icon-unlink'></i></a>", a.task_owner_id == LOG_USER_ID && (d += '<a href="javascript:void(0)" onclick="delete_dependent_task(\'' + a.task_id + '\');" id="delete_dependent_task_' + a.task_id + '" class="tooltips" data-placement="top" data-original-title="Click to Delete the dependency task"> <i class="icon-trash stngicn"></i> </a>'), d += "</td>", d += "</tr>"
            }),$("#dependent_project_id").val(e.task.general.task_project_id), $("#updated_dependencies").html(d), $("#main_task_due_date").val(e.task.general.task_due_date);
            var i = 0;
            if ($.map(e.task.steps, function(e) {
                    var a = "";
                    "1" == e.is_completed && (a = "checked='checked'"), l += "<tr>", l += '<script type="text/javascript">$(document).ready(function(){$("#step_title_' + e.task_step_id + '").editable({url: "' + SIDE_URL + 'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});</script>', l += '<td><label class="checkbox newcheckbox_task"><input type="checkbox" name="is_completed[]" id="' + e.task_step_id + '" value="1" ' + a + " /></label></td>", l += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_' + e.task_step_id + '" data-type="text" data-pk="1" data-original-title="' + e.step_title + '">' + e.step_title + "</a></td>", l += "<td>", e.step_added_by == LOG_USER_ID && (l += '<a href="javascript:;" onclick="delete_step(\'' + e.task_step_id + '\')" id="delete_step_'+ e.task_step_id +'" > <i class="icon-trash taskppstp"></i> </a>'), l += '<a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>', l += '<input type="hidden" name="step_title[]" value="' + e.step_title + '" />', l += '<input type="hidden" name="seq[]" value="' + e.step_sequence + '" />', l += '<input type="hidden" name="ids[]" value="' + e.task_step_id + '" />', l += '<input type="hidden" name="added_by[]" value="' + e.step_added_by + '" />', l += "</tr>", i++
                }), l && (l += '<input type="hidden" name="total" id="total" value="' + i + '" />'), $("#total").val(i), $("#updated_steps").html(l), App.initUniform(), $(".up,.down").click(function() {
                    var e = $(this).parents("tr:first");
                    $(this).is(".up") ? (e.insertBefore(e.prev()), $("#frm_steps").submit()) : (e.insertAfter(e.next()), $("#frm_steps").submit())
                }), $("input[name='is_completed[]']").click(function() {
                    $("#frm_steps").submit()
                }), "0" == e.task.general.master_task_id) {
                if ($("#frquency_for_master_msg").hide(), $("#frquency_disable").hide(), $("#frquency_normal").show(), "recurrence" == e.task.general.frequency_type) {
                    if ($("#one_off").closest("span").removeClass("checked"), $("#one_off").prop("checked", !1), $("#recurrence").closest("span").addClass("checked"), $("#recurrence").prop("checked", !0), $("#recurrence_div").show(), "1" == e.task.general.recurrence_type) $("#daily_chk").closest("span").addClass("checked"), $("#daily_chk").prop("checked", !0), $("#weekly_chk").closest("span").removeClass("checked"), $("#weekly_chk").prop("checked", !1), $("#monthly_chk").closest("span").removeClass("checked"), $("#monthly_chk").prop("checked", !1), $("#yearly_chk").closest("span").removeClass("checked"), $("#yearly_chk").prop("checked", !1), $("#daily_div").show(), $("#weekly_div").hide(), $("#monthly_div").hide(), $("#yearly_div").hide(), $("#datepicker_end_by").datepicker({
                        startDate: "+1d",
                        format: JAVASCRIPT_DATE_FORMAT
                    }), "1" == e.task.general.Daily_every_weekday ? ($("#Daily_every_weekday2").closest("span").addClass("checked"), $("#Daily_every_weekday2").prop("checked", !0), $("#Daily_every_week_day").val(e.task.general.Daily_every_week_day), $("#Daily_every_weekday").closest("span").removeClass("checked"), $("#Daily_every_weekday").prop("checked", !1)) : ($("#Daily_every_weekday").closest("span").addClass("checked"), $("#Daily_every_weekday").prop("checked", !0), $("#Daily_every_weekday2").closest("span").removeClass("checked"), $("#Daily_every_weekday2").removeAttr("checked", "checked"), $("#Daily_every_day").val(e.task.general.Daily_every_day)), $("#Daily_every_weekday").is(":checked") && $("#Daily_every_day").attr("disabled", !1), $("#Daily_every_weekday2").is(":checked") && $("#Daily_every_day").attr("disabled", !0);
                    else if ("2" == e.task.general.recurrence_type) {
                        $("#weekly_chk").closest("span").addClass("checked"), $("#weekly_chk").prop("checked", !0), $("#daily_chk").closest("span").removeClass("checked"), $("#daily_chk").prop("checked", !1), $("#monthly_chk").closest("span").removeClass("checked"), $("#monthly_chk").prop("checked", !1), $("#yearly_chk").closest("span").removeClass("checked"), $("#yearly_chk").prop("checked", !1), $("#weekly_div").show(), $("#daily_div").hide(), $("#monthly_div").hide(), $("#yearly_div").hide(), $("#datepicker_end_by").datepicker({
                            startDate: "+7d",
                            format: JAVASCRIPT_DATE_FORMAT
                        }), $("#Weekly_every_week_no").val(e.task.general.Weekly_every_week_no);
                        var h = e.task.general.Weekly_week_day;
                        var array = h.split(',');
                        $("input[name='Weekly_week_day[]']:checkbox").prop('checked',!1);
                        $.each(array, function( index, value ) { 
                            $("#weekly_week_day_"+value).attr('checked','checked')
                        });
                    } else "3" == e.task.general.recurrence_type ? ($("#monthly_chk").closest("span").addClass("checked"), $("#monthly_chk").prop("checked", !0), $("#weekly_chk").closest("span").removeClass("checked"), $("#weekly_chk").prop("checked", !1), $("#daily_chk").closest("span").removeClass("checked"), $("#daily_chk").prop("checked", !1), $("#yearly_chk").closest("span").removeClass("checked"), $("#yearly_chk").prop("checked", !1), $("#monthly_div").show(), $("#daily_div").hide(), $("#weekly_div").hide(), $("#yearly_div").hide(), $("#datepicker_end_by").datepicker({
                        startDate: "+1m",
                        format: JAVASCRIPT_DATE_FORMAT
                    }), "1" == e.task.general.monthly_radios ? ($("#monthly_radios1").closest("span").addClass("checked"), $("#monthly_radios1").prop("checked", !0), $("#Monthly_op1_1").val(e.task.general.Monthly_op1_1), $("#Monthly_op1_2").val(e.task.general.Monthly_op1_2)) : "2" == e.task.general.monthly_radios ? ($("#monthly_radios2").closest("span").addClass("checked"), $("#monthly_radios2").prop("checked", !0), $("#Monthly_op2_1").val(e.task.general.Monthly_op2_1), $("#Monthly_op2_2").val(e.task.general.Monthly_op2_2), $("#Monthly_op2_3").val(e.task.general.Monthly_op2_3)) : "3" == e.task.general.monthly_radios && ($("#monthly_radios3").closest("span").addClass("checked"), $("#monthly_radios3").prop("checked", !0), $("#Monthly_op3_1").val(e.task.general.Monthly_op3_1), $("#Monthly_op3_2").val(e.task.general.Monthly_op3_2)), $("#monthly_radios1").is(":checked") && ($("#Monthly_op1_1").attr("disabled", !1), $("#Monthly_op1_2").attr("disabled", !1), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0)), $("#monthly_radios2").is(":checked") && ($("#Monthly_op1_1").attr("disabled", !0), $("#Monthly_op1_2").attr("disabled", !0), $("#Monthly_op2_1").attr("disabled", !1), $("#Monthly_op2_2").attr("disabled", !1), $("#Monthly_op2_3").attr("disabled", !1), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0)), $("#monthly_radios3").is(":checked") && ($("#Monthly_op1_1").attr("disabled", !0), $("#Monthly_op1_2").attr("disabled", !0), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !1), $("#Monthly_op3_2").attr("disabled", !1))) : "4" == e.task.general.recurrence_type && ($("#yearly_chk").closest("span").addClass("checked"), $("#yearly_chk").prop("checked", !0), $("#monthly_chk").closest("span").removeClass("checked"), $("#monthly_chk").prop("checked", !1), $("#weekly_chk").closest("span").removeClass("checked"), $("#weekly_chk").prop("checked", !1), $("#daily_chk").closest("span").removeClass("checked"), $("#daily_chk").prop("checked", !1), $("#yearly_div").show(), $("#daily_div").hide(), $("#weekly_div").hide(), $("#monthly_div").hide(), $("#datepicker_end_by").datepicker({
                        startDate: "+1y",
                        format: JAVASCRIPT_DATE_FORMAT
                    }), "1" == e.task.general.yearly_radios ? ($("#yearly_radios1").closest("span").addClass("checked"), $("#yearly_radios1").prop("checked", !0), $("#Yearly_op1").val(e.task.general.Yearly_op1)) : "2" == e.task.general.yearly_radios ? ($("#yearly_radios2").closest("span").addClass("checked"), $("#yearly_radios2").prop("checked", !0), $("#Yearly_op2_1").val(e.task.general.Yearly_op2_1), $("#Yearly_op2_2").val(e.task.general.Yearly_op2_2)) : "3" == e.task.general.yearly_radios ? ($("#yearly_radios3").closest("span").addClass("checked"), $("#yearly_radios3").prop("checked", !0), $("#Yearly_op3_1").val(e.task.general.Yearly_op3_1), $("#Yearly_op3_2").val(e.task.general.Yearly_op3_2), $("#Yearly_op3_3").val(e.task.general.Yearly_op3_3)) : "4" == e.task.general.yearly_radios && ($("#yearly_radios4").closest("span").addClass("checked"), $("#yearly_radios4").prop("checked", !0), $("#Yearly_op4_1").val(e.task.general.Yearly_op4_1), $("#Yearly_op4_2").val(e.task.general.Yearly_op4_2)));
                    if ($("#yearly_radios1").is(":checked") && ($("#Yearly_op1").attr("disabled", !1), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)), $("#yearly_radios2").is(":checked") && ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !1), $("#Yearly_op2_2").attr("disabled", !1), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)), $("#yearly_radios3").is(":checked") && ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !1), $("#Yearly_op3_2").attr("disabled", !1), $("#Yearly_op3_3").attr("disabled", !1), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)), $("#yearly_radios4").is(":checked") && ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !1), $("#Yearly_op4_2").attr("disabled", !1)), "1" == e.task.general.no_end_date ? ($("#no_end_date1").closest("span").addClass("checked"), $("#no_end_date1").prop("checked", !0)) : "2" == e.task.general.no_end_date ? ($("#no_end_date2").closest("span").addClass("checked"), $("#no_end_date2").prop("checked", !0)) : "3" == e.task.general.no_end_date && ($("#no_end_date3").closest("span").addClass("checked"), $("#no_end_date3").prop("checked", !0)), $("#hdn_no_end_date").val(e.task.general.no_end_date), e.task.general.end_after_recurrence > 0 && $("#end_after_recurrence").val(e.task.general.end_after_recurrence), "0000-00-00" != e.task.general.end_by_date) {
                        var y = new Date(e.task.general.end_by_date),
                            v = DEFAULT_DATE_FOMAT;
                        if ("d M,Y" == v) {
                            var u = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                            y = a(y.getDate()) + " " + u[y.getMonth()] + ", " + y.getFullYear()
                        } else y = "d/m/Y" == v ? a(y.getDate()) + "/" + a(y.getMonth() + 1) + "/" + y.getFullYear() : "Y/m/d" == v ? y.getFullYear() + "/" + a(y.getMonth() + 1) + "/" + a(y.getDate()) : "d-m-Y" == v ? a(y.getDate()) + "-" + a(y.getMonth() + 1) + "-" + y.getFullYear() : y.getFullYear() + "-" + a(y.getMonth() + 1) + "-" + a(y.getDate())
                    } else var y = "";
                    $("#end_by_date").val(y), $("#datepicker_end_by").datepicker("update", y), $("#no_end_date1").is(":checked") && ($("#end_after_recurrence").attr("disabled", !0), $("#end_by_date").attr("disabled", !0))
                } else $("#one_off").closest("span").addClass("checked"), $("#one_off").prop("checked", !0), $("#recurrence_div").hide(), $("#recurrence").closest("span").removeClass("checked"), $("#recurrence").removeAttr("checked", "checked");
                $("#freq_task_id").val(e.task.general.task_id), $("#task_pre_due_date").val(e.task.general.task_due_date), $("#task_name").removeAttr("disabled", "disabled"), $("#search_date").removeAttr("disabled", "disabled")
            } else $("#one_off").closest("span").addClass("checked"), $("#one_off").prop("checked", !0), $("#recurrence").closest("span").removeClass("checked"), $("#recurrence").removeAttr("checked", "checked"), "1" == e.task.general.is_master_valid ? ($("#frquency_for_master_msg").show(), $("#frquency_disable").hide(), $("#frquency_normal").hide(), $("#task_name").removeAttr("disabled", "disabled"), $("#search_date").removeAttr("disabled", "disabled")) : ($("#frquency_for_master_msg").hide(), $("#frquency_disable").show(), $("#frquency_normal").hide(), $("#task_name").attr("disabled", "disabled"), $("#search_date").attr("disabled", "disabled"));
            if ("0000-00-00" != e.task.general.start_on_date) {
                var m = new Date(e.task.general.start_on_date),
                    v = DEFAULT_DATE_FOMAT;
                if ("d M,Y" == v) {
                    var u = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                    m = a(m.getDate()) + " " + u[m.getMonth()] + ", " + m.getFullYear()
                } else m = "d/m/Y" == v ? a(m.getDate()) + "/" + a(m.getMonth() + 1) + "/" + m.getFullYear() : "Y/m/d" == v ? m.getFullYear() + "/" + a(m.getMonth() + 1) + "/" + a(m.getDate()) : "d-m-Y" == v ? a(m.getDate()) + "-" + a(m.getMonth() + 1) + "-" + m.getFullYear() : m.getFullYear() + "-" + a(m.getMonth() + 1) + "-" + a(m.getDate())
            } else var m = t;
            if (m) $("#start_on_date").val(m), $("#start_on_date_picker").datepicker("update", m);
            else {
                var g = new Date((new Date).getTime() + 864e5),
                    b = g.getDate(),
                    f = g.getMonth(),
                    w = g.getFullYear(),
                    v = DEFAULT_DATE_FOMAT;
                if ("d M,Y" == v) {
                    var u = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                    date = a(b) + " " + u[f] + ", " + w
                } else "d/m/Y" == v ? date = a(b) + "/" + a(f + 1) + "/" + w : "Y/m/d" == v ? date = w + "/" + a(f + 1) + "/" + a(b) : "d-m-Y" == v ? date = a(b) + "-" + a(f + 1) + "-" + w : date = w + "-" + a(f + 1) + "-" + a(b);
                $("#start_on_date").val(date), $("#start_on_date_picker").datepicker("update", date)
            }
            $.each(e.users, function(i, item) {
                if(e.users[i].user_id == e.task.general.task_owner_id){
                    $("#task_owner_id_val").val(e.users[i].first_name + " " + e.users[i].last_name);
                 }
            });
        if(tab == 'task_tab_6'){
            $.ajax({
                type: "post",
                url: SIDE_URL + "task/ajax_files",
                data: {
                    task_id: task_id,
                    task_data : $("#task_data_"+task_id).val()
                },
                async: !1,
                success: function(z) {
                    $("#updated_files").html(z), $("#dvLoading").fadeOut("slow")
                }
            });
        }
        if(tab == 'task_tab_7'){
            $.ajax({
                type: "post",
                url: SIDE_URL + "task/ajax_comments",
                data: {
                    task_id: task_id
                },
                async: !1,
                success: function(b) {
                    $("#updated_task_comments").html(b), $("#comment_task_id").val(task_id)
                }
            });
        }
		
           activaTab(tab);
            "#" == $("#calender_team_user_id").val() && ($("#task_color_id").attr("disabled", !0), $("#task_swimlane_id").attr("disabled", !0)), "#" == $("#kanban_team_user_id").val() && ($("#task_color_id").attr("disabled", !0), $("#task_swimlane_id").attr("disabled", !0)), $("#full-width").modal("show"), $("#full-width").on("shown.bs.modal", function() {
                $("#task_title").focus();
                $('.tooltips').tooltip();
            })
        }
    })
}

function set_new_task_data(e) {
    var a = "",
        t = "";
    $.ajax({
        type: "post",
        url: SIDE_URL + "task/task_data",
        data: {
            task_id: e
        },
        success: function(e) {
            function s(e) {
                return 10 > e ? "0" + e : e
            }

            function s(e) {
                return 10 > e ? "0" + e : e
            }

            function s(e) {
                return 10 > e ? "0" + e : e
            }
            var e = jQuery.parseJSON(e);
            $("ul.wysihtml5-toolbar").remove(),$("iframe.wysihtml5-sandbox").remove(),$("input[name='_wysihtml5_mode']").remove();
            $("#series").modal("hide"), $(".alert").hide(), clear_form_elements("#frm_task_general"), clear_form_elements("#frm_search_dependency"), clear_form_elements("#frm_steps"), clear_form_elements("#frm_add_dependency"), clear_form_elements("#frm_add_allocation"), clear_form_elements("#frm_add_comment"), clear_form_elements("#frm_task_files"), $("#full-width").modal({
                backdrop: "static",
                keyboard: !1,
                show: !1
            }), $("#task_description,#is_personal,#task_priority,#task_category_id,#task_due_date,#task_sub_category_id,#locked_due_date,#task_color_id,#task_status_id,#task_time_estimate,#task_time_spent").attr("disabled", !1), $("#task_id").val(e.task.general.task_id), $("#old_task_id").val(e.task.general.task_id), $("#pre_task_id").val(e.task.general.task_id), $("#step_task_id").val(e.task.general.task_id), $("#files_task_id").val(e.task.general.task_id),$("#file_task_data").val($("#task_data_"+e.task.general.task_id).val()), $("#link_files_task_id").val(e.task.general.task_id), $("#link_file_task_data").val($("#task_data_"+e.task.general.task_id).val()),$("#comment_task_id").val(e.task.general.task_id), $("#freq_task_id").val(e.task.general.task_id), $("#search_task_id").val(e.task.general.task_id), $("#allocation_task_id").val(e.task.general.task_id), $("#from").val(from), $(".alert").hide(), $("#updated_dependencies").html('<tr><td colspan="6">No record available.</td></tr>'), $("#updated_steps").html('<tr><td colspan="3">No Record Available.</td></tr>'), $("#updated_files").html('<tr><td colspan="3">No Records available.</td></tr>'), $("#updated_task_comments").html(""), $("#scroll_history ul").html("<li>No Record Available.</li>"), $(".task-tab-pane").removeClass("active"), $("ul.task_navs li").removeClass("active"), $("ul.task_navs li:first").addClass("active"), $("#task_tab_1").addClass("active"), $("#task_title").focus(), $("#is_personal").prop("checked", !1), $("#is_personal").parent("span").removeAttr("class", "checked"), $("#task_title").val(e.task.general.task_title), $("#task_description").val(e.task.general.task_description), "1" == e.task.general.is_personal ? ($("#is_personal").prop("checked", !0), $("#is_personal").parent("span").attr("class", "checked")) : ($("#is_personal").prop("checked", !1), $("#is_personal").parent("span").removeAttr("class", "checked")), $("#hdn_is_personal").val(e.task.general.is_personal), $("#task_priority").val(e.task.general.task_priority), $("#hdn_task_priority").val(e.task.general.task_priority), "1" == e.task.general.locked_due_date ? ($("#locked_due_date").prop("checked", !0), $("#locked_due_date").parent("span").attr("class", "checked")) : ($("#locked_due_date").prop("checked", !1), $("#locked_due_date").parent("span").removeAttr("class", "checked")), $("#hdn_locked_due_date").val(e.task.general.locked_due_date), e.task.general.task_owner_id == LOG_USER_ID ? ($("#multiple_people_id").show(), $(".delete_task_btn").show()) : ($("#multiple_people_id").hide(), $(".delete_task_btn").hide()), $("#task_category_id").val(e.task.general.task_category_id), $("#task_sub_category_id").val(e.task.general.task_sub_category_id);
            var _ = e.task.general.user_task_due_date;
            $("#task_due_date").val(_), $("#old_task_due_date").val(_), $("#tmp_task_due_date").val(_), _ && $("#task_due_date_div").datepicker("update", _), $("#task_color_id").val(e.task.general.color_id), $("#task_staff_level_id").val(e.task.general.task_staff_level_id), $("#task_owner_id_val").val(e.task.general.first_name + " " + e.task.general.last_name), $("#task_owner_id").val(e.task.general.task_owner_id), $("#task_allocated_user_id").val(e.task.general.task_allocated_user_id), $("#task_allocated_user_id").trigger("chosen:updated"), $("#task_project_id").val(e.task.general.task_project_id), $("#general_project_id").val(e.task.general.task_project_id), $("#task_project_title").val(e.task.general.project_title), $("#task_section_name").val(e.task.general.section_name), $("#task_section_id").val(e.task.general.subsection_id), $("#task_subsection_id").val(e.task.general.subsection_id), $("#section_id").val(e.task.general.subsection_id), $("#task_time_spent_hour").val(e.task.general.task_time_spent_hour), $("#task_time_spent_min").val(e.task.general.task_time_spent_min), $("#old_task_time_spent_hour").val(e.task.general.task_time_spent_hour), $("#old_task_time_spent_min").val(e.task.general.task_time_spent_min), $("#task_time_spent").val(e.task.general.task_time_spent), $("#task_time_estimate_hour").val(e.task.general.task_time_estimate_hour), $("#task_time_estimate_min").val(e.task.general.task_time_estimate_min), $("#old_task_time_estimate_hour").val(e.task.general.task_time_estimate_hour), $("#old_task_time_estimate_min").val(e.task.general.task_time_estimate_min), $("#task_time_estimate").val(e.task.general.task_time_estimate), $("#task_status_id").val(e.task.general.task_status_id), $("#old_task_status_id").val(e.task.general.task_status_id), e.task.general.is_dependency_exist ? ($("#task_status_id").attr("disabled", "disabled"), $("#is_dependency_added").val("1")) : ($("#task_status_id").removeAttr("disabled", "disabled"), $("#is_dependency_added").val("0")), $("#master_task_id").val(e.task.general.master_task_id), $("#task_swimlane_id").val(e.task.general.swimlane_id), $("#kanban_order").val(e.task.general.kanban_order), $("#calender_order").val(e.task.general.calender_order), $("#task_scheduled_date").val(e.task.general.task_scheduled_date);
            var d = e.task.general.strtotime_scheduled_date;
            if ($("#strtotime_scheduled_date").val(d), $("#task_orig_scheduled_date").val(e.task.general.task_orig_scheduled_date), $("#task_orig_due_date").val(e.task.general.task_orig_due_date), e.task.general.task_division_id) {
                get_department_by_division(e.task.general.task_division_id, e.task.general.task_department_id);
                var l = e.task.general.task_division_id;
                $(".multiselect-container li span").find("input:checkbox").closest("span").removeClass("checked");
                for (var r in l) {
                    var i = l[r];
                    $("#task_division_id").find("option[value=" + i + "]").prop("selected", "selected"), $(".multiselect-container li span").find("input:checkbox[value=" + i + "]").closest("span").addClass("checked")
                }
                $("#task_division_id").multiselect("refresh")
            } else $(".multiselect-container li span").find("input:checkbox").closest("span").removeClass("checked"), $("#task_division_id").multiselect("refresh");
            if (e.task.general.task_department_id) {
                var n = e.task.general.task_department_id;
                for (var r in n) {
                    var o = n[r];
                    $("#task_department_id").find("option[value=" + o + "]").prop("selected", "selected"), $(".multiselect-container li span").find("input:checkbox[value=" + o + "]").closest("span").addClass("checked")
                }
                $("#task_department_id").multiselect("refresh")
            } else $("#task_department_id").multiselect("refresh");
            if (e.task.general.task_skill_id) {
                var c = e.task.general.task_skill_id;
                for (var r in c) {
                    var k = c[r];
                    $("#task_skill_id").find("option[value=" + k + "]").prop("selected", "selected"), $(".multiselect-container li span").find("input:checkbox[value=" + k + "]").closest("span").addClass("checked")
                }
                $("#task_skill_id").multiselect("refresh")
            } else $("#task_skill_id").multiselect("refresh");
            $("#task_allocated_user_id").val(e.task.general.task_allocated_user_id), $("#task_swimlane_id").val(e.task.general.swimlane_id), $("#genral_swimlane_id").val(e.task.general.swimlane_id), $("#is_multi_changed").val(0), e.is_multiallocation_task ? multiple_people_html(e.task.general.task_project_id) : (add_task_ajax(), $("#updated_users").show(), $("#updated_users_multiple").hide()), "from_project" == $("#redirect_page").val() && ($("#task_project_div").html('<div class="input-icon right"><i class="stripicon iconlink"></i><input readonly="readonly" class="m-wrap span11 valid" id="task_project_title" name="project_title" value="" type="text" placeholder="Link to Project" aria-invalid="false"></div><input type="hidden" name="task_project_id" value="' + e.task.general.task_project_id + '" />'), $("#section_div").html('<div class="input-icon right"><i class="stripicon iconlink"></i><input class="m-wrap span11 valid" name="task_section_name" id="task_section_name" readonly="readonly" value="" type="text" placeholder="Project section" aria-invalid="false"></div><input type="hidden" name="section_id" value="' + e.task.general.subsection_id + '" />'), $("#task_project_title").val(e.task.general.project_title), $("#task_section_name").val(e.task.general.section_name)), $("#task_allocated_user_id").val(e.task.general.task_allocated_user_id), e.task.general.task_allocated_user_id != LOG_USER_ID && $("#task_swimlane_id").attr("disabled", !0), e.task.general.task_owner_id != e.task.general.task_allocated_user_id && e.task.general.task_allocated_user_id == LOG_USER_ID && "1" == e.task.general.locked_due_date && ($("#locked_due_date").attr("disabled", !0), $("#task_due_date").attr("disabled", !0), $("#task_due_date_div").attr("disabled", !0), $("#task_due_date_div").css("pointer-events", "none"), $("#hdn_task_due_date").val(e.task.general.task_due_date)), "0" != e.task.general.multi_allocation_task_id && e.task.general.task_owner_id != e.task.general.task_allocated_user_id ? ($("#task_due_date").attr("disabled", !0), $("#task_due_date_div").attr("disabled", !0), $("#task_due_date_div").css("pointer-events", "none"), $("#hdn_task_due_date").val(e.task.general.task_due_date), $("#task_category_id").attr("disabled", !0), $("#task_sub_category_id").attr("disabled", !0), $("#task_title").attr("disabled", !0), $("#task_description").attr("disabled", !0), $("#multiple_people_id").hide()) : ($("#task_due_date").attr("disabled", !1), $("#task_due_date_div").attr("disabled", !1), $("#task_due_date_div").css("pointer-events", ""), $("#task_category_id").attr("disabled", !1), $("#task_sub_category_id").attr("disabled", !1), $("#task_title").attr("disabled", !1), $("#task_description").attr("disabled", !1)), $("#task_allocated_user_id").trigger("chosen:updated"), $.map(e.task.dependencies, function(t) {
                function s(e) {
                    return 10 > e ? "0" + e : e
                }
                var _ = "";
                if ("0000-00-00" != t.task_due_date) {
                    _ = t.task_due_date;
                    var _ = new Date(t.task_due_date),
                        d = DEFAULT_DATE_FOMAT;
                    if ("d M,Y" == d) {
                        var l = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                        _ = s(_.getDate()) + " " + l[_.getMonth()] + ", " + _.getFullYear()
                    } else _ = "d/m/Y" == d ? s(_.getDate()) + "/" + s(_.getMonth() + 1) + "/" + _.getFullYear() : "Y/m/d" == d ? _.getFullYear() + "/" + s(_.getMonth() + 1) + "/" + s(_.getDate()) : "d-m-Y" == d ? s(_.getDate()) + "-" + s(_.getMonth() + 1) + "-" + _.getFullYear() : _.getFullYear() + "-" + s(_.getMonth() + 1) + "-" + s(_.getDate())
                }
                a += "<tr>", a += "<td onclick=\"set_new_task_data('" + t.task_id + "','" + e.task.general.task_project_id + "')\">" + t.task_id + "</td>", a += "<td onclick=\"set_new_task_data('" + t.task_id + "','" + e.task.general.task_project_id + "')\">" + t.task_title + "</td>", a += "<td onclick=\"set_new_task_data('" + t.task_id + "','" + e.task.general.task_project_id + "')\">" + t.first_name + " " + t.last_name + "</td>", a += "<td onclick=\"set_new_task_data('" + t.task_id + "','" + e.task.general.task_project_id + "')\">" + _ + "</td>", a += '<td><span class="label label-' + t.task_status_name.replace(/\s/g, "").toLowerCase() + '">' + t.task_status_name + "</span></td>", a += "<td><a href='javascript://' onclick='remove_task_dependency(\"" + t.task_id + "\");' id='remove_task_dependency_"+ a.task_id +"' class='tooltips' data-placement='top' data-original-title='Click to Un-link the task' ><i class='icon-unlink'></i></a>", t.task_owner_id == LOG_USER_ID && (a += '<a href="javascript://" onclick="delete_dependent_task(\'' + t.task_id + '\');" id="delete_dependent_task_'+ t.task_id +'" class="tooltips" data-placement="top" data-original-title="Click to Delete the dependency task"> <i class="icon-trash stngicn"></i> </a>'), a += "</td>", a += "</tr>"
            }),$('.tooltips').tooltip(), $("#dependent_project_id").val(e.task.general.task_project_id), $("#updated_dependencies").html(a), $("#main_task_due_date").val(e.task.general.task_due_date);
            var r = 0;
            if ($.map(e.task.steps, function(e) {
                    var a = "";
                    "1" == e.is_completed && (a = "checked='checked'"), t += "<tr>", t += '<script type="text/javascript">$(document).ready(function(){$("#step_title_' + e.task_step_id + '").editable({url: "' + SIDE_URL + 'task/update_steps",type: "post",pk: 1,mode: "inline",showbuttons: true,validate: function (value) {if ($.trim(value) == \'\'){ return "This field is required"; };},success : function(responseData){$("#updated_steps").html(responseData);} });});', t += "</script>", t += '<td><label class="checkbox"><input type="checkbox" name="is_completed[]" id="' + e.task_step_id + '" value="1" ' + a + " /></label></td>", t += '<td><a href="javascript:void(0)" class="txt-style" id="step_title_' + e.task_step_id + '" data-type="text" data-pk="1" data-original-title="' + e.step_title + '">' + e.step_title + "</a></td>", t += "<td>", e.step_added_by == LOG_USER_ID && (t += '<a href="javascript:;" onclick="delete_step(\'' + e.task_step_id + '\')" id="delete_step_'+ e.task_step_id +'" > <i class="icon-trash taskppstp"></i> </a>'), t += '<a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a><a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a></td>', t += '<input type="hidden" name="step_title[]" value="' + e.step_title + '" />', t += '<input type="hidden" name="seq[]" value="' + e.step_sequence + '" />', t += '<input type="hidden" name="ids[]" value="' + e.task_step_id + '" />', t += '<input type="hidden" name="added_by[]" value="' + e.step_added_by + '" />', t += "</tr>", r++
                }), t && (t += '<input type="hidden" name="total" id="total" value="' + r + '" />'), $("#total").val(r), $("#updated_steps").html(t), App.initUniform(), $(".up,.down").click(function() {
                    var e = $(this).parents("tr:first");
                    $(this).is(".up") ? (e.insertBefore(e.prev()), $("#frm_steps").submit()) : (e.insertAfter(e.next()), $("#frm_steps").submit())
                }), $("input[name='is_completed[]']").click(function() {
                    $("#frm_steps").submit()
                }), "0" == e.task.general.master_task_id) {
                if ($("#frquency_for_master_msg").hide(), $("#frquency_disable").hide(), $("#frquency_normal").show(), "recurrence" == e.task.general.frequency_type) {
                    if ($("#one_off").closest("span").removeClass("checked"), $("#one_off").prop("checked", !1), $("#recurrence").closest("span").addClass("checked"), $("#recurrence").prop("checked", !0), $("#recurrence_div").show(), "1" == e.task.general.recurrence_type) $("#daily_chk").closest("span").addClass("checked"), $("#daily_chk").prop("checked", !0), $("#weekly_chk").closest("span").removeClass("checked"), $("#weekly_chk").prop("checked", !1), $("#monthly_chk").closest("span").removeClass("checked"), $("#monthly_chk").prop("checked", !1), $("#yearly_chk").closest("span").removeClass("checked"), $("#yearly_chk").prop("checked", !1), $("#daily_div").show(), $("#weekly_div").hide(), $("#monthly_div").hide(), $("#yearly_div").hide(), $("#datepicker_end_by").datepicker({
                        startDate: "+1d",
                        format: JAVASCRIPT_DATE_FORMAT
                    }), "1" == e.task.general.Daily_every_weekday ? ($("#Daily_every_weekday2").closest("span").addClass("checked"), $("#Daily_every_weekday2").prop("checked", !0), $("#Daily_every_week_day").val(e.task.general.Daily_every_week_day), $("#Daily_every_weekday").closest("span").removeClass("checked"), $("#Daily_every_weekday").prop("checked", !1)) : ($("#Daily_every_weekday").closest("span").addClass("checked"), $("#Daily_every_weekday").prop("checked", !0), $("#Daily_every_weekday2").closest("span").removeClass("checked"), $("#Daily_every_weekday2").removeAttr("checked", "checked"), $("#Daily_every_day").val(e.task.general.Daily_every_day)), $("#Daily_every_weekday").is(":checked") && $("#Daily_every_day").attr("disabled", !1), $("#Daily_every_weekday2").is(":checked") && $("#Daily_every_day").attr("disabled", !0);
                    else if ("2" == e.task.general.recurrence_type) {
                        $("#weekly_chk").closest("span").addClass("checked"), $("#weekly_chk").prop("checked", !0), $("#daily_chk").closest("span").removeClass("checked"), $("#daily_chk").prop("checked", !1), $("#monthly_chk").closest("span").removeClass("checked"), $("#monthly_chk").prop("checked", !1), $("#yearly_chk").closest("span").removeClass("checked"), $("#yearly_chk").prop("checked", !1), $("#weekly_div").show(), $("#daily_div").hide(), $("#monthly_div").hide(), $("#yearly_div").hide(), $("#datepicker_end_by").datepicker({
                            startDate: "+7d",
                            format: JAVASCRIPT_DATE_FORMAT
                        }), $("#Weekly_every_week_no").val(e.task.general.Weekly_every_week_no);
                        var p = e.task.general.Weekly_week_day;
                        var array = p.split(',');
                        $("input[name='Weekly_week_day[]']:checkbox").prop('checked',!1);
                        $.each(array, function( index, value ) { 
                            $("#weekly_week_day_"+value).attr('checked','checked')
                        });
                    } else "3" == e.task.general.recurrence_type ? ($("#monthly_chk").closest("span").addClass("checked"), $("#monthly_chk").prop("checked", !0), $("#weekly_chk").closest("span").removeClass("checked"), $("#weekly_chk").prop("checked", !1), $("#daily_chk").closest("span").removeClass("checked"), $("#daily_chk").prop("checked", !1), $("#yearly_chk").closest("span").removeClass("checked"), $("#yearly_chk").prop("checked", !1), $("#monthly_div").show(), $("#daily_div").hide(), $("#weekly_div").hide(), $("#yearly_div").hide(), $("#datepicker_end_by").datepicker({
                        startDate: "+1m",
                        format: JAVASCRIPT_DATE_FORMAT
                    }), "1" == e.task.general.monthly_radios ? ($("#monthly_radios1").closest("span").addClass("checked"), $("#monthly_radios1").prop("checked", !0), $("#Monthly_op1_1").val(e.task.general.Monthly_op1_1), $("#Monthly_op1_2").val(e.task.general.Monthly_op1_2)) : "2" == e.task.general.monthly_radios ? ($("#monthly_radios2").closest("span").addClass("checked"), $("#monthly_radios2").prop("checked", !0), $("#Monthly_op2_1").val(e.task.general.Monthly_op2_1), $("#Monthly_op2_2").val(e.task.general.Monthly_op2_2), $("#Monthly_op2_3").val(e.task.general.Monthly_op2_3)) : "3" == e.task.general.monthly_radios && ($("#monthly_radios3").closest("span").addClass("checked"), $("#monthly_radios3").prop("checked", !0), $("#Monthly_op3_1").val(e.task.general.Monthly_op3_1), $("#Monthly_op3_2").val(e.task.general.Monthly_op3_2)), $("#monthly_radios1").is(":checked") && ($("#Monthly_op1_1").attr("disabled", !1), $("#Monthly_op1_2").attr("disabled", !1), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0)), $("#monthly_radios2").is(":checked") && ($("#Monthly_op1_1").attr("disabled", !0), $("#Monthly_op1_2").attr("disabled", !0), $("#Monthly_op2_1").attr("disabled", !1), $("#Monthly_op2_2").attr("disabled", !1), $("#Monthly_op2_3").attr("disabled", !1), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0)), $("#monthly_radios3").is(":checked") && ($("#Monthly_op1_1").attr("disabled", !0), $("#Monthly_op1_2").attr("disabled", !0), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !1), $("#Monthly_op3_2").attr("disabled", !1))) : "4" == e.task.general.recurrence_type && ($("#yearly_chk").closest("span").addClass("checked"), $("#yearly_chk").prop("checked", !0), $("#monthly_chk").closest("span").removeClass("checked"), $("#monthly_chk").prop("checked", !1), $("#weekly_chk").closest("span").removeClass("checked"), $("#weekly_chk").prop("checked", !1), $("#daily_chk").closest("span").removeClass("checked"), $("#daily_chk").prop("checked", !1), $("#yearly_div").show(), $("#daily_div").hide(), $("#weekly_div").hide(), $("#monthly_div").hide(), $("#datepicker_end_by").datepicker({
                        startDate: "+1y",
                        format: JAVASCRIPT_DATE_FORMAT
                    }), "1" == e.task.general.yearly_radios ? ($("#yearly_radios1").closest("span").addClass("checked"), $("#yearly_radios1").prop("checked", !0), $("#Yearly_op1").val(e.task.general.Yearly_op1)) : "2" == e.task.general.yearly_radios ? ($("#yearly_radios2").closest("span").addClass("checked"), $("#yearly_radios2").prop("checked", !0), $("#Yearly_op2_1").val(e.task.general.Yearly_op2_1), $("#Yearly_op2_2").val(e.task.general.Yearly_op2_2)) : "3" == e.task.general.yearly_radios ? ($("#yearly_radios3").closest("span").addClass("checked"), $("#yearly_radios3").prop("checked", !0), $("#Yearly_op3_1").val(e.task.general.Yearly_op3_1), $("#Yearly_op3_2").val(e.task.general.Yearly_op3_2), $("#Yearly_op3_3").val(e.task.general.Yearly_op3_3)) : "4" == e.task.general.yearly_radios && ($("#yearly_radios4").closest("span").addClass("checked"), $("#yearly_radios4").prop("checked", !0), $("#Yearly_op4_1").val(e.task.general.Yearly_op4_1), $("#Yearly_op4_2").val(e.task.general.Yearly_op4_2)));
                    if ($("#yearly_radios1").is(":checked") && ($("#Yearly_op1").attr("disabled", !1), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)), $("#yearly_radios2").is(":checked") && ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !1), $("#Yearly_op2_2").attr("disabled", !1), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)), $("#yearly_radios3").is(":checked") && ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !1), $("#Yearly_op3_2").attr("disabled", !1), $("#Yearly_op3_3").attr("disabled", !1), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)), $("#yearly_radios4").is(":checked") && ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !1), $("#Yearly_op4_2").attr("disabled", !1)), "1" == e.task.general.no_end_date ? ($("#no_end_date1").closest("span").addClass("checked"), $("#no_end_date1").prop("checked", !0)) : "2" == e.task.general.no_end_date ? ($("#no_end_date2").closest("span").addClass("checked"), $("#no_end_date2").prop("checked", !0)) : "3" == e.task.general.no_end_date && ($("#no_end_date3").closest("span").addClass("checked"), $("#no_end_date3").prop("checked", !0)), $("#hdn_no_end_date").val(e.task.general.no_end_date), e.task.general.end_after_recurrence > 0 && $("#end_after_recurrence").val(e.task.general.end_after_recurrence), "0000-00-00" != e.task.general.end_by_date) {
                        var h = new Date(e.task.general.end_by_date),
                            y = DEFAULT_DATE_FOMAT;
                        if ("d M,Y" == y) {
                            var v = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                            h = s(h.getDate()) + " " + v[h.getMonth()] + ", " + h.getFullYear()
                        } else h = "d/m/Y" == y ? s(h.getDate()) + "/" + s(h.getMonth() + 1) + "/" + h.getFullYear() : "Y/m/d" == y ? h.getFullYear() + "/" + s(h.getMonth() + 1) + "/" + s(h.getDate()) : "d-m-Y" == y ? s(h.getDate()) + "-" + s(h.getMonth() + 1) + "-" + h.getFullYear() : h.getFullYear() + "-" + s(h.getMonth() + 1) + "-" + s(h.getDate())
                    } else var h = "";
                    $("#end_by_date").val(h), $("#datepicker_end_by").datepicker("update", h), $("#no_end_date1").is(":checked") && ($("#end_after_recurrence").attr("disabled", !0), $("#end_by_date").attr("disabled", !0))
                } else $("#one_off").closest("span").addClass("checked"), $("#one_off").prop("checked", !0), $("#recurrence_div").hide(), $("#recurrence").closest("span").removeClass("checked"), $("#recurrence").removeAttr("checked", "checked");
                $("#freq_task_id").val(e.task.general.task_id), $("#task_pre_due_date").val(e.task.general.task_due_date), $("#task_name").removeAttr("disabled", "disabled"), $("#search_date").removeAttr("disabled", "disabled")
            } else $("#one_off").closest("span").addClass("checked"), $("#one_off").prop("checked", !0), $("#recurrence").closest("span").removeClass("checked"), $("#recurrence").removeAttr("checked", "checked"), "1" == e.task.general.is_master_valid ? ($("#frquency_for_master_msg").show(), $("#frquency_disable").hide(), $("#frquency_normal").hide(), $("#task_name").removeAttr("disabled", "disabled"), $("#search_date").removeAttr("disabled", "disabled")) : ($("#frquency_for_master_msg").hide(), $("#frquency_disable").show(), $("#frquency_normal").hide(), $("#task_name").attr("disabled", "disabled"), $("#search_date").attr("disabled", "disabled"));
            if ("0000-00-00" != e.task.general.start_on_date) {
                var u = new Date(e.task.general.start_on_date),
                    y = DEFAULT_DATE_FOMAT;
                if ("d M,Y" == y) {
                    var v = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                    u = s(u.getDate()) + " " + v[u.getMonth()] + ", " + u.getFullYear()
                } else u = "d/m/Y" == y ? s(u.getDate()) + "/" + s(u.getMonth() + 1) + "/" + u.getFullYear() : "Y/m/d" == y ? u.getFullYear() + "/" + s(u.getMonth() + 1) + "/" + s(u.getDate()) : "d-m-Y" == y ? s(u.getDate()) + "-" + s(u.getMonth() + 1) + "-" + u.getFullYear() : u.getFullYear() + "-" + s(u.getMonth() + 1) + "-" + s(u.getDate())
            } else var u = _;
            if (u) $("#start_on_date").val(u), $("#start_on_date_picker").datepicker("update", u);
            else {
                var m = new Date((new Date).getTime() + 864e5),
                    g = m.getDate(),
                    b = m.getMonth(),
                    f = m.getFullYear(),
                    y = DEFAULT_DATE_FOMAT;
                if ("d M,Y" == y) {
                    var v = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                    date = s(g) + " " + v[b] + ", " + f
                } else "d/m/Y" == y ? date = s(g) + "/" + s(b + 1) + "/" + f : "Y/m/d" == y ? date = f + "/" + s(b + 1) + "/" + s(g) : "d-m-Y" == y ? date = s(g) + "-" + s(b + 1) + "-" + f : date = f + "-" + s(b + 1) + "-" + s(g);
                $("#start_on_date").val(date), $("#start_on_date_picker").datepicker("update", date)
            }
            $("#full-width").modal("show"), $("#full-width").on("shown.bs.modal", function() {
                $("#task_title").focus()
            })
        },
        error: function(e) {
            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
        }
    })
}

function clear_form_elements(e) {
    $(e).find(":input").each(function() {
        switch (this.type) {
            case "password":
            case "select-multiple":
            case "select-one":
            case "text":
            case "textarea":
                $(this).val("");
                break;
            case "checkbox":
            case "radio":
                this.checked = !1
        }
    })
}

function open_seris(e, a, t, s, tab) {
    return !$(e).parent("div").hasClass("after_timer_on") && !$(e).hasClass("after_timer_on") && ($("#series_task span").removeClass("checked"), $("#ocuurence_task span").removeClass("checked"), $("#series_task").attr("onclick", "edit_task(this,'" + t + "','series', '"+tab+"')"), $("#ocuurence_task").attr("onclick", "edit_task(this,'" + a + "','" + s + "','"+tab+"')"), void $("#series").modal("show"))
}
$(function() {
    $("#full-width").modal({
        backdrop: "static",
        keyboard: !1,
        show: !1
    })
});


function expand_project_data(id){
    if(id=='1'){
         var col = $("#collapse1").val();
         if(col == '1'){
             $("#expand_1").html(' <i class="icon-chevron-down" style="color: #fff !important;"></i>');
             $("#collapse1").val('0');
         }else{
             $("#expand_1").html(' <i class="icon-chevron-right" style="color: #fff !important;"></i>');
             $("#collapse1").val('1');
         }
         $("#project_info_collapse").toggle();
    }else if(id == '2'){
         var col = $("#collapse2").val();
         if(col == '1'){
             $("#expand_2").html(' <i class="icon-chevron-down" style="color: #fff !important;"></i>');
             $("#collapse2").val('0');
         }else{
             $("#expand_2").html(' <i class="icon-chevron-right" style="color: #fff !important;"></i>');
             $("#collapse2").val('1');
         }
         $("#project_team_collapse").toggle();
       
    }else if(id == '3'){
         var col = $("#collapse3").val();
         if(col == '1'){
             $("#expand_3").html(' <i class="icon-chevron-down" style="color: #fff !important;"></i>');
             $("#collapse3").val('0');
         }else{
             $("#expand_3").html(' <i class="icon-chevron-right" style="color: #fff !important;"></i>');
             $("#collapse3").val('1');
         }
         $("#project_history").toggle();
    }else if(id == '4'){
         var col = $("#collapse4").val();
         if(col == '1'){
             $("#expand_4").html(' <i class="icon-chevron-down" style="color: #fff !important;"></i>');
             $("#collapse4").val('0');
         }else{
             $("#expand_4").html(' <i class="icon-chevron-right" style="color: #fff !important;"></i>');
             $("#collapse4").val('1');
         }
         $("#project_comment_div").toggle();
    }else if(id == '5'){
        var col = $("#collapse5").val();
         if(col == '1'){
             $("#expand_5").html(' <i class="icon-chevron-down" style="color: #fff !important;"></i>');
             $("#collapse5").val('0');
         }else{
             $("#expand_5").html(' <i class="icon-chevron-right" style="color: #fff !important;"></i>');
             $("#collapse5").val('1');
         }
         $("#project_file_div").toggle();
    }else{
        var col = $("#collapse6").val();
         if(col == '1'){
             $("#expand_6").html(' <i class="icon-chevron-down" style="color: #fff !important;"></i>');
             $("#collapse6").val('0');
         }else{
             $("#expand_6").html(' <i class="icon-chevron-right" style="color: #fff !important;"></i>');
             $("#collapse6").val('1');
         }
         $("#project_finance_info").toggle();
    }
   
}


function change_view_capacity(e) { 
    $("#dvLoading").fadeIn("slow");
    var t =$('#change_mode').prop('checked')?'graph':'number';
    $.ajax({
        type: "POST",
        url: SIDE_URL + "user/ajax_capacity_dashboard",
        data: {
            mydate: e,
            graph_type:t,
            user_filter:$('#filter_value').val(),
            select_user:$('#select_user').val()
        },
        success: function(a) {
            $("#capacity_board").html(a);
            $("#dvLoading").fadeOut("slow");
        }
    })
}


$(document).ready(function(){
    $(".page-background").parentsUntil("html").addClass("bg_body");
    $(document).on("click",document,function() { 
        $("ul.dropdown-context").css("display","none");
    });
});
$(document).ready(function(){
   $("#filter_name").on("keypress", function(e) {
        if(13 === e.keyCode){
            save_filter();
        }
    })
});
function save_filter(){
    var form = $("#serach_data").serializeArray();
    var filter_name = $("#filter_name").val();
    if(filter_name == ''){
        alertify.alert("Please enter filter name.");
        $("#filter_name").focus();
        return false;
    }else{
        $("#dvLoading").fadeIn("slow");
        $.ajax({
            type: "POST",
            url: SIDE_URL + "task/save_user_filters",
            data: {
                filter_data:form,
                filter_name:filter_name
            },
            success: function(a) {
                $("li").removeClass("bold");
                $("li").removeClass("text-underline");
                var html = '';
                    html +='<li class="padding8 bold text-underline">';
                    html +='<a href="javascript:void(0);"  style="color: black !important;" onclick="apply_filter('+a+');">'+filter_name+'</a>';
                    html +='<input type="hidden" name="hidden_filter_name_'+a+'" id="hidden_filter_name_'+a+'" value="'+a+'"/>';
                    html +='</li>';  
                    $("#append_filter").append(html);
                    $("#chnage_filter").html('<strong>'+filter_name+'</strong>');
                    $("#save_filter").modal("hide");
                    alertify.set('notifier','position', 'top-right');
                    alertify.success("Filter has been saved successfully.");
                    var updatewidth = $("#chnage_filter").width()+8+"px";
                    $(".seach-dropdoen").css("margin-left",updatewidth);
                    $("#dvLoading").fadeOut("slow");
            },
            error:function(a){
                $("#dvLoading").fadeOut("slow");
            }
        });
    }
}
 
$(document).ready(function(){
        $.validator.addMethod("EmailExist", function(value, element) {
                    var remote =  $.ajax({
              		url: SIDE_URL+"settings/is_company_user_exists",
			type: "post",
			async : false,
			data: {
                            value: value,
                            user_id:$("#customer_user_id").val()
			},
			success : function(responseData){
                           return responseData;
			}
                    });
                    if(remote.responseText == '1'){
                        return false;
                    }else{
                        return true;
                    }
            },"There is an existing company user email address.");	 
        $("#customer_users_data").validate({ 
            errorElement: "span",
            errorClass: "help-inline",
            focusInvalid: false,
            ignore: "",
            rules: {
                customer_user_first: {
                    required: true,
                    maxlength:25
                },
                customer_user_last:{
                    required:true,
                    maxlength:25
                },
                customer_user_mail:{
                    required:true,
                    email:true,
                    EmailExist:true
                },
                parent_customer:{
                    required:true
                }
            },
            message:{
                customer_user_mail:{
                    required:"This field is required",
                    email:"Please enter a valid email address"
                }
            },
            submitHandler: function(data){ 
                  $("#dvLoading").fadeIn("slow");
                  var access_page = $("#access_page").val();
                  $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/add_customer_user_info",
                    data: {
                        info:$("#customer_users_data").serialize()
                    },
                    success: function(e) {
                        var data = jQuery.parseJSON(e);
                        var view = '';
                        function b(a) {
                          return a.substr(0, 1).toUpperCase() + a.substr(1);
                        }
                        if(access_page == 'Admin'){
                            view +='<tr id="customerUser_'+data.customer_info.user_id+'">';
                            view +='<td>'+data.customer_info.first_name+'</td>';
                            view +='<td>'+data.customer_info.last_name+'</td>';
                            view +='<td>'+data.customer_info.email+'</td>';
                            view +='<td>'+data.customer_info.customer_name+'</td>';
                            view +='<td>'+data.customer_info.user_status+'</td>';
                            if(data.customer_info.user_status != 'Pending'){
                             view +='<td>'+data.date+'</td>';
                            }else{
                             view +='<td>-</td>';   
                            }
                            view +='<td>';
                            view +='<a href="javascript:void(0);" onclick="edit_customer_user('+data.customer_info.user_id+');"> <i class="icon-pencil stngicn company_icon_black"></i> </a> ';
                            view +='<a href="javascript:void(0);" onclick="delete_customer_user('+data.customer_info.user_id+');" id="delete_customer_user_'+data.customer_info.user_id+'"> <i class="icon-trash stngicn company_icon_black"></i> </a> ';
                            view +='</td>';
                            if($("#empty_table").length == 1){
                                $("#empty_table").remove();
                            }
                            
                            if($("#customerUser_"+data.customer_info.user_id).length == 1){
                                $("#customerUser_"+data.customer_info.user_id).replaceWith(view);
                            }else{
                                $("#CustomerUsr").append(view);
                            }
                            $("#customerUsermodal").modal('hide'); 
                        }
                        if(access_page == 'Customer'){
                            view +='<li class="customer-user_li" id="customer_user_'+data.customer_info.user_id+'">';
                            view +='<div class="people-block">';
                            view +='<div class="people-img">';
                            view +='<img src="'+data.image_url+'upload/user/no_image.jpg" class="img-polaroid img-circle" >';
                            view +='<a onclick="removeCustomerUser('+data.customer_info.user_id+');" href="javascript:void(0)" >';
                            view +='<i class="stripicon iconredcolse"></i></a>';
                            view +='<p>'+b(data.customer_info.first_name)+' '+b(data.customer_info.last_name)+'</p>';
                            view +='</div>';
                            view +='</div>';
                            view +='</li>';
                            $("#add_new_customer_user").append(view);
                            $("#AddCustomerUsers").modal('hide'); 
                        }
                       $("#dvLoading").fadeOut("slow");
                    },
                    error:function(e){
                        $("#dvLoading").fadeOut("slow");
                    }
                });
            }
        });
});

function search_data_excel(){
    $("#dvLoading").fadeIn("slow");
    var form = $("#serach_data").serializeArray();
        window.open(
                SIDE_URL +'task/search_data_excel?filter='+JSON.stringify(form),
                    '_blank' // <- This is what makes it open in a new window.
              );
    $("#dvLoading").fadeOut("slow");
}