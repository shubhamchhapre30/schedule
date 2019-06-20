<?php if($project_id == '0' || $project_id == ""){ ?>
	<select class="col-md-11 m-wrap no-margin task-input allocation-change radius-b" name="section_id" id="section_id" tabindex="1" disabled="disabled" >
		<option value="">Project Section</option>
	</select>
<?php } ?>
<?php if(isset($sections) && $sections != ''){ ?>
	<select class="col-md-11 m-wrap no-margin task-input allocation-change radius-b" name="section_id" id="section_id" tabindex="1" >
		<?php foreach($sections as $pr_section){
				?>
				<option value="<?php echo $pr_section->section_id;?>" <?php if($section_id == $pr_section->section_id){ echo "selected='selected'"; } ?> > <?php echo $pr_section->section_name; ?> </option>
				<?php
			}
		?>
	</select>	
<?php } 
	?>
