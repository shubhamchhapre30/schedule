function recuurence_click() {
    $("input[name='recurrence_type']").closest("span").removeClass("checked"), $("input[name='recurrence_type']").prop("checked", !1), $("#recurrence_div").css("display", "block"), $("#daily_chk").closest("span").addClass("checked"), $("#daily_chk").prop("checked", !0), $("#daily_div").show(), $("#weekly_div").hide(), $("#monthly_div").hide(), $("#yearly_div").hide(), $('input[name="Daily_every_weekday"]').closest("span").parent("div").removeClass("disabled"), $("#daily_div :input").attr("disabled", !1), $("#Daily_every_weekday").closest("span").addClass("checked"), $("#Daily_every_weekday").prop("checked", !0), $("#Daily_every_day").val("1"), $("#Daily_every_week_day").attr("disabled", !0), $("#end_by_date").val(""), $("#end_after_recurrence").val(""), $("#no_end_date2").closest("span").removeClass("checked"), $("#no_end_date3").closest("span").removeClass("checked"), $("#no_end_date1").closest("span").addClass("checked"), $("input[name='no_end_date']").removeAttr("checked", "checked"), $("input[name='no_end_date']").prop("checked", !1), $("#no_end_date1").attr("checked", "checked"), $("#no_end_date1").prop("checked", !0), $("#hdn_no_end_date").val("1")
}

function recurrence_type_click() {
    $("#end_by_date").val(""), $("#end_after_recurrence").val(""), $("#no_end_date2").closest("span").removeClass("checked"), $("#no_end_date3").closest("span").removeClass("checked"), $("#no_end_date1").closest("span").addClass("checked"), $("input[name='no_end_date']").removeAttr("checked", "checked"), $("input[name='no_end_date']").prop("checked", !1), $("#no_end_date1").attr("checked", "checked"), $("#no_end_date1").prop("checked", !0), $("#hdn_no_end_date").val("1")
}

function daily_chk_click() {
    $("#datepicker_end_by").datepicker("setStartDate", "+1d"), $("#datepicker_end_by").datepicker("setFormat", DEFAULT_FORMAT), $("#daily_div").css("display", "block"), $("#weekly_div").css("display", "none"), $("#monthly_div").css("display", "none"), $("#yearly_div").css("display", "none"), $("#daily_div :input").attr("disabled", !1), $("#weekly_div :input").attr("disabled", !0), $("#monthly_div :input").attr("disabled", !0), $("#yearly_div :input").attr("disabled", !0), $("#Daily_every_weekday").is(":checked") ? ($("#Daily_every_day").val("1"), $("#Daily_every_day").attr("disabled", !1), $("#Daily_every_week_day").attr("disabled", !0)) : $("#Daily_every_weekday2").is(":checked") ? ($("#Daily_every_day").attr("disabled", !0), $("#Daily_every_week_day").attr("disabled", !1)) : ($("#Daily_every_weekday").closest("span").addClass("checked"), $("#Daily_every_weekday").attr("checked", "checked"), $("#Daily_every_weekday").prop("checked", !0), $("#Daily_every_day").val("1"), $("#Daily_every_week_day").attr("disabled", !0)), Frequency_ajax()
}

function weekly_chk_click() {
    var s_date = $('#task_scheduled_date').val();
    var dd = new Date(s_date).getDay();
    if(dd==0)
        dd=7;
    $("#datepicker_end_by").datepicker("setStartDate", "+7d"), $("#datepicker_end_by").datepicker("setFormat", DEFAULT_FORMAT), $("#daily_div").css("display", "none"), $("#weekly_div").css("display", "block"), $("#monthly_div").css("display", "none"), $("#yearly_div").css("display", "none"), $("#weekly_div :input").attr("disabled", !1), $("#daily_div :input").attr("disabled", !0), $("#monthly_div :input").attr("disabled", !0), $("#yearly_div :input").attr("disabled", !0), $('input[name="Weekly_week_day[]"][value="' + dd + '"]').closest("span").addClass("checked"), $('input[name="Weekly_week_day[]"][value="' + dd + '"]').prop("checked", !0), $("#Weekly_every_week_no").val("1"), Frequency_ajax()
}

