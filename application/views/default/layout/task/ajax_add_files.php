<?php if(isset($files) && $files!= ''){
	
		if($files['file_link']){
			$link = $files['file_link'];
			$is_link = 1;
		} else {
			$bucket = $this->config->item('bucket_name');
			$name = 'upload/task_project_files/'.$files['task_file_name'];
			$s3_display_url = $this->config->item('s3_display_url');
			$chk = $this->s3->getObjectInfo($bucket,$name);
			$ext = '';
			if($chk){
				$info = new SplFileInfo($s3_display_url.'upload/task_project_files/'.$files['task_file_name']);
				$ext = $info->getExtension();
			}
			$is_link = 0;
		}
		if($is_link){ ?>
			<tr class="hasfiles" id="file_tr_<?php echo $files['task_file_id'];?>">
				<td width="8%" class="text-center"><a href="<?php echo $files['file_link'];?>" target="_blank">
					<img src="<?php echo base_url().getThemeName();?>/assets/img/link.png" />
				</a></td>
  				<td>
					<p class="txt-normal"><strong><a href="<?php echo $files['file_link'];?>" target="_blank"><?php echo $files['task_file_name'];?></a></strong></p>
					<p> by <?php echo usernameById($files['file_added_by']); ?> a <?php echo time_ago($files['file_date_added']);?>.</p>
				</td>
	 			<td width="15%">
	 				<?php if($files['file_added_by'] == get_authenticateUserID()){ ?> 
		 			<a href="javascript:;" onclick="delete_task_file('<?php echo $files['task_file_id'];?>')" id="delete_task_file_<?php echo $files['task_file_id'];?>"> <i class="icon-trash taskppicn"></i> </a>  
	  				<?php } ?>
	  			</td>
  			</tr>
		<?php } else { ?>
			<tr class="hasfiles" id="file_tr_<?php echo $files['task_file_id'];?>">
				<td width="8%" class="text-center"><a href="<?php echo $s3_display_url.'upload/task_project_files/'.$files['task_file_name'];?>" target="_blank">
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
					<p> by <?php echo usernameById($files['file_added_by']); ?> a <?php echo time_ago($files['file_date_added']);?>.  <?php if($chk['size'] < 1024){ echo $chk['size'].' bytes'; } else { echo round($chk['size']/1024).'KB'; } ?> </p>
				</td>
	 			<td width="15%">
	 				<?php if($files['file_added_by'] == get_authenticateUserID()){ ?> 
		 			<a href="javascript:;" onclick="delete_task_file('<?php echo $files['task_file_id'];?>')" id="delete_task_file_<?php echo $files['task_file_id'];?>"> <i class="icon-trash taskppicn"></i> </a>  
	  				<?php } ?>
	  			</td>
  			</tr>
		<?php
		}
	
	}
	 ?>