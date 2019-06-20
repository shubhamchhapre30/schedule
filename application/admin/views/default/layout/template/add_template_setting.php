<div id="content" align="center">

 	<?php if($error!=''){ ?>
		<div class="column full">
			<span class="message information"><strong><?php echo $error;?></strong></span>
		</div>
    <?php }?>

	<div align="left" class="column half">
		<div class="box">
			<h2 class="box-header">Edit Template Setting </h2> 
			<div class="box-content">	
          
			  <?php
				$attributes = array('name'=>'frm_template_setting','enctype'=>'multipart/form-data');
				echo form_open('template_setting/add_template_setting/'.$template_id,$attributes);
			  ?>		
              
              
               <label class="form-label">Template Type </label>
				<?php if($is_admin_template==1) { echo "Admin"; } else { echo "Front "; }?>
                 <br />
<br />

                 			
				  <label class="form-label">Template Name </label> 
				  <input type="text" name="template_name" id="template_name" value="<?php echo $template_name; ?>" class="form-field width40" readonly="readonly"/>
							
                            
                    
                 
                 		
				  <label class="form-label">Template Logo</label> 
				  <input type="file" name="template_logo" id="template_logo" class="form-field width40"/>
				  <input type="hidden" name="prev_logo_image" id="prev_logo_image" class="form-field width40" value="<?php echo $template_logo;?>" />
				  
				  <label class="form-label">Template Logo Hover </label>
				  <input type="file" name="template_logo_hover" id="template_logo_hover" class="form-field width40"/> 
				  <input type="hidden" name="prev_logo_hover_image" id="prev_logo_hover_image" class="form-field width40" value="<?php echo $template_logo_hover;?>" />
                  
                  <label class="form-label">Active Template </label>
				  <select name="active_template" id="active_template" class="form-field settingselectbox required" >
						<option value="0" <?php if($active_template  == 0){ ?> selected="selected" <?php } ?>> No</option>
						<option value="1" <?php if($active_template  == 1){ ?> selected="selected" <?php } ?>> Yes</option>	 	  				 												
				  </select><br />
<br />

                  
                 <input type="hidden" name="template_name" id="template_name" value="<?php echo $template_name; ?>" />

				  <input type="hidden" name="template_id" id="template_id" value="<?php echo $template_id; ?>" />
				  <input type="submit" class="button themed" name="submit" value="Update" onclick=""/>
				  
			  </form>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>