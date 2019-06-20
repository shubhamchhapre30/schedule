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
							<th>Task ID</th>
							<th>Task Name</th>
							<th>Allocated to</th>
							<th>Manager</th>
							<th>Task Owner</th>
							<th>Priority</th>
							<th>Project</th>
							<th>Category</th>
							<th>Sub Category</th>
							<th>Due Date</th>
							<th>Days Overdue</th>
							<th>Estimated Time (Hrs)</th>
							<th>Time Spent (Hrs)</th>
							<th>Task Status</th>
                                                        <th>Customer Name</th>
                                                        <th>External ID</th>
							<th>No of interruptions logged for this task</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								$project_name = $row['project_title'];
								if($project_name){
									$project_name = $project_name;
								} else {
									$project_name = "N/A";
								}
								$category_name = $row['category_name'];
								if($category_name){
									$category_name = $category_name;
								} else {
									$category_name = "N/A";
								}
								$sub_category_name = $row['sub_category_name'];
								if($sub_category_name){
									$sub_category_name = $sub_category_name;
								} else {
									$sub_category_name = "N/A";
								}
								?>
								<tr>
									<td><?php echo $row['task_id'];?></td>
									<td><?php echo $row['task_title']; ?></td>
									<td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
									<td><?php 
										$users = get_managers_of_users($row['task_allocated_user_id']);
										$managers = '';
										if($users){
											foreach($users as $u){
												if($managers){
													$managers .= ', '.$u->first_name.' '.$u->last_name;
												} else {
													$managers .= $u->first_name.' '.$u->last_name;
												}
											}
										}
										if($managers){
											echo $managers;
										} else {
											echo "N/A";
										}
										
										?></td>
									<td><?php echo $row['owner_first_name']." ".$row['owner_last_name'];?></td>
									<td><?php echo $row['task_priority'];?></td>
									<td><?php echo $project_name; ?></td>
									<td><?php echo $category_name; ?></td>
									<td><?php echo $sub_category_name;?></td>
									<td><?php 
												$due = $row['task_due_date'];
												echo date($site_setting_date,strtotime($row['task_due_date'])); 
											 ?>	</td>
									<td><?php
									 $now = time(); // or your date as well
								     $your_date = strtotime($due);
								     $datediff = $now - $your_date;
								     echo floor($datediff/(60*60*24));
									 ?></td>
									<td><?php echo round($row['task_time_estimate']/60,2);?></td>
									<td><?php echo round($row['task_time_spent']/60);?></td>
									<td><?php echo $row['task_status_name'];?></td>
                                                                        <td><?php echo $row['customer_name'];?></td>
                                                                        <td><?php echo $row['external_id'];?></td>
									<td><?php echo interruption_by_task($row['task_id']);?></td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
				