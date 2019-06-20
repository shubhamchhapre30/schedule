      <?php date_default_timezone_set($this->session->userdata("User_timezone"));?>
                <div class='col-md-12'>
                                       <div class="row " style="margin-top: 5px;">
                                            
                                                <?php 
                                                foreach($customer_category as $curr){?>
                                                <script>
                                                    $(document).ready(function(){
                                                        $("#sub_<?php echo $curr->category_id; ?>").slideUp({});
                                                        $('#edit_cate_rate_<?php echo $curr->category_id; ?>').editable({
                                                                url: SIDE_URL + "price/updateCategoryRate",
                                                                params:{category_id : <?php echo $curr->category_id; ?>,customer_id:$("#hidden_customer_id").val()},
                                                                type: "post",
                                                                pk: 1,
                                                                mode: "popup",
                                                                showbuttons: !0,
                                                                validate: function(e) {
                                                                      var s = /^[0-9 .]*$/;
                                                                      return s.test($.trim(e)) ? void 0 : "Please enter only number."
                                                                },
                                                                success: function() {}  
                                                        });      
                                                    });
                                                </script>
                                                <div class="col-md-12" id="category_<?php echo $curr->category_id; ?>">
                                                    <div class="col-md-2" style="padding-left: 0px !important;">
                                                        <a href="javascript:void(0);" onclick="expand_data(<?php echo $curr->category_id; ?>)" ><span id="expand_div_<?php echo $curr->category_id; ?>"><i class="icon-chevron-right" ></i></span></a>
                                                        <span ><label class="control-label " ><?php echo $curr->category_name;?></label></span>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="control-label"><?php echo $this->session->userdata('currency');?></label><a href="#" data-name="rate" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-type="text" data-pk="1" class="txt-style edit_title" id="edit_cate_rate_<?php echo $curr->category_id;?>"><?php echo $curr->rate;?></a>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <a href="javascript:void(0);" onclick="remove_category(<?php echo $curr->category_id;?>)"><span  class="pull-right"><i class="icon-remove" ></i></span></a>
                                                    </div>
                                                    <div class="col-md-7 pull-right"><span><?php if($curr->updated_date != '0000-00-00 00:00:00'){?>Last changed on the <?php echo date('jS M Y ',strtotime(toDateNewTime($curr->updated_date)));?> By <?php echo $this->session->userdata('username');}?></span></div>
                                                        <input type="hidden" name="cate_expand_<?php echo $curr->category_id; ?>" id="cate_expand_<?php echo $curr->category_id; ?>" value="0"/>
                                                    <div class="col-md-12" id='sub_<?php echo $curr->category_id; ?>'>
                                                        
                                                    </div>
                                                </div>
                                                <?php }?>
                                        </div> 
                                        
                                    </div>

<?php date_default_timezone_set("UTC");?>