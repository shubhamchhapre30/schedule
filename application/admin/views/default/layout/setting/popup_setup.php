<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css?Ver<?php echo VERSION;?>" />
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js?Ver<?php echo VERSION;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css?Ver<?php echo VERSION;?>" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css?Ver<?php echo VERSION;?>" />-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-wysihtml5/wysiwyg-color.css?Ver<?php echo VERSION;?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-wysihtml5.css?Ver<?php echo VERSION;?>"></link>

  
  <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/select2/js/select2.min.js?Ver<?php echo VERSION;?>"></script>
  <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js?Ver<?php echo VERSION;?>"></script>
  <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery.input-ip-address-control-1.0.min.js?Ver<?php echo VERSION;?>"></script>
  <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/jquery-multi-select/js/jquery.multi-select.js?Ver<?php echo VERSION;?>"></script>
  <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js?Ver<?php echo VERSION;?>"></script>
   <!--<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js?Ver<?php echo VERSION;?>"></script>-->
<script src="<?php echo base_url().getThemeName(); ?>/assets/scripts/form-components.js?Ver<?php echo VERSION;?>"></script>
<script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/wysihtml5-0.3.0.js?Ver<?php echo VERSION;?>"></script>
  <script type="text/javascript" src="<?php echo base_url().getThemeName(); ?>/assets/plugins/bootstrap-wysihtml5.js?Ver<?php echo VERSION;?>"></script>



<!-- END PAGE CONTAINER--> 

