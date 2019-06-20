<div class="customtable table-scrollable form-horizontal" id="paging">
                          <table class="table table-striped table-hover table-condensed flip-content " id="customerTable">
				<thead class="flip-content">
				  <tr>
					<th>Name</th>
					<th>External ID</th>
					<th>Owner</th>
					<th>Contact</th>
					<th>Phone</th>
					<th>Email</th>
					<th>Action</th>
				  </tr>
				</thead>
				<tbody id="customerlist">
					<?php if(isset($customers) && $customers!=''){
						foreach($customers as $row){
							?>
							<tr id="listCustomer_<?php echo $row->customer_id;?>">
                                                           
                                                                <td width="22%">
                                                                    <form method="POST"  action="<?php echo site_url('customer/openCustomer');?>" name="myForm_<?php echo $row->customer_id;?>" id="myForm_<?php echo $row->customer_id;?>">
                                                                           <input type="hidden" name="cus_id" id="cus_id" value="<?php echo $row->customer_id;?>" />
                                                                   </form>
                                                                  <a href="javascript:void(0)" onclick="callCustomer('<?php echo $row->customer_id;?>');"><?php echo $row->customer_name; ?></a>
                                                                </td>
								<td><?php echo $row->external_id; ?></td>
                                                                <?php if($row->owner_id!="0"){?>
                                                                <td><?php  echo $row->ownername;  ?></td>
                                                                <?php }else{ ?>
                                                                <td><?php  echo '-';  ?></td>
                                                                <?php }?>
                                                                <td width="15%"><?php echo $row->first_name.' '.$row->last_name; ?></td>
								<td> <?php echo $row->phone; ?> </td>
								<td> <?php echo $row->email; ?></td>
								<td width="15%">
                                                                    <input type="hidden" name="hide_customer_id" id="hide_customer_id" value="<?php echo $row->customer_id;?>"/>
                                                                    <input type="hidden" name="hide_customer_name_<?php echo $row->customer_id;?>" id="hide_customer_name_<?php echo $row->customer_id;?>" value="<?php echo $row->customer_name; ?>"/>
                                                                    <input type="hidden" name="hide_external_id_<?php echo $row->customer_id;?>"  id="hide_external_id_<?php echo $row->customer_id;?>" value="<?php echo $row->external_id; ?>"/>
                                                                    <input type="hidden" name="hide_owner_id_<?php echo $row->customer_id;?>" id="hide_owner_id_<?php echo $row->customer_id;?>" value="<?php echo $row->owner_id; ?>"/>
                                                                    <input type="hidden" name="hide_first_name_<?php echo $row->customer_id;?>" id="hide_first_name_<?php echo $row->customer_id;?>" value="<?php echo $row->first_name; ?>"/>
                                                                    <input type="hidden" name="hide_last_name_<?php echo $row->customer_id;?>" id="hide_last_name_<?php echo $row->customer_id;?>" value="<?php echo $row->last_name; ?>"/>
                                                                    <input type="hidden" name="hide_phone_<?php echo $row->customer_id;?>" id="hide_phone_<?php echo $row->customer_id;?>" value="<?php echo $row->phone; ?>"/>
                                                                    <input type="hidden" name="hide_email_<?php echo $row->customer_id;?>"  id="hide_email_<?php echo $row->customer_id;?>" value="<?php echo $row->email; ?>"/>
                                                                    <input type="hidden" name="hide_parent_customer_id_<?php echo $row->customer_id;?>" id="hide_parent_customer_id_<?php echo $row->customer_id;?>" value="<?php echo $row->parent_customer_id; ?>"/>
                                                                    <?php if($this->session->userdata('customer_access')== '0'){?>
                                                                        <a href="javascript:void(0)"  class="not_access"> <i class="icon-pencil cstmricn" style="transform: scale(0.75);"></i> </a> 
                                                                        <a href="javascript:void(0);" class="not_access"> <i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>  
                                                                    <?php }else{?>
                                                                        <a href="javascript:void(0)"  onclick="editCustomer('<?php echo $row->customer_id;?>');"> <i class="icon-pencil cstmricn" style="transform: scale(0.75);"></i> </a> 
                                                                        <a href="javascript:void(0);" onclick="deleteCustomer('<?php echo $row->customer_id;?>');"> <i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>  
                                                                    <?php }?>	
								</td>
							  </tr>
							<?php
						}
					}else{?>
                                                          <tr>
                                                              <td colspan="6">No data found.</td>
                                                          </tr>            
                                        <?php }?>
				</tbody>
			  </table>
</div>
<div align="center" style="margin-bottom:-7px;">
        <ul class='pagination text-center' id="pagination">
            <?php if(!empty($pages) && $pages>1){for($i=0; $i<$pages; $i++){  
                    if($i == 0){?>
                       <li class='active'  id="<?php echo $i;?>"><a href='javascript:void(0)' onclick="getcustomerData(<?php echo $i;?>)"><?php echo $i+1;?></a></li> 
                    <?php }else{?>
                       <li id="<?php echo $i;?>"><a href='javascript:void(0)'  onclick="getcustomerData(<?php echo $i;?>)"><?php echo $i+1;?></a></li>
                    <?php }?>          
            <?php }}?>  
        </ul>                    
 </div>