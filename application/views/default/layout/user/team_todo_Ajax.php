<script type="text/javascript">
	$(document).ready(function(){
		$(".teamdoDueDatepicker").datepicker({
	        startDate: -Infinity,
		    format: JAVASCRIPT_DATE_FORMAT,
	   	}).on('changeDate', function(date) {
	   		$(this).datepicker('hide');
	   		updateDueDate(date.date,$(this).attr('id'));
	   	});
	 
		$(".teamSchedulledDatepicker").datepicker({
	        startDate: -Infinity,
		    format: JAVASCRIPT_DATE_FORMAT,
	   	}).on('changeDate', function(date) {
	   		$(this).datepicker('hide');
	   		updateSchedulledDate(date.date,$(this).attr('id'));
	   	});
	});
	function updateSchedulledDate(date,id){
		function pad(s) { return (s < 10) ? '0' + s : s; }
		var d = date;
		var sel_date = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
		id = id.replace('teamSchedulled_', '');
		
		$.ajax({
			type:'post',
			url : SIDE_URL+'task/updateSchedulledDate',
			async : false,
			data : {date:sel_date, task_id:id, post_data: $("#task_data_"+id).val(), redirect_page : $("#redirect_page").val(), type : $("#teamdashboard_filter_task_priority").val(), duration : $("#teamdashboard_filter_duration").val()},
			success : function(responseData){
				responseData = jQuery.parseJSON(responseData);
				function firstToUpperCase( str ) {
				    return str.substr(0, 1).toUpperCase() + str.substr(1);
				}
					
				if(responseData.is_div_valid == 1){
	
					var task_title = responseData.task_data.task_title;
					if(task_title > 25){
						var title_str = task_title.substring(0,22)+'...';
					} else {
						var title_str = task_title;
					}
					var status_name = responseData.task_status_name;
					status_class = status_name.toLowerCase();
					status_class = status_class.replace(" ", "");

					var task_str = '';
						task_str += '<tr id="teamtodo_'+responseData.task_data.task_id+'" role="row" class="even">';
						task_str += '<td title="'+responseData.task_data.task_description+'">';

						if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
   							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+title_str+'</a></td>';
   						} else {
   							task_str += '<a  data-placement="right" data-original-title="'+title_str+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a></td>';
   						}

						task_str += '<td class="teamdoDueDatepicker" id="teamDoDue_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_due_date+'</span><span class="date_edit">'+responseData.user_due_date+'</span></td>';
						task_str += '<td class="teamSchedulledDatepicker" id="teamSchedulled_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_scheduled_date+'</span><span class="date_edit">'+responseData.user_scheduled_date+'</span></td>';
						task_str += '<td>'+responseData.task_data.task_priority+'</td>';
						task_str += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
                        task_str += '<td><span class="label label-'+status_class+'">'+status_name+'</span></td>';
                        task_str += '<input type="hidden" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" id="task_data_'+responseData.task_data.task_id+'">';
                        task_str += '</tr>';

                   	$("#teamtodo_"+id).replaceWith(task_str);
                   	
                   	$(".teamdoDueDatepicker").datepicker({
				        startDate: -Infinity,
					    format: JAVASCRIPT_DATE_FORMAT,
				   	}).on('changeDate', function(date) {
				   		$(this).datepicker('hide');
				   		updateDueDate(date.date,$(this).attr('id'));
				   	});
				 
					$(".teamSchedulledDatepicker").datepicker({
				        startDate: -Infinity,
					    format: JAVASCRIPT_DATE_FORMAT,
				   	}).on('changeDate', function(date) {
				   		$(this).datepicker('hide');
				   		updateSchedulledDate(date.date,$(this).attr('id'));
				   	});
                    
				} else {
					if($("#teamtodo_"+id).length){
                		$("#teamtodo_"+id).remove();
                    }
                    if($("#teamtodolist tr td").length == 0){
           				$("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
           			}
				}
				
				if(responseData.task_data.task_allocated_user_id != LOG_USER_ID){
					if(responseData.today_date <= responseData.strtotime_scheduled_date){
                   		var task_title = responseData.task_data.task_title;
   						if(task_title.length > 25){
   							var title_str = task_title.substring(0,22)+'...';
   						} else {
   							var title_str = task_title;
   						}
   						var status_name = responseData.task_status_name;
   						status_class = status_name.toLowerCase();
   						status_class = status_class.replace(" ", "");

   						var task_str = '';
   							task_str += '<tr id="teampending_'+responseData.task_data.task_id+'" role="row" class="even">';
   							task_str += '<td title="'+responseData.task_data.task_description+'">';


   							if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a></td>';
       						} else {
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\'0\');" href="javascript:void(0)">'+firstToUpperCase(task_title)+'</a></td>';
       						}


   							task_str += '<td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';
    						task_str += '<td>'+responseData.task_owner_name+'.</td>';

    						task_str += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
    						task_str += '<input type="hidden" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" id="task_data_'+responseData.task_data.task_id+'">';
                            task_str += '</tr>';
                             // alert(task_str);
						if($("#teampending_"+id).length){
                    		$("#teampending_"+id).replaceWith(task_str);
                        } else {
                        	if($("#teampending_list tr td.dataTables_empty").length){
                        		$("#teampending_list tr td.dataTables_empty").remove();
                        	}
                        	$("#teampending_list").append(task_str);
                        	if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
                    			var new_row = $('#filtertab2').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+title_str+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_owner_name,
                    				responseData.task_allocated_user_name
                    			]);
                    		} else {
                    			var new_row = $('#filtertab2').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+title_str+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_owner_name,
                    				responseData.task_allocated_user_name
                    			]);
                    		}
                    		var row = $('#filtertab2').dataTable().fnGetNodes(new_row);
							$(row).attr('id', "teampending_"+responseData.task_data.task_id);
                        }

   					} else {
   						if($("#teampending_"+id).length){
                    		$("#teampending_"+id).remove();
                        }
                        if($("#teampending_list tr td").length == 0){
	           				$("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	           			}
   					}
   					
   					if(responseData.today_date > responseData.strtotime_due_date){

						var task_title = responseData.task_data.task_title;
   						if(task_title.length > 25){
   							var title_str = task_title.substring(0,22)+'...';
   						} else {
   							var title_str = task_title;
   						}
   						var status_name = responseData.task_status_name;
   						status_class = status_name.toLowerCase();
   						status_class = status_class.replace(" ", "");

   						var task_str = '';
   							task_str += '<tr id="teamoverdue_'+responseData.task_data.task_id+'" role="row" class="even">';
   							task_str += '<td title="'+responseData.task_data.task_description+'">';


   							if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a></td>';
       						} else {
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\'0\');" href="javascript:void(0)">'+firstToUpperCase(task_title)+'</a></td>';
       						}



   							task_str += '<td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';

    						task_str += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
    						task_str += '<td>'+responseData.delay+'</td>';
    						task_str += '<td>'+responseData.task_data.task_priority+'</td>';
    						task_str += '<input type="hidden" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" id="task_data_'+responseData.task_data.task_id+'">';
    						task_str += '</tr>';

    					if($("#teamoverdue_"+id).length){
    						if(responseData.task_data.is_personal!='1'){
        						$("#teamoverdue_"+id).replaceWith(task_str);
        						}else{
        						$("#teamoverdue_"+id).remove();
        						}
                        } else {

                        	if($("#teamoverdue_list tr td.dataTables_empty").length){
                        		$("#teamoverdue_list tr td.dataTables_empty").remove();
                        	}
                        	$("#teamoverdue_list").append(task_str);
                        	
                            if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
                    			var new_row = $('#filtertab3').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+title_str+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_allocated_user_name,
                    				responseData.delay,
                    				responseData.task_data.task_priority
                    			]);
                    		} else {
                    			var new_row = $('#filtertab3').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+title_str+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_allocated_user_name,
                    				responseData.delay,
                    				responseData.task_data.task_priority
                    			]);
                    		}
                    		var row = $('#filtertab3').dataTable().fnGetNodes(new_row);
							$(row).attr('id', "teamoverdue_"+responseData.task_data.task_id);
                        }

   					} else {
   						if($("#teamoverdue_"+id).length){
                    		$("#teamoverdue_"+id).remove();
                        }
                        if($("#teamoverdue_list tr td").length == 0){
	           				$("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	           			}
   					}
				}
				
			}
		});
	}
	
	function updateDueDate(date,id){
		function pad(s) { return (s < 10) ? '0' + s : s; }
		var d = date;
		var sel_date = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
		id = id.replace('teamDoDue_', '');
		$.ajax({
			type:'post',
			url : SIDE_URL+'task/updateDueDate',
			async : false,
			data : {date:sel_date, task_id:id, post_data: $("#task_data_"+id).val(), redirect_page : $("#redirect_page").val(), type : $("#teamdashboard_filter_task_priority").val(), duration : $("#teamdashboard_filter_duration").val()},
			success : function(responseData){
				responseData = jQuery.parseJSON(responseData);
				function firstToUpperCase( str ) {
				    return str.substr(0, 1).toUpperCase() + str.substr(1);
				}
				if(responseData.is_div_valid == 1){
	
					var task_title = responseData.task_data.task_title;
					if(task_title > 25){
						var title_str = task_title.substring(0,22)+'...';
					} else {
						var title_str = task_title;
					}
					var status_name = responseData.task_status_name;
					status_class = status_name.toLowerCase();
					status_class = status_class.replace(" ", "");

					var task_str = '';
						task_str += '<tr id="teamtodo_'+responseData.task_data.task_id+'" role="row" class="even">';
						task_str += '<td title="'+responseData.task_data.task_description+'">';

						if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
   							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+title_str+'</a></td>';
   						} else {
   							task_str += '<a  data-placement="right" data-original-title="'+title_str+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a></td>';
   						}

						task_str += '<td class="teamdoDueDatepicker" id="teamDoDue_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_due_date+'</span><span class="date_edit">'+responseData.user_due_date+'</span></td>';
						task_str += '<td class="teamSchedulledDatepicker" id="teamSchedulled_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_scheduled_date+'</span><span class="date_edit">'+responseData.user_scheduled_date+'</span></td>';
						task_str += '<td>'+responseData.task_data.task_priority+'</td>';
						task_str += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
                        task_str += '<td><span class="label label-'+status_class+'">'+status_name+'</span></td>';
                        task_str += '<input type="hidden" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" id="task_data_'+responseData.task_data.task_id+'">';
                        task_str += '</tr>';

                   	$("#teamtodo_"+id).replaceWith(task_str);
                   	
                   	$(".teamdoDueDatepicker").datepicker({
				        startDate: -Infinity,
					    format: JAVASCRIPT_DATE_FORMAT,
				   	}).on('changeDate', function(date) {
				   		$(this).datepicker('hide');
				   		updateDueDate(date.date,$(this).attr('id'));
				   	});
				 
					$(".teamSchedulledDatepicker").datepicker({
				        startDate: -Infinity,
					    format: JAVASCRIPT_DATE_FORMAT,
				   	}).on('changeDate', function(date) {
				   		$(this).datepicker('hide');
				   		updateSchedulledDate(date.date,$(this).attr('id'));
				   	});
                    
				} else {
					if($("#teamtodo_"+id).length){
                		$("#teamtodo_"+id).remove();
                    }
                    if($("#teamtodolist tr td").length == 0){
           				$("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
           			}
				}
				
				if(responseData.task_data.task_allocated_user_id != LOG_USER_ID){
					if(responseData.today_date <= responseData.strtotime_scheduled_date){
                   		var task_title = responseData.task_data.task_title;
   						if(task_title.length > 25){
   							var title_str = task_title.substring(0,22)+'...';
   						} else {
   							var title_str = task_title;
   						}
   						var status_name = responseData.task_status_name;
   						status_class = status_name.toLowerCase();
   						status_class = status_class.replace(" ", "");

   						var task_str = '';
   							task_str += '<tr id="teampending_'+responseData.task_data.task_id+'" role="row" class="even">';
   							task_str += '<td title="'+responseData.task_data.task_description+'">';


   							if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a></td>';
       						} else {
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\'0\');" href="javascript:void(0)">'+firstToUpperCase(task_title)+'</a></td>';
       						}


   							task_str += '<td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';
    						task_str += '<td>'+responseData.task_owner_name+'.</td>';

    						task_str += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
    						task_str += '<input type="hidden" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" id="task_data_'+responseData.task_data.task_id+'">';
                            task_str += '</tr>';
                             // alert(task_str);
						if($("#teampending_"+id).length){
                    		$("#teampending_"+id).replaceWith(task_str);
                        } else {
                        	if($("#teampending_list tr td.dataTables_empty").length){
                        		$("#teampending_list tr td.dataTables_empty").remove();
                        	}
                        	$("#teampending_list").append(task_str);
                        	if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
                    			var new_row = $('#filtertab2').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+title_str+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_owner_name,
                    				responseData.task_allocated_user_name
                    			]);
                    		} else {
                    			var new_row = $('#filtertab2').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+title_str+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_owner_name,
                    				responseData.task_allocated_user_name
                    			]);
                    		}
                    		var row = $('#filtertab2').dataTable().fnGetNodes(new_row);
							$(row).attr('id', "teampending_"+responseData.task_data.task_id);
                        }

   					} else {
   						if($("#teampending_"+id).length){
                    		$("#teampending_"+id).remove();
                        }
                        if($("#teampending_list tr td").length == 0){
	           				$("#teampending_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	           			}
   					}
   					
   					if(responseData.today_date > responseData.strtotime_due_date){

						var task_title = responseData.task_data.task_title;
   						if(task_title.length > 25){
   							var title_str = task_title.substring(0,22)+'...';
   						} else {
   							var title_str = task_title;
   						}
   						var status_name = responseData.task_status_name;
   						status_class = status_name.toLowerCase();
   						status_class = status_class.replace(" ", "");

   						var task_str = '';
   							task_str += '<tr id="teamoverdue_'+responseData.task_data.task_id+'" role="row" class="even">';
   							task_str += '<td title="'+responseData.task_data.task_description+'">';


   							if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a></td>';
       						} else {
       							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\'0\');" href="javascript:void(0)">'+firstToUpperCase(task_title)+'</a></td>';
       						}



   							task_str += '<td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';

    						task_str += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
    						task_str += '<td>'+responseData.delay+'</td>';
    						task_str += '<td>'+responseData.task_data.task_priority+'</td>';
    						task_str += '<input type="hidden" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" id="task_data_'+responseData.task_data.task_id+'">';
    						task_str += '</tr>';

    					if($("#teamoverdue_"+id).length){
    						if(responseData.task_data.is_personal!='1'){
        						$("#teamoverdue_"+id).replaceWith(task_str);
        						}else{
        						$("#teamoverdue_"+id).remove();
        						}
                        } else {

                        	if($("#teamoverdue_list tr td.dataTables_empty").length){
                        		$("#teamoverdue_list tr td.dataTables_empty").remove();
                        	}
                        	$("#teamoverdue_list").append(task_str);
                        	
                            if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
                    			var new_row = $('#filtertab3').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+title_str+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_allocated_user_name,
                    				responseData.delay,
                    				responseData.task_data.task_priority
                    			]);
                    		} else {
                    			var new_row = $('#filtertab3').dataTable().fnAddData( [
                    				'<a data-placement="right" data-original-title="'+title_str+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(title_str)+'</a><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" />',
                    				'<span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date,
                    				responseData.task_allocated_user_name,
                    				responseData.delay,
                    				responseData.task_data.task_priority
                    			]);
                    		}
                    		var row = $('#filtertab3').dataTable().fnGetNodes(new_row);
							$(row).attr('id', "teamoverdue_"+responseData.task_data.task_id);
                        }

   					} else {
   						if($("#teamoverdue_"+id).length){
                    		$("#teamoverdue_"+id).remove();
                        }
                        if($("#teamoverdue_list tr td").length == 0){
	           				$("#teamoverdue_list").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	           			}
   					}
				}
			}
		});
	}
