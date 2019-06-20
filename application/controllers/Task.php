<?php
/**
 * This class is created for manage task in different pages.It have various methods for save,delete,update and assign tasks.  
 * This class is extending the SPACULLUS_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Task extends SPACULLUS_Controller{
    /**
        * It default constuctor which is called when task class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */
	function Task(){
            /*
             * call base class contructor 
             */
		parent :: __construct ();
                /*
                 * Amazon S3  Configuration file
                 */
		$this->load->library('s3');
                /*
                 * Amazon S3 server Configuration file
                 */
		$this->config->load('s3');
                /*
                 * load database class of task class
                 */
		$this->load->model('task_model');
                /*
                 * load project_model for task class
                 */
		$this->load->model('project_model');
                /*
                 * load user_agent library
                 */
		$this->load->library('user_agent');
                /*
                 * set default timezone of date
                 */
		date_default_timezone_set("UTC");
                $this->load->model('kanban_model');
	}
        /**
         * this function is used for getting backlog task.
         */
        function get_user_back_log_task(){

		$theme = getThemeName();
		$this->load->model('user_model');
		$data = array();
		$task_status_completed_id = $this->config->item('completed_id');
		$data['task_list'] = $this->user_model->get_notScheduledTask($task_status_completed_id);
		$this->load->view($theme.'/layout/common/ajax_back_log',$data);
	}
        /**
         * This function is used for save user task in db.This function is checked name,value,task_id set or not, than it will call a method of task_model class for save task in db.
         * @returns void
         */

	function saveTask(){
		$name = isset($_POST['name'])?$_POST['name']:'';
		$value = isset($_POST['value'])?$_POST['value']:'';
		$task_id = isset($_POST['task_id'])?$_POST['task_id']:'';
		$redirect_page = isset($_POST['redirect_page'])?$_POST['redirect_page']:'';
		if(isset($_POST['task_scheduled_date']) && $_POST['task_scheduled_date']!="0000-00-00"){
			$task_scheduled_date = change_date_format($_POST['task_scheduled_date']);
		} else {
			$task_scheduled_date = '';
		}
		
		$id = $this->task_model->saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$task_id);
                switch ($name){
                    case "customer_id":
                        $name = "customer_id";
                        $value = isset($_POST['sub_val'])?$_POST['sub_val']:'';
                        $id = $this->task_model->saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$id);
                        break;
                    case "task_category_id":
                        $name = "task_sub_category_id";
                        $value = isset($_POST['sub_val'])?$_POST['sub_val']:'';
                        $id = $this->task_model->saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$id);
                        break;
                    case "task_project_id":
                        $name = "subsection_id";
                        $value = isset($_POST['sub_val'])?$_POST['sub_val']:'';
                        $id = $this->task_model->saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$id);
                        $name = "task_allocated_user_id";
                        $value = isset($_POST['sub_val2'])?$_POST['sub_val2']:get_authenticateUserID();
                        $id = $this->task_model->saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$id);
                        $name = 'task_project_id';
                        $value = get_project_id_by_section_id($_POST['sub_val']);
                        $id = $this->task_model->saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$id);
                        break;
                    case "task_division_id[]":
                        $name = "task_department_id[]";
                        $value = isset($_POST['sub_val'])?$_POST['sub_val']:'';
                        $id = $this->task_model->saveTaskIndividual($name,$value,$redirect_page,$task_scheduled_date,$id);
                        break;
                }
		
		echo $id;die;
	}
	/*
	 * Function : task_data
	 * Author : Spaculus
	 * Desc : This function is used to get task data from task id by ajax request
	 */
	/**
         * This function is used to get task data from task id by ajax request
         * @returns void
         */

	function task_data(){
		$task_id = $_POST['task_id'];
		$post_data = isset($_POST['post_data'])?$_POST['post_data']:'';
		$data['task']['general']['is_dependency_exist'] = array();
		$from = isset($_POST['from'])?$_POST['from']:'';
                $recurring_type = isset($_POST['recurring_type'])?$_POST['recurring_type']:'';
		$task_data = '';
                
		if(isset($_POST['type'])){$data['type']= $_POST['type'];}

		if($post_data){
			$task_data = json_decode($_POST['post_data'],true);
		}

		if($task_data){
			$data['task']['general'] = $task_data;
			$data['task']['general']['first_name'] = get_user_first_name($task_data['task_owner_id']);
			$data['task']['general']['last_name'] = get_user_last_name($task_data['task_owner_id']);
			$data['task']['dependencies'] = '';
			$data['task']['steps'] = get_task_steps($task_data['master_task_id']);
			$data['task']['files'] = '';

		} else {
			$data['task'] =   array(
	            'general' => get_task_detail($task_id),
	            'dependencies' => get_task_dependencies($task_id),
	            'steps' => get_task_steps($task_id),
	    	);
		}

		if($data['task']['general']){
			if($data['task']['general']['task_due_date'] != '0000-00-00'){
				date_default_timezone_set($this->session->userdata("User_timezone"));
				$data['task']['general']['user_task_due_date'] = date($this->config->item('company_default_format'),strtotime($data['task']['general']['task_due_date']));

				$data['task']['general']['strtotime_scheduled_date'] = strtotime($data['task']['general']['task_scheduled_date']);
				date_default_timezone_set("UTC");
			} else {
				$data['task']['general']['user_task_due_date'] = '';
				$data['task']['general']['strtotime_scheduled_date'] =  '';
			}

			if($data['task']['general']['master_task_id']>0){
				$data['task']['general']['is_master_valid'] = chk_master_task_id_deleted($data['task']['general']['master_task_id']);
			} else {
				$data['task']['general']['is_master_valid'] = 0;
			}
		}
		if($data['task']['general']){
			$total_task_time_spent_minute = $data['task']['general']['task_time_spent'];
			$spent_hours = intval($total_task_time_spent_minute/60);
			$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
			$data['task']['general']['task_time_spent_hour'] = $spent_hours;
			$data['task']['general']['task_time_spent_min'] = $spent_minutes;

			if($spent_hours > 0 || $spent_minutes > 0){
				$data['task']['general']['task_time_spent'] = $spent_hours."h ".$spent_minutes."m";
			} else{
				$data['task']['general']['task_time_spent'] = '';
			}

			$total_task_time_estimate_minute = $data['task']['general']['task_time_estimate'];
			$estimate_hours = intval($total_task_time_estimate_minute/60);
			$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
			$data['task']['general']['task_time_estimate_hour'] = $estimate_hours;
			$data['task']['general']['task_time_estimate_min'] = $estimate_minutes;
			if($estimate_hours > 0 || $estimate_minutes > 0){
				$data['task']['general']['task_time_estimate'] = $estimate_hours."h ".$estimate_minutes."m";
			}else{
				$data['task']['general']['task_time_estimate'] = '';
			}

			if($data['task']['general']['task_division_id']){
				$data['task']['general']['task_division_id'] = explode(',', $data['task']['general']['task_division_id']);
			}
			if($data['task']['general']['task_department_id']){
				$data['task']['general']['task_department_id'] = explode(',', $data['task']['general']['task_department_id']);
			}
			if($data['task']['general']['task_skill_id']){
				$data['task']['general']['task_skill_id'] = explode(',', $data['task']['general']['task_skill_id']);
			}

			$data['users'] = get_user_list($data['task']['general']['task_division_id'],$data['task']['general']['task_department_id'],$data['task']['general']['task_skill_id'],$data['task']['general']['task_staff_level_id']);
			$data['task']['general']['is_dependency_exist'] = chk_dependency_status($data['task']['general']['task_id'],$this->config->item('completed_id'));
		} else {
			$data['users'] = get_user_list();
			
		}
                $data['color_codes'] = get_user_color_codes($data['task']['general']['task_allocated_user_id']);
                $allc_user=get_list_user_report_to_adminstartor();
                if(isset($allc_user) && !empty($allc_user)){
                  foreach($allc_user as $val ){
                      if($val['user_id']==$data['task']['general']['task_owner_id']){
                      $data['task']['report_user_list_id']='1';  
                      }
                  }
                }else{
                    $data['task']['report_user_list_id']='0';
                }
                if($recurring_type == '0'){
                    $data['is_multiallocation_task'] = $this->task_model->get_multiallocation_taks($data['task']['general']['master_task_id']);
                }else {
                    $data['is_multiallocation_task'] = $this->task_model->get_multiallocation_taks($data['task']['general']['task_id']);
                }
		
		echo json_encode($data);die;
	}
	

	/*
	 * Function : setSubCategory
	 * Author : Spaculus
	 * Desc : This function is used to get subcategory of default main category
	 */
	/**
         * This function is used to get subcategory of default main category.It get parent,sub category id, than check and load subcategory list in view page.
         * @returns void
         */
	function setSubCategory(){

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template ($theme.'/template2.php');
                /*
                 * get data from user input
                 */
		$parent_id = $_POST['parent_id'];

		$sub_id = isset($_POST['sub_id'])?$_POST['sub_id']:'';
		$data['sub_id'] = $sub_id;
		$data['parent_id'] = $parent_id;
                /**
                 * with the help of parent get subcategory values
                 */
		if($parent_id){
			$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active',$parent_id);
		} else {
			$data['sub_category'] = '';
		}
		$cat = get_company_sub_category($this->session->userdata('company_id'),'Active');
		if($cat){
			$data['is_sub_category_exist'] = "1";
		} else {
			$data['is_sub_category_exist'] = "0";
		}
		
		$this->load->view($theme.'/layout/task/ajax_subCategory', $data);
	}
        /**
         * This function is used for delete task from right click popup window.It check task id,than it update many tables in db after that it will set is_deleted value 1 in task table.
         * @returns void
         */
	function delete_task(){

		$task_id = $_POST['task_id'];
		$post_data = isset($_POST['post_data'])?json_decode($_POST['post_data'],true):'';
		$from = isset($_POST['from'])?$_POST['from']:'';
                /*
                 * check task existance
                 */
		$task_exists = chk_task_exists($task_id);
		if($task_exists == '0'){
			$this->load->model("kanban_model");
			$task_id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($post_data['master_task_id']);
                        /*
                         * check and save steps in task_steps table
                         */
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
                /*
                 * with task_id it update tables 
                 */
                $data['task_id'] = $task_id;
                $data['task_title'] = get_task_title($task_id);
		if($task_id !=''){
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

			if($from == 'series' && $task_id !=''){
				$update_data = array('is_deleted'=>'1');
				$this->db->where('master_task_id',$task_id);
				$this->db->update('tasks',$update_data);
			}
                        $data['reponse'] = 'done';
			echo json_encode($data);die;
		}
	}

	/*
	 * Function : dependencies
	 * Author : Spaculus
	 * Desc : This function is used to save dependencies
	 */
 /**
         * This function checks authentication than apply some validation on task_title than it save in db.
         * @returns void
         */
	function dependencies(){
            /*
             * check authentication otherwise redirect on home
             */
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
                /*
                 * load form validation
                 */
		$this->load->library('form_validation');
		$this->form_validation->set_rules('task_title','Quick add', 'required');
		if($this->form_validation->run() == false){
			if(validation_errors()){
				$data['error'] = validation_errors();
			} else {
				$data['error'] = '';
			}
			$data['task_title'] = $this->input->post('task_title');
			$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
			$data['task_due_date'] = $this->input->post('task_due_date');
			$data['task_id'] = $this->input->post('task_id');
		} else {
			$dependencies_id = $this->task_model->insert_task_dependencies();
		}
		$data['task_id'] = $_POST['task_id'];
		$data['task']['dependencies'] = get_task_dependencies($this->input->post('task_id'));
		$this->load->view($theme.'/layout/task/ajax_add_dependencies',$data);
	}
/*
	 * Function : delete_dependent_task
	 * Author : Spaculus
	 * Desc : This function is used to delete dependent task
	 */
        /**
         * This function will delete dependent task and render new view
         * @returns view
         */
	function delete_dependent_task(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$task_id = $_POST['dependent_task_id'];
		$update = array(
			'is_deleted' => '1'
		);
		$this->db->where('task_id',$task_id);
		$this->db->update('tasks',$update);

		$history_data = array(
			'histrory_title' => 'Dependency task deleted.',
			'history_added_by' => $this->session->userdata('user_id'),
			'task_id' => $_POST['task_id'],
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);

		$data['task_id'] = $_POST['task_id'];
		$data['task']['dependencies'] = get_task_dependencies($_POST['task_id']);
		$this->load->view($theme.'/layout/task/ajax_add_dependencies',$data);
	}

	/*
	 * Function : get_allocated_user_list
	 * Author : Spaculus
	 * Desc : This function is used to get allocated user list from equivalent selction
	 */
	function get_allocated_user_list(){
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$division_ids = $_POST['division_id'];
		$department_ids = $_POST['dept_id'];
		$skill_ids = $_POST['skill_id'];
		$staff_level_ids = $_POST['staff_level_id'];
		$data['users'] = get_user_list($division_ids,$department_ids,$skill_ids,$staff_level_ids);
		$this->load->view($theme.'/layout/task/ajax_allocated_users',$data);
	}

	/*
	 * Function : steps
	 * Author : Spaculus
	 * Desc : This function is used to save steps
	 */
        /**
         * This function call when user save steps in db.this function check authentication than save steps otherwise it redirect on home.
         * @returns void
         */
	function steps(){
            /*
             * check authentication
             */
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		if($_POST){
			$step_title = htmlspecialchars($_POST['step_title']);
			$task_id = $_POST['task_id'];
			$task_step_id = $_POST['task_step_id'];
			if($task_step_id != ''){
				$steps_id = $this->task_model->update_task_steps($step_title,$task_id,$task_step_id);
			} else {
				$steps_id = $this->task_model->insert_task_steps($step_title,$task_id);
			}
			$data['task_id'] = $_POST['task_id'];
			$data['task']['steps'] = get_task_steps($this->input->post('task_id'));
			echo json_encode($data);die;
		}
	}
        /*
         * This function is used for set steps for indivisual task.
         * returns json
         */
        function steps_occurrence(){
                /*
                 * check authentication
                 */
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		if($_POST){
                        $step_title = htmlspecialchars($_POST['step_title']);
			$task_id = $_POST['task_id'];
                        $post_data=$_POST['post_data'];
                        //echo $post_data['task_id']; 
			$task_step_id = $_POST['task_step_id'];
                        $task_exists = chk_task_exists($post_data['task_id']);
                        if($task_exists=='0'){
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
                                                'file_date_added' => $file['file_date_added']
                                            );

                                        $this->db->insert('task_and_project_files',$file_data);
                                    }
                                }
                                if($task_step_id != ''){
                                        $steps_id = $this->task_model->update_task_steps($step_title,$id,$task_step_id);
                                } else {
                                        $steps_id = $this->task_model->insert_task_steps($step_title,$id);
                                }
                                $data['task_id'] = $id;
                                $data['task']['steps'] = get_task_steps($id);
                                
                        }
                        else
                        {
                                if($task_step_id != ''){
                                    $steps_id = $this->task_model->update_task_steps($step_title,$task_id,$task_step_id);
                                } else {
                                    $steps_id = $this->task_model->insert_task_steps($step_title,$task_id);
                                }
                                $data['task_id'] = $task_id;
                                $data['task']['steps'] = get_task_steps($task_id);
                        }
                        $data['task_data']=get_task_detail($data['task_id']);
			//echo "<pre>"; print_r($data); die();
			echo json_encode($data);die;
		}
	}
	/*
	 * Function : delete_step
	 * Author : Spaculus
	 * Desc : This function is used to delete steps
	 */
        /**
         * When user click on delete icon at the same time this function will call for delete step from db.
         * @returns json
         */
	function delete_step(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		$task_step_id = $_POST['task_step_id'];
		
		/*
                 * update table for delete
                 */
		$delete_data = array("is_deleted"=>"1");
		$this->db->where('(task_step_id = '.$task_step_id.' or (multi_allocation_step_id = '.$task_step_id.' and is_deleted = 0))');
		$this->db->update('task_steps',$delete_data);
		/*
                 * insert delete id in task_history table
                 */
		$history_data = array(
			'histrory_title' => 'Task step deleted.',
			'history_added_by' => $this->session->userdata('user_id'),
			'task_id' => $_POST['task_id'],
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);

		$data['task_id'] = $_POST['task_id'];
		$data['task']['steps'] = get_task_steps($this->input->post('task_id'));
		echo json_encode($data);die;
	}

	/*
	 * Function : update_step
	 * Author : Spaculus
	 * Desc : This function is used to update steps detail
	 */
	function update_step(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		$task_step_id = $_POST['task_step_id'];
		$data = array(
			'is_completed' => '1'
		);
		//$this->db->where('task_step_id',$_POST['task_step_id']);
		$this->db->where('(task_step_id = '.$_POST['task_step_id'].' or (multi_allocation_step_id = '.$_POST['task_step_id'].' and is_deleted = 0))');
		$this->db->update('task_steps',$data);

		$data['task_id'] = $_POST['task_id'];
		$data['task']['steps'] = get_task_steps($this->input->post('task_id'));
		echo json_encode($data);die;
	}

	/*
	 * Function : set_task_seq
	 * Author : Spaculus
	 * Desc : This function is used to set steps sequence
	 */
	function set_task_seq(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		if($_POST){
			$id = $this->task_model->update_task_step_seq();
			$data['task_id'] = $id;
			$data['task']['steps'] = get_task_steps($id);
			echo json_encode($data);die;
		}
	}

	/*
	 * Function : files
	 * Author : Spaculus
	 * Desc : This function is used to add files
	 */
