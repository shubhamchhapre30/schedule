<?php 
	$theme = getThemeName();
	$theme_url = base_url().getThemeName();
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	$default_format = $site_setting_date;
	if(getTilesOrder()){
		$tilesOrder = explode(',', getTilesOrder());
	}else{
		$tilesOrder = '';
	}
        $bucket = $this->config->item('bucket_name');
        $s3_display_url = $this->config->item('s3_display_url');
?>

<script type="text/javascript" src="<?php echo $theme_url;?>/assets/js/dashboard<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>


<script type="text/javascript">
	$(document).ready(function(){
		$("#filtertab5").dataTable({order:[[1,"asc"]],paging:!1,bFilter:!1,bLengthChange:!1,info:!1,language:{emptyTable:"No Records found."}}),
		<?php if($tilesOrder !=''){ ?>
				
		$("#rightList #sortableItem_<?php echo $tilesOrder[1];?>").insertAfter('#rightList #sortableItem_<?php echo $tilesOrder['0'];?>');
		$("#rightList #sortableItem_<?php echo $tilesOrder[2];?>").insertAfter('#rightList #sortableItem_<?php echo $tilesOrder['1'];?>');
		$("#rightList #sortableItem_<?php echo $tilesOrder[3];?>").insertAfter('#rightList #sortableItem_<?php echo $tilesOrder['2'];?>');
		$("#rightList #sortableItem_<?php echo $tilesOrder[4];?>").insertAfter('#rightList #sortableItem_<?php echo $tilesOrder['3'];?>');
				
		<?php } ?>

		
		$("#rightList > div:nth-child(2)").addClass('margin-class');
		$("#rightList > div:nth-child(4)").addClass('margin-class');
		$("#rightList > div:nth-child(6)").addClass('margin-class');

     
    $("#pending_task_search").keyup(function () {
        var searchTerm = $("#pending_task_search").val();
        var listItem = $('#filtertab5 tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

      $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
      });

      $("#filtertab5 tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','false');
      });

      $("#filtertab5 tbody tr:containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','true');
      });

      var jobCount = $('#filtertab5 tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' item');

      if(jobCount == '0') {$('.no-result').show();}
        else {$('.no-result').hide();}
     });
});



</script>
<style>
 #filtertab5 tr[visible='false'],
.no-result{
  display:none;
}

#filtertab5 tr[visible='true']{
  display:table-row;
}

