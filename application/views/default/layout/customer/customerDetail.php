<?php
        $active_menu='from_customer';
        $site_setting_date = $this->config->item('company_default_format');
        $default_format  = $site_setting_date;
        $date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	if($date_arr_java[$site_setting_date]=='dd M,yyyy'){
            $size=11;
        }else{
            $size=10;
        }
        $completed = $this->config->item('completed_id');
        $theme_url = base_url().getThemeName(); 
        $completed_id = $this->config->item('completed_id');
	$company_flags = $this->config->item('company_flags');
        $actaul_time_on = '0';
        $allow_past_task = "1";
        if($company_flags){
                $actaul_time_on = $company_flags['actual_time_on'];
                $allow_past_task = $company_flags['allow_past_task'];
        }
        if($allow_past_task == "1"){
                $start_date_picker = "-Infinity";
        } else {
                $start_date_picker = "this.date";
        }
        $s3_display_url = $this->config->item('s3_display_url');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/css/context.standalone.css?Ver=<?php echo VERSION;?>">
<script type="text/javascript" src='<?php echo $theme_url; ?>/assets/js/customer<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>'></script>
<style>
 #taskTable tr[visible='false'],
.no-result{
  display:none;
}

#taskTable tr[visible='true']{
  display:table-row;
}

.counter{
  padding:8px; 
  color:#ccc;
}
.table>tbody>tr>td{    padding: 0 8px !important;
    line-height: 0 !important;
    height: 36px !important;
}

</style>

<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/css/context.standalone.css?Ver=<?php echo VERSION;?>">
<script src="<?php echo $theme_url;?>/js/context.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript">
        var status = '';
	var ACTIVE_MENU = 'from_customer';
	var ACTUAL_TIME_ON = '<?php echo $actaul_time_on; ?>';
	var COMPLETED_ID = '<?php echo $completed_id;?>';
	var START_DATE_PICKER = '<?php echo $start_date_picker;?>';
	var DATE_ARR = '<?php echo $date_arr_java[$default_format]; ?>';
	var S3_DISPLAY_URL = '<?php echo $s3_display_url;?>';
        $(document).ready(function(){
            $("#hide_show").on('click',function(){
                $("#show_hide").toggle();
                $(this).find('i').toggleClass("icon-chevron-down");
                $(this).find('i').toggleClass("icon-chevron-right");
            });
        });
        
        function removeCustomerUser(customer_user_id){
            var s = "Are you sure, you want to delete this customer user?";
            $("#alertify").show(), alertify.confirm(s, function(s) {
                return 1 == s && ( void $.ajax({
                    type: "post",
                    url: SIDE_URL + "customer/delete_customer_user",
                    data: {
                        customer_user_id : customer_user_id,
                    },
                    success: function(a) { 
                        $("#customer_user_"+customer_user_id).remove();
                    },
                    error:function(a){
                         console.log("Ajax request not recieved!");
                    }
                }));
            });
        }
        
</script>
<script>
$(document).ready(function(){
    $("#new_project").on("click", function(){
            $("#project_customer_id").val($("#hide_customer_id").val());
            $("#addProject").modal("show");
            $('#addProject').on('shown.bs.modal', function () {
                    $('#project_title').focus();
                }) 
        
    });
     //task search on customer detail screen
    $("#task_search").keyup(function () {
        var searchTerm = $("#task_search").val();
        var listItem = $('#taskTable tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

      $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
      });

      $("#taskTable tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','false');
      });

      $("#taskTable tbody tr:containsi('" + searchSplit + "')").each(function(e){
        $(this).attr('visible','true');
      });

      var jobCount = $('#taskTable tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' item');

      if(jobCount == '0') {$('.no-result').show();}
        else {$('.no-result').hide();}
     });
    
    $("#projectTable").dataTable({
                    order: [
                        [0, "desc"]
                    ],
                    paging: !1,
                    bFilter: !1,
                    searching: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    }
    });
    
    $("#taskTable").dataTable({
                    order: [
                        [2, "desc"]
                    ],
                    paging: !1,
                    bFilter: !1,
                    searching: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    }
    });
    
});

