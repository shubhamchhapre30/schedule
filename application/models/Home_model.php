<?php
/**
 * This is a model class used for DB intreaction. This model class is used to get,update the various informations related to user, company.  
 * It has definations of various methods like check user authantication, create new user , retrive forgotton password etc.          
 * This class is extending the CI_Model subclasses are instantiated, and are significantly easier to create when 
  they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Home_model extends CI_Model 
{
      /**
        * It default constuctor which is called when home_model object is initialzied.It load base class methods & variables.
        * @returns void
        */
	
	function Home_model()
        {
        parent::__construct();	
        } 
	/**
        * This method checks logged in user email & password in DB.It will set session. And it return appropriate value.
        * @param int $company_id 
        * @returns int 
        */
	
	/*
	 * Function : check_login
	 * Author : Spaculus
	 * Return : boolean
	 * Desc : user status when login
	 */
	function check_login($company_id='',$info='')
	{
		$this->load->helper('cookie');
                if($info!=''){
                    $email = $info['email'];
                    $password = $info['password'];
                }else{
                    $email = $this->input->post('email');
                    $password = $this->input->post('password');
                }
                /**
                 * check company id set or not
                 */
		if($company_id!=''){
			$company_id = $company_id;
		}else{
			$company_id = $this->input->post('company_id');
		}
		/**
                 * get value from cookies and check 
                 */
                if($info==''){
                    if(get_cookie('remember_me')==1 && (get_cookie('email')==$email) && (get_cookie('password') == $this->input->post('password')))
                    {
                            $password = $this->encrypt->decode($this->input->post('password'));
                    }
                }
                
		
                $this->db->select('u.*,c.next_subscription_date,c.is_deleted,u.chargify_subscriptions_ID,c.customer_module_activation,u.customer_module_access,c.pricing_module_status,cr.currency_symbol,cr.currency_code,c.timesheet_module_status,c.xero_integration_status,c.xero_access_token,c.external_users_access');
                $this->db->from('users u');
		$this->db->join('company c','c.company_id = u.company_id');
                $this->db->join('currency cr','cr.currency_code = c.currency','left');
                if($info !=''){
                    $this->db->where(array('u.email'=>$email,'u.password'=>$password,'u.is_deleted'=>0,'c.is_deleted'=>'0','c.company_id'=>$company_id));
                }else{
                    $this->db->where(array('u.email'=>$email,'u.password'=>md5($password),'u.is_deleted'=>0,'c.is_deleted'=>'0','c.company_id'=>$company_id));
                }
                $query = $this->db->get();
		//echo $this->db->last_query();
		//echo $query->num_rows();die;
	  	if($query->num_rows()>0)
	  	{
		  				  	
		  		$admin = $query->row_array();
				
				if($admin['is_owner'] == '0'){
					$company_owner_user_subscription_id = getSubscriptionIDFromCompanyOwner($admin['company_id']);
				}
				$admin_id = $admin['user_id'];
				$username = $admin['first_name'].' '.$admin['last_name'];
				$company_id = $admin['company_id'];
				$status = $admin['user_status'];
				$is_delete = $admin['is_deleted'];
				$is_first_login = $admin['is_first_login'];
				$is_administrator = $admin['is_administrator'];
				$is_owner = $admin['is_owner'];
				$subscription_id = ($admin['is_owner'] == '1')?$admin['chargify_subscriptions_ID']:$company_owner_user_subscription_id;
				$chargify_transaction_status = $admin['chargify_transaction_status'];
                                $customer_access=$admin['customer_module_access'];
                                $customer_module_activation=$admin['customer_module_activation'];
                                $pricing_module_status = $admin['pricing_module_status'];
                                $currency_symbol = $admin['currency_symbol'];
                                $currency_code = $admin['currency_code'];
                                $timesheet_status = $admin['timesheet_module_status'];
                                $timesheet_approver_id = $admin['timesheet_approver_id'];
                                $outlook_synchronization_on = $admin['outlook_synchronization_on'];
                                $xero_integration_status = $admin['xero_integration_status'];
                                $xero_user_access = $admin['xero_access'];
                                $gmail_sync = $admin['gmail_sync'];
                                $is_customer_user = $admin['is_customer_user'];
                                if($admin['xero_access_token']!=''){
                                    $tokens = explode('&',$admin['xero_access_token']);
                                    $xero_access_token = $tokens['0'];
                                    $oauth_token_secret = $tokens['1'];
                                }else{
                                    $xero_access_token = '';
                                    $oauth_token_secret = '';
                                }
                                $xero_access_token = $admin['xero_access_token'];
                                $external_user_access = $admin['external_users_access'];
				$plan = getActiveplan();
				$component_id = $plan[0]->chargify_component_id;
				
				$test = TRUE;
				$sub = new ChargifySubscription(NULL,$test);
                                
				try{
                                    $sub_detail = @$sub->getByID($subscription_id);
                                    $state = $sub_detail->state;
                                    $period_ends = $sub_detail->current_period_ends_at;
                                    if($sub_detail->credit_card){
                                            $is_credit_info = 1;
                                    } else {
                                            $is_credit_info = 0;
                                    }
                                }catch(ChargifyConnectionException $ex){
                                    $state = $chargify_transaction_status;
                                    $period_ends = $admin['next_subscription_date'];
                                    $is_credit_info = '';
                                    
                                }
				
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				try{
				$componentDetail = $Qty->getAll($subscription_id, $component_id);
				
				$allocated_quantity = $componentDetail->allocated_quantity;
                                //echo $allocated_quantity ; die();
                                }catch(ChargifyConnectionException $ex1){
                                    $allocated_quantity = '';
                                }
				if($status=='Active')
				{
					$data = array(
							'user_id' => $admin_id,						
							'email' => $email,
							'username' => $username,
							'company_id' => $company_id,
							'is_administrator' => $is_administrator,
							'is_owner' => $is_owner,
							'is_manager' => $admin['is_manager'],
							'flag' =>'0',
							'flag_status' =>'0',
							'allocated_quantity' =>$allocated_quantity,
							'chargify_transaction_status' =>$state,
							'current_period_ends_at' =>$period_ends,
							'is_credit_info' => $is_credit_info,
                                                        'customer_access'=>$customer_access,
                                                        'customer_module_activation'=>$customer_module_activation,
                                                        'pricing_module_status'=>$pricing_module_status,
                                                        'currency'=>$currency_symbol,
                                                        'currency_code'=>$currency_code,
                                                        'timesheet_module_status'=>$timesheet_status,
                                                        'approver_id'=>$timesheet_approver_id,
                                                        'outlook_synchronization_on'=>$outlook_synchronization_on,
                                                        'browser_token_generate'=>'1',
                                                        'xero_integration_status'=>$xero_integration_status,
                                                        'xero_user_access'=>$xero_user_access,
                                                        'first_login'=>$is_first_login,
                                                        'gmail_sync'=>$gmail_sync,
                                                        'password_window_flag'=>'0',
                                                        'is_customer_user'=>$is_customer_user,
                                                        'access_token'=> $xero_access_token,
                                                        'oauth_token_secret'=>$oauth_token_secret,
                                                        'external_user_access'=>$external_user_access,
                                                        'user_background_type'=>$admin['user_background_type'],
                                                        'user_background_name'=>$admin['user_background_name']
					);
					
					$data1=array(
						'user_id'=>$admin_id,
						'user_login_date'=> date('Y-m-d H:i:s'),
						'user_login_ip'=>$_SERVER['REMOTE_ADDR']
					); 
					$this->db->insert('user_login_history',$data1);
					
					$this->session->set_userdata($data);
					 
                                        
					$utimezone = get_UserTimeZone($admin_id);
					$this->session->set_userdata("User_timezone",$utimezone);
					
                                        /**
                                         * get last user remeber values through id
                                         */
					$last_rember_values = get_user_last_rember_values($admin_id);
					if($last_rember_values){
						$temp_kanban_user_id = $last_rember_values->kanban_team_user_id;
						$temp_calendar_user_id = $last_rember_values->calender_team_user_id;
					} else {
						$last_remember_data = array(
							'user_id' => $admin_id,
							'sidbar_collapsed'=>'0',
							'kanban_project_id' => 'all',
							'calender_project_id' => 'all',
							'task_status_id' => 'all',
							'due_task' => 'all',
							'kanban_team_user_id' => $admin_id,
							'calender_team_user_id' =>$admin_id,
							'show_cal_view' => '1',
							'calender_sorting' => '1',
							'last_calender_view' => '1',
							'user_color_id' =>'0'
						);
						$this->db->insert('last_remember_search',$last_remember_data);
						$temp_kanban_user_id = $admin_id;
						$temp_calendar_user_id = $admin_id;
					}
					/**
                                         * check temp_kanban_user_id if it 0 than it will update last remember data
                                         */
					if($temp_kanban_user_id == "0"){
						$last_remember_data = array(
							'kanban_team_user_id' => $admin_id
						);
						$this->db->where('user_id',$admin_id);
						$this->db->update('last_remember_search',$last_remember_data);
					} 
					if($temp_calendar_user_id == "0"){
						$last_remember_data = array(
							'calender_team_user_id' =>$admin_id
						);
						$this->db->where('user_id',$admin_id);
						$this->db->update('last_remember_search',$last_remember_data);
					}
						
					/**
                                         * there set data in session
                                         */
					$this->session->set_userdata("Temp_kanban_user_id",$temp_kanban_user_id);
					$this->session->set_userdata("Temp_calendar_user_id",$temp_calendar_user_id);
					$this->session->set_userdata('companyDivision','0');
                                            /**
                                             * if remember me checkbox is checked,than this values set in cookie
                                             */
						if($this->input->post('remember_me')=='1')
						{
							$cookie = array(
								'name'   => 'remember_me',
								'value'  => '1',
								'expire' => time()+86500,
							);
							set_cookie($cookie);
							
							$cookieu = array(
								'name'   => 'email',
								'value'  => $email,
								'expire' => time()+86500,
							);
							set_cookie($cookieu);
												
							$cookiep = array(
								'name'   => 'password',
								'value'  => $this->encrypt->encode($password),
								'expire' => time()+86500,
							);
							set_cookie($cookiep);
                                                        
                                                        $cookieco = array(
								'name'   => 'company_id',
								'value'  => $company_id,
								'expire' => time()+86500,
							);
							set_cookie($cookieco);
	
						}
						else
						{
							 
                                                    delete_cookie('remember_me');
                                                    delete_cookie('email');
						    delete_cookie('password');
                                                    delete_cookie('company_id');
	
						}
                                                set_cookie(array('name'   => 'user_company_id',
								'value'  => $company_id,
								'expire' => time()+86500,));
						
	
				}
				/**
                                 *  Account suspended
                                 */
				if($is_delete == 1){
					return 2;
				}
                                /**
                                 *  Account active
                                 */
				elseif($status=='Active')
				{
					
					return 1;
				}
                               /**
                                 *  Account inactive
                                 */
                                else{
					return 3;
				}
	  	}
                   /**
                     *  invalid credentials
                     */
		else {
			return "0";
		}
	}
	/**
        * This method is called for checks email in DB.
        * @param string $str
        * @returns boolean 
        */
	
	/*
	 * Function : email_unique
	 * Author : Spaculus
	 * Desc : check email exist or not for user
	 * return : boolean
	 */
	function email_unique($str)	
	{
		/**
                 * check user_id exist or not in session
                 */
		if($this->session->userdata("user_id")>0)
		{
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$str' and user_id != '".$this->session->userdata("user_id")."' and is_deleted =0");
		}
		else
		{
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$str'  and is_deleted = 0 ");
		
		}	
		
		/**
                 * check above query get some data or not
                 */
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
        * This method will use for send mail on forget password request.It will get user information in DB,
         * then it will check user is active or not. It will send mail with reset password link otherwise it will return not found.
        * @returns string
        */
	
	/*
	 * Function : user_forgot_password
	 * Author : Spaculus
	 * Desc : Forgot password email send function
	 */
	function user_forgot_password()
	{
            /**
             * To fetch user details from users table 
             */
		$query = $this->db->query("select * from ".$this->db->dbprefix('users')." where email= '".$this->input->post('email')."' and is_deleted = '0' and user_status = 'Active'");
		$res = $query->row();
		
		if(count($res)> 0){
			$rnd=randomCode();			
			if($res->user_status == 'inactive'){
                            return 'inactive';
			}else if($res->	is_deleted == '1'){
                            return 'suspend';
			}else{
				
				$ud=array('forget_password_code'=>$rnd,"forget_password_flag"=>1);
				
				$this->db->where('email',$res->email);
				$this->db->update('users',$ud);
				$site_setting = site_setting();
				/**
                                 * Get forget password template from db
                                 */
				$email_temp=$this->db->get_where('email_template',array("task"=>"Forgot Password"))->row();
				
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;
				
				$email_subject=$email_temp->subject;
				$email_message=$email_temp->message;
				
				$email = $this->input->post('email');
				
				$username=ucwords($res->first_name.' '.$res->last_name);
				$email_to =$email;
                                /**
                                 * it create reset password link for mail
                                 */
				$login_link='<a href="'.site_url('home/reset_password/'.base64_encode($res->user_id).'/'.$rnd).'" target="_blank">Here</a>';
				
				$email_message=str_replace('{break}','<br/>',$email_message);				
				$email_message=str_replace('{username}',$username,$email_message);
				$email_message=str_replace('{email}',$email,$email_message);
				$email_message=str_replace('{login_link}',$login_link,$email_message);
								
				$str=$email_message;
				
				email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
				
				return 'success';
			}
		}	
		else{
			return 'notfound';
		}
	
		
	}
	/**
        * This function will check forgot password flag status in db.
        * @param $uid int 
         * @param $code int 
        * @returns int
        */
	
	/*
	 * Function : check_forgot_passwordflag
	 * Author : Spaculus
	 * Desc : Check forgot password flag status
	 */
 	
	function check_forgot_passwordflag($uid = 0,$code="")
	{
            /**
             * this query fetch forget_password_flag from db
             */
		$qry = $this->db->get_where("users", array("user_id" => $uid,"forget_password_code" => $code));
		
		if ($qry->num_rows() > 0)
		{
			$result = $qry->row_array();
			return $result["forget_password_flag"];
		}
		else {
			return 0;	
		}
		
	}
	/**
        * This function will check reset code in DB.That was sent through mail.
        * @param $uid int 
         * @param $code int 
        * @returns int
        */
	
	/*
	 * Function : check_reset_link
	 * Author : Spaculus
	 * return : boolean
	 */
	function check_reset_link($uid = 0,$code="")
	{
		$qry = $this->db->get_where("users", array("user_id" => $uid,"forget_password_code" => $code));
		
		if ($qry->num_rows() > 0)
		{
			return $result = $qry->row_array();
		}
		else {
			return 0;	
		}
		
	}
	/**
        * This function will send link of reset password.It will fetch all mail information in db.
        * @param $password ,$uid int ,$code int forget password code for flag
        * @returns int
        */
	
	/** function : reset_passsword
	 * return int 
	 * author: pokatalk
	 */
	 
	function reset_password($password = '', $uid = 0,$code="")
	{
		$qry = $this->db->get_where("users", array("user_id" => $uid,"forget_password_code" => $code));
		
		if ($qry->num_rows() > 0)
		{
			$user = $qry->row_array();

			$pass = md5($password);
			$password_update = array("password" => $pass, "forget_password_flag" => 0);
		
			
			$this->db->where("email", $user["email"]);
                        $this->db->update("users", $password_update);
                        $user_name = $user["first_name"] . " " . $user["last_name"];
                        $login_link = site_url("home/login");

			
			$email_template = $this->db->query("select * from " . $this->db->dbprefix('email_template') . " where task='change password'");
			$email_temp = $email_template->row();

			$email_address_from = $email_temp->from_address;
			$email_address_reply = $email_temp->reply_address;

			$email_subject = $email_temp->subject;
			$email_message = $email_temp->message;

			$email = $user["email"];		

			$email_to = $email;		

			$email_message = str_replace('{break}', '<br/>', $email_message);
			$email_message = str_replace('{user_name}', $user_name, $email_message);
			$email_message = str_replace('{email}', $email, $email_message);
			$email_message = str_replace('{password}', $password, $email_message);
			$email_message = str_replace('{login_link}', $login_link, $email_message);

                        $str = $email_message;
			
			return 1;

		} 
		else
		{
			return 0;
		}

	}
        /**
        * This function will check user activation in DB.
        * @param $uid int ,$email_verification_code int 
        * @returns int
        */
	
	function check_user_activation($uid=0,$email_verification_code='')
	{
		$query = $this->db->query("SELECT * FROM  ".$this->db->dbprefix('users')." where email_verification_code='".$email_verification_code."' and user_id = '".$uid."' and verify_email = 0");
		/**
                 * Check user is activated or not
                 */
		if($query->num_rows()>0)
		 {
		 	$res = $query->row();
			$data = array('verify_email'=>1,'user_status'=>'Active');
			$this->db->where('user_id',$uid);
			$this->db->update('users',$data);
			
			$company_data = array('status'=>'Active');
			$this->db->where('company_id',$res->company_id);
			$this->db->update('company',$company_data);
			
			return 1;
		 }
		 else 
		 {
			return 0;
		 }	
		
	}
	
	/**
         * This method will check subscription plan in DB.
         * @param $email 
         * @returns int
         */
	
	/*
	 * function :  check_user_subscribed
	 * author : spaculus
	 * desc : check user subscribed or not
	 */
	function check_user_subscribed($email)
	{
		$query = $this->db->query("SELECT * FROM  ".$this->db->dbprefix('users')." where email='".$email."' and is_deleted = 0 ");
		
		if($query->num_rows()>0)
		 {
		 	return 1;
		 }
		 else 
		 {
			return 0;
		 }	
		
	}
        /**
         * This method will access from signup page for mail sending.It will send mail for signup with schedullo.
         * @returns void
         */
	function register1(){
		$email = $this->input->post('email');
		
		$site_setting=site_setting();
		/**
                 * Get signup template from db
                 */
		$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='trial email'");
		$email_temp=$email_template->row();	
		$email_address_from=$email_temp->from_address;
		$email_address_reply=$email_temp->reply_address;
		$sandgrid_id=$email_temp->sandgrid_id;
		$email_subject=$email_temp->subject;				
		$email_message=$email_temp->message;
		
		$email_to = $email;
                /**
                 * It create subscription link for signup
                 */
		$subscription_link = "<a href='".site_url()."home/signup3/".base64_encode($email)."' target='_blank'>Subscription link</a>";
		
		$email_message=str_replace('{break}','<br/>',$email_message);
		$email_message=str_replace('{email}',$email_to,$email_message);
		$email_message=str_replace('{subscription_link}',$subscription_link,$email_message);		
		
		
		$str=$email_message;
                $data = array('subject'=>'trial email',
                    'data'=>array('subscription_link'=>$subscription_link));
		if($sandgrid_id)
                {
                    mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,'',$email_subject,$sandgrid_id,$data);
                }else{
                    email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                }
		return base64_encode($email);
	}
	
	/**
         * This function will call for insert new user data in db.It will insert new user data in different tables.
         * Then it will send mail of successfully registration.
         * @param $new_subscription
         * @returns void
         */
	function register3($new_subscription,$access_mode = ''){
		$country_id = isset($_POST['country_id'])? $this->input->post('country_id'):'';
                $company_phone = isset($_POST['company_phoneno'])?$this->input->post('company_phoneno'):'';
                $data = array(
			'company_name' => htmlspecialchars($this->input->post('company_name')),
			'company_email' => $this->input->post('email'),
			'plan_id' => $this->input->post('plan_id'),
			'country_id' => $country_id,
			'company_phoneno' => $company_phone,
			'subscription_date' => $new_subscription->current_period_started_at,
			'next_subscription_date' => $new_subscription->next_assessment_at,
			'status' => 'Active',
			'company_date_format' => 'd/m/Y',
			'company_timezone' => 'UTC',
			'total_task_status' => '8',
			'company_register_date' => date('Y-m-d h:i:s'),
			'company_register_IP' => $_SERVER['REMOTE_ADDR'],
                        'api_access_status' => 'Active',
                        'external_users_access'=>get_external_user_access()
		);
		/**
                 * insert company data in company table
                 */
		$this->db->insert('company',$data);
		$company_id = $this->db->insert_id();
		
		$rpass= randomCode();
		$code = randomCode();
		
		$data1["first_name"] = $this->input->post('first_name');
		$data1["last_name"] = $this->input->post('last_name');
		$data1["email"] = $this->input->post('email');
		$data1["is_administrator"]='1';
		$data1["is_owner"]='1';
		$data1["password"]=md5($this->input->post('password'));
		$data1["company_id"] = $company_id;
		$data1['user_status'] = 'Active';
		$data1['email_verification_code'] = $code;
		$data1['signup_date'] =date('Y-m-d h:i:s');
		$data1['signup_IP'] = $_SERVER['REMOTE_ADDR'];	
		$data1['user_default_page'] = 'weekly_calendar';
		
		$data1['chargify_customer_id'] = $new_subscription->customer->id;
		$data1['chargify_subscriptions_ID'] =$new_subscription->id;
		$data1['chargify_transaction_id'] =$new_subscription->signup_payment_id;
		$data1['chargify_transaction_status'] = $new_subscription->state;
		$data1['is_first_login'] = '0';
		/**
                 * insert user data in users table
                 */
		$this->db->insert('users',$data1);
		$user_id = $this->db->insert_id();
                
		$this->config->load('chargify');
		
		if($new_subscription->customer->id!=''){
			$headers = array(
			    'Accept:application/json',
			);
			
			
			$username = $this->config->item('API_key');
			$password=$this->config->item('API_key_pass');
			
			$url = 'https://schedullo.chargify.com/portal/customers/'.$new_subscription->customer->id.'/management_link.json';
				
			$ch = curl_init();
	              
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			curl_setopt($ch, CURLOPT_URL,$url);
			$result = curl_exec($ch);
			curl_close($ch);
			$data['billing'] = json_decode($result);
			/**
                         * billing data
                         */
			if($data['billing']->url !=''){
				$data['management_link'] = $data['billing']->url;
				$data['fetch_count'] = $data['billing']->fetch_count;
				$data['created_at'] = date("Y-m-d",strtotime($data['billing']->created_at));
				$data['new_link_available_at'] = date("Y-m-d",strtotime($data['billing']->new_link_available_at));
				$data['expires_at'] = date("Y-m-d",strtotime($data['billing']->expires_at));
				
				$data_link = array(
					'management_link' =>$data['billing']->url,
					'fetch_count'=>$data['billing']->fetch_count,
					'created_at'=>date("Y-m-d",strtotime($data['billing']->created_at)),
					'new_link_available_at'=>date("Y-m-d",strtotime($data['billing']->new_link_available_at)),
					'expires_at'=>date("Y-m-d",strtotime($data['billing']->expires_at))
				);
			
			$this->db->where('user_id',$user_id);
			$this->db->update('users',$data_link);
			
			}else{
				$data['error'] = $data['billing']->errors->error;
			}
		}
		
		$default_task_status = get_default_tasks();
		
                /**
                 * Create client id & secret for api access 
                 */
                $client_id = hash_hmac("sha256",randomCode(),PRIVATEKEY);
                $client_secret = hash_hmac("sha256",randomCode(),PRIVATEKEY);
                
                $reg_data = array(
                    "client_id"=> $client_id,
                    "client_secret"=> $client_secret,
                    "app_name"=>'office 365',
                    "created_date"=>date("Y-m-d H:i:s"),
                    "api_company_id"=> $company_id,
                    "auth_type"=>'client_credentials'
                );
                
                $this->db->insert('app_registration',$reg_data);
                
                
                $data1 = array(
                    "client_id"=>$client_id,
                    "client_secret"=>$client_secret,
                    "grant_types"=>"client_credentials",
                    "user_id"=> $company_id
                );
                
                $this->db->insert('oauth_clients',$data1);
                
		if($default_task_status){
			$i = 1;
			foreach($default_task_status as $task){
				$task_data = array(
					'task_status_name' => $task->task_status_name,
					'company_id' => $company_id,
					'task_status_flag' => 'Active',
					'task_sequence' => $i,
					'task_status_added_date' => date('Y-m-d H:i:s'),
					'task_status_added_IP' => $_SERVER['REMOTE_ADDR'],
					'is_default_status' => '1'
				);
				$this->db->insert('task_status',$task_data);
				$i++;
			}
		}
		/**
                 * insert company divisions in db
                 */
		$divisions_data = array(
			'devision_title' => 'General',
			'company_id' => $company_id,
			'devision_status' => 'Active',
			'date_added' => date('Y-m-d H:i:s'),
                        'seq'=>'1'
		);
		$this->db->insert('company_divisions',$divisions_data);
		$division_id = $this->db->insert_id();
		/**
                 * insert company departments in db
                 */
		$department_data = array(
			'department_title' => 'General',
			'deivision_id' => $division_id,
			'company_id' => $company_id,
			'status' => 'Active',
			'dept_added_date' => date('Y-m-d H:i:s'),
                        'department_seq'=>'1'
		);
		$this->db->insert('company_departments',$department_data);
		
		$colors = get_colors();
		if($colors){
			$i = 1;
			foreach($colors as $col){
				$color_data = array(
					'color_id' => $col->color_id,
					'user_id' => $user_id,
					'color_name' => $col->color_name,
					'name' => $col->color_name,
					'color_code' => $col->color_code,
					'outside_color_code' => $col->outside_color_code,
					'seq' => $i,
					'status' => 'Active',
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('user_colors',$color_data);
				$i++;
			}
		}
		/**
                 * insert deafult calender setting in db
                 */
		$cal_set_data = array(
			'comapny_id' => $company_id,
			'user_id' => $user_id,
			'fisrt_day_of_week' => 'Monday',
			'MON_hours' => '480',
			'TUE_hours' => '480',
			'WED_hours' => '480',
			'THU_hours' => '480',
			'FRI_hours' => '480',
			'SAT_hours' => '0',
			'SUN_hours' => '0',
			'MON_closed' => '1',
			'TUE_closed' => '1',
			'WED_closed' => '1',
			'THU_closed' => '1',
			'FRI_closed' => '1',
			'SAT_closed' => '0',
			'SUN_closed' => '0'
		);
		$this->db->insert('default_calendar_setting',$cal_set_data);
		
		$cal_set_data_user = array(
			'comapny_id' => '0',
			'user_id' => $user_id,
			'fisrt_day_of_week' => 'Monday',
			'MON_hours' => '480',
			'TUE_hours' => '480',
			'WED_hours' => '480',
			'THU_hours' => '480',
			'FRI_hours' => '480',
			'SAT_hours' => '0',
			'SUN_hours' => '0',
			'MON_closed' => '1',
			'TUE_closed' => '1',
			'WED_closed' => '1',
			'THU_closed' => '1',
			'FRI_closed' => '1',
			'SAT_closed' => '0',
			'SUN_closed' => '0'
		);
		$this->db->insert('default_calendar_setting',$cal_set_data_user);
		/**
                 * insert swimlanes in db
                 */
		$swimlane_data = array(
			'user_id' => $user_id,
			'swimlanes_name' => 'default',
			'swimlanes_desc' => 'default',
			'seq' => '1',
                        'is_default'=>'1',
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('swimlanes',$swimlane_data);
		
		
		$last_remember_data = array(
			'user_id' => $user_id,
			'sidbar_collapsed'=>'0',
			'kanban_project_id' => 'all',
			'calender_project_id' => 'all',
			'task_status_id' => 'all',
			'due_task' => 'all',
			'kanban_team_user_id' => $user_id,
			'calender_team_user_id' =>$user_id,
			'show_cal_view' => '1',
			'calender_sorting' => '1',
			'last_calender_view' => '1',
			'user_color_id' =>'0'
		);
		$this->db->insert('last_remember_search',$last_remember_data);
		
                $this->db->select('swimlanes_id');
                $this->db->from('swimlanes');
                $this->db->where('user_id',$user_id);
                $this->db->where('is_deleted','0');
                $query = $this->db->get();
                $default_swimlanes_id = $query->row()->swimlanes_id;
                
                $json_path = base_url().'default/json/By_default_task.json';
		$file = file_get_contents($json_path);
                $default_task = json_decode($file);
                $task_status = get_taskStatus($company_id,'Active');   
                $admin_color = get_colors_admin($user_id);
                $default_steps = array(
                                "1" => "Tick the box to complete the step",
                                "2" => "Step 2",
                                "3" => "Step 3"
                );
                $i = 1;
                foreach ($default_task as $task){
                    
                    foreach ($task_status as $status){
                            if($task->task_status == $status->task_status_name){
                                $task->task_status_id =$status->task_status_id;
                            }
                    }
                    
                    $monday= date("Y-m-d",strtotime('monday this week'));
                    $tuesday= date("Y-m-d",strtotime($monday . "+1 days"));
                    $wednesday = date("Y-m-d",strtotime($monday . "+2 days"));
                    if($task->task_due_date == 'Monday'){
                        $task->task_due_date = $monday;
                        $task->task_schedule_date = $monday;
                    }elseif($task->task_due_date == 'Tuesday'){
                        $task->task_due_date = $tuesday;
                        $task->task_schedule_date = $tuesday;
                    }else{
                        $task->task_due_date = $wednesday;
                        $task->task_schedule_date = $wednesday;
                    }
                    $data = array(
				'task_company_id' => $company_id,
				'master_task_id' => '0',
				'task_title' => $task->task_title,
                                'task_description' => $task->task_description,
				'task_priority' => $task->task_priority,
				'task_due_date' => $task->task_due_date,
				'task_scheduled_date' => $task->task_schedule_date,
				'task_orig_scheduled_date' => '0000-00-00',
				'task_orig_due_date' => '0000-00-00',
				'task_owner_id' => $user_id,
				'task_allocated_user_id' => $user_id,
				'task_status_id' => $task->task_status_id,
				'subsection_id' => '0',
				'section_id' => '0',
				'task_project_id' => '0',
                                'task_time_estimate'=>$task->task_estimate_time,
                                'task_time_spent' => $task->task_spent_time,
				'task_added_date' => date('Y-m-d H:i:s'),
                                );
						
		    $this->db->insert('tasks',$data);
                    $task_id = $this->db->insert_id();
                    foreach($admin_color as $color){
                        if($task->task_color == $color->name){
                            $task->task_color = $color->user_color_id;
                         }
                     }
                     
                    $data1 = array(
                                    "user_id"=>$user_id,
                                    "task_id"=>$task_id,
                                    "swimlane_id"=>$default_swimlanes_id,
                                    "color_id"=>$task->task_color,
                                    "calender_order"=> $i,
                                    "kanban_order"=>$i,
                                    "task_ex_pos"=>'1'
                                    
                    );
                    $this->db->insert('user_task_swimlanes',$data1); 
                    
                    if($task->steps == 'TRUE'){
                        foreach($default_steps as $key => $value){
						$step_data = array(
							'task_id' => $task_id,
							'step_title' => $value,
							'step_added_by' => $user_id,
							'is_completed' => '0',
							'step_sequence' => $key,
							'step_added_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('task_steps',$step_data);
                    }
                    }
                    if($task->comment == 'TRUE'){
                        $data2 = array(
                                    'task_comment' => "First comment",
                                    'task_id' => $task_id,
                                    'project_id' => '0',
                                    'comment_addeby' => $user_id,
                                    'comment_added_date' => date('Y-m-d H:i:s')
                                    );

                        $this->db->insert('task_and_project_comments',$data2);
                    }
                    
                    
                    $i++;
                }
                if($access_mode !='web'){
                    $user_name = $this->input->post('first_name').' '.$this->input->post('last_name');
                    $email_to = $this->input->post('email');

                    $email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='New subscription has been verified'");
                    $email_temp=$email_template->row();	
                    $email_address_from=$email_temp->from_address;
                    $email_address_reply=$email_temp->reply_address;
                    $sandgrid_id=$email_temp->sandgrid_id;
                    $email_subject=$email_temp->subject;				
                    $email_message=$email_temp->message;
                    $subscription_link = site_url();

                    $email_message=str_replace('{break}','<br/>',$email_message);
                    $email_message=str_replace('{user_name}',$user_name,$email_message);
                    $email_message=str_replace('{email}',$email_to,$email_message);


		$data = array('subject'=>'New subscription has been verified');
                    $str=$email_message;
                    if($sandgrid_id){
                    mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,$user_name,$email_subject,$sandgrid_id,$data);
                    }else{
                        email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                    }
                }else{
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
                    
                    $email_to = $this->input->post('email');
					
					
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
                }
                                
		return $company_id;
	}

        /**
         * It will verify company mail in DB.
         * @param  $email
         * @returns string
         */
	function chk_verify($email){
		$query = $this->db->get_where('company',array('company_email'=>$email));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->verify_email;
		}
	}
	
        /**
         * This function will insert company details in DB and returns company_id.
         * @param  $email
         * @returns int
         */
	function insert_company($email){
		$data = array(
			'company_email' => $email,
			'company_register_date' => date('Y-m-d H:i:s'),
			'verify_email' => '1',
			'company_register_IP' => $_SERVER['REMOTE_ADDR']
		);
		$this->db->insert('company',$data);
		$company_id = $this->db->insert_id();
		return $company_id;
	}
	
        /**
         * This method will update company information in DB.By using S3 library, it will update image on server after that it will update user information in DB.
         * @return int
         */
	function updateCompany(){
		
			$msg = '';
			$company_logo='';
			$s3_company_logo = '';
                        /**
                         * Check company_logo name empty or not
                         */
	        if($_FILES['company_logo']['name']!='')
	        {
	        	$this->load->library('upload');
	            $rand=rand(0,100000); 
				  
		        $_FILES['userfile']['name']     =   $_FILES['company_logo']['name'];
		        $_FILES['userfile']['type']     =   $_FILES['company_logo']['type'];
		        $_FILES['userfile']['tmp_name'] =   $_FILES['company_logo']['tmp_name'];
		        $_FILES['userfile']['error']    =   $_FILES['company_logo']['error'];
		        $_FILES['userfile']['size']     =   $_FILES['company_logo']['size'];
	   
				$config['file_name'] = $rand.'Company';
				
	            $config['upload_path'] = base_path().'upload/company_orig/';
				
	            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
	 			
	            $this->upload->initialize($config);
	 
	            if (!$this->upload->do_upload())
				{
					echo $error =  $this->upload->display_errors();
				  
				} 
				$picture = $this->upload->data();
			   
	            $this->load->library('image_lib');
			   
	            $this->image_lib->clear();
			   	
			   	$gd_var='gd2';
				
				list($width, $height, $type, $attr) = getimagesize($_FILES['company_logo']['tmp_name']);
				
				$new_width = (($width*40)/$height);
					
				$this->image_lib->initialize(array(
					'image_library' => $gd_var,
					'source_image' => base_path().'upload/company_orig/'.$picture['file_name'],
					'new_image' => base_path().'upload/company/'.$picture['file_name'],
					'maintain_ratio' => TRUE,
					'quality' => '100%',
					'width' => $new_width,
					'height' => 40
				 ));
				
				
				if(!$this->image_lib->resize())
				{
					$error = $this->image_lib->display_errors();
				}
				$new_image = $this->image_lib->new_image;
			 
		
				$bucket = $this->config->item('bucket_name');
			
	          	$name = $_FILES['company_logo']['name'];
				$size = $_FILES['company_logo']['size'];
				$tmp = $_FILES['company_logo']['tmp_name'];
				$ext = getExtension($name);
				
				$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
				if(in_array($ext,$valid_formats)){
					
					
					$s3_company_logo = $rand.'Company.'.$ext;
				    $actual_image_name = "upload/company_orig/".$s3_company_logo;
					$new_actual_image_name = "upload/company/".$s3_company_logo;
		 			
					if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, CI_S3::ACL_PUBLIC_READ) )
					{
						if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, CI_S3::ACL_PUBLIC_READ)){
							if(file_exists(base_path().'upload/company/'.$picture['file_name']))
							{
								$link=base_path().'upload/company/'.$picture['file_name'];
								unlink($link);
							}
						}
						if(file_exists(base_path().'upload/company_orig/'.$picture['file_name']))
						{
							$link=base_path().'upload/company_orig/'.$picture['file_name'];
							unlink($link);
						}
						if($this->input->post('hdn_company_logo')!='')
						{
							$delete_image_name = "upload/company_orig/".$this->input->post('hdn_company_logo');
							$delete_image_name1 = 'upload/company/'.$this->input->post('hdn_company_logo');
							
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
				if($this->input->post('hdn_company_logo')!='')
				{
					$s3_company_logo=$this->input->post('hdn_company_logo');
				}
			}		
				
			$data = array(
				'company_name' => htmlspecialchars($this->input->post('company_name')),
				'company_phoneno' => $this->input->post('company_phoneno'),
				'company_email' => $this->input->post('company_email'),
				'country_id' => $this->input->post('country_id'),
				'company_logo' => $s3_company_logo,
				'company_address' => $this->input->post('company_address')
			);
			$this->db->where('company_id',$this->input->post('company_id'));
			$this->db->update('company',$data);
		
			
		
		return 1;
		
		
	}
        /**
         * This method will update company date & time in DB.
         * @return int
         */

	function updateCompany2(){
		$data = array(
				'company_date_format' => $this->input->post('company_date_format'),
				'company_timezone' => $this->input->post('company_timezone')
			);
			$this->db->where('company_id',$this->input->post('company_id'));
			$this->db->update('company',$data);
		
			
		return 1;
	}

        /**
         * It will check existence of company name in DB.
         * @param  $str
         * @return boolean
         */
	function company_unique($str)
	{
		if($this->input->post('company_id'))
		{
			$query = $this->db->query("select company_name from ".$this->db->dbprefix('company')." where company_name = '$str' and company_id!='".$this->input->post('company_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select company_name from ".$this->db->dbprefix('company')." where company_name= '$str' and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
        /**
         * It will check company mail in DB.
         * @param $str
         * @return boolean
         */
	function company_email_unique($str)
	{
		if($this->input->post('company_id'))
		{
			$query = $this->db->query("select company_email from ".$this->db->dbprefix('company')." where company_email = '$str' and company_id!='".$this->input->post('company_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select company_email from ".$this->db->dbprefix('company')." where company_email = '$str' and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}

        /**
         * It will update company calendar setting in DB according to admin.
         * @return int
         */
	function updateCalenderSettings(){
		$data = array(
			'fisrt_day_of_week' => $this->input->post('fisrt_day_of_week'),
			'MON_hours' => $this->input->post('MON_hours_min'),
			'TUE_hours' => $this->input->post('TUE_hours_min'),
			'WED_hours' => $this->input->post('WED_hours_min'),
			'THU_hours' => $this->input->post('THU_hours_min'),
			'FRI_hours' => $this->input->post('FRI_hours_min'),
			'SAT_hours' => $this->input->post('SAT_hours_min'),
			'SUN_hours' => $this->input->post('SUN_hours_min'),
			'MON_closed' => $this->input->post('MON_closed'),
			'TUE_closed' => $this->input->post('TUE_closed'),
			'WED_closed' => $this->input->post('WED_closed'),
			'THU_closed' => $this->input->post('THU_closed'),
			'FRI_closed' => $this->input->post('FRI_closed'),
			'SAT_closed' => $this->input->post('SAT_closed'),
			'SUN_closed' => $this->input->post('SUN_closed')
		);
		
		$this->db->where('comapny_id',$this->session->userdata('company_id'));
		
		$this->db->update('default_calendar_setting',$data);
		return 1;
	}
	
	/**
         * This function will update company task settings.It will update actual time and allow to add task in past.
         * @returns int
         */
	function updateTaskSettings(){
		if($this->input->post('actual_time_on')){
			$actual_time_on = "1";
		} else {
			$actual_time_on = "0";
		}
		
		if($this->input->post('allow_past_task')){
			$allow_past_task = "1";
		} else {
			$allow_past_task = "0";
		}
		
		$data = array(
			'actual_time_on' => $actual_time_on,
			'allow_past_task' => $allow_past_task
		);
		
		$this->db->where('company_id',$this->session->userdata('company_id'));
		$this->db->update('company',$data);
		return 1;
	}
	
	/**
         * This function will insert default calender settings in db.
         * @returns int
         */
	function insertCalenderSettings(){
		$data = array(
			'comapny_id' => $this->session->userdata('company_id'),
			'user_id' => $this->session->userdata('user_id'),
			'fisrt_day_of_week' => $this->input->post('fisrt_day_of_week'),
			'MON_hours' => $this->input->post('MON_hours_min'),
			'TUE_hours' => $this->input->post('TUE_hours_min'),
			'WED_hours' => $this->input->post('WED_hours_min'),
			'THU_hours' => $this->input->post('THU_hours_min'),
			'FRI_hours' => $this->input->post('FRI_hours_min'),
			'SAT_hours' => $this->input->post('SAT_hours_min'),
			'SUN_hours' => $this->input->post('SUN_hours_min'),
			'MON_closed' => $this->input->post('MON_closed'),
			'TUE_closed' => $this->input->post('TUE_closed'),
			'WED_closed' => $this->input->post('WED_closed'),
			'THU_closed' => $this->input->post('THU_closed'),
			'FRI_closed' => $this->input->post('FRI_closed'),
			'SAT_closed' => $this->input->post('SAT_closed'),
			'SUN_closed' => $this->input->post('SUN_closed')
		);
		$this->db->insert('default_calendar_setting',$data);
		return 1;
	}
	/**
         * This function get company user list from DB.
         * @param string $email 
         * @returns int
         */
	function getUserCompanyList($email){
		$query = $this->db->select("u.company_id,c.company_name,u.first_name,u.last_name")
							->from("users u")
							->join("company c","c.company_id = u.company_id",'left')
							->where("u.email",$email)
							->where("u.is_deleted","0")
                                                        ->where("c.is_deleted","0")
							->where("u.user_status","Active")
							->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
        
        
        /************* Below methods are related to mobile APIS ****************/
        /**
         * this method is used for get company list using user email.
         * @param type $email
         * @return int
         */
        function getUserCompanyList1($email,$password){
		$query = $this->db->select("u.company_id,c.company_name,u.first_name,u.last_name,u.user_id")
							->from("users u")
							->join("company c","c.company_id = u.company_id",'left')
							->where("u.email",$email)
                                                        ->where("u.password",$password)
							->where("u.is_deleted","0")
							->where("u.user_status","Active")
							->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
        
        /**
         * This method is checked user authentication in db.
         * @param type $company_id
         * @param type $email
         * @param type $password
         * @return int
         */
        function check_login1($email,$password){
            $query = $this->db->select("u.company_id,c.company_name,u.first_name,u.last_name,u.user_id")
							->from("users u")
							->join("company c","c.company_id = u.company_id",'left')
							->where("u.email",$email)
                                                        ->where("u.password",md5($password))
							->where("u.is_deleted","0")
							->where("u.user_status","Active")
							->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
        }
        
        
        /****** End Api methods here.******/
}

?>
