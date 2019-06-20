<?php
$theme_url = base_url().getThemeName();
$cont = $this->uri->segment(1);
$fun = $this->uri->segment(2);
$ready = get_task_status_id_by_name('Ready');
$completed = $this->config->item('completed_id');
$company_flags = $this->config->item('company_flags');
$allow_past_task = "1";
if($company_flags){
	$allow_past_task = $company_flags['allow_past_task'];
}
if($this->session->userdata('Temp_calendar_user_id')=='0'){
    $user_swimlanes = get_user_swimlanes(get_authenticateUserID());
}else{
   $user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id'));
}
$total_active_swimlane = count_total_swimlanes();
$user_colors = $color_codes;
$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
$notscheduledtask = countnotscheduledtask();
$sorting_sortable = 'sortable';
if($calender_sorting !='1'){
    $sorting_sortable = '';
}
?>

<script src='<?php echo $theme_url; ?>/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?Ver=<?php echo VERSION;?>'></script>
<script type="text/javascript">
    $(document).ready(function(){
        cal_fill();
    });
</script>
<script src="<?php echo $theme_url;?>/assets/js/calender-weekview-common<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<?php date_default_timezone_set($this->session->userdata("User_timezone"));

$default_format = $this->config->item('company_default_format');
 ?>
<div class="mycaledar-table">

							<div class="cal-currentdate cstm_week_view">
							<a href="javascript:void(0)" onclick="change_view('<?php echo $start_date."#".$end_date."#prev"; ?>');"> <i class="stripicon mycalprev"> </i> </a>
                                                        <a class="tooltips cstm_week_view_sp css_schedule_task" data-placement="bottom" data-original-title="Tasks To Schedule" href="#" data-toggle="modal" onclick="open_backlog();" ><i class="icon-briefcase"> </i><span>Tasks To Schedule </span><span class="schedule_task_pill"><?php echo $notscheduledtask; ?></span> </a>
                                                        <a href="javascript:void(0);" onClick="showhide()" data-placement="bottom" data-original-title="Timer" class="tooltips calender-timer-icon timer_css" > <i class="icon-time" style="font-size:20px;" > </i><span  style="margin-left:0px;">Timer</span> </a>
                                                        <?php if($calender_team_user_id == get_authenticateUserID()){ $none = ""; }else{ $none = 'display:none'; } ?>
                                                                <span class="delegated_task_css" style="<?php echo $none; ?>">
                                                                    <input type="checkbox" name="other_user_task" id="other_user_task" value="" <?php if($show_other_user_task == 1){ echo 'checked="checked"'; }?>/> <span> Show Delegated Tasks</span>
                                                                </span>
                                                        
                                                        <div class="calendar-filter" style="margin-right: <?php if($calender_team_user_id == get_authenticateUserID()){echo "28%";}else{echo "35%";}?>" >
                                                            <ul class="list-unstyled">
                                                                <li  class="datetimepicker123"><span class="cstm_week_view_sp pull-left" style="margin-right: 5px;">
                                                                        <?php echo date("F d",strtotime(str_replace(array("/"," ",","), "-", $start_date))) ?> - <?php echo date("F d, Y",strtotime(str_replace(array("/"," ",","), "-", $end_date))) ?>
                                                                    </span>
                                                                    <i class="fa fa-calendar-o"></i><i class="fa fa-sort-desc"></i>
                                                                </li>
                                                            </ul>
                                                        </div>
								<a href="javascript:void(0)" onclick="change_view('<?php echo $start_date."#".$end_date."#next"; ?>');"> <i class="stripicon mycalnext"> </i> </a>

								<div class="calendar-filter">
									<ul class="list-unstyled">
                                                                                <li  <?php if($fun == "weekView"){?> class="active" <?php }?>> <a class="tooltips " data-placement="bottom" data-original-title="Weekly View" onclick="save_last_calender_view('1');" href="<?php echo site_url('calendar/weekView');?>"> <i class="stripicon weekicon"> </i> </a> </li>
										<li <?php if($fun == "NextFiveDayView"){?> class="active" <?php }?>> <a class="tooltips " data-placement="bottom" data-original-title="Next-Five Day View" onclick="save_last_calender_view('2');" href="<?php echo site_url('calendar/NextFiveDayView');?>"> <i class="stripicon dayicon"> </i> </a> </li>
										<li> <a class="tooltips " data-placement="bottom" data-original-title="Monthly View" onclick="save_last_calender_view('3');" href="<?php echo site_url('calendar/myCalendar');?>"> <i class="stripicon monthicon"> </i> </a> </li>
									</ul>
								</div>
							</div>
                                                        <input type="hidden" name="current_date" id="current_date" value="<?php echo date("Y-m-d");?>"/>
							<input type="hidden" name="start_date" id="week_start_date" value="<?php echo $start_date;?>" />
							<input type="hidden" name="week_end_date" id="week_end_date" value="<?php echo $end_date; ?>" />
							<input type="hidden" name="action" id="week_action" value="<?php if(isset($action) && $action!=''){ echo $action; } else { echo ''; } ?>" />
						 <table class="table " id="sample_1">
									<thead>
										<tr>
											<?php

											$offdays = get_company_offdays();
											foreach($date_arr as $date)
											{
												$ori_dt = change_date_format($date);
												$date_m = date("m",strtotime($ori_dt));
												$date_y = date("Y",strtotime($ori_dt));

												$total_working_day = month_total_working_day($date_m,$date_y,'',$offdays);
												$last_working_pos_day = $total_working_day - 5;
												$j = month_total_working_day($date_m,$date_y,$ori_dt,$offdays);
												if($j>$last_working_pos_day){
													$j = ($j-$total_working_day)-1;
												}

												?>
												<th width="180px;" style="max-width: 100px;">
                                                                                                     <input type="hidden" value="<?php echo strtotime($date);?>" name="day_strtotime_<?php echo $date;?>" id="day_strtotime_<?php echo $date;?>"/>
													<?php if(is_company_working_day($date_m,$date_y,$ori_dt,$offdays)){ ?>
														<span class="weekday-txt"> WD<?php echo $j; ?>   </span>
													<?php } else { ?>

													<?php } ?>

												<?php if($allow_past_task == "0" && strtotime(date("Y-m-d"))>strtotime(str_replace(array("/"," ",","), "-", $date))){ ?>
												<?php } else {

													$mm = date("r",strtotime(str_replace(array("/"," ",","),"-",$date)));

													 ?>
                                                                                                  <!--add icon removed-->

												<?php

												} ?>
											<?php echo date("l - d", strtotime(str_replace(array("/"," ",","), "-", $date))); ?></th>

											<?php

											}
											?>
											 <input type="hidden" name="last_day" id="last_day" value="<?php echo $j; ?>" />

										</tr>
									</thead>
									<tbody>
										<?php $weekly_tasks = get_calender_weekly_tasks($start_date,$end_date,$calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,$calender_sorting,$completed);
                                                                                        
                                                                                        $user_lists = getUserListFromTask(get_authenticateUserID());
                                                                                        if($user_lists != 0 && $calender_team_user_id == get_authenticateUserID()){
                                                                                            $users_task = get_calender_weekly_tasks($start_date,$end_date,$calender_project_id,$left_task_status_id,'users',$calender_date,$cal_user_color_id,$calender_sorting,$completed,'other_user');
                                                                                        }else{
                                                                                            $users_task = '';
                                                                                        }
