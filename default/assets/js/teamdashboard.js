function getNextweek() {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/taskteam_nextweek",
        data: {
            user_id: LOG_USER_ID
        },
        success: function(a) {
            $("#sortableItem_3").html(a), $("#dvLoading").fadeOut("slow")
        },
        error: function(a) {
            console.log("Ajax request not recieved!")
        }
    })
}

function getPreviousweek() {
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/taskteam_previousweek",
        data: {
            user_id: LOG_USER_ID
        },
        success: function(a) {
            $("#sortableItem_3").html(a), $("#dvLoading").fadeOut("slow")
        },
        error: function(a) {
            console.log("Ajax request not recieved!")
        }
    })
}

function teamDashboardFilterSet() {
    var a = $("#teamdashboard_filter_task_priority").val(),
        t = $("#teamdashboard_filter_duration").val();
    $("#dvLoading").fadeIn("slow"), $.ajax({
        type: "post",
        url: SIDE_URL + "user/team_todo_Ajax",
        data: {
            type: a,
            duration: t
        },
        success: function(a) {
            setCookie("teamdashboard_priority",$("#teamdashboard_filter_task_priority").val(),1);
            setCookie("teamdashboard_duration",$("#teamdashboard_filter_duration").val(),1);
            $("#filtertab1_in").html(a), $("#dashboard_priority").val($("#teamdashboard_filter_task_priority").val()), $("#dashboard_duration").val($("#teamdashboard_filter_duration").val()), $("#filtertab1").dataTable({
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
        error: function(a) {
            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
        }
    })
}
$(function() {
    $("#redirect_page").val("from_teamdashboard"), $("#dashboard_priority").val($("#teamdashboard_filter_task_priority").val()), $("#dashboard_duration").val($("#teamdashboard_filter_duration").val()), $(".scrollbaar").slimScroll({
        color: "#17A3E9",
        wheelStep: 20,
        height: "330px"
    }), $(".scrollbaar1").slimScroll({
        color: "#17A3E9",
        wheelStep: 20,
        height: "370px"
    }), $(".scrollbaar2").slimScroll({
        color: "#17A3E9",
        wheelStep: 20,
        height: "320px"
    }), $("#rightList_teamDashboard").sortable({
        items: "> :not(.unsorttd)",
        connectWith: ".connectedList",
        forcePlaceholderSize: !0,
        placeholder: "drag-place-holder",
        dropOnEmpty: !0,
        start: function(a, t) {},
        update: function(a, t) {
            $("#rightList_teamDashboard > div:nth-child(1)").addClass("margin-class"), $("#rightList_teamDashboard > div:nth-child(2)").removeClass("margin-class"), $("#rightList_teamDashboard > div:nth-child(3)").addClass("margin-class"), $("#rightList_teamDashboard > div:nth-child(4)").removeClass("margin-class"), $("#rightList_teamDashboard > div:nth-child(5)").addClass("margin-class"), $("#rightList_teamDashboard > div:nth-child(6)").removeClass("margin-class");
            var e = [];
            $("#rightList_teamDashboard").children().each(function() {
                e.push(this.id)
            }), $.ajax({
                type: "post",
                url: SIDE_URL + "user/updateTiles_teamDashboard",
                data: {
                    ids: e
                },
                success: function(a) {},
                error: function(a) {
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
    })
});