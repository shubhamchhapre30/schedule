
	 	<div id="listUserDiv">
			<div class="table-toolbar">
				<div>
					<a href="javascript:void(0)" onclick="addCompanyUser();" class="btn blue txtbold"><i class="icon-plus"></i>  Add User</a>
				</div>
			 </div>
                    <div class="customtable table-scrollable form-horizontal" style="border:0 !important;">
				<table class="table table-striped table-hover table-condensed flip-content">
				<thead class="flip-content">
				  <tr>
					<th>User Name</th>
					<th>Email</th>
					<th>Level</th>
					<th>Division</th>
					<th>Department</th>
					<th>Status</th>
					<th>Admin</th>
					<th>Owner</th>
                                        <th>Action</th>
                                  </tr>
				</thead>
				<tbody id="listUserTr">
					<?php if(isset($user) && $user!=''){
						foreach($user as $row){
							$division = get_user_division($row->user_id);
							$department = get_user_department($row->user_id);
							?>
							<tr id="listUser_<?php echo $row->user_id;?>">
								<td width="20%"><?php echo $row->first_name.' '.$row->last_name; ?></td>
							  	<td><?php echo $row->email; ?></td>
								<td><?php if($row->staff_level){ echo get_staff_level($row->staff_level); }else{ echo '-'; } ?></td>
								<td> <?php if($division){ echo $division; }else{ echo '-'; } ?> </td>
								<td> <?php if($department){ echo $department; }else{ echo '-'; }?></td>
                                                                <td><?php if($row->user_status == 'Active'){echo '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>'; } else { echo '<i class="fa fa-times " aria-hidden="true" style="color:red"></i>'; }?></td>
								<td><?php if($row->is_administrator == '1'){ echo '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>'; } else { echo '<i class="fa fa-times " aria-hidden="true" style="color:red"></i>'; }?></td>
								<td><?php if($row->is_owner == '1'){ echo '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>'; } else { echo '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>'; }?></td>
								<td width="5%">
									<?php if(($row->is_owner == '0' && $row->is_administrator =='1') || ($row->is_owner == '0' && $row->is_administrator =='0') || ($this->session->userdata("user_id")==$row->user_id)){ ?> 
                                                                                <a href="javascript:void(0)" onclick="editCompanyUser('<?php echo $row->user_id;?>');"> <i class=" icon-pencil stngicn company_icon_black"></i> </a> 
									<?php } ?>
									<?php if($row->is_owner == '0'){ ?> 
                                                                                <a href="javascript:void(0);" onclick="delete_user('<?php echo $row->user_id;?>');" id="delete_user_<?php echo $row->user_id;?>"> <i class="icon-trash stngicn company_icon_black"></i> </a>  
									<?php } ?>
								</td>
                                                        </tr>
					<?php } } ?>
				  <input type="hidden" name="hidden_login_user" id="hidden_login_user" value="<?php echo $this->session->userdata('user_id');?>"/>
				</tbody>
			  </table>
			</div>
		</div>
		
		<div id="addUserDiv">
			<?php  $this->load->view($theme."/layout/user/addUser") ?>
		</div>
		