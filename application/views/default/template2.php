<?php
$theme_url = base_url().getThemeName();
$uriseg=uri_string();
$uri=explode('/',$uriseg);
$billing_portal_detail = chargifyPaymentDetails();
$company_id = $this->session->userdata('company_id');
$company_name = getCompanyName($company_id);
$company = array('id'=>$company_id,'name'=>$company_name);
?>


<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php if($this->session->userdata('user_id')){  echo ucwords($this->session->userdata('username'))." - "; }?>  Schedullo </title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link  rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/css/developer.css?Ver=<?php echo VERSION;?>" />
<link  href="<?php echo $theme_url; ?>/assets/plugins/bootstrap/css/bootstrap.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo $theme_url; ?>/assets/plugins/bootstrap-modal/css/bootstrap-modal.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<!--<link media="none" onload="if(media!='all')media='all'"  href="<?php echo $theme_url; ?>/assets/plugins/bootstrap/css/bootstrap-responsive.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>-->
<link href="<?php echo $theme_url; ?>/assets/plugins/font-awesome/css/font-awesome.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo $theme_url; ?>/assets/css/style-metro.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo $theme_url; ?>/assets/css/style.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link  href="<?php echo $theme_url; ?>/assets/css/style-responsive.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo $theme_url; ?>/assets/css/themes/default.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css" id="style_color"/>
<link  href="<?php echo $theme_url; ?>/assets/plugins/uniform/css/uniform.default.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link  href="<?php echo $theme_url; ?>/assets/plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link  rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css?Ver=<?php echo VERSION;?>" />
<link  rel="stylesheet" href="<?php echo $theme_url;?>/js/alertify/css/alertify.core.css?Ver=<?php echo VERSION;?>" />
<link  rel="stylesheet" href="<?php echo $theme_url;?>/js/alertify/css/alertify.default.css?Ver=<?php echo VERSION;?>" id="toggleCSS" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link  rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.css?Ver=<?php echo VERSION;?>"/>
<link media="none" onload="if(media!='all')media='all'"  rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css?Ver=<?php echo VERSION;?>" />	

<link media="none" onload="if(media!='all')media='all'"  rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/jquery-multi-select/css/multi-select-metro.css?Ver=<?php echo VERSION;?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/bootstrap-toggle-switch/css/bootstrap-toggle.min.css?Ver=<?php echo VERSION;?>" />

<link media="none" onload="if(media!='all')media='all'"  rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/select2/select2_metro.css?Ver=<?php echo VERSION;?>" />
<!-- END PAGE LEVEL SCRIPTS -->
<link   rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" />

<!-- BEGIN CORE PLUGINS -->

<script src="<?php echo $theme_url; ?>/assets/plugins/jquery-1.10.1.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script  src="<?php echo $theme_url; ?>/assets/scripts/app.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url; ?>/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script> 
<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap/js/bootstrap.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-switch/dist/js/bootstrap-switch.min.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-toggle-switch/js/bootstrap-toggle.min.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>


<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-modal/js/bootstrap-modal.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<!--[if lt IE 9]>
	<script src="assets/plugins/excanvas.min.js"></script>
	<script src="assets/plugins/respond.min.js"></script>  
	<![endif]-->
<script src="<?php echo $theme_url; ?>/assets/plugins/uniform/jquery.uniform.min.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js?Ver=<?php echo VERSION;?>"></script>

<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver=<?php echo VERSION;?>"></script>   


<!-- modal added by sanket -->
<link href="<?php echo $theme_url; ?>/assets/css/prettify.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>



<script async="" src="<?php echo $theme_url;?>/assets/scripts/ui-modals.js?Ver=<?php echo VERSION;?>"></script>

<!-- modal added by sanket -->

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script async  src="<?php echo $theme_url; ?>/js/jquery.tinylimiter.js?Ver=<?php echo VERSION;?>"></script>

