<?php $theme_url = base_url().getThemeName(); 

if(isset($_REQUEST['active']) && $_REQUEST['active']=='1'){
   $active_tab = '7';
}else{
    $active_tab = '0';
}
    $name = 'upload/company/'.$company_logo;
	$bucket = $this->config->item('bucket_name');
	$s3_display_url = $this->config->item('s3_display_url');
        if((isset($company_logo) && $company_logo!='') && $this->s3->getObjectInfo($bucket,$name)){
	    $src = $s3_display_url.'upload/company/'.$company_logo;
        }else{
            $src =  $theme_url."/assets/img/logo_new.png";  
        }
      $api_access = check_api_access();  
?>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-switch/dist/js/bootstrap-switch.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo $theme_url;?>/assets/js/settings-general<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/css/bootstrap-editable.css?Ver=<?php echo VERSION;?>" />
<script src="<?php echo $theme_url;?>/assets/plugins/jquery.mockjax.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/js/bootstrap-editable.min.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/scripts/form-editable.js?Ver=<?php echo VERSION;?>"></script>
<script>
var customer_module_active = '<?php echo $this->session->userdata('customer_module_activation'); ?>'; 
var xero_module_access = '<?php echo $this->session->userdata('xero_integration_status'); ?>';
var companyDivision = '<?php echo ($this->session->userdata('companyDivision'));?>';
var xero_access_token = '<?php if(isset($_SESSION['access_token'])){ echo $_SESSION['access_token']; }else{ echo '';} ?>';
var active_tab = '<?php echo $active_tab; ?>';
	$(document).ready(function() { 
		FormEditable.init();
		if(active_tab == '7'){
                    $('.profile-usermenu ul.nav li').removeClass('active');
                    $('[href="#main_tab_4"]').parent('li').addClass('active');
                    $("#main_tab_1").removeClass('active');
                    $("#main_tab_4").addClass('active');
                }
	});
</script>
<style>
    #close_reason_other{
        width: 92% !important;
        border: 1px solid rgba(128, 128, 128, 0.22);
    }
