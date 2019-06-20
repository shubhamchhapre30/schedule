
<script type="text/javascript">
	$(document).ready(function(){
		$(".todoSchedulledDatepicker").datepicker({
                    startDate: -Infinity,
		    format: JAVASCRIPT_DATE_FORMAT,
                    autoclose:true,
                }).on('changeDate', function(date) {
	   		$(this).datepicker('hide');
	   		updateSchedulledDate(date.date,$(this).attr('id'));
	   	});
	 
		$(".todoDueDatepicker").datepicker({
                    startDate: -Infinity,
		    format: JAVASCRIPT_DATE_FORMAT,
                    autoclose:true,
                }).on('changeDate', function(date) {
	   		$(this).datepicker('hide');
	   		updateDueDate(date.date,$(this).attr('id'));
	   		
	   	});
	});
	function updateSchedulledDate(date,id){
		function pad(s) { return (s < 10) ? '0' + s : s; }
		var d = date;
		var sel_date = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
		
		id = id.replace('schedulled_', '');
		$.ajax({
			type:'post',
			url : SIDE_URL+'task/updateSchedulledDate',
			async : false,
			data : {date:sel_date, task_id:id, post_data: $("#task_data_"+id).val(), redirect_page : $("#redirect_page").val(), type : $("#dashboard_priority").val(), duration : $("#dashboard_duration").val()},
			success : function(responseData){
				responseData = jQuery.parseJSON(responseData);
				function firstToUpperCase( str ) {
				    return str.substr(0, 1).toUpperCase() + str.substr(1);
				}
				
				if($("#watch"+id).length){
					
					var status_name = responseData.task_status_name;
   						status_class = status_name.toLowerCase();
   						status_class = status_class.replace(" ", "");
					var watch_task = '';
						watch_task += '<tr id="watch'+responseData.task_data.task_id+'" role="row" class="odd">';
						watch_task += '<td title="'+responseData.task_data.task_description+'" class="sorting_1">';


						if(responseData.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
   							watch_task += '<a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\')" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a>';
   						} else {
   							watch_task += '<a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\'0\');" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a></td>';
   						}


						watch_task += '</td>';
						watch_task += '<td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';

						watch_task += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
						watch_task += '<td><span class="label label-'+status_class+'">'+status_name+'</span></td>';

						watch_task += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\''+responseData.watch_id+'\',\''+responseData.task_data.task_id+'\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>';

					$("#watch"+id).replaceWith(watch_task);
				}

				if($("#last_login_"+id).length){

					var last_login_task = '';
						last_login_task += '<tr id="last_login_'+responseData.task_data.task_id+'" role="row" class="odd">';


						if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
           							last_login_task += '<td class="sorting_1"><a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips dashboard_master_'+responseData.task_data.master_task_id+'" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a>';
           						} else {
           							last_login_task += '<td class="sorting_1"><a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips dashboard_master_'+responseData.task_data.master_task_id+'" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a>';
           						}


						last_login_task += '</td><td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';

						last_login_task += '<td>'+responseData.task_data.task_priority+'</td>';
						last_login_task += '</tr>';

					$("#last_login_"+id).replaceWith(last_login_task);
				}
				
				
				if(responseData.assign_status == "assign_other"){
					$("#todo_"+$("#task_id").val()).remove();
	
	       			if($("#todolist tr td").length == 0){
	       				$("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	       			}
	       			$("#last_login_"+$("#task_id").val()).remove();
	       			$("#teamoverdue_"+$("#task_id").val()).remove();
	       			$("#teampending_"+$("#task_id").val()).remove();
	       			$("#teamtodo_"+$("#task_id").val()).remove();
	       			if($("#teamtodolist tr td").length == 0){
	       				$("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	       			}
				} else {
					
					if(responseData.is_div_valid == 1){
						var task_title = responseData.task_data.task_title;
						if(task_title > 40){
							var title_str = task_title.substring(0,37)+'...';
						} else {
							var title_str = task_title;
						}
						var status_name = responseData.task_status_name;
						status_class = status_name.toLowerCase();
						status_class = status_class.replace(" ", "");
	
						var task_str = '';
							task_str += '<tr id="todo_'+responseData.task_data.task_id+'" role="row" class="even">';
							task_str += '<td title="'+responseData.task_data.task_description+'" class="sorting_1">';
	   							
						if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
	
							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" data-dismiss="modal" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)" >'+firstToUpperCase(title_str)+'</a></td>';
						} else {
							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(task_title)+'</a></td>';
						}
	
						task_str += '<td class="todoDueDatepicker" id="toDoDue_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_due_date+'</span><span class="date_edit">'+responseData.user_due_date+'</span></td>';
						task_str += '<td class="todoSchedulledDatepicker" id="schedulled_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_scheduled_date+'</span><span class="date_edit">'+responseData.user_scheduled_date+'</span></td>';
						task_str += '<td>'+responseData.task_data.task_priority+'</td>';
                                                task_str += '<td><span class="label label-'+status_class+'">'+status_name+'</span></td><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" /></tr>';
						
						$("#todo_"+id).replaceWith(task_str);
						
						$(".todoSchedulledDatepicker").datepicker({
                                                    startDate: -Infinity,
						    format: JAVASCRIPT_DATE_FORMAT,
                                                    autoclose:true,
                                                }).on('changeDate', function(date) {
					   		$(this).datepicker('hide');
					   		updateSchedulledDate(date.date,$(this).attr('id'));
					   	});
					   	
					   	$(".todoDueDatepicker").datepicker({
                                                    startDate: -Infinity,
						    format: JAVASCRIPT_DATE_FORMAT,
                                                    autoclose:true,
                                                }).on('changeDate', function(date) {
					   		$(this).datepicker('hide');
					   		updateDueDate(date.date,$(this).attr('id'));
					   	});
	                    
					} else {
	                	$("#todo_"+id).remove();
	                	if($("#todolist tr td").length == 0){
	           				$("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
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
		id = id.replace('toDoDue_', '');
		$.ajax({
			type:'post',
			url : SIDE_URL+'task/updateDueDate',
			async : false,
			data : {date:sel_date, task_id:id, post_data: $("#task_data_"+id).val(), redirect_page : $("#redirect_page").val(), type : $("#dashboard_priority").val(), duration : $("#dashboard_duration").val()},
			success : function(responseData){
				responseData = jQuery.parseJSON(responseData);
				function firstToUpperCase( str ) {
				    return str.substr(0, 1).toUpperCase() + str.substr(1);
				}
				
				if($("#watch"+id).length){
					
					var status_name = responseData.task_status_name;
   						status_class = status_name.toLowerCase();
   						status_class = status_class.replace(" ", "");
					var watch_task = '';
						watch_task += '<tr id="watch'+responseData.task_data.task_id+'" role="row" class="odd">';
						watch_task += '<td title="'+responseData.task_data.task_description+'" class="sorting_1">';


						if(responseData.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
   							watch_task += '<a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\')" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a>';
   						} else {
   							watch_task += '<a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\'0\');" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a></td>';
   						}


						watch_task += '</td>';
						watch_task += '<td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';

						watch_task += '<td class="hidden-480">'+responseData.task_allocated_user_name+'.</td>';
						watch_task += '<td><span class="label label-'+status_class+'">'+status_name+'</span></td>';

						watch_task += '<td> <a data-original-title="stop following" class="tooltips" href="javascript:void(0)" onclick="delwatch(\''+responseData.watch_id+'\',\''+responseData.task_data.task_id+'\');"> <i class="stripicon icondelete2"></i> </a> </td></tr>';

					$("#watch"+id).replaceWith(watch_task);
				}

				if($("#last_login_"+id).length){

					var last_login_task = '';
						last_login_task += '<tr id="last_login_'+responseData.task_data.task_id+'" role="row" class="odd">';


						if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
           							last_login_task += '<td class="sorting_1"><a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips dashboard_master_'+responseData.task_data.master_task_id+'" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a>';
           						} else {
           							last_login_task += '<td class="sorting_1"><a  data-placement="right" data-original-title="'+responseData.task_data.task_title+'" class="tooltips dashboard_master_'+responseData.task_data.master_task_id+'" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(responseData.task_data.task_title)+'</a>';
           						}


						last_login_task += '</td><td><span class="hidden">'+responseData.task_data.task_due_date+'</span>'+responseData.user_due_date+'</td>';

						last_login_task += '<td>'+responseData.task_data.task_priority+'</td>';
						last_login_task += '</tr>';

					$("#last_login_"+id).replaceWith(last_login_task);
				}
				
				
				if(responseData.assign_status == "assign_other"){
					$("#todo_"+$("#task_id").val()).remove();
	
	       			if($("#todolist tr td").length == 0){
	       				$("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	       			}
	       			$("#last_login_"+$("#task_id").val()).remove();
	       			$("#teamoverdue_"+$("#task_id").val()).remove();
	       			$("#teampending_"+$("#task_id").val()).remove();
	       			$("#teamtodo_"+$("#task_id").val()).remove();
	       			if($("#teamtodolist tr td").length == 0){
	       				$("#teamtodolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	       			}
				} else {
					
					if(responseData.is_div_valid == 1){
						var task_title = responseData.task_data.task_title;
						if(task_title > 40){
							var title_str = task_title.substring(0,37)+'...';
						} else {
							var title_str = task_title;
						}
						var status_name = responseData.task_status_name;
						status_class = status_name.toLowerCase();
						status_class = status_class.replace(" ", "");
	
						var task_str = '';
							task_str += '<tr id="todo_'+responseData.task_data.task_id+'" role="row" class="even">';
							task_str += '<td title="'+responseData.task_data.task_description+'" class="sorting_1">';
	   							
						if(responseData.task_data.is_master_deleted == "1" || responseData.task_data.master_task_id == "0"){
	
							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" data-dismiss="modal" onclick="edit_task(this,\''+responseData.task_data.task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)" >'+firstToUpperCase(title_str)+'</a></td>';
						} else {
							task_str += '<a  data-placement="right" data-original-title="'+task_title+'" class="tooltips" onclick="open_seris(this,\''+responseData.task_data.task_id+'\',\''+responseData.task_data.master_task_id+'\',\''+responseData.is_chk+'\');" href="javascript:void(0)">'+firstToUpperCase(task_title)+'</a></td>';
						}
	
						task_str += '<td class="todoDueDatepicker" id="toDoDue_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_due_date+'</span><span class="date_edit">'+responseData.user_due_date+'</span></td>';
						task_str += '<td class="todoSchedulledDatepicker" id="schedulled_'+responseData.task_data.task_id+'"><span class="hidden">'+responseData.task_data.task_scheduled_date+'</span><span class="date_edit">'+responseData.user_scheduled_date+'</span></td>';
						task_str += '<td>'+responseData.task_data.task_priority+'</td>';
	                    task_str += '<td><span class="label label-'+status_class+'">'+status_name+'</span></td><input type="hidden" id="task_data_'+responseData.task_data.task_id+'" value="{&quot;task_id&quot;:&quot;'+responseData.task_data.task_id+'&quot;,&quot;master_task_id&quot;:&quot;'+responseData.task_data.master_task_id+'&quot;,&quot;is_prerequisite_task&quot;:&quot;'+responseData.task_data.is_prerequisite_task+'&quot;,&quot;prerequisite_task_id&quot;:&quot;'+responseData.task_data.prerequisite_task_id+'&quot;,&quot;task_company_id&quot;:&quot;'+responseData.task_data.task_company_id+'&quot;,&quot;task_project_id&quot;:&quot;'+responseData.task_data.task_project_id+'&quot;,&quot;section_id&quot;:&quot;'+responseData.task_data.section_id+'&quot;,&quot;subsection_id&quot;:&quot;'+responseData.task_data.subsection_id+'&quot;,&quot;section_order&quot;:&quot;'+responseData.task_data.section_order+'&quot;,&quot;subsection_order&quot;:&quot;'+responseData.task_data.subsection_order+'&quot;,&quot;task_order&quot;:&quot;'+responseData.task_data.task_order+'&quot;,&quot;task_title&quot;:&quot;'+responseData.task_data.task_title+'&quot;,&quot;task_description&quot;:&quot;'+responseData.task_data.task_description+'&quot;,&quot;is_personal&quot;:&quot;'+responseData.task_data.is_personal+'&quot;,&quot;task_priority&quot;:&quot;'+responseData.task_data.task_priority+'&quot;,&quot;task_status_id&quot;:&quot;'+responseData.task_data.task_status_id+'&quot;,&quot;task_division_id&quot;:&quot;'+responseData.task_data.task_division_id+'&quot;,&quot;task_department_id&quot;:&quot;'+responseData.task_data.task_department_id+'&quot;,&quot;task_category_id&quot;:&quot;'+responseData.task_data.task_category_id+'&quot;,&quot;task_sub_category_id&quot;:&quot;'+responseData.task_data.task_sub_category_id+'&quot;,&quot;task_staff_level_id&quot;:&quot;'+responseData.task_data.task_staff_level_id+'&quot;,&quot;task_skill_id&quot;:&quot;'+responseData.task_data.task_skill_id+'&quot;,&quot;task_due_date&quot;:&quot;'+responseData.task_data.task_due_date+'&quot;,&quot;task_scheduled_date&quot;:&quot;'+responseData.task_data.task_scheduled_date+'&quot;,&quot;task_orig_scheduled_date&quot;:&quot;'+responseData.task_data.task_orig_scheduled_date+'&quot;,&quot;task_orig_due_date&quot;:&quot;'+responseData.task_data.task_orig_due_date+'&quot;,&quot;is_scheduled&quot;:&quot;'+responseData.task_data.is_scheduled+'&quot;,&quot;task_time_estimate&quot;:&quot;'+responseData.task_data.task_time_estimate+'&quot;,&quot;task_owner_id&quot;:&quot;'+responseData.task_data.task_owner_id+'&quot;,&quot;task_allocated_user_id&quot;:&quot;'+responseData.task_data.task_allocated_user_id+'&quot;,&quot;locked_due_date&quot;:&quot;'+responseData.task_data.locked_due_date+'&quot;,&quot;task_time_spent&quot;:&quot;'+responseData.task_data.task_time_spent+'&quot;,&quot;frequency_type&quot;:&quot;'+responseData.task_data.frequency_type+'&quot;,&quot;recurrence_type&quot;:&quot;'+responseData.task_data.recurrence_type+'&quot;,&quot;Daily_every_day&quot;:&quot;'+responseData.task_data.Daily_every_day+'&quot;,&quot;Daily_every_weekday&quot;:&quot;'+responseData.task_data.Daily_every_weekday+'&quot;,&quot;Daily_every_week_day&quot;:&quot;'+responseData.task_data.Daily_every_week_day+'&quot;,&quot;Weekly_every_week_no&quot;:&quot;'+responseData.task_data.Weekly_every_week_no+'&quot;,&quot;Weekly_week_day&quot;:&quot;'+responseData.task_data.Weekly_week_day+'&quot;,&quot;monthly_radios&quot;:&quot;'+responseData.task_data.monthly_radios+'&quot;,&quot;Monthly_op1_1&quot;:&quot;'+responseData.task_data.Monthly_op1_1+'&quot;,&quot;Monthly_op1_2&quot;:&quot;'+responseData.task_data.Monthly_op1_2+'&quot;,&quot;Monthly_op2_1&quot;:&quot;'+responseData.task_data.Monthly_op2_1+'&quot;,&quot;Monthly_op2_2&quot;:&quot;'+responseData.task_data.Monthly_op2_2+'&quot;,&quot;Monthly_op2_3&quot;:&quot;'+responseData.task_data.Monthly_op2_3+'&quot;,&quot;Monthly_op3_1&quot;:&quot;'+responseData.task_data.Monthly_op3_1+'&quot;,&quot;Monthly_op3_2&quot;:&quot;'+responseData.task_data.Monthly_op3_2+'&quot;,&quot;yearly_radios&quot;:&quot;'+responseData.task_data.yearly_radios+'&quot;,&quot;Yearly_op1&quot;:&quot;'+responseData.task_data.Yearly_op1+'&quot;,&quot;Yearly_op2_1&quot;:&quot;'+responseData.task_data.Yearly_op2_1+'&quot;,&quot;Yearly_op2_2&quot;:&quot;'+responseData.task_data.Yearly_op2_2+'&quot;,&quot;Yearly_op3_1&quot;:&quot;'+responseData.task_data.Yearly_op3_1+'&quot;,&quot;Yearly_op3_2&quot;:&quot;'+responseData.task_data.Yearly_op3_2+'&quot;,&quot;Yearly_op3_3&quot;:&quot;'+responseData.task_data.Yearly_op3_3+'&quot;,&quot;Yearly_op4_1&quot;:&quot;'+responseData.task_data.Yearly_op4_1+'&quot;,&quot;Yearly_op4_2&quot;:&quot;'+responseData.task_data.Yearly_op4_2+'&quot;,&quot;start_on_date&quot;:&quot;'+responseData.task_data.start_on_date+'&quot;,&quot;no_end_date&quot;:&quot;'+responseData.task_data.no_end_date+'&quot;,&quot;end_after_recurrence&quot;:&quot;'+responseData.task_data.end_after_recurrence+'&quot;,&quot;end_by_date&quot;:&quot;'+responseData.task_data.end_by_date+'&quot;,&quot;task_added_date&quot;:&quot;'+responseData.task_data.task_added_date+'&quot;,&quot;task_completion_date&quot;:&quot;'+responseData.task_data.task_completion_date+'&quot;,&quot;is_deleted&quot;:&quot;'+responseData.task_data.is_deleted+'&quot;}" /></tr>';
						
						$("#todo_"+id).replaceWith(task_str);
						
						$(".todoSchedulledDatepicker").datepicker({
					        startDate: -Infinity,
						    format: JAVASCRIPT_DATE_FORMAT,
					   	}).on('changeDate', function(date) {
					   		$(this).datepicker('hide');
					   		updateSchedulledDate(date.date,$(this).attr('id'));
					   	});
					   	
					   	$(".todoDueDatepicker").datepicker({
					        startDate: -Infinity,
						    format: JAVASCRIPT_DATE_FORMAT,
					   	}).on('changeDate', function(date) {
					   		$(this).datepicker('hide');
					   		updateDueDate(date.date,$(this).attr('id'));
					   	});
	                    
					} else {
	                	$("#todo_"+id).remove();
	                	if($("#todolist tr td").length == 0){
	           				$("#todolist").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No Records found.</td></tr>');
	           			}
	                }
				}
			}
		});
	}
</script>

<?php date_default_timezone_set($this->session->userdata("User_timezone")); ?>
<table id="filtertab1" class="table tabrd table-striped table-hover table-condensed flip-content">
                    <thead class="flip-content">
                      <tr>
                        <th>Task</th>
                        <th>Due Date</th>
                        <th>Scheduled Date</th>
                        <th id="prio">Priority <!--<a  href="javascript:;"> <i id='hideicon' class="stripicon icondwonarro"></i> </a>--></th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody id='todolist'>
                    <?php 
                    
                    if($todolist!='0'){
                    foreach($todolist as $t){
                    	$t = (object)$t;
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
						if($t->master_task_id){
							$is_master_deleted = chk_master_task_id_deleted($t->master_task_id);
						} else {
							$is_master_deleted = 0;
						}
						?>
                      <tr id="todo_<?php echo $t->task_id;?>">
                        <td title="<?php echo $t->task_description;?>">
                        	<?php if($t->master_task_id == '0' || $is_master_deleted=="1"){ ?>
								<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $t->task_id;?>','<?php echo chk_task_exists($t->task_id);?>')" class="tooltips dashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" style="color:#6f737b;"><?php echo (strlen($t->task_title) > 40)?substr(ucwords($t->task_title),0, 37).'...':ucwords($t->task_title);?></a>
							<?php } else { ?>
								<a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $t->task_id;?>','<?php echo $t->master_task_id;?>','<?php echo chk_task_exists($t->task_id);?>');" class="tooltips dashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" style="color:#6f737b;"><?php echo (strlen($t->task_title) > 40)?substr(ucwords($t->task_title),0, 37).'...':ucwords($t->task_title);?></a>
							<?php } ?>
                        </td>
                        <td class="todoDueDatepicker" id="toDoDue_<?php echo $t->task_id;?>"><span class="hidden"><?php echo $hidden_due_date;?></span><span class="date_edit"><?php echo $due_dt;?></span></td>
                        <td class="todoSchedulledDatepicker" id="schedulled_<?php echo $t->task_id;?>"><span class="hidden"><?php echo $hidden_scheduled_date;?></span><span class="date_edit"><?php echo $scheduled_dt;?></span></td>
                        <td><?php echo $t->task_priority;?></td>
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
							
							if( $ts->task_status_name!='In Progress' && $ts->task_status_name!='Ready' && $ts->task_status_name!='Not Ready')
							{
								$tsk_st = "common";
							}
                        ?>
                        <td><span class="label label-<?php echo $tsk_st;?>"><?php echo $ts->task_status_name;?></span></td>
                        <?php $t_arr = (array)$t;?>
                        <input type="hidden" id="task_data_<?php echo $t->task_id;?>" value="<?php echo htmlspecialchars(json_encode($t_arr)); ?>" />
                        <?php } } ?>
                      </tr>
                      <?php } } ?> 
                    
                    </tbody>
                  </table>