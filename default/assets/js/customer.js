function returnCustomerList(){
    window.location.href = SIDE_URL+"customer/index";
}    
function deactivate(customer_id){
      var s = "Are you sure, you want to deactivate this customer?";
    $('#deactive').confirmation('show').on('confirmed.bs.confirmation',function(){
                               $("#dvLoading").fadeIn("slow");
                               void $.ajax({
            type: "post",
            url: SIDE_URL + "customer/deactivateCustomer",
            data: {
                customer_id: customer_id,
                status:'inactive'
            },
            success: function(a) { 
                var link = '<a href="javascript:void(0)" id="active" onclick="activate(\''+customer_id+'\');" class="btn white txtbold pull-right margin5 btn-new" style="line-height: 16px !important;border: 1px solid #e5e5e5 !important;"> Activate</a>';
                $("#deactive").replaceWith(link);
               // window.location.href = SIDE_URL+"customer/index";
                $("#dvLoading").fadeOut("slow")
            },
            error:function(a){
                 console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
            }
        });
    });
    
    
}
function activate(customer_id){
      var s = "Are you sure, you want to activate this customer?";
    $('#active').confirmation('show').on('confirmed.bs.confirmation',function(){
                               $("#dvLoading").fadeIn("slow");void $.ajax({
            type: "post",
            url: SIDE_URL + "customer/deactivateCustomer",
            data: {
                customer_id: customer_id,
                status:'active'
            },
            success: function(a) { 
               var link = '<a href="javascript:void(0)" id="deactive" onclick="deactivate(\''+customer_id+'\');" class="btn white txtbold pull-right margin5 btn-new" style="line-height: 16px !important;border: 1px solid #e5e5e5 !important;"> Deactivate</a>';
                $("#active").replaceWith(link);
                $("#dvLoading").fadeOut("slow")
            },
            error:function(a){
                 console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
            }
        });
    });
    
    
}
function removeCustomer(customer_id){
    $('#delete_customer_'+customer_id).confirmation('show').on('confirmed.bs.confirmation',function(){
        void $.ajax({
            type: "post",
            url: SIDE_URL + "customer/removeCustomer",
            data: {
                customer_id: customer_id
            },
            success: function(a) { 
                window.location.href = SIDE_URL+"customer/index";
            },
            error:function(a){
                 console.log("Ajax request not recieved!")
            }
        });
    });
}
    
function expand_data(id){
    if(id=='1'){
        var b = document.getElementById("projectData_" + id);
         "none" !== b.style.display ? (b.style.display = "none", $("#expand_1").html(' <i class="icon-plus" style="color: #fff !important;"></i>')) : (b.style.display = "block", $("#expand_1").html(' <i class="icon-minus" style="color: #fff !important;"></i>'))
    }else{
        var b = document.getElementById("taskData_" + id);
         "none" !== b.style.display ? (b.style.display = "none", $("#expand_2").html(' <i class="icon-plus" style="color: #fff !important;"></i>')) : (b.style.display = "block", $("#expand_2").html(' <i class="icon-minus" style="color: #fff !important;"></i>'))
    }
   
}
function deleteTask(a){
       
        var pos = a.indexOf("child");
        //alert(pos)
        if (pos == '0') {
                $("#deleteTask").modal("show");
                $("#id").val(a);
                 
        }else{
            
                    var s = "Are you sure, you want to delete this task?";
                $('#delete_task_'+a).confirmation('show').on('confirmed.bs.confirmation',function(){
                               $("#dvLoading").fadeIn("slow");
                               void $.ajax({
                        type: "post",
                        url: SIDE_URL + "task/delete_task",
                        data: {
                            task_id:a,
                            post_data: $("#task_data_" + a).val(),
                        },
                        success: function(data) { 
                            $("#listtask_" + a).remove()
                            $("#dvLoading").fadeOut("slow")
                        },
                        error:function(data){
                             console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                        }
                    });
                });
            }
    }

