

<?php 
	$i = 0;
	if(isset($task['steps']) && $task['steps'] != ''){
		foreach($task['steps'] as $steps){
			?>
			<tr>
				<script type="text/javascript">
					$(document).ready(function(){
						$('#step_title_<?php echo $steps["task_step_id"]; ?>').editable({
					            url: '<?php echo site_url("task/update_steps");?>',
					            type: 'post',
					            pk: 1,
					            mode: 'inline',
					            showbuttons: true,
					            validate: function (value) {
					            	
					              	if ($.trim(value) == ''){ return 'This field is required'};
					              	
					            },
					            success : function(DivisionData){
					            	
					            }
					            
					        });
					});
				</script>
				<td><input type="checkbox" name="is_completed[]" id="<?php echo $steps["task_step_id"];?>" value="1" <?php if($steps['is_completed'] == '1'){ echo 'checked="checked" '; } ?> /></label> 
					
					</td>
				<td><a href="javascript:void(0)" class="txt-style" id="step_title_<?php echo $steps["task_step_id"]; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $steps['step_title'];?>"><?php echo $steps['step_title'];?></a></td>
				<td> 
					 
					 <?php if($steps['step_added_by'] == get_authenticateUserID()){ ?> 
					 <a href="javascript:;" onclick="delete_step('<?php echo $steps['task_step_id'];?>')" id="delete_step_<?php echo $steps['task_step_id'];?>"> <i class="icon-trash taskppstp"></i> </a>  
					 <?php } ?>
					 <a href="javascript:;" class="up"><i class="icon-arrow-up taskppstp"></i></a> 
					 <a href="javascript:;" class="down"><i class="icon-arrow-down taskppstp"></i></a> 
				</td>
				 <input type="hidden" name="step_title[]" value="<?php echo $steps['step_title'];?>" />
				<input type="hidden" name="seq[]" value="<?php echo $steps['step_sequence'];?>" />
				<input type="hidden" name="ids[]" value="<?php echo $steps['task_step_id'];?>" />
				<input type="hidden" name="added_by[]" value="<?php echo $steps['step_added_by'];?>" />
			</tr>
			<?php
			$i++;
		}
	} else { ?>
		<tr><td colspan="3">No Record Available.</td></tr>
  <?php } ?>
  <input type="hidden" name="total" value="<?php echo $i; ?>" />
<script>
	$(document).ready(function(){
		App.init();
		$(".up,.down").click(function(){
	        var row = $(this).parents("tr:first");
	        if ($(this).is(".up")) {
	            row.insertBefore(row.prev());
	            $("#frm_steps").submit();
	        } else {
	            row.insertAfter(row.next());
	            $("#frm_steps").submit();
	        } 
	    });
	    $("input[name='is_completed[]']").click(function(){
	    	$("#frm_steps").submit();
	    });
	});
</script> 