<?php 
$total_estimate = 0;
$total_spent=0;
$estimet = "0";
$spent = "0";
if($kanban_task_completed)
{ 
	$st_before_completed = get_status_id_before_completed($this->config->item('completed_id'));
	
	?>
<script type="text/javascript">
$(document).ready(function(){
	 App.init();
});
</script>
<style type="text/css">
	.pulsate {
		border: 1px dashed #0088CC; padding: 5px;
	}
</style>

<?php 

date_default_timezone_set($this->session->userdata("User_timezone"));

														
														foreach($kanban_task_completed as $kanban){
															
															if($kanban){
																$total_estimate += $kanban['task_time_estimate'];
													            $total_spent += $kanban['task_time_spent'];
																if (strpos($kanban['task_id'],'child') !== false) {
																    $chk = "0";
																} else {
																	$chk = "1";
																}
																if($chk == "1"){
																	$dependencies = $kanban['tpp']; 
																	if($kanban['tpp']!='0' && $kanban['completed_depencencies']=="0"){
																		$completed_depencencies = "green";
																	} else if($kanban['tpp']=='0' && $kanban['completed_depencencies']=="0"){
																		$completed_depencencies = "green";
																	} else {
																		$completed_depencencies = "red";
																	}
																} else {
																	$dependencies = ""; 
																	$completed_depencencies = "";
																}
																$color = $kanban['color_id'];
																if($kanban['outside_color_code']){
																	$outside_code = $kanban['outside_color_code'];
																} else {
																	$outside_code = '#e5e9ec';
																}
																if($kanban['color_code']){
																	$color_code = $kanban['color_code'];
																} else {
																	$color_code = '#fff';
																}
																$comments = $kanban['comments'];
																$swimlane = get_swimlanes_name($kanban['swimlane_id']);$customer = get_customer_detail($kanban['customer_id'],$kanban['task_company_id']);
																if($kanban['master_task_id']){
																	$is_master_deleted = chk_master_task_id_deleted($kanban['master_task_id']);
																} else {
																	$is_master_deleted = 0;
																}
																
																if($kanban['task_priority'] == 'Low'){
																	$priority_cls = "green1";
																} elseif($kanban['task_priority'] == 'Medium'){
																	$priority_cls = "yellow1"; 
																} elseif($kanban['task_priority'] == 'High'){
																	$priority_cls = "red1";
																} else {
																	$priority_cls = "";
																}
																
																if($kanban['is_personal'] == '1' && $kanban['task_owner_id'] != get_authenticateUserID()){
																	
																} else {
																	?>
																	
															
														
														<div onclick="save_task_for_timer(this,'<?php echo $kanban['task_id'];?>','<?php echo addslashes($kanban['task_title']);?>','<?php echo $kanban['task_time_spent'];?>','<?php echo $chk;?>','<?php echo $completed_depencencies;?>');" id="main_<?php echo $kanban['task_id'];?>" class="pulsate <?php if($kanban['task_status_id'] != $completed_id){ ?> kanban_master_<?php echo $kanban['master_task_id'];?> <?php } ?> <?php if($completed_depencencies === 'red'){  ?> unsorttd<?php } ?> <?php echo $priority_cls;?>"  >
														<div oncontextmenu="context_menu('<?php echo $kanban['task_id'];?>','<?php echo $kanban['task_status_id'];?>','<?php echo $kanban['swimlane_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $is_master_deleted;?>','<?php echo $st_before_completed;?>','<?php echo $completed_depencencies;?>','<?php echo $kanban['watch'];?>','<?php echo $kanban['task_owner_id'];?>');">
														<style>
														
															#main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .comm-title, #main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .comm-desc,#main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .com-brdbtm,#main_<?php echo $kanban['task_id'];?> .comm-box.whitebox .commicon-list{
																border-bottom:1px dashed <?php echo $outside_code;?>;
															}
														
														</style>
															<input type="hidden" id="task_data_<?php echo $kanban['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($kanban)); ?>" />
															<input type="hidden" id="or_color_<?php echo $kanban['task_id'];?>" name="or_color_id" value="<?php echo $outside_code;?>" />
															<div class="dragbox" id="task_<?php echo $kanban['task_id'];?>" style="border : solid 1px <?php echo $outside_code;?>;">
																<div class="comm-box whitebox disabled_sort" style="background-color: <?php echo $color_code;?>">
																	<?php if($kanban['master_task_id'] == '0' || $is_master_deleted=="1"){ ?>
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
																					$project_name = $kanban['project_title'].' - ';
																				}
																				$title = $kanban['task_title'];
																				$cl = '';
																				if($est == "0m" && $spt == "0m"){
																					$cl = 'display:none;';
																				}
																			?>
																			<div class="comtitle-LFD"> 
																				<div class="comttime"  id="task_time_<?php echo $kanban['task_id'];?>" style="<?php echo $cl;?>"> <?php echo $est.'/'.$spt;  ?></div> 
																				<?php echo $project_name.''.$title; ?>
																				<?php $chk_watch_list = $kanban['watch'];
																				if($chk_watch_list){ ?>
																					<span class="tooltips" data-placement="left" data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
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
																	<?php if($kanban['task_owner_id'] == get_authenticateUserID()){
																		if($kanban['task_owner_id'] != $kanban['task_allocated_user_id']){
																			?>
																			<div class="duedate com-brdbtm ">
																				<div> Allocated to : <?php echo $kanban['allocated_user_name'];?></div>
																			</div>
																			<?php
																		}
																	}?>
																	<?php if($kanban['task_due_date']!='0000-00-00'){
																		$task_due_date = change_date_format($kanban['task_due_date']);
																		
																		if($task_due_date == date("Y-m-d")){
																			$date = 'Today';
																		} elseif($task_due_date == date("Y-m-d",strtotime("-1 days"))){
																			$date = 'Yesterday';
																		} elseif($task_due_date == date("Y-m-d",strtotime("+1 days"))){
																			$date = 'Tomorrow';
																		} else {
																			$date = date($site_setting_date,strtotime(str_replace(array("/"," ",","), "-", $task_due_date)));
																		} ?>
																		<?php
																		if($kanban['task_status_id'] == $completed_id){
																			?>
																			<div class="duedate com-brdbtm ">
																				<div> Due : <?php echo $date;?> <?php if($kanban['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
																			</div>
																			<?php
																		} else { 
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
																		}?>
																	<?php
																	if($kanban['ts']){
																		if($chk == '1'){
																			$steps = get_task_steps($kanban['task_id']);
																		} else {
																			$steps = get_task_steps($kanban['master_task_id']);
																		}
																		if($steps){
																			?>
																			<div class="comm-step">
																				<div class="form-group">
																					<?php foreach($steps as $st){ 
																						$stp_cl = '';
																						if($st['is_completed'] == '1'){
																							$stp_cl = 'step-complete-class';
																						}
																						 ?>
																					 <label class="checkbox <?php echo $stp_cl;?>" id="step_class_<?php echo $st['task_step_id'];?>">
																						<input type="checkbox" name="step_chk" onclick="chek_step('<?php echo $st['task_step_id'];?>','<?php echo $kanban['task_id'];?>');" value="<?php echo $st['task_step_id'];?>" <?php if($st['is_completed'] == '1'){ echo 'checked="checked"'; } ?>><?php echo $st['step_title']; ?>
																					 </label>
																					 <?php } ?>
																				 </div>
																			</div>
																			<?php
																		}
																	}
																	?>
																</div>
																	<div class="commicon-list clearfix">
																		<ul class="list-unstyled">
																			
																			<?php 
																			if($dependencies){
																				if($completed_depencencies == 'red'){ ?> 
																					<li class="no-bottom-space">
																						<a class="tooltips" data-placement="right" data-original-title="Dependencies" onclick="dependency_html('<?php echo $kanban['task_id'];?>');" href="javascript:void(0);"><i class="stripicon dependenciesred"> </i></a> 
																					</li>
																				<?php } elseif($completed_depencencies == 'green'){ ?>
																					<li class="no-bottom-space">
																						<a class="tooltips" data-placement="right" data-original-title="Dependencies" onclick="dependency_html('<?php echo $kanban['task_id'];?>');" href="javascript:void(0);"><i class="stripicon dependenciesgreen"> </i></a> 
																					</li>
																				<?php } else {} ?>
																			
																			<?php } ?>
																			<?php if($kanban['master_task_id'] != '0'){ ?>
																				<?php  if($kanban['frequency_type']== 'one_off') {?>
																				<li class="no-bottom-space">
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Task disconnected from series"  href="javascript:void(0);"><i class="nonrecurring_icon "> </i></a>
                                                                                                                                                                </li>
                                                                                                                                                              <?php }else {?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Recurring task"  href="javascript:void(0);"><i class="icon-refresh myknbn"> </i></a>
                                                                                                                                                                </li>
                                                                                                                                                              <?php }?>
																			<?php } ?>
																			<?php 
																			if($chk == '1'){
																			
																			if($comments){
																				?>
																				<li class="no-bottom-space"><a class="tooltips" data-placement="right" data-original-title="Comments" onclick="comments_html('<?php echo $kanban['task_id'];?>');" href="javascript:void(0);"><i class="stripicon commentsicon"> </i></a></li>
																				<?php
																			} } ?>
																			<?php if($kanban['is_personal'] == '1'){ ?>
																				<li class="no-bottom-space"><a class="tooltips" data-placement="right" data-original-title="Private task"><i class="stripicon taskviewicon"></i></a></li> 
																			<?php } ?>
																			
																			<li class="chkbox new no-bottom-space"> <a href="javascript:void(0);" id="expand_div_symbol_<?php echo $kanban['task_id'];?>" onclick="expand_div('<?php echo $kanban['task_id'];?>');task_ex_pos(<?php echo htmlspecialchars(json_encode($kanban)); ?>);"> <?php if($arr_ex=='0'){ ?> <i class="stripicon icondownarrow"> </i> <?php  }else{ ?> <i class="stripicon iconuparrow"> </i> <?php } ?> </a> </li>
																	 		<li class="chkbox new margin-bottom-3" id="up_status_<?php echo $kanban['task_id'];?>"> 
																	 		<?php 
																	 			$ready = $ready_id;
																				$completed = $completed_id;
																				if($kanban['task_status_id'] == $completed){
																					?>
																					<label class="checkbox">
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($kanban)) ?>,'<?php echo $ready;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" checked="checked" /> 
																					 </label> 
																					<?php
																				} else {
																					?>
																					<label class="checkbox">
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($kanban)) ?>,'<?php echo $completed;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" /> 
																					 </label>
																					<?php
																				}
																			?>
																			</li>
																			<?php if($kanban['customer_id']){?>
                                                                                                                                                <li><span class="label-status label-Greylight"><?php echo $customer->customer_name;?></span></li>
                                                                                                                                                        <?php }?>
																		</ul>
																	</div>
																</div>
															</div>
															</div>
														</div>
														

														
														<?php
																}
																
															 ?>
														<?php 
				
                                                 } 

                                              } 
                               }  
else {
	echo "sj";
}
                                              ?> RGB
                                              

<?php 
 $estimate = calculate_completed_time($estimat_orig,$total_estimate);
$spent= calculate_completed_time($spent_orig,$total_spent);

						
           							echo '<div class="text-center hrmintitle  unsorttd" id="status_time_'.$completed_id.'"><span id="Estimate_time_'.$completed_id.'" class="hrlft tooltips" data-original-title="Estimate Time">'.$estimate.'</span><span class="hrrlt tooltips" data-original-title="Spent Time" id="spent_time_'.$completed_id.'">'.$spent.'</span></div>';		
									
?>RGB

<?php 
if($kanban_task_completed)
{
	echo $limit_whole + count($kanban_task_completed);
}
else {
	echo "0";
}

date_default_timezone_set("UTC");
 ?>
