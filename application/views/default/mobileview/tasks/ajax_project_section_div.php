<select class="span11 m-wrap fullwd" name="section_id" id="section_id" tabindex="1" >
	<!--<option value="">Project Section</option>-->
	<?php
		if(isset($sections) && $sections != ''){
			foreach($sections as $pr_section){
				?>
				<option value="<?php echo $pr_section->section_id;?>" <?php if($section_id == $pr_section->section_id){ echo "selected='selected'"; } ?> > <?php echo $pr_section->section_name; ?> </option>
				<?php
			}
		} 
	?>
</select>