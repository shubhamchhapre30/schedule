<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemename();?>/assets/plugins/chosen-bootstrap/chosen/chosen.css?Ver=<?php echo VERSION;?>" />
<script src="<?php echo base_url().getThemename();?>/assets/plugins/jquery-migrate-1.2.1.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().getThemename();?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemename();?>/assets/scripts/form-components.js?Ver=<?php echo VERSION;?>"></script>
<style type="text/css">
 
.text-right label{display:inline;}
</style>
<script type="text/javascript">
$(function(){
	
	App.init();
	FormComponents.init();
	
	$('.multiallocation_scroll').slimScroll({
		color: '#17A3E9',
 	    wheelStep: 20,
 	    height : '150px',
 	    showOnHover : true,
 	    overflow : 'initial'
	 });
	
	$('#task_skill_id').multiselect({
		nonSelectedText : 'Select Skills',
		buttonWidth: '349px'
	});
	$("#task_division_id").multiselect({
		nonSelectedText : 'Select Division',
		buttonWidth: '349px'
	});
	$("#task_department_id").multiselect({
		nonSelectedText : 'Select Department',
		buttonWidth: '349px'
	});
	
	$("#task_allocated_user_id").on("change",function(){
		if($(this).val() == 'multiple_people'){
			$("#updated_users").hide();
			$("#updated_users_multiple").show();
			$("input[name='task_allocated_user_id[]']").on("change",function(){
				$("#is_multi_changed").val(1);
			});
		} else {
                        var a=[];
                        setAllocation();
			setDivisionDepartment('allocation');
			setUserSwimlanes('allocation');
                        a[0]= $("#task_allocated_user_id").val();
                        $.ajax({
                            type: "post",
                            url: SIDE_URL + "task/send_allocation_mail",
                            data: {
                                task_allocated_user_id: a,
                                task_id: $("#task_id").val(),
                                
                            },
                            async: 1,
                            success: function(data) { 

                            }
                        });
                        
		}
	});
});
function multiple_people_html(project_id){
	$.ajax({
		type : 'post',
		url : SIDE_URL+'task/multiple_people',
		data : {task_id:$("#task_id").val(),project_id:project_id},
		success:function(data){
			$("#updated_users").hide();
			$(".chk-container").html(data);
			$("#updated_users_multiple").show();
			$("input[name='task_allocated_user_id[]']").on("change",function(){
				$("#is_multi_changed").val(1);
			});
		}
	});
}
</script>

