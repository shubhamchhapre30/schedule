
function chk_company_name() {
    var e = $("#company_name").val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/is_company_name_exists",
        data: {
            value: e
        },
        success: function(e) {
            return e
        }
    })
}

function setCompanyDepartment() {
    var e = $("#parent_division").val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/setDepartment",
        data: {
            division_id: e
        },
        success: function(e) {
            $("#company_department").html(e);
        },
        error: function(e) {
            console.log("Ajax request not recieved!")
        }
    })
}

function delete_division(e) {
    var s = "Are you sure that you want to delete this division?";
    $('#delete_division_'+e).confirmation('show').on('confirmed.bs.confirmation',function(){
            $("#dvLoading").fadeIn("slow");
            void $.ajax({
         type: "post",
         url: SIDE_URL + "settings/delete_division",
         data: {
             id: e
         },
         success: function(s) {
             $("#division_" + e).remove(), $("#company_divisions tr").length < 2 && $("#company_divisions tr:first").before('<tr class="empty"><td colspan="2">No Records Available.</td></tr>');
             var s = jQuery.parseJSON(s),
                 t = "";
             s.divisions && $.map(s.divisions, function(e) {
                 t += '<option value="' + e.division_id + '">' + e.devision_title + "</option>"
             }), $("#parent_division").html(t), setCompanyDepartment(), $("#dvLoading").fadeOut("slow")
         }
     })
    }
)
}

function changeDivisionStatus(e, s) {
    if ($("#dvLoading").fadeIn("slow"), 1 == s) var t = "Active";
    else var t = "Inactive";
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/update_division_status",
        data: {
            id: e,
            val: t
        },
        success: function() {
            $("#dvLoading").fadeOut("slow")
        }
    })
}

function delete_department(e) {
    var s = "Are you sure that you want to delete this department?";
    $('#delete_department_'+e).confirmation('show').on('confirmed.bs.confirmation',function(){
            $("#dvLoading").fadeIn("slow")
            void $.ajax({
            type: "post",
            url: SIDE_URL + "settings/delete_department",
            data: {
                id: e
            },
            success: function(s) {
                $("#department_" + e).remove(), $("#company_departments tr").length < 2 && $("#company_departments tr:first").before('<tr class="empty"><td colspan="2">No Records Available.</td></tr>'), $("#dvLoading").fadeOut("slow")
            }
        })
    }
)
}

function changeDepartmentStatus(e, s) {
    if ($("#dvLoading").fadeIn("slow"), 1 == s) var t = "Active";
    else var t = "Inactive";
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/update_department_status",
        data: {
            id: e,
            val: t
        },
        success: function() {
            $("#dvLoading").fadeOut("slow")
        }
    })
}

function changeDivisionStatus(e, s) {
    if ($("#dvLoading").fadeIn("slow"), 1 == s) var t = "Active";
    else var t = "Inactive";
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/update_division_status",
        data: {
            id: e,
            val: t
        },
        success: function() {
            $("#dvLoading").fadeOut("slow")
        }
    })
}

function changeTaskTime(e, s) {
    if (1 == s) var t = "1";
    else var t = "0";
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/genral",
        data: {
            name: e,
            value: t
        },
        success: function() {
            
        }
    })
}

function changeStaffLevelsStatus(e, s) {
    if ($("#dvLoading").fadeIn("slow"), 1 == s) var t = "Active";
    else var t = "Inactive";
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/updateStaffLevelsStatus",
        data: {
            id: e,
            val: t
        },
        success: function() {
            $("#dvLoading").fadeOut("slow")
        }
    })
}

function delete_staffLevel(e) {
    var s = "Are you sure that you want to delete this staff-level?";
    $('#delete_staffLevel_'+e).confirmation('show').on('confirmed.bs.confirmation',function(){
            $("#dvLoading").fadeIn("slow");
            void $.ajax({
            type: "post",
            url: SIDE_URL + "settings/delete_staffLevel",
            data: {
                id: e
            },
            success: function(s) {
                $("#staffLevel_" + e).remove(),$("#dvLoading").fadeOut("slow")
            }
        })
    })
}

function changeSkillStatus(e, s) {
    if ($("#dvLoading").fadeIn("slow"), 1 == s) var t = "Active";
    else var t = "Inactive";
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/updateSkillStatus",
        data: {
            id: e,
            val: t
        },
        success: function() {
            $("#dvLoading").fadeOut("slow")
        }
    })
}

function delete_skill(e) {
    var s = "Are you sure that you want to delete this skill?";
    $('#delete_skill_'+e).confirmation('show').on('confirmed.bs.confirmation',function(){
            $("#dvLoading").fadeIn("slow");
            $.ajax({
            type: "post",
            url: SIDE_URL + "settings/delete_skill",
            data: {
                id: e
            },
            success: function(s) {
                $("#skill_" + e).remove(), $("#dvLoading").fadeOut("slow")
            }
        })
    })
}

function changeCategoryStatus(e, s) {
    if ($("#dvLoading").fadeIn("slow"), 1 == s) var t = "Active";
    else var t = "Inactive";
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/updateTaskCategoryStatus",
        data: {
            id: e,
            val: t
        },
        success: function() {
            $("#dvLoading").fadeOut("slow")
        }
    })
}

function delete_category(e, s) {
    var t = "Are you sure that you want to delete this category?";
    $('#delete_'+s+'_'+e).confirmation('show').on('confirmed.bs.confirmation',function(){
            $("#dvLoading").fadeIn("slow");
            $.ajax({
            type: "post",
            url: SIDE_URL + "settings/delete_category",
            data: {
                id: e
            },
            success: function(t) {
                var t = jQuery.parseJSON(t);
                if ("main" == s) {
                    $("#mainCategory_" + e).remove();
                    var a = "";
                    $.map(t.ParentTaskCategory, function(e) {
                        a += '<option value="' + e.category_id + '">' + e.category_name + "</option>"
                    }), $("#parent_category").html(a), setCompanySubCategory()
                } else $("#subTaskCategory_" + e).remove();
                $("#dvLoading").fadeOut("slow")
            }
        })
    })
}

function setCompanySubCategory() {
    var e = $("#parent_category").val();
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/setSubCategory",
        data: {
            parent_id: e
        },
        success: function(e) {
            $("#settings_subCategory").html(e)
        },
        error: function(e) {
            console.log("Ajax request not recieved!")
        }
    })
}

function show_task_alert() {
    return $("#alertify").show(), alertify.alert("You can't add more than 8 task status."),$("#task_status_name").val(''),$("#task_status_name-error").remove(), !1
}

function delete_selected(id) {
    var s = "Are you sure, you want to delete status?";
    $('#delete_status_'+id).confirmation('show').on('confirmed.bs.confirmation',function(){
            $("#dvLoading").fadeIn("slow")
            $.ajax({
            type: "post",
            url: SIDE_URL + "settings/deleteStatus",
            data: {
                delete_ids: id
            },
            success: function(e) {
                if("not_done" == e ){
                    $("#alertify").show(),
                    alertify.alert("You can not delete status due to some task already assigned to it.");
                }else{
                    $("#hide_total_status").val(e);
                    $("#task_status_name-error").remove()
                    $("#status_"+id).remove();
                    if(e>=8){
                        $("#change_status_button").replaceWith('<button type="button" class="btn blue txtbold sm" id="change_status_button" onclick="show_task_alert();"> Add</button>');
                    }else{
                        $("#change_status_button").replaceWith('<button type="submit" class="btn blue txtbold sm" id="change_status_button">Add</button><br id="show-error">');
                    }
                }
                $("#dvLoading").fadeOut("slow")
            },
            error: function(e) {
                console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
            }
        });
    });
}

function addCompanyUser() {
    $("#first_name").val(""), $("#last_name").val(""), $("#email").val(""), $("#tags_division").val(""), $("#tags_division").select2("data", null), $("#tags_department").val(""), $("#tags_department").select2("data", null), $("#tags_skills").val(""), $("#tags_skills").select2("data", null), $("#staff_level").val(""), userEditReportsTo(""), $("#User_MON_closed").prop("checked", !0), $("#User_MON_closed").closest("span").addClass("checked"), $("#User_TUE_closed").prop("checked", !0), $("#User_TUE_closed").closest("span").addClass("checked"), $("#User_WED_closed").prop("checked", !0), $("#User_WED_closed").closest("span").addClass("checked"), $("#User_THU_closed").prop("checked", !0), $("#User_THU_closed").closest("span").addClass("checked"), $("#User_FRI_closed").prop("checked", !0), $("#User_FRI_closed").closest("span").addClass("checked"), $("#User_MON_hours").val("8h"), $("#User_MON_hours_min").val("480"), $("#User_TUE_hours").val("8h"), $("#User_TUE_hours_min").val("480"), $("#User_WED_hours").val("8h"), $("#User_WED_hours_min").val("480"), $("#User_THU_hours").val("8h"), $("#User_THU_hours_min").val("480"), $("#User_FRI_hours").val("8h"), $("#User_FRI_hours_min").val("480"), $("#User_MON_closed").is(":checked") && ($("#User_MON_hours").removeAttr("disabled", "disabled"), $("#User_MON_hours_min").removeAttr("disabled", "disabled")), $("#User_TUE_closed").is(":checked") && ($("#User_TUE_hours").removeAttr("disabled", "disabled"), $("#User_TUE_hours_min").removeAttr("disabled", "disabled")), $("#User_WED_closed").is(":checked") && ($("#User_WED_hours").removeAttr("disabled", "disabled"), $("#User_WED_hours_min").removeAttr("disabled", "disabled")), $("#User_THU_closed").is(":checked") && ($("#User_THU_hours").removeAttr("disabled", "disabled"), $("#User_THU_hours_min").removeAttr("disabled", "disabled")), $("#User_FRI_closed").is(":checked") && ($("#User_FRI_hours").removeAttr("disabled", "disabled"), $("#User_FRI_hours_min").removeAttr("disabled", "disabled")), $("#admin_is_administrator").prop("checked", !1), $("#admin_is_administrator").closest("span").removeClass("checked"), $("#is_manager").prop("checked", !1), $("#is_manager").closest("span").removeClass("checked"), $("#user_status").prop("checked", !0), $("#user_status").closest("span").addClass("checked"), $("#addUserStaffLevelDiv").show(), $("#addUserReportsToDiv").show(),$("#timsheet_show").hide(), $("#addUserIsAdminDiv").show(), $("#addUserIsOwnerDiv").hide(), $("#owner_is_administrator").val(""), $("#owner_is_administrator").attr("disabled", "disabled"), $("#pre_user_status").val("Inactive"), $("#is_owner").val(""), $("#user_id").val(""), $("#listUserDiv").hide(),$("#speical_access").hide(), $("#addUserDiv").show(), $("#addBtnUser").show(), $("#saveBtnUser").hide()
}

