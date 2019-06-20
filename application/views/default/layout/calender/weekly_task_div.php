<script type="text/javascript">
$(document).ready(function(){
	$(".full_task div").addClass("before_timer");
	App.init();
	
});
</script>

<?php
date_default_timezone_set($this->session->userdata("User_timezone"));
$ready = get_task_status_id_by_name('Ready');
$completed = $this->config->item('completed_id');
$chk = chk_task_exists($week_task['task_id']);
$dependencies = get_task_dependencies($week_task['task_id']); 
$completed_depencencies = chk_dependency_status($week_task['task_id'],$completed);
$outside_code = get_outside_color_code(get_user_task_color($week_task['task_id'],get_authenticateUserID()));
$comments = get_task_comments($week_task['task_id']);
$swimlane = get_swimlanes_name($week_task['swimlane_id']);
$customer = get_customer_detail($week_task['customer_id'],$week_task['task_company_id']);
$files = get_task_files($week_task['task_id']);
$status_name = get_task_status_name_by_id($week_task['task_status_id']);
if($this->session->userdata('Temp_calendar_user_id')== '0'){
    $user_swimlanes = get_user_swimlanes(get_authenticateUserID());
}else{
    $user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id'));
}
$task_status = get_taskStatus($this->session->userdata('company_id'),'Active'); 
if($this->session->userdata('Temp_calendar_user_id') == '#'){
    $color_codes = get_user_color_codes(get_authenticateUserID());
}else{
    $color_codes = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
}
$user_colors = $color_codes;
if($week_task['task_priority'] == 'Low'){
	$priority_cls = "green1";
} elseif($week_task['task_priority'] == 'Medium'){
	$priority_cls = "yellow1"; 
} elseif($week_task['task_priority'] == 'High'){
	$priority_cls = "red1";
} else {
	$priority_cls = "";
}
$chk_watch_list = check_my_watch_list($week_task['task_id'],get_authenticateUserID());
    if($color_menu=='false'){
        $outside_code='#e5e9ec';
        $week_task['color_id']='#fff';
    }
$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
$total_active_swimlane = count_total_swimlanes();
if($show_other_user_task == '1' && $week_task['task_owner_id'] != $week_task['task_allocated_user_id']){
    $user_swimlanes = get_user_swimlanes($week_task['task_allocated_user_id']);
    $chk_watch_list = check_my_watch_list($week_task['task_id'],$week_task['task_allocated_user_id']);
    $user_colors = get_user_color_codes($week_task['task_allocated_user_id']);
    $disabled = 'unsorttd';
    $outside_code = get_outside_color_code(get_user_task_color($week_task['task_id'],$week_task['task_allocated_user_id']));
}else{
    $disabled = '';
}

 ?>

