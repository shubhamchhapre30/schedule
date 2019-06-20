<?php if(isset($projects) && $projects!=''){?>
                    <div class="" >
                                    <table class="table table-striped table-hover table-condensed flip-content" id="projectTable">
                                    <thead class="flip-content">
                                      <tr>
                                            <th>Project Name</th>
                                            <th>Status</th>
                                            <th>Start date</th>
                                            <th>End Date</th>
                                            <?php if($this->session->userdata('pricing_module_status')=='1'){?>
                                            <th>Estimated Cost</th>
                                            <th>Estimated Revenue</th>
                                            <th>Committed Revenue</th>
                                            <?php }?>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($projects) && $projects!=''){
                                                foreach ($projects as $row){ ?>
                                            <tr>    
                                                <td>
                                                    <form method="POST" style="margin: 0px !important;" action="<?php echo site_url('project/editProject');?>" name="myProject_<?php echo $row['project_id'];?>" id="myProject_<?php echo $row['project_id'];?>">
                                                        <input type="hidden" name="project_id" id="project_id" value="<?php echo $row['project_id'];?>" />
                                                    </form>
                                                    <a href="javascript:void(0)" onclick="callMyproject(<?php echo $row['project_id'];?>);"><?php echo $row['project_title'];?></a>
                                                </td>
                                                <td><?php echo $row['project_status'];?></td>
                                                <td><?php echo date($site_setting_date,strtotime($row['project_start_date']));?></td>
                                                <td><?php echo date($site_setting_date,strtotime($row['project_end_date']));?></td>
                                                <?php   if($this->session->userdata('pricing_module_status')=='1'){
                                                        $estimated_revenue = 0;
                                                        $estimated_cost = 0;
                                                        $committed_revenue = 0;
                                                        $total_task = $this->project_model->get_all_project_task($row['project_id']);
                                                        if(!empty($total_task)){
                                                        foreach($total_task as $task){
                                                            $employee_rate = $task['cost_per_hour'];
                                                            $estimated_cost += round(($task['task_time_estimate']*$employee_rate)/60,2);
                                                            $estimated_revenue += $task['estimated_total_charge'];
                                                            if($task['task_status_id']==$completed){
                                                                   $committed_revenue += $task['actual_total_charge'];
                                                            }
                                                        }}?>
                                                <td><label class="control-label"><?php echo $this->session->userdata('currency');?></label><?php echo $estimated_cost; ?></td>
                                                <td><label class="control-label"><?php echo $this->session->userdata('currency');?></label><?php echo $estimated_revenue;?></td>
                                                <td><label class="control-label"><?php echo $this->session->userdata('currency');?></label><?php echo $committed_revenue;?></td>
                                                <?php }?>
                                            
                                            
                                            </tr>
                                            <?php }}?>

                                    </tbody>
                                    </table>
                    </div>
<?php }?>