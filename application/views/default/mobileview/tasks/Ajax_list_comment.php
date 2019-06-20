<?php if($comments!=''){
		 	foreach ($comments as $c) {
				$user = get_user_info($c['comment_addeby']);
		 	?> 
		 <p class="veiwpera"> Added by <b><?php echo ucwords($user->first_name)." ".ucwords($user->last_name);?> <?php echo time_ago($c['comment_added_date']); ?></b> </br>
			 <?php echo $c['task_comment'];?> </p>
			 <?php } } ?>