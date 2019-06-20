<script type="text/javascript" src="<?php echo base_url().getThemename();?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName();?>/assets/js/multiselect.js?Ver=<?php echo VERSION;?>"></script> 
<script type="text/javascript">
	$(document).ready(function(){
		$('#manager_multiselect').multiselect({});
	});
</script>
<label class="control-label col-md-2" style="margin-right: 15px">Reports to </label>
	<div class="controls">
		<div class="margin-bottom-10">
			<div class="listbox_spn4">
				<label>&nbsp;</label>
				<select multiple="multiple" name="manager_multiselect[]" id="manager_multiselect" class="large m-wrap radius-b" size="5" >
					<?php if($managers){
						foreach($managers as $row){
							if($row->user_id != $user_id){
							?>
							<option value="<?php echo $row->user_id;?>"><?php echo $row->first_name.' '.$row->last_name;?></option>
							<?php
							}	}
					}?>
				</select>
			</div>
			<div class="listbox_spn2">
				<div class="adjbtn-group">
					<a class="btn blue adj-btn" id="manager_multiselect_rightSelected" href="javascript:;"> <i class="stripicon iconrightarro"></i> </a>
					<a class="btn blue adj-btn" id="manager_multiselect_leftSelected" href="javascript:;"> <i class="stripicon iconleftarro"></i> </a>
				</div>
			</div>
			<div class="listbox_spn4">
				<label class="control-label">Selected </label>	
                                <select multiple="multiple" name="manager_multiselect_to[]" id="manager_multiselect_to" class="large m-wrap margin-bottom-10 radius-b"  size="5" >
					<?php if($user_managers){
						foreach($user_managers as $row){
							?>
							<option value="<?php echo $row->manager_id;?>" ><?php echo $row->first_name.' '.$row->last_name;?></option>
							<?php
						}
					}?>
				 </select>
			</div>
			<div class="clearfix"> </div>
		</div>
		 
	</div>