function CompanyUserCancel() {
    $("#listUserDiv").show(), $("#addUserDiv").hide()
}

function delete_user(e) {
    var s = "Are you sure, you want to delete this user?";
    $('#delete_user_'+e).confirmation('show').on('confirmed.bs.confirmation',function(){
            $("#dvLoading").fadeIn("slow");
            $.ajax({
            type: "post",
            url: SIDE_URL + "user/deleteUser",
            data: {
                user_id: e
            },
            success: function(a) {
                $("#update_total_user_count").text(a);
                $("#listUser_" + e).remove(), $("#dvLoading").fadeOut("slow")
            }
        })
    })
}

function editCompanyUser(e) {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/editUser",
        data: {
            user_id: e
        },
        success: function(e) {
            var e = jQuery.parseJSON(e); 
            var check = '';
            if(e.user_info.customer_module_access == '1'){
                 check = 'checked="checked"';
            }
           
            if(customer_module_active == '1'){
                $("#speical_access").show()
            }
            var check3 = '';
            if(e.user_info.xero_access == '1'){
                check3 = 'checked="checked"';
            }
            var check2 = 'display:none';
            if(xero_module_access == '1'){
                check2 = 'display:inline-block';
            }
            
            var html = '';
                html += '<label class="checkbox">';
		html += '<input type="checkbox" name="customer_access_'+e.user_info.user_id+'" id="customer_access_'+e.user_info.user_id+'" '+check+' onclick="updateCustomerAccess('+e.user_info.user_id+');" value=""/> Add/Edit Customers';
		html += '</label>';
		html += '<label class="checkbox " style="'+check2+'">';
		html += '<input type="checkbox" name="Xero_access_'+e.user_info.user_id+'" id="Xero_access_'+e.user_info.user_id+'" '+check3+' onclick="updateXeroAccess('+e.user_info.user_id+');" value=""/> Timesheet Export(XERO)';
		html += '</label>';
            $("#first_name").val(e.user_info.first_name), $("#last_name").val(e.user_info.last_name),$("#email").val(e.user_info.email), $("#addBtnUser").hide(),$("#timsheet_show").show(), $("#saveBtnUser").show(), 
            $.ajax({
                type: "post",
                url: SIDE_URL + "user/get_approver_list",
                data: {
                    user_id: e.user_info.user_id
                },
                success: function(e) { 
                    var view = '';
                    var style = '';
                    var data = jQuery.parseJSON(e); 
                     view +='<option value="0">Please select</option>';
                     if(data.managers != 0){
                            $.each(data.managers, function( i, value ) { 
                                if(data.approver_details.timesheet_approver_id == data.managers[i].manager_id){
                                     style = "selected='selected'";
                                }else{
                                    style ='';
                                }
                              view +=' <option value="'+data.managers[i].manager_id+'" '+style+' >'+data.managers[i].first_name+' '+data.managers[i].last_name+'</option>';
                             
                           });
                     }
                     $("#approver_select").html(view);
                     
                 }
            }),
            $.ajax({
                type: "post",
                url: SIDE_URL + "user/divisions",
                data: {
                    user_id: e.user_info.user_id
                },
                success: function(e) {
                    $("#addUserDivisionDiv").html(e)
                }
            }),
            $.ajax({
                type: "post",
                url: SIDE_URL + "user/departments",
                data: {
                    user_id: e.user_info.user_id,
                    division_id: $("#tags_division").val()
                },
                success: function(e) {
                    $("#addUserDepartmentDiv").html(e)
                }
            }),
            $.ajax({
                type: "post",
                url: SIDE_URL + "user/skills",
                data: {
                    user_id: e.user_info.user_id
                },
                success: function(e) {
                    $("#addUserSkillsDiv").html(e)
                }
            }), "0" == e.user_info.is_administrator ? ($("#addUserStaffLevelDiv").show(), $("#staff_level").val(e.user_info.staff_level)) : $("#addUserStaffLevelDiv").hide(), $("#user_time_zone").val(e.user_info.user_time_zone), LOG_USER_ID != e.user_info.user_id ? (userEditReportsTo(e.user_info.user_id), $("#addUserReportsToDiv").show()) : (e.user_info.is_owner? userEditReportsTo(e.user_info.user_id):$("#addUserReportsToDiv").hide()), "1" == e.user_info.MON_closed ? ($("#User_MON_closed").prop("checked", !0), $("#User_MON_closed").closest("span").addClass("checked")) : ($("#User_MON_closed").prop("checked", !1), $("#User_MON_closed").closest("span").removeClass("checked")), "1" == e.user_info.TUE_closed ? ($("#User_TUE_closed").prop("checked", !0), $("#User_TUE_closed").closest("span").addClass("checked")) : ($("#User_TUE_closed").prop("checked", !1), $("#User_TUE_closed").closest("span").removeClass("checked")), "1" == e.user_info.WED_closed ? ($("#User_WED_closed").prop("checked", !0), $("#User_WED_closed").closest("span").addClass("checked")) : ($("#User_WED_closed").prop("checked", !1), $("#User_WED_closed").closest("span").removeClass("checked")), "1" == e.user_info.THU_closed ? ($("#User_THU_closed").prop("checked", !0), $("#User_THU_closed").closest("span").addClass("checked")) : ($("#User_THU_closed").prop("checked", !1), $("#User_THU_closed").closest("span").removeClass("checked")), "1" == e.user_info.FRI_closed ? ($("#User_FRI_closed").prop("checked", !0), $("#User_FRI_closed").closest("span").addClass("checked")) : ($("#User_FRI_closed").prop("checked", !1), $("#User_FRI_closed").closest("span").removeClass("checked")), "1" == e.user_info.SAT_closed ? ($("#User_SAT_closed").prop("checked", !0), $("#User_SAT_closed").closest("span").addClass("checked")) : ($("#User_SAT_closed").prop("checked", !1), $("#User_SAT_closed").closest("span").removeClass("checked")), "1" == e.user_info.SUN_closed ? ($("#User_SUN_closed").prop("checked", !0), $("#User_SUN_closed").closest("span").addClass("checked")) : ($("#User_SUN_closed").prop("checked", !1), $("#User_SUN_closed").closest("span").removeClass("checked")), $("#User_MON_closed").is(":checked") && ($("#User_MON_hours").removeAttr("disabled", "disabled"), $("#User_MON_hours_min").removeAttr("disabled", "disabled")), $("#User_TUE_closed").is(":checked") && ($("#User_TUE_hours").removeAttr("disabled", "disabled"), $("#User_TUE_hours_min").removeAttr("disabled", "disabled")), $("#User_WED_closed").is(":checked") && ($("#User_WED_hours").removeAttr("disabled", "disabled"), $("#User_WED_hours_min").removeAttr("disabled", "disabled")), $("#User_THU_closed").is(":checked") && ($("#User_THU_hours").removeAttr("disabled", "disabled"), $("#User_THU_hours_min").removeAttr("disabled", "disabled")), $("#User_FRI_closed").is(":checked") && ($("#User_FRI_hours").removeAttr("disabled", "disabled"), $("#User_FRI_hours_min").removeAttr("disabled", "disabled")), $("#User_SAT_closed").is(":checked") && ($("#User_SAT_hours").removeAttr("disabled", "disabled"), $("#User_SAT_hours_min").removeAttr("disabled", "disabled")), $("#User_SUN_closed").is(":checked") && ($("#User_SUN_hours").removeAttr("disabled", "disabled"), $("#User_SUN_hours_min").removeAttr("disabled", "disabled")), $("#User_MON_hours").val(hoursminutes(e.user_info.MON_hours)), $("#User_MON_hours_min").val(e.user_info.MON_hours), $("#User_TUE_hours").val(hoursminutes(e.user_info.TUE_hours)), $("#User_TUE_hours_min").val(e.user_info.TUE_hours), $("#User_WED_hours").val(hoursminutes(e.user_info.WED_hours)), $("#User_WED_hours_min").val(e.user_info.WED_hours), $("#User_THU_hours").val(hoursminutes(e.user_info.THU_hours)), $("#User_THU_hours_min").val(e.user_info.THU_hours), $("#User_FRI_hours").val(hoursminutes(e.user_info.FRI_hours)), $("#User_FRI_hours_min").val(e.user_info.FRI_hours), $("#User_SAT_hours").val(hoursminutes(e.user_info.SAT_hours)), $("#User_SAT_hours_min").val(e.user_info.SAT_hours), $("#User_SUN_hours").val(hoursminutes(e.user_info.SUN_hours)), $("#User_SUN_hours_min").val(e.user_info.SUN_hours), "0" == e.user_info.is_owner ? ("1" == e.user_info.is_administrator ? ($("#admin_is_administrator").prop("checked", !0), $("#admin_is_administrator").closest("span").addClass("checked")) : ($("#admin_is_administrator").prop("checked", !1), $("#admin_is_administrator").closest("span").removeClass("checked")), $("#addUserIsAdminDiv").show(), $("#addUserIsOwnerDiv").hide(), $("#owner_is_administrator").attr("disabled", "disabled")) : ($("#addUserIsAdminDiv").hide(), $("#addUserIsOwnerDiv").show(), $("#owner_is_administrator").removeAttr("disabled", "disabled"), $("#owner_is_administrator").val(e.user_info.is_administrator)), "1" == e.user_info.is_manager ? ($("#is_manager").prop("checked", !0), $("#is_manager").closest("span").addClass("checked")) : ($("#is_manager").prop("checked", !1), $("#is_manager").closest("span").removeClass("checked")), "Active" == e.user_info.user_status ? ($("#user_status").prop("checked", !0), $("#user_status").closest("span").addClass("checked")) : ($("#user_status").prop("checked", !1), $("#user_status").closest("span").removeClass("checked")), $("#pre_user_status").val(e.user_info.user_status), $("#is_owner").val(e.user_info.is_owner), $("#user_id").val(e.user_info.user_id), $("#is_manager").click(function() {
                if ($("#is_manager").prop("checked"));
                else {
                    var s = e.count;
                    s > 0 && ($("#alertify").show(), alertify.alert("Please remove employees reporting to the user before removing manager's rights."), $("#is_manager").prop("checked", !0), $("#is_manager").parent("span").attr("class", "checked"))
                }
            }), $("#add_speical_access").html(html);
            $("#tags_division").change(function() {
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/departments",
                    data: {
                        division_id: $("#tags_division").val()
                    },
                    success: function(e) {
                        $("#addUserDepartmentDiv").html(e)
                    }
                })
            }),$("#listUserDiv").hide(), $("#addUserDiv").show(), $("#dvLoading").fadeOut("slow")
        }
    })
}

