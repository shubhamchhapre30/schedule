<script>
	$(document).ready(function(){
		
		var form1 = $('#frm_add_colors');
        var error1 = $('.alert-error', form1);
        var success1 = $('.alert-success', form1);
        
        $.validator.addMethod("regex", function(value, element) {          
		    	return this.optional(element) || /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(value);
		   	}, "Please enter a valid hex colour code.");  
        
        $.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "Please enter only letters.");
		    
        $('#frm_add_colors').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
				
			   color_name : {
               		required : true,
               		alpha : true
               },
               name : {
               		required : true,
					   
               		remote: {
						url: "<?php echo site_url("user/chk_name");?>",
						type: "post",
						data: {
							color_name: function(){ return $("#name").val(); },
							color_id : function(){ return $("#color_id").val(); }
						}
					}
               },
               color_code : {
               		required : true,
               		remote: {
						url: "<?php echo site_url("user/chk_color_code");?>",
						type: "post",
						data: {
							color_code: function(){ return $("#color_code").val(); },
							color_id : function(){ return $("#color_id").val(); }
						}
					},
               		regex : true
               		
               },
               /*color_tooltip : {
               		required : true
               },*/
               status : {
               		required : true
               }
			},
			messages : {
				name : {
					required : "This field is required.",
					/*alpha : "Please enter only letters.",*/
					remote : "This colour name already exists."
				},
				color_code : {
					required : "This field is required.",
					remote : "This colour code already exists.",
					regex : "Please enter a valid hex colour code."
				}
			},
	        submitHandler: function (form) {
               success1.show();
               error1.hide();
               form.submit();
           }
           
        });
	});
</script>
<!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid" style="padding-left:20px;paddding-right:20px;">
      <div class="mainpage-container">
	  		<div class="user-block">
       			 <div class="row">
			<div class="col-md-12 ">
				<div class="usertabs">
				<div class="tabbable tabbable-custom">
					<ul class="nav nav-tabs">
						<li ><a href="<?php echo site_url('user/my_settings');?>" >General</a></li>
						<li><a  href="<?php echo site_url('user/default_calender');?>" >Default calendar</a></li>
						<li class="active"><a href="<?php echo site_url('user/colors');?>">Colour</a></li>
						<li ><a  href="<?php echo site_url('user/swimlanes');?>">Swimlanes(Kanban)</a></li>
						<li><a href="<?php echo site_url('user/change_password'); ?>">Change Password</a></li>
					 </ul>
					<div class="tab-content">
						
						<div class="tab-pane active"  id="tab_4">
							<div class="portlet box blue">
								<div class="portlet-title">
									<div class="caption"><?php if($color_id){ echo 'Edit'; }else{ echo 'Add'; }?> Colour</div>
								 </div>
								 <div class="portlet-body  form flip-scroll">
									 <?php if($error){
											?>
											<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
											<?php
										}?>
									  <div class="form-horizontal">
											 <?php $attributes = array('name'=>'frm_add_colors', 'id'=>'frm_add_colors');
											 	echo form_open_multipart('user/addColors/'.base64_encode($color_id), $attributes); ?>
											 		<div class="form-group">
														<label class="control-label">Name :</label>
														<div class="controls">
															<input type="text" name="name" id="name" class="large m-wrap" value="<?php echo $name; ?>" />
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label">Colour Name :<span class="required">*</span></label>
														<div class="controls">
															<input type="text" name="color_name" <?php if($color_id){ echo "readonly='readonly'"; } ?> id="color_name" value="<?php echo $color_name; ?>" placeholder=" " class="m-wrap large" />
														</div> 
													</div>
														
													<div class="form-group">
														<label class="control-label">Colour code :<span class="required">*</span></label>
														<div class="controls">
															<input type="text" name="color_code" <?php if($color_id){ echo "readonly='readonly'"; } ?> id="color_code" value="<?php echo $color_code; ?>" placeholder=" " class="m-wrap large" />
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label">Outside colour code :<span class="required">*</span></label>
														<div class="controls">
															<input type="text" name="outside_color_code" <?php if($color_id){ echo "readonly='readonly'"; } ?> id="outside_color_code" value="<?php echo $outside_color_code; ?>" class="m-wrap large" />
														</div>
													</div>
														
													<!--
													<div class="control-group">
																											<label class="control-label">Color tooltip :</label>
																											<div class="controls">
																												<input type="text" name="color_tooltip" id="color_tooltip" class="large m-wrap" value="<?php echo $color_tooltip; ?>" />
																											</div>
																										</div>-->
													
													
													<div class="form-group">
															<label class="control-label">Status :<span class="required">*</span></label>
															<div class="controls">
																<select class="large m-wrap" name="status" tabindex="1">
																	<option value="Inactive" <?php if($status == 'Inactive'){ echo 'selected="selected"'; }?>>Inactive</option>
																	<option value="Active" <?php if($status == 'Active'){ echo 'selected="selected"'; }?>>Active</option>
																</select>
															</div>
													</div>
														<div class="form-actions">
															
															<input type="hidden" name="color_id" id="color_id" value="<?php echo base64_encode($color_id); ?>" />
															<button type="submit" class="btn blue txtbold"><i class="stripicon icosave"></i> Save</button>
															<button type="button" class="btn red txtbold" onclick="location.href='<?php echo site_url('user/colors'); ?>'"> <i class="stripicon icocancel"></i>  Cancel</button>
														</div>
													</form>
										</div>
									
								  </div>
							 </div>
						</div> <!-- Tab 4 -->
					 </div>
				</div>
				</div>
			</div>
		</div>
			</div>
      </div>
    </div>
