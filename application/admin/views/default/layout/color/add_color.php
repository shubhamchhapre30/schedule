
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
							<?php echo ($color_id=="")?'Add':'Edit'; ?> Colour					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"> <?php if($color_id==""){ echo 'Add Colour'; } else { echo 'Edit Colour'; }?> </div>
											
										</div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											
												<?php // Change the css classes to suit your needs    

										$attributes = array('class' => 'form-horizontal', 'id' => 'color','name'=>'color');
										echo form_open_multipart('Color/add', $attributes); ?>
										<?php  
										if($error != "") {
											
											if($error != "insert"){	
												echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
											}
										}
									?>		
									<div class="alert alert-danger hide">
										<button class="close" data-dismiss="alert"></button>
										You have some form errors. Please check below.
									</div>
									<div class="alert alert-success hide">
										<button class="close" data-dismiss="alert"></button>
										Your form validation is successful!
									</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Colour Name<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="color_name" id="color_name" placeholder="" value="<?php echo $color_name; ?>">														
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-md-2">Inside Colour Code<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="color_code" id="color_code" placeholder="" value="<?php echo $color_code; ?>">														
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Outside Colour Code<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="outside_color_code" id="outside_color_code" placeholder="" value="<?php echo $outside_color_code; ?>">														
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Status <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="status" id="status" class="small m-wrap">
															<option value="">Select</option>
															<option value="Active" <?php if($status=="Active"){ ?> selected="selected"<?php } ?>>Active</option>
															<option value="Inactive" <?php if($status=="Inactive" && $status!=''){ ?> selected="selected"<?php } ?>>Inactive</option>															
														</select>
													</div>
												</div>
												
												<div class="form-control form-change">
													<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
													<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
													<input type="hidden" name="color_id" id="color_id" value="<?php echo $color_id; ?>" />
													<input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
													<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
													
													 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
													 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
													
													<button class="btn green" type="submit"><?php echo ($color_id!='')?'Update':'Submit' ?></button>
													
													<?php if($redirect_page == 'list_color')
														{?>
														
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("Color/".$redirect_page.'/'.$limit.'/'.$offset); ?>'" />
														<?php }else
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("Color/".$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset); ?>'" />
														
														
														<?php }?>
												</div>
											</form>
											<!-- END FORM--> 
										</div>
									</div>
						<!-- BEGIN SAMPLE FORM PORTLET-->   
						
						<!-- END SAMPLE FORM PORTLET-->
					</div>
				</div>
				<!-- END PAGE CONTENT-->         
			</div>
			
			
			
<!-- END PAGE CONTAINER-->
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/jquery.validate.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/additional-methods.min.js?Ver<?php echo VERSION;?>"></script>
<!-- <script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-components.js?Ver<?php echo VERSION;?>"></script> -->
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-validation.js?Ver<?php echo VERSION;?>"></script>	   

<script>


		jQuery(document).ready(function() {    
			
			var form1 = $('#color');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
	
			$.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "Please enter only letters.");
		    
		    
		    $.validator.addMethod("regex", function(value, element) {          
		    	return this.optional(element) || /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(value);
		   	}, "Please enter a valid hex colour code."); 
		   
            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
					color_name: {
                        required: true,
                        alpha : true
                    },
					color_code: {
                        required: true,
                        regex : true
                    },
                    
                    outside_color_code: {
                        required: true,
                        regex : true
                    },
					status: {
                        required: true
                   	},
                  
                },
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
