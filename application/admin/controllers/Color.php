<?php
/**
 * This class  create color page for admin panel.This class function create color list & there we can add new colors.   
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Color extends  CI_Controller {
     /**
        * It default constuctor which is called when Color class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */ 
	function Color()
	{
            /**
             * call base class contructor
             */
		parent::__construct();	
                /* load color class model */
		$this->load->model('color_model');
                /* load library pagination */
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
		redirect('Color/list_color');
	}
	
	/* user list
	 * param  : limit,offset,msg
	 * 
	 */
	/**
         * This function create color list for admin panel and it's have option for add new color,delete & update color functionality.
         * @param int $limit
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function list_color($limit='20',$offset=0,$msg='') {
		
		/* check admin authentication */
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
		
		$check_rights=get_rights('list_color');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'Color/list_color/'.$limit.'/';
                /* get total color*/
		$config['total_rows'] = $this->color_model->get_total_color_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		/* color details*/
		$data['result'] = $this->color_model->get_color_result($offset,$limit);
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
		$data['redirect_page']='list_color';
		
		$data['site_setting'] = site_setting();
                /* create color list*/
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/color/list_color',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	/* search patitent
	 * param  : doctor id ,limit,option,keyword,offset,msg
	 * 
	 */
	/**
         * This function is used when user search color by name in search box.This function check authentication and get color data for create list of color.
         * @param int $limit
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function search_list_color($limit=20,$option='',$keyword='',$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$redirect_page = 'search_list_color';
		
		
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
		
		//$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
	
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->color_model->get_total_search_color_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
                /* get search color info*/
		$data['result'] = $this->color_model->get_search_color_result($option,$keyword,$offset, $limit);
		
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		$data['site_setting'] = site_setting();
		
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['redirect_page']=$redirect_page;
		/* create list*/
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/color/list_color',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}

	/*add new user also called in user update
	 * param  : limit
	 * 
	 */
	/**
         * This function have two functionality update and insert color in DB.It check color_is is exist or not than it will insert/update colors table.
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
                /* load and set rules of form validation*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules('color_name', 'Color Name', 'required|callback_color_name_check');
		$this->form_validation->set_rules('color_code', 'Inside Color Code', 'required|callback_color_code_check');
		$this->form_validation->set_rules('outside_color_code', 'Outside Color Code', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		
		/*check form validation */
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			$data["color_id"] = $this->input->post('color_id');
			$data["color_name"] = $this->input->post('color_name');
			$data["color_code"] = $this->input->post('color_code');
			$data["outside_color_code"] = $this->input->post('outside_color_code');
			$data["status"] = $this->input->post('status');
			
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]='list_color';
			$data['site_setting'] = site_setting();
		
			if($this->input->post('offset')=="")
			{
				$limit = '10';
				$totalRows = $this->color_model->get_total_color_count();
				$data["offset"] = (int)($totalRows/$limit)*$limit;
			}else{
				$data["offset"] = $this->input->post('offset');
			}
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/color/add_color',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
			
		}else{
				
			if($this->input->post('color_id')!='')
			{
                            /* if color_id set than color update here*/
				$this->color_model->color_update($_POST['color_id']);
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
				
				if($redirect_page == 'list_color')
				{
					
					redirect('Color/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
				}
				else
				{
					
					redirect('Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
				}
			}else{
					/* color id not exist than it insert*/	
				$this->color_model->color_insert();		
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
	
				if($redirect_page == 'list_color')
				{
					
					redirect('Color/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
				}
				else
				{
					
					redirect('Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
				}
			}
			
		}			

	}
	/**
         * This function check color name exist or not.
         * @param  $color_code
         * @returns boolean
         */
	function color_name_check($color_code)
	{
		$color_code = $this->color_model->color_name_unique($color_code);
		if($color_code == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('color_name_check', 'There is an existing color name associated with this name.');
			return FALSE;
		}
	}	
	/**
         * This function check color code is exist or not.
         * @param  $color_code
         * @returns boolean
         */
	function color_code_check($color_code)
	{
		$color_code = $this->color_model->color_code_unique($color_code);
		if($color_code == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('color_code_check', 'There is an existing color code associated with this code.');
			return FALSE;
		}
	}	
	/**
         * This function is used for edit color.It get color detail from DB and create edit page,otherwise it will redirect on current page.
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
            /* check admin authentication*/
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
		
		$data['msg']=$msg;
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$one_color = $this->color_model->get_one_color($id);
		
		
		if($one_color)
		{
			$data["error"] = "";
			$data["limit"]=$limit;
			$data["offset"]=$offset;
			$data["option"]=$option;
			$data["keyword"]=$keyword;
			$data["search_option"]=$option;
			$data["search_keyword"]=$keyword;


						

			$data["color_id"] = $one_color['color_id'];
			$data["color_name"] = $one_color['color_name'];
			$data["color_code"] = $one_color['color_code'];
			$data["outside_color_code"] = $one_color['outside_color_code'];
			$data["status"] = $one_color['status'];
			
			
			$data["redirect_page"]=$redirect_page;
			$data['site_setting'] = site_setting();
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/color/add_color',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			if($redirect_page == 'list_color')
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			elseif($redirect_page=='search_list_color')
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}else{
				redirect('Color/list_color');
			}
		}
	}
	
	/*delete user data
	 * param  : user id,doctor id ,option,keyword,limit,offset,msg
	 * 
	 */
	/**
         * This function is used for delete color from list.with the help of colo_id it will delete color from list and redirect color_list page.
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
		$check_rights=get_rights('list_color');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		/* delete query for color */
		$this->db->where("color_id",$id);
		$this->db->update("colors",array("is_deleted"=>1));
		
		$active_users = $this->color_model->get_active_users();
		if($active_users){
			foreach($active_users as $user){
				$user_data = array(
					"is_deleted" => '1'
				); 
				$this->db->where('user_id',$user->user_id);
				$this->db->where('color_id',$id);
				$this->db->update('user_colors',$user_data);
			}
		}
		
		$this->session->set_flashdata('msg', "delete");
		//$this->db->delete('user',array('store_id'=>$id));
                /* redirect on list_color page*/
		if($redirect_page == 'list_color')
		{
			redirect('Color/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
		}
	}
	/*delete , active , inactive multiple user at a time
	 * param  : user id,doctor id ,redirect page,search option,search keyword,limit,offset,msg
	 * 
	 */ 
	
	/**
         * This function is used for update color details of selected option from dropdown list.It check action option with selected option, than perform next steps.
         * @returns void
         */
	function action()
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_color');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$offset=$this->input->post('offset');
		
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		
		$color_id =$this->input->post('chk');
			//print_r($_POST);die;
		if($action=='delete')
		{
			foreach($color_id as $id)
			{
				$this->db->where("color_id",$id);
				$this->db->update("colors",array("is_deleted"=>1));
				
				$active_users = $this->color_model->get_active_users();
				if($active_users){
					foreach($active_users as $user){
						$user_data = array(
							"is_deleted" => '1'
						); 
						$this->db->where('user_id',$user->user_id);
						$this->db->where('color_id',$id);
						$this->db->update('user_colors',$user_data);
					}
				}
			}
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'list_color')
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
			}
		}
			
		if($action=='active')
		{
			foreach($color_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('color_id',$id);
				$this->db->update('colors', $data);
				
				$active_users = $this->color_model->get_active_users();
				if($active_users){
					foreach($active_users as $user){
						$user_data = array(
							'status'=>'Active'
						); 
						$this->db->where('user_id',$user->user_id);
						$this->db->where('color_id',$id);
						$this->db->update('user_colors',$user_data);
					}
				}
				
			}
			
			$this->session->set_flashdata('msg', "active");
			if($redirect_page == 'list_color')
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{
			foreach($color_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('color_id',$id);
				$this->db->update('colors', $data);
				
				$active_users = $this->color_model->get_active_users();
				if($active_users){
					foreach($active_users as $user){
						$user_data = array(
							'status'=>'Inactive'
						); 
						$this->db->where('user_id',$user->user_id);
						$this->db->where('color_id',$id);
						$this->db->update('user_colors',$user_data);
					}
				}
				
			}
			
			$this->session->set_flashdata('msg', "inactive");
			if($redirect_page == 'list_color')
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('Color/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');
			}
		}	
	}
	
	
}


?>
