<?php 
$rand=rand(0,100000);
define('S3_BUCKET', $this->config->item('bucket_name'));
define('S3_KEY', $this->config->item('accessKey'));
define('S3_SECRET',$this->config->item('secretKey'));
define('S3_ACL','public-read');
$policy = json_encode(array(
    'expiration' => gmdate('Y-m-d\TG:i:s\Z', strtotime('+6 hours')),
    'conditions' => array(
        array(
            'bucket' => S3_BUCKET
        ),
        array(
            'acl' => S3_ACL
        ),
        array(
            'starts-with',
            '$key',
            'upload/task_project_files/'
        ),
        array(
            'success_action_status' => '201'
        ),
        array(
            "content-length-range", 0, 4194304
        )
        
    )
));
$base64Policy = base64_encode($policy);
$signature = base64_encode(hash_hmac("sha1", $base64Policy, S3_SECRET, $raw_output = true));
?>
<div class="portlet">
	 <div class="portlet-body  form flip-scroll">
	 	
		 
		 <div class="form-horizontal">
		 	<!-- ***************** -->
			<div class="popuphight">
			<!-- ***************** -->	
			<div class="no_task_msg" style="display: none;">
				<div class='task_not_found_msg'><span>Please save the task before adding this.</span></div>
			</div>
			<div class="normal_div">
			<script type="text/javascript">
$(function(){
	
 $('#scroll_file').slimScroll({
 	   color: '#17A3E9',
	   wheelStep: 20,
	   height:380
  });


});

</script> 