<script language="javascript">
	$(document).ready(function() {
		
	FormComponents.init();
		
  	$('#m_start_date').datepicker({
		format: 'yyyy-mm-dd',
	});
	
	$('#m_end_date').datepicker({
		format: 'yyyy-mm-dd',
	});
	
	$('.savestep').click(function(){
            	
    	var step = $(this).attr('id');
    	var step_split = step.split('_');
    	var step_id = step_split[2];
    	
    	if($('#as_step_detail_'+step_id).val()!=''){
		
			$.ajax({
				url:'<?php echo site_url('Site_setting/save_step') ?>',
				type:'post',
				data:{detail:$('#as_step_detail_'+step_id).val(),type:$('#as_type_'+step_id).val(),status:$('#as_step_status_'+step_id).val(),step_id: step_id},
				success:function(responseData)
				{
					var data = jQuery.parseJSON(responseData);
					$('#as_step_detail_'+step_id).val(data.as_step_detail);
					$('#as_type_'+step_id).val(data.as_type);
					$('#as_step_status_'+step_id).val(data.as_step_status);
					
					alert("Step added successfully");
					window.location.reload();
				}
			});
		}else{
			
			alert("Please enter value in step detail field..!!")
			return false;
		}
	});
	
	
	$(".up,.down").click(function(){
        var row = $(this).parent("div").parent("div").parent("div").parent("div");
        if ($(this).is(".up")) {
        	
        	if(row[0].previousElementSibling.id){
        		
        		var step = row[0].id;
				var step_split = step.split('_');
				var step_id = step_split[2];
				
				var pre_step = row[0].previousElementSibling.id;
				var pre_step_split = pre_step.split('_');
				var pre_step_id = pre_step_split[2];
				
				$.ajax({
					url:'<?php echo site_url('Site_setting/update_steps') ?>',
					type:'post',
					data:{current_step:step_id,previous_step:pre_step_id,next_step:'empty'},
					success:function(responseData)
					{
						row.insertBefore("#"+pre_step);
						var val_html = $("#as_step_detail_"+step_id).parent('div');
						val_html.html('<textarea class="span12 wysihtml5 m-wrap textarea" name="as_step_detail_'+step_id+'" id="as_step_detail_'+step_id+'" rows="6"></textarea>');

						$('#as_step_detail_'+step_id+'').wysihtml5();
						$('#as_step_detail_'+step_id+'').data("wysihtml5").editor.setValue(responseData);
					}
				});
        		
        	}else{
        		alert("There is only one step which can not be sequenced...!!");
        		return false;
        	}
        } else {
		    
           if(row[0].nextElementSibling){
            	
            	var step = row[0].id;
				var step_split = step.split('_');
				var step_id = step_split[2];
				
				var next_step = row[0].nextElementSibling.id;
				var next_step_split = next_step.split('_');
				var next_step_id = next_step_split[2];
				
				$.ajax({
					url:'<?php echo site_url('Site_setting/update_steps') ?>',
					type:'post',
					data:{current_step:step_id,previous_step:'empty',next_step:next_step_id},
					success:function(responseData)
					{
						row.insertAfter("#"+next_step);
						var val_html = $("#as_step_detail_"+step_id).parent('div');
						val_html.html('<textarea class="span12 wysihtml5 m-wrap textarea" name="as_step_detail_'+step_id+'" id="as_step_detail_'+step_id+'" rows="6"></textarea>');

						$('#as_step_detail_'+step_id+'').wysihtml5();
						$('#as_step_detail_'+step_id+'').data("wysihtml5").editor.setValue(responseData);
					}
				});
            }else{
            	alert("There is only one step which can not be sequenced...!!");
            	return false;
            }
        } 
	});
	    
	    $('#m_step').click(function(){
	    	
	    	var today = new Date();
		    var dd = today.getDate();
		    var mm = today.getMonth()+1; //January is 0!
		
		    var yyyy = today.getFullYear();
		    if(dd<10){
		        dd='0'+dd
		    } 
		    if(mm<10){
		        mm='0'+mm
		    } 
		    var today = yyyy+'-'+mm+'-'+dd;
		    
		    var pattern =/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/;
		    
		    if(pattern.test($('#m_start_date').val()) == false){alert("Please select start date in yyyy-mm-dd format..!!");}
		    if(pattern.test($('#m_end_date').val()) == false){alert("Please select end date in yyyy-mm-dd format..!!");}
		    
		    if(new Date($('#m_start_date').val()) >= new Date($('#m_end_date').val()))
			{
			    alert("End date can not be smaller than start date");
			    $('#m_start_date').val(today);
			}
	    	
	   		if($('#m_start_date').val() ==''){ alert("Please select start date..!!"); $('#m_start_date').val(today); return false;}else 	   if($('#m_end_date').val() ==''){ alert("Please select end date..!!"); $('#m_end_date').val(today); return false;}else if($('#m_duration').val() ==''){ alert("Please enter value in duration field..!!"); $('#m_duration').val('0'); return false;}else if($('#m_detail').val()!=''){
		
			$.ajax({
				url:'<?php echo site_url('Site_setting/save_maintenance_step') ?>',
				type:'post',
				data:{detail:$('#m_detail').val(),status:$('#m_status').val(),start_date:$('#m_start_date').val(),end_date:$('#m_end_date').val(),duration:$('#m_duration').val(),id:$('#m_id').val()},
				success:function(responseData)
				{
					var data = jQuery.parseJSON(responseData);
					$('#m_detail').val(data.detail);
					$('#m_status').val(data.status);
					$('#m_start_date').val(data.start_date);
					$('#m_end_date').val(data.end_date);
					$('#m_duation').val(data.duation);
					alert("Maintenance tab data saved successfully...!!");
					window.location.reload()
				}
			});
		}else{
			alert("Please enter value in step detail field..!!")
			return false;
		}
	});
  
});