</style>
<?php date_default_timezone_set($this->session->userdata("User_timezone")); ?>
<!-- BEGIN PAGE CONTAINER-->
    <div id="rightList_Container" class="section-frame container-fluid">
      <div id="rightList" class="connectedList ui-sortable mainpage-container">
        <!-- BEGIN PAGE CONTENT-->
        
			<div class=" unsorttd">
  
                <div class=" col-md-3">
                    <div class="dashboard-stat red tilehover" onclick="load_overdue_tasks();">
                        <div class="visual">
                            <i class="icon-list-alt"></i>
                        </div>
                        <?php $overdue_tasks = countoverduetasks($com_off_days,$task_status_completed_id);?>
                        <div class="details dashboard-change">
                            <div class="number">
                                <span data-counter="counterup" data-value="<?php echo $overdue_tasks;?>"><?php echo $overdue_tasks;?></span>
                            </div>
                            <div class="desc"> Overdue Tasks </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-stat blue">
                        <div class="visual">
                           <i class="icon-time"></i>
                        </div>
                        <?php $planned_hours = countplannedhours($task_status_completed_id,$com_off_days);?>
                        <div class="details dashboard-change">
                            <div class="number">
                                <span data-counter="counterup" data-value="<?php echo $planned_hours;?>"><?php echo $planned_hours;?></span></div>
                            <div class="desc"> Planned Hours Next 5 days</div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-stat green tilehover" onclick="load_backlog_tasks();">
                        <div class="visual">
                            <i class="icon-tasks"></i>
                        </div>
                        <?php $backlog_tasks = countbacklogtasks();?>
                        <div class="details dashboard-change">
                            <div class="number">
                                <span data-counter="counterup" data-value="<?php echo $backlog_tasks;?>"><?php echo $backlog_tasks;?></span>
                            </div>
                            <div class="desc">Tasks in backlog</div>
                        </div>
                       
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-stat purple">
                        <div class="visual">
                            <i class="icon-calendar"></i>
                        </div>
                        <?php $remaing_tasks = countremaingtask($task_status_completed_id,$com_off_days);
						$remaing_tasks_count = 0;
						$remaing_tasks_time = 0;
                        if($remaing_tasks){
                        	$remaing_tasks_count = $remaing_tasks['tasks'];
							$remaing_tasks_time = $remaing_tasks['time'];
                         }
                        
                        ?>
                        
                        <div class="details">
                            <div class="number">
                                <span data-counter="counterup" data-value="<?php echo $remaing_tasks_count;?>"><?php echo $remaing_tasks_count;?></span></div>
                            <div class="desc"> Remaining Tasks for this week <!--this week--> </div>
                            <div class="desc"> Estimated hours : <?php echo $remaing_tasks_time;?> <!--this week--> </div>
                        </div>
                        
                    </div>
                </div>
		  </div>
        
        
      <!--  <div class="row">  -->
         <div id="sortableItem_0" class="sortableList-item col-md-6 ">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_0" class="portlet box blue blue-txt">
              <div class="portlet-title">
                <div class="caption"> Task To Do List </div>
                
              </div>
              <div class="portlet-body portlet-minhgt minimumhight flip-scroll">
			  	<div class="table-toolbar">
					<div class="form-horizontal">
						<div class="form-group">
							<div class=" col-md-3">
							<a class="btn btn-common-blue" href="javascript:void(0)" onclick="add_task('<?php echo strtotime(date("Y-m-d"));?>','<?php echo date($default_format);?>');"> Add Task <i class="stripicon addicon"></i> </a>
							</div>
							<div class=" col-md-9">
								<div class="text-right">
									<div class="controls2">
                                                                            <select name="task_priority" id="dashboard_filter_task_priority" class="small m-wrap radius-b" tabindex="1" >
											<option value="">Select Priority</option>
											<option value="None" <?php if(isset($_COOKIE['dashboard_priority']) && $_COOKIE['dashboard_priority'] == "None") echo 'selected';?> >None</option>
											<option value="Low" <?php if(isset($_COOKIE['dashboard_priority']) && $_COOKIE['dashboard_priority'] == "Low") echo 'selected';?> >Low</option>
											<option value="Medium" <?php if(isset($_COOKIE['dashboard_priority']) && $_COOKIE['dashboard_priority'] == "Medium") echo 'selected';?> >Medium</option>
											<option value="High" <?php if(isset($_COOKIE['dashboard_priority']) && $_COOKIE['dashboard_priority'] == "High") echo 'selected';?> >High</option>
										</select>
										<select name="duration" id="dashboard_filter_duration" class="small m-wrap radius-b" tabindex="1" >
											<option value="today" <?php if(isset($_COOKIE['dashboard_duration']) && $_COOKIE['dashboard_duration'] == 'today') echo 'selected';?> >Today</option>
											<option value="this_week" <?php if(isset($_COOKIE['dashboard_duration']) && $_COOKIE['dashboard_duration'] == 'this_week') echo 'selected';?> >This Week</option>
											<option value="next_week" <?php if(isset($_COOKIE['dashboard_duration']) && $_COOKIE['dashboard_duration'] == 'next_week') echo 'selected';?> >Next Week</option>
											<option value="this_month" <?php if(isset($_COOKIE['dashboard_duration']) && $_COOKIE['dashboard_duration'] == 'this_month') echo 'selected';?> >This Month</option>
                                                                                        <option value="next_month" <?php if(isset($_COOKIE['dashboard_duration']) && $_COOKIE['dashboard_duration'] == 'next_month') echo 'selected';?> >Next Month</option>
											<option value="overdue" <?php if(isset($_COOKIE['dashboard_duration']) && $_COOKIE['dashboard_duration'] == 'overdue') echo 'selected';?> >Overdue</option>
											<option value="backlog" <?php if(isset($_COOKIE['dashboard_duration']) && $_COOKIE['dashboard_duration'] == 'backlog') echo 'selected';?> >Back Log</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					 </div>
				 </div>
                <div class="customtable table-scrollable scrollbaar_new" id="filtertab1_in">
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
						$is_master_deleted = $t->tm;
						
						if (strpos($t->task_id,'child') !== false) {
						    $chk = "0";
						} else {
							$chk = "1";
						}
						
						 ?>
                      <tr id="todo_<?php echo $t->task_id;?>">
                        <td title="<?php echo $t->task_description;?>">
                        	<?php if($t->master_task_id == '0' || $is_master_deleted=="1"){ ?>
								<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $t->task_id;?>','<?php echo $chk;?>');" class="tooltips dashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right"><?php echo (strlen($t->task_title) > 40)?substr(ucwords($t->task_title),0, 37).'...':ucwords($t->task_title);?></a>
							<?php } else { ?>
								
								<a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $t->task_id;?>','<?php echo $t->master_task_id;?>','<?php echo $chk;?>');" class="tooltips dashboard_master_<?php echo $t->master_task_id;?>" data-original-title="<?php echo $t->task_title; ?>" data-placement="right"><?php echo (strlen($t->task_title) > 40)?substr(ucwords($t->task_title),0, 37).'...':ucwords($t->task_title);?></a>
							
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
							if($ts->task_status_name=='Completed')
							{
								$tsk_st = "completed";
							}
							if( $ts->task_status_name!='In Progress' && $ts->task_status_name!='Ready' && $ts->task_status_name!='Not Ready' && $ts->task_status_name!='Completed')
							{
								$tsk_st = "common";
							}
                        ?>
                        <td><span class="label label-sm label-<?php echo $tsk_st;?> " ><?php echo $ts->task_status_name;?></span></td>
                        <?php $t_arr = (array)$t;?>
                        <input type="hidden" id="task_data_<?php echo $t->task_id;?>" value="<?php echo htmlspecialchars(json_encode($t_arr)); ?>" />
                        <?php } } ?>
                      </tr>
                      <?php } } ?> 
                    
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
          <div id="sortableItem_1" class="sortableList-item fixheight  col-md-6 ">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div class="portlet box blue blue-txt">
              <div class="portlet-title">
                <div class="caption">My Watch List</div>
                
              </div>
              <div class="portlet-body portlet-minhgt minimumhight">
                <div class="customtable table-scrollable scrollbaar_new1">
                  <table id="filtertab2" class="table tabrd table-striped table-hover ">
                    <thead>
                      <tr>
                        <th width="40%">Task</th>
                        <th>Due Date</th>
                        <th>Allocated</th>
						<th>Status</th>
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="watchlist">
                    	<?php 
                    	
                    	if($watchlist!='0'){
                    		foreach ($watchlist as $w) {
                    				
                    			$un = get_user_info($w->task_allocated_user_id);
								
							if($w->task_due_date!= '0000-00-00' ){
								$due_dt = date($site_setting_date,strtotime($w->task_due_date));
								$hidden_due_date = date("Y-m-d",strtotime($w->task_due_date));
							} else {
								$due_dt = "N/A";
								$hidden_due_date = "N/A";
							}
						
                    		$is_master_deleted = $w->tm;
							
							if (strpos($w->task_id,'child') !== false) {
							    $chk = "0";
							} else {
								$chk = "1";
							}
                    		?>
                    		
                    		
                      <tr id="watch<?php echo $w->task_id; ?>" >
                        <td title="<?php echo $w->task_description;?>">
                        	<?php if($w->master_task_id == '0' || $is_master_deleted=="1"){ ?>
								<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $w->task_id;?>','<?php echo $chk;?>')" class="tooltips dashboard_master_<?php echo $w->master_task_id;?>" data-original-title="<?php echo $w->task_title; ?>" data-placement="right" ><?php echo (strlen($w->task_title) > 40)?substr(ucwords($w->task_title),0, 37).'...':ucwords($w->task_title);?></a>
							<?php } else { ?>
								<a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $w->task_id;?>','<?php echo $w->master_task_id;?>','<?php echo $chk;?>');" class="tooltips dashboard_master_<?php echo $w->master_task_id;?>" data-original-title="<?php echo $w->task_title; ?>" data-placement="right" ><?php echo (strlen($w->task_title) > 40)?substr(ucwords($w->task_title),0, 37).'...':ucwords($w->task_title);?></a>
							<?php } ?>
                        </td>
                        <td><span class="hidden"><?php echo $hidden_due_date;?></span><?php echo $due_dt;?></td>
                        <td >
                            <?php
                                           $word3 = ucfirst(substr($w->first_name,0,1));
                                         $word4 = ucfirst(substr($w->last_name,0,1));
                                           if(($w->allocated_user_profile_image != '' || $w->allocated_user_profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$w->allocated_user_profile_image)) { ?>
                                                    <img alt="" data-original-title="<?php echo ucwords($w->first_name)." ".ucwords($w->last_name);?>" class="tooltips capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$w->allocated_user_profile_image; ?>" class="profile-image" />
                                            <?php } else { ?>
                                                    <span class="tooltips" data-original-title="<?php echo ucwords($w->first_name)." ".ucwords($w->last_name);?>" data-letters="<?php echo $word3.$word4; ?>"></span>
                                            <?php } ?>
                        </td>
						<?php 
                        foreach($task_status as $ts){
                        if($ts->task_status_id == $w->task_status_id){
                        		
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
                        <td><span class="label label-sm label-<?php echo $tsk_st;?> "><?php echo $ts->task_status_name;?></span></td>
                        <?php } } ?>
                        <td id="td_<?php echo $w->id;?>"> <a onclick="delwatch('<?php echo $w->id;?>','<?php echo $w->task_id;?>');" href="javascript:void(0);" class="tooltips " data-original-title="stop following"> <i class="stripicon icondelete2"></i> </a> </td>
                        <?php $w_arr = (array)$w;?>
                        <input type="hidden" id="task_data_<?php echo $w->task_id;?>" value="<?php echo htmlspecialchars(json_encode($w_arr)); ?>" />
                        
                      </tr>
                      <?php } } ?>
                     
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
      <div id="sortableItem_5" class="sortableList-item col-md-6 fixheight">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_5" class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">Task Pending</div>
                <div class="col-md-3" style="margin-bottom:0px;width:49% !important;float:right;margin-top: -3px;">     
                        <input class="onsub m-wrap large cus_input " name="task_search" id="pending_task_search" placeholder="Search" value="" type="text"  tabindex="1" style="margin-top: 3px;padding: 0px 6px 4px 6px !important;"/>
                    </div>
              </div>
                 
              <div class="portlet-body minimumhight portlet-minhgt">
                 
                <div class="customtable table-scrollable scrollbaar_new3">
                  <table id="filtertab5" class="table tabrd table-striped table-hover">
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
        <!--<div class="row ">-->
          <div  id="sortableItem_2" class="sortableList-item  fixheight  col-md-6 " >
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_2" class="portlet box blue blue-txt">
              <div class="portlet-title">
                <div class="caption">New Tasks Since Last Login </div>
                
              </div>
              <div class="portlet-body portlet-minhgt minimumhight">
                <div class="customtable table-scrollable scrollbaar_new2">
                  <table id="filtertab3" class="table tabrd table-striped table-hover ">
                    <thead>
                      <tr>
                        <th class="hidden-480">Task</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                      </tr>
                    </thead>
                    <tbody>
                    	<?php
                    	 if($last_login_task!='0'){
                    		foreach ($last_login_task as $l) {
                    			$l = (object)$l;
								if($l->task_due_date!= '0000-00-00' ){
									$due_dt = date($site_setting_date,strtotime($l->task_due_date));
									$hidden_due_date = date("Y-m-d",strtotime($l->task_due_date));
								} else {
									$due_dt = "N/A";
									$hidden_due_date = "N/A";
								}
								if($l->master_task_id){
									$is_master_deleted = chk_master_task_id_deleted($l->master_task_id);
								} else {
									$is_master_deleted = 0;
								}
								
								if (strpos($l->task_id,'child') !== false) {
								    $chk = "0";
								} else {
									$chk = "1";
								}
							
                    	?>
                      <tr id="last_login_<?php echo $l->task_id;?>">
                        <td>
                        	<?php if($l->master_task_id == '0' || $is_master_deleted=="1"){ ?>
								<a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $l->task_id;?>','<?php echo $chk;?>')" class="tooltips dashboard_master_<?php echo $l->master_task_id;?>" data-original-title="<?php echo $l->task_title; ?>" data-placement="right" ><?php echo (strlen($l->task_title) > 40)?substr(ucwords($l->task_title),0, 37).'...':ucwords($l->task_title);?></a>
							<?php } else { ?>								
								<a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $l->task_id;?>','<?php echo $l->master_task_id;?>','<?php echo $chk;?>');" class="tooltips dashboard_master_<?php echo $l->master_task_id;?>" data-original-title="<?php echo $l->task_title; ?>" data-placement="right" ><?php echo (strlen($l->task_title) > 40)?substr(ucwords($l->task_title),0, 37).'...':ucwords($l->task_title);?></a>							
							<?php } ?>
                        </td>
                        <td><span class="hidden"><?php echo $hidden_due_date;?></span><?php echo $due_dt;?></td>
                        <td><?php echo $l->task_priority;?></td>
                        <?php $l_arr = (array)$l;?>
                        <input type="hidden" id="task_data_<?php echo $l->task_id;?>" value="<?php echo htmlspecialchars(json_encode($l_arr)); ?>" />
                      </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
          <div id="sortableItem_3" class="sortableList-item fixheight col-md-6 ">   <!--   id="nextweek"  -->
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_3" class="portlet box blue blue-txt"  >
              <div class="portlet-title">
                <div class="caption">My Time This Week</div>
              </div>
              <div class="portlet-body portlet-minhgt minimumhight">
			   <!--<div class="row fixheight">-->
                <div>
                	<?php if($task_thisweek!='0'){
                		  $format = '%02d:%02d';
							foreach ($task_thisweek as $t) {
							if($user_time!='0'){
                			 
                                                        if($t["day"] =='Monday'){
						
								$is_availabe = $user_time->MON_closed;
								$estimate_hours = intval($t["task_time"]/60);
								$estimate_minutes = ($t["task_time"] % 60);
								$prog = minutesToHourMinutes($user_time->MON_hours);
								$ration = ($user_time->MON_hours!='0')?(round($t["task_time"]/($user_time->MON_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes); 
								
							}
							if($t["day"] =='Tuesday'){
									
								$is_availabe = $user_time->TUE_closed;
								$estimate_hours = intval($t["task_time"]/60);
								$estimate_minutes = ($t["task_time"] % 60);
								$prog = minutesToHourMinutes($user_time->TUE_hours);
								$ration = ($user_time->TUE_hours!='0')?(round($t["task_time"]/($user_time->TUE_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Wednesday'){
							
								$is_availabe = $user_time->WED_closed;
								$estimate_hours = intval($t["task_time"]/60);
								$estimate_minutes = ($t["task_time"] % 60);
								$prog = minutesToHourMinutes($user_time->WED_hours);
								$ration = ($user_time->WED_hours!='0')?(round($t["task_time"]/($user_time->WED_hours)*100)):00;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Thursday'){
									
								$is_availabe = $user_time->THU_closed;
								$estimate_hours = intval($t["task_time"]/60);
								$estimate_minutes = ($t["task_time"] % 60);
								$prog = minutesToHourMinutes($user_time->THU_hours);
								$ration = ($user_time->THU_hours!='0')?(round($t["task_time"]/($user_time->THU_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								 
							}
							if($t["day"] =='Friday'){
									
								$is_availabe = $user_time->FRI_closed;
								$estimate_hours = intval($t["task_time"]/60);
								$estimate_minutes = ($t["task_time"] % 60);
								$prog = minutesToHourMinutes($user_time->FRI_hours);
								$ration = ($user_time->FRI_hours!='0')?(round($t["task_time"]/($user_time->FRI_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								 
							}
							if($t["day"] =='Saturday'){
									
								$is_availabe = $user_time->SAT_closed;
								$estimate_hours = intval($t["task_time"]/60);
								$estimate_minutes = ($t["task_time"] % 60);
								$prog = minutesToHourMinutes($user_time->SAT_hours);
								$ration = ($user_time->SAT_hours!='0')?(round($t["task_time"]/($user_time->SAT_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
								
							}
							if($t["day"] =='Sunday'){
									
								$is_availabe = $user_time->SUN_closed;
								$estimate_hours = intval($t["task_time"]/60);
								$estimate_minutes = ($t["task_time"] % 60);
								$prog = minutesToHourMinutes($user_time->SUN_hours);
								$ration = ($user_time->SUN_hours!='0')?(round($t["task_time"]/($user_time->SUN_hours)*100)):0;
								$prog_clr = ($ration > 100)?'#f35958':'#0e90d2';
								$time_with_format = sprintf($format, $estimate_hours, $estimate_minutes);
									 
							}

						  ?>  
						
				<div class="progress-loop clearfix">
                                    <div style="float:left;width:20%"> <span> <?php echo $t["day"];?> </span></div>
                    <div style="width:60%;float:left;">
                        <div class="progress" >
                            <div style="width: <?php echo $ration;?>%; background-color:<?php echo $prog_clr; ?>" class="progress-bar" role="progressbar"></div>
                         </div>
                    </div>
                    <div class=" text-right"> <span ><?php echo $time_with_format ;?> / <?php echo $prog;?> </span></div>
                     </div>
                    <?php }else{?>
                    	<div class="margin-top-20 clearfix txt-clr">&nbsp; </div>
                    	<?php } } }else{ ?>
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
       <!-- <div class="row">-->
          <div id="sortableItem_4" class="sortableList-item col-md-6 fixheight " >
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div data-parent="sortableItem_4" class="portlet box blue blue-txt">
              <div class="portlet-title">
                <div class="caption">Time Allocation For Next 5 days by Category </div>
              </div>
              <div class="portlet-body minimumhight">
              	
              	<!--<div class="text-center chartdiv ajax_category_data" id="piechart" ></div>
              	<div class="text-center chartdiv" id="piechart1" style="display: none" ></div>-->
              	<div  class="ajax_category_data chartdiv_dashboard"  id="chartdivcat"></div>
              	<div style="display: none;" class="chartdiv_dashboard"  id="chartdivcat1"></div>
              	
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
         
          
        <!--</div>-->
        <!-- END PAGE CONTENT-->
      </div>
    </div>
    <!-- END PAGE CONTAINER-->
 </div>
<?php date_default_timezone_set("UTC"); ?>

<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/amcharts.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/serial.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/light.js?Ver=<?php echo VERSION;?>"></script>

<script type="text/javascript">
	
var chart = AmCharts.makeChart( "chartdivcat", {
  "type": "serial",
  "theme": "light",
  "depth3D": 20,
  "angle": 30,
  "legend": {
    "horizontalGap": 10,
    "useGraphSettings": true,
    "markerSize": 10
  },
  "dataProvider": [ 
  <?php 
  
 
  if($timeallocationchart){
  	
	foreach ($timeallocationchart as $key => $value) {
		
	?>
  			
  {
    "Date": "<?php echo date("d/m",strtotime($key));?>"
    <?php foreach ($value as $key_est => $value_est) { ?>
    ,"<?php echo ($key_est!='0')?$value_est['category_name']:"No Category";?>": <?php echo $value_est['task_time_estimate'];?>
    	<?php }  ?>
  },
  <?php } }  ?>  
   ],
  "valueAxes": [ {
    "stackType": "regular",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "minimum": 0,
    "maximum": 1200	,
    "autoGridCount":false,
    "gridCount": 12,
    "labelFunction": function(value) {
      	return Math.round(value/60);
    } 
  } ],
  "graphs": [
  
  <?php if($timeallocationchart){
  	foreach ($categories as $key => $value) {  	
		?>
	    {
	   "balloonFunction": function(item) {
	      return "<span style='font-size:14px'><b>"+item.graph.title+"</b> : <b>" + minutesToTime(item.values.value) + "</b></span>";
	    },
	    "fillAlphas": 0.8,
	    "labelText": "",
	    "lineAlpha": 0.3,
	    "title": "<?php echo ($value!='0')?get_category_name($value):"No Category";?>",
	    "type": "column",
	    "color": "#000000",
	    "valueField": "<?php echo ($value!='0')?get_category_name($value):"No Category";?>"
	  },
	  
  <?php } }  ?>
  ],
  "categoryField": "Date",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  }

} );

function minutesToTime(minutes) {
	
	var hr = Math.floor(minutes / 60);
	var min = minutes - (hr * 60);
	
	if(hr=='0' && min=='0'){
		return '0m';
	} else if(hr!='0' && min =='0'){
		return hr + 'h';
	} else if(hr == '0' && min !='0'){
		return min + 'm';
	} else {
		return hr + 'h'+ min + 'm';
	}
	
}

</script>

