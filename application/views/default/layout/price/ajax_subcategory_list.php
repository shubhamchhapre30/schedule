<?php date_default_timezone_set($this->session->userdata("User_timezone")); ?>
<?php if(isset($sub_category) && $sub_category !=''){?>
        
<?php foreach($sub_category as $sub){?>
            <script>
                    $(document).ready(function(){
                        $('#edit_subcategoryrate_<?php echo $sub->category_id; ?>').editable({
                            url: SIDE_URL + "price/updateSubCategoryRate",
                            params:{category_id : <?php echo $category_id; ?>,sub_categoryid:<?php echo $sub->category_id; ?>,customer_id:$("#hidden_customer_id").val()},
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
            
            <div class="col-md-10" style="padding-top: 5px;padding-bottom: 5px;">
            <div class="col-md-2"id="subcategory_<?php echo $sub->category_id;?>"><?php echo $sub->category_name ?></div>
            <div class="col-md-2"><span class="pull-right"><label class="control-label"><?php echo $this->session->userdata('currency');?></label><a href="#" data-name="rate" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount" data-type="text" data-pk="1" class="txt-style edit_title" id="edit_subcategoryrate_<?php echo $sub->category_id;?>"><?php if($sub->rate!='0'){echo $sub->rate;}?></a></span></div>
            <div class="col-md-7 pull-right"><span><?php if($sub->updated_date != '0000-00-00 00:00:00'){?>Last changed on the <?php echo date('jS M Y ',strtotime(toDateNewTime($sub->updated_date)));?> By <?php echo $this->session->userdata('username');}?></span></div>
            </div>
<?php }}else{?>
                
            <div class="col-md-10">
                <span style="padding-left: 15px;">No subcategory.</span>
            </div>
<?php } date_default_timezone_set("UTC");?>