$(document).ready(function(){
		$('#task_actual_time').on('keypress', function( e ) {
			if( e.keyCode === 13 ) {
				e.preventDefault();
	        	var a =$('#task_actual_time').blur()
	        	if(a[0].value){
	        		$("#frm_actual_time").submit();
	        	} else {

	        	}
	        }
		});

		$("#task_actual_time").blur(function(){
			var val = $(this).val();

			var splitval = val.split(":");

			if(val){
				if(parseInt(val)>0){
					if(validate(val) == true) //   && (!$('#manual_reason').hasClass('in'))
					{
						if(splitval.length==2){
							var h = splitval[0];
							var m = splitval[1];
							if(m >= 60){
								var mm1 = parseInt(m / 60);
								var mm2 = m % 60;

								var hh = +h + +mm1;
								var mm = mm2;

								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(hh);
								$("#task_actual_time_min").val(mm);
							}else{
								var hh = h;
								var mm = m;
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(hh);
								$("#task_actual_time_min").val(mm);
							}
						}
						if(val.length>=1 && val.length <=2)
						{
							if(val >= 60){
								var hh = parseInt(val / 60);
								var mm = val % 60;

								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(hh);
								$("#task_actual_time_min").val(mm);
							}else{
								var mm = val;
								var time = mm + "m";
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(0);
								$("#task_actual_time_min").val(mm);
							}
						}

						if(val.length==3 && splitval.length!=2)
						{
							var digits = new Array();
							var digits= (""+val).split("");
							if((digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])>=60)
							{
								var additional = 1;
								var sum = [];
								var mm =  (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])-60;
								var hh = +digits[val.length-val.length]+ +additional;
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(hh);
								$("#task_actual_time_min").val(mm);

							}else{
								var mm = (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)]);
								var hh = digits[val.length-val.length];
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(hh);
								$("#task_actual_time_min").val(mm);
							}
						}

						if(val.length==4 && splitval.length!=2)
						{
							var digits = new Array();
							var digits= (""+val).split("");
							if((digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])>=60)
							{
								var additional = 1;
								var sum = [];
								var mm =  (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])-60;
								var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)])+ +additional;
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(hh);
								$("#task_actual_time_min").val(mm);

							}else{

								var mm = (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)]);
								var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)]);
								if(hh==0){
									var time = mm+"m";
								} else if(mm==0){
									var time = hh+"h";
								} else{
									var time = hh + "h "+ mm+"m";
								}
								$("#task_actual_time").val(time);
								$("#task_actual_time_hour").val(hh);
								$("#task_actual_time_min").val(mm);
							}
						}
						if(val.length>=5 && splitval.length!=2){
							$("input[name='task_time_spent']").val('');
							is_edited = '1';
							alertify.alert('maximum 4 digits allowed');
						}
					} else {
						$("#task_actual_time").val('');
						alertify.alert('your inserted value is not correct, please insert correct value');
					}
				} else {
					$("#task_actual_time").val('');
					alertify.alert('Please enter greater than 0 time.');
				}
			}
		});
		$("#frm_actual_time").validate({
			rules : {
				"task_actual_time" : {
					required : true
				}
			},
			errorPlacement: function (error, element) {
				error.insertAfter( element.parent("div") );
			},
			submitHandler:function(){
				//$('#dvLoading').fadeIn('slow');
				var task_actual_time_task_id = $("#task_actual_time_task_id").val();
				var orig_data = $('#task_data_'+task_actual_time_task_id).val();
				var id = $("#select_task_assign").val();
                                var filter = $("#select_task_status").val();
				$.ajax({
					type : 'post',
					url : '<?php echo site_url("kanban/add_actual_time");?>',
					data : $("#frm_actual_time").serialize(),
					success : function(data){

						//$("#tasklist_"+task_actual_time_task_id).replaceWith(data);

						$("#actual_time_task").modal("hide");


						orig_data = jQuery.parseJSON(orig_data);
						//console.log(orig_data);

						var c = jQuery.parseJSON(data);
                             $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "customer/set_update_task",
                                    data: {
                                        task_id: c.task_id,
                                        redirect_page : $('#redirect_page').val(),
                                        customer_id:$("#hide_customer_id").val()
                                        },
                                    success: function(taskdiv) {
                                        $("#listtask_" + task_actual_time_task_id).length ? $("#listtask_" + task_actual_time_task_id).replaceWith(taskdiv) : '';
                                    },
                                    error: function(e) {
                                        console.log("Ajax request not recieved!")
                                    }
                            });

					}
				});
			}
		});

		$(".close_actual_time_task").click(function(){
			var task_actual_time_task_id = $("#task_actual_time_task_id").val();
			$("#task_tasksort_"+task_actual_time_task_id).find("input[type='checkbox']").prop('checked',false);
			$("#task_tasksort_"+task_actual_time_task_id).find("span").removeClass('checked');
			$("#actual_time_task").modal("hide");
		});
	});
        
        function show_customer_user_modal(){
            $("#customer_user_first").val('');
            $("#customer_user_first-error").remove();
            $("#customer_user_last").val('');
            $("#customer_user_last-error").remove();
            $("#customer_user_mail").val('');
            $("#customer_user_mail-error").remove();
            var view = '<option value="<?php echo $customer_data['customer_id']; ?>" selected="selected"><?php echo $customer_data['customer_name']; ?></option>';
            $("#parent_customer").html(view);
            $("#customer_user_update").hide();
            $("#access_page").val('Customer');
            $("#AddCustomerUsers").modal('show'); 
        }
</script>

