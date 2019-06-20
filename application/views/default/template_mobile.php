<?php
$site_setting=site_setting();
$theme_url = base_url().getThemeName();
$uriseg=uri_string();
$uri=explode('/',$uriseg);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>:: Schedullo Mobile ::</title>
 	<!-- Bootstrap -->
 	<link href="<?php echo $theme_url; ?>/mobile-js-css/css/bootstrap.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/mobile-js-css/css/default.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/mobile-js-css/css/media.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/mobile-js-css/css/reset.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/mobile-js-css/css/animate.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<!--<link href="<?php echo $theme_url; ?>/mobile-js-css/css/datepicker.css?Ver=<?php echo VERSION;?>" rel="stylesheet">-->
	<link rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css?Ver=<?php echo VERSION;?>" />
	<link href="<?php echo $theme_url; ?>/css/mob-developer.css?Ver=<?php echo VERSION;?>" rel="stylesheet"> 
	<link rel="stylesheet" href="<?php echo $theme_url;?>/js/alertify/css/alertify.core.css?Ver=<?php echo VERSION;?>" />
	<link rel="stylesheet" href="<?php echo $theme_url;?>/js/alertify/css/alertify.default.css?Ver=<?php echo VERSION;?>" id="toggleCSS" />
	
	<script src="<?php echo $theme_url;?>/js/alertify/js/alertify.min.js?Ver=<?php echo VERSION;?>"></script>

	
	
 	  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
    <script src="<?php echo $theme_url; ?>/js/jquery.js?Ver=<?php echo VERSION;?>"></script>
    <!--	<script src="<?php echo $theme_url;?>/mobile-js-css/js/main.js"></script>-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo $theme_url; ?>/js/bootstrap.min.js?Ver=<?php echo VERSION;?>"></script>
    
    
    <!--validation -->
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.validate.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/additional-methods.js?Ver=<?php echo VERSION;?>"></script>


<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/form-validation.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/modernizr.js?Ver=<?php echo VERSION;?>"></script>

	
    
  </head>
  
   
  
<body>
<div id="dvLoading"><div class="dvLoading"></div></div>
<!-- #################################################################################### --> 
     <!-- Fixed navbar -->
     <?php echo $header; ?>


<!-- #################################################################################### -->
<?php echo $center; ?>
<!-- #################################################################################### -->    


<SCRIPT>
    function setupLabel() {
        if ($('.label_check input').length) {
            $('.label_check').each(function(){ 
                $(this).removeClass('c_on');
            });
            $('.label_check input:checked').each(function(){ 
                $(this).parent('label').addClass('c_on');
            });                
        };
        if ($('.label_radio input').length) {
            $('.label_radio').each(function(){ 
                $(this).removeClass('r_on');
            });
            $('.label_radio input:checked').each(function(){ 
                $(this).parent('label').addClass('r_on');
            });
        };
    };
    $(document).ready(function(){
        $('.label_check, .label_radio').click(function(){
            setupLabel();
        });
        setupLabel(); 
    });
    
function loadScroll()
{
	var settings = {
			text : 'To Top',
			min : 200,
			inDelay : 600,
			outDelay : 400,
			containerID : 'toTop',
			containerIDBtm : 'toBottom',
			containerHoverID : 'toTopHover',
			containerHoverIDBtm : 'toBottomHover',
			scrollSpeed : 500,
			easingType : 'linear'
		};
		var toTopHidden = true;
		var toBottomHidden = false;
		var toTop = $('#' + settings.containerID);
		var toBottom = $('#' + settings.containerIDBtm);
		toTop.click(function(e) {
			e.preventDefault();
			$.scrollTo(0, settings.scrollSpeed, {
				easing : settings.easingType
			});
		});
		
		toBottom.click(function(e) {
			e.preventDefault();
			$.scrollTo('100%', settings.scrollSpeed, {
				easing : settings.easingType
			});
		});
		
		$(window).scroll(function() {
			var sd = $(this).scrollTop();
			//alert(sd);
			if (sd > settings.min && toTopHidden) {
				
				toTop.fadeIn(settings.inDelay);
				toBottom.fadeOut(settings.inDelay);
				toTopHidden = false;
				toBottomHidden=true;
			} else if (sd <= settings.min && !toTopHidden) {
				
				toTop.fadeOut(settings.outDelay);
				toBottom.fadeIn(settings.inDelay);
				toTopHidden = true;
				toBottomHidden=false;
			}
			
			
/*
			if ($(window).scrollTop() >= 15) {
		        $('div.shadow-top').show();
		    } else {
		        $('div.shadow-top').hide();
		    }
		    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 15) {
		        $('div.shadow-bottom').show();
		    } else {
		        $('div.shadow-bottom').hide();
		    }*/


		});
}	


function goBack() {
    window.history.back();
}

</SCRIPT>

<!--<script src="<?php echo $theme_url;?>/mobile-js-css/js/bootstrap-datepicker.js" type="text/javascript"></script>-->
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js?Ver=<?php echo VERSION;?>"></script>

</body>
</html>
