<?php 
	$theme =  getThemeName();
	$theme_url = base_url().getThemeName(); 
	//$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	$default_format = default_date_format(); 
	$com_off_days = get_company_offdays();
	
	
	//echo $msg;die;
	if($msg =='insert'){ $message = "Project inserted successfully";}
	if($msg =='update'){ $message = "Project updated successfully";}
	
	//echo $department_id."==".$division_id;die;
	
	//echo $project_end_date;die;
	//echo $date_arr_java[default_date_format()];die;
?>
<script type="text/javascript">

$(document).ready(function(){
    $('#project_start_date').datepicker({format: '<?php echo $date_arr_java[default_date_format()]; ?>',daysOfWeekDisabled: "<?php echo get_company_offdays();?>",
});
    $('#project_end_date').datepicker({format: '<?php echo $date_arr_java[default_date_format()]; ?>',autoclose:true,
});
    $('#project_end_date').on('changeDate', function(ev){$(this).valid();});
    
    if(<?php echo $project_id;?> != 0){
	getdepartment('<?php echo $division_id;?>','<?php echo $department_id;?>');
   }
    $("#project_desc").limiter(40000, $('#ch'));
    
    var form1 = $('#frm_addProject');
    var error1 = $('.alert-error', form1);
    var success1 = $('.alert-success', form1);
    
	$.validator.addMethod("greaterThan", 
		function(value, element, params) {
		
		    if (!/Invalid|NaN/.test(new Date(value))) {
		        return new Date(value) >= new Date($(params).val());
		    }
		
		    return isNaN(value) && isNaN($(params).val()) 
		        || (Number(value) >= Number($(params).val())); 
		},'Must be greater than project start date.');
    
     $('#frm_insertProject').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-inline', // default input error message class
        focusInvalid: true, // do not focus the last invalid input
        ignore: "",
        rules: {
			
           project_title : {
           		required: true,
                rangelength: [3, 50]
           },
           project_start_date:{
           		required: true
           },
           project_end_date : {
           		required : true,
           		greaterThan : "#project_start_date"
           },
           division_id : {
           		required : true
           },
           department_id : {
           		required : true
           },
           project_desc : {
           		required : true
           }
       },
       errorPlacement: function (error, element) {
           	
           

                if (element.attr("name") == "project_start_date" || element.attr("name") == "project_end_date" ) { 
                	// for chosen elements, need to insert the error after the chosen container
                    error.appendTo( element.parent("div") );
                } else if(element.attr("name") == 'task_priority'){
                	error.appendTo( element.parent("span").parent("div").parent("label").parent("div").parent("li").parent("ul").parent("div").parent("div") );
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                } 
   
            },
           submitHandler: function (form1) {
	            success1.show();
	            error1.hide();
	            $("button[type=submit]").prop("disabled",true);
	            form1.submit();
	        }
        
    });
    
});
    
function getdepartment(id,did)
{
	$.ajax({
		url:'<?php echo site_url('project/getDepartment') ?>/'+id+'/'+did,
		beforeSend:function(){ $('#department_id').html('<option >Loading....</option>');},
		success:function(res){
			$('#department_id').html(res);
			},
	});	
}
	
</script>


<!--<script src="<?php echo $theme_url; ?>/js/jquery.tinylimiter.js?Ver=<?php echo VERSION;?>"></script>-->