function deleteData(){
    $("#deleteTask").modal("hide");
    var from=$("input[name='delete_option']:checked").val();
    var occ_id=$("#id").val();
    var ser_id = occ_id.substring(6, 10);
    if(from=='series'){
                    $.ajax({
                                        type: "post",
                                        url: SIDE_URL + "task/delete_task",
                                        data: {
                                            task_id:ser_id,
                                            post_data: $("#task_data_" + ser_id).val(),
                                            from:from
                                        },
                                        success: function(data) { 
                                            $("#listtask_" + occ_id).remove()
                                            $("#dvLoading").fadeOut("slow")
                                        },
                                        error:function(data){
                                             console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                                        }
                                    })
       }
}


function editCustomer(e){ 
                      
                        $("#customer_name").val($("#hide_customer_name_"+e).val()),
                        $("#customer_external_id").val($("#hide_external_id_"+e).val()),
                        $("#first_name").val($("#hide_first_name_"+e).val()),
                        $("#last_name").val($("#hide_last_name_"+e).val()),
                        $("#email").val($("#hide_email_"+e).val()),
                        $("#phone").val($("#hide_phone_"+e).val()),
                        $('#internal_owner option[value='+$("#hide_owner_id_"+e).val()+']').attr("selected", "selected"),
                        $("#customerid").val(e),
                        $("#customer_modal_title").text("Edit Customer"),
                        $("#customer_update").show(), 
                        $("#customer_save").hide();
                        $.ajax({
                            type: "post",
                            url: SIDE_URL + "customer/get_all_active_customer",
                            success: function(a) { 
                                var data = jQuery.parseJSON(a); 
                                var view = '';
                                view +='<option value="0">Please Select</option>';
                                if(data.customers!= '0' ){ 
                                    $.each( data.customers, function( i, value ) {
                                        if(data.customers[i].customer_id != e){
                                            view +='<option value="'+data.customers[i].customer_id+'">'+data.customers[i].customer_name+'</option>';
                                        }
                                    });
                                }
                                $("#parent_customer_id").html(view);
                                $('#parent_customer_id option[value='+$("#hide_parent_customer_id_"+e).val()+']').attr("selected", "selected");
                                $("#newcustomer").modal("show");  
                            },
                            error:function(a){
                                 console.log("Ajax request not recieved!");
                            }
                        });
    }
      
function deleteCustomer(e){ 
        var s = "Are you sure, you want to delete this customer?";
    $('#delete_customer_'+e).confirmation('show').on('confirmed.bs.confirmation',function(){
                               void $.ajax({
            type: "post",
            url: SIDE_URL + "customer/deleteCustomer",
            data: {
                customer_id: e
            },
            success: function(a) { 
                var data = jQuery.parseJSON(a); 
                $("#listCustomer_" + e).remove();
                toastr['success']("Customer "+data.customer_name+" deleted successfully", "");
            },
            error:function(a){
                console.log("Ajax request not recieved!");
            }
        });
    });
}
    
function callCustomer(customer_id){ 
    document.getElementById("myForm_"+customer_id).submit();
}
   
