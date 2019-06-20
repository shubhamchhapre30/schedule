<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css?Ver<?php echo VERSION;?>" />

<!-- Content begins -->
<div id="content" class="page-content">
    <!-- Main content -->
    <div class="container-fluid admin-list">
	<div class="row">
					<div class="col-md-12">
						<h3 class="page-title">
							EmailTemplate							
						</h3>
						
					</div>
		</div>
        		<?php  
		if($error != "") {
			
			if($error == 'insert') {?>
			<div class="alert alert-success">
					<button data-dismiss="alert" class="close"></button>
						<?php echo UPDATE_RECORD;?>.
				</div>
			<?php }
		
			if($error != "insert"){	?>
				<div class="alert alert-danger">
					<button data-dismiss="alert" class="close"></button>
						<?php echo $error;?>
			</div>	
		<?php	}
		}
	?>		
                  <div class="row">
					<div class="col-md-12">
						<!-- BEGIN SAMPLE FORM PORTLET-->   
						<div class="portlet box blue tabbable">
						
                   
		
            <!--<form id="usualValidate" class="main" method="post" action="index.html">-->
			
                   
                        <div class="portlet-title">
								<div class="caption">
									<i class="fa fa-reorder"></i>
									<span class="hidden-480"><?php if($EmailTemplate_id ==""){ echo 'Add Page'; } else { echo 'Edit Page'; }?></span>
								</div>
							</div>
                            <div class="portlet-body form">
								<div class="tabbable portlet-tabs">
									
                       <div class="tab-content" style="margin:0 !important">
										<div id="portlet_tab1" class="tab-pane active">
											<!-- BEGIN FORM-->
											
			<?php
				$attributes = array('id'=>'admin','name'=>'admin','class'=>'form-horizontal');
				echo form_open('EmailTemplate/'.$actionPage,$attributes);
			  ?>
                <div class="form-group">
					<label class="control-label col-md-2">Subject</label>
                          <div class="controls"> 
							<input type="text" name="subject" class="m-wrap medium" id="subject" value="<?php echo htmlspecialchars($subject); ?>"/>
						</div>
                        </div> 
               <div class="form-group ">
					<label class="control-label col-md-2">From Address</label>
                          <div class="controls"> 
							<input type="text" name="from_address" class="m-wrap medium" id="from_address" value="<?php echo $from_address; ?>"/>
						</div>
                        </div>         
				<div class="form-group ">
					<label class="control-label col-md-2">Reply Address</label>
                          <div class="controls"> 
							<input type="text" name="reply_address" class="m-wrap medium" id="reply_address" value="<?php echo $reply_address; ?>"/>
						</div>
                        </div>
                        <div class="form-group" style="<?php if($sandgrid_id) {} else echo 'display:none';?>">
					<label class="control-label col-md-2">Sendgrid ID</label>
                          <div class="controls" style="padding-top:10px;"> 
						<?php echo $sandgrid_id; ?>	
						</div>
                        </div>
                                
                        <?php //echo $status; die; ?>
						 <div class="form-group">
					<label class="control-label col-md-2">message:<span class="required">*</span></label>
                                        <div class="controls col-md-10" style="padding-left: 0px"> 
                             <textarea id="message" cols="10" rows="10" name="message" class="col-md-12 ckeditor m-wrap required"><?php echo htmlspecialchars($message); ?></textarea>
                           </div>
                        </div>
				   
                         
                        
                  
				  
						<input type="hidden" name="EmailTemplate_id" id="EmailTemplate_id" value="<?php echo $EmailTemplate_id; ?>" />
				 	     <input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
						 <input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
						 <input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
						 <input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
						 
						 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
						 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
						 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
						 
					
						
						<div class="form-control form-change">
						
						<button class="btn blue" type="submit"><?php echo ($EmailTemplate_id!='')?'Update':'Submit' ?></button>
													
						<?php if($redirect_page == 'listEmailTemplate')
							{?>
							<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>EmailTemplate/<?php echo $redirect_page.'/'.$limit.'/'.$offset?>'" />
							<?php }else
							{?>
							<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>EmailTemplate/<?php echo $redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset?>'" />
							
							<?php }?>
						
						</div>
					
					
					  
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>  
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/js/select2.min.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/ckeditor/ckeditor.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.input-ip-address-control-1.0.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-tags-input/jquery.tagsinput.min.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>   
 <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js?Ver<?php echo VERSION;?>"></script>
 	
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/jquery.validate.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/js/additional-methods.min.js?Ver<?php echo VERSION;?>"></script>
<!-- <script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-components.js?Ver<?php echo VERSION;?>"></script> -->
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-validation.js?Ver<?php echo VERSION;?>"></script>	    

<script>
		jQuery(document).ready(function() { 
			
			
			$('input[name=Document_type]').click(function(){
				if(this.id=='pp'){
					($('#fileDiv').is(':hidden'))?  $('#tempDiv').slideUp('normal',function(){ $('#fileDiv').slideDown() }):'';
				}else{
					($('#tempDiv').is(':hidden'))?  $('#fileDiv').slideUp('normal',function(){  $('#tempDiv').slideDown() }):'';
				}
			});
			      
		         var form1 = $('#admin');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                   
                   subject: {required: true},
                    from_address: {required: true},
                    reply_address: {required: true},
                    message: {required: true},
                    
                    
                },

                /*
                invalidHandler: function (event, validator) { //display error alert on form submit              
                                    success1.hide();
                                    error1.show();
                                    App.scrollTo(error1, -200);
                                },*/
                

                

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $("button[type=submit]").prop("disabled",true);
                    form.submit();
                }
            });
            
   });        
	</script>
<!-- Content ends -->    
