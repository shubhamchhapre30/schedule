
	
	<?php $x1=0;
		if(isset($users) && $users != ''){
			foreach($users as $u){
				$x1++;
				?>
				<option value="<?php echo $u->user_id;?>" <?php if($u->user_id == get_authenticateUserID()){ echo "selected='selected'"; }?> > <?php echo $u->first_name.' '.$u->last_name; ?> </option>
				<?php
			}
		} 
	?>
	<?php 
	
	if($x1>1){ ?> 
		<option value="multiple_people" id="multiple_people_id">Multiple People...</option>
		<?php }?>

