<?php 
	$theme_url = base_url().getThemeName();
	date_default_timezone_set($this->session->userdata("User_timezone"));
?>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
			
			<script type="text/javascript">
				$(document).ready(function(){
				 
				  $('#filtertab1').dataTable( {
                                  "order": [],
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
							<th width="30%">Task Name</th>
                                                        <th>Task Status</th>
							<th>Task Creation Date</th>
                                                        <th>Due Date</th>
							<th>Scheduled Date</th>
                                                        <th>Allocated To</th>
                                                </tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								?>
								<tr>
									<td><?php echo $row['task_title']; ?></td>
                                                                        <td><?php echo $row['task_status_name']; ?></td>
									<td><?php echo date($site_setting_date,strtotime(toDateNewTime($row['task_added_date'])));?></td>
									<td><?php if($row['task_due_date']!='0000-00-00'){ echo date($site_setting_date,strtotime($row['task_due_date'])); } else { echo 'N/A'; }?></td>
                                                                        <td><?php if($row['task_scheduled_date']!='0000-00-00'){ echo date($site_setting_date,strtotime($row['task_scheduled_date'])); } else { echo "N/A"; } ?></td>
                                                                        <td><?php echo $row['allocated_user_first_name']." ".$row['allocated_user_last_name']; ?></td>
                                                                </tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
				