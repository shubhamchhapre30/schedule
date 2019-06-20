<?php
/**
 * This class is used to create payment setting page for admin panel.This class function create setting related functionality.   
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Payment_setting extends CI_Controller {

        /**
        * It default constuctor which is called when Payment_setting class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */
	function Payment_setting()
	{
            /* call parent constructor */
		parent::__construct();	
                /* load payment class model*/
		$this->load->model('payment_setting_model');
		$this->adminRights=getadminRights();
		(count($this->adminRights)==0 && !checkSuperAdmin())?redirect('home/dashboard/noRights'):'';
		$this->adminRights=(object)getadminRights();
	}
	
	/*** site setting home page
	**/
	
	/**
         * This is default function for create payment page.It checks valiadtion,if it's false create payment setting page otherwise it update data in db
         * @returns void
         */
	function index()
	{
		/* check admin authentication*/
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
		$this->form_validation->set_rules('credit_card', 'credit card', '');
		//$this->form_validation->set_rules('cash', 'Site Status', 'required');
		//$this->form_validation->set_rules('bank_transfer', 'Site Status', 'required');

		 
		$data['adminRights']=$this->adminRights;
		
		/* check valiadtion if it's false create payment setting page otherwise it update data in db.*/
		if($this->form_validation->run() == FALSE){
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			/* check payment id for update*/
			if($this->input->post('payment_id'))
			{
				//die;
				//$one_site_setting = site_setting(); 
				//print_r($one_site_setting); die;
				/*$data["site_setting_id"] = $one_site_setting->site_setting_id;
				$data["site_name"] = $one_site_setting->site_name;
				$data["site_online"] = $one_site_setting->site_online;*/
				
				$data["payment_id"] = $this->input->post('payment_id');
				$data["payment_title"] = $this->input->post('payment_title');
				$data["Login_username"] = $this->input->post('Login_username');
				$data["login_password"] = $this->input->post('login_password');
				$data["API_key"] = $this->input->post('API_key');
				$data["subdomain"] = $this->input->post('subdomain');
				$data["payment_mode"] = $this->input->post('payment_mode');
				$data["payment_status"] = $this->input->post('payment_status');
				
				//$data["captcha_enable"] = $one_site_setting->captcha_enable;
		
					
					
			}else{
                            /* in else condition get payment data from db */
				$onedata=$this->payment_setting_model->get_payment_data();
				//print_r($onedata); echo $onedata['payment_id'];die;
				
				$data["payment_id"] = $onedata->payment_id;
				$data["payment_title"] = $onedata->payment_title;
				$data["Login_username"] = $onedata->Login_username;
				$data["login_password"] = $onedata->login_password;
				$data["API_key"] = $onedata->API_key;
				$data["subdomain"] = $onedata->subdomain;
				$data["payment_mode"] = $onedata->payment_mode;
				$data["payment_status"] = $onedata->payment_status;
		
					
			}

			$data['site_setting'] = site_setting(); 
			
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/payment_setting',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
			
				//echo "else"; die;
				$this->payment_setting_model->payment_setting_update();
				
				$this->session->set_flashdata("success",PAYMENT_SETTING_UPDATE);
				$data['success'] = PAYMENT_SETTING_UPDATE;
				
				redirect("payment_setting/index");
		}
	}
	
	
	

  
	
	
}
?>
