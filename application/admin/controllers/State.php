<?php
class State extends  CI_Controller {
	function State()
	{
		parent::__construct();	
		$this->load->model('State_model');
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
		redirect('State/listState');	
	}
	
	
	function listState($limit=20,$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/* $check_rights=get_rights('listState');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		*/
		//$this->load->library('pagination');
		$this->load->library('Jquery_pagination_bootstrap');

		//$limit = '2';
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'State/listState/'.$limit.'/';
		$config['div'] = '#content';
		$config['total_rows'] = $this->State_model->get_total_State_count();
	
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->State_model->get_State_result($offset,$limit);
		if($data['result']=='')
		{
			$offset=0;
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'State/listState/'.$limit.'/';
		$config['div'] = '#content';
		$config['total_rows'] = $this->State_model->get_total_State_count();
	
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->State_model->get_State_result($offset,$limit);	
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
		$data['redirect_page']='listState';
		$data['adminRights']=$this->adminRights;
		$data['sort_type']='1V1';
		$data['sort_on']='1V1';

		$data['site_setting'] = site_setting();
		if($this->input->is_ajax_request()){
			echo $this->load->view($theme .'/layout/State/StatelistAjax',$data,TRUE);die;
			
		}else{

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/State/listState',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}

	function searchListState($limit=20,$option='1V1',$keyword='1V1',$sort_on='1V1',$sort_type='1V1',$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		//$data['redirect_page']='searchListState';
		$redirect_page = 'searchListState';
		
		/*$check_rights=get_rights('listState');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		$this->load->library('Jquery_pagination_bootstrap');
		if($_POST)
		{		
			$option=$this->input->post('option')!=''?$this->input->post('option'):'1V1';
			$keyword=($this->input->post('keyword')!='')?str_replace(" ", "-",trim($this->input->post('keyword'))):'1V1';
			$limit=($this->input->post('limit'))?$this->input->post('limit'):$limit;
			$sort_type=($this->input->post('sort_type')!='')?str_replace(" ", "-",trim($this->input->post('sort_type'))):'1V1';
			$sort_on=($this->input->post('sort_on')!='')?str_replace(" ", "-",trim($this->input->post('sort_on'))):'1V1';
		}
		else
		{
			$option=$option;
			$keyword=$keyword;	
			$sort_type=$sort_type;
			$sort_on=$sort_on;
		}

		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		if($keyword=='')
		{
			$keyword='1V1';
		}
		$config['uri_segment']='8';
		$config['base_url'] = base_url().'State/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/';
		$config['total_rows'] = $this->State_model->get_total_search_State_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->State_model->get_search_State_result($option,$keyword,$sort_on,$sort_type,$offset,$limit);
		
		if($data['result']=='')
		{
		$offset=0;
		$config['uri_segment']='8';
		$config['base_url'] = base_url().'State/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/';
		$config['total_rows'] = $this->State_model->get_total_search_State_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->State_model->get_search_State_result($option,$keyword,$sort_on,$sort_type,$offset,$limit);
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
		$data['sort_type']=$sort_type;
		$data['sort_on']=$sort_on;
		$data['redirect_page']=$redirect_page;
		$data['adminRights']=$this->adminRights;
		if($this->input->is_ajax_request()){
			echo $this->load->view($theme .'/layout/State/StatelistAjax',$data,TRUE);die;
			
		}else{
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/State/listState',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}

	function addState($redirect_page='listState',$limit=20,$option='1V1',$keyword='1V1',$sort_on='1V1',$sort_type='1V1',$offset=0)
	{
		//echo "njhk"; die;
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');

		if($limit > 0)
		{
			$data['limit']=$limit;
		}
		else
		{
			$data['limit']=20;
		}
		$data['offset']=$offset;
		$data['sort_on']=$sort_on;
		$data['sort_type']=$sort_type;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('state_name', 'State Name', 'required|callback_email_check');
		$this->form_validation->set_rules('status', 'Status', 'required|');

		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["country_id"] = $this->input->post('country_id');
			$data["state_id"] = $this->input->post('state_id');
			$data["state_name"] = $this->input->post('state_name');
			$data["status"] = $this->input->post('status');
			$data["allCountry"] = getActiveCountry();
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]=$redirect_page;
			$data['offset']=$offset;
			$data['site_setting'] = site_setting();
			$data['adminRights']=$this->adminRights;
			
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/State/addState',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				
			if($this->input->post('state_id')!='')
			{
				//echo "update";die;
				$this->State_model->State_update();
				$msg = "update";
			}else{
				//echo "insert"; die;
				$this->State_model->State_insert();			
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
		 	$sort_type = $this->input->post('sort_type');
			$sort_on = $this->input->post('sort_on');
			$limit = $this->input->post('limit');
			//print_r($_POST) ;die;
			if($redirect_page == 'listState')
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/'.$msg);
			}
		}				
	}

	function editState($id=0,$redirect_page='',$limit=0,$option='',$keyword='',$sort_on='1V1',$sort_type='1V1',$offset=0)
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*$check_rights=get_rights('listState');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		$one_State = $this->State_model->get_one_State($id);
		//print_r($one_State); die;
		$data["error"] = "";
		$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		$data["allCountry"] = getActiveCountry();
		$data["state_id"] = $id;
		$data["country_id"] = $one_State['country_id'];
		$data["redirect_page"]=$redirect_page;
		$data["state_name"] = $one_State['state_name'];
		$data["status"] = $one_State['status'];
		$data['sort_on']=$sort_on;
		$data['sort_type']=$sort_type;
		$data['site_setting'] = site_setting();
		
		$data['adminRights']=$this->adminRights;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->update==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/State/addState',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function deleteState($id=0,$redirect_page='listState',$option='1V1',$keyword='1V1',$limit=20,$offset=0)
	{
		//echo "bnmb"; die;
		//$check_rights=get_rights('listState');
		//$limit='20';
		/*if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		
		$one_State = $this->State_model->get_one_State($id);
		$this->db->delete('state_master',array('state_id'=>$id));
		
		echo 'done';die;
		
		if($redirect_page == 'listState')
		{
			
			redirect('State/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('State/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/delete');

		}
	}
	
	
	
	
	function actionState()
	{
		/*$check_rights=get_rights('State_login');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
	//print_r($_POST);die;
		$offset=$this->input->post('offset');
		$sort_on=$this->input->post('sort_on');
		$sort_type=$this->input->post('sort_type');
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		
		
		
		$state_id =$this->input->post('chk');
		
		
		

			
		if($action=='delete')
		{		
			foreach($state_id as $id)
			{
				
				$one_State = $this->State_model->get_one_State($id);
							
				$this->db->query("delete from ".$this->db->dbprefix('state_master')." where state_id ='".$id."'");
			}
	
		$res=array('status'=>'done','msg'=>DELETE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listState')
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/delete');

			}
			
		}
			
		if($action=='active')
		{		
			foreach($state_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('state_id',$id);
				$this->db->update('state_master', $data);
			}

			$res=array('status'=>'done','msg'=>ACTIVE_RECORD);
			echo json_encode($res);die;
		
			if($redirect_page == 'listState')
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{		
			foreach($state_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('state_id',$id);
				$this->db->update('state_master', $data);
			}
			
			
			$res=array('status'=>'done','msg'=>INACTIVE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listState')
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('State/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/inactive');

			}
			
			
		}	
		
		
		
		
	}
	
	
	function downloadState()
	{
		//echo '<pre>';
		//print_r($_POST);
			
			$keyword=($this->input->post('key')!='')?str_replace(' ','-',$this->input->post('key')):'1V1';
			$option=($this->input->post('opt')!='')?str_replace(' ','-',$this->input->post('opt')):'1V1';
			
			
			
		
		$result=$this->State_model->downloadStateDate($option,$keyword);
		
		$filename ="State.csv";
		$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
	
	$data=$this->dbutil->csv_from_result($result, $delimiter, $newline);
	$this->load->helper('download');
	

	force_download($filename, $data);
	}	
	
}


?>
