<div class="wrapper row2">
	<div class="mainpage-container">
		<div class="page-container inner-pagecontainer">
  			<div class="container">
			 	<!-- <div class="page-title margin-bottom-25">
				 	<h2> My Profile </h2>
				 </div> --> 
				 
				 <div class="page-controler clearfix">
				 		<div class="pull-left"> 
							<a onclick="goBack();" class="btn blue btn-sm"> <i class="stripicon backicon"> </i> Back </a>  
						</div>
						<div class="pull-right"> 
							<div class="btn-group btn-control-action">
								
								<select name="s_type" id="s_type" onchange="filtertasks(this.value)" class="btn blue btn-sm" tabindex="1">
									<option class="blue" value="task_due_date">Due Date</option>
									<option class="blue" value="task_priority">Priority</option>
									<option class="blue" value="task_status_id">Status</option>
								</select>
								</div>
							 <a href="<?php echo base_url('task/add_ind_task');?>" class="btn blue btn-xm"> <i class="stripicon plusicon"> </i>  </a>  	
						</div>
				 </div>
				
				 <div class="common-table">
				 	<div class="table-responsive">
					  <table class="table table-hover table-striped">
							<thead>
							  <tr>
								<th width="16px;">#</th>
								<th>Task</th>
								<th class="text-left">Status</th>
							  </tr>
							</thead>
							<tbody id="filtertasks"> 
								<?php  $this->load->view($theme.'/mobileview/user/Ajax_today_tasks');?>
								
							</tbody>
						  </table>
					</div>
				 </div>
			 </div> <!-- /container -->
		</div>
	</div>
</div>

<script type="text/javascript">
	
	function changestatus(status,id)
	{
		//var s_type = $('#s_type').val();
		var s_type = $('#s_type :selected').val();
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/completeTask"); ?>',
				//dataType:"json",
				data : {id:id,status:status,s_type:s_type,from:'todaytask'},
				success : function(data){
					//alert(data.value);
					
					//$("#task_id_"+id).hide("slow");
					$('#filtertasks').html(data);					
					$('#dvLoading').fadeOut('slow');
				
				},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});
				
		}else{
			alertify.alert('Please select atleast one criteria..!!');
		}
	}
	
	function filtertasks(option)
	{
		var id = option;
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("user/filtertasks"); ?>',
				data : {id:id},
				success : function(data){
					
					$("#filtertasks").html(data);
					$('#dvLoading').fadeOut('slow');
				},
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
			});
				
		}else{
			alertify.alert('Please select atleast one option..!!');
		}
	}
	
	
	function setupLabel() {
			if ($('.label_check input').length) {
				$('.label_check').each(function(){ 
					$(this).removeClass('c_on');
				});
				$('.label_check input:checked').each(function(){ 
					$(this).parent('label').addClass('c_on');
				});                
			};
			if ($('.label_radio input').length) {
				$('.label_radio').each(function(){ 
					$(this).removeClass('r_on');
				});
				$('.label_radio input:checked').each(function(){ 
					$(this).parent('label').addClass('r_on');
				});
			};
		};
			$(document).ready(function(){
			
				$('.label_check, .label_radio').click(function(){
					setupLabel();
				});
			setupLabel(); 
		 });
	

</script>