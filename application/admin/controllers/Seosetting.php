<?php
class Seosetting extends CI_Controller {


	function Seosetting()
	{
		parent::__construct();	
		$this->load->model('Seosetting_model');
		$this->adminRights=getadminRights();
		(count($this->adminRights)==0 && !checkSuperAdmin())?redirect('home/dashboard/noRights'):'';
		$this->adminRights=(object)getadminRights();
	}
	
	/*** site setting home page
	**/
	function index()
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$data['success']='';
		$data["error"] = "";
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('meta_keyword', 'Meta Keyword', 'required');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required');
		
		if($this->form_validation->run() == FALSE){
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($this->input->post('Seo_setting_id'))
			{
				echo "if"; die;
				$one_setting = seo_setting(); 
				//print_r($one_seo_setting); die;
				$data["Seo_setting_id"] = $this->input->post('Seo_setting_id');
				$data["title"] = $this->input->post('title');
			    $data["meta_keyword"] = $this->input->post('meta_keyword');
				$data["meta_description"] = $this->input->post('meta_description');
				
			}else{
					//echo "else"; die;
				$one_setting = seo_setting(); 
				
				$data["Seo_setting_id"] = $one_setting->Seo_setting_id;
				$data["title"] = $one_setting->title;
				$data["meta_keyword"] = $one_setting->meta_keyword;
				$data["meta_description"] = $one_setting->meta_description;
			}

			$data['seo_setting'] = seo_setting(); 
			$data['adminRights']=$this->adminRights;
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/Seosetting/add_Seosetting',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				//echo "else"; die;
				$this->Seosetting_model->seo_setting_update();
				$one_seo_setting = seo_setting(); 
				
				$data['success'] = SEO_SETTING_UPDATE;
				$data["Seo_setting_id"] = $this->input->post('Seo_setting_id');
				$data["title"] = $this->input->post('title');
			    $data["meta_keyword"] = $this->input->post('meta_keyword');
				$data["meta_description"] = $this->input->post('meta_description');
				
				$data['seo_setting'] = seo_setting();
				//$data['currency'] = get_currency();	
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/Seosetting/add_Seosetting',$data,TRUE);

			$this->template->render();
		}		
	}
	
	
}
?>