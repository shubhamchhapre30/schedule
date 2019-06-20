<?php
$uriseg=uri_string();
$uri=explode('/',$uriseg);

$method = $this->uri->segment(1); 
$fun =   $this->uri->segment(2);


 ?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar nav-collapse collapse"  id="non-printable" style="display:block">
			<!-- BEGIN SIDEBAR MENU -->        
			<ul class="page-sidebar-menu">
				<li class="margin-bottom-10">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li class="divider"></li>
				<li class="start <?php echo ($fun=='dashboard')?'active':'' ?>">
					<a href="<?php echo site_url('home/dashboard'); ?>">
					<i class="fa fa-dashboard"></i> 
					<span class="title">Dashboard</span>
					<?php echo (isset($fun) && $fun=='dashboard')?'<span class="selected"></span>':'' ?>
					</a>
				</li>
				<?php if((isset($adminRights->admin) && $adminRights->admin->view==1) || checkSuperAdmin()){ ?>
				<li class="start <?php echo (isset($method) && $method=='admin')?'active':'' ?>">
					<a href="<?php echo site_url('admin') ?>">
					<i class="fa fa-home"></i> 
					<span class="title">Admin</span>
					<?php echo (isset($method) && $method=='admin')?'<span class="selected"></span>':'' ?>
					</a>
				</li>
				
				<?php } ?>  
				
				
				
				<li class="start <?php echo (isset($method) && ($method=='user' ))?'active':'' ?>">
								<a href="javascript:;">
								
								<i class="fa fa-th"></i> 
								<span class="title">Manage User</span>
								<?php echo (isset($method) && ($method=='user' ))?'<span class="arrow open"></span><span class="selected"></span>':'<span class="arrow"></span>' ?>
								
								</a>
								<ul class="sub-menu">
				<?php if((isset($adminRights->user) && $adminRights->user->view==1) || checkSuperAdmin()){ ?>
				<li class="start <?php echo (isset($method) && $method=='user')?'active':'' ?>">
					<a href="<?php echo site_url('user') ?>">
					<i class="fa fa-user"></i> 
					<span class="title">User</span>
					<?php echo (isset($method) && $method=='user')?'<span class="selected"></span>':'' ?>
					</a>
				</li>
				
				<?php }
				  ?> 
				
				  </ul>
				  </li>
				  
				  
				  
				  
				  
				
				 <?php if((isset($adminRights->Plan) && $adminRights->Plan->view==1) || checkSuperAdmin()){ ?>
				<li class="start <?php echo (isset($method) && $method=='plan')?'active':'' ?>">
					<a href="<?php echo site_url('plan') ?>">
					<i class="fa fa-gift"></i> 
					<span class="title">Plan </span>
					<?php echo (isset($method) && $method=='plan')?'<span class="selected"></span>':'' ?>
					</a>
				</li>
				
				<?php }
				  ?>  
				
				
				
				
				
				
				
				  <?php if((isset($adminRights->Company) && $adminRights->Company->view==1) || checkSuperAdmin()){ ?>
				<li class="start <?php echo (isset($method) && $method=='Company')?'active':'' ?>">
					<a href="<?php echo site_url('Company') ?>">
					<i class="fa fa-briefcase"></i> 
					<span class="title">Company </span>
					<?php echo (isset($method) && $method=='Company')?'<span class="selected"></span>':'' ?>
					</a>
				</li>
				
				<?php }
				  ?>  
				  
				   <?php if((isset($adminRights->plan_subscription) && $adminRights->plan_subscription->view==1) || checkSuperAdmin()){ ?>
                                            <li class="start <?php echo (isset($method) && $method=='plan_subscription')?'active':'' ?>">
                                                    <a href="<?php echo site_url('plan_subscription') ?>">
                                                    <i class="fa fa-briefcase"></i> 
                                                    <span class="title">Plan Subscription </span>
                                                    <?php echo (isset($method) && $method=='plan_subscription')?'<span class="selected"></span>':'' ?>
                                                    </a>
                                            </li>
                                    <?php }  ?>  
			
                                    <?php if((isset($adminRights->plan_subscription) && $adminRights->plan_subscription->view==1) || checkSuperAdmin()){ ?>
                                            <li class="start <?php echo (isset($method) && $method=='API')?'active':'' ?>">
                                                    <a href="<?php echo site_url("API"); ?>">
                                                    <i class="fa fa-book"></i> 
                                                    <span class="title">Apis</span>
                                                    <?php echo (isset($method) && $method=='API')?'<span class="selected"></span>':'' ?>
                                                    </a>
                                            </li>
                                    <?php }  ?>         
				 
				 
			
			
				 
				<?php if((isset($adminRights->Color) && $adminRights->Color->view==1) || checkSuperAdmin()){ ?>
				<li class="start <?php echo (isset($method) && $method=='Color')?'active':'' ?>">
					<a href="<?php echo site_url('Color') ?>">
					<i class="fa fa-bookmark-o"></i> 
					<span class="title">Colour Master </span>
					<?php echo (isset($method) && $method=='Color')?'<span class="selected"></span>':'' ?>
					</a>
				</li>
				
				<?php }
				  ?>  
				  
				 
				 
				 
				 
				  
				
				  
				  
				<?php 
						if(((isset($adminRights->Setting) && $adminRights->Setting->view==1)) ||  checkSuperAdmin()){ ?>
							<li class="start <?php echo (isset($method) && ($method=='homecontent' || $method=='twilio_setting' ||$method=='Site_setting' || $method=='MetaSetting' || $method=='payment_setting' || $method=='EmailTemplate' || $method=='Pages' || $method=='Tax' || $method=='banner'))?'active':'' ?>">
								<a href="javascript:;">
								
								<i class="fa fa-cogs"></i> 
								<span class="title">Settings</span>
								<?php echo (isset($method) && ($method=='homecontent' || $method=='twilio_setting' || $method=='Site_setting' || $method=='MetaSetting' || $method=='payment_setting' || $method=='EmailTemplate' || $method=='Pages' || $method=='Tax' || $method=='banner'))?'<span class="arrow open"></span><span class="selected"></span>':'<span class="arrow"></span>' ?>
								
								</a>
								<ul class="sub-menu">
									
									<li class="<?php echo ($method=='Site_setting' && $fun=='')?'active':'' ?>">
										<a href="<?php echo site_url('Site_setting') ?>"><i class="fa fa-cog"></i> <span class="title">Site Setting</span>	</a>
									</li>
									<!--<li class="<?php echo ($method=='MetaSetting' && $fun=='')?'active':'' ?>">
										<a href="<?php echo site_url('MetaSetting') ?>"><i class="icon-cog"></i> <span class="title">Seo Setting</span>	</a>
									</li>-->
									<li class="<?php echo ($method=='EmailTemplate' && ($fun=='listEmailTemplate' || $fun=='editEmailTemplate'))?'active':'' ?>">
										<a href="<?php echo site_url('EmailTemplate') ?>"><i class="fa fa-cog"></i> <span class="title">Email Template</span>	</a>
									</li>
									<!--<li class="<?php echo ($method=='Pages')?'active':'' ?>">
										<a href="<?php echo site_url('Pages') ?>"><i class="icon-cog"></i> <span class="title">Pages</span>	</a>
									</li>
									
									<!-- <li class="<?php echo ($method=='Site_setting' && $fun=='facebook_setting')?'active':'' ?>">
										<a href="<?php echo site_url('Site_setting/facebook_setting') ?>"><i class="icon-th-large"></i> <span class="title">Facebook Setting</span>	</a>
									</li>
									
									<li class="<?php echo ($method=='Site_setting' && $fun=='google_setting')?'active':'' ?>">
										<a href="<?php echo site_url('Site_setting/google_setting') ?>"><i class="icon-th-large"></i> <span class="title">Google Setting</span>	</a>
									</li> -->
									
									<!--<li class="<?php echo ($method=='Site_setting' && $fun=='add_image_setting')?'active':'' ?>">
										<a href="<?php echo site_url('Site_setting/add_image_setting') ?>"><i class="icon-cog"></i> <span class="title">Image Setting</span>	</a>
									</li>-->
									
									<li class="<?php echo ($method=='payment_setting' && $fun=='')?'active':'' ?>">
										<a href="<?php echo site_url('payment_setting') ?>"><i class="fa fa-cog"></i> <span class="title">Payment Setting</span>	</a>
									</li>
									
									<li class="<?php echo ($method=='Site_setting' && $fun=='popup_setup')?'active':'' ?>">
										<a href="<?php echo site_url('Site_setting/popup_setup') ?>"><i class="fa fa-cog"></i> <span class="title">Popup Setup</span>	</a>
									</li>
								

								</ul>
							</li>
							
			
			
			
			
			
			<?php } ?>
			
			
			
				  
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->
