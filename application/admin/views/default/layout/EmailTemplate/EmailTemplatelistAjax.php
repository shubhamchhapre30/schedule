<?php $keyword_data=($keyword != '1V1')?str_replace('-',' ',$keyword):'';?>

						<?php			 
					$attributes = array('name'=>'actionEmailTemplate','id'=>'actionEmailTemplate','data-target'=>'#content');
					echo form_open('EmailTemplate/actionEmailTemplate',$attributes);?>
			
				<input type="hidden" name="offset" id="offset" value="<?php echo ($offset!='')?$offset:0; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
            	<input type="hidden" name="serach_keyword" id="serach_keyword" value="<?php echo $keyword_data; ?>" />
				<input type="hidden" name="serach_option" id="serach_option" value="<?php echo $option; ?>" />
					
            	   <input type="hidden" name="action" id="action" />
				   <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
					<table class="table table-bordered table-striped table-condensed flip-content" id="s2">
					<thead class="flip-content">
					<tr>
					<th width="850">Email Template Name</th>
					<!--<th>Status</th>-->
					<th >Action</th>
					</tr>
					</thead>
					<tbody>

					<?php
				if($result!=''){
				foreach($result as $row) {
					//print_r($row);die;
					?>
					<tr>
					<td> <?php echo ucwords($row->task); ?> </td>	
					
					
					<td class="numeric">
					<p>
						<?php echo ((isset($adminRights->eCommerce) && $adminRights->eCommerce->update==1) || checkSuperAdmin())? anchor('EmailTemplate/editEmailTemplate/'.$row->email_template_id.'/'.$redirect_page.'/'.$option.'/'.$keyword.'/'.$limit.'/'.$offset,'<i class="fa fa-pencil-square-o m-icon-white"></i>','class="btn blue icn-only mini"   title="Edit EmailTemplate"'):'';
						if((isset($adminRights->eCommerce) && $adminRights->eCommerce->delete==1) || checkSuperAdmin()){	 ?>
					
					
					<!--<a href="javascript:void(0);" onClick="delete_rec('<?php echo $row->EmailTemplate_id; ?>','<?php echo $redirect_page;?>','<?php echo $option?>','<?php echo $keyword?>','<?php echo $limit?>','<?php echo $offset; ?>')" title="Delete" class="btn red icn-only mini"><i class="icon-remove icon-white"></i></a>-->
					<?php } ?>
					</p>
					</td>

					</tr>
					<?php	} }else{?>
					<tr> <td colspan="2"><h3>No Record Found.</h3></td></tr>	
					<?php } ?>
					</tbody>
					</table>
				</form>
					<div class="row" style="margin-top: 15px;">
					<?php echo $page_link 
					?>

					</div>
