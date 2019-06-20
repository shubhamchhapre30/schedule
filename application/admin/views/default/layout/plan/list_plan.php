<script type="text/javascript" language="javascript">

	function delete_rec(id,redirectpage,option,keyword,limit,offset)
	{
		var ans = confirm("Are you sure, you want to delete user?");
		if(ans)
		{
			location.href = "<?php echo site_url("plan/delete"); ?>/"+id+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset;
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
			window.location.href='<?php echo site_url("plan/list_plan/");?>/'+limit+'/';
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
			
			window.location.href='<?php echo site_url("plan/search_list_plan/");?>/'+limit+'/<?php echo $option.'/'.$keyword; ?>';
		}
	
	}
	
	function gomain(x)
	{
		
		if(x == 'all')
		{
			window.location.href= '<?php echo site_url("plan/list_plan/");?>';
		}
		
	}
	
	
function setchecked(elemName){
	elem = document.getElementsByName(elemName);
	if(document.getElementById("titleCheck").checked == true)
	{
		for(i=0;i<elem.length;i++){
			elem[i].checked=1;
			var pr = elem[i].parentNode;
			pr.className = 'checked';
		}
	}
	else
	{
		for(i=0;i<elem.length;i++){
			elem[i].checked=0;
			var pr = elem[i].parentNode;
			pr.className = '';
		}
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
			//document.formname.submit();
			//alert(formname);
			$("#"+formname).submit();
		}		
		
	}
}



function reset_password(id,redirectpage,option,keyword,limit,offset)
{
	var ans = confirm("Are you sure, you want to send reset password E-mail for this Customer?");
	if(ans)
	{
		location.href = "<?php echo site_url("plan/reset_password_user"); ?>/"+id+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset;
	}else{
		return false;
	}
}
</script>

<!-- BEGIN CONTAINER -->

