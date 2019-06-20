
	<?php if(isset($divisions) && $divisions != ''){
		foreach($divisions as $division){
			?>
			<option value="<?php echo $division->division_id; ?>"><?php echo $division->devision_title;?></option>
		<?php }
	}?>

