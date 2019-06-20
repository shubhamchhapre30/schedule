<?php 
                    	
                    	if($watchlist!='0'){
                    		foreach ($watchlist as $w) {
                    			
								
                    		?>
                      <tr >
                        <td title="<?php echo $w->task_description;?>"><?php echo ucwords($w->task_title);?></td>
                        <td><?php echo date($site_setting_date,strtotime($w->task_due_date));?></td>
                        <td ><?php echo ucwords($w->first_name)." ".ucwords($w->last_name);?></td>
						<?php 
                        foreach($task_status as $ts){
                        if($ts->task_status_id == $w->task_status_id){
                        		
                        	if($ts->task_status_name=='Not Ready')
							{
								$tsk_st = "notready";
							}
							if($ts->task_status_name=='Ready')
							{
								$tsk_st = "ready";
							}
							if($ts->task_status_name=='In Progress')
							{
								$tsk_st = "inprogress";
							}
							
							if( $ts->task_status_name!='In Progress' && $ts->task_status_name!='Ready' && $ts->task_status_name!='Not Ready')
							{
								$tsk_st = "common";
							}
                        ?>
                        <td><span class="label label-<?php echo $tsk_st;?>"><?php echo $ts->task_status_name;?></span></td>
                        <?php } } ?>
                        <td> <a onclick="delwatch('<?php echo $w->id;?>');" href="javascript:;"> <i class="icon-trash stngicn"></i> </a> </td>
                      </tr>
                      <?php } } ?>