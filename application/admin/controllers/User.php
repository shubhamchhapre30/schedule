<?php
require_once APPPATH."libraries/chargify_lib/Chargify.php";

/**
 * This is used for get user related data and create user view page.It get detail through user_model.
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class User extends  CI_Controller {
     /**
     * This is a constructor of user class, this function is used for load and config classes for user class.
     * @returns void 
     */
	function User()
	{
            /* call base class constructor*/
		parent::__construct();	
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
                /*
                 * load pagination library
                 */
		$this->load->library('pagination');
	   
	}
	//use:for redirecting at list user page
	/**
         * This is used for check authentication and redirect to list_user function.
         * @returns void
         */
	function index()
	{
            /* check admin authentication */
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		redirect('user/list_user');
	}
	
	/* user list
	 * param  : limit,offset,msg
	 * 
	 */
	/**
         * This function is used for create user list for admin panel.this function get all data and save data in array than it generate view page.
         * @param int $limit
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function list_user($limit='20',$offset=0,$msg='') {
		/* check authentication*/
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
		
		$check_rights=get_rights('list_user');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'user/list_user/'.$limit.'/';
		$config['total_rows'] = $this->user_model->get_total_user_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->user_model->get_user_result($offset,$limit);
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
		$data['redirect_page']='list_user';
		
		$data['site_setting'] = site_setting();
                /*render list user view*/
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/user/list_user',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	/* search patitent
	 * param  : doctor id ,limit,option,keyword,offset,msg
	 * 
	 */
	/**
         * This function is used for search username in the DB.It checks admin authentication than it search username and this all data are stored in array than it will show in list form.
         * @param int $limit
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function search_list_user($limit=20,$option='',$keyword='',$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$redirect_page = 'search_list_user';
		
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
		$config['base_url'] = base_url().'user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->user_model->get_total_search_user_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		$data['all_country']=getActiveCountry();
		$data['result'] = $this->user_model->get_search_user_result($option,$keyword,$offset, $limit);
		
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		$data['site_setting'] = site_setting();
		
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['redirect_page']=$redirect_page;
		
		/* save data in array form and show in list*/
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/user/list_user',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	/**
         * It is used for check wheather user is manager or not. 
         * @returns boolean
         */
	function manager_check(){
		
		$user_id = $_POST['user_id'];
		
		if($_POST['is_manager']){
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
	
	/*add new user also called in user update
	 * param  : limit
	 * 
	 */
	/**
         * This function is used for add new user through admin panel.this function is chceked admin authentication,than it get all data from add form and insert it in DB.
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
		$check_rights=get_rights('add_user');
		$data['user_type'] ='';
		$data['msg']='';
		if($check_rights==0) {			
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
                $data['staff_list'] = '';
                /* form validation*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'Name', 'required');
		$this->form_validation->set_rules('last_name', 'Surname', 'required');
		$this->form_validation->set_rules('email', 'E-mail Address', 'required|valid_email');
		//$this->form_validation->set_rules('contact_no', 'Contact No', 'required');
		$this->form_validation->set_rules('user_time_zone', 'User Time Zone', 'required');
		
		if($_POST && $this->input->post('user_id')==""){
		    $this->form_validation->set_rules('company_id', 'Company', 'required');
                    if($this->input->post('passwordReset')=='0'){
                        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[15]');
                    }
		}
		
		$this->form_validation->set_rules('user_status', 'Status', 'required');
                if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			$data["user_id"] = $this->input->post('user_id');
			$data["first_name"] = $this->input->post('first_name');
			$data["last_name"] = $this->input->post('last_name');
			$data["email"] = $this->input->post('email');
			$data["password"] = $this->input->post('password');
			//$data["contact_no"] = $this->input->post('contact_no');
			$data["staff_level"] = '';
			$data["user_time_zone"] = $this->input->post('user_time_zone');
			$data["is_administrator"] = $this->input->post('is_administrator');
			$data["is_owner"] = $this->input->post('is_owner');
			$data["is_manager"] = $this->input->post('is_manager');
			$data["company_id"] = '0';
			$data["country_id"] = $this->input->post('country_id');
			$data["user_status"] = $this->input->post('user_status');
			$data["prev_profile_image"] = $this->input->post('prev_profile_image');
			
			//$data["business"] = $this->input->post('business');
			
						
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]='list_user';
			$data['site_setting'] = site_setting();
		
			if($this->input->post('offset')=="")
			{
				$limit = '10';
				$totalRows = $this->user_model->get_total_user_count();
				$data["offset"] = (int)($totalRows/$limit)*$limit;
			}else{
				$data["offset"] = $this->input->post('offset');
			}
			$data["timezone"] = get_timezone();
			//echo "<pre>";print_r($data);exit;
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/user/add_user',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				
			if($this->input->post('user_id')!='')
			{
				
                                if($this->input->post('is_own')=='1')
				{
					
					$test = TRUE;
                                        /**
                                         * create new account for payment
                                         */
					$customer = new ChargifyCustomer(NULL, $test);
					//$customer->id = $customers[0]->id;
					$cus=get_all_data('users','user_id',$this->input->post('user_id'));
					
					if($cus['chargify_customer_id'])
					{
				    try{
					$customer->id =$cus['chargify_customer_id'];
					$customer->first_name = $this->input->post('first_name');
					$customer->last_name = $this->input->post('last_name');
					$customer->email = $this->input->post('email');
					//echo '<pre>';
					//print_r($customer);	die;
					$customer = $customer->update();	
					}catch (ChargifyValidationException $cve) { 
						 echo $data["error"]=$cve->getMessage(); //die;die;
					}
					///echo "hello";die;
					}
				}
					
					
				$this->user_model->user_update($_POST['user_id']);
				$msg = "update";
				$this->session->set_flashdata('msg', $msg);
			}else{
				
				$this->user_model->user_insert();

		
				$msg = "insert";
				$this->session->set_flashdata('msg', $msg);
			}
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
			
			

			/*
                         * check redirect_page name and redirect 
                         */
			if($redirect_page == 'list_user')
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
	
	/**
         * This function check  email in db for it's unique.
         * @param string $email
         * @returns boolean
         */
	function email_check($email)
	{
		$username = $this->user_model->user_email_unique($email);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('email_check', 'There is an existing Email id associated with this Name');
			return FALSE;
		}
	}	
	/*user update form fill
	 * param  : user id,doctor id ,redirect page,option,keyword,limit,offset
	 * 
	 */
	/**
         * This function is used when user will click on edit option of userlist.it get one user details from DB and update new data with old data.
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
		$check_rights=get_rights('add_user');
		$data['all_country']=getActiveCountry();
		$data['msg']=$msg;
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		/* get all details form get_one_user() in DB*/
		$one_user = $this->user_model->get_one_user($id);
	//	echo '<pre>';print_r($one_user);die;
		if($one_user){
			$data["error"] = "";
			$data["limit"]=$limit;
			$data["offset"]=$offset;
			$data["option"]=$option;
			$data["keyword"]=$keyword;
			$data["search_option"]=$option;
			$data["search_keyword"]=$keyword;
			
			$data["user_id"] = $id;
			
			$data["user_id"] = $one_user['user_id'];
			$data["first_name"] = $one_user['first_name'];
			$data["last_name"] = $one_user['last_name'];
			$data["email"] = $one_user['email'];
			$data['user_time_zone'] = $one_user['user_time_zone'];
			$data["company_id"] = $one_user['company_id'];
			$data["staff_level"] = $one_user['staff_level'];
			$data["country_id"] = $one_user['country_id'];
			//$data["contact_no"] = $one_user['contact_no'];
			$data["is_administrator"] = $one_user['is_administrator'];			
			$data["is_owner"] = $one_user['is_owner'];
			$data["is_manager"] = $one_user['is_manager'];
			/*$data["post_code"] = $one_user['post_code'];*/
			$data["prev_profile_image"] = $one_user['profile_image'];
			$data["user_status"] = $one_user['user_status'];
			$data['staff_list'] = get_staff_levels($one_user['company_id']);
			$data["timezone"] = get_timezone();
			$data["redirect_page"]=$redirect_page;
			$data['site_setting'] = site_setting();
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/user/add_user',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			if($redirect_page == 'list_user')
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			elseif($redirect_page=='search_list_user')
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}else{
				redirect('user/list_user');
			}
		}
	}
	/**
         * This function is used for get payment/chargify id form DB.
         * @param int $compant_id
         * @returns int
         */
	function get_component_id_fom_company($compant_id){
		$query = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$compant_id)->where("c.is_deleted","0")->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->chargify_component_id;
		} else {
			return 0;
		}
	}
	
	/*delete user data
	 * param  : user id,doctor id ,option,keyword,limit,offset,msg
	 * 
	 */
	/**
         * This function is used for delete user from the list.
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
		$check_rights=get_rights('list_user');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		//$this->db->delete('rights_assign',array('store_id'=>$id));
		//$this->db->where('user_id',$id);
		//$this->db->delete('user');
		
				$query=$this->db->get_where('users',array('user_id'=>$id));
				$use=$query->row();
						
				if($use->is_administrator !='1')
				{	
					$this->db->where("user_id",$id);
					$this->db->update("users",array("is_deleted"=>1));
				}
				
				$component_id = $this->get_component_id_fom_company($use->company_id);
		
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				//$Qty->allocated_quantity = "1";
				/*$Qty1 = $Qty->getAll($company->chargify_subscriptions_ID,"86260");
				$old_qt=$Qty1->allocated_quantity;
				$new_qty=$old_qt-1;*/
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
        
		//$this->db->delete('user',array('store_id'=>$id));
		if($redirect_page == 'list_user')
		{
			redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
		}
	}
	
	/* Send customer reset password
	 * param  : user id ,option,keyword,limit,offset,msg
	 * 
	 */
	/**
         * This function is used for reset user password from userlist.this function will send mail for reset password link.
         * @param int $id
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @param int $limit
         * @param int $offset
         * @returns void
         */
	function reset_password_user($id=0,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_user');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		//$this->db->delete('rights_assign',array('store_id'=>$id));
		//$this->db->where('user_id',$id);
		//$this->db->delete('user');
                /* this function use for send forget password link*/
		$this->user_model->forgot_password($id);
		
		
		$this->session->set_flashdata('msg', "sent");
        
		//$this->db->delete('user',array('store_id'=>$id));
		if($redirect_page == 'list_user')
		{
			redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/sent');
		}
		else
		{
			redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/sent');
		}
	}
	
	/*delete , active , inactive multiple user at a time
	 * param  : user id,doctor id ,redirect page,search option,search keyword,limit,offset,msg
	 * 
	 */ 
	/**
         * This function is used for set multiple user in active,inactive or delete.and this function according to option update chargify detail of user.
         * @returns void
         */
	function action()
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_user');
		
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
		/* here it will check action option is delete,than it update userm table for delete*/	
		if($action=='delete')
		{
			foreach($user_id as $id)
			{
				$query=$this->db->get_where('users',array('user_id'=>$id));
				$use=$query->row();
						
				if($use->is_administrator !='1')
				{	
				$this->db->where("user_id",$id);
		        $this->db->update("users",array("is_deleted"=>1));		
				//$this->db->query("delete from ".$this->db->dbprefix('user')." where store_id ='".$id."'");
				}else{
					$this->session->set_flashdata('msg', "no_delete");
					if($redirect_page == 'list_user')
					{
						redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/no_delete');
					}
					else
					{
						redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/no_delete');
					}
				}
				
				$component_id = $this->get_component_id_fom_company($use->company_id);
				
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				//$Qty->allocated_quantity = "1";
				/*$Qty1 = $Qty->getAll($company->chargify_subscriptions_ID,"86260");
				$old_qt=$Qty1->allocated_quantity;
				$new_qty=$old_qt-1;*/
				if(isset($company->chargify_subscriptions_ID) && $company->chargify_subscriptions_ID != '')
				{
				try{	
				$new_qty=count_user_by_company($use->company_id);
				$Qty->allocated_quantity = $new_qty;
				$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
				}catch (ChargifyValidationException $cve) { 
				 echo $data["error"]=$cve->getMessage(); //die;die;
				}
				}
			}
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'list_user')
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
			}
		}
		/**
                 * here active option is on than it will select id's user status set active.
                 */	
		if($action=='active')
		{
			foreach($user_id as $id)
			{
							
				$data = array('user_status'=>'active');
				$this->db->where('user_id',$id);
				$this->db->update('users', $data);
				
				$query=$this->db->get_where('users',array('user_id'=>$id));
				$use=$query->row();
				
				$component_id = $this->get_component_id_fom_company($use->company_id);
				
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				//$Qty->allocated_quantity = "1";
				/*$Qty1 = $Qty->getAll($company->chargify_subscriptions_ID,"86260");
				$old_qt=$Qty1->allocated_quantity;
				$new_qty=$old_qt-1;*/
				
				/*print_r($company);
				print_r($Qty);
				die;*/
				
				
				if($company->chargify_subscriptions_ID != '')
				{
				try{	
				$new_qty=count_user_by_company($use->company_id);
				$Qty->allocated_quantity = $new_qty;
				
				//print_r($new_qty);die;
				$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
				//print_r($Qt);die;
				
				}catch (ChargifyValidationException $cve) { 
				 echo $data["error"]=$cve->getMessage(); //die;die;
				}
				}
		
		
		//print_r($Qty1);echo '<br>';die;
				
				
			}
			
			$this->session->set_flashdata('msg', "active");
			if($redirect_page == 'list_user')
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{
			foreach($user_id as $id)
			{
				$query=$this->db->get_where('users',array('user_id'=>$id));
				$use=$query->row();
					
				if($use->is_administrator !='1')
				{		
				$data = array('user_status'=>'inactive');
				$this->db->where('user_id',$id);
				$this->db->update('users', $data);
				}
				
				$component_id = $this->get_component_id_fom_company($use->company_id);
				
				$query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
				$company=$query1->row();
				
				$test = TRUE;
				$Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				//$Qty->allocated_quantity = "1";
				/*$Qty1 = $Qty->getAll($company->chargify_subscriptions_ID,"86260");
				$old_qt=$Qty1->allocated_quantity;
				$new_qty=$old_qt-1;*/
				if($company->chargify_subscriptions_ID !='')
				{
					try{
				$new_qty=count_user_by_company($use->company_id);
				$Qty->allocated_quantity = $new_qty;
				$Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
				}catch (ChargifyValidationException $cve) { 
				 echo $data["error"]=$cve->getMessage(); //die;die;
				}
				}
			}
			
			$this->session->set_flashdata('msg', "inactive");
			if($redirect_page == 'list_user')
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('user/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');
			}
		}	
	}
        /**
         * This function delete image from server through s3 configuration.
         * @param int $user_id
         * @param string $image
         * @param int $limit
         * @param int $offset
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @returns void
         */
	function removeimage($user_id,$image,$limit,$offset,$redirect_page,$option,$keyword)
	{
		//echo "sdfsdf";die;
		if($image!='')
		{
			$this->db->where("user_id",$user_id);
	        $this->db->update("users",array("profile_image"=>''));	
			
			$bucket = $this->config->item('bucket_name');
			
			
			$delete_image_name = "upload/user_orig/".$image;
			$delete_image_name1 = 'upload/user/'.$image;
			
			if($this->s3->getObjectInfo($bucket,$delete_image_name)){
				$this->s3->deleteObject($bucket,$delete_image_name);
			}
			if($this->s3->getObjectInfo($bucket,$delete_image_name1)){
				$this->s3->deleteObject($bucket,$delete_image_name1);
			}
			
			
		}
		$msg='image_remove';
		redirect('user/edit/'.$user_id.'/'.$redirect_page.'/1V1/1V1/'.$limit.'/'.$offset.'/'.$msg);
	}	
	/**
         * This get staff list from DB.
         * @param int $id
         * @returns void
         */
	function get_staff($id)
	{
		
		$data=$this->user_model->get_staff($id);
		//print_r($data);die();
		// print form_dropdown('product_type',$data['product_type']);
		
		echo json_encode($data);
		 
	
		
		
	}
	/**
         * In ajax request,this function get all user list form DB.
         * @returns void
         */
	function userCompanyList(){
		$theme = getThemeName();
		$email = $_POST['email'];
		$data['users_company'] = $this->user_model->getUserCompanyList($email);
		$data['comapny'] = $this->user_model->getCompanyList($email);
		echo $this->load->view($theme.'/layout/user/ajax_company_list',$data,TRUE);
	}
	/**
         * This function get user details from DB through user mail id.
         * @returns void
         */
	function checkUser()
	{
		$email = $_POST['email'];
		$data['user_detail'] = $this->user_model->getUserDetail($email);
		echo json_encode($data['user_detail']);
		
	}
	
}


?>
