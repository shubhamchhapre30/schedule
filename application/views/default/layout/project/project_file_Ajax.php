<?php 
$bucket = $this->config->item('bucket_name');
$s3_display_url = $this->config->item('s3_display_url');
if(isset($files) && $files!= ''){
	foreach($files as $files){
		if($files['file_link']){ ?>
			<tr>
				<td width="8%" class="text-center">
					<a href="<?php echo $files['file_link'];?>" target="_blank">
						<img src="<?php echo base_url().getThemeName();?>/assets/img/link.png" />
				</a></td>
  				<td>
					<p class="txt-normal"><strong><a href="<?php echo $files['file_link'];?>" target="_blank"><?php echo $files['task_file_name'];?></a></strong></p>
					<p> by <?php echo $files['first_name']." ".$files['last_name']; ?> a <?php echo time_ago($files['file_date_added']);?>.   </p>
				</td>
	 			<td width="15%">
	 				<?php if($files['file_added_by']==get_authenticateUserID()){?>
					<a onclick="setval('<?php echo $files['task_file_id'];?>');" data-toggle="modal" href="#task_file-replace" data-dismiss="modal" ><i class="stripicon iconrefresh" style="transform: scale(0.75); "></i></a>
		 			<a href="javascript:;" onclick="delete_project_file('<?php echo $files['task_file_id'];?>')" id="project_file_<?php echo $files['task_file_id'];?>"> <i class="icon-trash tmsticn"></i></a>
		 			<?php } ?>
	  			</td>
  			</tr>
  			<?php
			
		} else {
			$name = 'upload/task_project_files/'.$files['task_file_name'];
			$chk = $this->s3->getObjectInfo($bucket,$name);
			if($chk){
				$info = new SplFileInfo($s3_display_url.'upload/task_project_files/'.$files['task_file_name']);
				$ext = $info->getExtension();
				
				?>
				<tr>
					<td width="8%" class="text-center">
						<a href="<?php echo $s3_display_url.'upload/task_project_files/'.$files['task_file_name'];?>" target="_blank">
						<?php if($ext == 'csv'){ ?>
							<img src="<?php echo base_url().getThemeName();?>/assets/img/csv.png" />
						<?php	} elseif($ext == 'pdf'){ ?>
							<img src="<?php echo base_url().getThemeName();?>/assets/img/pdf.png" />
						<?php } elseif($ext == 'xls' || $ext == 'xlsx' || $ext == 'xl'){ ?>
							<img src="<?php echo base_url().getThemeName();?>/assets/img/excel.png" />
						<?php } elseif($ext == 'doc' || $ext == 'docx' || $ext == 'word'){ ?>
							<img src="<?php echo base_url().getThemeName();?>/assets/img/icon2.png" />
						<?php } elseif($ext == 'png' || $ext == 'jpe' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'bmp' || $ext == 'jpeg'){ ?>
							<img src="<?php echo base_url().getThemeName();?>/assets/img/images.jpg" />
						<?php } else { ?>
							<img src="<?php echo base_url().getThemeName();?>/assets/img/document_icon.png" />
						<?php } ?>
					</a></td>
	  				<td>
						<p class="txt-normal"><strong><a href="<?php echo $s3_display_url.'upload/task_project_files/'.$files['task_file_name'];?>" target="_blank"><?php echo ($files['file_title']!='')?$files['file_title']:$files['task_file_name'];?></a></strong></p>
						<p> by <?php echo $files['first_name']." ".$files['last_name']; ?> a <?php echo time_ago($files['file_date_added']);?>.  <?php if($chk['size'] < 1024){ echo $chk['size'].' bytes'; } else { echo round($chk['size']/1024).'KB'; } ?> </p>
					</td>
		 			<td width="15%">
                                            <a href="<?php echo $s3_display_url.'upload/task_project_files/'.$files['task_file_name'];?>" target="_blank"> <i class="stripicon icondownlaod" style="transform: scale(0.75); "></i> </a> 
						<?php if($files['file_added_by']==get_authenticateUserID()){?>
						<a onclick="setval('<?php echo $files['task_file_id'];?>');" data-toggle="modal" href="#task_file-replace" data-dismiss="modal" ><i class="stripicon iconrefresh" style="transform: scale(0.75); "></i></a>
			 			<a href="javascript:;" onclick="delete_project_file('<?php echo $files['task_file_id'];?>')" id="project_file_<?php echo $files['task_file_id'];?>"> <i class="icon-trash tmsticn"></i> </a>
			 			<?php } ?>  
		  			</td>
	  			</tr>
	  			
		<?php }
		}
		 }} else { ?>
			<tr><td colspan="3">Drag & drop your files here to upload.</td></tr>
	  <?php }
	   ?>