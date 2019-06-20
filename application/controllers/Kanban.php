<?php
/**
 * This class is used to render kanban board,this kanban board is a kind of dashboard where user can see the assigned task on same page.
    And create new task also. It gives multiples options for change task color,priority and so on.
    It will define each tasks by its respective status or stage of production.
 * There is following task status are represented by columns
 * Not ready - Start working on the tasks at this stage. 
 * Ready - This stage will be showing that task for their activities to be flagged as Ready or completed within on set due date.
 * In progress - Tasks that currently working on will be automatically assigned to this column.
 * Completed - Tasks that  have completed and flagged as done will automatically be allocated to the Completed status.
 * This class is extending the SPACULLUS_Controller subclasses are instantiated, and are significantly easier to create when they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Kanban extends SPACULLUS_Controller{
	/**
	 * It default constuctor which is called when kanban object is initialzied.It load necesary models.
         * @returns void 
         */

	
	/*   
	 Function name :Kanban()
	 Description :Its Default Constuctor which called when kanban object initialzie.its load necesary models
	 */
	
	function Kanban(){
            /**
             *  Base class contructor
             */
		parent :: __construct ();
            /**
              *  Amazon s3 server configuration
              */
		$this->load->library('s3');
            /**
             * Amazon S3 Configuration
             */    
		$this->config->load('s3');
             /**
              * Load kanban_model for database
              */   
		$this->load->model('kanban_model');
                /**
                 * Set default timezone
                 */
		date_default_timezone_set("UTC");
		ini_set('max_execution_time', 0);
        
	}
	/**
        * This function will call when user click on kanban link.It is used for create kanban view with task.
          And it will check authentication whether user is authenticated or not.
          It will fetch values from custom functions for draw kanban view.
        * @returns void
        */  

	function myKanban(){
                /**
                 * This function check whether user is authenticated or not.if user is authenticated it will 
                 * redirect on home.
                 */
		if(!check_user_authentication()){
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
	
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		
		$data['error'] = '';
		
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
		
		$data['last_rember_values'] = $last_remembers = get_user_last_rember_values();
                /**
                 * It will fetch details and check last remembers details
                 */
		if($last_remembers){
			if($last_remembers->due_task){
				$due_task = $last_remembers->due_task;
			} else {
				$due_task = '';
			}
			
			$team_user_id = $last_remembers->kanban_team_user_id;
			if($last_remembers->kanban_project_id){
				$project_id = $last_remembers->kanban_project_id;
			} else {
				$project_id = '';
			}
			$user_color_id = $last_remembers->user_color_id;
			$data['swimlanes'] = get_user_swimlanes($team_user_id);
		} else {
			$due_task = '';
			$team_user_id = '';
			$project_id = '';
			$user_color_id ='';
			$data['swimlanes'] = get_user_swimlanes($this->session->userdata('user_id'));
		}
		/**
                 * Get kanban task
                 */
		$data['kanban_task'] = get_kanban_tasks($data['task_status'],$data['swimlanes'],$due_task,$team_user_id,$project_id,$user_color_id);
		//echo "<pre>"; print_r($data['kanban_task']); die();
		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array( 
            'general' => 0,
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0
		);
		$data['users'] = get_user_list();
		
		$data['divisions'] = getUserDivision($this->session->userdata('Temp_kanban_user_id'));
		$data['departments'] = getUserDepartment($this->session->userdata('Temp_kanban_user_id'));
		$data['customers']=  getCustomerList();
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_kanban_user_id'));
		$data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_kanban_user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
                $data['all_report_user']=get_list_user_report_to_adminstartor();
		$this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);
		$this->template->write_view('content_side', $theme.'/layout/kanban/myKanban', $data, TRUE);
		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
		
	}
        /**
        *  This function will call when user click on load more button click in kanban view.
            And it will render compeleted task page.
        * @returns void
        */ 
   
	/*
	 * Function : completed_loadmore
	 * Author : Spaculus
	 * Desc : This function is use to print completed task in load more button click in kanban view.
	*/
    function completed_loadmore()
	{
		$theme = getThemeName();
		$swimlane_id = $_POST["swimlane_id"];
		$status_id =  $_POST["status_id"];
		$limit = $_POST["limit_complete"];
		$offset = 20;
		$data["estimat_orig"] = $_POST["estimate"];
	    $data["spent_orig"] = $_POST["spent"];
				
		$limit_whole = $limit;
		$data["limit"] = 20;
		$data["limit_whole"] = $limit_whole;
		
		$data['last_rember_values'] = $last_remembers = get_user_last_rember_values();
                /**
                 * check last remembers values
                 */
		if($last_remembers){
			if($last_remembers->due_task){
				$due_task = $last_remembers->due_task;
			} else {
				$due_task = '';
			}
			
			$team_user_id = $last_remembers->kanban_team_user_id;
			if($last_remembers->kanban_project_id){
                            /**
                             * explode funtion convert string into array with , separator
                             */
				$project_id = $last_remembers->kanban_project_id;
			} else {
				$project_id = '';
			}
			$user_color_id = $last_remembers->user_color_id;
		} else {
			$due_task = '';
			$team_user_id = '';
			$project_id = '';
			$user_color_id = "0";
		}
		$data["completed_id"] = $status_id;
		$data["ready_id"] = get_task_status_id_by_name('Ready');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		
	
		$data['kanban_task_completed'] = get_kanban_tasks_onlycompleted($status_id,$swimlane_id,$due_task,$team_user_id,$project_id,$user_color_id,"20",$limit_whole);
		/**
                 * this load view of complete task.
                 */
		$this->load->view($theme.'/layout/kanban/load_completed_task', $data);
		
	} 
      /**
        *  This function will call when user click on checkbox of complete step on kanban view. It will check task id for update steps status in DB .
           Then it will access that function for change step status as step have completed.
          
        * @returns void
        */ 
	
	
	/*
	 * Function : set_step_complete
	 * Author : Spaculus
	 * Desc : This function is use to complete the steps
	*/
	function set_step_complete(){
		
		$step_id = $_POST['step_id'];
		$task_id = $_POST['task_id'];
		$task_exists = chk_task_exists($task_id);
                $post_data = isset($_POST['post_data'])?json_decode($_POST['post_data'],true):'';
                /**
                 * check task exist or not
                 */
		if($task_exists == '0'){
			$main_id = preg_replace("/[^0-9]/", '', $task_id);
			$ids = explode('_',$task_id);
			$main_id = $ids[1];
			if($main_id)
			{
				//$orig_data = get_task_detail($main_id);
				//$post_data = kanban_recurrence_logic($orig_data);
                                
				$task_id = $this->kanban_model->save_task($post_data);
                                $this->db->query("update `user_task_swimlanes`
                                            SET `task_ex_pos` = '1'
                                            WHERE `user_id` = '$post_data[task_allocated_user_id]'
                                            AND `task_id` = $task_id
                                            ");
                                //echo $this->db->last_query();
				$steps = get_task_steps($main_id);
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
											/**
											 * insert data into task_steps table
											 */
						$this->db->insert('task_steps',$step_data);
						$i++;
					}
				}
                         $task_file = get_task_files($main_id);
                                if($task_file){
                                    foreach($task_file as $file){ 
                                        $file_data = array(
                                                'task_file_name' => $file['task_file_name'],
                                                'file_link' => $file['file_link'],
                                                'file_title' => $file['file_title'],
                                                'task_id' => $task_id,
                                                'project_id' => $file['project_id'],
                                                'file_added_by' => $this->session->userdata('user_id'),
                                                'file_date_added' => date('Y-m-d H:i:s')
                                            );

                                        $this->db->insert('task_and_project_files',$file_data);
                                    }
                                }
				$step_detail = get_task_step_detail($step_id);
				$step_title = $step_detail['step_title'];
				$step_id = get_task_step_id($task_id,$step_title);
				$step_detail = get_task_step_detail($step_id);
				$is_completed = $step_detail['is_completed'];
							/**
							 * task completed
							 */
				if($is_completed == '1'){
					$data = array('is_completed'=>'0','completion_date'=>'');
				} 
							/**
							 * task not complete
							 */
							else {
					$data = array('is_completed'=>'1','completion_date'=>date('Y-m-d H:i:s'));
				}
				//$this->db->where('task_step_id',$step_id);
				$this->db->where('(task_step_id = '.$step_id.' or (multi_allocation_step_id = '.$step_id.' and is_deleted = 0))');
				$this->db->update('task_steps',$data);
				echo $task_id;
			}
			else{
				die;
			}
		} else {
			$step_detail = get_task_step_detail($step_id);
			$is_completed = $step_detail['is_completed'];
			if($is_completed == '1'){
				$data = array('is_completed'=>'0','completion_date'=>'');
			} else {
				$data = array('is_completed'=>'1','completion_date'=>date('Y-m-d H:i:s'));
			}
			//$this->db->where('task_step_id',$step_id);
			$this->db->where('(task_step_id = '.$step_id.' or (multi_allocation_step_id = '.$step_id.' and is_deleted = 0))');
			$this->db->update('task_steps',$data);
			echo $is_completed;die;
		}
		
		
		
	}
     
    
      
        /**
         * On Ajax request,This function will update task position in different swimlanes on kanban board.It will check task existence and update task position in DB.
         * @returns void
         */
	
	/*
	 * Function : save_task_pos
	 * Author : Spaculus
	 * Desc : This function is use to save is task data expanded or collapsed in kanban view.
	*/
	function save_task_pos(){
		$theme = getThemeName();
		$post_data = $_POST['data'];
		/*Fetch company default format and check task existence*/
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$task_exists = chk_task_exists($post_data['task_id']);
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		if($task_exists == '0'){
			$id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($post_data['master_task_id']);
			if($steps){
				$i = 1;
				foreach($steps as $step){
					$step_data = array(
						'task_id' => $id,
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
                        $task_file = get_task_files($post_data['master_task_id']);
                                if($task_file){
                                    foreach($task_file as $file){ 
                                        $file_data = array(
                                                'task_file_name' => $file['task_file_name'],
                                                'file_link' => $file['file_link'],
                                                'file_title' => $file['file_title'],
                                                'task_id' => $id,
                                                'project_id' => $file['project_id'],
                                                'file_added_by' => $this->session->userdata('user_id'),
                                                'file_date_added' => date('Y-m-d H:i:s')
                                            );

                                        $this->db->insert('task_and_project_files',$file_data);
                                    }
                                }
			$is_virtual = "1";
		} else {
			$id = $post_data['task_id'];
			$is_virtual = "0";
		}
		
		if($is_virtual == "0"){
			if($post_data['task_ex_pos']=='0'){
				$pos = '1';
			}else{
				$pos = '0';
			}
		} else {
			$pos = '1';
		}
		/* This query will update task swimlane in DB */
		$expand_pos = array('task_ex_pos'=>$pos);
		$this->db->where(array('task_id'=>$id,'user_id'=>$this->session->userdata("Temp_kanban_user_id")));
		$this->db->update('user_task_swimlanes',$expand_pos);
		
		
		if($is_virtual == "1"){
			$data['kanban'] = get_task_detail($id);
			$this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
		} else {
			echo "done";die;
		}
	} 
     
      /**
        * This function will call on right click option on kanban board.It will update task status
          and send mail of user,manager and admin.And it will notify also i.e specific task is completed or not.
        * @returns void
        */
		

	/*
	 * Function : update_status
	 * Author : Spaculus
	 * Desc : This function is use to update status of task from checkbox.
	*/	
	function update_status(){
		$theme = getThemeName();
                /**
                 * This ternary operator check from_module checkbox is checked or not
                 */
		$from_module = isset($_POST['from_module'])?$_POST['from_module']:'';
		if($from_module){
			$post_data = json_decode($_POST['data'],true);
		} else {
			$post_data = $_POST['data'];
		}
		/*Check task existence */
		$task_exists = chk_task_exists($post_data['task_id']);
		if($task_exists == '0'){
			$id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($post_data['master_task_id']);
			if($steps){
				$i = 1;
				foreach($steps as $step){
					$step_data = array(
						'task_id' => $id,
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
                        $task_file = get_task_files($post_data['master_task_id']);
                                if($task_file){
                                    foreach($task_file as $file){ 
                                        $file_data = array(
                                                'task_file_name' => $file['task_file_name'],
                                                'file_link' => $file['file_link'],
                                                'file_title' => $file['file_title'],
                                                'task_id' => $id,
                                                'project_id' => $file['project_id'],
                                                'file_added_by' => $this->session->userdata('user_id'),
                                                'file_date_added' => date('Y-m-d H:i:s')
                                            );

                                        $this->db->insert('task_and_project_files',$file_data);
                                    }
                                }
		} else {
			$id = $post_data['task_id'];
		}
		
                $task_scheduled_date = get_task_schedule_date($id);
                
		$status = $_POST['status'];
		
		$task_pre_id = $post_data['task_status_id'];
		
		$old_task_status_name = get_task_status_name_by_id($task_pre_id);
		
		$new_task_status_name = get_task_status_name_by_id($status);
		
		$task_status_completed_id = $this->config->item('completed_id');
		/**
                 * If task is completed than it will update task status in DB
                 */
		if($status == $task_status_completed_id){
			if($task_scheduled_date == '0000-00-00'){
                            $update_data = array('task_status_id'=>$status, 'task_completion_date'=>date('Y-m-d H:i:s'),'task_scheduled_date'=>date('Y-m-d'));
                        }else{
                            $update_data = array('task_status_id'=>$status, 'task_completion_date'=>date('Y-m-d H:i:s'));
                        }
			$this->db->where('task_id',$id);
			$this->db->update('tasks',$update_data);
			
			$swimlane_data = array(
					'kanban_order' => 1
			);
                        /**
                         * Update user_task_swimlanes table
                         */
			$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
			$this->db->where('task_id',$id);
			$this->db->update('user_task_swimlanes',$swimlane_data);
			
                        $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                         SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                         WHERE `uts`.`user_id` = '".$this->session->userdata('Temp_kanban_user_id')
                                         ."' AND `uts`.`task_id` != '$id'
                                         AND `t`.`task_status_id` = '$status'
                                         ");
                     /*Add custom query for update data in db in place of below query */
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $this->session->userdata("Temp_kanban_user_id"));
//			$this->db->where('uts.task_id != ',$id);
//			$this->db->where('t.task_status_id', $status);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
			
			//email variables
			$owner_name = usernameById($post_data['task_owner_id']);
                        /**
                         * check task due date format
                         */
			if($post_data['task_due_date']!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($post_data['task_due_date']));
			} else {
				$task_due_date = 'N/A';
			}
			$task_description = $post_data['task_description'];
                        /**
                         * check task description exist or not
                         */
			if($task_description){
				$task_description = $task_description;
			} else {
				$task_description = 'N/A';
			}
			$project_name = get_project_name($post_data['task_project_id']);
			if($project_name){
				$project_name = $project_name;
			} else {
				$project_name = 'N/A';
			}
			
			$completion_user_name = $this->session->userdata('username');
			
			if($post_data['task_owner_id'] !=get_authenticateUserID()){
				
				/**
                                 * It will update notification for user and admin
                                 */
				$notification_text = '"'.$post_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
				$notification_data = array(
					'task_id' => $id,
					'project_id' => $post_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $post_data['task_owner_id'],
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);
				$this->db->insert('task_notification',$notification_data);
				
				/**
                                 *  Send email to task owner user for task is completed 
                                 */
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
				$email_temp=$email_template->row();	
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;
				
				$email_subject=$email_temp->subject;				
				$email_message=$email_temp->message;
				
				$user_info = get_user_info($post_data['task_owner_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $post_data['task_title'];
				
				$allocated_user_info = get_user_info($post_data['task_allocated_user_id']);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_to = $user_info->email;
				$subscription_link = site_url();
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
                        /**
                         * check task owner id for notification and mail
                         */
			if($post_data['task_owner_id'] != $post_data['task_allocated_user_id'] && $post_data['task_allocated_user_id'] !=get_authenticateUserID()){
				
				/**
                                 * notification
                                 */
				$notification_text = '"'.$post_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
				$notification_data = array(
					'task_id' => $id,
					'project_id' => $post_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $post_data['task_allocated_user_id'],
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
				
				$user_info = get_user_info($post_data['task_allocated_user_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $post_data['task_title'];
				
				$allocated_user_info = get_user_info($post_data['task_allocated_user_id']);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_to = $user_info->email;
				$subscription_link = site_url();
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
			
			if($post_data['task_owner_id']!=$post_data['task_allocated_user_id']){
				
				$swimlane_data = array(
					'kanban_order' => 1
				);
				$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
				$this->db->where('task_id',$id);
				$this->db->update('user_task_swimlanes',$swimlane_data);
				
                                $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                                  SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                                  WHERE `uts`.`user_id` = '".$this->session->userdata('Temp_kanban_user_id')
                                                  ."' AND `uts`.`task_id` != '$id'
                                                  AND `t`.`task_status_id` = '$status'
                                                  ");
                                
                                
//				$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//				$this->db->where('uts.user_id', $this->session->userdata("Temp_kanban_user_id"));
//				$this->db->where('uts.task_id != ',$id);
//				$this->db->where('t.task_status_id', $status);
//				$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
				
			}
			
			$post_data['task_status_id'] = $status;
		
		} else {
			
			
			$update_data = array('task_status_id'=>$status, 'task_completion_date'=>'0000-00-00 00:00:00');
			$this->db->where('task_id',$id);
			$this->db->update('tasks',$update_data);
			
			$swimlane_data = array(
					'kanban_order' => 1
			);
			$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
			$this->db->where('task_id',$id);
			$this->db->update('user_task_swimlanes',$swimlane_data);
			
                         $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                                  SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                                  WHERE `uts`.`user_id` = '".$this->session->userdata('Temp_kanban_user_id')
                                                  ."' AND `uts`.`task_id` != '$id'
                                                  AND `t`.`task_status_id` = '$status'
                                                  ");
                        
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $this->session->userdata("Temp_kanban_user_id"));
//			$this->db->where('uts.task_id != ',$id);
//			$this->db->where('t.task_status_id', $status);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
			
			
			if($task_pre_id == $task_status_completed_id){
				//email variables
				$owner_name = usernameById($post_data['task_owner_id']);
				if($post_data['task_due_date']!='0000-00-00'){
					$task_due_date = date($this->config->item('company_default_format'),strtotime($post_data['task_due_date']));
				} else {
					$task_due_date = 'N/A';
				}
				$task_description = $post_data['task_description'];
				if($task_description){
					$task_description = $task_description;
				} else {
					$task_description = 'N/A';
				}
				$project_name = get_project_name($post_data['task_project_id']);
				if($project_name){
					$project_name = $project_name;
				} else {
					$project_name = 'N/A';
				}
				
				$completion_user_name = $this->session->userdata('username');
				
				if($post_data['task_owner_id'] !=get_authenticateUserID()){
					
					//notification
					$notification_text = '"'.$post_data['task_title'].'" is uncompleted by '.$this->session->userdata('username').' this user.';
					
					$notification_data = array(
						'task_id' => $id,
						'project_id' => $post_data['task_project_id'],
						'notification_text' => $notification_text,
						'notification_user_id' => $post_data['task_owner_id'],
						'notification_from' =>get_authenticateUserID(),
						'is_read' => '0',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->db->insert('task_notification',$notification_data);
					
					/* send email to task owner user for task is uncompleted */
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
					$email_temp=$email_template->row();	
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
					
					$email_subject=$email_temp->subject;				
					$email_message=$email_temp->message;
					
					$user_info = get_user_info($post_data['task_owner_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $post_data['task_title'];
					
					$allocated_user_info = get_user_info($post_data['task_allocated_user_id']);
						$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
						
					
					$email_to = $user_info->email;
					$subscription_link = site_url();
					
					$email_subject=str_replace('{break}','<br/>',$email_subject);
					$email_subject=str_replace('{user_name}',$user_name,$email_subject);
					$email_subject=str_replace('{task_name}', $task_name, $email_subject);
					$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
					$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
					$email_subject=str_replace('{project_name}',$project_name,$email_subject);
					$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
					$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
					
					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{task_name}', $task_name, $email_message);
					$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
					$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
					$email_message=str_replace('{task_description}',$task_description,$email_message);
					$email_message=str_replace('{project_name}',$project_name,$email_message);
					$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
	
				if($post_data['task_owner_id'] != $post_data['task_allocated_user_id'] && $post_data['task_allocated_user_id'] !=get_authenticateUserID()){
					
					//notification
					$notification_text = '"'.$post_data['task_title'].'" is uncompleted by '.$this->session->userdata('username').' this user.';
					$notification_data = array(
						'task_id' => $id,
						'project_id' => $post_data['task_project_id'],
						'notification_text' => $notification_text,
						'notification_user_id' => $post_data['task_allocated_user_id'],
						'notification_from' =>get_authenticateUserID(),
						'is_read' => '0',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->db->insert('task_notification',$notification_data);
					
					/* send email to task owner user for task is uncompleted */
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
					$email_temp=$email_template->row();	
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
					
					$email_subject=$email_temp->subject;				
					$email_message=$email_temp->message;
					
					$user_info = get_user_info($post_data['task_allocated_user_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $post_data['task_title'];
					
					$allocated_user_info = get_user_info($post_data['task_allocated_user_id']);
						$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
						
					
					$email_to = $user_info->email;
					$subscription_link = site_url();
					
					$email_subject=str_replace('{break}','<br/>',$email_subject);
					$email_subject=str_replace('{user_name}',$user_name,$email_subject);
					$email_subject=str_replace('{task_name}', $task_name, $email_subject);
					$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
					$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
					$email_subject=str_replace('{project_name}',$project_name,$email_subject);
					$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
					$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
					
					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{task_name}', $task_name, $email_message);
					$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
					$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
					$email_message=str_replace('{task_description}',$task_description,$email_message);
					$email_message=str_replace('{project_name}',$project_name,$email_message);
					$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
			$post_data['task_status_id'] = $status;
		}
                /**
                 * insert data into task_history
                 */
		if($task_pre_id != $status){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		$json['id'] = $id;
		$json['title'] = $post_data['task_title'];
		$json['task_time_spent'] = $post_data['task_time_spent'];
		$json['swimlane_id'] = $post_data['swimlane_id'];
		echo json_encode($json);die;
	}
      /**
        * On Ajax request,this function will call on kanban board and set order of task in different status and swim lanes.
        * @returns void
        */
		
	
	/*
	 * Function : setOrder
	 * Author : Spaculus
	 * Desc : This function is use to sets order of task at drag and drop time.
	*/	
	function setOrder(){
		
		$theme = getThemeName();
		$order = $_POST['order'];
		$post_scope_id = $_POST['scope_id'];
		$post_status = $_POST['status'];
		/**
                 * check task_data is set or not
                 */
		if(isset($_POST['task_data']) && $_POST['task_data']!=''){
			$post_data = json_decode($_POST['task_data'],true);
		} else {
			$post_data = '';
		}
		
		$scope_id = $post_scope_id;
		
		$status_arr = explode('_', $post_status);
		$status_id = $status_arr[2];
		if(isset($status_arr[3])){
			$swimlalane_id = $status_arr[3];
		} else {
			$swimlalane_id = 0;
		}
		
		if($post_data){
			$chk_exist = chk_task_exists($scope_id);
			if($chk_exist == '0'){
				$chk = chk_virtual_recurrence_exists($post_data['master_task_id'],$post_data['task_scheduled_date']);
				if($chk && $chk['is_deleted'] == "0"){
					$inserted_id = $chk['task_id'];
				} else {
					$inserted_id = $this->kanban_model->save_task($post_data);
					$steps = get_task_steps($post_data['master_task_id']);
					if($steps){
						$i = 1;
						foreach($steps as $step){
							$step_data = array(
								'task_id' => $inserted_id,
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
                                        $task_file = get_task_files($post_data['master_task_id']);
                                        if($task_file){
                                            foreach($task_file as $file){ 
                                                $file_data = array(
                                                        'task_file_name' => $file['task_file_name'],
                                                        'file_link' => $file['file_link'],
                                                        'file_title' => $file['file_title'],
                                                        'task_id' => $inserted_id,
                                                        'project_id' => $file['project_id'],
                                                        'file_added_by' => $this->session->userdata('user_id'),
                                                        'file_date_added' => date('Y-m-d H:i:s')
                                                    );

                                                $this->db->insert('task_and_project_files',$file_data);
                                            }
                                        }
				}
			} else {
				$inserted_id = $scope_id;
			}
			
			if($order){
				$step1 = explode('&', $order);
				$i = 1;
				foreach($step1 as $step){
					if(strpos($step, 'child') !== false){
						$virtual_ids = explode('[]=', $step);
						$custom_id = 'child_'.$virtual_ids[1];
						if($scope_id == $custom_id){
							$task_id = $inserted_id;
						} else {
							$task_id = '';
						}
					} else { 
						$id = preg_replace("/[^0-9]/", '', $step);
						$task_id = $id;
					}
					if($task_id){
						
						$swimlane_data = array(
							'swimlane_id' => $swimlalane_id,
							'kanban_order' => $i
						);
						$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
						$this->db->where('task_id',$task_id);
						$this->db->update('user_task_swimlanes',$swimlane_data);
					}
					
					
					$data['task_swimlane_id'] = $swimlalane_id;
					$data['task_status_id'] = $status_id;
					
					$i++;
				}
				
			} 
			/**
                         * Save data in json and encode
                         */
			$json['id'] = $inserted_id;
			$json['title'] = $post_data['task_title'];
			$json['task_time_spent'] = $post_data['task_time_spent'];
			echo json_encode($json);die;
			
		} else {
			echo "no_data";die;
		}
	}
  		
        /**
         * This function will call on right click option on kanban board.It will update task status
          and send mail of user,manager and admin.And it will notify also i.e specific task is completed or not.
         * @returns void
         */
	
	/*
	 * Function : UpdateScope
	 * Author : Spaculus
	 * Desc : This function is use to upate effective value of task at drag and drop time.
	*/	
	function UpdateScope(){
		
		$theme = getThemeName();
		 /* Get data from post methods */
		$post_scope_id = $_POST['scope_id'];
		$post_status = $_POST['status'];
		$post_order = $_POST['order'];
		$post_data = json_decode($_POST['task_data'],true);
		
		$scope_id = $post_scope_id;
		
		$status_arr = explode('_', $post_status);
		$status_id = $status_arr[2];
		if(isset($status_arr[3])){
			$swimlalane_id = $status_arr[3];
		} else {
			$swimlalane_id = 0;
		}
		/* check task existence */
		$chk_exist = chk_task_exists($scope_id);
		if($chk_exist == '0'){
			$chk = chk_virtual_recurrence_exists($post_data['master_task_id'],$post_data['task_scheduled_date']);
			if($chk && $chk['is_deleted'] == "0"){
				$inserted_id = $chk['task_id'];
			} else {
				$inserted_id = $this->kanban_model->save_task($post_data);
				$steps = get_task_steps($post_data['master_task_id']);
				if($steps){
					$i = 1;
					foreach($steps as $step){
						$step_data = array(
							'task_id' => $inserted_id,
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
                                $task_file = get_task_files($post_data['master_task_id']);
                                        if($task_file){
                                            foreach($task_file as $file){ 
                                                $file_data = array(
                                                        'task_file_name' => $file['task_file_name'],
                                                        'file_link' => $file['file_link'],
                                                        'file_title' => $file['file_title'],
                                                        'task_id' => $inserted_id,
                                                        'project_id' => $file['project_id'],
                                                        'file_added_by' => $this->session->userdata('user_id'),
                                                        'file_date_added' => date('Y-m-d H:i:s')
                                                    );

                                                $this->db->insert('task_and_project_files',$file_data);
                                            }
                                        }
			}
			
		} else {
			$inserted_id = $scope_id;
		}
		
		$task_data = get_task_detail($inserted_id);
		
		$task_prev_id = $task_data['task_status_id'];
		
		$prev_swimlane_id = $task_data['swimlane_id'];
		
		$old_task_status_name = get_task_status_name_by_id($task_prev_id);
		
		$new_task_status_name = get_task_status_name_by_id($status_id);
		
		$completed_id = $this->config->item('completed_id');
		/* It check task status and update task table on task completion*/
		if($status_id == $completed_id){
                    
                        $task_scheduled_date = get_task_schedule_date($inserted_id);
                        if($task_scheduled_date == '0000-00-00'){
                            $data = array('task_status_id'=>$status_id,'task_completion_date'=>date('Y-m-d H:i:s'),'task_scheduled_date'=>date('Y-m-d'));
                        }else{
                            $data = array('task_status_id'=>$status_id,'task_completion_date'=>date('Y-m-d H:i:s'));
                        }
			$this->db->where('task_id',$inserted_id);
			$this->db->update('tasks',$data);
			
			
			/* Fetch company details and task info */
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
			$project_name = $task_data['project_title'];
			if($project_name){
				$project_name = $project_name;
			} else {
				$project_name = 'N/A';
			}
			$completion_user_name = $this->session->userdata('username');	
			/* Check task_owner_id with authenticated user id for notification & send mail*/
			if($task_data['task_owner_id'] !=get_authenticateUserID()){	
				//notification
				$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
				$notification_data = array(
					'task_id' => $inserted_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_owner_id'],
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);
                                /*It will update task notification */
				$this->db->insert('task_notification',$notification_data);
			
				/* Send email to task owner and user for task is completed  */
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
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
				
				$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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

			if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){	
				//notification
				$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
				$notification_data = array(
					'task_id' => $inserted_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_allocated_user_id'],
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
				
				$user_info = get_user_info($task_data['task_allocated_user_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $task_data['task_title'];
				
				$email_to = $user_info->email;
				$subscription_link = site_url();
				
				$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
				$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
			$data = array('task_status_id'=>$status_id,'task_completion_date'=>'0000-00-00 00:00:00');
			$this->db->where('task_id',$inserted_id);
			$this->db->update('tasks',$data);
			
			if($task_prev_id == $completed_id){
			
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
				$project_name = $task_data['project_title'];
				if($project_name){
					$project_name = $project_name;
				} else {
					$project_name = 'N/A';
				}
				$completion_user_name = $this->session->userdata('username');	
				
				if($task_data['task_owner_id'] !=get_authenticateUserID()){	
					//notification
					$notification_text = '"'.$task_data['task_title'].'" is uncompleted by '.$this->session->userdata('username').' this user.';
					$notification_data = array(
						'task_id' => $inserted_id,
						'project_id' => $task_data['task_project_id'],
						'notification_text' => $notification_text,
						'notification_user_id' => $task_data['task_owner_id'],
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
					
					$user_info = get_user_info($task_data['task_owner_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $task_data['task_title'];
					
					$email_to = $user_info->email;
					$subscription_link = site_url();
					
					$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
						
					
					$email_subject=str_replace('{break}','<br/>',$email_subject);
					$email_subject=str_replace('{user_name}',$user_name,$email_subject);
					$email_subject=str_replace('{task_name}', $task_name, $email_subject);
					$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
					$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
					$email_subject=str_replace('{project_name}',$project_name,$email_subject);
					$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
					$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
					
					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{task_name}', $task_name, $email_message);
					$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
					$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
					$email_message=str_replace('{task_description}',$task_description,$email_message);
					$email_message=str_replace('{project_name}',$project_name,$email_message);
					$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
	
				if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){	
					//notification
					$notification_text = '"'.$task_data['task_title'].'" is uncompleted by '.$this->session->userdata('username').' this user.';
					$notification_data = array(
						'task_id' => $inserted_id,
						'project_id' => $task_data['task_project_id'],
						'notification_text' => $notification_text,
						'notification_user_id' => $task_data['task_allocated_user_id'],
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
					
					$user_info = get_user_info($task_data['task_allocated_user_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $task_data['task_title'];
					
					$email_to = $user_info->email;
					$subscription_link = site_url();
					
					$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
					$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
						
					
					$email_subject=str_replace('{break}','<br/>',$email_subject);
					$email_subject=str_replace('{user_name}',$user_name,$email_subject);
					$email_subject=str_replace('{task_name}', $task_name, $email_subject);
					$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
					$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
					$email_subject=str_replace('{project_name}',$project_name,$email_subject);
					$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
					$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
					
					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{task_name}', $task_name, $email_message);
					$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
					$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
					$email_message=str_replace('{task_description}',$task_description,$email_message);
					$email_message=str_replace('{project_name}',$project_name,$email_message);
					$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
		}
		
		$swimlane_data = array(
			'swimlane_id' => $swimlalane_id
		);
		$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
		$this->db->where('task_id',$inserted_id);
		$this->db->update('user_task_swimlanes',$swimlane_data);
		
		
		if($task_prev_id != $status_id){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $inserted_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		
		if($prev_swimlane_id != $swimlalane_id){
			$history_data = array(
				'histrory_title' => 'Task swimlane is updated by '.$this->session->userdata("username").'.',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $inserted_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		$json['id'] = $inserted_id;
		$json['master_task_id'] = $task_data['master_task_id'];
		$json['task_status_id'] = $task_data['task_status_id'];
		$json['swimlane_id'] = $task_data['swimlane_id'];
		$json['prerequisite_task_id'] = $task_data['prerequisite_task_id'];
		$json['title'] = $task_data['task_title'];
		$json['task_time_spent'] = $task_data['task_time_spent'];
		echo json_encode($json);die;
	}
	
	
	/**
	 * This function will use for update task display view on Ajax request.It will check all task related info i.e task color,
            due date.priority,comments and so on.Then it render task_ajax view on kanban board.
         * @returns void
	/*
	 * Function : set_update_task
	 * Author : Spaculus
	 * Desc : This function is use to upate task display view at ajax request
	*/	
	function set_update_task(){
		
		$theme = getThemeName();
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$task_id = $_POST['task_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');
                               $data['all_report_user']=get_list_user_report_to_adminstartor();
		if(isset($_POST['color_menu']) && !empty($_POST['color_menu'])){
                    $color_menu = $_POST['color_menu'];
                }
		else{
                    $color_menu='true';
                }
		if(strpos($task_id, 'child') !== false){
			$id = preg_replace("/[^0-9]/", '', $task_id);
			$task_id = $id;
			$task_detail = get_task_detail($task_id);
			$task_data = kanban_recurrence_logic($task_detail);
		} else {
			$task_data = get_task_detail($task_id);
		}
		
		$is_div_valid = 0;
		$data['last_rember_values'] = $last_remembers = get_user_last_rember_values();
		
		if($last_remembers){
			if($last_remembers->due_task){
				$due_task = $last_remembers->due_task;
			} else {
				$due_task = '';
			}
			
			
			$team_user_id = $last_remembers->kanban_team_user_id;
			$user_color_id = $last_remembers->user_color_id;
			
			$kanban_project_id = $last_remembers->kanban_project_id;
			if($due_task!='' && $due_task !='all'){
				if($due_task == "overdue" ){
					if($task_data['task_status_id']!=$this->config->item('completed_id')){
						if($kanban_project_id!='' && $user_color_id=='0'){
							$project_id = explode(',', $kanban_project_id);
							if(in_array('all',$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']<date("Y-m-d") /*&& $user_color_id==$task_data['color_id']*/){
								$is_div_valid = 1;
							} else {
								if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']<$end_date /*&& $user_color_id==$task_data['color_id']*/){
									$is_div_valid = 1;
								}
							}
						}else if($kanban_project_id=='' && $user_color_id!='0'){
							$project_id = explode(',', $kanban_project_id);
							if($task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']<date("Y-m-d") && $user_color_id==$task_data['color_id']){
								$is_div_valid = 1;
							} else {
								if( $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']<$end_date && $user_color_id==$task_data['color_id']){
									$is_div_valid = 1;
								}
							}
						} else if($kanban_project_id!='' && $user_color_id!='0'){
						//echo "in 4";
							$project_id = explode(',', $kanban_project_id);
							if(in_array('all',$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $user_color_id==$task_data['color_id']){
								$is_div_valid = 1;
							} else {
								if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $user_color_id==$task_data['color_id']){
									$is_div_valid = 1;
								}
							}
						}else {
								if($task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']<$end_date && $user_color_id=='0'){
									$is_div_valid = 1;
								}
							}
						} 
				} else {
					if($due_task == "today"){
						$start_date = date("Y-m-d");
						$end_date = date("Y-m-d");
					} else if($due_task == "this_week"){
						$start_date = date('Y-m-d',strtotime('this week', time()));
						$end_date = date("Y-m-d", strtotime('next Saturday'));
					} else if($due_task == "next_week"){
						$start_date = date('Y-m-d',strtotime('next week', time()));
						$end_date = date("Y-m-d", strtotime('+5 days',strtotime($start_date))); 
					}else if($due_task == "this_month"){
						$start_date = date('Y-m-01',strtotime('this month'));
						$end_date = date('Y-m-t',strtotime('this month')); 
					}else if($due_task == "next_month"){
						$start_date = date('Y-m-01',strtotime('next month'));
						$end_date = date('Y-m-t',strtotime('next month')); 
					}else if($due_task == "next_to_next_month"){
						$start_date = date('Y-m-01',strtotime('+2 month'));
						$end_date = date('Y-m-t',strtotime('+2 month')); 
					}else if($due_task == "next_ninty"){
						$start_date = date('Y-m-d');
						$end_date = date('Y-m-d',strtotime('+90 days'));
					}else if($due_task == "this_year"){
						$start_date = date('Y-01-01',strtotime('this year'));
						$end_date = date('Y-12-t',strtotime('this year'));
					}else if($due_task == "next_year"){
						$start_date = date('Y-01-01',strtotime('next year'));
						$end_date = date('Y-12-t',strtotime('next year'));
					} else {
						
					}
					if($kanban_project_id!='' && $user_color_id=='0'){
						$project_id = explode(',', $kanban_project_id);
						if(in_array('all',$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']>=$start_date && $task_data['task_due_date']<=$end_date /*&& $user_color_id==$task_data['color_id']*/){
							$is_div_valid = 1;
						} else {
							if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']>=$start_date && $task_data['task_due_date']<=$end_date /*&& $user_color_id==$task_data['color_id']*/){
								$is_div_valid = 1;
							}
						}
					} else if($kanban_project_id=='' && $user_color_id!='0'){
						$project_id = explode(',', $kanban_project_id);
						if($task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']>=$start_date && $task_data['task_due_date']<=$end_date && $user_color_id==$task_data['color_id']){
							$is_div_valid = 1;
						} else {
							if($task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']>=$start_date && $task_data['task_due_date']<=$end_date && $user_color_id==$task_data['color_id']){
								$is_div_valid = 1;
							}
						}
					} else if($kanban_project_id!='' && $user_color_id!='0'){
						$project_id = explode(',', $kanban_project_id);
						if(in_array('all',$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']>=$start_date && $task_data['task_due_date']<=$end_date && $user_color_id==$task_data['color_id']){
							$is_div_valid = 1;
						} else {
							if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']>=$start_date && $task_data['task_due_date']<=$end_date && $user_color_id==$task_data['color_id']){
								$is_div_valid = 1;
							}
						}
					}else {
						if($task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $task_data['task_due_date']>=$start_date && $task_data['task_due_date']<=$end_date && $user_color_id=='0'){
							$is_div_valid = 1;
						}
					}
				}
			} else {
				if($kanban_project_id!='' && $user_color_id=='0'){
					$project_id = explode(',', $kanban_project_id);
					if(in_array('all',$project_id) && $task_data['task_allocated_user_id'] == $team_user_id /*&& $user_color_id==$task_data['color_id']*/){
						$is_div_valid = 1;
					} else {
						if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_allocated_user_id'] == $team_user_id /*&& $user_color_id==$task_data['color_id']*/){
							$is_div_valid = 1;
						}
					}
				} else if($kanban_project_id=='0' && $user_color_id!='0'){
					$project_id = explode(',', $kanban_project_id);
					if($task_data['task_allocated_user_id'] == $team_user_id && $user_color_id==$task_data['color_id']){
						$is_div_valid = 1;
					} else {
						if($task_data['task_allocated_user_id'] == $team_user_id && $user_color_id==$task_data['color_id']){
							$is_div_valid = 1;
						}
					}
				} else if($kanban_project_id!='' && $user_color_id!='0'){
						$project_id = explode(',', $kanban_project_id);
						if(in_array('all',$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $user_color_id==$task_data['color_id']){
							$is_div_valid = 1;
						} else {
							if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_allocated_user_id'] == $team_user_id && $user_color_id==$task_data['color_id']){
								$is_div_valid = 1;
							}
						}
					}else {
					if($task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $user_color_id=='0'){
						$is_div_valid = 1;
					}
				}
			}
			
		}
		
		if($is_div_valid){
                        $data['color_menu']=$color_menu;
			$data['kanban'] = $task_data;
		} else {
                        $data['color_menu']=$color_menu;
			$data['kanban'] = $task_data;
		}
		
		if($data['kanban']!=''){
                    $this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
		}
		
	}

        /**
         * This function will call from filter of kanban.It will access data from DB
           and custom method for render kanban view on ajax request.
         * @returns void
         */
	/*
	 * Function : searchDueTask
	 * Author : Spaculus
	 * Desc : This function is used when filter calls from footer
	*/
	function searchDueTask(){
		
		if(!check_user_authentication()){
			redirect('home');
		}
		$users=array();
                $ids=array();
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		$data['user'] = get_user_info(get_authenticateUserID());
		$data['error'] = '';
		
		
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
		
		/* Check task duedate set or not */
		if(isset($_POST['due_task'])){ $types = $_POST['due_task']; } else { $types = ''; }
		if(isset($_POST['kanban_team_user_id'])){ $user_team_id = $_POST['kanban_team_user_id']; } else { $user_team_id = get_authenticateUserID(); }
		if($_POST['user_color_id']){ $user_color_id = $_POST['user_color_id']; } else { $user_color_id = "0"; }
		if(isset($_POST['kanban_project_id'])){ $project_id = $_POST['kanban_project_id']; } else { $project_id = ''; }
            /*              
             * This changes for project team option in kanban filter
             */
                if($user_team_id == '#' && $project_id !='all'){
                        $data['swimlanes']= get_user_swimlanes_team($user_team_id,$project_id);
                        $data['kanban_task']= get_kanban_tasks_team($data['task_status'],$data['swimlanes'],$types,$user_team_id,$project_id,$user_color_id);
                        $data['color_menu']= 'false';
                        $data['allocation_flag']='true';
                        
                }
                  else{
                      $data['swimlanes'] = get_user_swimlanes($user_team_id);
                      $data['kanban_task'] = get_kanban_tasks($data['task_status'],$data['swimlanes'],$types,$user_team_id,$project_id,$user_color_id);
                      $data['color_menu']= 'true';
                      $data['allocation_flag']='false';
                      $data['default_swimlane'] = get_default_swimlane(get_authenticateUserID());
                  }
                          
                                
                
		if($_POST){
			$last_remember_id = $this->kanban_model->save_last_remember();
		}
		
		$this->session->set_userdata("Temp_kanban_user_id",$user_team_id);
		
		
		
		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array( 
            'general' => 0,
            'dependencies' => '',
            'steps' => '',
            'files' => '',
            'comments' => '',
            'history' => ''
		);
		/* Get user details and render kanban view page */
		$data['users'] = get_user_list();
			
		
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		
		$data['customers']=  getCustomerList();
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
                               $data['all_report_user']=get_list_user_report_to_adminstartor();
                if($user_team_id == '#' ){
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                    $data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
                    $data['divisions'] = getUserDivision(get_authenticateUserID());
                    $data['departments'] = getUserDepartment(get_authenticateUserID());
                }  else {
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_kanban_user_id'));
                    $data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_kanban_user_id'));
                    $data['divisions'] = getUserDivision($this->session->userdata('Temp_kanban_user_id'));
                    $data['departments'] = getUserDepartment($this->session->userdata('Temp_kanban_user_id'));
                }
		$this->load->view($theme.'/layout/kanban/ajax_kanban_view',$data);
	}
	
	/**
        * This function will call on ajax request from kanban board.It will delete task single or multiple from DB.
        * @returns void
        */
	
	/*
	 * Function : delete_task
	 * Author : Spaculus
	 * Desc : This function is used to delete task.
	*/
	function delete_task(){
		$theme = getThemeName();
		$task_id = $_POST['task_id'];
		$from = isset($_POST['from'])?$_POST['from']:'';
		$current_date = date('Y-m-d');
                if($from == 'future'){
                        $update_data = array('end_by_date'=>$current_date,'no_end_date'=>'3');
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$update_data); 
                        echo "done" ; die();
                }
		$task_exists = chk_task_exists($task_id);
		
		
		if($task_exists == '0'){
			$ids = explode('_',$task_id);
			$main_id = $ids[1];
                        $orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
			$task_id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($main_id);
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
                        
		} 
                $data['task_id'] = $task_id;
                $data['task_title'] = get_task_title($task_id);
		/**
                 * Update tasks table for delete task
                 */
                if($task_id != ''){
		$update_data = array('is_deleted'=>'1');
		//$this->db->where('task_id',$task_id);
		$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
		$this->db->update('tasks',$update_data);
                }
		if($from == 'series' && $task_exists !='0' && $task_id !=''){
			$update_data = array('is_deleted'=>'1');
			$this->db->where('master_task_id',$task_id);
			$this->db->update('tasks',$update_data);
		}
		$data['response'] = 'removed';
		echo json_encode($data);die;
	}
	/**
         * It will call on ajax request from right-click functionality.It will create comment div on kanban board.
           Then this method insert comments in DB.After that it will notify and send mail user & task owner.
         * @returns void
         */
	
	
	/*
	 * Function : add_comment
	 * Author : Spaculus
	 * Desc : This function is used when task comment added by right click functionality.
	*/
	function add_comment(){
		$task_id = $_POST['task_id'];
		$right_task_comment = $_POST['right_task_comment'];
		$theme = getThemeName();
                if(isset($_POST['color_menu'])){
                    $color_menu = $_POST['color_menu'];
                }
                else{
                    $color_menu = 'true';
                }
                
               
		/* It check task existance */
		$task_exists = chk_task_exists($task_id);
		if($task_exists == '0'){
			$main_id = preg_replace("/[^0-9]/", '', $task_id);
			$orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
			$task_id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($main_id);
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
                        $task_file = get_task_files($main_id);
                                        if($task_file){
                                            foreach($task_file as $file){ 
                                                $file_data = array(
                                                        'task_file_name' => $file['task_file_name'],
                                                        'file_link' => $file['file_link'],
                                                        'file_title' => $file['file_title'],
                                                        'task_id' => $task_id,
                                                        'project_id' => $file['project_id'],
                                                        'file_added_by' => $this->session->userdata('user_id'),
                                                        'file_date_added' => date('Y-m-d H:i:s')
                                                    );

                                                $this->db->insert('task_and_project_files',$file_data);
                                            }
                                        }
		} 
                /* Get comment details for insert in DB */
		$project_id = get_project_id_from_task_id($task_id);
		$data = array(
			'task_comment' => $right_task_comment,
			'task_id' => $task_id,
			'comment_addeby' => $this->session->userdata('user_id'),
			'project_id' => $project_id,
			'comment_added_date' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('task_and_project_comments',$data);
		$id = $this->db->insert_id();
		
		
		$task_detail = get_task_detail($task_id);
		
		
		
		
		//email
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
				'project_id' => $task_detail['task_project_id'],
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
			$comment = $right_task_comment;
			$added_by = $this->session->userdata('username');
			
			$email_to = $user_info->email;
			$subscription_link = site_url();
			
			$allocated_user_info = get_user_info($task_detail['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
			
			
			$email_subject=str_replace('{break}','<br/>',$email_subject);
			$email_subject=str_replace('{user_name}',$user_name,$email_subject);
			$email_subject=str_replace('{user-name}',$user_name,$email_subject);
			$email_subject=str_replace('{task_name}',$task_name,$email_subject);
			$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
			$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
			$email_subject=str_replace('{project_name}',$project_name,$email_subject);
			$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
			
			
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
		/*Check owner id with task allocate user and admin for send mail to user*/
		if($task_detail['task_owner_id']!=$task_detail['task_allocated_user_id'] && $task_detail['task_allocated_user_id'] != get_authenticateUserID()){
			
			$notification_data = array(
				'task_id' => $task_id,
				'project_id' => $task_detail['task_project_id'],
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
			$comment = $right_task_comment;
			$added_by = $this->session->userdata("username");
			
			$email_to = $user_info->email;
			$subscription_link = site_url();
			
			$allocated_user_info = get_user_info($task_detail['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
			
			$email_subject=str_replace('{break}','<br/>',$email_subject);
			$email_subject=str_replace('{user_name}',$user_name,$email_subject);
			$email_subject=str_replace('{user-name}',$user_name,$email_subject);
			$email_subject=str_replace('{task_name}',$task_name,$email_subject);
			$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
			$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
			$email_subject=str_replace('{project_name}',$project_name,$email_subject);
			$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
			
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
		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['kanban'] = $task_detail;
                $data['color_menu'] = $color_menu;
               
		echo $this->load->view($theme.'/layout/kanban/ajax_task_div',$data,TRUE);
		
	}
	

	/**
        * This function will call on right-click functionality of task.It will move task right side from current poistion.
          It will also update task status in DB,then it check user_id with task_owner for send task status mail.
        * @returns void
        */
	
	/*
	 * Function : moveRight
	 * Author : Spaculus
	 * Desc : This function is used when task move to right column by right click functionality.
	*/
	
	function moveRight(){
		$task_id = $_POST['task_id'];
		$task_status_id = $_POST['task_status_id'];
		
		$theme = getThemeName();
		/* check task existance */
		$task_exists = chk_task_exists($task_id);
		if($task_exists == '0'){
			$main_id = preg_replace("/[^0-9]/", '', $task_id);
			$orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
			$task_id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($main_id);
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
                        $task_file = get_task_files($main_id);
                           if($task_file){
                                foreach($task_file as $file){ 
                                    $file_data = array(
                                                        'task_file_name' => $file['task_file_name'],
                                                        'file_link' => $file['file_link'],
                                                        'file_title' => $file['file_title'],
                                                        'task_id' => $task_id,
                                                        'project_id' => $file['project_id'],
                                                        'file_added_by' => $this->session->userdata('user_id'),
                                                        'file_date_added' => date('Y-m-d H:i:s')
                                                    );

                                    $this->db->insert('task_and_project_files',$file_data);
                                }
                           }
		} 

		$completed_id = $this->config->item('completed_id');
		$new_task_id = get_next_status_id($task_status_id);
		
		$old_task_status_name = get_task_status_name_by_id($task_status_id);
		
		$new_task_status_name = get_task_status_name_by_id($new_task_id);
		$task_scheduled_date = get_task_schedule_date($id);
		// email
		$task_data = get_task_detail($task_id);
		
		if($new_task_id == $completed_id){
                        if($task_scheduled_date == '0000-00-00'){
                            $data = array('task_status_id'=>$new_task_id,'task_completion_date'=>date('Y-m-d H:i:s'),'task_scheduled_date'=>date('Y-m-d'));
                        }else{
                            $data = array('task_status_id'=>$new_task_id,'task_completion_date'=>date('Y-m-d H:i:s'));
                        }
			
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data);
			
			
			
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
			$project_name = $task_data['project_title'];
			if($project_name){
				$project_name = $project_name;
			} else {
				$project_name = 'N/A';
			}
			
			$completion_user_name = $this->session->userdata('username');
			
			if($task_data['task_owner_id'] !=get_authenticateUserID()){
				
				//notification
				$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
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
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
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
				
				$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
				$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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

			if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
				
				//notification
				$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
				$notification_data = array(
					'task_id' => $task_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_allocated_user_id'],
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
				
				$user_info = get_user_info($task_data['task_allocated_user_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $task_data['task_title'];
				
				$email_to = $user_info->email;
				$subscription_link = site_url();
				
				$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
				$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
			
			/////////////
		} else {
			$data = array('task_status_id'=>$new_task_id);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data);
			
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
			$project_name = $task_data['project_title'];
			if($project_name){
				$project_name = $project_name;
			} else {
				$project_name = 'N/A';
			}
			
			$completion_user_name = $this->session->userdata('username');
			
			if($task_data['task_owner_id'] !=get_authenticateUserID()){
				
				//notification
				$notification_text = '"'.$task_data['task_title'].'" is uncompleted by '.$this->session->userdata('username').' this user.';
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
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
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
				
				$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
				$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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

			if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
				
				//notification
				$notification_text = '"'.$task_data['task_title'].'" is uncompleted by '.$this->session->userdata('username').' this user.';
				$notification_data = array(
					'task_id' => $task_id,
					'project_id' => $task_data['task_project_id'],
					'notification_text' => $notification_text,
					'notification_user_id' => $task_data['task_allocated_user_id'],
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);
				$this->db->insert('task_notification',$notification_data);
				
				/**
                                 * send email to task owner user for task is completed 
                                 */
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
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
				
				$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
				$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
					
				
				$email_subject=str_replace('{break}','<br/>',$email_subject);
				$email_subject=str_replace('{user_name}',$user_name,$email_subject);
				$email_subject=str_replace('{task_name}', $task_name, $email_subject);
				$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
				$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
				$email_subject=str_replace('{project_name}',$project_name,$email_subject);
				$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
				$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
				
				$email_message=str_replace('{break}','<br/>',$email_message);
				$email_message=str_replace('{user_name}',$user_name,$email_message);
				$email_message=str_replace('{task_name}', $task_name, $email_message);
				$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
				$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
				$email_message=str_replace('{task_description}',$task_description,$email_message);
				$email_message=str_replace('{project_name}',$project_name,$email_message);
				$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
		/* Check status_id with new_task_id for update task_history*/
		if($task_status_id != $new_task_id){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $task_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		$json['master_task_id'] = $task_data['master_task_id'];
		$json['task_status_id'] = $_POST['task_status_id'];
		$json['swimlane_id'] = $task_data['swimlane_id'];
		$json['prerequisite_task_id'] = $task_data['prerequisite_task_id'];
		$json['status_id'] = $new_task_id;
		$json['task_id'] = $task_id;
		echo json_encode($json);die;
		
	}
	/**
        * This function will save task for timer.It will call kanban_model->save_task($post_data) for save task in DB.
        * @returns void
        */
	
	/*
	 * Function : save_task
	 * Author : Spaculus
	 * Desc : This function is used when timer starts at that time to save task
	*/
	
	
	function save_task(){
		$theme = getThemeName();
		$post_data = json_decode($_POST['post_data'],true);
		$id = $this->kanban_model->save_task($post_data);
		$steps = get_task_steps($post_data['master_task_id']);
		if($steps){
			$i = 1;
			foreach($steps as $step){
				$step_data = array(
					'task_id' => $id,
					'step_title' => $step['step_title'],
					'step_added_by' => $step['step_added_by'],
					'is_completed' => $step['is_completed'],
					'step_sequence' => $i,
					'step_added_date' => date('Y-m-d H:i:s')
				);
                                /**
                                 * save steps
                                 */
				$this->db->insert('task_steps',$step_data);
				$i++;
			}
		}
		echo $id;die;
	}
	/**
         * This function will call when user click on stop button of timer.Then it will get proper reason for timer stop and save in DB.
         * @returns void
         */
	
	
	/*
	 * Function : add_manual_reason
	 * Author : Spaculus
	 * Desc : This function is used when updates task time manually from task popup
	*/
	function add_manual_reason(){
		$task_id = $_POST['task_id'];
		$text = $_POST['manual_reason_txt'];
		$hour = $_POST['manual_spent_hour'];
		$min = $_POST['manual_spent_min'];
		
		$time = (($hour * 60) + $min);
		
		$data = array(
			'task_id' => $task_id,
			'user_id' => get_authenticateUserID(),
			'spent_time' => $time,
			'interruption' => $text,
			'is_manual' => '1',
			'date_added' => date("Y-m-d H:i:s")
		);
		$this->db->insert('task_timer_logs',$data);
		
	}
	/**
        *  This function will call when user click on comment icon on kanban board.After that,it will load comments on kanban view.
        * @returns void
        */
	
	/*
	 * Function : commets_html
	 * Author : Spaculus
	 * Desc : To load comments on task view comment icon click.
	*/
	function commets_html(){
		
		$task_id = $_REQUEST['task_id'];
		$data['task']['comments'] = get_task_comments($task_id);
                /**
                 * store array in json
                 */
		echo json_encode($data);die;
	}
/**
 * This function will call when user click on dependency icon on kanban board for displaying dependencies on view.
 * @returns void
 */
	
	/*
	 * Function : dependency_html
	 * Author : Spaculus
	 * Desc : To display dependencies of task view dependency icon click.
	*/
	function dependency_html(){
		$task_id = $_REQUEST['task_id'];
		$data['task']['dependencies'] = get_task_dependencies($task_id);
		echo json_encode($data);die;
	}
	/**
         * This function will call when user click on recurring icon on kanban board for showing recurrence task details on view.
         * @returns void
         */
	
	/*
	 * Function : recurring_html
	 * Author : Spaculus
	 * Desc : To display recurrence type of task view recurrence icon click.
	*/
	function recurring_html(){
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		
		$task_id = $_POST['task_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['recurrence_detail'] = get_task_detail($task_id);
		 $this->load->view($theme."/layout/kanban/recurrencing_html",$data);
	}
	/**
	 * This function will set height of swimlane.
         * @returns void
	
	
	/*
	 * Function : SetSwimlane_height
	 * Author : Spaculus
	 * Desc : This function is used to save height of swimlane 
	*/
	function SetSwimlane_height()
	{
		$swimlane_id = $_POST['swimlane_id'];
		$swimlane_height = $_POST['swimlane_height'];
		$user_id = get_authenticateUserID();
		
		$data['swmilane_update'] = $this->kanban_model->set_swimlane_height_by_user($swimlane_id,$swimlane_height);
		echo $data['swmilane_update'];die;
		
	}
	
      /**
       * This method will call when task is going for complete via check box option on task. It will get actual time of task completion.
         After that it will update DB with new data and check user_id with task_owner,manger of that task.
         Then it will send notification & send mail user and task_owner of company.
       * @returns void
       */
	/*
	 * Function : add_actual_time
	 * Author : Spaculus
	 * Desc : This function is used to add actaul time when task is going for complete
	*/
	function add_actual_time(){
		
		$task_id = $_POST['task_id'];
		$task_actual_time_hour = $_POST['task_actual_time_hour'];
		$task_actual_time_min = $_POST['task_actual_time_min'];
		$theme = getThemeName();
		
		$task_exists = chk_task_exists($task_id);
		if($task_exists == '0'){
			$main_id = preg_replace("/[^0-9]/", '', $task_id);
			$orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
			$task_id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($main_id);
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
                        $task_file = get_task_files($main_id);
                         if($task_file){
                             foreach($task_file as $file){ 
                                $file_data = array(
                                                        'task_file_name' => $file['task_file_name'],
                                                        'file_link' => $file['file_link'],
                                                        'file_title' => $file['file_title'],
                                                        'task_id' => $task_id,
                                                        'project_id' => $file['project_id'],
                                                        'file_added_by' => $this->session->userdata('user_id'),
                                                        'file_date_added' => date('Y-m-d H:i:s')
                                                    );

                                $this->db->insert('task_and_project_files',$file_data);
                             }
                         }
		}  else {
			$orig_data = get_task_detail($task_id);
		}
		
		$old_task_status_name = get_task_status_name_by_id($orig_data['task_status_id']);
		
		$new_task_status_name = "Completed";
		$task_scheduled_date = get_task_schedule_date($task_id);
	       
		$task_time_spent = ($task_actual_time_hour*60)+$task_actual_time_min;
		$completed_status_id = $this->config->item('completed_id');
                if($task_scheduled_date == '0000-00-00'){
                    $task_update_data = array(
			"task_time_spent"=>$task_time_spent,
			'task_status_id' => $completed_status_id,
			'task_completion_date'=>date('Y-m-d H:i:s'),
                        'task_scheduled_date'=>date('Y-m-d'),
                        'billed_time' => $task_time_spent
                    );
                }else{
                    $task_update_data = array(
			"task_time_spent"=>$task_time_spent,
			'task_status_id' => $completed_status_id,
			'task_completion_date'=>date('Y-m-d H:i:s'),
                        'billed_time'=>$task_time_spent
                    );
                }
		
		$this->db->where("task_id",$task_id);
		$this->db->update("tasks",$task_update_data);
		
                if($this->session->userdata('pricing_module_status')=='1'){ 
                    
                    $actual_time = get_task_actual_time($task_id);
                    $estimated_time = get_task_estimated_time($task_id);
                    $charge_out_rate = get_charge_out_rate($task_id);
                        $data2 = array(
                                    "charge_out_rate"=>$charge_out_rate,
                                    "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2),
                                    "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2),
                                );
                    
                    $this->db->where('task_id',$task_id);
                    $this->db->update('tasks',$data2);
                }
                
                
		if($orig_data['task_status_id'] != $completed_status_id){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $task_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		$swimlane_data = array(
				'kanban_order' => 1
		);
		$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
		$this->db->where('task_id',$task_id);
		$this->db->update('user_task_swimlanes',$swimlane_data);
		
                $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                            SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                            WHERE `uts`.`user_id` = '".$this->session->userdata('Temp_kanban_user_id')
                                            ."' AND `uts`.`task_id` != '$task_id'
                                            AND `t`.`task_status_id` = '$completed_status_id'
                                            ");
		
		$task_data = get_task_detail($task_id);
		//email variables
		$owner_name = usernameById($task_data['task_owner_id']);
		if($task_data['task_due_date']!='0000-00-00'){
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$task_due_date = date($this->config->item('company_default_format'),strtotime($task_data['task_due_date']));
			date_default_timezone_set("UTC");
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
		$completion_user_name = $this->session->userdata('username');
		
		if($task_data['task_owner_id'] !=get_authenticateUserID()){
			
			
			//notification
			$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
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
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
			$email_temp=$email_template->row();	
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;
			
			$email_subject=$email_temp->subject;				
			$email_message=$email_temp->message;
			
			$user_info = get_user_info($task_data['task_owner_id']);
			$user_name = $user_info->first_name.' '.$user_info->last_name;
			$task_name = $task_data['task_title'];
			
			$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
				
			
			$email_to = $user_info->email;
			$subscription_link = site_url();
			
			$email_subject=str_replace('{break}','<br/>',$email_subject);
			$email_subject=str_replace('{user_name}',$user_name,$email_subject);
			$email_subject=str_replace('{task_name}', $task_name, $email_subject);
			$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
			$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
			$email_subject=str_replace('{project_name}',$project_name,$email_subject);
			$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
			$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{task_name}', $task_name, $email_message);
			$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
			$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
			$email_message=str_replace('{task_description}',$task_description,$email_message);
			$email_message=str_replace('{project_name}',$project_name,$email_message);
			$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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

		if($task_data['task_owner_id'] != $task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
			
			
			//notification
			$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
			$notification_data = array(
				'task_id' => $task_id,
				'project_id' => $task_data['task_project_id'],
				'notification_text' => $notification_text,
				'notification_user_id' => $task_data['task_allocated_user_id'],
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
			
			$user_info = get_user_info($task_data['task_allocated_user_id']);
			$user_name = $user_info->first_name.' '.$user_info->last_name;
			$task_name = $task_data['task_title'];
			
			$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
				
			
			$email_to = $user_info->email;
			$subscription_link = site_url();
			
			$email_subject=str_replace('{break}','<br/>',$email_subject);
			$email_subject=str_replace('{user_name}',$user_name,$email_subject);
			$email_subject=str_replace('{task_name}', $task_name, $email_subject);
			$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
			$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
			$email_subject=str_replace('{project_name}',$project_name,$email_subject);
			$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
			$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{task_name}', $task_name, $email_message);
			$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
			$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
			$email_message=str_replace('{task_description}',$task_description,$email_message);
			$email_message=str_replace('{project_name}',$project_name,$email_message);
			$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
		
		$pass['task_id'] = $task_id;
		$pass['master_task_id'] = $task_data['master_task_id'];
		$pass['task_status_id'] = $orig_data['task_status_id'];
		$pass['swimlane_id'] = $orig_data['swimlane_id'];
		$pass['prerequisite_task_id'] = $orig_data['prerequisite_task_id'];
		
		echo json_encode($pass);die;
	}
	
	/**
	 * This function is used to add actaul time when task is going for complete via drag and drop.It will get actual time of task completion.
         After that it will update DB with new data and check user_id with task_owner,manger of that task.
         Then it will send notification & send mail user and task_owner of the company.
         * @returns void
	/*
	 * Function : add_actual_time_drag
	 * Author : Spaculus
	 * Desc : This function is used to add actaul time when task is going for complete via drag and drop
	*/
	function add_actual_time_drag(){
		
		$theme = getThemeName();
		
		$old_task_id = $_POST['task_id'];
		$task_id = $_POST['task_id'];
		$task_actual_time_hour = $_POST['task_actual_time_hour'];
		$task_actual_time_min = $_POST['task_actual_time_min'];
		$actual_time_task_came_from_orders = $_POST['actual_time_task_came_from_orders'];
		$actual_time_task_dropped_orders = $_POST['actual_time_task_dropped_orders'];
		$actual_time_task_came_from_id = $_POST['actual_time_task_came_from_id'];
		$actual_time_task_dropped_id = $_POST['actual_time_task_dropped_id'];
		/**
                 * check task existance
                 */
		$task_exists = chk_task_exists($task_id);
		if($task_exists == '0'){
                    /**
                     * preg_replace function is replaced some values
                     */
			$main_id = preg_replace("/[^0-9]/", '', $task_id);
			$orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
			
			$chk = chk_virtual_recurrence_exists($post_data['master_task_id'],$post_data['task_scheduled_date']);
			/**
                         * check task id is deleted or not.
                         */
                        if($chk && $chk['is_deleted'] == "0"){
				$task_id = $chk['task_id'];
			} else {
				$task_id = $this->kanban_model->save_task($post_data);
				$steps = get_task_steps($main_id);
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
                                $task_file = get_task_files($main_id);
                                        if($task_file){
                                            foreach($task_file as $file){ 
                                                $file_data = array(
                                                        'task_file_name' => $file['task_file_name'],
                                                        'file_link' => $file['file_link'],
                                                        'file_title' => $file['file_title'],
                                                        'task_id' => $task_id,
                                                        'project_id' => $file['project_id'],
                                                        'file_added_by' => $this->session->userdata('user_id'),
                                                        'file_date_added' => date('Y-m-d H:i:s')
                                                    );

                                                $this->db->insert('task_and_project_files',$file_data);
                                            }
                                        }
			}
		} else {
			$orig_data = get_task_detail($task_id);
		}
		
		$old_task_status_name = get_task_status_name_by_id($orig_data['task_status_id']);
		$new_task_status_name = 'Completed';
		
		$task_time_spent = ($task_actual_time_hour*60)+$task_actual_time_min;
		$completed_status_id = $this->config->item('completed_id');
                $task_scheduled_date = get_task_schedule_date($task_id);
	        if($task_scheduled_date == '0000-00-00'){
                    $task_update_data = array(
			"task_time_spent"=>$task_time_spent,
			"task_status_id" => $completed_status_id,
			"task_completion_date"=>date('Y-m-d H:i:s'),
                        "task_scheduled_date"=>date('Y-m-d'),
                        "billed_time"=>$task_time_spent
                    );
                }else{
                    $task_update_data = array(
                        "task_time_spent"=>$task_time_spent,
                        "task_status_id" => $completed_status_id,
                        "task_completion_date" =>date('Y-m-d H:i:s'),
                        "billed_time"=>$task_time_spent
                    );
		}
		
                $this->db->where("task_id",$task_id);
		$this->db->update("tasks",$task_update_data);
		
		if($orig_data['task_status_id'] != $completed_status_id){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $task_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		$swimlane_data = array(
				'kanban_order' => 1
		);
		$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
		$this->db->where('task_id',$task_id);
		$this->db->update('user_task_swimlanes',$swimlane_data);
		$this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                         SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                         WHERE `uts`.`user_id` = '".$this->session->userdata('Temp_kanban_user_id')
                                         ."' AND `uts`.`task_id` != '$task_id'
                                         AND `t`.`task_status_id` = '$completed_status_id'
                                         ");

		/**** order set for came from *****/
		
		$scope_id = $task_id;
		
		$status_arr = explode('_', $actual_time_task_came_from_id);
		$status_id = $status_arr[2];
		if(isset($status_arr[3])){
			$swimlalane_id = $status_arr[3];
		} else {
			$swimlalane_id = 0;
		}
		
		if($actual_time_task_came_from_orders){
			$step1 = explode('&', $actual_time_task_came_from_orders);
			$i = 1;
			foreach($step1 as $step){
				if(strpos($step, 'child') !== false){
					$virtual_ids = explode('[]=', $step);
					$custom_id = 'child_'.$virtual_ids[1];
					if($old_task_id == $custom_id){
						$task_id1 = $scope_id;
					} else {
						$task_id1 = '';
					}
				} else { 
					$id = preg_replace("/[^0-9]/", '', $step);
					$task_id1 = $id;
				}
				if($task_id1){
					$task_data = array(
						'task_status_id' => $status_id
					);
					$this->db->where('task_id',$task_id1);
					$this->db->update('tasks',$task_data);
					
					$swimlane_data = array(
						'swimlane_id' => $swimlalane_id,
						'kanban_order' => $i
					);
					$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
					$this->db->where('task_id',$task_id1);
					$this->db->update('user_task_swimlanes',$swimlane_data);
				}
				
				$i++;
			}
			
		} 
		
		
		/**** order set for dropped *****/
		
		$scope_id2 = $task_id;
		
		$status_arr2 = explode('_', $actual_time_task_dropped_id);
		$status_id2 = $status_arr2[2];
		if(isset($status_arr2[3])){
			$swimlalane_id2 = $status_arr2[3];
		} else {
			$swimlalane_id2 = 0;
		}
		
		if($actual_time_task_dropped_orders){
			$step2 = explode('&', $actual_time_task_dropped_orders);
			$j = 1;
			foreach($step2 as $step3){
				
				if(strpos($step3, 'child') !== false){
					$virtual_ids2 = explode('[]=', $step3);
					$custom_id2 = 'child_'.$virtual_ids2[1];
					if($old_task_id == $custom_id2){
						$task_id2 = $scope_id2;
					} else {
						$task_id2 = '';
					}
				} else {
					$id = preg_replace("/[^0-9]/", '', $step3);
					$task_id2 = $id;
				}
				
				if($task_id2){
					$task_data = array(
						'task_status_id' => $status_id2
					);
					$this->db->where('task_id',$task_id2);
					$this->db->update('tasks',$task_data);
					
					$swimlane_data = array(
						'swimlane_id' => $swimlalane_id2,
						'kanban_order' => $j
					);
					$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
					$this->db->where('task_id',$task_id2);
					$this->db->update('user_task_swimlanes',$swimlane_data);
				}
				
				$j++;
			}
			
		} 
		
		
		/**** end of order sets ****/
		
		$kanban_order = get_task_kanban_order($task_id,get_authenticateUserID());
		
		$task_data = get_task_detail($task_id);
		//email variables
		$owner_name = usernameById($task_data['task_owner_id']);
		if($task_data['task_due_date']!='0000-00-00'){
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$task_due_date = date($this->config->item('company_default_format'),strtotime($task_data['task_due_date']));
			date_default_timezone_set("UTC");
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
		
		$completion_user_name = $this->session->userdata('username');
			
		if($task_data['task_owner_id'] !=get_authenticateUserID()){
			
			/**
                         * notification
                         */
			$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
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
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
			$email_temp=$email_template->row();	
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;
			
			$email_subject=$email_temp->subject;				
			$email_message=$email_temp->message;
			
			$user_info = get_user_info($task_data['task_owner_id']);
			$user_name = $user_info->first_name.' '.$user_info->last_name;
			$task_name = $task_data['task_title'];
			
			$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
				
			
			$email_to = $user_info->email;
			$subscription_link = site_url();
			
			$email_subject=str_replace('{break}','<br/>',$email_subject);
			$email_subject=str_replace('{user_name}',$user_name,$email_subject);
			$email_subject=str_replace('{task_name}', $task_name, $email_subject);
			$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
			$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
			$email_subject=str_replace('{project_name}',$project_name,$email_subject);
			$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
			$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{task_name}', $task_name, $email_message);
			$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
			$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
			$email_message=str_replace('{task_description}',$task_description,$email_message);
			$email_message=str_replace('{project_name}',$project_name,$email_message);
			$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
		
		if($task_data['task_owner_id'] != $task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
			
			//notification
			$notification_text = '"'.$task_data['task_title'].'" is completed by '.$this->session->userdata('username').' this user.';
			$notification_data = array(
				'task_id' => $task_id,
				'project_id' => $task_data['task_project_id'],
				'notification_text' => $notification_text,
				'notification_user_id' => $task_data['task_allocated_user_id'],
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
			
			$user_info = get_user_info($task_data['task_allocated_user_id']);
			$user_name = $user_info->first_name.' '.$user_info->last_name;
			$task_name = $task_data['task_title'];
			
			$allocated_user_info = get_user_info($task_data['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
				
			
			$email_to = $user_info->email;
			$subscription_link = site_url();
			
			$email_subject=str_replace('{break}','<br/>',$email_subject);
			$email_subject=str_replace('{user_name}',$user_name,$email_subject);
			$email_subject=str_replace('{task_name}', $task_name, $email_subject);
			$email_subject=str_replace('{task_owner_name}',$owner_name,$email_subject);
			$email_subject=str_replace('{task_due_date}',$task_due_date,$email_subject);
			$email_subject=str_replace('{project_name}',$project_name,$email_subject);
			$email_subject=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_subject);
			$email_subject=str_replace('{completion_user_name}',$completion_user_name,$email_subject);
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{task_name}', $task_name, $email_message);
			$email_message=str_replace('{task_owner_name}',$owner_name,$email_message);
			$email_message=str_replace('{task_due_date}',$task_due_date,$email_message);
			$email_message=str_replace('{task_description}',$task_description,$email_message);
			$email_message=str_replace('{project_name}',$project_name,$email_message);
			$email_message=str_replace('{task_allocated_to_name}',$allocate_user_name,$email_message);
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
		
		$pass['task_id'] = $task_id;
		$pass['master_task_id'] = $task_data['master_task_id'];
		$pass['task_status_id'] = $orig_data['task_status_id'];
		$pass['swimlane_id'] = $orig_data['swimlane_id'];
		$pass['prerequisite_task_id'] = $task_data['prerequisite_task_id'];
		$pass['task_kanban_order'] = $kanban_order;
		$pass['kanban_order_after'] = $kanban_order-1;
		
		
		echo json_encode($pass);die;
		
		
		
	}

	/**
	 * This function will set task on kanban board.It fetch data from config and methods for ajax task_div.
         * @returns void 
	/*
	 * function : set_task
	 * return : ajax view for task display
	 */
	function set_task(){
		$theme = getThemeName();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['kanban'] = get_task_detail($_POST['task_id']);
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		echo $this->load->view($theme.'/layout/kanban/ajax_task_div',$data,TRUE);
	}
	
	/**
         * On Ajax request,it will return non completed recurrence task from DB . 
         * @returns void
         */
	/*
	 * function : next_noncompleted_recurrence
	 * return : next non completed recurrence of task
	 * author : Spaculus
	 */
	function next_noncompleted_recurrence(){
		$theme = getThemeName();
		
		$com_off_days = get_company_offdays();
		$task_id = $_POST['task_id'];
		$task_detail = get_task_detail($task_id);
		$task_status_completed_id = $this->config->item('completed_id');
		
		if($task_detail['frequency_type'] == 'recurrence' && $task_detail['recurrence_type']!='0'){
			$virtual_array = kanban_recurrence_logic($task_detail);
			$chk_recu = chk_recurrence_exists($task_detail,$virtual_array,$task_status_completed_id,$com_off_days);
			if($chk_recu){
				if(strpos($chk_recu['task_id'], 'child') !== false){
					$new_task_inserted_id = $this->kanban_model->save_task($chk_recu);
					$task_data = get_task_detail($new_task_inserted_id);
				} else {
					$task_data = $chk_recu;
				}
				if($task_data){
					$data['task_id'] = $task_data['task_id'];
					$data['task_status_id'] = $task_data['task_status_id'];
					$data['swimlane_id'] = $task_data['swimlane_id'];
					echo json_encode($data);die;
				}
			}
		}
	}
	
	/*
	 * function : check_completed_dependency
	 * return : check completed dependency of task
	 * author : Spaculus
	 */
	 
	function check_completed_dependency()
	{
		$task_id = $_POST['task_id'];
		$completed_id = $this->config->item('completed_id');
		$completed_depencencies = chk_dependency_status($task_id,$completed_id);
		$data['completed_depencencies'] = $completed_depencencies; 
		if($completed_depencencies == 'green')
		{
			$ready_id = get_task_status_id_by_name("Ready");
			$data['main_task_status_id'] = get_taskStatus_id($task_id);
			$update_data = array("task_status_id"=>$ready_id);
			$this->db->where("task_id",$task_id);
			$this->db->update("tasks",$update_data);
			
			$swimlane_data = array(
					'kanban_order' => 1
			);
			$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
			$this->db->where('task_id',$task_id);
			$this->db->update('user_task_swimlanes',$swimlane_data);
			
                         $this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                                  SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                                  WHERE `uts`.`user_id` = '".$this->session->userdata('Temp_kanban_user_id')
                                                  ."' AND `uts`.`task_id` != '$task_id'
                                                  AND `t`.`task_status_id` = '$ready_id'
                                                  ");
                        
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $this->session->userdata("Temp_kanban_user_id"));
//			$this->db->where('uts.task_id != ',$task_id);
//			$this->db->where('t.task_status_id', $ready_id);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
			
			
			$data['task_status_id'] = $ready_id;
			
			echo json_encode($data);die;
		}else if($completed_depencencies == 'red')
		{
			$not_ready_id = get_task_status_id_by_name("Not Ready");
			$data['main_task_status_id'] = get_taskStatus_id($task_id);
			$update_data = array("task_status_id"=>$not_ready_id);
			$this->db->where("task_id",$task_id);
			$this->db->update("tasks",$update_data);
			
			$swimlane_data = array(
					'kanban_order' => 1
			);
			$this->db->where('user_id',$this->session->userdata("Temp_kanban_user_id"));
			$this->db->where('task_id',$task_id);
			$this->db->update('user_task_swimlanes',$swimlane_data);
			$this->db->query("update `user_task_swimlanes` as `uts` join `tasks` as `t` ON `t`.`task_id` = `uts`.`task_id`
                                         SET `uts`.`kanban_order` = `uts`.`kanban_order` + 1
                                         WHERE `uts`.`user_id` = '".$this->session->userdata('Temp_kanban_user_id')
                                         ."' AND `uts`.`task_id` != '$task_id'
                                         AND `t`.`task_status_id` = '$not_ready_id'
                                         ");
//			$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
//			$this->db->where('uts.user_id', $this->session->userdata("Temp_kanban_user_id"));
//			$this->db->where('uts.task_id != ',$task_id);
//			$this->db->where('t.task_status_id', $not_ready_id);
//			$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
//			
			$data['task_status_id'] = $not_ready_id;
			
			echo json_encode($data);die;
		}else{
			echo "";
		}
		
	}
	
	
	function save_task_swimlane()
	{
		$swimlane_id = $_POST['swimlane_id'];
		$user_id = $this->session->userdata("Temp_kanban_user_id");
		$swimlane_type = $_POST['swimlane_type'];
		
		if($swimlane_type == 'hide'){
			$data = array(
				'swimlane_show_hide'=>'0'
			);
			$this->db->where(array('swimlanes_id'=>$swimlane_id,'user_id'=>$user_id));
			$this->db->update('swimlanes',$data);	
		}
		if($swimlane_type == 'show'){
			$data = array(
				'swimlane_show_hide'=>'1'
			);
			$this->db->where(array('swimlanes_id'=>$swimlane_id,'user_id'=>$user_id));
			$this->db->update('swimlanes',$data);	
		}
		
		echo "done";die;
		
	}
/**
 * This function will add comment in DB and return all task details by using get_task_comments method.
 * @returns void
 */
	function add_comment_from_list(){
		
		$task_id = $_POST['task_id'];
		$project_id = get_project_id_from_task_id($task_id);
		$insert_data = array(
			'task_comment' => htmlspecialchars($_POST['task_comment_list']),
			'task_id' => $task_id,
			'comment_addeby' => $this->session->userdata('user_id'),
			'project_id' => $project_id,
			'comment_added_date' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('task_and_project_comments',$insert_data);
		$id = $this->db->insert_id();
		$data['task']['comments'] = get_task_comments($task_id);
		echo json_encode($data);die;

	}
        function get_kanban_project_team(){
             $theme = getThemeName();
             $data=array();
           if(isset($_POST['id'])){
               $data['ids']=$_POST['id'];
           }
            else {
                 die();
            }
          echo  $this->load->view($theme.'/layout/kanban/kanban_project_ajx',$data, TRUE);
        }
        
	
}
?>
