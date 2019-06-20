<?php 
date_default_timezone_set($this->session->userdata("User_timezone"));


$show_cal_view = $last_rember_values->show_cal_view;
if(isset($show_cal_view) && $show_cal_view!=''){
	$show_cal_arr = explode(",", $show_cal_view);
	if(in_array("1",$show_cal_arr)){
		$task_list = "display:block;";
	} else {
		$task_list = "display:none;";
	}
	
	if(in_array("2",$show_cal_arr)){
		$task_info = "display:block;";
	} else {
		$task_info = "display:none;";
	}
	
	if(in_array("3",$show_cal_arr)){
		$task_lable = "display:block;";
	} else {
		$task_lable = "display:none;";
	}
}?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".full_task div").addClass("before_timer");
		$('.tooltips').tooltip();
		<?php 
		$firstday = date("w",strtotime($year."-".$month."-01"));
				$lastday = date("w",strtotime(date("Y-m-t",strtotime($year."-".$month."-01"))));
				
			    if ($firstday == 0) $firstday = 7;
				$first_empty_days = $firstday-(get_default_day_no_of_company()-1);
				if($first_empty_days<0){
					$first_empty_days = 7 + $first_empty_days;
				}
				
				$last_empty_day = (get_default_day_no_of_company() - $lastday) - 2;
				if($last_empty_day<0){
					$last_empty_day = 7 + $last_empty_day;
				}
				
				$month_start_date = date("Y-m-d",strtotime("-".$first_empty_days." days",strtotime($year."-".$month."-01")));
				$month_end_date = date("Y-m-d",strtotime("+".$last_empty_day." days",strtotime(date("Y-m-t",strtotime($year."-".$month."-01")))));				
                    ?>
	});
</script>
	
	
	<?php $chk = chk_task_exists($date['task_id']); 
        $is_master_deleted = chk_master_task_id_deleted($date['master_task_id']);
        ?>
<?php 
$full_title = $date['task_title'];
$title = $date['task_title'];
if($date['task_project_id']){
	$project_name = get_project_name($date['task_project_id']);
	$title = $project_name.' - '.$full_title;
	$full_title = $project_name.' - '.$full_title;
}
$completed_depencencies = chk_dependency_status($date['task_id'],$this->config->item('completed_id'));
$task_type = "0";
if($date['task_status_id']==$this->config->item('completed_id')){
	$task_type = "1";
	if($task_type == "1"){
		$task_type = "1,3";
	} else {
		$task_type = "3";
	}
	if($date['task_due_date'] == $date['task_scheduled_date']){
		if($task_type == "1,3"){
			$task_type = "1,3,4";
		} else {
			$task_type = "3,4";
		}
	}
} else if($date['task_due_date'] < date('Y-m-d')){
	$task_type = "2";
	if($task_type == "2"){
		$task_type = "2,3";
	} else {
		$task_type = "3";
	}
} else {
	$task_type = "3";
	if($date['task_due_date'] == $date['task_scheduled_date']){
		if($task_type == "3"){
			$task_type = "3,4";
		} else {
			$task_type = "4";
		}
	}
}


$task_type_class = '';
if($task_type){
	$task_type_val = explode(",", $task_type);
	$task_type_class = '';
	for($x=0;$x<count($task_type_val);$x++){
		$task_type_class .= "task_type_".$task_type_val[$x]." ";
	}
}
$cl = '';
if($date['task_time_estimate'] == '0'){
	$cl = 'display:none;';
}

