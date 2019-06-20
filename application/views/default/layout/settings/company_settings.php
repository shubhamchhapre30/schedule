
<script>

	$(document).ready(function() { 
            FormEditable.init();
		
		setCompanyDepartment();
                var table =  $("#division_table").dataTable({
                    paging: !1,
                    bFilter: !1,
                    searching: !1,
                    bLengthChange: !1,
                    info: !1,
                    language: {
                        emptyTable: "No Records found."
                    },
                    rowReorder:true
                });
                table.on( 'row-reorder.dt', function ( e, diff, edit ) { 
                    var new_position = edit.values;
                    $.ajax({
			url:SIDE_URL+'settings/set_division_seq',
			type:'post',
			data:{new_position:new_position},
			success:function(responseData){
			}
		    });
                });
	});
</script>
<div class="controls">
    <label class="control-label"><b>Company Divisions</b> </label>
    <p class="alert alert-info" ><strong>Divisions</strong> is often referred to as a profit/expense center and is acountable for both income and expenditure. For example, Finance, IT, Marketing or Sales.
        Another way to Consider the use of Divisions is via Divisional geographical unit such as: Australia, France, UK or USA.
    </p>
    
            <div class="customtable form-horizontal">
              <table class="table table-striped table-hover table-condensed flip-content" id="division_table">
                <thead class="flip-content">
                    <tr>
                        <th></th>
                        <th>Name</th>
			<th>Action</th>
                    </tr>
		</thead>
		<tbody id="company_divisions">
		    <?php $i = 1;
                          if(isset($divisions) && $divisions!=''){
                            foreach($divisions as $division){ ?>
                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    $("#<?php echo $division->division_id; ?>").editable({
                                                            url: '<?php echo site_url("settings/update_division_name");?>',
                                                            type: 'POST',
                                                            pk: 1,
                                                            mode: 'inline',
                                                            showbuttons: true,
                                                            validate: function (value) {
                                                                    if ($.trim(value) == ''){ return 'This field is required'};
                                                                    if (value.length  > 30) { return 'Max length is 30'};
                                                                    var remote = $.ajax({
                                                                                        url: "<?php echo site_url("settings/chk_divisionName_exists");?>",
                                                                                        type: "POST",
                                                                                        async : false,
                                                                                        data: {
                                                                                            name: function(){ return $.trim(value); },
                                                                                            company_id: function(){ return $("#company_id").val(); },
                                                                                            division_id : '<?php echo $division->division_id; ?>'
                                                                                        },
                                                                                        success : function(responseData){
                                                                                            return responseData;
                                                                                        }
                                                                                });
                                                                    if(remote.responseText == "1") return 'There is an existing record with this division name.';
                                                            },
                                                            success : function(DivisionData){
                                                                var DivisionData = jQuery.parseJSON(DivisionData);
                                                                var str = '';
                                                                $.map(DivisionData.divisions, function (item){
                                                                        str += '<option value="'+item.division_id+'">'+item.devision_title+'</option>';
                                                                });
                                                                $("#parent_division").html(str);
                                                            }
                                                        });
                                                });
                                            $(function() {
                                              $("#devision_status_<?php echo $division->division_id; ?>").bootstrapToggle();
                                              $('#devision_status_<?php echo $division->division_id; ?>').bootstrapToggle().on("change",function() {
                                               var t=$(this).prop('checked')?1:0;
                                               changeDivisionStatus('<?php echo $division->division_id; ?>',t);
                                              });
                                            });
                                </script>
                                <tr id="division_<?php echo $division->division_id; ?>">
                                    <td width="3%" style="cursor:pointer"><i class="fa fa-bars" aria-hidden="true"></i><span style="display:none"><?php echo $i; ?></span></td>
                                    <td width="70%">
                                        <a href="javascript:void(0)" class="txt-style" id="<?php echo $division->division_id; ?>" data-type="text" data-pk="1" data-original-title="<?php echo $division->devision_title;?>"><?php echo $division->devision_title;?></a>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="checkbox"  id="devision_status_<?php echo $division->division_id; ?>" <?php echo $division->devision_status=='Active' ? 'checked':'' ;?> data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger" name="devision_status" value="<?php echo $division->devision_status;?>">
                                            <a onclick="delete_division('<?php echo $division->division_id; ?>');" href="javascript:void(0)" id='delete_division_<?php echo $division->division_id; ?>'> <i class="icon-trash stngicn company_icon_black"></i> </a>
                                        </div>
                                    </td>
				</tr>
                    <?php $i++; } }  ?>
                </tbody>
            </table>
            <div>
                <input type="text" class="large m-wrap valid" name="devision_title" id="devision_title" maxlength="30" onkeyup="error_display(this.value,'max_error_divison')">
                <a href="javascript:void(0)" class="btn btn-common-blue sm" id="save_division">Add</a>
                <span id="max_error_divison" style="display:none; color:red;">Max. Length 30 reached.</span>
            </div>    
	</div>
</div>
<div class="controls">
        <label class="control-label dept_company_css" ><b>Company Departments</b> </label>
	<p class="alert alert-info" ><strong>Departments</strong> can be referred to as cost centres. Another definition may be for teams that form part of overall Divisions. For example, if the Finance team was categorised as a 
            Division within schedullo, then you may have Accounts Payable, Payroll, Reporting and Performance teams as Departments within Divisions.
        </p>
        <div class="form-group">
            <label class="control-label col-md-4 dept_label_css" ><b>Parent Company Division  </b></label>
                <div class="controls relative-position" >
                    <select class="large m-wrap radius-b" name="parent_division" tabindex="1" id="parent_division" onchange="setCompanyDepartment();" >
                        <?php if(isset($divisions) && $divisions != ''){
                                foreach($divisions as $division){ ?>
                                <option value="<?php echo $division->division_id; ?>"><?php echo $division->devision_title;?></option>
                        <?php } }?>
                    </select>
                </div>
        </div>
	<div class="form-group">
            
            <div class="controls" id="company_department">
            </div>
	</div>
					
</div>
