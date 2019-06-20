<?php
class City extends  CI_Controller {
	function City()
	{
		parent::__construct();	
		$this->load->model('City_model');
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
		redirect('City/listCity');	
	}
	
	
	function listCity($limit=20,$sort_on='1V1',$sort_type='1V1',$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$this->load->library('Jquery_pagination_bootstrap');

		$config['uri_segment']='4';
		$config['base_url'] = base_url().'City/listCity/'.$limit.'/';
		$config['div'] = '#content';
		function microtime_float()
		{
		    list($usec, $sec) = explode(" ", microtime());
		    return ((float)$usec + (float)$sec);
		}
		
		$time_start = microtime_float();
		$config['total_rows'] = $this->City_model->get_total_City_count();//2454865;//
		$data['query2'] = $this->db->last_query();
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->City_model->get_City_result($offset,$limit);
		$time_end = microtime_float();
		$time = $time_end - $time_start;
		
		$data["extime"]=$time;
		$data['query'] = $this->db->last_query();
		if($data['result']=='')
		{
			$offset=0;
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'City/listCity/'.$limit.'/';
		$config['div'] = '#content';
		$config['total_rows'] = $this->City_model->get_total_City_count();
	
		$config['per_page'] = $limit;		
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->City_model->get_City_result($offset,$limit);	
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
		$data['sort_on'] = $sort_on;
		$data['sort_type'] = $sort_type;
		$data['redirect_page']='listCity';
		$data['adminRights']=$this->adminRights;
		$data['site_setting'] = site_setting();
		if($this->input->is_ajax_request()){
			echo $this->load->view($theme .'/layout/City/CitylistAjax',$data,TRUE);die;
			
		}else{

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/City/listCity',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}
	

	function searchListCity($limit=20,$option='1V1',$keyword='1V1',$sort_on='1V1',$sort_type='1V1',$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');

		$redirect_page = 'searchListCity';

		$this->load->library('Jquery_pagination_bootstrap');
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
			$option=$option;
			$keyword=$keyword;	
			$sort_type=$sort_type;
			$sort_on=$sort_on;
		}

		$keyword=str_replace('"','',str_replace(array(",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		if($keyword=='')
		{
			$keyword='1V1';
		}
		$config['uri_segment']='8';
		$config['base_url'] = base_url().'City/'.$redirect_page.'/'.$limit.'/'.$option.'/'.mysql_real_escape_string($keyword).'/'.$sort_on.'/'.$sort_type.'/';
		$config['total_rows'] = $this->City_model->get_total_search_City_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->City_model->get_search_City_result($option,$keyword,$sort_on,$sort_type,$offset,$limit);
		
		if($data['result']=='')
		{
		$offset=0;
		$config['uri_segment']='8';
		$config['base_url'] = base_url().'City/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$sort_on.'/'.$sort_type.'/';
		$config['total_rows'] = $this->City_model->get_total_search_City_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->City_model->get_search_City_result($option,$keyword,$sort_on,$sort_type,$offset,$limit);
		}

		$data['msg'] = $msg;
		$data['offset'] = $offset;
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
			echo $this->load->view($theme .'/layout/City/CitylistAjax',$data,TRUE);die;
			
		}else{
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/City/listCity',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}
	
	
	
	
	
	function addCity($redirect_page='listCity',$limit=20,$option='1V1',$keyword='1V1',$offset=0)
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
		$this->form_validation->set_rules('city_name', 'City Name', 'required|callback_email_check');
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
			$data["city_id"] = $this->input->post('city_id');
			$data["city_name"] = $this->input->post('city_name');
			$data["status"] = $this->input->post('status');
			$data["allCountry"] = getActiveCountry();
			$data["allState"] = get_all_state_by_country_id($this->input->post('country_id'));
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]=$redirect_page;
			$data['offset']=$offset;
			$data['site_setting'] = site_setting();
			$data['adminRights']=$this->adminRights;
			$data["sort_type"] = "1V1";
			$data["sort_on"] = "1V1";

			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/City/addCity',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				
			if($this->input->post('city_id')!='')
			{
				//echo "update";die;
				$this->City_model->City_update();
				$msg = "update";
			}else{
				//echo "insert"; die;
				$this->City_model->City_insert();			
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

			if($redirect_page == 'listCity')
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
	
	function editCity($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0)
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');

		$one_City = $this->City_model->get_one_City($id);
		//pr($one_City);die;
		$data["allCountry"] = getActiveCountry();
		$data["allState"] = get_all_state_by_country_id($one_City['country_id']);
		$data["error"] = "";
		$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		$data["allCountry"] = getActiveCountry();
		$data["city_id"] = $id;
		$data["country_id"] = $one_City['country_id'];
		$data["state_id"] = $one_City['state_id'];
		$data["redirect_page"]=$redirect_page;
		$data["city_name"] = $one_City['city_name'];
		$data["status"] = $one_City['status'];
		$data["sort_type"] = "1V1";
		$data["sort_on"] = "1V1";
		$data['site_setting'] = site_setting();
		
		$data['adminRights']=$this->adminRights;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		
		if((isset($this->adminRights->Globalization) && $this->adminRights->Globalization->update==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/City/addCity',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function deleteCity($id=0,$redirect_page='listCity',$option='1V1',$keyword='1V1',$limit=20,$offset=0)
	{
		$one_City = $this->City_model->get_one_City($id);
		$this->db->delete('city_master',array('city_id'=>$id));
		
		echo 'done';die;
		
		if($redirect_page == 'listCity')
		{
			
			redirect('City/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('City/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

		}
	}
	
	
	
	
	function actionCity()
	{
		/*$check_rights=get_rights('City_login');
		
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
		
		
		
		$city_id =$this->input->post('chk');
		
		
		

			
		if($action=='delete')
		{		
			foreach($city_id as $id)
			{
				
				$one_City = $this->City_model->get_one_City($id);
							
				$this->db->query("delete from ".$this->db->dbprefix('city_master')." where city_id ='".$id."'");
			}
	
		$res=array('status'=>'done','msg'=>DELETE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listCity')
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

			}
			
		}
			
		if($action=='active')
		{		
			foreach($city_id as $id)
			{			
				$data = array('status'=>'Active');
				$this->db->where('city_id',$id);
				$this->db->update('city_master', $data);
			}

			$res=array('status'=>'done','msg'=>ACTIVE_RECORD);
			echo json_encode($res);die;
		
			if($redirect_page == 'listCity')
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{		
			foreach($city_id as $id)
			{			
				$data = array('status'=>'Inactive');
				$this->db->where('city_id',$id);
				$this->db->update('city_master', $data);
			}
			
			
			$res=array('status'=>'done','msg'=>INACTIVE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listCity')
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('City/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');

			}
			
			
		}	
		
		
		
		
	}
	
	
	function downloadCity()
	{
		//echo '<pre>';
		//print_r($_POST);
			
			$keyword=($this->input->post('key')!='')?str_replace(' ','-',$this->input->post('key')):'1V1';
			$option=($this->input->post('opt')!='')?str_replace(' ','-',$this->input->post('opt')):'1V1';
			
			
			
		
		$result=$this->City_model->downloadCityDate($option,$keyword);
		
		$filename ="City.csv";
		$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
	
	$data=$this->dbutil->csv_from_result($result, $delimiter, $newline);
	$this->load->helper('download');
	

	force_download($filename, $data);
	}	
	
}


?>
