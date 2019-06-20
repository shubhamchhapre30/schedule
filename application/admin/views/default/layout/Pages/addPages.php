<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css?Ver<?php echo VERSION;?>" />

<!-- Content begins -->
<div id="content" class="page-content">
    <!-- Main content -->
    <div class="container-fluid">
	<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							Pages							
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
				<div class="alert alert-error">
					<button data-dismiss="alert" class="close"></button>
						<?php echo $error;?>
			</div>	
		<?php	}
		}
	?>		
                  <div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE FORM PORTLET-->   
						<div class="portlet box blue tabbable">
						
                   
		
            <!--<form id="usualValidate" class="main" method="post" action="index.html">-->
			
                   
                        <div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>
									<span class="hidden-480"><?php if($Pages_id ==""){ echo 'Add Page'; } else { echo 'Edit Page'; }?></span>
								</div>
							</div>
                            <div class="portlet-body form">
								<div class="tabbable portlet-tabs">
									
                       <div class="tab-content" style="margin:0 !important">
										<div id="portlet_tab1" class="tab-pane active">
											<!-- BEGIN FORM-->
			<?php
				$attributes = array('id'=>'admin','name'=>'admin','class'=>'form-horizontal');
				echo form_open('Pages/'.$actionPage,$attributes);
			  ?>
                <div class="control-group">
					<label class="control-label">Pages Title:<span class="m-wrap medium ">*</span></label>
                            <div class="controls"> <input type="text" name="pages_title" id="pages_title" value="<?php echo $pages_title; ?>" class="m-wrap small"/>
							</div>
                        </div>
                        

            <?php if($Pages_id!='') { ?>

 <div class="control-group">
					<label class="control-label">Pages URL:</label>
    <div class="controls"> 
     <a href="<?php echo front_base_url().'home/content/'.$slug.'/'.$Pages_id;?>" target="_balnk"><?php echo front_base_url().'home/content/'.$slug.'/'.$Pages_id;?></a>
    </div><div class="clear"></div>
</div>
        
             <?php }?>                        
                        <?php //echo $active; die; ?>
						 <div class="control-group">
					<label class="control-label">Description:<span class="required">*</span></label>
                           <div class="controls"> 
                             <textarea id="description" cols="10" rows="10" name="description" class="span12 ckeditor m-wrap required"><?php echo $description; ?></textarea>
                           </div>
                        </div>
				<?php if($Pages_id!='') { ?>		
                         <div class="control-group">
					<label class="control-label">Slug:</label>
                          <div class="controls"> 
							<input type="text" name="slug" class="m-wrap medium" id="slug" value="<?php echo $slug; ?>" readonly	/>
						</div>
                        </div>
                    <?php } ?>    
                         <div class="control-group">
					<label class="control-label">Meta Keyword:</label>
                          <div class="controls"> 
							<input type="text" name="meta_keyword" class="m-wrap medium" id="meta_keyword" value="<?php echo $meta_keyword; ?>"/>
						</div>
                        </div>
                        
                         <div class="control-group">
					<label class="control-label">Meta Description:</label>
                            <div class="controls"> 
							
							<textarea name="meta_description" id="meta_description" class="m-wrap span6"><?php echo $meta_description; ?></textarea>
						</div>
                        </div>
				  <!--
				  <div class="control-group">
									  <label class="control-label">Status:</label>
											<div class="controls"> 
																				   <select name="active" id="active" class="small m-wrap">
													  <option value="Active" <?php if($active=='Active'){ echo "selected"; } ?>>Active</option>
													  <option value="Inactive" <?php if($active=='Inactive'){ echo "selected"; } ?>>Inactive</option>														
												</select>
											  </div>
										  </div>-->
				  
						<input type="hidden" name="Pages_id" id="Pages_id" value="<?php echo $Pages_id; ?>" />
				 	     <input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
						 <input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
						 <input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
						 <input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
						 
						 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
						 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
						 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
						 
					
						
						<div class="form-actions">
						
						<button class="btn blue" type="submit"><?php echo ($Pages_id!='')?'Update':'Submit' ?></button>
													
						<?php if($redirect_page == 'listPages')
							{?>
							<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>Pages/<?php echo $redirect_page.'/'.$limit.'/'.$offset?>'" />
							<?php }else
							{?>
							<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>Pages/<?php echo $redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset?>'" />
							
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
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/select2.min.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/ckeditor/ckeditor.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.input-ip-address-control-1.0.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-switch/static/js/bootstrap-switch.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>
<script src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-tags-input/jquery.tagsinput.min.js?Ver<?php echo VERSION;?>" type="text/javascript" ></script>   
 <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js?Ver<?php echo VERSION;?>"></script>
 	
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/jquery.validate.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-validation/dist/additional-methods.min.js?Ver<?php echo VERSION;?>"></script>
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
            var error1 = $('.alert-error', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    meta_description: {required: true},
                   pages_title: {required: true},
                    description: {required: true},
                    active: {required: true },
                    
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