</script>

<table id="filtertab1"  class="table tabrd table-striped table-hover table-condensed flip-content ">
                    <thead class="flip-content">
                      <tr>
                        <th>Task</th>
                        <th>Due Date</th>
                        <th>Scheduled Date</th>
                        <th>Priority <!--<a href="javascript:;"> <i class="stripicon icondwonarro"></i> </a>--></th>
                        <th class="hidden-480">Allocated <!--<a href="javascript:;"> <i class=" stripicon iconfilter"></i> </a>--></th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody id='teamtodolist'>

<?php 
$bucket = $this->config->item('bucket_name');
        $s3_display_url = $this->config->item('s3_display_url');
if($teamtodolist!='0'){
		 foreach ($teamtodolist as $t) {
		 	$t = (object)$t;
			$user_name = ucwords($t->first_name)." ".ucwords($t->last_name[0]).".";
			
			if($t->task_due_date!= '0000-00-00' ){
				$due_dt = date($site_setting_date,strtotime($t->task_due_date));
				$hidden_due_date = date("Y-m-d",strtotime($t->task_due_date));
			} else {
				$due_dt = "N/A";
				$hidden_due_date = "N/A";
			}
			
			if($t->task_scheduled_date!= '0000-00-00' ){
				$scheduled_dt = date($site_setting_date,strtotime($t->task_scheduled_date));
				$hidden_scheduled_date = date("Y-m-d",strtotime($t->task_scheduled_date));
			} else {
				$scheduled_dt = "N/A";
				$hidden_scheduled_date = "N/A";
			}
			
			if (strpos($t->task_id,'child') !== false) {
			    $chk = "0";
			} else {
				$chk = "1";
			}
			
			$is_master_deleted = $t->tm;
			
		  ?>
  <tr id="teamtodo_<?php echo $t->task_id;?>">
    <td title="<?php echo $t->task_description;?>">
    	<?php if($t->master_task_id == '0' || $is_master_deleted=="1"){ ?>
			<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $t->task_id;?>','<?php echo $chk;?>')" class="tooltips _<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" ><?php echo (strlen($t->task_title) > 25)?substr(ucwords($t->task_title),0, 22).'...':ucwords($t->task_title);?></a>
		<?php } else { ?>
			
			<a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $t->task_id;?>','<?php echo $t->master_task_id;?>','<?php echo $chk;?>');" class="tooltips _<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" ><?php echo (strlen($t->task_title) > 25)?substr(ucwords($t->task_title),0, 22).'...':ucwords($t->task_title);?></a>
		
		<?php } ?>
    </td>
    <td class="teamdoDueDatepicker" id="teamDoDue_<?php echo $t->task_id;?>"><span class="hidden"><?php echo $hidden_due_date;?></span><span class="date_edit"><?php echo $due_dt;?></span></td>
    <td class="teamSchedulledDatepicker" id="teamSchedulled_<?php echo $t->task_id;?>"><span class="hidden"><?php echo $hidden_scheduled_date;?></span><span class="date_edit"><?php echo $scheduled_dt;?></span></td>
    <td><?php echo $t->task_priority;?></td>
    <td class="hidden-480">
        <?php
                                           $word3 = ucfirst(substr($t->first_name,0,1));
                                         $word4 = ucfirst(substr($t->last_name,0,1));
                                           if(($t->allocated_user_profile_image != '' || $t->allocated_user_profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$t->allocated_user_profile_image)) { ?>
                                                    <img alt="" data-original-title="<?php echo ucwords($t->first_name)." ".ucwords($t->last_name);?>" class="tooltips capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$t->allocated_user_profile_image; ?>" class="profile-image" />
                                            <?php } else { ?>
                                                    <span class="tooltips" data-original-title="<?php echo ucwords($t->first_name)." ".ucwords($t->last_name);?>" data-letters="<?php echo $word3.$word4; ?>"></span>
                                            <?php } ?>
    </td>
    
     <?php 
    foreach($task_status as $ts){
	if($ts->task_status_id == $t->task_status_id){
    		
    	if($ts->task_status_name=='Not Ready')
		{
			$tsk_st = "notready";
		}
		if($ts->task_status_name=='Ready')
		{
			$tsk_st = "ready";
		}
		if($ts->task_status_name=='In Progress')
		{
			$tsk_st = "inprogress";
		}
		if($ts->task_status_name=='Completed')
		{
			$tsk_st = "completed";
		}
		if( $ts->task_status_name!='In Progress' && $ts->task_status_name!='Ready' && $ts->task_status_name!='Not Ready' && $ts->task_status_name!='Completed')
		{
			$tsk_st = "common";
		}
    ?>
   <td><span class="label label-sm label-<?php echo $tsk_st;?>"><?php echo $ts->task_status_name;?></span></td>
    <?php } } ?>
    <?php $t_arr = (array)$t;?>
    <input type="hidden" id="task_data_<?php echo $t->task_id;?>" value="<?php echo htmlspecialchars(json_encode($t_arr)); ?>" />
  </tr>
   <?php } } ?>
   
</tbody>
              </table>