<?php 
$theme_url = base_url().getThemeName();
?>
<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			   <div class="page-controler clearfix">
				 		<div class="pull-left"> 
							<a onclick="goBack();" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div>
				 </div>
				
				 <div class="common-table">
				 	<div class="table-responsive">
					  <table class="table table-hover table-striped">
							<thead>
							  <tr>
								<th>Delay (Days)</th>
								<th>Task</th>
								<th class="text-left">Status</th>
							  </tr>
							</thead>
							<tbody id="overdue_tasks">
								<?php if($overdue_task!='0'){
                    		
							foreach ($overdue_task as $t) {
								
								$name = get_user_name($t['task_allocated_user_id']);
								if($name){
									$user_name = $name->first_name." ".$name->last_name;
								}else{
									$user_name = "N/A";
								}
								$today = date('Y-m-d'); 
								if($t['task_due_date']!= '0000-00-00' ){
									$due_dt = date($site_setting_date,strtotime($t['task_due_date']));
									$delay = round(floor(strtotime($today) - strtotime($t['task_due_date']))/(60*60*24));
								} else {
									$due_dt = "N/A";
									$delay = 'N/A';
								}
								
								if($t['task_priority']=='None'){ $tsk_st = "";}
								if($t['task_priority']=='Low'){ $tsk_st = "greennoticon";}
								if($t['task_priority']=='Medium'){ $tsk_st = "yellownoticon";}
								if($t['task_priority']=='High'){ $tsk_st = "rednoticon";} 
								?>
							  <tr>
								 
								 <td>
								 	<div class="txt-heading1"> <?php echo $delay;?></div>
								 </td>
								 
								<td>
									
									<!--<div title="<?php echo $t['task_description'];?> class="txt-heading1"> <?php echo (strlen($t['task_title']) > 27)?substr(ucwords($t['task_title']),0, 24).'...':ucwords($t['task_title']);?> </div>-->
							<div title="<?php echo $t['task_description'];?> class="txt-heading1"> <a href="<?php echo ($t['task_project_id']=='0')?site_url('task/view_task/'.base64_encode($t['task_id'])):site_url('task/view_task/'.base64_encode($t['task_id']));?>" ><?php echo (strlen($t['task_title']) > 27)?substr(ucwords($t['task_title']),0, 24).'...':ucwords($t['task_title']);?></a></div>		 
									<div class="txt-heading2">Allocated to :  &nbsp;<?php echo $user_name;?>  </div>
								 </td>
								
								<!--<td class="text-left"> <div class="status-bx green"> Ready </div> <i class="stripicon rednoticon"> </i> </td>-->
								 <?php 
			                        foreach($task_status as $ts){
			                        if($ts->task_status_id == $t['task_status_id']){
			                        		
			                        	if($ts->task_status_name=='Not Ready')
										{
											$tsk_clr = "red";
										}
										if($ts->task_status_name=='Ready')
										{
											$tsk_clr = "green";
										}
										if($ts->task_status_name=='In Progress')
										{
											$tsk_clr = "black";
										}
										
										if( $ts->task_status_name!='In Progress' && $ts->task_status_name!='Ready' && $ts->task_status_name!='Not Ready')
										{
											$tsk_clr = "parrot";
										}
										
			                        ?>
			                        <td class="text-left"><div class="status-bx <?php echo $tsk_clr;?>"><?php echo $ts->task_status_name;?> </div> <i class="stripicon <?php echo $tsk_st;?>"> </i></td>
			                        <?php } } ?>
							  </tr>
							  
							  <?php } }else{ ?>
							  	<td colspan="3">
									<div class="txt-heading1"> No records available. </div>
								 </td>
							  	
							  	  <?php } ?>
							  
							  
							</tbody>
						  </table>
					</div>
				 </div>
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>