/**
         * In ajax request,this function add user selected files in db.
         * @returns void
         */
	function files(){
		if(!check_user_authentication()){
			redirect('home');
		}
		//$this->config->load('s3');
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
                $task_id = isset($_POST['task_id'])?$_POST['task_id']:'';
                $post_data = json_decode($_POST['task_data'],true);
                $chk_exist = chk_task_exists($task_id);
		if($chk_exist == '1'){
                        $id = $this->task_model->add_task_files($task_id);
			$data['task_id'] = $task_id;
			$data['files'] = get_task_inserted_file($id);
                        $data1['view'] = $this->load->view($theme.'/layout/task/ajax_add_files',$data,true);
		}  else {
                        $task_id = $this->kanban_model->save_task($post_data);
                        $steps = get_task_steps($post_data['master_task_id']);
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
                        $id = $this->task_model->add_task_files($task_id);
                        $task_file = get_task_files($post_data['master_task_id']);
                        if($task_file){
                            foreach($task_file as $file){ 
                                $file_data = array(
                                        'task_file_name' => $file['task_file_name'],
                                        'file_link' => $file['file_link'],
                                        'file_title' => $file['file_title'],
                                        'task_id' => $task_id,
                                        'project_id' => $file['project_id'],
                                        'file_added_by' => $this->session->userdata('user_id'),
                                        'file_date_added' => $file['file_date_added']
                                    );

                                $this->db->insert('task_and_project_files',$file_data);
                            }
                        }
			
			$data['task']['files'] = get_task_files($task_id);
                        $data1['view'] = $this->load->view($theme.'/layout/task/ajax_files',$data,true);
		}
		
                $data1['task_data'] =  get_task_detail($task_id);
                $data1['task_id'] = $task_id;
                echo json_encode($data1); die();
	}   

	/*
	 * Function : ajax_files
	 * Author : Spaculus
	 * Desc : This function is used to give added files list via ajax request
	 */
	function ajax_files(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$this->config->load('s3');
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		$task_id = $_POST['task_id'];
                $task_data = isset($_POST['task_data'])?json_decode($_POST['task_data'],true):'';
                $chk_exist = chk_task_exists($task_id);
		if($chk_exist=='0'){
			$data['task']['files'] = get_task_files($task_data['master_task_id']);
                        $data['task']['is_master_id'] = 'true';
		} else {
			$data['task']['files'] = get_task_files($task_id);
                        
		}
		
		$data['task']['general']['task_id'] =  $_POST['task_id'];
		$data['task']['general']['task_owner_id'] = get_task_owner_id($_POST['task_id']);

		$this->load->view($theme.'/layout/task/ajax_files',$data);
	}

	/*
	 * Function : delete_task_file
	 * Author : Spaculus
	 * Desc : This function is used to delete task file
	 */
	function delete_task_file(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST){
			$task_file_name = $this->task_model->get_task_file_detail($_POST['task_file_id']);

			if($task_file_name->task_file_name){
				$this->config->load('s3');
				$delete_image_name = "upload/task_project_files/".$task_file_name->task_file_name;
				$bucket = $this->config->item('bucket_name');
				if($this->s3->getObjectInfo($bucket,$delete_image_name)){
					$this->s3->deleteObject($bucket,$delete_image_name);
				}

				$task_files = array("is_deleted"=>"1");
				//$this->db->where("task_file_id",$_POST['task_file_id']);
				$this->db->where('(task_file_id = '.$_POST['task_file_id'].' or (multi_allocation_file_id = '.$_POST['task_file_id'].' and is_deleted = 0))');
				$this->db->update("task_and_project_files",$task_files);

				$history_data = array(
					'histrory_title' => 'Task file deleted.',
					'history_added_by' => $this->session->userdata('user_id'),
					'task_id' => $_POST['task_id'],
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
				
				$multiIds = multiAllocationTaskIds($_POST['task_id']);
				if($multiIds){
					foreach($multiIds as $mId){
						$history_data = array(
							'histrory_title' => 'Task file deleted.',
							'history_added_by' => $this->session->userdata('user_id'),
							'task_id' => $mId->task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
					}
				}
			}
		}
		$data['task_id'] = $_POST['task_id'];
		$this->load->view($theme.'/layout/task/ajax_add_files',$data);

	}

	/*
	 * Function : comment
	 * Author : Spaculus
	 * Desc : This function is used to display task comments
	 */
/**
         * This function insert task comment in db with task_model class.
         * @returns void
         */
	function comment(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST){
                    /*
                     * insert task comment 
                     */
			$id = $this->task_model->insert_task_comments();
			$data['task_id'] = $_POST['task_id'];
			$data['comment'] = get_task_inserted_comments($id);
		}
		$this->load->view($theme.'/layout/task/ajax_add_comments',$data);
	}

	/*
	 * Function : ajax_comments
	 * Author : Spaculus
	 * Desc : This function is used to get task comment from ajax request
	 */
	function ajax_comments(){
		$theme = getThemeName();
		$data['theme'] = $theme;
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['task_id'] = $_POST['task_id'];
		if($_POST['task_id']>0){
			$data['task']['comments'] = get_task_comments($_POST['task_id']);
			$data['task']['general']['task_id'] =  $_POST['task_id'];
			$data['task']['general']['task_owner_id'] = get_task_owner_id($_POST['task_id']);
		} else {
			$data['task']['comments'] = '';
			$data['task']['general']['task_id'] =  '';
			$data['task']['general']['task_owner_id'] = '';
		}

		$this->load->view($theme.'/layout/task/ajax_comments',$data);
	}

	/*
	 * Function : delete_task_comment
	 * Author : Spaculus
	 * Desc : This function is used to delete task comments
	 */
	function delete_task_comment(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST){
			$this->db->delete('task_and_project_comments',array('task_comment_id'=>$_POST['task_comment_id']));
			$history_data = array(
				'histrory_title' => 'Task comment deleted.',
				'history_added_by' => $this->session->userdata('user_id'),
				'task_id' => $_POST['task_id'],
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		$data['task_id'] = $_POST['task_id'];
		$this->load->view($theme.'/layout/task/ajax_add_comments',$data);
	}

	/*
	 * Function : get_history
	 * Author : Spaculus
	 * Desc : This function is used to get history of tasks
	 */
/**
         * This function get task history in db.
         * @returns void
         */
	function get_history(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		$data['task_id'] = $_POST['task_id'];
		$data['task']['history'] = get_task_history($_POST['task_id']);
		$data['task']['general']['task_id'] =  $_POST['task_id'];
		$data['task']['general']['task_owner_id'] = get_task_owner_id($_POST['task_id']);
		$this->load->view($theme.'/layout/task/history',$data);
	}

	/*
	 * Function : ajax_history
	 * Author : Spaculus
	 * Desc : This function is used to get history of tasks from ajax
	 */
	function ajax_history(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		$data['task_id'] = $_POST['task_id'];
		if(chk_task_exists($data['task_id'])){
			$data['task']['history'] = get_task_history($_POST['task_id']);
		} else {
			$data['task']['history'] = '';
		}
		
		$data['task']['general']['task_id'] =  $_POST['task_id'];
		$data['task']['general']['task_owner_id'] = get_task_owner_id($_POST['task_id']);
		$this->load->view($theme.'/layout/task/ajax_history',$data);
	}

	/*
	 * Function : frequency
	 * Author : Spaculus
	 * Desc : This function is used to display frequency data
	 */
	function frequency(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('frequency_type','Frequency Type', 'required');
		$data['error'] = '';

		if($this->form_validation->run() == false){
			if(validation_errors()){
				$data['error'] = validation_errors();
			} else {
				$data['error'] = '';
			}

			$data['task_id'] = $this->input->post('task_id');
			$data['task']['general']['frequency_type'] = $this->input->post('frequency_type');
			$data['task']['general']['recurrence_type'] = $this->input->post('recurrence_type');

			$data['task']['general']['Daily_every_day'] = $this->input->post('Daily_every_day');
			$data['task']['general']['Daily_every_weekday'] = $this->input->post('Daily_every_weekday');

			$data['task']['general']['Weekly_every_week_no'] = $this->input->post('Weekly_every_week_no');
			$data['task']['general']['Weekly_week_day'] = $this->input->post('Weekly_week_day');

			$data['task']['general']['Monthly_op1_1'] = $this->input->post('Monthly_op1_1');
			$data['task']['general']['Monthly_op1_2'] = $this->input->post('Monthly_op1_2');
			$data['task']['general']['Monthly_op2_1'] = $this->input->post('Monthly_op2_1');
			$data['task']['general']['Monthly_op2_2'] = $this->input->post('Monthly_op2_2');
			$data['task']['general']['Monthly_op2_3'] = $this->input->post('Monthly_op2_3');
			$data['task']['general']['Monthly_op3_1'] = $this->input->post('Monthly_op3_1');
			$data['task']['general']['Monthly_op3_2'] = $this->input->post('Monthly_op3_2');

			$data['task']['general']['Yearly_op1'] = $this->input->post('Yearly_op1');
			$data['task']['general']['Yearly_op2_1'] = $this->input->post('Yearly_op2_1');
			$data['task']['general']['Yearly_op2_2'] = $this->input->post('Yearly_op2_2');
			$data['task']['general']['Yearly_op3_1'] = $this->input->post('Yearly_op3_1');
			$data['task']['general']['Yearly_op3_2'] = $this->input->post('Yearly_op3_2');
			$data['task']['general']['Yearly_op3_3'] =$this->input->post('Yearly_op3_3');
			$data['task']['general']['Yearly_op4_1'] = $this->input->post('Yearly_op4_1');
			$data['task']['general']['Yearly_op4_2'] = $this->input->post('Yearly_op4_2');

			$data['task']['general']['start_on_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","),"-", $this->input->post('start_on_date'))));
			$data['task']['general']['no_end_date'] = $this->input->post('no_end_date');
			$data['task']['general']['end_after_recurrence'] = $this->input->post('end_after_recurrence');
			$data['task']['general']['end_by_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","),"-", $this->input->post('end_by_date'))));


		} else {
			$id = $this->task_model->insert_frequency_data();
			$data['task_id'] = $this->input->post('task_id');
			$data['msg'] = 'update_msg';

			echo json_encode($data);die;
		}

	}

	

	/*
	 * Function : search_dependency
	 * Author : Spaculus
	 * Desc : This function is used to search dependency
	 */
	function search_dependency(){
		//pr($_POST);die;
		
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('task_name','Task Name', 'required');
		if($this->form_validation->run() == false){
			if(validation_errors()){
				$data['error'] = validation_errors();
			} else {
				$data['error'] = '';
			}
			$data['task_title'] = $this->input->post('task_title');
			$data['search_date'] = $this->input->post('search_date');
			$data['search_task_id'] = $this->input->post('search_task_id');
		} else {
			$data_dependency = array(
				'is_prerequisite_task' => '1',
				'prerequisite_task_id' => $this->input->post('search_task_id')
			);
			
			$this->db->where('task_id',$this->input->post('dep_task_id'));
			$this->db->update('tasks',$data_dependency);
		}
		$data['task_id'] = $_POST['search_task_id'];
		$data['task']['dependencies'] = get_task_dependencies($this->input->post('search_task_id'));
		//pr($data['task']['dependencies']);die;
		$this->load->view($theme.'/layout/task/ajax_add_dependencies',$data);
		
	}

	/*
	 * Function : set_new_task_data
	 * Author : Spaculus
	 * Desc : This function is used to get fill up form with new task
	 */
	function set_new_task_data(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		$data['user'] = get_user_info(get_authenticateUserID());

		$task_id = $_POST['task_id'];
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => get_task_detail($task_id),
            'dependencies' => get_task_dependencies($task_id),
            'steps' => get_task_steps($task_id),
            'files' => get_task_files($task_id),
            'comments' => get_task_comments($task_id),
            'history' => get_task_history($task_id)
		);

		if($data['task']['general'] != ''){
			$total_task_time_spent_minute = $data['task']['general']['task_time_spent'];
			$spent_hours = intval($total_task_time_spent_minute/60);
			$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
			$data['task']['general']['task_time_spent_hour'] = $spent_hours;
			$data['task']['general']['task_time_spent_min'] = $spent_minutes;
			$data['task']['general']['task_time_spent'] = $spent_hours."h ".$spent_minutes."m";


			$total_task_time_estimate_minute = $data['task']['general']['task_time_estimate'];
			$estimate_hours = intval($total_task_time_estimate_minute/60);
			$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
			$data['task']['general']['task_time_estimate_hour'] = $estimate_hours;
			$data['task']['general']['task_time_estimate_min'] = $estimate_minutes;
			$data['task']['general']['task_time_estimate'] = $estimate_hours."h ".$estimate_minutes."m";

			if($data['task']['general']['task_division_id']){
				$data['task']['general']['task_division_id'] = explode(',', $data['task']['general']['task_division_id']);
			}
			if($data['task']['general']['task_department_id']){
				$data['task']['general']['task_department_id'] = explode(',', $data['task']['general']['task_department_id']);
			}
			if($data['task']['general']['task_skill_id']){
				$data['task']['general']['task_skill_id'] = explode(',', $data['task']['general']['task_skill_id']);
			}
			$data['color_codes'] = get_user_color_codes($data['task']['general']['task_allocated_user_id']);
			$data['is_color_exist'] = is_user_color_exist($data['task']['general']['task_allocated_user_id']);
			$data['users'] = get_user_list($data['task']['general']['task_division_id'],$data['task']['general']['task_department_id'],$data['task']['general']['task_skill_id'],$data['task']['general']['task_staff_level_id']);
			$data['divisions'] = getUserDivision($data['task']['general']['task_allocated_user_id']);
			$data['departments'] = getUserDepartment($data['task']['general']['task_allocated_user_id']);
		} else {
			$data['users'] = get_user_list();
			$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));
			$data['is_color_exist'] = is_user_color_exist($this->session->userdata('user_id'));
			$data['divisions'] = getUserDivision($this->session->userdata('user_id'));
			$data['departments'] = getUserDepartment($this->session->userdata('user_id'));
		}

		$data['divisions'] = get_company_division($this->session->userdata('company_id'),'Active');
		$data['departments'] = get_company_department($this->session->userdata('company_id'),'Active');
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');

		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
		
		$this->load->view($theme.'/layout/task/general',$data);
	}

	/*
	 * Function : set_end_date
	 * Author : Spaculus
	 * Desc : This function is used to get frequency start and end date by end date selection
	 */
	function set_end_date(){

		$post_data = $this->input->post();

		$pre_date = get_task_schedule_date($post_data['task_id']);
		$pre_date = change_date_format($pre_date);

		if($pre_date!='0000-00-00' && $pre_date!='' && $post_data['is_start_date'] == '0'){
				$start_on_date = change_date_format($pre_date);
                    $start_on_date = change_date_format($pre_date);
//			if(strtotime(str_replace(array("/"," ",","), "-", $pre_date))>=strtotime(date('Y-m-d'))){
//                            
//				$start_on_date = date('Y-m-d',strtotime(str_replace(array("/"," ",","), "-", $pre_date)));
//			} else {
//				$start_on_date = isset($post_data['start_on_date']) && $post_data['start_on_date']!=''?date("Y-m-d",strtotime(str_replace(array("/"," ",","),"-",$post_data['start_on_date']))):date("Y-m-d");
//			}
		} else {
			$start_on_date = isset($post_data['start_on_date']) && $post_data['start_on_date']!=''?change_date_format($post_data['start_on_date']):date("Y-m-d");
		}

		$post_data['start_on_date'] = $start_on_date;

		$data = $this->set_frequency($post_data);

		if($data['start_date']){
			$data['start_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['start_date'])));
		}
		if($data['end_date']){
			$data['end_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['end_date'])));
		}

		echo json_encode($data);die;
	}

	/*
	 * Function : set_end_after_recurrence
	 * Author : Spaculus
	 * Desc : This function is used to get frequency start and end date by no of recurrence selection
	 */
	function set_end_after_recurrence(){

		$post_data = $this->input->post();
		$post_data['no_end_date'] = "3";


		$pre_date = get_task_schedule_date($post_data['task_id']);
		$pre_date = change_date_format($pre_date);
		if($pre_date!='0000-00-00' && $pre_date!=''){
			if(strtotime(str_replace(array("/"," ",","), "-", $pre_date))>=strtotime(date('Y-m-d'))){
				$start_on_date = change_date_format($pre_date);
			} else {
				$start_on_date = isset($post_data['start_on_date']) && $post_data['start_on_date']!=''?change_date_format($post_data['start_on_date']):date("Y-m-d");
			}
		} else {

			$start_on_date = isset($post_data['start_on_date']) && $post_data['start_on_date']!=''?change_date_format($post_data['start_on_date']):date("Y-m-d");
		}
		$post_data['start_on_date'] = $start_on_date;

		$data = $this->set_frequency($post_data);

		if($data['start_date']){
			$data['start_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['start_date'])));
		}
		if($data['end_date']){
			$data['end_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['end_date'])));
		}

		echo json_encode($data);die;
	}

	/*
	 * Function : set_end_date_from_recurrence
	 * Author : Spaculus
	 * Desc : This function is used to get frequency start and end date by no of recurrence entered value
	 */
	function set_end_date_from_recurrence(){

		$post_data = $this->input->post();
		$post_data['no_end_date'] = "2";


		$pre_date = get_task_schedule_date($post_data['task_id']);
		$pre_date = change_date_format($pre_date);
		if($pre_date!='0000-00-00' && $pre_date!=''){
			if(strtotime(str_replace(array("/"," ",","), "-", $pre_date))>=strtotime(date('Y-m-d'))){
				$start_on_date = change_date_format($pre_date);
			} else {
				$start_on_date = isset($post_data['start_on_date']) && $post_data['start_on_date']!=''?change_date_format($post_data['start_on_date']):date("Y-m-d");
			}
		} else {

			$start_on_date = isset($post_data['start_on_date']) && $post_data['start_on_date']!=''?change_date_format($post_data['start_on_date']):date("Y-m-d");
		}
		$post_data['start_on_date'] = $start_on_date;

		$data = $this->set_frequency($post_data);


		if($data['start_date']){
			$data['start_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['start_date'])));
		}
		if($data['end_date']){
			$data['end_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['end_date'])));
		}
		echo json_encode($data);die;
	}

	/*
	 * Function : set_task_from_start_on_date
	 * Author : Spaculus
	 * Desc : This function is used to get frequency start and end date by start date selection
	 */
/**
         * This function is used to get frequency start and end date by start date selection
         * @returns void
         */
	function set_task_from_start_on_date(){

		$post_data = $this->input->post();

		$data = $this->set_frequency($post_data);

		if($data['start_date']){
			$data['start_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['start_date'])));
		}
		if($data['end_date']){
			$data['end_date'] = date($this->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-", $data['end_date'])));
		}
		echo json_encode($data);die;
	}

	/*
	 * Function : set_frequency
	 * Author : Spaculus
	 * Desc : This function is used to get frequency start and end date by recurrence logic
	 */
	function set_frequency($main_arr){

		$default_day = get_default_day_of_company();
		
		$offdays = get_company_offdays();

		$recurrence_type = isset($main_arr['recurrence_type'])?$main_arr['recurrence_type']:'1';

		$start_on_date = change_date_format($main_arr['start_on_date']);

		$main_arr['end_by_date'] = isset($main_arr['end_by_date']) && $main_arr['end_by_date']!=''?change_date_format($main_arr['end_by_date']):date("Y-m-d");

		$start_on_date = change_date_format($start_on_date);
		$main_arr['end_by_date'] = change_date_format($main_arr['end_by_date']);

		$data = array();

		if($recurrence_type == '1'){
			if(isset($main_arr['no_end_date'])){
				if($main_arr['no_end_date'] == '2'){
					$start_date1 = array();
					$display = '';
					for($i=0;$i<$main_arr['end_after_recurrence'];$i++){

						if(isset($main_arr['Daily_every_weekday']) && $main_arr['Daily_every_weekday']!='0'){

							if($i==0){
								$display = date('Y-m-d', strtotime($start_on_date . ' +0 days'));
							} else {
								$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_week_day'].' days'));
							}

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
									$start_date1[] = $display;
								}
							} else {
								$start_date1[] = $display;
							}

						} else if(isset($main_arr['Daily_every_day'])) {

							if($i==0){
								$display = date('Y-m-d', strtotime($start_on_date . ' +0 days')); //gives after 2 days date without including saturday sunday only business days.
							} else {
								$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_day'].' days')); //gives after 2 days date without including saturday sunday only business days.
							}


							$start_date1[$i] = $display;
						} else {
							break;
						}

						$start_on_date = $display;
					}

					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = count($start_date1);

				} elseif($main_arr['no_end_date'] == '3'){

					$end_by_date = change_date_format($main_arr['end_by_date']);

					$i = 0;
					$start_date1 = array();
					$display = '';
					while (strtotime($start_on_date) <= strtotime($end_by_date)) {
						if(isset($main_arr['Daily_every_weekday']) && $main_arr['Daily_every_weekday']!='0'){

							if($i==0){
								$display = date('Y-m-d', strtotime($start_on_date . ' +0 days'));
							} else {
								$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_week_day'].' days'));
							}
							if(strtotime($display)<=strtotime($end_by_date)){
								if(chk_company_offday_date($display,$offdays)){

								} else {
									if(strtotime($display) <= strtotime($end_by_date)){
										$start_date1[] = $display;
									}
								}
							} else {
								break;
							}
						} else if(isset($main_arr['Daily_every_day'])){

							if($i==0){
								$display = date('Y-m-d', strtotime($start_on_date . ' +0 days')); //gives after 2 days date without including saturday sunday only business days.
							} else {
								$display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_day'].' days')); //gives after 2 days date without including saturday sunday only business days.
							}

							if(strtotime($display)<=strtotime($end_by_date)){
								$start_date1[] = $display;
							} else {
								break;
							}

						} else {

							break;
						}

						$i++;
						$start_on_date = $display;
					}

					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);

					$data['end_after_recurrence'] = count($start_date1);

				} else {

					$display = '';
					if(isset($main_arr['Daily_every_weekday']) && $main_arr['Daily_every_weekday']!='0'){

						$display = chk_company_working_day_next(date('Y-m-d', strtotime($start_on_date . ' +0 days')),$offdays);

					} else if(isset($main_arr['Daily_every_day'])) {

						$display = date('Y-m-d', strtotime($start_on_date . ' +0 days')); //gives after 2 days date without including saturday sunday only business days.

					} else {
						break;
					}

					$data['start_date'] = $display;
					$data['end_date'] = '';
					$data['end_after_recurrence'] = '';
				}
			}
		} elseif($recurrence_type == '2'){
			if(isset($main_arr['no_end_date'])){
				if($main_arr['no_end_date'] == '2'){

					if(isset($main_arr['end_after_recurrence'])){
						$end_after_recurrence = $main_arr['end_after_recurrence'];
						$start_date1 = array();
						$display = '';
						$i = 0;
						if(isset($main_arr['Weekly_week_day'])){
							$Weekly_week_day_arr = $main_arr['Weekly_week_day'];
							$i = 0;
							foreach($Weekly_week_day_arr as $week){
								if($week == '1'){
									$dow   = 'Monday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '2'){
									$dow   = 'Tuesday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '3'){
									$dow   = 'Wednesday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '4'){
									$dow   = 'Thursday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '5'){
									$dow   = 'Friday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '6'){
									$dow   = 'Saturday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '7'){
									$dow   = 'Sunday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								$i++;

							}
							sort($start_date1);

						}
						$data['start_date'] = reset($start_date1);
						$data['end_date'] = end($start_date1);
						$data['end_after_recurrence'] = $end_after_recurrence;
					}
				} elseif($main_arr['no_end_date'] == '3'){

					$end_by_date = change_date_format($main_arr['end_by_date']);
					$i = 0;
						$start_date1 = array();
					if(isset($main_arr['Weekly_week_day'])){
						$Weekly_week_day_arr = $main_arr['Weekly_week_day'];

						$display = '';
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
									$start_date1[] = $display;
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
									$start_date1[] = $display;
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
									$start_date1[] = $display;
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
									$start_date1[] = $display;
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
									$start_date1[] = $display;
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
									$start_date1[] = $display;
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
									$start_date1[] = $display;
								}
							}
							$i++;
						}
						sort($start_date1);

					}
					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = count($start_date1);

				} else {
					$start_date1 = array();
					$i = 0;
					if(isset($main_arr['Weekly_week_day'])){
						$Weekly_week_day_arr = $main_arr['Weekly_week_day'];

						$start_date1 = array();
						$display = '';
						foreach($Weekly_week_day_arr as $week){
							if($week == '1'){
								$dow   = 'Monday';
								$step  = $main_arr['Weekly_every_week_no'];
								$unit  = 'W';

								$start = new DateTime($start_on_date);

								$start->modify($dow); // Move to first occurence

								$interval = new DateInterval("P{$step}{$unit}");
								$period   = new DatePeriod($start, $interval, '1');

								foreach ($period as $date) {
								    $display = $date->format('Y-m-d');
									$start_date1[] = $display;
								}

							}
							if($week == '2'){
								$dow   = 'Tuesday';
								$step  = $main_arr['Weekly_every_week_no'];
								$unit  = 'W';

								$start = new DateTime($start_on_date);


								$start->modify($dow); // Move to first occurence

								$interval = new DateInterval("P{$step}{$unit}");
								$period   = new DatePeriod($start, $interval, '1');

								foreach ($period as $date) {
								    $display = $date->format('Y-m-d');
									$start_date1[] = $display;
								}
							}
							if($week == '3'){
								$dow   = 'Wednesday';
								$step  = $main_arr['Weekly_every_week_no'];
								$unit  = 'W';

								$start = new DateTime($start_on_date);


								$start->modify($dow); // Move to first occurence

								$interval = new DateInterval("P{$step}{$unit}");
								$period   = new DatePeriod($start, $interval, '1');

								foreach ($period as $date) {
								    $display = $date->format('Y-m-d');
									$start_date1[] = $display;
								}
							}
							if($week == '4'){
								$dow   = 'Thursday';
								$step  = $main_arr['Weekly_every_week_no'];
								$unit  = 'W';

								$start = new DateTime($start_on_date);

								$start->modify($dow); // Move to first occurence

								$interval = new DateInterval("P{$step}{$unit}");
								$period   = new DatePeriod($start, $interval, '1');

								foreach ($period as $date) {
								    $display = $date->format('Y-m-d');
									$start_date1[] = $display;
								}
							}
							if($week == '5'){
								$dow   = 'Friday';
								$step  = $main_arr['Weekly_every_week_no'];
								$unit  = 'W';

								$start = new DateTime($start_on_date);

								$start->modify($dow); // Move to first occurence

								$interval = new DateInterval("P{$step}{$unit}");
								$period   = new DatePeriod($start, $interval, '1');

								foreach ($period as $date) {
								    $display = $date->format('Y-m-d');
									$start_date1[] = $display;
								}
							}
							if($week == '6'){
								$dow   = 'Saturday';
								$step  = $main_arr['Weekly_every_week_no'];
								$unit  = 'W';

								$start = new DateTime($start_on_date);

								$start->modify($dow); // Move to first occurence

								$interval = new DateInterval("P{$step}{$unit}");
								$period   = new DatePeriod($start, $interval, '1');

								foreach ($period as $date) {
								    $display = $date->format('Y-m-d');
									$start_date1[] = $display;
								}
							}
							if($week == '7'){
								$dow   = 'Sunday';
								$step  = $main_arr['Weekly_every_week_no'];
								$unit  = 'W';

								$start = new DateTime($start_on_date);

								$start->modify($dow); // Move to first occurence

								$interval = new DateInterval("P{$step}{$unit}");
								$period   = new DatePeriod($start, $interval, '1');

								foreach ($period as $date) {
								    $display = $date->format('Y-m-d');
									$start_date1[] = $display;
								}
							}
							$i++;
						}
						sort($start_date1);
					}
					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = $i;
				}
			}
		} elseif($recurrence_type == '3'){
			if(isset($main_arr['no_end_date'])){
				if($main_arr['no_end_date'] == '2'){
					$start_date1 = array();
					$display = '';
					for($i=0;$i<$main_arr['end_after_recurrence'];$i++){

						if((isset($main_arr['Monthly_op1_1']) && $main_arr['Monthly_op1_1'] != '0') && (isset($main_arr['Monthly_op1_2']) && $main_arr['Monthly_op1_2']!='0')){

							$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9

							$day = date("d",strtotime($start_on_date));
							if($Monthly_op1_1_day>=$day){
								if($i == 0){
									$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
								}
							} else {
                                                            if($i == 0){
									$effectiveDate = date('Y-m-d', strtotime("+1 months", strtotime($start_on_date)));
								} else {
                                                                    $effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
                                                                }
								
							}

							if($Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
								$display = date('Y-m-t', strtotime($effectiveDate));
							} else {
								$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
							}
							$start_date1[] = $display;

						} elseif((isset($main_arr['Monthly_op2_1']) && $main_arr['Monthly_op2_1']!='') && (isset($main_arr['Monthly_op2_2']) && $main_arr['Monthly_op2_2'] !='') && (isset($main_arr['Monthly_op2_3']) && $main_arr['Monthly_op2_3']!='0')){

							$temp_date = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.date('F Y', strtotime("+0 months", strtotime($start_on_date)))));

							if(strtotime($start_on_date)<=strtotime($temp_date)){
								if($i == 0){
									$effectiveDate = date('F Y', strtotime("+0 months", strtotime($start_on_date))); // gives month date from given date
								} else {
									$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
								}
							} else {
                                                            if($i == 0){
									$effectiveDate = date('F Y', strtotime("+1 months", strtotime($start_on_date))); // gives month date from given date
                                                                } else{
                                                                    $effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
                                                                }
                                                        
                                                      }

							$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));
							$start_date1[] = $display;


						} elseif((isset($main_arr['Monthly_op3_1']) && $main_arr['Monthly_op3_1'] != '0') && (isset($main_arr['Monthly_op3_2']) && $main_arr['Monthly_op3_2']!='0')){

							if($main_arr['Monthly_op3_1']<0){
								$start_on_date = date("Y-m-01",strtotime($start_on_date));
								if($i ==0){
									$effectiveDate = date('Y-m-t', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
								}

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
                                                                if($i ==0){
									$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
								} else {
                                                                        $effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
                                                                }

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
							$start_date1[] = $display;
						} else {
							break;
						}
						$start_on_date = $display;

					}
					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = count($start_date1);

				} elseif($main_arr['no_end_date'] == '3'){

					$end_by_date = date("Y-m-d", strtotime(str_replace(array("/"," ",","),"-",$main_arr['end_by_date'])));

					$i = 0;
					$start_date1 = array();
					$display = '';
					while (strtotime($start_on_date) <= strtotime($end_by_date)) {

						if((isset($main_arr['Monthly_op1_1']) && $main_arr['Monthly_op1_1'] != '0') && (isset($main_arr['Monthly_op1_2']) && $main_arr['Monthly_op1_2']!='0')){

							$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9

							$day = date("d",strtotime($start_on_date));
							if($Monthly_op1_1_day>=$day){
								if($i == 0){
									$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
								}
							} else {
                                                            if($i == 0){
                                                                $start_on_date = date('Y-m-d', strtotime("+1 months", strtotime($start_on_date)));
									$effectiveDate = date('Y-m-d', strtotime("+1 months", strtotime($start_on_date)));
								} else {
                                                                        $effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
                                                                }
							}

							if($Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
								$start_on_date = date("Y-m-01",strtotime($start_on_date));
								if($i == 0){
									$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
								}
								$display = date('Y-m-t', strtotime($effectiveDate));
							} else {
								if($i == 0){
									$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
								}
								$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
							}
							if(strtotime($display)<=strtotime($end_by_date)){
								$start_date1[] = $display;
							} else {
								break;
							}


						} elseif((isset($main_arr['Monthly_op2_1']) && $main_arr['Monthly_op2_1']!='') && (isset($main_arr['Monthly_op2_2']) && $main_arr['Monthly_op2_2'] !='') && (isset($main_arr['Monthly_op2_3']) && $main_arr['Monthly_op2_3']!='0')){
							$temp_date = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.date('F Y', strtotime("+0 months", strtotime($start_on_date)))));

							if(strtotime($start_on_date)<=strtotime($temp_date)){
								if($i == 0){
									$effectiveDate = date('F Y', strtotime("+0 months", strtotime($start_on_date))); // gives month date from given date
								} else {
									$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
								}
							} else {
								$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
							}
							$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));

							if(strtotime($display)<=strtotime($end_by_date)){
								$start_date1[] = $display;
							} else {
								break;
							}

						} elseif((isset($main_arr['Monthly_op3_1']) && $main_arr['Monthly_op3_1'] != '0') && (isset($main_arr['Monthly_op3_2']) && $main_arr['Monthly_op3_2']!='0')){

							if($main_arr['Monthly_op3_1']<0){
								$start_on_date = date("Y-m-01",strtotime($start_on_date));
								if($i ==0){
									$effectiveDate = date('Y-m-t', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
								}

								if($main_arr['Monthly_op3_1'] == '-1'){

								} else {
									$temp_date = date("Y-m-d",strtotime($main_arr['Monthly_op3_1']." days",strtotime($effectiveDate)));
									if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
										$effectiveDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($effectiveDate)) . " + 1 month"));
									} else {
										$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
									}
									for($a=-1;$a>$main_arr['Monthly_op3_1'];$a--){
										$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
										if(chk_company_offday_date($effectiveDate,$offdays)){
											$a++;
										}

									}
								}
								$display = chk_company_offday(date("Y-m-d",strtotime($effectiveDate)),$offdays);

							} else {
								$start_on_date = date("Y-m-01",strtotime($start_on_date));
                                                                if($i ==0){
									$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
								} else {
                                                                        $effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
                                                                }

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
							if(strtotime($display) <= strtotime($end_by_date)){
								$start_date1[] = $display;
							} else {
								break;
							}
						} else {

							break;
						}
						$i++;
						$start_on_date = $display;
					}

					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);

					$data['end_after_recurrence'] = count($start_date1);

				} else {
					$start_date1 = array();
					$display = '';
					if((isset($main_arr['Monthly_op1_1']) && $main_arr['Monthly_op1_1'] != '0') && (isset($main_arr['Monthly_op1_2']) && $main_arr['Monthly_op1_2']!='0')){

						$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9
//                                                echo $start_on_date;
                                               $day = date("d",strtotime($start_on_date));
						if($Monthly_op1_1_day>$day){
							$effectiveDate = date('Y-m-d', strtotime("+1 months", strtotime($start_on_date))); // gives month date from given date
						} else {
							$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
//                                                        $start_on_date = date('Y-m-d', strtotime("+1 months", strtotime($start_on_date))); // gives month date from given date
						}

						if($Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
                                                     if($Monthly_op1_1_day<$day){
                                                        $start_on_date = date("Y-m-01",strtotime($start_on_date));
							$effectiveDate = date('Y-m-d', strtotime("+1 months", strtotime($start_on_date))); // gives month date from given date
							$display = date('Y-m-t', strtotime($effectiveDate));
							
						} else {
                                                    $start_on_date = date("Y-m-01",strtotime($start_on_date));
							$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
                                                        $display = date('Y-m-t', strtotime($effectiveDate));
						}
							
						} else {
                                                    if($Monthly_op1_1_day<$day){
                                                        $effectiveDate = date('Y-m-d', strtotime("+1 months", strtotime($start_on_date))); // gives month date from given date
							
						} else {
							$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));

						}
							$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
						}
					} elseif((isset($main_arr['Monthly_op2_1']) && $main_arr['Monthly_op2_1']!='') && (isset($main_arr['Monthly_op2_2']) && $main_arr['Monthly_op2_2'] !='') && (isset($main_arr['Monthly_op2_3']) && $main_arr['Monthly_op2_3']!='0')){

						$temp_date = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.date('F Y',  strtotime($start_on_date))));

						if(strtotime($start_on_date)<strtotime($temp_date)){
							$effectiveDate = date('F Y', strtotime("+0 months", strtotime($start_on_date))); // gives month date from given date

						} else {
							$effectiveDate = date('F Y', strtotime("+1 months", strtotime($start_on_date))); // gives month date from given date
						}

						$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));


					} elseif((isset($main_arr['Monthly_op3_1']) && $main_arr['Monthly_op3_1'] != '0') && (isset($main_arr['Monthly_op3_2']) && $main_arr['Monthly_op3_2']!='0')){
						if($main_arr['Monthly_op3_1']<0){
							$start_on_date = date("Y-m-01",strtotime($start_on_date));
							$effectiveDate = date('Y-m-t', strtotime("+0 months", strtotime($start_on_date)));

							if($main_arr['Monthly_op3_1'] == '-1'){

							} else {
								$temp_date = date("Y-m-d",strtotime($effectiveDate));
								if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
									$effectiveDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($effectiveDate)) . " + 1 month"));
								} else {
									$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
								}
								for($a=-1;$a>$main_arr['Monthly_op3_1'];$a--){
									$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
									if(chk_company_offday_date($effectiveDate,$offdays)){
										$a++;
									}

								}
							}
							$display = chk_company_offday(date("Y-m-d",strtotime($effectiveDate)),$offdays);

						} else {
							$start_on_date = date("Y-m-01",strtotime($start_on_date));
							$effectiveDate = date('Y-m-d', strtotime($start_on_date));

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

					} else {
						break;
					}
					$data['start_date'] = $display;
					$data['end_date'] = '';
					$data['end_after_recurrence'] = '';
				}
			}
		} elseif($recurrence_type == '4'){
			if(isset($main_arr['no_end_date'])){
				if($main_arr['no_end_date'] == '2'){
					$start_date1 = array();
					$display = '';

					for($i=0;$i<$main_arr['end_after_recurrence'];$i++){
						if(isset($main_arr['Yearly_op1']) && $main_arr['Yearly_op1']!='0'){

							if($i==0){
								$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " +0 year"));
							} else {
								$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + ".$main_arr['Yearly_op1']." year"));
							}

							$start_date1[$i] = $display;

						} elseif((isset($main_arr['Yearly_op2_1']) && $main_arr['Yearly_op2_1']!='0') && (isset($main_arr['Yearly_op2_2']) && $main_arr['Yearly_op2_2']!='0')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));

							if($i==0){
								if($year >= date('Y')){
									if($main_arr['Yearly_op2_1'] > $month){
										$year = $year;
									} elseif($main_arr['Yearly_op2_1'] = $month){
										if($main_arr['Yearly_op2_2'] >= $day){
											$year = $year;
										} else {
											$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
										}
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}

								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}


							$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));
							$start_date1[$i] = $display;


						} elseif((isset($main_arr['Yearly_op3_1']) && $main_arr['Yearly_op3_1']!='') && (isset($main_arr['Yearly_op3_2']) && $main_arr['Yearly_op3_2']!='') && (isset($main_arr['Yearly_op3_3'])&&$main_arr['Yearly_op3_3']!='')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));
							$temp_date = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));

							if(strtotime($temp_date)>=strtotime(date("Y-m-d"))){
								if($i==0){
									if($year >= date('Y')){
										if(date('m', strtotime($main_arr['Yearly_op3_3'])) >= $month){
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
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}


							$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
							$start_date1[$i] = $display;

						} elseif((isset($main_arr['Yearly_op4_1']) && $main_arr['Yearly_op4_1']!='0') && (isset($main_arr['Yearly_op4_2']) && $main_arr['Yearly_op4_2']!='')){

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
									$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));

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
							$start_date1[] = $display;

						} else {
							break;
						}

						$start_on_date = $display;//date("Y-m-d", strtotime(date("Y-m-d", strtotime($display)) . " + 1 year"));


					}

					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = count($start_date1);

				} elseif($main_arr['no_end_date'] == '3'){

					$end_by_date = change_date_format($main_arr['end_by_date']);

					$i = 0;
					$start_date1 = array();
					$display = '';
					while (strtotime($start_on_date) <= strtotime($end_by_date)) {

						$display = '';
						if(isset($main_arr['Yearly_op1']) && $main_arr['Yearly_op1']!='0'){

							$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + ".$main_arr['Yearly_op1']." year"));
							if(strtotime($display) <= strtotime($end_by_date)){
								$start_date1[] = $display;
							} else {
								break;
							}

						} elseif((isset($main_arr['Yearly_op2_1']) && $main_arr['Yearly_op2_1']!='0') && (isset($main_arr['Yearly_op2_2']) && $main_arr['Yearly_op2_2']!='0')){

							if($i == '0'){
								$year = date('Y',strtotime($start_on_date));
								$month = date('m',strtotime($start_on_date));
								$day = date('d',strtotime($start_on_date));

								if($year >= date('Y')){
									if($main_arr['Yearly_op2_1'] > $month){
										$year = $year;
									} elseif($main_arr['Yearly_op2_1'] = $month){
										if($main_arr['Yearly_op2_2'] >= $day){
											$year = $year;
										} else {
											$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
										}
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}

								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}

							$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));
							if(strtotime($display) <= strtotime($end_by_date)){
								$start_date1[] = $display;
							} else {
								break;
							}


						} elseif((isset($main_arr['Yearly_op3_1']) && $main_arr['Yearly_op3_1']!='') && (isset($main_arr['Yearly_op3_2']) && $main_arr['Yearly_op3_2']!='') && (isset($main_arr['Yearly_op3_3'])&&$main_arr['Yearly_op3_3']!='')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));
							$temp_date = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));

							if(strtotime($temp_date)>=strtotime(date("Y-m-d"))){
								if($i == '0'){
									if($year >= date('Y')){
										if(date('m', strtotime($main_arr['Yearly_op3_3'])) >= $month){
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
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}

							$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
							if(strtotime($display) <= strtotime($end_by_date)){
								$start_date1[] = $display;
							} else {
								break;
							}
						} elseif((isset($main_arr['Yearly_op4_1']) && $main_arr['Yearly_op4_1']!='0') && (isset($main_arr['Yearly_op4_2']) && $main_arr['Yearly_op4_2']!='')){

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
										$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
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
								if(strtotime($display) <= strtotime($end_by_date)){
									$start_date1[] = $display;
								}


						} else {
							break;
						}
						$start_on_date = $display;
						$i++;
					}
					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = count($start_date1);

				} else {
					$start_date1 = array();
					$display = '';
					if(isset($main_arr['Yearly_op1']) && $main_arr['Yearly_op1']!='0'){

						$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 0 year"));

					} elseif((isset($main_arr['Yearly_op2_1']) && $main_arr['Yearly_op2_1']!='0') && (isset($main_arr['Yearly_op2_2']) && $main_arr['Yearly_op2_2']!='0')){

						$year = date('Y',strtotime($start_on_date));
						$month = date('m',strtotime($start_on_date));
						$day = date('d',strtotime($start_on_date));

						if($year >= date('Y')){
							if($main_arr['Yearly_op2_1'] > $month){
								$year = $year;
							} elseif($main_arr['Yearly_op2_1'] = $month){
								if($main_arr['Yearly_op2_2'] >= $day){
									$year = $year;
								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
							}

						} else {
							$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
						}

						$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));


					} elseif((isset($main_arr['Yearly_op3_1']) && $main_arr['Yearly_op3_1']!='') && (isset($main_arr['Yearly_op3_2']) && $main_arr['Yearly_op3_2']!='') && (isset($main_arr['Yearly_op3_3'])&&$main_arr['Yearly_op3_3']!='')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));
							$temp_date = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));

							if(strtotime($temp_date)>=strtotime(date("Y-m-d"))){
								if($year >= date('Y')){
									if(date('m', strtotime($main_arr['Yearly_op3_3'])) >= $month){
										$year = $year;

									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}

								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
							}


							$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));



					} elseif((isset($main_arr['Yearly_op4_1']) && $main_arr['Yearly_op4_1']!='0') && (isset($main_arr['Yearly_op4_2']) && $main_arr['Yearly_op4_2']!='')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));

							if($main_arr['Yearly_op4_1']<0){
								if($year >= date('Y')){
									if(date('m', strtotime($main_arr['Yearly_op4_2'])) >= $month){
										$year = $year;
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
								} else {
									$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
								}
								$monthyear = date('Y-m-01',strtotime($main_arr['Yearly_op4_2']." ".$year));

								if($main_arr['Yearly_op4_1'] == '-1'){

								} else {
									$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
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
										$year = $year;

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
					} else {
						break;
					}
					$data['start_date'] = $display;
					$data['end_date'] = '';
					$data['end_after_recurrence'] = '';
				}
			}
		} else {
			$data['start_date'] = '';
			$data['end_date'] = '';
			$data['end_after_recurrence'] = '';
		}

		return $data;
	}

	/*
	 * Function : get_project_sections
	 * Author : Spaculus
	 * Desc : This function is used to get list of project section by id.
	 */
	function get_project_sections(){
		$project_id = $_POST['project_id'];
		$data['section_id'] = isset($_POST['section_id'])?$_POST['section_id']:'';
		$theme = getThemeName();
		$data['theme'] = $theme;
		$data['project_id'] = $project_id;
		if($project_id){
			$data['sections'] = $this->task_model->get_project_sections_by_id($project_id);
		} else {
			$data['sections'] = '';
		}


		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
			{
				$theme = getThemeName ();

				$this->template->set_master_template($theme .'/template_mobile.php');
				$this->load->view($theme.'/mobileview/tasks/ajax_project_section_div',$data);
			}else{
				
				$data['site_setting_date'] = $this->config->item('company_default_format');

				$data['project_id'] = $project_id;
				if($project_id){
					$data['users'] = get_project_user_list($project_id);
				} else {
					$data['users'] = get_user_list();
				}
                                $data['is_external_user'] = $this->session->userdata('is_customer_user');
				echo json_encode($data);die;
			}
	}

	/************** Timer ********************/
	/*
	 * Function : get_task_spent_time
	 * Author : Spaculus
	 * Desc : This function is used to get task spent time.
	 */
