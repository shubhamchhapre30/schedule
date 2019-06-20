<?php 
    $s3_display_url = $this->config->item('s3_display_url');
    $bucket = $this->config->item('bucket_name');
    date_default_timezone_set($this->session->userdata("User_timezone"));
    $theme_url = base_url().getThemeName();
    $default_format = $this->config->item('company_default_format');
    $date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
?>
<script src='<?php echo $theme_url; ?>/assets/js/timesheet<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>'></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
<script>
$(document).ready(function(){
    $("#timesheet_viewtable").dataTable({
                    order: [
                        [1, "asc"]
                    ],
                    columnDefs: [ {
                    "targets": 5,
                    "orderable": false
                    },{
                    "targets": 6,
                    "orderable": false
                    },{
                    "targets": 0,
                    "orderable": false
                    },{
                    "targets": 7,
                    "orderable": false
                    }  ],
                    paging: !1,
                    bFilter: !1,
                    searching: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    }
    });
    
})
</script>
<style>
    .dropdown-menu a{
        padding: 3px 9px !important;
    }
   
</style>
<script type="text/javascript">
        var ACTIVE_MENU = '<?php echo $active_menu;?>';
	var START_DATE_PICKER = 'this.date';
	var DATE_ARR = '<?php echo $date_arr_java[$default_format]; ?>';
        var SITE_URL = '<?php echo base_url(); ?>';
