<?php 
$task_status = get_taskStatus($this->session->userdata('company_id'),'Active'); 
$color_codes = get_user_color_codes($this->session->userdata('user_id'));
$swimlanes = get_user_swimlanes(get_authenticateUserID());
?><table class="table table-striped table-hover table-condensed flip-content margin5" id="taskTable">
				<thead class="flip-content">
				  <tr>
					<th>Task Name</th>
					<th>Status</th>
					<th>Scheduled date</th>
					<th>Due Date</th>
                                        <th>Allocated to</th>
                                        <th>Owner</th>
                                        <th>Action</th>
					
				  </tr>
				</thead>
                                <tbody id="task_data">
                                    <?php 
                                        if($tasks){
                                            foreach ($tasks as $row){
                                               $current_date=date('Y-m-d');
                                                if (strpos($row['task_id'],'child') !== false) {
                                                        $chk = "0";
                                                } else {
                                                        $chk = "1";
                                                }
                                                $due_date = check_task_exist_today($row['task_id']);
                                                if($due_date==$current_date){
                                                    
                                                }else{
                                                if($row['frequency_type']=='recurrence'){
                                                    $occurence_start_date= get_task_occurence_date($row['task_id']); 
                                                    //echo $occurence_start_date;
                                                    
                                                    $date1=date_create($current_date);
                                                    $date2=date_create($occurence_start_date);
                                                    $diff=date_diff($date1,$date2);
                                                    $days = $diff->d;
                                                    $task_id = "child_".$row['task_id']."_".$days;
                                                    $is_master_deleted = chk_master_task_id_deleted($row['task_id']);
                                                       
                                                    $row['master_task_id']=$row['task_id'];
                                                    $row['task_id'] = $task_id;
                                                    $row['task_orig_scheduled_date'] = $current_date;
                                                    $row['task_orig_due_date'] = $current_date;
                                                    $row['task_due_date']=$current_date;
                                                    $row['task_scheduled_date']=$current_date;
                                                     $jsonarray=array(
                                                    "task_status" =>$task_status,
                                                    "user_colors" =>$color_codes,
                                                    "user_swimlanes" =>$swimlanes,
                                                    "task_id" =>$row['task_id'],
                                                    "locked_due_date" => $row['locked_due_date'],
                                                    "task_due_date" =>date("m-d-Y",strtotime($current_date)),
                                                    "task_scheduled_date" =>date("m-d-Y",strtotime($current_date)),
                                                    "date" =>strtotime($current_date), 
                                                    "active_menu" =>'from_customer',
                                                    "start_date" =>'',
                                                    "end_date" =>'',
                                                    "master_task_id" =>$row['master_task_id'],
                                                    "is_master_deleted" =>'',
                                                    "chk_watch_list" =>'',
                                                    "task_owner_id" =>$row['task_owner_id'],
                                                    "completed_depencencies" =>'',
                                                    "color_menu" =>'',
                                                    "swimlane_id" =>'',
                                                    "task_status_id" => $row['task_status_id'],
                                                    "before_status_id" => '',
                                                    "customer_id" =>$row['customer_id']
                                                );
                                                      $jsonarray['is_master_deleted'] = $is_master_deleted;
                                                //$is_master_deleted=$tasks['tm'];
                                                ?>
                                       
                                                <tr id="listtask_<?php echo $row['task_id'];?>">
                                                    
                                                    <td id="remove_context"><div id="task_<?php echo $row['task_id'];?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');"><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $row['task_title'];?></a></div></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $row['task_status_name'];?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo date($site_setting_date,strtotime($current_date));?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo date($site_setting_date,strtotime($current_date));?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $row['allocated_user_name'];?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo usernameById($row['task_owner_id']); ?></a></td>
                                                    <td>
                                                        <input type="hidden" name="child_task_id" id="child_task_id" value="<?php echo $row['task_id'];?>"/>
                                                        <input type="hidden" id="task_data_<?php echo $row['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($row)); ?>" />
                                                        <a href="javascript:void(0);" onclick="deleteTask('<?php echo $row['task_id']?>');" id="delete_task_<?php echo $row['task_id']?>"> <i class="icon-trash tmsticn" style="transform: scale(0.75);"></i> </a>  
                                                    </td>
                                            
                                                </tr>
                                                <?php }else{
                                                    
                                                if($row['task_due_date'] == '0000-00-00')
                                                    $due_date1 = '';
                                                else 
                                                    $due_date1 =date("m-d-Y",strtotime($row['task_due_date']));
                                                if($row['task_scheduled_date'] == '0000-00-00')
                                                    $scheduled_date = '';
                                                else 
                                                    $scheduled_date =date("m-d-Y",strtotime($row['task_scheduled_date']));
                                                    $jsonarray=array(
                                                    "task_status" =>$task_status,
                                                    "user_colors" =>$color_codes,
                                                    "user_swimlanes" =>$swimlanes,
                                                    "task_id" =>$row['task_id'],
                                                    "locked_due_date" => $row['locked_due_date'],
                                                    "task_due_date" =>$due_date1,
                                                    "task_scheduled_date" =>$scheduled_date,
                                                    "date" =>strtotime($current_date), 
                                                    "active_menu" =>'from_customer',
                                                    "start_date" =>'',
                                                    "end_date" =>'',
                                                    "master_task_id" =>$row['master_task_id'],
                                                    "is_master_deleted" =>'',
                                                    "chk_watch_list" =>'',
                                                    "task_owner_id" =>$row['task_owner_id'],
                                                    "completed_depencencies" =>'',
                                                    "color_menu" =>'',
                                                    "swimlane_id" =>'',
                                                    "task_status_id" => $row['task_status_id'],
                                                    "before_status_id" => '',
                                                    "customer_id" =>$row['customer_id']
                                                );
                                                     ?>
                                                    <tr id="listtask_<?php echo $row['task_id'];?>">
                                                           
                                                        <td id="remove_context"><div id="task_<?php echo $row['task_id'];?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');"><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"><?php echo $row['task_title'];?></a></div></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"><?php echo $row['task_status_name'];?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"><?php if($row['task_scheduled_date']!='0000-00-00'){echo date($site_setting_date,strtotime($row['task_scheduled_date']));}else{echo '-';}?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"><?php if($row['task_due_date']!='0000-00-00'){echo date($site_setting_date,strtotime($row['task_due_date']));}else{echo '-';}?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"><?php echo $row['allocated_user_name'];?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"><?php echo usernameById($row['task_owner_id']); ?></a></td>
                                                            <td>
                                                                <input type="hidden" id="task_data_<?php echo $row['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($row)); ?>" />
                                                                <a href="javascript:void(0);" onclick="deleteTask('<?php echo $row['task_id']?>');"> <i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>  
                                                            </td>
                                            
                                                     </tr>
                                      
                                        <?php }}?>
                                        <?php }}else{?>
                                        <tr><td colspan="6">No tasks found.</td></tr>
                                        <?php }?>
                                 </tbody>
</table>
