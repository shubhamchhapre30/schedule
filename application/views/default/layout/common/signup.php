<?php
$theme_url = base_url().getThemeName();
if($msg){
$message = base64_decode($msg);
$array_data = explode(',', $message);
$error = base64_decode($array_data[0]);
$first_name = $array_data[1];
$last_name = $array_data[2];
$email = $array_data[3];
$password = $array_data[4];
}else{
    $message = '';
    $first_name = '';
    $last_name = '';
    $email = '';
    $password = '';
}
?>
<!-- Google Code for Kanban Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 956698216;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "vqRbCIPyxGcQ6JyYyAM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js?Ver=<?php echo VERSION;?>">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/956698216/?label=vqRbCIPyxGcQ6JyYyAM&guid=ON&script=0"/>
</div>
</noscript>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/bootstrap_notify.js?Ver=<?php echo VERSION;?>"></script> 
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
if('<?php echo $error?>'=='error'){ 
   $.notify({
        title:'Please verify Captcha.',
        message:''
    },{
      element: 'body',
      type: 'error',
      animate: {
	enter: 'animated fadeInUp',
        exit: 'animated fadeOutRight'
      },
      placement: {
        from: "top",
        align: "center"
      },
      allow_dismiss: true,
      offset: 20,
      spacing: 10,
      z_index: 100122123
    });
}
</script>
<script>
  function onSubmit(token) {
     document.getElementById("signup").submit();
  }
</script>
<div class="container" style="margin-top: 90px;">
	<div class="title-block margin-bottom-40">
		<h3><b>Discover the power of Schedullo.</b></h3>
		<h4>It's totally free for one user without time limit !</h4>	
	</div>
	<div class="register-block signup_register">
		<div class="horizontal-form">
			<h2 class="title-heading text-center margin-bottom-30" style="margin-top: 10px;">  Start<b> saving time</b> Today  </h2>
			
			<!-- BEGIN FORM-->
                        <form action="<?php echo site_url('home/signup5'); ?>" name="signup" id="signup" class="form-vertical login-form" method="post" accept-charset="utf-8" validate="novalidate">
				<div class="form-group margin-bottom-10" style="width: 50%;float: left; padding-right: 10px">
				<div class="controls">
				<label>First Name</label>
                                <input name="first_name" id="first_name" class="m-wrap fullwd m-hightcblk input_field_css" type="text" value="<?php if($first_name){ echo $first_name;}?>">
				</div>
				</div>
			<div class="form-group margin-bottom-10" style="width: 50%;float: left;">
				<div class="controls">
				<label>Last Name</label>
					<input name="last_name" id="last_name" class="m-wrap fullwd m-hightcblk input_field_css" type="text" value="<?php if($last_name){ echo $last_name;}?>">
				</div>
			</div>
			<div class="form-group margin-bottom-10" style="border-radius: 3px;">
				<div class="controls">
				<label>Email</label>
					<input name="email" id="email" class="m-wrap fullwd m-hightcblk email_pass_css" type="email" value="<?php if($email){ echo $email;}?>">
				</div>
			</div>
                        <div class="form-group margin-bottom-20" >
				<div class="controls">
				<label>Company Name</label>
                                <input name="company_name" id="company_name"  class="m-wrap fullwd m-hightcblk email_pass_css" type="text" value="">
				</div>
			</div>    
                        <div class="form-group margin-bottom-20" id="pass123" style="position: relative">
				<div class="controls">
				<label>Password</label>
                                <input name="password" id="password" maxlength="16" class="m-wrap fullwd m-hightcblk email_pass_css" type="password" value="<?php if($password){ echo $password;}?>"><span class="show_password"><img src="<?php echo $theme_url; ?>/img/eye.png"/></span>
				</div>
			</div>
                        <div class="form-group margin-bottom-20">
				<div class="controls">
				<label>Confirm Password</label>
                                <input name="confirm_password" id="confirm_password" maxlength="16" class="m-wrap fullwd m-hightcblk email_pass_css" type="password" value="<?php if($password){ echo $password;}?>">
				</div>
			</div>
			<div id="recaptcha" class="g-recaptcha" data-sitekey="<?php echo GOOGLE_SITE_KEY; ?>" data-callback="onSubmit" data-size="invisible"></div>			
			<div class="form-group">
				<div class="controls text-center margin-top-20">
                                    <input type="hidden" name="plan_id" id="plan_id" value="6" />
                                    <button type="submit" class="btn blue large text-upper " id="letsgo" value=""> Get Started </button>
				</div>						
			</div>
			</form><!-- END FORM -->
		</div>
	</div>
	
	<!--<div class="title-block margin-bottom-40" style="margin-top: 40px;">	
		<h5>Add team members for 30 days without engagement<br><br>Your subcription will revert back to 1 user after 30 days if you do not wish to subcribe for a plan.</h5>
	</div>-->
	<div class="title-block margin-bottom-40" style="margin-top: 40px;">
            <p>By clicking this button, you agree to our <a target="_blank" href="https://www.schedullo.com/terms-of-service/">Terms of Use </a>and <a target="_blank" href="https://www.schedullo.com/privacy-policy/">Privacy Policy.</a></p>
            <p>Already have a Schedullo account? <a href="<?php echo site_url('home/index'); ?>">Sign in</a></p>
	</div>			
</div>



<!-- #################################################################################### -->

<script type="text/javascript">
 $(document).ready(function () {
    $(".show_password").click(function () {
        if ($("#password").attr("type")=="password") {
            $("#password").attr("type", "text");
        }
        else{
            $("#password").attr("type", "password");
        }
    });
 });
</script>
<script>

	$(document).ready(function() { 
                    $.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "have only letters.");
                    $.validator.addMethod("pass", function(value, element) {
		        return this.optional(element) || (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z/\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/]{8,}$/).test(value);
		    }, "minimum length of 8 with at least 1 number, 1 upper & 1 lower letter.");
                    
                    
                    $('#signup input').on('keyup blur', function () {
                        if ($('#signup').valid()) {
                            $('#letsgo').prop('disabled', false);
                        }else{
                             $("#letsgo").attr("disabled",true);
                        } 
                    });	    
		 
			
		   
            $('#signup').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
			first_name: {
                        required: true,
                        alpha : true,
	                maxlength: 25
                        },
                        last_name : {
                   	required : true,
                   	alpha : true,
	                maxlength: 25
                        },
                        password: {
	                required:true,
                        pass:true,
                        rangelength: [8, 16]
	                },
                        confirm_password:{
                        required: true,
                        equalTo:'#password'  
                        },
                        email: {
                        required: true,
                        email: true,
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
                                remote: 'There is an existing record with this Email Address.'
				}
		    },  
                     errorPlacement: function(error, element) {
                        error.insertBefore(element);
                        $("#letsgo").attr("disabled",true);
                    },
                    submitHandler: function (form) {
                        grecaptcha.execute();
                        $("#letsgo").text("Loading...");
                    }
            });
        });
</script>

<script>
$(document).ready(function(){
    jQuery.extend(jQuery.validator.messages, {
     required: "*",
     email: "must be valid.",
    });
});
</script>

<style>
    .help-inline{
        margin-top: 0px !important;
        padding: 0px !important;
    }
    
</style>