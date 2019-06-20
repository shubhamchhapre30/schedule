<?php
$theme_url = base_url().getThemeName();
?>
<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container">
  			<div class="container">
			 	<div class="title-block margin-bottom-40">
					<!--<div class="text-center margin-bottom-20 animated bounceIn"> <img  src="img/logo-icon.png" alt="logoicon" />  </div>-->
					<?php if($error){
							?>
							<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
							<?php
						}?>
					<h2 class="title-heading text-upper"> Final   <span> Step</span>   </h2>
				</div>
				
 	  			<div class="register-block">
					<div class="horizontal-form">
					 	<!-- BEGIN FORM-->
						<?php            
                                $attributes = array('name'=>'signup3','id'=>'signup3','class'=>'form-vertical login-form');
                                echo form_open_multipart('home/signup3/'.base64_encode($email),$attributes);
                            ?>
							<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										<!--<label class="control-label">First name : <span class="estric"> * </span> </label>-->
										<div class="controls">
											<input type="text" name="first_name" id="first_name" placeholder="First name" class="m-wrap fullwd " value="<?php echo $first_name; ?>" tabindex="1" />
											 
										</div>
									</div>
								</div>
								
							</div>
							<div class="row">
							<div class="col-md-12">
									<div class="control-group">
										<!--<label class="control-label">Last name : <span class="estric"> * </span></label>-->
										<div class="controls">
											<input type="text" name="last_name" id="last_name" placeholder="Last name" value="<?php echo $last_name; ?>" class="m-wrap fullwd " tabindex="2" />
											 
										</div>
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="control-group">
										<!--<label class="control-label">Company  name : <span class="estric"> * </span> </label>-->
										<div class="controls">
											<input type="text" name="company_name" id="company_name" placeholder="Company name" value="<?php echo $company_name; ?>" class="m-wrap fullwd " tabindex="3"  />
										 </div>
									</div>
								</div>
							 </div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										<!--<label class="control-label">Phone : <span class="estric"> * </span> </label>-->
										<div class="controls">
											<input type="text" name="company_phoneno" id="company_phoneno" placeholder="Phone" value="<?php echo $company_phoneno; ?>"  class="m-wrap fullwd " tabindex="4" />
										 </div>
									</div>
								</div>
								
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="control-group">
										<!--<label class="control-label">Country : <span class="estric"> * </span></label>-->
										<div class="controls">
											 <select class="fullwd m-wrap" tabindex="5" name="country_id" id="country_id">
												<option value="">--select--</option>
												<?php if($countries){
													foreach($countries as $country){
														?>
														<option value="<?php echo $country->country_id;?>" <?php if($country->country_id == $country_id){ echo 'selected="selected"'; } ?> ><?php echo $country->country_name;?></option>
														<?php
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="control-group">
										<!--<label class="control-label">Company  name : <span class="estric"> * </span> </label>-->
										<div class="controls">
											<input type="password" name="password" id="password" placeholder="Password" value="" class="m-wrap fullwd " tabindex="3"  />
										 </div>
									</div>
								</div>
							 </div>
							 
							 <div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="control-group">
										<!--<label class="control-label">Company  name : <span class="estric"> * </span> </label>-->
										<div class="controls">
											<input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" value="" class="m-wrap fullwd " tabindex="3"  />
										 </div>
									</div>
								</div>
							 </div>		
								
							  <div class="control-group">
								<div class="controls text-center margin-top-20">
									<input type="hidden" name="plan_id" id="plan_id" value="6" />
									<input type="hidden" name="email" id="email" value="<?php echo $email; ?>" />
									<button type="submit" class="btn blue large"> Start </button>
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

	$(document).ready(function() { 
				
		    var form1 = $('#signup3');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);
			
			$.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "Please enter only letters.");
		    
            $('#signup3').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
					
					first_name: {
                        required: true,
                        alpha : true
                   },
                   last_name : {
                   		required : true,
                   		alpha : true
                   },
                   company_name : {
                   		required : true,
                   		alpha : true
                   },
                   company_phoneno : {
                   		required : true,
                   		number : true,
                   		minlength: 10,
	                    maxlength: 10
                   },
                   country_id : {
                   		required : true
                   },
                    password: {
	                	required:true,
	                	rangelength: [8, 16]
                    },
                    cpassword: {
                    	required:true,
                    	equalTo:'#password',
                    	rangelength: [8, 16]
                    }
					
                },                
				submitHandler: function (form) {
                   success1.show();
                   error1.hide();
                   form.submit();
               }
               
            });
            
                       
		});
</script>