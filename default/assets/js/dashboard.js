function load_overdue_tasks() {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/todo_Ajax",
        data: {
            type: "",
            duration: "overdue"
        },
        success: function(t) {
            $("#dashboard_filter_task_priority").val(""), $("#dashboard_filter_duration").val("overdue"), $("#filtertab1_in").html(t), $("#dashboard_priority").val($("#dashboard_filter_task_priority").val()), $("#dashboard_duration").val($("#dashboard_filter_duration").val()), $("#filtertab1").dataTable({
                order: [
                    [1, "asc"]
                ],
                paging: !1,
                bFilter: !1,
                bLengthChange: !1,
                info: !1,
                language: {
                    emptyTable: "No Records found."
                }
            }), $("#dvLoading").fadeOut("slow")
        },
        error: function(t) {
            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
        }
    })
}

function load_backlog_tasks() {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/todo_Ajax",
        data: {
            type: "",
            duration: "backlog"
        },
        success: function(t) {
            $("#dashboard_filter_task_priority").val(""), $("#dashboard_filter_duration").val("backlog"), $("#filtertab1_in").html(t), $("#dashboard_priority").val($("#dashboard_filter_task_priority").val()), $("#dashboard_duration").val($("#dashboard_filter_duration").val()), $("#filtertab1").dataTable({
                order: [
                    [1, "asc"]
                ],
                paging: !1,
                bFilter: !1,
                bLengthChange: !1,
                info: !1,
                language: {
                    emptyTable: "No Records found."
                }
            }), $("#dvLoading").fadeOut("slow")
        },
        error: function(t) {
            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
        }
    })
}

function getNextweek() {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/task_nextweek",
        data: {
            user_id: LOG_USER_ID
        },
        success: function(t) {
            $("#sortableItem_3").html(t), $("#dvLoading").fadeOut("slow")
        },
        error: function(t) {
            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
        }
    })
}

function getPreviousweek() {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/task_previousweek",
        data: {
            user_id: LOG_USER_ID
        },
        success: function(t) {
            $("#sortableItem_3").html(t), $("#dvLoading").fadeOut("slow")
        },
        error: function(t) {
            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
        }
    })
}

function delwatch(t, a) {
    $.ajax({
        type: "post",
        url: SIDE_URL + "user/delwatch",
        data: {
            id: t,
            task_id: a
        },
        success: function(t) {
            t > 0 && $("#watch" + a).hide("slow")
        },
        error: function(t) {
            console.log("Ajax request not recieved!")
        }
    })
}