function addAdminDiv(id,did)
{
	
	if($('#as_step_detail_'+id).val()!='' && $('#as_step_detail_0').val() !=''){
		
		var html = "";
		
		html +='<div id="admin_step_'+id+'" ><div class="col-md-12"><div class="btn-group pull-right " style="margin-right:30px;"> &nbsp;                 <a class="btn red mini marginright " onclick="removeAdminDiv(\''+id+'\');" href="javascript://"> <i class="fa fa-remove"></i> </a>                 </div></div><div class="span12"><div class="span8"><div class="control-group "><label class="control-label">Step '+did+'</label><div class="controls"><textarea rows="6" id="as_step_detail_'+id+'" name="as_step_detail_'+id+'" class="span12 wysihtml5 m-wrap textarea" style="display: ;"></textarea></div><label></label></div></div><div class="span4"><div class="control-group"><select class="small m-wrap" id="as_step_status_'+id+'" name="as_step_status_'+id+'" tabindex="1"><option value="Active">Active</option><option selected="selected" value="Inactive">Inactive</option></select>&nbsp;<input type="hidden" value="Admin" name="as_type_'+id+'" id="as_type_'+id+'"><input type="hidden" id="as_step_id_'+id+'" name="as_step_id_'+id+'" value="0" /><button id="as_step_'+id+'" name="as_step_'+id+'" type="button" class="btn green savestep">Save</button>&nbsp;<a href="javascript:" class="down" width="10px;"><img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/downarrow.png"></a>&nbsp;<a href="javascript:" class="up" width="10px;"><img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/uparrow.png"></a></div></div></div></div>';
		
		$("#admin_1:last").append(html);
		
		$('#as_step_detail_'+id).wysihtml5({
		  events: {
		    'load': function() { 
		      		$('#as_step_detail_'+id).html('');
		    	}
		  	}
		});
		
		$('#as_step_'+id).bind('.savestep').click(function(){
            	
    	var step = $(this).attr('id');
    	var step_split = step.split('_');
    	var step_id = step_split[2];
    	if($('#as_step_detail_'+step_id).val()!="" && $('#as_step_detail_0').val() !=''){
		
			$.ajax({
				url:'<?php echo site_url('Site_setting/save_step') ?>',
				type:'post',
				data:{detail:$('#as_step_detail_'+step_id).val(),type:$('#as_type_'+step_id).val(),status:$('#as_step_status_'+step_id).val(),step_id: step_id},
				success:function(responseData)
				{
					var data = jQuery.parseJSON(responseData);
					$('#as_step_detail_'+step_id).val(data.as_step_detail);
					$('#as_type_'+step_id).val(data.as_type);
					$('#as_step_status_'+step_id).val(data.as_step_status);
					$('.savestep').unbind('click');
					alert("Step added successfully");
					window.location.reload();
				}
				
			});
		}else{
			alert("Please enter value in step detail field..!!")
			return false;
		}
	});
	
	$(".up,.down").click(function(){
        var row = $(this).parent("div").parent("div").parent("div").parent("div");
        if ($(this).is(".up")) {
        	
        	if(row[0].previousElementSibling.id){
        		
        		var step = row[0].id;
				var step_split = step.split('_');
				var step_id = step_split[2];
				
				var pre_step = row[0].previousElementSibling.id;
				var pre_step_split = pre_step.split('_');
				var pre_step_id = pre_step_split[2];
				
				$.ajax({
					url:'<?php echo site_url('Site_setting/update_steps') ?>',
					type:'post',
					data:{current_step:step_id,previous_step:pre_step_id,next_step:'empty'},
					success:function(responseData)
					{
						row.insertBefore("#"+pre_step);
						var val_html = $("#as_step_detail_"+step_id).parent('div');
						val_html.html('<textarea class="col-md-12 wysihtml5 m-wrap textarea" name="as_step_detail_'+step_id+'" id="as_step_detail_'+step_id+'" rows="6"></textarea>');

						$('#as_step_detail_'+step_id+'').wysihtml5();
						$('#as_step_detail_'+step_id+'').data("wysihtml5").editor.setValue(responseData);
					}
				});
        		
        	}else{
        		alert("There is only one step which can not be sequenced...!!");
        		return false;
        	}
        } else {
		    
           if(row[0].nextElementSibling){
            	
            	var step = row[0].id;
				var step_split = step.split('_');
				var step_id = step_split[2];
				
				var next_step = row[0].nextElementSibling.id;
				var next_step_split = next_step.split('_');
				var next_step_id = next_step_split[2];
				
				$.ajax({
					url:'<?php echo site_url('Site_setting/update_steps') ?>',
					type:'post',
					data:{current_step:step_id,previous_step:'empty',next_step:next_step_id},
					success:function(responseData)
					{
						row.insertAfter("#"+next_step);
						var val_html = $("#as_step_detail_"+step_id).parent('div');
						val_html.html('<textarea class="col-md-12 wysihtml5 m-wrap textarea" name="as_step_detail_'+step_id+'" id="as_step_detail_'+step_id+'" rows="6"></textarea>');

						$('#as_step_detail_'+step_id+'').wysihtml5();
						$('#as_step_detail_'+step_id+'').data("wysihtml5").editor.setValue(responseData);
					}
				});
            }else{
            	alert("There is only one step which can not be sequenced...!!");
            	return false;
            }
        } 
	});
	
	}else{
		alert('Please save step before create new ..!!');
		return false;
	}
}			


