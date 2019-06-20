
<?php
$theme_url = base_url().getThemeName();

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
		<div class="page-container scyheight">
  			<div class="container">
			 	<div class="title-block margin-bottom-40">
					<h2 class="title-heading"><span> Please reset your password </span> </h2>
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
                                $attributes = array('name'=>'resetpass','id'=>'resetpass','class'=>'form-vertical login-form');
                                echo form_open('home/reset_password/'.base64_encode($user_id).'/'.$code,$attributes);
                            ?>   
							<div class="form-group">
								<div class="controls">
									<input type="password" placeholder="Enter new password" name="password" id="password" class="m-wrap fullwd " />
									<!--<span class="help-inline">Enter your new password</span>-->
								</div>
							</div>
							
							<div class="form-group">
								<div class="controls">
									<input type="password" placeholder="Confirm new password" name="confirm_password" id="confirm_password" class="m-wrap fullwd " />
									<!--<span class="help-inline">Confirm your password</span>-->
								</div>
							</div>
							
					 
							<div class="form-group">
								<div class="controls text-center margin-bottom-20">
									<button type="submit" class="btn blue text-upper "> Confirm </button>
									<button type="button" class="btn blue text-upper" onclick="location.href='<?php echo site_url('home/login');?>'"> Cancel </button>
								 </div>
								 <!--<p class="txt-normal2 text-center"> Not a user yet? <a href="<?php echo site_url('home/signup');?>" class="bluelink">Sign Up </a> </p>-->
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
            var form1 = $('#resetpass');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            $('#resetpass').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    password: {
                        required: true,
                        pass:true,
                        rangelength: [8, 16]
                    },
                    confirm_password: {
                        required: true,
                        equalTo:'#password'
                    }	
		}                
            });
            $.validator.addMethod("pass", function(value, element) {
		        return this.optional(element) || (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z/\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/]{8,}$/).test(value);
		    }, "Minimum length of 8 with at least 1 number, 1 upper & 1 lower letter.");
                       
		});
</script>
