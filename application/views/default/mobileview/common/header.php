
<?php
$site_setting=site_setting();
$theme_url = base_url().getThemeName();
$uriseg=uri_string();
$uri=explode('/',$uriseg);
//echo $uri[1];die;

$title="";

if($uri[1]=='task_thisweek'){$title = "My Time This Week";}
if($uri[1]=='mywatchlist'){$title = "My Watch List";}
if($uri[1]=='task_today'){$title = "My Allocation Time Today";}
if($uri[1]=='team_task_due'){$title = "Task Due Todays";}
if($uri[1]=='overdue_task'){$title = "OverDue Tasks";}
if($uri[1]=='team_time_thisweek'){$title = "My Team's Time this Week ";}
if($uri[1]=='team_allocation_by_category'){$title = "Team Allocation by Category";}
if($uri[1]=='task_since_last_login'){$title = "Task Since Last Login";}
if($uri[1]=='todays_task'){$title = "Task to do List";}
if($uri[1]=='list_project'){$title = "Projects";}
if($uri[1]=='kanban'){$title = "Kanban View";}
if($uri[1]=='add_task' || $uri[1]=='add_ind_task'){$title = "Add Task";}
if($uri[1]=='edit_task' || $uri[1]=='edit_ind_task'){$title = "Edit Task";}
if($uri[1]=='view_task'){$title = "Task";}
if($uri[1]=='insertProject'){$title = " Add Project";}
if($uri[1]=='editProject'){$title = " Edit Project";}
if($uri[1]=='project_tasks'){$title = " Task Projecs";}


if(!check_user_authentication()){
	$login = base_url()."home/login";
}else{
	$login = base_url()."home/main";
}


?>

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
	if($msg == 'expired'){ echo 'Your account is expired.'; }
	if($msg == 'reset'){ echo 'Your Password has been reset successfully.'; }
	if($msg == 'fail'){ echo 'Sorry ! Your connection has expired.'; }
	if($msg == 'expire'){ echo 'Your subscription has been expired.'; }
	 ?></span>
<?php 

} 
?>	

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
         <!-- <a class="navbar-brand <?php if($uri[1]=='login' || $uri[1]=='signup' || $uri[1]=='signup1' || $uri[1]=='signup2' || $uri[1]=='signup3'){ echo "logo-center";}elseif($uri[1]=='myprofile'){"";}else{"";}?> " href="javascript://"> <img src="<?php echo $theme_url; ?>/img/logo.png" alt="logo" /> </a>-->
         <?php if($uri[1]=='login' || $uri[1]=='signup' || $uri[1]=='signup1' || $uri[1]=='signup2' || $uri[1]=='signup3'){ ?> 	
          	<a class="navbar-brand logo-center" href="<?php echo $login;?>"> <img src="<?php echo $theme_url; ?>/img/logo.png" alt="logo" /> </a>
          		<?php }elseif($uri[1]=='myprofile'){ ?>
          			<a class="navbar-brand" href="<?php echo $login;?>"> <img src="<?php echo $theme_url; ?>/img/logosm.png" alt="logo" /> </a>
          			<?php }else{ ?>
          	<a class="navbar-brand" href="<?php echo $login;?>"> <img  class="" src="<?php echo $theme_url; ?>/img/logosm.png" alt="logo" /> <span><?php echo $title;?></span></a>			
          				<?php } ?>
          	
        </div>
        <div class="navbar-collapse collapse">
           <ul class="nav navbar-nav navbar-right margin-bottom-10">
            <!--<li class="active"><a href="#">About us</a></li>
            <li><a href="#">Product</a></li>
			<li><a href="#">Pricing</a></li>
			<li><a href="#">Blogs</a></li>
			<li><a href="#">Free Trial</a></li>-->
			
			<?php if(!check_user_authentication()){?>
			<li class="loginbtn"><a href="<?php echo site_url('home/login');?>">Login</a></li>
			<?php }else{ ?>
			<li><a href="<?php echo site_url('user/myprofile');?>">Profile</a></li>
			<li class="loginbtn"><a href="<?php echo site_url('home/logout');?>">logout</a></li>
			<?php } ?>
          </ul>
        </div> 
      </div>
    </div>
	</div>
</div>

