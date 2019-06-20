<?php 
    $s3_display_url = $this->config->item('s3_display_url');
    $bucket = $this->config->item('bucket_name');
    if($com_off_days!=''){
        $off_days_arr = explode(',', $com_off_days);
    }
    $completed = $this->config->item('completed_id');
    $theme_url = base_url().getThemeName(); 
    $default_format = $site_setting_date;
    $date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
    $main_category = get_company_category($this->session->userdata('company_id'),'Active');
    $cat_array = array();
    $graph_cat_array= array();
    if(!empty($main_category)){
        foreach($main_category as $category){
            $cat_array[$category->category_id]['name'] = $category->category_name;
            $cat_array[$category->category_id]['is_chargeable'] = $category->is_chargeable;
            $graph_cat = array("balloonText"=> "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]h</b></span>",
                "fillAlphas"=> 0.8,
                "labelText"=> "[[value]]",
                "lineAlpha"=> 0.3,
                "title"=> ucfirst($category->category_name),
                "type"=> "column",
                "color"=> "#000000",
                "valueField"=> $category->category_name);
            $graph_cat_array[] = $graph_cat;
        }
    }
    $cat_array[0]['name'] = 'No Category';
    $cat_array[0]['is_chargeable'] = '0';
    $graph_cat = array("balloonText"=> "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]h</b></span>",
        "fillAlphas"=> 0.8,
        "labelText"=> "[[value]]",
        "lineAlpha"=> 0.3,
        "title"=> 'No Category',
        "type"=> "column",
        "color"=> "#000000",
        "lineColor" => "#C0C0C0",
        "valueField"=> 'No Category');
    $graph_cat_array[] = $graph_cat;
    $customers = getTotalCustomerList();
    $customer_one = array();
    $customerList = array();
    if(!empty($customers)){
        foreach($customers as $one){
            $customer_one[$one->customer_id]['customer_id'] = $one->customer_id;
            $customer_one[$one->customer_id]['customer_name'] = $one->customer_name;
            $customer_one[$one->customer_id]['first_name'] = $one->first_name;
            $customer_one[$one->customer_id]['last_name'] = $one->last_name;
            $customer_one[$one->customer_id]['email'] = $one->email;
            $customer_one[$one->customer_id]['base_rate'] = $one->base_rate;
            $customer_one[$one->customer_id]['id'] = $one->id;
            $customer_one[$one->customer_id]['revenue'] = 0;
            $customer_one[$one->customer_id]['non_billable'] = 0;
            $customer_one[$one->customer_id]['total_hours'] = 0;
        }
    }
    $projects = get_project_list();
    $project_one = array();
    if(!empty($projects)){
        foreach($projects as $p1){
          $project_one[$p1->project_id]['project_id'] = $p1->project_id;  
          $project_one[$p1->project_id]['project_title'] = $p1->project_title;  
          $project_one[$p1->project_id]['project_added_by'] = $p1->project_added_by;
          $project_one[$p1->project_id]['revenue'] = 0;  
          $project_one[$p1->project_id]['non_billable'] = 0;
          $project_one[$p1->project_id]['total_hours'] = 0;  
        }
    }
?>

<script>
    $(function () {
        $("#user_filter").bootstrapToggle();
        $("#graph_mode").bootstrapToggle();
        $("#customerTable").dataTable({
                        order: [
                            [1, "desc"]
                        ],
                        paging: !1,
                        bFilter: !1,
                        searching: !1,
                        bLengthChange: !1,
                        info: !1,
                        language: {
                            emptyTable: "No Records found."
                        }
        });
        $("#projectTable").dataTable({
                    order: [
                        [1, "desc"]
                    ],
                    paging: !1,
                    bFilter: !1,
                    searching: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    }
    });
    });
    show_capacity_calendar();
    chnage_capacity_mode();
