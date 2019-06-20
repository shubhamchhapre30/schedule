<?php 
	$theme_url = base_url().getThemeName();
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	$default_format = $site_setting_date; 
	
	if(getTilesOrderTeamDashboard()){
		$getTilesOrderTeamDashboard = explode(',', getTilesOrderTeamDashboard());
	}else{
		$getTilesOrderTeamDashboard = '';
	}
        $bucket = $this->config->item('bucket_name');
        $s3_display_url = $this->config->item('s3_display_url');
//        pr($pending_task);die;
?>
  

<script type="text/javascript">
	var READY_STATUS = READY_ID;
</script>

<script type="text/javascript" src="<?php echo $theme_url;?>/assets/js/teamdashboard.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>

<script type="text/javascript">
	$(document).ready(function(){
		
		<?php if($getTilesOrderTeamDashboard !=''){ ?>
				
		$("#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard[1];?>").insertAfter('#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard['0'];?>');
		$("#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard[2];?>").insertAfter('#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard['1'];?>');
		$("#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard[3];?>").insertAfter('#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard['2'];?>');
		$("#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard[4];?>").insertAfter('#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard['3'];?>');
		$("#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard[5];?>").insertAfter('#rightList_teamDashboard #sortableItem_<?php echo $getTilesOrderTeamDashboard['4'];?>');
				
		<?php } ?>
		
		$("#rightList_teamDashboard > div:nth-child(1)").addClass('margin-class');
		$("#rightList_teamDashboard > div:nth-child(3)").addClass('margin-class');
		$("#rightList_teamDashboard > div:nth-child(5)").addClass('margin-class');
       $("#pending_task_search").keyup(function () {
        var searchTerm = $("#pending_task_search").val();
        var listItem = $('#filtertab2 tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

      $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
      });

      $("#filtertab2 tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','false');
      });

      $("#filtertab2 tbody tr:containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','true');
      });

      var jobCount = $('#filtertab2 tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' item');

      if(jobCount == '0') {$('.no-result').show();}
        else {$('.no-result').hide();}
     });
     $("#overdue_task_search").keyup(function () {
        var searchTerm = $("#overdue_task_search").val();
        var listItem = $('#filtertab3 tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

      $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
      });

      $("#filtertab3 tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','false');
      });

      $("#filtertab3 tbody tr:containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','true');
      });

      var jobCount = $('#filtertab3 tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' item');

      if(jobCount == '0') {$('.no-result').show();}
        else {$('.no-result').hide();}
     });
     $("#todo_task_search").keyup(function () {
        var searchTerm = $("#todo_task_search").val();
        var listItem = $('#filtertab1 tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

      $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
      });

      $("#filtertab1 tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','false');
      });

      $("#filtertab1 tbody tr:containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','true');
      });

      var jobCount = $('#filtertab1 tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' item');

      if(jobCount == '0') {$('.no-result').show();}
        else {$('.no-result').hide();}
     });
});


</script>
<style>
 #filtertab1 tr[visible='false'], #filtertab2 tr[visible='false'], #filtertab3 tr[visible='false'],
.no-result{
  display:none;
}

