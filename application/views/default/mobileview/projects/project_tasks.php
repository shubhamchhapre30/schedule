<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			 	<!-- <div class="page-title margin-bottom-25">
				 	<h2> My Profile </h2>
				 </div> --> 
				 
				 <div class="page-controler clearfix">
				 		<div class="pull-left"> 
							<a href="<?php echo site_url('project/list_project');?>" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div>
						<div class="pull-right"> 
							<div class="btn-group btn-control-action">
								<select name="s_type" id="s_type" onchange="filterUser(this.value,'<?php echo $project_id;?>')" class="btn blue btn-sm" tabindex="1">
								<option class="blue" value="">Select Filter</option>
								<option class="blue" value="<?php echo get_authenticateUserID() ;?>">My Tasks</option>
								<option class="blue" value="all">All</option>
								</select>
								</div>
							 <a href="<?php echo site_url('task/add_task/'.base64_encode($project_id));?>" class="btn blue btn-xm"> <i class="stripicon plusicon"> </i>  </a>  	
						</div>
				 </div>
				
				 <div class="common-table">
				 	<div class="table-responsive">
					  <table class="table table-hover table-striped">
							<thead>
							  <tr>
								<th width="16px;">#</th>
								<th>Task</th>
								<th>&nbsp;</th>
							  </tr>
							</thead>
							<tbody id="tasklist">
								
								<?php if($project_tasks){
									//pr($project_tasks);die;
									foreach ($project_tasks as $p){
										//pr($p);
										$tmp = $p;
										//pr($p);
										if(!empty($tmp) && $tmp !=''){
										$name = get_user_name($p->task_allocated_user_id);
										$user_name = ($name)?ucwords($name->first_name." ".$name->last_name[0]).".":"N/A";
										
										if($p->task_priority=='None'){ $tsk_st = "";}
										if($p->task_priority=='Low'){ $tsk_st = "greennoticon";}
										if($p->task_priority=='Medium'){ $tsk_st = "yellownoticon";}
										if($p->task_priority=='High'){ $tsk_st = "rednoticon";} 
										
										$task_status_completed_id = get_task_status_id_by_name('Completed');
										if($p->task_status_id == $task_status_completed_id){
											$ts = "c_on";
										}else{
											$ts = "";
										}
								
										
								?>
								
							<tr id="task_id_<?php echo $p->task_id;?>">
								<th scope="row" width="16px;">
									<div class="checkboxes">
										<label class="label_check <?php echo $ts;?>" id="status_<?php echo $p->task_id;?>" for="task_status_<?php echo $p->task_id;?>">
										<input onclick="changestatus('<?php echo $p->task_status_id;?>','<?php echo $p->task_id;?>');" name="task_status" id="task_status_<?php echo $p->task_id;?>" value="" type="checkbox" <?php if($p->task_status_id == $task_status_completed_id){ ?> checked="" <?php }else{ ?>  <?php }  ?> > &nbsp; </label>
										
									</div>
								</th>
								<td>
									<div title="<?php echo $p->task_description;?>"><a href="<?php echo site_url('task/view_task/'.base64_encode($p->task_id));?>"> <?php echo (strlen($p->task_title) > 40)?substr(ucwords($p->task_title),0, 37).'...':ucwords($p->task_title);?></a> </div>
									<div class="txt-heading2"> Created by : <?php echo ucwords($user_name);?></div>
									<?php 
										if($p->task_scheduled_date != '0000-00-00'  ){
											$due_dt = date($site_setting_date,strtotime($p->task_scheduled_date));
										}  else {
											$due_dt = 'N/A';
										}									
									?>
									<div class="txt-heading2">Due Date : <?php echo $due_dt;?> </div>
									
								 </td>
								<td class="text-right"><i class="stripicon <?php echo $tsk_st;?>"> </i> </td>
							  </tr>						
								<?php  } }	}else{?>
									<tr> 
										<td colspan="4">No tasks Available</td>	
									</tr>
									
									<?php } ?>
							</tbody>
						  </table>
					</div>
				 </div>
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>

<script type="text/javascript">
	
	function changestatus(status,id)
	{
		//alert(status+"==="+id)
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("project/completeTask"); ?>',
				//dataType:"json",
				data : {id:id,status:status,project_id:'<?php echo $project_id;?>'},
				success : function(data){
					$('#tasklist').html(data);
					$('#dvLoading').fadeOut('slow');
				},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});
				
		}else{
			alertify.alert('Please select atleast one criteria..!!');
		}
	}
	
	function filterUser(option,p_id)
	{
		var id = option;
		var project_id = p_id;
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("project/filterUser"); ?>',
				data : {id:id,project_id:project_id},
				success : function(data){
					
					$("#tasklist").html(data);
					$('#dvLoading').fadeOut('slow');
				},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});
				
		}else{
			alertify.alert('Please select atleast one option..!!');
		}
	}
	
	
	function setupLabel() {
			if ($('.label_check input').length) {
				$('.label_check').each(function(){ 
					$(this).removeClass('c_on');
				});
				$('.label_check input:checked').each(function(){ 
					$(this).parent('label').addClass('c_on');
				});                
			};
			if ($('.label_radio input').length) {
				$('.label_radio').each(function(){ 
					$(this).removeClass('r_on');
				});
				$('.label_radio input:checked').each(function(){ 
					$(this).parent('label').addClass('r_on');
				});
			};
		};
			$(document).ready(function(){
			
				$('.label_check, .label_radio').click(function(){
					setupLabel();
				});
			setupLabel(); 
		 });
	

</script>