<script type="text/javascript">
	$(document).ready(function(){
		
		browseClicked();
                var cur_time = 0;
                $("#scroll_file").dropzone({
                    
                    
                    url : 'https://s3-ap-southeast-2.amazonaws.com/static.schedullo.com/',
                    dragover : function(a){
                        cur_time = Math.round(new Date().getTime());
                        $('#drag_message').show();
                    },
                    dragleave : function(a){
                        if((new Date().getTime() - cur_time) >= 4){
                        $('#drag_message').hide();
                        }
                        
                        
                    },
                    drop : function(a){
                        $('#drag_message').hide();
                    },
                    params : {
                        key : $("input[name='key']").val(),
                        AWSAccessKeyId : $("input[name='AWSAccessKeyId']").val(),
                        acl : $("input[name='acl']").val(),
                        success_action_status : $("input[name='success_action_status']").val(),
                        policy : $("input[name='policy']").val(),
                        signature : $("input[name='signature']").val()
                    },
  addedfile:function(b){ 
        var file_name = b.name;
        var fileExtension = file_name.substring(file_name.lastIndexOf('.') + 1); 
        var html = '';
            html += '<tr id="demo_data"><td width="8%" class="text-center">';
            if(fileExtension == 'csv'){
                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/csv.png" />';
            } else if(fileExtension == 'pdf'){ 
                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/pdf.png" />';
            } else if(fileExtension == 'xls' || fileExtension == 'xlsx' || fileExtension == 'xl'){ 
                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/excel.png" />';
            } else if(fileExtension == 'doc' || fileExtension == 'docx' || fileExtension == 'word'){ 
                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/icon2.png" />';
            } else if(fileExtension == 'png' || fileExtension == 'jpe' || fileExtension == 'jpg' || fileExtension == 'jpeg' || fileExtension == 'gif' || fileExtension == 'bmp' || fileExtension == 'jpeg'){ 
                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/images.jpg" />';
            } else { 
                html +='<img src="<?php echo base_url().getThemeName();?>/assets/img/document_icon.png" />';
            } 
            html +='</td>',
            html +='<td>',
            html +=file_name,
            html +='</td>',
            html +='<td width="15%">',
            html +='<div class="progress" style="margin-bottom:0px !important;">',
            html +='<div class="progress-bar progress-bar-success progress-bar bg-success progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60px;">',
            html +='</div></div>';
            html +='</td></tr>';
            $("#no_file").remove();
            $('#updated_files').prepend(html);
    
      
    },
  sending:function(a,b,c){ 
  },
  success: function(b,responseData) {
      var frequency_option = $("a input[name=edit_task_re]:checked").val();
                var id = $("#files_task_id").val();
                
                            var name = $(responseData).find('Key').text();
                            var file_name = name.substring(name.lastIndexOf("/") + 1);
                            var file_title = file_name.substring(file_name.indexOf("_","5") + 1);
                            //$('#dvLoading').fadeIn('slow');
                            $.ajax({
                                        type: 'post',
                                        data : {task_id :$("#files_task_id").val() , uploaded_file_name : file_name , msg : 'success' , upload_file_title : file_title,task_data:$("#file_task_data").val() } ,
                                        url : '<?php echo site_url("task/files"); ?>',
                                        success: function(responseData) { 
                                            responseData = jQuery.parseJSON(responseData);
                                            if(frequency_option == 'occurrence' && id.search("child")== '0'){
                                                $("#demo_data").remove();
                                                $('#updated_files').html(responseData.view);
                                                $("#browse").css('display','block');
                                                $("#change").css('display','none');
                                                $("#icon").css('display','none');
                                                $(".fileupload-preview").html('');
                                                $("#no_file").hide();
                                                $("#task_id").val(responseData['task_id']), $("#allocation_task_id").val(responseData['task_id']), $("#pre_task_id").val(responseData['task_id']), $("#step_task_id").val(responseData['task_id']), $("#files_task_id").val(responseData['task_id']), $("#link_files_task_id").val(responseData['task_id']), $("#comment_task_id").val(responseData['task_id']), $("#freq_task_id").val(responseData['task_id']), $("#search_task_id").val(responseData['task_id']),
                                                
                                                $.ajax({
                                                        type: "post",
                                                        url: SIDEURL + "calendar/set_weekly_update_task",
                                                        data: {
                                                            task_id: responseData['task_id'],
                                                            start_date: $("#week_start_date").val(),
                                                            end_date: $("#week_end_date").val(),
                                                            action: $("#week_action").val(),
                                                            active_menu: ACTIVE_MENU,
                                                            color_menu: $("#task_color_menu").val()
                                                        },
                                                        success: function(b) { 
                                                            $( "#main_"+id ).replaceWith(b);
                                                           // $('#dvLoading').fadeOut('slow');
                                                        }
                                                    });
                                            }else{
                                                $("#demo_data").remove();
                                                $('#updated_files').prepend(responseData.view);
                                                $("#browse").css('display','block');
                                                $("#change").css('display','none');
                                                $("#icon").css('display','none');
                                                $(".fileupload-preview").html('');
                                                $("#no_file").hide();
                                               // $('#dvLoading').fadeOut('slow');
                                            }
                                            
                                        },
                                        error: function(responseData){
                                            console.log('Ajax request not recieved!');
                                            $('#dvLoading').fadeOut('slow');  	
                                        }
                                    });
                           },
                        error: function(responseData){ 
                            console.log('Ajax request not recieved!');
                            $('#dvLoading').fadeOut('slow');

                        }
})
		
    	
    	$("#upload-link-btn").on("click",function(){
    		var name = $("#file_name").val();
    		var link = $("#file_link").val();
    		var id = $("#link_files_task_id").val();
                var frequency_option = $("a input[name=edit_task_re]:checked").val();
    		if(link == ''){
    			alertify.alert("Please enter file link.");
    			return false;
    		}
    		
    		if(name == ''){
    			alertify.alert("Please enter file name.");
    			return false;
    		}
    		
    		if(link!='' && name!=''){
                    if(frequency_option == 'occurrence' && id.search("child")== '0'){
    			$('#dvLoading').fadeIn('slow');
                        $.ajax({
                            type: 'post',
                            url : '<?php echo site_url("task/uplaodLinkFiles"); ?>',
                            data: $("#frm_upload_link").serialize(),
                            async : false,
                            success: function(responseData) { 
                                    responseData = jQuery.parseJSON(responseData);
                                   // console.log(responseData.view);
                                    $('#updated_files').html(responseData.view);
                                    $('#dvLoading').fadeOut('slow');
                                    $("#file_name").val('');
                                    $("#file_link").val('');
                                    $("#no_file").hide();
                                    $("#task_id").val(responseData['task_id']), $("#allocation_task_id").val(responseData['task_id']), $("#pre_task_id").val(responseData['task_id']), $("#step_task_id").val(responseData['task_id']), $("#files_task_id").val(responseData['task_id']), $("#link_files_task_id").val(responseData['task_id']), $("#comment_task_id").val(responseData['task_id']), $("#freq_task_id").val(responseData['task_id']), $("#search_task_id").val(responseData['task_id']),
                                    
                                    $.ajax({
                                    type: "post",
                                    url: SIDEURL + "calendar/set_weekly_update_task",
                                    data: {
                                        task_id: responseData['task_id'],
                                        start_date: $("#week_start_date").val(),
                                        end_date: $("#week_end_date").val(),
                                        action: $("#week_action").val(),
                                        active_menu: ACTIVE_MENU,
                                        color_menu: $("#task_color_menu").val()
                                    },
                                    success: function(b) { 
                                        $( "#main_"+id ).replaceWith(b);
                                        $('#dvLoading').fadeOut('slow');
                                    }
                                });
                            },
                            error: function(responseData){
                                console.log('Ajax request not recieved!');
                                $('#dvLoading').fadeOut('slow');  	
                            }
                        });
                    }
                    else{
                        $('#dvLoading').fadeIn('slow');
                        $.ajax({
                            type: 'post',
                            url : '<?php echo site_url("task/uplaodLinkFiles"); ?>',
                            data: $("#frm_upload_link").serialize(),
                            async : false,
                            success: function(responseData) { 
                                    responseData = jQuery.parseJSON(responseData);
                                  //  console.log(responseData.view);
                                    $('#updated_files').prepend(responseData.view);
                                    $("#file_name").val('');
                                    $("#file_link").val('');
                                    $("#no_file").hide();
                                    $('#dvLoading').fadeOut('slow');  

                            },
                            error: function(responseData){
                                console.log('Ajax request not recieved!');
                                $('#dvLoading').fadeOut('slow');  	
                            }
                        });
                    }
                }
    		
    	});
	       
	});
	
	function delete_task_file(id){
		var ans = "Are you sure, you want to delete this file?";
		$('#delete_task_file_'+id).confirmation('show').on('confirmed.bs.confirmation',function(){
			$('#dvLoading').fadeIn('slow');
			$.ajax({
				type : 'post',
				url : '<?php echo site_url("task/delete_task_file"); ?>',
				data : {task_file_id : id, task_id : $("#files_task_id").val()},
				success : function(data){
					$('#file_tr_'+id).remove();
					if($("#updated_files .hasfiles").length == 0){
						$("#no_file").show();
					}
					$("#browse").css('display','block');
					$("#change").css('display','none');
					$("#icon").css('display','none');
					$('#dvLoading').fadeOut('slow');  	
				},
	            error: function(data){
	                console.log('Ajax request not recieved!');
	                $('#dvLoading').fadeOut('slow');  	
	            }
			});
		});
	}
	function linkClicked(){
		$("#file_name").val('');
		$("#file_link").val('');
		$("#file_name").show();
		$("#file_link").show();
		$("#upload-link-btn").show();
	}
	
	function browseClicked(){
		$("#file_name").hide();
		$("#file_link").hide();
		$("#upload-link-btn").hide();
	}
        

