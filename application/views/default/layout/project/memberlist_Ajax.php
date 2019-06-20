<script>
 $(document).ready(function(){
     $("#user_id").chosen({
         width:"200px !important"
     });
 })
</script>
<div class="col-md-12 paddTop20">
    <label class="control-label col-md-6" style="padding-left: 0px;"> <strong> Find Users : </strong><span class="required">*</span></label>
    <select class=" m-wrap radius-b" id="user_id" name="user_id" tabindex="1">
	<option value="">-- Select --</option>
	<?php if($member_lst){
		foreach($member_lst as $row){ ?>
                <?php if($row->is_customer_user == 1){ 
                        if($row->customer_user_id == $customer_id){?>
                    <option value="<?php echo $row->user_id;?>" ><?php echo ucwords($row->first_name)." ".ucwords($row->last_name); ?> <?php if($row->is_customer_user == 1){ echo "(External)";}?></option>
                <?php } }else{ ?>
                    <option value="<?php echo $row->user_id;?>" ><?php echo ucwords($row->first_name)." ".ucwords($row->last_name); ?> </option>
        <?php } } } ?>
    </select>
</div>
<div class="col-md-12 paddTop20">
    <label class="control-label col-md-6" style="padding-left: 0px;"> <strong> Set as Project Administrator : </strong></label>  
    <input type="checkbox" name="another_project_owner" id="another_project_owner" value="1" style="margin-top: 7px;"/>
</div>

<style>
    
    .chosen-container .chosen-results {
        max-height: 70px !important;
    }
</style>