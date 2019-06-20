<?php

require_once APPPATH."libraries/chargify_lib/Chargify.php";
//echo APPPATH;die;
/**
 * This class is used to create company page for admin panel.This class function create company related functionality.   
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Company extends  CI_Controller {
    /**
        * It default constuctor which is called when Company class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	function Company()
	{
            /**
             * call base class contructor
             */
		parent::__construct();	
                  /* load Amazon s3 library file*/
		$this->load->library('s3');
                /* config file amazon s3 */
		$this->config->load('s3');
                /* databasse of company class */
		$this->load->model('company_model');	
                     /* load pagination library */
		$this->load->library('pagination');
		//$this->load->library('chargify_lib/Chargify');
		
	}
	//use:for redirecting at list user page
        /**
         * This function is checked admin is loggedin or not,than it redirect on specific page.
         * @returns void
         */
	function index()
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		redirect('Company/list_company');
	}
	
	/* user list
	 * param  : limit,offset,msg
	 * 
	 */
	/**
         * This function get comapny details from DB and create list_company page for admin.
         * @param int $limit
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function list_company($limit='20',$offset=0,$msg='') {
		
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('list_company');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'Company/list_company/'.$limit.'/';
		$config['total_rows'] = $this->company_model->get_total_company_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->company_model->get_company_result($offset,$limit);
		$data['msg'] = $msg;
		
		$data['offset'] = $offset;
		$data['error']='';
		if($this->input->post('limit') != '')
		{
			$data['limit']=$this->input->post('limit');
		}
		else
		{
			$data['limit']=$limit;
		}
		$data['option']='1V1';
		$data['keyword']='1V1';
		$data['serach_option']='1V1';
		$data['serach_keyword']='1V1';
		$data['search_type']='normal';
		$data['redirect_page']='list_company';
		
		$data['site_setting'] = site_setting();
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/company/list_company',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	/* search patitent
	 * param  : doctor id ,limit,option,keyword,offset,msg
	 * 
	 */
	/**
         * This function check admin authentication and get company details from DB and show in list.
         * @param int $limit
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function search_list_company($limit=20,$option='',$keyword='',$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$redirect_page = 'search_list_company';
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		//$check_rights=get_rights('search_list_user');
		
		//if(	$check_rights==0) {			
		//	redirect('home/dashboard/no_rights');	
		//}
		if($_POST)
		{		
			$option=$this->input->post('option');
			$keyword=($this->input->post('keyword')!='')?str_replace(" ", "-",trim($this->input->post('keyword'))):'1V1';
		}
		else
		{
			$option=$option;
			$keyword=str_replace(" ", "-",trim($keyword));	
		}
		
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
	
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->company_model->get_total_search_company_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		$data['all_country']=getActiveCountry();
		$data['result'] = $this->company_model->get_search_company_result($option,$keyword,$offset, $limit);
		
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		$data['site_setting'] = site_setting();
		
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['redirect_page']=$redirect_page;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/company/list_company',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}

        /**
         * This function get customer details through company_model class and create customer list view page.
         * @param int $id
         * @param int $limit
         * @param int $offset
         * @param string $msg
         * @returns void
         */


