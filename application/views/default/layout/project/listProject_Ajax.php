<table id = "filtertab_pro" class="table table-striped table-hover table-condensed flip-content results">
                    <thead class="flip-content">
                      <tr>
                      	<th>Project  Name</th>
                      	<?php if($this->session->userdata['customer_module_activation']){
                      		echo'<th>Customer</th>';
                      	}?>
                      	<th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="center" width="25%"></th>
                        <th width="10%"></th>
                      </tr>
                    </thead>
                    <tbody id="filterView">
                    	<?php
                    	if($projects){
                    		$task_status_completed_id = $this->config->item('completed_id');
							$default_format = $this->config->item('company_default_format');
                    		foreach($projects as $prj){
                    			$encoded_project_id = $this->encrypt->encode($prj->project_id);
                    			if($prj->project_start_date!= '0000-00-00' ){
									//$project_start_date = date($site_setting_date,strtotime($prj->project_start_date));
									$hidden_project_start_date = date("Y-m-d",strtotime($prj->project_start_date));
								} else {
									//$project_start_date = "N/A";
									$hidden_project_start_date = "N/A";
								}
								
								if($prj->project_end_date!= '0000-00-00' ){
									//$project_end_date = date($site_setting_date,strtotime($prj->project_end_date));
									$hidden_project_end_date = date("Y-m-d",strtotime($prj->project_end_date));
								} else {
									//$project_end_date = "N/A";
									$hidden_project_end_date = "N/A";
								}
                    			
								if($prj->project_id!=''){
											$tot_task = get_total_task($prj->project_id,'all',$task_status_completed_id);
											$my_task = get_my_task($prj->project_id,'all',$task_status_completed_id);
											$tot_upcoming_task = get_total_upcoming_task($prj->project_id,'all',$task_status_completed_id);
											$my_upcoming_task = get_my_upcoming_task($prj->project_id,'all',$task_status_completed_id);
											$tot_today_task = get_tot_today_task($prj->project_id,'all',$task_status_completed_id);
											$my_today_task = get_my_today_task($prj->project_id,'all',$task_status_completed_id);
											$tot_overdue_task = get_tot_overdue_task($prj->project_id,'all',$task_status_completed_id);
											$my_overdue_task = get_my_overdue_task($prj->project_id,'all',$task_status_completed_id);
											 }else{
											 	
											$tot_task = 0;
											$my_task = 0;
											$tot_upcoming_task = 0;
											$my_upcoming_task = 0;
											$tot_today_task = 0;
											$my_today_task = 0;
											$tot_overdue_task = 0;
											$my_overdue_task = 0;
											 	
											 }
								 ?>
								
                    			<tr>
                    				<td><a onclick="callProject('<?php echo $encoded_project_id; ?>');" style="display: block;" href="javascript:void(0);"><?php echo $prj->project_title; ?></a></td>
                    				
                    				<?php 
                    				if($this->session->userdata['customer_module_activation']){
                    					echo "<td>";
                    				if(!empty($prj->customer_name)){
                    					echo $prj->customer_name; 

                    				} else{
                    					echo '-';
                    				}
                    				echo "</td>";
                    				}
                    				?>
                                                                    <?php  $zero='0';
                                                             $yellow=$tot_upcoming_task?'yellow':'light_cstm';
                                                             $green=$tot_today_task?'green':'light_cstm';
                                                             $red=$tot_overdue_task?'red':'light_cstm';
                                                
                                                ?>
                    				<td><?php echo str_replace("_"," ",$prj->project_status); ?></td>
                    				<td><span class="hidden"><?php echo $hidden_project_start_date;?></span><?php echo date($default_format,strtotime(str_replace(array("/"," ",","), "-", $prj->project_start_date))); ?></td>
			                        <td><span class="hidden"><?php echo $hidden_project_end_date;?></span><?php echo date($default_format,strtotime(str_replace(array("/"," ",","), "-", $prj->project_end_date))); ?></td>
                                                <td class="center "  > <span class="tasklbl pill_cstm"><span>Upcoming</span><span class="pill_num <?=$yellow?> "><?php if($tot_upcoming_task) {echo $tot_upcoming_task;  }  else {echo $zero;}?></span></span>
                                                    <span class="tasklbl pill_cstm"><span>Today</span><span class="pill_num <?=$green?> "><?php if($tot_today_task){  echo $tot_today_task; } else {echo $zero;}?> </span></span>
                                                    <span class="tasklbl pill_cstm"><span>Overdue</span><span class="pill_num <?=$red?> "><?php if($tot_overdue_task){ echo $tot_overdue_task; } else {echo $zero;}?></span></span></td>
			                         <td>
                                                    <form method="POST" style="margin: 0px !important;" action="<?php echo site_url('project/editProject');?>" name="myForm_<?php echo $encoded_project_id; ?>" id="myForm_<?php echo $encoded_project_id; ?>">
							<input type="hidden" name="project_id" id="project_id" value="<?php echo $encoded_project_id;?>" />
                                                    </form>
			                         	<?php if($prj->project_added_by == get_authenticateUserID() || $this->session->userdata('is_owner') == 1){ ?>
                                                        <a onclick="callProject('<?php echo $encoded_project_id; ?>');" href="javascript:void(0);"> <i class="icon-pencil prjcstmicn" style="transform: scale(0.75);"></i> </a> 
										 <a href="javascript:void(0);" onclick="delete_project('<?php echo $encoded_project_id; ?>','<?php echo $prj->project_id; ?>','<?php echo $prj->project_title; ?>');" id="delete_project_<?php echo $prj->project_id; ?>"> <i class="icon-trash prjcstmicn" style="transform: scale(0.75);"></i> </a>
                                                 <?php } if($this->session->userdata('is_customer_user') == '0') { ?>
                                                                                 <a onclick="copyProject('<?php echo $prj->project_id; ?>','<?php echo $prj->project_title; ?>');" href="javascript:void(0);" class="tooltips" title="Create Copy" id="copy_project_<?php echo $prj->project_id; ?>"> <i class="icon-copy prjcstmicn" style="transform: scale(0.75);"></i> </a>
                                                 <?php } ?>
                                                 </td>
					  </tr>
			                     
                    	<?php	} }?>
                    </tbody>
                  </table>