	<script>
            $(document).ready(function() {
                setCompanySubCategory();
             var table =  $("#maincategory_table").dataTable({
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
        <div class="form-group">
                        <label class="control-label"><b>Parent Task Categories</b> </label>
                        <p class="alert alert-info">Categories and sub categories are used to classify your tasks when you create them . It is particularly useful if you want to review where you spend most of your time. <br><br>
                            Some Examples of categories and sub categories :<br><br>
                            <b>Sales</b><br>
                            #Business Development<br>
                            #Account Management<br>
                            #Marketing<br><br>
                                   
                            <b>Service Delivery</b><br>
                            #Support<br>
                            #Project Activity<br>
                            #Conference<br>
                            #Training<br>
                        </p> 
                        <div class="controls customtable form-horizontal">
                            <table class="table table-striped table-hover table-condensed flip-content" id="maincategory_table">
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
				<tbody id="company_mainCategory">
                                    <?php $i = 1; 
                                        if(isset($ParentTaskCategory) && $ParentTaskCategory!=''){
                                            foreach($ParentTaskCategory as $main_category){ ?>
                                                                                                                <script type="text/javascript">
															$(document).ready(function(){
																$('#mainCategoryTitle_<?php echo $main_category->category_id; ?>').editable({
														            url: '<?php echo site_url("settings/update_catgory_name");?>',
														            type: 'post',
														            pk: 1,
														            mode: 'inline',
														            showbuttons: true,
														            validate: function (value) {
														            	
														              	if ($.trim(value) == ''){ return 'This field is required' };
														              	var remote = $.ajax({
														              		url: "<?php echo site_url("settings/chk_taskCategory_exists");?>",
																			type: "post",
																			async : false,
																			data: {
																				name: $.trim(value),
																				company_id: function(){ return $("#company_id").val(); },
																				category_id : '<?php echo $main_category->category_id; ?>',
																				type : 'main'
																			},
																			success : function(responseData){
																				return responseData;
																			}
														              	});
														              	if(remote.responseText == "1") return 'There is an existing record with this parent category name.';
														            },
														            success : function(categoryData){
														            	var categoryData = jQuery.parseJSON(categoryData);
																		var str = '';
														            	$.map(categoryData.ParentTaskCategory, function (item){
														            		str += '<option value="'+item.category_id+'">'+item.category_name+'</option>';
														            	});
														            	$("#parent_category").html(str);
														            }
														            
														        });
                                                                                                                        $("#mainCategory_status_<?php echo $main_category->category_id; ?>").bootstrapToggle();
														        $('#mainCategory_status_<?php echo $main_category->category_id; ?>').bootstrapToggle().on('change', function () {
                                                                                                                            var t=$(this).prop('checked')?1:0;
                                                                                                                            changeCategoryStatus('<?php echo $main_category->category_id; ?>',t);
															});
															});
														</script>
                                                <tr id="mainCategory_<?php echo $main_category->category_id; ?>">
                                                    <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i; ?></span></td>
                                                    <td width="300px">
                                                        <a href="javascript:void(0)" class="txt-style" id="mainCategoryTitle_<?php echo $main_category->category_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $main_category->category_name;?>"><?php echo $main_category->category_name;?></a>
                                                    </td>
                                                    <?php if($this->session->userdata('pricing_module_status')=='1'){?>
                                                        <td width="100px">
                                                            <input type="checkbox" class="cstm_chkbox" name='is_category_chargeable_<?php echo $main_category->category_id; ?>' id="is_category_chargeable_<?php echo $main_category->category_id; ?>" onclick="addChargeablecategory(<?php echo $main_category->category_id; ?>)" <?php if($main_category->is_chargeable == '1'){echo "checked='checked'";}?>/>
                                                        </td>
                                                    <?php }?>
                                                    <td width="200px">
                                                       <input type="checkbox" name="mainCategory_status" <?php if($main_category->category_status == "Active"){ echo "checked='checked'"; } ?> value="<?php echo $main_category->category_status;?>" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  id="mainCategory_status_<?php echo $main_category->category_id; ?>"/>
                                                       <a onclick="delete_category('<?php echo $main_category->category_id; ?>','main');" id="delete_main_<?php echo $main_category->category_id; ?>" href="javascript:void(0)"> <i class="icon-trash stngicn company_icon_black"></i> </a>
                                                    </td>
						</tr>
                                    <?php $i++; } } ?>
                                </tbody>
                            </table>
			</div>
                        <div class="form-group">
                            <input type="text" class="large m-wrap valid" name="main_category_name" id="main_category_name">
                            <a href="javascript:void(0)" class="btn blue txtbold sm" id="save_main_category">Add</a>
			</div>
                        
                        <div class="form-horizontal form-group" >
                            <label class="control-label col-md-4" style="padding-left:0px !important;" ><b>Parent Task Category</b></label>
				<div class="controls">
                                    <select class="large m-wrap radius-b" name="parent_category" tabindex="1" id="parent_category" onchange="setCompanySubCategory();">
                                        <?php if(isset($ParentTaskCategory) && $ParentTaskCategory!= ''){
                                                foreach($ParentTaskCategory as $category){ ?>
                                                    <option value="<?php echo $category->category_id;?>"><?php echo $category->category_name;?></option>
						<?php } } ?>
                                    </select>
				</div>
			</div>
                        <div class="form-group">
                            <label class="control-label"><b>Associated Sub Categories</b> </label>
                            <div class="controls" id="settings_subCategory">
                            </div>
			</div> 
                </div>