function addUserDiv(id,did)
{
	if($('#as_step_detail_'+id).val()!='' && $('#as_step_detail_0').val() !=''){
		var html = "";
		
		html +='<div id="user_step_'+id+'" ><div class="col-md-12"><div class="btn-group pull-right " style="margin-right:30px;"> &nbsp;                 <a class="btn red mini marginright " onclick="removeUserDiv(\''+id+'\');" href="javascript://"> <i class="fa fa-remove"></i> </a>                 </div></div><div class="span12"><div class="span8"><div class="control-group "><label class="control-label">Step '+did+'</label><div class="controls"><textarea rows="6" id="as_step_detail_'+id+'" name="as_step_detail_'+id+'" class="span12 wysihtml5 m-wrap textarea" style="display: ;"></textarea></div><label></label></div></div><div class="span4"><div class="control-group"><select class="small m-wrap" id="as_step_status_'+id+'" name="as_step_status_'+id+'" tabindex="1"><option value="Active">Active</option><option selected="selected" value="Inactive">Inactive</option></select>&nbsp;<input type="hidden" value="User" name="as_type_'+id+'" id="as_type_'+id+'"><input type="hidden" id="as_step_id_'+id+'" name="as_step_id_'+id+'" value="0" /><button id="as_step_'+id+'" name="as_step_'+id+'" type="button" class="btn green savestep">Save</button>&nbsp;<a href="javascript:" class="down" width="10px;"><img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/downarrow.png"></a>&nbsp;<a href="javascript:" class="up" width="10px;"><img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/uparrow.png"></a></div></div></div></div>';
		
		$("#user_1:last").append(html);
		
		$('#as_step_detail_'+id).wysihtml5({
		  events: {
		    'load': function() { 
		      $('#as_step_detail_'+id).html('');
		     
		    }
		  }
		});
		
		$('#as_step_'+id).bind('.savestep').click(function(){
            	
    	var step = $(this).attr('id');
    	var step_split = step.split('_');
    	var step_id = step_split[2];
    	if($('#as_step_detail_'+step_id).val()!="" && $('#as_step_detail_0').val() !=''){
		
			$.ajax({
				url:'<?php echo site_url('Site_setting/save_step') ?>',
				type:'post',
				data:{detail:$('#as_step_detail_'+step_id).val(),type:$('#as_type_'+step_id).val(),status:$('#as_step_status_'+step_id).val(),step_id: step_id},
				success:function(responseData)
				{
					var data = jQuery.parseJSON(responseData);
					$('#as_step_detail_'+step_id).val(data.as_step_detail);
					$('#as_type_'+step_id).val(data.as_type);
					$('#as_step_status_'+step_id).val(data.as_step_status);
					$('.savestep').unbind('click');
					alert("Step added successfully");
					window.location.reload();
				}
			});
		}else{
			alert("Please enter value in step detail field..!!")
			return false;
		}
	});
	
	$(".up,.down").click(function(){
        var row = $(this).parent("div").parent("div").parent("div").parent("div");
        if ($(this).is(".up")) {
        	
        	if(row[0].previousElementSibling.id){
        		
        		var step = row[0].id;
				var step_split = step.split('_');
				var step_id = step_split[2];
				
				var pre_step = row[0].previousElementSibling.id;
				var pre_step_split = pre_step.split('_');
				var pre_step_id = pre_step_split[2];
				
				$.ajax({
					url:'<?php echo site_url('Site_setting/update_steps') ?>',
					type:'post',
					data:{current_step:step_id,previous_step:pre_step_id,next_step:'empty'},
					success:function(responseData)
					{
						row.insertBefore("#"+pre_step);
						var val_html = $("#as_step_detail_"+step_id).parent('div');
						val_html.html('<textarea class="col-md-12 wysihtml5 m-wrap textarea" name="as_step_detail_'+step_id+'" id="as_step_detail_'+step_id+'" rows="6"></textarea>');

						$('#as_step_detail_'+step_id+'').wysihtml5();
						$('#as_step_detail_'+step_id+'').data("wysihtml5").editor.setValue(responseData);
					}
				});
        		
        	}else{
        		alert("There is only one step which can not be sequenced...!!");
        		return false;
        	}
        } else {
		    
           if(row[0].nextElementSibling){
            	
            	var step = row[0].id;
				var step_split = step.split('_');
				var step_id = step_split[2];
				
				var next_step = row[0].nextElementSibling.id;
				var next_step_split = next_step.split('_');
				var next_step_id = next_step_split[2];
				
				$.ajax({
					url:'<?php echo site_url('Site_setting/update_steps') ?>',
					type:'post',
					data:{current_step:step_id,previous_step:'empty',next_step:next_step_id},
					success:function(responseData)
					{
						row.insertAfter("#"+next_step);
						var val_html = $("#as_step_detail_"+step_id).parent('div');
						val_html.html('<textarea class="col-md-12 wysihtml5 m-wrap textarea" name="as_step_detail_'+step_id+'" id="as_step_detail_'+step_id+'" rows="6"></textarea>');

						$('#as_step_detail_'+step_id+'').wysihtml5();
						$('#as_step_detail_'+step_id+'').data("wysihtml5").editor.setValue(responseData);
					}
				});
            }else{
            	alert("There is only one step which can not be sequenced...!!");
            	return false;
            }
        } 
	});
	
	}else{
		alert("Please enter value in step detail field..!!")
		return false;
	}	
}
function removeAdminDiv(id)
{
	var c=confirm('Are you to sure you want to delete this Step ?');
	if(c==true){
		$.ajax({
			url:'<?php echo site_url('Site_setting/remove_step') ?>/'+id,
			success:function(res){
				$('#admin_step_'+id).remove();
				alert("Step deleted successfully");
				window.location.reload();
			},
			 error: function(responseData){
                 $('#admin_step_'+id).remove();
				alert("Step deleted successfully");
				window.location.reload();
            }
		});
	}else{
		return false;
	}
}

