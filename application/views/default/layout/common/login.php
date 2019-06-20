<?php
$theme_url = base_url().getThemeName();

$this->load->helper('cookie');
$email = "";
$password = "";
$remember_me = "";
$company_id = "";
$email = get_cookie('email');
$password = get_cookie('password');
$remember_me = get_cookie('remember_me');
$company_id=  get_cookie('company_id');
if($remember_me == "" || $remember_me == "0")
{
	$email= '';
	$password='';
        $company_id = '';
}
if($company_id == '')
        $company_id = get_cookie('user_company_id');
?>

<link href="<?php echo $theme_url;?>/assets/plugins/bootstrap-modal/css/bootstrap-modal.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/css/developer.css?Ver=<?php echo VERSION;?>" />
<Style>
html { 
	background: url(https://s3-ap-southeast-2.amazonaws.com/static.schedullo.com/upload/background.jpg) no-repeat center center fixed;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size:cover;
	background-size: cover;
	

}
.input-load{
    top:unset;
    padding-top: 4px;
margin-top: 11px;
width:100px;
}
</style>

<!-- #################################################################################### -->

				
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
							<div class="form-group">
								<div class="controls">
									<input type="text" placeholder="Enter your email here" name="email" id="email" value="<?php echo @$email; ?>" class="m-wrap fullwd cblk" />
									<span class="input-load" id="email_loading" style="display: none;"></span>
								</div>
							</div>
							
							<div class="form-group">
								 <div class="controls">
									<input type="password" placeholder="Password" id="password" name="password" value="<?php echo $password; ?>" class="m-wrap fullwd cblk" />
								 </div>
							</div>
							
                                                        <div class="form-group">
								 <div class="controls">
                                                                     <select name="companylist" id="companylist" class="col-md-12 m-wrap fullwd cblk " style="margin-bottom:15px;padding-left: 0px;display: none"> 
                                                                         <option value="<?php echo $company_id; ?>" selected="selected"></option>
                                                                         
                                                                     </select>
								 </div>
							</div>
							
							<div class="form-group">
								<div class="controls">
									<div class="checkboxes">
										
                                                                            <label class="label_check" for="remember_me"><input type="checkbox" name="remember_me" id="remember_me" value="1" <?php echo @$remember_me==1 ? 'checked':''; ?>/>  Remember me </label>

										<!--<label class="label_check" for="checkbox-01">
										<input name="sample-checkbox-01" id="checkbox-01" value="1" type="checkbox" checked=""> Remember me</label>-->
										<a href="<?php echo site_url('home/forgot_password'); ?>" class="forgotlink pull-right"> Forgot Password ? </a>
									 </div>
									 
									 
								</div>
							</div>
					 
							<div class="form-group">
								<div class="controls text-center margin-bottom-20">
									<input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>" />
									<input type="hidden" name="encoded_user_id" id="encoded_user_id" value="<?php echo $encoded_user_id; ?>" />
                                                                        <input type="submit" class="btn blue large text-upper " id="login-btn" value="Login" disabled="disabled" /> 
								 </div>
								 <p class="txt-normal2 text-center"> Not a user yet? <a href="<?php echo site_url('home/signup');?>" class="bluelink">Sign Up Now</a> </p>
							</div>
						</form>
						<!-- END FORM-->  
					</div>
				 </div>

<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-modal/js/bootstrap-modal.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js?Ver=<?php echo VERSION;?>" type="text/javascript" ></script>
<script>

	$(document).ready(function() {
		$("#login-btn").on("click",function(){ 
                        $("#company_id").val($( "#companylist option:selected" ).val()); 
                        $("#login-btn").attr("type","submit");
			});
		
		    var form1 = $('#login');
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            $('#login').validate({
                errorElement: 'span', 
                errorClass: 'help-inline', 
                focusInvalid: false, 
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
		function close_popup(id){
			$("#company_id").val(id);
			$("#companyListPopup").modal("hide");
			$("#login").submit();
		}
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


<script>
    $(document).ready(function(){
        
	$("#email").on('change',function(){
            var email = $("#email").val();
			if(email){
				$.ajax({
					type:'post',
					url : '<?php echo site_url("home/userCompanyList");?>',
					data : {email:email},
					async : false,
					success : function(data){
						var data = jQuery.parseJSON(data);
                                                $('#companylist').empty();
                                                var companys = data.companys;
                                                $("#companylist").html('');
                                                if(data.counts>1){
                                                    $("#companylist").css('display','block');
                                                }else{
                                                    $("#companylist").css('display','none');
                                                }
                                                $.map(companys, function(item){
                                                    if(item.company_name == ''){
                                                        $("#companylist").append("<option value="+item.company_id+">"+item.first_name+" "+item.last_name+"</option>");
                                                    }else{
                                                        $("#companylist").append("<option value="+item.company_id+">"+item.company_name+"</option>");
                                                    }
                                                });
                                                
                                          }
                                });
                        }
                        $("#companylist option:first").attr('selected','selected');
        });
    });  
    $(window).load(function()
    {
        var company_id = '<?php echo $company_id;?>';
        if(company_id == '')
        {
            $('#password').val('');
            $('#email').val('');
    }
    $("#login-btn").removeAttr('disabled');
    });
</script>

