<script src="<?php echo base_url().getThemeName();?>/assets/plugins/select2/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<label class="control-label col-md-2 " style="margin-right: 15px">Division </label>
<div class="controls">
	<!--<input id="tags_division" name="tags_division" type="text" class="m-wra tags medium" value="" />-->
    <input type="hidden" id="tags_division" name="tags_division" class="select2" value="<?php echo $tags_division;?>" style="width:80%;">
</div>

<script>
	$(document).ready(function(){
		$("#tags_division").select2({
			tags: [<?php echo isset($company_division)?$company_division:'';?>],
		});
	});
</script>
