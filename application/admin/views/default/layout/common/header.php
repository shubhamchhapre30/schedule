<?php
$uriseg=uri_string();
$uri=explode('/',$uriseg);

 ?>
<!-- BEGIN HEADER -->   
	<div class="header navbar navbar-inverse navbar-fixed-top"  id="non-printable">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div>
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand no-top-space margin-top-3" href="<?php echo base_url() ?>">
				<img src="<?php echo base_url().getThemeName(); ?>/images/logo.png" alt="logo" />
				</a>
				<!-- END LOGO -->
				
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
                                <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse" style="display: none">
				<img src="<?php echo base_url().getThemeName(); ?>/assets/img/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->            
				<!-- BEGIN TOP NAVIGATION MENU -->              
				<ul class="nav pull-right">
					
					            
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						
						<span class="username"><?php echo get_admin_name(get_authenticateadminID()); ?></span>
						<i class="fa fa-angle-down"></i>
						</a>
                                            <ul class="dropdown-menu" style="left: -34px;">							
							<li><?php echo anchor('home/changePassword','<i class="fa fa-key"></i> Change Password'); ?></li>
							<li><?php echo anchor('home/logout','<i class="fa fa-power-off "></i> Log Out'); ?>	</li>
                            <li><?php echo anchor('home/profile','<i class="fa fa-user"></i> My Profile'); ?></li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
					
				</ul>
				<!-- END TOP NAVIGATION MENU --> 
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
