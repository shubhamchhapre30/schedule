<?php $theme =  getThemeName();
	$theme_url = base_url().getThemeName();?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/css/bootstrap-editable.css?Ver=<?php echo VERSION;?>" />
<script src="<?php echo $theme_url;?>/assets/plugins/jquery.mockjax.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/plugins/bootstrap-fileupload/bootstrap-editable-1.5.1/bootstrap-editable/js/bootstrap-editable.min.js?Ver=<?php echo VERSION;?>"></script>
<script src="<?php echo $theme_url;?>/assets/scripts/form-editable.js?Ver=<?php echo VERSION;?>"></script>
<?php  

date_default_timezone_set($this->session->userdata("User_timezone")); 
$site_setting_date = $this->config->item('company_default_format');
$is_av = 0;

if(isset($from_date) && isset($to_date))
{
    $one = strtotime($from_date); // or your date as well
 $two = strtotime($to_date);
$datediff = $two - $one;
$no_of_days = floor($datediff / (60 * 60 * 24));
}else
{
    $no_of_days=29;
    $to_date=date("Y-m-d");
}
    for($i=0;$i<=$no_of_days;$i++){
	if($i==0){
		$date = $to_date;
	} else {
		$date = date("Y-m-d",strtotime('- 1 days', strtotime($date)));
	}
	date_default_timezone_set("UTC"); 
	$logs = get_user_work_log($date);
        
	
	if($logs){
		
		?>
		<div class="comment-block margin-bottom-20">
			<div class="comment-title">
				<?php
                                if($date==date('Y-m-d'))
                                    echo "Today";
                                else if($date==date("Y-m-d",strtotime('- 1 days')))
                                        echo "Yesterday";
                                else
                                    echo date($site_setting_date,strtotime($date));
				 ?>
			</div>
			<div>
				<ul class="list-unstyled">
					<?php foreach($logs as $log){ 
						
						?>
						<li class="light"> 
							<div class="left_log form-horizontal">
								<div class="txt" ><?php echo get_task_title($log->task_id);?></div>
                                                                <div class="controls relative-position" id="<?php echo $log->timer_logs_id;?>">
                                                                    <a href="javascript:void(0)"  class="txt-style work_log_comment edit_title" id="<?php echo $log->timer_logs_id;?>" data-type="text" data-pk="1" data-original-title="<?php echo $log->comment;?>" data-emptytext="Add Comment" data-inputclass="comment_edit"><?php if($log->comment!='') echo $log->comment;?></a>
                                                                    <span class="input-load" id="work_log_comment_loading"></span>
                                                                </div>
								<div class="desc">Stopped Timer with reason  "<?php echo $log->interruption;?>" </div>
							</div>
							
							<div class="right_log"><?php echo date('jS M Y g:i a',strtotime(toDateNewTime($log->date_added)));?></div>
							
							<div class="right_log">
								<?php 
								$time_a = explode(':',$log->spent_time);
								$hr = $time_a[0];
								$min = $time_a[1];
								$se = $time_a[2];
								if($hr>0){
									$time = $hr.'h '.$min.'m '.$se.'s';
								} elseif($min>0){
									$time = $min.'m '.$se.'s'; 
								} elseif($se>0){
									$time = $se.'s';
								} else {
									$time = '0s';
								}
								echo $time;
								?>
							</div>
                                                        
							<div class="clearfix"></div>
						</li>
                                                
                                        <?php 
                                      
                                                                } ?>
				</ul>
			</div>
			<div class="clearfix"> </div>
		</div>
		<?php
		$is_av = 1;
	}
}


if($is_av == 0){
	?>
	<div class="no_data">No data available.</div>
	<?php
}

?>

<script>

$(document).ready(function(){
      
        $('.work_log_comment').editable({
	       url: SIDE_URL+"task/edit_comment",
		   inputclass: 'comment_edit',
	       type: 'post',
	       pk: 1,
	       mode: 'inline',
	       showbuttons: true,
	       validate: function (value) {
	         	if ($.trim(value) == ''){ return 'This field is required';};
	       },
	       success : function(DivisionData){
	       }
	   });
             
});

</script>