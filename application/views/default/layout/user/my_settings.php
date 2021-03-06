<?php $theme_url = base_url().getThemeName(); ?>
<?php 
	$name = 'upload/user/'.$profile_image;
	$bucket = $this->config->item('bucket_name');
	$s3_display_url = $this->config->item('s3_display_url');
	if((isset($profile_image) && $profile_image!='') && $this->s3->getObjectInfo($bucket,$name)){
	    $src = $s3_display_url.'upload/user/'.$profile_image;
        }else{
            $src = $s3_display_url.'upload/user/no_image.jpg';  
        }
?>
<script async src="<?php echo base_url().getThemeName();?>/assets/plugins/select2/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<script async src="<?php echo base_url().getThemename();?>/assets/js/user-settings<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script async src="<?php echo base_url().getThemename();?>/assets/plugins/bootstrap-switch/dist/js/bootstrap-switch.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/css/bootstrap-editable.css?Ver=<?php echo VERSION;?>" />
<script async src="<?php echo $theme_url;?>/assets/plugins/jquery.mockjax.js?Ver=<?php echo VERSION;?>"></script>
<script async src="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/js/bootstrap-editable.min.js?Ver=<?php echo VERSION;?>"></script>
<?php 
    $company_division = addQuotes(get_company_division_list($this->session->userdata('company_id')));
    $company_department = addQuotes(get_company_department_list($this->session->userdata('company_id'),$tags_division));
    $app_info = getAppInfo();
    if($app_info){
        $client_id = $app_info[0]->client_id;
        $client_secret = $app_info[0]->client_secret;
    }else{
        $client_id = 0;
        $client_secret =0;
    }
	   
?>
<script>
	var company_division = "<?php echo addQuotes(get_company_division_list($this->session->userdata('company_id')));?>";
	var company_department = "<?php echo addQuotes(get_company_department_list($this->session->userdata('company_id'),$tags_division));?>";
	var gmail_auth_url = "<?php echo GMAIL_AUTH_URL."?response_type=code&client_id=".GMAIL_CLIENT."&redirect_uri=".GMAIL_REDIRECT_URL."&state=12345&scope=".GMAIL_SCOPES."&prompt=consent&access_type=offline"?>";
	var is_administrator = '<?php echo $this->session->userdata('is_administrator');?>';
	var user_id = '<?php echo $this->session->userdata('user_id');?>';
</script>
<script type="text/javascript">
    $(document).ready(function(){
    	
    	if(company_division.length >'2'){
            $('#divhide').hide();
	}else{
            $('#divhide').show();
	}
		
	if(company_department.length < '3' && $("#tags_division").val() == ""){
            $('#dephide').hide();
	}else{
            $('#dephide').show();
	}
    });
	
    </script>
    
