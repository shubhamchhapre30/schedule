<script>
	$(document).ready(function(){
		
		var form1 = $('#frm_add_swimlane');
        var error1 = $('.alert-error', form1);
        var success1 = $('.alert-success', form1);
        
		$.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "Please enter only letters.");
		    
        $('#frm_add_swimlane').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
				
			   swimlanes_name : {
               		required : true,
               		alpha : true
               },
               swimlanes_desc : {
               		required : true
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
<div class="container-fluid" style="padding-left:20px;padding-right:20px;">
      <div class="mainpage-container">
	  		<div class="user-block">
       			 <div class="row">
			<div class="col-md-12 ">
				<div class="usertabs">
				<div class="tabbable tabbable-custom">
					<ul class="nav nav-tabs">
						<li ><a href="<?php echo site_url('user/my_settings');?>" >General</a></li>
						<li><a  href="<?php echo site_url('user/default_calender');?>" >Default calendar</a></li>
						<li><a href="<?php echo site_url('user/colors');?>">Colour</a></li>
						<li class="active"><a  href="#">Swimlanes(Kanban)</a></li>
						<li><a href="<?php echo site_url('user/change_password'); ?>">Change Password</a></li>
					 </ul>
					<div class="tab-content">
						
						<div class="tab-pane active"  id="tab_4">
							<div class="portlet box blue">
								<div class="portlet-title">
									<div class="caption"><?php if($swimlanes_id){ echo 'Edit'; }else{ echo 'Add'; }?> Swimlane</div>
								 </div>
								 <div class="portlet-body  form flip-scroll">
									 <?php if($error){
											?>
											<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
											<?php
										}?>
									  <div class="form-horizontal">
											 <?php $attributes = array('name'=>'frm_add_swimlane', 'id'=>'frm_add_swimlane');
											 	echo form_open_multipart('user/addSwimlane/'.base64_encode($swimlanes_id), $attributes); ?>
											 		<div class="form-group">
														<label class="control-label">Swimlane Name :<span class="required">*</span></label>
														<div class="controls">
															<input type="text" name="swimlanes_name" id="swimlanes_name" value="<?php echo $swimlanes_name; ?>" placeholder=" " class="m-wrap large" />
														</div>
													</div>
														
													<div class="form-group">
														<label class="control-label">Description :<span class="required">*</span></label>
														<div class="controls">
															<textarea name="swimlanes_desc" id="swimlanes_desc" class="large m-wrap"><?php echo $swimlanes_desc; ?></textarea>
														</div>
													</div>
														
														<div class="form-actions">
															<input type="hidden" name="swimlanes_id" id="swimlanes_id" value="<?php echo base64_encode($swimlanes_id); ?>" />
															<button type="submit" class="btn blue txtbold"><i class="stripicon icosave"></i> Save</button>
															<button type="button" class="btn red txtbold" onclick="location.href='<?php echo site_url('user/swimlanes'); ?>'"> <i class="stripicon icocancel"></i>  Cancel</button>
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