function list_customer($id='0',$limit='20',$offset=0,$msg='') {
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('list_company');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		$config['uri_segment']='5';
		$config['base_url'] = base_url().'Company/list_customer/'.$id.'/'.$limit.'/';
		$config['total_rows'] = $this->company_model->get_total_customer_count($id);
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->company_model->get_customer_result($id,$offset,$limit);
		$data['msg'] = $msg;
		$data['id'] = $id;
		
		$data['offset'] = $offset;
		$data['error']='';
		if($this->input->post('limit') != '')
		{
			$data['limit']=$this->input->post('limit');
		}
		else
		{
			$data['limit']=$limit;
		}
		$data['option']='1V1';
		$data['keyword']='1V1';
		$data['serach_option']='1V1';
		$data['serach_keyword']='1V1';
		$data['search_type']='normal';
		$data['redirect_page']='list_customer';
		
		$data['site_setting'] = site_setting();
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/company/list_customer',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	
	/*add new user also called in user update
	 * param  : limit
	 * 
	 */
	/**
         * This function is used for add new company in DB.It set some validation rules and than it insert data in DB.
         * @param int $limit
         * @returns void
         */
	function add($limit=0)
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('add');
		$data['all_country']=getActiveCountry();
		$data['timezone'] = get_timezone();
		$data['msg']='';
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		if($limit > 0)
		{
			$data['limit']=$limit;
		}
		else
		{
			$data['limit']=20;
		}
                /* load form validation library and set rules*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules('company_name', 'Company Name', 'required|callback_company_check');
		$this->form_validation->set_rules('company_email', 'Company Email', 'required|callback_company_email_check');
		$this->form_validation->set_rules('company_address', 'Company Address', 'required');
		$this->form_validation->set_rules('plan_id', 'Plan id', 'required');
		$this->form_validation->set_rules('country_id', 'Country id', 'required');
		$this->form_validation->set_rules('company_phoneno', 'Company Phone no', 'required');
		$this->form_validation->set_rules('company_timezone', 'Company Time Zone', 'required');
		$this->form_validation->set_rules('company_date_format', 'Company Date Format', 'required');
		/*$this->form_validation->set_rules('subscription_date', 'Subscription Date', 'required');
		$this->form_validation->set_rules('next_subscription_date', 'Next Subscription Date', 'required');*/
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Email', 'required');
		$this->form_validation->set_rules('email', 'Email Address', 'required|callback_email_check');
		
		
		/* if form validation is false than it redirect on add_company page with user input*/
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			$data["company_id"] = $this->input->post('company_id');
			$data["company_name"] = $this->input->post('company_name');
			$data["company_email"] = $this->input->post('company_email');
			$data["company_address"] = $this->input->post('company_address');
			$data["prev_profile_image"] = $this->input->post('prev_profile_image');
			$data["plan_id"] = $this->input->post('plan_id');
			$data["country_id"] = $this->input->post('country_id');
			$data["company_phoneno"] = $this->input->post('company_phoneno');
			$data["company_timezone"] = $this->input->post('company_timezone');
			$data["company_date_format"] = $this->input->post('company_date_format');
			/*$data['subscription_date'] = $this->input->post('subscription_date');
			$data['next_subscription_date'] = $this->input->post('next_subscription_date');*/
			$data["status"] = $this->input->post('status');
			
			
			
			$data["user_id"] = $this->input->post('user_id');
			$data['first_name'] = $this->input->post('first_name');
			$data['last_name'] = $this->input->post('last_name');
			$data["email"] = $this->input->post('email');
			$data['chargify_subscriptions_ID'] = $this->input->post("chargify_subscriptions_ID");
			
			
			
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]='list_company';
			$data['site_setting'] = site_setting();
		
			if($this->input->post('offset')=="")
			{
				$limit = '10';
				$totalRows = $this->company_model->get_total_company_count();
				$data["offset"] = (int)($totalRows/$limit)*$limit;
			}else{
				$data["offset"] = $this->input->post('offset');
			}
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/company/add_company',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
			
		}else{
				
			if($this->input->post('company_id')!='')
			{
				
					$test = TRUE;
					$customer = new ChargifyCustomer(NULL, $test);
					//$customer->id = $customers[0]->id;
					$query1=$this->db->get_where('users',array('company_id'=>$this->input->post('company_id'),'is_administrator'=>'1'));
					
					$company=$query1->row();
					
					if($company->chargify_customer_id != '')
					{

					$customer->id =$company->chargify_customer_id;
					$customer->first_name = $this->input->post('first_name');
					$customer->last_name = $this->input->post('last_name');
					$customer->email = $this->input->post('email');
					//echo '<pre>';
					//print_r($customer);	die;
					$customer = $customer->update();	
					///echo "hello";die;
					}
//echo $this->input->post('status');die;
					if($this->input->post('status')=='Inactive')
					{
						$test = TRUE;
						$subscription = new ChargifySubscription(NULL, $test);
						if($company->chargify_subscriptions_ID != '')
						{
						$subscription->id=$company->chargify_subscriptions_ID;
		
						try{
						$deleted_subscription = $subscription->cancel('Subscription canceled');
						}catch (ChargifyValidationException $cve) { 
						 echo $data["error"]=$cve->getMessage(); //die;die;
						}
						}
						
						$user_data = array('user_status'=>'inactive');
						$this->db->where('user_id',$company->user_id);
						$this->db->update('users', $user_data);
					}


				
					
				$this->company_model->company_update($_POST['company_id']);
				$msg = "update";
				$this->session->set_flashdata('msg', $msg);
	
				$offset = $this->input->post('offset');
				$limit=$this->input->post('limit');
			
			if($limit == 0)
			{
				$limit = 20;
			}
			else
			{
				$limit = $limit;
			}
			$redirect_page = $this->input->post('redirect_page');
			$option = $this->input->post('option');
			$keyword = $this->input->post('keyword');
			$did = $this->input->post('did');
			$offset = 0;
			
			

			
			if($redirect_page == 'list_company')
			{
				
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
			}else{
							
		$test = TRUE;
		// Create a ChargifyProduct object in test mode.
		$customer = new ChargifyCustomer(NULL, $test);
		$customer->email = $this->input->post('email');
		$customer->first_name = $this->input->post('first_name');
		$customer->last_name = $this->input->post('last_name');
		
		// Create a card to go with the customer.
		/*$card = new ChargifyCreditCard(NULL, $test);
		$card->first_name = $this->input->post('first_name');
		$card->last_name = $this->input->post('last_name');
		// 1 is used in test mode for a vald credit card.
		/*$card->full_number = '1';
		$card->cvv = '123';
		$card->expiration_month = "02";
		$card->expiration_year = "2016";
		$card->billing_address = "123 any st";
		$card->billing_city = "Anytown";
		$card->billing_state = "CA";
		$card->billing_zip = "55555";
		$card->billing_country = 'US';*/
		

		$query=$this->db->get_where('plans',array('plan_id'=>$this->input->post('plan_id')));
		$plan=$query->row();
		 
		 
		 
		// $product = new ChargifyProduct(NULL, $test);
		//$products = $product->getAllProducts();
		//$Qty->component_id = "86260";
		//$Qty->allocated_quantity = "23";
		/*$Qty = $Qty->getAll("8424018","86260");
		print_r($Qty);die;*/
		//$subscription->component=$Qty;
		
				
		$subscription = new ChargifySubscription(NULL, $test);
		$subscription->customer_attributes = $customer;		
		//$subscription->credit_card_attributes = $card;
		
		if($plan){
			$subscription->product_id = $plan->chargify_product_id;
			$subscription->component_id = $plan->chargify_component_id;
		} else {
			$subscription->product_id = "";
			$subscription->component_id = "";
		}
		try {
			/* create new subscription for new company*/
		  $new_subscription = $subscription->create();
		
		//  echo '<pre>';
		$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
		$Qty->allocated_quantity = "1";
		$Qty1 = $Qty->create($new_subscription->id,$subscription->component_id);
		  
		 // echo '<pre>';
		 /// print_r($new_subscription);
		 // die;
                    /* insert comapny details with new subscription id*/
		  $this->company_model->company_insert($new_subscription);		
		  $msg = "insert";
		  $this->session->set_flashdata('msg', $msg);


			$offset = $this->input->post('offset');
			$limit=$this->input->post('limit');
			
			if($limit == 0)
			{
				$limit = 20;
			}
			else
			{
				$limit = $limit;
			}
			$redirect_page = $this->input->post('redirect_page');
			$option = $this->input->post('option');
			$keyword = $this->input->post('keyword');
			$did = $this->input->post('did');
			$offset = 0;

			if($redirect_page == 'list_company')
			{
				
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}

		} catch (ChargifyValidationException $cve) {
		  // Error handling code.
			 
			 $data["error"]=$cve->getMessage(); //die;die;
			
			
			$data["company_id"] = $this->input->post('company_id');
			$data["company_name"] = $this->input->post('company_name');
			$data["company_email"] = $this->input->post('company_email');
			$data["company_address"] = $this->input->post('company_address');
			$data["prev_profile_image"] = $this->input->post('prev_profile_image');
			$data["plan_id"] = $this->input->post('plan_id');
			$data["country_id"] = $this->input->post('country_id');
			$data["company_phoneno"] = $this->input->post('company_phoneno');
			$data["company_timezone"] = $this->input->post('company_timezone');
			$data["company_date_format"] = $this->input->post('company_date_format');
			/*$data['subscription_date'] = $this->input->post('subscription_date');
			$data['next_subscription_date'] = $this->input->post('next_subscription_date');*/
			$data["status"] = $this->input->post('status');
			
			
			
			$data["user_id"] = $this->input->post('user_id');
			$data['first_name'] = $this->input->post('first_name');
			$data['last_name'] = $this->input->post('last_name');
			$data["email"] = $this->input->post('email');
			$data['chargify_subscriptions_ID'] = $this->input->post("chargify_subscriptions_ID");
			
			
			
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]='list_company';
			$data['site_setting'] = site_setting();
		
			if($this->input->post('offset')=="")
			{
				$limit = '10';
				$totalRows = $this->company_model->get_total_company_count();
				$data["offset"] = (int)($totalRows/$limit)*$limit;
			}else{
				$data["offset"] = $this->input->post('offset');
			}
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/company/add_company',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}
		
		//die;		
			}
			
		}			

	}
	/**
         * This function check company name in DB
         * @param String $company
         * @returns boolean
         */
	
	function company_check($company)
	{
		$username = $this->company_model->company_unique($company);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('company_check', 'There is an existing company associated with this Name');
			return FALSE;
		}
	}	
	
	
	/**
         * This function check mail id is exist or not.
         * @param string $company
         * @returns boolean
         */
	
	
	function company_email_check($company)
	{
		$username = $this->company_model->company_email_unique($company);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('company_check', 'There is an existing company Email address with this Name');
			return FALSE;
		}
	}	

