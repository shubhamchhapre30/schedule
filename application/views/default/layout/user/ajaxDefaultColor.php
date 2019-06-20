<option value="">--Select--</option>
<?php if(isset($active_colors) && $active_colors!=''){
	foreach($active_colors as $d){
		?>
		<option value="<?php echo $d->user_color_id;?>" <?php if($default_color == $d->user_color_id){ echo "selected='selected'";} ?>><?php echo $d->name;?></option>
		<?php
	}
} ?>