<div class="portlet">
  <div class="portlet-body form">
    
    <div class="horizontal-form">
      
        <!-- ***************** -->
        <div class="popuphight"> 
          <!-- ***************** -->
          <form name="frm_add_allocation" id="frm_add_allocation" action="">
          <div class="no_task_msg" style="display: none;">
            <div class='task_not_found_msg'><span>Please save the task before adding this.</span></div>
          </div>
          <div class="normal_div">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="task_division_id">Division</label>
                  <div class="controls relative-position" id="updatedDivision">
                  	<?php if(isset($divisions) && $divisions !=""){ ?>
                      	<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();">
                  		<?php $count = count((array)$divisions); 
						if($count == "1" && $divisions[0]->devision_title == "General"){ ?>
							<option value="<?php echo $divisions[0]->division_id;?>" selected="selected"> <?php echo $divisions[0]->devision_title; ?> </option>
							
						<?php } else {
							foreach($divisions as $div){
							 ?>
							<option value="<?php echo $div->division_id;?>" > <?php echo $div->devision_title; ?> </option>
						<?php } 
							} ?>
						</select>
                  	<?php } else { ?>
                  		<select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" disabled="disabled" multiple name="task_division_id[]" id="task_division_id" tabindex="1" onchange="get_department_by_division();">
                  		</select>
                     <?php } ?>
                    <!--<span class="input-load" id="task_division_id_loading"></span>-->
                  </div>
                </div>
              </div>
              <div  class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="task_department_id">Department</label>
                  <div class="controls relative-position" id="filtered_dep">
                    <select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" multiple name="task_department_id[]" id="task_department_id" tabindex="1">
                      <?php 
												if(isset($departments) && $departments != ''){
													foreach($departments as $dept){ 
														?>
                      <option value="<?php echo $dept->department_id;?>" > <?php echo $dept->department_title; ?> </option>
                      <?php
													}
												} 
											?>
                    </select>
                    <!--<span class="input-load" id="task_department_id_loading"></span>-->
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="task_project_id">Project</label>
                  <div class="controls relative-position" id="task_project_div">
                    
                      <?php if(isset($user_projects) && $user_projects != ''){ ?>
                      	<select class="co-md-11 m-wrap no-margin task-input allocation-change radius-b" name="task_project_id" id="task_project_id" tabindex="1" onchange="set_project_section();" >
                      		<option value="0">Please select</option>
							<?php foreach($user_projects as $project){ ?>
							  <option value="<?php echo $project->project_id;?>" > <?php echo $project->project_title; ?> </option>
							<?php } ?>
						</select>
					<?php } else { ?>
						<select class="col-md-11 m-wrap no-margin" disabled="disabled"  name="task_project_id" id="task_project_id" tabindex="1"  >
                      		<option value="0" >Please select</option>
                      	</select>
                      	<input type="hidden" name="task_project_id" id="task_project_id" value="0" />
					<?php } ?>
					<!--<span class="input-load" id="task_project_id_loading"></span>-->
                  </div>
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="form-group">
                  <label class="control-label" for="section_id">Project Section</label>
                  <div class="controls relative-position" id="section_div">
                    <select class="col-md-11 m-wrap no-margin task-input allocation-change radius-b" name="section_id" id="section_id" tabindex="1" >
                      <option value="0">Please select</option>
                    </select>
                    <!--<span class="input-load" id="section_id_loading"></span>-->
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="task_skill_id">Skill</label>
                  <div class="controls relative-position">
                    <select class="onsub col-md-11 m-wrap no-margin task_multiselect task-input" name="task_skill_id[]" id="task_skill_id" multiple tabindex="1" >
                      <?php if(isset($skills) && $skills!= ''){
												foreach($skills as $skil){
													?>
                      <option value="<?php echo $skil->skill_id;?>" > <?php echo $skil->skill_title; ?> </option>
                      <?php
												}
											}?>
                    </select>
                    <!--<span class="input-load" id="task_skill_id_loading"></span>-->
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="task_staff_level_id">Staff Level</label>
                  <div class="controls relative-position">
                  	
                  	<?php if(isset($staff_levels) && $staff_levels!=''){ ?>
						<select class="col-md-11 m-wrap no-margin task-input allocation-change radius-b" name="task_staff_level_id" id="task_staff_level_id" tabindex="1" >
							<option value="0">Please Select</option>
						<?php foreach($staff_levels as $level){
							?>
							<option value="<?php echo $level->staff_level_id;?>" > <?php echo $level->staff_level_title; ?> </option>
							<?php
						} ?>
						</select>
					<?php } else {
						if($this->session->userdata("is_administrator")){ ?>
							<div class="input-icon right">
								<i onclick="window.open('<?php echo site_url("settings/index#company_setting_tab_4");?>','_blank');"  class="stripicon help"></i>
								<input class="m-wrap col-md-11" disabled="disabled" name="task_staff_level_id" value="Add staff level" type="text" placeholder="Add new staff-levels" />
							</div>
					<?php } else { ?>
						<select class="col-md-11 m-wrap no-margin task-input" disabled="disabled" name="task_staff_level_id" id="task_staff_level_id" tabindex="1" onchange="setSubCategory();">
							<option value="0" disabled="disabled">Please select</option>
						</select>
					<?php } ?>
					<input type="hidden" name="task_staff_level_id" id="task_staff_level_id" value="0" />
					<?php } ?>
                  	<!--<span class="input-load" id="task_staff_level_id_loading"></span>-->
                  </div>
                </div>
              </div>
              
            </div>
            
            <div class="row">
              <div class="col-md-6 ">
                <div class="form-group">
                  <label class="control-label" for="task_allocated_user_id">Task Allocated to </label>
                  <div class="controls relative-position" id="updated_users">
                      <select class=" m-wrap no-margin col-md-11 chosen " name="task_allocated_user_id" id="task_allocated_user_id" tabindex="1" style="font-weight: normal;">
                      
                      <?php $x=0;
						if(isset($users) && $users != ''){
							foreach($users as $u){$x++;
								?>
                      <option value="<?php echo $u->user_id;?>" <?php  if($u->user_id == get_authenticateUserID()){ echo 'selected="selected"'; } ?> > <?php echo $u->first_name.' '.$u->last_name; ?> </option>
                      <?php
								}
							} 
						?>
						<?php if($x>1){ ?> 
						<option value="multiple_people" id="multiple_people_id">Multiple People...</option>
						<?php }?>
                    </select>
                    <!--<span class="input-load" id="task_allocated_user_id_loading"></span>-->
                  </div>
                  <div class="controls relative-position" id="updated_users_multiple">
                  	<div class="chk-wrapper">
						<div class="text-right">
							<label id="selecctall">All</label>
							<span>|</span>
						    <label id="none">None</label>
						</div>
						<div class="multiallocation_scroll">
							<ul class="chk-container">
								<?php if(isset($users) && $users!=0){
									foreach($users as $user){
										if($user->user_id != get_authenticateUserID()){
										?>
										<li><input class="checkbox1" type="checkbox" name="task_allocated_user_id[]" value="<?php echo $user->user_id;?>"><?php echo $user->first_name." ".$user->last_name;?></li>
										<?php
										}
									}
								} ?>
							</ul>
						</div>
					</div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 ">
                <div class="form-group">
                  <label class="control-label" for="task_swimlane_id">User Swimlane </label>
                  <div class="controls relative-position" id="updated_user_swimlanes">
                    
                  </div>
                </div>
              </div>
            </div>
              
            <input type="hidden" name="task_id" id="allocation_task_id" value="" />
