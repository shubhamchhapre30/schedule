<script src="<?php echo base_url().getThemeName();?>/assets/plugins/select2/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<label class="control-label col-md-2" style="margin-right: 15px">Departments </label>
<div class="controls">
    <input type="hidden" id="tags_department" name="tags_department" class="select2" value="<?php echo $tags_department;?>" style="width:80%">
</div>

<script>
	$(document).ready(function(){
		$("#tags_department").select2({
			tags: [<?php echo isset($company_department)?$company_department:'';?>],
		});
		
	});
</script>
