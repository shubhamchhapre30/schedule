<!-- Content begins -->
<div id="content">
    <!-- Main content -->
    <div class="wrapper">
	
      <div class="fluid">

	<?php if($error!=''){ ?>
		<div class="nNote nSuccess">
			<p><?php echo $error;?></p>
		</div>
    <?php }?>
	
	
	
           		  <?php
				$attributes = array('name'=>'frm_email_setting');
				echo form_open('email_setting/add_email_setting',$attributes);
			  ?>     <fieldset>
                    <div class="widget">
                        <div class="whead"><h6>Email Setting</h6><div class="clear"></div></div>
                        <div class="formRow">
                            <div class="grid3"><label>Mailer<span class="req">*</span></label></div>
                            <div class="grid9"> 
							<select name="mailer" id="mailer" class="required" >
								<option value=""></option>
								<option value="mail" <?php if($mailer=='mail') { ?> selected="selected" <?php } ?> >PHP Mail</option>
								<option value="smtp" <?php if($mailer=='smtp') { ?> selected="selected" <?php } ?> >SMTP</option>
								<option value="sendmail" <?php if($mailer=='sendmail') { ?> selected="selected" <?php } ?> >sendmail</option>	
							</select>
							</div><div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <div class="grid3"><label>Send Mail Path</label></div>
                            <div class="grid9">
							<input type="text" name="sendmail_path" id="sendmail_path" value="<?php echo $sendmail_path; ?>" class="required"/>(if Mailer is sendmail)
							</div><div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <div class="grid3"><label>SMTP Port</label></div>
                            <div class="grid9"> 
							<input type="text" name="smtp_port" id="smtp_port" value="<?php echo $smtp_port; ?>"/>(465 or 25 or 587)
							</div><div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <div class="grid3"><label>SMTP Host</label></div>
                            <div class="grid9"> 
							<input type="text" name="smtp_host" id="smtp_host" value="<?php echo $smtp_host; ?>" readonly="readonly"/>(if smtp user is gmail then ssl://smtp.googlemail.com)
							</div><div class="clear"></div>
                        </div>

                     	<div class="formRow">
                            <div class="grid3"><label>SMTP Email</label></div>
                            <div class="grid9"> 
							 <input type="text" name="smtp_email" id="smtp_email" value="<?php echo $smtp_email; ?>"/>
							</div><div class="clear"></div>
                        </div>
						
						<div class="formRow">
                            <div class="grid3"><label>SMTP Password</label></div>
                            <div class="grid9"> 
							<input type="password" name="smtp_password" id="smtp_password" value="<?php echo $smtp_password; ?>" />
							</div><div class="clear"></div>
                        </div>
						
						 <input type="hidden" name="email_setting_id" id="email_setting_id" value="<?php echo $email_setting_id; ?>" />
						<div class="formRow">
						<input type="submit" name="submit" value="Update" class="buttonM bBlack" />
						
						
						<div class="clear"></div></div>
					
					   <div class="clear"></div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<!-- Content ends -->    