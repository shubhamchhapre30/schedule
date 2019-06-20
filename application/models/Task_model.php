<?php
/**
 * This class is used for database interaction with task class,this class have many functions.this function access data from db and returns calling method.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/

class Task_model extends CI_Model{

    /**
        * It default constuctor which is called when task_model object is initialzied.It load base class methods & variables.
        * @returns void
        */
	function Task_model(){
            /*
             * call base class constructor
             */
		 parent::__construct();
	}
	/**
         * This function is used for save task in db .This function checks task_id than run if part otherwise it will run else part of code.It function take five paramenters for check condition and save data,send mail,manage task_history in DB.
         * @param  $name
         * @param  $value
         * @param  $redirect_page
         * @param  $task_scheduled_date
         * @param  $task_id
         * @returns int
         */
	function saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$task_id=''){ 
		
		if($task_id =="" || strpos($task_id, 'child') !== false){
			$unserializedData = array();
			parse_str($value,$unserializedData);
                        //print_r($unserializedData);
                        
                            /* check values of unserializeddata  array for store */
			if($unserializedData){
				if($unserializedData['task_orig_scheduled_date'] && $unserializedData['task_orig_scheduled_date']!='0000-00-00'){ $orig_scheduled_date = change_date_format($unserializedData['task_orig_scheduled_date']);} else { $orig_scheduled_date = '';}
				if($unserializedData['task_orig_due_date'] && $unserializedData['task_orig_due_date']!='0000-00-00'){ $orig_due_date = change_date_format($unserializedData['task_orig_due_date']);} else { $orig_due_date = '';}
				if($unserializedData['task_scheduled_date'] && $unserializedData['task_scheduled_date']!='0000-00-00'){ $scheduled_date = change_date_format($unserializedData['task_scheduled_date']); } else { $scheduled_date = ''; }
				
				$due_date = $scheduled_date;
				//for project
				if($unserializedData['task_section_id']!=''){$section = $unserializedData['task_section_id'];}else{$section = '0';}
				if($unserializedData['task_subsection_id']!=''){$subsection = $unserializedData['task_subsection_id'];}else{$subsection = '0';}
				if($unserializedData['general_project_id']!=''){$project_id = $unserializedData['general_project_id'];}else{$project_id = '0';}
				if($project_id != '0'){
					$task_order = get_task_order_by_project($project_id,$section,$subsection);
				} else {
					$task_order = "0";
				}
				
				if($unserializedData['old_task_status_id']){
					$task_status_id = $unserializedData['old_task_status_id'];
				} else {
					$task_status_id = get_task_status_id_by_name("Ready");
				}
				if(isset($unserializedData['task_color_id']) && $unserializedData['task_color_id']){
					$color_id = $unserializedData['task_color_id'];
				} else {
					$color_id = get_default_color(get_authenticateUserID());
				}
				
				if($unserializedData['old_task_due_date']){
					$old_due_date = change_date_format($unserializedData['old_task_due_date']);
				} else {
					$old_due_date = '';
				}
				if(strpos($task_id, 'child') !== false){
					$old_task_data = get_task_detail($unserializedData['master_task_id']);
					$allocated_to = $old_task_data['task_allocated_user_id'];
                                        //echo "if condition id".$allocated_to; 
                                        /* check name and insert data in tasks table */
					if($name == "task_title" || $name == "task_priority" || $name == "task_status_id" || $name == "task_description"){
						$data = array(
                                                        'task_description'=>$unserializedData['task_description'],
							'task_company_id' => $this->session->userdata('company_id'),
							'master_task_id' => $unserializedData['master_task_id'],
							'task_title' => urldecode($unserializedData['task_title']),
							'task_priority' => $unserializedData['task_priority'],
							'task_due_date' => $due_date,
							'task_scheduled_date' => $scheduled_date,
							'task_orig_scheduled_date' => $orig_scheduled_date,
							'task_orig_due_date' => $orig_due_date,
							'task_owner_id' => $unserializedData['task_owner_id'],
							'task_allocated_user_id' => $allocated_to,
							'task_status_id' => $task_status_id,
							'subsection_id' => $subsection,
							'section_id' => $section,
							'task_order' => $task_order,
							'task_project_id' => $project_id,
							'task_added_date' => date('Y-m-d H:i:s'),
                                                        'customer_id' => isset($unserializedData['customer_id'])?$unserializedData['customer_id']:$unserializedData['allocated_customer_id'],
                                                        'cost_per_hour'=> $old_task_data['cost_per_hour'],
                                                        'cost'=>$old_task_data['cost'],
                                                        'charge_out_rate'=>$old_task_data['charge_out_rate'],
                                                        'estimated_total_charge'=>$old_task_data['estimated_total_charge'],
                                                        'actual_total_charge'=>$old_task_data['actual_total_charge']
						);
						
						$this->db->insert('tasks',$data);
						$task_id = $this->db->insert_id();
					} else if($name == "task_color_id" || $name == "task_swimlane_id" || $name == "watch_list"){
						$data = array(
							'task_company_id' => $this->session->userdata('company_id'),
							'master_task_id' => $unserializedData['master_task_id'],
							'task_title' => $unserializedData['task_title'],
							'task_priority' => $unserializedData['task_priority'],
							'task_due_date' => $due_date,
							'task_scheduled_date' => $scheduled_date,
							'task_orig_scheduled_date' => $orig_scheduled_date,
							'task_orig_due_date' => $orig_due_date,
							'task_owner_id' => $unserializedData['task_owner_id'],
							'task_allocated_user_id' => $allocated_to,
							'task_status_id' => $task_status_id,
							'subsection_id' => $subsection,
							'section_id' => $section,
							'task_order' => $task_order,
							'task_project_id' => $project_id,
							'task_added_date' => date('Y-m-d H:i:s'),
                                                        'customer_id' => isset($unserializedData['customer_id'])?$unserializedData['customer_id']:$unserializedData['allocated_customer_id'],
                                                        'cost_per_hour'=> $old_task_data['cost_per_hour'],
                                                        'cost'=>$old_task_data['cost'],
                                                        'charge_out_rate'=>$old_task_data['charge_out_rate'],
                                                        'estimated_total_charge'=>$old_task_data['estimated_total_charge'],
                                                        'actual_total_charge'=>$old_task_data['actual_total_charge']
						);
						
						$this->db->insert('tasks',$data);
						$task_id = $this->db->insert_id();
                                                if($name == "watch_list"){
                                                    $value = isset($_POST['sub_val'])?$_POST['sub_val']:'';
                                                    if($value == 1)
                                                    {
                                                        $watch_data = array('task_id'=>$task_id, 'user_id'=>get_authenticateUserID());
                                                        $this->db->insert('my_watch_list',$watch_data);
                                                    }  else {
                                                        $this->db->delete('my_watch_list',array('task_id'=>$task_id, 'user_id'=>get_authenticateUserID()));
                                                    }
                                                }
						
					} else {
						if($name == "is_personal" || $name == "locked_due_date"){
							if($value){
								$value = "1";
							} else {
								$value = "0";
							}
						} else if($name == "task_due_date"){
							if($unserializedData['task_due_date']){
								$due_date = change_date_format($unserializedData['task_due_date']);
							} else {
								 $due_date = '';
							}
							if($unserializedData['task_scheduled_date']){
								$scheduled_date = change_date_format($unserializedData['task_scheduled_date']);
							} else {
								$scheduled_date = $due_date;
							}
						}
						$data = array(
							'task_company_id' => $this->session->userdata('company_id'),
							$name => $value,
							'task_title' => $unserializedData['task_title'],
							'master_task_id' => $unserializedData['master_task_id'],
							'task_priority' => $unserializedData['task_priority'],
							'task_due_date' => $due_date,
							'task_scheduled_date' => $scheduled_date,
							'task_orig_scheduled_date' => $orig_scheduled_date,
							'task_orig_due_date' => $orig_due_date,
							'task_owner_id' => $unserializedData['task_owner_id'],
							'task_allocated_user_id' => $allocated_to,
							'task_status_id' => $task_status_id,
							'subsection_id' => $subsection,
							'section_id' => $section,
							'task_order' => $task_order,
							'task_project_id' => $project_id,
                                                        'customer_id' => isset($unserializedData['customer_id'])?$unserializedData['customer_id']:$unserializedData['allocated_customer_id'],
							'task_added_date' => date('Y-m-d H:i:s')
                                                );
						$this->db->insert('tasks',$data);
						$task_id = $this->db->insert_id();
					} 
                                } else { 
					$old_task_data = "";
					/* check redirect page */
					if($unserializedData['redirect_page'] == "from_kanban"){
						$allocated_to = $this->session->userdata("Temp_kanban_user_id");
					} else if($unserializedData['redirect_page'] == "from_calendar" || $unserializedData['redirect_page'] == "NextFiveDay" || $unserializedData['redirect_page'] == "FiveWeekView" || $unserializedData['redirect_page'] == "weekView"){
						$allocated_to = $this->session->userdata("Temp_calendar_user_id");
					} else {
						$allocated_to = get_authenticateUserID();
					}
                                        if($allocated_to=='#'){
                                            $allocated_to= get_authenticateUserID();
                                        }
					//echo "else condition id".$allocated_to; 
					$data = array(
						'task_company_id' => $this->session->userdata('company_id'),
						'task_title' => $unserializedData['task_title'],
						'task_priority' => $unserializedData['task_priority'],
						'master_task_id' => $unserializedData['master_task_id'],
						'task_due_date' => $due_date,
						'task_scheduled_date' => $scheduled_date,
						'task_orig_scheduled_date' => $orig_scheduled_date,
						'task_orig_due_date' => $orig_due_date,
						'task_owner_id' => $unserializedData['task_owner_id'],
						'task_allocated_user_id' => $allocated_to,
						'task_status_id' => $task_status_id,
						'subsection_id' => $subsection,
						'section_id' => $section,
						'task_order' => $task_order,
						'task_project_id' => $project_id,
                                                'customer_id' => isset($unserializedData['customer_id'])?$unserializedData['customer_id']:$unserializedData['allocated_customer_id'],
						'task_added_date' => date('Y-m-d H:i:s')
					);
					
					$this->db->insert('tasks',$data);
					$task_id = $this->db->insert_id();
                                        
                                        $charge_out_rate = get_charge_out_rate($task_id);
                                        $base_employee_rate = get_user_cost_per_hour($allocated_to);
                                        
                                        $rate_update = array(
                                            "cost_per_hour"=>$base_employee_rate,
                                            "charge_out_rate"=>$charge_out_rate,
                                        );
                                        $this->db->where('task_id',$task_id);
                                        $this->db->update('tasks',$rate_update);
                                        
				}
				
				if($this->config->item('completed_id') == $task_status_id){
					$updated_task = array(
						'task_completion_date'=>date('Y-m-d H:i:s')
					);
					$this->db->where("task_id",$task_id);
					$this->db->update("tasks",$updated_task);
				}
				if($allocated_to != get_authenticateUserID()){
                                    $chk_task_exist = chk_swim_exist($task_id,  get_authenticateUserID());
                                    if($chk_task_exist == '0'){
                                        $swimlane_id1 = get_default_swimlane(get_authenticateUserID());
                                        $user_swimlane1 = array(
						'user_id' => get_authenticateUserID(),
						'task_id' => $task_id,
						'swimlane_id' => $swimlane_id1,
						'color_id' => $color_id,
                                                'kanban_order' => 1,
						'calender_order' => get_user_last_calnder_order(get_authenticateUserID(),$scheduled_date) + 1
					);
                                        $this->db->insert('user_task_swimlanes',$user_swimlane1);
                                    }
                                }
				$chk_exist = chk_swim_exist($task_id,$allocated_to);
				if(isset($unserializedData['task_swimlane_id'])){
					$swimlane_id = $unserializedData['task_swimlane_id'];
				} else if(isset($unserializedData['genral_swimlane_id'])){
					$swimlane_id = $unserializedData['genral_swimlane_id'];
				} else {
					$swimlane_id = get_default_swimlane($allocated_to);
				}
				if($chk_exist == '0'){
					$user_swimlane = array(
						'user_id' => $allocated_to,
						'task_id' => $task_id,
						'swimlane_id' => $swimlane_id,
						'color_id' => $color_id,
						'kanban_order' => 1,
						'calender_order' => get_user_last_calnder_order($allocated_to,$scheduled_date) + 1
					);
		
					$this->db->insert('user_task_swimlanes',$user_swimlane);
                                        $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                            SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                            WHERE `uts`.`user_id` = '$allocated_to'
                                            AND `uts`.`task_id` != '$task_id'
                                            AND `t`.`task_status_id` = '$task_status_id'
                                            ");
                                     /*This query bulider have alising problem*/    
//					$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//					$this->db->where('uts.user_id', $allocated_to);
//					$this->db->where('uts.task_id != ',$task_id);
//					$this->db->where('t.task_status_id', $task_status_id);                                       
//					$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
                                                                             
                                        
				} else {
					if($color_id){
						$user_swimlane = array(
							'color_id' => $color_id
						);
						$this->db->where('user_id', $allocated_to);
						$this->db->where('task_id',$task_id);
						$this->db->update('user_task_swimlanes',$user_swimlane);
					}
					if($swimlane_id){
						$user_swimlane = array(
							'swimlane_id' => $swimlane_id
						);
						$this->db->where('user_id', $allocated_to);
						$this->db->where('task_id',$task_id);
						$this->db->update('user_task_swimlanes',$user_swimlane);
					}
				}
		
				$history_data = array(
					'histrory_title' => 'Task created.',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
				
				
				if($old_task_data){
					if($old_task_data['task_title'] != $unserializedData['task_title']){
						$history_data = array(
							'histrory_title' => 'Task name changed from "'.$old_task_data['task_title'].'" to "'.$unserializedData['task_title'].'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
					}
		
					if($old_task_data['task_description'] != $unserializedData['task_description']){
						$history_data = array(
							'histrory_title' => 'Task description changed from "'.$old_task_data['task_description'].'" to "'.$unserializedData['task_description'].'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
					}
		
					if($old_task_data['task_priority'] != $unserializedData['task_priority']){
						$history_data = array(
							'histrory_title' => 'Task priority changed from "'.$old_task_data['task_priority'].'" to "'.$unserializedData['task_priority'].'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
					}
		
					if($old_task_data['task_status_id'] != $task_status_id){
						$history_data = array(
							'histrory_title' => 'Task status changed from "'.get_task_status_name_by_id($old_task_data['task_status_id']).'" to "'.get_task_status_name_by_id($task_status_id).'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
					}
		
					if($old_due_date != $due_date){
						$history_data = array(
							'histrory_title' => 'Task due date changed from "'.$old_due_date.'" to "'.$due_date.'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
						
						//email variables
						$owner_name = usernameById($old_task_data['task_owner_id']);
						if($old_task_data['task_due_date']!='0000-00-00'){
							$task_due_date = date($this->config->item('company_default_format'),strtotime($old_task_data['task_due_date']));
						} else {
							$task_due_date = 'N/A';
						}
						$task_description = $old_task_data['task_description'];
						if($task_description){
							$task_description = $task_description;
						} else {
							$task_description = 'N/A';
						}
						$project_name = get_project_name($old_task_data['task_project_id']);
						if($project_name){
							$project_name = $project_name;
						} else {
							$project_name = 'N/A';
						}
						
						$modified_user_name = $this->session->userdata('username');
						
						if($old_task_data['task_owner_id'] !=get_authenticateUserID()){
			
							//notification
							$notification_text = 'Task "'.$old_task_data['task_title'].'" due date changed from "'.$old_due_date.'" to "'.$due_date.'" by '.$this->session->userdata('username').'';
							$notification_data = array(
								'task_id' => $task_id,
								'project_id' => $old_task_data['task_project_id'],
								'notification_text' => $notification_text,
								'notification_user_id' => $old_task_data['task_owner_id'],
								'notification_from' =>get_authenticateUserID(),
								'is_read' => '0',
								'date_added' => date("Y-m-d H:i:s")
							);
							$this->db->insert('task_notification',$notification_data);
							
							/*** send email to task owner userfor task due date changed  ****/
							$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='due date modified'");
							$email_temp=$email_template->row();
							$email_address_from=$email_temp->from_address;
							$email_address_reply=$email_temp->reply_address;
			
							$email_subject=$email_temp->subject;
							$email_message=$email_temp->message;
			
							$user_info = get_user_info($old_task_data['task_owner_id']);
							$user_name = $user_info->first_name.' '.$user_info->last_name;
							$task_name = $old_task_data['task_title'];
			
			
							$email_to = $user_info->email;
							$subscription_link = site_url();
							$allocated_user_name = usernameById($old_task_data['task_allocated_user_id']);
			
							$email_subject=str_replace('{break}','<br/>',$email_subject);
							$email_subject=str_replace('{user_name}',$user_name,$email_subject);
							$email_subject=str_replace('{task_name}', $task_name, $email_subject);
							$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
							$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
							$email_subject=str_replace('{project_name}',$project_name,$email_subject);
							$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
							$email_subject=str_replace('{old_due_date}',$old_due_date,$email_subject);
							$email_subject=str_replace('{new_due_date}',$due_date,$email_subject);
							$email_subject=str_replace('{modified_user_name}', $modified_user_name, $email_subject);
			
			
							$email_message=str_replace('{break}','<br/>',$email_message);
							$email_message=str_replace('{user_name}',$user_name,$email_message);
							$email_message=str_replace('{task_name}', $task_name, $email_message);
							$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
							$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
							$email_message=str_replace('{task_description}',$task_description,$email_message);
							$email_message=str_replace('{project_name}',$project_name,$email_message);
							$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
							$email_message=str_replace('{old_due_date}',$old_due_date,$email_message);
							$email_message=str_replace('{new_due_date}',$due_date,$email_message);
							$email_message=str_replace('{modified_user_name}', $modified_user_name, $email_message);
			
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
						if($old_task_data['task_owner_id']!=$old_task_data['task_allocated_user_id'] && $old_task_data['task_allocated_user_id'] !=get_authenticateUserID()){
							//notification
							$notification_text = 'Task "'.$old_task_data['task_title'].'" due date changed from "'.$old_due_date.'" to "'.$due_date.'" by '.$this->session->userdata('username').'';
							$notification_data = array(
								'task_id' => $task_id,
								'project_id' => $old_task_data['task_project_id'],
								'notification_text' => $notification_text,
								'notification_user_id' => $old_task_data['task_allocated_user_id'],
								'notification_from' =>get_authenticateUserID(),
								'is_read' => '0',
								'date_added' => date("Y-m-d H:i:s")
							);
							$this->db->insert('task_notification',$notification_data);
							
							/*** send email to task allocated userfor task due date changed ****/
							$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='due date modified'");
							$email_temp=$email_template->row();
							$email_address_from=$email_temp->from_address;
							$email_address_reply=$email_temp->reply_address;
			
							$email_subject=$email_temp->subject;
							$email_message=$email_temp->message;
			
							$user_info = get_user_info($old_task_data['task_allocated_user_id']);
							$user_name = $user_info->first_name.' '.$user_info->last_name;
							$task_name = $old_task_data['task_title'];
			
			
							$email_to = $user_info->email;
							$subscription_link = site_url();
							$allocated_user_name = usernameById($old_task_data['task_allocated_user_id']);
			
							$email_subject=str_replace('{break}','<br/>',$email_subject);
							$email_subject=str_replace('{user_name}',$user_name,$email_subject);
							$email_subject=str_replace('{task_name}', $task_name, $email_subject);
							$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
							$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
							$email_subject=str_replace('{project_name}',$project_name,$email_subject);
							$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
							$email_subject=str_replace('{old_due_date}',$old_due_date,$email_subject);
							$email_subject=str_replace('{new_due_date}',$due_date,$email_subject);
							$email_subject=str_replace('{modified_user_name}', $modified_user_name, $email_subject);
			
			
							$email_message=str_replace('{break}','<br/>',$email_message);
							$email_message=str_replace('{user_name}',$user_name,$email_message);
							$email_message=str_replace('{task_name}', $task_name, $email_message);
							$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
							$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
							$email_message=str_replace('{task_description}',$task_description,$email_message);
							$email_message=str_replace('{project_name}',$project_name,$email_message);
							$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
							$email_message=str_replace('{old_due_date}',$old_due_date,$email_message);
							$email_message=str_replace('{new_due_date}',$due_date,$email_message);
							$email_message=str_replace('{modified_user_name}', $modified_user_name, $email_message);
			
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
					}
		
				}
				
				if($allocated_to != get_authenticateUserID()){
					$notification_data = array(
						'task_id' => $task_id,
						'project_id' => $project_id,
						'notification_text' => $this->session->userdata('username').' has assigned the task "'.$unserializedData['task_title'].'" to you',
						'notification_user_id' => $allocated_to,
						'notification_from' =>get_authenticateUserID(),
						'is_read' => '0',
						'is_allocation_notification' => '1',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->db->insert('task_notification',$notification_data);
					
					/*** send email to task allocated user ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
					$email_temp=$email_template->row();
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
		
					$email_subject=$email_temp->subject;
					$email_message=$email_temp->message;
		
					$allocated_user_info = get_user_info($allocated_to);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					/******/
					$task_name = $unserializedData['task_title'];
					$owner_name = usernameById($unserializedData['task_owner_id']);
					if($due_date!='0000-00-00'){
						$task_due_date = date($this->config->item('company_default_format'),strtotime($due_date));
					} else {
						$task_due_date = 'N/A';
					}
					$task_data = get_task_detail($task_id);
					$task_description = $task_data['task_description'];
					if($task_description){
						$task_description = $task_description;
					} else {
						$task_description = 'N/A';
					}
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
						
			}
		} else { 
			
			if($name == "frequency_type" || $name == "recurrence_type" || $name == "Daily_every_weekday" || $name == "Daily_every_day" || $name == "Daily_every_week_day" || $name == "Weekly_week_day" || $name == "Weekly_every_week_no" || $name == "monthly_radios" || $name == "Monthly_op1_1" || $name == "Monthly_op1_2" || $name == "Monthly_op2_1" || $name == "Monthly_op2_2" || $name == "Monthly_op2_3" || $name == "Monthly_op3_1" || $name == "Monthly_op3_2" || $name == "yearly_radios" || $name == "Yearly_op1" || $name == "Yearly_op2_1" || $name == "Yearly_op2_2" || $name == "Yearly_op3_1" || $name == "Yearly_op3_2" || $name == "Yearly_op3_3" || $name == "Yearly_op4_1" || $name == "Yearly_op4_2" || $name == "start_on_date" || $name == "no_end_date" || $name == "end_after_recurrence" || $name == "end_by_date"){
				
				$unserializedData = array();
				parse_str($value,$unserializedData);
				
				if($unserializedData){
					if($unserializedData['frequency_type'] == "one_off"){
						$data = array(
							'frequency_type' => "one_off"
			
						);
			
						$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
						$this->db->update('tasks',$data);
						
						
						
					} else {
						if($unserializedData["start_on_date"]){
							$start_on_date = change_date_format($unserializedData["start_on_date"]);
				
							if(isset($unserializedData['Weekly_week_day']) && $unserializedData['Weekly_week_day']!=''){
								$Weekly_week_day = implode(',', $unserializedData['Weekly_week_day']);
							} else {
								$Weekly_week_day = '';
							}
				
							if(isset($unserializedData['end_by_date']) && $unserializedData['end_by_date']){
								$end_by_date = change_date_format($unserializedData['end_by_date']);
							} else {
								$end_by_date = '';
							}
							
							if(isset($unserializedData['Daily_every_day']) && $unserializedData['Daily_every_day'] !=''){ $Daily_every_day = $unserializedData['Daily_every_day']; } else { $Daily_every_day = '';}
							
							if(isset($unserializedData['Monthly_op1_1']) && $unserializedData['Monthly_op1_1']!=""){ $m_op1_1 = $unserializedData['Monthly_op1_1']; } else { $m_op1_1 = ''; }
							if(isset($unserializedData['Monthly_op1_2']) && $unserializedData['Monthly_op1_2']!=""){ $m_op1_2 = $unserializedData['Monthly_op1_2']; } else { $m_op1_2 = ''; }
							
							if(isset($unserializedData['Monthly_op2_1']) && $unserializedData['Monthly_op2_1']!=""){ $m_op2_1 = $unserializedData['Monthly_op2_1']; } else { $m_op2_1 = ''; }
							if(isset($unserializedData['Monthly_op2_2']) && $unserializedData['Monthly_op2_2']!=""){ $m_op2_2 = $unserializedData['Monthly_op2_2']; } else { $m_op2_2 = ''; }
							if(isset($unserializedData['Monthly_op2_3']) && $unserializedData['Monthly_op2_3']!=""){ $m_op2_3 = $unserializedData['Monthly_op2_3']; } else { $m_op2_3 = ''; }
							
							
							if(isset($unserializedData['Monthly_op3_1']) && $unserializedData['Monthly_op3_1']!=""){ $m_op3_1 = $unserializedData['Monthly_op3_1']; } else { $m_op3_1 = ''; }
							if(isset($unserializedData['Monthly_op3_2']) && $unserializedData['Monthly_op3_2']!=""){ $m_op3_2 = $unserializedData['Monthly_op3_2']; } else { $m_op3_2 = ''; }
							
							if(isset($unserializedData['yearly_radios']) && $unserializedData['yearly_radios']!=""){ $y_radio = $unserializedData['yearly_radios']; } else { $y_radio = '1'; }
							
							if(isset($unserializedData['Yearly_op1']) && $unserializedData['Yearly_op1']!=''){ $y_op1 = $unserializedData['Yearly_op1']; } else { $y_op1 = '';}
							
							if(isset($unserializedData['Yearly_op2_1']) && $unserializedData['Yearly_op2_1']!=""){ $y_op2_1 = $unserializedData['Yearly_op2_1']; } else { $y_op2_1 = ''; }
							if(isset($unserializedData['Yearly_op2_2']) && $unserializedData['Yearly_op2_2']!=""){ $y_op2_2 = $unserializedData['Yearly_op2_2']; } else { $y_op2_2 = ''; }
							
							if(isset($unserializedData['Yearly_op3_1']) && $unserializedData['Yearly_op3_1']!=""){ $y_op3_1 = $unserializedData['Yearly_op3_1']; } else { $y_op3_1 = ''; }
							if(isset($unserializedData['Yearly_op3_2']) && $unserializedData['Yearly_op3_2']!=""){ $y_op3_2 = $unserializedData['Yearly_op3_2']; } else { $y_op3_2 = ''; }
							if(isset($unserializedData['Yearly_op3_3']) && $unserializedData['Yearly_op3_3']!=""){ $y_op3_3 = $unserializedData['Yearly_op3_3']; } else { $y_op3_3 = ''; }
							
							if(isset($unserializedData['Yearly_op4_1']) && $unserializedData['Yearly_op4_1']!=""){ $y_op4_1 = $unserializedData['Yearly_op4_1']; } else { $y_op4_1 = ''; }
							if(isset($unserializedData['Yearly_op4_2']) && $unserializedData['Yearly_op4_2']!=""){ $y_op4_2 = $unserializedData['Yearly_op4_2']; } else { $y_op4_2 = ''; }
							
							if(isset($unserializedData['end_after_recurrence']) && $unserializedData['end_after_recurrence'] != "0"){
								$end_after_recurrence = $unserializedData['end_after_recurrence'];
							} else {
								$end_after_recurrence = "0";
							}
				
							if($unserializedData['recurrence_type'] == "1"){
								
								if(isset($unserializedData['Daily_every_weekday']) && $unserializedData['Daily_every_weekday']!="0"){
									$Daily_every_weekday = $unserializedData['Daily_every_weekday'];
								} else {
									$Daily_every_weekday = "0";
								}
								if(isset($unserializedData['Daily_every_week_day']) && $unserializedData['Daily_every_week_day']!="0"){
									$Daily_every_week_day = $unserializedData['Daily_every_week_day'];
								} else {
									$Daily_every_week_day = "0";
								}
								
								$data = array(
									'frequency_type' => $unserializedData['frequency_type'],
									'recurrence_type' => $unserializedData['recurrence_type'],
									'Daily_every_day' => $Daily_every_day,
									'Daily_every_weekday' => $Daily_every_weekday,
									'Daily_every_week_day' => $Daily_every_week_day,
									'start_on_date' => $start_on_date,
									'no_end_date' => $unserializedData['no_end_date'],
									'end_after_recurrence' => $end_after_recurrence,
									'end_by_date' => $end_by_date
					
								);
					
								$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
								$this->db->update('tasks',$data);
								
							} elseif($unserializedData['recurrence_type'] == "2"){
								$data = array(
									'frequency_type' => $unserializedData['frequency_type'],
									'recurrence_type' => $unserializedData['recurrence_type'],
									'Weekly_every_week_no' => $unserializedData['Weekly_every_week_no'],
									'Weekly_week_day' => $Weekly_week_day,
									'start_on_date' => $start_on_date,
									'no_end_date' => $unserializedData['no_end_date'],
									'end_after_recurrence' => $end_after_recurrence,
									'end_by_date' => $end_by_date
					
								);
					
								$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
								$this->db->update('tasks',$data);
							} elseif($unserializedData['recurrence_type'] == "3"){
								$data = array(
									'frequency_type' => $unserializedData['frequency_type'],
									'recurrence_type' => $unserializedData['recurrence_type'],
									'Monthly_op1_1' => $m_op1_1,
									'Monthly_op1_2' => $m_op1_2,
									'Monthly_op2_1' => $m_op2_1,
									'Monthly_op2_2' => $m_op2_2,
									'Monthly_op2_3' => $m_op2_3,
									'Monthly_op3_1' => $m_op3_1,
									'Monthly_op3_2' => $m_op3_2,
									'monthly_radios' => $unserializedData['monthly_radios'],
									'start_on_date' => $start_on_date,
									'no_end_date' => $unserializedData['no_end_date'],
									'end_after_recurrence' => $end_after_recurrence,
									'end_by_date' => $end_by_date
					
								);
					
								$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
								$this->db->update('tasks',$data);
							} elseif($unserializedData['recurrence_type'] == "4"){
								$data = array(
									'frequency_type' => $unserializedData['frequency_type'],
									'recurrence_type' => $unserializedData['recurrence_type'],
									'Yearly_op1' => $y_op1,
									'Yearly_op2_1' => $y_op2_1,
									'Yearly_op2_2' => $y_op2_2,
									'Yearly_op3_1' => $y_op3_1,
									'Yearly_op3_2' => $y_op3_2,
									'Yearly_op3_3' => $y_op3_3,
									'Yearly_op4_1' => $y_op4_1,
									'Yearly_op4_2' => $y_op4_2,
									'yearly_radios' => $y_radio,
									'start_on_date' => $start_on_date,
									'no_end_date' => $unserializedData['no_end_date'],
									'end_after_recurrence' => $end_after_recurrence,
									'end_by_date' => $end_by_date
					
								);
					
								$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
								$this->db->update('tasks',$data);
							} else {
								
							}
							
							
						}
					}
				}
                        } 
                        else if($name == "watch_list"){
                            if($value == 1)
                            {
                                $watch_data = array('task_id'=>$task_id, 'user_id'=>get_authenticateUserID());
                                $this->db->insert('my_watch_list',$watch_data);
                            }  else {
                                $this->db->delete('my_watch_list',array('task_id'=>$task_id, 'user_id'=>get_authenticateUserID()));
                            }
                        } else {
			
				if($name == "task_division_id[]"){
					if($value){ $divisions = implode(',', $value); } else { $divisions = ''; }
					$data = array(
						'task_division_id' => $divisions
					);
					$this->db->where('task_id',$task_id);
					$this->db->update('tasks',$data);
				} else if($name == "task_department_id[]"){
					if($value){ $department = implode(',', $value); } else { $department = ''; }
					$data = array(
						'task_department_id' => $department
					);
					$this->db->where('task_id',$task_id);
					$this->db->update('tasks',$data);
				} else if($name == "task_skill_id[]"){
					if($value){ $skill = implode(',', $value); } else { $skill = ''; }
					$data = array(
						'task_skill_id' => $skill
					);
					$this->db->where('task_id',$task_id);
					$this->db->update('tasks',$data);
				} else if($name == "is_personal" || $name == "locked_due_date"){
					if($value){
						$value = "1";
					} else {
						$value = "0";
					}
					$data = array(
						$name => $value
					);
					if($name == "locked_due_date"){
						$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
					} else {
						$this->db->where('task_id',$task_id);
					}
					
					$this->db->update('tasks',$data);
					
				} else if($name == "task_due_date"){
					
					$old_task_detail = get_task_detail($task_id);
					$due_date = $old_task_detail['task_due_date'];
					$is_scheduled = $old_task_detail['is_scheduled'];
					$allocated_to = $old_task_detail['task_allocated_user_id'];
					$is_scheduled_date_changed = 0;
					if($value){
						$new_due_date = change_date_format($value);
					} else {
						$new_due_date = '';
					}
					
					if($new_due_date){
						if($due_date != '0000-00-00'){
							if(strtotime($due_date) != strtotime($new_due_date)){
								if($redirect_page == 'from_kanban' || $redirect_page == 'from_teamdashboard' || $redirect_page == 'from_dashboard' || $redirect_page == 'from_project' || $redirect_page == 'from_customer'){
									$is_scheduled = '1';
									$is_scheduled_date_changed = 1;
									$scheduled_date = $new_due_date;
								} else {
									if($task_scheduled_date){
										$scheduled_date = change_date_format($task_scheduled_date);
									} else {
										$scheduled_date = '';
									}
								}
							} else {
								if($redirect_page == 'from_kanban' || $redirect_page == 'from_teamdashboard' || $redirect_page == 'from_dashboard' || $redirect_page == 'from_project' || $redirect_page == 'from_customer'){
									$scheduled_date = $new_due_date;
								} else {
									if($task_scheduled_date){
										$scheduled_date = change_date_format($task_scheduled_date);
									} else {
										$scheduled_date = '';
									}
								}
							}
						} else {
			
							if($redirect_page == 'from_kanban' || $redirect_page == 'from_teamdashboard' || $redirect_page == 'from_dashboard' || $redirect_page == 'from_project' || $redirect_page == 'from_customer'){
								$is_scheduled = '1';
								$is_scheduled_date_changed = 1;
								$scheduled_date = $new_due_date;
							} else {
								
								if($task_scheduled_date){
									$scheduled_date = change_date_format($task_scheduled_date);
								} else {
									$scheduled_date = $new_due_date;
								}
							}
						}
					} else {
						if($redirect_page == 'from_kanban' || $redirect_page == 'from_teamdashboard' || $redirect_page == 'from_dashboard' || $redirect_page == 'from_project' || $redirect_page == 'from_customer'){
							$scheduled_date = $new_due_date;
						} else {
							if($task_scheduled_date){
								$scheduled_date = change_date_format($task_scheduled_date);
							} else {
								$scheduled_date = '';
							}
						}
					}
					$data = array(
						$name => $new_due_date,
						'is_scheduled' => $is_scheduled,
						'task_scheduled_date' => $scheduled_date
					);
					$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
					$this->db->update('tasks',$data);
					
					if($new_due_date!="" && $old_task_detail['task_due_date'] != $new_due_date){
						$history_data = array(
							'histrory_title' => 'Task due date changed from "'.$old_task_detail['task_due_date'].'" to "'.$new_due_date.'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
						
						$multiIds = multiAllocationTaskIds($task_id);
						if($multiIds){
							foreach($multiIds as $mId){
								$history_data = array(
									'histrory_title' => 'Task due date changed from "'.$old_task_detail['task_due_date'].'" to "'.$new_due_date.'"',
									'history_added_by' => get_authenticateUserID(),
									'task_id' => $mId->task_id,
									'date_added' => date('Y-m-d H:i:s')
								);
								$this->db->insert('task_history',$history_data);
							}
						}
						
						//email variables
						$owner_name = usernameById($old_task_detail['task_owner_id']);
						if($old_task_detail['task_due_date']!='0000-00-00'){
							$task_due_date = date($this->config->item('company_default_format'),strtotime($old_task_detail['task_due_date']));
						} else {
							$task_due_date = 'N/A';
						}
						$task_description = $old_task_detail['task_description'];
						if($task_description){
							$task_description = $task_description;
						} else {
							$task_description = 'N/A';
						}
						$project_name = get_project_name($old_task_detail['task_project_id']);
						if($project_name){
							$project_name = $project_name;
						} else {
							$project_name = 'N/A';
						}
						
						$modified_user_name = $this->session->userdata('username');
						/* check owner_id and send mail*/
						if($old_task_detail['task_owner_id'] !=get_authenticateUserID()){
			
							//notification
							$notification_text = 'Task "'.$old_task_detail['task_title'].'" due date changed from "'.$old_task_detail['task_due_date'].'" to "'.$new_due_date.'" by '.$this->session->userdata('username').'';
							$notification_data = array(
								'task_id' => $task_id,
								'project_id' => $old_task_detail['task_project_id'],
								'notification_text' => $notification_text,
								'notification_user_id' => $old_task_detail['task_owner_id'],
								'notification_from' =>get_authenticateUserID(),
								'is_read' => '0',
								'date_added' => date("Y-m-d H:i:s")
							);
							$this->db->insert('task_notification',$notification_data);
							
							/*** send email to task owner userfor task due date changed  ****/
							$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='due date modified'");
							$email_temp=$email_template->row();
							$email_address_from=$email_temp->from_address;
							$email_address_reply=$email_temp->reply_address;
			
							$email_subject=$email_temp->subject;
							$email_message=$email_temp->message;
			
							$user_info = get_user_info($old_task_detail['task_owner_id']);
							$user_name = $user_info->first_name.' '.$user_info->last_name;
							$task_name = $old_task_detail['task_title'];
			
			
							$email_to = $user_info->email;
							$subscription_link = site_url();
							$allocated_user_name = usernameById($old_task_detail['task_allocated_user_id']);
			
							$email_subject=str_replace('{break}','<br/>',$email_subject);
							$email_subject=str_replace('{user_name}',$user_name,$email_subject);
							$email_subject=str_replace('{task_name}', $task_name, $email_subject);
							$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
							$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
							$email_subject=str_replace('{project_name}',$project_name,$email_subject);
							$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
							$email_subject=str_replace('{old_due_date}',$old_task_detail['task_due_date'],$email_subject);
							$email_subject=str_replace('{new_due_date}',$new_due_date,$email_subject);
							$email_subject=str_replace('{modified_user_name}', $modified_user_name, $email_subject);
			
			
							$email_message=str_replace('{break}','<br/>',$email_message);
							$email_message=str_replace('{user_name}',$user_name,$email_message);
							$email_message=str_replace('{task_name}', $task_name, $email_message);
							$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
							$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
							$email_message=str_replace('{task_description}',$task_description,$email_message);
							$email_message=str_replace('{project_name}',$project_name,$email_message);
							$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
							$email_message=str_replace('{old_due_date}',$old_task_detail['task_due_date'],$email_message);
							$email_message=str_replace('{new_due_date}',$new_due_date,$email_message);
							$email_message=str_replace('{modified_user_name}', $modified_user_name, $email_message);
			
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
						if($old_task_detail['task_owner_id']!=$old_task_detail['task_allocated_user_id'] && $old_task_detail['task_allocated_user_id'] !=get_authenticateUserID()){
							//notification
							$notification_text = 'Task "'.$old_task_detail['task_title'].'" due date changed from "'.$old_task_detail['task_due_date'].'" to "'.$new_due_date.'" by '.$this->session->userdata('username').'';
							$notification_data = array(
								'task_id' => $task_id,
								'project_id' => $old_task_detail['task_project_id'],
								'notification_text' => $notification_text,
								'notification_user_id' => $old_task_detail['task_allocated_user_id'],
								'notification_from' =>get_authenticateUserID(),
								'is_read' => '0',
								'date_added' => date("Y-m-d H:i:s")
							);
							$this->db->insert('task_notification',$notification_data);
							
							/*** send email to task allocated userfor task due date changed ****/
							$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='due date modified'");
							$email_temp=$email_template->row();
							$email_address_from=$email_temp->from_address;
							$email_address_reply=$email_temp->reply_address;
			
							$email_subject=$email_temp->subject;
							$email_message=$email_temp->message;
			
							$user_info = get_user_info($old_task_detail['task_allocated_user_id']);
							$user_name = $user_info->first_name.' '.$user_info->last_name;
							$task_name = $old_task_detail['task_title'];
			
							$email_to = $user_info->email;
							$subscription_link = site_url();
							$allocated_user_name = usernameById($old_task_detail['task_allocated_user_id']);
			
							$email_subject=str_replace('{break}','<br/>',$email_subject);
							$email_subject=str_replace('{user_name}',$user_name,$email_subject);
							$email_subject=str_replace('{task_name}', $task_name, $email_subject);
							$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
							$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
							$email_subject=str_replace('{project_name}',$project_name,$email_subject);
							$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
							$email_subject=str_replace('{old_due_date}',$old_task_detail['task_due_date'],$email_subject);
							$email_subject=str_replace('{new_due_date}',$new_due_date,$email_subject);
							$email_subject=str_replace('{modified_user_name}', $modified_user_name, $email_subject);
			
			
							$email_message=str_replace('{break}','<br/>',$email_message);
							$email_message=str_replace('{user_name}',$user_name,$email_message);
							$email_message=str_replace('{task_name}', $task_name, $email_message);
							$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
							$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
							$email_message=str_replace('{task_description}',$task_description,$email_message);
							$email_message=str_replace('{project_name}',$project_name,$email_message);
							$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
							$email_message=str_replace('{old_due_date}',$old_task_detail['task_due_date'],$email_message);
							$email_message=str_replace('{new_due_date}',$new_due_date,$email_message);
							$email_message=str_replace('{modified_user_name}', $modified_user_name, $email_message);
			
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
					}
	
					if($is_scheduled_date_changed == "1"){
						$user_swimlane = array(
							'calender_order' => get_user_last_calnder_order($allocated_to,$scheduled_date) + 1
						);
						
						$this->db->where('user_id',$allocated_to);
						$this->db->where('task_id',$task_id);
						$this->db->update('user_task_swimlanes',$user_swimlane);
					}
					
					
				} else if($name == "task_status_id"){
					
					$old_task_detail = get_task_detail($task_id);
					$old_task_status_id = $old_task_detail['task_status_id'];
					$allocated_to = $old_task_detail['task_allocated_user_id'];
					
					$is_dependency_added = get_task_dependencies_ids($task_id);
					if($is_dependency_added){
						$task_status_id = get_task_status_id_by_name("Not Ready");
					} else {
						$task_status_id = $value;
					}
					
					$data = array('task_status_id'=>$task_status_id);
					$this->db->where('task_id',$task_id);
					$this->db->update('tasks',$data);
					
					if($old_task_status_id != $task_status_id){
					
						if($this->config->item('completed_id') == $task_status_id){
							$updated_task = array(
								'task_completion_date'=>date('Y-m-d H:i:s')
							);
							$this->db->where("task_id",$task_id);
							$this->db->update("tasks",$updated_task);
							
							//email variables
							$owner_name = usernameById($old_task_detail['task_owner_id']);
							if($old_task_detail['task_due_date']!='0000-00-00'){
								$task_due_date = date($this->config->item('company_default_format'),strtotime($old_task_detail['task_due_date']));
							} else {
								$task_due_date = 'N/A';
							}
							$task_description = $old_task_detail['task_description'];
							if($task_description){
								$task_description = $task_description;
							} else {
								$task_description = 'N/A';
							}
							$project_name = get_project_name($old_task_detail['task_project_id']);
							if($project_name){
								$project_name = $project_name;
							} else {
								$project_name = 'N/A';
							}
							
							$completion_user_name = $this->session->userdata('username');
							
							if($old_task_detail['task_owner_id'] !=get_authenticateUserID()){
				
								//notification
								$notification_text = '"'.$old_task_detail['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
								$notification_data = array(
									'task_id' => $task_id,
									'project_id' => $old_task_detail['task_project_id'],
									'notification_text' => $notification_text,
									'notification_user_id' => $old_task_detail['task_owner_id'],
									'notification_from' =>get_authenticateUserID(),
									'is_read' => '0',
									'date_added' => date("Y-m-d H:i:s")
								);
								$this->db->insert('task_notification',$notification_data);
								
								/*** send email to task owner user for task is completed ****/
								$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
								$email_temp=$email_template->row();
								$email_address_from=$email_temp->from_address;
								$email_address_reply=$email_temp->reply_address;
				
								$email_subject=$email_temp->subject;
								$email_message=$email_temp->message;
				
								$user_info = get_user_info($old_task_detail['task_owner_id']);
								$user_name = $user_info->first_name.' '.$user_info->last_name;
								$task_name = $old_task_detail['task_title'];
				
				
								$email_to = $user_info->email;
								$subscription_link = site_url();
								$allocated_user_name = usernameById($old_task_detail['task_allocated_user_id']);
				
								$email_subject=str_replace('{break}','<br/>',$email_subject);
								$email_subject=str_replace('{user_name}',$user_name,$email_subject);
								$email_subject=str_replace('{task_name}', $task_name, $email_subject);
								$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
								$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
								$email_subject=str_replace('{project_name}',$project_name,$email_subject);
								$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
								$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
								
				
				
								$email_message=str_replace('{break}','<br/>',$email_message);
								$email_message=str_replace('{user_name}',$user_name,$email_message);
								$email_message=str_replace('{task_name}', $task_name, $email_message);
								$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
								$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
								$email_message=str_replace('{task_description}',$task_description,$email_message);
								$email_message=str_replace('{project_name}',$project_name,$email_message);
								$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
								$email_message=str_replace('{completion_user_name}',$completion_user_name,$email_message);
				
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
							if($old_task_detail['task_owner_id']!=$old_task_detail['task_allocated_user_id'] && $old_task_detail['task_allocated_user_id'] !=get_authenticateUserID()){
								//notification
								$notification_text = '"'.$old_task_detail['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
								$notification_data = array(
									'task_id' => $task_id,
									'project_id' => $old_task_detail['task_project_id'],
									'notification_text' => $notification_text,
									'notification_user_id' => $old_task_detail['task_allocated_user_id'],
									'notification_from' =>get_authenticateUserID(),
									'is_read' => '0',
									'date_added' => date("Y-m-d H:i:s")
								);
								$this->db->insert('task_notification',$notification_data);
								
								/*** send email to task owner user for task is completed ****/
								$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
								$email_temp=$email_template->row();
								$email_address_from=$email_temp->from_address;
								$email_address_reply=$email_temp->reply_address;
				
								$email_subject=$email_temp->subject;
								$email_message=$email_temp->message;
				
								$user_info = get_user_info($old_task_detail['task_allocated_user_id']);
								$user_name = $user_info->first_name.' '.$user_info->last_name;
								$task_name = $old_task_detail['task_title'];
				
				
								$email_to = $user_info->email;
								$subscription_link = site_url();
								$allocated_user_name = usernameById($old_task_detail['task_allocated_user_id']);
				
								$email_subject=str_replace('{break}','<br/>',$email_subject);
								$email_subject=str_replace('{user_name}',$user_name,$email_subject);
								$email_subject=str_replace('{task_name}', $task_name, $email_subject);
								$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
								$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
								$email_subject=str_replace('{project_name}',$project_name,$email_subject);
								$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
								$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				
								$email_message=str_replace('{break}','<br/>',$email_message);
								$email_message=str_replace('{user_name}',$user_name,$email_message);
								$email_message=str_replace('{task_name}', $task_name, $email_message);
								$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
								$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
								$email_message=str_replace('{task_description}',$task_description,$email_message);
								$email_message=str_replace('{project_name}',$project_name,$email_message);
								$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
								$email_message=str_replace('{completion_user_name}',$completion_user_name,$email_message);
				
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
						} else {
							$updated_task = array(
								'task_completion_date'=>''
							);
							$this->db->where("task_id",$task_id);
							$this->db->update("tasks",$updated_task);
							
							
							if($old_task_status_id == $this->config->item('completed_id')){
								
							
								//email variables
								$owner_name = usernameById($old_task_detail['task_owner_id']);
								if($old_task_detail['task_due_date']!='0000-00-00'){
									$task_due_date = date($this->config->item('company_default_format'),strtotime($old_task_detail['task_due_date']));
								} else {
									$task_due_date = 'N/A';
								}
								$task_description = $old_task_detail['task_description'];
								if($task_description){
									$task_description = $task_description;
								} else {
									$task_description = 'N/A';
								}
								$project_name = get_project_name($old_task_detail['task_project_id']);
								if($project_name){
									$project_name = $project_name;
								} else {
									$project_name = 'N/A';
								}
								
								$completion_user_name = $this->session->userdata('username');
								
								if($old_task_detail['task_owner_id'] !=get_authenticateUserID()){
					
									//notification
									$notification_text = '"'.$old_task_detail['task_title'].'" is uncompleted by '.$this->session->userdata('username').' this user.';
									$notification_data = array(
										'task_id' => $task_id,
										'project_id' => $old_task_detail['task_project_id'],
										'notification_text' => $notification_text,
										'notification_user_id' => $old_task_detail['task_owner_id'],
										'notification_from' =>get_authenticateUserID(),
										'is_read' => '0',
										'date_added' => date("Y-m-d H:i:s")
									);
									$this->db->insert('task_notification',$notification_data);
									
									/*** send email to task owner user for task is not completed ****/
									$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
									$email_temp=$email_template->row();
									$email_address_from=$email_temp->from_address;
									$email_address_reply=$email_temp->reply_address;
					
									$email_subject=$email_temp->subject;
									$email_message=$email_temp->message;
					
									$user_info = get_user_info($old_task_detail['task_owner_id']);
									$user_name = $user_info->first_name.' '.$user_info->last_name;
									$task_name = $old_task_detail['task_title'];
					
					
									$email_to = $user_info->email;
									$subscription_link = site_url();
									$allocated_user_name = usernameById($old_task_detail['task_allocated_user_id']);
					
									$email_subject=str_replace('{break}','<br/>',$email_subject);
									$email_subject=str_replace('{user_name}',$user_name,$email_subject);
									$email_subject=str_replace('{task_name}', $task_name, $email_subject);
									$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
									$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
									$email_subject=str_replace('{project_name}',$project_name,$email_subject);
									$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
									$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
									
					
					
									$email_message=str_replace('{break}','<br/>',$email_message);
									$email_message=str_replace('{user_name}',$user_name,$email_message);
									$email_message=str_replace('{task_name}', $task_name, $email_message);
									$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
									$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
									$email_message=str_replace('{task_description}',$task_description,$email_message);
									$email_message=str_replace('{project_name}',$project_name,$email_message);
									$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
									$email_message=str_replace('{completion_user_name}',$completion_user_name,$email_message);
					
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
								if($old_task_detail['task_owner_id']!=$old_task_detail['task_allocated_user_id'] && $old_task_detail['task_allocated_user_id'] !=get_authenticateUserID()){
									//notification
									$notification_text = '"'.$old_task_detail['task_title'].'" is uncompleted by '.usernameById(get_authenticateUserID()).' this user.';
									$notification_data = array(
										'task_id' => $task_id,
										'project_id' => $old_task_detail['task_project_id'],
										'notification_text' => $notification_text,
										'notification_user_id' => $old_task_detail['task_allocated_user_id'],
										'notification_from' =>get_authenticateUserID(),
										'is_read' => '0',
										'date_added' => date("Y-m-d H:i:s")
									);
									$this->db->insert('task_notification',$notification_data);
									
									/*** send email to task owner user for task is completed ****/
									$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
									$email_temp=$email_template->row();
									$email_address_from=$email_temp->from_address;
									$email_address_reply=$email_temp->reply_address;
					
									$email_subject=$email_temp->subject;
									$email_message=$email_temp->message;
					
									$user_info = get_user_info($old_task_detail['task_allocated_user_id']);
									$user_name = $user_info->first_name.' '.$user_info->last_name;
									$task_name = $old_task_detail['task_title'];
					
					
									$email_to = $user_info->email;
									$subscription_link = site_url();
									$allocated_user_name = usernameById($old_task_detail['task_allocated_user_id']);
					
									$email_subject=str_replace('{break}','<br/>',$email_subject);
									$email_subject=str_replace('{user_name}',$user_name,$email_subject);
									$email_subject=str_replace('{task_name}', $task_name, $email_subject);
									$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
									$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
									$email_subject=str_replace('{project_name}',$project_name,$email_subject);
									$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
									$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
					
					
									$email_message=str_replace('{break}','<br/>',$email_message);
									$email_message=str_replace('{user_name}',$user_name,$email_message);
									$email_message=str_replace('{task_name}', $task_name, $email_message);
									$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
									$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
									$email_message=str_replace('{task_description}',$task_description,$email_message);
									$email_message=str_replace('{project_name}',$project_name,$email_message);
									$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
									$email_message=str_replace('{completion_user_name}',$completion_user_name,$email_message);
					
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
							}
							if($old_task_status_id != $task_status_id){
								$old_task_status_name = get_task_status_name_by_id($old_task_status_id);
								$new_task_status_name = get_task_status_name_by_id($task_status_id);
					
								$history_data = array(
									'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
									'history_added_by' => get_authenticateUserID(),
									'task_id' => $task_id,
									'date_added' => date('Y-m-d H:i:s')
								);
								$this->db->insert('task_history',$history_data);
							}
					
							if($old_task_status_id != $task_status_id){
								$user_swimlane = array(
									'kanban_order' => get_user_last_kanban_order($allocated_to,$task_status_id) + 1
								);
								
								$this->db->where('user_id',$allocated_to);
								$this->db->where('task_id',$task_id);
								$this->db->update('user_task_swimlanes',$user_swimlane);
							}
						}
					}
				} else if($name == "task_color_id"){
					
					$old_task_detail = get_task_detail($task_id);
					$allocated_to = $old_task_detail['task_allocated_user_id'];
					
					$user_swimlane = array(
						'color_id' => $value
					);
					$this->db->where('user_id',$allocated_to);
					$this->db->where('task_id',$task_id);
					$this->db->update('user_task_swimlanes',$user_swimlane);
				} else if($name == "task_swimlane_id"){
					
					$old_task_detail = get_task_detail($task_id);
					$allocated_to = $old_task_detail['task_allocated_user_id'];
					
					$user_swimlane = array(
						'swimlane_id' => $value
					);
					$this->db->where('user_id',$allocated_to);
					$this->db->where('task_id',$task_id);
					$this->db->update('user_task_swimlanes',$user_swimlane);
				} else {
					
					$old_task_detail = get_task_detail($task_id);
					
					$data = array($name=>$value);
					
					if($name == "task_title" || $name == "task_description" || $name == "task_priority" || $name == "task_category_id" || $name == "task_sub_category_id"){
						$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
					} else {
						$this->db->where('task_id',$task_id);
					}
					
					$this->db->update('tasks',$data);
					if ($name == 'task_time_estimate') {
                                            //echo $redirect_page; die();
                                            if($this->session->userdata('pricing_module_status')=='1'){
                                                $minute = $value;
                                                $task_details =  get_task_detail($task_id);
                                                $actual_time = get_task_actual_time($task_id);
                                                $charge_out_rate = get_charge_out_rate($task_id);
                                                $base_employee_rate = get_user_cost_per_hour($task_details['task_allocated_user_id']);
                                                if($actual_time == '0'){
                                                    $data = array(
                                                            "cost_per_hour"=>$base_employee_rate,
                                                            "cost"=>round(($base_employee_rate*$minute)/60,2),
                                                            "charge_out_rate"=>$charge_out_rate,
                                                            "estimated_total_charge"=>round(($charge_out_rate*$minute)/60,2),
                                                        );
                                                }else{
                                                    $task_charge_out_rate = get_task_charge_out_rate($task_id);
                                                    $data = array(
                                                        "estimated_total_charge"=>round($task_charge_out_rate*$minute/60,2),
                                                    );  
                                                }
                                                $this->db->where('task_id',$task_id);
                                                $this->db->update('tasks',$data);
                                            }
                                            
                                        }elseif($name == 'customer_id' || $name == 'task_sub_category_id' || $name == 'task_category_id'|| $name == 'task_project_id'){
                                                if($this->session->userdata('pricing_module_status')=='1'){ 
                                                    $estimated_time = get_task_estimated_time($task_id);
                                                    $actual_time = get_task_actual_time($task_id);
                                                    $charge_out_rate = get_charge_out_rate($task_id);
                                                    //if($actual_time =='0'){
                                                        $data1 = array(
                                                                "charge_out_rate"=>$charge_out_rate,
                                                                "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2),
                                                                "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2)
                                                        );
                                                    $this->db->where('task_id',$task_id);
                                                    $this->db->update('tasks',$data1);
                                                   // }
                                                    
                                                }
                                                
                                        }elseif ($name == 'task_time_spent') {
                                                $this->db->set('billed_time',$value);
                                                $this->db->where('task_id',$task_id);
                                                $this->db->update('tasks');
                                                if($this->session->userdata('pricing_module_status')=='1'){ 
                                                    $task_details =  get_task_detail($task_id);
                                                    $minute = $value;
                                                    $base_employee_rate = get_user_cost_per_hour($task_details['task_allocated_user_id']);
                                                    $actual_time = get_task_actual_time($task_id);
                                                    $estimated_time = get_task_estimated_time($task_id);
                                                    $charge_out_rate = get_charge_out_rate($task_id);
                                                    if($actual_time == '0'){
                                                        $data2 = array(
                                                            "cost_per_hour"=>$base_employee_rate,
                                                            "cost"=>round(($base_employee_rate*$estimated_time)/60,2),
                                                            "charge_out_rate"=>$charge_out_rate,
                                                            "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2),
                                                            "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2)
                                                            );
                                                    }else{
                                                        $data2 = array(
                                                            "cost_per_hour"=>$base_employee_rate,
                                                            "charge_out_rate"=>$charge_out_rate,
                                                            "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2),
                                                            "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2)
                                                            );
                                                    }
                                                    $this->db->where('task_id',$task_id);
                                                    $this->db->update('tasks',$data2);
                                                }
                                        }
                        
                    
                                        
					if($name == "task_allocated_user_id"){
                                            if($this->session->userdata('pricing_module_status')=='1'){ 
                                                $task_details =  get_task_detail($task_id);
                                                $estimated_time = get_task_estimated_time($task_id);
                                                $actual_time = get_task_actual_time($task_id);
                                                $rate = get_user_cost_per_hour($task_details['task_allocated_user_id']);
                                                $charge_out_rate = get_charge_out_rate($task_id);
                                                if($actual_time == '0'){
                                                    $data = array(
                                                        "cost_per_hour"=>$rate,
                                                        "cost"=>round(($rate*$estimated_time)/60,2),
                                                        "charge_out_rate"=>$charge_out_rate,
                                                        "estimated_total_charge"=>round($charge_out_rate*$estimated_time/60,2)
                                                    );
                                                    $this->db->where('task_id',$task_id);
                                                    $this->db->update('tasks',$data);
                                                } 
                                                
                                            }
						$old_task_allocated_id = $old_task_detail['task_allocated_user_id'];
						$allocated_to = $value;
						if($old_task_allocated_id != $allocated_to){

							$history_data = array(
								'histrory_title' => 'Task has been reallocated from "'.usernameById($old_task_allocated_id).'" to "'.usernameById($allocated_to).'"',
								'history_added_by' => get_authenticateUserID(),
								'task_id' => $task_id,
								'date_added' => date('Y-m-d H:i:s')
							);
							$this->db->insert('task_history',$history_data);
							
							if($allocated_to != get_authenticateUserID()){
								$notification_data = array(
									'task_id' => $task_id,
									'project_id' => get_project_id_from_task_id($task_id),
									'notification_text' => $this->session->userdata('username').' has assigned the task "'.$old_task_detail['task_title'].'" to you',
									'notification_user_id' => $allocated_to,
									'notification_from' =>get_authenticateUserID(),
									'is_read' => '0',
									'is_allocation_notification' => '1',
									'date_added' => date("Y-m-d H:i:s")
								);
								$this->db->insert('task_notification',$notification_data);
							}
		
							$chk_exist = chk_swim_exist($task_id,$allocated_to);
							if($chk_exist == '0'){
								$user_swimlane = array(
									'user_id' => $allocated_to,
									'task_id' => $task_id,
									'swimlane_id' => get_default_swimlane($allocated_to),
									'kanban_order' => 1,
									'calender_order' => get_user_last_calnder_order($allocated_to,$old_task_detail['task_scheduled_date']) + 1
								);
		
								$this->db->insert('user_task_swimlanes',$user_swimlane);
                                                                $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                                                            SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                                                            WHERE `uts`.`user_id` = '$allocated_to'
                                                                            AND `uts`.`task_id` != '$task_id'
                                                                            AND `t`.`task_status_id` = '$old_task_detail[task_status_id]'
                                                                            ");
                                                                
//								$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//								$this->db->where('uts.user_id', $allocated_to);
//								$this->db->where('uts.task_id != ',$task_id);
//								$this->db->where('t.task_status_id', $old_task_detail['task_status_id']);
//								$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
							}
							
							
							
							if(($old_task_detail['task_owner_id'] != get_authenticateUserID()) && ($old_task_detail['task_allocated_user_id'] != get_authenticateUserID())){
									
								$allocate_user_name = usernameById($allocated_to);
								$assign_by_user = $this->session->userdata('username');
								
								/******/
								$task_name = $old_task_detail['task_title'];
								$owner_name = usernameById($old_task_detail['task_owner_id']);
								if($old_task_detail['task_due_date']!='0000-00-00'){
									$task_due_date = date($this->config->item('company_default_format'),strtotime($old_task_detail['task_due_date']));
								} else {
									$task_due_date = 'N/A';
								}
			
								$task_description = $old_task_detail['task_description'];
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
								
								
								/*** send email to task related other users ****/
								$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to other user'");
								$email_temp=$email_template->row();
								$email_address_from=$email_temp->from_address;
								$email_address_reply=$email_temp->reply_address;
			
								$email_subject=$email_temp->subject;
								$email_message=$email_temp->message;
								
								
								
								$reciver_info = get_user_info($old_task_detail['task_owner_id']);
								$reciver_user_name = $reciver_info->first_name.' '.$reciver_info->last_name;
								
								$email_to = $reciver_info->email;
			
								$email_subject=str_replace('{break}','<br/>',$email_subject);
								$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
			
			
								$email_message=str_replace('{break}','<br/>',$email_message);
								$email_message=str_replace('{user_name}',$reciver_user_name,$email_message);
								$email_message=str_replace('{task_name}',$task_name,$email_message);
								$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
								$email_message=str_replace('{allocated_user_name}',$allocate_user_name,$email_message);
								$email_message=str_replace('{assign_by_user}',$assign_by_user,$email_message);
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
								
								if($old_task_detail['task_owner_id'] != $old_task_detail['task_allocated_user_id']){
									$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to other user'");
									$email_temp=$email_template->row();
									$email_address_from=$email_temp->from_address;
									$email_address_reply=$email_temp->reply_address;
				
									$email_subject=$email_temp->subject;
									$email_message=$email_temp->message;
									
									$reciver_info = get_user_info($old_task_detail['task_allocated_user_id']);
									$reciver_user_name = $reciver_info->first_name.' '.$reciver_info->last_name;
									
									$email_to = $reciver_info->email;
				
									$email_subject=str_replace('{break}','<br/>',$email_subject);
									$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				
				
									$email_message=str_replace('{break}','<br/>',$email_message);
									$email_message=str_replace('{user_name}',$reciver_user_name,$email_message);
									$email_message=str_replace('{task_name}',$task_name,$email_message);
									$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
									$email_message=str_replace('{allocated_user_name}',$allocate_user_name,$email_message);
									$email_message=str_replace('{assign_by_user}',$assign_by_user,$email_message);
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
							}
						}
					} else if($name == "task_title"){
						
						if($old_task_detail['task_title'] != $value){
							$history_data = array(
								'histrory_title' => 'Task name changed from "'.$old_task_detail['task_title'].'" to "'.$value.'"',
								'history_added_by' => get_authenticateUserID(),
								'task_id' => $task_id,
								'date_added' => date('Y-m-d H:i:s')
							);
							$this->db->insert('task_history',$history_data);
						}
					} else if($name == "task_description"){
						if($old_task_detail['task_description'] != $value){
							$history_data = array(
								'histrory_title' => 'Task description changed from "'.$old_task_detail['task_description'].'" to "'.$value.'"',
								'history_added_by' => get_authenticateUserID(),
								'task_id' => $task_id,
								'date_added' => date('Y-m-d H:i:s')
							);
							$this->db->insert('task_history',$history_data);
						}
					} else if($name == "task_priority"){
						if($old_task_detail['task_priority'] != $value){
							$history_data = array(
								'histrory_title' => 'Task priority changed from "'.$old_task_detail['task_priority'].'" to "'.$value.'"',
								'history_added_by' => get_authenticateUserID(),
								'task_id' => $task_id,
								'date_added' => date('Y-m-d H:i:s')
							);
							$this->db->insert('task_history',$history_data);
							
							$multiIds = multiAllocationTaskIds($task_id);
							if($multiIds){
								foreach($multiIds as $mId){
									$history_data = array(
										'histrory_title' => 'Task priority changed from "'.$old_task_detail['task_priority'].'" to "'.$value.'"',
										'history_added_by' => get_authenticateUserID(),
										'task_id' => $mId->task_id,
										'date_added' => date('Y-m-d H:i:s')
									);
									$this->db->insert('task_history',$history_data);
								}
							}
						}
					} else {
						
					}
				}
			}
		}
		
		return $task_id;
	}
	
        /*
	 * Function : get_user_division
	 * Author : Spaculus
	 * Return : array
	 * Desc : get user divisions
	 */
	/**
         * This function is returned user division list.This function select data from table with specific user_id.
         * @param int $user_id
         * @returns int
         */
	function get_user_division($user_id){
		$this->db->select('ud.user_devision_id,d.devision_title');
		$this->db->from('user_devision ud');
		$this->db->join('company_divisions d','d.division_id = ud.devision_id');
		$this->db->where('ud.user_id',$user_id);
		$this->db->where('d.company_id',$this->session->userdata('company_id'));
		$this->db->where('d.devision_status','Active');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	/*
	 * Function : get_user_department
	 * Author : Spaculus
	 * Return : array
	 * Desc : get users all departments
	 */
	/**
         * It returns department list of user for selected user.
         * @param  $user_id
         * @param  $division_id
         * @returns int
         */
	function get_user_department($user_id,$division_id = ''){
		$this->db->select('ud.user_dept_id,d.department_title');
		$this->db->from('user_department ud');
		$this->db->join('company_departments d','d.department_id  = ud.dept_id');
		$this->db->where('ud.user_id',$user_id);
		$this->db->where('d.company_id',$this->session->userdata('company_id'));
		$this->db->where('d.status','Active');
		if($division_id){
			$this->db->where('d.deivision_id',$division_id);
		}
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	/*
	 * Function : get_task_due_date
	 * Author : Spaculus
	 * Return : task due date
	 */
	/**
         * This function returns task due date.
         * @param  $task_id
         * @returns array
         */
	function get_task_due_date($task_id){
		$query = $this->db->get_where('tasks',array('task_id'=>$task_id));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_due_date;
		}
	}

	/*
	 * Function : insert_task_dependencies
	 * Author : Spaculus
	 * Desc : Inserts task dependencies
	 */
	/**
         * This function is used for insert task dependencies in db.This function first insert new task in tasks table than it update dependence in different tables.
         * @returns int
         */
	function insert_task_dependencies(){
		
		$task_data = get_task_detail($this->input->post('task_id'));
		
                /*
                 * check task_allocated_user_id
                 */

		if($this->input->post('task_allocated_user_id')){ $allocated_to = $this->input->post('task_allocated_user_id'); }else{ $allocated_to = $this->session->userdata('user_id');}
		if($this->input->post('dependent_task_due_date')){
				 $due_date = change_date_format($this->input->post('dependent_task_due_date'));
		} else {
			if($this->input->post('main_task_due_date') && $this->input->post('main_task_due_date') != '0000-00-00'){
				$add_days = -1;
				$due_date = change_date_format($this->input->post('main_task_due_date') + (24*3600*$add_days));
			} else {
				$due_date = '';
			}

		}
		
		/*
                 * check task section of project
                 */
		//for project
		if($task_data['section_id']){$section = $task_data['section_id'];}else{$section = '0';}
		if($task_data['subsection_id']){$subsection = $task_data['subsection_id'];}else{$subsection = '0';}
		if($task_data['task_project_id']){$project_id = $task_data['task_project_id'];}else{$project_id = '0';}
		if($project_id != '0'){
			$task_order = get_task_order_by_project($project_id,$section,$subsection);
		} else {
			$task_order = "0";
		}
		
		$ready_id = get_task_status_id_by_name('Ready');
                /*
                 * save task for dependence
                 */
		$data = array(
			'is_prerequisite_task' => '1',
			'prerequisite_task_id' => $this->input->post('task_id'),
			'task_company_id' => $this->session->userdata('company_id'),
			'task_title' => htmlentities($this->input->post('task_title')),
			'task_status_id' => $ready_id,
			'task_due_date' => $due_date,
			'task_scheduled_date' => $due_date,
			'task_orig_due_date' => $due_date,
			'task_orig_scheduled_date' => $due_date,
			'task_owner_id' => $this->session->userdata('user_id'),
			'task_allocated_user_id' => $allocated_to,
			'subsection_id' => $subsection,
			'section_id' => $section,
			'task_order' => $task_order,
			'task_project_id' => $project_id,
			'task_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('tasks',$data);
		$new_task_id = $this->db->insert_id();

		$old_task_status_id = get_taskStatus_id($this->input->post('task_id'));

		$new_task_status_id = get_task_status_id_by_name('Not Ready');

		$update_task = array(
			'task_status_id' => $new_task_status_id
		);
		$this->db->where('task_id',$this->input->post('task_id'));
		$this->db->update('tasks',$update_task);
		
		if($old_task_status_id != $new_task_status_id){

			$old_task_status_name = get_task_status_name_by_id($old_task_status_id);

			$new_task_status_name = 'Not Ready';

			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $this->input->post('task_id'),
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		/*
                 * update swimlane
                 */
		
		$user_swimlane = array(
			'kanban_order' => 1
		);
		$this->db->where('user_id',$task_data['task_allocated_user_id']);
		$this->db->where('task_id',$this->input->post('task_id'));
		$this->db->update('user_task_swimlanes',$user_swimlane);

		$not_ready_task_id = get_task_status_id_by_name('Not Ready');
                $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                  SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                  WHERE `uts`.`user_id` = '$task_data[task_allocated_user_id]'
                                  AND `uts`.`task_id` != '" .$this->input->post('task_id')
                                  ."' AND `t`.`task_status_id` = '$not_ready_task_id'
                                  ");
               
//		$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//		$this->db->where('uts.user_id', $task_data['task_allocated_user_id']);
//		$this->db->where('uts.task_id != ',$this->input->post('task_id'));
//		$this->db->where('t.task_status_id', $not_ready_task_id);
//		$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
                

		$chk_exist = chk_swim_exist($new_task_id,$allocated_to);
		if($chk_exist == '0'){
			$user_swimlane = array(
				'user_id' => $allocated_to,
				'task_id' => $new_task_id,
				//'swimlane_id' => get_default_swimlane($allocated_to),
				'swimlane_id' => $task_data['swimlane_id'],
				'kanban_order' => 1,
				'calender_order' => 1,
				'color_id' => $task_data['color_id']
			);

			$this->db->insert('user_task_swimlanes',$user_swimlane);
                        $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                  SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                  WHERE `uts`.`user_id` = '$allocated_to'
                                  AND `uts`.`task_id` != '$new_task_id'
                                  AND `t`.`task_status_id` = '$ready_id'
                                  ");
                       
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $allocated_to);
//			$this->db->where('uts.task_id != ', $new_task_id);
//			$this->db->where('t.task_status_id', $ready_id);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
                        
                        $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                  SET `uts`.`calender_order` = `uts`.`calender_order` + 1
                                  WHERE `uts`.`user_id` = '$allocated_to'
                                  AND `uts`.`task_id` != '$new_task_id'
                                  AND `t`.`task_scheduled_date` = '$due_date'
                                  ");
                        
//			$this->db->set('uts.calender_order', 'uts.calender_order + 1', FALSE);
//			$this->db->where('uts.user_id', $allocated_to);
//			$this->db->where('uts.task_id != ', $new_task_id);
//			$this->db->where('t.task_scheduled_date', $due_date);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
                        

		} else {
			$user_swimlane = array(
				//'swimlane_id' => get_default_swimlane($allocated_to),
				'swimlane_id' => $task_data['swimlane_id'],
				'kanban_order' => get_user_last_kanban_order($allocated_to, $ready_id) + 1,
				'calender_order' => get_user_last_calnder_order($allocated_to, $due_date) + 1
			);
			$this->db->where('user_id',$allocated_to);
			$this->db->where('task_id',$new_task_id);
			$this->db->update('user_task_swimlanes',$user_swimlane);
		}
		$update_user_order = array('kanban_order'=>get_user_last_kanban_order($allocated_to, get_task_status_id_by_name('Not Ready')) + 1);
		$this->db->where('task_id',$this->input->post('task_id'));
		$this->db->where('user_id',$allocated_to);
		$this->db->update('user_task_swimlanes',$update_user_order);
                /*
                 * check allocated user is not authenticated user than it insert task detail in task_notifiaction table
                 */

		if($allocated_to != get_authenticateUserID()){
			$notification_data = array(
				'task_id' => $new_task_id,
				'project_id' => get_project_id_from_task_id($new_task_id),
				'notification_text' => $this->session->userdata('username').' has assigned the task "'.$this->input->post('task_title').'" to you',
				'notification_user_id' => $allocated_to,
				'notification_from' =>get_authenticateUserID(),
				'is_read' => '0',
				'is_allocation_notification' => '1',
				'date_added' => date("Y-m-d H:i:s")
			);
			$this->db->insert('task_notification',$notification_data);
			
			/*** send email to task allocated user ****/
			/*
                         *  send email to task allocated user
                         */
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
			$email_temp=$email_template->row();
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;

			$email_subject=$email_temp->subject;
			$email_message=$email_temp->message;

			$allocated_user_info = get_user_info($allocated_to);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
			/******/
			$task_name = $this->input->post('task_title');
			$owner_name = $this->session->userdata('username');
			if($due_date!=''){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($due_date));
			} else {
				$task_due_date = 'N/A';
			}
			$task_data = get_task_detail($new_task_id);
			$task_description = $task_data['task_description'];
			if($task_description){
				$task_description = $task_description;
			} else {
				$task_description = 'N/A';
			}
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
		
		/*
                 * insert history data in task_history table
                 */
		$history_data = array(
			'histrory_title' => 'Task dependency added.',
			'history_added_by' => $this->session->userdata('user_id'),
			'task_id' => $this->input->post('task_id'),
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);

		return $new_task_id;
	}

	/*
	 * Function : get_step_last_seq
	 * Author : Spaculus
	 * Desc : Gives steps last max sequence
	 */
	function get_step_last_seq($task_id){
		$this->db->select('MAX(step_sequence) as seq');
		$this->db->from('task_steps');
		$this->db->where('task_id',$task_id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->seq;
		} else {
			return 0;
		}
	}

	/*
	 * Function : insert_task_steps
	 * Author : Spaculus
	 * Desc : Insert task steps
	 */
	function insert_task_steps($step_title,$task_id){
            /*
             * get no. of steps 
             */
               if($task_id !=''){
		$last_seq = $this->get_step_last_seq($task_id);
		$data = array(
			'task_id' => $task_id,
			'step_title' => $step_title,
			'step_added_by' => $this->session->userdata('user_id'),
			'is_completed' => '0',
			'step_sequence' => $last_seq + 1,
			'step_added_date' => date('Y-m-d H:i:s')
		);
                /* insert steps*/

		$this->db->insert('task_steps',$data);
		$id = $this->db->insert_id();
		
		/* step insert message insert in task_history table */
		
		$history_data = array(
			'histrory_title' => 'Task step added.',
			'history_desc' => $step_title,
			'history_added_by' => $this->session->userdata('user_id'),
			'task_id' => $task_id,
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);
                return $id;
               }else{
                   return 0;
               }die();
		$multiIds = multiAllocationTaskIds($task_id);
		if($multiIds){
			foreach($multiIds as $mId){
				$step_data = array(
					'task_id' => $mId->task_id,
					'multi_allocation_step_id' => $id,
					'step_title' => $step_title,
					'step_added_by' => $this->session->userdata('user_id'),
					'is_completed' => 0,
					'step_sequence' => $last_seq + 1,
					'step_added_date' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_steps',$step_data);
				
				$history_data = array(
					'histrory_title' => 'Task step added.',
					'history_desc' => $step_title,
					'history_added_by' => $this->session->userdata('user_id'),
					'task_id' => $mId->task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}
		}

		
		return $id;
	}

	/*
	 * Function : update_task_steps
	 * Author : Spaculus
	 * Desc : Update task steps
	 */
	/**
         * This function is used for update task steps.It get number of steps than it update step in table and task_history table.
         * @param  $step_title
         * @param  $task_id
         * @param  $task_step_id
         * @returns int 
         */
	function update_task_steps($step_title,$task_id,$task_step_id){
            /* get no. of steps than update task_steps table with new steps */
                if($task_id !=''){
                    $last_seq = $this->get_step_last_seq($task_id);
                    $data = array(
                            'task_id' => $task_id,
                            'step_title' => $step_title
                    );
                    //$this->db->where('task_step_id',$task_step_id);
                    $this->db->where('(task_step_id = '.$task_step_id.' or (multi_allocation_step_id = '.$task_step_id.' and is_deleted = 0))');
                    $this->db->update('task_steps',$data);
                    /* Insert step insert history in table */

                    $history_data = array(
                            'histrory_title' => 'Task step updated.',
                            'history_desc' => $step_title,
                            'history_added_by' => $this->session->userdata('user_id'),
                            'task_id' => $task_id,
                            'date_added' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('task_history',$history_data);
                }
		return $task_step_id;
	}

	/*
	 * Function : get_completion_date
	 * Author : Spaculus
	 * Desc : gives task step completion date
	 */
	function get_completion_date($step_id){
		$query = $this->db->get_where('task_steps',array('task_step_id'=>$step_id));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->completion_date;
		} else {
			return 0;
		}
	}

	/*
	 * Function : update_task_step_seq
	 * Author : Spaculus
	 * Desc : Update task step sequence
	 */
	function update_task_step_seq(){

		$serializedData = $_POST['str'];

		$unserializedData = array();
		parse_str($serializedData,$unserializedData);
                $post_data = $_POST['post_data'];
                
		if($unserializedData['task_id']){
			$is_completed  = array();
			$completion_date = array();
			if(isset($_POST['check_array'])){ $check_array = $_POST['check_array']; } else { $check_array = array();}

			if(isset($unserializedData['ids']) && $unserializedData['ids']!=''){
				foreach($unserializedData['ids'] as $key=>$val){
					$completion_date_old = $this->get_completion_date($val);
					if(in_array($val,$check_array)){
						$is_completed[] = '1';
						if($completion_date_old!='0000-00-00 00:00:00'){
							$completion_date[] = $completion_date_old;
						} else {
							$completion_date[] = date('Y-m-d H:i:s');
						}
					} else {
						$is_completed[] = '0';
						$completion_date[] = '0000-00-00 00:00:00';
					}
				}
			}
			
			$total = $unserializedData['total'];
			if($total>0){

				$step_title = $unserializedData['step_title'];
				$task_id = $unserializedData['task_id'];
				$added_by = $unserializedData['added_by'];

				$this->db->delete('task_steps',array('task_id'=>$task_id));
				$multiIds = multiAllocationTaskIds($task_id);
				if($multiIds){
					foreach($multiIds as $mId){
						$this->db->delete('task_steps',array('task_id'=>$mId->task_id));
					}
				}
				for($i=0;$i<$total;$i++){
					$data = array(
						'step_title' => $step_title[$i],
						'task_id' => $task_id,
						'step_added_by' => $added_by[$i],
						'is_completed' => $is_completed[$i],
						'step_sequence' => $i + 1,
						'completion_date' => $completion_date[$i],
						'step_added_date' => date('Y-m-d H:i:s')
					);
					$this->db->insert('task_steps',$data);
					$ins_id = $this->db->insert_id();
					
					if($multiIds){
						foreach($multiIds as $mId){
							$data = array(
								'step_title' => $step_title[$i],
								'multi_allocation_step_id' => $ins_id,
								'task_id' => $mId->task_id,
								'step_added_by' => $added_by[$i],
								'is_completed' => $is_completed[$i],
								'step_sequence' => $i + 1,
								'completion_date' => $completion_date[$i],
								'step_added_date' => date('Y-m-d H:i:s')
							);
							$this->db->insert('task_steps',$data);
						}
					}
				}
			

				$history_data = array(
					'histrory_title' => 'Task step sequence updated.', 
					'history_added_by' => $this->session->userdata('user_id'),
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
	
				return $task_id;
			}
		}
	}

	/*
	 * Function : get_task_file_detail
	 * Author : Spaculus
	 * Return : Task file details by file id
	 */
	function get_task_file_detail($task_file_id){
		$query = $this->db->get_where("task_and_project_files",array('task_file_id'=>$task_file_id));
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}

	/*
	 * Function : add_task_files
	 * Author : Spaculus
	 * Return : Adds task files
	 */
	function add_task_files($task_id){

		if($_POST['msg']=='success'){
			$task_id = $task_id;
                        $task_data = get_task_detail($task_id);
                        $project_id = $task_data['task_project_id'];
                        $task_files = $_POST['uploaded_file_name'];
                        $file_title = $_POST['upload_file_title'];
			$msg = $_POST['msg'];
			$file_data = array(
				'task_file_name' => $task_files,
				'file_title' => $file_title,
				'task_id' => $task_id,
				'project_id' => $project_id,
				'file_added_by' => $this->session->userdata('user_id'),
				'file_date_added' => date('Y-m-d H:i:s')
			);

			$this->db->insert('task_and_project_files',$file_data);
			$id = $this->db->insert_id();
			
			//email variables
			$owner_name = usernameById($task_data['task_owner_id']);
			if($task_data['task_due_date']!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($task_data['task_due_date']));
			} else {
				$task_due_date = 'N/A';
			}
			$task_description = $task_data['task_description'];
			if($task_description){
				$task_description = $task_description;
			} else {
				$task_description = 'N/A';
			}
			$project_name = get_project_name($task_data['task_project_id']);
			if($project_name){
				$project_name = $project_name;
			} else {
				$project_name = 'N/A';
			}
			
			$added_by = $this->session->userdata('username');
			
			
			if($task_data['task_owner_id'] !=get_authenticateUserID()){

				//notification
				$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
				$notification_data = array(
					'task_id' => $task_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_owner_id'],
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);
				$this->db->insert('task_notification',$notification_data);
				
				/*** send email to task owner user for task is completed ****/
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
				$email_temp=$email_template->row();
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;

				$email_subject=$email_temp->subject;
				$email_message=$email_temp->message;

				$user_info = get_user_info($task_data['task_owner_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $task_data['task_title'];


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($task_data['task_allocated_user_id']);

				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
				$email_subject=str_replace('{added_by}',$added_by,$email_subject);
				$email_subject=str_replace('{file_name}',$task_files,$email_subject);


				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
				$email_message=str_replace('{added_by}',$added_by,$email_message);
				$email_message=str_replace('{file_name}',$task_files,$email_message);

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
			if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
				//notification
				$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
				$notification_data = array(
					'task_id' => $task_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_owner_id'],
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);
				$this->db->insert('task_notification',$notification_data);
				
				/*** send email to task allocated user ****/
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
				$email_temp=$email_template->row();
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;

				$email_subject=$email_temp->subject;
				$email_message=$email_temp->message;

				$user_info = get_user_info($task_data['task_allocated_user_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $task_data['task_title'];


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($task_data['task_allocated_user_id']);

				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
				$email_subject=str_replace('{added_by}',$added_by,$email_subject);
				$email_subject=str_replace('{file_name}',$task_files,$email_subject);


				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
				$email_message=str_replace('{added_by}',$added_by,$email_message);
				$email_message=str_replace('{file_name}',$task_files,$email_message);

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
			
			$history_data = array(
				'histrory_title' => 'Task file added',
				'history_desc' => $task_files,
				'history_added_by' => $this->session->userdata('user_id'),
				'task_id' => $task_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
			
			$multiIds = multiAllocationTaskIds($task_id);
			if($multiIds){
				foreach($multiIds as $mId){
					
					$task_data = get_task_detail($mId->task_id);
					$project_id = $task_data['task_project_id'];
					
					$file_data = array(
						'task_file_name' => $task_files,
						'file_title' => $file_title,
						'multi_allocation_file_id' => $id,
						'task_id' => $mId->task_id,
						'project_id' => $project_id,
						'file_added_by' => $this->session->userdata('user_id'),
						'file_date_added' => date('Y-m-d H:i:s')
					);
		
					$this->db->insert('task_and_project_files',$file_data);
					$mid = $this->db->insert_id();
					
					//email variables
					$owner_name = usernameById($task_data['task_owner_id']);
					if($task_data['task_due_date']!='0000-00-00'){
						$task_due_date = date($this->config->item('company_default_format'),strtotime($task_data['task_due_date']));
					} else {
						$task_due_date = 'N/A';
					}
					$task_description = $task_data['task_description'];
					if($task_description){
						$task_description = $task_description;
					} else {
						$task_description = 'N/A';
					}
					$project_name = get_project_name($task_data['task_project_id']);
					if($project_name){
						$project_name = $project_name;
					} else {
						$project_name = 'N/A';
					}
					
					$added_by = $this->session->userdata('username');
					
					
					if($task_data['task_owner_id'] !=get_authenticateUserID()){
		
						//notification
						$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
						$notification_data = array(
							'task_id' => $mId->task_id,
							'project_id' => $task_data['task_project_id'],
							'notification_text' => $notification_text,
							'notification_user_id' => $task_data['task_owner_id'],
							'notification_from' =>get_authenticateUserID(),
							'is_read' => '0',
							'date_added' => date("Y-m-d H:i:s")
						);
						$this->db->insert('task_notification',$notification_data);
						
						/*** send email to task owner user for task is completed ****/
						$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
						$email_temp=$email_template->row();
						$email_address_from=$email_temp->from_address;
						$email_address_reply=$email_temp->reply_address;
		
						$email_subject=$email_temp->subject;
						$email_message=$email_temp->message;
		
						$user_info = get_user_info($task_data['task_owner_id']);
						$user_name = $user_info->first_name.' '.$user_info->last_name;
						$task_name = $task_data['task_title'];
		
		
						$email_to = $user_info->email;
						$subscription_link = site_url();
						$allocated_user_name = usernameById($task_data['task_allocated_user_id']);
		
						$email_subject=str_replace('{break}','<br/>',$email_subject);
						$email_subject=str_replace('{user_name}',$user_name,$email_subject);
						$email_subject=str_replace('{task_name}', $task_name, $email_subject);
						$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
						$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
						$email_subject=str_replace('{project_name}',$project_name,$email_subject);
						$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
						$email_subject=str_replace('{added_by}',$added_by,$email_subject);
						$email_subject=str_replace('{file_name}',$task_files,$email_subject);
		
		
						$email_message=str_replace('{break}','<br/>',$email_message);
						$email_message=str_replace('{user_name}',$user_name,$email_message);
						$email_message=str_replace('{task_name}', $task_name, $email_message);
						$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
						$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
						$email_message=str_replace('{task_description}',$task_description,$email_message);
						$email_message=str_replace('{project_name}',$project_name,$email_message);
						$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
						$email_message=str_replace('{added_by}',$added_by,$email_message);
						$email_message=str_replace('{file_name}',$task_files,$email_message);
		
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
					if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
						//notification
						$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
						$notification_data = array(
							'task_id' => $mId->task_id,
							'project_id' => $task_data['task_project_id'],
							'notification_text' => $notification_text,
							'notification_user_id' => $task_data['task_owner_id'],
							'notification_from' =>get_authenticateUserID(),
							'is_read' => '0',
							'date_added' => date("Y-m-d H:i:s")
						);
						$this->db->insert('task_notification',$notification_data);
						
						/*** send email to task allocated user ****/
						$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
						$email_temp=$email_template->row();
						$email_address_from=$email_temp->from_address;
						$email_address_reply=$email_temp->reply_address;
		
						$email_subject=$email_temp->subject;
						$email_message=$email_temp->message;
		
						$user_info = get_user_info($task_data['task_allocated_user_id']);
						$user_name = $user_info->first_name.' '.$user_info->last_name;
						$task_name = $task_data['task_title'];
		
		
						$email_to = $user_info->email;
						$subscription_link = site_url();
						$allocated_user_name = usernameById($task_data['task_allocated_user_id']);
		
						$email_subject=str_replace('{break}','<br/>',$email_subject);
						$email_subject=str_replace('{user_name}',$user_name,$email_subject);
						$email_subject=str_replace('{task_name}', $task_name, $email_subject);
						$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
						$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
						$email_subject=str_replace('{project_name}',$project_name,$email_subject);
						$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
						$email_subject=str_replace('{added_by}',$added_by,$email_subject);
						$email_subject=str_replace('{file_name}',$task_files,$email_subject);
		
		
						$email_message=str_replace('{break}','<br/>',$email_message);
						$email_message=str_replace('{user_name}',$user_name,$email_message);
						$email_message=str_replace('{task_name}', $task_name, $email_message);
						$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
						$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
						$email_message=str_replace('{task_description}',$task_description,$email_message);
						$email_message=str_replace('{project_name}',$project_name,$email_message);
						$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
						$email_message=str_replace('{added_by}',$added_by,$email_message);
						$email_message=str_replace('{file_name}',$task_files,$email_message);
		
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
					
					$history_data = array(
						'histrory_title' => 'Task file added',
						'history_desc' => $task_files,
						'history_added_by' => $this->session->userdata('user_id'),
						'task_id' => $mId->task_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('task_history',$history_data);
					
				}
			}

			return $id;
		}
        }

	/*
	 * Function : insert_task_comments
	 * Author : Spaculus
	 * Return : Insert task comments
	 */
	function insert_task_comments(){

		$id = '';
		if($_POST['task_comment']){
			$project_id = get_project_id_from_task_id($_POST['task_id']);
			if(isset($_POST['task_comment_id']) && $_POST['task_comment_id'] != ''){
				$data = array(
					'task_comment' => htmlspecialchars($_POST['task_comment']),
					'task_id' => $_POST['task_id'],
					'project_id' => $project_id,
					'comment_addeby' => $this->session->userdata('user_id')
				);
				$this->db->where('task_comment_id',$_POST['task_comment_id']);
				$this->db->update('task_and_project_comments',$data);
				$id = $_POST['task_comment_id'];

				$history_data = array(
					'histrory_title' => 'Task comment updated',
					'history_desc' => $_POST['task_comment'],
					'history_added_by' => $this->session->userdata('user_id'),
					'task_id' => $_POST['task_id'],
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			} else {
				$data = array(
					'task_comment' => htmlspecialchars($_POST['task_comment']),
					'task_id' => $_POST['task_id'],
					'project_id' => $project_id,
					'comment_addeby' => $this->session->userdata('user_id'),
					'comment_added_date' => date('Y-m-d H:i:s')
				);

				$this->db->insert('task_and_project_comments',$data);
				$id = $this->db->insert_id();

				$task_id = $_POST['task_id'];
				//$task_detail = get_task_detail($_POST['task_id']);
				

				
				
				//email
				$task_detail = get_task_detail($_POST['task_id']);
				
				$task_name = $task_detail['task_title'];
				$owner_name = usernameById($task_detail['task_owner_id']);
				if($task_detail['task_due_date']!='0000-00-00'){
					$task_due_date = date($this->config->item('company_default_format'),strtotime($task_detail['task_due_date']));
				} else {
					$task_due_date = 'N/A';
				}
				$task_description = $task_detail['task_description'];
				$project_name = $task_detail['project_title'];
				
				$notification_text = $this->session->userdata('username').' commented on a task '.$task_detail['task_title'];

				if($task_detail['task_owner_id'] != get_authenticateUserID()){
					
					$notification_data = array(
						'task_id' => $task_id,
						'project_id' => $project_id,
						'notification_text' => $notification_text,
						'notification_user_id' => $task_detail['task_owner_id'],
						'notification_from' =>get_authenticateUserID(),
						'is_read' => '0',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->db->insert('task_notification',$notification_data);
					/*** send email to task owner user  ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='comment notification'");
					$email_temp=$email_template->row();
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;

					$email_subject=$email_temp->subject;

					$email_message=$email_temp->message;

					$user_info = get_user_info($task_detail['task_owner_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$comment = $_POST['task_comment'];
					$added_by = $this->session->userdata('username');

					$email_to = $user_info->email;
					$subscription_link = site_url();

					$allocated_user_info = get_user_info($task_detail['task_allocated_user_id']);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;

					$email_subject=str_replace('{break}','<br/>',$email_subject);
					$email_subject=str_replace('{user_name}',$user_name,$email_subject);
					$email_subject=str_replace('{user-name}',$added_by,$email_subject);
					$email_subject=str_replace('{task_name}',$task_name,$email_subject);
					$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
					$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
					$email_subject=str_replace('{project_name}',$project_name,$email_subject);
					$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);

					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{comment}',$comment,$email_message);
					$email_message=str_replace('{user-name}',$added_by,$email_message);
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

				if($task_detail['task_owner_id']!=$task_detail['task_allocated_user_id'] && $task_detail['task_allocated_user_id'] != get_authenticateUserID()){
					
					
					$notification_data = array(
						'task_id' => $task_id,
						'project_id' => $project_id,
						'notification_text' => $notification_text,
						'notification_user_id' => $task_detail['task_allocated_user_id'],
						'notification_from' =>get_authenticateUserID(),
						'is_read' => '0',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->db->insert('task_notification',$notification_data);
					
					/*** send email to task allocated user  ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='comment notification'");
					$email_temp=$email_template->row();
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;

					$email_subject=$email_temp->subject;


					$email_message=$email_temp->message;

					$user_info = get_user_info($task_detail['task_allocated_user_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$comment = $_POST['task_comment'];
					$added_by = $this->session->userdata('username');

					$email_to = $user_info->email;
					$subscription_link = site_url();

					$allocated_user_info = get_user_info($task_detail['task_allocated_user_id']);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;

					$email_subject=str_replace('{break}','<br/>',$email_subject);
					$email_subject=str_replace('{user_name}',$user_name,$email_subject);
					$email_subject=str_replace('{user-name}',$added_by,$email_subject);
					$email_subject=str_replace('{task_name}',$task_name,$email_subject);
					$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
					$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
					$email_subject=str_replace('{project_name}',$project_name,$email_subject);
					$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);


					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{comment}',$comment,$email_message);
					$email_message=str_replace('{user-name}',$added_by,$email_message);
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

				$history_data = array(
					'histrory_title' => 'Task comment added',
					'history_desc' => $_POST['task_comment'],
					'history_added_by' => $this->session->userdata('user_id'),
					'task_id' => $_POST['task_id'],
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}
		}

		
		return $id;
	}

	/*
	 * Function : insert_frequency_data
	 * Author : Spaculus
	 * DEsc : Insert/update task frequency data
	 */
	function insert_frequency_data(){

		if($this->input->post("start_on_date")){
			$start_on_date = change_date_format($this->input->post('start_on_date'));

			if($this->input->post('Weekly_week_day')!=''){
				$Weekly_week_day = implode(',', $this->input->post('Weekly_week_day'));
			} else {
				$Weekly_week_day = '';
			}

			if($this->input->post('end_by_date')){
				$end_by_date = change_date_format($this->input->post('end_by_date'));
			} else {
				$end_by_date = '';
			}
			if($this->input->post('Monthly_op2_1')){ $m_op2_1 = $this->input->post('Monthly_op2_1'); } else { $m_op2_1 = ''; }
			if($this->input->post('Monthly_op2_2')){ $m_op2_2 = $this->input->post('Monthly_op2_2'); } else { $m_op2_2 = ''; }
			if($this->input->post('Yearly_op3_1')){ $y_op3_1 = $this->input->post('Yearly_op3_1'); } else { $y_op3_1 = ''; }
			if($this->input->post('Yearly_op3_2')){ $y_op3_2 = $this->input->post('Yearly_op3_2'); } else { $y_op3_2 = ''; }
			if($this->input->post('Yearly_op3_3')){ $y_op3_3 = $this->input->post('Yearly_op3_3'); } else { $y_op3_3 = ''; }
			if($this->input->post('Yearly_op4_2')){ $y_op4_2 = $this->input->post('Yearly_op4_2'); } else { $y_op4_2 = ''; }

			
			$data = array(
				'frequency_type' => $this->input->post('frequency_type'),
				'recurrence_type' => $this->input->post('recurrence_type'),
				'Daily_every_day' => $this->input->post('Daily_every_day'),
				'Daily_every_weekday' => $this->input->post('Daily_every_weekday'),
				'Daily_every_week_day' => $this->input->post('Daily_every_week_day'),
				'Weekly_every_week_no' => $this->input->post('Weekly_every_week_no'),
				'Weekly_week_day' => $Weekly_week_day,
				'Monthly_op1_1' => $this->input->post('Monthly_op1_1'),
				'Monthly_op1_2' => $this->input->post('Monthly_op1_2'),
				'Monthly_op2_1' => $m_op2_1,
				'Monthly_op2_2' => $m_op2_2,
				'Monthly_op2_3' => $this->input->post('Monthly_op2_3'),
				'Monthly_op3_1' => $this->input->post('Monthly_op3_1'),
				'Monthly_op3_2' => $this->input->post('Monthly_op3_2'),
				'Yearly_op1' => $this->input->post('Yearly_op1'),
				'Yearly_op2_1' => $this->input->post('Yearly_op2_1'),
				'Yearly_op2_2' => $this->input->post('Yearly_op2_2'),
				'Yearly_op3_1' => $y_op3_1,
				'Yearly_op3_2' => $y_op3_2,
				'Yearly_op3_3' => $y_op3_3,
				'Yearly_op4_1' => $this->input->post('Yearly_op4_1'),
				'Yearly_op4_2' => $y_op4_2,
				'monthly_radios' => $this->input->post('monthly_radios'),
				'yearly_radios' => $this->input->post('yearly_radios'),
				'start_on_date' => $start_on_date,
				'no_end_date' => $this->input->post('no_end_date'),
				'end_after_recurrence' => $this->input->post('end_after_recurrence'),
				'end_by_date' => $end_by_date

			);

			$this->db->where('task_id',$this->input->post('task_id'));
			$this->db->update('tasks',$data);
		}
	}

	/*
	 * Function : get_search_task_dependencies
	 * Author : Spaculus
	 * DEsc : Gives serach task dependencies
	 */
	function get_search_task_dependencies(){
		$name = $_POST['task_name'];
		if($_POST['search_date']){ $date = change_date_format($_POST['search_date']); } else { $date =''; };
		$main_task_id = $_POST['search_task_id'];

		$this->db->select('*');
		$this->db->from('tasks');
		$this->db->where('prerequisite_task_id',$main_task_id);
		$this->db->where('is_prerequisite_task','1');
		$this->db->where('is_deleted <>','1');
		if($name){
			$this->db->like('task_title',$name);
		}
		if($date){
			$this->db->where('task_due_date',$date);
		}
		$this->db->where('task_owner_id != ',"0");
		$this->db->where('task_allocated_user_id != ',"0");
		$query = $this->db->get();

		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}

	/*
	 * Function : get_project_sections_by_id
	 * Author : Spaculus
	 * Return : project section  list by id
	 */
	function get_project_sections_by_id($project_id){
		$query = $this->db->select('section_id,section_name')->from('project_section')->where('project_id',$project_id)->where('main_section','0')->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	// task related functionality for mobile website
	/*
	 * Function : get_TaskDetailByID
	 * Author : Spaculus
	 * Return : This function is used in mobile for task detail from task id.
	 */
	/**
         *  This function is used in mobile for task detail from task id.
         * @param  $task_id
         * @returns int
         */
	function get_TaskDetailByID($task_id){
		
		if (strpos($task_id, 'child_') !== false) {
		   	$task_id = explode('_', $task_id);
			$task_id = $task_id[1];
		}else{
			$task_id = $task_id;
		}
		
		$query = $this->db->select('t.*,ps.section_name,ps.section_order,ps.subsection_order')
							->from('tasks t')
							->join('project_section ps','t.task_project_id = ps.project_id AND ps.section_id = t.subsection_id','left')
							->where('t.task_id',$task_id)
							->where('t.task_owner_id != ',"0")
							->where('t.task_allocated_user_id != ',"0")
							->get();
							
							//echo $this->db->last_query();die;

		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : insert_task
	 * Author : Spaculus
	 * Return : inserted id of task
	 */
	/**
         * This function is used for insert task in task table.It checks is_personal and lock_due_date than it insert task in table and update task_history table.
         * @param  $from
         * @returns int
         */
	function insert_task($from = ""){
		
		if($from == "virtual"){
			$old_task_data = get_task_detail($this->input->post('master_task_id'));
			if($this->input->post('old_task_due_date')){
				$old_due_date = change_date_format($this->input->post('old_task_due_date'));
			} else {
				$old_due_date = '';
			}
			$allocated_to = $old_task_data['task_allocated_user_id'];
		} else {
			$old_task_data = "";
			if($this->input->post('redirect_page') == "from_kanban"){
				$allocated_to = $this->session->userdata("Temp_kanban_user_id");
			} else if($this->input->post('redirect_page') == "from_calendar" || $this->input->post('redirect_page') == "NextFiveDay" || $this->input->post('redirect_page') == "FiveWeekView" || $this->input->post('redirect_page') == "weekView"){
				$allocated_to = $this->session->userdata("Temp_calendar_user_id");
			} else {
				$allocated_to = get_authenticateUserID();
			}
		}
                /* check is_personal and locaked_due_date input*/

		if($this->input->post('is_personal')){ $is_personal = '1'; } else { $is_personal = '0'; }
		if($this->input->post('hdn_locked_due_date')){ $locked_due_date = '1'; } else { $locked_due_date = '0'; }
		$task_time_spent = (($this->input->post('task_time_spent_hour') * 60 ) + $this->input->post('task_time_spent_min'));
		$task_time_estimate = (($this->input->post('task_time_estimate_hour') * 60 ) + $this->input->post('task_time_estimate_min'));

		if($this->input->post('task_due_date')){
			$due_date = change_date_format($this->input->post('task_due_date'));
		} else {
			 $due_date = '';
		}
		if($this->input->post('task_orig_scheduled_date') && $this->input->post('task_orig_scheduled_date')!='0000-00-00'){ $orig_scheduled_date = change_date_format($this->input->post('task_orig_scheduled_date'));} else { $orig_scheduled_date = '';}
		if($this->input->post('task_orig_due_date') && $this->input->post('task_orig_due_date')!='0000-00-00'){ $orig_due_date = change_date_format($this->input->post('task_orig_due_date'));} else { $orig_due_date = '';}
		if($this->input->post('task_scheduled_date') && $this->input->post('task_scheduled_date')!='0000-00-00'){ $scheduled_date = change_date_format($this->input->post('task_scheduled_date')); } else { $scheduled_date = $due_date; }
		
		
		/* check section of project */
		//for project
		if($this->input->post('section_id')!=''){$section = $this->input->post('section_id');}else{$section = '0';}
		if($this->input->post('subsection_id')!=''){$subsection = $this->input->post('subsection_id');}else{$subsection = '0';}
		if($this->input->post('general_project_id')!=''){$project_id = $this->input->post('general_project_id');}else{$project_id = '0';}
		if($project_id != '0'){
			$task_order = get_task_order_by_project($project_id,$section,$subsection);
		} else {
			$task_order = "0";
		}
                if($this->input->post('master_task_id')!=''){$master_task_id=$this->input->post('master_task_id');}else{$master_task_id='0';}
		/* insert task */
		$data = array(
			'task_company_id' => $this->session->userdata('company_id'),
			'task_title' => $this->input->post('task_title'),
			'task_description' => $this->input->post('task_description'),
			'is_personal' => $is_personal,
			'task_priority' => $this->input->post('task_priority'),
			'task_category_id' => $this->input->post('task_category_id'),
			'locked_due_date' => $locked_due_date,
			'task_due_date' => $due_date,
			'task_sub_category_id' => $this->input->post('task_sub_category_id'),
			'task_scheduled_date' => $scheduled_date,
			'task_orig_scheduled_date' => $orig_scheduled_date,
			'task_orig_due_date' => $orig_due_date,
			'task_time_spent' => $task_time_spent,
			'task_time_estimate' => $task_time_estimate,
			'task_owner_id' => get_authenticateUserID(),
			'task_allocated_user_id' => $allocated_to,
			'task_status_id' => $this->input->post('task_status_id'),
			'master_task_id' => $master_task_id,
			'subsection_id' => $section,
			'section_id' => '0',
			'task_order' => $task_order,
			'task_project_id' => $project_id,
			'task_added_date' => date('Y-m-d H:i:s'),
                        'billed_time'=>$task_time_spent
		);
		
		$this->db->insert('tasks',$data);
		$task_id = $this->db->insert_id();


		if($this->config->item('completed_id') == $this->input->post('task_status_id')){
			$updated_task = array(
				'task_completion_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where("task_id",$task_id);
			$this->db->update("tasks",$updated_task);
		}


		$chk_exist = chk_swim_exist($task_id,$allocated_to);
		if($chk_exist == '0'){
			$user_swimlane = array(
				'user_id' => $allocated_to,
				'task_id' => $task_id,
				'swimlane_id' => get_default_swimlane($allocated_to),
				'color_id' => $this->input->post('task_color_id'),
				'kanban_order' => 1,
				'calender_order' => get_user_last_calnder_order($allocated_to,$scheduled_date) + 1
			);

			$this->db->insert('user_task_swimlanes',$user_swimlane);
                         $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                  SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                  WHERE `uts`.`user_id` = '$allocated_to'
                                  AND `uts`.`task_id` != '$task_id'
                                  AND `t`.`task_status_id` = '".$this->input->post('task_status_id')."'
                                  ");
                         
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $allocated_to);
//			$this->db->where('uts.task_id != ',$task_id);
//			$this->db->where('t.task_status_id', $this->input->post('task_status_id'));
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
		}

		$history_data = array(
			'histrory_title' => 'Task created.',
			'history_added_by' => $this->session->userdata('user_id'),
			'task_id' => $task_id,
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);


		if($old_task_data){
			if($old_task_data['task_title'] != $this->input->post('task_title')){
				$history_data = array(
					'histrory_title' => 'Task name changed from "'.$old_task_data['task_title'].'" to "'.$this->input->post('task_title').'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $this->input->post('task_id'),
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}

			if($old_task_data['task_description'] != $this->input->post('task_description')){
				$history_data = array(
					'histrory_title' => 'Task description changed from "'.$old_task_data['task_description'].'" to "'.$this->input->post('task_description').'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $this->input->post('task_id'),
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}

			if($old_task_data['task_priority'] != $this->input->post('task_priority')){
				$history_data = array(
					'histrory_title' => 'Task priority changed from "'.$old_task_data['task_priority'].'" to "'.$this->input->post('task_priority').'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $this->input->post('task_id'),
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}

			if($old_task_data['task_status_id'] != $this->input->post('task_status_id')){
				$history_data = array(
					'histrory_title' => 'Task status changed from "'.get_task_status_name_by_id($old_task_data['task_status_id']).'" to "'.get_task_status_name_by_id($this->input->post('task_status_id')).'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $this->input->post('task_id'),
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}

			if($old_due_date != $due_date){
				$history_data = array(
					'histrory_title' => 'Task due date changed from "'.$old_due_date.'" to "'.$due_date.'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $this->input->post('task_id'),
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}

		}
		
		if($allocated_to != get_authenticateUserID()){
			$notification_data = array(
				'task_id' => $this->input->post('task_id'),
				'project_id' => get_project_id_from_task_id($this->input->post('task_id')),
				'notification_text' => $this->session->userdata('username').' has assigned the task "'.$this->input->post('task_title').'" to you',
				'notification_user_id' => $allocated_to,
				'notification_from' =>get_authenticateUserID(),
				'is_read' => '0',
				'date_added' => date("Y-m-d H:i:s")
			);
			$this->db->insert('task_notification',$notification_data);
			
			/*** send email to task allocated user ****/
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
			$email_temp=$email_template->row();
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;

			$email_subject=$email_temp->subject;
			$email_message=$email_temp->message;

			$allocated_user_info = get_user_info($allocated_to);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
			/******/
			$task_name = $this->input->post('task_title');
			$owner_name = $this->session->userdata('username');
			if($due_date!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($due_date));
			} else {
				$task_due_date = 'N/A';
			}

			$task_description = $this->input->post('task_description');
			$project_id = get_project_id_from_task_id($this->input->post('task_id'));
			if($project_id){
				$project_name = get_project_name($project_id);
			} else {
				$project_name = '';
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

	/*
	 * Function : update_task
	 * Author : Spaculus
	 * Return : task-id
	 * Desc : update task data
	 */
	/**
         * This function is used for update task.It checks redirect page than it update task table with new info.and at last it insert update task message in task_history table.
         * @returns void
         */
	function update_task(){
		
		//pr($_POST);	die;

		$is_scheduled_date_changed = 0;
		$old_task_detail = get_task_detail($this->input->post('task_id'));
		$is_scheduled = $old_task_detail['is_scheduled'];
		$due_date = $old_task_detail['task_due_date'];
		$old_task_status_id = $old_task_detail['task_status_id'];
		$allocated_to = $old_task_detail['task_allocated_user_id'];
		if($this->input->post('task_due_date')){
			$new_due_date = change_date_format($this->input->post('task_due_date'));
		} else {
			$new_due_date = '';
		}


		if($new_due_date){
			if($due_date != '0000-00-00'){
				if(strtotime($due_date) != strtotime($new_due_date)){
                                    /* check redirect_page */
					if($_POST['redirect_page'] == 'from_kanban' || $_POST['redirect_page'] == 'from_teamdashboard' || $_POST['redirect_page'] == 'from_dashboard' || $_POST['redirect_page'] == 'from_project'){
						$is_scheduled = '1';
						$is_scheduled_date_changed = 1;
						$scheduled_date = $new_due_date;
					} else {
						if($this->input->post('task_scheduled_date')){
							$scheduled_date = change_date_format($this->input->post('task_scheduled_date'));
						} else {
							$scheduled_date = '';
						}
					}
				} else {
					if($_POST['redirect_page'] == 'from_kanban' || $_POST['redirect_page'] == 'from_teamdashboard' || $_POST['redirect_page'] == 'from_dashboard' || $_POST['redirect_page'] == 'from_project'){
						$scheduled_date = $new_due_date;
					} else {
						if($this->input->post('task_scheduled_date')){
							$scheduled_date = change_date_format($this->input->post('task_scheduled_date'));
						} else {
							$scheduled_date = '';
						}
					}
				}
			} else {

				if($_POST['redirect_page'] == 'from_kanban' || $_POST['redirect_page'] == 'from_teamdashboard' || $_POST['redirect_page'] == 'from_dashboard' || $_POST['redirect_page'] == 'from_project'){
					$is_scheduled = '1';
					$is_scheduled_date_changed = 1;
					$scheduled_date = $new_due_date;
				} else {
					if($this->input->post('task_scheduled_date')){
						$scheduled_date = change_date_format($this->input->post('task_scheduled_date'));
					} else {
						$scheduled_date = '';
					}
				}
			}
		} else {
			if($_POST['redirect_page'] == 'from_kanban' || $_POST['redirect_page'] == 'from_teamdashboard' || $_POST['redirect_page'] == 'from_dashboard' || $_POST['redirect_page'] == 'from_project'){
				$scheduled_date = $new_due_date;
			} else {
				if($this->input->post('task_scheduled_date')){
					$scheduled_date = change_date_format($this->input->post('task_scheduled_date'));
				} else {
					$scheduled_date = '';
				}
			}
		}
		$task_time_spent = (($this->input->post('task_time_spent_hour') * 60 ) + $this->input->post('task_time_spent_min'));
		$task_time_estimate = (($this->input->post('task_time_estimate_hour') * 60 ) + $this->input->post('task_time_estimate_min'));

		$is_dependency_added = get_task_dependencies_ids($this->input->post('task_id'));
		if($is_dependency_added){
			$task_status_id = get_task_status_id_by_name("Not Ready");
		} else {
			$task_status_id = $this->input->post('task_status_id');
		}

		if($this->input->post('is_personal')){ $is_personal = '1'; } else { $is_personal = '0'; }
		if($this->input->post('hdn_locked_due_date')){ $locked_due_date = '1'; } else { $locked_due_date = '0'; }

		if($this->input->post('task_swimlane_id')){ $swimlane_id = $this->input->post('task_swimlane_id'); } else { $swimlane_id = get_default_swimlane(get_authenticateUserID());}
		if($this->input->post('task_orig_scheduled_date') && $this->input->post('task_orig_scheduled_date')!='0000-00-00'){ $orig_scheduled_date = change_date_format($this->input->post('task_orig_scheduled_date'));} else { $orig_scheduled_date = '';}
		if($this->input->post('task_orig_due_date') && $this->input->post('task_orig_due_date')!='0000-00-00'){ $orig_due_date = change_date_format($this->input->post('task_orig_due_date'));} else { $orig_due_date = '';}
		$data = array(
			'task_company_id' => $this->session->userdata('company_id'),
			'task_title' => $this->input->post('task_title'),
			'task_description' => $this->input->post('task_description'),
			'is_personal' => $is_personal,
			'task_priority' => $this->input->post('task_priority'),
			'task_category_id' => $this->input->post('task_category_id'),
			'locked_due_date' => $locked_due_date,
			'task_due_date' => $new_due_date,
			'task_sub_category_id' => $this->input->post('task_sub_category_id'),
			'task_owner_id' => $this->input->post('task_owner_id'),
			'task_allocated_user_id' => $this->input->post('task_allocated_user_id'),
			'task_status_id' => $task_status_id,
			'master_task_id' => $this->input->post('master_task_id'),
			'is_scheduled' => $is_scheduled,
			'task_scheduled_date' => $scheduled_date,
			'task_orig_scheduled_date' => $orig_scheduled_date,
			'task_orig_due_date' => $orig_due_date,
			'task_time_spent' => $task_time_spent,
			'task_time_estimate' => $task_time_estimate,
                        'billed_time'=>$task_time_spent
		);
		$this->db->where('task_id',$this->input->post('task_id'));
		$this->db->update('tasks',$data);
		//echo $this->db->last_query();die;
		if($old_task_detail['task_title'] != $this->input->post('task_title')){
			$history_data = array(
				'histrory_title' => 'Task name changed from "'.$old_task_detail['task_title'].'" to "'.$this->input->post('task_title').'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $this->input->post('task_id'),
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		if($old_task_detail['task_description'] != $this->input->post('task_description')){
			$history_data = array(
				'histrory_title' => 'Task description changed from "'.$old_task_detail['task_description'].'" to "'.$this->input->post('task_description').'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $this->input->post('task_id'),
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		if($old_task_detail['task_priority'] != $this->input->post('task_priority')){
			$history_data = array(
				'histrory_title' => 'Task priority changed from "'.$old_task_detail['task_priority'].'" to "'.$this->input->post('task_priority').'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $this->input->post('task_id'),
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		if($old_task_detail['task_due_date'] != $new_due_date){
			$history_data = array(
				'histrory_title' => 'Task due date changed from "'.$old_task_detail['task_due_date'].'" to "'.$new_due_date.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $this->input->post('task_id'),
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		if($this->config->item('completed_id') == $task_status_id){
			$updated_task = array(
				'task_completion_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where("task_id",$this->input->post('task_id'));
			$this->db->update("tasks",$updated_task);
		} else {
			$updated_task = array(
				'task_completion_date'=>''
			);
			$this->db->where("task_id",$this->input->post('task_id'));
			$this->db->update("tasks",$updated_task);
			
			
			if($old_task_detail['task_status_id'] == $this->config->item('completed_id')  && $this->input->post('task_status_id') != $this->config->item('completed_id')){
				//notification
				$notification_text = '"'.$this->input->post('task_title').'" is uncompleted by '.$this->session->userdata('username').' this user.';
				if(($this->input->post('task_owner_id')!=get_authenticateUserID())){
					$notification_data = array(
						'task_id' => $id,
						'project_id' => $old_task_detail['task_project_id'],
						'notification_text' => $notification_text,
						'notification_user_id' => $this->input->post('task_owner_id'),
						'notification_from' =>get_authenticateUserID(),
						'is_read' => '0',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->db->insert('task_notification',$notification_data);
				}
			}
			
			
			
			
		}

		if($this->input->post('kanban_order')){ $kanban_order = $this->input->post('kanban_order'); } else { $kanban_order = get_user_last_kanban_order(get_authenticateUserID(), $task_status_id) + 1; }
		if($this->input->post('calender_order')){ $calender_order = $this->input->post('calender_order'); } else { $calender_order = get_user_last_calnder_order(get_authenticateUserID(), $scheduled_date) + 1; }

		$chk_exist = chk_swim_exist($this->input->post('task_id'),$allocated_to);
		if($chk_exist == '0'){
			$user_swimlane = array(
				'user_id' => $allocated_to,
				'task_id' => $this->input->post('task_id'),
				'swimlane_id' => $swimlane_id,
				'color_id' => $this->input->post('task_color_id'),
				'kanban_order' => $kanban_order,
				'calender_order' => $calender_order
			);
			$this->db->insert('user_task_swimlanes',$user_swimlane);

		} else {
			$user_swimlane = array(
				'color_id' => $this->input->post('task_color_id')
			);
			$this->db->where('user_id',$allocated_to);
			$this->db->where('task_id',$this->input->post('task_id'));
			$this->db->update('user_task_swimlanes',$user_swimlane);
		}
		if($old_task_status_id != $task_status_id){

			$old_task_status_name = get_task_status_name_by_id($old_task_status_id);
			$new_task_status_name = get_task_status_name_by_id($task_status_id);

			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $this->input->post('task_id'),
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		if(($old_task_status_id != $task_status_id) || $is_scheduled_date_changed == "1"){

			if($is_scheduled_date_changed == "1"){
				$user_swimlane = array(
					'calender_order' => get_user_last_calnder_order($allocated_to,$scheduled_date) + 1
				);
			} else {
				$user_swimlane = array(
					'kanban_order' => get_user_last_kanban_order($allocated_to,$task_status_id) + 1
				);
			}
			$this->db->where('user_id',$allocated_to);
			$this->db->where('task_id',$this->input->post('task_id'));
			$this->db->update('user_task_swimlanes',$user_swimlane);

		}

		return $this->input->post('task_id');
	}

	// task related functionality for mobile website
	
	function saveUploadLink($task_id){
		
		$file_name = isset($_POST['file_name'])?$_POST['file_name']:'';
		$file_link = isset($_POST['file_link'])?$_POST['file_link']:'';
		
		if($file_link){
			$task_id = $task_id;
			$task_data = get_task_detail($task_id);
			$project_id = $task_data['task_project_id'];

			$task_files = '';
			$msg = '';
			$bucket = $this->config->item('bucket_name');

			if($file_name){

         		$task_files = $file_name;
				
			}

			$file_data = array(
				'task_file_name' => $task_files,
				'file_link' => $file_link,
				'task_id' => $task_id,
				'project_id' => $project_id,
				'file_added_by' => $this->session->userdata('user_id'),
				'file_date_added' => date('Y-m-d H:i:s')
			);

			$this->db->insert('task_and_project_files',$file_data);
			$id = $this->db->insert_id();
			
			//email variables
			$owner_name = usernameById($task_data['task_owner_id']);
			if($task_data['task_due_date']!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($task_data['task_due_date']));
			} else {
				$task_due_date = 'N/A';
			}
			$task_description = $task_data['task_description'];
			if($task_description){
				$task_description = $task_description;
			} else {
				$task_description = 'N/A';
			}
			$project_name = get_project_name($task_data['task_project_id']);
			if($project_name){
				$project_name = $project_name;
			} else {
				$project_name = 'N/A';
			}
			
			$added_by = $this->session->userdata('username');
			
			
			if($task_data['task_owner_id'] !=get_authenticateUserID()){

				//notification
				$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
				$notification_data = array(
					'task_id' => $task_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_owner_id'],
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);
				$this->db->insert('task_notification',$notification_data);
				
				/*** send email to task owner user for task is completed ****/
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
				$email_temp=$email_template->row();
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;

				$email_subject=$email_temp->subject;
				$email_message=$email_temp->message;

				$user_info = get_user_info($task_data['task_owner_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $task_data['task_title'];


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($task_data['task_allocated_user_id']);

				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
				$email_subject=str_replace('{added_by}',$added_by,$email_subject);
				$email_subject=str_replace('{file_name}',$task_files,$email_subject);


				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
				$email_message=str_replace('{added_by}',$added_by,$email_message);
				$email_message=str_replace('{file_name}',$task_files,$email_message);

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
			if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
				//notification
				$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
				$notification_data = array(
					'task_id' => $task_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_owner_id'],
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);
				$this->db->insert('task_notification',$notification_data);
				
				/*** send email to task allocated user ****/
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
				$email_temp=$email_template->row();
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;

				$email_subject=$email_temp->subject;
				$email_message=$email_temp->message;

				$user_info = get_user_info($task_data['task_allocated_user_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $task_data['task_title'];


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($task_data['task_allocated_user_id']);

				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
				$email_subject=str_replace('{added_by}',$added_by,$email_subject);
				$email_subject=str_replace('{file_name}',$task_files,$email_subject);


				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
				$email_message=str_replace('{added_by}',$added_by,$email_message);
				$email_message=str_replace('{file_name}',$task_files,$email_message);

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
			
			$history_data = array(
				'histrory_title' => 'Task file added',
				'history_desc' => $task_files,
				'history_added_by' => $this->session->userdata('user_id'),
				'task_id' => $task_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
			
			$multiIds = multiAllocationTaskIds($task_id);
			if($multiIds){
				foreach($multiIds as $mId){
					$task_data = get_task_detail($mId->task_id);
					$project_id = $task_data['task_project_id'];
		
					$file_data = array(
						'task_file_name' => $task_files,
						'file_link' => $file_link,
						'multi_allocation_file_id' => $id,
						'task_id' => $mId->task_id,
						'project_id' => $project_id,
						'file_added_by' => $this->session->userdata('user_id'),
						'file_date_added' => date('Y-m-d H:i:s')
					);
		
					$this->db->insert('task_and_project_files',$file_data);
					$mid = $this->db->insert_id();
					
					//email variables
					$owner_name = usernameById($task_data['task_owner_id']);
					if($task_data['task_due_date']!='0000-00-00'){
						$task_due_date = date($this->config->item('company_default_format'),strtotime($task_data['task_due_date']));
					} else {
						$task_due_date = 'N/A';
					}
					$task_description = $task_data['task_description'];
					if($task_description){
						$task_description = $task_description;
					} else {
						$task_description = 'N/A';
					}
					$project_name = get_project_name($task_data['task_project_id']);
					if($project_name){
						$project_name = $project_name;
					} else {
						$project_name = 'N/A';
					}
					
					$added_by = $this->session->userdata('username');
					
					
					if($task_data['task_owner_id'] !=get_authenticateUserID()){
		
						//notification
						$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
						$notification_data = array(
							'task_id' => $mId->task_id,
							'project_id' => $task_data['task_project_id'],
							'notification_text' => $notification_text,
							'notification_user_id' => $task_data['task_owner_id'],
							'notification_from' =>get_authenticateUserID(),
							'is_read' => '0',
							'date_added' => date("Y-m-d H:i:s")
						);
						$this->db->insert('task_notification',$notification_data);
						
						/*** send email to task owner user for task is completed ****/
						$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
						$email_temp=$email_template->row();
						$email_address_from=$email_temp->from_address;
						$email_address_reply=$email_temp->reply_address;
		
						$email_subject=$email_temp->subject;
						$email_message=$email_temp->message;
		
						$user_info = get_user_info($task_data['task_owner_id']);
						$user_name = $user_info->first_name.' '.$user_info->last_name;
						$task_name = $task_data['task_title'];
		
		
						$email_to = $user_info->email;
						$subscription_link = site_url();
						$allocated_user_name = usernameById($task_data['task_allocated_user_id']);
		
						$email_subject=str_replace('{break}','<br/>',$email_subject);
						$email_subject=str_replace('{user_name}',$user_name,$email_subject);
						$email_subject=str_replace('{task_name}', $task_name, $email_subject);
						$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
						$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
						$email_subject=str_replace('{project_name}',$project_name,$email_subject);
						$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
						$email_subject=str_replace('{added_by}',$added_by,$email_subject);
						$email_subject=str_replace('{file_name}',$task_files,$email_subject);
		
		
						$email_message=str_replace('{break}','<br/>',$email_message);
						$email_message=str_replace('{user_name}',$user_name,$email_message);
						$email_message=str_replace('{task_name}', $task_name, $email_message);
						$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
						$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
						$email_message=str_replace('{task_description}',$task_description,$email_message);
						$email_message=str_replace('{project_name}',$project_name,$email_message);
						$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
						$email_message=str_replace('{added_by}',$added_by,$email_message);
						$email_message=str_replace('{file_name}',$task_files,$email_message);
		
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
					if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
						//notification
						$notification_text = 'A file "'.$task_files.'" has been added by "'.$this->session->userdata('username').'" in task "'.$task_data['task_title'].'"';
						$notification_data = array(
							'task_id' => $mId->task_id,
							'project_id' => $task_data['task_project_id'],
							'notification_text' => $notification_text,
							'notification_user_id' => $task_data['task_owner_id'],
							'notification_from' =>get_authenticateUserID(),
							'is_read' => '0',
							'date_added' => date("Y-m-d H:i:s")
						);
						$this->db->insert('task_notification',$notification_data);
						
						/*** send email to task allocated user ****/
						$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='file added in task'");
						$email_temp=$email_template->row();
						$email_address_from=$email_temp->from_address;
						$email_address_reply=$email_temp->reply_address;
		
						$email_subject=$email_temp->subject;
						$email_message=$email_temp->message;
		
						$user_info = get_user_info($task_data['task_allocated_user_id']);
						$user_name = $user_info->first_name.' '.$user_info->last_name;
						$task_name = $task_data['task_title'];
		
		
						$email_to = $user_info->email;
						$subscription_link = site_url();
						$allocated_user_name = usernameById($task_data['task_allocated_user_id']);
		
						$email_subject=str_replace('{break}','<br/>',$email_subject);
						$email_subject=str_replace('{user_name}',$user_name,$email_subject);
						$email_subject=str_replace('{task_name}', $task_name, $email_subject);
						$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
						$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
						$email_subject=str_replace('{project_name}',$project_name,$email_subject);
						$email_subject=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_subject);
						$email_subject=str_replace('{added_by}',$added_by,$email_subject);
						$email_subject=str_replace('{file_name}',$task_files,$email_subject);
		
		
						$email_message=str_replace('{break}','<br/>',$email_message);
						$email_message=str_replace('{user_name}',$user_name,$email_message);
						$email_message=str_replace('{task_name}', $task_name, $email_message);
						$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
						$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
						$email_message=str_replace('{task_description}',$task_description,$email_message);
						$email_message=str_replace('{project_name}',$project_name,$email_message);
						$email_message=str_replace('{task_allocated_to_name}',$allocated_user_name,$email_message);
						$email_message=str_replace('{added_by}',$added_by,$email_message);
						$email_message=str_replace('{file_name}',$task_files,$email_message);
		
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
					
					$history_data = array(
						'histrory_title' => 'Task file added',
						'history_desc' => $task_files,
						'history_added_by' => $this->session->userdata('user_id'),
						'task_id' => $mId->task_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('task_history',$history_data);
				}
			}
			return $id;
		}
	}

	function save_multiple_tasks($allocated_id,$multi_task_id){
		
		$old_task_data = get_task_detail($multi_task_id);
		
		
		$task_data = array(
			'multi_allocation_task_id' => $multi_task_id,
			'task_company_id' => $old_task_data['task_company_id'],
			'task_title' => $old_task_data['task_title'],
			'task_description' => $old_task_data['task_description'],
			'is_personal' => $old_task_data['is_personal'],
			'task_priority' => $old_task_data['task_priority'],
			//'task_division_id' => $old_task_data['task_division_id'],
			//'task_department_id' => $old_task_data['task_department_id'],
			'task_category_id' => $old_task_data['task_category_id'],
			'locked_due_date' => $old_task_data['locked_due_date'],
			'task_due_date' => change_date_format($old_task_data['task_due_date']),
			'task_scheduled_date' => change_date_format($old_task_data['task_scheduled_date']),
			'task_orig_scheduled_date' => change_date_format($old_task_data['task_orig_scheduled_date']),
			'task_orig_due_date' => change_date_format($old_task_data['task_orig_due_date']),
			'task_sub_category_id' => $old_task_data['task_sub_category_id'],
			//'task_skill_id' => $old_task_data['task_skill_id'],
			'task_staff_level_id' => $old_task_data['task_staff_level_id'],
			'task_owner_id' => get_authenticateUserID(),
			'task_allocated_user_id' => $allocated_id,
			'task_time_spent' => 0,
			'task_time_estimate' => $old_task_data['task_time_estimate'],
			'task_status_id' => $old_task_data['task_status_id'],
			'task_project_id' => $old_task_data['task_project_id'],
			'section_id' =>  $old_task_data['section_id'],
			'subsection_id' =>  $old_task_data['subsection_id'],
			'task_added_date' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('tasks',$task_data);
		$task_id = $this->db->insert_id();
		
		if($this->config->item('completed_id') == $old_task_data['task_status_id']){
			$updated_task = array(
				'task_time_spent' => $old_task_data['task_time_spent'],
				'task_completion_date'=>date('Y-m-d H:i:s'),
                                'billed_time' => $old_task_data['task_time_spent']
			);
			$this->db->where("task_id",$task_id);
			$this->db->update("tasks",$updated_task);
		}
		
		
		$history_data = array(
			'histrory_title' => 'Task created.',
			'history_added_by' => get_authenticateUserID(),
			'task_id' => $task_id,
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);
		
		$chk_exist = chk_swim_exist($task_id,$allocated_id);
		if($chk_exist == '0'){
			$user_swimlane = array(
				'user_id' => $allocated_id,
				'task_id' => $task_id,
				'swimlane_id' => get_default_swimlane($allocated_id),
				'color_id' => get_default_color($allocated_id),
				'kanban_order' => 1,
				'calender_order' => get_user_last_calnder_order($allocated_id, date('Y-m-d',strtotime(str_replace(array("/"," ",","),"-", $old_task_data['task_scheduled_date']))))+1
			);
			$this->db->insert('user_task_swimlanes',$user_swimlane);
                        $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                  SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                  WHERE `uts`.`user_id` = '$allocated_to'
                                  AND `uts`.`task_id` != '$task_id'
                                  AND `t`.`task_status_id` = '$old_task_data[task_status_id]'
                                  ");
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $allocated_id);
//			$this->db->where('uts.task_id != ',$task_id);
//			$this->db->where('t.task_status_id', $old_task_data['task_status_id']);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
			
		} else {
			$user_swimlane = array(
				'swimlane_id' => get_default_swimlane($allocated_id),
				'color_id' => get_default_color($allocated_id),
				'kanban_order' => get_user_last_kanban_order($allocated_id, $old_task_data['task_status_id']) + 1,
				'calender_order' => get_user_last_calnder_order($allocated_id, date('Y-m-d',strtotime(str_replace(array("/"," ",","),"-", $old_task_data['task_scheduled_date']))))+1
			);
			$this->db->where('user_id',$allocated_id);
			$this->db->where('task_id',$task_id);
			$this->db->update('user_task_swimlanes',$user_swimlane);
		}
		
		
		if($allocated_id != get_authenticateUserID()){
			$notification_data = array(
				'task_id' => $task_id,
				'project_id' => $old_task_data['task_project_id'],
				'notification_text' => $this->session->userdata('username').' has assigned the task "'.$old_task_data['task_title'].'" to you',
				'notification_user_id' => $allocated_id,
				'notification_from' =>get_authenticateUserID(),
				'is_read' => '0',
				'is_allocation_notification' => '1',
				'date_added' => date("Y-m-d H:i:s")
			);
			$this->db->insert('task_notification',$notification_data);
			
			/*** send email to task allocated user ****/
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
			$email_temp=$email_template->row();
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;

			$email_subject=$email_temp->subject;
			$email_message=$email_temp->message;

			$allocated_user_info = get_user_info($allocated_id);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
			/******/
			$task_name = $old_task_data['task_title'];
			$owner_name = $this->session->userdata('username');
			$due_date = change_date_format($old_task_data['task_due_date']);
			if($due_date!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($due_date));
			} else {
				$task_due_date = 'N/A';
			}
			$task_description = $old_task_data['task_description'];
			$project_id = $old_task_data['task_project_id'];
			if($task_description){
				$task_description = $task_description;
			} else {
				$task_description = 'N/A';
			}
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
		
	}

	function remove_multiple_tasks($allocated_id,$multi_task_id){
		$data = array(
			'is_deleted' => 1
		);
		$this->db->where('task_allocated_user_id',$allocated_id);
		$this->db->where('multi_allocation_task_id',$multi_task_id);
		$this->db->update('tasks',$data);
	}
	
	function get_multiallocation_taks($task_id){
		$query = $this->db->select('t.task_id,t.task_allocated_user_id,t.task_status_id,ts.task_status_name')
						  ->from('tasks t')
						  ->join('task_status ts','ts.task_status_id = t.task_status_id','left')
						  ->where('t.multi_allocation_task_id',$task_id)
						  ->where('t.is_deleted','0')
						  ->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
        
        /************ New method for mobile api  ************/
        
        
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
        function saveTask($owner_id,$company_id,$task_title,$task_description,$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time)
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
                        'billed_time'=>$task_actual_time
                );
		
		$this->db->insert('tasks',$data);
		$task_id = $this->db->insert_id();
                
                $data1 = array(
                             'cost_per_hour'=> get_user_cost_per_hour($task_allocated_user_id, $company_id),
                             'charge_out_rate'=> get_charge_out_rate($task_id,$company_id)
                            );
                $this->db->where('task_id',$task_id);
                $this->db->update('tasks',$data1);
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
		
							
								$user_swimlane = array(
									'user_id' => $task_allocated_user_id,
									'task_id' => $task_id,
									'swimlane_id' => get_default_swimlane($task_allocated_user_id),
								);
		
								$this->db->insert('user_task_swimlanes',$user_swimlane);
                                                                
                                                                
							
							
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
                $allocated_id = isset($task_allocated_user_id)?$task_allocated_user_id:$task['task_allocated_user_id'];
                $swimlane_id1 = get_default_swimlane($allocated_id);
                $user_swimlane1 = array(
                                        'user_id' => $allocated_id,
                                        'task_id' => $task_id,
                                        'swimlane_id' => $swimlane_id1,
                                        'color_id' => 0,
                                        'kanban_order' => 1,
                                        'calender_order' => get_user_last_calnder_order($allocated_id) + 1
                                        );
                $this->db->insert('user_task_swimlanes',$user_swimlane1);
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
                        'billed_time' => $task_actual_time
			
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
		
							
								$user_swimlane = array(
									'user_id' => $task_allocated_user_id,
									'task_id' => $task_id,
									'swimlane_id' => get_default_swimlane($task_allocated_user_id),
                                                                    );
		
								$this->db->insert('user_task_swimlanes',$user_swimlane);
                                                        
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
                //$task_id = $task_id;
		
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
		 
	function copy_task($where,$task_due_date){
			 $this->db->select('`master_task_id`, `is_prerequisite_task`, `prerequisite_task_id`, `multi_allocation_task_id`, `task_company_id`, `task_project_id`, `section_id`, `subsection_id`, `section_order`, `subsection_order`, `task_order`, `task_title`, `task_description`, `is_personal`, `task_priority`, `task_status_id`, `task_division_id`, `task_department_id`, `task_category_id`, `task_color_id`, `task_staff_level_id`, `task_sub_category_id`, `task_skill_id`, `task_due_date`, `task_scheduled_date`, `task_orig_scheduled_date`, `task_orig_due_date`, `is_scheduled`, `task_time_estimate`, `task_owner_id`, `task_allocated_user_id`, `locked_due_date`, `task_time_spent`, `frequency_type`, `recurrence_type`, `Daily_every_day`, `Daily_every_weekday`, `Weekly_every_week_no`, `Weekly_week_day`, `monthly_radios`, `Monthly_op1_1`, `Monthly_op1_2`, `Monthly_op2_1`, `Monthly_op2_2`, `Monthly_op2_3`, `Monthly_op3_1`, `Monthly_op3_2`, `yearly_radios`, `Yearly_op1`, `Yearly_op2_1`, `Yearly_op2_2`, `Yearly_op3_1`, `Yearly_op3_2`, `Yearly_op3_3`, `Yearly_op4_1`, `Yearly_op4_2`, `start_on_date`, `no_end_date`, `end_after_recurrence`, `end_by_date`, `task_added_date`, `task_completion_date`, `is_deleted`, `Daily_every_week_day`, `customer_id`');
			 $this->db->from('tasks');
			 $this->db->where($where);
			 $query=$this->db->get();
			 if($query->num_rows>0)
			 {
				 $this->db->insert("tasks", $query->row());
				 $insert_id = $this->db->insert_id();
				 $where1=array(
				 "task_id"=>$insert_id
				 );
				 $update=array(
				 'task_added_date'=>date('Y-m-d H:i:s'),
				 'master_task_id'=>0,
				 'frequency_type'=>'one_off',
				 'recurrence_type'=>0
				 );
				 $taskdetail=$query->row_array();
				 if($taskdetail['task_due_date']=='0000-00-00')
				 {
					 $update['task_due_date']=$task_due_date;
					 $update['task_scheduled_date']=$task_due_date;
					 $update['task_orig_scheduled_date']=$task_due_date;
					 $update['task_orig_due_date']=$task_due_date;
				 }
				 $this->db->where($where1);
				 $this->db->update('tasks',$update);
				 $where['user_id']=$taskdetail['task_allocated_user_id'];
				 $this->db->select('`user_id`, `task_id`, `swimlane_id`, `color_id`, `kanban_order`, `calender_order`, `task_ex_pos`, `is_deleted`, `swimlane_height`');
				 $this->db->from('user_task_swimlanes');
				 $this->db->where($where);
				 $query1=$this->db->get();
				 if($query1->num_rows>0)
				 {
					 $row1=$query1->row_array();
					 $row1['task_id']=$insert_id;
					 $this->db->insert('user_task_swimlanes',$row1);
				 }
				 return $insert_id;
			 }
			 else
				 return 0;
			 
		 }
		 
        
        /****** End Api methods ******/

        function schedule_backlog_task($data){
                     
                        if($data['due_date'] != '0000-00-00'){
			 $update=array(
                                        "task_scheduled_date"=>$data['scheduled_date'],
                            );
                        }else{
                            $update=array(
                                    "task_scheduled_date"=>$data['scheduled_date'],
                                    "task_due_date"=>$data['scheduled_date'],
                            );
                        }  
			 $this->db->where('task_id',$data['task_id']);
			 $this->db->update('tasks',$update);
			 //echo $this->db->last_query();
		 }
                 
                 
        function getsearchtask(){
            $today_date = date("Y-m-d");
            $this->db->select('t.task_id,t.task_status_id,t.task_title,t.task_description,t.task_owner_id,u2.first_name as owner_first_name,u2.last_name as owner_last_name,u.user_id,t.task_allocated_user_id,u.first_name as allocated_user_first_name,u.last_name as allocated_user_last_name,t.task_priority,t.task_project_id,p.project_title,t.task_category_id,t.task_sub_category_id,t.task_time_estimate,t.task_time_spent,t.task_added_date,t.task_due_date,t.task_scheduled_date,t.task_completion_date,uts.color_id,uc.name,tc.category_name,tsc.category_name as sub_category_name,(SELECT customer_name FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as customer_name,(SELECT external_id FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as external_id,t.cost_per_hour,t.cost,t.charge_out_rate,t.estimated_total_charge,t.actual_total_charge,ts.task_status_name');
            $this->db->from('tasks t');
            $this->db->join('user_task_swimlanes uts','uts.task_id = t.task_id','left');
            $this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
            $this->db->join('users u2','u2.user_id = t.task_owner_id','left');
            $this->db->join('project p','p.project_id = t.task_project_id','left');
            $this->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
            $this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
            $this->db->join('task_category tsc','tsc.category_id = t.task_sub_category_id','left');
            $this->db->join('task_status ts','ts.task_status_id = t.task_status_id','left');
            $this->db->where('t.task_owner_id !=','');
            $this->db->where('t.task_scheduled_date ',$today_date);
            $this->db->where('t.task_allocated_user_id',  get_authenticateUserID());
            $this->db->where('t.is_deleted','0');
            $query = $this->db->get();
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return 0;
            }
        }
        
        function getFilteredData($data){
            if(isset($data)){
                $status_array = 0;
                $today_date = date("Y-m-d");
                $this->db->select('t.task_id,t.task_status_id,t.task_title,t.task_description,t.task_owner_id,u2.first_name as owner_first_name,u2.last_name as owner_last_name,u.user_id,t.task_allocated_user_id,u.first_name as allocated_user_first_name,u.last_name as allocated_user_last_name,t.task_priority,t.task_project_id,p.project_title,t.task_category_id,t.task_sub_category_id,t.task_time_estimate,t.task_time_spent,t.task_added_date,t.task_due_date,t.task_scheduled_date,t.task_completion_date,uts.color_id,uc.name,tc.category_name,tsc.category_name as sub_category_name,(SELECT customer_name FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as customer_name,(SELECT external_id FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as external_id,t.cost_per_hour,t.cost,t.charge_out_rate,t.estimated_total_charge,t.actual_total_charge,ts.task_status_name');
                $this->db->from('tasks t');
                $this->db->join('user_task_swimlanes uts','uts.task_id = t.task_id','left');
                $this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
                $this->db->join('users u2','u2.user_id = t.task_owner_id','left');
                $this->db->join('project p','p.project_id = t.task_project_id','left');
                $this->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
                $this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
                $this->db->join('task_category tsc','tsc.category_id = t.task_sub_category_id','left');
                $this->db->join('task_status ts','ts.task_status_id = t.task_status_id','left');
                $this->db->where('t.task_owner_id !=','');
                $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
                $this->db->where('t.is_deleted','0');
                if(isset($data['projects'])){
                    $this->db->where_in('t.task_project_id',$data['projects']);
                    $status_array = 1;
                }
                if(isset($data['customers'])){
                    $this->db->where_in('t.customer_id',$data['customers']);
                    $status_array = 1;
                }
                if(isset($data['users'])){
                    $this->db->where_in('t.task_allocated_user_id',$data['users']);
                    $status_array = 1;
                }
                if(isset($data['start_date']) && $data['start_date'] !='' && isset($data['end_date']) && $data['end_date'] !=''){
                    if(isset($data['by_date']) && $data['by_date']!=''){
                        foreach($data['by_date'] as $by){
                            if($by == 'scheduled_date'){
                                $this->db->where('t.task_scheduled_date >=',$data['start_date']);
                                $this->db->where('t.task_scheduled_date <=',$data['end_date']);
                            }else if($by == "completion_date"){
                                $this->db->where("DATE_FORMAT(`t`.`task_completion_date`,'%Y-%m-%d') >=",$data['start_date']);
                                $this->db->where("DATE_FORMAT(`t`.`task_completion_date`,'%Y-%m-%d') <=",$data['end_date']);
                            }else if($by == 'due_date'){
                                $this->db->where('t.task_due_date >=',$data['start_date']);
                                $this->db->where('t.task_due_date <=',$data['end_date']);
                            }
                        }
                    }else{
                        $this->db->where('t.task_scheduled_date >=',$data['start_date']);
                        $this->db->where('t.task_scheduled_date <=',$data['end_date']);
                    }
                    $status_array = 1;
                }
                if(isset($data['task_status']) && $data['task_status'] !=''){
                    $this->db->where_in('t.task_status_id ',$data['task_status']);
                    $status_array = 1;
                }
                if(isset($data['category']) && $data['category'] !=''){
                    $this->db->where_in('t.task_category_id ',$data['category']);
                    $status_array = 1;
                }
                if(isset($data['subcategory']) && $data['subcategory'] !=''){
                    $this->db->where_in('t.task_sub_category_id ',$data['subcategory']);
                    $status_array = 1;
                }
                if(isset($data['division']) && $data['division'] !=''){
                    $this->db->where_in('t.task_division_id ',$data['division']);
                    $status_array = 1;
                }
                if(isset($data['department']) && $data['department'] !=''){
                    $this->db->where_in('t.task_department_id ',$data['department']);
                    $status_array = 1;
                }
                if($status_array == 0){
                    $this->db->where('t.task_scheduled_date ',$today_date);
                    $this->db->where('t.task_allocated_user_id',  get_authenticateUserID());
                }
                $this->db->group_by('t.task_id');
                $query = $this->db->get();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
            }
        }
}
?>
