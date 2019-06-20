<script type="text/javascript" language="javascript">

	function delete_rec(id,redirectpage,option,keyword,limit,offset)
	{
		var ans = confirm("Are you sure, you want to delete user_id?");
		if(ans)
		{
			location.href = "<?php echo site_url("Pages/delete"); ?>/"+id+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset;
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
			window.location.href='<?php echo site_url("Pages/listPages/");?>/'+limit+'/';
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
			
			window.location.href='<?php echo site_url("Pages/searchListPages/");?>/'+limit+'/<?php echo $option.'/'.$keyword; ?>';
		}
	
	}
	
	function gomain(x)
	{
		
		if(x == 'all')
		{
			window.location.href= '<?php echo site_url("Pages/listPages/");?>';
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
	<div class="container-fluid">
		<!-- BEGIN PAGE HEADER-->
		<div class="row-fluid">
			<div class="span12">
				
				<h3 class="page-title"> Pages List </h3>
				
			</div>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="row-fluid">
			<div class="span12">
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
                        	<select name="getlimit" id="getlimit" class="small m-wrap" onchange="getlimit(this.value)" style="margin-bottom: 0">
                        <?php } if($search_type=='search') { ?>
                          	<select name="getlimit" id="getlimit" class="small m-wrap" onchange="getsearchlimit(this.value)" style="margin-bottom: 0">
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
					echo form_open('Pages/searchListPages/'.$limit,$attributes);?>
					<div class="pull-left">
					
						<input type="hidden" name="limit" id="search-limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
						<div style="margin-right:5px;">
							<!-- onchange="gomain(this.value)" -->
                            <select tabindex="1" class="small m-wrap" name="option" id="option" >
                            <!-- <option value="all">All</option> -->
                            <option value="">Select</option>
                            <option value="page_name" <?php if($option=='page_name'){?> selected="selected"<?php }?>>Pages Name</option>  
                            
                            </select>
                        </div>
					
					
					</div>
					<div class="input-append">
						<?php $keyword_data=($keyword != '1V1')?str_replace('-',' ',$keyword):'';?>
						<input type="text" class="m-wrap" name="keyword" id="keyword" value="<?php echo $keyword_data;?>" placeholder="Enter keyword">
						<div class="btn-group">
						<button type="submit" id="search" class="btn icn-only green"><i class="fa fa-search"></i></button>
						</div>
						<div class="btn-group">
						<?php echo anchor('Pages/listPages','<i class="fa fa-refresh"></i>','class="btn blue icn-only"');?>
						</div>
				    </div>
				   </form>
				   </div>
				   <?php /* ?>
					<div class="btn-group pull-right">
					<div class="btn-group">
					<?php echo ((isset($adminRights->eCommerce) && $adminRights->eCommerce->add==1) || checkSuperAdmin())?anchor('Pages/addPages','Add New <i class="icon-plus"></i>','class="btn green"'):'';
					?>
					</div>
					<?php if((isset($adminRights->eCommerce) && ($adminRights->eCommerce->update==1 || $adminRights->eCommerce->delete==1)) || checkSuperAdmin()){ ?>
					<div class="btn-group">
										<a data-toggle="dropdown" href="javascript://" class="btn blue">
										<i class="icon-cogs"></i> Action
										<i class="icon-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-right">
											<?php if((isset($adminRights->eCommerce->update) && $adminRights->eCommerce->update==1) || checkSuperAdmin()){?><li><a href="javascript:void(0)" onclick="setaction('chk[]','active', 'Are you sure, you want to activate selected record(s)?', 'frm_Pages');"><i class="icon-thumbs-up"></i> Active</a></li>
											<li><a href="javascript:void(0)" onclick="setaction('chk[]','inactive', 'Are you sure, you want to inactivate selected record(s)?', 'frm_Pages');"><i class="icon-thumbs-down"></i> Inactive</a></li>
											<?php } if((isset($adminRights->eCommerce->delete) && $adminRights->eCommerce->delete==1) || checkSuperAdmin()){ ?><li><a href="javascript:void(0)" onclick="setaction('chk[]','delete', 'Are you sure, you want to delete selected record(s)?', 'frm_Pages');"><i class="icon-trash"></i> Delete</a></li>
											<?php } ?>
											<li class="divider"></li>
										</ul>
										
									</div>
									<?php } ?>
					
					</div>
				    <?php */ ?>
					<div class="clearfix"></div>
					</div>
					<div id="content">
					
						<?php			 
					$attributes = array('name'=>'actionPages','id'=>'actionPages','data-target'=>'#content');
					echo form_open('Pages/actionPages',$attributes);?>
			
				<input type="hidden" name="offset" id="offset" value="<?php echo ($offset!='')?$offset:0; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
            	<input type="hidden" name="serach_keyword" id="serach_keyword" value="<?php echo $keyword_data; ?>" />
				<input type="hidden" name="serach_option" id="serach_option" value="<?php echo $option; ?>" />
					
            	   <input type="hidden" name="action" id="action" />
				   <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
					<table class="table-bordered table-striped table-condensed flip-content" id="s2">
					<thead class="flip-content">
					<tr>
					<th> Pages Title </th>
					<th >Action</th>
					</tr>
					</thead>
					<tbody>

					<?php
				if($result!=''){
				foreach($result as $row) {
					//print_r($row);die;
					?>
					<tr>
					<td> <?php echo $row->pages_title ?> </td>	
					
					<!--<td><span class="label <?php echo ($row->status=='Active')?'label-success':'label-danger'; ?>"><?php echo $row->status; ?></span></td>-->
					<td class="numeric">
					<p>
						<?php echo ((isset($adminRights->Pages) && $adminRights->Pages->update==1) || checkSuperAdmin())? anchor('Pages/editPages/'.$row->pages_id.'/'.$redirect_page.'/'.$option.'/'.$keyword.'/'.$limit.'/'.$offset,'<i class="fa fa-edit m-icon-white"></i>','class="btn blue icn-only mini"   title="Edit Pages"'):'';
						if((isset($adminRights->Pages) && $adminRights->Pages->delete==1) || checkSuperAdmin()){	 ?>
					
					
					<!--<a href="javascript:void(0);" onClick="delete_rec('<?php echo $row->Pages_id; ?>','<?php echo $redirect_page;?>','<?php echo $option?>','<?php echo $keyword?>','<?php echo $limit?>','<?php echo $offset; ?>')" title="Delete" class="btn red icn-only mini"><i class="icon-remove icon-white"></i></a>-->
					<?php } ?>
					</p>
					</td>

					</tr>
					<?php	} }else{?>
					<tr> <td colspan="2"><h3>No Record Found.</h3></td></tr>	
					<?php } ?>
					</tbody>
					</table>
				</form>
					<div class="row-fluid">
					<?php echo $page_link
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
		
		<?php if($msg!=''){
			
	     if($msg == "insert"){ $error = ADD_NEW_COLOR;}
            if($msg == "update"){ $error = UPDATE_COLOR;}
            if($msg == "delete"){ $error = DELETE_COLOR;}
			if($msg == "active") {  $error = ACTIVE_COLOR;}
			if($msg == "inactive"){ $error = INACTIVE_COLOR;}
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