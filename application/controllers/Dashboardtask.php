<?php

class DashboardTask extends SPACULLUS_Controller{

	function DashboardTask(){
		parent :: __construct ();
		$this->load->library('s3');
		$this->config->load('s3');
		date_default_timezone_set($this->session->userdata("User_timezone"));
	}
	/*
	 * Function : set_update_task
	 * Author : Spaculus
	 * Desc : This function use to set task div of inserted task
	*/
	function set_update_task(){
		$theme = getThemeName();
                $s3_display_url = $this->config->item('s3_display_url');
                $bucket = $this->config->item('bucket_name');
		
		$task_id = $_POST['task_id'];
		$from_page = isset($_POST['redirect_page'])?$_POST['redirect_page']:'from_dashboard';
		$type = isset($_POST['type'])?$_POST['type']:'';
		$duration = isset($_POST['duration'])?$_POST['duration']:'today';
		
		
		if(strpos($task_id, 'child') !== false){
			$id = preg_replace("/[^0-9]/", '', $task_id);
			$task_id = $id;
			$task_detail = get_task_detail($task_id);
			$task_data = kanban_recurrence_logic($task_detail);
			$data['task_data'] = $task_data;
		} else {
			$data['task_data'] = get_task_detail($task_id);
		}

		if($data['task_data']){
			if($data['task_data']['task_scheduled_date']!= '0000-00-00' ){
				$data['user_scheduled_date'] = date($this->config->item('company_default_format'),strtotime($data['task_data']['task_scheduled_date']));
			} else {
				$data['user_scheduled_date'] = "N/A";
			}
			if($data['task_data']['task_due_date']!= '0000-00-00' ){
				$data['user_due_date'] = date($this->config->item('company_default_format'),strtotime($data['task_data']['task_due_date']));
			} else {
				$data['user_due_date'] = "N/A";
			}
                        
			$data['task_status_name'] = get_task_status_name_by_id($data['task_data']['task_status_id']);
			$owner_name = get_user_name($data['task_data']['task_owner_id']);
                        
                        if(($owner_name->profile_image != '' || $owner_name->profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$owner_name->profile_image)) { 
                            $owner_image = $s3_display_url.'upload/user/'.$owner_name->profile_image;
                           } else {
                            $owner_image = '';
                           } 
                           
                           $data['task_owner_image'] = $owner_image;
                           $data['task_owner_image_name'] = ucfirst(substr($owner_name->first_name,0,1)).ucfirst(substr($owner_name->last_name[0],0,1));
                           $owner_name = ucwords($owner_name->first_name)." ".ucwords($owner_name->last_name);
                           $data['task_owner_name'] = $owner_name;
                           

			$allocated_name = get_user_name($data['task_data']['task_allocated_user_id']);
                        
                        if(($allocated_name->profile_image != '' || $allocated_name->profile_image != NULL) && $this->s3->getObjectInfo($bucket,'upload/user/'.$allocated_name->profile_image)) { 
                        $allocated_user_image = $s3_display_url.'upload/user/'.$allocated_name->profile_image;
                       } else {
                        $allocated_user_image = '';
                       }
                       $data['task_allocated_user_image_name'] = ucfirst(substr($allocated_name->first_name,0,1)).ucfirst(substr($allocated_name->last_name[0],0,1));
                       $allocated_name = ucwords($allocated_name->first_name)." ".ucwords($allocated_name->last_name);
                       $data['task_allocated_user_name'] = $allocated_name;
                       $data['task_allocated_user_image'] = $allocated_user_image;

			$data['delay'] = round(floor(strtotime(date("Y-m-d")) - strtotime($data['task_data']['task_due_date']))/(60*60*24));
			$data['watch_id'] = check_my_watch_list($data['task_data']['task_id'],get_authenticateUserID());

			if($data['task_data']['master_task_id']){
				$data['is_master_deleted'] = chk_master_task_id_deleted($data['task_data']['master_task_id']);
			} else {
				$data['is_master_deleted'] = 0;
			}
			
			$data['strtotime_scheduled_date'] = strtotime($data['task_data']['task_scheduled_date']);
			$data['strtotime_due_date'] = strtotime($data['task_data']['task_due_date']);
			
		}
		
		
		$is_div_valid = 0;
		
		if($type == "" && $duration == "backlog"){
			$reday_id = get_task_status_id_by_name("Reday");
			if($data['task_data']['task_scheduled_date'] == "0000-00-00" && $data['task_data']['task_status_id']!=$reday_id){
				$is_div_valid = 1;
			}
		} else if($type !="" && $duration == "backlog"){
			$reday_id = get_task_status_id_by_name("Reday");
			if($data['task_data']['task_priority'] == $type && $data['task_data']['task_scheduled_date'] == "0000-00-00" && $data['task_data']['task_status_id']!=$reday_id){
				$is_div_valid = 1;
			}
		} else if($type!='' && $duration=='this_week'){
			$d = strtotime("today");
			$start_week = strtotime("last sunday midnight",$d);
			$end_week = strtotime("next saturday",$d);
			if($data['task_data']['task_priority'] == $type && strtotime($data['task_data']['task_scheduled_date']) >= $start_week && strtotime($data['task_data']['task_scheduled_date']) <= $end_week){
				$is_div_valid = 1;
			}
		} elseif($type!='' && $duration == "next_week"){
			$d1 = strtotime("+1 week -1 day");
			$start_week = strtotime("last sunday midnight",$d1);
			$end_week = strtotime("next saturday",$d1);
			if($data['task_data']['task_priority'] == $type && strtotime($data['task_data']['task_scheduled_date']) >= $start_week && strtotime($data['task_data']['task_scheduled_date']) <= $end_week){
				$is_div_valid = 1;
			}
		} elseif($type!='' && $duration == "this_month"){
			$start_week = strtotime(date("Y-m-01"));
			$end_week = strtotime(date("Y-m-t"));
			if($data['task_data']['task_priority'] == $type && strtotime($data['task_data']['task_scheduled_date']) >= $start_week && strtotime($data['task_data']['task_scheduled_date']) <= $end_week){
				$is_div_valid = 1;
			}
		} elseif($type!='' && $duration == 'today'){
			$start_week = strtotime(date("Y-m-d"));
			$end_week = strtotime(date("Y-m-d"));
			if($data['task_data']['task_priority'] == $type && strtotime($data['task_data']['task_scheduled_date']) >= $start_week && strtotime($data['task_data']['task_scheduled_date']) <= $end_week){
				$is_div_valid = 1;
			}
		} elseif($type!='' && $duration == 'overdue'){
			$start_week = user_first_login_date();
			$end_week = date('Y-m-d');
			if($data['task_data']['task_priority'] == $type && $data['task_data']['task_due_date'] >= $start_week && $data['task_data']['task_scheduled_date'] < $end_week && $data['task_data']['task_status_id'] != $this->config->item('completed_id')){
				$is_div_valid = 1;
			}
		} elseif($type == '' && $duration == 'this_week'){
			$d = strtotime("today");
			$start_week = strtotime("last sunday midnight",$d);
			$end_week = strtotime("next saturday",$d);
			if(strtotime($data['task_data']['task_scheduled_date']) >= $start_week && strtotime($data['task_data']['task_scheduled_date']) <= $end_week){
				$is_div_valid = 1;
			}
		} elseif($type == '' && $duration == 'next_week'){
			$d1 = strtotime("+1 week -1 day");
			$start_week = strtotime("last sunday midnight",$d1);
			$end_week = strtotime("next saturday",$d1);
			if(strtotime($data['task_data']['task_scheduled_date']) >= $start_week && strtotime($data['task_data']['task_scheduled_date']) <= $end_week){
				$is_div_valid = 1;
			}
		} elseif($type == '' && $duration == 'this_month'){
			$start_week = strtotime(date("Y-m-01"));
			$end_week = strtotime(date("Y-m-t"));
			if(strtotime($data['task_data']['task_scheduled_date']) >= $start_week && strtotime($data['task_data']['task_scheduled_date']) <= $end_week){
				$is_div_valid = 1;
			}
		} elseif($type == '' && $duration == 'overdue'){
			$completed_id = $this->config->item('completed_id');
			$start_week = user_first_login_date();
			$end_week = date('Y-m-d');
			if($data['task_data']['task_due_date'] >= $start_week && $data['task_data']['task_due_date'] < $end_week && $data['task_data']['task_status_id'] != $this->config->item('completed_id')){
				$is_div_valid = 1;
			}
		} else {
			if(strtotime($data['task_data']['task_scheduled_date']) >= strtotime(date("Y-m-d")) && strtotime($data['task_data']['task_scheduled_date']) <= strtotime(date("Y-m-d"))){
				$is_div_valid = 1;
			}
		}

		if($from_page == "from_dashboard"){
			if(($data['task_data']['task_allocated_user_id'] != get_authenticateUserID())){
				$data['assign_status'] = "assign_other";
			} else {
				$data['assign_status'] = "";
			}
			$data['is_div_valid'] = $is_div_valid;
		}
		
		if($from_page == 'from_teamdashboard'){
			$data['team_ids'] = get_users_under_manager();
			if(in_array($data['task_data']['task_allocated_user_id'], $data['team_ids'])){
				$data['assign_status'] = "";
			}else {
				$data['assign_status'] = "assign_other";
			}
			$data['is_div_valid'] = $is_div_valid;
		}
		
		if (strpos($data['task_data']['task_id'],'child') !== false) {
		    $data['is_chk'] = "0";
		} else {
			$data['is_chk'] = "1";
		}
		$data['today_date'] = strtotime(date("Y-m-d"));

		echo json_encode($data);die;
	}

