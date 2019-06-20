<?php 

/**
 * This  controller class is used to create report page and it will create and export excel sheets of appropriate reports .
 * This class is extending the SPACULLUS_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Reports extends SPACULLUS_Controller{
	/**
        * It default constuctor which is called when reports object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	
	/*   
	 Function name : Reports()
	 Description :Its Default Constuctor which called when reports object initialzie.its load necesary models
	 */
	function Reports()
	{
            /**
             * Call base class constructor
             */
      		parent ::__construct();
                /**
                 * Amazon S3 server Configuration
                 */
		$this->load->library('s3');
                /**
                 * Amazon S3 Configuration
                 */
		$this->config->load('s3');
                /**
                 * Load reports database class
                 */
		$this->load->model('reports_model');
                /**
                 * Set default timezone
                 */
		date_default_timezone_set("UTC");
	}
	/**
         * This function will call when user click on report link.It will render report page.
         * It get report information for create report page.
         * @returns create view
         */
	
	/*
	 * Function : index()
	 * Author : Spaculus
	 * Desc : This function is used to display default page of report module  
	*/
	function index(){
            /**
             * check user authentication
             */
		if(!check_user_authentication()){
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		/**
                 * $data is array.it stores all parameters of report page.
                 */
		$data = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['theme'] = $theme;
		
		$data['devision'] = get_company_division($this->session->userdata('company_id'),'Active');
		$data['departments'] = get_company_department($this->session->userdata('company_id'),'Active');
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
		$data['reports_data'] = '';
		$data['categories'] = '';
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
		$data['customers'] =  getCustomerList();
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
                /**
                 * render report page
                 */
		$this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);
		
		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);
		
		$this->template->write_view('content_side', $theme.'/layout/reports/index', $data, TRUE);
		
		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
		
		
	}
        /**
         * On report page, this function will set department list on department drop-down.
         * @returns create view of department 
         */
	
	function setDepartment(){
            /**
             * check user authentication
             */
		if(!check_user_authentication()){
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		
		$data = array();
		
		$division_id = $_POST['division_id'];
                /**
                 * check division id
                 */
		if($division_id){
			$data['departments'] = get_company_department($this->session->userdata('company_id'),'Active',$division_id);
		} else {
			$data['departments'] = '';
		}
		
		echo $this->load->view($theme.'/layout/reports/ajax_departments',$data,TRUE);
	}
	/**
         * On report pagee, this function is used to fetch sub category from main category
         * @returns void 
         */
	
	/*
	 * Function : getSubCategory()
	 * Author : Spaculus
	 * Desc : This function is used to fetch sub category from main category
	*/
	function getSubCategory(){
		if(!check_user_authentication()){
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		
		$data = array();
		
		$category_id = $_POST['category_id'];
		if($category_id){
			$data['sub_category'] = get_company_category($this->session->userdata('company_id'),'Active',$category_id);
		} else {
			$data['sub_category'] = '';
		}
		echo $this->load->view($theme.'/layout/reports/ajax_subCategory',$data,TRUE);
	}
	
	/**    
         * This function will call when user click on report run button on report page.
         * And it will generate report according to it's selected options.
         * If user will select any filter than it will create according the filters
         * otherwise it will generate according to report option.
         * @returns create view
	*/
	function run_reports(){
		if(!check_user_authentication()){
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		
		$data = array();
		$report_title = $_POST['report_title'];
		$view = str_replace(array(" ","/"), "", $report_title);
		/**
                 * check user,division,department,category id is set or not
                 */
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:'';
		$division_id = isset($_POST['division_id'])?$_POST['division_id']:'';
		$department_id = isset($_POST['department_id'])?$_POST['department_id']:'';
		$category_id = isset($_POST['category_id'])?$_POST['category_id']:'';
		$sub_category_id = isset($_POST['sub_category_id'])?$_POST['sub_category_id']:'';
		$project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
		$customer_id = isset($_POST['customer_id'])?$_POST['customer_id']:'';
		date_default_timezone_set($this->session->userdata("User_timezone"));
		/**
                 * check from date is set or not empty
                 */
                if(isset($_POST['from_date']) && $_POST['from_date']!=''){
                        $from_date = change_date_format($_POST['from_date']);
                } else {
			$from_date = '';
		}
		if(isset($_POST['to_date']) && $_POST['to_date']!=''){
			$to_date = change_date_format($_POST['to_date']);
                } else {
			$to_date = '';
		}
                
		if($view == "Lastloginperuser"){
			
			$data['reports_data'] = $this->reports_model->Loginperuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			
		} elseif($view == "Loginhistorybyuser"){
			
			$data['reports_data'] = $this->reports_model->Loginhistorybyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			
		} elseif($view == "Listofoverduetasks"){
			
			$data['reports_data'] = $this->reports_model->Listofoverduetasks_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			
		} elseif($view == "Timeallocationbycategory"){
			
			$data['reports_data'] = $this->reports_model->Timeallocationbycategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			
		} elseif($view == "Timeallocatedbyproject"){
			
			$data['reports_data'] = $this->reports_model->Timeallocatedbyproject_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			
		} elseif($view == "Tasksduethisweekbyuser"){
			
			$data['reports_data'] = $this->reports_model->Tasksduethisweekbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			
		} elseif($view == "Interruptionsbytypeandbyuser"){
			
			$data['reports_data'] = $this->reports_model->Interruptionsbytypeandbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			
		} elseif($view == "ActivitybyCategory"){
			
			$data['reports_data'] = $this->reports_model->ActivitybyCategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			
		} elseif($view == "Actualtimebycategoryoveraperiodoftime"){
			
			$data['categories'] = $this->reports_model->Actualtimebycategoryoveraperiodoftime_category($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			$data['reports_data'] = $this->reports_model->Actualtimebycategoryoveraperiodoftime_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			
			if($data['categories'] && $data['reports_data']){
				
			} else {
				echo "no_data";die;
			}
		} elseif($view == "Dailytimeallocationbyuser"){
			
			$data['reports_data'] = $this->reports_model->Dailytimeallocationbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			
		} elseif($view == "DailyTimeallocationpercategoryandsubcategory"){
			
			$data['reports_data'] = $this->reports_model->DailyTimeallocationpercategoryandsubcategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			
		} elseif($view == "Listoftasks"){
			$data['reports_data'] = $this->reports_model->Listofcompletedtasks_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
		} elseif($view == "Mytasksallocatedtootherusers"){
                        $data['reports_data'] = $this->reports_model->Mytasksallocatedtootherusers($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
		} elseif($view == "Timerworklog"){
                    
                        $data['reports_data'] = $this->reports_model->Timerworklog_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
                }else {
			$data['reports_data'] = '';
		}
//                print_r($data);
//                echo $this->db->last_query();
//                die;
                
		$data['site_setting_date'] = $this->config->item('company_default_format');
		/**
                 * create view of report
                 */
            //  echo "<pre>"; print_r($data['reports_data']); die();
		echo $this->load->view($theme.'/layout/reports/'.$view,$data,TRUE);
		
		
	}
	/**
         * This function will call when user click on export option of report page.
         * It will generate report in excel sheet format.It will also create report according to filter and select report option.
         * @returns void
         */
	function export()
	{
		if(!check_user_authentication()){
			redirect('home');
		}
		/**
                 * for generate excelsheet this is loaded
                 */
		$this->load->library('excel');
		/**
                 * set activate worksheet number 
                 */
		$this->excel->setActiveSheetIndex(0);
		/**
                 * set name the worksheet
                 */
		$this->excel->getActiveSheet()->setTitle('order');
		
		
		$report_title = $_POST['report_title'];
		$view = str_replace(array(" ","/"), "", $report_title);
		
		$site_setting_date = $this->config->item('company_default_format');
		
		$user_id = isset($_POST['user_id'])?$_POST['user_id']:'';
		$division_id = isset($_POST['division_id'])?$_POST['division_id']:'';
		$department_id = isset($_POST['department_id'])?$_POST['department_id']:'';
		$category_id = isset($_POST['category_id'])?$_POST['category_id']:'';
		$sub_category_id = isset($_POST['sub_category_id'])?$_POST['sub_category_id']:'';
		$project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
		$graph_img = isset($_POST['graph_image'])?$_POST['graph_image']:'';
		$customer_id = isset($_POST['customer_id'])?$_POST['customer_id']:'';
		date_default_timezone_set($this->session->userdata("User_timezone")); 
		
		if(isset($_POST['from_date']) && $_POST['from_date']!=''){
                        $from_date = change_date_format($_POST['from_date']);
                } else {
			$from_date = '';
		}
		if(isset($_POST['to_date']) && $_POST['to_date']!=''){
			$to_date = change_date_format($_POST['to_date']);
                } else {
			$to_date = '';
		}
		if($view == "Timeallocationbycategory"){
			//set cell A1 content with some text
			$tables=array('Category', 'Sub category', 'User','Day','Time Allocated (Hrs)','Time Spent (Hrs)');
			$key=array('A1','B1','C1','D1','E1','F1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Timeallocationbycategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					
					$allocated_time = $row['allocationtime'];
					$res['category_name'] = $row['category_name'];
					$res['subcategory_name'] = $row['sub_category_name'];
					$res['user_name'] = $row['first_name'].' '.$row['last_name'];
					$res['date'] = date($site_setting_date,toDateUserTimeStamp($row['task_true_date']));
					$res['allocation_time'] = round($allocated_time/60,2);
					$res['spenttime'] = round($row['spenttime']/60,2);
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F');
			
			
		} elseif($view == "Lastloginperuser"){
			//set cell A1 content with some text
			$tables=array('User', 'Manager', 'Divisions','Departments','Last login date/time');
			$key=array('A1','B1','C1','D1','E1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Loginperuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$division = get_user_division($row['user_id']);
					if($division){
						$division = $division;
					} else {
						$division = "N/A";
					}
					$department = get_user_department($row['user_id']);
					if($department){
						$department = $department;
					} else {
						$department = "N/A";
					}
					$res['user_name'] = $row['first_name']." ".$row['last_name'];
					$res['is_manager'] = (isset($row['is_manager']) && $row['is_manager']=='1')?"Yes":"No"; 
					$res['division'] = $division;
					$res['department'] = $department;
					$res['last_login'] = date($site_setting_date." H:i:s",strtotime(toDateNewTime($row['user_login_date'])));
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E');
		
			
		} elseif($view == "Loginhistorybyuser"){
			//set cell A1 content with some text
			$tables=array('User', 'Divisions','Departments','Last login date/time');
			$key=array('A1','B1','C1','D1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Loginhistorybyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$division = get_user_division($row['user_id']);
					if($division){
						$division = $division;
					} else {
						$division = "N/A";
					}
					$department = get_user_department($row['user_id']);
					if($department){
						$department = $department;
					} else {
						$department = "N/A";
					}
					$res['user_name'] = $row['first_name']." ".$row['last_name'];
					$res['division'] = $division;
					$res['department'] = $department;
					$res['last_login'] = date($site_setting_date." H:i:s",strtotime(toDateNewTime($row['user_login_date'])));
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D');
			
		} elseif($view == "Listofoverduetasks"){
			//set cell A1 content with some text
			$tables=array('Task ID', 'Task Name', 'Allocated to','Manager','Task Owner','Priority','Project','Category','Sub Category','Due Date','Days Overdue','Estimated Time (Hrs)','Time Spent (Hrs)','Task Status','Customer Name','External ID','No of interruptions logged for this task');
			$key=array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1','Q1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Listofoverduetasks_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$users = get_managers_of_users($row['task_allocated_user_id']);
					$managers = '';
					if($users){
						foreach($users as $u){
							if($managers){
								$managers .= ', '.$u->first_name.' '.$u->last_name;
							} else {
								$managers .= $u->first_name.' '.$u->last_name;
							}
						}
					}
					if($managers){
						$managers = $managers;
					} else {
						$managers = "N/A";
					}
					$project_name = $row['project_title'];
					if($project_name){
						$project_name = $project_name;
					} else {
						$project_name = "N/A";
					}
					$category_name = $row['category_name'];
					if($category_name){
						$category_name = $category_name;
					} else {
						$category_name = "N/A";
					}
					$sub_category_name = $row['sub_category_name'];
					if($sub_category_name){
						$sub_category_name = $sub_category_name;
					} else {
						$sub_category_name = "N/A";
					}
					if($row['task_due_date']){
						$due = $row['task_due_date'];
						$date = date($site_setting_date,toDateUserTimeStamp($row['task_due_date'])); 
					} else {
						$due = '';
						$date = '';
					}
					$now = time(); // or your date as well
				     $your_date = strtotime(str_replace(array("/"," ",","), "-", $due));
				     $datediff = $now - $your_date;
				    
					$res['task_id'] = $row['task_id'];
					$res['task_title'] = $row['task_title'];
					$res['task_allocated_user_id'] = $row['first_name']." ".$row['last_name'];
					$res['managers'] = $managers;
					$res['owner'] = $row['owner_first_name']." ".$row['owner_last_name'];
					$res['priority'] = $row['task_priority'];
					$res['project_name'] = $project_name;
					$res['category_name'] = $category_name;
					$res['sub_category_name'] = $sub_category_name;
					$res['date'] = $date;
					$res['diff'] = floor($datediff/(60*60*24));
					$res['task_time_estimate'] = round($row['task_time_estimate']/60,2);
					$res['task_time_spent'] = round($row['task_time_spent']/60,2);
					$res['task_status_id'] = $row['task_status_name'];
                                        $res['customer_name'] = $row['customer_name'];
                                        $res['external_id'] = $row['external_id'];
					$res['interuptins'] = interruption_by_task($row['task_id']);
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q');
			
			
		} elseif($view == "ActivitybyCategory"){
			//set cell A1 content with some text
			$tables=array('Category', 'User','Start date','To date','Time allocated (Hrs)','Time spent (Hrs)');
			$key=array('A1','B1','C1','D1','E1','F1',);
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->ActivitybyCategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			$result = array();
			$res = array();
			if($from_date){
				$start_date = date($site_setting_date,strtotime($from_date));
			} else {
				$start_date = "N/A";
			}
			if($to_date){
				$end_date = date($site_setting_date,strtotime($to_date));
			} else {
				$end_date = "N/A";
			}
			if($order_result){
				foreach($order_result as $row){
					$category_name = $row['category_name'];
					if($category_name){ $category_name = $category_name; } else { $category_name = 'N/A'; }
					$res['category_name'] = $category_name;
					$res['user_name'] = $row['first_name'].' '.$row['last_name'];
					$res['start_date'] = $start_date;
					$res['end_date'] = $end_date;
					$res['time_estimate'] = round($row['task_time_estimate']/60,2);
					$res['spent_time'] = round($row['task_time_spent']/60,2);
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F');
			
		} elseif($view == "Actualtimebycategoryoveraperiodoftime"){
			
			$this->export_chart($graph_img,$user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);die;
			//set cell A1 content with some text
			$tables=array('User', 'Manager', 'Divisions','Departments','Last login date/time');
			$key=array('A1','B1','C1','D1','E1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Actualtimebycategoryoveraperiodoftime_report();
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$res['project_title'] = $row['project_title'];
					$res['project_start_date'] = date($site_setting_date,toDateUserTimeStamp($row['project_start_date']));
					$res['project_end_date'] = date($site_setting_date,toDateUserTimeStamp($row['project_end_date']));
					$res['section_name'] = $row['section_name'];
					$res['user_name'] = $row['first_name'].' '.$row['last_name'];
					$res['allocationtime'] = $row['allocationtime'];
					$res['actualtime'] = $row['actualtime'];
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E');
			
			
		} elseif($view == "Timeallocatedbyproject"){
			//set cell A1 content with some text
			$tables=array('Project', 'Start Date', 'End Date','Project Section','User','Customer Name','External ID','Time Allocated (Hrs)','Time Spent (Hrs)');
			$key=array('A1','B1','C1','D1','E1','F1','G1','H1','I1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Timeallocatedbyproject_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($from_date){
				$start_date = date($site_setting_date,strtotime($from_date));
			} else {
				$start_date = "N/A";
			}
			if($to_date){
				$end_date = date($site_setting_date,strtotime($to_date));
			} else {
				$end_date = "N/A";
			}
			if($order_result){
				foreach($order_result as $row){
					if($row['section_name']){ $section_name = $row['section_name']; } else { $section_name = "N/A"; }
					$res['project_title'] = $row['project_title'];
					// $res['project_start_date'] = date($site_setting_date,toDateUserTimeStamp($row['project_start_date']));
					// $res['project_end_date'] = date($site_setting_date,toDateUserTimeStamp($row['project_end_date']));
					$res['start_date'] = $start_date;
					$res['end_date'] = $end_date;
					$res['section_name'] = $section_name;
					$res['user_name'] = $row['first_name'].' '.$row['last_name'];
                                        $res['customer_name']= $row['customer_name'];
                                        $res['external_id']=$row['external_id'];
					$res['allocationtime'] = round($row['allocationtime']/60,2);
					$res['actualtime'] = round($row['actualtime']/60,2);
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F','G','H','I');
			
			
		} elseif($view == "Tasksduethisweekbyuser"){
			//set cell A1 content with some text
			$tables=array('Task Id','User', 'Due Date', 'Task Name','Task Status','Estimated Time (Hrs)','Spent Time (Hrs)','No of interruptions','Priority','Project','Task owner','Colour','Category','Sub Category','Customer Name','External ID');
			$key=array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Tasksduethisweekbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$color_name = $row['name'];
					
					$category_name = $row['category_name'];
					
					$sub_category_name = $row['sub_category_name'];
					
					$project_name = $row['project_title'];
                                        
					$date = date($site_setting_date,toDateUserTimeStamp($row['task_due_date'])); 
					$res['task_id'] = $row['task_id'];
					$res['user_name'] = $row['first_name'].' '.$row['last_name'];
					$res['date'] = $date;
					$res['task_title'] = $row['task_title'];
                                        $res['task_status_name'] = $row['task_status_name'];
					$res['task_time_estimate'] = round($row['task_time_estimate']/60,2);
					$res['task_time_spent'] = round($row['task_time_spent']/60,2);
					$res['interruptions'] = interruption_by_task($row['task_id'],'this_week');
					$res['task_priority'] = $row['task_priority'];
					$res['project_title'] = $project_name;
					$res['owner'] = $row['owner_first_name']." ".$row['owner_last_name'];
					$res['color'] = $color_name;
					$res['category_name'] = $category_name;
					$res['sub_category_name'] = $sub_category_name;
                                        $res['customer_name'] = $row['customer_name'];
                                        $res['external_id'] = $row['external_id'];
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P');
			
			
		} elseif($view == "Interruptionsbytypeandbyuser"){
			//set cell A1 content with some text
			$tables=array('Task Id','Task Name','Task Category','User','Customer Name','External ID', 'Interruption', 'Date');
			$key=array('A1','B1','C1','D1','E1','F1','G1','H1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Interruptionsbytypeandbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$category_name = $row['category_name'];
					if($category_name){
						$category_name = $category_name;
					} else {
						$category_name = "N/A";
					}
					$res['task_id'] = $row['task_id'];
					$res['task_title'] = $row['task_title'];
					$res['task_category'] = $category_name;
					$res['user_name'] = $row['first_name'].' '.$row['last_name'];
                                        $res['customer_name'] = $row['customer_name'];
                                        $res['external_id'] = $row['external_id'];
					$res['interruption'] = $row['interruption'];
					$res['date'] = date($site_setting_date." H:i:s",strtotime(toDateNewTime($row['date_added'])));
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F','G','H');
			
			
		} elseif($view == "Dailytimeallocationbyuser"){
			//set cell A1 content with some text
			$tables=array('Date', 'User', 'Allocated Time (Hrs)','Time Spent (Hrs)');
			$key=array('A1','B1','C1','D1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Dailytimeallocationbyuser_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$res['task_true_date'] = date($site_setting_date,toDateUserTimeStamp($row['task_true_date']));
					$res['user_name'] = $row['first_name'].' '.$row['last_name'];
					$res['allocationtime'] = round($row['allocationtime']/60,2);
					$res['spenttime'] = round($row['spenttime']/60,2);
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D');
			
			
		} elseif($view == "DailyTimeallocationpercategoryandsubcategory"){
			//set cell A1 content with some text
			$tables=array('Day', 'Category', 'Sub category','User','Allocated Time (Hrs)','Time Spent (Hrs)');
			$key=array('A1','B1','C1','D1','E1','F1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->DailyTimeallocationpercategoryandsubcategory_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$res['date'] = date($site_setting_date,toDateUserTimeStamp($row['task_true_date']));
					$res['category_name'] = $row['category_name'];
					$res['subcategory_name'] = $row['sub_category_name'];
                                        $res['user_name'] = $row['first_name'].' '.$row['last_name'];
					$res['allocationtime'] = $row['allocationtime'];
					$res['spenttime'] = $row['spenttime'];
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F');
			
			
		} elseif($view == "Listoftasks"){
			//set cell A1 content with some text
			$tables=array('Task ID', 'Task Name','Task Description', 'Task Owner','Allocated to','User department','User division','Priority','Colour','Project','Task Status','Task Category','Task Sub Category','Time Allocated (Hrs)','Actual Time (Hrs)','Creation Date','Scheduled Date','Due Date','Customer Name','External ID','Base Cost','Estimated Total Cost','Base Charge','Estimated Total Revenue','No of interruptions logged for this task');
			$key=array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1','Q1','R1','S1','T1','U1','V1','W1','X1','Y1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Listofcompletedtasks_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					$scheduled_date = 'N/A';
					$due_date = 'N/A';
					if($row['task_scheduled_date']!='0000-00-00'){ $scheduled_date = date($site_setting_date,toDateUserTimeStamp($row['task_scheduled_date'])); }
					if($row['task_due_date']!='0000-00-00'){ $due_date =  date($site_setting_date,toDateUserTimeStamp($row['task_due_date'])); }
					if($row['project_title']){ $project_title = $row['project_title']; } else { $project_title = "N/A"; }
					$color_name = $row['name'];
					if($color_name){
						$color_name = $color_name;
					} else {
						$color_name = "N/A";
					}
					$category_name = $row['category_name'];
					if($category_name){
						$category_name = $category_name;
					} else {
						$category_name = "N/A";
					}
					$sub_category_name = $row['sub_category_name'];
					if($sub_category_name){
						$sub_category_name = $sub_category_name;
					} else {
						$sub_category_name = "N/A";
					}
					$division = get_user_division($row['user_id']);
					if($division){
						$division = $division;
					} else {
						$division = "N/A";
					}
					$department = get_user_department($row['user_id']);
					if($department){
						$department = $department;
					} else {
						$department = "N/A";
					}
					$res['task_id'] = $row['task_id'];
					$res['task_title'] = $row['task_title'];
                                        $res['task_description'] = strip_tags($row['task_description']);
					$res['task_owner_id'] = $row['owner_first_name']." ".$row['owner_last_name'];
					$res['user_name'] = $row['allocated_user_first_name']." ".$row['allocated_user_last_name'];
					$res['departments'] = $department;
					$res['divisions'] = $division;
					$res['task_priority'] = $row['task_priority'];
					$res['color'] = $color_name;
					$res['project'] = $project_title;
                                        $res['task_status'] = $row['task_status_name'];
					$res['task_category_id'] = $category_name;
					$res['task_sub_category_id'] = $sub_category_name;
					$res['time_estimate'] = round($row['task_time_estimate']/60,2);
					$res['spent_time'] = round($row['task_time_spent']/60,2);
					$res['creation_date'] = date($site_setting_date,strtotime(toDateNewTime($row['task_added_date'])));
					$res['scheduled_date'] = $scheduled_date;
					$res['due_date'] = $due_date;
                                        $res['customer_name'] = $row['customer_name'];
                                        $res['external_id'] = $row['external_id'];
					$res['cost'] = $row['cost_per_hour'];
                                        $res['estimated_total_cost'] = $row['cost'];
                                        $res['base_charge'] = $row['charge_out_rate'];
                                        $res['estimated_total_revenue'] = $row['estimated_total_charge'];
					$res['interuptions'] = interruption_by_task($row['task_id']);
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y');
			
		} elseif($view == "Mytasksallocatedtootherusers"){
			//set cell A1 content with some text
			$tables=array('Task Name', 'Task Staus', 'Task Creation date','Due Date','Scheduled date','Allocated to');
			$key=array('A1','B1','C1','D1','E1','F1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result=$this->reports_model->Mytasksallocatedtootherusers($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
                                        $scheduled_date = 'N/A';
					$due_date = 'N/A';
					if($row['task_scheduled_date']!='0000-00-00'){ $scheduled_date = date($site_setting_date,toDateUserTimeStamp($row['task_scheduled_date'])); }
					if($row['task_due_date']!='0000-00-00'){ $due_date =  date($site_setting_date,toDateUserTimeStamp($row['task_due_date'])); }
                                        $res['task_title'] = $row['task_title'];
					$res['task_status'] = $row['task_status_name'];
					$res['creation_date'] = date($site_setting_date,strtotime(toDateNewTime($row['task_added_date'])));
					$res['scheduled_date'] = $scheduled_date;
					$res['due_date'] = $due_date;
                                        $res['user_name'] = $row['allocated_user_first_name']." ".$row['allocated_user_last_name'];
					$result[] = $res; 
				}
			}
                        
			$key1=array('A','B','C','D','E','F');
			
			
		} elseif($view == "Timerworklog"){
                        //set cell A1 content with some text
			$tables=array('Task Id','Task Name','Task Status','User','Project Name','Customer Name','External ID', 'Interruption', 'Comment','Base Cost','Estimated Total Cost','Base Charge','Estimated Total Revenue','Date');
			$key=array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$order_result = $this->reports_model->Timerworklog_report($user_id,$division_id,$department_id,$category_id,$sub_category_id,$project_id,$from_date,$to_date,$customer_id);
			$result = array();
			$res = array();
			if($order_result){
				foreach($order_result as $row){
					
					$res['task_id'] = $row['task_id'];
					$res['task_title'] = $row['task_title'];
                                        $res['task_status'] = $row['task_status_name'];
                                        $res['user'] = $row['first_name'].' '.$row['last_name'];
                                        $res['project_title'] = $row['project_title'];
					$res['customer_name'] = $row['customer_name'];
                                        $res['external_id'] = $row['external_id'];
					$res['interruption'] = $row['interruption'];
                                        $res['comment'] = $row['comment'];
                                        $res['base_cost'] = $row['cost_per_hour'];
                                        $res['estimated_total_cost'] = $row['cost'];
                                        $res['base_charge'] = $row['charge_out_rate'];
                                        $res['estimated_total_revenue'] = $row['estimated_total_charge'];
					$res['date'] = date($site_setting_date." H:i:s",strtotime(toDateNewTime($row['date_added'])));
					$result[] = $res; 
				}
			}
			$key1=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
			
                        
                }else {
			//set cell A1 content with some text
			$tables=array('User', 'Manager', 'Divisions','Departments','Last login date/time');
			$key=array('A1','B1','C1','D1','E1');
			$title=array_combine($key, $tables);
			foreach ($title as $key=>$value)
			{
				$this->excel->getActiveSheet()->setCellValue($key, $value);
				//change the font size
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
				//make the font become bold
				$this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
			}
			
			
			$result='';
			
			$key1=array('A','B','C','D','E');
		}
		
		
		$i=2;
		
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
		
		$filename='Export_'.$view.''.date("Y:m:d h:i:s") .'.xlsx'; //save our workbook as this file name
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
		//force user to download the Excel file without writing it to server's HD
//		ob_end_clean();
		$objWriter->save('php://output');
	
				//return "success";
	}
	
	/**
         * This function is used for generate pdf version of report.
         * @returns void 
         */
	function chart_html()
	{
		//var_dump($_POST["id"]);
		
		$image = '<img src="'.$_POST['id'].'">';
		// Get output html
		$html = $this->output->get_output();
		
		// Load library
		$this->load->library('dompdf_gen');
		$file_name = 'Export_Actualtimebycategoryoveraperiodoftime'.date("Y:m:d h:i:s") .'.pdf';
		// Convert to PDF
		$this->dompdf->load_html($image);
		$this->dompdf->render();
		$this->dompdf->stream($file_name);
		
		
		
	}
	
	/**
         *  This function is used to export chart from reports.It will call from same controller for create chart html.
         * @returns void 
	 */
	function export_chart($graph_img,$user_id='',$division_id='',$department_id='',$category_id='',$sub_category_id='',$project_id='',$from_date='',$to_date=''){
		$img = '';
		if($graph_img){
			$img = '<img src="'.$graph_img.'" />';
		}
		
		$file_name = 'Export_Actualtimebycategoryoveraperiodoftime'.date("Y:m:d h:i:s") .'.pdf';
		// Get output html
		
		$this->load->library("Pdf");
    
    /**
     *  create new PDF document
     */
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
 
    /**
     *  set document information
     */
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Schedullo Report');
    $pdf->SetSubject('Schedullo Report');
    $pdf->SetKeywords('Schedullo, Report');   
 
    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', "", array(0,64,255), array(0,64,128));
    $pdf->setFooterData(array(0,64,0), array(0,64,128)); 
 
    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
 
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
 
    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);    
 
    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 
 
    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);  
 
    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }   
 
    // ---------------------------------------------------------    
 
    // set default font subsetting mode
    $pdf->setFontSubsetting(true);   
 
    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 14, '', true);   
 
    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage(); 
 
    // set text shadow effect
    $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));    
 
    // Set some content to print
    $html = <<<EOD
    <h5>Actual time by category over a period of time Graph Report : </h5>
	{$img}    
EOD;
 
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
 
    // ---------------------------------------------------------    
 
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    //ob_end_clean();
    $pdf->Output($file_name, 'I');   
		
	}
        /**
         * On report pagee, this function is used to fetch project users
         * @returns void 
         */
	
	/*
	 * Function : getProjectUsers()
	 * Author : Spaculus
	 * Desc : This function is used to project Users 
	*/
	function getProjectUsers(){
		if(!check_user_authentication()){
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		
		$data = array();
		
		$project_id = $_POST['project_id'];
		if($project_id){
			$data['users'] = get_project_user_list($project_id);
		} else {
			$data['users'] = get_company_users();
		}
                $data['project_id'] = $project_id;
                
		echo $this->load->view($theme.'/layout/reports/ajax_userList',$data,TRUE);
	}
}

?>
