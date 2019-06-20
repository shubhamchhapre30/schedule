<?php 
	$theme_url = base_url().getThemeName();
	date_default_timezone_set($this->session->userdata("User_timezone"));
        $default_format = $site_setting_date;
        $date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
        $datetimezone = date($site_setting_date,  strtotime(date('Y-m-d')));
?>
<link href="<?php echo $theme_url; ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-select/js/bootstrap-select.min.js?Ver=<?php echo VERSION;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.css?Ver=<?php echo VERSION;?>" />
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/daterangepicker/moment.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/daterangepicker/daterangepicker.js?Ver=<?php echo VERSION;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/daterangepicker/daterangepicker.css?Ver=<?php echo VERSION;?>" />
<script type="text/javascript">
    init_data();
    function init_data(){
       $(function () {
        $('#searchTask').dataTable( {
            "paging":   false,
            "bFilter" : false,               
            "bLengthChange": false,
            "info":     false,
            "language": {
                "emptyTable":"No Records found."
            }
        });
        $("#selectpicker1").selectpicker();
        $("#selectpicker2").selectpicker();
        $("#selectpicker3").selectpicker();
        $("#selectpicker4").selectpicker();
        $("#selectpicker10").selectpicker();
        $('#daterange').daterangepicker({
                opens:"right",
                locale: {
                    format: '<?php echo strtoupper($date_arr_java[$site_setting_date]); ?>'
                },
                startDate: '<?php echo $datetimezone;?>',
                endDate: '<?php echo $datetimezone;?>',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            },
            function(start, end, label) {
                $("#start_date").val(start.format('YYYY-MM-DD'));
                $("#end_date").val(end.format('YYYY-MM-DD'));
                var form = $("#serach_data").serializeArray();
                $("#dvLoading").fadeIn("slow");
                $.ajax({
                    type: "POST",
                    url: SIDE_URL + "task/ajax_search_result_data",
                    data: {
                        data:form
                    },
                    success: function(a) {
                        $("#replace_search_data").html(a);
                        $('#searchTaskresult').dataTable( {
                            "paging":   false,
                            "bFilter" : false,               
                            "bLengthChange": false,
                            "info":     false,
                            "language": {
                                "emptyTable":"No Records found."
                            }
                        });
                        $("#dvLoading").fadeOut("slow");
                    },
                    error:function(a){
                        $("#dvLoading").fadeOut("slow");
                    }
                });
            });
       });
    }
    function init_data2(){
        $(function(){
            $("#selectpicker5").selectpicker();
            $("#selectpicker6").selectpicker();
            $("#selectpicker7").selectpicker();
            $("#selectpicker8").selectpicker();
            $("#selectpicker9").selectpicker();
        });
    }
