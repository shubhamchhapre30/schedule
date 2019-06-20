<?php
/**
 * This class is used to create plan page for admin panel.This class function create plan related functionality.   
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Plan extends  CI_Controller {
     /**
        * It default constuctor which is called when plan class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	function Plan()
	{
            /*
             * call parent class constructor
             */
		parent::__construct();	
                     /* load Amazon s3 library file*/
		$this->load->library('s3');
                /* config file amazon s3 */
		$this->config->load('s3');
                /* databasse of plan class */
		$this->load->model('plan_model');
                /* load pagination library */
		$this->load->library('pagination');
	   
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
		redirect('plan/list_plan');
	}
	
	/* user list
	 * param  : limit,offset,msg
	 * 
	 */
	/**
         * This function get plan details from DB and create list_plan page for admin.
         * @param int $limit
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function list_plan($limit='20',$offset=0,$msg='') {
		/* admin authentication */
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
		
		$check_rights=get_rights('list_plan');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'plan/list_plan/'.$limit.'/';
		$config['total_rows'] = $this->plan_model->get_total_plan_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->plan_model->get_plan_result($offset,$limit);
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
		$data['redirect_page']='list_plan';
		
		$data['site_setting'] = site_setting();
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/plan/list_plan',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	/* search patitent
	 * param  : doctor id ,limit,option,keyword,offset,msg
	 * 
	 */
	/**
         * This function check admin authentication and get plan details from DB for create search plan list.
         * @param int $limit
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function search_list_plan($limit=20,$option='',$keyword='',$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$redirect_page = 'search_list_plan';
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		//$check_rights=get_rights('search_list_plan');
		
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
		$config['base_url'] = base_url().'plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->plan_model->get_total_search_plan_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		//$data['all_country']=getActiveCountry();
		$data['result'] = $this->plan_model->get_search_plan_result($option,$keyword,$offset, $limit);
		
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
		$this->template->write_view('center',$theme .'/layout/plan/list_plan',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	
	/*add new plan also called in plan update
	 * param  : limit
	 * 
	 */
	/**
         * This function is used for add plan.Firstly it checks admin authentication after that it will set validation rules for insert plan in DB.
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
		$check_rights=get_rights('add_plan');
		//$data['all_country']=getActiveCountry();
		//$data['all_company']=getActiveCompany();
		//$data['user_type'] ='';
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
                /* load and set form validation*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules('chargify_product_id', 'Chargify product id', 'required');
		$this->form_validation->set_rules('chargify_component_id', 'Chargify component id', 'required');
		$this->form_validation->set_rules('plan_title', 'Plan Title', 'required');
		$this->form_validation->set_rules('plan_description', 'Plan Description', 'required');
		$this->form_validation->set_rules('plan_currency_code', 'Plan Currency Code', 'required');
		$this->form_validation->set_rules('plan_price', 'Plan Price', 'required');
		$this->form_validation->set_rules('plan_duration', 'Plan Duration', 'required');
		$this->form_validation->set_rules('plan_status', 'Plan Status', 'required');
		
		
	

		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			$data["plan_id"] = $this->input->post('plan_id');
			$data["plan_title"] = $this->input->post('plan_title');
			$data["plan_description"] = $this->input->post('plan_description');
			$data["plan_currency_code"] = $this->input->post('plan_currency_code');
			$data["plan_price"] = $this->input->post('plan_price');
			$data['plan_duration'] = $this->input->post('plan_duration');
			$data["plan_status"] = $this->input->post('plan_status');
			$data["chargify_product_id"] = $this->input->post('chargify_product_id');
			$data["chargify_component_id"] = $this->input->post('chargify_component_id');
			$data['chargify_external_user_component_id'] = '';
						
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]='list_plan';
			$data['site_setting'] = site_setting();
		
			if($this->input->post('offset')=="")
			{
				$limit = '10';
				$totalRows = $this->plan_model->get_total_plan_count();
				$data["offset"] = (int)($totalRows/$limit)*$limit;
			}else{
				$data["offset"] = $this->input->post('offset');
			}
			//echo "<pre>";print_r($data);exit;
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/plan/add_plan',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			/* validation is true than,it insert data */	
			if($this->input->post('plan_id')!='')
			{	
				$this->plan_model->plan_update($_POST['plan_id']);
				$msg = "update";
				$this->session->set_flashdata('msg', $msg);
			}else{
				$this->plan_model->plan_insert();			
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
			
			

			
			if($redirect_page == 'list_plan')
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
	
	/**
         * With the help,this function check user email is exist or not.
         * @param  $email
         * @returns boolean
         */
	function email_check($email)
	{
		$username = $this->plan_model->user_email_unique($email);
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
	/*plan update form fill
	 * param  : plan id,doctor id ,redirect page,option,keyword,limit,offset
	 * 
	 */
	/**
         * This function is used for edit plan details.By using plan_id,it will get plan details for create edit plan page, otherwise it redirect on current page.
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
		$check_rights=get_rights('add_plan');
		$data['all_country']=getActiveCountry();
		$data['msg']=$msg;
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$one_user = $this->plan_model->get_one_plan($id);
	//	echo '<pre>';print_r($one_user);die;
		if($one_user){
			$data["error"] = "";
			$data["limit"]=$limit;
			$data["offset"]=$offset;
			$data["option"]=$option;
			$data["keyword"]=$keyword;
			$data["search_option"]=$option;
			$data["search_keyword"]=$keyword;
			
			$data["plan_id"] = $id;
			
			$data["plan_id"] = $one_user['plan_id'];
			$data["plan_title"] = $one_user['plan_title'];
			$data["plan_description"] = $one_user['plan_description'];
			$data["plan_currency_code"] = $one_user['plan_currency_code'];
			$data['plan_duration'] = $one_user['plan_duration'];
			$data["plan_price"] = $one_user['plan_price'];
			$data["plan_status"] = $one_user['plan_status'];
			$data["chargify_product_id"] = $one_user['chargify_product_id'];
			$data["chargify_component_id"] = $one_user['chargify_component_id'];
			$data['chargify_external_user_component_id'] = $one_user['chargify_external_user_component_id']; 
				
			$data["redirect_page"]=$redirect_page;
			$data['site_setting'] = site_setting();
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/plan/add_plan',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			if($redirect_page == 'list_plan')
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			elseif($redirect_page=='search_list_plan')
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}else{
				redirect('plan/list_plan');
			}
		}
	}
	
	/*delete plan data
	 * param  : plan id,doctor id ,option,keyword,limit,offset,msg
	 * 
	 */
	/**
         * This function delete plan from list.It will update plans table column is_deleted with 1 for delete.
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
		$check_rights=get_rights('list_plan');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		//$this->db->delete('rights_assign',array('store_id'=>$id));
		//$this->db->where('plan_id',$id);
		//$this->db->delete('plan');
		/* update query for plans */
		$this->db->where("plan_id",$id);
		$this->db->update("plans",array("is_deleted"=>1));
        
		//$this->db->delete('plan',array('store_id'=>$id));
		if($redirect_page == 'list_plan')
		{
			redirect('plan/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
		}
	}
	
	/* Send customer reset password
	 * param  : plan id ,option,keyword,limit,offset,msg
	 * 
	 */
	function reset_password_user($id=0,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_plan');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		//$this->db->delete('rights_assign',array('store_id'=>$id));
		//$this->db->where('plan_id',$id);
		//$this->db->delete('plan');
		$this->plan_model->forgot_password($id);
		
		
		$this->session->set_flashdata('msg', "sent");
        
		//$this->db->delete('plan',array('store_id'=>$id));
		if($redirect_page == 'list_plan')
		{
			redirect('plan/'.$redirect_page.'/'.$limit.'/'.$offset.'/sent');
		}
		else
		{
			redirect('plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/sent');
		}
	}
	
	/*delete , active , inactive multiple plan at a time
	 * param  : plan id,doctor id ,redirect page,search option,search keyword,limit,offset,msg
	 * 
	 */ 
	/**
         * This function is used for update plan details of selected option from dropdown list.
         * @returns void
         */
	function action()
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_plan');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$offset=$this->input->post('offset');
		
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		
		$plan_id =$this->input->post('chk');
			
		if($action=='delete')
		{
			foreach($plan_id as $id)
			{
				$this->db->where("plan_id",$id);
		        $this->db->update("plans",array("is_deleted"=>1));		
				//$this->db->query("delete from ".$this->db->dbprefix('plan')." where store_id ='".$id."'");
			}
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'list_plan')
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
			}
		}
			
		if($action=='active')
		{
			foreach($plan_id as $id)
			{			
				$data = array('plan_status'=>'Active');
				$this->db->where('plan_id',$id);
				$this->db->update('plans', $data);
			}
			
			$this->session->set_flashdata('msg', "active");
			if($redirect_page == 'list_plan')
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{
			foreach($plan_id as $id)
			{			
				$data = array('plan_status'=>'Inactive');
				$this->db->where('plan_id',$id);
				$this->db->update('plans', $data);
			}
			
			$this->session->set_flashdata('msg', "inactive");
			if($redirect_page == 'list_plan')
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('plan/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');
			}
		}	
	}
	function removeimage($plan_id,$image,$limit,$offset,$redirect_page,$option,$keyword)
	{
		//echo "sdfsdf";die;
		if($image!='')
		{
			$this->db->where("plan_id",$plan_id);
	        $this->db->update("plan",array("profile_image"=>''));	
			if(file_exists(base_path().'upload/profile_orig/'.$image))
			{
				$link=base_path().'upload/profile_orig/'.$image;
				unlink($link);
			}
			
			if(file_exists(base_path().'upload/profile_thumb/'.$image))
			{
				$link1=base_path().'upload/profile_thumb/'.$image;
				unlink($link1);
			}			
		}
		$msg='image_remove';
		redirect('plan/edit/'.$plan_id.'/'.$redirect_page.'/1V1/1V1/'.$limit.'/'.$offset.'/'.$msg);
	}	
	
	function getcompanyaddress($id)
	{
		
		$data=$this->plan_model->get_companyaddress($id);
		//print_r($data);die();
		// print form_dropdown('product_type',$data['product_type']);
		
		echo json_encode($data);
		 
	
		
		
	}
	
}


?>
