<script src="<?php echo base_url().getThemeName();?>/assets/js/multiselect.js?Ver=<?php echo VERSION;?>"></script>
<script>

	$(document).ready(function() { 
		
		$("#skill_multiselect").click(function(){
			$('#skill_multiselect_to option').attr("selected",false);
		});
		$("#skill_multiselect_to").click(function(){
			$('#skill_multiselect option').attr("selected",false);
		});
		
		$('#skill_multiselect').multiselect({
			beforeMoveToRight: function($left, $right, options) {
				var value = [];
				$.each(options, function(i, val) {
					value[i] = $(this).val();
				});
				if(value){
					$.ajax({
			            type: 'post',
			            url : '<?php echo site_url("settings/updateSkillStatus"); ?>',
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
			            url : '<?php echo site_url("settings/updateSkillStatus"); ?>',
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
	<select multiple="multiple" id="skill_multiselect" class="large m-wrap" size="5" >
		<?php if($Active_skills){
			foreach($Active_skills as $skill){
				?>
				<option value="<?php echo $skill->skill_id;?>"><?php echo $skill->skill_title; ?></option>
				<?php
			}
		}?>
	</select>
	</div>
	<div class="listbox_spn2">
		<div class="adjbtn-group">
			<a class="btn blue adj-btn" id="skill_multiselect_rightSelected" href="javascript:;"> <i class="stripicon iconrightarro"></i> </a>
			<a class="btn blue adj-btn" id="skill_multiselect_leftSelected" href="javascript:;"> <i class="stripicon iconleftarro"></i> </a>
		</div>
	</div>
	<div   class="listbox_spn4">
	<label class="control-label2">Inactive :</label>	
	<select multiple="multiple" id="skill_multiselect_to" class="large m-wrap"  size="5">
		<?php if($Inactive_skills){
			foreach($Inactive_skills as $skill){
				?>
				<option value="<?php echo $skill->skill_id;?>"><?php echo $skill->skill_title; ?></option>
				<?php
			}
		}?>
	 </select>
	</div>
	<div class="clearfix"> </div>