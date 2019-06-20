<?php
class Email_template extends CI_Controller {
	function Email_template()
	{
		 parent::__construct();	
		$this->load->model('email_template_model');	
	}
	
	function index()
	{
		redirect('email_template/list_email_template/');
	}
	
	function list_email_template(){
		
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data["error"] = '';
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		
		$this->load->library('pagination');

		$limit = '40';
		$offset = 0;
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'email_template/list_email_template/'.$limit.'/';
		$config['total_rows'] = $this->email_template_model->get_email_template_count();
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['site_setting'] = site_setting();
		$data["template"] = $this->email_template_model->get_email_template($offset, $limit);
			
		$this->template->write_view('header_menu',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/email_template/list_email_template',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		//$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function add_email_template($id=0)
	{
		
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$one_email_template = $this->email_template_model->get_one_email_template($id);
		$data['task'] = $one_email_template->task;
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('from_address', 'From Address', 'required|valid_email');
		$this->form_validation->set_rules('reply_address', 'Reply Address', 'required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($this->input->post('email_template_id'))
			{
				$data["email_template_id"] = $this->input->post('email_template_id');
				$data["from_address"] = $this->input->post('from_address');
				$data["reply_address"] = $this->input->post('reply_address');
				$data["subject"] = $this->input->post('subject');
				$data["message"] = $this->input->post('message');
			}else{
				
				$data["email_template_id"] = $one_email_template->email_template_id;
				$data["from_address"] = $one_email_template->from_address;
				$data["reply_address"] = $one_email_template->reply_address;
				$data["subject"] = $one_email_template->subject;
				$data["message"] = $one_email_template->message;
				$data["task"] = $one_email_template->task;
			}
			
			$data['site_setting'] = site_setting();
			//$data["template"] = $this->email_template_model->get_email_template();
			$this->template->write_view('header_menu',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/email_template/add_email_template',$data,TRUE);
			$this->template->render();
		}else{
			$this->email_template_model->email_template_update();
			$data["error"] = "Email template updated successfully";
			$data["email_template_id"] = $this->input->post('email_template_id');
			$data["from_address"] = $this->input->post('from_address');
			$data["reply_address"] = $this->input->post('reply_address');
			$data["subject"] = $this->input->post('subject');
			$data["message"] = $this->input->post('message');
			//$data["template"] = $this->email_template_model->get_email_template();
			redirect('email_template/list_email_template/');
			$data['site_setting'] = site_setting();
			
			$this->template->write_view('header_menu',$theme .'/layout/common/header',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/email_template/list_email_template',$data,TRUE);
			$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			//$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}				
	}	
}
?>