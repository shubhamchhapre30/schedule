<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css?Ver<?php echo VERSION;?>" />

<style>
    .control-label{
        text-align: left !important;
    }
</style>
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
							<?php echo ($plan_id=="")?'Add':'Edit'; ?> Plan					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"> <?php if($plan_id==""){ echo 'Add Plan'; } else { echo 'Edit Plan'; }?> </div>
											
										</div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											
												<?php // Change the css classes to suit your needs    

										$attributes = array('class' => 'form-horizontal', 'id' => 'plan','name'=>'plan');
										echo form_open_multipart('plan/add', $attributes); ?>
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
													<label class="control-label col-md-3">Chargify Product Id<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="chargify_product_id" id="chargify_product_id" placeholder="" value="<?php echo $chargify_product_id; ?>">														
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-3">Chargify Component Id<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="chargify_component_id" id="chargify_component_id" placeholder="" value="<?php echo $chargify_component_id; ?>">														
													</div>
												</div>
												
												
												<div class="form-group ">
													<label class="control-label col-md-3">Plan Title<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="plan_title" id="plan_title" placeholder="" value="<?php echo $plan_title; ?>">														
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-3">Plan Description<span class="required">*</span></label>
													<div class="controls">
														<textarea  class="m-wrap large border-change"  name="plan_description" id="plan_description" placeholder="" ><?php echo $plan_description; ?></textarea>
													</div>
												</div>
												
                                                                                                <div class="form-group ">
													<label class="control-label col-md-3">External User Component Id<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="plan_external_user" id="plan_external_user" placeholder="" value="<?php echo $chargify_external_user_component_id; ?>">														
													</div>
												</div>
												<div class="form-group ">
													<label class="control-label col-md-3">Plan Currency<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="plan_currency_code" id="plan_currency_code" placeholder="" value="<?php echo $plan_currency_code; ?>">
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-3">Plan Price<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="plan_price" id="plan_price" placeholder="" value="<?php echo $plan_price; ?>">
													</div>
												</div>
											
											
												
												
												<div class="form-group ">
													<label class="control-label col-md-3">Plan Duration<span class="required">*</span></label>
													<div class="controls">
														<select id="plan_duration" name="plan_duration" class="m-wrap large" style="width: 320px !important;text-transform:capitalize">
															<option value="">Select Month</option>
															<option value="1"<?php if($plan_duration=='1'){?> selected="selected" <?php } ?>>1</option>
															<option value="2" <?php if($plan_duration=='2'){?> selected="selected" <?php } ?>>2</option>
															<option value="3" <?php if($plan_duration=='3'){?> selected="selected" <?php } ?>>3</option>
															<option value="4" <?php if($plan_duration=='4'){?> selected="selected" <?php } ?>>4</option>
															<option value="5" <?php if($plan_duration=='5'){?> selected="selected" <?php } ?>>5</option>
															<option value="6" <?php if($plan_duration=='6'){?> selected="selected" <?php } ?>>6</option>
															<option value="7" <?php if($plan_duration=='7'){?> selected="selected" <?php } ?>>7</option>
															<option value="8" <?php if($plan_duration=='8'){?> selected="selected" <?php } ?>>8</option>
															<option value="9" <?php if($plan_duration=='9'){?> selected="selected" <?php } ?>>9</option>
															<option value="10" <?php if($plan_duration=='10'){?> selected="selected" <?php } ?>>10</option>
															<option value="11" <?php if($plan_duration=='11'){?> selected="selected" <?php } ?>>11</option>
															<option value="12" <?php if($plan_duration=='12'){?> selected="selected" <?php } ?>>12</option>
															
														</select>
													</div>
												</div>
												
											
												
												<div class="form-group ">
													<label class="control-label col-md-3">Status <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="plan_status" id="plan_status" class="small m-wrap">
															<option value="">Select</option>
															<option value="Active" <?php if($plan_status=="Active"){ ?> selected="selected"<?php } ?>>Active</option>
															<option value="Inactive" <?php if($plan_status=="Inactive" && $plan_status!=''){ ?> selected="selected"<?php } ?>>Inactive</option>															
														</select>
													</div>
												</div>
												
												
                                                                                        <div class="form-control form-change" style="padding-left: 80px !important;">
													<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
													<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
													<input type="hidden" name="plan_id" id="plan_id" value="<?php echo $plan_id; ?>" />
													<input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
													<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
													
													 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
													 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
													
													<button class="btn green" type="submit"><?php echo ($plan_id!='')?'Update':'Submit' ?></button>
													
													<?php if($redirect_page == 'list_user')
														{?>
														
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("plan/"); ?>'" />
														<?php }else
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("plan/"); ?>'" />
														
														
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


function getcompanyaddress(id)
{
	/*if(id!='')
	{
	var str=id;
	var address = str.split("#");
	$('#company_address').val(address[1]);
	}
	else
	{
		$('#company_address').val("");
	}*/
	
	
	$.ajax({
			// see the (*)
			url: "<?php echo site_url("plan/getcompanyaddress"); ?>/"+id,
			dataType: "json",
			//data: data,
			success: function(data) {
				
				
				var string = "";
				$.map(data, function (item,i) {
					//alert($("#company_ad").val());
					//alert(item.product_type_id);alert(item.product_type);
				if($("#company_ad").val()==item.address)
				{
					
				string += '<option value="'+item.address+'" selected>'+item.address+'</option>';
				}
				else
				{
				string += '<option value="'+item.address+'" >'+item.address+'</option>';
				}
				
				});

				 $('#company_address').html(string);
				
			//	alert(response);
			//	alert("hello");
			
			}
		});

	
	
	
}




function getState(id)
{	
	$.ajax({
		url:"<?php echo site_url('home/GetStateAjax/') ?>/"+id,
		beforeSend:function(){ 
			$('#state').html('<option value="">Loading....</option>');  },
		success:function(data){
			$('#state').html(data);
		}
	});
}

function getCity(id)
{
	$.ajax({
		url:"<?php echo site_url('home/GetCityAjax/') ?>/"+id,
		beforeSend:function(){ 
			$('#city').html('<option value="">Loading....</option>');  },
		success:function(data){
			$('#city').html(data);
		}
	});
}

		jQuery(document).ready(function() {    
			
				$('.alpha-only').bind('keyup blur',function(){ 
    			$(this).val( $(this).val().replace(/[^A-Za-z]/g,'') ); }
			);
			   
		         var form1 = $('#plan');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
					plan_title: {
                        required: true
                    },
					plan_description: {
                        required: true
                    },
					plan_currency_code: {
                        required: true,
                    },
                   	chargify_component_id : {
                   		required : true,
                   	},
                   	chargify_product_id : {
                   		required : true,
                   	},
                   
					plan_duration: {
                        required: true
                    },
					plan_price: {
                        required: true,
						number:true
                    },
                    plan_status: {
                    	 required: true,						
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
