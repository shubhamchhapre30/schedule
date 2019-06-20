<script type="text/javascript">
    
    $(document).ready(function(){
            $("#customer_module_status").bootstrapToggle();
            $('#customer_module_status').bootstrapToggle().on('change', function (e, data) {
                var t=$(this).prop('checked')?1:0;
                updateCustomerModuleStatus(t);
            });

                
            $("#pricing_module_status").bootstrapToggle();
            $('#pricing_module_status').bootstrapToggle().on('change', function (e, data) {
                var t=$(this).prop('checked')?1:0;
                pricingModuleStatus(t);
            });  
            
            
            $(".change_currency").on("change",function(){
                    var curr = $(this).val();
                    $.ajax({
                        url: SIDE_URL + "settings/change_currency",
                        type: "post",
                        data: {
                            currency:curr
                        },
                        success: function(data) { 
                        },
                        error:function(data){
                            console.log('Ajax request not recieved!');
                        }
                    });
                });
                
            $("#timesheet_module_status").bootstrapToggle();
            $('#timesheet_module_status').bootstrapToggle().on('change', function (e, data) {
                var t=$(this).prop('checked')?1:0;
                timesheet_module_status(t);
            });
            
            $("#xero_integration").bootstrapToggle();
            $('#xero_integration').bootstrapToggle().on('change', function (e, data) {
                var t=$(this).prop('checked')?1:0;
                xero_integration_status(t);
            });
             
                $("#show_xero_setup").on("click",function(){
                    $("#xero-setup").modal('show');
                    $("#save_xero_account").removeProp("disabled");
                    $("#xero_setup_close").replaceWith('<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top: 16px !important"></button>');
                    
                });
                
                $("#save_xero_account").on('click',function(){
                    var account_code = $("#xero_account").val();
                    var tax_type = $("#xero_tax").val();
                    $.ajax({
                           url: SIDE_URL +'settings/update_xero_info',
                           type: "post",
                            data: {
                                account_code:account_code,
                                tax_type : tax_type,
                                xero_access_token:'<?php echo $this->session->userdata('access_token'); ?>',
                                oauth_token_secret:'<?php echo $this->session->userdata('oauth_token_secret');?>'
                            },
                           success: function(data){
                            $("#xero-setup").modal('hide');
                           }
                    });
                });
                
                $("#xero_setup_close").on('click',function(){
                    $("#dvLoading").fadeIn("slow");
                    var value = 0;
                        $.ajax({
                                url: SIDE_URL + "settings/update_xero_integration",
                                type: "post",
                                data: {
                                    status:value,
                                    wipe:value
                                },
                                success: function(data) { 
                                        $("#xero-setup").modal('hide');
                                        $("#xero_integration").css('display','none'); //TIMESHEET MODULE ACCESS OFF
                                        $("#xero_org").css('display','none');
                                        $("#show_xero_setup").css('display','none');
                                        $("#xero_integration").removeProp('checked');
                                        $.ajax({
                                                url: SIDE_URL +'xero/testLinks?wipe=1',
                                                success: function(data){
                                                }
                                        });
                                        $("#dvLoading").fadeOut("slow");
                                },
                                error:function(data){
                                    console.log('Ajax request not recieved!');
                                    $("#dvLoading").fadeOut("slow");
                                }
                         });
                });
                
                $(".mysetting-select").on('change',function(){
                    var account_code = $("#xero_account").val();
                    var tax_type = $("#xero_tax").val();
                    if(account_code !='0' && tax_type !='0'){
                         $("#save_xero_account").removeProp('disabled');
                    }else{
                        $("#save_xero_account").prop('disabled','disabled');
                    }
                });
    });      
                $(function() {
                    $("#xero-setup").modal({
                        backdrop: "static",
                        keyboard: !1,
                        show: !1
                    })
                });
														        
</script>


