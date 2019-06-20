
<div id="content">
   
    <!-- Main content -->   
    <div class="wrapper">
	<?php if($error != ""){ ?>
       <div class="nNote nSuccess"><p><?php echo $error;?></p></div>
        </div>
    <?php } ?>
	
	
    	<!-- Table with sortable and resizable columns -->

				 <div class="widget" style="margin-top:30px;">
				 <div class="whead">
				
				 <span class="titleIcon fa fa-user"></span><h6>Email Templates </h6><div class="clear"></div></div>
					 <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll" id="checkAll">
                <thead>
                    <tr>
					    <td style="width:60px;"> Sr. No</td>
                        <td class="sortCol">Email Template Name</td>
                        <td style="width:60px;">Action</td> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($template)
                    {
						$i=1;
                        foreach($template as $row)
                        {
						?>
					<tr>
						
                   		<td align="center"><?php echo $i;?></td>
                        <td align="center" valign="middle"><?php echo $row->task;?></td>
                       <td align="center" width="60">
                        <?php echo anchor('email_template/add_email_template/'.$row->email_template_id,'<span class="iconb" data-icon="&#xe1db;"></span>',' title="Edit Email Tamplate" class="tablectrl_small bBlue tipS" id="editTemplate_'.$row->email_template_id.'" ');?>
	                   </td>
                    </tr>
				<?php
							$i++;
							}
						} else { ?>
					<tr><td colspan="9" align="center"><strong>No Records Found</strong></td></tr>	
						<?php }?>	
                   
                </tbody>
            </table>
			</form>
					
					</div>
			</div>	
		<ul class="pagination_new" style="margin-top: 15px;">
				<?php echo $page_link; ?>
             </ul>
        </div>
		    	


    </div>
    <!-- Main content ends -->

    </div>
