<script src="<?php echo base_url().getThemeName(); ?>/js/jquery.form.js?Ver<?php echo VERSION;?>"></script>
<script> 
        // wait for the DOM to be loaded 
        $(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            $('#theForm').ajaxForm({
			dataType:'json',
			beforeSend:function(){ $('#loader').fadeIn();},
			success:function(data){
				//alert(data.total);
			$('#response').html(data.total+' row inserted');	
			},
			complete:function(){ $('#loader').fadeOut();},
			}); 
        }); 
    </script> 
<div class="fluid">

		
<form id="theForm" action="<?php echo site_url('admin/import_data') ?>" name="theForm" method="POST" enctype="multipart/form-data">
<fieldset>
<div class="widget">
<div class="formRow">
					<div class="grid3" style="width:23%;"><label>Import Csv:<span class="req">*</span></label></div>
					<div class="grid9">
						
						<input type="file" name="file_up_img" id="file_up_img" class="required"/>
						<label for="file_up_img" generated="true" class="error" style="display:none !important">This field is required.</label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
<div class="formRow">
						<input type="submit" name="add" value="Submit" id="add" class="buttonH bGreen" />
</div>		
				<div class="clear"></div>		
</div>
</fieldset>	
</form>
</div>	
<img src="<?php echo base_url().getThemeName(); ?>/images/elements/loaders/7.gif" id="loader" style="display:none;"/>
<div id="response"></div>
				
</div>