</script>
            <!-- MAIN CONTENT START HERE -->
             <div class="mycaledar-table row board-css">
                    <div class="cal-currentdate col-md-12 btn_show">
                         <div class="col-md-2">
                            <input type="hidden" name="start_date_cap" id="start_date_cap" value="<?php echo $start_date; ?>"/>
                            <input type="hidden" name="end_date_cap" id="end_date_cap" value="<?php echo $end_date; ?>"/>
                            <label  class="control-label padding-top-8" style="margin-top: 0px !important;color:#fff;font-weight: bold;">View :</label>
                            <input type="checkbox" id="user_filter" name="user_filter" data-toggle="toggle" data-style="android" <?php if($user_filter == 'me'){echo 'checked';} ?> data-width="70" data-on="Me" data-off="Team" onchange="user_filter()" >
                            <input type="hidden" name="filter_value" id="filter_value" value="<?php echo $user_filter;?>" />
                        </div>
                        <div class="col-md-6">
                            <div id="filter_list" style="padding-top: 4px;<?php if($user_filter == 'me'){echo 'display:none;';}?>">
                                <select class="large m-wrap radius-b select_user_menu" name="select_user" id="select_user" tabindex="1" onchange="on_filter();">
                                        <?php if($this->session->userdata('is_administrator')=='1'){?>
                                            <option value="reported_user"  <?php if($select_user =='reported_user') {echo 'selected';}?> >My Team - Direct Reports only</option>
                                            <option value="all" <?php if($select_user =='all') {echo 'selected';}?>>My Team - All</option>
                                        <?php } ?>
                                        <?php if($this->session->userdata('is_manager')=='1' && $this->session->userdata('is_administrator') == '0'){?>
                                            <option value="reported_user" <?php if($select_user =='reported_user'){ echo 'selected';}?>>My Team - Direct Reports only</option> <?php
                                        }?>
                                        <?php foreach ($users as $user){?>
                                            <option value="<?php echo $user->user_id;?>" <?php if($select_user == $user->user_id){echo 'selected';}?> ><?php if($user->user_id == $this->session->userdata('user_id')){echo 'Me';}else{ echo $user->first_name;}?></option><?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4"  style="padding-left:60px;">
                             <div id="daterange">
                                <div style="line-height: 40px;" >
                                    <span >
                                        <?php echo date("F d, Y",strtotime(str_replace(array("/"," ",","), "-", $start_date))) ?> - <?php echo date("F d, Y",strtotime(str_replace(array("/"," ",","), "-", $end_date))) ?>
                                        <i class="fa fa-calendar-o" style="padding-top: 10px;padding-right: 5px;"></i><i class="fa fa-sort-desc"></i>
                                    </span>
                                </div>
                            </div>  
                        </div>

                       
		    </div>
                </div>
            <!-- WIDGET CODE -->
             <div class="row row_box">
                    <?php
                        $total_tasks = 0;
                        $on_time = 0;
                        $late = 0;
                        $actul_work_time = 0;
                        $count_user = 0;
                        $active_users = 0;
                        $planned_hours = 0;
                        $hour_array = array();
                        $cost_array = array();
                        $demo_array = array();
                        $month_hour_array = array(); 
                        $month_cost_array = array();
                        $graph_cat_data=array();
                        $date_data = array();
                        $week_data = array();
                        $month_data = array();
                        
                        foreach($filter_list as $filter){
                         $count_user+=1; 
                         $flag=0;
                        $weekly_tasks = get_calender_weekly_tasks($start_date,$end_date,'all','all',$filter->user_id,'0000-00-00','0','1',$completed,'completed');
                        $i=0;
                         foreach ($date_range as $date){
                             $month = date("M",  strtotime($date));
                             $daydate = date("M d", strtotime($date));
                                                $billable_hours = 0;
                                                $non_billable_hours = 0;
                                                $billable_cost = 0;
                                                $non_billable_cost = 0;
                                                if(isset($weekly_tasks[$date]) && $weekly_tasks[$date] != ''){
                                                    $tm = strtotime($date);
                                                    $w = date("w", $tm);
                                                    $week_start = (date("Y-m-d", $tm - (86400 * $w) ) );
                                                    $week_end = (date("Y-m-d", $tm + 86400 * (6 - $w) ) );
                                                    $week = date("d M", $tm - (86400 * $w) ).'-'.date("d M", $tm + 86400 * (6 - $w) ) ;
                                                    foreach($weekly_tasks[$date] as $week_task_time){
                                                        if($week_task_time){
                                                            if($week_task_time['task_scheduled_date'] > $week_task_time['task_due_date']){
                                                                $late += 1;
                                                            }else{
                                                                $on_time += 1;
                                                            }
                                                            
                                                            if($week_task_time['task_time_estimate'] > 0){
                                                                $planned_hours = $planned_hours + $week_task_time['task_time_estimate'];
                                                            }
                                                            if($week_task_time['customer_id'] != '' && $week_task_time['customer_id'] != NULL && $week_task_time['customer_id'] != '0' && $week_task_time['task_category_id'] != '0' && $cat_array[$week_task_time['task_category_id']]['is_chargeable'] == '1'){
                                                                $billable_hours = $billable_hours + ($week_task_time['billed_time'] != '0' ?$week_task_time['billed_time']:$week_task_time['task_time_spent']);
                                                                $billable_cost = $billable_cost + ($week_task_time['billed_time'] != '0' ?round($week_task_time['billed_time'] * $week_task_time['charge_out_rate']/60,2):$week_task_time['actual_total_charge']);
                                                            }else if($week_task_time['customer_id'] != '' && $week_task_time['customer_id'] != NULL && $week_task_time['customer_id'] != '0' && $week_task_time['task_category_id'] != '0' && $cat_array[$week_task_time['task_category_id']]['is_chargeable'] == '0'){
                                                                $customer_one[$week_task_time['customer_id']]['non_billable']=(isset($customer_one[$week_task_time['customer_id']]['non_billable'])?$customer_one[$week_task_time['customer_id']]['non_billable']+$week_task_time['actual_total_charge']:$week_task_time['actual_total_charge']);
                                                                $non_billable_hours = $non_billable_hours + ($week_task_time['billed_time'] != '0' ?$week_task_time['billed_time']:$week_task_time['task_time_spent']);
                                                                $non_billable_cost = $non_billable_cost + ($week_task_time['billed_time'] != '0' ?round($week_task_time['billed_time'] * $week_task_time['charge_out_rate']/60,2):$week_task_time['actual_total_charge']);
                                                            }else if($week_task_time['task_category_id'] != '0' && $cat_array[$week_task_time['task_category_id']]['is_chargeable'] == '0'){
                                                                $non_billable_hours = $non_billable_hours + ($week_task_time['billed_time'] != '0' ?$week_task_time['billed_time']:$week_task_time['task_time_spent']);
                                                                $non_billable_cost = $non_billable_cost + ($week_task_time['billed_time'] != '0' ?round($week_task_time['billed_time'] * $week_task_time['charge_out_rate']/60,2):$week_task_time['actual_total_charge']);
                                                            }
                                                            if($week_task_time['customer_id'] != '' && $week_task_time['customer_id'] != NULL && $week_task_time['customer_id'] != '0'){
                                                                $customer_one[$week_task_time['customer_id']]['revenue']=(isset($customer_one[$week_task_time['customer_id']]['revenue'])?$customer_one[$week_task_time['customer_id']]['revenue']+$week_task_time['actual_total_charge']:$week_task_time['actual_total_charge']);
                                                                $customer_one[$week_task_time['customer_id']]['total_hours']=(isset($customer_one[$week_task_time['customer_id']]['total_hours'])?$customer_one[$week_task_time['customer_id']]['total_hours']+$week_task_time['task_time_spent']:$week_task_time['task_time_spent']);
                                                            }
                                                            if($week_task_time['task_category_id'] != '0'){
                                                                $date_data[$daydate]['year'] = $daydate;
                                                                $date_data[$daydate][$cat_array[$week_task_time['task_category_id']]['name']] = round(isset($date_data[$daydate][$cat_array[$week_task_time['task_category_id']]['name']])?$date_data[$daydate][$cat_array[$week_task_time['task_category_id']]['name']]+$week_task_time['task_time_spent']/60:$week_task_time['task_time_spent']/60,1);
                                                                $month_data[$month]['year'] = $month;
                                                                $month_data[$month][$cat_array[$week_task_time['task_category_id']]['name']] = round(isset($month_data[$month][$cat_array[$week_task_time['task_category_id']]['name']])?$month_data[$month][$cat_array[$week_task_time['task_category_id']]['name']]+$week_task_time['task_time_spent']/60:$week_task_time['task_time_spent']/60,1);
                                                                $week_data[$week]['year'] = $week;
                                                                $week_data[$week][$cat_array[$week_task_time['task_category_id']]['name']] = round(isset($week_data[$week][$cat_array[$week_task_time['task_category_id']]['name']])?$week_data[$week][$cat_array[$week_task_time['task_category_id']]['name']]+$week_task_time['task_time_spent']/60:$week_task_time['task_time_spent']/60,1);
                                                            }else{
                                                                $week_data[$week]['year'] = $week;
                                                                $week_data[$week][$cat_array[0]['name']] = round(isset($week_data[$week][$cat_array[0]['name']])?$week_data[$week][$cat_array[0]['name']]+$week_task_time['task_time_spent']/60:$week_task_time['task_time_spent']/60,1);
                                                                $month_data[$month]['year'] = $month;
                                                                $month_data[$month][$cat_array[0]['name']] = round(isset($month_data[$month][$cat_array[0]['name']])?$month_data[$month][$cat_array[0]['name']]+$week_task_time['task_time_spent']/60:$week_task_time['task_time_spent']/60,1);
                                                                $date_data[$daydate]['year'] = $daydate;
                                                                $date_data[$daydate][$cat_array[0]['name']] = round(isset($date_data[$daydate][$cat_array[0]['name']])?$date_data[$daydate][$cat_array[0]['name']]+$week_task_time['task_time_spent']/60:$week_task_time['task_time_spent']/60,1);
                                                            }
                                                            if($week_task_time['task_project_id'] != '0'){
                                                                $project_one[$week_task_time['task_project_id']]['revenue']=(isset($project_one[$week_task_time['task_project_id']]['revenue'])?$project_one[$week_task_time['task_project_id']]['revenue']+$week_task_time['actual_total_charge']:$week_task_time['actual_total_charge']);
                                                                $project_one[$week_task_time['task_project_id']]['total_hours']=(isset($project_one[$week_task_time['task_project_id']]['total_hours'])?$project_one[$week_task_time['task_project_id']]['total_hours']+$week_task_time['task_time_spent']:$week_task_time['task_time_spent']);
                                                            }
                                                            if($week_task_time['task_project_id'] != '0' && $week_task_time['task_category_id'] != '0' && $cat_array[$week_task_time['task_category_id']]['is_chargeable'] == '0'){
                                                                $project_one[$week_task_time['task_project_id']]['non_billable']=(isset($project_one[$week_task_time['task_project_id']]['non_billable'])?$project_one[$week_task_time['task_project_id']]['non_billable']+$week_task_time['actual_total_charge']:$week_task_time['actual_total_charge']);
                                                            }
                                                            $total_tasks += 1;
                                                            $actul_work_time = $actul_work_time + $week_task_time['task_time_spent'];
                                                            $flag=1;
//                                                            echo $billable_hours;
                                                        }
                                                    }
                                                }
                            $hour_array[$date]['date'] = $date;
                            $hour_array[$date]['value'] = isset($hour_array[$date]['value'])?$hour_array[$date]['value']+$billable_hours/60:$billable_hours/60;
                            $hour_array[$date]['view'] = isset($hour_array[$date]['view'])?$hour_array[$date]['view']+$non_billable_hours/60:$non_billable_hours/60;
                            $hour_array[$date]['unit'] = 'h';
                            $cost_array[$date]['date'] = $date;
                            $cost_array[$date]['value'] = isset($cost_array[$date]['value'])?$cost_array[$date]['value']+$billable_cost:$billable_cost;
                            $cost_array[$date]['view'] = isset($cost_array[$date]['view'])?$cost_array[$date]['view']+$non_billable_cost:$non_billable_cost;
                            $cost_array[$date]['unit'] = $this->session->userdata('currency');
                            $demo_array[$date]['date'] = $date;
                            $demo_array[$date]['value'] = rand(1, 10);
                            $demo_array[$date]['view'] = rand(1, 10);
                            $demo_array[$date]['unit'] = 'h';
                            $i++;
                        }
                        if($flag == 1)
                            $active_users+=1;
                        }
                        $graph_cat_data= array();
                        foreach($date_data as $k){
                            $graph_cat_data[] = $k;
                        }
                        $month_cat_data= array();
                        foreach($month_data as $k){
                            $month_cat_data[] = $k;
                        }
                        $week_cat_data= array();
                        foreach($week_data as $k){
                            $week_cat_data[] = $k;
                        }
                        $hour_array1 = array();
                        foreach ($hour_array as $h1){
                            $h1['value'] = round($h1['value'],2);
                            $h1['view'] = round($h1['view'],2);
                            $hour_array1[] = $h1;
                        }
                        $hour_array=$hour_array1;
                        $cost_array1=array();
                        foreach($cost_array as $c1){
                            $c1['value'] = round($c1['value']);
                            $c1['view'] = round($c1['view']);
                            $cost_array1[] = $c1;
                        }
                        $cost_array = $cost_array1;
                        foreach($demo_array as $d1){
                            $demo_array1[] = $d1;
                        }
                        $demo_array = $demo_array1;
                       
                         ?>
                        <div class="col-md-3">
                            <div class="common_widget dashboard-stat2">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-blue-sharp">
                                            <span data-counter="counterup" data-value="567"></span>
                                        </h3>
                                        <small>Completed Tasks</small>
                                    </div>
                                </div>
                                <div class="first_widget">
                                    <canvas id="doughnutData" > </canvas>
                                </div>    
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="common_widget dashboard-stat2">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-blue-sharp">
                                            <span data-counter="counterup" data-value="567"></span>
                                        </h3>
                                        <small>Utilization</small>
                                    </div>
                                </div>
                                <div id="chartdiv"></div>
                            </div>
                        </div>
                        <?php if($total_tasks > 0){
                            $width = round(($active_users * 100)/$count_user);
                            }else{
                                $width = 0;
                            }
                            $prg_width=0;
                        ?>
                        <div class="col-md-3">
                            <div class="dashboard-stat2 common_widget">
                                <div class="display">
                                    <div class="number">
                                        <small>Active Users</small>
                                        <h3 class="font-blue-sharp" style="padding-top: 10px;">
                                            <span data-counter="counterup" data-value="1899"><?php echo $active_users;?>/<?php echo $count_user;?></span>
                                        </h3>
                                    </div>
                                    <div class="icon">
                                        <i class="icon-user"></i>
                                    </div>
                                </div>
                                <div class="progress-info" style="margin-top: 35px;">
                                    <div class="progress">
                                        <span style="width: <?php echo $width;?>%;" class="progress-bar progress-bar-success blue-sharp">
                                            <span class="sr-only">45% grow</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title"> PERCENTAGE </div>
                                        <div class="status-number"> <?php echo $width;?>% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stat2 common_widget">
                                <div class="display">
                                    <div class="number">
                                        <small>Planned Hours/Actual Hours</small>
                                        <h3 class="font-purple-soft" style="padding-top: 10px;">
                                            <span data-counter="counterup" data-value="276,7"><span id="widget4p">h</span>/<?php echo round($actul_work_time/60,2);?>h</span>
                                        </h3>
                                    </div>
                                    <div class="icon">
                                        <i class="icon-basket"></i>
                                    </div>
                                </div>
                                <div class="progress-info" style="margin-top: 35px;">
                                    <div class="progress">
                                        <span style="width: <?php echo round($prg_width);?>%;" id="widget4w" class="progress-bar progress-bar-success purple-soft">

                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title">  </div>
                                        <div class="status-number" id="widget4n"> <?php echo round($prg_width);?>% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                 </div>
            <!-- PLANNED CAPACITY BOARD -->
            <div class="row marginzero">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject bold uppercase font-dark">PLANNED CAPACITY</span>
                            </div>
                            <div class="actions" style="padding-top: 15px !important;">
                                <input type="hidden" name="start_date_cap" id="start_date_cap" value="<?php echo $start_date; ?>"/>
                                <input type="hidden" name="end_date_cap" id="end_date_cap" value="<?php echo $end_date; ?>"/>
                                <input type="checkbox" id="change_mode" <?php if($graph_type == 'graph'){ echo "checked='checked'";}?> data-toggle="toggle" data-style="android" data-width="120" data-on="<i class='fa  fa-bar-chart'></i> Graphical" data-off="<i class='fa fa-line-chart'></i> Numerical" >
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="customtable table-scrollable form-horizontal" style="border: none !important;" >
                                <table class="table table-striped  table-condensed table-bordered " style=" width: auto !important;border:2px solid #e7ecf1 !important;"  >
                                    <thead class="flip-content">
                                        <tr>
                                            <th style="width: 206px !important;"><span>Name</span><span style="float: right;"> <a href="javascript:void(0)" onclick="change_view_capacity('<?php echo $start_date."#prev"; ?>');"> <i class="glyphicon glyphicon-chevron-left"> </i> </a></span></th>
                                              <?php 
                                              $days=0;
                                              foreach($date_range as $date) {
                                                  if($days<14){
                                                      if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                          $tdstyle = 'style="background-color:#E7E7E7;width: 84px !important;border-right: 2px solid #E7E7E7;"';
                                                      } else {
                                                          $tdstyle = 'style="width: 84px !important;border-right: 2px solid #E7E7E7;"';
                                                      } ?>
                                                <th <?php echo $tdstyle; ?> ><span <?php if($date == $end_date || $days == 13){ echo 'style="float:left"'; } ?>><?php echo date('D d',  strtotime($date)); ?><span style="display:block"><?php echo date('M',  strtotime($date)); ?></span></span><?php if($date == $end_date || $days == 13){?><span style="float: right;padding-top: 9px;"><a href="javascript:void(0)" onclick="change_view_capacity('<?php echo $end_date."#next"; ?>');"> <i class="glyphicon glyphicon-chevron-right"> </i> </a></span> <?php } ?></th>           
                                              <?php } $days++;} ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($filter_list as $user){
                                            $capacity = getUserCapacity($user->user_id); 
                                            if($capacity){
                                                 $Mon_capacity = $capacity['MON_hours'];
                                                 $Tue_capacity = $capacity['TUE_hours'];
                                                 $Wed_capacity = $capacity['WED_hours'];
                                                 $Thu_capacity = $capacity['THU_hours'];
                                                 $Fri_capacity = $capacity['FRI_hours'];
                                                 $Sat_capacity = $capacity['SAT_hours'];
                                                 $Sun_capacity = $capacity['SUN_hours'];
                                             }
                                             $weekly_tasks = get_calender_weekly_tasks($start_date,$end_date,'all','all',$user->user_id,'0000-00-00','0','1',$completed,'capacity');
                                             $word1 = ucfirst(substr($user->first_name,0,1));
                                             $word2 = ucfirst(substr($user->last_name,0,1));
                                            ?>
                                        <tr>
                                            <td>
                                                <?php $name = 'upload/user/'.$user->profile_image;
                                                     if(($user->profile_image != '' || $user->profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$user->profile_image)) { ?>
                                                        <img alt="" class="capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$user->profile_image; ?>" class="profile-image" />
                                                <?php } else { ?>
                                                        <span data-letters="<?php echo $word1.$word2; ?>"></span>
                                                <?php } ?>
                                                <?php echo $user->first_name.' '.$user->last_name; ?>
                                            </td>
                                            <?php 
                                            $user_total_capacity = 0;$days=0;$total_estimate_time = 0;
                                            foreach ($date_range as $date){
                                                $estimate_hours = 0;
                                                $estimate_minutes= 0;
                                                $total_estimate = 0;
                                                    if(isset($weekly_tasks[$date]) && $weekly_tasks[$date] != ''){
                                                        foreach($weekly_tasks[$date] as $week_task_time){
                                                            if($week_task_time){
                                                                $total_estimate += $week_task_time['task_time_estimate'];
                                                                $total_estimate_time += $week_task_time['task_time_estimate'];
                                                            }
                                                        }
                                                        $total_task_time_estimate_minute_1 = $total_estimate;
                                                        $estimate_hours = intval($total_task_time_estimate_minute_1/60);
                                                        $estimate_minutes = $total_task_time_estimate_minute_1 - ($estimate_hours * 60);
                                                    }
                                                    if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr)){
                                                        $tdstyle = 'style="background-color:#E7E7E7;width: 84px !important;border-right: 2px solid #E7E7E7;';
                                                    } else {
                                                        $tdstyle = 'style="width: 84px !important;border-right: 2px solid #E7E7E7;"';
                                                    } 
                                                $day_name = date('D',strtotime(str_replace(array("/"," ",","), "-", $date)));
                                                if($day_name == "Mon"){
                                                    $capacity = $Mon_capacity;
                                                } else if($day_name == "Tue"){
                                                    $capacity = $Tue_capacity;
                                                } else if($day_name == "Wed"){
                                                    $capacity = $Wed_capacity;
                                                } else if($day_name == "Thu"){
                                                    $capacity=$Thu_capacity;
                                                } else if($day_name=='Fri'){
                                                    $capacity=$Fri_capacity;
                                                } else if($day_name=="Sat"){
                                                    $capacity=$Sat_capacity;
                                                }else {
                                                    $capacity=$Sun_capacity;
                                                }
                                                $user_total_capacity = $user_total_capacity + $capacity;
                                             ?>
                                            <?php if($days <14){if($graph_type == 'graph'){?>
                                            <?php if($capacity!='0'){ ?>
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                      $("#sparkline_<?php echo $user->user_id.'_'.$date; ?>").sparkline([ [<?php echo ($total_estimate*100)/($capacity*10); ?>,<?php if(($capacity/60-$estimate_hours)>0){ echo ($capacity/60-$estimate_hours);}else{ echo "0";} ?>,0]], {
                                                            type: 'bar',
                                                            chartRangeMin: 0,
                                                            height: '50',
                                                            barWidth: 40,
                                                            disableHighlight:true,
                                                            stackedBarColor: ['#00a99e',"#F3F3F3"],
                                                            barSpacing: 10,
                                                            myPrefixes: ['','Capacity', 'Allocation'],
                                                                tooltipFormatter: function(sp, options, fields) {
                                                                    var format =  $.spformat('<div class="tooltip-class"><span style="color: {{color}}">&#9679;</span> {{myprefix}}  {{value}}h</div>');
                                                                    var result = '';
                                                                    $.each(fields, function(i, field) {
                                                                    if(i>0){
                                                                            field.myprefix = options.get('myPrefixes')[i];
                                                                            if(field.myprefix == 'Capacity'){
                                                                                field.value = <?php echo $capacity/60; ?>
                                                                            }
                                                                            if(field.myprefix == "Allocation"){
                                                                                field.value = <?php echo round($total_estimate/60,2); ?>
                                                                            }
                                                                            result += format.render(field, options.get('tooltipValueLookups'), options);}
                                                                    })
                                                                   return result;
                                                                }
                                                        });
                                                    })
                                                </script>
                                            <?php } ?>

                                                <td <?php echo $tdstyle; ?> ><span id="sparkline_<?php echo $user->user_id.'_'.$date; ?>"></span></td>
                                            <?php }else{ ?>
                                                <td <?php echo $tdstyle; ?> ><span id="sparkline_<?php echo $user->user_id.'_'.$date; ?>"></span><?php if($capacity == 0 || ($estimate_hours == 0 && $estimate_minutes == 0)){}else{echo round($total_task_time_estimate_minute_1/60,2);} ?></td>

                                            <?php } } $days++;}?>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row marginzero">
                <!-- BILLABLE & NON-BILLABLE CHART -->
                        <div class="col-md-6">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase font-dark">Billable vs Non-billable Time</span>
                                        <span class="caption-helper">based on actual time</span>
                                    </div>
                                    <div class="actions" style="padding-top:12px">
                                        <input type="checkbox" <?php if($this->session->userdata('pricing_module_status')=='1'){echo '';}else{ echo 'disabled';}?> id="graph_mode" data-toggle="toggle" data-style="android" data-width="75" onchange="change_graph_data();" data-on="Hours" data-off="$" <?php echo (isset($_COOKIE['billable_graph_type']) && $_COOKIE['billable_graph_type'] == 0) ?'checked':'';?> > 
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="chartdiv1" style="<?php if($this->session->userdata('pricing_module_status')=='0'){ echo 'background-color:grey;';}?>"></div>
                                            <?php if($this->session->userdata('pricing_module_status')=='1'){echo '';}else {
                                                ?><div style="color: white;font-size: 18px;position: absolute;top: 50%;left: 0;right: 0;text-align: center;">to access this dashboard, you must activate the pricing module</div>
                                           <?php }?>
                                </div>
                            </div>
                        </div>
                <!-- CATEGORY ANALYSIS CHART -->
                <div class="col-md-6">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase font-dark">Category Analysis</span>
                                        <span class="caption-helper">based on actual time</span>
                                    </div>
                                    <div class="actions" style="padding-top:0px !important;">
                                        <select name="task_priority" class="small m-wrap radius-b" tabindex="1" onchange="change_cat_graph(this.value);">
                                            <option value="date" <?php if(isset($_COOKIE['cat_graph_type']) && $_COOKIE['cat_graph_type']=='date') echo 'selected';?>>Day</option>
                                            <option value="week" <?php if(isset($_COOKIE['cat_graph_type']) && $_COOKIE['cat_graph_type']=='week') echo 'selected';?>>Week</option>
                                            <option value="month" <?php if(isset($_COOKIE['cat_graph_type']) && $_COOKIE['cat_graph_type']=='month') echo 'selected';?>>Month</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="chartdiv2"></div>
                                </div>
                            </div>
                </div>
            </div>    
            <!-- TOP 10 CUSTOMERS & PROJECTS LISTS -->
            <div class="row marginzero">
                <div class="col-md-6">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject bold uppercase font-dark">Top 10 Customers</span>
                                <span class="caption-helper"> by time and revenue...</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="customtable form-horizontal">
                                <table class="table table-striped table-hover table-condensed flip-content <?php if($this->session->userdata('customer_module_activation')=='0' || $this->session->userdata('pricing_module_status')=='0'){echo 'disable_customer';}?>" id="customerTable" style="<?php if($this->session->userdata('customer_module_activation')=='0'){ echo 'background-color:grey;';}?>">
                                    <thead class="flip-content">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Revenue</th>
                                            <th>Non-billable</th>
                                            <th>Actual Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php $sort_ = array();
                                        foreach ($customer_one as $key => $row) {
                                            // replace 0 with the field's index/key
                                            $sort_[$key]  = $row['revenue'];
                                        }
                                        array_multisort($sort_, SORT_DESC, $customer_one);
                                        $i=1;
                                        foreach($customer_one as $list){
                                         if($i<=10 && isset($list['customer_name'])){
                                            if($list['revenue'] == 0 && $list['non_billable'] == 0 && $list['total_hours'] == 0){}else{
                                        ?>
                                     <tr>
					<td onclick="callCustomer('<?php echo $list['customer_id'];?>');" style="cursor: pointer;" >
                                            <form method="POST" style="margin: 0px !important;" action="<?php echo site_url('customer/openCustomer');?>" name="myForm_<?php echo $list['customer_id'];?>" id="myForm_<?php echo $list['customer_id'];?>">
                                                <input type="hidden" name="cus_id" id="cus_id" value="<?php echo $list['customer_id'];?>" />
                                            </form>
                                            <?php echo $list['customer_name'];?>
                                        </td>
                                        <td><?php if($this->session->userdata('pricing_module_status')=='1'){echo $this->session->userdata('currency').$list['revenue'];}?></td>
                                        <td><?php if($this->session->userdata('pricing_module_status')=='1'){echo $this->session->userdata('currency').$list['non_billable'];}?></td>
                                        <td style="text-align: center !important;"><?php echo round($list['total_hours']/60,2);?>h</td>
                                     </tr>
                                     <?php  $i++;}}}?>
                                    </tbody>
                                </table>
                                <div style="color: white;font-size: 18px;position: absolute;top: 42%;left: 10%;right: 10%;text-align: center;<?php if($this->session->userdata('customer_module_activation')=='1' && $this->session->userdata('pricing_module_status')=='1'){echo 'display:none';}?>">To access this dashboard, you must activate the customer and pricing modules.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                 <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Top 10 Projects</span>
                            <span class="caption-helper"> by time and revenue...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="customtable form-horizontal">
                            <table class="table table-striped table-hover table-condensed flip-content " id="projectTable">
                                <thead class="flip-content">
                                    <tr>
					<th>Project Name</th>
                                        <th>Revenue</th>
                                        <th>Non-billable</th>
                                        <th>Actual Hours</th>
                                    </tr>
				</thead>
				<tbody>
                                    <?php
                                    $sort_ = array();
                                    foreach ($project_one as $key => $row) {
                                        // replace 0 with the field's index/key
                                        $sort_[$key]  = $row['revenue'];
                                    }
                                    array_multisort($sort_, SORT_DESC, $project_one);
                                    $i=1;
                                    foreach($project_one as $list){
                                        if($i<=10 && isset($list['project_title'])){
                                            if($list['revenue'] == 0 && $list['non_billable'] == 0 && $list['total_hours'] == 0){}else{
                                        ?>
                                    <tr>
					<td onclick="callMyproject(<?php echo $list['project_id'];?>);" style="cursor: pointer;">
                                            <form method="POST" style="margin: 0px !important;" action="<?php echo site_url('project/editProject');?>" name="myProject_<?php echo $list['project_id'];?>" id="myProject_<?php echo $list['project_id'];?>">
                                                        <input type="hidden" name="project_id" id="project_id" value="<?php echo $this->encrypt->encode($list['project_id']);?>" />
                                            </form>
                                            <?php echo $list['project_title'];?>
                                        </td>
                                        <td><?php if($this->session->userdata('pricing_module_status')=='1'){echo $this->session->userdata('currency').$list['revenue'];}?></td>
                                            <td><?php if($this->session->userdata('pricing_module_status')=='1'){echo $this->session->userdata('currency').$list['non_billable'];}?></td>
                                        <td style="text-align: center !important;"><?php echo round($list['total_hours']/60,2);?>h</td>
                                    </tr>
                                    <?php  $i++;}}}?>
                                </tbody>
                            </table>
                         </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- MAIN CONTENT CODE END HERE -->
              
