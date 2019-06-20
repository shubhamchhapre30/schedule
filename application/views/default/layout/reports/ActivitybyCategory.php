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
							<th>Category</th>
							<th>User</th>
							<th>Start date</th>
							<th>To date</th>
							<th>Time allocated (Hrs)</th>
							<th>Time spent (Hrs)</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								$division = get_user_division($row['user_id']);
								$department = get_user_department($row['user_id']);
								?>
								<tr>
									<td><?php $category_name = $row['category_name']; if($category_name){ echo $category_name; } else { echo 'N/A'; }?></td>
									<td><?php echo $row['first_name'].' '.$row['last_name']; ?></td>
									<td><?php if($from_date){ echo date($site_setting_date,strtotime($from_date)); } else { echo "N/A"; }?></td>
									<td><?php if($to_date){ echo date($site_setting_date,strtotime($to_date)); } else { echo "N/A"; }?></td>
									<td><?php echo round($row['task_time_estimate']/60,2);?></td>
									<td><?php echo round($row['task_time_spent']/60,2);?></td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
			