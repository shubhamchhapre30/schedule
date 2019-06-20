<?php
if($capacity<0 || $capacity == 'NaN')
    $capacity=480;
if($total_estimate<0 || $total_estimate == 'NaN')
    $total_estimate=0;
if($total_spent<0 || $total_spent == 'NaN')
    $total_spent=0;
 $estcolor=($total_estimate*100)/$capacity;
if($total_estimate!=0)
    $spentcolor=($total_spent*100)/$total_estimate;
else 
    $spentcolor=($total_spent*100)/$capacity;
                            if($capacity>$total_estimate)
                            {
                            ?>
    <div id="capacity_<?php echo $date;?>" data-time="<?php echo $capacity;?>" data-html="true" class="progress tooltips" style="margin-bottom:0px;background-color: #ebeaea;" data-original-title="<?php echo $title;?>">
        <?php if($total_estimate!=0)
        {?>
    <div id="est_<?php echo $date;?>" data-time="<?php echo $total_estimate;?>" class="progress-bar" role="progressbar" style="width: <?php echo $estcolor;?>%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
        <div id="spent_<?php echo $date;?>" data-time="<?php echo $total_spent;?>" class="progress-bar bg-success" role="progressbar" style="width: <?php echo $spentcolor;?>%;background-color: #5cb85c!important;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
        <?php }
        else {?>
    <div id="est_<?php echo $date;?>" data-time="<?php echo $total_estimate;?>" class="progress-bar" role="progressbar" style="width: <?php echo $estcolor;?>%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
    <div  id="spent_<?php echo $date;?>" data-time="<?php echo $total_spent;?>"  class="progress-bar bg-success" role="progressbar" style="width: <?php echo $spentcolor;?>%;background-color: #5cb85c!important;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>

                            <?php
        }?>
    </div><?php }
                            else
                            {
                                $spentcolor=($total_spent*100)/$total_estimate;
                                ?>
<div data-html="true" id="est_<?php echo $date;?>" data-time="<?php echo $total_estimate;?>" class="progress tooltips"  data-original-title="<?php echo $title;?>" style="background-color: red!important; margin-bottom:0px;">
    <div class="progress-bar" data-time="<?php echo $capacity;?>" id="capacity_<?php echo $date;?>"  role="progressbar" style="width: <?php echo ($capacity*100)/$total_estimate;?>%;"  aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar bg-success" role="progressbar" id="spent_<?php echo $date;?>" data-time="<?php echo $total_spent;?>"  style="width: <?php echo $spentcolor;?>%;background-color: #5cb85c!important;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
 </div>
                                                                                               <?php  }?>
