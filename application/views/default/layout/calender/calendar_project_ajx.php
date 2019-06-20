<!--Adding new file for calender filter-->
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
            $users = get_users_under_managers();
            
            ?>
            <div class="filter-listing">
		<select class="col-md-10 m-wrap no-margin" name="calender_team_user_id" id="calender_team_user_id" tabindex="1">
                    <option value="<?php echo $this->session->userdata('user_id');?>"  <?php if($calender_team_user_id == get_authenticateUserID()){echo 'selected="selected"'; }?>selected="selected"><?php echo "My Task";?></option>
			<?php if($users){
				foreach($users as $u){?>
                                <option value="<?php echo $u->user_id;?>"><?php  echo $u->first_name.' '.$u->last_name; ?></option>
				<?php 
                                }
                                }?>
		</select>
            </div>
           
      <?php  }
        else{
           $users=array();
           $users=get_user_under_project($ids);
      
?>
<div class="filter-listing">
	<select class="col-md-10 m-wrap no-margin" name="calender_team_user_id" id="calender_team_user_id" tabindex="1">
            <option value="<?php echo $this->session->userdata('user_id');?>"  ><?php echo "My Task";?></option>
		<option value="#" <?php if($calender_team_user_id == '0'){ echo 'selected="selected"'; }?> ><?php echo "Project Team";?></option>	
                <?php foreach($users as $user){ ?>
                <?php if($user->user_id != get_authenticateUserID()){?>
                     <option value = <?php echo $user->user_id;?> <?php if($calender_team_user_id == $user->user_id){ echo 'selected="selected"'; }?> ><?php echo $user->first_name." ".$user->last_name;?></option>
                    <?php    
                }
                }
?>
	</select>
        </div><?php }?>
<script>
    <?php if($view=='weekView'){?>
    $("#calender_team_user_id").on('change',function(){
        $('#dvLoading').fadeIn('slow');
           //alert($("#calender_team_user_id").val());
            var str = $('#last_remember').serialize();
                    $.ajax({
                            type : 'post',
                            url : '<?php echo site_url("calendar/searchWeekTask"); ?>',
		            data : {str:str,start_date:$("#week_start_date").val(),end_date:$("#week_end_date").val(),action:$("#week_action").val(),active_menu:'<?php echo $view;?>'},
                            success : function(data){ 
				$("#sjcalendar").html(data);
                                if($("#calender_team_user_id").val()!= <?php echo get_authenticateUserID();?>){
                                    $("#calender_team_user_id").parents('li').children('a').addClass('filter_selected');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon-red');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon');
                                }else{
                                    $("#calender_team_user_id").parents('li').children('a').removeClass('filter_selected');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon-red');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon');
                                }
                                $('#dvLoading').fadeOut('slow');
                               }
                        });
    });
    <?php }else{?>
         $("#calender_team_user_id").on('change',function(){
             $('#dvLoading').fadeIn('slow');
            var str = $('#last_remember').serialize();
            $.ajax({
			type : 'post',
			url : '<?php echo site_url("calendar/searchTask"); ?>',
			data : {str:str,year:$("#year").val(),month:$("#month").val()},
			success : function(data){
				$("#sjcalendar").html(data);
                                if($("#calender_team_user_id").val() != <?php echo get_authenticateUserID();?>){
                                    $("#calender_team_user_id").parents('li').children('a').addClass('filter_selected');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon-red');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon');
                                }else{
                                    $("#calender_team_user_id").parents('li').children('a').removeClass('filter_selected');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').removeClass('filtericon-red');
                                    $("#calender_team_user_id").parents('li').children('a').children('i').addClass('filtericon');
                                }
                                $('#dvLoading').fadeOut('slow');
			}
                });
        });
    <?php }?>
</script>
    