/**
         * This function is used to get task spent time.
         * @param int $task_id
         * @return int
         */
	function get_task_spent_time($task_id){
		$query = $this->db->select('task_time_spent')->from('tasks')->where('task_id',$task_id)->where('task_owner_id != ','0')->where('task_allocated_user_id != ','0')->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_time_spent;
		} else {
			return 0;
		}
	}

	/*
	 * Function : get_task_estimate_time
	 * Author : Spaculus
	 * Desc : This function is used to get task estimate time.
	 */
/**
         * This function is used to get task estimate time.
         * @param int $task_id
         * @return int
         */
	function get_task_estimate_time($task_id){
		$query = $this->db->select('task_time_estimate')->from('tasks')->where('task_owner_id != ','0')->where('task_allocated_user_id != ','0')->where('task_id',$task_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_time_estimate;
		} else {
			return 0;
		}
	}

	/*
	 * Function : save_time
	 * Author : Spaculus
	 * Desc : This function is used to save time of task from timer
	 */
	function save_time(){
		$interruption = '';
                if(isset($_POST['interruption'])){
			$interruption = json_decode($_POST['interruption']);
                }
                $comment = isset($_POST['timer_comment'])?$_POST['timer_comment']:'';
              //  echo $comment; die();
		$from = '';
		if(isset($_POST['name'])){
			$from = $_POST['name'];
		}
		$task_id = $_POST['task_id'];
		$str_time = date('H:i:s',strtotime(str_replace(array("/"," ",","), "-", $_POST['time'])));

		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
		$time_minutes = isset($minutes) ? $hours * 60 + $minutes : $hours * 60;
		$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 3600 + $minutes * 60;

		$past_spent_time = $this->get_task_spent_time($task_id);
		$total_spent_time = $past_spent_time + $time_minutes;

		if($from == 'completed'){
			$interruption = "Task Completed";
			$task_status_completed_id = $this->config->item('completed_id');

			$old_status_id = get_taskStatus_id($task_id);

			$old_task_status_name = get_task_status_name_by_id($old_status_id);
			$new_task_status_name = "Completed";

			$update_data = array('task_status_id'=>$task_status_completed_id, 'task_completion_date'=>date('Y-m-d H:i:s'), 'task_time_spent' => $total_spent_time);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$update_data);
                        
                        if($this->session->userdata('pricing_module_status')=='1'){ 
                            $task_details =  get_task_detail($task_id);
                            $actual_time = get_task_actual_time($task_id);
                            $estimated_time = get_task_estimated_time($task_id);
                            $task_charge_out_rate = get_task_charge_out_rate($task_id);
                            $rate = get_user_cost_per_hour($task_details['task_allocated_user_id']);
                            if($estimated_time == '0'){
                                $charge_out_rate = get_charge_out_rate($task_id);
                                $data2 = array(
                                            "cost_per_hour"=>$rate,
                                            "charge_out_rate"=>$charge_out_rate,
                                            "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2)
                                        );
                            }else{
                                $data2 = array(
                                    "actual_total_charge"=>round(($task_charge_out_rate*$actual_time)/60,2)
                                    );
                            }
                            $this->db->where('task_id',$task_id);
                            $this->db->update('tasks',$data2);
                        }
                        
                        
			if($old_status_id != $task_status_completed_id){
				$history_data = array(
					'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
			}
			//insert in timer
			$timer_data = array(
				'task_id' => $task_id,
				'user_id' => get_authenticateUserID(),
				'spent_time' => $str_time,
				'interruption' => $interruption,
				'date_added' => date('Y-m-d H:i:s'),
                                'comment' => $comment
			);
			$this->db->insert('task_timer_logs',$timer_data);

			// email
			$task_data = get_task_detail($task_id);
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

		} else {
			$task_data = array(
				'task_time_spent' => $total_spent_time,
                                'billed_time'=> $total_spent_time
			);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$task_data);
                        
                        if($this->session->userdata('pricing_module_status')=='1'){ 
                            $task_details =  get_task_detail($task_id);
                            $actual_time = get_task_actual_time($task_id);
                            $estimated_time = get_task_estimated_time($task_id);
                            $task_charge_out_rate = get_task_charge_out_rate($task_id);
                            $rate = get_user_cost_per_hour($task_details['task_allocated_user_id']);
                            if($estimated_time == '0'){
                                $charge_out_rate = get_charge_out_rate($task_id);
                                $data2 = array(
                                            "cost_per_hour"=>$rate,
                                            "charge_out_rate"=>$charge_out_rate,
                                            "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2)
                                        );
                            }else{
                                $data2 = array(
                                    "actual_total_charge"=>round(($task_charge_out_rate*$actual_time)/60,2)
                                    );
                            }
                            $this->db->where('task_id',$task_id);
                            $this->db->update('tasks',$data2);
                        }
                
			//insert in timer
			$timer_data = array(
				'task_id' => $task_id,
				'user_id' => get_authenticateUserID(),
				'spent_time' => $str_time,
				'interruption' => $interruption,
				'date_added' => date('Y-m-d H:i:s'),
                                'comment' => $comment
			);
			$this->db->insert('task_timer_logs',$timer_data);
		}
		$data['interruptions'] = count_today_interruptions();
		$data['total_spent_time'] = hour_minute_formate($this->get_task_estimate_time($task_id),$total_spent_time);
		$data['total_timer_time'] = $total_spent_time;
		echo json_encode($data);
		die;
	}

	/*
	 * Function : total_task_time
	 * Author : Spaculus
	 * Desc : This function is used to get total task spent time from timer log
	 */
	function total_task_time(){
		$task_id = $_POST['task_id'];
		$data = array();
		$query = $this->db->select('spent_time')->from('task_timer_logs')->where('task_id',$task_id)->where('user_id',get_authenticateUserID())->get();
		if($query->num_rows()>0){
			$res = $query->result();

			if($res){
				$sum = 0;
				foreach($res as $row){
					$sum += $row->spent_time;
				}
				if($sum){
					$hours = intval($sum/60);
					$minutes = $sum - ($hours * 60);
					$time = $hours.':'.$minutes.':00';
					$data['seconds'] = $sum;
					$data['time'] = $time;
				} else {
					$data['seconds'] = '0';
					$data['time'] = '00:00:00';
				}
			}
		} else {
			$data['seconds'] = '0';
			$data['time'] = '00:00:00';
		}
		echo json_encode($data);die;
	}

	/*
	 * Function : spent_time
	 * Author : Spaculus
	 * Desc : This function is used to get task spent time
	 */
	function spent_time(){
		$task_id = $_POST['task_id'];
		$query = $this->db->select('task_time_spent')->from('tasks')->where('task_owner_id != ','0')->where('task_allocated_user_id != ','0')->where('task_id',$task_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			echo $res->task_time_spent;die;

		} else {
			echo 0;die;
		}
	}

	/*
	 * Function : project_users
	 * Author : Spaculus
	 * Desc : This function is used to get project users listing
	 */