<!--            //<input type="hidden" name="customerId" id="allocated_customer_id" value=""/>-->
          </div>
                   
          <?php if($this->session->userdata('customer_module_activation')=='1') {?>     
                    <div class="row">
                        <div class="col-md-6" >
			    <div class="form-group paddding-5">
				<label class="control-label">Customer</label> 
				<div class="controls relative-position">
                                    <?php if(isset($customers) && $customers != ''){ ?>
                                                <select class="m-wrap no-margin col-md-11   chosen task-input" name="customer_id" id="customer_id" tabindex="5">
                                                    <option value="0"  >Please select</option>
                                                        <?php foreach($customers as $row){ ?>
                                                    <option value="<?php echo $row->customer_id;?>" > <?php echo $row->customer_name; ?> </option>
                                                        <?php } ?>
                                                </select>
                                    <?php } else { ?>
                                                <select class="m-wrap no-margin col-md-11  chosen" disabled="disabled" name="customer_id" tabindex="5">
                                                        <option value="0" disabled="disabled">Please select</option>
                                                </select>
                                    <?php } ?>
				</div>
			    </div>
			</div>
                  </div>
                <?php }?>
              <div class="row">
                        <div class="col-md-6" >
			    <div class="form-group paddding-5">
				<div class="controls relative-position">
                                    <button type="button" class="btn green" id="add_to_watch"  > Add to Watch List</button>
                                </div>
                            </div>
                        </div>
              </div>
               </form>
        </div>
        
        
    </div>
  </div>
</div>
