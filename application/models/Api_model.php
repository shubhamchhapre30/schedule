<?php

class Api_model extends CI_Model{
    function Home_model()
    {
        parent::__construct();	
    }
    
        /**
         * This method is checked user authentication in db.
         * @param type $company_id
         * @param type $email
         * @param type $password
         * @return int
         */
        function check_login1($email,$password){
            $query = $this->db->select("u.company_id,c.company_name,u.first_name,u.last_name,u.user_id")
							->from("users u")
							->join("company c","c.company_id = u.company_id",'left')
							->where("u.email",$email)
                                                        ->where("u.password",md5($password))
							->where("u.is_deleted","0")
							->where("u.user_status","Active")
							->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
        }
        
        /**
         * This method is used for save new task in db.
         * @param type $owner_id
         * @param type $company_id
         * @param type $task_title
         * @param type $task_description
         * @param type $task_due_date
         * @param type $task_scheduled_date
         * @param type $task_status
         * @param type $task_priority
         * @param type $task_allocated_user_id
         * @param type $task_project_id
         * @param type $task_watch_list
         * @param type $is_personal
         * @param type $task_time_estimate
         * @param type $task_actual_time
         * @return type $task_id
         */
        function saveTask($owner_id,$company_id,$task_title,$task_description,$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time,$outlook_task_id,$gmail_task_id)
        {
            
            if($task_project_id){
                $subsection_id=get_project_subsection_id($task_project_id);
            }else
            {
             $subsection_id='0';   
            }
            
            /* insert new task inn db */
            
		$data = array(
			'task_company_id' => $company_id,
			'task_title' => $task_title,
			'task_description' => $task_description,
			'is_personal' => $is_personal,
			'task_priority' => $task_priority,
			'task_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
			'task_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_scheduled_date))),
			'task_orig_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_scheduled_date))),
			'task_orig_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
			'task_time_spent' => $task_actual_time,
			'task_time_estimate' => $task_time_estimate,
			'task_owner_id' =>$owner_id,
			'task_allocated_user_id' => $task_allocated_user_id,
			'task_status_id' => $task_status,
			'task_project_id' => $task_project_id,
                        'subsection_id' =>$subsection_id,   
			'task_added_date' => date('Y-m-d H:i:s'),
                        'outlook_task_id' => $outlook_task_id,
                        'gmail_task_id' => $gmail_task_id
		);
		
		$this->db->insert('tasks',$data);
		$task_id = $this->db->insert_id();
                /* add task in watch list*/
                if($task_watch_list=='1'){
                    $data=array(
                                'task_id'=>$task_id,
                                'user_id'=>$owner_id
                                );
                    $this->db->insert('my_watch_list',$data);
                }
                
                /**
                 * insert task history in db.
                 */
                $history_data = array(
					'histrory_title' => 'Task created.',
					'history_added_by' => $owner_id,
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
                                
                                
                  /**
                    * get user details
                    */
            
                $authenticate_user_details=get_user_name($owner_id);
               /**
                  * check allocationi for sending notification & mail.
                  * if user is not authenticated user then it send mail & notification .
                  */               
                             
                if($task_allocated_user_id != $owner_id){
								$notification_data = array(
									'task_id' => $task_id,
									'project_id' => get_project_id_from_task_id($task_id),
									'notification_text' => $authenticate_user_details->first_name." ".$authenticate_user_details->last_name.' has assigned the task "'.$task_title.'" to you',
									'notification_user_id' => $task_allocated_user_id,
									'notification_from' =>$owner_id,
									'is_read' => '0',
									'is_allocation_notification' => '1',
									'date_added' => date("Y-m-d H:i:s")
								);
								$this->db->insert('task_notification',$notification_data);
							}
		
							$chk_exist = chk_swim_exist($task_id,$task_allocated_user_id);
							if($chk_exist == '0'){
								$user_swimlane = array(
									'user_id' => $task_allocated_user_id,
									'task_id' => $task_id,
									'swimlane_id' => get_default_swimlane($task_allocated_user_id),
									'kanban_order' => 1,
									//'calender_order' => get_user_last_calnder_order($task_allocated_user_id,$old_task_detail['task_scheduled_date']) + 1
								);
		
								$this->db->insert('user_task_swimlanes',$user_swimlane);
                                                                
                                                                
							}
							
							if($task_allocated_user_id != $owner_id){
								/*** send email to task allocated user ****/
								$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
								$email_temp=$email_template->row();
								$email_address_from=$email_temp->from_address;
								$email_address_reply=$email_temp->reply_address;
			
								$email_subject=$email_temp->subject;
								$email_message=$email_temp->message;
			
								$allocated_user_info = get_user_info($task_allocated_user_id);
								$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
								/******/
								$task_name = $task_title;
								$owner_name = usernameById($owner_id);
								
									$task_due_date = date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date)));
								
			
								
