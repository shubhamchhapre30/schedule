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
					<h2 class="title-heading text-upper"> Start<span> Scheduling </span> Today </h2>
			 	</div>
				
				<div class="register-block">
					<div class="horizontal-form">
						<!-- BEGIN FORM-->
						 <?php            
                                $attributes = array('name'=>'signup','id'=>'signup','class'=>'form-vertical login-form');
                                echo form_open('home/signup',$attributes);
                            ?> 
							<div class="control-group margin-bottom-40">
								<div class="controls">
									<input type="text" name="email" id="email" placeholder="Enter  your email here" class="m-wrap fullwd m-hight" />
									<!--<span class="help-inline">Some hint here</span>-->
								</div>
							</div>
							
							 <div class="control-group">
								<div class="controls text-center margin-bottom-20">
									<button type="submit" class="btn blue large "> Next </button>
								 </div>
								 
							</div>
						</form>
						<!-- END FORM-->  
					</div>
				 </div>
			 	
				<p class="text-center pera1 margin-top-20"> We Strongly recommend that you use the non mobile version to subscribe to Schedullo. You will have access to additional features.  </p> 
				
				<hr class="hrline"> 
				<div class="text-center margin-top-20"> 
					<a href="javascript://" class="bluelink"> Access to the full web version </a> 
				</div> 
	  
			  
       
 			</div> <!-- /container -->
		</div>
	</div>
</div>
<script>

	$(document).ready(function() { 
				
		    var form1 = $('#signup');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            $('#signup').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
					
					email: {
                        required: true,
						email:true,
						remote: {
							url: "<?php echo site_url("home/chk_email_exist");?>",
							type: "post",
							data: {
								email: function(){ return $("#email").val(); }
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

               /*
                submitHandler: function (form) {
                                   success1.show();
                                   error1.hide();
                                   $("button[type=submit]").prop("disabled",true);
                                   form.submit();
                               }*/
               
            });
            
                       
		});
</script>