



<script type="text/javascript">
$(function(){
 $('#scroll_history').slimScroll({
 	  
	  color: '#17A3E9',
	   wheelStep: 20,
	   height:430
  });
 
 
});


function show_more_link(id){
	$("#show_more_link_"+id).css('display','none');
	$("#show_more_"+id).css('display','block');
}
</script> 


<div class="portlet">
	  <div class="portlet-body form">
		<div class="customtable horizontal-form">
			<!-- ***************** -->
			<div class="popuphight">
			<!-- ***************** -->
			
			<div class="no_task_msg" style="display: none;">
				<div class='task_not_found_msg'><span>Please save the task before adding this.</span></div>
			</div>
			<div class="normal_div">	
			 <div class="comment-block">
			 <div id="scroll_history">
			 	<ul class="list-unstyled" id="updated_history">
			 		<?php echo $this->load->view($theme."/layout/task/ajax_history");?>
				</ul>
			</div>
			
		 </div>
		 </div>
		 </div>
   		
		 
		</div>
		</div>
	   
	</div>
