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
							<?php echo ($city_id=="")?'Add':'Edit'; ?> City					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box blue">
										<div class="portlet-title">
											<div class="caption"><i class="icon-City"></i><?php echo ($city_id=="")?'Add':'Edit'; ?> City </div>
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
										echo form_open_multipart('City/addCity', $attributes); ?>
										<?php  
										if($error != "") {
											
											if($error != "insert"){	
												echo '<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
											}
										}
									?>		
										<div class="alert alert-error hide">
										<button class="close" data-dismiss="alert"></button>
										You have some form errors. Please check below.
									</div>
									<div class="alert alert-success hide">
										<button class="close" data-dismiss="alert"></button>
										Your form validation is successful!
									</div>
											<div class="control-group">
												<label class="control-label">Country<span class="required">*</span></label>
												<div class="controls">
													<select name="country_id" id="country_id" class="medium m-wrap" onchange="getState(this.value)">
														<option value="">--Select Country--</option>
														<?php
														if($allCountry!='')
														{
															foreach ($allCountry as $ac) {?>
															<option value="<?php echo $ac->country_id ?>" <?php echo (@$country_id==$ac->country_id)?'selected="selected"':'' ?>><?php echo ucfirst($ac->country_name); ?></option>
															<?php }
														}
														?>
													</select>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Province<span class="required">*</span></label>
												<div class="controls">
													<select name="state_id" id="state_id" class="medium m-wrap">
														<option value="">--Select Province--</option>
														<?php
														if($allState!='')
														{
															foreach ($allState as $as) {?>
															<option value="<?php echo $as->state_id ?>" <?php echo (@$state_id==$as->state_id)?'selected="selected"':'' ?>><?php echo ucfirst($as->state_name); ?></option>
															<?php }
														}
														?>
													</select>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">City Name<span class="required">*</span></label>
												<div class="controls">
													<input type="text" class="m-wrap medium alpha-only"  name="city_name" id="city_name" placeholder="" value="<?php echo $city_name; ?>">
													
												</div>
											</div>
											
											<div class="control-group">
													<label class="control-label">Status <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="status" id="status" class="small m-wrap">
															<option value="">--Select--</option>
															<option value="Active" <?php if($status=='Active'){ ?> selected="selected"<?php } ?>>Active</option>
															<option value="Inactive" <?php if($status=='Inactive'){ ?> selected="selected"<?php } ?>>Inactive</option>
															
														</select>
													</div>
												</div>
												
												
												<div class="form-actions">
													<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
													<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
													<input type="hidden" name="city_id" id="city_id" value="<?php echo $city_id; ?>" />
													<input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
													 <input type="hidden" name="sort_on" id="sort_on" value="<?php echo $sort_on; ?>" />
													 <input type="hidden" name="sort_type" id="sort_type" value="<?php echo $sort_type; ?>" />
													 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
													 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
													<button class="btn blue" type="submit"><?php echo ($city_id!='')?'Update':'Submit' ?></button>
													
													<?php if($redirect_page == 'listCity')
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>City/<?php echo $redirect_page.'/'.$limit.'/'.$offset?>'" />
														<?php }else
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>City/<?php echo $redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset?>'" />
														
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
function getState(id)
{
	$.ajax({
		url:"<?php echo site_url('home/GetStateAjax/') ?>/"+id,
		beforeSend:function(){ 
			$('#state_id').html('<option value="">Loading....</option>');  },
		success:function(data){
			$('#state_id').html(data);
		}
	});
}
		jQuery(document).ready(function() {   
			
			$('.alpha-only').bind('keyup blur',function(){ 
    			$(this).val( $(this).val().replace(/[^A-Za-z]/g,'') ); }
			);			
			    
		    var form1 = $('#admin');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    country_id: {required: true},
                    state_id: {required: true},
                    City_name: {required: true},
                    status: {required: true },
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