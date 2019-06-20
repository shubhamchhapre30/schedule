<?php 
	$theme_url = base_url().getThemeName();
	date_default_timezone_set($this->session->userdata("User_timezone"));
?>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
			
			<script type="text/javascript">
				$(document).ready(function(){
				 
				  $('#filtertab1').dataTable( {
			        "paging":   false,
			        "bFilter" : false,               
					"bLengthChange": false,
			        "info":     false,
			         "language": {
			        "emptyTable":     "No Records found."
			    	}
			    } );
			    
			   
			});
			
			
			</script>
				<table id="filtertab1" class="table table-striped table-hover table-condensed flip-content">
					<thead class="flip-content">
						<tr>
							<th>Task Id</th>
							<th>User</th>
							<th>Due Date</th>
							<th>Task Name</th>
                                                        <th>Task Status</th>
							<th>Estimated Time (Hrs)</th>
							<th>Time Spent (Hrs)</th>
							<th>No of interruptions</th>
							<th>Priority</th>
							<th>Project</th>
							<th>Task owner</th>
							<th>Colour</th>
							<th>Category</th>
							<th>Sub Category</th>
                                                        <th>Customer Name</th>
                                                        <th>External ID</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								$color_name = $row['name'];
								$category_name = $row['category_name'];
								$sub_category_name = $row['sub_category_name'];
								?>
								<tr>
									<td><?php echo $row['task_id'];?></td>
									<td><?php echo $row['first_name'].' '.$row['last_name'];?></td>
									<td><?php echo date($site_setting_date,strtotime($row['task_due_date'])); ?></td>
									<td><?php echo $row['task_title']; ?></td>
                                                                        <td><?php echo $row['task_status_name'];?></td>
									<td><?php echo round($row['task_time_estimate']/60,2);?></td>
									<td><?php echo round($row['task_time_spent']/60,2);?></td>
									<td><?php echo interruption_by_task($row['task_id'],'this_week');?></td>
									<td><?php echo $row['task_priority'];?></td>
									<td><?php if($row['project_title']){ echo $row['project_title']; }  ?></td>
									<td><?php echo $row['owner_first_name']." ".$row['owner_last_name'];?></td>
									<td><?php echo  $color_name;?></td>
									<td><?php echo $category_name;?></td>
									<td><?php echo $sub_category_name; ?></td>
                                                                        <td><?php echo $row['customer_name'];?></td>
                                                                        <td><?php echo $row['external_id'];?></td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
			