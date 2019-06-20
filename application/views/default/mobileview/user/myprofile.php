<?php 
$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
?>
<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container">
  			<div class="container">
			 	<!--<div class="title-block margin-bottom-40">
					<div class="text-center margin-bottom-20 animated bounceIn"> <img  src="img/logo-icon.png" alt="logoicon" />  </div>
					<h2 class="title-heading text-upper"><span> Home </span>   </h2>
				 </div>-->
				 
				 <div class="page-title margin-bottom-25">
				 	<h2> My Profile </h2>
				 </div>
				 <div class="border-bx">
				 	
				 		<?php 
									if($this->session->flashdata('msg') != ''){
										?>
										<script>
											$(document).ready(function() {
												$('#update_profile').slideDown('slow').delay(5000).slideUp('slow');
											});
										</script>
										<div class='alert alert-success' id="update_profile" ><a class='closemsg' data-dismiss='alert'></a><span>
										<?php
											if($this->session->flashdata('msg') == 'update_profile'){ echo 'User profile updated successfully.'; } 
											?>
											</span></div>
										<?php
									} ?>
									<?php if($error){
											?>
											<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
											<?php
										}?>
										
				  <div class="horizontal-form">
					 	<!-- BEGIN FORM-->
						<?php $attributes = array('name'=>'frm_my_profile','id'=>'frm_my_profile');
							  echo form_open_multipart('user/myprofile',$attributes); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										 <label class="control-label">First name : </label> 
										<div class="controls">
											<input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" placeholder="Enter your first name" class="m-wrap fullwd " />
											 
										</div>
									</div>
								</div>
								
							</div>
							<div class="row">
							<div class="col-md-12">
									<div class="control-group">
										 <label class="control-label">Last name : </label> 
										<div class="controls">
											<input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>"  placeholder="Enter your last name" class="m-wrap fullwd " />
											 
										</div>
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										 <label class="control-label">Email :  </label> 
										<div class="controls">
											<input type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="Email" class="m-wrap fullwd " />
										 </div>
									</div>
								</div>
							 </div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										 <label class="control-label">Profile  :  </label> 
										<div class="controls">
											<div class="profile-pic">
												<!--<a id="imgp" onclick="del_img('<?php echo $user_id;?>');" href="javascript://"> <i class="stripicon redcloseicon"> </i> </a>-->
												<?php if((isset($profile_image) && $profile_image!='') && $this->s3->getObjectInfo($bucket,'upload/user/'.$profile_image)){
																			echo '<a onclick="del_img(\''.$user_id.'\')" href="javascript://"> <i class="stripicon redcloseicon"> </i> </a><img alt="profile-img" class="img-thumbnail img-responsive" src="'.$s3_display_url.'upload/user/'.$profile_image.'"style="width:240px;height:240px; ">';
																		}else{
																			
																			echo '<img alt="profile-img" class="img-thumbnail img-responsive" src="'.base_url().'upload/user/no_image.jpg" style="width:240px;height:240px; ">';
																		}?>
												<!--<img src="img/240x240.jpg" class="img-thumbnail img-responsive" alt="profile-img" />--> 		
												
												
												<input type="hidden" name="hdn_profile_image" id="hdn_profile_image" value="<?php echo $profile_image; ?>" />
											</div>
											
											<!--<input type="text" placeholder="Phone" class="m-wrap fullwd " />-->
										 </div>
									</div>
								</div>
								
							</div>
							<!--<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										 <label class="control-label">Country : <span class="estric"> * </span></label> 
										<div class="controls">
											 <select class="fullwd m-wrap" tabindex="1">
												<option value="Category 1"> Usa </option>
												<option value="Category 2">Category 2</option>
												<option value="Category 3">Category 5</option>
												<option value="Category 4">Category 4</option>
											</select>
										</div>
									</div>
								</div>
							</div>-->		
								
							  <div class="control-group">
								<div class="controls text-center margin-top-20">
									<div class="margin-bottom-20">
										<div class="browse1">
											<input type="file" value="loremipsum" name="profile_image" id="profile_image" class="browse"/>
										</div>
									</div>
									<input type="hidden" name="pre_email" id="pre_email" value="<?php echo $pre_email;?>" />
									<input type="hidden" name="user_id" id="user_id" value="<?php echo base64_encode($user_id); ?>" /> 
									<button type="submit" class="btn blue btn-mid"> <i class="stripicon correcticon"> </i> Save </button>
									<button type="button" onClick="location.href='<?php echo base_url('user/dashboard_menu');?>'" class="btn blue btn-mid"> <i class="stripicon backicon"> </i> Cancel </button>
								 </div>
								 
							</div>
						</form>
						<!-- END FORM-->  
					</div>
				 </div>
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		
		var form1 = $('#frm_my_profile');
        var error1 = $('.alert-error', form1);
        var success1 = $('.alert-success', form1);
        
        $.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "Please enter only letters.");
		
        $('#frm_my_profile').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
				
			   first_name : {
               		required : true,
               		alpha : true,
               		minlength : 3,
               		maxlength: 25
               },
               last_name : {
               		required : true,
               		alpha : true,
               		minlength : 3,
               		maxlength: 25
               },
               email : {
               		required : true,
               		email : true,
               		remote: {
						url: "<?php echo site_url("user/chk_email_exist");?>",
						type: "post",
						data: {
							email: function(){ return $("#email").val(); },
							user_id: function(){ return $("#user_id").val(); }
						}
					}
               }
			},
	        messages: {
				email: {
					required: 'Email address is required',
					email: 'Please enter a valid email address',
					remote: 'There is an existing record with this Email Address.'
				}
			},                
			submitHandler: function (form) {
               success1.show();
               error1.hide();
               form.submit();
           }
           
        });
        
        

	});
	
	function del_img(id)
    {
    	var ans = "Are you sure, you want to remove profile picture?";
		alertify.confirm(ans,function(chk){
        if(chk){
        	
			$('#dvLoading').fadeIn('slow');
			$.ajax({
			url : "<?php echo site_url('user/deleteProfileImage') ?>/"+id,
			cache: false,
			success: function(responseData) {
						//$("#listcmt").html(responseData);
						//$('.profile-pic').hide('slow');
						$('.profile-pic').html('<img alt="profile-img" class="img-thumbnail img-responsive" src="<?php echo base_url();?>upload/user/no_image.jpg" style="width:240px;height:240px; ">');
		            	$('#dvLoading').fadeOut('slow');
		            	
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});	
			
			}
			else
			{
				return false;
			}
    	});	
    	//alert(id);
    	//$('.profile-pic').hide('slow');
    }
</script>