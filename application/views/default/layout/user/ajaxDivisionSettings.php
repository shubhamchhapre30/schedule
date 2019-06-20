<script src="<?php echo base_url().getThemeName();?>/assets/plugins/select2/select2.min.js?Ver=<?php echo VERSION;?>"></script>
<?php if($this->session->userdata('is_administrator') =='1'){?>
<div class="form-group">
	<label class="control-label">Division </label>
	<div class="controls relative-position">
		<input type="hidden" id="tags_division" name="tags_division" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_division;?>">
		<span class="input-load" id="tags_division_loading"></span>
		<?php if($this->session->userdata('is_administrator')=='1'){ ?>
		<span id="divhide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup</a></span>
		<?php } ?>
	</div>
	
</div>
<?php }else{ 
	if(strlen($company_division) > '2' || $tags_division !=''){
	?>
	<div class="form-group">
	<label class="control-label">Division </label>
	<div class="controls relative-position">
		<input type="hidden" id="tags_division" name="tags_division" class="m-wrap large select2 mysetting-text" value="<?php echo $tags_division;?>">
		<span class="input-load" id="tags_division_loading"></span>
		<?php if($this->session->userdata('is_administrator')=='1'){ ?>
		<span id="divhide"><a href="javascript:void(0)" onclick="openCompanyDivisionTab();"> Setup</a></span>
		<?php }  ?>
	</div>
	
</div>
<?php } }?>

<script>
	$(document).ready(function(){
		var company_division = "<?php echo $company_division;?>";
		var company_department = "<?php echo $company_department;?>";
		
		$("#tags_division").select2({
			tags: [<?php echo isset($company_division)?$company_division:'';?>],
		});
		 $("#tags_department").select2({
			tags: [<?php echo $company_department;?>],
			
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
	    		if(name =='tags_division'){
	    			$("#"+id+"_loading").show();
	    			$.ajax({
			    		type : 'post',
			    		url : SIDE_URL+'user/mysetting_index',
			    		data : {name:name, value:value},
			    		async:false,
			    		success : function(data){
			    			
			    		}
			    	});
			    	
			    	$.ajax({
						type : 'post',
						url : SIDE_URL+'user/divisionsSettings',
						data : {user_id : user_id},
						success : function(data){
							$("#addUserDivisionDivSettings").html(data);
						}
					});
			    	
			    	$.ajax({
						type : 'post',
						url : SIDE_URL+'user/departmentsSettings',
						data : {user_id : user_id, division_id:$("#tags_division").val()},
						success : function(data){
							$("#addUserDepartmentDivSettings").html(data);
						}
					});
			    	$("#"+id+"_loading").hide();
	    		} else{
	    			
	    		}
	    	//}
        });
	});
</script>
