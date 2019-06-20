<script type="text/javascript">
	$(document).ready(function(){
		App.init();
		calltasklist();
                inlin_edit();
                trunc_task_title();
$(".panel-heading").click(function(){
  $(this).siblings(".panel-body").toggle();
  $(this).find('.expand_sections i').toggleClass("icon-chevron-down");
   $(this).find('.expand_sections i').toggleClass("icon-chevron-right");
});
	});
</script>
<div class="tab-pane srtab active" id="panel-body_srtab" >
<style>
    .accrodian .panel-body ul li{
        padding: 4px !important;   
}
</style>
	<?php
        $task_status = get_taskStatus($this->session->userdata('company_id'),'Active'); 
        $color_codes = get_user_color_codes($this->session->userdata('user_id'));
         $swimlanes = get_user_swimlanes(get_authenticateUserID());
        $default_format = $this->config->item('company_default_format');
	$task_status_completed_id = $this->config->item('completed_id');
                    $date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
             $dat='';
        foreach ($date_arr_java  as $v=>$val){
            if($v==$default_format){
                $dat=$val;
            }
        }
           $report_user_list_id='';
	if(isset($section) && $section!=''){
            foreach($section as $s){
                $tasktot = total_task($s->section_id);
               // if($tasktot> 0)
                
            ?>
                <div class="sortable border margin-bottom-15 full-width tasks_tab" id="Stab_<?php echo $s->section_id;?>">
                    <div id="ch_sec_<?php echo $s->section_id;?>"  class="panel-heading pointer">
                       <a <?php if($is_owner!='0'){ ?> class="changesecname"  sec_type="section" sec_prj_id="<?php echo $s->project_id;?>" data-pk="<?php echo $s->section_id;?>" <?php }else{?> <?php } ?> ><?php echo $s->section_name;?></a>
                         <span class="expand_sections" style="float:left"><i class="icon-chevron-down default_color"></i></span>
                            <?php if($is_owner!='0'){ ?>
                                <a onclick="delete_section('<?php echo $s->section_id;?>');"  href="javascript://" ><i class="icon-trash prjicn" id = "delete_section_<?php echo $s->section_id;?>"></i></a>
                                   <?php } ?>
                    </div>
                <div class="panel-body" id="panel-body_<?php echo $s->section_id;?>">
                    <?php 
                        if($subSection[$s->section_id]!='0'){ 
                            foreach ($subSection[$s->section_id] as $sub) {
                                if($sub!='0'){
                                    $tasktot1 = total_sub_task($sub->section_id);
                                    ?> 
                                    <div id="Subtab_<?php echo $sub->section_id;?>" class="border margin-bottom-15">
                                        <div id="ch_subsec_<?php echo $sub->section_id;?>" class="panel-heading gray pointer">
                                            <a <?php if($is_owner!='0' ){ ?>  class="changesecname"  sec_type="subsection" sec_prj_id="<?php echo $sub->project_id;?>" data-pk="<?php echo $sub->section_id;?>"  <?php }else{?> <?php } ?> ><?php echo $sub->section_name;?></a>
                                            <span class="expand_sections" style="float:left"><i class="icon-chevron-down default_color"></i></span>
                                             <?php if($is_owner!='0'){ ?>
                                                 <a onclick="delete_subsection('<?php echo $sub->section_id;?>');" href="javascript://" ><i class="icon-trash prjicn" id="delete_subsection_<?php echo $sub->section_id;?>"></i></a>
                                    <?php }?>
                                        </div>
                                        <div id="taskmove_<?php echo $sub->section_id;?>_<?php echo $sub->main_section;?>" class="panel-body panel-body_<?php echo $s->section_id;?>">
                                            <?php 
                                                $task_detail = getTaskDetail($sub->section_id,$sub->main_section,$project_id,$task_status_completed_id);
                                                    if($task_detail!='0'){
                                                       foreach ($task_detail as $td) {
                                                            $tmp = (array) $td;
                                                            if(!empty($tmp)){
                                                                
                                                                date_default_timezone_set($this->session->userdata("User_timezone"));
                                                                $today = date($site_setting_date);
                                                                //date_default_timezone_set("UTC");
                                                                if($td->master_task_id){
                                                                    $is_master_deleted = chk_master_task_id_deleted($td->master_task_id);
                                                                } else {
                                                                    $is_master_deleted = 0;
                                                                }
                                                                //echo $user_id."====111===".$td->task_allocated_user_id;	
                                                                if($td->task_due_date != '0000-00-00'  ){
                                                                    $due_dt = date($site_setting_date,strtotime($td->task_due_date));
                                                                                //echo $due_dt;die;
                                                                } else{
                                                                     $due_dt = "N/A";
                                                                }
                                                                if($type =='ut'){
                                                                    $con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) > strtotime(str_replace(array("/"," ",","),"-", $today)));
                                                                    $due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
                                                                    $completed = '1 == 1';
                                                                //echo "cd";
                                                                }else if($type =='tt'){									
                                                                    $con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) == strtotime(str_replace(array("/"," ",","),"-", $today)));
                                                                    $due_not = strtotime($td->task_due_date)!= strtotime('0000-00-00');
                                                                    $completed = '1 == 1';
                                                                }else if($type == 'ot'){									
                                                                    $con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) < strtotime(str_replace(array("/"," ",","),"-", $today)));
                                                                    $due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
                                                                    $completed = $task_status_completed_id==$td->task_status_id;
                                                                }else if($type =='all'){
                                                                    $con = '1 == 1';	
                                                                    $due_not = '1 == 1';
                                                                    $completed = '1 == 1';
                                                                }else if($type =='opt'){
                                                                    $con = '1 == 1';	
                                                                    $due_not = '1 == 1';
                                                                    $completed =$task_status_completed_id==$td->task_status_id;
                                                                }
                                                                if (strpos($td->task_id,'child') !== false) {
                                                                     $chks = "0";
                                                                } else {
                                                                     $chks = "1";
                                                                }
                                                                if(($user_id =='all' || $user_id == $td->task_allocated_user_id) && $con && $due_not && $completed!='1'){
                                                                     if($td->task_due_date == '0000-00-00')
                                                                                                                                                $due_date1 = '';
                                                                                                                                            else 
                                                                                                                                                $due_date1 =date("m-d-Y",strtotime($td->task_due_date));
                                                                                                                                            if($td->task_scheduled_date == '0000-00-00')
                                                                                                                                                $scheduled_date = '';
                                                                                                                                            else 
                                                                                                                                                $scheduled_date =date("m-d-Y",strtotime($td->task_scheduled_date));
                                                                                                                                            
                                                                                                                                            $report_user_list_id='0';
                                                                                                                                             if(isset($all_report_user) && !empty($all_report_user)){
                                                                                                                                            foreach($all_report_user as $val ){
                                                                                                                                                if($val['user_id']==$td->task_owner_id){
                                                                                                                                                   $report_user_list_id='1';  
                                                                                                                                                }
                                                                                                                                            }
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
                                                                <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){ 
                                                                        $disabled = 'disabled="disabled"';
                                                                      }else{
                                                                        $disabled = '';
                                                                    }?>
                                                                <div onclick="save_task_for_timer(this,'<?php echo $td->task_id;?>','<?php echo addslashes($td->task_title);?>','<?php echo $td->task_time_spent;?>','<?php echo $chks;?>','<?php echo $td->completed_depencencies;?>');" class="task_tasksort project_master_task_<?php echo $td->master_task_id;?> before_timer" id="task_tasksort_<?php echo $td->task_id;?>" >
                                                                    <ul class="clearfix cst_ul">
                                                                        <li style="margin-top:7px;"><i class="icon-align-justify prjicn"></i></li>
                                                                        <li ><input class="projectTask" <?php echo $disabled;  ?> onclick="changeTaskStatus('<?php echo $td->task_id;?>','<?php echo $td->subsection_id;?>','<?php echo $td->section_id;?>','<?php echo $td->task_time_spent;?>');" name="task_status" id="pr_task_status_<?php echo $td->task_id;?>" value="" type="checkbox" <?php if($td->task_status_id == $task_status_completed_id){ ?> checked="checked" <?php } ?> ></li>
                                                                        
                                                                        <li style="margin-top:5px;"><?php if($td->task_status_id == $task_status_completed_id){?><em class="linethrought"><?php }else{?> <?php }?>
                                                                        <?php if($td->master_task_id == '0' || $is_master_deleted=="1"){ ?>
                                                                                <div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?> oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php } ?> >
                                                                                    <a   id="tskname_<?=$td->task_id?>" data-toggle="modal" href="javascript:void(0)"  <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?> onclick="edit_task(this,'<?php echo $td->task_id;?>','<?php echo $chks;?>')" <?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
                                                                                </div>
                                                                                    <?php } else { ?>
                                                                                <div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?> oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php } ?> >
                                                                                    <a  id="tskname_<?=$td->task_id?>" data-toggle="modal" href="javascript:void(0)" <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?>  onclick="open_seris(this,'<?php echo $td->task_id;?>','<?php echo $td->master_task_id;?>','<?php echo $chks;?>')" <?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
                                                                                </div>
                                                                                    <?php } ?>
                                                                        <?php if($td->task_status_id == $task_status_completed_id){?></em ><?php }else{?> <?php }?>
                                                                      
                                                                        </li>
                                                                           <li ><?php if($task_status){
                                                                               echo'<span class="updttskstatus">(Status: ';
                                                                               foreach($task_status as $v){
                                                                                   if($v->task_status_id==$td->task_status_id){ ?>
                                                                               <a href="javascript:void(0)"  class="task_status_editable"  data-value="<?php echo $v->task_status_id;?>" data-type="select" data-pk="<?=$td->task_id?>"  data-original-title="Task Status" data-emptytext="Status" data-taskId="<?=$td->task_id?>"><?php echo $v->task_status_name;?></a>
                                                                                <?php    } }echo'</span>';  }?>
                                                                             </li>
                                                                <li><span class="updtallcuserl">- Allocated to  <a href="javascript:void(0)"  class="task_allocated_user_editable" data-value="<?=$td->task_allocated_user_id?>" data-type="select"    data-pk="<?=$td->task_id?>" data-original-title="Select User" data-emptytext="User" ><?php echo  ucwords($td->first_name)." ".ucwords($td->last_name);?></a>
                                                                   </span></li>
                                                                          <?php  if($td->task_due_date!="0000-00-00" && $due_dt != 'N/A'){
				               if($td->task_status_id != $task_status_completed_id && (strtotime(str_replace(array("/"," ",","), "-", $due_dt)) < strtotime(str_replace(array("/"," ",","),"-", $today))))
                                                                                      { ?>
                                                                                       <li><span class="red_date" >- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <p  class="red"> <?php echo $due_dt;?></p></a> </span>)</li>
                                                                                        <?php }else if($td->task_status_id == $task_status_completed_id){ ?>
                                                                                         <li>- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> )</li>
                                                                                            <?php }else{?>
                                                                                           <li >- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' data-format="<?=$dat?>" class="task_due_date_editable" > <?php echo $due_dt;?></a> )</li>
                                                                                              <?php } }else{?>
                                                                                                <li >-  <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> No Due Date)</a></li>
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
                                                                                    if($td->comments){ ?>
                                                                                                                            <li><a class="tooltips" data-placement="right" data-original-title="Comments" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_7')" href="javascript:void(0);"><i class="icon-comment-alt prjicn"> </i><sup><?=$td->comments?></sup></a></li>
                                                                            <?php }
                                                                            }?>
                                                                                                      <?php if($td->files){ ?><li><a class="tooltips" data-placement="right" data-original-title="Task Files" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_6')" href="javascript:void(0);"><i class="icon-paperclip prjicn"> </i><sup><?=$td->files?></sup></a></li><?php } ?>
                                                                                                        <?php if($td->steps){ ?> <li><a class="tooltips" data-placement="right" data-original-title="Task Steps" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_4')" href="javascript:void(0);"><i class="icon-list-ul prjicn"> </i><sup><?=$td->steps?></sup></a></li>
                                                                                                            <?php }?>

                                                                    </ul>	
                                                                    <input type="hidden" id="task_data_<?php echo $td->task_id;?>" value="<?php echo htmlspecialchars(json_encode($td)); ?>" />
                                                                </div>			

                                                                <?php } } } }?>
                                                            <div> </div>
                                                            <div class="unsorttd" > </div>
                                                           
                                                            <div id="chngSubNm_<?php echo $sub->section_id;?>" class="modal model-size pro-change fade" tabindex="-1" >
                                                                <div class="portlet">
                                                                    <div class="portlet-body  form flip-scroll">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close cmt_close" data-dismiss="modal" aria-hidden="true"></button>
                                                                            <h3>Sub Section Name</h3>
                                                                        </div>
                                                                        <div>
                                                                            <div class="addcomment-block">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 ">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label" > <strong> Sub Section Name : </strong><span class="required">*</span></label>
                                                                                            <div class="controls">
                                                                                                <input type="text" class="m-wrap" id="subsection_name_<?php echo $sub->section_id;?>" name="subsection_name_<?php echo $sub->section_id;?>" value="<?php echo htmlspecialchars($sub->section_name, ENT_QUOTES);?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="pull-right">
                                                                                            <input type="hidden" name="section_id" id="section_id" value="<?php echo $sub->section_id;?>" />
                                                                                            <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                                                            <input type="hidden" name="tab" id="tab" value="tab_1" />
                                                                                            <button type="button" id="subsection_submit_<?php echo $sub->section_id;?>" name="section_submit" class="btn blue txtbold"> Submit </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             <?php //if($is_owner!='0'){ ?>
                                                            <button onclick="my_custom_task_edit('<?php echo $project_id;?>','<?php echo strtotime(date($default_format));?>','<?php echo $sub->section_id;?>','<?php echo $sub->main_section;?>','<?php echo rawurlencode($s->section_name);?>');datapass('<?php echo $sub->section_id;?>','<?php echo $sub->main_section;?>','<?php echo rawurlencode($s->section_name);?>');" href="javascript:void(0)" type="button" name="task" name="task" class="btn-new green unsorttd" id="pro_button_<?php echo $sub->section_id;?>" style="min-width: 0px !important;">Add Task</button>
                                                            <?php //}?> 
                                        </div>
                                    </div>
