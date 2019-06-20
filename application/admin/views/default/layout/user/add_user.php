<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css?Ver=<?php echo VERSION;?>" />
<script language="javascript">
	$(document).ready(function() {
		
		<?php if($msg!=''){
			
	     if($msg == "image_remove"){ $error = IMAGE_REMOVE;}
    ?>    
    //  $.growlUI('<?php //echo $error; ?>');
   <?php } ?>   
   
   getstaff("<?php echo $company_id; ?>");
   
   <?php if($user_id){ ?>
   
   $("#is_manager").click(function(){
		if($("#is_manager").prop("checked")){
			
		} else {
			var count = '<?php echo get_user_count_under_manager($user_id);?>';
			
			if(count>0){
				alert("Please remove employees reporting to the user before removing manager's rights.");
				$("#is_manager").prop("checked",true);
				$("#is_manager").parent('span').attr('class','checked');
			}
		}
	});
	
	<?php } ?>
   
});


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
							<?php echo ($user_id=="")?'Add':'Edit'; ?> User					
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption"> <?php if($user_id==""){ echo 'Add User'; } else { echo 'Edit User'; }?> </div>
											
										</div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											
												<?php // Change the css classes to suit your needs    

										$attributes = array('class' => 'form-horizontal', 'id' => 'user','name'=>'user');
										echo form_open_multipart('user/add', $attributes); ?>
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
												
												<?php 
												if($user_id == 0 || $user_id == "")
												{
												?>
												<div id="password_hd" class="form-group ">
													<label class="control-label col-md-2">Password<span class="required">*</span></label>
													<div class="controls">
														<input type="password" class="m-wrap large" value=""  name="password" id="password" />
													</div>
												</div>
												<?php }
												else {
													?>
												<input type="hidden" value="0" name="password" id="password" />
												<?php }?>
												
											<!--
											<div class="control-group">
																								<label class="control-label">Contact No<span class="required">*</span></label>
																								<div class="controls">
																									<input type="text" class="m-wrap large"  name="contact_no" id="contact_no" placeholder="" value="">
																								</div>
																							</div>-->
											
											
													
											    <div class="form-group ">
													<label class="control-label col-md-2">user Timezone<span class="required">*</span></label>
													<div class="controls">
                                                                                                            <select name="user_time_zone" id="user_time_zone" style="width: 230px !important;height: 32px !important;">
												<option value="">Select Time Zone</option>
												<?php 
													
													if(isset($timezone) && $timezone!=''){
														foreach($timezone as $t){
															?>
															<option value="<?php echo $t->timezone_name;?>" <?php if($user_time_zone==$t->timezone_name){ ?> selected="selected"<?php } ?>><?php echo $t->name;?></option>
															<?php
														}
													}?>
											</select>
													</div>
												</div>	
												
												<?php 
												if($user_id != 0 || $user_id != "")
												{
												if($is_owner=='1')
												{	
												?> 
												<input type="hidden" id="is_own"  value="<?php echo $is_owner; ?>" name="is_own"/>
														
												<div class="form-group ">
													<label class="control-label col-md-2">Owner</label>
													<div class="controls">
													<label class="checkbox">
															<input type="checkbox" id="is_owner" disabled readonly  value="1" <?php echo $is_owner=='1'?'checked="checked"':'';  ?> name="is_owner"/>
														</label>
														</div>
												</div>	
												
												<?php } }?>
												
												<!--<input type="hidden" id="is_ad"  value="<?php echo $is_administrator; ?>" name="is_ad"/>-->
												<?php if($is_owner == "0"){ ?>
													<div class="form-group">
														<label class="control-label col-md-2">Administrator<!--<span class="required">*</span>--></label>
														<div class="controls">
														<label class="checkbox">
																<input type="checkbox" id="is_administrator"   value="1" <?php echo $is_administrator=='1'?'checked="checked"':'';  ?> name="is_administrator"/>
															</label>
															</div>
													</div>	
												<?php } else {	?>
													<input type="hidden" name="is_administrator" id="is_administrator" value="<?php echo $is_administrator; ?>" />
												<?php } ?>
												
												
												<div class="form-group ">
													<label class="control-label col-md-2">Manager</label>
													<div class="controls">
													<label class="checkbox">
															<input type="checkbox" id="is_manager"  value="1" <?php echo $is_manager=='1'?'checked="checked"':'';  ?> name="is_manager"/>
														</label>
														</div>
												</div>	
												
												
												<div id="companyList">
													<?php if(($user_id != 0 || $user_id != "")){ ?>
													<div class="form-group col-md-12">
														<label class="control-label col-md-2">Select Company <span class="required">*</span></label>
														<div class="controls">
															<select tabindex="1" name="company_id" id="company_id" onchange="getstaff(this.value)" class="medium m-wrap"  disabled>
																<option value="">Select Company</option>
																
																<?php $all_company =getActiveCompany();
																
																foreach($all_company as $cmp){
																?>
																
																	<option value="<?php echo $cmp->company_id ?>"  <?php echo ($company_id==$cmp->company_id)?'selected=	""':'' ?>><?php echo $cmp->company_name; ?></option>
																	<?php 
																} ?>
														
															</select>
														</div>
													</div>
													<?php } ?>
												</div>
												
												<?php 
												if($is_administrator=='0')
												{	
												?>
												<div class="form-group ">
													<label class="control-label col-md-2">Staff Level<span class="required">*</span></label>
													<div class="controls">
															<select tabindex="1" name="staff_level" id="staff_level" class="medium m-wrap" >
															<option value="">Select Staff</option>
															
															<?php /*$all_company =getActiveCompany();
															
															foreach($all_company as $cmp){
															?>
															
																<option value="<?php echo $cmp->company_id ?>" <?php echo ($company_id==$cmp->company_id)?'selected=	""':'' ?>><?php echo $cmp->company_name; ?></option>
																<?php 
															} */?>
													
														</select>
													</div>
												</div>
												<?php } ?>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Select Country <span class="required">*</span></label>
													<div class="controls">
                                                                                                            <select tabindex="1" name="country_id" id="country_id" class="medium m-wrap" style="width: 230px !important;">
															<option value="">Select Country</option>
															
															<?php $all_country =getActiveCountry();;
															
															foreach($all_country as $cmp){
															?>
															
																<option value="<?php echo $cmp->country_id ?>" <?php echo ($country_id==$cmp->country_id)?'selected=	""':'' ?>><?php echo $cmp->country_name; ?></option>
																<?php 
															} ?>
													
														</select>
													</div>
												</div>
												
												
													<div class="form-group">
                                                                                                            <label class="control-label col-md-2" style="margin-right: 5px;">Profile Image :</label>
														<div class="controls">
															<div class="fileupload fileupload-new" data-provides="fileupload">
																<div class="input-append">
																	<div class="uneditable-input profile-change" ><i class="fa fa-file fileupload-exists"></i><span class="fileupload-preview"></span></div>
                                                                                                                                        <span class="btn btn-file" style="margin-left:-3px">
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
															//$name = 'upload/user/'.$prev_profile_image;
															if(($prev_profile_image!='') && $this->s3->getObjectInfo($bucket,'upload/user/'.$prev_profile_image)){ ?>
																<div class="form-group" style="clear:both">
																	<label class="control-label"></label>
																	<div class="controls">
																		<div class="span2">
																			<img src="<?php echo $s3_display_url.'upload/user/'.$prev_profile_image; ?>" width="50"  height="50" />
																			<a style="float:left" href="<?php echo base_url(); ?>user/removeimage/<?php echo $user_id.'/'.$prev_profile_image.'/'.$limit.'/'.$offset.'/'.$redirect_page.'/'.$option.'/'.$keyword;?>" id="remove" name="remove">Remove image</a>
																		</div>
																	</div>
																</div>
															<?php } ?>
													<div class="clear"></div>
												</div>
												
												<div class="form-group ">
													<label class="control-label col-md-2">Status <span class="required">*</span></label>
													<div class="controls">
														<select tabindex="1" name="user_status" id="user_status" class="small m-wrap">
															<option value="">Select</option>
															<option value="active" <?php if($user_status=="Active"){ ?> selected="selected"<?php } ?>>Active</option>
															<option value="inactive" <?php if($user_status=="Inactive" && $user_status!=''){ ?> selected="selected"<?php } ?>>Inactive</option>															
														</select>
													</div>
											</div>
												
												
												<div class="form-control form-change">
													<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
													<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
													<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
													<input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
													<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
													
													 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
													 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
													 <input type="hidden" name="passwordReset" id="passwordReset" value="0" />
													 <input type="hidden" name="hiddenPassword" id="hiddenPassword" value="" />
													
													<button class="btn green" type="submit"><?php echo ($user_id!='')?'Update':'Submit' ?></button>
													
													<?php if($redirect_page == 'list_user')
														{?>
														
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("user/".$redirect_page.'/'.$limit.'/'.$offset); ?>'" />
														<?php }else
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn red" onClick="location.href='<?php echo site_url("user/".$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset); ?>'" />
														
														
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
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver=<?php echo VERSION;?>"></script>  
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/js/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.input-ip-address-control-1.0.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-tags-input/jquery.tagsinput.min.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>   
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/jquery.validate.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/additional-methods.min.js?Ver=<?php echo VERSION;?>"></script>
<!-- <script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-components.js?Ver=<?php echo VERSION;?>"></script> -->
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-validation.js?Ver=<?php echo VERSION;?>"></script>	    

