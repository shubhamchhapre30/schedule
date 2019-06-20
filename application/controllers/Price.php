<?php 
/**
 * This class is used for maintaining customer price module & it will render pricing view, there admin can manage their customer & users charge rate per hour.
 * This class is extending the SPACULLUS_Controller subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 *  @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

 */
class Price extends SPACULLUS_Controller {
        /**
         * This is a default construtor of customer class.It's used for initilized parent class construtor & methods.
         * @returns void
         */
        function Price () {
            /* call base class constructor */
             
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
                 * load price model
                 */
                $this->load->model('price_model');
                /*
                 * set default timezone 
                 */
		date_default_timezone_set("UTC");
                
                
	}
        /**
         * This is index page and it display pricing screen, there admin manage their users cost per hour against customers.
         * @param type $limit
         * @param type $offset
         */
        function index($limit='10',$offset='0'){
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
             
            
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu']='from_price';
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
                $data['total_records ']=  count_total_employee();  
                $data['total_pages']= ceil($data['total_records '] / $limit);
                
                    
                $data['employee'] = get_employee_list($limit,$offset);
                
                $data['customers']=  getCustomerList();
                
                $this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);

		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);

		$this->template->write_view('content_side', $theme.'/layout/price/price_maintain', $data, TRUE);

		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
        }
        
        function getMoreEmployee($limit='10',$offset='0'){
                $page=isset($_POST['page'])?$_POST['page']:0; 
                if($page){
                    $offset=$page*$limit;
                    $limit=$limit;
                }
                $search_name =$_POST['search_name'];
                $theme = getThemeName();
                $data['theme'] = $theme;
                if($search_name!=''){
                    $data['employee']= $this->price_model->search_employee_list($search_name,$limit,$offset);
                }else{
                  $data['employee'] = get_employee_list($limit,$offset);
                }
                
                echo $this->load->view($theme .'/layout/price/ajax_moreemployee',$data,TRUE);

            
        }
        
        function searchEmployee($limit='10',$offset='0'){
            if(!check_user_authentication()){
                        redirect('home');
                }
               $theme = getThemeName ();  
               $search_name=  $this->input->post('search_name');
               $total_search_row=  $this->price_model->countemployeeresult($search_name);
               //echo $total_search_row; die();
               $data['total_pages']= ceil($total_search_row/$limit);
              //echo $search_name; die();
              $data['employee']= $this->price_model->search_employee_list($search_name,$limit,$offset);
              $this->load->view($theme.'/layout/price/loadMoreEmployee_ajax', $data);  
        }
        
        function getCustomerBaseRate(){
                if(!check_user_authentication()){
                        redirect('home');
                }
                if($_POST){
                    $customer_id = $_POST['customer_id'];
                    $this->db->select('base_rate');
                    $this->db->from('customers');
                    $this->db->where('customer_id',$customer_id);
                    $this->db->where('customer_company_id',  $this->session->userdata('company_id'));
                    $query = $this->db->get();
                    echo $query->row()->base_rate; die();
                }
        }
        
        function addcustomercategory(){
            if(!check_user_authentication()){
                        redirect('home');
                }
                $theme = getThemeName ();  
                if($_POST){
                    $category_id = $_POST['category_id'];
                    $rate = $_POST['rate'];
                    $customer_id = $_POST['customer_id'];
                    $category_name = $_POST['category_name'];
                    $data = array(
                        "category_name"=>$category_name,
                        "customer_id"=>$customer_id,
                        "category_id"=>$category_id,
                        "rate"=>$rate,
                        "parent_category_id"=>'0',
                        "is_deleted"=>'0',
                        "company_id"=>  $this->session->userdata('company_id'),
                        'created_date'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('customer_category',$data);
                    $this->price_model->insert_subcategory($category_id,$customer_id);
                    $data1['customer_category']= get_customer_category($customer_id);
                   // print_r($data1['customer_category']); die();
                    echo $this->load->view($theme .'/layout/price/ajax_customercategoryadd',$data1,TRUE);
                     die();
                }
        }
        
        function getDefaultCustomerCategory(){
            if(!check_user_authentication()){
                        redirect('home');
                }
                $theme = getThemeName ();  
                if($_POST){
                    $customer_id = $_POST['customer_id'];
                    $data1['customer_category']= get_customer_category($customer_id);
                   // print_r($data1['customer_category']); die();
                    echo $this->load->view($theme .'/layout/price/ajax_customercategoryadd',$data1,TRUE);
                     die();
                }
        }
        
        function getcategoryOption(){
                if(!check_user_authentication()){
                        redirect('home');
                }
                $theme = getThemeName ();  
                if($_POST){
                    $customer_id = $_POST['customer_id'];
                    $data1['category']= get_customer_category_default($customer_id);
                   //print_r($data1['category']); die();
                    echo $this->load->view($theme .'/layout/price/ajax_customer_cat_add',$data1,TRUE);
                     die();
                }
        }
        
        function delete_customer_category(){
            $theme = getThemeName ();
            if($_POST){
                $category_id = $_POST['category_id'];
                $customer_id = $_POST['customer_id'];
                $data = array(
                    'is_deleted'=>'1'
                );
                $this->db->where('customer_id',$customer_id);
                $this->db->where('company_id',$this->session->userdata('company_id'));
                $this->db->where('category_id',$category_id);
                $this->db->update('customer_category',$data);
                
                $this->db->where('customer_id',$customer_id);
                $this->db->where('company_id',$this->session->userdata('company_id'));
                $this->db->where('parent_category_id',$category_id);
                $this->db->update('customer_category',$data);
                
                $data1['category']= get_customer_category_default($customer_id);
                
                echo $this->load->view($theme .'/layout/price/ajax_customer_cat_add',$data1,TRUE); die();
                
            }
            
        }
        
        function updateCategoryRate(){
            if($_POST){
                $customer_id = $_POST['customer_id'];
                $category_id = $_POST['category_id'];
                $name = $_POST['name'];
                $value = $_POST['value'];
                 $data = array(
                     $name=>$value,
                     'updated_date'=>date('Y-m-d H:i:s')
                 );       
                 $this->db->where('customer_id',$customer_id);
                 $this->db->where('company_id',  $this->session->userdata('company_id'));
                 $this->db->where('category_id',$category_id);
                 $this->db->update("customer_category" ,$data);
            }
        }
        
        function get_subcategory_by_category_id(){
            if(!check_user_authentication()){
                        redirect('home');
                }
            $theme = getThemeName ();  
            if ($_POST){
                $category_id = $_POST['category_id'];
                $customer_id = $_POST['customer_id'];
                $data['category_id'] = $category_id;
                $data['sub_category']= getSubcategoryByCategoryId($category_id,$customer_id);
                echo $this->load->view($theme .'/layout/price/ajax_subcategory_list',$data,TRUE);
                     die();
                
            }
        }
        
        function updateEmployeeRate(){
            if($_POST){
                $user_id = $_POST['user_id'];
                $name = $_POST['name'];
                $value = $_POST['value'];
                 $data = array(
                     $name=>$value,
                     "rate_updated_date"=>date('Y-m-d H:i:s')
                 );       
                 $this->db->where('user_id',$user_id);
                 $this->db->where('company_id',  $this->session->userdata('company_id'));
                 $this->db->update("users" ,$data);
            }
            
        }
        
        function updateCustomerrate(){
            if($_POST){
                $customer_id = $_POST['customer_id'];
                $name = $_POST['name'];
                $value = $_POST['value'];
                 $data = array(
                     $name=>$value,
                 );       
                 $this->db->where('customer_id',$customer_id);
                 $this->db->where('customer_company_id',  $this->session->userdata('company_id'));
                 $this->db->update("customers" ,$data);
            }
        }
        
        function updateSubCategoryRate(){
            
            if($_POST){
                $customer_id = $_POST['customer_id'];
                $category_id = $_POST['category_id'];
                $sub_category_id = $_POST['sub_categoryid'];
                $name = $_POST['name'];
                $value = $_POST['value'];
                 $data = array(
                     $name=>$value,
                     'updated_date'=>date('Y-m-d H:i:s')
                 );       
                 $this->db->where('customer_id',$customer_id);
                 $this->db->where('company_id',  $this->session->userdata('company_id'));
                 $this->db->where('category_id',$sub_category_id);
                 $this->db->where('parent_category_id',$category_id);
                 $this->db->update("customer_category" ,$data);
            }
        }
        
        function updateUserRateUnderCustomer(){
            if($_POST){
                $customer_id = $_POST['customer_id'];
                $user_id = $_POST['user_id'];
                $is_exist = is_exists_user_under_customer($customer_id,$user_id);
                $name = $_POST['name'];
                $value = $_POST['value'];
                if($is_exist == '1'){
                    $data = array(
                        $name=>$value,
                        "update_date"=>date("Y-m-d H:i:s")
                    );
                    $this->db->where('customer_id',$customer_id);
                    $this->db->where('user_id',$user_id);
                    $this->db->where('company_id',  $this->session->userdata("company_id"));
                    $this->db->update("users_under_customer_rate", $data);
                }else{
                    
                 $data = array(
                     "customer_id"=>$customer_id,
                     "user_id"=>$user_id,
                     "company_id"=>  $this->session->userdata('company_id'),
                     $name=>$value,
                     'create_date'=>date('Y-m-d H:i:s'),
                     'is_deleted'=>'0',
                     "update_date"=>date('Y-m-d H:i:s')
                 );       
                 $this->db->insert("users_under_customer_rate" ,$data);
                }
            }
        }
        
        function getemployeelist($limit='10',$offset='0'){
            if(!check_user_authentication()){
                        redirect('home');
                }
            $theme = getThemeName ();  
            $customer_id = $_POST['customer_id'];
            $total_search_row=  $this->price_model->count_user_under_manger($customer_id);
            $data['total_pages']= ceil($total_search_row/$limit);
            $data['employee'] = $this->price_model->get_user_under_customer($customer_id,$limit,$offset);
            //echo "<pre>";   print_r($data['employee']); die();
            echo $this->load->view($theme .'/layout/price/employee_under_customer_ajax',$data,TRUE); die();
                     
        }
        
        function getUserUnderEmployee($limit='10',$offset='0'){
            if(!check_user_authentication()){
                        redirect('home');
                }
            $theme = getThemeName (); 
            $page=isset($_POST['page'])?$_POST['page']:0; 
                if($page){
                    $offset=$page*$limit;
                    $limit=$limit;
                }
            $search=$_POST['search'];
            $customer_id = $_POST['customer_id'];
            if($search!=''){
                $data['employee']= $this->price_model->get_user_under_customer($customer_id,$limit,$offset,$search); 
            }else{
                $data['employee'] = $this->price_model->get_user_under_customer($customer_id,$limit,$offset);
            }
            echo $this->load->view($theme .'/layout/price/more_employee_under_customer',$data,TRUE); die();
        }
        
        function searchEmployeeUnderCustomer($limit='10',$offset='0'){
            if(!check_user_authentication()){
                        redirect('home');
                }
               $theme = getThemeName ();  
               $search= $this->input->post('search');
               $customer_id = $_POST['customer_id'];
               $total_search_row=  $this->price_model->count_user_under_manger($customer_id,$search);
               //echo $total_search_row; die();
               $data['total_pages']= ceil($total_search_row/$limit);
              //echo $search_name; die();
              $data['employee']= $this->price_model->get_user_under_customer($customer_id,$limit,$offset,$search);
             // echo "<Pre>"; print_r($data['employee']); die();
              
              $this->load->view($theme.'/layout/price/employee_under_customer_ajax', $data);  
        }
}
?>