function userEditReportsTo(e) {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/reports_to",
        data: {
            user_id: e
        },
        async: !1,
        success: function(e) {
            $("#dvLoading").fadeOut("slow"), $("#addUserReportsToDiv").html(e)
        }
    })
}
$(function() {
    App.init(), $("#tags_division").change(function() {
        $.ajax({
            type: "post",
            url: SIDE_URL + "settings/departments",
            data: {
                division_id: $("#tags_division").val()
            },
            success: function(e) {
                $("#addUserDepartmentDiv").html(e)
            }
        })
    });

    $("#addUserDiv").hide(), $("#manager_multiselect").multiselect({}), $("#User_MON_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#User_MON_hours").removeAttr("disabled", "disabled"), $("#User_MON_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#User_MON_hours").attr("disabled", "disabled"), $("#User_MON_hours_min").attr("disabled", "disabled"))
    }), $("#User_TUE_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#User_TUE_hours").removeAttr("disabled", "disabled"), $("#User_TUE_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#User_TUE_hours").attr("disabled", "disabled"), $("#User_TUE_hours_min").attr("disabled", "disabled"))
    }), $("#User_WED_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#User_WED_hours").removeAttr("disabled", "disabled"), $("#User_WED_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#User_WED_hours").attr("disabled", "disabled"), $("#User_WED_hours_min").attr("disabled", "disabled"))
    }), $("#User_THU_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#User_THU_hours").removeAttr("disabled", "disabled"), $("#User_THU_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#User_THU_hours").attr("disabled", "disabled"), $("#User_THU_hours_min").attr("disabled", "disabled"))
    }), $("#User_FRI_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#User_FRI_hours").removeAttr("disabled", "disabled"), $("#User_FRI_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#User_FRI_hours").attr("disabled", "disabled"), $("#User_FRI_hours_min").attr("disabled", "disabled"))
    }), $("#User_SAT_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#User_SAT_hours").removeAttr("disabled", "disabled"), $("#User_SAT_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#User_SAT_hours").attr("disabled", "disabled"), $("#User_SAT_hours_min").attr("disabled", "disabled"))
    }), $("#User_SUN_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#User_SUN_hours").removeAttr("disabled", "disabled"), $("#User_SUN_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#User_SUN_hours").attr("disabled", "disabled"), $("#User_SUN_hours_min").attr("disabled", "disabled"))
    }), $(".user-time-text").blur(function() {
        var e = $(this).attr("id"),
            s = $(this).val(),
            t = 1;
        if (s) {
            if ("1" == t)
                if (1 == validate(s)) {
                    var a = s.split(":");
                    if (s.split(":"), 2 == a.length) {
                        var i = a[0],
                            r = a[1];
                        if (r >= 60) {
                            var n = parseInt(r / 60),
                                o = r % 60,
                                l = +i + +n,
                                d = o;
                            if (0 == l) var c = d + "m";
                            else if (0 == d) var c = l + "h";
                            else var c = l + "h " + d + "m";
                            var _ = parseInt(60 * l) + parseInt(d);
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        } else {
                            var l = i,
                                d = r;
                            if (0 == l) var c = d + "m";
                            else if (0 == d) var c = l + "h";
                            else var c = l + "h " + d + "m";
                            var _ = parseInt(60 * l) + parseInt(d);
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        }
                    }
                    if (s.length >= 1 && s.length <= 2)
                        if (s >= 60) {
                            var l = parseInt(s / 60),
                                d = s % 60;
                            if (0 == l) var c = d + "m";
                            else if (0 == d) var c = l + "h";
                            else var c = l + "h " + d + "m";
                            var _ = parseInt(60 * l) + parseInt(d);
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        } else {
                            var d = s,
                                c = d + "m",
                                _ = d;
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        }
                    if (3 == s.length && 2 != a.length) {
                        var u = new Array,
                            u = ("" + s).split("");
                        if (u[s.length - (s.length - 1)] + u[s.length - (s.length - 2)] >= 60) {
                            var v = 1,
                                d = u[s.length - (s.length - 1)] + u[s.length - (s.length - 2)] - 60,
                                l = +u[s.length - s.length] + +v;
                            if (0 == l) var c = d + "m";
                            else if (0 == d) var c = l + "h";
                            else var c = l + "h " + d + "m";
                            var _ = parseInt(60 * l) + parseInt(d);
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        } else {
                            var d = u[s.length - (s.length - 1)] + u[s.length - (s.length - 2)],
                                l = u[s.length - s.length];
                            if (0 == l) var c = d + "m";
                            else if (0 == d) var c = l + "h";
                            else var c = l + "h " + d + "m";
                            var _ = parseInt(60 * l) + parseInt(d);
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        }
                    }
                    if (4 == s.length && 2 != a.length) {
                        var u = new Array,
                            u = ("" + s).split("");
                        if (u[s.length - (s.length - 2)] + u[s.length - (s.length - 3)] >= 60) {
                            var v = 1,
                                d = u[s.length - (s.length - 2)] + u[s.length - (s.length - 3)] - 60,
                                l = +(u[s.length - s.length] + u[s.length - (s.length - 1)]) + +v;
                            if (0 == l) var c = d + "m";
                            else if (0 == d) var c = l + "h";
                            else var c = l + "h " + d + "m";
                            var _ = parseInt(60 * l) + parseInt(d);
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        } else {
                            var d = u[s.length - (s.length - 2)] + u[s.length - (s.length - 3)],
                                l = +(u[s.length - s.length] + u[s.length - (s.length - 1)]);
                            if (0 == l) var c = d + "m";
                            else if (0 == d) var c = l + "h";
                            else var c = l + "h " + d + "m";
                            var _ = parseInt(60 * l) + parseInt(d);
                            $("#" + e).val(c), $("#" + e + "_min").val(_)
                        }
                    }
                    s.length >= 5 && 2 != a.length && ($("#" + e).val(""), $("#" + e + "_min").val("0"), $("#alertify").show(), alertify.alert("maximum 4 digits allowed"))
                } else $("#" + e + "_min").val() == get_minutes(s) || ($("#" + e).val(""), $("#" + e + "_min").val("0"), $("#alertify").show(), alertify.alert("your inserted value is not correct, please insert correct value"))
        } else $("#" + e).val(""), $("#" + e + "_min").val("0")
    }), $.validator.addMethod("alpha", function(e, s) {
        return this.optional(s) || /^[a-zA-Z\s]+$/.test(e)
    }, "Please enter only letters."), $.validator.addMethod("GreaterZero", function(e) {
        return parseFloat(e) > 0
    }, "Must be greater than 0"), $("#frm_add_user").validate({
        errorElement: "span",
        errorClass: "help-inline",
        focusInvalid: !0,
        ignore: "",
        rules: {
            first_name: {
                required: !0,
                alpha: !0,
                maxlength: 25
            },
            last_name: {
                required: !0,
                alpha: !0,
                maxlength: 25
            },
            email: {
                required: !0,
                email: !0,
                remote: {
                    url: SIDE_URL + "user/chk_email_exist",
                    type: "post",
                    data: {
                        email: function() {
                            return $("#email").val()
                        },
                        user_id: function() {
                            return $("#user_id").val()
                        }
                    }
                }
            },
            user_time_zone: {
                required: !0
            },
            MON_hours_min: {
                required: function(e) {
                    return !!$("#MON_closed").is(":checked")
                },
                number: !0,
                GreaterZero: !0
            },
            TUE_hours_min: {
                required: function(e) {
                    return !!$("#TUE_closed").is(":checked")
                },
                number: !0,
                GreaterZero: !0
            },
            WED_hours_min: {
                required: function(e) {
                    return !!$("#WED_closed").is(":checked")
                },
                number: !0,
                GreaterZero: !0
            },
            THU_hours_min: {
                required: function(e) {
                    return !!$("#THU_closed").is(":checked")
                },
                number: !0,
                GreaterZero: !0
            },
            FRI_hours_min: {
                required: function(e) {
                    return !!$("#FRI_closed").is(":checked")
                },
                number: !0,
                GreaterZero: !0
            },
            SAT_hours_min: {
                required: function(e) {
                    return !!$("#SAT_closed").is(":checked")
                },
                number: !0,
                GreaterZero: !0
            },
            SUN_hours_min: {
                required: function(e) {
                    return !!$("#SUN_closed").is(":checked")
                },
                number: !0,
                GreaterZero: !0
            }
        },
        messages: {
            email: {
                required: "Email address is required",
                email: "Please enter a valid email address",
                remote: "There is an existing record with this Email Address."
            }
        },
        errorPlacement: function(e, s) {
            "MON_hours_min" == s.attr("name") || "TUE_hours_min" == s.attr("name") || "WED_hours_min" == s.attr("name") || "THU_hours_min" == s.attr("name") || "FRI_hours_min" == s.attr("name") || "SAT_hours_min" == s.attr("name") || "SUN_hours_min" == s.attr("name") ? e.appendTo(s.parent("div")) : e.insertAfter(s)
        },
        submitHandler: function(e) {
            $("#dvLoading").fadeIn("slow"), $.ajax({
                type: "post",
                url: SIDE_URL + "user/addUser",
                data: $("#frm_add_user").serialize(),
                async: !1,
                success: function(e) {
                    var e = jQuery.parseJSON(e),
                        s = '<i class="fa fa-times " aria-hidden="true" style="color:red"></i>';
                    "1" == e.user.is_administrator && (s = '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>');
                    $("#update_total_user_count").text(e.user_count);
                    var check2 = '<i class="fa fa-times " aria-hidden="true" style="color:red"></i>';
                    if(e.user.user_status == 'Active'){
                        check2 = '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>';
                    }
                    var t = '<i class="fa fa-times " aria-hidden="true" style="color:red"></i>';
                    if ("1" == e.user.is_owner && (t = '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>'), e.tags_division) var a = e.tags_division;
                    else var a = "-";
                    if (e.tags_department) var i = e.tags_department;
                    else var i = "-";
                    if (e.staff_level) var r = e.staff_level;
                    else var r = "-";
                    var n = "";
                    n = '<tr id="listUser_' + e.user.user_id + '"><td width="20%">' + e.user.first_name + " " + e.user.last_name + "</td>",
                    n += "<td>" + e.user.email + "</td>",
                    n += "<td>" + r + "</td>",
                    n += "<td>" + a + "</td>",
                    n += "<td>" + i + "</td>",
                    n += "<td>" + check2 + "</td>",
                    n += "<td>" + s + "</td>",
                    n += "<td>" + t + "</td>",
                    n += '<td width="5%">', ("0" == e.user.is_owner && "1" == e.user.is_administrator || "0" == e.user.is_owner && "0" == e.user.is_administrator || LOG_USER_ID == e.user.user_id) && (n += '<a href="javascript:void(0)" onclick="editCompanyUser(\'' + e.user.user_id + '\')"><i class="icon-pencil stngicn company_icon_black"></i></a>'), "0" == e.user.is_owner && (n += "<a onclick=\"delete_user('" + e.user.user_id + '\');" id="delete_user_'+ e.user.user_id +'" href="javascript:void(0)"><i class="icon-trash stngicn company_icon_black"></i> </a>'),
                    n += "</td>" ;       
                    n += '</tr>';
                     $("#listUser_" + e.user.user_id).length ? $("#listUser_" + e.user.user_id).replaceWith(n) : $("#listUserTr").append(n), $("#listUserDiv").show(), $("#addUserDiv").hide(), $("#dvLoading").fadeOut("slow")
                }
            })
        }
    }),
    $(".setting-text").on("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var s = $(this).val();
                $(this).val(s+"\n");
             }
    }),
    $(".setting-text").blur(function() {
        var e = $(this).attr("id");
        //$("#" + e + "_loading").show();
        var s = $(this).attr("name"),
            t = $(this).val();
        t.trim() ? $.ajax({
            type: "post",
            url: SIDE_URL + "settings/genral",
            data: {
                name: s,
                value: t
            },
            async: !1,
            success: function(s) {
               // $("#" + e + "_loading").hide()
            }
        }) : ($("#alertify").show(), alertify.alert("This field is required.", function(s) {
            return $("#" + e).focus(), !1
        }))
    }),
    $(".setting-select").change(function() {
        var e = $(this).attr("id");
        $("#" + e + "_loading").show();
        var s = $(this).attr("name"),
            t = $(this).val();
        $.ajax({
            type: "post",
            url: SIDE_URL + "settings/genral",
            data: {
                name: s,
                value: t
            },
            async: !1,
            success: function(s) {
                $("#" + e + "_loading").hide()
            }
        })
    }),
    $("#company_logo").change(function() {
        var e = new FormData($("#frm_general")[0]);
        $("#dvLoading").fadeIn("slow"), $.ajax({
            type: "post",
            url: SIDE_URL + "settings/companyLogo",
            data: e,
            processData: !1,
            contentType: !1,
            success: function(e) {
                if ("not" == e) $("#dvLoading").fadeOut("slow"), $("#logo-browse").css("display", "block"), $("#logo-change").css("display", "none"), $("#logo-preview").html(""), $("#logo-icon").removeClass("icon-file"), $("#alertify").show(), alertify.alert("The filetype you are attempting to upload is not allowed.");
                else {
                    var e = jQuery.parseJSON(e);$("#new_company_logo").html('<img src="' + e + '" alt="schedullo" class="img-responsive">');
                    $("#company_logo_view").html('<img src="' + e + '" alt="schedullo" class="company_logo_css">'), $(".brand_header").html('<img class="margin-left-10" src="' + e + '" alt="schedullo">'), $("#logo-browse").css("display", "block"), $("#logo-change").css("display", "none"), $("#logo-preview").html(""), $("#logo-icon").css("display", "none"), $("#dvLoading").fadeOut("slow")
                }
            },
            error: function(e) {
                console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
            }
        })
    }), 
    $("#save_division").click(function() {
        var e = $("#devision_title").val();
        return "" == $.trim(e) ? ($("#alertify").show(), alertify.alert("Please enter division title."), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type: "post",
            url: SIDE_URL + "settings/chk_divisionName_exists",
            data: {
                name: e,
                company_id: $("#company_id").val()
            },
            async: !1,
            success: function(s) {
                return "1" == s ? ($("#alertify").show(), alertify.alert("There is an existing record with this division name.", function(e) {
                    return $("#devision_title").focus(), !1
                }), $("#dvLoading").fadeOut("slow"), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/add_division",
                    data: {
                        division_name: e,
                        status: "Active"
                    },
                    async: !1,
                    success: function(e) { 
                        var e = jQuery.parseJSON(e),
                            s = '<tr id="division_' + e.deivision_id + '">';
                            s += '<td width="3%" style="cursor:pointer;"><i class="fa fa-bars" aria-hidden="true"></i></td>';
                            s += '<td width="70%"><a href="javascript:void(0)" class="txt-style" id="' + e.deivision_id + '" data-type="text" data-pk="1" data-original-title="' + e.devision_title + '">' + e.devision_title + "</a></td>";
                            s += "<td>";
                            s += "Active" == e.devision_status ? '<input type="checkbox" id="devision_status_' + e.deivision_id + '" name="devision_status" checked value="' + e.devision_status + '"  data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  />' : '<input type="checkbox" id="devision_status_' + e.deivision_id + '" name="devision_status" value="' + e.devision_status + '"data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" "/>';
                            s += "<a onclick=\"delete_division('" + e.deivision_id + '\');" id="delete_division_'+ e.deivision_id +'" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i></a></td></tr>',$(".dataTables_empty").length == '1'? $(".dataTables_empty").parent().remove():'', $("#company_divisions").append(s), $("#" + e.deivision_id).editable({
                            url: SIDE_URL + "settings/update_division_name",
                            type: "post",
                            pk: 1,
                            mode: "inline",
                            showbuttons: !0,
                            validate: function(s) {
                                if ("" == $.trim(s)) return "This field is required";
                                var t = $.ajax({
                                    url: SIDE_URL + "settings/chk_divisionName_exists",
                                    type: "post",
                                    async: !1,
                                    data: {
                                        name: function() {
                                            return $.trim(s)
                                        },
                                        company_id: function() {
                                            return $("#company_id").val()
                                        },
                                        division_id: e.deivision_id
                                    },
                                    success: function(e) {
                                        return e
                                    }
                                });
                                return "1" == t.responseText ? "There is an existing record with this division name." : void 0
                            },
                            success: function(e) {
                                var e = jQuery.parseJSON(e),
                                    s = "";
                                $.map(e.divisions, function(e) {
                                    s += '<option value="' + e.division_id + '">' + e.devision_title + "</option>"
                                }), $("#parent_division").html(s)
                            }
                        }), $("#division_" + e.deivision_id + " .bts_toggle").bootstrapToggle('on'), $('#devision_status_' + e.deivision_id ).change(function() {
                            var t=$(this).prop('checked')?1:0;
                            changeDivisionStatus(e.deivision_id,t);
                           }), $("#devision_title").val(""), $("#devision_title").blur(function() {
                            $("#alertify-cover").css("position", "relative")
                        });
                        var t = "";
                        $.map(e.divisions, function(e) {
                            t += '<option value="' + e.division_id + '">' + e.devision_title + "</option>"
                        }), $("#parent_division").html(t), setCompanyDepartment(), $("#dvLoading").fadeOut("slow")
                    },
                    error: function(e) {
                        console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
                    }
                }))
            }
        }))
    }),
    $("#MON_closed").is(":checked") && ($("#MON_hours").removeAttr("disabled", "disabled"), $("#MON_hours_min").removeAttr("disabled", "disabled")), $("#MON_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#MON_hours").removeAttr("disabled", "disabled"), $("#MON_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#MON_hours").attr("disabled", "disabled"), $("#MON_hours_min").attr("disabled", "disabled"))
    }), $("#TUE_closed").is(":checked") && ($("#TUE_hours").removeAttr("disabled", "disabled"), $("#TUE_hours_min").removeAttr("disabled", "disabled")), $("#TUE_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#TUE_hours").removeAttr("disabled", "disabled"), $("#TUE_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#TUE_hours").attr("disabled", "disabled"), $("#TUE_hours_min").attr("disabled", "disabled"))
    }), $("#WED_closed").is(":checked") && ($("#WED_hours").removeAttr("disabled", "disabled"),
        $("#WED_hours_min").removeAttr("disabled", "disabled")), $("#WED_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#WED_hours").removeAttr("disabled", "disabled"), $("#WED_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#WED_hours").attr("disabled", "disabled"), $("#WED_hours_min").attr("disabled", "disabled"))
    }), $("#THU_closed").is(":checked") && ($("#THU_hours").removeAttr("disabled", "disabled"), $("#THU_hours_min").removeAttr("disabled", "disabled")), $("#THU_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#THU_hours").removeAttr("disabled", "disabled"), $("#THU_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#THU_hours").attr("disabled", "disabled"), $("#THU_hours_min").attr("disabled", "disabled"))
    }), $("#FRI_closed").is(":checked") && ($("#FRI_hours").removeAttr("disabled", "disabled"), $("#FRI_hours_min").removeAttr("disabled", "disabled")), $("#FRI_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#FRI_hours").removeAttr("disabled", "disabled"), $("#FRI_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#FRI_hours").attr("disabled", "disabled"), $("#FRI_hours_min").attr("disabled", "disabled"))
    }), $("#SAT_closed").is(":checked") && ($("#SAT_hours").removeAttr("disabled", "disabled"), $("#SAT_hours_min").removeAttr("disabled", "disabled")), $("#SAT_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#SAT_hours").removeAttr("disabled", "disabled"), $("#SAT_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#SAT_hours").attr("disabled", "disabled"), $("#SAT_hours_min").attr("disabled", "disabled"))
    }), $("#SUN_closed").is(":checked") && ($("#SUN_hours").removeAttr("disabled", "disabled"), $("#SUN_hours_min").removeAttr("disabled", "disabled")), $("#SUN_closed").click(function() {
        1 == $(this).prop("checked") ? ($("#SUN_hours").removeAttr("disabled", "disabled"), $("#SUN_hours_min").removeAttr("disabled", "disabled")) : 0 == $(this).prop("checked") && ($("#SUN_hours").attr("disabled", "disabled"), $("#SUN_hours_min").attr("disabled", "disabled"))
    }),
    $("#fisrt_day_of_week").change(function() {
        var e = $("#fisrt_day_of_week").val(),
            s = $("#old_first_day_of_week").val();
        if ("Monday" == e) {
            if (!$("#MON_closed").is(":checked")) return $("#alertify").show(), alertify.alert("Please select first day of week from your working days.", function(e) {
                return $("#fisrt_day_of_week").val(s), !1
            }), !1;
            $("#old_first_day_of_week").val(e)
        } else if ("Tuesday" == e) {
            if (!$("#TUE_closed").is(":checked")) return $("#alertify").show(), alertify.alert("Please select first day of week from your working days.", function(e) {
                return $("#fisrt_day_of_week").val(s), !1
            }), !1;
            $("#old_first_day_of_week").val(e)
        } else if ("Wednesday" == e) {
            if (!$("#WED_closed").is(":checked")) return $("#alertify").show(), alertify.alert("Please select first day of week from your working days.", function(e) {
                return $("#fisrt_day_of_week").val(s), !1
            }), !1;
            $("#old_first_day_of_week").val(e)
        } else if ("Thursday" == e) {
            if (!$("#THU_closed").is(":checked")) return $("#alertify").show(), alertify.alert("Please select first day of week from your working days.", function(e) {
                return $("#fisrt_day_of_week").val(s), !1
            }), !1;
            $("#old_first_day_of_week").val(e)
        } else if ("Friday" == e) {
            if (!$("#FRI_closed").is(":checked")) return $("#alertify").show(), alertify.alert("Please select first day of week from your working days.", function(e) {
                return $("#fisrt_day_of_week").val(s), !1
            }), !1;
            $("#old_first_day_of_week").val(e)
        } else if ("Saturday" == e) {
            if (!$("#SAT_closed").is(":checked")) return $("#alertify").show(), alertify.alert("Please select first day of week from your working days.", function(e) {
                return $("#fisrt_day_of_week").val(s), !1
            }), !1;
            $("#old_first_day_of_week").val(e)
        } else if ("Sunday" == e) {
            if (!$("#SUN_closed").is(":checked")) return $("#alertify").show(), alertify.alert("Please select first day of week from your working days.", function(e) {
                return $("#fisrt_day_of_week").val(s), !1
            }), !1;
            $("#old_first_day_of_week").val(e)
        }
        $.ajax({
            type: "post",
            url: SIDE_URL + "settings/save_calendar_settings",
            async: !1,
            data: {
                name: "fisrt_day_of_week",
                val: e
            },
            success: function() {
                
            }
        })
    }), 
    $(".time-text").blur(function() {
        var e = $(this).attr("id"),
            s = $(this).attr("name"),
            t = $(this).val();
        if ("" == t || "0" == t) return $("#" + e).val(""), $("#" + e + "_min").val("0"), $("#alertify").show(), alertify.alert("Please insert value greater than 0", function() {
            $("#" + e).focus()
        }), !1;
        if (1 == validate(t)) {
            var a = t.split(":");
            if (t.split(":"), 2 == a.length) {
                var i = a[0],
                    r = a[1];
                if (r >= 60) {
                    var n = parseInt(r / 60),
                        o = r % 60,
                        l = +i + +n,
                        d = o;
                    if (0 == l) var c = d + "m";
                    else if (0 == d) var c = l + "h";
                    else var c = l + "h " + d + "m";
                    var _ = parseInt(60 * l) + parseInt(d);
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                } else {
                    var l = i,
                        d = r;
                    if (0 == l) var c = d + "m";
                    else if (0 == d) var c = l + "h";
                    else var c = l + "h " + d + "m";
                    var _ = parseInt(60 * l) + parseInt(d);
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                }
            }
            if (t.length >= 1 && t.length <= 2)
                if (t >= 60) {
                    var l = parseInt(t / 60),
                        d = t % 60;
                    if (0 == l) var c = d + "m";
                    else if (0 == d) var c = l + "h";
                    else var c = l + "h " + d + "m";
                    var _ = parseInt(60 * l) + parseInt(d);
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                } else {
                    var d = t,
                        c = d + "m",
                        _ = d;
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                }
            if (3 == t.length && 2 != a.length) {
                var u = new Array,
                    u = ("" + t).split("");
                if (u[t.length - (t.length - 1)] + u[t.length - (t.length - 2)] >= 60) {
                    var v = 1,
                        d = u[t.length - (t.length - 1)] + u[t.length - (t.length - 2)] - 60,
                        l = +u[t.length - t.length] + +v;
                    if (0 == l) var c = d + "m";
                    else if (0 == d) var c = l + "h";
                    else var c = l + "h " + d + "m";
                    var _ = parseInt(60 * l) + parseInt(d);
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                } else {
                    var d = u[t.length - (t.length - 1)] + u[t.length - (t.length - 2)],
                        l = u[t.length - t.length];
                    if (0 == l) var c = d + "m";
                    else if (0 == d) var c = l + "h";
                    else var c = l + "h " + d + "m";
                    var _ = parseInt(60 * l) + parseInt(d);
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                }
            }
            if (4 == t.length && 2 != a.length) {
                var u = new Array,
                    u = ("" + t).split("");
                if (u[t.length - (t.length - 2)] + u[t.length - (t.length - 3)] >= 60) {
                    var v = 1,
                        d = u[t.length - (t.length - 2)] + u[t.length - (t.length - 3)] - 60,
                        l = +(u[t.length - t.length] + u[t.length - (t.length - 1)]) + +v;
                    if (0 == l) var c = d + "m";
                    else if (0 == d) var c = l + "h";
                    else var c = l + "h " + d + "m";
                    var _ = parseInt(60 * l) + parseInt(d);
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                } else {
                    var d = u[t.length - (t.length - 2)] + u[t.length - (t.length - 3)],
                        l = +(u[t.length - t.length] + u[t.length - (t.length - 1)]);
                    if (0 == l) var c = d + "m";
                    else if (0 == d) var c = l + "h";
                    else var c = l + "h " + d + "m";
                    var _ = parseInt(60 * l) + parseInt(d);
                    $("#" + e).val(c), $("#" + e + "_min").val(_)
                }
            }
            if (t.length >= 5 && 2 != a.length) return $("#" + e).val(""), $("#" + e + "_min").val("0"), $("#alertify").show(), alertify.alert("maximum 4 digits allowed"), !1
        } else if ($("#" + e + "_min").val() != get_minutes(t)) return $("#" + e).val(""), $("#" + e + "_min").val("0"), $("#alertify").show(), alertify.alert("your inserted value is not correct, please insert correct value"), !1;
        $("#" + e + "_loading").show(), $.ajax({
            type: "post",
            url: SIDE_URL + "settings/save_calendar_settings",
            async: !1,
            data: {
                name: s,
                val: $("#" + s + "_min").val()
            },
            success: function() {
                $("#" + e + "_loading").hide()
            }
        })
    }), 
    $(".setting-cal-chkbox").click(function() {
        var e = $(this).attr("name"),
            s = ($(this).attr("id"), 
            e.replace("closed", "") + "hours");
        if ($("#" + e).is(":checked")){
            var a = "1";
            $("#"+s).val("8h");
            $("#"+s+"_min").val("480");
        }else{
            var a = "0";
        }
        var t = $("#" + s + "_min").val();
        if("1" == a){
            if("0" != t){
                $.ajax({
                type: "post",
                url: SIDE_URL + "settings/save_calendar_settings",
                async: !1,
                data: {
                    name: s,
                    val: t
                },
                success: function() {}
            }), $.ajax({
                type: "post",
                url: SIDE_URL + "settings/save_calendar_settings",
                async: !1,
                data: {
                    name: e,
                    val: a
                },
                success: function() {}
            })
            }else{
                $("#alertify").show();
                alertify.confirm("Please add more than 0 minutes for equivalent field of this day.", function(t) {
                return 1 != t ? ($("#" + e).closest("span").removeClass("checked"), $("#" + e).prop("checked", !1), $("#" + s).attr("disabled", !0), $("#" + s + "_min").attr("disabled", !0), !1) : ($("#" + s).val(""), $("#" + s).focus(), void $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/save_calendar_settings",
                    async: !1,
                    data: {
                        name: e,
                        val: a
                    },
                    success: function() {}
                }))
                })
            }
        }else{
            $("#" + s).val("0m");
            $("#" + s + "_min").val("0");
            $.ajax({
                type: "post",
                url: SIDE_URL + "settings/save_calendar_settings",
                async: !1,
                data: {
                    name: s,
                    val: "0"
                },
                success: function() {}
            }), $.ajax({
                type: "post",
                url: SIDE_URL + "settings/save_calendar_settings",
                async: !1,
                data: {
                    name: e,
                    val: a
                },
                success: function() {}
            })
        }
    }),
    $("#actual_time_on").bootstrapToggle(), $("#actual_time_on").bootstrapToggle().on("change", function() { var t=$(this).prop('checked')?1:0;
     changeTaskTime("actual_time_on", t)
    }),
    $("#allow_past_task").bootstrapToggle(), $("#allow_past_task").bootstrapToggle().on("change", function() { var st=$(this).prop('checked')?1:0;
        changeTaskTime("allow_past_task", st)
    }), 
    $("#save_staffLevel").click(function() {
        var e = $("#staff_level_title").val();
        return "" == $.trim(e) ? ($("#alertify").show(), alertify.alert("Please enter staff level title."), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type: "post",
            url: SIDE_URL + "settings/chk_staffLevels_exists",
            data: {
                name: e,
                company_id: $("#company_id").val()
            },
            async: !1,
            success: function(s) {
                return "1" == s ? ($("#alertify").show(), alertify.alert("There is an existing record with this staff-level name.", function(e) {
                    return $("#staff_level_title").focus(), !1
                }), $("#dvLoading").fadeOut("slow"), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/addStaffLevels",
                    data: {
                        staff_name: e,
                        staff_status: "Active"
                    },
                    success: function(e) {
                        var e = jQuery.parseJSON(e),
                            s = '<tr id="staffLevel_' + e.staff_level_id + '">';
                            s += '<td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none">'+e.seq+'</span></td>';
                            s += '<td width="400px"><a href="javascript:void(0)" class="txt-style" id="staffLevelName_' + e.staff_level_id + '" data-type="text" data-pk="1" data-original-title="' + e.staff_level_title + '">' + e.staff_level_title + "</a></td>";
                            s += "<td>", s += "Active" == e.staff_level_status ? '<input type="checkbox" id="staffLevel_status_' + e.staff_level_id + '" name="staff_level_status" checked value="' + e.staff_level_status + '" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" />' : '<input type="checkbox" id="staffLevel_status_' + e.staff_level_id + '" name="staff_level_status" value="' + e.staff_level_status + '" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" />', s += "<a onclick=\"delete_staffLevel('" + e.staff_level_id + '\');" id="delete_staffLevel_'+ e.staff_level_id +'" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i></a></td></tr>',($("#company_staffLevels .dataTables_empty").length)? $("#company_staffLevels .dataTables_empty").parent().remove():'', $("#company_staffLevels").append(s), $("#staffLevelName_" + e.staff_level_id).editable({
                            url: SIDE_URL + "settings/update_stafflevel_name",
                            type: "post",
                            pk: 1,
                            mode: "inline",
                            showbuttons: !0,
                            validate: function(s) {
                                if ("" == $.trim(s)) return "This field is required";
                                var t = $.ajax({
                                    url: SIDE_URL + "settings/chk_staffLevels_exists",
                                    type: "post",
                                    async: !1,
                                    data: {
                                        name: $.trim(s),
                                        company_id: function() {
                                            return $("#company_id").val()
                                        },
                                        staff_level_id: e.staff_level_id
                                    },
                                    success: function(e) {
                                        return e
                                    }
                                });
                                return "1" == t.responseText ? "There is an existing record with this staff-level name." : void 0
                            },
                            success: function(e) {}
                        }), $("#staffLevel_status_" + e.staff_level_id).bootstrapToggle(), $("#staffLevel_status_" + e.staff_level_id).bootstrapToggle().on("change", function(s, t) {
                             var t=$(this).prop('checked')?1:0;
                            changeStaffLevelsStatus(e.staff_level_id, t)
                        }), $("#staff_level_title").val(""), $("#staff_level_title").blur(function() {
                            $("#alertify-cover").css("position", "relative")
                        }), $("#dvLoading").fadeOut("slow")
                    },
                    error: function(e) {
                        console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
                    }
                }))
            }
        }))
    }), 
    $("#save_skill").click(function() {
        var e = $("#skill_title").val();
        return "" == $.trim(e) ? ($("#alertify").show(), alertify.alert("Please enter skill name."), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type: "post",
            url: SIDE_URL + "settings/chk_skillName_exists",
            data: {
                name: e,
                company_id: $("#company_id").val()
            },
            async: !1,
            success: function(s) {
                return "1" == s ? ($("#alertify").show(), alertify.alert("There is an existing record with this skill name.", function(e) {
                    return $("#skill_title").focus(), !1
                }), $("#dvLoading").fadeOut("slow"), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/addSkills",
                    data: {
                        skill_name: e,
                        skill_status: "Active"
                    },
                    success: function(e) {
                        var e = jQuery.parseJSON(e),
                            s = '<tr id="skill_' + e.skill_id + '">';
                            s += '<td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none">'+e.seq+'</span></td>';
                            s += '<td width="400px"><a href="javascript:void(0)" class="txt-style" id="skillName_' + e.skill_id + '" data-type="text" data-pk="1" data-original-title="' + e.skill_title + '">' + e.skill_title + "</a></td>";
                            s += "<td>", s += "Active" == e.skill_status ? '<input type="checkbox" id="skill_status_' + e.skill_id + '" name="skill_status" checked value="' + e.skill_status + '" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" />' : '<input type="checkbox" name="skill_status" id="skill_status_' + e.skill_id + '" value="' + e.skill_status + '" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" />', s += "<a onclick=\"delete_skill('" + e.skill_id + '\');" id="delete_skill_'+ e.skill_id +'" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i></a></td></tr>',($("#company_skills .dataTables_empty").length)? $("#company_skills .dataTables_empty").parent().remove():'', $("#company_skills").append(s), $("#skillName_" + e.skill_id).editable({
                            url: SIDE_URL + "settings/update_skill_name",
                            type: "post",
                            pk: 1,
                            mode: "inline",
                            showbuttons: !0,
                            validate: function(s) {
                                if ("" == $.trim(s)) return "This field is required";
                                var t = $.ajax({
                                    url: SIDE_URL + "settings/chk_skillName_exists",
                                    type: "post",
                                    async: !1,
                                    data: {
                                        name: $.trim(s),
                                        company_id: function() {
                                            return $("#company_id").val()
                                        },
                                        skill_id: e.skill_id
                                    },
                                    success: function(e) {
                                        return e
                                    }
                                });
                                return "1" == t.responseText ? "There is an existing record with this skill name." : void 0
                            },
                            success: function(e) {}
                        }), $("#skill_status_" + e.skill_id).bootstrapToggle(), $("#skill_status_" + e.skill_id).bootstrapToggle().on("change", function(s, t) {
                            var t=$(this).prop('checked')?1:0;
                            changeSkillStatus(e.skill_id, t)
                        }), $("#skill_title").val(""), $("#skill_title").blur(function() {
                            $("#alertify-cover").css("position", "relative")
                        }), $("#dvLoading").fadeOut("slow")
                    },
                    error: function(e) {
                        console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
                    }
                }))
            }
        }))
    }), 
    $("#save_main_category").click(function() {
        var e = $("#main_category_name").val();
        return "" == $.trim(e) ? ($("#alertify").show(), alertify.alert("Please enter category name."), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
            type: "post",
            url: SIDE_URL + "settings/chk_taskCategory_exists",
            data: {
                name: e,
                company_id: $("#company_id").val(),
                type: "main"
            },
            async: !1,
            success: function(s) {
                return "1" == s ? ($("#alertify").show(), alertify.alert("There is an existing record with this category name.", function(e) {
                    return $("#main_category_name").focus(), !1
                }), $("#dvLoading").fadeOut("slow"), !1) : ($("#dvLoading").fadeIn("slow"), void $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/addTaskCategory",
                    data: {
                        taskCategory_name: e,
                        taskCategory_status: "Active"
                    },
                    success: function(e) {
                        var e = jQuery.parseJSON(e),
                            s = '<tr id="mainCategory_' + e.category_id + '">';
                            s += '<td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none">'+e.seq+'</span></td>';
                            s += '<td width="300px"><a href="javascript:void(0)" class="txt-style" id="mainCategoryTitle_' + e.category_id + '" data-type="text" data-pk="1" data-original-title="' + e.category_name + '">' + e.category_name + "</a></td>";
                            if(PRICING_MODULE_STATUS == '1'){
                            s += '<td width="100px"><input type="checkbox" name="is_category_chargeable_'+e.category_id +'" id="is_category_chargeable_'+e.category_id+'" onclick="addChargeablecategory('+e.category_id+');" checked /></td>';
                            }
                             s += '<td width="200px">', s += "Active" == e.category_status ? '<input type="checkbox" id="mainCategory_status_' + e.category_id + '" name="mainCategory_status" checked value="' + e.category_status + '" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" />' : '<input type="checkbox" id="mainCategory_status_' + e.category_id + '" name="mainCategory_status" value="' + e.category_status + '" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" />', s += "<a onclick=\"delete_category('" + e.category_id + '\',\'main\');" id="delete_main_'+ e.category_id +'"  href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i></a></td></tr>',($("#company_mainCategory .dataTables_empty").length)? $("#company_mainCategory .dataTables_empty").parent().remove():'', $("#company_mainCategory").append(s), $("#mainCategoryTitle_" + e.category_id).editable({
                            url: SIDE_URL + "settings/update_catgory_name",
                            type: "post",
                            pk: 1,
                            mode: "inline",
                            showbuttons: !0,
                            validate: function(s) {
                                if ("" == $.trim(s)) return "This field is required";
                                var t = $.ajax({
                                    url: SIDE_URL + "settings/chk_taskCategory_exists",
                                    type: "post",
                                    async: !1,
                                    data: {
                                        name: $.trim(s),
                                        company_id: function() {
                                            return $("#company_id").val()
                                        },
                                        category_id: e.category_id,
                                        type: "main"
                                    },
                                    success: function(e) {
                                        return e
                                    }
                                });
                                return "1" == t.responseText ? "There is an existing record with this category name." : void 0
                            },
                            success: function(e) {
                                var e = jQuery.parseJSON(e),
                                    s = "";
                                $.map(e.ParentTaskCategory, function(e) {
                                    s += '<option value="' + e.category_id + '">' + e.category_name + "</option>"
                                }), $("#parent_category").html(s)
                            }
                        }), $("#mainCategory_status_"+ e.category_id).bootstrapToggle(), $("#mainCategory_status_" + e.category_id).bootstrapToggle().on("change", function() {
                             var t=$(this).prop('checked')?1:0;
                            changeCategoryStatus(e.category_id, t)
                        }), $("#main_category_name").val(""), $("#main_category_name").blur(function() {
                            $("#alertify-cover").css("position", "relative")
                        });
                        var t = "";
                        $.map(e.ParentTaskCategory, function(e) {
                            t += '<option value="' + e.category_id + '">' + e.category_name + "</option>"
                        }), $("#parent_category").html(t), setCompanySubCategory(), $("#dvLoading").fadeOut("slow")
                    },
                    error: function(e) {
                        console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
                    }
                }))
            }
        }))
    }), 
    $("#frm_taskStatus_add").validate({
        rules: {
            task_status_name: {
                required: !0,
                maxlength: 50,
                remote: {
                    url: SIDE_URL + "settings/chk_taskStatus_exists",
                    type: "post",
                    data: {
                        name: function() {
                            return $("#task_status_name").val()
                        },
                        company_id: function() {
                            return $("#company_id").val()
                        }
                    }
                }
            }
        },
        messages: {
            task_status_name: {
                required: "This field is required",
                maxlength: "Please enter no more than 50 characters.",
                remote: "There is an existing record with this task status name"
            }
        },
        errorPlacement: function (error, element) { 
		error.insertAfter("#show-error");
        },
        submitHandler: function() {
            $("#dvLoading").fadeIn("slow"), $.ajax({
                type: "post",
                url: SIDE_URL + "settings/addTaskStatus",
                data:{
                    info:$("#frm_taskStatus_add").serialize()
                },
                success: function(e) { 
                   var data = jQuery.parseJSON(e);
                   var html = '';
                   html += '<tr id="status_'+data.status_id+'">';
                   html += '<td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none">'+data.seq+'</span></td>';
                   html += '<td width="70%">'+data.status_name+'</td>';
                   html += '<td><div><a  href="javascript:void(0)" onclick="delete_selected('+data.status_id+');" id="delete_status_'+data.status_id+'"> <i class="fa fa-trash-o company_trash_icon company_icon_black"></i> </a></div></td>';
                   html += '</tr>';
                   $("#hide_total_status").val(data.total_status);
                   $("#addStatus").append(html);
                   if(data.total_status>=8){
                        $("#change_status_button").replaceWith('<button type="button" class="btn blue txtbold sm" id="change_status_button" onclick="show_task_alert();"> Add</button>');
                    }else{
                        $("#change_status_button").replaceWith('<button type="submit" class="btn blue txtbold sm" id="change_status_button">Add</button><br id="show-error">');
                    }
                    $("#task_status_name").val('');
                   $("#dvLoading").fadeOut("slow")
                },
                error: function(e) {
                    console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
                }
            })
        }
    }), 
    $("#billing").click(function() {
        $("#dvLoading").fadeIn("slow"), $.ajax({
            url: SIDE_URL + "settings/accessportal",
            type: "post",
            cache: !1,
            success: function(e) {
                e = jQuery.parseJSON(e), null != e ? e.url ? window.open(e.url, "_blank") : (date = new Date(e.errors.new_link_available_at), $date1 = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(), alertify.alert(e.errors.error + ", New link will be available after " + $date1)) : alertify.alert("Sorry, we couldn't find billing detail for the account"), $("#dvLoading").fadeOut("slow")
            },
            error: function(e) {
                console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
            }
        })
    })
     $("#close_account").click(function() {
        var s = "Do you really want to close the account?";
        var ss = "Your account and all users will be removed, your data will also be deleted immediately. Please confirm.";
    $("#alertify").show(), alertify.confirm(s, function(s) {
        return !!s && alertify.confirm(ss, function(ss) { 
                    return !!ss && (
                            $('#closeAccountModal').modal('show')
            )
        })
    })
     })
     $('#close_reason').change(function(){
         if($(this).val() == 'Other')
            $('#reason_other').show();
        else
            $('#reason_other').hide();
     });
      $("#close_user_account").click(function() {
                     $("#dvLoading").fadeIn("slow");
              
        $.ajax({
            url: SIDE_URL + "settings/close_account",
            type: "post",
            data: $('#close_account_form').serialize(),
            cache: !1,
            success: function(e) {
                $.ajax({
				url : SIDE_URL + "home/logout",
				cache: false,
				success: function(responseData) {
					window.location.reload();
			    }
			})	
            },
            error: function(e) {
                console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
            }
        })
          
      });
     
});
/**
 *update customer module access for particuler usero of company.
 */
