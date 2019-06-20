<?php
/**
 * This class is used to create email template page for admin panel.This class function create template setting related functionality.   
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class EmailTemplate extends  CI_Controller {
    /**
     * This is class constructor and it load nesscessary files.
     */
	function EmailTemplate()
	{
            /* load parent class*/
		parent::__construct();	
                /* load emailtemplate model */
		$this->load->model('EmailTemplate_model');
		$this->adminRights=getadminRights();
		(count($this->adminRights)==0 && !checkSuperAdmin())?redirect('home/dashboard/noRights'):'';
		$this->adminRights=(object)getadminRights();
		
	}
	/**
         * This is default page of emailtemplate class.It checks authentication and redirect
         */
	function index()
	{
            /* admin authentication */
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		redirect('EmailTemplate/listEmailTemplate');	
	}
	/**
         * This function is used for show list of emailtemplate on template page.
         * @param int $limit
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	
	function listEmailTemplate($limit=20,$offset=0,$msg='')
	{
		/* check admin authentication */
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/* $check_rights=get_rights('listEmailTemplate');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		*/
		//$this->load->library('pagination');
                /* load library of jquery paginations*/
		$this->load->library('Jquery_pagination_bootstrap');

		//$limit = '2';
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'EmailTemplate/listEmailTemplate/'.$limit.'/';
		$config['div'] = '#content';
		$config['total_rows'] = $this->EmailTemplate_model->get_total_EmailTemplate_count();
	
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->EmailTemplate_model->get_EmailTemplate_result($offset,$limit);
		//print_r($data); die;
		if($data['result']=='')
		{
			$offset=0;
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'EmailTemplate/listEmailTemplate/'.$limit.'/';
		$config['div'] = '#content';
		$config['total_rows'] = $this->EmailTemplate_model->get_total_EmailTemplate_count();
	
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->EmailTemplate_model->get_EmailTemplate_result($offset,$limit);	
		}
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
		$data['redirect_page']='listEmailTemplate';
		$data['adminRights']=$this->adminRights;
		
		
		
		
		$data['site_setting'] = site_setting();
		
		/* check request is ajax request or not.*/
		if($this->input->is_ajax_request()){
			echo $this->load->view($theme .'/layout/EmailTemplate/EmailTemplatelistAjax',$data,TRUE);die;
			
		}else{

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/EmailTemplate/listEmailTemplate',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}
	
        /**
         * This function functionality is committed.
         */
	function searchListEmailTemplate($limit=20,$option='1V1',$keyword='1V1',$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		//$data['redirect_page']='searchListEmailTemplate';
		$redirect_page = 'searchListEmailTemplate';
		
		/*$check_rights=get_rights('listEmailTemplate');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		$this->load->library('Jquery_pagination_bootstrap');
		if($_POST)
		{		
			$option=$this->input->post('option');
			$keyword=($this->input->post('keyword')!='')?str_replace(" ", "-",trim($this->input->post('keyword'))):'1V1';
			$limit=($this->input->post('limit'))?$this->input->post('limit'):$limit;
			
		}
		else
		{
			$option=$option;
			$keyword=$keyword;	
		
		}
		
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		if($keyword=='')
		{
			$keyword='1V1';
		}
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->EmailTemplate_model->get_total_search_EmailTemplate_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->EmailTemplate_model->get_search_EmailTemplate_result($option,$keyword,$offset, $limit);
		
		if($data['result']=='')
		{
		$offset=0;
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->EmailTemplate_model->get_total_search_EmailTemplate_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->EmailTemplate_model->get_search_EmailTemplate_result($option,$keyword,$offset, $limit);
		}
		//print_r($data['result']);die;
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		
		
		//$data['statelist']=$this->project_category_model->get_state();
		
		$data['site_setting'] = site_setting();
		
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['redirect_page']=$redirect_page;
		$data['adminRights']=$this->adminRights;
		if($this->input->is_ajax_request()){
			echo $this->load->view($theme .'/layout/EmailTemplate/EmailTemplatelistAjax',$data,TRUE);die;
			
		}else{
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/EmailTemplate/listEmailTemplate',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}
	
       /**
         * This function functionality is committed.
         */
	function addEmailTemplate($redirect_page='listEmailTemplate',$limit=20,$option='1V1',$keyword='1V1',$offset=0)
	{
		//echo "njhk"; die;
		$data['actionPage']='addEmailTemplate';
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*$check_rights=get_rights('listEmailTemplate');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		if($limit > 0)
		{
			$data['limit']=$limit;
		}
		else
		{
			$data['limit']=20;
		}
		$data['offset']=$offset;
		$this->load->library('form_validation');
		//$this->form_validation->set_rules('status', 'Status', 'required|');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required');
		$this->form_validation->set_rules('EmailTemplate_title', 'EmailTemplate Title', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["EmailTemplate_id"] = $this->input->post('EmailTemplate_id');
			$data["EmailTemplate_title"] = $this->input->post('EmailTemplate_title');
			$data["status"] = $this->input->post('status');
			$data["slug"] = $this->input->post('slug');
			$data["description"] = $this->input->post('description');
			$data["meta_keyword"] = $this->input->post('meta_keyword');
			$data["EmailTemplate_title"] = $this->input->post('EmailTemplate_title');
			$data["status"] = $this->input->post('status');
			
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]=$redirect_page;
			
			$data['offset']=$offset;
			$data['site_setting'] = site_setting();
			
			$data['allState']=get_all_state_by_country_id(231);
			$data['adminRights']=$this->adminRights;
			
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/EmailTemplate/addEmailTemplate',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				
			if($this->input->post('EmailTemplate_id')!='')
			{	$this->EmailTemplate_model->EmailTemplate_update();
				$msg = "update";
			}else{
				$this->EmailTemplate_model->EmailTemplate_insert();			
				$msg = "insert";
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
		 	
			//print_r($_POST) ;die;
			if($redirect_page == 'listEmailTemplate')
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
        /**
         * This function is used for edit template.It defines rules for template and check if is true it will update data,otherwise it create edit page with user inputs.
         * @param int $id
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @param int $limit
         * @param int $offset
         * @returns void
         */
	function editEmailTemplate($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0)
	{
	$data['actionPage']='editEmailTemplate/'.$id;	
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$data['adminRights']=$this->adminRights;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('from_address', 'From Address', 'required');
		$this->form_validation->set_rules('reply_address', 'Reply Address', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			
			
			if($this->input->post('EmailTemplate_id')){
				
			$data["EmailTemplate_id"] = $this->input->post('EmailTemplate_id');
			$data["from_address"] = $this->input->post('from_address');
			$data["reply_address"] = $this->input->post('reply_address');
			$data["message"] = $this->input->post('message');
			$data["subject"] = $this->input->post('subject');
			
			
			
			$data["limit"]=$this->input->post('limit');
			$data["offset"]=$this->input->post('offset');
			$data["option"]=$this->input->post('option');
			$data["keyword"]=$this->input->post('keyword');
			$data["search_option"]=$this->input->post('option');
			$data["search_keyword"]=$this->input->post('keyword');
			$data["redirect_page"]=$this->input->post('redirect_page');
			
			}else{
				$one_EmailTemplate = $this->EmailTemplate_model->get_one_EmailTemplate($id);
				//print_r($one_EmailTemplate);die;
				$data["EmailTemplate_id"] = $id;
				$data["redirect_page"]=$redirect_page;
				$data["from_address"] = $one_EmailTemplate['from_address'];
				$data["reply_address"] = $one_EmailTemplate['reply_address'];
				$data["subject"] = $one_EmailTemplate['subject'];
				$data["message"] = $one_EmailTemplate['message'];
                                $data["sandgrid_id"] = $one_EmailTemplate['sandgrid_id'];
			
				
				$data["limit"]=$limit;
				$data["offset"]=$offset;
				$data["option"]=$option;
				$data["keyword"]=$keyword;
				$data["search_option"]=$option;
				$data["search_keyword"]=$keyword;
				
			}
			
		
			
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		
		if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->update==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/EmailTemplate/addEmailTemplate',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
			
		}else{
                    /* check emailtemplate_id for update data*/
			if($this->input->post('EmailTemplate_id')!='')
			{	$this->EmailTemplate_model->EmailTemplate_update();
				$msg = "update";
			
				$this->session->set_flashdata('msg', $msg);
			}else{
				$this->EmailTemplate_model->EmailTemplate_insert();			
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
		 	
			//print_r($_POST) ;die;
			if($redirect_page == 'listEmailTemplate')
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}

		
		
	}
	function editEmailTemplate_old($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0)
	{
	$data['actionPage']='editEmailTemplate';	
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*$check_rights=get_rights('listEmailTemplate');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		$one_EmailTemplate = $this->EmailTemplate_model->get_one_EmailTemplate($id);
		//print_r($one_EmailTemplate); die;
		$data["error"] = "";
		$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		
		
		$data["EmailTemplate_id"] = $id;
		$data["redirect_page"]=$redirect_page;
		$data["EmailTemplate_title"] = $one_EmailTemplate['EmailTemplate_title'];
		$data["status"] = $one_EmailTemplate['status'];
		$data["slug"] = $one_EmailTemplate['slug'];
		$data["description"] = $one_EmailTemplate['description'];
		$data["meta_keyword"] = $one_EmailTemplate['meta_keyword'];
		$data["meta_description"] = $one_EmailTemplate['meta_description'];
		$data['site_setting'] = site_setting();
		
		$data['adminRights']=$this->adminRights;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		
		if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->update==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/EmailTemplate/addEmailTemplate',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function deleteEmailTemplate($id=0,$redirect_page='listEmailTemplate',$option='1V1',$keyword='1V1',$limit=20,$offset=0)
	{
		//echo "bnmb"; die;
		//$check_rights=get_rights('listEmailTemplate');
		//$limit='20';
		/*if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		
		$one_EmailTemplate = $this->EmailTemplate_model->get_one_EmailTemplate($id);
				$profile_image=$one_EmailTemplate['EmailTemplate_image'];
				if($profile_image!='')
				{
					if(file_exists(base_path().'upload/EmailTemplate/'.$profile_image))
					{
						$link=base_path().'upload/EmailTemplate/'.$profile_image;
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/EmailTemplate_orig/'.$profile_image))
					{
						$link2=base_path().'upload/EmailTemplate_orig/'.$profile_image;
						unlink($link2);
					}
				}
		$this->db->delete('EmailTemplate',array('EmailTemplate_id'=>$id));
		
		echo 'done';die;
		
		if($redirect_page == 'listEmailTemplate')
		{
			
			redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

		}
	}
	
	 /**
         * This function functionality is committed.
         */
	
	
	function actionEmailTemplate()
	{
		/*$check_rights=get_rights('EmailTemplate_login');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
	//print_r($_POST);die;
		$offset=$this->input->post('offset');
		
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		
		
		
		$EmailTemplate_id =$this->input->post('chk');
		
		
		

			
		if($action=='delete')
		{		
			foreach($EmailTemplate_id as $id)
			{
				
				$one_EmailTemplate = $this->EmailTemplate_model->get_one_EmailTemplate($id);
				$profile_image=$one_EmailTemplate['EmailTemplate_image'];
				if($profile_image!='')
				{
					if(file_exists(base_path().'upload/EmailTemplate/'.$profile_image))
					{
						$link=base_path().'upload/EmailTemplate/'.$profile_image;
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/EmailTemplate_orig/'.$profile_image))
					{
						$link2=base_path().'upload/EmailTemplate_orig/'.$profile_image;
						unlink($link2);
					}
				}
							
				$this->db->query("delete from ".$this->db->dbprefix('EmailTemplate')." where EmailTemplate_id ='".$id."'");
			}
	
		$res=array('status'=>'done','msg'=>DELETE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listEmailTemplate')
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

			}
			
		}
			
		if($action=='active')
		{		
			foreach($EmailTemplate_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('EmailTemplate_id',$id);
				$this->db->update('EmailTemplate', $data);
			}

			$res=array('status'=>'done','msg'=>ACTIVE_RECORD);
			echo json_encode($res);die;
		
			if($redirect_page == 'listEmailTemplate')
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{		
			foreach($EmailTemplate_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('EmailTemplate_id',$id);
				$this->db->update('EmailTemplate', $data);
			}
			
			
			$res=array('status'=>'done','msg'=>INACTIVE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listEmailTemplate')
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('EmailTemplate/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');

			}
			
			
		}	
		
		
		
		
	}
	 /**
         * This function functionality is committed.
         */
	function email_check($EmailTemplate_name)
	{
		if($this->input->post('EmailTemplate_id')!='')
		{
			$query=$this->db->get_where('EmailTemplate',array('EmailTemplate_name'=>$EmailTemplate_name,'EmailTemplate_id !='=>$this->input->post('EmailTemplate_id')));
		}else{
			$query=$this->db->get_where('EmailTemplate',array('EmailTemplate_name'=>$EmailTemplate_name));
		}
		
		if($query->num_rows()>0)
		{
			$this->form_validation->set_message('email_check', 'There is an existing EmailTemplate');
			return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
	 /**
         * This function functionality is committed.
         */
	function removeImage(){
		$EmailTemplate_id= $this->input->get_post('EmailTemplate_id',true)?$this->input->get_post('EmailTemplate_id'):0;
		$imagename= $this->input->get_post('imagename',true)?$this->input->get_post('imagename'):0;
		$action = $this->input->get_post('action',true)?$this->input->get_post('action'):"";
		if($action == 'removeImage'){
			$removeimage=$this->EmailTemplate_model->removeImage($EmailTemplate_id,$imagename);
		}	
		echo $removeimage;exit;
	}
	 /**
         * This function functionality is committed.
         */
	
	function downloadEmailTemplate()
	{
		//echo '<pre>';
		//print_r($_POST);
			
			$keyword=($this->input->post('key')!='')?str_replace(' ','-',$this->input->post('key')):'1V1';
			$option=($this->input->post('opt')!='')?str_replace(' ','-',$this->input->post('opt')):'1V1';
			
			
			
		
		$result=$this->EmailTemplate_model->downloadEmailTemplateDate($option,$keyword);
		
		$filename ="EmailTemplate.csv";
		$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
	
	$data=$this->dbutil->csv_from_result($result, $delimiter, $newline);
	$this->load->helper('download');
	

	force_download($filename, $data);
	}	
	
}


?>