</style>
<script type="text/javascript" src="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/dataTable.min.js?Ver=<?php echo VERSION;?>"></script>
<link href="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/datatable.rowreorder.css?Ver=<?php echo VERSION;?>" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $theme_url ?>/assets/plugins/datatable.rowreorder/datatable.rowreorder.min.js?Ver=<?php echo VERSION;?>"></script>
<link href="<?php echo $theme_url;?>/assets/css/components.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $theme_url;?>/assets/css/profile.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $theme_url;?>/assets/css/admin.css" rel="stylesheet" type="text/css"/>
<!-- BEGIN PAGE CONTAINER-->
   <div class="page-container custom_company_setting ">
            
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="custom_margin_26">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE SIDEBAR -->
                            <div class="profile-sidebar">
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet ">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic" id="new_company_logo">
                                       <img src="<?php echo $src; ?>" class="img-responsive" alt="schedullo"/> 
                                    </div>
                                    <!-- END SIDEBAR USERPIC -->
                                    <!-- SIDEBAR USER TITLE -->
                                    <div class="profile-usertitle">
                                        <div class="profile-usertitle-name" id="change_company_name"><?php echo $company_name; ?></div>
                                        <div class="profile-usertitle-job"> 
                                          <?php 
                                                if($this->session->userdata('chargify_transaction_status') == 'trialing'){
                                                    echo 'Free';
                                                }else{
                                                    echo 'Plan';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <!-- END SIDEBAR USER TITLE -->
                                    
                                    <!-- SIDEBAR MENU -->
                                    <div class="profile-usermenu">
                                        <ul class="nav">
                                            <li class="active">
                                                <a href="#main_tab_1" data-toggle="tab" >
                                                    <i class="fa fa-cog"></i> Company Settings </a>
                                            </li>
                                            <li class="">
                                                <a href="#main_tab_2"  data-toggle="tab">
                                                    <i class="fa fa-cog"></i> Users </a>
                                            </li>
                                            <li class="">
                                                <a href="#main_tab_3" data-toggle="tab">
                                                    <i class="fa fa-cog"></i> Task Settings </a>
                                            </li>
                                            <li class="">
                                                <a href="#main_tab_4" data-toggle="tab">
                                                    <i class="fa fa-cog"></i> Addons </a>
                                            </li>
                                            <?php if($this->session->userdata('is_owner')=='1' || $this->session->userdata('is_administrator') == '1'){?>
                                            <li class="">
                                                <a href="#main_tab_5" data-toggle="tab">
                                                    <i class="fa fa-cog"></i> Billing </a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <!-- END MENU -->
                                </div>
                                <!-- END PORTLET MAIN -->
                                <!-- PORTLET MAIN -->
                                <div class="portlet light ">
                                    <!-- STAT -->
                                    <div class="row list-separated ">
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"><?php echo $total_company_projects; ?></div>
                                            <div class="uppercase profile-stat-text"> Projects </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title" id="update_total_user_count"><?php echo $total_company_users; ?> </div>
                                            <div class="uppercase profile-stat-text"> users  </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"><?php echo $total_company_customers; ?></div>
                                            <div class="uppercase profile-stat-text"> Customers </div>
                                        </div>
                                    </div>
                                    <!-- END STAT -->
                                    
                                </div>
                                <!-- END PORTLET MAIN -->
                            </div>
                            <!-- BEGIN  CONTENT -->
                            <div class="tab-content">
                                <!-- COMPANY INFO TAB -->    
                                <div class="profile-content custom_margin_20 tab-pane active" id="main_tab_1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light " >
                                                <div class="portlet-title tabbable-line" >
                                                    <div class="caption caption-md">
                                                        <span class="caption-subject font-blue-madison bold uppercase">COMPANY SETTINGS</span>
                                                    </div>
                                                    <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab_1_1" data-toggle="tab">Company Info</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_1_2" data-toggle="tab">Company Structure</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_1_3" data-toggle="tab">Change Logo</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_1_4" data-toggle="tab">Default Calendar</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="tab-content">
                                                        <!-- COMPANY INFO TAB -->
                                                        <div class="tab-pane active" id="tab_1_1">
                                                            <form  name="company_info_setting" id="company_info_setting" method="post">
                                                                <div class="form-group">
                                                                    <label class="control-label">Company Name</label>
                                                                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $company_name;?>" maxlength="40"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">Phone Number</label>
                                                                    <input type="text" class="form-control" id="company_phone"  name="company_phone" value="<?php echo $company_phoneno; ?>"/> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">Main Email Address<span class="required">*</span></label>
                                                                    <input type="email" name="company_email" id="company_email" class="form-control" value="<?php echo $company_email; ?>"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">Country<span class="required">*</span></label>
                                                                    <select class="form-control" name="company_country">
                                                                        <option value="">select</option>
                                                                        <?php if($countries){
                                                                                foreach($countries as $country){ ?>
                                                                                    <option value="<?php echo $country->country_id;?>" <?php if($country->country_id == $country_id){ echo 'selected="selected"'; } ?> ><?php echo $country->country_name;?></option>
                                                                        <?php } } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">Billing Address</label>
                                                                    <textarea class="form-control" rows="3" name="company_address" id="company_address"><?php echo $company_address; ?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">Date Format - <span class="label_des">Select your preferred date format</span></label>
                                                                    <select name="company_date_format" id="company_date_format" class="form-control">
                                                                        <option value="m/d/Y" <?php if($company_date_format=="m/d/Y"){ ?> selected="selected"<?php } ?>>MM/DD/YYYY</option>
                                                                        <option value="d/m/Y" <?php if($company_date_format=="d/m/Y"){ ?> selected="selected"<?php } ?>>DD/MM/YYYY</option> 
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">Time Zone - <span class="label_des">The Default Time Zone is used for reporting and when creating new users</span></label>
                                                                    <select class="form-control" name="company_timezone">
                                                                        <option value="">Select</option>
                                                                        <?php if($timezone){
                                                                        foreach($timezone as $zone){ ?>
                                                                                <option value="<?php echo $zone->timezone_name; ?>" <?php if($company_timezone == $zone->timezone_name){ echo "selected='selected'"; } ?> ><?php echo $zone->name; ?></option>   
                                                                        <?php } } ?>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>" />
                                                                <div class="margiv-top-10">
                                                                    <button class="btn btn-common-blue" id="submit" type="submit"> Save Changes </button>
                                                                    <button class="btn default"  type="button"> Cancel </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- END COMPANY INFO TAB -->
                                                        <!-- COMPANY STRUCTURE TAB -->
                                                        <div class="tab-pane" id="tab_1_2">
                                                            <?php  $this->load->view($theme."/layout/settings/company_settings")?>
                                                        </div>
                                                        <!-- END COMPANY STRUCTURE TAB -->
                                                        <!-- CHANGE LOGO TAB -->
                                                        <div class="tab-pane" id="tab_1_3">
                                                            <div class="controls">
                                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                                    <div style="margin-bottom:10px ;">
                                                                        <span id="company_logo_view">
                                                                            <img src="<?php echo $src; ?>" class="company_logo_css"/>
                                                                        </span>
                                                                    </div>
                                                                    <form id="frm_general" name="frm_general" action="" enctype="multipart/form-data" >
                                                                    <div class="input-append">
                                                                        <div class="uneditable-input logo_css">
                                                                            <i class="icon-file fileupload-exists" id="logo-icon"></i> 
                                                                            <span class="fileupload-preview" id="logo-preview"></span>
                                                                        </div>
                                                                        <span class="btn btn-file">
                                                                            <span class="fileupload-new" id="logo-browse">Browse</span>
                                                                            <span class="fileupload-exists" id="logo-change">Change</span>
                                                                            <input type="file" accept="image/*" class="default" name="company_logo" id="company_logo" value="" />
                                                                            <input type="hidden" name="hdn_company_logo" id="hdn_company_logo" value="<?php echo $company_logo; ?>" />
                                                                        </span>

                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END CHANGE LOGO TAB -->
                                                        <!-- CALENDAR TAB -->
                                                        <div class="tab-pane" id="tab_1_4">
                                                            <?php  $this->load->view($theme."/layout/settings/calender_settings")?>
                                                        </div>
                                                        <!-- END CALENDAR TAB -->

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- USERS INFO TAB -->
                                <div class="profile-content custom_margin_20 tab-pane" id="main_tab_2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light " >
                                                <div class="portlet-title tabbable-line" >
                                                    <div class="caption caption-md">
                                                        <span class="caption-subject font-blue-madison bold uppercase">Users</span>
                                                    </div>
                                                    <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab_2_1" data-toggle="tab">Company Users</a>
                                                        </li>
                                                        <?php if($this->session->userdata('customer_module_activation') == '1' && $this->session->userdata('external_user_access') == 1){ ?>
                                                        <li class="">
                                                            <a href="#tab_2_2" data-toggle="tab">External Users</a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="tab-content">
                                                        <!-- COMPANY USER INFO TAB -->
                                                        <div class="tab-pane active" id="tab_2_1">
                                                           <?php  $this->load->view($theme."/layout/user/listUser")?>
                                                        </div>
                                                        <!-- END COMPANY USERS INFO TAB -->
                                                        <!-- CUSTOMER USERS INFO TAB -->
                                                        <?php if($this->session->userdata('customer_module_activation') == '1' && $this->session->userdata('external_user_access') == 1){?>
                                                        <div class="tab-pane" id="tab_2_2">
                                                           <?php  $this->load->view($theme."/layout/user/customer_user")?>
                                                        </div>
                                                        <?php } ?>
                                                        <!-- END CUSTOMER USERS INFO TAB -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- TASK SETTING TAB -->
                                <div class="profile-content custom_margin_20 tab-pane" id="main_tab_3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light " >
                                                <div class="portlet-title tabbable-line" >
                                                    <div class="caption caption-md">
                                                        <span class="caption-subject font-blue-madison bold uppercase">task settings</span>
                                                    </div>
                                                    <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab_3_1" data-toggle="tab">General</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_3_2" data-toggle="tab">Task Status</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_3_3" data-toggle="tab">Categories </a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_3_4" data-toggle="tab">Staff Levels</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_3_5" data-toggle="tab">Skills </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="tab-content">
                                                        <!-- GENERAL INFO TAB -->
                                                        <div class="tab-pane active" id="tab_3_1">
                                                           <div class="form-group">
                                                                <p class="alert alert-info">Switching this function <strong>On</strong> will force users to enter the time it took to complete a task on completion</p>
                                                                <label class="control-label col-md-5 company_force_time"><b>Force users to enter actual time :</b> </label>
                                                                    <div class="controls relative-position">
                                                                            <div >
                                                                                    <input type="checkbox" name="actual_time_on" value="1" <?php if($actual_time_on == "1"){ echo 'checked'; } ?> data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  id="actual_time_on"/>
                                                                            </div>
                                                                            
                                                                    </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <p class="alert alert-info">When <strong>on</strong>, users will be able to create tasks with a scheduled date and due date in the past </p>
                                                                <label class="control-label company_force_time col-md-5"><b>Allow new task to be created in the past :</b></label>
                                                                    <div class="controls relative-position">
                                                                            <div>
                                                                                    <input type="checkbox" name="allow_past_task" value="1" <?php if($allow_past_task == "1"){ echo 'checked'; } ?> data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"   id="allow_past_task"/>
                                                                            </div>
                                                                            
                                                                    </div>
                                                            </div>
                                                        </div>
                                                        <!-- END GENERAL INFO TAB -->
                                                        <!-- COMPANY STATUS TAB -->
                                                        <div class="tab-pane" id="tab_3_2">
                                                            <?php  $this->load->view($theme."/layout/settings/task_status_setting")?>
                                                        </div>
                                                        <!-- END COMPANY STATUS TAB -->
                                                        <!-- COMPANY CATEGORY TAB -->
                                                        <div class="tab-pane" id="tab_3_3">
                                                            <?php  $this->load->view($theme."/layout/settings/ajax_main_taskCategory")?>
                                                        </div>
                                                        <!-- END COMPANY STATUS TAB -->
                                                        <!-- STAFF TAB -->
                                                        <div class="tab-pane" id="tab_3_4">
                                                            <?php  $this->load->view($theme."/layout/settings/staff_levels")?>
                                                        </div>
                                                        <!-- END STAFF TAB -->
                                                        <!-- SKILL TAB -->
                                                        <div class="tab-pane" id="tab_3_5">
                                                            <?php  $this->load->view($theme."/layout/settings/skills")?>
                                                        </div>
                                                        <!-- END SKILL TAB -->
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- END TASK SETTING TAB -->
                                <!-- USERS ADDONS TAB -->
                                <div class="profile-content custom_margin_20 tab-pane" id="main_tab_4">
                                     <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light " >
                                                <div class="portlet-title tabbable-line" >
                                                    <div class="caption caption-md">
                                                        <span class="caption-subject font-blue-madison bold uppercase">addons</span>
                                                    </div>
                                                    <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab_4_1" data-toggle="tab">General</a>
                                                        </li>
                                                        <?php if($api_access=='Active'){?>
                                                        <li>
                                                            <a href="#tab_4_2" data-toggle="tab">API Access</a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="tab-content">
                                                        <!-- GENERAL INFO TAB -->
                                                        <div class="tab-pane active" id="tab_4_1">
                                                           <?php $this->load->view($theme."/layout/settings/addon_setting"); ?>
                                                        </div>
                                                        <!-- END GENERAL INFO TAB -->
                                                       <?php if($api_access=='Active'){?>
                                                        <!-- API TAB -->
                                                        <div class="tab-pane" id="tab_4_2">
                                                            <?php $this->load->view($theme."/layout/settings/api_access"); ?>
                                                        </div>
                                                        <!-- END API TAB -->
                                                       <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                 <!-- BILLING TAB -->
                                <div class="profile-content custom_margin_20 tab-pane" id="main_tab_5">
                                     <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light " >
                                                <div class="portlet-title tabbable-line" >
                                                    <div class="caption caption-md">
                                                        <span class="caption-subject font-blue-madison bold uppercase">billing</span>
                                                    </div>
                                                    <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab_5_1" data-toggle="tab">General</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="tab-content">
                                                        <!-- GENERAL INFO TAB -->
                                                        <div class="tab-pane active" id="tab_5_1">
                                                           <?php $this->load->view($theme."/layout/settings/billing"); ?>
                                                        </div>
                                                        <!-- END GENERAL INFO TAB -->
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END  CONTENT -->
                            <!-- END BEGIN PROFILE SIDEBAR -->
                            </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
    <!-- END PAGE CONTAINER-->
    <style>
        .profile-userpic img{
            border-radius: 0% !important; 
        }
        
    </style>