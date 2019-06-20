<script>
	$(document).ready(function(){
		$('input[type=checkbox]').attr('disabled',true);
		$('input[type=checkbox]:checked').attr('disabled',false);
		$('.view').attr('disabled',false);
		$('.view:checked').each(function(){
			$(this).parent().parent().parent().parent().find('input[type=checkbox]').attr('disabled',false);
		});
		$.uniform.update();
		$('#st input[type=radio]').click(function(){
			if($(this).val()==0)
			{
				$('input[type=checkbox]').attr('disabled',false);
				$('#ac input[type=checkbox]').attr('checked',true);
				$.uniform.update();
			}else{
				$('#ac input[type=checkbox]').attr('checked',false);
				$('#ac input[type=checkbox]').attr('disabled',true);
				$('.view').attr('disabled',false);
				$('.view').attr('checked',true);
				$.uniform.update();
			}
		});
		
		$('.view').change(function(){
			if($(this).is(':checked')==true)
			{
				
				$(this).parent().parent().parent().parent().find('input[type=checkbox]').attr('disabled',false);
				$.uniform.update();
			}else{
				$(this).parent().parent().parent().parent().find('input[type=checkbox]').attr('checked',false);
				$(this).parent().parent().parent().parent().find('input[type=checkbox]').attr('disabled',true);
				$(this).attr('disabled',false);
				$.uniform.update();
			}
			
		});
	});
</script>
<div class="page-content" style="min-height:728px !important">
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <h3 class="page-title">Assign Rights</h3>
      <!--<ul class="breadcrumb">
        <li> <i class="icon-home"></i> <a href="#">Home</a> <span class="icon-angle-right"></span> </li>
        <li> <a href="#">User Master Detail</a>
      </li></ul>-->
    </div>
  </div>
    <div class="row-fluid">
    <div class="span12">
    
      <?php  
		if($error != "") {
			
			if($error == 'insert') {
				echo '<div class="alert alert-success ">Record has been updated Successfully.</div>';
			}
		
			if($error != "insert"){	
				echo '<div class="alert alert-error ">'.$error.'</div>';	
			}
		}
	?>		
      <div class="portlet box green ">
     
      
        <div class="portlet-title">
          <div class="caption"> <span class="hidden-480"></span> </div>
          <div class="actions"> &nbsp;</div>
        </div>
        <div class="portlet-body form">
          <div class="portlet-tabs">
            <div class="row-fluid margin-top-10">
             
              
              <?php
				$attributes = array('id'=>'usualValidate','name'=>'frm_addpages','class'=>'form-horizontal uValidate');
				echo form_open('admin/assignRights/'.$admin_id,$attributes);  ?>
                <div class="row-fluid">
                
                  
                    
                    
                     <?php if($all_rights!=''){?>
                     <div class="span9">
                      <div class="control-group">
										<label class="control-label">Assign Rights</label>
										<div class="controls" id="st">
											<label class="radio">
											<input type="radio" value="0" name="setRights">All Rights
											</label>
											<label class="radio">
											<input type="radio" value="1" name="setRights">Only View
											</label>
										</div>
						</div>
						</div>
					<div class="row-fluid">
					<div class="span9" id="ac">
					 <?php foreach($all_rights as $ar){
					
					 if(in_array($ar->rights_id,$ad_r)){
					//print_r($rid[$ar->rights_id]);
					  ?>
                     <div class="control-group">
										<label class="control-label"><?php echo ucfirst(str_replace('_',' ',$ar->rights_name)); ?></label>
										<div class="controls">
                                        <input type="hidden" name="right_name[]" value="<?php echo $ar->rights_id ?>">
											<label class="checkbox">
											<input type="checkbox" value="1" name="add[<?php echo $ar->rights_id ?>]" <?php echo ($rid[$ar->rights_id]->add==1)?'checked':'' ?>> Add
											</label>
											<label class="checkbox">
											<input type="checkbox" value="1" name="update[<?php echo $ar->rights_id ?>]" <?php echo ($rid[$ar->rights_id]->update==1)?'checked':'' ?>> Update
											</label>
                                            <label class="checkbox">
											<input type="checkbox" value="1" name="delete[<?php echo $ar->rights_id ?>]" <?php echo ($rid[$ar->rights_id]->delete==1)?'checked':'' ?>> Delete
											</label>
											<label class="checkbox">
											<input type="checkbox" value="1" name="view[<?php echo $ar->rights_id ?>]" <?php echo ($rid[$ar->rights_id]->view==1)?'checked':'' ?> class="view"> View
											</label>
										</div>
									</div>
                     <?php }else{ ?>
                      <div class="control-group">
										<label class="control-label"><?php echo ucfirst(str_replace('_',' ',$ar->rights_name)); ?></label>
										<div class="controls">
                                        <input type="hidden" name="right_name[]" value="<?php echo $ar->rights_id ?>">
											<label class="checkbox">
											<input type="checkbox" value="1" name="add[<?php echo $ar->rights_id ?>]"> Add
											</label>
											<label class="checkbox">
											<input type="checkbox" value="1" name="update[<?php echo $ar->rights_id ?>]"> Update
											</label>
                                            <label class="checkbox">
											<input type="checkbox" value="1" name="delete[<?php echo $ar->rights_id ?>]"> Delete
											</label>
											<label class="checkbox">
											<input type="checkbox" value="1" name="view[<?php echo $ar->rights_id ?>]" class="view"> View
											</label>
										</div>
									</div>
                    
                    <?php } } ?>
                     </div>
                  </div>
					<?php } ?>              
					  
                   
                </div>
                <div class="clear"></div>
                <div class="form-actions-box">
                         <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $admin_id ?>">         
				 	    <input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
						 <input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
						 <input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>" />
													 
													 <input type="hidden" name="search_option" id="search_option" value="<?php echo $option; ?>" />
													 <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>"/>
													 <input type="hidden" name="search_keyword" id="search_keyword" value="<?php echo $keyword; ?>" />
                  <button class="btn green" id="btnsubmit" name="btnsubmit" type="submit"><i class="halflings-icon white folder-close"></i>Save</button>
                  <?php if($redirect_page == 'list_admin')
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>admin/<?php echo $redirect_page.'/'.$limit.'/'.$offset?>'" />
														<?php }else
														{?>
														<input type="button" name="Cancel" value="Cancel" class="btn" onClick="location.href='<?php echo base_url(); ?>admin/<?php echo $redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset?>'" />
														
														<?php }?>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>