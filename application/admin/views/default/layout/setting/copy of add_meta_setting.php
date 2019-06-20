<!-- Content begins -->
<div id="content">
    <!-- Main content -->
    <div class="wrapper">
	
        <div class="fluid">
		
		<?php  
		if($error != "") {
			
			if($error == 'insert') {
				//echo '<div class="nNote nSuccess"><p>Record has been updated Successfully.</p></div>';
			}
		
			if($error != "insert"){	
				echo '<div class="nNote nSuccess"><p>'.$error.'</p></div>';	
			}
		}
	?>		
            <!--<form id="usualValidate" class="main" method="post" action="index.html">-->
			 <?php
				$attributes = array('name'=>'frm_meta_setting');
				echo form_open('meta_setting/add_meta_setting',$attributes);
			  ?>	
                <fieldset>
                    <div class="widget">
                        <div class="whead"><h6>Meta Setting</h6><div class="clear"></div></div>
                        <div class="formRow">
                            <div class="grid3"><label>Title</label></div>
                            <div class="grid9"> <input type="text" name="title" id="title" value="<?php echo $title; ?>"/>
							</div><div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <div class="grid3"><label>Meta Keyword</label></div>
                            <div class="grid9"> <input type="text" name="meta_keyword" id="meta_keyword" value="<?php echo $meta_keyword; ?>" />
							</div><div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <div class="grid3"><label>Meta Description </label></div>
							<div class="grid9">
							 <textarea name="meta_description" cols="" rows="" id="meta_description"><?php echo $meta_description; ?></textarea>
							</div><div class="clear"></div>
                        </div>

               	
												
                                                											
						<input type="hidden" name="meta_setting_id" id="meta_setting_id" value="<?php echo $meta_setting_id; ?>" />
				 		
						<?php if($meta_setting_id==""){ ?>
						<div class="formRow">
						<input type="submit" name="submit" value="Submit" class="buttonM bBlack" />
						
						
						<div class="clear"></div></div>
					<?php }else { ?>
						<div class="formRow">
						<input type="submit" name="submit" value="Update" class="buttonM bBlack" />
						
						
						<div class="clear"></div></div>
					<?php } ?>
					
					   <div class="clear"></div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<!-- Content ends -->    