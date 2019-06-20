<?php if(isset($sub_category) && $sub_category!=''){
    ?><option value="">All</option><?php 
	foreach($sub_category as $sub_cat){
		?>
		<option value="<?php echo $sub_cat->category_id;?>"><?php echo $sub_cat->category_name;?></option>
		<?php
	}
}?>