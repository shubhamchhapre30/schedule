<?php
$theme_url = base_url().getThemeName(); 
//echo $total_pages; die();
?>
<script src='<?php echo $theme_url; ?>/assets/js/maintain<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>'></script>
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <?php if(isset($category)){?>
                                                <select class="m-wrap no-margin col-md-12 radius-b " name="customer_category_name" id="customer_category_name" >
                                                    <option value="0">select category to add</option>
                                                  <?php foreach($category as $curr){?>
                                                  <option value="<?php echo $curr->category_id; ?>"> <?php echo $curr->category_name;?></option>
                                                  <?php }?>
                                                </select>
                                                <?php }?>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="customer_category_rate " id="customer_category_rate" value="" placeholder="Rate" class="onsub m-wrap cus_input" style="margin-top:0px !important"/>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="button" name="add_rate" value="Add" id="add_customer_category" class="btn blue txtbold sm"/>
                                            </div>
                                        </div>
                                     </div>  

