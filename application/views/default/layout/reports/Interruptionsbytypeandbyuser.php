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
							<th>Task Category</th>
							<th>User</th>
                                                        <th>Customer Name</th>
                                                        <th>External ID</th>
							<th>Interruption</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								$category_name = $row['category_name'];
								if($category_name){
									$category_name = $category_name;
								} else {
									$category_name = "N/A";
								}
								?>
								<tr>
									<td><?php echo $row['task_id'];?></td>
									<td><?php echo $row['task_title'];?></td>
									<td><?php echo $category_name;?></td>
									<td><?php echo $row['first_name'].' '.$row['last_name'];?></td>
                                                                        <td><?php echo $row['customer_name'];?></td>
                                                                        <td><?php echo $row['external_id'];?></td>
									<td><?php echo $row['interruption']; ?></td>
									<td><?php echo date($site_setting_date." H:i:s",strtotime(toDateNewTime($row['date_added']))); ?>	</td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
			