function getcustomerData(a){ 
    $("#dvLoading").fadeIn("slow")
            $.ajax({
                   type: "post",
                   url: SIDE_URL + "customer/getMoreCustomer",
                   data: {
                       page:a,
                       search:$("#customer_search").val()
                    },
                   success: function(data) {  
                        $("#paging").html(data);
                        $("#pagination li").removeClass('active');
                        $("#"+a).addClass('active');
                        
                        $("#dvLoading").fadeOut("slow")
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                   }
         
     })
        
        
}

 function getprojectdata(a){
      $("#dvLoading").fadeIn("slow")
            $.ajax({
                   type: "post",
                   url: SIDE_URL + "customer/getMoreProject",
                   data: {
                       page:a,
                       customer_id:$("#hide_customer_id").val()
                    },
                   success: function(data) {  
                        $("#page_project").html(data);
                        $("#pagination_project li").removeClass('active');
                        $("#project_"+a).addClass('active');
                        
                        $("#dvLoading").fadeOut("slow")
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                   }
         
     })
        
 }
 
 function gettaskdata(a){
      $("#dvLoading").fadeIn("slow")
            $.ajax({
                   type: "post",
                   url: SIDE_URL + "customer/getMoreTask",
                   data: {
                       page:a,
                       customer_id:$("#hide_customer_id").val(),
                       status_id:$("#customer_task_status_id").val(),
                       owner_id:$("#owner_id").val(),
                       allocated_id:$("#allocated_id").val()
                    },
                   success: function(data) {  
                        $("#task_data").html(data);
                        $("#pagination_task li").removeClass('active');
                        $("#task_"+a).addClass('active');
                        $("#dvLoading").fadeOut("slow")
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                   }
         
     })
        
 }
 
 function filter(){
     $("#dvLoading").fadeIn("slow")
                $.ajax({
                        type: "post",
                        url: SIDE_URL + "customer/gettaskByFilter",
                        data: {
                            status_id:$("#customer_task_status_id").val(),
                            owner_id:$("#owner_id").val(),
                            allocated_id:$("#allocated_id").val(),
                            customer_id:$("#hide_customer_id").val()
                         },
                        success: function(data) {
                            $("#task_page").html(data);
                            $("#dvLoading").fadeOut("slow")
                        },
                        error:function(data){
                            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                        }
                        });
     
 }
 
 function reset(){
     $("#dvLoading").fadeIn("slow")
                $.ajax({
                        type: "post",
                        url: SIDE_URL + "customer/gettaskByFilter",
                        data: {
                            status_id:'0',
                            owner_id:'0',
                            allocated_id:'0',
                            customer_id:$("#hide_customer_id").val()
                         },
                        success: function(data) {
                            $("#customer_task_status_id").val('0');
                            $("#owner_id").val('0');
                            $("#allocated_id").val('0');
                            $("#task_search").val('');
                            $("#task_page").html(data);
                            $("#dvLoading").fadeOut("slow")
                        },
                        error:function(data){
                            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                        }
                        });
 }
 
 
 function callMyproject(project_id){
      document.getElementById("myProject_"+project_id).submit();
      
 }
 
