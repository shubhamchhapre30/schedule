<?php 
            $task_status_completed_id = $this->config->item('completed_id');
                    if($project_id!=0){
                        $total_task = $this->project_model->get_all_project_task($project_id);
                        $total_chargeable_task = $this->project_model->get_all_chargeable_project_task($project_id);
                    }

                        $estimated_revenue = 0;
                        $estimated_cost = 0;
                        $committed_revenue = 0;
                        $committed_cost = 0;
                        $non_chargeable_category = get_non_chargeable_category($this->session->userdata('company_id'));
                        $non_billable_time = 0;
                        $non_billable_cost = 0;
                        $estimated_profit = 0;
                        $estimated_margin = 0;
                        $committed_profit = 0;
                        $committed_margin = 0;
                        $total_estimated_minute = 0;
                        if(!empty($total_chargeable_task)){
                            foreach($total_chargeable_task as $task){
                                $total_estimated_minute += $task['task_time_estimate'];
                                $estimated_revenue += $task['estimated_total_charge'];
                                if($task['task_status_id']==$task_status_completed_id){
                                    $committed_revenue += $task['actual_total_charge'];
                                }
                            }
                        }
                        if(!empty($total_task)){
                            foreach($total_task as $task){
                               // $total_estimated_minute += $task['task_time_estimate'];
                                $employee_rate = $task['cost_per_hour'];
                               // $estimated_revenue += $task['estimated_total_charge'];
                                $estimated_cost += round(($task['task_time_estimate']*$employee_rate)/60,2);
                                if($task['task_status_id']==$task_status_completed_id){
                                  //  $committed_revenue += $task['actual_total_charge'];
                                    $committed_cost += round(($task['task_time_spent']*$employee_rate)/60,2);
                                }
                            }

                            foreach ($total_task as $task){
                                $employee_rate = $task['cost_per_hour'];
                                if(!empty($non_chargeable_category)){
                                    foreach($non_chargeable_category as $category){
                                        if($category->category_id == $task['task_category_id'] && $task['task_status_id']==$task_status_completed_id){
                                            $non_billable_cost += round(($task['task_time_spent']*$employee_rate)/60,2);
                                            $non_billable_time += $task['task_time_spent'];
                                        }
                                    }
                                }
                            }
                             if($project_fixed_price !='0'){
                                 $estimated_revenue = $project_fixed_price;
                             }else if($project_base_rate !='0'){
                                 $estimated_revenue = round(($total_estimated_minute * $project_base_rate)/60,2);
                             }else{
                                 $estimated_revenue = $estimated_revenue;
                             }

//                            $estimated_profit = $estimated_revenue-$estimated_cost;
                            if($estimated_revenue==0){
                                $estimated_profit = $estimated_revenue-$estimated_cost;
                                $estimated_margin = 0;
                            }else{
                                $estimated_profit = $estimated_revenue-$estimated_cost;
                                $estimated_margin = round(($estimated_profit/$estimated_revenue)*100,2);
                            }


                            $committed_revenue = $committed_revenue;
                            $committed_cost = $committed_cost;
//                            $committed_profit = $committed_revenue - $committed_cost;
                             if($committed_revenue==0){
                                 $committed_profit = $committed_revenue - $committed_cost;
                                 $committed_margin = 0;
                             }else{
                                 $committed_profit = $committed_revenue - $committed_cost;
                                 $committed_margin = round(($committed_profit/ $committed_revenue)*100, 2);

                             }

                            $hours = intval($non_billable_time/60);
                            $minutes = $non_billable_time - ($hours * 60);

                            $total_non_billable_time = $hours.".".$minutes."h";
                            $non_billable_cost = $non_billable_cost;
                            $non_billable_time = $total_non_billable_time;
                        }
   
    ?> 
                                            <div class="row">
                                                <div class="col-md-12" style="padding-top:5px">
                                                    <input type="hidden" class = "main_project" name="project_id" id="project_id" value="" />
                                                    <button type="button" class="btn blue btn-new unsorttd pull-right" id="finance_refresh" name="finance_refresh" style="margin-right: 8px !important;">Refresh</button>
                                                </div>
                                                <div class="col-md-12" style="padding-top: 5px;">
                                                    <label class="control-label col-md-6" ><b>Estimated Revenue</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($estimated_revenue, '.') == false) { echo $estimated_revenue.'.00';}else {echo $estimated_revenue;} ?> </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Estimated Cost</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($estimated_cost, '.') == false) { echo $estimated_cost.'.00';}else {echo $estimated_cost;} ?></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Estimated Profit</b></label>
                                                    <label class="control-label col-md-3" ><?php echo $estimated_margin.'%'; ?></label>
                                                    <label class="control-label col-md-3" <?php if($estimated_profit<0){ echo "style='color:red'";}?> ><?php echo $this->session->userdata('currency');?><?php if (strpos($estimated_profit, '.') == false) { echo $estimated_profit.'.00';}else {echo $estimated_profit;}?></label>
                                                </div>
                                                <div class="col-md-12" style="padding-top:15px;">
                                                    <label class="control-label col-md-6" ><b>Committed Revenue</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($committed_revenue, '.') == false) { echo $committed_revenue.'.00';}else {echo $committed_revenue;} ?></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Non Billable Time / Cost</b></label>
                                                    <label class="control-label col-md-1" ></label>
                                                    <label class="control-label col-md-5" ><?php echo $non_billable_time .' /  '?><?php echo $this->session->userdata('currency');?><?php if (strpos($non_billable_cost, '.') == false) { echo $non_billable_cost.'.00';}else {echo $non_billable_cost;} ?> </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Committed Cost</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <label class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><?php if (strpos($committed_cost, '.') == false) { echo $committed_cost.'.00';}else {echo $committed_cost;} ?> </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Committed Profit</b></label>
                                                    <label class="control-label col-md-3" ><?php echo $committed_margin.'%';?></label>
                                                    <label class="control-label col-md-3" <?php if($committed_profit<0){ echo "style='color:red'";}?> ><?php echo $this->session->userdata('currency');?><?php if (strpos($committed_profit, '.') == false) { echo $committed_profit.'.00';}else {echo $committed_profit;}?></label>
                                                </div>
                                                <div class="col-md-12" style="padding-top:10px;">
                                                    <label class="control-label col-md-6" ><b>Project base hourly rate</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <span class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><a herf="#" data-name="project_base_rate" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount"  data-type="text" data-pk="1" id="edit_project_charge_rate"><?php if($project_base_rate!='0'){echo $project_base_rate;}?></a></span>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="control-label col-md-6" ><b>Project Fixed price</b></label>
                                                    <label class="control-label col-md-3" ></label>
                                                    <span class="control-label col-md-3" ><?php echo $this->session->userdata('currency');?><a herf="#" data-name="project_fixed_price" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount"  data-type="text" data-pk="1" id="edit_project_fixed_charge_rate"><?php if($project_fixed_price!='0'){echo $project_fixed_price;}?></a></span>
                                                </div>
                                            </div>

<script>
        $(document).ready(function(){
            $('#edit_project_charge_rate').editable({
                    url: SIDE_URL + "project/update_project_rate",
                    params:{project_id : $("#check_project_id").val()},
                    type: "post",
                    pk: 1,
                    mode: "popup",
                    showbuttons: !0,
                    validate: function(e) {
                            var s = /^[0-9 .]*$/;
                            return s.test($.trim(e)) ? void 0 : "Please enter only number."
                    },
                    success: function() {}  
                });      
                
                $('#edit_project_fixed_charge_rate').editable({
                    url: SIDE_URL + "project/update_project_rate",
                    params:{project_id : $("#check_project_id").val()},
                    type: "post",
                    pk: 1,
                    mode: "popup",
                    showbuttons: !0,
                    validate: function(e) {
                            var s = /^[0-9 .]*$/;
                            return s.test($.trim(e)) ? void 0 : "Please enter only number."
                    },
                    success: function() {}  
                }); 
        })

</script>