<?php 
	$theme_url = base_url().getThemeName();
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
        if($date_arr_java[$site_setting_date]=='dd M,yyyy'){
            $size=11;
        }else{
            $size=10;
        }
        $new_date = date("Y-m-d H:i:s");
        date_default_timezone_set($this->session->userdata("User_timezone")); 
        $date_to_timezone = date($site_setting_date,strtotime(toDateNewTime($new_date)));
        $format = $date_arr_java[$site_setting_date];
        $split1 = explode('-', $format);
        $split2 = explode('/', $format);
        if(count($split1)>1){
            $split_format = $split1;
        }else if(count($split2)>1){
            $split_format = $split2;
        }
        ?>


<script type="text/javascript" src="<?php echo $theme_url;?>/assets/js/jsapi"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$('#exportdata').hide();
                $('.input-append.date').datepicker().on('changeDate', function(e) {
                    $("#hide_from_date").val($("#from_date").val());
                    $("#hide_to_date").val($("#to_date").val());
                });
 		$('.input-append.date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
			startDate: -Infinity,
                        format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
                        autoclose:true,
                        
		});
		
		
		$("#division_id_report").change(function(){
			var division_id = $(this).val();
			//$('#dvLoading').fadeIn('slow');
			$.ajax({
				type: 'post',
	            url : '<?php echo site_url("reports/setDepartment"); ?>',
	            data: {division_id : division_id},
	            success: function(responseData) {
	                $("#department_id_report").html(responseData);
	                //$('#dvLoading').fadeOut('slow');
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	            }
			});
		});
		
		$("#category_id").change(function(){
			var category_id = $(this).val();
			//$('#dvLoading').fadeIn('slow');
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("reports/getSubCategory");?>',
				data : {category_id : category_id},
				success : function(data){
					$("#sub_category_id").html(data);
					//$('#dvLoading').fadeOut('slow');
				}
			});
		});
                 $("#project_id").change(function(){
			var project_id = $(this).val();
			//$('#dvLoading').fadeIn('slow');
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("reports/getProjectUsers");?>',
				data : {project_id : project_id},
				success : function(data){
					$("#user_id").html(data);
					//$('#dvLoading').fadeOut('slow');
				}
			});
		});
		
		var form1 = $('#frm_submit_report');
        var error1 = $('.alert-error', form1);
        var success1 = $('.alert-success', form1);
        
       	$.validator.addMethod("greaterThan", 
		function(value, element, params) {
			if($("#to_date").val()=='' || $("#from_date").val()==''){
				return true;
			}
                        $('#from_date').datepicker({
			startDate: -Infinity,
			format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
                        autoclose:true,
                        
                        });
                
                        $('#to_date').datepicker({
                                startDate: -Infinity,
                                format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
                                autoclose:true,

                        });
		    //var from_date = $('#from_date').datepicker('getDate');
			//var to_date = $("#to_date").datepicker('getDate');
			
			var from_date = $('#from_date').val();
			var to_date = $("#to_date").val();
			//alert(from_date+"===="+to_date);
			from_date = $('#from_date').datepicker('getDate');
			to_date = $('#to_date').datepicker('getDate');
			//alert(from_date+"===="+to_date);
			//alert(from_date+"===="+to_date);
			//alert(('#from_date').data('date'));
			//alert(from_date);
			//alert(to_date);
			//alert(Number($('#to_date').val())+"===="+Number($('#from_date').val()));
			if (!/Invalid|NaN/.test(to_date)) {
		        return to_date >= from_date;
		    }
			return (Number($('#to_date').val()) >= Number($('#from_date').val())); 
		},'Must be greater than or equal to start date.');
		
		
        $('#frm_submit_report').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            
            ignore: "",
			rules : {
				"report_title" : {
					required : true
				},
				"to_date" : {
					greaterThan : true
				}
			},
       		errorPlacement: function (error, element) {
				if (element.attr("name") == "from_date" || element.attr("name") == "to_date" ) { // for chosen elements, need to insert the error after the chosen container
                    error.appendTo( element.parent("div") );
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                } 
   
            },
		 	submitHandler: function () {
		 		$('#dvLoading').fadeIn('slow');
               	$.ajax({
               		type : 'post',
               		url : '<?php echo site_url("reports/run_reports");?>',
               		data : $("#frm_submit_report").serialize(),
               		success : function(data){
               			
               			var a = $('#report_title').val();
               			$('#exportdata').show('slow');
               			$("#ajax_div").css("display","block");
               			if(data == "no_data"){
	               			$("#ajax_report_data").html("<h6 class='heading6'><strong> No Records Found. </strong></h6>");
               				
               			} else {
               				$("#ajax_report_data").html(data);
	               			if(a == "Actual time by category over a period of time"){
	               				google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization1});
	               			}
               			}
	     				$('#dvLoading').fadeOut('slow');
               		}
               	});
           }
		});
		
		$("#export").on("click",function(){ 
			var report = $("#report_title").val();
			if(report==''){
				alertify.alert("Please select any report.");
				return false;
			} else {
				$('#frm_submit_report').submit();
				//sreturn false;
				$("#exportdata").show();
				document.getElementById('frm_export').setAttribute('target', '_blank');
				
				$("#ex_graph_image").val($("#chart_img_div").html());
				$("#ex_report_title").val($("#report_title").val());
				$("#ex_from_date").val($("#from_date").val());
				$("#ex_to_date").val($("#to_date").val());
				$("#ex_user_id").val($("#user_id").val());
				$("#ex_division_id").val($("#division_id_report").val());
				$("#ex_department_id").val($("#department_id_report").val());
				$("#ex_category_id").val($("#category_id").val());
				$("#ex_sub_category_id").val($("#sub_category_id").val());
				$("#ex_project_id").val($("#project_id").val());
				$("#ex_customer_id").val($("#report_customer_id").val());
				setTimeout(function(){ 
					if(report == "Actual time by category over a period of time"){
						$("#ex_graph_image").val($("#chart_img_div").html());
						$("#ex_report_title").val($("#report_title").val());
						$("#ex_from_date").val($("#from_date").val());
						$("#ex_to_date").val($("#to_date").val());
						$("#ex_user_id").val($("#user_id").val());
						$("#ex_division_id").val($("#division_id_report").val());
						$("#ex_department_id").val($("#department_id_report").val());
						$("#ex_category_id").val($("#category_id").val());
						$("#ex_sub_category_id").val($("#sub_category_id").val());
						$("#ex_project_id").val($("#project_id").val());
                                                $("#ex_customer_id").val($("#report_customer_id").val());
						$('#frm_export').submit(); 
					} else {
						$('#frm_export').submit(); 
					}
					
				}, 2000);
				/*
				$.ajax({
									type : 'post',
									url : '<?php //echo site_url("reports/export");?>',
									data : $("#frm_submit_report").serialize(),
									success : function(response){
										//$("#frm_submit_report").setAttribute("target", "_blank");
										window.location.href = response.url; 
									}
								});*/
				
			}
		});
		$("#report_title1").change(function(){
			var a = $(this).val();
			if(a){
				$('#dvLoading').fadeIn('slow');
               	$.ajax({
               		type : 'post',
               		url : '<?php echo site_url("reports/run_reports");?>',
               		data : $("#frm_submit_report").serialize(),
               		success : function(data){
               			
               			//alert(a);
               			$("#ajax_div").css("display","block");
               			if(data == "no_data"){
	               			$("#ajax_report_data").html("<h6 class='heading6'><strong> No Records Found. </strong></h6>");
               				
               			} else {
               				$("#ajax_report_data").html(data);
	               			if(a == "Actual time by category over a period of time"){
	               				google.load("visualization", "1", {"packages": ["corechart"], "callback": drawVisualization1});
	               			}
               			}
               			$('#dvLoading').fadeOut('slow');
               		}
               	});
			}
		});
		$("#frm_export1").submit(function(){
			var report = $("#report_title").val();
			if(report==''){
				alertify.alert("Please select any report.");
				return false;
			}  else {
				$("#ex_graph_image").val($("#chart_img_div").html());
				$("#ex_report_title").val($("#report_title").val());
				$("#ex_from_date").val($("#from_date").val());
				$("#ex_to_date").val($("#to_date").val());
				$("#ex_user_id").val($("#user_id").val());
				$("#ex_division_id").val($("#division_id_report").val());
				$("#ex_department_id").val($("#department_id_report").val());
				$("#ex_category_id").val($("#category_id").val());
				$("#ex_sub_category_id").val($("#sub_category_id").val());
				$("#ex_project_id").val($("#project_id").val());
                                $("#ex_customer_id").val($("#report_customer_id").val());
				return true;	
			}
		});
                $("#from_date").keyup(function(){
                     var val=$("#from_date").val();
                     $("#hide_from_date").val(val);
                    
                });
                $("#from_date").focusout(function(){    
                    
                    var letter = /[a-zA-Z@&_\.]/;  
                    var val=$("#hide_from_date").val();
                    if(val != '')
                    {
                        if(isValidDate(val)==false)
                        {
                            alertify.set("notifier", "position", "top-right"),
                            alertify.log("Please enter date only <?php echo $date_arr_java[$site_setting_date]; ?> format.");
                            //alertify.alert("Please enter date only <?php echo $date_arr_java[$site_setting_date]; ?> format.");
                            $("#from_date").val("");
                            $("#from_date").focus();
                            $("#hide_from_date").val("");
                        }
                        else
                        {
                            $(this).datepicker('setDate', $(this).datepicker('getDate'));
                        }
                    }
                    
                    
                }).datepicker({
                    showOn: "button",
                    maxDate: "+1",
                    showOtherMonths: true,
                    format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
                });
                 $("#to_date").keyup(function(){
                     var val=$("#to_date").val();
                     $("#hide_to_date").val(val);
                    
                });
                $("#to_date").focusout(function(){
                    
                    var letter = /[a-zA-Z@&_\.]/;  
                    var val=$("#hide_to_date").val();
                    if(val != '')
                    {
                        console.log(isValidDate(val));
                        if(isValidDate(val)==false)
                        {
                            alertify.set("notifier", "position", "top-right"),
                            alertify.log("Please enter date only <?php echo $date_arr_java[$site_setting_date]; ?> format.");
                            //alertify.alert("Please enter date only <?php echo $date_arr_java[$site_setting_date]; ?> format.");
                            $("#to_date").val("");
                            $("#to_date").focus();
                            $("#hide_to_date").val("");
                        }
                    }
                    else
                    {
                        $(this).datepicker('show');
                    }
                    
                }).datepicker({
                    showOn: "button",
                    maxDate: "+1",
                    showOtherMonths: true,
                    format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
                });
	});
        function isValidDate(date)
        {
            var matches = /^(\d{<?php echo strlen($split_format[0]);?>})[-\/](\d{<?php echo strlen($split_format[1]);?>})[-\/](\d{<?php echo strlen($split_format[2]);?>})$/.exec(date);
            if (matches == null) return false;
        }
       
