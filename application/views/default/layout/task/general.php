<?php

$theme_url = base_url().getThemeName();
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
$completed_id = $this->config->item('completed_id');
$default_swimlae = get_default_swimlane(get_authenticateUserID());
$default_color = get_default_color(get_authenticateUserID());
$company_flags = $this->config->item('compay_flags');
$actaul_time_on = '0';
if($company_flags){
	$actaul_time_on = $company_flags['actual_time_on'];
}
?>

<script type="text/javascript">

	var LOG_USER_NAME = USERNAME;
	var ACTUAL_TIME_ON = '<?php echo $actaul_time_on;?>';
	var COMPLETED_ID = '<?php echo  $completed_id;?>';
	var DEFAULT_SWIMLANE = <?php echo $default_swimlae;?>;
	var TEAM_MY_TASK = <?php echo isset($mytask)?$mytask:'0'; ?>;
	var TEAM_TASK = <?php echo isset($teamtask)?$teamtask:'0';?>;
	var TASK_BY_CAT_TOT = <?php echo isset($taskByCat_tot)?$taskByCat_tot:'0';?>;
	var DASHBOARD_NONE = <?php echo isset($none)?$none:'0';?>;
	var DASHBOARD_LOW= <?php echo isset($low)?$low:'0';?>;
	var DASHBOARD_MEDIUM = <?php echo isset($medium)?$medium:'0';?>;
	var DASHBOARD_HIGH = <?php echo isset($high)?$high:'0';?>;
	var ACTIVE_MENU = '<?php echo isset($active_menu)?$active_menu:'';?>';
	var DEFAULT_COLOR = '<?php echo $default_color;?>';


</script>

<script src="<?php echo $theme_url;?>/assets/js/task-general<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<!--- ####################################################################### -->

<div class="modal-header" style="display:block;">
	<button type="button" class="close save_close_cross" onmousedown="close_popup_general();" data-dismiss="modal" aria-hidden="true"></button>
        <h3 style="margin-top: 10px"> Task  </h3>
 </div>
<div class="modal-body">
	<!-- BEGIN PAGE CONTENT-->
	<div class="taskmain-container">
	 	<div class="user-block">
		 	<div class="row">
				<div class="col-md-12 ">
					<div class="usertabs">
					<div class="tabbable tabbable-custom">
							<ul class="nav nav-pills task_navs" style="padding-left: 10px; background-color: #fff;padding-bottom: 2px;padding-top: 2px;">
							<li class="active"><a href="#task_tab_1" data-toggle="tab">General</a></li>
							<li><a  href="#task_tab_2" id="allocation_tab" class="task_tabs" data-toggle="tab">Allocation</a></li>
							<li><a  href="#task_tab_3" id="dependent_tab" class="task_tabs" data-toggle="tab">Dependencies</a></li>
							<li><a  href="#task_tab_4" class="task_tabs" data-toggle="tab">Steps</a></li>
							<li><a  href="#task_tab_5" class="task_tabs" data-toggle="tab">Frequency</a></li>
							<li><a  href="#task_tab_6" id="file_tab" class="task_tabs" data-toggle="tab">Files</a></li>
							<li><a  href="#task_tab_7" id="cmt_tab"  class="task_tabs" data-toggle="tab">Comments</a></li>
							<li><a  href="#task_tab_8" id="history_tab" class="task_tabs" data-toggle="tab">History</a></li>
						 </ul>
                                            <div class="tab-content set-task-popup" >
							<div class="tab-pane task-tab-pane active" id="task_tab_1">
								<?php $this->load->view($theme.'/layout/task/general_html') ?>
							</div> <!-- Tab 1 -->
							<div class="tab-pane task-tab-pane" id="task_tab_2">
								<?php $this->load->view($theme.'/layout/task/allocation') ?>
							</div> <!-- Tab 2 -->
							<div class="tab-pane task-tab-pane" id="task_tab_3">
								<?php  $this->load->view($theme.'/layout/task/dependencies')?>
							</div> <!-- Tab 2 -->
							<div class="tab-pane task-tab-pane " id="task_tab_4">
								<?php  $this->load->view($theme.'/layout/task/steps')?>
							</div> <!-- Tab 3 -->
							<div class="tab-pane task-tab-pane"  id="task_tab_5">
								<?php  $this->load->view($theme.'/layout/task/frequency')?>
							</div> <!-- Tab 4 -->
							<div class="tab-pane task-tab-pane"  id="task_tab_6">
								<?php  $this->load->view($theme.'/layout/task/files')?>
							</div> <!-- Tab 4 -->
							<div class="tab-pane task-tab-pane"  id="task_tab_7">
								<?php  $this->load->view($theme.'/layout/task/comments')?>
							</div> <!-- Tab 4 -->
							<div class="tab-pane task-tab-pane" id="task_tab_8">
								<?php  $this->load->view($theme.'/layout/task/history')?>
							</div>
							<input type="hidden" name="event" id="event" value=""/>
							<input type="hidden" name="tab_data_change" id="tab_data_change" value="0" />
							<input type="hidden" name="allocation_data_change" id="allocation_data_change" value="0" />
							<input type="hidden" name="freq_data_change" id="freq_data_change" value="0" />
							<input type="hidden" name="is_dependency_added" id="is_dependency_added" value="0" />
							<input type="hidden" name="dashboard_priority" id="dashboard_priority" value="" />
							<input type="hidden" name="dashboard_duration" id="dashboard_duration" value="" />
							<input type="hidden" id="is_multi_changed" value="0" />
							<div class="form-actions task-poup-footer task-footer col-md-12">
							 	<div class="task-time-div " style="float: right;">
							 		<div class="form-group floating">
                                                                            <label class="control-label floating" style="margin-top: 6px;" >Time Estimate </label> 
										<div class="controls relative-position floating">
											<input class="m-wrap m-ctrl-small small_input task-input" name="task_time_estimate" id="task_time_estimate" placeholder="0h" value="" type="text"  tabindex="12" />									
											<input type="hidden" name="is_edited1" id="is_edited1" value="" />