</script>
		 	
			  <div class="form-group">
                              <label class="control-label width75" style="float: left;" >Add File<span class="required">*</span></label>
					<div class="controls margin-left-10">
						<div class="fileupload fileupload-new task-file-dv" data-provides="fileupload">
							<form action="" method="POST" enctype="multipart/form-data" class="direct-upload">
                                                            <input type="hidden" name="key" value="upload/task_project_files/task_<?php echo $rand;?>_${filename}">
                                                            <input type="hidden" name="AWSAccessKeyId" value="<?php echo S3_KEY; ?>">
                                                            <input type="hidden" name="acl" value="<?php echo S3_ACL; ?>">
                                                            <input type="hidden" name="success_action_status" value="201">
                                                            <input type="hidden" name="policy" value="<?php echo $base64Policy; ?>">
                                                            <input type="hidden" name="signature" value="<?php echo $signature; ?>">
                                                            <div class="input-append">
									<div class="uneditable-input" style="display: none;">
										<i id="icon" class="icon-file fileupload-exists"></i> 
										<span class="fileupload-preview"></span>
									</div>
									<span class="btn btn-common-blue btn-file browse-btn" onclick="browseClicked();">
										<span class="fileupload-new" id="browse" >Browse</span>
										<span class="fileupload-exists" id="change">Change</span>
<!--                                                                                //<input type="file" name="file" id="file" />-->
										<input type="file" name="file" id="file" class="default"  />
									</span>
									<!--<a href="#" class="btn blue fileupload-exists" data-dismiss="fileupload">Remove</a>-->
								</div>
								<input type="hidden" name="task_id" id="files_task_id" value="" />
                                                                
                                                                
		 					</form>
                                                    <input type="hidden" name='file_task_data' id='file_task_data' value="" />
						</div>
						OR
						<div class="btn btn-common-blue link-btn" onclick="linkClicked();">Link</div>
                                                <form class="frm_upload_link" name="frm_upload_link" id="frm_upload_link" enctype="multipart/form-data" style="margin-right: 50px !important;">
							<input type="text"  name="file_name" id="file_name" value="" class="m-wrap" placeholder="File Name" tabindex="1">
							<input type="text"  name="file_link" id="file_link" value="" class="m-wrap" placeholder="File Link" tabindex="1">
							<input type="hidden" name="task_id" id="link_files_task_id" value="" />
                                                        <input type="hidden" name='task_data' id='link_file_task_data' value="" />
                                                        <button type="button" class="btn btn-common-blue" id="upload-link-btn" >Add Link</button>
						</form>
					</div>
				</div>
					   	  
			 
			  
		 <div id="scroll_file" class="customtable table-scrollable horizontal-form">
                     <div id="drag_message" style="display: none;padding-top: 20% !important;border: 2px dashed #b7afaf;padding: 10%;font-size: 40px;color: #b5abab;position: absolute;width: 100%;height: 100%;background: #ecebeb;">Drop Files here to upload</div>
			<table class="table table-striped table-hover table-condensed flip-content" >
			<thead class="flip-content">
			  <tr>
				<th>Doc Type</th>
				<th>File Name</th>
				<th>Action</th>
			  </tr>
			</thead>
			<tbody id="updated_files">
				<?php $this->load->view($theme."/layout/task/ajax_files") ?>
			</tbody>
		  </table>
		 </div>
		 	
		 
		  </div>
		  
		</div>
		
		<div class="clearfix"></div>
		
	  </div>
	 </div>