</script>
<div class="container-fluid page-background" style="padding:15px;">
        <div class="border" style="background-color:#fff;opacity: 0.97;" >
            <div class="user-block" >
       		<div class="row">
                    <div class="col-md-12">
                        <!--Heading-->
                        <div class="col-md-12">
                            <span><b><h4 class="txtbold bold_black">Timesheets</h4></b></span>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group floating" >
                                <button id="new_timesheet" name="new_timesheet" class="btn blue btn-new unsorttd" type="button">New Timesheet</button>
                                <input type="hidden" name="defalut_form_date" id="defalut_from_date" value="<?php echo $max_date; ?>"/>
                            </div>
                            <div class="form-group floating">
                                <div class="dropdown">
                                   <button class="btn blue btn-new unsorttd dropdown-toggle" type="button" data-toggle="dropdown">Export<span class="caret" style="margin-left: 3px !important;"></span></button>
                                   <ul class="dropdown-menu timesheet_excel_css">
                                       <li><a href="javascript:void(0);" onclick="excel_generate();">Excel</a></li>
                                       <?php if($this->session->userdata('xero_integration_status')== '1' && $this->session->userdata('xero_user_access')== '1'){?>
                                       <li><a href="javascript:void(0);" onclick="create_invoice();">Xero</a></li>
                                       <?php } ?>
                                   </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 ">
                            <form id="filter_timesheet" name="filter_timesheet" class="timesheet_margin" method="POST">
                            <div class="form-group">
                                <label class="control-label timesheet_option"><strong>Status</strong></label>
                                <select class=" m-wrap no-margin radius-b change_timesheet_list" name="timesheet_status_id" id="timesheet_status_id" >
                                    <option value="draft">Draft</option>
                                    <option value="submitted">Submitted</option>
                                    <option value="approved">Approved</option>
                                    <option value="partially_exported">Partially Exported</option>
                                    <option value="exported">Exported</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label timesheet_option" ><strong>Employee</strong></label>
                                <select class=" m-wrap no-margin radius-b change_timesheet_list" name="timesheet_employee_id" id="timesheet_employee_id" >
                                    <option value="all">All</option>
                                    <?php if($this->session->userdata('is_manager')=='0' && $this->session->userdata('is_administrator')=='0'){ ?>
                                    <option value="<?php echo get_authenticateUserID(); ?>"><?php echo $this->session->userdata('username'); ?></option>
                                    <?php } elseif($this->session->userdata('is_manager')=='1'){ 
                                            $users = get_users_under_managers(); ?>
                                    <option value="<?php echo get_authenticateUserID(); ?>"><?php echo $this->session->userdata('username'); ?></option>
                                    <?php if($users){
                                            foreach($users as $list){ ?>
                                            <option value="<?php echo $list->user_id;?>" ><?php echo $list->first_name." ".$list->last_name; ?></option>
                                    <?php } }} else{ ?>
                                        <?php if(isset($userslist) && $userslist !=''){
                                                foreach($userslist as $list){?>
                                                  <option value="<?php echo $list['user_id'];?>" ><?php echo $list['first_name']." ".$list['last_name']; ?></option>
                                        <?php }}?>
                                    <?php } ?>
                                </select>
                            </div>
                            
                                <div class="form-group" style="width: 210px;">
                                <label class="control-label timesheet_option pull-left" ><strong>Start Date</strong></label>
                                <div style="display: inline-block;" class=" controls no-margin relative-position">
                                    <div class="input-append date date-picker" data-date="<?php echo date($default_format);?>" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
                                        <input style="width: 90px;" class="m-wrap m-ctrl-medium  change_timesheet_list "  placeholder="Start date" name="timesheet_start_date" id="timesheet_start_date" size="16" type="text" value="" /><span class="add-on"><i class="icon-calendar taskppicn" style="width: 24px;height: 24px;"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group" style="width: 198px;">
                                <label class="control-label timesheet_option pull-left" style="padding-right: 9px !important;" ><strong>End Date</strong></label>
				<div style="display: inline-block;" class=" controls no-margin relative-position">
                                    <div class="input-append date date-picker" data-date="<?php echo date($default_format);?>" data-date-format="<?php echo $date_arr_java[$default_format]; ?>" data-date-viewmode="years">
                                        <input style="width: 90px;" class="m-wrap m-ctrl-medium change_timesheet_list" placeholder="End date" name="timesheet_end_date" id="timesheet_end_date" size="16" type="text" value="" /><span class="add-on"><i class="icon-calendar taskppicn" style="width: 24px;height: 24px;" ></i></span>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="customtable table-scrollable form-horizonta" id="replace_table">
                                <table class="table table-striped table-hover table-condensed flip-content" id="timesheet_viewtable" >
                                <thead class="flip-content">
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Status</th>
                                        <th>Last Edited</th>
                                        <th>Hours</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="timesheet_list" >
                                    <script>
                                    $(document).ready(function(){
                                        $. each($("input[name='timesheet_check[]']:disabled"), function(){ 
                                        $(this).parent().prop({"class" : "tooltips"}).attr("data-placement","right").attr("data-original-title","Cannot export timesheet that is not approved"); });
                                    })
                                    </script>
                                    <?php if($timesheets_list){?>
                                    <?php foreach ($timesheets_list as $list){?>
                                    <?php $total_timesheet_time = $this->timesheet_model->get_overall_timesheet_time($list['timesheet_user_id'],$list['from_date'],$list['to_date']);
                                          $hours = intval($total_timesheet_time/60);
                                          $minutes = $total_timesheet_time - ($hours * 60);
                                          if(strlen($minutes)=='1'){
                                              $minutes = '0'.$minutes;
                                          }
//                                          $word1 = ucfirst(substr($list['first_name'],0,1));
//                                          $word2 = ucfirst(substr($list['last_name'],0,1));
                                         ?>
                                    <tr id="id_<?php echo $list['timesheet_id']; ?>">
                                        <td><div><input type="checkbox" name="timesheet_check[]" id="timesheet_check" value="<?php echo $list['timesheet_id']; ?>" <?php if($list['timesheet_status'] !='approved' && $list['timesheet_status'] !='exported'){ echo "disabled='disabled'" ; } ?> /></div></td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo $list['first_name'].' '.$list['last_name'];?></a>
                                        </td>
                                        <td><span class="hidden"><?php echo date("Y-m-d",strtotime($list['from_date'])); ?></span><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo date($default_format,  strtotime($list['from_date']));?></a></td>
                                        <td><span class="hidden"><?php echo date("Y-m-d",strtotime($list['to_date'])); ?></span><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo date($default_format,  strtotime($list['to_date']));?></a></td>
                                        <td id="status_<?php echo $list['timesheet_id']; ?>"><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo ucfirst($list['timesheet_status']);?></a></td>
                                        <td><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php if($list['timesheet_updated_date']!='0000-00-00 00:00:00'){echo date($default_format,  strtotime($list['timesheet_updated_date'])); }else{ echo '-';} ?></a></td>
                                        <td><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo $hours.':'.$minutes; ?></a></td>
                                        <td>
                                            <form method="POST" action="<?php echo site_url('timesheet/showtimesheet');?>" name="myForm_<?php echo $list['timesheet_id'];?>" id="myForm_<?php echo $list['timesheet_id']; ?>">
                                                <input type="hidden" name="timesheet_id" id="timesheet_id" value="<?php echo $list['timesheet_id']; ?>" />
                                            </form>
                                            <a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><i class="icon-pencil tmsticn"  style="transform: scale(0.75);"></i> </a>
                                            <?php if($this->session->userdata('is_administrator') == '1' || $list['timesheet_user_id'] == $this->session->userdata('user_id')) {?>
                                            <a href="javascript:void(0);" onclick="delete_timesheet(<?php echo $list['timesheet_id'];?>)" id="delete_timesheet_<?php echo $list['timesheet_id'];?>"> <i class="icon-trash tmsticn" style="transform: scale(0.75); "></i> </a>  
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php }}?>
                                </tbody>
                            </table>
                            </div>
                            <?php if(!empty($total_pages) && $total_pages>1){?>
                              <div align="center" id="footer_pagination">
                                    <ul class='pagination text-center' id="pagination">
                                    <?php for($i=0; $i<$total_pages; $i++){  
                                                if($i == 0){?>
                                                 <li class='active'  id="<?php echo $i;?>"><a href='javascript:void(0)' onclick="pagination(<?php echo $i;?>)"><?php echo $i+1;?></a></li> 
                                                <?php }else{?>
                                                        <li id="<?php echo $i;?>"><a href='javascript:void(0)'  onclick="pagination(<?php echo $i;?>)"><?php echo $i+1;?></a></li>
                                                 <?php }?>          
                                    <?php } ?>  
                                    </ul>                    
                              </div> 
                              <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>



<div id="newtimesheet" class="modal cus_model fade customecontainer" tabindex="-1">
    <?php  $this->load->view($theme.'/layout/timesheet/create_new_timesheet') ?>
</div>

<div id="XeroTimesheetExport" class="modal cus_model fade customecontainer" tabindex="-1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h3> EXPORT TO XERO </h3>
    </div>
    <div class="modal-body">
        <div class="portlet" style="padding:10px;">
            <div class="portlet-body  flip-scroll">
                    <div class="form-group">
                        <label class="control-label col-md-5"><b>Split invoices by projects :</b> </label>
                        <div class="controls">
                            <input type="checkbox" name="is_projecr_separate" id="is_projecr_separate" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5"><b>Customers :</b> </label>
                        <div class="controls">
                            <select id="selected_customer_invoice" name="selected_customer_invoice" class="large m-wrap radius-b">
                                <option value="">select customer</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" id="xero_invoice_option" class="btn blue btn-new"> Save </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php date_default_timezone_set("UTC"); ?>