function updateSchedulledDate(t, a) {
    function o(t) {
        return 10 > t ? "0" + t : t
    }
    var s = t,
        e = [o(s.getDate()), o(s.getMonth() + 1), s.getFullYear()].join("-");
    a = a.replace("schedulled_", ""), $.ajax({
        type: "post",
        url: SIDE_URL + "task/updateSchedulledDate",
        async: !1,
        data: {
            date: e,
            task_id: a,
            post_data: $("#task_data_" + a).val(),
            redirect_page: $("#redirect_page").val(),
            type: $("#dashboard_priority").val(),
            duration: $("#dashboard_duration").val()
        },
        success: function(t) {
            function o(t) {
                return t.substr(0, 1).toUpperCase() + t.substr(1)
            }
            if (t = jQuery.parseJSON(t), $("#watch" + a).length) {
                var s = t.task_status_name;
                status_class = s.toLowerCase(), status_class = status_class.replace(" ", "");
                var e = "";
                e += '<tr id="watch' + t.task_data.task_id + '" role="row" class="odd">', e += '<td title="' + t.task_data.task_description + '" class="sorting_1">', e += "1" == t.is_master_deleted || "0" == t.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips" onclick="edit_task(this,\'' + t.task_data.task_id + "','" + t.is_chk + '\')" href="javascript:void(0)">' + o(t.task_data.task_title) + "</a>" : '<a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips" onclick="open_seris(this,\'' + t.task_data.task_id + "','" + t.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + o(t.task_data.task_title) + "</a></td>", e += "</td>", e += '<td><span class="hidden">' + t.task_data.task_due_date + "</span>" + t.user_due_date + "</td>", e += '<td class="hidden-480">' + t.task_allocated_user_name + ".</td>", e += '<td><span class="label label-sm label-' + status_class + '">' + s + "</span></td>", e += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\'' + t.watch_id + "','" + t.task_data.task_id + '\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>', $("#watch" + a).replaceWith(e)
            }
            if ($("#last_login_" + a).length) {
                var d = "";
                d += '<tr id="last_login_' + t.task_data.task_id + '" role="row" class="odd">', d += "1" == t.task_data.is_master_deleted || "0" == t.task_data.master_task_id ? '<td class="sorting_1"><a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips dashboard_master_' + t.task_data.master_task_id + '" onclick="edit_task(this,\'' + t.task_data.task_id + "','" + t.is_chk + '\');" href="javascript:void(0)">' + o(t.task_data.task_title) + "</a>" : '<td class="sorting_1"><a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips dashboard_master_' + t.task_data.master_task_id + '" onclick="open_seris(this,\'' + t.task_data.task_id + "','" + t.task_data.master_task_id + "','" + t.is_chk + '\');" href="javascript:void(0)">' + o(t.task_data.task_title) + "</a>", d += '</td><td><span class="hidden">' + t.task_data.task_due_date + "</span>" + t.user_due_date + "</td>", d += "<td>" + t.task_data.task_priority + "</td>", d += "</tr>", $("#last_login_" + a).replaceWith(d)
            }
            if ("assign_other" == t.assign_status) $("#todo_" + $("#task_id").val()).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#last_login_" + $("#task_id").val()).remove(), $("#teamoverdue_" + $("#task_id").val()).remove(), $("#teampending_" + $("#task_id").val()).remove(), $("#teamtodo_" + $("#task_id").val()).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
            else if (1 == t.is_div_valid) {
                var _ = t.task_data.task_title;
                if (_ > 40) var i = _.substring(0, 37) + "...";
                else var i = _;
                var s = t.task_status_name;
                status_class = s.toLowerCase(), status_class = status_class.replace(" ", "");
                var u = "";
                u += '<tr id="todo_' + t.task_data.task_id + '" role="row" class="even">', u += '<td title="' + t.task_data.task_description + '" class="sorting_1">', u += "1" == t.task_data.is_master_deleted || "0" == t.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + _ + '" class="tooltips" data-dismiss="modal" onclick="edit_task(this,\'' + t.task_data.task_id + "','" + t.is_chk + '\');" href="javascript:void(0)" >' + o(i) + "</a></td>" : '<a  data-placement="right" data-original-title="' + _ + '" class="tooltips" onclick="open_seris(this,\'' + t.task_data.task_id + "','" + t.task_data.master_task_id + "','" + t.is_chk + '\');" href="javascript:void(0)">' + o(_) + "</a></td>", u += '<td class="todoDueDatepicker" id="toDoDue_' + t.task_data.task_id + '"><span class="hidden">' + t.task_data.task_due_date + '</span><span class="date_edit">' + t.user_due_date + "</span></td>", u += '<td class="todoSchedulledDatepicker" id="schedulled_' + t.task_data.task_id + '"><span class="hidden">' + t.task_data.task_scheduled_date + '</span><span class="date_edit">' + t.user_scheduled_date + "</span></td>", u += "<td>" + t.task_data.task_priority + "</td>", u += '<td><span class="label label-sm label-' + status_class + '">' + s + '</span></td><input type="hidden" id="task_data_' + t.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + t.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + t.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + t.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + t.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + t.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + t.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + t.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + t.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + t.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + t.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + t.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + t.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + t.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + t.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + t.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + t.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + t.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + t.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + t.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + t.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + t.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + t.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + t.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + t.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + t.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + t.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + t.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + t.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + t.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + t.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + t.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + t.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + t.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + t.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + t.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + t.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + t.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + t.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + t.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + t.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + t.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + t.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + t.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + t.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + t.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + t.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + t.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + t.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + t.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + t.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + t.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + t.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + t.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + t.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + t.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + t.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + t.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + t.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + t.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + t.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + t.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + t.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + t.task_data.is_deleted + '&quot;}" /></tr>', $("#todo_" + a).replaceWith(u), $(".todoSchedulledDatepicker").datepicker({
                    startDate: -(1 / 0),
                    format: JAVASCRIPT_DATE_FORMAT
                }).on("changeDate", function(t) { t.stopImmediatePropagation();
                    $(this).datepicker("hide"), updateSchedulledDate(t.date, $(this).attr("id"))
                }), $(".todoDueDatepicker").datepicker({
                    startDate: -(1 / 0),
                    format: JAVASCRIPT_DATE_FORMAT
                }).on("changeDate", function(t) { t.stopImmediatePropagation();
                    $(this).datepicker("hide"), updateDueDate(t.date, $(this).attr("id"))
                })
            } else $("#todo_" + a).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
        }
    })
}

function updateDueDate(t, a) {
    function o(t) {
        return 10 > t ? "0" + t : t
    }
    var s = t,
        e = [o(s.getDate()), o(s.getMonth() + 1), s.getFullYear()].join("-");
    a = a.replace("toDoDue_", ""), $.ajax({
        type: "post",
        url: SIDE_URL + "task/updateDueDate",
        async: !1,
        data: {
            date: e,
            task_id: a,
            post_data: $("#task_data_" + a).val(),
            redirect_page: $("#redirect_page").val(),
            type: $("#dashboard_priority").val(),
            duration: $("#dashboard_duration").val()
        },
        success: function(t) {
            function o(t) {
                return t.substr(0, 1).toUpperCase() + t.substr(1)
            }
            if (t = jQuery.parseJSON(t), $("#watch" + a).length) {
                var s = t.task_status_name;
                status_class = s.toLowerCase(), status_class = status_class.replace(" ", "");
                var e = "";
                e += '<tr id="watch' + t.task_data.task_id + '" role="row" class="odd">', e += '<td title="' + t.task_data.task_description + '" class="sorting_1">', e += "1" == t.is_master_deleted || "0" == t.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips" onclick="edit_task(this,\'' + t.task_data.task_id + "','" + t.is_chk + '\')" href="javascript:void(0)">' + o(t.task_data.task_title) + "</a>" : '<a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips" onclick="open_seris(this,\'' + t.task_data.task_id + "','" + t.task_data.master_task_id + "','0');\" href=\"javascript:void(0)\">" + o(t.task_data.task_title) + "</a></td>", e += "</td>", e += '<td><span class="hidden">' + t.task_data.task_due_date + "</span>" + t.user_due_date + "</td>", e += '<td class="hidden-480">' + t.task_allocated_user_name + ".</td>", e += '<td><span class="label label-sm label-' + status_class + '">' + s + "</span></td>", e += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\'' + t.watch_id + "','" + t.task_data.task_id + '\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>', $("#watch" + a).replaceWith(e)
            }
            if ($("#last_login_" + a).length) {
                var d = "";
                d += '<tr id="last_login_' + t.task_data.task_id + '" role="row" class="odd">', d += "1" == t.task_data.is_master_deleted || "0" == t.task_data.master_task_id ? '<td class="sorting_1"><a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips dashboard_master_' + t.task_data.master_task_id + '" onclick="edit_task(this,\'' + t.task_data.task_id + "','" + t.is_chk + '\');" href="javascript:void(0)">' + o(t.task_data.task_title) + "</a>" : '<td class="sorting_1"><a  data-placement="right" data-original-title="' + t.task_data.task_title + '" class="tooltips dashboard_master_' + t.task_data.master_task_id + '" onclick="open_seris(this,\'' + t.task_data.task_id + "','" + t.task_data.master_task_id + "','" + t.is_chk + '\');" href="javascript:void(0)">' + o(t.task_data.task_title) + "</a>", d += '</td><td><span class="hidden">' + t.task_data.task_due_date + "</span>" + t.user_due_date + "</td>", d += "<td>" + t.task_data.task_priority + "</td>", d += "</tr>", $("#last_login_" + a).replaceWith(d)
            }
            if ("assign_other" == t.assign_status) $("#todo_" + $("#task_id").val()).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>'), $("#last_login_" + $("#task_id").val()).remove(), $("#teamoverdue_" + $("#task_id").val()).remove(), $("#teampending_" + $("#task_id").val()).remove(), $("#teamtodo_" + $("#task_id").val()).remove(), 0 == $("#teamtodolist tr td").length && $("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
            else if (1 == t.is_div_valid) {
                var _ = t.task_data.task_title;
                if (_ > 40) var i = _.substring(0, 37) + "...";
                else var i = _;
                var s = t.task_status_name;
                status_class = s.toLowerCase(), status_class = status_class.replace(" ", "");
                var u = "";
                u += '<tr id="todo_' + t.task_data.task_id + '" role="row" class="even">', u += '<td title="' + t.task_data.task_description + '" class="sorting_1">', u += "1" == t.task_data.is_master_deleted || "0" == t.task_data.master_task_id ? '<a  data-placement="right" data-original-title="' + _ + '" class="tooltips" data-dismiss="modal" onclick="edit_task(this,\'' + t.task_data.task_id + "','" + t.is_chk + '\');" href="javascript:void(0)" >' + o(i) + "</a></td>" : '<a  data-placement="right" data-original-title="' + _ + '" class="tooltips" onclick="open_seris(this,\'' + t.task_data.task_id + "','" + t.task_data.master_task_id + "','" + t.is_chk + '\');" href="javascript:void(0)">' + o(_) + "</a></td>", u += '<td class="todoDueDatepicker" id="toDoDue_' + t.task_data.task_id + '"><span class="hidden">' + t.task_data.task_due_date + '</span><span class="date_edit">' + t.user_due_date + "</span></td>", u += '<td class="todoSchedulledDatepicker" id="schedulled_' + t.task_data.task_id + '"><span class="hidden">' + t.task_data.task_scheduled_date + '</span><span class="date_edit">' + t.user_scheduled_date + "</span></td>", u += "<td>" + t.task_data.task_priority + "</td>", u += '<td><span class="label label-sm label-' + status_class + '">' + s + '</span></td><input type="hidden" id="task_data_' + t.task_data.task_id + '" value="{&quot;task_id&quot;:&quot;' + t.task_data.task_id + "&quot;,&quot;master_task_id&quot;:&quot;" + t.task_data.master_task_id + "&quot;,&quot;is_prerequisite_task&quot;:&quot;" + t.task_data.is_prerequisite_task + "&quot;,&quot;prerequisite_task_id&quot;:&quot;" + t.task_data.prerequisite_task_id + "&quot;,&quot;task_company_id&quot;:&quot;" + t.task_data.task_company_id + "&quot;,&quot;task_project_id&quot;:&quot;" + t.task_data.task_project_id + "&quot;,&quot;section_id&quot;:&quot;" + t.task_data.section_id + "&quot;,&quot;subsection_id&quot;:&quot;" + t.task_data.subsection_id + "&quot;,&quot;section_order&quot;:&quot;" + t.task_data.section_order + "&quot;,&quot;subsection_order&quot;:&quot;" + t.task_data.subsection_order + "&quot;,&quot;task_order&quot;:&quot;" + t.task_data.task_order + "&quot;,&quot;task_title&quot;:&quot;" + t.task_data.task_title + "&quot;,&quot;task_description&quot;:&quot;" + t.task_data.task_description + "&quot;,&quot;is_personal&quot;:&quot;" + t.task_data.is_personal + "&quot;,&quot;task_priority&quot;:&quot;" + t.task_data.task_priority + "&quot;,&quot;task_status_id&quot;:&quot;" + t.task_data.task_status_id + "&quot;,&quot;task_division_id&quot;:&quot;" + t.task_data.task_division_id + "&quot;,&quot;task_department_id&quot;:&quot;" + t.task_data.task_department_id + "&quot;,&quot;task_category_id&quot;:&quot;" + t.task_data.task_category_id + "&quot;,&quot;task_sub_category_id&quot;:&quot;" + t.task_data.task_sub_category_id + "&quot;,&quot;task_staff_level_id&quot;:&quot;" + t.task_data.task_staff_level_id + "&quot;,&quot;task_skill_id&quot;:&quot;" + t.task_data.task_skill_id + "&quot;,&quot;task_due_date&quot;:&quot;" + t.task_data.task_due_date + "&quot;,&quot;task_scheduled_date&quot;:&quot;" + t.task_data.task_scheduled_date + "&quot;,&quot;task_orig_scheduled_date&quot;:&quot;" + t.task_data.task_orig_scheduled_date + "&quot;,&quot;task_orig_due_date&quot;:&quot;" + t.task_data.task_orig_due_date + "&quot;,&quot;is_scheduled&quot;:&quot;" + t.task_data.is_scheduled + "&quot;,&quot;task_time_estimate&quot;:&quot;" + t.task_data.task_time_estimate + "&quot;,&quot;task_owner_id&quot;:&quot;" + t.task_data.task_owner_id + "&quot;,&quot;task_allocated_user_id&quot;:&quot;" + t.task_data.task_allocated_user_id + "&quot;,&quot;locked_due_date&quot;:&quot;" + t.task_data.locked_due_date + "&quot;,&quot;task_time_spent&quot;:&quot;" + t.task_data.task_time_spent + "&quot;,&quot;frequency_type&quot;:&quot;" + t.task_data.frequency_type + "&quot;,&quot;recurrence_type&quot;:&quot;" + t.task_data.recurrence_type + "&quot;,&quot;Daily_every_day&quot;:&quot;" + t.task_data.Daily_every_day + "&quot;,&quot;Daily_every_weekday&quot;:&quot;" + t.task_data.Daily_every_weekday + "&quot;,&quot;Daily_every_week_day&quot;:&quot;" + t.task_data.Daily_every_week_day + "&quot;,&quot;Weekly_every_week_no&quot;:&quot;" + t.task_data.Weekly_every_week_no + "&quot;,&quot;Weekly_week_day&quot;:&quot;" + t.task_data.Weekly_week_day + "&quot;,&quot;monthly_radios&quot;:&quot;" + t.task_data.monthly_radios + "&quot;,&quot;Monthly_op1_1&quot;:&quot;" + t.task_data.Monthly_op1_1 + "&quot;,&quot;Monthly_op1_2&quot;:&quot;" + t.task_data.Monthly_op1_2 + "&quot;,&quot;Monthly_op2_1&quot;:&quot;" + t.task_data.Monthly_op2_1 + "&quot;,&quot;Monthly_op2_2&quot;:&quot;" + t.task_data.Monthly_op2_2 + "&quot;,&quot;Monthly_op2_3&quot;:&quot;" + t.task_data.Monthly_op2_3 + "&quot;,&quot;Monthly_op3_1&quot;:&quot;" + t.task_data.Monthly_op3_1 + "&quot;,&quot;Monthly_op3_2&quot;:&quot;" + t.task_data.Monthly_op3_2 + "&quot;,&quot;yearly_radios&quot;:&quot;" + t.task_data.yearly_radios + "&quot;,&quot;Yearly_op1&quot;:&quot;" + t.task_data.Yearly_op1 + "&quot;,&quot;Yearly_op2_1&quot;:&quot;" + t.task_data.Yearly_op2_1 + "&quot;,&quot;Yearly_op2_2&quot;:&quot;" + t.task_data.Yearly_op2_2 + "&quot;,&quot;Yearly_op3_1&quot;:&quot;" + t.task_data.Yearly_op3_1 + "&quot;,&quot;Yearly_op3_2&quot;:&quot;" + t.task_data.Yearly_op3_2 + "&quot;,&quot;Yearly_op3_3&quot;:&quot;" + t.task_data.Yearly_op3_3 + "&quot;,&quot;Yearly_op4_1&quot;:&quot;" + t.task_data.Yearly_op4_1 + "&quot;,&quot;Yearly_op4_2&quot;:&quot;" + t.task_data.Yearly_op4_2 + "&quot;,&quot;start_on_date&quot;:&quot;" + t.task_data.start_on_date + "&quot;,&quot;no_end_date&quot;:&quot;" + t.task_data.no_end_date + "&quot;,&quot;end_after_recurrence&quot;:&quot;" + t.task_data.end_after_recurrence + "&quot;,&quot;end_by_date&quot;:&quot;" + t.task_data.end_by_date + "&quot;,&quot;task_added_date&quot;:&quot;" + t.task_data.task_added_date + "&quot;,&quot;task_completion_date&quot;:&quot;" + t.task_data.task_completion_date + "&quot;,&quot;is_deleted&quot;:&quot;" + t.task_data.is_deleted + '&quot;}" /></tr>', $("#todo_" + a).replaceWith(u),
                $(".todoDueDatepicker").datepicker({
                    startDate: -(1 / 0),
                    format: JAVASCRIPT_DATE_FORMAT
                }).on("changeDate", function(t) { t.stopImmediatePropagation();
                    $(this).datepicker("hide"),updateDueDate(t.date, $(this).attr("id"))
                }), $(".todoSchedulledDatepicker").datepicker({
                    startDate: -(1 / 0),
                    format: JAVASCRIPT_DATE_FORMAT
                }).on("changeDate", function(t) {t.stopImmediatePropagation();
                    $(this).datepicker("hide"),updateSchedulledDate(t.date, $(this).attr("id"))
                })
            } else $("#todo_" + a).remove(), 0 == $("#todolist tr td").length && $("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>')
        }
    })
}
$(function() {
    $(".todoSchedulledDatepicker").datepicker({
        startDate: -(1 / 0),
        format: JAVASCRIPT_DATE_FORMAT
    }).on("changeDate", function(t) {
        $(this).datepicker("hide"), updateSchedulledDate(t.date, $(this).attr("id"))
    }), $(".todoDueDatepicker").datepicker({
        startDate: -(1 / 0),
        format: JAVASCRIPT_DATE_FORMAT
    }).on("changeDate", function(t) { 
        $(this).datepicker("hide"), updateDueDate(t.date, $(this).attr("id"))
    }), $("#redirect_page").val("from_dashboard"), $("#dashboard_priority").val($("#dashboard_filter_task_priority").val()), $("#dashboard_duration").val($("#dashboard_filter_duration").val()), $(".scrollbaar_new").slimScroll({
        color: "#17A3E9",
        wheelStep: 20,
        height: "330px",
        showOnHover: !0
    }), $(".scrollbaar_new1").slimScroll({
        color: "#17A3E9",
        wheelStep: 20,
        height: "330px",
        showOnHover: !0
    }), $(".scrollbaar_new2").slimScroll({
        color: "#17A3E9",
        wheelStep: 20,
        height: "320px",
        showOnHover: !0
    }), $(".scrollbaar_new3").slimScroll({
        color: "#17A3E9",
        wheelStep: 20,
        height: "380px",
        showOnHover: !0
    }), $("#dashboard_filter_task_priority").val(""), $("#dashboard_filter_duration").val("today"), $("#rightList").sortable({
        items: "> :not(.unsorttd)",
        connectWith: ".connectedList",
        forcePlaceholderSize: !0,
        placeholder: "drag-place-holder",
        dropOnEmpty: !0,
        start: function(t, a) {},
        update: function(t, a) {
            $("#rightList > div:nth-child(2)").addClass("margin-class"), $("#rightList > div:nth-child(3)").removeClass("margin-class"), $("#rightList > div:nth-child(4)").addClass("margin-class"), $("#rightList > div:nth-child(5)").removeClass("margin-class"), $("#rightList > div:nth-child(6)").addClass("margin-class");
            var o = [];
            $("#rightList").children().each(function() {
                o.push(this.id)
            }), $.ajax({
                type: "post",
                url: SIDE_URL + "user/updateTiles",
                data: {
                    ids: o
                },
                success: function(t) {},
                error: function(t) {
                    console.log("Ajax request not recieved!")
                }
            })
        }
    }), $("#filtertab1,#filtertab2,#filtertab3").dataTable({
        order: [
            [1, "asc"]
        ],
        paging: !1,
        bFilter: !1,
        bLengthChange: !1,
        info: !1,
        language: {
            emptyTable: "No Records found."
        }
    }), $("#dashboard_filter_task_priority").change(function() {
        var t = $(this).val(),
            a = $("#dashboard_filter_duration").val();
        $("#dvLoading").fadeIn("slow"), $.ajax({
            type: "post",
            url: SIDE_URL + "user/todo_Ajax",
            data: {
                type: t,
                duration: a
            },
            success: function(t) {
                $("#filtertab1_in").html(t), $("#dashboard_priority").val($("#dashboard_filter_task_priority").val()), $("#dashboard_duration").val($("#dashboard_filter_duration").val()), $("#filtertab1").dataTable({
                    order: [
                        [1, "asc"]
                    ],
                    paging: !1,
                    bFilter: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    }
                }), $("#dvLoading").fadeOut("slow")
            },
            error: function(t) {
                console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
            }
        })
    }), $("#dashboard_filter_duration").change(function() {
        var t = $(this).val(),
            a = $("#dashboard_filter_task_priority").val();
        "" != t ? ($("#dvLoading").fadeIn("slow"), $.ajax({
            type: "post",
            url: SIDE_URL + "user/todo_Ajax",
            data: {
                type: a,
                duration: t
            },
            success: function(t) {
                $("#filtertab1_in").html(t), $("#dashboard_priority").val($("#dashboard_filter_task_priority").val()), $("#dashboard_duration").val($("#dashboard_filter_duration").val()), $("#filtertab1").dataTable({
                    order: [
                        [1, "asc"]
                    ],
                    paging: !1,
                    bFilter: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    }
                }), $("#dvLoading").fadeOut("slow")
            },
            error: function(t) {
                console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
            }
        })) : alertify.alert("Please select duration")
    })
});