</script>
<!-- BEGIN PAGE CONTAINER-->
<div class="container-fluid" style="padding-left:20px;padding-right:20px;margin-bottom: 30px;">
    <div class="mainpage-container">
        <div class="user-block" >
            <div class="row">
                <div class="col-md-12"  >
                    <div class="col-md-2 search_left_side" style="height: calc(100vh - 100px);">
                        <div class="col-md-12 panel-heading_pro ">
                            <span><i class="fa fa-filter" aria-hidden="true"></i></span>
                            <label class="control-label default_color bold" >Filters</label>
                        </div>
                        <div class="col-md-12">
                            <ul class="list-unstyled" id="append_filter">
                                <?php if(isset($get_user_filters) && !empty($get_user_filters)){
                                         foreach ($get_user_filters as $filter){ ?>
                                            <li class="padding8">
                                                <a href="javascript:void(0);"  style="color: black !important;" onclick="apply_filter('<?php echo $filter->filter_id; ?>');"><?php echo $filter->filter_name ?></a>
                                                <input type="hidden" name="hidden_filter_name_<?php echo $filter->filter_id; ?>" id="hidden_filter_name_<?php echo $filter->filter_id; ?>" value="<?php echo $filter->filter_name; ?>"/>
                                            </li>     
                                <?php } } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-10 searchResult">
                        <div class="col-md-12">
                            <label class="control-label" id="chnage_filter"><strong>Search</strong></label>
                            <a href="javascript:void(0);" onclick="show_filter_popup();" class="btn btn-new green" >Save View</a>
                            <a href="javascript:void(0);" onclick="search_data_excel();" class="btn btn-new green" >Export to Excel</a>
                        </div>
                        <form name="serach_data" id="serach_data" method="post">
                        <div class="col-md-12" style="margin-top: 15px;">
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">Projects</label>
                                <select id="selectpicker1" multiple name="projects" class="serach_module_data" title="Select project" data-size="5" size="1" data-live-search="true" >
                                    <?php if(isset($user_projects) && !empty($user_projects)){
                                            foreach($user_projects as $project){ ?>
                                                <option value="<?php echo $project->project_id; ?>"><?php echo $project->project_title; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">Customers</label>
                                <select id="selectpicker2" name="customers" class="serach_module_data" multiple title="Select customer" data-size="5" size="1" data-live-search="true">
                                    <?php if(isset($customers) && !empty($customers)){
                                            foreach($customers as $cus){ ?>
                                                <option value="<?php echo $cus->customer_id; ?>"><?php echo $cus->customer_name; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label bold display_flex">Date Range</label>
                                <div id="daterange" name="date_range" class="date-range_css" value="<?php echo $datetimezone;?>">
                                    <span>
                                        <input type="hidden" name="start_date" id="start_date" value="<?php echo date('Y-m-d'); ?>"/>
                                        <input type="hidden" name="end_date" id="end_date" value="<?php echo date('Y-m-d'); ?>"/>
                                        <i class="fa fa-calendar-o" style="font-size: 15px;padding-right: 5px;"></i><i class="fa fa-sort-desc" style="font-size: 15px;"></i>
                                    </span>
                                </div>
                                <select id="selectpicker10" multiple name="by_date" class="serach_module_data" title="By date" data-size="5" size="1" data-live-search="true">
                                    <option value="scheduled_date" selected>Scheduled Date</option>
                                    <option value="completion_date">Completion Date</option>
                                    <option value="due_date">Due date</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">Assignee</label>
                                <select id="selectpicker3" multiple name="users" class="serach_module_data" title="Select assignee" data-size="5" size="1" data-live-search="true">
                                    <?php if(isset($users) && !empty($users)){
                                            foreach($users as $user){ ?>
                                                <option value="<?php echo $user->user_id; ?>" <?php if($user->user_id == $this->session->userdata('user_id')){echo 'selected';}?>><?php echo $user->first_name." ".$user->last_name; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold display_flex">More</label>
                                <select id="selectpicker4" multiple title="More" data-size="5" size="1" data-live-search="true">
                                    <option value="category">Category</option>
                                    <option value="subcategory">Sub-category</option>
                                    <option value="division">Division</option>
                                    <option value="department">Department</option>
                                    <option value="task_status">Task Status</option>   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="more_filters" style="margin-top: 15px;"></div>
                        </form>
                        <div class="col-md-12"><hr></div>
                        <div class="col-md-12" id="replace_search_data">
                            <div class="customtable table-scrollable form-horizontal" style="overflow-x: auto !important;">
                                <table id="searchTask" class="table table-striped table-hover table-condensed flip-content">
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
                                                    <td><?php echo $sub_category_name; ?> </td>
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
                                                    <td><?php if($row['customer_name']!=''){ echo $row['customer_name'];}else{echo '-';}?></td>
                                                    <td><?php if($row['external_id'] != ''){ echo $row['external_id'];}else{ echo '-'; } ?></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
init_data1();
    function init_data1(){
        $(function(){
            $('#selectpicker4').on('changed.bs.select', function (e) { 
                
                var selected_values = $(this).val();
                var form = $("#serach_data").serializeArray();
                $.ajax({
                    type: "POST",
                    url: SIDE_URL + "task/ajax_load_more_filter",
                    data: {
                        filters:selected_values,
                        filters_data:form
                    },
                    success: function(a) { 
                        $("#more_filters").html(a);
                        
                    },
                    error:function(a){
                        
                    }
                });
            });

            $(document).on('change',".serach_module_data",function(){
                $("#dvLoading").fadeIn("slow");
                var form = $("#serach_data").serializeArray();
                $.ajax({
                    type: "POST",
                    url: SIDE_URL + "task/ajax_search_result_data",
                    data: {
                        data:form
                    },
                    success: function(a) {
                        $("#replace_search_data").html(a);
                        $('#searchTaskresult').dataTable( {
                            "paging":   false,
                            "bFilter" : false,               
                            "bLengthChange": false,
                            "info":     false,
                            "language": {
                                "emptyTable":"No Records found."
                            }
                        });
                        $("#dvLoading").fadeOut("slow");
                    },
                    error:function(a){
                        $("#dvLoading").fadeOut("slow");
                    }
                });
            });
        });
    }
function show_filter_popup(){
    $("#filter_name").val('');
    $("#save_filter").modal("show");
    $('#save_filter').on('shown.bs.modal', function () {
        $("#filter_name").focus();
    }); 
}
function apply_filter(filter_id){
    $("#dvLoading").fadeIn("slow");
    $.ajax({
            type: "POST",
            url: SIDE_URL + "task/set_user_filter",
            data: {
                filter_id:filter_id
            },
            success: function(a) { 
                $("li").removeClass("bold");
                $("li").removeClass("text-underline");
                $("#hidden_filter_name_"+filter_id).parent("li").addClass("bold");
                $("#hidden_filter_name_"+filter_id).parent("li").addClass("text-underline");
                $(".searchResult").html(a);
                $('#searchTaskresult').dataTable( {
                    "paging":   false,
                    "bFilter" : false,               
                    "bLengthChange": false,
                    "info":     false,
                    "language": {
                        "emptyTable":"No Records found."
                    }
                });
                $("#dvLoading").fadeOut("slow");
            },
            error:function(a){
                console.log("Ajx request have error.");
                $("#dvLoading").fadeOut("slow");
            }
        });
}

function update_existing_filter(filter_id){
        $("#dvLoading").fadeIn("slow");
        $.ajax({
            type: "POST",
            url: SIDE_URL + "task/update_filter",
            data: {
                filter_id:filter_id,
                data:$("#serach_data").serializeArray()
            },
            success: function(a) { 
                alertify.set('notifier','position', 'top-right');
                alertify.success("Filter has been updated successfully.");
                $("#dvLoading").fadeOut("slow");
            },
            error:function(a){
                console.log("Ajx request have error.");
                $("#dvLoading").fadeOut("slow");
            }
        });
}
</script>

<div id="save_filter" class="modal cus_model fade customecontainer" tabindex="-1" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h3>Filter</h3>
    </div>
    <div class="modal-body">
        <div class="portlet">
            <div class="portlet-body  form flip-scroll" style="padding:26px;">
                <div class="row form-horizontal">
                    <div class="form-group">
                        <label class="control-label bold">Enter Filter Name :<span class="required">*</span> </label>
                        <div class="controls">
                            <input type="text" class="alert_input m-wrap mysetting-select" id="filter_name" name="filter_name" value="" tabindex="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn green" type="button" onclick="save_filter();">Save filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>