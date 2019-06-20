
<?php 
$last_rember_values = get_user_last_rember_values();
if($last_rember_values){
			$kanban_project_id = $last_rember_values->kanban_project_id;
			$calender_project_id = $last_rember_values->calender_project_id;
			$rem_task_status_id = $last_rember_values->task_status_id;
			$due_task = $last_rember_values->due_task;
			$kanban_team_user_id = $last_rember_values->kanban_team_user_id;
			$calender_team_user_id = $last_rember_values->calender_team_user_id;
			$sidbar_collapsed = $last_rember_values->sidbar_collapsed;
			$last_calender_view = $last_rember_values->last_calender_view;
			$calender_sorting = $last_rember_values->calender_sorting;
			$user_color_id = $last_rember_values->user_color_id;
			$cal_user_color_id = $last_rember_values->cal_user_color_id;
		} else {
			$kanban_project_id = '';
			$calender_project_id = '';
			$rem_task_status_id = '';
			$due_task = '';
			$kanban_team_user_id = '';
			$calender_team_user_id = '';
			$sidbar_collapsed = '0';
			$last_calender_view = '1';
			$calender_sorting = '1';
			$user_color_id = '0';
			$cal_user_color_id = '0';
		}
       if($ids=='all'){
            $user_all=get_users_under_managers(); ?>
            <div class="filter-listing">
		<select class="col-md-10 m-wrap no-margin" name="kanban_team_user_id" id="kanban_team_user_id" tabindex="1">
			<option value="<?php echo $this->session->userdata('user_id');?>" <?php if($this->session->userdata('user_id') == $kanban_team_user_id){ echo 'selected="selected"'; }?> ><?php echo "My Task";?></option>
				<?php if($user_all){
				foreach($user_all as $user){
                                   if($user->user_id != $kanban_team_user_id){
					?>
					<option value="<?php echo $user->user_id;?>"><?php  echo $user->first_name.' '.$user->last_name; ?></option>
					<?php }
                                   }
                                   }?>
		</select>
            </div>
       <?php }  else {
     
        $users=array();
        $users=get_user_under_project($ids);

?>
<div class="filter-listing">
	<select class="col-md-10 m-wrap no-margin" name="kanban_team_user_id" id="kanban_team_user_id" tabindex="1">
		<option value="<?php echo $this->session->userdata('user_id');?>" <?php if($this->session->userdata('user_id') == $kanban_team_user_id){ echo 'selected="selected"'; }?> ><?php echo "My Task";?></option>
		<option value="#"  ><?php echo "Project Team";?></option>	
                <?php foreach($users as $user){
                       if($user->user_id != get_authenticateUserID()) { ?>
                <option value = <?php echo $user->user_id;?>><?php echo $user->first_name.' '.$user->last_name;?></option>
                       <?php   } }
                    
?>
	</select>
       </div><?php }?>
<script>
    $("#kanban_team_user_id").on('change',function(){
        $('#common-teambox').hide();
            $('#dvLoading').fadeIn('slow');
           $.ajax({
		type : 'post',
		url : '<?php echo site_url('kanban/searchDueTask');?>',
		data : $('#last_remember').serialize(),
		success : function(data){ 
                    $("#kanban_view").html(data);
                    if($("#kanban_team_user_id").val() != <?php echo get_authenticateUserID();?>){
                        $("#kanban_team_user_id").parents('li').children('a').addClass('filter_selected');
                        $("#kanban_team_user_id").parents('li').children('a').children('i').addClass('filtericon-red');
                        $("#kanban_team_user_id").parents('li').children('a').children('i').removeClass('filtericon');
                    }else{
                        $("#kanban_team_user_id").parents('li').children('a').removeClass('filter_selected');
                        $("#kanban_team_user_id").parents('li').children('a').children('i').removeClass('filtericon-red');
                        $("#kanban_team_user_id").parents('li').children('a').children('i').addClass('filtericon');
                    }
                    $('#dvLoading').fadeOut('slow');
                }
            });
    
    });
</script>
    