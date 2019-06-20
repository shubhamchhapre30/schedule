
<script>
    $(document).ready(function(){
        $("#iphone_app").hide();
    })
</script>
<!-- BEGIN CONTAINER -->

<!-- BEGIN PAGE -->
<div class="page-content">
	
	<div class="container-fluid admin-list">
		<!-- BEGIN PAGE HEADER-->
		<div class="row">
			<div class="col-md-12">
                            <h3 class="page-title"> Company List</h3>
			</div>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN SAMPLE TABLE PORTLET-->
				<div class="portlet box green">
					<div class="portlet-title">
						
					</div>
				
					<div class="portlet-body flip-scroll" >
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="content" style="width:100%">
                                                        <input type="hidden" name="offset" id="offset" value="<?php echo ($offset!='')?$offset:0; ?>" />
                                                        <input type="hidden" name="limit" id="limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
                                                        <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
                                                        <table class="table-change table-striped table-condensed flip-content" style="width:inherit">
                                                            <thead class="flip-content">
                                                                <tr>
                                                                    <th>Company Name</th>
                                                                    <th>App Name</th>
                                                                    <th>Client ID</th>
                                                                    <th>Client Secret</th>
                                                                    <th>Auth Type</th>
                                                                    <th>API Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Schedullo</td>
                                                                    <td><?php echo $iphone_info->app_name; ?></td>
                                                                    <td><p class="auth_link"><?php echo $iphone_info->client_id; ?></p></td>
                                                                    <td><p class="auth_link"><?php echo $iphone_info->client_secret; ?></p></td>
                                                                    <td><?php echo $iphone_info->auth_type; ?></td>
                                                                    <td>
                                                                        <div class="onoffswitch">
                                                                            <input type="checkbox" name="onoffswitch"  id="iphone_app" class="onoffswitch-checkbox" value=""  checked disabled="disabled" >
                                                                            <label class="onoffswitch-label" for="iphone_app">
                                                                                <span class="onoffswitch-inner"></span>
                                                                                <span class="onoffswitch-switch"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                    if($result!=''){
                                                                    foreach($result as $row) { ?>
                                                                            <script type="text/javascript" language="javascript">
                                                                                $(document).ready(function(){
                                                                                    $("#api_status_<?php echo $row->company_id; ?>").hide();
                                                                                    $("#api_status_<?php echo $row->company_id; ?>").on("change",function(){
                                                                                        var status = '';
                                                                                        if($(this).is(':checked')){
                                                                                            status = 'Active';
                                                                                        }else{
                                                                                            status = 'Inactive';
                                                                                        }
                                                                                        $.ajax({
                                                                                            url:"<?php echo site_url('api/change_api_status') ?>",
                                                                                            type:"post",
                                                                                            data:{
                                                                                                status:status,
                                                                                                company_id:"<?php echo $row->company_id; ?>",
                                                                                                client_id:"<?php echo $row->client_id; ?>"
                                                                                            },
                                                                                            success:function(data){
                                                                                            }
                                                                                        });
                                                                                    });
                                                                                });
                                                                            </script>
                                                                        <tr>
                                                                            
                                                                            <td><?php echo $row->company_name; ?>(<?php echo $row->company_id; ?>)</td>
                                                                            <td><?php echo $row->app_name; ?></td>
                                                                            <td><p class="auth_link"><?php echo $row->client_id; ?></p></td>
                                                                            <td><p class="auth_link"><?php echo $row->client_secret; ?></p></td>
                                                                            <td><?php echo $row->auth_type; ?></td>
                                                                            
                                                                            <td><div class="onoffswitch">
                                                                                    <input type="checkbox" name="onoffswitch"  class="onoffswitch-checkbox" value=""  id="api_status_<?php echo $row->company_id;?>" <?php if($row->api_access_status == 'Active'){ echo "checked"; }?> >
                                                                                    <label class="onoffswitch-label" for="api_status_<?php echo $row->company_id;?>">
                                                                                        <span class="onoffswitch-inner"></span>
                                                                                        <span class="onoffswitch-switch"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                            
                                                                        </tr>
                                                                    <?php	} }else{?>
                                                                        <tr> 
                                                                            <td colspan="7"><h3>No Record Found.</h3></td>
                                                                        </tr>	
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>

                                                            <div class="row" style="margin-top: 15px;">
                                                                    <?php echo $page_link; ?>
                                                            </div>
                                                    </div>
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
	


	<!-- END PAGE -->
</div>
<!-- END CONTAINER -->
