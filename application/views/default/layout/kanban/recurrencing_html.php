<script>
	$(document).ready(function(){
		 App.init();
	});
</script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h3>Frequency</h3>
</div>
<div class="modal-body">
	<div class="lightgrey-box ">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<div class="controls">
						<label class="radio line">
							<input type="radio" <?php if($recurrence_detail['recurrence_type'] == '1'){ echo 'checked="checked"'; }?> />Daily
						</label>
						<label class="radio line">
							<input type="radio" <?php if($recurrence_detail['recurrence_type'] == '2'){ echo 'checked="checked"'; } ?> />Weekly
						</label>  
						<label class="radio line">
							<input type="radio" <?php if($recurrence_detail['recurrence_type'] == '3'){ echo 'checked="checked"'; } ?> />Monthly
						</label>  
						<label class="radio line">
							<input type="radio" <?php if($recurrence_detail['recurrence_type'] == '4'){ echo 'checked="checked"'; } ?> />Yearly
						</label> 
					</div>
				</div>
		    </div>
		    <div class="col-md-9" <?php if($recurrence_detail['recurrence_type'] == '1'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> >
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="controls clearfix">
								<div class="set-box1">
									<label class="radio line">
										<input type="radio" <?php if($recurrence_detail['Daily_every_weekday'] == '0'){ echo 'checked="checked"'; } ?> />Every
									</label>
								</div>
							 	<div class="set-box2">
									<input type="text" value="<?php echo $recurrence_detail['Daily_every_day']; ?>" class="m-wrap adj-recm-wrap " /> <span class="txtlabl"> day(s)   </span>
							 	</div>
								
							</div>
					 	</div>
						 
						<div class="form-group">
							<div class="controls clearfix">
								<div  class="set-box1" >
									<label class="radio line">
										<input type="radio" <?php if($recurrence_detail['Daily_every_weekday'] == '1'){ echo 'checked="checked"'; } ?> />Every weekday
									</label>
								</div>
							   	
							</div>
					 	</div> 	 
						
					</div>
				</div>
			</div>
			<div class="col-md-9" <?php if($recurrence_detail['recurrence_type'] == '2'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> >
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="controls clearfix">
								<div class="set-box1">
									<label class="radio line">
										Recur every
									</label>
								</div>
							 	<div class="set-box2">
									<input type="text" value="<?php echo $recurrence_detail['Weekly_every_week_no'];?>" class="m-wrap adj-recm-wrap " /> <span class="txtlabl"> week(s) on :    </span>
							 	</div>
							</div>
					 	</div>
						<?php if(isset($recurrence_detail['Weekly_week_day']) && $recurrence_detail['Weekly_week_day']!=''){
							$Weekly_week_day = explode(',', $recurrence_detail['Weekly_week_day']);
							
						?>
						<div class="form-group">
							<div class="controls clearfix">
								<div  class="set-box1" >
									<label class="radio line">
										<input type="checkbox" <?php if(in_array('1', $Weekly_week_day)){ echo "checked='checked'"; } ?> />
										Monday
									</label>
								</div>
								<div  class="set-box1" >
									<label class="radio line">
										<input type="checkbox" <?php if(in_array('2', $Weekly_week_day)){ echo "checked='checked'"; } ?> />
										Tuesday
									</label>
								</div>
							   	<div  class="set-box1" >
									<label class="radio line">
										<input type="checkbox" <?php if(in_array('3', $Weekly_week_day)){ echo "checked='checked'"; } ?> />
										Wednesday
									</label>
								</div>
								<div  class="set-box1" >
									<label class="radio line">
										<input type="checkbox" <?php if(in_array('4', $Weekly_week_day)){ echo "checked='checked'"; } ?> />
										Thursday
									</label>
								</div>
							</div>
					 	</div> 	 
						 
						<div class="form-group">
							<div class="controls clearfix">
								<div  class="set-box1" >
									<label class="radio line">
										<input type="checkbox" <?php if(in_array('5', $Weekly_week_day)){ echo "checked='checked'"; } ?> />
										Friday
									</label>
								</div>
								<div  class="set-box1" >
									<label class="radio line">
										<input type="checkbox" <?php if(in_array('6', $Weekly_week_day)){ echo "checked='checked'"; } ?> />
										Saturday
									</label>
								</div>
							   	<div  class="set-box1" >
									<label class="radio line">
										<input type="checkbox" <?php if(in_array('7', $Weekly_week_day)){ echo "checked='checked'"; } ?> />
										Sunday
									</label>
								</div>
							</div>
						 </div>
						 <?php } ?>
					</div>
				</div>
			</div>
			<div class="span9" <?php if($recurrence_detail['recurrence_type'] == '3'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> >
				<div class="row">
					<div class="col-md-12">
						<div class="form-group" >
							<div class="controls clearfix">
								<div class="set-box1">
									<label class="radio line">
										<input type="radio" <?php if(isset($recurrence_detail['monthly_radios']) && $recurrence_detail['monthly_radios']=='1'){ echo "checked='checked'"; } ?> />
										Day
									</label>
								</div>
								<div id="monthly_op1">
								 	<div class="set-box2">
										<input type="text" value="<?php echo $recurrence_detail['Monthly_op1_1'];?>" class="m-wrap adj-recm-wrap " /> <span class="txtlabl"> of every    </span>
								 	</div>
									<div class="set-box2">
										<input type="text" value="<?php echo $recurrence_detail['Monthly_op1_2']; ?>" class="m-wrap adj-recm-wrap " /> <span class="txtlabl"> month(s)    </span>
								 	</div>
							 	</div>
							</div>
					 	</div>
						 
						<div class="form-group" >
							<div class="controls clearfix">
								<div  class="set-box1" >
									<label class="radio line">
										<input type="radio" <?php if(isset($recurrence_detail['monthly_radios']) && $recurrence_detail['monthly_radios']=='2'){ echo "checked='checked'"; } ?> />
										The
									</label>
								</div>
							   	<div class="set-box2" id="monthly_op2">
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option><?php echo $recurrence_detail['Monthly_op2_1'];?></option>
									</select>
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option><?php echo $recurrence_detail['Monthly_op2_2'];?></option>
									</select>
									<input type="text" value="<?php echo $recurrence_detail['Monthly_op2_3'];?>" class="m-wrap adj-recm-wrap " />  <span class="txtlabl"> month(s)    </span>
							 	</div>
							</div>
					 	</div> 	 
						 
						<div class="form-group">
							<div class="controls clearfix">
								<div class="set-box1" >
									<label class="radio line">
										<input type="radio" <?php if(isset($recurrence_detail['monthly_radios']) && $recurrence_detail['monthly_radios']=='3'){ echo "checked='checked'"; } ?> />
										Working day
									</label>
								</div>
								<div class="set-box2">
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option><?php echo $recurrence_detail['Monthly_op3_1'];?></option>
									</select>
									<span class="txtlabl">of every </span>
								</div>	
							 	<div class="set-box2">		
									<input type="text" value="<?php echo $recurrence_detail['Monthly_op3_2'];?>" class="m-wrap adj-recm-wrap " />  <span class="txtlabl"> month(s)    </span>
							 	</div>
							</div>
						 </div>
					</div>
				</div>
			</div>
			<div class="col-md-9" <?php if($recurrence_detail['recurrence_type'] == '4'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> >
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="controls clearfix">
								<div class="set-box1">
									<label class="radio line">
										<input type="radio" <?php if(isset($recurrence_detail['yearly_radios']) && $recurrence_detail['yearly_radios']=='1'){ echo "checked='checked'"; } ?> />
										Recur every
									</label>
								</div>
							 	<div class="set-box2">
									<input type="text" value="<?php echo $recurrence_detail['Yearly_op1'];?>" class="m-wrap adj-recm-wrap " /> <span class="txtlabl"> year(s)   </span>
							 	</div>
							</div>
					 	</div>
						 
						<div class="form-group">
							<div class="controls clearfix">
								<div  class="set-box1" >
									<label class="radio line">
										<input type="radio" <?php if(isset($recurrence_detail['yearly_radios']) && $recurrence_detail['yearly_radios']=='2'){ echo "checked='checked'"; } ?> />
										on 
									</label>
								</div>
							   	<div class="set-box2">
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '1'){ echo 'selected="selected"'; } ?>>January</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '2'){ echo 'selected="selected"'; } ?>>February</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '3'){ echo 'selected="selected"'; } ?>>March</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '4'){ echo 'selected="selected"'; } ?>>April</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '5'){ echo 'selected="selected"'; } ?>>May</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '6'){ echo 'selected="selected"'; } ?>>June</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '7'){ echo 'selected="selected"'; } ?>>July</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '8'){ echo 'selected="selected"'; } ?>>August</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '9'){ echo 'selected="selected"'; } ?>>September</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '10'){ echo 'selected="selected"'; } ?>>October</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '11'){ echo 'selected="selected"'; } ?>>November</option>
										<option <?php if($recurrence_detail['Yearly_op2_1'] == '12'){ echo 'selected="selected"'; } ?>>December</option>
									</select>
									<input type="text" value="<?php echo $recurrence_detail['Yearly_op2_2']; ?>" class="m-wrap adj-recm-wrap " />
							 	</div>
							</div>
					 	</div> 	 
						 
						<div class="form-group">
							<div class="controls clearfix">
								<div class="set-box1" >
									<label class="radio line">
										<input type="radio" <?php if(isset($recurrence_detail['yearly_radios']) && $recurrence_detail['yearly_radios']=='3'){ echo "checked='checked'"; } ?> />
										The
									</label>
								</div>
							  	<div class="set-box2">
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option><?php echo $recurrence_detail['Yearly_op3_1'];?></option>
									</select>
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option><?php echo $recurrence_detail['Yearly_op3_2'];?></option>
									</select>
									<span class="txtlabl">of &nbsp;</span>
								</div>	
							 	<div class="set-box2">		
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option><?php echo $recurrence_detail['Yearly_op3_3'];?></option>
									</select>
							 	</div>
							</div>
						 </div>
						 
						 <div class="form-group">
							<div class="controls clearfix">
								<div class="set-box1" >
									<label class="radio line">
										<input type="radio" <?php if(isset($recurrence_detail['yearly_radios']) && $recurrence_detail['yearly_radios']=='4'){ echo "checked='checked'"; } ?> />
										The working day
									</label>
								</div>
							  	<div class="set-box2">
									<select class="m-wrap adj-recm-wrap2 no-margin" >
										<option><?php echo $recurrence_detail['Yearly_op4_1'];?></option>
									</select>
									<span class="txtlabl">of &nbsp;</span>
									<select class="m-wrap adj-recm-wrap2 no-margin">
										<option><?php echo $recurrence_detail['Yearly_op4_2'];?></option>
									</select>
									
								</div>	
							 	
							</div>
						 </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="recurring_change">
		<h6 class="heading6 no-padding">  <strong> Range of Recurrence  </strong>  </h6>
			<div class="row">
				<div class="col-md-6 ">
					<div class="form-group no-margin">
						<label class="control-label">Start on :</label> 
						<div class="controls">
							<input class="m-wrap m-ctrl-medium " readonly size="16" type="text" value="<?php if($recurrence_detail['start_on_date']!= '0000-00-00'){ echo date($site_setting_date,strtotime(str_replace(array("/"," ",","), "-", $recurrence_detail['start_on_date']))); } else { echo ''; }?>" />
						</div>
					</div>
				</div> 
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="controls">
							<label class="radio line">
								<input type="radio" <?php if($recurrence_detail['no_end_date'] == '1'){ echo 'checked="checked"'; }  ?> />
								No End Date
							</label>
						</div>
					</div>	 
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="controls">
							<label class="radio line">
								<input type="radio" <?php if($recurrence_detail['no_end_date'] == '2'){ echo 'checked="checked"'; } ?> />
								End After
							</label>
						</div>
					</div>	 
				</div>
				<div class="col-md-5">
					<input type="text" value="<?php if($recurrence_detail['end_after_recurrence']>0){ echo $recurrence_detail['end_after_recurrence']; } else { echo ''; }?>" class="m-wrap adj-recm-wrap " /> <span class="txtlabl"> Recurrence    </span>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="controls">
							<label class="radio line">
								<input type="radio" <?php if($recurrence_detail['no_end_date'] == '3'){ echo 'checked="checked"'; } ?> />
								End By
							</label>
							
						
						</div>
					</div>	 
				</div>
				
				<div class="col-md-5">
					<input class="m-wrap m-ctrl-medium " readonly size="16" type="text" value="<?php if($recurrence_detail['end_by_date']!= '0000-00-00'){ echo date($site_setting_date,strtotime($recurrence_detail['end_by_date'])); } else { echo ''; }?>" />
				</div>
			</div>
	</div>
</div>