function updateCustomerAccess(e){
        if($('#customer_access_'+e).is(':checked')){
            var a = '1';
        }else{
            var a = '0';
        }
        $.ajax({
                url: SIDE_URL + "settings/updateCustomerAccess",
                type: "post",
                data: {
                    user_id: e,
                    access: a
                 },
                success: function(s) {
                },
                error:function(s){
                    console.log('Ajax request not recieved!');
                }
            });
}
/**
 * This method is used for update customer module addon in chargify account & db. 
 */
function updateCustomerModuleStatus(s){
    
    var status=s;
     $("#dvLoading").fadeIn("slow");
        $.ajax({
                url: SIDE_URL + "settings/updateCustomerModule",
                type: "post",
                data: {
                    status:status
                 },
                success: function(data) { 
                     $.ajax({
                            url: SIDE_URL + "settings/changeCustomerModuleStatus",
                            type: "post",
                            data: {
                                  status:status
                            },
                            success: function(data) { 
                                if(status=='0'){ 
                                    $("#customer_module").css('display','none'); // CUSTOMER MODULE DISABLE
                                    $("#access_pricing_module").css("display",'none'); //PRICING MODULE DISABLE
                                    $("#pricing_module_status").parent('div').addClass('disabled'); //PRICING MODULE BUTTON DISABLE
                                    $("#pricing_module_status").prop('disabled','disabled'); //PRICING MODULE DISABLE
                                    $("#currency_list").css('display','none'); //CURRENCY LIST HIDE
                                    $("#timesheet_module_status").prop('disabled','disabled'); // TIMESHEET MODULE DISABLE
                                    $("#timesheet_module_status").parent('div').addClass('disabled'); //ADD DISABLE CLASS ON TIMESHEET BUTTON
                                    $("#timesheet_module_access").css('display','none'); //TIMESHEET MODULE ACCESS OFF
                                    $("#xero_integration").prop('disabled','disabled'); // XERO INTEGRATION  DISABLE
                                    $("#xero_integration").parent('div').addClass('disabled'); //ADD DISABLE CLASS ON XERO BUTTON
                                }else{
                                    $("#customer_module").css('display','block'); //CUSTOMER MODULE ENABLE
                                    $("#access_pricing_module").css("display",'block'); //PRICING MODULE ENABLE
                                    $("#pricing_module_status").parent('div').removeClass('disabled'); //REMOVE CLASS FORM PRICING MODULE
                                    $("#pricing_module_status").parent('div').removeAttr('disabled'); //REMOVE CLASS FORM PRICING MODULE
                                    $("#pricing_module_status").removeProp('disabled'); //PRICING MODULE ENABLE
                                    $("#currency_list").css('display','block'); //CURRENCY LIST SHOW
                                    $("#timesheet_module_status").removeProp('disabled'); // TIMESHEET MODULE ENABLE
                                    $("#timesheet_module_status").parent('div').removeClass('disabled'); //REMOVE DISABLE CLASS ON TIMESHEET BUTTON
                                    $("#timesheet_module_status").parent('div').removeAttr('disabled'); //REMOVE DISABLE CLASS ON TIMESHEET BUTTON
                                    $("#timesheet_module_access").css('display','block'); //TIMESHEET MODULE ACCESS ON
                                    $("#xero_integration").removeProp('disabled'); // XERO INTEGRATION  ENABLE
                                    $("#xero_integration").parent('div').removeClass('disabled'); //remove DISABLE CLASS ON XERO BUTTON
                                    $("#xero_integration").parent('div').removeAttr('disabled'); //remove DISABLE CLASS ON XERO BUTTON
                                }
                                $("#dvLoading").fadeOut("slow");
                                
                            },
                            error:function(data){
                                console.log('Ajax request not recieved!');
                                $("#dvLoading").fadeOut("slow")                
                            }
                        })
                },
                error:function(data){
                    console.log('Ajax request not recieved!');
                    $("#dvLoading").fadeOut("slow");
                }
            })
   
}

