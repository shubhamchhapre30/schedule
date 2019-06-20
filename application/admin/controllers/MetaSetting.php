<?php
class MetaSetting extends CI_Controller {
	function MetaSetting()
	{
		parent::__construct();
		$this->load->model('meta_setting_model');	
		$this->adminRights=getadminRights();
		(count($this->adminRights)==0 && !checkSuperAdmin())?redirect('home/dashboard/noRights'):'';
		$this->adminRights=(object)getadminRights();
	}
	
	/*** meta setting home page
	**/
	function index()
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$data["success"] ='';
		$data["error"] = "";
		$data['adminRights']=$this->adminRights;
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Title', 'required');
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($this->input->post('meta_setting_id'))
			{
				$data["meta_setting_id"] = $this->input->post('meta_setting_id');
				$data["title"] = $this->input->post('title');
				$data["meta_keyword"] = $this->input->post('meta_keyword');
				$data["meta_description"] = $this->input->post('meta_description');
			}else{
			
				$one_meta_setting = $this->meta_setting_model->get_one_meta_setting();
				if($one_meta_setting)
				{
					$data["meta_setting_id"] = $one_meta_setting->meta_setting_id;
					$data["title"] = $one_meta_setting->title;
					$data["meta_keyword"] = $one_meta_setting->meta_keyword;
					$data["meta_description"] = $one_meta_setting->meta_description;
				}
				else
				{
					$data["meta_setting_id"] = '';
					$data["title"] = '';
					$data["meta_keyword"] = '';
					$data["meta_description"] = '';

				}
				
			}
			
			$data['site_setting'] = site_setting();
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->view==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/add_meta_setting',$data,TRUE);
			}else{
			
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else
		
		{
			
			$this->meta_setting_model->meta_setting_update();
			if($_POST)
			{
				
			 $this->session->set_flashdata('success', "Meta settings updated successfully.");
			}
			$data["success"] = "Meta settings updated successfully.";
			$data["meta_setting_id"] = $this->input->post('meta_setting_id');
			$data["title"] = $this->input->post('title');
			$data["meta_keyword"] = $this->input->post('meta_keyword');
			$data["meta_description"] = $this->input->post('meta_description');
			
			$data['site_setting'] = site_setting();
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->view==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/add_meta_setting',$data,TRUE);
			}else{
			
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}
	}
	
	/** admin meta setting display and update function
	* var integer $meta_setting_id
	* var string $title
	* var string $meta_keyword
	* var string $meta_description	
	* var string $error		
	**/
	function add_meta_setting()
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Title', 'required');
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($this->input->post('meta_setting_id'))
			{
				$data["meta_setting_id"] = $this->input->post('meta_setting_id');
				$data["title"] = $this->input->post('title');
				$data["meta_keyword"] = $this->input->post('meta_keyword');
				$data["meta_description"] = $this->input->post('meta_description');
			}else{
			
				$one_meta_setting = $this->meta_setting_model->get_one_meta_setting();
				if($one_meta_setting)
				{
					$data["meta_setting_id"] = $one_meta_setting->meta_setting_id;
					$data["title"] = $one_meta_setting->title;
					$data["meta_keyword"] = $one_meta_setting->meta_keyword;
					$data["meta_description"] = $one_meta_setting->meta_description;
				}
				else
				{
					$data["meta_setting_id"] = '';
					$data["title"] = '';
					$data["meta_keyword"] = '';
					$data["meta_description"] = '';

				}
				
			}
			
			$data['site_setting'] = site_setting();
			
			$this->template->write_view('header_menu',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/setting/add_meta_setting',$data,TRUE);
				   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->render();
		}else{
			$this->meta_setting_model->meta_setting_update();
			
			$data["error"] = "Meta settings updated successfully.";
			$data["meta_setting_id"] = $this->input->post('meta_setting_id');
			$data["title"] = $this->input->post('title');
			$data["meta_keyword"] = $this->input->post('meta_keyword');
			$data["meta_description"] = $this->input->post('meta_description');
			
			$data['site_setting'] = site_setting();
			
			$this->template->write_view('header_menu',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/setting/add_meta_setting',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->render();
		}				
	}
	
}
?>