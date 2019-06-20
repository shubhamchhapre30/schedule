<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemename();?>/assets/plugins/jquery-multi-select/css/multi-select-metro.css?Ver=<?php echo VERSION;?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemename();?>/assets/plugins/jquery-tags-input/jquery.tagsinput.css?Ver=<?php echo VERSION;?>" />

<script src="<?php echo base_url().getThemeName();?>/assets/plugins/select2/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemename();?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemename();?>/assets/plugins/jquery-tags-input/jquery.tagsinput.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName();?>/assets/js/multiselect.js?Ver=<?php echo VERSION;?>"></script> 


<div class="form-horizontal">
  	<form name="frm_add_user" id="frm_add_user" >
	 		<div class="row" style="margin-left: -1px;">
				
				<div class="form-group col-md-6">
                                    <div class="row">
					<label class="control-label col-md-4">First Name<span class="required">*</span> </label>
					<div class="controls col-md-8">
						<input type="text" name="first_name" id="first_name" value="" placeholder=" " class="m-wrap large" />
					</div>
                                    </div>   
				</div>
				
				
                                <div class="form-group col-md-6">
                                    <div class="row">
                                            <label class="control-label col-md-4" >Last Name<span class="required">*</span> </label>
					<div class="controls col-md-8">
						<input type="text" name="last_name" id="last_name" value="" placeholder=" " class="m-wrap large" />
					</div>
                                    </div>
				</div>
				
                        </div>	
				<div class="form-group">
                                    <label class="control-label col-md-2" >Email<span class="required">*</span> </label>
					<div class="controls col-md-8">
						<input type="text" name="email" id="email" value="" placeholder=" " class="m-wrap large col-md-3" />
				 	</div>
				</div>
				
				<div class="form-group" id="addUserDivisionDiv">
					<label class="control-label col-md-2">Division </label>
					<div class="controls">
						<!--<input id="tags_division" name="tags_division" type="text" class="m-wra tags medium" value="" />-->
						<input type="hidden" id="tags_division" name="tags_division" class="col-md-10 select2" value="">
				 	</div>
				</div>
				<div class="form-group" id="addUserDepartmentDiv">
					<label class="control-label col-md-2">Departments </label>
					<div class="controls">
						<!--<input id="tags_department" name="tags_department" type="text" class="m-wra tags medium" value="" />-->
						<input type="hidden" id="tags_department" name="tags_department" class="col-md-10 select2" value="">
				 	</div>
				</div>
				<div class="form-group" id="addUserSkillsDiv">
					<label class="control-label col-md-2">Skills </label>
					<div class="controls">
						<!--<input id="tags_skills" name="tags_skills" type="text" class="m-wra tags medium" value="" />-->
						<input type="hidden" id="tags_skills" name="tags_skills" class="col-md-10 select2" value="">
				 	</div>
				</div>
				
				
				
			  	<div class="form-group" id="addUserStaffLevelDiv">
					<label class="control-label col-md-2" >Staff Level </label>
					<div class="controls col-md-8">
						<select class="large m-wrap" name="staff_level" id="staff_level" tabindex="1" style="padding:5px;border-radius:4px;">
							<option value="0">--Select--</option>
							<?php if($staff_levels){
								foreach($staff_levels as $row){
									?>
									<option value="<?php echo $row->staff_level_id;?>" ><?php echo $row->staff_level_title;?></option>
									<?php
								}
							}?>
						</select>
					</div>
				</div>
				
                                <div class="form-group" id="timsheet_show">
					<label class="control-label col-md-2">Timesheet Approver</label>
					<div class="controls relative-position col-md-8">
                                            <div>
                                                <select class="large m-wrap radius-b" name="approver_select" id="approver_select" tabindex="1" >
                                                    
                                                </select>
                                            </div>
					</div>
			        </div>
            
				<div class="form-group">
					<label class="control-label col-md-2" >Time Zone<span class="required">*</span> </label>
					<div class="controls col-md-8">
                                            <select class="large m-wrap radius-b" name="user_time_zone" id="user_time_zone" tabindex="1" >
							<option value="">--Select--</option>
							<?php
								
							 if(isset($timezone) && $timezone!=''){
								foreach($timezone as $t){
									?>
									<option value="<?php echo $t->timezone_name;?>" <?php if(getCompanyTimeZone($company_id) == $t->timezone_name){ echo "selected='selected'";} ?>><?php echo $t->name;?></option>
									<?php
								}
							} ?>
						</select>
					</div>
				</div>
			     
			 <div class="form-group" id="addUserReportsToDiv">
				<label class="control-label col-md-2" >Reports to </label>
				<div class="controls col-md-8">
					<div class="margin-bottom-10">
						<div class="listbox_spn4">
                                                    <label >&nbsp;</label>
                                                    <select multiple="multiple" name="manager_multiselect[]" id="manager_multiselect" class="large m-wrap" size="5" style="padding:5px;border-radius:4px;">
							<?php if($managers){
								foreach($managers as $row){
									?>
									<option value="<?php echo $row->user_id;?>"><?php echo $row->first_name.' '.$row->last_name;?></option>
									<?php
									
									}
							}?>
						</select>
						</div>
						<div class="listbox_spn2">
							<div class="adjbtn-group">
								<a class="btn blue adj-btn" id="manager_multiselect_rightSelected" href="javascript:;"> <i class="stripicon iconrightarro"></i> </a>
								<a class="btn blue adj-btn" id="manager_multiselect_leftSelected" href="javascript:;"> <i class="stripicon iconleftarro"></i> </a>
							</div>
						</div>
						<div class="listbox_spn4">
						<label class="form-group">Selected </label>	
                                                <select multiple="multiple" name="manager_multiselect_to[]" id="manager_multiselect_to" class="large m-wrap margin-bottom-10"  size="5" style="border-radius:4px;padding:5px;">
							
						 </select>
						</div>
						<div class="clearfix"> </div>
					</div>
					 
				</div>
			</div>
		<div class="form-group">
                    <label class="control-label col-md-2">&nbsp;</label>
				<div class="controls col-md-8">
					<span id="addUserIsAdminDiv">
						<label class="checkbox">
						<input type="checkbox" class="newcheckbox_task" name="is_administrator" id="admin_is_administrator" value="1" /> Administrator
						</label>
					</span>
					<span id="addUserIsOwnerDiv">
						<input type="hidden" name="is_administrator" id="owner_is_administrator" value="" />
					</span>
					<label class="checkbox">
					<input type="checkbox" class="newcheckbox_task" name="is_manager" id="is_manager" value="1" /> Manager
					</label>
					
					<label class="checkbox">
					<input type="checkbox" class="newcheckbox_task" name="user_status" id="user_status" value="Active" /> Active
					</label>
				</div>
                                
		</div>	
            <div class="form-group" id="speical_access" style="display:none">
                    <label class="control-label col-md-2">&nbsp;</label>
			<div class="controls col-md-8">
                            <div class="controls" id="add_speical_access">
                                    
                            </div>
                        </div>
            </div>
		<div class="form-group">
			<label class="control-label col-md-2">Default Working Day </label>
			<div class="controls col-md-10">
				<div  class="row margin-top-10 setworkchk">
					<div class="col-md-2 padding-top-7">
						<label class="checkbox line">
							<input type="checkbox" class="newcheckbox_task" name="MON_closed" id="User_MON_closed" value="1" />Monday
						</label>
					</div>
					<div class="col-md-5">
						<input type="text" placeholder="" name="MON_hours" id="User_MON_hours" value="" class="m-wrap small setHourErr user-time-text" disabled="disabled" />
						<input type="hidden" name="MON_hours_min" id="User_MON_hours_min" value="" disabled="disabled" />
						<span class="hravailable"> hours available </span>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div  class="row margin-top-10 setworkchk">
					<div class="col-md-2 padding-top-7">
						<label class="checkbox line" >
							<input type="checkbox" class="newcheckbox_task" name="TUE_closed" id="User_TUE_closed" value="1" />Tuesday
						</label>
					</div>
					<div class="col-md-5">
                                            <input type="text" placeholder=" " name="TUE_hours" id="User_TUE_hours" value="" class="m-wrap small setHourErr user-time-text" disabled="disabled"  />
						<input type="hidden" name="TUE_hours_min" id="User_TUE_hours_min" value="" disabled="disabled" />
						<span class="hravailable"> hours available </span>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div  class="row margin-top-10 setworkchk">
					<div class="col-md-2 padding-top-7">
						<label class="checkbox line" >
							<input type="checkbox" class="newcheckbox_task" name="WED_closed" id="User_WED_closed" value="1" />Wednesday
						</label>
					</div>
					<div class="col-md-5">
                                            <input type="text" placeholder=" " name="WED_hours" id="User_WED_hours" value="" class="m-wrap small setHourErr user-time-text" disabled="disabled" />
						<input type="hidden" name="WED_hours_min" id="User_WED_hours_min" value="" disabled="disabled" />
						<span class="hravailable"> hours available </span>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div  class="row margin-top-10 setworkchk">
					<div class="col-md-2 padding-top-7">
						<label class="checkbox line">
							<input type="checkbox" class="newcheckbox_task" name="THU_closed" id="User_THU_closed" value="1" />Thursday
						</label>
					</div>
					<div class="col-md-5">
                                            <input type="text" placeholder=" " name="THU_hours" id="User_THU_hours" value="" class="m-wrap small setHourErr user-time-text" disabled="disabled" />
						<input type="hidden" name="THU_hours_min" id="User_THU_hours_min" value="" disabled="disabled" />
						<span class="hravailable"> hours available </span>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div  class="row margin-top-10 setworkchk">
					<div class="col-md-2 padding-top-7">
						<label class="checkbox line">
							<input type="checkbox" class="newcheckbox_task" name="FRI_closed" id="User_FRI_closed" value="1" />Friday
						</label>
					</div>
					<div class="col-md-5">
                                            <input type="text" placeholder=" "  name="FRI_hours" id="User_FRI_hours" value="" class="m-wrap small setHourErr user-time-text" disabled="disabled" />
						<input type="hidden" name="FRI_hours_min" id="User_FRI_hours_min" value="" disabled="disabled" />
						<span class="hravailable"> hours available </span>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div  class="row margin-top-10 setworkchk">
					<div class="col-md-2 padding-top-7">
						<label class="checkbox line">
							<input type="checkbox" class="newcheckbox_task" name="SAT_closed" id="User_SAT_closed" value="1" />Saturday
						</label>
					</div>
					<div class="col-md-5">
                                            <input type="text" placeholder=" " name="SAT_hours" id="User_SAT_hours" value="" class="m-wrap small setHourErr user-time-text" disabled="disabled" />
						<input type="hidden" name="SAT_hours_min" id="User_SAT_hours_min" value="" disabled="disabled" />
						<span class="hravailable"> hours available </span>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div  class="row margin-top-10 setworkchk">
					<div class="col-md-2 padding-top-7">
						<label class="checkbox line" >
							<input type="checkbox" class="newcheckbox_task" name="SUN_closed" id="User_SUN_closed" value="1" />Sunday
						</label>
					</div>
					<div class="col-md-5">
                                            <input type="text" placeholder=" " name="SUN_hours" id="User_SUN_hours" value="" class="m-wrap small setHourErr user-time-text" disabled="disabled"/>
						<input type="hidden" name="SUN_hours_min" id="User_SUN_hours_min" value="" disabled="disabled" />
						<span class="hravailable"> hours available </span>
						<div class="clearfix"></div>
					</div>
				</div>
			 	 
			</div>
		</div>
		
		
		  	
            <div id="hideSaveCancel" class="form-control" style="padding: 19px 20px 20px;background-color: #e5e9ec;height:75px;">
				<input type="hidden" name="pre_user_status" id="pre_user_status" value="" />
				
				<input type="hidden" name="is_owner" id="is_owner" value="" />
				<input type="hidden" name="user_id" id="user_id" value="" />
				<button type="submit" id="saveBtnUser" name="saveBtnUser" class="btn blue txtbold"><i class="icon-ok usrstngicn"></i> Save</button>
				<button type="submit" id="addBtnUser" name="addBtnUser" class="btn blue txtbold"><i class="icon-ok usrstngicn"></i> Add</button>
				<button type="button" id="cancelBtnUser" name="cancelBtnUser" class="btn red txtbold" onclick="CompanyUserCancel();"> <i class="icon-remove usrstngicn "></i>Cancel</button>
			</div>
	</form>
</div>

<script>
	$(document).ready(function(){
		$("#tags_division").select2({
			tags: [<?php echo isset($company_division)?$company_division:'';?>],
		});
		
		$("#tags_department").select2({
			tags: [<?php echo isset($company_department)?$company_department:'';?>],
		});
		
		$("#tags_skills").select2({
			tags: [<?php echo isset($company_skills)?$company_skills:'';?>],
		});
	});
</script>
