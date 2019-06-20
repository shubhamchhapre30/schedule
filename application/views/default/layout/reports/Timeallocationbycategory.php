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
							<th>Sub category</th>
							<th>User</th>
							<th>Day</th>
							<th>Time Allocated (Hrs)</th>
							<th>Time Spent (Hrs)</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								?>
								<tr>
									<td><?php echo $row['category_name'];?></td>
									<td><?php echo $row['sub_category_name'];?></td>
									<td><?php echo $row['first_name'].' '.$row['last_name']; ?></td>
									<td><?php echo date($site_setting_date,strtotime($row['task_true_date']));?></td>
									<td><?php echo $allocated_time = round($row['allocationtime']/60,2);?></td>
									<td><?php echo round($row['spenttime']/60,2);?></td>
								</tr>
								<?php
							}
						} ?>
						
						
					</tbody>
				</table>