$(document).ready(function(){
        $("#close_customer_div").on("click",function(){
            $("#newcustomer").modal("hide");
            
        });
        
        $.validator.addMethod("alpha", function(value, element) {
		        return this.optional(element) || /^[a-zA-Z\s_@./#&!$;,:'"]+$/.test(value);
		    }, "Please enter only letters & special characters.");

        $.validator.addMethod("phoneno", function(phone_number, element) {
         phone_number = phone_number.replace(/\s+/g, "");
         return this.optional(element) || phone_number.length > 9 && 
         phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
     }, "<br />Please specify a valid phone number");
		    
        
        $("#customer_data").validate({ 
        errorElement: "span",
        errorClass: "help-inline",
        focusInvalid: false,
        ignore: "",
        rules: {
            customer_name:{
                required:true,
                maxlength:100
                
            },
            first_name: {
                //required: true,
                alpha: true,
                maxlength: 25
            },
            last_name: {
                //required: true,
                alpha: true,
                maxlength: 25
            },
            email: {
                //required: true,
                email: true
             },
            phone:{
                //required:true,
                //maxlength:12,
                phoneno:true
            },
            customer_external_id:{
                //maxlength:6,
                
            }
             },
        message:{
                customer_name:{
                    required:"This field is required"
                   
                },
                first_name:{
                   //  required:"This field is required"
                 },
                last_name:{
                    // required:"This field is required"
                 },
                email:{
                   //  required:"This field is required",
                     email:"Please enter a valid email address"
                 },
                phone:{
                    //  required:"This field is required"
                 }
                 
              },
         submitHandler: function(){
                $("#dvLoading").fadeIn("slow");
                $.ajax({
                        type: "post",
                        url: SIDE_URL + "customer/saveCustomer",
                        data: {
                            customer_data: $("#customer_data").serialize()
                        },
                        success: function(data) {
                            if($("#active_menu").val()=='from_customer'){
                                var e = jQuery.parseJSON(data); 
                                var row="";
                                        row = '<div id="customer_info"><input type="hidden" name="hide_customer_name_'+ e.customer.customer_id +'" id="hide_customer_name_'+ e.customer.customer_id + '" value="'+ e.customer.customer_name  +'"/>',
                                        row += '<input type="hidden" name="hide_external_id_'+ e.customer.customer_id +'"  id="hide_external_id_'+ e.customer.customer_id +'" value="'+ e.customer.external_id + '"/>',
                                        row += '<input type="hidden" name="hide_owner_id_'+ e.customer.customer_id +'" id="hide_owner_id_'+ e.customer.customer_id +'" value="'+ e.customer.owner_id +'"/>',
                                        row += '<input type="hidden" name="hide_first_name_'+ e.customer.customer_id +'" id="hide_first_name_'+ e.customer.customer_id +'" value="'+ e.customer.first_name+'"/>',
                                        row += '<input type="hidden" name="hide_last_name_'+ e.customer.customer_id +'" id="hide_last_name_'+ e.customer.customer_id +'" value="'+e.customer.last_name +'"/>',
                                        row += '<input type="hidden" name="hide_phone_'+ e.customer.customer_id +'" id="hide_phone_'+ e.customer.customer_id +'" value="'+ e.customer.phone +'"/>',
                                        row += '<input type="hidden" name="hide_email_'+ e.customer.customer_id +'"  id="hide_email_'+ e.customer.customer_id +'" value="'+ e.customer.email + '"/>',
                                        row += '<input type="hidden" name="hide_parent_customer_id_'+ e.customer.customer_id +'" id="hide_parent_customer_id_'+ e.customer.customer_id +'" value="'+ e.customer.parent_customer_id + '"/>',  
                                        row +='<div class="row cus_info" style="margin-left: -5px;">',
                                        row +='<div class="col-md-12 cus_heading">',
                                        row +='<div class="col-md-6">',
                                        row +='<h5 class="txtbold" style="text-transform: capitalize;font-size:18px;"><b>'+e.customer.customer_name+'</b> ',
                                        row +='<span style="font-size: 15px;">('+e.customer.customer_id+')</span></h5>',
                                        row +='</div>',
                                        row +='</div><div class="col-md-6" style="padding-top:10px;"></div></div>',
                                        row +='<div class="row" style="margin-left: -2px;">',
                                        row +='<div class="row">',
                                        row +='<div class="col-md-6">',
                                        row +='<div class="col-md-6">',
                                        row +='<label class="control-label txtbold"><b>External ID</b> </label>',
                                        row +='</div>',
                                        row +='<div class="col-md-6">';
                                        row +=e.customer.external_id;
                                        row +='</div>',
                                        row +='</div>',
                                        row +='<div class="col-md-6">',
                                        row +='<div class="col-md-6">',
                                        row +='<label class="control-label txtbold"><b>Contact</b> </label>',
                                        row +='</div>',
                                        row +='<div class="col-md-6"> ',
                                        row +=e.customer.first_name+' '+e.customer.last_name,
                                        row +='</div>',
                                        row +='</div>',
                                        row +='</div>',
                                        row +='<div class="row"> ',
                                        row +='<div class="col-md-6">',
                                        row +='<div class="col-md-6">',
                                        row +='<label class="control-label txtbold"><b>Owner</b> </label>',
                                        row +='</div>',
                                        row +='<div class="col-md-6">';
                                        if(e.customer.ownername==null){
                                        row +='';    
                                        }else{
                                        row +=e.customer.ownername;
                                        }
                                        row +='</div>',    
                                        row +='</div>',
                                        row +='<div class="col-md-6">',
                                        row +='<div class="col-md-6">',
                                        row +='<label class="control-label txtbold"><b>Phone</b> </label>',
                                        row +='</div>',
                                        row +='<div class="col-md-6">',
                                        row +=e.customer.phone,
                                        row +='</div>',   
                                        row +='</div>',
                                        row +='</div>',
                                        row +='<div class="row">',
                                        row +='<div class="col-md-6">',
                                        row +='<div class="col-md-6">',
                                        row +='<label class="control-label txtbold"><b>Parent customer</b> </label>',
                                        row +='</div>',
                                        row +='<div class="col-md-6">';
                                        if(e.customer.name==undefined ){
                                        row +='';    
                                        }else{
                                        row +=e.customer.name;
                                        }
                                        row +='</div>',    
                                        row +='</div>',
                                        row +='<div class="col-md-6">',
                                        row +='<div class="col-md-6">',
                                        row +='<label class="control-label txtbold"><b>Email</b> </label>',
                                        row +='</div>',
                                        row +='<div class="col-md-6">',
                                        row +=e.customer.email,
                                        row +='</div> ',
                                        row +='</div>',
                                        row +='</div>',
                                        row +='</div></div>';
                                $("#customer_info").replaceWith(row);
                                $("#newcustomer").modal("hide");
                              //console.log(row);
                                $("#dvLoading").fadeOut("slow") 
                            }else{
                                
                                    var e = jQuery.parseJSON(data);
                                   // console.log(e);
                                    var row = "";

                                    row = '<tr id="listCustomer_' + e.customer.customer_id + '">',
                                    row +='<td width="22%" onclick="callCustomer(\''+e.customer.customer_id+'\');" style="cursor:pointer;">',
                                    row += '<form method="POST"  action="'+ SIDE_URL +"customer/openCustomer"+'" name="myForm_'+e.customer.customer_id+'" id="myForm_'+e.customer.customer_id+'">',
                                    row += '<input type="hidden" name="cus_id" id="cus_id" value="'+e.customer.customer_id+'" /></form> '+ e.customer.customer_name  +'</td>', 
                                    row += '<td onclick="callCustomer(\''+e.customer.customer_id+'\');" style="cursor:pointer;">' + e.customer.external_id + '</td>';
                                    if(e.customer.owner_id=='0'){
                                        row += '<td onclick="callCustomer(\''+e.customer.customer_id+'\');" style="cursor:pointer;">-</td>';
                                    }
                                    else{
                                    row += '<td onclick="callCustomer(\''+e.customer.customer_id+'\');" style="cursor:pointer;">' + e.customer.ownername + "</td>";
                                        }
                                    row += '<td width="15%" onclick="callCustomer(\''+e.customer.customer_id+'\');" style="cursor:pointer;">' + e.customer.first_name+' '+e.customer.last_name + "</td>";
                                    row += '<td onclick="callCustomer(\''+e.customer.customer_id+'\');" style="cursor:pointer;">' + e.customer.phone + "</td>",
                                    row += '<td onclick="callCustomer(\''+e.customer.customer_id+'\');" style="cursor:pointer;">' + e.customer.email + "</td>",
                                    row += '<td >';
                                    if(e.customer_access == '0'){
                                        row += '<a href="javascript:void(0)" class="not_access"><i class="icon-pencil cstmricn" style="transform: scale(0.75);"></i> </a>',
                                        row += '<a href="javascript:void(0)" class="not_access"><i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>';   
                                    }else{
                                        row += '<a href="javascript:void(0)" onclick="editCustomer(\'' + e.customer.customer_id + '\')"><i class="icon-pencil cstmricn" style="transform: scale(0.75);"></i> </a>',
                                        row += "<a onclick=\"deleteCustomer('" + e.customer.customer_id + '\');" id="delete_customer_'+e.customer.customer_id+'" href="javascript:void(0)"><i class="icon-trash cstmricn" style="transform: scale(0.75);"></i> </a>'; 
                                    }   
                                    row += '<input type="hidden" name="hide_customer_name_'+ e.customer.customer_id +'" id="hide_customer_name_'+ e.customer.customer_id + '" value="'+ e.customer.customer_name  +'"/>',
                                    row += '<input type="hidden" name="hide_external_id_'+ e.customer.customer_id +'"  id="hide_external_id_'+ e.customer.customer_id +'" value="'+ e.customer.external_id + '"/>',
                                    row += '<input type="hidden" name="hide_owner_id_'+ e.customer.customer_id +'" id="hide_owner_id_'+ e.customer.customer_id +'" value="'+ e.customer.owner_id +'"/>',
                                    row += '<input type="hidden" name="hide_first_name_'+ e.customer.customer_id +'" id="hide_first_name_'+ e.customer.customer_id +'" value="'+ e.customer.first_name+'"/>',
                                    row += '<input type="hidden" name="hide_last_name_'+ e.customer.customer_id +'" id="hide_last_name_'+ e.customer.customer_id +'" value="'+e.customer.last_name +'"/>',
                                    row += '<input type="hidden" name="hide_phone_'+ e.customer.customer_id +'" id="hide_phone_'+ e.customer.customer_id +'" value="'+ e.customer.phone +'"/>',
                                    row += '<input type="hidden" name="hide_email_'+ e.customer.customer_id +'"  id="hide_email_'+ e.customer.customer_id +'" value="'+ e.customer.email + '"/>',
                                    row += '<input type="hidden" name="hide_parent_customer_id_'+ e.customer.customer_id +'" id="hide_parent_customer_id_'+ e.customer.customer_id +'" value="'+ e.customer.parent_customer_id + '"/>',
                                    row += "</td></tr>";

                                    //console.log(row);
                                    $(".dataTables_empty").length == '1'? $(".dataTables_empty").parent().remove():'';
                                    $("#listCustomer_" + e.customer.customer_id).length ? $("#listCustomer_" + e.customer.customer_id).replaceWith(row) : $("#customerlist").append(row);
                                    // $("#customerlist").append(row) ;
                                    $.ajax({
                                            url: SIDE_URL +'xero/testLinks?contacts=1&method=post&customername='+e.customer.customer_name,
                                            success: function(data){
                                                var data = jQuery.parseJSON(data);
                                               
                                            }
                                    });
                                    $("#newcustomer").modal("hide");
                                    $("#dvLoading").fadeOut("slow");
                        }
                            //$("#listCustomer_" + e).remove(), $("#dvLoading").fadeOut("slow")
                        },
                        error: function(e) {
                            console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow")
                        }
                    });
                }
     });
     
     
     $("#customer_search").keyup(function(){
             var search_data=$("#customer_search").val();
              //$("#dvLoading").fadeIn("slow")
                            $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "customer/searchCustomer",
                                    data: {
                                        search_name:search_data
                                        },
                                    success: function(data) { 
                                        $("#search_data").html(data);
                                        //$("#dvLoading").fadeOut("slow")
                                    },
                                    error: function(data) {
                                        console.log("Ajax request not recieved!");
                                                //$("#dvLoading").fadeOut("slow")
                                    }
                            });
             
         });
});
function right_click_delete(a, b, c, d) {
//    var s = "Are you sure, you want to delete this task?";
//    alertify.confirm(s, function(s) {
//        1 == s && 
                $.ajax({
            type: "post",
            url: SIDE_URL + "kanban/delete_task",
            data: {
                task_id: a
            },
                success: function(b) {
                     $("#listtask_" + a).remove(), alertify.set("notifier", "position", "top-right"), alertify.log("Task has been deleted successfully.")
                }
            })
//        })
}
function opendelete(a, e, t, l) {
    $("#delete_series span").removeClass("checked"), $("#delete_ocuurence span").removeClass("checked"), $("#delete_future span").removeClass("checked"), $("#delete_series").attr("onclick", "delete_rightClick_task('" + e + "','" + t + "','" + l + "','series','" + a + "')"), $("#delete_ocuurence").attr("onclick", "delete_rightClick_task('" + a + "','" + t + "','" + l + "')"), $("#delete_future").attr("onclick", "delete_rightClick_task('" + e + "','" + t + "','" + l + "','future','" + a + "')"), $("#delete_task").modal("show")
}
function delete_rightClick_task(a, e, t, l, i) {
    var l = l || 1,
        i = i || a;
    $.ajax({
        type: "post",
        url: SIDEURL + "kanban/delete_task",
        data: {
            task_id: a,
            from: l
        },
        success: function(a) {
            if ("done" == a) $("#delete_task").modal("hide"), alertify.set("notifier", "position", "top-right"), alertify.log("Task has been deleted successfully.");
            else {
                $("#listtask_" + i).remove(), $("#delete_task").modal("hide"), alertify.set("notifier", "position", "top-right"), alertify.log("Task has been deleted successfully.")
            }
        }
    })
}
    