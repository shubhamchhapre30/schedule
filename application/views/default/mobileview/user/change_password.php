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
if($remember_me == "" || $remember_me == "0")
{
	$email= '';
	$password='';
}
?>

<script>

	$(document).ready(function() { 
				
		    var form1 = $('#login');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            $('#login').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
					
					email: {
                        required: true,
						email:true
                    },
                   
                    password: {
                        required: true                        
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

<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container">
  			<div class="container">
			 	<div class="title-block margin-bottom-40">
					<!--<div class="text-center margin-bottom-20 animated bounceIn "> <img  src="img/logo-icon.png" alt="logoicon" />  </div>-->
					<h2 class="title-heading"> <span> LOGIN </span> </h2>
					<!--<h2 class="title-heading"> Hello, Please <span> sign in </span> </h2>-->
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
                                $attributes = array('name'=>'login','id'=>'login','class'=>'form-vertical login-form');
                                echo form_open('home/login',$attributes);
                            ?>
						<!-- BEGIN FORM-->
						
							<div class="control-group">
								<div class="controls">
									<input type="text" placeholder="Enter  your email here" name="email" id="email" value="<?php echo @$email; ?>" class="m-wrap fullwd " />
									<!--<span class="help-inline">Some hint here</span>-->
								</div>
							</div>
							
							<div class="control-group">
								 <div class="controls">
									<input type="Password" placeholder="******" id="password" name="password" value="<?php echo $password; ?>" class="m-wrap fullwd" />
								 </div>
							</div>
							
							
							<div class="control-group">
								<div class="controls">
									<div class="checkboxes">
										<label class="label_check" for="remember_me">
										<input name="remember_me" id="remember_me" value="1" <?php echo @$remember_me==1 ? 'checked':''; ?> type="checkbox" > Remember me</label>
										<a href="<?php echo site_url('home/forgot_password'); ?>" class="forgotlink pull-right"> Forgot Password ? </a>
									 </div>
									 
									 
								</div>
							</div>
					 
							<div class="control-group">
								<div class="controls text-center margin-bottom-20">
									<button type="submit" class="btn blue large"> Login </button>
								 </div>
								<p class="txt-normal2 text-center"> Not a user yet? <a href="<?php echo site_url('home/signup');?>" class="bluelink">Sign Up </a> </p>
							</div>
						</form>
						<!-- END FORM-->  
					</div>
				 </div>
				
				<hr class="hrline"> 
				 
				<div class="text-center margin-top-20"> 
					<a href="#" class="bluelink"> Access to the full web version </a> 
				</div>
	  
			  
       
 			</div> <!-- /container -->
		</div>
	</div>
</div>

