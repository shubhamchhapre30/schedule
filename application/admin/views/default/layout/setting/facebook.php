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
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					<div class="span12">
						
						<h3 class="page-title">
							 Facebook Setting					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"><i class="icon-cogs"></i>Facebook Setting </div>
										</div>
										<div class="portlet-body form">
										<?php
								$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'frm_meta_setting',);
								echo form_open_multipart('Site_setting/facebook_setting', $attributes); ?>
			 
										<?php  
										if($error != "") {
										
												echo '<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
										}
									?>
									
												
												 <div class="control-group">
							<label class="control-label">Facebook Application Id<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="facebook_application_id" id="facebook_application_id" value="<?php echo $facebook_application_id; ?>" class="m-wrap medium"/>
							</div>
							
                            </div>
                       
                        <div class="control-group">
							<label class="control-label">Facebook Login Enable<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="facebook_login_enable" id="facebook_login_enable" value="<?php echo $facebook_login_enable; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
                    
						<div class="control-group">
							<label class="control-label">Facebook Access Token<span class="required">*</span></label>
                            <div class="controls"><input type="text" name="facebook_access_token" id="facebook_access_token" value="<?php echo $facebook_access_token; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							<div class="control-group">
							<label class="control-label">Facebook Api Key<span class="required">*</span></label>
                            <div class="controls"><input type="text" name="facebook_api_key" id="facebook_api_key" value="<?php echo $facebook_api_key; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							 <div class="control-group">
							<label class="control-label">Facebook Secret Key<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="facebook_secret_key" id="facebook_secret_key" value="<?php echo $facebook_secret_key; ?>" class="m-wrap medium"/>
							</div>
							
                            </div>
                       
                        <div class="control-group">
							<label class="control-label">Facebook User Autopost<span class="req">*</span></label>
                            <div class="controls"> <input type="text" name="facebook_user_autopost" id="facebook_user_autopost" value="<?php echo $facebook_user_autopost; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
                    
						<div class="control-group">
							<label class="control-label">Facebook Wall Post<span class="required">*</span></label>
                            <div class="controls"><input type="text" name="facebook_wall_post" id="facebook_wall_post" value="<?php echo $facebook_wall_post; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							<div class="control-group">
							<label class="control-label">Facebook Url<span class="req">*</span></label>
                            <div class="controls"><input type="text" name="facebook_url" id="facebook_url" value="<?php echo $facebook_url; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							<input type="hidden" name="facebook_setting_id" id="facebook_setting_id" value="<?php echo $facebook_setting_id; ?>" />
												
																						
												<div class="form-actions">
													
													<button class="btn green" type="submit"><?php echo ($facebook_setting_id!='')?'Update':'Submit' ?></button>
													
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

<script>
		jQuery(document).ready(function() { 
			
			<?php  
					if(isset($success) && $success != "") {										
						?>
					$.growlUI('<?php echo $success; ?>'); 		
				<?php }?>		
			
			      
		         var form1 = $('#admin');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    facebook_application_id: {required: true},
                    facebook_login_enable: {required: true},
                    facebook_access_token: {required: true},
                    facebook_api_key: {required: true},
                    facebook_secret_key: {required: true},
                    facebook_user_autopost: {required: true},
                    facebook_wall_post: {required: true},
                    facebook_url: {required: true},
                   // title: {required: true},
                    
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
                    form.submit();
                }
            });
		});
	</script>
		</div>
