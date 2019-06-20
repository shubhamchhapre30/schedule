
<?php
$site_setting=site_setting();
$theme_url = base_url().getThemeName();
$uriseg=uri_string();
$uri=explode('/',$uriseg);

$this->load->helper('cookie');
$email = "";
$password = "";
$remember_me = "";
$email = get_cookie('email');
$password = get_cookie('password');
$remember_me = get_cookie('remember_me');

?>



<!-- #################################################################################### -->
<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container">
  			<div class="container">
			 	<div class="title-block margin-bottom-40">
					<div class="text-center margin-bottom-20 animated bounceIn "> <img  src="<?php echo $theme_url; ?>/img/logo-icon.png" alt="logoicon" />  </div>
					<h2 class="title-heading"> Hello, Please <span> Enter Email </span> </h2>
					 <p class=""> </p>
				</div>
				
 	  			<div class="login-block">
					<div class="horizontal-form">
						<?php if($error){
							?>
							<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
							<?php
						}?>
						<!-- BEGIN FORM-->
						  <?php            
                                $attributes = array('name'=>'fr_pass','id'=>'fr_pass','class'=>'form-vertical login-form');
                                echo form_open('home/forgot_password',$attributes);
                            ?>   
							<div class="control-group">
								<div class="controls">
									<input type="text" placeholder="Enter  your email here" name="email" id="email" class="m-wrap fullwd " />
									<!--<span class="help-inline">Some hint here</span>-->
								</div>
							</div>
							
							
							
					 
							<div class="control-group">
								<div class="controls text-center margin-bottom-20">
									<button type="submit" class="btn blue text-upper "> Submit </button>
									<button type="button" class="btn blue text-upper" onclick="location.href='<?php echo site_url('home/login');?>'"> Cancel </button>
								 </div>
								 <p class="txt-normal2 text-center"> Not a user yet? <a href="<?php echo site_url('home/signup');?>" class="bluelink">Sign Up </a> </p>
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
				
		    var form1 = $('#fr_pass');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            $('#fr_pass').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
					
					email: {
                        required: true,
						email:true
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