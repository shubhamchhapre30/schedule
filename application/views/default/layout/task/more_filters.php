<script>
init_data2();
</script>
<?php if(isset($main_category) && !empty($main_category)){ ?>
        <div class="col-md-2">
            <label class="control-label bold display_flex">Category</label>
            <select id="selectpicker5" multiple name="category" class="serach_module_data" title="Select category" data-size="5" size="1" data-live-search="true" >
                <?php foreach($main_category as $cate){
                        if(in_array_r($cate->category_id,$filters_data)){ ?>
                            <option value="<?php echo $cate->category_id; ?>" selected="selected"><?php echo $cate->category_name; ?></option>
                <?php }else{ ?>
                            <option value="<?php echo $cate->category_id; ?>" ><?php echo $cate->category_name; ?></option>
                <?php } } ?>
            </select>
        </div>
<?php } ?>
<?php if(isset($sub_category) && !empty($sub_category)){ ?>
        <div class="col-md-2">
            <label class="control-label bold display_flex">Sub-Category</label>
            <select id="selectpicker6" multiple name="subcategory" class="serach_module_data" title="Select sub-category" data-size="5" size="1" data-live-search="true" >
                <?php foreach($sub_category as $cate){
                        if(in_array_r($cate->category_id,$filters_data)){ ?>
                            <option value="<?php echo $cate->category_id; ?>" selected='selected'><?php echo $cate->category_name; ?></option>
                <?php }else{ ?>
                            <option value="<?php echo $cate->category_id; ?>"><?php echo $cate->category_name; ?></option>
                <?php } } ?>
            </select>
        </div>
<?php } ?>
<?php if(isset($divisions) && !empty($divisions)){ ?>
        <div class="col-md-2">
            <label class="control-label bold display_flex">Division</label>
            <select id="selectpicker7" multiple name="division" class="serach_module_data" title="Select division" data-size="5" size="1" data-live-search="true" >
                <?php foreach($divisions as $div){
                        if(in_array_r($div->division_id,$filters_data)){ ?>
                            <option value="<?php echo $div->division_id; ?>" selected='selected'><?php echo $div->devision_title; ?></option>
                <?php }else{ ?>
                            <option value="<?php echo $div->division_id; ?>"><?php echo $div->devision_title; ?></option>
                <?php } } ?>
            </select>
        </div>
<?php } ?>
<?php if(isset($departments) && !empty($departments)){ ?>
        <div class="col-md-2">
            <label class="control-label bold display_flex">Department</label>
            <select id="selectpicker8" multiple name="department" class="serach_module_data" title="Select department" data-size="5" size="1" data-live-search="true" >
                <?php foreach($departments as $dept){ 
                        if(in_array_r($dept->department_id,$filters_data)){?>
                            <option value="<?php echo $dept->department_id; ?>" selected='selected'><?php echo $dept->department_title; ?></option>
                <?php }else{ ?>
                            <option value="<?php echo $dept->department_id; ?>"><?php echo $dept->department_title; ?></option>
                <?php } } ?>
            </select>
        </div>
<?php } ?>
<?php if(isset($task_status) && !empty($task_status)){ ?>
        <div class="col-md-2">
            <label class="control-label bold display_flex">Status</label>
            <select id="selectpicker9" multiple name="task_status" class="serach_module_data" title="Select status" data-size="5" size="1" data-live-search="true" >
                <?php foreach($task_status as $status){
                        if(in_array_r($status->task_status_id,$filters_data)){?>
                            <option value="<?php echo $status->task_status_id; ?>" selected='selected'><?php echo $status->task_status_name; ?></option>
                <?php }else{ ?>
                            <option value="<?php echo $status->task_status_id; ?>"><?php echo $status->task_status_name; ?></option>
                <?php } } ?>
            </select>
        </div>
<?php } ?>
