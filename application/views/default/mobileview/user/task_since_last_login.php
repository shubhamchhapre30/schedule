<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			 	<!-- <div class="page-title margin-bottom-25">
				 	<h2> My Profile </h2>
				 </div> --> 
				 
				 <div class="page-controler clearfix">
				 		<div class="pull-left"> 
							<a href="<?php echo site_url('home/main');?>" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div>
						<div class="pull-right"> 
							<div class="btn-group btn-control-action">
								
							<select name="s_type" id="s_type" onchange="filtertasks(this.value)" class="btn blue btn-sm" tabindex="1">
								<option class="blue" value="">Select Filter</option>
								<option class="blue" value="due_date">Due Date</option>
								<option class="blue" value="priority">Priority</option>
								<!--<option class="blue" value="people">People</option>-->
							</select>
								  <!--
								  <button type="button" class="btn blue btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	  Sort By Due Date <span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu">
																	  <li><a href="#">Action</a></li>
																	  <li><a href="#">Another action</a></li>
																	  <li><a href="#">Something else here</a></li>
																																			</ul>-->
								  
								</div>
							 <a href="<?php echo base_url('task/add_ind_task');?>" class="btn blue btn-xm"> <i class="stripicon plusicon"> </i>  </a>  	
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
							<tbody id="lastlogin">
								<?php
		                    	 if($last_login_task!='0'){
		                    		foreach ($last_login_task as $l) {
		                    			
									if($l['task_scheduled_date']!= '0000-00-00' ){
										$due_dt = $l['task_scheduled_date'];
									} else {
										$due_dt = $l['task_due_date'];
									}
									
									$name = get_user_name($l['task_allocated_user_id']);
									
									if($l['task_priority']=='None'){ $tsk_st = "";}
									if($l['task_priority']=='Low'){ $tsk_st = "greennoticon";}
									if($l['task_priority']=='Medium'){ $tsk_st = "yellownoticon";}
									if($l['task_priority']=='High'){ $tsk_st = "rednoticon";} 
									
									$task_status_completed_id = get_task_status_id_by_name('Completed');
									if($l['task_status_id'] == $task_status_completed_id){
										$ts = "c_on";
									}else{
										$ts = "";
									}
									
		                    	?>
							  <tr id="task_id_<?php echo $l['task_id'];?>">
								<th scope="row" width="16px;">
									<div class="checkboxes">
										<label class="label_check <?php echo $ts;?>" id="status_<?php echo $l['task_id'];?>" for="task_status_<?php echo $l['task_id'];?>">
										<input onclick="changestatus('<?php echo $l['task_status_id'];?>','<?php echo $l['task_id'];?>');" name="task_status" id="task_status_<?php echo $l['task_id'];?>" value="" type="checkbox" <?php if($l['task_status_id'] == $task_status_completed_id){ ?> checked="" <?php }else{ ?>  <?php }  ?> > &nbsp; </label>
										
									</div>
								</th>
								<td>
									<div class="txt-heading1"> <?php echo (strlen($l['task_title']) > 35)?substr(ucwords($l['task_title']),0,32).'...':ucwords($l['task_title']);?> </div>
									<div class="txt-heading2"> Created by : <?php echo ucwords($name->first_name)." ".ucwords($name->last_name);?></div>
									<div class="txt-heading2"><?php echo date($site_setting_date,strtotime($due_dt));?> </div>
								 </td>
								<td class="text-right"><i class="stripicon <?php echo $tsk_st;?>"> </i> </td>
							  </tr>
							  
							  <?php } }else{ ?>
							  	<tr>
							  		<td colspan="4"> No task Availabe</td>
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
		//alert(status+""+id)
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/completeTask"); ?>',
				//dataType:"json",
				data : {id:id,status:status,from:'lastlogin'},
				success : function(data){
					//alert(data.value);
					//$("#task_id_"+id).hide("slow");		
					$('#lastlogin').html(data);			
					
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
	
	function filtertasks(option)
	{
		var id = option;
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/filterlasttask"); ?>',
				data : {id:id},
				success : function(data){
					
					$("#lastlogin").html(data);
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