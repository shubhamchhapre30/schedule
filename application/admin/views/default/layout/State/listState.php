<link rel="stylesheet" href="<?php echo base_url().getThemeName() ?>/css/alertify.core.css?Ver<?php echo VERSION;?>" />
<link rel="stylesheet" href="<?php echo base_url().getThemeName() ?>/css/alertify.default.css?Ver<?php echo VERSION;?>" id="toggleCSS" />
<script src="<?php echo base_url().getThemeName() ?>/js/alertify.min.js?Ver<?php echo VERSION;?>"></script>
<script>
	$(document).ready(function() {
	bindJquery();	
	// $()

    $('#frm_search').live('submit', function(event) {
        var $form = $(this);
        $('#search-limit').val($('#limit').val());
        var $target = $($form.attr('data-target'));
        
 		if($form.find('#option').val()!='' && $form.find('#keyword').val().trim()!=''){
	        $.ajax({
	            type: $form.attr('method'),
	            url: $form.attr('action'),
	            cache: false,
	            data: $form.serialize(),
	            beforeSend : function() {
					blockUI('.portlet-body');
				},success: function(data, status) {
	                $target.html(data);
	                bindJquery();	
	            },complete : function() {
					unblockUI('.portlet-body');
				},
	        });
        }
		else if($form.find('#option').val()=='')
	 	{
	 		alertify.alert("Please select search criteria", function (e) { return false; });
	 	}
	 	else
	 	{
	 		alertify.alert("Please enter keyword", function (e) { return false; });
	 	}
		event.preventDefault();
    });

	});

function getOrderRecord(obj){
		$("#sort_on").val($(obj).attr("data-sort-on"));
		$("#sort_type").val($(obj).attr("data-sort-type"));

		var $form = $('#frm_search');
        $('#search-limit').val($('#limit').val());
        var $target = $($form.attr('data-target'));
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            cache: false,
            data: $form.serialize(),
            beforeSend : function() {
				blockUI('.portlet-body');
			},success: function(data, status) {
                $target.html(data);
                bindJquery();	
            },complete : function() {
				unblockUI('.portlet-body');
			},
        });
	}

function gomain(x)
	{
		
		if(x == 'all')
		{
			window.location.href= '<?php echo site_url('State/listState');?>';
		}
		
	}	
