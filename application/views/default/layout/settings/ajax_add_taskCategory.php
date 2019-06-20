<script src="<?php echo base_url().getThemeName();?>/assets/js/multiselect.js?Ver=<?php echo VERSION;?>"></script>
<script>

	$(document).ready(function() { 
		$("#parent_category").change(function(){
			var parent_div_id = $(this).val();
			$.ajax({
	            type: 'post',
	            url : '<?php echo site_url("settings/setSubCategory"); ?>',
	            data: {parent_id : parent_div_id},
	            success: function(responseData) {
	                $("#updated_subCategory").html(responseData);
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	            }
	        });
		});
		
		$('#taskCategory_multiselect').multiselect({
			beforeMoveToRight: function($left, $right, options) {
				var value = [];
				$.each(options, function(i, val) {
					value[i] = $(this).val();
				});
				if(value){
					$.ajax({
			            type: 'post',
			            url : '<?php echo site_url("settings/updateTaskCategoryStatus"); ?>',
			            data : { value : value, status : 'Inactive' },
			            success: function(responseData) {
			               
			            },
			            error: function(responseData){
			                console.log('Ajax request not recieved!');
			            }
			        });
				}
				return true; 
			},
			beforeMoveToLeft: function($left, $right, options) {
				var value = [];
				$.each(options, function(i, val) {
					value[i] = $(this).val();
				});
				if(value){
					$.ajax({
			            type: 'post',
			            url : '<?php echo site_url("settings/updateTaskCategoryStatus"); ?>',
			            data : { value : value, status : 'Active' },
			            success: function(responseData) {
			               
			            },
			            error: function(responseData){
			                console.log('Ajax request not recieved!');
			            }
			        });
				}
				return true; 
			}
		});
		$("#taskCategory_multiselect").click(function(){
			$('#taskCategory_multiselect_to option').attr("selected",false);
		});
		$("#taskCategory_multiselect_to").click(function(){
			$('#taskCategory_multiselect option').attr("selected",false);
		});
		
	});
</script>

<div class="control-group">
														<label class="control-label">Task Categories :</label>
														<div class="controls">
															<div class="margin-bottom-10" id="">
																<div class="listbox_spn4">
																<label class="control-label2">Active :</label>
																<select multiple="multiple" id="taskCategory_multiselect" class="large m-wrap" size="5" >
																	<?php if($Active_taskCategory){
																		foreach($Active_taskCategory as $taskCategory){
																			?>
																			<option value="<?php echo $taskCategory->category_id;?>"><?php echo $taskCategory->category_name;?></option>
																			<?php
																		}
																	}?>
																</select>
																</div>
																<div class="listbox_spn2">
																	<div class="adjbtn-group">
																		<a class="btn blue adj-btn" id="taskCategory_multiselect_rightSelected" href="javascript:;"> <i class="stripicon iconrightarro"></i> </a>
																		<a class="btn blue adj-btn" id="taskCategory_multiselect_leftSelected" href="javascript:;"> <i class="stripicon iconleftarro"></i> </a>
																	</div>
																</div>
																<div   class="listbox_spn4">
																<label class="control-label2">Inactive :</label>	
																<select multiple="multiple" id="taskCategory_multiselect_to" class="large m-wrap"  size="5">
																	<?php if($Inactive_taskCategory){
																		foreach($Inactive_taskCategory as $taskCategory){
																			?>
																			<option value="<?php echo $taskCategory->category_id;?>"><?php echo $taskCategory->category_name;?></option>
																			<?php
																		}
																	}?>
																 </select>
																</div>
																<div class="clearfix"> </div>
															</div>
															<div>
																	<a class="btn blue txtbold sm" data-toggle="modal" href="#taskCategory_responsive" onclick="chk_add_taskCategory();"> Add   </a>
																	<a class="btn blue txtbold sm" href="javascript:;" onclick="edit_taskCategory_selected();"> Edit Selected   </a>
															</div>
														</div>
													</div>
													<!--
													<div class="control-group">
																												<label class="control-label">Parent Task Category : </label>
																												<div class="controls">
																													<select class="large m-wrap" name="parent_category" tabindex="1" id="parent_category">
																														<?php if($ParentTaskCategory){
																															foreach($ParentTaskCategory as $category){
																																?>
																																<option value="<?php echo $category->category_id;?>"><?php echo $category->category_name;?></option>
																																<?php
																															}
																														}?>
																													</select>
																												</div>
																											</div>-->
													
														