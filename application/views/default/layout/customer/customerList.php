<?php
$theme_url = base_url().getThemeName(); 
//echo $total_pages; die();
?>
<script src='<?php echo $theme_url; ?>/assets/js/customer<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>'></script>
<style>
    .table-toolbar{
        margin-bottom:2px;
    }
  
</style>
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/js/jquery.dataTables.min.js?Ver=<?php echo VERSION;?>"></script>
<script>
    function addCustomer(){ 
                $("#customer_name").val(""),
                $("#customer_external_id").val(""),
                $("#first_name").val(""),
                $("#last_name").val(""),
                $("#email").val(""),
                $("#phone").val(""), 
                $('#internal_owner option[value="0"]').attr("selected",true);
                $('#parent_customer_id option[value="0"]').attr("selected",true);
                $("#customerid").val("");
                $("#customer_modal_title").text("New Customer"),
                $("#customer_update").hide(),
                $.ajax({
                    type: "post",
                    url: SIDE_URL + "customer/get_all_active_customer",
                    success: function(a) { 
                        var e = jQuery.parseJSON(a); 
                        var view = '';
                        view +='<option value="0">Please Select</option>';
                        if(e.customers!= '0' ){ 
                            $.each( e.customers, function( i, value ) {
                                view +='<option value="'+e.customers[i].customer_id+'">'+e.customers[i].customer_name+'</option>';
                            });
                        }
                        $("#parent_customer_id").html(view);
                        $("#newcustomer").modal("show");
                        $('#newcustomer').on('shown.bs.modal', function () {
                            $('#customer_name').focus();
                        });  
                    },
                    error:function(a){
                         console.log("Ajax request not recieved!");
                    }
                });
    }
     
</script>
<script>
    $(document).ready(function(){     
    $("#customerTable").dataTable({
        order: [
            [0, "asc"]
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

</script>
<div class="portlet box list_cus page-background" style="border:none !important;border-radius: 5px 5px 5px 5px; margin-bottom:50px !important;" >
	    <div class="portlet-body  form flip-scroll" style="background-color: rgba(255,255,255,0.68) !important">

	 	<div id="listCustomerDiv">
                    <div class="table-toolbar" style="padding-bottom:38px;">
				<div class="col-md-2">
                                    <?php if($this->session->userdata('customer_access') == '0'){?>
					<a href="javascript:void(0)" class="btn blue txtbold not_access" style="line-height: 20px;font-size:13px;border:0;"><i class="icon-plus"></i>  Add Customer</a>
                                    <?php }else{?>    
                                        <a href="javascript:void(0)" onclick="addCustomer();" class="btn blue txtbold" style="line-height: 20px;font-size:13px;border:0;"><i class="icon-plus"></i>  Add Customer</a>
                                    <?php }?>    
                                </div>
                                <div class="pull-right" style="padding-right: 16px;">
                                    
                                    <input class="onsub m-wrap large cus_input " name="customer_search" id="customer_search" placeholder="Search" value="" type="text"  tabindex="1" style="padding: 4px 6px !important;"/>
                                </div>
			 </div>
                 <div id="search_data"> 
                     <div class="customtable table-scrollable form-horizontal" id="paging">
                         <table class="table table-striped table-hover table-condensed flip-content " id="customerTable" style="margin-bottom:0px !important">
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
                                                                <td width="22%" onclick="callCustomer('<?php echo $row->customer_id;?>');" style="cursor: pointer;">
                                                                    <form method="POST" style="margin: 0px !important;" action="<?php echo site_url('customer/openCustomer');?>" name="myForm_<?php echo $row->customer_id;?>" id="myForm_<?php echo $row->customer_id;?>">
                                                                            <input type="hidden" name="cus_id" id="cus_id" value="<?php echo $row->customer_id;?>" />
                                                                    </form>
                                                                    <?php echo $row->customer_name; ?>
                                                                </td>
								<td onclick="callCustomer('<?php echo $row->customer_id;?>');" style="cursor: pointer;"><?php echo $row->external_id; ?></td>
                                                                <?php if($row->owner_id!="0"){?>
                                                                <td onclick="callCustomer('<?php echo $row->customer_id;?>');" style="cursor: pointer;"><?php  echo $row->ownername;  ?></td>
                                                                <?php }else{ ?>
                                                                <td  onclick="callCustomer('<?php echo $row->customer_id;?>');" style="cursor: pointer;"><?php  echo '-';  ?></td>
                                                                <?php }?>
                                                                <td width="15%"  onclick="callCustomer('<?php echo $row->customer_id;?>');" style="cursor: pointer;"><?php echo $row->first_name.' '.$row->last_name; ?></td>
								<td  onclick="callCustomer('<?php echo $row->customer_id;?>');" style="cursor: pointer;"> <?php echo $row->phone; ?> </td>
								<td  onclick="callCustomer('<?php echo $row->customer_id;?>');" style="cursor: pointer;"> <?php echo $row->email; ?></td>
                                                                
								<td width="8%">
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
                                                                        <a href="javascript:void(0);" onclick="deleteCustomer('<?php echo $row->customer_id;?>');" id="delete_customer_<?php echo $row->customer_id;?>"> <i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>  
                                                                    <?php }?>
								</td>
							  </tr>
							<?php
						}
					}?>
				  
				</tbody>
			  </table>
                        
			</div>
                     <div align="center" style="margin-bottom:-7px">
                                <ul class='pagination text-center' id="pagination">
                                <?php if(!empty($total_pages) && $total_pages>1){for($i=0; $i<$total_pages; $i++){  
                                            if($i == 0){?>
                                             <li class='active'  id="<?php echo $i;?>"><a href='javascript:void(0)' onclick="getcustomerData(<?php echo $i;?>)"><?php echo $i+1;?></a></li> 
                                            <?php }else{?>
                                                    <li id="<?php echo $i;?>"><a href='javascript:void(0)'  onclick="getcustomerData(<?php echo $i;?>)"><?php echo $i+1;?></a></li>
                                             <?php }?>          
                                <?php }}?>  
                                </ul>                    
                        </div>

                   </div>  
		</div>
                
	</div>
</div>

<div id="newcustomer" class="modal cus_model fade customecontainer" tabindex="-1">
    <?php  $this->load->view($theme.'/layout/customer/addCustomer') ?>
</div>