<div class="portlet box list_cus page-background customer_module_divide_first" style="margin-bottom:50px !important;<?php if($this->session->userdata('external_user_access') == 1){ echo "float: left;width: 75%;";} ?>">
    <div class="portlet-body  form flip-scroll customer_transparency">
       
        <div class="col-md-12" style="margin-bottom:9px;">
            <a href="index"  class="pull-left " style="padding-top: 8px;"> Return to Customer List</a>
            <?php if($this->session->userdata('customer_access') == '0'){?>
                <a href="javascript:void(0)"  class="btn btn-common-red pull-right margin5 not_access btn-new" style="margin-right:-4px;"> Delete</a>
                <a href="javascript:void(0)"  class="btn btn-common-blue pull-right margin5 not_access btn-new"> Edit</a>
            <?php }else{?>
                <a href="javascript:void(0)" onclick="removeCustomer('<?php echo $customer_data['customer_id'];?>');" id="delete_customer_<?php echo $customer_data['customer_id'];?>" class="btn btn-common-red pull-right margin5 btn-new" style="margin-right:-4px;" data-toggle="confirmation" data-placement="left"> Delete</a>
                <a href="javascript:void(0)" onclick="editCustomer('<?php echo $customer_data['customer_id'];?>');" class="btn btn-common-blue pull-right margin5 btn-new"> Edit</a>
            <?php }?>
            <input type="hidden" id="current_date" name="current_date" value="<?php echo date('Y-m-d');?>"/>
            <input type="hidden" name="active_menu" id="active_menu" value="<?php  echo $active_menu;?>"/>
            <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $active_menu;?>" />
            <input type="hidden" name="hide_customer_id" id="hide_customer_id" value="<?php echo $customer_data['customer_id'];?>"/>
            <?php if($this->session->userdata('customer_access') == '0'){?>
                <?php if($customer_data['status']=='active'){?>
                    <a href="javascript:void(0)" id="deactive"  class="btn white pull-right margin5 not_access btn-new height34" > Deactivate</a>
                <?php }else{?>
                    <a href="javascript:void(0)" id="active"  class="btn white pull-right margin5 not_access btn-new height34" > Activate</a>
                <?php }}else{?>
                    <?php if($customer_data['status']=='active'){?>
                    <a href="javascript:void(0)" id="deactive" onclick="deactivate('<?php echo $customer_data['customer_id'];?>');" class="btn white pull-right margin5 btn-new height34" style="border: 1px solid #e5e5e5 !important;"> Deactivate</a>
                <?php }else{?>
                    <a href="javascript:void(0)" id="active" onclick="activate('<?php echo $customer_data['customer_id'];?>');" class="btn white pull-right margin5 btn-new height34" style="border: 1px solid #e5e5e5 !important;"> Activate</a>
                <?php }}?>     
        </div>
       
        <div id="customerdetails" class="col-md-12 ">
            <div id="customer_info">
                
                <input type="hidden" name="hide_customer_name_<?php echo $customer_data['customer_id'];?>" id="hide_customer_name_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['customer_name'];?>" />
                <input type="hidden" name="hide_external_id_<?php echo $customer_data['customer_id'];?>" id="hide_external_id_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['external_id'];?>" />
                <input type="hidden" name="hide_owner_id_<?php echo $customer_data['customer_id'];?>" id="hide_owner_id_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['owner_id'];?>"/>
                <input type="hidden" name="hide_first_name_<?php echo $customer_data['customer_id'];?>" id="hide_first_name_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['first_name'];?>"/>
                <input type="hidden" name="hide_last_name_<?php echo $customer_data['customer_id'];?>" id="hide_last_name_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['last_name'];?>"/>
                <input type="hidden" name="hide_phone_<?php echo $customer_data['customer_id'];?>" id="hide_phone_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['phone'];?>"/>
                <input type="hidden" name="hide_email_<?php echo $customer_data['customer_id'];?>"  id="hide_email_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['email'];?>"/>
                <input type="hidden" name="hide_parent_customer_id_<?php echo $customer_data['customer_id'];?>" id="hide_parent_customer_id_<?php echo $customer_data['customer_id'];?>" value="<?php echo $customer_data['parent_customer_id'];?>"/>
                <div class="row cus_info" style="margin-left: -5px;">
                <div class="col-md-12 cus_heading" style="width:99.1%;">
             
                <div class="col-md-6" >
                    
                    <h5 class='txtbold' style="font-size:18px;"><b><?php echo ucfirst($customer_data['customer_name']);?></b>
                        <span style="font-size: 15px;">(<?php echo $customer_data['customer_id'];?>)</span>
                    </h5>
                                 
                </div>
                <div class="col-md-6" style="padding-top:10px;">
                   
                </div>
            </div>
                </div>
                <div class="row" style="padding-top: 10px; margin-left: -2px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label class="control-label txtbold"><b>External ID</b> </label>
                            </div>
                            <div class="col-md-6">
                                 <?php echo $customer_data['external_id'];?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label class="control-label txtbold"><b>Contact</b> </label>
                            </div>
                            <div class="col-md-6"> 
                                <?php echo $customer_data['first_name'].' '.$customer_data['last_name'];?>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label class="control-label txtbold"><b>Owner</b> </label>
                            </div>
                            <div class="col-md-6">
                                 <?php echo $customer_data['ownername'];?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label class="control-label txtbold"><b>Phone</b> </label>
                            </div>
                            <div class="col-md-6">
                                <?php echo $customer_data['phone'];?>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label class="control-label txtbold"><b>Parent customer</b> </label>
                            </div>
                            <div class="col-md-6">
                                <?php if(isset($customer_data['name'])){echo $customer_data['name'];}else{echo "";}?>
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label class="control-label txtbold"><b>Email</b> </label>
                            </div>
                            <div class="col-md-6">
                                <?php echo $customer_data['email'];?>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div id="customerProject" class="row margin5" >
            <div class="col-md-12 cus_heading margin5" style="width:99%;">
                <div class="col-md-6">
                    <h5 class='txtbold'><b>Projects</b></h5>
                </div>
                <div class="col-md-6" style="padding-top:10px;">
                    <span>
                        <a class="pull-right"  href="javascript:void(0);"  id="expand_data_1" onclick="expand_data('1')" >
                            <span id="expand_1"><b><i class="icon-minus" style="color: #fff !important;"></i> </b></span>
                        </a>
                    </span>
                    <span>
                        <button type="button" class="subsection_btn btn-new unsorttd" style="float:right;margin-right:10px;padding:1px 9px 2px 8px; margin-top:-3px;" id="new_project"> Add Project</button>
                    </span>
                </div>
            </div>
            <div class=" col-md-12 customtable table-scrollable margin5" id="projectData_1" style="display:block;margin-left: 0px !important">
                <div id='page_project'>
                 <?php if(isset($projects) && $projects!=''){?>
                    <div class="" >
                                    <table class="table table-striped table-hover table-condensed flip-content" id="projectTable">
                                    <thead class="flip-content">
                                      <tr>
                                            <th>Project Name</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <?php if($this->session->userdata('pricing_module_status')=='1'){?>
                                            <th>Estimated Cost</th>
                                            <th>Estimated Revenue</th>
                                            <th>Committed Revenue</th>
                                            <?php }?>

                                      </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($projects) && $projects!=''){
                                                foreach ($projects as $row){ ?>
                                            <tr>    
                                                <td>
                                                    <form method="POST" style="margin: 0px !important;" action="<?php echo site_url('project/editProject');?>" name="myProject_<?php echo $row['project_id'];?>" id="myProject_<?php echo $row['project_id'];?>">
                                                        <input type="hidden" name="project_id" id="project_id" value="<?php echo $this->encrypt->encode($row['project_id']);?>" />
                                                    </form>
                                                    <a href="javascript:void(0)" onclick="callMyproject(<?php echo $row['project_id'];?>);"><?php echo $row['project_title'];?></a>
                                                    
                                                </td>
                                                <td><?php echo $row['project_status'];?></td>
                                                <td><?php echo date($site_setting_date,strtotime($row['project_start_date']));?></td>
                                                <td><?php echo date($site_setting_date,strtotime($row['project_end_date']));?></td>
                                                
                                                <?php   if($this->session->userdata('pricing_module_status')=='1'){
                                                        $estimated_revenue = 0;
                                                        $estimated_cost = 0;
                                                        $committed_revenue = 0;
                                                        $total_task = $this->project_model->get_all_project_task($row['project_id']);
                                                        if(!empty($total_task)){
                                                        foreach($total_task as $task){
                                                            $employee_rate = $task['cost_per_hour'];
                                                            $estimated_cost += round(($task['task_time_estimate']*$employee_rate)/60,2);
                                                            $estimated_revenue += $task['estimated_total_charge'];
                                                            if($task['task_status_id']==$completed){
                                                                   $committed_revenue += $task['actual_total_charge'];
                                                            }
                                                           
                                                        }}
                                                         $id = get_authenticateUserID();
                                                           $is_owner = is_user_owner(get_authenticateUserID());
                                                           $is_project_owner = is_user_project_owner(get_authenticateUserID(),$row['project_id']);
                                                        ?>
                                                <td><?php if($is_owner == '1' || $is_project_owner == '1'){?><label class="control-label"><?php echo $this->session->userdata('currency');?></label><?php echo $estimated_cost; }else echo '-';?></td>
                                                <td><?php if($is_owner == '1' || $is_project_owner == '1'){?><label class="control-label"><?php echo $this->session->userdata('currency');?></label><?php echo $estimated_revenue;}else echo '-';?></td>
                                                <td><?php if($is_owner == '1' || $is_project_owner == '1'){?><label class="control-label"><?php echo $this->session->userdata('currency');?></label><?php echo $committed_revenue;}else echo '-';?></td>
                                                <?php }?>
                                            </tr>
                                            <?php }}?>

                                    </tbody>
                                    </table>
                    </div>
               
                    
                <?php }else{?>
                    <div class="col-md-12">

                        <label class="control-label ">No project assigned to this customer </label>
                    </div>
                <?php }?>
                <?php if(isset($projects) && $projects!='' && $total_page1>1){?>
                <div align="center" >
                                <ul class='pagination text-center' id="pagination_project">
                                    <?php if(!empty($total_page1) && $total_page1>1){for($i=0; $i<$total_page1; $i++){  
                                                if($i == 0){?>
                                                 <li class='active'  id="project_<?php echo $i;?>"><a href='javascript:void(0)' onclick="getprojectdata(<?php echo $i;?>)"><?php echo $i+1;?></a></li> 
                                                <?php }else{?>
                                                        <li id="project_<?php echo $i;?>"><a href='javascript:void(0)'  onclick="getprojectdata(<?php echo $i;?>)"><?php echo $i+1;?></a></li>
                                                 <?php }?>          
                                    <?php }}?>  
                                </ul>                    
               </div>
                <?php }?>
            </div>
                

        </div>
    
       </div> 
        <div id="customerTask" class="row margin5">
            <div class="col-md-12 cus_heading margin5" style="width:99%;">
                <div class="col-md-6">
                    <h5 class="txtbold pull-left"><b>Tasks</b></h5>
                </div>
                <div class="col-md-6" style="padding-top:10px">
                    <a class="pull-right"  href="javascript:void(0);" id="expand_data_2" onclick="expand_data('2')">
                        <span id="expand_2"><b><i class="icon-minus" style="color: #fff !important;"></i> </b></span>
                    </a>
                    <span>
                        <button type="button" class="subsection_btn btn-new unsorttd" style="float:right;margin-right:10px;padding:1px 9px 2px 8px; margin-top:-3px;" onclick="add_task('<?php echo $customer_data['customer_id'];?>','<?php echo date('Y-m-d');?>')"> Add Task</button>
                    </span>
                </div>
            </div>
    
            
          <div class="customtable table-scrollable form-horizontal" id="taskData_2" style="display:block" >
             
              <div class="col-md-12 customer_task_filter" id="task_filter_option" style="display:<?php if(isset($tasks) && $tasks!=''){echo 'block';}else if(isset($completed_tasks) && $completed_tasks!=0) echo 'block'; else echo 'none';?>">
                  <div class="col-md-3" style="padding-left: 5px !important;">     
                        <input class="onsub m-wrap cus_input " name="task_search" id="task_search" placeholder="Search" value="" type="text"  tabindex="1" style="margin-top: 3px;padding: 0px 6px 4px 6px !important;"/>
                    </div>
                   <div class="col-md-2">
                        <label class="control-label col-md-5" style="font-weight: 600 !important;font-size:13px;padding-left: 0px;padding-top: 4px !important;">Status </label>
                        <select class="col-md-7 m-wrap no-margin  radius-b" onchange="filter();" name="customer_task_status_id" id="customer_task_status_id" tabindex="3" style="margin-top: 3px !important;height:25px !important;padding:1px;" >
                                 <option value="0">Open</option>
                                    <?php if(isset($task_status) && $task_status !=''){
                                             foreach($task_status as $list){?>
                                                    <option value="<?php echo $list->task_status_id?>"  ><?php echo $list->task_status_name?></option>
                                    <?php }}?>									
                            </select>

                    </div>
                    <div class="col-md-3">
                        <label class="control-label col-md-6" style="font-weight: 600 !important;font-size:13px;padding-top: 4px !important;">Owner </label>
                        <select class="col-md-6 m-wrap no-margin  radius-b" onchange="filter();" name="owner_id" id="owner_id" tabindex="3" style="margin-top: 3px !important;height:25px !important;padding:1px;" >
                                 <option value="0">All</option>
                                    <?php if(isset($user) && $user !=''){
                                             foreach($user as $list){?>
                                                    <option value="<?php echo $list['user_id'];?>"  ><?php echo $list['first_name'].' '.$list['last_name'];?></option>
                                    <?php }}?>									
                            </select>

                    </div>
                    <div class="col-md-3">
                        <label class="control-label col-md-6" style="font-weight: 600 !important;font-size:13px;padding-top: 4px !important;">Allocated </label>
                        <select class="col-md-6 m-wrap no-margin  radius-b" onchange="filter();" name="allocated_id" id="allocated_id" tabindex="3" style="margin-top: 3px !important;height:25px !important;padding:1px;" >
                                 <option value="0">All</option>
                                    <?php if(isset($user) && $user !=''){
                                             foreach($user as $list){?>
                                                    <option value="<?php echo $list['user_id'];?>"  ><?php echo $list['first_name'].' '.$list['last_name'];?></option>
                                    <?php }}?>									
                            </select>

                    </div>
                    <div class="col-md-1">
                        <div class="col-md-6">
                             <a href="javascript:void(0)" onclick="reset();" class="btn blue btn-new pull-right line_height" style="margin-top: 3px;margin-right: -45px;"> Reset</a> 
                        </div>
                   </div>    

              </div>
             
            <div id="task_page">
               <?php  if(isset($tasks) && $tasks!='')
               {?>
                <div class="col-md-12" id="task_data" style="padding-left: 10px !important;padding-right: 20px !important;">

                                    <table class="table table-striped table-hover table-condensed flip-content margin5" id="taskTable">
                                    <thead class="flip-content">
                                      <tr>
                                            <th>Task Name</th>
                                            <th>Status</th>
                                            <th>Scheduled date</th>
                                            <th>Due Date</th>
                                            <th>Allocated to</th>
                                            <th>Owner</th>
                                            <th>Action</th>

                                      </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if($tasks){
                                                foreach ($tasks as $row){
                                                    $current_date=date('Y-m-d');
                                                    if (strpos($row['task_id'],'child') !== false) {
                                                            $chk = "0";
                                                    } else {
                                                            $chk = "1";
                                                    }
                                                    $due_date = check_task_exist_today($row['task_id']);
                                                    if($due_date==$current_date){

                                                    }else{
                                                if($row['task_due_date'] == '0000-00-00'){
                                                    $due_date1 = '';
                                                }else{ 
                                                    $due_date1 =date("m-d-Y",strtotime($row['task_due_date']));
                                                }
                                                if($row['task_scheduled_date'] == '0000-00-00'){
                                                    $scheduled_date = '';
                                                }else{ 
                                                    $scheduled_date =date("m-d-Y",strtotime($row['task_scheduled_date']));
                                                }
                                                $jsonarray=array(
                                                    "task_status" =>$task_status,
                                                    "user_colors" =>$color_codes,
                                                    "user_swimlanes" =>$swimlanes,
                                                    "task_id" =>$row['task_id'],
                                                    "locked_due_date" => $row['locked_due_date'],
                                                    "task_due_date" =>$due_date1,
                                                    "task_scheduled_date" =>$scheduled_date,
                                                    "date" =>strtotime($current_date), 
                                                    "active_menu" =>$active_menu,
                                                    "start_date" =>'',
                                                    "end_date" =>'',
                                                    "master_task_id" =>$row['master_task_id'],
                                                    "is_master_deleted" =>'',
                                                    "chk_watch_list" =>'',
                                                    "task_owner_id" =>$row['task_owner_id'],
                                                    "completed_depencencies" =>'',
                                                    "color_menu" =>'',
                                                    "swimlane_id" =>'',
                                                    "task_status_id" => $row['task_status_id'],
                                                    "before_status_id" => '',
                                                    "customer_id" =>$customer_data['customer_id']
                                                );
                                       
                                                    if($row['frequency_type']=='recurrence'){
                                                        $occurence_start_date= get_task_occurence_date($row['task_id']); 
                                                        //echo $occurence_start_date;

                                                        $date1=date_create($current_date);
                                                        $date2=date_create($occurence_start_date);
                                                        $diff=date_diff($date1,$date2);
                                                        $days = $diff->d;
                                                        $task_id = "child_".$row['task_id']."_".$days;
                                                        $jsonarray['task_id'] = $task_id;
                                                        $jsonarray['master_task_id'] = $row['task_id'];
                                                        $is_master_deleted = chk_master_task_id_deleted($row['task_id']);
                                                        $row['master_task_id']=$row['task_id'];
                                                        $row['task_id'] = $task_id;
                                                        $row['task_orig_scheduled_date']=$current_date;
                                                        $row['task_orig_due_date'] = $current_date;
                                                        $row['task_due_date']=$current_date;
                                                        $row['task_scheduled_date']=$current_date;
                                                        $jsonarray['is_master_deleted'] = $is_master_deleted;
                                                        $jsonarray['task_due_date'] = date("m-d-Y",strtotime($current_date));
                                                        $jsonarray['task_scheduled_date'] = date("m-d-Y",strtotime($current_date));
                                                        
                                                    //$is_master_deleted=$tasks['tm'];
                                                    ?>
                                    
                                  
                                                    <tr id="listtask_<?php echo $row['task_id'];?>">
                                    
                                                        <td id="remove_context"><div id="task_<?php echo $row['task_id'];?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');"><a  onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $row['task_title'];?></a></div></td>
                                                        <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $row['task_status_name'];?></a></td>
                                                        <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo date($site_setting_date,strtotime($current_date));?></a></td>
                                                        <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo date($site_setting_date,strtotime($current_date));?></a></td>
                                                        <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo $row['allocated_user_name'];?></a></td>
                                                        <td><a onclick="open_seris(this,'<?php echo $row['task_id'];?>','<?php echo $row['master_task_id'];?>','0');" href="javascript:void(0)"><?php echo usernameById($row['task_owner_id']); ?></a></td>
                                                        <td>
                                                            <input type="hidden" name="child_task_id" id="child_task_id" value="<?php echo $row['task_id'];?>"/>
                                                            <input type="hidden" id="task_data_<?php echo $row['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($row)); ?>" />
                                                            <a href="javascript:void(0);" onclick="deleteTask('<?php echo $row['task_id']?>');" id="delete_task_<?php echo $row['task_id']?>"> <i class="icon-trash tmsticn" style="transform: scale(0.75);"></i> </a>  
                                                        </td>
                                    
                                                    </tr>
                                    
                                                    <?php }else{ ?>
                                                    <tr id="listtask_<?php echo $row['task_id'];?>" >

                                                            <td id="remove_context"><div id="task_<?php echo $row['task_id'];?>" oncontextmenu="context_menu('<?php echo htmlspecialchars(json_encode($jsonarray));?>');"><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"><?php echo $row['task_title'];?> </a></div></td>
                                                                <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"> <?php echo $row['task_status_name'];?></a></td>
                                                                <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"> <?php if($row['task_scheduled_date']!='0000-00-00'){echo date($site_setting_date,strtotime($row['task_scheduled_date']));}else{echo '-';}?></a></td>
                                                                <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"> <?php if($row['task_due_date'] != '0000-00-00'){echo date($site_setting_date,strtotime($row['task_due_date']));}else{echo '-';}?></a></td>
                                                                <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"> <?php echo $row['allocated_user_name'];?></a></td>
                                                                <td><a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $row['task_id'];?>','<?php echo $chk;?>');"> <?php echo usernameById($row['task_owner_id']); ?></a></td>

                                                                <td>
                                                                    <input type="hidden" id="task_data_<?php echo $row['task_id'];?>" value="<?php echo htmlspecialchars(json_encode($row)); ?>" />
                                                                    <a href="javascript:void(0);" onclick="deleteTask('<?php echo $row['task_id']?>');" id="delete_task_<?php echo $row['task_id']?>"> <i class="icon-trash tmsticn" style="transform: scale(0.75);"></i> </a>  
                                                                </td>
                                                                
                                                         </tr>

                                            <?php }}?>
                                                <?php }
                                            }?>
                                    </tbody>
                                    </table>
                    </div>
               <?php }
               else{?>
                    <div class="col-md-12">

                            <label class="control-label ">No task assigned to this customer </label>
                    </div>
            <?php }?>
                    <div align="center" style="margin-bottom:-9px;">
                                    <ul class='pagination text-center' id="pagination_task">
                                        <?php if(!empty($total_page2) && $total_page2>1){for($i=0; $i<$total_page2; $i++){  
                                                    if($i == 0){?>
                                                     <li class='active'  id="task_<?php echo $i;?>"><a href='javascript:void(0)' onclick="gettaskdata(<?php echo $i;?>)"><?php echo $i+1;?></a></li> 
                                                    <?php }else{?>
                                                            <li id="task_<?php echo $i;?>"><a href='javascript:void(0)'  onclick="gettaskdata(<?php echo $i;?>)"><?php echo $i+1;?></a></li>
                                                     <?php }?>          
                                        <?php }}?>  
                                    </ul>                    
                   </div>
                </div>
           
                </div>
        </div>
            
        
    </div>
