<?php
class Email_setting extends CI_Controller {
	function Email_setting()
	{
		parent::__construct();
		$this->load->model('email_setting_model');
	}
	
	/*** email setting home page
	**/
	function index()
	{
		redirect('email_setting/add_email_setting/');
	}
	
	function add_email_setting()
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('mailer', 'Mailer Type', 'required');
		
		if($this->input->post('mailer')=='sendmail')
		{		
			$this->form_validation->set_rules('sendmail_path', 'Sendmail Path', 'required');
		}

		if($this->input->post('mailer')=='smtp')
		{
			$this->form_validation->set_rules('smtp_port', 'Smtp Port', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('smtp_host', 'Smtp Host', 'required');
			$this->form_validation->set_rules('smtp_email', 'Smtp Email', 'required|valid_email');
			$this->form_validation->set_rules('smtp_password', 'Smtp Password', 'required');
		}

		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($this->input->post('email_setting_id'))
			{
				$data["email_setting_id"] = $this->input->post('email_setting_id');
				$data["mailer"] = $this->input->post('mailer');
				$data["sendmail_path"] = $this->input->post('sendmail_path');
				$data["smtp_port"] = $this->input->post('smtp_port');
				$data["smtp_host"] = $this->input->post('smtp_host');
				$data["smtp_email"] = $this->input->post('smtp_email');
				$data["smtp_password"] = $this->input->post('smtp_password');
			}else{
				$email_setting = $this->email_setting_model->get_my_email_setting();
								
				$data["email_setting_id"] = $email_setting->email_setting_id;
				$data["mailer"] = $email_setting->mailer;
				$data["sendmail_path"] = $email_setting->sendmail_path;
				$data["smtp_port"] = $email_setting->smtp_port;
				$data["smtp_host"] = $email_setting->smtp_host;
				$data["smtp_email"] = $email_setting->smtp_email;
				$data["smtp_password"] = $email_setting->smtp_password;
			}

			$data['site_setting'] =site_setting();
			
			$this->template->write_view('header_menu',$theme .'/layout/common/header',$data,TRUE);
     		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/setting/add_email_setting',$data,TRUE);
			//$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			$this->email_setting_model->email_setting_update();
			$data["error"] = "Email settings updated successfully.";
			$data["email_setting_id"] = $this->input->post('email_setting_id');
			
			$data["mailer"] = $this->input->post('mailer');
			$data["sendmail_path"] = $this->input->post('sendmail_path');
			$data["smtp_port"] = $this->input->post('smtp_port');
			$data["smtp_host"] = $this->input->post('smtp_host');
			$data["smtp_email"] = $this->input->post('smtp_email');
			$data["smtp_password"] = $this->input->post('smtp_password');
				
			$data['site_setting'] = site_setting();
			
			$this->template->write_view('header_menu',$theme .'/layout/common/header',$data,TRUE);
   		    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/setting/add_email_setting',$data,TRUE);
			//$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}				
	}
	
}
?>