<?php
/**
 * This class is used to create site setting page for admin panel.This class function create setting related functionality.   
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Site_setting extends CI_Controller {

    /**
        * It default constuctor which is called when Site_setting class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */
	function Site_setting()
	{
            /**
             * call base class contructor
             */
		parent::__construct();	
                /** load site_setting database class */
		$this->load->model('site_setting_model');
		$this->adminRights=getadminRights();
		(count($this->adminRights)==0 && !checkSuperAdmin())?redirect('home/dashboard/noRights'):'';
		$this->adminRights=(object)getadminRights();
	}
	
	/*** site setting home page
	**/
	
	/**
         * This function is used for update site related setting in DB.it will check whether admin is login or not than it will set validation rules for required field, than it create add_site page for update setting.
         * @returns void
         */
	function index()
	{
            /* commented code no loger use */
		/* check admin authentication */
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$data['success']='';
		$data["error"] = "";
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		/* load library for form validation and set rules*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules('site_name', 'Site Name', 'required');
		$this->form_validation->set_rules('site_online', 'Site Status', 'required');
		//$this->form_validation->set_rules('captcha_enable', 'Captcha Enable', 'required');
		//$this->form_validation->set_rules('site_version', 'Site Version', 'required');
		//$this->form_validation->set_rules('site_language', 'Site Language', 'required');
		$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'required');
		$this->form_validation->set_rules('currency_code', 'Currency Code', 'required');
		$this->form_validation->set_rules('date_format', 'Date Format', 'required');
		$this->form_validation->set_rules('time_format', 'Time Format', 'required');
		$this->form_validation->set_rules('date_time_format', 'Date/Time Format', 'required');
		$this->form_validation->set_rules('address_data', 'Address', 'required');
		/*$this->form_validation->set_rules('order_cancellation_time', 'Order Cancellation Time', 'required');
		$this->form_validation->set_rules('order_close_time', 'Order Close Time', 'required');
		$this->form_validation->set_rules('google_map_key', 'Google Map Key', 'required');
		$this->form_validation->set_rules('default_longitude', 'Longitude', 'required');
		$this->form_validation->set_rules('default_latitude', 'Latitude', 'required');*/
		$this->form_validation->set_rules('site_email', 'Site Email', 'required');	
		$this->form_validation->set_rules('admin_email', 'Admin Email', 'required');
		//$this->form_validation->set_rules('skype_id', 'Skype Id', 'required');
		
		
		/*$this->form_validation->set_rules('facebook_link', 'Facebook Link', 'required');
		$this->form_validation->set_rules('twitter_link', 'Twitter Link', 'required');
		$this->form_validation->set_rules('instagram_link', 'Instagram Link', 'required');	*/	
		$this->form_validation->set_rules('contact_number', 'Contact Number', 'required');	
		
		//$this->form_validation->set_rules('shipping_charge', 'Shipping Charges', 'required');		
			
		 
		$data['adminRights']=$this->adminRights;
		
		/**
                 * check validation for update data in DB
                 */
		if($this->form_validation->run() == FALSE){
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
                        /* check site_setting_id exist or not.if exist save if condition data in array otherwise else condition data in array*/
			if($this->input->post('site_setting_id'))
			{
				$one_site_setting = site_setting(); 
				//print_r($one_site_setting); die;
				$data["site_setting_id"] = $this->input->post('site_setting_id');
				$data["site_name"] = $this->input->post('site_name');
				$data["site_online"] = $this->input->post('site_online');
				//$data["captcha_enable"] = $one_site_setting->captcha_enable;
				//$data["site_version"] = $one_site_setting->site_version;
				$data["site_language"] = $one_site_setting->site_language;
				$data["currency_symbol"] = $one_site_setting->currency_symbol;
				$data["currency_code"] = $one_site_setting->currency_code;
				$data["date_format"] = $one_site_setting->date_format;
				$data["time_format"] = $one_site_setting->time_format;
				$data["date_time_format"] = $one_site_setting->date_time_format;
				$data["address_data"] = $one_site_setting->address_data;
				/*$data["zipcode_min"] = $one_site_setting->zipcode_min;
				$data["zipcode_max"] = $one_site_setting->zipcode_max;
				$data["google_map_key"] = $one_site_setting->google_map_key;
				$data["default_longitude"] = $one_site_setting->default_longitude;
				$data["default_latitude"] = $one_site_setting->default_latitude;*/
				$data["site_email"] = $one_site_setting->site_email;	
				$data["admin_email"] = $one_site_setting->admin_email;	
				/*$data["order_close_time"] = $one_site_setting->order_close_time;	
				$data["order_cancellation_time"] = $one_site_setting->order_cancellation_time;	*/
				$data["address_data"] = $one_site_setting->address_data;	
				$data["admin_email"] = $one_site_setting->admin_email;	
				
				/*$data["facebook_link"] = $one_site_setting->facebook_link;	
				$data["twitter_link"] = $one_site_setting->twitter_link;		
				$data["instagram_link"] = $one_site_setting->instagram_link;	*/
				$data["contact_number"] = $one_site_setting->contact_number;			
				
				/*$data["fullday_buy"] = $one_site_setting->fullday_buy;	
				$data["skype_id"] = $one_site_setting->skype_id;	
				$data["shipping_charge"] = $one_site_setting->shipping_charge;	*/
					
					
					
			}else{
				$one_site_setting = site_setting(); 
				
				$social_seting=$this->site_setting_model->get_one_social_data();
				//print_r($social_seting); die;
				
				$data["site_setting_id"] = $one_site_setting->site_setting_id;
				$data["site_name"] = $one_site_setting->site_name;
				$data["site_online"] = $one_site_setting->site_online;
				//$data["captcha_enable"] = $one_site_setting->captcha_enable;
				//$data["site_version"] = $one_site_setting->site_version;
				$data["site_language"] = $one_site_setting->site_language;
				$data["currency_symbol"] = $one_site_setting->currency_symbol;
				$data["currency_code"] = $one_site_setting->currency_code;
				$data["date_format"] = $one_site_setting->date_format;
				$data["time_format"] = $one_site_setting->time_format;
				$data["date_time_format"] = $one_site_setting->date_time_format;
				$data["address_data"] = $one_site_setting->address_data;
				/*$data["zipcode_min"] = $one_site_setting->zipcode_min;
				$data["zipcode_max"] = $one_site_setting->zipcode_max;
				$data["google_map_key"] = $one_site_setting->google_map_key;
				$data["default_longitude"] = $one_site_setting->default_longitude;
				$data["default_latitude"] = $one_site_setting->default_latitude;*/
				$data["site_email"] = $one_site_setting->site_email;
				$data["admin_email"] = $one_site_setting->admin_email;
				/*$data["order_close_time"] = $one_site_setting->order_close_time;	
				$data["order_cancellation_time"] = $one_site_setting->order_cancellation_time;	*/
				$data["address_data"] = $one_site_setting->address_data;		
				$data["admin_email"] = $one_site_setting->admin_email;		

				/*$data["facebook_link"] = $one_site_setting->facebook_link;	
				$data["twitter_link"] = $one_site_setting->twitter_link;		
				$data["instagram_link"] = $one_site_setting->instagram_link;	*/
				$data["contact_number"] = $one_site_setting->contact_number;	
				
				
				/*$data["fullday_buy"] = $one_site_setting->fullday_buy;
				$data["skype_id"] = $one_site_setting->skype_id;			
				$data["shipping_charge"] = $one_site_setting->shipping_charge;				*/
			}

			$data['site_setting'] = site_setting(); 
			/* according to site_setting_id it will create add_site page*/
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/add_site',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				//echo "else"; die;
				$this->site_setting_model->site_setting_update();
				$this->session->set_flashdata("success",SITE_SETTING_UPDATE);
				$one_site_setting = site_setting(); 
				
				$data['success'] = SITE_SETTING_UPDATE;
				
				$data["site_setting_id"] = $this->input->post('site_setting_id');
				$data["site_name"] = $this->input->post('site_name');
				$data["site_online"] = $this->input->post('site_online');
				//$data["captcha_enable"] = $this->input->post('captcha_enable');
				//$data["site_version"] = $this->input->post('site_version');
				$data["site_language"] = $this->input->post('site_language');
				$data["currency_symbol"] = $this->input->post('currency_symbol');
				$data["currency_code"] = $this->input->post('currency_code');
				$data["date_format"] = $this->input->post('date_format');
				$data["time_format"] = $this->input->post('time_format');
				$data["date_time_format"] = $this->input->post('date_time_format');
				$data["how_it_works_video"] = $this->input->post('how_it_works_video');
				$data["zipcode_min"] = $this->input->post('zipcode_min');
				$data["zipcode_max"] = $this->input->post('zipcode_max');
				$data["google_map_key"] = $this->input->post('google_map_key');
				$data["default_longitude"] = $this->input->post('default_longitude');
				$data["default_latitude"] = $this->input->post('default_latitude');
				$data["site_email"] = $this->input->post('site_email');
				$data["order_close_time"] = $this->input->post('order_close_time');	
				$data["order_cancellation_time"] = $this->input->post('order_cancellation_time');
				$data["address_data"] = $this->input->post('address_data');
				$data["admin_email"] = $this->input->post('admin_email');
				
				/*$data["facebook_link"] = $this->input->post('facebook_link');	
				$data["twitter_link"] = $this->input->post('twitter_link');		
				$data["instagram_link"] = $this->input->post('instagram_link');*/
				$data["contact_number"] = $this->input->post('contact_number');
				
				$data["fullday_buy"] = $this->input->post('fullday_buy');
				$data["skype_id"] = $this->input->post('skype_id');
				$data["shipping_charge"] = $this->input->post('shipping_charge');
				
				
				$data['site_setting'] = site_setting();
				//$data['currency'] = get_currency();	
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/add_site',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

			$this->template->render();
		}		
	}
	 /**
         * This function functionality is committed.
         */
	function add_site_setting()
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('site_name', 'Site Name', 'required');
		$this->form_validation->set_rules('site_email', 'Site Email', 'required|email');
		$this->form_validation->set_rules('google_analytics', 'Google cnalytics code', 'required');
		$this->form_validation->set_rules('google_map_key', 'Google map key', 'required');
		$this->form_validation->set_rules('facebook_application_id', 'Facebook application Id', 'required');
		$this->form_validation->set_rules('facebook_api_key', 'Facebook api key', 'required');
		$this->form_validation->set_rules('facebook_secret_key', 'Facebook secret key', 'required');
		$this->form_validation->set_rules('twitter_consumer_key', 'Twitter consumer key', 'required');
		$this->form_validation->set_rules('twitter_secret_key', 'Twitter secret key', 'required');
		
		
		if($this->form_validation->run() == FALSE){
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($this->input->post('site_setting_id'))
			{
				$one_site_setting = site_setting(); 
				
				$data["site_setting_id"] = $this->input->post('site_setting_id');
				$data["site_name"] = $this->input->post('site_name');
				
				$data["google_map_key"] = $this->input->post('google_map_key');
				$data["google_analytics"] = $this->input->post('google_analytics');
				$data["site_email"] = $this->input->post('site_email');
				
				$data["facebook_application_id"] = $this->input->post('facebook_application_id');
				$data["facebook_api_key"] = $this->input->post('facebook_api_key');
				$data["facebook_secret_key"] = $this->input->post('facebook_secret_key');
			
				$data["twitter_consumer_key"] = $this->input->post('twitter_consumer_key');
				$data["twitter_secret_key"] = $this->input->post('twitter_secret_key');
				
				
			}else{
				$one_site_setting = site_setting(); 
				
				$social_seting=$this->site_setting_model->get_one_social_data();
				//print_r($social_seting); die;
				
				$data["site_setting_id"] = $one_site_setting->site_setting_id;
				$data["site_name"] = $one_site_setting->site_name;
				$data["google_analytics"] = $one_site_setting->google_analytics;
				$data["google_map_key"] = $one_site_setting->google_map_key;
				$data["site_email"] = $one_site_setting->site_email;
				
				$data["facebook_application_id"] = $social_seting['facebook_application_id'];
				$data["facebook_api_key"] = $social_seting['facebook_api_key'];
				$data["facebook_secret_key"] = $social_seting['facebook_secret_key'];
				
				$data["twitter_consumer_key"] = $social_seting['twitter_consumer_key'];
				$data["twitter_secret_key"] = $social_seting['twitter_secret_key'];
				
				
			}

			$data['site_setting'] = site_setting(); 
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/add_site',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			
			 $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
			$this->template->render();
		}else{
				//echo "else"; die;
				$this->site_setting_model->site_setting_update();
				$one_site_setting = site_setting(); Site_setting/
				
				//$data["error"] = "Site settings updated successfully.";
				$data["site_setting_id"] = $this->input->post('site_setting_id');
				
				$data["site_name"] = $this->input->post('site_name');
			    $data["google_analytics"] = $this->input->post('google_analytics');
				$data["google_map_key"] = $this->input->post('google_map_key');
				$data["site_email"] = $this->input->post('site_email');
				
				$data["facebook_application_id"] = $this->input->post('facebook_application_id');
				$data["facebook_api_key"] = $this->input->post('facebook_api_key');
				$data["facebook_secret_key"] = $this->input->post('facebook_secret_key');
				$data["twitter_consumer_key"] = $this->input->post('twitter_consumer_key');
				$data["twitter_secret_key"] = $this->input->post('twitter_secret_key');

				
				
				//$data['currency'] = get_currency();	
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			$this->template->write_view('center',$theme .'/layout/setting/add_site',$data,TRUE);

			$this->template->render();
		}				
	}
	 /**
         * This function functionality is committed.
         */
	
	function google_setting() {
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$theme = getThemeName();
		$this -> template -> set_master_template($theme . '/template.php');

		$this -> load -> library('form_validation');
		$this -> form_validation -> set_rules('google_client_id', 'Google Client Id', 'required');
		$this -> form_validation -> set_rules('google_url', 'Google Url', 'required');
		$this -> form_validation -> set_rules('google_login_enable', 'Google Login Enable', 'required');
		$this -> form_validation -> set_rules('google_client_secret', 'Google Client Secret', 'required|email');
       
        
		$data['page_name']="google_setting";
		
		if ($this -> form_validation -> run() == FALSE) {
			if (validation_errors()) {
				$data["error"] = validation_errors();
			} else {
				$data["error"] = "";
			}
			if ($this -> input -> post('google_setting_id')) {
				$$one_google_setting = $this->site_setting_model->google_setting();
				
				$data["google_setting_id "] = $this -> input -> post('google_setting_id ');
				$data["google_client_id"] = $this -> input -> post('google_client_id');
				$data["google_url"] = $this -> input -> post('google_url');
				$data["google_login_enable"] = $this -> input -> post('google_login_enable');
				$data["google_client_secret"] = $this -> input -> post('google_client_secret');
				
				
		
			} else {
				$one_google_setting = $this->site_setting_model->google_setting();
				//print_r($one_site_setting); die;	
				$data["google_setting_id"] = $one_google_setting -> google_setting_id ;
				$data["google_client_id"] = $one_google_setting -> google_client_id;
				$data["google_url"] = $one_google_setting -> google_url;
				$data["google_login_enable"] = $one_google_setting -> google_login_enable;
				$data["google_client_secret"] = $one_google_setting -> google_client_secret;
				
               
			
			}
			
			

			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/google',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

			$this->template->render();
		} else {
			$this -> site_setting_model -> site_google_update();
			
			$one_google_setting = $this->site_setting_model->google_setting();
			$data['success'] = "Google Setting updated successfully";
			$data["error"] = "";
			$data["google_setting_id"] = $this -> input -> post('google_setting_id');
			$data["google_client_id"] = $this -> input -> post('google_client_id');
			$data["google_url"] = $this -> input -> post('google_url');
			$data["google_login_enable"] = $this -> input -> post('google_login_enable');
			$data["google_client_secret"] = $this -> input -> post('google_client_secret');
			
     
			
			
			$data['page_name']="usercls";
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/google',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

			$this->template->render();
			$this -> template -> render();
		}
	}
	
	 /**
         * This function functionality is committed.
         */
	function facebook_setting() {
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$theme = getThemeName();
		$this -> template -> set_master_template($theme . '/template.php');

		$this -> load -> library('form_validation');
		$this -> form_validation -> set_rules('facebook_application_id', 'Facebook Application Id', 'required');
		$this -> form_validation -> set_rules('facebook_login_enable', 'Facebook Login Enable', 'required');
		$this -> form_validation -> set_rules('facebook_access_token', 'Facebook_Access Token', 'required');
		$this -> form_validation -> set_rules('facebook_api_key', 'Facebook Api Key', 'required');
		$this -> form_validation -> set_rules('facebook_secret_key', 'Facebook Secret Key', 'required');
		$this -> form_validation -> set_rules('facebook_user_autopost', 'Facebook User Autopost', 'required');
		$this -> form_validation -> set_rules('facebook_wall_post', 'Facebook Wall Post', 'required');
		$this -> form_validation -> set_rules('facebook_url', 'Facebook Url', 'required');
		
       
        
		$data['page_name']="facebook_setting";
		
		if ($this -> form_validation -> run() == FALSE) {
			if (validation_errors()) {
				$data["error"] = validation_errors();
			} else {
				$data["error"] = "";
			}
			if ($this -> input -> post('facebook_setting_id')) {
				$one_facebook_setting = $this->site_setting_model->facebook_setting();
				//print_r($one_facebook_setting); die;
				
				$data["facebook_setting_id"] = $this -> input -> post('facebook_setting_id');
				$data["facebook_login_enable"] = $this -> input -> post('facebook_login_enable');
				$data["facebook_application_id"] = $this -> input -> post('facebook_application_id');
				$data["facebook_access_token"] = $this -> input -> post('facebook_access_token');
				$data["facebook_api_key"] = $this -> input -> post('facebook_api_key');
				$data["facebook_secret_key"] = $this -> input -> post('facebook_secret_key');
				$data["facebook_user_autopost"] = $this -> input -> post('facebook_user_autopost');
				$data["facebook_wall_post"] = $this -> input -> post('facebook_wall_post');
				$data["facebook_url"] = $this -> input -> post('facebook_url');
				
				
		
			} else {
				$one_facebook_setting = $this->site_setting_model->facebook_setting();
				//print_r($one_facebook_setting); die;	
				$data["facebook_setting_id"] = $one_facebook_setting -> facebook_setting_id ;
				$data["facebook_login_enable"] = $one_facebook_setting -> facebook_login_enable;
				$data["facebook_application_id"] = $one_facebook_setting -> facebook_application_id ;
				$data["facebook_access_token"] = $one_facebook_setting -> facebook_access_token;
				$data["facebook_api_key"] = $one_facebook_setting -> facebook_api_key;
				$data["facebook_secret_key"] = $one_facebook_setting -> facebook_secret_key;
				$data["facebook_user_autopost"] = $one_facebook_setting -> facebook_user_autopost;
				$data["facebook_wall_post"] = $one_facebook_setting -> facebook_wall_post;
				$data["facebook_url"] = $one_facebook_setting -> facebook_url;
				
               
			
			}
			
			$data['facebook_setting'] = $this->site_setting_model->facebook_setting();

			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/facebook',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

			$this->template->render();
			
		} else {
			$this -> site_setting_model -> site_facebook_update();
			$one_facebook_setting = $this->site_setting_model->facebook_setting();
			
			    $data['success'] = "Facebook Setting updated successfully";
			    //$data["facebook_setting_id"] = $this -> input -> post('facebook_setting_id');
				$data["error"] = ''; 
				$data["facebook_setting_id"] = $this -> input -> post('facebook_setting_id');
				$data["facebook_application_id"] = $this -> input -> post('facebook_application_id');
				$data["facebook_login_enable"] = $this -> input -> post('facebook_login_enable');
				$data["facebook_access_token"] = $this -> input -> post('facebook_access_token');
				$data["facebook_api_key"] = $this -> input -> post('facebook_api_key');
				$data["facebook_secret_key"] = $this -> input -> post('facebook_secret_key');
				$data["facebook_user_autopost"] = $this -> input -> post('facebook_user_autopost');
				$data["facebook_wall_post"] = $this -> input -> post('facebook_wall_post');
				$data["facebook_url"] = $this -> input -> post('facebook_url');
     		
			
			
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/facebook',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

			$this->template->render();
		}
	}
 /**
         * This function functionality is committed.
         */

  function add_image_setting()
	{
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$data = array();
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_width', 'User image width', 'required|numeric');
		$this->form_validation->set_rules('user_height', 'User image height', 'required|numeric');
		$this->form_validation->set_rules('product_width', 'Product width', 'required|numeric');
		$this->form_validation->set_rules('product_height', 'Product height', 'required|numeric');
		$this->form_validation->set_rules('gift_card_width', 'Gift Card width', 'required|numeric');
		$this->form_validation->set_rules('gift_card_height', 'Gift Card height', 'required|numeric');

		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($this->input->post('image_setting_id'))
			{
				$one_image_setting = $this->site_setting_model->image_setting(); 
				
				$data["image_setting_id"] = $this->input->post('image_setting_id');
				$data["user_width"] = $this->input->post('user_width');
				$data["user_height"] = $this->input->post('user_height');
				$data["product_width"] = $this->input->post('product_width');
				$data["product_height"] = $this->input->post('product_height');
				$data["gift_card_width"] = $this->input->post('gift_card_width');
				$data["gift_card_height"] = $this->input->post('gift_card_height');	
				
				
			}else{
				$one_image_setting = $this->site_setting_model->image_setting(); 

				$data["image_setting_id"] = $one_image_setting->image_setting_id;
				$data["user_width"] = $one_image_setting->user_width;
				$data["user_height"] = $one_image_setting->user_height;
				$data["product_width"] = $one_image_setting->product_width;
				$data["product_height"] = $one_image_setting->product_height;
				$data["gift_card_width"] = $one_image_setting->gift_card_width;
				$data["gift_card_height"] = $one_image_setting->gift_card_height;
				
			}
			
			
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/add_image_setting',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

			$this->template->render();
			
			
		}else{
				$this->site_setting_model->image_setting_update();
				$one_image_setting = $this->site_setting_model->image_setting(); 
				
				$data["success"] = IMAGE_SETTING_UPDATE;
				$data["error"]= '';
				$data["image_setting_id"] = $this->input->post('image_setting_id');
				$data["user_width"] = $this->input->post('user_width');
				$data["user_height"] = $this->input->post('user_height');
				$data["product_width"] = $this->input->post('product_width');
				$data["product_height"] = $this->input->post('product_height');
				$data["gift_card_width"] = $this->input->post('gift_card_width');
				$data["gift_card_height"] = $this->input->post('gift_card_height');	

				
				
			$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
	   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
			if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
			$this->template->write_view('center',$theme .'/layout/setting/add_image_setting',$data,TRUE);
			}else{
				$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
			}
			$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

			$this->template->render();
		}				
	}

	function popup_setup()
	{
		//echo "comning soon";die;
		
		$data = array();
		$data['success']='';
		$data["error"] = "";
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$data['admin_setup'] = $this->site_setting_model->get_admin_setup();
		$data['user_setup'] = $this->site_setting_model->get_user_setup();
		$data['maintenance'] = $this->site_setting_model->get_maintenance_detail();
		$data['tot_step'] = $this->site_setting_model->get_tot_steps();
		
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
   	    $this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		if((isset($this->adminRights->Setting) && $this->adminRights->Setting->add==1) || checkSuperAdmin()){
		$this->template->write_view('center',$theme .'/layout/setting/popup_setup',$data,TRUE);
		}else{
			$this->template->write_view('center',$theme .'/layout/noRights/noRights',$data,TRUE);
		}
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);

		$this->template->render();
	}
	
	function save_step()
	{
		if($_POST['step_id'] > 0){
			$step = $this->site_setting_model->update_step($_POST['step_id']);
		}else{
			$step = $this->site_setting_model->add_step();
		}
		
		$step_array =  $this->site_setting_model->get_one_step($step);
		echo json_encode($step_array);
	}
	
	function remove_step($step_id)
	{
		$data["is_deleted"] = '1';
		
		$this->db->where('as_step_id',$step_id);
		$this->db->update('popup_setup',$data);
		echo  $step_id;
	}
	
	function save_maintenance_step()
	{
		if($_POST['id'] > 0){
			$step = $this->site_setting_model->update_maintenance($_POST['id']);
		}
		
		$step_array =  $this->site_setting_model->get_maintenance_detail($step);
		echo json_encode($step_array);
	}
  
  	function update_steps()
	{
		if($_POST['previous_step']!='empty'){
			
			$current_step = getStep($_POST['current_step']);
			$previous_step = getStep($_POST['previous_step']);
			
			$data_current = array(
				'as_step_sequence'=>$previous_step
			);
			
			$this->db->where('as_step_id',$_POST['current_step']);
			$this->db->update('popup_setup',$data_current); 
			
			$data_previous = array(
				'as_step_sequence'=>$current_step
			);
			
			$this->db->where('as_step_id',$_POST['previous_step']);
			$this->db->update('popup_setup',$data_previous); 
			
			echo getStepDetail($_POST['current_step']);
		}
		
		if($_POST['next_step'] != 'empty'){
			
			 $current_step = getStep($_POST['current_step']);
			 $next_step = getStep($_POST['next_step']);
			
			$data_next = array(
				'as_step_sequence'=>$current_step
			);
			
			$this->db->where('as_step_id',$_POST['next_step']);
			$this->db->update('popup_setup',$data_next); 
			
			$data_current = array(
				'as_step_sequence'=>$next_step
			);
			
			$this->db->where('as_step_id',$_POST['current_step']);
			$this->db->update('popup_setup',$data_current); 
			
			echo getStepDetail($_POST['current_step']);
		}
		
	}
}
?>
