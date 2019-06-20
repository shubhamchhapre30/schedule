<?php $chk_watch_list = check_my_watch_list($task_id,get_authenticateUserID());
if($chk_watch_list){ ?>
	<a href="javascript:void(0)" onclick="delete_watchlist(<?php echo htmlspecialchars(json_encode($kanban)) ?>);"><i class="stripicon startyellowicon"> </i></a> 
<?php } else { ?>
	<a href="javascript:void(0)" onclick="insert_watchlist(<?php echo htmlspecialchars(json_encode($kanban)) ?>)" ><i class="stripicon startgreyicon"> </i></a> 
<?php } ?>