/**
         * This function is used to get project users listing
         * @returns void
         */
	function project_users(){
		$project_id = $_POST['project_id'];

		$theme = getThemeName();

		$data['site_setting_date'] = $this->config->item('company_default_format');

		$data['project_id'] = $project_id;
		$data['users'] = get_project_user_list($project_id);

		$this->load->view($theme.'/layout/task/ajax_allocated_users',$data);
	}

	/*
	 * Function : get_user_work_log
	 * Author : Spaculus
	 * Desc : This function is used to get work log of user for ajax request
	 */
/**
         * This function is used to get work log of user for ajax request 
         * @returns void
         */
	function get_user_work_log(){

		$theme = getThemeName();

		$data = array();
                if(isset($_POST['from_date']))
                    $data['from_date']= change_date_format($_POST['from_date']);
                if(isset($_POST['to_date']))
                    $data['to_date']= change_date_format($_POST['to_date']);

		$this->load->view($theme.'/layout/common/ajax_work_log',$data);
	}

	/*
	 * Function : get_statistics
	 * Author : Spaculus
	 * Desc : This function is used to get statistics
	 */
/**
         * This function is used to get statistics
         * @returns void 
         */
	function get_statistics(){
		$theme = getThemeName();

		$data = array();

		$this->load->view($theme.'/layout/common/ajax_statistics',$data);
	}

	/*
	 * Function : get_department_by_division
	 * Author : Spaculus
	 * Desc : This function is used to get department by division
	 */
	function get_department_by_division()
	{
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$division_ids = $_POST['division_id'];

		if(isset($_POST['dept_ids']) && $_POST['dept_ids']!='0'){
			$dept_ids = $_POST['dept_ids'];
		} else {
			$dept_ids = array();
		}
		if($division_ids){
			$data['departments'] = getUserDepartmentByDivision($division_ids);
		} else {
			$data['departments'] = '';
		}
		
		$data['dept_ids'] = $dept_ids;
		$this->load->view($theme.'/layout/task/ajax_department',$data);
	}
	
	
	function setDivisionDepartment(){
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$data['divisions'] = getUserDivision($user_id);
		$this->load->view($theme.'/layout/task/ajax_divisions',$data);
	}

	// task related functionality for mobile website
	/*
	 * Function : view_task
	 * Author : Spaculus
	 * Desc : This function is used for mobile site task list view data
	 */
    /**
     * This function is used for mobile site task list view data.
     * @param int $task_id
     * @returns void
     */
	function view_task($task_id)
	{
		//$data['task_id'] = base64_decode($task_id);
		if (!check_user_authentication()) {
			redirect ('home');
		}

		//echo $task_id;die;
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');

		$data = array();
		$data['msg']="";
		$data['site_setting_date'] = $this->config->item('company_default_format');


		$data['user'] = get_user_info(get_authenticateUserID());

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');

			$data['taskDetail'] = $this->task_model->get_TaskDetailByID(base64_decode($task_id));
			$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
			$data['comments'] =get_task_comments(base64_decode($task_id));
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/tasks/view_task',$data,TRUE);
			$this->template->render();
		}
	}

	/*
	 * Function : edit_task
	 * Author : Spaculus
	 * Desc : This function is used for mobile site for edit task
	 */
	function edit_task($task_id="")
	{
		$data['task_id'] = base64_decode($task_id);
		$data['msg']='';
		$data['error']='';

		$task = gettaskbyid($data['task_id']);
		
		if($task ==''){
			redirect ('home');
		}

		$data['task_title'] = $task['task_title'];
		$data['task_description'] = $task['task_description'];
		$data['task_priority'] = $task['task_priority'];
		$data['task_category_id'] = $task['task_category_id'];
		$data['task_sub_category_id'] = $task['task_sub_category_id'];
		$data['task_due_date'] = $task['task_due_date'];
		$data['task_allocated_user_id'] = $task['task_allocated_user_id'];
		$data['task_owner_id'] = $task['task_owner_id'];
		$data['task_status_id'] = $task['task_status_id'];
		$data['default_color'] = get_default_color($this->session->userdata('user_id'));
		$data['color_id'] = $task['color_id'];

		$total_task_time_estimate_minute = $task['task_time_estimate'];
		$estimate_hours = intval($total_task_time_estimate_minute/60);
		$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
		$data['task_time_estimate_hour'] = $estimate_hours;
		$data['task_time_estimate_min'] = $estimate_minutes;
		$data['task_time_estimate'] = minutesToTime($total_task_time_estimate_minute);
		
		$total_task_time_spent_minute = $task['task_time_spent'];
		$spent_hours = intval($total_task_time_spent_minute/60);
		$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
		$data['task_time_spent_hour'] = $spent_hours;
		$data['task_time_spent_min'] = $spent_minutes;
		$data['task_time_spent'] = minutesToTime($total_task_time_spent_minute);

		$data['task_id'] = $task['task_id'];
		$data['is_personal'] = $task['is_personal'];
		$data['locked_due_date'] = $task['locked_due_date'];
		$data['task_allocated_user_id'] = $task['task_allocated_user_id'];

		$data['task_skill_id'] =$task['task_skill_id'];
		$data['task_project_id'] = $task['task_project_id'];
		$data['project_id'] = $task['task_project_id'];
		$data['section_id'] = $task['subsection_id'];
		$data['redirect_page'] = 'from_project';

		$data['user'] = get_user_info(get_authenticateUserID());
		$data['members'] = $this->project_model->get_project_members($data['task_project_id']);
		$data['member_lst'] = get_memberList($data['task_project_id']);
		$data['users_list'] = get_company_users();
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['task_project_id']);
		$data['section'] = $this->project_model->get_project_section($data['task_project_id']);
		$data['priority'] = taskPriority();
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));

		$theme = getThemeName ();

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');

			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/tasks/edit_task',$data,TRUE);
			$this->template->render();
		}
	}

	/*
	 * Function : add_task
	 * Author : Spaculus
	 * Desc : This function is used for mobile site for add task
	 */
	/**
         * This function check user is authenticated or not than it apply validation and save task in db for moblie site.
         * @param type $id
         * @returns void
         */
	function add_task($id="")
	{
		//pr($_POST);die;
                    /*
                     * check authentication
                     */
		

		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');

		$data = array();

		$data['site_setting_date'] = $this->config->item('company_default_format');
		
		$data['project_id'] = base64_decode($id);
		$data['msg']='';
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('task_title','Project Title','required');
		if($_POST){

                    /*
                     * check form validation rules 
                     */
			if($this->form_validation->run() == FALSE){

				if(validation_errors()){
					 $data['error'] = validation_errors();
				} else {
					$data['error'] = '';
				}

				$data['task_title'] = $this->input->post('task_title');
				$data['task_description'] = $this->input->post('task_description');
				$data['task_priority'] = $this->input->post('task_priority');
				$data['task_category_id'] = $this->input->post('task_category_id');
				$data['task_sub_category_id'] = $this->input->post('task_sub_category_id');
				$data['task_due_date'] = $this->input->post('task_due_date');
				$data['task_scheduled_date'] = $this->input->post('task_scheduled_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
				$data['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');
				$data['task_time_estimate'] = $this->input->post('task_time_estimate');
				$data['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
				$data['task_time_spent_min'] = $this->input->post('task_time_spent_min');
				$data['task_time_spent'] = $this->input->post('task_time_spent');

				$data['task_id'] = $this->input->post('task_id');
				$data['is_personal'] = $this->input->post('hdn_is_personal');
				$data['locked_due_date'] = $this->input->post('hdn_locked_due_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_owner_id'] = $this->input->post('task_owner_id');
				$data['task_status_id'] = $this->input->post('task_status_id');
				$data['default_color'] = get_default_color($this->session->userdata('user_id'));
				$data['color_id'] = '0';


				$data['task_skill_id'] =$this->input->post('task_skill_id');
				$data['task_project_id'] = $this->input->post('task_project_id');
				$data['section_id'] = $this->input->post('section_id');

				$data['user'] = get_user_info(get_authenticateUserID());
				$data['members'] = $this->project_model->get_project_members($data['project_id']);
				$data['member_lst'] = get_memberList($data['project_id']);
				$data['users_list'] = get_company_users();
				$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
				$data['section'] = $this->project_model->get_project_section($data['project_id']);
				$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));

				if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
				{
					$this->template->set_master_template($theme .'/template_mobile.php');

					$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
					$this->template->write_view('center',$theme .'/mobileview/tasks/edit_task',$data,TRUE);
					$this->template->render();
				}

			} else {

				if($this->input->post('task_id') != ''){

					$res = $this->task_model->update_task();
					$msg = "update";
					$this->session->set_flashdata('msg', 'update');
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('task/view_task/'.base64_encode($res));
					}
				} else {
					$res = $this->task_model->insert_task();
					$msg = "insert";
					$this->session->set_flashdata('msg', 'update');
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('task/view_task/'.base64_encode($res));
					}
				}
			}
		}else{
			
				$data['task_id'] = '';
				$data['task_title'] = $this->input->post('task_title');
				$data['task_description'] = $this->input->post('task_description');
				$data['task_priority'] = $this->input->post('task_priority');
				$data['task_category_id'] = $this->input->post('task_category_id');
				$data['task_sub_category_id'] = $this->input->post('task_sub_category_id');
				$data['task_due_date'] = $this->input->post('task_due_date');
				$data['task_scheduled_date'] = $this->input->post('task_scheduled_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
				$data['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');
				$data['task_time_estimate'] = $this->input->post('task_time_estimate');
				$data['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
				$data['task_time_spent_min'] = $this->input->post('task_time_spent_min');
				$data['task_time_spent'] = $this->input->post('task_time_spent');

				$data['is_personal'] = $this->input->post('hdn_is_personal');
				$data['locked_due_date'] = $this->input->post('hdn_locked_due_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_owner_id'] = $this->input->post('task_owner_id');
				$data['task_status_id'] = $this->input->post('task_status_id');
				$data['default_color'] = get_default_color($this->session->userdata('user_id'));
				$data['color_id'] = '0';


				$data['task_skill_id'] =$this->input->post('task_skill_id');
				$data['task_project_id'] = $this->input->post('task_project_id');
				$data['section_id'] = $this->input->post('section_id');

				$data['user'] = get_user_info(get_authenticateUserID());
				$data['members'] = $this->project_model->get_project_members($data['project_id']);
				$data['member_lst'] = get_memberList($data['project_id']);
				$data['users_list'] = get_company_users();
				$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
				$data['section'] = $this->project_model->get_project_section($data['project_id']);
				$data['priority'] = taskPriority();
				$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
				$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
				$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));


				$data['msg']='';
				$data['error'] = '';

				if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
				{
					$this->template->set_master_template($theme .'/template_mobile.php');

					$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
					$this->template->write_view('center',$theme .'/mobileview/tasks/edit_task',$data,TRUE);
					$this->template->render();
				}
		}

	}

	/*
	 * Function : edit_ind_task
	 * Author : Spaculus
	 * Desc : This function is used for mobile site for edit task
	 */
	/**
         * This function is used for mobile site and it work on individual id for task edit.
         * @param type $task_id
         * @returns void
         */
	function edit_ind_task($task_id="")
	{
		if(get_task_title(base64_decode($task_id))=='0'){
			redirect ('home');
		}

		$data['task_id'] = base64_decode($task_id);
		$data['msg']='';
		$data['error']='';

		$task = gettaskbyid($data['task_id']);
		$data['task_title'] = $task['task_title'];
		$data['task_description'] = $task['task_description'];
		$data['task_priority'] = $task['task_priority'];
		$data['task_category_id'] = $task['task_category_id'];
		$data['task_sub_category_id'] = $task['task_sub_category_id'];
		$data['task_due_date'] = $task['task_due_date'];
		$data['task_allocated_user_id'] = $task['task_allocated_user_id'];
		$data['task_owner_id'] = $task['task_owner_id'];
		$data['task_status_id'] = $task['task_status_id'];
		$data['default_color'] = get_default_color($this->session->userdata('user_id'));
		$data['color_id'] = $task['color_id'];

		$total_task_time_estimate_minute = $task['task_time_estimate'];
		$estimate_hours = intval($total_task_time_estimate_minute/60);
		$estimate_minutes = $total_task_time_estimate_minute - ($estimate_hours * 60);
		$data['task_time_estimate_hour'] = $estimate_hours;
		$data['task_time_estimate_min'] = $estimate_minutes;
		$data['task_time_estimate'] = minutesToTime($total_task_time_estimate_minute);
		
		$total_task_time_spent_minute = $task['task_time_spent'];
		$spent_hours = intval($total_task_time_spent_minute/60);
		$spent_minutes = $total_task_time_spent_minute - ($spent_hours * 60);
		$data['task_time_spent_hour'] = $spent_hours;
		$data['task_time_spent_min'] = $spent_minutes;
		$data['task_time_spent'] = minutesToTime($total_task_time_spent_minute);

		$data['task_id'] = $task['task_id'];
		$data['is_personal'] = $task['is_personal'];
		$data['locked_due_date'] = $task['locked_due_date'];
		$data['task_allocated_user_id'] = $task['task_allocated_user_id'];

		$data['task_skill_id'] =$task['task_skill_id'];
		$data['task_project_id'] = $task['task_project_id'];
		$data['project_id'] = $task['task_project_id'];
		$data['section_id'] = $task['subsection_id'];
		$data['redirect_page'] = 'from_project';

		$data['user'] = get_user_info(get_authenticateUserID());
		$data['members'] = $this->project_model->get_project_members($data['task_project_id']);
		$data['member_lst'] = get_memberList($data['task_project_id']);
		$data['users_list'] = get_company_users();
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['task_project_id']);
		$data['section'] = $this->project_model->get_project_section($data['task_project_id']);
		$data['priority'] = taskPriority();
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));


		$theme = getThemeName ();

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');

			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/tasks/edit_ind_task',$data,TRUE);
			$this->template->render();
		}
	}
       

	/*
	 * Function : add_ind_task
	 * Author : Spaculus
	 * Desc : This function is used for mobile site for add task
	 */
	function add_ind_task()
	{
		$data['msg']='';

		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');

		$data = array();

		$data['site_setting_date'] = $this->config->item('company_default_format');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('task_title','Project Title','required');
		if($_POST){

			if($this->form_validation->run() == FALSE){

				if(validation_errors()){
					 $data['error'] = validation_errors();
				} else {
					$data['error'] = '';
				}

				$data['task_title'] = $this->input->post('task_title');
				$data['task_description'] = $this->input->post('task_description');
				$data['task_priority'] = $this->input->post('task_priority');
				$data['task_category_id'] = $this->input->post('task_category_id');
				$data['task_sub_category_id'] = $this->input->post('task_sub_category_id');
				$data['task_due_date'] = $this->input->post('task_due_date');
				$data['task_scheduled_date'] = $this->input->post('task_scheduled_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
				$data['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');
				$data['task_time_estimate'] = $this->input->post('task_time_estimate');
				$data['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
				$data['task_time_spent_min'] = $this->input->post('task_time_spent_min');
				$data['task_time_spent'] = $this->input->post('task_time_spent');

				$data['task_id'] = $this->input->post('task_id');
				$data['is_personal'] = $this->input->post('hdn_is_personal');
				$data['locked_due_date'] = $this->input->post('hdn_locked_due_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_owner_id'] = $this->input->post('task_owner_id');
				$data['task_status_id'] = $this->input->post('task_status_id');
				$data['default_color'] = get_default_color($this->session->userdata('user_id'));
				$data['color_id'] = '0';


				$data['task_skill_id'] =$this->input->post('task_skill_id');
				$data['task_project_id'] = $this->input->post('task_project_id');
				$data['section_id'] = $this->input->post('section_id');

				$data['user'] = get_user_info(get_authenticateUserID());
				$data['users_list'] = get_company_users();
				$data['user_projects'] = get_user_projects(get_authenticateUserID());
				$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));

				if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
				{
					$this->template->set_master_template($theme .'/template_mobile.php');

					$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
					$this->template->write_view('center',$theme .'/mobileview/tasks/edit_ind_task',$data,TRUE);
					$this->template->render();
				}

			} else {


				if($this->input->post('task_id') != ''){

					$res = $this->task_model->update_task();
					$msg = "update";
					$this->session->set_flashdata('msg', 'update');
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('task/view_task/'.base64_encode($res));
					}
				} else {
					$res = $this->task_model->insert_task();
					$msg = "insert";
					$this->session->set_flashdata('msg', 'insert');
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('task/view_task/'.base64_encode($res));
					}
				}
			}
		}else{
				$data['task_id'] = '';
				$data['task_title'] = $this->input->post('task_title');
				$data['task_description'] = $this->input->post('task_description');
				$data['task_priority'] = $this->input->post('task_priority');
				$data['task_category_id'] = $this->input->post('task_category_id');
				$data['task_sub_category_id'] = $this->input->post('task_sub_category_id');
				$data['task_due_date'] = $this->input->post('task_due_date');
				$data['task_scheduled_date'] = $this->input->post('task_scheduled_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
				$data['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');
				$data['task_time_estimate'] = $this->input->post('task_time_estimate');
				$data['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
				$data['task_time_spent_min'] = $this->input->post('task_time_spent_min');
				$data['task_time_spent'] = $this->input->post('task_time_spent');

				$data['is_personal'] = $this->input->post('hdn_is_personal');
				$data['locked_due_date'] = $this->input->post('hdn_locked_due_date');
				$data['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');
				$data['task_owner_id'] = $this->input->post('task_owner_id');
				$data['task_status_id'] = $this->input->post('task_status_id');
				$data['default_color'] = get_default_color($this->session->userdata('user_id'));
				$data['color_id'] = '0';


				$data['task_skill_id'] =$this->input->post('task_skill_id');
				$data['task_project_id'] = $this->input->post('task_project_id');
				$data['section_id'] = $this->input->post('section_id');

				$data['user'] = get_user_info(get_authenticateUserID());
				$data['users_list'] = get_company_users();
				$data['priority'] = taskPriority();
				$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
				$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
				$data['user_projects'] = get_user_projects(get_authenticateUserID());
				$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));

				$data['msg']='';
				$data['error'] = '';


				if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
				{
					$this->template->set_master_template($theme .'/template_mobile.php');

					$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
					$this->template->write_view('center',$theme .'/mobileview/tasks/edit_ind_task',$data,TRUE);
					$this->template->render();
				}
			}
		}
	/*
	 * Function : kanban
	 * Author : Spaculus
	 * Desc : This function is used for mobile site for kanban view with tasks
	 */
	function kanban($msg="")
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = "";
			$limit      = '30';
        	$offset     = '0';
			$task_status_id = $task_status_completed_id = get_task_status_id_by_name('Ready');
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$data['task_kanban'] = get_kanbanTasks($task_status_id,$limit, $offset);
			$data['task_status_id'] = $task_status_id;

			$this->template->set_master_template($theme .'/template_mobile.php');

			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/tasks/kanban',$data,TRUE);
			$this->template->render();
		}
	}


	/*
	 * Function : filterKanban
	 * Author : Spaculus
	 * Desc : This function is used for mobile site for kanban view filter
	 */
	function filterKanban()
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		$data['msg'] = "";
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$task_status = $_POST['id'];

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = "";
			$limit      = '30';
        	$offset     = $this->input->post('offset');
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$data['task_kanban'] = get_kanbanTasks($task_status,$limit, $offset);
			$data['task_status_id'] = $task_status;

			$this->template->set_master_template($theme .'/template_mobile.php');

			$this->load->view($theme.'/mobileview/tasks/Ajax_kanban',$data);

		}
	}
	
	function completeTask()
	{
		
		$status = $_POST['status'];
		$task_id = $_POST['id'];
		
		$theme = getThemeName ();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$task_status_completed_id = $this->config->item('completed_id');
		$task_status_ready_id = get_task_status_id_by_name('Ready');
		
		if($status==$task_status_completed_id){
			
			$data_status = array('task_status_id'=>$task_status_ready_id);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data_status);
		}else{
			$data_status = array('task_status_id'=>$task_status_completed_id);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data_status);
		}
		
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = "";
			$limit      = '30';
        	$offset     = $this->input->post('offset');
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$data['task_kanban'] = get_kanbanTasks($status,$limit, $offset);
			$data['task_status_id'] = $status;

			$this->template->set_master_template($theme .'/template_mobile.php');

			$this->load->view($theme.'/mobileview/tasks/Ajax_kanban',$data);
		}
	}

	/*
	 * Function : AjaxKanban
	 * Author : Spaculus
	 * Desc : This function is used for mobile site for ajax request kanban view with tasks
	 */
	/**
         * This function is used for mobile site.it show kanban via ajax request.
         * @returns void
         */
	function AjaxKanban()
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		$data['msg'] = "";
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$task_status = $_POST['id'];

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = "";
			$limit      = '30';
        	$offset     = $this->input->post('offset');
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$data['task_kanban'] = get_kanbanTasks($task_status,$limit, $offset);
			$data['task_status_id'] = $task_status;

			$this->template->set_master_template($theme .'/template_mobile.php');

			$this->load->view($theme.'/mobileview/tasks/Ajax_kanban',$data);

		}
	}


	// task related functionality for mobile website
	
	/*
	 * function : set_dependent_tasks
	 * return : gives dependent tasks of main task when tasks saves from popup
	 * author : Spaculus
	 */
	function set_dependent_tasks(){

		$task_id = $_POST['task_id'];

		$final_div = array();
		$div = array();

		$task_dependencies = get_task_dependencies_ids($task_id);
		date_default_timezone_set($this->session->userdata("User_timezone"));
		if($task_dependencies){
			foreach($task_dependencies as $dependency){
				$div['task_id'] = $dependency['task_id'];
				$div['task_scheduled_date'] = strtotime($dependency['task_scheduled_date']);
				$div['task_status_id'] = $dependency['task_status_id'];
				$div['task_due_date_time'] = strtotime($dependency['task_due_date']);
				$div['today_time'] = strtotime(date("Y-m-d"));
				$div['task_time_estimate'] = $dependency['task_time_estimate'];
				$div['subsection_id'] = $dependency['subsection_id'];
				$div['section_id'] = $dependency['section_id'];
				$div['color_id'] = $dependency['color_id'];
				$div['swimlane_id'] = $dependency['swimlane_id'];
				$final_div[] = $div;
			}
                        //echo "<pre>"; print_r($div); die();
			
		}
                echo json_encode($final_div);die;
	}
	
	function listtask()
	{
		$tasklist = gettasklist($_GET['term'],$_GET['searchDate'],$_GET['main_task_id']);
		
		$arr = array(); 
        if($tasklist)
        {
            foreach($tasklist as $key=>$val){
            	if($val->task_due_date!='0000-00-00'){
            		$due_date = date($this->config->item('company_default_format'),strtotime($val->task_due_date)); 
            	}else{
            		$due_date = 'N/A';
            	}
                $arr[] = array("id"=>$val->task_id,"label"=>$val->task_title." ( Due : ".$due_date." ) ","value"=>$val->task_title." ( Due : ".$due_date." ) "); 
            }
        } else {
        	$arr[] = array("id"=>'',"label"=>'No result found from "'.$_GET['term'].'"',"value"=>'');
        }
		
		echo json_encode($arr);
	}
	
	function setUserSwimlanes(){
		$theme = getThemeName();
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$swimlane_id = isset($_POST['swimlane_id'])?$_POST['swimlane_id']:get_default_swimlane($user_id);
		$this->load->model("user_model");
		$data['user_swimlanes'] = $this->user_model->get_swimlanes($user_id);
		$data['swimlane_id'] = $swimlane_id;
		if($user_id != get_authenticateUserID()){
			$data['type'] = 'disabled="disabled"';
		} else {
			$data['type'] = '';
		}
		$this->load->view($theme.'/layout/task/ajax_user_swimlanes',$data);
	}
	
	function uplaodLinkFiles(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->template->set_master_template($theme.'/template2.php');
		//echo date('Y-m-d H:i:s'); die();
		$post_data = json_decode($_POST['task_data'],true);
                
		$task_id = isset($_POST['task_id'])?$_POST['task_id']:'';
                $chk_exist = chk_task_exists($task_id);
		if($chk_exist=='1'){
			$id = $this->task_model->saveUploadLink($task_id);
			$data['files'] = get_task_inserted_file($id);
                        $data1['view'] = $this->load->view($theme.'/layout/task/ajax_add_files',$data,true);
		} else {
                        $task_id = $this->kanban_model->save_task($post_data);
                        $steps = get_task_steps($post_data['master_task_id']);
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
                        $id = $this->task_model->saveUploadLink($task_id);
                        $task_file = get_task_files($post_data['master_task_id']);
                        if($task_file){
                            foreach($task_file as $file){ 
                                $file_data = array(
                                        'task_file_name' => $file['task_file_name'],
                                        'file_link' => $file['file_link'],
                                        'file_title' => $file['file_title'],
                                        'task_id' => $task_id,
                                        'project_id' => $file['project_id'],
                                        'file_added_by' => $this->session->userdata('user_id'),
                                        'file_date_added' => $file['file_date_added']
                                    );

                                $this->db->insert('task_and_project_files',$file_data);
                            }
                        }
                        
			$data['task']['files'] = get_task_files($task_id);
                        $data1['view'] = $this->load->view($theme.'/layout/task/ajax_files',$data,true);
		}
		
		 
                 $data1['task_data'] =  get_task_detail($task_id);
                 $data1['task_id'] = $task_id;
//			echo "<pre>"; print_r($data1); die();
			echo json_encode($data1);die;
		
		
	}
	
	function update_steps(){
		$name = isset($_POST['name'])?$_POST['name']:'';
		$value = isset($_POST['value'])?$_POST['value']:'';
		$task_step_id = str_replace("step_title_","",$_POST['name']);
		$data = array(
			'step_title' => $value
		);
		//$this->db->where('task_step_id',$task_step_id);
		$this->db->where('(task_step_id = '.$task_step_id.' or (multi_allocation_step_id = '.$task_step_id.' and is_deleted = 0))');
		$this->db->update('task_steps',$data);
		
		$query = $this->db->select("task_id")->from("task_steps")->where("task_step_id",$task_step_id)->get();
		$task_id = $query->row()->task_id;
		$data['task']['steps'] = get_task_steps($task_id);
		$theme = getThemeName();
		$data['theme'] = $theme;
		$this->load->view($theme.'/layout/task/ajax_add_steps',$data);
	}
	
	function multiple_people(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		
		$task_id = isset($_POST['task_id'])?$_POST['task_id']:'';
		$project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
		if($project_id){
			$data['users'] = get_project_user_list($project_id);
		} else {
			$data['users'] = get_user_list();
		}
		$data['is_multiallocation_task'] = array();
		$data['status_name'] = array();
		$data['task_owner_id'] = get_authenticateUserID();
		if($task_id){
			$ids = $this->task_model->get_multiallocation_taks($task_id);
			if($ids){
				foreach($ids as $id){
					$data['is_multiallocation_task'][] = $id['task_allocated_user_id'];
					$data['status_name'][$id['task_allocated_user_id']] = $id['task_status_name'];
				}
			}
			$data['task_owner_id'] = get_task_owner_id($task_id);
		}
		
		
		$this->load->view($theme.'/layout/task/multiple_allocated_users',$data);
	}
	
	function assign_task(){
		$theme = getThemeName();
		$data['theme'] = $theme;
		$task_allocated_user_id = isset($_POST['task_allocated_user_id'])?$_POST['task_allocated_user_id']:'';
		$task_id = isset($_POST['task_id'])?$_POST['task_id']:'';
		
		$user_id = get_authenticateUserID();
		$swimlane_id = isset($_POST['swimlane_id'])?$_POST['swimlane_id']:get_default_swimlane($user_id);
		
		
		if($task_allocated_user_id){
			
			$task_update_data = array('task_allocated_user_id'=>get_authenticateUserID());
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$task_update_data);
			
			$multi_task_id = $task_id;
			$old_task_data = get_task_detail($multi_task_id);
			
			if($old_task_data['task_allocated_user_id'] != get_authenticateUserID()){

				$history_data = array(
					'histrory_title' => 'Task has been reallocated from "'.usernameById($old_task_data['task_allocated_user_id']).'" to "'.usernameById(get_authenticateUserID()).'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
				

				$chk_exist = chk_swim_exist($task_id,get_authenticateUserID());
				if($chk_exist == '0'){
					$user_swimlane = array(
						'user_id' => get_authenticateUserID(),
						'task_id' => $task_id,
						'swimlane_id' => get_default_swimlane(get_authenticateUserID()),
						'kanban_order' => 1,
						'calender_order' => get_user_last_calnder_order(get_authenticateUserID(),$old_task_data['task_scheduled_date']) + 1
					);

					$this->db->insert('user_task_swimlanes',$user_swimlane);

					$this->db->set('uts.kanban_order', 'uts.kanban_order + 1', FALSE);
					$this->db->where('uts.user_id', get_authenticateUserID());
					$this->db->where('uts.task_id != ',$task_id);
					$this->db->where('t.task_status_id', $old_task_data['task_status_id']);
					$this->db->update('user_task_swimlanes as uts join tasks as t ON t.task_id = uts.task_id');
				}
				
			}
			
			$steps = get_task_steps($multi_task_id);
			
			$files = getTaskFiles($multi_task_id);
			
			$task_data = array(
				'multi_allocation_task_id' => $multi_task_id,
				'task_company_id' => $old_task_data['task_company_id'],
				'task_project_id' =>$old_task_data['task_project_id'],
				'section_id' => $old_task_data['section_id'],
				'subsection_id' => $old_task_data['subsection_id'],
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
				'is_scheduled' => $old_task_data['is_scheduled'],
				'task_sub_category_id' => $old_task_data['task_sub_category_id'],
				//'task_skill_id' => $old_task_data['task_skill_id'],
				'task_staff_level_id' => $old_task_data['task_staff_level_id'],
				'task_owner_id' => get_authenticateUserID(),
				//'task_allocated_user_id' => $allocated_id,
				'task_time_spent' => 0,
				'task_time_estimate' => $old_task_data['task_time_estimate'],
				'task_status_id' => $old_task_data['task_status_id'],
				'frequency_type' => $old_task_data['frequency_type'],
				'recurrence_type' => $old_task_data['recurrence_type'],
				'Daily_every_day' => $old_task_data['Daily_every_day'],
				'Daily_every_weekday' => $old_task_data['Daily_every_weekday'],
				'Daily_every_week_day' => $old_task_data['Daily_every_week_day'],
				'Weekly_every_week_no' => $old_task_data['Weekly_every_week_no'],
				'Weekly_week_day' => $old_task_data['Weekly_week_day'],
				'monthly_radios' => $old_task_data['monthly_radios'],
				'Monthly_op1_1' => $old_task_data['Monthly_op1_1'],
				'Monthly_op1_2' => $old_task_data['Monthly_op1_2'],
				'Monthly_op2_1' => $old_task_data['Monthly_op2_1'],
				'Monthly_op2_2' => $old_task_data['Monthly_op2_2'],
				'Monthly_op2_3' => $old_task_data['Monthly_op2_3'],
				'Monthly_op3_1' => $old_task_data['Monthly_op3_1'],
				'Monthly_op3_2' => $old_task_data['Monthly_op3_2'],
				'yearly_radios' => $old_task_data['yearly_radios'],
				'Yearly_op1' => $old_task_data['Yearly_op1'],
				'Yearly_op2_1' => $old_task_data['Yearly_op2_1'],
				'Yearly_op2_2' => $old_task_data['Yearly_op2_2'],
				'Yearly_op3_1' => $old_task_data['Yearly_op3_1'],
				'Yearly_op3_2' => $old_task_data['Yearly_op3_2'],
				'Yearly_op3_3' => $old_task_data['Yearly_op3_3'],
				'Yearly_op4_1' => $old_task_data['Yearly_op4_1'],
				'Yearly_op4_2' => $old_task_data['Yearly_op4_2'],
				'start_on_date' => $old_task_data['start_on_date'],
				'no_end_date' => $old_task_data['no_end_date'],
                                'end_after_recurrence' => $old_task_data['end_after_recurrence'],
                                'end_by_date' => $old_task_data['end_by_date'],
                                'customer_id' => $old_task_data['customer_id'],
				'task_added_date' => date('Y-m-d H:i:s')
			);
			
			
			$mail_ids =array();
			foreach($task_allocated_user_id as $allocated_id){
				if($allocated_id != get_authenticateUserID()){
					
					$chk = is_task_exist_for_user($allocated_id,$task_id);
					
					if($chk == 0){
						
						$task_data['task_allocated_user_id'] = $allocated_id;
						$this->db->insert('tasks',$task_data);
						$task_id = $this->db->insert_id();
						
                                                $mail_ids[] = $task_id;
						if($steps){
							$i = 1;
							foreach($steps as $step){
								$step_data = array(
									'task_id' => $task_id,
									'multi_allocation_step_id' => $step['task_step_id'],
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
						
						if($files){
							foreach($files as $file){
								$file_data = array(
									'task_file_name' => $file['task_file_name'],
									'file_link' => $file['file_link'],
									'file_title' => $file['file_title'],
									'multi_allocation_file_id' => $file['task_file_id'],
									'task_id' => $task_id,
									'project_id' => $old_task_data['task_project_id'],
									'file_added_by' => $this->session->userdata('user_id'),
									'file_date_added' => date('Y-m-d H:i:s')
								);
					
								$this->db->insert('task_and_project_files',$file_data);
								$mid = $this->db->insert_id();
							}
						}
						
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
                                                                            WHERE `uts`.`user_id` = '$allocated_id'
                                                                            AND `uts`.`task_id` != '$task_id'
                                                                            AND `t`.`task_status_id` = '".$old_task_data['task_status_id']."'
                                                                        ");
							
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
						}
					}
				}
			}
			//pr($mail_ids); die();
                        $send_mail = array('ids'=>$mail_ids);
                        //pr($send_mail); die();
                        $this->load->library('session');
                        $this->session->set_userdata($send_mail);
                        $data['divisions'] = getUserDivision(get_authenticateUserID());
			$this->load->model("user_model");
			$data['user_swimlanes'] = $this->user_model->get_swimlanes($user_id);
			$data['swimlane_id'] = $swimlane_id;
			if($user_id != get_authenticateUserID()){
				$data['type'] = 'disabled="disabled"';
			} else {
				$data['type'] = '';
			}
			echo json_encode($data);die;
		}
	}

	function unassign_task(){
		if(!check_user_authentication()){
			redirect('home');
		}
		$task_allocated_user_id = isset($_POST['task_allocated_user_id'])?$_POST['task_allocated_user_id']:'';
		$task_id = isset($_POST['task_id'])?$_POST['task_id']:'';
		if($task_allocated_user_id){
			foreach($task_allocated_user_id as $allocated_id){
				if($allocated_id != get_authenticateUserID()){
					$this->task_model->remove_multiple_tasks($allocated_id,$task_id);
				}
			}
		}
	}
	
	function add_task_ajax(){
		$project_id = $_POST['project_id'];
		$data['section_id'] = isset($_POST['section_id'])?$_POST['section_id']:'';
		$theme = getThemeName();
		$data['theme'] = $theme;
		$data['project_id'] = $project_id;
		if($project_id){
			$data['sections'] = $this->task_model->get_project_sections_by_id($project_id);
		} else {
			$data['sections'] = '';
		}
		
		$parent_id = $_POST['parent_id'];
		$sub_id = isset($_POST['sub_id'])?$_POST['sub_id']:'';
		$data['sub_id'] = $sub_id;
		$data['parent_id'] = $parent_id;
		if($parent_id){
			$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active',$parent_id);
		} else {
			$data['sub_category'] = '';
		}

		$cat = get_company_sub_category($this->session->userdata('company_id'),'Active');
		if($cat){
			$data['is_sub_category_exist'] = "1";
		} else {
			$data['is_sub_category_exist'] = "0";
		}
		
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$data['divisions'] = getUserDivision($user_id);
		
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$swimlane_id = isset($_POST['swimlane_id'])?$_POST['swimlane_id']:get_default_swimlane($user_id);
		
		$this->load->model("user_model");
		$data['user_swimlanes'] = $this->user_model->get_swimlanes($user_id);
		
		$data['swimlane_id'] = $swimlane_id;
		if($user_id != get_authenticateUserID()){
			$data['type'] = 'disabled="disabled"';
		} else {
			$data['type'] = '';
		}
                
                $last_remeber_team_id = get_user_last_remember_calendar_team_id();
                if($last_remeber_team_id != 0){
                    $data['project_list'] = get_user_projects($last_remeber_team_id);
                }else{
                    $data['project_list'] = get_user_projects(get_authenticateUserID());
                }
		if($project_id){
			$data['users'] = get_project_user_list($project_id);
		} else {
			$data['users'] = get_user_list();
		}
		$data['customers']=  getCustomerList();
                $data['is_customer_user'] = $this->session->userdata('is_customer_user');
                if($this->session->userdata('is_customer_user') == 1){
                    $data['user_customer_id'] = get_user_customer_id($user_id);
                }else{
                    $data['user_customer_id'] = 0;
                }
		echo json_encode($data);die;
	}

	function set_multiallocation_tasks(){

		$task_id = $_POST['task_id'];

		$final_div = array();
		$div = array();

		$task_multiallocation = get_task_multiallocation_ids($task_id);
		date_default_timezone_set($this->session->userdata("User_timezone"));
		if($task_multiallocation){
			foreach($task_multiallocation as $multiatocation){
				$div['task_id'] = $multiatocation['task_id'];
				$div['task_scheduled_date'] = strtotime($multiatocation['task_scheduled_date']);
				$div['task_status_id'] = $multiatocation['task_status_id'];
				$div['task_due_date_time'] = strtotime($multiatocation['task_due_date']);
				$div['today_time'] = strtotime(date("Y-m-d"));
				$div['task_time_estimate'] = $multiatocation['task_time_estimate'];
				$div['subsection_id'] = $multiatocation['subsection_id'];
				$div['section_id'] = $multiatocation['section_id'];
				$div['task_allocated_user_id'] = $multiatocation['task_allocated_user_id'];
				$final_div[] = $div;
			}
			echo json_encode($final_div);die;
		}

	}
	
	/**
         * This function is used for updated scheduled date according to task_id of task.
         * @returns void
         */
	function updateSchedulledDate(){
		$date = $_POST['date'];
		$task_id = $_POST['task_id'];
		$date = change_date_format($date);
		$post_data = isset($_POST['post_data'])?json_decode($_POST['post_data'],true):'';
		
		$task_exists = chk_task_exists($task_id);
		if($task_exists == '0'){
			$this->load->model("kanban_model");
                        /*
                         * save task
                         */
			$task_id = $this->kanban_model->save_task($post_data);
			
			$steps = get_task_steps($post_data['master_task_id']);
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
                        $task_file = get_task_files($post_data['master_task_id']);
                        if($task_file){
                            foreach($task_file as $file){ 
                                $file_data = array(
                                        'task_file_name' => $file['task_file_name'],
                                        'file_link' => $file['file_link'],
                                        'file_title' => $file['file_title'],
                                        'task_id' => $task_id,
                                        'project_id' => $file['project_id'],
                                        'file_added_by' => $this->session->userdata('user_id'),
                                        'file_date_added' => $file['file_date_added']
                                    );

                                $this->db->insert('task_and_project_files',$file_data);
                            }
                        }
		}
		
		if($task_id){
                    /*
                     * update tasks table column is scheduled with value 1
                     */
			$update_data = array(
				'is_scheduled' => '1',
				'task_scheduled_date' => $date
			);
			$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
			$this->db->update('tasks',$update_data);
			
			$user_swimlane = array(
				'calender_order' => get_user_last_calnder_order($post_data['task_allocated_user_id'],$date) + 1
			);
			
			$this->db->where('user_id',$post_data['task_allocated_user_id']);
			$this->db->where('task_id',$task_id);
			$this->db->update('user_task_swimlanes',$user_swimlane);
			
			$data['task_data'] = get_task_detail($task_id);
			
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
				$owner_name = ucwords($owner_name->first_name)." ".ucwords($owner_name->last_name[0]);
				$data['task_owner_name'] = $owner_name;
	
				$allocated_name = get_user_name($data['task_data']['task_allocated_user_id']);
				$allocated_name = ucwords($allocated_name->first_name)." ".ucwords($allocated_name->last_name[0]);
				$data['task_allocated_user_name'] = $allocated_name;
	
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
			
			$type = isset($_POST['type'])?$_POST['type']:'';
			$duration = isset($_POST['duration'])?$_POST['duration']:'today';
			$from_page = isset($_POST['redirect_page'])?$_POST['redirect_page']:'from_dashboard';
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
		
	}
	
	function updateDueDate(){
		$date = $_POST['date'];
		$task_id = $_POST['task_id'];
		$date = change_date_format($date);
		$post_data = isset($_POST['post_data'])?json_decode($_POST['post_data'],true):'';
		$new_due_date = $date;
		$task_exists = chk_task_exists($task_id);
		if($task_exists == '0'){
			$this->load->model("kanban_model");
			$task_id = $this->kanban_model->save_task($post_data);
			
			$steps = get_task_steps($post_data['master_task_id']);
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
                        $task_file = get_task_files($post_data['master_task_id']);
                        if($task_file){
                            foreach($task_file as $file){ 
                                $file_data = array(
                                        'task_file_name' => $file['task_file_name'],
                                        'file_link' => $file['file_link'],
                                        'file_title' => $file['file_title'],
                                        'task_id' => $task_id,
                                        'project_id' => $file['project_id'],
                                        'file_added_by' => $this->session->userdata('user_id'),
                                        'file_date_added' => $file['file_date_added']
                                    );

                                $this->db->insert('task_and_project_files',$file_data);
                            }
                        }
		}
		
		if($task_id){
			if($post_data['task_scheduled_date']!='0000-00-00'){
				$update_data = array(
					'task_due_date' => $date
				);
			} else {
				$update_data = array(
					'is_scheduled' => '1',
					'task_scheduled_date' => $date,
					'task_due_date' => $date
				);
				
				$user_swimlane = array(
					'calender_order' => get_user_last_calnder_order($post_data['task_allocated_user_id'],$date) + 1
				);
				
				$this->db->where('user_id',$post_data['task_allocated_user_id']);
				$this->db->where('task_id',$task_id);
				$this->db->update('user_task_swimlanes',$user_swimlane);
				
			}
			
			$this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
			$this->db->update('tasks',$update_data);
			
			$data['task_data'] = $task_data = get_task_detail($task_id);
			
			if($date!="" && $post_data['task_due_date'] != $date){
				$history_data = array(
					'histrory_title' => 'Task due date changed from "'.$post_data['task_due_date'].'" to "'.$date.'"',
					'history_added_by' => get_authenticateUserID(),
					'task_id' => $task_id,
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('task_history',$history_data);
				
				$multiIds = multiAllocationTaskIds($task_id);
				if($multiIds){
					foreach($multiIds as $mId){
						$history_data = array(
							'histrory_title' => 'Task due date changed from "'.$post_data['task_due_date'].'" to "'.$date.'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $mId->task_id,
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
					}
				}
				
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
				
				$modified_user_name = $this->session->userdata('username');
				
				if($task_data['task_owner_id'] !=get_authenticateUserID()){
	
					//notification
					$notification_text = 'Task "'.$task_data['task_title'].'" due date changed from "'.$post_data['task_due_date'].'" to "'.$date.'" by '.$this->session->userdata('username').'';
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
					
					/*** send email to task owner userfor task due date changed  ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='due date modified'");
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
					$email_subject=str_replace('{old_due_date}',$post_data['task_due_date'],$email_subject);
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
					$email_message=str_replace('{old_due_date}',$post_data['task_due_date'],$email_message);
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
				if($task_data['task_owner_id']!=$task_data['task_allocated_user_id'] && $task_data['task_allocated_user_id'] !=get_authenticateUserID()){
					//notification
					$notification_text = 'Task "'.$task_data['task_title'].'" due date changed from "'.$post_data['task_due_date'].'" to "'.$date.'" by '.$this->session->userdata('username').'';
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
					
					/*** send email to task allocated userfor task due date changed ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='due date modified'");
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
					$email_subject=str_replace('{old_due_date}',$post_data['task_due_date'],$email_subject);
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
					$email_message=str_replace('{old_due_date}',$post_data['task_due_date'],$email_message);
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
				$owner_name = ucwords($owner_name->first_name)." ".ucwords($owner_name->last_name[0]);
				$data['task_owner_name'] = $owner_name;
	
				$allocated_name = get_user_name($data['task_data']['task_allocated_user_id']);
				$allocated_name = ucwords($allocated_name->first_name)." ".ucwords($allocated_name->last_name[0]);
				$data['task_allocated_user_name'] = $allocated_name;
	
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
			
			$type = isset($_POST['type'])?$_POST['type']:'';
			$duration = isset($_POST['duration'])?$_POST['duration']:'today';
			$from_page = isset($_POST['redirect_page'])?$_POST['redirect_page']:'from_dashboard';
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
			
			if (strpos($data['task_data']['task_id'],'child') !== false){
			    $data['is_chk'] = "0";
			} else {
				$data['is_chk'] = "1";
			}
			$data['today_date'] = strtotime(date("Y-m-d"));
	
			echo json_encode($data);die;
		}
		
	}
        
        
        function send_allocation_mail(){
            $task_id = $_POST['task_id'];
            $allocated_to = $_POST['task_allocated_user_id'];
            $old_task_detail = get_task_detail($task_id);
            foreach($allocated_to as $allocated){
                                            if($allocated != get_authenticateUserID()){
								/*** send email to task allocated user ****/
								$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task allocated to'");
								$email_temp=$email_template->row();
								$email_address_from=$email_temp->from_address;
								$email_address_reply=$email_temp->reply_address;
			
								$email_subject=$email_temp->subject;
								$email_message=$email_temp->message;
			
								$allocated_user_info = get_user_info($allocated);
								$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;
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
                                        
                                                     echo 'done'; die();   
        }
        
        
        function project_team(){
            $project_id = $_POST['project_id'];
            $users['users']=get_user_under_project($project_id);
           // print_r($users); die();
          echo json_encode($users); die();
        }
         function edit_comment(){
            $comment_id = $_POST['name'];
            $comment = $_POST['value'];
            $data=array(
                "comment"=>$comment
            );
            $this->db->where('timer_logs_id',$comment_id);
            $this->db->update('task_timer_logs',$data);
        }
        
        
       
        function get_project_customer(){
            if($_POST){
                $project_id  = $_POST['project_id'];
                $task_id = $_POST['task_id'];
                if($project_id!=0){
                    $this->db->select('project_customer_id');
                    $this->db->from('project');
                    $this->db->where('project_id',$project_id);
                    $this->db->where('company_id',  $this->session->userdata('company_id'));
                    $this->db->where('is_deleted','0');
                    $query = $this->db->get();
                    $customer_id = $query->row()->project_customer_id;
                }else{
                    $customer_id = '';
                }
                if($customer_id !=''){
                    $data = array(
                        "customer_id"=>$customer_id
                    );
                    $this->db->where('task_id',$task_id);
                    $this->db->where('task_company_id',$this->session->userdata('company_id'));
                    $this->db->update("tasks",$data);
                    echo $customer_id;
                }else{
                    if($this->session->userdata('is_customer_user') == '1'){
                        $customer_id = get_user_customer_id(get_authenticateUserID());
                        $this->db->set('customer_id',$customer_id);
                        $this->db->where('task_id',$task_id);
                        $this->db->where('task_company_id',$this->session->userdata('company_id'));
                        $this->db->update("tasks",$data);
                        echo $customer_id;
                    }else{
                        $data = array(
                            "customer_id"=>''
                        );
                        $this->db->where('task_id',$task_id);
                        $this->db->where('task_company_id',$this->session->userdata('company_id'));
                        $this->db->update("tasks",$data);
                        echo "0";
                    }
                }
                die();
            }
        } 
        
        function update_step_status_completed(){
            
                if($_POST){
                        $serializedData = $_POST['str'];
			$post_data = $_POST['post_data'];
                        $unserializedData = array();
                        parse_str($serializedData,$unserializedData);
                        $task_exists = chk_task_exists($unserializedData['task_id']);
                        
                        if($task_exists =='0'){
                            $check_array = $_POST['check_array'];
                          //  print_r($check_array); die();
                            //$ids = array();
                            $id = $this->kanban_model->save_task($post_data);
                            $steps = get_task_steps($post_data['master_task_id']);
                            if($steps){
                                    $i = 1;
                                    foreach($steps as $step){
                                        foreach($check_array as $check){
                                            if($step['task_step_id']==$check){
                                                $step['is_completed'] = '1';
                                            }
                                        }
                                        $step_data = array(
                                                        'task_id' => $id,
                                                        'step_title' => $step['step_title'],
                                                        'step_added_by' => $step['step_added_by'],
                                                        'is_completed' => $step['is_completed'],
                                                        'step_sequence' => $i,
                                                        'step_added_date' => date('Y-m-d H:i:s')
                                                    );
                                        $this->db->insert('task_steps',$step_data);
                                        //$ids[] = $this->db->insert_id();
                                        $i++;
                                    }
                            }

                        }else{
                            $id = $this->task_model->update_task_step_seq();
                        }
			$data['task_id'] = $id;
			$data['task']['steps'] = get_task_steps($id);
			echo json_encode($data);die;
		}
                
        }
        
        function get_customer_id_by_project_id(){
            if($_POST){
                $project_id = $_POST['project_id'];
                
                $this->db->select('project_customer_id');
                $this->db->from('project');
                $this->db->where('project_id',$project_id);
                $this->db->where('company_id',$this->session->userdata('company_id'));
                $this->db->where('is_deleted','0');
                $query = $this->db->get();
                echo $query->row()->project_customer_id;
            }
        }
        /**
         * This function is used to remove task dependency.
         */
        
        function remove_task_dependency(){
            
		if(!check_user_authentication()){
			redirect('home');
		}
		$theme = getThemeName();
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$dependent_task_id = $_POST['dependent_task_id'];
		$update = array(
			'is_prerequisite_task' => '0',
                        'prerequisite_task_id' => '0'
		);
		$this->db->where('task_id',$dependent_task_id);
		$this->db->update('tasks',$update);

		$history_data = array(
			'histrory_title' => 'Dependency task removed.',
			'history_added_by' => $this->session->userdata('user_id'),
			'task_id' => $_POST['task_id'],
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);

		$data['task_id'] = $_POST['task_id'];
		$data['task']['dependencies'] = get_task_dependencies($_POST['task_id']);
		$this->load->view($theme.'/layout/task/ajax_add_dependencies',$data);
	}
        
        function search_task(){
            /**
             * check authentication
             */
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		$data = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		
		$data['theme'] = $theme;
		$data['error'] = '';
                

		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		/**
                 * get all user and company related info
                 */
		if($this->session->userdata('is_administrator') == 1){
                    $data['users'] = get_user_list();
                }else if($this->session->userdata('is_manager')== 1){
                    $user_under_manager = get_users_under_managers();
                    if($user_under_manager != '0'){
                        $data['users'] = array_merge($user_under_manager,get_user_inform());
                    }else{
                        $data['users'] = get_user_inform();
                    }
                }else{
                    $data['users'] = get_user_inform();
                }
                $data['get_user_filters'] = getUserFilters();
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                $data['customers']=  getCustomerList();
                $data['tasks']  = $this->task_model->getsearchtask();          
                $data['divisions'] = getUserDivision(get_authenticateUserID());
                $data['departments'] = getUserDepartment(get_authenticateUserID());
                
		/**
                 * search task page
                 */
		$this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);

		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);

		$this->template->write_view('content_side', $theme.'/layout/task/search_task', $data, TRUE);

		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
        }
        
        function ajax_load_more_filter(){
            $theme = getThemeName();
            $data['site_setting_date'] = $this->config->item('company_default_format');
            $selected_filters = $this->input->post('filters');
            $filters_data = $this->input->post('filters_data');
            $data = array();
            if(isset($selected_filters) && !empty($selected_filters)){
                foreach ($selected_filters as $filter){
                    switch($filter){
                        case 'category':
                            $data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
                            break;
                        case 'subcategory':
                            $data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
                            break;
                        case 'division':
                            $data['divisions'] = getUserDivision(get_authenticateUserID());
                            break;
                        case 'department':
                            $data['departments'] = getUserDepartment(get_authenticateUserID());
                            break;
                        case 'task_status':
                            $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                            break;
                    }
                }
            }
            $data['filters_data'] = get_filters_value($filters_data);
            echo $this->load->view($theme .'/layout/task/more_filters',$data,TRUE);
        }
        
        function ajax_search_result_data(){
            $select_data = $this->input->post('data');
            $data1 = array();
            $theme = getThemeName();
            $data = get_filters_value($select_data);
            $result = $this->task_model->getFilteredData($data);
            $data1['tasks'] = $result;
            echo $this->load->view($theme .'/layout/task/ajax_search_result',$data1,TRUE);
        }
        
        function save_user_filters(){
            $filter_data = $this->input->post('filter_data');
            $data = get_filters_value($filter_data);
            $insert_data = array(
                            "user_id"=>  get_authenticateUserID(),
                            "filter_name"=>  $this->input->post('filter_name'),
                            "filter_value"=>  json_encode($data),
                            "create_date"=> date('Y-m-d H:i:s'),
                            "is_deleted"=>'0'
            );
            $this->db->insert('user_filters',$insert_data);
            $filter_id = $this->db->insert_id();
            echo $filter_id; die();
        }
        
        function set_user_filter(){
            $theme = getThemeName();
            $data = array();
            $filter_id = $this->input->post('filter_id');
            $this->db->select('*');
            $this->db->from('user_filters');
            $this->db->where('filter_id',$filter_id);
            $query = $this->db->get();
            $data1 =$query->row();
            $new_data = json_decode($data1->filter_value,true);
            if($this->session->userdata('is_administrator') == 1){
                $data['users'] = get_user_list();
            }else if($this->session->userdata('is_manager')== 1){
                    $user_under_manager = get_users_under_managers();
                    if($user_under_manager != '0'){
                        $data['users'] = array_merge($user_under_manager,get_user_inform());
                    }else{
                        $data['users'] = get_user_inform();
                    }
            }else{
                $data['users'] = get_user_inform();
            }
            $data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
            $data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
            $data['user_projects'] = get_user_projects(get_authenticateUserID());
            $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
            $data['customers']=  getCustomerList();
            $data['divisions'] = getUserDivision(get_authenticateUserID());
            $data['departments'] = getUserDepartment(get_authenticateUserID());
            $data['tasks'] = $this->task_model->getFilteredData($new_data);
            $data['filter_name'] = $data1->filter_name;
            $data['filters_data'] = $new_data;
            $data['filter_id'] = $data1->filter_id;
            echo $this->load->view($theme .'/layout/task/ajax_search_task',$data,TRUE); die();
        }
        
        function update_filter(){
            $filter_id = $this->input->post('filter_id');
            $data = $this->input->post('data');
            $json_data = get_filters_value($data);
            $insert_data = array(
                "filter_value"=>  json_encode($json_data)
            );
            $this->db->where('filter_id',$filter_id);
            $this->db->update('user_filters',$insert_data);
            echo $filter_id; die();
        }
        /**
         * generate excel sheet from search module.
         */
        function search_data_excel(){
                if(!check_user_authentication()){
			redirect('home');
		}
		/**
                 * for generate excelsheet this is loaded
                 */
		$this->load->library('excel');
                $filter_data = $_GET['filter'];
                $unserializedData = json_decode($filter_data,true);
                $data = get_filters_value($unserializedData);
                if(empty($data)){
                    $excel_data = $this->task_model->getsearchtask();
                }else{
                    $excel_data = $this->task_model->getFilteredData($data);
                }
                $site_setting_date = $this->config->item('company_default_format');

                       $this->excel->createSheet();
                       /**
                        * set activate worksheet number 
                        */
                       $this->excel->setActiveSheetIndex(0);
                       /**
                        * set name the worksheet
                        */
                       $this->excel->getActiveSheet()->setTitle('Search module');
                    
                        date_default_timezone_set($this->session->userdata("User_timezone")); 

                        //set cell A1 content with some text
                        $tables=array('Task Name', 'Task Owner', 'Allocated to','User division','User department','Priority','Color','Project','Task Status','Task Category','Task Sub Category','Time allocated','Actual Time','Completion Date','Scheduled Date','Due Date','Customer Name','External ID','Base Cost','Estimated Total Cost','Base Charge','Estimated Total Revenue');
                        $key=array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1','Q1','R1','S1','T1','U1','V1');
                        $title=array_combine($key, $tables);
                        foreach ($title as $key=>$value)
                         {
                            $this->excel->getActiveSheet()->setCellValue($key, $value);
                            //change the font size
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
                                //make the font become bold
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
                         }
                        $result = array();
                        $res = array();
                        if($excel_data){
                            foreach($excel_data as $row){
                                $division = get_user_division($row['user_id']);
                                if ($division) {
                                    $division = $division;
                                } else {
                                    $division = "N/A";
                                }
                                $department = get_user_department($row['user_id']);
                                if ($department) {
                                    $department = $department;
                                } else {
                                    $department = "N/A";
                                }
                                $color_name = $row['name'];
                                if ($color_name) {
                                    $color_name = $color_name;
                                } else {
                                    $color_name = "N/A";
                                }
                                $category_name = $row['category_name'];
                                if ($category_name) {
                                    $category_name = $category_name;
                                } else {
                                    $category_name = "N/A";
                                }
                                $sub_category_name = $row['sub_category_name'];
                                if ($sub_category_name) {
                                    $sub_category_name = $sub_category_name;
                                } else {
                                    $sub_category_name = "N/A";
                                }
                                if($row['project_title']){
                                    $project_title = $row['project_title']; 
                                } else { 
                                    $project_title = "N/A";
                                }
                                if($row['task_completion_date'] != '0000-00-00 00:00:00'){
                                    $completion_date = date($site_setting_date, strtotime(toDateNewTime($row['task_completion_date'])));
                                }else{
                                    $completion_date = 'N/A';   
                                }
                                if ($row['task_scheduled_date'] != '0000-00-00') {
                                    $task_scheduled_date = date($site_setting_date, strtotime($row['task_scheduled_date']));
                                } else {
                                    $task_scheduled_date = "N/A";
                                } 
                                if ($row['task_due_date'] != '0000-00-00') {
                                    $task_due_date = date($site_setting_date, strtotime($row['task_due_date']));
                                } else {
                                    $task_due_date = "N/A";
                                } 
                                $res['task_title'] = $row['task_title'];
                                $res['task_owner'] = $row['owner_first_name'] . " " . $row['owner_last_name'];
                                $res['allocated_to'] = $row['allocated_user_first_name'] . " " . $row['allocated_user_last_name'];
                                $res['user_division'] = $division;
                                $res['user_department'] = $department;
                                $res['priority'] = $row['task_priority'];
                                $res['color'] = $color_name;
                                $res['project'] = $project_title;
                                $res['task_status'] = $row['task_status_name'];
                                $res['task_category'] = $category_name;
                                $res['task_sub_category'] = $sub_category_name;
                                $res['time_allocated'] = round($row['task_time_estimate'] / 60, 2);
                                $res['actual_time'] = round($row['task_time_spent'] / 60, 2);
                                $res['completion_date'] = $completion_date;
                                $res['scheduled_date'] = $task_scheduled_date;
                                $res['due_date'] = $task_due_date;
                                $res['customer_name'] = $row['customer_name'];
                                $res['external_id'] = $row['external_id'];
                                $res['base_cost'] = $row['cost_per_hour'];
                                $res['estimated_total_cost'] = $row['cost'];
                                $res['base_charge'] = $row['charge_out_rate'];
                                $res['estimated_total_revenue'] = $row['estimated_total_charge'];
                                $result[] = $res; 
                            }

                        }
//                        pr($result); die();
                        $key1=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V');
                        $i=2;
                        //pr($result); die();
                        foreach($result as $a)
                        {
                            $result=array_combine($key1,$a);
                            foreach($result as $key=>$value)
                            {
                                $this->excel->getActiveSheet()->setCellValue($key.$i, $value);
                                //change the font size
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(11);
                                //make the font become bold
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(FALSE);
                            }
                            $i++;
                        }
                        
                    
                //merge cell A1 until D1
                //$this->excel->getActiveSheet()->mergeCells('A1:D1');
                    /*
                     * set aligment to center for that merged cell (A1 to D1)
                     */
                    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $filename='Export_Data '.date("Y:m:d h:i:s") .'.xlsx'; //save our workbook as this file name

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
                    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                    header('Cache-Control: max-age=0'); //no cache
                    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                    //if you want to save it as .XLSX Excel 2007 format
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
                    //force user to download the Excel file without writing it to server's HD
                    //	ob_end_clean();
                   $objWriter->save('php://output');
        }
}
?>