#filtertab1 tr[visible='true'],#filtertab2 tr[visible='true'],#filtertab3 tr[visible='true']{
  display:table-row;
}
</style>
<?php 
date_default_timezone_set($this->session->userdata("User_timezone"));
?>
<div id="rightList_Container_teamDashboard" class="section-frame container-fluid">
<div id="rightList_teamDashboard" class="connectedList ui-sortable mainpage-container">
        <!-- BEGIN PAGE CONTENT-->
        <!--<div class="row-fluid">-->
          <div id="sortableItem_0" class="sortableList-item col-md-6 fixheight">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_0" class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">Task to do list </div>
               
              </div>
              <div class="portlet-body minimumhight portlet-minhgt flip-scroll">
              	<div class="table-toolbar">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-md-3">
								<a class="btn btn-common-blue" href="javascript:void(0)" onclick="add_task('<?php echo strtotime(date("Y-m-d"));?>','<?php echo date($default_format);?>');"> Add Task <i class="stripicon addicon"></i> </a>
							</div>
							<div class="col-md-9">
								<div class="text-right">
									<div class="controls2">
									 	<select name="team_task_priority" id="teamdashboard_filter_task_priority" class="small m-wrap radius-b" tabindex="1" onchange="teamDashboardFilterSet();" >
											<option value="">Select Priority</option>
											<option value="None" <?php if(isset($_COOKIE['teamdashboard_priority']) && $_COOKIE['teamdashboard_priority'] == "None") echo 'selected';?>>None</option>
											<option value="Low" <?php if(isset($_COOKIE['teamdashboard_priority']) && $_COOKIE['teamdashboard_priority'] == "Low") echo 'selected';?>>Low</option>
											<option value="Medium" <?php if(isset($_COOKIE['teamdashboard_priority']) && $_COOKIE['teamdashboard_priority'] == "Medium") echo 'selected';?>>Medium</option>
											<option value="High" <?php if(isset($_COOKIE['teamdashboard_priority']) && $_COOKIE['teamdashboard_priority'] == "High") echo 'selected';?>>High</option>
										</select>
										<select name="team_duration" id="teamdashboard_filter_duration" class="small m-wrap radius-b" tabindex="1" onchange="teamDashboardFilterSet();" >
											<option value="today" <?php if(isset($_COOKIE['teamdashboard_duration']) && $_COOKIE['teamdashboard_duration'] == 'today') echo 'selected';?>>Today</option>
											<option value="this_week" <?php if(isset($_COOKIE['teamdashboard_duration']) && $_COOKIE['teamdashboard_duration'] == 'this_week') echo 'selected';?>>This Week</option>
											<option value="next_week" <?php if(isset($_COOKIE['teamdashboard_duration']) && $_COOKIE['teamdashboard_duration'] == 'next_week') echo 'selected';?>>Next Week</option>
											<option value="this_month" <?php if(isset($_COOKIE['teamdashboard_duration']) && $_COOKIE['teamdashboard_duration'] == 'this_month') echo 'selected';?>>This Month</option>
											<option value="overdue" <?php if(isset($_COOKIE['teamdashboard_duration']) && $_COOKIE['teamdashboard_duration'] == 'overdue') echo 'selected';?>>Overdue</option>
											<option value="backlog" <?php if(isset($_COOKIE['teamdashboard_duration']) && $_COOKIE['teamdashboard_duration'] == 'backlog') echo 'selected';?>>Back Log</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					 </div>
				 </div>

                <div class="customtable table-scrollable scrollbaar" id="filtertab1_in">
                  
                    	<?php  $this->load->view($theme."/layout/user/team_todo_Ajax") ?>
               

            </div>
          </div>
        </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
          <div id="sortableItem_1" class="sortableList-item col-md-6 fixheight">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_1" class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">Task Pending</div>
                <div class="col-md-3" style="margin-bottom:0px;width:49% !important;float:right;margin-top: -3px;">     
                        <input class="onsub m-wrap large cus_input " name="task_search" id="pending_task_search" placeholder="Search" value="" type="text"  tabindex="1" style="margin-top: 3px;padding: 0px 6px 4px 6px !important;"/>
                    </div>
              </div>
                 
              <div class="portlet-body minimumhight portlet-minhgt">
                 
                <div class="customtable table-scrollable scrollbaar1">
                  <table id="filtertab2" class="table tabrd table-striped table-hover">
                    <thead>
                    	
                      <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th >Task Owner <!--<a href="javascript:;"> <i class="stripicon icondwonarro"></i> </a>--></th>
                        <th class="hidden-480">Pending With <!--<a href="javascript:;"> <i class=" stripicon iconfilter"></i> </a>--></th>
                      </tr>
                    </thead>
                    <tbody id="teampending_list">
                     
		                    	<?php if($pending_task!='0'){
		                    			foreach ($pending_task as $t) {
		                    			$t = (object)$t;
										$user_name = ucwords($t->allocated_user_first_name)." ".ucwords($t->allocated_user_last_name[0]).".";
										
										if($t->task_due_date!= '0000-00-00' ){
											$due_dt = date($site_setting_date,strtotime($t->task_due_date));
											$hidden_due_date = date("Y-m-d",strtotime($t->task_due_date));
										} else {
											$due_dt = "N/A";
											$hidden_due_date = "N/A";
										}
										 if($t->master_task_id){
											$is_master_deleted = chk_master_task_id_deleted($t->master_task_id);
										} else {
											$is_master_deleted = 0;
										}
										
										if (strpos($t->task_id,'child') !== false) {
										    $chk = "0";
										} else {
											$chk = "1";
										}
		                    			?>
		                      <tr id="teampending_<?php echo $t->task_id;?>">
		                        <td title="<?php echo $t->task_description;?>">
		                        	<?php if($t->master_task_id == '0' || $is_master_deleted=="1"){ ?>
										<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $t->task_id;?>','<?php echo $chk;?>')" class="tooltips teamdashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" ><?php echo ucwords($t->task_title); echo $t->customer_name!=''? ' ('.$t->customer_name.')':'';?></a>
									<?php } else { ?>
										
                                                                                <a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $t->task_id;?>','<?php echo $t->master_task_id;?>','<?php echo $chk;?>');" class="tooltips teamdashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" ><?php echo ucwords($t->task_title); echo $t->customer_name!=''? ' ('.$t->customer_name.')':'';?></a>
									
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
		                        <td><span class="hidden"><?php echo $hidden_due_date;?></span><?php echo $due_dt;?></td>
                                        <td >
                                           <?php
                                           $word1 = ucfirst(substr($t->first_owner_name,0,1));
                                         $word2 = ucfirst(substr($t->last_owner_name,0,1));
                                           if(($t->owner_profile_image != '' || $t->owner_profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$t->owner_profile_image)) { ?>
                                                    <img alt="" data-original-title="<?php echo ucwords($t->first_owner_name)." ".ucwords($t->last_owner_name);?>"  class="tooltips capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$t->owner_profile_image; ?>" class="profile-image" />
                                            <?php } else { ?>
                                                    <span class="tooltips" data-original-title="<?php echo ucwords($t->first_owner_name)." ".ucwords($t->last_owner_name);?>"  data-letters="<?php echo $word1.$word2; ?>"></span>
                                            <?php } ?>
                                        </td>
		                        <td class="hidden-480">
                                            <?php
                                           $word3 = ucfirst(substr($t->allocated_user_first_name,0,1));
                                         $word4 = ucfirst(substr($t->allocated_user_last_name,0,1));
                                           if(($t->allocated_user_profile_image != '' || $t->allocated_user_profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$t->allocated_user_profile_image)) { ?>
                                                    <img alt="" data-original-title="<?php echo ucwords($t->allocated_user_first_name)." ".ucwords($t->allocated_user_last_name);?>" class="tooltips capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$t->allocated_user_profile_image; ?>" class="profile-image" />
                                            <?php } else { ?>
                                                    <span class="tooltips" data-original-title="<?php echo ucwords($t->allocated_user_first_name)." ".ucwords($t->allocated_user_last_name);?>" data-letters="<?php echo $word3.$word4; ?>"></span>
                                            <?php } ?>
                                            </td>
		                        <?php $t_arr = (array)$t;?>
                        		<input type="hidden" id="task_data_<?php echo $t->task_id;?>" value="<?php echo htmlspecialchars(json_encode($t_arr)); ?>" />
		                      </tr>
		                      <?php }
		                    	} ?>
                    	 
                     
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
        <!--</div>-->
        <!-- END PAGE CONTENT-->
        <!-- BEGIN PAGE CONTENT-->
        <!--<div class="row-fluid">-->
          <div id="sortableItem_2" class="sortableList-item col-md-6 fixheight">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_2" class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">Overdue Tasks </div>
                
              </div>
              <div class="portlet-body minimumhight portlet-minhgt">
                <div class="customtable table-scrollable scrollbaar2" >
                  <table id="filtertab3" class="table tabrd table-striped table-hover ">
                    <thead>
                      <tr>
                        <th>Task</th>
                        <th>Due Date</th>
                        <th>Allocated </th>
                        <th class="hidden-480">Delay <!--<a href="javascript:;"> <i class="stripicon iconfilter"></i> </a>--></th>
                        <th>Priority</th>
                      </tr>
                    </thead>
                    <tbody id="teamoverdue_list">
                    	<?php if($overdue_task!='0'){
                    		
							foreach ($overdue_task as $t) {
								$t = (object)$t;
							if($t->task_due_date!= '0000-00-00' ){
								$due_dt = date($site_setting_date,strtotime($t->task_due_date));
								$hidden_due_date = date("Y-m-d",strtotime($t->task_due_date));
							} else {
								$due_dt = "N/A";
								$hidden_due_date = "N/A";
							}
							if($t->master_task_id){
								$is_master_deleted = chk_master_task_id_deleted($t->master_task_id);
							} else {
								$is_master_deleted = 0;
							}
							$today = date('Y-m-d'); 
							$delay = round(floor(strtotime($today) - strtotime($t->task_due_date))/(60*60*24));
								?>
	                      <tr id="teamoverdue_<?php echo $t->task_id;?>">
	                        <td title="<?php echo $t->task_description;?>">
	                        	<?php if($t->master_task_id == '0' || $is_master_deleted=="1"){ ?>
									<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $t->task_id;?>','<?php echo chk_task_exists($t->task_id);?>')" class="tooltips teamdashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" ><?php echo (strlen($t->task_title) > 25)?substr(ucwords($t->task_title),0, 22).'...':ucwords($t->task_title);?></a>
								<?php } else { ?>
									
									<a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $t->task_id;?>','<?php echo $t->master_task_id;?>','<?php echo chk_task_exists($t->task_id);?>');" class="tooltips teamdashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right" ><?php echo (strlen($t->task_title) > 25)?substr(ucwords($t->task_title),0, 22).'...':ucwords($t->task_title);?></a>
								
								<?php } ?>
	                        </td>
	                         <td><span class="hidden"><?php echo $hidden_due_date;?></span><?php echo $due_dt;?></td>
                                 <td >
                                     <?php
                                           $word3 = ucfirst(substr($t->allocated_user_first_name,0,1));
                                         $word4 = ucfirst(substr($t->allocated_user_last_name,0,1));
                                           if(($t->allocated_user_profile_image != '' || $t->allocated_user_profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$t->allocated_user_profile_image)) { ?>
                                                    <img alt="" data-original-title="<?php echo ucwords($t->allocated_user_first_name)." ".ucwords($t->allocated_user_last_name);?>" class="tooltips capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$t->allocated_user_profile_image; ?>" class="profile-image" />
                                            <?php } else { ?>
                                                    <span class="tooltips" data-original-title="<?php echo ucwords($t->allocated_user_first_name)." ".ucwords($t->allocated_user_last_name);?>" data-letters="<?php echo $word3.$word4; ?>"></span>
                                            <?php } ?>
                                 </td>
	                        <td class="hidden-480"><?php echo $delay;?></td>
	                        <td><?php echo $t->task_priority;?></td>
	                        <?php $t_arr = (array)$t;?>
                        	<input type="hidden" id="task_data_<?php echo $t->task_id;?>" value="<?php echo htmlspecialchars(json_encode($t_arr)); ?>" />
	                      </tr>
                      
                      <?php 	}
                    	} ?>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
          <div id="sortableItem_3" class=" sortableList-item col-md-6 fixheight">
<!--</div>-->
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_3" class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">My Team's Time This Week</div>
              </div>
              <div class="portlet-body minimumhight portlet-minhgt">
                <div >
                	
                	<?php if($task_thisweekteam!='0'){
                			$format = '%02d:%02d';
						 	foreach ($task_thisweekteam as $t) { ?>
						
                  	<?php
                  			
                                                        if($t["day"] =='Monday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($MON_hours);
								$ration = ($MON_hours!='0')?(round($t["task_time"]/($MON_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
							}
							if($t["day"] =='Tuesday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($TUE_hours);
								$ration = ($TUE_hours!='0')?(round($t["task_time"]/($TUE_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Wednesday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($WED_hours);
								$ration = ($WED_hours!='0')?(round($t["task_time"]/($WED_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Thursday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($THU_hours);
								$ration = ($THU_hours!='0')?(round($t["task_time"]/($THU_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Friday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($FRI_hours);
								$ration = ($FRI_hours!='0')?(round($t["task_time"]/($FRI_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Saturday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($SAT_hours);
								$ration = ($SAT_hours!='0')?(round($t["task_time"]/($SAT_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
							}
							if($t["day"] =='Sunday'){
								if($t["task_time"]!='0'){
									$estimate_hours = intval($t["task_time"]/60);
									$estimate_minutes = ($t["task_time"] % 60);
								}else{
									$estimate_hours = 0;
									$estimate_minutes = 0;
								}
								$prog = minutesToHourMinutes($SUN_hours);
								$ration = ($SUN_hours!='0')?(round($t["task_time"]/($SUN_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
							}
						   ?>
						  
						
					<div class="progress-loop margin-top-10 clearfix">	  	
                    <div style="float:left;width:20%"> <span> <?php echo $t["day"];?> </span></div>
                    <div style="width:60%;float:left;">
                        <div class="progress " >
                          <div style="width: <?php echo $ration;?>%; background-color:<?php echo $prog_clr;?>" class="progress-bar" role="progressbar"></div>
                      </div>
                    </div>
                    
                    
                    <div class=" text-right"><span>
                    	<?php echo $time_with_format;?> / <?php echo $prog;?> 
                    	</span></div>
                   </div>
                  <?php	} }else{ ?>
                		<div class="progress-loop margin-top-20 clearfix txt-clr"> No task Available</div>
                		
                	<?php 	} ?>
                
              </div>
              <div>
                  <div class="text-right "> <a onclick="getNextweek();" href="javascript:;" class="btn btn-common-blue"> Next Week <i class="stripicon iconrightarro "></i> </a> </div>
                </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
        </div>
        <!-- END PAGE CONTENT-->
        <!-- BEGIN PAGE CONTENT-->
        <!--<div class="row-fluid">-->
          <div id="sortableItem_4" class="sortableList-item col-md-6 fixheight">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_4" class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">Time Allocation For Today </div>
              </div>
              <div class="portlet-body minimumhight">
              	<div class="text-center chartdiv ajax_team_time_data" id="teampiechart" ></div>
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
          <div id="sortableItem_5" class="sortableList-item col-md-6 fixheight">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_5" class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">Time Allocation by Category for Today</div>
              </div>
              <div class="portlet-body minimumhight">
                <!--<div class="customtable">-->
                	<div class="text-center chartdiv ajax_team_category_data" id="piechartcat" >
                	</div>
                <!--</div>-->
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
        <!--</div>-->
        <!-- END PAGE CONTENT-->
      
      </div>
   </div>  
      
      <?php 
      date_default_timezone_set("UTC");
     
	  
      ?>
      <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
      
      
<script type="text/javascript">
	
	google.setOnLoadCallback(drawChart);
      function drawChart() {
      	
    
        var data = new google.visualization.DataTable();
		    data.addColumn('string', 'Task');
		    data.addColumn('number', 'Hours per Day');
		    data.addColumn({type: 'string', role: 'tooltip'});
		    data.addRows([
		        ['Allocated', <?php echo $allocated;?>, '<?php echo ($allocated!='0')?minutesToTime($allocated):'';?>'],
		        ['Not Allocated', <?php echo $nonallocated;?>, '<?php echo ($nonallocated!='0')?minutesToTime($nonallocated):'';?>']
		    ]);
		
		 
		var options = {
          //title: 'Time Allocation for Today',
          legend: { position: 'bottom' },
          width:'100%',
          height:'100%'
        };
		
        var chart = new google.visualization.PieChart(document.getElementById('teampiechart'));

        chart.draw(data, options);
               
      }
     // window.onresize = function(){ chart.draw(data, options);};

	
	google.setOnLoadCallback(drawChartcat);
      function drawChartcat() {

        
        var datacat = new google.visualization.DataTable();
		    datacat.addColumn('string', 'category');
		    datacat.addColumn('number', 'Hours per Day');
		    datacat.addColumn({type: 'string', role: 'tooltip'});
		    datacat.addRows([
		    	<?php if($taskByCat!='0'){
          	foreach ($taskByCat as $t) {
          		//pr($t);
				 ?>
		        ['<?php echo ($t['task_category_id']!='0')?get_category_name($t['task_category_id']):'No Category';?>', <?php echo $t['task_time_estimate'];?>, '<?php echo minutesToTime($t['task_time_estimate']);?>'],
		         <?php } }?>
		        
		    ]);
		
		
        	
        	
		var options = {
          //title: 'Time Allocation by Category',
          legend: { position: 'bottom' },
          width:'100%',
          height:'100%'
        };

        var chartcat = new google.visualization.PieChart(document.getElementById('piechartcat'));

        chartcat.draw(datacat, options);

      }
      // window.onresize = function(){ chartcat.draw(datacat, options);};  
</script>