<script src="<?php echo $theme_url;?>/assets/scripts/form-components.js?Ver=<?php echo VERSION;?>"></script> 
<script src="<?php echo $theme_url; ?>/assets/scripts/app.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url; ?>/js/jquery.tinylimiter.js?Ver=<?php echo VERSION;?>"></script>
<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container">
  			<div class="container">
			 	 <div class="page-controler clearfix">
				 		<div class="pull-left"> 
							<a href="<?php echo site_url('project/list_project');?>" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div>
						 
				 </div>
			 
				 
				<!-- <div class="page-title margin-bottom-25">
				 	<h2> My Profile </h2>
				 </div>-->
				 <div class="border-bx">
				  <div class="horizontal-form">
					 	<!-- BEGIN FORM-->
					 	<?php if(isset($msg) && $msg != ''){ ?>
								 		<div class='alert alert-success' id="insert_msg" ><a class='closemsg' data-dismiss='alert'></a><span><?php echo $message;?></span></div>
								 	<?php } ?>
						<?php   $attributes = array('name'=>'frm_insertProject', 'id' => 'frm_insertProject');
								echo form_open('project/insertProject', $attributes); 
						?>
							<div class="row">
								<div class="col-md-12">
									
									<?php if($project_title==''){	?>
									<div class="control-group">
										 <label class="control-label">Project name : </label> 
										<div class="controls">
											<input type="text" name="project_title" id="project_title"  value="<?php echo $project_title; ?>" placeholder="Enter project name" class="m-wrap fullwd " />
										 </div>
									</div>
									<?php }else{ ?>
										
										<div class="control-group">
										 <label class="control-label">Project name : </label> 
										<div class="controls">
											<input type="text" <?php echo($is_owner=='0')?'readonly="readonly"':'';?> name="project_title" id="project_title"  value="<?php echo $project_title; ?>" placeholder="Enter project name" class="m-wrap fullwd " />
										 </div>
									</div>
										
										<?php } ?>
										<?php 
										$today_date = date("Y-m-d");
										date_default_timezone_set($this->session->userdata("User_timezone")); 
										if($project_start_date){
											$project_start_date = change_date_format($project_start_date); 
										}
										
										if($project_end_date){
											$project_end_date = change_date_format($project_end_date);
										}
										?>
									<?php //if($project_start_date){ echo date(default_date_format(),strtotime($project_start_date));}else{ echo date(default_date_format());} ?>
									<div class="control-group clearfix date-picker">
										 <label class="control-label">Start Date : </label> 
										<div class="controls set-controls">
											<input type="text"  name="project_start_date" id="project_start_date" <?php echo($project_id!='0')?($is_owner=='0')?'disabled="disabled"':'':'';?>   placeholder="" value="<?php if($project_start_date){ echo date(default_date_format(),toDateUserTimeStamp($project_start_date));}else{ echo date(default_date_format(),toDateUserTimeStamp($today_date));} ?>" class="m-wrap fullwd "/>
										 </div>
										<span> <i class="stripicon calgreyicon"> </i> </span>
									</div>
									
									<div class="control-group clearfix date-picker">
										 <label class="control-label">End  Date : </label> 
										<div class="controls set-controls ">
											<input type="text" name="project_end_date" id="project_end_date" <?php echo($project_id!='0')?($is_owner=='0')?'disabled="disabled"':'':'';?>  placeholder="" value="<?php if($project_end_date){ echo date(default_date_format(),toDateUserTimeStamp($project_end_date));}else{ echo date(default_date_format(),toDateUserTimeStamp($today_date));} ?>" class="m-wrap fullwd"  />
											 
										</div>
										<span > <i class="stripicon calgreyicon"> </i> </span>
									</div>
									<?php date_default_timezone_set("UTC"); ?>
									<div class="control-group">
										 <label class="control-label">Division :</label> 
										<div class="controls">
											
											<select onchange="getdepartment(this.value,'<?php echo $department_id;?>');" class="fullwd m-wrap" name="division_id" tabindex="1" <?php echo($project_id!='0')?($is_owner=='0')?'disabled="disabled"':'':'';?>>
													<option value="">-- Select Division --</option>
												<?php if($division){
													foreach ($division as $row) { ?>
														<option value="<?php echo $row->division_id;?>" <?php if($row->division_id == $division_id){ echo 'selected="selected"'; } ?>><?php echo $row->devision_title; ?></option>
												<?php	}
												} ?>
											</select>
											
										</div>
									</div>
									
									<div class="control-group">
										 <label class="control-label">Department :</label> 
										<div class="controls">
											
											<select class="fullwd m-wrap" name="department_id" id="department_id" tabindex="1" <?php echo($project_id!='0')?($is_owner=='0')?'disabled="disabled"':'':'';?>>
												<option value="">-- Select Department--</option>
												<?php if($department){
													foreach($department as $row){ ?>
														<option value="<?php echo $row->department_id;?>" <?php if($row->department_id == $department_id){ echo 'selected="selected"'; } ?>><?php echo $row->department_title; ?></option>
												<?php }
												} ?>
											</select>							 
										</div>
									</div>
									
									<div class="control-group">
										<label>Description :<span class="req">*</span></label>
										<div>
											<textarea class="m-wrap fullwd " name="project_desc" id="project_desc" <?php echo($project_id!='0')?($is_owner=='0')?'readonly="readonly"':'':'';?> rows="4"><?php echo $project_desc; ?></textarea><span class="add-on"></span>
											
										</div>
										<span class="chr">Char left :- <i id="ch">40000</i></span>
									</div>
									
								</div>
								
							</div>
							 
								
							  <div class="control-group">
								<div class="controls text-center margin-top-20">
									<input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id;?>" />
									 <button type="submit" id="submitproject" class="btn blue btn-mid"> <i class="stripicon correcticon"> </i> <?php echo ($project_id!='0')?"Update":"Save";?> </button>
								 </div>
								 
							</div>
						</form>
						<!-- END FORM-->  
					</div>
				 </div>
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>

