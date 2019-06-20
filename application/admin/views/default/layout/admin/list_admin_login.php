<script type="text/javascript" language="javascript">
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
			document.getElementById(formname).submit();
		}		
	}
}


	
function delete_admin()
	{
		var ans = confirm("Are you sure to delete Admin Login Details?");
		if(ans)
		{
			location.href = "<?php echo base_url(); ?>delete_login/delete_admin_login";
			
		}else{
			return false;
		}
	}
	
</script>



<div id="content">  

 
            
	<?php if($msg != ""){
            if($msg == "delete"){ $error = 'Login details has been deleted successfully.';}
    ?>
        <div class="column full">
            <span class="message information"><strong><?php echo $error;?></strong></span>
        </div>
    <?php } ?>
    
	<div class="clear"></div>
	<div class="column full">
	
	<div class="box">		
		<div class="box themed_box">
		<h2 class="box-header">Users Login Details</h2>
			
			<div class="box-content box-table">
             <form name="frm_listlogin" id="frm_listlogin" action="<?php echo base_url();?>admin/action_login" method="post">
               <input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
               <input type="hidden" name="action" id="action" />
			<table class="tablebox">  
				
                <div id="topbar" style="border:#CCC solid 1px;">

                    <div style="float:right;">
                      	<a href="javascript:void(0)"  onclick="setaction('chk[]','delete', 'Are you sure, you want to delete selected record(s)?', 'frm_listlogin')" class="button white" style="margin:0px;"><span class="icon_text cancel"></span>Delete</a>
                        
                        
                        
                        <a href="javascript:void(0)"  onclick="delete_admin()" class="button white" style="margin:0px;"><span class="icon_text cancel"></span>Delete Admin Login</a>
                      
                    </div>
                    
 
                </div>
                
          		<thead class="table-header">
					<tr> 
                        <th class="first tc"><a href="javascript:void(0)" onclick="javascript:setchecked('chk[]',1)" style="color:#000;"><?php echo "Check All"; ?></a>|<a href="javascript:void(0)" onclick="javascript:setchecked('chk[]',0)" style="color:#000;"><?php echo "Clear All"; ?></a></th>
                        <th>Username</th>
                        <th>Email</th>                                  
                        <th>Admin Type</th>                                     
                        <th>Signup IP Address</th>
                        <th>Date</th>                                  
                    </tr>
                </thead>
                
                <tbody class="openable-tbody">
				<?php
                    if($result)
                    {
                        $i=0;
                        foreach($result as $row)
                        {
                            if($i%2=="0")
                            {
                                $cl = "odd";	
                            }else{	
                                $cl = "even";	
                            }
                  ?>
					<tr class="<?php echo $cl; ?>"> 
                    	<td ><input type="checkbox" name="chk[]" id="chk" value="<?php echo $row->login_id;?>" /></td>
                   		<td><?php echo $row->username; ?></td>
                        <td><?php echo $row->email; ?></td>
                        <td><?php if($row->admin_type==1) { echo "Super Admin"; } elseif($row->admin_type==2){ echo "Administrator"; } ?></td>
						<td><?php echo $row->login_ip; ?></td>                                      
                        <td><?php echo date('d M,Y H:i:s',strtotime($row->login_date)); ?></td>
                    </tr>
					<?php
							$i++;
							}
						} else { ?>
						<tr class="alter"><td colspan="15" align="center" valign="middle" height="30">No Admin login has been added yet</td></tr>
					<?php 	}
					?> 
                  	</tr>
                </tbody>
                </table>
                </form>
                <ul class="pagination" style="margin-top: 15px;">
					<?php  echo $page_link; ?>
                </ul>

           </div>
       </div>
    </div>
</div>
