<?php
$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
date_default_timezone_set($this->session->userdata("User_timezone"));
$default_format = $this->config->item('company_default_format');
$date_arr_java = array("d M,Y"=>"dd M,yyyy","Y-m-d"=>"yyyy-mm-dd","m-d-Y"=>"mm-dd-yyyy","d-m-Y"=>"dd-mm-yyyy","Y/m/d"=>"yyyy/mm/dd","m/d/Y"=>"mm/dd/yyyy","d/m/Y"=>"dd/mm/yyyy");
?>
                   <table class="table table-striped table-hover table-condensed flip-content" id="timesheet_viewtable1" >
                                <thead class="flip-content">
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Status</th>
                                        <th>Last Edited</th>
                                        <th>Hours</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="timesheet_list" >
                       <?php if($timesheets_list){?>
                                    <?php foreach ($timesheets_list as $list){?>
                                    <?php $total_timesheet_time = $this->timesheet_model->get_overall_timesheet_time($list['timesheet_user_id'],$list['from_date'],$list['to_date']);
                                          $hours = intval($total_timesheet_time/60);
                                          $minutes = $total_timesheet_time - ($hours * 60);
                                        ?>
                                    <tr id="id_<?php echo $list['timesheet_id']; ?>">
                                        <td><div><input type="checkbox" name="timesheet_check[]" id="timesheet_check" value="<?php echo $list['timesheet_id']; ?>" <?php if($list['timesheet_status'] !='approved' && $list['timesheet_status'] != 'partially_exported'){ echo "disabled='disabled'"; } ?> /></div></td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo $list['first_name'].' '.$list['last_name'];?></a>
                                        </td>
                                        <td><span class="hidden"><?php echo date("Y-m-d",strtotime($list['from_date'])); ?></span><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo date($default_format,  strtotime($list['from_date'])); ?></a></td>
                                        <td><span class="hidden"><?php echo date("Y-m-d",strtotime($list['to_date'])); ?></span><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo date($default_format,  strtotime($list['to_date'])); ?></a></td>
                                        <td id="status_<?php echo $list['timesheet_id']; ?>"><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php if($list['timesheet_status'] == 'partially_exported'){ echo "Partially Exported";}else{ echo ucfirst($list['timesheet_status']);} ?></a></td>
                                        <td><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php if($list['timesheet_updated_date']!='0000-00-00 00:00:00'){echo date($default_format, strtotime($list['timesheet_updated_date'])); }else{ echo '-';} ?></a></td>
                                        <td><a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><?php echo $hours.':'.(strlen($minutes) == 1 ? '0'.$minutes : $minutes); ?></a></td>
                                        <td>
                                            <form method="POST" action="<?php echo site_url('timesheet/showtimesheet');?>" name="myForm_<?php echo $list['timesheet_id'];?>" id="myForm_<?php echo $list['timesheet_id']; ?>">
                                                <input type="hidden" name="timesheet_id" id="timesheet_id" value="<?php echo $list['timesheet_id']; ?>" />
                                            </form>
                                            <a href="javascript:void(0);" onclick="open_timesheet(<?php echo $list['timesheet_id'];?>);" ><i class="icon-pencil tmsticn"  style="transform: scale(0.75);"></i> </a> 
                                            <?php if($this->session->userdata('is_administrator') == '1' || $list['timesheet_user_id'] == $this->session->userdata('user_id')) {?>
                                            <a href="javascript:void(0);" onclick="delete_timesheet(<?php echo $list['timesheet_id'];?>)" id="delete_timesheet_<?php echo $list['timesheet_id'];?>"> <i class="icon-trash  tmsticn" style="transform: scale(0.75);"></i> </a>  
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php }}?>
                                   
                                </tbody>
                                <script>
                                    $(document).ready(function() {    
                                       // initiate layout and plugins
                                      
                                       $. each($("input[name='timesheet_check[]']:disabled"), function(){ 
                                           if('<?php echo $timesheet_status_id; ?>' == 'exported'){
                                               $(this).parent().prop({"class" : "tooltips"}).attr("data-placement","right").attr("data-original-title","Cannot export timesheet that is already exported"); 
                                           }else{
                                               $(this).parent().prop({"class" : "tooltips"}).attr("data-placement","right").attr("data-original-title","Cannot export timesheet that is not approved"); 
                                            }
                                        });                                 
                                    $('.tooltips').tooltip(); 
                                    });
                                </script>
                   </table>
<?php date_default_timezone_set("UTC"); ?>
