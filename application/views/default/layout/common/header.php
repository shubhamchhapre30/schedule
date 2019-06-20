
<?php
$theme_url = base_url().getThemeName();


?>
<style type="text/css">
.msg_profile {
    background-color: green;
    border: 1px solid #ffffff;
    border-radius: 3px;
    box-shadow: 0 0 5px #8b8d8b;
    color: white;
    font-size: 13px;
    font-weight: bold;
    display: block;
    left: 38.33%;
    max-width: 650px;
    padding: 10px;
    position: absolute;
    top: 2px;
    z-index: 2147483647;

</style>
	
<?php 

if($msg){ ?>
	<script>
		$(document).ready(function() {
			$('#msg_index').slideDown('slow').delay(50000).slideUp('slow');
		});
	</script>
	<span id="msg_index" class="msg_profile">
	<?php 
	if($msg == 'forgetsuccess'){ echo 'Your request has been send successfully. please check your email.'; }
	if($msg == 'register'){ echo 'You are Successfully registered. Please check your mail to activate your account.'; } 
	if($msg == 'activate'){ echo 'Your account has been verified successfully.'; }
	if($msg == 'expired'){ echo 'Your activation link has been expired.'; }
	if($msg == 'reset'){ echo 'Your Password has been reset successfully.'; }
	if($msg == 'fail'){ echo 'Sorry ! Your connection has expired.'; }
	if($msg == 'expire'){ echo 'Your subscription has been expired.'; }
         ?></span>
<?php 

} 
?>	

<!-- #################################################################################### --> 
     <!-- Fixed navbar -->
<div class="wrapper row1">	 
	<div class="header-top">
   	 	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://www.schedullo.com"> <img src="<?php echo $theme_url; ?>/img/logo.png" alt="logo" /> </a>
        </div>
        <div class="navbar-collapse collapse">
           
          <!-- <ul class="nav navbar-nav navbar-right">
            <li class="loginbtn"><a href="<?php echo site_url('home/login');?>">Login</a></li>
          </ul> -->
        </div> 
      </div>
    </div>
	</div>
</div>	