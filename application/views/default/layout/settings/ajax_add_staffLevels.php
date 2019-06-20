<script src="<?php echo base_url().getThemeName();?>/assets/js/multiselect.js?Ver=<?php echo VERSION;?>"></script>
<script>

	$(document).ready(function() { 
		
		$("#staff_multiselect").click(function(){
			$('#staff_multiselect_to option').attr("selected",false);
		});
		$("#staff_multiselect_to").click(function(){
			$('#staff_multiselect option').attr("selected",false);
		});
		
		$('#staff_multiselect').multiselect({
			beforeMoveToRight: function($left, $right, options) {
				var value = [];
				$.each(options, function(i, val) {
					value[i] = $(this).val();
				});
				if(value){
					$.ajax({
			            type: 'post',
			            url : '<?php echo site_url("settings/updateStaffLevelsStatus"); ?>',
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
			            url : '<?php echo site_url("settings/updateStaffLevelsStatus"); ?>',
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
	});
</script>

<div class="listbox_spn4">
																<label class="control-label2">Active :</label>
																<select multiple="multiple" id="staff_multiselect" class="large m-wrap" size="5" >
																	<?php if($Active_staffLevels){
																		foreach($Active_staffLevels as $staff){
																			?>
																			<option value="<?php echo $staff->staff_level_id;?>"><?php echo $staff->staff_level_title;?></option>
																			<?php
																		}
																	}?>
																</select>
																</div>
																<div class="listbox_spn2">
																	<div class="adjbtn-group">
																		<a class="btn blue adj-btn" id="staff_multiselect_rightSelected" href="javascript:;"> <i class="stripicon iconrightarro"></i> </a>
																		<a class="btn blue adj-btn" id="staff_multiselect_leftSelected" href="javascript:;"> <i class="stripicon iconleftarro"></i> </a>
																	</div>
																</div>
																<div   class="listbox_spn4">
																<label class="control-label2">Inactive :</label>	
																<select multiple="multiple" id="staff_multiselect_to" class="large m-wrap"  size="5">
																	<?php if($Inactive_staffLevels){
																		foreach($Inactive_staffLevels as $staff){
																			?>
																			<option value="<?php echo $staff->staff_level_id;?>"><?php echo $staff->staff_level_title;?></option>
																			<?php
																		}
																	}?>
																 </select>
																</div>
																<div class="clearfix"> </div>