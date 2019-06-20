<?php
$uriseg=uri_string();
$theme_url = base_url().getThemeName();
$uri=explode('/',$uriseg);
$method = $this->uri->segment(1); 
$fun =   $this->uri->segment(2);
$is_manager = "0";
if(get_users_under_manager()!="0"){
	$is_manager = "1";
}

if($last_rember_values){
	$sidbar_collapsed = $last_rember_values->sidbar_collapsed;
	$last_calender_view = $last_rember_values->last_calender_view;
} else {
	$sidbar_collapsed = '0';
	$last_calender_view = '1';
}
if($this->session->userdata('is_customer_user') == '1'){
    $customer_user_access = customer_user_access('customer_user');
}else{
    $customer_user_access = customer_user_access();
}

?>
<script type="text/javascript" src="<?php echo $theme_url;?>/assets/scripts/onlymenu.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript">
            $(window).on('resize' , function(){
                var viewport = $(window).width(); 
                        if(viewport <= 958){
                            $(".custom_toggle").show();
                            $("#sidebar_custom").removeClass("in");
                        }
                        else{
                            $(".custom_toggle").hide();
                            $("#sidebar_custom").addClass("in");
                         }
            });
    
            $(window).on('resize' , function(){
            var viewport = $(window).width(); 

                if(viewport <= 958){
                    $(".mainpage-container").css("padding-top" , "86px");
                    $("#sidebar_custom").removeClass("page-sidebar-closed");
                    $(".custom_toggle").on("click",function(){
                            if(!$( ".page-sidebar" ).is( ".in" )){
                                    $(".mainpage-container").css("padding-top" , "0");
                                }
                                else{
                                        $(".mainpage-container").css("padding-top" , "86px");
                                }
                        });
                }else{ 
                    $("#sidebar_custom").addClass("page-sidebar-closed");
                    $(".mainpage-container").css("padding-top" , "20px");
                  }
            });
            $(document).ready(function() {
                $(window).trigger('resize');
            });	
</script>
<!-- BEGIN SIDEBAR -->
  <div class="page-sidebar nav-collapse collapse in" id="sidebar_custom">
  	
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu">
     <!-- <li>
        <div class="sidebar-toggler hidden-phone"></div>
      </li>-->
     <!-- <li class="start active "> <a href="javascript:;"> <i class="icon-home"></i> <span class="title">MY SPACE</span> <span class="arrow open"></span> </a> </li>-->
       <!-- <ul class="sub-menu"> -->
       <?php if($customer_user_access->dashboards == '1'){ ?>
          <li class="">
            <a href="<?php echo site_url('user/dashboard'); ?>"> <i class="icon-dashboard"></i> <span class="title">Dashboard</span></a>
            <ul class="sub-menu">
                <li class="<?php if(isset($fun) && $fun == 'dashboard'){ echo 'active'; }?> cstm_left_menu ">
                    <a href="<?php echo site_url('user/dashboard'); ?>" class="nav-link "><span class="title">My Dashboard </span> </a>
                </li>
                <?php   if($is_manager == "1"){ ?>
                    <li class="<?php if(isset($fun) && $fun == 'team_dashboard'){ echo 'active'; }?> cstm_left_menu">
                        <a href="<?php echo site_url('user/team_dashboard'); ?>"><span class="title"> Team Dashboard</span></a> 
                    </li>
                <?php } ?>
                <li class="<?php if(isset($fun) && $fun == 'team_dashboard'){ echo 'active'; }?> cstm_left_menu">
                    <a href="<?php echo site_url('user/capacity_dashboard'); ?>"><span class="title"> Capacity & Performance Dashboard</span></a> 
                </li>
            </ul>
          </li>
       <?php } ?>
          <?php if($customer_user_access->calendar_module == '1'){ ?>
            <li class="<?php if(isset($method) && $method == 'calendar'){ echo 'active'; } ?>">
                  <a href="<?php 
                  if($last_calender_view == '2'){
                          echo site_url('calendar/NextFiveDayView');
                  } elseif($last_calender_view == '3'){
                          echo site_url('calendar/myCalendar');
                  } else {
                          echo site_url('calendar/weekView');
                  }
                   ?>"><i class="icon-calendar"></i> <span class="title">My Calendar</span></a>
            </li>
          <?php } ?>
          <?php if($customer_user_access->kanban_module == '1'){ ?>
            <li class="<?php if(isset($method) && $method == 'kanban'){ echo 'active'; }?>">
                  <a href="<?php echo site_url('kanban/myKanban');?>"> <i class="icon-columns"></i><span class="title"> My Kanban</span></a>
            </li>
          <?php } ?>
          <?php if($customer_user_access->project_module == '1'){ ?>
            <li class="<?php if(isset($method) && $method == 'project'){ echo 'active'; }?>">
                  <a href="<?php echo site_url('project/listProject'); ?>"><i class="icon-cubes"></i><span class="title"> My Projects</span></a>
            </li>
          <?php } ?>
          <?php if($customer_user_access->customer_module == '1'){ ?>
            <li class="<?php if(isset($method) && $method == 'customer'){ echo 'active'; } ?>" id="customer_module" style="<?php if($this->session->userdata('customer_module_activation')=='1'){echo "display:block";}else{echo "display:none";}?>" >
                  <a href="<?php echo site_url("customer/index");?>"><i class="icon-user"></i><span class="title"> Customer</span></a>
            </li>
          <?php } ?>
          <?php if($customer_user_access->timesheet_module == '1'){ ?>
            <li class="<?php if(isset($method) && $method == 'timesheet'){ echo 'active'; } ?>" id="timesheet_module_access" style="<?php if($this->session->userdata('customer_module_activation')=='1' && $this->session->userdata('pricing_module_status')=='1' && $this->session->userdata('timesheet_module_status')=='1'){echo "display:block";}else{echo "display:none";}?>" >
                  <a href="<?php echo site_url("timesheet/index");?>"><i class="icon-time"></i><span class="title"> Timesheet </span></a>
            </li>
          <?php } ?>
          <?php if($customer_user_access->report_module == '1'){ ?>
            <li class="<?php if(isset($method) && $method == 'reports'){ echo 'active'; } ?>">
                  <a href="<?php echo site_url("reports/index");?>"><i class="stripicon icoreport "></i><span class="title"> Report</span></a>
            </li>
         <?php } ?> 
         
        
        <!-- </ul> 
         </li>-->
  
    </ul>
    <!-- END SIDEBAR MENU -->
    
  </div>
  <!-- END SIDEBAR -->
  
