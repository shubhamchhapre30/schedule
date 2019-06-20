<?php

/**
 * This is a base class of admin panel.This class is used to create login page for admin panel and it's checking authentication of admin,it generates admin list and settings.
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Admin extends  CI_Controller {
    /**
     * This is a constructor of admin class, this function is used for load and config classes for admin class.
     * @returns void 
     */
	function Admin()
	{
            /*
             * call parent class constructor
             */
		 parent::__construct();	
                 /* load Amazon s3 library file*/
		$this->load->library('s3');
                /* config file amazon s3 */
		$this->config->load('s3');
                /* databasse of admin class */
		$this->load->model('admin_model');
		$this->adminRights=getadminRights();
		(count($this->adminRights)==0 && !checkSuperAdmin())?redirect('home/dashboard/noRights'):'';
		$this->adminRights=(object)getadminRights();
		
	}
	/**
         * This function is default function of admin class.it's called by default.It checks admin authentication also.
         * @returns void
         */
	function index()
	{
            /**
             * check user authentications and redirect on home
             */
		if(!check_admin_authentication())
		{
			redirect('home');
		}
                /* if admin is not authenticated than it will redirect on list_admin function of this admin class*/
		redirect('admin/list_admin');
		
		
		
	}
	
	// Use :This function use for Lost all admin User.
	// Param :limit,offset,message
	// Return :'N/A'
	/**
         * This function is used for show admin list.It checks admin authentication and get admin related data for generates listadmin page.
         * @param  $limit
         * @param  $offset
         * @param  $msg
         * @returns void
         */
	function list_admin($limit='20',$offset=0,$msg='')
	{
		/*
                 * check admin authentication
                 */
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
		
		$check_rights=get_rights('list_admin');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$this->load->library('pagination');

		
		$config['uri_segment']='4';
	//	$config['base_url'] = base_url().'admin/list_admin/'.$limit.'/';
		$config['base_url'] =site_url("admin/list_admin/".$limit);
		$config['total_rows'] = $this->admin_model->get_total_admin_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->admin_model->get_admin_result($offset,$limit);
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
		$data['redirect_page']='list_admin';
	
		
		
		
		
		$data['site_setting'] = site_setting();

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->admin) && $this->adminRights->admin->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/admin/listAdmin',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		
	}
	
	// Use :This function use for list admin by filter.
	// Param :limit,option,keyword,offset,message
	// Return :'N/A'
	/**
         * This function is used for filter admin list with user inputs for search and keyboard.
         * @param int $limit
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function search_list_admin($limit=20,$option='',$keyword='',$offset=0,$msg='')
	{
		/* check authentication*/
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$redirect_page = 'search_list_admin';
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('search_list_admin');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		$this->load->library('pagination');
		
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
		//$config['base_url'] = base_url().'admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		
		$config['base_url'] =site_url('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/');
		
		/* this admin_model class function accroding to keyboard search data in db and returns*/
		$config['total_rows'] = $this->admin_model->get_total_search_admin_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->admin_model->get_search_admin_result($option,$keyword,$offset, $limit);
		
		
		if($data['result']==0){
		$offset=0;
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->admin_model->get_total_search_admin_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->admin_model->get_search_admin_result($option,$keyword,$offset, $limit);
		}
		
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		$data['site_setting'] = site_setting();
		
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['redirect_page']=$redirect_page;
		
	
		/* show data in listadmin page of admin*/
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->admin) && $this->adminRights->admin->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/admin/listAdmin',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	// Use :This function use for check unique UserName of admin.
	// Param :UserName
	// Return :Boolean
	/**
         * This function use for check unique UserName of admin.
         * @param  string $username
         * @returns boolean
         */
	function username_check($username)
	{
            /* this user_unique() take username and return boolean value for username unique*/
		$username = $this->admin_model->user_unique($username);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('username_check', 'There is an existing account associated with this Username');
			return FALSE;
		}
	}	
	// Use :This function use for check unique Email of admin.
	// Param :Email
	// Return :Boolean
	/**
         * This function is used for check unique Email of admin.
         * @param string $emailField
         * @returns boolean
         */
	function adminmail_check($emailField)
	{
		$username = $this->admin_model->user_email_unique($emailField);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('adminmail_check', 'There is an existing account associated with this Email');
			return FALSE;
		}
	}
	
	// Use :This function use for add new admin.
	// Param :'N/A'
	// Return :'N/A'
	/**
         * This function is used for two purpose, one for insert new data and other for update admin profile through s3 config.
         * @param int $limit
         * @returns void
         */
	
	function add_admin($limit=0)
	{
		/* check authentication of admin*/
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
		
		$check_rights=get_rights('add_admin');
		
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
                /* load form-validation library and set rules*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		
		$this->form_validation->set_rules('emailField', 'Email', 'required|valid_email|callback_adminmail_check');
		
		if($this->input->post("admin_id") == 0 || $this->input->post("admin_id") == "")
		{
		  $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[15]');
		}
			
		
		/**
                 * check form validation,if it false than it redirect on add admin page with input data 
                 */
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["first_name"] = $this->input->post('first_name');
			$data["last_name"] = $this->input->post('last_name');
			$data["admin_id"] = $this->input->post('admin_id');
			$data["email"] = $this->input->post('emailField');
			$data["username"] = $this->input->post('username');
			$data["password"] = $this->input->post('password');
			$data["login_ip"] = $this->input->post('login_ip');
			$data["admin_type"] = $this->input->post('admin_type');		
			$data["status"] = $this->input->post('status');
			$data['pre_profile_image']=$this->input->post('pre_profile_image');
			
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]='list_admin';
			
			
			$data['site_setting'] = site_setting();
			
		
			if($this->input->post('offset')=="")
			{
				$limit = '10';
				$totalRows = $this->admin_model->get_total_admin_count();
				$data["offset"] = (int)($totalRows/$limit)*$limit;
			}else{
				$data["offset"] = $this->input->post('offset');
			}
			
			/* render add admin page*/
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->admin) && $this->adminRights->admin->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/admin/addAdmin',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
			
			
		}else{
                    /*there check admin id if it's not empty than it insert new admin details in db */
				
			if($this->input->post('admin_id')!='')
			{	
				$this->admin_model->admin_update();
				$msg = "update";
				$this->session->set_flashdata('msg', $msg);
			}else{
				$this->admin_model->admin_insert();			
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
		 	
			 
			if($redirect_page == 'list_admin')
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
	
	
	// Use :This function use for edit of update admin.
	// Param :admin id,redirect page,option,keyword,limit,offset
	// Return :'N/A'
	/**
         * This function is used for edit admin detail.It get values of admin via admin id and create edit page otherwise it redirect on current page.
         * @param int $id
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @param int $limit
         * @param int $offset
         * @returns void
         */
	
	function edit_admin($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0)
	{
		/* check authentication */
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
		
		$check_rights=get_rights('add_admin');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		/* get data from db and render add admin page */
		$one_user = $this->admin_model->get_one_admin($id);
		if($one_user){
		$data["error"] = "";
		$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		
		$data["admin_id"] = $id;
		$data["redirect_page"]=$redirect_page;
		$data["email"] = $one_user['email'];
		$data["first_name"] = $one_user['first_name'];
		$data["last_name"] =$one_user['last_name'];
		//$data["username"] = $one_user['username'];
		$data["password"] = $one_user['password'];
		$data["login_ip"] = $one_user['login_ip'];
		$data["admin_type"] = $one_user['admin_type'];
		$data["status"] = $one_user['status'];
		$data['pre_profile_image']=$one_user['image'];
		
		$data['site_setting' ] = site_setting();

			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->admin) && $this->adminRights->admin->update==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/admin/addAdmin',$data,TRUE);
			}else{
			  $this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			if($redirect_page == 'list_admin')
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$offset);
			}
			elseif($redirect_page=="search_list_admin")
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset);
			}else{
				redirect('admin/list_admin');
			}
		}
	}
	
	
	// Use :This function use for Delete admin.
	// Param :admin id,redirect page,option,keyword,limit,offset
	// Return :'N/A'
	/**
         * This function is deleted admin from list via admin id.
         * @param int $id
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @param int $limit
         * @param int $offset
         * @returns void
         */
	
	function delete_admin($id=0,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*   
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('delete_admin');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
                /* delete query for admin delete*/
		//$this->db->delete('rights_assign',array('admin_id'=>$id));
		$this->db->delete('admin',array('admin_id'=>$id));
		
		$this->session->set_flashdata('msg', "delete");
		
		if($redirect_page == 'list_admin')
		{
			
			redirect('admin/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

		}
        
        /// common function for insert all action//
           
	}
	
	// Use :This function use for sending reset password to admin.
	// Param :admin id,redirect page,option,keyword,limit,offset
	// Return :'N/A'
	/**
         * This function is used for reset password for admin.this function call forgot_password function for reset password.
         * @param int $id
         * @param String $redirect_page
         * @param string $option
         * @param string $keyword
         * @param int $limit
         * @param int $offset
         * @returns void
         */
	
	function reset_password_admin($id=0,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*   
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('edit_admin');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
                /*
                 * call method of admin_model class for forget password
                 */
		$this->admin_model->forgot_password($id);
		
		
		$this->session->set_flashdata('msg', "sent");
		/**
                 * check redirect page and then redirect it.
                 */
		if($redirect_page == 'list_admin')
		{
			
			redirect('admin/'.$redirect_page.'/'.$limit.'/'.$offset.'/sent');
		}
		else
		{
			redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/sent');

		}
        
        /// common function for insert all action//
           
	}
	// Use :This function use for admin Login.
	// Param :offset,message (optional)
	// Return :'N/A'
	function admin_login($offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/* Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('admin_login');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		
		$this->load->library('pagination');

		$limit = '20';
		
		$config['base_url'] = base_url().'admin/admin_login/';
		$config['total_rows'] = $this->admin_model->get_total_adminlogin_count();
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->admin_model->get_adminlogin_result($offset, $limit);
		$data['offset'] = $offset;
		
		$data['site_setting'] = site_setting();
		
		$data['msg']=$msg;
		

		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/admin/listAdmin_login',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	// Use :This function use for change status or delete admin.
	// Param :'N/a'
	// Return :'N/A'
	/**
         * This function is used for update status and delete admin from admin list.
         * @returns void 
         */
	function action_admin()
	{
		/* Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('action_admin');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$offset=$this->input->post('offset');
		
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		
		$admin_id =$this->input->post('chk');
		
		if($action=='delete')
		{
             /// common function for insert all action//
          
               /**
                * with foreah loop delete selected id from db via delete query and redirect current page
                */
                		
			foreach($admin_id as $id)
			{			
				$this->db->query("delete from ".$this->db->dbprefix('admin')." where admin_id ='".$id."'");
			}
			
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'list_admin')
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

			}
			
		}
			
		if($action=='active')
		{
            //Log Entry		
                    /**
                     * with the help of foreach loop update admin table of active admin
                     */
                		
			foreach($admin_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('admin_id',$id);
				$this->db->update('admin', $data);
			}

            $this->session->set_flashdata('msg', "active");
			
			if($redirect_page == 'list_admin')
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{
		  //Log Entry        
                    /*
                     * there update status column for inactive admin
                     */
			foreach($admin_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('admin_id',$id);
				$this->db->update('admin', $data);
			}

			$this->session->set_flashdata('msg', "inactive");
			
			if($redirect_page == 'list_admin')
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');

			}
		}	
		
	}

  
    function assignRights($admin_id=0,$redirect_page='list_admin',$option='1V1',$keyword='1V1',$limit=20,$offset=0)
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
	
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		if($admin_id==0){
		redirect('admin');
		}else{
		$data['admin_id']=$admin_id;
		}
		$this->form_validation->set_rules('admin_id', 'Admin ID', 'required');	
		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			$data["admin_id"] = ($this->input->post('admin_id'))?$this->input->post('admin_id'):$admin_id;
			
			$data['site_setting'] = site_setting();
			
		
			if($this->input->post('offset')=="")
			{
				$limit = '10';
				$totalRows = $this->admin_model->get_total_admin_count();
				$data["offset"] = (int)($totalRows/$limit)*$limit;
			}else{
				$data["offset"] = $this->input->post('offset');
			}
			$data['all_rights']=$this->admin_model->get_all_rights();
			$admin_right=$this->admin_model->get_admin_rights($data['admin_id']);
			//echo '<pre>';
			//print_r($data['all_rights']);
			$ad_r=array();
			$rid=array();
			if($admin_right!=''){
			foreach($admin_right as $adr){
				$ad_r[]=$adr->rights_id;
				$rid[$adr->rights_id]=$adr;
			}}
			
			$data['ad_r']=$ad_r;
			$data['rid']=$rid;
			//print_r($data['ad_r']);
			//print_r($data['rid']);die;
			$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		$data["redirect_page"]=$redirect_page;
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/admin/assign_rights',$data,TRUE);
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				
			
			$this->admin_model->assigin_rights();
			$msg='rights';	
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
		 	
			 
			if($redirect_page == 'listAdmin')
			{
				redirect('admin/listAdmin/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('admin/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
	
	////////// code///
			
		 function testexls()
		{

			$file=base_path()."upload/ingredients/10077user.xls";
			 //   $file = './files/test.xlsx';
			//load the excel library
			$this->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) {
			    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			    //header will/should be in row 1 only. of course this can be modified to suit your need.
			    if ($row == 1) {
			        $header[$row][$column] = $data_value;
			    } else {
			        $arr_data[$row][$column] = $data_value;
			    }
			}
			//send the data in an array format
			$data['header'] = $header;
			$data['values'] = $arr_data;

        echo "<pre>";
        print_r($data);
        die;
	
}
	/// end code////
	
}


?>
