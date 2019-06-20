<script type="text/javascript" language="javascript">
		$(document).ready(function(){
		$('.chk').click(function(){
			if($("#titleCheck").val() == 'on'){
				$('#titleCheck').parent('span').removeClass('checked');
				$('#titleCheck').removeAttr('checked');
			}
		});
		
		$("#titleCheck").click( function(){	
			if($(this).attr('checked')){
			//	$(".chk").attr('checked','checked');
				$(".chk").parent('span').addClass('checked');
				$(".chk").parent('span').parent('div').parent('td').parent('tr').addClass('thisRow');
				elem = document.getElementsByName('chk[]');
				//alert('sddfdf');
				//setchecked('chk[]');
				
				for(i=0;i<elem.length;i++){
					elem[i].checked=1;
				}
				
				
			}else{
				//$(".chk").removeAttr('checked','checked');
				$(".chk").parent('span').removeClass('checked');
				$(".chk").parent('span').parent('div').parent('td').parent('tr').removeClass('thisRow');
				elem = document.getElementsByName('chk[]');
				for(i=0;i<elem.length;i++){
					elem[i].checked=0;
				}
				
				//setchecked('chk[]');
				
		
			}
			// $("#frm1 input[type='checkbox'].child").attr ( "checked" , $(this).attr("checked" ) );
		});
	});


	function delete_rec(id,offset)
	{
		var ans = confirm("Are you sure to delete facebook_setting?");
		if(ans)
		{
			location.href = "<?php echo base_url(); ?>facebook_setting/delete_facebook_setting/"+id+"/"+offset;
		}else{
			return false;
		}
	}
	
	function getlimit(limit)
	{
		if(limit=='0')
		{
		return false;
		}
		else
		{
			window.location.href='<?php echo base_url();?>facebook_setting/list_facebook_setting/'+limit;
		}
	
	}	
	
	function getsearchlimit(limit)
	{
		if(limit=='0')
		{
		return false;
		}
		else
		{
			
			window.location.href='<?php echo base_url();?>facebook_setting/search_list_facebook_setting/'+limit+'/<?php echo $option.'/'.$keyword; ?>';
		}
	
	}
	
	function gomain(x)
	{
		
		if(x == 'all')
		{
			window.location.href= '<?php echo base_url();?>facebook_setting/list_facebook_setting';
		}
		
	}
	
	
	function setchecked(elemName,status){
	elem = document.getElementsByName(elemName);
	for(i=0;i<elem.length;i++){
		elem[i].checked=status;
	}
}

function setaction(elename, actionval, actionmsg, formname) {


	vchkcnt=0;
	elem = document.getElementsByName(elename);
	
	for(i=0;i<elem.length;i++){
		if(elem[i].checked) vchkcnt++;	
	}
	if(vchkcnt==0) {
		alert('Please select a record')
	} else {
		
		if(confirm(actionmsg))
		{
			document.getElementById('action').value=actionval;	
			document.frm_facebook_setting.submit();
		}		
		
	}
}


