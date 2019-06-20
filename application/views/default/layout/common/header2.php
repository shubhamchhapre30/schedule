<?php  
        $set_password_user = '';
        if($this->uri->segment(3)){
            $set_password_user = base64_decode($this->uri->segment(3));
        }     
        $completed_id = $this->config->item('completed_id');
	$theme_url = base_url().getThemeName(); 
	$user = get_user_info(get_authenticateUserID());
	$noti = notification();
        $totnoti = countnotification(); 
	
	$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
	$default_format = $this->config->item('company_default_format');
	$company = get_one_company($this->session->userdata('company_id'));
	if($totnoti>0){
	 	$totnum = $totnoti;
	}else{
		$totnum = 0;
	}
        $length = base64_encode($this->session->userdata('user_id'));
        $encoded_user_id = "10".str_pad($length,10,"0",STR_PAD_LEFT);
	$ignore = get_user_setup_steps();
	$admin_setup = get_admin_setup('Admin',$ignore);
	if($admin_setup){ $count_admin = '1';}else{ $count_admin = '0';}
	
	$user_setup = get_admin_setup('User',$ignore);
	if($user_setup){ $count_user = '1';}else{ $count_user = '0';}
	
	$maintenance_setup = get_maintenance_detail();
	if($maintenance_setup){ $count_maintenance = '1';}else{ $count_maintenance = '0';}
	
	$user_info = get_user_info($this->session->userdata('user_id'));
	
	$s3_display_url = $this->config->item('s3_display_url');
	$bucket = $this->config->item('bucket_name');
        
        if($this->session->userdata('first_login') == 0){
            $timezones = get_timezone();
            $countries = get_all_country();
        }
        date_default_timezone_set($this->session->userdata("User_timezone"));
        $start_date = date('Y-m-d',strtotime($user_info->signup_date));
        $today = date('Y-m-d');
        $date1=date_create($today);
        $date2=date_create(date('Y-m-d',strtotime("+30 day ", strtotime(str_replace(array("/"," ",","), "-", $start_date)))));
        $diff=date_diff($date1,$date2);
        $days = $diff->days;
        
        $userCompanyList = get_company_list();
        
        $background_type = $this->session->userdata("user_background_type");
        $background_name = $this->session->userdata("user_background_name");
        $bg_image = 'https://s3-ap-southeast-2.amazonaws.com/static.schedullo.com/upload/background.jpg';
	if($background_type == 'Image')
            $bg_image = $s3_display_url.'upload/user/'.$background_name;
        else if($background_type == 'DefaultImage' || $background_type == '' || $background_type != 'Color')
            $bg_image = 'https://s3-ap-southeast-2.amazonaws.com/static.schedullo.com/upload/background.jpg';
        
?>
<script async type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/jquery.slimscroll.js?Ver=<?php echo VERSION;?>"></script> 
<script async type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/bootstrap_notify.js?Ver=<?php echo VERSION;?>"></script>
<link  rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/plugins/toastr/toastr.min.css?Ver=<?php echo VERSION;?>" />
<script type="text/javascript" src="<?php echo $theme_url; ?>/assets/plugins/toastr/toastr.min.js?Ver=<?php echo VERSION;?>"></script> 
<script src="<?php echo $theme_url; ?>/assets/firebasejs/firebase.js"></script>
<script>
  // Initialize Firebase
  $(document).ready(function(){
     
      var plateform = '';
        if($.browser.safari){
    var platform = navigator.platform;
    platform = platform.toLowerCase();
    platform = platform.substr(0, 3);
  }
  var brow_token = '';
  var brower_access = '<?php echo $this->session->userdata('browser_token_generate'); ?>'; 
  if (navigator.userAgent.match(/msie/i) || navigator.userAgent.match(/trident/i) ){
    
}else if($.browser.safari && platform == 'win'){
  }else
{
  var config = {
    apiKey: "AIzaSyC3hFIERXkG-VeJq0JNg5cQE_M2pqVVRaw",
	authDomain: "schedullo-test1.firebaseapp.com",
	databaseUrl: "https://schedullo-test1.firebaseio.com/",
    projectId: "schedullo-test1",
    messagingSenderId: "168322553740"
  };
  
  // Initialize the default app
var defaultApp = firebase.initializeApp(config);

const messaging = firebase.messaging();

messaging.requestPermission()
.then(function() {

})
.catch(function(err) {
  
});
if(brower_access == 1){
messaging.getToken()
  .then(function(currentToken) {
    if (currentToken) {
        brow_token= currentToken;
$.ajax({
      url : "<?php echo site_url('home/setNotificationObject') ?>",
      type : "post",
      data : {
          //object : messaging,
          token : brow_token
      },
      success: function (result) {
       },
       error:function(data){
           //console.log("error"+data);
       }
    });	  
		
                
    } else {
      // Show permission request.
      
      // Show permission UI.
      updateUIForPushPermissionRequired();
      setTokenSentToServer(false);
    }
  })
  .catch(function(err) {
    
    showToken('Error retrieving Instance ID token. ', err);
    setTokenSentToServer(false);
  });
  
  <?php $this->session->set_userdata('browser_token_generate','0'); ?>

  }

  
   messaging.onMessage(function(payload){
       
          var task_data = payload.data;
          
          if(task_data.change_type!='deleted')
          {
              if(task_data.frequency_type != 'recurrence')
              {
          $.ajax({
						type: "post",
						url: SIDE_URL + "calendar/task_update_div",
						data: {
							task_id: task_data.task_id,
							start_date: $("#week_start_date").val(),
							end_date: $("#week_end_date").val(),
							action: $("#week_action").val(),
							active_menu: $('#redirect_page').val(),
							color_menu:$("#task_color_menu").val()
						},
						success: function(b) {
                                                    if($('#redirect_page').val() != 'from_kanban')
                                                    {
                                                        var truef=$("#week_" + task_data.date).find("#task_"+task_data.task_id).length;
                                                    if(truef) 
                                                        $("#task_"+task_data.task_id).replaceWith(b);
                                                    else
                                                        $("#task_"+task_data.task_id).remove(),$("#week_" + task_data.date).find("#add_newTask_"+task_data.date).before(b);
                            }
                            else
                            {
                                $("#task_"+task_data.task_id).length?$("#task_"+task_data.task_id).replaceWith(b):$("#task_status_" + task_data.task_status_id + "_" + task_data.swimlane_id).append(b);
                            }
						}
					})
                                    }
                                    else
                                    {
                                        if($('#redirect_page').val() == 'from_kanban')
                                        {
                                            $.ajax({
                                            type: "post",
                                            url: SIDEURL + "kanban/searchDueTask",
                                            data: $("#last_remember").serialize(),
                                            success: function(a) {
                                                $("#kanban_view").html(a), $(".scroll1").slimScroll({
                                                    color: "#17A3E9",
                                                    height: "160",
                                                    wheelStep: 12,
                                                    showOnHover: !0
                                                })
                                            }
                                        })
                                    }
                                        else 
                                        {
                                            change_view($("#week_start_date").val()+"#"+$("#week_end_date").val()+"#current");
                                        }
                                        
                                    }
                                }
                                    else
                                    {
                                        if(task_data.frequency_type == 'recurrence')
                                        {
                                            
                                            if($('#redirect_page').val() == 'from_kanban')
                                        {
                                            $.ajax({
                                            type: "post",
                                            url: SIDEURL + "kanban/searchDueTask",
                                            data: $("#last_remember").serialize(),
                                            success: function(a) {
                                                $("#kanban_view").html(a)
                                            }
                                        })
                                    }
                                        else 
                                        {
                                            change_view($("#week_start_date").val()+"#"+$("#week_end_date").val()+"#current");
                                        }
                                        }else{
                                        $("#task_"+task_data.task_id).remove();
                                    }
                                    }
      });
  }

    
 });
</script>
<script type="text/javascript">
$(document).ready(function(){
var sIndex = 30, offSet = 30, isPreviousEventComplete = true, isDataAvailable = true;

$('#notyul').scroll(function () {
   if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
  if (isPreviousEventComplete && isDataAvailable) {

    isPreviousEventComplete = false;
    $.ajax({
      url : "<?php echo site_url('home/loadNotification') ?>",
      type : "post",
      cache: false,
      data : {
          start : sIndex,
          limit : offSet
      },
      dataType: "json",
      success: function (result) { 
         var str='';
        $.map(result, function (item) {
                               
                               var s3path = "<?php echo $s3_display_url;?>";
                              
                               	count_new = item.task_notification_id.length;
                                
                                function firstToUpperCase( str ) {
                                    return str.substr(0, 1).toUpperCase() + str.substr(1);
				}
                                var from = "N/A";
                              if(item.first_name){
                                  from = firstToUpperCase(item.first_name+" "+item.last_name);}
                                  var ntext = item.notification_text;
                                  var t = item.date_added;
                                  var word1 = item.first_name.charAt(0).toUpperCase();
                                  var word2 = item.last_name.charAt(0).toUpperCase();                                
								  
                                  str += "<li class='active' id='rnoti_"+item.task_notification_id+"' >";
                                  if(item.task_id != "0"){
                                  	if(item.master_task_id == "0" || item.is_master_deleted == "1"){
                                  		str += '<a href="javascript:void(0)" onclick="edit_task(this,\''+item.task_id+'\',\''+item.is_chk+'\');openNotiTask('+item.task_notification_id+');" class="notifi-txt" >';
	                              		str += '<span class="pull-left" >';
                                                        if(item.profile_image){
                                                          str +='<img alt="" class="capacity_images" src="'+s3path+'"upload/user/"'+item.profile_image+'" class="profile-image" />';
                                                        }else{
                                                          str +='<span data-letters="'+word1+word2+'"></span>';
                                                        }                                                                  
                                                str +=" </span>";
	                                  	str += "<span class='subject'>";
	                                  	str += "<span class='from'>"+from+"</span>";
	                                  	str +=  "<span class='time'>"+item.date_added+"</span></span>";
						str += "<span class='message'>";
						str += ""+ntext+"</span></a>"; 
                                  	} else {
                                  		str += '<a href="javascript:void(0)" onclick="open_seris(this,\''+item.task_id+'\',\''+item.master_task_id+'\',\''+item.is_chk+'\');openNotiTask('+item.task_notification_id+');" class="notifi-txt" >';
	                              		str += '<span class="pull-left" >';
                                                        if(item.profile_image){
                                                          str +='<img alt="" class="capacity_images" src="'+s3path+'"upload/user/"'+item.profile_image+'" class="profile-image" />';
                                                        }else{
                                                          str +='<span data-letters="'+word1+word2+'"></span>';
                                                        }                                                                  
                                                str +=" </span>";
	                                  	str += "<span class='subject'>";
	                                  	str += "<span class='from'>"+from+"</span>";
	                                  	str +=  "<span class='time'>"+item.date_added+"</span></span>";
						str += "<span class='message'>";
						str += ""+ntext+"</span></a>"; 
                                  	}
                                    } else {
					str += '<a href="javascript:void(0)" onclick="openNotiTask('+item.task_notification_id+');" href="javascript://" class="notifi-txt" >';
                              		str += '<span class="pull-left" >';
                                                        if(item.profile_image){
                                                          str +='<img alt="" class="capacity_images" src="'+s3path+'"upload/user/"'+item.profile_image+'" class="profile-image" />';
                                                        }else{
                                                          str +='<span data-letters="'+word1+word2+'"></span>';
                                                        }                                                                  
                                        str +=" </span>";
                                  	str += "<span class='subject'>";
                                  	str += "<span class='from'>"+from+"</span>";
                                  	str +=  "<span class='time'>"+item.date_added+"</span></span>";
					str += "<span class='message'>";
					str += ""+ntext+"</span></a>"; 
                                    }
                                  str += "<span class='notification-removeicon'><a onclick='deleteNoti("+item.task_notification_id+")' href='javascript://'> <i class='icon-remove'></i> </a> </span>";
                                  str += "</li>";
                                 
                          
                           });
                           $('#notyul').append(str);
                         sIndex = sIndex + offSet;
                        isPreviousEventComplete = true;
                        if (result == '' || result == '0') 
                            isDataAvailable = false;

                           /*var old_count = $("#countnotify").html();
                            var now_new_count = parseInt(old_count) + parseInt(tot);
                       		 $("#countnotify").html(now_new_count);*/
                         
		            	
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		               // $('#dvLoading').fadeOut('slow');
		            }
    });

  }
 }
 });
 });
 
 
function show_timesheet(id,notification_id){
   
   $.ajax({
      url : "<?php echo site_url('home/updateTimesheet_status') ?>",
      type : "post",
      data : {
          id:notification_id
      },
      success: function (e) {
          document.getElementById("myForm_"+id).submit();
      }
      });
}

    
 </script>
