<?php 
	$theme_url = base_url().getThemeName();
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	$default_format = $this->config->item('company_default_format'); 
	
?>
<script type="text/javascript">
	var SIDE_URL = '<?php echo site_url(); ?>';
	var LOG_USER_ID = '<?php echo get_authenticateUserID(); ?>';
	var DEFAULT_DATE_FOMAT = '<?php echo $default_format;?>';
	var JAVASCRIPT_DATE_FORMAT = '<?php echo $date_arr_java[$default_format]; ?>';
	var READY_ID = '<?php echo get_task_status_id_by_name('Ready'); ?>';
	var USERNAME = '<?php echo $this->session->userdata('username');?>';
	var CAL_SESSION_ID = '<?php echo $this->session->userdata('Temp_calendar_user_id');?>';
	var KANBAN_SESSION_ID = '<?php echo $this->session->userdata('Temp_kanban_user_id');?>';
	var IS_ADMIN = '<?php echo $this->session->userdata("is_administrator");?>';
        var PRICING_MODULE_STATUS ='<?php echo $this->session->userdata('pricing_module_status');?>';
        
	function editor1()
        {
            $(document).ready(function(){
                ComponentsEditors.init();
                $( ".wysihtml5-sandbox" ).css('height','80px');
                $( ".wysihtml5-sandbox" ).attr('height','80px');
                //$( ".editor .wysihtml5-sandbox" ).resizable();
            });
        }
        function onEditorBlur()
        {
            var id = $(this)[0].textarea.element.id;
            $("#"+id).trigger('change');
        }
</script>
<!--<script src="<?php echo $theme_url;?>/assets/scripts/components-editors.min.js" type="text/javascript"></script>-->
<link href="<?php echo $theme_url;?>/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url().'admin/'.getThemeName(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css?Ver<?php echo VERSION;?>" />
<script src="<?php echo base_url().'admin/'.getThemeName(); ?>/assets/plugins/ckeditor/ckeditor.js?Ver<?php echo VERSION;?>" type="text/javascript"></script>-->

<link href="<?php echo $theme_url; ?>/assets/plugins/bootstrap/css/bootstrap-multiselect.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo $theme_url; ?>/assets/plugins/bootstrap/js/bootstrap-multiselect.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>

<script src="<?php echo $theme_url;?>/assets/js/common<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/css/bootstrap-editable.css?Ver=<?php echo VERSION;?>" />
<script src="<?php echo $theme_url;?>/assets/plugins/jquery.mockjax.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/js/bootstrap-editable.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/scripts/form-editable.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/plugins/dropzone.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		FormEditable.init();
	});
</script>

<div id="full-width" class="modal fade taskcontainer task_model_popup" tabindex="-1" >
<?php  $this->load->view($theme.'/layout/task/general') ?>
</div>
<div id="series" class="modal model-size fade customecontainer recurrence-popup" tabindex="-1">
    <div class="modal-header" >
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" ></button>
		<h3> Occurence  </h3>
	</div>
	<div class="modal-body">
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				
                            <div class="form-group" style="padding: 10px;">
					<label class="control-label">Do you want to update Task series or Task occurrence?</label>
					
					<div class="controls">
						<label class="radio">
							<a id="series_task" href="javascript:void(0);" ><input type="radio" name="edit_task_re" value="series" ></a>Task Series
						</label>
						<label class="radio">
							<a id="ocuurence_task" href="javascript:void(0);" ><input type="radio" name="edit_task_re" value="occurrence" ></a>Task Occurrence
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
