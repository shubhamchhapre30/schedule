<?php

require_once APPPATH."libraries/chargify_lib/Chargify.php";
/**
 * This controller class is used for show setting functionality,and it manage company administration and user settings. 
 * There is following settings on setting page.
 * Company administration
     General settings
     Company settings
     Default Calendar
     Task Settings
     Users
 
 * User settings
     General
     Default Calendar
     Colour
     Swim lanes
     Change Password
 
 * This class is extending the SPACULLUS_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Settings extends SPACULLUS_Controller {

	 /**
        * It default constuctor which is called when Settings class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	function Settings () {
            /* call base class constructor */
             
		parent :: __construct ();
                /*
                 * load Amazon S3 server Configuration library
                 */
		$this->load->library('s3');
                /*
                 * load Amazon S3 server Configuration file
                 */
		$this->config->load('s3');
                /*
                 * load payment related class file
                 */
		$this->config->load('chargify');
                /*
                 * load encrypt class library
                 */
		$this->load->library ("encrypt");
                /*
                 * load form validation library
                 */
		$this->load->library('form_validation');
                /*
                 * load home_model class for database
                 */
		$this->load->model('home_model');
                /*
                 * This libary is used for remote servers via cURL
                 */
		$this->load->library('Curl');
                /*
                 * set default timezone 
                 */
		date_default_timezone_set("UTC");
               
                ini_set('max_execution_time', 300);
        }

	/**
         * When user click on setting link on admin page at that time this method will render setting page.
         * It will check user authentication otherwise redirect on home page.
         * Than it gets all company  related information in db and create setting page.
         * @param  $msg
         * @returns setting view
         */
	function index($msg = ''){
		/* check authentication otherwise redirect on home page */
		if(!check_user_authentication()) {
			redirect ('home');
		}
		
		if($this->session->userdata('is_administrator')=='0') {
			redirect('user/dashboard');
		}
		if(!$this->session->userdata('company_id')){
			redirect('user/dashboard');
		}
		
		$this->config->load('s3');
		$this->config->load('chargify');
		
		$username = $this->config->item('API_key');
		$password = $this->config->item('API_key_pass');
	
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['msg'] = $msg;
		$data['theme'] = $theme;
		$data['countries'] = get_all_country();
		$data['billing'] ="";
		$data['credit_card'] = ""; 
		$data['expiry_date'] = "";
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['site_setting'] = site_setting();
		
		/* create new chargifysubscription object*/
		$test = TRUE;
		$sub = new ChargifySubscription(NULL,$test);
		$query=$this->db->get_where('users',array('user_id'=>$this->session->userdata('user_id')));
		$use=$query->row();
		$query1=$this->db->get_where('users',array('company_id'=>$this->session->userdata('company_id'),'is_administrator'=>'1'));
		$company_user=$query1->row();
		
		if($company_user->chargify_subscriptions_ID != '')
		{
			try{
				//$company_user->chargify_subscriptions_ID='12008899';
				$sub_detail = @$sub->getByID($company_user->chargify_subscriptions_ID);
                                if(isset($sub_detail->credit_card)){
                                        $data['credit_card'] = $sub_detail->credit_card->masked_card_number; 
                                }else{
                                        $data['credit_card'] = ""; 
                                }
                                if(isset($sub_detail->credit_card)){
                                        $data['expiry_date'] = $sub_detail->credit_card->expiration_month."/".$sub_detail->credit_card->expiration_year;
                                }else{
                                        $data['expiry_date'] = "";
                                }
				//pr($sub_detail);die;
			} catch(ChargifyValidationException $cve) {
				
				$data["error"]=$cve->getMessage();
				
			}catch(ChargifyConnectionException $ex){
                                $data['credit_card'] = "error";
                        }
			
			
			// Statement 
			
			$test = TRUE;
			$statements = new ChargifyStatement(NULL,$test);
			
			try{
				$data['statements'] = @$statements->getAllBySubscriptionId($company_user->chargify_subscriptions_ID);
				if($data['statements']){
					usort($data['statements'], "cmp");
				}
				
			} catch(ChargifyValidationException $cve) {
				$data['statements'] = "";
				$data["error"]=$cve->getMessage();
				
			}catch(ChargifyConnectionException $e1){
                            
                        }
			// Statement Ends 
		}
		
		$company =  get_one_company($this->session->userdata('company_id'));
		$calender = get_calender_settings($this->session->userdata('company_id'));
		if($calender){
			$data['fisrt_day_of_week'] = $calender->fisrt_day_of_week;
			$data['MON_hours'] = minutesToTime($calender->MON_hours);
			$data['TUE_hours'] = minutesToTime($calender->TUE_hours);
			$data['WED_hours'] = minutesToTime($calender->WED_hours);
			$data['THU_hours'] = minutesToTime($calender->THU_hours);
			$data['FRI_hours'] = minutesToTime($calender->FRI_hours);
			$data['SAT_hours'] = minutesToTime($calender->SAT_hours);
			$data['SUN_hours'] = minutesToTime($calender->SUN_hours);
			$data['MON_hours_min'] = $calender->MON_hours;
			$data['TUE_hours_min'] = $calender->TUE_hours;
			$data['WED_hours_min'] = $calender->WED_hours;
			$data['THU_hours_min'] = $calender->THU_hours;
			$data['FRI_hours_min'] = $calender->FRI_hours;
			$data['SAT_hours_min'] = $calender->SAT_hours;
			$data['SUN_hours_min'] = $calender->SUN_hours;
			$data['MON_closed'] = $calender->MON_closed;
			$data['TUE_closed'] = $calender->TUE_closed;
			$data['WED_closed'] = $calender->WED_closed;
			$data['THU_closed'] = $calender->THU_closed;
			$data['FRI_closed'] = $calender->FRI_closed;
			$data['SAT_closed'] = $calender->SAT_closed;
			$data['SUN_closed'] = $calender->SUN_closed;
		}else{
			$data['fisrt_day_of_week'] = "Monday";
			$data['MON_hours'] = "8h";
			$data['TUE_hours'] = "8h";
			$data['WED_hours'] = "8h";
			$data['THU_hours'] = "8h";
			$data['FRI_hours'] = "8h";
			$data['SAT_hours'] = "0m";
			$data['SUN_hours'] = "0m";
			$data['MON_hours_min'] = "480";
			$data['TUE_hours_min'] = "480";
			$data['WED_hours_min'] = "480";
			$data['THU_hours_min'] = "480";
			$data['FRI_hours_min'] = "480";
			$data['SAT_hours_min'] = "0";
			$data['SUN_hours_min'] ="0";
			$data['MON_closed'] = "1";
			$data['TUE_closed'] = "1";
			$data['WED_closed'] = "1";
			$data['THU_closed'] = "1";
			$data['FRI_closed'] = "1";
			$data['SAT_closed'] = "0";
			$data['SUN_closed'] = "0";
		}
		
		
		/* get company setting related info. */
		$data['company_id'] = $company->company_id;
		$data['company_name'] = $company->company_name;
		$data['company_email'] = $company->company_email;
		$data['company_address'] = $company->company_address;
		$data['company_logo'] = $company->company_logo;
		$data['country_id'] = $company->country_id;
		$data['company_phoneno'] = $company->company_phoneno;
		$data['company_date_format'] = $company->company_date_format;
		$data['company_timezone'] = $company->company_timezone;
		$data['currency'] = get_currency_list();	
		$data['divisions'] = get_company_division($this->session->userdata('company_id'));
		$data['departments'] = get_company_department($this->session->userdata('company_id'));
		$data['xero_account_info']  = get_xero_account_info();
                $data['timezone'] = get_timezone();
		$data['app_info'] = getAppInfo();
                
		$compay_flags = $this->config->item('company_flags');
		$data['actual_time_on'] = $compay_flags['actual_time_on'];
		$data['allow_past_task'] = $compay_flags['allow_past_task'];
		$data['user_id'] = $this->session->userdata('user_id');
		$data['skills'] = get_company_skills($this->session->userdata('company_id'));
		$data['total_company_users']= count_user_by_company($this->session->userdata("company_id"));
                $data['total_company_projects'] = count_total_company_project();
                $data['total_company_customers'] = count_total_company_customers();
		$data['staffLevels'] = get_company_staffLevels($this->session->userdata('company_id'));
		
		$data['ParentTaskCategory'] = get_company_category($this->session->userdata('company_id'));
		
		$data['taskStatus'] = get_taskStatus($this->session->userdata('company_id'),'Active');
		
		$this->load->model('user_model');	
		$data['user'] = $this->user_model->get_user_list($this->session->userdata('company_id'));
		$data['timezone'] = get_timezone();
		$data['staff_levels'] = get_company_staffLevels($this->session->userdata('company_id'),'Active');
		$data['managers'] = $this->user_model->get_managers($this->session->userdata('company_id'));
		
		$company_division = get_company_division($this->session->userdata('company_id'),'Active');
		$company_division_quots = '';
		if($company_division){
			foreach($company_division as $div){
				$company_division_quots .= "'".$div->devision_title."',";
			}
			$company_division_quots = substr($company_division_quots, 0,-1);
		}
		$data['company_division'] = $company_division_quots;
		
		/*
		$company_department = get_company_department($this->session->userdata('company_id'),'Active');
				$company_department_quots = '';
				if($company_department){
					foreach($company_department as $dep){
						$company_department_quots .= "'".$dep->department_title."',";
					}
					$company_department_quots = substr($company_department_quots, 0,-1);
				}
				$data['company_department'] = $company_department_quots;*/
		
		
		$company_skills = get_company_skills($this->session->userdata('company_id'),'Active');
		$company_skills_quots = '';
		if($company_skills){
			foreach($company_skills as $sk){
				$company_skills_quots .= "'".$sk->skill_title."',";
			}
			$company_skills_quots = substr($company_skills_quots, 0,-1);
		}
		$data['company_skills'] = $company_skills_quots;
		$data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		$data['task_status'] = get_taskStatus($this->session->userdata('company_id'),'Active'); 
                $data['customer_users'] = get_customer_user_list();
                $data['customers']=  getCustomerList();
               // pr($data['customer_users']); die();
		/* create setting page */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/settings/index',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
		
	}
        /**
         * This function will call when user click on billing option on setting and it will render billing page of admin.
         * @param  $msg
         * @returns billingview
         */
	function billing($msg = ''){
		
		/*
                 *check user authentication 
                 */
		if(!check_user_authentication()) {
			redirect ('home');
		}
		
		if($this->session->userdata('is_administrator')=='0') {
			redirect('user/dashboard');
		}
		if(!$this->session->userdata('company_id')){
			redirect('user/dashboard');
		}
		
		$this->config->load('s3');
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$data['msg'] = $msg;
		$data['theme'] = $theme;
		$data['user_id'] = $this->session->userdata('user_id');
		
		/* create billing page */
		$this->template->write_view('header',$theme .'/layout/common/header2',$data,TRUE);
		$this->template->write_view('content_left',$theme .'/layout/common/leftsidebar',$data,TRUE);
		$this->template->write_view('content_side',$theme .'/layout/settings/billing',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer2',$data,TRUE);
		$this->template->render();
		
	}
        /**
         * In billing setting,when user will click on access portal option this method is called.
         * This function is used for open company payment related data via the help of curl.
         * @returns void
         */
	function accessportal()
	{
            /* load chargify config */
		$this->config->load('chargify');
		/* access username & password of s3*/
		$username = $this->config->item('API_key');
		$password=$this->config->item('API_key_pass');
		$query1=$this->db->get_where('users',array('company_id'=>$this->session->userdata('company_id'),'is_owner'=>'1'));
		$company_user=$query1->row();
                /* check chargify customer id of loggedin user */
		
		if($company_user->new_link_available_at != "0000-00-00" && (strtotime($company_user->expires_at)>strtotime(date("Y-m-d")))){
			$result = array();
			$result['url'] = $company_user->management_link;
			$result['fetch_count'] = $company_user->fetch_count;
			$result['created_at'] = $company_user->created_at;
			$result['new_link_available_at'] = $company_user->new_link_available_at;
			$result['expires_at'] = $company_user->expires_at;
			echo json_encode($result);die;
		} else {
			if($company_user->chargify_customer_id != '')
			{
				$headers = array(
			    	'Accept:application/json',
				);
				$url = 'https://schedullo.chargify.com/portal/customers/'.$company_user->chargify_customer_id.'/management_link.json';
				
				$ch = curl_init();
		              
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
				curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
				curl_setopt($ch, CURLOPT_URL,$url);
				
				$result = curl_exec($ch);
				
				if(trim($result)!=''){
					$result1 = json_decode($result,TRUE);
					if(isset($result1['url'])){
						
						$data_link = array(
							'management_link'=>trim($result1['url']),
							'fetch_count'=>$result1['fetch_count'],
							'created_at'=>date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-",$result1['created_at']))),
							'new_link_available_at'=>date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-",$result1['new_link_available_at']))),
							'expires_at'=> date("Y-m-d", strtotime(str_replace(array("/"," ",","), "-",$result1['expires_at']))),
						);
						
						$this->db->where('user_id',$company_user->user_id);
						$this->db->update('users',$data_link);
					}
				}
				curl_close($ch);
				echo trim($result);die;
			}
		}
		
		
	}


	function generateStatement()
	{
            /*load chargify config*/
		$this->config->load('chargify');
		$statement_id = $_POST['statement_id'];
                /**
                 * access chargify username and password
                 */
		$username = $this->config->item('API_key');
		$password=$this->config->item('API_key_pass');
		$query1=$this->db->get_where('users',array('company_id'=>$this->session->userdata('company_id'),'is_administrator'=>'1'));
		$company_user=$query1->row();
                 /* check statement id for generate pdf of statement*/
		if($statement_id != '')
		{
			$url = 'https://schedullo.chargify.com/statements/'.$statement_id.'.pdf';
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			$result = curl_exec($ch); 
			
			header('Cache-Control: public'); 
			header('Content-type: application/pdf');
			header('Content-Length: '.strlen($result));
			
			curl_close($ch);
			$destination = "./".$statement_id.".pdf";
			$file = fopen($destination, "w+");
			fputs($file, $result);
			echo $statement_id.'.pdf';die;
		}
	}
        /**
         * This function is used for delete file from folder.
         * @returns void
         */
	function deleteExistFile()
	{
		$file = $this->input->post('file');
		if(file_exists(base_path().$file))
		{
			unlink(base_path().$file);
			echo "success";die;
		}
		else{
			echo "fail";die;
		}
		
	}
        /**
         * when admin change company name at same time this function will update data in db.
         * @returns void
         */
	function genral(){
	
		$name = isset($_POST['name'])?$_POST['name']:'';
		$value = isset($_POST['value'])?htmlspecialchars($_POST['value']):'';
		$data = array(
			$name => $value
		);
		$this->db->where("company_id",$this->session->userdata("company_id"));
		$this->db->update("company",$data);
	}
	/**
         * This function will call when user admin will change company logo.It will change company logo using S3 library and class.
         * @returns void
         */
	function companyLogo(){
		
		$company_logo='';
		$s3_company_logo = '';
     
        if($_FILES['company_logo']['name']!='')
        {
        	$this->load->library('upload');
            $rand=rand(0,100000); 
			  
	        $_FILES['userfile']['name']     =   $_FILES['company_logo']['name'];
	        $_FILES['userfile']['type']     =   $_FILES['company_logo']['type'];
	        $_FILES['userfile']['tmp_name'] =   $_FILES['company_logo']['tmp_name'];
	        $_FILES['userfile']['error']    =   $_FILES['company_logo']['error'];
	        $_FILES['userfile']['size']     =   $_FILES['company_logo']['size'];
   
			$config['file_name'] = $rand.'Company';
			
            $config['upload_path'] = base_path().'upload/company_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 			
            $this->upload->initialize($config);
            /* upload file */
            if (!$this->upload->do_upload())
		{
		    $error =  $this->upload->display_errors();
                    echo "not"; die();
		} 
	    $picture = $this->upload->data();
		   
            $this->load->library('image_lib');
		   
            $this->image_lib->clear();
		   	
		   	$gd_var='gd2';
			
			list($width, $height, $type, $attr) = getimagesize($_FILES['company_logo']['tmp_name']);
			
			$new_width = (($width*40)/$height);
				
			$this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/company_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/company/'.$picture['file_name'],
				'maintain_ratio' => TRUE,
				'quality' => '100%',
				'width' => $new_width,
				'height' => 40
			 ));
			
			
			if(!$this->image_lib->resize())
			{
				$error = $this->image_lib->display_errors();
			}
			$new_image = $this->image_lib->new_image;
		 
	
			$bucket = $this->config->item('bucket_name');
		
                    $name = $_FILES['company_logo']['name'];
			$size = $_FILES['company_logo']['size'];
			$tmp = $_FILES['company_logo']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			if(in_array($ext,$valid_formats)){
				
				
				$s3_company_logo = $rand.'Company.'.$ext;
			    $actual_image_name = "upload/company_orig/".$s3_company_logo;
				$new_actual_image_name = "upload/company/".$s3_company_logo;
	 			/* It call putObjectFile for upload image in folder.*/
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, CI_S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, CI_S3::ACL_PUBLIC_READ)){
						if(file_exists(base_path().'upload/company/'.$picture['file_name']))
						{
							$link=base_path().'upload/company/'.$picture['file_name'];
							unlink($link);
						}
					}
					if(file_exists(base_path().'upload/company_orig/'.$picture['file_name']))
					{
						$link=base_path().'upload/company_orig/'.$picture['file_name'];
						unlink($link);
					}
					if($this->input->post('hdn_company_logo')!='')
					{
						$delete_image_name = "upload/company_orig/".$this->input->post('hdn_company_logo');
						$delete_image_name1 = 'upload/company/'.$this->input->post('hdn_company_logo');
						
						$this->s3->deleteObject($bucket,$delete_image_name);
						$this->s3->deleteObject($bucket,$delete_image_name1);
					}
					$msg = "success";
				} else {
					$msg = "fail";
	
				}
			} else {
				$msg = "invalid";
			}
			
		} else {
			if($this->input->post('hdn_company_logo')!='')
			{
				$s3_company_logo=$this->input->post('hdn_company_logo');
			}
		}
		
		$data = array(
			'company_logo' => $s3_company_logo
		);
		$this->db->where('company_id',$this->session->userdata('company_id'));
		$this->db->update('company',$data);
		$pass = $this->config->item('s3_display_url').'upload/company/'.$s3_company_logo;
		echo json_encode($pass);die;
	}
        /**
         * This function checks company name exist or not.
         * @returns int
         */
	function is_company_name_exists(){
		$value = isset($_POST["name"])?$this->input->post('name'):'';
		$query = $this->db->query("select company_name from ".$this->db->dbprefix('company')." where company_name= '".$value."' and company_id!='".$this->session->userdata('company_id')."' and is_deleted = 0");
		if($query->num_rows()>0){
			echo "1";
		} else {
			echo "0";
		}
		die;
	}
	/**
         * This function is used for check company email in db.
         * @returns int
         */
	function is_company_email_exists(){
		$value = isset($_POST["value"])?$this->input->post('value'):'';
		$query = $this->db->query("select company_email from ".$this->db->dbprefix('company')." where company_email= '".$value."' and company_id!='".$this->session->userdata('company_id')."' and is_deleted = 0");
		if($query->num_rows()>0){
			echo "1";
		} else {
			echo "0";
		}
		die;
	}

	/**
         * This function is used to check is company name exist or not.
         * @param  $company
         * @return boolean
         */
	function company_check($company)
	{
		$username = $this->home_model->company_unique($company);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('company_check', 'There is an existing company associated with this Name');
			return FALSE;
		}
	}	
	
	/* 
	 * Function : company_email_check
	 * Author : Spaculus
	 * Desc : This function is used to check is company email exist or not
	 */
        /**
         * It will check company email in db
         * @param type $company
         * @return boolean
         */
	function company_email_check($company)
	{
		$username = $this->home_model->company_email_unique($company);
		if($username == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('company_email_check', 'There is an existing company Email address with this Name');
			return FALSE;
		}
	}	
	
	/* 
	 * Function : add_division
	 * Author : Spaculus
	 * Desc : This function is used to add division of company
	 */
	/**
         * This function will insert division and department names in db.When admin add new divisions at the same time this function is called for update db.
         * @returns json
         */
	function add_division(){
		/* check user authentication */
		if (!check_user_authentication()) {
			redirect ('home');
		}
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		$devision_title=  htmlspecialchars($this->input->post('division_name'));
                $division_count = get_company_division_count();
		/* get value and insert data into a DB*/
		$data = array(
			'devision_title' => $devision_title,
			'devision_status' => $this->input->post('status'),
			'company_id' => $this->session->userdata('company_id'),
			'date_added' => date('Y-m-d H:i:s'),
                        'seq' => $division_count +1
		);
		$this->db->insert('company_divisions',$data);
		$division_id = $this->db->insert_id();
		
		$department_data = array(
			'department_title' => 'General',
			'deivision_id' => $division_id,
			'company_id' => $this->session->userdata('company_id'),
			'status' => 'Active',
			'dept_added_date' => date('Y-m-d H:i:s'),
                        'department_seq' => '1'
		);
		$this->db->insert('company_departments',$department_data);
		
		$pass_data['deivision_id'] = $division_id;
		$pass_data['devision_title'] = $devision_title;
		$pass_data['devision_status'] = $this->input->post('status');
		$pass_data['divisions'] = get_company_division($this->session->userdata('company_id'));
		echo json_encode($pass_data);die;
	}
        /**
         * When admin enter new division at before this function will check division name in db.
         * @returns int
         */
	function chk_divisionName_exists(){
		$name = $this->input->post('name');
		$company_id = $this->input->post('company_id');
		$division_id = isset($_POST['division_id'])?$this->input->post('division_id'):'';
		/* check division id*/
		if($division_id)
		{
			$query = $this->db->query("select devision_title from ".$this->db->dbprefix('company_divisions')." where devision_title= '$name' and company_id = '".$company_id."' and division_id != '".$division_id."' and is_delete =0");
			
		} else {
			$query = $this->db->query("select devision_title from ".$this->db->dbprefix('company_divisions')." where devision_title= '$name' and company_id = '".$company_id."' and is_delete =0");
		}
		if($query->num_rows()>0){
			echo "1";
		}else{
			echo "0";
		}
		
	}
	
	
        /**
         * When admin will update division name on admin setting page at the same moment this function update division name in db.
         * @returns void
         */
	function update_division_name(){
                $division_title=  htmlspecialchars($this->input->post('value'));
		$data = array(
			'devision_title' => $division_title
		);
		$this->db->where('division_id',$this->input->post('name'));
		$this->db->update('company_divisions',$data);
		
		$return['divisions'] = get_company_division($this->session->userdata('company_id'));
		echo json_encode($return);die;
	}
	/**
         * This function will update division status in db on admin setting page.
         * @returns void
         */
	function update_division_status(){
		$division_id = $this->input->post('id');
		$value = $this->input->post('val');
		$data = array("devision_status"=>$value);
		$this->db->where('division_id',$division_id);
		$this->db->update('company_divisions',$data);
	}
        /**
         * When admin click on delete icon this function will call for delete division in db.
         * @returns json
         */
	function delete_division(){ 
		$data = array("is_delete"=>"1");
		$this->db->where("division_id",  $this->input->post('id'));
		$this->db->update('company_divisions',$data);
		
		$return['divisions'] = get_company_division($this->session->userdata('company_id'));
		echo json_encode($return);die;
	}
	
	/**
         * Accrording to division option this method will set department name on list.
         * @returns View
         */
	
	/* 
	 * Function : setDepartment
	 * Author : Spaculus
	 * Desc : This function is used to get department list from division
	 */
	function setDepartment(){
		
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$division_id = $this->input->post('division_id');
		
		
		if($division_id){
			$data['departments'] = get_company_department($this->session->userdata('company_id'),'',$division_id);
		} else {
			$data['departments'] = '';
		}
		
		
		$this->load->view($theme.'/layout/settings/ajax_add_department', $data);
	}
        /**
         * This function is used for add new department in DB .
         * @returns void
         */
	function addDepartment(){
		$division_id  = $this->input->post('pop_parent_division');
		$dept_count = get_company_department_count($division_id);
		$data = array(
			'department_title' => htmlspecialchars($this->input->post('dept_name')),
			'deivision_id' => $division_id,
			'company_id' => $this->session->userdata('company_id'),
			'status' => $this->input->post('dept_status'),
			'dept_added_date' => date('Y-m-d H:i:s'),
                        'department_seq' => $dept_count +1
		);
		$this->db->insert('company_departments',$data);
		$dept_id = $this->db->insert_id();
		
		$pass_data['department_id'] = $dept_id;
		$pass_data['department_title'] = htmlspecialchars($this->input->post('dept_name'));
		$pass_data['status'] = $this->input->post('dept_status');
		$pass_data['deivision_id'] = $division_id;
                $pass_data['seq'] = $dept_count +1;
		echo json_encode($pass_data);die;
	}
        /**
         * When admin add new department name this function will check department name in db.
         * @returns int
         */

	/* 
	 * Function : chk_department_exists
	 * Author : Spaculus
	 * Desc : This function is used to check departments name exist or not
	 */
	function chk_department_exists(){
		$name = $this->input->post('name');
		$company_id = $this->input->post('company_id');
		$dept_id = isset($_POST['dept_id'])?$this->input->post('dept_id'):'';
		$devision_id = $this->input->post('devision_id');
		if($dept_id)
		{
			$query = $this->db->query("select department_title from ".$this->db->dbprefix('company_departments')." where department_title= '$name' and deivision_id = '".$devision_id."' and company_id = '".$company_id."' and department_id != '".$dept_id."' and is_deleted =0");
			
		} else {
			$query = $this->db->query("select department_title from ".$this->db->dbprefix('company_departments')." where department_title= '$name' and deivision_id = '".$devision_id."' and company_id = '".$company_id."' and is_deleted =0");
		}
		
		if($query->num_rows()>0){
			echo "1";
		}else{
			echo "0";
		}
		
	}
	/**
         * This function will update department name in db.
         * @returns void
         */
	function update_department_name(){
		$data = array(
			'department_title' => $this->input->post('value')
		);
		$this->db->where('department_id',  $this->input->post('name'));
		$this->db->update('company_departments',$data);
		
	}
        /**
         * This function is used for update department status in db.
         * @returns void
         */
	function update_department_status(){
		$department_id = $this->input->post('id');
		$value = $this->input->post('val');
		$data = array("status"=>$value);
		$this->db->where('department_id',$department_id);
		$this->db->update('company_departments',$data);
	}
	/**
         * This function is called when user click on delete icon for delete department name in db.
         * @returns void
         */
	function delete_department(){ 
		$data = array("is_deleted"=>"1");
		$this->db->where("department_id",$this->input->post('id'));
		$this->db->update('company_departments',$data);
		
	}
	
	/**
         * This function will save company default calendar setting in db.
         * @returns int
         */
	function save_calendar_settings(){
		$name = $this->input->post('name');
		$value = $this->input->post('val');
		
		$data = array(
			$name => $value
		);
		
		$this->db->where('comapny_id',$this->session->userdata('company_id'));
		$this->db->update('default_calendar_setting',$data);
		return 1;
	}
        /**
         * This function is used for insert new skills in db..
         * @returns json
         */
	function addSkills(){
		$skill_count = get_company_skills_count();
		$data = array(
			'skill_title' => htmlspecialchars($this->input->post('skill_name')),
			'skill_status' => $this->input->post('skill_status'),
			'company_id' => $this->session->userdata('company_id'),
			'skill_added_date' => date('Y-m-d H:i:s'),
			'skill_added_IP' => $_SERVER['REMOTE_ADDR'],
                        'skill_seq'=> $skill_count +1
		);
		$this->db->insert('skills',$data);
		$skill_id = $this->db->insert_id();
		
		$return_data['skill_title'] = $this->input->post('skill_name');
		$return_data['skill_status'] = $this->input->post('skill_status');
		$return_data['skill_id'] = $skill_id;
                $return_data['seq'] = $skill_count + 1;
		echo json_encode($return_data);die;
	}
	/**
         * This function will update skills status in db on Ajax request.
         * @returns void
         */
	
	/* 
	 * Function : updateSkillStatus
	 * Author : Spaculus
	 * Desc : This function is used to update skills status
	 */
	function updateSkillStatus(){
		
		$data = array(
			'skill_status' => $this->input->post('val'),
		);
		$this->db->where('skill_id',  $this->input->post('id'));
		$this->db->update('skills',$data);
	}
	/**
         * This function will update skill name in db.
         * @returns void
         */
	function update_skill_name(){
		$id = str_replace("skillName_","",  $this->input->post('name'));
                $skill_title=  htmlspecialchars($this->input->post('value'));
		$data = array(
			'skill_title' => $skill_title
		);
		$this->db->where('skill_id',$id);
		$this->db->update('skills',$data);
	}
	/**
         * When admin click on delete icon this function will delete skill in db.
         * @returns void
         */
	function delete_skill(){
		$data = array("is_deleted"=>"1");
		$this->db->where("skill_id",$this->input->post('id'));
		$this->db->update('skills',$data);
	}
	
	/**
         * This function add new staff list in DB .
         * @retuens Json
         */
	/* 
	 * Function : addStaffLevels
	 * Author : Spaculus
	 * Desc : This function is used to add stafflevels
	 */
	function addStaffLevels(){
		
		$staff_count = get_company_staff_levels_count();
		$data = array(
			'staff_level_title' => htmlspecialchars($this->input->post('staff_name')),
			'staff_level_status' => $this->input->post('staff_status'),
			'company_id' => $this->session->userdata('company_id'),
			'staff_level_added' => date('Y-m-d H:i:s'),
			'staff_level_IP' => $_SERVER['REMOTE_ADDR'],
                        'staff_levels_seq' => $staff_count +1
		);
		$this->db->insert('staff_levels',$data);
		$staff_level_id = $this->db->insert_id();
		
		$return_data['staff_level_id'] = $staff_level_id; 
		$return_data['staff_level_title'] = $this->input->post('staff_name');
		$return_data['staff_level_status'] = $this->input->post('staff_status');
                $return_data['seq'] = $staff_count +1;
		echo json_encode($return_data);die;
	}
	/**
         * It will update stafflevel name in db.
         * @returns void
         */
	function update_stafflevel_name(){
		$id = str_replace("staffLevelName_","",  $this->input->post('name'));
                $staff_level_title=  htmlspecialchars($this->input->post('value'));
		$data = array(
			'staff_level_title' => $staff_level_title
		);
		$this->db->where('staff_level_id',$id);
		$this->db->update('staff_levels',$data);
	}
	/**
         *  This function is used to update stafflevels status in db.
         * @returns void
         */
	
	/* 
	 * Function : updateStaffLevelsStatus
	 * Author : Spaculus
	 * Desc : This function is used to update stafflevels status
	 */
	function updateStaffLevelsStatus(){
		
		$data = array(
			'staff_level_status' => $this->input->post('val'),
		);
		$this->db->where('staff_level_id',  $this->input->post('id'));
		$this->db->update('staff_levels',$data);
		
	}
        /**
         * This function will delete staff levels in db.
         */
	function delete_staffLevel(){ 
		$data = array("is_deleted"=>"1");
		$this->db->where("staff_level_id",  $this->input->post('id'));
		$this->db->update('staff_levels',$data);
	}
	
	
	function get_category_last_seq($parent_id){
		$this->db->select('MAX(category_seq) as seq');
		$this->db->from('task_category');
		$this->db->where('company_id',$this->session->userdata('company_id'));
		$this->db->where('parent_id',$parent_id);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->seq;
		} else {
			return 0;
		}
	}
	
	/* 
	 * Function : addTaskCategory
	 * Author : Spaculus
	 * Desc : This function is used to add task categories
	 */
	function addTaskCategory(){
		
		$parent_id = isset($_POST['parent_id'])?$_POST['parent_id']:'0';
		$cate_name = htmlspecialchars($this->input->post('taskCategory_name'));
                $seq_no = $this->get_category_last_seq($parent_id);
		$data = array(
			'category_name' => htmlspecialchars($this->input->post('taskCategory_name')),
			'category_status' => $this->input->post('taskCategory_status'),
			'company_id' => $this->session->userdata('company_id'),
			'parent_id' => $parent_id,
			'category_seq' => $seq_no + 1,
			'category_added_date' => date('Y-m-d H:i:s'),
			'category_added_IP' => $_SERVER['REMOTE_ADDR'],
                        'is_chargeable'=>'1'
		);
		$this->db->insert('task_category',$data);
		$category_id = $this->db->insert_id();
		
                if($parent_id!='0'){
                    $this->db->select('*');
                    $this->db->from('customer_category');
                    $this->db->where('category_id',$parent_id);
                    $this->db->where('company_id',  $this->session->userdata('company_id'));
                    $this->db->where('is_deleted','0');
                    $query = $this->db->get();
                    if($query->num_rows()>0){
                        $cate_list = $query->result_array();   
                    }else{
                        $cate_list = 0;
                    } 
                    if($cate_list != '0' && !empty($cate_list)){
                        foreach($cate_list as $list){
                            $data=array(
                                        'category_name'=>$cate_name,
                                        'customer_id'=>$list['customer_id'],
                                        'category_id'=>$category_id,
                                        'company_id'=>  $this->session->userdata('company_id'),
                                        'parent_category_id'=>$parent_id,
                                        'rate'=>'0',
                                        'is_deleted'=>'0',
                                        'created_date'=>date('Y-m-d H:i:s')
                                    );
                            $this->db->insert('customer_category',$data);
                        }
                    }
                   // die();
                }
                
		$return_data['category_name'] = $this->input->post('taskCategory_name');
		$return_data['category_status'] = $this->input->post('taskCategory_status');
		$return_data['category_id'] = $category_id;
		$return_data['parent_id'] = $parent_id;
                $return_data['seq'] = $seq_no +1;
		$return_data['ParentTaskCategory'] = get_company_category($this->session->userdata('company_id'));
                echo json_encode($return_data);die;
	}
        /**
         * It will update category name in db.
         * @returns Json
         */
        function update_catgory_name(){
		$id = str_replace("mainCategoryTitle_","",  $this->input->post('name'));
		$data = array(
			'category_name' => $this->input->post('value')
		);
		$this->db->where('category_id',$id);
		$this->db->update('task_category',$data);
		$return_data['ParentTaskCategory'] = get_company_category($this->session->userdata('company_id'));
		echo json_encode($return_data);die;
	}
	
	/**
         * This function will create parent category list on Ajax request
         * @returns view
         */
	/* 
	 * Function : maincategoryList
	 * Author : Spaculus
	 * Desc : This function is used to give main category list
	 */
	function maincategoryList(){
//		if (!check_user_authentication()) {
//			redirect ('home');
//		}
//		
//		$theme = getThemeName ();
//		$this->template->set_master_template ($theme.'/template2.php');
//		$data['ParentTaskCategory'] = get_company_category($this->session->userdata('company_id'));
//		$this->load->view($theme.'/layout/settings/ajax_main_taskCategory', $data);
	}
	
	/* 
	 * Function : updateTaskCategoryStatus
	 * Author : Spaculus
	 * Desc : This function is used to update task category status
	 */
        /**
         * This function will update category status in db.
         * @returns void
         */
	function updateTaskCategoryStatus(){
		
		$data = array(
			'category_status' => $this->input->post('val'),
		);
		$this->db->where('category_id',  $this->input->post('id'));
		$this->db->update('task_category',$data);
	}
	
        /**
         * This function is used to delete category name in db.
         * @returns json
         */
	function delete_category(){
		$data = array("is_deleted"=>"1");
		$this->db->where("category_id",  $this->input->post('id'));
		$this->db->update('task_category',$data);
		$return_data['ParentTaskCategory'] = get_company_category($this->session->userdata('company_id'));
		echo json_encode($return_data);die;
	}
	/**
         * According to parent category,this function will set subcategory name on drop down list.
         * @returns categoryview
         */
	
	/* 
	 * Function : setSubCategory
	 * Author : Spaculus
	 * Desc : This function is used to get list of sub category
	 */
	function setSubCategory(){
		
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$parent_id = $_POST['parent_id'];
		
		$data['subTaskCategory'] = get_company_category($this->session->userdata('company_id'),'',$parent_id);
		
		$this->load->view($theme.'/layout/settings/ajax_add_subCategory', $data);
	}
        /**
         * This function will call for update sub category name in db.
         * @returns void
         */
        function update_subCatgory_name(){
		$id = str_replace("subTaskCategoryName_","",  $this->input->post('name'));
		$data = array(
			'category_name' => $this->input->post('value')
		);
		$this->db->where('category_id',$id);
		$this->db->update('task_category',$data);
		
	}
	/**
         * This function is used to find task status id from status name in db.
         * @param  $task_seq
         * @return int
         */
	/* 
	 * Function : get_task_id
	 * Author : Spaculus
	 * Desc : This function is used to find task status id from status name
	 */
	function get_task_id($task_seq){
		$this->db->select('task_status_id');
		$this->db->from('task_status');
		$this->db->where("task_sequence",$task_seq);
		$this->db->where('company_id',$this->session->userdata('company_id'));
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_id;
		} else {
			return 0;
		}
	} 
	/**
         * On setting page of admin,when user click on up-down icon on task status option at the same time this function is used for update task status in db.
         * @returns void
         */
	
	/* 
	 * Function : updateTaskSequence
	 * Author : Spaculus
	 * Desc : This function is used set task status sequence
	 */
	function updateTaskSequence(){
		$ids = $_POST['new_position'];
		foreach($ids as $key=>$value){
                    $status_id = explode('_', $key);
                    $value = strip_tags($value);
                    $data = array(
                        'task_sequence' => $value
                    );
                    $this->db->where('task_status_id',$status_id[1]);
                    $this->db->update('task_status',$data);
		}
	}
        /* 
	 * This function will fetch task status sequence in db.
         * @returns Array
	 */
	function get_task_last_seq(){
		$this->db->select('MAX(task_sequence) as seq');
		$this->db->from('task_status');
		$this->db->where('company_id',$this->session->userdata('company_id'));
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->seq;
		} else {
			return 0;
		}
	}
      /**
       * This function will get last task sequence in db and create new view on admin setting.
       * @returns taskview
       */
	
	/* 
	 * Function : addTaskStatus
	 * Author : Spaculus
	 * Desc : This function is used add task status
	 */
	function addTaskStatus(){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
                $task_status = $this->input->post('info');
		$unserializedData = array();
                parse_str($task_status,$unserializedData);
                
		$last_seq = $this->get_task_last_seq();
                $insert_data = array(
				'task_status_name' => $unserializedData['task_status_name'],
				'company_id' => $this->session->userdata('company_id'),
				'task_status_flag' => 'Active',
				'task_sequence' => $last_seq + 1,
				'task_status_added_date' => date('Y-m-d H:i:s'),
				'task_status_added_IP' => $_SERVER['REMOTE_ADDR']
			);
			$this->db->insert('task_status',$insert_data);
                        $status_id = $this->db->insert_id();
                        $total_status = get_total_taskStatus($this->session->userdata('company_id'));
		$data['status_name']  = $unserializedData['task_status_name'];
                $data['status_id'] = $status_id;
                $data['seq'] = $last_seq + 1;
                $data['total_status'] = $total_status;
		echo json_encode($data); die();
	}
	
	/* 
	 * Function : deleteStatus
	 * Author : Spaculus
	 * Desc : This function is used delete task status
	 */
        /**
         * When admin click on delete task status from list this function will call for delete status in db.
         * @returns string
         */
	function deleteStatus(){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		
		$deleted_id = $this->input->post('delete_ids');
		$status = task_status_ids($deleted_id);
		
			if($status == 0){
				$this->db->delete('task_status',array('task_status_id'=>$deleted_id, 'company_id'=>$this->session->userdata('company_id')));
			} else {
				echo "not_done";die;
			}
		$total_status = get_total_taskStatus($this->session->userdata('company_id'));
		echo $total_status;die;
	}
	
	/* 
	 * Function : statusdiv
	 * Author : Spaculus
	 * Desc : This function is used to get ajax view of task status 
	 */
        /**
         * After deleting task status form list this function will render task status page.
         * @returns statusview
         */
	function statusdiv(){
		if (!check_user_authentication()) {
			redirect ('home');
		}
		
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$data['taskStatus'] = get_taskStatus($this->session->userdata('company_id'),'Active');
		
		$this->load->view($theme.'/layout/settings/ajax_add_taskStatus', $data);
	}
	
	/**
         * When admin add new skills at the same time this function is used to check skill name in db.
         * @returns int
         */
	function chk_skillName_exists(){
		$name = $this->input->post('name');
		$company_id = $this->input->post('company_id');
		$skill_id = isset($_POST['skill_id'])?$this->input->post('skill_id'):'';
		
		if($skill_id)
		{
			$query = $this->db->query("select skill_title from ".$this->db->dbprefix('skills')." where skill_title= '$name' and company_id = '".$company_id."' and skill_id != '".$skill_id."' and is_deleted =0");
			
		} else {
			$query = $this->db->query("select skill_title from ".$this->db->dbprefix('skills')." where skill_title= '$name' and company_id = '".$company_id."' and is_deleted =0");
		}
		
		if($query->num_rows()>0){
			echo "1";
		}else{
			echo "0";
		}
		die;
	}
	/**
         * When admin add new stafflevel at the same time this function is used to check stafflevel name in db.
         * @returns int
         */
	function chk_staffLevels_exists(){
		$name = $this->input->post('name');
		$company_id = $this->input->post('company_id');
		$staff_level_id = isset($_POST['staff_level_id'])?$this->input->post('staff_level_id'):'';
		
		if($staff_level_id)
		{
			$query = $this->db->query("select staff_level_title from ".$this->db->dbprefix('staff_levels')." where staff_level_title= '$name' and company_id = '".$company_id."' and staff_level_id != '".$staff_level_id."' and is_deleted =0");
			
		} else {
			$query = $this->db->query("select staff_level_title from ".$this->db->dbprefix('staff_levels')." where staff_level_title= '$name' and company_id = '".$company_id."' and is_deleted =0");
		}
		
		if($query->num_rows()>0){
			echo "1";
		}else{
			echo "0";
		}
		die;
	}
	
	/**
         *When admin add new task status at the same time this function is used to check task status name in db.
         * @returns int
         */
	function chk_taskStatus_exists(){
		$name = $this->input->post('name');
		$company_id = $this->input->post('company_id');
		
		$query = $this->db->query("select task_status_name from ".$this->db->dbprefix('task_status')." where task_status_name= '$name' and company_id = '".$company_id."' ");
		if($query->num_rows()>0){
			echo json_encode(FALSE);
		}else{
			echo json_encode(TRUE);
		}
		
	}
	/**
         *When admin add new task category at the same time this function is used to check task category name in db.
         * @returns int
         */
	function chk_taskCategory_exists(){
		$name = $this->input->post('name');
		$company_id = $this->input->post('company_id');
		$category_id = isset($_POST['category_id'])?$this->input->post('category_id'):'';
		$type = $this->input->post('type');
		if($type == 'sub'){
			$sub_category_id = isset($_POST['sub_category_id'])?$this->input->post('sub_category_id'):'';
			if($sub_category_id)
			{
				$query = $this->db->query("select category_name from ".$this->db->dbprefix('task_category')." where category_name= '$name' and parent_id = '".$category_id."' and company_id = '".$company_id."' and parent_id = '".$category_id."' and category_id != '".$sub_category_id."' and is_deleted =0");
				
			} else {
				$query = $this->db->query("select category_name from ".$this->db->dbprefix('task_category')." where category_name= '$name' and parent_id = '".$category_id."' and company_id = '".$company_id."' and parent_id = '".$category_id."' and is_deleted =0");
			}
		} else {
			if($category_id)
			{
				$query = $this->db->query("select category_name from ".$this->db->dbprefix('task_category')." where category_name= '$name' and company_id = '".$company_id."' and category_id != '".$category_id."' and is_deleted =0");
				
			} else {
				$query = $this->db->query("select category_name from ".$this->db->dbprefix('task_category')." where category_name= '$name' and company_id = '".$company_id."' and is_deleted =0");
			}
		}
		if($query->num_rows()>0){
			echo "1";
		}else{
			echo "0";
		}
		die;
	}
            /**
             * This function will set departments name in drop down and create view of department.
             * @returns departmentview
             */
	function departments(){
		$theme = getThemeName ();
		$this->template->set_master_template ($theme.'/template2.php');
		
		$tags_division = $this->input->post('division_id');
		$company_department = addQuotes(get_company_department_list($this->session->userdata('company_id'),$tags_division));
		$data['tags_department'] = '';
		$data['company_department'] = $company_department;
		echo $this->load->view($theme.'/layout/user/ajaxDepartment',$data,TRUE); die;
	}
	
	function setMainCategorySeq(){
		
                $new_position = $this->input->post('new_position');
                foreach($new_position as $key=>$value){
                    $id = explode('_', $key);
                    $value = strip_tags($value);
                    	$data = array(
                            'category_seq'=>$value
			);
                    $this->db->where('category_id',$id[1]);
                    $this->db->update('task_category',$data); 
                }
	}
        
        function updateCustomerAccess(){
                if($_POST){
                    $user_id=  $this->input->post('user_id');
                    $access_mode=  $this->input->post('access');
                    $data=array(
                                'customer_module_access'=>$access_mode
                    );
                    $this->db->where('user_id',$user_id);
                    $this->db->update('users',$data);
                    if($user_id == get_authenticateUserID()){
                        $this->session->unset_userdata('customer_access');
                        $this->session->set_userdata('customer_access',$access_mode);
                    }
                    echo "1"; die();
                }
        }
        
        function changeCustomerModuleStatus(){
            if($_POST){
                $status=  $this->input->post('status');
                $data=array(
                            'customer_module_activation'=>$status
                );
                $this->db->where('company_id',  $this->session->userdata('company_id'));
                $this->db->update('company',$data);
                $this->session->unset_userdata('customer_module_activation');
                $this->session->set_userdata('customer_module_activation',$status);
                echo $this->session->userdata('customer_access'); die();
            }
        }
        /**
         * This methods is called for update customer module in billing portal & db.
         */
        function updateCustomerModule(){
                $status=  $this->input->post('status');
                $this->config->load('chargify');
		/* access username & password of chargify*/
		$username = $this->config->item('API_key');
		$password = $this->config->item('API_key_pass');
                $component_id= $this->config->item('addon_subscription_id');
                $query1=$this->db->get_where('users',array('company_id'=>$this->session->userdata('company_id'),'is_owner'=>'1'));
		$company_user=$query1->row();
                $headers = array(
			    	'Accept:application/json',
				);
                $data=array(
                            "allocation"=>array(
                                               "quantity"=>$status
                                             )
                        );
                
				
		$url = 'https://schedullo.chargify.com/subscriptions/'.$company_user->chargify_subscriptions_ID.'/components/'.$component_id.'/allocations.json';
                //$url = 'https://5-exception-sandbox.chargify.com/subscriptions/15579091/components/373311/allocations.json';
				
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                curl_setopt($ch, CURLOPT_URL,$url);
		$result = curl_exec($ch);
                curl_close($ch);
		echo trim($result);die;
        }
	
        
        function changePricingModuleStatus(){
            if($_POST){
                $status=  $this->input->post('status');
                $data=array(
                            'pricing_module_status'=>$status
                );
                $this->db->where('company_id',  $this->session->userdata('company_id'));
                $this->db->update('company',$data);
                $this->session->unset_userdata('pricing_module_status');
                $this->session->set_userdata('pricing_module_status',$status);
//                echo $this->session->userdata('customer_access'); die();
            }
        }
        
        
        function change_currency(){
            if($_POST){
                $currency_code = $this->input->post('currency');
                $data = array(
                        "currency"=>$currency_code
                );
                $this->db->where('company_id',  $this->session->userdata('company_id'));
                $this->db->update('company',$data);
                $currency_symbol = get_currency_symbol($currency_code);
                $this->session->unset_userdata('currency');
                $this->session->set_userdata('currency',$currency_symbol->currency_symbol);
                $this->session->unset_userdata('currency_code');
                $this->session->set_userdata('currency_code',$currency_symbol->currency_code);
            }
        }
        
        
        function changeCategoryChargeStatus(){ 
            if($_POST){
                $is_charge = $this->input->post('status');
                $category_id = $this->input->post('category_id');
                $data = array(
                    "is_chargeable"=>$is_charge
                );
                $this->db->where('company_id',$this->session->userdata('company_id'));
                $this->db->where('category_id',$category_id);
                $this->db->update('task_category',$data);
                echo $is_charge; die();
            }
        }
        function setTimeZoneFirst(){
                $form_data = $this->input->post('data');
                $unserializedData = array();
                parse_str($form_data,$unserializedData);
               
		$country_id = get_country_id_by_code($unserializedData['country_code']);
		$data = array(
			'user_time_zone' => $unserializedData['user_time_zone'],
                        'is_first_login' => '1',
                        'country_id'=>$country_id
		);
		$this->db->where('user_id',  get_authenticateUserID());
                $this->db->update('users',$data);
                $is_owner = is_owner(get_authenticateUserID());
                if($is_owner == '1')
                {
                    $data = array(
                        'company_timezone' => $unserializedData['user_time_zone'],
                        'country_id'=>$country_id
                    );
                    $this->db->where(array('company_id' => $this->session->userdata('company_id'), 'status' => 'Active'));
                    $this->db->update('company',$data);
                    $user_info = get_user_info(get_authenticateUserID());
                    $user_name = $user_info->first_name.' '.$user_info->last_name;
                    $email_to = $user_info->email;

                    $email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='New subscription has been verified'");
                    $email_temp=$email_template->row();	
                    $email_address_from=$email_temp->from_address;
                    $email_address_reply=$email_temp->reply_address;
                    $sandgrid_id=$email_temp->sandgrid_id;
                    $email_subject=$email_temp->subject;				
                    $email_message=$email_temp->message;
                    $subscription_link = site_url();

                    $email_message=str_replace('{break}','<br/>',$email_message);
                    $email_message=str_replace('{user_name}',$user_name,$email_message);
                    $email_message=str_replace('{email}',$email_to,$email_message);


                    $str=$email_message;
                    $data = array('subject'=>'New subscription has been verified');
                    if($sandgrid_id){
                        mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,$user_name,$email_subject,$sandgrid_id,$data);
                    }else{
                        email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                    }
                }
                
                $this->session->unset_userdata('first_login');
                $this->session->set_userdata('first_login','1');
                
                echo "done"; die();
	}
        
        /**
         * change timesheet module status
         */
        function change_timesheet_status(){
            if($_POST){
                $status= $this->input->post('status');
                $data=array(
                            'timesheet_module_status'=>$status
                );
                $this->db->where('company_id',  $this->session->userdata('company_id'));
                $this->db->update('company',$data);
                $this->session->unset_userdata('timesheet_module_status');
                $this->session->set_userdata('timesheet_module_status',$status);
                echo 'done'; die();
            }
        }
        function save_application(){
            $status = isset($_POST['status'])?$this->input->post('status'):'';
            if($status == '1'){
                $client_id = hash_hmac("sha256",randomCode(),PRIVATEKEY);
                $client_secret = hash_hmac("sha256",randomCode(),PRIVATEKEY);
                
                $data = array(
                    "client_id"=> $client_id,
                    "client_secret"=> $client_secret,
                    "app_name"=>'office 365',
                    "created_date"=>date("Y-m-d H:i:s"),
                    "api_company_id"=> $this->session->userdata('company_id'),
                    "auth_type"=>'client_credentials'
                );
                
                $this->db->insert('app_registration',$data);
                
                
                $data1 = array(
                    "client_id"=>$client_id,
                    "client_secret"=>$client_secret,
                    "grant_types"=>"client_credentials",
                    "user_id"=> $this->session->userdata('company_id')
                );
                
                $this->db->insert('oauth_clients',$data1);
                $data1['client_id']= $client_id;
                $data1['client_secret'] = $client_secret;
                echo json_encode($data1); die();
            }else{
                $client_id = $_POST['client_id'];
                $this->db->where('api_company_id',  $this->session->userdata('company_id'));
                $this->db->where('client_id',$client_id);
                $this->db->delete('app_registration');
                
                $this->db->where('user_id',  $this->session->userdata('company_id'));
                $this->db->where('client_id',$client_id);
                $this->db->delete('oauth_clients');
                $data1['client_id'] = '0';
                echo json_encode($data1); die();
            }
        }
        
        function update_xero_integration(){
            if($_POST){
                $status=  $this->input->post('status');
                $wipe = isset($_POST['wipe'])?$_POST['wipe']:'';
                $data=array(
                            'xero_integration_status'=>$status
                );
                $this->db->where('company_id',  $this->session->userdata('company_id'));
                $this->db->update('company',$data);
                $this->session->unset_userdata('xero_integration_status');
                $this->session->set_userdata('xero_integration_status',$status);
                if($wipe == 0){
                    $this->db->set('xero_account_code',0);
                    $this->db->set('xero_tax_type','');
                    $this->db->where('company_id',  $this->session->userdata('company_id'));
                    $this->db->update('company');
                }
            }
        }
        
        function updateUserXeroAccess(){
            if($_POST){
                    $user_id = $this->input->post('user_id');
                    $xero_status = $this->input->post('access');
                    
                    $this->db->set('xero_access',$xero_status);
                    $this->db->where('user_id',$user_id);
                    $this->db->update('users');
                    if($user_id == get_authenticateUserID()){
                        $this->session->unset_userdata('xero_user_access');
                        $this->session->set_userdata('xero_user_access',$xero_status);
                    }
                    echo "1"; die();
                }
        }
        
        function update_xero_info(){
            if($_POST){
                $account_code = $this->input->post('account_code');
                $tax_type = $this->input->post('tax_type');
                $xero_access_token = $this->input->post('xero_access_token');
                $oauth_token_secret = $this->input->post('oauth_token_secret');
                $access_token = $xero_access_token."&".$oauth_token_secret;
                $this->db->set("xero_access_token",$access_token);
                $this->db->set('xero_account_code',$account_code);
                $this->db->set('xero_tax_type',$tax_type);
                $this->db->where('company_id',  $this->session->userdata('company_id'));
                $this->db->update('company');
                echo "done"; die();
                
            }
        }
        
        function save_company_info(){
            if($_POST){
                $companyInfo = $this->input->post('info');
                $unserializedData = array();
                parse_str($companyInfo,$unserializedData);
                $company_info = array(
                    "company_name"=>$unserializedData['company_name'],
                    "company_phoneno"=>$unserializedData['company_phone'],
                    "company_email"=>$unserializedData['company_email'],
                    "country_id"=>$unserializedData['company_country'],
                    "company_address"=>$unserializedData['company_address'],
                    "company_date_format"=>$unserializedData['company_date_format'],
                    "company_timezone"=>$unserializedData['company_timezone']
                );
                
                $this->db->where('company_id',  $unserializedData['company_id']);
                $this->db->where('status','Active');
                $this->db->update('company',$company_info);
                echo $unserializedData['company_name']; die();
            }
        }
        
        function set_division_seq(){
                $new_position = $this->input->post('new_position');
                foreach($new_position as $key=>$value){
                    $id = explode('_', $key);
                    $value = strip_tags($value);
                    	$data = array(
                            'seq'=>$value
			);
                    $this->db->where('division_id',$id[1]);
                    $this->db->update('company_divisions',$data); 
                }
        }
        
        function set_company_department_seq(){
                $new_position = $this->input->post('new_position');
                foreach($new_position as $key=>$value){
                    $id = explode('_', $key);
                    $value = strip_tags($value);
                    	$data = array(
                            'department_seq'=>$value
			);
                    $this->db->where('department_id',$id[1]);
                    $this->db->update('company_departments',$data); 
                }
        }
        
        function set_company_staff_levels_seq(){
                $new_position = $this->input->post('new_position');
                foreach($new_position as $key=>$value){
                    $id = explode('_', $key);
                    $value = strip_tags($value);
                    	$data = array(
                            'staff_levels_seq'=>$value
			);
                    $this->db->where('staff_level_id',$id[1]);
                    $this->db->update('staff_levels',$data); 
                }
        }
        function set_company_skills_seq(){
                $new_position = $this->input->post('new_position');
                foreach($new_position as $key=>$value){
                    $id = explode('_', $key);
                    $value = strip_tags($value);
                    	$data = array(
                            'skill_seq'=>$value
			);
                    $this->db->where('skill_id',$id[1]);
                    $this->db->update('skills',$data); 
                }
        }
        function close_account(){
            
            $reason = $this->input->post('close_reason');
            if($reason == 'Other' && $this->input->post('close_reason_other') != '')
            {
                $reason = $reason .': '.$this->input->post('close_reason_other');
            }
            
            $company_id = $this->session->userdata('company_id');
            $update = array('status' => 'Inactive', 'is_deleted' => 1);
            $where = array('company_id'=>$company_id);
            $this->db->where($where);
            $this->db->update('company',$update);
            $update1 = array('is_deleted' => 1,'user_status' => 'Inactive','delete_account_reason' => $reason);
            $this->db->where($where);
            $this->db->update('users',$update1);
            chargifyCancelSubscrption();
            return 1;
        }
        
        /**
         * This function is used for checking existance user email in db.
         * @returns int
         */
	function is_company_user_exists(){
		$value = isset($_POST["value"])?$_POST['value']:'';
                $user_id = isset($_POST['user_id'])?$_POST['user_id']:'';
                if($user_id ==''){
                    $query = $this->db->query("select email from ".$this->db->dbprefix('users')." where company_id= '".$this->session->userdata('company_id')."' and email = '".$value."' and is_deleted = 0");
                }else{
                    $query = $this->db->query("select email from ".$this->db->dbprefix('users')." where company_id= '".$this->session->userdata('company_id')."' and email = '".$value."' and is_deleted = 0 and user_id !=".$user_id);
                }
                if($query->num_rows()>0){
			echo "1";
		} else {
			echo "0";
		}
		die;
	}
        /**
         * Add/Edit customer user info
         */
        function add_customer_user_info(){
            if($_POST){
                $default_format = $this->config->item('company_default_format');
                $s3_display_url = $this->config->item('s3_display_url');
                $this->load->model('user_model');
                $customeruserInfo = $_POST['info'];
                $unserializedData = array();
                $data = array();
                parse_str($customeruserInfo,$unserializedData);
                if($unserializedData['customer_user_id'] != ''){
                    $user_id = $this->user_model->update_customer_user($unserializedData);
                }else{
                    $user_id = $this->user_model->insert_customer_user($unserializedData);
                }
                $data['customer_info'] = get_one_customer_user($user_id);
                $data['date'] = date($default_format,strtotime(get_user_last_login_date($user_id)));
                $data['image_url'] = $s3_display_url;
                echo json_encode($data,true); die();
            }
        }
        
        /**
         * delete customer user, if no task aasign to this customer users.
         */
        function delete_customerUser(){
            $customer_user_id = $this->input->post('customer_user_id');
            $task_status = chk_customerUser_task($customer_user_id);
            
            if($task_status == '0'){
                $this->db->set('is_deleted','1');
                $this->db->where('user_id',$customer_user_id);
                $this->db->update('users');
                
                $query = $this->db->get_where('users', array('user_id' => $customer_user_id));
                $use = $query->row();

                $query1 = $this->db->get_where('users', array('company_id' => $use->company_id, 'is_owner' => '1'));

                $company = $query1->row();

                $query_plan = $this->db->select("p.chargify_external_user_component_id")->from("plans p")->join("company c", "c.plan_id = p.plan_id")->where("c.company_id", $use->company_id)->where("c.is_deleted", "0")->get();
                $company_plan = $query_plan->row();
                /**
                 * get user's plan
                 */
                if ($company_plan) {
                    $component_id = $company_plan->chargify_external_user_component_id;
                } else {
                    $component_id = 0;
                }

                $test = TRUE;
                $Qty = new ChargifyQuantityBasedComponent(NULL, $test);

                if ($company->chargify_subscriptions_ID) {
                    try {
                        $new_qty = count_customer_user_by_company($use->company_id);
                        $Qty->allocated_quantity = $new_qty;
                        $Qt = $Qty->update($company->chargify_subscriptions_ID, $component_id);
                    } catch (ChargifyValidationException $cve) {
                        log_message('error', $cve->getMessage());
                    }
                }

                echo "1";
            }else{
                echo "2";
            }
             die();
        }
        
        /**
         * Get requested customer user info
         */
        function get_one_customer_user_info(){
            $customer_user_id = $this->input->post('customerUser_id');
            
            $data['user_info'] = get_user_info($customer_user_id);
           // $data['customers']=  getCustomerList();
            echo json_encode($data); die();
        }
}
    
?>
