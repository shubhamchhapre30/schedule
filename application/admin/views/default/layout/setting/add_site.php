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
							 Site Setting					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"><i class="fa fa-cogs"></i>Site Setting </div>
										</div>
										<div class="portlet-body form">
										<?php 
										$attributes = array('class' => 'form-horizontal', 'id' => 'admin','name'=>'admin');
										echo form_open_multipart('Site_setting/index', $attributes); ?>
										<?php  
										if($error != "") {
										
												echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
										}
									?>
									<input type="hidden" name="site_setting_id" id="site_setting_id" value="<?php echo $site_setting_id ?>" />
												<input type="hidden" name="social_setting_id" id="social_setting_id" value="<?php echo $site_setting_id ?>" />
												<div class="form-group ">
													<label class="control-label col-md-2">Site Name<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="site_name" id="site_name" placeholder="" value="<?php echo $site_name; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-2">Site Offline<span class="required">*</span></label>
													<div class="controls">
														<label class="radio">
															<input type="radio" class="required" value="0" <?php echo $site_online=='0'?'checked="checked"':''; ?> name="site_online">Yes
														</label>
														<label class="radio">
															<input type="radio" class="required" value="1" <?php echo $site_online=='1'?'checked="checked"':''; ?> name="site_online">No
														</label>
													</div>
												</div>
												
												<?php /*?><div class="control-group">
													<label class="control-label">Captcha. <span class="required">*</span></label>
													<div class="controls">
														<label class="radio">
															<input type="radio" class="required" value="0" <?php echo $captcha_enable=='0'?'checked="checked"':''; ?> name="captcha_enable" id="captcha_enable">Enable
														</label>
														<label class="radio">
															<input type="radio" class="required" value="1" <?php echo $captcha_enable=='1'?'checked="checked"':''; ?> name="captcha_enable" id="captcha_enable">Disable
														</label>
													</div>
												</div><?php */?>
												
												<!--<div class="control-group">
													<label class="control-label">Site Version <span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="site_version" id="site_version" placeholder="" value="<?php echo $site_version; ?>">
													</div>
												</div>-->
												
												
												<div class="form-group ">
													<label class="control-label col-md-2"> Currency Symbol <span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="currency_symbol" id="currency_symbol" placeholder="" value="<?php echo $currency_symbol ; ?>">
													</div>
												</div>												
												
												<div class="form-group">
													<label class="control-label col-md-2"> Currency Code<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="currency_code" id="currency_code" placeholder="" value="<?php echo $currency_code ; ?>">
													</div>
												</div>										
												
												<div class="form-group ">
													<label class="control-label col-md-2">Date format<span class="required">*</span></label>
													<div class="controls">
                                                                                                            <select class="m-wrap required" id="date_format" name="date_format" data-placeholder="Choose a Format" tabindex="1" style="width:206px;">
															<option value="">--Select Format--</option>
															 <option value="d M,Y" <?php if($date_format=="d M,Y"){ ?> selected="selected"<?php } ?>>d M,Y</option>
							                                  <option value="Y-m-d" <?php if($date_format=="Y-m-d"){ ?> selected="selected"<?php } ?>>Y-m-d</option>  
							                                  <!--<option value="m-d-Y" <?php if($date_format=="m-d-Y"){ ?> selected="selected"<?php } ?>>m-d-Y</option> -->
							                                  <option value="d-m-Y" <?php if($date_format=="d-m-Y"){ ?> selected="selected"<?php } ?>>d-m-Y</option>
							                                  <option value="Y/m/d" <?php if($date_format=="Y/m/d"){ ?> selected="selected"<?php } ?>>Y/m/d</option> 
							                                  <!--<option value="m/d/Y" <?php if($date_format=="m/d/Y"){ ?> selected="selected"<?php } ?>>m/d/Y</option>-->
							                                  <option value="d/m/Y" <?php if($date_format=="d/m/Y"){ ?> selected="selected"<?php } ?>>d/m/Y</option> 
														</select>
													</div>
												</div>
												<div class="form-group ">
													<label class="control-label col-md-2">Time format<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="time_format" id="time_format" placeholder="" value="<?php echo $time_format; ?>">
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-md-2">Date/Time format<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="date_time_format" id="date_time_format" placeholder="" value="<?php echo $date_time_format; ?>">
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Address<span class="required">*</span></label>
													<div class="controls">
														<textarea  class="m-wrap medium required border-change"  name="address_data" id="address_data" placeholder="" ><?php echo $address_data; ?></textarea>
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Admin email<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="admin_email" id="admin_email" placeholder="" value="<?php echo $admin_email; ?>">
													</div>
												</div>
												
												
												
												<!--<div class="control-group">
													<label class="control-label">Google Map Api<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="google_map_key" id="google_map_key" placeholder="" value="<?php echo $google_map_key; ?>">
													</div>
												</div>-->
												
												<!--<div class="control-group">
													<label class="control-label">Default Longitude<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="default_longitude" id="default_longitude" placeholder="" value="<?php echo $default_longitude; ?>">
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">Default Latitude<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="default_latitude" id="default_latitude" placeholder="" value="<?php echo $default_latitude; ?>">
													</div>
												</div>-->

												<div class="form-group ">
													<label class="control-label col-md-2">Site e-mail<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="site_email" id="site_email" placeholder="" value="<?php echo $site_email; ?>">
													</div>
												</div> 
												
												<?php /*<div class="control-group">
													<label class="control-label">Order Cancellation Time<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="order_cancellation_time" id="order_cancellation_time" placeholder="" value="<?php echo $order_cancellation_time; ?>">
													</div>
												</div> */ ?>
												<!--<input type="hidden" name="order_cancellation_time" id="order_cancellation_time" value="<?php echo $order_cancellation_time; ?>">
												
												<div class="control-group">
													<label class="control-label">Order Close Time<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="order_close_time" id="order_close_time" placeholder="" value="<?php echo $order_close_time; ?>">
													</div>
												</div>   -->  
												
												
												
												
												
												
												
												<!--<div class="control-group">
													<label class="control-label">Facebook Link<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium required"  name="facebook_link" id="facebook_link" placeholder="" value="<?php echo $facebook_link; ?>">
													</div>
												</div>

												<div class="control-group">
													<label class="control-label">Twitter Link<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="twitter_link" id="twitter_link" placeholder="" value="<?php echo $twitter_link; ?>">
													</div>
												</div> 
												<div class="control-group">
													<label class="control-label">Instagram Link<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="instagram_link" id="instagram_link" placeholder="" value="<?php echo $instagram_link; ?>">
													</div>
												</div> 
												
												<!--<div class="control-group">
													<label class="control-label">Skype ID<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="skype_id" id="skype_id" placeholder="" value="<?php echo $skype_id; ?>">
													</div>
												</div> -->
												
												<div class="form-group ">
													<label class="control-label col-md-2">Contact Number<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap medium"  name="contact_number" id="contact_number" placeholder="" value="<?php echo $contact_number; ?>">
													</div>
												</div>     
												
												
											         
                                                 
												<div class="form-control form-change">													
													<button class="btn green" type="submit"><?php echo ($site_setting_id!='')?'Update':'Submit' ?></button>													
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
                    site_name: {required: true},
                    site_online : {required: true},
                   // site_version: {required: true},
                    site_email: {required: true, email:true },
                    admin_email: {required: true, email:true },
                    site_name : {required: true },
                    currency_symbol : {required: true },
                    currency_code : {required: true },
                    date_format : {required:true},
                    time_format : {required: true},
                    date_time_format : {required : true},
                	google_map_key : {required: true },
                	default_longitude : {required: true },
                	default_latitude : {required: true },
                	order_close_time : {required: true },
                	order_cancellation_time : {required: true },
                	//content_right_text : {required: true },
                	//calendar_syn_title : {required: true },
                	//email_text_title : {required: true },
                	//enable_live_chat : {required: true },
                	address_data : {required : true},
                	
                	/*facebook_link : {required: true, url: true },
                	twitter_link : {required: true, url: true },
                	instagram_link : {required: true, url: true },*/
                	contact_number : {required: true, number:true, minlength: 10, maxlength: 10},
                	fullday_buy : {required: true,number:true },
                	shipping_charge: {required: true,number:true },
                	skype_id : {required: true}
                	
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
