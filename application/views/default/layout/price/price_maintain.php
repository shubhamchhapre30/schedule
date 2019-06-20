<?php
$theme_url = base_url().getThemeName(); 
date_default_timezone_set($this->session->userdata("User_timezone"));
//echo $total_pages; die();
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/css/context.standalone.css?Ver=<?php echo VERSION;?>">
<script src='<?php echo $theme_url; ?>/assets/js/maintain<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>'></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/js/bootstrap-editable.min.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/scripts/form-editable.js?Ver=<?php echo VERSION;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/css/bootstrap-editable.css?Ver=<?php echo VERSION;?>" />
<script>
    $(document).ready(function(){
        $("#change_customer").on('change',function(){ 
            var customer_id = $("#change_customer").val();
           $.ajax({
                   type: "post",
                   url: SIDE_URL + "price/getCustomerBaseRate",
                   data: {
                       customer_id:$("#change_customer").val(),
                    },
                   success: function(data) {  
                       var a='';
                       if(data=='0.00'){
                           a='<a  href="#"  class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-name="base_rate" data-type="text" data-pk="1"  id="customer_base_rate_'+customer_id+'"></a>';
                       }else{
                          a='<label class="control-label"><?php echo $this->session->userdata('currency');?></label><a  href="#"  class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-name="base_rate" data-type="text" data-pk="1"  id="customer_base_rate_'+customer_id+'">'+data+'</a>'; 
                       }
                        
                       $("#hidden_customer_id").val($("#change_customer").val());
                       $.ajax({
                               type: "post",
                               url: SIDE_URL + "price/getDefaultCustomerCategory",
                               data: {
                                    customer_id:$("#change_customer").val(),
                               },
                                success: function(b) { 
                                    $("#category_show").html(b);
                                    $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "price/getcategoryOption",
                                            data: {
                                                 customer_id:$("#change_customer").val(),
                                            },
                                             success: function(c) {
                                                 $("#add_category_option").html(c);
                                             },
                                             error:function(c){
                                                 console.log("Ajax request not recieved!"), 
                                                 $("#dvLoading").fadeOut("slow");
                                             }
                                     })
                                },
                                error:function(b){
                                    console.log("Ajax request not recieved!"), 
                                    $("#dvLoading").fadeOut("slow");
                                }
                        })
                        if($("#change_customer").val()==0){
                            $("#show_customer").css('display','none');
                        }else{
                            $("#show_customer").css('display','block');
                            $("#customer_rate_set").html(a);
                        }
                        $("#dvLoading").fadeOut("slow");
                        $("#customer_base_rate_"+customer_id).editable({
                                  url: SIDE_URL + "price/updateCustomerrate",
                                  params:{customer_id : customer_id},
                                  type: "post",
                                  pk: 1,
                                  mode: "popup",
                                  showbuttons: !0,
                                  validate: function(e) {
                                                                    
                                        var s = /^[0-9 .]*$/;
                                        return s.test($.trim(e)) ? void 0 : "Please enter only number."
                                  },
                                  success: function(a) { 
                                      
                                  }  
                                  
                        });
                                $.ajax({
                                            type: "post",
                                            url: SIDE_URL + "price/getemployeelist",
                                            data: {
                                                 customer_id:$("#change_customer").val(),
                                            },
                                             success: function(c) {
                                                 $("#customer_employee_list").html(c);
                                             },
                                             error:function(c){
                                                 console.log("Ajax request not recieved!"+c), 
                                                 $("#dvLoading").fadeOut("slow");
                                             }
                                        })
                        
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"+a), 
                        $("#dvLoading").fadeOut("slow");
                   }
            })
         });
                
               
               
        
    });
    
</script>

