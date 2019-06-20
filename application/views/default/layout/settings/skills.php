<script>

	$(document).ready(function() { 
            var table =  $("#skill_table").dataTable({
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
			url:SIDE_URL+'settings/set_company_skills_seq',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	});
</script>
		<div class="form-group">
                    <p class="alert alert-info" >Skills can be allocated to users and allow you to perform some analysis on the alignment between your staff skills and the tasks performed. </p>
                    <label class="control-label" ><b>Skills</b> </label>
                    <div class="controls form-group">
                        <div class="customtable form-horizontal">
			<table class="table table-striped table-hover table-condensed flip-content" id="skill_table">
                            <thead class="flip-content">
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Action</th>
				</tr>
                            </thead>
                            <tbody id="company_skills">
                                <?php $i = 1;
                                      if(isset($skills) && $skills!=''){
                                        foreach($skills as $skill){ ?>
                                            <script type="text/javascript">
						$(document).ready(function(){
                                                    $('#skillName_<?php echo $skill->skill_id; ?>').editable({
                                									url: '<?php echo site_url("settings/update_skill_name");?>',
													type: 'post',
													pk: 1,
													mode: 'inline',
													showbuttons: true,
													validate: function (value) {
                                                                                                            if ($.trim(value) == ''){ return 'This field is required' };
                                                                                                                var remote = $.ajax({
														url: "<?php echo site_url("settings/chk_skillName_exists");?>",
                                                                                                                type: "post",
														async : false,
														data: {
                                                                                                                    name: $.trim(value),
                                                                                                                    company_id: function(){ return $("#company_id").val(); },
                                                                                                                    skill_id : '<?php echo $skill->skill_id; ?>'
														},
														success : function(responseData){
                                                                                                                    return responseData;
														}
                                                                                                            });
													if(remote.responseText == "1") return 'There is an existing record with this skill name.';
													},
													success : function(DivisionData){
													}
											});
                                                                                        $("#skill_status_<?php echo $skill->skill_id; ?>").bootstrapToggle();
											$('#skill_status_<?php echo $skill->skill_id; ?>').bootstrapToggle().on('change', function (e, data) {
                                                                                            var t=$(this).prop('checked')?1:0;
                                                                                            changeSkillStatus('<?php echo $skill->skill_id; ?>',t);
											});
									});
						</script>
                                            <tr id="skill_<?php echo $skill->skill_id; ?>">
                                                <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i; ?></span></td>								
						<td width="400px">
                                                    <a href="javascript:void(0)" class="txt-style" id="skillName_<?php echo $skill->skill_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $skill->skill_title;?>"><?php echo $skill->skill_title;?></a>
						</td>
						<td>
                                                    <div >
                                                        <input type="checkbox" name="skill_status" <?php if($skill->skill_status == "Active"){ echo "checked='checked'"; } ?> value="<?php echo $skill->skill_status;?>" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  id="skill_status_<?php echo $skill->skill_id; ?>" />
                                                        <a onclick="delete_skill('<?php echo $skill->skill_id; ?>');" id="delete_skill_<?php echo $skill->skill_id; ?>" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i> </a>
                                                    </div>
						</td>
                                            </tr>
				<?php $i++; } } ?>
                            </tbody>
			</table>
                        </div>
                    </div>
                    <div>
                        <input type="text" class="large m-wrap valid" name="skill_title" id="skill_title">
                        <a href="javascript:void(0)" class="btn blue txtbold sm" id="save_skill">Add</a>
                    </div>
		</div>
					