function monthly_chk_click() {
    $("#monthly_radios2").closest("span").removeClass("checked"), $("#monthly_radios2").prop("checked", !1), $("#monthly_radios3").closest("span").removeClass("checked"), $("#monthly_radios3").prop("checked", !1), $("#datepicker_end_by").datepicker("setStartDate", "+1m"), $("#datepicker_end_by").datepicker("setFormat", DEFAULT_FORMAT), $("#daily_div").css("display", "none"), $("#weekly_div").css("display", "none"), $("#monthly_div").css("display", "block"), $("#yearly_div").css("display", "none"), $("#monthly_div input[name='monthly_radios']").attr("disabled", !1), $("#daily_div :input").attr("disabled", !0), $("#weekly_div :input").attr("disabled", !0), $("#yearly_div :input").attr("disabled", !0), $("#monthly_radios1").closest("span").addClass("checked"), $("#monthly_radios1").attr("checked", "checked"), $("#monthly_radios1").prop("checked", !0), $("#Monthly_op1_1").val("1"), $("#Monthly_op1_2").val("1"), $("#monthly_radios1").is(":checked") ? ($("#Monthly_op1_1").attr("disabled", !1), $("#Monthly_op1_2").attr("disabled", !1), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0)) : $("#monthly_radios2").is(":checked") ? ($("#Monthly_op1_1").attr("disabled", !0), $("#Monthly_op1_2").attr("disabled", !0), $("#Monthly_op2_1").attr("disabled", !1), $("#Monthly_op2_2").attr("disabled", !1), $("#Monthly_op2_3").attr("disabled", !1), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0)) : $("#monthly_radios3").is(":checked") ? ($("#Monthly_op1_1").attr("disabled", !0), $("#Monthly_op1_2").attr("disabled", !0), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !1), $("#Monthly_op3_2").attr("disabled", !1)) : ($("#Monthly_op1_1").attr("disabled", !1), $("#Monthly_op1_2").attr("disabled", !1), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0), $("#Monthly_op1_1").val("1"), $("#Monthly_op1_2").val("1")), Frequency_ajax()
}

function yearly_chk_click() {
    $("#yearly_radios2").closest("span").removeClass("checked"), $("#yearly_radios2").prop("checked", !1), $("#yearly_radios3").closest("span").removeClass("checked"), $("#yearly_radios3").prop("checked", !1), $("#yearly_radios4").closest("span").removeClass("checked"), $("#yearly_radios4").prop("checked", !1), $("#datepicker_end_by").datepicker("setStartDate", "+1y"), $("#datepicker_end_by").datepicker("setFormat", DEFAULT_FORMAT), $("#daily_div").css("display", "none"), $("#weekly_div").css("display", "none"), $("#monthly_div").css("display", "none"), $("#yearly_div").css("display", "block"), $("#yearly_div input[name='yearly_radios']").attr("disabled", !1), $("#daily_div :input").attr("disabled", !0), $("#weekly_div :input").attr("disabled", !0), $("#monthly_div :input").attr("disabled", !0), $("#yearly_radios1").closest("span").addClass("checked"), $("#Yearly_op1").attr("checked", "checked"), $("#Yearly_op1").prop("checked", !0), $("#Yearly_op1").val("1"), $("#yearly_radios1").is(":checked") ? ($("#Yearly_op1").attr("disabled", !1), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0), $("#Yearly_op1").val("1")) : $("#yearly_radios2").is(":checked") ? ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !1), $("#Yearly_op2_2").attr("disabled", !1), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)) : $("#yearly_radios3").is(":checked") ? ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !1), $("#Yearly_op3_2").attr("disabled", !1), $("#Yearly_op3_3").attr("disabled", !1), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0)) : $("#yearly_radios4").is(":checked") ? ($("#Yearly_op1").attr("disabled", !0), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !1), $("#Yearly_op4_2").attr("disabled", !1)) : ($("#Yearly_op1").attr("disabled", !1), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0), $("#Yearly_op1").val("1")), Frequency_ajax()
}

function Daily_op2_click() {
    $("input[name='Daily_every_day']").attr("disabled", !0), $("#Daily_every_week_day").attr("disabled", !1), $("#Daily_every_week_day").val("1"), Frequency_ajax()
}

function Daily_op1_click() {
    $("#Daily_every_day").attr("disabled", !1), $("#Daily_every_day").val("1"), $("#Daily_every_week_day").attr("disabled", !0), Frequency_ajax()
}

function Monthly_op1_click() {
    $("#Monthly_op1_1").attr("disabled", !1), $("#Monthly_op1_2").attr("disabled", !1), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0), $("#Monthly_op1_1").val("1"), $("#Monthly_op1_2").val("1"), Frequency_ajax()
}

function Monthly_op2_click() {
    $("#Monthly_op1_1").attr("disabled", !0), $("#Monthly_op1_2").attr("disabled", !0), $("#monthly_radios2").is(":checked") && ($("#Monthly_op2_1 option:eq(0)").prop("selected", !0), $("#Monthly_op2_2 option:eq(0)").prop("selected", !0), $("#Monthly_op2_3").val("1")), $("#Monthly_op2_1").attr("disabled", !1), $("#Monthly_op2_2").attr("disabled", !1), $("#Monthly_op2_3").attr("disabled", !1), $("#Monthly_op3_1").attr("disabled", !0), $("#Monthly_op3_2").attr("disabled", !0), Frequency_ajax()
}

