<?php

require_once APPPATH."libraries/chargify_lib/Chargify.php";
/**
 * This class is used to create login page and it's checking authentication of logged in user.
 * And it's defined various methods for login,signup,forgot password etc.
 * This class is extending the SPACULLUS_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Home extends SPACULLUS_Controller {

	/*   
	 Function name :Home()
	 Description :Its Default Constuctor which called when home object initialzie.its load necesary models
	 */
	 /**
        * It default constuctor which is called when home object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	function Home () {
		parent :: __construct ();
                /**
                 * Amazon S3 server Configuration
                 */
		$this->load->library('s3');
                /**
                 * Amazon S3 Configuration
                 */
		$this->config->load('s3');
                /**
                 * Load payment configration
                 */
		$this->config->load('chargify');
                /**
                 * load encrypt library
                 */
		$this->load->library ("encrypt");
                /**
                 * load home controller database
                 */
		$this->load->model ('home_model');
                /**
                 * load user agent library
                 */
		$this->load->library('user_agent');
                /**
                 * load kanban_model
                 */
		$this->load->model('kanban_model');
                /**
                 * set default timezone
                 */
		date_default_timezone_set("UTC");
	}
      /**
        * This method will call by default in home controller.It checks whether the user is authenticated or not.
          If it's authenticated, it will redirect on user default page, otherwise it will redirect on login page with error messages.
        * @param  $msg 
        * @returns void
        */  
	public function index ($msg = '') { 
		$theme = getThemeName ();
		$data['msg'] = $msg;
		/**
                 * check user authentication
                 */
		if (check_user_authentication()) {
			/**
                         * check access version of schedullo if it's moblie version or web.
                         */
			if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
			{
				redirect ('home/main');
			}
			$user_default_page = $this->get_user_default_page();
			/**
                         * redirect user default page
                         */
			if($user_default_page == "team_dashboard"){
				redirect('user/team_dashboard');
			} else if($user_default_page == "weekly_calendar"){
				redirect('calendar/weekView');
			} else if($user_default_page == "monthly_calendar"){
				redirect('calendar/myCalendar');
			} else if($user_default_page == "kanban"){
				redirect('kanban/myKanban');
			} else {
				redirect('user/dashboard');
			}
		}else{
			redirect('home/login');
		}
		/**
                 * it check access version
                 */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/common/home',$data,TRUE);
			$this->template->render();
		}
				
		redirect('home/login');

	}
      /**
        * This function is used for checking session data.
        * @returns int
        */ 
	function session_check()
	{
            /**
             * check session set or not.
             */
		if (!$this->session->userdata('user_id') || $this->session->userdata('user_id') == false) {
			
				echo '-1';
		 		session_destroy();
			}
			else
			{
			    //not expired
			    echo "1";
			}
		
	}
       /**
        * This function is used for show mobileview.it check user authentication 
        * if it's not authenticated to redirect on login page.
        * @returns create view
        */ 
	function main($msg='')
	{
            /**
             * check user authentication if it's not authenticated to redirect login page.
             */
		if (!check_user_authentication()) {
			
				redirect('home/login');
		}
		$theme = getThemeName ();
		$data = array();
		$data['msg'] = $msg;
		 /**
                  * it check moblie version/web version
                  */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/common/home',$data,TRUE);
			//$this->template->write_view('footer',$theme .'/mobileview/common/footer',$data,TRUE);
			$this->template->render();
		}
		
	}
      /** 
        * This method is used when user click on signup link on login page.And it will create signup page for moblie device.
         After that it's applyed validation on input email.If validation is true,it will send mail and redirect on "home/signup2" for next step.
        * @param   string $msg
        * @returns create signup view
        */ 
	
	/*
	 * function : signup
	 * author : Spaculus
	 * desc : This function is used for signup process first step.
	 */
	function signup($msg = '') 
	{
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template.php');
		$data['msg'] = $msg;
		
		$data['active_menu']='signup';
		$data["error"] = "";
		/* load form validation */
		$this->load->library('form_validation');
		
		
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
                        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
                        /**
                         * check form validation for mail send
                         */
                        if($this->form_validation->run() == FALSE){	
                                if(validation_errors())
                                {
                                        $data["error"] = validation_errors();
                                }else{
                                        $data["error"] = "";
                                }
                                $data["email"] = $this->input->post('email');

                        } else {
                            /**
                             * register1 functions is sent mail for registration process.
                             */ 
                                $res = $this->home_model->register1();
                                redirect('home/signup2/'.$res);
                        }
			$this->template->set_master_template($theme .'/template_mobile.php');
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/common/signup',$data,TRUE);
			$this->template->render();
		}else{
                        $this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/common/signup',$data,TRUE);
			$this->template->render();
		}
	}
      /**
        * This function is used when user enter valid email on signup page.
       * After that it will create confirmation page, it will redirect on login page again.
        * @param  $email
        * @returns render view page
        */ 
	
	/*
	 * function : signup2
	 * author : Spaculus
	 * desc : This function is used for signup process second step.
	 */
	function signup2($email = ''){
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template.php');
		$data['msg'] = '';
		$data['active_menu']='signup';
		$data["error"] = "";
		$data['email'] = base64_decode($email);
		/**
                 * it checks access verion for render signup page.
                 */
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
                    /**
                     * it render mobile version.
                     */
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/common/signup2',$data,TRUE);
			$this->template->render();
		}else{
		
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/common/signup2',$data,TRUE);
			$this->template->render();
		}
	}
      /**
        * This method is called when user click on activation link that was sent in mail.
        * It create signup form for registration.It will create subscribation plan for new user 
        * via this method "home_model->register3" .
         
        * @param  $email
        * @returns create view page
        */ 
	
	/*
	 * function : signup3
	 * author : Spaculus
	 * desc : This function is used for signup process last step.
	 */
	function signup3($email = 0){
		
		if($email == ''){
			redirect('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template.php');
		$data['msg'] = '';
		$data['active_menu']='signup';
		$data["error"] = "";
		$data['email'] = $email = base64_decode($email);
		/**
                 * this is checked user subscribation.
                 */
		$check = $this->home_model->check_user_subscribed($email);
		
		if($check== 1) {
                    /**
                     * user is subscribed.
                     */
			$msg = base64_encode("expire");
			redirect("home/login/".$msg);
		}
		
		$data['countries'] = get_all_country();
		/* load form validation and set rules */
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('first_name', 'First Name', 'required|alpha_space|max_length[35]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha_space|max_length[35]');
		$this->form_validation->set_rules('country_id', 'Country Name', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[15]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|min_length[8]|max_length[15]|matches[password]');
		
		if($this->form_validation->run() == FALSE){	
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["first_name"] = $this->input->post('first_name');
			$data["last_name"] = $this->input->post('last_name');
			$data["company_name"] = $this->input->post('company_name');
			$data["company_phoneno"] = $this->input->post('company_phoneno');
			$data["country_id"] = $this->input->post('country_id');
			$data["password"] = $this->input->post('password');
			$data["cpassword"] = $this->input->post('cpassword');
			$data["email"] = $email;
			
		} else {
                        /**
                         * form validation successful
                         */
			
			$test = TRUE;
			
			/**
                         *  Create a ChargifyProduct object in test mode.
                         */
			$customer = new ChargifyCustomer(NULL, $test);
			$customer->email = $email;
			$customer->first_name = $this->input->post('first_name');
			$customer->last_name = $this->input->post('last_name');
			
			/**
                         * to fetch plan detail from database 
                         */
			$query=$this->db->get_where('plans',array('plan_id'=>$this->input->post('plan_id')));
			$plan=$query->row();
		
		
			$subscription = new ChargifySubscription(NULL, $test);
			$subscription->customer_attributes = $customer;	
			/**
                         * it check user subscription plan
                         */
			if($plan){
                            /**
                             * if true it set product id and component_id.
                             */
				$subscription->product_id = $plan->chargify_product_id;
				$subscription->component_id = $plan->chargify_component_id;
			} else {
                            /**
                             * otherwise both will empty
                             */
				$subscription->product_id = "";
				$subscription->component_id = "";
			}
			try{
                            /**
                             * user create new subscription
                             */
				$new_subscription = $subscription->create();
		
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				$Qty->allocated_quantity = "1";
				$Qty1 = $Qty->create($new_subscription->id,$subscription->component_id);
				
		  		
				$company_id = $this->home_model->register3($new_subscription);
				
				$msg = base64_encode("register");
	       /**
                 * check_login function is used for check whether user is logged in or not.		
                 */
				$login =$this->home_model->check_login($company_id);
				/**
                                 * user is authenticated
                                 */
				if($login == '1')
				{
                                    /**
                                     *  redirect user to home page if accessing from mobile device
                                     */
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('home');
					}
					
					redirect('calendar/weekView');
						
				}
                                /**
                                 * change password request
                                 */
				else if($login == '4'){
					redirect('user/change_password');
				}
                                /**
                                 * account is suspended
                                 */
				else if($login == '2')
				{
					$data["email"] = $this->input->post('email');
					$data["first_name"] = $this->input->post('first_name');
					$data["last_name"] = $this->input->post('last_name');
					$data["company_name"] = $this->input->post('company_name');
					$data["company_phoneno"] = $this->input->post('company_phoneno');
					$data["country_id"] = $this->input->post('country_id');
					$data["remember_me"] = $this->input->post('remember_me');
					$data['error']='<p>Your Account is suspended Please contact Administrator</p>';
				}
                                /**
                                 * user's account is inactive
                                 */
				else if($login == '3')
				{
					$data["email"] = $this->input->post('email');
					$data["first_name"] = $this->input->post('first_name');
					$data["last_name"] = $this->input->post('last_name');
					$data["company_name"] = $this->input->post('company_name');
					$data["company_phoneno"] = $this->input->post('company_phoneno');
					$data["country_id"] = $this->input->post('country_id');
					$data["remember_me"] = $this->input->post('remember_me');
					$data['error']='<p>Your account is Inactive. Please, Contact the Administrator.</p>';
				}
                                /**
                                 *  invalid credentials
                                 */
				else
				{
					$data["email"] = $this->input->post('email');
					$data["first_name"] = $this->input->post('first_name');
					$data["last_name"] = $this->input->post('last_name');
					$data["company_name"] = $this->input->post('company_name');
					$data["company_phoneno"] = $this->input->post('company_phoneno');
					$data["country_id"] = $this->input->post('country_id');
					$data["remember_me"] = $this->input->post('remember_me');
					$data['error']='<p>Invalid Email id or Password.</p>';
				}
				
			} catch(ChargifyValidationException $cve) {
				
				$data["error"]=$cve->getMessage();
				
				$data["first_name"] = $this->input->post('first_name');
				$data["last_name"] = $this->input->post('last_name');
				$data["company_name"] = $this->input->post('company_name');
				$data["company_phoneno"] = $this->input->post('company_phoneno');
				$data["country_id"] = $this->input->post('country_id');
				$data["password"] = $this->input->post('password');
				$data["cpassword"] = $this->input->post('cpassword');
				$data['email'] = $email;
			}
		}
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/common/signup3',$data,TRUE);
			$this->template->render();
		}else{
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/common/signup3',$data,TRUE);
			$this->template->render();
		}
	}
      /**
        * This method check company name in db on ajax request.
        * @param  $company
        * @returns boolean
        */ 
	

	/*
	 * function : company_check
	 * author : Spaculus
	 * desc : This function is used for is there any company exist with this name.
	 */

	function company_check($company)
	{
           /**
             * company_unique function is checked company name whether it is unique or not.
             */
		$username = $this->home_model->company_unique($company);
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
        * This function is used for check password validations.This password can only contain alphanumeric value.
        * @param  $str
        * @returns boolan
        */ 
	
	/** password check function
	 * 
	 * author : spaculus
	 */
    public function password_check($str)
    {
       if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
           
         return TRUE;
       }
       else {
           $this->form_validation->set_message('password_check', 'The password can only contain alphanumeric characters');
            return FALSE;
       }
       
    }
	/**
         * This method will fetch user default page from DB on ajax request.
         * @returns String|int
         */
	
	/*
	 * Function : get_user_default_page
	 * Author : Spaculus
	 * Desc : set default page of user when user logged in.
	 */
	function get_user_default_page(){
            /**
             * get user default page from db
             */
		$query = $this->db->select("user_default_page")->from("users")->where("user_id",get_authenticateUserID())->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->user_default_page;
		} else {
			return 0;
		}
	}
      /**
        * This function create login page.When user will click on login button on login page 
       * at the same time this function is called.It will check whether user is authenticated or not.
       *  If it's authenticated ,it will redirect on user default page, otherwise it will redirect on login page with error message.
       *  For checking authentication it will call "home_model->check_login()" method.
        * @param  $msg
        *  @param $id
        * @returns void
        */
	
	/*
	 * Function : login
	 * Author : Spaculus
	 * Desc : When user goes to login page this function is called.
	 */
	function login($msg = '', $id='') {
		
            /**
             * check user authentication
             */
		if (check_user_authentication()) {
			
			if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
			{
				redirect ('home/main');
			}
			$user_default_page = $this->get_user_default_page();
			/**
                         * redirect to user's default page
                         */
			if($user_default_page == "team_dashboard"){
				redirect('user/team_dashboard');
			} else if($user_default_page == "weekly_calendar"){
				redirect('calendar/weekView');
			} else if($user_default_page == "monthly_calendar"){
				redirect('calendar/myCalendar');
			} else if($user_default_page == "kanban"){
				redirect('kanban/myKanban');
			} else {
				redirect('user/dashboard');
			}
		}
		
		if(isset($_GET['userid'])){
                    $encoded_user_id = $_GET['userid'];
                }else{
                    $encoded_user_id ='';
                }
		
	    $data = array();
		$this->load->helper('cookie');
		$data["email"] = get_cookie('email');
		$data["password"] = $this->encrypt->decode(get_cookie('password'));
		$data["remember_me"] = get_cookie('remember_me');
                $data['company_id'] = get_cookie('company_id');
		$data['active_menu']='login';
                $data['encoded_user_id'] = $encoded_user_id;
		$theme = getThemeName();

		$data['error']='';
		$data['msg'] = base64_decode($msg);		
		$data['buy_id'] = $id;	
		
                $this->template->set_master_template($theme .'/template.php');

		$meta_setting = meta_setting();

		$pageTitle = 'Login - '.$meta_setting->title;
	  	$metaDescription = $meta_setting->meta_description;

		$metaKeyword=$meta_setting->meta_keyword;
		$this->template->write('pageTitle',$pageTitle,TRUE);
		$this->template->write('metaDescription',$metaDescription,TRUE);
		$this->template->write('metaKeyword',$metaKeyword,TRUE);

		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password','Password', 'required');

		if($this->form_validation->run() == FALSE)
		{
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
                        /**
                         * it check method is post or not .
                         */
			if($_POST){
				$data["email"] = $this->input->post('email');
				$data["password"] = $this->input->post('password');
				$data["remember_me"] = $this->input->post('remember_me');
				$data["company_id"] = $this->input->post('company_id');
			}
		}
		else
		{
                    
			$this->load->helper('cookie');
                        /**
                         * check login status
                         */
			$login =$this->home_model->check_login();
                        /**
                         * user is authenticated
                         */
			if($login == '1')
			{
				//pr($this->session->all_userdata());die;
				if($this->input->post('encoded_user_id')){
                                    $module_name =  substr($this->input->post('encoded_user_id'),0, 2);
                                    if($module_name == '10'){
                                        redirect('user/my_settings?userid='.$this->input->post('encoded_user_id'));
                                    }
                                  
                                }
				if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
				{
					redirect('home');
				}
				
				$user_default_page = $this->get_user_default_page();
				
				if($user_default_page == "team_dashboard"){
					redirect('user/team_dashboard');
				} else if($user_default_page == "weekly_calendar"){
					redirect('calendar/weekView');
				} else if($user_default_page == "monthly_calendar"){
					redirect('calendar/myCalendar');
				} else if($user_default_page == "kanban"){
					redirect('kanban/myKanban');
				} else {
					redirect('user/dashboard');
				}
				
					
			}
                        /**
                         * change password
                         */
			else if($login == '4'){
				redirect('user/change_password');
			}
                        /**
                         * Account suspended
                         */
			else if($login == '2')
			{
				$data["email"] = $this->input->post('email');
				$data["password"] = $this->input->post('password');
				$data["remember_me"] = $this->input->post('remember_me');
				$data["company_id"] = $this->input->post('company_id');
				echo $data['error']='<p>Your Account is suspended Please contact Administrator</p>';
			}
                        /**
                         * account inactive
                         */
			else if($login == '3')
			{
				$data["email"] = $this->input->post('email');
				$data["password"] = $this->input->post('password');
				$data["remember_me"] = $this->input->post('remember_me');
				$data["company_id"] = $this->input->post('company_id');
				$data['error']='<p>Your account is Inactive. Please, Contact the Administrator.</p>';
			}
                        /**
                         * invalid credentials
                         */
			else
			{                        delete_cookie('remember_me');
					    	delete_cookie('email');
						    delete_cookie('password');
                                                    delete_cookie('company_id');
	
				$data["email"] = $this->input->post('email');
				$data["password"] = $this->input->post('password');
				$data["remember_me"] = $this->input->post('remember_me');
				$data["company_id"] = $this->input->post('company_id');
				$data['error']='<p>Invalid Email id or Password.</p>';
			}
		}

		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/common/login',$data,TRUE);
			$this->template->render();
		}else{

                        $this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/common/login',$data,TRUE);
			$this->template->render();
		}
	}
      /**
        * To activate user account, this function will check whether user is activated or not,
          and it will show error message on login page. 
          When user will click on the activation link of email then this method will acceessed to check.
        * @param  $code 
        * @returns void
        */
	

	/*
	 * Function : activation
	 * Author : Spaculus
	 * Desc : To activate user account, when user will click on activation link of email this function will call.
	 */
	function activation($code) {
		
		$org_code = base64_decode ($code);
                /**
                 * this explode funtion convert code into an array with 1@1  separator.
                 */
                $new_user = '';
		$org_code_arr = explode ("1@1", $org_code);
	 	$uid = $org_code_arr[0];
		$code_org = $org_code_arr[1];
                if(isset($org_code_arr[2])){
                    $new_user = $org_code_arr[2];
                }
               // echo $new_user; die();
           /**
             * Check_user_activation function is used to check whether user is activated or not.    
             */
		$check = $this->home_model->check_user_activation($uid, $code_org);
		/**
                 * if user is activated
                 */
		if ($check== 1) {
				$query=$this->db->get_where('users',array('user_id'=>$uid));
				$use=$query->row();
				
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
				$company_plan=$query_plan->row();
				/**
                                 * get user's plan
                                 */
				if($company_plan){
					$component_id = $company_plan->chargify_component_id;
				} else {
					$component_id = 0;
				}
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				
				if($company->chargify_subscriptions_ID)
				{
					try{	
						$new_qty=count_user_by_company($use->company_id);
						$Qty->allocated_quantity = $new_qty;
						$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
					}catch (ChargifyValidationException $cve) { 
				 		echo $data["error"]=$cve->getMessage();
					}
				}
				
			/**
                         * this is email sending process and fetch template from db
                         */	
                         
			$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='Email address verified'");
			$email_temp=$email_template->row();	
			$email_address_from=$email_temp->from_address;
			$email_address_reply=$email_temp->reply_address;
			$sandgrid_id=$email_temp->sandgrid_id;
			$email_subject=$email_temp->subject;				
			$email_message=$email_temp->message;
			
			$user_name = $use->first_name.' '.$use->last_name;
			
			$email_to = $use->email;
			$subscription_link = site_url();
			
			$email_message=str_replace('{break}','<br/>',$email_message);
			$email_message=str_replace('{user_name}',$user_name,$email_message);
			$email_message=str_replace('{email}',$email_to,$email_message);
			
			
			$str=$email_message;
                        if($sandgrid_id){
                            mail_by_sendgrid($email_to,$user_name,$email_subject,$sandgrid_id);
                        }else{
                           email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                        }
			if($new_user == 'NewUser'){
                            $user_info = array(
                                "email"=>$use->email,
                                "password"=>$use->password
                            );
                            $login = $this->home_model->check_login($use->company_id,$user_info);
                            $msg = base64_encode("NewUser");
                           // echo $login; die();
                            if($login=='1'){
                                redirect('calendar/weekView/'.$msg);	
                            }
                        }
                        else if($new_user == 'NewUserByAdmin')
                        {
                            $rnd=randomCode();	
				$ud=array('forget_password_code'=>$rnd,"forget_password_flag"=>1);
				
				$this->db->where('email',$use->email);
				$this->db->update('users',$ud);
                            redirect('home/reset_password/'.base64_encode($uid).'/'.$rnd);
                        }
			
			
			$msg = base64_encode("activate");
			redirect("home/login/".$msg);						
		} 
                /**
                 *it redirect to login with expired message			
                 */
                 else {
			$msg = base64_encode("expired");
			redirect("home/login/".$msg);
		}		
      
	  	
	 
	}
      /**
        * After login, When user click on logout option this method is used for logout.
       * This function destroy all session of logged in user.
        * @returns void
        */
	

	/** logout function
	 * @return null
	 * author: spaculus
	 */
	function logout ($company_id='',$password = '') {
            /**
             * destory session
             */ 
                $user_email = $this->session->userdata('email');
                if($company_id !='' && $password !=''){
                    $user_info = array(
                                "email"=>$user_email,
                                "password"=>$password
                    );
                    $login = $this->home_model->check_login($company_id,$user_info);
                    
                    if($login == '1'){           
                        return 1;
                        
                    }
                }else{
                    $data1=array(
			'user_id'=>get_authenticateUserID(),
			'user_login_date'=> date('Y-m-d H:i:s'),
			'user_login_ip'=>$_SERVER['REMOTE_ADDR']
		); 
		$this->db->insert('user_login_history',$data1);
		$this->session->sess_destroy();
                    redirect("home");
                }
	}
      /**
        * At registration time,this function checks email existence in DB.
         If email is existed than it will show specific message with boolean value,otherwise returns another boolean value.
        * @returns boolean 
        */
	
	/** email_check function
	 * check unique email
	 * @return booloean
	 * author: spaculus
	 */
	function email_check($email){
           /**
             * email_unique function will check whether email is exist or not in db with $email parameter.and it retruns true or false.
             */
		$email = $this->home_model->email_unique($email);
		if($email == FALSE){
			$this->form_validation->set_message('email_check','There is an existing record with this Email Address.');
			return FALSE;
		}
		return TRUE;
	}
      /**
        * On registration process, this function will check username in DB.
       *  If it exist than it will shows an error message, otherwise it will return boolean value true.
        * @param String $name
        * @returns boolean 
        */
	
	/** username_check function
	 * check unique username
	 * @return booloean
	 * author: spaculus
	 */
	function username_check($name)
	{
       /**
         * Register_unique function checks whether username is unique or not.	
         */
               $username = $this->home_model->register_unique($name);
		if($username == FALSE)
		{
			$this->form_validation->set_message('username_check','There is an existing record with this User Name.');
			return FALSE;
		}
		return TRUE;
	}
	
      /**
        * This function is called when user click on forget password link on login page. 
       *  It will show forget password view page and send mail for forget password. 
        * @param String $msg
        * @returns void
        */

	/** function : forget paassword
 	*  author : spaculus
 	*/
  	function forgot_password($msg='')
	{
		if (check_user_authentication ()!= '') {
			redirect ('home');
		}
		$theme = getThemeName();
		$data['error'] = '';
		$data["msg"] = $msg;
        $data["active_menu"]='';
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template.php');
		
		$pageTitle = 'forget_password';
	    $metaDescription = '';
		$metaKeyword = '';
        $data['site_setting'] = site_setting ();
		
		$this->template->write ('pageTitle', $pageTitle, TRUE);
		$this->template->write ('metaDescription', $metaDescription, TRUE);
		$this->template->write ('metaKeyword', $metaKeyword, TRUE);
		

		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');

		if ($_POST)
		{
			if ($this->form_validation->run() == FALSE)
			{
				if (validation_errors())
				{
					$data["error"] = validation_errors();
				  
				} else
				{
					$data["error"] = "";
				}
				$data["email"] = $this->input->post('email');
				$data["company_id"] = $this->input->post('company_id');
			} else
			{
                            
                       /*
                        * User_forgot_password function send a link for reset password through mail.and it returns a string. 
                        */     
				$message = $this->home_model->user_forgot_password($this->input->post('email'));
				
				
				
				if ($message == "success")
				{
					$msg = base64_encode("forgetsuccess");										
					redirect("home/login/".$msg);
					
				} 
				else if ($message == "inactive")
					{
						$data['error'] = '<p>Your Account is inactive Please contact Administrator</p>';
					} 
					
				else if ($message == "suspend")
				{
							$data['error'] = '<p>Your Account is suspended Please contact Administrator</p>';
				 } 
				 else
				 {
							$data['error'] = '<p>Email Address Not Found.</p>';
				 }

			}
		}
		
		if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
		{
			$this->template->set_master_template($theme .'/template_mobile.php');
			
			
			$this->template->write_view('header',$theme .'/mobileview/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/mobileview/common/forgotpass',$data,TRUE);
			$this->template->render();
		}else{

	       	$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/common/forgotpass',$data,TRUE);
			$this->template->render();
		}

	}
      /**
        * This function is called when user click on reset password link. It will check validation on reset link whether it's exists or expired,
       *  than it show reset password page view, after it is resetting the password than it will redirect on login page.
        * @param $id
       *  @param $code
       *   @param $msg 
        * @returns void
        */
    
	 function reset_password($id = 0,$code="",$msg = '')
	{
		if (check_user_authentication ()!= '') {
		}
		
		 $uid = base64_decode($id);			 
                /**
                 * check reset link 
                 */
                $check_reset_link = $this->home_model->check_reset_link($uid,$code);
		if($check_reset_link==0){
		}
		   
                $check_forgot_password = $this->home_model->check_forgot_passwordflag($uid,$code);
		if ($check_forgot_password == 0){
		}
		
		$theme = getThemeName();

		$data['error'] = '';
		$data["msg"] = '';
                $data["active_menu"]='';
                $data['site_setting'] = site_setting ();
		$this->template->set_master_template($theme . '/template.php');

		$page_detail=meta_setting();
		$pageTitle=$page_detail->title;
		$metaDescription=$page_detail->meta_description;
		$metaKeyword=$page_detail->meta_keyword;
		
		
		$this->template->write('pageTitle', $pageTitle, TRUE);
		$this->template->write('metaDescription', $metaDescription, TRUE);
		$this->template->write('metaKeyword', $metaKeyword, TRUE);

		$this->load->library('form_validation');
                $this->form_validation->set_rules('password', "Password", 'required|min_length[8]|max_length[15]');
		$this->form_validation->set_rules('confirm_password', "Confirm Password", 'required|matches[password]');

		if ($_POST)
		{        
			if ($this->form_validation->run() == FALSE)
			{
				$data["error"] = '';
				
				if (validation_errors())
				{
					$data["error"] .= validation_errors();
				} else
				{

					
					$data["error"] .= "";
				}

				$data["password"] = $this->input->post('password');
				$data["confirm_pssword"] = $this->input->post('confirm_password');

			}
			else{
				
				
				$message = $this->home_model->reset_password($this->input->post("password"), $uid,$code);				
				if($message == "1")
				{
					$msg = base64_encode("reset");
					redirect('home/login/'. $msg);
								
				}
			}
		}
		$data["msg"] = $msg;
		$data["user_id"] = $uid;
		$data["code"] = $code;
				
	
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/common/reset_password',$data,TRUE);
		$this->template->render();

	}

      /**
        * This function is check the existence of email. If the email exists in db 
       * then push the message on existing view like the email is already in use. 
        * @param $email 
        * @returns void
        */  
         function verify_email($email) {
		
		$email = base64_decode($email);
		/**
                 * it verify email
                 */
		$chk_verify = $this->home_model->chk_verify($email);
                /**
                 * if it verify when it redirect to login page
                 */
		if($chk_verify == '1'){
			
			$msg = base64_encode('expire');
			redirect("home/login/".$msg);
		} else {
                    /**
                     * register email as a company
                     */
			$check = $this->home_model->insert_company($email);
			/**
                         * check company register or not.
                         */
			if ($check) {
				$msg = base64_encode($check);
				redirect("home/signup3/".$msg);						
			} else {			
				redirect("home/index/");
			}	
		}
	}
      /**
        * It will check whether email is exist or not in DB.
        * @returns json
        */  
   
   /*
	 * Function :  chk_email_exist
	 * Author : Spaculus
	 * @return boolean
	 */  
   function chk_email_exist(){
		$email = $_POST['email'];
                /**
                 * get company email
                 */
		$query = $this->db->query("select company_email from ".$this->db->dbprefix('company')." where company_email= '$email'  and is_deleted = 0 ");
		if($query->num_rows()>0){
			echo json_encode(FALSE);
		}else{
			echo json_encode(TRUE);
		}
		
	}
      /**
        * This function is used for update or insert sidebar collapse value in db on Ajax request.
        * @returns string
        */  
      
	function save_left_collapse(){
		$collapsed = $_POST['collapsed'];
		$chk_remember_exist = chk_last_remember_exists();
		if($chk_remember_exist == '1'){
			$data = array(
				'sidbar_collapsed' => $collapsed
			);
			$this->db->where('user_id',$this->session->userdata('user_id'));
			$this->db->update('last_remember_search',$data);
		} else {
			$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'sidbar_collapsed' => $collapsed
			);
			$this->db->insert('last_remember_search',$data);
		}
		echo 'done';die;	
	}
	
	/**
        * It will update notification in DB on Ajax request.This function fetch notification details in db and return in Json form.
        * @returns jsonObject
        */  
	function notification()
	{
		$id= $this->input->post('notification_id');
		
		$data_notify = array(
				'is_read' => '1'
			);
			$this->db->where('task_notification_id',$id);
			$this->db->update('task_notification',$data_notify);
			
		$data['detail']=get_notificationdetail($id);
		$data['total'] = countnotification();
		
		echo json_encode($data);
	}
	
	/**
        * It's used for delete the notification from DB.And it will fetch remaining notification details in db for return.
        * @returns int
        */  
	function deleteNotification($notification_id)
	{
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		//$this->db->delete('project_section',array('section_id'=>$sub_id));
		$data = array(
				'is_deleted' => '1'
			);
			$this->db->where('task_notification_id',$notification_id);
			$this->db->update('task_notification',$data);
		$total = countnotification();
		echo $total;
		die;
	}
	/**
        * This method gives latest notification in db and it will return that information in JSON form.
        * @returns JsonObject
        */
	
	/*
	 * Function : getLetestNotification
	 * Author : spaculus
	 * Desc : Gives latest notification of last 15 minutes
	 */
	function getLetestNotification()
	{
		
		if(!check_user_authentication()){
			redirect('home');
		}

		$noti = Letestnotification();
	    

		echo json_encode($noti);
		die;
	}
	/**
        * Set the read status true for notification if it's viewed by user.
        * @returns json
        */
	
	/*
	 * Function : NotyRead
	 * Author : spaculus
	 * Desc : Mark notification as read
	 */
	function NotyRead()
	{
		$id= $this->input->post('id');
		
		$data_notify = array(
			'is_read' => '1'
		);
		
		$this->db->where('notification_user_id',$this->session->userdata("user_id"));
		$this->db->update('task_notification',$data_notify);
			
		$data['message']="DONE";
		echo json_encode($data);
	}
	/**
         * Update user setup in Db.
         * @returns void
         */
	function update_user_setup()
	{
		$user_id = $this->session->userdata("user_id");
		$step_id = $_POST['step_id'];
		
		$check = checkstepExist($user_id,$step_id);
		if($check == 0){
		
			$data_setup = array(
				'us_user_id' => $user_id,
				'step_id'=> $step_id,
				'us_added_date'=>date("Y-m-d")
			);
			
			$this->db->insert('user_setup',$data_setup);
		}
		
	}
        /**
         * It will update all setup in db.
         * @returns void
         */
	function update_all_setup()
	{
		$stepsExist = getExistSteps($this->session->userdata("user_id"),$_POST['type']);
		if($stepsExist){
			foreach ($stepsExist as $s) {
				$data_setup = array(
				'us_user_id' => $this->session->userdata("user_id"),
				'step_id'=> $s->as_step_id,
				'us_added_date'=>date("Y-m-d")
			);
			
			$this->db->insert('user_setup',$data_setup);
			}
		}
	}
	/**
        * This function set flag in session and return.
        * @returns int
        */
	
	function set_session()
	{
		$data['flag'] = '1';
		$this->session->set_userdata($data);
		return $this->session->userdata("flag");
	}
	function set_session_chargify()
	{
		$data['flag_status'] = '1';
		$this->session->set_userdata($data);
		return $this->session->userdata("flag_status");
	}
	
	function userCompanyList(){
		$email = $_POST['email'];
                /**
                 * Get user list
                 */
		$data['companys'] = $this->home_model->getUserCompanyList($email);
		$data['counts'] = count($data['companys']);
		echo json_encode($data);die;
	}
     
	/**
        * This function access web version from session than it will redirect on login page.
        * @returns void
        */
	function access_web_vesrion(){
		$data = array(
			'access_web_vesrion' => '1'
		);
		$this->session->set_userdata($data);
		redirect('home/login');
	}
	
	function setCategoryDefaultSeq(){
		$query = $this->db->query("select company_id from task_category where is_deleted = 0 group by company_id");
		if($query->num_rows()>0){
			$company_ids = $query->result();
			if($company_ids){
				foreach($company_ids as $company_id){
					$query2 = $this->db->query("select category_id from task_category where is_deleted = 0 and company_id = '".$company_id->company_id."' and parent_id = 0");
					if($query2->num_rows()>0){
						$main_category_ids = $query2->result();
						if($main_category_ids){
							$i = 0;
							foreach($main_category_ids as $main_id){
								$i++;
								$update_main = array("category_seq" => $i);
								$this->db->where("category_id",$main_id->category_id);
								$this->db->update("task_category",$update_main);
								
								$query3 = $this->db->query("select category_id from task_category where is_deleted = 0 and company_id = '".$company_id->company_id."' and parent_id = '".$main_id->category_id."'");
								if($query3->num_rows()>0){
									$sub_ids = $query3->result();
									if($sub_ids){
										$j = 0;
										foreach($sub_ids as $sub_id){
											$j++;
											$update_sub = array("category_seq" => $j);
											$this->db->where("category_id",$sub_id->category_id);
											$this->db->update("task_category",$update_sub);
										}
									}
								}
							}
						}
					}
					
				}
			}
		}
		echo "done";die;
	}
        /* Load More notifications*/
        function loadNotification()
	{
		
		if(!check_user_authentication()){
			redirect('home');
		}
		$noti = notification();
	    

		echo json_encode($noti);
		die;
	}
        
        function updateTimesheet_status(){
            $id = $_POST['id'];
            $this->db->set('is_read','1');
            $this->db->where("task_notification_id",$id);
            $this->db->update('task_notification');
            
        }
        function setNotificationObject()
        {
            $object=isset($_POST['msg_object'])?$_POST['msg_object']:'';
            $token= isset($_POST['token'])?$_POST['token']:'';
            $where1=array('browser_token'=>$token);
            $update1=array('browser_token'=>'');
            $this->db->where($where1);
            $this->db->update('users',$update1);
            $where=array('user_id'=>  get_authenticateUserID());
            $update=array('browser_token'=>$token);
            $this->db->where($where);
            $this->db->update('users',$update);
            echo '1';
            die;
        }
        
        function signup5(){
                        $theme = getThemeName ();
                        $this->template->set_master_template ($theme.'/template.php');
                        $data['msg'] = '';
                        $data['active_menu']='signup';
                        $data["error"] = "";
                        
                        $email = $this->input->post('email');
                        $g_recaptcha = $this->input->post('g-recaptcha-response');
                        
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                          CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS => "secret=".GOOGLE_SECRET_KEY."&response=".$g_recaptcha,
                          CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/x-www-form-urlencoded"
                          ),
                        ));

                        $response = curl_exec($curl);
                        curl_close($curl);
                        $response = json_decode($response,true);
                        if ($response['success'] == '1') {
                         
			$test = TRUE;
			
			/**
                         *  Create a ChargifyProduct object in test mode.
                         */
			$customer = new ChargifyCustomer(NULL, $test);
			$customer->email = $email;
			$customer->first_name = $this->input->post('first_name');
			$customer->last_name = $this->input->post('last_name');
			
			/**
                         * to fetch plan detail from database 
                         */
			$query=$this->db->get_where('plans',array('plan_id'=>$this->input->post('plan_id')));
			$plan=$query->row();
		
		
			$subscription = new ChargifySubscription(NULL, $test);
			$subscription->customer_attributes = $customer;	
			/**
                         * it check user subscription plan
                         */
			if($plan){
                            /**
                             * if true it set product id and component_id.
                             */
				$subscription->product_id = $plan->chargify_product_id;
				$subscription->component_id = $plan->chargify_component_id;
			} else {
                            /**
                             * otherwise both will empty
                             */
				$subscription->product_id = "";
				$subscription->component_id = "";
			}
			try{
                            /**
                             * user create new subscription
                             */
				$new_subscription = $subscription->create();
		
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				$Qty->allocated_quantity = "1";
				$Qty1 = $Qty->create($new_subscription->id,$subscription->component_id);
				
		  		
				$company_id = $this->home_model->register3($new_subscription,'web');
				
				$msg = base64_encode("register");
                                /**
                                  * check_login function is used for check whether user is logged in or not.		
                                  */
				$login =$this->home_model->check_login($company_id);
				/**
                                 * user is authenticated
                                 */
				if($login == '1')
				{
                                    /**
                                     *  redirect user to home page if accessing from mobile device
                                     */
					if($this->session->userdata('access_web_vesrion') == 0 && $this->agent->is_mobile())
					{
						redirect('home');
					}
					
					redirect('calendar/weekView');
						
				}
                                /**
                                 * change password request
                                 */
				else if($login == '4'){
					redirect('user/change_password');
				}
                                /**
                                 * account is suspended
                                 */
				else if($login == '2')
				{
					$data["email"] = $this->input->post('email');
					$data["first_name"] = $this->input->post('first_name');
					$data["last_name"] = $this->input->post('last_name');
					$data["company_name"] = $this->input->post('company_name');
					$data["company_phoneno"] = $this->input->post('company_phoneno');
					$data["country_id"] = $this->input->post('country_id');
					$data["remember_me"] = $this->input->post('remember_me');
					$data['error']='<p>Your Account is suspended Please contact Administrator</p>';
				}
                                /**
                                 * user's account is inactive
                                 */
				else if($login == '3')
				{
					$data["email"] = $this->input->post('email');
					$data["first_name"] = $this->input->post('first_name');
					$data["last_name"] = $this->input->post('last_name');
					$data["company_name"] = $this->input->post('company_name');
					$data["company_phoneno"] = $this->input->post('company_phoneno');
					$data["country_id"] = $this->input->post('country_id');
					$data["remember_me"] = $this->input->post('remember_me');
					$data['error']='<p>Your account is Inactive. Please, Contact the Administrator.</p>';
				}
                                /**
                                 *  invalid credentials
                                 */
				else
				{
					$data["email"] = $this->input->post('email');
					$data["first_name"] = $this->input->post('first_name');
					$data["last_name"] = $this->input->post('last_name');
					$data["company_name"] = $this->input->post('company_name');
					$data["company_phoneno"] = $this->input->post('company_phoneno');
					$data["country_id"] = $this->input->post('country_id');
					$data["remember_me"] = $this->input->post('remember_me');
					$data['error']='<p>Invalid Email id or Password.</p>';
				}
				
			} catch(ChargifyValidationException $cve) {
				
				$data["error"]=$cve->getMessage();
				
				$data["first_name"] = $this->input->post('first_name');
				$data["last_name"] = $this->input->post('last_name');
				$data["password"] = $this->input->post('password');
				$data['email'] = $email;
			}
                       }else{
                           
                           $data1['msg'] = base64_encode("error");
                           $data1['first_name'] = $this->input->post('first_name');
                           $data1['last_name'] = $this->input->post('last_name');
                           $data1['email'] = $this->input->post('email');
                           $data1['password'] = $this->input->post('password');
                           $new_data =  implode(',',$data1);
                           $base = base64_encode($new_data);
                           
                        redirect('home/signup/'.$base);
                       }
        }
        
        function activation_email($code){
                $org_code = base64_decode ($code);
                
                /**
                 * this explode funtion convert code into an array with 1@1  separator.
                 */
		$org_code_arr = explode ("1@1", $org_code);
	 	$uid = $org_code_arr[0];
		$code1 = $org_code_arr[1];
                
                $this->db->select('*');
                $this->db->from('users');
                $this->db->where('email_verification_code',$code1);
                $this->db->where('user_id',$uid);
                $query = $this->db->get();
                
		/**
                 * Check user is activated or not
                 */
		if($query->num_rows()>0){ 
                        $this->db->set('verify_email','1');
                        $this->db->set('user_status','Active');
			$this->db->where('user_id',$uid);
			$this->db->update('users');
		}
		 
                redirect('home/index');
        }
        
        
        function resend_verify_mail(){
                     $user_id = $this->input->post('user_id');
                     $code = randomCode();
                     
                     $this->db->set('email_verification_code',$code);
                     $this->db->where('user_id',$user_id);
                     $this->db->where('is_deleted','0');
                     $this->db->update('users');
                    /**
                     * send verify email address mail
                     */
                    $email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='user email verify'");
		    $email_temp=$email_template->row();	
		    $email_address_from=$email_temp->from_address;
		    $email_address_reply=$email_temp->reply_address;
		
                    $email_subject=$email_temp->subject;				
		    $email_message=$email_temp->message;
                   
		    $data_pass = base64_encode($user_id."1@1".$code);
					
                    $activation_link = "<a href='".base_url()."home/activation_email/".$data_pass."' target='_blank'>Activation link</a>";
                    $com_info = $this->session->userdata('email');
					
		    $email_to = $com_info;
					
					
                    $email_message=str_replace('{break}','<br/>',$email_message);
                    $email_message=str_replace('{Activation_link}',$activation_link,$email_message);		
					
					
                    $str=$email_message;
                    $sandgrid_id=$email_temp->sandgrid_id;
                        $sendgriddata = array('subject'=>'user email verify',
                            'data'=>array('subscription_link'=>$activation_link));
                        if($sandgrid_id)
                        {
                            mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,'',$email_subject,$sandgrid_id,$sendgriddata);
                        }else{
                            email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                        }
            echo "done"; die();
        }
        
        function change_user_password(){
            if($_POST){
                $form_data = $this->input->post('data');
                $unserializedData = array();
                parse_str($form_data,$unserializedData);
                
                $this->db->set('password',md5($unserializedData['set_password']));
                $this->db->where('user_id',  $this->session->userdata('user_id'));
                $this->db->update('users');
                
                $this->session->unset_userdata('password_window_flag');
                $this->session->set_userdata('password_window_flag','1');
                echo "done"; die();
            }
        }
        
        
        function change_company_login(){
            $company_id = $this->input->post('company_id');
            $password = $this->input->post('password');
            $value = $this->logout($company_id,$password);
            $data = array();
            $user_default_page = $this->get_user_default_page();
                       /**
                         * redirect user default page
                         */
			if($user_default_page == "team_dashboard"){
				$data['redirect']="user/team_dashboard";
			} else if($user_default_page == "weekly_calendar"){
				$data['redirect']='calendar/weekView';
			} else if($user_default_page == "monthly_calendar"){
				$data['redirect']='calendar/myCalendar';
			} else if($user_default_page == "kanban"){
				$data['redirect']='kanban/myKanban';
			} else {
				$data['redirect']='user/dashboard';
			}
                        $data['status'] = $value;
            echo json_encode($data); die();
        }
        
        /**
         * Verify customer user activation
         * @param type $code
         */
        
        function activate_customer_user($code){
                $org_code = base64_decode ($code);
                /**
                 * this explode funtion convert code into an array with 1@1  separator.
                 */
                $new_user = '';
		$org_code_arr = explode ("1@1", $org_code);
	 	$uid = $org_code_arr[0];
		$code_org = $org_code_arr[1];
                if(isset($org_code_arr[2])){
                    $new_user = $org_code_arr[2];
                }
               // echo $new_user; die();
           /**
             * Check_user_activation function is used to check whether user is activated or not.    
             */
		$check = $this->home_model->check_user_activation($uid, $code_org);
		/**
                 * if user is activated
                 */
		if ($check== 1) {
				$query=$this->db->get_where('users',array('user_id'=>$uid));
				$use=$query->row();
				
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$query_plan = $this->db->select("p.chargify_external_user_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
				$company_plan=$query_plan->row();
				/**
                                 * get user's plan
                                 */
				if($company_plan){
					$component_id = $company_plan->chargify_external_user_component_id;
				} else {
					$component_id = 0;
				}
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				
				if($company->chargify_subscriptions_ID)
				{
					try{	
						$new_qty=count_customer_user_by_company($use->company_id);
						$Qty->allocated_quantity = $new_qty;
						$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
					}catch (ChargifyValidationException $cve) { 
                                            log_message('error',$cve->getMessage());
					}
				}
				
			
			if($new_user == 'NewUser'){
                            $user_info = array(
                                "email"=>$use->email,
                                "password"=>$use->password
                            );
                            $login = $this->home_model->check_login($use->company_id,$user_info);
                            $msg = base64_encode("NewUser");
                           // echo $login; die();
                            if($login=='1'){
                                redirect('calendar/weekView/'.$msg);	
                            }
                        }else{
                            redirect("home/login");						
                        }
		}else {
			redirect("home/login");
		}		
        }
        
}	
?>