function pricingModuleStatus(data){
    var status=data;
        $("#dvLoading").fadeIn("slow");
        $.ajax({
                url: SIDE_URL + "settings/changePricingModuleStatus",
                type: "post",
                data: {
                    status:status
                },
                success: function(data) { 
                  
                        if(status=='0'){
                           $("#currency_list").css('display','none'); //HIDE CURRENCY LIST
                           $("#access_pricing_module").css("display",'none'); //PRICING MODULE DISABLE
                           $("#timesheet_module_status").prop('disabled','disabled'); //SET PROPERTY ON TIMESHEET MODULE
                           $("#timesheet_module_status").parent().addClass('disabled'); //DISABLE TIMESHEET MODULE STATUS
                           $("#timesheet_module_access").css('display','none'); //TIMESHEET MODULE ACCESS OFF
                        }else{
                           $("#currency_list").css('display','block'); //ENABLE CURRENY LIST
                           $("#access_pricing_module").css("display",'block'); //ENABLE PRICING MODULE
                           $("#timesheet_module_status").removeProp('disabled'); //REMOVE DISABLED PROPERTY 
                           $("#timesheet_module_status").parent().removeClass("disabled"); // REMOVE DISABLE CLASS CSS
                           $("#timesheet_module_status").parent().removeAttr("disabled"); // REMOVE DISABLE CLASS CSS
                           $("#timesheet_module_access").css('display','block'); //TIMESHEET MODULE ACCESS ON
                        }
                   $("#dvLoading").fadeOut("slow");
                                             
                },
                error:function(data){
                    console.log('Ajax request not recieved!');
                     $("#dvLoading").fadeOut("slow");                           
                }
         })
}

