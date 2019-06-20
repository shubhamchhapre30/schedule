<?php $theme_url = base_url().getThemeName(); ?>


<div class="customtable">
      <table class="table table-striped table-hover table-condensed flip-content" id="department_table">
	<thead class="flip-content">
	 	<tr>
                    <th></th>
                    <th>Name</th>
                    <th>Action</th>
	  	</tr>
	</thead>
	<tbody id="company_departments">
		<?php $i = 1;
                        if(isset($departments) && $departments!=''){
				foreach($departments as $department){ ?>
                                                <script type="text/javascript">
							$(document).ready(function(){
                                                          $("#<?php echo $department->department_id; ?>").editable({
						            url: '<?php echo site_url("settings/update_department_name");?>',
						            type: 'post',
						            pk: 1,
						            mode: 'inline',
						            showbuttons: true,
						            validate: function (value) {
						            	if ($.trim(value) == ''){ return 'This field is required '};
                                                                if (value.length  > 30) { return 'Max length is 30'};
						              	var remote = $.ajax({
						              		url: "<?php echo site_url("settings/chk_department_exists");?>",
											type: "post",
											async : false,
											data: {
												name: value,
												company_id: function(){ return $("#company_id").val(); },
												dept_id : '<?php echo $department->department_id; ?>',
												devision_id : '<?php echo $department->deivision_id; ?>'
											},
											success : function(responseData){
												return responseData;
											}
						              	});
						              	if(remote.responseText == "1") return 'There is an existing record with this department name.';
						            },
						            success : function(departmentData){
						            	
						            }
						            
						        });
							 $('#department_status_<?php echo $department->department_id; ?>').bootstrapToggle();
                                                         $('#department_status_<?php echo $department->department_id; ?>').bootstrapToggle().on('change',function() {
                                                          var t=$(this).prop('checked')?1:0;
                                                          changeDepartmentStatus('<?php echo $department->department_id; ?>',t);
                                                         });
                                                       });
						</script>
					<tr id="department_<?php echo $department->department_id; ?>">
                                            <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i; ?></span></td>
                                            <td width="70%">
                                                <a href="javascript:void(0)" class="txt-style" id="<?php echo $department->department_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $department->department_title;?>"><?php echo $department->department_title;?></a>
                                            </td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" name="department_status" <?php if($department->status == "Active"){ echo "checked='checked'"; } ?>  data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" value="<?php echo $department->status;?>"  id="department_status_<?php echo $department->department_id; ?>"/>
                                                    <a onclick="delete_department('<?php echo $department->department_id; ?>');" id="delete_department_<?php echo $department->department_id; ?>" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i> </a>
                                                </div>
                                            </td>
					</tr>
                    <?php $i++; } } ?>
                 </tbody>
      </table>
</div>
<div>
    <input type="text" class="large m-wrap valid" name="department_title" id="department_title" maxlength="30" onkeyup="error_display(this.value,'max_error_department')">
    <a href="javascript:void(0)" class="btn btn-common-blue sm" id="save_department">Add</a>
    <span id="max_error_department" style="display:none; color:red;">Max. Length 30 reached.</span>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		//department functions
	    
                  var table =  $("#department_table").dataTable({
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
			url:SIDE_URL+'settings/set_company_department_seq',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	    $("#save_department").click(function(){
	    	var department_title = $("#department_title").val();
	    	if($.trim(department_title) == ''){
	    		$("#alertify").show();
	    		alertify.alert('Please enter department title.');
	    		return false;
	    	} else {
	    		$('#dvLoading').fadeIn('slow');
	    		$.ajax({
					type : 'post',
					url : SIDE_URL+"settings/chk_department_exists",
					data : {name: department_title, company_id: $("#company_id").val(), devision_id:$("#parent_division").val()},
					async : false,
					success : function(data){
						if(data == "1"){
							$("#alertify").show();
                                                        alertify.alert("There is an existing record with this department name.", function (e) {
								$("#department_title").focus();
								return false;
							});
							$('#dvLoading').fadeOut('slow');
		    				return false;
						} else {
                                                    $('#dvLoading').fadeIn('slow');
						$.ajax({
					            type: 'post',
					            url : SIDE_URL+"settings/addDepartment",
					            data: {dept_name : department_title, pop_parent_division : $("#parent_division").val(), dept_status : 'Active'},
					            success: function(responseData) {
					            	var responseData = jQuery.parseJSON(responseData);
					            	var html = '<tr id="department_'+responseData.department_id+'">';
                                                            html +='<td width="3%" style="cursor:pointer;"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none">'+responseData.seq+'</span></td>';
                                                            html += '<td width="70%"><a href="javascript:void(0)" class="txt-style" id="'+responseData.department_id+'" data-type="text" data-pk="1" data-original-title="'+responseData.department_title+'">'+responseData.department_title+'</a></td>';
                                                            html += '<td>';
                                                                if(responseData.status == "Active"){
                                                                    html += '<input type="checkbox" id="department_status_'+responseData.department_id+'" name="department_status" checked value="'+responseData.status+'" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"/>';
								} else {
                                                                    html += '<input type="checkbox" id="department_status_'+responseData.department_id+'" name="department_status" value="'+responseData.status+'" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"/>';
                                                                }
							html += '<a onclick="delete_department('+responseData.department_id+');" href="javascript:void(0)" id="delete_department_'+responseData.department_id+'"> <i class="icon-trash stngicn company_icon_black"></i></a></td></tr>';
					            	($(".dataTables_empty").length == '1')? $(".dataTables_empty").parent().remove():'';
					            	$("#company_departments").append(html);
					            	
					            	
					            	$('#'+responseData.department_id).editable({
					            		url: SIDE_URL+"settings/update_department_name",
							            type: 'post',
							            pk: 1,
							            mode: 'inline',
							            showbuttons: true,
							            validate: function (value) {
							            	
							              	if ($.trim(value) == ''){ return 'This field is required'};
							              	var remote = $.ajax({
							              		url: SIDE_URL+"settings/chk_department_exists",
												type: "post",
												async : false,
												data: {
													name: value,
													company_id: function(){ return $("#company_id").val(); },
													dept_id : responseData.department_id,
													devision_id : responseData.deivision_id
												},
												success : function(responseData){
													return responseData;
												}
							              	});
							              	if(remote.responseText == "1") return 'There is an existing record with this division name.';
							            },
							            success : function(DivisionData){
							            	
							            }
					            	});
					            	
					            	$("#department_status_"+responseData.department_id).bootstrapToggle();
                                                        $('#department_status_'+responseData.department_id).bootstrapToggle().on('change',function() {
                                                            var t=$(this).prop('checked')?1:0;
                                                           changeDepartmentStatus(responseData.department_id,t);
                                                        });
					            	$("#department_title").val('');
					            	$("#department_title").blur(function(){$("#alertify-cover").css("position","relative");});
					            	
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
	});
</script>
