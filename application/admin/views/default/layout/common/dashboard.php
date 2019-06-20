<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div id="portlet-config" class="modal hide">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button"></button>
					<h3>Widget Settings</h3>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
			</div>
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid admin-list">
				
				
				<!-- BEGIN PAGE HEADER-->
				<div class="row">
					
					<div class="col-md-12">
						
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">Dashboard </h3>
						
						<?php if($msg!='' && $msg=='noRights'){?>
				<div class="alert alert-danger">
									<button data-dismiss="alert" class="close"></button>
									<strong>Error!  </strong> <?php echo NO_RIGHTS_DASH; ?>
								</div>
				<?php } ?>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
					<div class="row">
						<!-- <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-comments"></i>
								</div>
								<div class="details">
									<div class="number">
										1349
									<?php 
										//echo $totalsp=getAllServiceProvider_count();
									?>	
									</div>
									<div class="desc">                           
										Service Provider
										<!--New Feedbacks-->
									<!--</div>
								</div>
								<a class="more" href="javascript:void(0);">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>               
							</div>
						</div> -->
						<div class="col-md-3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="fa fa-globe"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo $totalcust=getAllCustomer_count() ?></div>
									<div class="desc">User </div>
								</div>
								<a class="more" href="<?php echo site_url('user') ?>">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						
						
						 <div class="col-md-3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="fa fa-shopping-cart"></i>
								</div>
								 <div class="details">
									<div class="number"><?php echo $totalgc=getAllCompany_count(); ?></div>
									<div class="desc">Company</div>
								</div> 
								<a class="more" href="<?php echo site_url('Company/list_company') ?>">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div> 
						
						<!-- <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat yellow">
								<div class="visual">
									<i class="icon-bar-chart"></i>
								</div>
								<div class="details">
									<div class="number">12,5M$ <?php //$totalallprofit=getAllProfit_count(); print_r($totalallprofit[0]->amount) ?><?php //echo(site_setting()->site_currency); ?></div>
									<div class="desc">Total Transaction</div>
								</div>
								<a class="more" href="<?php echo site_url('transaction_history/listTransaction_history') ?>">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div> 
					</div>
					
					<!--<div class="row-fluid">
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-comments"></i>
								</div>
								<div class="details">
									<div class="number">
										1349
									</div>
									<div class="desc">                           
										New Feedbacks
									</div>
								</div>
								<a class="more" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="icon-shopping-cart"></i>
								</div>
								<div class="details">
									<div class="number">549</div>
									<div class="desc">New Orders</div>
								</div>
								<a class="more" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="icon-globe"></i>
								</div>
								<div class="details">
									<div class="number">+89%</div>
									<div class="desc">Brand Popularity</div>
								</div>
								<a class="more" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat yellow">
								<div class="visual">
									<i class="icon-bar-chart"></i>
								</div>
								<div class="details">
									<div class="number">12,5M$</div>
									<div class="desc">Total Profit</div>
								</div>
								<a class="more" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
					</div>-->
					<!-- END DASHBOARD STATS -->
					<div class="clearfix"></div>
					
					
					
					
					
				</div>
			</div>
			<!-- END PAGE CONTAINER-->    
		</div>
		<!-- END PAGE -->
<script>

	$(document).ready(function() {
		
		<?php if($msg!=''){
			
	     if($msg == "profileUpdateSuccess"){ $m = PROFILE_UPDATE_SUCC;}
		 if($msg == "passwordUpdateSuccess"){ $m = PASS_UPDATE_SUCCESS;}
           
    ?> 
   
      $.growlUI('<?php echo $m; ?>');
      
/*
      var unique_id=$.gritter.add({
                        title: 'Success',
                        text: '<?php //echo $m; ?>',
                         class_name: 'gritter-light'
                       
                   });*/

                 
     
   <?php } ?>
   
   
    
});
</script>