								if($task_description){
									$task_description = $task_description;
								} else {
									$task_description = 'N/A';
								}
								
								$project_id = get_project_id_from_task_id($task_id);
								if($project_id){
									$project_name = get_project_name($project_id);
								} else {
									$project_name = 'N/A';
								}
								
								$email_to = $allocated_user_info->email;
								$subscription_link = site_url();
			
								$email_subject=str_replace('{break}','<br/>',$email_subject);
								$email_subject=str_replace('{user_name}',$allocate_user_name,$email_subject);
								$email_subject=str_replace('{task_name}',$task_name,$email_subject);
								$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
								$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
								$email_subject=str_replace('{project_name}',$project_name,$email_subject);
								$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
			
								$email_message=str_replace('{break}','<br/>',$email_message);
								$email_message=str_replace('{user_name}',$allocate_user_name,$email_message);
								$email_message=str_replace('{task_name}',$task_name,$email_message);
								$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
								$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
								$email_message=str_replace('{task_description}',$task_description,$email_message);
								$email_message=str_replace('{project_name}',$project_name,$email_message);
								$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
			
								$str=$email_message;
                                                                
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
                                                                        "date"=>date('Y-m-d H:i:s')
                                                                        );
                                                                  $this->db->insert('email_queue',$mail_data);
                                                                
								//email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
							}                
                return $task_id;
        }
        
        /**
         * It's used for update existing task in db and return task id.
         * @param type $task_id
         * @param type $owner_id
         * @param type $company_id
         * @param type $task_title
         * @param type $task_description
         * @param type $task_due_date
         * @param type $task_scheduled_date
         * @param type $task_status
         * @param type $task_priority
         * @param type $task_allocated_user_id
         * @param type $task_project_id
         * @param type $task_watch_list
         * @param type $is_personal
         * @param type $task_time_estimate
         * @param type $task_actual_time
         * @return type task_id
         */
        
        function updateTaskInfo($task_id,$owner_id,$company_id,$task_title,$task_description,$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time)
        {
            if($task_project_id){
                $subsection_id=get_project_subsection_id($task_project_id);
            }else
            {
             $task_project_id='0';   
             $subsection_id='0';   
            }
            $chk_exist = chk_task_exists($task_id);
            if($chk_exist=='0'){
               $main_id = explode("_", $task_id);
               $master_task_id = $main_id[1];
               $task = get_task_info($master_task_id,$company_id);
               $data = array(
			'task_company_id' => $company_id,
                        'master_task_id'=>$master_task_id,
			'task_title' => isset($task_title)?$task_title:$task['task_title'],
			'task_description' => isset($task_description)?$task_description:$task['task_description'],
			'is_personal' => isset($is_personal)?$is_personal:$task['is_personal'],
			'task_priority' => isset($task_priority)?$task_priority:$task['task_priority'],
			'task_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
			'task_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_scheduled_date))),
			'task_orig_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_scheduled_date))),
			'task_orig_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
			'task_time_spent' => isset($task_actual_time)?$task_actual_time:$task['task_time_spent'],
			'task_time_estimate' => isset($task_time_estimate)?$task_time_estimate:$task['task_time_estimate'],
			'task_owner_id' =>$owner_id,
			'task_allocated_user_id' => isset($task_allocated_user_id)?$task_allocated_user_id:$task['task_allocated_user_id'],
			'task_status_id' => isset($task_status)?$task_status:$task['task_status_id'],
			'task_project_id' => isset($task_project_id)?$task_project_id:$task['task_project_id'],
                        'subsection_id' =>$subsection_id,   
			'task_added_date' => date('Y-m-d H:i:s')
		);
                $this->db->insert('tasks',$data);
		$task_id = $this->db->insert_id();
                
                $steps = get_task_steps($master_task_id);
				if($steps){
					$i = 1;
					foreach($steps as $step){
						$step_data = array(
							'task_id' => $task_id,
							'step_title' => $step['step_title'],
							'step_added_by' => $step['step_added_by'],
							'is_completed' => $step['is_completed'],
							'step_sequence' => $i,
							'step_added_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_steps',$step_data);
						$i++;
					}
				}
                                
                 /**
                 * insert task history in db.
                 */
                $history_data = array(
					'histrory_title' => 'Task created.',
					'history_added_by' => $owner_id,
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
                                                
                
            }
            else{
            /* update  task in db */
            
		$data = array(
			'task_title' => $task_title,
			'task_description' => $task_description,
			'is_personal' => $is_personal,
			'task_priority' => $task_priority,
			'task_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
			'task_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_scheduled_date))),
			'task_orig_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_scheduled_date))),
			'task_orig_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
			'task_time_spent' => $task_actual_time,
			'task_time_estimate' => $task_time_estimate,
			'task_owner_id' =>$owner_id,
			'task_allocated_user_id' => $task_allocated_user_id,
			'task_status_id' => $task_status,
			'task_project_id' => $task_project_id,
                        'subsection_id' =>$subsection_id,   
			
		);
                $this->db->where('task_id',$task_id);
		$this->db->where('task_company_id',$company_id);
		$this->db->update('tasks',$data);
                
                 /**
                 * insert task history in db.
                 */
                $history_data = array(
					'histrory_title' => 'Task updated.',
					'history_added_by' => $owner_id,
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
		$this->db->insert('task_history',$history_data);
                                
            }
                /* add task in watch list*/
                $watch_list= chk_task_watchlist($task_id,$owner_id);
                if($task_watch_list!=$watch_list && $task_watch_list=='1'){
                    $data=array(
                                'task_id'=>$task_id,
                                'user_id'=>$owner_id
                                );
                    $this->db->insert('my_watch_list',$data);
                }
                else if($task_watch_list!=$watch_list && $task_watch_list=='0'){ 
                    $data=array(
                                'task_id'=>$task_id,
                                'user_id'=>$owner_id
                                );
                    $this->db->delete('my_watch_list',$data);
                }else{}
                
               
                                
                  /**
                    * get user details
                    */
            
                $authenticate_user_details=get_user_name($owner_id);
               
                           
                                
                 /**
                  * check allocationi for sending notification & mail.
                  * if user is not authenticated user then it send mail & notification .
                  */               
                             
                if($task_allocated_user_id != $owner_id){
								$notification_data = array(
									'task_id' => $task_id,
									'project_id' => get_project_id_from_task_id($task_id),
									'notification_text' => $authenticate_user_details->first_name." ".$authenticate_user_details->last_name.' has assigned the task "'.$task_title.'" to you',
									'notification_user_id' => $task_allocated_user_id,
									'notification_from' =>$owner_id,
									'is_read' => '0',
									'is_allocation_notification' => '1',
									'date_added' => date("Y-m-d H:i:s")
								);
								$this->db->insert('task_notification',$notification_data);
							}
		
							$chk_exist = chk_swim_exist($task_id,$task_allocated_user_id);
							if($chk_exist == '0'){
								$user_swimlane = array(
									'user_id' => $task_allocated_user_id,
									'task_id' => $task_id,
									'swimlane_id' => get_default_swimlane($task_allocated_user_id),
									'kanban_order' => 1,
									//'calender_order' => get_user_last_calnder_order($task_allocated_user_id,$old_task_detail['task_scheduled_date']) + 1
								);
		
								$this->db->insert('user_task_swimlanes',$user_swimlane);
                                                                
                                                                
							}
							
							if($task_allocated_user_id != $owner_id){
								/*** send email to task allocated user ****/
								$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
								$email_temp=$email_template->row();
								$email_address_from=$email_temp->from_address;
								$email_address_reply=$email_temp->reply_address;
			
								$email_subject=$email_temp->subject;
								$email_message=$email_temp->message;
			
								$allocated_user_info = get_user_info($task_allocated_user_id);
								$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
								/******/
								$task_name = $task_title;
								$owner_name = usernameById($owner_id);
								
									$task_due_date = date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date)));
								
			
								
								if($task_description){
									$task_description = $task_description;
								} else {
									$task_description = 'N/A';
								}
								
								$project_id = get_project_id_from_task_id($task_id);
								if($project_id){
									$project_name = get_project_name($project_id);
								} else {
									$project_name = 'N/A';
								}
								
								$email_to = $allocated_user_info->email;
								$subscription_link = site_url();
			
								$email_subject=str_replace('{break}','<br/>',$email_subject);
								$email_subject=str_replace('{user_name}',$allocate_user_name,$email_subject);
								$email_subject=str_replace('{task_name}',$task_name,$email_subject);
								$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
								$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
								$email_subject=str_replace('{project_name}',$project_name,$email_subject);
								$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
			
								$email_message=str_replace('{break}','<br/>',$email_message);
								$email_message=str_replace('{user_name}',$allocate_user_name,$email_message);
								$email_message=str_replace('{task_name}',$task_name,$email_message);
								$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
								$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
								$email_message=str_replace('{task_description}',$task_description,$email_message);
								$email_message=str_replace('{project_name}',$project_name,$email_message);
								$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
			
								$str=$email_message;
                                                                
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
                                                                        "date"=>date('Y-m-d H:i:s')
                                                                        );
                                                                  $this->db->insert('email_queue',$mail_data);
                                                                
								//email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
							}                
                return $task_id;
        }
        
        /**
         * This method is used for saving new comment in db.
         * @param type $task_id
         * @param type $user_id
         * @param type $task_comment
         */
        function saveComment($task_id,$user_id,$task_comment,$user_timezone){
            
                    /* Get project id using task_id */
                   $project_id = get_project_id_from_task_id($task_id);
                   /*Insert query for adding comment*/
                   $data = array(
					'task_comment' => $task_comment,
					'task_id' => $task_id,
					'project_id' => $project_id,
					'comment_addeby' => $user_id,
					'comment_added_date' => date('Y-m-d H:i:s')
				);

				$this->db->insert('task_and_project_comments',$data);
				$id = $this->db->insert_id();

				/* insert task history */
                                $history_data = array(
					'histrory_title' => 'Task comment added',
					'history_desc' => $task_comment,
					'history_added_by' => $user_id,
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
                                
                                $get_comment = get_task_comments_info($task_id,$user_timezone);
//                                 print_r($get_comment); die();
                  return $get_comment[0];              
        }
        
        /**
         * This method is used for deleting task using api.
         * @param type $task_id
         */
        
         function deleteTask($task_id,$company_id,$user_id,$due_date){
                /*
                 * check task existance
                 */
		$task_exists = chk_task_exists($task_id);
                if($task_exists == '0'){
                        $main_id = explode("_", $task_id);
                        $master_task_id = $main_id[1];
                        $task = get_task_info($master_task_id,$company_id);
                        $data = array(
                                'task_company_id' => $company_id,
                                'master_task_id'=>$master_task_id,
                                'task_title' => $task['task_title'],
                                'task_description' => $task['task_description'],
                                'is_personal' => $task['is_personal'],
                                'task_priority' => $task['task_priority'],
                                'task_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $due_date))),
                                'task_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $due_date))),
                                'task_orig_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $due_date))),
                                'task_orig_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $due_date))),
                                'task_time_spent' => $task['task_time_spent'],
                                'task_time_estimate' => $task['task_time_estimate'],
                                'task_owner_id' =>$user_id,
                                'task_allocated_user_id' => $task['task_allocated_user_id'],
                                'task_status_id' => $task['task_status_id'],
                                'task_project_id' => $task['task_project_id'],
                                'task_added_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('tasks',$data);
                        $task_id = $this->db->insert_id();

                        $steps = get_task_steps($master_task_id);
                                        if($steps){
                                                $i = 1;
                                                foreach($steps as $step){
                                                        $step_data = array(
                                                                'task_id' => $task_id,
                                                                'step_title' => $step['step_title'],
                                                                'step_added_by' => $step['step_added_by'],
                                                                'is_completed' => $step['is_completed'],
                                                                'step_sequence' => $i,
                                                                'step_added_date' => date('Y-m-d H:i:s')
                                                        );
                                                        $this->db->insert('task_steps',$step_data);
                                                        $i++;
                                                }
                                        }

                         /**
                         * insert task history in db.
                         */
                        $history_data = array(
                                                'histrory_title' => 'Task created.',
                                                'history_added_by' => $user_id,
                                                'task_id' => $task_id,
                                                'date_added' => date('Y-m-d H:i:s')
                                        );
                                        $this->db->insert('task_history',$history_data);


		}
                /*
                 * with task_id it update tables 
                 */
		if($task_id){
			$user_task_swimlane = array("is_deleted"=>"1");
			$this->db->where("task_id",$task_id);
			$this->db->update("user_task_swimlanes",$user_task_swimlane);

			$task_history = array("is_deleted"=>"1");
			$this->db->where("task_id",$task_id);
			$this->db->update("task_history",$task_history);

			$task_notification = array("is_deleted"=>"1");
			$this->db->where("task_id",$task_id);
			$this->db->update("task_notification",$task_notification);

			$task_steps = array("is_deleted"=>"1");
			$this->db->where("task_id",$task_id);
			$this->db->update("task_steps",$task_steps);
			
			
			$task_files = array("is_deleted"=>"1");
			$this->db->where("task_id",$task_id);
			$this->db->update("task_and_project_files",$task_files);
			
			$multiIds = multiAllocationTaskIds($task_id);
			if($multiIds){
				foreach($multiIds as $mId){
					$task_steps2 = array("is_deleted"=>"1");
					$this->db->where("task_id",$mId->task_id);
					$this->db->update("task_steps",$task_steps2);
					
					$task_files = array("is_deleted"=>"1");
					$this->db->where("task_id",$mId->task_id);
					$this->db->update("task_and_project_files",$task_files);
				}
			}

			$task_comments = array("is_deleted"=>"1");
			$this->db->where("task_id",$task_id);
			$this->db->update("task_and_project_comments",$task_comments);

			$task_data = array("is_deleted"=>"1");
			//$this->db->where("task_id",$task_id);
			$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
			$this->db->update("tasks",$task_data);

			
			return "done";
		}
         }
         
         function get_user_list($company_id){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('company_id',$company_id);
		$this->db->where('is_deleted','0');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        
        function get_user_details($user_id,$company_id){
            /**
             * select data query
             */
		$this->db->select('u.first_name,u.last_name,u.email,u.user_id,u.profile_image,u.staff_level,u.user_time_zone,u.is_administrator,u.is_owner,u.is_manager,u.user_status,u.user_default_page,u.default_color,u.daily_email_summary,d.MON_hours,d.TUE_hours,d.WED_hours,d.THU_hours,d.FRI_hours,d.SAT_hours,d.SUN_hours,d.MON_closed,d.TUE_closed,d.WED_closed,d.THU_closed,d.FRI_closed,d.SAT_closed,d.SUN_closed');
		$this->db->from('users u');
		$this->db->join('default_calendar_setting d','d.user_id = u.user_id','left');
		$this->db->where('u.user_id',$user_id);
                $this->db->where('company_id',$company_id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}
        
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
			return '';
		}
	}
        
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
			return '';
		}
	}
        
        function get_project_list($company_id){
            $this->db->select('*');
            $this->db->from('project');
            $this->db->where('company_id',$company_id);
            $this->db->where('is_deleted','0');
            $query = $this->db->get();
            if($query->num_rows()>0){
                return $query->result();
            } else{
                return 0;
            }
        }
        
        function get_project_info($company_id,$project_id){
            $this->db->select('*');
            $this->db->from('project');
            $this->db->where('project_id',$project_id);
            $this->db->where('company_id',$company_id);
            $this->db->where('is_deleted','0');
            $query = $this->db->get();
            
            if($query->num_rows()>0){
                return $query->result();
            }else{
                return 0;
            }
            
            
        }
        
        function check_project_existance($company_id,$project_id){
            $this->db->select('*');
            $this->db->from('project');
            $this->db->where('company_id',$company_id);
            $this->db->where('project_id',$project_id);
            $this->db->where('is_deleted','0');
            $query = $this->db->get();
            if($query->num_rows()>0){
                return 1;
            }else{
                return 0;
            }
        }
}

