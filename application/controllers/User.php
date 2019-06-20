<?php

require_once APPPATH."libraries/chargify_lib/Chargify.php";
/**
 * This controller class will create dashboard,teamboard for user and this board's show loggedin user task .
 * There is various methods for create user dashboard,user setting,team dashboard like dashboard_menu(),dashboard(),mysetting(),team_dashboard() etc.
 * This class is extending the SPACULLUS_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class User extends SPACULLUS_Controller {
      /**
        * It Default Constuctor which is called when user object is initialzied. It loads necesary models,librarys, and config.
        * @returns void
        */  
	function User () {
		/**
                 * parent contructor call
                 */
		parent :: __construct ();
                /**
                 *  Amazon S3 server Configuration 
                 */
		$this->load->library('s3');
                /**
                 * Amazon S3 Configuration
                 */
		$this->config->load('s3');
                /**
                 * user controller database class
                 */
		$this->load->model('user_model');
                /**
                 * form validation library
                 */
		$this->load->library('form_validation');
                /**
                 * user agent library
                 */
		$this->load->library('user_agent');
                /**
                 * curl library
                 */
		$this->load->library('curl');
                
                $this->load->library('encrypt');
                date_default_timezone_set("UTC");
                define("OUTLOOK_REDIRECT_URL", base_url()."user/outlook_synchronization");
                /**
                 * gmail redirect url & push notification url
                 */
                define('GMAIL_REDIRECT_URL',base_url().'user/gmail_access');
                
                define('PUSH_NOTIFICATION_URL',  base_url().'cron/get_gmail_notification');
                
	}
 	/**
         * No code found.
         */
	public function index ($msg = '') {
		
	}
	/**
         * It will render dashboard for mobile .
         * @param $msg
         * @returns moblieview
         */
	function dashboard_menu($msg='')
	{
		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/dashboard-menu',$data,TRUE);
			$this->template->render();
		}
	}
	
      /**
       * When user click on dashboard option,this function check authentication than render loggedin user dashboard page.
       * It will access all loggedin user related information in db for dashboard.
       * @param $msg
       * @returns void
       */
	
	function dashboard($msg = ''){
		
		/**
                 * check user authentication
                 */
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
                /**
                 * set template and theme
                 */
		$this->template->set_master_template ($theme.'/template2.php');
		
		/**
                 * this array store all info of user
                 */
		$data = array();
		
		$data['theme'] = $theme;
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$type = isset($_COOKIE['dashboard_priority'])?$_COOKIE['dashboard_priority']:"";
		$duration=isset($_COOKIE['dashboard_duration'])?$_COOKIE['dashboard_duration']:"";;
		$data['task_status_completed_id'] = $task_status_completed_id = $this->config->item('completed_id');
		$data['com_off_days'] = $offdays = get_company_offdays();
		$data['task_priority'] = taskPriority();
		/**
                 * get task list from user model
                 */
		$data['todolist'] = $this->user_model->get_taskList($type,$duration,$task_status_completed_id,$offdays);
		$data['pending_task'] = $this->user_model->get_pendingtaskList($task_status_completed_id,$offdays);
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
		$data['watchlist'] = $this->user_model->getwatchlist();
		$data['task_thisweek'] = $this->user_model->gettimeestimation($task_status_completed_id,$offdays);
		$data['user_time'] = get_calender_settings_by_user(get_authenticateUserID());
		
		$data['none'] = taskByPriority('None',$task_status_completed_id,$offdays);
		$data['low'] = taskByPriority('Low',$task_status_completed_id,$offdays);
		$data['medium'] = taskByPriority('Medium',$task_status_completed_id,$offdays);
		$data['high'] = taskByPriority('High',$task_status_completed_id,$offdays);
		
		$data['timeallocationchart'] = $this->user_model->getTimeAllocationChart($task_status_completed_id,$offdays);
		$data['categories'] = $this->user_model->getcategoryforchart($task_status_completed_id,$offdays);
		$task_id = '';
		$data['task_id'] = $task_id;
		
		$data['task_section_name'] = "";
		$data['task'] =   array( 
            'general' => '',
            'dependencies' => '',
            'steps' => '',
            'files' => '',
            'comments' => ''
		);
		
		/**
                 * this all line get userlist,division,department,statt,color and so on.
                 */
		$data['users'] = get_user_list();
		$data['customers']=  getCustomerList();
		$data['divisions'] = getUserDivision($this->session->userdata('user_id'));
		$data['departments'] = getUserDepartment($this->session->userdata('user_id'));
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));
		$data['is_color_exist'] = is_user_color_exist($this->session->userdata('user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		$data['task_last_login'] = getLastloginrange();
		if(count($data['task_last_login'])=='2'){
			
			 $current_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][1]->user_login_date));
			 $last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
			 
			 $data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
			 
		} elseif(count($data['task_last_login'])=='1'){
			$current_login = "0000-00-00 00:00:00";
			$last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
			$data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
		} else {
			
			$data['last_login_task'] = $this->user_model->getLastlogintask('0000-00-00 00:00:00','0000-00-00 00:00:00',$task_status_completed_id);
		}
		
		/**
                 * this render dashboard page.
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/dashboard',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
		
	}
        /**
         * This function will render task list since last login for mobile.
         * @param $msg
         * @returns view
         */
        function task_since_last_login($msg="")
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		$task_status_completed_id = $this->config->item('completed_id');
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['task_last_login'] = getLastloginrange();
			
			if(count($data['task_last_login'])=='2'){
			
			 $current_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][1]->user_login_date));
			 $last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
			 
			 $data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
			 
		} elseif(count($data['task_last_login'])=='1'){
			$current_login = "0000-00-00 00:00:00";
			$last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
			$data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
		} else {
			
			$data['last_login_task'] = $this->user_model->getLastlogintask('0000-00-00 00:00:00','0000-00-00 00:00:00',$task_status_completed_id);
		}
			
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/task_since_last_login',$data,TRUE);
			$this->template->render();
		}
	}
	/**
         * This function check user authentication than render today task table on moblie view.
         * @param $msg
         * @returns void
         */

	function today_tasks($msg="")
	{
            /**
             * check authentication
             */
		if (!check_user_authentication()){
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$data['theme'] = $theme;
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		/**
                 * check web version
                 */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
			$type = "task_due_date";
			$data['todolist'] = $this->user_model->get_taskListMobile($type);
			
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/today_tasks',$data,TRUE);
			$this->template->render();
		}
	}
        /**
         * This function will filter todays task on mobile view.
         * @param $msg
         * @returns view
         */
	function filtertasks($msg="")
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$data['theme'] = $theme;
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['theme'] = $theme;
			$type = "";
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$type = $_POST['id'];
			$data['todolist'] = $this->user_model->get_taskListMobile($type);
			
			
			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->load->view($theme.'/mobileview/user/Ajax_today_tasks',$data);
		}
	}
        /**
         * This function will update task status in db on task completion.It will render complete task view.
         * @returns view
         */
	function completeTask()
	{
		/**
                 * store values in variables
                 */
		$status = $_POST['status'];
		$task_id = $_POST['id'];
		
		$theme = getThemeName ();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$task_status_completed_id = $this->config->item('completed_id');
		$task_status_ready_id = get_task_status_id_by_name('Ready');
		/**
                 * check status with completed task id
                 */
		if($status==$task_status_completed_id){
			
			$data_status = array('task_status_id'=>$task_status_ready_id);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data_status);
		}else{
			$data_status = array('task_status_id'=>$task_status_completed_id);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data_status);
		}
		
		/**
                 * check web version 
                 */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['theme'] = $theme;
			if($_POST['from']=='todaytask'){
				$type = "";
				$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
				$type = $_POST['s_type'];
				$duration = "";
				$data['todolist'] = $this->user_model->get_taskListMobile($type);
				
				$this->template->set_master_template($theme .'/template_mobile.php');
			
				$this->load->view($theme.'/mobileview/user/Ajax_today_tasks',$data);
			}
			if($_POST['from']=='lastlogin'){
				
				$data['task_last_login'] = getLastloginrange();
				/**
                                 * check current, last login task status
                                 */
			if(count($data['task_last_login'])=='2'){
				$task_status_completed_id = $this->config->item('completed_id');
			 	$current_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][1]->user_login_date));
			 	$last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
			 
			 	$data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
			 
			} elseif(count($data['task_last_login'])=='1'){
				$task_status_completed_id = $this->config->item('completed_id');
				$current_login = "0000-00-00 00:00:00";
				$last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
				$data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
			} else {
				$task_status_completed_id = $this->config->item('completed_id');
				$data['last_login_task'] = $this->user_model->getLastlogintask('0000-00-00 00:00:00','0000-00-00 00:00:00',$task_status_completed_id);
			}
				
				$this->template->set_master_template($theme .'/template_mobile.php');
			
				$this->load->view($theme.'/mobileview/user/Ajax_lastlogintask',$data);
			}
			
		}
		
	}
	/**
         * When user change filter values,this function will load task according to filters on user dashboard.
         * And it will create view for dashboard. 
         * @returns view
         */
	function filterlasttask()
	{
		$theme = getThemeName ();
		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$type = $_POST['id'];
		
			
			$data['task_last_login'] = getLastloginrange();
			$task_status_completed_id = $this->config->item('completed_id');
		/**
                 * check login status
                 */
		if(count($data['task_last_login'])=='2'){
		
			$current_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][1]->user_login_date));
		 	$last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
		 
		 	$data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
			 
		} elseif(count($data['task_last_login'])=='1'){
			$current_login = "0000-00-00 00:00:00";
			$last_login = date("Y-m-d H:i:s",strtotime($data['task_last_login'][0]->user_login_date));
			$data['last_login_task'] = $this->user_model->getLastlogintask($current_login,$last_login,$task_status_completed_id);
		} else {
			
			$data['last_login_task'] = $this->user_model->getLastlogintask("0000-00-00 00:00:00","0000-00-00 00:00:00",$task_status_completed_id);
		}
			/**
                         * version check
                         */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{	
			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->load->view($theme.'/mobileview/user/Ajax_lastlogintask',$data);
		}
		else{
			$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
			$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
			$this->template->write_view('content_side',$theme .'/layout/user/dashboard',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
			$this->template->render();
		}
	}
        /**
         * This function will create watch list on mobile user.
         * @param string $msg
         * @returns view
         */
	function mywatchlist($msg="")
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = "";
			$data['watchlist'] = $this->user_model->getwatchlist($type);
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/mywatchlist',$data,TRUE);
			$this->template->render();
		}
	}
        /**
         * It will filter watch list of user on mobile version.
         * @returns void
         */
        function filterwatchlist()
	{
		$theme = getThemeName ();
		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$type = $_POST['id'];
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			
			$data['watchlist'] = $this->user_model->getwatchlist($type);
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			
			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->load->view($theme.'/mobileview/user/Ajax_mywatchlist',$data);
		}
	}
        /**
         * This function will show this week task on user dashboard on mobile.
         * @param string $msg
         * @returns view
         */
	
	function task_thisweek($msg="")
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();

		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		/** 
                 * check version
                 */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
                    /**
                     * there it get thisweek task,status and time for render thisweek table.
                     */
			$type = "";
			$data['task_thisweek'] = $this->user_model->gettimeestimation($task_status_completed_id,$offdays);
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$data['user_time'] = get_calender_settings_by_user(get_authenticateUserID());
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/task_thisweek',$data,TRUE);
			$this->template->render();
		}
	}
	/**
         * This function will render timeallocation view on user dashboard.
         * @param string $msg
         * @returns view
         */
	function task_today($msg="")
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
                    /**
                     * get value for today_task list
                     */
			$data['allocationtime'] = $this->user_model->getAllocationByType("priority");
			$data['allocationtime_project'] = $this->user_model->getAllocationByType("project");
			$data['allocationtime_category'] = $this->user_model->getAllocationByType("category");
			$data['totalNonEstTask'] = $this->user_model->getNonEstTask("priority");
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/timeallocation',$data,TRUE);
			$this->template->render();
			
		}
	}
        /**
         * This function will filter task via type on user dashboard.
         * @returns view
         */
	function filterbytype()
	{
		$msg="";
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = $_POST['id'];
			$data['allocationtime'] = $this->user_model->getAllocationByType($type);
			$data['totalNonEstTask'] = $this->user_model->getNonEstTask($type);
			
			$view = "timeallocation_".$type;
			
			echo $this->load->view($theme.'/mobileview/user/'.$view,$data,TRUE);
			
		}
	}
	/**
         * When user click on team dashboard link at the same time this function will call. And it will render team dashboard for admin.
         * It will fetch team dashboard data from db for create dashboard page of admin.
         * 
         * @param string $msg
         * @returns view
         */
	
	function team_dashboard($msg = ''){
		
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		
		$data = array();
		$data['theme'] = $theme;
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$type = isset($_COOKIE['teamdashboard_priority'])?$_COOKIE['teamdashboard_priority']:"";
		$duration=isset($_COOKIE['teamdashboard_duration'])?$_COOKIE['teamdashboard_duration']:'today';
		
		$data['task_status_completed_id'] = $task_status_completed_id = $this->config->item('completed_id');
		$data['com_off_days'] = $offdays = get_company_offdays();
		/**
                 * This will fetch data from db
                 */
		$data['teamtodolist'] = $this->user_model->get_teamtaskList($type,$duration,$task_status_completed_id,$offdays);
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),"Active");
		$data['pending_task'] = $this->user_model->get_pendingtaskList($task_status_completed_id,$offdays);
		$data['overdue_task'] = $this->user_model->get_overduetaskList($task_status_completed_id,$offdays);
		$data['task_thisweekteam'] = $this->user_model->gettimeestimationteam($task_status_completed_id,$offdays);
		$data['team'] = get_users_under_managers();
		$data['MON_hours'] = 0;
		$data['TUE_hours'] = 0;
		$data['WED_hours'] = 0;
		$data['THU_hours'] = 0;
		$data['FRI_hours'] = 0;
		$data['SAT_hours'] = 0;
		$data['SUN_hours'] = 0;
		/**
                 * check team 
                 */
		if($data['team']!="0")
		{
			foreach ($data['team'] as $t)
			{
				$data['user_time'] = get_calender_settings_by_user($t->user_id);
				if($data['user_time']!='0'){
					$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
					$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
					$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
					$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
					$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
					$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
					$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours;
				}
			}
		}
		$data['allocated'] = getmyteamtask($task_status_completed_id,$offdays);
		$nonallocatedtime = '0';
		/**
                 * allocate time and task
                 */
		if(date("l")=='Monday'){ $nonallocatedtime = $data['MON_hours'] - $data['allocated'];}
		if(date("l")=='Tuesday'){ $nonallocatedtime = $data['TUE_hours'] - $data['allocated'];}
		if(date("l")=='Wednesday'){ $nonallocatedtime = $data['WED_hours'] - $data['allocated'];}
		if(date("l")=='Thusday'){ $nonallocatedtime = $data['THU_hours'] - $data['allocated'];}
		if(date("l")=='Friday'){ $nonallocatedtime = $data['FRI_hours'] - $data['allocated'];}
		if(date("l")=='Saturday'){ $nonallocatedtime = $data['SAT_hours'] - $data['allocated'];}
		if(date("l")=='Sunday'){ $nonallocatedtime = $data['SUN_hours'] - $data['allocated'];}

		$data['nonallocated'] = $nonallocatedtime;
	  	
		
		$data['category'] = get_company_category($this->session->userdata('company_id'),'Active','0');
	  	$data['taskByCat'] = get_task_By_category($task_status_completed_id,$offdays);
	  
	  	$data['taskByCat_tot'] = get_task_By_category_count();
		// task functionality 
		
		$task_id = '';
		$data['task_id'] = $task_id;
		
		$data['task_section_name'] = "";
		$data['task'] =   array( 
            'general' =>'',
            'dependencies' => '',
            'steps' => '',
            'files' => '',
            'comments' => ''
		);
		
		
		$data['users'] = get_user_list();
		$data['customers']=  getCustomerList();
		$data['divisions'] = getUserDivision($this->session->userdata('user_id'));
		$data['departments'] = getUserDepartment($this->session->userdata('user_id'));
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));
		$data['is_color_exist'] = is_user_color_exist($this->session->userdata('user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		
		/**
                 * create teamboard page.
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/teamdashboard',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
		
		
	}
        /**
         * This function will show task list on Ajax request on team dashboard.
         * @returns view
         */
        function team_todo_Ajax(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		
		$type = isset($_POST["type"])?$_POST["type"]:"";
		$duration = isset($_POST["duration"])?$_POST["duration"]:"today";
		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		$data['teamtodolist'] = $this->user_model->get_teamtaskList($type,$duration,$task_status_completed_id,$offdays);
		
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),"Active");
		$this->load->view($theme.'/layout/user/team_todo_Ajax', $data);
	}
        /**
         * On team dashboard,this function will show due task list.This function will create due task table on team dashboard.And it will fetch data from db.
         * @param string $type
         * @returns void
         */
	function team_task_due($type="")
	{
		
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		
		$data = array();
		$data['msg']="";
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		$data['user'] = get_user_info(get_authenticateUserID());
		
		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		/**
                 * check version
                 */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())

		{
			$type = 'status';
			$data['teamtodolist'] = $this->user_model->get_teamtaskListByType($type);
			$data['task_thisweekteam'] = $this->user_model->gettimeestimationteam($task_status_completed_id,$offdays);
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$data['team'] = get_users_under_managers();
			$data['MON_hours'] = 0;
			$data['TUE_hours'] = 0;
			$data['WED_hours'] = 0;
			$data['THU_hours'] = 0;
			$data['FRI_hours'] = 0;
			$data['SAT_hours'] = 0;
			$data['SUN_hours'] = 0;
			/**
                         * accroding day there allocate task in week
                         */
			$data['user_time'] = 0;
			if($data['team']!="0")
			{
				foreach ($data['team'] as $t)
				{
					$data['user_time'] = get_calender_settings_by_user($t->user_id);
					if($data['user_time']!='0'){
					$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
					$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
					$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
					$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
					$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
					$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
					$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours;
					}
				}
			}
			
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/team_task_due',$data,TRUE);
			$this->template->render();
		}
	}
        /**
         * On Ajax request, this function will filter due date for mobile version and render team dashboard .
         * @returns view
         */
        function filter_team_task_due()
	{
		$theme = getThemeName ();
		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$type = $_POST['id'];
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			
			$data['teamtodolist'] = $this->user_model->get_teamtaskListByType($type);
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			
			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->load->view($theme.'/mobileview/user/Ajax_team_task_due',$data);
		}
	}
	/**
         * This function will render due task table for mobile version.
         * @returns view
         */
	
	function overdue_task()
	{
		
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		
		$data = array();
		$data['msg']="";
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		$data['user'] = get_user_info(get_authenticateUserID());
		
		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = "";
                        /**
                         * get overduetask list
                         */
			$data['overdue_task'] = $this->user_model->get_overduetaskList($task_status_completed_id,$offdays);
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/overdue_task',$data,TRUE);
			$this->template->render();
			
		}
		
		
	}
        /**
         * This function will create thisweek task table on team dashboard for mobile.
         * @returns view
         */
     function team_time_thisweek()
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		
		$data = array();
		$data['msg']="";
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		$data['user'] = get_user_info(get_authenticateUserID());
		

		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type = "";
			$data['task_thisweekteam'] = $this->user_model->gettimeestimationteam($task_status_completed_id,$offdays);
			$data['team'] = get_users_under_managers();
		
			$data['MON_hours'] = 0;
			$data['TUE_hours'] = 0;
			$data['WED_hours'] = 0;
			$data['THU_hours'] = 0;
			$data['FRI_hours'] = 0;
			$data['SAT_hours'] = 0;
			$data['SUN_hours'] = 0;
			
			$data['user_time'] = 0;
				if($data['team']!="0")
				{
					foreach ($data['team'] as $t)
					{
						$data['user_time'] = get_calender_settings_by_user($t->user_id);
						if($data['user_time']!='0'){
						$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
						$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
						$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
						$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
						$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
						$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
						$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours;
						}
					}
				}
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/team_time_thisweek',$data,TRUE);
			$this->template->render();
			
		}
	}
        /**
         * This function is used for create time allocation by category table on team dashboard for mobile.
         * @param $msg
         * @returns view
         */
	function team_allocation_by_category($msg="")
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}
		$theme = getThemeName ();
		$data['msg'] = $msg;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			if(!$_POST){
				$data['team_thisweek'] = $this->user_model->getTeamTimeByPeriod("this_week");
				$data['team_nextweek'] = $this->user_model->getTeamTimeByPeriod("next_week");
				$data['team_thismonth'] = $this->user_model->getTeamTimeByPeriod("this_month");
				$data['team_nextmonth'] = $this->user_model->getTeamTimeByPeriod("next_month");
			}else{
				$data['team_thisweek'] = $this->user_model->getTeamTimeByPeriod($_POST['id']);
				$data['team_nextweek'] = $this->user_model->getTeamTimeByPeriod($_POST['id']);
				$data['team_thismonth'] = $this->user_model->getTeamTimeByPeriod($_POST['id']);
				$data['team_nextmonth'] = $this->user_model->getTeamTimeByPeriod($_POST['id']);
			}
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/user/team_allocation_by_category',$data,TRUE);
			$this->template->render();
			
		}
	}
	/**
         * This function is used for remove task from watch list.When admin/user click on delete icon this function will call for delete task in db.
         * @returns int
         */
	
	function delwatch()
	{
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		
		
		$watchlist_id = $_POST['id'];
		$task_id = $_POST['task_id'];
		$this->db->delete('my_watch_list',array('id'=>$watchlist_id));
		$type = "";
		$duration='';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
                /**
                 * task status
                 */
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
		$data['watchlist'] = $this->user_model->getwatchlist($type);
		echo $task_id;
	}
	/**
         * This function will call from admin setting page.It will render user list for admin setting page .
         * @returns view
         */
	function listUser(){
		
			
			if (!check_user_authentication() || $this->session->userdata('is_administrator')=='0') {
			redirect ('home');
		}
		/**
                 * check company id
                 */
		if(!$this->session->userdata('company_id')){
			redirect('user/dashboard');
		}
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		
		$data = array();
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		$data['company_id'] = $this->session->userdata('company_id');
                /**
                 * user list
                 */
		$data['user'] = $this->user_model->get_user_list($this->session->userdata('company_id'));
		/**
                 * create user list
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/listUser',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
	}
        /**
         * This function will check user email in db.
         * @returns Json
         */
        function chk_email_exist(){
		$email = $_POST['email'];
		$user_id = $_POST['user_id'];
		$user_id = $user_id;
		if($user_id)
		{
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$email' and user_id != '".$user_id."' and company_id = '".$this->session->userdata('company_id')."' and is_deleted = 0");
		}
		else
		{
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$email' and company_id = '".$this->session->userdata('company_id')."'  and is_deleted = 0 ");	
		}	
		if($query->num_rows()>0){
			echo json_encode(FALSE);
		}else{
			echo json_encode(TRUE);
		}
		
	}
	/**
         * It will check user mail id in db and returns boolean values.
         * @param string $email
         * @returns boolean
         */
	function email_check($email){
		
		$user_id = base64_decode($_POST['user_id']);
		$email = $this->input->post('email');
                /**
                 * check user id
                 */
		if($user_id){
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$email'  and company_id = '".$this->session->userdata('company_id')."' and user_id != '".$user_id."' and is_deleted =0");
		} else {
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$email'  and company_id = '".$this->session->userdata('company_id')."' and is_deleted = 0 ");
		}
		if($query->num_rows()>0){
			$this->form_validation->set_message('email_check','There is an existing record with this Email Address.');
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
         * This function is checked that email is exist or not.
         * @returns int
         */
	function is_email_exists(){
		$value = isset($_POST["value"])?$_POST['value']:'';
		$user_id = get_authenticateUserID();
		$query = $query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$value' and user_id != '".$user_id."' and is_deleted = 0");
		$total_row = $query->num_rows(); 
                if($total_row > 1 || $total_row == 0){
			echo "0";
		} else {
			echo "1";
		}
		die;
	}
	/**
         * When user will update password this will check both password are same or not.
         * @returns int
         */
	function is_password_same()
	{
		$value = isset($_POST["value"])?$_POST['value']:'';
		$old_password = isset($_POST["password"])?$_POST['password']:'';
		if($value == $old_password)
		{
			echo "1";
		} else {
			echo "0";
		}
		die;
	}
        /**
         * When user change password, this function will check old password is correct or not in db.
         * @returns int
         */
       function is_password_correct()
	{
		$value = isset($_POST["value"])?$_POST['value']:'';
		$user_id = get_authenticateUserID();
		$query = $query = $this->db->query("select password from ".$this->db->dbprefix('users')." where password= md5('$value') and user_id = '".$user_id."' ");
		if($query->num_rows()>0){
			echo "1";
		} else {
			echo "0";
		}
		die;
	}
	/**
         * This function is used for update user password in db.
         * @returns String
         */
	
	function update_password()
	{
            /**
             * check value is set or not
             */
		$value = isset($_POST["value"])?$_POST['value']:'';
		$password_update['password'] = md5($value);
		$this->db->where("email", $this->session->userdata('email'));
                /**
                 * it update users table with new password
                 */
		$this->db->update("users", $password_update);
		echo "updated";die;
	}
        /**
         * This function is checked user id for manager.
         * @return boolean
         */
        function manager_check(){
		
		$user_id = base64_decode($_POST['user_id']);
		
		if(isset($_POST['is_manager'])){
			
		} else {
			$count = get_user_count_under_manager($user_id);
		
			if($count>0){
				$this->form_validation->set_message("manager_check","Please remove employees reporting to the user before removing manager's rights.");
				return FALSE;
			} else {
				return TRUE;
			}
		} 
		return TRUE;
	}
	
        /**
         * When admin add new user at the same time this function will call for add new user information in db.
         * It will create new subscription plan for user and update all data in db.
         * @param int $user_id
         * @returns Json 
         */

	function addUser($user_id=''){
		/**
                 * check user id
                 */
		if($this->input->post('user_id')!=''){
			
			$user_id = $this->input->post('user_id');
			$id = $this->user_model->update_user($user_id);
			if($this->input->post('user_status')){
				if($this->input->post('pre_user_status') == 'Inactive'){
					$query=$this->db->get_where('users',array('user_id'=>$user_id));
					$use=$query->row();
					
					$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
					
					$company=$query1->row();
					
					
					$query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
					$company_plan=$query_plan->row();
					
					if($company_plan){
						$component_id = $company_plan->chargify_component_id;
					} else {
						$component_id = 0;

					}
					
					$test = TRUE;
                                        /**
                                         * create new object of chargify class
                                         */
					$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
					
					if($company->chargify_subscriptions_ID != '')
					{
						try{	
							$new_qty=count_user_by_company($use->company_id);
							$Qty->allocated_quantity = $new_qty;
					
							$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
							
						}catch (ChargifyValidationException $cve) { 
							 $data["error"]=$cve->getMessage(); //die;die;
						}catch(ChargifyConnectionException $d){
                                                    
                                                }
					}
					
				}
			} else {
				if($this->input->post('pre_user_status') == 'Active'){
					$query=$this->db->get_where('users',array('user_id'=>$user_id));
					$use=$query->row();
					
					$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
					
					$company=$query1->row();
					
					$query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
					$company_plan=$query_plan->row();
					
					if($company_plan){
						$component_id = $company_plan->chargify_component_id;
					} else {
						$component_id = 0;
					}
					
					$test = TRUE;
					$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
					
					if($company->chargify_subscriptions_ID !='')
					{
						try{
							$new_qty=count_user_by_company($use->company_id);
							$Qty->allocated_quantity = $new_qty;
							$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
						}catch (ChargifyValidationException $cve) { 
					 		 $data["error"]=$cve->getMessage(); //die;die;
						}catch(ChargifyConnectionException $d){
                                                    
                                                }
					}
				}
			}
                        $return['user_count'] = count_user_by_company($this->session->userdata('company_id'));
			$return['user'] = get_user_info($id);
			$return['tags_division'] = get_user_division($id);
			$return['tags_department'] = get_user_department($id);
			$return['staff_level'] = get_staff_level($return['user']->staff_level);
			echo json_encode($return);die;
		} else {
			$id = $this->user_model->insert_user();
			
			if($this->input->post('user_status') == 'Active'){
							
				$query=$this->db->get_where('users',array('user_id'=>$id));
				$use=$query->row();
				
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
				$company_plan=$query_plan->row();
				
				if($company_plan){
					$component_id = $company_plan->chargify_component_id;
				} else {
					$component_id = 0;
				}
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				
				if($company->chargify_subscriptions_ID != '')
				{
					try{	
						$new_qty=count_user_by_company($use->company_id);
						$Qty->allocated_quantity = $new_qty;
			
						$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
			
					}catch (ChargifyValidationException $cve) { 
						 echo $data["error"]=$cve->getMessage(); //die;die;
					}catch(ChargifyConnectionException $d){
                                                    
                                        }
				}
			}
			$return['user_count'] = count_user_by_company($use->company_id);
			$return['user'] = get_user_info($id);
			$return['tags_division'] = get_user_division($id);
			$return['tags_department'] = get_user_department($id);
			$return['staff_level'] = get_staff_level($return['user']->staff_level);
			echo json_encode($return);die;
		}
		
	}
        /**
         * This function will fetch information from db and return on request.
         * 
         * @returns Json
         */

        
	function editUser(){
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		
		$data = array();
		$data['user_info'] = $this->user_model->get_user_details($user_id);
		$data['tags_division'] = $this->user_model->get_division_list($user_id);
		$data['tags_department'] = $this->user_model->get_department_list($user_id);
		$data['tags_skills'] = $this->user_model->get_skills_list($user_id);
		$data['count'] = get_user_count_under_manager($user_id);
		echo json_encode($data);die;

	}
	/**
         * When admin add new user,this function will set division view on admin setting.
         * @returns view
         */
	function divisions(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$data['tags_division'] = get_user_division($user_id);
		$company_division = get_company_division($this->session->userdata('company_id'),'Active');
		$company_division_quots = '';
		if($company_division){
			foreach($company_division as $div){
				$company_division_quots .= "'".$div->devision_title."',";
			}
			$company_division_quots = substr($company_division_quots, 0,-1);
		}

		$data['company_division'] = $company_division_quots;
		echo $this->load->view($theme.'/layout/user/ajaxDivision',$data,TRUE); die;
	}
	/**
         * This function will set department list on add user form on admin setting.
         * @returns view 
         */
	
	function departments(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$division_id = isset($_POST['division_id'])?$_POST['division_id']:'';
		$div_id = array();
			
		if($division_id){
			$tags_division = explode(',', $division_id);
			$array_text = array();
			if(isset($tags_division) && $tags_division!=''){
				foreach($tags_division as $row){
					$id = $this->user_model->get_division_id_by_name($row);
					$div_id[] = $id;
				}
			}
		}
		
		
		$data['tags_department'] = get_user_department($user_id);
                /**
                 * check division id
                 */
		if($div_id){
			$company_department = $this->user_model->get_company_department_list($div_id);
			//$company_department = get_company_department($this->session->userdata('company_id'),'Active');
			$company_department_quots = '';
			if($company_department){
				foreach($company_department as $dep){
					$company_department_quots .= "'".$dep->department_title."',";
	
				}
				$company_department_quots = substr($company_department_quots, 0,-1);
			}
			$data['company_department'] = $company_department_quots;
		} else {
			$data['company_department'] = '';
		}
		echo $this->load->view($theme.'/layout/user/ajaxDepartment',$data,TRUE); die;
	}
	/**
         * It will add skills in add user form on ajax request on admin setting.
         * @returns view
         */
	function skills(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		/**
                 * get skill
                 */
		$data['tags_skills'] = $this->user_model->get_user_skill($user_id);
		
		$company_skills = get_company_skills($this->session->userdata('company_id'),'Active');
		$company_skills_quots = '';
		if($company_skills){
			foreach($company_skills as $sk){
				$company_skills_quots .= "'".$sk->skill_title."',";
			}
			$company_skills_quots = substr($company_skills_quots, 0,-1);
		}
		$data['company_skills'] = $company_skills_quots;
		echo $this->load->view($theme.'/layout/user/ajaxSkills',$data,TRUE); die;
	}
	/**
         * This function will create report to view for add user on admin setting.
         * @returns view
         */
	function reports_to(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		
		$data['user_id'] = $user_id;
		$data['managers'] = $this->user_model->get_managers($this->session->userdata('company_id'));
		$data['user_managers'] = $this->user_model->get_user_managers($user_id);
		echo $this->load->view($theme.'/layout/user/userReportsTo',$data,TRUE);die;
	}

	/**
         * This function is used for delete user from list on delete icon click.
         * @returns string
         */
	function deleteUser(){
		
		$id = $_POST['user_id'];
		
		$query=$this->db->get_where('users',array('user_id'=>$id));
		$use=$query->row();
						
		if($use->is_administrator !='1')
		{	
			$this->db->where("user_id",$id);
			$this->db->update("users",array("is_deleted"=>1));
		}
		
		$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
		$company=$query1->row();
		
		$query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
		$company_plan=$query_plan->row();
		
		if($company_plan){
			$component_id = $company_plan->chargify_component_id;
		} else {
			$component_id = 0;
		}
		
		$test = TRUE;
		$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
		
		if($company->chargify_subscriptions_ID != '')
		{
			try{	
				$new_qty=count_user_by_company($use->company_id);
				$Qty->allocated_quantity = $new_qty;
				$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
			}catch (ChargifyValidationException $cve) { 
				 echo $data["error"]=$cve->getMessage(); //die;die;
			}
		}
		echo count_user_by_company($use->company_id); die();
	}
	/**
         * This method is accessed when the USER click on my setting link in user settings.  
         * It is having checks whether an authanticate user acceessing or not. Enternally accessing methods 
         * user_model->get_user_info to fetch the user info for prepopulating them on edit view, 
         * also accessing other methods such as get_user_active_colors to show on edit setting view. 
         * @returns view
         */
	function my_settings(){
                if(isset($_GET['userid'])){
                    if (!check_user_authentication()) {
			redirect ('home/login?userid='.$_GET['userid']);
		}
                }else{
                    if (!check_user_authentication()) {
			redirect ('home');
                    }
                }
		/**
                 * check company id
                 */
		if(!$this->session->userdata('company_id')){
			redirect('user/dashboard');
		}
		/**
                 * load Amazon S3 configuration
                 */
		$this->config->load('s3');
		$theme = getThemeName();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		$user_info = $this->user_model->get_user_details($user_id);
		$completed_id = $this->config->item('completed_id');	
		$data['MON_hours'] = minutesToTime($user_info->MON_hours);
		$data['TUE_hours'] = minutesToTime($user_info->TUE_hours);
		$data['WED_hours'] = minutesToTime($user_info->WED_hours);
		$data['THU_hours'] = minutesToTime($user_info->THU_hours);
		$data['FRI_hours'] = minutesToTime($user_info->FRI_hours);
		$data['SAT_hours'] = minutesToTime($user_info->SAT_hours);
		$data['SUN_hours'] = minutesToTime($user_info->SUN_hours);
		$data['MON_closed'] = $user_info->MON_closed;
		$data['TUE_closed'] = $user_info->TUE_closed;
		$data['WED_closed'] = $user_info->WED_closed;
		$data['THU_closed'] = $user_info->THU_closed;
		$data['FRI_closed'] = $user_info->FRI_closed;
		$data['SAT_closed'] = $user_info->SAT_closed;
		$data['SUN_closed'] = $user_info->SUN_closed;
		$data['MON_hours_min'] = $user_info->MON_hours;
		$data['TUE_hours_min'] = $user_info->TUE_hours;
		$data['WED_hours_min'] = $user_info->WED_hours;
		$data['THU_hours_min'] = $user_info->THU_hours;
		$data['FRI_hours_min'] = $user_info->FRI_hours;
		$data['SAT_hours_min'] = $user_info->SAT_hours;
		$data['SUN_hours_min'] = $user_info->SUN_hours;
		
		$data['colors'] = $this->user_model->get_user_colors($user_id);
		$data['default_color'] = $user_info->default_color;
		$data['active_colors'] = $this->user_model->get_user_active_colors($this->session->userdata('user_id'));
		$data['user_default_swimlane']= $this->user_model->get_default_swimlanes_info($this->session->userdata('user_id'));
		$data['task_swimlane_ids'] = swim_task_ids();
		$data['result'] = $this->user_model->get_swimlanes($user_id);
		
		$data["old_password"] = '';
		$data["password"] = '';
		$data["confirm_password"] = '';
		$data['error'] = '';		
		$data['total_tasks'] = getTotalTaskUser($completed_id);
                $data['total_projects'] = getTotalProjectsUser();
                $data['total_customers'] = getTotalCustomersUser();
               
		$data['first_name'] = $user_info->first_name;
		$data['last_name'] = $user_info->last_name;
		$data['email'] = $user_info->email;
		
		$data['tags_division'] = get_user_division($user_id);
		$data['tags_department'] = get_user_department($user_id);
		$data['profile_image'] = $user_info->profile_image;
		$data['user_time_zone'] = $user_info->user_time_zone;
                $data['user_background_type'] = $user_info->user_background_type;
		$data['user_background_name'] = $user_info->user_background_name;
		$data['pre_email'] = $user_info->email;
		$data['user_default_page'] = $user_info->user_default_page;	
		$data['pre_email'] = $user_info->email;
		$data['daily_email_summary'] = $user_info->daily_email_summary;
		$data['timezone'] = get_timezone();
		$data['contact_no'] = $user_info->contact_no;
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
		$data['approver_id'] = $user_info->timesheet_approver_id;
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		/**
                 * render my setting
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/my_settings',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
	}
/**
 * This function is used when user update setting like first name,last name,department etc.
 * It will update user information in db.And when user change mail id it will send mail activation link for new mail id.
 * @returns void
 */
	function mysetting_index(){
				
		$user_id = $this->session->userdata("user_id");	
		$name = isset($_POST['name'])?$_POST['name']:'';
		$value = isset($_POST['value'])?$_POST['value']:'';
		$data = array(
			$name => $value
		);
		
		if($_POST['name'] == 'tags_division'){
			$tags_division = explode(',', $_POST['value']);
			if($tags_division){
				$this->db->delete('user_devision',array('user_id'=>$user_id));
				foreach($tags_division as $row){
					$id = $this->user_model->get_division_id_by_name($row);
					$division_data = array(
						'devision_id' => $id,
						'user_id' => $user_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('user_devision',$division_data);
				}
			}else{
				$this->db->delete('user_devision',array('user_id'=>$user_id));
			}
		}else if($_POST['name'] == 'tags_department'){
			$tags_department = explode(',', $_POST['value']);
			if($tags_department){
				$this->db->delete('user_department',array('user_id'=>$user_id));
				foreach($tags_department as $row){
					$id = $this->user_model->get_department_id_by_name($row);
					$department_data = array(
						'dept_id' => $id,
						'user_id' => $user_id,
						'date_added' => date('Y-m-d H:i:s')
					);
					$this->db->insert('user_department',$department_data);
				}
			}else{
				$this->db->delete('user_department',array('user_id'=>$user_id));
			}
		}else{
			$user_info = get_user_info($user_id);
			if($_POST['name'] == 'email'){
				if($user_info->email != $this->input->post('value')){
					$code = randomCode();
					$data1 = array('verify_email'=>0,'user_status'=>'Inactive','email_verification_code'=>$code);
					$this->db->where('user_id',$user_id);
					$this->db->update('users',$data1);
					
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='verify email'");
					$email_temp=$email_template->row();	
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
					
					$email_subject=$email_temp->subject;				
					$email_message=$email_temp->message;
					
					$data_pass = base64_encode($user_id."1@1".$code);
					
					$activation_link = "<a href='".base_url()."home/activation/".$data_pass."' target='_blank'>Activation link</a>";
					
					$user_name = $user_info->first_name.' '.$user_info->last_name;
					
					$email_to = $this->input->post('value');
					
					
					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{activation_link}',$activation_link,$email_message);		
					
					
					$str=$email_message;
                                        $sandgrid_id=$email_temp->sandgrid_id;
                                        $sendgriddata = array('subject'=>'verify email',
                                            'data'=>array('activation_link'=>$activation_link));
                                        if($sandgrid_id)
                                        {
                                            mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,'',$email_subject,$sandgrid_id,$sendgriddata);
                                        }else{
                                            email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                                        }
					
					$this->session->set_userdata("email",$this->input->post('value'));
					
				}
			}
			if($_POST['name'] == 'first_name'){
				$this->session->set_userdata("username",$value." ".$user_info->last_name);
			}
			if($_POST['name'] == 'last_name'){
				$this->session->set_userdata("username",$user_info->first_name." ".$value);
			}
			$this->db->where('user_id',$this->session->userdata("user_id"));
			$this->db->update('users',$data);
		}
		
	}
        /**
         * On mysetting,when user will change default calender setting at the same time this function will update calender setting in db. 
         * @returns int
         */

	function save_user_calendar_settings(){
		$name = $_POST['name'];
		$value = $_POST['val'];
		
		$data = array(
			$name => $value
		);
		/**
                 * update query
                 */
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->where('comapny_id','0');
		$this->db->update('default_calendar_setting',$data);
		return 1;
	}
	/**
         * On user setting,when user will update color name. This function update color name in db. 
         * @returns void 
         */
	function update_color_name(){
		$name = $_POST['name'];
		$value = $_POST['value'];
		$color_id = str_replace("name_", "", $name);
		$data = array(
			'name' => $value
		);
		
		$this->db->where('user_color_id',$color_id);
		$this->db->update('user_colors',$data);
	}
        /**
         * When user is updated color name this function will check color name in db.
         * @returns Json
         */
	function chk_colorName_exists(){
		$name = $_POST['name'];
		$color_id = $_POST['color_id'];
		
		if($color_id){
			$query = $this->db->query("select name from ".$this->db->dbprefix('user_colors')." where name = '".$name."' and user_color_id != '".$color_id."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		} else {
			$query = $this->db->query("select name from ".$this->db->dbprefix('user_colors')." where name= '".$name."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		}
		if($query->num_rows()>0){
			echo json_encode(FALSE);
		}else{
			echo json_encode(TRUE);
		}
		
	}
	/**
         * On user setting,this function will update color status in db.
         * @returns void
         */
	function updateColorStatus(){
		$color_id = $_POST['id'];
		$value = $_POST['val'];
		$data = array(
			'status' => $value
		);
		
		$this->db->where('user_color_id',$color_id);
		$this->db->update('user_colors',$data);
	}
	
        /**
         * This function will update swimlane in db on user setting.
         * @returns void 
         */
	function update_swimlane_name(){
		$name = $_POST['name'];
		$value = htmlspecialchars($_POST['value']);
		$swimlane_id = str_replace("sname_", "", $name);
		$data = array(
			'swimlanes_name' => $value
		);
		
		$this->db->where('swimlanes_id',$swimlane_id);
		$this->db->update('swimlanes',$data);
	}
	/**
         * This function will check swimlane name in db on add new swim lane.
         * @returns Json 
         */
	function chk_swimlaneName_exists(){
		$name = $_POST['name'];
		$swimlanes_id = $_POST['swimlanes_id'];
		
		if($swimlanes_id){
			$query = $this->db->query("select swimlanes_name from ".$this->db->dbprefix('swimlanes')." where swimlanes_name = '".$name."' and swimlanes_id != '".$swimlanes_id."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		} else {
			$query = $this->db->query("select swimlanes_name from ".$this->db->dbprefix('swimlanes')." where swimlanes_name= '".$name."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		}
		if($query->num_rows()>0){
			echo json_encode(FALSE);
		}else{
			echo json_encode(TRUE);
		}
	}
	/**
         * This function will add new swim lane in db.
         * @returns json
         */
	function addSwimlanes(){
		$last_seq = $this->user_model->get_last_seq();
		$data = array(
			'swimlanes_name' => htmlspecialchars($this->input->post('swimlanes_name')),
			'user_id' => $this->session->userdata('user_id'),
			'seq'=> $last_seq+1,
			'date_added' => date('Y-m-d H:i:s'),
			'is_deleted' => '0'
                );
		$this->db->insert('swimlanes',$data);
		$swimlanes_id = $this->db->insert_id();
		
		$return_data['swimlanes_name'] = $this->input->post('swimlanes_name');
		$return_data['swimlanes_id'] = $swimlanes_id;
                $return_data['seq'] = $last_seq+1;
                echo json_encode($return_data);die;
	}
        /**
         * When user will update progile image at the same time this function will call for change image in db.
         * It will upload new image through S3 library and delete old image from folder.
         * @returns Json
         */
	function myprofile_logo()
	{
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
	 			
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, CI_S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, CI_S3::ACL_PUBLIC_READ)){
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
			'profile_image' => $s3_profile_image
		);
		
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->update('users',$data);
		
		$pass = $this->config->item('s3_display_url').'upload/user/'.$s3_profile_image;
		echo json_encode($pass);die;
	}

        /**
         * This function is used for update user profile information in db for mobile version user setting.
         * @returns void
         */
	function myprofile()
	{
		
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		if(!$this->session->userdata('company_id')){
			
			redirect('user/dashboard_menu');
		}
		
		$data['msg']='';
		$this->config->load('s3');
		$theme = getThemeName ();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		$user_info = $this->user_model->get_user_details($user_id);
		
		$this->form_validation->set_rules('first_name','First name','required|alpha_space|max_length[25]');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|alpha_space|max_length[25]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email||callback_email_check');
		/**
                 * form validation
                 */
		if($this->form_validation->run() == FALSE){
			if(validation_errors()){
				$data['error'] = validation_errors();
			} else {
				$data['error'] = '';
			}
			
			if($user_id){
                            /**
                             * user details
                             */
				$user_info = $this->user_model->get_user_details($user_id);
		
				$data['first_name'] = $user_info->first_name;
				$data['last_name'] = $user_info->last_name;
				$data['email'] = $user_info->email;
				
				$data['profile_image'] = $user_info->profile_image;
				$data['pre_email'] = $user_info->email;
				
			} else {
				$data['first_name'] = $this->input->post('first_name');
				$data['last_name'] = $this->input->post('last_name');
				$data['email'] = $this->input->post('email');
				$data['profile_image'] = $this->input->post('profile_image');
				
			}
			
			$data['pre_email'] = $user_info->email;
			
			if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
			{
				$this->template->set_master_template($theme .'/template_mobile.php');
				
				$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
				$this->template->write_view('center',$theme .'/mobileview/user/myprofile',$data,TRUE);
				$this->template->render();
			}
		} else {
			$user_id = base64_decode($this->input->post('user_id'));
			$id = $this->user_model->update_profile($user_id);
			if($id == 'sent'){
				$query=$this->db->get_where('users',array('user_id'=>$user_id));
				$use=$query->row();
				
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
				$company_plan=$query_plan->row();
				
				if($company_plan){
					$component_id = $company_plan->chargify_component_id;
				} else {
					$component_id = 0;
				}
				
				$test = TRUE;
                                /**
                                 * create new object of Chargify class
                                 */
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				
				if($company->chargify_subscriptions_ID != '')
				{
					try{	
						$new_qty=count_user_by_company($use->company_id);
						$Qty->allocated_quantity = $new_qty;
				
						$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
				
					}catch (ChargifyValidationException $cve) { 
				 		$data["error"]=$cve->getMessage(); //die;die;
					}
				}
			}
			$this->session->set_flashdata('msg','update_profile');
			redirect('user/myprofile');
			
		}
		
	}
        /**
         * This function is used for delete profile image from spacific folder and db for mobile version.
         * @param type $user_id
         * @returns void
         */
	function deleteProfileImage($user_id)
	{
		$image = getImageName($user_id);
		/**
                 * check image name
                 */
		if($image!='' && is_file('upload/user_orig/'.$image))
		{
			$link=base_path().'upload/user_orig/'.$image;
                        /**
                         * delete image
                         */
			unlink($link);
		}
		if($image!='' && is_file('upload/user/'.$image))
		{
			$link=base_path().'upload/user/'.$image;
			unlink($link);
		}
		echo $this->db->where('user_id',get_authenticateUserID())->update('users',array('profile_image'=>''));die;
		
	}
        /**
         * On user setting,this function will create default color list.
         * @returns view
         */
	function colors(){ 
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
                /**
                 * get user colors
                 */
		$data['colors'] = $this->user_model->get_user_colors($user_id);
		/**
                 * render color list
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/colors',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
	}
	/**
         * This function will set default color name on color setting on user setting.
         * @returns view
         */
	function default_color()
	{	
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$data['active_colors'] = $this->user_model->get_user_active_colors($this->session->userdata('user_id'));
		$user_info = $this->user_model->get_user_details($this->session->userdata('user_id'));
		$data['default_color'] = $user_info->default_color;
		
		$this->load->view($theme.'/layout/user/ajaxDefaultColor', $data);
	}
	/**
         * When user will update color name at the same time this function will check color name in db.
         * @return boolean
         */
	function name(){
		$color_name = $_POST['name'];
		$color_id = base64_decode($_POST['color_id']);
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($color_id){
			$query = $this->db->query("select name from ".$this->db->dbprefix('user_colors')." where name= '".$color_name."' and user_color_id != '".$color_id."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		} else {
			$query = $this->db->query("select name from ".$this->db->dbprefix('user_colors')." where name= '".$color_name."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		}
		
		if($query->num_rows()>0){
			$this->form_validation->set_message('name','This color name already exists.');
			return FALSE;
		}else{
			return TRUE;
		}
		
	}
	/**
         * When user will update color code at the same time this function will check color code in db.If color code exist it will show appropriate message on page.
         * @return boolean
         */
	function color_code(){
		$color_code = $_POST['color_code'];
		$color_id = base64_decode($_POST['color_id']);
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($color_id){
			$query = $this->db->query("select color_code from ".$this->db->dbprefix('user_colors')." where color_code= '".$color_code."' and user_color_id != '".$color_id."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0");
 		} else {
 			$query = $this->db->query("select color_code from ".$this->db->dbprefix('user_colors')." where color_code= '".$color_code."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
 		}
		
		if($query->num_rows()>0){
			$this->form_validation->set_message('color_code','This color code already exists.');
			return FALSE;
		}else{
			return TRUE;
		}
		
	}
	/**
         * In schedullo have no option to add new color in admin and user setting both.
         */
	function addColors($color_id = ''){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		$data['color_id'] = $color_id = base64_decode($color_id);
	
		$this->form_validation->set_rules('color_name','Color name','required');
		$this->form_validation->set_rules('name', 'Name', 'required|callback_name');
		$this->form_validation->set_rules('color_code', 'Color code', 'required|callback_color_code');
		$this->form_validation->set_rules('status', 'Status', 'required');
		
		
		if($this->form_validation->run() == FALSE){
			if(validation_errors()){
				$data['error'] = validation_errors();
			} else {
				$data['error'] = '';
			}
			
			if($_POST){
				$data['color_name'] = $this->input->post('color_name');
				$data['name'] = $this->input->post('name');
				$data['color_code'] = $this->input->post('color_code');
				$data['outside_color_code'] = $this->input->post('outside_color_code');
				$data['status'] = $this->input->post('status');
				
			} else {
				
				if($color_id){
					$color = $this->user_model->get_color_detail($color_id);
					
					$data['color_name'] = $color->color_name;
					$data['name'] = $color->name;
					$data['color_code'] = $color->color_code;
					$data['outside_color_code'] = $color->outside_color_code;
					$data['status'] = $color->status;
					
				} else {
					$data['color_name'] = '';
					$data['name'] = '';
					$data['color_code'] = '';
					$data['outside_color_code'] = '';
					$data['status'] = '';
				}
			}
			
			$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
			$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
			$this->template->write_view('content_side',$theme .'/layout/user/addColors',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
			$this->template->render();
		} else {
			if($this->input->post('color_id')){
				$color_id = base64_decode($this->input->post('color_id'));
				$id = $this->user_model->update_colors($color_id);
				$this->session->set_flashdata('msg','update_color');
				redirect('user/colors');
			} else {
				$id = $this->user_model->insert_colors();
				$this->session->set_flashdata('msg','insert_color');
				redirect('user/colors');
			}
		}
	}
	/**
         * This function will render swim lane view on user settings.It will fetch swimlane details from db and create swim lane view.
         * @returns view
         */
	function swimlanes(){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		
		$data['task_swimlane_ids'] = swim_task_ids();
		$data['result'] = $this->user_model->get_swimlanes($user_id);
	
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/swimlanes',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
	}
        /**
         * This function is used for add new swimlane on user setting.When user click on add button this function will call for add new swimlane in db.
         * @param $swimlanes_id
         * @returns void
         */
	function addSwimlane($swimlanes_id = ''){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		$data['swimlanes_id'] = $swimlanes_id = base64_decode($swimlanes_id);
		
		$this->form_validation->set_rules('swimlanes_name','Swimlane name','required|alpha_space');
		$this->form_validation->set_rules('swimlanes_desc', 'Swimlane description', 'required');
		/* Check form validation for insert swimlane data in db*/
		if($this->form_validation->run() == FALSE){
			if(validation_errors()){
				$data['error'] = validation_errors();
			} else {
				$data['error'] = '';
			}
			
			if($swimlanes_id){
				$swimlane = $this->user_model->get_swimlane_detail($swimlanes_id);
				$data['swimlanes_name'] = $swimlane->swimlanes_name;
				$data['swimlanes_desc'] = $swimlane->swimlanes_desc;
			} else {
				$data['swimlanes_name'] = $this->input->post('swimlanes_name');
				$data['swimlanes_desc'] = $this->input->post('swimlanes_desc');
			}
			
			
			$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
			$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
			$this->template->write_view('content_side',$theme .'/layout/user/addSwimlane',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
			$this->template->render();
		} else {
			if($this->input->post('swimlanes_id')){
				$swim_id = base64_decode($this->input->post('swimlanes_id'));
				$id = $this->user_model->update_swimlane($swim_id);
				$this->session->set_flashdata('msg','update_swimlanes');
				redirect('user/swimlanes');
			} else {
				$id = $this->user_model->insert_swimlane();
				$this->session->set_flashdata('msg','insert_swimlanes');
				redirect('user/swimlanes');
			}
		}
	}
        /**
         * When user click on delete icon of swim lane option this function will delete swimlane from db.
         * @returns int
         */
	function deleteSwimlane(){
		$swim_id = $_POST['id'];
		$this->db->delete('swimlanes',array('swimlanes_id'=>$swim_id));
                echo "done";die;
	}
	/**
         * It will delete all swim lanes from db.
         * @returns void
         */
	function deleteAllSwimlane(){
		$this->db->delete('swimlanes',array('user_id'=>$this->session->userdata('user_id')));
		$this->session->set_flashdata('msg','delete_swimlane');
		redirect('user/swimlanes');
	}
	/**
         * This function will update swim lane order in db. Using up-down icon this function will update order of swim lane in db.
         * @returns void
         */
	function set_swim_seq(){
		if($_POST['new_position']){
			$new_position = $_POST['new_position'];
                        foreach($new_position as $key=>$value){
                            $swimlane = explode('_', $key);
                            $value = strip_tags($value);
                            $this->db->set('seq',$value);
                            $this->db->where('swimlanes_id',$swimlane[1]);
                            $this->db->where('is_deleted','0');
                            $this->db->update('swimlanes');
                        }
                    echo "done"; die();
                }
	}
	/**
         * This function is used for update color sequence in db.It will update when user click on up-down icon on color page in user setting.
         * @returns void
         */
	function set_colors_seq(){
		if($_POST['new_position']){
			$new_position = $_POST['new_position'];
                        foreach($new_position as $key=>$value){
                            $color = explode('_', $key);
                            $value = strip_tags($value);
                            $this->db->set('seq',$value);
                            $this->db->where('user_color_id',$color[1]);
                            $this->db->where('is_deleted','0');
                            $this->db->update('user_colors');
                        }
                    echo "done"; die();
                }
	}
       /**
        * This function will check color name in db .
        * @returns json
        */
	function chk_name(){
		$color_name = $_POST['name'];
		$color_id = $_POST['color_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		/**
                 * check color id
                 */
		if($color_id){
			$query = $this->db->query("select name from ".$this->db->dbprefix('user_colors')." where name = '".$color_name."' and user_color_id = '".$color_id."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		} else {
			$query = $this->db->query("select name from ".$this->db->dbprefix('user_colors')." where name= '".$color_name."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
		}
		/**
                 * check query give some value or not
                 */
		if($query->num_rows()>0){
			echo json_encode(FALSE);
		}else{
			echo json_encode(TRUE);
		}
		
	}
	/**
         * This function will check color code in db.
         * @returns json
         */
	function chk_color_code(){
		$color_code = $_POST['color_code'];
		$color_id = $_POST['color_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		if($color_id){
			$query = $this->db->query("select color_code from ".$this->db->dbprefix('user_colors')." where color_code= '".$color_code."' and user_color_id = '".$color_id."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
 		} else {
 			$query = $this->db->query("select color_code from ".$this->db->dbprefix('user_colors')." where color_code= '".$color_code."' and user_id = '".$this->session->userdata('user_id')."' and is_deleted = 0 ");
 		}
		
		if($query->num_rows()>0){
			echo json_encode(FALSE);
		}else{
			echo json_encode(TRUE);
		}
		
	}
        /**
         * This function will render user profile on mobile view.
         * @returns view 
         */
	function my_profile(){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/my_profile',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
	}
	
	
	/**
         * When user change password at the same time this function will check old password in db.
         * @param $password
         * @return boolean
         */
	function old_password_check($password)
	{
		$flg = $this->user_model->password_check($password);
		if($flg == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('old_password_check', 'Please Enter Correct Old Password.');
			return FALSE;
		}
	}
        /**
         * This function is used for change user password in db.It will render change password page.After that it will update password in db.
         * @returns void
         */
	function change_password(){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		$data["error"] = '';
		/**
                 * form validation rules
                 */		
		$this->form_validation->set_rules('old_password','Old password','required|callback_old_password_check');
		$this->form_validation->set_rules('password', 'New password', 'required|min_length[8]|max_length[15]');
		$this->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]');
		/**
                 * check send data method
                 */
		if ($_POST)
		{        
			if ($this->form_validation->run() == FALSE)
			{
				if (validation_errors())
				{
					$data["error"] .= validation_errors();
				} else {
					$data["error"] .= "";
				}
	
			} else {
					/**
                                         * it call change_password method of user model for change password.
                                         */
				$cahngepassword = $this->user_model->change_password(md5($this->input->post("old_password")));				
				if($cahngepassword==1)
				{
					$password_update['password'] = md5($this->input->post('password'));
					$this->db->where("user_id",$this->session->userdata('user_id'));
		    		$this->db->update("users", $password_update);
					
					$this->session->set_flashdata('msg','changepass');
					redirect('user/change_password');
								
				} else {
					$data['error'] = 'Your old password is wrong.';
				}
			}
			$data["old_password"] = $this->input->post('old_password');
			$data["password"] = $this->input->post('password');
			$data["confirm_password"] = $this->input->post('confirm_password');
		} else {
			$data["old_password"] = '';
			$data["password"] = '';
			$data["confirm_password"] = '';
		}
		/**
                 * render change password page
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/change_password',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
	}
        /**
         * When user apply filter on dashboard this function will create page according to filter value.It will fetch data from db and render page.
         * @returns view
         */
	function todo_Ajax()
	{
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		$type = $this->input->post('type');
		$duration = $this->input->post('duration');
		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		$data['todolist'] = $this->user_model->get_taskList($type,$duration,$task_status_completed_id,$offdays);
		
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
		$this->load->view($theme.'/layout/user/todo_Ajax', $data);
	}
        /**
         * On ajax request,it will show next week task in dashboard.
         * @returns void
         */
	function task_nextweek()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$user_id = $this->input->post('user_id');
		/**
                 * get task next week
                 */
		$data['task_nextweek'] = $this->user_model->gettimeestimationnext();
		$data['user_time'] = 0;
		$data['user_time'] = get_calender_settings_by_user(get_authenticateUserID());
		$this->load->view($theme.'/layout/user/task_nextweek_Ajax', $data);
	}
	/**
         *On ajax request,it will show previous week task in dashboard.
         * @returns void
         */
	function task_previousweek()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$user_id = $this->input->post('user_id');
		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		$data['task_thisweek'] = $this->user_model->gettimeestimation($task_status_completed_id,$offdays);
		$data['user_time'] = 0;
		$data['user_time'] = get_calender_settings_by_user(get_authenticateUserID());
		$this->load->view($theme.'/layout/user/task_previousweek_Ajax', $data);
	}
	
	/**
         * This function will show nextweek task on team dashboard on button click from team board.
         * @returns view
         */
function taskteam_nextweek()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$user_id = $this->input->post('user_id');
		
		$data['task_nextweekteam'] = $this->user_model->gettimeestimationnextteam();
		$data['team'] = get_users_under_managers();
		
		$data['MON_hours'] = 0;
		$data['TUE_hours'] = 0;
		$data['WED_hours'] = 0;
		$data['THU_hours'] = 0;
		$data['FRI_hours'] = 0;
		$data['SAT_hours'] = 0;
		$data['SUN_hours'] = 0;
		$data['user_time'] = 0;
		if($data['team']!="0")
		{
			foreach ($data['team'] as $t)
			{
				$data['user_time'] = get_calender_settings_by_user($t->user_id);
				if($data['user_time']!='0'){
				$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
				$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
				$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
				$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
				$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
				$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
				$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours; 	
				}
			}
			
		}
		$this->load->view($theme.'/layout/user/taskteam_nextweek_Ajax', $data);
	}
	/**
         * This function will show nextweek task on team dashboard on button click from team board for mobile version.
         * @returns view
         */
	function task_team_nextweek()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$user_id = $this->input->post('user_id');
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['task_nextweekteam'] = $this->user_model->gettimeestimationnextteam();
			$data['team'] = get_users_under_managers();
			$data['MON_hours'] = 0;
			$data['TUE_hours'] = 0;
			$data['WED_hours'] = 0;
			$data['THU_hours'] = 0;
			$data['FRI_hours'] = 0;
			$data['SAT_hours'] = 0;
			$data['SUN_hours'] = 0;
			$data['user_time'] = 0;
			if($data['team']!="0")
			{
				foreach ($data['team'] as $t)
				{
					$data['user_time'] = get_calender_settings_by_user($t->user_id);
					if($data['user_time']!='0'){
					$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
					$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
					$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
					$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
					$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
					$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
					$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours; 	
					}
				}
				
			}
			$this->load->view($theme.'/mobileview/user/task_team_nextweek', $data);	
		}
		
	}
	/**
         * This function will show previousweek task on team dashboard on button click from team board.
         * @returns view
         */
	function taskteam_previousweek()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$user_id = $this->input->post('user_id');
		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		$data['task_thisweekteam'] = $this->user_model->gettimeestimationteam($task_status_completed_id,$offdays);
		$data['team'] = get_users_under_managers();
		
		$data['MON_hours'] = 0;
		$data['TUE_hours'] = 0;
		$data['WED_hours'] = 0;
		$data['THU_hours'] = 0;
		$data['FRI_hours'] = 0;
		$data['SAT_hours'] = 0;
		$data['SUN_hours'] = 0;
		$data['user_time'] = 0;
		if($data['team']!="0")
		{
			foreach ($data['team'] as $t)
			{
				$data['user_time'] = get_calender_settings_by_user($t->user_id);
				if($data['user_time']!='0'){
				$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
				$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
				$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
				$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
				$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
				$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
				$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours; 
				}	
			}
			
		}
		$this->load->view($theme.'/layout/user/taskteam_previousweek_Ajax', $data);
	}
        /**
         * This function will show previousweek task on team dashboard on button click from team board for mobile version.
         * @returns view
         */
	function task_team_previousweek()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$user_id = $this->input->post('user_id');

		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['task_thisweekteam'] = $this->user_model->gettimeestimationteam($task_status_completed_id,$offdays);
			$data['team'] = get_users_under_managers();
			
			$data['MON_hours'] = 0;
			$data['TUE_hours'] = 0;
			$data['WED_hours'] = 0;
			$data['THU_hours'] = 0;
			$data['FRI_hours'] = 0;
			$data['SAT_hours'] = 0;
			$data['SUN_hours'] = 0;
			$data['user_time'] = 0;
			if($data['team']!="0")
			{
				foreach ($data['team'] as $t)
				{
					$data['user_time'] = get_calender_settings_by_user($t->user_id);
					if($data['user_time']!='0'){
					$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
					$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
					$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
					$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
					$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
					$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
					$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours; 
					}	
				}
				
			}
			$this->load->view($theme.'/mobileview/user/task_team_previousweek', $data);
		}
	}
/**
 * This function is used for draw chart on dashboard.It will render chart view for time allocation for today.
 * @returns view
 */
function dashboardchart()
{
	$theme = getThemeName ();
	$this->template->set_master_template ($theme.'/template2.php');
	$data['site_setting_date'] = $this->config->item('company_default_format');
	$data['site_setting'] = site_setting();
	$task_status_completed_id = $this->config->item('completed_id');
	$offdays = get_company_offdays();
	$data['none'] = taskByPriority('None',$task_status_completed_id,$offdays);
	$data['low'] = taskByPriority('Low',$task_status_completed_id,$offdays);
	$data['medium'] = taskByPriority('Medium',$task_status_completed_id,$offdays);
	$data['high'] = taskByPriority('High',$task_status_completed_id,$offdays);
	
	$data['timeallocationchart'] = $this->user_model->getTimeAllocationChart($task_status_completed_id,$offdays);
	$data['categories'] = $this->user_model->getcategoryforchart($task_status_completed_id,$offdays);
	
	$this->load->view($theme.'/layout/user/allocationfortoday', $data);
}
    /**
     * It will draw chart on team dashboard.Accprding to allocation time it will render chart on team dashboard.
     * @returns view
     */
	
	function teamdashcharttime()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		$data['team'] = get_users_under_managers();
	
		$data['MON_hours'] = 0;
		$data['TUE_hours'] = 0;
		$data['WED_hours'] = 0;
		$data['THU_hours'] = 0;
		$data['FRI_hours'] = 0;
		$data['SAT_hours'] = 0;
		$data['SUN_hours'] = 0;
		
		if($data['team']!="0")
		{
			foreach ($data['team'] as $t)
			{
				$data['user_time'] = get_calender_settings_by_user($t->user_id);
				if($data['user_time']!='0'){
					$data['MON_hours'] = $data['MON_hours'] + $data['user_time']->MON_hours;
					$data['TUE_hours'] = $data['TUE_hours'] + $data['user_time']->TUE_hours;
					$data['WED_hours'] = $data['WED_hours'] + $data['user_time']->WED_hours;
					$data['THU_hours'] = $data['THU_hours'] + $data['user_time']->THU_hours;
					$data['FRI_hours'] = $data['FRI_hours'] + $data['user_time']->FRI_hours;
					$data['SAT_hours'] = $data['SAT_hours'] + $data['user_time']->SAT_hours;
					$data['SUN_hours'] = $data['SUN_hours'] + $data['user_time']->SUN_hours;
				}
			}
		}
		$task_status_completed_id = $this->config->item('completed_id');
		$offdays = get_company_offdays();
		$data['allocated'] = getmyteamtask($task_status_completed_id,$offdays);
		$nonallocatedtime = '0';
		
		if(date("l")=='Monday'){ $nonallocatedtime = $data['MON_hours'] - $data['allocated'];}
		if(date("l")=='Tuesday'){ $nonallocatedtime = $data['TUE_hours'] - $data['allocated'];}
		if(date("l")=='Wednesday'){ $nonallocatedtime = $data['WED_hours'] - $data['allocated'];}
		if(date("l")=='Thusday'){ $nonallocatedtime = $data['THU_hours'] - $data['allocated'];}
		if(date("l")=='Friday'){ $nonallocatedtime = $data['FRI_hours'] - $data['allocated'];}
		if(date("l")=='Saturday'){ $nonallocatedtime = $data['SAT_hours'] - $data['allocated'];}
		if(date("l")=='Sunday'){ $nonallocatedtime = $data['SUN_hours'] - $data['allocated'];}

		$data['nonallocated'] = $nonallocatedtime;
	  	
		
		$data['category'] = get_company_category($this->session->userdata('company_id'),'Active','0');
	  	$data['taskByCat'] = get_task_By_category($task_status_completed_id,$offdays);
	  
	  	$data['taskByCat_tot'] = get_task_By_category_count();
		
	
	$this->load->view($theme.'/layout/user/teamallocationfortime', $data);
}
/**
 * This will draw chart on team dashboard.It will render chart by category for today.
 * @returns view
 */
function teamdashchartcategory()
{
	$theme = getThemeName ();
	$this->template->set_master_template ($theme.'/template2.php');
	$data['site_setting_date'] = $this->config->item('company_default_format');
	$data['site_setting'] = site_setting();
	$task_status_completed_id = $this->config->item('completed_id');
	$offdays = get_company_offdays();
	 $data['category'] = get_company_category($this->session->userdata('company_id'),'Active','0');
	 $data['taskByCat'] = get_task_By_category($task_status_completed_id,$offdays);
	  
	 $data['taskByCat_tot'] = get_task_By_category_count();
	
	$this->load->view($theme.'/layout/user/teamallocationforcategory', $data);
}
/**
 * This function will render division setting on admin setting page.It will use get_company_division() method for fetch division details from db.
 * @returns view
 */
function divisionsSettings(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$data['tags_division'] = get_user_division($user_id);
		$company_division = get_company_division($this->session->userdata('company_id'),'Active');
		$company_division_quots = '';
		if($company_division){
			foreach($company_division as $div){
				$company_division_quots .= "'".$div->devision_title."',";
			}
			$company_division_quots = substr($company_division_quots, 0,-1);
		}

		$data['company_division'] = $company_division_quots;
		$data['tags_division'] = get_user_division($user_id);
		$data['tags_department'] = get_user_department($user_id);
		$data['company_department'] = addQuotes(get_company_department_list($this->session->userdata('company_id'),$data['tags_division']));
		echo $this->load->view($theme.'/layout/user/ajaxDivisionSettings',$data,TRUE); die;
	}
	/**
         * This function will render department setting on admin setting page.It will use get_company_department_list() method for fetch department details from db.
         * @returns view
         */
	function departmentsSettings(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$division_id = isset($_POST['division_id'])?$_POST['division_id']:'';
		$div_id = array();
			
		if($division_id){
			$tags_division = explode(',', $division_id);
			$array_text = array();
			if(isset($tags_division) && $tags_division!=''){
				foreach($tags_division as $row){
					$id = $this->user_model->get_division_id_by_name($row);
					$div_id[] = $id;
				}
			}
		}
		
		$data['tags_department'] = get_user_department($user_id);
		
		if($div_id){
			$company_department = $this->user_model->get_company_department_list($div_id);
			//$company_department = get_company_department($this->session->userdata('company_id'),'Active');
			$company_department_quots = '';
			if($company_department){
				foreach($company_department as $dep){
					$company_department_quots .= "'".$dep->department_title."',";
	
				}
				$company_department_quots = substr($company_department_quots, 0,-1);
			}
			$data['company_department'] = $company_department_quots;
		} else {
			$data['company_department'] = '';
		}
		$data['company_division'] = addQuotes(get_company_division_list($this->session->userdata('company_id')));
		$data['tags_division'] = get_user_division($user_id);
		$data['tags_department'] = get_user_department($user_id);
		
	  	echo $this->load->view($theme.'/layout/user/ajaxDepartmentSettings',$data,TRUE); die;
	}
        /**
         * This function set company division on session.
         * @returns void
         */
	function setDivisionSession(){
		$this->session->set_userdata('companyDivision','1');
	}
	/**
         * When user will update dashboard tiles at the same time this function will update tiles details in db.
         * @returns void
         */
	function updateTiles()
	{
		$post_ids = $_POST['ids'];
		$tiles_arr = implode(',', $post_ids);
		$tiles_val = str_replace('sortableItem_','',$tiles_arr);
		
		$data = array(
			'tiles_order' => $tiles_val,
		);
		
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->update('users',$data);
		
	}
	/**
         * When admin update team dashboard tiles at the same time this function will update tiles details in db.
         * @returns void
         */
	function updateTiles_teamDashboard()
	{
		$post_ids = $_POST['ids'];
		$tiles_arr = implode(',', $post_ids);
		$tiles_val = str_replace('sortableItem_','',$tiles_arr);
		
		$data = array(
			'team_tiles_order' => $tiles_val,
		);
		
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->update('users',$data);
	}
        
        function set_approver(){
            $approver_id = $_POST['approver_id'];
            $user_id = $_POST['user_id'];
            $this->db->set('timesheet_approver_id',$approver_id);
            $this->db->where('user_id',  $user_id);
            $this->db->update('users');
            echo "done"; die();
        }
        
        function get_approver_list(){
                $user_id = $_POST['user_id'];
                $result = array();
		$this->db->select('um.manager_id,u.first_name,u.last_name');
		$this->db->from('user_managers um');
		$this->db->join('users u','u.user_id = um.manager_id');
		$this->db->where('um.user_id',$user_id);
		$this->db->where('u.user_status','Active');
		$this->db->where('u.is_deleted','0');
		$query = $this->db->get();
                if($query->num_rows()>0){
                    $result['managers'] =  $query->result();
                }else{
                    $result['managers'] = 0;
                }
                $result['approver_details'] = get_user_info($user_id);
                echo json_encode($result); die();
        }
        function outlook_synchronization()
        {
            $data = $_GET;
            $auth_code = $data["code"];
            $scope='Calendars.Read Calendars.Read.Shared Calendars.ReadWrite Calendars.ReadWrite.Shared Contacts.Read Contacts.Read.Shared Contacts.ReadWrite Contacts.ReadWrite.Shared Mail.Read Mail.Read.Shared Mail.ReadWrite Mail.ReadWrite.Shared Mail.Send Mail.Send.Shared MailboxSettings.Read MailboxSettings.ReadWrite People.Read Tasks.Read Tasks.Read.Shared Tasks.ReadWrite Tasks.ReadWrite.Shared';
            $fields=array(
            'grant_type'=>'authorization_code',
            'code'=>urlencode($auth_code),
            'client_id'=>urlencode(OUTLOOK_CLIENT_ID),
            'client_secret'=>urlencode(OUTLOOK_SECRET_KEY),
            'scopes' =>urlencode($scope),
            'redirect_uri'=>  urlencode(OUTLOOK_REDIRECT_URL)
            );

            $post = '';
            foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
            $post = rtrim($post,'&');
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,'https://login.microsoftonline.com/common/oauth2/v2.0/token');
            curl_setopt($curl,CURLOPT_POST,5);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
             $result = curl_exec($curl); 
            curl_close($curl);

             $response =  json_decode($result);
            if(isset($response->access_token)){
                $_SESSION['outlook_access_token'] = $response->access_token;
                $_SESSION['refresh_token'] = $response->refresh_token;
                $_SESSION['id_token'] = $response->id_token;
                $where1=array('user_id'=>  get_authenticateUserID());
                $this->db->where($where1);
                $query = $this->db->get('outlook_detail');
                $row=$query->row_array();
                if($row)
                {
                    $update=array('refresh_token'=>$response->refresh_token);
                    $this->db->where($where1);
                    $this->db->update('outlook_detail',$update);
                }
                else
                {
                    $data=array('user_id'=>  get_authenticateUserID(),
                    'refresh_token'=>$response->refresh_token);
                    $this->db->insert('outlook_detail',$data);
                }
                
                $update=array('outlook_synchronization_on'=>1);
                $this->db->where($where1);
                $this->db->update('users',$update);
                $this->session->set_userdata('outlook_synchronization_on',1);
                redirect('user/outlook_user_detail');
            }
        }
        function outlook_user_detail()
        {
            $accesstoken = $_SESSION['outlook_access_token'];
            $api_url='https://graph.microsoft.com/v1.0/me';
            $send=array('User-Agent:Schedullo/1.0',
            'client-request-id:'.OUTLOOK_CLIENT_ID,
            'return-client-request-id:true',
            'authorization:Bearer '.$accesstoken
            );
            $curl = curl_init($api_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$send);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($curl_response);
            $_SESSION['outlook_user_id']=$response->id;
            $_SESSION['outlook_email_id']=$response->userPrincipalName;
            $data=array('outlook_user_id'=>$response->id);
            $this->db->where('user_id',  get_authenticateUserID());
            $this->db->update('outlook_detail',$data);
            $this->outlook_subscription();
            redirect('user/outlook_events');
        }
        function outlook_events()
        {
            $accesstoken = $_SESSION['outlook_access_token'];
            $api_url='https://graph.microsoft.com/v1.0/me/events';
            $send=array('User-Agent:Schedullo/1.0',
            'client-request-id:'.OUTLOOK_CLIENT_ID,
            'return-client-request-id:true',
            'authorization:Bearer '.$accesstoken
            );
            $curl = curl_init($api_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$send);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($curl_response,'true');
            
            if(isset($response['value'])){
                common_method_for_task($response['value'],'outlook');
            }
            redirect('home');
        }
        function outlook_subscription()
        {
            $accesstoken = $_SESSION['outlook_access_token'];
            $send=array('User-Agent:Schedullo/1.0',
            'cache-control: no-cache',
            'client-request-id:'.OUTLOOK_CLIENT_ID,
            'return-client-request-id:true',
            'Authorization:Bearer '.$accesstoken,
            'Content-Type: application/json'
            );
            $date = Date("Y-m-d");
            $date = Date("Y-m-d\TH:i:s.000\Z",strtotime("+3 days", strtotime($date)));
            
            
            $curl = curl_init();
            $postfields=array(
               "changeType"=> "created,updated,deleted",
               "notificationUrl"=> base_url()."cron/push_notification",
               "resource"=> "me/events",
               "expirationDateTime"=>$date,
               "clientState"=> "outlook event subscription"
            );

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://graph.microsoft.com/beta/subscriptions",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => json_encode($postfields),
              CURLOPT_HTTPHEADER => $send,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

//            if ($err) {
//              echo "cURL Error #:" . $err;
//            } else {
//              echo $response;
//            }
            
            $resp_array=json_decode($response,true);
            $where = array('user_id'=> get_authenticateUserID());
            $update=array(
                'subscription_id'=>$resp_array['id'],
                'subscription_expiration'=>$resp_array['expirationDateTime']
                );
            $this->db->where($where);
            $this->db->update('outlook_detail',$update);
        }
        function outlook_synchronization_off()
        {
            $this->db->select('*');
            $this->db->from('outlook_detail');
            $this->db->where('user_id',  get_authenticateUserID());
            $query = $this->db->get();
            $detail = $query->row_array();
            $token = outlook_refresh_token(get_authenticateUserID());
            $accesstoken = $token['access_token'];
                $send=array('User-Agent:Schedullo/1.0',
                'cache-control: no-cache',
                'client-request-id:'.OUTLOOK_CLIENT_ID,
                'return-client-request-id:true',
                'Authorization:Bearer '.$accesstoken,
                'Content-Type: application/json'
                );
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://graph.microsoft.com/beta/subscriptions/".$detail['subscription_id'],
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "DELETE",
                  CURLOPT_HTTPHEADER => $send,
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);
                $update=array('outlook_synchronization_on'=>0);
                $this->db->where('user_id',  get_authenticateUserID());
                $this->db->update('users',$update);
                $this->db->where('user_id',  get_authenticateUserID());
                $this->db->delete('outlook_detail');
                $this->session->set_userdata('outlook_synchronization_on','0');
        }
        
        
        function capacity_dashboard(){
                /**
                 * check user authentication
                 */
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
                /**
                 * set template and theme
                 */
		$this->template->set_master_template ($theme.'/template2.php');
		
		/**
                 * this array store all info of user
                 */
		$data = array();
		
		$data['theme'] = $theme;
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['com_off_days'] = $offdays = get_company_offdays();
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
                
                if($this->session->userdata('is_administrator')=='1'){
                    $data['users'] = get_user_list();
                } elseif($this->session->userdata('is_manager')=='1' && $this->session->userdata('is_administrator') == '0'){ 
                    $report_users = get_users_under_managers(); 
                    $user_info = get_user_inform();
                    if($report_users != 0){
                        $data['users'] = array_merge($report_users,$user_info);
                    }else{
                        $data['users'] = $user_info;
                    }
                }else{
                    $data['users'] = get_user_inform();
                }
                $data['filter_list'] = get_user_inform();
                
                
                $default_week_day = get_company_default_week_day();
		//pr($data['users']); die();
               
		$data['start_date'] = date("Y-m-d",strtotime($default_week_day.'this week')); 
		$data['end_date'] = date('Y-m-d',strtotime("+13 day",  strtotime($data['start_date'])));
                $date1=date_create($data['start_date']);
                $date2=date_create($data['end_date']);
                $diff=date_diff($date1,$date2);
                $days = $diff->days;
                $date_arr = array();
                for($i=0;$i<=$days;$i++){
                    $to = date("Y-m-d",strtotime("+".$i." day", strtotime($data['start_date'])));   //Returns the date of sunday in week
                    array_push($date_arr,$to);
                }
                $data['date_range'] = $date_arr;
		/**
                 * this render dashboard page.
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/user/capacity_dashboard',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
        }
        
        
        function ajax_capacity_dashboard(){
            
                $theme = getThemeName ();
                /**
                 * set template and theme
                 */
		$this->template->set_master_template ($theme.'/template2.php');
		
		/**
                 * this array store all info of user
                 */
		
		$data = array();
		$date_data = explode("#",$_POST["mydate"]);
		$data['error'] = '';
                
		$start_date = $date_data[0];
		$action = $date_data[1];
		$graph_type = isset($_POST['graph_type'])?$_POST['graph_type']:'';
                $user_filter = isset($_POST['user_filter'])?$_POST['user_filter']:'me';
                $select_user = isset($_POST['select_user'])?$_POST['select_user']:$this->session->userdata('user_id');
               
		$data['theme'] = $theme;
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');
                $data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
                $data['user_filter'] = $user_filter;
                $data['select_user'] = $select_user;

		date_default_timezone_set($this->session->userdata("User_timezone"));

		if($action == "next"){
			$end_date = date('Y-m-d',strtotime("+7 day ", strtotime(str_replace(array("/"," ",","), "-", $start_date))));
                        $starting_date = date('Y-m-d',strtotime("-6 day ", strtotime(str_replace(array("/"," ",","), "-", $start_date))));
                }else if($action == "prev"){
			$starting_date = date('Y-m-d',strtotime("-7 day ", strtotime(str_replace(array("/"," ",","), "-", $start_date))));
                        $end_date = date('Y-m-d',strtotime("+6 day ", strtotime(str_replace(array("/"," ",","), "-", $start_date))));
                }else if($action == "current"){
                        $starting_date = date('Y-m-d',strtotime(str_replace(array("/"," ",","), "-", $start_date)));
                        $end_date = date('Y-m-d',strtotime("+13 day ", strtotime(str_replace(array("/"," ",","), "-", $start_date))));
                }else{
                        $starting_date = $start_date;
                        $end_date = $action;
                }
               
		
		$data['com_off_days'] = $offdays = get_company_offdays();
		
                $data['graph_type'] = $graph_type;
		if($this->session->userdata('is_administrator')=='1'){
                    $data['users'] = get_user_list();
                } elseif($this->session->userdata('is_manager')=='1' && $this->session->userdata('is_administrator') == '0'){ 
                    $report_users = get_users_under_managers(); 
                    $user_info = get_user_inform();
                    if($report_users != 0){
                        $data['users'] = array_merge($report_users,$user_info);
                    }else{
                        $data['users'] = $user_info;
                    }
                }else{
                    $data['users'] = get_user_inform();
                }
                $filter = array();
                if($user_filter == 'me')
                {
                    $filter = get_user_inform();
                }
                else if($user_filter == 'team' && $select_user == 'all')
                {
                    $filter = get_user_list();
                }
                else if($user_filter == 'team' && $select_user == 'reported_user')
                {
                    $report_users = get_users_under_managers(); 
                    $user_info = get_user_inform();
                    if($report_users != 0){
                        $filter = array_merge($report_users,$user_info);
                    }else{
                        $filter = $user_info;
                    }
                }
                else
                {
                    $filter[0] = get_user_info($select_user);
                }
                $data['filter_list'] = $filter;
                
		$data['start_date'] = $starting_date; 
		$data['end_date'] = $end_date;
                $date1=date_create($data['start_date']);
                $date2=date_create($data['end_date']);
                $diff=date_diff($date1,$date2);
                $days = $diff->days;
                $date_arr = array();
                for($i=0;$i<=$days;$i++){
                    $to = date("Y-m-d",strtotime("+".$i." day", strtotime($data['start_date'])));   //Returns the date of sunday in week
                    array_push($date_arr,$to);
                }
                $data['date_range'] = $date_arr;
            echo $this->load->view($theme.'/layout/user/ajax_capacity_dashboard', $data,TRUE);  
            
        }
        
        function update_user_info(){
            if($_POST){
                $userInfo = $_POST['info'];
                $unserializedData = array();
                parse_str($userInfo,$unserializedData);
                $update_info = array(
                    "first_name"=>$unserializedData['first_name'],
                    "last_name"=>$unserializedData['last_name'],
                    "email"=>$unserializedData['email'],
                    "contact_no"=>$unserializedData['mobile'],
                    "user_time_zone"=>$unserializedData['user_timezone'],
                    "user_default_page"=>$unserializedData['user_default_page']
                );
                
                $this->db->where('user_id',  get_authenticateUserID());
                $this->db->where('company_id',  $this->session->userdata('company_id'));
                $this->db->where('user_status','active');
                $this->db->update('users',$update_info);
                
                if($this->session->userdata('email') != $unserializedData['email']){
                    $code = randomCode();
                    $user_id = get_authenticateUserID();
                    $data1 = array('verify_email'=>0,'user_status'=>'Inactive','email_verification_code'=>$code);
                    $this->db->where('user_id',$user_id);
                    $this->db->update('users',$data1);
                    
                    $email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='verify email'");
                    $email_temp=$email_template->row();	
                    $email_address_from=$email_temp->from_address;
                    $email_address_reply=$email_temp->reply_address;
					
                    $email_subject=$email_temp->subject;				
                    $email_message=$email_temp->message;
					
                    $data_pass = base64_encode($user_id."1@1".$code);
		
                    $activation_link = "<a href='".base_url()."home/activation/".$data_pass."' target='_blank'>Activation link</a>";
		
                    $user_name = $unserializedData['first_name'].' '.$unserializedData['last_name'];
                    
                    $email_to = $unserializedData['email'];
                    
                    $email_message=str_replace('{break}','<br/>',$email_message);
                    $email_message=str_replace('{user_name}',$user_name,$email_message);
                    $email_message=str_replace('{activation_link}',$activation_link,$email_message);		
                    
                    $str=$email_message;
                    $sandgrid_id=$email_temp->sandgrid_id;
                    $sendgriddata = array('subject'=>'verify email',
                                          'data'=>array('activation_link'=>$activation_link));
                    if($sandgrid_id){
                        mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,'',$email_subject,$sandgrid_id,$sendgriddata);
                    }else{
                        email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                    }
                    $this->session->set_userdata("email",$unserializedData['email']);
                }
                
                $this->session->set_userdata("username",$unserializedData['first_name']." ".$unserializedData['last_name']);
                
                echo "done"; die();
            }
            
        }
        
        
        /**
         * gmail sync
         */
        function gmail_sync(){
            if($_POST){
                $status = $_POST['status'];
                
                $this->db->set('gmail_sync',$status);
                $this->db->where('user_id',  get_authenticateUserID());
                $this->db->update('users');
                $this->session->unset_userdata('gmail_sync');
                $this->session->set_userdata('gmail_sync',$status);
                if($status == 0){
                    $this->db->select('*');
                    $this->db->from('gmail_integration_details');
                    $this->db->where('user_id',  get_authenticateUserID());
                    $query = $this->db->get();
                    $res = $query->row_array();
                    $refresh_token = $res['gmail_refresh_token'];
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => GMAIL_TOKEN_URL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "grant_type=refresh_token&client_id=".GMAIL_CLIENT."&client_secret=".GMAIL_SECRET."&refresh_token=".$refresh_token,
                        CURLOPT_HTTPHEADER => array(
                          "cache-control: no-cache",
                          "content-type: application/x-www-form-urlencoded",
                        ),
                      ));

                      $response1 = curl_exec($curl);
                      $array_response = json_decode($response1,true);
                      //pr($array_response); 
                    curl_close($curl);
                    
                    
                    /**
                     * stop push notification after gmail integration off.
                     */
                    $curl = curl_init();
                    $post_data = array(
                        "id"=> $res['notification_id'],
                        "resourceId"=> $res['resource_id']
                    );
                    $json_data = json_encode($post_data);
                     curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://www.googleapis.com/calendar/v3/channels/stop",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => $json_data,
                      CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer ".$array_response['access_token'],
                        "cache-control: no-cache",
                        "content-type: application/json",
                      ),
                    ));

                    $response2 = curl_exec($curl);
                    
                    $this->session->unset_userdata('gmail_access_token');
                    $this->session->unset_userdata('gmail_refresh_token');
                    
                    
                    $this->db->where('user_id',  get_authenticateUserID());
                    $this->db->delete('gmail_integration_details');
                    
                    
                }
                echo "done"; die();
            }
        }
        
        function gmail_access(){
            $data = $_GET;
            $code = $data['code'];
            
            $curl = curl_init();
            // get access token using authorize code
            curl_setopt_array($curl, array(
              CURLOPT_URL => GMAIL_TOKEN_URL,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "code=".$code."&grant_type=authorization_code&client_id=".GMAIL_CLIENT."&client_secret=".GMAIL_SECRET."&redirect_uri=".GMAIL_REDIRECT_URL,
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
              ),
            ));

            $response = curl_exec($curl);
            
            $array_response = json_decode($response,TRUE);
            //pr($array_response);
            $this->session->set_userdata('gmail_access_token',$array_response['access_token']);
            $this->session->set_userdata('gmail_refresh_token',$array_response['refresh_token']);
            
            $this->add_event_in_schedullo();
        }
        
        function add_event_in_schedullo(){
            
                $curl = curl_init();
                /**
                 * get user calendar list
                 */
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://www.googleapis.com/calendar/v3/users/me/calendarList",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer ".$this->session->userdata('gmail_access_token'),
                    "cache-control: no-cache"
                   ),
                ));

                $response = curl_exec($curl);
                $event_response = json_decode($response,TRUE);
               
                $calendar_id = $event_response['items'][0]['id'];
                
                /**
                 * insert gmail info in db
                 */
                
                $data = array(
                    'user_id'=>  get_authenticateUserID(),
                    'gmail_calendar_id'=>$calendar_id,
                    'created_date'=>date('Y-m-d H:i:s'),
                    'gmail_refresh_token'=>  $this->session->userdata('gmail_refresh_token')
                );
                $this->db->insert('gmail_integration_details',$data);
                
                /**
                 * get all user events list
                 */
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/".$calendar_id."/events",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer ".$this->session->userdata('gmail_access_token'),
                    "cache-control: no-cache"
                    ),
                ));

                $events = curl_exec($curl);
                $array_events = json_decode($events,TRUE);
                
                if(isset($array_events['items'])){
                   common_method_for_task($array_events['items'],'gmail');
                }
                /**
                 * update user info in db
                 */
                
                $this->db->set('gmail_nextSyncToken',$array_events['nextSyncToken']);
                $this->db->where('user_id',  get_authenticateUserID());
                $this->db->update('gmail_integration_details');
                
                
                
                /**
                 * gmail push Notification registration
                 */
                $new_date =  strtotime(date("Y-m-d H:i:s", strtotime("+5 day")))*1000; 
                $code = randomCode();

                $post_data = array(
                        "id"=> $code,
                        "type"=> "web_hook",
                        "address"=> PUSH_NOTIFICATION_URL,
                        "expiration"=>$new_date
                );
                $json_post_data = json_encode($post_data);
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/".$calendar_id."/events/watch",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS=>$json_post_data,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer ".$this->session->userdata('gmail_access_token'),
                    "cache-control: no-cache",
                    "content-type: application/json"
                    ),
                ));

                $reg_info = curl_exec($curl);
                $array_reg_info = json_decode($reg_info,TRUE);

                $mil = $array_reg_info['expiration'];
                $seconds = $mil / 1000;
                $notification_expire_time =  date("Y-m-d H:i:s", $seconds); 
                $notification_id = $array_reg_info['id'];
                
                
                /**
                 * update push notification details in db
                 */
                $this->db->set('notification_expire_time',$notification_expire_time);
                $this->db->set('notification_id',$notification_id);
                $this->db->set('resource_id',$array_reg_info['resourceId']);
                $this->db->where('user_id',  get_authenticateUserID());
                $this->db->update('gmail_integration_details');
                
                
                curl_close($curl);
                
                redirect('user/my_settings');
        }
        
        
        function change_swimlane_status(){
            if($_POST){
                $swimlane_id = $this->input->post('swimlane_id');
                $status = $this->input->post('status');
                
                $this->db->set('swimlane_status',$status);
                $this->db->where('swimlanes_id',$swimlane_id);
                $this->db->where('user_id',$this->session->userdata('user_id'));
                $this->db->update('swimlanes');
                $swimlane = count_total_swimlanes();
                echo $swimlane; die();
            }
        }
        
        function set_default_swimlane(){
            $new_default_swimlane = $this->input->post('new_default_swimlane');
            $old_default_swimlane = $this->input->post('old_default_swimlane');
            
            
            $swimlane_list = $this->user_model->get_default_swimlanes_info($this->session->userdata('user_id'));
            
            $this->db->set('is_default','0');
            $this->db->where('swimlanes_id',$old_default_swimlane);
            $this->db->where('is_deleted','0');
            $this->db->update('swimlanes');
            
            $this->db->set('is_default','1');
            $this->db->where('swimlanes_id',$new_default_swimlane);
            $this->db->where('is_deleted','0');
            $this->db->update('swimlanes');
            
            
            $return_data['swimlane_status'] = $swimlane_list->swimlane_status;
            $return_data['swimlanes_id'] = $swimlane_list->swimlanes_id;
            $return_data['swimlane_name'] = $swimlane_list->swimlanes_name;
            echo json_encode($return_data); die();
        }
        function upload_background_image()
	{
		$msg = '';
		$background_image='';
		$s3_background_image = '';
		if($_FILES['background_image']['name']!='')
        {
     		$this->load->library('upload');
         	$rand=rand(0,100000); 
			  
	         $_FILES['userfile']['name']     =   $_FILES['background_image']['name'];
	         $_FILES['userfile']['type']     =   $_FILES['background_image']['type'];
	         $_FILES['userfile']['tmp_name'] =   $_FILES['background_image']['tmp_name'];
	         $_FILES['userfile']['error']    =   $_FILES['background_image']['error'];
	         $_FILES['userfile']['size']     =   $_FILES['background_image']['size'];
	   
			$config['file_name'] = 'background'.$rand;
			
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
				
				
		   if ($_FILES["background_image"]["type"]!= "image/png" and $_FILES["background_image"]["type"] != "image/x-png") {		  
			   	$gd_var='gd2';			
			}
			
					
		   if ($_FILES["background_image"]["type"] != "image/gif") {		   
		    	$gd_var='gd2';
		   }	   
		   
		   if ($_FILES["background_image"]["type"] != "image/jpeg" and $_FILES["background_image"]["type"] != "image/pjpeg" ) {		   
		    	$gd_var='gd2';
		   }
		   
		   $this->image_lib->clear();
			
			 $this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/user_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/user/'.$picture['file_name'],
				'maintain_ratio' => TRUE,
				'quality' => '100%'
			 ));
			
			
			if(!$this->image_lib->resize())
			{
				$error = $this->image_lib->display_errors();
			}
			
			$new_image = $this->image_lib->new_image;
			 
		
			$bucket = $this->config->item('bucket_name');
		
          	$name = $_FILES['background_image']['name'];
			$size = $_FILES['background_image']['size'];
			$tmp = $_FILES['background_image']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			if(in_array($ext,$valid_formats)){
				
				
				$s3_profile_image = 'background'.$rand.'.'.$ext;
			    $actual_image_name = "upload/user_orig/".$s3_profile_image;
				$new_actual_image_name = "upload/user/".$s3_profile_image;
	 			
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, CI_S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, CI_S3::ACL_PUBLIC_READ)){
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
					if($this->input->post('hdn_background_image')!='')
					{
						$delete_image_name = "upload/user_orig/".$this->input->post('hdn_background_image');
						$delete_image_name1 = 'upload/user/'.$this->input->post('hdn_background_image');
						
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
			if($this->input->post('hdn_background_image')!='')
			{
				$s3_profile_image=$this->input->post('hdn_background_image');
			}
		}
		
		$data = array(
                        'user_background_type' => 'Image',
			'user_background_name' => $s3_profile_image
		);
		
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->update('users',$data);
                $this->session->set_userdata('user_background_type','Image');
                $this->session->set_userdata('user_background_name',$s3_profile_image);
		
		$pass = $this->config->item('s3_display_url').'upload/user/'.$s3_profile_image;
		echo json_encode($pass);die;
	}
        function set_background_color()
        {
            if($_POST['color'] && $_POST['color']!='')
            {
                $color = $_POST['color'];
            
                $data = array(
                        'user_background_type' => 'Color',
			'user_background_name' => $color
		);
		
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->update('users',$data);
                $this->session->set_userdata('user_background_type','Color');
                $this->session->set_userdata('user_background_name',$color);
            }
        }
        function set_default_background()
        {
            
            $data = array(
                    'user_background_type' => 'DefaultImage',
                    'user_background_name' => ''
            );

            $this->db->where('user_id',$this->session->userdata('user_id'));
            $this->db->update('users',$data);
            $this->session->set_userdata('user_background_type','DefaultImage');
            $this->session->set_userdata('user_background_name','');
        }
}
?>