//                                                                                        pr($users_task); die();
										?>

										<tr style="background-color: #FFFFFF;">
										<?php
                                                                                        if($calender_team_user_id=='#' || $calender_team_user_id == '0' )
                                                                                        {
                                                                                            $Mon_capacity=0;
                                                                                            $Tue_capacity=0;
                                                                                            $Wed_capacity=0;
                                                                                            $Thu_capacity=0;
                                                                                            $Fri_capacity=0;
                                                                                            $Sat_capacity=0;
                                                                                            $Sun_capacity=0;
                                                                                            $users_list = get_user_under_project($calender_project_id);
                                                                                                $team_capacity = array();
                                                                                               if(!empty($users_list)){
                                                                                                 foreach($users_list as $data_id){
                                                                                                     if(!empty($data_id)){
                                                                                                        $team_capacity[]= getUserCapacity($data_id->user_id);
                                                                                                     }
                                                                                                 }
                                                                                               }

                                                                                                //$team_capacity[] = getUserCapacity(get_authenticateUserID());
                                                                                                for($i=0;$i<count($team_capacity); $i++){
                                                                                                    $Mon_capacity += $team_capacity[$i]['MON_hours'] ;
                                                                                                    $Tue_capacity += $team_capacity[$i]['TUE_hours'] ;
                                                                                                    $Wed_capacity += $team_capacity[$i]['WED_hours'] ;
                                                                                                    $Thu_capacity += $team_capacity[$i]['THU_hours'] ;
                                                                                                    $Fri_capacity += $team_capacity[$i]['FRI_hours'] ;
                                                                                                    $Sat_capacity += $team_capacity[$i]['SAT_hours'] ;
                                                                                                    $Sun_capacity += $team_capacity[$i]['SUN_hours'] ;
                                                                                                }
                                                                                      }else{
                                                                                          if($this->session->userdata('Temp_calendar_user_id')=='0'){
                                                                                              $capacity = getUserCapacity(get_authenticateUserID());
                                                                                          }else{
                                                                                             $capacity = getUserCapacity($this->session->userdata('Temp_calendar_user_id'));
                                                                                          }

                                                                                             if($capacity){
												$Mon_capacity = $capacity['MON_hours'];
												$Tue_capacity = $capacity['TUE_hours'];
												$Wed_capacity = $capacity['WED_hours'];
												$Thu_capacity = $capacity['THU_hours'];
												$Fri_capacity = $capacity['FRI_hours'];
												$Sat_capacity = $capacity['SAT_hours'];
												$Sun_capacity = $capacity['SUN_hours'];
											}
                                                                                        }

                                                                                            $total_estimate=0;
                                                                                            $total_spent=0;
											foreach($date_arr as $date){
												$est_class = "";
												$day_name = date('D',strtotime(str_replace(array("/"," ",","), "-", $date)));
												if($day_name == "Mon"){$capacity = $Mon_capacity;} else if($day_name == "Tue"){$capacity = $Tue_capacity;} else if($day_name == "Wed"){$capacity = $Wed_capacity;} else if($day_name == "Thu"){$capacity=$Thu_capacity;} else if($day_name=='Fri'){$capacity=$Fri_capacity;} else if($day_name=="Sat"){$capacity=$Sat_capacity;}else {$capacity=$Sun_capacity;}
												if(isset($weekly_tasks[$date]) && $weekly_tasks[$date] != ''){
													$total_estimate = '0';
													$total_spent = '0';

													foreach($weekly_tasks[$date] as $week_task_time){
														if($week_task_time){
															$total_estimate += $week_task_time['task_time_estimate'];
															$total_spent += $week_task_time['task_time_spent'];
														}
													}

													$total_task_time_estimate_minute_1 = $total_estimate;
													$estimate_hours_1 = intval($total_task_time_estimate_minute_1/60);
													$estimate_minutes_1 = $total_task_time_estimate_minute_1 - ($estimate_hours_1 * 60);


													$total_task_time_spent_minute_1 = $total_spent;
													$spent_hours_1 = intval($total_task_time_spent_minute_1/60);
													$spent_minutes_1 = $total_task_time_spent_minute_1 - ($spent_hours_1 * 60);

													if($estimate_hours_1 != '0'){	$e_h_1 = $estimate_hours_1.'h'; } else { $e_h_1 = ''; }

													if($estimate_minutes_1 != '0'){ $e_m_1 = $estimate_minutes_1.'m'; }else{ $e_m_1 = ''; }

													if($spent_hours_1 != '0'){	$s_h_1 = $spent_hours_1.'h'; } else { $s_h_1 = ''; }

													if($spent_minutes_1 != '0'){ $s_m_1 = $spent_minutes_1.'m'; }else{ $s_m_1 = ''; }

													if($e_h_1 == '' && $e_m_1 == ''){
														$est_1 = '0m';
													} elseif($e_h_1 !='' && $e_m_1 == ''){
														$est_1 = $e_h_1;
													} elseif($e_h_1 =='' && $e_m_1 != ''){
														$est_1 = $e_m_1;
													} else {
														$est_1 = $e_h_1.''.$e_m_1;
													}

													if($s_h_1 == '' && $s_m_1 == ''){
														$spt_1 = '0m';
													} elseif($s_h_1 !='' && $s_m_1 == ''){
														$spt_1 = $s_h_1;
													} elseif($s_h_1 =='' && $s_m_1 != ''){
														$spt_1 = $s_m_1;
													} else {
														$spt_1 = $s_h_1.''.$s_m_1;
													}
													if($total_estimate>($capacity)){
														$est_class = "red";
													}
												} else {
													$est_1 = '0m';
													$spt_1 = '0m';
												}

												echo '<td style="max-width: 100px;">';?>
                                                                                            <div class="text-center hrmintitle unsorttd progress_new progress_<?php echo strtotime($date);?>" id="progress_<?php echo strtotime($date);?>">
                                                                                    <?php
                                                                                            if($capacity != 0){
                                                                                                $estcolor=($total_estimate*100)/$capacity;
                                                                                            }else{
                                                                                                $estcolor = 0;
                                                                                            }
                                                                                            if($total_estimate!=0){
                                                                                                $spentcolor=($total_spent*100)/$total_estimate;
                                                                                            }else{
                                                                                                if($capacity != 0){
                                                                                                    $spentcolor=($total_spent*100)/$capacity;
                                                                                                }else{
                                                                                                    $spentcolor = 0;
                                                                                                }
                                                                                            }
                                                                                    if($capacity>$total_estimate){ ?>
                                                                                        <div id="capacity_<?php echo strtotime($date);?>" data-time="<?php echo $capacity;?>" data-html="true" class="progress tooltips" style="margin-bottom:0px;background-color: #ebeaea;" data-original-title="Capacity: <?php echo minutesToTime($capacity);?> <br>Estimate Time: <?php echo $est_1;?>  <br>Spent Time: <?php echo $spt_1;?>">
                                                                                            <?php if($total_estimate!=0)
                                                                                            {?>
                                                                                        <div id="est_<?php echo strtotime($date);?>" data-time="<?php echo $total_estimate;?>" class="progress-bar" role="progressbar" style="width: <?php echo $estcolor;?>%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"><div id="spent_<?php echo strtotime($date);?>" data-time="<?php echo $total_spent;?>" class="progress-bar bg-success" role="progressbar" style="width: <?php echo $spentcolor;?>%;background-color: #5cb85c!important;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div></div>
                                                                                            <?php }
                                                                                            else {?>
                                                                                        <div id="est_<?php echo strtotime($date);?>" data-time="<?php echo $total_estimate;?>" class="progress-bar" role="progressbar" style="width: <?php echo $estcolor;?>%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                        <div  id="spent_<?php echo strtotime($date);?>" data-time="<?php echo $total_spent;?>"  class="progress-bar bg-success" role="progressbar" style="width: <?php echo $spentcolor;?>%;background-color: #5cb85c!important;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                         </div>
                                                                                                <?php
                                                                                                }}
                                                                                                else
                                                                                                {
                                                                                                    if($total_estimate != 0){
                                                                                                        $spentcolor=($total_spent*100)/$total_estimate;
                                                                                                    }else{
                                                                                                        $spentcolor = 0;
                                                                                                    }
                                                                                                    ?>
                                                                                                    <div data-html="true" data-time="<?php echo $total_estimate;?>" id="est_<?php echo strtotime($date);?>" class="progress tooltips"  data-original-title="Capacity:<?php echo minutesToTime($capacity);?> <br>Estimate Time:<?php echo $est_1;?>  <br>Spent Time:<?php echo $spt_1;?>" style="background-color: red!important; margin-bottom:0px;">
                                                                                                    <div class="progress-bar" id="capacity_<?php echo strtotime($date);?>" data-time="<?php echo $capacity;?>"  role="progressbar" style="width: <?php if($total_estimate != 0){echo ($capacity*100)/$total_estimate;}else{ echo '0';}?>%;"  aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-success" data-time="<?php echo $total_spent;?>" role="progressbar" id="spent_<?php echo strtotime($date);?>" style="width: <?php echo $spentcolor;?>%;background-color: #5cb85c!important;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div></div>

                                                                                                    </div>
                                                                                               <?php  }?>
                                                                                                 </div></td><?php
												}
												 ?>
										</tr>
                                                                                <tr style="height: 65vh;">
											<?php
											foreach($date_arr as $date){
												$input_date = $date;
												 ?>
												<td id="td1" class="minhightweek" style="max-width: 100px;">
													<!--<div class="text-center hrmintitle margin-bottom-10 unsorttd"> 5h - 30m - 0m   style="padding-bottom: 25px;"</div>-->
													<div class="<?php echo $sorting_sortable; ?> full_task scroll_cal_week" id="week_<?php echo strtotime($date); ?>" >

                                                                                                            <?php if(isset($weekly_tasks[$date]) && !empty($weekly_tasks[$date])){
														foreach($weekly_tasks[$date] as $week_task){
															if($week_task){
																if (strpos($week_task['task_id'],'child') !== false) {
																    $chk = "0";
																} else {
																	$chk = "1";
																}
																//echo "<pre>"; print_r($week_task);
																if($chk == "1"){
																	$dependencies = $week_task['tpp'];
																	if($week_task['tpp']!='0' && $week_task['completed_depencencies']=="0"){
																		$completed_depencencies = "green";
																	} else if($week_task['tpp']=='0' && $week_task['completed_depencencies']=="0"){
																		$completed_depencencies = "green";
																	} else {
																		$completed_depencencies = "red";
																	}
																} else {
																	$dependencies = '';
																	$completed_depencencies = "";
																}

																if($week_task['task_priority'] == 'Low'){
																	$priority_cls = "green1";
																} elseif($week_task['task_priority'] == 'Medium'){
																	$priority_cls = "yellow1";
																} elseif($week_task['task_priority'] == 'High'){
																	$priority_cls = "red1";
																} else {
																	$priority_cls = "";
																}

																$chk_watch_list = $week_task['watch'];

																$is_master_deleted = $week_task['tm'];
//
																$color = $week_task['color_id'];
																if($week_task['outside_color_code']){
																	$outside_code = $week_task['outside_color_code'];
																} else {
																	$outside_code = '#e5e9ec';
																}

																if($week_task['color_code']){
																	$color_code = $week_task['color_code'];
																} else {
																	$color_code = '#fff';
																}
																//echo $color_menu;
																$comments = $week_task['comments'];
                                                                                                                                 $swimlane = get_swimlanes_name($week_task['swimlane_id']);
                                                                                                                                $customer = get_customer_detail($week_task['customer_id'],$week_task['task_company_id']);
													?>

                                                                                                            <?php if($weekly_tasks['color_menu']== 'false')
                                                                                                            {
                                                                                                                $color_code='#fff';
                                                                                                                $outside_code='#e5e9ec';
                                                                                                            }
                                                                                                                ?>



														<div onclick="save_task_for_timer(this,'<?php echo $week_task['task_id'];?>','<?php echo addslashes($week_task['task_title']);?>','<?php echo $week_task['task_time_spent'];?>','<?php echo $chk;?>','<?php echo $completed_depencencies;?>');" id="main_<?php echo $week_task['task_id'];?>" class="task_div week_master_<?php echo $week_task['master_task_id'];?> <?php if($calender_sorting!='1'){  ?> unsorttd<?php } ?> <?php echo $priority_cls;?>" >
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
                                                                                                                        "date" =>strtotime($input_date),
                                                                                                                        "active_menu" =>$active_menu,
                                                                                                                        "start_date" =>strtotime($start_date),
                                                                                                                        "end_date" =>strtotime($end_date),
                                                                                                                        "master_task_id" =>$week_task['master_task_id'],
                                                                                                                        "is_master_deleted" =>$is_master_deleted,
                                                                                                                        "chk_watch_list" =>$chk_watch_list,
                                                                                                                        "task_owner_id" =>$week_task['task_owner_id'],
                                                                                                                        "completed_depencencies" =>$completed_depencencies,
                                                                                                                        "color_menu" =>$weekly_tasks['color_menu'],
                                                                                                                        "swimlane_id" =>$week_task['swimlane_id'],
                                                                                                                        "task_status_id" => $week_task['task_status_id'],
                                                                                                                        "before_status_id" => '',
                                                                                                                        "report_user_list_id" => $report_user_list_id 
                                                                                                                    );

                                                                                                                    ?>
                                                                                                                    <div oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');">

                                                                                                                        <style>

																#main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .comm-title, #main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .comm-desc,#main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .com-brdbtm,#main_<?php echo $week_task['task_id'];?> .comm-box.whitebox .commicon-list{
																	border-bottom:1px dashed <?php echo $outside_code;?>;
																}

															</style>
                                                                                                                        <input type="hidden" id="task_color_menu" value="<?php echo $weekly_tasks['color_menu']; ?>" />
															<input type="hidden" id="task_data_<?php echo $week_task['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($week_task)); ?>" />
															<input type="hidden" id="hdn_due_date_<?php echo $week_task['task_id'];?>" value="<?php echo strtotime($week_task['task_due_date']);?>" />
															<input type="hidden" id="hdn_locked_due_date_<?php echo $week_task['task_id'];?>" value="<?php echo $week_task['locked_due_date'];?>" />
															<input type="hidden" id="or_color_<?php echo $week_task['task_id'];?>" name="or_color_id" value="<?php echo $outside_code;?>" />
                                                                                                                        <?php if($chk==0)
                                                                                                                        {
                                                                                                                            ?>
                                                                                                                        <input type="hidden" id="task_estimate_time_<?php echo $week_task['master_task_id'];?>" name="task_estimate_time" value="<?php echo $week_task['task_time_estimate']?>" />
                                                                                                                        <input type="hidden" id="task_spent_time_<?php echo $week_task['master_task_id'];?>" name="task_spent_time" value="<?php echo $week_task['task_time_spent']?>" />
                                                                                                                            <?php
                                                                                                                        }?>

                                                                                                                        <div class="dragbox" id="task_<?php echo $week_task['task_id'];?>" style="border : solid 1px <?php echo $outside_code;?>;">
																<div class="comm-box whitebox disabled_sort" style="background-color: <?php echo $color_code;?>">
																	<?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
																		<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>');">
																	<?php } else {?>
																		<a onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>');" href="javascript:void(0)">
																	<?php } ?>

																		<div class="comm-title clearfix">

																			<?php $project_name = '';
																				if($week_task['task_project_id']){
																					$project_name = $week_task['project_title'].' - ';
																				}
																				$title = $week_task['task_title'];

																				$est = '0m';
																					$spt = '0m';
																			?>
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

																			}
																			$cl = '';
																			if($est == "0m" && $spt == "0m"){
																				$cl = "display:none;";
																			}

																			?>
																				<div class="comtitle-LFD">

																					
																					<?php
                                                                                                                                                                        if($weekly_tasks['allocation_flag']=='true'){
                                                                                                                                                                            ?>
                                                                                                                                                                    <?php  $name = 'upload/user/'.$week_task['profile_image'];
                                                                                                                                                                                                //echo "image name ".$week_task['profile_image'];
                                                                                                                                                                                        if(($week_task['profile_image'] != '' || $week_task['profile_image'] != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$week_task['profile_image'])) { ?>
                                                                                                                                                                                           <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $week_task['allocated_user_name'];?>" alt="" src="<?php echo $s3_display_url.'upload/user/'.$week_task['profile_image']; ?>" />
                                                                                                                                                                                           <?php } else { ?>
                                                                                                                                                                                           <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $week_task['allocated_user_name'];?>" alt="" src="<?php echo $s3_display_url; ?>upload/user/no_image.jpg"  />
                                                                                                                                                                                           <?php } ?>
                                                                                                                                                                    <div class="comttime"  id="task_time_<?php echo $week_task['task_id'];?>" style="<?php echo $cl;?>"> <?php echo $est.'/'.$spt;  ?></div>

                                                                                                                                                                                    <div >
                                                                                                                                                                                       
                                                                                                                                                                                            <?php echo $project_name.''.$title; ?>
                                                                                                                                                                                            <?php
                                                                                                                                                                                            if($chk_watch_list){ ?>
                                                                                                                                                                                                    <span class="tooltips" data-placement="right" data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
                                                                                                                                                                                            <?php } ?>
                                                                                                                                                                                    </div>

                                                                                                                                                                            <?php }else{ ?>
                                                                                                                                                                    <?php   
                                                                                                                                                                    if($week_task['task_allocated_user_id'] != $week_task['task_owner_id']){
                                                                                                                                                                        $profile = get_task_owner_image($week_task['task_owner_id']);
                                                                                                                                                                                            $name = 'upload/user/'.$profile->profile_image;
                                                                                                                                                                                            if(($profile->profile_image != '' || $profile->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) { ?>
                                                                                                                                                                                           <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $profile->first_name.' '.$profile->last_name;?>" alt="" src="<?php echo $s3_display_url.$name; ?>" />
                                                                                                                                                                                           <?php } else { ?>
                                                                                                                                                                                           <img class="tooltips profile-image_task" data-placement="left" data-original-title="<?php echo $profile->first_name.' '.$profile->last_name;?>" alt="" src="<?php echo $s3_display_url; ?>upload/user/no_image.jpg"  />
                                                                                                                                                                    <?php } } ?>
                                                                                                                                                                                           <div class="comttime"  id="task_time_<?php echo $week_task['task_id'];?>" style="<?php echo $cl;?>"> <?php echo $est.'/'.$spt;  ?></div>
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

                                                                                                                                        <?php if($week_task['task_owner_id'] == get_authenticateUserID() && $weekly_tasks['allocation_flag']=='false'){
																		if($week_task['task_owner_id'] != $week_task['task_allocated_user_id']){
																			?>
																			<div class="duedate com-brdbtm ">
																				<div> Allocated to : <?php echo $week_task['allocated_user_name'];?></div>
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
																	if($week_task['ts']){
																		if($chk == '1'){
																			$steps = get_task_steps($week_task['task_id']);
																		} else {
																			$steps = get_task_steps($week_task['master_task_id']);
																		}
																		if($steps){
																			?>
																			<div class="comm-step" style="border-bottom : dashed 1px <?php echo $outside_code;?>;">
                                                                                                                                                                <div class="form-group" style="margin-bottom:0px !important;">
																					<?php

                                                                                                                                                                        foreach($steps as $st){
																						$stp_cl = '';
																						if($st['is_completed'] == '1'){
																							$stp_cl = 'step-complete-class'; $com_steps++;
																						}
                                                                                                                                                                                $total_steps++;
																						 ?>
																					 <label class="marginTop checkbox <?php echo $stp_cl;?>" id="step_class_<?php echo $st['task_step_id'];?>" style="margin-bottom:6px !important;">
                                                                                                                                                                             <input type="checkbox" name="step_chk" class="newcheckbox_task" onclick="chek_step('<?php echo $st['task_step_id'];?>','<?php echo $week_task['task_id'];?>');" value="<?php echo $st['task_step_id'];?>" <?php if($st['is_completed'] == '1'){ echo 'checked="checked"'; } ?>><?php echo $st['step_title']; ?>
																					 </label>
																					 <?php } ?>
																				 </div>
																			</div>
																			<?php
																		}
																	}
																	?>
																</div>
                                                                                                                                       <?php if($dependencies!='0' || $week_task['master_task_id'] != '0' || $comments!='0' || $week_task['is_personal'] != '0' || $week_task['files']!='0' || $total_steps !='0'){?>
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
																			<?php if($week_task['master_task_id'] != '0'){ ?>
                                                                                                                                                                <?php  if($week_task['frequency_type']== 'one_off') {?>
																				<li class="no-bottom-space">
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Task disconnected from series"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>','task_tab_5');"><strike><i class="icon-refresh wvicn"> </i></strike></a>
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
																			//$comments = get_task_comments($week_task['task_id']);
																			//echo date_default_timezone_get();
																			if($comments){?>
                                                                                                                                                                <li class="no-bottom-space">
																					<?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                                <a class="tooltips" data-placement="right" data-original-title="Comments"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_7');"><i class="icon-comment-alt wvicn"> </i><sup><?php echo $comments;?></sup></a>
                                                                                                                                                                        <?php } else { ?>
                                                                                                                                                                                <a class="tooltips" data-placement="right"  data-original-title="Comments"  onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_7');" href="javascript:void(0)"><i class="icon-comment-alt wvicn"> </i><sup><?php echo $comments;?></sup></a>
                                                                                                                                                                        <?php } ?>
                                                                                                                                                                </li>
                                                                                                                                                        <?php } } ?>
																			<?php if($week_task['is_personal'] == '1'){ ?>
																				<li class="no-bottom-space">
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Private task"><i class="icon-eye-slash wvicn"></i></a>
                                                                                                                                                                </li>
																			<?php } ?>
                                                                                                                                                        <?php if($week_task['files']!=0){ ?>
                                                                                                                                                                    <li class="no-bottom-space">
                                                                                                                                                                        <?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                                <a class="tooltips" data-placement="right" data-original-title="Task Files"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_6');"><i class="icon-paperclip wvicn"></i><sup><?php echo $week_task['files'];?></sup></a>
                                                                                                                                                                        <?php } else { ?>
                                                                                                                                                                                <a class="tooltips" data-placement="right" data-original-title="Task Files"  onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_6');" href="javascript:void(0)"><i class="icon-paperclip wvicn"></i><sup><?php echo $week_task['files'];?></sup></a>
                                                                                                                                                                        <?php } ?>
                                                                                                                                                                    </li>
                                                                                                                                                        <?php } ?>

                                                                                                                                                        <?php if($total_steps > 0){ ?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Task Steps"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_4');"><i class="icon-list-ul wvicn"></i><sup><span id="stepcom_<?php echo $week_task['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                                                                                                                    <?php } else { ?>
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Task Steps" onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_4');" href="javascript:void(0)"><i class="icon-list-ul wvicn"></i><sup><span id="stepcom_<?php echo $week_task['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </li>
                                                                                                                                                        <?php } ?>



																		</ul>
																	</div>
                                                                                                                                       <?php }?>
                                                                                                                                        <div class="commicon-list clearfix custom-height">
                                                                                                                                            <ul class="list-unstyled">
                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                    <span class="label-status label-<?php echo str_replace(' ', '',$week_task['task_status_name']);?>"><?php echo $week_task['task_status_name'];?></span>
                                                                                                                                                </li>
                                                                                                                                                <?php if($week_task['customer_id']!='' && $week_task['customer_id']!='0'){?>
                                                                                                                                                    <li class="no-bottom-space">
                                                                                                                                                        <span class="label-status label-Greylight"><?php echo $customer->customer_name;?></span>
                                                                                                                                                    </li>
                                                                                                                                                <?php } if($week_task['swimlane_id'] !=''  && $total_active_swimlane > 1){?>
                                                                                                                                                        <li class="no-bottom-space">
                                                                                                                                                            <span class="label-status label-Greylight"><?php echo $swimlane;?></span>
                                                                                                                                                        </li>
                                                                                                                                                <?php }?>
                                                                                                                                                <li class="chkbox new no-bottom-space marginTop"> <a href="javascript:void(0);" id="expand_div_symbol_<?php echo $week_task['task_id'];?>"   onclick="expand_div('<?php echo $week_task['task_id'];?>');task_ex_pos(<?php echo htmlspecialchars(json_encode($week_task)); ?>)"> <?php if($arr_ex=='0'){ ?> <i class="icon-cstexpand"> </i> <?php  }else{ ?> <i class="icon-cstcompress "> </i> <?php } ?> </a> </li>
                                                                                                                                                <li class="chkbox new margin-bottom-3" id="up_status_<?php echo $week_task['task_id'];?>">
																	 		<?php
																	 		if($week_task['task_status_id'] == $completed){
																					?>
																					<label class="checkbox marginTop2" >
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($week_task)) ?>,'<?php echo $ready;?>');" <?php if($completed_depencencies =='red'){ ?> disabled = disabled <?php } ?>  value="" checked="checked" />
																					 </label>
																					<?php
																				} else {
																					?>
																					<label class="checkbox marginTop2" >
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($week_task)) ?>,'<?php echo $completed;?>');" <?php if($completed_depencencies =='red'){ ?> disabled = disabled <?php } ?>  value="" />
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
                                                                                                      <?php } } }?>
                                                                                                            
                                                                                                            <?php if(isset($users_task[$input_date]) && !empty($users_task[$input_date])){ ?>
                                                                                                                <div  style="border-top:2px dotted red;padding-top: 5px !important;<?php if($show_other_user_task == 0){ echo 'display:none'; }?>" class="unsorttd" id="divide_<?php echo strtotime($input_date); ?>">
                                                                                                                    <?php foreach($users_task[$input_date] as $week_task){ ?>
                                                                                                                  
														<?php 	if($week_task){
																if (strpos($week_task['task_id'],'child') !== false) {
																    $chk = "0";
																} else {
																    $chk = "1";
																}

																if($chk == "1"){
																	$dependencies = $week_task['tpp'];
																	if($week_task['tpp']!='0' && $week_task['completed_depencencies']=="0"){
																		$completed_depencencies = "green";
																	} else if($week_task['tpp']=='0' && $week_task['completed_depencencies']=="0"){
																		$completed_depencencies = "green";
																	} else {
																		$completed_depencencies = "red";
																	}
																} else {
																	$dependencies = '';
																	$completed_depencencies = "";
																}

																if($week_task['task_priority'] == 'Low'){
																	$priority_cls = "green1";
																} elseif($week_task['task_priority'] == 'Medium'){
																	$priority_cls = "yellow1";
																} elseif($week_task['task_priority'] == 'High'){
																	$priority_cls = "red1";
																} else {
																	$priority_cls = "";
																}

																$chk_watch_list = $week_task['watch'];

																$is_master_deleted = $week_task['tm'];

																$color = $week_task['color_id'];
																if($week_task['outside_color_code']){
																	$outside_code = $week_task['outside_color_code'];
																} else {
																	$outside_code = '#e5e9ec';
																}

																if($week_task['color_code']){
																	$color_code = $week_task['color_code'];
																} else {
																	$color_code = '#fff';
																}
                                                                                                                                

																$comments = $week_task['comments'];
                                                                                                                                $swimlane = get_swimlanes_name($week_task['swimlane_id']);
                                                                                                                                $customer = get_customer_detail($week_task['customer_id'],$week_task['task_company_id']);
													?>
                                                                                                        <div onclick="save_task_for_timer(this,'<?php echo $week_task['task_id'];?>','<?php echo addslashes($week_task['task_title']);?>','<?php echo $week_task['task_time_spent'];?>','<?php echo $chk;?>','<?php echo $completed_depencencies;?>');" id="main_<?php echo $week_task['task_id'];?>" class="unsorttd task_div week_master_<?php echo $week_task['master_task_id'];?> <?php echo $priority_cls;?>" style="padding-bottom: 5px;<?php if($show_other_user_task == 0){ echo 'display:none'; }?>">
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
                                                                                                                        "user_colors" =>get_user_color_codes($week_task['task_allocated_user_id']),
                                                                                                                        "user_swimlanes" =>$user_swimlanes,
                                                                                                                        "task_id" =>$week_task['task_id'],
                                                                                                                        "locked_due_date" => $week_task['locked_due_date'],
                                                                                                                        "task_due_date" =>date("m-d-Y",strtotime($week_task['task_due_date'])),
                                                                                                                        "task_scheduled_date" =>date("m-d-Y",strtotime($week_task['task_scheduled_date'])),
                                                                                                                        "date" =>strtotime($input_date),
                                                                                                                        "active_menu" =>$active_menu,
                                                                                                                        "start_date" =>strtotime($start_date),
                                                                                                                        "end_date" =>strtotime($end_date),
                                                                                                                        "master_task_id" =>$week_task['master_task_id'],
                                                                                                                        "is_master_deleted" =>$is_master_deleted,
                                                                                                                        "chk_watch_list" =>$chk_watch_list,
                                                                                                                        "task_owner_id" =>$week_task['task_owner_id'],
                                                                                                                        "completed_depencencies" =>$completed_depencencies,
                                                                                                                        "color_menu" =>'true',
                                                                                                                        "swimlane_id" =>$week_task['swimlane_id'],
                                                                                                                        "task_status_id" => $week_task['task_status_id'],
                                                                                                                        "before_status_id" => '',
                                                                                                                        "report_user_list_id" => $report_user_list_id
                                                                                                                    );

                                                                                                                    ?>
                                                                                                                           <div oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>')" >

				
                                                                                                                        <input type="hidden" id="task_color_menu" value="<?php echo 'true'; ?>"/>
															<input type="hidden" id="task_data_<?php echo $week_task['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($week_task)); ?>" />
															<input type="hidden" id="hdn_due_date_<?php echo $week_task['task_id'];?>" value="<?php echo strtotime($week_task['task_due_date']);?>" />
															<input type="hidden" id="hdn_locked_due_date_<?php echo $week_task['task_id'];?>" value="<?php echo $week_task['locked_due_date'];?>" />
															<input type="hidden" id="or_color_<?php echo $week_task['task_id'];?>" name="or_color_id" value="<?php echo $outside_code;?>" />
                                                                                                                        <?php if($chk==0){ ?>
                                                                                                                        <input type="hidden" id="task_estimate_time_<?php echo $week_task['master_task_id'];?>" name="task_estimate_time" value="<?php echo $week_task['task_time_estimate']?>" />
                                                                                                                        <input type="hidden" id="task_spent_time_<?php echo $week_task['master_task_id'];?>" name="task_spent_time" value="<?php echo $week_task['task_time_spent']?>" />
                                                                                                                        <?php  } ?>

															<div class="" id="task_<?php echo $week_task['task_id'];?>" style="border : solid 1px <?php echo $outside_code;?>;">
																<div class="comm-box whitebox disabled_sort" style="background-color: <?php echo $color_code;?>">
																	<?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
																		<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>');">
																	<?php } else { ?>
																		<a onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>');" href="javascript:void(0)">
																	<?php } ?>

																		<div class="comm-title clearfix">
																			<?php $project_name = '';
																				if($week_task['task_project_id']){
																					$project_name = $week_task['project_title'].' - ';
																				}
																				$title = $week_task['task_title'];

																				$est = '0m';
																					$spt = '0m';
																			?>
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

																			}
																			$cl = '';
																			if($est == "0m" && $spt == "0m"){
																				$cl = "display:none;";
																			}

																			?>
																				<div class="comtitle-LFD">
                                                                                                                                                                        <?php  $user_name = explode(' ',$week_task['allocated_user_name']);
                                                                                                                                                                               $word1 = ucfirst(substr($user_name[0],0,1));
                                                                                                                                                                               $word2 = ucfirst(substr($user_name[1],0,1)); ?>
                                                                                                                                                                        <span class="tooltips pull-right" data-placement="left" data-html="true" data-original-title="Allocated to <br><?php echo $week_task['allocated_user_name']; ?>" user-letters="<?php echo $word1.$word2; ?>"></span>
                                                                                                                                                                        <div class="comttime"  id="task_time_<?php echo $week_task['task_id'];?>" style="<?php echo $cl;?>;margin-top: 3px;"> <?php echo $est.'/'.$spt;  ?></div>
                                                                                                                                                                                <div>
                                                                                                                                                                                       
                                                                                                                                                                                            <?php echo $project_name.''.$title; ?>
                                                                                                                                                                                            <?php
                                                                                                                                                                                            if($chk_watch_list){ ?>
                                                                                                                                                                                                    <span class="tooltips" data-placement="right" data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
                                                                                                                                                                                            <?php } ?>
                                                                                                                                                                                </div>
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
//                                                                                                                                                        if(strlen($desc) > 100) {
//																			    $desc = substr($desc, 0, 100).'...';
//																			}
																		?>
                                                                                                                                    <div class="comm-desc"><p>  <?php echo nl2br($desc);?></p></div>
																		<?php
																	} ?>
																	<?php if($week_task['task_owner_id'] == get_authenticateUserID()){
																		if($week_task['task_owner_id'] != $week_task['task_allocated_user_id']){
																			?>
																			<div class="duedate com-brdbtm ">
																				<div> Allocated to : <?php echo $week_task['allocated_user_name'];?></div>
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
																	if($week_task['ts']){
																		if($chk == '1'){
																			$steps = get_task_steps($week_task['task_id']);
																		} else {
																			$steps = get_task_steps($week_task['master_task_id']);
																		}
																		if($steps){
																			?>
																			<div class="comm-step" style="border-bottom : dashed 1px <?php echo $outside_code;?>;">
                                                                                                                                                                 <div class="form-group" style="margin-bottom:0px !important">
																					<?php

                                                                                                                                                                        foreach($steps as $st){
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
																			<?php
																		}
																	}
																	?>
																</div>
                                                                                                                                    <?php if($dependencies!='0' || $week_task['master_task_id'] != '0' || $comments!='0' || $week_task['is_personal'] != '0' || $week_task['files']!='0' || $total_steps !='0'){ ?>

																	<div class="commicon-list clearfix" >
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
																			<?php if($week_task['master_task_id'] != '0'){ ?>
                                                                                                                                                              <?php  if($week_task['frequency_type']== 'one_off') {?>
                                                                                                                                                                        <li class="no-bottom-space">
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task disconnected from series"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>','task_tab_5');"><strike><i class="icon-refresh wvicn "></i></strike></a>
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
																			//$comments = get_task_comments($week_task['task_id']);
																			//echo date_default_timezone_get();

																			if($comments){?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                                        <a class="tooltips" data-placement="right" data-original-title="Comments"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_7');"><i class="icon-comment-alt wvicn"> </i><sup><?php echo $comments;?></sup></a>
                                                                                                                                                                                <?php } else { ?>
                                                                                                                                                                                        <a class="tooltips" data-placement="right"  data-original-title="Comments"  onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_7');" href="javascript:void(0)"><i class="icon-comment-alt wvicn"> </i><sup><?php echo $comments;?></sup></a>
                                                                                                                                                                                <?php } ?>
                                                                                                                                                                </li>
																				<?php } } ?>
																			<?php if($week_task['is_personal'] == '1'){ ?>
                                                                                                                                                                    <li class="no-bottom-space">
                                                                                                                                                                        <a class="tooltips" data-placement="right" data-original-title="Private task"><i class="icon-eye-slash wvicn"></i></a>
                                                                                                                                                                    </li>
																			<?php } ?>
                                                                                                                                                        <?php if($week_task['files']!=0){ ?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_6');"><i class="icon-paperclip wvicn"></i><sup><?php echo $week_task['files'];?></sup></a>
                                                                                                                                                                    <?php } else { ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_6');" href="javascript:void(0)"><i class="icon-paperclip wvicn"></i><sup><?php echo $week_task['files'];?></sup></a>
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </li>
																			<?php } ?>

                                                                                                                                                        <?php if($total_steps > 0){ ?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($week_task['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Steps"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $week_task['task_id'];?>','<?php echo $chk;?>', 'task_tab_4');"><i class="icon-list-ul wvicn"></i><sup><span id="stepcom_<?php echo $week_task['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                                                                                                                    <?php } else { ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Steps" onclick="open_seris(this,'<?php echo $week_task['task_id'];?>','<?php echo $week_task['master_task_id'];?>','<?php echo $chk;?>','task_tab_4');" href="javascript:void(0)"><i class="icon-list-ul wvicn"></i><sup><span id="stepcom_<?php echo $week_task['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </li>
																			<?php } ?>



                                                                                                                                                </ul>
																	</div>
                                                                                                                                    <?php }?>
                                                                                                                                        <div class="commicon-list clearfix custom-height" >
                                                                                                                                            <ul class="list-unstyled">
                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                    <span class="label-status label-<?php echo str_replace(' ', '',$week_task['task_status_name']);?>"><?php echo $week_task['task_status_name'];?></span>
                                                                                                                                                </li>
                                                                                                                                                <?php if($week_task['customer_id']!='' && $week_task['customer_id']!='0'){?>
                                                                                                                                                <li class="no-bottom-space" >
                                                                                                                                                        <span class="label-status label-Greylight"><?php echo $customer->customer_name;?></span>
                                                                                                                                                    </li>
                                                                                                                                                <?php } if($week_task['swimlane_id'] !='' && $total_active_swimlane > 1){?>
                                                                                                                                                        <li class="no-bottom-space" >
                                                                                                                                                            <span class="label-status label-Greylight"><?php echo $swimlane;?></span>
                                                                                                                                                        </li>
                                                                                                                                                <?php }?>
                                                                                                                                                <li class="chkbox new no-bottom-space marginTop"> <a href="javascript:void(0);" id="expand_div_symbol_<?php echo $week_task['task_id'];?>"   onclick="expand_div('<?php echo $week_task['task_id'];?>');task_ex_pos(<?php echo htmlspecialchars(json_encode($week_task)); ?>)"> <?php if($arr_ex=='0'){ ?> <i class="icon-cstexpand"> </i> <?php  }else{ ?> <i class="icon-cstcompress "> </i> <?php } ?> </a> </li>
                                                                                                                                                <li class="chkbox new margin-bottom-3" id="up_status_<?php echo $week_task['task_id'];?>">
																	 		<?php
																	 		if($week_task['task_status_id'] == $completed){
																					?>
                                                                                                                                                                         <label class="checkbox marginTop2">
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($week_task)) ?>,'<?php echo $ready;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?> value="" checked="checked" />
																					 </label>
																					<?php
																				} else {
																					?>
                                                                                                                                                                         <label class="checkbox marginTop2">
																						<input type="checkbox" onclick="update_status_complete(<?php echo htmlspecialchars(json_encode($week_task)) ?>,'<?php echo $completed;?>');" <?php if($completed_depencencies ==='red'){ ?> disabled = disabled <?php } ?>  value="" />
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

													<?php
													}
                                                                                                    } ?>
                                                                                                                </div>
                                                                                                            <?php } ?>
                                                                                                            <?php if(empty($weekly_tasks[$input_date]) && empty($users_task[$input_date])){ ?>
                                                                                                                    <div class='space'></div>
                                                                                                            <?php } ?>
                                                                                                             <div  id="add_newTask_<?php echo strtotime($input_date);?>" class="unsorttd">
                                                                                                               <div  class="">
                                                                                                                       <div class="before_timer"  style="border : solid 1px #e5e9ec;">
                                                                                                                           <div class="comm-box whitebox disabled_sort before_timer default_color" >
                                                                                                                               <div class="">
                                                                                                                                   <div class="" >
                                                                                                                                       <div  onClick="add_task_title('<?php echo strtotime($input_date);?>','<?php echo $input_date; ?>');" class="red  new_addTask" id="icon_addTask_<?php echo strtotime($input_date);?>">
                                                                                                                                          <i class="icon-plus task_adding_icon" ></i>
                                                                                                                                       </div>
                                                                                                                                       <input type="hidden" name="task_create_date_<?php echo strtotime($input_date); ?>" id="task_create_date_<?php echo strtotime($input_date); ?>" value="<?php echo $input_date;?>"/>
                                                                                                                                   </div>
                                                                                                                                </div>
                                                                                                                           </div>
                                                                                                                       </div>
                                                                                                               </div>
                                                                                                           </div>
                                                                                                        </div>
                                                                                                    </td>
												<?php } ?>

										 </tr>
                                                                        </tbody>
								</table>

<?php date_default_timezone_set("UTC"); ?>
