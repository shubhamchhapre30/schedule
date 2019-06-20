<?php if(isset($users) && $users!=''){
    if($project_id){
        if(is_user_project_owner(get_authenticateUserID(),$project_id)){?>
        <option value="all">All</option>
           <?php  foreach($users as $one){
		?>
		<option value="<?php echo $one->user_id;?>"><?php echo $one->first_name.' '.$one->last_name;?></option>
		<?php
	} }
 else {?>
                <option value="<?php echo get_authenticateUserID();?>"><?php echo 'Me';?></option>
     <?php 
 }
        
    
    ?><?php 
	
        }
        else
        {
            $chk_owner = $this->session->userdata('is_administrator');
            $chk_manager = $this->session->userdata('is_manager');
                                                                ?>
         <option value="<?php echo get_authenticateUserID();?>">Me</option>
            <?php if($chk_owner=='1' || $chk_manager == '1') {
                    $users = get_users_under_managers();
                    if($users){
                            foreach($users as $u){
                                if($u->user_id != get_authenticateUserID()){
                                    ?>
                                    <option value="<?php echo $u->user_id;?>"><?php  echo $u->first_name.' '.$u->last_name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                            <option value="">My Team</option>
                            <?php
                    }
                    if($chk_owner=='1'){?>
                    <option value="all">All</option>
                    <?php }
            }
        }
}?>