<link href="<?php echo $theme_url;?>/assets/css/components.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css" />
<link href="<?php echo $theme_url;?>/assets/css/profile.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/jquery-minicolors/jquery.minicolors.css?Ver=<?php echo VERSION;?>" />
<script type="text/javascript" async src="<?php echo $theme_url;?>/assets/plugins/jquery-minicolors/jquery.minicolors.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" async src="<?php echo $theme_url;?>/assets/plugins/jquery-minicolors/components-color-pickers.min.js?Ver=<?php echo VERSION;?>"></script>
<!-- BEGIN PAGE CONTAINER-->
<div class="page-container custom_my_setting">
            
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
                                    <div class="profile-userpic" id="new_user_image">
                                       <img src="<?php echo $src; ?>" class="img-responsive" alt=""/> 
                                    </div>
                                    <!-- END SIDEBAR USERPIC -->
                                    <!-- SIDEBAR USER TITLE -->
                                    <div class="profile-usertitle">
                                        <div class="profile-usertitle-name"><?php echo $first_name.' '.$last_name; ?> </div>
                                        <div class="profile-usertitle-job"> 
                                            <?php
                                                if($this->session->userdata('is_administrator') == '1'){
                                                    echo "admin";
                                                }else if($this->session->userdata('is_manager') == '1'){
                                                    echo "manager";
                                                }else{
                                                    echo "user";
                                                }
                                            ?>
                                        
                                        </div>
                                    </div>
                                    <!-- END SIDEBAR USER TITLE -->
                                    
                                    <!-- SIDEBAR MENU -->
                                    <div class="profile-usermenu">
                                        <ul class="nav">
                                            <li class="active">
                                                <a href="javascript:void(0)">
                                                    <i class="fa fa-cog"></i> My Settings </a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                    <!-- END MENU -->
                                </div>
                                <!-- END PORTLET MAIN -->
                                <!-- PORTLET MAIN -->
                                <div class="portlet light ">
                                    <!-- STAT -->
                                    <div class="row list-separated profile-stat">
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> <?php echo $total_projects; ?> </div>
                                            <div class="uppercase profile-stat-text"> Projects </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> <?php echo $total_tasks; ?> </div>
                                            <div class="uppercase profile-stat-text"> Tasks </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> <?php echo $total_customers; ?> </div>
                                            <div class="uppercase profile-stat-text"> Customers </div>
                                        </div>
                                    </div>
                                    <!-- END STAT -->
                                    
                                    <div class="form-group custom-margin10">
					<label class="control-label new_content-label col-md-9">Daily Email Summary : </label>
                                        <div class="controls relative-position">
                                            <div>
                                                <input type="checkbox" name="daily_email_summary" value="1" <?php if($daily_email_summary == "1"){ echo 'checked'; } ?> data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" id="daily_email_summary"/>
                                            </div>
					</div>
                                    </div>    
                                    <div class="form-group" style="display:<?php if($client_id !='0'){ echo 'block';}else{ echo 'none';}?>">
                                        <label class="control-label new_content-label col-md-9 ">Office 365 Integration : </label>
                                        <div class="controls relative-position">
                                            <div>
                                                <input type="checkbox" name="outlook_synchronization" value="1" <?php if($this->session->userdata('outlook_synchronization_on')=='1'){echo 'checked';}?> data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  id="outlook_synchronization"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="display:<?php if($client_id !='0'){ echo 'block';}else{ echo 'none';}?>">
                                        <label class="control-label new_content-label col-md-9">Gmail Integration : </label>
                                        <div class="controls relative-position">
                                            <div>
                                                <input type="checkbox" name="gmail_sync" value="1" <?php if($this->session->userdata('gmail_sync')=='1'){echo 'checked';}?> data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  id="gmail_sync"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET MAIN -->
                            </div>
                            <!-- END BEGIN PROFILE SIDEBAR -->
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content custom_margin_20">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <span class="caption-subject font-blue-madison bold uppercase">MY SETTINGS</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_2" data-toggle="tab">Profile Image</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_4" data-toggle="tab">Calendar</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_6" data-toggle="tab">Color</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_5" data-toggle="tab">Kanban</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1" >
                                                        <input type="hidden" name="hidden_usr_first_name" id="hidden_usr_first_name" value="<?php echo $first_name; ?>"/>
                                                        <input type="hidden" name="hidden_usr_last_name" id="hidden_usr_last_name" value="<?php echo $last_name; ?>"/>
                                                        <input type="hidden" name="hidden_usr_email" id="hidden_usr_email" value="<?php echo $email; ?>" />
                                                        <input type="hidden" name="hidden_usr_mobile" id="hidden_usr_mobile" value="<?php echo $contact_no; ?>" />
                                                        <input type="hidden" name="hidden_usr_timezone" id="hidden_usr_timezone" value="<?php echo $user_time_zone; ?>"/>
                                                        <input type="hidden" name="hidden_usr_default_page" id="hidden_usr_default_page" value="<?php echo $user_default_page; ?>"/>
                                                        <form  name="user_info_setting" id="user_info_setting" method="post">
                                                            <div class="form-group">
                                                                <label class="control-label">First Name<span class="required">*</span></label>
                                                                <input type="text" placeholder="John"   class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Last Name<span class="required">*</span></label>
                                                                <input type="text" placeholder="Doe" class="form-control" id="last_name"  name="last_name" value="<?php echo $last_name; ?>"/> 
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Email Address<span class="required">*</span></label>
                                                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Mobile Number</label>
                                                                <input type="text" name="mobile"  class="form-control" value="<?php echo $contact_no; ?>" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Time Zone<span class="required">*</span></label>
                                                                <select class="form-control" name="user_timezone">
                                                                    <option value="">Select</option>
                                                                    <?php if($timezone){
                                                                    foreach($timezone as $zone){ ?>
                                                                            <option value="<?php echo $zone->timezone_name; ?>" <?php if($user_time_zone == $zone->timezone_name){ echo "selected='selected'"; } ?> ><?php echo $zone->name; ?></option>   
                                                                    <?php } } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <div id="addUserDivisionDivSettings">
                                                                    <?php if($this->session->userdata('is_administrator') =='1'){?>
								            <div class="form-group">
                                                                                <label class="control-label">Division </label>
										<div class="controls relative-position">
                                                                                    <input type="hidden" id="tags_division" name="tags_division" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_division;?>">
                                                                                    <span class="input-load" id="tags_division_loading"></span>
                                                                                    <?php if($this->session->userdata('is_administrator')=='1'){ ?>
                                                                                        <span id="divhide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup </a></span>
                                                                                    <?php } ?>
										</div>
                                                                            </div>
                                                                    <?php }else{ 
										if(strlen($company_division) > '2' || $tags_division !=''){ ?>
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Division </label>
                                                                                        <div class="controls relative-position">
                                                                                            <input type="hidden" id="tags_division" name="tags_division" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_division;?>">
                                                                                            <span class="input-load" id="tags_division_loading"></span>
                                                                                            <?php if($this->session->userdata('is_administrator')=='1'){ ?>
                                                                                                <span id="divhide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup</a></span>
                                                                                            <?php }  ?>
                                                                                        </div>
                                                                                    </div>
                                                                    <?php } }?>
								</div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div id="addUserDepartmentDivSettings">
                                                                    <?php if($this->session->userdata('is_administrator') =='1'){?>
                                                                            <div class="form-group">
                                                                                <label class="control-label">Department 
                                                                                    <?php if($this->session->userdata('is_administrator')=='1'){?>
                                                                                        <span id="dephide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup Department in Company Settings</a></span>
                                                                                    <?php } ?>
                                                                                </label>
										<div class="controls relative-position">
                                                                                    <input type="hidden" id="tags_department" name="tags_department" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_department;?>" <?php if($tags_division == ''){ echo "disabled='disabled'"; } ?> >
                                                                                    <span class="input-load" id="tags_department_loading"></span>
                                                                                    
										</div>
                                                                            </div>
								    <?php }else{ 
                                                                                if(strlen($company_division) > '2' || $tags_department!=''){ ?>
                    								    <div class="form-group">
                                                                                        <label class="control-label">Department
                                                                                            <?php if($this->session->userdata('is_administrator')=='1'){?>
												<span id="dephide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup Department in Company Settings</a></span>
                                                                                            <?php } ?>
                                                                                        </label>
											<div class="controls relative-position">
                                                                                            <input type="hidden" id="tags_department" name="tags_department" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_department;?>">
                                                                                            <span class="input-load" id="tags_department_loading"></span>
                                                                                            
											</div>
										    </div>
								    <?php } } ?>
								</div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Home Page<span class="required">*</span></label>
                                                                    <select class="form-control" name="user_default_page" >
									<option value="dashboard" <?php if($user_default_page == "dashboard"){ echo "selected"; } ?>>My Dashboard</option>
									    <?php $is_manager = "0";
                                                                                    if($this->session->userdata('is_manager') == "1" && get_users_under_manager()!="0"){
										     $is_manager = "1";
										    }
										    if($is_manager == "1"){ ?> 
											<option value="team_dashboard" <?php if($user_default_page == "team_dashboard"){ echo "selected"; } ?>>Team dashboard</option>
										<?php } ?>
										<option value="weekly_calendar" <?php if($user_default_page == "weekly_calendar"){ echo "selected"; } ?>>Weekly Calendar</option>
										<option value="monthly_calendar" <?php if($user_default_page == "monthly_calendar"){ echo "selected"; } ?>>Monthly Calendar</option>
										<option value="kanban" <?php if($user_default_page == "kanban"){ echo "selected"; } ?>>Kanban Board</option>
							            </select>  
                                                            </div>
                                                            <div class="form-group">
                                                            <label class="control-label">Change Background</label>
                                                            <input type="hidden" name="user_background_type" id="user_background_type" >
                                                            </div>
                                                        </form>
                                                        <div class="form-group" style="margin-left:-15px">
                                                                    <span>
                                                                        <form id="frm_background_color" name="frm_background_color" method="post" >
                                                                        <div class="form-group">
                                                                            <span class="col-md-2">
                                                                                <input type="text" id="wheel-demo" class="form-control demo" style="width:121%;" data-control="wheel" value="<?php echo $user_background_name; ?>"> 
                                                                            
                                                                            </span>
                                                                            <span class="col-md-2">
                                                                                <a class="btn btn-common-blue" id="button_add_color" href="Javascript:void(0)">Validate Color</a>
                                                                            </span>
                                                                        </div>
                                                                        </form>
                                                                    
                                                                        OR
                                                                   
                                                                        
                                                               
                                                                        <form name="frm_background_image" id="frm_background_image" method="post" enctype="multipart/form-data" style="display: inline-block;margin-left: 40px;">
                                                                           
                                                                           <div class="fileupload fileupload-new" >
                                                                               <div class="input-append">

                                                                                   <span class="btn btn-file cus_bordr" style="border-radius: 4px;">
                                                                                       <span id="myprofile-logo-browse" class="fileupload-new">Upload Image</span>
                                                                                      
                                                                                       <input type="file" accept="image/*" class="default" name="background_image" id="background_image" value="<?php echo $user_background_name; ?>" />
                                                                                       <input type="hidden" name="hdn_background_image" id="hdn_background_image" value="<?php echo $user_background_name; ?>" />
                                                                                   </span>
                                                                               </div>
                                                                           </div>
                                                                                 
                                                                       </form>
                                                                         
                                                                        <strong style="margin-left:100px;font-size: 18px;"> Preview</strong><div id="bg_thumbnal" class="form-group" style="width:80px; height:40px; display: inline-block; margin-left: 60px; position: absolute;background: <?php if($user_background_type == 'Color') echo $user_background_name;else if($user_background_type == 'Image') echo "url('".$s3_display_url."upload/user/".$user_background_name."')";?>; background-size:cover;"></div>
                                                                         
                                                                    </span>
                                                            <div style="margin-left:20px;"> <a   id="set_default_background" style="text-decoration: underline;">Restore default background</a></div>
                                                        </div>
                                                            <div class="margiv-top-10">
                                                                <button class="btn btn-common-blue" id="submit" type="submit" > Save Changes </button>
                                                                <button class="btn default" id="user_info_clear" type="button"> Cancel </button>
                                                            </div>
                                                        
                                                    </div>
                                                    
                                                    <!-- END PERSONAL INFO TAB -->
                                                    <!-- CHANGE AVATAR TAB -->
                                                    <div class="tab-pane" id="tab_1_2">
                                                        <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="controls relative-position margin-top-10">
                                                                <div class="cus_margin_bottom20">
                                                                    <span class="cus_margin_left10" id="myprofile_logo_view">
									<img class ="myprofile-brand_header custom_profile_size" src="<?php echo $src; ?>">
                                                                    </span>
                                                                </div>
                                                                <form name="frm_my_settings" id="frm_my_settings" method="post" enctype="multipart/form-data">
                                                                    <div class="fileupload fileupload-new" >
                                                                        <div class="input-append">
                                                                            <div class="uneditable-input cus_preview_image">
                                                                                <i id="myprofile-logo-icon" class="icon-file fileupload-exists"></i> 
                                                                                <span id="myprofile-logo-preview" class="fileupload-preview"></span>
                                                                            </div>
                                                                            <span class="btn btn-file cus_bordr">
                                                                                <span id="myprofile-logo-browse" class="fileupload-new">Browse</span>
                                                                                <span id="myprofile-logo-change" class="fileupload-exists">Change</span>
                                                                                <input type="file" accept="image/*" class="default" name="profile_image" id="profile_image" value="<?php echo $profile_image; ?>" />
                                                                                <input type="hidden" name="hdn_profile_image" id="hdn_profile_image" value="<?php echo $profile_image; ?>" />
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                
							    </div>
                                                        </div>
                                                        </div>
                                                           
                                                    </div>
                                                    <!-- END CHANGE AVATAR TAB -->
                                                    <!-- CHANGE PASSWORD TAB -->
                                                    <div class="tab-pane" id="tab_1_3">
                                                        <?php  $this->load->view($theme."/layout/user/change_password");?>
                                                    </div>
                                                    <!-- END CHANGE PASSWORD TAB -->
                                                    <!-- CALENDAR TAB -->
                                                    <div class="tab-pane" id="tab_1_4">
                                                        <?php  $this->load->view($theme."/layout/user/default_calender");?>
                                                    </div>
                                                    <!-- END CALENDAR TAB -->
                                                    <!-- KANBAN TAB -->
                                                    <div class="tab-pane" id="tab_1_5">
                                                        <?php  $this->load->view($theme."/layout/user/swimlanes");?>
                                                    </div>
                                                    <!-- END KANBAN TAB -->
                                                    <!-- COLOR TAB -->
                                                    <div class="tab-pane" id="tab_1_6">
                                                        <?php  $this->load->view($theme."/layout/user/colors");?>
                                                    </div>
                                                    <!-- END COLOR TAB -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PROFILE CONTENT -->
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
    <!-- END PAGE CONTAINER-->
<script type="text/javascript">
        function call(){
            var urls='https://login.microsoftonline.com/common/oauth2/v2.0/authorize?state=52f47444ef7562e956c164f7d4669329&scope=openid%20profile%20offline_access%20User.Read%20Mail.Read%20Calendars.Read%20Contacts.Read&response_type=code&approval_prompt=auto&redirect_uri='+'<?php echo OUTLOOK_REDIRECT_URL;?>'+'&client_id=79faae9e-b01b-4312-a7b3-30cbc7471095';
            window.location.replace(urls);
        }
    $(document).ready(function(){
        
        $("#tags_division").select2({
            tags: [<?php echo $company_division;?>]
	});
        
        $("#tags_department").select2({
            tags: [<?php echo $company_department;?>]
	});
    });
    </script>
    
<style>
    
    .page-footer-fixed .page-container{
            background: #eef1f5;
    }
    .form-control{
        border-radius: 0px !important;
    }
    .m-wrap.large.select2{
        width: 100% !important;
    }
    
</style>
