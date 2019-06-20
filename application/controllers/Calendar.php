<?php
/**
 * This class is used to create calender page and render different views of calender.  The calendar views are more appropriate to work through 
   day-to-day activities and manage priorities for the team.
   
 * It has different methods to render following views on pages, and for adding tasks for current date and future dates.
 *		Monthly view -  from a task planning and availability perspective.
		Weekly view -  To manage week key priorities that have been scheduled.
		Next 5 days view - this shows today's and the next 4 days effectively allowing  to plan,
		visualise and review 5 days at a time. 

 * This class is extending the SPACULLUS_Controller subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Calendar extends SPACULLUS_Controller{

	/**
        * It default constuctor which is called when calender class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */ 
	function Calendar(){
            /**
             * call base class constructor
             */
		parent :: __construct ();
                /**
                 * Amazon S3  Configuration file
                 */
		$this->load->library('s3');
                /**
                 * Amazon S3 server Configuration file
                 */
		$this->config->load('s3');
                /**
                 * load kanban_model database
                 */
		$this->load->model('kanban_model');
		
                /**
                 * set default timezone of date
                 */
		date_default_timezone_set("UTC");
	}
        
        function update_progress_bar()
            {
		$theme = getThemeName();
		$data = array();
                //print_r($_POST);
		$data['date'] = $_POST['id'];
                $data['capacity'] = $_POST['capacity'];
                $data['total_estimate'] = $_POST['estimate_time'];
                $data['total_spent'] = $_POST['spent_time'];
                $data['title'] = $_POST['title'];
		echo $this->load->view($theme .'/layout/calender/progressbar',$data,TRUE);
		
            }

        /* change multiple progress bar simultaneusly*/
        function update_multiple_progress_bar()
            {
		$theme = getThemeName();
		$data = array();
                $json = json_decode($_POST['data']);
                $res=array();
                $response=array();
                foreach($json as $row)
                {
		$data['date'] = $row->id;
                $data['capacity'] = $row->capacity;
                $data['total_estimate'] = $row->estimate_time;
                $data['total_spent'] = $row->spent_time;
                $data['title'] = $row->title;
                $response['id'] = $row->id;
		$response['html'] = $this->load->view($theme .'/layout/calender/progressbar',$data,TRUE);
                $res[]=$response;
                }
                echo json_encode($res);
            }
	function update_backlog_task(){
		$this->load->model('task_model');
		$data = array();
                $json = json_decode($_POST['data']);
                $res=array();
                $response=array();
                foreach($json as $row)
                {   
                    $info = explode('&', $row->id);
		    $data['task_id'] = $info[0];
                    $data['scheduled_date'] = change_date_format($row->task_date);
		    $data['due_date'] = $info[1];
                    $this->task_model->schedule_backlog_task($data);
                    $response['id'] = $info[0];
                    $res[]=$response;
                }
                echo json_encode($res);
	}

	/**
         * This function will call When user click on calender link.It will render calender page of loggedin user with task.
         * It fetch data from different method for view.
         * @returns void
         */
	function myCalendar(){
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

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => '',
            'steps' => '',
            'files' => '',
            'comments' => ''
		);

		$data['year'] = date('Y');
		$data['month'] = date('m');

		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['cal_user_color_id'] = '0';
                        $data['show_other_user_task'] = 0;
		}
                /**
                 * get all calender related values
                 */
		$data['users'] = get_user_list();

		
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                               $data['customers']=  getCustomerList();
                           
                if($this->session->userdata('Temp_calendar_user_id') == '0'){
                    $data['divisions'] = getUserDivision(get_authenticateUserID());
                    $data['departments'] = getUserDepartment(get_authenticateUserID());
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                    $data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
                    $data['capacity'] = getUserCapacity(get_authenticateUserID());
                }else{
                    $data['capacity'] = getUserCapacity($this->session->userdata('Temp_calendar_user_id'));
                    $data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
                    $data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                    $data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
                }
                
                
		/**
                 * create mycalender page
                 */
		$this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);

		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);

		$this->template->write_view('content_side', $theme.'/layout/calender/myCalender', $data, TRUE);

		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
		
		
	}
        /**
         *  When user click on calender link on footer option then this function will call.And it will render new calender on ajax request.
         * @returns void
         */
	function calendarview_ajx()
	{
		$theme = getThemeName();

		$data = array();
		$data['year'] = $_REQUEST['year'];
		$data['month'] = $_REQUEST['month'];
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['cal_user_color_id'] = '0';
                        $data['show_other_user_task'] = 0;
		}
		$data['capacity'] = getUserCapacity($this->session->userdata('Temp_calendar_user_id'));
		$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
		echo $this->load->view($theme .'/layout/calender/calendar_view_ajx',$data,TRUE);
		
	}
	/**
         * This function will call when user drag & drop task on month view.It will update task related data in DB.
         * @returns string of jsonObject
         */
	function UpdateScope(){

		$theme = getThemeName();
		$data = array();
		$scope_id = $_POST['scope_id'];
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$orig_date = date("Y-m-d",$_POST['date']);
                
		$post_data = json_decode($_POST['task_data'],true);

		$date = date("Y-m-d",strtotime($orig_date));
                
		///die;
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$chk_exist = chk_task_exists($scope_id);
                /*Check task existence for update task*/
		if($chk_exist == '0'){
			$chk_ext = chk_virtual_recurrence_exists($post_data['master_task_id'],$post_data['task_orig_scheduled_date']);
			if($chk_ext!='' && $chk_ext['is_deleted'] == '0'){
				$id = $chk_ext['task_id'];
			} else {
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
			}
		} else {
			$id = $scope_id;
		}
                /*This query update task due date in DB*/
		$task_data = array(
			'task_scheduled_date' => $date,
			'is_scheduled' => '1'
		);
		$this->db->where('task_id',$id);
		$this->db->update('tasks',$task_data);

		$json['id'] = $id;
		$json['title'] = $post_data['task_title'];
		$json['task_time_spent'] = $post_data['task_time_spent'];
		$json['task_due_date'] = strtotime($post_data['task_due_date']);
		$json['today_date_time'] = strtotime(date("Y-m-d"));
		$json['task_scheduled_date'] = $date;

		echo json_encode($json);die;
	}

	/**
         * On month view, it's used to update the order of tasks. As on calendar view user can drag-drop the tasks to move it on appropriate position.
         * So once user drop the task on selected position at the same time this function update the order of tasks in db.  So that it can render on appropriate position next time.  

         * @returns string of jsonobject 
         */
	function setOrder(){

		$theme = getThemeName();
		$data = array();

		$data['site_setting_date'] = $this->config->item('company_default_format');

		date_default_timezone_set($this->session->userdata("User_timezone"));
		$date = date('Y-m-d',$_POST['date']);

		$order = $_POST['order'];
		$data['from'] = isset($_POST['from'])?$_POST['from']:'';
		$scope_id = $_POST['scope_id'];
		if(isset($_POST['task_data']) && $_POST['task_data']!=''){
			$post_data = json_decode($_POST['task_data'],true);
		} else {
			$post_data = '';
		}

		$data['site_setting_date'] = $this->config->item('company_default_format');

		if($post_data){
			if(strpos($scope_id, 'child') !== false){
				$chk_ext = chk_virtual_recurrence_exists($post_data['master_task_id'],$post_data['task_orig_scheduled_date']);
				if($chk_ext!='' && $chk_ext['is_deleted'] == '0'){
					$inserted_id = $chk_ext['task_id'];
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
				}

			} else {
				$inserted_id = $scope_id;
			}


			if($order){
				$step1 = explode('&', $order);
				$i = 1;

				foreach($step1 as $step){

					if(strpos($step, 'child') !== false){
						$ids = str_replace("main_child_","",$step);
						$virtual_ids = explode('[]=', $ids);
						$main_id = $virtual_ids[0];
						$custom_id = 'child_'.$virtual_ids[0].'_'.$virtual_ids[1];
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
						$user_wise_data = array(
							'calender_order' => $i
						);
						$this->db->where('task_id',$task_id);
						$this->db->where('user_id',$this->session->userdata("Temp_calendar_user_id"));
						$this->db->update('user_task_swimlanes',$user_wise_data);
					}
					$i++;
				}
			}
			$json['id'] = $inserted_id;
			$json['title'] = $post_data['task_title'];
			$json['task_time_spent'] = $post_data['task_time_spent'];

			echo json_encode($json);die;
		} else {
			echo "no_data";die;
		}
	}

        /**
         * This function will call from right-click functionality option on calender page.It will fetch all data from post method after that
           it will check task existence for save task in DB.Then it update the task color in DB and task history like status, color, preority.
           It create a new view when the color updated and render it on monthly calendar page.
         * @returns string in HTML format  
         */
	function set_task_color(){

		$color_id = $_POST['color_id'];
		$task_id = $_POST['task_id'];
		//$post_data = json_decode($_POST['task_data'],true);
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
		/* check task existance for get color code*/
		$chk_exist = chk_task_exists($task_id);
		if($chk_exist == '0'){
                    /**
                     * save task through kanban_model database
                     */
                    $post_data = isset($_POST['task_data'])?json_decode($_POST['task_data'],true):'';
                    
                    if($post_data == '')
                    {
                        $main_id = preg_replace("/[^0-9]/", '', $task_id);
                        $ids = explode('_',$task_id);
			$main_id = $ids[1];
                        $orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
                    }
                    $main_id = $post_data['master_task_id'];
			$id = $this->kanban_model->save_task($post_data);
			$steps = get_task_steps($main_id);
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
			$id = $task_id;
			$is_virtual = "0";
		}

		$data = array('color_id'=>$color_id);
		$this->db->where('task_id',$id);
		$this->db->where('user_id',$this->session->userdata("Temp_calendar_user_id"));
		$this->db->update('user_task_swimlanes',$data);
                /**
                 * update task_history after task is completed
                 */
		$history_data = array(
			'histrory_title' => 'Task color has been changed by '.$this->session->userdata("username").'.',
			'history_added_by' => get_authenticateUserID(),
			'task_id' => $id,
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('task_history',$history_data);

		if($is_virtual == "1"){

			$theme = getThemeName();
                        if(isset($_POST['color_menu'])){
                            $color_menu=$_POST['color_menu'];
                        }
                        else{
                            $color_menu='true';
                        }
                        $data['color_menu']=$color_menu;
			$data['site_setting_date'] = $this->config->item('company_default_format');
			$default_day = get_default_day_of_company();
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			if($redirect == 'weekView' || $redirect == "NextFiveDay"){
                                if(isset($_POST['color_menu'])){
                                    $color_menu=$_POST['color_menu'];
                                }
                                else{
                                    $color_menu='true';
                                }
				$data['active_menu']=$_POST['active_menu'];
				$data['week_task'] = get_task_detail($id);
                                
				date_default_timezone_set($this->session->userdata("User_timezone"));
				$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));
                                
				$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

				$action = isset($_POST['action'])?$_POST['action']:'';

				$date_arr = array();

				$data['date_arr'] = $this->getArr($start_date);
				$data['company_date_arr'] = $this->getArr($start_date,'company');

				$data["start_date"] = reset($data["date_arr"]);
				$data["end_date"] =end($data["date_arr"]);
				$data['action'] = $redirect;
				
				if($last_rember_values){
					$data['calender_project_id'] = $last_rember_values->calender_project_id;
					$data['left_task_status_id'] = $last_rember_values->task_status_id;
					$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
					$data['calender_date'] = $last_rember_values->calender_date;
					$data['calender_sorting'] = $last_rember_values->calender_sorting;
					$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
				} else {
					$data['calender_project_id'] = '';
					$data['left_task_status_id'] = '';
					$data['calender_team_user_id'] = '';
					$data['calender_date'] = '';
					$data['calender_sorting'] = '1';
					$data['cal_user_color_id'] = '';
                                        $data['show_other_user_task'] = 0;
				}
                                $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
				echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);

			}else if($redirect == 'from_kanban'){
                            $data['color_menu']=$color_menu;
			$data['kanban'] = get_task_detail($id);
			$this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
                        }else {
				$data['date'] = get_task_detail($id);
				$data['scope_id'] = $id;
				$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
				$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
				echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
			}
		} else {
                    $a['color_menu']=$color_menu;
			$a['color_code'] = get_task_color_code($color_id);
			$a['outside_color_code'] = get_outside_color_code($color_id);
			echo json_encode($a);die;
		}
	}
	
        /**
         * This function will call for move task from right click functionality on calender view.It will move task using calender option on footer.
           It fetch task_id & dates from post method.After that,it will update date in task table then it will check a condition for redirect page and render specific Ajax view page. 
         * @returns void
         */
	function move_task(){

		$task_id = $_POST['task_id'];
		$orig_date = change_date_format($_POST['sel_date']);
		$due_date = change_date_format($_POST['due_date']);
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
		$from_redirect = isset($_POST['from_redirect'])?$_POST['from_redirect']:'';
		$post_data = isset($_POST['task_data'])?json_decode($_POST['task_data'],true):'';

		$date = change_date_format($orig_date);

		$chk_exist = chk_task_exists($task_id);
		/* check task existance by default is exist */
		if($chk_exist == '0'){
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
			$id = $task_id;
			$is_virtual = "0";
		}
                $task_details = get_task_detail($id);
                /*This query update task table with new due date*/
                if($task_details['task_due_date'] == '0000-00-00'){
                    $task_data = array(
                            'task_scheduled_date' => $date,
                            'is_scheduled' => '1',
                            'task_due_date'=>$date
                    );
                }else{
                    $task_data = array(
                            'task_scheduled_date' => $date,
                            'is_scheduled' => '1'
                    );
                }
		$this->db->where('task_id',$id);
		$this->db->update('tasks',$task_data);

		

		/* check redirect page with fiveweek page for redirection */
		if($from_redirect == "fiveweek"){
			$theme = getThemeName();

			$data['site_setting_date'] = $this->config->item('company_default_format');
			$task_id = '';
			$data['task_id'] = $task_id;
			$data['task'] =   array(
	            'general' => 0
			);
			
			$data['users'] = get_user_list();
                        $data['customers']=  getCustomerList();
			$data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
			$data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
			$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
			$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
			$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
			$data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
			$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
			$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
			$data['user_projects'] = get_user_projects(get_authenticateUserID());
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 

			$last_rember_values = get_user_last_rember_values();
			$data['last_rember_values'] = $last_rember_values;
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
			if($redirect == "weekView" || $redirect == "NextFiveDay"){
				if($last_rember_values){
					$data['calender_sorting'] = $last_rember_values->calender_sorting;
				} else {
					$data['calender_sorting'] = '1';
				}
				$default_day = get_default_day_of_company();

				$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

				$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

				$data['active_menu'] = $redirect;

				$date_arr = array();

				$data['date_arr'] = $this->getArr($start_date);
				$data['company_date_arr'] = $this->getArr($start_date,'company');

				$data["start_date"] = reset($data["date_arr"]);
				$data["end_date"] =end($data["date_arr"]);

				echo   $this->load->view($theme .'/layout/calender/calendar_week_view_ajx',$data,TRUE);
			} else {
				$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
				$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");

				echo   $this->load->view($theme .'/layout/calender/calendar_view_ajx',$data,TRUE);
			}
		} else {
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$json['date'] = strtotime($orig_date);
			$json['task_id'] = $id;
			echo json_encode($json);die;
		}
	}

	/**
         * This function will call on calender view on right-click functionality.It will fetch comment details from post method.
           And Updating data in DB then it will send notification to allocated user and admin also.
           With notification it will send mail also.And it render calender Ajax view.
         * @returns void
         */
	function add_comment(){
		$redirect = $_POST['redirect_page'];
		$task_id = $_POST['task_id'];
		$right_task_comment = htmlspecialchars($_POST['right_task_comment']);
		$post_data = json_decode($_POST['task_data'],true);

		$task_exists = chk_task_exists($task_id);
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
			$id = $task_id;
		}

		$project_id = get_project_id_from_task_id($id);
                /*Below query insert comment in Db*/
		$data = array(
			'task_comment' => $right_task_comment,
			'task_id' => $id,
			'project_id' => $project_id,
			'comment_addeby' => $this->session->userdata('user_id'),
			'comment_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('task_and_project_comments',$data);
		$cmt_id = $this->db->insert_id();


		$task_detail = get_task_detail($id);
		
		//email


		/*It will send email to user which have task assigned */
		$notification_text = $this->session->userdata('username').' commented on a task '.$task_detail['task_title'];
		
		if($task_detail['task_owner_id'] != get_authenticateUserID()){
			
			$notification_data = array(
				'task_id' => $id,
				'project_id' => $task_detail['task_project_id'],
				'notification_text' => $notification_text,
				'notification_user_id' => $task_detail['task_owner_id'],
				'notification_from' => get_authenticateUserID(),
				'is_read' => '0',
				'date_added' => date("Y-m-d H:i:s")
			);
			$this->db->insert('task_notification',$notification_data);
			
			/** send email to task owner & user  ****/
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

			$task_name = $task_detail['task_title'];
			$owner_name = usernameById($task_detail['task_owner_id']);
			if($task_detail['task_due_date']!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($task_detail['task_due_date']));
			} else {
				$task_due_date = 'N/A';
			}
			$task_description = $task_detail['task_description'];
			$project_name = $task_detail['project_title'];

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

		if($task_detail['task_owner_id']!= $task_detail['task_allocated_user_id'] && $task_detail['task_allocated_user_id'] != get_authenticateUserID()){
			
			$notification_data = array(
				'task_id' => $id,
				'project_id' => $task_detail['task_project_id'],
				'notification_text' => $notification_text,
				'notification_user_id' => $task_detail['task_allocated_user_id'],
				'notification_from' =>get_authenticateUserID(),
				'is_read' => '0',
				'date_added' => date("Y-m-d H:i:s")
			);
			$this->db->insert('task_notification',$notification_data);
			
			/* send email to task allocated user */
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='comment notification'");
			$email_temp=$email_template->row();
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;

			$email_subject=$email_temp->subject;


			$email_message=$email_temp->message;

			$user_info = get_user_info($task_detail['task_allocated_user_id']);
			$user_name = $user_info->first_name.' '.$user_info->last_name;
			$comment = $right_task_comment;
			$added_by = $this->session->userdata('username');

			$email_to = $user_info->email;
			$subscription_link = site_url();

			$task_name = $task_detail['task_title'];
			$owner_name = usernameById($task_detail['task_owner_id']);
			if($task_detail['task_due_date']!='0000-00-00'){
				$task_due_date = date($this->config->item('company_default_format'),strtotime($task_detail['task_due_date']));
			} else {
				$task_due_date = 'N/A';
			}
			$task_description = $task_detail['task_description'];
			$project_name = $task_detail['project_title'];

			$allocated_user_info = get_user_info($task_detail['task_allocated_user_id']);
			$allocate_user_name = $allocated_user_info->first_name.' '.$allocated_user_info->last_name;

			$email_subject=$email_temp->subject;

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

		$theme = getThemeName();
                if(isset($_POST['color_menu'])){
                     $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$default_day = get_default_day_of_company();
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($redirect == 'weekView' || $redirect == "NextFiveDay"){
			$data['active_menu']=$_POST['cmt_active_menu'];
			$data['week_task'] = $task_detail;

			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$data['active_menu'] = $redirect;

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = 'weekView';
			
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['calender_sorting'] = $last_rember_values->calender_sorting;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['calender_sorting'] = '1';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
                        $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
			echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);

		} else if($redirect == 'from_kanban'){
                    $data['site_setting_date'] = $this->config->item('company_default_format');
                    $data['kanban'] = $task_detail;
                    $data['color_menu'] = $color_menu;
               
                    echo $this->load->view($theme.'/layout/kanban/ajax_task_div',$data,TRUE);
                } else {
			$task_id = $_POST['task_id'];

			$data['date'] = $task_detail;
			$data['scope_id'] = $task_id;
			$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
			$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
		}

	}

	/**
         * This function will call when user click on task right-click option . It will delete task as a series or occurrence.
           Then it will check redirect page for redirection.
         * @returns void
         */
	function delete_task(){
		$task_id = $_POST['task_id'];
		$due_date = change_date_format($_POST['due_date']);
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
		$from_redirect = isset($_POST['from_redirect'])?$_POST['from_redirect']:'';
		$from = isset($_POST['from'])?$_POST['from']:'';
                $current_date=$_POST['current_date'];
		$post_data = isset($_POST['task_data'])?json_decode($_POST['task_data'],true):'';
                $chk_exist = chk_task_exists($task_id);
                $occurence_start_date=get_task_occurence_date($task_id);
                $date1=date_create($current_date);
                $date2=date_create($occurence_start_date);
                $diff=date_diff($date1,$date2);
                $days = $diff->d;
                 /**
                 *  This condition for deleted task as a future instance
                 */
                if(isset($_POST['form']) && $_POST['form']== 'delete' && $task_id !='' && $chk_exist !='0')
                {
                        $update_data = array('is_deleted'=>'1');
                        $this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
                        $this->db->update('tasks',$update_data);
                }
                if( $from == 'future')
                { 
                        $update_data = array('end_by_date'=>$current_date,'no_end_date'=>'3');
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$update_data); 
                        echo $days; die();
                }
                /**
                 *  This condition for deleted task as a ocuurence instance
                 */
		
		if($chk_exist == '0' && $from == 'ocuurence'){ 
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
			$is_virtual = "1";
                        if($id !=''){
                            $update_data = array('is_deleted'=>'1');
                            $this->db->where('(task_id = '.$id.' or (multi_allocation_task_id = '.$id.' and is_deleted = 0))');
                            $this->db->update('tasks',$update_data);
                        }
		} else {
			$id = $task_id;
			$is_virtual = "0";
                        
		}
                $data['task_id'] = $task_id;
                $data['task_title'] = get_task_title($task_id);
                /* This condition for deleted task as a series instance */
		if($from == 'series' && $id !='' && $chk_exist !='0'){
                        $update_data = array('is_deleted'=>'1');
                        //$this->db->where('task_id',$id);
                        $this->db->where('(task_id = '.$id.' or (multi_allocation_task_id = '.$id.' and is_deleted = 0))');
                        $this->db->update('tasks',$update_data);
			$update_data = array('is_deleted'=>'1');
			$this->db->where('master_task_id',$id);
			$this->db->update('tasks',$update_data);
		}

		if($from_redirect == "fiveweek"){
			$theme = getThemeName();

			$data['site_setting_date'] = $this->config->item('company_default_format');
			$task_id = '';
			$data['task_id'] = $task_id;
			$data['task'] =   array(
	            'general' => 0
			);

			$data['users'] = get_user_list();
			$data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
			$data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
			$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
			$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
			$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
			$data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
			$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
			$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
			$data['user_projects'] = get_user_projects(get_authenticateUserID());
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                        $data['customers']= getCustomerList();
			$last_rember_values = get_user_last_rember_values();
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			if($redirect == "weekView" || $redirect == "NextFiveDay"){
				
				if($last_rember_values){
					$data['calender_sorting'] = $last_rember_values->calender_sorting;
				} else {
					$data['calender_sorting'] = '1';
				}
				$default_day = get_default_day_of_company();

				$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

				$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

				$action = $_POST['action'];
				$data['active_menu'] = $_POST['active_menu'];

				$date_arr = array();

				$data['date_arr'] = $this->getArr($start_date);
				$data['company_date_arr'] = $this->getArr($start_date,'company');

				$data["start_date"] = reset($data["date_arr"]);
				$data["end_date"] =end($data["date_arr"]);
				$data['action'] = $action;

				echo   $this->load->view($theme .'/layout/calender/calendar_week_view_ajx',$data,TRUE);
			} else {
				$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
				$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");

				echo   $this->load->view($theme .'/layout/calender/calendar_view_ajx',$data,TRUE);
			}
		} else {
                    $data['response'] = 'removed';
			echo json_encode($data);die;
		}
	}
	/**
         * This function will save monthly calender task summery in DB on Ajax request.
         * @returns void
         */
	function saveShowTask(){
		$chk_remember_exist = chk_last_remember_exists();
		$show_cal_view = $_POST['show_cal_view'];
		$cal = implode(',', $show_cal_view);
		if($chk_remember_exist == '1'){
			$last_data = array(
				'show_cal_view' => $cal
			);
			$this->db->where('user_id',$this->session->userdata('user_id'));
			$this->db->update('last_remember_search',$last_data);
		} else {
			$last_data = array(
				'user_id' => $this->session->userdata('user_id'),
				'show_cal_view' => $cal,
				'kanban_team_user_id' => $user_id,
				'calender_team_user_id' =>$user_id,
				'show_cal_view' => '1',
				'calender_sorting' => '1',
				'last_calender_view' => '1',
				'cal_user_color_id'=>$cal_user_color_id
			);
			$this->db->insert('last_remember_search',$last_data);
		}
	}
        /**
         * This function will call on calender option in filter.It will fetch info for render monthly view.
     
         * @returns void
         */
	function searchTask(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		$data = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		$data['error'] = '';

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => '',
            'steps' => '',
            'files' => ''
		);

		$serializedData = $_POST['str'];
		$unserializedData = array();
		parse_str($serializedData,$unserializedData);

		if(isset($unserializedData['calender_project_id']) && $unserializedData['calender_project_id']!=''){ $calender_project_id = $unserializedData['calender_project_id']; } else { $calender_project_id = ''; }
		if(isset($unserializedData['left_task_status_id']) && $unserializedData['left_task_status_id']!=''){ $left_task_status_id = $unserializedData['left_task_status_id']; } else { $left_task_status_id = ''; }
		if(isset($unserializedData['calender_team_user_id']) && $unserializedData['calender_team_user_id']!=''){ $calender_team_user_id = $unserializedData['calender_team_user_id']; } else { $calender_team_user_id = get_authenticateUserID(); }
		if(isset($unserializedData['calender_date']) && $unserializedData['calender_date']!=''){$calender_date = change_date_format($unserializedData['calender_date']); } else { $calender_date = '0000-00-00'; }
		if($unserializedData['cal_user_color_id'] && $unserializedData['cal_user_color_id']!='0'){ $cal_user_color_id = $unserializedData['cal_user_color_id']; } else { $cal_user_color_id = "0"; }

		if($_POST){
			$chk_remember_exist = chk_last_remember_exists();

			if(isset($unserializedData['calender_project_id']) && $unserializedData['calender_project_id']!=''){ $project_id = $unserializedData['calender_project_id']; } else { $project_id = ''; }
			if(isset($unserializedData['left_task_status_id']) && $unserializedData['left_task_status_id']!= ''){ $types = implode(',', $unserializedData['left_task_status_id']); } else { $types = ''; }
			if(isset($unserializedData['calender_team_user_id']) && $unserializedData['calender_team_user_id']!=''){ $user_team_id = $unserializedData['calender_team_user_id']; } else { $user_team_id = get_authenticateUserID(); }
			if(isset($unserializedData['calender_date']) && $unserializedData['calender_date']!=''){ $calender_date = change_date_format($unserializedData['calender_date']); } else { $calender_date = '0000-00-00'; }
			if($unserializedData['cal_user_color_id'] && $unserializedData['cal_user_color_id']!='0'){ $cal_user_color_id = $unserializedData['cal_user_color_id']; } else { $cal_user_color_id = "0"; }

			if($chk_remember_exist == '1'){
				$last_data = array(
					'calender_project_id' =>$calender_project_id,
					'task_status_id' => $types,
					'calender_team_user_id' => $calender_team_user_id,
					'calender_date' => $calender_date,
					'cal_user_color_id' =>$cal_user_color_id
				);
				$this->db->where('user_id',$this->session->userdata('user_id'));
				$this->db->update('last_remember_search',$last_data);
			} else {
				$last_data = array(
					'user_id' => $this->session->userdata('user_id'),
					'calender_project_id' => $calender_project_id,
					'task_status_id' => $types,
					'calender_team_user_id' => $calender_team_user_id,
					'calender_date' => $calender_date,
					'kanban_team_user_id' => get_authenticateUserID(),
					'show_cal_view' => '1',
					'calender_sorting' => '1',
					'last_calender_view' => '1',
					'cal_user_color_id' =>$cal_user_color_id
				);
				$this->db->insert('last_remember_search',$last_data);
			}
		}
		$this->session->unset_userdata('Temp_calendar_user_id');
		$this->session->set_userdata("Temp_calendar_user_id",$calender_team_user_id);
		
		$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
		$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
		$data['calender_project_id'] = $calender_project_id;
		$data['left_task_status_id'] = $left_task_status_id;
		$data['calender_team_user_id'] = $calender_team_user_id;
		$data['cal_user_color_id'] = $cal_user_color_id;
		$data['calender_date'] = $calender_date;

		
		$data['users'] = get_user_list();
		$data['customers']=  getCustomerList();
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                
                if($this->session->userdata('Temp_calendar_user_id') == '0'){
                    $data['divisions'] = getUserDivision(get_authenticateUserID());
                    $data['departments'] = getUserDepartment(get_authenticateUserID());
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                    $data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
                    $data['capacity'] = getUserCapacity(get_authenticateUserID());
                }else{
                    $data['capacity'] = getUserCapacity($this->session->userdata('Temp_calendar_user_id'));
                    $data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
                    $data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                    $data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
                }
                if($this->session->userdata('Temp_calendar_user_id') == '#'){
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                }else{
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                }

		echo   $this->load->view($theme .'/layout/calender/calendar_view_ajx',$data,TRUE);
		
		
	}


	//******************** calender week view ***********************//
	/**
         * This function will return dates in array form for week view.
         * @param  $year
         * @param  $week
         * @param  $start
         * @returns array
         */
	function getWeekDates($year, $week, $start=true)
	{
		$date_arr = array();
     	$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week

     	for($i=1;$i<=5;$i++)
	 	{
	 	 	$to = date("Y-m-d", strtotime("{$year}-W{$week}-".$i.""));   //Returns the date of sunday in week
	 	 	array_push($date_arr,$to);
	 	}
		return $date_arr;

	}

	/**
         * This function will returns date format on request.
         * @param  $date
         * @param  $view
         * @returns array
         */
	function getArr($date,$view=''){
		$date_arr = array();
		if($view == "company"){
			$defaults = get_calender_settings($this->session->userdata('company_id'));
		} else {
			$defaults = get_calender_settings_by_user($this->session->userdata('user_id'));
		}
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

        /**
         * This function will call when user click on week view link on calender page.It will render week view with task.
           It check user authentication than it will fetch values from session for render view page.
         * @returns void
         */
	function weekView(){
            /**
             * check authentication
             */
		if(!check_user_authentication()){
			redirect('home');
		}
                /* It create array for store values for render week view page*/
		$data = array();

		$data['active_menu']='weekView';
		$data['error'] = '';
		$theme = getThemeName();+
		$this->template->set_master_template($theme .'/template2.php');

		$data['theme'] = $theme;
		$date_arr = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$default_day = get_default_day_of_company();
		
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$start_date = date('Y-m-d',strtotime($default_day.' this week'));
                if(strtotime($start_date )>  strtotime(date('Y-m-d'))){
                    $start_date = date('Y-m-d',strtotime('previous '.$default_day));
                }
		$data['date_arr'] = $this->getArr($start_date);
		$data['company_date_arr'] = $this->getArr($start_date,'company');


		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] = end($data["date_arr"]);

		$last_rember_values = get_user_last_rember_values();
		$data['last_rember_values'] = $last_rember_values;
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '';
			$data['cal_user_color_id'] = '';
                        $data['show_other_user_task'] = 0;
		}

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => '',
            'dependencies' => '',
            'steps' => '',
            'files' => '',
            'comments' => ''
		);
		$data['customers']=  getCustomerList();
		$data['users'] = get_user_list();
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                $data['all_report_user']=get_list_user_report_to_adminstartor();
                if($this->session->userdata('Temp_calendar_user_id') == '0'){
                    $data['divisions'] = getUserDivision(get_authenticateUserID());
                    $data['departments'] = getUserDepartment(get_authenticateUserID());
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                    $data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
                }else{
                    $data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
                    $data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                    $data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
                }
		$this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);
		$this->template->write_view('content_side', $theme.'/layout/calender/calendar_week_view', $data, TRUE);
		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
		
	}

	/**
         * This function will call on Ajax request.It will render week view with task.
           It check user authentication than it will fetch values from session for render view page.
         * @returns void
         */
	function weekview_ajx()
	{
		//pr($_POST);
		$theme = getThemeName();
		$data = array();
		$date_data = explode("#",$_REQUEST["mydate"]);
		$data['error'] = '';

		$start_date = $date_data[0];
		$end_date = $date_data[1];
		$action = $date_data[2];

		$date_arr = array();

		$default_day = get_default_day_of_company();

		date_default_timezone_set($this->session->userdata("User_timezone"));

		if($action == "next"){
			$starting_date = date('Y-m-d',strtotime("next ".$default_day, strtotime(str_replace(array("/"," ",","), "-", $end_date))));
			$data['date_arr'] = $this->getArr($starting_date);
			$data['company_date_arr'] = $this->getArr($starting_date,'company');
			$data['last'] = isset($_POST['last_day'])?$_POST['last_day']:'';
		}
		if($action == "prev"){
			$starting_date = date('Y-m-d',strtotime("last ".$default_day, strtotime(str_replace(array("/"," ",","), "-", $start_date))));
			$data['date_arr'] = $this->getArr($starting_date);
			$data['company_date_arr'] = $this->getArr($starting_date,'company');
			$data['last'] = isset($_POST['last_day'])?$_POST['last_day']-(2*get_no_of_working_days()):'';
		}
                if($action == "current"){
			$starting_date = date('Y-m-d',strtotime($start_date));
			$data['date_arr'] = $this->getArr($starting_date);
			$data['company_date_arr'] = $this->getArr($starting_date,'company');
			$data['last'] = isset($_POST['last_day'])?$_POST['last_day']-(2*get_no_of_working_days()):'';
		}
		
		$data['active_menu']='weekView';

		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] = end($data["date_arr"]);
		$data['action'] = $action;

		$last_rember_values = get_user_last_rember_values();
		$data['last_rember_values'] = $last_rember_values;
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '1';
			$data['cal_user_color_id'] = '';
                        $data['show_other_user_task'] =0;
		}

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] = array(
            'general' => '',
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0
		);
		$data['customers']=  getCustomerList();
		$data['users'] = get_user_list();
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                $data['all_report_user']=get_list_user_report_to_adminstartor();
                if($this->session->userdata('Temp_calendar_user_id') == '0'){
                    $data['divisions'] = getUserDivision(get_authenticateUserID());
                    $data['departments'] = getUserDepartment(get_authenticateUserID());
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                    $data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
                }else{
                    $data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
                    $data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                    $data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
                }
		echo $this->load->view($theme .'/layout/calender/calendar_week_view_ajx',$data,TRUE);
	}

	/**
         * This function will call on footer option when user filters date from week or nextfive day view.
           Using calender option, it will render week or nextfive day view.
         * @returns void
         */
	function filterWeek(){

		$theme = getThemeName();
		$data = array();
		$data['theme'] = $theme;
		$date = $_POST["date"];
		$data['error'] = '';
		$default_day = get_default_day_of_company();
		$user_working_days = get_user_no_of_working_days();
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$day = date("l",strtotime(str_replace(array("/"," ",","), "-", $date)));
		if($day == $default_day){
			$date = change_date_format($date);
		} else {
			$date = date('Y-m-d', strtotime("last ".$default_day."",strtotime(str_replace(array("/"," ",","), "-", $date))));
		}


		$data['date_arr'] = $this->getArr($date);
		$data['company_date_arr'] = $this->getArr($date,'company');
		if($_POST['redirect'] = "NextFiveDayView"){
			$data['active_menu'] = 'NextFiveDay';
		} else {
			$data['active_menu'] =  'weekView';
		}
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] = end($data["date_arr"]);

		$last_rember_values = get_user_last_rember_values();
		$data['last_rember_values'] = $last_rember_values;
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '';
			$data['cal_user_color_id'] = '';
                        $data['show_other_user_task'] = 0;
		}

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0
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

			$data['users'] = get_user_list($data['task']['general']['task_division_id'],$data['task']['general']['task_department_id'],$data['task']['general']['task_skill_id'],$data['task']['general']['task_staff_level_id']);
		} else {
			$data['users'] = get_user_list();
		}
                $data['customers']=  getCustomerList();
		$data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
		$data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
		$data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
		echo $this->load->view($theme .'/layout/calender/calendar_week_view_ajx',$data,TRUE);
		
	}

	/**
         * On week view, it's used to update the order of tasks. As on week or nextfiveday view user can drag-drop the tasks to move it on appropriate position.
         * So once user drop the task on selected position at the same time this function update the order of tasks in db.
         * @returns jsonObject
         */
	function setWeekOrder(){
                /*It store data in array */
		$theme = getThemeName();
		$data = array();
		$default_day = get_default_day_of_company();
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

		$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;
		//$end_date = $_POST['end_date'];
		$action = $_POST['action'];
		$data['active_menu'] = $_POST['active_menu'];

		$date_arr = array();

		$data['date_arr'] = $this->getArr($start_date);
		$data['company_date_arr'] = $this->getArr($start_date,'company');

		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] =end($data["date_arr"]);
		$data['action'] = $action;


		$date = date('Y-m-d',$_POST['status']);
		$order = $_POST['order'];
		$scope_id = $_POST['scope_id'];
		if(isset($_POST['task_data']) && $_POST['task_data']!=''){
			$post_data = json_decode($_POST['task_data'],true);
		} else {
			$post_data = '';
		}

		$data['active_menu'] = $_POST['active_menu'];
		$data['site_setting_date'] = $this->config->item('company_default_format');

		if($post_data){
			if(strpos($scope_id, 'child') !== false){
				$chk_ext = chk_virtual_recurrence_exists($post_data['master_task_id'],$post_data['task_orig_scheduled_date']);
				if($chk_ext!='' && $chk_ext['is_deleted'] == '0'){
					$inserted_id = $chk_ext['task_id'];
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
				}

			} else {
				$inserted_id = $scope_id;
			}

			$order_task = array();
			if($order){
				$step1 = explode('&', $order);
				$i = 1;

				foreach($step1 as $step){

					if(strpos($step, 'child') !== false){
						$ids = str_replace("main_child_","",$step);
						$virtual_ids = explode('[]=', $ids);
						$main_id = $virtual_ids[0];
						$custom_id = 'child_'.$virtual_ids[0].'_'.$virtual_ids[1];
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
						$user_wise_data = array(
							'calender_order' => $i
						);
						$this->db->where('task_id',$task_id);
						$this->db->where('user_id',$this->session->userdata("Temp_calendar_user_id"));
						$this->db->update('user_task_swimlanes',$user_wise_data);
					}
					$i++;
				}
			}
			$json['id'] = $inserted_id;
			$json['title'] = $post_data['task_title'];
			$json['task_time_spent'] = $post_data['task_time_spent'];
			echo json_encode($json);die;
		} else {
			echo "no_data";die;
		}


	}

	/**
         * It's used to update the scope of tasks.As on week view user can drag-drop the task on appropriate position.
           Once user drop the task on select date at that time this function update scope of task in DB.
         * @returns jsonObject
         */
	function UpdateWeekScope(){

		$theme = getThemeName();
		$data = array();
		$default_day = get_default_day_of_company();
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

		$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

		$action = $_POST['action'];
		$data['active_menu'] = $_POST['active_menu'];

		$date_arr = array();

		$data['date_arr'] = $this->getArr($start_date);
		$data['company_date_arr'] = $this->getArr($start_date,'company');

		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] =end($data["date_arr"]);
		$data['action'] = $action;



		$scope_id = $_POST['scope_id'];
		$orig_date = date('Y-m-d',$_POST['status']);
		$post_data = json_decode($_POST['task_data'],true);

		$date = date("Y-m-d",strtotime($orig_date));


		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu'] = $_POST['active_menu'];

		$chk_exist = chk_task_exists($scope_id);
		if($chk_exist == '0'){
			$chk_ext = chk_virtual_recurrence_exists($post_data['master_task_id'],$post_data['task_orig_scheduled_date']);
			if($chk_ext!='' && $chk_ext['is_deleted'] == '0'){
				$id = $chk_ext['task_id'];
			} else {
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
			}
		} else {
			$id = $scope_id;
		}
                /*This query update schedule date of task in db*/
		$task_data = array(
			'task_scheduled_date' => $date,
			'is_scheduled' => '1'
		);
		$this->db->where('task_id',$id);
		$this->db->update('tasks',$task_data);



		$json['id'] = $id;
		$json['title'] = $post_data['task_title'];
		$json['task_time_spent'] = $post_data['task_time_spent'];
		echo json_encode($json);die;
	}

	/**
         * It's used to search week task on calendar.On week or nextfivedays view when user select date at that
           time this function is called for render new week or nextfivedays view page with tasks on Ajax request.
         * @returns void
         */
	function searchWeekTask(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		$data = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		$data['error'] = '';
		$action = $_POST['action'];
		$data['active_menu'] = $_POST['active_menu'];

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0,
            'history' => 0
		);
                /*Get user details */
		$data['users'] = get_user_list();
		$data['customers']=  getCustomerList();
		$data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