function addChargeablecategory(id){ 
   var is_charge; 
   var  status = $("#is_category_chargeable_"+id).is(':checked');
    if(status == true){
        is_charge = '1';
    }else{
        is_charge = '0';
    }
     $("#dvLoading").fadeIn("slow");
        $.ajax({
                url: SIDE_URL + "settings/changeCategoryChargeStatus",
                type: "post",
                data: {
                    status:is_charge,
                    category_id:id
                },
                success: function(data) { 
                     $("#dvLoading").fadeOut("slow");
                },
                error:function(data){
                    console.log('Ajax request not recieved!');
                     $("#dvLoading").fadeOut("slow");
                                                
                }
         })
}

function addChargeablesubcategory(id){
    var is_charge; 
     var  status = $("#is_sub_category_chargeable_"+id).is(':checked');
    if(status == true){
        is_charge = '1';
    }else{
        is_charge = '0';
    }
    $("#dvLoading").fadeIn("slow");
    $.ajax({
                url: SIDE_URL + "settings/changeCategoryChargeStatus",
                type: "post",
                data: {
                    status:is_charge,
                    category_id:id
                },
                success: function(data) { 
                     $("#dvLoading").fadeOut("slow");
                },
                error:function(data){
                     console.log('Ajax request not recieved!');
                      $("#dvLoading").fadeOut("slow");                           
                }
    });
}

