<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css?Ver<?php echo VERSION;?>" />
<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal hide" id="portlet-config">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"></button>
					<h3>portlet Settings</h3>
				</div>
				<div class="modal-body">
					<p>Here will be a configuration form</p>
				</div>
			</div>
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid admin-list">
				<!-- BEGIN PAGE HEADER-->   
				<div class="row">
					<div class="col-md-12">
						
						<h3 class="page-title">
							 Payment Setting					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"><i class="fa fa-cogs"></i>Payment Setting </div>
										</div>
										<div class="portlet-body form">
										<?php 
										$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'admin');
										echo form_open_multipart('payment_setting/index', $attributes); ?>
										<?php  
										if($error != "") {
										
												echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
										}
										
										
									?>
									<!--<input type="hidden" name="site_setting_id" id="site_setting_id" value="<?php echo $site_setting_id ?>" />
												<input type="hidden" name="social_setting_id" id="social_setting_id" value="<?php echo $site_setting_id ?>" /> -->
												
													<input type="hidden" name="payment_id" id="payment_id" value="<?php echo $payment_id; ?>" />
													
														<div class="form-group ">
													<label class="control-label col-md-2">Payment Mode <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="payment_mode" id="payment_mode" class="small m-wrap">
															<option value="">Select</option>
															<option value="Sandbox" <?php if($payment_mode=="Sandbox"){ ?> selected="selected"<?php } ?>>Sandbox</option>
															<option value="Live" <?php if($payment_mode=="Live" && $payment_mode!=''){ ?> selected="selected"<?php } ?>>Live</option>															
														</select>
													</div>
												</div>
												
													<div class="form-group ">
												
													<label class="control-label col-md-2">Payment Title</label>
													<div class="controls">
														
                                                                                                            <input type="text"  value="<?php echo $payment_title; ?>" name="payment_title" id="payment_title" class="payment-set"/>
															
																										
													</div>
													
												</div>
												
													<div class="form-group ">
												
													<label class="control-label col-md-2">Login UserName</label>
													<div class="controls">
													
															<input type="text"  value="<?php echo $Login_username; ?>" name="Login_username" id="Login_username" class="payment-set"/>
																											
													</div>
													
												</div>
												
													<div class="form-group ">
												
													<label class="control-label col-md-2">Login Password</label>
													<div class="controls">
														
															<input type="text"  value="<?php echo $login_password; ?>" name="login_password" id="login_password" class="payment-set"/>
																											
													</div>
													
												</div>
												
													<div class="form-group ">
												
													<label class="control-label col-md-2">Api Key</label>
													<div class="controls">
														
															<input type="text"  value="<?php echo $API_key; ?>" name="API_key" id="API_key" class="payment-set"/>
																											
													</div>
													
												</div>
												
													<div class="form-group ">
												
													<label class="control-label col-md-2">Sub Domain</label>
													<div class="controls">
													
															<input type="text"  value="<?php echo $subdomain; ?>" name="subdomain" id="subdomain" class="payment-set"/>
																											
													</div>
													
												</div>
												
											


												<div class="form-group ">
													<label class="control-label col-md-2">Payment Status <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="payment_status" id="payment_status" class="small m-wrap">
															<option value="">Select</option>
															<option value="Active" <?php if($payment_status=="Active"){ ?> selected="selected"<?php } ?>>Active</option>
															<option value="Inactive" <?php if($payment_status=="Inactive" && $payment_status!=''){ ?> selected="selected"<?php } ?>>Inactive</option>															
														</select>
													</div>
												</div>
													
                                                 
												<div class="form-control form-change">													
													<button class="btn green" type="submit">Update</button>													
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
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/js/select2.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.input-ip-address-control-1.0.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-tags-input/jquery.tagsinput.min.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>   
 <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js?Ver<?php echo VERSION;?>"></script>
 	
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/jquery.validate.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/additional-methods.min.js?Ver<?php echo VERSION;?>"></script>
<!-- <script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-components.js?Ver<?php echo VERSION;?>"></script> -->
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-validation.js?Ver<?php echo VERSION;?>"></script>	    

<script>
		jQuery(document).ready(function() { 
			
			<?php  
			    $success = $this->session->flashdata("success");
					if($this->session->flashdata("success") != "") {										
						?>
					$.growlUI('<?php echo $this->session->flashdata("success"); ?>'); 		
				<?php }?>		
			
			      
		         var form1 = $('#admin');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                   payment_name: {required: true},
                   Login_username : {required: true},
                   login_password : {required: true},
                   API_key : {required: true},
                   subdomain : {required: true},
                   payment_mode : {required: true},
                   payment_status : {required: true},
                  
                },

                /*
                invalidHandler: function (event, validator) { //display error alert on form submit              
                                    success1.hide();
                                    error1.show();
                                    App.scrollTo(error1, -200);
                                },*/
                

                

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $("button[type=submit]").prop("disabled",true);
                    form.submit();
                }
            });
		});
	</script>
		</div>
