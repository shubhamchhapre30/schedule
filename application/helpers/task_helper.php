<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function get_task_swimlane_id($task_id){
		$CI =& get_instance();
		$query = $CI->db->get_where('user_task_swimlanes',array('task_id'=>$task_id, 'user_id'=>get_authenticateUserID()));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->swimlane_id;
		} else {
			return 0;
		}
	}
	
	function get_company_time_flags(){
		
		$CI =& get_instance();
		$query = $CI->db->select('actual_time_on,allow_past_task')->from('company')->where('company_id',$CI->session->userdata('company_id'))->where('is_deleted','0')->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	/**
         * It get user list using this four parameters
         * @param string $division
         * @param string $department
         * @param string $skills
         * @param string $staff_level
         * @returns array|int
         */
	function get_user_list($division='',$department='',$skills='',$staff_level = ''){
		$CI =& get_instance();
		$CI->db->select('u.first_name, u.last_name, u.user_id,u.profile_image,u.is_customer_user');
		$CI->db->from('users u');
		$CI->db->join('user_devision ud','ud.user_id = u.user_id','left');
		$CI->db->join('user_department udp','udp.user_id = u.user_id','left');
		$CI->db->join('user_skills us','us.user_id = u.user_id', 'left');
		if($division){
			$CI->db->where_in('ud.devision_id',$division);	
		}
		if($department){
			$CI->db->where_in('udp.dept_id',$department);	
		}
		if($skills){
			$CI->db->where_in('us.skill_id',$skills);	
		}
		if($staff_level){
			$CI->db->where('u.staff_level',$staff_level);
		}
		$CI->db->where('u.company_id',$CI->session->userdata('company_id'));
		$CI->db->where('u.user_status','Active');
		$CI->db->where('u.is_deleted','0');
//                $CI->db->where('u.is_customer_user','0');
		$CI->db->group_by('u.user_id');
		$CI->db->order_by('u.first_name','asc');
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	/**
         * It get task details from DB via task_id
         * @param int $task_id
         * @returns array|int
         */
	function get_task_detail($task_id,$company_id= ''){
		
		$CI =& get_instance();
		$CI->db->select('t.*,c1.customer_name,u.profile_image,u.first_name,u.last_name,u1.profile_image as owner_profile_image,p.project_title,ps.section_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,uts.color_id,uts.swimlane_id,ts.task_status_name,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm,(SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments,(SELECT COUNT(1) FROM task_and_project_files tpf  WHERE tpf.task_id = t.task_id and tpf.is_deleted=0) AS files,(SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts, (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch');
		$CI->db->from('tasks t');
                $CI->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
                $CI->db->join('users u1','t.task_owner_id = u1.user_id','left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('project_section ps','ps.section_id = t.subsection_id','left');
                $CI->db->join('customers c1','t.customer_id = c1.customer_id AND t.task_company_id = c1.customer_company_id','left');
		$CI->db->where('t.task_owner_id != ','0');
                if($company_id !=''){
                    $CI->db->where('t.task_company_id',$company_id);
                }else{
                    $CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
                }
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_id',$task_id);
		$query = $CI->db->get();
                if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	/**
         * It get task dependencies by using task_id.
         * @param int $task_id
         * @returns array|int
         */
	function get_task_dependencies($task_id){
		$CI =& get_instance();
		$query = $CI->db->select("t.task_id,t.task_title,t.task_allocated_user_id,t.task_due_date,t.task_status_id,t.task_owner_id,u.first_name,u.last_name,ts.task_status_name")
						->from("tasks t")
						->join("users u","u.user_id = t.task_allocated_user_id")
						->join("task_status ts","ts.task_status_id = t.task_status_id")
						->where("t.prerequisite_task_id",$task_id)
						->where("t.is_prerequisite_task","1")
						->where('t.task_company_id',$CI->session->userdata('company_id'))
						->where('t.task_owner_id != ','0')
						->where('t.task_allocated_user_id != ','0')
						->where("t.is_deleted","0")
						->get();
						
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}

	function get_task_dependencies_ids($task_id){
		$CI =& get_instance();
		$query = $CI->db->select("t.task_id,t.task_scheduled_date,t.task_status_id,t.task_due_date,t.task_time_estimate,t.subsection_id,t.section_id,uts.color_id,uts.swimlane_id")
						->from("tasks t")
						->join('user_task_swimlanes uts','uts.task_id = t.prerequisite_task_id','left')
						->where("t.prerequisite_task_id",$task_id)
						->where("t.is_prerequisite_task","1")
						->where('t.task_owner_id != ','0')
						->where('t.task_company_id',$CI->session->userdata('company_id'))
						->where('t.task_allocated_user_id != ','0')
						->where("t.is_deleted","0")
						->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	/**
         * It get multiallocation task id via task_id
         * @param int $task_id
         * @returns array|int
         */
	function get_task_multiallocation_ids($task_id){
		$CI =& get_instance();
		$query = $CI->db->select("task_id,task_scheduled_date,task_status_id,task_due_date,task_time_estimate,subsection_id,section_id,task_allocated_user_id")
						->from("tasks")
						->where("multi_allocation_task_id",$task_id)
						->where('task_owner_id != ','0')
						->where('task_company_id',$CI->session->userdata('company_id'))
						->where('task_allocated_user_id != ','0')
						->where("is_deleted","0")
						->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/**
         * It get status name using status id
         * @param int $id
         * @returns string|int
         */
	function getStatusName($id){
		$CI =& get_instance();
		$query = $CI->db->get_where('task_status',array('company_id'=>$CI->session->userdata('company_id'), 'task_status_id' => $id));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_name;
		} else {
			return 0;
		}
	}
        /**
         * It get task steps using task id.
         * @param int $task_id
         * @returns array|int
         */
	function get_task_steps($task_id){
		$CI =& get_instance();
                if($task_id!='0'){
                    $query = $CI->db->get_where('task_steps',array('task_id' => $task_id,'is_deleted'=>'0'));
                    if($query->num_rows()>0){
                            return $query->result_array();
                    } else {
                            return 0;
                    }
                }else{
                    return 0;
                }
	}
	/**
         * it get steps id. 
         * @param int $task_id
         * @param string $step_title
         * @returns int
         */
	function get_task_step_id($task_id,$step_title){
		$CI =& get_instance();
		$query = $CI->db->get_where('task_steps',array('task_id' => $task_id,'step_title'=>$step_title));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_step_id;
		} else {
			return 0;
		}
	}
        /**
         * It get task related files from server 
         * @param int $task_id
         * @returns array|int
         */
	function get_task_files($task_id){
		
		$CI =& get_instance();
		$project_id = get_project_id_from_task_id($task_id);
		if($project_id){
			$query = $CI->db->select('tp.*,u.first_name,u.last_name')->from('task_and_project_files tp')->join('users u','tp.file_added_by = u.user_id','left')->where('tp.task_id',$task_id)->where('tp.project_id',$project_id)->where('tp.is_deleted',0)->order_by('tp.task_file_id','desc')->get();
		} else {
			$query = $CI->db->select('tp.*,u.first_name,u.last_name')->from('task_and_project_files tp')->join('users u','tp.file_added_by = u.user_id','left')->where('tp.task_id',$task_id)->where('tp.project_id','0')->where('tp.is_deleted',0)->order_by('tp.task_file_id','desc')->get();
		}
		if($query->num_rows()>0){
			$res = $query->result_array();
			$final_array = array();
			if($res){
				foreach($res as $row){
					//$row['user_name'] = usernameById($row['file_added_by']);
					$row['user_name'] = $row['first_name']." ".$row['last_name'];
					$row['time_ago'] = time_ago($row['file_date_added']);
					
					$CI->load->library('s3');
					$CI->config->load('s3');
					$bucket = $CI->config->item('bucket_name');
					$name = 'upload/task_project_files/'.$row['task_file_name'];
					$chk = $CI->s3->getObjectInfo($bucket,$name);
					if($chk)
					{
						$row['file_size'] = $chk['size'];
					} else {
						$row['file_size'] = "0";
					}
					$final_array[] = $row;
				}
			}
			return $final_array;
		} else {
			return 0;
		}
	}

	function getTaskFiles($task_id){
			
		$CI =& get_instance();
		$project_id = get_project_id_from_task_id($task_id);
		if($project_id){
			$query = $CI->db->select('*')->from('task_and_project_files')->where('task_id',$task_id)->where('project_id',$project_id)->where('is_deleted',0)->order_by('task_file_id','asc')->get();
		} else {
			$query = $CI->db->select('*')->from('task_and_project_files')->where('task_id',$task_id)->where('project_id','0')->where('is_deleted',0)->order_by('task_file_id','asc')->get();
		}
		if($query->num_rows()>0){
			return $query->result_array();
			
		} else {
			return 0;
		}
	}
	/**
         * It get inserted file details from DB.
         * @param int $id
         * @returns array|int
         */
	function get_task_inserted_file($id){
		$CI =& get_instance();
		$query = $CI->db->select('*')->from('task_and_project_files')->where('task_file_id',$id)->where('is_deleted',0)->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	/**
         * It get task comments.
         * @param int $task_id
         * @return int
         */
	function get_task_comments($task_id){
		
		$CI =& get_instance();
		$project_id = get_project_id_from_task_id($task_id);
		$CI->db->select('tc.*,u.profile_image,u.first_name,u.last_name');
		$CI->db->from('task_and_project_comments tc');
		$CI->db->join('users u','u.user_id = tc.comment_addeby');
		$CI->db->where('tc.task_id',$task_id);
		if($project_id){
			$CI->db->where('project_id',$project_id);
		} else {
			$CI->db->where('project_id','0');	
		}
		$CI->db->order_by('task_comment_id','desc');
		$query = $CI->db->get();
		//echo $CI->db->last_query();
		
		if($query->num_rows()>0){
			$res = $query->result_array();
			
			$final_array = array();
			if($res){
				
				$bucket = $CI->config->item('bucket_name');
				foreach($res as $row){
					
					$name = 'upload/user/'.$row['profile_image'];
					$row['time_ago'] = time_ago($row['comment_added_date']);
					$row['file_exist'] = $CI->s3->getObjectInfo($bucket,$name);
					$final_array[] = $row;
				}
			}
			return $final_array;
		} else {
			return 0;
		}
	}
	
	function get_task_inserted_comments($id){
		$CI =& get_instance();
		$query = $CI->db->select("*")->from("task_and_project_comments")->where("task_comment_id",$id)->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	/**
         * It get task history from DB
         * @param int $task_id
         * @returns array|int
         */
	function get_task_history($task_id){
		$CI =& get_instance();
		$query = $CI->db->select('th.*,u.profile_image,u.first_name,u.last_name')->from('task_history th')->join('users u','u.user_id = th.history_added_by')->where('task_id',$task_id)->order_by('task_history_id','desc')->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	/**
         * It returns task owner id
         * @param int $task_id
         * @returns int
         */
	function get_task_owner_id($task_id){
		if($task_id){
			$CI =& get_instance();
			$query = $CI->db->select("task_owner_id")->from("tasks")->where("task_owner_id != ","0")->where("task_allocated_user_id != ","0")->where("task_id",$task_id)->get();
			if($query->num_rows()>0){
				$res = $query->row();
				return $res->task_owner_id;
			} 
		}
		return 0;
	}
	
	function time_ago($date)
	{
		
       $CI =& get_instance();
	   date_default_timezone_set($CI->session->userdata("User_timezone"));
	   
         $dt = new DateTime($date, new DateTimeZone("UTC"));
		    $dt->format('r') . PHP_EOL;
		//	echo "<br>";
			$dt->setTimezone(new DateTimeZone($CI->session->userdata("User_timezone")));
			
			
			 $date = $dt->format('Y-m-d H:i:s');
			
			

		if(empty($date)) {
			return "No date provided";
		}

		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();

		$unix_date = strtotime($date);

		// check validity of date
		if(empty($unix_date)) {
			return "Bad date";
		}

		// is it future date or past date
		if($now > $unix_date) {
			$difference = $now - $unix_date;
			$tense = "ago";
		} else {
			$difference = $unix_date - $now;
			$tense = "from now";
		}

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j].= "s";
		}
        //date_default_timezone_set("UTC");
		return "$difference $periods[$j] {$tense}";

	}
	
	function get_taskStatus_id($task_id){
		$CI =& get_instance();
		$query = $CI->db->select("task_status_id")->from("tasks")->where("task_owner_id != ","0")->where("task_allocated_user_id != ","0")->where("task_id",$task_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_id;
		} else {
			return 0;
		}
	}
        /**
         * It returns status id by name
         * @param string $name
         * @returns int
         */
	function get_task_status_id_by_name($name){
		$CI =& get_instance();
		$CI->db->select('task_status_id');
		$CI->db->from('task_status');
		$CI->db->where('task_status_name',$name);
		$CI->db->where('task_status_flag','Active');
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_id;
		} else {
			return 0;
		}
	}
	/**
         * It returns status name by id
         * @param int $status_id
         * @returns string|int
         */
	function get_task_status_name_by_id($status_id){
		$CI =& get_instance();
		$CI->db->select('task_status_name');
		$CI->db->from('task_status');
		$CI->db->where('task_status_id',$status_id);
		$CI->db->where('task_status_flag','Active');
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_name;
		} else {
			return 0;
		}
	}
	
	function chk_kanban_recurrence_exists($master_task_id,$due_date){
		$CI =& get_instance();
		
		$CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order');
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id','left');
		$CI->db->where('t.master_task_id',$master_task_id);
		$CI->db->where('t.task_orig_due_date',$due_date);
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.is_deleted','0');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			
		}
	}
	/**
         * It checks recurrence exists,if exist it return data array otherwise returns 0.
         * @param  $row
         * @param  $vr_arr
         * @param  $task_status_completed_id
         * @param  $off_days
         * @returns array|int
         */
	function chk_recurrence_exists($row,$vr_arr,$task_status_completed_id,$off_days){
		$CI =& get_instance();
		$CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id  and tp.is_deleted = 0) AS tpp, uc.color_code,uc.outside_color_code, uc.color_name, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments , (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts,(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies,(SELECT COUNT(1) FROM task_and_project_files tpf  WHERE tpf.task_id = t.task_id and tpf.is_deleted=0) AS files',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		$CI->db->where('t.master_task_id',$vr_arr['master_task_id']);
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_orig_scheduled_date',$vr_arr['task_orig_scheduled_date']);
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$chk_arr = $query->row_array();
			
			if($chk_arr['is_deleted'] == '0'){
				// check for completed
				if($chk_arr['task_status_id'] == $task_status_completed_id){
					$arr = kanban_recurrence_logic($row,$chk_arr['task_orig_scheduled_date'],$off_days);
					if($arr){
						return chk_recurrence_exists($row,$arr,$task_status_completed_id,$off_days);
					} else {
						return 0;
					}
					
				} else {
					return $chk_arr;
				}
			} else {
				return 0;
			}
		} else {
			return $vr_arr;
		}
	}

	function get_kanban_tasks($status,$swimlanes = '',$type='',$user_team_id='',$project_id,$user_color_id=''){
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$task = array();
		
		$task_status_completed_id = $CI->config->item('completed_id');
		//print_r($project_id);
		$off_days = get_company_offdays();
		
		if($swimlanes){
			foreach($swimlanes as $swm){
				$task[$swm->swimlanes_id][$task_status_completed_id] = array();
				$result = get_kanban_tasks_onlycompleted($task_status_completed_id,$swm->swimlanes_id,$type,$user_team_id,$project_id,$user_color_id,20,0);
				if($result){
					foreach($result as $row){
						if($row['task_status_id'] == $task_status_completed_id && $row['swimlane_id'] == $swm->swimlanes_id){
							if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){
								if($row['is_personal'] == "0"){
									if($type){
										if($type=='today'){
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type=='this_week'){
											$d = strtotime("today");
											$start_week = strtotime("last sunday midnight",$d);
											$end_week = strtotime("next saturday",$d);
											$week_start_date = date("Y-m-d",$start_week); 
											$week_end_date = date("Y-m-d",$end_week);  
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_week'){
											$d = strtotime("+1 week -1 day");
											$start_week = strtotime("last sunday midnight",$d);
											$end_week = strtotime("next saturday",$d);
											$next_week_start_date = date("Y-m-d",$start_week); 
											$next_week_end_date = date("Y-m-d",$end_week); 
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'this_month'){
											$this_month_start_date = date('Y-m-01',strtotime('this month'));
											$this_month_end_date = date('Y-m-t',strtotime('this month'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_month'){
											$next_month_start_date = date('Y-m-01',strtotime('next month'));
											$next_month_end_date = date('Y-m-t',strtotime('next month'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_to_next_month'){
											$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
											$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										}else if($type == 'next_ninty'){
											$next_ninty_start_date = date('Y-m-d');
											$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										}else if($type == 'this_year'){
											$this_year_start_date = date('Y-01-01',strtotime('this year'));
											$this_year_end_date = date('Y-12-t',strtotime('this year'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_year'){
											$next_year_start_date = date('Y-01-01',strtotime('next year'));
											$next_year_end_date = date('Y-12-t',strtotime('next year'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'overdue'){
											
										} else {
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									}
								}
							} else {
								if($type){
									if($type=='today'){
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type=='this_week'){
										$d = strtotime("today");
										$start_week = strtotime("last sunday midnight",$d);
										$end_week = strtotime("next saturday",$d);
										$week_start_date = date("Y-m-d",$start_week); 
										$week_end_date = date("Y-m-d",$end_week);
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'next_week'){
										$d = strtotime("+1 week -1 day");
										$start_week = strtotime("last sunday midnight",$d);
										$end_week = strtotime("next saturday",$d);
										$next_week_start_date = date("Y-m-d",$start_week); 
										$next_week_end_date = date("Y-m-d",$end_week);
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'this_month'){
										$this_month_start_date = date('Y-m-01',strtotime('this month'));
										$this_month_end_date = date('Y-m-t',strtotime('this month'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									}else if($type == 'next_month'){
										$next_month_start_date = date('Y-m-01',strtotime('next month'));
										$next_month_end_date = date('Y-m-t',strtotime('next month'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'next_to_next_month'){
										$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
										$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'next_ninty'){
										$next_ninty_start_date = date('Y-m-d');
										$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'this_year'){
										$this_year_start_date = date('Y-01-01',strtotime('this year'));
										$this_year_end_date = date('Y-12-t',strtotime('this year'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									}else if($type == 'next_year'){
										$next_year_start_date = date('Y-01-01',strtotime('next year'));
										$next_year_end_date = date('Y-12-t',strtotime('next year'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'overdue'){
										
									} else {
										array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
									}
								}
							}
						}
					}
				}
			}
		}
		
		$CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, uc.color_code, uc.outside_color_code, uc.color_name, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments , (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts,(SELECT COUNT(1) FROM task_and_project_files tpf WHERE tpf.task_id = t.task_id and tpf.is_deleted = 0) AS files, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies ',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		
		if($user_team_id){
			if($user_team_id != get_authenticateUserID()){
				$CI->db->where('t.is_personal','0');
			}
			$CI->db->where('t.task_allocated_user_id',$user_team_id);
		} else {
			$CI->db->where('t.task_allocated_user_id',$CI->session->userdata('user_id'));
		}
		
		if($user_color_id!='0'){
			$CI->db->where('uts.color_id',$user_color_id);
		} else {
		}
		if($project_id[0] != 'all' && $project_id!='all'){
		
                        $CI->db->where_in('t.task_project_id',$project_id);
		}
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.master_task_id','0');
		$CI->db->where('t.task_status_id != ',$task_status_completed_id);
		$CI->db->order_by('uts.kanban_order','asc');
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
             ///echo "<pre>"; echo $CI->db->last_query(); die();
		if($query->num_rows()>0){
			$res = $query->result_array();
			if($res){
				if($swimlanes){
					if($status){
						foreach($swimlanes as $swm){
							foreach($status as $st){
								if($st->task_status_id == $task_status_completed_id){
									
								} else {
									$task[$swm->swimlanes_id][$st->task_status_id] = array();
									foreach($res as $row){
										if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){
											$virtual_array = kanban_recurrence_logic($row,'',$off_days);
											$chk_recu = chk_recurrence_exists($row,$virtual_array,$task_status_completed_id,$off_days);
											if($chk_recu){
												if($chk_recu['task_status_id'] == $st->task_status_id && $chk_recu['swimlane_id'] == $swm->swimlanes_id ){
													if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){
														if($chk_recu['is_personal'] == "0"){
															if($type){
																if($type=='today'){
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] == date("Y-m-d")){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type=='this_week'){
																	$d = strtotime("today");
																	$start_week = strtotime("last sunday midnight",$d);
																	$end_week = strtotime("next saturday",$d);
																	$week_start_date = date("Y-m-d",$start_week); 
																	$week_end_date = date("Y-m-d",$end_week);
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $week_start_date && $chk_recu['task_due_date'] <= $week_end_date){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type == 'next_week'){
																	$d = strtotime("+1 week -1 day");
																	$start_week = strtotime("last sunday midnight",$d);
																	$end_week = strtotime("next saturday",$d);
																	$next_week_start_date = date("Y-m-d",$start_week); 
																	$next_week_end_date = date("Y-m-d",$end_week);
																			
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $next_week_start_date && $chk_recu['task_due_date'] <= $next_week_end_date){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type == 'this_month'){
																										 																		$this_month_start_date = date('Y-m-01',strtotime('this month'));
																	$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_month'){
																										 																		$next_month_start_date = date('Y-m-01',strtotime('next month'));
																	$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_to_next_month'){
																										 																		$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																	$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_ninty'){
																										 																		$next_ninty_start_date = date('Y-m-d');
																	$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'this_year'){
																										 																		$this_year_start_date = date('Y-01-01',strtotime('this year'));
																	$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_year'){
																										 																		$next_year_start_date = date('Y-01-01',strtotime('next year'));
																	$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'overdue'){
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
															
																} else {
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															}
														}
													} else {
														if($type){
															if($type=='today'){
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] == date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type=='this_week'){
																$d = strtotime("today");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$week_start_date = date("Y-m-d",$start_week); 
																$week_end_date = date("Y-m-d",$end_week);
																
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $week_start_date && $chk_recu['task_due_date'] <= $week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type == 'next_week'){
																$d = strtotime("+1 week -1 day");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$next_week_start_date = date("Y-m-d",$start_week); 
																$next_week_end_date = date("Y-m-d",$end_week); 
																
																
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $next_week_start_date && $chk_recu['task_due_date'] <= $next_week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type == 'this_month'){
																										 																	$this_month_start_date = date('Y-m-01',strtotime('this month'));
																$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_month'){
																										 																	$next_month_start_date = date('Y-m-01',strtotime('next month'));
																$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_to_next_month'){
																										 																	$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_ninty'){
																										 																	$next_ninty_start_date = date('Y-m-d');
																$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_year'){
																										 																	$this_year_start_date = date('Y-01-01',strtotime('this year'));
																$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_year'){
																										 																	$next_year_start_date = date('Y-01-01',strtotime('next year'));
																$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'overdue'){
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															
															} else {
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
															}
														}
													}
												}
											}
										} else {
											if($row['task_status_id'] == $st->task_status_id && $row['swimlane_id'] == $swm->swimlanes_id){
												if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){
													if($row['is_personal'] == "0"){
														if($type){
															if($type=='today'){
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type=='this_week'){
																$d = strtotime("today");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$week_start_date = date("Y-m-d",$start_week); 
																$week_end_date = date("Y-m-d",$end_week);
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_week'){
																$d = strtotime("+1 week -1 day");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$next_week_start_date = date("Y-m-d",$start_week); 
																$next_week_end_date = date("Y-m-d",$end_week); 
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
																$this_month_end_date = date('Y-m-t',strtotime('this month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_month'){
																											 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
																$next_month_end_date = date('Y-m-t',strtotime('next month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_to_next_month'){
																											 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_ninty'){
																											 																$next_ninty_start_date = date('Y-m-d');
																$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_year'){
																											 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
																$this_year_end_date = date('Y-12-t',strtotime('this year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_year'){
																											 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
																$next_year_end_date = date('Y-12-t',strtotime('next year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'overdue'){
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
																
															} else {
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														}
													}
												} else {
													if($type){
														if($type=='today'){
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type=='this_week'){
															$d = strtotime("today");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$week_start_date = date("Y-m-d",$start_week); 
															$week_end_date = date("Y-m-d",$end_week);
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_week'){
															$d = strtotime("+1 week -1 day");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$next_week_start_date = date("Y-m-d",$start_week); 
															$next_week_end_date = date("Y-m-d",$end_week); 
																
																
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
															$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_month'){
																										 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
															$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_to_next_month'){
																										 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
															$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_ninty'){
																										 																$next_ninty_start_date = date('Y-m-d');
															$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_year'){
																										 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
															$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_year'){
																										 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
															$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'overdue'){
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
															
														} else {
															array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
														}
													}
												}
											}
										}
										
									}
								}
							}
						}
					}
				}
			}
		}
		date_default_timezone_set("UTC");
                return $task; 
		
	}
        /*
         * This function is used for getting task of project team
         */
        function get_kanban_tasks_team($status,$swimlanes = '',$type='',$user_team_id='',$project_id='',$user_color_id=''){
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$task = array();
		
		$task_status_completed_id = $CI->config->item('completed_id');
		
		$off_days = get_company_offdays();
		$users=array();
                $ids=array(); 
            
                if($user_team_id == '#' && $project_id != 'all')
                    {
                        
                         $users=get_user_under_project($project_id);
                         if(!empty($users)){
                            foreach($users as $user){
                                $ids[]=$user->user_id;
                             }
                         }
                        
                     }
		if($swimlanes){
			foreach($swimlanes as $swm){
				$task[$swm->swimlanes_id][$task_status_completed_id] = array();
				$result = get_kanban_tasks_onlycompleted($task_status_completed_id,$swm->swimlanes_id,$type,$user_team_id,$project_id,$user_color_id,20,0);
				if($result){
					foreach($result as $row){
						if($row['task_status_id'] == $task_status_completed_id && $row['swimlane_id'] == $swm->swimlanes_id){
							if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){
								if($row['is_personal'] == "0"){
									if($type){
										if($type=='today'){
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type=='this_week'){
											$d = strtotime("today");
											$start_week = strtotime("last sunday midnight",$d);
											$end_week = strtotime("next saturday",$d);
											$week_start_date = date("Y-m-d",$start_week); 
											$week_end_date = date("Y-m-d",$end_week);  
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_week'){
											$d = strtotime("+1 week -1 day");
											$start_week = strtotime("last sunday midnight",$d);
											$end_week = strtotime("next saturday",$d);
											$next_week_start_date = date("Y-m-d",$start_week); 
											$next_week_end_date = date("Y-m-d",$end_week); 
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'this_month'){
											$this_month_start_date = date('Y-m-01',strtotime('this month'));
											$this_month_end_date = date('Y-m-t',strtotime('this month'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_month'){
											$next_month_start_date = date('Y-m-01',strtotime('next month'));
											$next_month_end_date = date('Y-m-t',strtotime('next month'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_to_next_month'){
											$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
											$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										}else if($type == 'next_ninty'){
											$next_ninty_start_date = date('Y-m-d');
											$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										}else if($type == 'this_year'){
											$this_year_start_date = date('Y-01-01',strtotime('this year'));
											$this_year_end_date = date('Y-12-t',strtotime('this year'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'next_year'){
											$next_year_start_date = date('Y-01-01',strtotime('next year'));
											$next_year_end_date = date('Y-12-t',strtotime('next year'));
											
											if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
												
												array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
											}
										} else if($type == 'overdue'){
											
										} else {
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									}
								}
							} else {
								if($type){
									if($type=='today'){
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type=='this_week'){
										$d = strtotime("today");
										$start_week = strtotime("last sunday midnight",$d);
										$end_week = strtotime("next saturday",$d);
										$week_start_date = date("Y-m-d",$start_week); 
										$week_end_date = date("Y-m-d",$end_week);
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'next_week'){
										$d = strtotime("+1 week -1 day");
										$start_week = strtotime("last sunday midnight",$d);
										$end_week = strtotime("next saturday",$d);
										$next_week_start_date = date("Y-m-d",$start_week); 
										$next_week_end_date = date("Y-m-d",$end_week);
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'this_month'){
										$this_month_start_date = date('Y-m-01',strtotime('this month'));
										$this_month_end_date = date('Y-m-t',strtotime('this month'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									}else if($type == 'next_month'){
										$next_month_start_date = date('Y-m-01',strtotime('next month'));
										$next_month_end_date = date('Y-m-t',strtotime('next month'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'next_to_next_month'){
										$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
										$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'next_ninty'){
										$next_ninty_start_date = date('Y-m-d');
										$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'this_year'){
										$this_year_start_date = date('Y-01-01',strtotime('this year'));
										$this_year_end_date = date('Y-12-t',strtotime('this year'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									}else if($type == 'next_year'){
										$next_year_start_date = date('Y-01-01',strtotime('next year'));
										$next_year_end_date = date('Y-12-t',strtotime('next year'));
										
										if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
											
											array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
										}
									} else if($type == 'overdue'){
										
									} else {
										array_push($task[$swm->swimlanes_id][$task_status_completed_id],$row);
									}
								}
							}
						}
					}
				}
			}
		}
		
		$CI->db->select('t.*,u.profile_image,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, uc.color_code, uc.outside_color_code, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments , (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies ,(SELECT COUNT(1) FROM task_and_project_files tpf WHERE tpf.task_id = t.task_id and tpf.is_deleted = 0) AS files',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		
                if($user_team_id == '#')
                {
                    //array_push($ids, $CI->session->userdata('user_id'));
                    $CI->db->where_in('t.task_allocated_user_id',$ids);
                }else{
		if($user_team_id){
			if($user_team_id != get_authenticateUserID()){
				$CI->db->where('t.is_personal','0');
			}
			$CI->db->where('t.task_allocated_user_id',$user_team_id);
		} else {
			$CI->db->where('t.task_allocated_user_id',$CI->session->userdata('user_id'));
		}
                }
		if($user_color_id!='0'){
			$CI->db->where('uts.color_id',$user_color_id);
		} else {
		}
		if($project_id !='all'){
			
                        $CI->db->where('t.task_project_id',$project_id);
			
		} 
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.master_task_id','0');
		$CI->db->where('t.task_status_id != ',$task_status_completed_id);
		$CI->db->order_by('uts.kanban_order','asc');
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
                if($query->num_rows()>0){
			$res = $query->result_array();
                        
			if($res){ 
				if($swimlanes){ 
					if($status){ 
						foreach($swimlanes as $swm){  
							foreach($status as $st){ 
								if($st->task_status_id == $task_status_completed_id){
									
								} else { 
									$task[$swm->swimlanes_id][$st->task_status_id] = array();
									foreach($res as $row){ 
										if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){ 
											$virtual_array = kanban_recurrence_logic($row,'',$off_days);
											$chk_recu = chk_recurrence_exists($row,$virtual_array,$task_status_completed_id,$off_days);
											if($chk_recu){
												if($chk_recu['task_status_id'] == $st->task_status_id && $chk_recu['swimlane_id'] == $swm->swimlanes_id ){
													if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){ 
														if($chk_recu['is_personal'] == "0"){
															if($type){
																if($type=='today'){
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] == date("Y-m-d")){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type=='this_week'){
																	$d = strtotime("today");
																	$start_week = strtotime("last sunday midnight",$d);
																	$end_week = strtotime("next saturday",$d);
																	$week_start_date = date("Y-m-d",$start_week); 
																	$week_end_date = date("Y-m-d",$end_week);
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $week_start_date && $chk_recu['task_due_date'] <= $week_end_date){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type == 'next_week'){
																	$d = strtotime("+1 week -1 day");
																	$start_week = strtotime("last sunday midnight",$d);
																	$end_week = strtotime("next saturday",$d);
																	$next_week_start_date = date("Y-m-d",$start_week); 
																	$next_week_end_date = date("Y-m-d",$end_week);
																			
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $next_week_start_date && $chk_recu['task_due_date'] <= $next_week_end_date){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type == 'this_month'){
																										 																		$this_month_start_date = date('Y-m-01',strtotime('this month'));
																	$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_month'){
																										 																		$next_month_start_date = date('Y-m-01',strtotime('next month'));
																	$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_to_next_month'){
																										 																		$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																	$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_ninty'){
																										 																		$next_ninty_start_date = date('Y-m-d');
																	$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'this_year'){
																										 																		$this_year_start_date = date('Y-01-01',strtotime('this year'));
																	$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_year'){
																										 																		$next_year_start_date = date('Y-01-01',strtotime('next year'));
																	$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'overdue'){
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
															
																} else {
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															}
														}
													} else {
														if($type){
															if($type=='today'){
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] == date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type=='this_week'){
																$d = strtotime("today");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$week_start_date = date("Y-m-d",$start_week); 
																$week_end_date = date("Y-m-d",$end_week);
																
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $week_start_date && $chk_recu['task_due_date'] <= $week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type == 'next_week'){
																$d = strtotime("+1 week -1 day");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$next_week_start_date = date("Y-m-d",$start_week); 
																$next_week_end_date = date("Y-m-d",$end_week); 
																
																
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $next_week_start_date && $chk_recu['task_due_date'] <= $next_week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type == 'this_month'){
																										 																	$this_month_start_date = date('Y-m-01',strtotime('this month'));
																$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_month'){
																										 																	$next_month_start_date = date('Y-m-01',strtotime('next month'));
																$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_to_next_month'){
																										 																	$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_ninty'){
																										 																	$next_ninty_start_date = date('Y-m-d');
																$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_year'){
																										 																	$this_year_start_date = date('Y-01-01',strtotime('this year'));
																$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_year'){
																										 																	$next_year_start_date = date('Y-01-01',strtotime('next year'));
																$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'overdue'){
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															
															} else {
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
															}
														}
													}
												}
											}
										} else { 
											if($row['task_status_id'] == $st->task_status_id && $row['swimlane_id'] == $swm->swimlanes_id){
												if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){  
													if($row['is_personal'] == "0"){ 
														if($type){
															if($type=='today'){
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type=='this_week'){
																$d = strtotime("today");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$week_start_date = date("Y-m-d",$start_week); 
																$week_end_date = date("Y-m-d",$end_week);
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_week'){
																$d = strtotime("+1 week -1 day");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$next_week_start_date = date("Y-m-d",$start_week); 
																$next_week_end_date = date("Y-m-d",$end_week); 
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
																$this_month_end_date = date('Y-m-t',strtotime('this month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_month'){
																											 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
																$next_month_end_date = date('Y-m-t',strtotime('next month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_to_next_month'){
																											 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_ninty'){
																											 																$next_ninty_start_date = date('Y-m-d');
																$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_year'){
																											 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
																$this_year_end_date = date('Y-12-t',strtotime('this year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_year'){
																											 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
																$next_year_end_date = date('Y-12-t',strtotime('next year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'overdue'){
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
																
															} else {
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														}
													}
												} else {
													if($type){
														if($type=='today'){
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type=='this_week'){
															$d = strtotime("today");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$week_start_date = date("Y-m-d",$start_week); 
															$week_end_date = date("Y-m-d",$end_week);
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_week'){
															$d = strtotime("+1 week -1 day");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$next_week_start_date = date("Y-m-d",$start_week); 
															$next_week_end_date = date("Y-m-d",$end_week); 
																
																
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
															$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_month'){
																										 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
															$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_to_next_month'){
																										 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
															$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_ninty'){
																										 																$next_ninty_start_date = date('Y-m-d');
															$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_year'){
																										 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
															$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_year'){
																										 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
															$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'overdue'){
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
															
														} else {
															array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
														}
													}
												}
											}
										}
										
									}
								}
							}
						}
					}
				}
			}
		}
		date_default_timezone_set("UTC");
                
		return $task; 
		
	}
	function get_kanban_tasks2($status,$swimlanes = '',$type='',$user_team_id='',$project_id='',$user_color_id=''){
		
		
		
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		

		$task = array();
		
		$task_status_completed_id = $CI->config->item('completed_id');
		
		$off_days = get_company_offdays();
		
		$CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, uc.color_code, uc.outside_color_code, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments , (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts,
(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies ',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		
		if($user_team_id){
			if($user_team_id != get_authenticateUserID()){
				$CI->db->where('t.is_personal','0');
			}
			$CI->db->where('t.task_allocated_user_id',$user_team_id);
		} else {
			$CI->db->where('t.task_allocated_user_id',$CI->session->userdata('user_id'));
		}
		
		if($user_color_id!='0'){
			$CI->db->where('uts.color_id',$user_color_id);
		} else {
		}
		if($project_id){
			if(in_array('all',$project_id)){
				
			} else {
				$CI->db->where_in('t.task_project_id',$project_id);
			}
		} else {
			$CI->db->where('t.task_project_id','0');
		}
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.master_task_id','0');
		$CI->db->order_by('uts.kanban_order','asc');
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			if($res){
				if($swimlanes){
					if($status){
						foreach($swimlanes as $swm){
							foreach($status as $st){
								$task[$swm->swimlanes_id][$st->task_status_id] = array();
								
								if($st->task_status_id == $task_status_completed_id){
									$result = get_kanban_tasks_onlycompleted($st->task_status_id,$swm->swimlanes_id,$type,$user_team_id,$project_id,$user_color_id,10,0);
									
									if($result){
										foreach($result as $row){
											if($row['task_status_id'] == $st->task_status_id && $row['swimlane_id'] == $swm->swimlanes_id){
												if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){
													if($row['is_personal'] == "0"){
														if($type){
															if($type=='today'){
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type=='this_week'){
																$d = strtotime("today");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$week_start_date = date("Y-m-d",$start_week); 
																$week_end_date = date("Y-m-d",$end_week);  
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_week'){
																$d = strtotime("+1 week -1 day");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$next_week_start_date = date("Y-m-d",$start_week); 
																$next_week_end_date = date("Y-m-d",$end_week); 
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
																$this_month_end_date = date('Y-m-t',strtotime('this month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_month'){
																											 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
																$next_month_end_date = date('Y-m-t',strtotime('next month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_to_next_month'){
																											 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															}else if($type == 'next_ninty'){
																											 																$next_ninty_start_date = date('Y-m-d');
																$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															}else if($type == 'this_year'){
																											 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
																$this_year_end_date = date('Y-12-t',strtotime('this year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_year'){
																											 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
																$next_year_end_date = date('Y-12-t',strtotime('next year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'overdue'){
																
															} else {
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														}
													}
												} else {
													if($type){
														if($type=='today'){
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type=='this_week'){
															$d = strtotime("today");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$week_start_date = date("Y-m-d",$start_week); 
															$week_end_date = date("Y-m-d",$end_week);
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_week'){
															$d = strtotime("+1 week -1 day");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$next_week_start_date = date("Y-m-d",$start_week); 
															$next_week_end_date = date("Y-m-d",$end_week);
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
															$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														}else if($type == 'next_month'){
																										 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
															$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_to_next_month'){
																										 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
															$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_ninty'){
																										 																$next_ninty_start_date = date('Y-m-d');
															$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_year'){
																										 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
															$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														}else if($type == 'next_year'){
																										 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
															$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'overdue'){
															
														} else {
															array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
														}
													}
												}
											}
										}
									}
								} else {
									foreach($res as $row){
										if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){
											$virtual_array = kanban_recurrence_logic($row,'',$off_days);
											$chk_recu = chk_recurrence_exists($row,$virtual_array,$task_status_completed_id,$off_days);
											if($chk_recu){
												if($chk_recu['task_status_id'] == $st->task_status_id && $chk_recu['swimlane_id'] == $swm->swimlanes_id ){
													if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){
														if($chk_recu['is_personal'] == "0"){
															if($type){
																if($type=='today'){
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] == date("Y-m-d")){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type=='this_week'){
																	$d = strtotime("today");
																	$start_week = strtotime("last sunday midnight",$d);
																	$end_week = strtotime("next saturday",$d);
																	$week_start_date = date("Y-m-d",$start_week); 
																	$week_end_date = date("Y-m-d",$end_week);
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $week_start_date && $chk_recu['task_due_date'] <= $week_end_date){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type == 'next_week'){
																	$d = strtotime("+1 week -1 day");
																	$start_week = strtotime("last sunday midnight",$d);
																	$end_week = strtotime("next saturday",$d);
																	$next_week_start_date = date("Y-m-d",$start_week); 
																	$next_week_end_date = date("Y-m-d",$end_week);
																			
																	if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $next_week_start_date && $chk_recu['task_due_date'] <= $next_week_end_date){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																	}
																} else if($type == 'this_month'){
																										 																		$this_month_start_date = date('Y-m-01',strtotime('this month'));
																	$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_month'){
																										 																		$next_month_start_date = date('Y-m-01',strtotime('next month'));
																	$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_to_next_month'){
																										 																		$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																	$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_ninty'){
																										 																		$next_ninty_start_date = date('Y-m-d');
																	$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'this_year'){
																										 																		$this_year_start_date = date('Y-01-01',strtotime('this year'));
																	$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'next_year'){
																										 																		$next_year_start_date = date('Y-01-01',strtotime('next year'));
																	$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																		
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
																} else if($type == 'overdue'){
															
																	if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																		array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																	}
															
																} else {
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															}
														}
													} else {
														if($type){
															if($type=='today'){
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] == date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type=='this_week'){
																$d = strtotime("today");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$week_start_date = date("Y-m-d",$start_week); 
																$week_end_date = date("Y-m-d",$end_week);
																
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $week_start_date && $chk_recu['task_due_date'] <= $week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type == 'next_week'){
																$d = strtotime("+1 week -1 day");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$next_week_start_date = date("Y-m-d",$start_week); 
																$next_week_end_date = date("Y-m-d",$end_week); 
																
																
																if($chk_recu['task_due_date'] != '0000-00-00' && $chk_recu['task_due_date'] >= $next_week_start_date && $chk_recu['task_due_date'] <= $next_week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
																}
															} else if($type == 'this_month'){
																										 																	$this_month_start_date = date('Y-m-01',strtotime('this month'));
																$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_month'){
																										 																	$next_month_start_date = date('Y-m-01',strtotime('next month'));
																$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_to_next_month'){
																										 																	$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_ninty'){
																										 																	$next_ninty_start_date = date('Y-m-d');
																$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_year'){
																										 																	$this_year_start_date = date('Y-01-01',strtotime('this year'));
																$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_year'){
																										 																	$next_year_start_date = date('Y-01-01',strtotime('next year'));
																$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'overdue'){
															
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															
															} else {
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$chk_recu);
															}
														}
													}
												}
											}
										} else {
											if($row['task_status_id'] == $st->task_status_id && $row['swimlane_id'] == $swm->swimlanes_id){
												if($user_team_id!="0" && $user_team_id!=get_authenticateUserID()){
													if($row['is_personal'] == "0"){
														if($type){
															if($type=='today'){
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type=='this_week'){
																$d = strtotime("today");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$week_start_date = date("Y-m-d",$start_week); 
																$week_end_date = date("Y-m-d",$end_week);
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_week'){
																$d = strtotime("+1 week -1 day");
																$start_week = strtotime("last sunday midnight",$d);
																$end_week = strtotime("next saturday",$d);
																$next_week_start_date = date("Y-m-d",$start_week); 
																$next_week_end_date = date("Y-m-d",$end_week); 
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
																$this_month_end_date = date('Y-m-t',strtotime('this month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_month'){
																											 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
																$next_month_end_date = date('Y-m-t',strtotime('next month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_to_next_month'){
																											 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
																$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_ninty'){
																											 																$next_ninty_start_date = date('Y-m-d');
																$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'this_year'){
																											 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
																$this_year_end_date = date('Y-12-t',strtotime('this year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'next_year'){
																											 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
																$next_year_end_date = date('Y-12-t',strtotime('next year'));
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																	
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
															} else if($type == 'overdue'){
																
																if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																	array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
																}
																
															} else {
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														}
													}
												} else {
													if($type){
														if($type=='today'){
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] == date("Y-m-d")){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type=='this_week'){
															$d = strtotime("today");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$week_start_date = date("Y-m-d",$start_week); 
															$week_end_date = date("Y-m-d",$end_week);
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $week_start_date && $row['task_due_date'] <= $week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_week'){
															$d = strtotime("+1 week -1 day");
															$start_week = strtotime("last sunday midnight",$d);
															$end_week = strtotime("next saturday",$d);
															$next_week_start_date = date("Y-m-d",$start_week); 
															$next_week_end_date = date("Y-m-d",$end_week); 
																
																
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_week_start_date && $row['task_due_date'] <= $next_week_end_date){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_month'){
																											 																$this_month_start_date = date('Y-m-01',strtotime('this month'));
															$this_month_end_date = date('Y-m-t',strtotime('this month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_month_start_date && $row['task_due_date'] <= $this_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_month'){
																										 																$next_month_start_date = date('Y-m-01',strtotime('next month'));
															$next_month_end_date = date('Y-m-t',strtotime('next month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_month_start_date && $row['task_due_date'] <= $next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_to_next_month'){
																										 																$next_to_next_month_start_date = date('Y-m-01',strtotime('+2 month'));
															$next_to_next_month_end_date = date('Y-m-t',strtotime('+2 month'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_to_next_month_start_date && $row['task_due_date'] <= $next_to_next_month_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_ninty'){
																										 																$next_ninty_start_date = date('Y-m-d');
															$next_ninty_end_date = date('Y-m-d',strtotime('+90 days'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_ninty_start_date && $row['task_due_date'] <= $next_ninty_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'this_year'){
																										 																$this_year_start_date = date('Y-01-01',strtotime('this year'));
															$this_year_end_date = date('Y-12-t',strtotime('this year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $this_year_start_date && $row['task_due_date'] <= $this_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'next_year'){
																										 																$next_year_start_date = date('Y-01-01',strtotime('next year'));
															$next_year_end_date = date('Y-12-t',strtotime('next year'));
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date'] >= $next_year_start_date && $row['task_due_date'] <= $next_year_end_date){
																
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
														} else if($type == 'overdue'){
															
															if($row['task_due_date'] != '0000-00-00' && $row['task_due_date']<date("Y-m-d")){
																array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
															}
															
														} else {
															array_push($task[$swm->swimlanes_id][$st->task_status_id],$row);
														}
													}
												}
											}
										}
										
									}
								}
							}
						}
					}
				}
			}
		}
		date_default_timezone_set("UTC");
		return $task; 
	}

	/*
	 * function : get_kanban_tasks_onlycompleted
	 * author : spaculus
	 * Get only completed task with limit
	 * return array
	 * */
        /**
         * It get only complete task for kanban design
         * @param int $status_id
         * @param int $swimlanes_id
         * @param string $type
         * @param int $user_team_id
         * @param int $project_id
         * @param int $user_color_id
         * @param int $limit
         * @param int $offset
         * @returns array|int
         */
	 function get_kanban_tasks_onlycompleted($status_id,$swimlanes_id = '',$type='',$user_team_id='',$project_id='',$user_color_id='',$limit=0,$offset =0)
	 {
	 	$CI =& get_instance();
		$users=array();
                $ids=array();
                $task_status_completed_id = $status_id;
		if($user_team_id == '#' && $project_id != 'all')
                    {
                         $users=get_user_under_project($project_id);
                         if(!empty($users)){
                            foreach($users as $user){
                                $ids[]=$user->user_id;
                             }
                         }
                    }
	 	$week_start_date = date('Y-m-d',strtotime('this week', time())); //current week first date
		$week_end_date = date("Y-m-d", strtotime('next Saturday')); //current week last date
							
		$CI->db->select('t.*,u.profile_image,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id  and tp.is_deleted = 0) AS tpp, uc.color_code, uc.outside_color_code, uc.color_name, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments , (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id  and w.user_id = '.get_authenticateUserID().') AS watch, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts, (SELECT COUNT(1) FROM task_and_project_files tpf  WHERE tpf.task_id = t.task_id) AS files ,(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies ',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		$CI->db->where('t.task_status_id',$status_id);
		$CI->db->where('uts.swimlane_id',$swimlanes_id);
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		
		if($user_team_id == '#')
                {
                    //array_push($ids, $CI->session->userdata('user_id'));
                    $CI->db->where_in('t.task_allocated_user_id',$ids);
                }else{
		if($user_team_id){
			if($user_team_id != get_authenticateUserID()){
				$CI->db->where('t.is_personal','0');
			}
			$CI->db->where('t.task_allocated_user_id',$user_team_id);
		} else {
			$CI->db->where('t.task_allocated_user_id',$CI->session->userdata('user_id'));
		}
                } 
                
		if($project_id != 'all'){
			
			$CI->db->where_in('t.task_project_id',$project_id);
			
		} 
		if($user_color_id!='0'){
			$CI->db->where('uts.color_id',$user_color_id);
		}
		if($type){
			if($type=='today'){
				$CI->db->where('t.task_due_date',date('Y-m-d'));
			} else if($type=='this_week'){
				$week_start_date = date('Y-m-d',strtotime('this week', time())); //current week first date
				$week_end_date = date("Y-m-d", strtotime('next Saturday')); //current week last date
				$CI->db->where('t.task_due_date >= ',$week_start_date);
				$CI->db->where('t.task_due_date <= ',$week_end_date);
			} else if($type == 'next_week'){
				$next_week_start_date = date('Y-m-d',strtotime('next week', time())); //next week first date
				$next_week_end_date = date("Y-m-d", strtotime('+5 days',strtotime($next_week_start_date))); //next week last date
				$CI->db->where('t.task_due_date >= ',$next_week_start_date);
				$CI->db->where('t.task_due_date <= ',$next_week_end_date);
			} else {
				
			}
		}
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.is_deleted','0');
		$CI->db->order_by('uts.kanban_order','asc');
		$CI->db->group_by('t.task_id');
		
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get();
		//echo "<pre>"; echo $CI->db->last_query(); die();
		if($query->num_rows()>0){
			return   $query->result_array();
		} else {
			return 0;
		}
	 }
	
	/**
         * It get team member list under any manager by user_id
         * @returns int
         */
	  
	function get_users_under_manager(){
		$CI =& get_instance();
		$user_ids = array();
		$CI->db->select('um.user_id');
		$CI->db->from('user_managers um');
		$CI->db->join('users u','u.user_id = um.user_id');
		$CI->db->where('um.manager_id',$CI->session->userdata('user_id'));
		$CI->db->where('u.user_status','Active');
		$CI->db->where('u.is_deleted','0');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $row){
				$user_ids[] = $row['user_id'];
			}
			return $user_ids;
		} else {
			return 0;
		}
	}
	
	/**
         * It returns task steps details from DB
         * @param int $step_id
         * @returns array|int
         */
	
	function get_task_step_detail($step_id){
		$CI =& get_instance();
		$query = $CI->db->get_where('task_steps',array('task_step_id'=>$step_id));
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	/**
         * It checks dependency status of task
         * @param int $task_id
         * @param int $completed_id
         * @return int|string
         */
	function chk_dependency_status($task_id,$completed_id){
		$CI =& get_instance();
		$query = $CI->db->get_where('tasks',array('prerequisite_task_id'=>$task_id,'task_owner_id !=' => '0', 'task_allocated_user_id !=' => '0', 'is_prerequisite_task'=>'1','is_deleted'=>'0'));
		if($query->num_rows()>0){
			$res = $query->result_array();
			$st = '';
			foreach($res as $row){
				if($row['task_status_id'] != $completed_id){
					return 'red';
				} 
			}
			return 'green';
		} else {
			return 0;
		}
	}
	/**
         * 
         * @param int $task_id
         * @param int $user_id
         * @returns int
         */
	function check_my_watch_list($task_id,$user_id){
		$CI =& get_instance();
		$CI->db->select('id');
		$CI->db->from('my_watch_list');
		$CI->db->where('task_id',$task_id);
		$CI->db->where('user_id',$user_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->id;
		} else {
			return 0;
		}
	}
	/**
         * It checks task existances.
         * @param int $task_id
         * @returns int
         */
	function chk_task_exists($task_id){
		$CI =& get_instance();
		$query = $CI->db->get_where('tasks',array('task_id'=>$task_id,'task_owner_id !=' => '0', 'task_allocated_user_id !=' => '0'));
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	} 
	/**
         * It get last logged in user kanban order from DB.
         * @param int $user_id
         * @param int $status_id
         * @returns int
         */
	function get_user_last_kanban_order($user_id,$status_id){
		$CI =& get_instance();
		$CI->db->select('MAX(uts.kanban_order) as seq');
		$CI->db->from('user_task_swimlanes uts');
		$CI->db->join('tasks t','t.task_id = uts.task_id');
		$CI->db->where('uts.user_id',$user_id);
		$CI->db->where('t.task_status_id',$status_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->seq;
		} else {
			return 0;
		}
	}
	/**
         * It get last logged in user calender order from DB.
         * @param int $user_id
         * @param int $status_id
         * @returns int
         */
	function get_user_last_calnder_order($user_id,$schedule_date=''){
		$CI =& get_instance();
		if($schedule_date!='0000-00-00'){
			$CI->db->select('MAX(uts.calender_order) as seq');
			$CI->db->from('user_task_swimlanes uts');
			$CI->db->join('tasks t','t.task_id = uts.task_id');
			$CI->db->where('t.task_owner_id != ',"0");
			$CI->db->where('t.task_allocated_user_id != ',"0");
			$CI->db->where('uts.user_id',$user_id);
			$CI->db->where('t.task_scheduled_date',$schedule_date);
			$query = $CI->db->get();
			if($query->num_rows()>0){
				$res = $query->row();
				return $res->seq;
			} else {
				return 0;
			}
		}  else {
			return 0;
		}
	}
	
	function chk_last_remember_exists(){
		$CI =& get_instance();
		$query = $CI->db->get_where('last_remember_search',array('user_id'=>$CI->session->userdata('user_id')));
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0; 
		}
	}

	function get_user_last_rember_values($user_id = ""){
		$CI =& get_instance();
		if($user_id){
			$user_id = $user_id;
		} else {
			$user_id = $CI->session->userdata('user_id');
		}
		$query = $CI->db->get_where('last_remember_search',array('user_id'=>$user_id));
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0; 
		}
	}
	
	function get_next_status_id($task_status_id){
		$CI =& get_instance();
		$task_seq = get_task_sequence($task_status_id);
		$subQuery1 = $CI->db->select('min(task_sequence) as seq')->from('task_status')->where('task_sequence >',$task_seq)->where('company_id',$CI->session->userdata('company_id'))->get();
		$res1 = $subQuery1->row()->seq;
		
		$subQuery2 = $CI->db->select('min(task_sequence) as seq')->from('task_status')->where('task_sequence <',$task_seq)->where('company_id',$CI->session->userdata('company_id'))->get();
		$res2 = $subQuery2->row()->seq;
		
		if($res1){
			$query = $CI->db->select('task_status_id')->from('task_status')->where('task_sequence',$res1)->where('company_id',$CI->session->userdata('company_id'))->where('task_status_flag','Active')->order_by('task_sequence','asc')->get();
		} else {
			$query = $CI->db->select('task_status_id')->from('task_status')->where('task_sequence',$res2)->where('company_id',$CI->session->userdata('company_id'))->where('task_status_flag','Active')->order_by('task_sequence','asc')->get();
		}
		
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_id;
		} else {
			return 0;
		}
	}

	function get_status_id_before_completed($completed_id){
		$CI =& get_instance();
		$task_seq = get_task_sequence($completed_id);
		
		$subQuery1 = $CI->db->select('max(task_sequence) as seq')->from('task_status')->where('task_sequence >',$task_seq)->where('company_id',$CI->session->userdata('company_id'))->get();
		$res1 = $subQuery1->row()->seq;
		
		
		if($res1){
			$query = $CI->db->select('task_status_id')->from('task_status')->where('task_sequence',$res1)->where('company_id',$CI->session->userdata('company_id'))->where('task_status_flag','Active')->order_by('task_sequence','asc')->get();
		} else {
			$subQuery2 = $CI->db->select('max(task_sequence) as seq')->from('task_status')->where('task_sequence <',$task_seq)->where('company_id',$CI->session->userdata('company_id'))->get();
			$res2 = $subQuery2->row()->seq;
			$query = $CI->db->select('task_status_id')->from('task_status')->where('task_sequence',$res2)->where('company_id',$CI->session->userdata('company_id'))->where('task_status_flag','Active')->order_by('task_sequence','asc')->get();
		}
		
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_id;
		} else {
			return 0;
		}
	}

	/**
         * It get task due date via task_id
         * @param int $task_id
         * @returns int
         */

	function get_task_due_date($task_id){
		$CI =& get_instance();
		$CI->db->select('task_due_date');
		$CI->db->from('tasks');
		$CI->db->where('task_owner_id != ',"0");
		$CI->db->where('task_allocated_user_id != ',"0");
		$CI->db->where('task_id',$task_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_due_date;
		} else {
			return 0;
		}
	}
	/**
         * It get task schedule date from DB.
         * @param int $task_id
         * @return int
         */
	function get_task_schedule_date($task_id){
		$CI =& get_instance();
		$CI->db->select('task_scheduled_date');
		$CI->db->from('tasks');
		$CI->db->where('task_owner_id != ',"0");
		$CI->db->where('task_allocated_user_id != ',"0");
		$CI->db->where('task_id',$task_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_scheduled_date;
		} else {
			return 0;
		}
	}
	
	function chk_virtual_recurrence_exists($master_task_id,$orig_scheduled_date,$task_status_completed_id=''){
		
		$CI =& get_instance();
		
		if($task_status_completed_id){
			$task_status_completed_id = $task_status_completed_id;
		} else {
			$task_status_completed_id = $CI->config->item('completed_id');
		}
		
		$CI->db->select('t.*,u.first_name,u.last_name,u.profile_image as allocated_user_profile_image ,tc.category_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,uc.color_code, uc.outside_color_code,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies,(SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch,(SELECT COUNT(1) FROM task_and_project_files tpf  WHERE tpf.task_id = t.task_id and tpf.is_deleted=0) AS files',FALSE);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		$CI->db->join('task_category tc','t.task_category_id = tc.category_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		$CI->db->where('t.master_task_id',$master_task_id);
		$CI->db->where('t.task_orig_scheduled_date',$orig_scheduled_date);
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	
	function kanban_recurrence_logic($main_arr,$orig_due_date = '',$off_days=''){
		$CI =& get_instance();
		
		$data = array();
		
		if($orig_due_date == ''){
			
			$display = date("Y-m-d",strtotime($main_arr['start_on_date']));
			
			$data = $main_arr;
			$actual_day = $display;
			$data['task_orig_scheduled_date'] = $display;
			$data['task_scheduled_date'] = $actual_day;
			$data['task_due_date'] = $actual_day;
			$data['master_task_id'] = $main_arr['task_id'];
			$data['task_id'] = 'child_'.$main_arr['task_id'];
			$data['task_orig_due_date'] = $actual_day;
			
		} else {
			
			$recurrence_type = $main_arr['recurrence_type'];
			
			if($off_days){
				$off_days = $off_days;
			} else {
				$off_days = get_company_offdays();
			}
			
			switch($recurrence_type){
				case '1':
					$display = daily_task_kanban_logic($main_arr,$orig_due_date,$off_days);
					break;
							
				case '2' :
					$display = weekly_task_kanban_logic($main_arr,$orig_due_date,$off_days);
					break;
					
				case '3' :
					$display = monthly_task_kanban_logic($main_arr,$orig_due_date,$off_days);
					break;
					
				case '4' :
					$display = yearly_task_kanban_logic($main_arr,$orig_due_date,$off_days);
					break;
					
				default :
					break;
			}
			
			if($main_arr['end_by_date']!='0000-00-00'){
				$end_date = change_date_format($main_arr['end_by_date']);
			} else {
				$end_date = '';	
			}
			
			if($end_date == '' || (strtotime($display) <= strtotime($end_date))){
				$data = $main_arr;
				$actual_day = $display;
				$data['task_orig_scheduled_date'] = $display;
				$data['task_scheduled_date'] = $actual_day;
				$data['task_due_date'] = $actual_day;
				$data['master_task_id'] = $main_arr['task_id'];
				$data['task_id'] = 'child_'.$main_arr['task_id'];
				$data['task_orig_due_date'] = $actual_day;
			}
		}

		return $data;
	}

	function daily_task_kanban_logic($main_arr,$orig_due_date='',$off_days){
		
		if($orig_due_date){
			$start_on_date = change_date_format($orig_due_date);
		} else {
			$start_on_date = change_date_format($main_arr['start_on_date']);
		}
		
		if($main_arr['Daily_every_weekday']){
			if($main_arr['Daily_every_week_day'] == 0){
				break;
			}
			$display = chk_company_working_day_next(date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_week_day'].' days')),$off_days);
			
			
		} else {
			
			$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_day'].' days')); //gives after 2 days date without including saturday sunday only business days.
			
		}
		return $display;
	}

	function weekly_task_kanban_logic($main_arr,$orig_due_date='',$off_days){
		$return_date = '';
		$child_arr = array();
		$data = array();
		if($orig_due_date){
			$start_on_date = change_date_format($orig_due_date);
		} else {
			$start_on_date = change_date_format($main_arr['start_on_date']);
		}
		if($main_arr['Weekly_week_day']!=''){
			$Weekly_week_day_arr = explode(',', $main_arr['Weekly_week_day']);
			
			foreach($Weekly_week_day_arr as $week){
				
				if($week == '1'){
					$dow   = 'Monday';
					$step  = $main_arr['Weekly_every_week_no'];
					$unit  = 'W';
					
					$start = new DateTime($start_on_date);
					
					$start->modify($dow); // Move to first occurence
					
					$interval = new DateInterval("P{$step}{$unit}");
					$period   = new DatePeriod($start, $interval, '1', '1');
					
					foreach ($period as $date) {
					    $display[] = $date->format('Y-m-d');
					}
				}
				if($week == '2'){
					$dow   = 'Tuesday';
					$step  = $main_arr['Weekly_every_week_no'];
					$unit  = 'W';
					
					$start = new DateTime($start_on_date);
					
					$start->modify($dow); // Move to first occurence
					
					$interval = new DateInterval("P{$step}{$unit}");
					$period   = new DatePeriod($start, $interval, '1', '1');
					
					foreach ($period as $date) {
					    $display[] = $date->format('Y-m-d');
					}
				}
				if($week == '3'){
					$dow   = 'Wednesday';
					$step  = $main_arr['Weekly_every_week_no'];
					$unit  = 'W';
					
 					$start = new DateTime($start_on_date);
					
					$start->modify($dow); // Move to first occurence
					
					$interval = new DateInterval("P{$step}{$unit}");
					$period = new DatePeriod($start, $interval, '1', '1');
					
					foreach ($period as $date) {
					    $display[] = $date->format('Y-m-d');
					}
				}
				if($week == '4'){
					$dow   = 'Thursday';
					$step  = $main_arr['Weekly_every_week_no'];
					$unit  = 'W';
					
					$start = new DateTime($start_on_date);
					
					$start->modify($dow); // Move to first occurence
					
					$interval = new DateInterval("P{$step}{$unit}");
					$period   = new DatePeriod($start, $interval, '1', '1');
					
					foreach ($period as $date) {
					    $display[] = $date->format('Y-m-d');
					}
				}
				if($week == '5'){
					$dow   = 'Friday';
					$step  = $main_arr['Weekly_every_week_no'];
					$unit  = 'W';
					
					$start = new DateTime($start_on_date);
					
					$start->modify($dow); // Move to first occurence
					
					$interval = new DateInterval("P{$step}{$unit}");
					$period   = new DatePeriod($start, $interval, '1', '1');
					
					foreach ($period as $date) {
					    $display[] = $date->format('Y-m-d');
					}
				}
				if($week == '6'){
					$dow   = 'Saturday';
					$step  = $main_arr['Weekly_every_week_no'];
					$unit  = 'W';
					
					$start = new DateTime($start_on_date);
					//$end   = new DateTime($end_date);
					
					$start->modify($dow); // Move to first occurence
					
					$interval = new DateInterval("P{$step}{$unit}");
					$period   = new DatePeriod($start, $interval, '1', '1');
					
					foreach ($period as $date) {
					    $display[] = $date->format('Y-m-d');
					}
				}
				if($week == '7'){
					$dow   = 'Sunday';
					$step  = $main_arr['Weekly_every_week_no'];
					$unit  = 'W';
					
					$start = new DateTime($start_on_date);
					//$end   = new DateTime($end_by_date);
					
					$start->modify($dow); // Move to first occurence
					
					$interval = new DateInterval("P{$step}{$unit}");
					$period   = new DatePeriod($start, $interval, '1', '1');
					
					foreach ($period as $date) {
					    $display[] = $date->format('Y-m-d');
					}
				}
			}
			$dates = array();
			
			if($display){
				foreach($display as $date){
					$dates[] = $date;
				}
			}
			
			$return_date = reset($dates);
		}
		
		return $return_date;
	}


	function monthly_task_kanban_logic($main_arr,$orig_due_date='',$offdays){
		
		$child_arr = array();
		$data = array();
		if($orig_due_date){
			$start_on_date = change_date_format($orig_due_date);
		} else {
			$start_on_date = change_date_format($main_arr['start_on_date']);
		}
		
		
		if($main_arr['Monthly_op1_1']!='0' && $main_arr['Monthly_op1_2']!='0'){
		
			$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9
			
			if($Monthly_op1_1_day == "29" || $Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
				$start_on_date = date("Y-m-01",strtotime($start_on_date));
				$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
				$display = date('Y-m-t', strtotime($effectiveDate));
			} else {
				$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
				$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
			}
			
		} elseif($main_arr['Monthly_op2_1']!='' && $main_arr['Monthly_op2_2']!='' && $main_arr['Monthly_op2_3']!='0'){
			
			$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
			$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));
			
			
		} elseif($main_arr['Monthly_op3_1']!='0' && $main_arr['Monthly_op3_2']!='0'){
			
			if($main_arr['Monthly_op3_1']<0){
				
				
				$start_on_date = date("Y-m-01",strtotime($start_on_date));
				$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
				if($main_arr['Monthly_op3_1'] == '-1'){
					
				} else {
					$temp_date = date("Y-m-d",strtotime($main_arr['Monthly_op3_1']." days",strtotime($effectiveDate)));
					if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
						$effectiveDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($effectiveDate)) . " + 1 month"));
					} else {
						$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
					}
					for($a=-1;$a>=$main_arr['Monthly_op3_1'];$a--){
						$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
						if(chk_company_offday_date($effectiveDate,$offdays)){
							$a++;
						}
						
					}
				}
				$display = chk_company_offday(date("Y-m-d",strtotime($effectiveDate)),$offdays);
			} else {
				$start_on_date = date("Y-m-01",strtotime($start_on_date));
				$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
				
				if($main_arr['Monthly_op3_1'] == '1'){
					$display = chk_company_working_day_next(date("Y-m-d",strtotime($effectiveDate)),$offdays);
				} else {
					$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
					for($a=1;$a<=$main_arr['Monthly_op3_1'];$a++){
						$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
						if(chk_company_offday_date($effectiveDate,$offdays)){
							$a--;
						}
					}
					$display = date("Y-m-d",strtotime($effectiveDate));
				}
			}
		}

		return $display;
	}
	
	function yearly_task_kanban_logic($main_arr,$orig_due_date='',$offdays){
		$child_arr = array();
		$data = array();
		
		if($orig_due_date){
			$start_on_date = change_date_format($orig_due_date);
		} else {
			$start_on_date = change_date_format($main_arr['start_on_date']);
		}

		if($main_arr['Yearly_op1'] != '0'){
				
			$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + ".$main_arr['Yearly_op1']." year"));
			
			
		} elseif($main_arr['Yearly_op2_1']!='0' && $main_arr['Yearly_op2_2']!='0'){
			
			$year = date('Y',strtotime($start_on_date));
			$month = date('m',strtotime($start_on_date));
			$day = date('d',strtotime($start_on_date));
			
			$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
			
			$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));
			
			
		} elseif($main_arr['Yearly_op3_1']!='0' && $main_arr['Yearly_op3_2']!='' && $main_arr['Yearly_op3_3']!='0'){
			//echo $start_on_date;die;
			$year = date('Y',strtotime($start_on_date));
			$month = date('m',strtotime($start_on_date));
			$day = date('d',strtotime($start_on_date));
			
			$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
			
			$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
			
			
		} elseif($main_arr['Yearly_op4_1'] != '0' && $main_arr['Yearly_op4_2']!= ''){
			
			$year = date('Y',strtotime($start_on_date));
			$month = date('m',strtotime($start_on_date));
			$day = date('d',strtotime($start_on_date));
			
			if($main_arr['Yearly_op4_1']<0){
				if($year >= date('Y')){
					if(date('m', strtotime($main_arr['Yearly_op4_2'])) >= $month){
						if($i==0){
							$year = $year;
						} else {
							$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
						}
						
					} else {
						$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
					}
				} else {
					$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
				}
				$monthyear = date('Y-m-t',strtotime($main_arr['Yearly_op4_2']." ".$year));
				
				if($main_arr['Yearly_op4_1'] == '-1'){
				
				} else {
					$temp_date = date("Y-m-d",strtotime($main_arr['Yearly_op4_1']." days",strtotime($monthyear)));
					if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
						$monthyear = date("Y-m-d",strtotime(date("Y-m-d", strtotime($monthyear)) . " + 1 year"));
					}
					for($a=-1;$a>$main_arr['Yearly_op4_1'];$a--){
						if(chk_company_offday_date($monthyear,$offdays)){
							$a++;
						}
						$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
					}
				}
				$display = chk_company_offday(date("Y-m-d",strtotime($monthyear)),$offdays);
			} else {
				if($year >= date('Y')){
					if(date('m', strtotime($main_arr['Yearly_op4_2'])) > $month){
						if($i==0){
							$year = $year;
						} else {
							$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
						}
					} else {
						$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
					}
				} else {
					$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
				}
				$monthyear = date('Y-m-01',strtotime($main_arr['Yearly_op4_2']." ".$year));
				
				if($main_arr['Yearly_op4_1'] == '1'){
					$display = chk_company_working_day_next(date("Y-m-d",strtotime($monthyear)),$offdays);
				} else {
					$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
					for($a=1;$a<=$main_arr['Yearly_op4_1'];$a++){
						$monthyear = date("Y-m-d",strtotime("+1 days",strtotime($monthyear)));
						if(chk_company_offday_date($monthyear,$offdays)){
							$a--;
						}
					}
					$display = date("Y-m-d",strtotime($monthyear));
				}
			}
		}
		
		return $display;
	}
	
	function monthly_recurrence_logic($main_arr,$start_date,$end_date,$offdays=''){
		$CI =& get_instance();
		
		$data = array();
		
		if($offdays){
			$offdays = $offdays;
		} else {
			$offdays = get_company_offdays();
		}
		
		$recurrence_type = $main_arr['recurrence_type'];
		
		//$end_date = date("Y-m-d",strtotime($end_date . ' + 5 days'));
		
		switch($recurrence_type){
			case '1':
				$data = daily_task_monthly_logic($main_arr,$start_date,$end_date,$offdays);
				break;
						
			case '2' :
				$data = weekly_task_monthly_logic($main_arr,$start_date,$end_date,$offdays);
				break;
				
			case '3' :
				$data = monthly_task_monthly_logic($main_arr,$start_date,$end_date,$offdays);
				break;
				
			case '4' :
				$data = yearly_task_monthly_logic($main_arr,$start_date,$end_date,$offdays);
				break;
				
			default :
				break;
		}
		//pr($data);die;
		return $data;
	}
	
	function daily_task_monthly_logic($main_arr,$start_date,$end_date,$offdays){
		
		$CI =& get_instance();
		
		$child_arr = array();
		$data = array();
		
		$start_on_date = change_date_format($main_arr['start_on_date']);
		if($main_arr['no_end_date'] == '2'){
			$end_after_recurrence = $main_arr['end_after_recurrence'];
			if($end_after_recurrence){
				
				$display = $start_on_date;	
				
				if($display>=$start_date && $display<=$end_date){
					//echo "in================";
					$data = $main_arr;
					$data['task_orig_scheduled_date'] = $display;
					$data['task_scheduled_date'] = $display;
					$data['task_due_date'] = $display;
					$data['master_task_id'] = $main_arr['task_id'];
					$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
					$data['task_orig_due_date'] = $display;
					
					array_push($child_arr,$data);
					//pr($data);
				}
				
				for($i=1;$i<$end_after_recurrence;$i++){
					
					if($main_arr['Daily_every_weekday']){
						if($main_arr['Daily_every_week_day'] == 0){
							break;
						}
						$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_week_day'].' days'));
						
						if(chk_company_offday_date($display,$offdays)){
							$i--;
							if($main_arr['Daily_every_week_day']>1){
								for($k=1;$k<$main_arr['Daily_every_week_day'];$k++){
									$display = date('Y-m-d', strtotime($display . ' + 1 days'));
									if(chk_company_offday_date($display,$offdays)){
										$display = date('Y-m-d', strtotime($display . ' + 1 days'));
									} else {
										break;
									}
								}
								$i++;
								if($display>=$start_date && $display<=$end_date){
									
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
							}
						} else {
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
						}
						
						
						
					} else {
						if($main_arr['Daily_every_day'] == 0){
							break;
						}
						$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_day'].' days')); //gives after 2 days date without including saturday sunday only business days.
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
					}
					$start_on_date = $display;
				}
			}
		} elseif($main_arr['no_end_date'] == '3'){
			
			$end_by_date = change_date_format($main_arr['end_by_date']);
			
			$display = $start_on_date;	
			if($display>=$start_date && $display<=$end_date){
				
				$data = $main_arr;
				$data['task_orig_scheduled_date'] = $display;
				$data['task_scheduled_date'] = $display;
				$data['task_due_date'] = $display;
				$data['master_task_id'] = $main_arr['task_id'];
				$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
				$data['task_orig_due_date'] = $display;
				
				array_push($child_arr,$data);
			}
			$i = 1;
			
			while (strtotime($start_on_date) < strtotime($end_by_date)) {
				if($main_arr['Daily_every_weekday']){
					if($main_arr['Daily_every_week_day'] == 0){
						break;
					}
					$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_week_day'].' days'));
					if(chk_company_offday_date($display,$offdays)){
						$i--;
						if($main_arr['Daily_every_week_day']>1){
							for($k=1;$k<$main_arr['Daily_every_week_day'];$k++){
								$display = date('Y-m-d', strtotime($display . ' + 1 days'));
								if(chk_company_offday_date($display,$offdays)){
									$display = date('Y-m-d', strtotime($display . ' + 1 days'));
								} else {
									break;
								}
							}
							$i++;
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
						}
					} else {
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
					}
					
				
				} else {
				
					if($main_arr['Daily_every_day'] == 0){
						break;
					}
					//$Daily_every_day = '2'; //means every 2nd day. i.e mon, wed, fri
					
					$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_day'].' days')); //gives after 2 days date without including saturday sunday only business days.
					
					if($display>=$start_date && $display<=$end_date){
							
						$data = $main_arr;
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
				}
				$i++;
				$start_on_date = $display;
				//print_r($child_arr); echo '======='; print_r($main_arr);
			}
			
		} else {
			
			
			$display = $start_on_date;	
			if($display>=$start_date && $display<=$end_date){
				
				$data = $main_arr;
				$data['task_orig_scheduled_date'] = $display;
				$data['task_scheduled_date'] = $display;
				$data['task_due_date'] = $display;
				$data['master_task_id'] = $main_arr['task_id'];
				$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
				$data['task_orig_due_date'] = $display;
				
				array_push($child_arr,$data);
			}
			$i = 1;
			
			while (strtotime($start_on_date) < strtotime($end_date)) {
				
				
				
				if($main_arr['Daily_every_weekday']){
					if($main_arr['Daily_every_week_day'] == 0){
						break;
					}
					$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_week_day'].' days'));
					//echo "ram".$display;die;
					if(chk_company_offday_date($display,$offdays)){
						$i--;
						if($main_arr['Daily_every_week_day']>1){
							for($k=1;$k<$main_arr['Daily_every_week_day'];$k++){
								$display = date('Y-m-d', strtotime($display . ' + 1 days'));
								if(chk_company_offday_date($display,$offdays)){
									$display = date('Y-m-d', strtotime($display . ' + 1 days'));
								} else {
									break;
								}
							}
							$i++;
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
						}
					} else {
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
					}
					
				
				} else {
				
					//$Daily_every_day = '2'; //means every 2nd day. i.e mon, wed, fri
					if($main_arr['Daily_every_day'] == 0){
						break;
					}
					$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_day'].' days')); //gives after 2 days date without including saturday sunday only business days.
					if($display>=$start_date && $display<=$end_date){
						
						$data = $main_arr;
						
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
				}
				$i++;
				if($display > $end_date){
					break;
				}
				$start_on_date = $display;
				//print_r($child_arr); echo '======='; print_r($main_arr);
			}
			
		}
		
		return $child_arr;
	}

	function weekly_task_monthly_logic($main_arr,$start_date,$end_date,$offdays){
		
		
		$CI =& get_instance();
		
		$child_arr = array();
		$data = array();
		
		$start_on_date = change_date_format($main_arr['start_on_date']);
		
		if($main_arr['no_end_date'] == '2'){
			
			$end_after_recurrence = $main_arr['end_after_recurrence'];
			
			if($end_after_recurrence){
				
				
				
				if($main_arr['Weekly_week_day']!=''){
					$Weekly_week_day_arr = explode(',', $main_arr['Weekly_week_day']);
					$i = 0;
					foreach($Weekly_week_day_arr as $week){
						if($week == '1'){
							$dow   = 'Monday';
							$step  = $main_arr['Weekly_every_week_no'];
							$unit  = 'W';
							
							$start = new DateTime($start_on_date);
							
							$start->modify($dow); // Move to first occurence
							//$end->add(new DateInterval('P1Y')); // Move to 1 year from start
							$occurence = $end_after_recurrence-1;
							$interval = new DateInterval("P{$step}{$unit}");
							$period   = new DatePeriod($start, $interval, $occurence, 0);
							
							foreach ($period as $date) {
							    $display = $date->format('Y-m-d');
								if($display>=$start_date && $display<=$end_date){
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
								$i++;
							}
						}
						if($week == '2'){
							$dow   = 'Tuesday';
							$step  = $main_arr['Weekly_every_week_no'];
							$unit  = 'W';
							
							$start = new DateTime($start_on_date);
							
							$start->modify($dow); // Move to first occurence
							//$end->add(new DateInterval('P1Y')); // Move to 1 year from start
							$occurence = $end_after_recurrence-1;
							$interval = new DateInterval("P{$step}{$unit}");
							$period   = new DatePeriod($start, $interval, $occurence, 0);
							
							foreach ($period as $date) {
							    $display = $date->format('Y-m-d');
								if($display>=$start_date && $display<=$end_date){
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
								$i++;
							}
						}
						if($week == '3'){
							$dow   = 'Wednesday';
							$step  = $main_arr['Weekly_every_week_no'];
							$unit  = 'W';
							
							$start = new DateTime($start_on_date);
							
							$start->modify($dow); // Move to first occurence
							//$end->add(new DateInterval('P1Y')); // Move to 1 year from start
							$occurence = $end_after_recurrence-1;
							$interval = new DateInterval("P{$step}{$unit}");
							$period   = new DatePeriod($start, $interval, $occurence, 0);
							
							foreach ($period as $date) {
							    $display = $date->format('Y-m-d');
								if($display>=$start_date && $display<=$end_date){
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
								$i++;
							}
						}
						if($week == '4'){
							$dow   = 'Thursday';
							$step  = $main_arr['Weekly_every_week_no'];
							$unit  = 'W';
							
							$start = new DateTime($start_on_date);
							
							$start->modify($dow); // Move to first occurence
							//$end->add(new DateInterval('P1Y')); // Move to 1 year from start
							$occurence = $end_after_recurrence-1;
							$interval = new DateInterval("P{$step}{$unit}");
							$period   = new DatePeriod($start, $interval, $occurence, 0);
							
							foreach ($period as $date) {
							    $display = $date->format('Y-m-d');
								if($display>=$start_date && $display<=$end_date){
									
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
								$i++;
							}
						}
						if($week == '5'){
							$dow   = 'Friday';
							$step  = $main_arr['Weekly_every_week_no'];
							$unit  = 'W';
							
							$start = new DateTime($start_on_date);
							
							$start->modify($dow); // Move to first occurence
							//$end->add(new DateInterval('P1Y')); // Move to 1 year from start
							$occurence = $end_after_recurrence-1;
							$interval = new DateInterval("P{$step}{$unit}");
							$period   = new DatePeriod($start, $interval, $occurence, 0);
							
							foreach ($period as $date) {
							    $display = $date->format('Y-m-d');
								if($display>=$start_date && $display<=$end_date){
									
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
								$i++;
							}
						}
						if($week == '6'){
							$dow   = 'Saturday';
							$step  = $main_arr['Weekly_every_week_no'];
							$unit  = 'W';
							
							$start = new DateTime($start_on_date);
							
							$start->modify($dow); // Move to first occurence
							//$end->add(new DateInterval('P1Y')); // Move to 1 year from start
							$occurence = $end_after_recurrence-1;
							$interval = new DateInterval("P{$step}{$unit}");
							$period   = new DatePeriod($start, $interval, $occurence, 0);
							
							foreach ($period as $date) {
							    $display = $date->format('Y-m-d');
								if($display>=$start_date && $display<=$end_date){
									
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
								$i++;
							}
						}
						if($week == '7'){
							$dow   = 'Sunday';
							$step  = $main_arr['Weekly_every_week_no'];
							$unit  = 'W';
							
							$start = new DateTime($start_on_date);
							
							$start->modify($dow); // Move to first occurence
							//$end->add(new DateInterval('P1Y')); // Move to 1 year from start
							$occurence = $end_after_recurrence-1;
							$interval = new DateInterval("P{$step}{$unit}");
							$period   = new DatePeriod($start, $interval, $occurence, 0);
							
							foreach ($period as $date) {
							    $display = $date->format('Y-m-d');
								if($display>=$start_date && $display<=$end_date){
									
									$data = $main_arr;
									$data['task_orig_scheduled_date'] = $display;
									$data['task_scheduled_date'] = $display;
									$data['task_due_date'] = $display;
									$data['master_task_id'] = $main_arr['task_id'];
									$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
									$data['task_orig_due_date'] = $display;
									
									array_push($child_arr,$data);
								}
								$i++;
							}
						}
						$i++;
					}
				}
			}
			
		} elseif($main_arr['no_end_date'] == '3'){
			
			//$end_by_date = $main_arr['end_by_date'];
			$end_date1 = change_date_format($main_arr['end_by_date']);
			$end_date2 = change_date_format($end_date);
			
			if(strtotime($end_date1)>=strtotime($end_date2)){
				$end_by_date = change_date_format($end_date);
			} else {
				$end_by_date = change_date_format($main_arr['end_by_date']);
			}
			
			//echo $end_by_date;die;
			if($main_arr['Weekly_week_day']!=''){
				$Weekly_week_day_arr = explode(',', $main_arr['Weekly_week_day']);
				$i = 0;
				foreach($Weekly_week_day_arr as $week){
					if($week == '1'){
						$dow   = 'Monday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_by_date);
						$end = $end->modify('+1 day'); 
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '2'){
						$dow   = 'Tuesday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_by_date);
						$end = $end->modify('+1 day'); 
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '3'){
						$dow   = 'Wednesday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_by_date);
						$end = $end->modify('+1 day'); 
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					
					if($week == '4'){
						$dow   = 'Thursday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_by_date);
						$end = $end->modify('+1 day'); 
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '5'){
						$dow   = 'Friday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_by_date);
						$end = $end->modify('+1 day'); 
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '6'){
						$dow   = 'Saturday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_by_date);
						$end = $end->modify('+1 day'); 
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '7'){
						$dow   = 'Sunday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_by_date);
						$end = $end->modify('+1 day'); 
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					$i++;
				}
			}
			
		} else {
			
			
			if($main_arr['Weekly_week_day']!=''){
				$Weekly_week_day_arr = explode(',', $main_arr['Weekly_week_day']);
				$i = 0;
				foreach($Weekly_week_day_arr as $week){
					if($week == '1'){
						$dow   = 'Monday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_date);
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '2'){
						$dow   = 'Tuesday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_date);
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '3'){
						$dow   = 'Wednesday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_date);
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '4'){
						$dow   = 'Thursday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_date);
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '5'){
						$dow   = 'Friday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_date);
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '6'){
						$dow   = 'Saturday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_date);
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					if($week == '7'){
						$dow   = 'Sunday';
						$step  = $main_arr['Weekly_every_week_no'];
						$unit  = 'W';
						
						$start = new DateTime($start_on_date);
						$end   = new DateTime($end_date);
						
						$start->modify($dow); // Move to first occurence
						
						$interval = new DateInterval("P{$step}{$unit}");
						$period   = new DatePeriod($start, $interval, $end);
						
						foreach ($period as $date) {
						    $display = $date->format('Y-m-d');
							if($display>=$start_date && $display<=$end_date){
								
								$data = $main_arr;
								$data['task_orig_scheduled_date'] = $display;
								$data['task_scheduled_date'] = $display;
								$data['task_due_date'] = $display;
								$data['master_task_id'] = $main_arr['task_id'];
								$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
								$data['task_orig_due_date'] = $display;
								
								array_push($child_arr,$data);
							}
							$i++;
						}
					}
					$i++;
				}
			}
			
		}

		return $child_arr;
	}
	
	function monthly_task_monthly_logic($main_arr,$start_date,$end_date,$offdays){
		
		$CI =& get_instance();
		
		$child_arr = array();
		$data = array();
		
		$start_on_date = change_date_format($main_arr['start_on_date']);
		
		if($main_arr['no_end_date'] == '2'){
			
			$end_after_recurrence = $main_arr['end_after_recurrence'];
			if($end_after_recurrence){
				$display = $start_on_date;
				if($display>=$start_date && $display<=$end_date){
					
					$data = $main_arr;
					$data['task_orig_scheduled_date'] = $display;
					$data['task_scheduled_date'] = $display;
					$data['task_due_date'] = $display;
					$data['master_task_id'] = $main_arr['task_id'];
					$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
					$data['task_orig_due_date'] = $display;
					
					array_push($child_arr,$data);
				}
				for($i=1;$i<$end_after_recurrence;$i++){
					
					if($main_arr['Monthly_op1_1']!='0' && $main_arr['Monthly_op1_2']!='0'){
		
						$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9
						
						if($Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
							$start_on_date = date("Y-m-01",strtotime($start_on_date));
							$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
							$display = date('Y-m-t', strtotime($effectiveDate));
						} else {
							$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
							$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
						}
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
					} elseif($main_arr['Monthly_op2_1']!='' && $main_arr['Monthly_op2_2']!='' && $main_arr['Monthly_op2_3']!='0'){
						
						//$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
						if($i == 0){
							$effectiveDate = date('F Y', strtotime("+0 months", strtotime($start_on_date))); // gives month date from given date
						} else {
							$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
						}
						$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
					} elseif($main_arr['Monthly_op3_1']!='0' && $main_arr['Monthly_op3_2']!='0'){
						
						if($main_arr['Monthly_op3_1']<0){
							$start_on_date = date("Y-m-01",strtotime($start_on_date));
							$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
							
							if($main_arr['Monthly_op3_1'] == '-1'){
								
							} else {
								$temp_date = date("Y-m-d",strtotime($main_arr['Monthly_op3_1']." days",strtotime($effectiveDate)));
								if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
									$effectiveDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($effectiveDate)) . " + 1 month"));
								} else {
									$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
								}
								for($a=-1;$a>=$main_arr['Monthly_op3_1'];$a--){
									$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
									if(chk_company_offday_date($effectiveDate,$offdays)){
										$a++;
									}
								}
							}
							$display = chk_company_offday(date("Y-m-d",strtotime($effectiveDate)),$offdays);
						} else {
							$start_on_date = date("Y-m-01",strtotime($start_on_date));
							$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
							
							if($main_arr['Monthly_op3_1'] == '1'){
								$display = chk_company_working_day_next(date("Y-m-d",strtotime($effectiveDate)),$offdays);
							} else {
								$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
								for($a=1;$a<=$main_arr['Monthly_op3_1'];$a++){
									$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
									if(chk_company_offday_date($effectiveDate,$offdays)){
										$a--;
									}
								}
								$display = date("Y-m-d",strtotime($effectiveDate));
							}
						}
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
					}
					if($display > $end_date){
						break;
					}
					$start_on_date = $display;
				}
			}
			
		} elseif($main_arr['no_end_date'] == '3'){
			
			$end_by_date = change_date_format($main_arr['end_by_date']);
			
			$i = 1;
			$display = $start_on_date;
				if($display>=$start_date && $display<=$end_date){
					
					$data = $main_arr;
					$data['task_orig_scheduled_date'] = $display;
					$data['task_scheduled_date'] = $display;
					$data['task_due_date'] = $display;
					$data['master_task_id'] = $main_arr['task_id'];
					$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
					$data['task_orig_due_date'] = $display;
					
					array_push($child_arr,$data);
				}
			while (strtotime($start_on_date) < strtotime($end_by_date)) {
				if($main_arr['Monthly_op1_1']!='0' && $main_arr['Monthly_op1_2']!='0'){
		
					$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9
					
					if($Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
						$start_on_date = date("Y-m-01",strtotime($start_on_date));
						$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
						$display = date('Y-m-t', strtotime($effectiveDate));
					} else {
						$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
						$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
					}
					
					if($display>=$start_date && $display<=$end_date){
						
						$data = $main_arr;
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
					
				} elseif($main_arr['Monthly_op2_1']!='' && $main_arr['Monthly_op2_2']!='' && $main_arr['Monthly_op2_3']!='0'){
					
					//$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
					if($i == 0){
						$effectiveDate = date('F Y', strtotime("+0 months", strtotime($start_on_date))); // gives month date from given date
					} else {
						$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
					}
					$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));
					
					if($display>=$start_date && $display<=$end_date){
						
						$data = $main_arr;
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
					
				} elseif($main_arr['Monthly_op3_1']!='0' && $main_arr['Monthly_op3_2']!='0'){
					
					if($main_arr['Monthly_op3_1']<0){
						$start_on_date = date("Y-m-01",strtotime($start_on_date));
						$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
						
						if($main_arr['Monthly_op3_1'] == '-1'){
							
						} else {
							$temp_date = date("Y-m-d",strtotime($main_arr['Monthly_op3_1']." days",strtotime($effectiveDate)));
							if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
								$effectiveDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($effectiveDate)) . " + 1 month"));
							} else {
								$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
							}
							for($a=-1;$a>=$main_arr['Monthly_op3_1'];$a--){
								$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
								if(chk_company_offday_date($effectiveDate,$offdays)){
									$a++;
								}
								
							}
						}
						$display = chk_company_offday(date("Y-m-d",strtotime($effectiveDate)),$offdays);
					} else {
						$start_on_date = date("Y-m-01",strtotime($start_on_date));
						$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
						
						if($main_arr['Monthly_op3_1'] == '1'){
							$display = chk_company_working_day_next(date("Y-m-d",strtotime($effectiveDate)),$offdays);
						} else {
							$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
							for($a=1;$a<=$main_arr['Monthly_op3_1'];$a++){
								$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
								if(chk_company_offday_date($effectiveDate,$offdays)){
									$a--;
								}
							}
							$display = date("Y-m-d",strtotime($effectiveDate));
						}
					}
					
					if($display>=$start_date && $display<=$end_date){
						
						$data = $main_arr;
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
				}
				$i++;
				if($display > $end_date){
					break;
				}
				$start_on_date = $display;
			}
			
		} else {
			
			$i = 1;
			$display = $start_on_date;
			if($display>=$start_date && $display<=$end_date){
				
				$data = $main_arr;
				$data['task_orig_scheduled_date'] = $display;
				$data['task_scheduled_date'] = $display;
				$data['task_due_date'] = $display;
				$data['master_task_id'] = $main_arr['task_id'];
				$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
				$data['task_orig_due_date'] = $display;
				
				array_push($child_arr,$data);
			}
			while (strtotime($start_on_date) < strtotime($end_date)) {
				if($main_arr['Monthly_op1_1']!='0' && $main_arr['Monthly_op1_2']!='0'){
		
					$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9
					
					if($Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
						$start_on_date = date("Y-m-01",strtotime($start_on_date));
						$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
						$display = date('Y-m-t', strtotime($effectiveDate));
					} else {
						$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
						$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
					}
					
					if($display>=$start_date && $display<=$end_date){
						
						$data = $main_arr;
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
					
				} elseif($main_arr['Monthly_op2_1']!='' && $main_arr['Monthly_op2_2']!='' && $main_arr['Monthly_op2_3']!='0'){
					
					//$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
					if($i == 0){
						$effectiveDate = date('F Y', strtotime("+0 months", strtotime($start_on_date))); // gives month date from given date
					} else {
						$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
					}
					$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));
					
					if($display>=$start_date && $display<=$end_date){
						
						$data = $main_arr;
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
					
				} elseif($main_arr['Monthly_op3_1']!='0' && $main_arr['Monthly_op3_2']!='0'){
					
					if($main_arr['Monthly_op3_1']<0){
						$start_on_date = date("Y-m-01",strtotime($start_on_date));
						$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
						
						if($main_arr['Monthly_op3_1'] == '-1'){
							
						} else {
							$temp_date = date("Y-m-d",strtotime($main_arr['Monthly_op3_1']." days",strtotime($effectiveDate)));
							if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
								$effectiveDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($effectiveDate)) . " + 1 month"));
							} else {
								$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
							}
							for($a=-1;$a>=$main_arr['Monthly_op3_1'];$a--){
								$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
								if(chk_company_offday_date($effectiveDate,$offdays)){
									$a++;
								}
								
							}
						}
						$display = chk_company_offday(date("Y-m-d",strtotime($effectiveDate)),$offdays);
					} else {
						$start_on_date = date("Y-m-01",strtotime($start_on_date));
						$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
						
						if($main_arr['Monthly_op3_1'] == '1'){
							$display = chk_company_working_day_next(date("Y-m-d",strtotime($effectiveDate)),$offdays);
						} else {
							$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
							for($a=1;$a<=$main_arr['Monthly_op3_1'];$a++){
								$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
								if(chk_company_offday_date($effectiveDate,$offdays)){
									$a--;
								}
							}
							$display = date("Y-m-d",strtotime($effectiveDate));
						}
					}
					
					
					if($display>=$start_date && $display<=$end_date){
						$data = $main_arr;
						$data['task_orig_scheduled_date'] = $display;
						$data['task_scheduled_date'] = $display;
						$data['task_due_date'] = $display;
						$data['master_task_id'] = $main_arr['task_id'];
						$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
						$data['task_orig_due_date'] = $display;
						
						array_push($child_arr,$data);
					}
				}
				$i++;
				if($display > $end_date){
					break;
				}
				$start_on_date = $display;
			}
			
		}

		return $child_arr;
	}
	
	function yearly_task_monthly_logic($main_arr,$start_date,$end_date,$offdays){
		
		$CI =& get_instance();
		
		$child_arr = array();
		$data = array();
		//pr($main_arr);
		$start_on_date = change_date_format($main_arr['start_on_date']);
		
		if($main_arr['no_end_date'] == '2'){
			
			$end_after_recurrence = $main_arr['end_after_recurrence'];
			if($end_after_recurrence){
				$display = $start_on_date;
				if($display>=$start_date && $display<=$end_date){
					
					$data = $main_arr;
					$data['task_orig_scheduled_date'] = $display;
					$data['task_scheduled_date'] = $display;
					$data['task_due_date'] = $display;
					$data['master_task_id'] = $main_arr['task_id'];
					$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
					$data['task_orig_due_date'] = $display;
					
					array_push($child_arr,$data);
				}
				for($i=1;$i<$end_after_recurrence;$i++){
					if($main_arr['Yearly_op1'] != '0'){
				
						$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + ".$main_arr['Yearly_op1']." year"));
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
						
					} elseif($main_arr['Yearly_op2_1']!='0' && $main_arr['Yearly_op2_2']!='0'){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
						
						$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
						
					} elseif($main_arr['Yearly_op3_1']!='0' && $main_arr['Yearly_op3_2']!='' && $main_arr['Yearly_op3_3']!='0'){
						//echo $start_on_date;die;
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						$temp_date = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
						
						$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
						
						$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
					} elseif($main_arr['Yearly_op4_1'] != '0' && $main_arr['Yearly_op4_2']!= ''){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						if($main_arr['Yearly_op4_1']<0){
							if($year >= date('Y')){
								if(date('m', strtotime($main_arr['Yearly_op4_2'])) >= $month){
									if($i==0){
										$year = $year;
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
									
								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}
							$monthyear = date('Y-m-t',strtotime($main_arr['Yearly_op4_2']." ".$year));
							
							if($main_arr['Yearly_op4_1'] == '-1'){
							
							} else {
								$temp_date = date("Y-m-d",strtotime($main_arr['Yearly_op4_1']." days",strtotime($monthyear)));
								if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
									$monthyear = date("Y-m-d",strtotime(date("Y-m-d", strtotime($monthyear)) . " + 1 year"));
								}
								for($a=-1;$a>$main_arr['Yearly_op4_1'];$a--){
									if(chk_company_offday_date($monthyear,$offdays)){
										$a++;
									}
									$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
								}
							}
							$display = chk_company_offday(date("Y-m-d",strtotime($monthyear)),$offdays);
						} else {
							if($year >= date('Y')){
								if(date('m', strtotime($main_arr['Yearly_op4_2'])) > $month){
									if($i==0){
										$year = $year;
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}
							$monthyear = date('Y-m-01',strtotime($main_arr['Yearly_op4_2']." ".$year));
							
							if($main_arr['Yearly_op4_1'] == '1'){
								$display = chk_company_working_day_next(date("Y-m-d",strtotime($monthyear)),$offdays);
							} else {
								$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
								for($a=1;$a<=$main_arr['Yearly_op4_1'];$a++){
									$monthyear = date("Y-m-d",strtotime("+1 days",strtotime($monthyear)));
									if(chk_company_offday_date($monthyear,$offdays)){
										$a--;
									}
								}
								$display = date("Y-m-d",strtotime($monthyear));
							}
						}
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
					}
					if($display > $end_date){
						break;
					}
					$start_on_date = date("Y-m-d", strtotime($display));
					
				}
			}
			
		} elseif($main_arr['no_end_date'] == '3'){
			
			$end_by_date = change_date_format($main_arr['end_by_date']);
			
			$i = 1;
			$display = $start_on_date;
				if($display>=$start_date && $display<=$end_date){
					
					$data = $main_arr;
					$data['task_orig_scheduled_date'] = $display;
					$data['task_scheduled_date'] = $display;
					$data['task_due_date'] = $display;
					$data['master_task_id'] = $main_arr['task_id'];
					$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
					$data['task_orig_due_date'] = $display;
					
					array_push($child_arr,$data);
				}
			while (strtotime($start_on_date) < strtotime($end_by_date)) {
				if($main_arr['Yearly_op1'] != '0'){
				
						$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + ".$main_arr['Yearly_op1']." year"));
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
					} elseif($main_arr['Yearly_op2_1']!='0' && $main_arr['Yearly_op2_2']!='0'){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
						
						$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
						
					} elseif($main_arr['Yearly_op3_1']!='0' && $main_arr['Yearly_op3_2']!='' && $main_arr['Yearly_op3_3']!='0'){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
						
						$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
					} elseif($main_arr['Yearly_op4_1'] != '0' && $main_arr['Yearly_op4_2']!= ''){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						if($main_arr['Yearly_op4_1']<0){
							if($year >= date('Y')){
								if(date('m', strtotime($main_arr['Yearly_op4_2'])) >= $month){
									if($i==0){
										$year = $year;
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
									
								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}
							$monthyear = date('Y-m-t',strtotime($main_arr['Yearly_op4_2']." ".$year));
							
							if($main_arr['Yearly_op4_1'] == '-1'){
							
							} else {
								$temp_date = date("Y-m-d",strtotime($main_arr['Yearly_op4_1']." days",strtotime($monthyear)));
								if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
									$monthyear = date("Y-m-d",strtotime(date("Y-m-d", strtotime($monthyear)) . " + 1 year"));
								}
								for($a=-1;$a>$main_arr['Yearly_op4_1'];$a--){
									if(chk_company_offday_date($monthyear,$offdays)){
										$a++;
									}
									$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
								}
							}
							$display = chk_company_offday(date("Y-m-d",strtotime($monthyear)),$offdays);
						} else {
							if($year >= date('Y')){
								if(date('m', strtotime($main_arr['Yearly_op4_2'])) > $month){
									if($i==0){
										$year = $year;
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}
							$monthyear = date('Y-m-01',strtotime($main_arr['Yearly_op4_2']." ".$year));
							
							if($main_arr['Yearly_op4_1'] == '1'){
								$display = chk_company_working_day_next(date("Y-m-d",strtotime($monthyear)),$offdays);
							} else {
								$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
								for($a=1;$a<=$main_arr['Yearly_op4_1'];$a++){
									$monthyear = date("Y-m-d",strtotime("+1 days",strtotime($monthyear)));
									if(chk_company_offday_date($monthyear,$offdays)){
										$a--;
									}
								}
								$display = date("Y-m-d",strtotime($monthyear));
							}
						}
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
					}
					if($display > $end_date){
						break;
					}
					$start_on_date = $display;
					$i++;
			}
			
		} else {
			
			$i = 1;
			$display = $start_on_date;
				if($display>=$start_date && $display<=$end_date){
					$data = $main_arr;
					$data['task_orig_scheduled_date'] = $display;
					$data['task_scheduled_date'] = $display;
					$data['task_due_date'] = $display;
					$data['master_task_id'] = $main_arr['task_id'];
					$data['task_id'] = 'child_'.$main_arr['task_id'].'_0';
					$data['task_orig_due_date'] = $display;
					
					array_push($child_arr,$data);
				}
			while (strtotime($start_on_date) < strtotime($end_date)) {
				if($main_arr['Yearly_op1'] != '0'){
				
						$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + ".$main_arr['Yearly_op1']." year"));
						if($display>=$start_date && $display<=$end_date){
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
					} elseif($main_arr['Yearly_op2_1']!='0' && $main_arr['Yearly_op2_2']!='0'){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
						
						
						$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
						
					} elseif($main_arr['Yearly_op3_1']!='0' && $main_arr['Yearly_op3_2']!='' && $main_arr['Yearly_op3_3']!='0'){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
						
						$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
						
					} elseif($main_arr['Yearly_op4_1'] != '0' && $main_arr['Yearly_op4_2']!= ''){
						
						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));
						
						if($main_arr['Yearly_op4_1']<0){
							if($year >= date('Y')){
								if(date('m', strtotime($main_arr['Yearly_op4_2'])) >= $month){
									if($i==0){
										$year = $year;
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
									
								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}
							$monthyear = date('Y-m-t',strtotime($main_arr['Yearly_op4_2']." ".$year));
							
							if($main_arr['Yearly_op4_1'] == '-1'){
							
							} else {
								$temp_date = date("Y-m-d",strtotime($main_arr['Yearly_op4_1']." days",strtotime($monthyear)));
								if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
									$monthyear = date("Y-m-d",strtotime(date("Y-m-d", strtotime($monthyear)) . " + 1 year"));
								}
								for($a=-1;$a>$main_arr['Yearly_op4_1'];$a--){
									if(chk_company_offday_date($monthyear,$offdays)){
										$a++;
									}
									$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
								}
							}
							$display = chk_company_offday(date("Y-m-d",strtotime($monthyear)),$offdays);
						} else {
							if($year >= date('Y')){
								if(date('m', strtotime($main_arr['Yearly_op4_2'])) > $month){
									if($i==0){
										$year = $year;
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}
							$monthyear = date('Y-m-01',strtotime($main_arr['Yearly_op4_2']." ".$year));
							
							if($main_arr['Yearly_op4_1'] == '1'){
								$display = chk_company_working_day_next(date("Y-m-d",strtotime($monthyear)),$offdays);
							} else {
								$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
								for($a=1;$a<=$main_arr['Yearly_op4_1'];$a++){
									$monthyear = date("Y-m-d",strtotime("+1 days",strtotime($monthyear)));
									if(chk_company_offday_date($monthyear,$offdays)){
										$a--;
									}
								}
								$display = date("Y-m-d",strtotime($monthyear));
							}
						}
						
						if($display>=$start_date && $display<=$end_date){
							
							$data = $main_arr;
							$data['task_orig_scheduled_date'] = $display;
							$data['task_scheduled_date'] = $display;
							$data['task_due_date'] = $display;
							$data['master_task_id'] = $main_arr['task_id'];
							$data['task_id'] = 'child_'.$main_arr['task_id'].'_'.$i;
							$data['task_orig_due_date'] = $display;
							
							array_push($child_arr,$data);
						}
					}
					if($display > $end_date){
						break;
					}
					$start_on_date = $display;
					$i++;
			}
			
		}
		
		return $child_arr;
	}
	
	function month_task_recu($row,$month_start_date,$month_end_date,$j){
		$task = array();
		$virtual_array = '';
		
		if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){
			$virtual_array = monthly_recurrence_logic($row,$month_start_date,$month_end_date);
		}
		
		while(strtotime($month_start_date) <= strtotime($month_end_date)){
			$day = date('j',strtotime($month_start_date));
			$task[$day] = array();
			
			if($virtual_array){
				foreach($virtual_array as $vrt_arry){
					if($vrt_arry['task_scheduled_date'] == $month_start_date){
						$chk_recu = chk_virtual_recurrence_exists($vrt_arry['master_task_id'],date('Y-m-d',strtotime($vrt_arry['task_orig_scheduled_date'])));
						if($chk_recu){
							
						} else {
							array_push($j[$day],$vrt_arry);
						}
					}
				}
			}  else {
				if($row['task_scheduled_date'] == $month_start_date){
					array_push($j[$day],$row);
				}
			}
			$month_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($month_start_date)));
		}
		return $j;
	}
	
	function week_task_recu($row,$month_start_date,$month_end_date,$j,$calender_team_user_id,$task_status_completed_id,$offdays,$module=''){
		
            $task = array();
		$virtual_array = '';
		//pr($row);
		if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){ 
			//$org_month_start_date = toDate($month_start_date);
			//$org_month_end_date = toDate($month_end_date);
			$virtual_array = monthly_recurrence_logic($row,$month_start_date,$month_end_date,$offdays);
		}
		//pr($virtual_array);
		
		while(strtotime($month_start_date) <= strtotime($month_end_date)){
			
			//$orig_month_start_date = toDate($month_start_date);
			
			$task[$month_start_date] = array();
			
			if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0' && $module!='completed'){ 
				if($virtual_array){
					foreach($virtual_array as $vrt_arry){
						if(strtotime($vrt_arry['task_scheduled_date']) == strtotime($month_start_date)){
							$chk_recu = chk_virtual_recurrence_exists($vrt_arry['master_task_id'],$vrt_arry['task_orig_scheduled_date'],$task_status_completed_id);
                                                                    
							if($chk_recu){
								//array_push($j[$month_start_date],$chk_recu);
							} else { 
								if($vrt_arry['task_allocated_user_id'] == $calender_team_user_id){
									array_push($j[$month_start_date],$vrt_arry);
                                                                        $j['color_menu']='true';
                                                                        $j['allocation_flag']='false';
								}else{ 
                                                                    if(is_array($calender_team_user_id) && $module == 'other_user'){
                                                                        foreach($calender_team_user_id as $id){
                                                                            if($id == $vrt_arry['task_allocated_user_id']){
                                                                                array_push($j[$month_start_date],$vrt_arry);
                                                                                $j['color_menu']='true';
                                                                                $j['allocation_flag']='false';
                                                                            }
                                                                        }
                                                                    }
                                                                }
							}
						}
					}
				}
			} else { 
                                if(is_array($calender_team_user_id)){
                                    for($i=0;$i<count($calender_team_user_id);$i++){
                                    if($row['task_scheduled_date'] == $month_start_date && $row['task_allocated_user_id']==$calender_team_user_id[$i]){  
					array_push($j[$month_start_date],$row);
                                        $j['color_menu'] = 'false';
                                        $j['allocation_flag']='true';
                                    }
                                    }  //print_r($calender_team_user_id); die();
                                }
				if($row['task_scheduled_date'] == $month_start_date && $row['task_allocated_user_id']==$calender_team_user_id){ 
					array_push($j[$month_start_date],$row);
                                       $j['color_menu']='true';
                                       $j['allocation_flag']='false';
				}
			}
			
			$month_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($month_start_date)));
		}
		//echo "<pre>"; pr($j);
		return $j;
	}
	
	
	
	function minutesToTime($totalMinutes) {
		$hours = intval($totalMinutes/60);
		$minutes = $totalMinutes - ($hours * 60);
		if($hours == '0' && $minutes == '0'){
			return '0m';
		} elseif($hours != '0' && $minutes == '0'){
			return $hours.'h';
		} elseif($hours == '0' && $minutes != '0'){
			return $minutes.'m';
		} else {
			return $hours.'h '.$minutes.'m';
		}
	}

	function minutesToHourMinutes($totalMinutes) {
		$hours = intval($totalMinutes/60);
		$minutes = $totalMinutes - ($hours * 60);
		//echo $totalMinutes."====>".$hours."====>".$minutes;
		if($hours == '0' && $minutes == '0'){
			return '00:00';
		} elseif($hours != '0' && $minutes == '0'){
			if($hours<10){
				$hours = "0".$hours;
			}
			return $hours.':00';
		} elseif($hours == '0' && $minutes != '0'){
			if($minutes<10){
				$minutes = "0".$minutes;
			}
			return '00:'.$minutes;
		} else {
			if($hours<0 || $minutes<0){
				
			} else {
				if($hours<10){
					$hours = "0".$hours;
				}
				if($minutes<10){
					$minutes = "0".$minutes;
				}
			}
			return $hours.':'.$minutes;
		}
	}
	
	/**
         * It get user capacity via week day and user_id
         * @param string $week_day
         * @param int $user_id
         * @returns int
         */
	
	function get_user_capacity($week_day,$user_id){
		
		$CI =& get_instance();
		$user_id = $CI->session->userdata('user_id');
		$query = $CI->db->get_where('default_calendar_setting',array('user_id'=>$user_id));
		if($query->num_rows()>0){
			$res = $query->row_array();
			$hour = 0;
			if($week_day == 'Mon'){
				if($res['MON_closed']){
					$hour = $res['MON_hours'];
				} else {
					$hour = 0;
				}
			} elseif($week_day == 'Tue'){
				if($res['TUE_closed']){
					$hour = $res['TUE_hours'];
				} else {
					$hour = 0;
				}
			} elseif($week_day == 'Wed'){
				if($res['WED_closed']){
					$hour = $res['WED_hours'];
				} else {
					$hour = 0;
				}
			} elseif($week_day == 'Thu'){
				if($res['THU_closed']){
					$hour = $res['THU_hours'];
				} else {
					$hour = 0;
				}
			} elseif($week_day == 'Fri'){
				if($res['FRI_closed']){
					$hour = $res['FRI_hours'];
				} else {
					$hour = 0;
				}
			} elseif($week_day == 'Sat'){
				if($res['SAT_closed']){
					$hour = $res['FRI_hours'];
				} else {
					$hour = 0;
				}
			} elseif($week_day == 'Sun'){
				if($res['SUN_closed']){
					$hour = $res['SUN_hours'];
				} else {
					$hour = 0;
				}
			} else {
				$hour = 0;
			}
			return $hour;
		} else {
			return 0;
		}
	}

	function getUserCapacity($user_id){
			
			$CI =& get_instance();
			$query = $CI->db->get_where('default_calendar_setting',array('user_id'=>$user_id));
			if($query->num_rows()>0){
				return  $query->row_array();
			} else {
				return 0;
			}
		}

	
	function get_comment_users($task_id,$owner_id,$allocated_user_id){
		
		
		$CI =& get_instance();
		$CI->db->select('comment_addeby');
		$CI->db->from('task_and_project_comments');
		$CI->db->where('task_id',$task_id);
		$CI->db->where('(comment_addeby != '.$owner_id.' OR comment_addeby != '.$allocated_user_id.')');
		$CI->db->group_by('comment_addeby');
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	function get_calender_weekly_tasks($start_date,$end_date,$calender_project_id,$left_task_status_id='',$calender_team_user_id='',$calender_date='',$cal_user_color_id='0',$calender_sorting='1',$task_status_completed_id,$module=''){
		
		 $CI =& get_instance();
		$users_list=array();
                $project_list=array();
                if($calender_project_id != 'all'){
                   
                     $users_list = get_user_under_project($calender_project_id);
                     
                     if(!empty($users_list)){
                        foreach($users_list as $data_id){
                           $ids[] = $data_id->user_id;
                        }
                     }
                }
                if($calender_team_user_id == "users"){
                    $users = getUserListFromTask(get_authenticateUserID());
                    foreach($users as $id){
                         $users_ids[] = $id->task_allocated_user_id;
                     }
                      //pr($users_ids); die();
                } 
                        
		if($left_task_status_id){
			if(is_string($left_task_status_id)){
				$left_task_status_id = explode(',',$left_task_status_id);
			}
		}
		
                /**
                 * query for getting non-recurring task
                 */
                
		$CI->db->select('t.*,u.profile_image,ts.task_status_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,uc.color_code, uc.outside_color_code,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies,(SELECT COUNT(1) FROM task_and_project_files tpf  WHERE tpf.task_id = t.task_id and tpf.is_deleted=0) AS files,(SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch',FALSE);
		
		$CI->db->from('tasks t');
		$CI->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		
                
		
                if($calender_team_user_id == '#' || $calender_team_user_id == '0'){ 
                        $CI->db->where_in('t.task_allocated_user_id',$ids);
                        $CI->db->where('t.is_personal','0');
                }else if($calender_team_user_id == 'users'){
                        $CI->db->where_in('t.task_allocated_user_id',$users_ids);
                        $CI->db->where('t.is_personal','0');
                        $CI->db->where('t.task_owner_id',  get_authenticateUserID());
                }else{ 
                    if($calender_team_user_id){
                        if($module == 'capacity'){
                                $CI->db->where('t.is_personal','0');
                        }else if($calender_team_user_id != get_authenticateUserID()){
				$CI->db->where('t.is_personal','0');
			}else if($module == 'completed'){
                                $CI->db->where('t.is_personal','0');
                                $CI->db->where('t.task_status_id',$task_status_completed_id);
                        }
			$CI->db->where('t.task_allocated_user_id',$calender_team_user_id);
                    } else {
                            $CI->db->where('t.task_allocated_user_id',$CI->session->userdata('user_id'));
                    }
                }
		if($left_task_status_id){
			if(in_array('all',$left_task_status_id)){
				
			} else {
				$CI->db->where_in('t.task_status_id',$left_task_status_id);
			}
		} else {
			$CI->db->where('t.task_status_id','0');
		}
		if($calender_project_id !='all'){
                     
                    $CI->db->where_in('t.task_project_id',$calender_project_id);
                }
		if($cal_user_color_id!='0'){
			$CI->db->where('uts.color_id',$cal_user_color_id);
		}
                
                $CI->db->where('t.frequency_type','one_off');
//		$CI->db->where('t.task_due_date >=',$start_date);
//                $CI->db->where('t.task_due_date <=',$end_date);
                $CI->db->where('t.task_scheduled_date >=',$start_date);
                $CI->db->where('t.task_scheduled_date <=',$end_date);
                
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		//$CI->db->where('t.master_task_id','0');
		
		
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		
		if($calender_sorting == '2'){
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '3'){
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '4'){
			$CI->db->order_by('t.task_time_estimate','desc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
		} else {
			$CI->db->order_by('uts.calender_order','asc');
			$CI->db->order_by('t.task_completion_date','asc');
		}
		$CI->db->group_by('t.task_id');
		
                $query = $CI->db->get();
                $result1 = $query->result_array();
//		echo "<pre>"; echo $CI->db->last_query(); 
//                die();
                /**
                 * query for getting recurring task
                 */
                
                
                $CI->db->select('t.*,u.profile_image,ts.task_status_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,uc.color_code, uc.outside_color_code,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, (SELECT COUNT(1) FROM task_steps tsp WHERE tsp.task_id = t.task_id and tsp.is_deleted = 0) AS ts, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies,(SELECT COUNT(1) FROM task_and_project_files tpf  WHERE tpf.task_id = t.task_id and tpf.is_deleted=0) AS files,(SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.get_authenticateUserID().') AS watch',FALSE);
		
		$CI->db->from('tasks t');
		$CI->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		if($calender_team_user_id == '#' || $calender_team_user_id == '0'){ 
                        $calender_team_user_id = $ids;
			$CI->db->where_in('t.task_allocated_user_id',$ids);
                        $CI->db->where('t.is_personal','0');
                }else if($calender_team_user_id == 'users'){ 
                        $calender_team_user_id = $users_ids;
                        $CI->db->where_in('t.task_allocated_user_id',$users_ids);
                        $CI->db->where('t.is_personal','0');
                        $CI->db->where('t.task_owner_id',  get_authenticateUserID());
                }else{ 
                    if($calender_team_user_id){
                        if($module == 'capacity'){
                                $CI->db->where('t.is_personal','0');
                        }elseif($calender_team_user_id != get_authenticateUserID()){
				$CI->db->where('t.is_personal','0');
			}else if($module == 'completed'){
                                $CI->db->where('t.is_personal','0');
                                $CI->db->where('t.task_status_id',$task_status_completed_id);
                        }
			$CI->db->where('t.task_allocated_user_id',$calender_team_user_id);
                    } else {
                            $CI->db->where('t.task_allocated_user_id',$CI->session->userdata('user_id'));
                    }
                }
		if($left_task_status_id){
			if(in_array('all',$left_task_status_id)){
				
			} else {
				$CI->db->where_in('t.task_status_id',$left_task_status_id);
			}
		} else {
			$CI->db->where('t.task_status_id','0');
		}
		if($calender_project_id !='all'){
                     
                    $CI->db->where_in('t.task_project_id',$calender_project_id);
                }
		if($cal_user_color_id!='0'){
			$CI->db->where('uts.color_id',$cal_user_color_id);
		}
                
                $CI->db->where('t.frequency_type','recurrence');
               // $CI->db->where('t.task_due_date <=',$start_date);
                $CI->db->where('t.task_due_date <=',$end_date);
               // $CI->db->where('t.task_scheduled_date <=',$start_date);
                $CI->db->where('t.task_scheduled_date <=',$end_date);
                
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		//$CI->db->where('t.master_task_id','0');
		
		
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where("(CASE WHEN t.no_end_date =1  THEN '1'
                    WHEN t.no_end_date =2 AND t.end_after_recurrence = '1' AND t.start_on_date >= '$start_date' AND t.start_on_date <= '$end_date' THEN '1'
                    WHEN t.no_end_date =2 AND t.end_by_date >= '$start_date' THEN '1'
                    WHEN t.no_end_date =3 AND t.end_by_date >= '$start_date' THEN '1'
                    ELSE '0' END)='1'");
		if($calender_sorting == '2'){
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '3'){
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '4'){
			$CI->db->order_by('t.task_time_estimate','desc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
		} else {
			$CI->db->order_by('uts.calender_order','asc');
			$CI->db->order_by('t.task_completion_date','asc');
		}
		$CI->db->group_by('t.task_id');
		
                $query1 = $CI->db->get();
                $result2 = $query1->result_array();
                $result=  array_merge($result1, $result2);
                
                
//               echo "<pre>"; echo $CI->db->last_query(); die();
////		 print_r($result2); die();
		$res = $result;
                
//		if($query->num_rows()>0){ 
//			$res = $query->result_array();
			//pr($res);
			
			$week_start_date = date('Y-m-d',strtotime($start_date));
			$week_end_date = date('Y-m-d',strtotime($end_date));
			
			$task = array();
			while(strtotime($week_start_date)<=strtotime($week_end_date)){
				$j[$week_start_date] = array();
				$week_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($week_start_date)));
			}
			
			//echo "<pre>";
			//print_r($j); die;
			$task_array = array();
			$main_rec = array();
			$week_start_date = date('Y-m-d',strtotime($start_date));
			$week_end_date = date('Y-m-d',strtotime($end_date));
			$offdays = get_company_offdays();
			if($res){
				
				$i = 1;
				foreach($res as $row){
					//pr($row);
					//echo $i;
					if($i == 1)
					{
						$task_array = week_task_recu($row,$week_start_date,$week_end_date,$j,$calender_team_user_id,$task_status_completed_id,$offdays,$module);
						
					}
					else
					{
						$task_array = week_task_recu($row,$week_start_date,$week_end_date,$task_array,$calender_team_user_id,$task_status_completed_id,$offdays,$module);
						//pr($task_array);
					}
					
					$i++;
					
				}
				
			
			}
			//echo "<pre>"; print_r($task_array); 
			return $task_array;
//		} else {
//			return 0;
//		}
	}

	
	/***** project users ****/
	

	function get_user_projects($user_id){
		$CI =& get_instance();
		$query = $CI->db->select('p.project_id,p.project_title')
						->from('project p')
						->join('project_users pu','pu.project_id = p.project_id')
						->where('pu.user_id',$user_id)
						->where('pu.status','Active')
						->where('pu.is_deleted','0')
						->where('p.project_status','Open')
						->where('p.is_deleted','0')
						->order_by('p.project_title','asc')
						->get();
		//echo $CI->db->last_query();die;
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_project_name($project_id){
		$CI =& get_instance();
		$query = $CI->db->select('project_title')->from('project')->where('project_id',$project_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->project_title;
		} else {
			return '';
		}
	}
	
	function get_user_projects_sections($project_id=''){
		$CI =& get_instance();
		$CI->db->select('section_id,section_name');
		$CI->db->from('project_section');
		if($project_id){
			$CI->db->where('project_id',$project_id);
		}
		$CI->db->where('main_section','0');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
		
	}
	
	function count_today_interruptions(){
		
		
		$CI =& get_instance();
       $today_date = toDateUser(date('Y-m-d'));
		
		$offset = get_TimezoneOffset($CI->session->userdata("User_timezone"));
		
		
		$query = $CI->db->select('timer_logs_id')
						->from('task_timer_logs')
						->where('(interruption != "Task Completed" and interruption != "")')
						->where('user_id',get_authenticateUserID())
						->where('is_manual','0')
						->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'"))',$today_date)
						->get();
				//echo $CI->db->last_query(); die;		
		return $query->num_rows();
	}
	
	function get_user_work_log($date){
		$CI =& get_instance();
		
		$offset = get_TimezoneOffset($CI->session->userdata("User_timezone"));
		
		$query = $CI->db->select('*')
						->from('task_timer_logs')
						->where('user_id',get_authenticateUserID())
						->where('is_manual','0')
						->where('interruption !=','')
						->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'"))',$date)
						->order_by('timer_logs_id','desc')
						->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	function user_total_spent_time_per_day($date){
		$CI =& get_instance();
		
		$date = toDateUser($date);
		
		$offset = get_TimezoneOffset($CI->session->userdata("User_timezone"));
		
		
		$query = $CI->db->select("SEC_TO_TIME( SUM( TIME_TO_SEC( `spent_time` ) ) ) AS timeSum")
						->from('task_timer_logs')
						->where('user_id',get_authenticateUserID())
						->where('is_manual','0')
						->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'"))',$date)
						->get();
		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			$res = $query->row();
			$sum = $res->timeSum;
			if($sum){
				$time = explode(':',$sum);
				$hr = $time[0]*60;
				$min = $time[1];
				$sec = $time[2]/60;
				$total_min = $hr + $min + $sec;
				return $total_min;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	function get_user_interruptions($type){
		
		$CI =& get_instance();
		
		$user_today_date = toDateUser(date("Y-m-d"));
		
		$last_date = date("Y-m-d",strtotime("-7 days",strtotime($user_today_date)));
		
		//echo $user_today_date."===".$last_date;die;
		$offset = get_TimezoneOffset($CI->session->userdata("User_timezone"));
		
		$query = $CI->db->select('timer_logs_id')
						->from('task_timer_logs')
						->where('user_id',get_authenticateUserID())
						->where('is_manual','0')
						->where('interruption ',$type)
						->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'")) <=',$user_today_date)
						->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'")) >=',$last_date)
						->get();
	//	echo $CI->db->last_query();die;
		return $query->num_rows();
	}
	
	function user_total_interruptions($type,$date){
		$CI =& get_instance();
		
		$date = toDateUser($date);
		
		$offset = get_TimezoneOffset($CI->session->userdata("User_timezone"));
		
		
		$query = $CI->db->select('timer_logs_id')
						->from('task_timer_logs')
						->where('user_id',get_authenticateUserID())
						->where('is_manual','0')
						->where('interruption ',$type)
						->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'"))',$date)
						->get();
		//echo $CI->db->last_query();die;
		return $query->num_rows();
	}
	
	function interruption_by_task($task_id,$type=''){
		$CI =& get_instance();
		
		$CI->db->select('*');
		$CI->db->from('task_timer_logs');
		$CI->db->where('task_id',$task_id);
		$CI->db->where('is_manual','0');
		$CI->db->where('interruption !=','');
		if($type == "this_week"){
			$offset = get_TimezoneOffset($CI->session->userdata("User_timezone"));
			$week_start_date = date("Y-m-d",strtotime("monday this week"));
			$week_end_date = date("Y-m-d",strtotime("sunday this week"));
			$CI->db->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'")) >=',$week_start_date);
			$CI->db->where('DATE(CONVERT_TZ(date_added,"+00:00","'.$offset.'")) <=',$week_end_date);
		}
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}
	
	function chk_swim_exist($task_id,$user_id){
		$CI =& get_instance();
		$query = $CI->db->select('id')->from('user_task_swimlanes')->where('task_id',$task_id)->where('user_id',$user_id)->get();
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	}
	
	
	function month_total_working_day($month,$year, $date='',$off_days){
		$CI =& get_instance();
		$start_date = date("Y-m-d",strtotime($year."-".$month."-01"));
		$end_date = date("Y-m-t",strtotime($start_date));
		
		$i=0;
		//company off days array
		$off_days_arr = array();
		if($off_days!=''){
			$off_days_arr = explode(',', $off_days);
		}
		while(strtotime($start_date)<=strtotime($end_date)){
			
			if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $start_date)))),$off_days_arr) ){
			} else {
				$i++;
			}
			if($date){
				if(strtotime($start_date) == strtotime($date)){
					return $i;
				} 
			}
			$start_date = date("Y-m-d",strtotime("+1 days",strtotime($start_date)));
		}
		return $i;
	}
	
	function is_company_working_day($month,$year, $date='',$off_days){
		$CI =& get_instance();
		$start_date = date("Y-m-d",strtotime($year."-".$month."-01"));
		$end_date = date("Y-m-t",strtotime($start_date));
		
		$i=0;
		//company off days array
		$off_days_arr = array();
		
		if($off_days!=''){
			$off_days_arr = explode(',', $off_days);
		}
		while(strtotime($start_date)<=strtotime($end_date)){
			
			if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $start_date)))),$off_days_arr) ){
			} else {
				if($date){
					if(strtotime($start_date) == strtotime($date)){
						return 1;
					} 
				}
			}
			
			$start_date = date("Y-m-d",strtotime("+1 days",strtotime($start_date)));
		}
		return 0;
	}
	
	
	function chk_master_task_id_deleted($master_task_id){
		$CI =& get_instance();
		$query = $CI->db->select("is_deleted")->from("tasks")->where("task_id",$master_task_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->is_deleted;
		} else {
			return 1;
		}
	}
	
	function get_task_data($task_id){
		$CI =& get_instance();
		
		$query = $CI->db->select("task_owner_id,task_status_id,task_scheduled_date,task_allocated_user_id,task_title,task_due_date,task_description,is_deleted")
						->from("tasks")
						->where("task_id",$task_id)
						->where('task_company_id',$CI->session->userdata('company_id'))
						->where("task_owner_id != ","0")
						->where("task_allocated_user_id != ","0")
						->where("is_deleted","0")
						->get();
		if($query->num_rows()>0){
			return $query->row_array();
		}
	}
	
	 function calculate_completed_time($a = "",$b='',$format = '%02dh%02dm')
	{
			   //$a = "10h59m";
	           //$b = "10h59m";
	    
	    // echo $a."/".$b;
		//die;
		if(strpos($a, "h") == false)
		{
			$a = "0h".$a;
		}
		
		// if(strpos($b, "h") == false)
		// {
			// $b = "0h".$b;
		// }
		
		
		if(strpos($a, "m") == false)
		{
			$a = $a."0m";
		}
		
		// if(strpos($b, "m") == false)
		// {
			// $b = $b."0m";
		// }
		
		$ar = explode("h",$a);
		$br = explode("h",$b);
		 
		
		//echo "<pre>";
		//print_r($ar);
		
		//print_r($br);
		
		
		
		//$h_c = substr($ar[1],0,-1)+substr($br[1],0,-1);
		 $h_c = substr($ar[1],0,-1)+$b;
		
		//echo $ar[1]+$br["1"]; die;
		// $m_c = ($ar[0]+$br["0"])*60;
		  $m_c = ($ar[0])*60;
	
		$final_m = $m_c + $h_c; 
		
		//echo "<pre>";
		
		if ($final_m < 1) {
	        return "00h00m";
			
	    }
	    
	    $hours = floor($final_m / 60);
	    $minutes = ($final_m % 60);
	    return sprintf($format, $hours, $minutes);
	
	} 
	
	function chk_virtual_recurrence_exists_teampending($master_task_id,$orig_due_date){
		
		$CI =& get_instance();
		$CI->db->select('t.*,c1.customer_name,ts.task_status_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,p.project_title, u1.first_name AS first_owner_name, u1.last_name  AS last_owner_name, u2.first_name AS allocated_user_first_name,u1.profile_image as owner_profile_image , u2.last_name AS allocated_user_last_name, u2.profile_image as allocated_user_profile_image');
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u1','t.task_owner_id = u1.user_id','left');
		$CI->db->join('users u2','t.task_allocated_user_id = u2.user_id','left');
                $CI->db->join('customers c1','t.customer_id = c1.customer_id AND t.task_company_id = c1.customer_company_id','left');
                $CI->db->join('task_status ts','ts.task_status_id = t.task_status_id','left');
		$CI->db->where('t.master_task_id',$master_task_id);
		$CI->db->where('t.task_orig_scheduled_date',$orig_due_date);
		$CI->db->where('t.task_owner_id != ',"0");
		$CI->db->where('t.task_allocated_user_id != ',"0");
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->where('t.is_personal',0);
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	
	
	function chk_virtual_recurrence_exists_teamoverdue($master_task_id,$orig_due_date){
		
		$CI =& get_instance();
		$CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,p.project_title, CONCAT( u1.first_name, " ", u1.last_name ) AS owner_name, u1.profile_image as owner_profile_image,  u2.first_name AS allocated_user_first_name , u2.last_name  AS allocated_user_last_name, u2.profile_image as allocated_user_profile_image', FALSE);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u1','t.task_owner_id = u1.user_id','left');
		$CI->db->join('users u2','t.task_allocated_user_id = u2.user_id','left');
		$CI->db->where('t.master_task_id',$master_task_id);
		$CI->db->where('t.task_owner_id != ',"0");
		$CI->db->where('t.task_allocated_user_id != ',"0");
		$CI->db->where('t.task_orig_scheduled_date',$orig_due_date);
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	
	function get_task_kanban_order($task_id,$user_id){
		$CI =& get_instance();
		$query = $CI->db->select('kanban_order')->from("user_task_swimlanes")->where('task_id',$task_id)->where('user_id',$user_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->kanban_order;
		} else {
			return 0;
		}
	}

	function gettasklist($term,$date,$main_task_id)
	{
		$CI =& get_instance();
		$task_status_completed_id = $CI->config->item('completed_id');
		if($date!=''){
			$dt = date("Y-m-d",strtotime($date));
		}
		
		$CI->db->select('t.task_id,t.task_title,t.task_due_date');
		$CI->db->from('tasks t');
//		$CI->db->join('project_users pu','pu.project_id = t.task_project_id','left');
		$CI->db->where('t.task_title LIKE "%'.$term.'%"');
		if($date!=''){
			$CI->db->where('t.task_due_date',$dt);
		}
		$CI->db->where('t.task_allocated_user_id',get_authenticateUserID());
		$CI->db->where(array('t.is_deleted'=>'0','t.task_status_id <>'=>$task_status_completed_id,'t.master_task_id'=>'0','task_id <>'=>$main_task_id));
		$CI->db->order_by('t.task_id','DESC');
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $res = $query->result();
		} else {
			return 0;
		}
	}
	
	function is_task_exist_for_user($allocated_id,$task_id){
		$CI =& get_instance();
		$query = $CI->db->get_where("tasks",array("task_allocated_user_id"=>$allocated_id,"multi_allocation_task_id"=>$task_id,"is_deleted"=>"0"));
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	}
	
	function multiAllocationTaskIds($id){
		$CI =& get_instance();
		$query = $CI->db->select("task_id")->from("tasks")->where(array("multi_allocation_task_id"=>$id,"is_deleted"=>"0"))->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        
	function check_task_exist_today($task_id){
                $CI =& get_instance();
		$CI->db->select("task_due_date");
                $CI->db->from("tasks");
                $CI->db->where("master_task_id",$task_id);
                $CI->db->where('task_due_date',date('Y-m-d'));
                $CI->db->where("is_deleted","0");
               $query =  $CI->db->get();
               //echo $CI->db->last_query(); 
		if($query->num_rows()>0){
			return $query->row()->task_due_date;
		} else {
			return 0;
		}        
        }
        /*********** Start API related methods************/
	/**
         * This method is used for getting kanban task from db.
         * @param type $status
         * @param type $swimlanes
         * @param type $type
         * @param type $user_id
         * @param type $project_id
         * @param type $user_color_id
         * @param type $company_id
         * @return array
         */
        
        function get_kanban_tasks_data($status,$swimlanes,$type='',$user_id,$project_id,$user_color_id,$company_id,$limit,$offset,$user_timezone){
		$CI =& get_instance();
		$task_status_completed_id = get_company_completed_id($company_id);
		$result=array();
		$off_days = get_company_offdays();
		$task=array();
                foreach($swimlanes as $swimlane){
                $CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, uc.color_code, uc.outside_color_code , (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id =" '.$user_id.'") AS watch , (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies ',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		
		
		if($user_color_id !='0'){
			$CI->db->where('uts.color_id',$user_color_id);
		} else {
		}
		$CI->db->where('uts.swimlane_id',$swimlane->swimlanes_id);
		$CI->db->where('t.task_company_id',$company_id);
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id ',$user_id);
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.master_task_id','0');
		$CI->db->where('t.task_status_id',$status);
                $CI->db->order_by('t.task_completion_date','desc');
//		$CI->db->order_by('uts.swimlane_id','desc');
//		$CI->db->group_by('uts.swimlane_id');
                $CI->db->limit($limit,$offset);
                $query=$CI->db->get();
                $result[$swimlane->swimlanes_name]=$query->result_array();
                // print_r($result); die();
                //echo "<pre>"; echo $CI->db->last_query(); die();
                }
               // print_r($result); die();
		//if($query->num_rows()>0){ 
			$res = $result;
                       // print_r($res); die();
			if($res){ 
				if($swimlanes){  
					//if($status){  
						foreach($swimlanes as $swm){
							//foreach($status as $st){ 
								 
									$task[$swm->swimlanes_name]['status_task'] = array();
									foreach($res as $row){  
                                                                            foreach($row as $r){
										if($r['frequency_type'] == 'recurrence' && $r['recurrence_type']!='0'){ 
											$virtual_array = kanban_recurrence_logic($r,'',$off_days);
                                                                                       
											$chk_recu = chk_task_recurrence_existence($r,$virtual_array,$task_status_completed_id,$off_days,$company_id,$user_id);
											if($chk_recu){
												if($chk_recu['task_status_id'] == $status && $chk_recu['swimlane_id'] == $swm->swimlanes_id ){
													if($user_id!="0"){
														$comment['comments']= get_task_comments_info($chk_recu['master_task_id'],$user_timezone);
                                                                                                                $chk_recu=  array_merge($chk_recu,$comment);
														array_push($task[$swm->swimlanes_name]['status_task'],$chk_recu);
														
													} else {
                                                                                                            $comment['comments']= get_task_comments_info($chk_recu['master_task_id'],$user_timezone);
                                                                                                            $chk_recu=  array_merge($chk_recu,$comment);
														array_push($task[$swm->swimlanes_name]['status_task'],$chk_recu);
													}
												}
											}
										} else {
                                                                                  //$comment= get_task_comments_info($row['task_id']);
                                                                                   
											if($r['task_status_id'] == $status&& $r['swimlane_id'] == $swm->swimlanes_id){ 
												if($user_id!="0" ){
													//if($r['is_personal'] == "0"){ 
                                                                                                            $comment['comments']= get_task_comments_info($r['task_id'],$user_timezone);
                                                                                                            $r=  array_merge($r,$comment);
														array_push($task[$swm->swimlanes_name]['status_task'],$r);
//                                                                                                                 $comment= get_task_comments_info($row['task_id']);
//                                                                                                                if($comment){array_push($task[$swm->swimlanes_name][$st->task_status_name],$comment);}
													//}
												} else {
                                                                                                    $comment['comments']= get_task_comments_info($r['task_id'],$user_timezone);
                                                                                                       $r=  array_merge($r,$comment);
													array_push($task[$swm->swimlanes_name]['status_task'],$r);
                                                                                                         
                                                                                                         //if($comment){array_push($task[$swm->swimlanes_name][$st->task_status_name],$comment);}
                                                                                                       
												}
                                                                                                
											}
                                                                                 //       if($comment){array_push($task[$swm->swimlanes_name][$st->task_status_name],$comment);}
										}
									    //}
                                                                        }
							}
						}
					//}
				}
			}
		//}
		
                //echo "<pre>"; print_r($task); die();
                return $task; 
		
	}
	
        /**
         * It checks recurrence exists,if exist it return data array otherwise returns 0.
         * @param  $row
         * @param  $vr_arr
         * @param  $task_status_completed_id
         * @param  $off_days
         * @returns array|int
         */
	function chk_task_recurrence_existence($row,$vr_arr,$task_status_completed_id,$off_days,$company_id,$user_id){
		$CI =& get_instance();
		$CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, uc.color_code,uc.outside_color_code, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments , (SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.$user_id.') AS watch,  (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		$CI->db->where('t.master_task_id',$vr_arr['master_task_id']);
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_orig_scheduled_date',$vr_arr['task_orig_scheduled_date']);
		$CI->db->where('t.task_company_id',$company_id);
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
                //echo "<pre>"; echo $CI->db->last_query(); die();
		if($query->num_rows()>0){
			$chk_arr = $query->row_array();
			
			if($chk_arr['is_deleted'] == '0'){
				// check for completed
				if($chk_arr['task_status_id'] == $task_status_completed_id){
					$arr = kanban_recurrence_logic($row,$chk_arr['task_orig_scheduled_date'],$off_days);
					if($arr){
						return chk_recurrence_exists_kanban($row,$arr,$task_status_completed_id,$off_days,$company_id,$user_id);
					} else {
						return 0;
					}
					
				} else {
					return $chk_arr;
				}
			} else {
				return 0;
			}
		} else {
			return $vr_arr;
		}
	}
        
        
        function chk_recurrence_exists_kanban($row,$vr_arr,$task_status_completed_id,$off_days,$company_id,$user_id){
		$CI =& get_instance();
		$CI->db->select('t.*,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, uc.color_code,uc.outside_color_code, (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments ,  (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies',false);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		$CI->db->where('t.master_task_id',$vr_arr['master_task_id']);
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_orig_scheduled_date',$vr_arr['task_orig_scheduled_date']);
		$CI->db->where('t.task_company_id',$company_id);
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$chk_arr = $query->row_array();
			
			if($chk_arr['is_deleted'] == '0'){
				// check for completed
				if($chk_arr['task_status_id'] == $task_status_completed_id){
					$arr = kanban_recurrence_logic($row,$chk_arr['task_orig_scheduled_date'],$off_days);
					if($arr){
						return chk_recurrence_exists_kanban($row,$arr,$task_status_completed_id,$off_days,$company_id,$user_id);
					} else {
						return 0;
					}
					
				} else {
					return $chk_arr;
				}
			} else {
				return 0;
			}
		} else {
			return $vr_arr;
		}
	}
        
        
        function get_all_tasks($start_date,$end_date,$calender_project_id,$left_task_status_id='',$calender_team_user_id='',$calender_date='',$cal_user_color_id='0',$calender_sorting='1',$task_status_completed_id,$company_id,$status,$user_timezone){
		$result=array();
		$CI =& get_instance();
		$CI->db->select('t.*,u.profile_image,ts.task_status_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,uc.color_code, uc.outside_color_code,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies,(SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.$calender_team_user_id.') AS watch',FALSE);
		
		$CI->db->from('tasks t');
		$CI->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		
		if($cal_user_color_id!='0'){
			$CI->db->where('uts.color_id',$cal_user_color_id);
		}
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.task_company_id',$company_id);
		//$CI->db->where('t.master_task_id','0');
		if($status=='open' || $status =='overdue'){
                    $CI->db->where('t.task_status_id != ',$task_status_completed_id);
                }
                $CI->db->where('t.frequency_type','one_off');
//		$CI->db->where('t.task_due_date >=',$start_date);
//                $CI->db->where('t.task_due_date <=',$end_date);
                $CI->db->where('t.task_scheduled_date >=',$start_date);
                $CI->db->where('t.task_scheduled_date <=',$end_date);
                
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id',$calender_team_user_id);
		
		if($calender_sorting == '2'){
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '3'){
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '4'){
			$CI->db->order_by('t.task_time_estimate','desc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
		} else {
			$CI->db->order_by('uts.calender_order','asc');
			$CI->db->order_by('t.task_completion_date','asc');
		}
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
                $result1 = $query->result_array();
		
               //echo "<pre>"; print_r($result1); die();
                
                /**
                 * query for getting recurring task
                 */
                
                
                $CI->db->select('t.*,u.profile_image,ts.task_status_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,uc.color_code, uc.outside_color_code,CONCAT(u.first_name," ",u.last_name) as allocated_user_name, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies,(SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.$calender_team_user_id.') AS watch',FALSE);
		
		$CI->db->from('tasks t');
		$CI->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		
		if($cal_user_color_id!='0'){
			$CI->db->where('uts.color_id',$cal_user_color_id);
		}
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.task_company_id',$company_id);
		//$CI->db->where('t.master_task_id','0');
		if($status=='open' || $status =='overdue'){
                    $CI->db->where('t.task_status_id != ',$task_status_completed_id);
                }
                $CI->db->where('t.frequency_type','recurrence');
               // $CI->db->where('t.task_due_date <=',$start_date);
               // $CI->db->where('t.task_due_date <=',$end_date);
               // $CI->db->where('t.task_scheduled_date <=',$start_date);
                $CI->db->where('t.task_scheduled_date <=',$end_date);
                
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id',$calender_team_user_id);
		
		if($calender_sorting == '2'){
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '3'){
			$CI->db->order_by('t.task_due_date','asc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_time_estimate','desc');
		} elseif($calender_sorting == '4'){
			$CI->db->order_by('t.task_time_estimate','desc');
			$CI->db->order_by('t.task_priority','desc');
			$CI->db->order_by('t.task_due_date','asc');
		} else {
			$CI->db->order_by('uts.calender_order','asc');
			$CI->db->order_by('t.task_completion_date','asc');
		}
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
                $result2 = $query->result_array();
                $result=  array_merge($result1, $result2);
		//print_r($result); die();
			$res = $result;
			
			
			$week_start_date = date('Y-m-d',strtotime($start_date));
			$week_end_date = date('Y-m-d',strtotime($end_date));
			
			$task = array();
			while(strtotime($week_start_date)<=strtotime($week_end_date)){
				$j[$week_start_date] = array();
				$week_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($week_start_date)));
			}
			
			//echo "<pre>";
			//print_r($j); die;
			$task_array = array();
			$main_rec = array();
			$week_start_date = date('Y-m-d',strtotime($start_date));
			$week_end_date = date('Y-m-d',strtotime($end_date));
			$offdays = get_company_offdays();
			if($res){
				
				$i = 1;
				foreach($res as $row){
					//pr($row);
					//echo $i;
					if($i == 1)
					{
						$task_array = task_recu($row,$week_start_date,$week_end_date,$j,$calender_team_user_id,$task_status_completed_id,$offdays,$company_id,$user_timezone);
						
					}
					else
					{
						$task_array = task_recu($row,$week_start_date,$week_end_date,$task_array,$calender_team_user_id,$task_status_completed_id,$offdays,$company_id,$user_timezone);
						//pr($task_array);
					}
					
					$i++;
					
				}
				
			
			}
			//echo "<pre>"; print_r($task_array); die();
			return $task_array;
		
	
        }
        
        
        function task_recu($row,$month_start_date,$month_end_date,$j,$calender_team_user_id,$task_status_completed_id,$offdays,$company_id,$user_timezone){
		
            $task = array();
		$virtual_array = '';
		//pr($row);
		if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){ 
			//$org_month_start_date = toDate($month_start_date);
			//$org_month_end_date = toDate($month_end_date);
			$virtual_array = monthly_recurrence_logic($row,$month_start_date,$month_end_date,$offdays);
		}
		//pr($virtual_array);
		
		while(strtotime($month_start_date) <= strtotime($month_end_date)){
			
			//$orig_month_start_date = toDate($month_start_date);
			
			$task[$month_start_date] = array();
			
			if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){ 
				if($virtual_array){
					foreach($virtual_array as $vrt_arry){
						if(strtotime($vrt_arry['task_scheduled_date']) == strtotime($month_start_date)){
							$chk_recu = chk_virtual_recurrence_existence($vrt_arry['master_task_id'],$vrt_arry['task_orig_scheduled_date'],$task_status_completed_id,$company_id,$calender_team_user_id);
                                                                    
							if($chk_recu){
								//array_push($j[$month_start_date],$chk_recu);
							} else { 
								if($vrt_arry['task_allocated_user_id'] == $calender_team_user_id){
                                                                        $comment['comments']= get_task_comments_info($vrt_arry['master_task_id'],$user_timezone);
                                                                        $vrt_arry=  array_merge($vrt_arry,$comment);
									array_push($j[$month_start_date],$vrt_arry);
                                                                        
								}
							}
						}
					}
				}
			} else { 
                                if(is_array($calender_team_user_id)){
                                    for($i=0;$i<count($calender_team_user_id);$i++){
                                    if($row['task_scheduled_date'] == $month_start_date && $row['task_allocated_user_id']==$calender_team_user_id[$i]){  
					$comment['comments']= get_task_comments_info($row['task_id'],$user_timezone);
                                        $row=  array_merge($row,$comment);
                                        array_push($j[$month_start_date],$row);
                                        
                                    }
                                    }  //print_r($calender_team_user_id); die();
                                }
				if($row['task_scheduled_date'] == $month_start_date && $row['task_allocated_user_id']==$calender_team_user_id){ 
                                        $comment['comments']= get_task_comments_info($row['task_id'],$user_timezone);
                                        $row=  array_merge($row,$comment);
					array_push($j[$month_start_date],$row);
                                       
				}
			}
			
			$month_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($month_start_date)));
		}
		//echo "<pre>"; pr($j);
		return $j;
	}
        
        
        
        function chk_virtual_recurrence_existence($master_task_id,$orig_scheduled_date,$task_status_completed_id='',$company_id,$calender_team_user_id){
		
		$CI =& get_instance();
		
		if($task_status_completed_id){
			$task_status_completed_id = $task_status_completed_id;
		} 
		
		$CI->db->select('t.*,u.first_name,u.last_name, tc.category_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,p.project_title,uc.color_code, uc.outside_color_code,CONCAT(u.first_name," ",u.last_name) as allocated_user_name,   (SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments,  (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies,(SELECT COUNT(1) FROM my_watch_list w  WHERE w.task_id = t.task_id and w.user_id = '.$calender_team_user_id.') AS watch',FALSE);
		$CI->db->from('tasks t');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		$CI->db->join('task_category tc','t.task_category_id = tc.category_id','left');
		//$CI->db->join('task_steps tsp','tsp.task_id = t.task_id','left');
		$CI->db->where('t.master_task_id',$master_task_id);
		$CI->db->where('t.task_orig_scheduled_date',$orig_scheduled_date);
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_company_id',$company_id);
		$CI->db->group_by('t.task_id');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
        
        /**
         * This function is used for getting task info using task_id and company_id.
         * @param type $task_id
         * @param type $company_id
         * @return int
         */
        function get_task_info($task_id,$company_id){
		
		$CI =& get_instance();
		$CI->db->select('t.*,u.profile_image,u.first_name,u.last_name,p.project_title,ps.section_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos,uts.color_id,uts.swimlane_id');
		$CI->db->from('tasks t');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('project_section ps','ps.section_id = t.subsection_id','left');
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_company_id',$company_id);
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_id',$task_id);
		$query = $CI->db->get();
                if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
        
        /**
         * This method is check watch list in db.
         * @param type $task_id
         * @param type $user_id
         * @return int
         */
        
        function chk_task_watchlist($task_id,$user_id){
		$CI =& get_instance();
		$query = $CI->db->get_where('my_watch_list',array('task_id'=>$task_id,'user_id ' => $user_id));
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	} 
        
        /**
         * It's used for getting task comment info using task id.
         * @param type $task_id
         * @return int
         */
        function get_task_comments_info($task_id,$user_timezone){
		
		$CI =& get_instance();
		$project_id = get_project_id_from_task_id($task_id);
		$CI->db->select('tc.*,CONCAT(u.first_name,SPACE(1),u.last_name) as comment_addbyUser');
		$CI->db->from('task_and_project_comments tc');
		$CI->db->join('users u','u.user_id = tc.comment_addeby');
		$CI->db->where('tc.task_id',$task_id);
		if($project_id){
			$CI->db->where('project_id',$project_id);
		} else {
			$CI->db->where('project_id','0');	
		}
		$CI->db->order_by('task_comment_id','desc');
		$query = $CI->db->get();
              //  echo $CI->db->last_query(); die();
                if($query->num_rows()>0){
			$new_array = array();
                        $comment=$query->result_array();
                        foreach($comment as $comm){ 
                                            $comment_date = $comm['comment_added_date']; 
                                            $comment_newdate = date('jS M Y g:i a',strtotime(toDateNewTimed($comment_date,$user_timezone)));
                                            $comm['comment_added_date'] = $comment_newdate;
                                            $new_array[] = $comm;
                                           
                                        }  
                         return $new_array; 
		} else {
			return array();
		}
        }
        /**
         * This function is used for getting user capacity perday.
         * @param type $user_id
         * @return int
         */
        
        function getUserCapacity_id($user_id){
			
			$CI =& get_instance();
                        $CI->db->select('MON_hours,TUE_hours,WED_hours,THU_hours,FRI_hours,SAT_hours,SUN_hours');
                        $CI->db->from('default_calendar_setting');
                        $CI->db->where('user_id',$user_id);
                        $query=$CI->db->get();
			//$query = $CI->db->get_where('default_calendar_setting',array('user_id'=>$user_id));
			if($query->num_rows()>0){
				return  $query->row_array();
			} else {
				return 0;
			}
		}
        
        
        /****** End Api methods  ********/
                
                
        function get_employee_list($limit,$offset){
                $CI =& get_instance();
                $CI->db->select('u.*,sls.staff_level_title');
		$CI->db->from('users u');
                $CI->db->join('staff_levels sls','sls.staff_level_id = u.staff_level','left');
		$CI->db->where('u.company_id',$CI->session->userdata('company_id'));
		$CI->db->where('u.is_deleted','0');
                $CI->db->order_by('u.first_name','asc');
                $CI->db->limit($limit,$offset);
		$query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
        }
        function count_total_employee(){
                $CI =& get_instance();
                $CI->db->select('*');
		$CI->db->from('users u');
		$CI->db->where('u.company_id',$CI->session->userdata('company_id'));
		$CI->db->where('u.is_deleted','0');
                $query = $CI->db->get();
                
                    return $query->num_rows();
                
        }
        
        function get_task_owner_image($owner_id){
                 $CI =& get_instance();
                $CI->db->select('u.profile_image,u.first_name,u.last_name');
		$CI->db->from('users u');
		$CI->db->where('u.user_id',$owner_id);
		$CI->db->where('u.is_deleted','0');
                $query = $CI->db->get();
                
                return $query->row();
                
        }
        
        
        function get_authenticated_user_charge_out_rate(){
                $CI = &get_instance();
                $CI->db->select('base_charge_rate_per_hour');
                $CI->db->from('users');
                $CI->db->where('user_id',  get_authenticateUserID());
                $CI->db->where('company_id',$CI->session->userdata('company_id'));
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->base_charge_rate_per_hour;
                }else{
                    return 0;
                }
        }
        
        function is_exist_task($event_id,$user_id,$access_mode = 'gmail'){
                $CI = &get_instance();
                $CI->db->select('*');
                $CI->db->from('tasks');
                if($access_mode == 'gmail')
                    $CI->db->where('gmail_task_id', $event_id);
                else
                    $CI->db->where('outlook_task_id', $event_id);
                $CI->db->where('task_allocated_user_id',$user_id);
                $CI->db->where('task_owner_id',$user_id);
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row_array();
                }else{
                    return 0;
                }
        }
        
        function common_method_for_task($response,$access_mode){
            $CI = &get_instance();
            $app_info = getAppInfo();
            $client_id = $app_info[0]->client_id;
            $client_secret = $app_info[0]->client_secret;
            $fields=array(
                'grant_type'=>  'client_credentials',
                'client_id'=> $client_id,
                'client_secret'=> $client_secret
            );
            
            $post='';
                        
            foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
            $post = rtrim($post,'&');
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => base_url()."OAuth2/token",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $post,
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
              ),
            ));

            $response1 = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            
            $response1 = json_decode($response1);
            
            if(isset($response1->access_token)){
                $timezone = get_UserTimeZone();
                date_default_timezone_set($timezone);
                $api_access_token=$response1->access_token;
                $user_id = get_authenticateUserID();
                $task_status_id = get_task_status_id_by_name('Ready');
                if(isset($response)){
                    foreach($response as $task){
                            
                        $header = array(
                                "accept: application/json",
                                "Authorization: Bearer ".$api_access_token,
                                "cache-control: no-cache",
                                "Content-Type: application/x-www-form-urlencoded"
                              );
                        $today = Date('Y-m-d');
                        
                            
                            $event_start_date = isset($task['start']['dateTime'])?$task['start']['dateTime']:$task['start']['date'];
                            $event_end_date = isset($task['end']['dateTime'])?$task['end']['dateTime']:$task['end']['date'];
                            
                            $task_scheduled_date=Date('Y-m-d',(strtotime($event_start_date)+date("Z")));
                            $task_time_estimate = (strtotime($event_end_date)-strtotime($event_start_date))/60;
                            
                            
                            if($task_scheduled_date>=$today){
                                if($access_mode == 'outlook'){
                                    $outlook_task_id = $task['id'];
                                    $CI->db->where('outlook_task_id',$outlook_task_id);
                                }else{
                                    $gmail_task_id = $task['id'];
                                    $CI->db->where('gmail_task_id',$gmail_task_id);
                                }
                                
                                $CI->db->where('task_owner_id',$user_id);
                                $query = $CI->db->get('tasks');
                                $exist_task = $query->row_array();
                                
                                if(!$exist_task){
                                    if($access_mode == 'outlook'){
                                        $task_title = $task['subject'];
                                        $task_description = $task['bodyPreview'];
                                        $gmail_task_id = '';
                                    }else{
                                        $task_title = $task['summary'];
                                        $task_description = isset($task['description'])?$task['description']:'';
                                        $outlook_task_id = '';
                                    }
                                    $fields=array(
                                        'user_id'=>  $user_id,
                                        'task_title'=> $task_title,
                                        'task_description'=> $task_description,
                                        'task_due_date' => $task_scheduled_date,
                                        'task_scheduled_date'=> $task_scheduled_date,
                                        'task_status_id'=> $task_status_id,
                                        'task_allocated_user_id'=>  $user_id,
                                        'task_project_id'=> 0,
                                        'task_time_estimate'=>$task_time_estimate,
                                        'outlook_task_id'=>$outlook_task_id,
                                        'gmail_task_id'=>$gmail_task_id
                                    );
                            
                                    $post='';
                                    foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                                    $post = rtrim($post,'&');
                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => base_url()."api/v1/addTask",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => "",
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => "POST",
                                      CURLOPT_POSTFIELDS => $post,
                                      CURLOPT_HTTPHEADER =>$header ,
                                    ));

                                    $response2 = curl_exec($curl);
                                    $err = curl_error($curl);
                                    $task_data = json_decode($response2,true);
                                    curl_close($curl);
                                   if($access_mode == 'outlook'){
                                    if($task['type']=='seriesMaster'){
                                        create_integrated_recurring_task($task,$access_mode,$task_data['task_id']);
                                    }
                                   }else{
                                       if($task['recurrence']){
                                        create_integrated_recurring_task($task['recurrence'],$access_mode,$task_data['task_id'],$task_scheduled_date);
                                       }
                                       if(isset($task['recurringEventId'])){
                                           $master_task_id = get_master_task_id_by_gmailid($task['recurringEventId'],$user_id);
                                            if($master_task_id != 0){
                                                $CI->db->set('master_task_id',$master_task_id);
                                                $CI->db->where('task_id',$task_data['task_id']);
                                                $CI->db->where('gmail_task_id',$gmail_task_id);
                                                $CI->db->update('tasks');
                                            }
                                       }
                                   }
                                }
                            }
                      
                    }
                }
            }
            
        }
        
        function create_integrated_recurring_task($one,$access_mode,$task_id,$task_date ='',$user = ''){
           $CI = &get_instance();
           if($user!='')
           {
               $u = explode('@',$user);
               $user_id = $u[0];
               $company_id = $u[1];
           }
           else{
               $user_id = get_authenticateUserID();
            $company_id = $CI->session->userdata('company_id');
           }
           
           $Weekly_every_week_no = 0;
           $end_by_date = '0000-00-00';
           $Daily_every_day = 0;
           $end_after_recurrence = 0;
           $Weekly_week_day = 0;
           $monthly_radios = 0;
           $Monthly_op1_1 = 0;
           $Monthly_op1_2 = 0;
           $Monthly_op2_1 = '';
           $Monthly_op2_2 = '';
           $Monthly_op2_3 = 0;
           $Monthly_op3_1 = 0;
           $Monthly_op3_2 = 0;
           $yearly_radios = 0;
           $Yearly_op1 = 0;
           $Yearly_op2_1 = 0;
           $Yearly_op2_2 = 0;
           $Yearly_op3_1 = '';
           $Yearly_op3_2 = '';
           $Yearly_op3_3 = '';
           if($access_mode == 'outlook'){
            $type = $one['recurrence']['pattern']['type'];
            $frequency_type = 'recurrence';
            $end = $one['recurrence']['range']['type'];
            if($end == 'noEnd'){
                $start_on_date = $one['recurrence']['range']['startDate'];
                $no_end_date = 1;
            }else if($end == 'endDate'){
                $no_end_date = 3;
                $start_on_date = $one['recurrence']['range']['startDate'];
                $end_by_date = $one['recurrence']['range']['endDate'];
            }else if($end == 'numbered'){
                $no_end_date = 2;
                $start_on_date = $one['recurrence']['range']['startDate'];
                $end_after_recurrence = $one['recurrence']['range']['numberOfOccurrences'];
            }
                                        
            if($type == 'daily'){
                $Daily_every_day = $one['recurrence']['pattern']['interval'];
                $recurrence_type = 1;
            }else if($type == 'weekly'){
                $recurrence_type = 2;
                $Weekly_every_week_no = $one['recurrence']['pattern']['interval'];
                $daysOfWeek = $one['recurrence']['pattern']['daysOfWeek'];
                $days=array();
                foreach($daysOfWeek as $day){
                    if($day == 'monday'){
                        $days[] = '1';
                    }else if($day == 'tuesday'){
                        $days[] = '2';
                    }else if($day == 'wednesday'){
                        $days[] = '3';
                    }else if($day == 'thursday'){
                        $days[] = '4';
                    }else if($day == 'friday'){
                        $days[] = '5';
                    }else if($day == 'saturday'){
                        $days[] = '6';
                    }else if($day == 'sunday'){
                        $days[] = '7';
                    }
                }
                $Weekly_week_day = implode(',',$days);
            }else if($type == 'absoluteMonthly'){
                $recurrence_type = 3;
                $monthly_radios = 1;
                $Monthly_op1_1 = $one['recurrence']['pattern']['interval'];
                $Monthly_op1_2 = $one['recurrence']['pattern']['dayOfMonth'];
            }else if($type == 'relativeMonthly'){
                $recurrence_type = 3;
                $monthly_radios = 2;
                $Monthly_op2_1 = $one['recurrence']['pattern']['index'];
                $Monthly_op2_2 = ucfirst($one['recurrence']['pattern']['daysOfWeek'][0]);
                $Monthly_op2_3 = $one['recurrence']['pattern']['interval'];
            }else if($type == 'absoluteYearly'){
                $recurrence_type = 4;
                $yearly_radios = 2;
                $Yearly_op2_1 = $one['recurrence']['pattern']['month'];
                $Yearly_op2_2 = $one['recurrence']['pattern']['dayOfMonth'];
            }else if($type == 'relativeYearly'){
                $recurrence_type = 4;
                $yearly_radios = 3;
                $Yearly_op3_1 = $one['recurrence']['pattern']['index'];
                $Yearly_op3_2 = ucfirst($one['recurrence']['pattern']['daysOfWeek'][0]);
                $year = $one['recurrence']['pattern']['month'];
                    if($year == 1){
                        $Yearly_op3_3 = 'January';
                    }else if($year == 2){
                        $Yearly_op3_3 = 'February';
                    }else if($year == 3){
                        $Yearly_op3_3 = 'March';
                    }else if($year == 4){
                        $Yearly_op3_3 = 'April';
                    }else if($year == 5){
                        $Yearly_op3_3 = 'May';
                    }else if($year == 6){
                        $Yearly_op3_3 = 'June';
                    }else if($year == 7){
                        $Yearly_op3_3 = 'July';
                    }else if($year == 8){
                        $Yearly_op3_3 = 'August';
                    }else if($year == 9){
                        $Yearly_op3_3 = 'September';
                    }else if($year == 10){
                        $Yearly_op3_3 = 'October';
                    }else if($year == 11){
                        $Yearly_op3_3 = 'November';
                    }else if($year == 12){
                        $Yearly_op3_3 = 'December';                        
                    }
            }
            
            if($no_end_date == 2){
                $array=array(
                    'user_id'=>$user_id,
                    'company_id'=>$company_id,
                    'frequency_type'=>$frequency_type,
                    'recurrence_type'=>$recurrence_type,
                    'Daily_every_day'=>$Daily_every_day,
                    'Daily_every_weekday'=>'',
                    'Weekly_every_week_no'=>$Weekly_every_week_no,
                    'Weekly_week_day'=>$days,
                    'monthly_radios'=>$monthly_radios,
                    'Monthly_op1_1'=>$Monthly_op1_1,
                    'Monthly_op1_2'=>$Monthly_op1_2,
                    'Monthly_op2_1'=>$Monthly_op2_1,
                    'Monthly_op2_2'=>$Monthly_op2_2,
                    'Monthly_op2_3'=>$Monthly_op2_3,
                    'Monthly_op3_1'=>'',
                    'Monthly_op3_2'=>'',
                    'yearly_radios'=>$yearly_radios,
                    'Yearly_op1'=>'',
                    'Yearly_op2_1'=>$Yearly_op2_1,
                    'Yearly_op2_2'=>$Yearly_op2_2,
                    'Yearly_op3_1'=>$Yearly_op3_1,
                    'Yearly_op3_2'=>$Yearly_op3_2,
                    'Yearly_op3_3'=>$Yearly_op3_3,
                    'Yearly_op4_1'=>'',
                    'Yearly_op4_2'=>'',
                    'start_on_date'=>$start_on_date,
                    'no_end_date'=>$no_end_date,
                    'end_by_date'=>$end_by_date,
                    'end_after_recurrence'=>$end_after_recurrence
                );
		$set_end = set_end_date_from_occurence($array);
                $end_by_date = $set_end['end_date'];
            }
            
          }else{
                $str = $one[0];
                $str = str_replace('RRULE:', '', $str);
                $rec_data = explode(';', $str);
               
                $frequency_type = 'recurrence';
                $type = str_replace('FREQ=', '', $rec_data[0]);
                $Daily_every_day = '1'; 
                $start_on_date = $task_date;
                $no_end_date = 1;
                if($type == 'DAILY'){
                    $recurrence_type = 1;
                    if(isset($rec_data[1])){ 
                        $d1 = explode('=', $rec_data[1]);
                        if($d1[0] == 'COUNT'){ 
                            $no_end_date = 2;
                            $end_after_recurrence = $d1[1];
                            if(isset($rec_data[2])){
                                $d3 = explode('=', $rec_data[2]);
                                if($d3[0] == 'INTERVAL'){
                                  $end_by_date =  date("Y-m-d",strtotime("+".($d1[1]*$d3[1])." day", strtotime($start_on_date)));  
                                }
                            }else{
                                $end_by_date =  date("Y-m-d",strtotime("+".$d1[1]." day", strtotime($start_on_date)));
                            }
                        }else if($d1[0] == 'UNTIL'){
                            $no_end_date = 3;
                            $D_date = str_replace("Z","",$d1[1]);
                            $end_by_date = Date('Y-m-d',(strtotime($D_date)));
                        }else if($d1[0] == 'INTERVAL'){
                            $Daily_every_day = $d1[1];
                            $no_end_date = 1;
                        }
                    }else{
                        $no_end_date = 1;
                    }

                    if(isset($rec_data[2])){
                        $d2 = explode('=', $rec_data[2]);
                        if($d2[0] == 'BYDAY'){
                            $no_end_date = 1;
                        }else if($d2[0] == 'INTERVAL'){
                            $Daily_every_day = $d2[1];
                        }
                    }
                }
                else if($type == 'WEEKLY'){
                    $Weekly_every_week_no = 1;
                    $recurrence_type = 2;
                    
                    if(isset($rec_data[1])){
                        $w1 = explode('=', $rec_data[1]);
                        if($w1[0] == 'BYDAY'){ 
                            $daysOfWeek = explode(',', $w1[1]);
                            $days=array();
                            foreach($daysOfWeek as $day){
                                if($day == 'MO'){
                                    $days[] = '1';
                                }else if($day == 'TU'){
                                    $days[] = '2';
                                }else if($day == 'WE'){
                                    $days[] = '3';
                                }else if($day == 'TH'){
                                    $days[] = '4';
                                }else if($day == 'FR'){
                                    $days[] = '5';
                                }else if($day == 'SA'){
                                    $days[] = '6';
                                }else if($day == 'SU'){
                                    $days[] = '7';
                                }
                            }
                            $no_end_date = 1;
                            $Weekly_week_day = implode(',',$days);
                        }else if($w1[0] == 'INTERVAL'){
                            $Weekly_every_week_no = $w1[1];
                            $no_end_date = 1;
                        }else if($w1[0] == 'COUNT'){
                            $no_end_date = 2;
                            $end_after_recurrence = $w1[1];
                            $end_by_date =  date("Y-m-d",strtotime("+".$w1[1]." week", strtotime($start_on_date)));
                        }else if($w1[0] == 'UNTIL'){
                            $no_end_date = 3;
                            $W_date = str_replace("Z","",$w1[1]);
                            $end_by_date = Date('Y-m-d',(strtotime($W_date)));
                        }
                    }
                    if(isset($rec_data[2])){
                        $w2 = explode('=', $rec_data[2]);
                        if($w2[0] == 'BYDAY'){
                            $daysOfWeek = explode(',', $w2[1]);
                            $days=array();
                            foreach($daysOfWeek as $day){
                                if($day == 'MO'){
                                    $days[] = '1';
                                }else if($day == 'TU'){
                                    $days[] = '2';
                                }else if($day == 'WE'){
                                    $days[] = '3';
                                }else if($day == 'TH'){
                                    $days[] = '4';
                                }else if($day == 'FR'){
                                    $days[] = '5';
                                }else if($day == 'SA'){
                                    $days[] = '6';
                                }else if($day == 'SU'){
                                    $days[] = '7';
                                }
                            }
                            $no_end_date = 1;
                            $Weekly_week_day = implode(',',$days);
                        }
                    }
                }
                else if($type == 'MONTHLY'){
                    $recurrence_type = 3;
                    $monthly_radios = 1;
                    if(isset($rec_data[1])){
                        $m1 = explode('=', $rec_data[1]);
                        if($m1[0] == 'INTERVAL'){ 
                            $Monthly_op1_1 = 1;
                            $Monthly_op1_2 = $m1[1];
                        }else if($m1[0] == 'COUNT'){
                            $no_end_date = 2;
                            $end_after_recurrence = $m1[1];
                            $end_by_date =  date("Y-m-d",strtotime("+".$m1[1]." month", strtotime($start_on_date)));
                        }else if($m1[0] == 'UNTIL'){
                            $no_end_date = 3;
                            $M_date = str_replace("Z","",$m1[1]);
                            $end_by_date = Date('Y-m-d',(strtotime($M_date)));
                        }else if($m1[0] == 'BYDAY'){
                            $monthly_radios = 2;
                            $month_data = preg_split('#(?<=\d)(?=[a-z])#i', $m1[1]);
                            if($month_data[0] == '1'){
                                $month_week = 'first';
                            }else if($month_data[0] == '2'){
                                $month_week = 'second';
                            }else if($month_data[0] == '3'){
                                $month_week = 'third';
                            }else if($month_data[0] == '4'){
                                $month_week = 'fourth';
                            }else{
                                $month_week = 'last';
                            }

                            if($month_data[1] == 'MO'){
                                $days= 'Monday';
                            }else if($month_data[1] == 'TU'){
                                $days = 'Tuesday';
                            }else if($month_data[1] == 'WE'){
                                $days = 'Wednesday';
                            }else if($month_data[1] == 'TH'){
                                $days = 'Thursday';
                            }else if($month_data[1] == 'FR'){
                                $days = 'Friday';
                            }else if($month_data[1] == 'SA'){
                                $days = 'Saturday';
                            }else if($month_data[1] == 'SU'){
                                $days = 'Sunday';
                            }
                            $Monthly_op2_1 = $month_week;
                            $Monthly_op2_2 = $days;
                            $Monthly_op2_3 = 1;
                        }
                    }else{
                        $Monthly_op1_1 = 1;
                        $Monthly_op1_2 = 1;
                    }

                    if(isset($rec_data[2])){
                        $m2 = explode('=', $rec_data[2]);
                        if($m2[0] == 'INTERVAL'){
                            $Monthly_op1_1 = 1;
                            $Monthly_op1_2 = $m2[1];
                        }else if($m2[0] == 'BYDAY'){
                            $monthly_radios = 2;
                            $month_data = preg_split('#(?<=\d)(?=[a-z])#i', $m2[1]);
                            if($month_data[0] == '1'){
                                $month_week = 'first';
                            }else if($month_data[0] == '2'){
                                $month_week = 'second';
                            }else if($month_data[0] == '3'){
                                $month_week = 'third';
                            }else if($month_data[0] == '4'){
                                $month_week = 'fourth';
                            }else{
                                $month_week = 'last';
                            }

                            if($month_data[1] == 'MO'){
                                $days= 'Monday';
                            }else if($month_data[1] == 'TU'){
                                $days = 'Tuesday';
                            }else if($month_data[1] == 'WE'){
                                $days = 'Wednesday';
                            }else if($month_data[1] == 'TH'){
                                $days = 'Thursday';
                            }else if($month_data[1] == 'FR'){
                                $days = 'Friday';
                            }else if($month_data[1] == 'SA'){
                                $days = 'Saturday';
                            }else if($month_data[1] == 'SU'){
                                $days = 'Sunday';
                            }


                            $m3 = explode('=', $rec_data[1]);
                            if($m3[0] == 'INTERVAL'){
                                $Monthly_op2_3 = $m3[1];
                            }
                            $Monthly_op1_1 = 0;
                            $Monthly_op1_2 = 0;
                            $Monthly_op2_1 = $month_week;
                            $Monthly_op2_2 = $days;

                        }

                    }
                }
                else if($type == 'YEARLY'){
                 $recurrence_type = 4;
                 if(isset($rec_data[1])){
                     $y1 = explode('=', $rec_data[1]);
                     if($y1[0] == 'INTERVAL'){
                        $yearly_radios = 1;
                        $Yearly_op1 = $y1[1];
                     }else if($y1[0] == 'COUNT'){ 
                        $no_end_date = 2;
                        $end_after_recurrence = $y1[1];
                        $end_by_date =  date("Y-m-d",strtotime("+".$y1[1]." year", strtotime($start_on_date)));
                     }else if($y1[0] == 'UNTIL'){
                        $no_end_date = 3;
                        $Y_date = str_replace("Z","",$y1[1]);
                        $end_by_date = Date('Y-m-d',(strtotime($Y_date)));
                    }else{
                        $yearly_radios = 1;
                        $Yearly_op1 = 1;
                    }
                 }else{
                     $yearly_radios = 1;
                     $Yearly_op1 = 1;
                 }
                 
                 if(isset($rec_data[2])){
                     $y2 = explode('=', $rec_data[2]);
                     if($y2[0] == 'INTERVAL'){
                         $yearly_radios = 1;
                         $Yearly_op1 = $y2[1];
                     }
                 }
            }
           }
           
           $fields=array(
                    'frequency_type'=>$frequency_type,
                    'recurrence_type'=>$recurrence_type,
                    'Daily_every_day'=>$Daily_every_day,
                    'Daily_every_weekday'=>'',
                    'Weekly_every_week_no'=>$Weekly_every_week_no,
                    'Weekly_week_day'=>$Weekly_week_day,
                    'monthly_radios'=>$monthly_radios,
                    'Monthly_op1_1'=>$Monthly_op1_1,
                    'Monthly_op1_2'=>$Monthly_op1_2,
                    'Monthly_op2_1'=>$Monthly_op2_1,
                    'Monthly_op2_2'=>$Monthly_op2_2,
                    'Monthly_op2_3'=>$Monthly_op2_3,
                    'Monthly_op3_1'=>$Monthly_op3_1,
                    'Monthly_op3_2'=>$Monthly_op3_2,
                    'yearly_radios'=>$yearly_radios,
                    'Yearly_op1'=>$Yearly_op1,
                    'Yearly_op2_1'=>$Yearly_op2_1,
                    'Yearly_op2_2'=>$Yearly_op2_2,
                    'Yearly_op3_1'=>$Yearly_op3_1,
                    'Yearly_op3_2'=>$Yearly_op3_2,
                    'Yearly_op3_3'=>$Yearly_op3_3,
                    'Yearly_op4_1'=>'',
                    'Yearly_op4_2'=>'',
                    'start_on_date'=>$start_on_date,
                    'no_end_date'=>$no_end_date,
                    'end_by_date'=>$end_by_date,
                    'end_after_recurrence'=>$end_after_recurrence
            );
            
            $CI->db->where('task_id',$task_id);
            $CI->db->where('is_deleted','0');
            $CI->db->update('tasks',$fields);
        }
        
        function get_master_task_id_by_gmailid($recurring_event_id,$user_id){
            $CI = &get_instance();
            $CI->db->select('task_id');
            $CI->db->from('tasks');
            $CI->db->where('gmail_task_id',$recurring_event_id);
            $CI->db->where('task_allocated_user_id',$user_id);
            $CI->db->where('is_deleted','0');
            $query = $CI->db->get();
            if($query->num_rows()>0){
                return $query->row()->task_id;
            }else{
                return 0;
            }
            
        }
        
        /**
         * Check customer user task in db
         */
        function chk_customerUser_task($customer_id){
            $CI = &get_instance();
            $CI->db->select('task_id');
            $CI->db->from('tasks');
            $CI->db->where('task_allocated_user_id',$customer_id);
            $CI->db->where('task_owner_id !=','');
            $CI->db->where('is_deleted','0');
            $query = $CI->db->get();
            if($query->num_rows()>0){
                return 1;
            }else{
                return 0;
            }
        }
/* End of file task_helper.php */
/* Location: ./system/application/helpers/task_helper.php */
?>