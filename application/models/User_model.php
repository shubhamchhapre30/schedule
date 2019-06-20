<?php
/**
 * This class is used for database interaction, this class is used to access data from database for user controller request.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class User_model extends CI_Model 
{
    /**
        * It default constuctor which is called when user_model object is initialzied.It load base class methods & variables.
        * @returns void
        */
	
	
	function User_model()
    {
        parent::__construct();	
    } 
	/**
        * This function is used for get user info from db.This function get details from users,default_calender_setting table and return it's array.
        * @param int $user_id 
        * @returns int 
        */
	
	function get_user_details($user_id){
            /**
             * select data query
             */
		$this->db->select('u.first_name,u.last_name,u.email,u.user_id,u.contact_no,u.profile_image,u.staff_level,u.user_time_zone,u.is_administrator,u.is_owner,u.is_manager,u.user_status,u.user_default_page,u.default_color,u.daily_email_summary,d.MON_hours,d.TUE_hours,d.WED_hours,d.THU_hours,d.FRI_hours,d.SAT_hours,d.SUN_hours,d.MON_closed,d.TUE_closed,d.WED_closed,d.THU_closed,d.FRI_closed,d.SAT_closed,d.SUN_closed,u.timesheet_approver_id,u.customer_module_access,u.	xero_access,u.user_background_type,u.user_background_name');
		$this->db->from('users u');
		$this->db->join('default_calendar_setting d','d.user_id = u.user_id','left');
		$this->db->where('u.user_id',$user_id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}
	/**
         * This function returns user division id from company_division table .
         * @param string $name
         * @returns int
         */
	function get_division_id_by_name($name){
		$query = $this->db->get_where('company_divisions',array('devision_title'=>$name, 'company_id'=>$this->session->userdata('company_id')));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->division_id;
		} else {
			return 0;
		}
		
	}
	/**
         * This returns department id from company_departments table.
         * @param string $name
         * @returns int
         */
	function get_department_id_by_name($name){
		$query = $this->db->get_where('company_departments',array('department_title'=>$name, 'company_id'=>$this->session->userdata('company_id')));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->department_id;
		} else {
			return 0;
		}
	}
        /**
         * it will return skill id of user in db.
         * @param string $name
         * @returns int
         */
	function get_skill_id_by_name($name){
		$query = $this->db->get_where('skills',array('skill_title'=>$name, 'company_id'=>$this->session->userdata('company_id')));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->skill_id;
		} else {
			return 0;
		}
		
	}
	/**
         * it checks this id is manager or not.
         * @param int $user_id
         * @param int $manager_id
         * @returns int
         */
	function is_manager_exist($user_id,$manager_id){
		$query = $this->db->select("manager_id")->from("user_managers")->where("user_id",$user_id)->where("manager_id",$manager_id)->get();
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	}
	/**
         * This returns array of tasks for manager.
         * @param int $user_id
         * @returns int
         */
	function get_task_ids_for_maganer($user_id){
		$query = $this->db->select("task_id,task_scheduled_date,task_status_id")->from("tasks")->where("task_allocated_user_id",$user_id)->where("is_deleted","0")->where("is_personal","0")->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	/**
         * This functiond is used for update details of user.
         * @param int  $user_id
         * @returns int
         */
	function update_user($user_id){
		
		//echo pr($_POST);die;
		if($this->input->post('manager_multiselect')){
			$manager = $this->input->post('manager_multiselect');
                        /**
                         * check manager
                         */
			if($manager){
				foreach ($manager as $row){
					$chk = $this->is_manager_exist($user_id,$row);
					if($chk == "1"){
						$this->db->delete("user_managers",array("user_id"=>$user_id,"manager_id"=>$row));
					}
				}
			}
		}
		/*
                 * Add a condition for checking user_id and manager_id is not same in admin case.
                 */
		if($this->input->post('manager_multiselect_to')){
			$managers = $this->input->post('manager_multiselect_to');
			if($managers){
				foreach($managers as $row){
					$chk = $this->is_manager_exist($user_id,$row);
					if($chk == "0" && $user_id != $row){
						$data = array(
							'user_id' => $user_id,
							'manager_id' => $row,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_managers',$data);
					}
				}
			}
		} else {
			$this->db->delete('user_managers',array('user_id'=>$user_id));
		}
		
		if($this->input->post('tags_division')){
			$tags_division = explode(',', $this->input->post('tags_division'));
			if($tags_division){
				$this->db->delete('user_devision',array('user_id'=>$user_id));
				foreach($tags_division as $row){
					$id = $this->get_division_id_by_name($row);
					if($id){
						$division_data = array(
							'devision_id' => $id,
							'user_id' => $user_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_devision',$division_data);
					}
					
				}
			}
		} else {
			$this->db->delete('user_devision',array('user_id'=>$user_id));
		}
		/**
                 * check departments
                 */
		if($this->input->post('tags_department')){
			$tags_department = explode(',', $this->input->post('tags_department'));
			if($tags_department){
				$this->db->delete('user_department',array('user_id'=>$user_id));
				foreach($tags_department as $row){
					$id = $this->get_department_id_by_name($row);
					if($id){
						$department_data = array(
							'dept_id' => $id,
							'user_id' => $user_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_department',$department_data);
					}
					
				}
			}
		} else {
			$this->db->delete('user_department',array('user_id'=>$user_id));
		}
		
		if($this->input->post('tags_skills')){
			$tags_skills = explode(',', $this->input->post('tags_skills'));
			if($tags_skills){
				$this->db->delete('user_skills',array('user_id'=>$user_id));
				foreach($tags_skills as $row){
					$id = $this->get_skill_id_by_name($row);
					if($id){
						$skill_data = array(
							'skill_id' => $id,
							'user_id' => $user_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_skills',$skill_data);
					}
					
				}
			}
		} else {
			$this->db->delete('user_skills',array('user_id'=>$user_id));
		}
		/**
                 * check user_status and is_manager
                 */
		if($this->input->post('user_status')){ $status = 'Active'; } else { $status = 'Inactive'; }
		if($this->input->post('is_manager')){ $is_manager_val = "1"; } else { $is_manager_val = "0"; }
		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email'),
			'user_time_zone' => $this->input->post('user_time_zone'),
			'staff_level' => $this->input->post('staff_level'),
			'is_administrator' => $this->input->post('is_administrator'),
			'is_owner' => $this->input->post('is_owner'),
			'is_manager' => $is_manager_val,
			'user_status' => $status,
			'company_id' => $this->session->userdata('company_id')
		);
		$this->db->where('user_id',$user_id);
		$this->db->update('users',$data);
		/**
                 * check authentication and set session data
                 */
		if($user_id == get_authenticateUserID()){
			$this->session->set_userdata("User_timezone",get_UserTimeZone());
			$this->session->set_userdata("is_administrator",$this->input->post('is_administrator'));
			$this->session->set_userdata("is_manager",$is_manager_val);
		}
		//$this->session->set_userdata("User_timeoffset",get_TimezoneOffset(get_UserTimeZone()));
		/**
                 * update calender setting
                 */
		$calender_data = array(
			'MON_hours' => $this->input->post('MON_hours_min'),
			'TUE_hours' => $this->input->post('TUE_hours_min'),
			'WED_hours' => $this->input->post('WED_hours_min'),
			'THU_hours' => $this->input->post('THU_hours_min'),
			'FRI_hours' => $this->input->post('FRI_hours_min'),
			'SAT_hours' => $this->input->post('SAT_hours_min'),
			'SUN_hours' => $this->input->post('SUN_hours_min'),
			'MON_closed' => $this->input->post('MON_closed'),
			'TUE_closed' => $this->input->post('TUE_closed'),
			'WED_closed' => $this->input->post('WED_closed'),
			'THU_closed' => $this->input->post('THU_closed'),
			'FRI_closed' => $this->input->post('FRI_closed'),
			'SAT_closed' => $this->input->post('SAT_closed'),
			'SUN_closed' => $this->input->post('SUN_closed')
		);
		$this->db->where('user_id',$user_id);
		$this->db->update('default_calendar_setting',$calender_data);
		
		return $user_id;
	}
        /**
         * This function is used for save new user in db.
         * @returns int
         */
	function insert_user(){
		//pr($_POST);die;
		/**
                 * check user status and  manager
                 */
		if($this->input->post('user_status')){ $status = 'Active'; } else { $status = 'Inactive'; }
		if($this->input->post('is_manager')){ $is_manager_val = "1"; } else { $is_manager_val = "0"; }
                if($this->input->post('staff_level')){ $staff_level = $this->input->post('staff_level');} else { $staff_level = " ";}
                if($this->input->post('is_administrator')){$is_administrator = $this->input->post('is_administrator');} else { $is_administrator=" ";}
		$no_of_company_by_user = check_user_avaibility_by_email($this->input->post('email'));
		
		if($no_of_company_by_user < 1){
			$randomcode = randomCode();
			$password = md5($randomcode);
		}else{
			$password = get_user_password($this->input->post('email'));
                }
		
		
		$code = randomCode();
		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email'),
			'user_time_zone' => $this->input->post('user_time_zone'),
			//'user_time_zone' => $this->session->userdata('User_timezone'),
			'staff_level' => $staff_level,
			'is_administrator' => $is_administrator,
			'is_owner' => $this->input->post('is_owner'),
			'is_manager' => $is_manager_val,
			'user_status' => $status,
			'company_id' => $this->session->userdata('company_id'),
			'password' => $password,
			'email_verification_code' => $code,
			'signup_date' => date('Y-m-d H:i:s'),
			'signup_IP' => $_SERVER['REMOTE_ADDR'],
			'user_default_page' =>'weekly_calendar',
                        'is_first_login'=>'1'
		);
		$this->db->insert('users',$data);
		$user_id = $this->db->insert_id();
		
		if($user_id == get_authenticateUserID()){
			$this->session->set_userdata("User_timezone",get_UserTimeZone());
		}
		//$this->session->set_userdata("User_timeoffset",get_TimezoneOffset(get_UserTimeZone()));
		
		$swimlane_data = array(
			'user_id' => $user_id,
			'swimlanes_name' => 'default',
			'swimlanes_desc' => 'default',
			'seq' => '1',
                        'is_default'=>'1',
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('swimlanes',$swimlane_data);
		/**
                 * insert data in last_remember_search
                 */
		$last_remember_data = array(
			'user_id' => $user_id,
			'sidbar_collapsed'=>'0',
			'kanban_project_id' => 'all',
			'calender_project_id' => 'all',
			'task_status_id' => 'all',
			'due_task' => 'all',
			'kanban_team_user_id' => $user_id,
			'calender_team_user_id' =>$user_id,
			'show_cal_view' => '1',
			'calender_sorting' => '1',
			'last_calender_view' => '1',
			'user_color_id'=>'0'
		);
		$this->db->insert('last_remember_search',$last_remember_data);
		/**
                 * insert in user_manger
                 */
		if($this->input->post('manager_multiselect_to')){
			$managers = $this->input->post('manager_multiselect_to');
			if($managers){
				
				foreach($managers as $row){
					$chk = $this->is_manager_exist($user_id,$row);
					if($chk == "0" && $user_id != $row){
						$data = array(
							'user_id' => $user_id,
							'manager_id' => $row,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_managers',$data);
						
					}
				}
			}
		}
		
		
		if($this->input->post('tags_division')){
			$tags_division = explode(',', $this->input->post('tags_division'));
			
			if(isset($tags_division) && $tags_division!=''){
				$this->db->delete('user_devision',array('user_id'=>$user_id));
				foreach($tags_division as $row){
					$id = $this->get_division_id_by_name($row);
					if($id){
						$division_data = array(
							'devision_id' => $id,
							'user_id' => $user_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_devision',$division_data);
					}
					
				}
			}
		}
		/**
                 * insert in department
                 */
		if($this->input->post('tags_department')){
			$tags_department = explode(',', $this->input->post('tags_department'));
			if($tags_department){
				$this->db->delete('user_department',array('user_id'=>$user_id));
				foreach($tags_department as $row){
					$id = $this->get_department_id_by_name($row);
					if($id){
						$department_data = array(
							'dept_id' => $id,
							'user_id' => $user_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_department',$department_data);
					}
					
				}
			}
		}
		
		if($this->input->post('tags_skills')){
			$tags_skills = explode(',', $this->input->post('tags_skills'));
			if($tags_skills){
				$this->db->delete('user_skills',array('user_id'=>$user_id));
				foreach($tags_skills as $row){
					$id = $this->get_skill_id_by_name($row);
					if($id){
						$skill_data = array(
							'skill_id' => $id,
							'user_id' => $user_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('user_skills',$skill_data);
					}
					
				}
			}
		}
		/*This code is added for check post method value on form submission */
		if($this->input->post('MON_hours_min')){$MON_hours = $this->input->post('MON_hours_min') ;} else { $MON_hours=" ";}
                if($this->input->post('TUE_hours_min')){$TUE_hours = $this->input->post('TUE_hours_min') ;} else { $TUE_hours=" ";}
                if($this->input->post('WED_hours_min')){$WED_hours = $this->input->post('WED_hours_min') ;} else { $WED_hours=" ";}
                if($this->input->post('THU_hours_min')){$THU_hours = $this->input->post('THU_hours_min') ;} else { $THU_hours=" ";}
                if($this->input->post('FRI_hours_min')){$FRI_hours = $this->input->post('FRI_hours_min') ;} else { $FRI_hours=" ";}
                if($this->input->post('SAT_hours_min')){$SAT_hours = $this->input->post('SAT_hours_min') ;} else { $SAT_hours=" ";}
                if($this->input->post('SUN_hours_min')){$SUN_hours = $this->input->post('SUN_hours_min') ;} else { $SUN_hours=" ";}
                if($this->input->post('MON_closed')){$MON_closed = $this->input->post('MON_closed') ;} else { $MON_closed=" ";}
                if($this->input->post('TUE_closed')){$TUE_closed = $this->input->post('TUE_closed') ;} else { $TUE_closed=" ";}
                if($this->input->post('WED_closed')){$WED_closed = $this->input->post('WED_closed') ;} else { $WED_closed=" ";}
                if($this->input->post('THU_closed')){$THU_closed = $this->input->post('THU_closed') ;} else { $THU_closed=" ";}
                if($this->input->post('FRI_closed')){$FRI_closed = $this->input->post('FRI_closed') ;} else { $FRI_closed=" ";}
                if($this->input->post('SAT_closed')){$SAT_closed = $this->input->post('SAT_closed') ;} else { $SAT_closed=" ";}
                if($this->input->post('SUN_closed')){$SUN_closed = $this->input->post('SUN_closed') ;} else { $SUN_closed=" ";}
		$calender_data = array(
			'user_id' => $user_id,
			'MON_hours' => $MON_hours,
			'TUE_hours' => $TUE_hours,
			'WED_hours' => $WED_hours,
			'THU_hours' => $THU_hours,
			'FRI_hours' => $FRI_hours,
			'SAT_hours' => $SAT_hours,
			'SUN_hours' => $SUN_hours,
			'MON_closed' => $MON_closed,
			'TUE_closed' => $TUE_closed,
			'WED_closed' => $WED_closed,
			'THU_closed' => $THU_closed,
			'FRI_closed' => $FRI_closed,
			'SAT_closed' => $SAT_closed,
			'SUN_closed' => $SUN_closed
		);
		$this->db->insert('default_calendar_setting',$calender_data);
		
		$colors = get_colors();
		if($colors){
			$i = 1;
			foreach($colors as $col){
				$color_data = array(
					'color_id' => $col->color_id,
					'user_id' => $user_id,
					'color_name' => $col->color_name,
					'name' => $col->color_name,
					'color_code' => $col->color_code,
					'outside_color_code' => $col->outside_color_code,
					'seq' => $i,
					'status' => 'Active',
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('user_colors',$color_data);
				$i++;
			}
		}
		
                $this->db->select('swimlanes_id');
                $this->db->from('swimlanes');
                $this->db->where('user_id',$user_id);
                $this->db->where('is_deleted','0');
                $query = $this->db->get();
                $default_swimlanes_id = $query->row()->swimlanes_id;
                
                $json_path = base_url().'default/json/By_default_task.json';
		$file = file_get_contents($json_path);
                $default_task = json_decode($file);
                $task_status = get_taskStatus($this->session->userdata('company_id'),'Active');   
                $admin_color = get_colors_admin($user_id);
                $default_steps = array(
                                "1" => "Tick the box to complete the step",
                                "2" => "Step 2",
                                "3" => "Step 3"
                );
                $i = 1;
                foreach ($default_task as $task){
                    
                    foreach ($task_status as $status){
                            if($task->task_status == $status->task_status_name){
                                $task->task_status_id =$status->task_status_id;
                            }
                    }
                    
                    $monday= date("Y-m-d",strtotime('monday this week'));
                    $tuesday= date("Y-m-d",strtotime($monday . "+1 days"));
                    $wednesday = date("Y-m-d",strtotime($monday . "+2 days"));
                    if($task->task_due_date == 'Monday'){
                        $task->task_due_date = $monday;
                        $task->task_schedule_date = $monday;
                    }elseif($task->task_due_date == 'Tuesday'){
                        $task->task_due_date = $tuesday;
                        $task->task_schedule_date = $tuesday;
                    }else{
                        $task->task_due_date = $wednesday;
                        $task->task_schedule_date = $wednesday;
                    }
                    $data = array(
				'task_company_id' => $this->session->userdata('company_id'),
				'master_task_id' => '0',
				'task_title' => $task->task_title,
                                'task_description' => $task->task_description,
				'task_priority' => $task->task_priority,
				'task_due_date' => $task->task_due_date,
				'task_scheduled_date' => $task->task_schedule_date,
				'task_orig_scheduled_date' => '0000-00-00',
				'task_orig_due_date' => '0000-00-00',
				'task_owner_id' => $user_id,
				'task_allocated_user_id' => $user_id,
				'task_status_id' => $task->task_status_id,
				'subsection_id' => '0',
				'section_id' => '0',
				'task_project_id' => '0',
                                'task_time_estimate'=>$task->task_estimate_time,
                                'task_time_spent' => $task->task_spent_time,
				'task_added_date' => date('Y-m-d H:i:s'),
                                );
						
		    $this->db->insert('tasks',$data);
                    $task_id = $this->db->insert_id();
                    foreach($admin_color as $color){
                        if($task->task_color == $color->name){
                            $task->task_color = $color->user_color_id;
                         }
                     }
                     
                    $data1 = array(
                                    "user_id"=>$user_id,
                                    "task_id"=>$task_id,
                                    "swimlane_id"=>$default_swimlanes_id,
                                    "color_id"=>$task->task_color,
                                    "calender_order"=> $i,
                                    "kanban_order"=>$i,
                                    "task_ex_pos"=>'1'
                                    
                    );
                    $this->db->insert('user_task_swimlanes',$data1); 
                    
                    if($task->steps == 'TRUE'){
                        foreach($default_steps as $key => $value){
						$step_data = array(
							'task_id' => $task_id,
							'step_title' => $value,
							'step_added_by' => $user_id,
							'is_completed' => '0',
							'step_sequence' => $key,
							'step_added_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_steps',$step_data);
                    }
                    }
                    if($task->comment == 'TRUE'){
                        $data2 = array(
                                    'task_comment' => "First comment",
                                    'task_id' => $task_id,
                                    'project_id' => '0',
                                    'comment_addeby' => $user_id,
                                    'comment_added_date' => date('Y-m-d H:i:s')
                                    );

                        $this->db->insert('task_and_project_comments',$data2);
                    }
                    
                    
                    $i++;
                }
                
                
		
		
		//hard delete for soft deleted user again register
		$query = $this->db->query("Delete From ".$this->db->dbprefix('users')." where email= '".$this->input->post('email')."' and is_deleted = 1 ");
		
		if($no_of_company_by_user < 1){
		//email
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='Add New User By Admin'");
			$email_temp=$email_template->row();	
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;
			
			$email_subject=$email_temp->subject;				
			$email_message=$email_temp->message;
			
			$data_pass = base64_encode($user_id."1@1".$code."1@1NewUserByAdmin");
			
			$activation_link = "<a href='".base_url()."home/activation/".$data_pass."' target='_blank'>Activation link</a>";
			
			$user_name = $this->input->post('first_name').' '.$this->input->post('last_name');
			
			$email_to = $this->input->post('email');
			$subscription_link = site_url();
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{email}',$email_to,$email_message);
			$email_message=str_replace('{password}',$randomcode,$email_message);
			$email_message=str_replace('{activation_link}',$activation_link,$email_message);
                        
			$company_name = getCompanyName($this->session->userdata('company_id'));
			
			$str=$email_message;
                        $sandgrid_id=$email_temp->sandgrid_id;
                        $sendgriddata = array('subject'=>'Add New User By Admin',
                                    'data'=>array('user_name'=>$user_name,'company_name'=>$company_name,'activation_link'=>$activation_link,"email"=>$email_to));
                        if($sandgrid_id){
                            $str = json_encode($sendgriddata);
                        }
                        /**
                         * Adding mail in mail queue for sending through cronjob.
                         */
                        
                        $mail_data = array(
                                      "email_to"=>$email_to,
                                      "email_from"=>$email_address_from,
                                      "email_reply"=>$email_address_reply,
                                      "email_subject"=>$email_subject,
                                      "message"=>$str,
                                      "attach"=>'',
                                      "status"=>'pending',
                                      "date"=>date('Y-m-d H:i:s'),
                                      "sandgrid_id"=>$sandgrid_id
                                      );
                        $this->db->insert('email_queue',$mail_data);
                
                }else{
					
				$email_template = $this->db->query("select * from " . $this->db->dbprefix('email_template') . " where task='Add User To New Company'");
				$email_temp = $email_template->row();
		
				$email_address_from = $email_temp->from_address;
				$email_address_reply = $email_temp->reply_address;
		
				$email_subject = $email_temp->subject;
				$email_message = $email_temp->message;
		
				$email = $this->input->post('email');
		
				$user_name = $this->input->post('first_name').' '.$this->input->post('last_name');
				$company_name = getCompanyName($this->session->userdata('company_id'));
				$email_to = $email;
		
				$email_message = str_replace('{break}', '<br/>', $email_message);
				$email_message = str_replace('{user_name}', $user_name, $email_message);
				$email_message = str_replace('{company_name}', $company_name, $email_message);
				//$email_message = str_replace('{sore_name}', $store_name, $email_message);
				$str = $email_message;
                                $data_pass = base64_encode($user_id."1@1".$code);

                                $activation_link = "<a href='".base_url()."home/activation_email/".$data_pass."' target='_blank'>Activation link</a>";
                                
				//echo $str;die;
                                $sandgrid_id=$email_temp->sandgrid_id;
                                $sendgriddata = array('subject'=>'Add User To New Company',
                                    'data'=>array('user_name'=>$user_name,'company_name'=>$company_name,'activation_link'=>$activation_link,'email'=>$email));
                                if($sandgrid_id)
                                {
                                    $str = json_encode($sendgriddata);
                                }
                                /**
                                  * Adding mail in mail queue for sending through cronjob.
                                  */
                                $mail_data = array(
                                      "email_to"=>$email_to,
                                      "email_from"=>$email_address_from,
                                      "email_reply"=>$email_address_reply,
                                      "email_subject"=>$email_subject,
                                      "message"=>$str,
                                      "attach"=>'',
                                      "status"=>'pending',
                                      "date"=>date('Y-m-d H:i:s'),
                                      "sandgrid_id"=>$sandgrid_id
                                      );
                                $this->db->insert('email_queue',$mail_data);
			}
		
		return $user_id;
	}

	function getAllocationByType($type)
	{
		//echo $type;
		$today = date('Y-m-d');
		$task_status_completed_id = $this->config->item('completed_id');
		$task_list = array();
		$this->db->select('t.*,t.task_priority,t.task_category_id,t.task_project_id');
		$this->db->from('tasks t');
		//$this->db->order_by()
		//$query = $CI->db->query("SELECT  * FROM `tasks` WHERE `task_status_id` <> '".$task_status_completed_id."' AND master_task_id  = 0 AND task_company_id = ".$CI->session->userdata('company_id')." AND is_deleted ='0' ");
		
		if($type=="priority"){
			$this->db->where("t.task_priority <> ","NULL");
			//$this->db->group_by('t.task_priority');
		}
		if($type=="category"){
			$this->db->join('task_category tc','t.task_category_id = tc.category_id','left');
			$this->db->where('t.task_category_id <>','0');
			//$this->db->group_by('t.task_category_id');
		}
		if($type=="project"){
			$this->db->join('project p','t.task_project_id = p.project_id');
			//$this->db->group_by('t.task_project_id');
		}
		
		//$this->db->where('task_scheduled_date',$today);
		//$this->db->where('(`task_owner_id` = "'.get_authenticateUserID().'" OR `task_allocated_user_id` = "'.get_authenticateUserID().'" )');
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_deleted','0');
		$this->db->where('t.master_task_id','0');
		$this->db->where('t.task_status_id <>',$task_status_completed_id);
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		
		$query = $this->db->get();
		
		/*SELECT `t`.*, `t`.`task_priority`, `t`.`task_category_id`, `t`.`task_project_id` FROM (`tasks` t) WHERE `t`.`task_priority` <> 'NULL' AND `t`.`is_deleted` = '0' AND `t`.`master_task_id` = '0' AND `t`.`task_status_id` <> '4' AND `t`.`task_company_id` = '20'
		SELECT `t`.*, `t`.`task_priority`, `t`.`task_category_id`, `t`.`task_project_id` FROM (`tasks` t) JOIN `project` p ON `t`.`task_project_id` = `p`.`project_id` WHERE `t`.`is_deleted` = '0' AND `t`.`master_task_id` = '0' AND `t`.`task_status_id` <> '4' AND `t`.`task_company_id` = '20'
		SELECT `t`.*, `t`.`task_priority`, `t`.`task_category_id`, `t`.`task_project_id` FROM (`tasks` t) LEFT JOIN `task_category` tc ON `t`.`task_category_id` = `tc`.`category_id` WHERE `t`.`task_category_id` <> '0' AND `t`.`is_deleted` = '0' AND `t`.`master_task_id` = '0' AND `t`.`task_status_id` <> '4' AND `t`.`task_company_id` = '20'*/
		
		
		
		
		//echo $this->db->last_query();
		if($query->num_rows()>0){
			$res = $query->result();
			//pr($res);die;
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						//pr($row_pass);
						$re_data = monthly_recurrence_logic($row_pass,$today,$today);
						//pr($re_data);
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date'] == $today  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
							//&& $row->task_priority == $type 
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			
			//$task_list = (object)$task_list;
			//pr($task_list);die;
			//return $task_list;
			
			$task_time = 0;
			foreach ($task_list as $key) {
				//echo $key['task_time_estimate']."====";
				$task_time = $task_time + $key['task_time_estimate'];
			}
			$task_time = (($task_time)/60);
			//echo $task_time;
			//$task_list = (object)$task_list;
			//pr($task_list);
			//date_default_timezone_set($CI->session->userdata("User_timezone"));
			return $task_time;
			
		} else {
			return 0;
		}
		
		
		
		//if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`='".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`='".$today."' and `task_due_date` <>'0000-00-00')
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function getTeamTimeByPeriod($period)
	{
		$today = date('Y-m-d');
		$this->db->select('(sum(t.task_time_spent)/60) as tasktime,t.task_priority,t.task_category_id,t.task_project_id');
		$this->db->from('tasks t');
		$this->db->join('task_category tc','t.task_category_id = tc.category_id','left');
		
		if($period=="this_week"){
			
			
			$d = strtotime("today");
			$start_week = strtotime("last sunday midnight",$d);
			$end_week = strtotime("next saturday",$d);
			$start = date("Y-m-d",$start_week); 
			$end = date("Y-m-d",$end_week);
			
			$this->db->where("task_scheduled_date >= '".$start."' and  task_scheduled_date <= '".$end."'");
			//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."')
		}
		if($period=="next_week"){
			
			$d1 = strtotime("+1 week -1 day");
			$start_week = strtotime("last sunday midnight",$d1);
			$end_week = strtotime("next saturday",$d1);
			$start = date("Y-m-d",$start_week); 
			$end = date("Y-m-d",$end_week); 
					
			$this->db->where("task_scheduled_date >= '".$start."' and  task_scheduled_date <= '".$end."'");			
			//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."')
		}
		if($period=="this_month"){
			
			$start = date("Y-m-01");
			$end = date("Y-m-t");
			
			$this->db->where("task_scheduled_date >= '".$start."' and  task_scheduled_date <= '".$end."'");		
			//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."')
				
		}
		
		if($period=="next_month"){
			
			$start_date = date('Y-m-01', strtotime('+1 month'));
			$end_date = date('Y-m-t', strtotime('+1 month'));
			
			$start = date("Y-m-01",strtotime($start_date));
			$end = date("Y-m-t",strtotime($end_date));
			
			$this->db->where("task_scheduled_date >= '".$start."' and task_scheduled_date <= '".$end."'");	
			//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."')
					
		}
		
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.task_category_id <>','0');
		$this->db->where('t.is_deleted','0');
		$this->db->group_by('t.task_category_id');
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
		
	}

	

	function getNonEstTask($type)
	{
		$today = date('Y-m-d');
		$this->db->select('t.task_priority,t.task_category_id,t.task_project_id');
		$this->db->from('tasks t');
		//$this->db->order_by()
		
		if($type=="priority"){
			$this->db->where("t.task_priority <> "," ");
			$this->db->group_by('t.task_priority');
		}
		if($type=="category"){
			$this->db->join('task_category tc','t.task_category_id = tc.category_id','left');
			$this->db->where('t.task_category_id <>','0');
			$this->db->group_by('t.task_category_id');
		}
		if($type=="project"){
			$this->db->join('project p','t.task_project_id = p.project_id');
			$this->db->group_by('t.task_project_id');
		}
		
		$this->db->where("task_scheduled_date",$today);
		
		//if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`='".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`='".$today."' and `task_due_date` <>'0000-00-00')
		$this->db->where('(task_owner_id = "'.get_authenticateUserID().'" OR task_allocated_user_id = "'.get_authenticateUserID().'" )');
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_deleted','0');
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function get_user_list($company_id){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('company_id',$company_id);
                $this->db->where('is_customer_user','0');
		$this->db->where('is_deleted','0');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	/**
         * It returns division list of user.
         * @param int $user_id
         * @returns int
         */
	function get_division_list($user_id){
		$this->db->select('cd.*,ud.user_devision_id');
		$this->db->from('user_devision ud');
		$this->db->join('company_divisions cd','cd.division_id = ud.devision_id');
		$this->db->where('cd.devision_status','Active');
		$this->db->where('cd.is_delete','0');
		$this->db->where('ud.user_id',$user_id);
		$this->db->where('cd.is_delete','0');
		$this->db->where('cd.devision_status','Active');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	/**
         * Returns department list of user
         * @param int $user_id
         * @returns string
         */
	function get_department_list($user_id){
		$this->db->select('cd.*,ud.user_dept_id');
		$this->db->from('user_department ud');
		$this->db->join('company_departments cd','cd.department_id = ud.dept_id');
		$this->db->where('ud.user_id',$user_id);
		$this->db->where('cd.status','Active');
		$this->db->where('cd.is_deleted','0');

		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return '';
		}
	}
	/**
         * Return company department list
         * @param int $id
         * @returns int
         */
	function get_company_department_list($id){
		//$this->db->like('department_title',$q);
		$this->db->select('department_id,department_title');
		$this->db->from('company_departments');
		$this->db->where_in('deivision_id',$id);
		$this->db->where('status','Active');
		$this->db->where('is_deleted','0');
		$this->db->where('company_id',$this->session->userdata('company_id'));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return 0;
	}
	/**
         * Return user skills list
         * @param int $user_id
         * @returns int
         */
	function get_skills_list($user_id){
		$this->db->select('s.*,us.user_skill_id');
		$this->db->from('user_skills us');
		$this->db->join('skills s','s.skill_id = us.skill_id');
		$this->db->where('s.skill_status','Active');
		$this->db->where('us.user_id',$user_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return 0;
		}
	}
	/**
         * This function will return manager details
         * @param int $company_id
         * @returns int
         */
	function get_managers($company_id){
		$this->db->select('first_name,last_name,user_id');
		$this->db->from('users');
		$this->db->where('company_id',$company_id);
		$this->db->where('user_status','Active');
		$this->db->where('is_deleted','0');
		$this->db->where('(is_manager = "1" or is_administrator = "1")');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	function get_user_managers($user_id){
		$this->db->select('um.manager_id,u.first_name,u.last_name');
		$this->db->from('user_managers um');
		$this->db->join('users u','u.user_id = um.manager_id');
		$this->db->where('um.user_id',$user_id);
		$this->db->where('u.is_deleted','0');
		$this->db->where('u.user_status','Active');
		
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	function get_user_skill($user_id){
		$this->db->select('s.skill_title');
		$this->db->from('user_skills us');
		$this->db->join('skills s','s.skill_id = us.skill_id');
		$this->db->where('us.user_id',$user_id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			$array_text ='';
			foreach($res as $arr){
				$array_text .= $arr['skill_title'].',';
			}
			return substr($array_text, 0,-1);
		} else {
			return '';
		}
	}
	
	function update_my_settings($user_id){
		
		if($this->input->post('tags_division')){
			$tags_division = explode(',', $this->input->post('tags_division'));
			if($tags_division){
				$this->db->delete('user_devision',array('user_id'=>$user_id));
				foreach($tags_division as $row){
					$id = $this->get_division_id_by_name($row);
					//echo $this->db->last_query();die;
					$division_data = array(
						'devision_id' => $id,
						'user_id' => $user_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('user_devision',$division_data);
				}
			}
		}
		
		if($this->input->post('tags_department')){
			$tags_department = explode(',', $this->input->post('tags_department'));
			if($tags_department){
				$this->db->delete('user_department',array('user_id'=>$user_id));
				foreach($tags_department as $row){
					$id = $this->get_department_id_by_name($row);
					$department_data = array(
						'dept_id' => $id,
						'user_id' => $user_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('user_department',$department_data);
				}
			}
		}
		
		$msg = '';
		$profile_image='';
		$s3_profile_image = '';
		if($_FILES['profile_image']['name']!='')
        {
     		$this->load->library('upload');
         	$rand=rand(0,100000); 
			  
	         $_FILES['userfile']['name']     =   $_FILES['profile_image']['name'];
	         $_FILES['userfile']['type']     =   $_FILES['profile_image']['type'];
	         $_FILES['userfile']['tmp_name'] =   $_FILES['profile_image']['tmp_name'];
	         $_FILES['userfile']['error']    =   $_FILES['profile_image']['error'];
	         $_FILES['userfile']['size']     =   $_FILES['profile_image']['size'];
	   
			$config['file_name'] = 'user'.$rand;
			
            $config['upload_path'] = base_path().'upload/user_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 
            $this->upload->initialize($config);
 
	          if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();   
			  } 
			
			 $picture = $this->upload->data();
		   	
             $this->load->library('image_lib');
		   
             $this->image_lib->clear();
		   	
			$gd_var='gd2';
				
				
		   if ($_FILES["profile_image"]["type"]!= "image/png" and $_FILES["profile_image"]["type"] != "image/x-png") {		  
			   	$gd_var='gd2';			
			}
			
					
		   if ($_FILES["profile_image"]["type"] != "image/gif") {		   
		    	$gd_var='gd2';
		   }	   
		   
		   if ($_FILES["profile_image"]["type"] != "image/jpeg" and $_FILES["profile_image"]["type"] != "image/pjpeg" ) {		   
		    	$gd_var='gd2';
		   }
		   
		   $this->image_lib->clear();
			
			 $this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/user_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/user/'.$picture['file_name'],
				'maintain_ratio' => FALSE,
				'quality' => '100%',
				'width' => 300,
				'height' => 300
			 ));
			
			
			if(!$this->image_lib->resize())
			{
				$error = $this->image_lib->display_errors();
			}
			
			$new_image = $this->image_lib->new_image;
			 
		
			$bucket = $this->config->item('bucket_name');
		
          	$name = $_FILES['profile_image']['name'];
			$size = $_FILES['profile_image']['size'];
			$tmp = $_FILES['profile_image']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			if(in_array($ext,$valid_formats)){
				
				
				$s3_profile_image = 'user'.$rand.'.'.$ext;
			    $actual_image_name = "upload/user_orig/".$s3_profile_image;
				$new_actual_image_name = "upload/user/".$s3_profile_image;
	 			
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, S3::ACL_PUBLIC_READ)){
						if(file_exists(base_path().'upload/user/'.$picture['file_name']))
						{
							$link=base_path().'upload/user/'.$picture['file_name'];
							unlink($link);
						}
					}
					if(file_exists(base_path().'upload/user_orig/'.$picture['file_name']))
					{
						$link=base_path().'upload/user_orig/'.$picture['file_name'];
						unlink($link);
					}
					if($this->input->post('hdn_profile_image')!='')
					{
						$delete_image_name = "upload/user_orig/".$this->input->post('hdn_profile_image');
						$delete_image_name1 = 'upload/user/'.$this->input->post('hdn_profile_image');
						
						$this->s3->deleteObject($bucket,$delete_image_name);
						$this->s3->deleteObject($bucket,$delete_image_name1);
					}
					$msg = "success";
				} else {
					$msg = "fail";
	
				}
			} else {
				$msg = "invalid";
			}
		} else {
			if($this->input->post('hdn_profile_image')!='')
			{
				$s3_profile_image=$this->input->post('hdn_profile_image');
			}
		}
		
		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email'),
			'profile_image' => $s3_profile_image,
			'user_time_zone' => $this->input->post('user_time_zone'),
			'user_default_page' => $this->input->post('user_default_page')
		);
		
		$this->db->where('user_id',$user_id);
		$this->db->update('users',$data);
		
		$this->session->set_userdata("User_timezone",get_UserTimeZone());
		//$this->session->set_userdata("User_timeoffset",get_TimezoneOffset(get_UserTimeZone()));
		
		if($this->input->post('pre_email') != $this->input->post('email')){
			$code = randomCode();
			$data = array('verify_email'=>0,'user_status'=>'Inactive','email_verification_code'=>$code);
			$this->db->where('user_id',$user_id);
			$this->db->update('users',$data);
			
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='verify email'");
			$email_temp=$email_template->row();	
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;
			
			$email_subject=$email_temp->subject;				
			$email_message=$email_temp->message;
			
			$data_pass = base64_encode($user_id."1@1".$code);
			
			$activation_link = "<a href='".base_url()."home/activation/".$data_pass."' target='_blank'>Activation link</a>";
			
			$user_name = $this->input->post('first_name').' '.$this->input->post('last_name');
			
			$email_to = $this->input->post('email');
			
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{activation_link}',$activation_link,$email_message);		
			
			
			$str=$email_message;
			//echo $str;die;
                        $sandgrid_id=$email_temp->sandgrid_id;
                        $sendgriddata = array('subject'=>'verify email',
                            'data'=>array('activation_link'=>$activation_link));
                        if($sandgrid_id)
                        {
                            mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,'',$email_subject,$sandgrid_id,$sendgriddata);
                        }else{
                            email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                        }
			
			return 'sent';
		}
		
		return $user_id;
	}

	function update_profile($user_id)
	{
		$profile_image = '';
		$s3_profile_image = '';
		if($_FILES['profile_image']['name']!='')
        {
     		$this->load->library('upload');
         	$rand=rand(0,100000); 
			  
	         $_FILES['userfile']['name']     =   $_FILES['profile_image']['name'];
	         $_FILES['userfile']['type']     =   $_FILES['profile_image']['type'];
	         $_FILES['userfile']['tmp_name'] =   $_FILES['profile_image']['tmp_name'];
	         $_FILES['userfile']['error']    =   $_FILES['profile_image']['error'];
	         $_FILES['userfile']['size']     =   $_FILES['profile_image']['size'];
	   
			$config['file_name'] = 'user'.$rand;
			
            $config['upload_path'] = base_path().'upload/user_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 
            $this->upload->initialize($config);
 
	          if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();   
			  } 
			
			 $picture = $this->upload->data();
		   	
             $this->load->library('image_lib');
		   
             $this->image_lib->clear();
		   	
			$gd_var='gd2';
				
				
		   if ($_FILES["profile_image"]["type"]!= "image/png" and $_FILES["profile_image"]["type"] != "image/x-png") {		  
			   	$gd_var='gd2';			
			}
			
					
		   if ($_FILES["profile_image"]["type"] != "image/gif") {		   
		    	$gd_var='gd2';
		   }	   
		   
		   if ($_FILES["profile_image"]["type"] != "image/jpeg" and $_FILES["profile_image"]["type"] != "image/pjpeg" ) {		   
		    	$gd_var='gd2';
		   }
		   
		   $this->image_lib->clear();
			
			 $this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/user_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/user/'.$picture['file_name'],
				'maintain_ratio' => FALSE,
				'quality' => '100%',
				'width' => 300,
				'height' => 300
			 ));
			
			
			if(!$this->image_lib->resize())
			{
				$error = $this->image_lib->display_errors();
			}
			
			$new_image = $this->image_lib->new_image;
			 
		
			$bucket = $this->config->item('bucket_name');
		
          	$name = $_FILES['profile_image']['name'];
			$size = $_FILES['profile_image']['size'];
			$tmp = $_FILES['profile_image']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			
			$profile_image=$picture['file_name'];
			
		
			/*if($this->input->post('hdn_profile_image')!='')
			{
				if(file_exists(base_path().'upload/user_orig/'.$this->input->post('hdn_profile_image')))
				{
					$link=base_path().'upload/user_orig/'.$this->input->post('hdn_profile_image');
					unlink($link);
				}
				
				if(file_exists(base_path().'upload/user/'.$this->input->post('hdn_profile_image')))
				{
					$link2=base_path().'upload/user/'.$this->input->post('hdn_profile_image');
					unlink($link2);
				}
				
			}*/
			if(in_array($ext,$valid_formats)){
				
				
				$s3_profile_image = 'user'.$rand.'.'.$ext;
			    $actual_image_name = "upload/user_orig/".$s3_profile_image;
				$new_actual_image_name = "upload/user/".$s3_profile_image;
	 			
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, S3::ACL_PUBLIC_READ)){
						if(file_exists(base_path().'upload/user/'.$picture['file_name']))
						{
							$link=base_path().'upload/user/'.$picture['file_name'];
							unlink($link);
						}
					}
					if(file_exists(base_path().'upload/user_orig/'.$picture['file_name']))
					{
						$link=base_path().'upload/user_orig/'.$picture['file_name'];
						unlink($link);
					}
					if($this->input->post('hdn_profile_image')!='')
					{
						$delete_image_name = "upload/user_orig/".$this->input->post('hdn_profile_image');
						$delete_image_name1 = 'upload/user/'.$this->input->post('hdn_profile_image');
						
						$this->s3->deleteObject($bucket,$delete_image_name);
						$this->s3->deleteObject($bucket,$delete_image_name1);
					}
					$msg = "success";
				} else {
					$msg = "fail";
	
				}
			} else {
				$msg = "invalid";
			}
		} else {
			if($this->input->post('hdn_profile_image')!='')
			{
				$profile_image=$this->input->post('hdn_profile_image');
			}
		}
		
		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email'),
			'profile_image' => $profile_image
		);
		
		$this->db->where('user_id',$user_id);
		$this->db->update('users',$data);
		
		if($this->input->post('pre_email') != $this->input->post('email')){
			$code = randomCode();
			$data = array('verify_email'=>0,'user_status'=>'Inactive','email_verification_code'=>$code);
			$this->db->where('user_id',$user_id);
			$this->db->update('users',$data);
			
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='verify email'");
			$email_temp=$email_template->row();	
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;
			
			$email_subject=$email_temp->subject;				
			$email_message=$email_temp->message;
			
			$data_pass = base64_encode($user_id."1@1".$code);
			
			$activation_link = "<a href='".base_url()."home/activation/".$data_pass."' target='_blank'>Activation link</a>";
			
			$user_name = $this->input->post('first_name').' '.$this->input->post('last_name');
			
			$email_to = $this->input->post('email');
			
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{activation_link}',$activation_link,$email_message);		
			
			
			$str=$email_message;
			//echo $str;die;
                        $sandgrid_id=$email_temp->sandgrid_id;
                        $sendgriddata = array('subject'=>'verify email',
                            'data'=>array('activation_link'=>$activation_link));
                        if($sandgrid_id)
                        {
                            mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,'',$email_subject,$sandgrid_id,$sendgriddata);
                        }else{
                            email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                        }
			
			return 'sent';
		}
		
		return $user_id;
	}

	function update_defualt_calender($user_id){
		$calender_data = array(
			'MON_hours' => $this->input->post('MON_hours_min'),
			'TUE_hours' => $this->input->post('TUE_hours_min'),
			'WED_hours' => $this->input->post('WED_hours_min'),
			'THU_hours' => $this->input->post('THU_hours_min'),
			'FRI_hours' => $this->input->post('FRI_hours_min'),
			'SAT_hours' => $this->input->post('SAT_hours_min'),
			'SUN_hours' => $this->input->post('SUN_hours_min'),
			'MON_closed' => $this->input->post('MON_closed'),
			'TUE_closed' => $this->input->post('TUE_closed'),
			'WED_closed' => $this->input->post('WED_closed'),
			'THU_closed' => $this->input->post('THU_closed'),
			'FRI_closed' => $this->input->post('FRI_closed'),
			'SAT_closed' => $this->input->post('SAT_closed'),
			'SUN_closed' => $this->input->post('SUN_closed')
		);
		$this->db->where('user_id',$user_id);
		$this->db->where('comapny_id','0');
		$this->db->update('default_calendar_setting',$calender_data);
		return $user_id;
	}
	/**
         * This function set calender default setting in db.
         * @param int $user_id
         * @returns int $id
         */
	function insert_defualt_calender($user_id){
		$calender_data = array(
			'user_id' => $user_id,
			'MON_hours' => $this->input->post('MON_hours_min'),
			'TUE_hours' => $this->input->post('TUE_hours_min'),
			'WED_hours' => $this->input->post('WED_hours_min'),
			'THU_hours' => $this->input->post('THU_hours_min'),
			'FRI_hours' => $this->input->post('FRI_hours_min'),
			'SAT_hours' => $this->input->post('SAT_hours_min'),
			'SUN_hours' => $this->input->post('SUN_hours_min'),
			'MON_closed' => $this->input->post('MON_closed'),
			'TUE_closed' => $this->input->post('TUE_closed'),
			'WED_closed' => $this->input->post('WED_closed'),
			'THU_closed' => $this->input->post('THU_closed'),
			'FRI_closed' => $this->input->post('FRI_closed'),
			'SAT_closed' => $this->input->post('SAT_closed'),
			'SUN_closed' => $this->input->post('SUN_closed')
		);
		$this->db->insert('default_calendar_setting',$calender_data);
		return $this->db->insert_id();
	}
	
	function get_last_seq(){
		$this->db->select('MAX(seq) as seq');
		$this->db->from('swimlanes');
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			if($res->seq){
				return $res->seq;
			}else{
				return 1;
			}
			
		} else {
			return 1;
		}
	}

	function insert_swimlane(){
		
		$last_seq = $this->get_last_seq();
		$data = array(
			'swimlanes_name' => $this->input->post('swimlanes_name'),
			'swimlanes_desc' => $this->input->post('swimlanes_desc'),
			'user_id' => $this->session->userdata('user_id'),
			'seq' => $last_seq + 1,
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('swimlanes',$data);
		$id = $this->db->insert_id();
		
		return $id;
	}
	
	function update_swimlane($id){
		$data = array(
			'swimlanes_name' => $this->input->post('swimlanes_name'),
			'swimlanes_desc' => $this->input->post('swimlanes_desc'),
			'user_id' => $this->session->userdata('user_id')
		);
		$this->db->where('swimlanes_id',$id);
		$this->db->update('swimlanes',$data);
		
		return $id;
	}
	
	function get_swimlanes($user_id){
		$this->db->select('*');
		$this->db->from('swimlanes');
		$this->db->where('user_id',$user_id);
		$this->db->order_by('seq','asc');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	function get_swimlane_detail($swimlanes_id){
		$query = $this->db->get_where('swimlanes',array('swimlanes_id'=>$swimlanes_id));
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}

	function update_swim(){
		$swimlanes_name = $_POST['swimlanes_name'];
		$swimlanes_desc = $_POST['swimlanes_desc'];
		$total = $_POST['total'];
		$swimlanes_id = $_POST['swimlanes_id'];
		if($total){
			for($i=0;$i<$total;$i++){
				$data = array(
					'swimlanes_name' => $swimlanes_name[$i],
					'swimlanes_desc' => $swimlanes_desc[$i],
					'user_id' => $this->session->userdata('user_id'),
					'seq' => $i + 1,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->where('swimlanes_id',$swimlanes_id[$i]);
				$this->db->update('swimlanes',$data);
			}
		}
	}
	
	function get_user_colors($user_id){
		$this->db->order_by('seq','asc');
		$query = $this->db->get_where('user_colors',array('user_id'=>$user_id,'is_deleted'=>'0'));
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /**
         * It return only active color list
         * @param int $user_id
         * @returns int
         */
	function get_user_active_colors($user_id)
	{
		$this->db->order_by('seq','asc');
		$query = $this->db->get_where('user_colors',array('user_id'=>$user_id,'is_deleted'=>'0','status'=>'Active'));
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	function update_user_color(){
		$color_name = $_POST['color_name'];
		$name = $_POST['name'];
		$color_code = $_POST['color_code'];
		$ids = $_POST['ids'];
		$total = $_POST['total'];
		$status = $_POST['status'];
		$status_keys = array_keys($status);
		foreach($ids as $key=>$val){
			if(in_array($val,$status_keys)){
				$new_status[] = 'Active';
			} else {
				$new_status[] = 'Inactive';
			}
		}

		if($total){
			for($i=0;$i<$total;$i++){
				
				$data = array(
					'color_name' => $color_name[$i],
					'name' => $name[$i],
					'user_id' => $this->session->userdata('user_id'),
					'color_code' => $color_code[$i],
					//'color_tooltip' => $color_tooltip[$i],
					'seq' => $i + 1,
					'status' => $new_status[$i]
				);
				$this->db->where('user_color_id',$ids[$i]);
				$this->db->update('user_colors',$data);
			}
			
		} 
		
	}
	
	function get_color_detail($color_id){
		$query = $this->db->get_where('user_colors',array('user_color_id'=>$color_id,'is_deleted'=>'0'));
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0; 
		}
	}
	
	function get_last_color_seq(){
		$this->db->select('MAX(seq) as seq');
		$this->db->from('user_colors');
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->where('is_deleted','0');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->seq;
		} else {
			return 0;
		}
	}
	
	function update_colors($color_id){
		
		$data = array(
			//'color_name' => $this->input->post('color_name'),
			'name' => $this->input->post('name'),
			'user_id' => $this->session->userdata('user_id'),
			//'color_code' => $this->input->post('color_code'),
			//'color_tooltip' => $this->input->post('color_tooltip'),
			'status' => $this->input->post('status')
		);
		$this->db->where('user_color_id',$color_id);
		$this->db->update('user_colors',$data);
	}
	
	function insert_colors(){
		$last_seq = $this->get_last_color_seq();
		
		$data = array(
			'color_name' => $this->input->post('color_name'),
			'name' => $this->input->post('name'),
			'user_id' => $this->session->userdata('user_id'),
			'color_code' => $this->input->post('color_code'),
			'outside_color_code' => $this->input->post('outside_color_code'),
			'seq' => $last_seq + 1,
			'status' => $this->input->post('status'),
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('user_colors',$data);
	}
	/**
         * This function is checked password in user table and returns boolean .
         * @param String $str
         * @returns boolean
         */
	function password_check($str){
		$query = $this->db->get_where('users',array('user_id'=>$this->session->userdata('user_id'),"password"=>md5($str)));
		if($query->num_rows()>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function change_password($oldpassword)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('user_id',get_authenticateUserID());
		$this->db->where('password',$oldpassword);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return 1;
		}
		else {
			return 0;
		}
		
	}
	
	function get_backLogTask($type='',$completed_id){
		$ready_id = get_task_status_id_by_name("Ready");

		$this->db->select("t.*,(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm",FALSE);
		$this->db->from("tasks t");
		$this->db->where("t.task_status_id !=",$completed_id);
		$this->db->where("t.task_status_id !=",$ready_id);
		$this->db->where("t.task_owner_id != ","0");
		$this->db->where("t.task_allocated_user_id != ","0");
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where("t.task_allocated_user_id",get_authenticateUserID());
		$this->db->where("t.task_scheduled_date","0000-00-00");
		if($type){
			$this->db->where('t.task_priority',$type);
		}
		$this->db->where("t.is_deleted","0");
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	
	
	function get_taskList($type='',$duration ='',$task_status_completed_id='',$offdays='')
	{
		if($duration == "backlog"){
			return $this->get_backLogTask($type,$task_status_completed_id);
		} else {
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$today = date('Y-m-d');
			
			$task_list = array();
			if($task_status_completed_id){ $task_status_completed_id = $task_status_completed_id; } else { $task_status_completed_id = $this->config->item('completed_id'); }
			if($offdays){ $offdays = $offdays; } else { $offdays = get_company_offdays();}
			
			$query = $this->db->select('t.*,(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm',FALSE)
							  ->from('tasks t')
							  ->where(array('t.task_owner_id != '=>'0','t.task_allocated_user_id != '=>'0','t.master_task_id'=>'0','t.is_deleted'=>'0','t.task_company_id'=>$this->session->userdata('company_id')))
								->get();
			
			if($query->num_rows()>0){
				$res = $query->result();
				
				if($res){
					foreach($res as $row){
						if($type!='' && $duration=='this_week'){
							$d = strtotime("today");
							$start_week = strtotime("last sunday midnight",$d);
							$end_week = strtotime("next saturday",$d);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week);
							 
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_priority'] == $type && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_priority'] == $type && $row2['task_allocated_user_id'] == get_authenticateUserID()  && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_priority == $type && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						} elseif($type!='' && $duration == "next_week"){
							$d1 = strtotime("+1 week -1 day");
							$start_week = strtotime("last sunday midnight",$d1);
							$end_week = strtotime("next saturday",$d1);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week);
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_priority'] == $type  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_priority'] == $type  && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_priority == $type && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						} elseif($type!='' && $duration == "this_month"){
								
							$start = date("Y-m-01");
							$end = date("Y-m-t");
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_priority'] == $type  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_priority'] == $type  && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_priority == $type && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						}elseif($type!='' && $duration == "next_month"){
							$d1 = strtotime("+1 month");
							$start = date("Y-m-01",$d1);
							$end = date("Y-m-t",$d1);
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_priority'] == $type  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_priority'] == $type  && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_priority == $type && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						}elseif($type!='' && $duration == 'today'){
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$today,$today,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_priority'] == $type && $chk_rec['task_scheduled_date'] == $today  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_priority'] == $type  && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_priority == $type && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						} elseif($type!='' && $duration == 'overdue'){
							
							$start = user_first_login_date();
							$end = date('Y-m-d');
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_priority'] == $type  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_priority'] == $type  && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_priority == $type && $row->task_due_date >= $start && $row->task_due_date < $end && $row->task_due_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						} elseif($type == '' && $duration == 'this_week'){
							$d = strtotime("today");
							$start_week = strtotime("last sunday midnight",$d);
							$end_week = strtotime("next saturday",$d);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week); 
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								//pr($re_data);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										
										//pr($chk_rec);
										if($chk_rec){
											if($chk_rec['task_scheduled_date'] >= $start && $chk_rec['task_scheduled_date'] <= $end  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_scheduled_date'] >= $start && $row2['task_scheduled_date'] <= $end  && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								//echo "else";
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						} elseif($type == '' && $duration == 'next_week'){
							$d1 = strtotime("+1 week -1 day");
							$start_week = strtotime("last sunday midnight",$d1);
							$end_week = strtotime("next saturday",$d1);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week);
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_scheduled_date'] >= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_scheduled_date'] >= $start && $row2['task_scheduled_date'] <= $end && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
							
						} elseif($type == '' && $duration == 'this_month'){
							$start = date("Y-m-01");
							$end = date("Y-m-t");
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_scheduled_date'] >= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_scheduled_date'] >= $start && $row2['task_scheduled_date'] <= $end && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						}  elseif($type == '' && $duration == 'next_month'){
                                                        $d1 = strtotime("+1 month");
							$start = date("Y-m-01",$d1);
							$end = date("Y-m-t",$d1);
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_scheduled_date'] >= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_scheduled_date'] >= $start && $row2['task_scheduled_date'] <= $end && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_status_id != $task_status_completed_id && $row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						}elseif($type == '' && $duration == 'overdue'){
							$start = user_first_login_date();
							$end = date('Y-m-d');
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_due_date'] >= $start && $chk_rec['task_due_date'] < $end && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if($row2['task_due_date'] >= $start && $row2['task_due_date'] < $end && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_due_date >= $start && $row->task_due_date < $end && $row->task_due_date != '0000-00-00' && $row->task_status_id != $task_status_completed_id){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						} else {
							//pr($row);
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$today,$today,$offdays);
								//pr($re_data);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if($chk_rec['task_scheduled_date'] == $today && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
											
										} else {
											if($row2['task_scheduled_date'] == $today && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00' && $row->task_status_id != $task_status_completed_id){
									$row1 = (array)$row;
									array_push($task_list,$row1);
								} 
							}
						}
					}
				}
				date_default_timezone_set("UTC");
				//$task_list = usort($task_list,$type);
				// ksort($array, SORT_STRING);
				//pr($task_list);
				$task_list = (object)$task_list;
				
				
				//pr($task_list);
				
				return $task_list;
			} else {
				return 0;
			}
		}
	}
        /**
         * It will return today task list.
         * @param  $type
         * @returns int|tasklist
         */
	function get_todaytaskList($type)
	{
		//echo $type;
		$today = date("Y-m-d");
		$task_status_completed_id = $this->config->item('completed_id');
		$task_list = array();
		
		$this->db->select('*');
		$this->db->from('tasks');
		$this->db->where(array('master_task_id'=>'0','is_deleted'=>'0')); //,'task_status_id <>'=>$task_status_completed_id
		$this->db->where('task_owner_id != ',"0");
		$this->db->where('task_allocated_user_id != ',"0");
		
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->result();
			
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$today,$today);
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date'] == $today  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row->task_allocated_user_id == get_authenticateUserID()  && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
							//&& $row->task_priority == $type
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			$task_list = array_sort($task_list, $type, $direction = SORT_ASC);
			//pr($task_list);die;
			$task_list = (object)$task_list;
			return $task_list;
		} else {
			return 0;
		}
		
	}

	function getwatchlist($type="")
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$today = date('Y-m-d');
		$this->db->select('t.*,u.first_name,u.last_name,u.profile_image as allocated_user_profile_image,wl.*,(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm',FALSE);
		$this->db->from('my_watch_list wl');
		$this->db->join('tasks t','wl.task_id = t.task_id','left');
		$this->db->join('users u','wl.user_id = u.user_id','left');
		$this->db->where('wl.user_id',get_authenticateUserID());
		$this->db->where('t.is_deleted','0');
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		
		if($type=="due_date"){
			$this->db->order_by('`task_scheduled_date`','DESC');
		}		
		if($type=="priority"){
			$this->db->order_by('t.task_priority');
		}
		if($type=="people"){
			$this->db->order_by('wl.user_id');
		}
		
		$query = $this->db->get();
		date_default_timezone_set("UTC");
		//echo $this->db->last_query();die;
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
		
	}
	
	function getLastlogintask($current="0000-00-00 00:00:00",$last="0000-00-00 00:00:00",$task_status_completed_id)
	{
		
		if($current!="0000-00-00 00:00:00"){
			$query = $this->db->query('SELECT * FROM `tasks` WHERE `task_owner_id` != "0" AND task_allocated_user_id != "0" AND `task_added_date` <= "'.$last.'" AND `task_added_date` >= "'.$current.'" AND is_deleted="0" AND task_allocated_user_id = '.get_authenticateUserID().' and master_task_id = "0" AND task_company_id = "'.$this->session->userdata('company_id').'" order by task_id desc');
		} else {
			$query = $this->db->query('SELECT * FROM `tasks` WHERE `task_owner_id` != "0" AND task_allocated_user_id != "0" AND `task_added_date` <= "'.$last.'" AND is_deleted="0" AND task_allocated_user_id = '.get_authenticateUserID().' and master_task_id = "0" AND task_company_id = "'.$this->session->userdata('company_id').'" order by task_id desc');
		}
		
		
		if($query->num_rows()>0){
			$res = $query->result();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = kanban_recurrence_logic($row_pass);
						if($re_data){
							$chk_rec = chk_virtual_recurrence_exists($re_data['master_task_id'],$re_data['task_orig_scheduled_date'],$task_status_completed_id);
							if($chk_rec){
								if($chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
									array_push($task_list,$chk_rec);
								}
							} else {
								if($re_data['task_allocated_user_id'] == get_authenticateUserID()){
									array_push($task_list,$re_data);
								}
							}
								
						}
					} else {
						//pr($row);
						if($row->task_allocated_user_id == get_authenticateUserID()){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			
			//$task_list = array_sort($task_list, $type, $direction = SORT_ASC);
			$task_list = (object)$task_list;
			//pr($task_list);
			return $task_list;
		} else {
			return 0;
		}
	}
	
	function getArr($date){
		$date_arr = array();
		$defaults = get_calender_settings_by_user($this->session->userdata('user_id'));
		$day = date("l",strtotime(str_replace(array("/"," ",","), "-", $date)));
		if($defaults){
			if($defaults->MON_closed == '1'){
				if($day == 'Monday'){
					array_push($date_arr,$date);
				} else {
					$to = date("Y-m-d",strtotime("next monday", strtotime($date)));
					array_push($date_arr,$to);
				}
				
			}
			if($defaults->TUE_closed == '1'){
				if($day == 'Tuesday'){
					array_push($date_arr,$date);
				} else {
					$to = date("Y-m-d",strtotime("next tuesday", strtotime($date)));
					array_push($date_arr,$to);
				}
			}
			if($defaults->WED_closed == '1'){
				if($day == 'Wednesday'){
					array_push($date_arr,$date);
				} else { 
					$to = date("Y-m-d",strtotime("next wednesday", strtotime($date)));
					array_push($date_arr,$to);
				}
			}
			if($defaults->THU_closed == '1'){
				if($day == 'Thursday'){
					array_push($date_arr,$date);
				} else {
					$to = date("Y-m-d",strtotime("next thursday", strtotime($date)));
					array_push($date_arr,$to);	
				}
			}
			if($defaults->FRI_closed == '1'){
				if($day == 'Friday'){
					array_push($date_arr,$date);
				} else {
					$to = date("Y-m-d",strtotime("next friday", strtotime($date)));
					array_push($date_arr,$to);
				}
			}
			if($defaults->SAT_closed == '1'){
				if($day == 'Saturday'){
					array_push($date_arr,$date);
				} else {
					$to = date("Y-m-d",strtotime("next saturday", strtotime($date)));
					array_push($date_arr,$to);
				}
			}
			if($defaults->SUN_closed == '1'){
				if($day == 'Sunday'){
					array_push($date_arr,$date);
				} else {
					$to = date("Y-m-d",strtotime("next sunday", strtotime($date)));
					array_push($date_arr,$to);
				}
			}
		} else {
			for($i=1;$i<=7;$i++)
		 	{
		 	 	$to = date("Y-m-d", strtotime($date . ' +1 Weekday'));   
		 	 	array_push($date_arr,$to);
				$date = $to;
		 	}
		}
		
		sort($date_arr);
		return $date_arr;
	}
	
	
	function gettimeestimation($task_status_completed_id='',$offdays='')
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$date_arr = array();
		$default_day = get_default_day_of_company();
		$start_date = date('Y-m-d',strtotime($default_day.' this week'));
		
		$date_arr = $this->getArr($start_date);
		
		$start = reset($date_arr);
		$end = end($date_arr);
		
		
		
		$last_week_dates = array();
		
		if($date_arr){
			foreach($date_arr as $date){
				$last_week_dates[$date] = array("task_time"=>0,
											"task_scheduled_date"=> date('Y-m-d',strtotime($date)),
											"day" => date('l', strtotime($date)));
			}
		}		
		
		//pr($last_week_dates);die;
		$user_id = get_authenticateUserID();
		
		$query = $this->db->query("SELECT t.*, DAYNAME(`t`.`task_scheduled_date`) as day FROM (`tasks` t) LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND t.is_deleted='0'  AND  d1.comapny_id ='0' AND master_task_id = '0' AND t.task_company_id = ".$this->session->userdata('company_id')."");
		
		//echo $this->db->last_query();die;
		
		if($query->num_rows()>0){
			$res = $query->result();
			if($offdays){ $offdays = $offdays; }else {$offdays = get_company_offdays(); }
			if($task_status_completed_id){$task_status_completed_id = $task_status_completed_id; } else { $task_status_completed_id = $this->config->item('completed_id');}
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
						if($re_data){
							//pr($re_data);
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
								if($chk_rec){
									if($chk_rec['task_scheduled_date']>= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date']>= $start && $row2['task_scheduled_date'] <= $end && $row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						//pr($row);
						if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			$task_list = $task_list;
			//return $task_list;
		
			//pr($task_list);
	
			$groups = array();
			if($task_list)
			{
				$key = 0;
				foreach($task_list as $t)
				{
					$t = (array)$t;
					
					 if (!array_key_exists($t['task_scheduled_date'], $groups)) {
			            $groups[$t['task_scheduled_date']] = array(
			            	'task_time' => $t['task_time_estimate'],
			                'task_scheduled_date' => $t['task_scheduled_date'],
			                'day' => date('l', strtotime($t["task_scheduled_date"])),
			            );
						
			        } else {
			            $groups[$t['task_scheduled_date']]['task_time'] = $groups[$t['task_scheduled_date']]['task_time'] + $t['task_time_estimate'];
						
			        }
			        $key++;
					
					
					if(isset($last_week_dates[$t["task_scheduled_date"]]))
					{
						unset($last_week_dates[$t["task_scheduled_date"]]);
									   
			           	$last_week_dates[$t["task_scheduled_date"]] = array(
			           					  "task_time"=>$groups[$t["task_scheduled_date"]]['task_time'],
			            	              "task_scheduled_date"=> $t["task_scheduled_date"],
			                	          "day" => date('l', strtotime($t["task_scheduled_date"])));
					}
				}
					
			}
		//pr($last_week_dates);die;
		} else {
			return 0;
		}
		
		/*
		if($query->result_array())
				{
					foreach($query->result_array() as $t)
					{
						if(isset($last_week_dates[$t["task_scheduled_date"]]))
						{
							unset($last_week_dates[$t["task_scheduled_date"]]);
																		   $last_week_dates[$t["task_scheduled_date"]] = array("task_time"=>$t["task_time"],
											  "task_scheduled_date"=> $t["task_scheduled_date"],
											  "day" => date('l', strtotime($t["task_scheduled_date"])));
						}
					}
				}*/
		
		
		date_default_timezone_set("UTC");
		$this->array_sort_by_column($last_week_dates, 'task_scheduled_date');
		//pr($last_week_dates);die;
		return $last_week_dates;
	}

function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
    $reference_array = array();
//print_r($array);die;
    foreach($array as $key => $row) {
        $reference_array[$key] = $row[$column];
    }
	//echo "<pre>";print_r($reference_array);die;

    array_multisort($reference_array, $direction, $array);
}
	function gettimeestimationnext_old()
	{
		$user_id = get_authenticateUserID();
		
		/*
		$d1 = strtotime("+1 week -1 day");
				$start_week = strtotime("last sunday midnight",$d1);
				$end_week = strtotime("next saturday",$d1);*/
		
		//$end_week = strtotime("+1 week",$start_week);
		
		/*
		$start = date("Y-m-d",$start_week);
				$end = date("Y-m-d",$end_week);*/
		$date_arr = array();
		$default_day = get_default_day_of_company();
		$start_date = date('Y-m-d',strtotime($default_day.' next week'));
		
		$date_arr = $this->getArr($start_date);
		
		$start = reset($date_arr);
		$end = end($date_arr);
		
		$this_week_dates = array();
		
		/*
		for($i=0; $i<7; $i++){
					$date = date('Y-m-d',strtotime('this sunday + '.$i.' day'));
					$this_week_dates[$date] = array("task_time"=>0,
											  "task_scheduled_date"=> date('Y-m-d',strtotime('this sunday + '.$i.' day')),
											   "day" => date('l', strtotime('this sunday + '.$i.' day')));
				}*/
		
		if($date_arr){
			foreach($date_arr as $date){
				$this_week_dates[$date] = array("task_time"=>0,
											"task_scheduled_date"=> date('Y-m-d',strtotime($date)),
											"day" => date('l', strtotime($date)));
			}
		}	
		
		
		$query = $this->db->query("SELECT `t`.`task_id`,sum( `t`.`task_time_estimate`) as task_time, `t`.`task_scheduled_date`,DAYNAME(`t`.`task_scheduled_date`) as day FROM (`tasks` t)  LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."' AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND t.is_deleted='0' AND  d1.comapny_id ='0'  AND t.task_owner_id = '$user_id' group by t.task_scheduled_date");
		
		//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."')
		
		//echo $this->db->last_query();
		
		if($query->result_array())
		{
			foreach($query->result_array() as $t)
			{
				if(isset($this_week_dates[$t["task_scheduled_date"]]))
				{
					unset($this_week_dates[$t["task_scheduled_date"]]);
								   
		           	$this_week_dates[$t["task_scheduled_date"]] = array("task_time"=>$t["task_time"],
		            	              "task_scheduled_date"=> $t["task_scheduled_date"],
		                	          "day" => date('l', strtotime($t["task_scheduled_date"])));
				}
				
			
			}
		}
		
		//$result = array_unique(array_merge($query->result_array(),$this_week_dates), SORT_REGULAR);
		$this->array_sort_by_column($this_week_dates, 'task_scheduled_date');
		//$this->array_sort_by_column($result, 'task_due_date');

		return $this_week_dates;
	}
	
	function gettimeestimationnext()
	{
		$user_id = get_authenticateUserID();
		date_default_timezone_set($this->session->userdata("User_timezone"));
		/*
		$d1 = strtotime("+1 week -1 day");
				$start_week = strtotime("last sunday midnight",$d1);
				$end_week = strtotime("next saturday",$d1);*/
		
		//$end_week = strtotime("+1 week",$start_week);
		
		/*
		$start = date("Y-m-d",$start_week);
				$end = date("Y-m-d",$end_week);*/
		$date_arr = array();
		$default_day = get_default_day_of_company();
		$start_date = date('Y-m-d',strtotime($default_day.' next week'));
		
		$date_arr = $this->getArr($start_date);
		//pr($start_date);die;
		$start = reset($date_arr);
		$end = end($date_arr);
		
		$this_week_dates = array();
		
		/*
		for($i=0; $i<7; $i++){
					$date = date('Y-m-d',strtotime('this sunday + '.$i.' day'));
					$this_week_dates[$date] = array("task_time"=>0,
											  "task_scheduled_date"=> date('Y-m-d',strtotime('this sunday + '.$i.' day')),
											   "day" => date('l', strtotime('this sunday + '.$i.' day')));
				}*/
		
		if($date_arr){
			foreach($date_arr as $date){
				$this_week_dates[$date] = array("task_time"=>0,
											"task_scheduled_date"=> date('Y-m-d',strtotime($date)),
											"day" => date('l', strtotime($date)));
			}
		}	
		
		
		//$query = $this->db->query("SELECT `t`.`task_id`,sum( `t`.`task_time_estimate`) as task_time, `t`.`task_scheduled_date`,DAYNAME(`t`.`task_scheduled_date`) as day FROM (`tasks` t)  LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."' AND t.is_deleted='0' AND  d1.comapny_id ='0'  AND t.task_owner_id = '$user_id' group by t.task_scheduled_date");
		
		
		$query = $this->db->query("SELECT t.*, `t`.`task_scheduled_date`,DAYNAME(`t`.`task_scheduled_date`) as day FROM (`tasks` t) LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE  t.is_deleted='0'  AND  d1.comapny_id ='0' AND master_task_id = '0' AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND  t.task_company_id = ".$this->session->userdata('company_id')."");
		
		//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."')
		
		//echo $this->db->last_query();
		
		if($query->num_rows()>0){
			$res = $query->result();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$start,$end);
						if($re_data){
							//pr($re_data);
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date']>= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date']>= $start && $row2['task_scheduled_date'] <= $end && $row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						//pr($row);
						if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			$task_list = $task_list;
			//return $task_list;
		
		
	
			$groups = array();
			if($task_list)
			{
				$key = 0;
				foreach($task_list as $t)
				{
					$t = (array)$t;
					
					 if (!array_key_exists($t['task_scheduled_date'], $groups)) {
			            $groups[$t['task_scheduled_date']] = array(
			            	'task_time' => $t['task_time_estimate'],
			                'task_scheduled_date' => $t['task_scheduled_date'],
			                'day' => date('l', strtotime($t["task_scheduled_date"])),
			            );
						
			        } else {
			            $groups[$t['task_scheduled_date']]['task_time'] = $groups[$t['task_scheduled_date']]['task_time'] + $t['task_time_estimate'];
						
			        }
			        $key++;
					
					
					if(isset($this_week_dates[$t["task_scheduled_date"]]))
					{
						unset($this_week_dates[$t["task_scheduled_date"]]);
									   
			           	$this_week_dates[$t["task_scheduled_date"]] = array(
			           					  "task_time"=>$groups[$t["task_scheduled_date"]]['task_time'],
			            	              "task_scheduled_date"=> $t["task_scheduled_date"],
			                	          "day" => date('l', strtotime($t["task_scheduled_date"])));
					}
				}
					
			}
		
		} else {
			return 0;
		}
		
		/*
		if($query->result_array())
				{
					foreach($query->result_array() as $t)
					{
						if(isset($this_week_dates[$t["task_scheduled_date"]]))
						{
							unset($this_week_dates[$t["task_scheduled_date"]]);
																		   $this_week_dates[$t["task_scheduled_date"]] = array("task_time"=>$t["task_time"],
											  "task_scheduled_date"=> $t["task_scheduled_date"],
											  "day" => date('l', strtotime($t["task_scheduled_date"])));
						}
						
					
					}
				}*/
		
		
		//$result = array_unique(array_merge($query->result_array(),$this_week_dates), SORT_REGULAR);
		date_default_timezone_set("UTC");
		$this->array_sort_by_column($this_week_dates, 'task_scheduled_date');
		//$this->array_sort_by_column($result, 'task_due_date');

		return $this_week_dates;
	}
	
	function get_teamBackLogTask($type=''){
		$completed_id = $this->config->item('completed_id');
		$ready_id = get_task_status_id_by_name("Ready");
		$user_ids = get_users_under_manager();
		
		$this->db->select("t.*,u.first_name,u.last_name,(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm",FALSE);
		$this->db->from("tasks t");
		$this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$this->db->where("t.task_status_id !=",$completed_id);
		$this->db->where("t.task_status_id !=",$ready_id);
		$this->db->where("t.task_owner_id != ","0");
		$this->db->where("t.task_allocated_user_id != ","0");
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where_in("t.task_allocated_user_id",$user_ids);
		$this->db->where("t.task_scheduled_date","0000-00-00");
		if($type){
			$this->db->where('t.task_priority',$type);
		}
		$this->db->where("t.is_deleted","0");
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	/**
         * This function return  task list for teamdasshboard.
         * @param  $type
         * @param  $duration
         * @param  $task_status_completed_id
         * @param  $offdays
         * @returns int
         */
	function get_teamtaskList($type='',$duration ='', $task_status_completed_id='',$offdays='')
	{
		if($duration == "backlog"){
			return $this->get_teamBackLogTask($type);
		} else {
			
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$today = date('Y-m-d');
			
			$task_list = array();
			
			$user_ids = get_users_under_manager();
			
			if($task_status_completed_id){ $task_status_completed_id = $task_status_completed_id; } else { $task_status_completed_id = $this->config->item('completed_id'); }
			if($offdays){ $offdays = $offdays; } else { $offdays = get_company_offdays(); }
			
			$query = $this->db->query("SELECT t.*,u.first_name,u.last_name,u.profile_image as allocated_user_profile_image, (SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm FROM (`tasks` t) LEFT JOIN users u ON u.user_id = t.task_allocated_user_id WHERE t.task_owner_id !='0' AND t.task_allocated_user_id != '0' AND t.master_task_id = '0' AND t.task_company_id = ".$this->session->userdata('company_id')." AND t.is_deleted ='0'");
			/**
                         * check duration for thisweek,nextweek,thismonth,nextmonth and so on.
                         */
			if($query->num_rows()>0){
				$res = $query->result();
				
				if($res){
					foreach($res as $row){
						
						if($type!='' && $duration=='this_week'){
							$d = strtotime("today");
							$start_week = strtotime("last sunday midnight",$d);
							$end_week = strtotime("next saturday",$d);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week);
							 
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_priority'] == $type && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_priority'] == $type && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) &&  $row1["is_personal"] == "0" && $row1["task_priority"] == $type && $row1["task_scheduled_date"] >= $start && $row1["task_scheduled_date"] <= $end && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} elseif($type!='' && $duration == "next_week"){
							$d1 = strtotime("+1 week -1 day");
							$start_week = strtotime("last sunday midnight",$d1);
							$end_week = strtotime("next saturday",$d1);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week);
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_priority'] == $type  && $chk_rec['is_personal'] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_priority'] == $type  && $row2["is_personal"] =="0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) &&  $row1["is_personal"] == "0" && $row1["task_priority"] == $type && $row1["task_scheduled_date"] >= $start && $row1["task_scheduled_date"] <= $end && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} elseif($type!='' && $duration == "this_month"){
								
							$start = date("Y-m-01");
							$end = date("Y-m-t");
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_priority'] == $type  && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_priority'] == $type  && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) &&  $row1["is_personal"] == "0" && $row1["task_priority"] == $type && $row1["task_scheduled_date"] >= $start && $row1["task_scheduled_date"] <= $end && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} elseif($type!='' && $duration == 'today'){
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$today,$today,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_priority'] == $type && $chk_rec['task_scheduled_date'] == $today  && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_priority'] == $type  && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) &&  $row1["is_personal"] == "0" && $row1["task_scheduled_date"] == $today  && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} elseif($type!='' && $duration == 'overdue'){
									
							
							$start = user_first_login_date();
							$end = date('Y-m-d');
							
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_priority'] == $type  && $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_priority'] == $type  && $row2['task_status_id'] != $task_status_completed_id && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) && $row1["task_status_id"] != $task_status_completed_id &&  $row1["is_personal"] == "0" &&  $row1['task_priority'] == $type && $row1["task_due_date"] >= $start && $row1["task_due_date"] < $end && $row1["task_due_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} elseif($type == '' && $duration == 'this_week'){
							$d = strtotime("today");
							$start_week = strtotime("last sunday midnight",$d);
							$end_week = strtotime("next saturday",$d);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week); 
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								//pr($re_data);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										
										//pr($chk_rec);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_scheduled_date'] >= $start && $chk_rec['task_scheduled_date'] <= $end  && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_scheduled_date'] >= $start && $row2['task_scheduled_date'] <= $end  && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) && $row1["is_personal"] == "0" && $row1["task_scheduled_date"] >= $start && $row1["task_scheduled_date"] <= $end && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} elseif($type == '' && $duration == 'next_week'){
							$d1 = strtotime("+1 week -1 day");
							$start_week = strtotime("last sunday midnight",$d1);
							$end_week = strtotime("next saturday",$d1);
							$start = date("Y-m-d",$start_week); 
							$end = date("Y-m-d",$end_week);
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_scheduled_date'] >= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_scheduled_date'] >= $start && $row2['task_scheduled_date'] <= $end && $chk_rec["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) && $row1["is_personal"] == "0" && $row1["task_scheduled_date"] >= $start && $row1["task_scheduled_date"] <= $end && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
							
						} elseif($type == '' && $duration == 'this_month'){
							$start = date("Y-m-01");
							$end = date("Y-m-t");
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_scheduled_date'] >= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_scheduled_date'] >= $start && $row2['task_scheduled_date'] <= $end && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) && $row1["is_personal"] == "0" && $row1["task_scheduled_date"] >= $start && $row1["task_scheduled_date"] <= $end && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} elseif($type == '' && $duration == 'overdue'){
							
							$start = user_first_login_date();
							$end = date('Y-m-d');
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_status_id'] != $task_status_completed_id && $chk_rec['task_due_date'] >= $start && $chk_rec['task_due_date'] < $end && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids)  && $row2['task_due_date'] >= $start && $row2['task_due_date'] < $end && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) && $row1["task_status_id"] != $task_status_completed_id  && $row1["is_personal"] == "0" && $row1["task_due_date"] >= $start && $row1["task_due_date"] < $end && $row1["task_due_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						} else {
							//pr($row);
							if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
								$row_pass = (array) $row;
								$re_data = monthly_recurrence_logic($row_pass,$today,$today,$offdays);
								//pr($re_data);
								if($re_data){
									foreach($re_data as $row2){
										$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
										if($chk_rec){
											if(in_array($chk_rec['task_allocated_user_id'], $user_ids) &&  $chk_rec['task_scheduled_date'] == $today && $chk_rec["is_personal"] == "0" && $chk_rec['task_status_id']!=$task_status_completed_id && $chk_rec['is_deleted'] == "0"){
												array_push($task_list,$chk_rec);
											}
											
										} else {
											if(in_array($row2['task_allocated_user_id'], $user_ids) &&  $row2['task_scheduled_date'] == $today && $row2["is_personal"] == "0" && $row2['task_status_id']!=$task_status_completed_id){
												array_push($task_list,$row2);
											}
										}
									}
								}
							} else {
								$row1 = (array)$row;
								if(in_array($row1['task_allocated_user_id'], $user_ids) && $row1["is_personal"] == "0" && $row1["task_scheduled_date"] == $today && $row1["task_scheduled_date"] != '0000-00-00' && $row1['task_status_id']!=$task_status_completed_id){
									array_push($task_list,$row1);
								} 
							}
						}
					
					}
				}
				date_default_timezone_set("UTC");
				$task_list = (object)$task_list;
				
				return $task_list;
			} else {
				return 0;
			}
		}
	}
	/**
         * This function return task list for teamdashboard according to it type.
         * @param string $type
         * @returns int
         */
	function get_teamtaskListByType($type)
	{
		$today = date('Y-m-d');
		
		$task_list = array();
		
		$task_status_completed_id = $this->config->item('completed_id');
		
		$this->db->select("t.*");
		$this->db->from("tasks t");
		$this->db->where("t.`task_scheduled_date`", $today);
		$this->db->where("t.task_company_id",$this->session->userdata('company_id'));
		$this->db->where("t.task_status_id <>",$task_status_completed_id);
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_deleted','0');
		
		if($type == "people"){
			$this->db->order_by("t.task_allocated_user_id","DESC");
		}

		if($type =="priority"){
			$this->db->order_by("t.task_priority","DESC");
		}
		
		if($type =="status"){
			$this->db->order_by("t.task_status_id","DESC");
		}
		
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			$res = $query->result();
			
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$today,$today);
						if($re_data){
							//pr($re_data);
							foreach($re_data as $row2){
								//pr($row2);
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date']==$today && $chk_rec['task_allocated_user_id'] != get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date']==$today && $row2['task_allocated_user_id'] != get_authenticateUserID()){
										//pr($task_list);
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						//pr($row);
						if($row->task_allocated_user_id != get_authenticateUserID() && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			date_default_timezone_set("UTC");
			//pr($task_list);die;
			//echo count($task_list);die;
			if(count($task_list)>0){
				$task_list = (object)$task_list;
			}
			//pr($task_list);die;
			return $task_list;
		} else {
			return 0;
		}
		
		
	}
	
	function get_pendingtaskList_old()
	{
		$today = date('Y-m-d');
		$task_status_completed_id = $this->config->item('completed_id');
		$query = $this->db->query("SELECT t . * ,  u1.first_name AS first_owner_name, u1.last_name  AS last_owner_name, u2.first_name AS allocated_user_first_name , u2.last_name AS allocated_user_last_name FROM tasks t LEFT JOIN users u1 ON t.task_owner_id = u1.user_id LEFT JOIN users u2 ON t.task_allocated_user_id = u2.user_id WHERE `task_scheduled_date`>='".$today."' and `task_scheduled_date`<> '0000-00-00' AND t.`task_status_id` <> '".$task_status_completed_id."'  AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND  t.task_company_id = ".$this->session->userdata('company_id')." AND t.is_deleted ='0'");
		
		if($query->num_rows()>0){
			
			return $query->result();
		} else {
			return 0;
		}
	}
	
	function get_pendingtaskList($task_status_completed_id,$offdays)
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$start_date = date('Y-m-d');
		$end_date = date("Y-m-d",strtotime("+5 days"));
                $allusers = get_company_users();
                $all = array();
                foreach($allusers as $one)
                {
                    $all[] = $one->user_id;
                }
