<script src="<?php echo base_url().getThemeName();?>/assets/plugins/select2/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<label class="control-label col-md-2" style="margin-right: 15px">Skills </label>
<div class="controls">
    <input type="hidden" id="tags_skills" name="tags_skills" class="select2" value="<?php echo $tags_skills;?>" style="width:80%">
</div>

<script>
	$(document).ready(function(){
		$("#tags_skills").select2({
			tags: [<?php echo isset($company_skills)?$company_skills:'';?>],
		});
	});
</script>
