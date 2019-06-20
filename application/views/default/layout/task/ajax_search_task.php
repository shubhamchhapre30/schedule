<?php 
    date_default_timezone_set($this->session->userdata("User_timezone"));
    $site_setting_date = $this->config->item('company_default_format');
    $datetimezone = date($site_setting_date,  strtotime(date('Y-m-d')));
    $default_format = $this->config->item('company_default_format');
    $date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
?>
<script>
init_data();
init_data1();
init_data2();
</script>
</script>
                        <div class="col-md-12">
                            <label class="control-label pull-left" id="chnage_filter"><strong>Current View : <?php echo $filter_name; ?></strong></label>
                            <div class="dropdown">
                                   <button class="btn green btn-new unsorttd dropdown-toggle" type="button" data-toggle="dropdown">Save as<span class="caret" style="margin-left: 3px !important;"></span></button>
                                   <ul class="dropdown-menu seach-dropdoen">
                                       <li><a href="javascript:void(0);" onclick="update_existing_filter(<?php  echo $filter_id; ?>);">Save</a></li>
                                       <li><a href="javascript:void(0);" onclick="show_filter_popup();">Save as new</a></li>
                                   </ul>
                                   <a href="javascript:void(0);" onclick="search_data_excel();" class="btn btn-new green" >Excel</a>
                            </div>
                        </div>
                        <form name="serach_data" id="serach_data" method="post">
                            <div class="col-md-12" style="margin-top: 15px;">
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">Projects</label>
                                <select id="selectpicker1" multiple name="projects" class="serach_module_data" title="Select project" data-size="5" size="1" data-live-search="true" >
                                    <?php if(isset($user_projects) && !empty($user_projects)){
                                            foreach($user_projects as $project){ 
                                                if(in_array_r($project->project_id,$filters_data)){ ?>
                                                    <option value="<?php echo $project->project_id; ?>" selected='selected'><?php echo $project->project_title; ?></option>    
                                    <?php }else{ ?>
                                                  <option value="<?php echo $project->project_id; ?>"><?php echo $project->project_title; ?></option>
                                    <?php } } }?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">Customers</label>
                                <select id="selectpicker2" name="customers" class="serach_module_data" multiple title="Select customer" data-size="5" size="1" data-live-search="true">
                                    <?php if(isset($customers) && !empty($customers)){
                                            foreach($customers as $cus){ 
                                                if(in_array_r($cus->customer_id,$filters_data)){?>
                                                <option value="<?php echo $cus->customer_id; ?>" selected="selected"><?php echo $cus->customer_name; ?></option>
                                    <?php }else{ ?>
                                                <option value="<?php echo $cus->customer_id; ?>"><?php echo $cus->customer_name; ?></option>
                                    <?php } } } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label bold display_flex">Date Range</label>
                                <div id="daterange" name="date_range" class="date-range_css" value="<?php echo $datetimezone;?>">
                                    <span>
                                        <input type="hidden" name="start_date" id="start_date" value=""/>
                                        <input type="hidden" name="end_date" id="end_date" value=""/>
                                        <i class="fa fa-calendar-o" style="font-size: 15px;padding-right: 5px;"></i><i class="fa fa-sort-desc" style="font-size: 15px;"></i>
                                    </span>
                                </div>
                                <select id="selectpicker10" multiple name="by_date" class="serach_module_data" title="By date" data-size="5" size="1" data-live-search="true">
                                    <?php if(in_array_r("scheduled_date",$filters_data)){ ?>
                                            <option value="scheduled_date" selected="selected">Scheduled Date</option>
                                    <?php }else{ ?>
                                            <option value="scheduled_date">Scheduled Date</option>
                                    <?php } ?>
                                    <?php if(in_array_r("completion_date",$filters_data)){?>
                                            <option value="completion_date" selected="selected">Completion Date</option>
                                    <?php }else{ ?>
                                            <option value="completion_date">Completion Date</option>
                                    <?php } ?>
                                    <?php if(in_array_r("due_date",$filters_data)){ ?>
                                            <option value="due_date" selected="selected">Due Date</option>
                                    <?php }else{ ?>
                                            <option value="due_date">Due Date</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">Assignee</label>
                                <select id="selectpicker3" multiple name="users" class="serach_module_data" title="Select assignee" data-size="5" size="1" data-live-search="true">
                                    <?php if(isset($users) && !empty($users)){
                                            foreach($users as $user){
                                                 if(in_array_r($user->user_id,$filters_data)){?>
                                                <option value="<?php echo $user->user_id; ?>" selected="selected"><?php echo $user->first_name." ".$user->last_name; ?></option>
                                    <?php }else{ ?>
                                                <option value="<?php echo $user->user_id; ?>"><?php echo $user->first_name." ".$user->last_name; ?></option>
                                    <?php } } } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">More</label>
                                <select id="selectpicker4" multiple title="More" data-size="5" size="1" data-live-search="true">
                                    <?php if(in_array_key('category',$filters_data)){?>
                                            <option value="category" selected='selected'>Category</option>
                                    <?php }else{ ?>
                                            <option value="category">Category</option>
                                    <?php } ?>
                                    <?php if(in_array_key('subcategory',$filters_data)){?>    
                                            <option value="subcategory" selected='selected'>Sub-category</option>
                                    <?php }else{ ?>
                                            <option value="subcategory">Sub-category</option>
                                    <?php } ?>
                                    <?php if(in_array_key('division',$filters_data)){?>
                                            <option value="division" selected="selected">Division</option>
                                    <?php }else{ ?> 
                                            <option value="division">Division</option>
                                    <?php }?>
                                    <?php if(in_array_key('department',$filters_data)){?>
                                            <option value="department" selected="selected">Department</option>
                                    <?php }else{ ?> 
                                            <option value="department">Department</option>
                                    <?php } ?>
                                    <?php if(in_array_key('task_status',$filters_data)){?> 
                                            <option value="task_status" selected="selected">Task Status</option> 
                                    <?php }else{ ?> 
                                            <option value="task_status">Task Status</option> 
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                            <div class="col-md-12" id="more_filters" style="margin-top: 15px;">
                                <?php if(in_array_key('category',$filters_data)){
                                        if(isset($main_category) && !empty($main_category)){ ?>
                                        <div class="col-md-2">
                                            <label class="control-label bold display_flex">Category</label>
                                            <select id="selectpicker5" multiple name="category" class="serach_module_data" title="Select category" data-size="5" size="1" data-live-search="true" >
                                                <?php foreach($main_category as $cate){
                                                        if(in_array_r($cate->category_id,$filters_data)){ ?>
                                                            <option value="<?php echo $cate->category_id; ?>" selected="selected"><?php echo $cate->category_name; ?></option>
                                                <?php }else{ ?>
                                                            <option value="<?php echo $cate->category_id; ?>" ><?php echo $cate->category_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                <?php } } ?>
                                <?php if(in_array_key('subcategory',$filters_data)){
                                        if(isset($sub_category) && !empty($sub_category)){ ?>
                                        <div class="col-md-2">
                                            <label class="control-label bold display_flex">Sub-Category</label>
                                            <select id="selectpicker6" multiple name="subcategory" class="serach_module_data" title="Select sub-category" data-size="5" size="1" data-live-search="true" >
                                                <?php foreach($sub_category as $cate){
                                                        if(in_array_r($cate->category_id,$filters_data)){ ?>
                                                            <option value="<?php echo $cate->category_id; ?>" selected='selected'><?php echo $cate->category_name; ?></option>
                                                <?php }else{ ?>
                                                            <option value="<?php echo $cate->category_id; ?>"><?php echo $cate->category_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                <?php } } ?>
                                <?php if(in_array_key('division',$filters_data)){
                                        if(isset($divisions) && !empty($divisions)){ ?>
                                        <div class="col-md-2">
                                            <label class="control-label bold display_flex">Division</label>
                                            <select id="selectpicker7" multiple name="division" class="serach_module_data" title="Select division" data-size="5" size="1" data-live-search="true" >
                                                <?php foreach($divisions as $div){
                                                        if(in_array_r($div->division_id,$filters_data)){ ?>
                                                            <option value="<?php echo $div->division_id; ?>" selected='selected'><?php echo $div->devision_title; ?></option>
                                                <?php }else{ ?>
                                                            <option value="<?php echo $div->division_id; ?>"><?php echo $div->devision_title; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                <?php } } ?>
                                <?php if(in_array_key('department',$filters_data)){
                                        if(isset($departments) && !empty($departments)){ ?>
                                        <div class="col-md-2">
                                            <label class="control-label bold display_flex">Department</label>
                                            <select id="selectpicker8" multiple name="department" class="serach_module_data" title="Select department" data-size="5" size="1" data-live-search="true" >
                                                <?php foreach($departments as $dept){ 
                                                        if(in_array_r($dept->department_id,$filters_data)){?>
                                                            <option value="<?php echo $dept->department_id; ?>" selected='selected'><?php echo $dept->department_title; ?></option>
                                                <?php }else{ ?>
                                                            <option value="<?php echo $dept->department_id; ?>"><?php echo $dept->department_title; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                <?php } } ?>
                                <?php if(in_array_key('task_status',$filters_data)){
                                        if(isset($task_status) && !empty($task_status)){ ?>
                                        <div class="col-md-2">
                                            <label class="control-label bold display_flex">Status</label>
                                            <select id="selectpicker9" multiple name="task_status" class="serach_module_data" title="Select status" data-size="5" size="1" data-live-search="true" >
                                                <?php foreach($task_status as $status){
                                                        if(in_array_r($status->task_status_id,$filters_data)){?>
                                                            <option value="<?php echo $status->task_status_id; ?>" selected='selected'><?php echo $status->task_status_name; ?></option>
                                                <?php }else{ ?>
                                                            <option value="<?php echo $status->task_status_id; ?>"><?php echo $status->task_status_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                <?php } } ?>
                            </div>
                        </form>
                        <div class="col-md-12"><hr></div>
                        <div class="col-md-12" id="replace_search_data">
                            <div class="customtable table-scrollable form-horizontal" style="overflow-x: auto !important;">
                                <table id="searchTaskresult" class="table table-striped table-hover table-condensed flip-content">
                                    <thead class="flip-content">
                                        <tr>
                                            <th>Task Name</th>
                                            <th>Task Owner</th>
                                            <th>Allocated to</th>
                                            <th>User division</th>
                                            <th>User department</th>
                                            <th>Priority</th>
                                            <th>Color</th>
                                            <th>Project</th>
                                            <th>Task Status</th>
                                            <th>Task Category</th>
                                            <th>Task Sub Category</th>
                                            <th>Time allocated (Hrs)</th>
                                            <th>Actual Time (Hrs)</th>
                                            <th>Completion Date</th>
                                            <th>Scheduled Date</th>
                                            <th>Due Date</th>
                                            <th>Customer Name</th>
                                            <th>External ID</th>
                                            <th>Base Cost</th>
                                            <th>Estimated Total Cost</th>
                                            <th>Base Charge</th>
                                            <th>Estimated Total Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($tasks) && $tasks != '') {
                                            foreach ($tasks as $row) {
                                                    $division = get_user_division($row['user_id']);
                                                    if ($division) {
                                                        $division = $division;
                                                    } else {
                                                        $division = "N/A";
                                                    }
                                                    $department = get_user_department($row['user_id']);
                                                    if ($department) {
                                                        $department = $department;
                                                    } else {
                                                        $department = "N/A";
                                                    }
                                                    $color_name = $row['name'];
                                                    if ($color_name) {
                                                        $color_name = $color_name;
                                                    } else {
                                                        $color_name = "N/A";
                                                    }
                                                    $category_name = $row['category_name'];
                                                    if ($category_name) {
                                                        $category_name = $category_name;
                                                    } else {
                                                        $category_name = "N/A";
                                                    }
                                                    $sub_category_name = $row['sub_category_name'];
                                                    if ($sub_category_name) {
                                                        $sub_category_name = $sub_category_name;
                                                    } else {
                                                        $sub_category_name = "N/A";
                                                    }
                                                    ?>
                                                <tr>
                                                    <td><?php echo $row['task_title']; ?></td>
                                                    <td><?php echo $row['owner_first_name'] . " " . $row['owner_last_name']; ?></td>
                                                    <td><?php echo $row['allocated_user_first_name'] . " " . $row['allocated_user_last_name']; ?></td>
                                                    <td><?php echo $division; ?></td>
                                                    <td><?php echo $department; ?></td>
                                                    <td><?php echo $row['task_priority']; ?></td>
                                                    <td><?php echo $color_name; ?></td>
                                                    <td><?php if($row['project_title']){ echo $row['project_title']; } else { echo "N/A"; } ?></td>
                                                    <td><?php echo $row['task_status_name']; ?></td>
                                                    <td><?php echo $category_name; ?></td>
                                                    <td><?php echo $sub_category_name; ?></td>
                                                    <td><?php echo round($row['task_time_estimate'] / 60, 2); ?></td>
                                                    <td><?php echo round($row['task_time_spent'] / 60, 2); ?></td>
                                                    <td>
                                                        <?php if($row['task_completion_date'] != '0000-00-00 00:00:00'){
                                                            echo date($site_setting_date, strtotime(toDateNewTime($row['task_completion_date'])));
                                                        }else{
                                                            echo 'N/A';   
                                                        }?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['task_scheduled_date'] != '0000-00-00') {
                                                            echo date($site_setting_date, strtotime($row['task_scheduled_date']));
                                                        } else {
                                                            echo "N/A";
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['task_due_date'] != '0000-00-00') {
                                                            echo date($site_setting_date, strtotime($row['task_due_date']));
                                                        } else {
                                                            echo 'N/A';
                                                        } ?>
                                                    </td>
                                                    <td><?php if($row['customer_name'] != ''){ echo $row['customer_name'];}else{ echo '-'; }?></td>
                                                    <td><?php if($row['external_id'] != ''){ echo $row['external_id']; }else{ echo '-'; } ?></td>
                                                    <td><?php echo $row['cost_per_hour']; ?></td>
                                                    <td><?php echo $row['cost']; ?></td>
                                                    <td><?php echo $row['charge_out_rate']; ?></td>
                                                    <td><?php echo $row['estimated_total_charge'];?></td>
                                                </tr>
                                                <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function(){
                                var width = $("#chnage_filter").width()+8+"px";
                                $(".seach-dropdoen").css("margin-left",width);
                            });
                        </script>