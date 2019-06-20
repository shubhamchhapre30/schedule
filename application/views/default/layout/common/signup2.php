<?php
$theme_url = base_url().getThemeName();

?>

<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container scyheight">
  			<div class="container">
			 	
				
 	  			<div class="register-block">
					<div class="horizontal-form">
						<?php if($error){
							?>
							<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
							<?php
						}?>
						<h2 class="title-heading text-center margin-bottom-30"> Almost   <span> There </span></h2>
						
						<p class="txt-normal2 text-center  margin-bottom-40 ">  We have sent an email to your the address <a href="#" class="bluelink">  <?php echo $email; ?>  </a>
							</br> Click on the link located in the email to confirm your trial </p>
						
						<!-- BEGIN FORM-->
						<!-- BEGIN FORM-->
						  <?php            
                                $attributes = array('name'=>'signup2','id'=>'signup2','class'=>'form-vertical login-form');
                                echo form_open('home/index/',$attributes);
                            ?> 
							  <div class="form-group">
								<div class="controls text-center margin-bottom-20">
									<button type="submit" class="btn blue large text-upper "> continue </button>
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
