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
 
        event.preventDefault();
    });
    
/*
    $('#frm_EmailTemplate').on('submit', function(event) {
        var $form = $(this);
        var $target = $($form.attr('data-target'));
 		var limit=$('#limit').val();
 		var offset=$('#offset').val();
 		
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            beforeSend : function() {
				App.blockUI('.portlet-body');
			},success: function(res, status) {
                if(res=='done'){
                	getData(limit,offset);	
                	//bindJquery();
                }
            },complete : function() {
				App.unblockUI('.portlet-body');
			},
        });
 
        event.preventDefault();
    });*/


	});
function gomain(x)
	{
		
		if(x == 'all')
		{
			window.location.href= '<?php echo site_url('EmailTemplate/listEmailTemplate');?>';
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
            
     /*
      $('#frm_search').on('submit', function(event) {
             var $form = $(this);
              alert($('#limit').val());
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
      
             event.preventDefault();
         });*/
     
    
}
function getData(limit,offset)
{
	/*var limit=($('#limit').val()!='')?$('#limit').val():'<?php //echo $limit ?>';*/
	var redirect_page=$('#redirect_page').val();
	if(redirect_page=='listEmailTemplate'){ 
	var url='<?php echo site_url('EmailTemplate') ?>/'+redirect_page+'/'+limit+'/'+offset;
	}else{
		var url='<?php echo site_url('EmailTemplate/') ?>/'+redirect_page+'/'+limit+'/<?php echo $option.'/'.$keyword.'/'; ?>'+offset;
	} 
	// alert(url);
	
	
	$.ajax({
			url : url,
			cache: false,
			beforeSend : function() {
				//blockUI('.portlet-body');
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
		var ans = confirm("Are you sure, you want to delete  EmailTemplate?");
		if(ans)
		{
/*			location.href = "<?php //echo base_url(); ?>EmailTemplate/deleteEmailTemplate/"+id+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset;*/
			$.ajax({
			url : "<?php echo site_url('EmailTemplate/deleteEmailTemplate') ?>/"+id+"/"+redirectpage+"/"+option+"/"+keyword+"/"+limit+"/"+offset,
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
			//$('#frm_EmailTemplate').submit();
		var $form = $('#actionEmailTemplate');
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
			/*window.location.href='<?php //echo base_url();?>EmailTemplate/list_EmailTemplate/'+limit;*/
			var offset=$('#offset').val();
			var option=$('#option').val();
			var keyword=($('#keyword').val()!=undefined && $('#keyword').val()!='')?$('#keyword').val().split(' ').join('-'):'1V1';
			var redirect_page=$('#redirect_page').val();
			if(redirect_page=='listEmailTemplate'){ 
			var url='<?php echo site_url('EmailTemplate') ?>/'+redirect_page+'/'+limit;
			}else{
				var url='<?php echo site_url('EmailTemplate/') ?>/'+redirect_page+'/'+limit+'/'+option+'/'+keyword;
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
			var option=$('#option').val();
			var keyword=($('#keyword').val()!=undefined && $('#keyword').val()!='')?$('#keyword').val().split(' ').join('-'):'1V1';
			var redirect_page=$('#redirect_page').val();
			if(redirect_page=='listEmailTemplate'){ 
			var url='<?php echo site_url('EmailTemplate') ?>/'+redirect_page+'/'+limit;
			}else{
				var url='<?php echo site_url('EmailTemplate/') ?>/'+redirect_page+'/'+limit+'/'+option+'/'+keyword;
			} 
			
			/*window.location.href='<?php //echo base_url();?>EmailTemplate/search_list_EmailTemplate/'+limit+'/<?php //echo $option.'/'.$keyword; ?>';*/
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
	<div class="container-fluid admin-list">
		<!-- BEGIN PAGE HEADER-->
		<div class="row">
			<div class="col-md-12">
				
				<h3 class="page-title"> EmailTemplate List </h3>
				
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
				<?php $keyword_data=($keyword != '1V1')?str_replace('-',' ',$keyword):'';?>
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
						 <?php /* ?>
					<div class="table-toolbar">
						<div class="pull-left">
					<?php			 
					$attributes = array('name'=>'frm_search','id'=>'frm_search','data-async'=>'','data-target'=>'#content');
					echo form_open('EmailTemplate/searchListEmailTemplate/'.$limit,$attributes);?>
					<div class="pull-left">
					
						<input type="hidden" name="limit" id="search-limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
						<div style="margin-right:5px;">
							<!-- onchange="gomain(this.value)" -->
                            <select tabindex="1" class="small m-wrap" name="option" id="option" >
                            <!-- <option value="all">All</option> -->
                            <option value="">Select</option>
                            <option value="page_name" <?php if($option=='page_name'){?> selected="selected"<?php }?>>EmailTemplate Name</option>  
                            
                            </select>
                        </div>
					
					
					</div>
					<div class="input-append">
						
						<input type="text" class="m-wrap" name="keyword" id="keyword" value="<?php echo $keyword_data;?>" placeholder="Enter keyword">
						<div class="btn-group">
						<button type="submit" id="search" class="btn icn-only green"><i class="icon-search"></i></button>
						</div>
						<div class="btn-group">
						<?php echo anchor('EmailTemplate/listEmailTemplate','<i class="icon-refresh"></i>','class="btn blue icn-only"');?>
						</div>
				    </div>
				   </form>
				   </div>
				  
					<div class="btn-group pull-right">
					<div class="btn-group">
					<?php echo ((isset($adminRights->eCommerce) && $adminRights->eCommerce->add==1) || checkSuperAdmin())?anchor('EmailTemplate/addEmailTemplate','Add New <i class="icon-plus"></i>','class="btn green"'):'';
					?>
					</div>
					<?php if((isset($adminRights->eCommerce) && ($adminRights->eCommerce->update==1 || $adminRights->eCommerce->delete==1)) || checkSuperAdmin()){ ?>
					<div class="btn-group">
										<a data-toggle="dropdown" href="javascript://" class="btn blue">
										<i class="icon-cogs"></i> Action
										<i class="icon-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-right">
											<?php if((isset($adminRights->eCommerce->update) && $adminRights->eCommerce->update==1) || checkSuperAdmin()){?><li><a href="javascript:void(0)" onclick="setaction('chk[]','active', 'Are you sure, you want to activate selected record(s)?', 'frm_EmailTemplate');"><i class="icon-thumbs-up"></i> Active</a></li>
											<li><a href="javascript:void(0)" onclick="setaction('chk[]','inactive', 'Are you sure, you want to inactivate selected record(s)?', 'frm_EmailTemplate');"><i class="icon-thumbs-down"></i> Inactive</a></li>
											<?php } if((isset($adminRights->eCommerce->delete) && $adminRights->eCommerce->delete==1) || checkSuperAdmin()){ ?><li><a href="javascript:void(0)" onclick="setaction('chk[]','delete', 'Are you sure, you want to delete selected record(s)?', 'frm_EmailTemplate');"><i class="icon-trash"></i> Delete</a></li>
											<?php } ?>
											<li class="divider"></li>
										</ul>
										
									</div>
									<?php } ?>
					
					</div>
				   
					<div class="clearfix"></div>
					</div>
						  <?php */ ?>
					<div id="content">
					
						<?php			 
					$attributes = array('name'=>'actionEmailTemplate','id'=>'actionEmailTemplate','data-target'=>'#content');
					echo form_open('EmailTemplate/actionEmailTemplate',$attributes);?>
			
				<input type="hidden" name="offset" id="offset" value="<?php echo ($offset!='')?$offset:0; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
            	<input type="hidden" name="serach_keyword" id="serach_keyword" value="<?php echo $keyword_data; ?>" />
				<input type="hidden" name="serach_option" id="serach_option" value="<?php echo $option; ?>" />
					
            	   <input type="hidden" name="action" id="action" />
				   <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
					<table class="table-change table-striped table-condensed flip-content" id="s2">
					<thead class="flip-content">
					<tr>
					<th width="850">Email Template Name</th>
					<!--<th>Status</th>-->
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
					<td> <?php echo ucwords($row->task); ?> </td>	
					
					
					<td class="numeric">
					<p>
						<?php echo ((isset($adminRights->eCommerce) && $adminRights->eCommerce->update==1) || checkSuperAdmin())? anchor('EmailTemplate/editEmailTemplate/'.$row->email_template_id.'/'.$redirect_page.'/'.$option.'/'.$keyword.'/'.$limit.'/'.$offset,'<i class="fa fa-edit m-icon-white"></i>','class="btn blue icn-only mini"   title="Edit EmailTemplate"'):'';
						if((isset($adminRights->eCommerce) && $adminRights->eCommerce->delete==1) || checkSuperAdmin()){	 ?>
					
					
					
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
					<div class="row" style="margin-top: 15px;">
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
		
		<?php 
	
	     $msg = $this->session->flashdata("msg");
		if($msg!=''){
			
	      if($msg == "insert"){ $error = ADD_NEW_RECORD;}
            if($msg == "update"){ $error = UPDATE_RECORD;}
            if($msg == "delete"){ $error = DELETE_RECORD;}
			if($msg == "active") {  $error = ACTIVE_RECORD;}
			if($msg == "inactive"){ $error = INACTIVE_RECORD;}
			if($msg == "rights"){ $error = ASSIGN_RIGHTS;}
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
