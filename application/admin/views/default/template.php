<?php
$site_setting=site_setting();  
clear_cache();
//$site_timezone=tzOffsetToName($site_setting->site_timezone);
//date_default_timezone_set($site_timezone);
$user_file_path=$this->config->item('base_url_site').  getThemeName();

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
		<title>:: Administration ::</title>
		
		<script type="application/javascript">
			var baseUrl='<?php echo base_url(); ?>';
			var baseThemeUrl='<?php echo base_url().getThemeName(); ?>';
			//alert(baseThemeUrl);
		</script>
		<!-- Style Sheet  -->
	<link href="<?php echo $user_file_path; ?>/assets/plugins/bootstrap/css/bootstrap.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
	
	<link href="<?php echo base_url().getThemeName(); ?>/assets/plugins/font-awesome/css/font-awesome.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url().getThemeName(); ?>/assets/css/style-metro.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url().getThemeName(); ?>/assets/css/style.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url().getThemeName(); ?>/assets/css/style-responsive.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url().getThemeName(); ?>/assets/css/themes/light.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="<?php echo base_url().getThemeName(); ?>/assets/plugins/uniform/css/uniform.default.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/css/select2.css?Ver=<?php echo VERSION;?>" />
	<link href="<?php echo base_url().getThemeName(); ?>/assets/plugins/gritter/css/jquery.gritter.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-ui/jquery-ui.min.css?Ver=<?php echo VERSION;?>"/>
	<link rel="stylesheet" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/datatables/datatables.min.css?Ver=<?php echo VERSION;?>" />
	<link rel="stylesheet" href="<?php echo base_url().getThemeName(); ?>/css/developer.css?Ver=<?php echo VERSION;?>"></link>
	<!-- Style Sheet  -->
	<!-- JavaScript -->
	<!-- BEGIN CORE PLUGINS -->   <script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-1.12.4.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-migrate-1.2.1.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-ui/jquery-ui.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>      
	<script src="<?php echo $user_file_path; ?>/assets/plugins/bootstrap/js/bootstrap.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
	<script src="<?php echo $user_file_path; ?>/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.blockui.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/gritter/js/jquery.gritter.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/uniform/jquery.uniform.min.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
	
	<!--[if lt IE 9]>
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/excanvas.min.js"></script>
	<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/respond.min.js"></script>  
	<![endif]-->   
	<!-- JavaScript -->
	<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/app.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
	
	<script>
		jQuery(document).ready(function() {     
		  App.init();		  
		});
	</script>
	
	<style type="text/css" >

    @media print
    {
    	#non-printable { display: none; }
    	#content { display: block; }
    }

</style>

	</head>
	<!-- login -->
<body class="<?php echo (!check_admin_authentication())?'login':'page-header-fixed'; ?>">


	<?php echo $header; ?>
<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">	
	<?php echo $left; ?>
		<?php echo $center; ?>
	</div>  
	

<?php echo $footer; ?>
	
</body>
<script>
	// $(document).ready(function(){
		 function blockUI(el, centerY) {
			
            var el = jQuery(el); 
            el.block({
                    message: '<img src="<?php echo base_url().getThemeName(); ?>/assets/img/ajax-loading.gif" align="">',
                    centerY: centerY != undefined ? centerY : true,
                    css: {
                        top: '10%',
                        border: 'none',
                        padding: '2px',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: '#000',
                        opacity: 0.08,
                        cursor: 'wait'
                    }
                });
        }
         function unblockUI(el) {
            jQuery(el).unblock({
                    onUnblock: function () {
                        jQuery(el).removeAttr("style");
                    }
                });
        }
	// });
</script>
</html>
