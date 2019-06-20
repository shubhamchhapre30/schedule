<?php 
        $theme_url = base_url().getThemeName(); 
        //company off days array
        $off_days_arr = array();
        $off_days = get_company_offdays();
        if($off_days!=''){
            $off_days_arr = explode(',', $off_days);
        }
?>
<script src='<?php echo $theme_url; ?>/assets/js/timesheet<?php echo MINIFIED;?>.js?Ver=<?php echo VERSION;?>'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    textarea {
    resize: none;
    }
    table th,table td{padding:10px; text-align:center; overflow: hidden; border-bottom: 1px solid #e7ecf1;}
table{width:100%;}
.main{width:100%;clear: both;}
.left , .middle{float:left;}
.left , .right{ float:left; text-align:center; }
.right{width:100px;}
.left table, .right table{margin:0 auto; }
.middle{ overflow-x:scroll;}
.middle table{width:<?php echo ($total_days*100)."px";?>;table-layout:fixed; }
.middle th, .middle td{width:100px;text-align: center;}
.cols td, .cols th{text-align:center;}
/*th ,td{display:inline-block;}*/
.left table tr th,.left table tr td{ text-align:left}
.cols table tr:last-child { color:#000; font-weight:bold;background-color:#a9a9a9}
.cols table tr:not(:last-child):nth-child(even){background:#e7ecf1;}
.right table tr { color:#000; font-weight:bold }
.cols table tr{height:47px;}  
.cols table tr:first-child{height:59px;}
.left table,.right table {border: 1px solid #e1e6eb;}
.middle table{border-top: 1px solid #e1e6eb;}
.middle table td{border-right: 1px solid #e1e6eb;}
.middle table th{border-right: 1px solid #e1e6eb;}
</style>
<script>
var SITE_URL = '<?php echo base_url(); ?>';
var totalDays ='<?php echo $total_days;?>';
var LOGIN_USER_ID ='<?php echo get_authenticateUserID(); ?>';
var APPROVER_ID = '<?php echo $this->session->userdata('approver_id'); ?>';
</script>
<div class="container-fluid page-background" style="padding:15px;margin-bottom:50px">
        <div class="border" style="background-color:#fff;opacity: 1.0;" >
           
            <div class="col-md-12">
                <a href="index"  class="pull-left" style="padding: 5px 0 5px 0;"> Return to Timesheet List</a>
                <input type="hidden" name="hidden_timesheet_id" id="hidden_timesheet_id" value="<?php echo $timesheet_id; ?>"/>
                <input type="hidden" name="hidden_timesheet_user_id" id="hidden_timesheet_user_id" value="<?php echo $timesheet_user_id; ?>"/>
                <input type="hidden" name="hidden_timesheet_status" id="hidden_timesheet_status" value="<?php echo $timesheet_status; ?>"/>
            </div>
            <div class="col-md-12 ">
                <div class="col-md-3 ">
                    <div class="form-group border timesheet_header">
                        <label class="control-label timesheet_label">Employee</label>
                        <h4><b><?php echo ucfirst($first_name)." ".ucfirst($last_name);?></b></h4>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group border timesheet_header">
                        <label class="control-label timesheet_label" >Status</label>
                        <h4><b id="timesheet_status"><?php echo ucfirst($timesheet_status); ?></b></h4>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group border timesheet_header">
                        <label class="control-label timesheet_label" >Period</label>
                        <h4><b><?php echo $from_date . ' - ' . $to_date; ?></b></h4>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group border timesheet_header">
                        <label class="control-label timesheet_label" >Timesheet Total</label>
                        <h4><b id="total_counts"><?php echo $total_hours; ?></b></h4>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 margin5">
                <div class="form-group floating" >
                    <button  name="exceptions" class="btn red btn-new unsorttd tooltips" style="padding-bottom: 2px;cursor: default !important"  data-placement="top" data-original-title="Number of tasks that have actual time but status isn't completed.">Exceptions  <span class="badge timesheet_pills"><?php echo $exception_task; ?></span></button>
                </div>
                <div class="form-group floating">
                    <button  name="days_changed" class="btn green btn-new unsorttd tooltips" style="cursor: default !important" data-placement="top" data-original-title="Number of days where actual time is different to the billed time.">Days Changed <span class="badge timesheet_pills" id="days_chnaged"><?php echo $days_changed; ?></span></button>
                </div>
                <div class="form-group floating col-md-6" style="padding-left:40px">
                    <label class="control-label col-md-2 margin5"><b>Show</b></label>
                    <select class="col-md-4 m-wrap no-margin radius-b change_customer_timesheet" id="change_customer_timesheet">
                        <option value="time" selected="selected">Time</option>
                        <?php if($this->session->userdata('is_manager')=='1'){ ?>
                        <option value="revenue">Revenue</option>
                        <option value="cost">Cost</option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="hidden_from_date" id="hidden_from_date" value="<?php echo $from_date; ?>"/>
                    <input type="hidden" name="hidden_to_date" id="hidden_to_date" value="<?php echo $to_date; ?>"/>
                </div>
            </div>
            
                <div class="col-md-12" id="change_timesheet_view">
                    <script type="text/javascript">
                            $(document).ready(function(){
                                var AllDaysWidth = totalDays*100;
                                var scrolled = 0;
                                 $(document).on("click",'#rightClick', function () {
                                    
                                    var div_width = $(".middle").width();
                                    var total = Number(AllDaysWidth) - Number(div_width);
                                    
                                    if(scrolled <= total){
                                        scrolled = scrolled + 300;
                                        $("#leftClick").removeClass('not_access');
                                        $("#leftClick").removeAttr('disabled');
                                    }else{
                                        $("#rightClick").attr('disabled');
                                        $("#rightClick").addClass('not_access');
                                    }
                                    
                                    $(".middle").stop(false,true).animate({
                                        scrollLeft: scrolled
                                    });
                                });
                                $("#leftClick").addClass('not_access');
                                $("#leftClick").prop('disabled', true);
                                $(document).on("click",'#leftClick', function () { 
                                    if(scrolled != 0){
                                        scrolled = scrolled - 300;
                                        $("#rightClick").removeClass('not_access');
                                        $("#rightClick").removeAttr('disabled');
                                    }
                                    if(scrolled <= '0'){
                                        $("#leftClick").addClass('not_access');
                                        $("#leftClick").attr('disabled');
                                    }
                                    $(".middle").stop(false,true).animate({
                                        scrollLeft: scrolled
                                    });
                                    
                                });
                               
                                var leftRightColWidth =  $(".left").outerWidth() + 105;
                                $(".middle").css("width" , "calc(100% - " + leftRightColWidth +"px)");
                                
                                var containerWidth = $(".middle").outerWidth();
                                
    
                                if(AllDaysWidth < containerWidth){
                                    $(".middle").css("overflow" , "visible");
                                    $(".middle table").css("border-right",'1px solid #e1e6eb');
                                    $("#rightClick").css('display','none');
                                    $("#leftClick").css('display','none');
                                    $("span").removeClass('timesheet_middle_css');
                                }
                            });
                    </script>
                   <div class="main col-md-12">
                        <div class="left cols">
                            <table>
                                <tr>
                                   <th>
                                        <span>Customers</span>
                                        <span style="float: right;">
                                            <a href="javascript:void(0)" id="leftClick" disabled> <i class="glyphicon glyphicon-chevron-left"> </i> </a>
                                        </span>
                                   </th>
                                </tr>
                                <?php if($customers){
                                        foreach($customers as $cus){ 
                                            $total_time = 0;?>
                                <tr id="customer_id_<?php echo $cus->customer_id; ?>">
                                    <td><?php echo ucfirst($cus->customer_name); ?></td>
                                        
                                </tr>
                                <?php } } ?>
                                <tr>
                                    <td>Non Customer Related</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                </tr>
                            </table>  
                        </div>
  
                        <div class="middle cols">
                            <table>
                                <tr>
                                    <?php foreach($date_range as $date) {
                                        if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                            $tdstyle = 'style="background-color:#CED2D8;"';
                                        } else {
                                            $tdstyle = '';
                                        }
                                    ?>
                                    <th <?php echo $tdstyle; ?> ><?php echo date('D d',  strtotime($date)); ?><span style="display:block"><?php echo date('M',  strtotime($date)); ?></span></th>           
                                    <?php } ?>
                                </tr>
                                <?php if($customers){
                                        foreach($customers as $cus){ 
                                            $total_time = 0;?>
                                            <tr id="customer_id_<?php echo $cus->customer_id; ?>">
                                                    <?php foreach($date_range as $date) { ?>
                                                    <?php 
                                                            $total_completed_time = $this->timesheet_model->get_customer_total_time($cus->customer_id,$date,$timesheet_user_id);
                                                            $exception_task = $this->timesheet_model->check_exception_task($cus->customer_id,$date,$timesheet_user_id);
                                                            $days_chnaged_task = $this->timesheet_model->check_days_changed_task($cus->customer_id,$date,$timesheet_user_id);
                                                            $total_time += $total_completed_time;
                                                            $hours = intval($total_completed_time/60);
                                                            $minutes = $total_completed_time - ($hours * 60);
                                                            if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                    $tdstyle = 'style="background-color:#CED2D8;"';
                                                            } else {
                                                                    $tdstyle = '';
                                                            }
                                                            ?>   
                                                        <input type="hidden" id="hidden_date_time_<?php echo $date; ?>_<?php echo $cus->customer_id; ?>" name="hidden_date_time_<?php echo $date; ?>_<?php echo $cus->customer_id; ?>" value="<?php  if($total_completed_time!=''){echo $total_completed_time;}else{ echo '0';}?>"/>
                                                        <td class="timesheet_link" onclick="open_popup('<?php echo $date; ?>','<?php echo $cus->customer_id; ?>');" <?php  echo $tdstyle; ?> id="timesheet_date_<?php echo $date; ?>_<?php echo $cus->customer_id; ?>" ><?php if($hours == 0 && $minutes ==0){ echo '-';}else{echo $hours.':'.(strlen($minutes) == 1 ? '0'.$minutes : $minutes); } ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh" ></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
                                                    <?php } ?>
                                            </tr>
                                <?php } } ?>
                                <tr>
                                    <?php    $non_customer_task_total_time = 0;
                                              foreach($date_range as $date) { ?>
                                        <?php   
                                                $total_completed_time = $this->timesheet_model->get_customer_total_time('',$date,$timesheet_user_id);
                                                $exception_task = $this->timesheet_model->check_exception_task('',$date,$timesheet_user_id);
                                                $days_chnaged_task = $this->timesheet_model->check_days_changed_task('',$date,$timesheet_user_id);
                                                $non_customer_task_total_time += $total_completed_time;
                                                $hours4 = intval($total_completed_time/60);
                                                $minutes4 = $total_completed_time - ($hours4 * 60);
                                                if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                        $tdstyle = 'style="background-color:#CED2D8;"';
                                                } else {
                                                        $tdstyle = '';
                                                }
                                                ?>   
                                            <input type="hidden" id="hidden_date_time_<?php echo $date; ?>_" name="hidden_date_time_<?php echo $date; ?>_" value="<?php  if($total_completed_time!=''){echo $total_completed_time;}else{ echo '0';}?>"/>
                                            <td class="timesheet_link" onclick="open_popup('<?php echo $date; ?>','');" <?php  echo $tdstyle; ?> id="timesheet_date_<?php echo $date; ?>_" ><?php if($hours4 == 0 && $minutes4 == 0){ echo '-';}else{echo $hours4.':'.(strlen($minutes4) == 1 ? '0'.$minutes4 : $minutes4); } ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh"></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
                                        <?php } ?>
                                </tr>
                                <tr>
                                    <?php  $sum = 0;
                                            foreach($date_range as $date) { 
                                                if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                        $tdstyle = 'style="background-color:#CED2D8;"';
                                                } else {
                                                        $tdstyle = '';
                                                }    
                                            $customer_total_time = 0;
                                            if($customers){
                                            foreach($customers as $cus){ ?>
                                        <?php 
                                                $total_completed_time = $this->timesheet_model->get_customer_total_time($cus->customer_id,$date,$timesheet_user_id);
                                                $customer_total_time += $total_completed_time;
                                                $sum += $total_completed_time;
                                                ?>   
                                            <?php }} ?> 
                                        <?php   $non_customer_task_time = $this->timesheet_model->get_customer_total_time('',$date,$timesheet_user_id);
                                                $customer_total_time += $non_customer_task_time;
                                                $sum += $non_customer_task_time;
                                                $hours2 = intval($customer_total_time/60);
                                                $minutes2 = $customer_total_time - ($hours2 * 60); ?>
                                            <input type="hidden" id="hidden_date_time_<?php echo $date; ?>" name="hidden_date_time_<?php echo $date; ?>" value="<?php  if($customer_total_time!=''){echo $customer_total_time;}else{ echo '0';}?>"/>
                                            <td <?php echo $tdstyle; ?> id="total_time_<?php echo $date;?>" ><?php if($hours2 == 0 && $minutes2 ==0){ echo '-';}else{echo $hours2.':'.(strlen($minutes2) == 1 ? '0'.$minutes2 : $minutes2);} ?></td>
                                    
                                    <?php } ?>
                                </tr>
                            </table>
                        </div> 
  
                        
                        <div class="right cols">
                              <table>
                                <tr>
                                  <th>
                                      <span class="timesheet_middle_css">
                                          Total
                                      </span>
                                      <span>
                                          <a href="javascript:void(0)" id="rightClick" > <i class="glyphicon glyphicon-chevron-right"> </i> </a>
                                      </span>
                                  </th>
                                </tr>
                                 <?php if($customers){
                                        foreach($customers as $cus){ 
                                            $total_time = 0;?>
                                            <tr id="customer_id_<?php echo $cus->customer_id; ?>">

                                                    <?php foreach($date_range as $date) { ?>
                                                    <?php 
                                                            $total_completed_time = $this->timesheet_model->get_customer_total_time($cus->customer_id,$date,$timesheet_user_id);
                                                            $exception_task = $this->timesheet_model->check_exception_task($cus->customer_id,$date,$timesheet_user_id);
                                                            $days_chnaged_task = $this->timesheet_model->check_days_changed_task($cus->customer_id,$date,$timesheet_user_id);
                                                            $total_time += $total_completed_time;
                                                            $hours = intval($total_completed_time/60);
                                                            $minutes = $total_completed_time - ($hours * 60);
                                                            if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                    $tdstyle = 'style="background-color:#CED2D8;"';
                                                            } else {
                                                                    $tdstyle = '';
                                                            }
                                                            ?>   

                                                    <?php } $hours1 = intval($total_time/60);
                                                            $minutes1 = $total_time - ($hours1 * 60);
                                                             ?>
                                                        <input type="hidden" id="hidden_specific_customer_time_<?php echo $cus->customer_id; ?>" name="hidden_specific_customer_time_<?php echo $cus->customer_id; ?>" value="<?php  if($total_time!=''){echo $total_time;}else{ echo '0';}?>"/>
                                                        <td id="specific_customer_total_<?php echo $cus->customer_id; ?>" ><?php if($hours1 == 0 && $minutes1 == 0){ echo '-';}else{echo $hours1.':'.(strlen($minutes1) == 1 ? '0'.$minutes1 : $minutes1);} ?></td>
                                            </tr>
                                            <?php } } ?>
                                            <tr>
                                                <?php    $non_customer_task_total_time = 0;
                                                    foreach($date_range as $date) { ?>
                                                <?php   
                                                    $total_completed_time = $this->timesheet_model->get_customer_total_time('',$date,$timesheet_user_id);
                                                    $exception_task = $this->timesheet_model->check_exception_task('',$date,$timesheet_user_id);
                                                    $days_chnaged_task = $this->timesheet_model->check_days_changed_task('',$date,$timesheet_user_id);
                                                    $non_customer_task_total_time += $total_completed_time;
                                                    $hours4 = intval($total_completed_time/60);
                                                    $minutes4 = $total_completed_time - ($hours4 * 60);
                                                    if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                            $tdstyle = 'style="background-color:#CED2D8;"';
                                                    } else {
                                                            $tdstyle = '';
                                                    }
                                                    ?>   
                                                    <?php } $hours5 = intval($non_customer_task_total_time/60);
                                                    $minutes5 = $non_customer_task_total_time - ($hours5 * 60);?>
                                                <input type="hidden" id="hidden_specific_customer_time_" name="hidden_specific_customer_time_" value="<?php  if($non_customer_task_total_time!=''){echo $non_customer_task_total_time;}else{ echo '0';}?>"/>
                                                <td id="specific_customer_total_"><?php if($hours5 == 0 && $minutes5 == 0){echo '-';}else{echo $hours5.':'.(strlen($minutes5) == 1 ? '0'.$minutes5 : $minutes5);} ?></td>
                                            </tr>
                                            <tr>
                                                <?php  $sum = 0;
                                                    foreach($date_range as $date) { 
                                                        if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                $tdstyle = 'style="background-color:#CED2D8;"';
                                                        } else {
                                                                $tdstyle = '';
                                                        }    
                                                    $customer_total_time = 0;
                                                    if($customers){
                                                    foreach($customers as $cus){ ?>
                                                <?php 
                                                        $total_completed_time = $this->timesheet_model->get_customer_total_time($cus->customer_id,$date,$timesheet_user_id);
                                                        $customer_total_time += $total_completed_time;
                                                        $sum += $total_completed_time;
                                                        ?>   
                                                <?php } } ?> 
                                                <?php   $non_customer_task_time = $this->timesheet_model->get_customer_total_time('',$date,$timesheet_user_id);
                                                        $customer_total_time += $non_customer_task_time;
                                                        $sum += $non_customer_task_time;
                                                        $hours2 = intval($customer_total_time/60);
                                                        $minutes2 = $customer_total_time - ($hours2 * 60); ?>
                                                <?php } $hours3 = intval($sum/60);
                                                    $minutes3 = $sum - ($hours3 * 60);  ?>
                                                <input type="hidden" name="hidden_overall_time" id="hidden_overall_time" value="<?php if($sum!=''){ echo $sum; }else{ echo '0'; }?>"/>       
                                                <td id="overall_total"><?php echo $hours3.':'.(strlen($minutes3) == 1 ? '0'.$minutes3 : $minutes3); ?></td>
                                                <input type="hidden" name="hidden_total_time" id="hidden_total_time" value="<?php echo $hours3.'h'.(strlen($minutes3) == 1 ? '0'.$minutes3 : $minutes3).'m'; ?>"/>
                                            </tr>
                                </table>  
                          </div>
                    </div>
                </div>
            <?php if($timesheet_status== 'submitted' ||$timesheet_status == 'exported'|| $timesheet_status =='approved' || $timesheet_user_id != get_authenticateUserID()){
                        $class5 = "disabled='disabled'";
                    }else{
                        $class5 = '';
                    } ?>
            <div class="row margin-top-10">
                <div class="col-md-4" style="margin-left: 30px;">
                    <label class="control-label timesheet_label" ><b>Timesheet Comments</b></label>
                     <div class="form-group">
                        <textarea cols="40" rows="5" id="save_timesheet_comment" <?php echo $class5; ?>><?php if($timesheet_comments !=''){ echo $timesheet_comments->timesheet_comments;} ?></textarea>
                        <input type="hidden" name="timesheet_comment_id" id="timesheet_comment_id" value="<?php if($timesheet_comments !=''){ echo $timesheet_comments->comment_id;}else{ echo '0';}?>"/>
                     </div>
                </div>
                
                <?php if($timesheet_status == 'exported'|| $timesheet_status =='approved' || $timesheet_user_id == get_authenticateUserID() || $approver_details->timesheet_approver_id != get_authenticateUserID()){ $class8 = "disabled='disabled'"; }else{ $class8 = ''; }?>
                <div class="col-md-4" >
                    <label class="control-label timesheet_label"><b>Approver Comments</b></label>
                     <div class="form-group">
                         <textarea cols="40" rows="5" id="approver_comments" <?php echo $class8; ?> ><?php if($approver_comment !=''){ echo $approver_comment->timesheet_comments;} ?></textarea>
                         <input type="hidden" name="approver_comments_id" id="approver_comments_id" value="<?php if($approver_comment !=''){ echo $approver_comment->comment_id;}else{ echo '0';}?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 margin-top-10">

                    <div class="col-md-10">
                        <?php if($timesheet_status =='draft'){  $class4="display:block"; }else{ $class4="display:none";}?>
                        <div class="form-group floating" style="margin-left: 5px;" >
                            <button  name="draft" id="draft" style="<?php echo $class4; ?>" class="btn blue btn-new unsorttd hide_button" type="button" onclick="save_as_draft(<?php echo $timesheet_id;?>);">Save as Draft</button>
                        </div>
                        
                        <?php if($this->session->userdata('approver_id')!='0' && $timesheet_user_id == get_authenticateUserID() && $timesheet_status !='submitted' && $timesheet_status !='approved' && $timesheet_status !='exported'){ $class3="display:block";}else{  $class3="display:none"; }?>
                        <div class="form-group floating" >
                            <button  name="approval" id="approval" class="btn green btn-new unsorttd hide_button" style="<?php echo $class3; ?>" type="button" onclick="submit_for_approval(<?php echo $timesheet_id; ?>);">Submit for Approval</button>
                        </div>
                        
                        <?php if($timesheet_status == 'submitted' && ($timesheet_user_id == get_authenticateUserID() || ($approver_details->timesheet_approver_id != get_authenticateUserID() && $this->session->userdata('is_administrator') == '1'))){ $class2 = "display:block";}else{$class2="display:none";}?>
                        <div class="form-group floating " >
                            <button  name="recall" id="recall" style="padding-bottom: 6px !important;<?php echo $class2; ?>" class="btn green btn-new unsorttd hide_button" type="button" onclick="timesheet_recall('<?php echo $timesheet_id; ?>');">Recall</button>
                        </div>
                        <?php if($timesheet_status == 'approved' || $timesheet_status == 'exported' || $this->session->userdata('approver_id')!='0' && $timesheet_user_id == get_authenticateUserID()){ $class6 = 'display:none'; }else{ $class6 = '';} ?>
                        <div class="form-group floating" >
                            <button  name="approve" id="approve" class="btn green btn-new unsorttd hide_button" type="button" onclick="timesheet_approve(<?php echo $timesheet_id; ?>)" style="padding-bottom: 6px !important;<?php echo $class6; ?>">Approve</button>
                        </div>
                        
                    </div>
                    
                    <div class="col-md-2">
                        <?php if(($timesheet_status !='approved' && $timesheet_status !='exported') && ($this->session->userdata('is_manager')!='1' || $timesheet_user_id == get_authenticateUserID()) ){ $class1="display:block";}else{ $class1="display:none"; }?>
                        <div class="form-group floating" >
                            <button  name="delete" id="delete" style="<?php echo $class1;?>" class="btn red btn-new unsorttd hide_button" type="button" onclick="timesheet_deletion('<?php echo $timesheet_id;?>')" >Delete</button>
                        </div>
                        <?php if($timesheet_status == 'exported' && $this->session->userdata('is_administrator') == '1'){ ?>
                        <div class="form-group floating" >
                            <button  name="export_cancel" id="export_cancel" class="btn red btn-new unsorttd hide_button" type="button" onclick="cancel_export(<?php echo $timesheet_id; ?>);">Cancel Export</button>
                        </div>
                        <?php } ?>
                    </div>

                </div>
                <div class="col-md-12 margin-top-5">
                    <div class="col-md-10">
                        <div class="form-group floating" style="margin-left: 5px;" >
                            <button  name="return_to_draft" id="return_to_draft" class="btn green btn-new unsorttd hide_button" type="button" onclick="return_to_draft(<?php echo $timesheet_id; ?>);" style="<?php if($timesheet_status == 'approved') { echo "display:block";}else{ echo "display:none";}?>" >Return to draft</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

                                <div id="timesheet_task_popup" class="modal cus_model fade customecontainer" tabindex="-1">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3 id="popup_header"></h3>
                                            <input type="hidden" name="now_date" id="now_date" value=""/>
                                            <input type="hidden" name="now_customer_id" id="now_customer_id" value=""/>
                                        </div>
                                        <div class="modal-body">
					<div class="portlet">
                                            <div class="portlet-body  form flip-scroll" style="padding:10px;">
                                                <div class="row form-horizontal">
                                                    <form name="update_task_timesheet" id="update_task_timesheet" method="POST">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <table class="table table-striped table-hover table-condensed flip-content">
                                                                    <thead class="flip-content">
                                                                        <th style="text-align:left">Task Name</th>
                                                                        <th style="text-align:left">Project</th>
                                                                        <th>Estimated Time</th>
                                                                        <th>Actual Time</th>
                                                                        <th>Billed Time</th>
                                                                    </thead>
                                                                    <tbody id="timesheet_task_list">
                                                                        
                                                                    </tbody>
                                                                </table>    
                                                            </div>
                                                        </div>
                                                        <div class="form-group pull-right col-md-12">
                                                            <button class="btn blue btn-new unsorttd txtbold" type="button" id="save_timesheet_task" style="float:left">Save</button>
                                                            <button class="btn green btn-new unsorttd txtbold" id="cancel_timesheet_popup" type="button">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>