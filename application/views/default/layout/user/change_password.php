<?php $theme_url = base_url().getThemeName();  ?>

<form name="frm_change_password" id="frm_change_password" method="post">
    <div class="form-group ">
        <label class="control-label">Current Password<span class="required">*</span></label>
        <input type="password" class="form-control " name="old_password" id="old_password" value="<?php echo $old_password; ?>" autocomplete="off"/><span class="show_password show_old_pass"><img src="<?php echo $theme_url; ?>/img/eye.png"/></span> 
    </div>
    <div class="form-group">
        <label class="control-label">New Password<span class="required">*</span></label>
        <input type="password" class="form-control" name="password" id="password" value="<?php echo $password; ?>" autocomplete="off" /><span class="show_password display_password" ><img src="<?php echo $theme_url; ?>/img/eye.png"/></span>
    </div>
    <div class="form-group">
        <label class="control-label">Re-type New Password<span class="required">*</span></label>
        <input type="password" class="form-control" name="confirm_password" id="confirm_password" value="<?php echo $confirm_password; ?>" autocomplete="off"  />
    </div>
    <div class="margin-top-10">
        <button class="btn sm btn-common-blue"  type="submit"> Change Password </button>
        <button class="btn sm default" id='clear_password_fields' type="button"> Cancel </button>
    </div>
</form>    
	
<script>
$(document).ready(function(){
            jQuery.extend(jQuery.validator.messages, {
                required: "is required.",
                email: "must be valid.",
            });
        });
</script>