$(document).ready(function(){
        $("#outlook_synchronization").bootstrapToggle();
        $("#daily_email_summary").bootstrapToggle();
        $('#outlook_synchronization').bootstrapToggle().on('change', function (e, data) {
            var value =$(this).prop('checked')?1:0;
            $('#outlook_synchronization_loading').show();
            if(value == '1')
            {
                call();
            }
            else
            {
                $.ajax({
			type : 'post',
			url : SIDE_URL+"user/outlook_synchronization_off",
			success: function(){
				$('#outlook_synchronization_loading').hide();
			}
		});
            }
    	
		
	});
    	
	$('#daily_email_summary').bootstrapToggle().on('change', function () {
           var value =$(this).prop('checked')?1:0;
        $('#daily_email_summary_loading').show();

		$.ajax({
			type : 'post',
			url : SIDE_URL+"user/mysetting_index",
			data : {name:'daily_email_summary', value : value},
			success: function(){
				$('#daily_email_summary_loading').hide();
			}
		});
	});
         
        $(".mysetting-text").blur(function(){
        	var id = $(this).attr('id');
	    	$("#"+id+"_loading").show();
	    	var name = $(this).attr('name');
	    	var value = $(this).val();
	    	if(value.trim()){
                                $.ajax({
			    		type : 'post',
			    		url : SIDE_URL+'user/mysetting_index',
			    		data : {name:name, value:value},
			    		async:false,
			    		success : function(data){
			    			$("#"+id+"_loading").hide();
			    		}
			    	});
		    	
			} else {
		    	$("#alertify").show();
	    		alertify.alert("This field is required.", function (e) {
					$("#"+id).focus(); $("#alertify").hide();$("#alertify-cover").css("position","relative");
					return false;
				});
		    }
	    });
	    
	    
	$(".mysetting-select").change(function(){
			var id = $(this).attr('id');
			$("#"+id+"_loading").show();
			var name = $(this).attr('name');
			var value = $(this).val();
			if(value.trim()){
				$.ajax({
					type : 'post',
					url : SIDE_URL+'user/mysetting_index',
					data : {name:name, value:value},
					async:false,
					success : function(data){
						$("#"+id+"_loading").hide();
						$("#old_"+id).val(value);
					}
				});
			}else {
		    	$("#alertify").show();
	    		alertify.alert("This field is required.", function (e) {
					$("#"+id).focus();
					$("#"+id).val($("#old_"+id).val());
					$("#"+id+"_loading").hide(); 
					$("#alertify").hide();$("#alertify-cover").css("position","relative");
					return false;
				});
		    }
		});
		
	    
	$(".mysetting-text").click(function(){
	    	var id = $(this).attr('id');
	    	var name = $(this).attr('name');
	    	var value = $(this).val();
	    	//if(value.trim()){
	    		if(name =='tags_division'){
	    			$("#"+id+"_loading").show();
	    			$.ajax({
			    		type : 'post',
			    		url : SIDE_URL+'user/mysetting_index',
			    		data : {name:name, value:value},
			    		async:false,
			    		success : function(data){
			    			$("#"+id+"_loading").hide();
			    		}
			    	});
			    	
			    	$.ajax({
						type : 'post',
						url : SIDE_URL+'user/divisionsSettings',
						data : {user_id : user_id},
						success : function(data){
							$("#addUserDivisionDivSettings").html(data);
						}
					});
			    	
			    	$.ajax({
						type : 'post',
						url : SIDE_URL+'user/departmentsSettings',
						data : {user_id : user_id, division_id:$("#tags_division").val()},
						success : function(data){
							$("#addUserDepartmentDivSettings").html(data);
						}
					});
			    	
	    		}else{
	    			$("#"+id+"_loading").show();
	    			$.ajax({
			    		type : 'post',
			    		url : SIDE_URL+'user/mysetting_index',
			    		data : {name:name, value:value},
			    		async:false,
			    		success : function(data){
			    			$("#"+id+"_loading").hide();
			    		}
			    	});
	    		}
	    	//}
        });
        
        $("#profile_image").change(function(){
            if(this.files[0].size <= 2097152){
		var data = new FormData($("#frm_my_settings")[0]);
	  	$('#dvLoading').fadeIn('slow');
	        $.ajax({
	            type: 'post',
	            url : SIDE_URL+'user/myprofile_logo',
	            data: data,
		        processData: false,
		        contentType: false,
	            success: function(responseData) {
	            	var responseData = jQuery.parseJSON(responseData);
	            	$('#myprofile_logo_view').html('<img src="'+responseData+'" style="width:200px;height:200px;" alt="schedullo">');
	            	$('.myprofile-brand_header').html('<img class="profile-image" src="'+responseData+'" alt="schedullo"  ><i class="icon-angle-down"></i>');
			$("#myprofile-logo-browse").css('display','block');
			$("#myprofile-logo-change").css('display','none');
			$("#myprofile-logo-preview").html('');
			$("#myprofile-logo-icon").css('display','none');
                        $('#new_user_image').html('<img src="'+responseData+'" class="img-responsive" alt="schedullo">');
		        $('#dvLoading').fadeOut('slow');  	
	            },
	            error: function(responseData){
	                console.log('Ajax request not recieved!');
	                $('#dvLoading').fadeOut('slow');  	
	            }
	        });
            }else
    {
        toastr.error('File size should be less than 2MB');
    }
		});
        $("#background_image").change(function(){
            if(this.files[0].size <= 2097152)
            {
            
            var data = new FormData($("#frm_background_image")[0]);
        $('#dvLoading').fadeIn('slow');
        $.ajax({
            type: 'post',
            url : SIDE_URL+'user/upload_background_image',
            data: data,
                processData: false,
                contentType: false,
            success: function(responseData) {
                console.log(responseData);
                var responseData = jQuery.parseJSON(responseData);
                console.log(responseData);
                $('#bg_thumbnal').css('background','url('+responseData+')');
                $('#bg_thumbnal').css('background-size','cover');
                toastr.success('Background Image successfully uploaded.');
                $('#dvLoading').fadeOut('slow');  	
            },
            error: function(responseData){
                console.log('Ajax request not recieved!');
                $('#dvLoading').fadeOut('slow');  	
            }
        });
    }else
    {
        toastr.error('File size should be less than 2MB');
    }
        });
        $("#button_add_color").click(function(){
        $('#dvLoading').fadeIn('slow');
        $.ajax({
            type: 'post',
            url : SIDE_URL+'user/set_background_color',
            data : {color :$('#wheel-demo').val()},
            async : false,
            success: function(responseData) {
                $('#bg_thumbnal').css('background',$('#wheel-demo').val());
                var responseData = jQuery.parseJSON(responseData);
                $('#dvLoading').fadeOut('slow');  	
            },
            error: function(responseData){
                console.log('Ajax request not recieved!');
                $('#dvLoading').fadeOut('slow');  	
            }
        });
        });
         $("#set_default_background").click(function(){
        $('#dvLoading').fadeIn('slow');
        $.ajax({
            type: 'post',
            url : SIDE_URL+'user/set_default_background',
            async : false,
            success: function(responseData) {
                $('#bg_thumbnal').css('background','');
                var responseData = jQuery.parseJSON(responseData);
                $('#dvLoading').fadeOut('slow');  	
            },
            error: function(responseData){
                console.log('Ajax request not recieved!');
                $('#dvLoading').fadeOut('slow');  	
            }
        });
        });
        
        
        
         if($('#MON_closed').is(":checked")){
            $("#MON_hours").removeAttr('disabled','disabled');
            $("#MON_hours_min").removeAttr('disabled','disabled');
       	}
		$('#MON_closed').click(function(){
            if($(this).prop("checked") == true){
                $("#MON_hours").removeAttr('disabled','disabled');
                $("#MON_hours_min").removeAttr('disabled','disabled');
            }
            else if($(this).prop("checked") == false){
                $("#MON_hours").attr('disabled','disabled');
                $("#MON_hours_min").attr('disabled','disabled');
            }
        });
        
        if($('#TUE_closed').is(":checked")){
            $("#TUE_hours").removeAttr('disabled','disabled');
            $("#TUE_hours_min").removeAttr('disabled','disabled');
       	}
		$('#TUE_closed').click(function(){
            if($(this).prop("checked") == true){
                $("#TUE_hours").removeAttr('disabled','disabled');
                $("#TUE_hours_min").removeAttr('disabled','disabled');
            }
            else if($(this).prop("checked") == false){
                $("#TUE_hours").attr('disabled','disabled');
                $("#TUE_hours_min").attr('disabled','disabled');
            }
        });
        
        if($('#WED_closed').is(":checked")){
            $("#WED_hours").removeAttr('disabled','disabled');
            $("#WED_hours_min").removeAttr('disabled','disabled');
       	}
		$('#WED_closed').click(function(){
            if($(this).prop("checked") == true){
                $("#WED_hours").removeAttr('disabled','disabled');
                $("#WED_hours_min").removeAttr('disabled','disabled');
            }
            else if($(this).prop("checked") == false){
                $("#WED_hours").attr('disabled','disabled');
                $("#WED_hours_min").attr('disabled','disabled');
            }
        });
        
        if($('#THU_closed').is(":checked")){
            $("#THU_hours").removeAttr('disabled','disabled');
            $("#THU_hours_min").removeAttr('disabled','disabled');
       	}
		$('#THU_closed').click(function(){
            if($(this).prop("checked") == true){
                $("#THU_hours").removeAttr('disabled','disabled');
                $("#THU_hours_min").removeAttr('disabled','disabled');
            }
            else if($(this).prop("checked") == false){
                $("#THU_hours").attr('disabled','disabled');
                $("#THU_hours_min").attr('disabled','disabled');
            }
        });
        
        if($('#FRI_closed').is(":checked")){
            $("#FRI_hours").removeAttr('disabled','disabled');
            $("#FRI_hours_min").removeAttr('disabled','disabled');
       	}
		$('#FRI_closed').click(function(){
            if($(this).prop("checked") == true){
                $("#FRI_hours").removeAttr('disabled','disabled');
                $("#FRI_hours_min").removeAttr('disabled','disabled');
            }
            else if($(this).prop("checked") == false){
                $("#FRI_hours").attr('disabled','disabled');
                $("#FRI_hours_min").attr('disabled','disabled');
            }
        });
        
        if($('#SAT_closed').is(":checked")){
            $("#SAT_hours").removeAttr('disabled','disabled');
            $("#SAT_hours_min").removeAttr('disabled','disabled');
       	}
		$('#SAT_closed').click(function(){
            if($(this).prop("checked") == true){
                $("#SAT_hours").removeAttr('disabled','disabled');
                $("#SAT_hours_min").removeAttr('disabled','disabled');
            }
            else if($(this).prop("checked") == false){
                $("#SAT_hours").attr('disabled','disabled');
                $("#SAT_hours_min").attr('disabled','disabled');
            }
        });
        
        if($('#SUN_closed').is(":checked")){
            $("#SUN_hours").removeAttr('disabled','disabled');
            $("#SUN_hours_min").removeAttr('disabled','disabled');
       	}
		$('#SUN_closed').click(function(){
            if($(this).prop("checked") == true){
                $("#SUN_hours").removeAttr('disabled','disabled');
                $("#SUN_hours_min").removeAttr('disabled','disabled');
            }
            else if($(this).prop("checked") == false){
                $("#SUN_hours").attr('disabled','disabled');
                $("#SUN_hours_min").attr('disabled','disabled');
            }
        });
        
        
        $(".time-text").blur(function(){
			var id = $(this).attr('id');
			var val = $(this).val();
			var val_clone = val;
			var is_edited1 = 1;
			$("#"+id+"_loading").show();
	    	var name = $(this).attr('name');
	    	
			if(val){
				if(is_edited1=='1'){
					
					if(validate(val) == true ){
						var splitval = val.split(":");
						var splitval_clone = val.split(":");
						
						if(splitval.length==2){
							var h = splitval[0];
							var m = splitval[1];
							if(m >= 60){
								var mm1 = parseInt(m / 60);
								var mm2 = m % 60;
		
								var hh = +h + +mm1;
								var mm = mm2;
		
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								
								var mmm = parseInt(hh*60)+parseInt(mm);
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
		
							}else{
								var hh = h;
								var mm = m;
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								var mmm = parseInt(hh*60)+parseInt(mm);
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
							}
						}
						
						if(val.length>=1 && val.length <=2)
						{
							if(val >= 60){
								var hh = parseInt(val / 60);
								var mm = val % 60;
		
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								var mmm = parseInt(hh*60)+parseInt(mm);
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
							}else{
								var mm = val;
								var time = mm + "m";
								var mmm = mm;
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
							}
						}
		
						if(val.length==3 && splitval.length!=2)
						{
							var digits = new Array();
							var digits= (""+val).split("");
							if((digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])>=60)
							{
								var additional = 1;
								var sum = [];
								var mm =  (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)])-60;
								var hh = +digits[val.length-val.length]+ +additional;
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								
								var mmm = parseInt(hh*60)+parseInt(mm);
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
		
							}else{
								var mm = (digits[val.length-(val.length-1)]+digits[val.length-(val.length-2)]);
								var hh = digits[val.length-val.length];
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								
								var mmm = parseInt(hh*60)+parseInt(mm);
								
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
							}
						}
		
						if(val.length==4 && splitval.length!=2)
						{
							var digits = new Array();
							var digits= (""+val).split("");
							if((digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])>=60)
							{
								var additional = 1;
								var sum = [];
								var mm =  (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)])-60;
								var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)])+ +additional;
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								var mmm = parseInt(hh*60)+parseInt(mm);
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
		
							}else{
		
								var mm = (digits[val.length-(val.length-2)]+digits[val.length-(val.length-3)]);
								var hh = +(digits[val.length-val.length]+digits[val.length-(val.length-1)]);
								if(hh==0){
									var time = mm+"m";
								}else if(mm==0){
									var time = hh+"h";
								}else{
									var time = hh + "h "+ mm+"m";
								}
								var mmm = parseInt(hh*60)+parseInt(mm);
								$("#"+id).val(time);
								$("#"+id+"_min").val(mmm);
							}
						}
						if(val.length>=5 && splitval.length!=2){
							$("#"+id).val('');
							$("#"+id+"_min").val('0');
							alertify.alert('maximum 4 digits allowed');
							$("#"+id+"_loading").hide();
							return false;
						}
					}else{
						 if($("#"+id+"_min").val() == get_minutes(val)){
					
						} else {
							$("#"+id).val('');
							$("#"+id+"_min").val('0');
							alertify.alert('your inserted value is not correct, please insert correct value');
							$("#"+id+"_loading").hide();
							return false;
						}
					}
				}
			}else{
				
				
				$("#"+id).val('');
				$("#"+id+"_min").val('0');
				alertify.alert("Please insert value greater than 0",function(){
					$("#"+id).focus();
					$("#"+id+"_loading").hide();
				});
				return false;
			}
			$("#"+id+"_loading").show();
			$.ajax({
				type : 'post',
				url : SIDE_URL+'user/save_user_calendar_settings',
				async : false,
				data : {name:name, val : $("#"+name+"_min").val()},
				success : function(){
					$("#"+id+"_loading").hide();
				}
			});		
		});
		
		
	$(".mysetting-cal-chkbox").click(function(){
			var name = $(this).attr('name');
			var id = $(this).attr('id');
			var other_name = name.replace('closed', '')+"hours";
			if($("#"+name).is(":checked")){
				var val = "1";
                                $("#"+other_name).val('8h');
                                $("#"+other_name+"_min").val('480');
			} else {
				var val = "0";
			}
                        var other_val = $("#"+other_name+"_min").val();
                        if(val == "1"){
                                if(other_val != "0"){
                                        $.ajax({
                                            type : 'post',
                                            url : SIDE_URL+'user/save_user_calendar_settings',
                                            async : false,
                                            data : {name:other_name, val : '480'},
                                            success : function(){

                                            }
                                        });
                                        $.ajax({
                                                type : 'post',
                                                url : SIDE_URL+'user/save_user_calendar_settings',
                                                async : false,
                                                data : {name:name, val : val},
                                                success : function(){

                                                }
                                        });
				} else {
					alertify.confirm("Please add more than 0 minutes for equivalent field of this day.",function(r){
						if(r == true){
							$("#"+other_name).val('');
							$("#"+other_name).focus();
							$.ajax({
								type : 'post',
								url : SIDE_URL+'user/save_user_calendar_settings',
								async : false,
								data : {name:name, val : val},
								success : function(){
									
								}
							});
						} else {
							$("#"+name).closest("span").removeClass("checked");
							$("#"+name).prop('checked', false);
							$("#"+other_name).attr("disabled",true);
							$("#"+other_name+"_min").attr("disabled",true);
							return false;
						}
					});
				}
			} else {
				$("#"+other_name).val('0m');
				$("#"+other_name+"_min").val('0');
				$.ajax({
					type : 'post',
					url : SIDE_URL+'user/save_user_calendar_settings',
					async : false,
					data : {name:other_name, val : '0'},
					success : function(){
						
					}
				});
				$.ajax({
					type : 'post',
					url : SIDE_URL+'user/save_user_calendar_settings',
					async : false,
					data : {name:name, val : val},
					success : function(){
						
					}
				});
			}
		});
		
	$(".color-select").change(function(){
			var id = $(this).attr('id');
			$("#"+id+"_loading").show();
			var name = $(this).attr('name');
			var value = $(this).val();
			if(value.trim()){
				$.ajax({
					type : 'post',
					url : SIDE_URL+'user/mysetting_index',
					data : {name:name, value:value},
					async:false,
					success : function(data){
						$("#"+id+"_loading").hide();
						$("#old_"+id).val(value);
						alertify.set('notifier','position', 'top-right');
						alertify.log("Default color has been changed successfully.");
					}
				});
			}
		});
        
        $("#swimlanes_name").on("keypress", function(e){
		 if( e.keyCode === 13 ) {
			e.preventDefault();
			$("#save_swimlanes").trigger('click');
		}
	 });
    
        $("#swimlanes_name").blur(function(){
            $("#save_swimlanes").trigger('click');
        });
        
	$("#save_swimlanes").click(function(){
    	var swimlanes_name = $("#swimlanes_name").val();
    	if(swimlanes_name == ''){
    		$("#alertify").show();
    		alertify.alert('Please enter swimlanes name.');
    		return false;
    	} else {
    		$.ajax({
				type : 'post',
				url : SIDE_URL+"user/chk_swimlaneName_exists",
				data : {name: swimlanes_name, swimlanes_id : ''},
				async : false,
				success : function(data){
					if(data == "false"){
						$("#alertify").show();
                                                alertify.alert("There is an existing record with this swimlane name.", function (e) {
						$("#swimlanes_name").focus();
						$("#alertify").hide();$("#alertify-cover").css("position","relative");
                                                    return false;
						});
						$('#dvLoading').fadeOut('slow');
	    				return false;
					} else {
    		
			    		$('#dvLoading').fadeIn('slow');
			    		$.ajax({
				            type: 'post',
				            url : SIDE_URL+"user/addSwimlanes",
				            data: {swimlanes_name : swimlanes_name, swimlanes_status : 'Active'},
				            success: function(responseData) {
				            	var responseData = jQuery.parseJSON(responseData);
				            	var html = '<tr id="swimlane_'+responseData.swimlanes_id+'">';
                                                    html +='<td width="3%" style="cursor:pointer;"><i class="fa fa-bars" aria-hidden="true"></i></td>';
                                                    html += '<td width="50%"><a href="javascript:void(0)" class="txt-style" id="sname_'+responseData.swimlanes_id+'" data-type="text" data-pk="1" data-original-title="'+responseData.swimlanes_name+'">'+responseData.swimlanes_name+'</a></td>';
                                                    html += '<td width="22%">';
                                                    html += '<a onclick="delete_swimlane(\''+responseData.swimlanes_id+'\');" href="javascript:void(0)"> <i class="icon-trash stngicn new_swimlane_css"></i></a></td>';
                                                    html += '<td><input type="checkbox" id="swimlane_status_'+responseData.swimlanes_id+'" checked data-toggle="toggle" data-width="80" data-size="small" data-offstyle="danger" data-on="Active" data-off="Inactive" ></td>';
                                                    html +='</tr>';
				            	
						 var total = $("#total_swimlanes").val();
                                                $("#total_swimlanes").val(Number(total)+ Number(1));		
				            	$("#swimlanes").find('tr:last').after(html);
						if($("#swimlanes tr.empty").length){
                                                    $("#swimlanes tr.empty").remove();
				            	}
                                               
                                                var view = '<option value="'+responseData.swimlanes_id+'" >'+responseData.swimlanes_name+'</option>';
                                                $("#default_swimlane").append(view);
				            	$('#sname_'+responseData.swimlanes_id).editable({
				            		url: SIDE_URL+"user/update_swimlane_name",
						            type: 'post',
						            pk: 1,
						            mode: 'inline',
						            showbuttons: true,
						            validate: function (value) {
						            	
						              	if ($.trim(value) == ''){ return 'This field is required';};
						              	var remote = $.ajax({
						              		url: SIDE_URL+"user/chk_swimlaneName_exists",
											type: "post",
											async : false,
											data: {
												name: $.trim(value),
												user_id: function(){ return $("#user_id").val(); },
												swimlanes_id : responseData.swimlanes_id
											},
											success : function(responseData){
												return responseData;
											}
						              	});
						              	if(remote.responseText == "false") return 'There is an existing record with this swimlanes name.';
						            },
						            success : function(DivisionData){
						            	
						            }
				            	});
				            	
                                                $("#swimlane_status_"+responseData.swimlanes_id).bootstrapToggle();
						$('#swimlane_status_'+responseData.swimlanes_id).bootstrapToggle().on('change', function () {
                                                    var t = $(this).prop('checked')? "active" : "deactive";
                                                    change_swimlane_status(responseData.swimlanes_id,t);
                                                });
				            	
				            	$("#swimlanes_name").val('');
				            	$("#swimlanes_name").blur(function(){$("#alertify").hide();$("#alertify-cover").css("position","relative");});
				            	
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
    	}
    	
    });
    /* add on change event for checking ('old_password','password','confirm_password") field for update password */
        
        $("#clear_password_fields").click(function(){
            $("#old_password").val('');
            $("#password").val('');
            $("#confirm_password").val('');
            $("#old_password-error").remove();
            $("#password-error").remove();
            $("#confirm_password-error").remove();
        });
        
        $(".show_old_pass").click(function () {
            if ($("#old_password").attr("type")=="password") {
                $("#old_password").attr("type", "text");
            }
            else{
                $("#old_password").attr("type", "password");
            }
        });
        $(".display_password").click(function(){
            if ($("#password").attr("type")=="password") {
                $("#password").attr("type", "text");
            }
            else{
                $("#password").attr("type", "password");
            }
        });
        
        $.validator.addMethod("loginRegex", function(value, element) {
	        return this.optional(element) || /^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9/\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/]{8,16}$/.test(value);
	    }, "Provide atleast 1 Number, 1 Alphabet and between 8 to 16 characters.");
		    
        $.validator.addMethod("password_check", function(value, element) {
	    var check_old_password =  $.ajax({
                                            type : 'post',
                                            url : SIDE_URL+"user/is_password_correct",
                                            data : {value :value},
                                            async : false,
                                            success : function(data){
                                              return data;    
                                            }
                                        });
              if(check_old_password.responseText == 1){ 
                  return true;
              }else{
                  return false;
              }       
	    }, " is not correct, Please try again..!");
		    
        $('#frm_change_password').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
		old_password : {
               	    required : true,
	            //rangelength: [8, 16],
                    password_check:true
                },
                password : {
               	    required : true,
                    loginRegex: true,
	            rangelength: [8, 16]
                },
                confirm_password : {
                    required: true,
                    equalTo:'#password'
                }
            },
            errorPlacement: function (error, element) {
		error.insertBefore(element);
            },
	    submitHandler: function (form) {
                 $("#dvLoading").fadeIn("slow"),
                  $.ajax({
		    	type : 'post',
		    	url : SIDE_URL+'user/update_password',
		    	data : {value:$("#password").val()},
		    	success : function(data){ 
		    		if(data=="updated"){
                                    $("#alertify").show();
                                    toastr['success']("Password changed successfully.", "");
                                    $("#password").val("");
                                    $("#confirm_password").val("");
                                    $("#old_password").val("");
                                }
				$("#dvLoading").fadeOut("slow");		
		    	},
                        error:function(data){
                            $("#dvLoading").fadeOut("slow");
                        }
		 });	
            }
           
        });
        
        if(company_division.length >'2'){
			$('#divhide').hide();
		}else{
            $('#divhide').show();
	}
		
	if(company_department.length < '3' && $("#tags_division").val() == ""){
			$('#dephide').hide();
		}else{
            $('#dephide').show();
	}
});
	/**
         * This function delete swimlane form list using user class function on ajax request.
         * @param id swimlane_id
         * @returns void
         */
	function delete_swimlane(id){
		var ans = "Are you sure, you want to delete swimlane?";
		alertify.confirm(ans,function(r){
			if(r == true){
				$('#dvLoading').fadeIn('slow');
				$.ajax({
					type:'post',
					url : SIDE_URL+"user/deleteSwimlane",
					data : {id:id},
					success : function(data){
						$("#swimlane_"+id).remove();
                                                if($("#swimlanes tr").length < 2){
                                                    $("#swimlanes tr:first").before('<tr class="empty"><td colspan="2">No Records Available.</td></tr>');
                                                }
                                                $('#default_swimlane option[value='+id+']').remove();
				            	var total = $("#total_swimlanes").val();
                                                $("#total_swimlanes").val(Number(total)-Number(1));
                                                $('#dvLoading').fadeOut('slow');
					}
				});
			} else {
				return false;
			}
		});
	}
	
	
	function changeColorStatus(id,val){
		$('#dvLoading').fadeIn('slow');
		if(val == true){
			var value = 'Active';
		} else {
			var value = 'Inactive';
		}
		$.ajax({
			type : 'post',
			url : SIDE_URL+"user/updateColorStatus",
			data : {id:id, val : value},
			async:false,
			success: function(){
				$('#dvLoading').fadeOut('slow');
			}
		});
		$.ajax({
			type : 'post',
			url : SIDE_URL+"user/default_color",
			async:false,
			success: function(data){
				$('#default_color').html(data);
				$("#"+id+"_loading").hide();
			}
		});
	}
        /**
         * This function set division in user division list through setDivisionSession.
         * @returns void
         */
	function openCompanyDivisionTab(){
		$.ajax({
			type : 'post',
			url : SIDE_URL+"user/setDivisionSession",
			async : false,
			success : function(){
				window.open(SIDE_URL+'settings/index', '_blank');
			}
		});
	}

        $(document).ready(function(){
            $("#gmail_sync").bootstrapToggle();
            $('#gmail_sync').bootstrapToggle().on('change', function (e, data) {
                var value =$(this).prop('checked')?1:0;
                $.ajax({
			type : 'post',
			url : SIDE_URL+"user/gmail_sync",
			data:{
                          status:value  
                        },
			success : function(){
                            if(value == "1"){
                                window.open(gmail_auth_url,'_self');
                            }
                        }
		});
            });
       });
        $(document).ready(function(){
        $.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
		    }, "Please enter only letters.");
        $.validator.addMethod("UserExist", function(value, element) {
                    var remote =  $.ajax({
              		url: SIDE_URL+"user/is_email_exists",
			type: "post",
			async : false,
			data: {
                            value: value
			},
			success : function(responseData){
                           return responseData;
			}
                    });
                    if(remote.responseText == '1'){
                        return false;
                    }else{
                        return true;
                    }
            },"There is an existing account associated with this email.");
        
        $.validator.addMethod("phoneno", function(phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 && 
            phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
        }, "Please specify a valid phone number");
		    
        
        $("#user_info_setting").validate({ 
            errorElement: "span",
            errorClass: "help-inline",
            focusInvalid: false,
            ignore: "",
            rules: {
                first_name:{
                    required:true,
                    alpha:true,
                    maxlength:25
                },
                last_name: {
                    required: true,
                    alpha: true,
                    maxlength: 25
                },
                email: {
                    required: true,
                    email: true,
                    UserExist:true
                },
                mobile:{
                    phoneno:true
                },
                user_default_page:{
                    required:true
                },
                user_timezone:{
                    required:true
                }
            },
            message:{
                    first_name:{
                         required:"This field is required"
                     },
                    last_name:{
                         required:"This field is required"
                     },
                    email:{
                         required:"This field is required",
                         email:"Please enter a valid email address"
                    }

            },
            submitHandler: function(data){
                  $("#dvLoading").fadeIn("slow"),
                  $.ajax({
                    type: "post",
                    url: SIDE_URL + "user/update_user_info",
                    data: {
                        info:$("#user_info_setting").serialize()
                    },
                    success: function(e) {
                        $("#dvLoading").fadeOut("slow");
                        toastr['success']("Settings saved successfully.", "");
                    },
                    error:function(e){
                        $("#dvLoading").fadeOut("slow");
                    }
                });
            }
        });
});

        function change_swimlane_status(swimlane_id,status){ 
            var swimlanes = $("#total_swimlanes").val();
            if(status == 'active' || swimlanes > '1'){
                $.ajax({
                        type : 'post',
                        url : SIDE_URL+"user/change_swimlane_status",
                        data:{
                            swimlane_id:swimlane_id,
                            status:status
                        },
                        success : function(a){
                           $("#total_swimlanes").val(a);
                           if(status == 'deactive'){
                               $('#default_swimlane option[value='+swimlane_id+']').css('display','none');
                           }else{
                               $('#default_swimlane option[value='+swimlane_id+']').css('display','block');
                           }
                        }
                });
            }else{
                $("#swimlane_status_"+swimlane_id).parent().removeClass("off");
                alertify.error("You must have at least one active swimlane.");
            }
        }
        
        
        $(document).ready(function(){
            $(document).on("change","#default_swimlane",function(){
                var default_swimlane = $(this).val();
                var old_swimlane = $("#hidden_default_swimlane").val();
                $.ajax({
			type : 'post',
			url : SIDE_URL+"user/set_default_swimlane",
			data:{
                          old_default_swimlane : old_swimlane,  
                          new_default_swimlane :default_swimlane  
                        },
			success : function(data){ 
                            var responseData1 = jQuery.parseJSON(data);
                            var show= '';
                            if(responseData1.swimlane_status == 'active'){
                                show = "checked='checked'";
                            }else{
                                show = '';
                            }
                            var view = '<input type="checkbox" id="swimlane_status_'+responseData1.swimlanes_id+'"  '+show+'  data-toggle="toggle" data-width="80"  data-offstyle="danger" data-on="Active" data-off="Inactive"  >';
                            $("#swimlane_status").replaceWith(view);
                            $("#swimlane_status_"+default_swimlane).parent().replaceWith('<input type="checkbox" id="swimlane_status" style="display:none">');
                            $("#delete_icon_show_"+default_swimlane).css("display",'none');
                            $("#delete_icon_show_"+old_swimlane).css("display",'block');
                            $("#swimlane_status_"+responseData1.swimlanes_id).bootstrapToggle();
                            $('#swimlane_status_'+responseData1.swimlanes_id).bootstrapToggle().on('change', function () {
                            var t = $(this).prop('checked')? "active" : "deactive";
                                change_swimlane_status(responseData1.swimlanes_id,t);
                            });
                            
                            $("#hidden_default_swimlane").val(default_swimlane);
                            alertify.set('notifier','position', 'top-right');
                            alertify.log("Default swimlane has been changed successfully.");
			}
		});
            });
            $('#submit').click(function(){
                $('#user_info_setting').submit();
            });
        });
        function change_background_toggle()
        {
            $('#change_background').toggle();
        }