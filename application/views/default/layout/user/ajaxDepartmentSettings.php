<script src="<?php echo base_url().getThemeName();?>/assets/plugins/select2/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<?php if($this->session->userdata('is_administrator') =='1'){?>
<div class="form-group">
	<label class="control-label">Department
            <?php if($this->session->userdata('is_administrator')=='1'){?>
		<span id="dephide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup Department in Company Settings</a></span>
            <?php } ?>
        </label>
	<div class="controls relative-position">
		
                <input type="hidden" id="tags_department" name="tags_department" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_department;?>" <?php if($tags_division == ''){ echo "disabled='disabled'";}?> >
		<span class="input-load" id="tags_department_loading"></span>
		
	</div>
</div>
<?php }else{ 
	if(strlen($company_division) > '2' || $tags_department!=''){
	?>
<div class="form-group">
	<label class="control-label">Department
        <?php if($this->session->userdata('is_administrator')=='1'){?>
		<span id="dephide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup Department in Company Settings</a></span>
		<?php } ?>
        </label>
	<div class="controls relative-position">
		
		<input type="hidden" id="tags_department" name="tags_department" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_department;?>" <?php if($tags_division == ''){ echo "disabled='disabled'";}?> >
		<span class="input-load" id="tags_department_loading"></span>
		
	</div>
</div>
<?php } } ?>

<script>
	$(document).ready(function(){
		
		var company_division = "<?php echo $company_division;?>";
		var company_department = "<?php echo $company_department;?>";
		$("#tags_division").select2({
			tags: [<?php echo isset($company_division)?$company_division:'';?>],
		});
		
		$("#tags_department").select2({
			tags: [<?php echo isset($company_department)?$company_department:'';?>],
		});
		
		if(company_division.length >'2'){
			$('#divhide').hide();
		}else{
			$('#divhide').show();
		}
		
		if(company_department.length < '3' && $("#tags_division").val() == ""){
			$('#dephide').hide();
		}else{
			$('#dephide').show();
		}
		
		$(".mysetting-text").click(function(){
	    	var id = $(this).attr('id');
	    	var name = $(this).attr('name');
	    	var value = $(this).val();
	    	//if(value.trim()){
	    		$("#"+id+"_loading").show();
				$.ajax({
		    		type : 'post',
		    		url : SIDE_URL+'user/mysetting_index',
		    		data : {name:name, value:value},
		    		async:false,
		    		success : function(data){
		    			$("#"+id+"_loading").hide();
		    		}
		    	});
	    	//}
        });
		
	});
</script>