</script><div id="content" class="form_page">
		      <div class="cantnet_top_sed">
			<div class="contentTop">
				<span class="pageTitle"><span class="icon-link"></span>Facebook_setting Listing</span>
			<div class="clear"></div>
			</div>
		</div>
   
    <!-- Main content -->   
    <div class="wrapper">
	<?php if($msg != ""){
	     if($msg == "insert"){ $error = 'Record has been added successfully.';}
            if($msg == "update"){ $error = 'Record has been updated successfully.';}
            if($msg == "delete"){ $error = 'Record(s) has been deleted successfully.';}
			if($msg == "active") {  $error = 'Record(s) has been activated successfully.';}
			if($msg == "inactive"){ $error = 'Record(s) has been inactivated successfully.';}
    ?>
	<div class="nNote nSuccess"><?php echo '<p>'.$error.'</p>'; ?></div>
    <?php } ?>
       <!-- Table with sortable and resizable columns -->
        <div class="widget check grid8">
		
		<div class="whead">
				<h6>
					<span class="ico  gray arrow_bidirectional">
						Facebook_setting Listing
					</span>
				</h6>
				<a class="buttonH bGold" href="javascript:void(0)" onclick="setaction('chk[]','active', 'Are you sure, you want to activate selected record(s)?', 'frm_facebook_setting');">Active</a>
					<a class="buttonH bBlack" href="javascript:void(0)" onclick="setaction('chk[]','inactive', 'Are you sure, you want to inactivate selected record(s)?', 'frm_facebook_setting');">Inactive</a>
					<a class="buttonH bRed" href="javascript:void(0)" onclick="setaction('chk[]','delete', 'Are you sure, you want to delete selected record(s)?', 'frm_facebook_setting');">Delete</a>
					<?php echo anchor('facebook_setting/add_facebook_setting','Add', 'class="buttonH bGreen" id="addfacebook_setting" '); ?>
				<div class="clear"></div>		
			</div>
				
				<div class="tablePars">
				<div class="dataTables_filter grid9"> 
					<div>
					 <div style="float:left;">
                        <strong>Show</strong>
					    <?php if($search_type=='normal') { ?>
                        	<select name="limit" id="limit" onchange="getlimit(this.value)" style="width:80px;background:#FFFFFF;">
                        <?php } if($search_type=='search') { ?>
                          	<select name="limit" id="limit" onchange="getsearchlimit(this.value)" style="width:80px;background:#FFFFFF;">
                        <?php } ?>
                                <option value="0">Per Page</option>
                                <option value="5" <?php if($limit==5){?> selected="selected"<?php }?>>5</option>
                                <option value="10"  <?php if($limit==10){?> selected="selected"<?php }?>>10</option>
                                <option value="15"  <?php if($limit==15){?> selected="selected"<?php }?>>15</option>
                                <option value="25"  <?php if($limit==25){?> selected="selected"<?php }?>>25</option>
                                <option value="50"  <?php if($limit==50){?> selected="selected"<?php }?>>50</option>
                                <option value="75"  <?php if($limit==75){?> selected="selected"<?php }?>>75</option>
                                <option value="100"  <?php if($limit==100){?> selected="selected"<?php }?>>100</option>     
                       	   </select>
                    </div>
					
		
			
			       <?php			 
					$attributes = array('name'=>'frm_search','id'=>'frm_search');
					echo form_open('facebook_setting/search_list_facebook_setting/'.$limit,$attributes);?>
					
					 <strong>&nbsp;&nbsp;&nbsp;Search By</strong>&nbsp;
                            <select name="option" id="option" onchange="gomain(this.value)" style="background:#FFFFFF;">
                            	<option value="all">All</option>                  
                            </select>
                
                
                            <input type="text" name="keyword" id="keyword" value="<?php echo $keyword;?>" style=" border: 1px solid #CCCCCC;height: 23px;width: 162px; padding:2px;" placeholder="Enter keyword" />                
                            <input type="submit" name="submit" id="submit" value="Search" class="buttonM bBlue" /> 
                
                	</form>
					</div>
					<div style="clear:both;"></div>
					</div> <!-- dataTables_filter End -->
					
					
		 	</div>
			</div>
         <form name="frm_facebook_setting" id="frm_facebook_setting" action="<?php echo base_url();?>facebook_setting/action_facebook_setting" method="post">
			
				<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
            	<input type="hidden" name="action" id="action" /><table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll" id="checkAll"><thead><tr>  <td align="center"><input type="checkbox" id="titleCheck" name="titleCheck" /></td><td>Action</td></tr>
                </thead>
                <tbody><?php if($result){ $i=0;
	    foreach($result as $row){?><tr>
	                <td align="center">
					<input type="checkbox"  id="titleCheck<?php echo  $row->facebook_setting_id;?>" class="chk" name="chk[]" value="<?php echo $row->facebook_setting_id;?>"/> 
					</td><td align="center" width="60">
                        	<?php echo anchor('facebook_setting/edit/'.$row->facebook_setting_id.'/'.$limit.'/'.$offset,'<span class="iconb" data-icon="&#xe1db;"></span>','class="tablectrl_small bBlue tipS"  id="user_'.$row->facebook_setting_id.'" title="Edit facebook_setting"'); ?>
					   		<a href="javascript:void(0);" onClick="delete_rec('<?php echo $row->facebook_setting_id; ?>','<?php echo $offset; ?>')" title="Delete" class="tablectrl_small bRed  tipS"><span class="iconb" data-icon="&#xe136;"></span></a></td>
							</tr><?php $i++;}}else{?><tr><td colspan="6" align="center"><strong>No Record Found</strong></td></tr><?php }?> </tbody>
            </table>
			</form>
				</div>	
		<div class="dataTables_paginate paging_full_numbers" style="float:right"><?php echo $page_link; ?></div>
        </div> 
	</div></div> <!-- hiddenpars End -->
		</div><!-- widget End -->
	</div> <!-- wrapper end -->
</div>
</div>
    <!-- Main content ends -->