<script type="text/javascript">
    
$(window).load(function(){
     
                       
	if("NewUser" == '<?php echo $set_password_user;?>' && <?php echo $this->session->userdata('password_window_flag'); ?> == '0'){
            $("#User_password").modal('show');
        }
	if(0 == <?php echo $this->session->userdata('first_login');?>){
            $("#timeZoneModal").modal('show');
        }
	var is_admin_setup = '<?php echo $count_admin;?>';
	var is_user_setup = '<?php echo $count_user;?>';
	var is_maintenance_setup = '<?php echo $count_maintenance;?>';
	var flag = '<?php echo $this->session->userdata('flag');?>';
	var countadmin = <?php echo count($admin_setup);?>;
	var countuser = <?php echo count($user_setup);?>;
	
	<?php if($maintenance_setup){ ?>
		
	var start_date = "<?php echo $maintenance_setup[0]->start_date;?>";
	var end_date = "<?php echo $maintenance_setup[0]->end_date;?>";
	var duration = "<?php echo (($maintenance_setup[0]->duration)*1000);?>";
	
	<?php } ?>
	
	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; 

    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    var today = yyyy+'-'+mm+'-'+dd;
		
			var is_admin = '<?php echo $this->session->userdata('is_administrator');?>';
		
			if(is_admin == 1){
				if(is_maintenance_setup !='0'){
					if(today >= start_date && today <= end_date)
				    {
				    	if(flag != '1'){
			    			$('#maintenanceModal').modal('show');
			    		
				    		var explode = function(){
						 	$('#maintenanceModal').modal('hide');
						  
						  	$.ajax({
							url : "<?php echo site_url('home/set_session') ?>",
							cache: false,
							success: function(responseData) {
									var flag = responseData;
							    },
					            error: function(responseData){
					                console.log('Ajax request not recieved!');
					            }
							});	
							
							if(is_admin_setup !='0'){	
							$('#adminModal').modal('show');
							
							$('#adminModal').modal({
							    backdrop: 'static',
							    keyboard: false
							});
							
							var step_num = $('#a_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
							if(step_num!=undefined){
								 $.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
							
							$("#admin_dtl a").click(function(){
								var elementId = $(this).attr("rel");
								if(elementId == 'nofollow'){
							    	$('#a_next').trigger('click');
							    	$("#admin_dtl a").bind('click');
							    }
							});
						}else{
							if(is_user_setup !='0'){
									
								$('#userModal').modal('show');
								$('#userModal').modal({
								    backdrop: 'static',
								    keyboard: false
								});
								
								var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
								
								if(step_num!=undefined){
									 $.ajax({
										url : "<?php echo site_url('home/update_user_setup'); ?>",
										cache: false,
										type:'post',
										data:{'step_id' :step_num},
										success: function(responseData) {
										},
								        error: function(responseData){
								        	console.log('Ajax request not recieved!');
								        }
									});
								}
								$("#user_dtl a").click(function(){
									var elementId = $(this).attr("rel");
									if(elementId == 'nofollow'){
								    	$('#u_next').trigger('click');
								    	$("#user_dtl a").bind('click');
								    }
								});
							}
						}
					};
					setTimeout(explode, (duration));
			    	}else{
			    		if(is_admin_setup !='0'){	
							$('#adminModal').modal('show');
							
							$('#adminModal').modal({
							    backdrop: 'static',
							    keyboard: false
							});
							
							var step_num = $('#a_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
							if(step_num!=undefined){
								 $.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
							
							
							$("#admin_dtl a").click(function(){
								var elementId = $(this).attr("rel");
								if(elementId == 'nofollow'){
							    	$('#a_next').trigger('click');
							    	$("#admin_dtl a").bind('click');
							    }
							});
						}else{
							if(is_user_setup !='0'){
									
								$('#userModal').modal('show');
								$('#userModal').modal({
								    backdrop: 'static',
								    keyboard: false
								});
								
								var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
								
								if(step_num!=undefined){
									 $.ajax({
										url : "<?php echo site_url('home/update_user_setup'); ?>",
										cache: false,
										type:'post',
										data:{'step_id' :step_num},
										success: function(responseData) {
										},
								        error: function(responseData){
								        	console.log('Ajax request not recieved!');
								        }
									});
								}
								$("#user_dtl a").click(function(){
									var elementId = $(this).attr("rel");
									if(elementId == 'nofollow'){
								    	$('#u_next').trigger('click');
								    	$("#user_dtl a").bind('click');
								    }
								});
							}
						}
			    	}
				    	
				    	var explode = function(){
						  $('#maintenanceModal').modal('hide');
						  
						  $.ajax({
							url : "<?php echo site_url('home/set_session') ?>",
							cache: false,
							success: function(responseData) {
									var flag = responseData;
							    },
					            error: function(responseData){
					                console.log('Ajax request not recieved!');
					            }
							});	
							
							if(is_admin_setup !='0'){	
								$('#adminModal').modal('show');
								
								$('#adminModal').modal({
								    backdrop: 'static',
								    keyboard: false
								});
								
								var step_num = $('#a_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
								if(step_num!=undefined){
									 $.ajax({
										url : "<?php echo site_url('home/update_user_setup'); ?>",
										cache: false,
										type:'post',
										data:{'step_id' :step_num},
										success: function(responseData) {
										},
								        error: function(responseData){
								        	console.log('Ajax request not recieved!');
								        }
									});
								}
								
								
								$("#admin_dtl a").click(function(){
									var elementId = $(this).attr("rel");
									if(elementId == 'nofollow'){
								    	$('#a_next').trigger('click');
								    	$("#admin_dtl a").bind('click');
								    }
								});
							}else{
								if(is_user_setup !='0'){
										
									$('#userModal').modal('show');
									$('#userModal').modal({
									    backdrop: 'static',
									    keyboard: false
									});
									
									var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
									
									if(step_num!=undefined){
										 $.ajax({
											url : "<?php echo site_url('home/update_user_setup'); ?>",
											cache: false,
											type:'post',
											data:{'step_id' :step_num},
											success: function(responseData) {
											},
									        error: function(responseData){
									        	console.log('Ajax request not recieved!');
									        }
										});
									}
									$("#user_dtl a").click(function(){
										var elementId = $(this).attr("rel");
										if(elementId == 'nofollow'){
									    	$('#u_next').trigger('click');
									    	$("#user_dtl a").bind('click');
									    }
									});
								}
							}
						};
						setTimeout(explode, (duration));
					}else{
						if(is_admin_setup !='0'){	
							$('#adminModal').modal('show');
							
							$('#adminModal').modal({
							    backdrop: 'static',
							    keyboard: false
							});
							
							var step_num = $('#a_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
							if(step_num!=undefined){
								 $.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
							
							
							$("#admin_dtl a").click(function(){
								var elementId = $(this).attr("rel");
								if(elementId == 'nofollow'){
							    	$('#a_next').trigger('click');
							    	$("#admin_dtl a").bind('click');
							    }
							});
						}else{
							if(is_user_setup !='0'){
									
								$('#userModal').modal('show');
								$('#userModal').modal({
								    backdrop: 'static',
								    keyboard: false
								});
								
								var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
								
								if(step_num!=undefined){
									 $.ajax({
										url : "<?php echo site_url('home/update_user_setup'); ?>",
										cache: false,
										type:'post',
										data:{'step_id' :step_num},
										success: function(responseData) {
										},
								        error: function(responseData){
								        	console.log('Ajax request not recieved!');
								        }
									});
								}
								$("#user_dtl a").click(function(){
									var elementId = $(this).attr("rel");
									if(elementId == 'nofollow'){
								    	$('#u_next').trigger('click');
								    	$("#user_dtl a").bind('click');
								    }
								});
							}
						}
					}
				}else{
					if(is_admin_setup !='0'){	
						$('#adminModal').modal('show');
						
						$('#adminModal').modal({
						    backdrop: 'static',
						    keyboard: false
						});
						
						var step_num = $('#a_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
						if(step_num!=undefined){
							 $.ajax({
								url : "<?php echo site_url('home/update_user_setup'); ?>",
								cache: false,
								type:'post',
								data:{'step_id' :step_num},
								success: function(responseData) {
								},
						        error: function(responseData){
						        	console.log('Ajax request not recieved!');
						        }
							});
						}
						
						
						$("#admin_dtl a").click(function(){
							var elementId = $(this).attr("rel");
							if(elementId == 'nofollow'){
						    	$('#a_next').trigger('click');
						    	$("#admin_dtl a").bind('click');
						    }
						});
					}else{
						if(is_user_setup !='0'){
								
							$('#userModal').modal('show');
							$('#userModal').modal({
							    backdrop: 'static',
							    keyboard: false
							});
							
							var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
							
							if(step_num!=undefined){
								 $.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
							$("#user_dtl a").click(function(){
								var elementId = $(this).attr("rel");
								if(elementId == 'nofollow'){
							    	$('#u_next').trigger('click');
							    	$("#user_dtl a").bind('click');
							    }
							});
						}
					}
				}
				
				
				$('#a_next').click(function(){
					var step_num = $('#a_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
					
					var test = $('.modal-body').children('div').not('.hide').prev().children('input').attr('value');
										
					var content = $("#admin_dtl_"+test).html();
					var substring = 'rel="nofollow"';
					if(test!= undefined){
						if (content.toLowerCase().indexOf(substring) >= 0){
							var link = $("#admin_dtl_"+test).find('a').attr('href');
							$('#adminModal').modal('hide');
							window.location.href = link;
						}else{
							if(step_num!=undefined){
							 	$.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
						}
					}else{
						var last_step_num = $('#adminModal').find('.modal-body').children('div').children('input').attr('value');
						var content = $("#admin_dtl_"+last_step_num).html();
						
						if (content.toLowerCase().indexOf(substring) >= 0){
							var link = $("#admin_dtl_"+last_step_num).find('a').attr('href');
							$('#adminModal').modal('hide');
							$('#userModal').modal('hide');
							window.location.href = link;
						}else{
							if(step_num!=undefined){
							 	$.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
						}
					}
					$("#admin_dtl a").click(function(){
						var elementId = $(this).attr("rel");
						if(elementId == 'nofollow'){
					    	$('#a_next').trigger('click');
					    	$("#admin_dtl a").bind('click');
					    }
					});
						
					if(countadmin == 1){
						$('#a_cancel').trigger('click');
					}
					if($('#a_next').val() == 'a_complete'){
						
						$('#adminModal').modal('hide');
						
						if(is_user_setup !='0'){
							
							$('#userModal').modal('show');
							$('#userModal').modal({
							    backdrop: 'static',
							    keyboard: false
							});
							
							var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
							if(step_num!=undefined){
								 $.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
							$("#user_dtl a").click(function(){
								var elementId = $(this).attr("rel");
								if(elementId == 'nofollow'){
							    	$('#u_next').trigger('click');
							    	$("#user_dtl a").bind('click');
							    }
							});
						}
					}
					
					if($('#a_next').attr('data-step') == 'complete'){
						$('#a_next').val('a_complete');
					}else{
						$('#a_next').val('');
					}
				});
				
				$('#a_previous').click(function(){
					$('#a_next').val('');
				});
				
				$('#a_cancel').click(function(){
					$('#adminModal').modal('hide');
					
					 var step_num = $('#a_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
					
					var test = $('.modal-body').children('div').not('.hide').prev().children('input').attr('value');
										
					var content = $("#admin_dtl_"+test).html();
					var substring = 'rel="nofollow"';
					if(test!= undefined){
						if (content.toLowerCase().indexOf(substring) >= 0){
							var link = $("#admin_dtl_"+test).find('a').attr('href');
							$('#adminModal').modal('hide');
						}else{
							if(step_num!=undefined){
							 	$.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
						}
					}else{
						var last_step_num = $('#adminModal').find('.modal-body').children('div').children('input').attr('value');
						var content = $("#admin_dtl_"+last_step_num).html();
						
						if (content.toLowerCase().indexOf(substring) >= 0){
							var link = $("#admin_dtl_"+last_step_num).find('a').attr('href');
							$('#adminModal').modal('hide');
							$('#userModal').modal('hide');
						}else{
							if(step_num!=undefined){
							 	$.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
						}
					}
					$("#admin_dtl a").click(function(){
						var elementId = $(this).attr("rel");
						if(elementId == 'nofollow'){
					    	$('#a_next').trigger('click');
					    	$("#admin_dtl a").bind('click');
					    }
					});
				});
			}else{
				
				if(is_maintenance_setup !='0'){
					if(today >= start_date && today <= end_date)
				    {
				    	if(flag != '1'){
				    		$('#maintenanceModal').modal('show');
				    		
					    	  var explode = function(){
							  $('#maintenanceModal').modal('hide');
							  
							  $.ajax({
								url : "<?php echo site_url('home/set_session') ?>",
								cache: false,
								success: function(responseData) {
										var flag = responseData;
								    },
						            error: function(responseData){
						                console.log('Ajax request not recieved!');
						            }
								});	
								
								if(is_user_setup !='0'){
									$('#userModal').modal('show');
									$('#userModal').modal({
									    backdrop: 'static',
									    keyboard: false
									});
									
									var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
									if(step_num!=undefined){
										 $.ajax({
											url : "<?php echo site_url('home/update_user_setup'); ?>",
											cache: false,
											type:'post',
											data:{'step_id' :step_num},
											success: function(responseData) {
											},
									        error: function(responseData){
									        	console.log('Ajax request not recieved!');
									        }
										});
									}
									$("#user_dtl a").click(function(){
										var elementId = $(this).attr("rel");
										if(elementId == 'nofollow'){
									    	$('#u_next').trigger('click');
									    	$("#user_dtl a").bind('click');
									    }
									});
								}
							};
							setTimeout(explode, (duration));
				    	}else{
				    		if(is_user_setup !='0'){
								$('#userModal').modal('show');
								$('#userModal').modal({
								    backdrop: 'static',
								    keyboard: false
								});
								
								var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
								if(step_num!=undefined){
									 $.ajax({
										url : "<?php echo site_url('home/update_user_setup'); ?>",
										cache: false,
										type:'post',
										data:{'step_id' :step_num},
										success: function(responseData) {
										},
								        error: function(responseData){
								        	console.log('Ajax request not recieved!');
								        }
									});
								}
								$("#user_dtl a").click(function(){
									var elementId = $(this).attr("rel");
									if(elementId == 'nofollow'){
								    	$('#u_next').trigger('click');
								    	$("#user_dtl a").bind('click');
								    }
								});
							}
				    	}
				    }else{
				    	if(is_user_setup !='0'){
							$('#userModal').modal('show');
							$('#userModal').modal({
							    backdrop: 'static',
							    keyboard: false
							});
							
							var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
							if(step_num!=undefined){
								 $.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
							$("#user_dtl a").click(function(){
								var elementId = $(this).attr("rel");
								if(elementId == 'nofollow'){
							    	$('#u_next').trigger('click');
							    	$("#user_dtl a").bind('click');
							    }
							});
						}
				    }
					
				}else{
					if(is_user_setup !='0'){
						$('#userModal').modal('show');
							$('#userModal').modal({
							    backdrop: 'static',
							    keyboard: false
							});
							
							var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
							if(step_num!=undefined){
								 $.ajax({
									url : "<?php echo site_url('home/update_user_setup'); ?>",
									cache: false,
									type:'post',
									data:{'step_id' :step_num},
									success: function(responseData) {
									},
							        error: function(responseData){
							        	console.log('Ajax request not recieved!');
							        }
								});
							}
							$("#user_dtl a").click(function(){
								var elementId = $(this).attr("rel");
								if(elementId == 'nofollow'){
							    	$('#u_next').trigger('click');
							    	$("#user_dtl a").bind('click');
							    }
							});
						}
					}
				}
				
		$('#u_next').click(function(){
			
			var step_num = $('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
			
			var user_prev_step = $('.modal-body').children('div').not('.hide').prev().children('input').attr('value');
										
			var user_content = $("#user_dtl_"+user_prev_step).html();
			var substring = 'rel="nofollow"';
				if(user_prev_step!= undefined){
					if (user_content.toLowerCase().indexOf(substring) >= 0){
						var link = $("#user_dtl_"+user_prev_step).find('a').attr('href');
						$('#userModal').modal('hide');
						window.location.href = link;
					}else{
						if(step_num!=undefined){		
							 $.ajax({
								url : "<?php echo site_url('home/update_user_setup'); ?>",
								cache: false,
								type:'post',
								data:{'step_id' :step_num},
								success: function(responseData) {
								},
						        error: function(responseData){
						        	console.log('Ajax request not recieved!');
						        }
							});
						}
					}
				}else{
					var last_step_num = $('#userModal').find('.modal-body').children('div').children('input').attr('value');
					var user_content = $("#user_dtl_"+last_step_num).html();
					
					if (user_content.toLowerCase().indexOf(substring) >= 0){
						var link = $("#user_dtl_"+last_step_num).find('a').attr('href');
						$('#userModal').modal('hide');
						$('#maintenanceModal').modal('hide');
						window.location.href = link;
					}else{
						if(step_num!=undefined){		
							 $.ajax({
								url : "<?php echo site_url('home/update_user_setup'); ?>",
								cache: false,
								type:'post',
								data:{'step_id' :step_num},
								success: function(responseData) {
								},
						        error: function(responseData){
						        	console.log('Ajax request not recieved!');
						        }
							});
						}
					}
				}
			
			if(countuser == 1){
				$('#u_cancel').trigger('click');
			}
						
			if($('#u_next').val() == 'u_complete'){
				
				$('#userModal').modal('hide');
				
			}
				
			if($('#u_next').attr('data-step') == 'complete'){
				$('#u_next').val('u_complete');
			}else{
				$('#u_next').val('');
			}
		});
		
		$('#u_previous').click(function(){
			$('#u_next').val('');
		});
			
		$('#u_cancel').click(function(){
			$('#userModal').modal('hide');
			
			var step_num = 			$('#u_next').parent('div').parent('div').find('.modal-body').children('div').not('.hide').children('input').attr('value');
			
			var user_prev_step = $('.modal-body').children('div').not('.hide').prev().children('input').attr('value');
										
			var user_content = $("#user_dtl_"+user_prev_step).html();
			var substring = 'rel="nofollow"';
				if(user_prev_step!= undefined){
					if (user_content.toLowerCase().indexOf(substring) >= 0){
						var link = $("#user_dtl_"+user_prev_step).find('a').attr('href');
						$('#userModal').modal('hide');
						//window.location.href = link;
					}else{
						if(step_num!=undefined){		
							 $.ajax({
								url : "<?php echo site_url('home/update_user_setup'); ?>",
								cache: false,
								type:'post',
								data:{'step_id' :step_num},
								success: function(responseData) {
								},
						        error: function(responseData){
						        	console.log('Ajax request not recieved!');
						        }
							});
						}
					}
				}else{
					var last_step_num = $('#userModal').find('.modal-body').children('div').children('input').attr('value');
					var user_content = $("#user_dtl_"+last_step_num).html();
					
					if (user_content.toLowerCase().indexOf(substring) >= 0){
						var link = $("#user_dtl_"+last_step_num).find('a').attr('href');
						$('#userModal').modal('hide');
						$('#maintenanceModal').modal('hide');
						//window.location.href = link;
					}else{
						if(step_num!=undefined){		
							 $.ajax({
								url : "<?php echo site_url('home/update_user_setup'); ?>",
								cache: false,
								type:'post',
								data:{'step_id' :step_num},
								success: function(responseData) {
								},
						        error: function(responseData){
						        	console.log('Ajax request not recieved!');
						        }
							});
						}
					}
				}
			
		});
	
	$('#m_cancel').click(function(){
		
		 $.ajax({
			url : "<?php echo site_url('home/set_session') ?>",
			cache: false,
			success: function(responseData) {
					var flag = responseData;
			    },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	            }
			});	
		$('#maintenanceModal').modal('hide');
		
		if(is_admin == 1){
			$('#adminModal').modal('show');
		}else{
			$('#userModal').modal('show');
		}
		
	});
	
});

$(document).ready(function(){
	
        $(".scroller").slimScroll({
            color: "#17A3E9",
            height: "250px",
            wheelStep: 20,
            showOnHover: !0,
        });
       var qty = '<?php echo $this->session->userdata('allocated_quantity');?>';
	var state = '<?php echo $this->session->userdata('chargify_transaction_status');?>';
	var is_owner = '<?php echo $this->session->userdata('is_owner');?>'; 
	var period_ends = '<?php echo date('d/m/Y',strtotime($this->session->userdata('current_period_ends_at')));?>';
	var flag_status = '<?php echo $this->session->userdata('flag_status');?>';
	var is_credit_info = '<?php echo $this->session->userdata('is_credit_info');?>';
        var is_admin = '<?php echo $this->session->userdata('is_administrator'); ?>';
	
	if(is_owner =='0' && state =='trial_ended')
	{
            $('#chargifyNonAdmin').modal();
            $('#chargifyMsg').html('<p class="chargify-padding5" >Your trial has ended and your administrator has not added a valid payment method.</p>')
	}
	
	if((is_owner =='1' || is_admin == '1') && state =='trialing' && qty > 1 && flag_status == '0' && is_credit_info == '0')
	{
            $.notify({
                    title:'<strong><b>Billing notification</b></strong> <br>',
                    message: 'We have detected that your instance of Schedullo has more than 1 active user.<br>Please <a style="cursor:pointer;text-decoration: underline;" href="javascript:void(0);" id = "generateBillingPortal" > Click here</a> to enter your payment details before the '+period_ends+' to continue using Schedullo.'
                },{
                  element: 'body',
                  type: 'info',
                  animate: {
                    enter: 'animated fadeInUp',
                    exit: 'animated fadeOutRight'
                  },
                  placement: {
                    from: "top",
                    align: "right"
                  },
                  delay:0,
                  autoHide:false,
                  newest_on_top: true,
                  allow_dismiss: true,
                  offset: 20,
                  z_index: 100121,
                  template: '<div data-notify="container" class=" alert alert_chargify_porlet" role="alert" style="width:30.33%;text-align:center;padding:5px !important">' +
                                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss" id="chargifyNonAdmin_close"></button>' +
                                    '<span data-notify="icon"></span> ' +
                                    '<span data-notify="title" style="font-size: 15px;">{1}</span> ' +
                                    '<span data-notify="message">{2}</span>' +
                            '</div>' 
            });
        }else if((is_owner =='1' || is_admin == '1') &&  state !='active' && qty > 1 && flag_status == '0' && is_credit_info == '0'){
            $('#chargifyNonAdmin').modal();
            $('#chargifyMsg').html('<p class="chargify-padding5" >Your access to Schedullo is suspended due to the following reason code:  "'+state.replace("_"," ")+'" . Please <a style="cursor:pointer" href"javascri[t:void(0);" id = "generateBillingPortal" > Click here </a> to access the billing portal.  </p>');
	}else{
		
	}
	
	$(document).on('click','#chargifyNonAdmin_close',function(){
		
		if(state !='trialing'){
			$.ajax({
				url : "<?php echo site_url('home/logout') ?>",
				cache: false,
				success: function(responseData) {
					window.location.reload();
			    }
			});	
		}else{
			$.ajax({
				url : "<?php echo site_url('home/set_session_chargify') ?>",
				cache: false,
				success: function(responseData) {
					var flag_status = responseData;
			    },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	            }
			});
		}
	});
	
	$('#generateBillingPortal').click(function(){
		$('#dvLoading').fadeIn('slow');
		$.ajax({
		url : SIDE_URL+"settings/accessportal",
		type:"post",
		cache: false,
		success: function(responseData) {
				responseData = jQuery.parseJSON(responseData);
				if(responseData!=null){
					
					if(responseData.url){
						window.open(responseData.url, '_blank');
					}else{
						date = new Date(responseData.errors.new_link_available_at);
						$date1 = date.getFullYear()+'-' + (date.getMonth()+1) + '-'+date.getDate();
						alertify.alert(responseData.errors.error+", New link will be available after "+$date1);
					}
				}else{
					alertify.alert("Sorry, we couldn't find billing detail for the account");
				}
	        	$('#dvLoading').fadeOut('slow');
	
	        },
	        error: function(responseData){
	            console.log('Ajax request not recieved!');
	            $('#dvLoading').fadeOut('slow');
	        }
		});
	});
	
	var screenheight = $(window).height();
	var a = parseInt(screenheight) - parseInt('124');
	$('.mainpage-container').css('min-height',a);
		
		/*$("#burgericon").click(function(){
			if($( "body" ).hasClass( "page-sidebar-closed" ))
			{
				 $('body').removeClass("page-sidebar-closed");
			}
			else
			{
				$('body').addClass("page-sidebar-closed");    
			}
		  });*/

	$(function(){
		$('.scroll').slimScroll({
		color: '#17A3E9',
 	    wheelStep: 5
	 });
																												
	});
	
	

	/***
         * Global Ajax call which gets excuted before each $.ajax and $.post function
         * at server side chk session is set or destroy 
         ***/
        
        BASEURL = "<?php echo base_url(); ?>"
        $(document).on('ajaxStart', function()
        {
              jQuery.ajax({
			  type: "POST",
			  url: BASEURL+"home/session_check",
			  success: function (data) {
			     if(data == "-1")
			        {
			            /*alert("Your session has been expired!");
			           // $.ajaxQ.abortAll();
			            //location.reload(); // or window.location = "http://www.redirect.com";*/
			        }
			  }, 
			  async: true,
			  global: true, 
			});    
      });
      
$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    var originalSuccess = options.success;

    options.success = function (data) {
    	
    	
        if(data == "-1") {
        	jqXHR.abort();
           location.reload();
        }
        else {
            if (originalSuccess != null) {
                originalSuccess(data);
            }
        }   
    };
});
	
      
        $("#user_info").validate({
                rules: {
                    user_time_zone: {
                        required: true
                    },
                    country_list:{
                        required:true
                    }
                },
                submitHandler: function() { 
                    $.ajax({
                        type: "post",
                        url: SIDE_URL + "settings/setTimeZoneFirst",
                        data: {
                            data:$("#user_info").serialize()
                        },
                        success: function(a) { 
                           $("#timeZoneModal").modal('hide'); 
                        }
                    });
                }
    });
    
    $.validator.addMethod("pass", function(value, element) {
		        return this.optional(element) || (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z/\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/]{8,}$/).test(value);
    }, "minimum length of 8 with at least 1 number, 1 upper & 1 lower letter.");
    
    $("#set_user_password").validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    set_password: {
                        required:true,
                        pass:true,
                        rangelength: [8, 16]
                    },
                    set_confirm_password:{
                        required: true,
                        equalTo:'#set_password' 
                    }
                },
                submitHandler: function() { 
                    
                    $.ajax({
                        type: "post",
                        url: SIDE_URL + "home/change_user_password",
                        data: {
                            data:$("#set_user_password").serialize()
                        },
                        success: function(a) {
                            $("#User_password").modal('hide'); 
                            if(a== 'done'){
                                alertify.success("Your Password has been changed successfully.");
                            }
                        }
                    });
                }
    });
   
	/*****/
	
});	
	function session_checking()
{
    $.post("<?php echo site_url("home/session_check"); ?>", function(data) {
        return data;
    });
}
</script>
<script>
$(document).ready(function(){
    $(function() {
        $("#timeZoneModal").modal({
            backdrop: "static",
            keyboard: !1,
            show: !1
        });
    });
    $(function() {
        $("#User_password").modal({
            backdrop: "static",
            keyboard: !1,
            show: !1
        });
    });
    
    $.getJSON("https://freegeoip.net/json/", function (data) {
    //var country = data.country_name;
    var country_code = data.country_code;
    var time_zone = data.time_zone;
    
    $('#user_time_zone option[value="'+time_zone+'"]').attr('selected', 'selected');
    $('#country_code option[value="'+country_code+'"]').attr('selected', 'selected');
    
    }); 
    var email_verify = '<?php echo $user_info->verify_email;?>';
    var user_id = '<?php echo $this->session->userdata('user_id'); ?>';
   
   $(function(){
       if(email_verify == '0' && <?php echo $days ?> <= 30){
            $.notify({
                title:'<strong>You have not yet validated your email address.</strong> <br>',
                message: 'Please check your email and click on the link.<br>If you have misplaced the email, <a href="javascript:void(0);" onclick="resend_mail('+user_id+');" style="text-decoration: underline;">click here</a> to send a new one.<br>You have <strong>'+<?php echo $days; ?>+' days left</strong> to activate your account before it is de-activated.'
            },{
              element: 'body',
              type: 'info',
              animate: {
                enter: 'animated fadeInUp',
                exit: 'animated fadeOutRight'
              },
              placement: {
                from: "top",
                align: "right"
              },
              delay:0,
              autoHide:false,
              newest_on_top: true,
              allow_dismiss: true,
              offset: 20,
              z_index: 100121,
              template: '<div data-notify="container" class=" alert alert-{0}" role="alert" style="width:30.33%;text-align:center;padding:5px !important">' +
                            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                '<span data-notify="icon"></span> ' +
                                '<span data-notify="title">{1}</span> ' +
                                '<span data-notify="message">{2}</span>' +
                        '</div>' 
            });
    }
});
   
   if(<?php echo $days ?> > 30 && email_verify == '0'){
        $("#deactive_user_account").modal('show');
   }
   
   $(document).on('click','#deactiveaccount_close',function(){
	$.ajax({
            url : "<?php echo site_url('home/logout') ?>",
            cache: false,
            success: function(responseData) {
                window.location.reload();
            }
	});
    });
});

function resend_mail(user_id){
$.ajax({
        type: "post",
        url: SIDEURL + "home/resend_verify_mail",
        data: {
            user_id:user_id
        },
        success: function(a){
         if(a == 'done'){
             alertify.success("Mail has been sent sucessfully.");
         }
        }
    });

}

function change_company(company_id,password){
    $("#dvLoading").fadeIn("slow");
    $.ajax({
        type: "post",
        url: SIDEURL + "home/change_company_login",
        data: {
            company_id:company_id,
            password:password
        },
        success: function(a){
            var data = jQuery.parseJSON(a);
            if(data.status=='1'){ 
                 window.open(SIDEURL+data.redirect,"_self");
            }
            $("#dvLoading").fadeOut("slow");
        }
    });
}
    
</script>
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
    
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
      <div class="container-fluid" style="padding-left:20px;padding-right: 20px;" >
      <!-- BEGIN LOGO -->
      <div class="setburgericon"><div class="hidden-phone" id="burgericon"></div></div>  <!--sidebar-toggler -->
          <?php if($this->session->userdata('is_customer_user') == '1'){
                $home_page_url = 'javascript:void(0);';
            }else{
                $home_page_url = base_url().'user/dashboard'; 
            }
          ?>
          <?php if((isset($company->company_logo) && $company->company_logo!='') && $this->s3->getObjectInfo($bucket,'upload/company/'.$company->company_logo)){
            $comapny_n = 'upload/company/'.$company->company_logo; ?>
            <a class="brand_header" href="<?php echo $home_page_url ?>"> <img class="margin-left-10"  src="<?php echo $s3_display_url; ?>upload/company/<?php echo $company->company_logo;?>" alt="schedullo" /> </a>
        <?php }else{?>
            <a class="brand_header" href="<?php echo $home_page_url; ?>"> <img class="logo-image" src="<?php echo base_url().getThemeName(); ?>/assets/img/logo_new.png" alt="schedullo" /> </a>
      	<?php } ?>
      <!-- END LOGO -->
      <!-- BEGIN RESPONSIVE MENU TOGGLER -->
      <a href="javascript:;" class="nav pull-right custom_toggle collapse" data-toggle="collapse" data-target="#sidebar_custom"> <img src="<?php echo base_url().getThemeName(); ?>/assets/img/menu-toggler.png" alt="" /> </a>
      <!-- END RESPONSIVE MENU TOGGLER -->
      <!-- BEGIN TOP NAVIGATION MENU -->
        <ul class="nav pull-right">
        
           <li class="dropdown topnav-icon"><a href="<?php echo site_url('task/search_task');?>" class="dropdown-toggle" title="Search"> <i class="fa fa-search cstmicn" aria-hidden="true"></i><span class="badge"></span> </a> </li>  
            
        <!-- BEGIN SETTINGS DROPDOWN -->
        <?php if($user->is_administrator == '1'){ 
        	if($this->session->userdata('pricing_module_status')== '1' && $this->session->userdata('customer_module_activation')=='1'){?>
                <li class="dropdown topnav-icon dropdown-user">
                    <a href="#" class="dropdown-toggle " data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="Settings" > <i class="icon-cog cstmicn"></i> <span class="badge"></span> </a>
                    <ul class="dropdown-menu" style="left:auto;right:13px;padding: 5px  !important;margin-top: 1px !important;">
                        <li><?php echo anchor('settings/index','<div><i class="fa fa-cogs" aria-hidden="true"></i> Company Settings</div>');?></li>
                        <li id="access_pricing_module"><?php echo anchor('price/index','<div><i style="padding-right: 2px;" class="glyphicon glyphicon-usd "></i> Price Maintenance</div>'); ?></li>
                    </ul>
                </li>
        <?php }else{?>
            <li class="dropdown topnav-icon" > <a href="<?php echo site_url('settings/index');?>" class="dropdown-toggle " title="Settings"> <i class="icon-cog cstmicn"></i> <span class="badge"></span> </a> </li>
        <?php }} ?>
        <!-- END SETTINGS DROPDOWN --> 
        
        <!-- BEGIN INBOX DROPDOWN -->
        <li class="dropdown topnav-icon" id="header_notification_bar"> <a href="javascript://" class="dropdown-toggle" data-toggle="dropdown" data-close-others="dropdown" data-close-others="true" title="Notifications" > <i class="icon-bell-alt cstmicn"></i> <span id="hidebell" class="badge">
          <div id="countnotify"><?php echo $totnum;?></div>
          </span> </a>
            <ul class="dropdown-menu extended inbox" style="left:auto;right:10px;">
            <li style="padding-right: 7px !important;">
              <ul class="dropdown-menu-list scroller " style="height:250px;" id="notyul">
                <li class="external" <?php if($totnum==0) {?>  style="display:none;"   <?php }?> id="markall_li"> <a href="javascript:void(0);" id="markAll"><i class="icon-ok-sign"></i> Mark all as read </a> </li>
                <?php if($noti){ 
            	  	foreach ($noti as $n) {
                            $word1 = ucfirst(substr($n['first_name'],0,1));
                            $word2 = ucfirst(substr($n['last_name'],0,1));
                            $cls = ($n["is_read"] =='1')?'':'active'; ?>
				<li class="<?php echo $cls;?>" id="rnoti_<?php echo $n["task_notification_id"];?>" >
                                    <?php  if($n["task_id"] != "0"){
                                            if($n["master_task_id"] == "0" || $n["is_master_deleted"] == "1"){ ?>
                                                <a href="javascript:void(0)" onclick="edit_task(this,'<?php echo $n["task_id"];?>','<?php echo $n["is_chk"];?>');openNotiTask('<?php echo $n["task_notification_id"];?>');" class="notifi-txt" >
                                                    <span class="pull-left" >
                                                    <?php   if((isset($n['profile_image']) && $n['profile_image']!='') && $this->s3->getObjectInfo($bucket,'upload/user/'.$n['profile_image'])){ ?>
                                                                <img alt="" class="capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$n['profile_image']; ?>" class="profile-image" />
                                                    <?php } else { ?>
                                                                <span data-letters="<?php echo $word1.$word2; ?>"></span>
                                                    <?php } ?>
                                                    </span>
                                                    <span class="subject">
                                                        <span class="from"><?php echo ($n['notification_from']!='0')?ucwords($n["first_name"]." ".$n['last_name']):'N/A';?></span>
                                                        <span class="time"><?php echo time_ago($n["date_added"]);?></span>
                                                    </span>
                                                    <span class="message">
                                                        <?php echo htmlentities($n["notification_text"]);?>
                                                    </span>  
						</a>
                                            <?php } else { ?>
                                                        <a href="javascript:void(0)" onclick="open_seris(this,'<?php echo $n["task_id"];?>','<?php echo $n["master_task_id"];?>','<?php echo $n["is_chk"];?>');openNotiTask('<?php echo $n["task_notification_id"];?>');" class="notifi-txt" >
							<span class="pull-left">
                                                            <?php   if((isset($n['profile_image']) && $n['profile_image']!='') && $this->s3->getObjectInfo($bucket,'upload/user/'.$n['profile_image'])){ ?>
                                                                    <img alt="" class="capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$n['profile_image']; ?>" class="profile-image" />
                                                            <?php } else { ?>
                                                                    <span data-letters="<?php echo $word1.$word2; ?>"></span>
                                                            <?php } ?>
                                                        </span>
							<span class="subject">
                                                            <span class="from"><?php echo ($n['notification_from']!='0')?ucwords($n["first_name"]." ".$n['last_name']):'N/A';?></span>
                                                            <span class="time"><?php echo time_ago($n["date_added"]);?></span>
							</span>
							<span class="message">
                                                            <?php echo htmlentities($n["notification_text"]);?>
							</span>  
							</a>
                                            <?php } } else { ?>
							<?php if($n['timesheet_notification']=='1'){?>
                                                            <form method="POST" action="<?php echo site_url('timesheet/showtimesheet');?>" name="myForm_<?php echo $n['timesheet_id'];?>" id="myForm_<?php echo $n['timesheet_id']; ?>">
                                                                <input type="hidden" name="timesheet_id" id="timesheet_id" value="<?php echo $n['timesheet_id']; ?>" />
                                                            </form>
                                                            <a  href="javascript:void(0)" onclick="show_timesheet(<?php echo $n['timesheet_id'];?>,<?php echo $n["task_notification_id"];?>);" href="javascript://" class="notifi-txt" >
                                                                <span class="pull-left">
                                                                    <?php   if((isset($n['profile_image']) && $n['profile_image']!='') && $this->s3->getObjectInfo($bucket,'upload/user/'.$n['profile_image'])){ ?>
                                                                                <img alt="" class="capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$n['profile_image']; ?>" class="profile-image" />
                                                                    <?php } else { ?>
                                                                        <span data-letters="<?php echo $word1.$word2; ?>"></span>
                                                                    <?php } ?>
                                                                </span>
                                                                <span class="subject">
                                                                    <span class="from"><?php echo ($n['notification_from']!='0')?ucwords($n["first_name"]." ".$n['last_name']):'N/A';?></span>
                                                                    <span class="time"><?php echo time_ago($n["date_added"]);?></span>
                                                                </span>
                                                                <span class="message">
                                                                    <?php echo htmlentities($n["notification_text"]);?>
                                                                </span>  
                                                            </a>
                                                        <?php }else{?>
                                                                <a  href="javascript:void(0)" onclick="openNotiTask('<?php echo $n["task_notification_id"];?>');" href="javascript://" class="notifi-txt" >
                                                                    <span class="pull-left">
                                                                        <?php   if((isset($n['profile_image']) && $n['profile_image']!='') && $this->s3->getObjectInfo($bucket,'upload/user/'.$n['profile_image'])){ ?>
                                                                                    <img alt="" class="capacity_images" src="<?php echo $s3_display_url.'upload/user/'.$n['profile_image']; ?>" class="profile-image" />
                                                                        <?php } else { ?>
                                                                            <span data-letters="<?php echo $word1.$word2; ?>"></span>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="subject">
                                                                        <span class="from"><?php echo ($n['notification_from']!='0')?ucwords($n["first_name"]." ".$n['last_name']):'N/A';?></span>
									<span class="time"><?php echo time_ago($n["date_added"]);?></span>
                                                                    </span>
                                                                    <span class="message">
                                                                        <?php echo htmlentities($n["notification_text"]);?>
                                                                    </span>  
								</a>
							<?php } } ?>
							<span class="notification-removeicon"> <a onclick="deleteNoti('<?php echo $n["task_notification_id"];?>')" href="javascript://"> <i class="icon-remove"></i> </a> </span>
                                </li>
                <?php } }else{ ?>
                    <li  class="margin-left-10-custom" id="allread" >No new notifications</li>
                <?php } ?>
              </ul>
            </li>
          
          </ul>
        </li>
        <!-- END INBOX DROPDOWN --> 
        
        <!-- START HELP DROPDOWN -->
        <li class="dropdown topnav-icon"> <a href="https://schedullo.atlassian.net/wiki/" class="dropdown-toggle" target="_blank" title="Help"> <i class=" icon-question-sign cstmicn"></i> <span class="badge"></span> </a> </li>
        <!-- END HELP DROPDOWN --> 
        
        <!-- BEGIN USER LOGIN DROPDOWN -->
        <li class="dropdown user"> <a href="#" class="dropdown-toggle myprofile-brand_header" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="Profile">
          <?php $name = 'upload/user/'.$user->profile_image;
		$word1 = ucfirst(substr($user->first_name,0,1));
                $word2 = ucfirst(substr($user->last_name,0,1));
                if(($user->profile_image != '' || $user->profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$user->profile_image)) { ?>
                     <img alt="" src="<?php echo $s3_display_url.'upload/user/'.$user->profile_image; ?>" class="profile-image" />
          <?php } else { ?>
            <span data-letters="<?php echo $word1.$word2; ?>"></span>
          <?php } ?>
          <i class="icon-angle-down"></i> </a>
          <ul class="dropdown-menu" style="left:auto;right: -1px;padding: 5px  !important; margin-top: 1px !important;">
            <li><?php echo anchor('user/my_settings?userid='.$encoded_user_id,'<div><i class="fa fa-cog" aria-hidden="true"></i> My Setting </div>');?></li>
            <?php if(count($userCompanyList) >1){
                    foreach($userCompanyList as $list){
                        if($list->company_name !='' ){?>
                            <li><a href="javascript:void(0)" <?php if($this->session->userdata('company_id') != $list->company_id){ ?>onclick="change_company('<?php echo $list->company_id; ?>','<?php echo $list->password; ?>');" <?php } ?>><div <?php if($this->session->userdata('company_id') == $list->company_id){ echo 'class="bold"'; }?>><i class="fa fa-industry" aria-hidden="true"></i> <?php echo $list->company_name; ?> </div></a></li>
                        <?php }else{ ?>
                            <li><a href="javascript:void(0)" <?php if($this->session->userdata('company_id') != $list->company_id){ ?>onclick="change_company('<?php echo $list->company_id; ?>','<?php echo $list->password; ?>');" <?php } ?>><div <?php if($this->session->userdata('company_id') == $list->company_id){ echo 'class="bold"'; }?>><i class="fa fa-industry" aria-hidden="true"></i> <?php echo $list->first_name." ".$list->last_name; ?></div></a></li>
            <?php } } }?>
            <li class="divider"> </li>
            <li><?php echo anchor('home/logout','<div><i class="fa fa-power-off" aria-hidden="true"></i> Log Out </div>'); ?></li>
          </ul>
        </li>
        <!-- END USER LOGIN DROPDOWN -->
        
      </ul>

      <!-- END TOP NAVIGATION MENU -->      
    </div>
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
	
<script type="text/javascript">

function refreshNotification()
{
	var str = '';
	var count_new = 0;
	var now_new_count= 0;
	$.ajax({
			url : "<?php echo site_url('home/getLetestNotification') ?>",
			cache: false,
                        dataType: "json",
			success: function(responseData) {
				var tot= 0;
				$.map(responseData, function (item) {
                               
                               var s3path = "<?php echo $s3_display_url;?>";
                              
                               	
                              	tot++;
                                var img = item.is_img;	
                                var word1 = item.first_name.charAt(0).toUpperCase();
                                var word2 = item.last_name.charAt(0).toUpperCase();
                              
                                count_new = item.task_notification_id.length;
                                
                                function firstToUpperCase( str ) {
                                    return str.substr(0, 1).toUpperCase() + str.substr(1);
				}
                              var from = 'N/A';
                                  if(item.first_name) from = firstToUpperCase(item.first_name+" "+item.last_name);
                                  var ntext = item.notification_text;
				  var t = item.date_added;
				  
                                  str += "<li class='active' id='rnoti_"+item.task_notification_id+"' >";
                                  if(item.task_id != "0"){
                                  	if(item.master_task_id == "0" || item.is_master_deleted == "1"){
                                  		str += '<a href="javascript:void(0)" onclick="edit_task(this,\''+item.task_id+'\',\''+item.is_chk+'\');openNotiTask('+item.task_notification_id+');" class="notifi-txt" >';
	                              		str += '<span class="pull-left" >';
                                                        if(item.profile_image){
                                                          str +='<img alt="" class="capacity_images" src="'+s3path+'"upload/user/"'+item.profile_image+'" class="profile-image" />';
                                                        }else{
                                                          str +='<span data-letters="'+word1+word2+'"></span>';
                                                        }                                                                  
                                                str +=" </span>";
	                                  	str += "<span class='subject'>";
	                                  	str += "<span class='from'>"+from+"</span>";
	                                  	str +=  "<span class='time'>"+item.date_added+"</span></span>";
						str += "<span class='message'>";
						str += ""+ntext+"</span></a>"; 
                                  	} else {
                                  		str += '<a href="javascript:void(0)" onclick="open_seris(this,\''+item.task_id+'\',\''+item.master_task_id+'\',\''+item.is_chk+'\');openNotiTask('+item.task_notification_id+');" class="notifi-txt" >';
	                              		str += '<span class="pull-left" >';
                                                        if(item.profile_image){
                                                          str +='<img alt="" class="capacity_images" src="'+s3path+'"upload/user/"'+item.profile_image+'" class="profile-image" />';
                                                        }else{
                                                          str +='<span data-letters="'+word1+word2+'"></span>';
                                                        }                                                                  
                                                str +=" </span>";
	                                  	str += "<span class='subject'>";
	                                  	str += "<span class='from'>"+from+"</span>";
	                                  	str +=  "<span class='time'>"+item.date_added+"</span></span>";
						str += "<span class='message'>";
						str += ""+ntext+"</span></a>"; 
                                  	}
				  } else {
                                        str += '<a href="javascript:void(0)" onclick="openNotiTask('+item.task_notification_id+');" href="javascript://" class="notifi-txt" >';
                              		str += '<span class="pull-left" >';
                                                        if(item.profile_image){
                                                          str +='<img alt="" class="capacity_images" src="'+s3path+'"upload/user/"'+item.profile_image+'" class="profile-image" />';
                                                        }else{
                                                          str +='<span data-letters="'+word1+word2+'"></span>';
                                                        }                                                                  
                                                str +=" </span>";
                                  	str += "<span class='subject'>";
                                  	str += "<span class='from'>"+from+"</span>";
                                  	str +=  "<span class='time'>"+item.date_added+"</span></span>";
					str += "<span class='message'>";
					str += ""+ntext+"</span></a>"; 
                                    }
                                  str += "<span class='notification-removeicon'><a onclick='deleteNoti("+item.task_notification_id+")' href='javascript://'> <i class='icon-remove'></i> </a> </span>";
                                  str += "</li>";
                                 
                          
                           });
                        if(tot>0) { $("#markall_li").show(); $('#allread').hide();}
                           $('#notyul li:first').after(str);
                         
                           var old_count = $("#countnotify").html();
                            var now_new_count = parseInt(old_count) + parseInt(tot);
                       		 $("#countnotify").html(now_new_count);
                         
		            	
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		            }
			});	
}
	$(document).ready(function(){
		
		$("#markAll").click(function(){
			
			
			   $.post("<?php echo site_url("home/NotyRead") ?>", 
                    {
                        id: "Readall"
                       
                    }, 
                    function(data) {
                    	$('.inbox li.external').remove();
                    	$('#hidebell').hide();
                    	$('#notyul li.active').removeClass('active');
                        
                    });
			
		});
		setInterval(function() {
         refreshNotification();
         }, 1000 * 60 * 15); 
		
		if(<?php echo $totnum;?>=='0')
		{
			$('#hidebell').hide();$("#markall_li").hide(); $('#allread').show();
		}else{
			$('#hidebell').show();$("#markall_li").show(); $('#allread').hide();
		}
		
		$('.noti_close').click(function(){
			$('#dvLoading').fadeIn('slow');
			$('#notify').modal('hide');
			$("#notititle").html("");
			$("#notiview").html("");
			$("#notipview").html("");
			$('#dvLoading').fadeOut('slow');
		});
	});
	
	function openNotiTask(id){
		$.ajax({
			type: 'POST',
			url : "<?php echo site_url('home/notification');?>",
			data:{notification_id:id},
			dataType: 'json',
			success: function(responseData) {
				count = responseData.total
            			
    			if(count!='0'){
    				document.getElementById("countnotify").textContent = count;
    			}else{
    				$('#hidebell').hide();
    			}
    			
    			$('#rnoti_'+responseData.detail.task_notification_id).removeClass('active');
			}
		});
	}
	
	function openNoti(id)
	{
		$("#notititle").html("");
		$("#notiview").html("");
		$("#notipview").html("");
		$('#dvLoading').fadeIn('slow');
		$.ajax({
			type: 'POST',
			url : "<?php echo site_url('home/notification');?>",
			data:{notification_id:id},
			dataType: 'json',
			success: function(responseData) {
						
						$('#notify').modal();
						
						if(responseData.detail.task_title){
						title ='<lable><b>Task </b>: '+ responseData.detail.task_title + '</lable>';
						title += '<br/><br/>';
						$(title).appendTo("#notititle");
						}
						
						if(responseData.detail.project_title){
						ptitle ='<lable><b>Project </b>: '+ responseData.detail.project_title + '</lable>';
						ptitle += '<br/><br/>';
						$(ptitle).appendTo("#notipview");
						}
						
						content = '<p><b>Notification</b> : ' + responseData.detail.notification_text + '</p>';
            			content += '<br/>';
            			$(content).appendTo("#notiview");
            			count = responseData.total
            			
            			if(count!='0'){
            				document.getElementById("countnotify").textContent = count;
            			}else{
            				$('#hidebell').hide();
            			}
            			
            			$('#rnoti_'+responseData.detail.task_notification_id).removeClass('active');
            			
						$('#dvLoading').fadeOut('slow');
		            	
		            },
		            error: function(responseData){
		                console.log('Ajax request not recieved!');
		                $('#dvLoading').fadeOut('slow');
		            }
		});	
}

function deleteNoti(noti_id)
{	
	$.ajax({
	url : "<?php echo site_url('home/deleteNotification') ?>/"+noti_id,
	cache: false,
	success: function(responseData) {
				$("#rnoti_"+noti_id).hide("slow");
				document.getElementById("countnotify").textContent = responseData;
				$('#rnoti_'+noti_id).remove();
				$('#header_notification_bar').addClass('open');
            	
            },
            error: function(responseData){
                console.log('Ajax request not recieved!');
            }
	});	
			
}
	
</script>

<div id="notify" class="modal model-size fade" tabindex="-1" >
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				<div class="modal-header">
					<button type="button" class="close noti_close" data-dismiss="modal" aria-hidden="true"></button>
					<h3>Notification Detail </h3>
				</div>
				<div>
					<form name="notifyDetail" id="notifyDetail" >
						<div class="addcomment-block">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<div class="controls">
											<div id="notititle">
											</div>
											
											<div id="notipview">
											</div>
											
											<div id="notiview">
											</div>
											
										  </div>
									</div>
								</div>
							 </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

<script src="<?php echo $theme_url; ?>/assets/plugins/jquery-bootstrap-modal-steps.js?Ver=<?php echo VERSION;?>"></script>

<div id="adminModal" class="modal  model-size fade" data-backdrop="static" data-keyboard="false" tabindex="-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="">Admin Setup</h3> 
      </div>
      <div class="modal-body">
      	<?php if($admin_setup){
      		foreach ($admin_setup as $key => $as) { ?>
		        <div class="row maintenance_row hide" data-step="<?php echo $key+1;?>" data-title="Step - <?php echo $key+1;?> ">
		          <div id="admin_dtl_<?php echo $as->as_step_id;?>" class="well maintenance_well"><?php echo $as->as_step_detail;?></div>
		          <input type="hidden" id="step_id" name="step_id" value="<?php echo $as->as_step_id;?>" />
		        </div>
       <?php }	} ?>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default js-btn-step pull-left" id="a_cancel" name="a_cancel" data-orientation="close" data-dismiss="modal"></button>
        <button type="button" class="btn btn-warning js-btn-step" id="a_previous" name="a_previous" data-orientation="previous"></button>
        <button type="button" class="btn btn-success js-btn-step" id="a_next" name="a_next" data-orientation="next"></button>
      </div>
    </div>
  </div>
</div>
<script>
	$('#adminModal').modalSteps();
</script>

<div id="userModal" class="modal  model-size fade" data-backdrop="static" data-keyboard="false" tabindex="-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="">User Setup</h3> 
      </div>
      <div class="modal-body">
      	<?php if($user_setup){
      		foreach ($user_setup as $key => $us) { ?>
		        <div class="row maintenance_row hide" data-step="<?php echo $key+1;?>" data-title="Step - <?php echo $key+1;?> ">
		          <div id="user_dtl_<?php echo $us->as_step_id;?>"  class="well maintenance_well"><?php echo $us->as_step_detail;?></div>
		          <input type="hidden" id="step_id" name="step_id" value="<?php echo $us->as_step_id;?>" />
		        </div>
       <?php }	} ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default js-btn-step pull-left" id="u_cancel" name="u_cancel" data-orientation="close" data-dismiss="modal"></button>
        <button type="button" class="btn btn-warning js-btn-step" id="u_previous" name="u_previous"  data-orientation="previous"></button>
        <button type="button" class="btn btn-success js-btn-step" id="u_next" name="u_next" data-orientation="next"></button>
      </div>
    </div>
  </div>
</div>
<script>
	$('#userModal').modalSteps();
</script>	

<div id="maintenanceModal" class="modal  model-size fade" data-backdrop="static" data-keyboard="false" tabindex="-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="">Maintenance Setup</h3> 
      </div>
      <div class="modal-body">
      	<?php 
      	if($maintenance_setup){ 
      		foreach ($maintenance_setup as $key => $ms) { ?>
      			
		        <div class="row maintenance_row hide" data-step="<?php echo $key+1;?>" data-title="Step - <?php echo $key+1;?> ">
		          <div class="well maintenance_well"><?php echo $ms->detail;?></div>
		        </div>
       <?php }	} ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default js-btn-step pull-left" id="m_cancel" name="m_cancel" data-orientation="close" data-dismiss="modal"></button>
      </div>
    </div>
  </div>
</div>
<script>
	$('#maintenanceModal').modalSteps();
</script>
<!-- chargify popup -->

<div id="chargifyNonAdmin" data-backdrop="static" data-keyboard="false"  class="modal model-size fade " tabindex="-1" >
	<div class="portlet">
		<div class="portlet-body  form flip-scroll">
			<div class="modal-header">
				<button id="chargifyNonAdmin_close" type="button" class="close noti_close" data-dismiss="modal" aria-hidden="true"></button>
				<h3>Chargify Alert</h3>
			</div>
			<div>
				<div id="chargifyMsg"></div>
			</div>
		</div>
	</div>
</div>

<!-- chargify popup ends -->

<!-- Logout Screen popup -->
<div id="Logout-screen" class="modal  model-size fade" tabindex="-1" >
		<div class="portlet">
			<div class="portlet-body  form flip-scroll">
				<div class="modal-header">
					<button type="button" class="close noti_close" data-dismiss="modal" aria-hidden="true"></button>
					<h3>Login Authentication Faield</h3>
				</div>
				<div>
					<div><p>You are not logged in anymore.</p><p>The reason is probably due to an extended period of inactivity.</p></div>
				</div>
			</div>
		</div>
	</div>
<!-- end of logut screen popup-->
                

                                                    
<?php $this->load->view($theme."/layout/common/task-popup"); 

?>

<script>
    var TOTAL_TIME = 0;
  		$(document).ready(function(){
                    var background_type = '<?php echo $this->session->userdata("user_background_type");?>';
    var background_name = '<?php echo $this->session->userdata("user_background_name");?>';
    if(background_type == 'Color')
        $('.bg_body').css('background-color',background_name);
    else {
        
        $('.bg_body').css('background','url(<?php echo $bg_image;?>) no-repeat center center fixed');
        $('.bg_body').css('background-size','cover');
    }
                    $(function(){
				$('.scroll_log').slimScroll({
				color: '#000',
				height : '500px',
                                wheelStep: 100

			 });
                     });
//  			$(window).bind('beforeunload', function(){
//  				if($("#is_timer_popup").val() == '1'){
//					return 'Are you sure, you want to leave this page? Data you have entered may not be save.';
//				}
//			});
			$(function(){
				$('.reason_scroll').slimScroll({
				color: '#000',
				height : '200px',
		 	    wheelStep: 100

			 });

			});
	 		var hasTimer = false;

	 		//select task from timer
	 		$("#select_task").on("click", function(){
	 			$("#timer").timer('reset');
	 			
                                if($("#current_page").val() == 'weekview' || $("#current_page").val() == 'NextFiveDayWeekView' || $("#current_page").val() == 'kanban'){
                                  $(".full_task div").removeClass("before_timer");
                                    $(".comm-box > a").addClass("after_timer_on");
                                    $(".taskbox > a").addClass("after_timer_on");
                                }else{
                                    $(".full-width div").removeClass("before_timer");
                                    $(".task_tasksort ul > li > div > a").addClass("after_timer_on");
       			$(".task_tasksort ul > li > em > div > a").addClass("after_timer_on");
                                }
       			selectTask();
	 		});


			// Init timer start
			$('#start').on('click', function() {
				$("#timer").timer('reset');
				$("#is_timer_popup").val('1');

			});

			// reason select

			$('.reason').on('click', function() {
				$("#common-timerbox").css("height","265px");
			});

			//Resume timer
			$("#resume").on('click', function(){
				$('#timer').timer('resume');
				$("#is_timer_popup").val('1');
				$("#reason_div").hide();
				$("#timer_div").show();
				$("#start").hide();

				$("#stop").show();
				$("#change_tak").show();
				//$("#endtask").show();
			});
			// Init timer pause
			$('#stop').on('click', function() {
				$("#timer_div").hide();
				$("#reason_div").show();
				$("#common-timerbox").css("height","295px");
			});

			// Additional focus event for this demo
			$('#timer').on('focus', function() {
				if(hasTimer) {
					$('#stop').addClass('hidden');
				}
			});

			// Additional blur event for this demo
			$('#timer').on('blur', function() {
				if(hasTimer) {
					$('#stop').removeClass('hidden');
					//$('.resume-timer-btn').addClass('hidden');
				}
			});


			$('#change_tak').on('click', function() {
				/*
				var ans = "Are you sure you want to change this task?";
								alertify.confirm(ans,function(t){
									if(t)
									{*/
//						if($("#hdn_timer").val()){
//		     				var total_time = time_sub($("#hdn_timer").val(),$("#timer").html());
//						} else {
//							var total_time = $("#timer").html();
//						}
                                        //sec2TimeObj
//                                        setCookie('timer_task_id','',-1);
//                                        getCookie('start_time','',-1);
//                                        setCookie('stop_time','',-1);
//                                        setCookie('duration','',-1);
                                        //setCookie('timer_status','',-1);
                                        var start_time = getCookie('start_again');
                                        var currenttime = Math.round(new Date().getTime() / 1000);
                                        var timer_time = currenttime - parseInt(start_time);
                                        
                                        var min = timer_time/60;
                                        var tobject = sec2TimeObj(timer_time);
                                        var total_time = tobject.hours + ':' + tobject.minutes + ':' + tobject.seconds;
                                        
						var task_id = $("#timer_task_id").val();
                                                if(getCookie('timer_status') && getCookie('timer_status') == 'stop'){}else{
						if(total_time){
							var a = total_time.split(':'); // split it at the colons
							var minutes = (+a[0]) * 60  + (+a[1]);
                                                        $.ajax({
								type : "post",
								url : "<?php echo site_url('task/save_time');?>",
								data : {task_id : task_id, time : total_time},
								success : function(data){

									var data = jQuery.parseJSON(data);
									var task_time = data.total_spent_time;
									var scope_time_div = task_time.split("/");
									if(scope_time_div[1]!="0m"){
										$("#task_time_"+task_id).show();
									}
									$("#task_time_"+task_id).html(data.total_spent_time);
									$('#timer').timer('pause');
									$("#is_timer_popup").val('0');
									$("#hdn_timer").val('');
									$("#total_interruptions").html(data.interruptions);

									if($("#current_page").val() == 'weekview' || $("#current_page").val() == 'NextFiveDayWeekView'){
										if($("#main_"+task_id).length){
											var parent_id = $("#main_"+task_id).parent("div").attr("id");
											parent_id = parent_id.replace ( /[^\d.]/g, '' );
											//alert(parent_id);
											var c1 = $("#capacity_"+parent_id).attr('data-time');
											var e1 = $("#est_"+parent_id).attr('data-time');
											var s1 = $("#spent_"+parent_id).attr('data-time');
											var s1 = parseInt(s1) + parseInt(minutes);
											e1 = parseInt(e1);
											c1 = parseInt(c1);
											$.ajax({
												type: "post",
												url: SIDE_URL + "calendar/update_progress_bar",
												data: {
													id: parent_id,
													capacity: c1,
													estimate_time: e1,
													spent_time: s1,
													title: 'Capacity:'+hoursminutes(c1)+'<br>Time Estimate: '+hoursminutes(e1)+'<br>Time Spent: '+hoursminutes(s1)
												},
												success: function(progress) {
													$('#progress_'+parent_id).html(progress)
												}
											});
											//$("#spent_"+parent_id).html(final_spent);


										}
									} else if($("#current_page").val() == 'kanban'){
										if($("#main_"+task_id).length){
											var parent_id = $("#main_"+task_id).parent("div").attr("id");
											strings = parent_id.split("_");
											parent_id = strings[2];
											var orig_time = $("#status_time_"+parent_id+" .hrrlt").html();
											var orig_time_minutes = get_minutes(orig_time);
											var total_week_spent = parseInt(orig_time_minutes) + parseInt(minutes);
											var final_spent = hoursminutes(total_week_spent);
											$("#status_time_"+parent_id+" .hrrlt").html(final_spent);
										}
									} else {

									}
								}
							});
						}}
						$("#is_timer_popup").val('1');
                                                if($("#current_page").val() == 'weekview' || $("#current_page").val() == 'NextFiveDayWeekView' || $("#current_page").val() == 'kanban'){
                                                
                                                
						$(".full_task div").removeClass("before_timer");
       					$(".comm-box > a").addClass("after_timer_on");
		       			$(".taskbox > a").addClass("after_timer_on");
                                                }else{
                                                $(".full-width div").removeClass("before_timer");
                                                
                                        $(".task_tasksort > ul >li >div > a").addClass("after_timer_on");
                                        $(".task_tasksort > ul >li >em >div > a").addClass("after_timer_on");
                                                }
                                        setCookie('timer_task_id','',-1);
                                        setCookie('start_time','',-1);
                                        setCookie('stop_time','',-1);
                                        setCookie('duration','',-1);
                                        setCookie('timer_status','',-1);
                                        setCookie('start_again','',-1);
                                        setCookie('total_time','',-1);
                                        
		       			//$("#timer_task_id").val('');
						selectTask();



			      	/*
					  }
											return false;
									  });*/

			});
                                
       	$.validator.addMethod("greaterThan", 
		function(value, element, params) {
			if($("#to_date").val()=='' || $("#from_date").val()==''){
				return true;
			}
                        $('#from_date').datepicker({
			startDate: -Infinity,
			format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
                        autoclose:true,
                        
                        });
                
                        $('#to_date').datepicker({
                                startDate: -Infinity,
                                format: '<?php echo $date_arr_java[$site_setting_date]; ?>',
                                autoclose:true,

                        });
		    //var from_date = $('#from_date').datepicker('getDate');
			//var to_date = $("#to_date").datepicker('getDate');
			
			var from_date = $('#from_date').val();
			var to_date = $("#to_date").val();
			//alert(from_date+"===="+to_date);
			from_date = $('#from_date').datepicker('getDate');
			to_date = $('#to_date').datepicker('getDate');
			//alert(from_date+"===="+to_date);
			//alert(from_date+"===="+to_date);
			//alert(('#from_date').data('date'));
			//alert(from_date);
			//alert(to_date);
			//alert(Number($('#to_date').val())+"===="+Number($('#from_date').val()));
			if (!/Invalid|NaN/.test(to_date)) {
		        return to_date >= from_date;
		    }
			return (Number($('#to_date').val()) >= Number($('#from_date').val())); 
		},'Must be greater than or equal to start date.');

			$("#endtask").click(function(){
                                
				if($('#task_com_status').val() === 'red')
				{
					alertify.alert('You cannot change status of the main task as its dependent tasks are still not completed.');
					return false;
				}
				var ans = "Are you sure you want to complete this task?";
				alertify.confirm(ans,function(t){
					if(t == true)
					{
                                            end_task_timer();

			      	}
			      	return false;
				});
			});

			$("#start_interruption").on('click',function(){
                                var startagain = Math.round(new Date().getTime() / 1000);
                                var stop = getCookie('stop_time');
                                var stop1 = parseInt(stop);
                                var duration = startagain - stop;
                                
                                if(getCookie('duration'))
                                    duration = duration + parseInt(getCookie('duration'));
                                setCookie('start_again',startagain,1);
                                setCookie('duration',duration,1);
                                
				$("#timer").timer('resume');
				$("#is_timer_popup").val('1');
				$("#start_interruption").hide();
				$("#stop").show();
				$("#change_tak").show();
                                setCookie('timer_status','',-1);
			});

		});


     	function selectTask(){
     		$("#timer_task_title").show();
     		$("#timer_task_title").html('Click on the task to start timer.');
     		$("#common-timerbox").css("height","265px");
                setCookie('timer_status','',-1)
     		return false;
     	}

     	function rectime(time) {
			var hr = Math.floor(time / 60);
			var min = time - (hr * 60);
			var sec = '00';

			if (min < 10) {min = '0' + min;}
			if (hr < 10) {hr = '0' + hr;}
			return hr + ':'+ min + ':' + sec;
		}
     	function chk_task_selected(title,time){
     		$(".full_task div a").unbind('click', false);
                $(".task_tasksort ul li a").unbind('click', false);
     		$("#timer_task_title").show();
     		$("#timer_task_title").html('Task : '+title);
     		var spent_time = rectime(time);
			var task_id = $("#timer_task_id").val();
			$("#total_timer").html(spent_time);
                        var start_time = 0;
                                var cdate = new Date();
                                var time = Math.round(cdate.getTime()/1000);
                                var old_task_id = getCookie('timer_task_id');
                                var oldtime = getCookie('start_time');
                                if(old_task_id == task_id && oldtime !='')
                                {
                                    var oldtime1 = parseInt(oldtime);
                                    start_time = time-oldtime1;
                                    
                                    if(getCookie('duration'))
                                        start_time = start_time - parseInt(getCookie('duration'));
                                    
                                }
                                else{
                                    $("#timer").timer('reset');
                                    setCookie('timer_task_id',task_id,1);
                                    setCookie('start_time',time,1);
                                    setCookie('timer_status','',-1)
                                    setCookie('start_again',time,1);
                                }
			
			$("#is_timer_popup").val('1');
                        TOTAL_TIME = getCookie('total_time')?parseInt(getCookie('total_time')):0;
 			if(task_id){
                            if(start_time == 0){

 				$.ajax({
 					type : 'post',
 					url : '<?php echo site_url("task/spent_time");?>',
 					data : { task_id : task_id},
 					success : function(data){
 						var spent_time = rectime(data);
 						$("#total_timer").html(spent_time);
                                                TOTAL_TIME = parseInt(data)*60;
                                                setCookie('total_time',TOTAL_TIME,1);
 					}
 				});
                                }
                                if(getCookie('timer_status') && getCookie('timer_status') == 'stop')
                                {
                                    var oldtime1 = parseInt(oldtime);
                                    start_time = parseInt(getCookie('stop_time'))-oldtime1;
                                    
                                    if(getCookie('duration'))
                                        start_time = start_time - parseInt(getCookie('duration'));
                                    
                                    
                                $("#total_timer").html(rectime(TOTAL_TIME)/60);
                                    $('#timer').timer({
					editable: true,
                                        seconds:start_time
				});
                                
                                                
                                    $('#timer').timer('pause');
                                    $("#start_interruption").show();
				$("#stop").hide();
                                }
                                else
                                {
                                    $('#timer').timer({
					editable: true,
                                        seconds:start_time
				});
                                $("#start_interruption").hide();
				$("#stop").show();
                            }
 				hasTimer = true;
 				

				//$(this).addClass('hidden');
				$('#stop').removeClass('hidden');
				$("#select_task").hide();
				$("#start").hide();
				$("#resumeme").hide();
				
                                if(ACTIVE_MENU == 'from_kanban' || ACTIVE_MENU == 'from_calendar' || ACTIVE_MENU == 'weekView' || ACTIVE_MENU == 'NextFiveDay' || ACTIVE_MENU == 'from_project') {
            $("#change_tak").show();
        }    
				
                                $("#timer_comment").show();
				//$("#endtask").show();
     		} else {
     			return false;
     		}
     	}

     	function add_interruption(val){
                var comment = $("#timer_comment").val();
                if(val){
//     			if($("#hdn_timer").val()){
//     				var total_time = time_sub($("#hdn_timer").val(),$("#timer").html());
//     			} else {
//					var total_time = $("#timer").html();
//				}
				var task_id = $("#timer_task_id").val();
//				var a = total_time.split(':'); // split it at the colons
//				var minutes = (+a[0]) * 60  + (+a[1]);
                                
                                var start_time = getCookie('start_again');
                                        var currenttime = Math.round(new Date().getTime() / 1000);
                                        var timer_time = currenttime -  parseInt(start_time);
                                        
                                        var minutes = timer_time/60;
                                        var tobject = sec2TimeObj(timer_time);
                                        var total_time = tobject.hours + ':' + tobject.minutes + ':' + tobject.seconds;
                                        
                                setCookie('stop_time',Math.round(new Date().getTime() / 1000),1);
                                setCookie('timer_status','stop',1);
     			$.ajax({
     				type : 'post',
     				url : '<?php echo site_url("task/save_time");?>',
     				data : {task_id:task_id, time:total_time,interruption:val,timer_comment:comment},
     				success : function(data){//alert(data);
     					var data = jQuery.parseJSON(data);
     					var task_time = data.total_spent_time;
						var scope_time_div = task_time.split("/");
						if(scope_time_div[1]!="0m"){
							$("#task_time_"+task_id).show();
						}
						$("#task_time_"+task_id).html(data.total_spent_time);
						$('#timer').timer('pause');
						$("#is_timer_popup").val('0');
						$("#hdn_timer").val($("#timer").html());
						$("#total_interruptions").html(data.interruptions);
						$("#reason_div").hide();
						$("#timer_div").show();
						$("#stop").hide();
						$("#change_tak").show();
						//$("#endtask").hide();
						$("#start").hide();
						$("#start_interruption").show();
                                                $("#timer_comment").show();
                                                $("#timer_comment").val('');
						if($("#current_page").val() == 'weekview' || $("#current_page").val() == 'NextFiveDayWeekView'){
							if($("#main_"+task_id).length){
								var parent_id = $("#main_"+task_id).parent("div").attr("id");
								parent_id = parent_id.replace ( /[^\d.]/g, '' );
								var c1 = $("#capacity_"+parent_id).attr('data-time');
								var e1 = $("#est_"+parent_id).attr('data-time');
								var s1 = $("#spent_"+parent_id).attr('data-time');
								var ss1 = parseInt(s1) + parseInt(minutes);
								var ee1 = parseInt(e1);
								var cc1 = parseInt(c1);
								$.ajax({
									type: "post",
									url: SIDE_URL + "calendar/update_progress_bar",
									data: {
										id: parent_id,
										capacity: cc1,
										estimate_time: ee1,
										spent_time: ss1,
										title: 'Capacity:'+hoursminutes(cc1)+'<br>Time Estimate: '+hoursminutes(ee1)+'<br>Time Spent: '+hoursminutes(ss1)
									},
									success: function(progress) {
										$('#progress_'+parent_id).html(progress)
									}
								});
								//$("#spent_"+parent_id).html(final_spent);
								var spent_time = rectime(data.total_timer_time);
								$("#total_timer").html(spent_time);
							}
						} else if($("#current_page").val() == 'kanban'){
							if($("#main_"+task_id).length){
								var parent_id = $("#main_"+task_id).parent("div").attr("id");
								strings = parent_id.split("_");
								parent_id = strings[2];
								var orig_time = $("#status_time_"+parent_id+" .hrrlt").html();
								var orig_time_minutes = get_minutes(orig_time);
								var total_week_spent = parseInt(orig_time_minutes) + parseInt(minutes);
								var final_spent = hoursminutes(total_week_spent);
								$("#status_time_"+parent_id+" .hrrlt").html(final_spent);
								var spent_time = rectime(data.total_timer_time);
								$("#total_timer").html(spent_time);
							}
						} else {
								var spent_time = rectime(data.total_timer_time);
								$("#total_timer").html(spent_time);
						}

     				}
     			});
     		}
     	}
        function end_task_timer()
        {
            var comment = $("#timer_comment").val();

                                        var start_time = getCookie('start_again');
                                        var currenttime = Math.round(new Date().getTime() / 1000);
                                        var timer_time = currenttime -  parseInt(start_time);
                                       
                                        var minutes = timer_time/60;
                                        var tobject = sec2TimeObj(timer_time);
                                        var total_time = tobject.hours + ':' + tobject.minutes + ':' + tobject.seconds;
						var task_id = $("#timer_task_id").val();
						if(total_time){
							var a = total_time.split(':'); // split it at the colons
							var minutes = (+a[0]) * 60  + (+a[1]);
							$.ajax({
								type : "post",
								url : "<?php echo site_url('task/save_time');?>",
								data : {task_id : task_id, time : total_time, name : 'completed',timer_comment:comment},
								success : function(data){
									var data = jQuery.parseJSON(data);
									var task_time = data.total_spent_time;
									var scope_time_div = task_time.split("/");
									if(scope_time_div[1]!="0m"){
										$("#task_time_"+task_id).show();
									}
									$("#task_time_"+task_id).html(data.total_spent_time);
									$("#timer").html("00:00:00");
									$("#timer").timer('pause');
									$("#is_timer_popup").val('0');
									$("#hdn_timer").val('');
									$("#total_interruptions").html(data.interruptions);
									$("#reason_div").hide();
									$("#timer_div").show();
									$("#stop").hide();
									$("#change_tak").hide();
									$("#start").hide();
									$("#select_task").show();
									$("#timer_task_title").hide();
                                                                        $("#timer_comment").hide();
                                                                        $("#timer_comment").val('');
									var spent_time = rectime(data.total_timer_time);
									$("#total_timer").html("00:00:00");

									if($("#current_page").val() == 'weekview' || $("#current_page").val() == 'NextFiveDayWeekView'){
										if($("#main_"+task_id).length){
											var parent_id = $("#main_"+task_id).parent("div").attr("id");
											parent_id = parent_id.replace ( /[^\d.]/g, '' );
											var orig_time = $("#spent_"+parent_id).html();
											var orig_time_minutes = get_minutes(orig_time);
											var total_week_spent = parseInt(orig_time_minutes) + parseInt(minutes);
											var final_spent = hoursminutes(total_week_spent);
											var c1 = $("#capacity_"+parent_id).attr('data-time');
											var e1 = $("#est_"+parent_id).attr('data-time');
											var s1 = $("#spent_"+parent_id).attr('data-time');
											var ss1 = parseInt(s1) + parseInt(minutes);
											var ee1 = parseInt(e1);
											var cc1 = parseInt(c1);
											$.ajax({
												type: "post",
												url: SIDE_URL + "calendar/update_progress_bar",
												data: {
													id: parent_id,
													capacity: cc1,
													estimate_time: ee1,
													spent_time: ss1,
													title: 'Capacity:'+hoursminutes(cc1)+'<br>Time Estimate: '+hoursminutes(ee1)+'<br>Time Spent: '+hoursminutes(ss1)
												},
												success: function(progress) {
													$('#progress_'+parent_id).html(progress)
												}
											});
											//$("#spent_"+parent_id).html(final_spent);
											var array_data = $("#task_data_"+task_id).val();
											var status = '<?php echo $completed_id;?>';
											$.ajax({
												type : 'post',
												url : '<?php echo site_url("calendar/update_status");?>',
												data : { data : array_data, status : status, from_module : "footer",start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php if(isset($active_menu)){ echo $active_menu; } else { echo ""; }?>' },
												success : function(responseData){
													App.init();
													$("#main_"+task_id).replaceWith(responseData);
													var data = jQuery.parseJSON(array_data);
													if(data.prerequisite_task_id != "0"){
														$.ajax({
															type : 'post',
															url : SIDEURL+'kanban/check_completed_dependency',
															data : {task_id :  data.prerequisite_task_id},
															success : function(task_detail3){
																if(task_detail3){
																	task_detail3 = jQuery.parseJSON(task_detail3);
																	$.ajax({
																		type : 'post',
																		url : SIDEURL+'calendar/set_weekly_update_task',
																		data : {task_id:data.prerequisite_task_id,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:ACTIVE_MENU},
																		success : function(taskData){
																			if(task_detail3.main_task_status_id == task_detail3.task_status_id){
																				if($("#main_"+data.prerequisite_task_id).length){
																					$("#main_"+data.prerequisite_task_id).replaceWith(taskData);
																				}
																			} else {
																				$("#main_"+data.prerequisite_task_id).replaceWith(taskData);
																			}

																		}
																	});
																}
															}
														});
													}
												}

											});
										}
									} else if($("#current_page").val() == 'kanban'){

										if($("#main_"+task_id).length){
											var parent_id = $("#main_"+task_id).parent("div").attr("id");
											strings = parent_id.split("_");
											parent_id = strings[2];
											var orig_time = $("#status_time_"+parent_id+" .hrrlt").html();
											var orig_time_minutes = get_minutes(orig_time);
											var total_week_spent = parseInt(orig_time_minutes) + parseInt(minutes);
											var final_spent = hoursminutes(total_week_spent);
											$("#status_time_"+parent_id+" .hrrlt").html(final_spent);


											

											var came_from_time = $("#status_time_"+parent_id).html();
									  		if(came_from_time){
									  			var came_from_estimate = get_minutes($("#status_time_"+parent_id+" .hrlft").html());
									        	var came_from_spent = get_minutes($("#status_time_"+parent_id+" .hrrlt").html());
									  		} else {
									  			var came_from_estimate = '0';
									        	var came_from_spent = '0';
									  		}


									    	var scope_time = $("#task_time_"+task_id).html();
									    	if(scope_time){
									    		var scope_time_div = scope_time.split("/");
									        	var scope_time_estimate = get_minutes(scope_time_div[0]);
									        	var scope_time_spent = get_minutes(scope_time_div[1]);
									    	} else {
									    		var scope_time_estimate = '0';
									        	var scope_time_spent = '0';
									    	}

											var array_data = $("#task_data_"+task_id).val();
											var status = '<?php echo $completed_id;?>';

											var dropped_time = $("#status_time_"+status).html();

									  		if(dropped_time){
									  			var dropped_estimate = get_minutes($("#status_time_"+status+" .hrlft").html());
									        	var dropped_spent = get_minutes($("#status_time_"+status+" .hrrlt").html());
									  		} else {
									  			var dropped_estimate = '0';
									        	var dropped_spent = '0';
									  		}

											$.ajax({
												type : 'post',
												url : '<?php echo site_url("kanban/update_status");?>',
												data : { data : array_data, status : status, from_module : "footer" },
												success : function(responseData){
													var responseData = jQuery.parseJSON(responseData);
													var came_estimate = hoursminutes(parseInt(came_from_estimate)-parseInt(scope_time_estimate));
													var came_spent = hoursminutes(parseInt(came_from_spent) - parseInt(scope_time_spent));

													var came_final_time = "<span class='hrlft tooltips' data-original-title='Estimate Time'>"+came_estimate+"</span><span class='hrrlt' data-original-title='Spent Time'>"+came_spent+"</span>";
													$("#status_time_"+parent_id).html(came_final_time);

													var dropped_est = hoursminutes(parseInt(dropped_estimate)+parseInt(scope_time_estimate));
													var dropped_spe = hoursminutes(parseInt(dropped_spent)+parseInt(scope_time_spent));
													var dropped_final_time = "<span class='hrlft tooltips' id='Estimate_time_"+status+"' data-original-title='Estimate Time'>"+dropped_est+"</span><span id='spent_time_"+status+"' class='hrrlt tooltips' data-original-title='Spent Time'>"+dropped_spe+"</span>";
													//alert(dropped_final_time);
													$("#status_time_"+status).html(dropped_final_time);

													//Set limit for loadmore

													var completed_loadMore_limit = $("#completed_loadMore_limit"+status+""+responseData.swimlane_id).val();

								 		            var completed_loadMore_limit_new = parseInt(completed_loadMore_limit) + parseInt("1");

								 		            $("#completed_loadMore_limit"+status+""+responseData.swimlane_id).val(completed_loadMore_limit_new);

													//end of limit for load more completed task//
													$("#main_"+task_id).remove();

													$.ajax({
														type : 'post',
														url : '<?php echo site_url("kanban/set_update_task");?>',
														data : {task_id : task_id},
														success : function(task_detal){

															var html_data = task_detal;
															$("#task_status_"+status+"_"+responseData.swimlane_id).append(html_data);
															var array_data = $("#task_data_"+task_id).val();
															var array_data = jQuery.parseJSON(array_data);

															if(array_data.prerequisite_task_id != "0"){
																$.ajax({
																	type : 'post',
																	url : SIDEURL+'kanban/check_completed_dependency',
																	data : {task_id :  array_data.prerequisite_task_id},
																	success : function(task_detail3){
																		if(task_detail3){
																			task_detail3 = jQuery.parseJSON(task_detail3);
																			$.ajax({
																				type : 'post',
																				url : SIDEURL+'kanban/set_update_task',
																				data : {task_id : array_data.prerequisite_task_id},
																				success : function(taskData){
																					if(task_detail3.main_task_status_id == task_detail3.task_status_id){
																						if($("#main_"+array_data.prerequisite_task_id).length){
																							$("#main_"+array_data.prerequisite_task_id).replaceWith(taskData);
																						}
																					} else {
																						$("#task_status_"+task_detail3.task_status_id+"_"+array_data.swimlane_id).prepend(taskData);
																						$("#main_"+array_data.prerequisite_task_id).remove();

																					}

																				}
																			});
																		}
																	}
																});
															}
														}
													});
													
												}
											});
										}
									} else {
                                                                         var g = $("#select_task_assign").val(),
                                                                             h = $("#select_task_status").val();
										 $.ajax({
                                                                                type: "post",
                                                                                url: SIDE_URL + "project/set_update_task",
                                                                                data: {
                                                                                    task_id: task_id,
                                                                                    type: h,
                                                                                    user_id: g
                                                                                },
                                                                                async: !1,
                                                                                success: function(rs) { 
                                                                                    App.init(), $("#task_tasksort_" + task_id).replaceWith(rs);
                                                                                }
                                                                            })
									}

								},
                                                                error:function(data){
                                                                alertify.set("notifier", "position", "top-right"), 
                                                                alertify.error("Please check your internet connection."),
                                                                console.log("Ajax request not received!");
                                                            }
							});
						}
                                                setCookie('timer_task_id','',-1);
                                        setCookie('start_time','',-1);
                                        setCookie('stop_time','',-1);
                                        setCookie('duration','',-1);
                                        setCookie('timer_status','',-1);
                                        setCookie('start_again','',-1);
                                        setCookie('total_time','',-1);
                                        }
     	function time_sub(start,end){

		    s = start.split(':');
		    e = end.split(':');

			sec = e[2]-s[2];
			min_carry = 0;
			if(sec<0){
				sec += 60;
				min_carry += 1;
			}
		    min = e[1]-s[1]-min_carry;
		    hour_carry = 0;
		    if(min < 0){
		        min += 60;
		        hour_carry += 1;
		    }
		    hour = e[0]-s[0]-hour_carry;
		    if(hour<10){
		    	hour = "0"+hour;
		    }
		    if(min<10){
		    	min = "0"+min;
		    }
		    if(sec<10){
		    	sec = "0"+sec;
		    }
		    diff = hour + ":" + min + ":" + sec;
		    return diff;
     	}
        </script>
<!--set user country & timezone        -->

<div id="timeZoneModal" class="modal fade new_alert_msg alert-info"  tabindex="-1" >
        <div class="modal-body">
          <div class="portlet new_porlet_body" >
            <div class="portlet-body flip-scroll padd15" >
                <strong>Your Schedullo is nearly ready.</strong> Please confirm the setup below:
                <form id="user_info" name="user_info" method="post" onsubmit="event.preventDefault();">
                <div class="form-group">
                    <label class="control-label">Time Zone : </label>
                    <div class="controls">
                        <select class=" m-wrap mysetting-select alert_input" id="user_time_zone" name="user_time_zone" tabindex="1" >
                            <option value="">--Select--</option>
                            <?php if(isset($timezones) && $timezones!=''){
                                    foreach($timezones as $t){ ?>
                            <option value="<?php echo $t->timezone_name;?>" ><?php echo $t->name;?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Country : </label>
                    <div class="controls">
                        <select class=" m-wrap mysetting-select alert_input" id="country_code" name="country_code" tabindex="2" >
                            <option value="">--Select--</option>
                            <?php if(isset($countries) && $countries!=''){
                                    foreach($countries as $c){ ?>
                                    <option value="<?php echo $c->Countries_ISO_Code;?>" ><?php echo $c->country_name;?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn green" id="save_user_info" type="submit">Save changes</button>
                </div>
                </form>
            </div>
	</div>
      </div>
    
</div>
        
<!--Set password for new user-->

<div id="User_password" class="modal fade new_alert_msg alert-info"  tabindex="-1" >
        <div class="modal-body">
          <div class="portlet new_porlet_body" >
            <div class="portlet-body flip-scroll padd15">
                <p><strong>Change your Password</strong></p>
                <p>To ensure that your data is safe and secure, please set your password now.</p>
                <p>The password needs to be at least 8 characters long, with 1 number, 1 uppercase & 1 lowercase letter.</p>
                <form id="set_user_password" name="set_user_password" method="post" onsubmit="event.preventDefault();">
                <div class="form-group">
                    <label class="control-label">New Password : </label>
                    <div class="controls">
                        <input type="password" class="alert_input m-wrap mysetting-select " id="set_password" name="set_password" tabindex="1">
                            
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Confirm Password : </label>
                    <div class="controls">
                        <input type="password" class=" m-wrap mysetting-select alert_input" id="set_confirm_password" name="set_confirm_password" tabindex="2" >
                            
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn green" id="save_user_password" type="submit">Save changes</button>
                </div>
                </form>
            </div>
	</div>
      </div>
    
</div>

<!--modal popup for deactive user-->

<div id="deactive_user_account" data-backdrop="static" data-keyboard="false"  class="modal chargidy_width"tabindex="-1" >
    <div class="modal-body chargify_modal_body">
        <div class="portlet chargify_porlet padd15" >
            <div class="portlet-body form flip-scroll">
                <button id="deactiveaccount_close" type="button" class="close noti_close" data-dismiss="modal" aria-hidden="true"></button>
                   <strong><b>Your account has been deactivated </b></strong> as you have not validated your email address.<br>
                   <p>Please check your email and click on the activation link.</p>
                   <p>If you have misplaced the email, <a href="javascript:void(0);" onclick="resend_mail('<?php echo $this->session->userdata('user_id') ?>');" style="text-decoration: underline;"> click here</a> to send a new one.</p>
            </div>
	</div>
    </div>
</div>