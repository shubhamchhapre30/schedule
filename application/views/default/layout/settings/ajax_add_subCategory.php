<?php $theme_url = base_url().getThemeName(); ?>

<div class="customtable">
    <table class="table table-striped table-hover table-condensed flip-content" id="sub_category_table">
	<thead class="flip-content">
	 	<tr>
                        <th></th>
			<th>Name</th>
                        <?php if($this->session->userdata('pricing_module_status')=='1'){?>
                        <th>Chargeable</th>
                        <?php } ?>
			<th>Action</th>
	  	</tr>
	</thead>
	<tbody id="company_subTaskCategory">
		<?php $i=1;
                            if(isset($subTaskCategory) && $subTaskCategory!=''){
				foreach($subTaskCategory as $subCategory){ ?>
                                        <script type="text/javascript">
							$(document).ready(function(){
								$('#subTaskCategoryName_<?php echo $subCategory->category_id; ?>').editable({
						            url: '<?php echo site_url("settings/update_subCatgory_name");?>',
						            type: 'post',
						            pk: 1,
						            mode: 'inline',
						            showbuttons: true,
						            validate: function (value) {
						            	
						              	if ($.trim(value) == ''){ return 'This field is required'};
						              	var remote = $.ajax({
						              		url: "<?php echo site_url("settings/chk_taskCategory_exists");?>",
											type: "post",
											async : false,
											data: {
												name: $.trim(value),
												company_id: function(){ return $("#company_id").val(); },
												category_id : '<?php echo $subCategory->parent_id;?>',
												sub_category_id : '<?php echo $subCategory->category_id;?>',
												type : 'sub'
											},
											success : function(responseData){
												return responseData;
											}
						              	});
						              	if(remote.responseText == "1") return 'There is an existing record with this subcategory name.';
						            },
						            success : function(departmentData){
						            	
						            }
						            
						        });
                                                        $("#subTaskCategory_status_<?php echo $subCategory->category_id; ?>").bootstrapToggle();
						        $('#subTaskCategory_status_<?php echo $subCategory->category_id; ?>').bootstrapToggle().on('change', function () {
                                                                var xt=$(this).prop('checked')?1:0;
						        	changeCategoryStatus('<?php echo $subCategory->category_id; ?>',xt);
							});
							});
						</script>
					<tr id="subTaskCategory_<?php echo $subCategory->category_id; ?>">
                                            <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i; ?></span></td>
                                            <td width="300px">
							<a href="javascript:void(0)" class="txt-style" id="subTaskCategoryName_<?php echo $subCategory->category_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $subCategory->category_name;?>"><?php echo $subCategory->category_name;?></a>
                                            </td>
                                                <?php if($this->session->userdata('pricing_module_status')=='1'){?>
                                                <td width="100px">
                                                   <input type="checkbox" <?php if($subCategory->is_chargeable == '1'){echo "checked='checked'";}?> name='is_sub_category_chargeable_<?php echo $subCategory->category_id; ?>' id="is_sub_category_chargeable_<?php echo $subCategory->category_id; ?>" onclick="addChargeablesubcategory(<?php echo $subCategory->category_id; ?>);"/>
                                                </td>
                                                <?php }?>
						<td width="200px">
							<div >
<!--                                                                <a href="javascript:;" class="sub_category_up"> <i class="stripicon iconup"></i> </a> 
                                                                <a href="javascript:;" class="sub_category_down"> <i class="stripicon icondown"></i> </a> -->
								<input type="checkbox" name="subTaskCategory_status" <?php if($subCategory->category_status == "Active"){ echo "checked='checked'"; } ?> value="<?php echo $subCategory->category_status;?>" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" id="subTaskCategory_status_<?php echo $subCategory->category_id; ?>"/>
                                                                <a onclick="delete_category('<?php echo $subCategory->category_id; ?>','sub');" id="delete_sub_<?php echo $subCategory->category_id; ?>" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i> </a>
                                                        </div>
                                                </td>
					</tr>
		<?php $i++; } } ?>
	</tbody>