</script>


<!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid" style="padding-left:20px;padding-right:20px;">
      <div class="mainpage-container">
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
          <div class="col-md-12">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div class="portlet box blue">
              <div class="portlet-title">
                <div class="caption">Reports </div>
              </div>
              
              <div class="portlet-body flip-scroll">
			  	 <div class="table-toolbar no-margin clearfix">
					<div id="exportdata" class="btn-group no-margin pull-right">
						
						<?php $attributes = array('name'=>'frm_export', 'id'=>'frm_export',"target"=>"_blank");
							echo form_open_multipart('reports/export',$attributes);?>
							<input type="hidden" name="report_title" id="ex_report_title" value="" />
							<input type="hidden" name="from_date" id="ex_from_date" value="" />
							<input type="hidden" name="to_date" id="ex_to_date" value="" />
							<input type="hidden" name="user_id" id="ex_user_id" value="" />
							<input type="hidden" name="division_id" id="ex_division_id" value="" />
							<input type="hidden" name="department_id" id="ex_department_id" value="" />
							<input type="hidden" name="category_id" id="ex_category_id" value="" />
							<input type="hidden" name="sub_category_id" id="ex_sub_category_id" value="" />
							<input type="hidden" name="project_id" id="ex_project_id" value="" />
							<input type="hidden" name="graph_image" id="ex_graph_image" value="" />
                                                        <input type="hidden" name="customer_id" id="ex_customer_id" value="" />
                                                        
							<!--<button type="submit"  name="submit" class="btn green txtbold adjicon"> Export <i class="stripicon iconexport"></i> </button>-->
						</form>
						
						<!--<a href="javascript:void(0);" id="export" class="btn green txtbold adjicon"> Export <i class="stripicon iconexport"></i> </a>-->
					</div>
				 </div>
				 	<div class="form-horizontal form">
						<form name="frm_submit_report" id="frm_submit_report" action=""  >
							
							<?php if(isset($error) && $error != ''){
								?>
								<div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $error; ?></span></div>
							<?php
							}?>
								
                                                       <div class="row">
                                                            <div class="col-xs-offset-1 col-md-11">
                                                                    <div class="form-group m-b-sm">
                                                                        <label class="control-label " ><span>Select Report<span class="required">*</span></span></label>
										<div class="controls">
											<select class="large m-wrap large_select radius-b" name="report_title" id="report_title" tabindex="1" >
												<option value="">--- Select Report --- </option>
												<option value="Time allocation by category">Time allocation by category</option>
												<option value="Last login per user">Last login per user</option>
												<option value="Login history by user">Login history by user</option>
												<option value="List of overdue tasks">List of overdue tasks</option>
												<option value="Activity by Category">Activity by Category</option>
												<option value="Actual time by category over a period of time">Actual time by category over a period of time</option>
												<option value="Time allocated by project">Time allocated by project</option>
												<option value="Tasks due this week by user">Tasks due by user</option>
												<option value="Interruptions by type and by user">Interruptions by type and by user</option>
												<option value="Daily time allocation by user"> Daily time allocation by user</option>
												<option value="Daily Time allocation per category and sub category">Daily Time allocation per category and sub category</option>
												<option value="List of tasks">List of tasks</option>
                                                                                                <option value="My tasks allocated to other users">My tasks allocated to other users</option>
                                                                                                <option value="Timer work log">Timer work log</option>
											</select>
										</div>
									</div>
                                                                </div>
                                                       </div>
                                                                
                                                                
							 <hr>
                                                         <div class="row">
                                                                <h6 class="heading6">  <strong> Filter   </strong>  </h6>
                                                         </div>
                                                         
                              <div class="row">
                                    <div class="col-xs-offset-1 col-md-11">
							 <div class="form-group m-b-sm">
								<label class="control-label " ><span>Date From :</span></label>
								<div class="controls">
									<div class="datLT">
										<div class="input-append date date-picker" data-date="<?php echo $date_to_timezone;?>" data-date-format="<?php echo $date_arr_java[$site_setting_date]; ?>" data-date-viewmode="years">
                                                                                    <input name="from_date" id="from_date" class="m-wrap m-ctrl-medium setHourErr" size="16" type="text" value="" style="width:225px;" maxlength="<?php echo $size;?>" autocomplete="off"/><span class="add-on"><i class="icon-calendar taskppicn" style="color: #000 !important;"></i></span>
                                                                                    <input type="hidden" id="hide_from_date" value=""/>
                                                                                </div>
									</div>
									<div class="dattxt" style=""> To </div>
									<div class="datLT">
										<div class="input-append date date-picker" data-date="<?php echo $date_to_timezone;?>" data-date-format="<?php echo $date_arr_java[$site_setting_date]; ?>" data-date-viewmode="years">
                                                                                    <input name="to_date" id="to_date" class="m-wrap m-ctrl-medium setHourErr " size="16" type="text" value="" style="width:225px;" maxlength="<?php echo $size;?>" autocomplete="off"/><span class="add-on"><i class="icon-calendar taskppicn" style="color: #000 !important;" ></i></span>
                                                                                    <input type="hidden" id="hide_to_date" value=""/>
                                                                                </div>
									</div>
									
								</div>
							</div>
							
							<?php 
								$chk_owner = $this->session->userdata('is_administrator');
								$chk_manager = $this->session->userdata('is_manager');
							?>							
                                                     <div class="row">
                                                         <div class="col-md-12">    
                                                                <div class="form-group m-b-sm">
									<label class="control-label "><span>User :</span></label>
									<div class="controls">
                                                                            <select class="large m-wrap radius-b" name="user_id" id="user_id" tabindex="1" >
											<option value="<?php echo get_authenticateUserID();?>">Me</option>
											<?php if($chk_owner=='1' || $chk_manager == '1') {
												$users = get_users_under_managers();
												if($users){
													foreach($users as $u){
                                                                                                            if($u->user_id != get_authenticateUserID()){
														?>
														<option value="<?php echo $u->user_id;?>"><?php  echo $u->first_name.' '.$u->last_name; ?></option>
														<?php
                                                                                                            }
													}
													?>
													<option value="">My Team</option>
                                                                                                        
													<?php
												}
                                                                                                if($chk_owner=='1'){?>
                                                                                                    <option value="all">All</option>
												<?php }
												
											} else {}  ?>
                                                                            </select>
                                                                         </div>
								</div>
                                                         </div>
                                                       </div>    
								
								
                                                        <div class="row">
                                                            <div class="col-md-6">
										<div class="form-group m-b-sm">
											<label class="control-label" ><span>Division :</span></label>
											<div class="controls">
                                                                                            <select class="large m-wrap radius-b" name="division_id" id="division_id_report" tabindex="1">
													<option value="">All</option>
													<?php if(isset($devision) && $devision!=''){
														foreach($devision as $dev){
															?>
															<option value="<?php echo $dev->division_id;?>"><?php echo $dev->devision_title;?></option>
															<?php
														}
													}?>
												</select>
											</div>
										</div>
                                                            </div>     
                                                            <div class="col-md-6">
										<div class="form-group m-b-sm">
											<label class="control-label" ><span>Department :</span></label>
											<div class="controls">
                                                                                            <select class="large m-wrap radius-b" name="department_id" id="department_id_report" tabindex="1" >
													<option value="">All</option>
													<?php if(isset($departments) && $departments!=''){
														foreach($departments as $dept){
															?>
															<option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_title;?></option>
															<?php
														}
													}?>
												</select>
											</div>
										</div>
                                                            </div>
                                                        </div>    
									
								
                                                        <div class="row">
                                                            <div class="col-md-6">
										<div class="form-group m-b-sm">
                                                                                    <label class="control-label"><span>Category :</span></label>
											<div class="controls">
                                                                                            <select class="large m-wrap radius-b" name="category_id" id="category_id" tabindex="1" >
													<option value="">All</option>
													<?php if(isset($main_category) && $main_category!=''){
														foreach($main_category as $cat){
															?>
															<option value="<?php echo $cat->category_id;?>"><?php echo $cat->category_name;?></option>
															<?php
														}
													}?>
												</select>
											</div>
										</div>
                                                            </div>   
                                                            <div class="col-md-6">
										<div class="form-group m-b-sm">
                                                                                    <label class="control-label" ><span>Sub-Category :</span></label>
											<div class="controls">
                                                                                            <select class="large m-wrap radius-b" name="sub_category_id" id="sub_category_id" tabindex="1" >
													<option value="">All</option>
													<?php if(isset($sub_category) && $sub_category!=''){
														foreach($sub_category as $sub_cat){
															?>
															<option value="<?php echo $sub_cat->category_id;?>"><?php echo $sub_cat->category_name;?></option>
															<?php
														}
													}?>
												</select>
											</div>
										</div>
                                                            </div>
                                                        </div>   
                                                        <div class="row">
                                                            <div class="col-md-6">
								<div class="form-group m-b-sm">
									<label class="control-label " ><span>Projects :</span></label>
									<div class="controls">
										<select class="large m-wrap radius-b" name="project_id" id="project_id" tabindex="1">
											<option value="">All</option>
											<?php if(isset($user_projects) && $user_projects!=''){
												foreach($user_projects as $pr){
													?>
													<option value="<?php echo $pr->project_id;?>"><?php echo $pr->project_title;?></option>
													<?php
												}
											}?>
										</select>
									</div>
								</div>
                                                            </div>
                                                            <div class="col-md-6" style="<?php if($this->session->userdata('customer_module_activation')=='1'){echo "display:block";}else{echo "display:none";}?>">
								<div class="form-group m-b-sm">
									<label class="control-label " ><span>Customers :</span></label>
									<div class="controls">
                                                                                <select class="large m-wrap radius-b" name="customer_id" id="report_customer_id" tabindex="1">
											<option value="">All</option>
											<?php if(isset($customers) && $customers!=''){
												foreach($customers as $cus){
													?>
													<option value="<?php echo $cus->customer_id;?>"><?php echo $cus->customer_name;?></option>
													<?php
												}
											}?>
										</select>
									</div>
								</div>
                                                            </div>
                                                        </div>    
                                     </div>  
				</div>		
							 
                                                         <div class="form-control" style="background-color: #e5e9ec;border-top: 1px solid #e5e5e5;padding: 19px 20px 20px; height:72px;">
								<button type="submit" class="btn blue txtbold"><i class="icon-ok rprticn"></i> Run Report </button>
								<button type="button" id="export" name="submit" class="btn green txtbold"> Export <i class="icon-external-link rprticn"></i> </button>
							 
							 </div>
						</form>
                                        </div>
                </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
           
        </div>
        <!-- END PAGE CONTENT-->
		
		<!-- BEGIN PAGE CONTENT-->
        <div class="row">
          <div class="col-md-12" id="ajax_div" style="display:none;">
			<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/jquery.slimscroll.js?Ver=<?php echo VERSION;?>"></script> 
			<script type="text/javascript">
				$(function(){
//					$('.scroll').slimScroll({
//					color: '#17A3E9',
//			 	    wheelStep: 100,
//			 	     axis: 'both'
//				 });
$('.purple-sharp').click(function(){
    alert('hi');
});
																															
				});
			</script>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">View Data</div>
						
						</div>
						<div class="portlet-body ">
				      <div class="customtable" id="ajax_report_data">
			          	 <?php echo $this->load->view($theme.'/layout/reports/Actualtimebycategoryoveraperiodoftime.php');?>
			          	 </div>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
          </div>
           
        </div>
        <!-- END PAGE CONTENT-->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.js" type="text/javascript"></script>-->
        <!--<script src="<?php echo $theme_url; ?>/assets/scripts/app.js" type="text/javascript"></script>-->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>-->
        
<!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"> </script>-->

      </div>
    </div>
    <!-- END PAGE CONTAINER-->
    
