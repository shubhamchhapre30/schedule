<script>

	$(document).ready(function() { 
            var table =  $("#staff_level_table").dataTable({
                    paging: !1,
                    bFilter: !1,
                    searching: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    },
                  rowReorder:true
                });
                table.on( 'row-reorder.dt', function ( e, diff, edit ) { 
                    var new_position = edit.values;
                    $.ajax({
			url:SIDE_URL+'settings/set_company_staff_levels_seq',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	});
</script>
			<div class="form-group">
                          	<p class="alert alert-info" >Staff levels are only used for reporting purposes. A staff level can be allocated to a task and to a user. It allows you to perform some analysis on the type of work performed by specific task levels. </p>
                                <label class="control-label"><b>Staff Levels</b> </label>
                                <div class="form-group">
                                    <div class="customtable form-horizontal">
                                        <table class="table table-striped table-hover table-condensed flip-content" id="staff_level_table">
                                            <thead class="flip-content">
                                                <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="company_staffLevels">
                                                <?php $i = 1;
                                                      if(isset($staffLevels) && $staffLevels!=''){
                                                        foreach($staffLevels as $level){ ?>
                                                            <script type="text/javascript">
															$(document).ready(function(){
																$('#staffLevelName_<?php echo $level->staff_level_id; ?>').editable({
														            url: '<?php echo site_url("settings/update_stafflevel_name");?>',
														            type: 'post',
														            pk: 1,
														            mode: 'inline',
														            showbuttons: true,
														            validate: function (value) {
														            	
														              	if ($.trim(value) == ''){ return 'This field is required'};
														              	var remote = $.ajax({
														              		url: "<?php echo site_url("settings/chk_staffLevels_exists");?>",
																			type: "post",
																			async : false,
																			data: {
																				name: $.trim(value),
																				company_id: function(){ return $("#company_id").val(); },
																				staff_level_id : '<?php echo $level->staff_level_id; ?>'
																			},
																			success : function(responseData){
																				return responseData;
																			}
														              	});
														              	if(remote.responseText == "1") return 'There is an existing record with this staff-level name.';
														            },
														            success : function(DivisionData){
														            	
														            }
														            
														        });
                                                                                                                        $("#staffLevel_status_<?php echo $level->staff_level_id; ?>").bootstrapToggle();
														        $('#staffLevel_status_<?php echo $level->staff_level_id; ?>').bootstrapToggle().on('change', function (e, data) {
                                                                                                                            var t=$(this).prop('checked')?1:0;
                                                                                                                            changeStaffLevelsStatus('<?php echo $level->staff_level_id; ?>',t);
															});
															});
														</script>
                                                        <tr id="staffLevel_<?php echo $level->staff_level_id; ?>">
                                                            <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i; ?></span></td>								
                                                            <td width="400px">
                                                                <a href="javascript:void(0)" class="txt-style" id="staffLevelName_<?php echo $level->staff_level_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $level->staff_level_title;?>"><?php echo $level->staff_level_title;?></a>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <input type="checkbox" name="staff_level_status" <?php if($level->staff_level_status == "Active"){ echo "checked='checked'"; } ?> value="<?php echo $level->staff_level_status;?>" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" id="staffLevel_status_<?php echo $level->staff_level_id; ?>"/>
                                                                    <a onclick="delete_staffLevel('<?php echo $level->staff_level_id; ?>');" id="delete_staffLevel_<?php echo $level->staff_level_id; ?>" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i> </a>
                                                                </div>
                                                            </td>
							</tr>
						<?php $i++; } } ?>
                                            </tbody>
					</table>
                                    </div>
                                </div>
                                <div>											
                                    <input type="text" class="large m-wrap valid" name="staff_level_title" id="staff_level_title">
                                    <a href="javascript:void(0)" class="btn blue txtbold sm" id="save_staffLevel">Add</a>
                                </div>
			</div>

