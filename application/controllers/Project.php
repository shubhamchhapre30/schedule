<?php

/**
 * This controller class is used for create project page and it show list of project of loggedin user. 
 * There is following methods like  listProject(),addProject() etc.
 * This class is extending the SPACULLUS_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Project extends SPACULLUS_Controller {

	   /**
        * It default constuctor which is called when project object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	function Project () {
            /**
             * load base class contructor
             */
		parent :: __construct ();
		//$this->load->library ("PasswordHash");
                /**
                 * Amazon S3 server Configuration file
                 */
		$this->load->library('s3');
                /**
                 * Amazon S3  Configuration file
                 */
		$this->config->load('s3');
                /**
                 * load database of project controller
                 */
		$this->load->model('project_model');
                /**
                 * load task_model database
                 */
		$this->load->model('task_model');
                /**
                 * load user_agent 
                 */
		$this->load->library('user_agent');
                /**
                 * load kanban_model class
                 */
		$this->load->model('kanban_model');
                /**
                 * set default timezone
                 */
		date_default_timezone_set("UTC");
		/**
                 * load library for encrypting form data
                 */
                $this->load->library('encrypt');
	}
        /**
         * This method will redirect on other function .
         * @param string $msg
         * @returns void
         */
	public function index ($msg = '') {

		redirect('project/listProject');
	}
        /**
         * When user click on project link at the same time this method will call.It is used to show list of user project.
         * It will fetch project information in db and render project page.
         * @returns create view
         */
	function listProject()
	{
            /**
             * check authentication
             */
		if (!check_user_authentication()){
			redirect ('home');
		}
		//echo "here";die;
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		//$data['site_setting'] = $this->config->item('company_default_format');

		$data = array();
		$data['theme'] = $theme;
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 

		$filter = 'Open';
                /**
                 * GET PROJECT LIST
                 */
		$data['projects'] = $this->project_model->get_project_list($filter);
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
		/**
                 * render listproject page
                 */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/project/listProject',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
		
	}
        /* this funcion creates copy of a project.
        It will copy all tasks from project with default status Not Ready.
        */
        function copyProject()
        {
            $project_id=$this->input->post('id')>0?$this->input->post('id'):0;
            $filter=$this->input->post('filter')!=''?$this->input->post('filter'):'Open';
            $one_project = $this->project_model->get_one_project($project_id);
            
            if($one_project)
            {
                $id = $this->project_model->copy_project($project_id);
            }
            $data['user'] = get_user_info(get_authenticateUserID());
            $data['projects'] = $this->project_model->get_project_list($filter);
            $theme = getThemeName ();
            $this->load->view($theme.'/layout/project/listProject_Ajax',$data);
        }
        
        /**
         * This function is called when user click on add project link on project page.It is used as addproject and editproject also.
         * It will fetch project information in db than it will render addproject page on project view.
         * @returns createview
         */
	function addProject(){
            /**
             * check authentication
             */
		if (!check_user_authentication()) {
			redirect ('home');
		}


                /**
                 * get select option from menu
                 */
		

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data = array();
		$data['theme'] = $theme;
		$offset=0;
		$data['error'] = '';
		//$data['user_id'] = get_authenticateUserID();
		$data['user_id'] = 'all';
		$data['type'] = 'all';
		if($_POST){
			$offset=$this->input->post('offset')>0?$this->input->post('offset'):0;
		}else{
			$offset=$offset;
		}

		$limit = 10;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('project_title','Project Title','required');
		$this->form_validation->set_rules('project_status','Project status','required');
		$this->form_validation->set_rules('project_start_date','Project start date','required');
		$this->form_validation->set_rules('project_end_date','Project end date','required');
		$this->form_validation->set_rules('project_desc','Project description','required');
		if($_POST){
			if($this->form_validation->run() == FALSE){

				if(validation_errors()){
					 $data['error'] = validation_errors();
				} else {
					$data['error'] = '';
				}
                                /**
                                 * project related functionality
                                 */
				$data['project_id'] = ($this->input->post('project_id')!='')?$this->input->post('project_id'):'0';
				$data['project_status'] = $this->input->post('project_status');
				$data['project_start_date'] = $this->input->post('project_start_date');
				$data['project_end_date'] = $this->input->post('project_end_date');
				$data['division_id'] = $this->input->post('division_id');
				$data['department_id'] = $this->input->post('department_id');
				$data['project_desc'] = $this->input->post('project_desc');
				$data['project_title'] = $this->input->post('project_title');


				$data['user'] = get_user_info(get_authenticateUserID());
				
				$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
				$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
		
				$data['comments'] = $this->project_model->get_project_comments($data['project_id']);
				$data['members'] = $this->project_model->get_project_members($data['project_id']);
				$data['member_lst'] = get_memberList($data['project_id']);

				$data['users_list'] = get_company_users();
				$data['files'] = get_project_files($data['project_id']);
				$data['total_history'] = $this->project_model->get_total_history_by_date($data['project_id']);
				$data['history'] = $this->project_model->get_history_by_date($data['project_id'],$limit,$offset);
				$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
				$data['section'] = $this->project_model->get_project_section($data['project_id']);


				if($data['section']!=''){
					foreach ($data['section'] as $s) {

						$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);

					}
					$data['subSection'] = $subSection;
				}

				/**
                                 *  for task related functionality
                                 */

				$task_id = '';
				$data['task_id'] = $task_id;
				$data['task_section_name'] = "";
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
						//$data['task']['general']['task_department_id'] = explode(',', $data['task']['general']['task_department_id']);
						$data['task']['general']['task_department_id'] = explode(',', get_department_by_division($data['task']['general']['task_department_id']));
					}
					if($data['task']['general']['task_skill_id']){
						$data['task']['general']['task_skill_id'] = explode(',', $data['task']['general']['task_skill_id']);
					}

					$data['users'] = get_user_list($data['task']['general']['task_division_id'],$data['task']['general']['task_department_id'],$data['task']['general']['task_staff_level_id']);
					$data['color_codes'] = get_user_color_codes($data['task']['general']['task_allocated_user_id']);
					$data['is_color_exist'] = is_user_color_exist($data['task']['general']['task_allocated_user_id']);
				} else {
					$data['users'] = get_user_list();
					$data['color_codes'] = get_user_color_codes(get_authenticateUserID());
					$data['is_color_exist'] = is_user_color_exist(get_authenticateUserID());
				}
				$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();

				// for task related functionality finishs

				$data['offset'] = $offset;
				$data['limit'] = $limit;

				$data['msg']='';

				$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
				$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
				$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
				$this->template->write_view('content_side',$theme .'/layout/project/addProject',$data,TRUE);
				$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
				$this->template->render();

			} else {

				if($this->input->post('project_id') != ''){

					$res = $this->project_model->updateProject();

					$msg = "update";

					redirect('project/editProject/'.$res);
				} else {
					$res = $this->project_model->addProject();
					$msg = "insert";
					$this->session->set_flashdata('msg', $msg);
					redirect('project/editProject/'.$res);
				}
			}
		}else{

				$data['project_id'] = '0';
				$data['project_status'] = $this->input->post('project_status');
				$data['project_start_date'] = $this->input->post('project_start_date');
				$data['project_end_date'] = $this->input->post('project_end_date');
				$data['division_id'] = $this->input->post('division_id');
				$data['department_id'] = $this->input->post('department_id');
				$data['project_desc'] = $this->input->post('project_desc');
				$data['project_title'] = $this->input->post('project_title');

				$data['comments'] = "";

				$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
				$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
				
				$data['files'] = get_project_files($data['project_id']);
				$data['total_history'] = $this->project_model->get_total_history_by_date($data['project_id']);
				$data['history'] = $this->project_model->get_history_by_date($data['project_id'],$limit,$offset);
				$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
				$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
				
				$data['msg']='';
				$data['offset'] = $offset;
				$data['limit'] = $limit;
				$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();

                                /**
                                 * render addproject page
                                 */
			$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
			$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
			$this->template->write_view('content_side',$theme .'/layout/project/addProject',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
			$this->template->render();
			
			
		}

	}
        /**
         * When user click on edit project option in projects lists this function will call .
         * This function is used for edit some information of project.It will render edit project page for add new details in db.And it will update task information too.
         * @returns create view
         */
	function editProject(){
            /**
             * get project id
             */
                $pro_id=isset($_POST['project_id'])?$_POST['project_id']:$this->input->get('pro_id');
                $ecrypted_project_id = str_replace(' ','+',$pro_id);
                
		if(isset($ecrypted_project_id)){
			$project_id = $this->encrypt->decode($ecrypted_project_id);
		}else{
			redirect ('project/listProject');
		}
		$offset=0;
                $completed = $this->config->item('completed_id');
		if (!check_user_authentication()) {
			redirect ('home');
		}
		if($project_id==''){
			redirect ('home');
		}

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data = array();
		$data['theme'] = $theme;
		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['user_id'] = 'all';
		//$data['type'] = 'all';
                                    $data['type'] = 'opt';
		$data['error'] = '';

		if($project_id!=''){$project_id = $project_id;}else{$project_id="0";}
			
		if($_POST){
			$offset=$this->input->post('offset')>0?$this->input->post('offset'):0;

		}else{
			$offset=$offset;

		}
		
		if($project_id=='0'){    
			
			$limit = 10;
			$data['project_id'] = '0';
			$data['project_status'] = $this->input->post('project_status');
			$data['project_start_date'] = $this->input->post('project_start_date');
			$data['project_end_date'] = $this->input->post('project_end_date');
			$data['division_id'] = $this->input->post('division_id');
			$data['department_id'] = $this->input->post('department_id');
			$data['project_desc'] = $this->input->post('project_desc');
			$data['project_title'] = $this->input->post('project_title');
                        $data['customer_id']=  $this->input->post('project_customer_id');

			$data['comments'] = "";
                        $data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
                        $data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
			
			$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
			$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
			$data['customers']= getCustomerList();
			$data['files'] = get_project_files($data['project_id']);
			$data['total_history'] = $this->project_model->get_total_history_by_date($data['project_id']);
			$data['history'] = $this->project_model->get_history_by_date($data['project_id'],$limit,$offset);
			$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
			
			$data['msg']='';
			$data['offset'] = $offset;
			$data['limit'] = $limit;
			$data['members'] = $this->project_model->get_project_members($project_id);
			$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
			$data['section'] = $this->project_model->get_project_section($project_id);
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			
                        $data['project_base_rate'] = 0;
                        $data['project_fixed_price'] = 0;
			if($data['section']!=''){
				foreach ($data['section'] as $s) {
					$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);
				}
				$data['subSection'] = $subSection;
			}


			//$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
			$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
			$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
			$this->template->write_view('content_side',$theme .'/layout/project/addProject',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
			$this->template->render();
			
		}else{
			$limit = 10;
			$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
			$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
			$data['comments'] = $this->project_model->get_project_comments($project_id);
			$data['members'] = $this->project_model->get_project_members($project_id);
			$data['member_lst'] = get_memberList($project_id);
			$data['files'] = get_project_files($project_id);
                        $data['customers']= getCustomerList();
			$data['total_history'] = $this->project_model->get_total_history_by_date($project_id);
			$data['history'] = $this->project_model->get_history_by_date($project_id,$limit,$offset);
			$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
			$data['section'] = $this->project_model->get_project_section($project_id);
                        
//                       
                        
			// task functionality
	
			$task_id = '';
			$data['task_id'] = $task_id;
	
			$data['task_section_name'] = "";
	
			
			$data['task'] =   array(
	            'general' => '',
	            'dependencies' => '',
	            'steps' => '',
	            'files' => '',
	            'comments' => '',
	            'history' => ''
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
				
				$data['users'] = get_user_list_project($data['task']['general']['task_project_id'],$data['task']['general']['task_division_id'],$data['task']['general']['task_department_id'],$data['task']['general']['task_skill_id'],$data['task']['general']['task_staff_level_id']);
	
	
	
			} else {
				$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));
				$data['is_color_exist'] = is_user_color_exist($this->session->userdata('user_id'));
				$data['users'] = get_user_list_project($project_id);
			}
	
			
			$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
	
			$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
	
			$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
	
			$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
			
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                        $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
	
			// task functionality finishes
	
			if($data['section']!=''){
				foreach ($data['section'] as $s) {
					$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);
				}
				$data['subSection'] = $subSection;
			}
			$one_project = $this->project_model->get_one_project($project_id);
			$data['project_id'] = $project_id;
			$data['project_status'] = $one_project->project_status;
			$data['project_title'] = $one_project->project_title;
			$data['project_start_date'] = $one_project->project_start_date;
			$data['project_end_date'] = $one_project->project_end_date;
			$data['division_id'] = $one_project->division_id;
			$data['department_id'] = $one_project->department_id;
			$data['project_desc'] = $one_project->project_desc;
                        $data['customer_id']=$one_project->project_customer_id;
			$data['users_list'] = $this->project_model->get_users_list($project_id,$data['division_id'],$data['department_id']);
			$data['tab']= $this->input->post('tab');
			$data['msg']='';
			$data['limit']=$limit;
			$data['offset']=$offset;
			$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
			$data['project_base_rate'] = $one_project->project_base_rate;
                                                $data['project_fixed_price'] = $one_project->project_fixed_price;
                                                 $data['all_report_user']=get_list_user_report_to_adminstartor();
                        
			if($this->input->is_ajax_request()){
				if($data['history']!=''){
					$res=array('html'=> $this->load->view($theme .'/layout/project/listComments_Ajax',$data,TRUE),
					'total_history'=>$data['total_history'],'status'=>'success');
				}else{
					$res=array('html'=>'','total_history'=>$data['total_history'],'status'=>'NoMore');
				}
				echo json_encode($res);die;
			}else{
				
			$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
			$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
			$this->template->write_view('content_side',$theme .'/layout/project/addProject',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
			$this->template->render();
		}	
	}

}
    /**
     * When user click on history option on add project & edit project page this function will call.It will fetch all comment of user and company in db.
     * And it will render comment list on project page.
     * @returns void
     */
	function history()
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');

		$data['site_setting_date'] = $this->config->item('company_default_format');

		$offset=0;
		if($_POST){
			$offset=$this->input->post('offset')>0?$this->input->post('offset'):0;
		}else{
			$offset=$offset;
		}
		$limit = 10;
                /**
                 * get total history by date
                 */
		$data['total_history'] = $this->project_model->get_total_history_by_date($_POST['project_id']);
		$data['history'] = $this->project_model->get_history_by_date($_POST['project_id'],$limit,$offset);
		$data["offset"] = $offset;
		$data["limit"] = $limit;
		echo $this->load->view($theme .'/layout/project/listComments_Ajax',$data,TRUE);die;
	}
        /**
         * This function is used for delete  projects from list.It will fetch delete status from post method than according to project status it will delete projects.
         * After that it will create new view page.
         * @returns void
         */
	function deleteProject(){

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data = array();

		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$data['task_status_completed_id'] = $task_status_completed_id;
		$user_id = get_authenticateUserID();
		$project_id = $this->encrypt->decode($this->input->post('project_id'));
               // echo $project_id; die();
		$delete_status = $this->input->post('delete_status');
                if($project_id ==''){
                    redirect('home');
                }
		$checkUserTask=$this->project_model->UserTask($user_id,$project_id,$task_status_completed_id);
               /** 
                 * It checks projects details for delete project
                 */
		if($checkUserTask=='0')
		{
			$data_array = array('is_deleted'=>'1');
			$this->db->where('project_id',$project_id);
			$this->db->update('project',$data_array);

			$filter = 'Open';
			$data['user'] = get_user_info(get_authenticateUserID());
			$data['projects'] = $this->project_model->get_project_list($filter);


			$this->load->view($theme.'/layout/project/listProject_Ajax',$data);
		}

		if($checkUserTask > 0 && $delete_status=='')
		{ 
			echo 'not done';die;
		}
		else
		{
			if($delete_status=='close'){
			$Close_all_task= $this->project_model->all_task_by_projectID($project_id,$task_status_completed_id);

				if($Close_all_task!='0')
				{
					$date = date('Y-m-d');
					foreach($Close_all_task as $cat){

						if($cat->task_due_date <= $date){

							$task_list = getTaskListFromProjectId($cat->task_project_id);
							if($task_list){
								foreach($task_list as $task){
									$old_task_status_name = $task['task_status_name'];
									$new_task_status_name = 'Completed';
									$history_data = array(
										'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
										'history_added_by' => get_authenticateUserID(),
										'task_id' => $task['task_id'],
										'date_added' => date('Y-m-d H:i:s')
									);
									$this->db->insert('task_history',$history_data);
								}
							}


							$data_array = array('task_status_id'=>$task_status_completed_id,'task_completion_date'=>date("Y-m-d H:i:s"));
							$this->db->where('task_project_id',$cat->task_project_id);
							$this->db->update('tasks',$data_array);

							$data_history = array(
								'project_history_title' => TASK_COMPLETED,
								'project_history_desc' => 'Task '.$cat->task_title.'Completed successfully.',
								'project_id' => $project_id,
								'history_type' =>'Task',
								'project_history_added_by' =>$this->session->userdata('user_id'),
								'project_history_added_date' => date('Y-m-d H:i:s')
							);

							$this->db->insert('project_history',$data_history);
						} else {
							$data_array = array('is_deleted'=>'1');;
							$this->db->where('task_project_id',$project_id);
							$this->db->update('tasks',$data_array);

							$data_history = array(
							'project_history_title' => TASK_DELETED,
							'project_history_desc' => 'Task '.$cat->task_title.'deleted successfully.',
							'project_id' => $cat->task_project_id,
							'history_type' =>'Task',
							'project_history_added_by' =>$this->session->userdata('user_id'),
							'project_history_added_date' => date('Y-m-d H:i:s')
						);

							$this->db->insert('project_history',$data_history);
							
						}
					}
                                        $data_array = array('is_deleted'=>'1');
			                $this->db->where('project_id',$project_id);
			                $this->db->update('project',$data_array);
                                        $filter = 'Open';
				        $data['user'] = get_user_info(get_authenticateUserID());
				        $data['projects'] = $this->project_model->get_project_list($filter);
					$this->load->view($theme.'/layout/project/listProject_Ajax',$data);
				}

			}

			if($delete_status=='remap'){
                                $select_project = $this->input->post('select_project');
                                $section_data=$this->project_model->get_project_section($select_project);
                                $assigned_task= $this->project_model->all_task_by_projectID($project_id,$task_status_completed_id);
                                
                                foreach($section_data as $sec )
                                {
                                   if($sec->section_order == 1) 
                                   {
                                       $section=$sec->section_id;
                                   }
                                }
                                if($assigned_task !='0')
				{
                                    foreach ($assigned_task as $remap_task){
					$data_array = array('task_project_id'=>$select_project,'subsection_id'=>$section);
					$this->db->where('task_project_id',$remap_task->task_project_id);
					$this->db->update('tasks',$data_array);
                                        $data_history = array(
					'project_history_title' => TASK_REALLOCATED,
					'project_history_desc' => 'Tasks re-allocated to project '.$remap_task->project_title.' successfully.',
					'project_id' => $project_id,
					'history_type' =>'Task',
					'project_history_added_by' =>$this->session->userdata('user_id'),
					'project_history_added_date' => date('Y-m-d H:i:s'),
                                        );
                                        $this->db->insert('project_history',$data_history);
                                        }
					$data_array = array('is_deleted'=>'1');
			                $this->db->where('project_id',$project_id);
			                $this->db->update('project',$data_array);
                                        $filter = 'Open';
				        $data['user'] = get_user_info(get_authenticateUserID());
				        $data['projects'] = $this->project_model->get_project_list($filter);
					$this->load->view($theme.'/layout/project/listProject_Ajax',$data);

				}

			}

                        /**
                         * delete project
                         */
			if($delete_status=='unlink')
			{

				$task_status_completed_id = get_task_status_id_by_name('Completed');
				$unlink_all_task= $this->project_model->all_task_by_projectID($project_id,$task_status_completed_id);
			
                                if($unlink_all_task!='0')
				{
                                    foreach ($unlink_all_task as $task_unlink) {
                                        $data_status = array('task_project_id'=>'0');
					$this->db->where(array('task_status_id <>'=>$task_status_completed_id,'task_project_id'=>$task_unlink->task_project_id));
					$this->db->update('tasks',$data_status);
                                        $data_array = array('project_status'=>'Complete');
                                        $this->db->where('project_id',$task_unlink->task_project_id);
					$this->db->update('project',$data_array);
                                        $data_history = array(
					'project_history_title' => TASK_UNLINKED,
					'project_history_desc' => 'Task '.$task_unlink->task_title.'unlinked successfully.',
					'project_id' => $task_unlink->task_project_id,
					'history_type' =>'Task',
					'project_history_added_by' =>$this->session->userdata('user_id'),
					'project_history_added_date' => date('Y-m-d H:i:s')
                                        );
                                        $this->db->insert('project_history',$data_history);
                                        }   
					$filter = 'Open';
					$data['user'] = get_user_info(get_authenticateUserID());
					$data['projects'] = $this->project_model->get_project_list($filter);
					$this->load->view($theme.'/layout/project/listProject_Ajax',$data);


				}
			}

			if($delete_status=='cancel')
			{
				$filter = 'Open';
				$data['user'] = get_user_info(get_authenticateUserID());
				$data['projects'] = $this->project_model->get_project_list($filter);
                                $this->load->view($theme.'/layout/project/listProject_Ajax',$data);
			}
		}

	}
        /**
         * This function will call when user select any option from drop-down list.It will filter projects list and create view page.
         * @returns createview
         */
	function filterProject()
	{

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data = array();
		$filter = $this->input->post('id');
		$data['user'] = get_user_info(get_authenticateUserID());

		$data['projects'] = $this->project_model->get_project_list_By_filter($filter);
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$this->load->view($theme.'/layout/project/listProject_Ajax',$data);

	}
	
	/**
         * When user click on edit project comment option at the same time this function will call for add new comment.
         * It will render popup for add comment after that it will insert comment in db.Then it will send mail & notification to project allocated users.
         * @returns commentview
         */
	function add_comment(){

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');

		$project_id = $_POST['project_id'];
		$project_comment = $_POST['project_comment'];

		$data = array(
			'task_comment' => $project_comment,
			'project_id' => $project_id,
			'comment_addeby' => $this->session->userdata('user_id'),
			'comment_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('task_and_project_comments',$data);
		$id = $this->db->insert_id();

		$project_info= get_project_info($project_id);

		// Email notification to all other users regarding comment

		$data['user'] = get_user_info($this->session->userdata('user_id'));
		$data['user_id'] = get_authenticateUserID();
		$data['type'] = 'all';
		$username = $data['user']->first_name." ".$data['user']->last_name;

		$query = $this->db->select("pu.*,u.first_name,u.last_name,u.email")
						  ->from("project_users pu")
						  ->join("users u","pu.user_id = u.user_id","left")
						  ->where("pu.project_id",$project_id)
						  ->where("pu.is_deleted <>","1")
						  ->where("pu.user_id <>",$this->session->userdata('user_id'))
						  ->get();

		/*Mail Send*/


		if($query->result()!=''){

			if(get_authenticateUserID() != $project_info['project_added_by']){

				$user = get_user_info($project_info['project_added_by']);

					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='project comment notification'");
					$email_temp=$email_template->row();

					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;

					$email_subject=$email_temp->subject;
					$email_message=$email_temp->message;

					$email = $user->email;

					$user_name = $user->first_name." ".$user->last_name;


					$email_to =$email;

					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{user-name}',$username,$email_message);
					$email_message=str_replace('{comment}',$_POST['project_comment'],$email_message);


					$str=$email_message;
					//echo $str;
					/** custom_helper email function **/
                                        
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

			foreach($query->result() as $row)
			{
				if($row->user_id != $project_info['project_added_by']){

					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='project comment notification'");
					$email_temp=$email_template->row();

					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;

					$email_subject=$email_temp->subject;
					$email_message=$email_temp->message;

					$email = $row->email;

					$user_name = $row->first_name." ".$row->last_name;


					$email_to =$email;

					$email_message=str_replace('{break}','<br/>',$email_message);
					$email_message=str_replace('{user_name}',$user_name,$email_message);
					$email_message=str_replace('{user-name}',$username,$email_message);
					$email_message=str_replace('{comment}',$_POST['project_comment'],$email_message);


					$str=$email_message;
					//echo $str;
					/** custom_helper email function **/
                                        
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
				// add notification userwise

				$notification_text = 'Comment "'.$project_comment.'" is added by '.$this->session->userdata('username').'.';

				$notification_data = array(
					'task_id' => '0',
					'project_id' => $project_id,
					'notification_text' => $notification_text,
					'notification_user_id' => $row->user_id,
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s")
				);

				$this->db->insert('task_notification',$notification_data);
			}
		}

		// Email notification to all other users regarding comment ends

		$project_title = getProjectName($project_id);

		$data_history = array(
			'project_history_title' => PROJECT_COMMENT_ADD,
			'project_history_desc' => '"'.$project_comment.'" added to "'.$project_title .'"',
			'project_id' => $project_id,
			'history_type' =>'Comment',
			'project_history_added_by' => $this->session->userdata('user_id'),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);



		if($id){

			$data['tab']= $this->input->post('tab');
			$this->load->library('session');

			$this->session->set_userdata(array(
                            'tab'       => $data['tab']
                    ));

			$data['comments'] = $this->project_model->get_project_comments($data['project_id']);
			$this->load->view($theme.'/layout/project/add_comments_Ajax',$data);


		}
	}
        /**
         * This function will remove comment on project when user click on delete icon.It will delete comment in db and update project history.
         * @param int $cmt_id
         * @param int $project_id
         * @returns void
         */
	function deleteComment($cmt_id,$project_id)
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$project_comment = getComment($cmt_id);
		$project_title = getProjectName($project_id);

		$this->db->delete('task_and_project_comments',array('task_comment_id'=>$cmt_id));
		$data_history = array(
			'project_history_title' => PROJECT_COMMENT_DELETE,
			'project_history_desc' => '"'.$project_comment.'" deleted from "'.$project_title .'"',
			'project_id' => $project_id,
			'history_type' =>'Comment',
			'project_history_added_by' => $this->session->userdata('user_id'),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);

		$data['comments'] = $this->project_model->get_project_comments($project_id);
		$data['user_id'] = get_authenticateUserID();
		$data['type'] = 'all';
		$this->load->view($theme.'/layout/project/add_comments_Ajax',$data);

	}

        /**
         *It will call when user click on add users option on project.It will add new user in project list and create new view page for project user.
         * @returns createview
         */
	function add_memeber()
	{
		//pr($_POST);die;
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$project_id = $_POST['project_id'];
		$data['project_id'] = $_POST['project_id'];
		$res = $this->project_model->updateUser();

		$data['members'] = $this->project_model->get_project_members($project_id);
		$data['member_lst'] = get_memberList($project_id);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
		$this->load->view($theme.'/layout/project/list_memeber_Ajax',$data);

	}
        /**
         * This method will create new view with new user list of project.
         * @returns createview
         */
	function memberView()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$project_id = $_POST['project_id'];
		$data['project_id'] = $_POST['project_id'];
		
		$data['members'] = $this->project_model->get_project_members($project_id);
		$data['member_lst'] = get_memberList($project_id);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
                $this->load->view($theme.'/layout/project/memberView',$data);

	}
        /**
         * It will create user list on specific project.
         * @returns create userlist
         */
	function memberlist()
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$project_id = $_POST['project_id'];
		$data['project_id'] = $_POST['project_id'];
                $data['customer_id'] = get_project_customer($project_id);
		$data['member_lst'] = get_memberList($project_id);
		$this->load->view($theme.'/layout/project/memberlist_Ajax',$data);
	}
        /**
         * This function will returns user list .
         * @param int $project_id
         * @returns JsonObject
         */
	function get_users_list($project_id)
	{
		$keyword=$_GET["term"];

		$proj_info = get_project_info($project_id);
		$division_id = $proj_info['division_id'];
		$department_id = $proj_info['department_id'];
		$users= $this->project_model->get_users_list_By_name($keyword,$division_id,$department_id);

		$arr = array();
        if($users)
        {
            foreach($users as $key=>$val){
                $arr[] = array("id"=>$val->user_id,"label"=>$val->first_name." ".$val->last_name,"value"=>$val->first_name." ".$val->last_name);
            }
        }
        echo json_encode($arr);die;


	}

	/*function addUser(){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data = array();

		$data['site_setting_date'] = $this->config->item('company_default_format');

		$data['tab']= $this->input->post('tab');

			$this->load->library('session');
			$this->session->set_userdata(array(
                            'tab'       => $data['tab']
                    ));

		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_id','Users','required');

		if($this->form_validation->run() == FALSE){

			if(validation_errors()){
				$data['error'] = validation_errors();
			} else {
				$data['error'] = '';
			}

			$data['project_id'] = $this->input->post('project_id');
			$data['user_id'] = $this->input->post('user_id');

			$data['user'] = get_user_info(get_authenticateUserID());
			$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
			$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
			$data['comments'] = $this->project_model->get_project_comments($data['project_id']);
			$data['members'] = $this->project_model->get_project_members($data['project_id']);
			$data['member_lst'] = get_memberList($data['project_id']);

			$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
			$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
			$this->template->write_view('content_side',$theme .'/layout/project/addProject',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
			$this->template->render();

			} else {
				$res = $this->project_model->updateUser();
				redirect('project/editProject/'.$res);

		}

	}*/
        /**
         * This function is used for delete user from specific on projects.
         * @param type $project_users_id
         * @param type $user_id
         * @param type $project_id
         * @returns create listmember
         */
	function deleteUser($project_users_id,$user_id,$project_id)
	{

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$data['tab']= "tab_2";

			$this->load->library('session');
			$this->session->set_userdata(array(
                            'tab'       => $data['tab']
                    ));
		$checkUserTask=$this->project_model->UserTask($user_id,$project_id,$task_status_completed_id);
		if($checkUserTask > 0)
		{
			echo 'not done';die;
		}
		else
		{
			$user_detail = get_user_info($user_id);
			$this->db->delete('project_users',array('project_users_id'=>$project_users_id));
			$data_history = array(
			'project_history_title' => USER_DELETED_PROJECT,
			'project_history_desc' => 'User '.$user_detail->first_name."".$user_detail->last_name.' deleted from project .',
			'project_id' => $project_id,
			'history_type' =>'User',
			'project_history_added_by' => $user_id,
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);
		$data['project_id'] = $project_id;
		$data['members'] = $this->project_model->get_project_members($project_id);
		$data['member_lst'] = get_memberList($project_id);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
		$this->load->view($theme.'/layout/project/list_memeber_Ajax',$data);
		}

	}
        /**
         * This function will feth project file in db and render project file view.
         * @returns file view
         */
	function project_files(){

		if(!check_user_authentication()){
			redirect('home');
		}

		$data['tab']= $this->input->post('tab');

			$this->load->library('session');
			$this->session->set_userdata(array(
                            'tab'       => $data['tab']
                    ));

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST){
			$id = $this->project_model->add_project_files();
		}
		$data['project_id'] = $_POST['project_id'];
		$data['project']['files'] = get_project_files($_POST['project_id']);
		$data['files'] = get_project_files($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		$this->load->view($theme.'/layout/project/project_file_Ajax',$data);
	}
        /**
         * It will use for upload new file in db on project file option.
         * @returns fileview
         */
	function uplaodLinkFiles(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$data['tab']= $this->input->post('tab');

		$this->load->library('session');
		$this->session->set_userdata(array(
                        'tab'       => $data['tab']
                ));

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST){
			$id = $this->project_model->add_project_LinkFiles();
		}
		$data['project_id'] = $_POST['project_id'];
		$data['project']['files'] = get_project_files($_POST['project_id']);
		$data['files'] = get_project_files($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		$this->load->view($theme.'/layout/project/project_file_Ajax',$data);
	}
        /**
         * It will delete files from list of file on project.
         * @returns fileview
         */
	function delete_project_file(){

		if(!check_user_authentication()){
			redirect('home');
		}

		$data['tab']= "tab_3";

			$this->load->library('session');
			$this->session->set_userdata(array(
                            'tab'       => $data['tab']
                    ));

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST){


			$bucket = $this->config->item('bucket_name');

			$project_file_name = $this->project_model->get_project_file_detail($_POST['task_file_id']);
			if($project_file_name){
			if($project_file_name->task_file_name){
				$delete_image_name = "upload/task_project_files/".$project_file_name->task_file_name;
				if($this->s3->getObjectInfo($bucket,$delete_image_name)){
					$this->s3->deleteObject($bucket,$delete_image_name);
				}

				$this->db->delete('task_and_project_files',array('task_file_id'=>$_POST['task_file_id']));
				

				$data_history = array(
					'project_history_title' => FILE_DELETED_PROJECT,
					'project_history_desc' => 'File  '.$project_file_name->task_file_name.' deleted successfully.',
					'project_id' => $this->input->post('project_id'),
					'history_type' =>'File',
					'project_history_added_by' => $this->session->userdata('user_id'),
					'project_history_added_date' => date('Y-m-d H:i:s')
				);

				$this->db->insert('project_history',$data_history);
			}
			}
		}
		$data['project_id'] = $_POST['project_id'];
		$data['files'] = get_project_files($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		$this->load->view($theme.'/layout/project/project_file_Ajax',$data);

	}
        /**
         * It will replace file with new file and create new view.
         * @returns fileview
         */
	function files_replace()
	{
		if(!check_user_authentication()){
			redirect('home');
		}

//		$data['tab']= $this->input->post('tab');
//
//			$this->load->library('session');
//			$this->session->set_userdata(array(
//                            'tab'       => $data['tab']
//                    ));

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST['rep_fil']){
			$id = $this->project_model->replace_project_files();
		}
                $data['project_id'] = $_POST['project_id'];
		$data['project']['files'] = get_project_files($_POST['project_id']);
		$data['files'] = get_project_files($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		$this->load->view($theme.'/layout/project/project_file_Ajax',$data);
	}
	/**
         * It will create new file view with uploaded files .
         * @returns fileview
         */
	function uplaodLinkFilesReplace(){
		if(!check_user_authentication()){
			redirect('home');
		}

		$data['tab']= $this->input->post('tab');

		$this->load->library('session');
		$this->session->set_userdata(array(
                        'tab'       => $data['tab']
                ));

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		if($_POST['rep_fil']){
			$id = $this->project_model->replace_filesUploadLink();
		}
		$data['project_id'] = $_POST['project_id'];
		$data['project']['files'] = get_project_files($_POST['project_id']);
		$data['files'] = get_project_files($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		$this->load->view($theme.'/layout/project/project_file_Ajax',$data);
	}
        /**
         * When user add new project have a status option when user select completed option at the same time this function will call for check project action.
         * It will create project view .
         * @returns projectview
         */
	function complete_task()
	{
		$project_id = $_POST['project_id'];
		$task_status = $_POST['task_status'];
		$data['user_id'] = get_authenticateUserID();
		$data['type'] = 'all';

		if($task_status=='Unlink')
		{
			
			$task_status_completed_id = get_task_status_id_by_name('Completed');
			$unlink_all_task= $this->project_model->all_task_by_projectID($project_id,$task_status_completed_id);

			if($unlink_all_task!='0')
			{
				$data_status = array('is_deleted'=>'1');
				$this->db->where(array('task_status_id <>'=>$task_status_completed_id,'task_project_id'=>$project_id));
				$this->db->update('tasks',$data_status);

				$data_array = array('project_status'=>'Complete');

				$this->db->where('project_id',$project_id);
				$this->db->update('project',$data_array);

				$data_history = array(
                                    'project_history_title' => TASK_COMPLETED,
                                    'project_id' => $project_id,
                                    'project_history_desc' => 'Task '.$unlink_all_task->task_title.'unlinked successfully.',
                                    'history_type' =>'Task',
                                    'project_history_added_by' =>$this->session->userdata('user_id'),
                                    'project_history_added_date' => date('Y-m-d H:i:s')
                                );

				$this->db->insert('project_history',$data_history);
				echo "done";die;
//				$res = $project_id;
//				redirect('project/editProject/'.$res);

			}
			else {

				$data_array = array('project_status'=>'Complete');

				$this->db->where('project_id',$project_id);
				$this->db->update('project',$data_array);
				echo "not done";die;
			}
		}

		if($task_status=='Close')
		{
			
			$task_status_completed_id = get_task_status_id_by_name('Completed');

			$Close_all_task= $this->project_model->all_task_by_projectID($project_id,$task_status_completed_id);

			if($Close_all_task!='0')
			{

				$task_list = getTaskListFromProjectId($project_id);
				if($task_list){
					foreach($task_list as $task){
						$old_task_status_name = $task['task_status_name'];
						$new_task_status_name = 'Completed';
						$history_data = array(
							'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
							'history_added_by' => get_authenticateUserID(),
							'task_id' => $task['task_id'],
							'date_added' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_history',$history_data);
					}
				}


				$data_array = array('t.task_status_id'=>$task_status_completed_id,"t.task_completion_date"=>date("Y-m-d H:i:s"));
				$this->db->join('project p','t.task_project_id = p.project_id','left');
                                $this->db->where('t.task_project_id',$project_id);
				$this->db->update('tasks t',$data_array);

				$data_array = array('project_status'=>'Complete');

				$this->db->where('project_id',$project_id);
				$this->db->update('project',$data_array);

				$data_history = array(
                                    'project_history_title' => TASK_COMPLETED,
                                    'project_history_desc' => 'Task '.$Close_all_task->task_title.'unlinked successfully.',
                                    'project_id' => $project_id,
                                    'history_type' =>'Task',
                                    'project_history_added_by' =>$this->session->userdata('user_id'),
                                    'project_history_added_date' => date('Y-m-d H:i:s')
                                );

				$this->db->insert('project_history',$data_history);
				echo "done";die;
//				$res = $project_id;
//				redirect('project/editProject/'.$res);

			}else {

				$data_array = array('project_status'=>'Complete');

				$this->db->where('project_id',$project_id);
				$this->db->update('project',$data_array);
				echo "not done";die;
			}
		}
		if($task_status=='Keep')
		{
			
			echo "done";die;
//			$res = $project_id;
//			redirect('project/editProject/'.$res);
		}
	}
	/**
         * This function will return project list in db.
         * @returns string
         */
	function getProjects()
    {
    	$project_id = $this->input->post('project_id');
		//echo $project_id;
        $CI =& get_instance();
        $str='<option value="">Select</option>';
        $q=$CI->db->select('project_id,project_title')->get_where('project',array('project_id <> '=>$project_id,'project_added_by'=>$CI->session->userdata('user_id'),'project_status'=>'Open','is_deleted'=>'0'));
		//echo $CI->db->last_query();die;

        if($q->num_rows()>0)
        {
            $str.='';
            foreach ($q->result() as $proj){

                $str.='<option value="'.$proj->project_id.'" >'.ucwords($proj->project_title).'</option>';
            }
            $str.='';

        }else{
        	$str='<option value="">No Project found</option>';
        }
        echo $str;
    }
        /**
         * When user click on section name of project task at the time this method call.It will update section name in db.
         * @returns string
         */
	function update_sectionName()
	{
		$section_id = $this->input->post('section_id');
		$section_name = $this->input->post('section_name');
		$tab= $this->input->post('tab');
		$type= $this->input->post('type');
		$project_id= $this->input->post('project_id');
                                   $icon_class=$this->input->post('iconclass');
		$data['user_id'] = get_authenticateUserID();
		$data['type'] = $_POST['type'];

		$up_array = array('section_name'=>$section_name);
		$this->db->where('section_id',$section_id);
		$this->db->update('project_section',$up_array);
		if($type=='section'){
                                     $str='<span class="expand_sections" style="float:left"><i class="'.$icon_class.'"></i></span>';
		$str .= '<a onclick="changeSecName('.$section_id.');" >'.$section_name.'</a>';
		$str.='<a onclick="delete_section('.$section_id.');" href="javascript://"><i class="stripicon delete_icon"></i></a>';
		echo $str;die;
		}
		if($type=='subsection'){
                                                       $str='<span class="expand_sections" style="float:left"><i class="'.$icon_class.'"></i></span>';
			$str .= '<a  onclick="changeSubSecName('.$section_id.');" >'.$section_name.'</a>';
			$str.= '<a onclick="delete_subsection('.$section_id.');" href="javascript://"><i class="stripicon delete_icon"></i></a>';
		echo $str;die;
		}

	}
        /**
         * This method is called for add new section on task on project page.It will insert new section in db and create view page.
         * @returns viewpage
         */
	function createSection()
	{
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		$data['site_setting_date'] = $this->config->item('company_default_format');

		$project_id = $this->input->post('project_id');
		$user_id = get_authenticateUserID();
		$data['user_id'] = 'all';
		$data['project_id'] = $project_id;
		$data['section_name']= $this->input->post('section_name');
		$data['user_id'] = get_authenticateUserID();
		$data['type'] = 'all';
//		$chk = checkSectionName($data['project_id'],$data['section_name']);
//		//echo $chk;
//		if($chk > 1){
//			echo "fail";die;
//		}else{

//		$data['tab']= $this->input->post('tab');
//
//			$this->load->library('session');
//			$this->session->set_userdata(array(
//                            'tab'       => $data['tab']
//                    ));

				$data_section = array(
					'section_name' =>$data['section_name'],
					'main_section' =>'0',    //main section
					'project_id' => $project_id,
					'added_by' => get_authenticateUserID(),
					'added_date' => date('Y-m-d')
				);
				$this->db->insert('project_section',$data_section);
				$section_id = $this->db->insert_id();

				$section_order = array(

				'section_order'=>get_section_order_by_project($this->input->post('project_id'),$section_id,'0') ,
				'subsection_order'=>'0' ,

				);

				$this->db->where('section_id',$section_id);
				$this->db->update('project_section',$section_order);

			$data['task_id'] = $this->input->post('task_id');
			$data['task_subsection_id'] = $this->input->post('task_subsection_id');
			$data['task_section_id'] = $this->input->post('task_section_id');
			$data['task_project_id'] = $this->input->post('project_id');
			$data['task']['general']['task_title'] = $this->input->post('task_title');
			$data['task']['general']['task_description'] = $this->input->post('task_description');
			$data['task']['general']['is_personal'] = $this->input->post('is_personal');
			$data['task']['general']['task_priority'] = $this->input->post('task_priority');
			$data['task']['general']['locked_due_date'] = $this->input->post('locked_due_date');
			$data['task']['general']['task_owner_id'] = $this->input->post('task_owner_id');
			$data['task']['general']['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');

			$data['task']['general']['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
			$data['task']['general']['task_time_spent_min'] = $this->input->post('task_time_spent_min');
			$data['task']['general']['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
			$data['task']['general']['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');

			$data['task']['general']['task_due_date'] = $this->input->post('task_due_date');
			$data['task']['general']['task_category_id'] = $this->input->post('task_category_id');
			$data['task']['general']['task_sub_category_id'] = $this->input->post('task_sub_category_id');
			$data['task']['general']['task_color_code'] = $this->input->post('task_color_code');
			$data['task']['general']['task_staff_level_id'] = $this->input->post('task_staff_level_id');
			$data['task']['general']['task_division_id'] = $this->input->post('task_division_id');
			$data['task']['general']['task_department_id'] = $this->input->post('task_department_id');
			$data['task']['general']['task_skill_id'] =$this->input->post('task_skill_id');
			$data['task']['general']['task_status_id'] = $this->input->post('task_status_id');
			$data['task']['general']['master_task_id'] = $this->input->post('master_task_id');
			$data['task']['general']['kanban_order'] = $this->input->post('kanban_order');
			$data['task']['general']['calender_order'] = $this->input->post('calender_order');

		// for edit task values

		$data['section'] = $this->project_model->get_project_section($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$data['task_status_completed_id'] = $task_status_completed_id;
                $data['members'] = $this->project_model->get_project_members($project_id);
                $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 

		if($data['section']!=''){
			foreach ($data['section'] as $s) {

				$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);

			}
			$data['subSection'] = $subSection;
		}

		$this->load->view($theme.'/layout/project/task_result_Ajax',$data);
		//}
	}
        /**
         * It will add new sub-section on section of task.It will insert new subsection information in db.And it will render subsection view.
         * @returns view
         */
	function createSubSection()
	{
		//echo "<pre>";print_r($_POST);die;
		if(!check_user_authentication()){
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');

		$data['site_setting_date'] = $this->config->item('company_default_format');

		$project_id = $this->input->post('project_id');
		$section_id = $this->input->post('section_id');
		$data['project_id'] = $project_id;
		$data['user_id'] = 'all';
		//$data['filter'] = $_POST['filter'];
		$data['type'] = 'all';

		$data['section_name']= $this->input->post('section_name');
		//$chk = checkSectionName($data['project_id'],$data['section_name'],$section_id);

//		if($chk > 1){
//			echo "fail";die;
//		}else{

//		$data['tab']= $this->input->post('tab');
//
//			$this->load->library('session');
//			$this->session->set_userdata(array(
//                            'tab'       => $data['tab']
//                    ));


						$data_section = array(
							'section_name' =>$data['section_name'],
							'main_section' =>$section_id,    //main section
							'project_id' => $project_id,
							'added_by' => get_authenticateUserID(),
							'added_date' => date('Y-m-d')
						);
						$this->db->insert('project_section',$data_section);

						$subsection_id = $this->db->insert_id();

                                                $subsection_order = array(
                                                     'section_order'=>get_section_order_by_section($this->input->post('project_id'),$section_id) ,
                                                     'subsection_order'=>get_sub_section_order_by_project($this->input->post('project_id'),$section_id,$subsection_id) ,

                                                );

				$this->db->where('section_id',$subsection_id);
				$this->db->update('project_section',$subsection_order);

				// for edit task values

			$data['task_id'] = $this->input->post('task_id');
			$data['task_subsection_id'] = $this->input->post('task_subsection_id');
			$data['task_section_id'] = $this->input->post('task_section_id');
			$data['task_project_id'] = $this->input->post('project_id');
			$data['task']['general']['task_title'] = $this->input->post('task_title');
			$data['task']['general']['task_description'] = $this->input->post('task_description');
			$data['task']['general']['is_personal'] = $this->input->post('is_personal');
			$data['task']['general']['task_priority'] = $this->input->post('task_priority');
			$data['task']['general']['locked_due_date'] = $this->input->post('locked_due_date');
			$data['task']['general']['task_owner_id'] = $this->input->post('task_owner_id');
			$data['task']['general']['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');

			$data['task']['general']['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
			$data['task']['general']['task_time_spent_min'] = $this->input->post('task_time_spent_min');
			$data['task']['general']['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
			$data['task']['general']['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');

			$data['task']['general']['task_due_date'] = $this->input->post('task_due_date');
			$data['task']['general']['task_category_id'] = $this->input->post('task_category_id');
			$data['task']['general']['task_sub_category_id'] = $this->input->post('task_sub_category_id');
			$data['task']['general']['task_color_code'] = $this->input->post('task_color_code');
			$data['task']['general']['task_staff_level_id'] = $this->input->post('task_staff_level_id');
			$data['task']['general']['task_division_id'] = $this->input->post('task_division_id');
			$data['task']['general']['task_department_id'] = $this->input->post('task_department_id');
			$data['task']['general']['task_skill_id'] =$this->input->post('task_skill_id');
			$data['task']['general']['task_status_id'] = $this->input->post('task_status_id');
			$data['task']['general']['master_task_id'] = $this->input->post('master_task_id');
			$data['task']['general']['kanban_order'] = $this->input->post('kanban_order');
			$data['task']['general']['calender_order'] = $this->input->post('calender_order');

		// for edit task values



		$data['section'] = $this->project_model->get_project_section($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);

		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$data['task_status_completed_id'] = $task_status_completed_id;
                $data['members'] = $this->project_model->get_project_members($project_id);
                $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 

		if($data['section']!=''){
			foreach ($data['section'] as $s) {

				$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);

			}
			$data['subSection'] = $subSection;
		}

		echo $this->load->view($theme.'/layout/project/task_result_Ajax',$data,TRUE);die;

		//}

	}
        /**
         * This method will create task list when user click on task option on project page.
         * @returns view
         */
	function task_result()
	{
		//print_r($_POST);
		$theme = getThemeName();
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$user_id = $this->input->post('user_id');
		$task_type = $this->input->post('type');
		$project_id = $this->input->post('project_id');
		$from = $this->input->post('from');
		$data['project_id'] = $project_id;
		$data['user_id'] = $user_id;
		$data['type'] = $task_type;
		$data['from'] = $from;
		$data['task_id']='';
		//echo $user_id."+++++";

		// for edit task values

			if($from !='from_project'){
				$data['task_id'] = $this->input->post('task_id');
				$data['task_subsection_id'] = $this->input->post('task_subsection_id');
				$data['task_section_id'] = $this->input->post('task_section_id');
				$data['task_project_id'] = $this->input->post('project_id');
				$task['general']['task_id']= $this->input->post('task_id');
				$data['task']['general']['task_title'] = $this->input->post('task_title');
				$data['task']['general']['task_description'] = $this->input->post('task_description');
				$data['task']['general']['is_personal'] = $this->input->post('is_personal');
				$data['task']['general']['task_priority'] = $this->input->post('task_priority');
				$data['task']['general']['locked_due_date'] = $this->input->post('locked_due_date');
				$data['task']['general']['task_owner_id'] = $this->input->post('task_owner_id');
				$data['task']['general']['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');

				$data['task']['general']['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
				$data['task']['general']['task_time_spent_min'] = $this->input->post('task_time_spent_min');
				$data['task']['general']['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
				$data['task']['general']['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');

				$data['task']['general']['task_due_date'] = $this->input->post('task_due_date');
				$data['task']['general']['task_category_id'] = $this->input->post('task_category_id');
				$data['task']['general']['task_sub_category_id'] = $this->input->post('task_sub_category_id');
				$data['task']['general']['task_color_code'] = $this->input->post('task_color_code');
				$data['task']['general']['task_staff_level_id'] = $this->input->post('task_staff_level_id');
				$data['task']['general']['task_division_id'] = $this->input->post('task_division_id');
				$data['task']['general']['task_department_id'] = $this->input->post('task_department_id');
				$data['task']['general']['task_skill_id'] =$this->input->post('task_skill_id');
				$data['task']['general']['task_status_id'] = $this->input->post('task_status_id');
				$data['task']['general']['master_task_id'] = $this->input->post('master_task_id');
				$data['task']['general']['kanban_order'] = $this->input->post('kanban_order');
				$data['task']['general']['calender_order'] = $this->input->post('calender_order');
				$data['task']['general']['task_scheduled_date'] = $this->input->post('task_scheduled_date');
			}
		// for edit task values
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
		$data['section'] = $this->project_model->get_project_section_byfilter($project_id,$user_id);
                                $data['members'] = $this->project_model->get_project_members($project_id);
                                $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
		$data['color_codes'] = get_user_color_codes($this->session->userdata('user_id'));
                                $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                                $data['all_report_user']=get_list_user_report_to_adminstartor();


		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$data['task_status_completed_id'] = $task_status_completed_id;


		if($data['section']!=''){
			foreach ($data['section'] as $s) {

				$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);

			}
			$data['subSection'] = $subSection;
		}
		//echo $data['type']."=====".$data['user_id'];
		$this->load->view($theme.'/layout/project/task_result_Ajax',$data);

	}
        /**
         * It will count task in db according to task type.
         * @returns view
         */
	function task_counter()
	{
		$theme = getThemeName();
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$user_id = $this->input->post('user_id');
		$task_type = $this->input->post('type');
		$project_id = $this->input->post('project_id');
		$from = $this->input->post('from');
		$data['project_id'] = $project_id;
		$data['user_id'] = $user_id;
		$data['type'] = $task_type;
		$data['from'] = $from;
		$data['task_id']='';
		
		$task_status_completed_id = get_task_status_id_by_name('Completed');

		$data['tot_task'] = get_total_task($project_id,$user_id,$task_status_completed_id);
		$data['my_task'] = get_my_task($project_id,$user_id,$task_status_completed_id);
		$data['tot_upcoming_task'] = get_total_upcoming_task($project_id,$user_id,$task_status_completed_id);
		$data['my_upcoming_task'] = get_my_upcoming_task($project_id,$user_id,$task_status_completed_id);
		$data['tot_today_task'] = get_tot_today_task($project_id,$user_id,$task_status_completed_id);
		$data['my_today_task'] = get_my_today_task($project_id,$user_id,$task_status_completed_id);
		$data['tot_overdue_task'] = get_tot_overdue_task($project_id,$user_id,$task_status_completed_id);
		$data['my_overdue_task'] = get_my_overdue_task($project_id,$user_id,$task_status_completed_id);

		$this->load->view($theme.'/layout/project/task_counter',$data);
	}

	/**
         * This function will set departments in drop-down of add project.
         * @param int $division_id
         * @param int $department_id
         * @returns string
         */
	function getDepartment($division_id,$department_id='')
	{
		$department=getDepartmentByDivision($division_id);
		$str='<option value=""> -- Select Department -- </option>';
		if($department!=''){
			foreach ($department as $s) {
				if($s->department_id == $department_id)
                        {
                            $ssel = "selected = 'selected'";
                        }else{
                        	$ssel = "";
                        }

				$str.='<option value="'.$s->department_id.'" '.$ssel.' >'.$s->department_title.'</option>';
			}
		}
		echo $str;die;
	}
        /**
         * It will delete subsection of task on project in db.
         * @param  $sub_id
         * @param  $project_id
         * @returns string
         */
	function deleteSubSection($sub_id,$project_id)
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$this->db->delete('project_section',array('section_id'=>$sub_id));

		echo "success";
		die;


	}
            /**
             * It  will delete section of task on project page in db.
             * @param type $s_id
             * @param type $project_id
             * @returns string
             */
	function deleteSection($s_id,$project_id)
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$this->db->delete('project_section',array('section_id'=>$s_id));
		$this->db->delete('project_section',array('main_section'=>$s_id));
		echo "success";
		die;

	}
	/**
         * This function is no longer used.
         */
	function ajax_reload()
	{
		$theme = getThemeName();
		$data['error'] = '';
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$user_id = $this->input->post('user_id');
		$project_id = $this->input->post('project_id');
		$from = $this->input->post('from');
		$data['project_id'] = $project_id;

		if($from == 'from_project'){
			$data['user_id'] = 'all';
		}else{
			$data['user_id'] = $user_id;
		}

			$data['from'] = $from;

			$data['task_id'] = $this->input->post('task_id');
			$data['task_subsection_id'] = $this->input->post('task_subsection_id');
			$data['task_section_id'] = $this->input->post('task_section_id');
			$data['task_project_id'] = $this->input->post('project_id');
			$data['task']['general']['task_title'] = $this->input->post('task_title');
			$data['task']['general']['task_description'] = trim(str_replace(array("\n", "\r"), ' ',$this->input->post('task_description')));

			//$data['task']['general']['task_description'] =$this->input->post('task_description');
			$data['task']['general']['is_personal'] = $this->input->post('is_personal');
			$data['task']['general']['task_priority'] = $this->input->post('task_priority');
			$data['task']['general']['locked_due_date'] = $this->input->post('locked_due_date');
			$data['task']['general']['task_owner_id'] = $this->input->post('task_owner_id');
			$data['task']['general']['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');

			$data['task']['general']['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
			$data['task']['general']['task_time_spent_min'] = $this->input->post('task_time_spent_min');
			$data['task']['general']['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
			$data['task']['general']['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');

			$data['task']['general']['task_due_date'] = $this->input->post('task_due_date');
			$data['task']['general']['task_category_id'] = $this->input->post('task_category_id');
			$data['task']['general']['task_sub_category_id'] = $this->input->post('task_sub_category_id');
			$data['task']['general']['task_color_code'] = $this->input->post('task_color_code');
			$data['task']['general']['task_staff_level_id'] = $this->input->post('task_staff_level_id');
			$data['task']['general']['task_division_id'] = $this->input->post('task_division_id');
			$data['task']['general']['task_department_id'] = $this->input->post('task_department_id');
			$data['task']['general']['task_skill_id'] =$this->input->post('task_skill_id');
			$data['task']['general']['task_status_id'] = $this->input->post('task_status_id');
			$data['task']['general']['master_task_id'] = $this->input->post('master_task_id');
			$data['task']['general']['kanban_order'] = $this->input->post('kanban_order');
			$data['task']['general']['calender_order'] = $this->input->post('calender_order');

		// for edit task values
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
		$data['section'] = $this->project_model->get_project_section_byfilter($project_id,$user_id);

		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$data['task_status_completed_id'] = $task_status_completed_id;

		if($data['section']!=''){
			foreach ($data['section'] as $s) {

				$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);

			}
			$data['subSection'] = $subSection;
		}

		$this->load->view($theme.'/layout/project/task_result_Ajax',$data);
	}
        /**
         * This function will update task order in db.When user move task by drag-drop at the same time this function is called for set order of task in db. 
         * @returns void
         */
	function setOrder(){

		//echo "<pre>";print_r($_POST);die;
		$theme = getThemeName();
		$order = $_POST['order'];
		$project_id = $_POST['project_id'];
		$scope_id = $_POST['scope_id'];
		$post_status = $_POST['status'];
		$status_arr = explode('_', $post_status);

		$scope_arr = explode('_', $scope_id);

		if(isset($_POST['task_data']) && $_POST['task_data']!=''){
			$post_data = json_decode($_POST['task_data'],true);
		} else {
			$post_data = '';
		}

		//$post_data =
		//echo pr($post_data);die;
		//echo $scope_arr[2];die;
		//print_r($scope_arr);die;
		//if($scope_arr[1]=='child'){
		/*
		$taskdetailByid = json_encode(gettaskbyid($scope_arr[2]));

				if(isset($taskdetailByid) && $taskdetailByid!=''){
					$post_data = json_decode($taskdetailByid,true);
				} else {
					$post_data = '';
				}*/


		//echo "<pre>";print_r($post_data);die;
		//}

		//echo "end";die;
		$step1 = explode('&', $order);



		//echo pr($step1);die;

		$is_owner=is_user_project_owner(get_authenticateUserID(),$project_id);

		if($post_data){
		$chk_exist = chk_task_exists($scope_arr[2]);
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

				$inserted_id = $scope_arr[2];
			}
		$step1 = str_replace('_child', '', $step1);
		}
		//echo $inserted_id;die;
		if($status_arr[0] =='task')
		{
			$task = array();
			$task = str_replace('Stab[]=', '', $step1);
			$i=1;
			foreach ($task as $t)
			{
				 $task_order = get_task_original_order($t);
				 $data = array('section_order'=>$i);
				 	$this->db->where(array('section_id'=>$t,'main_section'=>'0','project_id'=>$project_id));
					$this->db->update('project_section',$data);
				$i++;
			}

		}
		if($status_arr[0] =='panel-body' || $status_arr[0] =='panel-body1')
		{
			$task1 = array();
                       
                        if(is_numeric($status_arr[1])){
                          $task1 = str_replace('Subtab[]=','', $step1);  
                        }else{
                             $task1 = str_replace('Stab[]=','', $step1); 
                        }
			$i=1;
                        $data=array();
			foreach ($task1 as $t)
			{

				if(is_numeric($t)){

				 $task_order = get_task_original_order($t);
                                 
                                  if(is_numeric($status_arr[1])){
                                          $data = array('subsection_order'=>$i);
                                          $this->db->where(array('section_id'=>$t,'main_section <>'=>'0','project_id'=>$project_id));
                                          }else{
                                              $data = array('section_order'=>$i);
                                              $this->db->where(array('section_id'=>$t,'main_section'=>'0','project_id'=>$project_id));
                                             
                                          }
				 	
					$this->db->update('project_section',$data);
                                       $this->db->flush_cache();
				$i++;
				}
			}
                        $task2 = array();
			$task2 = str_replace('task_tasksort[]=', '', $step1);

			$j=1;
			foreach ($task2 as $t2)
			{
				if(is_numeric($t2)){

				$task_order = get_task_original_order($t2);
				$data = array('task_order'=>$j,'subsection_id'=>$status_arr[1]);
				 	$this->db->where(array('task_id'=>$t2,'task_project_id'=>$project_id));
					$this->db->update('tasks',$data);

					//echo "set -->".$this->db->last_query();

				$j++;
				}
			}

		}

		if($status_arr[0] =='taskmove')
		{
			$subsection_id = $status_arr[1];
			$section_id = $status_arr[2];

			$task = array();
			$task = str_replace('task_tasksort[]=', '', $step1);

			$i=1;
			foreach ($task as $t)
			{
				 $data = array('task_order'=>$i,'section_id'=>$section_id,'subsection_id'=>$subsection_id);
				 	$this->db->where(array('task_id'=>$t,'task_project_id'=>$project_id));
					$this->db->update('tasks',$data);
				$i++;
			}

		}
	}
        /**
         * This function will update task scope in db.When user move task by drag-drop at the same time this function is called for set scope of task in db. 
         * @returns void
         */
	function UpdateScope(){

		//echo "<pre>";print_r($_POST);die;

		$theme = getThemeName();

		$post_scope_id = $_POST['scope_id'];
		$post_status = $_POST['status'];
		$post_order = $_POST['order'];
		$project_id = $_POST['project_id'];

		$scope_id = $post_scope_id;
		$scope_val = explode('_', $post_scope_id);
		$status_arr = explode('_', $post_status);

		if(isset($_POST['task_data']) && $_POST['task_data']!=''){
			$post_data = json_decode($_POST['task_data'],true);
		} else {
			$post_data = '';
		}

		$step1 = explode('&', $post_order);

		$is_owner=is_user_project_owner(get_authenticateUserID(),$project_id);

		$chk_exist = chk_task_exists($scope_val[2]);


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

				$inserted_id = $scope_val[2];
			}

		$step1 = str_replace('_child', '', $step1);

		if($scope_val[0]=='Subtab')
		{
			if($status_arr[0] =='panel-body' || $status_arr[0] =='panel-body1')
			{
				$task = array();
				$task1 = str_replace('Subtab[]=', '', $step1);
				$section_order = get_section_order($status_arr[1]);
				$i=1;
				foreach ($task1 as $t)
				{
					if(is_numeric($t))
					{

						 $data = array('subsection_order'=>$i,'main_section'=>$status_arr[1],'section_order'=>$section_order);
						 	$this->db->where(array('section_id'=>$t,'project_id'=>$project_id));
							$this->db->update('project_section',$data);

						$data_task = array('section_id'=>$status_arr[1]);
							$this->db->where(array('subsection_id'=>$t,'task_project_id'=>$project_id));
							$this->db->update('tasks',$data_task);

						$i++;
					}
				}
			}

		}

		if($scope_val[0]=='task')
		{

			if($status_arr[0] =='panel-body' || $status_arr[0] =='panel-body1')
			{
				$task = array();
				$task1 = str_replace('task_tasksort[]=', '', $step1);

				$i=1;
				foreach ($task1 as $t)
				{
					if(is_numeric($t))
					{
						 $section_order = get_section_order($status_arr[1]);
						 $data = array('task_order'=>$i,'subsection_id'=>$status_arr[1],'section_id'=>'0');
						 	$this->db->where(array('task_id'=>$t,'task_project_id'=>$project_id));
							$this->db->update('tasks',$data);
							//echo"update -->". $this->db->last_query();
						$i++;
					}
				}

				$task2 = str_replace('Subtab[]=', '', $step1);
				$section_order = get_section_order($status_arr[1]);
				$j=1;
				foreach ($task2 as $t2)
				{
					if(is_numeric($t2))
					{
						 $data = array('subsection_order'=>$j,'main_section'=>$status_arr[1],'section_order'=>$section_order);
						 	$this->db->where(array('section_id'=>$t2,'project_id'=>$project_id));
							$this->db->update('project_section',$data);

						$data_task = array('section_id'=>$status_arr[1]);
							$this->db->where(array('subsection_id'=>$t2,'task_project_id'=>$project_id));
							$this->db->update('tasks',$data_task);

						$j++;
					}
				}

			}

			if($status_arr[0] =='taskmove')
			{
				$subsection_id = $status_arr[1];
				$section_id = $status_arr[2];

				$task = array();
				$task = str_replace('task_tasksort[]=', '', $step1);

				$i=1;
				foreach ($task as $t)
				{
					 $data = array('task_order'=>$i,'section_id'=>$section_id,'subsection_id'=>$subsection_id);
					 	$this->db->where(array('task_id'=>$t,'task_project_id'=>$project_id));
						$this->db->update('tasks',$data);
					$i++;
				}
			}
		}
	}


	// Project section functions for mobile site
	/**
         * This is project section functions for mobile site.
         * @returns void
         */

	function list_project()
	{
		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		//$data['site_setting'] = $this->config->item('company_default_format');


		$data = array();
		$data['msg']="";
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$data['user'] = get_user_info(get_authenticateUserID());
		$filter = 'Open';
		$data['projects'] = $this->project_model->get_project_list($filter);

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type="";
			$this->template->set_master_template($theme .'/template_mobile.php');

			$data['listProjects'] = $this->project_model->get_AllProjects();
			$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),$type);
			//echo pr($data['listProjects']);die;

			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/projects/list_projects',$data,TRUE);
			//$this->template->write_view('footer',$theme .'/mobileview/common/footer',$data,TRUE);
			$this->template->render();
		}

	}
        /**
         * This function will show task of project on  mobile site.
         * @param int $project_id
         * @returns void
         */
	function project_tasks($project_id)
	{
		$data['project_id'] = base64_decode($project_id);
                /**
                 * check authentication
                 */
		if (!check_user_authentication()) {
			redirect ('home');
		}
		if(!is_project($data['project_id'])){
			redirect ('home');
		}

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		//$data['site_setting'] = $this->config->item('company_default_format');


		$data = array();
		$data['msg']="";
		$data['site_setting_date'] = $this->config->item('company_default_format');
		
		$data['user'] = get_user_info(get_authenticateUserID());

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$type="all";
			$this->template->set_master_template($theme .'/template_mobile.php');

			$data['project_tasks'] = $this->project_model->get_TasksByID(base64_decode($project_id),$type);
			$data['project_id'] = base64_decode($project_id);
			//echo pr($data['project_tasks']);die;

			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/projects/project_tasks',$data,TRUE);
			//$this->template->write_view('footer',$theme .'/mobileview/common/footer',$data,TRUE);
			$this->template->render();
		}
	}
        /**
         * This function is used for filter user list on mobile site.
         * @returns view
         */
	function filterUser()
	{
		$theme = getThemeName ();

		$data['site_setting_date'] = $this->config->item('company_default_format');

		$type = $_POST['id'];
		$project_id = $_POST['project_id'];

		$data['user'] = get_user_info(get_authenticateUserID());
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['project_tasks'] = $this->project_model->get_TasksByID($project_id,$type);


			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->load->view($theme.'/mobileview/projects/Ajax_project_tasks',$data);
		}
	}
        /**
         * When user add new project have a status option when user select completed option at the same time this function will call for check project action.
         * It will 
         * create project view .
         * @returns view
         */
	function completeTask()
	{
		//echo pr($_POST);
		$status = $_POST['status'];
		$task_id = $_POST['id'];
		$project_id = $_POST['project_id'];
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$task_status_notready_id = get_task_status_id_by_name('Not Ready');

		if($status==$task_status_completed_id){
			$data_status = array('task_status_id'=>$task_status_notready_id,'task_completion_date'=>date("Y-m-d H:i:s"));
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data_status);
		}else{
			$data_status = array('task_status_id'=>$task_status_completed_id);
			$this->db->where('task_id',$task_id);
			$this->db->update('tasks',$data_status);
		}



			$data['user'] = get_user_info(get_authenticateUserID());

			if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
			{
				$theme = getThemeName ();
				$type = 'all';
				$data['project_tasks'] = $this->project_model->get_TasksByID($project_id,$type);


				$this->template->set_master_template($theme .'/template_mobile.php');
				$this->load->view($theme.'/mobileview/projects/Ajax_project_tasks',$data);
			}

		//echo "done";
	}
        /**
         * This function will insert new projects from moblie version
         * @returns void
         */
	function insertProject()
	{
		//echo "in";die;
		//echo pr($_POST);die;
		if (!check_user_authentication()) {
			redirect ('home');
		}

		$theme = getThemeName ();
		//$this->template->set_master_template ($theme.'/template2.php');
		$this->template->set_master_template($theme .'/template_mobile.php');
		$data = array();

		$data['site_setting_date'] = $this->config->item('company_default_format');
		$offset=0;
		$data['error'] = '';

		if($_POST){
			$offset=$this->input->post('offset')>0?$this->input->post('offset'):0;
		}else{
			$offset=$offset;
		}

		$limit = 10;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('project_title','Project Title','required');
		//$this->form_validation->set_rules('project_status','Project status','required');
		$this->form_validation->set_rules('project_start_date','Project start date','required');
		$this->form_validation->set_rules('project_end_date','Project end date','required');
		$this->form_validation->set_rules('division_id','Division','required');
		$this->form_validation->set_rules('department_id','Department','required');
		$this->form_validation->set_rules('project_desc','Project description','required');
		if($_POST){
			//echo "if post";die;
			if($this->form_validation->run() == FALSE){

				if(validation_errors()){
					 $data['error'] = validation_errors();
				} else {
					$data['error'] = '';
				}

				$data['project_id'] = ($this->input->post('project_id')!='')?$this->input->post('project_id'):'0';
				//$data['project_status'] = $this->input->post('project_status');
				$data['project_start_date'] = $this->input->post('project_start_date');
				$data['project_end_date'] = $this->input->post('project_end_date');
				$data['division_id'] = $this->input->post('division_id');
				$data['department_id'] = $this->input->post('department_id');
				$data['project_desc'] = $this->input->post('project_desc');
				$data['project_title'] = $this->input->post('project_title');


				$data['user'] = get_user_info(get_authenticateUserID());
				$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
				$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
				$data['comments'] = $this->project_model->get_project_comments($data['project_id']);
				$data['members'] = $this->project_model->get_project_members($data['project_id']);
				$data['member_lst'] = get_memberList($data['project_id']);

				$data['users_list'] = get_company_users();
				$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
				$data['section'] = $this->project_model->get_project_section($data['project_id']);


				$data['offset'] = $offset;
				$data['limit'] = $limit;

				$data['msg']='';

				if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
				{
					$this->template->set_master_template($theme .'/template_mobile.php');

					$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
					$this->template->write_view('center',$theme .'/mobileview/projects/insertProject',$data,TRUE);
					//$this->template->write_view('footer',$theme .'/mobileview/common/footer',$data,TRUE);
					$this->template->render();
				}

			} else {
				//pr($_POST);die;

				if($this->input->post('project_id') != '0'){

					$res = $this->project_model->edit_Project();
					$this->session->set_flashdata('msg', 'update');
					$data['msg'] = "update";
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('project/list_project');
					}
				} else {
					$res = $this->project_model->insert_Project();
					$this->session->set_flashdata('msg', 'insert');
					$data['msg'] = "insert";
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('project/list_project');
					}
				}
			}
		}else{

				$data['project_id'] = '0';
				//$data['project_status'] = $this->input->post('project_status');
				$data['project_start_date'] = $this->input->post('project_start_date');
				$data['project_end_date'] = $this->input->post('project_end_date');
				$data['division_id'] = $this->input->post('division_id');
				$data['department_id'] = $this->input->post('department_id');
				$data['project_desc'] = $this->input->post('project_desc');
				$data['project_title'] = $this->input->post('project_title');

				$data['user'] = get_user_info(get_authenticateUserID());
				$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
				$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
				$data['comments'] = "";

				$data['members'] = $this->project_model->get_project_members($data['project_id']);
				$data['member_lst'] = get_memberList($data['project_id']);
				$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);


				$data['msg']='';
				$data['offset'] = $offset;
				$data['limit'] = $limit;


				if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
				{
					$this->template->set_master_template($theme .'/template_mobile.php');

					$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
					$this->template->write_view('center',$theme .'/mobileview/projects/insertProject',$data,TRUE);
					//$this->template->write_view('footer',$theme .'/mobileview/common/footer',$data,TRUE);
					$this->template->render();
				}
		}
	}
        /**
         * This function will update project in db .It will create view for edit project.
         * @param int $project_id
         * @returns void
         */
	function edit_Project($project_id){

		$offset=0;

		if (!check_user_authentication()) {
			//echo "in first home";die;
			redirect ('home');
		}
		if(!is_project($project_id)){
			//echo "in second home";die;
			redirect ('home');
		}

		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data = array();
		$data['site_setting_date'] = $this->config->item('company_default_format');

		$data['error'] = '';
		if($project_id!=''){$project_id = $project_id;}else{$project_id="0";}


		if($_POST){
			$offset=$this->input->post('offset')>0?$this->input->post('offset'):0;

		}else{
			$offset=$offset;

		}
		$limit = 10;
		$data['user'] = get_user_info(get_authenticateUserID());
		$data['division'] = get_company_division($this->session->userdata('company_id'),'Active');
		$data['department'] = get_company_department($this->session->userdata('company_id'),'Active');
		$data['comments'] = $this->project_model->get_project_comments($project_id);
		$data['members'] = $this->project_model->get_project_members($project_id);
		$data['member_lst'] = get_memberList($project_id);
		//$data['files'] = get_project_files($project_id);
		//$data['total_history'] = $this->project_model->get_total_history_by_date($project_id);
		//$data['history'] = $this->project_model->get_history_by_date($project_id,$limit,$offset);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
		//$data['section'] = $this->project_model->get_project_section($project_id);


		$one_project = $this->project_model->get_one_project($project_id);
		$data['project_id'] = $project_id;
		//$data['project_status'] = $one_project->project_status;
		$data['project_title'] = $one_project->project_title;
		$data['project_start_date'] = $one_project->project_start_date;
		$data['project_end_date'] = $one_project->project_end_date;
		$data['division_id'] = $one_project->division_id;
		$data['department_id'] = $one_project->department_id;
		$data['project_desc'] = $one_project->project_desc;
		$data['users_list'] = $this->project_model->get_users_list($project_id,$data['division_id'],$data['department_id']);
		//$data['tab']= $this->input->post('tab');
		$data['msg']='';
		$data['limit']=$limit;
		$data['offset']=$offset;

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');

			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/projects/insertProject',$data,TRUE);
			//$this->template->write_view('footer',$theme .'/mobileview/common/footer',$data,TRUE);
			$this->template->render();
		}


	}
        /**
         * It will update task information in db and create view.
         * @returns view
         */
	function set_update_task(){

		$theme = getThemeName();
		$task_id = $_POST['task_id'];
		$data['task_id'] = $task_id;
		$data['type'] = isset($_POST['type'])?$_POST['type']:'';
		$data['selected_user_id'] = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
		$data['data']['msg'] = 'update-task';
		$data['site_setting_date'] = $this->config->item('company_default_format');
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$off_days = get_company_offdays();
		$date = date("Y-m-d");
                                         $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                                           $data['all_report_user']=get_list_user_report_to_adminstartor();
		if(strpos($task_id, 'child') !== false){
			$id = preg_replace("/[^0-9]/", '', $task_id);
			$task_id = $id;
			$task_detail = get_task_detail($task_id);
			$orig_data = (array) $task_detail;
			$task_data = kanban_recurrence_logic($orig_data,'',$off_days);
			$data['td'] = (object) $task_data;
		} else {
			$data['td'] = get_project_task_detail($task_id);
		}
		
		if($data['selected_user_id'] == "all"){
			if($data['td']->task_allocated_user_id != get_authenticateUserID()){
				if($data['td']->is_personal == "0"){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){

						//echo strtotime($date)." != ".strtotime($data['td']->task_due_date);
						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				}

			} else {
				if($data['td']->task_allocated_user_id == get_authenticateUserID()){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){
						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				}
			}
		} else {
			if($data['td']->task_allocated_user_id != get_authenticateUserID()){
				if($data['td']->is_personal == "0"){
					if($data['selected_user_id'] == $data['td']->task_allocated_user_id){
						if($data['type']=='ut')
						{
							if(strtotime($date) < strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'tt'){

							//echo strtotime($date)." != ".strtotime($data['td']->task_due_date);
							if(strtotime($date) == strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'ot'){
							if(strtotime($date) > strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'all'){
							$data['td'] = $data['td'];
						}
					} else {
						$data['td'] = '0';
					}
				} else {
					$data['td'] = '0';
				}

			} else {
				if($data['selected_user_id'] == $data['td']->task_allocated_user_id){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){

						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				}
			}
		}


		if($data['td']!='0'){
			$data["is_master_deleted"] = chk_master_task_id_deleted($data['td']->master_task_id);
			$this->load->view($theme.'/layout/project/ajax_task_div',$data);
		}
	}
        /**
         * It will check task frequency_type for return noncompleted task in db.
         * @returns json
         */
	function next_noncompleted_recurrence(){
		$theme = getThemeName();


		$task_id = $_POST['task_id'];
		$task_detail = get_task_detail($task_id);
		$off_days = get_company_offdays();
		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$off_days = get_company_offdays();

		if($task_detail['frequency_type'] == 'recurrence' && $task_detail['recurrence_type']!='0'){
			$virtual_array = kanban_recurrence_logic($task_detail,'',$off_days);
			$chk_recu = chk_project_recurrence_exists($task_detail,$virtual_array,$off_days);
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
        /**
         * This method is used when user change status of project on mobile site.It will update project status in db .
         * Then it will send mail and notifications to user,task_owner.And it will create view on project.
         * @returns view
         */
	function changeTaskStatus(){
		$theme = getThemeName();
		$status_id = $_POST['status_id'];
		$task_id = $_POST['task_id'];
		$post_data = json_decode($_POST['post_data'],true);
		$data['type'] = isset($_POST['type'])?$_POST['type']:'';
		$data['selected_user_id'] = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
                                        $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
		
		$chk_exist = chk_task_exists($task_id);
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
			$inserted_id = $task_id;
		}
		
		$old_task_status_id = $post_data['task_status_id'];
		$old_task_status_name = get_task_status_name_by_id($old_task_status_id);
		
		$new_task_status_name = get_task_status_name_by_id($status_id);
		
		$task_status_completed_id = get_task_status_id_by_name("Completed");
		
		if($status_id==$task_status_completed_id){
			$data_status = array('task_status_id'=>$status_id,'task_completion_date'=>date("Y-m-d H:i:s"));
			$this->db->where('task_id',$inserted_id);
			$this->db->update('tasks',$data_status);
			
			
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
					'task_id' => $inserted_id,
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
					'task_id' => $inserted_id,
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
			
		} else {
			$data_status = array('task_status_id'=>$status_id,'task_completion_date'=>'0000-00-00 00:00:00');
			$this->db->where('task_id',$inserted_id);
			$this->db->update('tasks',$data_status);
			
			if($old_task_status_id == $task_status_completed_id){
				
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
						'task_id' => $inserted_id,
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
						'task_id' => $inserted_id,
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
		}
		
		if($old_task_status_id != $status_id){
			$history_data = array(
				'histrory_title' => 'Task status changed from "'.$old_task_status_name.'" to "'.$new_task_status_name.'"',
				'history_added_by' => get_authenticateUserID(),
				'task_id' => $inserted_id,
				'date_added' => date('Y-m-d H:i:s')
			);
			$this->db->insert('task_history',$history_data);
		}
		
		$data['site_setting_date'] = $this->config->item('company_default_format');
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$date = date("Y-m-d");
		
		$data['td'] = get_project_task_detail($inserted_id);
		
		if($data['selected_user_id'] == "all"){
			if($data['td']->task_allocated_user_id != get_authenticateUserID()){
				if($data['td']->is_personal == "0"){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){

						//echo strtotime($date)." != ".strtotime($data['td']->task_due_date);
						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				}

			} else {
				if($data['td']->task_allocated_user_id == get_authenticateUserID()){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){
						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				}
			}
		} else {
			if($data['td']->task_allocated_user_id != get_authenticateUserID()){
				if($data['td']->is_personal == "0"){
					if($data['selected_user_id'] == $data['td']->task_allocated_user_id){
						if($data['type']=='ut')
						{
							if(strtotime($date) < strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'tt'){

							//echo strtotime($date)." != ".strtotime($data['td']->task_due_date);
							if(strtotime($date) == strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'ot'){
							if(strtotime($date) > strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'all'){
							$data['td'] = $data['td'];
						}
					} else {
						$data['td'] = '0';
					}
				} else {
					$data['td'] = '0';
				}

			} else {
				if($data['selected_user_id'] == $data['td']->task_allocated_user_id){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){

						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				} 
			}
		}


		if($data['td']!='0'){
			$data["is_master_deleted"] = chk_master_task_id_deleted($data['td']->master_task_id);
			$this->load->view($theme.'/layout/project/ajax_task_div',$data);
		}
		
	}
     /**   
      * This method will call when task is going for complete via check box option on task. It will get actual time of task completion.
      * @returns view   
     */
	function add_actual_time(){

		$theme = getThemeName();
		
		$serializedData = $_POST['str'];

		$unserializedData = array();
		parse_str($serializedData,$unserializedData);
		
		$task_id = $unserializedData['task_id'];
		$task_actual_time_hour = $unserializedData['task_actual_time_hour'];
		$task_actual_time_min = $unserializedData['task_actual_time_min'];
		$post_data = json_decode($unserializedData['task_data'],true);
		$data['type'] = isset($_POST['type'])?$_POST['type']:'';
		$data['selected_user_id'] = isset($_POST['user_id'])?$_POST['user_id']:get_authenticateUserID();
                                           $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
		
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

		$status = get_task_status_id_by_name('Completed');
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
                                    "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2),
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
		date_default_timezone_set($this->session->userdata("User_timezone"));
		$date = date("Y-m-d");
		
		$data['td'] = get_project_task_detail($id);
		
		if($data['selected_user_id'] == "all"){
			if($data['td']->task_allocated_user_id != get_authenticateUserID()){
				if($data['td']->is_personal == "0"){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){

						//echo strtotime($date)." != ".strtotime($data['td']->task_due_date);
						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				}

			} else {
				if($data['td']->task_allocated_user_id == get_authenticateUserID()){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){
						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				}
			}
		} else {
			if($data['td']->task_allocated_user_id != get_authenticateUserID()){
				if($data['td']->is_personal == "0"){
					if($data['selected_user_id'] == $data['td']->task_allocated_user_id){
						if($data['type']=='ut')
						{
							if(strtotime($date) < strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'tt'){

							//echo strtotime($date)." != ".strtotime($data['td']->task_due_date);
							if(strtotime($date) == strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'ot'){
							if(strtotime($date) > strtotime($data['td']->task_due_date)){
								$data['td'] = $data['td'];
							}else{
								$data['td'] = '0';
							}
						}else if($data['type'] == 'all'){
							$data['td'] = $data['td'];
						}
					} else {
						$data['td'] = '0';
					}
				} else {
					$data['td'] = '0';
				}

			} else {
				if($data['selected_user_id'] == $data['td']->task_allocated_user_id){
					if($data['type']=='ut')
					{
						if(strtotime($date) < strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'tt'){

						if(strtotime($date) == strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'ot'){
						if(strtotime($date) > strtotime($data['td']->task_due_date)){
							$data['td'] = $data['td'];
						}else{
							$data['td'] = '0';
						}
					}else if($data['type'] == 'all'){
						$data['td'] = $data['td'];
					}
				} else {
					$data['td'] = '0';
				} 
			}
		}


		if($data['td']!='0'){
			$data["is_master_deleted"] = chk_master_task_id_deleted($data['td']->master_task_id);
			$this->load->view($theme.'/layout/project/ajax_task_div',$data);
		}

	}

// mobile site related functions starts here
/**
 * This function show comment of user for mobile site.
 * @returns view
 */
function comment(){
		if(!check_user_authentication()){
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
		$data['theme'] = $theme;
		
		if($_POST){
			$id = $this->task_model->insert_task_comments();
			$data['task_id'] = $_POST['task_id'];
			$data['project_id'] = $_POST['project_id'];
			$data['comment'] = get_task_inserted_comments($id);
		}
		$project_id = $_POST['project_id'];
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$data['comments'] = get_task_comments($data['task_id']);
			
			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->load->view($theme.'/mobileview/tasks/Ajax_list_comment',$data);
		}else{
			$this->load->view($theme.'/layout/project/ajax_add_comments',$data);
		}
	}

// mobile site related functions ends here
        /**
         * This function is used for update project information in db.When user change project details at the same time this function is called for update data in db.
         * @returns int
         */
	function project_update()
	{
		$project_id = isset($_POST['project_id'])?$_POST['project_id']:'0';	
		$name = isset($_POST['name'])?$_POST['name']:'';
		$value = isset($_POST['value'])?$_POST['value']:'';
		$new_customer_id = isset($_POST['project_customer_id'])?$_POST['project_customer_id']:'';
		if($project_id !='0'){
                                $this->db->select('project_customer_id');
                                $this->db->from('project');
                                $this->db->where('project_id',$project_id);
                                $query = $this->db->get();
                                $project_customer_id =  $query->row()->project_customer_id;
                                $data = array();
                                if(isset($_POST['name']) && $_POST['name']!=''){
                                    $data[$name]= $value;
				}
				else
				{
					$data['project_desc'] = $_POST['project_desc'];
					$data['project_status'] = $_POST['project_status'];
					$data['division_id'] = $_POST['division_id'];
					$data['department_id'] = $_POST['department_id'];
					$data['project_customer_id'] = $new_customer_id;
					$project_start_date = change_date_format($_POST['project_start_date']);
					$data['project_start_date'] = $project_start_date;
					$project_end_date = change_date_format($_POST['project_end_date']);
					$data['project_end_date'] = $project_end_date;
				}
				
                                $this->db->where('project_id',$project_id);
                                $this->db->update('project',$data);
                                
                                
                                if($project_customer_id != $new_customer_id){ 
                                    $total_project_task = $this->project_model->get_all_project_task($project_id);
                                    if(!empty($total_project_task)){
                                        foreach ($total_project_task as $task){
                                            $this->db->set('customer_id',$new_customer_id);
                                            $this->db->where('task_id',$task['task_id']);
                                            $this->db->update('tasks');

                                            if($task['task_time_spent'] == '0'){
                                                $charge_out_rate = get_charge_out_rate($task['task_id']);
                                                $base_employee_rate = get_user_cost_per_hour($task['task_allocated_user_id']);
                                                $dataupdate = array(
                                                           "cost_per_hour"=>$base_employee_rate,
                                                           "cost"=>round(($base_employee_rate*$task['task_time_estimate'])/60,2),
                                                           "charge_out_rate"=>$charge_out_rate,
                                                           "estimated_total_charge"=>round(($charge_out_rate*$task['task_time_estimate'])/60,2),
                                                       );
                                                $this->db->where('task_id',$task['task_id']);
                                                $this->db->update('tasks',$dataupdate);
                                            }
                                        }
                                    }
                                }
                                echo '0';die;
		}else{
			$data = array(
				'project_added_by' => get_authenticateUserID(),
				'company_id' => $this->session->userdata('company_id'),
				'project_title' => $value,
				'project_start_date'=>date('Y-m-d'),
				'project_end_date'=>date('Y-m-d')
			);
			
			$this->db->insert('project',$data);
			$id = $this->db->insert_id();
			
			$data_history = array(
				'project_history_title' => PROJECT_CREATED,
				'project_history_desc' => $this->input->post('project_title').' named Project created successfully.',
				'project_id' => $id,
				'history_type' =>'Project',
				'project_history_added_by' => get_authenticateUserID(),
				'project_history_added_date' => date('Y-m-d H:i:s')
			);
	
			$this->db->insert('project_history',$data_history);
	
			$data = array(
				'user_id' => get_authenticateUserID(),
				'project_id' => $id,
				'is_project_owner' => '1',
				'status' => 'Active',
				'project_user_added_date' => date('Y-m-d H:i:s'),
				'is_deleted' => '0'
			);
			
			$this->db->insert('project_users',$data);
	
			$user_detail = get_user_info(get_authenticateUserID());
	
			$data_history = array(
				'project_history_title' => USER_ADDED_PROJECT,
				'project_history_desc' => 'User '.$user_detail->first_name."".$user_detail->last_name.' added to project .',
				'project_id' => $id,
				'history_type' =>'User',
				'project_history_added_by' => get_authenticateUserID(),
				'project_history_added_date' => date('Y-m-d H:i:s')
			);
	
			$this->db->insert('project_history',$data_history);
	
			$data_section = array(
				'section_name' =>'Section 1',
				'main_section' =>'0',    //main section
				'project_id' => $id,
				'added_by' => get_authenticateUserID(),
				'added_date' => date('Y-m-d')
			);
			$this->db->insert('project_section',$data_section);
			$section_id = $this->db->insert_id();

			$section_order = array(

			'section_order'=>get_section_order_by_project($id,$section_id,'0') ,

			);

			$this->db->where('section_id',$section_id);
			$this->db->update('project_section',$section_order);
			echo $id;die;
		}
		
	}
	/**
         * It will project current with project start date .
         * @returns int
         */
	function is_date_greater()
	{
		$value = change_date_format($_POST['value']);
		
		$project_start_date = change_date_format($_POST['project_start_date']);
		
		if($value < $project_start_date){
			echo "1";die;
		}
		echo "0";die;
		
	}
        /**
         * This function will set project status "OPEN" by default in project page.
         * @returns view
         */
	function set_status()
	{
		$theme = getThemeName();
		
		$data['project_id'] = $_POST['project_id'];
		$data['project_status'] = 'Open';
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		echo $this->load->view($theme .'/layout/project/project_status_Ajax',$data,TRUE);
		die;
	}
	
	// mobile site missing functions 
	
	function setSubCategory(){
			
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$parent_id = $this->input->post('parent_id');
		$sub_id = isset($_POST['sub_id'])?$_POST['sub_id']:'';
		$data['sub_id'] = $sub_id;
		$data['parent_id'] = $parent_id;
		if($parent_id){
			$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active',$parent_id);
		} else {
			$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		}
		
		if($this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->load->view($theme.'/mobileview/tasks/ajax_subCategory', $data);
		}else{
			$this->load->view($theme.'/layout/project/ajax_subCategory', $data);
		}
	}
        
        
        function defaultMember(){
          
		//pr($_POST);die;
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$project_id = $_POST['project_id'];
		$data['project_id'] = $_POST['project_id'];
		

		$data['members'] = $this->project_model->get_project_members($project_id);
		$data['member_lst'] = get_memberList($project_id);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$project_id);
		$this->load->view($theme.'/layout/project/list_memeber_Ajax',$data);

	}
        
        
        function defaultSection()
	{
                        if(!check_user_authentication()){
                                redirect('home');
                        }

                        $theme = getThemeName();
                        $this->template->set_master_template($theme.'/template2.php');

                        $data['site_setting_date'] = $this->config->item('company_default_format');

                        $project_id = $this->input->post('project_id');
                        $user_id = get_authenticateUserID();
                        $data['user_id'] = 'all';
                        $data['project_id'] = $project_id;
                        //$data['section_name']= $this->input->post('section_name');
                        $data['user_id'] = get_authenticateUserID();
                        $data['type'] = 'all';
                        $section_id =  $this->input->post('section_id');


			$data['task_id'] = $this->input->post('task_id');
			$data['task_subsection_id'] = $this->input->post('task_subsection_id');
			$data['task_section_id'] = $this->input->post('task_section_id');
			$data['task_project_id'] = $this->input->post('project_id');
			$data['task']['general']['task_title'] = $this->input->post('task_title');
			$data['task']['general']['task_description'] = $this->input->post('task_description');
			$data['task']['general']['is_personal'] = $this->input->post('is_personal');
			$data['task']['general']['task_priority'] = $this->input->post('task_priority');
			$data['task']['general']['locked_due_date'] = $this->input->post('locked_due_date');
			$data['task']['general']['task_owner_id'] = $this->input->post('task_owner_id');
			$data['task']['general']['task_allocated_user_id'] = $this->input->post('task_allocated_user_id');

			$data['task']['general']['task_time_spent_hour'] = $this->input->post('task_time_spent_hour');
			$data['task']['general']['task_time_spent_min'] = $this->input->post('task_time_spent_min');
			$data['task']['general']['task_time_estimate_hour'] = $this->input->post('task_time_estimate_hour');
			$data['task']['general']['task_time_estimate_min'] = $this->input->post('task_time_estimate_min');

			$data['task']['general']['task_due_date'] = $this->input->post('task_due_date');
			$data['task']['general']['task_category_id'] = $this->input->post('task_category_id');
			$data['task']['general']['task_sub_category_id'] = $this->input->post('task_sub_category_id');
			$data['task']['general']['task_color_code'] = $this->input->post('task_color_code');
			$data['task']['general']['task_staff_level_id'] = $this->input->post('task_staff_level_id');
			$data['task']['general']['task_division_id'] = $this->input->post('task_division_id');
			$data['task']['general']['task_department_id'] = $this->input->post('task_department_id');
			$data['task']['general']['task_skill_id'] =$this->input->post('task_skill_id');
			$data['task']['general']['task_status_id'] = $this->input->post('task_status_id');
			$data['task']['general']['master_task_id'] = $this->input->post('master_task_id');
			$data['task']['general']['kanban_order'] = $this->input->post('kanban_order');
			$data['task']['general']['calender_order'] = $this->input->post('calender_order');

		// for edit task values

		$data['section'] = $this->project_model->get_project_section($data['project_id']);
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$data['project_id']);
		$task_status_completed_id = get_task_status_id_by_name('Completed');
		$data['task_status_completed_id'] = $task_status_completed_id;

		if($data['section']!=''){
			foreach ($data['section'] as $s) {

				$subSection[$s->section_id]= $this->project_model->get_project_subSection($s->section_id);

			}
			$data['subSection'] = $subSection;
		}

		$this->load->view($theme.'/layout/project/task_result_Ajax',$data);
		
	}
        
        function get_default_section_id(){
            
            
            if(!check_user_authentication()){
                                redirect('home');
            }
                        
            $project_id = $_POST['project_id'];
            $section_id= get_project_section_id($project_id);
            echo $section_id; die();
        }
        
        function update_project_member_rate(){
                if(!check_user_authentication()){
                    redirect('home');
                }
                if($_POST){
                    $project_id = $_POST['project_id'];
                    $project_user_id = $_POST['project_user_id'];
                    $user_id = $_POST['user_id'];
                    $name =$_POST['name'];
                    $value = $_POST['value'];
                     $data = array(
                         $name=>$value
                         
                     );
                    $this->db->where('user_id',$user_id);
                    $this->db->where('project_id',$project_id);
                    $this->db->where('project_users_id',$project_user_id);
                    $this->db->update('project_users' ,$data);
                    
                }
        }
        
        function update_project_rate(){
                if(!check_user_authentication()){
                    redirect('home');
                }
                if($_POST){
                    $project_id = $_POST['project_id'];
                    $name = $_POST['name'];
                    $value = $_POST['value'];
                    
                    $data =array(
                        $name=>$value
                        
                    );
                    $this->db->where('project_id',$project_id);
                    $this->db->where('company_id',  $this->session->userdata('company_id'));
                    $this->db->update('project',$data);
                }
            
        }
        
        function update_project_finance_view(){
                if(!check_user_authentication()){
                    redirect('home');
                }
                $theme = getThemeName();
                if($_POST){
                    $project_id = $_POST['project_id'];
                    $one_project = $this->project_model->get_one_project($project_id);
                    $data['project_base_rate'] = $one_project->project_base_rate;
                    $data['project_fixed_price'] = $one_project->project_fixed_price;
                    
                    $data['project_id'] = $project_id;
                    
                    return $this->load->view($theme.'/layout/project/ajax_finance_view',$data);
                }
        }
      //function for getting user of the project  
        function fetch_member_list($project_id){
           // $project_id=$_POST['p_id'];
            $mem=$this->project_model->get_project_members($project_id);
            $data=array();
            foreach($mem as $v){
                $z=array('value'=>"$v->user_id",'text'=>"$v->first_name".' '."$v->last_name");
                 array_push($data,$z);
            }
            echo  json_encode($data);
        }
        
        function set_as_project_admin(){
            if($_POST){
                $project_id = $this->input->post('project_id');
                $is_admin = $this->input->post('is_admin');
                $user_info = $this->input->post('user_info');
                $user_info = explode('&', $user_info);
                
                $this->db->set('is_project_owner',$is_admin);
                $this->db->where('project_id',$project_id);
                $this->db->where('project_users_id',$user_info[0]);
                $this->db->where('user_id',$user_info[1]);
                $this->db->where('is_deleted','0');
                $this->db->update('project_users');
                
            }
            
        }
        
}//Class
?>
