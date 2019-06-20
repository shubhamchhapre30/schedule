<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css?Ver<?php echo VERSION;?>" />
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js?Ver<?php echo VERSION;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css?Ver<?php echo VERSION;?>" />

<script language="javascript">
	$(document).ready(function() {
		
		<?php if($msg!=''){
			
	     if($msg == "image_remove"){ $error = IMAGE_REMOVE;}
		  if($msg == "delete1"){ $error = DELETE_RECORD;}
    ?>    
      $.growlUI('<?php echo $error; ?>');
   <?php } ?>   
   /*	$('#subscription_date').datepicker({
		format: 'yyyy-mm-dd',
	});
	
	$('#next_subscription_date').datepicker({
		format: 'yyyy-mm-dd',
	});*/
  
});



</script>

<style>
	
#ad_label
{
	 border-bottom: 1px solid #CCCCCC;
    color: #000000;
    font-weight: bold;
    margin: 30px 0;
    padding-bottom: 10px;
}	
	
</style>

<script type="text/javascript">


	
	/*function delete_add(id,redirectid,redirectpage,option,keyword,limit,offset)
	{  
		var ans = confirm("Are you sure, you want to delete company address?");
		if(ans)
		{
			
			location.href = "<?php /*echo site_url("Company/delete_add"); */?>/"+id+"/"+redirectid+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset;

		}
		else
		{
			return false;
		}
	}*/
	