<script>


function getstaff(id)
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
			url: "<?php echo site_url("user/get_staff"); ?>/"+id,
			dataType: "json",
			//data: data,
			success: function(data) {
				
				
				var string = "";
				$.map(data, function (item,i) {
					//alert($("#company_ad").val());
					//alert(item.product_type_id);alert(item.product_type);
					
				if($("#staff_level").val()==item.staff_level_title)
				{
					
				string += '<option value="'+item.staff_level_id+'" selected>'+item.staff_level_title+'</option>';
				}
				else
				{
				string += '<option value="'+item.staff_level_id+'" >'+item.staff_level_title+'</option>';
				}
				
				});

				 $('#staff_level').html(string);
				
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
		<?php if(($user_id != 0 || $user_id != "")){ } else {?>
		$("#email").on("blur",function(){
			var email = $(this).val();
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/userCompanyList");?>',
				data : {email : email},
				success : function(data){
					$("#companyList").html(data);
					//$("#password_hd").css("display",'none');
				}
			});
			
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/checkUser");?>',
				data : {email : email},
				success : function(data){
					
					data = jQuery.parseJSON(data);
					if(data){
						 
						$("#hiddenPassword").val(data.password);
						$("#passwordReset").val('1');
						$("#password_hd").css("display",'none');
						$("#password").rules("remove", "required");
						$("#password").removeClass("valid");
						
					}else{
						$("#hiddenPassword").val("");
						$("#passwordReset").val('0');
						$("#password_hd").css("display",'block');
						$('#password').val("");
					}
					
				}
			});
		});
		<?php } ?>
		$('.alpha-only').bind('keyup blur',function(){ 
			$(this).val( $(this).val().replace(/[^A-Za-z]/g,'') ); 
		});
		   
	         var form1 = $('#user');
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
				email: {
                    required: true,
					email:true
                },
               	user_time_zone: {
                    required: true,
				},
                 <?php 
                if($user_id==0 || $user_id == "")
				{
                ?>
                company_id: {
                    required: true,
					
                },
                <?php } ?>
                staff_level: {
                    required: true,
					
                },
                 country_id: {
                    required: true,
					
                },
                 user_status: {
                    required: true,
					
                },
               
			   
                <?php 
                if($user_id==0 || $user_id == "")
				{
                ?>
				password: {
					
                   	required:$("#passwordReset").val() == 0,
	               	//loginRegex: true,
                    rangelength: [8, 16]
                },
                <?php }?>
			
				/*
				contact_no: {
										required: true,
										minlength: 10,
										maxlength: 10,
										number:true
									},*/
				
              
				
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
