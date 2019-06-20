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
							<th>Project</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Project Section</th>
							<th>User</th>
                                                        <th>Customer Name</th>
                                                        <th>External ID</th>
							<th>Time Allocated (Hrs)</th>
							<th>Time Spent(Hrs)</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								?>
								<tr>
									<td><?php echo $row['project_title'];?></td>
									<td><?php if($from_date){ echo date($site_setting_date,strtotime($from_date)); } else { echo "N/A"; }?></td>
									<td><?php if($to_date){ echo date($site_setting_date,strtotime($to_date)); } else { echo "N/A"; }?></td>
									<td><?php if($row['section_name']){ echo $row['section_name']; } else { echo "N/A"; }?></td>
									<td><?php echo $row['first_name'].' '.$row['last_name'];?></td>
                                                                        <td><?php echo $row['customer_name'];?></td>
                                                                        <td><?php echo $row['external_id'];?></td>
									<td><?php echo round($row['allocationtime']/60,2);?></td>
									<td><?php echo round($row['actualtime']/60,2); ?></td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
			