<?php 
$utilization = 0;
if($actul_work_time!=0){
    $utilization = $actul_work_time/$user_total_capacity;
}
if($total_estimate_time != 0){
    $prg_width = round((round($actul_work_time/60,2) * 100)/round($total_estimate_time/60,2),2);
}
?>
<script>
    
                utilization = <?php echo $utilization;?>;
                total_tasks = <?php echo $total_tasks;?>;
                on_time =<?php echo $on_time;?>;
                late = <?php echo $late;?>;
                cost_data = <?php echo json_encode($cost_array);?>;
                hour_data = <?php echo json_encode($hour_array);?>;
                date_cat_graph = <?php echo json_encode($graph_cat_data);?>;
                month_cat_graph = <?php echo json_encode($month_cat_data);?>;
                week_cat_graph = <?php echo json_encode($week_cat_data);?>;
                current_cat_graph = <?php echo isset($_COOKIE['cat_graph_type'])?($_COOKIE['cat_graph_type']=='date'?json_encode($graph_cat_data):($_COOKIE['cat_graph_type']=='week'?json_encode($week_cat_data):($_COOKIE['cat_graph_type']=='month'?json_encode($month_cat_data):json_encode($graph_cat_data)))):json_encode($graph_cat_data);?>;
                graph_type = getCookie('billable_graph_type')?getCookie('billable_graph_type'):0;
                console.log(graph_type);
                billable_graph_data = <?php if($this->session->userdata('customer_module_activation')=='1' && $this->session->userdata('pricing_module_status')=='1')echo (isset($_COOKIE['billable_graph_type']) && $_COOKIE['billable_graph_type'] == 1)?json_encode($cost_array):json_encode($hour_array);else echo json_encode($demo_array);?>;
                
                $(document).ready(function(){
                    $('#widget4w').css('width','<?php echo $prg_width;?>%');
                    $('#widget4n').text('<?php echo $prg_width;?>%');
                    $('#widget4p').text('<?php echo round($total_estimate_time/60,2);?>h');
                    chart = AmCharts.makeChart( "chartdiv", {
  "type": "gauge",
  "theme": "light",
  "axes": [ {
    "fontSize":8,
    "axisThickness": 1,
    "axisAlpha": 0.2,
    "tickAlpha": 0.2,
    "valueInterval": 10,
    "bands": [ {
      "color": "red",
      "endValue": 50,
      "startValue": 0
    }, {
      "color": "#00A99E",
      "endValue": 100,
//      "innerRadius": "95%",
      "startValue": 50
    } ],
    "bottomText": "0%",
    "bottomTextYOffset": -20,
    "endValue": 100,
    "bottomTextFontSize": 16,
  } ],
//   "export": {
//    "enabled": true
//  },
  "arrows": [ {} ]
 
} );
	
                    draw_widgets();
                    setTimeout(randomValue, 2000);
                    
chart1 = AmCharts.makeChart("chartdiv1", {
    "type": "serial",
    "theme": "light",
    "marginRight": 40,
    "marginLeft": 40,
    "autoMarginOffset": 20,
    "mouseWheelZoomEnabled":true,
    "dataDateFormat": "YYYY-MM-DD",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left",
        "ignoreAxisWidth":true
    },{
        "id":"v2",
        "axisColor": "#FCD202",
        "axisThickness": 2,
        "axisAlpha": 1,
        "position": "left"
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
        "id": "g1",
        "lineColor": "blue",
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
          "color":"#ffffff"
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "red line",
        "useLineColorForBulletBorder": true,
        "valueField": "value",
        "balloonText": "<span style='font-size:18px;'>[[value]][[unit]]</span>"
    },
     {
        "id": "g2",
        "lineColor": "#FF0000",
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
          "color":"#FFFFFF"
        },
        "bullet": "square",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "red line",
        "useLineColorForBulletBorder": true,
        "valueField": "view",
        "balloonText": "<span style='font-size:18px;'>[[value]][[unit]]</span>"
    }],
    "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis":false,
        "offset":10,
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "limitToGraph":"g1",
        "valueLineAlpha":0.2,
        "valueZoomable":true
    },
    "valueScrollbar":{
      "oppositeAxis":false,
      "offset":50,
      "scrollbarHeight":10
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true
    },
    "dataProvider": billable_graph_data
});
chart1.addListener("rendered", zoomChart);
chart2 = AmCharts.makeChart("chartdiv2", {
  "chartScrollbar": {
    "resizeEnabled":true,
    "updateOnReleaseOnly": false,
    "enabled":true,
    "scrollbarHeight":20,
    "maximum":5,
    "autoGridCount":false,
    "gridCount":4,
    "hideResizeGrips":false,
    "oppositeAxis":false,
    "offset":30
  },
    "type": "serial",
	"theme": "light",
    "legend": {
      "horizontalGap": 10,
      "maxColumns": 1,
      "position": "right",
		"useGraphSettings": true,
		"markerSize": 10
    },
    "dataProvider": current_cat_graph,
    "valueAxes": [{
        "stackType": "regular",
        "axisAlpha": 0.3,
        "gridAlpha": 0
    }],
    "graphs": <?php echo json_encode($graph_cat_array);?>,
    "categoryField": "year",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "position": "left"
    },
    "export": {
    	"enabled": true
     }

});
                });

</script>
<!-- end here-->
        


