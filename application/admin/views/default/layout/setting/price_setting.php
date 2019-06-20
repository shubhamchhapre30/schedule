<?php
//echo $_REQUEST['option'];
 ?>

<div id="content">
   
    <!-- Main content -->   
    <div class="wrapper">
	<?php 
		if($msg != ""){
	     if($msg == "insert"){ $error = 'New Record has been added Successfully.';}
            if($msg == "update"){ $error = 'Record has been updated Successfully.';}
            if($msg == "delete"){ $error = 'Record has been deleted Successfully.';}
			if($msg == "active") {  $error = 'Rights has been active Successfully.';}
			if($msg == "inactive"){ $error = 'Record has been inactive Successfully.';}
    ?>
        <div class="nNote nSuccess"><?php echo '<p>'.$error.'</p>';?></div>
    <?php } ?>
	
<?php
				$attributes = array('id'=>'usualValidate','name'=>'frm_addprice','class'=>'main');
				echo form_open('price_setting/list_price',$attributes);
			  ?>
                <fieldset>	
    	<!-- Table with sortable and resizable columns -->
        <div class="widget">
         	
				
				 <div class="whead"><span class="titleIcon icon-user"></span><h6>Package Price Setting</h6><div class="clear"></div></div>
				 

				<div class="formRow">
					<div class="grid3"><label>10 Hour Package<span class="req">*</span></label></div>
					<div class="grid9"><input type="text" value="<?php echo $price_rate_10; ?>" name="price_rate_10" id="price_rate_10" class="required" />
					<input type="hidden" value="<?php echo $price_id_10; ?>" name="price_id_10" id="price_id_10" readonly=""/>
					</div><div class="clear"></div>
				</div>
				 
				<div class="formRow">
					<div class="grid3"><label>15 Hour Package<span class="req">*</span></label></div>
					<div class="grid9"><input type="text" value="<?php echo $price_rate_15; ?>" name="price_rate_15" id="price_rate_15" class="required" />
					<input type="hidden" value="<?php echo $price_id_15; ?>" name="price_id_15" id="price_id_15" readonly=""/>
					</div><div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label>20 Hour Package<span class="req">*</span></label></div>
					<div class="grid9"><input type="text" value="<?php echo $price_rate_20; ?>" name="price_rate_20" id="price_rate_20" class="required" />
					<input type="hidden" value="<?php echo $price_id_20; ?>" name="price_id_20" id="price_id_20" readonly=""/>
					</div><div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label>30 Hour Package<span class="req">*</span></label></div>
					<div class="grid9"><input type="text" value="<?php echo $price_rate_30; ?>" name="price_rate_30" id="price_rate_30" class="required" />
					<input type="hidden" value="<?php echo $price_id_30; ?>" name="price_id_30" id="price_id_30" readonly=""/>
					</div><div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label>40 Hour Package<span class="req">*</span></label></div>
					<div class="grid9"><input type="text" value="<?php echo $price_rate_40; ?>" name="price_rate_40" id="price_rate_40" class="required" />
					<input type="hidden" value="<?php echo $price_id_40; ?>" name="price_id_40" id="price_id_40" readonly=""/>
					</div><div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label>Per Hour Package<span class="req">*</span></label></div>
					<div class="grid9"><input type="text" value="<?php echo $price_rate_other; ?>" name="price_rate_other" id="price_rate_other" class="required" />
					<input type="hidden" value="<?php echo $price_id_other; ?>" name="price_id_other" id="price_id_other" readonly=""/>
					</div><div class="clear"></div>
				</div>


						<div class="formRow">
						<input type="submit" name="submit" value="Submit" class="buttonM bBlack formSubmit" />
						<div class="clear"></div>
						</div>
					</div>
		</fieldset>			
			</form>
					
					</div>
			</div>	
		 
        </div>
		    	


    </div>
    <!-- Main content ends -->

    </div>
	