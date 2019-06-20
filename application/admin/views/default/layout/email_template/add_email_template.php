<!-- Content begins -->
<div id="content">
    <!-- Main content -->
    <div class="wrapper">
	
        <div class="fluid">
		<?php if($error!=''){ ?>
				<div class="nNote nFailure"><p><?php echo $error;?></p></div>
		<?php }?>		
            <?php
					$attributes = array('name'=>'frm_email_template');
					echo form_open('email_template/add_email_template',$attributes);
			  ?>
			     <fieldset>
                    <div class="widget">
                        <div class="whead"><h6>Editing in'<?php echo $task;?>' Template </h6><div class="clear"></div></div>
                        <div class="formRow">
                            <div class="grid3"><label>From Address :<span class="req">*</span></label></div>
                            <div class="grid9"> 
							<input type="text" name="from_address" id="from_address" value="<?php echo $from_address; ?>" class="required"/>
							</div><div class="clear"></div>
                        </div>
						 <div class="formRow">
                            <div class="grid3"><label>Reply Address: <span class="req">*</span></label></div>
                            <div class="grid9">
							<input type="text" name="reply_address" id="reply_address" value="<?php echo $reply_address; ?>" class="required"/>
							</div><div class="clear"></div>
                        </div>
						
						<div class="formRow">
                            <div class="grid3"><label>Subject :<span class="req">*</span></label></div>
                            <div class="grid9"> 
							<input type="text" name="subject" id="subject" value="<?php echo $subject; ?>" class="required"/>
							</div><div class="clear"></div>
                        </div>
						
						<div class="formRow">
                            <div class="grid3"><label>Message :<span class="req">*</span></label></div>
                            <div class="grid9"> 
							<textarea class="textstyle required" name="message" cols="" rows="10" id="message"><?php echo $message; ?></textarea>
							</div><div class="clear"></div>
                        </div>
						
						<input type="hidden" name="email_template_id" id="email_template_id" value="<?php echo $email_template_id; ?>" />
						
						<?php if($email_template_id=""){ ?>
						<div class="formRow">
						<input type="submit" name="submit" value="Submit" class="buttonM bBlack" />
						<input type="button" name="Cancel" value="Cancel" class="buttonM bRed" onClick="location.href='<?php echo base_url(); ?>email_template/list_email_template'" />
						
						<div class="clear"></div></div>
					<?php }else { ?>
						<div class="formRow">
						<input type="submit" name="submit" value="Update" class="buttonM bBlack" />
						<input type="button" name="Cancel" value="Cancel" class="buttonM bRed" onClick="location.href='<?php echo base_url(); ?>email_template/list_email_template'" />
						<div class="clear"></div></div>
					<?php } ?>
					
					   <div class="clear"></div>
					  
					  	 <table border="0" cellpadding="2" cellspacing="2" style="margin-left:10px;">
               
               <tr><td align="left" valign="middle" height="70" colspan="3" style="font-size:18px; font-weight:bold;">Email Tag<br />
<span style="font-size:12px; font-weight:normal;">(copy paste the tags with braces into the message part)</span> </td></tr>

               <tr>
               <td align="left" valign="top" style="font-weight:bold;">Welcome Email</td>
               <td align="center" valign="top">:</td>
               <td align="left" valign="top">{user_name}, {email}</td>
               </tr>

               <tr>
               <td align="left" valign="top" style="font-weight:bold;">New User Join</td>
               <td align="center" valign="top">:</td>
               <td align="left" valign="top">{user_name}, {email}, {password}, {login_link}</td>
               </tr>
               
               <tr>
               <td align="left" valign="top" style="font-weight:bold;">Forgot Password</td>
               <td align="center" valign="top">:</td>
               <td align="left" valign="top">{user_name}, {email}, {password}, {login_link}</td>
               </tr>

              <tr>
               <td align="left" valign="top" style="font-weight:bold;">Other HTML Tags</td>
               <td align="center" valign="top">:</td>
               <td align="left" valign="top">{break}</td>
               </tr>

               </table>
			   			
                    </div>
					
                </fieldset>
            </form>
        </div>
    </div>
</div>
<!-- Content ends -->    