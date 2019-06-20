<div class="control-group col-md-12" style="    padding-right: 60px;margin-bottom: 10px;">
	<label class="control-label col-md-2">Select Company <span class="required">*</span></label>
	<div class="controls" id="companyList">
		<select tabindex="1" name="company_id" id="company_id" onchange="getstaff(this.value)" class="medium m-wrap" style="float:left;width: 230px !important;">
			<option value="">Select Company</option>
			<?php 
			if(isset($comapny)){
				foreach($comapny as $cmp){
				?>
				<option value="<?php echo $cmp->company_id ?>" ><?php echo $cmp->company_name; ?></option>
				<?php 
				} 
			}
			?>
		</select>
	
		<div>
			<?php if(isset($users_company) && $users_company!=''){ ?>
				Belongs to Company : 
				<?php
				foreach($users_company as $uc){
					echo '"'.$uc->company_name.'", ';
				}
			}?>
		</div>
	</div>
</div>

	