</div>
<?php if($this->session->userdata('external_user_access') == 1){ ?>
<div class="customer_module_divide_second">
    <div>
        <div class="col-md-12 panel-heading_pro " id="hide_show">
            <a href="javascript:void(0);" >
                <span><i class="icon-chevron-down default_color" ></i></span>
                <label class="control-label default_color" >External Users</label>
            </a>
        </div>
        <div class="portlet-body form flip-scroll radius-b" id="show_hide" style="border-radius: 7px !important;background-color: #F0F0F0;">
            <div id="customer_user_list" >
                <div class="row" >
                    <div class="col-md-12" style="padding-top: 10px;">
                        <div class="people-list" >
                            <ul class="list-unstyled" id="add_new_customer_user">
                                <?php if(isset($customer_users)){
                                        foreach ($customer_users as $users){ ?>
                                            <li class="customer-user_li" id="customer_user_<?php echo $users->user_id; ?>">
                                                <div class="people-block">
                                                    <div class="people-img">
                                                       <?php  if($users->profile_image != ''){ ?>
                                                            <img src="<?php echo $s3_display_url.'upload/user/'.$users->profile_image; ?>" class="img-polaroid img-circle" >
                                                       <?php }else{ ?>
                                                            <img src="<?php echo $s3_display_url.'upload/user/no_image.jpg'; ?>" class="img-polaroid img-circle" >
                                                       <?php } ?> 
                                                       <?php if($this->session->userdata('is_owner') == '1' && 0 == chk_customerUser_task($users->user_id)){ ?>
                                                            <a onclick="removeCustomerUser('<?php echo $users->user_id;?>');" href="javascript:void(0)" >
                                                            <i class="stripicon iconredcolse"></i>    </a>
                                                       <?php } ?>     
                                                       <p> <?php echo ucwords($users->first_name)." ".ucwords($users->last_name);?> </p>
                                                    </div>
                                                </div>
                                            </li>
                                <?php } } ?>            
                            </ul>
                        </div>
                        <?php if($this->session->userdata('is_owner') == '1'){ ?>
                            <div class="people-block">
                                <a class="btn btn-new green" href="javascript:void(0);" onclick="show_customer_user_modal();" >Invite Customers</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>   
        </div>
    </div>    
</div>
<?php } ?>

<div id="newcustomer" class="modal cus_model fade customecontainer" tabindex="-1">
    <?php  $this->load->view($theme.'/layout/customer/addCustomer') ?>
</div>

<div id="AddCustomerUsers" class="modal cus_model fade customecontainer" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <?php  $this->load->view($theme.'/layout/customer/editCustomer') ?>
</div>


<div id="addProject" class="modal model-size actual-time fade customecontainer" tabindex="-1">
				<div class="modal-header">
					<button type="button" class="close close_actual_time_task" data-dismiss="modal" aria-hidden="true"></button>
					<h3> New Project</h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
                                            <div class="portlet-body  form flip-scroll" > 
                                                <form name="customer_project_add" id="customer_project_add" method="POST" action="<?php echo site_url('customer/saveProject');?>">
								<div class="form-group">
                                                                    <label class=" col-md-4" style="padding: 5px;"><strong>Project Name:</strong></label>
									<div class="col-md-8">
                                                                            <input class=" m-wrap m-ctrl-small large_input" name="project_title" id="project_title" placeholder="" value="" type="text"  tabindex="1" required="true"/>
                                                                                <input type="hidden" id="project_customer_id" name="project_customer_id" value=""/>
                                                                                <input type="hidden" id="project_status" name="project_status" value="open"/>
                                                                                <input type="hidden" id="project_start_date" name="project_start_date" value="<?php echo date('Y-m-d');?>"/>
                                                                                <input type="hidden" id="project_end_date" name="project_end_date" value="<?php echo date('Y-m-d');?>"/>
                                                                                <input type="hidden" id="division_id" name="division_id" value="0"/>
                                                                                <input type="hidden" id="department_id" name="department_id" value="0"/>
                                                                                <input type="hidden" id="project_desc" name="project_desc" value=""/>
									</div>
								</div>
                                                            <div class="pull-right row" style="margin:10px;">
                                                                <input class="btn blue txtbold" type="submit"  value="Add Project"/>
                                                            </div>
								
							</form>
						</div>
					</div>
				</div>
</div>

<div id="deleteTask" class="modal model-size pro-change fade" tabindex="-1">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3> Delete Task  </h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
						<div class="portlet-body  form flip-scroll">
							
							<div class="form-group"  style="padding:10px;">
                                                            <label class="control-label col-md-12" style="padding-left:0px;">Do you want to delete the series or occurrence tasks?</label>
								<label class="control-label">Select :</label>
                                                                  <input type="hidden" name="id" id="id" value=""/>
								<div class="controls">
									<label class="radio">
                                                                            <input type="radio"  name="delete_option" value="series" onclick="deleteData()">Task Series
									</label>
									<label class="radio">
									    <input type="radio" name="delete_option" value="occurrence" onclick="deleteData()">Task Occurrence
									</label>
                                                                        
								</div>
							</div>
						</div>
					</div>
				</div>
</div>

<div id="delete_task" class="modal model-size pro-change fade" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Delete Task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
				<div class="portlet-body  form flip-scroll">

                                    <div class="form-group" style="padding:10px">
                                        <label class="control-label col-md-12" style="padding-left:0px;">Do you want to delete the series, this occurence or only future tasks?</label>
						<label class="control-label">Select :</label>
						<div class="controls">
							<label class="radio">
								<a id="delete_series" href="javascript:void(0);" ><input type="radio" value="" ></a>Task Series
							</label>
							<label class="radio">
								<a id="delete_ocuurence" href="javascript:void(0);" ><input type="radio" value="" ></a>Task Occurrence
							</label>
                                                        <label class="radio">
								<a id="delete_future" href="javascript:void(0);" ><input type="radio" value="" ></a>Future Tasks
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<div id="actual_time_task" class="modal project_actual_time_popup fade customecontainer" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close close_actual_time_task" data-dismiss="modal" aria-hidden="true"></button>
			<h3> Actual time of task  </h3>
		</div>
		<div class="modal-body">
			<div class="portlet">
				<div class="portlet-body  form flip-scroll">
					<form name="frm_actual_time" id="frm_actual_time" method="post">
                                                <div class="form-group col-md-12" style="margin-top: 10px;">
                                                    <label class="control-label col-md-4" style="margin-top: 6px;padding-left: 13px !important;">Enter Actual Time : </label>
							<div class="controls col-md-8">
								<input class="onsub m-wrap m-ctrl-small small_input" name="task_actual_time" id="task_actual_time" placeholder="0h" value="" type="text"  tabindex="1" /><span class="word_set">time(ex. 130 for 1h30)</span>
								<input type="hidden" name="task_actual_time_hour" id="task_actual_time_hour" value="" />
								<input type="hidden" name="task_actual_time_min" id="task_actual_time_min" value="" />
							</div>
						</div>
                                                <div class="col-md-12" style="margin:8px;">
							<div class="col-md-6">
								<input type="hidden" name="task_id" id="task_actual_time_task_id" value="" />
								<input type="hidden" name="task_data" id="task_actual_time_task_data" value="" />
								<button type="submit" class="btn blue txtbold"> Save </button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
</div> 