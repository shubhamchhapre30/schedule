<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css?Ver<?php echo VERSION;?>" />
<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal hide" id="portlet-config">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"></button>
					<h3>Twilio Sms Settings</h3>
				</div>
				<div class="modal-body">
					<p>Here will be a configuration form</p>
				</div>
			</div>
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					<div class="span12">
						
						<h3 class="page-title">
							Twilio Sms Settings					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box blue">
										<div class="portlet-title">
											<div class="caption"><i class="icon-cogs"></i>Twilio Sms Settings</div>
										</div>
										<div class="portlet-body form">
										<?php 
										$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'admin');
										echo form_open_multipart('twilio_setting/sms_setting', $attributes); ?>
										<?php  
										if($error != "") {
										
												echo '<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
										}
									?>
									<input type="hidden" name="twilio_id" id="twilio_id" value="<?php echo $twilio_id; ?>" />
												<div class="control-group">
													<label class="control-label">Mode<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="mode" id="mode" placeholder="" value="<?php echo $mode; ?>">
													</div>
												</div>
												
												
												<div class="control-group">
													<label class="control-label">Account Sid <span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="account_sid" id="account_sid" placeholder="" value="<?php echo $account_sid; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Auth Token<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="auth_token" id="auth_token" placeholder="" value="<?php echo $auth_token; ?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Api Version<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="api_version" id="api_version" placeholder="" value="<?php echo $api_version; ?>">
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">Number<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="number" id="number" placeholder="" value="<?php echo $number; ?>">
													</div>
												</div>
                                                    
												<div class="form-actions">
													
													<button class="btn blue" type="submit" name="submit" id="submit" value="submit"><?php echo ($twilio_id!='')?'Update':'Submit' ?></button>
													
												</div>
											</form>
										</div>
									</div>
						
					</div>
				</div>
				<!-- END PAGE CONTENT-->         
			</div>
<!-- END PAGE CONTAINER-->
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>  
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/select2.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.input-ip-address-control-1.0.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-switch/static/js/bootstrap-switch.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-tags-input/jquery.tagsinput.min.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>   
 <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js?Ver<?php echo VERSION;?>"></script>
 	
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/jquery.validate.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/additional-methods.min.js?Ver<?php echo VERSION;?>"></script>
<!-- <script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-components.js?Ver<?php echo VERSION;?>"></script> -->
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-validation.js?Ver<?php echo VERSION;?>"></script>	    

		</div>
