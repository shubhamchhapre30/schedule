<?php
	$theme_url = base_url().getThemeName();
	$completed_id = $this->config->item('completed_id');
	$ready_id = get_task_status_id_by_name('Ready');
	$default_format = $this->config->item('company_default_format');
	$com_off_days = get_company_offdays();
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");

	$company_flags = $this->config->item('company_flags');
	$actaul_time_on = '0';
	if($company_flags){
		$actaul_time_on = $company_flags['actual_time_on'];
	}
	$st_before_completed = get_status_id_before_completed($completed_id);
        $user_colors = $color_codes;
        $active_menu = 'from_kanban';
        $s3_display_url = $this->config->item('s3_display_url');
        $bucket = $this->config->item('bucket_name');
        $total_active_swimlane = count_total_swimlanes();
?>
<style>
.add_task_new
{
    width: 100%;
}
</style>
<script type="text/javascript">
var baseUrl='<?php echo base_url(); ?>';
</script>

<script src="<?php echo $theme_url;?>/assets/js/swimR.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script src='<?php echo $theme_url; ?>/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?Ver=<?php echo VERSION;?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/css/context.standalone.css?Ver=<?php echo VERSION;?>">
<script src="<?php echo $theme_url;?>/js/context.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>

<script type="text/javascript">

	$(document).ready(function(){
		$("#task_swimlane_id").val('<?php echo get_default_swimlane($this->session->userdata('Temp_kanban_user_id'));?>');

	});

	var SIDEURL = '<?php echo site_url(); ?>';
	var CMT_TEXT_SIZE = '<?php echo CMT_TEXT_SIZE; ?>';
	var COMP_status_id = '<?php echo $completed_id;?>';
	var READY_status_id = '<?php echo $ready_id;?>';
	var LOGID = '<?php echo get_authenticateUserID(); ?>';
	var D_DATE_FORMAT = '<?php echo $default_format; ?>';
	var D_FOR = '<?php echo $date_arr_java[$default_format]; ?>';
	var COMP_OF_DAYS = '<?php echo $com_off_days;?>';
	var S3_DISPLAY_URL = '<?php echo $this->config->item('s3_display_url');?>';
	var USER_NAME = '<?php echo $this->session->userdata('username');?>';
	var ACTUAL_TIME_ON = '<?php echo $actaul_time_on;?>';
	var FILTER_ID = '<?php echo $this->session->userdata('Temp_kanban_user_id');?>';
        var ACTIVE_MENU = '<?php echo $active_menu;?>';




</script>

<script src="<?php echo $theme_url;?>/assets/js/main_kanban<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo $theme_url;?>/assets/js/mykanban<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/css/mykanban.css?Ver=<?php echo VERSION;?>">

