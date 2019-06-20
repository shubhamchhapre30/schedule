<?php if($comments){
			$s3_display_url = $this->config->item('s3_display_url');
			$bucket = $this->config->item('bucket_name');							
			foreach ($comments as $cmt) {
                                $user = get_user_info($cmt->comment_addeby);
                                $name = 'upload/user/'.$user->profile_image;
				if(($user->profile_image != '' || $user->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) {
                                    $src =  $s3_display_url.'upload/user/'.$user->profile_image;
				} else {
                                    $src = $s3_display_url.'upload/user/no_image.jpg';
				} ?>
                                <div class="row" >
                                    <div class="col-md-2">
                                         <img class="img-responsive comment-img-new" alt="" src="<?php echo $src;?>" alt="" />
                                    </div>
                                    <div class="col-md-10 well well-sm">
                                        <label class="comment-label "><b><?php echo ucwords($user->first_name)." ".ucwords($user->last_name);?></b> added <?php echo time_ago($cmt->comment_added_date); ?></label>
                                        <div class="position-relative">
                                            <div class="comment-msgblock">
                                                    <p class="comment-label"><?php echo $cmt->task_comment;?></p>
                                            </div>
                                            <?php if($cmt->comment_addeby == get_authenticateUserID()){ ?>
                                                        <a href="javascript:void(0)" onclick="removeCmt('<?php echo $cmt->task_comment_id;?>')" >
                                                        <i class="icon-trash prjcmt" id="removeCmt_<?php echo $cmt->task_comment_id;?>"></i></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
  <?php } }else{?> 
			<div >
                            <label class="comment-label">No Comments</label>
			</div>
        <?php } ?>