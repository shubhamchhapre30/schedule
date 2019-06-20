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
							<div class="btn-group btn-control-action">
								  <select name="s_type" id="s_type" onchange="filterKanban(this.value)" class="btn blue btn-sm" tabindex="1">
								<?php if($task_status!='0'){
									foreach ($task_status as $t){
								?>
								<option value="<?php echo $t->task_status_id;?>" <?php if($t->task_status_id == $task_status_id){ echo 'selected="selected"'; } ?> > <?php echo $t->task_status_name;?> </option>
										
									<?php } } ?>
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
								<th class="text-left"></th>
							  </tr>
							</thead>
							<tbody id="kanbanview">
							<?php include('Ajax_kanban.php'); ?>
							
							</tbody>
						  </table>
					</div>
				 </div>
				 
				  
			 </div> <!-- /container -->
		</div>
	</div>
</div>
<input type="hidden" id="id" name="id" value="" />
<script type="text/javascript">
	
	function changestatus(status,id)
	{
		//var s_type = $('#s_type').val();
		var s_type = $('#s_type :selected').val();
		if(id!=''){
		 	$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("task/completeTask"); ?>',
				//dataType:"json",
				data : {id:id,status:status,s_type:s_type},
				success : function(data){
					$("#kanbanview").html(data);
					scrollbind();				
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
	
	function filterKanban(option)
	{
		var id = option;
		$('#id').val(id);
		
		if(id!=''){
			$('#dvLoading').fadeIn('slow');
        	$.ajax({
				type : 'post',
				url : '<?php echo site_url("task/filterKanban"); ?>',
				data : {id:id},
				success : function(data){
					
					$("#kanbanview").html(data);
					scrollbind();
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
				
				$('#id').val('<?php echo $task_status_id;?>');
			
				$('.label_check, .label_radio').click(function(){
					setupLabel();
				});
			setupLabel(); 
			
	 var loading = false;
    
    $(window).scroll(function(){
    	
        
       // if($(window).scrollTop() + $(window).height() >= $('#kanbanview').height() + $('#kanbanview').offset().top){
        if ($(window).height() + $(window).scrollTop() == $(document).height()) {
        	if(loading == false){
                loading = true;
               
                var offset  = $('.event_block').length;
                var id = $('#id').val();
                
                $('#dvLoading').fadeIn('slow');
                $.ajax({
                       type    : 'POST',
                       url     : "<?php echo site_url('task/AjaxKanban'); ?>",
                       data    : {offset:offset,id:id},
                       success : function(data){
                           $("#kanbanview").append(data);
                           $('#dvLoading').fadeOut('slow');
                           loading = false;
                           if(data == '<tr class="event_block"> <td colspan="3">No more tasks available.</td> </tr>'){
                               loading = true;
                               return false;
                           }
                       }
                   });
	            }
	        }
	    });
	});

function scrollbind(){
	var loading = false;
    
    $(window).scroll(function(){
        if ($(window).height() + $(window).scrollTop() == $(document).height()) {
        	if(loading == false){
                loading = true;
               
                var offset  = $('.event_block').length;
                var id = $('#id').val();
                
                $('#dvLoading').fadeIn('slow');
                $.ajax({
                       type    : 'POST',
                       url     : "<?php echo site_url('task/AjaxKanban'); ?>",
                       data    : {offset:offset,id:id},
                       success : function(data){
                           $("#kanbanview").append(data);
                            $('#dvLoading').fadeOut('slow');
                           loading = false;
                           if(data == '<tr class="event_block"> <td colspan="3">No more tasks available.</td> </tr>'){
                               loading = true;
                               return false;
                           }
                       }
                   });
	            }
	        }
	    });
}
</script>