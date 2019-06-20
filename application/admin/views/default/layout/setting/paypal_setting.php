<script type="text/javascript">
	$(document).ready(function() {
	
	jQuery.validator.addMethod("nowhitespace", function(value, element) {
    return this.optional(element) || /^\S+$/i.test(value);
	}, "white Space is not allowed."); 
			
	jQuery.validator.addMethod("lettersonly", function(value, element) {
	return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Space is not allowed."); 
	$("#frm_paypal").validate({
	//set the rules for the fields
	rules: {			
		             site_status: {required: true},			
		             
		             paypal_email: {required: true}
						
		       
			},
			});
	});
	
	</script>
<!-- Content begins -->
<div id="content">
    <!-- Main content -->
    <div class="wrapper">
	
        <div class="fluid">
		
		<?php  
		if($error != "") {
			
			if($error == 'update') {
				echo '<div class="nNote nSuccess"><p>Paypal Setting has been updated Successfully.</p></div>';
			}
		
			if($error != "update"){	
				echo '<div class="nNote nFailure"><p>'.$error.'</p></div>';	
			}
		}
	?>		
            <!--<form id="usualValidate" class="main" method="post" action="index.html">-->
			<?php
				$attributes = array('id'=>'frm_paypal','name'=>'frm_paypal','class'=>'main');
				echo form_open('site_setting/paypal',$attributes);
			  ?>
                <fieldset>
                    <div class="widget">
                        <div class="whead"><h6>Paypal Setting</h6><div class="clear"></div></div>
                       
                       <div class="formRow">
                            <div class="grid3"><label>Paypal Status<span class="req">*</span></label></div>
                            <div class="grid9">  
							 <select  id="site_status" name="site_status">
				  	 	     <option selected="" value="sandbox" <?php if($site_status=="sandbox"){ ?> selected="selected"<?php }?>>sand box</option>
						     <option value="live" <?php if($site_status=="live"){ ?> selected="selected"<?php }?>>live</option>
				           </select> 
							</div><div class="clear"></div>
                        </div>

                        

                    
						
						<div class="formRow">
                            <div class="grid3"><label>Paypal Email Id<span class="req">*</span></label></div>
                            <div class="grid9"> <input type="text" name="paypal_email" id="paypal_email" value="<?php echo $paypal_email; ?>"/>
							</div><div class="clear"></div>
                        </div>
						
						
						   <p></p>            											
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
				 		
						<div class="formRow">
						<input type="submit" name="submit" value="Submit" class="buttonS bGreen" />
						<input type="button" name="Cancel" value="Cancel" class="buttonS bRed" onClick="location.href='<?php echo base_url(); ?>admin/list_admin'" />
						<div class="clear"></div></div>
					
					   <div class="clear"></div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<!-- Content ends -->    