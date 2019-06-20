<?php
    $theme_url = base_url().getThemeName();
    $off_days_arr = array();
    $off_days = get_company_offdays();
    if($off_days!=''){
       $off_days_arr = explode(',', $off_days);
    }
?>

<div class="col-md-12" id="change_timesheet_view">
    <script type="text/javascript">
                            $(document).ready(function(){
                                var AllDaysWidth = totalDays*100;
                                var scrolled1 = 0;
                                $(document).on("click",'#rightClick', function () {
                                    var div_width = $(".middle").width();
                                    var total = Number(AllDaysWidth) - Number(div_width);
                                    
                                    if(scrolled1 < total){
                                        scrolled1 = scrolled1 + 300;
                                        $("#leftClick").removeClass('not_access');
                                        $("#leftClick").removeAttr('disabled');
                                    }else{
                                        $("#rightClick").addClass('not_access');
                                        $("#rightClick").attr('disabled');
                                    }
                                    
                                    $(".middle").stop(false,true).animate({
                                        scrollLeft: scrolled1
                                    });
                                });
                                 $("#leftClick").addClass('not_access');
                                 $("#leftClick").prop('disabled', true);
                                $(document).on("click",'#leftClick', function () {
                                    if(scrolled1 != 0){
                                        scrolled1 = scrolled1 - 300;
                                        $("#rightClick").removeClass('not_access');
                                        $("#rightClick").removeAttr('disabled');
                                    }
                                    if(scrolled1 <= '0'){
                                        $("#leftClick").attr('disabled');
                                        $("#leftClick").addClass('not_access');
                                    }
                                    $(".middle").stop(false,true).animate({
                                        scrollLeft: scrolled1
                                    });
                                });
                                
                                var leftRightColWidth =  $(".left").outerWidth() + 100;
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
                        <span class="pull-right" >
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
                <?php switch ($view){
                            case 'time': ?>
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
                                                            <td class="timesheet_link" onclick="open_popup('<?php echo $date; ?>','<?php echo $cus->customer_id; ?>');" <?php  echo $tdstyle; ?> id="timesheet_date_<?php echo $date; ?>_<?php echo $cus->customer_id; ?>" ><?php if($hours == 0 && $minutes ==0){ echo '-';}else{echo $hours.':'.(strlen($minutes) == 1 ? '0'.$minutes : $minutes);} ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh"></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
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
                                                            <td class="timesheet_link" onclick="open_popup('<?php echo $date; ?>','');" <?php  echo $tdstyle; ?> id="timesheet_date_<?php echo $date; ?>_" ><?php if($hours4 == 0 && $minutes4 ==0 ){echo '-';}else{echo $hours4.':'.(strlen($minutes4) == 1 ? '0'.$minutes4 : $minutes4);} ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh"></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
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
                                                            <td <?php echo $tdstyle; ?> id="total_time_<?php echo $date;?>" ><?php if($hours2 == 0 && $minutes2 == 0){echo '-';}else{echo $hours2.':'.(strlen($minutes2) == 1 ? '0'.$minutes2 : $minutes2);} ?></td>

                                                    <?php } ?>
                                                </tr>
                            <?php break;
                            case 'revenue' :?>
                               <?php if($customers){
                                        foreach($customers as $cus){ ?>
                                            <tr id="customer_id_<?php echo $cus->customer_id; ?>">
                                                    <?php foreach($date_range as $date) { ?>
                                                    <?php   $per_day_total_revenue = 0;
                                                            if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                    $tdstyle = 'style="background-color:#CED2D8;"';
                                                            } else {
                                                                    $tdstyle = '';
                                                            }    
                                                            $total_charge_out_rate = $this->timesheet_model->get_total_timesheet_revenue($cus->customer_id,$date,$timesheet_user_id);
                                                            $exception_task = $this->timesheet_model->check_exception_task($cus->customer_id,$date,$timesheet_user_id);
                                                            $days_chnaged_task = $this->timesheet_model->check_days_changed_task($cus->customer_id,$date,$timesheet_user_id);
                                                            if($total_charge_out_rate){
                                                                foreach($total_charge_out_rate as $rate){
                                                                    $per_day_total_revenue += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                }
                                                            }
                                                            ?>   
                                                         <td <?php echo $tdstyle; ?> ><?php if($per_day_total_revenue == 0){echo '-';}else{if (strpos($per_day_total_revenue, '.') == false) { echo $per_day_total_revenue.'.00'; }else { echo $per_day_total_revenue; }} ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh"></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
                                                    <?php } ?>
                                            </tr>
                                            <?php } } ?>
                                            <tr>
                                                <?php foreach($date_range as $date) { ?>
                                                <?php   $per_day_total_revenue = 0;
                                                    if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                            $tdstyle = 'style="background-color:#CED2D8;"';
                                                    } else {
                                                            $tdstyle = '';
                                                    }
                                                    $total_charge_out_rate = $this->timesheet_model->get_total_timesheet_revenue('',$date,$timesheet_user_id);
                                                    $exception_task = $this->timesheet_model->check_exception_task('',$date,$timesheet_user_id);
                                                    $days_chnaged_task = $this->timesheet_model->check_days_changed_task('',$date,$timesheet_user_id);
                                                    if($total_charge_out_rate){
                                                        foreach($total_charge_out_rate as $rate){
                                                            $per_day_total_revenue += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                        }
                                                    }
                                                    ?>   
                                                 <td <?php echo $tdstyle; ?> ><?php if($per_day_total_revenue == 0){echo '-';}else{if (strpos($per_day_total_revenue, '.') == false) { echo $per_day_total_revenue.'.00';}else {echo $per_day_total_revenue;}} ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh"></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <?php  foreach($date_range as $date) { 
                                                            if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                    $tdstyle = 'style="background-color:#CED2D8;"';
                                                            } else {
                                                                    $tdstyle = '';
                                                            }
                                                        $customer_total_revenue = 0;
                                                        if($customers){
                                                        foreach($customers as $cus){ ?>
                                                    <?php 
                                                            $total_charge_out_rate = $this->timesheet_model->get_total_timesheet_revenue($cus->customer_id,$date,$timesheet_user_id);
                                                            if($total_charge_out_rate){
                                                                foreach($total_charge_out_rate as $rate){
                                                                    $customer_total_revenue += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                }
                                                            }
                                                            ?>   
                                                    <?php } } ?>
                                                    <?php $non_customer_task_revenue = $this->timesheet_model->get_total_timesheet_revenue('',$date,$timesheet_user_id);
                                                          if($non_customer_task_revenue){
                                                                foreach($non_customer_task_revenue as $rate){
                                                                    $customer_total_revenue += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                }
                                                            }
                                                          ?>
                                                    <td <?php echo $tdstyle; ?> ><?php if($customer_total_revenue == 0){echo '-';}else{if (strpos($customer_total_revenue, '.') == false) { echo $customer_total_revenue.'.00';}else {echo $customer_total_revenue;}} ?></td>

                                                <?php }  ?>
                                            </tr>
                        <?php break; 
                            case 'cost': ?> 
                                    <?php if($customers){
                                        foreach($customers as $cus){ ?>
                                            <tr id="customer_id_<?php echo $cus->customer_id; ?>">
                                                <?php foreach($date_range as $date) { ?>
                                                    <?php $per_day_total_cost = 0;
                                                            if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                    $tdstyle = 'style="background-color:#CED2D8;"';
                                                            } else {
                                                                    $tdstyle = '';
                                                            }   
                                                            $total_completed_task_cost = $this->timesheet_model->get_total_timesheet_cost($cus->customer_id,$date,$timesheet_user_id);
                                                            $exception_task = $this->timesheet_model->check_exception_task($cus->customer_id,$date,$timesheet_user_id);
                                                            $days_chnaged_task = $this->timesheet_model->check_days_changed_task($cus->customer_id,$date,$timesheet_user_id);
                                                            if($total_completed_task_cost){
                                                                foreach($total_completed_task_cost as $rate){
                                                                    $per_day_total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                                }
                                                            }
                                                            ?>   
                                                         <td <?php echo $tdstyle; ?> ><?php if($per_day_total_cost == 0){echo '-';}else{if (strpos($per_day_total_cost, '.') == false) { echo $per_day_total_cost.'.00';}else {echo $per_day_total_cost;}} ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh"></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
                                                    <?php } ?>
                                            </tr>
                                    <?php } } ?>
                                    <tr>
                                        <?php foreach($date_range as $date) { ?>
                                        <?php   $per_day_total_cost = 0;
                                                if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                        $tdstyle = 'style="background-color:#CED2D8;"';
                                                } else {
                                                        $tdstyle = '';
                                                }
                                                $total_completed_task_cost = $this->timesheet_model->get_total_timesheet_cost('',$date,$timesheet_user_id);
                                                $exception_task = $this->timesheet_model->check_exception_task('',$date,$timesheet_user_id);
                                                $days_chnaged_task = $this->timesheet_model->check_days_changed_task('',$date,$timesheet_user_id);
                                                if($total_completed_task_cost){
                                                    foreach($total_completed_task_cost as $rate){
                                                        $per_day_total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                    }
                                                }
                                                ?>   
                                             <td <?php echo $tdstyle; ?> ><?php if($per_day_total_cost == 0){echo '-';}else{ if (strpos($per_day_total_cost, '.') == false) { echo $per_day_total_cost.'.00';}else {echo $per_day_total_cost;}} ?><?php if($exception_task =='1'){?><i class="stripicon iconhigh"></i><?php } if($days_chnaged_task=='1'){?><i class="fa fa-check" style="color: #0de40d !important;"></i><?php }?></td>           
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <?php  foreach($date_range as $date) { 
                                                if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                        $tdstyle = 'style="background-color:#CED2D8;"';
                                                } else {
                                                        $tdstyle = '';
                                                }
                                            $customer_total_cost = 0;
                                            if($customers){
                                            foreach($customers as $cus){ ?>
                                        <?php 
                                                $total_completed_task_cost = $this->timesheet_model->get_total_timesheet_cost($cus->customer_id,$date,$timesheet_user_id);
                                                if($total_completed_task_cost){
                                                    foreach($total_completed_task_cost as $rate){
                                                        $customer_total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                    }
                                                }
                                               ?>   
                                        <?php } }  ?>
                                        <?php   $non_customer_task_cost = $this->timesheet_model->get_total_timesheet_cost('',$date,$timesheet_user_id);
                                                if($non_customer_task_cost){
                                                    foreach($non_customer_task_cost as $rate){
                                                        $customer_total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                    }
                                                }
                                             ?>
                                                
                                            <td <?php echo $tdstyle; ?> ><?php if($customer_total_cost == 0){echo '-';}else{if (strpos($customer_total_cost, '.') == false) { echo $customer_total_cost.'.00';}else {echo $customer_total_cost;}} ?></td>
                                        <?php }  ?>
                                    </tr>
                        <?php break; ?>        
                
                   <?php } ?>
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
                <?php switch ($view){
                            case 'time': ?>
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
                                                        <td id="specific_customer_total_<?php echo $cus->customer_id; ?>" ><?php if($hours1 ==0 && $minutes1 ==0){echo '-';}else{echo $hours1.':'.($minutes1 == 0 ? '00' : $minutes1);}?></td>
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
                                                <td id="specific_customer_total_"><?php if($hours5 == 0 && $minutes5 == 0){ echo '-';}else{echo $hours5.':'.($minutes5 == 0 ? '00' : $minutes5);} ?></td>
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
                                                <td id="overall_total"><?php echo $hours3.':'.($minutes3 == 0 ? '00' : $minutes3); ?></td>
                                                <input type="hidden" name="hidden_total_time" id="hidden_total_time" value="<?php echo $hours3.'h'.($minutes3 == 0 ? '00' : $minutes3).'m'; ?>"/>
                                            </tr>
                                            
                            <?php break;
                            case 'revenue' :?>
                                <?php if($customers){
                                        foreach($customers as $cus){ 
                                            $total_charge_out = 0;?>
                                            <tr id="customer_id_<?php echo $cus->customer_id; ?>">
                                                    <?php foreach($date_range as $date) { ?>
                                                    <?php 
                                                            if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                    $tdstyle = 'style="background-color:#CED2D8;"';
                                                            } else {
                                                                    $tdstyle = '';
                                                            }    
                                                            $total_charge_out_rate = $this->timesheet_model->get_total_timesheet_revenue($cus->customer_id,$date,$timesheet_user_id);
                                                            if($total_charge_out_rate){
                                                                foreach($total_charge_out_rate as $rate){
                                                                    $total_charge_out += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                }
                                                            }
                                                            ?>   
                                                    <?php } ?>
                                                     <td><?php if($total_charge_out == 0){echo '-';}else{if (strpos($total_charge_out, '.') == false) { echo $total_charge_out.'.00';}else {echo $total_charge_out;}} ?></td>
                                            </tr>
                                            <?php } } ?>
                                            <tr>
                                                <?php $total_charge_out = 0; ?>
                                                 <?php foreach($date_range as $date) { ?>
                                                    <?php   
                                                            if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                    $tdstyle = 'style="background-color:#CED2D8;"';
                                                            } else {
                                                                    $tdstyle = '';
                                                            }
                                                            $total_charge_out_rate = $this->timesheet_model->get_total_timesheet_revenue('',$date,$timesheet_user_id);
                                                            if($total_charge_out_rate){
                                                                foreach($total_charge_out_rate as $rate){
                                                                    $total_charge_out += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                }
                                                            }
                                                            ?>   
                                                    <?php } ?>
                                                     <td><?php if($total_charge_out == 0){echo '-';}else{if (strpos($total_charge_out, '.') == false) { echo $total_charge_out.'.00';}else {echo $total_charge_out;}} ?></td>
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
                                                            $total_charge_out_rate = $this->timesheet_model->get_total_timesheet_revenue($cus->customer_id,$date,$timesheet_user_id);
                                                            if($total_charge_out_rate){
                                                                foreach($total_charge_out_rate as $rate){
                                                                    $customer_total_time += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                    $sum += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                }
                                                            }
                                                            
                                                            ?>   
                                                    <?php } } ?>
                                                    <?php $non_customer_task_revenue = $this->timesheet_model->get_total_timesheet_revenue('',$date,$timesheet_user_id);
                                                          if($non_customer_task_revenue){
                                                                foreach($non_customer_task_revenue as $rate){
                                                                    $customer_total_time += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                    $sum += round(($rate['billed_time']*$rate['charge_out_rate'])/60,2);
                                                                }
                                                            }  ?>
                                                    <?php }  ?>
                                                <td><?php if (strpos($sum, '.') == false) { echo $sum.'.00';}else {echo $sum;} ?></td>
                                                <input type="hidden" name="hidden_revenue" id="hidden_revenue" value="<?php if (strpos($sum, '.') == false) { echo $this->session->userdata('currency').$sum.'.00';}else {echo $this->session->userdata('currency').$sum;} ?>"/>
                                            </tr>
                        <?php break; 
                            case 'cost': ?> 
                                <?php if($customers){
                                        foreach($customers as $cus){ 
                                            $total_cost = 0;?>
                                        <tr id="customer_id_<?php echo $cus->customer_id; ?>">
                                            <?php foreach($date_range as $date) { ?>
                                                <?php 
                                                        if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                                $tdstyle = 'style="background-color:#CED2D8;"';
                                                        } else {
                                                                $tdstyle = '';
                                                        }   
                                                        $total_completed_task_cost = $this->timesheet_model->get_total_timesheet_cost($cus->customer_id,$date,$timesheet_user_id);
                                                        if($total_completed_task_cost){
                                                            foreach($total_completed_task_cost as $rate){
                                                                $total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                            }
                                                        }
                                                        ?>   
                                                <?php } ?>
                                            <td><?php if($total_cost == 0){echo '-';}else{if (strpos($total_cost, '.') == false) { echo $total_cost.'.00';}else {echo $total_cost;}} ?></td>
                                        </tr>
                                <?php } } ?>
                                <tr>
                                    <?php $total_cost = 0; ?>
                                     <?php foreach($date_range as $date) { ?>
                                        <?php   
                                                if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                        $tdstyle = 'style="background-color:#CED2D8;"';
                                                } else {
                                                        $tdstyle = '';
                                                }
                                                $total_completed_task_cost = $this->timesheet_model->get_total_timesheet_cost('',$date,$timesheet_user_id);
                                                if($total_completed_task_cost){
                                                    foreach($total_completed_task_cost as $rate){
                                                        $total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                    }
                                                }
                                                ?>   
                                        <?php } ?>
                                         <td><?php if($total_cost == 0){echo '-';}else{if (strpos($total_cost, '.') == false) { echo $total_cost.'.00';}else {echo $total_cost;}} ?></td>
                                </tr>
                                <tr>
                                    <?php  $sum = 0;
                                            foreach($date_range as $date) { 
                                                if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
                                                        $tdstyle = 'style="background-color:#CED2D8;"';
                                                } else {
                                                        $tdstyle = '';
                                                }
                                            $customer_total_cost = 0;
                                            if($customers){
                                            foreach($customers as $cus){ ?>
                                        <?php 
                                                $total_completed_task_cost = $this->timesheet_model->get_total_timesheet_cost($cus->customer_id,$date,$timesheet_user_id);
                                                if($total_completed_task_cost){
                                                    foreach($total_completed_task_cost as $rate){
                                                        $customer_total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                        $sum += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                    }
                                                }
                                                ?>   
                                        <?php } } ?>
                                        <?php   $non_customer_task_cost = $this->timesheet_model->get_total_timesheet_cost('',$date,$timesheet_user_id);
                                                if($non_customer_task_cost){
                                                    foreach($non_customer_task_cost as $rate){
                                                        $customer_total_cost += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                        $sum += round(($rate['task_time_spent']*$rate['cost_per_hour'])/60,2);
                                                    }
                                                } ?>
                                        <?php }  ?>
                                    <td><?php if (strpos($sum, '.') == false) { echo $sum.'.00';}else {echo $sum;} ?></td>
                                    <input type="hidden" name="hidden_cost" id="hidden_cost" value="<?php if (strpos($sum, '.') == false) { echo $this->session->userdata('currency').$sum.'.00';}else {echo $this->session->userdata('currency').$sum;} ?>"/>
                                </tr>
                        <?php break; ?>      
                                                
                <?php } ?>
            </table>  
        </div>
    </div>
    
</div>