</div>
<!--This script code for direct upload to s3 server.-->
<script>
$(document).ready( function() {
            $("#file").change(function(){
                var file = this.files[0],
                fileName = file.name,
                fileSize = file.size;
                var frequency_option = $("a input[name=edit_task_re]:checked").val();
                var id = $("#files_task_id").val();
                    if(fileSize > 4194304){
                                $("#alertify").show();
                                alertify.alert("Please upload file size less than 4MB.");
                                $("#change").css('display','block');
                                return false;
                        }
                     var fd = new FormData($(".direct-upload")[0]);
                     $('#dvLoading').fadeIn('slow');
                     $.ajax({
                        type: 'post',
                        url : 'https://s3-ap-southeast-2.amazonaws.com/static.schedullo.com/',
                        data: fd,
                        datatype: 'xml',
                        processData: false,
                        contentType: false,

                        success: function(responseData) {
                            var name = $(responseData).find('Key').text();
                            var file_name = name.substring(name.lastIndexOf("/") + 1);
                            var file_title = file_name.substring(file_name.indexOf("_","5") + 1);
                            $('#dvLoading').fadeIn('slow');
                            $.ajax({
                                        type: 'post',
                                        data : {task_id :$("#files_task_id").val() , uploaded_file_name : file_name , msg : 'success' , upload_file_title : file_title,task_data:$("#file_task_data").val() } ,
                                        url : '<?php echo site_url("task/files"); ?>',
                                        success: function(responseData) { 
                                            responseData = jQuery.parseJSON(responseData);
                                            if(frequency_option == 'occurrence' && id.search("child")== '0'){
                                                $('#updated_files').html(responseData.view);
                                                $("#browse").css('display','block');
                                                $("#change").css('display','none');
                                                $("#icon").css('display','none');
                                                $(".fileupload-preview").html('');
                                                $("#no_file").hide();
                                                $("#task_id").val(responseData['task_id']), $("#allocation_task_id").val(responseData['task_id']), $("#pre_task_id").val(responseData['task_id']), $("#step_task_id").val(responseData['task_id']), $("#files_task_id").val(responseData['task_id']), $("#link_files_task_id").val(responseData['task_id']), $("#comment_task_id").val(responseData['task_id']), $("#freq_task_id").val(responseData['task_id']), $("#search_task_id").val(responseData['task_id']),
                                                
                                                $.ajax({
                                                        type: "post",
                                                        url: SIDEURL + "calendar/set_weekly_update_task",
                                                        data: {
                                                            task_id: responseData['task_id'],
                                                            start_date: $("#week_start_date").val(),
                                                            end_date: $("#week_end_date").val(),
                                                            action: $("#week_action").val(),
                                                            active_menu: ACTIVE_MENU,
                                                            color_menu: $("#task_color_menu").val()
                                                        },
                                                        success: function(b) { 
                                                            $( "#main_"+id ).replaceWith(b);
                                                            $('#dvLoading').fadeOut('slow');
                                                        }
                                                    });
                                            }else{
                                                
                                                $('#updated_files').prepend(responseData.view);
                                                $("#browse").css('display','block');
                                                $("#change").css('display','none');
                                                $("#icon").css('display','none');
                                                $(".fileupload-preview").html('');
                                                $("#no_file").hide();
                                                $('#dvLoading').fadeOut('slow');
                                            }
                                            
                                        },
                                        error: function(responseData){
                                            console.log('Ajax request not recieved!');
                                            $('#dvLoading').fadeOut('slow');  	
                                        }
                                    });
                           },
                        error: function(responseData){ 
                            console.log('Ajax request not recieved!');
                            $('#dvLoading').fadeOut('slow');

                        }
                    });
               // }
            });
               
         });
        </script>