<!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid page-background" style="padding:15px;margin-bottom: 30px">
        <div class="border" style="background-color: #FFF">
            <div class="user-block" >
       		<div class="row">
                    <div class="col-md-12">
                        <!--Heading-->
                        <div class="col-md-12">
                            <span><b><h4 class="txtbold bold_black">Price Maintenance</h4></b></span>
                        </div>
                        <div class="col-md-12 ">
                            <div class="col-md-12 cus_heading" style="padding-left:10px">
                                <h5 class='txtbold'><b>Base Employee cost and rate</b></h5>
                            </div>
                            <div class="col-md-12" style="padding-top: 6px;">
                                    <div class="col-md-8">
                                        <p>This base charge out rates are used when no pricing has been set for the customer or for a project. </p> 
                                    </div>
                                    <div class="col-md-4">
                                          <input class="onsub m-wrap large cus_input " name="employee_search" id="employee_search" placeholder="Search employee" value="" type="text"  tabindex="1" />
                                    </div>
                                
                            </div>
                            <div id="seachData">
                            <div class="customtable table-scrollable form-horizontal" id="paging">
                             <table class="table table-striped table-hover table-condensed flip-content " >
				<thead class="flip-content">
				  <tr>
					<th>Name</th>
					<th>Status</th>
					<th>Level</th>
					<th>Cost / hr</th>
					<th>Base Charge Rate / hr</th>
					<th></th>
					
				  </tr>
				</thead>
				<tbody>
					<?php if(isset($employee) && $employee !=''){?>
                                                <?php foreach($employee as $user){?>
                                                <script>
                                                    $(document).ready(function(){
                                                        
                                                        $('#cost_<?php echo $user['user_id'];?>').editable({
                                                                url: SIDE_URL + "price/updateEmployeeRate",
                                                                params:{user_id : <?php echo $user['user_id'];?>},
                                                                type: "post",
                                                                pk: 1,
                                                                mode: "popup",
                                                                showbuttons: !0,
                                                                validate: function(e) {
                                                                    
                                                                    var s = /^[0-9 .]*$/;
                                                                    return s.test($.trim(e)) ? void 0 : "Please enter valid number."
                                                                },
                                                                success: function() {
                                                                    
                                                                }
                                                        }); 
                                                        $('#charge_rate_<?php echo $user['user_id'];?>').editable({
                                                                url: SIDE_URL + "price/updateEmployeeRate",
                                                                params:{user_id : <?php echo $user['user_id'];?>},
                                                                type: "post",
                                                                pk: 1,
                                                                mode: "popup",
                                                                showbuttons: !0,
                                                                validate: function(e) {
                                                                    
                                                                    var s = /^[0-9 .]*$/;
                                                                    return s.test($.trim(e)) ? void 0 : "Please enter valid number."
                                                                },
                                                                success: function() {}
                                                        }); 
                                                    });
                                                </script>
                                                <tr id="tr_<?php echo $user['user_id'];?>">
                                                    <td><?php echo $user['first_name'].' '.$user['last_name']; ?></td>
                                                    <td><?php echo $user['user_status'];?></td>
                                                    <td><?php if($user['staff_level_title']){echo $user['staff_level_title'];}else{ echo "-";} ?></td>
                                                    <td ><label class="control-label"><?php echo $this->session->userdata('currency');?></label><a  href="#" class="font-family_customer" data-name="cost_per_hour" data-type="text" data-emptytext="Not set" data-placeholder="Enter amount" data-pk="1"  id="cost_<?php echo $user['user_id'];?>"><?php if($user['cost_per_hour']!='0'){echo $user['cost_per_hour'];}?></a></td>
                                                    <td ><label class="control-label"><?php echo $this->session->userdata('currency');?></label><a herf="#" class="font-family_customer" data-name="base_charge_rate_per_hour" data-emptytext="Not set" data-placeholder="Enter amount" data-type="text" data-pk="1" id="charge_rate_<?php echo $user['user_id'];?>"><?php if($user['base_charge_rate_per_hour']!='0'){echo $user['base_charge_rate_per_hour'];}?></a></td>
                                                    <td><div class="pull-left"><span><?php if($user['rate_updated_date'] != '0000-00-00 00:00:00'){?>Last changed on the <?php echo date('jS M Y ',strtotime(toDateNewTime($user['rate_updated_date'])));?> By <?php echo $this->session->userdata('username');}?></span></div></td>
                                                </tr>
                                                <?php }?>
                                        <?php }?>
				  
				</tbody>
			     </table>
                            </div>
                                <div align="center">
                                    <ul class='pagination text-center' id="pagination">
                                    <?php if(!empty($total_pages) && $total_pages>1){for($i=0; $i<$total_pages; $i++){  
                                                if($i == 0){?>
                                                 <li class='active'  id="<?php echo $i;?>"><a href='javascript:void(0)' onclick="getmoreemployee(<?php echo $i;?>)"><?php echo $i+1;?></a></li> 
                                                <?php }else{?>
                                                        <li id="<?php echo $i;?>"><a href='javascript:void(0)'  onclick="getmoreemployee(<?php echo $i;?>)"><?php echo $i+1;?></a></li>
                                                 <?php }?>          
                                    <?php }}?>  
                                    </ul>                    
                                 </div>      
                            </div>
                      </div>
                      <div class="col-md-12 ">
                            <div class="col-md-12 cus_heading" style="padding-left:10px">
                                <h5 class='txtbold'><b>Customer pricing</b></h5>
                            </div>
                            <div class="col-md-12">
                                <div class="form-horizontal">
                                  <div class="form-group">
                                      <label class="control-label padding-top-7 bold_black" style="padding-right:53px;">Select Customer</label>
                                      <?php if(isset($customers)){?>
                                      <select class="m-wrap no-margin col-md-11 radius-b chosen " name="change_customer" id="change_customer" >
                                          <option value="0">Please select</option>
                                        <?php foreach($customers as $curr){?>
                                        <option value="<?php echo $curr->customer_id; ?>"> <?php echo $curr->customer_name;?></option>
                                        <?php }?>
                                      </select>
                                      <?php }?>
                                  </div>
                                </div>
                            </div>
                          <div id="show_customer" style="display:none;">
                            <div class="col-md-12" style="padding-top: 6px;">
                                 <p>This base rates is used when no others rates are setup at the customer level. To charge the </p> 
                                 <P>customer the base employee rate, leave this field blank.</P>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <span class="txtbold bold_black">Base rate</span>
                                    <input type="hidden" id="hidden_customer_id" name="hidden_customer_id" value=""/>
                                </div>
                                <div class="form-group">
                                <div class="col-md-2" id="customer_rate_set">
                                    <a  href="#"   data-name="customer_base_rate" data-type="text" data-pk="1"  id="customer_base_rate"></a>
                                </div>
                                </div>
                            </div>  
                            <div class="col-md-8" style="margin-top:5px;color: #333333;background-color: #d8d8d8 !important;">
                                   <b>Customer task category pricing</b>
                            </div>
                            <div class="col-md-12" style="margin-top: 10px">
                                <p>You can vary the charge out rate for customer based on the type of work performed.</p>
                            
                                <div class="col-md-12" id='category_show'>
                                    
                                </div>
                                <div class="col-md-8" id="add_category_option" style="padding-top: 10px;">
                                      
                                </div> 
                            </div>
                            <div class="col-md-8" style="margin-top:5px;color: #333333;background-color: #d8d8d8 !important;">
                                   <b>Customer pricing by employee</b>
                            </div>
                            <div class="col-md-12" style="margin-top: 10px">
                                <div class="col-md-6">
                                <p>You can set a charge out per employee for the selected customer. If you set charge out</p>
                                <p>rate, it will overwrite all other charge out rate except the project specific rate.</p>
                                </div>
                                <div class="col-md-6">
                                          <input class="onsub m-wrap large " name="employee_search_under_customer" id="employee_search_under_customer" placeholder="Search employee" value="" type="text"   />
                                    </div>
                                <div id="customer_employee_list">
                                    
                                </div>
                                    
                            </div>
                          </div>
                          
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php date_default_timezone_set("UTC");?>