<div class="form-group"
                    <!--  customer module status code-->
			<div class="form-horizontal">
                                <div class="form-group">
                                        <p class="alert alert-info" style="width:98%"><b>Customer module</b><br><br>The customer module allows you to link your tasks and projects to customers. Once activated, you will also be able to activate the Pricing and the timesheet modules.</p>
                                        <label class="control-label padding-top-7 col-md-3">Activate Customer AddOn: </label>
					<?php if($this->session->chargify_transaction_status=='active' || $this->session->chargify_transaction_status=="trialing"){?>
                                        <div class="relative-position ">
                                            <input type="checkbox" name="customer_module_status" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  value="" <?php if($this->session->userdata('customer_module_activation')=='1'){echo 'checked';}?>  id="customer_module_status">
                                        </div>
                                        <?php }else{?>
                                        <div class="relative-position ">
                                            <input type="checkbox" name="customer_module_status" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  value="" <?php if($this->session->userdata('customer_module_activation')=='1'){echo 'checked';}?>  id="customer_module_status" disabled="disabled">
                                        </div>
                                        <?php }?>
				</div>
                        </div>
                    <!--   pricing module status code-->
                    <div id="pricing_status" >
                        <div class="form-horizontal" >
                                <div class="form-group">
                                        <p class="alert alert-info" style="width:98%"><b>Pricing Maintenance</b><br><br>The pricing module allows you to record a cost per hour against your users and setup a charge out rate for your customers, projects or type of tasks. You will be able to keep track of your project profitability and review how much you should charge your customers by period.</p>
					<label class="control-label padding-top-7 col-md-3">Activate Pricing Module: </label>
					<?php if($this->session->chargify_transaction_status=='active' || $this->session->chargify_transaction_status=="trialing" && $this->session->userdata('customer_module_activation')=='1'){?>
                                                <div class="relative-position ">
                                                    <input type="checkbox" name="pricing_module_status" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" value="" <?php if($this->session->userdata('pricing_module_status')=='1'){echo 'checked';}?>  id="pricing_module_status">
                                                </div>
                                        <?php }else{?>
                                                <div class="relative-position " >
                                                    <input type="checkbox" name="pricing_module_status"  data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" value="" <?php if($this->session->userdata('pricing_module_status')=='1'){echo 'checked';}?>  id="pricing_module_status" disabled="disabled">
                                                </div>
                                        <?php }?>
				</div>
                        </div>
            
                        <div class="form-horizontal" id ="currency_list" style="<?php if($this->session->userdata('customer_module_activation')=='1'&& $this->session->userdata('pricing_module_status')=='1'){echo "display:block";}else{echo "display:none";}?>">
                                <div class="form-group">
                                    <label class="control-label padding-top-7 col-md-3">Choose your currency :</label>
                                    
                                    <?php if(isset($currency)){?>
                                    <?php if($this->session->chargify_transaction_status=='active' || $this->session->chargify_transaction_status=="trialing"){?>
                                    <select class="m-wrap no-margin col-md-5 radius-b  change_currency" name="change_currency_name" id="change_currency_id" >
                                        <option value=""  >Please select</option>
                                      <?php foreach($currency as $curr){?>
                                      <option value="<?php echo $curr['currency_code'];?>" <?php if($this->session->userdata('currency_code')== $curr['currency_code']){echo "selected='selected'";}?>> <?php echo $curr['currency']."(".$curr['currency_symbol'].")";?></option>
                                      <?php }?>
                                    </select>
                                    <?php }else{?>
                                    <select class="m-wrap no-margin col-md-5 radius-b  change_currency" name="change_currency_name" id="change_currency_id" disabled="disabled">
                                        <option value=""  >Please select</option>
                                      <?php foreach($currency as $curr){?>
                                      <option value="<?php echo $curr['currency_code'];?>" <?php if($this->session->userdata('currency_code')== $curr['currency_code']){echo "selected='selected'";}?>> <?php echo $curr['currency']."(".$curr['currency_symbol'].")";?></option>
                                      <?php }?>
                                    </select>
                                    <?php }}?>
                                </div>
                        </div>
                    </div>
                    
        
                    <!--  timesheet status code-->
                    <div class="form-horizontal" >
                        <div class="form-group ">
                            <label class="control-label padding-top-7 col-md-3">Timesheet Module: </label>
			    <div class="relative-position ">
                                <input type="checkbox" name="timesheet_module_status" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  value="" <?php if($this->session->userdata('timesheet_module_status') == '1'){echo 'checked';} ?>  id="timesheet_module_status" <?php if($this->session->userdata('pricing_module_status')== '0' || $this->session->userdata('customer_module_activation')== '0'){echo "disabled = 'disabled'";}?> >
                            </div>
                        </div>
                    </div>

                    <!-- Xero integration  -->
                    <div class="form-horizontal" >
                        <div class="form-group">
                            <label class="control-label padding-top-7 col-md-3">Xero Integration: </label>
                            <div class="relative-position col-md-1" style="padding-left:0px !important;">
                               <input type="checkbox" name="xero_integration" data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" value="" <?php if($this->session->userdata('xero_integration_status') == '1'){echo 'checked';} ?>  id="xero_integration" <?php if($this->session->userdata('customer_module_activation')== '0' || $this->session->userdata('timesheet_module_status')== '0'){echo "disabled = 'disabled'";}?> >
                            </div>
                            <p ><a href="javascript:void(0);" class="btn blue txtbold sm" id="show_xero_setup" style="display:none;line-height: 20px !important;">Xero Setup</a></p>
                        </div>
                    </div>
                    <!-- Xero Integrated organization  details -->
                    
                    <?php if(isset($_SESSION['access_token']) && $_SESSION['access_token'] !=''){?>
                    <div class="form-horizontal">
                    <script>
                    $(document).ready(function(){
                        var account_code_xero = "<?php echo $xero_account_info->xero_account_code; ?>";
                        var tax_type_xero = "<?php echo $xero_account_info->xero_tax_type; ?>";
                        var xero_status = "<?php  echo $this->session->userdata('xero_integration_status');?>";
                        var token = '<?php echo $_SESSION['access_token']?>';
                        if(token !=''){
                            $.ajax({
                                    url: SIDE_URL +'xero/testLinks?organisation=1',
                                    success: function(data){
                                        data = jQuery.parseJSON(data);
                                        if(data['error_code'] == 'error'){
                                            if(data['error_message'] == 'token_rejected'){
                                                $.ajax({
                                                    url: SIDE_URL + "settings/update_xero_integration",
                                                    type: "post",
                                                    data: {
                                                        status:'0',
                                                        wipe:0
                                                    },
                                                    success: function(data) { 
                                                        $("#xero_integration").removeProp('checked');
                                                    }
                                                });
                                            }
                                        }else{
                                            var text = '<label class="control-label padding-top-7 col-md-3">Xero Organisation Name : </label><label class="control-label padding-top-7">'+data.organisations+'</label> ';
                                            $("#xero_org").html(text);
                                        }
                                    }
                            });
                            $.ajax({
                                    url: SIDE_URL +'xero/testLinks?accounts=1&type=1',
                                    success: function(data){ 
                                        
                                        data = jQuery.parseJSON(data);
                                        var view = '<option value="0">Select</option>'; 
                                        if(data['error_code'] != 'error'){
                                            if(data.accounts){
                                                $.each( data.accounts, function( i, value ) {
                                                    if(account_code_xero == data.accounts[i].Code ){
                                                        view +='<option value="'+data.accounts[i].Code+'"  selected>'+data.accounts[i].Code+' - '+data.accounts[i].Name +'</option>'
                                                    }else{
                                                        view +='<option value="'+data.accounts[i].Code+'">'+data.accounts[i].Code+' - '+data.accounts[i].Name +'</option>'
                                                    }
                                                   
                                               });
                                            }
                                            $("#xero_account").html(view);
                                            $.ajax({
                                                url: SIDE_URL +'xero/testLinks?accounts=1&type=2',
                                                success: function(data1){ 
                                                data1 = jQuery.parseJSON(data1);
                                                if(data1.accounts){
                                                     $.each( data1.accounts, function( i, value ) {
                                                         if(data1.accounts[i].Code == account_code_xero){
                                                          var d = '<option value="'+data1.accounts[i].Code+'" selected>'+data1.accounts[i].Code+' - '+data1.accounts[i].Name +'</option>';
                                                         }else{
                                                          var d = '<option value="'+data1.accounts[i].Code+'">'+data1.accounts[i].Code+' - '+data1.accounts[i].Name +'</option>';
                                                         }
                                                     $("#xero_account").append(d);
                                                    });
                                                 }
                                                }
                                            });
                                            if(account_code_xero =='0' && xero_status =='1'){
                                                $("#xero-setup").modal('show'); 
                                            }
                                            $("#show_xero_setup").show();
                                            
                                            
                                        }else{
                                            $("#show_xero_setup").hide();
                                            $("#xero-setup").modal('hide'); 
                                        }
                                        $("#save_xero_account").attr("disabled","disabled");
                                        
                                    }
                                });
                                        $.ajax({
                                                url: SIDE_URL +'xero/testLinks?TaxRates=1',
                                                success: function(data){ 
                                                data = jQuery.parseJSON(data);
                                                var view = '<option value="0">Select</option>'; 
                                                if(data['error_code'] != 'error'){
                                                    if(data.tax){
                                                        $.each( data.tax, function( i, value ) { 
                                                            if(data.tax[i].TaxType == tax_type_xero ){
                                                                view +='<option value="'+data.tax[i].TaxType+'" selected>'+data.tax[i].Name +' - '+data.tax[i].TaxComponents[0].Rate+'%'+'</option>'
                                                            }else{
                                                                view +='<option value="'+data.tax[i].TaxType+'">'+data.tax[i].Name +' - '+data.tax[i].TaxComponents[0].Rate+'%'+'</option>'
                                                            }
                                                           
                                                       });
                                                    }
                                                }
                                                $("#xero_tax").html(view);
                                                }
                                            });
                                            
                        }
                    });
                    </script>
                    <p id="xero_org"></p>
                    
                    </div>
                    <?php } ?>
</div>

<div id="xero-setup" class="modal model-size fade "  tabindex="-1" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" id="xero_setup_close" aria-hidden="true" style="margin-top: 16px !important"></button>
        <h3 class="">Xero Setup</h3> 
      </div>
      <div class="modal-body">
          <div class="portlet" style="margin-bottom:0px !important">
            <div class="portlet-body flip-scroll" style="padding:15px;">
                <div class="form-group">
                    <label class="control-label">Select Account : </label>
                    <div class="controls">
                        <select class="large m-wrap mysetting-select" id="xero_account" name="xero_account" tabindex="1" style="border-radius:5px;width:450px !important;padding: 2px;">
                            <option value="0">--Select--</option>
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Select Tax : </label>
                    <div class="controls">
                        <select class="large m-wrap mysetting-select" id="xero_tax" name="xero_tax" tabindex="2" style="border-radius:5px;width:450px !important;padding: 2px;">
                            <option value="0">--Select--</option>
                            
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="padding-left:0px !important">
                    <button style="float:left" class="btn green" data-dismiss="modal" id="save_xero_account">Save changes</button>
                </div>
            </div>
	</div>
      </div>
      
    </div>
  </div>
</div>