//                $data['team_ids'] = get_users_under_managers_ids();
                
		$data['team_ids'] = $all;
		if($data['team_ids']!='0'){
			$ids = join(',',$data['team_ids']);
		}else{
			$ids = '0';
		}
		
		$query = $this->db->query("SELECT t . * ,  u1.first_name AS first_owner_name, u1.last_name  AS last_owner_name, u1.profile_image as owner_profile_image, u2.first_name AS allocated_user_first_name , u2.last_name AS allocated_user_last_name, u2.profile_image as allocated_user_profile_image, c1.customer_name, ts.task_status_name FROM tasks t LEFT JOIN users u1 ON t.task_owner_id = u1.user_id LEFT JOIN users u2 ON t.task_allocated_user_id = u2.user_id LEFT JOIN customers c1 on (t.customer_id = c1.customer_id AND t.task_company_id = c1.customer_company_id) LEFT JOIN task_status ts on ts.task_status_id = t.task_status_id WHERE t.master_task_id = 0 AND t.`task_status_id` <> '".$task_status_completed_id."' AND t.task_owner_id = ".$this->session->userdata("user_id")." AND t.task_allocated_user_id != '0' AND  t.task_company_id = ".$this->session->userdata('company_id')." AND t.task_allocated_user_id in (".$ids.")  AND t.is_deleted ='0' AND t.is_personal = '0' ");
