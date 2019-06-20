<div id="content">        
	<?php if($msg != ""){
	
		if($msg= 'update'){ $error = 'Template updated successfully.'; } ?>
        <div class="column full">
            <span class="message information"><strong><?php echo $error;?></strong></span>
        </div>
    <?php } ?>
	<div class="clear"></div>
	<div class="column full">
	
	<div class="box">		
		<div class="box themed_box">
		<h2 class="box-header">Templates Manager </h2>
			
			<div class="box-content box-table">
			<table class="tablebox">

				<thead class="table-header">
					<tr> 
                        <th class="first tc">Sr. No</th>
                        <th style="text-align:left; padding-left:20px;">Template Name</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th class="tc">Action</th>        
					</tr>
				</thead>
				
				<tbody class="openable-tbody">
				<?php
                    if($template)
                    {
                        $i=1;
                        foreach($template as $row)
                        {
                            if($i%2=="0")
                            {
                                $cl = "even";	
                            }else{	
                                $cl = "odd";	
                            }
                  ?>
					<tr class="<?php echo $cl; ?>">
                        <td class="tc"><?php echo $i;?></td>
                        <td style="text-align:left; padding-left:20px;"><?php echo $row->template_name;?></td> 
                        <td><?php if($row->active_template == 1) { echo 'Active'; } else { echo 'Inactive'; } ?></td>
                        
                         <td><?php if($row->is_admin_template == 1) { echo 'Admin'; } else { echo 'Front'; } ?></td>
                         
                        <td>
							<?php echo anchor('template_setting/add_template_setting/'.$row->	template_id,'<span class="icon_single edit"></span>',' title="Edit Tamplate" class="button white" ');?>
                        </td>
                        
                       
                  	</tr>
				  <?php
                            $i++;
                        }
                    }
                  ?>	
				</tbody>
			</table>
               
			</div>
		</div>
	</div>
</div>