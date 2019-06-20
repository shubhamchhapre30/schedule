<?php $keyword_data=($keyword != '1V1')?str_replace('-',' ',$keyword):'';?>

						<?php			 
					$attributes = array('name'=>'actionCity','id'=>'actionCity','data-target'=>'#content');
					echo form_open('City/actionCity',$attributes);?>
			
				<input type="hidden" name="offset" id="offset" value="<?php echo ($offset!='')?$offset:0; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo ($limit>0)?$limit:20; ?>" />
            	<input type="hidden" name="serach_keyword" id="serach_keyword" value="<?php echo $keyword_data; ?>" />
				<input type="hidden" name="serach_option" id="serach_option" value="<?php echo $option; ?>" />
					
            	   <input type="hidden" name="action" id="action" />
				   <input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page;?>"/>
					<table class="table table-bordered table-striped table-condensed flip-content" id="s2">
					<thead class="flip-content">
					<tr>
					<th width="10" align="center"><input type="checkbox" data-set=".checkboxes" class="group-checkable"></th>
					<th class="<?php echo $sort_on=='city_name'?($sort_type=='asc'?'sorting_desc':'sorting_asc'):'sorting'; ?>" data-sort-on="city_name" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">City Name</th>
					<th class="<?php echo $sort_on=='country_name'?($sort_type=='asc'?'sorting_desc':'sorting_asc'):'sorting'; ?>" data-sort-on="country_name" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Country Name</th>
					<th class="<?php echo $sort_on=='state_name'?($sort_type=='asc'?'sorting_desc':'sorting_asc'):'sorting'; ?>" data-sort-on="state_name" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Province</th>
					<th class="<?php echo $sort_on=='ct.status'?($sort_type=='asc'?'sorting_desc':'sorting_asc'):'sorting'; ?>" data-sort-on="ct.status" data-sort-type="<?php echo $sort_type=='asc'?'desc':'asc'; ?>" onclick="getOrderRecord(this);">Status</th>
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
					<td align="center"> <input type="checkbox"  class="checkboxes" name="chk[]" id="titleCheck<?php echo  $row->city_id;?>" value="<?php echo $row->city_id;?>"></td>
				
					<td><?php echo ucwords($row->city_name); ?></td>
					<td><?php echo ucwords($row->country_name); ?></td>
					<td><?php echo ucwords($row->state_name); ?></td>
					<td><span class="label <?php echo ($row->status=='Active')?'label-success':'label-danger'; ?>"><?php echo $row->status; ?></span></td>
					<td class="numeric">
					<p>
						<?php echo ((isset($adminRights->Globalization) && $adminRights->Globalization->update==1) || checkSuperAdmin())? anchor('City/editCity/'.$row->city_id.'/'.$redirect_page.'/'.$option.'/'.$keyword.'/'.$limit.'/'.$offset,'<i class="icon-edit m-icon-white"></i>','class="btn blue icn-only mini"   title="Edit City"'):'';
						if((isset($adminRights->Globalization) && $adminRights->Globalization->delete==1) || checkSuperAdmin()){	 ?>
					
					
					<a href="javascript:void(0);" onClick="delete_rec('<?php echo $row->city_id; ?>','<?php echo $redirect_page;?>','<?php echo $option?>','<?php echo $keyword?>','<?php echo $limit?>','<?php echo $offset; ?>')" title="Delete" class="btn red icn-only mini"><i class="icon-remove icon-white"></i></a>
					<?php } ?>
					</p>
					</td>

					</tr>
					<?php	} }else{?>
					<tr> <td colspan="7"><h3>No Record Found.</h3></td></tr>
					<?php } ?>
					</tbody>
					</table>
				</form>
					<div class="row-fluid">
					<?php echo $page_link
					?>

					</div>