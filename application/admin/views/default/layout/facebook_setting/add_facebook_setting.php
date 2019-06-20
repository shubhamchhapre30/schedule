<div id="content">
<div class="cantnet_top_sed">
			<div class="contentTop">
				<span class="pageTitle"><span class="icon-link"></span>Facebook Setting</span>
			<div class="clear"></div>
			</div>
		</div>
    <!-- Main content -->
    <div class="wrapper">
	<script type="text/javascript">
	$(document).ready(function() {
	
	jQuery.validator.addMethod("nowhitespace", function(value, element) {
    return this.optional(element) || /^\S+$/i.test(value);
	}, "white Space is not allowed."); 
			
	jQuery.validator.addMethod("lettersonly", function(value, element) {
	return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Enter Only Alpha Charcter."); 
	$("#facebook_setting").validate({
	//set the rules for the fields
	rules: {			
		             facebook_application_id: {required: true},			
		             facebook_api_key: {required: true},			
		             facebook_secret_key: {required: true},			
		             facebook_login_enable: {required: true},
					 facebook_url:{url:true}
				
				
				
			},
			});
	});
	
	</script>
	<div class="fluid">
	      <?php   
		  
		  if($msg != "") {
			
			if($msg == "update") {
				echo "<div class='nNote nSuccess'><p>".FACEBOOK_SETTING_UPDATE."</p></div>";
			}
		
			
		}
		 
		if($error != "") {
			
			if($error == "insert") {
				echo "<div class='nNote nSuccess'><p>".FACEBOOK_SETTING_UPDATE."</p></div>";
			}
		
			if($error != "insert"){	
				echo "<div class='nNote nFailure'>".$error."</div>";	
			}
		}
	?>		
		
		<?php // Change the css classes to suit your needs    

$attributes = array('class' => 'main', 'id' => 'facebook_setting','name'=>'facebook_setting');
echo form_open_multipart('facebook_setting/add_facebook_setting', $attributes); ?>
<div class="widget">
<div class="whead"><h6><?php if($facebook_setting_id==""){ echo 'Add Facebook setting'; } else { echo 'Edit Facebook setting'; }?> </h6><div class="clear"></div></div>
                       
<div class='formRow'><div class='grid3' style="width: 13%;">
        <label for="facebook_application_id">Facebook Application  Id<span class="req">*</span></label></div>
        <div class="grid9">
		<input id="facebook_application_id" type="text" name="facebook_application_id" value="<?php echo $facebook_application_id; ?>"   />
		
</div><div class="clear"></div>
 </div>
<div class='formRow'><div class='grid3' style="width: 13%;">
        <label for="facebook_api_key">Facebook Api Key<span class="req">*</span></label></div>
        <div class="grid9">
		<input id="facebook_api_key" type="text" name="facebook_api_key" value="<?php echo $facebook_api_key; ?>"   />
		
</div><div class="clear"></div>
 </div>
<div class='formRow'><div class='grid3' style="width: 13%;">
        <label for="facebook_secret_key">Facebook Secret Key<span class="req">*</span></label></div>
        <div class="grid9">
		<input id="facebook_secret_key" type="text" name="facebook_secret_key" value="<?php echo $facebook_secret_key; ?>"   />
		
</div><div class="clear"></div>
 </div>
<div class='formRow'><div class='grid3' style="width: 13%;">
        <label for="facebook_login_enable">Facebook Login Enable<span class="req">*</span></label></div> <div class='grid9'> 
      
						 <select name="facebook_login_enable" id="facebook_login_enable">
						<option value="">--SELECT--</option>
						<option value="1" <?php if($facebook_login_enable==1){ ?>  selected="selected"<?php } ?>>Enable</option>
						<option value="0" <?php if($facebook_login_enable==0){ ?>  selected="selected"<?php } ?>>Disable</option>
						</select>
				</div><div class="clear"></div> </div>                                          
                        
<div class='formRow'><div class='grid3' style="width: 13%;">
        <label for="facebook_url">Facebook Url</label></div>
        <div class="grid9">
		<input id="facebook_url" type="text" name="facebook_url" value="<?php echo $facebook_url; ?>"   />
		
</div><div class="clear"></div>
 </div>
<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
<input type="hidden" name="facebook_setting_id" id="facebook_setting_id" value="<?php echo $facebook_setting_id; ?>" />

	<?php if($facebook_setting_id==""){ ?>
						<div class="formRow">
						<input type="submit" name="submit" value="Submit" class="buttonS bBlack" />
						<!--<input type="button" name="Cancel" value="Cancel" class="buttonS bRed" onClick="document.location.href='<?php //echo base_url(); ?>facebook_setting/add_facebook_setting'"/>-->
						
						<div class="clear"></div></div>
					<?php }else { ?>
						<div class="formRow">
						<input type="submit" name="submit" value="Update" class="buttonS bBlack" />
						<!--<input type="button" name="Cancel" value="Cancel" class="buttonS bRed" onClick="document.location.href='<?php //echo base_url(); ?>facebook_setting/add_facebook_setting'" />-->
						<div class="clear"></div></div>
					<?php } ?>
        <?php //echo form_submit( 'submit', 'Submit'); ?>
   <div class="clear"></div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

 