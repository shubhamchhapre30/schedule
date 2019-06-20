<?php
$theme_url = base_url().getThemeName(); 

?>





				<div class="modal-header">
					<button type="button" class="close close_customer_div" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="customer_modal_title">  </h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
                                            <div class="portlet-body  form flip-scroll" style="padding:10px;">
                                                <div class="row form-horizontal">
                                                    <form name="customer_data" id="customer_data" method="post" >
                                                            
								<div class="form-group col-md-6">
                                                                   
                                                                        <label class="control-label"><strong>Name</strong> </label>
									<div class="controls">
										<input class="onsub m-wrap cus_input " name="customer_name" id="customer_name" placeholder="Enter Name" value="" type="text"  tabindex="1" />
                                                                                
									</div>
                                                                </div>
                                                                
                                                                <div class=" form-group col-md-6">
                                                                        <label class="control-label"><strong>External ID</strong> </label>
									<div class="controls">
										<input class="onsub m-wrap cus_input" name="customer_external_id" id="customer_external_id" placeholder="External ID" value="" type="text"  tabindex="1" />
									</div>
								</div>
                                                                    
                                                                    <div class="col-md-12">
                                                                        <label class="control-label"><strong>Contact</strong></label>
                                                                    </div>
                                                                    <div class="col-md-12 " style="padding: 0px !important;">
									<div class="form-group controls col-md-6">
										<input class="onsub m-wrap cus_input" name="first_name" id="first_name" placeholder="First name" value="" type="text"  tabindex="1" />
									</div>
                                                                        <div class="form-group controls col-md-6">
										<input class="onsub m-wrap cus_input" name="last_name" id="last_name" placeholder="Last Name" value="" type="text"  tabindex="1" />
									</div>
                                                                    </div>   
                                                                    <div class="col-md-12 " style="padding: 0px !important;">
                                                                        <div class="form-group controls col-md-6" style="padding-top: 10px;">
										<input class="onsub m-wrap cus_input" name="email" id="email" placeholder="Email address" value="" type="text"  tabindex="1" />
									</div>
                                                                        <div class="form-group controls col-md-6" style="padding-top: 10px;">
										<input class="onsub m-wrap cus_input" name="phone" id="phone" placeholder="Phone" value="" type="text"  tabindex="1" />
									</div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class="control-label"><strong>Internal Owner</strong> </label>
                                                                        <select class="col-md-12 m-wrap no-margin  radius-b" name="internal_owner" id="internal_owner" tabindex="3" >
										<option value="0">Please Select</option>
										<?php if(isset($user) && $user!=''){
                                                                                    foreach($user as $list){?>
                                                                                <option value="<?php echo $list['user_id']?>"  ><?php echo $list['first_name'].' '.$list['last_name']?></option>
                                                                                <?php }}?>									
									</select>
                                                                        
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class="control-label"><strong>Parent Customer</strong> </label>
                                                                        <select class="col-md-12 m-wrap no-margin  radius-b" name="parent_customer_id" id="parent_customer_id" tabindex="3" >
										<option value="0">Please Select</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="pull-right col-md-12" style="margin-top:10px;">
                                                                        <input type="hidden" name="customerid" id="customerid" value=""/>
                                                                        <input type="hidden" name='customer_company_id' id="customer_company_id" value="<?php echo $this->session->userdata('company_id');?>"/>
                                                                        <button class="btn blue txtbold" id="customer_update" type="submit"><i class="stripicon icosave"></i>Update  </button>
                                                                         <button class="btn blue txtbold" id="customer_save" type="submit"><i class="stripicon icosave"></i>Save Customer  </button>
                                                                         <button class="btn red txtbold" id="close_customer_div" type="button" > <i class="stripicon icocancel"></i>Cancel </button>
                                                                                                   
                                                                    </div>
                                                        </form>
				                 </div>
                                            </div>
					</div>
			        </div>
                  
                    
			
			