//		$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
		$data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                
		$serializedData = $_POST['str'];
		$unserializedData = array();
		parse_str($serializedData,$unserializedData);

		if(isset($unserializedData['calender_project_id']) && $unserializedData['calender_project_id']!=''){ $calender_project_id = $unserializedData['calender_project_id']; } else { $calender_project_id = ''; }
		if(isset($unserializedData['left_task_status_id']) && $unserializedData['left_task_status_id']!=''){ $left_task_status_id = $unserializedData['left_task_status_id']; } else { $left_task_status_id = ''; }
		if(isset($unserializedData['calender_team_user_id']) && $unserializedData['calender_team_user_id']!=''){ $calender_team_user_id = $unserializedData['calender_team_user_id']; } else { $calender_team_user_id = get_authenticateUserID();; }
		if(isset($unserializedData['calender_date']) && $unserializedData['calender_date']!=''){$calender_date = change_date_format($unserializedData['calender_date']); } else { $calender_date = '0000-00-00'; }
		if($unserializedData['cal_user_color_id'] && $unserializedData['cal_user_color_id']!='0'){ $cal_user_color_id = $unserializedData['cal_user_color_id']; } else { $cal_user_color_id = "0"; }

		if($_POST){
			$chk_remember_exist = chk_last_remember_exists();

			if(isset($unserializedData['calender_project_id']) && $unserializedData['calender_project_id']!=''){ $project_id = $unserializedData['calender_project_id']; } else { $project_id = ''; }
			if(isset($unserializedData['left_task_status_id']) && $unserializedData['left_task_status_id']!= ''){ $types = implode(',', $unserializedData['left_task_status_id']); } else { $types = ''; }
			if(isset($unserializedData['calender_team_user_id']) && $unserializedData['calender_team_user_id']!=''){ $user_team_id = $unserializedData['calender_team_user_id']; } else { $user_team_id = get_authenticateUserID();; }
			if(isset($unserializedData['calender_date']) && $unserializedData['calender_date']!=''){ $calender_date = change_date_format($unserializedData['calender_date']); } else { $calender_date = '0000-00-00'; }
			if($unserializedData['cal_user_color_id'] && $unserializedData['cal_user_color_id']!='0'){ $cal_user_color_id = $unserializedData['cal_user_color_id']; } else { $cal_user_color_id = "0"; }
                       
                        if($chk_remember_exist == '1'){
				$last_data = array(
					'calender_project_id' => $project_id,
					'task_status_id' => $types,
					'calender_team_user_id' => $user_team_id,
					'calender_date' => $calender_date,
					'cal_user_color_id' =>$cal_user_color_id
				);
				$this->db->where('user_id',$this->session->userdata('user_id'));
				$this->db->update('last_remember_search',$last_data);
			} else {
				$last_data = array(
					'user_id' => $this->session->userdata('user_id'),
					'calender_project_id' => $project_id,
					'task_status_id' => $types,
					'calender_team_user_id' => $user_team_id,
					'calender_date' => $calender_date,
					'kanban_team_user_id' => get_authenticateUserID(),
					'show_cal_view' => '1',
					'calender_sorting' => '1',
					'last_calender_view' => '1',
					'cal_user_color_id' =>$cal_user_color_id
				);
				$this->db->insert('last_remember_search',$last_data);
			}
		}

		$this->session->set_userdata("Temp_calendar_user_id",$calender_team_user_id);

		$data['calender_project_id'] = $calender_project_id;
		$data['left_task_status_id'] = $left_task_status_id;
		$data['calender_team_user_id'] = $calender_team_user_id;
		$data['calender_date'] = $calender_date;
		$data['cal_user_color_id'] = $cal_user_color_id;
		$last_rember_values = get_user_last_rember_values();
		$data['last_rember_values'] = $last_rember_values;
		if($last_rember_values){
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_sorting'] = '1';
                        $data['show_other_user_task'] = 0;
		}
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                                 $data['all_report_user']=get_list_user_report_to_adminstartor();
		if($_POST['active_menu'] == "NextFiveDay"){

			$default_day = get_default_day_of_company();
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$start_date_old = date('Y-m-d',strtotime(date("Y-m-d")));
			
			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$data['date_arr'] = $this->getDateArr($start_date);
			$data['company_date_arr'] = $this->getDateArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] = end($data["date_arr"]);

			$action = $_POST['action'];
			$data['active_menu'] = $_POST['active_menu'];

			$data['action'] = $action;
		} else {
			$default_day = get_default_day_of_company();

			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$action = $_POST['action'];
			$data['active_menu'] = $_POST['active_menu'];

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = $action;
		}
                if($this->session->userdata('Temp_calendar_user_id') == '#'){
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                }else{
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                }
		echo   $this->load->view($theme .'/layout/calender/calendar_week_view_ajx',$data,TRUE);
	}


	/**
         * This function is called when user select sorting option on filter.It will create view page according to selected option from list.
           And it will update at the same time last_member table in db.
         * @returns create view page of week
         */
	function saveSortingTask(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		$data = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		$data['user'] = get_user_info(get_authenticateUserID());
		$data['error'] = '';
		$data['active_menu'] = $_POST['active_menu'];

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0,
            'history' => 0
		);
		
			$data['users'] = get_user_list();
		$data['customers']=  getCustomerList();
		$data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
		$data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
		$data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 

		$chk_remember_exist = chk_last_remember_exists();
		$calender_sorting = $_POST['id'];
		if($chk_remember_exist == '1'){
			$last_data = array(
				'calender_sorting' => $calender_sorting
			);
			$this->db->where('user_id',$this->session->userdata('user_id'));
			$this->db->update('last_remember_search',$last_data);
		} else {
			$last_data = array(
				'user_id' => $this->session->userdata('user_id'),
				'calender_sorting' => $calender_sorting
			);
			$this->db->insert('last_remember_search',$last_data);
		}

		$last_rember_values = get_user_last_rember_values();
		$data['last_rember_values'] = $last_rember_values;
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '1';
			$data['cal_user_color_id'] = '';
                        $data['show_other_user_task'] = 0;
		}
		$default_day = get_default_day_of_company();

		$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));


		$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

		$action = $_POST['action'];

		$date_arr = array();

		$data['date_arr'] = $this->getArr($start_date);
		$data['company_date_arr'] = $this->getArr($start_date,'company');
		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] =end($data["date_arr"]);
		$data['action'] = $action;
		echo   $this->load->view($theme .'/layout/calender/calendar_week_view_ajx',$data,TRUE);
	}

        /**
         * This function is used for save calendar summery in db.
         * @returns void
         */
	
	function save_calender_view(){

		$chk_remember_exist = chk_last_remember_exists();
		$last_calender_view = $_POST['val'];
                /**
                 * check remember id  is exist or not
                 */
		if($chk_remember_exist == '1'){
			$last_data = array(
				'last_calender_view' => $last_calender_view
			);
			$this->db->where('user_id',$this->session->userdata('user_id'));
			$this->db->update('last_remember_search',$last_data);
		} else {
			$last_data = array(
				'user_id' => $this->session->userdata('user_id'),
				'last_calender_view' => $last_calender_view
			);
			$this->db->insert('last_remember_search',$last_data);
		}
	}


	/************ next 5 days *************/
	/**
         * This function returns array of date for next fivedays.It check request for company or user, than it will return array of date.
         * @param  $start_date
         * @param  $from
         * @returns array
         */
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

	/**
         * This function is used for returns previous five dates.
         * @param $start_date
         * @param  $from
         * @return array
         */
	function getPrevDateArr($start_date,$from=''){
		$date_arr = array();
		if($from == "company"){
			$defaults = get_calender_settings($this->session->userdata('company_id'));
		} else {
			$defaults = get_calender_settings_by_user($this->session->userdata('user_id'));
		}
		$day = date('l');
		$i = 0;
		while($i<5){

			if($defaults){
				$start_date = date('Y-m-d',strtotime("-1 days",strtotime(str_replace(array("/"," ",","), "-", $start_date))));
				$day = date("l",strtotime(str_replace(array("/"," ",","), "-", $start_date)));
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
			} else {
				$start_date = date('Y-m-d',strtotime(str_replace(array("/"," ",","), "-", $start_date) . ' +1 Weekday'));
				array_push($date_arr,$start_date);
				$i++;
				$start_date = date('Y-m-d',strtotime(str_replace(array("/"," ",","), "-", $start_date) . ' +1 Weekday'));
			}


		}
		sort($date_arr);
		return $date_arr;
	}

        /**
         * When user click on NextFiveDay link at that time this function is called to display next_five_days view with tasks.
           It will fetch data appropriate data for create view page for NextFiveDays.
         * @returns void
         */
	function NextFiveDayView(){
            /**
             * check authentication
             */
		if(!check_user_authentication()){
			redirect('home');
		}
		$data = array();

		$data['active_menu']='NextFiveDay';
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template2.php');

		$data['theme'] = $theme;
		$date_arr = array();
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$default_day = get_default_day_of_company();

		date_default_timezone_set($this->session->userdata("User_timezone"));
		$start_date = date('Y-m-d');

		$data['date_arr'] = $this->getDateArr($start_date);
		$data['company_date_arr'] = $this->getDateArr($start_date,'company');

		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] = end($data["date_arr"]);

		$last_rember_values = get_user_last_rember_values();
		$data['last_rember_values'] = $last_rember_values;
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '';
			$data['cal_user_color_id'] = '0';
                        $data['show_other_user_task'] = 0;
		}

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0,
            'history' => 0
		);
                /**
                 * get all values from seesion and save in data array
                 */
                $data['customers']=  getCustomerList();
		$data['users'] = get_user_list();
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                                $data['all_report_user']=get_list_user_report_to_adminstartor();
                if($this->session->userdata('Temp_calendar_user_id') == '0'){
                    $data['divisions'] = getUserDivision(get_authenticateUserID());
                    $data['departments'] = getUserDepartment(get_authenticateUserID());
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                    $data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
                }else{
                    $data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
                    $data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                    $data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
                }
		/*
                 * create calender view for next five day
                 */
		$this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);
		$this->template->write_view('content_side', $theme.'/layout/calender/calendar_week_view', $data, TRUE);
		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
		
		
	}

	/**
         * This function is used to display next five day view on Ajax request with tasks.
           It will fetch data from session and methods for create view page.
         * @returns void
         */
	function NextFiveDay_ajx(){
		$theme = getThemeName();
		$data = array();
		$date_data = explode("#",$_REQUEST["mydate"]);


		$start_date = $date_data[0];
		$end_date = $date_data[1];
		$action = $date_data[2];

		$date_arr = array();

		$default_day = get_default_day_of_company();

		if($action == "next"){
			$data['date_arr'] = $this->getDateArr($end_date,'ajax');
			$data['company_date_arr'] = $this->getDateArr($end_date,'companyAjax');
			$data['last'] = isset($_POST['last_day'])?$_POST['last_day']:'';
		}
		
		if($action == "prev"){
			$data['date_arr'] = $this->getPrevDateArr($start_date);
			$data['company_date_arr'] = $this->getPrevDateArr($start_date,'company');
			$data['last'] = isset($_POST['last_day'])?$_POST['last_day']-(2*get_no_of_working_days()):'';
		}
		

	   	$data['active_menu']='NextFiveDay';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] =end($data["date_arr"]);
		$data['action'] = $action;

		$last_rember_values = get_user_last_rember_values();
		$data['last_rember_values'] = $last_rember_values;
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '1';
			$data['cal_user_color_id'] = '0';
                        $data['show_other_user_task'] = 0;
		}

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0,
            'history' => 0
		);
                $data['customers']=  getCustomerList();
		$data['users'] = get_user_list();
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                if($this->session->userdata('Temp_calendar_user_id') == '0'){
                    $data['divisions'] = getUserDivision(get_authenticateUserID());
                    $data['departments'] = getUserDepartment(get_authenticateUserID());
                    $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                    $data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
                }else{
                    $data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
                    $data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
                    $data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
                    $data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
                }
		echo   $this->load->view($theme .'/layout/calender/calendar_week_view_ajx',$data,TRUE);
		
	}


	/****************** Calneder five week view from left side bar ******************/
	/**
         * When user click on next or previous button on calendar view at the same time this function will call
           and it will return next/previous five days from current calendar date.
         * @param  $date
         * @returns array
         */
	function getFiveWeekDates($date){
		$start_date = date('Y-m-d', strtotime("last monday",strtotime('-2 weeks', strtotime(str_replace(array("/"," ",","), "-", $date)))));
		$end_date = date('Y-m-d', strtotime("this sunday",strtotime('+2 weeks', strtotime(str_replace(array("/"," ",","), "-", $date)))));

		$date_arr = array();
		while(strtotime($start_date)<=strtotime($end_date)){
			array_push($date_arr,$start_date);
			$start_date = date("Y-m-d",strtotime("+1 days",strtotime($start_date)));
		}

		return $date_arr;
	}

	/**
         * This function will create next five week view.
         * @returns void
         */
	function FiveWeekView(){
            /*
             * check user access
             */
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		$data = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		$data['user'] = get_user_info(get_authenticateUserID());
		$data['error'] = '';

	    $date = change_date_format($_POST['date']);
		$year = date("Y");
		$date_arr = $this->getFiveWeekDates($date);


		$data['year'] = $year;
		$data['start_date'] = reset($date_arr);
		$data['end_date'] = end($date_arr);
		$data['date_arr'] = $date_arr;

		$data['from_view'] = $_POST['from'];
		$data['ajax_year'] = isset($_POST['year'])?$_POST['year']:date("Y");
		$data['ajax_month'] = isset($_POST['month'])?$_POST['month']:date("m");
		$data['ajax_start_date'] = isset($_POST['start_date'])?$_POST['start_date']:'';
		$data['ajax_end_date'] = isset($_POST['end_date'])?$_POST['end_date']:'';
		$data['from_page'] = isset($_POST['redirect'])?$_POST['redirect']:'';

		$task_id = '';
		$data['task_id'] = $task_id;
		$data['task'] =   array(
            'general' => 0,
            'dependencies' => 0,
            'steps' => 0,
            'files' => 0,
            'comments' => 0,
            'history' => 0
		);
		
		/*
                 * save all info in data array fro render view
                 */
		$data['users'] = get_user_list();
		$data['customers']=  getCustomerList();
		$data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
		$data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
		$data['is_color_exist'] = is_user_color_exist($this->session->userdata('Temp_calendar_user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');

		$last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['show_cal_view'] = $last_rember_values->show_cal_view;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '1';
			$data['show_cal_view'] =  '';
			$data['cal_user_color_id'] = '0';
		}

		echo $this->load->view($theme.'/layout/calender/FiveWeekView', $data, TRUE);
	}
	/**
	 * This function is used to display day wise task list.
         * @returns void
	*/
	function monthly_day_view(){
		$theme = getThemeName();

		date_default_timezone_set($this->session->userdata("User_timezone"));
		$date = date("Y-m-d",$_POST['date']);

		$task_id = $_POST['task_id'];
		$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
		$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
		$data['from'] = isset($_POST['from'])?$_POST['from']:'';
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;

		$task_data = get_task_detail($task_id);

		$is_div_valid = 0;
		$data['last_rember_values'] = $last_remembers = get_user_last_rember_values();
		if($last_remembers){
			$task_status_id = $last_remembers->task_status_id;
			$team_user_id = $last_remembers->calender_team_user_id;
			$calender_project_id = $last_remembers->calender_project_id;
			$cal_user_color_id = $last_remembers->cal_user_color_id;
			$status_id = '';
			$project_id = '';
			if($calender_project_id){
				$project_id = explode(',', $calender_project_id);
			}
			if($task_status_id){
				$status_id = explode(',', $task_status_id);
			}

			if($project_id!='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 1";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 2";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 3";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 4";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id=='0'){
				//echo "in 5";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 6";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 7";
				if($task_data['task_status_id'] == '0'&& $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else {
				//echo "in 8";
				if($task_data['task_project_id'] == '0' && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				}
			}
		}

		if($is_div_valid){
			$order_task[] = $task_data;
			$data['order_task'] = $order_task;
		} else {
			$data['order_task'] = '';
		}
		$data['date'] = $date;
		$data['is_data'] = 'yes';
		$data['wd'] = isset($_POST['wd'])?$_POST['wd']:'';
		$this->load->view($theme.'/layout/calender/monthly_day_view_ajax',$data);

	}

	/**
         * When user change tasks property like color,due date at the same time this function will call .
          It will create new monthly calendar view  on Ajax request.
         * 
         * @returns create view
         */
	function set_update_task(){

		$theme = getThemeName();
		$task_id = $_POST['task_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');
                
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$data['scope_id'] = $task_id;
		$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
		$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
		
		$task_data = get_task_detail($task_id);
		$is_div_valid = 0;
		$data['last_rember_values'] = $last_remembers = get_user_last_rember_values();
                $data['all_report_user']=get_list_user_report_to_adminstartor();
		if($last_remembers){
			$task_status_id = $last_remembers->task_status_id;
			$team_user_id = $last_remembers->calender_team_user_id;
			$calender_project_id = $last_remembers->calender_project_id;
			$cal_user_color_id = $last_remembers->cal_user_color_id;
			$status_id = '';
			$project_id = '';
			if($calender_project_id){
				$project_id = explode(',', $calender_project_id);
			}
			if($task_status_id){
				$status_id = explode(',', $task_status_id);
			}

			if($project_id!='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 1";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 2";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 3";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 4";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id=='0'){
				//echo "in 5";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 6";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 7";
				if($task_data['task_status_id'] == '0'&& $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else {
				//echo "in 8";
				if($task_data['task_project_id'] == '0' && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				}
			}
		}

		if($is_div_valid){
			$data['date'] = $task_data;
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);die;
		} else {
			$data['date'] = $task_data;
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);die;
		}
	}

	/**
         * When user will update on task at Week View or NextFiveDay calendar page
           at the same time this method call for create updated view page.
         
         * @returns create week view
         */
	function set_weekly_update_task(){
		$theme = getThemeName();

		$default_day = get_default_day_of_company();

		$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

		$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

		$action = $_POST['action'];
		$data['active_menu'] = $_POST['active_menu'];

		$date_arr = array();

		$data['date_arr'] = $this->getArr($start_date);
		$data['company_date_arr'] = $this->getArr($start_date,'company');

		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] =end($data["date_arr"]);
		$data['action'] = $action;

		$task_id = $_POST['task_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu']=$_POST['active_menu'];
                $data['all_report_user']=get_list_user_report_to_adminstartor();
                if(isset($_POST['color_menu'])){
                            $color_menu=$_POST['color_menu'];
                }else{
                            $color_menu='true';
                }
                $data['color_menu']=$color_menu;

		$task_data = get_task_detail($task_id);
                $is_div_valid = 0;
		$last_remembers = get_user_last_rember_values();
		if($last_remembers){
			$data['calender_project_id'] = $last_remembers->calender_project_id;
			$data['left_task_status_id'] = $last_remembers->task_status_id;
			$data['calender_team_user_id'] = $last_remembers->calender_team_user_id;
			$data['calender_date'] = $last_remembers->calender_date;
			$data['calender_sorting'] = $last_remembers->calender_sorting;
			$data['cal_user_color_id'] = $last_remembers->cal_user_color_id;
                        $data['show_other_user_task'] = $last_remembers->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '1';
			$data['cal_user_color_id'] = '0';
                        $data['show_other_user_task'] = 0;
		}
		if($last_remembers){
			$task_status_id = $last_remembers->task_status_id;
			$team_user_id = $last_remembers->calender_team_user_id;
			$calender_project_id = $last_remembers->calender_project_id;
			$cal_user_color_id = $last_remembers->cal_user_color_id;
			$status_id = '';
			$project_id = '';
                        $data['footer_user_id']=$team_user_id;
			if($calender_project_id){
				$project_id = explode(',', $calender_project_id);
			}
			if($task_status_id){
				$status_id = explode(',', $task_status_id);
			}

			if($project_id!='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 1";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 2";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 3";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 4";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id=='0'){
				//echo "in 5";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 6";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 7";
				if($task_data['task_status_id'] == '0'&& $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else {
				//echo "in 8";
				if($task_data['task_project_id'] == '0' && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				}
			}
		}
               
		if($is_div_valid){
			$data['week_task'] = $task_data;
                        $this->load->view($theme.'/layout/calender/weekly_task_div',$data);
		} else {
			$data['week_task'] = $task_data;
                        $this->load->view($theme.'/layout/calender/weekly_task_div',$data);
		}
	}

	/**
         * On monthly view,this function is used to show recurrence tasks on calendar page.
           According due date,it will display task in monthly view.
         * @returns void
         */
	function set_monthly_update_div_for_task(){
		$theme = getThemeName();

		$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
		$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");

		$firstDay = @getdate(mktime(0,0,0,$data['month'],1,$data['year']));
		$lastDay  = @getdate(mktime(0,0,0,$data['month']+1,0,$data['year']));

		$weekStartedDay = get_default_day_no_of_company();

		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$max_empty_days = $firstDay['wday']-($weekStartedDay-1);
		if($max_empty_days<0){
			$max_empty_days = 7 + $max_empty_days;
		}

		$last_empty_days = ($weekStartedDay - $lastDay['wday']) - 2;
		if($last_empty_days<0){
			$last_empty_days = 7 + $last_empty_days;
		}

		$start_date = date("Y-m-d",strtotime("-".$max_empty_days." days", $firstDay[0]));
		$end_date = date("Y-m-d",strtotime("+".$last_empty_days." days",$lastDay[0]));

		$task_id = $_POST['task_id'];


		$task_data = get_task_detail($task_id);

		$is_div_valid = 0;
		$last_remembers = get_user_last_rember_values();
		if($last_remembers){
			$task_status_id = $last_remembers->task_status_id;
			$team_user_id = $last_remembers->calender_team_user_id;
			$calender_project_id = $last_remembers->calender_project_id;
			$cal_user_color_id = $last_remembers->cal_user_color_id;
			$status_id = '';
			$project_id = '';
			if($calender_project_id){
				$project_id = explode(',', $calender_project_id);
			}
			if($task_status_id){
				$status_id = explode(',', $task_status_id);

			}

			if($project_id!='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 1";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 2";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 3";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 4";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id=='0'){
				//echo "in 5";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 6";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 7";
				if($task_data['task_status_id'] == '0'&& $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else {
				//echo "in 8";
				if($task_data['task_project_id'] == '0' && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				}
			}
		}

		//company off days array
		$off_days_arr = array();
		$off_days = get_company_offdays();
		if($off_days!=''){
			$off_days_arr = explode(',', $off_days);
		}
		
		if($is_div_valid){
		
			if($task_data['frequency_type'] == "recurrence"){
				$re_data = monthly_recurrence_logic($task_data,$start_date,$end_date,$off_days);
				date_default_timezone_set($this->session->userdata("User_timezone"));
                                $status = get_taskStatus($this->session->userdata('company_id'),'Active');
				foreach($re_data as $row){
					$sortclass = "sortable";
					if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $row['task_scheduled_date'])))),$off_days_arr) ){
						$sortclass = 'unsorttd';
					}
					$div['re_data'] = $row;
					$div['div_id'] = strtotime($row['task_scheduled_date']);
					$div['tmezone_day'] = date("j",strtotime(str_replace(array("/"," ",","), "-", $row['task_scheduled_date'])));
					$div['tmezone_time'] = strtotime($row['task_scheduled_date']);
					$div['tmezone_scheduled_date'] = date($this->config->item('company_default_format'),strtotime($row['task_scheduled_date']));
					$div['task_due_date_time'] = strtotime($row['task_due_date']);
					$div['today_time'] = strtotime(date("Y-m-d"));
					$div['strtotime_start_date'] = strtotime($start_date);
					$div['strtotime_end_date'] = strtotime($end_date);
					$div['user_capacity'] = get_user_capacity(date('D',strtotime(str_replace(array("/"," ",","), "-", $row['task_scheduled_date']))),get_authenticateUserID());
					$div['status_name'] = get_task_status_name_by_id($row['task_status_id']);
					$div['sort_class'] = $sortclass;
					$div['color_code'] = get_task_color_code($row['color_id']);
					$div['outside_color_code'] = get_outside_color_code($row['color_id']);
                                        $color_codes = get_user_color_codes($div['re_data']['task_allocated_user_id']);
                                        $context['user_colors'] = $color_codes;
                                        $context['task_status'] = $status;
                                        $context['color_menu'] = 'true';
                                        //$context['completed_depencencies'] = $div['re_data']['completed_depencencies'];
                                        $context['date'] = $div['div_id'];
                                        $context['start_date'] = $div['strtotime_start_date'];
                                        $context['strtotime_end_date'] = $div['strtotime_end_date'];
                                        //$context['is_master_deleted'] = $div['is_chk'];
                                        $context['master_task_id'] = $div['re_data']['master_task_id'];
                                        $context['swimlane_id'] = $div['re_data']['swimlane_id'];
                                        $context['task_due_date'] = $div['re_data']['task_due_date'];
                                        $context['task_scheduled_date'] = $div['re_data']['task_scheduled_date'];
                                        $context['task_id'] = $div['re_data']['task_id'];
                                        $context['task_owner_id'] = $div['re_data']['task_owner_id'];
                                        $context['before_status_id'] = '';
                                        $context['active_menu'] = 'from_calendar';
                                        $context['chk_watch_list'] = $task_data['watch'];
                                        if($this->session->userdata('Temp_calendar_user_id')== '0'){
                                            $user_swimlanes = get_user_swimlanes(get_authenticateUserID());
                                        }else{
                                            $user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id'));
                                        }
                                        $context['user_swimlanes'] = $user_swimlanes;
                                        $div['context_menu'] = htmlspecialchars(json_encode($context));
					$final_div[] = $div;
				}
                                
				echo json_encode($final_div);die;
			} else {
				die;
			}
		} else {
			die;
		}
		die;
	}

	/**
         * On week view,this function is used to show recurrence tasks on calendar weekview page.
           According due date,it will display task in week view.
         * @returns void
         */
	function set_weekly_update_div_for_task(){
		$theme = getThemeName();

		$default_day = get_default_day_of_company();
                $s3_display_url = $this->config->item('s3_display_url');
                $bucket = $this->config->item('bucket_name');
		$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

		$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

		$action = $_POST['action'];
		$data['active_menu'] = $_POST['active_menu'];

		$date_arr = array();

		$data['date_arr'] = $this->getArr($start_date);
		$data['company_date_arr'] = $this->getArr($start_date,'company');

		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] =end($data["date_arr"]);
		$data['action'] = $action;

		$task_id = $_POST['task_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu']=$_POST['active_menu'];

		$final_div = array();
		$task_data = get_task_detail($task_id);
		
		$is_div_valid = 0;
		$last_remembers = get_user_last_rember_values();
		if($last_remembers){
			$data['calender_project_id'] = $last_remembers->calender_project_id;
			$data['left_task_status_id'] = $last_remembers->task_status_id;
			$data['calender_team_user_id'] = $last_remembers->calender_team_user_id;
			$data['calender_date'] = $last_remembers->calender_date;
			$data['calender_sorting'] = $last_remembers->calender_sorting;
			$data['cal_user_color_id'] = $last_remembers->cal_user_color_id;
                        $data['show_other_user_task'] = $last_remembers->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '1';
			$data['cal_user_color_id'] = '0';
                        $data['show_other_user_task'] = 0;
		}
		if($last_remembers){
			$task_status_id = $last_remembers->task_status_id;
			$team_user_id = $last_remembers->calender_team_user_id;
			$calender_project_id = $last_remembers->calender_project_id;
			$cal_user_color_id = $last_remembers->cal_user_color_id;
			$status_id = '';
			$project_id = '';

			if($calender_project_id){
				$project_id = explode(',', $calender_project_id);
			}
			if($task_status_id){
				$status_id = explode(',', $task_status_id);
			}

			if($project_id!='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 1";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id!='0'){
				//echo "in 2";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 3";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 4";
				if(in_array('all',$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array('all',$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array("all", $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && in_array($task_data['task_status_id'], $status_id) && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id !='' && $status_id=='' && $cal_user_color_id=='0'){
				//echo "in 5";
				if(in_array('all',$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_project_id'],$project_id) && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id!='' && $cal_user_color_id=='0'){
				//echo "in 6";
				if(in_array("all", $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else if(in_array($task_data['task_status_id'], $status_id) && $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				} else {

				}
			} else if($project_id =='' && $status_id=='' && $cal_user_color_id!='0'){
				//echo "in 7";
				if($task_data['task_status_id'] == '0'&& $task_data['task_project_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id==$task_data['color_id']){
					$is_div_valid = 1;
				} else {

				}
			} else {
				//echo "in 8";
				if($task_data['task_project_id'] == '0' && $task_data['task_status_id'] == '0' && $task_data['task_allocated_user_id'] == $team_user_id && $cal_user_color_id=='0'){
					$is_div_valid = 1;
				}
			}
		}
		$profile = get_task_owner_image($task_data['task_owner_id']);
                $name = 'upload/user/'.$profile->profile_image;
                if(($profile->profile_image != '' || $profile->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) { 
                 $owner_image = $s3_display_url.$name;
                } else {
                 $owner_image = $s3_display_url."upload/user/no_image.jpg";
                } 
                
                
		if($is_div_valid){
			if($task_data['frequency_type'] == "recurrence"){
				$off_days = get_company_offdays();
				$re_data = monthly_recurrence_logic($task_data,$data["start_date"],$data["end_date"],$off_days);
				//pr($re_data);
				$task_status_completed_id = $this->config->item('completed_id');
				 
				foreach($re_data as $row){
					//$master_task_array = array();
					$master_task_array = chk_virtual_recurrence_exists($row['master_task_id'],$row['task_due_date'],$task_status_completed_id);
					//pr($master_task_array);die;
                                        //echo $this->db->last_query();
                                        
                                        $div = array();
                                        $status = get_taskStatus($this->session->userdata('company_id'),'Active');
					if($master_task_array){
                                            
						$div['re_data'] = $master_task_array;
						date_default_timezone_set($this->session->userdata("User_timezone"));
						$div['div_id'] = strtotime($master_task_array['task_scheduled_date']);
						$div['tmezone_scheduled_date'] = date($this->config->item('company_default_format'),strtotime($row['task_scheduled_date']));
						$div['user_due_date'] = date($this->config->item('company_default_format'),strtotime($master_task_array['task_due_date']));
						$div['strtotime_scheduled_date'] = strtotime($master_task_array['task_scheduled_date']);
						$div['strtotime_start_date'] = strtotime($data["start_date"]);
						$div['strtotime_end_date'] = strtotime($data["end_date"]);
						$div['color_code'] = get_task_color_code($master_task_array['color_id']);
						$div['outside_color_code'] = get_outside_color_code($master_task_array['color_id']);
						//echo $task_status_completed_id." == ".$master_task_array['task_status_id'];
						$div['task_status_name'] = get_task_status_name_by_id($master_task_array['task_status_id']);
						if($task_status_completed_id == $master_task_array['task_status_id']){
							$div['is_completed'] = '1';
						}else{
							$div['is_completed'] = '0';
						}
						if (strpos($master_task_array['task_id'],'child') !== false) {
						    $div['is_chk'] = "0";
						} else {
							$div['is_chk'] = "1";
						}
                                                $div['steps']=get_task_steps($master_task_array['task_id']);
                                                $div['swimlane_name'] = get_swimlanes_name($master_task_array['swimlane_id']);
                                                $div['customer'] = get_customer_detail($master_task_array['customer_id'],$master_task_array['task_company_id']);
						$div['context_due_date'] = date("m-d-Y",strtotime($master_task_array['task_due_date']));
                                                $div['context_scheduled_date'] = date("m-d-Y",strtotime($master_task_array['task_scheduled_date']));
                                                $div['color_menu'] = 'true';
                                                $div['total_active_swimlane'] = count_total_swimlanes();
					}else{
						$div['re_data'] = $row;
						date_default_timezone_set($this->session->userdata("User_timezone"));
						$div['div_id'] = strtotime($row['task_scheduled_date']);
						$div['tmezone_scheduled_date'] = date($this->config->item('company_default_format'),strtotime($row['task_scheduled_date']));
						$div['user_due_date'] = date($this->config->item('company_default_format'),strtotime($row['task_due_date']));
						$div['strtotime_scheduled_date'] = strtotime($row['task_scheduled_date']);
						$div['strtotime_start_date'] = strtotime($data["start_date"]);
						$div['strtotime_end_date'] = strtotime($data["end_date"]);
						$div['color_code'] = get_task_color_code($row['color_id']);
						$div['outside_color_code'] = get_outside_color_code($row['color_id']);
						$div['task_status_name'] = get_task_status_name_by_id($row['task_status_id']);
						if($task_status_completed_id == $row['task_status_id']){
							$div['is_completed'] = '1';
						}else{
							$div['is_completed'] = '0';
						}
						if (strpos($row['task_id'],'child') !== false) {
						    $div['is_chk'] = "0";
						} else {
							$div['is_chk'] = "1";
						}
                                                //echo $task_id; die();
                                                $div['steps']=get_task_steps($task_id);
                                                $div['swimlane_name'] = get_swimlanes_name($row['swimlane_id']);
                                                $div['customer'] = get_customer_detail($row['customer_id'],$row['task_company_id']);
                                                $div['context_due_date'] = date("m-d-Y",strtotime($row['task_due_date']));
                                                $div['context_scheduled_date'] = date("m-d-Y",strtotime($row['task_scheduled_date']));
                                                $div['color_menu'] = 'true';
                                                $div['total_active_swimlane'] = count_total_swimlanes();
					}
                                        $color_codes = get_user_color_codes($div['re_data']['task_allocated_user_id']);
                                        $context['user_colors'] = $color_codes;
                                        $context['task_status'] = $status;
                                        $context['color_menu'] = $div['color_menu'];
                                        //$context['completed_depencencies'] = $div['re_data']['completed_depencencies'];
                                        $context['date'] = $div['div_id'];
                                        $context['start_date'] = $div['strtotime_start_date'];
                                        $context['strtotime_end_date'] = $div['strtotime_end_date'];
                                        $context['is_master_deleted'] = $div['is_chk'];
                                        $context['master_task_id'] = $div['re_data']['master_task_id'];
                                        $context['swimlane_id'] = $div['re_data']['swimlane_id'];
                                        $context['task_due_date'] = $div['context_due_date'];
                                        $context['task_scheduled_date'] = $div['context_scheduled_date'];
                                        $context['task_id'] = $div['re_data']['task_id'];
                                        $context['task_owner_id'] = $div['re_data']['task_owner_id'];
                                        $context['before_status_id'] = '';
                                        $context['active_menu'] = $data['active_menu'];
                                        $context['chk_watch_list'] = $task_data['watch'];
                                        if($this->session->userdata('Temp_calendar_user_id')== '0'){
                                            $user_swimlanes = get_user_swimlanes(get_authenticateUserID());
                                        }else{
                                            $user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id'));
                                        }
                                        $context['user_swimlanes'] = $user_swimlanes;
                                        $div['context_menu'] = htmlspecialchars(json_encode($context));
					$final_div[] = $div;
				}
                                $final_div['image'] = $owner_image;
                                $final_div['first_name']= $profile->first_name;
                                $final_div['last_name']= $profile->last_name;
                                
                                //echo "<pre>"; print_r($final_div); die();
				echo json_encode($final_div);die;
			} 
		}
		
	}

        /**
         * In timer option of filter,when user select task for starting timer at the same time this function will return task_id.
         * 
         * @returns int
         */
	function save_task(){
		$theme = getThemeName();
		$data = array();
                /* Get scope_id from post method*/
		$scope_id = $_POST['scope_id'];
		$post_data = json_decode($_POST['post_data'],true);
		$from = isset($_POST['from'])?$_POST['from']:'';
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$chk_exist = chk_task_exists($scope_id);
		if($chk_exist == '0'){
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
			$id = $scope_id;
		}
		echo $id;die;
	}
	
        /**
         * On right-click functionality of calendar page, at the same this function will call for update priority in db.
         * It fetch value from post method for update priority and update appropriate history in db.
         * @returns void
         */
	function set_priority(){

		$theme = getThemeName();
                /*
                 *get task _id and priority value 
                 */
		$task_id = $_POST['task_id'];
		$val = $_POST['value'];
		$post_data = json_decode($_POST['post_data'],true);
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
		//pr($_POST);die;
		$task_exists = chk_task_exists($post_data['task_id']);
                /**
                 * check task existance
                 */
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

		$old_priority = $post_data['task_priority'];

		$data = array('task_priority'=>$val);
		//$this->db->where('task_id',$id);
		$this->db->where('(task_id = '.$id.' or (multi_allocation_task_id = '.$id.' and is_deleted = 0))');
		$this->db->update('tasks',$data);
                /**
                 * check old and new priority
                 */
		if($old_priority != $val){
			$history_data = array(
				'histrory_title' => 'Task priority changed from "'.$old_priority.'" to "'.$val.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
			
			$multiIds = multiAllocationTaskIds($id);
			if($multiIds){
				foreach($multiIds as $mId){
					$history_data = array(
						'histrory_title' => 'Task priority changed from "'.$old_priority.'" to "'.$val.'"',
						'history_added_by' => get_authenticateUserID(),
						'task_id' => $mId->task_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('task_history',$history_data);
				}
			}
			
		}
                

		if($is_virtual == "1"){
			$theme = getThemeName();

			$data['site_setting_date'] = $this->config->item('company_default_format');
			$default_day = get_default_day_of_company();
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			if(isset($_POST['color_menu'])){
                                    $color_menu=$_POST['color_menu'];
                                }
                                else{
                                    $color_menu='true';
                                }
                                $data['color_menu']=$color_menu;
			if($redirect == 'weekView' || $redirect == "NextFiveDay"){
                                
				$data['active_menu']=$redirect;
				$data['week_task'] = get_task_detail($id);
				date_default_timezone_set($this->session->userdata("User_timezone"));
				$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));
	
				$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;
	
				$action = isset($_POST['action'])?$_POST['action']:'';
	
				$date_arr = array();
	
				$data['date_arr'] = $this->getArr($start_date);
				$data['company_date_arr'] = $this->getArr($start_date,'company');
	
				$data["start_date"] = reset($data["date_arr"]);
				$data["end_date"] =end($data["date_arr"]);
				$data['action'] = 'weekView';
				
				if($last_rember_values){
					$data['calender_project_id'] = $last_rember_values->calender_project_id;
					$data['left_task_status_id'] = $last_rember_values->task_status_id;
					$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
					$data['calender_date'] = $last_rember_values->calender_date;
					$data['calender_sorting'] = $last_rember_values->calender_sorting;
					$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
				} else {
					$data['calender_project_id'] = '';
					$data['left_task_status_id'] = '';
					$data['calender_team_user_id'] = '';
					$data['calender_date'] = '';
					$data['calender_sorting'] = '1';
					$data['cal_user_color_id'] = '';
                                        $data['show_other_user_task'] = 0;
				}
                                $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
				echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);
	
			} else if($redirect == 'from_kanban'){
                            $data['kanban'] = get_task_detail($id);
                            $this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
                        }else if($redirect == 'from_project'){
                        $data['active_menu']='from_project';
                        $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                        $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                        $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                        $data['td']= get_project_task_detail($id);
                        $data["is_master_deleted"] = chk_master_task_id_deleted($data['td']->master_task_id);
                        $this->load->view($theme.'/layout/project/ajax_task_div',$data);
                }else {
	
				$data['date'] = get_task_detail($id);
				$data['scope_id'] = $id;
				$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
				$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
				echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
			}
		} else {
			echo "done";die;
		}

	}
        /**
         * This function is called when user update due date on right-click option of calendar page.
           It will access due date and task_id for update task due date & task histroy in db.After that it will move task on appropriate date automatically.
         * @returns jasonObject|create view page
         */
        function set_task_due_date(){
            /* Get due date and task id for update date in db*/
		$due_date = change_date_format($_POST['due_date']);
		$task_id = $_POST['task_id'];
		$post_data = isset($_POST['task_data'])?json_decode($_POST['task_data'],true):'';
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }else{
                    $color_menu='true';
                }
                
		$chk_exist = chk_task_exists($task_id);
		if($chk_exist == '0'){
                    $post_data = isset($_POST['task_data'])?json_decode($_POST['task_data'],true):'';
                    
                    if($post_data == ''){
                        $ids = explode('_',$task_id);
			$main_id = $ids[1];
                        $orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
                    }
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
			$id = $task_id;
			$is_virtual = "0";
		}
		/* Below query update due date in db*/
		$task_data = get_task_detail($id);
		$old_due_date = $task_data['task_due_date'];
                if($task_data['task_scheduled_date'] == '0000-00-00'){
                    $data = array('task_due_date'=>$due_date,
                                  'task_scheduled_date'=>$due_date);
                }else{
                    $data = array('task_due_date'=>$due_date);
                }
		$this->db->where('(task_id = '.$id.' or (multi_allocation_task_id = '.$id.' and is_deleted = 0))');
		$this->db->update('tasks',$data);
                /*Check due date for update task history in db*/
		if($old_due_date != $due_date){
			$history_data = array(
				'histrory_title' => 'Task due date changed from "'.$old_due_date.'" to "'.$due_date.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
			
			$multiIds = multiAllocationTaskIds($id);
			if($multiIds){
				foreach($multiIds as $mId){
					$history_data = array(
						'histrory_title' => 'Task due date changed from "'.$old_due_date.'" to "'.$due_date.'"',
						'history_added_by' => get_authenticateUserID(),
						'task_id' => $mId->task_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('task_history',$history_data);
				}
			}
		}

		$theme = getThemeName();
                $data['color_menu']=$color_menu;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$default_day = get_default_day_of_company();
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($redirect == 'weekView' || $redirect == "NextFiveDay"){

			$data['active_menu']=$redirect;
			$data['week_task'] = get_task_detail($id);
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$action = isset($_POST['action'])?$_POST['action']:'';

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = 'weekView';
			
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['calender_sorting'] = $last_rember_values->calender_sorting;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['calender_sorting'] = '1';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
                        $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
			echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);

		}else if($redirect == 'from_kanban'){
                        $data['kanban'] = get_task_detail($id);
                        $data['color_menu']=$color_menu;
                        $this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
                }else if($redirect == 'from_customer'){
                        $data['active_menu']='from_customer';
                        $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                        $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                        $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                        $data['tasks']= get_task_detail($id);
                        $this->load->view($theme.'/layout/customer/update_task_ajax', $data);  

                }else if($redirect == 'from_project'){
                        $data['active_menu']='from_project';
                        $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                        $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                        $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                        $data['td']= get_project_task_detail($id);
                        $data["is_master_deleted"] = chk_master_task_id_deleted($data['td']->master_task_id);
                        $this->load->view($theme.'/layout/project/ajax_task_div',$data);
                }else if($redirect == 'from_calendar'){
			$data['date'] = get_task_detail($id);
			$data['scope_id'] = $id;
			$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
			$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
		}else{
                        $data['task_id'] = $id;
			$data['date'] = strtotime($task_data['task_scheduled_date']);
			echo json_encode($data);die;
		}
		
	}
        /**
         * This function is used for update swim lane on calendar.
           When user change swim lane at the same time this method call for update task in db.
         * @returns string
         */
	function change_swimlane(){
		
		$task_id = $_POST['task_id'];
		$swimlane_id = $_POST['swimlane_id'];
		$post_data = json_decode($_POST['task_data'],true);
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                
		$chk_exist = chk_task_exists($task_id);
		if($chk_exist == '0'){
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
			$id = $task_id;
			$is_virtual = "0";
		}
		
		/* Below query update swimlane in db*/
		$data = array('swimlane_id'=>$swimlane_id);
		$this->db->where('task_id',$id);
		$this->db->where('user_id',$this->session->userdata("Temp_calendar_user_id"));
		$this->db->update('user_task_swimlanes',$data);

		
		if($is_virtual == "1"){
		
			$theme = getThemeName();
                        $data['color_menu']=$color_menu;
			$data['site_setting_date'] = $this->config->item('company_default_format');
			$default_day = get_default_day_of_company();
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			if($redirect == 'weekView' || $redirect == "NextFiveDay"){
	
				$data['active_menu']=$redirect;
				$data['week_task'] = get_task_detail($id);
				date_default_timezone_set($this->session->userdata("User_timezone"));
				$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));
	
				$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;
	
				$action = isset($_POST['action'])?$_POST['action']:'';
	
				$date_arr = array();
	
				$data['date_arr'] = $this->getArr($start_date);
				$data['company_date_arr'] = $this->getArr($start_date,'company');
	
				$data["start_date"] = reset($data["date_arr"]);
				$data["end_date"] =end($data["date_arr"]);
				$data['action'] = 'weekView';
				
				if($last_rember_values){
					$data['calender_project_id'] = $last_rember_values->calender_project_id;
					$data['left_task_status_id'] = $last_rember_values->task_status_id;
					$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
					$data['calender_date'] = $last_rember_values->calender_date;
					$data['calender_sorting'] = $last_rember_values->calender_sorting;
					$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
				} else {
					$data['calender_project_id'] = '';
					$data['left_task_status_id'] = '';
					$data['calender_team_user_id'] = '';
					$data['calender_date'] = '';
					$data['calender_sorting'] = '1';
					$data['cal_user_color_id'] = '';
                                        $data['show_other_user_task'] = 0;
				}
                                $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
				echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);
	
			} else {
                                $data['color_menu']=$color_menu;
				$data['date'] = get_task_detail($id);
				$data['scope_id'] = $id;
				$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
				$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
				echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
			}
		} else {
			$theme = getThemeName();
                        $data['color_menu']=$color_menu;
			$data['site_setting_date'] = $this->config->item('company_default_format');
			$default_day = get_default_day_of_company();
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			if($redirect == 'weekView' || $redirect == "NextFiveDay"){
	
				$data['active_menu']=$redirect;
				$data['week_task'] = get_task_detail($id);
				date_default_timezone_set($this->session->userdata("User_timezone"));
				$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));
	
				$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;
	
				$action = isset($_POST['action'])?$_POST['action']:'';
	
				$date_arr = array();
	
				$data['date_arr'] = $this->getArr($start_date);
				$data['company_date_arr'] = $this->getArr($start_date,'company');
	
				$data["start_date"] = reset($data["date_arr"]);
				$data["end_date"] =end($data["date_arr"]);
				$data['action'] = 'weekView';
				
				if($last_rember_values){
					$data['calender_project_id'] = $last_rember_values->calender_project_id;
					$data['left_task_status_id'] = $last_rember_values->task_status_id;
					$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
					$data['calender_date'] = $last_rember_values->calender_date;
					$data['calender_sorting'] = $last_rember_values->calender_sorting;
					$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
				} else {
					$data['calender_project_id'] = '';
					$data['left_task_status_id'] = '';
					$data['calender_team_user_id'] = '';
					$data['calender_date'] = '';
					$data['calender_sorting'] = '1';
					$data['cal_user_color_id'] = '';
                                        $data['show_other_user_task'] = 0;
				}
				echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);
                                
                        }
		} 
	}
	/**
	* When user update status on right-click functionality of calendar at the same time this method is called for update status.
          Once it will update status in db .After that it will send mail and notification user and task_owner like this task has completed or uncompleted. 
          And it will create monthly view with new status.
	* @returns create view
	*/
	function change_status(){
		
		$task_id = $_POST['task_id'];
		$status_id = $_POST['status_id'];
		$post_data = isset($_POST['task_data'])?json_decode($_POST['task_data'],true):'';
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
		/* check task is existed or not*/
		$chk_exist = chk_task_exists($task_id);
		if($chk_exist == '0'){
                    $ids = explode('_',$task_id);
			$main_id = $ids[1];
                        $orig_data = get_task_detail($main_id);
			$post_data = kanban_recurrence_logic($orig_data);
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
			$id = $task_id;
			$is_virtual = "0";
		}
		if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$post_data = get_task_detail($id);
		$old_status_id = $post_data['task_status_id'];

		$status = $status_id;

		$task_prev_id = $old_status_id;

		$old_task_status_name = get_task_status_name_by_id($task_prev_id);

		$new_task_status_name = get_task_status_name_by_id($status);

		$task_status_completed_id = $this->config->item('completed_id');
		/* check completed task id with stutus for update task*/
		if($status == $task_status_completed_id){

			$update_data = array('task_status_id'=>$status, 'task_completion_date'=>date('Y-m-d H:i:s'));
			$this->db->where('task_id',$id);
			$this->db->update('tasks',$update_data);


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
			/* check task_owner id with authenticated user for mail*/
			if($post_data['task_owner_id'] !=get_authenticateUserID()){
				
				//notification
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
				
				/* send email to task owner & user for task is completed */
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
				$email_temp=$email_template->row();
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;

				$email_subject=$email_temp->subject;
				$email_message=$email_temp->message;

				$user_info = get_user_info($post_data['task_owner_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $post_data['task_title'];


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($post_data['task_allocated_user_id']);

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

			/* check owner_id with allocated_user_id & authenticated user*/
			if($post_data['task_owner_id'] != $post_data['task_allocated_user_id'] && $post_data['task_allocated_user_id'] !=get_authenticateUserID()){
				
				//notification
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
				
				/* send email to task owner user for task is completed */
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
				$email_temp=$email_template->row();
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;

				$email_subject=$email_temp->subject;
				$email_message=$email_temp->message;

				$user_info = get_user_info($post_data['task_allocated_user_id']);
				$user_name = $user_info->first_name.' '.$user_info->last_name;
				$task_name = $post_data['task_title'];


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($post_data['task_allocated_user_id']);

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
			/////////////

			$post_data['task_status_id'] = $status;
		} else {

			$update_data = array('task_status_id'=>$status, 'task_completion_date'=>'0000-00-00 00:00:00');
			$this->db->where('task_id',$id);
			$this->db->update('tasks',$update_data);

			if($task_prev_id == $task_status_completed_id){
				
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
				
				if($post_data['task_owner_id']!=get_authenticateUserID()){
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
					
					/*** send email to task owner user for task is completed ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
					$email_temp=$email_template->row();
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
	
					$email_subject=$email_temp->subject;
					$email_message=$email_temp->message;
	
					$user_info = get_user_info($post_data['task_owner_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $post_data['task_title'];
	
	
					$email_to = $user_info->email;
					$subscription_link = site_url();
					$allocated_user_name = usernameById($post_data['task_allocated_user_id']);
	
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
				
				if($post_data['task_owner_id']!=$post_data['task_allocated_user_id']  && $post_data['task_allocated_user_id']!=get_authenticateUserID()){
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
					
					/*** send email to task owner user for task is completed ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
					$email_temp=$email_template->row();
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
	
					$email_subject=$email_temp->subject;
					$email_message=$email_temp->message;
	
					$user_info = get_user_info($post_data['task_allocated_user_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $post_data['task_title'];
	
	
					$email_to = $user_info->email;
					$subscription_link = site_url();
					$allocated_user_name = usernameById($post_data['task_allocated_user_id']);
	
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
			$post_data['task_status_id'] = $status;
		}
		if($task_prev_id != $status){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}

		
		$theme = getThemeName();

		$data['site_setting_date'] = $this->config->item('company_default_format');
		$default_day = get_default_day_of_company();
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($redirect == 'weekView' || $redirect == "NextFiveDay"){

			$data['active_menu']=$redirect;
			$data['week_task'] = get_task_detail($id);
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$action = isset($_POST['action'])?$_POST['action']:'';

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = 'weekView';
			
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['calender_sorting'] = $last_rember_values->calender_sorting;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['calender_sorting'] = '1';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
                        $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
			echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);

		} else if($redirect == ''){

			$data['date'] = get_task_detail($id);
			$data['scope_id'] = $id;
			$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
			$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
		}
                else if($redirect == 'from_customer')
                {
                        $data['active_menu']='from_customer';
                        $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                        $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                        $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                        $data['tasks']= get_task_detail($id);
                        $this->load->view($theme.'/layout/customer/update_task_ajax', $data);  

		$this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
                }
                else if($redirect == 'from_project'){
                        $data['active_menu']='from_project';
                        $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                        $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                        $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                        $data['td']= get_project_task_detail($id);
                        $data["is_master_deleted"] = chk_master_task_id_deleted($data['td']->master_task_id);
                        $this->load->view($theme.'/layout/project/ajax_task_div',$data);
                }
		
	}
	/**
         * This function will also update task status but using check box on task div.When user checked checkbox at the same time this function
           will call for update status in db .It will send mail and notification to task owner,manager,user for task completion.
         * @returns create view
         */
	function update_status(){
		$theme = getThemeName();
		$from_view = isset($_POST['from_module'])?$_POST['from_module']:'';
		if($from_view){
			$post_data = json_decode($_POST['data'],true);
		} else {
			$post_data = $_POST['data'];
		}
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$task_exists = chk_task_exists($post_data['task_id']);
                /**
                 * check task exist or not
                 */
		if($task_exists == '0'){
                    /**
                     * save task
                     */
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

		$status = $_POST['status'];

		$task_prev_id = $post_data['task_status_id'];

		$old_task_status_name = get_task_status_name_by_id($task_prev_id);

		$new_task_status_name = get_task_status_name_by_id($status);

		$task_status_completed_id = $this->config->item('completed_id');

		if($status == $task_status_completed_id){

			$update_data = array('task_status_id'=>$status, 'task_completion_date'=>date('Y-m-d H:i:s'));
			$this->db->where('task_id',$id);
			$this->db->update('tasks',$update_data);


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
                                 * send email to task owner user for task is completed 
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


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($post_data['task_allocated_user_id']);

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


			if($post_data['task_owner_id'] != $post_data['task_allocated_user_id'] && $post_data['task_allocated_user_id'] !=get_authenticateUserID()){
				
				//notification
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


				$email_to = $user_info->email;
				$subscription_link = site_url();
				$allocated_user_name = usernameById($post_data['task_allocated_user_id']);

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
			/////////////

			$post_data['task_status_id'] = $status;
		} else {

			$update_data = array('task_status_id'=>$status, 'task_completion_date'=>'0000-00-00 00:00:00');
			$this->db->where('task_id',$id);
			$this->db->update('tasks',$update_data);

			if($task_prev_id == $task_status_completed_id){
				
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
				
				if($post_data['task_owner_id']!=get_authenticateUserID()){
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
					
					/*** send email to task owner user for task is completed ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
					$email_temp=$email_template->row();
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
	
					$email_subject=$email_temp->subject;
					$email_message=$email_temp->message;
	
					$user_info = get_user_info($post_data['task_owner_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $post_data['task_title'];
	
	
					$email_to = $user_info->email;
					$subscription_link = site_url();
					$allocated_user_name = usernameById($post_data['task_allocated_user_id']);
	
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
				
				if($post_data['task_owner_id']!=$post_data['task_allocated_user_id']  && $post_data['task_allocated_user_id']!=get_authenticateUserID()){
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
					
					/*** send email to task owner user for task is completed ****/
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task uncompleted'");
					$email_temp=$email_template->row();
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
	
					$email_subject=$email_temp->subject;
					$email_message=$email_temp->message;
	
					$user_info = get_user_info($post_data['task_allocated_user_id']);
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					$task_name = $post_data['task_title'];
	
	
					$email_to = $user_info->email;
					$subscription_link = site_url();
					$allocated_user_name = usernameById($post_data['task_allocated_user_id']);
	
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
			$post_data['task_status_id'] = $status;
		}
		if($task_prev_id != $status){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}


		$data['site_setting_date'] = $this->config->item('company_default_format');

		$data['week_task'] = get_task_detail($id);
		$default_day = get_default_day_of_company();

		$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

		$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;
		$end_date = $_POST['end_date'];
		$action = $_POST['action'];
		$data['active_menu'] = $_POST['active_menu'];

		$date_arr = array();

		$data['date_arr'] = $this->getArr($start_date);
		$data['company_date_arr'] = $this->getArr($start_date,'company');

		$data["start_date"] = reset($data["date_arr"]);
		$data["end_date"] =end($data["date_arr"]);
		$data['action'] = $action;
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['calender_sorting'] = $last_rember_values->calender_sorting;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                        $data['show_other_user_task'] = $last_rember_values->other_user_task;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['calender_sorting'] = '1';
			$data['cal_user_color_id'] = '';
                        $data['show_other_user_task'] = 0;
		}
		$data['scope_id'] = $id;
                $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
		echo $this->load->view($theme.'/layout/calender/weekly_task_div', $data, TRUE);
		die;
	}
        /**
         * When user click on expand icon on task div this function will call for update position in db.
         * @returns void
         */
	function save_task_pos(){
		$theme = getThemeName();
		$post_data = $_POST['data'];
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$data['site_setting_date'] = $this->config->item('company_default_format');
                
		$task_exists = chk_task_exists($post_data['task_id']);
//		if($task_exists == '0'){
//			$id = $this->kanban_model->save_task($post_data);
//			$steps = get_task_steps($post_data['master_task_id']);
//			if($steps){
//				$i = 1;
//				foreach($steps as $step){
//					$step_data = array(
//						'task_id' => $id,
//						'step_title' => $step['step_title'],
//						'step_added_by' => $step['step_added_by'],
//						'is_completed' => $step['is_completed'],
//						'step_sequence' => $i,
//						'step_added_date' => date('Y-m-d H:i:s')
//					);
//					$this->db->insert('task_steps',$step_data);
//					$i++;
//				}
//			}
//                        $id = $post_data['task_id'];
//			$is_virtual = "0";
//		} else {
//			
//		}
                $id = $post_data['task_id'];
		$is_virtual = "0";
		if($is_virtual == "0"){
			if($post_data['task_ex_pos']=='0'){
				$pos = '1';
			}else{
				$pos = '0';
			}
		} else {
			$pos = '1';
		}


		$expand_pos = array('task_ex_pos'=>$pos);
		$this->db->where(array('task_id'=>$id,'user_id'=>$this->session->userdata("Temp_calendar_user_id")));
		$this->db->update('user_task_swimlanes',$expand_pos);

		if($is_virtual == "1"){

			$data['site_setting_date'] = $this->config->item('company_default_format');

			$default_day = get_default_day_of_company();

			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;
			$end_date = $_POST['end_date'];
			$action = $_POST['action'];
			$data['active_menu'] = $_POST['active_menu'];

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = $action;

			$data['last_rember_values'] =$last_rember_values = get_user_last_rember_values();
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['calender_sorting'] = $last_rember_values->calender_sorting;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['calender_sorting'] = '1';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}

			$data['week_task'] = get_task_detail($id);
                        $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
			$this->load->view($theme.'/layout/calender/weekly_task_div',$data);
		} else {
			echo "done";die;
		}
	}

        /**
         *This function is called when user save task in watch list.It will insert task details in db.
          After that it will create calendar view page.
         * @returns create view 
         */
	function save_watch_list(){
		$theme = getThemeName();
		$post_data = json_decode($_POST['data'],true);
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
		$data['site_setting_date'] = $this->config->item('company_default_format');
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$task_exists = chk_task_exists($post_data['task_id']);
		if($task_exists == '0'){
                    /**
                     * save task
                     */
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

		$watch_data = array('task_id'=>$id, 'user_id'=>get_authenticateUserID());
		$this->db->insert('my_watch_list',$watch_data);

		$theme = getThemeName();

		$data['site_setting_date'] = $this->config->item('company_default_format');
		$default_day = get_default_day_of_company();

		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
                /**
                 * check view for create view page
                 */
		if($redirect == 'weekView' || $redirect == "NextFiveDay"){

			$data['active_menu']=$redirect;
			$data['week_task'] = get_task_detail($id);
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$action = isset($_POST['action'])?$_POST['action']:'';

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = 'weekView';
			
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['calender_sorting'] = $last_rember_values->calender_sorting;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['calender_sorting'] = '1';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
                        $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
			echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);

		} else if($redirect == 'from_kanban'){
                    $data['active_menu']=$redirect;
                        $data['kanban'] = get_task_detail($id);
                        $this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
                }else {

			$data['date'] = get_task_detail($id);
			$data['scope_id'] = $id;
			$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
			$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
		}
		
	}

	/**
         * When user save task in watch list after that on right-click menu have a option for remove task from watch list.
           When user click to remove option this function will delete task from the list and create new calendar view page .
         * @returns create view
         */
	function delete_watch_list(){

		$theme = getThemeName();
		$post_data = json_decode($_POST['data'],true);
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$data['site_setting_date'] = $this->config->item('company_default_format');
                /**
                 * check task 
                 */
		$task_exists = chk_task_exists($post_data['task_id']);
                /*
                 * check task exist or not than set is_virtual value 0 or 1.
                 */
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
                                        /**
                                         * insert steps into db
                                         */
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
                /**
                 * query for delete task 
                 */
		$this->db->delete('my_watch_list',array('task_id'=>$id, 'user_id'=>get_authenticateUserID()));

		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$default_day = get_default_day_of_company();
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
                /**
                 * check view for create calender view page
                 */
		if($redirect == 'weekView' || $redirect == "NextFiveDay"){

			$data['active_menu']=$redirect;
			$data['week_task'] = get_task_detail($id);
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$action = isset($_POST['action'])?$_POST['action']:'';

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = 'weekView';
			
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['calender_sorting'] = $last_rember_values->calender_sorting;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['calender_sorting'] = '1';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
                        $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
			echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);

		} else if($redirect == 'from_kanban'){
		$data['task_id'] = $id;
		$data['kanban'] = get_task_detail($id);
                /**
                 * it will render task_div view on kanban page
                 */
		$this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
                }else {

			$data['date'] = get_task_detail($id);
			$data['scope_id'] = $id;
			$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
			$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
		}
		
	}

	/**
       * This method will call when task is going for completed via check box option on task div. It will get actual time of task completion.
         After that it will update DB with new data and check user_id with task_owner,manager of that task.
         Then it will send notification & mail user and task_owner of company.
       * @returns void
       */
	function add_actual_time(){

		$theme = getThemeName();

		$task_id = $_POST['task_id'];
		$task_actual_time_hour = $_POST['task_actual_time_hour'];
		$task_actual_time_min = $_POST['task_actual_time_min'];
		$post_data = json_decode($_POST['task_data'],true);
		$redirect = isset($_POST['redirect_page'])?$_POST['redirect_page']:'';
                if(isset($_POST['color_menu'])){
                    $color_menu=$_POST['color_menu'];
                }
                else{
                    $color_menu='true';
                }
                $data['color_menu']=$color_menu;
		$old_task_status_id = $post_data['task_status_id'];
		$old_task_status_name = get_task_status_name_by_id($old_task_status_id);

		$task_exists = chk_task_exists($task_id);
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
			$id = $task_id;
		}
                /*Below query for update completed task details in db*/
		$status = $this->config->item('completed_id');
		$task_time_spent = ($task_actual_time_hour*60)+$task_actual_time_min;

		$new_task_status_name = "Completed";


		$update_data = array("task_time_spent"=>$task_time_spent,'task_status_id'=>$status, 'task_completion_date'=>date('Y-m-d H:i:s'),'billed_time'=>$task_time_spent);
		$this->db->where('task_id',$id);
		$this->db->update('tasks',$update_data);
                
                if($this->session->userdata('pricing_module_status')=='1'){ 
                    $actual_time = get_task_actual_time($id);
                    $estimated_time = get_task_estimated_time($id);
                    $charge_out_rate = get_charge_out_rate($id);
                        $data2 = array(
                                    "charge_out_rate"=>$charge_out_rate,
                                    "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2),
                                    "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2)
                                );
                    
                    $this->db->where('task_id',$id);
                    $this->db->update('tasks',$data2);
                }
                
                
		if($old_task_status_id != $status){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		//email variables
		$owner_name = usernameById($post_data['task_owner_id']);
		if($post_data['task_due_date']!='0000-00-00'){
			date_default_timezone_set($this->session->userdata("User_timezone"));
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
			$notification_text = '"'.$post_data['task_title'].'" is completed by '.usernameById(get_authenticateUserID()).' this user.';
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
			
			/*** send email to task owner user for task is completed ****/
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='task completed'");
			$email_temp=$email_template->row();
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;

			$email_subject=$email_temp->subject;
			$email_message=$email_temp->message;

			$user_info = get_user_info($post_data['task_owner_id']);
			$user_name = $user_info->first_name.' '.$user_info->last_name;
			$task_name = $post_data['task_title'];


			$email_to = $user_info->email;
			$subscription_link = site_url();
			$allocated_user_name = usernameById($post_data['task_allocated_user_id']);

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

		if($post_data['task_owner_id'] != $post_data['task_allocated_user_id'] && $post_data['task_allocated_user_id'] !=get_authenticateUserID()){
			
			//notification
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


			$email_to = $user_info->email;
			$subscription_link = site_url();
			$allocated_user_name = usernameById($post_data['task_allocated_user_id']);

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


		$data['site_setting_date'] = $this->config->item('company_default_format');
		$default_day = get_default_day_of_company();
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($redirect == 'weekView' || $redirect == "NextFiveDay"){

			$data['active_menu']=$redirect;
			$data['week_task'] = get_task_detail($id);
			date_default_timezone_set($this->session->userdata("User_timezone"));
			$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));

			$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;

			$action = isset($_POST['action'])?$_POST['action']:'';

			$date_arr = array();

			$data['date_arr'] = $this->getArr($start_date);
			$data['company_date_arr'] = $this->getArr($start_date,'company');

			$data["start_date"] = reset($data["date_arr"]);
			$data["end_date"] =end($data["date_arr"]);
			$data['action'] = 'weekView';
			
			if($last_rember_values){
				$data['calender_project_id'] = $last_rember_values->calender_project_id;
				$data['left_task_status_id'] = $last_rember_values->task_status_id;
				$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
				$data['calender_date'] = $last_rember_values->calender_date;
				$data['calender_sorting'] = $last_rember_values->calender_sorting;
				$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
                                $data['show_other_user_task'] = $last_rember_values->other_user_task;
			} else {
				$data['calender_project_id'] = '';
				$data['left_task_status_id'] = '';
				$data['calender_team_user_id'] = '';
				$data['calender_date'] = '';
				$data['calender_sorting'] = '1';
				$data['cal_user_color_id'] = '';
                                $data['show_other_user_task'] = 0;
			}
                        $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
			echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);

		} else {

			$data['date'] = get_task_detail($id);
			$data['scope_id'] = $id;
			$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
			$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
			echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
		}
	}

	function delete_task_series(){
                $task_id = $_POST['task_id'];
		$redirect = isset($_POST['redirect'])?$_POST['redirect']:'';
		$from_redirect = isset($_POST['from_redirect'])?$_POST['from_redirect']:'';
		$from = isset($_POST['from'])?$_POST['from']:'';
                $current_date=$_POST['current_date'];
		$chk_exist = chk_task_exists($task_id);
                $occurence_start_date=get_task_occurence_date($task_id);
                    $date1=date_create($current_date);
                    $date2=date_create($occurence_start_date);
                    $diff=date_diff($date1,$date2);
                    $days = $diff->d;
//                    echo $days; die();
                 /**
                 *  This condition for deleted task as a future instance
                 */
                if( $from == 'future')
                { 
                        $update_data = array('end_by_date'=>$current_date,'no_end_date'=>'3');
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$update_data);
                        if($this->db->affected_rows()>0){
                            echo $days; die();
                        }
                        else
                        {
                            echo "false"; die();
                        }
                }
                $data['task_id'] = $task_id;
                $data['task_title'] = get_task_title($task_id);
                /*
                 * This condition is deleted task in series.
                 */
                if($from == 'series' && $task_id !='' && $chk_exist !='0'){
                        $update_data = array('is_deleted'=>'1');
                        //$this->db->where('task_id',$id);
                        $this->db->where('(task_id = '.$task_id.' or (multi_allocation_task_id = '.$task_id.' and is_deleted = 0))');
                        $this->db->update('tasks',$update_data);
			$update_data = array('is_deleted'=>'1');
			$this->db->where('master_task_id',$task_id);
			$this->db->update('tasks',$update_data);
                        $data['response'] = 'done';
                        echo json_encode($data); die();
		}
        }
        
        function get_project(){
            
            $theme = getThemeName();
            $data=array();
            if(isset($_POST['id'])){
                $data['ids']=$_POST['id'];
                $data['view']=$_POST['view'];
             }
             else {
                  die();
             }
            echo  $this->load->view($theme.'/layout/calender/calendar_project_ajx',$data, TRUE);
        }
		
        /**
        * This function will call on right-click functionality of task.It will create copy of task.
        * @returns copied task data
        */
	
	/*
	 * Function : copy_task
	 * Author : Spaculus
	 * Desc : This function is used when copy task  right click functionality.
	*/
        
	function copy_task(){
		
		$data = array();
		$scope_id = $_POST['task_id'];
		$task_due_date = isset($_POST['task_due_date'])?$_POST['task_due_date']:date('Y-m-d');
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$this->load->model('task_model');
		$post_data = json_decode($_POST['task_data'],true);
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$chk_exist = chk_task_exists($scope_id);
                /*Check task existence for update task*/
		if($chk_exist == '0'){
		
			$id=$post_data['master_task_id'];
		} else {
			$id = $scope_id;
		}
                /*This query create a new copy of task in DB*/
				$where=array("task_id"=>$id);
		$new_task_id=$this->task_model->copy_task($where,$task_due_date);
		$steps = get_task_steps($id);
				if($steps){
					$i = 1;
					foreach($steps as $step){
						$step_data = array(
							'task_id' => $new_task_id,
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
                                 $task_file = get_task_files($id);
                                if($task_file){
                                    foreach($task_file as $file){ 
                                        $file_data = array(
                                                'task_file_name' => $file['task_file_name'],
                                                'file_link' => $file['file_link'],
                                                'file_title' => $file['file_title'],
                                                'task_id' => $new_task_id,
                                                'project_id' => $file['project_id'],
                                                'file_added_by' => $this->session->userdata('user_id'),
                                                'file_date_added' => date('Y-m-d H:i:s')
                                            );

                                        $this->db->insert('task_and_project_files',$file_data);
                                    }
                                }
		//echo $new_task_id;
                $task_data = get_task_detail($new_task_id);
                $json['master_task_id'] = $task_data['master_task_id'];
		$json['task_status_id'] = isset($_POST['task_status_id'])?$_POST['task_status_id']:'';
		$json['swimlane_id'] = $task_data['swimlane_id'];
		$json['prerequisite_task_id'] = $task_data['prerequisite_task_id'];
		$json['status_id'] = isset($_POST['task_status_id'])?$_POST['task_status_id']:'';
		$json['task_id'] = $new_task_id;
		echo json_encode($json);die;
	}
        function task_update_div(){
            $task_id = $_POST['task_id'];
            $id=$task_id;
            $redirect = isset($_POST['active_menu'])?$_POST['active_menu']:'';
            $theme = getThemeName();

			$data['site_setting_date'] = $this->config->item('company_default_format');
			$default_day = get_default_day_of_company();
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			if(isset($_POST['color_menu'])){
                                    $color_menu=$_POST['color_menu'];
                                }
                                else{
                                    $color_menu='true';
                                }
                                $data['color_menu']=$color_menu;
			if($redirect == 'weekView' || $redirect == "NextFiveDay"){
                                
				$data['active_menu']=$redirect;
				$data['week_task'] = get_task_detail($id);
				date_default_timezone_set($this->session->userdata("User_timezone"));
				$start_date_old = date('Y-m-d',strtotime($default_day.' this week'));
	
				$start_date = isset($_POST['start_date'])?$_POST['start_date']:$start_date_old;
	
				$action = isset($_POST['action'])?$_POST['action']:'';
	
				$date_arr = array();
	
				$data['date_arr'] = $this->getArr($start_date);
				$data['company_date_arr'] = $this->getArr($start_date,'company');
	
				$data["start_date"] = reset($data["date_arr"]);
				$data["end_date"] =end($data["date_arr"]);
				$data['action'] = 'weekView';
				
				if($last_rember_values){
					$data['calender_project_id'] = $last_rember_values->calender_project_id;
					$data['left_task_status_id'] = $last_rember_values->task_status_id;
					$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
					$data['calender_date'] = $last_rember_values->calender_date;
					$data['calender_sorting'] = $last_rember_values->calender_sorting;
					$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
				} else {
					$data['calender_project_id'] = '';
					$data['left_task_status_id'] = '';
					$data['calender_team_user_id'] = '';
					$data['calender_date'] = '';
					$data['calender_sorting'] = '1';
					$data['cal_user_color_id'] = '';
				}
                                $data['footer_user_id'] = $last_rember_values->calender_team_user_id;
				echo $this->load->view($theme.'/layout/calender/weekly_task_div',$data,TRUE);
	
			} else if($redirect == 'from_kanban'){
                            $data['kanban'] = get_task_detail($id);
                            $this->load->view($theme.'/layout/kanban/ajax_task_div',$data);
                        }
//                        else if($redirect == 'from_project'){
//                        $data['active_menu']='from_project';
//                        $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
//                        $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
//                        $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
//                        $data['td']= get_project_task_detail($id);
//                        $data["is_master_deleted"] = chk_master_task_id_deleted($data['td']->master_task_id);
//                        $this->load->view($theme.'/layout/project/ajax_task_div',$data);
//                }
                else {
	
				$data['date'] = get_task_detail($id);
				$data['scope_id'] = $id;
				$data['year'] = isset($_POST['year'])?$_POST['year']:date("Y");
				$data['month'] = isset($_POST['month'])?$_POST['month']:date("m");
				echo $this->load->view($theme.'/layout/calender/monthly_task_div', $data, TRUE);
			}
        }
        
        function set_show_other_user_task(){
            $task_status = $this->input->post('other_user_task');
            
            $this->db->set('other_user_task',$task_status);
            $this->db->where('user_id',  get_authenticateUserID());
            $this->db->update('last_remember_search');
            echo "done"; dir();
        }
}
?>
