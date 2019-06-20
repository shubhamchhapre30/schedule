<?php
$site_setting=site_setting();
$theme_url = base_url().getThemeName();
$uriseg=uri_string();
$uri=explode('/',$uriseg);

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
					<h2 class="title-heading text-upper">Almost   <span> There </span>   </h2>
					  
				</div>
				
				 <?php            
                                $attributes = array('name'=>'signup2','id'=>'signup2','class'=>'form-vertical login-form');
                                echo form_open('home/index/',$attributes);
                            ?>
				
				<div>
					<p class="txt-normal2 text-center  margin-bottom-40 ">  We have sent an email to </br> <a href="javascript://" class="bluelink txt-normal1">  <?php echo $email; ?> </a> </p>
					<p  class="txt-normal2 text-center margin-bottom-40" >  Click on the link located in the email to confirm your trial </p>
					<button type="submit" class="btn blue large "> Continue </button>
				</div>
				</form>
 	  			<!--<div class="register-block">
					<div class="horizontal-form">
					  
						<form action="#">
							  <div class="control-group">
								<div class="controls text-center margin-bottom-20">
									<button type="submit" class="btn blue large "> Continue </button>
								 </div>
						 	</div>
						</form>
						   
					</div>
				 </div>-->
				 
				 <hr class="hrline"> 
				 
				<div class="text-center margin-top-20"> 
					<a href="javascript://" class="bluelink"> Access to the full web version </a> 
				</div>
	  
			  
       
 			</div> <!-- /container -->
		</div>
	</div>
</div>