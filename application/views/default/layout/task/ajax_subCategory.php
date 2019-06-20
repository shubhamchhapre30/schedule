
<?php if(isset($sub_category) && $sub_category!=''){ ?>
		<select class="col-md-11 m-wrap no-margin task-input radius-b" name="task_sub_category_id" id="task_sub_category_id" tabindex="5" >
			<!-- <option value="0">Please Select</option> -->
		<?php foreach($sub_category as $sub_cat){
			?>
			<option value="<?php echo $sub_cat->category_id ?>" <?php if($sub_cat->category_id == $sub_id){ echo 'selected="selected"'; } ?> ><?php echo $sub_cat->category_name; ?></option>
			<?php
		} ?>
		</select>
	<?php } else {
		if($this->session->userdata("is_administrator") && $is_sub_category_exist=="0"){ ?>
			<div class="input-icon right">
				<i onclick="window.open('<?php echo site_url("settings/index#company_setting_tab_4");?>','_blank');"  class="stripicon help"></i>
				<input class="m-wrap col-md-11" disabled="disabled" name="task_sub_category_id" value="Add Sub Category" type="text" placeholder="Add sub category" />
			</div>
	<?php } else { ?>
		<select class="col-md-11 m-wrap no-margin" disabled="disabled" name="task_sub_category_id" id="task_sub_category_id" tabindex="5">
			<option value="0" >Please select</option>
		</select>
	<?php } ?>
	<input type="hidden" name="task_sub_category_id" id="task_sub_category_id" value="0" />
<?php } ?>

<span class="input-load" id="task_sub_category_id_loading"></span>
