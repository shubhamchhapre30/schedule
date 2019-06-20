<?php
if($this->input->is_ajax_request())
{
	$attributes = array('name'=>'dummy_frm','id'=>'dummy_frm');
	echo form_open('home',$attributes);
	echo form_hidden('dummy_input', 'dummy_value');
	echo form_close();
	//header_remove('X-requested-with');
	//$this->output->set_header("content-type: text/html; charset=UTF-8\r\n");
	//redirect('home');
	$this->output->set_header('Access-Control-Allow-Origin', '*');
?>
<script>
jQuery(document).ready(function() {
	$("#dummy_frm").submit();
	
});
</script>
<?php
}
else
{
//$headers = $this->input->request_headers();
//pr($headers);
//pr($this->input->get_request_header('X-requested-with',TRUE));die;
?>
<link href="<?php echo base_url().getThemeName(); ?>/assets/css/pages/login-soft.css?Ver<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<!-- BEGIN LOGO -->
	<div class="logo">
		<img src="<?php echo base_url().getThemeName(); ?>/images/logo.png" alt="" /> 
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		 <?php			 
		$attributes = array('name'=>'frmlogin','id'=>'usualValidate','class'=>'form-vertical login-form');
		echo form_open('home/login',$attributes);
	?>
			<h3 class="form-title">Login to your account</h3>
			<div class="alert alert-danger hide" id="loginresult">
				
			</div>
			<?php  if($msg == 'invalid'){  ?>
		<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button><span><?php echo INVALID_USERNAME;?></span></div>
	<?php } elseif($msg == 'valid'){  ?>
		<div class="alert alert-success"><button class="close" data-dismiss="alert"></button><span><?php echo LOGOUT_SUCCESS;?></span></div>
	<?php } elseif($msg == 'ResetSuccess'){  ?>
		<div class="alert alert-success"><button class="close" data-dismiss="alert"></button><span><?php echo RESET_SUCCESS;?></span></div>
	<?php } ?>
			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Username</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="fa fa-envelope"></i>
                                                <input class="m-wrap placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="username" id="username" style="height: 35px;"/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="fa fa-lock"></i>
                                                <input class="m-wrap placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" id="password" style="height: 35px;"/>
					</div>
				</div>
			</div>
			<div class="form-actions">
				
				<button type="submit" class="btn blue pull-right">
				Login <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
			<div class="forget-password">
				<h4>Forgot your password ?</h4>
				<p>
					no worries, click <a href="javascript:;"  id="forget-password">here</a>
					to reset your password.
				</p>
			</div>
			
		</form>
		<!-- END LOGIN FORM -->   
		<!-- BEGIN FORGOT PASSWORD FORM -->
		 <?php			 
		$attributes = array('name'=>'frmforget','id'=>'frmforget','class'=>'form-vertical forget-form');
		echo form_open('home/forgotPassword',$attributes);
	?>
			<h3 >Forget Password ?</h3>
			<div class="alert alert-danger hide" id="forgetresult"></div>
			<div class="alert alert-success hide"></div>
			<p>Enter your e-mail address below to reset your password.</p>
			<div class="form-group">
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-envelope"></i>
						<input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" autocomplete="off" name="email" id="email" />
					</div>
				</div>
			</div>
			<div class="form-control">
				<button type="button" id="back-btn" class="btn">
				<i class="m-icon-swapleft"></i> Back
				</button>
				<button type="submit" class="btn blue pull-right">
				Submit <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
		</form>
		<!-- END FORGOT PASSWORD FORM -->
</div>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/select2.min.js?Ver<?php echo VERSION;?>"></script>
<!--<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/jquery.validate.min.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>-->
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/backstretch/jquery.backstretch.min.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.form.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/login-soft.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>

<script>
		jQuery(document).ready(function() {
			<?php
				if($this->input->is_ajax_request())
				{
			?>
				$("#dummy_frm").submit();
			<?php
				}
			?>
		 
		  Login.init();
		  
		  $.backstretch([
		        "<?php echo base_url().getThemeName(); ?>/assets/img/bg/1.jpg",
		        "<?php echo base_url().getThemeName(); ?>/assets/img/bg/2.jpg",
		        "<?php echo base_url().getThemeName(); ?>/assets/img/bg/3.jpg",
		        "<?php echo base_url().getThemeName(); ?>/assets/img/bg/4.jpg"
		        ], {
		          fade: 1000,
		          duration: 8000
		    });
		  
		});
	</script>
<?php
		}
?>
