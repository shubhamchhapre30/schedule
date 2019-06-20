<?php $keyword_data=($keyword != '1V1')?str_replace('-',' ',$keyword):'';?>

<?php			 
					$attributes = array('name'=>'actionAdmin','id'=>'actionAdmin','data-target'=>'#content');
					echo form_open('admin/actionAdmin',$attributes);?>
			
				<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>" />
            	<input type="hidden" name="serach_keyword" id="serach_keyword" value="<?php echo $keyword_data; ?>" />
				<input type="hidden" name="serach_option" id="serach_option" value="<?php echo $option; ?>" />
					
            	   <input type="hidden" name="action" id="action" />
				   <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
					<table class="table-change table-striped table-condensed flip-content" id="s2">
					<thead class="flip-content">
					<tr>
					<th width="10" align="center"><input type="checkbox" data-set=".checkboxes" class="group-checkable"></th>
					<th class="<?php echo $sort_on=='first_name'?($sort_type=='asc'?'sorting_asc':'sorting_desc'):'sorting'; ?>" data-sort-on="first_name" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">First Name</th>
					<th class="<?php echo $sort_on=='last_name'?($sort_type=='asc'?'sorting_asc':'sorting_desc'):'sorting'; ?>" data-sort-on="last_name" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">last Name</th>
					<th class="<?php echo $sort_on=='email'?($sort_type=='asc'?'sorting_asc':'sorting_desc'):'sorting'; ?>" data-sort-on="email" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Email</th>
					<th class="<?php echo $sort_on=='active'?($sort_type=='asc'?'sorting_asc':'sorting_desc'):'sorting'; ?>" data-sort-on="active" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Status</th>
					<?php echo (checkSuperAdmin())?'<th>Rights</th>':'' ?>
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
					<td align="center"> <input type="checkbox"  class="checkboxes" name="chk[]" id="titleCheck<?php echo  $row->admin_id;?>" value="<?php echo $row->admin_id;?>"></td>
					<td><?php echo $row->first_name; ?></td>
					<td><?php echo $row->last_name; ?></td>
                  	<td><?php echo $row->email; ?></td>
					<td><span class="label <?php echo ($row->active=='1')?'label-success':'label-danger'; ?>"><?php echo ($row->active==1)?'Active':'Inactive'; ?></span></td>
					<?php if(checkSuperAdmin()){ ?>
					<td class="numeric">
					<p>
						<?php echo anchor('admin/assignRights/'.$row->admin_id.'/'.$redirect_page.'/'.$option.'/'.$keyword.'/'.$limit.'/'.$offset,'<i class="icon-edit m-icon-white"></i>','class="btn blue icn-only mini"   title="Rights"'); ?>
					</p></td>
					<?php } ?>
					<td class="numeric">
					<p>
						<?php echo ((isset($adminRights->admin) && $adminRights->admin->update==1) || checkSuperAdmin())?anchor('admin/editAdmin/'.$row->admin_id.'/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset,'<i class="icon-edit m-icon-white"></i>','class="btn blue icn-only mini"   title="Edit admin"'):'';
					if((isset($adminRights->admin) && $adminRights->admin->delete==1) || checkSuperAdmin()){	
						 ?>
					
					<a href="javascript:void(0);" onClick="delete_rec('<?php echo $row->admin_id; ?>','<?php echo $redirect_page;?>','<?php echo $option?>','<?php echo $keyword?>','<?php echo $limit?>','<?php echo $offset; ?>')" title="Delete" class="btn red icn-only mini"><i class="icon-remove icon-white"></i></a>
					<?php } ?>
					</p></td>

					</tr>
					<?php	} }else{?>
					<tr> <td colspan="<?php echo (checkSuperAdmin())?'7':'6';  ?>"><h3>No Record Found.</h3></td></tr>	
					<?php } ?>
					
					</tbody>
					</table>
				</form>
								<div class="row" style="margin-top: 15px;">
									<?php echo $page_link ?>
							
						</div>

