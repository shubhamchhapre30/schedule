<?php if(isset($task['history']) && $task['history']!=''){
			$s3_display_url = $this->config->item('s3_display_url');
			$bucket = $this->config->item('bucket_name');
				 		foreach($task['history'] as $history){
				 			if($history['history_added_by']){
				 			?>
				 			<li class="light"> 
								<div class="userimg">
									<?php 
									$name = 'upload/user/'.$history['profile_image'];
									if(($history['profile_image']!='' || $history['profile_image'] != NULL) && $this->s3->getObjectInfo($bucket,$name)){ ?>
										<img src="<?php echo $s3_display_url.'upload/user/'.$history['profile_image'];?>" alt="img" class="img-circle" /> 
									<?php } else { ?>
									 	 <i class="icon-user taskppicn "></i>
									 <?php } ?>	
									 
								</div>
								<div class="userdetail">
									<div class="usertxt"> <?php echo $history['first_name'].' '.$history['last_name'];?>   <span> <?php echo date('jS M Y g:i a',strtotime(toDateNewTime($history['date_added'])));?>  </span> </div>
									<p> <?php echo $history['histrory_title']; ?> 
										<?php if($history['history_desc'] != ''){
												echo ': ';
												if(strlen($history['history_desc']) > 150){
													echo substr($history['history_desc'],0,150);
													?>
													<span id="show_more_<?php echo $history['task_history_id'];?>" style="display: none;">
														<?php echo substr($history['history_desc'],150); ?>
													</span>
													<a href="javascript://" id="show_more_link_<?php echo $history['task_history_id'];?>" onclick="show_more_link('<?php echo $history['task_history_id'];?>');" class="showlink"> Show detail</a>
													
													<?php
												} else {
													echo $history['history_desc'];
												}
										}?>
										
								</div>
								<div class="clearfix"> </div>
							
							</li>
							
				 			<?php
				 			}
				 		}
				 	} else { ?>
				 		<li>No Record Available.</li>
				 	<?php } ?>