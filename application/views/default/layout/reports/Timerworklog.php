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
							<th>Task Name</th>
                                                        <th>Task Status</th>
                                                        <th>User</th>
                                                        <th>Project Name</th>
							<th>Customer Name</th>
                                                        <th>External ID</th>
							<th>Interruption</th>
                                                        <th>Comment</th>
                                                        <th>Base Cost</th>
                                                        <th>Estimated Total Cost</th>
                                                        <th>Base Charge</th>
                                                        <th>Estimated Total Revenue</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								
								?>
								<tr>
									<td><?php echo $row['task_id'];?></td>
									<td><?php echo $row['task_title'];?></td>
                                                                        <td><?php echo $row['task_status_name'];?></td>
                                                                        <td><?php echo $row['first_name'].' '.$row['last_name'];?></td>
                                                                        <td><?php echo $row['project_title'];?></td>
                                                                        <td><?php echo $row['customer_name'];?></td>
                                                                        <td><?php echo $row['external_id'];?></td>
									<td><?php echo $row['interruption']; ?></td>
                                                                        <td><?php echo $row['comment'];?></td>
                                                                        <td><?php echo $row['cost_per_hour']; ?></td>
                                                                        <td><?php echo $row['cost'];?></td>
                                                                        <td><?php echo $row['charge_out_rate'];?></td>
                                                                        <td><?php echo $row['estimated_total_charge'];?></td>
									<td><?php echo date($site_setting_date." H:i:s",strtotime(toDateNewTime($row['date_added']))); ?>	</td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
			