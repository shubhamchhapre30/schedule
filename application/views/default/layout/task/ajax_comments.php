<?php if(isset($task['comments']) && $task['comments']!=''){
	$s3_display_url = $this->config->item('s3_display_url');
	$bucket = $this->config->item('bucket_name');
	foreach($task['comments'] as $comment){
		if($comment['comment_addeby']){
		?>
		<li class="light" id="cmt_<?php echo $comment['task_comment_id'];?>"> 
			<div class="userimg">
				
				<?php 
				$name = 'upload/user/'.$comment['profile_image'];
				if(($comment['profile_image']!='' || $comment['profile_image'] != NULL) && $this->s3->getObjectInfo($bucket,$name)){ ?>
					<img src="<?php echo $s3_display_url.'upload/user/'.$comment['profile_image'];?>" alt="img" class="img-circle" /> 
				<?php } else { ?>
                                         <i class="icon-user taskppicn "></i>
<!--				 	<img src="<?php echo $s3_display_url.'upload/user/no_image.jpg';?>" alt="img" class="img-circle" /> -->
				 <?php } ?>
			</div>
			<div class="userdetail">
                            <div class="usertxt"> <?php echo $comment['first_name'].' '.$comment['last_name'];?>   <span> <a href="javascript:;" onclick="edit_comment('<?php echo $comment['task_comment_id']; ?>')"> <i class="icon-pencil tmsticn" style="color: #000 !important;"></i> </a>  <a href="javascript:;" onclick="delete_comment('<?php echo $comment['task_comment_id']; ?>')" id="delete_comment_<?php echo $comment['task_comment_id']; ?>"> <i class="icon-trash taskppstp" style="color:#000 !important;"></i> </a>  </span> </div>
				<p class="usertxt2"> Added <?php echo time_ago($comment['comment_added_date']);?> </p>
				<p id="orig_comment_<?php echo $comment['task_comment_id']; ?>" class="wrap"><?php echo nl2br(htmlspecialchars_decode($comment['task_comment'])); ?></p>
			</div>
			<div class="clearfix"> </div>
		</li>
		<?php
		}
	}
}?>