function bindJquery()
{
	
	jQuery('input').uniform();
	jQuery('.group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });
            
   
}
function getData(limit,offset)
{

	var offset=$('#offset').val();
	var option=($('#option').val()!='')?$('#option').val():'1V1';
	var sort_on=($('#sort_on').val()!='')?$('#sort_on').val():'1V1';
	var sort_type=($('#sort_type').val()!='')?$('#sort_type').val():'1V1';
	var keyword=($('#keyword').val()!='')?$('#keyword').val().split(' ').join('-'):'1V1';
	var redirect_page=$('#redirect_page').val();
	if(redirect_page=='listState'){ 
	var url='<?php echo site_url('State') ?>/'+redirect_page+'/'+limit+'/'+offset;
	}else{
		var url='<?php echo site_url('State/') ?>/'+redirect_page+'/'+limit+'/'+option+'/'+keyword+'/'+sort_on+'/'+sort_type+'/'+offset;
	} 

	$.ajax({
			url : url,
			cache: false,
			beforeSend : function() {
				blockUI('.portlet-body');
			},
			success : function(response) {
				// alert(response);
				$('#content').html('');
				$('#content').html(response);
				bindJquery();
			},
			complete : function() {
				unblockUI('.portlet-body');
			},
	});
	
}
function delete_rec(id,redirectpage,option,keyword,limit,offset)
	{
		var ans = confirm("Are you sure, you want to delete  State?");
		if(ans)
		{
/*			location.href = "<?php //echo base_url(); ?>State/deleteState/"+id+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset;*/
			$.ajax({
			url : "<?php echo site_url('State/deleteState') ?>/"+id+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset,
			cache: false,
			
			beforeSend : function() {
				blockUI('.portlet-body');
			},
			success : function(data) {
				if(data=='done'){
					$.growlUI('<?php echo DELETE_RECORD; ?>'); 
					getData(limit,offset);
					
				}
			},
			complete : function() {
				unblockUI('.portlet-body');
			}
		});	
			
		}else{
			return false;
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
			//$('#frm_State').submit();
		var $form = $('#actionState');
        var $target = $($form.attr('data-target'));
 		var limit=$('#limit').val();
 		var offset=$('#offset').val();
 		 //alert($form.attr('action'));return false;
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            cache: false,
            dataType:'json',
            data: $form.serialize(),
            beforeSend : function() {
				blockUI('.portlet-body');
			},success: function(res, status) {
				// alert(res);
                if(res.status=='done'){
                	$.growlUI(res.msg); 
                	getData(limit,offset);	
                	//bindJquery();
                }
                
            },complete : function() {
				unblockUI('.portlet-body');
			},
        });
		}		
		
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
			/*window.location.href='<?php //echo base_url();?>State/list_State/'+limit;*/
			var offset=$('#offset').val();
			var option=($('#option').val()!='')?$('#option').val():'1V1';
			var sort_on=($('#sort_on').val()!='')?$('#sort_on').val():'1V1';
			var sort_type=($('#sort_type').val()!='')?$('#sort_type').val():'1V1';
			var keyword=($('#keyword').val()!='')?$('#keyword').val().split(' ').join('-'):'1V1';
			var redirect_page=$('#redirect_page').val();
			if(redirect_page=='listState'){ 
			var url='<?php echo site_url('State') ?>/'+redirect_page+'/'+limit;
			}else{
				var url='<?php echo site_url('State/') ?>/'+redirect_page+'/'+limit+'/'+option+'/'+keyword+'/'+sort_on+'/'+sort_type;
			}

			$('#search-limit').val(limit);
			$.ajax({
			url : url,
			cache: false,
			beforeSend : function() {
				blockUI('.portlet-body');
			},
			success : function(data) {
				$('#content').html(data);
				bindJquery();
			},
			complete : function() {
				unblockUI('.portlet-body');
			}
		});

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
			$('#search-limit').val(limit);
			var offset=$('#offset').val();
			var option=($('#option').val()!='')?$('#option').val():'1V1';
			var sort_on=($('#sort_on').val()!='')?$('#sort_on').val():'1V1';
			var sort_type=($('#sort_type').val()!='')?$('#sort_type').val():'1V1';
			var keyword=($('#keyword').val()!='')?$('#keyword').val().split(' ').join('-'):'1V1';
			var redirect_page=$('#redirect_page').val();
			if(redirect_page=='listState'){ 
			var url='<?php echo site_url('State') ?>/'+redirect_page+'/'+limit;
			}else{
				var url='<?php echo site_url('State/') ?>/'+redirect_page+'/'+limit+'/'+option+'/'+keyword+'/'+sort_on+'/'+sort_type;
			} 

			$.ajax({
			url : url,
			cache: false,
			beforeSend : function() {
				blockUI('.portlet-body');
			},
			success : function(data) {
				$('#content').html(data);
			},
			complete : function() {
				unblockUI('.portlet-body');
			}
		});
		}
	
	}
	function donwloadCSV(){
		$('#downloadCSV #opt').val($('#option').val());
		$('#downloadCSV #key').val($('#keyword').val());
		$('#downloadCSV').submit();
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
				
				<h3 class="page-title"> State List </h3>
				
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
					echo form_open('State/searchListState/'.$limit,$attributes);?>
					<div class="pull-left">
						<input type="hidden" name="sort_on" id="sort_on" value="<?php echo $sort_on; ?>" />
            			<input type="hidden" name="sort_type" id="sort_type" value="<?php echo $sort_type; ?>" />
						<input type="hidden" name="limit" id="search-limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
						<div style="margin-right:5px;">
							<!-- onchange="gomain(this.value)" -->
                            <select tabindex="1" class="small m-wrap" name="option" id="option" >
                            <option value="">Select</option>
                            <option value="state_name" <?php if($option=='state_name'){?> selected="selected"<?php }?>>State Name</option>
                            <option value="country_name" <?php if($option=='country_name'){?> selected="selected"<?php }?>>Country Name</option>  
                            <option value="status" <?php if($option=='status'){?> selected="selected"<?php }?>>Status</option>
                            </select>
                        </div>
					
					
					</div>
					<div class="input-append">
						<?php $keyword_data=($keyword != '1V1')?str_replace('-',' ',$keyword):'';?>
						<input type="text" class="m-wrap" name="keyword" id="keyword" value="<?php echo $keyword_data;?>" placeholder="Enter keyword">
						<div class="btn-group">
						<button type="submit" id="search" class="btn icn-only green"><i class="icon-search"></i></button>
						</div>
						<div class="btn-group">
						<?php echo anchor('State/listState','<i class="icon-refresh"></i>','class="btn blue icn-only"');?>
						</div>
				    </div>
				   </form>
				   </div>
					<div class="btn-group pull-right">
					<div class="btn-group">
					<?php echo ((isset($adminRights->Globalization) && $adminRights->Globalization->add==1) || checkSuperAdmin())?anchor('State/addState','Add New <i class="icon-plus"></i>','class="btn green"'):'';
					?>
					</div>
					<?php if((isset($adminRights->Globalization) && ($adminRights->Globalization->update==1 || $adminRights->Globalization->delete==1)) || checkSuperAdmin()){ ?>
					<div class="btn-group">
										<a data-toggle="dropdown" href="javascript://" class="btn blue">
										<i class="icon-cogs"></i> Action
										<i class="icon-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-right">
											<?php if((isset($adminRights->Globalization->update) && $adminRights->Globalization->update==1) || checkSuperAdmin()){?><li><a href="javascript:void(0)" onclick="setaction('chk[]','active', 'Are you sure, you want to activate selected record(s)?', 'frm_State');"><i class="icon-thumbs-up"></i> Active</a></li>
											<li><a href="javascript:void(0)" onclick="setaction('chk[]','inactive', 'Are you sure, you want to inactivate selected record(s)?', 'frm_State');"><i class="icon-thumbs-down"></i> Inactive</a></li>
											<?php } if((isset($adminRights->Globalization->delete) && $adminRights->Globalization->delete==1) || checkSuperAdmin()){ ?><li><a href="javascript:void(0)" onclick="setaction('chk[]','delete', 'Are you sure, you want to delete selected record(s)?', 'frm_State');"><i class="icon-trash"></i> Delete</a></li>
											<?php } ?>
											<li class="divider"></li>
										</ul>
										
									</div>
									<?php } ?>
					<!--<div class="btn-group">
						<a href="javascript://" onclick="donwloadCSV()" class="btn black"><i class="icon-download"></i> download</a>
					</div>-->
					</div><?php $att=array('id'=>'downloadCSV','name'=>'downloadCSV','class'=>'no-margin');
										echo form_open('State/downloadState',$att) ?>
										<input type="hidden" value="" id="opt" name="opt" />
										<input type="hidden" value="" id="key" name="key" />
									</form>
					<div class="clearfix"></div>
					</div>
					<div id="content">
					
						<?php			 
					$attributes = array('name'=>'actionState','id'=>'actionState','data-target'=>'#content');
					echo form_open('State/actionState',$attributes);?>
			
				<input type="hidden" name="offset" id="offset" value="<?php echo ($offset!='')?$offset:0; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
            	<input type="hidden" name="serach_keyword" id="serach_keyword" value="<?php echo $keyword_data; ?>" />
				<input type="hidden" name="serach_option" id="serach_option" value="<?php echo $option; ?>" />
					
            	   <input type="hidden" name="action" id="action" />
				   <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
					<table class="table table-bordered table-striped table-condensed flip-content" id="s2">
					<thead class="flip-content">
					<tr>
					<th width="10" align="center"><input type="checkbox" data-set=".checkboxes" class="group-checkable"></th>
					<th class="<?php echo $sort_on=='state_name'?($sort_type=='asc'?'sorting_desc':'sorting_asc'):'sorting'; ?>" data-sort-on="state_name" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Province Name</th>
					<th class="<?php echo $sort_on=='country_name'?($sort_type=='asc'?'sorting_desc':'sorting_asc'):'sorting'; ?>" data-sort-on="country_name" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Country Name</th>
					<th class="<?php echo $sort_on=='status'?($sort_type=='asc'?'sorting_desc':'sorting_asc'):'sorting'; ?>" data-sort-on="status" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Status</th>
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
					<td align="center"> <input type="checkbox"  class="checkboxes" name="chk[]" id="titleCheck<?php echo  $row->state_id;?>" value="<?php echo $row->state_id;?>"></td>
				
					<td><?php echo ucwords($row->state_name); ?></td>
					<td><?php echo ucwords($row->country_name); ?></td>
					<td><span class="label <?php echo ($row->status=='Active')?'label-success':'label-danger'; ?>"><?php echo $row->status; ?></span></td>
					<td class="numeric">
					<p>
						<?php echo ((isset($adminRights->Globalization) && $adminRights->Globalization->update==1) || checkSuperAdmin())? anchor('State/editState/'.$row->state_id.'/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset,'<i class="icon-edit m-icon-white"></i>','class="btn blue icn-only mini"   title="Edit State"'):'';
						if((isset($adminRights->Globalization) && $adminRights->Globalization->delete==1) || checkSuperAdmin()){	 ?>
					
					
					<a href="javascript:void(0);" onClick="delete_rec('<?php echo $row->state_id; ?>','<?php echo $redirect_page;?>','<?php echo $option?>','<?php echo $keyword?>','<?php echo $limit?>','<?php echo $offset; ?>')" title="Delete" class="btn red icn-only mini"><i class="icon-remove icon-white"></i></a>
					<?php } ?>
					</p>
					</td>

					</tr>
					<?php	} }else{?>
					<tr> <td colspan="7"><h3>No Record Found.</h3></td></tr>	
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
			
	     if($msg == "insert"){ $error = ADD_NEW_STATE;}
            if($msg == "update"){ $error = UPDATE_STATE;}
            if($msg == "delete"){ $error = DELETE_STATE;}
			if($msg == "active") {  $error = ACTIVE_STATE;}
			if($msg == "inactive"){ $error = INACTIVE_STATE;}
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