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
							<?php echo ($admin_id=="")?'Add':'Edit'; ?> admin					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"> <?php if($admin_id==""){ echo 'Add admin'; } else { echo 'Edit admin'; }?> </div>
											<!-- <div class="tools">
												<a class="collapse" href="javascript:;"></a>
												<a class="config" data-toggle="modal" href="#portlet-config"></a>
												<a class="reload" href="javascript:;"></a>
												<a class="remove" href="javascript:;"></a>
											</div> -->
										</div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											
												<?php // Change the css classes to suit your needs    

										$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'admin');
										echo form_open_multipart('admin/add_admin', $attributes); ?>
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
												<!--<div class="control-group">
													<label class="control-label">User Name<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="username" id="username" placeholder="" value="<?php echo $username; ?>">
														
													</div>
												</div>-->
												<div class="form-group ">
													<label class="control-label col-md-2">First Name<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="first_name" id="first_name" placeholder="" value="<?php echo $first_name; ?>">
														
													</div>
												</div>
												<div class="form-group ">
													<label class="control-label col-md-2">Last Name<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="last_name" id="last_name" placeholder="" value="<?php echo $last_name; ?>">
														
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Email<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="emailField" id="emailField" placeholder="" value="<?php echo $email; ?>">
														
													</div>
												</div>
												<?php if($admin_id==''){ ?>
												<div class="form-group ">
													<label class="control-label col-md-2">Password<span class="required">*</span></label>
													<div class="controls">
														<input type="password" class="m-wrap large"  name="password" id="password" placeholder="" value="">
														
													</div>
												</div>
												<div class="form-group ">
													<label class="control-label col-md-2">Confirm Password<span class="required">*</span></label>
													<div class="controls">
														<input type="password" class="m-wrap large"  name="cpassword" id="cpassword" placeholder="" value="">
														
													</div>
												</div>
											<?php } ?>
											
												<div class="form-group ">
												<label class="control-label col-md-2">Profile Image</label>
												<div class="controls">
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="input-append">
                                                                                                    <div class="uneditable-input" style="border: 1px solid #e5e5e5;width:230px">
														<i class="fa fa-file fileupload-exists"></i> 
														<span class="fileupload-preview"></span>
													</div>
                                                                                                    <span class="btn btn-file btn-margin" >
													<span class="fileupload-new">Select file</span>
													<span class="fileupload-exists">Change</span>
													<input type="file" class="default" name="profile_image" id="profile_image" />
													</span>
													
												</div><span for="profile_image" class="help-inline" style="display: none">This field is required.</span>
											</div>
											<input type="hidden" name="pre_profile_image" id="pre_profile_image" value="<?php echo $pre_profile_image ?>" />
												</div>
									</div>
									<?php 
									$bucket = $this->config->item('bucket_name');
									$s3_display_url = $this->config->item('s3_display_url');
									
									if($pre_profile_image!='' && $this->s3->getObjectInfo($bucket,'upload/admin/'.$pre_profile_image)){ ?>
									<div class="form-group ">
												<label class="control-label col-md-2"></label>
												<div class="controls">
													<div class="col-md-2">
													<img src="<?php echo  $s3_display_url.'upload/admin/'.$pre_profile_image; ?>"  />
													</div>
													</div>
									</div>
									<?php } ?>
												<div class="form-group ">
													<label class="control-label col-md-2">Status <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="status" id="status" class="small m-wrap">
															<option value="">Select</option>
															<option value="Active" <?php if($status=='Active'){ ?> selected="selected"<?php } ?>>Active</option>
															<option value="Inactive" <?php if($status==='Inactive' && $status!=''){ ?> selected="selected"<?php } ?>>Inactive</option>
															
														</select>
													</div>
												</div>
												
												
												<div class="form-control form-change ">
													<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
													<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
													<input type="hidden" name="admin_id" id="admin_id" value="<?php echo $admin_id; ?>" />
													<input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
													<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
													
													 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
													 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
													
													<button class="btn green" type="submit"><?php echo ($admin_id!='')?'Update':'Submit' ?></button>
													
													
														<?php if($redirect_page == 'list_admin')
														{?>
														
														<input type="button" name="Cancel" value="Cancel" class="btn red " onClick="location.href='<?php echo site_url("admin/".$redirect_page.'/'.$limit.'/'.$offset); ?>'" />
														<?php }else
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("admin/".$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset); ?>'" />
														
														
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
			
				$('.alpha-only').bind('keyup blur',function(){ 
    			$(this).val( $(this).val().replace(/[^A-Za-z]/g,'') ); }
			);
			   
		         var form1 = $('#admin');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
			
			$.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "Please enter only letters.");
		    
            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
					first_name: {
                        required: true,
                        alpha : true
                    },
                    last_name: {
                        required: true,
                        alpha : true
                    },
                    address: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    phone_no: {
                        required: true,
                        digits: true,
                        rangelength: [10, 10]
                    },
                    zip: {
                       required: true,
                        rangelength: [0, 6]
                    },
                    password: {
	                	required:true,
	                	//loginRegex: true,
	                    rangelength: [8, 16]
                    },
                    cpassword: {
                    	required:true,
                    	equalTo:'#password',
                    	//loginRegex: true,
	                    rangelength: [8, 16]
                    },
                    emailField: {
                        required: true,
                        email:true,
                    },
                    status: {
                        required: true
                    },
                    profile_image:{
						accept: "jpg|jpeg|png|bmp"
					},
                },
                messages: {
		        	profile_image:{
		        		accept: "Please provide valid image.",
		        	}
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
            $.validator.addMethod("loginRegex", function(value, element) {
		        return this.optional(element) || /^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{8,16}$/.test(value);
		    }, "Provide atleast 1 Number, 1 Special character,1 Alphabet and between 8 to 16 characters.");
		    $.validator.addMethod("alphanumeric", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
		    }, "Please provide only alpha numeric.");
		});
	</script>
		</div>
