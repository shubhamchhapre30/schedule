                    <?php $default_format = $this->config->item('company_default_format'); ?>
                    <div id="customeruserdiv">
			<div class="table-toolbar">
                            <div>
                                <a href="javascript:void(0);" onclick="add_customer_user_modal();"  class="btn blue txtbold"><i class="icon-plus"></i> Invite External Users</a>
                            </div>
			</div>
                        <div class="customtable table-scrollable form-horizontal" style="border:0 !important;">
                            <table class="table table-striped table-hover table-condensed flip-content">
				<thead class="flip-content">
				  <tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Email</th>
					<th>Customer Name</th>
					<th>Status</th>
					<th>Last Connection</th>
					<th>Action</th>
                                  </tr>
				</thead>
				<tbody id="CustomerUsr">
					<?php if(isset($customer_users) && $customer_users!=''){
						foreach($customer_users as $row){ ?>
                                                    <tr id="customerUser_<?php echo $row->user_id;?>">
                                                        <td><?php echo $row->first_name; ?></td>
                                                        <td><?php echo $row->last_name; ?></td>
							<td><?php echo $row->email; ?></td>
							<td><?php echo $row->customer_name; ?></td>
							<td><?php echo $row->user_status; ?></td>
                                                        <td><?php if($row->user_status != 'Pending'){echo date($default_format, strtotime(get_user_last_login_date($row->user_id)));}else{echo '-';}?></td>
							<td>
                                                            <a href="javascript:void(0);" onclick="edit_customer_user(<?php echo $row->user_id; ?>);"> <i class="icon-pencil stngicn company_icon_black"></i> </a> 
                                                            <a href="javascript:void(0);" onclick="delete_customer_user(<?php echo $row->user_id; ?>);" id="delete_customer_user_<?php echo $row->user_id; ?>"> <i class="icon-trash stngicn company_icon_black"></i> </a>  
							</td>
                                                    </tr>
                                        <?php } }else{ ?>
                                                    <tr id="empty_table">
                                                        <td colspan="7">No record found...</td>
                                                    </tr>
                                        <?php } ?>
				</tbody>
			    </table>
			</div>
                    </div>
                    <div id="customerUsermodal" data-backdrop="static" data-keyboard="false" class="modal cus_model fade customecontainer" tabindex="-1">
			<?php $this->load->view($theme."/layout/customer/editCustomer"); ?>
                    </div>