</script>
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
							<?php echo ($company_id=="")?'Add':'Edit'; ?> Company					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"> <?php if($company_id==""){ echo 'Add Company'; } else { echo 'Edit Company'; }?> </div>
											
										</div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											
												<?php // Change the css classes to suit your needs    

										$attributes = array('class' => 'form-horizontal', 'id' => 'company','name'=>'company');
										echo form_open_multipart('Company/add', $attributes); ?>
										<?php  
										if($error != "") {
											
											if($error != "insert"){	
												echo '<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>'.$error.'</div>';	
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
												
												<div class="form-group">
													<label class="control-label col-md-2">Company Name<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="company_name" id="company_name" placeholder="" value="<?php echo $company_name; ?>">														
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Company Email<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="company_email" id="company_email" placeholder="" value="<?php echo $company_email; ?>">														
													</div>
												</div>
												
												<div class="form-group">
													
													<label class="control-label col-md-2">Company Address<span class="required">*</span></label>
													<div class="controls">
														<textarea id="company_address" class="m-wrap large border-change" name="company_address"><?php echo $company_address; ?></textarea>														
													</div>
													
												</div>
												
												
												<div class="form-group ">
													
													<label class="control-label col-md-2">Company Phone no<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="company_phoneno" id="company_phoneno" placeholder="" value="<?php echo $company_phoneno; ?>">														
																										
													</div>
													
												</div>
												
												
												
												<div class="form-group ">
													<label class="control-label col-md-2">Select Plan <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="plan_id" id="plan_id" class="medium m-wrap">
															<option value="">Select plan</option>
															
															<?php $all_plan =getActiveplan();
															
															foreach($all_plan as $cmp){
															?>															
																<option value="<?php echo $cmp->plan_id; ?>" <?php echo ($plan_id==$cmp->plan_id)?'selected=	""':'' ?> ><?php echo $cmp->plan_title; ?></option>
																<?php 
															} ?>
													
														</select>
													</div>
												</div>
												<?php if($company_id!="0"){?>
												<div class="form-group ">
													
													<label class="control-label col-md-2">Subscription ID</label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="chargify_subscriptions_ID" id="chargify_subscriptions_ID" placeholder="" value="<?php echo $chargify_subscriptions_ID; ?>">														
																										
													</div>
													
												</div>	
												<?php }?>
												
												
												<!--<div class="control-group">
													<label class="control-label">Subscription Date :  <span class="required">*</span></label>
															<div class="controls">
															
																<div class="input-append date date-picker" <?php if(@$subscription_date) { ?>} date-date="<?php echo @$subscription_date; ?>" <?php } else { ?>  <?php } ?> data-date-viewmode="years">
																
																<input type="text" placeholder="" class="" name="subscription_date" id="subscription_date" value="<?php echo (@$subscription_date!='')?date('Y-m-d', strtotime(@$subscription_date)):''; ?>"><span class="add-on"><i class="icon-calendar"></i></span>
																</div>
																<span for="subscription_date" class="help-inline" style="display: none;">This field is required.</span>
																  
														  </div>
														
											   </div>
											   <div class="control-group">
													<label class="control-label">Next Subscription Date :  <span class="required">*</span></label>
																<div class="controls">
															
																<div class="input-append date date-picker" <?php if(@$next_subscription_date) { ?>} date-date="<?php echo $next_subscription_date; ?>" <?php } else { ?> data-date="2012-02-12" <?php } ?> data-date-format="yyyy-mm-dd" data-date-viewmode="years">
																	<input type="text" placeholder="" class="" name="next_subscription_date" id="next_subscription_date" value="<?php echo (@$next_subscription_date!='')?date('Y-m-d', strtotime(@$next_subscription_date)):''; ?>"><span class="add-on"><i class="icon-calendar"></i></span>
																</div>
																<span for="subscription_date" class="help-inline" style="display: none;">This field is required.</span>
																  
																</div>
												</div>-->

												
												<div class="form-group ">
													<label class="control-label col-md-2">Select Country <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="country_id" id="country_id" class="medium m-wrap">
															<option value="">Select Country</option>
															
															<?php $all_country =getActiveCountry();;
															
															foreach($all_country as $cmp){
															?>
															
																<option value="<?php echo $cmp->country_id ?>" <?php echo ($plan_id==$cmp->country_id)?'selected=	""':'' ?>><?php echo $cmp->country_name; ?></option>
																<?php 
															} ?>
													
														</select>
													</div>
												</div>
												
												

												
													
												
											
												
												
												
												
												
												<!--<div id="coupon_contanier">
												<?php if($company_id!="" && isset($one_user1)){ ?>
											<?php if(@$one_user1['num_rows']>0){  
												
												$i =1;
												?>
												<div id="coupon_contanier">
													<div class="control-group">												
													<div class="controls">
														<a href="Javascript:" onclick="addMoreCoupon()" class="btn green"><i class="icon-plus"></i></a>
														</div>
														</div>
													
												<?php foreach(@$one_user1['result'] as $a) { ?>

												<div class="control-group">
													
													<label class="control-label">Address<span class="required">*</span></label>
													<div class="controls">
														
														<textarea id="address<?php echo $i; ?>" class="m-wrap large" name="address[]"><?php if(@$a->address){ ?><?php echo $a->address; ?><?php } ?></textarea>
														<a href="Javascript:" onclick="removeCouponAjax(this,'<?php echo $a->address_company_id;?>')" class="btn red"><i class="icon-remove"></i></a>
														<!--<a href="Javascript:" onclick="delete_add('<?php echo $a->address_company_id; ?>','<?php echo $a->company_id; ?>','<?php echo $redirect_page;?>','<?php echo $option;?>','<?php echo $keyword;?>','<?php echo $limit;?>','<?php echo $offset; ?>')" class="btn red"><i class="icon-remove"></i></a>-->
												<!--	<span for="address<?php echo $i; ?>" class="help-inline" style="display: none;">This field is required.</span>
													</div>
													
												</div>
												
												<?php $i ++ ;} ?>
												</div>
												
												<?php } }else { ?>

												<div id="coupon_contanier">
												<div class="control-group">
													<label class="control-label">Address<span class="required">*</span></label>
													<div class="controls">
														<textarea id="address" class="m-wrap large" name="address[]"></textarea>
														<a href="Javascript:" onclick="addMoreCoupon()" class="btn green"><i class="icon-plus"></i></a>
														<span for="address" class="help-inline" style="display: none;">This field is required.</span>
													</div>
													
												</div>
												</div>
												<?php } ?>
												</div>-->
												
												<div class="form-group ">
													<label class="control-label col-md-2">Company Timezone<span class="required">*</span></label>
													<div class="controls">
												
                                                                                                            <select name="company_timezone" id="company_timezone" style="width:320px;height:35px;" class="border-change">
													<option value="">Select Time Zone</option>
													<?php 
													
													if(isset($timezone) && $timezone!=''){
														foreach($timezone as $t){
															?>
															<option value="<?php echo $t->timezone_name;?>" <?php if($company_timezone==$t->timezone_name){ ?> selected="selected"<?php } ?>><?php echo $t->name;?></option>
															<?php
														}
													}?>
												</select>
														<!--<input type="text" class="m-wrap large"  name="company_timezone" id="company_timezone" placeholder="" value="<?php echo $company_timezone; ?>">-->
													</div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Company Date Format<span class="required">*</span></label>
													<div class="controls">
														<!--<input type="text" class="m-wrap large"  name="company_date_format" id="company_date_format" placeholder="" value="<?php echo $company_date_format; ?>">-->
                                                                                                            <select name="company_date_format" id="company_date_format" class="form-field settingselectbox required border-change" style="width:220px;height:35px;">
														<option value="">Select Date Format</option>
                                                                                                                <option value="m/d/Y" <?php if($company_date_format=="m/d/Y"){ ?> selected="selected"<?php } ?>>m/d/Y</option>
                                                                                                                 <option value="d/m/Y" <?php if($company_date_format=="d/m/Y"){ ?> selected="selected"<?php } ?>>d/m/Y</option> 
                                                                                                             </select>
													</div>
												</div>
												
													<div class="form-group ">
                                                                                                            <label class="control-label col-md-2" style="margin-right: 5px;">Company  Logo :</label>
														<div class="controls">
															<div class="fileupload fileupload-new" data-provides="fileupload">
																<div class="input-append">
																	<div class="uneditable-input profile-change"><i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span></div>
                                                                                                                                        <span class="btn btn-file" style="margin-left:-3px;">
																		<span class="fileupload-new">Select Image</span>
																		<span class="fileupload-exists">Change</span><input type="file" class="default" name="profile_image" id="profile_image" /></span>
																		<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
																	</div>														
																</div>
															<input type="hidden" name="prev_profile_image" id="prev_profile_image" value="<?php echo $prev_profile_image; ?>" />												
															</div>
															<label for="profile_image" generated="true" style="display:none" class="error">Please enter a value with a valid extension.</label>
															<?php 
															$bucket = $this->config->item('bucket_name');
															$s3_display_url = $this->config->item('s3_display_url');
															//$name = 'upload/company_orig/'.$prev_profile_image;
															if($prev_profile_image!='' && $this->s3->getObjectInfo($bucket,'upload/company_orig/'.$prev_profile_image)){ ?>
																<div class="form-group" style="clear:both">
																	<label class="control-label"></label>
																	<div class="controls">
																		<div class="col-md-2">
																			<img src="<?php echo $s3_display_url.'upload/company_orig/'.$prev_profile_image; ?>" width="50"  height="50" />
																			<a style="float:left" href="<?php echo base_url(); ?>Company/removeimage/<?php echo $company_id.'/'.$prev_profile_image.'/'.$limit.'/'.$offset.'/'.$redirect_page.'/'.$option.'/'.$keyword;?>" id="remove" name="remove">Remove image</a>
																		</div>
																	</div>
																</div>
															<?php } ?>
													<div class="clear"></div>
												</div>
												
												
												<div class="col-md-12 margin20"><label class="label-control blue" id="ad_label">Administrator Information </label></div>
											
											
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
													<label class="control-label col-md-2">E-mail Address<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap large"  name="email" id="email" placeholder="" value="<?php echo $email; ?>">
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
													<input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>" />
													<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
													<input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
													<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
													
													 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
													 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
													
													<button class="btn green" type="submit"><?php echo ($company_id!='')?'Update':'Submit' ?></button>
													
													<?php if($redirect_page == 'list_company')
														{?>
														
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("Company/".$redirect_page.'/'.$limit.'/'.$offset); ?>'" />
														<?php }else
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("Company/".$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset); ?>'" />
														
														
														<?php }?>
												</div>
												<input type="hidden" id="menu_cnt" name="menu_cnt" value="<?php echo  isset($one_user1['num_rows']) ? $one_user1['num_rows']+1 : 1; ?>" />
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
			
			
			<div id="hid_div" style="display: none;">
				
			
														<div class="control-group">
													<label class="control-label">Address<span class="required">*</span></label>
													<div class="controls">
														<textarea id="address" class="m-wrap large" name="address[]"></textarea>
														<a href="Javascript:" onclick="removeCoupon(this)" class="btn red"><i class="icon-remove"></i></a>
														<span for="address" class="help-inline"  style="display: none;">This field is required.</span>
													</div>
																									
												</div>							
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