</table>
</div>
<div>
    <input type="text" class="large m-wrap valid" name="subTaskCategory_title" id="subTaskCategory_title">
    <a href="javascript:void(0)" class="btn blue txtbold sm" id="save_subTaskCategory">Add</a>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		//subCategory functions
            $("#save_subTaskCategory").click(function(){
	    	var taskCategory_name = $("#subTaskCategory_title").val();
	    	if($.trim(taskCategory_name) == ''){
	    		$("#alertify").show();
	    		alertify.alert('Please enter sub-category title.');
	    		return false;
	    	} else {
	    		$('#dvLoading').fadeIn('slow');
	    		$.ajax({
					type : 'post',
					url : SIDE_URL+"settings/chk_taskCategory_exists",
					data : {name: taskCategory_name, company_id: $("#company_id").val(),category_id : $("#parent_category").val(), type : 'sub'},
					async : false,
					success : function(data){
						if(data == "1"){
							$("#alertify").show();
				    		alertify.alert("There is an existing record with this sub-category name.", function (e) {
								$("#subTaskCategory_title").focus();
								return false;
							});
							$('#dvLoading').fadeOut('slow');
		    				return false;
						} else {
							$('#dvLoading').fadeIn('slow');
							$.ajax({
					            type: 'post',
					            url : SIDE_URL+"settings/addTaskCategory",
					            data: {taskCategory_name : taskCategory_name, parent_id : $("#parent_category").val(), taskCategory_status : 'Active'},
					            success: function(responseData) {
					            	var responseData = jQuery.parseJSON(responseData);
					            	var html = '<tr id="subTaskCategory_'+responseData.category_id+'">';
                                                            html += '<td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none">'+responseData.seq+'</span></td>';
                                                            html +='<td width="300px"><a href="javascript:void(0)" class="txt-style" id="subTaskCategoryName_'+responseData.category_id+'" data-type="text" data-pk="1" data-original-title="'+responseData.category_name+'">'+responseData.category_name+'</a></td>';
							    if(<?php echo $this->session->userdata('pricing_module_status');?> == '1'){
                                                                html += '<td width="100px"><input type="checkbox" name="is_sub_category_chargeable_'+responseData.category_id+'" id="is_sub_category_chargeable_'+responseData.category_id +'" onclick="addChargeablesubcategory('+responseData.category_id+');" checked /> </td>';
                                                            }
                                                            html += '<td width="200px"><a href="javascript:;" class="sub_category_up"> ';
										
								if(responseData.category_status == "Active"){
								    html += '<input type="checkbox" id="subTaskCategory_status_'+responseData.category_id+'" name="subTaskCategory_status" checked value="'+responseData.category_status+'" class="bts_toggle switch" data-toggle="toggle" data-style="android" data-onstyle="primary"  data-offstyle="danger"/>';
								} else {
                                                                    html += '<input type="checkbox" id="subTaskCategory_status_'+responseData.category_id+'" name="subTaskCategory_status" value="'+responseData.category_status+'" class="switch bts_toggle" data-toggle="toggle" data-style="android" data-onstyle="primary"  data-offstyle="danger"/>';
								}
										
							    html += '<a onclick="delete_category('+responseData.category_id+',\'sub\');" id="delete_sub_'+responseData.category_id+'" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i></a></td></tr>';
					            	($("#company_subTaskCategory .dataTables_empty").length)? $("#company_subTaskCategory .dataTables_empty").parent().remove():'';
					            	$("#company_subTaskCategory").append(html);
					            	
					            	
					            	$('#subTaskCategoryName_'+responseData.category_id).editable({
					            		url: SIDE_URL+"settings/update_subCatgory_name",
							            type: 'post',
							            pk: 1,
							            mode: 'inline',
							            showbuttons: true,
							            validate: function (value) {
							            	
							              	if ($.trim(value) == ''){ return 'This field is required'};
							              	var remote = $.ajax({
							              		url: SIDE_URL+"settings/chk_taskCategory_exists",
												type: "post",
												async : false,
												data: {
													name: $.trim(value),
													company_id: function(){ return $("#company_id").val(); },
													category_id : responseData.parent_id,
													sub_category_id : responseData.category_id,
													type : 'sub'
												},
												success : function(responseData){
													return responseData;
												}
							              	});
							              	if(remote.responseText == "1") return 'There is an existing record with this sub-category name.';
							            },
							            success : function(DivisionData){
							            	
							            }
					            	});
					            	
					            	
					            	$("#subTaskCategory_status_"+responseData.category_id).bootstrapToggle();
					            	$('#subTaskCategory_status_'+responseData.category_id).bootstrapToggle().on('change', function () {
                                                                var zt=$(this).prop('checked')?1:0;
					            		changeCategoryStatus(responseData.category_id,zt);
                                                        });
					            	$("#subTaskCategory_title").val('');
					            	$("#subTaskCategory_title").blur(function(){$("#alertify-cover").css("position","relative");});
					            	
					            	$('#dvLoading').fadeOut('slow');
					            },
					            error: function(responseData){
					                console.log('Ajax request not recieved!');
					                $('#dvLoading').fadeOut('slow');
					            }
					        });
						}
					}
				});
	    		
	    	}
	    	
	    });
	    var table =  $("#sub_category_table").dataTable({
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
			url:SIDE_URL+'settings/setMainCategorySeq',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	});
</script>
