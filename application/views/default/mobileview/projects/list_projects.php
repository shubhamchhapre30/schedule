<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			 	<!-- <div class="page-title margin-bottom-25">
				 	<h2> My Profile </h2>
				 </div> --> 
				 
				 <div class="page-controler clearfix">
				 		<div class="pull-left"> 
							<a href="<?php echo site_url('home/main');?>" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div>
						
						<div class="pull-right">
							 <a href="<?php echo site_url('project/insertProject');?>" class="btn blue btn-xm"> <i class="stripicon plusicon"> </i>  </a>  	
						</div>
				 </div>
				 <div>
						<?php if($this->session->flashdata('msg')!=''){
								 	 			
											?>
											<script>
												$(document).ready(function() {
													$('#user_msg').slideDown('slow').delay(5000).slideUp('slow');
												});
											</script>
											<div class='alert alert-success' id="user_msg"><a class='closemsg' data-dismiss='alert'></a><span>
												<?php if($this->session->flashdata('msg') == 'update'){ echo "Project updated successfully."; } ?>
												<?php if($this->session->flashdata('msg') == 'insert'){ echo "Project inserted successfully."; } ?>
												
											</span></div>
											<?php 
										}?>
						</div>
				
				 <div class="common-table">
				 	<div class="table-responsive">
					  <table class="table table-hover table-striped">
							<thead>
							  <tr>
								 
								<th>Projects </th>
								<th class="text-left">Status</th>
								<th class="text-left">View Tasks</th>
							  </tr>
							</thead>
							<tbody>
								
								<?php if($listProjects!="0"){
									foreach ($listProjects as $p) {
								?>
								<tr>
									<td>
										<div class="txt-heading1"><a style="white-space: normal !important;" href="<?php echo site_url('project/edit_Project/'.$p->project_id); ?>"><?php echo $p->project_title; ?></a> </div>
									 </td>
									<?php
										if($p->project_status=='Cancelled'){ $tsk_clr = "red";}
										if($p->project_status=='Open'){$tsk_clr = "green";}
										if($p->project_status=='On_hold'){$tsk_clr = "black";}
										if( $p->project_status=='Complete'){$tsk_clr = "parrot";}
									?>
									
									<td class="text-left"> <div class="status-bx <?php echo $tsk_clr;?>"> <?php echo ucwords(str_replace("_"," ", $p->project_status));?> </div>  </td>
									<td><div class="txt-heading1"><a href="<?php echo site_url('project/project_tasks/'.base64_encode($p->project_id));?>">View Tasks</a></div></td>
								
								</tr>
										
								<?php }	}else{?>
									<tr>
										<td colspan="4">No Projects Available</td>
									</tr>
									
									<?php }?>
							  
							</tbody>
						  </table>
					</div>
				 </div>
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>