function Monthly_op3_click() {
    $("#Monthly_op1_1").attr("disabled", !0), $("#monthly_radios3").is(":checked") && ($("#Monthly_op3_1 option:eq(0)").prop("selected", !0), $("#Monthly_op3_2").val("1")), $("#Monthly_op1_2").attr("disabled", !0), $("#Monthly_op2_1").attr("disabled", !0), $("#Monthly_op2_2").attr("disabled", !0), $("#Monthly_op2_3").attr("disabled", !0), $("#Monthly_op3_1").attr("disabled", !1), $("#Monthly_op3_2").attr("disabled", !1), Frequency_ajax()
}

function Yearly_op1_click() {
    $("#Yearly_op1").attr("disabled", !1), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0), $("#Yearly_op1").val("1"), Frequency_ajax()
}

function Yearly_op2_click() {
    $("#Yearly_op1").attr("disabled", !0), $("#yearly_radios2").is(":checked") && ($("#Yearly_op2_1 option:eq(0)").prop("selected", "selected"), $("#Yearly_op2_2").val("1")), $("#Yearly_op2_1").attr("disabled", !1), $("#Yearly_op2_2").attr("disabled", !1), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0), Frequency_ajax()
}

function Yearly_op3_click() {
    $("#Yearly_op1").attr("disabled", !0), $("#yearly_radios3").is(":checked") && ($("#Yearly_op3_1 option:eq(0)").prop("selected", "selected"), $("#Yearly_op3_2 option:eq(0)").prop("selected", "selected"), $("#Yearly_op3_3 option:eq(0)").prop("selected", "selected")), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !1), $("#Yearly_op3_2").attr("disabled", !1), $("#Yearly_op3_3").attr("disabled", !1), $("#Yearly_op4_1").attr("disabled", !0), $("#Yearly_op4_2").attr("disabled", !0), Frequency_ajax()
}

function Yearly_op4_click() {
    $("#Yearly_op1").attr("disabled", !0), $("#yearly_radios4").is(":checked") && ($("#Yearly_op4_1 option:eq(0)").prop("selected", "selected"), $("#Yearly_op4_2 option:eq(0)").prop("selected", "selected")), $("#Yearly_op2_1").attr("disabled", !0), $("#Yearly_op2_2").attr("disabled", !0), $("#Yearly_op3_1").attr("disabled", !0), $("#Yearly_op3_2").attr("disabled", !0), $("#Yearly_op3_3").attr("disabled", !0), $("#Yearly_op4_1").attr("disabled", !1), $("#Yearly_op4_2").attr("disabled", !1), Frequency_ajax()
}

function NoEndDate1() {
    $("#hdn_no_end_date").val("1"), $("#end_after_recurrence").attr("disabled", !0), $("#end_by_date").attr("disabled", !0), $("#end_after_recurrence").val(""), $("#end_by_date").val(""), $.ajax({
        type: "post",
        url: SIDEURL + "task/set_end_date",
        data: $("#frm_add_recurrence").serialize(),
        success: function(e) {
            var e = jQuery.parseJSON(e);
            $("#start_on_date").val(e.start_date), e.start_date && $("#start_on_date_picker").datepicker("update", e.start_date), $("#start_on_date_picker").datepicker("refresh"), $("#hdn_no_end_date").val("1")
        }
    })
}

function NoEndDate2() {
    $("#end_after_recurrence").removeAttr("disabled"), $("#end_by_date").removeAttr("disabled"), $("#end_after_recurrence").val("1"), $("#hdn_no_end_date").val("2"), Frequency_ajax()
}

function NoEndDate3() {
    $("#end_after_recurrence").removeAttr("disabled"), $("#end_by_date").removeAttr("disabled"), $("#end_by_date").val() ? $("#hdn_no_end_date").val("3") : ($("#end_after_recurrence").val("1"), $("#hdn_no_end_date").val("2")), Frequency_ajax()
}

function Frequency_ajax() {
    $$.ajax({
        type: "post",
        url: SIDEURL + "task/set_end_date",
        data: $("#frm_add_recurrence").serialize(),
        success: function(e) {
            var e = jQuery.parseJSON(e);
            "0" != e.end_after_recurrence ? ($("#end_after_recurrence").val(e.end_after_recurrence), e.start_date && ($("#start_on_date").val(e.start_date), $("#start_on_date_picker").datepicker("update", e.start_date), $("#start_on_date_picker").datepicker("refresh")), e.end_date && ($("#end_by_date").val(e.end_date), $("#datepicker_end_by").datepicker("update", e.end_date), $("#datepicker_end_by").datepicker("refresh"))) : ($("#end_after_recurrence").val(""), $("#start_on_date").val(""), $("#end_by_date").val(""), $("#no_end_date2").closest("span").removeClass("checked"), $("#no_end_date3").closest("span").removeClass("checked"), $("#no_end_date1").closest("span").addClass("checked"), $("input[name='no_end_date']").removeAttr("checked", "checked"), $("input[name='no_end_date']").prop("checked", !1), $("#no_end_date1").attr("checked", "checked"), $("#no_end_date1").prop("checked", !0), alertify.alert("Oops! There is no possibility to occur recurrence , Can you try again?"))
        }
    })
}