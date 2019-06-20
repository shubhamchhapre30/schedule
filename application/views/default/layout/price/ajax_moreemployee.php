<?php date_default_timezone_set($this->session->userdata("User_timezone")); ?>
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
                                                    <td><?php if($user['staff_level_title']){ echo $user['staff_level_title'];}else{ echo "-";}?></td>
                                                    <td ><label class="control-label"><?php echo $this->session->userdata('currency');?></label><a  href="#"   data-name="cost_per_hour" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-type="text" data-pk="1"  id="cost_<?php echo $user['user_id'];?>"><?php if($user['cost_per_hour']!='0'){echo $user['cost_per_hour'];}?></a></td>
                                                    <td ><label class="control-label"><?php echo $this->session->userdata('currency');?></label><a herf="#" data-name="base_charge_rate_per_hour" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-type="text" data-pk="1" id="charge_rate_<?php echo $user['user_id'];?>"><?php if($user['base_charge_rate_per_hour']!='0'){echo $user['base_charge_rate_per_hour'];}?></a></td>
                                                    <td><div class="pull-left"><span><?php if($user['rate_updated_date'] != '0000-00-00 00:00:00'){?>Last changed on the <?php echo date('jS M Y ',strtotime(toDateNewTime($user['rate_updated_date'])));?> By <?php echo $this->session->userdata('username');}?></span></div></td>
                                                </tr>
                                                <?php }?>
                                        <?php }else{?>
                                                <tr>
                                                    <td colspan="5">No data found.</td>
                                                </tr> 
                                        <?php }?>        
				  
				</tbody>
			  </table>
                           
                        </div>
<?php date_default_timezone_set("UTC");?>