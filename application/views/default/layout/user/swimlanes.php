<?php $theme_url = base_url().getThemeName(); 
      $total_swimlane = count_total_swimlanes();
?>
<script type="text/javascript" src="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/dataTable.min.js?Ver=<?php echo VERSION;?>"></script>
<link href="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/datatable.rowreorder.css?Ver=<?php echo VERSION;?>" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/datatable.rowreorder.min.js?Ver=<?php echo VERSION;?>"></script>
<style>
    #swimlanes .btn{
    border-radius: 0px !important;
}
</style>
<script>
        $(document).ready(function() { 
		FormEditable.init();
              var table =  $("#swimlane_editable").dataTable({
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
			url:SIDE_URL+'user/set_swim_seq',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	});
</script>
    <p class="alert alert-info" ><strong>Swimlanes</strong> are ways to group visually all tasks and activities on your Kanban board. Typically swimlanes may represent general work streams such as personal tasks, work-related tasks etc.
        <br><br><strong>Swimlanes</strong> are set at the individual level only while the columns running vertically on your Kanban board are set at the company level defining the "stage" that a task is up to. The horizontal columns - called swimlanes - are defined at the individual level and do not form part of reporting for the overall organization.
    </p>
    <div class="customtable  form-horizontal">
	<div>
            <table class="table table-striped table-hover table-condensed flip-content" id="swimlane_editable">
		<thead class="flip-content">
		    <tr>
                        <th></th>
			<th>Name</th>
			<th>Action</th>
                        <th>Status</th>
		    </tr>
		</thead>
		<tbody id="swimlanes">
		    <?php  $i = 0;
				if($result){
				    foreach($result as $row){ ?>
                                        <script type="text/javascript">
					    $(document).ready(function(){
						$('#sname_<?php echo $row->swimlanes_id; ?>').editable({
							url: '<?php echo site_url("user/update_swimlane_name");?>',
							type: 'post',
							pk: 1,
							mode: 'inline',
							showbuttons: true,
							validate: function (value) {
							    if ($.trim(value) == ''){ return 'This field is required' };
							        var remote = $.ajax({
							              		url: "<?php echo site_url("user/chk_swimlaneName_exists");?>",
										type: "post",
										async : false,
										data: {
											name: $.trim(value),
											user_id: function(){ return $("#user_id").val(); },
											swimlanes_id : '<?php echo $row->swimlanes_id; ?>'
										},
										success : function(responseData){
											return responseData;
										}
							              	});
							              	if(remote.responseText == "false") return 'There is an existing record with this swimlane name.';
							},
							success : function(DivisionData){
							            	
							}
						});
                                                $("#swimlane_status_<?php echo $row->swimlanes_id; ?>").bootstrapToggle();
						$('#swimlane_status_<?php echo $row->swimlanes_id; ?>').bootstrapToggle().on('change', function () {
                                                    var t = $(this).prop('checked')? "active" : "deactive";
                                                    change_swimlane_status('<?php echo $row->swimlanes_id; ?>',t);
                                                });
					    });
					</script>
                                        <tr id="swimlane_<?php echo $row->swimlanes_id; ?>">
					
                                        <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i+1; ?></span></td>
					<td width="50%">
					    <a href="javascript:void(0)" class="txt-style" id="sname_<?php echo $row->swimlanes_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $row->swimlanes_name;?>"><?php echo $row->swimlanes_name;?></a> 
					</td>
					<td width="22%">
					    <?php 
						if(!in_array($row->swimlanes_id,$task_swimlane_ids)){ ?>
                                            <a onclick="delete_swimlane('<?php echo $row->swimlanes_id;?>');" href="javascript:void(0);" <?php if($row->is_default == '1'){ echo "style='display:none'"; } ?> id="delete_icon_show_<?php echo $row->swimlanes_id; ?>" > <i class="icon-trash stngicn new_swimlane_css"></i> </a>	
                                            <?php } ?>
					</td>
                                        <?php if($row->is_default == '1'){ ?>
                                        <td>
                                            <input type="checkbox" id="swimlane_status" style="display:none">
                                        </td>
                                        <?php }else{ ?>
                                        <td>
                                            <input type="checkbox" id="swimlane_status_<?php echo $row->swimlanes_id; ?>" <?php  if( $row->swimlane_status == 'active'){ echo "checked='checked'"; }?> data-toggle="toggle" data-style="android" data-width="80"  data-offstyle="danger" data-on="Active" data-off="Inactive" >
                                        </td>
                                        <?php } ?>
                                        </tr>
					<?php $i++; }
				} ?>
					
		</tbody>
	    </table>
            <div>
                <input type="hidden" id="total_swimlanes" name="total_swimlanes" value="<?php echo $total_swimlane; ?>"/>
                <input type="text" class="large m-wrap valid" name="swimlanes_name" id="swimlanes_name">
		<a href="javascript:void(0)" class="btn btn-common-blue sm" id="save_swimlanes">Add</a>
            </div>
	</div>
        <div class="form-group margin_top10">
            <label class="control-label pull-left margin_right10">Default Swimlane:</label>
		<div class="controls relative-position">
                    <input type="hidden" name="hidden_default_swimlane" id="hidden_default_swimlane" value="<?php echo $user_default_swimlane->swimlanes_id; ?>"/>
                    <select class="large m-wrap default_swimlane " id="default_swimlane" name="default_swimlane" tabindex="1">
                        <?php if(isset($result) && $result!=''){
                                foreach($result as $d){ ?>
                                    <option value="<?php echo $d->swimlanes_id;?>" <?php if($d->is_default == '1'){ echo "selected='selected'"; }?> <?php if($d->swimlane_status == 'deactive'){ echo "style='display:none'"; } ?> ><?php echo $d->swimlanes_name;?></option>
                        <?php } } ?>
                    </select>
		</div>
        </div>
    </div>
    