<!--validation -->
<script async  type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.validate.js?Ver=<?php echo VERSION;?>"></script>
<script async type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/additional-methods.js?Ver=<?php echo VERSION;?>"></script>
<script async type="text/javascript" src="<?php echo $theme_url; ?>/assets/scripts/form-validation.js?Ver=<?php echo VERSION;?>"></script>

<script async type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/modernizr.js?Ver=<?php echo VERSION;?>"></script>

<script async type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/prettify.js?Ver=<?php echo VERSION;?>"></script>
<script async src="<?php echo $theme_url;?>/js/alertify/js/alertify.min.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>
<style>
    #timer_comment{
        width:175px;
    }
    </style>
<script>
    $(document).ready(function(){
        $('body').show();
    });        
</script>
<script type="text/javascript">

$(document).ready(function() {    
   App.init();
});
</script>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- END JAVASCRIPTS -->
<style>
    .btn-ok{
        color: #fff !important;
        background-color: #007fbe !important;
        border-color: #006ea5 !important;
        padding: 5px 10px !important;
        font-size: 12px;
        line-height: 1.5 !important;
    }
    .btn-cancel{
        color: #fff !important;
        background-color: #f34235 !important;
        border-color: #f33123 !important;
        padding: 5px 10px !important;
        font-size: 12px;
        line-height: 1.5 !important;
        clear: unset !important;
    }
    .popover-title
    {
        display:none;
        /*color:black;*/
    }
    .popover.confirmation
    {
        width:146px;
    }
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-footer-fixed page-sidebar-closed" style="display: none;">   <!--page-header-fixed page-footer-fixed page-sidebar-closed-->
	<div class="se-pre-con" style="display:none;"></div>
	<div id="dvLoading"><div class="dvLoading"></div></div>
	<!--<div class="modal hide h modal-hw modal-margin-100"   id="dvLoading" data-backdrop="static" data-keyboard="false">
        <div style="background-color: #fff !important;" class="modal-header">
            <h4><b>Loading...</b></h4>
        </div>
        <div class="modal-body">
            <div class="progress progress-striped active padding-10">
                <div class="bar" style="width: 100%;"></div>
            </div>
        </div>
 </div>-->
<?php echo $header; ?>

<!-- BEGIN CONTAINER -->
<div class="page-container row">
<!--<button class="btn btn-outline purple-sharp  uppercase" data-toggle="confirmation" data-placement="right">Confirmation on right</button>-->
	<?php echo $content_left; ?>
	
	<!-- BEGIN PAGE -->
  	<div class="page-content">

		<?php echo $content_side; ?>

		
	</div>
  	<!-- END PAGE -->
	
</div>

<!-- END CONTAINER -->
<?php echo $footer; ?>
<script type="text/javascript"  src="https://schedullo.atlassian.net/s/46ca45817299cda372ec85e43e755fc5-T/en_UK-bzw2ah/72000/3a934f707717766868991cff037d9e95/2.0.13/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-UK&collectorId=7fd37956"></script>
<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
        { (i[r].q=i[r].q||[]).push(arguments)}
        ,i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-71351139-1', 'auto');
        ga('send', 'pageview');
</script>

