<div class="form-group">
    <label class="control-label"><b>First day of the week</b> <span class="required">*</span> - <span class="label_des">The first day of the week will show as the first day in your weekly calendar</span></label>
    <div class="controls relative-position">
        <select class="large m-wrap radius-b" name="fisrt_day_of_week" id="fisrt_day_of_week" tabindex="1">
            <option value="Monday" <?php if($fisrt_day_of_week == 'Monday'){ echo "selected='selected'"; } ?>>Monday</option>
	    <option value="Tuesday" <?php if($fisrt_day_of_week == 'Tuesday'){ echo "selected='selected'"; } ?>>Tuesday</option>
	    <option value="Wednesday" <?php if($fisrt_day_of_week == 'Wednesday'){ echo "selected='selected'"; } ?>>Wednesday</option>
            <option value="Thursday" <?php if($fisrt_day_of_week == 'Thursday'){ echo "selected='selected'"; } ?>>Thursday</option>
            <option value="Friday" <?php if($fisrt_day_of_week == 'Friday'){ echo "selected='selected'"; } ?>>Friday</option>
            <option value="Saturday" <?php if($fisrt_day_of_week == 'Saturday'){ echo "selected='selected'"; } ?>>Saturday</option>
            <option value="Sunday" <?php if($fisrt_day_of_week == 'Sunday'){ echo "selected='selected'"; } ?>>Sunday</option>
	</select>
    </div>
    <input type="hidden" id="old_first_day_of_week" value="<?php echo $fisrt_day_of_week; ?>" />
</div>
							
