<?php 
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	$default_format = $site_setting_date; 
	
?>


			<div class="form-horizontal">
                                
	 			<div class="form-group">
                                    <label class="control-label padding-top-7 col-md-3">Credit Card Information:</label>
					<?php if($credit_card!='' && $expiry_date!=''){?>
					<div class="controls">
						<div  class="row margin-top-10 setworkchk">
							<div class="col-md-2">
								<label class="checkbox line">
									Credit Card : 
								</label>
							</div>
							<div class="col-md-4  relative-position">
								<span class="hravailable"> <?php echo $credit_card;?></span>
								<div class="clearfix"></div>
							</div>
						</div>
						
						<div  class="row margin-top-10 setworkchk">
							<div class="col-md-2">
								<label class="checkbox line">
									Expiry Date : 
								</label>
							</div>
							<div class="col-md-4  relative-position">
								<span class="hravailable"> <?php echo $expiry_date;?> </span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					<?php }else{ ?>
					<div class="controls">
						<div  class="row margin-top-10 setworkchk">
							<div class="col-md-2">
                                                            <label class="checkbox line" >
									&nbsp; 
								</label>
							</div>
							<?php if($credit_card == 'error'){?>
                                                        <div class="col-md-4  relative-position">
								<span class="hravailable"> <?php echo "Unable to retrieve your billing information. Please try again later.";?></span>
								<div class="clearfix"></div>
							</div>
                                                        <?php }else{?>
                                                        <div class="col-md-4  relative-position">
								<span class="hravailable"> <?php echo "No payment method found";?></span>
								<div class="clearfix"></div>
							</div>
                                                        <?php }?>
						</div>
					</div>	
					<?php } ?>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" >&nbsp;</label>
					<div class="relative-position" >
						<a href="javascript:void(0)" class="btn blue txtbold sm" id="billing">Access billing portal</a>
                                                <?php if($this->session->userdata('is_owner') == 1){?>
                                                <a href="javascript:void(0)" class="btn red txtbold sm" id="close_account">Close My Account</a>
                                                <?php }?>
					</div>
				</div>
                                
			</div>
		
		
		
		
			<div class="form-group">
				<div>
                                    <label class="bld control-label padding-top-7"> <b>Invoices:</b></label>
				</div>
			 
			 <div class="customtable form-horizontal">
				<table class="table table-striped table-hover table-condensed flip-content">
				<thead class="flip-content">
				  <tr>
                                        <th><span><i class="fa fa-hashtag" aria-hidden="true"></i></span>Invoice ID</th>
					<th>Date</th>
					<th>Charges</th>
					<th>Payments</th>
					<th>Paid(Yes/No)</th>
					<th>Paid On</th>
				  </tr>
				</thead>
				<tbody id="listUserTr">
					<?php $i = 0;
					if(isset($statements) && $statements!=""){
						foreach ($statements as $s) {
							$i = $i+1;
							?>
							<tr>
								<td width="22%"><a href="javascript:void(0);" onclick="generateStatement('<?php echo $s->id;?>');" > <?php echo $s->id;?></a></td>
							  	<td><?php echo ($s->updated_at)?date($default_format,strtotime(str_replace(array("/"," ",","), "-",$s->updated_at))):'N/A'; ?></td>
								<td> $ <?php echo number_format($s->total_in_cents/100,2);?></td>
								<td> $ <?php echo number_format((($s->total_in_cents+$s->starting_balance_in_cents)-($s->ending_balance_in_cents))/100,2);?> </td>
								<td> <?php echo ($s->settled_at!='')?'Yes': 'No';?></td>
								<td> <?php echo ($s->settled_at!='')?date($default_format,strtotime(str_replace(array("/"," ",","), "-",$s->settled_at))):'N/A';?></td>
							  </tr>
							<?php
						}
					 }else{ ?>
						<tr ><td colspan="6" >No Invoice Available</td></tr>
				<?php } ?>
				  
				</tbody>
			  </table>
			</div>
                        </div>

<div id="closeAccountModal" class="modal fade new_alert_msg alert-info"  tabindex="-1" >
        <div class="modal-body">
          <div class="portlet new_porlet_body" >
            <div class="portlet-body flip-scroll padd15" >
                <strong>Your Schedullo is ready to close.</strong> Could you please tell us why you want to close your account?
                <form id="close_account_form" name="close_account_form" method="post" onsubmit="event.preventDefault();">
                <div class="form-group">
                    <label class="control-label">Reason : </label>
                    <div class="controls">
                        <select class=" m-wrap mysetting-select alert_input" id="close_reason" name="close_reason" tabindex="1" required >
                            <option value="">--Select--</option>
                            <option value="I don't need a task management app"> I don't need a task management app</option>
                            <option value="Schedullo is too complex"> Schedullo is too complex</option>
                            <option value="Missing functionality">Missing functionality</option>
                            <option value="I don't like Schedullo">I don't like Schedullo</option>
                            <option value="I found another app">I found another app</option>
                            <option value="Other">Other</option>
                            
                            
                        </select>
                    </div>
                </div>
                <div class="form-group" id= 'reason_other' style='display: none;margin-bottom:45px;'>
                    <label class="control-label">Justify : </label>
                    <div class="controls">
                        <input type="text"  name="close_reason_other" id="close_reason_other" value="" class="m-wrap col-md-12" placeholder="" tabindex="1">
                    </div>
                </div>
               
                <div class="modal-footer">
                    <button class="btn green" id="close_user_account" type="submit">Close Account</button>
                </div>
                </form>
            </div>
	</div>
      </div>
    
</div>
   
   
<script type="text/javascript">
	

		function generateStatement(statement_id)
		{
			
			if(statement_id!=''){
			 	$('#dvLoading').fadeIn('slow');
	        	$.ajax({
					type : 'post',
					url : '<?php echo site_url("settings/generateStatement");?>',
					data : {statement_id:statement_id},
					async:false,
					success : function(data){
						
						$.ajax({
						    url:'<?php echo site_url();?>'+data+'',
						    type:'HEAD',
						    error: function()
						    {   
						    },
						    success: function()
						    {
						    	window.open('<?php echo site_url();?>'+data, '_blank');
					            
						       $.ajax({
						          url: '<?php echo site_url("settings/deleteExistFile");?>',
						          data: {'file' : data },
						          type:'post',
						          async:false,
						          success: function (response) {
						          	$('#dvLoading').fadeOut('slow');
						          },
						          error: function () {
						          	$('#dvLoading').fadeOut('slow');
						          }
						        });
						    }
						});
						
						$('#dvLoading').fadeOut('slow');
					},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
				});
					
			}
		}	
</script>
