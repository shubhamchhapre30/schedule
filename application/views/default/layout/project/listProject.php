<?php
	$theme = getThemeName();
	$theme_url = base_url().getThemeName();
?>
<!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid rmBg" style="padding-left:20px;padding-right:20px;">
      <div class="mainpage-container">
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
          <div class="col-md-12">
            <!-- BEGIN BORDERED TABLE PORTLET-->
            <div class="portlet box cstmRam page-background">
              <div class="portlet-title blue">
                <div class="caption">Project List </div>
              </div>
              <div class="portlet-body flip-scroll ">
			  	 <div class="table-toolbar">
                                     <?php if($this->session->userdata('is_customer_user') == '0'){?>
					<div class="btn-group">
                                            <?php  $encryte_id =  $this->encrypt->encode(0);?>
						<form method="POST" style="margin: 0px !important;" action="<?php echo site_url('project/editProject');?>" name="myForm_<?php echo $encryte_id; ?>" id="myForm_<?php echo $encryte_id;  ?>">
						<a onclick="callProject('<?php echo $encryte_id; ?>');" class="btn blue txtbold" href="javascript:void(0)">
						Add Project <i class="icon-plus"></i>
						</a>
                                                    <input type="hidden" name="project_id"  value="<?php echo $encryte_id; ?>" />
						</form>
					</div>
                                        <div class="btn-group search_project">
						<input type="text" class="search form-control" placeholder="Search...">
						<span class="counter"></span>
					</div>
                                     <?php }else{ ?>
					<div class="btn-group">
						<input type="text" class="search form-control" placeholder="Search...">
						<span class="counter"></span>
					</div>
                                     <?php } ?>
					<div class="btn-group pull-right">

						<select onchange="checkStatus(this.value);" name="duration"  id="duration" class=" m-wrap radius-b" tabindex="1">
							<option value="Open">All Active Projects</option>
							<option value="Complete">Completed Projects</option>
							<option value="On_hold">Project On Hold</option>
						</select>
					</div>
				</div>
                <div id="filterView1" class="customtable table-scrollable">
                  <table id = "filtertab_pro" class="table table-striped table-hover table-condensed flip-content results">
                    <thead class="flip-content">
                      <tr>
                      	<th>Project  Name</th>
                      	<?php if($this->session->userdata('customer_module_activation') == 1){
                      		echo'<th>Customer</th>';
                      	}?>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="center" width="25%"></th>
                        <th width="10%"></th>
                      </tr>
                    </thead>
                    <tbody id="filterView">
                    	<?php if($projects){
                    		$task_status_completed_id = $this->config->item('completed_id');
                    		$default_format = $this->config->item('company_default_format');
                    		foreach($projects as $prj){
                                                        $encoded_project_id = $this->encrypt->encode($prj->project_id);
								if($prj->project_start_date!= '0000-00-00' ){
									//$project_start_date = date($site_setting_date,strtotime($prj->project_start_date));
									$hidden_project_start_date = date("Y-m-d",strtotime($prj->project_start_date));
								} else {
									//$project_start_date = "N/A";
									$hidden_project_start_date = "N/A";
								}

								if($prj->project_end_date!= '0000-00-00' ){
									//$project_end_date = date($site_setting_date,strtotime($prj->project_end_date));
									$hidden_project_end_date = date("Y-m-d",strtotime($prj->project_end_date));
								} else {
									//$project_end_date = "N/A";
									$hidden_project_end_date = "N/A";
								}

								if($prj->project_id!=''){
									$tot_task = get_total_task($prj->project_id,'all',$task_status_completed_id);
									$my_task = get_my_task($prj->project_id,get_authenticateUserID(),$task_status_completed_id);
									$tot_upcoming_task = get_total_upcoming_task($prj->project_id,'all',$task_status_completed_id);
									$my_upcoming_task = get_my_upcoming_task($prj->project_id,get_authenticateUserID(),$task_status_completed_id);
									$tot_today_task = get_tot_today_task($prj->project_id,'all',$task_status_completed_id);
									$my_today_task = get_my_today_task($prj->project_id,get_authenticateUserID(),$task_status_completed_id);
									$tot_overdue_task = get_tot_overdue_task($prj->project_id,'all',$task_status_completed_id);
									$my_overdue_task = get_my_overdue_task($prj->project_id,get_authenticateUserID(),$task_status_completed_id);

									}else{

									$tot_task = 0;
									$my_task = 0;
									$tot_upcoming_task = 0;
									$my_upcoming_task = 0;
									$tot_today_task = 0;
									$my_today_task = 0;
									$tot_overdue_task = 0;
									$my_overdue_task = 0;

								}

								 ?>

                    			<tr>
                    				<td><a onclick="callProject('<?php echo $encoded_project_id; ?>');" style="display: block;" href="javascript:void(0);"><?php echo $prj->project_title; ?></a></td>
                    					<?php 
                    				if($this->session->userdata['customer_module_activation']){
                    					echo "<td>";
                    				if(!empty($prj->customer_name)){
                    					echo $prj->customer_name;

                    				}else{
                    					echo'_';
                    				}
                    				echo "</td>";
                    				}
                    				?>
                    				<td><?php echo str_replace("_"," ",$prj->project_status); ?></td>
			                        <td><span class="hidden"><?php echo $hidden_project_start_date;?></span><?php echo date($site_setting_date,strtotime(str_replace(array("/"," ",","), "-", $prj->project_start_date))); ?></td>
			                        <td><span class="hidden"><?php echo $hidden_project_end_date;?></span><?php echo date($site_setting_date,strtotime(str_replace(array("/"," ",","), "-", $prj->project_end_date))); ?></td>
                                                <?php  $zero='0';
                                                             $yellow=$tot_upcoming_task?'yellow':'light_cstm';
                                                             $green=$tot_today_task?'green':'light_cstm';
                                                             $red=$tot_overdue_task?'red':'light_cstm';
                                                
                                                ?>
                                                <td class="center "  > <span class="tasklbl pill_cstm"><span>Upcoming</span><span class="pill_num <?=$yellow?> "><?php if($tot_upcoming_task) {echo $tot_upcoming_task;  }  else {echo $zero;}?></span></span>
                                                    <span class="tasklbl pill_cstm"><span>Today</span><span class="pill_num <?=$green?> "><?php if($tot_today_task){  echo $tot_today_task; } else {echo $zero;}?> </span></span>
                                                    <span class="tasklbl pill_cstm"><span>Overdue</span><span class="pill_num <?=$red?> "><?php if($tot_overdue_task){ echo $tot_overdue_task; } else {echo $zero;}?></span></span>
                                                </td>
                                                <td>
                                                    <form method="POST" style="margin: 0px !important;" action="<?php echo site_url('project/editProject'); ?>" name="myForm_<?php echo $encoded_project_id; ?>" id="myForm_<?php echo $encoded_project_id; ?>">
                                                        <input type="hidden" name="project_id" id="project_id" value="<?php echo $encoded_project_id; ?>" />
                                                    </form>
			                         	<?php if($prj->project_added_by == get_authenticateUserID() || $this->session->userdata('is_owner') == 1 || $prj->is_project_owner == 1){ ?>
                                                            <a onclick="callProject('<?php echo $encoded_project_id; ?>');" href="javascript:void(0);"> <i class="icon-pencil prjcstmicn" style="transform: scale(0.75);"></i> </a>
										 <a href="javascript:void(0);" onclick="delete_project('<?php echo $encoded_project_id; ?>','<?php echo $prj->project_id; ?>','<?php echo $prj->project_title; ?>');" id="delete_project_<?php echo $prj->project_id; ?>"> <i class="icon-trash prjcstmicn" style="transform: scale(0.75);"></i> </a>
                                                    <?php } if ($this->session->userdata('is_customer_user') == '0') { ?>
                                                                                 <a onclick="copyProject('<?php echo $prj->project_id; ?>','<?php echo $prj->project_title; ?>');" href="javascript:void(0);"  class="tooltips" id="copy_project_<?php echo $prj->project_id; ?>" title='Create Copy'> <i class="icon-copy prjcstmicn" style="transform: scale(0.75);"></i> </a>
                                                    <?php } ?>
                                                                                 
						</td>
                                        </tr>
                            <?php } }  ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- END BORDERED TABLE PORTLET-->
          </div>
        </div>
        <!-- END PAGE CONTENT-->
      </div>
    </div>
  <!-- END PAGE CONTAINER-->
    <div id="delete_project" class="modal model-size pro-change fade" tabindex="-1" >
				<div class="portlet">
					<div class="portlet-body  form flip-scroll">
						<div class="modal-header">
							<button type="button" class="close cmt_close" data-dismiss="modal" aria-hidden="true"></button>
							<h3>Delete Project</h3>
						</div>
						<div>
								<div class="addcomment-block">
									<div class="row">
										<div class="col-md-12 ">
										<div>
												<label class="control-label" > There are some open tasks left on the project you are trying to delete. Please confirm what you wish the system to do:</label>
										</div>
											<div class="form-group">
												<label class="control-label" > <strong> Action : </strong></label>
												<div class="controls">
													<select onchange="checkremap(this.value);" class="large m-wrap radius-b" id="delete_status" name="delete_status" tabindex="1" >
														<option value="">-- Select --</option>
														<option value="close" >Close open tasks</option>
														<option value="remap" >Remap open tasks to other project</option>
														<option value="unlink" >Un-link the tasks from the project</option>
														<option value="cancel" >Cancel, do not close the project</option>
													</select>
												  </div>
												  <div id="proj_status" class="controls" style="display: none">
                                                                                                      <select class="large m-wrap radius-b" id="select_project" name="select_project" tabindex="1" style="margin-top: 5px;">
													</select>
												  </div>
											</div>
											<div class="pull-left">
												<input type="hidden" id="project_id" name="project_id" value="" />
												<button type="button" id='confirm_delete' name="confirm_delete" class="btn blue txtbold"> Submit </button>
											</div>
										</div>
									 </div>
								</div>
						</div>
					</div>
				</div>
			</div>
 
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
<script type="text/javascript">

	$(document).ready(function(){

	$('#filtertab_pro').dataTable( {
	  	"order": [[ 0, "asc" ]],
                "paging":   false,
                "bFilter" : false,
                        "bLengthChange": false,
                "info":     false,
                 "language": {
                "emptyTable":     "No Records found."
                }
        } );

	 $('#confirm_delete').click(function(){
    	if($('#delete_status').val()!=''){
		$.ajax({
				type: 'POST',
				url : "<?php echo site_url('project/deleteProject') ?>",
				data:{delete_status:$("#delete_status").val(),project_id:$('#project_id').val(),select_project:$("#select_project").val()},

				success : function(data) {
					if(data=='not done')
					{
						alertify.alert('Project have assigned task , it can not be deleted.');
						$('#delete_project').modal('hide');
						$("#delete_status").val('')
						$('#dvLoading').fadeOut('slow');
					}
                                        else{
                                            if($('#delete_status').val()=='cancel'){
                                                $('#delete_project').modal('hide');
						$("#delete_status").val('')
						$('#dvLoading').fadeOut('slow');
                                            }else
                                            {
						$("#filterView1").html(data);
						$('#delete_project').modal('hide');
						$("#delete_status").val('')
						$('#dvLoading').fadeOut('slow');
						alertify.alert('Project deleted successfully..!!');
                                            }
					}
				},

			});
		}else{
			alertify.alert('Please Select action field');
		}

		});
});
	function copyProject(id,title)
	{
                       $('#copy_project_'+id).confirmation('show').on('confirmed.bs.confirmation',function(){
                                            var filter = $('#duration').val();
                                            if(id!=''){
                                                    $('#dvLoading').fadeIn('slow');
                                            $.ajax({
				type : 'post',
				url : '<?php echo site_url("project/copyProject"); ?>',
				data : {id:id,filter:filter},
				async:false,
				success : function(data){
					$("#filterView1").html(data);
					 $('#filtertab_pro').dataTable( {
		                	"order": [[ 0, "asc" ]],
					        "paging":   false,
					        "bFilter" : false,
							"bLengthChange": false,
					        "info":     false,
					         "language": {
					        "emptyTable":     "No Records found."
					    	}
					    } );
					$('#dvLoading').fadeOut('slow');

                                       toastr['success']("Project "+title+" has been copied successfully.", "");
				},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});
                        }
                        }
                        );

		}

	function checkStatus(status)
	{
		var id = status;
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("project/filterProject"); ?>',
				data : {id:id},
				async:false,
				success : function(data){

					$("#filterView1").html(data);
					 $('#filtertab_pro').dataTable( {
		                	"order": [[ 0, "asc" ]],
					        "paging":   false,
					        "bFilter" : false,
							"bLengthChange": false,
					        "info":     false,
					         "language": {
					        "emptyTable":     "No Records found."
					    	}
					    } );
					$('#dvLoading').fadeOut('slow');
				},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});

		}
	}


	function callProject(project_id)
	{
		document.getElementById("myForm_"+project_id).submit();
	}

	function checkremap(del_id)
	{
		 if(del_id=='remap'){

		  	$.ajax({
					type : 'post',
					url : '<?php echo site_url("project/getProjects"); ?>',
					data : {del_id:del_id,project_id:$('#project_id').val()},
					success : function(responsedata){
					if(responsedata.length>0){
						$("#select_project").html(responsedata);
						$('#proj_status').css('display','block');
						}
					}
				});
	        }else{
	        	$('#proj_status').css('display','none');
	        }

	}

	function delete_project(project_id,pr_id,title)
	{ 
		$('#delete_project_'+pr_id).confirmation('show').on('confirmed.bs.confirmation',function(){
			$('#proj_status').css('display','none');
			$('#dvLoading').fadeIn('slow');

			$.ajax({
                                type : 'post',
                                url : '<?php echo site_url("project/deleteProject"); ?>',
                                data : {delete_status:$("#delete_status").val(),project_id:project_id,select_project:$("#select_project").val()},
                                success : function(data){ 

                                        if(data =='not done')
                                        {
                                                $('#dvLoading').fadeOut('slow');
                                                $('#delete_project').modal();
                                                $('#project_id').val(project_id);
                                        }
                                        else
                                        {
                                                $("#filterView1").html(data);
                                                $('#dvLoading').fadeOut('slow');
							toastr['success']("Project "+title+" has been deleted.", "");
                                        }

                                }
                        });
		}
    	);
	}


	</script>

	<script type="text/javascript">
            $(document).ready(function() {
                         $(".search").keyup(function () {
                                         var searchTerm = $(".search").val();
                                 var listItem = $('.results tbody').children('tr');
                                 var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
                                 $.extend($.expr[':'], {
                                         'containsi': function(elem, i, match, array){
                                         return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                                 }
                         });
                         $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
                               $(this).attr('visible','false');
                              });
                                 $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
                                         $(this).attr('visible','true');
                                 });

                         var jobCount = $('.results tbody tr[visible="true"]').length;
                         $('.counter').text(jobCount + ' item');
                         if(jobCount == '0') {$('.no-result').show();}
                         else {$('.no-result').hide();}
                 });
                 });
         </script>
