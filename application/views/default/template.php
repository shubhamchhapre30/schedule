<?php
$theme_url = base_url().getThemeName();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>:: Schedullo ::</title>

    <!-- Bootstrap -->
    <link href="<?php echo $theme_url; ?>/css/bootstrap.min.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/css/default.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/css/media.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/css/reset.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
	<link href="<?php echo $theme_url; ?>/css/animate.css?Ver=<?php echo VERSION;?>" rel="stylesheet"> 
	<link href="<?php echo $theme_url; ?>/css/developer.css?Ver=<?php echo VERSION;?>" rel="stylesheet"> 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo $theme_url; ?>/js/jquery.js?Ver=<?php echo VERSION;?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo $theme_url; ?>/js/bootstrap.min.js?Ver=<?php echo VERSION;?>"></script>
    
    
    <!--validation -->
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.validate.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/additional-methods.js?Ver=<?php echo VERSION;?>"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/form-validation.js?Ver=<?php echo VERSION;?>"></script>
<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
        { (i[r].q=i[r].q||[]).push(arguments)}
        ,i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-71351139-1', 'auto');
        ga('send', 'pageview');
</script>

  </head>
  <body class="has-js">


 <?php echo $header; ?>

 <?php echo $center; ?>

<?php echo $footer; ?>

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
</SCRIPT>
</body>
</html>
