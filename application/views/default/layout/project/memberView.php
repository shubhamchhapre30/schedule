<?php 
$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
?>
<div class="">
								<div class="portlet-body form">
										<div class="form-horizontal">
													<?php
													if($is_owner=='1'){ ?>
											 		<div class="form-group">
															<a onclick="listmem('<?php echo $project_id;?>');" class="btn blue" data-toggle="modal" href="#users_list" id="add_users_<?php echo $project_id;?>" data-dismiss="modal" ><i class="stripicon icoplus"></i>Add Users</a>
														</div>
														<?php } ?>

											 <hr>
											<?php
												$attributes = array('name'=>'frm_addProject', 'id' => 'frm_addProject');
												echo form_open('project/addProject', $attributes);
											?>
													 <div class="people-list">
													 	<ul id="memberlist" class="list-unstyled">

													 		<?php if($members!=""){											 			
																	$proj_admin = get_project_info($project_id);
																	foreach ($members as $mem) {
																		$is_allocated = get_project_allocated($project_id,$mem->user_id);
													 					$name = 'upload/user/'.$mem->profile_image;
																		if(($mem->profile_image != '' || $mem->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) {
														 					$src_member =  $s3_display_url.'upload/user/'.$mem->profile_image;
												        		 		} else {
												        		 	       $src_member = $s3_display_url.'upload/user/no_image.jpg';
																 } ?>
																<li>
																	<div class="people-block">
																		<div class="people-img">
																			<img src="<?php echo $src_member;?>" alt="photo1" class="img-polaroid img-circle" >
																			<?php if($proj_admin['project_added_by'] != $mem->user_id && $is_allocated <= '0' ){ ?>												
																			<a onclick="removeUser('<?php echo $mem->project_users_id;?>','<?php echo $mem->user_id;?>','<?php echo $mem->project_id;?>');" href="javascript:void(0)" >
																				<i class="stripicon iconredcolse"></i>    </a>
																			<?php } ?>
																		</div>
																		<p> <?php echo ucwords($mem->first_name)." ".ucwords($mem->last_name);?> </p>
																		<?php echo($mem->is_project_owner == '1')?"<span> <i class='stripicon iconactadmin'></i> Admin </span>":"<span> <i class='stripicon iconadmin'></i> &nbsp; </span>";?>
																	</div>
																	</li>
													 			<?php }	}?>
															</ul>
													 	</div>
													</form>
												</div>
											</div>
										</div>
