<?php 
$site_setting_date=$this->config->item('company_default_format');
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
        if($date_arr_java[$site_setting_date]=='dd M,yyyy'){
            $size=11;
        }else{
            $size=10;
        }
?>
<?php date_default_timezone_set($this->session->userdata("User_timezone")); ?>
<style>
.check1{
margin-right: 8%;
}

</style>
<script type="text/javascript">
$(document).ready(function(){
    $(".back_log_slimScroll").slimScroll({
    height: '410px',
    color: 'rgb(23, 163, 233)',
    wheelStep: 10
    });
    $('.input-append.date').datepicker({
        startDate: -Infinity,
        format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
        autoclose:true
    }).on('changeDate', function(date) {
        var id = $(this).attr('customID');
        $("#select_task_"+id).attr("checked",'true');
    });;
   
    $('#add_to_calender').on('click',function(){
		var jsonObj = [];
		var check=0;
		var count="0";
		count=parseInt(count);
		 $('.checkbox1').each(function(){
                if($(this).attr('checked') == 'checked'){
                        var id = $(this).attr('id');
                            id = id.replace('select_task_','');
			var task_date = $('#task_date_'+id).val();
			if(task_date){
			    var data = {
                            id: $(this).val(),
                            task_date: task_date
                            }
                            jsonObj.push(data);
                            check=1;
			}else{
                            $('#task_date_'+id).focus();
			    check=0;
                            return 0;
			}
                }
            });
			
			if(check==1)
			{
			$.ajax({
                                type: "post",
                                url: SIDE_URL + "calendar/update_backlog_task",
                                data: {
                                    data:JSON.stringify(jsonObj)
                                },
                                success: function(pr) { 
                                    $.each($.parseJSON(pr), function(key,value){
                                         count+=1;
                                    });
                                },error:function(){
                                    console.log("ajax request not received!");

                                }
                            });
			      $("#back_log").modal("hide")
				change_view($("#week_start_date").val()+"#"+$("#week_end_date").val()+"#current");
			}
			else{
                            alertify.alert("Please select date!");
			}
		});
 });
</script>

<?php if(!empty($task_list)){ ?>
	<div class="comment-block margin-bottom-20 ">
	<div class="comment-title backLog_title">Select the tasks that are in your back log and schedule them on a select date</div>
        <div class="customtable table-scrollable form-horizontal  ">
            <form id="back_log_form" name="back_log_form" method="post" onsubmit="task_scheduled(this);" style="margin-right: 6px;">
                <div class="back_log_slimScroll">
               <table class="table table-striped table-hover table-condensed flip-content " id="schedule_task_table" style="margin-bottom:0px !important">
                 <tbody>
                    <?php 
                            foreach($task_list as $task){ ?>
                             <tr>
                                 <td>
                                     <input type="checkbox" class="checkbox1" name="select_task[]"  id="select_task_<?php echo $task->task_id;?>" value='<?php echo $task->task_id."&".$task->task_due_date;?>' />
                                 </td>
                                 <td width="45%">
                                     <?php echo $task->task_title;?>
                                 </td>
                                 <td>
                                     <span class="label-status label-<?php echo str_replace(' ', '',$task->task_status_name);?>" style="padding:8px !important;"><?php echo $task->task_status_name;?></span>
                                 </td>
                                 <td>
                                     <div class="right_log back_log1">
                                        <div class="controls">
                                            <div class="datLT">
                                                <div class="input-append date date-picker" customID="<?php echo $task->task_id; ?>" id="back_log_picker" data-date="<?php echo date($site_setting_date);?>" data-date-format="<?php echo $date_arr_java[$site_setting_date]; ?>" data-date-viewmode="years">
                                                    <input name="task_date[]" placeholder="Select a date" id="task_date_<?php echo $task->task_id;?>" class="m-wrap m-ctrl-medium setHourErr all_date" size="16" type="text" value="" style="width:175px;" maxlength="<?php echo $size;?>" autocomplete="off"/><span  class="add-on"><i style=" width: 24px; height: 24px;" class="icon-calendar taskppicn"></i></span>
                                                    <input type="hidden" id="hide_task_date_<?php echo $task->task_id;?>" value=""/>
                                                </div>
                                            </div>
					</div>
                                    </div>
                                 </td>
                             </tr>
                            <?php } ?>
                             
                 </tbody>
                </table>
                </div>
                <div class="col-md-12" style="padding-top:10px;padding-bottom: 10px;">
                     <button  type="button" class="btn btn-common-blue" id="add_to_calender"> Add to Calendar </button>
                </div>
            </form>
           </div>
            
	</div>
<?php }else{ ?>
        <div class="no_data" style="height:410px">No tasks in Backlog.</div>
<?php } ?>
<?php date_default_timezone_set("UTC"); ?>