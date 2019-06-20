      <?php date_default_timezone_set($this->session->userdata("User_timezone"));?>
<div id="search_result_show">
                        <div class="customtable table-scrollable form-horizontal" id="paging1">
                                     <table class="table table-striped table-hover table-condensed flip-content " >
                                        <thead class="flip-content">
                                          <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Level</th>
                                                <th>Base Charge Rate / hr</th>
                                                <th></th>

                                          </tr>
                                        </thead>
                                        <tbody>
                                                <?php if(isset($employee) && $employee !=''){?>
                                                        <?php foreach($employee as $user){?>
                                                    <script>
                                                        $(document).ready(function(){
                                                            
                                                            $('#employee_charge_rate_<?php echo $user['user_id'];?>').editable({
                                                                url: SIDE_URL + "price/updateUserRateUnderCustomer",
                                                                params:{user_id : <?php echo $user['user_id'];?>,customer_id:$("#hidden_customer_id").val()},
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
                                                        <tr>
                                                            <td><?php echo $user['first_name'].' '.$user['last_name']; ?></td>
                                                            <td><?php echo $user['user_status'];?></td>
                                                            <td><?php if($user['staff_level_title']){ echo $user['staff_level_title'];}else{ echo "-";}?></td>
                                                            <td><label class="control-label"><?php echo $this->session->userdata('currency');?></label><a herf="#" data-name="base_rate" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-type="text" data-pk="1" id="employee_charge_rate_<?php echo $user['user_id'];?>"><?php if($user['base_rate']!='0'){echo $user['base_rate'];}?></a></td>
                                                            <td><div class="pull-left"><span><?php if($user['update_date'] != ''){?>Last changed on the <?php echo date('jS M Y ',strtotime(toDateNewTime($user['update_date'])));?> By <?php echo $this->session->userdata('username');}?></span></div></td>
                                                        </tr>
                                                        <?php }?>
                                                <?php }?>

                                        </tbody>
                                     </table>
                                     </div>
                            
                                     <div align="center">
                                    <ul class='pagination text-center' id="pagination1">
                                    <?php if(!empty($total_pages) && $total_pages>1){for($i=0; $i<$total_pages; $i++){  
                                                if($i == 0){?>
                                                 <li class='active'  id="e_<?php echo $i;?>"><a href='javascript:void(0)' onclick="getuserunderemployee(<?php echo $i;?>)"><?php echo $i+1;?></a></li> 
                                                <?php }else{?>
                                                        <li id="e_<?php echo $i;?>"><a href='javascript:void(0)'  onclick="getuserunderemployee(<?php echo $i;?>)"><?php echo $i+1;?></a></li>
                                                 <?php }?>          
                                    <?php }}?>  
                                    </ul>                    
                                </div>
</div>           
                                 
<?php date_default_timezone_set('UTC');?>