$cl3 = "";
if($date['locked_due_date'] == "0"){
	$cl3 = 'display:none;';
}
if($cl == "" && $cl3 == ""){
	if(strlen($title) > 18) {
	    $title = substr($title, 0, 16).'..'; 
	}
} else if($cl!="" && $cl3 == ""){
	if(strlen($title) > 24) {
	    $title = substr($title, 0, 22).'..'; 
	}
} else if($cl=="" && $cl3!=""){
	if(strlen($title) > 18) {
	    $title = substr($title, 0, 16).'..'; 
	}
} else {
	if(strlen($title) > 26) {
	    $title = substr($title, 0, 24).'..'; 
	}
}
if($color_menu == 'false'){
    $date['color_id']='#fff';
}
$color_codes = get_user_color_codes(get_authenticateUserID());
$company_id = get_company_id();
$task_status = get_taskStatus($company_id,'Active'); 
 if($this->session->userdata('Temp_calendar_user_id')=='0'){
    $user_swimlanes = get_user_swimlanes(get_authenticateUserID());
}else{
   $user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id')); 
}
$report_user_list_id='';     
                                                                                                                   if(isset($all_report_user) && !empty($all_report_user)){
                                                                                                                            foreach($all_report_user as $val ){
                                                                                                                                if($val['user_id']==$date['task_owner_id']){
                                                                                                                                   $report_user_list_id='1';  
                                                                                                                                }
                                                                                                                            }
                                                                                                                          }else{
                                                                                                                             $report_user_list_id='0';
                                                                                                                          }

 $jsonarray=array(
            "task_status" =>$task_status,
            "user_colors" =>$color_codes,
            "user_swimlanes" =>$user_swimlanes,
            "task_id" =>$date['task_id'],
            "locked_due_date" => $date['locked_due_date'],
            "task_due_date" =>date("m-d-Y",strtotime($date['task_due_date'])),
            "task_scheduled_date" =>date("m-d-Y",strtotime($date['task_scheduled_date'])),
            "date" =>strtotime($date['task_scheduled_date']), 
            "active_menu" =>'from_calendar',
            "start_date" =>strtotime($month_start_date),
            "end_date" =>strtotime($month_end_date),
            "master_task_id" =>$date['master_task_id'],
            "is_master_deleted" =>$is_master_deleted,
            "chk_watch_list" =>check_my_watch_list($date['task_id'],get_authenticateUserID()),
            "task_owner_id" =>$date['task_owner_id'],
            "completed_depencencies" =>$completed_depencencies,
            "color_menu" =>$color_menu,
            "swimlane_id" =>$date['swimlane_id'],
            "task_status_id" => $date['task_status_id'],
            "before_status_id" => '',
             "report_user_list_id" => $report_user_list_id 
        );
 
?>
<div id="task_<?php echo $date['task_id'];?>" style="background-color:<?php echo get_task_color_code($date['color_id']);?>; border:1px solid <?php echo get_outside_color_code($date['color_id']);;?>;" class="taskbox calicon<?php echo $date['task_priority'];?> <?php echo $task_type_class;?> month_master_<?php echo $date['master_task_id'];?> <?php if($date['is_personal'] == '1' && $date['task_owner_id'] != get_authenticateUserID()){ echo 'unsorttd'; } ?>" onclick="save_task_for_timer(this,'<?php echo $date['task_id'];?>','<?php echo addslashes($date['task_title']);?>','<?php echo $date['task_time_spent'];?>','1','<?php echo $completed_depencencies;?>');">
	<div oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');">
	<?php if($date['master_task_id']=='0'){ ?>
		<a class="tooltips " data-toggle="tooltip" data-original-title="<?php echo $full_title;?>"  href="javascript:void(0)" onclick="edit_task(this,'<?php echo $date['task_id'];?>','<?php echo $chk;?>');">
	<?php } else { ?>
		<a class="tooltips " data-toggle="tooltip" data-original-title="<?php echo $full_title;?>" onclick="open_seris(this,'<?php echo $date['task_id'];?>','<?php echo $date['master_task_id'];?>','<?php echo $chk;?>');" href="javascript:void(0)">
	<?php } ?>
		<span class="task-desc"><?php echo $title;?></span>
		<p class="task-hrs"> 
		<i style="<?php echo $cl3; ?>" class="stripicon lockicon"></i>
		<span id="task_est_<?php echo $date['task_id'];?>" class="task-hrs" style="<?php echo $cl;?>"> <?php echo minutesToTime($date['task_time_estimate']);?> </span>
		</p>
                <input type="hidden" id="monthly_color_menu" value="<?php echo $color_menu;?>" />
		<input type="hidden" value="<?php echo htmlspecialchars(json_encode($date));?>" id="task_data_<?php echo $date['task_id'];?>" />
		<input type="hidden" value="<?php echo strtotime(str_replace(array("/"," ",","), "-", $date['task_due_date'])); ?>" id="hdn_due_date_<?php echo $date['task_id'];?>" />
		<input type="hidden" value="<?php echo $date['locked_due_date']; ?>" id="hdn_locked_due_date_<?php echo $date['task_id'];?>" />
		<input type="hidden" id="or_color_<?php echo $date['task_id']; ?>" name="or_color_id" value="<?php echo get_outside_color_code($date['color_id']);?>" />
		<input type="hidden" id="task_type_<?php echo $date['task_id'];?>" name="task_type" value="<?php echo $task_type;?>" />

		<input type="hidden" id="task_spent_<?php echo $date['task_id'];?>" name="task_spent_time" value="<?php echo $date['task_time_spent'];?>" />
		<input type="hidden" id="task_status_<?php echo $date['task_id'];?>" name="task_status_name" value="<?php echo get_task_status_name_by_id($date['task_status_id']);?>" />

	<div class="clearfix"> </div></a>
	</div>
</div>

<?php date_default_timezone_set("UTC");?>