<script type="text/javascript">
	
	
	function addMoreCoupon()
		{
			var cnt=Number($('#menu_cnt').val())+1;
			var tmp_div2 = document.createElement("div");
			tmp_div2.id='coupon_'+cnt;
			var more_htm=$('#hid_div').html();
		//	more_htm =more_htm.replace("address1", "address"+cnt);
			//more_htm =more_htm.replace("category_id1", "category_id"+cnt);
			//more_htm =more_htm.replace("product_type1", "product_type"+cnt);
			//more_htm =more_htm.replace("is_included1","is_included"+cnt);
			//more_htm =more_htm.replace("meal_id1","meal_id"+cnt);
		//	more_htm =more_htm.replace("recipi_id1","recipi_id"+cnt);
			tmp_div2.innerHTML = more_htm;
			
			
			$('#coupon_contanier').append(tmp_div2);
			$('#menu_cnt').val(cnt);
			
		}
		
		
		function removeCoupon(val)
		{
			var cnt=Number($('#menu_cnt').val())-1;
			$('#menu_cnt').val(cnt);
			$(val).parent().parent().parent().remove();
		}
		
		function removeCouponAjax(val,id)
		{
			var ans = confirm("Are you sure, you want to delete this Company Address?");
			if(ans)
			{
				$.ajax({
				url:"<?php echo site_url('Company/removeCompanyAjax/') ?>/"+id,
				success:function(data){
					var cnt=Number($('#menu_cnt').val())-1;
					$('#menu_cnt').val(cnt);
					$(val).parent().parent().remove();
					
					<?php $error = DELETE_RECORD; ?>
					 $.growlUI('<?php echo $error; ?>');

				}
				});
				
			}
		}
	
	
	
	
	
</script> 

<script>
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
			   
		            var form1 = $('#company');
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
                        company_name: {
                                required: true,
                                alpha : true
                            },

			company_address: {
                                required: true,
                        },
			company_email: {
                            required: true,
                            email:true
                        },
			plan_id: {
                            required: true
                        },
                        country_id: {
                            required: true
                        },
                        company_phoneno: {
                            minlength: 10,
                                maxlength: 10,
                                                    number:true
                        },
                        company_timezone: {
                            required: true
                        },
                        company_date_format: {
                            required: true
                        },
			status: {
                            required: true
                       },
                  /* subscription_date: { required: true },
					next_subscription_date: { required: true },*/
                        first_name: {
                            required: true,
                            alpha : true
                        },
			last_name: {
                            required: true,
                            alpha : true
                        },
			email: {
                            required: true,
                            email:true
                        },
					
                },
                messages: {
		        	profile_image:{
		        		accept: "Please provide valid image.",
		        	}
		        },
		errorPlacement: function (error, element) {
			error.insertAfter(element); // for other inputs, just perform default behavior
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