<?php date_default_timezone_set($this->session->userdata("User_timezone")); ?>
<!-- BEGIN PAGE CONTAINER-->
<div class="container-fluid page-background kanban_consistency">
	<div class="mainpage-container">
		<div class="user-block" id="kanban_view">
			 <div class="row">
				<div class="col-md-12">
					<div class="kanban-table ">
					 <table class="table table-bordered table-full-width" id="sample_1">
						<thead>
							<tr>
								<?php if(isset($swimlanes) && $swimlanes!=''){ ?>
								<th width="120px;"><a href="javascript:void(0);" onClick="showhide()" data-placement="bottom" data-original-title="Timer" class="tooltips kanban-timer-icon" style="color: #fff;border: 1px white solid;border-radius: 5px;padding: 3px 12px 3px 7px;margin-top: -3px;margin-bottom: -3px;"> <i class="icon-time"  style="font-size:20px;"> </i> <span style="margin-left:0px;font-size: 16px;">Timer</span></a> &nbsp;</th>
								<?php } ?>
								<?php
								$status_arr = array();
								$i = 0;
								if(isset($task_status) && $task_status!=''){
									foreach($task_status as $status){
										$status_arr[$i++] = $status->task_status_id;
										?>
										<th id="th_hide_<?php echo $status->task_status_id;?>" style="display:none;" ><?php echo $status->task_status_name; ?></th>
										<th id="th_show_<?php echo $status->task_status_id;?>"><span id="th_<?php echo $status->task_status_id;?>" class="task_anchor"><?php echo ucwords($status->task_status_name); ?></span></th>
										<?php
									}
								}?>
							</tr>
						</thead>
						<tbody>
							<?php
							$spt_1 = '0m';
							$est_1 = '0m';
                                                                         
							if(isset($swimlanes) && $swimlanes!=''){
								echo '<tr style="background-color: #FFFFFF;"><td>&nbsp;</td>';
									foreach($status_arr as $st){
										$total_estimate = '0';
										$total_spent = '0';
										$kanban_total_task_statuswise =0;

										foreach($swimlanes as $row){

											if(isset($kanban_task[$row->swimlanes_id][$st])){

												$kanban_task_array = $kanban_task[$row->swimlanes_id][$st];
												$kanban_total_task_statuswise = $kanban_total_task_statuswise + count($kanban_task_array);
												foreach($kanban_task_array as $kanban_time){
													if($kanban_time){
														$total_estimate += $kanban_time['task_time_estimate'];
														$total_spent += $kanban_time['task_time_spent'];
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
											}
										}
									echo '<td class="td_'.$st.'" id="td_'.$st.'">';?>
                                                <div class="text-center hrmintitle  unsorttd" id="status_time_<?php echo $st;?>" <?php if($completed_id == $st) echo "style='display:none;'";?>>
                                                    <?php echo '<span class="hrlft tooltips"  data-original-title="Estimate Time" id="Estimate_time_'.$st.'">'.$est_1.'</span><span class="hrrlt tooltips" data-original-title="Spent Time" id="spent_time_'.$st.'">'.$spt_1.'</span></div></td>';
									echo '<td class="td_hideme'.$st.'" id="tdhideme_'.$st.'" style="display:none;">&nbsp;</td>';
                                 }
								echo "</tr>";
							}?>


							<?php
							if(isset($swimlanes) && $swimlanes!=''){
								foreach($swimlanes as $row){

									$stl = "style='height: ".$row->swimlane_height.";'";
									$stl = $row->swimlane_height.";";


                                                                        $withoutpx = str_replace("px", "", $row->swimlane_height);

									if($row->swimlane_height == 0 || $row->swimlane_height == "" || $row->swimlane_height == "0px")
									{
                                                                            $stl = "160px;";
                                                                            $withoutpx = "160";
                                                                        }

									if($row->swimlane_show_hide =='0'){
										$slsh = ' class="cstm_kanban" style="display:table-row"';
										$slsh_r = 'style="display:none"';
									}else{
										$slsh = 'style="display:none"';
										$slsh_r = 'class="cstm_kanban" style="display:table-row"';
									}


									?>

									<tr>
                                                                            <td class="td-padding"></td>
                                                                            <?php foreach($status_arr as $st1){ ?>
                                                                                    <td class="td-padding">
                                                                                            <div class="td-icon">
                                                                                            <!-- add icon removed-->
                                                                                            </div>
                                                                                    </td>
                                                                            <?php }?>
                                                                        </tr>

									<tr id="tr_<?php echo $row->swimlanes_id; ?>"  <?php echo $slsh;?> >
										<td class="tdtitle1" >
											<div class="h60 avs1" style="height: <?php echo $stl; ?>" id="sj_<?php echo $row->swimlanes_id; ?>">
                                                                                                <?php if(count($swimlanes) >1) { ?>
                                                                                                    <a href="javascript:void(0)" id="swimlane_<?php echo $row->swimlanes_id; ?>" data-fruit='yes' class="swimlane_anchor resizable-s ui-resizable-handle ui-resizable-s">  <i class="stripicon horhide" > </i>  </a><?php if($total_active_swimlane > 1){ echo $row->swimlanes_name;} ?></div>
                                                                                                <?php }else{ ?>
                                                                                                        </div>
                                                                                                            <a href="javascript:void(0)" id="swimlane_<?php echo $row->swimlanes_id; ?>" data-fruit='yes' class="swimlane_anchor resizable-s  ui-resizable-s">  <i class="stripicon horhide" > </i>  </a> <?php if($total_active_swimlane > 1){ echo $row->swimlanes_name; } ?>
                                                                                                        <?php }?>
											
										 </td>
                                                                                     <?php $i = 1;
                                                                                                foreach($status_arr as $st){
                                                                                                    if(isset($kanban_task[$row->swimlanes_id][$st])){
												$kanban_task_array = $kanban_task[$row->swimlanes_id][$st];

												?>

													<td class="td_hide_<?php echo $st;?> task_hide_anchor board-collapsedColumnNameCell column-collapsed" id="td_hide_<?php echo $st;?>" style="display:none;" >
														<div class="collapsed-info-wrapper">
                                                                                                                    <div class="collapsed-rotation" >
                                                                                                                        <h2 class="collapse_text" id="collapse_<?php echo $st;?>"></h2>
                                                                                                                    </div>
														</div>
													</td>
													<td class="td_<?php echo $st;?>" >
                                                                                                                <div   class="" id="add_newTask_<?php echo $row->swimlanes_id;?>_<?php echo $st;?>" style="padding-bottom: 5px;">
                                                                                                                    <div  class="before_timer">
                                                                                                                        <div class="before_timer"  style="border : solid 1px #e5e9ec;">
                                                                                                                            <div class="comm-box whitebox disabled_sort before_timer default_color" >
                                                                                                                                
                                                                                                                                <div class="">
                                                                                                                                    <div class=" " >
                                                                                                                                        <div  onClick="add_task_kanban(<?php echo $row->swimlanes_id;?>,<?php echo $st;?>);" class="red new_addTask before_timer" id="icon_addTask_<?php echo $row->swimlanes_id;?>_<?php echo $st;?>"> 
                                                                                                                                            <i class="icon-plus task_adding_icon" ></i>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div> 
														<div onscroll="scrolled(this,<?php echo $st;?>,<?php echo $row->swimlanes_id; ?>)" class="sortable full_task scroll1  scroll1<?php echo $row->swimlanes_id; ?>" id="task_status_<?php echo $st;?>_<?php echo $row->swimlanes_id;?>" style="overflow: auto;height: 160px;">


														<?php

														foreach($kanban_task_array as $kanban){
															if($kanban){

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
																	$dependencies = '';
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
                                                                                                                                if($kanban['color_name']){
                                                                                                                                    if($kanban['color_name'] == 'Green')
                                                                                                                                    {
                                                                                                                                        $color_class = 'task_Green';
                                                                                                                                    } else if($kanban['color_name'] == 'Orange')
                                                                                                                                    {
                                                                                                                                        $color_class = 'task_Orange';
                                                                                                                                    } else if($kanban['color_name'] == 'Purple')
                                                                                                                                    {
                                                                                                                                        $color_class = 'task_Purple';
                                                                                                                                    } else if($kanban['color_name'] == 'White')
                                                                                                                                    {
                                                                                                                                        $color_class = 'task_White';
                                                                                                                                    } else if($kanban['color_name'] == 'Yellow')
                                                                                                                                    {
                                                                                                                                        $color_class = 'task_Yellow';
                                                                                                                                    } else if($kanban['color_name'] == 'Blue')
                                                                                                                                    {
                                                                                                                                        $color_class = 'task_Blue';
                                                                                                                                    } else if($kanban['color_name'] == 'Red')
                                                                                                                                    {
                                                                                                                                        $color_class = 'task_Red';
                                                                                                                                    }
                                                                                                                                }else
                                                                                                                                {
                                                                                                                                    $color_class = 'task_Default';
                                                                                                                                }

																$comments = $kanban['comments'];
                                                                                                                                $customer = get_customer_detail($kanban['customer_id'],$kanban['task_company_id']);
																$is_master_deleted = $kanban['tm'];

																if($kanban['task_priority'] == 'Low'){
																	$priority_cls = "green1";
																} elseif($kanban['task_priority'] == 'Medium'){
																	$priority_cls = "yellow1";
																} elseif($kanban['task_priority'] == 'High'){
																	$priority_cls = "red1";
																} else {
																	$priority_cls = "";
																}
																?>



														<div onclick="save_task_for_timer(this,'<?php echo $kanban['task_id'];?>','<?php echo addslashes($kanban['task_title']);?>','<?php echo $kanban['task_time_spent'];?>','<?php echo $chk;?>','<?php echo $completed_depencencies;?>');" id="main_<?php echo $kanban['task_id'];?>" class="<?php echo $color_class;?> <?php if($kanban['task_status_id'] != $completed_id){ ?> kanban_master_<?php echo $kanban['master_task_id'];?> <?php } ?> <?php if($completed_depencencies == 'red'){  ?> unsorttd <?php } ?> <?php echo $priority_cls;?> "  >
															<?php 
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
                                                                                                                        if($kanban['task_scheduled_date'] == '0000-00-00')
                                                                                                                            $task_scheduled_date='';
                                                                                                                        else
                                                                                                                            $task_scheduled_date =  date("m-d-Y",strtotime($kanban['task_scheduled_date']));
                                                                                                                        if($kanban['task_due_date'] == '0000-00-00')
                                                                                                                            $task_due_date = '';
                                                                                                                        else
                                                                                                                            $task_due_date =  date("m-d-Y",strtotime($kanban['task_due_date']));
                                                                                                                       
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
                                                                                                                        "is_master_deleted" =>$is_master_deleted,
                                                                                                                        "chk_watch_list" =>$kanban['watch'],
                                                                                                                        "task_owner_id" =>$kanban['task_owner_id'],
                                                                                                                        "completed_depencencies" =>$completed_depencencies,
                                                                                                                        "color_menu" =>'true',
                                                                                                                        "swimlane_id" =>$kanban['swimlane_id'],
                                                                                                                        "task_status_id" => $kanban['task_status_id'],
                                                                                                                        "before_status_id" => $st_before_completed,
                                                                                                                         "report_user_list_id" => $report_user_list_id 
                                                                                                                    );
                                                                                                                    
                                                                                                                    ?>
                                                                                                                    <div oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');">
														
                                                                                                                
                                                                                                                        <input type="hidden" name="current_date" id="current_date" value="<?php echo date("Y-m-d") ?>"/>
                                                                                                                        <input type="hidden" name="color_menu" value='true'/>
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
																			<div class="comtitle-LFD" >
                                                                                                                                                                
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
                                                                                                                                                                        <?php $chk_watch_list = $kanban['watch'];
                                                                                                                                                                        if($chk_watch_list){ ?>
                                                                                                                                                                                <span class="tooltips" data-placement="left"  data-original-title="Watchlist"><i class="stripicon startyellowicon"> </i></span>
                                                                                                                                                                        <?php } ?>        
                                                                                                                                                                    </div>
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
//																			if(strlen($desc) > 100) {
//																			    $desc = substr($desc, 0, 100).'...';
//																			}
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

																		 $today = date("Y-m-d");

																		$task_due_date = $kanban['task_due_date'];
																		if($task_due_date == $today){
																			$date = 'Today';
																		} elseif($task_due_date == date("Y-m-d",strtotime("-1 days",strtotime($today)))){
																			$date = 'Yesterday';
																		} elseif($task_due_date == date("Y-m-d",strtotime("+1 days",strtotime($today)))){
																			$date = 'Tomorrow';
																		} else {
																			$date = date($site_setting_date,strtotime(str_replace(array("/"," ",","),"-", $task_due_date)));
																		} ?>
																		<?php
																		if($kanban['task_status_id'] == $completed_id){
																			?>
																			<div class="duedate com-brdbtm ">
																				<div> Due : <?php echo $date;?> <?php if($kanban['locked_due_date']){ ?><i class="stripicon lockicon"> </i><?php } ?> </div>
																			</div>
																			<?php
																		} else {
																			if($task_due_date < $today){ ?>
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
                                                                                                                                        $total_steps=0;
                                                                                                                                         $com_steps=0;
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
																					<?php foreach($steps as $st1){
																						$stp_cl = '';
																						if($st1['is_completed'] == '1'){
																							$stp_cl = 'step-complete-class';
                                                                                                                                                                                        $com_steps++;
																						}
                                                                                                                                                                                $total_steps++;
																						 ?>
																					 <label class="checkbox <?php echo $stp_cl;?>" id="step_class_<?php echo $st1['task_step_id'];?>">
                                                                                                                                                                             <input type="checkbox" name="step_chk" class="newcheckbox_task" onclick="chek_step('<?php echo $st1['task_step_id'];?>','<?php echo $kanban['task_id'];?>');" value="<?php echo $st1['task_step_id'];?>" <?php if($st1['is_completed'] == '1'){ echo 'checked="checked"'; } ?>><?php echo $st1['step_title']; ?>
																					 </label>
																					 <?php } ?>
																				 </div>
																			</div>
																			<?php
																		}
																	}
																	?>

																</div>
                                                                                                                                    <?php /* if($dependencies!='0' || $kanban['master_task_id'] != '0' || $comments!='0' || $kanban['is_personal'] != '0' || $kanban['files']!='0' || $total_steps !='0'){ */ ?>      
																	<div class="commicon-list clearfix" >
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
                                                                                                                                                                    <a class="tooltips" data-placement="right" data-original-title="Task disconnected from series"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>','task_tab_5');"><strike><i class="icon-refresh "> </i></strike></a>
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
																					<?php if($kanban['master_task_id'] == '0' || $is_master_deleted=="1"){ ?>
                                                                                                                                                                                <a class="tooltips" data-placement="right" data-original-title="Comments" href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>','task_tab_7')" ><i class="icon-comment-alt myknbn"></i><sup><?php echo $comments;?></sup></a>
                                                                                                                                                                        <?php } else { ?>
                                                                                                                                                                                <a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $kanban['task_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $chk;?>', 'task_tab_7');"><i class="icon-comment-alt myknbn"></i><sup><?php echo $comments;?></sup></a>
                                                                                                                                                                        <?php } ?>
                                                                                                                                                                </li>
																			<?php } } ?>
																			<?php if($kanban['is_personal'] == '1'){ ?>
																				<li class="no-bottom-space"><a class="tooltips" data-placement="right" data-original-title="Private task"><i class="icon-eye-slash myknbn"></i></a></li>
																			<?php } ?>
                                                                                                                                                        <?php if($kanban['files']!=0){ ?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($kanban['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>', 'task_tab_6');"><i class="icon-paperclip myknbn"></i><sup><?php echo $kanban['files'];?></sup></a>
                                                                                                                                                                    <?php } else { ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Files"  onclick="open_seris(this,'<?php echo $kanban['task_id'];?>','<?php echo $kanban['master_task_id'];?>','<?php echo $chk;?>','task_tab_6');" href="javascript:void(0)"><i class="icon-paperclip myknbn"></i><sup><?php echo $kanban['files'];?></sup></a>
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </li>
																			<?php } ?>

                                                                                                                                                         <?php if($total_steps > 0){ ?>
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                    <?php if($kanban['master_task_id'] == '0' || $is_master_deleted == '1'){ ?>
                                                                                                                                                                            <a class="tooltips" data-placement="right" data-original-title="Task Steps"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $kanban['task_id'];?>','<?php echo $chk;?>', 'task_tab_4');"><i class="icon-list-ul myknbn"></i><sup><span id="stepcom_<?php echo $kanban['task_id'];?>"><?php echo $com_steps;?></span><?php echo '/'.$total_steps;?></sup></a>
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
                                                                                                                                                    <?php if($kanban['customer_id']){ ?>
                                                                                                                                                    <div class="commicon-list clearfix" >
                                                                                                                                                        <ul class="list-unstyled">
                                                                                                                                                                <li class="no-bottom-space">
                                                                                                                                                                        <span class="label-status label-Greylight"><?php echo $customer->customer_name;?></span>
                                                                                                                                                                </li>
                                                                                                                                                         </ul>
                                                                                                                                                    </div>  
                                                                                                                                                     <?php } ?>
                                                                                                                                          
																</div>
															</div>

														</div>

                                                                                                            </div>
                                                                                                                <?php } } ?>
                                                                                                                    
													</div>

													<?php if($st == $completed_id && count($kanban_task_array)>=20) { ?>
                                                                                                                <a href="javascript:void(0)"  class="completed_loadMore" data-status="<?php echo $st; ?>" data-swim="<?php echo $row->swimlanes_id;  ?>" data-over="" id="completed_loadMore_<?php echo $st.$row->swimlanes_id; ?>" style="display: none;">View More</a>
                                                                                                                <input type="hidden" name="completed_loadMore_limit" id="completed_loadMore_limit<?php echo $st.$row->swimlanes_id; ?>" value="<?php echo "20"; ?>" />
                                                                                                        <?php  }?>
                                                                                                               

													</td>


													<?php $i++; }else{ ?>

														<td class="td_<?php echo $st;?>" >
                                                                                                                    <div   class="" id="add_newTask_<?php echo $row->swimlanes_id;?>_<?php echo $st;?>" style="padding-bottom: 5px;">
                                                                                                                    <div  class="before_timer">
                                                                                                                        <div class="before_timer"  style="border : solid 1px #e5e9ec;">
                                                                                                                            <div class="comm-box whitebox disabled_sort before_timer default_color" >
                                                                                                                                
                                                                                                                                <div class="">
                                                                                                                                    <div class=" " >
                                                                                                                                        <div  onClick="add_task_kanban(<?php echo $row->swimlanes_id;?>,<?php echo $st;?>);" class="red new_addTask before_timer" id="icon_addTask_<?php echo $row->swimlanes_id;?>_<?php echo $st;?>"> 
                                                                                                                                            <i class="icon-plus task_adding_icon" ></i>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div> 
                                                                                                                    <div onscroll="scrolled(this,<?php echo $st;?>,<?php echo $row->swimlanes_id; ?>)" class="sortable full_task scroll1  scroll1<?php echo $row->swimlanes_id; ?>" id="task_status_<?php echo $st;?>_<?php echo $row->swimlanes_id;?>" style="overflow: auto;height: 160px;">
                                                                                                                    </div>
														</td>

										    <?php } ?> 
                                                                                                               
                                                                                    <?php } ?>
									</tr>
                                                                        
									<tr  id="tr_hide_<?php echo $row->swimlanes_id; ?>" <?php echo $slsh_r;?> >
                                                                            <td class="tdtitle1"> <a href="javascript:void(0)" id="swimlaneclone_<?php echo $row->swimlanes_id; ?>" class="swimlane_cloneanchor"> <i class="stripicon horhide"> </i></a> <?php if($total_active_swimlane > 1) { echo $row->swimlanes_name; }?></td>
											<?php
											foreach($status_arr as $st){
												if(isset($kanban_task[$row->swimlanes_id][$st]) && $completed_id != $st){
												$kanban_task_array = $kanban_task[$row->swimlanes_id][$st];
													$t = 0;
													foreach($kanban_task_array as $kanban){
														if($kanban){

															$t++;
														}
													}
													?>
                                                                                        <td id="task_count_hide_<?php echo $st;?>_<?php echo $row->swimlanes_id; ?>" ><?php echo $t;?></td>
													<?php
												}
											}?>

									</tr>



									<script type="text/javascript">
										 $(function(){
											$('.scroll1<?php echo $row->swimlanes_id; ?>').slimScroll({
											color: '#17A3E9',
											height : '<?php echo $withoutpx; ?>',
									 	    wheelStep: 12,
									 	    showOnHover : true
										 });


										});
									</script>
								<?php
								}
							} else {} ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
                    <?php date_default_timezone_set("UTC"); 
 if(count($swimlanes)==1){ ?>
 <script>
    $(document).ready(function(){
        if_only_one_swimlane();
   });
   </script>
                                                                              
 <?php  }
?>
	</div>



            <div id="comments_add" class="modal  fade model-size commentbox-size" tabindex="-1" style="border-bottom:0px;">
		<div class="modal-header">
			<button type="button" class="close close_cmt" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Comments  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
				<div class="portlet-body  form flip-scroll">
					<div class="customtable horizontal-form">
						<div class="comment-block margin-bottom-20">
							<div class="scroll">
								<ul class="list-unstyled" id="comments_html">

								</ul>
							</div>
							<form action="" id="frm_add_comment_from_list" name="frm_add_comment_from_list" novalidate="novalidate">
								<div class="addcomment-block padding-10">
									<div class="row">
										<div class="col-md-12 ">
											<div class="form-group">
												<label for="comment" class="control-label"> <strong> Add Comment</strong></label>
												<div class="controls relative-position">
													<textarea class="col-md-12 m-wrap" id="task_comment_list" name="task_comment_list" rows="3"></textarea>
													<span id="task_comment_list_loading" class="input-load desc-load"></span>
												</div>
											</div>
                                                                                    <div class="pull-right" style="padding-top:10px;">
												<input type="hidden" value="" id="comment_list_task_id" name="task_id">
												<button class="btn blue txtbold" id="cmts_list_submit" type="submit"> Add Comments </button>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="dependency" class="modal model-size fade" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Dependency  </h3>
		</div>
		<div class="modal-body">
                    <div class="portlet" style="padding:10px">
				<div class="portlet-body  form flip-scroll">
					<div class="horizontal-form">
						<div class="customtable table-scrollable">
							<table class="table table-striped table-hover table-condensed flip-content">
								<thead class="flip-content">
									<tr>
										<th>Task</th>
										<th>Allocated</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="dependency_html">

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="recurring" class="modal model-size fade" tabindex="-1">

	</div>


	<div id="comments_right" class="modal model-size fade commentbox-size" tabindex="-1" >
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				<div class="modal-header">
					<button type="button" class="close right_cmt_close" data-dismiss="modal" aria-hidden="true"></button>
					<h3>Comments</h3>
				</div>
				<div>
					<form name="right_cmt" id="right_cmt" action="" >

						<div class="addcomment-block">
							<div class="row">
								<div class="col-md-12 ">
									<div class="form-group">
										<label class="control-label" for="firstName"> <strong> Add Comment :<span class="required">*</span> </strong></label>
										<div class="controls">
											<textarea rows="3" name="right_task_comment" maxlength="<?php echo CMT_TEXT_SIZE;?>" id="right_task_comment" class="col-md-12 m-wrap"></textarea>
										  </div>
									</div>
									<span class="chr">Char left :- <i id="ch_cmt"><?php echo CMT_TEXT_SIZE;?></i></span>
                                                                        <div class="pull-right" style="margin-top:10px;">
                                                                            <input type="hidden" name="cmt_active_menu" value="<?php echo $active_menu;?>" />

												<input type="hidden" name="redirect_page" value="<?php echo $active_menu;?>" />
												<input type="hidden" name="task_data" id="task_data" value="" />
										<input type="hidden" name="task_id" id="right_comment_task_id" value="" />
										<button type="submit" id="right_cmt_btn" class="btn blue txtbold"> Add Comments </button>
									</div>
								</div>
							 </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="delete_task" class="modal model-size pro-change fade" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Delete Task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
				<div class="portlet-body  form flip-scroll">

                                    <div class="form-group" style="padding:10px">
                                        <label class="control-label col-md-12" style="padding-left:0px;">Do you want to delete the series, this occurence or only future tasks?</label>
						<label class="control-label">Select :</label>
						<div class="controls">
							<label class="radio">
								<a id="delete_series" href="javascript:void(0);" ><input type="radio" value="" ></a>Task Series
							</label>
							<label class="radio">
								<a id="delete_ocuurence" href="javascript:void(0);" ><input type="radio" value="" ></a>Task Occurrence
							</label>
                                                        <label class="radio">
								<a id="delete_future" href="javascript:void(0);" ><input type="radio" value="" ></a>Future Tasks
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
        <div id="series_task_deletion" class="modal model-size pro-change fade" tabindex="-1">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3> Delete Task  </h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
						<div class="portlet-body  form flip-scroll">

							<div class="form-group"  style="padding:10px;">
                                                            <label class="control-label col-md-12" style="padding-left:0px;">Do you want to delete the entire series or future tasks only?</label>

								<div class="controls">
									<label class="radio ">
                                                                           <input type="radio" name="series_option" value="series" onclick="delete_kanban_task()">Task Series
									</label>
									<label class="radio ">
                                                                          <input type="radio" name="series_option" value="future" onclick="delete_kanban_task()">Future Tasks
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
       </div>    
            
            
            
	<div id="actual_time_task" class="modal model-size actual-time fade customecontainer" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close close_actual_time_task" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Actual time of task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
                            <div class="portlet-body  form flip-scroll" style="padding: 10px;">
					<form name="frm_actual_time" id="frm_actual_time" method="post">
						<div class="form-group">
							<label class="control-label">Enter Actual Time : </label>
							<div class="controls">
								<input class="onsub m-wrap m-ctrl-small small_input" name="task_actual_time" id="task_actual_time" placeholder="0h" value="" type="text"  tabindex="1" /><span class="word_set">time (ex. 130 for 1h30)</span>
								<input type="hidden" name="task_actual_time_hour" id="task_actual_time_hour" value="" />
								<input type="hidden" name="task_actual_time_min" id="task_actual_time_min" value="" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<input type="hidden" name="task_id" id="task_actual_time_task_id" value="" />
								<button type="submit" class="btn blue txtbold"> Save </button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="actual_time_task_drag" class="modal model-size actual-time fade customecontainer" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Actual time of task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
                            <div class="portlet-body  form flip-scroll" style="padding:10px;">
					<form name="frm_actual_time_drag" id="frm_actual_time_drag" method="post">
						<div class="form-group">
							<label class="control-label">Enter Actual Time : </label>
							<div class="controls">
								<input class="onsub m-wrap m-ctrl-small small_input" name="task_actual_time" id="task_actual_time_drag" placeholder="0h" value="" type="text"  tabindex="1" /><span class="word_set">time (ex. 130 for 1h30)</span>
								<input type="hidden" name="task_actual_time_hour" id="task_actual_time_hour_drag" value="" />
								<input type="hidden" name="task_actual_time_min" id="task_actual_time_min_drag" value="" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<input type="hidden" name="task_id" id="task_actual_time_task_id_drag" value="" />
								<input type="hidden" name="actual_time_task_came_from_orders" id="actual_time_task_came_from_orders" value="" />
								<input type="hidden" name="actual_time_task_dropped_orders" id="actual_time_task_dropped_orders" value="" />
								<input type="hidden" name="actual_time_task_came_from_id" id="actual_time_task_came_from_id" value="" />
								<input type="hidden" name="actual_time_task_dropped_id" id="actual_time_task_dropped_id" value="" />
								<button type="submit" class="btn blue txtbold"> Save </button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
    function if_only_one_swimlane()
    {
        var count_tr=0;    
    $(".cstm_kanban").each(function(){ count_tr++; });   
    if(count_tr==1){
         $(".slimScrollDiv , .ui-sortable").css("min-height" , "calc("+100+"vh - 261px)");
         console.log("calc("+100+"vh - 261px)");
    }
    }
    </script>