function upadteapiaccess(status){
    $("#dvLoading").fadeIn("slow");
    $.ajax({
                url: SIDE_URL + "settings/save_application",
                type: "post",
                data: {
                    status:status,
                    client_id:$("#hidden_client_id").val()
                },
                success: function(data) { 
                    data = jQuery.parseJSON(data);
                    var view= '';
                    if(data.client_id == 0){
                        $("#app_data").empty();
                    }else{
                        view +='<div class="col-md-12 form-group">',
                        view +='<div class="col-md-3" >',
                        view +='<label class="control-label"><b>Client ID</b> </label>',
                        view +='</div>',
                        view +='<div>',
                        view +=data.client_id,
                        view +='</div></div>',
                        view +='<div class="col-md-12 form-group">',
                        view +='<div class="col-md-3">',
                        view +='<label class="control-label"><b>Client Secret</b> </label>',
                        view +='</div>',
                        view +='<div>',
                        view +=data.client_secret,
                        view +='</div></div>',
                        view +='<div class="col-md-12 form-group">',
                        view +='<div class="col-md-3">',
                        view +='<label class="control-label "><b>Token Generate URL</b> </label>',
                        view +='</div>',
                        view +='<div>',
                        view += SIDE_URL+'OAuth2/token',
                        view +='</div></div>';
                        $("#app_data").append(view);
                    }
                    $("#hidden_client_id").val(data.client_id);
                    $("#dvLoading").fadeOut("slow");
                },
                error:function(data){
                     console.log('Ajax request not recieved!');
                      $("#dvLoading").fadeOut("slow");                           
                }
         });
}

