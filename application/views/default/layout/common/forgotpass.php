
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


<link href="<?php echo $theme_url;?>/assets/plugins/bootstrap-modal/css/bootstrap-modal.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<!-- #################################################################################### -->
<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container scyheight">
  			<div class="container">
			 	
				
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
							<div class="form-group">
								<div class="controls">
									<input type="text" placeholder="Enter your email here" name="email" id="email" class="m-wrap fullwd " />
									<!--<span class="help-inline">Some hint here</span>-->
								</div>
							</div>
							
							
							
					 
							<div class="form-group">
								<div class="controls text-center margin-bottom-20">
									<input type="hidden" name="company_id" id="company_id" value="<?php echo @$company_id;?>" />
									<button type="submit" class="btn blue text-upper " id="forgot-pass"> Retrieve Password </button>
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

<div id="companyListPopup" class="modal container hide fade" tabindex="-1" style="height: 200px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h3> Select Company  </h3>
	</div>
	<div class="modal-body">
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				<div class="form-group">
					<div class="controls" id="companyList">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-modal/js/bootstrap-modal.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
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
                                  form1.submit();
                              }
                              */
               
            });
            
                       
		});
		
		function setupLabel() {
	        if ($('.label_check input').length) {
	            $('.label_check').each(function(){ 
	                $(this).removeClass('c_on');
	            });
	            $('.label_check input:checked').each(function(){ 
	                $(this).parent('label').addClass('c_on');
	            });                
	        };
	        if ($('.label_radio input').length) {
	            $('.label_radio').each(function(){ 
	                $(this).removeClass('r_on');
	            });
	            $('.label_radio input:checked').each(function(){ 
	                $(this).parent('label').addClass('r_on');
	            });
	        };
	    };
</script>
