<?php
class Pages extends  CI_Controller {
	function Pages()
	{
		parent::__construct();	
		$this->load->model('Pages_model');
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
		redirect('Pages/listPages');	
	}
	
	
	function listPages($limit=20,$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/* $check_rights=get_rights('listPages');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		*/
		$this->load->library('pagination');

		//$limit = '2';
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'Pages/listPages/'.$limit.'/';
		$config['total_rows'] = $this->Pages_model->get_total_Pages_count();
	
		
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		$data['result'] = $this->Pages_model->get_Pages_result($offset,$limit);
		//print_r($data); die;
	
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
		$data['redirect_page']='listPages';
		$data['adminRights']=$this->adminRights;
		
		
		
		
		$data['site_setting'] = site_setting();
		/*if($this->input->is_ajax_request()){
			echo $this->load->view($theme .'/layout/Pages/PageslistAjax',$data,TRUE);die;
			
		}else{*/

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Pages) && $this->adminRights->Pages->view==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/Pages/listPages',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
		
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	

	function searchListPages($limit=20,$option='1V1',$keyword='1V1',$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		//$data['redirect_page']='searchListPages';
		$redirect_page = 'searchListPages';
		
		/*$check_rights=get_rights('listPages');
		
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
		$config['base_url'] = base_url().'Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->Pages_model->get_total_search_Pages_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->Pages_model->get_search_Pages_result($option,$keyword,$offset, $limit);
		
		if($data['result']=='')
		{
		$offset=0;
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
		$config['total_rows'] = $this->Pages_model->get_total_search_Pages_count($option,$keyword);
		$config['per_page'] = $limit;
		$config['div'] = '#content';
		$this->jquery_pagination_bootstrap->initialize($config);		
		$data['page_link'] = $this->jquery_pagination_bootstrap->create_links();
		
		$data['result'] = $this->Pages_model->get_search_Pages_result($option,$keyword,$offset, $limit);
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
			echo $this->load->view($theme .'/layout/Pages/PageslistAjax',$data,TRUE);die;
			
		}else{
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/Pages/listPages',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		}
	}
	
	
	
	
	
	function addPages($redirect_page='listPages',$limit=20,$option='1V1',$keyword='1V1',$offset=0)
	{
		//echo "njhk"; die;
		$data['actionPage']='addPages';
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*$check_rights=get_rights('listPages');
		
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
		//$this->form_validation->set_rules('active', 'Status', 'required|');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required|');
		$this->form_validation->set_rules('pages_title', 'Pages Title', 'required|');
		$this->form_validation->set_rules('description', 'Description', 'required|');
		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["Pages_id"] = $this->input->post('Pages_id');
			$data["pages_title"] = $this->input->post('pages_title');
			$data["active"] = $this->input->post('active');
			$data["slug"] = $this->input->post('slug');
			$data["description"] = $this->input->post('description');
			$data["meta_keyword"] = $this->input->post('meta_keyword');
			$data["pages_title"] = $this->input->post('pages_title');
			$data["active"] = $this->input->post('active');
				$data["meta_description"] = $this->input->post('meta_description');
			
			$data["search_option"]='';
			$data["search_keyword"]='';
			$data["option"]='1V1';
			$data["keyword"]='1V1';
			$data["redirect_page"]=$redirect_page;
			
			$data['offset']=$offset;
			$data['site_setting'] = site_setting();
			
			//$data['allState']=get_all_state_by_country_id(231);
			$data['adminRights']=$this->adminRights;
			
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/Pages/addPages',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				
			if($this->input->post('Pages_id')!='')
			{	$this->Pages_model->Pages_update();
				$msg = "update";
			}else{
				$this->Pages_model->Pages_insert();			
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
			if($redirect_page == 'listPages')
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}				
	}
	function editPages($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0)
	{
	$data['actionPage']='editPages/'.$id;	
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$data['adminRights']=$this->adminRights;
		$this->load->library('form_validation');
		//$this->form_validation->set_rules('active', 'Status', 'required|');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required|');
		$this->form_validation->set_rules('pages_title', 'Pages Title', 'required|');
		$this->form_validation->set_rules('description', 'Description', 'required|');
		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			
			
			if($this->input->post('Pages_id')){
				
			$data["Pages_id"] = $this->input->post('Pages_id');
			$data["pages_title"] = $this->input->post('pages_title');
			$data["active"] = $this->input->post('active');
			$data["slug"] = $this->input->post('slug');
			$data["description"] = $this->input->post('description');
			$data["meta_keyword"] = $this->input->post('meta_keyword');
			$data["pages_title"] = $this->input->post('pages_title');
			$data["active"] = $this->input->post('active');
			$data["meta_description"] = $this->input->post('meta_description');
			
			
			$data["limit"]=$this->input->post('limit');
			$data["offset"]=$this->input->post('offset');
			$data["option"]=$this->input->post('option');
			$data["keyword"]=$this->input->post('keyword');
			$data["search_option"]=$this->input->post('option');
			$data["search_keyword"]=$this->input->post('keyword');
			$data["redirect_page"]=$this->input->post('redirect_page');
			
			}else{
				$one_Pages = $this->Pages_model->get_one_Pages($id);
				
				$data["Pages_id"] = $id;
				$data["redirect_page"]=$redirect_page;
				$data["pages_title"] = $one_Pages['pages_title'];
				$data["active"] = $one_Pages['active'];
				$data["slug"] = $one_Pages['slug'];
				$data["description"] = $one_Pages['description'];
				$data["meta_keyword"] = $one_Pages['meta_keyword'];
				$data["meta_description"] = $one_Pages['meta_description'];
				
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
		$this->template->write_view('center',$theme .'/layout/Pages/addPages',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
			
		}else{
			if($this->input->post('Pages_id')!='')
			{	$this->Pages_model->Pages_update();
				$msg = "update";
			}else{
				$this->Pages_model->Pages_insert();			
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
			if($redirect_page == 'listPages')
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$offset.'/'.$msg);
			}
			else
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/'.$msg);
			}
		}

		
		
	}
	function editPages_old($id=0,$redirect_page='',$option='',$keyword='',$limit=0,$offset=0)
	{
	$data['actionPage']='editPages';	
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*$check_rights=get_rights('listPages');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		$one_Pages = $this->Pages_model->get_one_Pages($id);
		//print_r($one_Pages); die;
		$data["error"] = "";
		$data["limit"]=$limit;
		$data["offset"]=$offset;
		$data["option"]=$option;
		$data["keyword"]=$keyword;
		$data["search_option"]=$option;
		$data["search_keyword"]=$keyword;
		
		
		$data["Pages_id"] = $id;
		$data["redirect_page"]=$redirect_page;
		$data["pages_title"] = $one_Pages['pages_title'];
		$data["active"] = $one_Pages['active'];
		$data["slug"] = $one_Pages['slug'];
		$data["description"] = $one_Pages['description'];
		$data["meta_keyword"] = $one_Pages['meta_keyword'];
		$data["meta_description"] = $one_Pages['meta_description'];
		$data['site_setting'] = site_setting();
		
		$data['adminRights']=$this->adminRights;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		
		if((isset($this->adminRights->eCommerce) && $this->adminRights->eCommerce->update==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/Pages/addPages',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function deletePages($id=0,$redirect_page='listPages',$option='1V1',$keyword='1V1',$limit=20,$offset=0)
	{
		//echo "bnmb"; die;
		//$check_rights=get_rights('listPages');
		//$limit='20';
		/*if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}*/
		
		
		
		
		$one_Pages = $this->Pages_model->get_one_Pages($id);
				$profile_image=$one_Pages['Pages_image'];
				if($profile_image!='')
				{
					if(file_exists(base_path().'upload/Pages/'.$profile_image))
					{
						$link=base_path().'upload/Pages/'.$profile_image;
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/Pages_orig/'.$profile_image))
					{
						$link2=base_path().'upload/Pages_orig/'.$profile_image;
						unlink($link2);
					}
				}
		$this->db->delete('Pages',array('Pages_id'=>$id));
		
		echo 'done';die;
		
		if($redirect_page == 'listPages')
		{
			
			redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

		}
	}
	
	
	
	
	function actionPages()
	{
		/*$check_rights=get_rights('Pages_login');
		
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
		
		
		
		$Pages_id =$this->input->post('chk');
		
		
		

			
		if($action=='delete')
		{		
			foreach($Pages_id as $id)
			{
				
				$one_Pages = $this->Pages_model->get_one_Pages($id);
				$profile_image=$one_Pages['Pages_image'];
				if($profile_image!='')
				{
					if(file_exists(base_path().'upload/Pages/'.$profile_image))
					{
						$link=base_path().'upload/Pages/'.$profile_image;
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/Pages_orig/'.$profile_image))
					{
						$link2=base_path().'upload/Pages_orig/'.$profile_image;
						unlink($link2);
					}
				}
							
				$this->db->query("delete from ".$this->db->dbprefix('Pages')." where Pages_id ='".$id."'");
			}
	
		$res=array('active'=>'done','msg'=>DELETE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listPages')
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

			}
			
		}
			
		if($action=='active')
		{		
			foreach($Pages_id as $id)
			{			
				$data = array('active'=>'active');
				$this->db->where('Pages_id',$id);
				$this->db->update('Pages', $data);
			}

			$res=array('active'=>'done','msg'=>ACTIVE_RECORD);
			echo json_encode($res);die;
		
			if($redirect_page == 'listPages')
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{		
			foreach($Pages_id as $id)
			{			
				$data = array('active'=>'inactive');
				$this->db->where('Pages_id',$id);
				$this->db->update('Pages', $data);
			}
			
			
			$res=array('active'=>'done','msg'=>INACTIVE_RECORD);
		echo json_encode($res);die;
			
			if($redirect_page == 'listPages')
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('Pages/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');

			}
			
			
		}	
		
		
		
		
	}
	
	function email_check($Pages_name)
	{
		if($this->input->post('Pages_id')!='')
		{
			$query=$this->db->get_where('Pages',array('Pages_name'=>$Pages_name,'Pages_id !='=>$this->input->post('Pages_id')));
		}else{
			$query=$this->db->get_where('Pages',array('Pages_name'=>$Pages_name));
		}
		
		if($query->num_rows()>0)
		{
			$this->form_validation->set_message('email_check', 'There is an existing Pages');
			return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
	
	function removeImage(){
		$Pages_id= $this->input->get_post('Pages_id',true)?$this->input->get_post('Pages_id'):0;
		$imagename= $this->input->get_post('imagename',true)?$this->input->get_post('imagename'):0;
		$action = $this->input->get_post('action',true)?$this->input->get_post('action'):"";
		if($action == 'removeImage'){
			$removeimage=$this->Pages_model->removeImage($Pages_id,$imagename);
		}	
		echo $removeimage;exit;
	}
	
	
	function downloadPages()
	{
		//echo '<pre>';
		//print_r($_POST);
			
			$keyword=($this->input->post('key')!='')?str_replace(' ','-',$this->input->post('key')):'1V1';
			$option=($this->input->post('opt')!='')?str_replace(' ','-',$this->input->post('opt')):'1V1';
			
			
			
		
		$result=$this->Pages_model->downloadPagesDate($option,$keyword);
		
		$filename ="Pages.csv";
		$this->load->dbutil();
		$delimiter = ",";
		$newline = "\r\n";
	
	$data=$this->dbutil->csv_from_result($result, $delimiter, $newline);
	$this->load->helper('download');
	

	force_download($filename, $data);
	}	
	
}


?>