function timesheet_module_status(value){
    $("#dvLoading").fadeIn("slow");
        $.ajax({
                url: SIDE_URL + "settings/change_timesheet_status",
                type: "post",
                data: {
                    status:value
                },
                success: function(data) { 
                    if(value == '0'){
                        $("#timesheet_module_access").css('display','none'); //TIMESHEET MODULE ACCESS OFF
                        $("#xero_integration").prop('disabled','disabled'); // XERO INTEGRATION  DISABLE
                        $("#xero_integration").parent().addClass('disabled'); //ADD DISABLE CLASS ON XERO BUTTON
                    }else{
                        $("#timesheet_module_access").css('display','block'); //TIMESHEET MODULE ACCESS ON
                        $("#xero_integration").removeProp('disabled','disabled'); // XERO INTEGRATION  ENABLE
                        $("#xero_integration").parent().removeClass('disabled'); //REMOVE DISABLE CLASS ON XERO BUTTON
                        $("#xero_integration").parent().removeAttr('disabled'); //REMOVE DISABLE CLASS ON XERO BUTTON
                    }
                    $("#dvLoading").fadeOut("slow");
                },
                error:function(data){
                    console.log('Ajax request not recieved!');
                    $("#dvLoading").fadeOut("slow");
                }
         });
}

function xero_integration_status(value){
    $("#dvLoading").fadeIn("slow");
        $.ajax({
                url: SIDE_URL + "settings/update_xero_integration",
                type: "post",
                data: {
                    status:value,
                    wipe:value
                },
                success: function(data) { 
                    if(value == '0'){
                        $("#xero_integration").css('display','none'); //TIMESHEET MODULE ACCESS OFF
                        $("#xero_org").css('display','none');
                        $("#show_xero_setup").css('display','none');
                    }else{
                        $("#xero_integration").css('display','none'); //TIMESHEET MODULE ACCESS ON
                    }
                    if(value == '1'){
                    $.ajax({
                            url: SIDE_URL +'xero?authenticate=1',
                            success: function(data){
                                window.open(data,"_self");
                            }
                        });
                    }
                    $("#dvLoading").fadeOut("slow");
                },
                error:function(data){
                    console.log('Ajax request not recieved!');
                    $("#dvLoading").fadeOut("slow");
                }
         });
}
       
function updateXeroAccess(value){
        var a;
        if($('#Xero_access_'+value).is(':checked')){
             a = '1';
        }else{
             a = '0';
        }
        $.ajax({
                url: SIDE_URL + "settings/updateUserXeroAccess",
                type: "post",
                data: {
                    user_id: value,
                    access: a
                 },
                success: function(s) {
                    
                },
                error:function(s){
                    console.log('Ajax request not recieved!');
                }
        });
}       

$(document).on('change','#approver_select',function(){
    $.ajax({
	    type : 'post',
	    url : SIDE_URL+"user/set_approver",
	    data : {
                    approver_id : $("#approver_select").val(),
                    user_id : $("#user_id").val()
            },
            success: function(){}
    });  
});           
     
function error_display(val, id){
        if(val.length>=30){
           document.getElementById(id).style.display = 'block';
        }else{
           document.getElementById(id).style.display = 'none';
        }
}
$(document).ready(function(){
                $(".cstm_chkbox").parent().parent().attr('class','');
                if(xero_access_token !=''){
                    $.ajax({
                            url: SIDE_URL +'xero/testLinks?contacts=1',
                            success: function(data){
                                var data = jQuery.parseJSON(data);
                                if(data['error_code'] != 'error'){
                                    $.ajax({
                                         url: SIDE_URL +'customer/syn_new_customer',
                                         type:'post',
                                         data:{
                                           data: data  
                                         },
                                         success: function(data){
                                             
                                         }
                                      });
                                }
                            }
                    });
                }
            });
            
$(document).ready(function(){
    
        $.validator.addMethod("phoneno", function(phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 && 
            phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
        }, "Please specify a valid phone number");
	$.validator.addMethod("UserExist", function(value, element) {
                    var remote =  $.ajax({
              		url: SIDE_URL+"settings/is_company_email_exists",
			type: "post",
			async : false,
			data: {
                            value: value
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
            },"There is an existing company Email address associated with this id.");	    
        
        $("#company_info_setting").validate({ 
            errorElement: "span",
            errorClass: "help-inline",
            focusInvalid: false,
            ignore: "",
            rules: {
                company_email: {
                    required: true,
                    email: true,
                    UserExist:true
                },
                company_phone:{
                    phoneno:true
                },
                company_country:{
                    required:true
                }
            },
            message:{
                    company_email:{
                         required:"This field is required",
                         email:"Please enter a valid email address"
                    }

            },
            submitHandler: function(data){
                  $("#dvLoading").fadeIn("slow"),
                  $.ajax({
                    type: "post",
                    url: SIDE_URL + "settings/save_company_info",
                    data: {
                        info:$("#company_info_setting").serialize()
                    },
                    success: function(e) {
                        $("#change_company_name").text(e);
                        $("#dvLoading").fadeOut("slow");
                        toastr['success']("Settings saved successfully.", "");
                    },
                    error:function(e){
                        $("#dvLoading").fadeOut("slow");
                    }
                });
            }
        });
}); 


function add_customer_user_modal(){
            $("#parent_customer").find("option[value='']").prop("selected", "selected");
            $('#parent_customer').trigger('chosen:updated');
            $("#customer_user_id").val('');
            $("#customer_user_first").val('');
            $("#customer_user_first-error").remove();
            $("#customer_user_last").val('');
            $("#customer_user_last-error").remove();
            $("#customer_user_mail").val('');
            $("#customer_user_mail-error").remove();
            $("#parent_customer-error").remove();
            $("#parent_customer_users_list").show();
            $("#access_page").val('Admin');
            $("#customer_user_save").show();
            $("#customer_user_update").hide();
            $("#customerUsermodal").modal('show');  
        
}

function delete_customer_user(customer_user_id){
    console.log('hi');
    var s = "Are you sure, you want to delete this customer user?";
    $('#delete_customer_user_'+customer_user_id).confirmation('show').on('confirmed.bs.confirmation',function(){
        console.log($(this).id);
        $("#dvLoading").fadeIn("slow");
        void $.ajax({
            type: "post",
            url: SIDE_URL + "settings/delete_customerUser",
            data: {
                customer_user_id : customer_user_id,
            },
            success: function(a) { 
                $("#dvLoading").fadeOut("slow");
                if(a == '1'){
                    $("#customerUser_"+customer_user_id).remove();
                    alertify.set('notifier','position', 'top-right');
                    alertify.log("External user has been deleted successfully.");
                }else{
                    $("#alertify").show(),
                    alertify.alert("You can not delete Customer user due to some task already assigned to it.");
                }
               
            },
            error:function(a){
                 console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
            }
        });
    });
}

function edit_customer_user(customer_user_id){
    $.ajax({
        type: "post",
        url: SIDE_URL + "settings/get_one_customer_user_info",
        data: {
            customerUser_id:customer_user_id
        },
        success: function(e) {
            var data = jQuery.parseJSON(e); 
            $("#parent_customer").find("option[value='"+data.user_info.customer_user_id+"']").prop("selected", "selected");
            $('#parent_customer').trigger('chosen:updated');
            $("#customer_user_id").val(customer_user_id);
            $("#customer_user_first").val(data.user_info.first_name);
            $("#customer_user_first-error").remove();
            $("#customer_user_last").val(data.user_info.last_name);
            $("#customer_user_last-error").remove();
            $("#customer_user_mail").val(data.user_info.email);
            $("#customer_user_mail-error").remove();
            $("#parent_customer-error").remove();
            $("#parent_customer_users_list").show();
            $("#access_page").val('Admin');
            $("#customer_user_save").hide();
            $("#customer_user_update").show();
            $("#customerUsermodal").modal('show');  
        },
        error:function(a){
            console.log("Ajax request not recieved!");
        }
    });
}