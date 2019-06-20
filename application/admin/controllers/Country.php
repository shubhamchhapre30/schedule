<?php
class Country extends  CI_Controller {
	function Country()
	{
		parent::__construct();	
		$this->load->model('Country_model');
		$this->load->library('pagination');
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
		redirect('Country/listCountry');	
	}
	
	
	function listCountry($limit=20,$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'Country/listCountry/'.$limit.'/';
		$config['total_rows'] = $this->Country_model->get_total_Country_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data["result"]= $this->Country_model->get_Country_result($offset,$limit);
		
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
		$data['sort_type']='1V1';
		$data['sort_on']='1V1';
		$data['search_type']='normal';
		$data['redirect_page']='listCountry';
		$data['adminRights']=$this->adminRights;
		$data['site_setting'] = site_setting();
		
		
		
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/Country/listCountry',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		
	}
	

	function searchListCountry($limit=20,$option='1V1',$keyword='1V1',$sort_on='1V1',$sort_type='1V1',$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$redirect_page = 'searchListCountry';
		
		if($_POST)
		{		
			$option=$this->input->post('option')!=''?$this->input->post('option'):'1V1';
			$keyword=($this->input->post('keyword')!='')?str_replace(" ", "-",trim($this->input->post('keyword'))):'1V1';
			$limit=($this->input->post('limit'))?$this->input->post('limit'):$limit;
			$sort_type = ($this->input->post('sort_type')!='')?$this->input->post('sort_type'):'1V1';
			$sort_on = ($this->input->post('sort_on')!='')?$this->input->post('sort_on'):'1V1';
		}
		else
		{
			$sort_type=$sort_type;
			$sort_on=$sort_on;
			$option=$option;
			$keyword=$keyword;	
		}
		
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		if($keyword=='')
		{
			$keyword='1V1';
		}
		$config['uri_segment']='8';
		$config['base_url'] = base_url().'Country/searchListCountry/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/';
		$config['total_rows'] = $this->Country_model->get_total_search_Country_count($option,$keyword);
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->Country_model->get_search_Country_result($option,$keyword,$sort_on,$sort_type,$offset,$limit);
		
		
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		$data['site_setting'] = site_setting();
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['sort_type']=$sort_type;
		$data['sort_on']=$sort_on;
		$data['option']=$option;
		$data['redirect_page']=$redirect_page;
		$data['adminRights']=$this->adminRights;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/Country/listCountry',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		
	}

	function addCountry($redirect_page='listCountry',$limit=20,$option='1V1',$keyword='1V1',$sort_on='1V1',$sort_type='1V1',$offset=0)
	{
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
		$this->load->library('form_validation');
		$this->form_validation->set_rules('country_name', 'Country Name', 'required|callback_email_check');
		$this->form_validation->set_rules('status', 'Status', 'required|');

		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["country_id"] = $this->input->post('country_id');
			$data["country_name"] = $this->input->post('country_name');
			$data["status"] = $this->input->post('status');
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data['sort_type']=$sort_type;
			$data['sort_on']=$sort_on;
			$data['option']=$option;
			$data["redirect_page"]=$redirect_page;
			$data['offset']=$offset;
			$data['site_setting'] = site_setting();
			$data['allState']=get_all_state_by_country_id(231);
			$data['adminRights']=$this->adminRights;

			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/Country/addCountry',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}

			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			if($this->input->post('country_id')!='')
			{
				$this->Country_model->Country_update();
				$msg = "update";
				$this->session->set_flashdata('msg', $msg);
			}else{
				$this->Country_model->Country_insert();			
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
			$sort_on = $this->input->post('sort_on');
			$sort_type = $this->input->post('sort_type');

			if($redirect_page == 'listCountry')
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/'.$msg);
			}
		}				
	}

	function editCountry($id=0,$redirect_page='listCountry',$limit=20,$option='1V1',$keyword='1V1',$sort_on='1V1',$sort_type='1V1',$offset=0)
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}

		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$one_Country = $this->Country_model->get_one_Country($id);
		$data["error"] = "";
		$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		$data['sort_type']=$sort_type;
		$data['sort_on']=$sort_on;
		$data['option']=$option;
		$data["country_id"] = $id;
		$data["redirect_page"]=$redirect_page;
		$data["country_name"] = $one_Country['country_name'];
		$data["status"] = $one_Country['status'];
		$data['site_setting'] = site_setting();
		$data['adminRights']=$this->adminRights;
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->update==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/Country/addCountry',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function deleteCountry($id=0,$redirect_page='listCountry',$option='1V1',$keyword='1V1',$limit=20,$offset=0)
	{
		$one_Country = $this->Country_model->get_one_Country($id);
		$this->db->delete('country_master',array('country_id'=>$id));
		
		$this->session->set_flashdata('msg', "delete");
		
		if($redirect_page == 'listCountry')
		{
			
			redirect('Country/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('Country/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/delete');

		}
	}

	function actionCountry()
	{
		$offset=$this->input->post('offset');
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		$country_id =$this->input->post('chk');
		$sort_on=$this->input->post('sort_on');
		$sort_type=$this->input->post('sort_type');
		
		if($action=='delete')
		{		
			foreach($country_id as $id)
			{
				
				$one_Country = $this->Country_model->get_one_Country($id);
							
				$this->db->query("delete from ".$this->db->dbprefix('country_master')." where country_id ='".$id."'");
			}

		// $res=array('status'=>'done','msg'=>DELETE_RECORD);
		// echo json_encode($res);die;
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'listCountry')
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/delete');
			}
		}

		if($action=='active')
		{		
			foreach($country_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('country_id',$id);
				$this->db->update('country_master', $data);
			}

			// $res=array('status'=>'done','msg'=>ACTIVE_RECORD);
			// echo json_encode($res);die;
		   $this->session->set_flashdata('msg', "active");
			if($redirect_page == 'listCountry')
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{		
			foreach($country_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('country_id',$id);
				$this->db->update('country_master', $data);
			}
			
			
			// $res=array('status'=>'done','msg'=>INACTIVE_RECORD);
		// echo json_encode($res);die;
			$this->session->set_flashdata('msg', "inactive");
			if($redirect_page == 'listCountry')
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('Country/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/'.$offset.'/inactive');

			}
			
			
		}	
		
		
		
		
	}
	
	
	function downloadCountry()
	{
		//echo '<pre>';
		//print_r($_POST);
			
			$keyword=($this->input->post('key')!='')?str_replace(' ','-',$this->input->post('key')):'1V1';
			$option=($this->input->post('opt')!='')?str_replace(' ','-',$this->input->post('opt')):'1V1';
			
			
			
		
		$result=$this->Country_model->downloadCountryDate($option,$keyword);
		
		$filename ="Country.csv";
		$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
	
	$data=$this->dbutil->csv_from_result($result, $delimiter, $newline);
	$this->load->helper('download');
	
	//echo '<pre>'; print_r($data); die;
	force_download($filename, $data);
	}	
	
}


?>