<!-- BEGIN PAGE -->
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- <div id="portlet-config" class="modal hide">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button"></button>
			<h3>portlet Settings</h3>
		</div>
		<div class="modal-body">
			<p>
				Here will be a configuration form
			</p>
		</div>
	</div> -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE CONTAINER-->
	<div class="container-fluid admin-list">
		<!-- BEGIN PAGE HEADER-->
		<div class="row">
			<div class="col-md-12">
				
				<h3 class="page-title"> Plan List <noscript> DIsable </noscript></h3>
				
			</div>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-success" style="display: none;">
					<button class="close" data-dismiss="alert"></button>
					<span class="label label-important"></span>
				</div>
				<!-- BEGIN SAMPLE TABLE PORTLET-->
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">
							<!-- <i class="icon-cogs"></i> -->
						</div>
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>
							<a href="#portlet-config" data-toggle="modal" class="config"></a>
							<a href="javascript:;" class="reload"></a>
							<a href="javascript:;" class="remove"></a> -->
							
                       <div class="pull-right">
					    <?php if($search_type=='normal') { ?>
                        	<select name="getlimit" id="getlimit" class="small m-wrap" onchange="getlimit(this.value)" style=" ">
                        <?php } if($search_type=='search') { ?>
                          	<select name="getlimit" id="getlimit" class="small m-wrap" onchange="getsearchlimit(this.value)" style=" ">
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
						
						</div>
					</div>
					<?php
					// echo '<pre>';
					// print_r($result);die;
					?>
					<div class="portlet-body flip-scroll" >
					<div class="table-toolbar">
						<div class="pull-left">
					<?php			 
					$attributes = array('name'=>'frm_search','id'=>'frm_search','data-async'=>'','data-target'=>'#content');
					echo form_open('plan/search_list_plan/'.$limit,$attributes);?>
					<div class="pull-left">
						
						<input type="hidden" name="limit" id="search-limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
						
						<div style="margin-right:5px;">
                            <select tabindex="1" class="small m-wrap medium" name="option" id="option" >
                            <option value="">Select</option>
                            <option value="plan_title" <?php if($option=='plan_title'){?> selected="selected"<?php }?>>Plan Title</option>
							 <!--<option value="last_name" <?php if($option=='last_name'){?> selected="selected"<?php }?>>Last Name</option>
							<option value="email" <?php if($option=='email'){?> selected="selected"<?php }?>>Email</option>
							<option value="mobile_no" <?php if($option=='mobile_no'){?> selected="selected"<?php }?>>Mobile Number</option>-->
                            </select>
                        </div>
					
					
					</div>
					<div class="input-append">
						<?php $keyword_data=($keyword != '1V1')?str_replace('-',' ',$keyword):'';?>
                                            <input type="text" class="m-wrap input-width" name="keyword" id="keyword" value="<?php echo $keyword_data;?>" placeholder="Enter keyword" >
                                            <div class="btn-group btn-margin" >
						<button type="submit" id="search" class="btn icn-only green"><i class="fa fa-search"></i></button>
						</div>
						<div class="btn-group">
						<?php echo anchor('plan/list_plan','<i class="fa fa-refresh"></i>','class="btn green icn-only"');?>
						</div>
				    </div>
				   </form>
				   </div>
				   <?php //print_r($adminRights);?>
					<div class="btn-group pull-right">
                                            <div class="btn-group btn-space" >
					<?php echo ((isset($adminRights->plan) && $adminRights->plan->add==1) || checkSuperAdmin())?anchor('plan/add','Add New <i class="fa fa-plus"></i>','class="btn green"'):''?>
					</div>
					<?php if((isset($adminRights->plan) && ($adminRights->plan->update==1 || $adminRights->plan->delete==1)) || checkSuperAdmin()){ ?>
						
					<div class="btn-group">
										<a data-toggle="dropdown" href="javascript://" class="btn blue">
										<i class="fa fa-cogs"></i> Action
										<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-right">
											<?php if((isset($adminRights->plan->update) && $adminRights->plan->update==1) || checkSuperAdmin()){?><li><a href="javascript:void(0)" onclick="setaction('chk[]','active', 'Are you sure, you want to activate selected record(s)?', 'action_user');"><i class="fa fa-thumbs-up"></i> Active</a></li>
											<li><a href="javascript:void(0)" onclick="setaction('chk[]','inactive', 'Are you sure, you want to inactivate selected record(s)?', 'action_user');"><i class="fa fa-thumbs-down"></i> Inactive</a></li>
											<?php } if((isset($adminRights->plan->delete) && $adminRights->plan->delete==1) || checkSuperAdmin()){ ?><li><a href="javascript:void(0)" onclick="setaction('chk[]','delete', 'Are you sure, you want to delete selected record(s)?', 'action_user');"><i class="fa fa-trash"></i> Delete</a></li>
												<?php } ?>
											<li class="divider"></li>
										</ul>
									</div>
						<?php } ?>
					</div>
					<div class="clearfix"></div>
					</div>
					<div id="content">
					
						<?php			 
					$attributes = array('name'=>'action_user','id'=>'action_user');
					echo form_open('plan/action',$attributes);?>
			
				<input type="hidden" name="offset" id="offset" value="<?php echo ($offset!='')?$offset:0; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
            	<input type="hidden" name="serach_keyword" id="serach_keyword" value="<?php echo $keyword_data; ?>" />
				<input type="hidden" name="serach_option" id="serach_option" value="<?php echo $option; ?>" />
					
            	   <input type="hidden" name="action" id="action" />
				   <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
					<table class="table-change table-striped table-condensed flip-content" id="s2">
					<thead class="flip-content">
					<tr>
					<th width="10" align="center"><input type="checkbox" id="titleCheck" name="titleCheck"  onclick="setchecked('chk[]')" class="group-checkable"></th>
					<th>Plan Title</th>
					<th>Plan Description</th>
					<th>Plan Price</th>
					<th>Plan Duration</th>
					<!--<th>Plan Status</th>
					<th>Email</th>
					<!--<th>Address</th>
					<th>City</th>
					<th>State</th>
					<th>Country</th>-->
									
				    <th>Status</th>
					<?php if((isset($adminRights->plan) && ($adminRights->plan->update==1 || $adminRights->plan->delete==1)) || checkSuperAdmin()){ ?>
				    <th>Action</th><?php } ?>
					</tr>
					</thead>
					<tbody>

					<?php
				if($result!=''){
				foreach($result as $row) {
					
					$acclass = "label-success";
					if($row->plan_status == "Inactive")
					{
						$acclass = "label-danger";
					}
					//print_r($row);die;
					?>
					<tr>
					<td align="center"> <input type="checkbox"  class="checkboxes" name="chk[]" id="titleCheck<?php echo  $row->plan_id;?>" value="<?php echo $row->plan_id;?>"></td>
					<td><?php echo $row->plan_title; ?></td>
					<td><?php echo $row->plan_description; ?></td>
					<td><?php echo $row->plan_currency_code.''.$row->plan_price; ?></td>
					<td><?php echo $row->plan_duration; ?></td>
					<!--<td><?php  echo $row->user_type; ?></td>-->	
					
					
					<!--<td><?php echo $row->address; ?></td>
					<td><?php echo $row->city; ?></td>
					<td><?php echo $row->state; ?></td>
					<td><?php echo $row->country_name; ?></td>-->
									
				    <td align="center"><span class="label <?php echo $acclass; ?> wb"><?php echo ucwords($row->plan_status); ?></span></td>
					
					<?php
					if((isset($adminRights->plan) && ($adminRights->plan->update==1 || $adminRights->plan->delete==1)) || checkSuperAdmin()){ ?>
					<td align="center">
					<p>	
						<?php 
					/* if((isset($adminRights->plan->update) && $adminRights->plan->update==1) || checkSuperAdmin()){?>				
						<a href="javascript:void(0);" onClick="reset_password('<?php echo $row->plan_id; ?>','<?php echo $redirect_page;?>','<?php echo $option?>','<?php echo $keyword?>','<?php echo $limit?>','<?php echo $offset; ?>')" title="Send reset password email" class="btn purple icn-only mini"><i class="icon-envelope-alt m-icon-white"></i></a>
							
					<?php 
					 }*/
					 if((isset($adminRights->plan->update) && $adminRights->plan->update==1) || checkSuperAdmin()){
						echo anchor('plan/edit/'.$row->plan_id.'/'.$redirect_page.'/'.$option.'/'.$keyword.'/'.$limit.'/'.$offset,'<i class="fa fa-edit m-icon-white"></i>','class="btn blue icn-only mini"   title="Edit admin"');
					} ?>
					<?php
					 if((isset($adminRights->plan->delete) && $adminRights->plan->delete==1) || checkSuperAdmin()){?>
					<a href="javascript:void(0);" onClick="delete_rec('<?php echo $row->plan_id; ?>','<?php echo $redirect_page;?>','<?php echo $option?>','<?php echo $keyword?>','<?php echo $limit?>','<?php echo $offset; ?>')" title="Delete" class="btn red icn-only mini"><i class="fa fa-remove fa fa-white"></i></a>
					<?php } ?>
					</p></td><?php } ?>
					</tr>
					<?php	} }else{?>
					<tr> <td colspan="<?php echo (checkSuperAdmin())?'8':'7';  ?>"><h3>No Record Found.</h3></td></tr>	
					<?php } ?>
						
					</tbody>
					</table>
				</form>
					<div class="row" style="margin-top: 15px;">
					<?php echo $page_link;
					?>

					</div>
					
					</div>
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->

			</div>
		</div>
		<!-- END PAGE CONTENT-->
	</div>
	<!-- END PAGE CONTAINER-->
	
<script>

	$(document).ready(function() {
		
		<?php 
	
		$msg = $this->session->flashdata('msg');
		if($msg!=''){
			
	     if($msg == "insert"){ $error = ADD_NEW_RECORD;}
            if($msg == "update"){ $error = UPDATE_RECORD;}
            if($msg == "delete"){ $error = DELETE_RECORD;}
			if($msg == "active") {  $error = ACTIVE_RECORD;}
			if($msg == "inactive"){ $error = INACTIVE_RECORD;}
			if($msg == "rights"){ $error = ASSIGN_RIGHTS;}
			if($msg == "sent"){ $error = RESET_SENT;}	
    ?> 
   
      $.growlUI('<?php echo $error; ?>');
      
/*
      var unique_id=$.gritter.add({
                        title: 'Success',
                        text: '<?php //echo $m; ?>',
                         class_name: 'gritter-light'
                       
                   });*/

                 
     
   <?php } ?>
   
   
    
});
</script>

	<!-- END PAGE -->
</div>
<!-- END CONTAINER -->
