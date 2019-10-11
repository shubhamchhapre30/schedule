<?php
/**
 * This class is used for create customer page & render customer view page also..
 * This class is extending the SPACULLUS_Controller subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 *  @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

 */



class Customer extends SPACULLUS_Controller{
        /**
         * This is a default construtor of customer class.It's used for initilized parent class construtor & methods.
         * @returns void
         */

         function Customer(){
           /**
             * default class construtor
             */
             parent::__construct();
           /**
             * Amazon S3  Configuration file
             */
	     $this->load->library('s3');
           /**
             * Amazon S3 server Configuration file
             */
	     $this->config->load('s3');
           /**    
             * set company default timezone
             */
             date_default_timezone_set("UTC");
             /**
              * load customer model
              */
             $this->load->model('customer_model');
             /**
              * load user model 
              */
             $this->load->model('user_model');
             /**
              * load project model
              */
             $this->load->model('project_model');
             /*
              * load encryption library
              */
	     $this->load->library('encrypt');
         }
         /**
          * This method is used for rendering customer list,when user click on left side customer icon.
          */
        function index($limit='20',$offset='0'){
                /**
                 * check user authentication
                 */
                if(!check_user_authentication()){
                        redirect('home');
                }
                $completed = $this->config->item('completed_id');
                //echo $completed; die();
                $theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
           
		$data = array();
             
                /**
                 * pagination for project list
                 */
           
               
                $data['total_records ']=  getTotalCustomer();  
                $data['total_pages']= ceil($data['total_records '] / $limit);
                
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu']='from_customer';
		$data['theme'] = $theme;
		$data['error'] = '';
                $data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['cal_user_color_id'] = '0';
		}
                $data['customers']= $this->customer_model->get_Customer_List($limit,$offset);
              //echo "<pre>";  print_r($data['customer']); die();
                $data['user'] = getUserList($this->session->userdata('company_id'));   
              //  print_r($data['user']); die();
                $this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);

		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);

		$this->template->write_view('content_side', $theme.'/layout/customer/customerList', $data, TRUE);

		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
        }
        
        /**
         * This method is used for deleting customer on ajx request.
         * returns id int
         */
        function deleteCustomer(){
            
            
             if(!check_user_authentication()){
                        redirect('home');
                } 
                
                
            $id=  $_POST['customer_id'];
            $this->db->select('*');
            $this->db->from('customers');
            $this->db->where('customer_company_id',$this->session->userdata('company_id'));
            $this->db->where('customer_id',$id);
            $query = $this->db->get();
            $customer_data = $query->row_array();
            $data=array(
                        "is_deleted"=>"1"
            );
            $this->db->where('customer_company_id',$this->session->userdata('company_id'));
            $this->db->where('customer_id',$id);
            $this->db->update('customers',$data);
            
            $data1=array(
                        "customer_id"=>""
                       );
            $this->db->where('task_company_id',$this->session->userdata('company_id'));
            $this->db->where('customer_id',$id);
            $this->db->update('tasks',$data1);
            
            $data2=array(
                        "project_customer_id"=>""
                       );
            $this->db->where('company_id',$this->session->userdata('company_id'));
            $this->db->where('project_customer_id',$id);
            $this->db->update('project',$data2);
            echo json_encode($customer_data); die();
            
        }
        
        /**
         * This function is used for adding/updating customer related info in db and return customer id.
         * returns id int
         */
       
        function saveCustomer(){
            
             if(!check_user_authentication()){
                        redirect('home');
                }
               $unserializedData = array();
               parse_str($_POST['customer_data'],$unserializedData);
            if($unserializedData['customerid']!="")
            { 
                $id = $this->customer_model->updateCustomer();
                $return['customer']=  getOneCustomerDetail($id);
               echo json_encode($return); die();
            }
            else{
                $id = $this->customer_model->insertCustomer();
                $return['customer']=  getOneCustomerDetail($id);
                $return['customer_access'] = $this->session->userdata('customer_access');
                echo json_encode($return); die();
            }
            
        }
        
        /**
         * When user click on customer list at that time this method will render customer related info.
         * @param type $limit
         * @param type $offset1
         */
        function openCustomer($limit='20',$offset='0'){
            if(!check_user_authentication()){
                        redirect('home');
                }
                $theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
                $id=  isset($_POST['cus_id'])?$_POST['cus_id']:'';
                if($id==''){
                        redirect('customer/index');
                }
                
		$data = array();
                $total_row1= $this->customer_model->countTotalCustomerProject($id);
                $data['total_page1']=  ceil($total_row1/$limit);
                $total_row2= $this->customer_model->countTotalCustomerTask($id);
                $data['total_page2']= ceil($total_row2/$limit);
                
                $data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu']='from_customer';
		$data['theme'] = $theme;
		$data['error'] = '';
                $data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['cal_user_color_id'] = '0';
		}
                $data['divisions'] = getUserDivision($this->session->userdata('Temp_calendar_user_id'));
		$data['departments'] = getUserDepartment($this->session->userdata('Temp_calendar_user_id'));
		$data['skills'] = get_company_skills($this->session->userdata('company_id'),'Active');
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['color_codes'] = get_user_color_codes($this->session->userdata('Temp_calendar_user_id'));
		$data['main_category'] = get_company_category($this->session->userdata('company_id'),'Active');
		$data['sub_category'] = get_company_sub_category($this->session->userdata('company_id'),'Active');
		$data['user_projects'] = get_user_projects(get_authenticateUserID());
                $data['customers']= getCustomerList();
                $data['user'] = getUserList($this->session->userdata('company_id')); 
                $data['customer_data']=getOneCustomerDetail($id);
                $data['projects']=  $this->customer_model->get_projects($id,$limit,$offset);
               
                $data['tasks'] = $this->customer_model->get_tasks($id,$limit,$offset);
                $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                $completed_id = $this->config->item('completed_id');
                $data['completed_tasks'] = $this->customer_model->gettaskListByFilter($completed_id,'0','0',$id,'10','0'); 
                $data['customer_users'] = get_customer_user_list($id);
               // pr($data['customer_users']); die();
                $this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);

		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);

		$this->template->write_view('content_side', $theme.'/layout/customer/customerDetail', $data, TRUE);

		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();    
        }
        /**
         * This method will return task list after applying filter on task list.
         * @param type $limit
         * @param type $offset
         */
        function gettaskByFilter($limit='20',$offset='0'){
            if(!check_user_authentication()){
                        redirect('home');
                }
                $completed = $this->config->item('completed_id');
                $theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();    
                $status_id= $this->input->post('status_id');
                $owner_id = $this->input->post('owner_id');
                $allocated_id = $this->input->post('allocated_id');
                $customer_id=  $this->input->post('customer_id');
                $data['active_menu']='from_customer';
                $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
                $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
                $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                $total_row=  $this->customer_model->counttaskListByFilter($status_id,$owner_id,$allocated_id,$customer_id);
                $data['page_no']=ceil($total_row/$limit);
                $data['tasks']=  $this->customer_model->gettaskListByFilter($status_id,$owner_id,$allocated_id,$customer_id,$limit,$offset); 
                $this->load->view($theme.'/layout/customer/ajax_filter', $data);
               
            
        }
       
        /**
         * This method is used for update task data in db & create new view for task.
         */
        function set_update_task(){
            if(!check_user_authentication()){
                        redirect('home');
                }
              $theme = getThemeName ();  
              $data['site_setting_date'] = $this->config->item('company_default_format');
              $task_id=  $this->input->post('task_id');
              $redirect_page =  $this->input->post('redirect_page');
              $data['active_menu']='from_customer';
              $data['swimlanes'] = get_user_swimlanes(get_authenticateUserID());
              $data['color_codes'] = get_user_color_codes(get_authenticateUserID());
              $data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
              $data['tasks']= get_task_detail($task_id);
              $this->load->view($theme.'/layout/customer/update_task_ajax', $data);  
            
        }
        /**
         * It's used for deactivate customer on ajax request.
         */
        function deactivateCustomer(){
            if(!check_user_authentication()){
                        redirect('home');
                }
            $customer_id=  $this->input->post('customer_id');
            $status=  $this->input->post('status');
            $data=array(
                        'status'=>$status           
                        );
            $this->db->where('customer_company_id',$this->session->userdata('company_id'));
            $this->db->where('customer_id',$customer_id);
            $this->db->update('customers',$data);
            if($this->db->affected_rows() == '1') {
                echo TRUE; die();
            } else {
                echo FALSE; die();
            }

        }
        /**
         * This method is used for creating new project from customer module & after that it will redirect on project page.
         */
        function saveProject(){
            if(!check_user_authentication()){
                        redirect('home');
                }
            $id=  $this->project_model->addProject();
            $project_id = $this->encrypt->encode($id);
            //echo $project_id; die();
            redirect('project/editProject?pro_id='.$project_id);
        }
      
        
        function removeCustomer(){
            
            
             if(!check_user_authentication()){
                        redirect('home');
                } 
                
                
            $id=  $_POST['customer_id'];
            $data=array(
                        "is_deleted"=>"1"
            );
            $this->db->where('customer_company_id',$this->session->userdata('company_id'));
            $this->db->where('customer_id',$id);
            $this->db->update('customers',$data);
            
            $data1=array(
                        "customer_id"=>""
                       );
            $this->db->where('task_company_id',$this->session->userdata('company_id'));
            $this->db->where('customer_id',$id);
            $this->db->update('tasks',$data1);
            
            $data2=array(
                        "project_customer_id"=>""
                       );
            $this->db->where('company_id',$this->session->userdata('company_id'));
            $this->db->where('project_customer_id',$id);
            $this->db->update('project',$data2);
            echo $id; die();
            
        }
        function getMoreCustomer($limit='20',$offset='0'){
             $page=isset($_POST['page'])?$_POST['page']:0; 
                if($page){
                    $offset=$page*$limit;
                    $limit=$limit;
                }
                
                $theme = getThemeName();
                $data['theme'] = $theme;
             
                $data['customers']= $this->customer_model->get_Customer_List($limit,$offset);
              
                $data['user'] = getUserList($this->session->userdata('company_id'));   
              
                
		echo $this->load->view($theme .'/layout/customer/loadmorecustomer_ajax',$data,TRUE);

		
        }
        
        function getMoreProject($limit='20',$offset='0'){
                $id=$_POST['customer_id'];
                $page=isset($_POST['page'])?$_POST['page']:0; 
                if($page){
                    $offset=$page*$limit;
                    $limit=$limit;
                }
                //echo $offset; echo $limit; die();
                $theme = getThemeName();
                $data['theme'] = $theme;
                $data['site_setting_date'] = $this->config->item('company_default_format');
                $data['projects']=  $this->customer_model->get_projects($id,$limit,$offset);
                  
                echo $this->load->view($theme .'/layout/customer/ajax_moreProject',$data,TRUE);
            
        }
        
        function getMoreTask($limit='20',$offset='0'){
                $page=isset($_POST['page'])?$_POST['page']:0; 
                if($page){
                    $offset=$page*$limit;
                    $limit=$limit;
                }
                //echo $offset; echo $limit; die();
                $theme = getThemeName();
                $data['theme'] = $theme;
                $data['site_setting_date'] = $this->config->item('company_default_format');
                $status_id= $this->input->post('status_id');
                $owner_id = $this->input->post('owner_id');
                $allocated_id = $this->input->post('allocated_id');
                $customer_id=  $this->input->post('customer_id');
                $data['active_menu']='from_customer';
                $total_row=  $this->customer_model->counttaskListByFilter($status_id,$owner_id,$allocated_id,$customer_id);
                $data['page_no']=ceil($total_row/$limit);
                $data['tasks']=  $this->customer_model->gettaskListByFilter($status_id,$owner_id,$allocated_id,$customer_id,$limit,$offset);
                echo $this->load->view($theme .'/layout/customer/task_ajax',$data,TRUE);
        }
        
        function searchCustomer($limit='20',$offset='0'){
             if(!check_user_authentication()){
                        redirect('home');
                }
               $theme = getThemeName ();  
               $search_name=  $this->input->post('search_name');
               $total_search_row=  $this->customer_model->countCustomerdata($search_name);
               //echo $total_search_row; die();
               $data['pages']= ceil($total_search_row/$limit);
              //echo $search_name; die();
              $data['customers']= $this->customer_model->searchCustomerList($search_name,$limit,$offset);
              $this->load->view($theme.'/layout/customer/ajx_customerlist', $data);  
          }
          
          
          function syn_new_customer(){
              if($_POST){
                  
                  $check = 0;
                  $customers_list =  getCustomerList();
                  $data = $_POST['data'];
                  foreach ($data['Contacts'] as $cus){
                      
                      if($cus['IsCustomer']=='true'){
                          foreach($customers_list as $list){
                              if($list->customer_name == $cus['Name']){
                                  $this->db->set('xero_customer_id',$cus['ContactID']);
                                  $this->db->where('customer_id',$list->customer_id);
                                  $this->db->where('customer_company_id',$list->customer_company_id);
                                  $this->db->update('customers');
                                  $check = 1;
                              }
                          }
                          if($check == 0){
                            $total_customer= getCustomerCount();
                            $customer_id="CU-".($total_customer+1);  
                            $data1 =array(
                                        "customer_id"=>$customer_id,
                                        "first_name" => '',
                                        "last_name"=>'',
                                        "email"=>$cus['EmailAddress'],
                                        "phone"=>'',
                                        "external_id"=>'',
                                        "owner_id"=>  $this->session->userdata('user_id'),
                                        "customer_name"=>$cus['Name'],
                                        "parent_customer_id"=>'',
                                        "customer_company_id"=>  $this->session->userdata('company_id'),
                                        "status"=>"active",
                                        "is_deleted"=>'0',
                                        "create_date"=>date('Y-m-d H:i:s'),
                                        "xero_customer_id"=>$cus['ContactID']
                             );
                              $this->db->insert('customers',$data1);
                              $check = 0;
                          }
                          $check = 0;
                      }  
                  }
                  echo "done"; die();
              }
          }
          
          function get_all_active_customer(){
              $data['customers']=  getCustomerList();
              echo json_encode($data); die();
          }
          
          /**
           * Delete customer user
           */
          function delete_customer_user(){
              $customer_userid = $this->input->post('customer_user_id');
              $this->db->set('is_deleted','1');
              $this->db->where('user_id', $customer_userid);
              $this->db->update('users');
              echo '1'; die();
          }
}
?>