/**
         * This function checks email id of user for admin panel.
         * @param string $email
         * @returns boolean
         */
function email_check($email)
	{
		$username = $this->company_model->user_email_unique($email);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('email_check', 'There is an existing User Email id associated with this Name');
			return FALSE;
		}
	}	
 /**
         * This function is called when user click on removeimage option in edit company details.
         * @param int $company_id
         * @param string $image
         * @param int $limit
         * @param int $offset
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @returns void
         */

function removeimage($company_id,$image,$limit,$offset,$redirect_page,$option,$keyword)
	{
		//echo $company_id;die;
		//echo "sdfsdf";die;
		if($image!='')
		{
			$this->db->where("company_id",$company_id);
	        $this->db->update("company",array("company_logo"=>''));	
			
			$bucket = $this->config->item('bucket_name');
			$actual_image_name = "upload/company_orig/".$image;
			$actual_image_name1 = 'upload/company/'.$image;
			
			if($this->s3->getObjectInfo($bucket,$actual_image_name)){
				$this->s3->deleteObject($bucket,$actual_image_name);
			}
			if($this->s3->getObjectInfo($bucket,$actual_image_name1)){
				$this->s3->deleteObject($bucket,$actual_image_name1);	
			}
		}
		$msg='image_remove';
		redirect('Company/edit/'.$company_id.'/'.$redirect_page.'/1V1/1V1/'.$limit.'/'.$offset.'/'.$msg);
	}	

	/*user update form fill
	 * param  : user id,doctor id ,redirect page,option,keyword,limit,offset
	 * 
	 */
	 /**
          * This function is used for edit company data. This function first check admin authentication,than it will get all data and create edit company page.
          * @param int $id
          * @param string $redirect_page
          * @param string $option
          * @param string $keyword
          * @param int $limit
          * @param int $offset
          * @param string $msg
          * @returns void
          */
	function edit($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('add');
		$data['all_country']=getActiveCountry();
		$data['timezone'] = get_timezone();
		$data['msg']=$msg;
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$one_user = $this->company_model->get_one_company($id);
		//$data['one_user1'] = $this->company_model->get_one_company1($id);
		
	//	print_r($one_user1);die;
		
		
		if($one_user)
		{
			$data["error"] = "";
			$data["limit"]=$limit;
			$data["offset"]=$offset;
			$data["option"]=$option;
			$data["keyword"]=$keyword;
			$data["search_option"]=$option;
			$data["search_keyword"]=$keyword;


						

			$data["company_id"] = $one_user['company_id'];
			$data["company_name"] = $one_user['company_name'];
			$data["company_email"] = $one_user['company_email'];
			$data["company_address"] = $one_user['company_address'];
			$data["prev_profile_image"] = $one_user['company_logo'];
			$data["plan_id"] = $one_user['plan_id'];
			$data["country_id"] = $one_user['country_id'];
			$data["company_phoneno"] = $one_user['company_phoneno'];
			$data["company_timezone"] = $one_user['company_timezone'];
			$data["company_date_format"] = $one_user['company_date_format'];
			$data["chargify_subscriptions_ID"] = $one_user["chargify_subscriptions_ID"];
			/*$data["subscription_date"] = $one_user['subscription_date'];
			$data["next_subscription_date"] = $one_user['next_subscription_date'];*/
			$data["status"] = $one_user['status'];
			
			
			$data["user_id"] = $one_user['user_id'];
			$data["first_name"] = $one_user['first_name'];
			$data["last_name"] = $one_user['last_name'];
			$data["email"] = $one_user['email'];
			
			
			$data["redirect_page"]=$redirect_page;
			$data['site_setting'] = site_setting();
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/company/add_company',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			if($redirect_page == 'list_company')
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			elseif($redirect_page=='search_list_company')
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}else{
				redirect('Company/list_company');
			}
		}
	}
	
	/*delete user data
	 * param  : user id,doctor id ,option,keyword,limit,offset,msg
	 * 
	 */
	/**
         * This function is used for delete company from DB.if company delete,than it also delete their subscription.
         * @param int $id
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @param int $limit
         * @param int $offset
         * @returns void
         */
	function delete($id=0,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_company');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$this->db->where("company_id",$id);
		$this->db->update("company",array("is_deleted"=>1));
		
		
		$query=$this->db->get_where('users',array('company_id'=>$id,'is_administrator'=>'1'));
		
				$use=$query->row();
				
				$test = TRUE;
				$subscription = new ChargifySubscription(NULL, $test);
				if($use->chargify_subscriptions_ID != '')
				{
				$subscription->id=$use->chargify_subscriptions_ID;
				
				
				try{
				$deleted_subscription = $subscription->cancel('Subscription canceled');
				}catch (ChargifyValidationException $cve) { 
				 echo $data["error"]=$cve->getMessage(); //die;die;
				}
				}
				$this->db->where("company_id",$id);
		        $this->db->update("users",array("is_deleted"=>1));	
				
					
		//$this->db->query("delete from ".$this->db->dbprefix('company')." where company_id ='".$id."'");
        $this->session->set_flashdata('msg', "delete");
		//$this->db->delete('user',array('store_id'=>$id));
		if($redirect_page == 'list_company')
		{
			redirect('Company/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
		}
	}
	
	function removeCompanyAjax($id)
	{
		//$data = array('is_deleted'=>'1');
		//$this->db->where('address_company_id',$id);
		//$this->db->delete('company_address', $data);
		$this->db->query("delete from ".$this->db->dbprefix('company_address')." where address_company_id ='".$id."'");
		//$this->db->where('ingredient_id',$id);
		//$this->db->update('ingredients_es', $data);
		
		$this->session->set_flashdata('msg', "delete1");
		
	}
    
	
	function delete_add($id=0,$redirectid,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('list_company');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		//$this->db->delete('rights_assign',array('store_id'=>$id));
		//$this->db->where('user_id',$id);
		//$this->db->delete('user');
	    $this->session->set_flashdata('msg', "delete1");
		
		$this->db->query("delete from ".$this->db->dbprefix('company_address')." where address_company_id ='".$id."'");
		
		
		
      
        $msg='delete1';
		redirect('Company/edit/'.$redirectid.'/'.$redirect_page.'/1V1/1V1/'.$limit.'/'.$offset.'/'.$msg);
       
	}
	
	/*delete , active , inactive multiple user at a time
	 * param  : user id,doctor id ,redirect page,search option,search keyword,limit,offset,msg
	 * 
	 */ 
	function action()
	{
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_company');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$offset=$this->input->post('offset');
		
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		
		$user_id =$this->input->post('chk');
			
		if($action=='delete')
		{
			foreach($user_id as $id)
			{
				$this->db->where("company_id",$id);
				$this->db->update("company",array("is_deleted"=>1));
				
				//$this->db->query("delete from ".$this->db->dbprefix('company')." where company_id ='".$id."'");
				
				$query=$this->db->get_where('users',array('company_id'=>$id,'is_administrator'=>'1'));
				
				$use=$query->row();
				$use_rows =  $query->num_rows();
				
				if(isset($use->chargify_subscriptions_ID)){ $user_id_char = $use->chargify_subscriptions_ID;}else{ $user_id_char = '0';};
				
				$test = TRUE;
				$subscription = new ChargifySubscription(NULL, $test);
				if($user_id_char != '0')
				{
				$subscription->id=$user_id_char;
				
				
				try{
				$deleted_subscription = $subscription->cancel('Subscription canceled');
				}catch (ChargifyValidationException $cve) { 
				 echo $data["error"]=$cve->getMessage(); //die;die;
				}
				}


				//$this->db->where("company_id",$id);
		       // $this->db->update("users",array("is_deleted"=>1));	
				
				if($use_rows >0)
				{
					$user_data = array('is_deleted'=>'1');
					$this->db->where('user_id',$use->user_id);
					$this->db->update('users', $user_data);
				}
				
				$user_data = array('is_deleted'=>'1');
				$this->db->where('company_id',$id);
				$this->db->update('users', $user_data);
				
				

			}
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'list_company')
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
			}
		}
		
		if($action=='active')
		{
			foreach($user_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('company_id',$id);
				$this->db->update('company', $data);
				
				$query=$this->db->get_where('users',array('company_id'=>$id,'is_administrator'=>'1'));
				
				$use=$query->row();
				$use_rows =  $query->num_rows();
				//die;
				$query=$this->db->get_where('company',array('company_id'=>$id));
				$plan=$query->row();
				
				$query=$this->db->get_where('plans',array('plan_id'=>$plan->plan_id));
				$plan1=$query->row();
				
				//print_r($use);die;
				if(isset($use->chargify_subscriptions_ID)){ $user_id_char = $use->chargify_subscriptions_ID;}else{ $user_id_char = '0';};
				$test = TRUE;
				
				//echo $user_id_char; die;
				$subscription = new ChargifySubscription(NULL, $test);
				$subscription->id=     $user_id_char;
				if($plan1){
					$subscription->product_id = $plan1->chargify_product_id;
					$subscription->component_id = $plan1->chargify_component_id;
				} else {
					$subscription->product_id = "";
					$subscription->component_id = "";
				}
				
				
				
				
				// try{
				// $deleted_subscription = $subscription->reactivate();
				// }catch (ChargifyValidationException $cve) { 
				 	// $data["error"]=$cve->getMessage(); //die;die;
				// }
// 				
				if($user_id_char != '0')
				{
				$subscription->id=$user_id_char;
					
				try{
				$deleted_subscription = $subscription->reactivate();
				}catch (ChargifyValidationException $cve) { 
				 	$data["error"]=$cve->getMessage(); //die;die;
				}
				}
				
				
				if($use_rows >0)
				{
					$user_data = array('user_status'=>'Active');
					$this->db->where('user_id',$use->user_id);
					$this->db->update('users', $user_data);
				}
				
				$user_data = array('user_status'=>'Active');
				$this->db->where('company_id',$id);
				$this->db->update('users', $user_data);
			}
			
			$this->session->set_flashdata('msg', "active");
			if($redirect_page == 'list_company')
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{
			
			foreach($user_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('company_id',$id);
				$this->db->update('company', $data);
				
				$query=$this->db->get_where('users',array('company_id'=>$id,'is_administrator'=>'1'));
				
				$use=$query->row();
				$use_rows =  $query->num_rows();
				
				if(isset($use->chargify_subscriptions_ID)){ $user_id_char = $use->chargify_subscriptions_ID;}else{ $user_id_char = '0';};
				
				$test = TRUE;
				//echo $user_id_char; die;
				$subscription = new ChargifySubscription(NULL, $test);
				if($user_id_char != '0')
				{
				$subscription->id=$user_id_char;
					
				try{
				$deleted_subscription = $subscription->cancel('Subscription canceled');
				}catch (ChargifyValidationException $cve) { 
				 	$data["error"]=$cve->getMessage(); //die;die;
				}
				}
				
				if($use_rows >0)
				{
					$user_data = array('user_status'=>'Inactive');
					$this->db->where('user_id',$use->user_id);
					$this->db->update('users', $user_data);
				}
				$user_data = array('user_status'=>'Inactive');
				$this->db->where('company_id',$id);
				$this->db->update('users', $user_data);
				//die;
				
				
			}
			
			$this->session->set_flashdata('msg', "inactive");
			if($redirect_page == 'list_company')
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('Company/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');
			}
		}	
	}
 	
}


?>
