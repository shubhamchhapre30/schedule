<?php 
	$theme_url = base_url().getThemeName();
	date_default_timezone_set($this->session->userdata("User_timezone"));
?>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
			
			<script type="text/javascript">
				$(document).ready(function(){
				 
				  $('#filtertab1').dataTable({
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
							<th>User</th>
							<th class="hidden-480">Divisions</th>
							<th class="hidden-480">Departments</th>
							<th class="hidden-480">Last login date / time</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						
						//date_default_timezone_set($this->session->userdata("User_timezone"));
						if(isset($reports_data) && $reports_data!= ''){
							foreach($reports_data as $row){
								$login_dt = date($site_setting_date." H:i:s",strtotime(toDateNewTime($row['user_login_date'])));
								
								$division = get_user_division($row['user_id']);
								$department = get_user_department($row['user_id']);
								?>
								<tr>
									<td><?php echo $row['first_name'].' '.$row['last_name'];?></td>
									<td class="hidden-480"><?php if($division){ echo $division; }else{ echo 'N/A'; } ?></td>
									<td class="hidden-480"><?php if($department){ echo $department; }else{ echo 'N/A'; }?></td>
									<td class="hidden-480"><?php echo $login_dt;?></td>
								</tr>
								<?php
							}
						} 
						//date_default_timezone_set("UTC");
						
						
						?>
						
						
					</tbody>
				</table>
			