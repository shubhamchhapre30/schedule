<?php
/**
 * This model class is created for database interaction with kanban controller,it is used to access data from database. 
 * And it have various function for interaction  with db like save_task, save_last_remember etc.
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Kanban_model extends CI_model{
      /**
        * It default constuctor which is called when kanban_model object is initialzied.It load base class methods & variables.
        * @returns void
        */
	function Kanban_model(){
            /**
             * Call base class constructor
             */
		parent :: __construct();	
	}
	/**
         * This function is used for save task in db.It will call when task is not existed in db then
         * It will insert new task in db.And it will send mail & notification of new task of appropriate user and task_owner.
         * @param  $data
         * @returns void
         */
	function save_task($data){
		
		$old_task_data = get_task_detail($data['master_task_id']);
		
		
		$task_data = array(
			'master_task_id' => $data['master_task_id'],
			'task_company_id' => $data['task_company_id'],
			'task_title' => $data['task_title'],
			'task_description' => $data['task_description'],
			'is_personal' => $data['is_personal'],
			'task_priority' => $data['task_priority'],
			'task_division_id' => $data['task_division_id'],
			'task_department_id' => $data['task_department_id'],
			'task_category_id' => $data['task_category_id'],
			'locked_due_date' => $data['locked_due_date'],
			'task_due_date' => change_date_format($data['task_due_date']),
			'task_scheduled_date' => change_date_format($data['task_scheduled_date']),
			'task_orig_scheduled_date' => change_date_format($data['task_orig_scheduled_date']),
			'task_orig_due_date' => change_date_format($data['task_orig_due_date']),
			'task_sub_category_id' => $data['task_sub_category_id'],
			'task_skill_id' => $data['task_skill_id'],
			'task_staff_level_id' => $data['task_staff_level_id'],
			'task_owner_id' => $data['task_owner_id'],
			'task_allocated_user_id' => $data['task_allocated_user_id'],
			'task_time_spent' => $data['task_time_spent'],
			'task_time_estimate' => $data['task_time_estimate'],
			'task_status_id' => $data['task_status_id'],
			'task_project_id' => $data['task_project_id'],
			'section_id' =>  $data['section_id'],
			'subsection_id' =>  $data['subsection_id'],
			'task_added_date' => date('Y-m-d H:i:s'),
                        'customer_id'=> $data['customer_id'],
                        'cost_per_hour'=>$data['cost_per_hour'],
                        'cost'=>$data['cost'],
                        'charge_out_rate'=>$data['charge_out_rate'],
                        'estimated_total_charge'=>$data['estimated_total_charge'],
                        'actual_total_charge'=>$data['actual_total_charge']
		);
		/**
                 * This query will save task in DB
                 */
		$this->db->insert('tasks',$task_data);
		$task_id = $this->db->insert_id();
		
		
		if($this->config->item('completed_id') == $data['task_status_id']){
			$updated_task = array(
				'task_completion_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where("task_id",$task_id);
			$this->db->update("tasks",$updated_task);
		}
		
		/**
                 * insert task history
                 */
		$history_data = array(
			'histrory_title' => 'Task created.',
			'history_added_by' => $data['task_owner_id'],
			'task_id' => $task_id,
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);
		
		if($old_task_data){
			if($old_task_data['task_title'] != $data['task_title']){
				$history_data = array(
					'histrory_title' => 'Task name changed from "'.$old_task_data['task_title'].'" to "'.$data['task_title'].'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $data['task_id'],
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}
			
			if($old_task_data['task_description'] != $data['task_description']){
				$history_data = array(
					'histrory_title' => 'Task description changed from "'.$old_task_data['task_description'].'" to "'.$data['task_description'].'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $data['task_id'],
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}
	
			if($old_task_data['task_priority'] != $data['task_priority']){
				$history_data = array(
					'histrory_title' => 'Task priority changed from "'.$old_task_data['task_priority'].'" to "'.$data['task_priority'].'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $data['task_id'],
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}
	
			if($old_task_data['task_status_id'] != $data['task_status_id']){
				$history_data = array(
					'histrory_title' => 'Task status changed from "'.get_task_status_name_by_id($old_task_data['task_status_id']).'" to "'.get_task_status_name_by_id($data['task_status_id']).'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $data['task_id'],
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}
		}
		
		if(isset($data['color_id'])){
			$color_id = $data['color_id'];
		} else {
			$color_id = $old_task_data['color_id'];
		}
		
		$chk_exist = chk_swim_exist($task_id,$data['task_allocated_user_id']);
                /**
                 * check task existance
                 */
		if($chk_exist == '0'){
			$user_swimlane = array(
				'user_id' => $data['task_allocated_user_id'],
				'task_id' => $task_id,
				'swimlane_id' => get_default_swimlane($data['task_allocated_user_id']),
				'color_id' => $color_id,
				'kanban_order' => 1,
				'calender_order' => get_user_last_calnder_order($data['task_allocated_user_id'], date('Y-m-d',strtotime(str_replace(array("/"," ",","),"-", $data['task_scheduled_date']))))+1
			);
			$this->db->insert('user_task_swimlanes',$user_swimlane);
			$this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                            SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                            WHERE `uts`.`user_id` = '$data[task_allocated_user_id]'
                                            AND `uts`.`task_id` != $task_id
                                            AND `t`.`task_status_id` = '$data[task_status_id]'
                                            ");
                       /* This query is not worked for query builder */
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $data['task_allocated_user_id']);
//			$this->db->where('uts.task_id != ',$task_id);
//			$this->db->where('t.task_status_id', $data['task_status_id']);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
	
		} else {
			$user_swimlane = array(
				'swimlane_id' => get_default_swimlane($data['task_allocated_user_id']),
				'color_id' => $color_id,
				'kanban_order' => get_user_last_kanban_order($data['task_allocated_user_id'], $data['task_status_id']) + 1,
				'calender_order' => get_user_last_calnder_order($data['task_allocated_user_id'], date('Y-m-d',strtotime(str_replace(array("/"," ",","),"-", $data['task_scheduled_date']))))+1
			);
			$this->db->where('user_id',$data['task_allocated_user_id']);
			$this->db->where('task_id',$task_id);
			$this->db->update('user_task_swimlanes',$user_swimlane);
		}
		/**
                 * check task allocation id is authenticated or not for task_notification insertion
                 */
		
		if($data['task_allocated_user_id'] != get_authenticateUserID()){
			$notification_data = array(
				'task_id' => $task_id,
				'project_id' => $data['task_project_id'],
				'notification_text' => $this->session->userdata('username').' has assigned the task "'.$data['task_title'].'" to you',
				'notification_user_id' => $data['task_allocated_user_id'],
				'notification_from' =>get_authenticateUserID(),
				'is_read' => '0',
				'is_allocation_notification' => '1',
				'date_added' => date("Y-m-d H:i:s")
			);
			$this->db->insert('task_notification',$notification_data);
			
			/**
                         *  send email to task allocated user 
                         */
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
			$email_temp=$email_template->row();
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;

			$email_subject=$email_temp->subject;
			$email_message=$email_temp->message;

			$allocated_user_info = get_user_info($data['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
			/******/
			$task_name = $data['task_title'];
			$owner_name = usernameById($data['task_owner_id']);
			$due_date = change_date_format($data['task_due_date']);
                        /**
                         * check due_date format
                         */
			if($due_date!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($due_date));
			} else {
				$task_due_date = 'N/A';
			}
			$task_data = get_task_detail($task_id);
			$task_description = $task_data['task_description'];
			$project_id = $task_data['task_project_id'];
                        /**
                         * check task_description variable have string or not
                         */
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
		
		
		return $task_id;
	}
	
	/**
         * This function will save last task remember summery in db.
         * @returns void
         */
	function save_last_remember(){
		
		$chk_remember_exist = chk_last_remember_exists();
		
		if(isset($_POST['kanban_project_id']) && $_POST['kanban_project_id']!=''){ $project_id = $_POST['kanban_project_id']; } else { $project_id = ''; }
		if(isset($_POST['user_color_id'])!='0'){$user_color_id = $_POST['user_color_id'];}else{$user_color_id = "0";}
		if(isset($_POST['due_task']) && $_POST['due_task']!= ''){ $types = $_POST['due_task']; } else { $types = ''; }
		if(isset($_POST['kanban_team_user_id'])){ $user_team_id = $_POST['kanban_team_user_id']; } else { $user_team_id = $this->session->userdata('user_id'); }
		
		
		if($chk_remember_exist == '1'){
			$data = array(
				'kanban_project_id' => 'all',
				'due_task' => $types,
				'kanban_team_user_id' => get_authenticateUserID(),
				'user_color_id' =>$user_color_id
			);
			$this->db->where('user_id',$this->session->userdata('user_id'));
			$this->db->update('last_remember_search',$data);
		} else {
			$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'kanban_project_id' => 'all',
				'due_task' => $types,
				'kanban_team_user_id' => get_authenticateUserID(),
				'user_color_id' =>$user_color_id
			);
			$this->db->insert('last_remember_search',$data);
		}
	}
	
	/**
         * It will save swim lane height in db and return this height also.
         * @param  $swimlane_id
         * @param  $swimlane_height
         * @returns int
         */
	function set_swimlane_height_by_user($swimlane_id,$swimlane_height)
	{
		$data = array(
			'swimlane_height' => $swimlane_height
		);
                /**
                 * update query
                 */
		$this->db->where(array('user_id'=>$this->session->userdata('Temp_kanban_user_id'),'swimlanes_id'=>$swimlane_id));
		$this->db->update('swimlanes',$data);
			
		return $swimlane_height;
	}	
	
}

?>
