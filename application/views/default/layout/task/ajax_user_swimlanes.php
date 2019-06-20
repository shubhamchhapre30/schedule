<select class=" m-wrap no-margin col-md-11 task-input width350 radius-b" name="task_swimlane_id" id="task_swimlane_id" tabindex="1" <?php echo $type;?>>
	<?php if(isset($user_swimlanes) && $user_swimlanes!=''){
		foreach($user_swimlanes as $swimlane){
			?>
			<option value="<?php echo $swimlane->swimlanes_id;?>" <?php if($swimlane_id == $swimlane->swimlanes_id){ echo "selected='selected'";  }?> ><?php echo $swimlane->swimlanes_name;?></option>
			<?php
		}
	}   ?>          
</select>
<span class="input-load" id="task_swimlane_id_loading"></span>
<input type="hidden" name="task_swimlane_id" id="hdn_swimlane_id" value="<?php echo $swimlane_id;?>" />
