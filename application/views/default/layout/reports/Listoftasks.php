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
			    });
			    
			   
			});
			
			
			</script>
				<table id="filtertab1" class="table table-striped table-hover table-condensed flip-content">
					<thead class="flip-content">
						<tr>
							<th>Task ID</th>
							<th>Task Name</th>
							<th>Task Owner</th>
							<th>Allocated to</th>
							<th>User department</th>
							<th>User division</th>
							<th>Priority</th>
							<th>Colour</th>
							<th>Project</th>
                                                        <th>Task Status</th>
							<th>Task Category</th>
							<th>Task Sub Category</th>
							<th>Time allocated (Hrs)</th>
							<th>Actual Time (Hrs)</th>
							<th>Creation Date</th>
							<th>Scheduled Date</th>
                                                        <th>Due Date</th>
                                                        <th>Customer Name</th>
                                                        <th>External ID</th>
							<th>Base Cost</th>
                                                        <th>Estimated Total Cost</th>
                                                        <th>Base Charge</th>
                                                        <th>Estimated Total Revenue</th>
							<th>No of interruptions logged for this task</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								$division = get_user_division($row['user_id']);
								if($division){
									$division = $division;
								} else {
									$division = "N/A";
								}
								$department = get_user_department($row['user_id']);
								if($department){
									$department = $department;
								} else {
									$department = "N/A";
								}
								$color_name = $row['name'];
								if($color_name){
									$color_name = $color_name;
								} else {
									$color_name = "N/A";
								}
								$category_name =$row['category_name'];
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
									<td><?php echo $row['owner_first_name']." ".$row['owner_last_name']; ?></td>
									<td><?php echo $row['allocated_user_first_name']." ".$row['allocated_user_last_name']; ?></td>
									<td><?php echo $department;?></td>
									<td><?php echo $division;?></td>
									<td><?php echo $row['task_priority'];?></td>
									<td><?php echo $color_name; ?></td>
									<td><?php if($row['project_title']){ echo $row['project_title']; } else { echo "N/A"; } ?></td>
                                                                        <td><?php echo $row['task_status_name']; ?></td>
                                                                        <td><?php echo $category_name; ?></td>
									<td><?php echo $sub_category_name;?></td>
									<td><?php echo round($row['task_time_estimate']/60,2);?></td>
									<td><?php echo round($row['task_time_spent']/60,2);?></td>
									<td><?php echo date($site_setting_date,strtotime(toDateNewTime($row['task_added_date'])));?></td>
									<td><?php if($row['task_scheduled_date']!='0000-00-00'){ echo date($site_setting_date,strtotime($row['task_scheduled_date'])); } else { echo "N/A"; } ?></td>
									<td><?php if($row['task_due_date']!='0000-00-00'){ echo date($site_setting_date,strtotime($row['task_due_date'])); } else { echo 'N/A'; }?></td>
                                                                        <td><?php echo $row['customer_name'];?></td>
                                                                        <td><?php echo $row['external_id'];?></td>
                                                                        <td><?php echo $row['cost_per_hour']; ?></td>
                                                                        <td><?php echo $row['cost'];?></td>
                                                                        <td><?php echo $row['charge_out_rate'];?></td>
                                                                        <td><?php echo $row['estimated_total_charge'];?></td>
									<td><?php echo interruption_by_task($row['task_id']);?></td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
				