<div class="form-group">
    <p class="alert alert-info" >Select the days your employees normally work and how many hours are available each day. Each user will have the ability to adjust their default working hours.
        <br><br>Examples :
        <br><br>50 minutes ==> enter 50
        <br>1h30 ==> 130 
        <br>8 hours ==> 800
    </p>
    <label class="control-label"><b>Default Working Day</b> </label>
    <div class="controls">
        <div  class="row setworkchk" >
            <div class="col-md-12">
                <div class="col-md-2"></div>   
                <div class="col-md-2">
                    <label class="checkbox line company_top">
                        <input class="setting-cal-chkbox newcheckbox_task" type="checkbox" name="MON_closed" id="MON_closed" value="1" <?php if($MON_closed){ echo 'checked="checked"'; } ?> />Monday
                    </label>
                </div>
                <div class="col-md-8  relative-position" >
                    <input type="text" placeholder=" " name="MON_hours" id="MON_hours" value="<?php echo $MON_hours;?>" class="m-wrap small time-text" disabled="disabled" />
                    <input type="hidden" name="MON_hours_min" id="MON_hours_min" value="<?php echo $MON_hours_min;?>" disabled="disabled" />
                    <span class="hravailable"> hours available </span>
                    <span class="input-load" id="MON_hours_loading"></span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div  class="row setworkchk" >
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <label class="checkbox line company_top">
                        <input class="setting-cal-chkbox newcheckbox_task" type="checkbox" name="TUE_closed" id="TUE_closed" value="1" <?php if($TUE_closed){ echo 'checked="checked"'; } ?> />Tuesday
                    </label>
                </div>
                <div class="col-md-8  relative-position" >
                    <input type="text" placeholder=" " name="TUE_hours" id="TUE_hours" value="<?php echo $TUE_hours;?>" class="m-wrap small time-text" disabled="disabled" />
                    <input type="hidden" name="TUE_hours_min" id="TUE_hours_min" value="<?php echo $TUE_hours_min;?>" disabled="disabled" />
                    <span class="hravailable"> hours available </span>
                    <span class="input-load" id="TUE_hours_loading"></span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
	<div  class="row margin-top-10 setworkchk">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <label class="checkbox line company_top">
                        <input class="setting-cal-chkbox newcheckbox_task" type="checkbox" name="WED_closed" id="WED_closed" value="1" <?php if($WED_closed){ echo 'checked="checked"'; } ?> />Wednesday
                    </label>
                </div>
                <div class="col-md-8  relative-position" >
                    <input type="text" placeholder=" " name="WED_hours" id="WED_hours" value="<?php echo $WED_hours;?>" class="m-wrap small time-text" disabled="disabled" />
                    <input type="hidden" name="WED_hours_min" id="WED_hours_min" value="<?php echo $WED_hours_min;?>" disabled="disabled" />
                    <span class="hravailable"> hours available </span>
                    <span class="input-load" id="WED_hours_loading"></span>
                    <div class="clearfix"></div>
                </div>
            </div>
	</div>
	<div  class="row setworkchk" >
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <label class="checkbox line company_top">
                        <input class="setting-cal-chkbox newcheckbox_task" type="checkbox" name="THU_closed" id="THU_closed" value="1" <?php if($THU_closed){ echo 'checked="checked"'; } ?> />Thursday
                    </label>
                </div>
                <div class="col-md-8  relative-position">
                    <input type="text" placeholder=" " name="THU_hours" id="THU_hours" value="<?php echo $THU_hours;?>" class="m-wrap small time-text" disabled="disabled" />
                    <input type="hidden" name="THU_hours_min" id="THU_hours_min" value="<?php echo $THU_hours_min;?>" disabled="disabled" />
                    <span class="hravailable"> hours available </span>
                    <span class="input-load" id="THU_hours_loading"></span>
                    <div class="clearfix"></div>
                </div>
            </div>
	</div>
	<div  class="row setworkchk">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <label class="checkbox line company_top">
                        <input class="setting-cal-chkbox newcheckbox_task" type="checkbox" name="FRI_closed" id="FRI_closed" value="1" <?php if($FRI_closed){ echo 'checked="checked"'; } ?> />Friday
                    </label>
                </div>
                <div class="col-md-8  relative-position" >
                    <input type="text" placeholder=" "  name="FRI_hours" id="FRI_hours" value="<?php echo $FRI_hours;?>" class="m-wrap small time-text" disabled="disabled" />
                    <input type="hidden" name="FRI_hours_min" id="FRI_hours_min" value="<?php echo $FRI_hours_min;?>" disabled="disabled" />
                    <span class="hravailable"> hours available </span>
                    <span class="input-load" id="FRI_hours_loading"></span>
                    <div class="clearfix"></div>
                </div>
            </div>
	</div>
	<div  class="row setworkchk">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <label class="checkbox line company_top">
                        <input class="setting-cal-chkbox newcheckbox_task" type="checkbox" name="SAT_closed" id="SAT_closed" value="1" <?php if($SAT_closed){ echo 'checked="checked"'; } ?> />Saturday
                    </label>
                </div>
                <div class="col-md-8  relative-position">
                    <input type="text" placeholder=" " name="SAT_hours" id="SAT_hours" value="<?php echo $SAT_hours;?>" class="m-wrap small time-text" disabled="disabled" />
                    <input type="hidden" name="SAT_hours_min" id="SAT_hours_min" value="<?php echo $SAT_hours_min;?>" disabled="disabled" />
                    <span class="hravailable"> hours available </span>
                    <span class="input-load" id="SAT_hours_loading"></span>
                    <div class="clearfix"></div>
                </div>
            </div>
	</div>
	<div  class="row  setworkchk">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <label class="checkbox line company_top">
                        <input class="setting-cal-chkbox newcheckbox_task" type="checkbox" name="SUN_closed" id="SUN_closed" value="1" <?php if($SUN_closed){ echo 'checked="checked"'; } ?> />Sunday
                    </label>
                </div>
                <div class="col-md-8  relative-position">
                    <input type="text" placeholder=" " name="SUN_hours" id="SUN_hours" value="<?php echo $SUN_hours;?>" class="m-wrap small time-text" disabled="disabled" />
                    <input type="hidden" name="SUN_hours_min" id="SUN_hours_min" value="<?php echo $SUN_hours_min;?>" disabled="disabled" />
                    <span class="hravailable"> hours available </span>
                    <span class="input-load" id="SUN_hours_loading"></span>
                    <div class="clearfix"></div>
                </div>
            </div>
	</div>
    </div>
</div>
			