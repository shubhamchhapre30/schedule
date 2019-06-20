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
							 Google Setting					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"><i class="icon-cogs"></i>Google Setting </div>
										</div>
										<div class="portlet-body form">
										<?php
								$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'frm_meta_setting',);
								echo form_open_multipart('Site_setting/google_setting', $attributes); ?>
			 
										<?php  
										if($error != "") {
										
												echo '<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
										}
									?>
									
												
						 <div class="control-group">
							<label class="control-label">Google Client Id<span class="required">*</span></label>
                            <div class="controls"> 
                            	<input type="text" name="google_client_id" id="google_client_id" placeholder="" value="<?php echo $google_client_id; ?>" class="m-wrap medium"/>
							</div>
							
                           </div>
                           
                           
                       
                        <div class="control-group">
							<label class="control-label">Google Url<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="google_url" id="google_url" value="<?php echo $google_url; ?>" class="required m-wrap medium"/>
							</div>
							
							</div>
                    
						<div class="control-group">
							<label class="control-label">Google Login Enable<span class="required">*</span></label>
                            <div class="controls"><input type="text" name="google_login_enable" id="google_login_enable" value="<?php echo $google_login_enable; ?>" class="required m-wrap medium "/>
							</div>
							
							</div>
							<div class="control-group">
							<label class="control-label">Google Client Secret<span class="required">*</span></label>
                            <div class="controls"><input type="text" name="google_client_secret" id="google_client_secret" value="<?php echo $google_client_secret; ?>" class="required m-wrap medium"/>
							</div>
							
							</div>
							
							<input type="hidden" name="google_setting_id" id="google_setting_id" value="<?php echo $google_setting_id; ?>" />
				 		
												
																						
												<div class="form-actions">
													
													<button class="btn green" type="submit"><?php echo ($google_setting_id!='')?'Update':'Submit' ?></button>
													
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
                    google_client_id: {required: true},
                    google_url: {required: true},
                    google_login_enable: {required: true},
                    google_client_secret: {required: true},
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