	/*
	 * Function : set_recurrence_update_task
	 * Author : Spaculus
	 * Desc : This function use to set task div of inserted task's recurrence
	*/
	function set_recurrence_update_task(){
		$theme = getThemeName();
		
		$task_id = $_POST['task_id'];
		$duration = isset($_POST['duration'])?$_POST['duration']:'today';
		$type = isset($_POST['priority'])?$_POST['priority']:'';
		$redirect_page = isset($_POST['redirect_page'])?$_POST['redirect_page']:'from_dashboard';

		
		if($duration == "this_month"){
			$start_date = date("Y-m-01");
			$end_date = date("Y-m-t");
		} elseif($duration == "this_week"){
			$d = strtotime("today");
			$start_week = strtotime("last sunday midnight",$d);
			$end_week = strtotime("next saturday",$d);
			$start_date = date("Y-m-d",$start_week);
			$start_date = date("Y-m-d",$end_week);
		} else if($duration == "next_week"){
			$d1 = strtotime("+1 week -1 day");
			$start_week = strtotime("last sunday midnight",$d1);
			$end_week = strtotime("next saturday",$d1);
			$start_date = date("Y-m-d",$start_week);
			$end_date = date("Y-m-d",$end_week);
		} else if($duration == "overdue"){
			$start_date = user_first_login_date();
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} else {
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d");
		}
		

		$data['site_setting_date'] = $this->config->item('company_default_format');
		$task_data = get_task_detail($task_id);
		$final_div = array();

		
		if($task_data['frequency_type'] == "recurrence"){
			$off_days = get_company_offdays();
			$re_data = monthly_recurrence_logic($task_data,$start_date,$end_date,$off_days);
			
			foreach($re_data as $row){
				
				$is_div_valid = 0;
				
				if($type == "" && $duration == "backlog"){
					$reday_id = get_task_status_id_by_name("Reday");
					if($data['task_data']['task_scheduled_date'] == "0000-00-00" && $data['task_data']['task_status_id']!=$reday_id){
						$is_div_valid = 1;
					}
				} elseif($type !="" && $duration == "backlog"){
					$reday_id = get_task_status_id_by_name("Reday");
					if($data['task_data']['task_priority'] == $type && $data['task_data']['task_scheduled_date'] == "0000-00-00" && $data['task_data']['task_status_id']!=$reday_id){
						$is_div_valid = 1;
					}
				} elseif($type!='' && $duration=='this_week'){
					$d = strtotime("today");
					$start_week = strtotime("last sunday midnight",$d);
					$end_week = strtotime("next saturday",$d);
					if($row['task_priority'] == $type && strtotime($row['task_scheduled_date']) >= $start_week && strtotime($row['task_scheduled_date']) <= $end_week){
						$is_div_valid = 1;
					}
				} elseif($type!='' && $duration == "next_week"){
					$d1 = strtotime("+1 week -1 day");
					$start_week = strtotime("last sunday midnight",$d1);
					$end_week = strtotime("next saturday",$d1);
					if($row['task_priority'] == $type && strtotime($row['task_scheduled_date']) >= $start_week && strtotime($row['task_scheduled_date']) <= $end_week){
						$is_div_valid = 1;
					}
				} elseif($type!='' && $duration == "this_month"){
					$start_week = strtotime(date("Y-m-01"));
					$end_week = strtotime(date("Y-m-t"));
					if($row['task_priority'] == $type && strtotime($row['task_scheduled_date']) >= $start_week && strtotime($row['task_scheduled_date']) <= $end_week){
						$is_div_valid = 1;
					}
				} elseif($type!='' && $duration == 'today'){
					$start_week = strtotime(date("Y-m-d"));
					$end_week = strtotime(date("Y-m-d"));
					if($row['task_priority'] == $type && strtotime($row['task_scheduled_date']) >= $start_week && strtotime($row['task_scheduled_date']) <= $end_week){
						$is_div_valid = 1;
					}
				} elseif($type!='' && $duration == 'overdue'){
					$start_week = user_first_login_date();
					$end_week = date('Y-m-d');
					if($row['task_priority'] == $type && $row['task_due_date'] >= $start_week && $row['task_scheduled_date'] < $end_week && $row['task_status_id'] != $this->config->item('completed_id')){
						$is_div_valid = 1;
					}
				} elseif($type == '' && $duration == 'this_week'){
					$d = strtotime("today");
					$start_week = strtotime("last sunday midnight",$d);
					$end_week = strtotime("next saturday",$d);
					if(strtotime($row['task_scheduled_date']) >= $start_week && strtotime($row['task_scheduled_date']) <= $end_week){
						$is_div_valid = 1;
					}
				} elseif($type == '' && $duration == 'next_week'){
					$d1 = strtotime("+1 week -1 day");
					$start_week = strtotime("last sunday midnight",$d1);
					$end_week = strtotime("next saturday",$d1);
					if(strtotime($row['task_scheduled_date']) >= $start_week && strtotime($row['task_scheduled_date']) <= $end_week){
						$is_div_valid = 1;
					}
				} elseif($type == '' && $duration == 'this_month'){
					$start_week = strtotime(date("Y-m-01"));
					$end_week = strtotime(date("Y-m-t"));
					if(strtotime($row['task_scheduled_date']) >= $start_week && strtotime($row['task_scheduled_date']) <= $end_week){
						$is_div_valid = 1;
					}
				} elseif($type == '' && $duration == 'overdue'){
					$completed_id = $this->config->item('completed_id');
					$start_week = user_first_login_date();
					$end_week = date('Y-m-d');
					if($row['task_due_date'] >= $start_week && $row['task_due_date'] < $end_week && $row['task_status_id'] != $this->config->item('completed_id')){
						$is_div_valid = 1;
					}
				} else {
					if(strtotime($row['task_scheduled_date']) >= strtotime(date("Y-m-d")) && strtotime($row['task_scheduled_date']) <= strtotime(date("Y-m-d"))){
						$is_div_valid = 1;
					}
				}
		
				if($redirect_page == "from_dashboard"){
					if(($row['task_allocated_user_id'] != get_authenticateUserID())){
						$div['assign_status'] = "assign_other";
					} else {
						$div['assign_status'] = "";
					}
					$div['is_div_valid'] = $is_div_valid;
				}
				$data['team_ids'] = get_users_under_manager();
				if($redirect_page == 'from_teamdashboard'){
					if(in_array($row['task_allocated_user_id'], $data['team_ids'])){
						$div['assign_status'] = "";
					}else {
						$div['assign_status'] = "assign_other";
					}
					$div['is_div_valid'] = $is_div_valid;
				}
				
				$div['today_date'] = strtotime(date("Y-m-d"));
				$div['re_data'] = $row;
				if($div['re_data']['task_scheduled_date']!= '0000-00-00' ){
					$div['user_scheduled_date'] = date($this->config->item('company_default_format'),strtotime($div['re_data']['task_scheduled_date']));
					$div['strtotime_scheduled_date'] = strtotime($div['re_data']['task_scheduled_date']);
				} else {
					$div['user_scheduled_date'] = "N/A";
					$div['strtotime_task_scheduled_date'] =  '';
				}
				if($div['re_data']['task_due_date']!= '0000-00-00' ){
					$div['user_due_date'] = date($this->config->item('company_default_format'),strtotime($div['re_data']['task_due_date']));
					$div['strtotime_due_date'] = strtotime($div['re_data']['task_due_date']);
				} else {
					$div['user_due_date'] = "N/A";
					$div['strtotime_due_date'] = '';
				}
				$div['task_status_name'] = get_task_status_name_by_id($div['re_data']['task_status_id']);
				$owner_name = get_user_name($div['re_data']['task_owner_id']);
				$owner_name = ucwords($owner_name->first_name)." ".ucwords($owner_name->last_name[0]);
				$div['task_owner_name'] = $owner_name;

				if($div['re_data']['master_task_id']){
					$div['is_master_deleted'] = chk_master_task_id_deleted($div['re_data']['master_task_id']);
				} else {
					$div['is_master_deleted'] = 0;
				}

				$allocated_name = get_user_name($div['re_data']['task_allocated_user_id']);
				$allocated_name = ucwords($allocated_name->first_name)." ".ucwords($allocated_name->last_name[0]);
				$div['task_allocated_user_name'] = $allocated_name;
				$div['delay'] = round(floor(strtotime(date("Y-m-d")) - strtotime($div['re_data']['task_due_date']))/(60*60*24));
				$final_div[] = $div;
				if (strpos($div['re_data']['task_id'],'child') !== false) {
				    $div['is_chk'] = "0";
				} else {
					$div['is_chk'] = "1";
				}
			}
			echo json_encode($final_div);die;
		} else {
			echo json_encode("done");die;
		}
		die;
	}
}
?>