<div onclick="save_task_for_timer(this,'<?php echo $week_task['task_id'];?>','<?php echo addslashes($week_task['task_title']);?>','<?php echo $week_task['task_time_spent'];?>','<?php echo $chk;?>','<?php echo $completed_depencencies;?>');" id="main_<?php echo $week_task['task_id'];?>" class="<?php echo $disabled; ?> task_div week_master_<?php echo $week_task['master_task_id'];?> <?php if($calender_sorting!='1'){  ?> unsorttd<?php } ?> <?php echo $priority_cls;?>" <?php if($week_task['task_allocated_user_id'] != get_authenticateUserID()){ if($footer_user_id== $week_task['task_allocated_user_id']){}else{ if($footer_user_id=='0' || $footer_user_id=='#'){}else{if($week_task['task_owner_id'] == get_authenticateUserID()){}else{echo 'style="display:none;"';}}}}?>>
	<?php 
        $report_user_list_id='';
                if(isset($all_report_user) && !empty($all_report_user)){
                    foreach($all_report_user as $val ){
                        if($val['user_id']==$week_task['task_owner_id']){
                            $report_user_list_id='1';  
                        }
                    }
                }else{
                    $report_user_list_id='0';
                }
                $jsonarray=array(
                    "task_status" =>$task_status,
                    "user_colors" =>$user_colors,
                    "user_swimlanes" =>$user_swimlanes,
                    "task_id" =>$week_task['task_id'],
                    "locked_due_date" => $week_task['locked_due_date'],
                    "task_due_date" =>date("m-d-Y",strtotime($week_task['task_due_date'])),
                    "task_scheduled_date" =>date("m-d-Y",strtotime($week_task['task_scheduled_date'])),
                    "date" =>strtotime($week_task['task_scheduled_date']), 
                    "active_menu" =>$active_menu,
                    "start_date" =>strtotime($start_date),
                    "end_date" =>strtotime($end_date),
                    "master_task_id" =>$week_task['master_task_id'],
                    "is_master_deleted" =>'1',
                    "chk_watch_list" =>$chk_watch_list,
                    "task_owner_id" =>$week_task['task_owner_id'],
                    "completed_depencencies" =>$completed_depencencies,
                    "color_menu" =>$color_menu,
                    "swimlane_id" =>$week_task['swimlane_id'],
                    "task_status_id" => $week_task['task_status_id'],
                    "before_status_id" => '',
                     "report_user_list_id" => $report_user_list_id
                );

                ?>														<div oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');">
															<style>
															 
																#main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .comm-title, #main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .comm-desc,#main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .com-brdbtm,#main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .commicon-list{
																	border-bottom:1px dashed <?php echo $outside_code;?>;
																}
															
															</style>
                                                                                                                        <input type="hidden" id="task_color_menu" value="<?php echo $color_menu;?>"/>
															<input type="hidden" id="task_data_<?php echo $week_task['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($week_task)); ?>" />
															<input type="hidden" id="hdn_due_date_<?php echo $week_task['task_id'];?>" value="<?php echo strtotime($week_task['task_due_date']);?>" />
															<input type="hidden" id="hdn_locked_due_date_<?php echo $week_task['task_id'];?>" value="<?php echo $week_task['locked_due_date'];?>" />
															<input type="hidden" id="or_color_<?php echo $week_task['task_id'];?>" name="or_color_id" value="<?php echo $outside_code;?>" />
															<div class="dragbox" id="task_<?php echo $week_task['task_id'];?>" style="border : solid 1px <?php echo $outside_code;?>;">
                                                                                                                            

                                                                                                                            <div class="comm-box whitebox disabled_sort" style="background-color: <?php echo get_task_color_code($week_task['color_id']);?>">
																	<?php if($week_task['master_task_id'] == '0' || chk_master_task_id_deleted($week_task['master_task_id']) == '1' || $week_task['frequency_type']== 'one_off'){ ?>
																		<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>');">
																	<?php } else { ?>
																		<a onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>');" href="javascript:void(0)">
																	<?php } ?>
																	
																		<div class="comm-title clearfix">
																			<?php $project_name = '';
																				if($week_task['task_project_id']){
																					$project_name = get_project_name($week_task['task_project_id']).' - ';
																				}
																				$title = $week_task['task_title'];
																				// if(strlen($title) > 15) {
																				    // $title = substr($title, 0, 15).'..'; 
																				// }
																				$est = '0m';
																					$spt = '0m';
																			?>
																			<!--<div class="comtitle-LF"> <?php echo $project_name.''.$title; ?> </div>-->
																			<?php if($week_task['task_time_estimate']!='0' || $week_task['task_time_spent']!='0'){
																				
																				$total_task_time_estimate_minute = $week_task['task_time_estimate'];
																				$estimate_hours = intval($total_task_time_estimate_minute/60);
																				$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
																				
																				
																				$total_task_time_spent_minute = $week_task['task_time_spent'];
																				$spent_hours = intval($total_task_time_spent_minute/60);
																				$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
																				
																				if($estimate_hours != '0'){	$e_h = $estimate_hours.'h'; } else { $e_h = ''; }
																				
																				if($estimate_minutes != '0'){ $e_m = $estimate_minutes.'m'; }else{ $e_m = ''; }
																				
																				if($spent_hours != '0'){	$s_h = $spent_hours.'h'; } else { $s_h = ''; }
																				
																				if($spent_minutes != '0'){ $s_m = $spent_minutes.'m'; }else{ $s_m = ''; }
																				
																				if($e_h == '' && $e_m == ''){
																					$est = '0m';
																				} elseif($e_h !='' && $e_m == ''){
																					$est = $e_h;
																				} elseif($e_h =='' && $e_m != ''){
																					$est = $e_m;																					
																				} else {
																					$est = $e_h.''.$e_m;
																				}
																				
																				if($s_h == '' && $s_m == ''){
																					$spt = '0m';
																				} elseif($s_h !='' && $s_m == ''){
																					$spt = $s_h;
																				} elseif($s_h =='' && $s_m != ''){
																					$spt = $s_m;																					
																				} else {
																					$spt = $s_h.''.$s_m;
																				}
																				 
																				// echo '<div class="comtitle-RT" id="task_time_'.$week_task['task_id'].'">'.$est.'/'.$spt.'</div>';
																				
																			}
																			
																			$cl = '';
																			if($est == "0m" && $spt == "0m"){
																				$cl = "display:none;";
																			}
																			?>
																				<div class="comtitle-LFD"> 
																				
																				<?php if($color_menu=='false'){ ?>
                                                                                                                                                                    <?php  $name = 'upload/user/'.$week_task['profile_image'];
                                                                                                                                                                                   if(($week_task['profile_image'] != '' || $week_task['profile_image'] != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$week_task['profile_image'])) { ?>
                                                                                                                                                                                           <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo usernameById($week_task['task_allocated_user_id']);?>" alt="" src="<?php echo $s3_display_url.'upload/user/'.$week_task['profile_image']; ?>" />
                                                                                                                                                                                           <?php } else { ?>
                                                                                                                                                                                           <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo usernameById($week_task['task_allocated_user_id']);?>" alt="" src="<?php echo $s3_display_url; ?>upload/user/no_image.jpg"  />
                                                                                                                                                                                           <?php } ?>
                                                                                                                                                                                        <div class="comttime" style="<?php echo $cl;?>" id="task_time_<?php echo $week_task['task_id'];?>"> <?php echo $est.'/'.$spt;  ?></div> 
                                                                                                                                                                                    <div >
                                                                                                                                                                                       
                                                                                                                                                                                            <?php echo $project_name.''.$title; ?>
                                                                                                                                                                                          <?php 
                                                                                                                                                                                            if($chk_watch_list){ ?>
                                                                                                                                                                                                    <span class="tooltips" data-placement="right" data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
                                                                                                                                                                                            <?php } ?>
                                                                                                                                                                                    </div>
                                                                                                                                                                            
                                                                                                                                                                            <?php }else{ ?>
                                                                                                                                                                                <?php if($show_other_user_task == 1 && $week_task['task_allocated_user_id'] != $footer_user_id){ ?>  
                                                                                                                                                                                        <?php  $user_name = explode(' ',$week_task['allocated_user_name']);
                                                                                                                                                                                        $word1 = ucfirst(substr($user_name[0],0,1));
                                                                                                                                                                                        $word2 = ucfirst(substr($user_name[1],0,1)); ?>
                                                                                                                                                                                        <span class="tooltips pull-right" data-placement="left" data-html="true" data-original-title="Allocated to <br><?php echo $week_task['allocated_user_name']; ?>" user-letters="<?php echo $word1.$word2; ?>"></span>
                                                                                                                                                                                <?php }else if($week_task['task_allocated_user_id'] != $week_task['task_owner_id']){
                                                                                                                                                                                       $profile = get_task_owner_image($week_task['task_owner_id']);
                                                                                                                                                                                               $name = 'upload/user/'.$profile->profile_image;
                                                                                                                                                                                               if(($profile->profile_image != '' || $profile->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) { ?>
                                                                                                                                                                                              <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $profile->first_name.' '.$profile->last_name;?>" alt="" src="<?php echo $s3_display_url.$name; ?>" />
                                                                                                                                                                                              <?php } else { ?>
                                                                                                                                                                                              <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $profile->first_name.' '.$profile->last_name;?>" alt="" src="<?php echo $s3_display_url; ?>upload/user/no_image.jpg"  />
                                                                                                                                                                                <?php } }?>
                                                                                                                                                                                    <div class="comttime" style="<?php echo $cl;?>" id="task_time_<?php echo $week_task['task_id'];?>"> <?php echo $est.'/'.$spt;  ?></div> 
                                                                                                                                                                                    <div>

                                                                                                                                                                                               <?php echo $project_name.''.$title; ?>
                                                                                                                                                                                               <?php
                                                                                                                                                                                               if($chk_watch_list){ ?>
                                                                                                                                                                                                       <span class="tooltips" data-placement="right" data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
                                                                                                                                                                                               <?php } ?>
                                                                                                                                                                                    </div>
                                                                                                                                                                            <?php } ?>
																				
																			</div>
																		</div>
																	</a>
																	<?php if($chk == "1"){
																		if($week_task['task_ex_pos']=='0'){
																			$style_ex = 'style="display:none;"';
																			$arr_ex = "0";
																		} else {
																			$style_ex = 'style="display:block;"';
																			$arr_ex = "1";
																		}
																	} else {
																		$style_ex = 'style="display:none;"';
																		$arr_ex = "0";
																	} ?>
																<div id="expand_div_<?php echo $week_task['task_id'];?>" <?php echo $style_ex; ?> >
																	<?php if($week_task['task_description']){
																			$desc = $week_task['task_description'];
//																			if(strlen($desc) > 100) {
//																			    $desc = substr($desc, 0, 100).'...'; 
//																			}
																		?>
																		<div class="comm-desc"> <p> <?php echo nl2br($desc);?> </p></div>
																		<?php
																	} ?>
                                                                                                                                        
																	<?php if($week_task['task_owner_id'] == get_authenticateUserID() && $color_menu == 'true'){
																		if($week_task['task_owner_id'] != $week_task['task_allocated_user_id']){
																			?>
																			<div class="duedate com-brdbtm ">
																				<div> Allocated to : <?php echo usernameById($week_task['task_allocated_user_id']);?></div>
																			</div>
																			<?php
																		}
																	}?>
																	<?php 
																	if($week_task['task_due_date']!='0000-00-00'){
																		$task_due_date = date("Y-m-d",strtotime($week_task['task_due_date']));
																		
																		$today = date("Y-m-d");
																		
																		if($task_due_date == $today){
																			$date = 'Today';
																		} elseif($task_due_date == date('Y-m-d',strtotime("-1 days",strtotime($today)))){
																			$date = 'Yesterday';
																		} elseif($task_due_date == date('Y-m-d',strtotime("+1 days",strtotime($today)))){
																			$date = 'Tomorrow';
																		} else {
																			$date = date($site_setting_date,strtotime($task_due_date));
																		} ?>
																		<?php 
																		if($week_task['task_status_id'] == $completed){ ?>
																				<div class="duedate com-brdbtm ">
																					<div> Due : <?php echo $date;?> <?php if($week_task['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
																				</div>
																			<?php
																			
																		} else {
																			if($task_due_date < $today){ ?>
																				<div class="duedate com-brdbtm ">
																					<div class="red"> Overdue : <?php echo $date;?> <?php if($week_task['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
																				</div>
																			<?php } else { ?>
																				<div class="duedate com-brdbtm ">
																					<div> Due : <?php echo $date;?> <?php if($week_task['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
																				</div>
																			<?php } ?>
																			<?php
																		}
																	}?>
                                                                                                                                        
                                                                                                                                                
                                                                                                                                                
																	<?php
                                                                                                                                          $total_steps=0;
                                                                                                                                         $com_steps=0;
																	if($chk == '1'){
																	 $steps = get_task_steps($week_task['task_id']);
																	if($steps){?>
																	<div class="comm-step" style="border-bottom : dashed 1px <?php echo $outside_code;?>;">
                                                                                                                                                 <div class="form-group" style="margin-bottom:0px !important;">
																			<?php foreach($steps as $st){
																				
																				$stp_cl = '';
																				if($st['is_completed'] == '1'){
																					$stp_cl = 'step-complete-class';
                                                                                                                                                                        $com_steps++;
																						}
                                                                                                                                                                                $total_steps++;
																				 ?> 
																			 <label class="marginTop checkbox <?php echo $stp_cl;?>" id="step_class_<?php echo $st['task_step_id'];?>" style="margin-bottom:6px !important;">
																				<input type="checkbox" name="step_chk" class="newcheckbox_task" onclick="chek_step('<?php echo $st['task_step_id'];?>','<?php echo $week_task['task_id'];?>');" value="<?php echo $st['task_step_id'];?>" <?php if($st['is_completed'] == '1'){ echo 'checked="checked"'; } ?>><?php echo $st['step_title']; ?>
																			 </label>
																			 <?php } ?>
																		 </div>
																	</div>
																	<?php } } ?>
																	<?php 
                                                                                                                                      
                                                                                                                                        if(strpos($week_task['task_id'], 'child') !== false){
																		$steps = get_task_steps($week_task['master_task_id']);
																		if($steps){?>
																	<div class="comm-step" style="border-bottom : dashed 1px <?php echo $outside_code;?>;">
                                                                                                                                                 <div class="form-group" style="margin-bottom:0px !important;">
																			<?php foreach($steps as $st){
																				$stp_cl = '';
																				if($st['is_completed'] == '1'){
																							$stp_cl = 'step-complete-class';
                                                                                                                                                                                        $com_steps++;
																						}
                                                                                                                                                                                $total_steps++;
																				 ?> 
																			 <label class="marginTop checkbox <?php echo $stp_cl;?>" id="step_class_<?php echo $st['task_step_id'];?>">
                                                                                                                                                             <input type="checkbox" name="step_chk" class="newcheckbox_task" onclick="chek_step('<?php echo $st['task_step_id'];?>','<?php echo $week_task['task_id'];?>');" value="<?php echo $st['task_step_id'];?>" <?php if($st['is_completed'] == '1'){ echo 'checked="checked"'; } ?>><?php echo $st['step_title']; ?>
																			 </label>
																			 <?php } ?>
																		 </div>
																	</div>
																	<?php } 
																	} ?>
                                                                                                                                    </div>
                                                                                                                                       <?php if($dependencies!='0' || $week_task['master_task_id'] != '0' || $comments!='0' || $week_task['is_personal'] != '0' || $files!='0' || $total_steps !='0'){?>      
																	<div class="commicon-list clearfix">
																		<ul class="list-unstyled">
																			
																			<?php 
																			if($dependencies){
																				if($completed_depencencies == 'red'){ ?> 
																					<li class="no-bottom-space">
																						<a class="tooltips" data-placement="right" data-original-title="Dependencies" onclick="dependency_html('<?php echo $week_task['task_id'];?>');" href="javascript:void(0);"><i class="icon-chain wvicn red"> </i></a> 
																					</li>
																				<?php } elseif($completed_depencencies == 'green'){ ?>
																					<li class="no-bottom-space">
																						<a class="tooltips" data-placement="right" data-original-title="Dependencies" onclick="dependency_html('<?php echo $week_task['task_id'];?>');" href="javascript:void(0);"><i class="icon-chain wvicn green"> </i></a> 
																					</li>
																				<?php } else {} ?>
																			
																			<?php } ?>
																			<?php if($week_task['master_task_id'] != '0' ){ ?>
																				<?php  if($week_task['frequency_type']== 'one_off') {?>
																				<li class="no-bottom-space">
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Task disconnected from series"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>','task_tab_5');"><strike><i class="icon-refresh wvicn "> </i></strike></a>
                                                                                                                                                                </li>
                                                                                                                                                              <?php }else {?>
                                                                                                                                                                <li class="no-bottom-space">
                                    																	<a class="tooltips" data-placement="right" data-original-title="Recurring task"  onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_5');" href="javascript:void(0)">
																					<i class="icon-refresh wvicn"> </i></a>
                                                                                                                                                                </li>
                                                                                                                                                              <?php }?>
																			<?php } ?>
																			<?php 
																			if($chk == '1'){
																			
																			if($comments){
																				?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($week_task['master_task_id'] == '0' || $week_task['tm'] == '1'){ ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right"  data-original-title="Comments"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_7');"><i class="icon-comment-alt wvicn"></i><sup><?php echo count($comments);?></sup></a>
                                                                                                                                                                    <?php } else { ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Comments"  onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_7');" href="javascript:void(0)"><i class="icon-comment-alt wvicn"></i><sup><?php echo count($comments);?></sup></a>
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </li>
																			<?php } } ?>
																			<?php if($week_task['is_personal'] == '1'){ ?>
																				<li class="no-bottom-space"><a class="tooltips" data-placement="right" data-original-title="Private task"><i class="icon-eye-slash wvicn"></i></a></li>
																			<?php } ?>
                                                                                                                                                        <?php if($files){ ?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($week_task['master_task_id'] == '0' || $week_task['tm'] == '1'){ ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_6');"><i class="icon-paperclip wvicn"></i><sup><?php echo count($files);?></sup></a>
                                                                                                                                                                    <?php } else { ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_6');" href="javascript:void(0)"><i class="icon-paperclip wvicn"></i><sup><?php echo count($files);?></sup></a>
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </li>
																			<?php } ?>

                                                                                                                                                         <?php if($total_steps > 0){ ?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($week_task['master_task_id'] == '0' || $week_task['tm'] == '1'){ ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Steps"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_4');"><i class="icon-list-ul wvicn"></i><sup><span id="stepcom_<?php echo $week_task['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                                                                                                                    <?php } else { ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right"  data-original-title="Task Steps" onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_4');" href="javascript:void(0)"><i class="icon-list-ul wvicn"></i><sup><span id="stepcom_<?php echo $week_task['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </li>
																			<?php } ?>
																			
																			
																	 		
                                                                                                                                                          
																		</ul>
																	</div>
                                                                                                                                       <?php } ?>
                                                                                                                                        <div class="commicon-list clearfix custom-height" >
                                                                                                                                            <ul class="list-unstyled">
                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                    <span class="label-status label-<?php echo str_replace(' ', '',$week_task['task_status_name']);?>"><?php echo $week_task['task_status_name'];?></span>
                                                                                                                                                </li>
                                                                                                                                                <?php if($week_task['customer_id']!='' && $week_task['customer_id'] !='0'){?>
                                                                                                                                                    <li class="no-bottom-space">
                                                                                                                                                        <span class="label-status label-Greylight"><?php echo $customer->customer_name;?></span>
                                                                                                                                                    </li>
                                                                                                                                                <?php } if($week_task['swimlane_id'] !='' && $total_active_swimlane > 1){?>        
                                                                                                                                                        <li class="no-bottom-space">
                                                                                                                                                            <span class="label-status label-Greylight"><?php echo $swimlane;?></span>
                                                                                                                                                        </li>
                                                                                                                                                <?php }?>
                                                                                                                                                        <li class="chkbox new no-bottom-space marginTop"> <a href="javascript:void(0);" id="expand_div_symbol_<?php echo $week_task['task_id'];?>"   onclick="expand_div('<?php echo $week_task['task_id'];?>');task_ex_pos(<?php echo htmlspecialchars(json_encode($week_task)); ?>)"> <?php if($arr_ex=='0'){ ?> <i class="icon-cstexpand"> </i> <?php  }else{ ?> <i class="icon-cstcompress"> </i> <?php } ?> </a> </li>
																	 		<li class="chkbox new margin-bottom-3" id="up_status_<?php echo $week_task['task_id'];?>"> 
																	 		<?php 
																	 		if($week_task['task_status_id'] == $completed){
																					?>
                                                                                                                                                                         <label class="checkbox marginTop2">
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($week_task)) ?>,'<?php echo $ready;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?>  value="" checked="checked" /> 
																					 </label> 
																					<?php
																				} else {
																					?>
																					<label class="checkbox marginTop2">
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($week_task)) ?>,'<?php echo $completed;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" /> 
																					 </label>
																					<?php
																				}
																			?>
																			</li>
                                                                                                                                            </ul>
                                                                                                                                        </div>            
																</div>
															</div>
															
														</div>
                                                                                                            </div>
<?php date_default_timezone_set("UTC"); ?>
