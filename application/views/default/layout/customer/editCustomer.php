<script>
$(document).ready(function(){
     $("#parent_customer").chosen({
         width: "100% !important",
     });
});

</script>
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3> External User Details </h3>
                                    <input type="hidden" name="access_page" id="access_page" value="" />
                                </div>
				<div class="modal-body" >
                                    <div class="portlet" style="border-radius: 0px 0px 5px 5px !important;">
                                        <div class="portlet-body flip-scroll padding-10">
                                            <div class="row form-horizontal">
                                                <form name="customer_users_data" id="customer_users_data" method="post" >
                                                    <input type="hidden" name="customer_user_id" id="customer_user_id" value="" />
                                                    <div class="col-md-6">
                                                        <label class="control-label"><strong>First Name</strong><span class="required">*</span> </label>
                                                        <div class="controls">
                                                            <input class="onsub m-wrap cus_input " name="customer_user_first" id="customer_user_first" placeholder="First Name" value="" type="text"  tabindex="1" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="control-label"><strong>Last Name</strong><span class="required">*</span> </label>
							<div class="controls">
                                                            <input class="onsub m-wrap cus_input" name="customer_user_last" id="customer_user_last" placeholder="Last Name" value="" type="text"  tabindex="1" />
							</div>
                                                    </div>
                                                    <div class="col-md-12 paddding-5" id="parent_customer_users_list" style="display:none;">
                                                        <label class="control-label"><strong>Active Customers</strong><span class="required">*</span> </label>
                                                        <div class="controls">
                                                            <select class="m-wrap no-margin radius-b" name="parent_customer" id="parent_customer" tabindex="3" >
                                                                <option value=""> Select customer</option>
                                                                <?php  if(isset($customers) && !empty($customers)){
                                                                        foreach($customers as $cus){ ?>
                                                                          <option value="<?php echo $cus->customer_id; ?>"><?php echo $cus->customer_name; ?></option>  
                                                               <?php } } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 paddTop10">
                                                        <label class="control-label"><strong>Email</strong><span class="required">*</span> </label>
							<div class="controls">
                                                            <input class="onsub m-wrap cus_input" name="customer_user_mail" id="customer_user_mail" placeholder="Email Address" value="" type="text"  tabindex="1" />
							</div>
                                                    </div>
                                                    
                                                    
                                                    <div class="pull-right col-md-12 margin-top-20">
                                                        <button class="btn blue txtbold"  id="customer_user_update" type="submit">Update</button>
                                                        <button class="btn blue txtbold" id="customer_user_save" type="submit">Send Invite</button>
                                                        <button class="btn red txtbold" class="close" data-dismiss="modal" aria-hidden="true" type="button" >Cancel </button>
                                                    </div>
                                                </form>
				            </div>
                                        </div>
                                    </div>
			        </div>
<style>
    
    .chosen-container .chosen-results {
        max-height: 100px !important;
    }
</style>