function removeUserDiv(id)
{
	var c=confirm('Are you to sure you want to delete this Step ?');
	if(c==true){
		$.ajax({
			url:'<?php echo site_url('Site_setting/remove_step') ?>/'+id,
			success:function(res){
				$('#user_step_'+id).remove();
				alert("Step deleted successfully...!!");
				window.location.reload();
			},
			 error: function(responseData){
                $('#user_step_'+id).remove();
				alert("Step deleted successfully");
				window.location.reload();
            }
		});
	}else{
		return false;
	}
}
			
</script>
<div class="page-content"> 
  
  <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
  <div class="modal hide" id="portlet-config">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal"></button>
      <h3>portlet Settings</h3>
    </div>
    <div class="modal-body">
      <p>Here will be a configuration form</p>
    </div>
  </div>
  <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM--> 
  <!-- BEGIN PAGE CONTAINER-->
  <div class="container-fluid admin-list"> 
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
      <div class="col-md-12">
        <h3 class="page-title">
          Popup Setup </h3>
      </div>
    </div>
    <!-- END PAGE HEADER--> 
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
      <div class="col-md-12">
        <div class="portlet box green">
          <div class="portlet-title">
            <div class="caption">Popup Setup </div>
          </div>
          <div class="portlet-body form"> 
            <!-- BEGIN FORM-->
             

            <?php
				$attributes = array('class' => 'form-horizontal', 'id' => 'popup_setup_add','name'=>'popup_setup_add');
				echo form_open_multipart('popup_setup/save_steps', $attributes);
			?>
           
            <div class="alert alert-danger hide">
              <button class="close" data-dismiss="alert"></button>
              You have some form errors. Please check below. </div>
            <div class="alert alert-success hide">
              <button class="close" data-dismiss="alert"></button>
              Your form validation is successful! </div>
              
            <div class="col-md-12">
                <label class="label-control blue ad_label" style="width:100%">Admin Setup </label>
            </div>
            
            <div id="admin_1" >	
               <div class="col-md-12">
                	<div class="btn-group pull-right " style="margin-right:10px;" > 
                 		<a class="btn black mini marginright " onclick="addAdminDiv('<?php echo ($tot_step+1);?>','<?php echo count($admin_setup)+1;?>')" href="javascript://"> <i class="fa fa-plus"></i> </a> 
                 	</div>
                </div> 
            <?php $i = 0;if($admin_setup){
            	foreach ($admin_setup as $as) {
            		?>
            	<div id="admin_step_<?php echo $as->as_step_id;?>" >		
            	<div class="col-md-12">
            		<?php if($i>0){ ?>
                	<div class="btn-group pull-right " style="margin-right:10px;"> &nbsp;
                 		<a class="btn red mini marginright " onclick="removeAdminDiv('<?php echo $as->as_step_id;?>')" href="javascript://"> <i class="fa fa-remove"></i> </a>
                 	</div>
                <?php } ?>
                </div>
          	
		        <div class="col-md-12">
		            <div class="col-md-8">
		                <div class="form-group col-md-12 ">
		                  <label class="control-label col-md-2">Step <?php echo $i+1;?></label>
		                  <div class="controls col-md-10">
		                    <textarea rows="6" id="as_step_detail_<?php echo $as->as_step_id;?>" name="as_step_detail_<?php echo $as->as_step_id;?>" class="span12 wysihtml5 m-wrap textarea" ><?php echo $as->as_step_detail;?></textarea>
		                  </div>
		                  <label></label>
		                </div>
		              </div>
		              <div class="col-md-4">
		                <div class="form-group">
		                    <select class="small m-wrap" id="as_step_status_<?php echo $as->as_step_id;?>" name="as_step_status_<?php echo $as->as_step_id;?>" tabindex="1">
		                      <option <?php if($as->as_step_status == 'Active'){?> selected="selected"  <?php } ?> value="Active">Active</option>
		                      <option <?php if($as->as_step_status == 'Inactive'){?> selected="selected"  <?php } ?>  value="Inactive">Inactive</option>
		                    </select>
		                    
		            	<input type="hidden" id="as_type_<?php echo $as->as_step_id;?>" name="as_type_<?php echo $as->as_step_id;?>" value = "Admin" />
		            	<input type="hidden" id="as_step_id_<?php echo $as->as_step_id;?>" name="as_step_id_<?php echo $as->as_step_id;?>" value="<?php echo $as->as_step_id;?>" />
		            	
		           
		                <button type="button" id="as_step_<?php echo $as->as_step_id;?>" name="as_step_<?php echo $as->as_step_id;?>"  class="btn green savestep">Save</button>
		                  <a href="javascript:" class="down" width="10px;">
						<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/downarrow.png">
						</a>
		<a href="javascript:" class="up" width="10px;">
						<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/uparrow.png">
						</a>
		    
		        		</div></div>
		    		</div>
		    		<div><span id="error" style="display: none"></span></div>
		       	</div>
		    
            <?php $i++;}  }else{ ?>
            	
            	<div id="admin_step_0" >
            		<div class="col-md-12">
                	<div class="btn-group pull-right " style="margin-right:10px;" > 
                 		<a class="btn black mini marginright " onclick="addAdminDiv('0','1')" href="javascript://"> <i class="fa fa-plus"></i> </a> 
                 	</div>
                </div>
            		
            	<div class="col-md-12">
              <div class="col-md-8">
                <div class="form-group col-md-12 ">
                  <label class="control-label col-md-2">Step 1</label>
                  <div class="controls col-md-10">
                      <textarea rows="6" id="as_step_detail_0" name="as_step_detail_0" class="col-md-12 wysihtml5 m-wrap textarea"  style="width:100%"></textarea>
                  </div>
                  <label></label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <select class="small m-wrap" id="as_step_status_0" name="as_step_status_0" tabindex="1">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
          		<input type="hidden" id="as_type_0" name="as_type_0" value = "Admin" /> 
          		<input type="hidden" id="as_step_id_0" name="as_step_id_0" value="0" />
                <button type="button" id="as_step_0" name="as_step_0" class="btn green savestep">Save</button>
                <a href="javascript:" class="down" width="10px;">
				<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/downarrow.png">
				</a>
<a href="javascript:" class="up" width="10px;">
				<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/uparrow.png">
				</a>
    
              </div></div>
            </div>	
            <?php } ?>
            </div>
            
            <div id="user_1" >
             <div class="col-md-12">
                 <label class="label-control blue ad_label" style="width:100%">User Setup</label>
            </div>
             <div class="col-md-12">	
                	<div class="btn-group pull-right " style="margin-right:10px;" > 
                 		<a class="btn black mini marginright " onclick="addUserDiv('<?php echo ($tot_step+1);?>','<?php echo count($user_setup)+1;?>')" href="javascript://"> <i class="fa fa-plus"></i> </a> 
            	   	</div>
            </div>
                
            <?php $i=0; if($user_setup){
            	foreach ($user_setup as $us) {
            		?>  
        	<div id="user_step_<?php echo $us->as_step_id;?>" >
            <div class="col-md-12">	
             	<?php if($i>0){ ?>
                	<div class="btn-group pull-right " style="margin-right:10px;"> &nbsp;
                 		<a class="btn red mini marginright " onclick="removeUserDiv('<?php echo $us->as_step_id;?>')" href="javascript://"> <i class="fa fa-remove"></i> </a>
                 	</div>
                <?php } ?>
            </div>	
            	  
            <div class="col-md-12">
              <div class="col-md-8">
                <div class="form-group  col-md-12 ">
                  <label class="control-label col-md-2">Step <?php echo $i+1;?>:</label>
                  <div class="controls col-md-10">
                    <textarea rows="6" id="as_step_detail_<?php echo $us->as_step_id;?>" name="as_step_detail_<?php echo $us->as_step_id;?>" class="col-md-12 wysihtml5 m-wrap textarea" style="width:100% ;"><?php echo $us->as_step_detail;?></textarea>
                  </div>
                  <label></label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <select class="small m-wrap" id="as_step_status_<?php echo $us->as_step_id;?>" name="as_step_status_<?php echo $us->as_step_id;?>" tabindex="1">
                      <option <?php if($us->as_step_status == 'Active'){?> selected="selected"  <?php } ?> value="Active">Active</option>
                      <option <?php if($us->as_step_status == 'Inactive'){?> selected="selected"  <?php } ?>  value="Inactive">Inactive</option>
                    </select>
                    
            	<input type="hidden" id="as_type_<?php echo $us->as_step_id;?>" name="as_type_<?php echo $us->as_step_id;?>" value = "User" />
            	<input type="hidden" id="as_step_id_<?php echo $us->as_step_id;?>" name="as_step_id_<?php echo $us->as_step_id;?>" value="<?php echo $us->as_step_id;?>" />
           
                <button type="button" id="as_step_<?php echo $us->as_step_id;?>" name="as_step_<?php echo $us->as_step_id;?>"  class="btn green savestep">Save</button>
                <a href="javascript:" class="down" width="10px;">
				<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/downarrow.png">
				</a>
<a href="javascript:" class="up" width="10px;">
				<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/uparrow.png">
				</a>
              </div></div>
            </div>
            </div>
           
            <?php $i++; }  }else{ ?>
            	
          <div id="user_step_0" >
          	<div class="col-md-12">
                	<div class="btn-group pull-right " style="margin-right:10px;" > 
                 		<a class="btn black mini marginright " onclick="addUserDiv('0','1')" href="javascript://"> <i class="fa fa-plus"></i> </a> 
                 	</div>
                </div>
                
            <div class="col-md-12">
              <div class="col-md-8">
                <div class="form-group col-md-12">
                  <label class="control-label col-md-2">Step 1:</label>
                  <div class="controls col-md-10">
                    
                    <textarea rows="6" id="as_step_detail_0" name="as_step_detail_0" class="col-md-12 wysihtml5 m-wrap textarea" style="width: 100% ;"></textarea>
                    
                  </div>
                  <label></label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <select class="small m-wrap" id="as_step_status_0" name="as_step_status_0" tabindex="1">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
		            	
          		<input type="hidden" id="as_type_0" name="as_type_0" value = "User" /> 
          		<input type="hidden" id="as_step_id_0" name="as_step_id_0" value="0" />
                <button type="button" id="as_step_0" name="as_step_0" class="btn green savestep">Save</button>
                <a href="javascript:" class="down" width="10px;">
				<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/downarrow.png">
				</a>
<a href="javascript:" class="up" width="10px;">
				<img alt="logo" src="<?php echo base_url().getThemeName(); ?>/assets/img/uparrow.png">
				</a>
              </div></div>
            </div>
            </div>
            <?php } ?>
            </div>
            <div id="maintainance_1" >            
             <div class="col-md-12 ">
                 <label class="label-control blue ad_label" style="width:100%">Maintenance Tab</label>
            </div>
            <?php if($maintenance){?>  
	             <div class="col-md-12">
	              <div class="col-md-8">
	                <div class="form-group col-md-12">
	                  <label class="control-label col-md-2">Detail:</label>
	                  <div class="controls col-md-10">
	                    
	                    <textarea rows="6" id="m_detail" name="m_detail" class="col-md-12 wysihtml5 m-wrap textarea" style="width: 100%;"><?php echo $maintenance['detail'];?></textarea>
	                   
	                  </div>
	                  <label></label>
	                </div>
	              </div>
	              <div class="col-md-4">
	                <div class="form-group">
	                    <select class="small m-wrap" id="m_status" name="m_status" tabindex="1">
	                      <option <?php if($maintenance['status'] == 'Active'){?> selected="selected"  <?php } ?>  value="Active">Active</option>
	                      <option <?php if($maintenance['status'] == 'Inactive'){?> selected="selected"  <?php } ?>  value="Inactive">Inactive</option>
	                    </select>
			        <input type="hidden" id="m_id" name="m_id" value="<?php echo $maintenance['id'];?>" />    	
	          		<button type="button" id="m_step" name="m_step" class="btn green ">Save</button>
	                
	              </div></div>
	            </div>
	             <div class="form-group ">
				
				<!--<div class="controls">--></div>
					
					<div class="input-prepend col-md-4">
						<label class="control-label marginleft">Start Date : &nbsp;</label>
						<span class="add-on"><i class="fa fa-calendar"></i></span><input type="text" class="small m-wrap date-range" id="m_start_date" name="m_start_date" value="<?php echo date("Y-m-d",strtotime($maintenance['start_date']));?>" />
					</div>
					
	                <div class="input-prepend col-md-4">
	                <label class="control-label marginleft">End Date : &nbsp;</label> 
						<span class="add-on"><i class="fa fa-calendar"></i></span><input type="text" class="small m-wrap date-range" id="m_end_date" name="m_end_date" value="<?php echo date("Y-m-d",strtotime($maintenance['end_date']));?>" />
					
	                </div>
	          	
	                
	               <div class="input-prepend">
	               		<label class="control-label marginleft">Duration : &nbsp;</label><input name="m_duration" id="m_duration" type="text" placeholder="duration in seconds" class="small m-wrap" value="<?php echo $maintenance['duration'];?>" /> </div> Seconds
	                
				<!--</div>-->
	         
			</div>
			
			<?php } ?>
             </div>                      

            </form>
            <!-- END FORM--> 
          </div>
        </div>
        <!-- BEGIN SAMPLE FORM PORTLET--> 
        
        <!-- END SAMPLE FORM PORTLET--> 
      </div>
    </div>
    <!-- END PAGE CONTENT--> 
  </div>
</div>
