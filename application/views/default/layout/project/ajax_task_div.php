<script type="text/javascript">
	$(document).ready(function(){
		$(".full-width div").addClass("before_timer");App.init();
             
                                            inlin_edit();
                                            trunc_task_title();
                                     
	});
</script>
<?php 
$task_status_completed_id = $this->config->item('completed_id');
date_default_timezone_set($this->session->userdata("User_timezone"));
$task_status = get_taskStatus($this->session->userdata('company_id'),'Active'); 
$color_codes = get_user_color_codes($this->session->userdata('user_id'));
$swimlanes = get_user_swimlanes(get_authenticateUserID());
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
$default_format = $this->config->item('company_default_format'); 
  $dat='';
        foreach ($date_arr_java  as $v=>$val){
            if($v==$default_format){
                $dat=$val;
            }
        }
$today = date($site_setting_date);
if (strpos($td->task_id,'child') !== false) {
    $chks = "0";
	$steps = 0;
} else {
	$chks = "1";

	$steps = get_task_steps($td->task_id);
	 $comments = get_task_comments($td->task_id);
	 $files=get_task_files($td->task_id); 
     
}
 if($chks == "1"){
    $dependencies = $td->tpp;
    if($td->tpp!='0' && $td->completed_depencencies=="0"){
            $completed_depencencies = "green";
    } else if($td->tpp=='0' && $td->completed_depencencies=="0"){
            $completed_depencencies = "green";
    } else {
            $completed_depencencies = "red";
    }
    } else {
            $dependencies = '';
            $completed_depencencies = "";
    }
if($td->task_due_date == '0000-00-00')
                                                                                                                                                $due_date1 = '';
                                                                                                                                            else 
                                                                                                                                                $due_date1 =date("m-d-Y",strtotime($td->task_due_date));
                                                                                                                                            if($td->task_scheduled_date == '0000-00-00')
                                                                                                                                                $scheduled_date = '';
                                                                                                                                            else 
                                                                                                                                                $scheduled_date =date("m-d-Y",strtotime($td->task_scheduled_date));
                                                                                                                                            
                                                                                                                                            $report_user_list_id='';
                                                                                                                  if(isset($all_report_user) && !empty($all_report_user)){
                                                                                                                            foreach($all_report_user as $val ){
                                                                                                                                if($val['user_id']==$td->task_owner_id){
                                                                                                                                   $report_user_list_id='1';  
                                                                                                                                }
                                                                                                                            }
                                                                                                                          }else{
                                                                                                                             $report_user_list_id='0';
                                                                                                                          }
                                                                                                                                            $jsonarray=array(
                                                                                                                                                "task_status" =>$task_status,
                                                                                                                                                "user_colors" =>$color_codes,
                                                                                                                                                "user_swimlanes" =>$swimlanes,
                                                                                                                                                "task_id" =>$td->task_id,
                                                                                                                                                "locked_due_date" => $td->locked_due_date,
                                                                                                                                                "task_due_date" =>$due_date1,
                                                                                                                                                "task_scheduled_date" =>$scheduled_date,
                                                                                                                                                "date" =>strtotime(date('Y-m-d')), 
                                                                                                                                                "active_menu" =>'from_project',
                                                                                                                                                "start_date" =>'',
                                                                                                                                                "end_date" =>'',
                                                                                                                                                "master_task_id" =>$td->master_task_id,
                                                                                                                                                "is_master_deleted" =>chk_master_task_id_deleted($td->master_task_id),
                                                                                                                                                "chk_watch_list" =>'',
                                                                                                                                                "task_owner_id" =>$td->task_owner_id,
                                                                                                                                                "completed_depencencies" =>'',
                                                                                                                                                "color_menu" =>'',
                                                                                                                                                "swimlane_id" =>'',
                                                                                                                                                "task_status_id" => $td->task_status_id,
                                                                                                                                                "before_status_id" => '',
                                                                                                                                                "customer_id" =>'',
                                                                                                                                                "report_user_list_id" => $report_user_list_id
                                                                                                                                            );

?>

