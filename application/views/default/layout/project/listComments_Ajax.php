<?php

$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
if($total_history!=''){ ?>



	 <div class="row" style="margin-left: 0px">
	 	<table class="col-md-12 table table-hover">
	 	<?php
				$old_dt = "0000-00-00";
					foreach($history as $h){

								if(date("Y-m-d",strtotime($old_dt))!=date("Y-m-d",strtotime(toDateNewTime($h->project_history_added_date)))){  ?>
								<thead class="flip-content">
								  <tr>
									<th colspan="4" class="bigrow"><?php echo date("jS F Y ",strtotime(toDateNewTime($h->project_history_added_date)));?></th>
								  </tr>
								</thead>
								<tbody>
								<?php
								$old_dt=date("Y-m-d",strtotime(toDateNewTime($h->project_history_added_date)));
								}else{ ?>
									<?php }

								if(date("Y-m-d",strtotime(toDateNewTime($h->project_history_added_date)))!=""){
										$name = 'upload/user/'.$h->profile_image;
										if((isset($h->profile_image) && $h->profile_image!='') && $this->s3->getObjectInfo($bucket,$name)) {
										 	 $src_user =  $s3_display_url.'upload/user/'.$h->profile_image;
							        	  }
							        	  else
										  {
						        		 	 $src_user = $s3_display_url.'upload/user/no_image.jpg';
										  }

										  if($h->history_type =='File'){ $class = "blue";  }
										  if($h->history_type =='Comment') { $class = "yellow"; }
										  if($h->history_type =='Project') { $class = "green"; }
										  if($h->history_type =='User') { $class = "red"; }
										  if($h->history_type =='') { $class = "purple"; }



									 ?>
								<tr>
								<td style="padding-left: 0px; Padding-right: 0px;">
								<div class="row" style="margin-left: 0px">
									<!-- History Type and Date column -->
									<div class="col-md-3" style="padding-left: 0px;">
										<div class="row ">
											<div class="col-md-12" style="padding-bottom: 10px;">
											<span class="tasklbl <?php echo $class;?> sml "><?php echo $h->history_type;?> </span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
											<!--<?php echo date('jS M Y g.i a',strtotime(toDateNewTime($h->project_history_added_date)));?>-->
											<b><?php echo date('g.i A',strtotime(toDateNewTime($h->project_history_added_date)));?></b>
											</div>
										</div>
									</div>
									<!-- History description column -->
									<div class="col-md-7">
										<?php if($h->history_type =='File'){ ?> <em class="underline"> <?php  } ?>
								  		<?php echo $h->project_history_desc;?>
								  		<?php  if($h->history_type =='File'){ ?> </em> <?php } ?>
									</div>
									<!-- Customer Picture column -->
									<div class="col-md-2" style="padding-left: 0px;">
										<img src="<?php echo $src_user;?>" alt="img" title="<?php echo $h->first_name." ".$h->last_name;?>" class="img-circle" />
									</div>
								</div>
									<!--<td width="12%"><span class="tasklbl <?php echo $class;?> sml "><?php echo $h->history_type;?> </span> </td>
								  	<td>
								  		<?php if($h->history_type =='File'){ ?> <em class="underline"> <?php  } ?>
								  		<?php echo $h->project_history_desc;?>
								  		<?php  if($h->history_type =='File'){ ?> </em> <?php } ?>

								  		</td>
									<td width="10%"> <?php echo date('jS M Y g.i a',strtotime(toDateNewTime($h->project_history_added_date)));?></td>
									<td width="12%"> <img src="<?php echo $src_user;?>" alt="img" title="<?php echo $h->first_name." ".$h->last_name;?>" class="img-circle" />  </td>
								--></td>
								</tr>
								<?php  }else{ ?>
								<tr> No history found</tr>
								<?php } ?>


								<?php
								if(date("Y-m-d",strtotime($old_dt))!=date("Y-m-d",strtotime(toDateNewTime($h->project_history_added_date)))){  ?>

								</tbody>
								<?php }?>



							<?php
								$offset++;
							 } ?>
							 </table>
							 </div>

		<div class="clearfix"></div>
                <div id="append_data"></div>
<?php } ?>



<!-- Display No More button -->
	<div id="hid" style="display:none">
		<input type="text" name="offset" id="offset" value="<?php echo $offset; ?>" />
	</div>
