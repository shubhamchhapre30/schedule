<?php
$uriseg=uri_string();
$uri=explode('/',$uriseg);

 ?>
<!-- BEGIN HEADER -->   
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand no-top-space margin-top-3" href="<?php echo base_url() ?>">
				<img src="<?php echo base_url().getThemeName(); ?>/images/logo.png" alt="logo" />
				</a>
				<!-- END LOGO -->
				<!-- BEGIN HORIZANTAL MENU -->
				<div class="navbar hor-menu hidden-phone hidden-tablet">
					<div class="navbar-inner">
						<ul class="nav">
							<li class="visible-phone visible-tablet">
								<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
								<form class="sidebar-search">
									<div class="input-box">
										<a href="javascript:;" class="remove"></a>
										<input type="text" placeholder="Search..." />            
										<input type="button" class="submit" value=" " />
									</div>
								</form>
								<!-- END RESPONSIVE QUICK SEARCH FORM -->
							</li>
							<li class="<?php echo (isset($uri[1]) && $uri[1]=='dashboard')?'active':'' ?>">
								<a href="<?php echo site_url('home/dashboard'); ?>">
								<i class="icon-dashboard"></i> 
								<span class="title">Dashboard</span>
								<?php echo (isset($uri[1]) && $uri[1]=='dashboard')?'<span class="selected"></span>':'' ?>
								</a>
							</li>
							<?php if((isset($adminRights->admin) && $adminRights->admin->view==1) || checkSuperAdmin()){ ?>
							<li class="<?php echo (isset($uri[0]) && $uri[0]=='admin')?'active':'' ?>">
								<a href="<?php echo site_url('admin') ?>">
								<i class="icon-home"></i> 
								<span class="title">Admin</span>
								<?php echo (isset($uri[0]) && $uri[0]=='admin')?'<span class="selected"></span>':'' ?>
								</a>
							</li>
							<?php }if(((isset($adminRights->user) && $adminRights->user->view==1) || (isset($adminRights->Stylist) && $adminRights->Stylist->view==1)) || checkSuperAdmin()){ ?>
							<li class="<?php echo (isset($uri[0]) && ($uri[0]=='user' || $uri[0]=='Stylist'))?'active':'' ?>">
								<a data-hover="dropdown" data-close-others="true" class="dropdown-toggle" href="javascript:;">
								
								<i class="icon-user"></i> 
								<span class="title">User</span>
								<?php echo (isset($uri[0]) && ($uri[0]=='user' || $uri[0]=='Stylist'))?'<span class="selected"></span>':'<span class="arrow"></span>' ?>
								
								</a>
								<ul class="dropdown-menu">
									<?php if((isset($adminRights->user) && $adminRights->user->view==1) || checkSuperAdmin()){ ?>
									<li class="<?php echo (isset($uri[0]) && $uri[0]=='user')?'active':'' ?>">
										<a href="<?php echo site_url('user') ?>"><i class="icon-user"></i> <span class="title">User</span>	</a>
									</li>
									<?php } if((isset($adminRights->Stylist) && $adminRights->Stylist->view==1) || checkSuperAdmin()){ ?>
									<li class="<?php echo (isset($uri[0]) && $uri[0]=='Stylist')?'active':'' ?>">
										<a href="<?php echo site_url('Stylist') ?>"><i class="icon-user"></i> <span class="title">Stylist</span>	</a>
									</li>
									<?php } ?>
								</ul>
							</li>
							
							<?php }  if(((isset($adminRights->eCommerce) && $adminRights->eCommerce->view==1)) ||  checkSuperAdmin()){ ?>
								
								<li class="<?php echo (isset($uri[0]) && ($uri[0]=='Color'))?'active':'' ?>">
								<a data-hover="dropdown" data-close-others="true" class="dropdown-toggle" href="javascript:;">
								
								<i class="icon-shopping-cart"></i> 
								<span class="title">E Commerce</span>
								<?php echo (isset($uri[0]) && ($uri[0]=='Color'))?'<span class="selected"></span>':'<span class="arrow"></span>' ?>
								
								</a>
								<ul class="dropdown-menu">
									<li class="<?php echo (isset($uri[0]) && $uri[0]=='Category')?'active':'' ?>">
										<a href="<?php echo site_url('Category') ?>"><i class="icon-th-large"></i> <span class="title">Category</span>	</a>
									</li>
									<li class="<?php echo (isset($uri[0]) && $uri[0]=='Color')?'active':'' ?>">
										<a href="<?php echo site_url('Color') ?>"><i class="icon-th-large"></i> <span class="title">Color</span>	</a>
									</li>
								</ul>
							</li>
								
							<?php } ?>
							<li class="">
								<a data-hover="dropdown" data-close-others="true" class="dropdown-toggle" href="javascript:;">
								<span class="selected"></span>
								Layouts
								<span class="arrow"></span>     
								</a>
								<ul class="dropdown-menu">
									<li >
										<a href="layout_language_bar.html">
										<span class="badge badge-roundless badge-important">new</span>Language Switch Bar</a>
									</li>
									<li >
										<a href="layout_horizontal_sidebar_menu.html">
										Horizontal & Sidebar Menu                     </a>
									</li>
									<li class="active">
										<a href="layout_horizontal_menu1.html">
										Horizontal Menu 1                    </a>
									</li>
									<li >
										<a href="layout_horizontal_menu2.html">
										Horizontal Menu 2                    </a>
									</li>
									<li >
										<a href="layout_promo.html">
										Promo Page                    </a>
									</li>
									<li >
										<a href="layout_email.html">
										Email Templates                     </a>
									</li>
									<li >
										<a href="layout_ajax.html">
										Content Loading via Ajax</a>
									</li>
									<li >
										<a href="layout_sidebar_closed.html">
										Sidebar Closed Page                    </a>
									</li>
									<li >
										<a href="layout_blank_page.html">
										Blank Page                    </a>
									</li>
									<li >
										<a href="layout_boxed_page.html">Boxed Page</a>
									</li>
									<li >
										<a href="layout_boxed_not_responsive.html">
										Non-Responsive Boxed Layout                     </a>
									</li>
									<li class="dropdown-submenu">
										<a href="javascript:;">
										More options
										<span class="arrow"></span>
										</a>
										
									</li>
								</ul>
								<b class="caret-out"></b>                        
							</li>
						</ul>
					</div>
				</div>
				<!-- END HORIZANTAL MENU -->
				
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<img src="<?php echo base_url().getThemeName(); ?>/assets/img/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->            
				<!-- BEGIN TOP NAVIGATION MENU -->              
				<ul class="nav pull-right">
					
					            
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						
						<span class="username"><?php echo get_admin_name(get_authenticateadminID()); ?></span>
						<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">							
							<li><?php echo anchor('home/changePassword','<i class="icon-key"></i> Change Password'); ?></li>
							<li><?php echo anchor('home/logout','<i class="icon-key"></i> Log Out'); ?>	</li>
                            <li><?php echo anchor('home/profile','<i class="icon-user"></i> My Profile'); ?></li>
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