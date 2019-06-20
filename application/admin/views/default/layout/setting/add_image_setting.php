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
							 Image Setting					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"><i class="icon-cogs"></i>Image Setting </div>
										</div>
										<div class="portlet-body form">
										<?php
								$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'frm_meta_setting',);
								echo form_open_multipart('Site_setting/add_image_setting', $attributes); ?>
			 
										<?php  
										if($error != "") {
										
												echo '<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
										}
									?>
									
												
						 <div class="control-group">
							<label class="control-label">User Width<span class="required">*</span></label>
                            <div class="controls"> 
                            	<input type="text" name="user_width" id="user_width" placeholder="" value="<?php echo $user_width; ?>" class="m-wrap medium"/>
							</div>
							
                           </div>
                           
                           
                       
                        <div class="control-group">
							<label class="control-label">User Height<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="user_height" id="user_height" value="<?php echo $user_height; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
                    
						  
							 <div class="control-group">
							<label class="control-label">Gift Card Height<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="gift_card_height" id="gift_card_height" value="<?php echo $gift_card_height; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							 <div class="control-group">
							<label class="control-label">Gift Card Width<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="gift_card_width" id="gift_card_width" value="<?php echo $gift_card_width; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							 <div class="control-group">
							<label class="control-label">Product Height<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="product_height" id="product_height" value="<?php echo $product_height; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							 <div class="control-group">
							<label class="control-label">Product Width<span class="required">*</span></label>
                            <div class="controls"> <input type="text" name="product_width" id="product_width" value="<?php echo $product_width; ?>" class="m-wrap medium"/>
							</div>
							
							</div>
							
							
							
							
							<input type="hidden" name="image_setting_id" id="image_setting_id" value="<?php echo $image_setting_id; ?>" />
				 		
												
																						
												<div class="form-actions">
													
													<button class="btn green" type="submit"><?php echo ($image_setting_id!='')?'Update':'Submit' ?></button>
													
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
                    user_width: {required: true},
                    user_height: {required: true},
                    product_width: {required: true},
                    product_height: {required: true},
                    gift_card_width: {required: true},
                    gift_card_height: {required: true},
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
