<?php
    $default_format = $this->config->item('company_default_format');
    $date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
?>


                                <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3>New Timesheet</h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
                                            <div class="portlet-body  form flip-scroll" style="padding:10px;">
                                                <div class="row form-horizontal">
                                                    <form name="timesheet_data" id="timesheet_data" method="POST">
                                                        <div class="form-group col-md-12">
                                                            <div class="col-md-6">
                                                                <label class="control-label" ><strong>From Date</strong><span class="required">*</span></label>
                                                                <div class="controls">
                                                                    <div class="input-append date date-picker dd" data-date="" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
                                                                        <input class="m-wrap m-ctrl-medium col-md-10 "  placeholder="From date" name="timesheet_fromdate" id="timesheet_fromdate" size="16" type="text" value="" /><span class="add-on"><i class="icon-calendar taskppicn" style="width:24px; height: 24px;"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label" ><strong>To Date</strong><span class="required">*</span></label>
                                                                <div class="controls">
                                                                    <div class="input-append date date-picker" data-date="" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
                                                                        <input class="m-wrap m-ctrl-medium col-md-10 "  placeholder="To date" name="timesheet_todate" id="timesheet_todate" size="16" type="text" value="<?php echo date($default_format);?>" /><span class="add-on"><i class="icon-calendar taskppicn" style="width:24px; height: 24px;"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8" id="showuserlist" style="display:none">
                                                                <label class="control-label" ><strong>Users</strong><span class="required">*</span></label>
                                                                <div class="controls">
                                                                    <select class="m-wrap no-margin radius-b" style="width:100%" name="timesheet_to_another" id="timesheet_to_another" tabindex="3" >
                                                                        <option value="">Select user</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group pull-right col-md-12">
                                                            <button type="button" class="btn blue btn-new unsorttd txtbold" onclick="return save_timesheet();">Continue  </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </div>