<script>
  window.intercomSettings = {
    app_id: "e9gmq7wa",
    company: <?php echo json_encode($company);?>,
    name: "<?php echo $this->session->userdata("username"); ?>", // Full name
    email: "<?php echo $this->session->userdata("email"); ?>", // Email addres
    created_at: <?php echo getUserSignupDateTime(); ?>, // Signup date as a Unix timestamp
    "admin": "<?php if($this->session->userdata("is_administrator") == "1"){ echo "Yes"; } else { echo "No"; }?>" ,// will be yes or not depending if the user is an admin
    tasks : "<?php echo getTotalTaskByUser();?>", // total task created by user
    company_name : "<?php echo get_company_name();?>", // company name
    users : "<?php echo count_user_by_company($this->session->userdata("company_id"));?>", // total user in company
    swimlanes :"<?php echo get_total_swimlanes($this->session->userdata("user_id"));?>", // total swimlanes under the user
    projects :"<?php echo get_total_projects($this->session->userdata("user_id"));?>", // total project created by user
	"Billing portal URL" : "<?php echo $billing_portal_detail['billing_portal_url'];?>", //billing portal managment URL
	"Subscription status" : "<?php echo $billing_portal_detail['subscription_status'];?>",
	"Trial end date" : "<?php echo $billing_portal_detail['trial_end_date']?>",
	"Payment method found" : "<?php echo $billing_portal_detail['payment_method'];?>",
	"Number of categories" : "<?php echo count(get_company_category($this->session->userdata("company_id"),'Active'));?>",
        "Customer module status":"<?php echo $this->session->userdata('customer_module_activation');?>",
        "Pricing module status":"<?php echo $this->session->userdata('pricing_module_status');?>",
        "Timesheet":"<?php if($this->session->userdata('timesheet_module_status')=='1'){echo "yes"; }else{ echo "no";}?>",
        "Customer User":"<?php echo count_customer_user_by_company($this->session->userdata('company_id'));?>",
        "External User":"<?php if($this->session->userdata('is_customer_user') == '1'){ echo 'Yes'; }else{ echo "No"; } ?>"
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/e9gmq7wa';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
<!--TImEr-->
<script src="<?php echo base_url().getThemeName();?>/assets/js/timer.jquery.js?Ver=<?php echo VERSION;?>"></script>

<div class="timerbox" id="common-timerbox"  style="display:none;z-index: 100;">
	  		<button type="button" onclick="showhide();" class="close timer_pos" data-dismiss="modal" aria-hidden="true"></button>
	  		<h4> Timer  </h4>
	  		<input type="hidden" name="timer_task_id" id="timer_task_id" value="" />
	  		<input type="hidden" name="current_page" id="current_page" value="" />
	  		<input type="hidden" name="task_com_status" id="task_com_status" value="" />
			<div class="timerinnerbox" id="timer_div">
				<h2 class="heading2" id="timer_task_title" style="display:none"></h2>
				<div class="time-box">
					<div class="time-lt">
						<p class="pera"> Timer </p>
						<h3 class="heading3 timer" id="timer" > 00:00:00 </h3>
						<!--<input type="text" name="timer" id="timer" class="heading3 timer" placeholder="0 sec" />-->
					</div>
					<div class="time-rt">
						<dl>
							<dt>Total task time :</dt>
							<dd id="total_timer">00:00:00</dd>

							<dt>Interruptions : </dt>
							<dd id="total_interruptions"><?php echo count_today_interruptions();?></dd>
						</dl>
			   		</div>
					<div class="clearfix"> </div>
				</div>

                                <div class="timer-btn row" style="margin-right: 0px !important;margin-left: 2px !important;">
					<input type="text" name="timer_comment" id="timer_comment" value="" placeholder="Add notes before stopping" style="display:none; line-height: 1.8;" class="radius-b"/>
					<a href="javascript:void(0)" class="btn blue txtbold" id="stop" style="display: none;"> Stop</a>
					<a href="javascript:void(0)" class="btn blue txtbold" id="select_task">Click here to select a task</a>
					<a href="javascript:void(0)" class="btn blue txtbold" id="start" style="display: none;"> Start</a>
					<a href="javascript:void(0)" class="btn blue txtbold" id="start_interruption" style="display:none;"> Start</a>
					<a href="javascript:void(0)" class="btn blue txtbold" id="change_tak" style="display: none;"> Change task</a>
					<!--<a href="javascript:void(0)" class="btn blue txtbold" id="resumeme" style="display: none;"> Resume</a>-->
					<!--<a href="javascript:void(0)" class="btn blue txtbold" id="endtask" style="display: none;"> End Task</a>-->
					<input type="hidden" name="hdn_timer" id="hdn_timer" value="" />
					<input type="hidden" name="is_timer_on" id="is_timer_on" value="0" />
					<input type="hidden" name="is_timer_popup" id="is_timer_popup" value="0" />
				</div>
                                
				<div class="timer-list text-right" id="statics_link" style="display: none;">
					<ul class="list-unstyled">
						<li> <a data-toggle="modal" href="#statistics" onclick="get_statistics();"> Statistics </a> | </li>
						<li> <a data-toggle="modal" href="#work_log" onclick="get_work_log();"> Work log </a></li>

						<!--<li> <a href="#"> Settings </a>  </li>-->
					</ul>
				</div>
			</div>

			<div class="timerinnerbox" id="reason_div" style="display: none;">
				<h2 class="heading2"><b>Why do you want to stop?</b></h2>
				<div class="reason_scroll">
                                    <ul style="padding-inline-start: 0px;-webkit-padding-start:0px;">
						<li class="reason" id="resume"><a href="javascript:void(0);"  >Undo stop</a></li>
						<li class="reason" id="endtask"><a href="javascript:void(0);" >Task complete</a></li>
						<li class="reason" onclick="add_interruption('<?php echo htmlspecialchars(json_encode("Need to leave"));?>');"><a href="javascript:void(0);" >Need to leave</a></li>
						<li class="reason" onclick="add_interruption('<?php echo htmlspecialchars(json_encode("Meeting"));?>');"><a href="javascript:void(0);" >Meeting</a></li>
						<li class="reason" onclick="add_interruption('<?php echo htmlspecialchars(json_encode("Phone call"));?>');"><a href="javascript:void(0);" >Phone call</a></li>
						<li class="reason" onclick="add_interruption('<?php echo htmlspecialchars(json_encode("Email"));?>');"><a href="javascript:void(0);" >Email</a></li>
						<li class="reason" onclick="add_interruption('<?php echo htmlspecialchars(json_encode("Co-worker interruption"));?>');"><a href="javascript:void(0);" >Co-worker interruption</a></li>
						<li class="reason" onclick="add_interruption('<?php echo htmlspecialchars(json_encode("Others"));?>');"><a href="javascript:void(0);" >Others</a></li>

					</ul>
				</div>
			</div>
	  	</div>
</body>
<!-- END BODY -->
<script>
     
function showhide()
{   
        $('#common-sortbybox').hide();
        $("#common-projbox").hide();
        $("#common-statusbox").hide();
        $("#common-duedatebox").hide();
        $("#common-teambox").hide();
        $('#common-calendbox').hide();
        if($('#common-timerbox').is(':visible')){
            $('#common-timerbox').hide();
            $("#is_timer_on").val("0");
            if(getCookie('timer_status') != 'stop' && getCookie('timer_task_id')){
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "positionClass": "toast-top-right",
                    "onclick": null,
                    "showDuration": "5000",
                    "hideDuration": "5000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                toastr.warning('Your timer is still running in the background.','Click on the Timer icon to display it again.');
            }
        } else {
            $('#common-timerbox').show();
            $( "#common-timerbox" ).draggable();
            $("#is_timer_on").val("1");
        }
        $('#common-colorbox').hide();
}

    $(document).ready(function(){
         <?php
         
    if(isset($_COOKIE['timer_task_id']) && $_COOKIE['timer_task_id']!='')
    {
         
        $task_id = $_COOKIE['timer_task_id'];
        $title = get_task_title($task_id);
        ?>
        $("#timer_task_id").val('<?php echo $task_id;?>');
        chk_task_selected('<?php echo $title;?>');
        if(getCookie('timer_status') && getCookie('timer_status') == 'stop'){}else{
        showhide();}<?php
    }?>
            
        if(ACTIVE_MENU == 'from_kanban' || ACTIVE_MENU == 'from_calendar' || ACTIVE_MENU == 'weekView' || ACTIVE_MENU == 'NextFiveDay' || ACTIVE_MENU == 'from_project') {
        $("#common-timerbox").css("height","265px");    
        $('#statics_link').show();
        }    
    });
    </script>
    
</html>