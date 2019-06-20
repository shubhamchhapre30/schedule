<style>
	.error{
		color:#FF0000;
	}
</style>

<div class="page-content">
			
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid admin-list">
				<!-- BEGIN PAGE HEADER-->   
				<div class="row">
					<div class="col-md-12">
						
						<h3 class="page-title"> Change Password </h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"><i class="fa fa-key"></i> Change Password</div>
											
										</div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											
												<?php // Change the css classes to suit your needs    

										$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'admin');
										echo form_open_multipart('home/changePassword', $attributes); ?>
										<?php  
										if($error != "") {
											
											if($error != "insert"){	
												echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
											}
										}
									?>		
										
												<div class="form-group">
													<label class="control-label col-md-2">Old Password<span class="required">*</span></label>
													<div class="controls">
														<input type="password" class="m-wrap medium"  name="oldpassword" id="oldpassword" placeholder="" value="">
														
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-2">Password<span class="required">*</span></label>
													<div class="controls">
														<input type="password" class="m-wrap medium"  name="password" id="password" placeholder="" value="">
														
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-md-2">Confirm Password<span class="required">*</span></label>
													<div class="controls">
														<input type="password" class="m-wrap medium"  name="cpassword" id="cpassword" placeholder="" value="">
														
													</div>
												</div>
												
												
												
												<div class="form-control form-change">
													
													<button class="btn green" type="submit"><i class="fa fa-ok"></i> Update</button>
													
												
														<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo site_url('home/dashboard'); ?>'" />
														
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
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/select2.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.input-ip-address-control-1.0.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-switch/static/js/bootstrap-switch.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-tags-input/jquery.tagsinput.min.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>   
 
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/jquery.validate.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/additional-methods.min.js?Ver<?php echo VERSION;?>"></script>
<!-- <script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-components.js?Ver<?php echo VERSION;?>"></script> -->
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-validation.js?Ver<?php echo VERSION;?>"></script>	    

<script>
		jQuery(document).ready(function() {       
		         var form1 = $('#admin');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
               errorElement: 'label', //default input error message container
                //errorClass: 'error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                	oldpassword: {
                       required:true,
	                	//loginRegex: true,
	                    rangelength: [8, 16]
                    },
                    password: {
                        required:true,
	                	//loginRegex: true,
	                    rangelength: [8, 16]
                    },
                    cpassword: {
                       required:true,
	                	rangelength: [8, 16],
                        equalTo:'#password'
                    },
                    
                },
/*

                invalidHandler: function (event, validator) {               
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
                },

                */


                submitHandler: function (form) {
                    /*
                    success1.show();
                                        error1.hide();*/
                    
                    form.submit();
                }
            });
            
            $.validator.addMethod("loginRegex", function(value, element) {
		        return this.optional(element) || /^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{8,16}$/.test(value);
		    }, "Provide atleast 1 Number, 1 Special character,1 Alphabet and between 8 to 16 characters.");
		});
	</script>
		</div>