//		echo $this->db->last_query();
		if($query->num_rows()>0){
			$res = $query->result();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$start_date,$end_date,$offdays);
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists_teampending($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
								if($chk_rec){
									if($chk_rec['task_allocated_user_id'] != get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_allocated_user_id'] != get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}	}
						}
					} else {
						//pr($row);
						if($row->task_allocated_user_id != get_authenticateUserID() && $row->task_scheduled_date != '0000-00-00'){
							$row1 = (array)$row;
					
						array_push($task_list,$row1);
						} 
					}
				}
			}
			date_default_timezone_set("UTC");
			$task_list = (object)$task_list;
			return $task_list;
		} else {
			return 0;
		}
	}

	function get_overduetaskList_old()
	{
		$today = date('Y-m-d');
		
		$task_status_completed_id = $this->config->item('completed_id');
		
		$query = $this->db->query("SELECT t . * ,task_due_date as task_true_date, CONCAT( u1.first_name, ' ', u1.last_name ) AS owner_name,  u2.first_name AS allocated_user_first_name , u2.last_name  AS allocated_user_last_name FROM tasks t LEFT JOIN users u1 ON t.task_owner_id = u1.user_id LEFT JOIN users u2 ON t.task_allocated_user_id = u2.user_id WHERE `task_due_date`<'".$today."' and `task_due_date`<> '0000-00-00' AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND  t.`task_status_id` <> '".$task_status_completed_id."' AND t.task_company_id = ".$this->session->userdata('company_id')." AND t.is_deleted ='0' and `task_due_date`!='0000-00-00'  order by task_true_date");
		
		//if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`<'".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`<'".$today."' and `task_due_date` <>'0000-00-00')
		
		//echo $this->db->last_query();die;
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_overduetaskList($task_status_completed_id,$offdays)
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$end_date = date('Y-m-d');
		$start_date = user_first_login_date();
		
		$data['team_ids'] = get_users_under_managers_ids();
		if($data['team_ids']!='0'){
			$ids = join(',',$data['team_ids']);
		}else{
			$ids = '0';
		} 
		
		$query = $this->db->query("SELECT t . * ,task_due_date as task_true_date, CONCAT( u1.first_name, ' ', u1.last_name ) AS owner_name, u1.profile_image as owner_profile_image,  u2.first_name AS allocated_user_first_name , u2.last_name  AS allocated_user_last_name, u2.profile_image as allocated_user_profile_image FROM tasks t LEFT JOIN users u1 ON t.task_owner_id = u1.user_id LEFT JOIN users u2 ON t.task_allocated_user_id = u2.user_id WHERE t.master_task_id = 0 AND t.`task_status_id` <> '".$task_status_completed_id."' AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND t.is_personal!= '1' AND  t.task_company_id = ".$this->session->userdata('company_id')." AND t.is_deleted ='0' AND t.task_allocated_user_id in (".$ids.") order by t.task_due_date");
		if($query->num_rows()>0){
			$res = $query->result();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$start_date,$end_date,$offdays);
						if($re_data){
							foreach($re_data as $row2){
								
								$chk_rec = chk_virtual_recurrence_exists_teamoverdue($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								
								if($chk_rec){
									if($chk_rec['task_due_date']>= $start_date && $chk_rec['task_due_date'] < $end_date && $chk_rec['task_allocated_user_id'] != get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_due_date']>= $start_date && $row2['task_due_date'] < $end_date && $row2['task_allocated_user_id'] != get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row->task_allocated_user_id != get_authenticateUserID() && $row->task_due_date >= $start_date && $row->task_due_date < $end_date && $row->task_due_date != '0000-00-00'){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			date_default_timezone_set("UTC");
			$task_list = (object)$task_list;
			//pr($task_list);die;
			return $task_list;
		} else {
			return 0;
		}
	}
	
	function gettimeestimationteam_old()
	{
		$user_id = get_authenticateUserID();
		
		$date_arr = array();
		$default_day = get_default_day_of_company();
		$start_date = date('Y-m-d',strtotime($default_day.' this week'));
		
		$date_arr = $this->getArr($start_date);
		
		$start = reset($date_arr);
		$end = end($date_arr);			
		
		$last_week_dates = array();
		
		/*
		for($i=0; $i<7; $i++){
					$date = date('Y-m-d',strtotime('last sunday + '.$i.' day'));
					$last_week_dates[$date] = array("task_time"=>0,
											  "task_scheduled_date"=> date('Y-m-d',strtotime('last sunday + '.$i.' day')),
											   "day" => date('l', strtotime('last sunday + '.$i.' day')));
				}*/
		
		
		
		if($date_arr){
			foreach($date_arr as $date){
				$last_week_dates[$date] = array("task_time"=>0,
											"task_scheduled_date"=> date('Y-m-d',strtotime($date)),
											"day" => date('l', strtotime($date)));
			}
		}
		
		
		$query = $this->db->query("SELECT t.task_owner_id,t.task_allocated_user_id,`t`.`task_id`,sum( `t`.`task_time_estimate`) as task_time, `t`.`task_scheduled_date`,DAYNAME(`t`.`task_scheduled_date`) as day FROM (`tasks` t)  LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."' AND t.is_deleted='0' AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND  t.task_company_id = ".$this->session->userdata('company_id')." group by t.task_scheduled_date");
		
		//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."') 
		
		//echo $this->db->last_query();die;
		if($query->result_array())
		{
			foreach($query->result_array() as $t)
			{
				if(isset($last_week_dates[$t["task_scheduled_date"]]))
				{
					unset($last_week_dates[$t["task_scheduled_date"]]);
								   
		           	$last_week_dates[$t["task_scheduled_date"]] = array("task_time"=>$t["task_time"],
		            	              "task_scheduled_date"=> $t["task_scheduled_date"],
		                	          "day" => date('l', strtotime($t["task_scheduled_date"])));
				}
				
			
			}
		}
		
		$this->array_sort_by_column($last_week_dates, 'task_scheduled_date');
		return $last_week_dates;
	}
	
	function gettimeestimationteam($task_status_completed_id,$offdays)
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		
		$user_id = get_authenticateUserID();
		
		$date_arr = array();
		$default_day = get_default_day_of_company();
		$start_date = date('Y-m-d',strtotime($default_day.' this week'));
		
		$date_arr = $this->getArr($start_date);
		
		$start = reset($date_arr);
		$end = end($date_arr);			
		
		$last_week_dates = array();
		
		if($date_arr){
			foreach($date_arr as $date){
				$last_week_dates[$date] = array("task_time"=>0,
											"task_scheduled_date"=> date('Y-m-d',strtotime($date)),
											"day" => date('l', strtotime($date)));
			}
		}
		$data['team_ids'] = get_users_under_managers_ids();
		if($data['team_ids']!='0'){
			$ids = join(',',$data['team_ids']);
		}else{
			$ids = '0';
		} 
		
		$query = $this->db->query("SELECT t.*, `t`.`task_scheduled_date`,DAYNAME(`t`.`task_scheduled_date`) as day FROM (`tasks` t) LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND  t.is_deleted='0'  AND  d1.comapny_id ='0' AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND  master_task_id = '0' AND t.task_company_id = ".$this->session->userdata('company_id')." AND t.task_allocated_user_id in (".$ids.")");
		
		
		if($query->num_rows()>0){
			$res = $query->result();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$start,$end,$offdays);
						if($re_data){
							//pr($re_data);
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
								if($chk_rec){
									if($chk_rec['task_scheduled_date']>= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec['task_allocated_user_id'] != get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date']>= $start && $row2['task_scheduled_date'] <= $end && $row2['task_allocated_user_id'] != get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						//pr($row);
						if($row->task_allocated_user_id != get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			$task_list = $task_list;
			//return $task_list;
		
		
	
			$groups = array();
			if($task_list)
			{
				$key = 0;
				foreach($task_list as $t)
				{
					$t = (array)$t;
					
					 if (!array_key_exists($t['task_scheduled_date'], $groups)) {
			            $groups[$t['task_scheduled_date']] = array(
			            	'task_time' => $t['task_time_estimate'],
			                'task_scheduled_date' => $t['task_scheduled_date'],
			                'day' => date('l', strtotime($t["task_scheduled_date"])),
			            );
						
			        } else {
			            $groups[$t['task_scheduled_date']]['task_time'] = $groups[$t['task_scheduled_date']]['task_time'] + $t['task_time_estimate'];
						
			        }
			        $key++;
					
					
					if(isset($last_week_dates[$t["task_scheduled_date"]]))
					{
						unset($last_week_dates[$t["task_scheduled_date"]]);
									   
			           	$last_week_dates[$t["task_scheduled_date"]] = array(
			           					  "task_time"=>$groups[$t["task_scheduled_date"]]['task_time'],
			            	              "task_scheduled_date"=> $t["task_scheduled_date"],
			                	          "day" => date('l', strtotime($t["task_scheduled_date"])));
					}
				}
					
			}
		
		} else {
			date_default_timezone_set("UTC");
			return 0;
		}
		
		
		date_default_timezone_set("UTC");
		//pr($last_week_dates);die;
		$this->array_sort_by_column($last_week_dates, 'task_scheduled_date');
		return $last_week_dates;
	}
	
	
	
	function gettimeestimationnextteam()
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$date_arr = array();
		$default_day = get_default_day_of_company();
		$start_date = date('Y-m-d',strtotime($default_day.' next week'));
		
		$date_arr = $this->getArr($start_date);
		
		$start = reset($date_arr);
		$end = end($date_arr);
		
		
			
		
		$this_week_dates = array();
		
		/*
		for($i=0; $i<7; $i++){
					$date = date('Y-m-d',strtotime('last sunday + '.$i.' day'));
					$last_week_dates[$date] = array("task_time"=>0,
											  "task_scheduled_date"=> date('Y-m-d',strtotime('last sunday + '.$i.' day')),
											   "day" => date('l', strtotime('last sunday + '.$i.' day')));
				}*/
		
		
		
		if($date_arr){
			foreach($date_arr as $date){
				$this_week_dates[$date] = array("task_time"=>0,
											"task_scheduled_date"=> date('Y-m-d',strtotime($date)),
											"day" => date('l', strtotime($date)));
			}
		}
		
		//pr($this_week_dates);
		
		//$query = $this->db->query("SELECT t.task_owner_id,t.task_allocated_user_id,`t`.`task_id`,sum( `t`.`task_time_estimate`) as task_time, `t`.`task_scheduled_date`,DAYNAME(`t`.`task_due_date`) as day FROM (`tasks` t)  LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."' AND t.is_deleted='0' AND  t.task_company_id = ".$this->session->userdata('company_id')." group by t.task_scheduled_date");
		
		$data['team_ids'] = get_users_under_managers_ids();
		if($data['team_ids']!='0'){
			$ids = join(',',$data['team_ids']);
		}else{
			$ids = '0';
		} 
		
		$query = $this->db->query("SELECT t.*, `t`.`task_scheduled_date`,DAYNAME(`t`.`task_scheduled_date`) as day FROM (`tasks` t) LEFT JOIN `default_calendar_setting` d1 ON `t`.`task_allocated_user_id` = `d1`.`user_id` WHERE t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND  t.is_deleted='0'  AND  d1.comapny_id ='0' AND master_task_id = '0' AND t.task_company_id = ".$this->session->userdata('company_id')." AND t.task_allocated_user_id in (".$ids.")");
		
		
		if($query->num_rows()>0){
			$res = $query->result();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$start,$end);
						if($re_data){
							//pr($re_data);
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date']>= $start && $chk_rec['task_scheduled_date'] <= $end && $chk_rec['task_allocated_user_id'] != get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date']>= $start && $row2['task_scheduled_date'] <= $end && $row2['task_allocated_user_id'] != get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						//pr($row);
						if($row->task_allocated_user_id != get_authenticateUserID() && $row->task_scheduled_date >= $start && $row->task_scheduled_date <= $end && $row->task_scheduled_date != '0000-00-00'){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
				}
			}
			$task_list = $task_list;
			//return $task_list;
		
		
	
			$groups = array();
			if($task_list)
			{
				$key = 0;
				foreach($task_list as $t)
				{
					$t = (array)$t;
					
					 if (!array_key_exists($t['task_scheduled_date'], $groups)) {
			            $groups[$t['task_scheduled_date']] = array(
			            	'task_time' => $t['task_time_estimate'],
			                'task_scheduled_date' => $t['task_scheduled_date'],
			                'day' => date('l', strtotime($t["task_scheduled_date"])),
			            );
						
			        } else {
			            $groups[$t['task_scheduled_date']]['task_time'] = $groups[$t['task_scheduled_date']]['task_time'] + $t['task_time_estimate'];
						
			        }
			        $key++;
					
					
					if(isset($this_week_dates[$t["task_scheduled_date"]]))
					{
						unset($this_week_dates[$t["task_scheduled_date"]]);
									   
			           	$this_week_dates[$t["task_scheduled_date"]] = array(
			           					  "task_time"=>$groups[$t["task_scheduled_date"]]['task_time'],
			            	              "task_scheduled_date"=> $t["task_scheduled_date"],
			                	          "day" => date('l', strtotime($t["task_scheduled_date"])));
					}
				}
					
			}
		
		} else {
			return 0;
		}
		
		//if(`task_scheduled_date`!='0000-00-00', `task_scheduled_date`>= '".$start."' and `task_scheduled_date`<= '".$end."',`task_due_date`!='0000-00-00' and `task_due_date`>= '".$start."' and `task_due_date`<='".$end."')
		
		//echo $this->db->last_query();
		
		/*
		if($query->result_array())
				{
					foreach($query->result_array() as $t)
					{
						if(isset($this_week_dates[$t["task_scheduled_date"]]))
						{
							unset($this_week_dates[$t["task_scheduled_date"]]);
																		   $this_week_dates[$t["task_scheduled_date"]] = array("task_time"=>$t["task_time"],
											  "task_scheduled_date"=> $t["task_scheduled_date"],
											  "day" => date('l', strtotime($t["task_scheduled_date"])));
						}
						
					
					}
				}*/
		
		date_default_timezone_set("UTC");
		$this->array_sort_by_column($this_week_dates, 'task_scheduled_date');
		return $this_week_dates;
	}
	
	function getMemberList()
	{
		$d = strtotime("today");
		$start_week = strtotime("last monday midnight",$d);
		$end_week = strtotime("next sunday",$d);
		$start = date("Y-m-d",$start_week); 
		$end = date("Y-m-d",$end_week); 
		$user_id = get_authenticateUserID();
		
		$query = $this->db->query("select distinct(task_allocated_user_id) from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND  task_company_id = ".$this->session->userdata('company_id')."");
		
		//echo $this->db->last_query();die;
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	/**
         * This function return parameters for draw time allocation chart.
         * @param  $task_status_completed_id
         * @param  $offdays
         * @returns string
         */
	function getTimeAllocationChart($task_status_completed_id='',$offdays='')
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$start_date1 = date('Y-m-d');

		$date_arr = $this->getDateArr($start_date1);
		
                $start_date = reset($date_arr);
		$end_date = end($date_arr);
		
		if($offdays){ $offdays = $offdays; } else { $offdays = get_company_offdays(); }
		if($task_status_completed_id){ $task_status_completed_id = $task_status_completed_id; } else { $task_status_completed_id = $this->config->item('completed_id'); }
		
		$task_list1 = array();


//		$query = $this->db->query("SELECT t.*,tc.category_name FROM tasks t left join task_category tc on t.task_category_id = tc.category_id WHERE t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND t.master_task_id  = 0 AND t.task_company_id = ".$this->session->userdata('company_id')." AND t.is_deleted ='0' ORDER BY t.task_scheduled_date");
		$this->db->select('t.*,tc.category_name');
                $this->db->from('tasks t');
                $this->db->join('task_category tc','t.task_category_id = tc.category_id','left');
                $this->db->where('t.task_owner_id ', get_authenticateUserID());
                $this->db->where('t.task_allocated_user_id ',  get_authenticateUserID());
                $this->db->where('t.master_task_id','0');
                $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
                $this->db->where('t.is_deleted','0');
                $this->db->order_by('t.task_scheduled_date');
                $query = $this->db->get();
		//echo $this->db->last_query();die;

		if($query->num_rows()>0){
			$res = $query->result();
			if($res){
				foreach($res as $row){
				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;
					$re_data = monthly_recurrence_logic($row_pass,$start_date,$end_date,$offdays);
					if($re_data){
						foreach($re_data as $row2){
							$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
							if($chk_rec){
								if($chk_rec['task_scheduled_date']>= $start_date && $chk_rec['task_scheduled_date'] <= $end_date && $chk_rec['is_personal'] == "0" && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){

									array_push($task_list1,$chk_rec);
								}
							} else {

								if($row2['task_scheduled_date']>= $start_date && $row2['task_scheduled_date'] <= $end_date  && $row2['is_personal'] == "0"  &&  $row2['task_allocated_user_id'] == get_authenticateUserID()){
									array_push($task_list1,$row2);
								}
							}
						}
					}
				} else {

					if($row->task_allocated_user_id == get_authenticateUserID() && $row->is_personal == "0"   && $row->task_scheduled_date >= $start_date && $row->task_scheduled_date <= $end_date && $row->task_scheduled_date != '0000-00-00'){
						array_push($task_list1,(array)$row);
					}
				}
			}
		} 
                $task_list = array();
                foreach($date_arr as $date){ 
                    foreach($task_list1 as $list){
                        if($date == $list['task_scheduled_date']){
                            array_push($task_list,$list);
                        }
                    }
                }
                //pr($a); die();
	    $groups = array();
	    $date = array();
		if($task_list){
			$key = $task_list[0]['task_scheduled_date'];
			$key1 = $task_list[0]['task_category_id'];
		
			foreach ($task_list as $item) {
				$key = $item['task_scheduled_date'];
				$key1 = $item['task_category_id'];
		        if (!array_key_exists($key, $groups)) {
		        	$groups[$key][$key1] = array(
		        	
		            	'task_scheduled_date' => $item['task_scheduled_date'],
		                'task_category_id' => $item['task_category_id'],
		                'task_time_estimate' => $item['task_time_estimate'],
		                'task_time_spent' => $item['task_time_spent'],
		                'category_name' => $item['category_name'],
		            );
					
					$groups1[$key][$key1] = array(
		            	'task_scheduled_date' => $item['task_scheduled_date'],
		                'task_category_id' => $item['task_category_id'],
		                'task_time_estimate' => $item['task_time_estimate'],
		                'task_time_spent' => $item['task_time_spent'],
		                'category_name' => $item['category_name'],
		            );
					
		        } else {
	        		if(!isset($groups[$key][$key1]['task_time_estimate'])){
						$groups[$key][$key1]['task_time_estimate'] = '0';
						$groups1[$key][$key1]['task_time_estimate'] = '0';
					}
					if(!isset($groups[$key][$key1]['task_time_spent'])){
						$groups[$key][$key1]['task_time_spent'] = '0';
						$groups1[$key][$key1]['task_time_spent'] = '0';
					}
	        		$groups[$key][$key1]['task_scheduled_date'] = $item['task_scheduled_date'];
					$groups[$key][$key1]['task_category_id'] = $item['task_category_id'];
					$groups[$key][$key1]['category_name'] = $item['category_name'];
					$groups[$key][$key1]['task_time_spent'] = $groups[$key][$key1]['task_time_spent'] + $item['task_time_spent'];
					$groups[$key][$key1]['task_time_estimate'] = $groups[$key][$key1]['task_time_estimate'] + $item['task_time_estimate'];
					$groups1[$key][$key1]['task_scheduled_date'] = $item['task_scheduled_date'];
					$groups1[$key][$key1]['task_category_id'] = $item['task_category_id'];
					$groups1[$key][$key1]['category_name'] = $item['category_name'];
	        		
	        		$groups1[$key][$key1]['task_time_estimate'] = $groups1[$key][$key1]['task_time_estimate'] + $item['task_time_estimate'];
					$groups1[$key][$key1]['task_time_spent'] = $groups1[$key][$key1]['task_time_spent'] + $item['task_time_spent'];
		        }
		        $key++;
			
		    }
			$data['group'] = $groups;
			$data['group1'] = $groups1; 
	    }
		//pr($groups);pr($groups1);die;
		
			return $groups; 
		} else {
			return '0';
		}
		
	}
/**
 * It return values for draw pie chart of category.
 * @param  $task_staus_completed_id
 * @param  $offdays
 * @returns int
 */
function getCategoryforchart($task_staus_completed_id='',$offdays=''){
	
		date_default_timezone_set($this->session->userdata("User_timezone"));
		
		$start_date1 = date('Y-m-d');

		$date_arr = $this->getDateArr($start_date1);
		
                $start_date = reset($date_arr);
		$end_date = end($date_arr);
		
		if($offdays){ $offdays = $offdays; } else { $offdays = get_company_offdays(); }
		if($task_staus_completed_id){ $task_status_completed_id = $task_staus_completed_id; } else { $task_status_completed_id = $this->config->item('completed_id'); }

		$query = $this->db->query("SELECT t.*,tc.category_name FROM tasks t left join task_category tc on t.task_category_id = tc.category_id WHERE t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND t.master_task_id  = 0 AND t.task_company_id = ".$this->session->userdata('company_id')." AND t.is_deleted ='0' ORDER BY t.task_scheduled_date");
		
		//echo $this->db->last_query();die;
                $task_list = array();
		if($query->num_rows()>0){
			$res = $query->result();
			if($res){
				foreach($res as $row){
				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;
					$re_data = monthly_recurrence_logic($row_pass,$start_date,$end_date,$offdays);
					if($re_data){
						foreach($re_data as $row2){
							$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_staus_completed_id);
							if($chk_rec){
								if($chk_rec['task_scheduled_date']>= $start_date && $chk_rec['task_scheduled_date'] <= $end_date && $chk_rec['is_personal'] == "0" && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){

									array_push($task_list,$chk_rec);
								}
							} else {

								if($row2['task_scheduled_date']>= $start_date && $row2['task_scheduled_date'] <= $end_date  && $row2['is_personal'] == "0"  &&  $row2['task_allocated_user_id'] == get_authenticateUserID()){
									array_push($task_list,$row2);
								}
							}
						}
					}
				} else {

					if($row->task_allocated_user_id == get_authenticateUserID() && $row->is_personal == "0"   && $row->task_scheduled_date >= $start_date && $row->task_scheduled_date <= $end_date && $row->task_scheduled_date != '0000-00-00'){
						array_push($task_list,(array)$row);
					}
				}
			}
		}

			//pr($task_list);die;
			//$task_list1 = (array) $task_list;
			//$array =  (array) $yourObject;
			//pr($task_list);die;
			$pids = array();
			foreach ($task_list as $h) {
				//$h = (array)$h;
			    $pids[] = $h['task_category_id'];
			}
				$categories = array_unique($pids);
				//pr($data['categories']);die;
			
			return $categories;
		}else{
			return 0;
		}
		
	}
	
	function get_taskListMobile($type='')
	{
		
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$today = date('Y-m-d');
		
		$task_list = array();
		$task_status_completed_id = $this->config->item('completed_id');
		
		$query = $this->db->query("SELECT * FROM (`tasks`) WHERE task_owner_id !='0' AND task_allocated_user_id != '0' AND master_task_id = '0' AND is_deleted='0' AND task_company_id = ".$this->session->userdata('company_id')." ");
		
		if($query->num_rows()>0){
			$res = $query->result();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$today,$today);
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date'] == $today  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
							$row1 = (array)$row;
							array_push($task_list,$row1);
						} 
					}
					
				}
			}
			date_default_timezone_set("UTC");
			if($type == "task_due_date"){
				$this->array_sort_by_column($task_list, 'task_due_date');
			} else if($type == "task_priority"){
				$this->array_sort_by_column($task_list, 'task_priority');
			} else if($type == "task_status_id"){
				$this->array_sort_by_column($task_list, 'task_status_id');
			} else {}
			$task_list = (object)$task_list;
			
			return $task_list;
		} else {
			return 0;
		}
	}
	
        function getDateArr($start_date,$from=''){
		$date_arr = array();
		if($from == "company" || $from == "companyAjax"){
			$defaults = get_calender_settings($this->session->userdata('company_id'));
		} else {
			$defaults = get_calender_settings_by_user($this->session->userdata('user_id'));
		}

		$day = date('l');
		$i = 0;
		$k = 0;
		while($i<5){
			if(($from == 'ajax' || $from == "companyAjax") && $k=='0'){
				$start_date = date('Y-m-d',strtotime("+1 days",strtotime(str_replace(array("/"," ",","), "-", $start_date))));
				$day = date("l",strtotime(str_replace(array("/"," ",","), "-", $start_date)));
				$k++;
			}
			if($defaults){
				if($defaults->MON_closed == '1'){
					if($day == 'Monday'){
						array_push($date_arr,$start_date);
						$i++;
					}
				}
				if($defaults->TUE_closed == '1'){
					if($day == 'Tuesday'){
						array_push($date_arr,$start_date);
						$i++;
					}
				}
				if($defaults->WED_closed == '1'){
					if($day == 'Wednesday'){
						array_push($date_arr,$start_date);
						$i++;
					}
				}
				if($defaults->THU_closed == '1'){
					if($day == 'Thursday'){
						array_push($date_arr,$start_date);
						$i++;
					}
				}
				if($defaults->FRI_closed == '1'){
					if($day == 'Friday'){
						array_push($date_arr,$start_date);
						$i++;
					}
				}
				if($defaults->SAT_closed == '1'){
					if($day == 'Saturday'){
						array_push($date_arr,$start_date);
						$i++;
					}
				}
				if($defaults->SUN_closed == '1'){
					if($day == 'Sunday'){
						array_push($date_arr,$start_date);
						$i++;
					}
				}
				$start_date = date('Y-m-d',strtotime("+1 days",strtotime(str_replace(array("/"," ",","), "-", $start_date))));
				$day = date("l",strtotime(str_replace(array("/"," ",","), "-", $start_date)));
			} else {

				array_push($date_arr,$start_date);
				$i++;
				$start_date = date('Y-m-d',strtotime(str_replace(array("/"," ",","), "-", $start_date) . ' +1 Weekday'));
			}


		}
		return $date_arr;
	}
        
        function get_default_swimlanes_info($user_id){
		$this->db->select('*');
		$this->db->from('swimlanes');
		$this->db->where('user_id',$user_id);
		$this->db->where('is_default','1');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}
        
        function get_notScheduledTask($completed_id){
		

		$this->db->select("t.*,ts.task_status_name");
		$this->db->from("tasks t");
                $this->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
		$this->db->where("t.task_status_id !=",$completed_id);
		$this->db->where("t.task_owner_id != ","0");
		$this->db->where("t.task_allocated_user_id != ","0");
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where("t.task_allocated_user_id",get_authenticateUserID());
		$this->db->where("t.task_scheduled_date","0000-00-00");
                //$this->db->where("t.task_due_date !=","0000-00-00");
		$this->db->where("t.is_deleted","0");
                $this->db->order_by('t.task_due_date','asc');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /**
         * Create/ register new customer user & send invitaion mail
         * @param type $customer_user_info
         * @return type int
         */
        function insert_customer_user($customer_user_info){
                $code = randomCode();
		$data = array(
                        'first_name' => $customer_user_info['customer_user_first'],
			'last_name' => $customer_user_info['customer_user_last'],
			'email' => $customer_user_info['customer_user_mail'],
			'user_time_zone' => '',
			'staff_level' => '0',
			'is_administrator' => '0',
			'is_owner' => '0',
			'is_manager' => '0',
			'user_status' => 'Pending',
			'company_id' => $this->session->userdata('company_id'),
			'password' => md5($code),
			'email_verification_code' => $code,
			'signup_date' => date('Y-m-d H:i:s'),
			'signup_IP' => $_SERVER['REMOTE_ADDR'],
			'user_default_page' =>'weekly_calendar',
                        'is_first_login'=>'0',
                        'customer_user_id'=>$customer_user_info['parent_customer'],
                        'is_customer_user'=>'1'
		);
		$this->db->insert('users',$data);
		$user_id = $this->db->insert_id();
		/**
                 * create swimlane for customer user
                 */
                $swimlane_data = array(
			'user_id' => $user_id,
			'swimlanes_name' => 'default',
			'swimlanes_desc' => 'default',
			'seq' => '1',
                        'is_default'=>'1',
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('swimlanes',$swimlane_data);
                $default_swimlane_id = $this->db->insert_id();
		/**
                 * insert data in last_remember_search
                 */
		$last_remember_data = array(
			'user_id' => $user_id,
			'sidbar_collapsed'=>'0',
			'kanban_project_id' => 'all',
			'calender_project_id' => 'all',
			'task_status_id' => 'all',
			'due_task' => 'all',
			'kanban_team_user_id' => $user_id,
			'calender_team_user_id' =>$user_id,
			'show_cal_view' => '1',
			'calender_sorting' => '1',
			'last_calender_view' => '1',
			'user_color_id'=>'0'
		);
		$this->db->insert('last_remember_search',$last_remember_data);
		
		/*
                 *create customer user calendar 
                 */
		$calender_data = array(
			'user_id' => $user_id,
                        'MON_hours' => '480',
                        'TUE_hours' => '480',
                        'WED_hours' => '480',
                        'THU_hours' => '480',
                        'FRI_hours' => '480',
                        'SAT_hours' => '0',
                        'SUN_hours' => '0',
                        'MON_closed' => '1',
                        'TUE_closed' => '1',
                        'WED_closed' => '1',
                        'THU_closed' => '1',
                        'FRI_closed' => '1',
                        'SAT_closed' => '0',
                        'SUN_closed' => '0'
		);
		$this->db->insert('default_calendar_setting',$calender_data);
		
                /**
                 * create customer user colors
                 */
		$colors = get_colors();
		if($colors){
			$i = 1;
			foreach($colors as $col){
				$color_data = array(
					'color_id' => $col->color_id,
					'user_id' => $user_id,
					'color_name' => $col->color_name,
					'name' => $col->color_name,
					'color_code' => $col->color_code,
					'outside_color_code' => $col->outside_color_code,
					'seq' => $i,
					'status' => 'Active',
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('user_colors',$color_data);
				$i++;
			}
		}
                
                /**
                 * Default task created by schedullo
                 */
                $json_path = base_url().'default/json/By_default_task.json';
		$file = file_get_contents($json_path);
                $default_task = json_decode($file);
                $task_status = get_taskStatus($this->session->userdata('company_id'),'Active');   
                $admin_color = get_colors_admin($user_id);
                $default_steps = array(
                                "1" => "Tick the box to complete the step",
                                "2" => "Step 2",
                                "3" => "Step 3"
                );
                $i = 1;
                foreach ($default_task as $task){
                    
                    foreach ($task_status as $status){
                            if($task->task_status == $status->task_status_name){
                                $task->task_status_id =$status->task_status_id;
                            }
                    }
                    
                    $monday= date("Y-m-d",strtotime('monday this week'));
                    $tuesday= date("Y-m-d",strtotime($monday . "+1 days"));
                    $wednesday = date("Y-m-d",strtotime($monday . "+2 days"));
                    if($task->task_due_date == 'Monday'){
                        $task->task_due_date = $monday;
                        $task->task_schedule_date = $monday;
                    }elseif($task->task_due_date == 'Tuesday'){
                        $task->task_due_date = $tuesday;
                        $task->task_schedule_date = $tuesday;
                    }else{
                        $task->task_due_date = $wednesday;
                        $task->task_schedule_date = $wednesday;
                    }
                    $data = array(
				'task_company_id' => $this->session->userdata('company_id'),
				'master_task_id' => '0',
				'task_title' => $task->task_title,
                                'task_description' => $task->task_description,
				'task_priority' => $task->task_priority,
				'task_due_date' => $task->task_due_date,
				'task_scheduled_date' => $task->task_schedule_date,
				'task_orig_scheduled_date' => '0000-00-00',
				'task_orig_due_date' => '0000-00-00',
				'task_owner_id' => $user_id,
				'task_allocated_user_id' => $user_id,
				'task_status_id' => $task->task_status_id,
				'subsection_id' => '0',
				'section_id' => '0',
				'task_project_id' => '0',
                                'task_time_estimate'=>$task->task_estimate_time,
                                'task_time_spent' => $task->task_spent_time,
				'task_added_date' => date('Y-m-d H:i:s'),
                                );
						
		    $this->db->insert('tasks',$data);
                    $task_id = $this->db->insert_id();
                    foreach($admin_color as $color){
                        if($task->task_color == $color->name){
                            $task->task_color = $color->user_color_id;
                         }
                     }
                     
                    $data1 = array(
                                    "user_id"=>$user_id,
                                    "task_id"=>$task_id,
                                    "swimlane_id"=>$default_swimlane_id,
                                    "color_id"=>$task->task_color,
                                    "calender_order"=> $i,
                                    "kanban_order"=>$i,
                                    "task_ex_pos"=>'1'
                                    
                    );
                    $this->db->insert('user_task_swimlanes',$data1); 
                    
                    if($task->steps == 'TRUE'){
                        foreach($default_steps as $key => $value){
						$step_data = array(
							'task_id' => $task_id,
							'step_title' => $value,
							'step_added_by' => $user_id,
							'is_completed' => '0',
							'step_sequence' => $key,
							'step_added_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_steps',$step_data);
                    }
                    }
                    if($task->comment == 'TRUE'){
                        $data2 = array(
                                    'task_comment' => "First comment",
                                    'task_id' => $task_id,
                                    'project_id' => '0',
                                    'comment_addeby' => $user_id,
                                    'comment_added_date' => date('Y-m-d H:i:s')
                                    );

                        $this->db->insert('task_and_project_comments',$data2);
                    }
                    
                    
                    $i++;
                }
                /**
                 * send invitation mail to customer user
                 */
                        $email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='customer user invitation'");
			$email_temp=$email_template->row();	
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;
			
			$email_subject=$email_temp->subject;				
			$email_message=$email_temp->message;
			
			$data_pass = base64_encode($user_id."1@1".$code."1@1NewUser");
			
			$activation_link = "<a href='".base_url()."home/activate_customer_user/".$data_pass."' target='_blank'>Activation link</a>";
			
			$user_name = $customer_user_info['customer_user_first'].' '.$customer_user_info['customer_user_last'];
			$company_name = getCompanyName($this->session->userdata('company_id'));
			$email_to = $customer_user_info['customer_user_mail'];
			
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{company_name}',$company_name,$email_message);
			$email_message=str_replace('{activation_link}',$activation_link,$email_message);
			$email_message=str_replace('{email}',$email_to,$email_message);
                        
			$str=$email_message;
                        $sandgrid_id=$email_temp->sandgrid_id;
                        $sendgriddata = array('subject'=>'Customer Invitation',
                                    'data'=>array('user_name'=>$user_name,'company_name'=>$company_name,'activation_link'=>$activation_link,"email"=>$email_to));
                        if($sandgrid_id){
                            $str = json_encode($sendgriddata);
                        }
                        $mail_data = array(
                                      "email_to"=>$email_to,
                                      "email_from"=>$email_address_from,
                                      "email_reply"=>$email_address_reply,
                                      "email_subject"=>$email_subject,
                                      "message"=>$str,
                                      "attach"=>'',
                                      "status"=>'pending',
                                      "date"=>date('Y-m-d H:i:s'),
                                      "sandgrid_id"=>$sandgrid_id
                                      );
                        $this->db->insert('email_queue',$mail_data);
                       // email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                return $user_id;
        }
        /**
         * Update Customer user info.
         * @param type $customer_user_info
         * @return type int
         */
        function update_customer_user($customer_user_info){
                $data = array(
                        'first_name' => $customer_user_info['customer_user_first'],
			'last_name' => $customer_user_info['customer_user_last'],
			'email' => $customer_user_info['customer_user_mail'],
			'customer_user_id'=>$customer_user_info['parent_customer'],
                );
                $this->db->where('user_id',$customer_user_info['customer_user_id']);
		$this->db->update('users',$data);
                
		return $customer_user_info['customer_user_id'];
        }
}

?>
