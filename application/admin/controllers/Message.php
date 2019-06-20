<?php
class Message extends  CI_Controller {
	function Message()
	{
		 parent::__construct();	
		$this->load->model('Message_model');
		$this->adminRights=getadminRights();
		(count($this->adminRights)==0 && !checkSuperAdmin())?redirect('home/dashboard/noRights'):'';
		$this->adminRights=(object)getadminRights();
		
	}
	
	function index()
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		redirect('Message/listMessage');	
	}
	
	
	function listMessage($limit=20,$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/* $check_rights=get_rights('listMessage');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		*/
		//$this->load->library('pagination');
		$this->load->library('Jquery_pagination_bootstrap');

		//$limit = '2';
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'Message/listMessage/'.$limit.'/';
		$config['div'] = '#content';
		$config['total_rows'] = $this->Message_model->get_total_Message_count();
	
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->Message_model->get_Message_result($offset,$limit);
		if($data['result']=='')
		{
			$offset=0;
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'Message/listMessage/'.$limit.'/';
		$config['div'] = '#content';
		$config['total_rows'] = $this->message_model->get_total_Message_count();
	
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->message_model->get_Message_result($offset,$limit);	
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
		$data['redirect_page']='listMessage';
		$data['adminRights']=$this->adminRights;
		
		
		
		
		$data['site_setting'] = site_setting();
		if($this->input->is_ajax_request()){
			echo $this->load->view($theme .'/layout/Message/MessagelistAjax',$data,TRUE);die;
			
		}else{

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Message) && $this->adminRights->Message->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/Message/listMessage',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}
	

	function searchListMessage($limit=20,$option='1V1',$keyword='1V1',$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		//$data['redirect_page']='searchListMessage';
		$redirect_page = 'searchListMessage';
		
		/*$check_rights=get_rights('listMessage');
		
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
		$config['base_url'] = base_url().'Message/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->Message_model->get_total_search_Message_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->Message_model->get_search_Message_result($option,$keyword,$offset, $limit);
		
		if($data['result']=='')
		{
		$offset=0;
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'Message/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->Message_model->get_total_search_Message_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->Message_model->get_search_Message_result($option,$keyword,$offset, $limit);
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
			echo $this->load->view($theme .'/layout/Message/MessagelistAjax',$data,TRUE);die;
			
		}else{
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Message) && $this->adminRights->Message->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/message/listMessage',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}
	
	
	
	
	
	function addMessage($redirect_page='listMessage',$limit=20,$option='1V1',$keyword='1V1',$offset=0)
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*$check_rights=get_rights('listMessage');
		
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
		$this->form_validation->set_rules('first_name', 'First Name', 'required|');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
		
		if($this->input->post('Message_id')==''){
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[12]');
		$this->form_validation->set_rules('cpassword', 'Password', 'required|min_length[8]|max_length[12]|matches[password]');
		}
		$this->form_validation->set_rules('phone_no', 'Phone No', 'required|');
		$this->form_validation->set_rules('address', 'Address', 'required|');
		$this->form_validation->set_rules('state', 'State', 'required|');
		$this->form_validation->set_rules('city', 'City', 'required|');
		$this->form_validation->set_rules('zip', 'Zip', 'required|');
		$this->form_validation->set_rules('status', 'Status', 'required|');
		//$this->form_validation->set_rules('login_ip', 'Login IP', 'required|valid_ip');	
		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["Message_id"] = $this->input->post('Message_id');
			$data["first_name"] = $this->input->post('first_name');
			$data["last_name"] = $this->input->post('last_name');
			
			$data["email"] = $this->input->post('email');
			$data["password"] = $this->input->post('password');		
			$data["cpassword"] = $this->input->post('cpassword');		
			$data["status"] = $this->input->post('status');
			$data["phone_no"] = $this->input->post('phone_no');
			$data["image"] = $this->input->post('prev_Message_image');
			$data["about_Message"] = $this->input->post('about_Message');
			$data["city"] = $this->input->post('city');
			$data["state"] =$this->input->post('state');
			$data["zip"] =$this->input->post('zip');
			$data["address"] = $this->input->post('address');
			
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
			if((isset($this->adminRights->Message) && $this->adminRights->Message->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/Message/addMessage',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				
			if($this->input->post('Message_id')!='')
			{	
				$this->Message_model->Message_update();
				$msg = "update";
			}else{
				$this->Message_model->Message_insert();			
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
			if($redirect_page == 'listMessage')
			{
				redirect('Message/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('Message/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
	
	function editMessage($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0)
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*$check_rights=get_rights('listMessage');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		$one_Message = $this->message_model->get_one_Message($id);
		$data["error"] = "";
		$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		
		
		$data["Message_id"] = $id;
		$data["redirect_page"]=$redirect_page;
		$data["first_name"] = $one_Message['first_name'];
		$data["last_name"] = $one_Message['last_name'];
		$data["email"] = $one_Message['email'];
		$data["password"] = $one_Message['password'];		
		$data["cpassword"] = $one_Message['password'];		
		$data["status"] = $one_Message['status'];
		$data["image"] = $one_Message['profile_image'];
		$data["phone_no"] = $one_Message['phone_no'];

		$data["country"] = $one_Message['country'];
		$data["state"] =$one_Message['state'];
		$data["city"] =$one_Message['city'];
		$data["zip"] =$one_Message['zip'];
		$data["address"] =$one_Message['address'];
		
		
				
		$data['allState']=get_all_state_by_country_id(231);
		
		$data['site_setting'] = site_setting();
		
		$data['adminRights']=$this->adminRights;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		
		if((isset($this->adminRights->Message) && $this->adminRights->Message->update==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/message/addMessage',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function deleteMessage($id=0,$redirect_page='listMessage',$option='1V1',$keyword='1V1',$limit=20,$offset=0)
	{
		//$check_rights=get_rights('listMessage');
		//$limit='20';
		/*if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		
		$one_Message = $this->message_model->get_one_Message($id);
				$profile_image=$one_Message['profile_image'];
				if($profile_image!='')
				{
					if(file_exists(base_path().'upload/message/'.$profile_image))
					{
						$link=base_path().'upload/message/'.$profile_image;
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/Message_orig/'.$profile_image))
					{
						$link2=base_path().'upload/Message_orig/'.$profile_image;
						unlink($link2);
					}
				}
		$this->db->delete('Message',array('Message_id'=>$id));
		
		echo 'done';die;
		
		if($redirect_page == 'listMessage')
		{
			
			redirect('message/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('message/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

		}
	}
	
	
	
	
	function actionMessage()
	{
		/*$check_rights=get_rights('Message_login');
		
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
		
		
		
		$Message_id =$this->input->post('chk');
		
		
		

			
		if($action=='delete')
		{		
			foreach($Message_id as $id)
			{
				
				$one_Message = $this->message_model->get_one_Message($id);
				$profile_image=$one_Message['profile_image'];
				if($profile_image!='')
				{
					if(file_exists(base_path().'upload/message/'.$profile_image))
					{
						$link=base_path().'upload/message/'.$profile_image;
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/Message_orig/'.$profile_image))
					{
						$link2=base_path().'upload/Message_orig/'.$profile_image;
						unlink($link2);
					}
				}
							
				$this->db->query("delete from ".$this->db->dbprefix('Message')." where Message_id ='".$id."'");
			}
	
		$res=array('status'=>'done','msg'=>DELETE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listMessage')
			{
				redirect('message/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('message/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

			}
			
		}
			
		if($action=='active')
		{		
			foreach($Message_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('Message_id',$id);
				$this->db->update('Message', $data);
			}

			$res=array('status'=>'done','msg'=>ACTIVE_RECORD);
			echo json_encode($res);die;
		
			if($redirect_page == 'listMessage')
			{
				redirect('message/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('message/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{		
			foreach($Message_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('Message_id',$id);
				$this->db->update('Message', $data);
			}
			
			
			$res=array('status'=>'done','msg'=>INACTIVE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listMessage')
			{
				redirect('message/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('message/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');

			}
			
			
		}	
		
		
		
		
	}
	
	function email_check($email)
	{
		if($this->input->post('Message_id')!='')
		{
			$query=$this->db->get_where('Message',array('email'=>$email,'Message_id !='=>$this->input->post('Message_id')));
		}else{
			$query=$this->db->get_where('Message',array('email'=>$email));
		}
		
		if($query->num_rows()>0)
		{
			$this->form_validation->set_message('email_check', 'There is an existing account associated with this email');
			return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
	
	function removeImage(){
		$Message_id= $this->input->get_post('Message_id',true)?$this->input->get_post('Message_id'):0;
		$imagename= $this->input->get_post('imagename',true)?$this->input->get_post('imagename'):0;
		$action = $this->input->get_post('action',true)?$this->input->get_post('action'):"";
		if($action == 'removeImage'){
			$removeimage=$this->message_model->removeImage($Message_id,$imagename);
		}	
		echo $removeimage;exit;
	}
	
	
	function downloadMessage()
	{
		//echo '<pre>';
		//print_r($_POST);
			
			$keyword=($this->input->post('key')!='')?str_replace(' ','-',$this->input->post('key')):'1V1';
			$option=($this->input->post('opt')!='')?str_replace(' ','-',$this->input->post('opt')):'1V1';
			
			
			
		
		$result=$this->message_model->downloadMessageDate($option,$keyword);
		
		$filename ="Message.csv";
		$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
	
	$data=$this->dbutil->csv_from_result($result, $delimiter, $newline);
	$this->load->helper('download');
	

	force_download($filename, $data);
	}	
	
}


?>
