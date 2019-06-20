<link href="<?php echo base_url().getThemeName(); ?>/assets/css/pages/login.css?Ver<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url().getThemeName(); ?>/assets/css/pages/login-soft.css?Ver<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<script>
	$(document).ready(function(){
		$('body').removeAttr('class');
		$('body').attr('class','login');
	});
</script>
<!-- BEGIN LOGO -->
	<div class="logo">
		<img src="<?php echo base_url().getThemeName(); ?>/images/logo.png" alt="" /> 
	</div>
<div class="content">
		
		<!-- BEGIN REGISTRATION FORM -->
		 <?php	
		
		 if($errorfail!='fail'){		 
		$attributes = array('name'=>'frmreset','id'=>'frmreset','class'=>'form-vertical reset-form');
		echo form_open('home/resetPassword',$attributes);
	?>
			<h3 >Reset Password </h3>
			<div class="alert alert-danger hide" id="resetresult"></div>
			<div class="alert alert-success hide"></div>
			
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="fa fa-lock"></i>
						<input type="password" name="password" placeholder="Password" id="password" autocomplete="off" class="m-wrap placeholder-no-fix">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="fa fa-ok"></i>
						<input type="password" name="rpassword" placeholder="Re-type Your Password" autocomplete="off" class="m-wrap placeholder-no-fix">
					</div>
				</div>
			</div>
			<input type="hidden" name="admin_id"  value="<?php echo base64_encode($admin_id); ?>" />
			<input type="hidden" name="code"  value="<?php echo $code; ?>" />
			<div class="form-actions">
				
				<button class="btn green pull-right" id="register-submit-btn" type="submit">
				Submit <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
		</form>
		<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/select2.min.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/jquery.validate.min.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/backstretch/jquery.backstretch.min.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.form.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/login-soft.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>

<script>
		jQuery(document).ready(function() {     
		 
		  Login.init();
		
		  
		});
	</script>
		<!-- END REGISTRATION FORM -->
		<?php }else{?>
		<div class="alert alert-danger" ><?php echo EXPIRED_RESET_LINK ?></div>	
		<?php		} ?>
	</div>

	<div class="copyright">
		2014 &copy; Spaculus. Admin Dashboard Template.
	</div>