<!--											<span class="input-load" id="task_time_estimate_loading"></span>-->
										</div>
									</div>
									<div class="form-group floating">	
                                                                            <label class="control-label floating" style="margin-top: 6px;">Time Spent </label>
										<div class="controls relative-position floating">
											<input class="m-wrap m-ctrl-small small_input orgst task-input" name="task_time_spent" id="task_time_spent" placeholder="0h"  value="" type="text"  tabindex="13" />								 	
											<input type="hidden" name="is_edited" id="is_edited" value="" />
											<!--<span class="input-load" id="task_time_spent_loading"></span>-->
										</div>
									</div>
								</div>
								
							 	<div style="float: left;">
							 		
						 			<input type="hidden" name="recurring_type" id="recurring_type" value=""/>
						 			<button type="button" data-dismiss="modal" class="btn btn-common-blue save_close" id="general_save_close" onmousedown="close_popup_general();"><i class="stripicon icosave"></i>  Save & Close</button>
									<button type="button" class="btn btn-common-red delete_task_btn" style="display: none;" onclick="delete_task();" id="delete_task_" data-toggle="confirmation" data-placement="right"><i class="stripicon icoremove"></i> Delete </button>
								</div>
							 </div>
						 </div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
</div>
<!--<div id="manual_reason" class="modal container hide fade" tabindex="-1" >
	<div class="portlet">
		<div class="portlet-body  form flip-scroll">
			<div class="modal-header">
				<button type="button" class="close manual_reason_close" aria-hidden="true"></button>
				<h3>Reason</h3>
			</div>
			<div>
				<form name="frm_manual_reason" id="frm_manual_reason" action="">
					<div class="addcomment-block">
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label" for="firstName"> <strong> Add Reason :<span class="required">*</span> </strong></label>
									<div class="controls">
										<textarea rows="3" name="manual_reason_txt" maxlength="<?php echo CMT_TEXT_SIZE;?>" id="manual_reason_txt" class="span12 m-wrap"></textarea>
									  </div>
								</div>
								<span class="chr">Char left :- <i id="ch2"><?php echo CMT_TEXT_SIZE;?></i></span>
								<div class="pull-right">
									<input type="hidden" name="task_id" id="manual_reason_task_id" value="" />
									<input type="hidden" name="manual_spent_hour" id="manual_spent_hour" value="" />
									<input type="hidden" name="manual_spent_min" id="manual_spent_min" value="" />
									<button type="submit" class="btn blue txtbold"> Submit </button>
								</div>
							</div>
						 </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>-->
<!-- ####################################################################### -->
