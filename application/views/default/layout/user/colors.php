<?php $theme_url = base_url().getThemeName(); ?>
<script src="<?php echo base_url().getThemename();?>/assets/plugins/bootstrap-switch/dist/js/bootstrap-switch.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script type="text/javascript" src="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/dataTable.min.js?Ver=<?php echo VERSION;?>"></script>
<link href="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/datatable.rowreorder.css?Ver=<?php echo VERSION;?>" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/datatable.rowreorder.min.js?Ver=<?php echo VERSION;?>"></script>
<script>
        $(document).ready(function() { 
		FormEditable.init();
                var table =  $("#color_editdrag").dataTable({
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
			url:SIDE_URL+'user/set_colors_seq',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	});
</script>
        <p class="alert alert-info" ><strong>Colours</strong> are user specific and allow you to identify visually your tasks on your Kanban board or in the calendar. You can change the name of each colour.
            <br><br>When you are viewing your kanban board or your calendar, right-click on a task to allocate a colour.
        </p>
	<div class="customtable form-horizontal">
            <div>
		<table class="table table-striped table-hover table-condensed flip-content" id="color_editdrag">
                    <thead class="flip-content">
			<tr>
                            <th></th>
			    <th>Colour</th>
                            <th>Name</th>
                            <th>Active</th>
			</tr>
		    </thead>
		    <tbody>
                        <?php
				$i = 0;
				if($colors){
				    foreach($colors as $row){ ?>
                                        <script type="text/javascript">
								$(document).ready(function(){
									$('#name_<?php echo $row->user_color_id; ?>').editable({
                                                                                url: '<?php echo site_url("user/update_color_name");?>',
                                                                                type: 'post',
                                                                                pk: 1,
                                                                                mode: 'inline',
                                                                                showbuttons: true,
                                                                                validate: function (value) {
                                                                                    var pattern = /[^a-zA-Z0-9 ]/;
                                                                                    if( pattern.test(value) ) {
                                                                                        return 'Colour name must be alphanumerical';
                                                                                    }
                                                                                    if ($.trim(value) == ''){ return 'This field is required' };
                                                                                    var remote = $.ajax({
                                                                                            url: "<?php echo site_url("user/chk_colorName_exists");?>",
                                                                                                            type: "post",
                                                                                                            async : false,
                                                                                                            data: {
                                                                                                                    name: $.trim(value),
                                                                                                                    user_id: function(){ return $("#user_id").val(); },
                                                                                                                    color_id : '<?php echo $row->user_color_id; ?>'
                                                                                                            },
                                                                                                            success : function(responseData){
                                                                                                                    return responseData;
                                                                                                            }
                                                                                    });
                                                                                    if(remote.responseText == "false") return 'There is an existing record with this colour name.';
                                                                                },
                                                                                success : function(DivisionData){

                                                                                }
							            
							        });
                                                                $("#status_<?php echo $row->user_color_id; ?>").bootstrapToggle();
							        $('#status_<?php echo $row->user_color_id; ?>').bootstrapToggle().on('change', function () {
                                                                        var t=$(this).prop('checked')? true : false ;
							        	changeColorStatus('<?php echo $row->user_color_id; ?>',t);
									}); 
								});
						</script>
					<tr id="color_<?php echo $row->user_color_id;?>" >
                                            <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i+1; ?></span></td>
                                            <td width="22%"><span class="green-color" style="background-color: <?php echo $row->color_code;?>; border:1px solid <?php echo $row->outside_color_code;?>; color : <?php echo $row->outside_color_code;?>;"><?php echo $row->color_name;?></span> </td>
                                            <td width="40%">
                                                <a href="javascript:void(0)" class="txt-style" id="name_<?php echo $row->user_color_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $row->name;?>"><?php echo $row->name;?></a>
					    </td>
                                            <td> 
                                                <input type="checkbox" name="status" <?php if($row->status == "Active"){ echo "checked='checked'"; } ?> value="<?php echo $row->status;?>" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"   id="status_<?php echo $row->user_color_id; ?>"/>
                                            </td>
                                        </tr> 
                                    <?php $i++;
					}	
				}?>
			</tbody>
		  </table>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Default Colour:</label>
		<div class="controls relative-position">
                    <select class="large m-wrap color-select" id="default_color" name="default_color" tabindex="1" style="border-radius:4px;padding-left: 2px;">
                        <option value="0">--Select--</option>
                            <?php if(isset($active_colors) && $active_colors!=''){
                                    foreach($active_colors as $d){ ?>
                                        <option value="<?php echo $d->user_color_id;?>" <?php if($default_color == $d->user_color_id){ echo "selected='selected'";} ?>><?php echo $d->name;?></option>
                            <?php } } ?>
                    </select>
		</div>
            </div>
	</div>
	  
    