<div onclick="save_task_for_timer(this,'<?php echo $td->task_id;?>','<?php echo addslashes($td->task_title);?>','<?php echo $td->task_time_spent;?>','<?php echo $chks;?>','<?php echo $completed_depencencies;?>');" id="task_tasksort_<?php echo $td->task_id;?>" class="task_tasksort project_master_task_<?php echo $td->master_task_id;?>">
									<ul class="clearfix cst_ul">
										<li style="margin-top:7px;"><i class="icon-align-justify prjicn"></i></li>
                                                                                <li ><input class="projectTask" onclick="changeTaskStatus('<?php echo $td->task_id;?>','<?php echo $td->subsection_id;?>','<?php echo $td->section_id;?>','<?php echo $td->task_time_spent;?>');" name="task_status" id="pr_task_status_<?php echo $td->task_id;?>" value="" type="checkbox" <?php if($td->task_status_id == $task_status_completed_id){ ?> checked="checked" <?php } ?> ></li>
                                                                                <li style="margin-top:5px;"><?php if($td->task_status_id == $task_status_completed_id){?>
                                                                                    <em class="linethrought"><?php }else{?> <?php }?>
                                                                                        <?php if($td->master_task_id == '0' || $is_master_deleted=="1"){ ?>
                                                                                        <div id="task_<?php echo $td->task_id;?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');">
					           <a data-toggle="modal" href="javascript:void(0)"  onclick="edit_task(this,'<?php echo $td->task_id;?>','<?php echo $chks;?>')" data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
                                                                                        </div>
                                                                                                    <?php } else { ?>
                                                            <div id="task_<?php echo $td->task_id;?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');">
								<a data-toggle="modal" href="javascript:void(0)"  onclick="open_seris(this,'<?php echo $td->task_id;?>','<?php echo $td->master_task_id;?>','<?php echo $chks;?>')" data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
                                                            </div>
                                                                    <?php } ?>
                                                                        <?php if($td->task_status_id == $task_status_completed_id){?></em><?php }else{?> <?php }?> </li>
                                                                                 <li ><?php if($task_status){
                                                                                                                                                            echo'<span class="updttskstatus">(Status: ';
                                                                                                                                                            foreach($task_status as $v){
                                                                                                                                                                if($v->task_status_id==$td->task_status_id){ ?>
                                                                                                                                                                   <a href="javascript:void(0)"  class="task_status_editable"  data-value="<?php echo $v->task_status_id;?>" data-type="select" data-pk="<?=$td->task_id?>"  data-original-title="Task Status" data-emptytext="Status" ><?php echo $v->task_status_name;?></a>
                                                                                                                                                            <?php    }
                                                                                                                                                            }
                                                                                                                                                            echo'</span>';
                                                                                                                                                            }?>
                                                                                                                                                        </li>
                                                              <li><span class="updtallcuserl">- Allocated to  <a href="javascript:void(0)"  class="task_allocated_user_editable" data-value="<?=$td->task_allocated_user_id?>"  data-type="select" data-original-title="Select User" data-emptytext="User" data-pk="<?=$td->task_id?>"><?php echo  ucwords($td->first_name)." ".ucwords($td->last_name);?></a>
                                                                                                                                                            </span></li>
                                                                                                                                                                <?php


								if($td->task_due_date != '0000-00-00' ){
									$due_dt = date($site_setting_date,strtotime($td->task_due_date));
								}  else {
									$due_dt = 'N/A';
								}


						if($td->task_due_date!="0000-00-00" && $due_dt != 'N/A'){
						if($td->task_status_id != $task_status_completed_id && (strtotime(str_replace(array("/"," ",","), "-", $due_dt)) < strtotime(str_replace(array("/"," ",","),"-", $today)))){
						?>
						<li><span class="red_date">- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date'   class="task_due_date_editable" data-format="<?=$dat?>"> <p  class="red"> <?php echo $due_dt;?></p></a></span> )</li>
                                                                                                                                                <?php }else if($td->task_status_id == $task_status_completed_id){ ?>
                                                                                                                                                <li>- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' data-format="<?=$dat?>"  class="task_due_date_editable" > <?php echo $due_dt;?></a> )</li>
<!--                                                                                                                                                <li><em class="linethrought">- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date'   class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> )</em></li>-->
                                                                                                                                                <?php }else{?>
                                                                                                                                                <li >- Due : <a href="#" data-type='date'  data-pk="<?=$td->task_id?>" class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> )</li>
                                                                                                                                                <?php } }else{?>
                                                                                                                                                <li >-<a href="#" data-pk="<?=$td->task_id?>" data-type='date'  class="task_due_date_editable" data-format="<?=$dat?>"> No Due Date</a> )</li>
							<?php } ?>
							
							<?php if($td->locked_due_date){ ?><li><a class="tooltips" data-placement="right" data-original-title="Due date is locked" href="javascript:void(0);"><i class="icon-lock  prjicn prtoppos"> </i></a></li><?php } ?>
							
							<?php if($td->task_priority == "Low"){
									echo "<li><a class='tooltips' data-placement='right' data-original-title='Low Priority' href='javascript:void(0);'><i class='icon-warning-sign  prjicn iconlow'></i></a></li>";
								} else if($td->task_priority == "Medium"){
									echo "<li><a class='tooltips' data-placement='right' data-original-title='Medium Priority' href='javascript:void(0);'><i class='icon-warning-sign  prjicn iconmedium'></i></a></li>";
								} else if($td->task_priority == "High"){
									echo "<li><a class='tooltips' data-placement='right' data-original-title='High Priority' href='javascript:void(0);'><i class='icon-warning-sign  prjicn iconhigh'></i></a></li>";
								} else {
									
								}?>
								
									
							<?php if($chks == '1'){
									
									if($comments){ ?>
										<li><a class="tooltips" data-placement="right" data-original-title="Comments" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_7')" href="javascript:void(0);"><i class="icon-comment-alt prjicn"> </i><sup><?=count($comments)?></sup></a></li>
									<?php } }?>
							<?php if($files){ ?><li><a class="tooltips" data-placement="right" data-original-title="Task Files" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_6')" href="javascript:void(0);"><i class="icon-paperclip prjicn"> </i><sup><?=count($files)?></sup></a></li><?php } ?>
								<?php if($steps){ ?>
									<li><a class="tooltips" data-placement="right" data-original-title="Task Steps" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_4')" href="javascript:void(0);"><i class="icon-list-ul prjicn"> </i><sup><?=count($steps)?></sup></a></li>
								<?php }?>
							
							
					</ul>

				<input type="hidden" id="task_data_<?php echo $td->task_id;?>" value="<?php echo htmlspecialchars(json_encode($td)); ?>" />
</div>