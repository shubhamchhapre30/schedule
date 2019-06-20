<?php
/**
 * In this class have defined report class related functiones, this class is used to access data from database.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Reports_model extends CI_Model 
{
     /**
        * It default constuctor which is called when Reports_model object is initialzied.It load base class methods & variables.
        * @returns void
        */
	
	function Reports_model()
    {
            /**
             * call base class constructor
             */
        parent::__construct();	
    }
	
	/*
	 * Function : Loginperuser_report
	 * Author : Spaculus
	 * Return : List of user login data.
	 */
	/**
         * This function is used for access login user data from db.It access current loggedin user data from db.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function Loginperuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date){
		
	
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		
		$subquery = "select user_login_date,user_login_history_id, user_id from user_login_history order by user_login_history_id desc";

		$this->db->select('u.user_id,u.first_name,u.last_name,u.is_manager,ulh.user_login_date');
		$this->db->from('users u');
		$this->db->join("($subquery)  ulh","ulh.user_id = u.user_id");
		if($from_date){
			$this->db->where('DATE(ulh.user_login_date) >=',$from_date);
		}
		if($to_date){
			$this->db->where('DATE(ulh.user_login_date) <=',$to_date);
		}
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('u.user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(u.user_id = "'.get_authenticateUserID().'" or u.user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->where_in('ud.devision_id',$division_id);
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
		}
		if($department_id){
			$this->db->where_in('udp.dept_id',$department_id);
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
		}
		$this->db->where('u.company_id',$this->session->userdata('company_id'));
		$this->db->where('u.user_status','Active');
		$this->db->where('u.is_deleted','0');
		$this->db->group_by('u.user_id');
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Loginhistorybyuser_report
	 * Author : Spaculus
	 * Return : List of user login history data.
	 */
	/**
         * This function is used for access login history of user.it return list of login user.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
        
	function Loginhistorybyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date){
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
                /**
                 * check user id
                 */
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		/**
                 * to fetch data
                 */
		$this->db->select("u.user_id,u.first_name,u.last_name,ulh.user_login_date");
		$this->db->from("users u");
		$this->db->join("user_login_history  ulh","ulh.user_id = u.user_id");
		if($from_date){
			$this->db->where("DATE(ulh.user_login_date) >=",$from_date);
		}
		if($to_date){
			$this->db->where("DATE(ulh.user_login_date) <=",$to_date);
		}
		if($user_id !='' && $user_id != 'all'){
			$this->db->where("u.user_id",$user_id);
		} else {
			if($ids){
				$this->db->where('(u.user_id = "'.get_authenticateUserID().'" or u.user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->where_in('ud.devision_id',$division_id);
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
		}
		if($department_id){
			$this->db->where_in('udp.dept_id',$department_id);
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
		}
		$this->db->where("u.company_id",$this->session->userdata('company_id'));
		$this->db->where("u.user_status","Active");
		$this->db->where("u.is_deleted","0");
		$this->db->order_by("ulh.user_login_history_id","desc");
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Listofoverduetasks_report
	 * Author : Spaculus
	 * Return : List of overdue task
	 */
	/**
         * It returns list of overdue tasks of selected user in dropdown list.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function Listofoverduetasks_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id)
	{
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$today_date = date("Y-m-d");
			
		$task_completed_status_id = $this->config->item('completed_id');
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		/**
                 * fetch data 
                 */
              
		$this->db->select('t.task_time_estimate,t.task_time_spent,t.task_status_id,t.task_due_date as task_true_date,t.task_id,t.task_title,t.task_allocated_user_id,t.task_owner_id,t.task_priority,t.task_project_id,t.task_category_id,t.task_sub_category_id,t.task_due_date,t.task_scheduled_date,p.project_title,tc.category_name,tsc.category_name as sub_category_name,u.first_name,u.last_name,uo.first_name as owner_first_name,uo.last_name as owner_last_name,ts.task_status_name,(SELECT customer_name FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as customer_name,(SELECT external_id FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as external_id');
		$this->db->from('tasks t');
		$this->db->join('project p','p.project_id = t.task_project_id','left');
		$this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
		$this->db->join('task_category tsc','tsc.category_id = t.task_sub_category_id','left');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$this->db->join('users uo','uo.user_id = t.task_owner_id','left');
		$this->db->join('task_status ts','ts.task_status_id = t.task_status_id','left');
               // $this->db->join('customers c','c.customer_id = t.customer_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->where('t.task_division_id',$division_id);
		}
		if($department_id){
			$this->db->where('t.task_department_id',$department_id);
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
                if($customer_id){
                    $this->db->where('t.customer_id',$customer_id);
                    //$this->db->where('c.customer_company_id',$this->session->userdata('company_id'));   
                }
                //$this->db->where('c.customer_company_id',$this->session->userdata('company_id')); 
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.is_deleted','0');
		$this->db->where('t.task_status_id !=',$task_completed_status_id);
		if($to_date!='' && $from_date!=''){
			$this->db->where('(`t`.`task_due_date`<="'.$to_date.'" and `t`.`task_due_date`>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(`t`.`task_due_date`<="'.$to_date.'" and `t`.`task_due_date`<"'.$today_date.'")');
		} elseif($from_date){
			$this->db->where('(`t`.`task_due_date`>="'.$from_date.'" and `t`.`task_due_date`<"'.$today_date.'")');
		} else {
			$this->db->where('(`t`.`task_due_date`<"'.$today_date.'")');
		}
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.task_scheduled_date !=','0000-00-00');
		$this->db->where('t.task_due_date !=','0000-00-00');
		$this->db->order_by('task_due_date','desc');
		//$this->db->limit(200);
		$query = $this->db->get();
		//echo $this->db->last_query(); die();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
		
	}
	
	/*
	 * Function : Timeallocatedbyproject_report
	 * Author : Spaculus
	 * Return : List of time llocation sum by project
	 */
	/**
         * This function returns list of time allocation sum by project.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function Timeallocatedbyproject_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id){
		
		
		$task_completed_status_id = $this->config->item('completed_id');
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		/**
                 * fetch sum of time allocation
                 */
		$this->db->select('sum(t.task_time_estimate) as allocationtime,sum(t.task_time_spent) as actualtime,t.task_scheduled_date,p.project_title,p.project_start_date,p.project_end_date,ps.section_name,u.first_name,u.last_name,(SELECT customer_name FROM customers cs WHERE cs.customer_id = p.project_customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as customer_name,(SELECT external_id FROM customers cs WHERE cs.customer_id = p.project_customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as external_id');
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id');
		$this->db->join('project p','p.project_id = t.task_project_id','left');
		$this->db->join('project_section ps','ps.section_id = t.section_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('u.user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(u.user_id = "'.get_authenticateUserID().'" or u.user_id IN ('.$ids.'))');
			} 
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")');
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")');
		} else { }
                if($customer_id){
                        $this->db->where('p.project_customer_id',$customer_id);
                }
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.task_project_id !=','0');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.is_deleted','0');
		//$this->db->where('t.task_status_id !=',$task_completed_status_id);
		$this->db->group_by(array('t.task_project_id','t.task_allocated_user_id'));
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Tasksduethisweekbyuser_report
	 * Author : Spaculus
	 * Return : List of due task in this week
	 */
	/**
         * It returns list of due task in this week with user info.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function Tasksduethisweekbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id){
		
		$task_completed_status_id = $this->config->item('completed_id');
		$week_start_date = date("Y-m-d",strtotime("monday this week"));
		$week_end_date = date("Y-m-d",strtotime("sunday this week"));
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}

		$this->db->select('u.first_name,u.last_name,ts.task_status_name,t.task_due_date,t.task_id,t.task_time_estimate,t.task_time_spent,t.task_scheduled_date,t.task_title,t.task_priority,t.task_project_id,t.task_owner_id,t.task_id,t.task_allocated_user_id,t.task_category_id,t.task_sub_category_id,p.project_title,tc.category_name, tsc.category_name as sub_category_name,uts.color_id,uc.name,uo.first_name as owner_first_name,uo.last_name as owner_last_name,(SELECT customer_name FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as customer_name,(SELECT external_id FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as external_id');
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$this->db->join('project p','p.project_id = t.task_project_id','left');
		$this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
		$this->db->join('task_category tsc','tsc.category_id = t.task_sub_category_id','left');
		$this->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$this->db->join('user_colors uc','uc.user_color_id = uts.color_id','left');
		$this->db->join('users uo','uo.user_id = t.task_owner_id','left');
                $this->db->join('task_status ts','ts.task_status_id = t.task_status_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			} 
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_due_date!="0000-00-00" and t.task_due_date>="'.$from_date.'" and t.task_due_date<="'.$to_date.'")');
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$week_end_date.'" and t.task_due_date!="0000-00-00" and t.task_due_date>="'.$from_date.'" and t.task_due_date<="'.$week_end_date.'")');
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$week_start_date.'" and t.task_scheduled_date<="'.$to_date.'" and t.task_due_date!="0000-00-00" and t.task_due_date>="'.$week_start_date.'" and t.task_due_date<="'.$to_date.'")');
		} else {
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$week_start_date.'" and t.task_scheduled_date<="'.$week_end_date.'" and t.task_due_date!="0000-00-00" and t.task_due_date>="'.$week_start_date.'" and t.task_due_date<="'.$week_end_date.'")');
		}
                if($customer_id){
                        $this->db->where('t.customer_id',$customer_id);
                }
		
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.is_deleted','0');
		$this->db->where('t.task_status_id !=',$task_completed_status_id);
		//$this->db->limit(200);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Interruptionsbytypeandbyuser_report
	 * Author : Spaculus
	 * Return : List of users interruptions by type
	 */
	/**
         * This function returns list of users interruptions by type and by user.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function Interruptionsbytypeandbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id){
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
                /**
                 * fetch query from db
                 */

		$this->db->select('t.task_id,t.task_title,t.task_category_id,u.first_name,u.last_name,ttl.interruption,ttl.date_added,tc.category_name,(SELECT customer_name FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as customer_name,(SELECT external_id FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as external_id');
		$this->db->from('tasks t');
		$this->db->join('task_timer_logs ttl','t.task_id = ttl.task_id');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id');
		$this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			}
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")');
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")');
		} else { }
                if($customer_id){
                    $this->db->where('t.customer_id',$customer_id);
                }
		$this->db->where('ttl.is_manual','0');
		$this->db->where('(ttl.interruption != "" and interruption != "Task Completed")');
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.is_deleted','0');
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Timeallocationbycategory_report
	 * Author : Spaculus
	 * Return : List of task time allocation to user by category
	 */
	/**
         * This function is used for return list of time allocation to user by category.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function Timeallocationbycategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id){
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		
		$this->db->select('IF(t.task_scheduled_date!=0000-00-00, t.task_scheduled_date, t.task_due_date) as task_true_date,sum(t.task_time_estimate) as allocationtime,sum(t.task_time_spent) as spenttime,t.task_category_id,t.task_sub_category_id,u.user_id,u.first_name,u.last_name,t.task_due_date,t.task_scheduled_date,tc.category_name as category_name, tsc.category_name as sub_category_name', FALSE);
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id');
		$this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
		$this->db->join('task_category tsc','tsc.category_id = t.task_sub_category_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->where_in('ud.devision_id',$division_id);
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
		}
		if($department_id){
			$this->db->where_in('udp.dept_id',$department_id);
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.is_deleted !=','1');
		$this->db->where('t.task_category_id !=','0');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.task_scheduled_date != ', "0000-00-00");
		
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")');
			$this->db->group_by(array('task_true_date','t.task_category_id'));
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")');
			$this->db->group_by(array('task_true_date','t.task_category_id'));
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")');
			$this->db->group_by(array('task_true_date','t.task_category_id'));
		} else { }
		if($customer_id){
                        $this->db->where('t.customer_id',$customer_id);
                }
		$this->db->group_by('t.task_category_id');
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
		
	}
	
	/*
	 * Function : ActivitybyCategory_report
	 * Author : Spaculus
	 * Return : List of task by category
	 */
	/**
         * This function returns list of task by category.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function ActivitybyCategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date){
		
		$task_completed_status_id = $this->config->item('completed_id');
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		
		$this->db->select('t.task_scheduled_date as task_true_date,sum(t.task_time_estimate) as task_time_estimate,sum(t.task_time_spent) as task_time_spent,t.task_scheduled_date,t.task_due_date,t.task_category_id,t.task_project_id,u.user_id,u.first_name,u.last_name,tc.category_name',FALSE);
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
		$this->db->join('user_department udp','udp.user_id = u.user_id','left');
		$this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->where_in('ud.devision_id',$division_id);
		}
		if($department_id){
			$this->db->where_in('udp.dept_id',$department_id);
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")');
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")');
		} else { }
		
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.is_deleted','0');
		//$this->db->where('t.task_status_id !=',$task_completed_status_id);
		$this->db->group_by(array('t.task_allocated_user_id','t.task_category_id'));
		$this->db->order_by('t.task_time_estimate','desc');
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Dailytimeallocationbyuser_report
	 * Author : Spaculus
	 * Return : List of task time allocation by user
	 */
	function Dailytimeallocationbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date){
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		
		$this->db->select('t.task_id,t.task_scheduled_date as task_true_date,sum(t.task_time_estimate) as allocationtime,sum(t.task_time_spent) as spenttime,t.task_category_id,t.task_sub_category_id,u.user_id,u.first_name,u.last_name,t.task_due_date,t.task_scheduled_date,t.task_division_id,t.task_department_id', FALSE);
		
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id');
		
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
			$this->db->where_in('ud.devision_id',$division_id);
		}
		if($department_id){
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
			$this->db->where_in('udp.dept_id',$department_id);
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.is_deleted !=','1');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.task_scheduled_date !=', "0000-00-00");
		
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")');
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")');
		} else { }
		$this->db->group_by(array('t.task_scheduled_date','t.task_allocated_user_id'));
		
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
		
	}
	
	/*
	 * Function : DailyTimeallocationpercategoryandsubcategory_report
	 * Author : Spaculus
	 * Return : List of task time allocation by category and sub category
	 */
	function DailyTimeallocationpercategoryandsubcategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date){
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		
		$this->db->select('t.task_scheduled_date as task_true_date,sum(t.task_time_estimate) as allocationtime,sum(t.task_time_spent) as spenttime,t.task_category_id,t.task_sub_category_id,u.user_id,u.first_name,u.last_name,t.task_due_date,t.task_scheduled_date,tc.category_name,tsc.category_name as sub_category_name', FALSE);
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id');
		$this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
		$this->db->join('task_category tsc','tsc.category_id = t.task_sub_category_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->where_in('ud.devision_id',$division_id);
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
		}
		if($department_id){
			$this->db->where_in('udp.dept_id',$department_id);
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}

		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.task_category_id !=','0');
		$this->db->where('t.is_deleted !=','1');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.task_scheduled_date != ',"0000-00-00");
		
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")');
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")');
		} else { }
		$this->db->group_by(array('task_true_date','t.task_category_id'));
		
		//$this->db->limit(200);
		$query = $this->db->get();
		
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Actualtimebycategoryoveraperiodoftime_category
	 * Author : Spaculus
	 * Return : List of spent time by category by time
	 */
	function Actualtimebycategoryoveraperiodoftime_category($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date){
		
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
			
		$this->db->select('sum(t.task_time_spent) as actual_time,t.task_scheduled_date as task_true_date,t.task_scheduled_date,t.task_due_date,t.task_category_id,tc.category_name',FALSE);
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id');
		$this->db->join('task_category tc','t.task_category_id = tc.category_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			} 
		}
		if($division_id){
			$this->db->where_in('ud.devision_id',$division_id);
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
		}
		if($department_id){
			$this->db->where_in('udp.dept_id',$department_id);
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		$this->db->where('t.task_category_id !=','0');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.task_scheduled_date != ', "0000-00-00");
		if($from_date!='' && $to_date!=''){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")');
		} elseif($from_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")');
		} else { }
		
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.is_deleted','0');
		$this->db->group_by('t.task_category_id');
		$this->db->order_by('t.task_category_id','asc');
		//$this->db->limit(200);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Actualtimebycategoryoveraperiodoftime_report
	 * Author : Spaculus
	 * Return : List of spent time by category by time
	 */
	function Actualtimebycategoryoveraperiodoftime_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date){
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
		
		$query = 'select 
					task_true_date , 
					group_concat(task_category_id) as mycal , 
					group_concat(actual_time) as mytime from 
						(SELECT sum(t.task_time_spent) as actual_time,  t.task_scheduled_date as task_true_date, t.task_scheduled_date, t.task_due_date, t.task_category_id
							FROM (`tasks` t)
							JOIN `users` u ON `u`.`user_id` = `t`.`task_allocated_user_id`
							LEFT JOIN `user_devision` ud ON `ud`.`user_id` = `u`.`user_id`
							LEFT JOIN `user_department` udp ON `udp`.`user_id` = `u`.`user_id`
							WHERE `t`.`task_category_id` != "0"';
		if($user_id !='' && $user_id != 'all'){
			$query .= ' AND t.task_allocated_user_id = "'.$user_id.'"';
		} else {
			if($ids){
				$query .= 'AND (t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ("'.$ids.'"))';
			}
		}
		if($division_id){
			$query .= 'AND ud.devision_id IN ('.$division_id.')';
		}
		if($department_id){
			$query .= 'AND udp.dept_id IN ('.$department_id.')';
		}
		if($category_id){
			$query .= ' AND t.task_category_id = "'.$category_id.'"';
		}
		if($sub_category_id){
			$query .= ' AND t.task_sub_category_id = "'.$sub_category_id.'"';
		}
		if($project_id){
			$query .= ' AND t.task_project_id = "'.$project_id.'"';
		}
		$query .= 'AND `t`.`task_company_id` =  "'.$this->session->userdata('company_id').'"
							AND t.task_scheduled_date != "0000-00-00"';
		if($from_date!='' && $to_date!=''){
			$query .= 'AND (t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'" and t.task_scheduled_date<="'.$to_date.'")';
		} elseif($from_date){
			$query .= 'AND (t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date>="'.$from_date.'")';
		} elseif($to_date){
			$query .= 'AND (t.task_scheduled_date!="0000-00-00" and t.task_scheduled_date<="'.$to_date.'")';
		} else { }
		$query .= 'AND `t`.`is_personal` =  "0" AND `t`.`task_owner_id` != "0" AND `t`.`task_allocated_user_id` != "0" 
							AND `t`.`is_deleted` =  "0"
							GROUP BY `t`.`task_category_id`, `task_true_date`
							ORDER BY `task_true_date` asc
							LIMIT 200 ) as sj 
					group by task_true_date';
		$query = $this->db->query($query);
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : Listofcompletedtasks_report
	 * Author : Spaculus
	 * Return : List of completed tasks
	 */
	/**
         * This function returns list of completed task for report generation.
         * @param  $user_id
         * @param  $division_id
         * @param  $department_id
         * @param  $category_id
         * @param  $sub_category_id
         * @param  $project_id
         * @param  $from_date
         * @param  $to_date
         * @returns int
         */
	function Listofcompletedtasks_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id)
	{
		$task_completed_status_id = $this->config->item('completed_id');
		$today_date = date('Y-m-d');
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
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
               // $this->db->join('customers c','c.customer_id = t.customer_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
			$this->db->where('uts.user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'"  or t.task_allocated_user_id IN ('.$ids.'))');
                                 $this->db->where('uts.user_id IN ('.$ids.')');
			}
			//$this->db->where('uts.user_id',get_authenticateUserID()); 
		}
		if($division_id){
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
			$this->db->where_in('ud.devision_id',$division_id);
		}
		if($department_id){
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
			$this->db->where_in('udp.dept_id',$department_id);
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
                if($customer_id){
                        $this->db->where('t.customer_id',$customer_id);
                       // $this->db->where('c.customer_company_id',$this->session->userdata('company_id'));
                }
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.is_deleted','0');
		//$this->db->where('t.task_status_id!=',$task_completed_status_id);
		if($to_date!='' && $from_date!=''){
			$this->db->where('(`t`.`task_scheduled_date`<="'.$to_date.'" and `t`.`task_scheduled_date`>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(`t`.`task_scheduled_date`<="'.$to_date.'" and `t`.`task_scheduled_date`<"'.$today_date.'")');
		} elseif($from_date){
			$this->db->where('(`t`.`task_scheduled_date`>="'.$from_date.'" and `t`.`task_scheduled_date`<"'.$today_date.'")');
		} else {
			
		}
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->group_by('t.task_id');
		$this->db->order_by('t.task_id','desc');
		//$this->db->limit(200);
		$query = $this->db->get();
//		echo $this->db->last_query(); 
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
		
	}
        
        
        function Timerworklog_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id){
                $user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
			$ids = implode(',', $user_ids);
		}
                /**
                 * fetch query from db
                 */
                
		$this->db->select('t.task_id,t.task_title,u.first_name,u.last_name,p.project_title,ttl.interruption,ttl.comment,ttl.date_added,(SELECT customer_name FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as customer_name,(SELECT external_id FROM customers cs WHERE cs.customer_id = t.customer_id AND cs.customer_company_id ='. $this->session->userdata('company_id').') as external_id,t.cost_per_hour,t.cost,t.charge_out_rate,t.estimated_total_charge,t.actual_total_charge,ts.task_status_name');
		$this->db->from('tasks t');
		$this->db->join('task_timer_logs ttl','t.task_id = ttl.task_id');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id');
		$this->db->join('task_category tc','tc.category_id = t.task_category_id','left');
                $this->db->join('project p','p.project_id = t.task_project_id','left');
                $this->db->join('task_status ts','ts.task_status_id = t.task_status_id','left');
		if($user_id !='' && $user_id != 'all'){
			$this->db->where('t.task_allocated_user_id',$user_id);
		} else {
			if($ids){
				$this->db->where('(t.task_owner_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id = "'.get_authenticateUserID().'" or t.task_allocated_user_id IN ('.$ids.'))');
			}
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
		if($from_date!='' && $to_date!=''){
                        $date = new DateTime($from_date." ".date("00:00:00"));
                        $date->setTimezone(new DateTimeZone("UTC"));
                        $from_date = $date->format("Y-m-d");
                        
                        $date1 = new DateTime($to_date." ".date("23:59:59"));
                        $date1->setTimezone(new DateTimeZone("UTC"));
                        $to_date = $date1->format("Y-m-d");
                        
			$this->db->where('( DATE(`ttl`.`date_added`)>="'.$from_date.'" and DATE(`ttl`.`date_added`)<="'.$to_date.'")');
		} elseif($from_date){
                        $date = new DateTime($from_date." ".date("00:00:00"));
                        $date->setTimezone(new DateTimeZone("UTC"));
                        $from_date = $date->format("Y-m-d");
                        
			$this->db->where('( DATE(`ttl`.`date_added`)>="'.$from_date.'")');
		} elseif($to_date){
                        $date1 = new DateTime($to_date." ".date("23:59:59"));
                        $date1->setTimezone(new DateTimeZone("UTC"));
                        $to_date = $date1->format("Y-m-d");
			$this->db->where('(DATE(`ttl`.`date_added`) <="'.$to_date.'")');
		} else { }
                if($customer_id){
                    $this->db->where('t.customer_id',$customer_id);
                }
//		$this->db->where('ttl.is_manual','0');
//		$this->db->where('(ttl.interruption != "" and interruption != "Task Completed")');
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		$this->db->where('t.is_deleted','0');
                //$this->db->order_by('t.task_id','desc');
		//$this->db->limit(200);
		$query = $this->db->get();
		//echo "<pre>"; echo $this->db->last_query(); die();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
        }
        function Mytasksallocatedtootherusers($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id)
	{
		$task_completed_status_id = $this->config->item('completed_id');
		$today_date = date('Y-m-d');
		$user_ids = get_users_under_manager();
		$ids = '';
                if($user_id == 'all')
                {
                    $user_ids = array();
                    $allusers = get_company_users();
                    $user_ids = array();
                    foreach($allusers as $one)
                    {
                        $user_ids[] = $one->user_id;
                    }
                }
		if($user_ids){
                    $ids = implode(',', $user_ids);
		}
		
                $this->db->select('t.task_id,t.task_title,u.user_id,u.first_name as allocated_user_first_name,u.last_name as allocated_user_last_name,t.task_added_date,t.task_due_date,t.task_scheduled_date,t.task_completion_date,ts.task_status_name');
		$this->db->from('tasks t');
		$this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$this->db->join('task_status ts','ts.task_status_id = t.task_status_id','left');
                $this->db->where('t.task_owner_id ',get_authenticateUserID());
                if($ids){
                    $this->db->where('t.task_allocated_user_id IN ('.$ids.')');
                }
                if($division_id){
			$this->db->join('user_devision ud','ud.user_id = u.user_id','left');
			$this->db->where_in('ud.devision_id',$division_id);
		}
		if($department_id){
			$this->db->join('user_department udp','udp.user_id = u.user_id','left');
			$this->db->where_in('udp.dept_id',$department_id);
		}
		if($category_id){
			$this->db->where('t.task_category_id',$category_id);
		}
		if($sub_category_id){
			$this->db->where('t.task_sub_category_id',$sub_category_id);
		}
		if($project_id){
			$this->db->where('t.task_project_id',$project_id);
		}
                if($customer_id){
                        $this->db->where('t.customer_id',$customer_id);
                       // $this->db->where('c.customer_company_id',$this->session->userdata('company_id'));
                }
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_personal','0');
		$this->db->where('t.is_deleted','0');
		$this->db->where('t.task_status_id !=',$task_completed_status_id);
		if($to_date!='' && $from_date!=''){
			$this->db->where('(`t`.`task_due_date`<="'.$to_date.'" and `t`.`task_due_date`>="'.$from_date.'")');
		} elseif($to_date){
			$this->db->where('(`t`.`task_due_date`<="'.$to_date.'" and `t`.`task_due_date`<"'.$today_date.'")');
		} elseif($from_date){
			$this->db->where('(`t`.`task_due_date`>="'.$from_date.'" and `t`.`task_due_date`<"'.$today_date.'")');
		} else {
			
		}
		$this->db->where('t.task_company_id',$this->session->userdata('company_id'));
		
		$this->db->order_by('t.task_due_date','asc');
		//$this->db->limit(200);
		$query = $this->db->get();
		//echo "<pre>"; echo $this->db->last_query(); die();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
		
	}


}?>
