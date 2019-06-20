<?php $total_status = get_total_taskStatus($this->session->userdata('company_id')); ?> 
<?php $theme_url = base_url().getThemeName(); ?>

<script>

	$(document).ready(function() { 
            var table =  $("#status_table").dataTable({
                    paging: !1,
                    bFilter: !1,
                    searching: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    },
                    rowReorder:true
                });
                table.on( 'row-reorder.dt', function ( e, diff, edit ) { 
                    var new_position = edit.values;
                    $.ajax({
			url:SIDE_URL+'settings/updateTaskSequence',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	});
</script>
                <div class="form-group">
                    <p class="alert alert-info">Task statuses are used to define the <strong>kanban board columns.</strong> You can create up to 4 additional statuses. Not Ready, Ready, In Progress and Completed are required for Schedullo to work. <br><br>
                        Typically, we find that our users love adding the following statuses :<br>
                        - <b>Backlog</b> - to keep track of your ideas and tasks that you will need to work on later.<br>
                        - <b>Waiting on</b> - to keep track of tasks that you cannot complete because you are waiting for someone else to finish something.<br><br>
                        <strong>The Sequence of statuses will impact the sequence of columns in kanban </strong>
                      </p>
                    <label class="control-label"><b>Task Status </b></label>
                    
                    <div class="customtable form-horizontal">
                        <table class="table table-striped table-hover table-condensed flip-content" id="status_table">
                          <thead class="flip-content">
                              <tr>
                                  <th></th>
                                  <th>Name</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody id="addStatus">
                              <?php $i = 0;
                                    if(isset($taskStatus) && $taskStatus!=''){
                                      foreach($taskStatus as $status){ ?>
                                            <tr id="status_<?php echo $status->task_status_id; ?>">
                                              <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i+1; ?></span></td>
                                              <td width="70%">
                                                  <?php echo $status->task_status_name;?>
                                              </td>
                                              <td>
                                                  <div>
                                                      <?php if($status->is_default_status == '1'){ ?>
                                                            <a  href="javascript:void(0)" disabled='disabled' > <i class="fa fa-trash-o company_trash_icon company_icon_black not_access"></i> </a>
                                                      <?php }else{ ?>
                                                            <a  href="javascript:void(0)" onclick="delete_selected(<?php echo $status->task_status_id; ?>);" id="delete_status_<?php echo $status->task_status_id; ?>"> <i class="fa fa-trash-o company_trash_icon company_icon_black"></i> </a>
                                                      <?php } ?>
                                                  </div>
                                              </td>
                                            </tr>
                              <?php $i++; } }  ?>
                          </tbody>
                      </table>
                      <div>
                          <input type="hidden" name="hide_total_status" id="hide_total_status" value="<?php echo $total_status; ?>"/> 
                          <form name="frm_taskStatus_add" id="frm_taskStatus_add" action="" >
                            <input type="text" class="large m-wrap valid" name="task_status_name" id="task_status_name" value="">
                            <?php if($total_status >= 8){ ?>
                                  <button type="button" class="btn blue txtbold sm" id="change_status_button" onclick="show_task_alert();"> Add</button>
                            <?php }else{ ?>
                                   <button type="submit" class="btn blue txtbold sm" id="change_status_button">Add</button><br id="show-error">
                            <?php } ?>
                          </form>
                      </div>    
                  </div>
                    
                </div>
