<?php
class Csvdata extends  CI_Controller {
	function Csvdata()
	{
		 parent::__construct();	
		$this->load->model('csvdata_model');
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
		redirect('csvdata/lists');
	}
	
	
	function lists($limit='20',$offset=0,$msg='')
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
		
		// $check_rights=get_rights('lists');
// 		
		// if(	$check_rights==0) {			
			// redirect('home/dashboard/no_rights');	
		// }
		
		$this->load->library('pagination');

		
		$config['uri_segment']='4';
	//	$config['base_url'] = base_url().'admin/list_admin/'.$limit.'/';
		$config['base_url'] =site_url("admin/lists/".$limit);
		$config['total_rows'] = $this->csvdata_model->get_total_csvdata_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->csvdata_model->get_csvdata_result($offset,$limit);
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
		$data['redirect_page']='lists';
	
		$data["adminRights"] = $this->adminRights;
		
		
		
		$data['site_setting'] = site_setting();

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->admin) && $this->adminRights->admin->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/csvdata/list',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		
		
		
	}

   
	// Use :This function use for list admin by filter.
	// Param :limit,option,keyword,offset,message
	// Return :'N/A'
	function search($limit=20,$option='',$keyword='',$offset=0,$msg='')
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$redirect_page = 'search';
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		// $check_rights=get_rights('search');
// 		
		// if(	$check_rights==0) {			
			// redirect('home/dashboard/no_rights');	
		// }
		
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
		
		
		$config['total_rows'] = $this->csvdata_model->get_total_search_csvdata_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->csvdata_model->get_search_csvdata_result($option,$keyword,$offset, $limit);
		
		
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		$data['site_setting'] = site_setting();
		
		$data["adminRights"] = $this->adminRights;
		
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['redirect_page']=$redirect_page;
		
	
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
  	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->admin) && $this->adminRights->admin->view==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/csvdata/list',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	
	
	function action()
	{
		/* Future enhancement
		 * when assigning rights is used
		*/
		
		// $check_rights=get_rights('action_admin');
// 		
		// if(	$check_rights==0) {			
			// redirect('home/dashboard/no_rights');	
		// }
		
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
          
               
                		
			foreach($admin_id as $id)
			{			
				$this->db->query("delete from ".$this->db->dbprefix('enroll_csvdata')." where enroll_id ='".$id."'");
			}
			
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'lists')
			{
				redirect('csvdata/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('csvdata/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');

			}
			
		}
		
	}
}


?>