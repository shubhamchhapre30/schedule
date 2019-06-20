<?php 

if($kanban!=''){ ?>

<script type="text/javascript">
$(document).ready(function(){
	$(".full_task div").addClass("before_timer");
	App.init();
});

</script>

<?php
date_default_timezone_set($this->session->userdata("User_timezone"));
$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
$ready_id = get_task_status_id_by_name('Ready');
$completed_id = $this->config->item('completed_id');
$chk = chk_task_exists($kanban['task_id']);
$dependencies = get_task_dependencies($kanban['task_id']); 
$completed_depencencies = chk_dependency_status($kanban['task_id'],$completed_id);
$task_status = get_taskStatus($this->session->userdata('company_id'),'Active'); 
if($this->session->userdata('Temp_kanban_user_id')== '0'){
    $swimlanes = get_user_swimlanes(get_authenticateUserID());
    $color_codes = get_user_color_codes(get_authenticateUserID());
}else{
    $swimlanes = get_user_swimlanes($this->session->userdata('Temp_kanban_user_id'));
    $color_codes = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
}
if($chk){
	$outside_code = get_outside_color_code(get_user_task_color($kanban['task_id'],$kanban['task_allocated_user_id']));
	$color_code = get_task_color_code(get_user_task_color($kanban['task_id'],$kanban['task_allocated_user_id']));
} else {
	$outside_code = get_outside_color_code(get_user_task_color($kanban['master_task_id'],$kanban['task_allocated_user_id']));
	$color_code = get_task_color_code(get_user_task_color($kanban['master_task_id'],$kanban['task_allocated_user_id']));
}
$st_before_completed = get_status_id_before_completed($completed_id);
$comments = get_task_comments($kanban['task_id']);
$files = get_task_files($kanban['task_id']);

if($kanban['task_priority'] == 'Low'){
	$priority_cls = "green1";
} elseif($kanban['task_priority'] == 'Medium'){
	$priority_cls = "yellow1"; 
} elseif($kanban['task_priority'] == 'High'){
	$priority_cls = "red1";
} else {
	$priority_cls = "";
}
if($color_menu =='false'){
    $color_code='#fff';
    $outside_code='#e5e9ec';
}
$user_colors = $color_codes;
if($kanban['task_scheduled_date'] == '0000-00-00')
$task_scheduled_date='';
else
$task_scheduled_date =  date("m-d-Y",strtotime($kanban['task_scheduled_date']));
if($kanban['task_due_date'] == '0000-00-00')
$task_due_date = '';
else
$task_due_date =  date("m-d-Y",strtotime($kanban['task_due_date']));

                                                                                                            $report_user_list_id='';
                                                                                                                        if(isset($all_report_user) && !empty($all_report_user)){
                                                                                                                            foreach($all_report_user as $val ){
                                                                                                                                if($val['user_id']==$kanban['task_owner_id']){
                                                                                                                                   $report_user_list_id='1';  
                                                                                                                                }
                                                                                                                            }
                                                                                                                          }else{
                                                                                                                             $report_user_list_id='0';
                                                                                                                          }

$jsonarray=array(
"task_status" =>$task_status,
"user_colors" =>$user_colors,
"user_swimlanes" =>$swimlanes,
"task_id" =>$kanban['task_id'],
"locked_due_date" => $kanban['locked_due_date'],
"task_due_date" =>$task_due_date,
"task_scheduled_date" =>$task_scheduled_date,
"date" =>'', 
"active_menu" =>'from_kanban',
"start_date" =>'',
"end_date" =>'',
"master_task_id" =>$kanban['master_task_id'],
"is_master_deleted" =>chk_master_task_id_deleted($kanban['master_task_id']),
"chk_watch_list" =>check_my_watch_list($kanban['task_id'],get_authenticateUserID()),
"task_owner_id" =>$kanban['task_owner_id'],
"completed_depencencies" =>$completed_depencencies,
"color_menu" =>'true',
"swimlane_id" =>$kanban['swimlane_id'],
"task_status_id" => $kanban['task_status_id'],
"before_status_id" => $st_before_completed,
 "report_user_list_id" => $report_user_list_id 
);

 ?>
 
<div onclick="save_task_for_timer(this,'<?php echo $kanban['task_id'];?>','<?php echo addslashes($kanban['task_title']);?>','<?php echo $kanban['task_time_spent'];?>','<?php echo $chk;?>','<?php echo $completed_depencencies;?>');" id="main_<?php echo $kanban['task_id'];?>" class="<?php if($kanban['task_status_id'] != $completed_id){ ?> kanban_master_<?php echo $kanban['master_task_id'];?> <?php } ?> <?php if($completed_depencencies === 'red'){  ?> unsorttd<?php } ?> <?php echo $priority_cls;?> "  >
		<div oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');">
		<style>
		
			#main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .comm-title, #main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .comm-desc,#main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .com-brdbtm,#main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .commicon-list{
				border-bottom:1px dashed <?php echo $outside_code;?>;
			}
		
		</style>
                        <input type="hidden" id="kanban_color_menu" value="<?php echo $color_menu;?>" />
			<input type="hidden" id="task_data_<?php echo $kanban['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($kanban)); ?>" />
			<input type="hidden" id="or_color_<?php echo $kanban['task_id'];?>" name="or_color_id" value="<?php echo $outside_code;?>" />
			<div class="dragbox" id="task_<?php echo $kanban['task_id'];?>" style="border : solid 1px <?php echo $outside_code;?>;">
				<div class="comm-box whitebox disabled_sort" style="background-color: <?php echo $color_code;?>">
					<?php if($kanban['master_task_id'] == '0' || chk_master_task_id_deleted($kanban['master_task_id'])=="1"){ ?>
						<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>')" >
					<?php } else { ?>
						<a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $kanban['task_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $chk;?>');">
					<?php } ?>
					
						<div class="comm-title clearfix">
							
									<?php 
									$est = '0m';
									$spt = '0m';
							if($kanban['task_time_estimate']!='0' || $kanban['task_time_spent']!='0'){
								
								$total_task_time_estimate_minute = $kanban['task_time_estimate'];
								$estimate_hours = intval($total_task_time_estimate_minute/60);
								$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
								
								
								$total_task_time_spent_minute = $kanban['task_time_spent'];
								$spent_hours = intval($total_task_time_spent_minute/60);
								$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
								
								if($estimate_hours != '0'){	$e_h = $estimate_hours.'h'; } else { $e_h = ''; }
								
								if($estimate_minutes != '0'){ $e_m = $estimate_minutes.'m'; }else{ $e_m = ''; }
								
								if($spent_hours != '0'){	$s_h = $spent_hours.'h'; } else { $s_h = ''; }
								
								if($spent_minutes != '0'){ $s_m = $spent_minutes.'m'; }else{ $s_m = ''; }
								$est = '0m';
								if($e_h == '' && $e_m == ''){
									$est = '0m';
								} elseif($e_h !='' && $e_m == ''){
									$est = $e_h;
								} elseif($e_h =='' && $e_m != ''){
									$est = $e_m;																					
								} else {
									$est = $e_h.''.$e_m;
								}
								$spt = '0m';
								if($s_h == '' && $s_m == ''){
									$spt = '0m';
								} elseif($s_h !='' && $s_m == ''){
									$spt = $s_h;
								} elseif($s_h =='' && $s_m != ''){
									$spt = $s_m;																					
								} else {
									$spt = $s_h.''.$s_m;
								}
							}?>
							<?php $project_name = '';
								if($kanban['task_project_id']){
									$project_name = get_project_name($kanban['task_project_id']).' - ';
								}
								$title = $kanban['task_title'];
								
								$cl = '';
								if($est == "0m" && $spt == "0m"){
									$cl = 'display:none;';
								}
                                                                $customer = get_customer_detail($kanban['customer_id'],$kanban['task_company_id']);
							?>
							<div class="comtitle-LFD"> 
                                                                <?php if($color_menu=='false'){
                                                                    ?>
                                                                            
                                                                                    <div>
                                                                                        <?php  $name = 'upload/user/'.$kanban['profile_image'];
                                                                                             if(($kanban['profile_image']!= '' || $kanban['profile_image'] != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$kanban['profile_image'])) { ?>
                                                                                                <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo usernameById($kanban['task_allocated_user_id']);?>" alt="" src="<?php echo $s3_display_url.'upload/user/'.$kanban['profile_image']; ?>" />
                                                                                                    <?php } else { ?>
                                                                                                <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo usernameById($kanban['task_allocated_user_id']);?>" alt="" src="<?php echo $s3_display_url; ?>upload/user/no_image.jpg"  />
                                                                                                    <?php } ?>
                                                                                                <div class="comttime"  id="task_time_<?php echo $kanban['task_id'];?>" style="<?php echo $cl;?>"> <?php echo $est.'/'.$spt;  ?></div> 
                                                                                                <?php echo $project_name.''.$title; ?>

                                                                                                <?php $chk_watch_list = check_my_watch_list($kanban['task_id'],get_authenticateUserID());
                                                                                                if($chk_watch_list){ ?>
                                                                                                        <span class="tooltips" data-placement="left" data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
                                                                                                <?php } ?>
                                                                                    </div>
                                                                           

                                                                    <?php }else{ ?>  
                                                                                    <div>
                                                                                       <?php 
                                                                                       if($kanban['task_owner_id'] != $kanban['task_allocated_user_id']){
                                                                                       $profile = get_task_owner_image($kanban['task_owner_id']);
                                                                                                  $name = 'upload/user/'.$profile->profile_image;
                                                                                                  if(($profile->profile_image != '' || $profile->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) { ?>
                                                                                                  <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $profile->first_name.' '.$profile->last_name;?>" alt="" src="<?php echo $s3_display_url.$name; ?>" />
                                                                                                  <?php } else { ?>
                                                                                                  <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $profile->first_name.' '.$profile->last_name;?>" alt="" src="<?php echo $s3_display_url; ?>upload/user/no_image.jpg"  />
                                                                                       <?php } }?>
                                                                                                  <div class="comttime"  id="task_time_<?php echo $kanban['task_id'];?>" style="<?php echo $cl;?>"> <?php echo $est.'/'.$spt;  ?></div>
                                                                                                <?php echo $project_name.''.$title; ?>

                                                                                                <?php $chk_watch_list = check_my_watch_list($kanban['task_id'],get_authenticateUserID());
                                                                                                if($chk_watch_list){ ?>
                                                                                                        <span class="tooltips" data-placement="left" data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
                                                                                                <?php } ?>
                                                                                    </div>
                                                                    <?php } ?>
								
							</div>
					
							
						</div>
					</a>
					
					<?php if($chk == "1"){
						if($kanban['task_ex_pos']=='0'){
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
				<div id="expand_div_<?php echo $kanban['task_id'];?>" <?php echo $style_ex; ?> >
					<?php if($kanban['task_description']){
							$desc = $kanban['task_description'];
							
						?>
						<div class="comm-desc"> <p> <?php echo nl2br($desc);?> </p></div>
						<?php
					} ?>
					<?php if($kanban['task_owner_id'] == get_authenticateUserID() && $color_menu=='true'){
						if($kanban['task_owner_id'] != $kanban['task_allocated_user_id']){
							?>
							<div class="duedate com-brdbtm ">
								<div> Allocated to : <?php echo usernameById($kanban['task_allocated_user_id']);?></div>
							</div>
							<?php
						}
					}?>
                                              
                                                
					<?php 
					
					if($kanban['task_due_date']!='0000-00-00'){
						$task_due_date = $kanban['task_due_date'];
						
						if($task_due_date == date("Y-m-d")){
							$date = 'Today';
						} elseif($task_due_date == date("Y-m-d",strtotime("-1 days"))){
							$date = 'Yesterday';
						} elseif($task_due_date == date("Y-m-d",strtotime("+1 days"))){
							$date = 'Tomorrow';
						} else {
							$date = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $task_due_date)));
						}
						date_default_timezone_set("UTC");
						 ?>
						<?php
						if($kanban['task_status_id'] == $completed_id){ ?>
							<div class="duedate com-brdbtm ">
								<div> Due : <?php echo $date;?> <?php if($kanban['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
							</div>
						<?php } else {
						 if($task_due_date < date("Y-m-d")){ ?>
							<div class="duedate com-brdbtm ">
								<div class="red"> Overdue : <?php echo $date;?> <?php if($kanban['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
							</div>
						<?php } else { ?>
							<div class="duedate com-brdbtm ">
								<div> Due : <?php echo $date;?> <?php if($kanban['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
							</div>
						<?php } ?>
						<?php
						}
					}
					
					?>
                                                
					<?php 
                                         $total_steps=0;
                                         $com_steps=0;
					if($chk == '1'){
					$steps = get_task_steps($kanban['task_id']);
					if($steps){?>
					<div class="comm-step">
						<div class="form-group">
							<?php foreach($steps as $st){
								$stp_cl = '';
								if($st['is_completed'] == '1'){
									$stp_cl = 'step-complete-class';
                                                                        $com_steps++;
								}
								$total_steps++;
								 ?> 
							 <label class="checkbox <?php echo $stp_cl;?>" id="step_class_<?php echo $st['task_step_id'];?>">
                                                             <input type="checkbox" name="step_chk" class="newcheckbox_task" onclick="chek_step('<?php echo $st['task_step_id'];?>','<?php echo $kanban['task_id'];?>');" value="<?php echo $st['task_step_id'];?>" <?php if($st['is_completed'] == '1'){ echo 'checked="checked"'; } ?>><?php echo $st['step_title']; ?>
							 </label>
							 <?php } ?>
						 </div>
					</div>
					<?php }
					} ?>
					<?php if(strpos($kanban['task_id'], 'child') !== false){
						$steps = get_task_steps($kanban['master_task_id']);
						if($steps){?>
					<div class="comm-step">
						<div class="form-group">
							<?php foreach($steps as $st){
								$stp_cl = '';
								if($st['is_completed'] == '1'){
									$stp_cl = 'step-complete-class';
                                                                        $com_steps++;
								}
                                                                $total_steps++;
								 ?> 
							 <label class="checkbox <?php echo $stp_cl;?>" id="step_class_<?php echo $st['task_step_id'];?>">
								<input type="checkbox" name="step_chk" onclick="chek_step('<?php echo $st['task_step_id'];?>','<?php echo $kanban['task_id'];?>');" value="<?php echo $st['task_step_id'];?>" <?php if($st['is_completed'] == '1'){ echo 'checked="checked"'; } ?>><?php echo $st['step_title']; ?>
							 </label>
							 <?php } ?>
						 </div>
					</div>
					<?php } 
					} ?>
				</div>
                                    <?php /* if($dependencies!='0' || $kanban['master_task_id'] != '0' || $comments!='0' || $kanban['is_personal'] != '0' || $files !='0' || $total_steps !='0'){ */?>                      
					<div class="commicon-list clearfix">
						<ul class="list-unstyled">
							
							<?php 
							if($dependencies){
								if($completed_depencencies == 'red'){ ?> 
									<li class="no-bottom-space">
										<a class="tooltips" data-placement="right" data-original-title="Dependencies" onclick="dependency_html('<?php echo $kanban['task_id'];?>');" href="javascript:void(0);"><i class="icon-chain myknbn red"> </i></a> 
									</li>
								<?php } elseif($completed_depencencies == 'green'){ ?>
									<li class="no-bottom-space">
										<a class="tooltips" data-placement="right" data-original-title="Dependencies" onclick="dependency_html('<?php echo $kanban['task_id'];?>');" href="javascript:void(0);"><i class="icon-chain myknbn green"> </i></a> 
									</li>
								<?php } else {} ?>
							
							<?php } ?>
							<?php if($kanban['master_task_id'] != '0'){ ?>
								<?php  if($kanban['frequency_type']== 'one_off') {?>
									<li class="no-bottom-space">
                                                                            <a class="tooltips" data-placement="right" data-original-title="Task disconnected from series"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>','task_tab_5');"><strike><i class="icon-refresh myknbn "> </i></strike></a>
                                                                        </li>
                                                                <?php }else {?>
                                                                        <li class="no-bottom-space">
                                                                            <a class="tooltips" data-placement="right" data-original-title="Recurring task"  onclick="open_seris(this,'<?php echo $kanban['task_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $chk;?>','task_tab_5');" href="javascript:void(0)"><i class="icon-refresh myknbn"> </i></a>
                                                                        </li>
                                                                <?php }?>
							<?php } ?>
							<?php if($chk == '1'){
                                                                if($comments){ ?>
                                                                        <li class="no-bottom-space">
										<?php if($kanban['master_task_id'] == '0' || $kanban['tm'] =="1"){ ?>
                                                                                    <a class="tooltips" data-placement="right" data-original-title="Comments" href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>','task_tab_7')" ><i class="icon-comment-alt myknbn"> </i><sup><?php echo count($comments);?></sup></a>
										<?php } else { ?>
                                                                                 <a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $kanban['task_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $chk;?>', 'task_tab_7');"><i class="icon-comment-alt myknbn"> </i><sup><?php echo count($comments);?></sup></a>
                                                                                <?php } ?>
									</li>
								<?php } } ?>
							<?php if($kanban['is_personal'] == '1'){ ?>
								<li class="no-bottom-space" ><a class="tooltips" data-placement="right" data-original-title="Private task"><i class="icon-eye-slash myknbn"></i></a></li> 
							<?php } ?>
                                                        <?php 
                                                            if($files){ ?>
                                                                <li class="no-bottom-space">
									<?php if($kanban['master_task_id'] == '0' || $kanban['tm'] == '1'){ ?>
                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>', 'task_tab_6');"><i class="icon-paperclip myknbn"></i><sup><?php echo count($files);?></sup></a>
									<?php } else { ?>
                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  onclick="open_seris(this,'<?php echo $kanban['task_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $chk;?>','task_tab_6');" href="javascript:void(0)"><i class="icon-paperclip myknbn"></i><sup><?php echo count($files);?></sup></a>
									<?php } ?>
								</li>
                                                        <?php } ?>

                                                         <?php if($total_steps > 0){ ?>
                                                                <li class="no-bottom-space">
								    <?php if($kanban['master_task_id'] == '0' || $kanban['tm'] == '1'){ ?>
                                                                        <a class="tooltips" data-placement="right"  data-original-title="Task Steps"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>', 'task_tab_4');"><i class="icon-list-ul myknbn"></i><sup><span id="stepcom_<?php echo $kanban['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                    <?php } else { ?>
                                                                        <a class="tooltips" data-placement="right"  data-original-title="Task Steps" onclick="open_seris(this,'<?php echo $kanban['task_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $chk;?>','task_tab_4');" href="javascript:void(0)"><i class="icon-list-ul myknbn"></i><sup><span id="stepcom_<?php echo $kanban['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                    <?php } ?>
								</li>
                                                        <?php } ?>
							<li class="chkbox new no-bottom-space marginTop2"> <a href="javascript:void(0);" id="expand_div_symbol_<?php echo $kanban['task_id'];?>" onclick="expand_div('<?php echo $kanban['task_id'];?>');task_ex_pos(<?php echo htmlspecialchars(json_encode($kanban)); ?>);"> <?php if($arr_ex=='0'){ ?> <i class="icon-cstexpand"> </i> <?php  }else{ ?> <i class="icon-cstcompress"> </i> <?php } ?> </a> </li>
					 		<li class="chkbox new margin-bottom-3" id="up_status_<?php echo $kanban['task_id'];?>"> 
					 		<?php 
					 			$ready = $ready_id;
								$completed = $completed_id;
								if($kanban['task_status_id'] == $completed){
									?>
									<label class="checkbox marginTop5">
										<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($kanban)) ?>,'<?php echo $ready;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" checked="checked" /> 
									 </label> 
									<?php
								} else {
									?>
									<label class="checkbox marginTop5">
										<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($kanban)) ?>,'<?php echo $completed;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" /> 
									 </label>
									<?php
								}
							?>
							</li>
							
						</ul>
					</div>
                                    <?php //} ?>
                                                   
                                         <div class="commicon-list clearfix" >
                                                <ul class="list-unstyled">
                                                    <?php if($kanban['customer_id']){?> 
                                                        <li class="no-bottom-space">
                                                            <span class="label-status label-Greylight"><?php echo $customer->customer_name;?></span>
                                                        </li>
                                                    <?php }?>
                                                       
                                                </ul>
                                         </div> 
                                       
				</div>
			</div>
			</div>
		</div>
	<?php 
date_default_timezone_set("UTC");

} ?>
