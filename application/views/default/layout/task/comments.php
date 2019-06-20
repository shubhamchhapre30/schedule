<script type="text/javascript">
$(function(){
	
 $('#scroll_comments').slimScroll({
 	  
	  color: '#17A3E9',
	   wheelStep: 20,
	   height:260
  });


});

</script> 

<script>
	$(document).ready(function(){
		
		$("#task_comment").blur(function(e){
			$("#cmts_submit").trigger( "click" );
	    });
	    
/*	    $("#task_comment").on("keypress", function(e){
			 if( e.keyCode === 13 ) {
				e.preventDefault();
				$("#cmts_submit").focus();
				//$("#cmts_submit").trigger('click');
			}
		 });*/
		
		//$("#task_comment").limiter(<?php //echo CMT_TEXT_SIZE;?>, //$('#ch_cmt_1'));
		
		$("#frm_add_comment").validate({
			rules : {
				
			},
			submitHandler:function(){
				var cmt = $("#task_comment").val();
				if(cmt == ''){
					$("#alertify").show();
		    		alertify.alert('Please enter comments');
		    		return false;
		    	} else {
					$("#task_comment_loading").show();
		    		$('#dvLoading').fadeIn('slow');
					$.ajax({
			            type: 'post',
			            url : '<?php echo site_url("task/comment"); ?>',
			            data: $('#frm_add_comment').serialize(),
			            success: function(responseData) {
			            	if($("#task_comment_id").val()){
			            		$("#cmt_"+$("#task_comment_id").val()).replaceWith(responseData);
			            	} else {
			            		$("#updated_task_comments").prepend(responseData);
			            	}
			            	
			            	$("#task_comment").val('');
			            	$("#task_comment_id").val('');
			            	$("#task_comment").blur(function(){$("#alertify").hide();$("#alertify-cover").css("position","relative");});
			            	$('#dvLoading').fadeOut('slow');  	
			            	$("#task_comment_loading").hide();
			            },
			            error: function(responseData){
			                console.log('Ajax request not recieved!');
			                $('#dvLoading').fadeOut('slow'); 
			            }
			        });
				}
                                $(".wysihtml5-sandbox").contents().find("body.comment_editor").html('');
                                
			}
		});
	});
	
	function edit_comment(id){
		$("#task_comment_id").val(id);
		var orig_comment = $("#orig_comment_"+id).html();
		$("#task_comment").val(orig_comment);
                $(".wysihtml5-sandbox").contents().find("body.comment_editor").html(orig_comment);
	}
	
	function delete_comment(id){
		var ans = "Are you sure, you want to delete this comment?";
                $('#delete_comment_'+id).confirmation({placement: 'left'});
		$('#delete_comment_'+id).confirmation('show').on('confirmed.bs.confirmation',function(){
			$('#dvLoading').fadeIn('slow');
			
			$.ajax({
	            type: 'post',
	            url : '<?php echo site_url("task/delete_task_comment"); ?>',
	            data: {task_comment_id : id, task_id : $("#comment_task_id").val()},
	            success: function(responseData) {
	            	$("#cmt_"+id).remove();
	            	$('#dvLoading').fadeOut('slow'); 
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	                $('#dvLoading').fadeOut('slow'); 
	            }
	        });
	 });
	}
</script>


<div class="portlet">
								 
	 <div class="portlet-body  form flip-scroll">
		 
		 <div class="customtable horizontal-form">
			
			<!-- ***************** -->
			<div class="popuphight">
			<!-- ***************** -->	
			
			<div class="no_task_msg" style="display: none;">
				<div class='task_not_found_msg'><span>Please save the task before adding this.</span></div>
			</div>

			<div class="normal_div">
				<div class="comment-block">
					<!--
					<div class="comment-title">
											Comments
										</div>-->
					
					<div id="scroll_comments">
						<ul class="list-unstyled" id="updated_task_comments">
							<?php echo $this->load->view($theme."/layout/task/ajax_comments");?>
						</ul>
					</div>
					<form name="frm_add_comment" id="frm_add_comment" action="">
						<div class="addcomment-block">
							<div class="row">
								<div class="col-md-12 ">
									<div class="form-group">
										<label class="control-label" for="firstName"> <strong> Add Comment<span class="required">*</span> </strong></label>
										<div class="controls relative-position">
											<textarea rows="3" name="task_comment" id="task_comment" class="col-md-12 m-wrap comment_editor" ></textarea>
											<span class="input-load desc-load" id="task_comment_loading"></span>
										  </div>
									</div>
									<!--<span class="chr">Char left :- <i id="ch_cmt_1"><?php echo CMT_TEXT_SIZE;?></i></span>-->
									<div class="pull-right" style="margin-top: 5px;">
										<input type="hidden" name="task_id" id="comment_task_id" value="" />
										<input type="hidden" name="task_comment_id" id="task_comment_id" value="" />
										<button type="submit" id="cmts_submit" class="btn btn-common-blue"> Add Comments </button>
									</div>
								</div>
							 </div>
						</div>
					</form>
				
				</div>
			 
		 	</div>
		 </div>
		 <div class="clearfix"></div>
		  
		</div>
		 
		 
	 </div>
 </div>
