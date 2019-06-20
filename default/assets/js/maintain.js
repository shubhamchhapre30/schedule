
function getmoreemployee(a){ 
    $("#dvLoading").fadeIn("slow")
     var search_data=$("#employee_search").val();
            $.ajax({
                   type: "post",
                   url: SIDE_URL + "price/getMoreEmployee",
                   data: {
                       page:a,
                       search_name:search_data
                    },
                   success: function(data) {  
                        $("#paging").replaceWith(data);
                        $("#pagination li").removeClass('active');
                        $("#"+a).addClass('active');
                        
                        $("#dvLoading").fadeOut("slow")
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                   }
         
     })
 }

    function getuserunderemployee(a){ 
    $("#dvLoading").fadeIn("slow")
     var search=$("#employee_search_under_customer").val();
            $.ajax({
                   type: "post",
                   url: SIDE_URL + "price/getUserUnderEmployee",
                   data: {
                       page:a,
                       search:search,
                       customer_id:$("#hidden_customer_id").val()
                    },
                   success: function(data) {  
                        $("#paging1").replaceWith(data);
                        $("#pagination1 li").removeClass('active');
                        $("#e_"+a).addClass('active');
                        
                        $("#dvLoading").fadeOut("slow")
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                   }
         
     })
 }

 function remove_category(id){
     var s = "Are you sure, you want to remove this category?";
    $("#alertify").show(), alertify.confirm(s, function(s) {
        return 1 == s && ($("#dvLoading").fadeIn("slow"),void  $.ajax({
                   type: "post",
                   url: SIDE_URL + "price/delete_customer_category",
                   data: {
                       category_id:id,
                       customer_id:$("#hidden_customer_id").val()
                    },
                   success: function(data) {  
                       $("#category_"+id).remove();
                       $("#add_category_option").html(data);
                        $("#dvLoading").fadeOut("slow");
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"), $("#dvLoading").fadeOut("slow");
                   }
         
     })
 )})
 }
 
 
 function expand_data(id){
            
            if($("#cate_expand_"+id).val()=='0'){
                $("#expand_div_"+id).html('<i class="icon-chevron-down" >');
                $("#cate_expand_"+id).val('1');
                $.ajax({
                   type: "post",
                   url: SIDE_URL + "price/get_subcategory_by_category_id",
                   data: {
                       category_id:id,
                       customer_id:$("#hidden_customer_id").val()
                    },
                   success: function(data) {  
                       $("#sub_"+id).html(data);
                       $("#sub_"+id).slideToggle();
                        $("#dvLoading").fadeOut("slow");
                   },
                   error:function(a){
                       console.log("Ajax request not recieved!"), 
                       $("#dvLoading").fadeOut("slow");
                   }
         
                })
            }else{
                $("#expand_div_"+id).html('<i class="icon-chevron-right" >');
                $("#cate_expand_"+id).val('0');
                $("#sub_"+id).slideToggle();
            }
    }
    
 $(document).ready(function(){
     
     $("#employee_search").keyup(function(){
             var search_data=$("#employee_search").val();
              //$("#dvLoading").fadeIn("slow")
                            $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "price/searchEmployee",
                                    data: {
                                        search_name:search_data
                                        },
                                    success: function(data) { 
                                        $("#seachData").html(data);
                                        //$("#dvLoading").fadeOut("slow")
                                    },
                                    error: function(data) {
                                        console.log("Ajax request not recieved!");
                                                //$("#dvLoading").fadeOut("slow")
                                    }
                            });
             
         });
//     
        $("#employee_search_under_customer").keyup(function(){
            var search=$("#employee_search_under_customer").val();
              //$("#dvLoading").fadeIn("slow")
                            $.ajax({
                                    type: "post",
                                    url: SIDE_URL + "price/searchEmployeeUnderCustomer",
                                    data: {
                                        search:search,
                                        customer_id:$("#hidden_customer_id").val()
                                        },
                                    success: function(data) { 
                                        $("#search_result_show").html(data);
                                        //$("#dvLoading").fadeOut("slow")
                                    },
                                    error: function(data) {
                                        console.log("Ajax request not recieved!");
                                                //$("#dvLoading").fadeOut("slow")
                                    }
                            });
            
            
        })

        $("#add_customer_category").on("click",function(){
                   var category_id = $("#customer_category_name").val();
                   var rate = $("#customer_category_rate").val();
                   var category_name = $("#customer_category_name :selected").text();
                   //alert(category_name);
                  //alert(rate)
                   if(category_id == '0'){
                       alertify.alert("Please select a category.");
                   }else if(rate == ''){
                       alertify.alert("Please enter category rate.");
                   }else{
                       $.ajax({
                            type: "post",
                            url: SIDE_URL + "price/addcustomercategory",
                            data: {
                                customer_id:$("#hidden_customer_id").val(),
                                category_id:category_id,
                                rate:rate,
                                category_name:category_name
                             },
                            success: function(data) {  
                                alertify.log("Category added successfully.");
                                $("#category_show").html(data);
                                $('#customer_category_name :selected').remove();
                                $('#customer_category_name option[value=0]').attr("selected", "selected"),
                                $("#customer_category_rate").val('');
                                 $("#dvLoading").fadeOut("slow")
                            },
                            error:function(a){
                                console.log("Ajax request not recieved!"), 
                                 $("#dvLoading").fadeOut("slow");
                            }
                     })
                   }
                   
               });
 })