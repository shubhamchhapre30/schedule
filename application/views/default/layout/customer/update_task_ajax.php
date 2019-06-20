
<?php 
$task_status = get_taskStatus($this->session->userdata('company_id'),'Active'); 
$color_codes = get_user_color_codes($this->session->userdata('user_id'));
$swimlanes = get_user_swimlanes(get_authenticateUserID());
                                        if($tasks){
                                            
                                                if (strpos($tasks['task_id'],'child') !== false) {
                                                        $chk = "0";
                                                } else {
                                                        $chk = "1";
                                                }
                                                if($tasks['task_due_date'] == '0000-00-00')
                                                    $due_date1 = '';
                                                else 
                                                    $due_date1 =date("m-d-Y",strtotime($tasks['task_due_date']));
                                                
                                                if($tasks['task_scheduled_date'] == '0000-00-00')
                                                    $scheduled_date = '';
                                                else 
                                                    $scheduled_date =date("m-d-Y",strtotime($tasks['task_scheduled_date']));
                                                 $jsonarray=array(
                                                    "task_status" =>$task_status,
                                                    "user_colors" =>$color_codes,
                                                    "user_swimlanes" =>$swimlanes,
                                                    "task_id" =>$tasks['task_id'],
                                                    "locked_due_date" => $tasks['locked_due_date'],
                                                    "task_due_date" =>$due_date1,
                                                    "task_scheduled_date" =>$scheduled_date,
                                                    "date" =>'', 
                                                    "active_menu" =>'from_customer',
                                                    "start_date" =>'',
                                                    "end_date" =>'',
                                                    "master_task_id" =>$tasks['master_task_id'],
                                                    "is_master_deleted" =>'',
                                                    "chk_watch_list" =>'',
                                                    "task_owner_id" =>$tasks['task_owner_id'],
                                                    "completed_depencencies" =>'',
                                                    "color_menu" =>'',
                                                    "swimlane_id" =>'',
                                                    "task_status_id" => $tasks['task_status_id'],
                                                    "before_status_id" => ''
                                                );
                                                if($tasks['frequency_type']=='recurrence'){
                                                    $occurence_start_date= get_task_occurence_date($tasks['task_id']); 
                                                    //echo $occurence_start_date;
                                                    $current_date=date('Y-m-d');
                                                    $date1=date_create($current_date);
                                                    $date2=date_create($occurence_start_date);
                                                    $diff=date_diff($date1,$date2);
                                                    $days = $diff->d;
                                                    $task_id = "child_".$tasks['task_id']."_".$days;
                                                    $jsonarray['task_id'] = $task_id;
                                                    $jsonarray['master_task_id'] = $tasks['task_id'];
                                                    $is_master_deleted = chk_master_task_id_deleted($tasks['task_id']);
                                                    $jsonarray['master_task_id']=$tasks['task_id'];
                                                    $tasks['master_task_id']=$tasks['task_id'];
                                                    $tasks['task_id'] = $task_id;
                                                    $tasks['task_orig_scheduled_date']=$current_date;
                                                    $tasks['task_orig_due_date'] = $current_date;
                                                    $tasks['task_due_date']=$current_date;
                                                    $tasks['task_scheduled_date']=$current_date;
                                                    $jsonarray['is_master_deleted'] = $is_master_deleted;
                                                    $jsonarray['task_due_date'] = date("m-d-Y",strtotime($current_date));
                                                    $jsonarray['task_scheduled_date'] = date("m-d-Y",strtotime($current_date));
                                                //$is_master_deleted=$tasks['tm'];
                                                ?>
                                       
                                                <tr id="listtask_<?php echo $tasks['task_id'];?>">
                                                   
                                                    <td><div id="task_<?php echo $tasks['task_id'];?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');"><a onclick="open_seris(this,'<?php echo $tasks['task_id'];?>','<?php echo $tasks['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $tasks['task_title'];?></a></div></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $tasks['task_id'];?>','<?php echo $tasks['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $tasks['task_status_name'];?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $tasks['task_id'];?>','<?php echo $tasks['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo date($site_setting_date,strtotime($current_date));?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $tasks['task_id'];?>','<?php echo $tasks['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo date($site_setting_date,strtotime($current_date));?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $tasks['task_id'];?>','<?php echo $tasks['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $tasks['allocated_user_name'];?></a></td>
                                                    <td><a onclick="open_seris(this,'<?php echo $tasks['task_id'];?>','<?php echo $tasks['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo usernameById($tasks['task_owner_id']); ?></a></td>
                                                    <td>
                                                        <input type="hidden" name="child_task_id" id="child_task_id" value="<?php echo $tasks['task_id'];?>"/>
                                                        <input type="hidden" id="task_data_<?php echo $tasks['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($tasks)); ?>" />
                                                        <a href="javascript:void(0);" onclick="deleteTask('<?php echo $tasks['task_id']?>');" id="delete_task_<?php echo $tasks['task_id']?>"> <i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>  
                                                    </td>
                                            
                                                </tr>
                                        <?php }else{?>
                                                    <tr id="listtask_<?php echo $tasks['task_id'];?>">
                                                            
                                                        <td><div id="task_<?php echo $tasks['task_id'];?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');"><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $tasks['task_id'];?>','<?php echo $chk;?>');"><?php echo $tasks['task_title'];?></a></div></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $tasks['task_id'];?>','<?php echo $chk;?>');"><?php echo $tasks['task_status_name'];?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $tasks['task_id'];?>','<?php echo $chk;?>');"><?php if($tasks['task_scheduled_date']!='0000-00-00'){echo date($site_setting_date,strtotime($tasks['task_scheduled_date']));}else{echo "-";}?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $tasks['task_id'];?>','<?php echo $chk;?>');"><?php if($tasks['task_due_date'] !='0000-00-00'){echo date($site_setting_date,strtotime($tasks['task_due_date']));}else{echo '-';}?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $tasks['task_id'];?>','<?php echo $chk;?>');"><?php echo $tasks['allocated_user_name'];?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $tasks['task_id'];?>','<?php echo $chk;?>');"><?php echo usernameById($tasks['task_owner_id']); ?></a></td>
                                                            <td>
                                                                <input type="hidden" id="task_data_<?php echo $tasks['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($tasks)); ?>" />
                                                                
                                                                <a href="javascript:void(0);" onclick="deleteTask('<?php echo $tasks['task_id']?>');"> <i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>  
                                                            </td>
                                            
                                                     </tr>
                                      
                                        <?php }}?>