<script type="text/javascript">

	$(document).ready(function(){

	$('#chngSubNm_'+<?php echo $sub->section_id;?>).on( 'keypress', function( e ) {
        if( e.keyCode === 13 ) {
            e.preventDefault();
            $("#subsection_submit_"+<?php echo $sub->section_id;?>).trigger('click');
        }
    });



	$('#subsection_submit_'+<?php echo $sub->section_id;?>).click(function(){

    	//alert('in');

    	var filter = $('#typefilter1 li.active').attr('id');
		var id = $("#select_task").val();

    	if($('#subsection_name_'+<?php echo $sub->section_id;?>).val()!=''){

    		$('#dvLoading').fadeIn('slow');
			$.ajax({
					type: 'POST',
					url : "<?php echo site_url('project/update_sectionName') ?>",
					data:{section_name:$("#subsection_name_"+<?php echo $sub->section_id;?>).val(),section_id:<?php echo $sub->section_id;?>,tab:'tab_1',project_id:<?php echo $sub->project_id;?>,type:'subsection',user_id:id,filter:filter},
					success : function(data) {
						if(data!=''){

						$('#chngSubNm_'+<?php echo $sub->section_id;?>).modal('hide');
						$("#ch_subsec_"+<?php echo $sub->section_id;?>).html(data);
						$('#dvLoading').fadeOut('slow');

						}else{
							$('#dvLoading').fadeOut('slow');

						}
					},

				});
			}else{
				alertify.alert('Field can not be empty');
			}

		});


	});

	// task-container

	</script>	

			
															
				<?php } }
				} ?>
                                <div class="unsorttd"></div>												
                                <div class="panel-body1" id="panel-body1_<?php echo $s->section_id;?>">												
                                <?php //if($is_owner!='0'){ ?>											
                                        <div class=" unsorttd">
                                            <div class="row">
                                                   <div class="col-md-6" style="padding-top: 3px;">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <input type="text" name="project_subsection_<?php echo $s->section_id;?>" id="project_subsection_<?php echo $s->section_id;?>" class="col-md-12 m-wrap" placeholder="Enter sub-section name"/>
                                                            </div>
                                                        </div>
                                                   </div>
                                                    <div class="col-md-6 pull-left" style="padding-top:5px">
                                                        <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                        <button type="button" class="subsection_btn btn-new unsorttd" onclick="createSubSection('<?php echo $s->section_id;?>','<?php echo rawurlencode($s->section_name);?>','<?php echo $project_id;?>');" >Add Sub Section</button>
                                                   </div>
                                            </div>
                                        </div>
                                <?php //} ?>
			
			<!-- main task settings for section start here -->
			
					<?php 
					
					$task_detail = getTaskDetail($s->section_id,'0',$project_id,$task_status_completed_id);
					
					
					if($task_detail!='0'){
								foreach ($task_detail as $td) {
									$tmp = (array) $td;
									if(!empty($tmp)){
                                    
									//$task_status_completed_id = get_task_status_id_by_name('Completed');
									date_default_timezone_set($this->session->userdata("User_timezone"));
									$today = date($site_setting_date);
									//echo $today."==345";
									//date_default_timezone_set("UTC");
									
									if($td->master_task_id){
										$is_master_deleted = chk_master_task_id_deleted($td->master_task_id);
									} else {
										$is_master_deleted = 0;
									}
									
									if($td->task_due_date != '0000-00-00'  ){
		
										$due_dt = date($site_setting_date,strtotime($td->task_due_date));
										//echo $due_dt;die;
									} else{

										$due_dt = "N/A";
									}
									
									//echo $type ."==== dt".$due_dt;
									//echo date($site_setting_date,strtotime($td->task_due_date));die;
									if($type =='ut'){
										$con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) > strtotime(str_replace(array("/"," ",","),"-", $today)));
										$due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
										$completed = '1 == 1';
										//echo "cd";
									}else if($type =='tt'){									
										$con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) == strtotime(str_replace(array("/"," ",","),"-", $today)));
										$due_not = strtotime($td->task_due_date)!= strtotime('0000-00-00');
										$completed = '1 == 1';
									}else if($type == 'ot'){									
										$con = (strtotime(str_replace(array("/"," ",","), "-", date($site_setting_date,strtotime($td->task_due_date)))) < strtotime(str_replace(array("/"," ",","),"-", $today)));
										$due_not = strtotime($td->task_due_date)!=strtotime('0000-00-00');
										$completed = $task_status_completed_id==$td->task_status_id;
									}else if($type =='all'){
										$con = '1 == 1';	
										$due_not = '1 == 1';
										$completed = '1 == 1';
									}else if($type =='opt'){
										$con = '1 == 1';	
										$due_not = '1 == 1';
										$completed =$task_status_completed_id==$td->task_status_id;
									}
									
									if (strpos($td->task_id,'child') !== false) {
									    $chks = "0";
									} else {
										$chks = "1";
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
                                                                        if(($user_id =='all' || $user_id == $td->task_allocated_user_id) && $con && $due_not && $completed!='1'){
                                                                             if($td->task_due_date == '0000-00-00')
                                                                                                                                                $due_date1 = '';
                                                                                                                                            else 
                                                                                                                                                $due_date1 =date("m-d-Y",strtotime($td->task_due_date));
                                                                                                                                            if($td->task_scheduled_date == '0000-00-00')
                                                                                                                                                $scheduled_date = '';
                                                                                                                                            else 
                                                                                                                                                $scheduled_date =date("m-d-Y",strtotime($td->task_scheduled_date));
                                                                                                                                            
                                                                                                                            $report_user_list_id='0';               
                                                                                                                            if(isset($all_report_user) && !empty($all_report_user)){
                                                                                                                                foreach($all_report_user as $val ){
                                                                                                                                    if($val['user_id']==$td->task_owner_id){
                                                                                                                                       $report_user_list_id='1';  
                                                                                                                                    }
                                                                                                                                }
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
                                                                                <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){ 
                                                                                    $disabled = 'disabled="disabled"';
                                                                                  }else{
                                                                                    $disabled = '';
                                                                                }?>
                                                                            <div onclick="save_task_for_timer(this,'<?php echo $td->task_id;?>','<?php echo addslashes($td->task_title);?>','<?php echo $td->task_time_spent;?>','<?php echo $chks;?>','<?php echo $completed_depencencies;?>');" id="task_tasksort_<?php echo $td->task_id;?>" class="task_tasksort project_master_task_<?php echo $td->master_task_id;?>">
                                                                                <ul class="clearfix cst_ul">
                                                                                    <li style="margin-top:7px;"><i class="icon-align-justify prjicn"></i></li>
                                                                                    <li><input class="projectTask" <?php echo $disabled; ?> onclick="changeTaskStatus('<?php echo $td->task_id;?>','<?php echo $td->subsection_id;?>','<?php echo $td->section_id;?>','<?php echo $td->task_time_spent;?>');" name="task_status" id="pr_task_status_<?php echo $td->task_id;?>" value="" type="checkbox" <?php if($td->task_status_id == $task_status_completed_id){ ?> checked="checked" <?php } ?> ></li>
										
                                                                                    
                                                                                    <li style="margin-top:5px;"><?php if($td->task_status_id == $task_status_completed_id){?><em class="linethrought"><?php }else{?> <?php }?>
											<?php if($td->master_task_id == '0' || $is_master_deleted=="1"){ ?>
                                                                                            <div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?> oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php } ?> >
                                                                                                <a  id="tskname_<?=$td->task_id?>" data-toggle="modal" href="javascript:void(0)" <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?>  onclick="edit_task(this,'<?php echo $td->task_id;?>','<?php echo $chks;?>')" <?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title); ?></a>
                                                                                            </div>
                                                                                                    <?php } else { ?>
                                                                                            <div id="task_<?php echo $td->task_id;?>" <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?> oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');" <?php } ?> >
                                                                                                <a id="tskname_<?=$td->task_id?>" data-toggle="modal" href="javascript:void(0)"  <?php if($this->session->userdata('is_customer_user') == '1' && $td->task_allocated_user_id != get_authenticateUserID() ){}else{ ?> onclick="open_seris(this,'<?php echo $td->task_id;?>','<?php echo $td->master_task_id;?>','<?php echo $chks;?>')" <?php } ?> data-dismiss="modal" ><?php echo ucwords($td->task_title);?></a>
                                                                                            </div>
                                                                                        <?php } ?>
                                                                                    <?php if($td->task_status_id == $task_status_completed_id){?></em><?php }else{?> <?php }?>
                                                                                    
                                                                        </li>
                                                                       <li ><?php if($task_status){
                                                                               echo'<span class="updttskstatus">(Status: ';
                                                                               foreach($task_status as $v){
                                                                                   if($v->task_status_id==$td->task_status_id){ ?>
                                                                               <a href="javascript:void(0)"  class="task_status_editable"  data-value="<?php echo $v->task_status_id;?>" data-type="select" data-pk="<?=$td->task_id?>" data-original-title="Task Status" data-emptytext="Status" data-taskId="<?=$td->task_id?>"><?php echo $v->task_status_name;?></a>
                                                                                <?php    } }echo'</span>';  }?>
                                                                             </li>
                                                                <li><span class="updtallcuserl">- Allocated to  <a href="javascript:void(0)"  data-value="<?=$td->task_allocated_user_id?>" class="task_allocated_user_editable" data-type="select" data-original-title="Select User" data-emptytext="User"    data-pk="<?=$td->task_id?>" ><?php echo  ucwords($td->first_name)." ".ucwords($td->last_name);?></a>
                                                                   </span></li>
                                                                          <?php  if($td->task_due_date!="0000-00-00" && $due_dt != 'N/A'){
				               if($td->task_status_id != $task_status_completed_id && (strtotime(str_replace(array("/"," ",","), "-", $due_dt)) < strtotime(str_replace(array("/"," ",","),"-", $today))))
                                                                                      { ?>
                                                                                       <li><span  class="red_date">- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> </span>)</li>
                                                                                        <?php }else if($td->task_status_id == $task_status_completed_id){ ?>
                                                                                         <li>- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> )</li>
                                                                                            <?php }else{?>
                                                                                           <li >- Due : <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> <?php echo $due_dt;?></a> )</li>
                                                                                              <?php } }else{?>
                                                                                                <li >- <a href="#" data-pk="<?=$td->task_id?>" data-type='date' class="task_due_date_editable" data-format="<?=$dat?>"> No Due Date )</a></li>
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
											
											 <?php if($td->comments){ ?>
                                                            <li><a class="tooltips" data-placement="right" data-original-title="Comments" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_7')" href="javascript:void(0);"><i class="icon-comment-alt prjicn"> </i><sup><?=$td->comments?></sup></a></li>
                                                <?php } ?>
                                                                                                      <?php if($td->files){ ?><li><a class="tooltips" data-placement="right" data-original-title="Task Files" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_6')"  href="javascript:void(0);"><i class=" icon-paperclip prjicn"> </i><sup><?=$td->files?></sup></a></li><?php } ?>
                                                                                                        <?php if($td->steps){ ?> <li><a class="tooltips" data-placement="right" data-original-title="Task Steps" onclick="edit_task(this,'<?php echo $td->task_id;?>','1','task_tab_4')" href="javascript:void(0);"><i class="icon-list-ul prjicn"> </i><sup><?=$td->steps?></sup></a></li>
                                                                                        <?php }?>
												
							 
                                                                                </ul>
                                                                                <input type="hidden" id="task_data_<?php echo $td->task_id;?>" value="<?php echo htmlspecialchars(json_encode($td)); ?>" />
                                                                            </div>
								<?php }	} } }?>
                                                    <div> </div>
                                                    <div class="unsorttd" > </div>
                                                    <!-- main task settings end here -->

                                                   
                                                    
                                                    <div id="chngNm_<?php echo $s->section_id;?>" class="modal model-size pro-change fade" tabindex="-1" >
                                                        <div class="portlet">
                                                            <div class="portlet-body  form flip-scroll">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close cmt_close" data-dismiss="modal" aria-hidden="true"></button>
                                                                    <h3>Section Name</h3>
                                                                </div>
                                                                <div>
                                                                    <div class="addcomment-block">
                                                                        <div class="row">
                                                                            <div class="col-md-12 ">
                                                                                <div class="form-group">
                                                                                    <label class="control-label" > <strong> Section Name : </strong><span class="required">*</span></label>
                                                                                    <div  class="controls">
                                                                                        <input type="text" class="m-wrap" id="section_name_<?php echo $s->section_id;?>" name="section_name_<?php echo $s->section_id;?>" value="<?php echo htmlspecialchars($s->section_name, ENT_QUOTES);?>" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="pull-right">
                                                                                    <input type="hidden" name="section_id" id="section_id" value="<?php echo $s->section_id;?>" />
                                                                                    <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                                                    <input type="hidden" name="tab" id="tab" value="tab_1" />
                                                                                    <button type="button" id="section_submit_<?php echo $s->section_id;?>" name="section_submit" class="btn blue txtbold"> Submit </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <?php //if($is_owner!='0'){ ?>
                                                    <div  class="margin-top-10 add_new_task_div subsectionbtn_cls unsorttd">
                                                        <button onclick="my_custom_task_edit('<?php echo $project_id;?>','<?php echo strtotime(date($default_format));?>','<?php echo $s->section_id;?>','0','<?php echo rawurlencode($s->section_name);?>');datapass('<?php echo $s->section_id;?>','0','<?php echo rawurlencode($s->section_name);?>');" href="javascript:void(0)" type="button" name="task"  class="btn-new green unsorttd" id="pro_button_<?php echo $s->section_id;?>" style="min-width: 0px !important;">Add Task</button>
                                                        </div>
                                                    <?php //} ?>
                                </div>
		</div>
	</div>
	<script type="text/javascript">

                                $(document).ready(function(){

                                $('#chngNm_'+<?php echo  $s->section_id;?>).on( 'keypress', function( e ) {
                                if( e.keyCode === 13 ) {
                                    e.preventDefault();
                                    $("#section_submit_"+<?php echo  $s->section_id;?>).trigger('click');
                                }
                            });

                                $('#section_submit_'+<?php echo $s->section_id;?>).click(function(){

                                        var filter = $('#typefilter1 li.active').attr('id');
                                        var id = $("#select_task").val();

                                if($('#section_name_'+<?php echo $s->section_id;?>).val()!=''){

                                        $('#dvLoading').fadeIn('slow');
                                                $.ajax({
                                                                type: 'POST',
                                                                url : "<?php echo site_url('project/update_sectionName') ?>",
                                                                data:{section_name:$("#section_name_"+<?php echo $s->section_id;?>).val(),section_id:<?php echo $s->section_id;?>,project_id:<?php echo $s->project_id;?>,type:'section',user_id:id,filter:filter},

                                                                success : function(data) {
                                                                        if(data!=''){

                                                                        $('#chngNm_'+<?php echo $s->section_id;?>).modal('hide');
                                                                        $("#ch_sec_"+<?php echo $s->section_id;?>).html(data);
                                                                        $('#dvLoading').fadeOut('slow');
                                                                        return false;

                                                                        }else{
                                                                                $('#dvLoading').fadeOut('slow');
                                                                                return false;

                                                                        }
                                                                },

                                                        });
                                                }else{
                                                        alertify.alert('Field can not be empty')
                                                }

                                        });
$('.changesecname').editable({
                    url: SIDE_URL + "project/update_sectionName",
                    params:function(params){
                          var data = {};
                                   data['section_id']=params.pk;
                                  data['project_id']=$(this).attr("sec_prj_id");
                                  data['section_name']=params.value;
                                  data['type']=$(this).attr("sec_type");
                                  data['user_id']=$("#select_task_assign").val();
                                  data['filter']= $("#select_task_status").val();
                          return data;
                 
                    },
                    type: "text",
                    mode: "inline",
                    inputclass:"changesectionname",
                    showbuttons: !0,
                    validate: function(e) {
                    },
                    success: function() {}  
                }); 
                                        

                                });
</script>									
                <?php  } }  ?>
		<div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6" style="padding-top: 3px;">
                            <div class="form-group">
                                <div class="controls">
                                     <input type="text" name="project_section" id="project_section" class="col-md-12 m-wrap" placeholder="Enter section name" <?php if($project_id == '0'){echo 'disabled="disabled"';}?>/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pull-left" style="padding-top:5px">
                                <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                 <button type="button" class="btn blue btn-new unsorttd" id="name_section" name="name_section">Add Section</button>
                        </div>
                    </div>
                </div>
</div>
