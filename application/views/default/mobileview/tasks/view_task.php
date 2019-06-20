<?php
	$theme_url = base_url().getThemeName(); 
	$allocated_user_name = get_user_name($taskDetail->task_allocated_user_id);
	$owner_name = get_user_name($taskDetail->task_owner_id);
	

	
?>

<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			 	 <div class="page-controler clearfix">
				 		<div> 
				 			<a onclick="goBack();" class="btn  pull-left blue btn-sm"> <i class="stripicon backicon"> </i> Cancel </a>
							<span class="middle-title"> Task </span>	
							<?php if($taskDetail->task_project_id=='0'){ ?>	
							  
							<a href="<?php echo site_url('task/edit_ind_task/'.base64_encode($taskDetail->task_id));?>" class="btn  pull-right  blue btn-sm">  Edit </a>
							<?php }else{ ?>
								
								<a href="<?php echo site_url('task/edit_task/'.base64_encode($taskDetail->task_id));?>" class="btn  pull-right  blue btn-sm">  Edit </a>
								<?php } ?> 
						</div>
				 </div>
				 
				 <div>
						<?php if($this->session->flashdata('msg')!=''){
								 	 			
							?>
							<script>
								$(document).ready(function() {
									$('#user_msg').slideDown('slow').delay(5000).slideUp('slow');
								});
							</script>
							<div class='alert alert-success' id="user_msg"><a class='closemsg' data-dismiss='alert'></a><span>
								<?php if($this->session->flashdata('msg') == 'update'){ echo "Task updated successfully."; } ?>
								<?php if($this->session->flashdata('msg') == 'insert'){ echo "Task inserted successfully."; } ?>
								
							</span></div>
							<?php 
						}?>
						</div>
			  
				 <div class="">
				  <div class="horizontal-form">
				  		<div class="viewtask-top">
							 <div class="radios">
            					<label class="" for="radio-01">
            						<!--<input name="sample-radio" id="radio-01" value="1" type="radio" checked="">-->
            						<?php echo ucwords($taskDetail->task_title);?> </label>
							  </div>
							  <?php if($taskDetail->task_description){ ?>
							  	<p><?php echo $taskDetail->task_description;?></p>
							  <?php } ?>
							 
						</div>
				  		
						<div class="view-list">
							<ul class="list-unstyled">
								<li> Due Date <span> <?php echo ($taskDetail->task_due_date!='0000-00-00')?date($site_setting_date,strtotime(str_replace("/", "-", $taskDetail->task_due_date))):'N/A';?> </span></li>
								<li> Scheduled Date <span> <?php echo ($taskDetail->task_scheduled_date!='0000-00-00')?date($site_setting_date,strtotime(str_replace("/", "-", $taskDetail->task_scheduled_date))):'N/A';?> </span></li>
								<li> Status <span> <?php echo ($taskDetail->task_status_id!='0')?getStatusName($taskDetail->task_status_id):'N/A';?></span></li>
								<li> Priority <span> <?php echo $taskDetail->task_priority;?> </span></li>
								<!--
								<li> Category<span>  <?php echo (get_category_name($taskDetail->task_category_id)!='')?get_category_name($taskDetail->task_category_id):'N/A';?></span></li>
																<li> Sub Category <span> <?php echo (get_category_name($taskDetail->task_sub_category_id)!='')?get_category_name($taskDetail->task_sub_category_id):'N/A';?></span></li>-->
								
								<li> Assigned to <span> <?php echo $allocated_user_name->first_name." ".$allocated_user_name->last_name;?></span></li>
								<li> Allocated by <span> <?php echo $owner_name->first_name." ".$owner_name->last_name;?></span></li>
								<li> Time Estimate<span><?php echo minutesToTime($taskDetail->task_time_estimate);?></span></li>
								<li> Time Spent <span> <?php echo minutesToTime($taskDetail->task_time_spent);?></span></li>
								<?php if($taskDetail->task_project_id!='0'){ ?>
									<li> Project <span > <?php echo getProjectName($taskDetail->task_project_id);?></span></li>
								<?php } ?>
								
								<!--<li> Section <span> <?php echo ($taskDetail->section_name!='')?$taskDetail->section_name:'N/A';?></span></li>-->
								<li> Personal <span> <?php if($taskDetail->is_personal == '1'){ echo 'Yes'; }else{ echo "No";} ?> </span></li>
								<li> Locked Due Date <span> <?php if($taskDetail->locked_due_date == '1'){ echo 'Yes'; }else{ echo 'No';} ?></span></li>
								<!--
								<li> Skills Required <span> 
																	<?php if(isset($skills) && $skills!= ''){
																		foreach($skills as $skil){
																			if(in_array($skil->skill_id,(array)$taskDetail)){  echo $skil->skill_title.","; }
																		}}else{?>N/A<?php } ?>
																	
																	</span></li>-->
								
							 </ul>
						</div>
					
					
					 	<!-- BEGIN FORM-->
						<form name="frm_add_comment" id="frm_add_comment" action="">
							 <div class="control-group"  >
								 <label class="control-label viewlabel">Comments</label>
								 <div id="task_lst">
								 <?php if($comments!=''){
								 	foreach ($comments as $c) {
								 		
										$user = get_user_info($c['comment_addeby']);
								 	?> 
								 <p class="veiwpera"> Added by <b><?php echo ucwords($user->first_name)." ".ucwords($user->last_name);?> <?php echo time_ago($c['comment_added_date']); ?></b> </br>
									 <?php echo $c['task_comment'];?> </p>
									 <?php } } ?>
									 </div> 
								<div class="controls">
									<textarea name="task_comment" id="task_comment" class="m-wrap fullwd " rows="3"></textarea>
								</div>
								<!--<span class="chr">Char left :- <i id="ch_cmt_1"><?php echo CMT_TEXT_SIZE;?></i></span>-->
							</div>
							<div class="control-group">
								<div class="controls  margin-top-20">
									<input type="hidden" name="task_id" id="comment_task_id" value="<?php echo $taskDetail->task_id; ?>" />
									<input type="hidden" name="project_id" id="project_id" value="<?php echo $taskDetail->task_project_id; ?>" />			
									
									 <button type="submit" class="btn blue btn-mid"> Add Comments </button>
								 </div>
							 </div>
						</form>
						<!-- END FORM-->  
					</div>
				 </div> 
			 </div> <!-- /container -->
		</div>
	</div>
</div>
<script src="<?php echo $theme_url; ?>/js/jquery.tinylimiter.js?Ver=<?php echo VERSION;?>"></script>
<script>
	$(document).ready(function(){
		
		$("#frm_add_comment").validate({
			
		errorElement: 'span', //default input error message container
        errorClass: 'help-inline', // default input error message class
        focusInvalid: true, // do not focus the last invalid input
        
			rules : {
				"task_comment" : {
					required : true,
					rangelength: [3, <?php echo CMT_TEXT_SIZE;?>]
				}
			},
			submitHandler:function(){
				
				$('#dvLoading').fadeIn('slow');
				$.ajax({
		            type: 'post',
		            url : '<?php echo site_url("project/comment"); ?>',
		            data: $('#frm_add_comment').serialize(),
		            success: function(responseData) {
		            	//alert(responseData);
		            	//return false;
		            	$("#task_lst").html(responseData);
		            	$("#task_comment").val('');
		            	//$("#task_comment_id").val('');
		            	$('#dvLoading').fadeOut('slow');
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
		        });
			}
		});
	});
	
</script>