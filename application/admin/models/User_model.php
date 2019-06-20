<?php
/**
 * This class declares user class related functions for database interaction, this class is used to access data from database.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class User_model extends CI_Model {
	/**
         * This is default constructor of this class.
         * @returns void
         */
    function User_model()
    {
         /**
             * call base class methods
             */
        parent::__construct();	
    }   
	
	/**
         * This function check user email in Db and returns boolean value.
         * @param string $str
         * @returns boolean
         */
	function user_email_unique($str)
	{
		if($this->input->post('user_id'))
		{
			$query = $this->db->get_where('users',array('user_id'=>$this->input->post('user_id')));
			$res = $query->row_array();
			$email = $res['email'];
			
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email = '$str' and user_id!='".$this->input->post('user_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select email from ".$this->db->dbprefix('users')." where email= '$str'  and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
         * This database function is created for insert data in DB.
         * @returns void
         */
	function user_insert()
	{
		$code = randomCode();
		/*create random code and check is_manager value for set */
		if($this->input->post('is_manager')){$is_manager_val = "1"; } else { $is_manager_val = "0"; }
		//$company_timezone = getCompanyTimeZone($company_id);
		
		$data["first_name"] = $this->input->post('first_name');
		$data["last_name"] = $this->input->post('last_name');
		$data["email"] = $this->input->post('email');
		//$data['contact_no'] =$this->input->post('contact_no');
		if($this->input->post('passwordReset')=="0"){
			$data["password"] = md5($this->input->post('password'));
		}else{
			
			$data["password"] = $this->input->post('hiddenPassword');
		}
		if($this->input->post('staff_level')){
                    $staff_level = $this->input->post('staff_level');
                }else{
                    $staff_level = 0;
                }
		$data['staff_level'] = $staff_level;
		$data['user_time_zone'] = $this->input->post('user_time_zone');
		
		
		$data['is_manager'] = $is_manager_val;
		$data['is_administrator'] = $this->input->post('is_administrator');
		$data['company_id'] = $this->input->post('company_id');
		$data['country_id'] = $this->input->post('country_id');	
		
		$data['user_status'] = $this->input->post('user_status');
		$data['email_verification_code'] = $code;
		$data['signup_date'] =date('Y-m-d h:i:s');
		$data['signup_IP'] = $_SERVER['REMOTE_ADDR'];
	
		
		$query = $this->db->query("Delete From ".$this->db->dbprefix('users')." where email= '".$data["email"]."' and is_deleted = 1 ");
		
		
		if($_FILES["profile_image"]["name"]!= "")
		{
			$profile_image = $this->upload_profile_img();
			$data['profile_image']=$profile_image;
			
		}
		/* insert data in different tables*/
		$this->db->insert('users',$data);
		$user_id = $this->db->insert_id();
		
		$swimlane_data = array(
			'user_id' => $user_id,
			'swimlanes_name' => 'default',
			'swimlanes_desc' => 'default',
			'seq' => '1',
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('swimlanes',$swimlane_data);
		
		$last_remember_data = array(
			'user_id' => $user_id,
			'sidbar_collapsed'=>'0',
			'kanban_project_id' => 'all',
			'calender_project_id' => 'all',
			'task_status_id' => 'all',
			'due_task' => 'all',
			'kanban_team_user_id' => $user_id,
			'calender_team_user_id' =>$user_id,
			'show_cal_view' => '1',
			'calender_sorting' => '1',
			'last_calender_view' => '1'
		);
		$this->db->insert('last_remember_search',$last_remember_data);
		
		$colors = get_colors();
		if($colors){
			$i = 1;
			foreach($colors as $col){
				$color_data = array(
					'color_id' => $col->color_id,
					'user_id' => $user_id,
					'color_name' => $col->color_name,
					'name' => $col->color_name,
					'color_code' => $col->color_code,
					'outside_color_code' => $col->outside_color_code,
					'seq' => $i,
					'status' => 'Active',
					'date_added' => date('Y-m-d H:i:s')
				);
				$this->db->insert('user_colors',$color_data);
				$i++;
			}
		}
		
		$company_Calendar = getCompanyCalendar($this->input->post('company_id'));
		if($company_Calendar){
			$calender_data = array(
				'user_id' => $user_id,
				'MON_hours' => $company_Calendar['MON_hours'],
				'TUE_hours' => $company_Calendar['TUE_hours'],
				'WED_hours' => $company_Calendar['WED_hours'],
				'THU_hours' => $company_Calendar['THU_hours'],
				'FRI_hours' => $company_Calendar['FRI_hours'],
				'SAT_hours' => $company_Calendar['SAT_hours'],
				'SUN_hours' => $company_Calendar['SUN_hours'],
				'MON_closed' => $company_Calendar['MON_closed'],
				'TUE_closed' => $company_Calendar['TUE_closed'],
				'WED_closed' => $company_Calendar['WED_closed'],
				'THU_closed' => $company_Calendar['THU_closed'],
				'FRI_closed' => $company_Calendar['FRI_closed'],
				'SAT_closed' => $company_Calendar['SAT_closed'],
				'SUN_closed' => $company_Calendar['SUN_closed']
			);
			$this->db->insert('default_calendar_setting',$calender_data);
		} else {
			$calender_data = array(
				'comapny_id' => '0',
				'user_id' => $user_id,
				'fisrt_day_of_week' => 'Monday',
				'MON_hours' => '480',
				'TUE_hours' => '480',
				'WED_hours' => '480',
				'THU_hours' => '480',
				'FRI_hours' => '480',
				'SAT_hours' => '0',
				'SUN_hours' => '0',
				'MON_closed' => '1',
				'TUE_closed' => '1',
				'WED_closed' => '1',
				'THU_closed' => '1',
				'FRI_closed' => '1',
				'SAT_closed' => '0',
				'SUN_closed' => '0'
			);
			$this->db->insert('default_calendar_setting',$calender_data);
		}
		
			$no_of_company_by_user = check_user_avaibility_by_email($this->input->post('email'));
			if($no_of_company_by_user <= 1){
				$data_pass = base64_encode(mysql_insert_id()."1@1".$code);
		
		/*Mail Send*/
				$email_template = $this->db->query("select * from " . $this->db->dbprefix('email_template') . " where task='Add New User By Admin'");
				$email_temp = $email_template->row();
		
				$email_address_from = $email_temp->from_address;
				$email_address_reply = $email_temp->reply_address;
		
				$email_subject = $email_temp->subject;
				$email_message = $email_temp->message;
		
				$email = $this->input->post('email');
		
				$user_name = $this->input->post('first_name').' '.$this->input->post('last_name');
				$login_link = front_base_url() . "home/login";
				
				
                $activation_link = "<a href='".front_base_url()."home/activation/".$data_pass."'>Activation link</a>";
               // $store_id = $this->input->post('store_id');
				//$query_store = $this->db->get_where('stores',array('store_id'=>$store_id));
				//$res_store = $query_store->row_array();
				//$store_name = ucwords(str_replace("_"," ",$res_store['store_name']));
				//$user_type = $this->input->post('user_type');
		        $login_link = "<a href='".front_base_url()."/home/login'>here</a>";
				$email_to = $email;
		
				$email_message = str_replace('{break}', '<br/>', $email_message);
				$email_message = str_replace('{user_name}', $user_name, $email_message);
				$email_message = str_replace('{email}', $email, $email_message);
				$email_message = str_replace('{password}', $this->input->post ("password"), $email_message);
				$email_message = str_replace('{login_link}', $login_link, $email_message);
				//$email_message = str_replace('{user_type}', $user_type, $email_message);
				//$email_message = str_replace('{sore_name}', $store_name, $email_message);
				$email_message = str_replace('{activation_link}', $activation_link, $email_message);
				
				$str = $email_message;
                                
                                $company_name = getCompanyName($this->input->post('company_id'));
                                
                                $sandgrid_id=$email_temp->sandgrid_id;
                                $sendgriddata = array('subject'=>'Add New User By Admin',
                                'data'=>array('user_name'=>$user_name,'company_name'=>$company_name,'activation_link'=>$activation_link,'email'=>$email));
                                if($sandgrid_id)
                                {
                                    $str = json_encode($sendgriddata);
                                }
	        	/** custom_helper email function **/
                                
                                /**
                                  * Adding mail in mail queue for sending through cronjob.
                                  */
                                
                                $mail_data = array(
                                      "email_to"=>$email_to,
                                      "email_from"=>$email_address_from,
                                      "email_reply"=>$email_address_reply,
                                      "email_subject"=>$email_subject,
                                      "message"=>$str,
                                      "attach"=>'',
                                      "status"=>'pending',
                                      "date"=>date('Y-m-d H:i:s'),
                                      "sandgrid_id"=>$sandgrid_id
                                      );
                                $this->db->insert('email_queue',$mail_data);
                                
		        //email_send($email_address_from, $email_address_reply, $email_to, $email_subject, $str);
				
			}else{
					
				$email_template = $this->db->query("select * from " . $this->db->dbprefix('email_template') . " where task='Add User To New Company'");
				$email_temp = $email_template->row();
		
				$email_address_from = $email_temp->from_address;
				$email_address_reply = $email_temp->reply_address;
		
				$email_subject = $email_temp->subject;
				$email_message = $email_temp->message;
		
				$email = $this->input->post('email');
		
				$user_name = $this->input->post('first_name').' '.$this->input->post('last_name');
				$company_name = getCompanyName($this->input->post('company_id'));
				$email_to = $email;
		
				$email_message = str_replace('{break}', '<br/>', $email_message);
				$email_message = str_replace('{user_name}', $user_name, $email_message);
				$email_message = str_replace('{company_name}', $company_name, $email_message);
				//$email_message = str_replace('{sore_name}', $store_name, $email_message);
                                $data_pass = base64_encode(mysql_insert_id()."1@1".$code);
				$activation_link = "<a href='".front_base_url()."home/activation_email/".$data_pass."'>Activation link</a>";
				$str = $email_message;
                                $sandgrid_id=$email_temp->sandgrid_id;
                                $sendgriddata = array('subject'=>'Add User To New Company',
                            'data'=>array('user_name'=>$user_name,'company_name'=>$company_name,'activation_link'=>$activation_link,'email'=>$email));
                                if($sandgrid_id)
                                {
                                    $str = json_encode($sendgriddata);
                                }
	        	/** custom_helper email function **/
                                
                                /**
                                  * Adding mail in mail queue for sending through cronjob.
                                  */
                                
                                $mail_data = array(
                                      "email_to"=>$email_to,
                                      "email_from"=>$email_address_from,
                                      "email_reply"=>$email_address_reply,
                                      "email_subject"=>$email_subject,
                                      "message"=>$str,
                                      "attach"=>'',
                                      "status"=>'pending',
                                      "date"=>date('Y-m-d H:i:s'),
                                      "sandgrid_id"=>$sandgrid_id
                                      );
                                $this->db->insert('email_queue',$mail_data);
                                
			}
	
				//echo $str; die; 
	
    
	
		/** custom_helper email function **/
	}
	/**
         * This function is called from user class for update user details in DB.
         * @param int $user_id
         * @returns void
         */
	function user_update($user_id)
	{
            /**
             * get all data and update user table 
             */
		if($this->input->post('is_manager')){$is_manager_val = "1"; } else { $is_manager_val = "0"; }
		//pr($_POST);die;
		$data["first_name"] = $this->input->post('first_name');
		$data["last_name"] = $this->input->post('last_name');
		$data["email"] = $this->input->post('email');
		//$data['contact_no'] =$this->input->post('contact_no');
		$data['staff_level'] = $this->input->post('staff_level');
		$data['user_time_zone'] = $this->input->post('user_time_zone');
		
		
		$data['is_manager'] = $is_manager_val;
		$data['is_administrator'] = $this->input->post('is_administrator');
		/*$data['company_id'] = $this->input->post('company_id');*/
		$data['user_status'] = $this->input->post('user_status');
		$data['country_id'] = $this->input->post('country_id');	
		
		
		if($_FILES["profile_image"]["name"]!= "")
		{
			$profile_image = $this->upload_profile_img();
			$data['profile_image']=$profile_image;
			//$this->upload_profile_review_image($data['profile_image']);
		}
		
		$this->db->where('user_id',$user_id);
	    $this->db->update('users',$data);
		
			
		//print_r($data); die;	
		//$this->db->where('user_id',$this->input->post('user_id'));
		//$this->db->update('users',$data);
		
		
	}
	

	function upload_profile_review_image($profile_image)
	{
		//$image_setting = image_setting();
		//echo '===>>'.$image_setting->skill_icon_width.' '.$image_setting->skill_icon_height;die;
		//echo $profile_image;die;
		if($profile_image!='')
         {
             $this->load->library('upload');
             $rand=rand(0,100000); 
			  
             $_FILES['userfile']['name']     =   $_FILES['profile_image']['name'];
             $_FILES['userfile']['type']     =   $_FILES['profile_image']['type'];
             $_FILES['userfile']['tmp_name'] =   $_FILES['profile_image']['tmp_name'];
             $_FILES['userfile']['error']    =   $_FILES['profile_image']['error'];
             $_FILES['userfile']['size']     =   $_FILES['profile_image']['size'];
   
			$config['file_name'] = $profile_image;
			
            $config['upload_path'] = base_path().'upload/profile_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 
             $this->upload->initialize($config);
 
              if (!$this->upload->do_upload())
			  {
				echo $error =  $this->upload->display_errors();die;   
			  } 
			
			   
           	  $picture = $this->upload->data();
		   
              $this->load->library('image_lib');
		   
              $this->image_lib->clear();
		   	
			
					$gd_var='gd2';
				
				
		   if ($_FILES["profile_image"]["type"]!= "image/png" and $_FILES["profile_image"]["type"] != "image/x-png") {		  
			   	$gd_var='gd2';			
			}
			
					
		   if ($_FILES["profile_image"]["type"] != "image/gif") {		   
		    	$gd_var='gd2';
		   }	   
		   
		   if ($_FILES["profile_image"]["type"] != "image/jpeg" and $_FILES["profile_image"]["type"] != "image/pjpeg" ) {		   
		    	$gd_var='gd2';
		   }
		   
             
			$this->image_lib->clear();
			
			 $this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/profile_orig/'.$profile_image,
				'new_image' => base_path().'upload/profile_review_image/'.$profile_image,
				'maintain_ratio' => FALSE,
				'quality' => '100%',
				'width' => 300,
				'height' => 300
			 ));
			
			
			if(!$this->image_lib->resize())
			{
				echo $error = $this->image_lib->display_errors();die;
			}
			
			$profile_image=$profile_image;
			
		
			if($this->input->post('prev_profile_image')!='')
				{
					if(file_exists(base_path().'upload/profile_orig/'.$this->input->post('prev_profile_image')))
					{
						$link=base_path().'upload/profile_orig/'.$this->input->post('prev_profile_image');
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/profile_review_image/'.$this->input->post('prev_profile_image')))
					{
						$link2=base_path().'upload/profile_review_image/'.$this->input->post('prev_profile_image');
						unlink($link2);
					}
					
				}
			} else {
				if($this->input->post('prev_profile_image')!='')
				{
					$profile_image=$this->input->post('prev_profile_image');
				}
			}
			
			//return $profile_image;
	}


	function upload_profile_img()
	{
		//pr($_POST);die;
		$msg = '';
		$user_image='';
		$s3_user_image = '';
		
		if($_FILES['profile_image']['name']!='')
         {
             $this->load->library('upload');
             $rand=rand(0,100000); 
			  
             $_FILES['userfile']['name']     =   $_FILES['profile_image']['name'];
             $_FILES['userfile']['type']     =   $_FILES['profile_image']['type'];
             $_FILES['userfile']['tmp_name'] =   $_FILES['profile_image']['tmp_name'];
             $_FILES['userfile']['error']    =   $_FILES['profile_image']['error'];
             $_FILES['userfile']['size']     =   $_FILES['profile_image']['size'];
   
			$config['file_name'] = 'user'.$rand;
			
            $config['upload_path'] = base_path().'upload/user_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 /* initialize config file of image*/
             $this->upload->initialize($config);
 			
              if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();   
				
			  } 
			
			   
           	  $picture = $this->upload->data();
		  /* this library is used for image manipulation */ 
              $this->load->library('image_lib');
		   
              $this->image_lib->clear();
		   	
			
					$gd_var='gd2';
				
				
		   if ($_FILES["profile_image"]["type"]!= "image/png" and $_FILES["profile_image"]["type"] != "image/x-png") {		  
			   	$gd_var='gd2';			
			}
			
					
		   if ($_FILES["profile_image"]["type"] != "image/gif") {		   
		    	$gd_var='gd2';
		   }	   
		   
		   if ($_FILES["profile_image"]["type"] != "image/jpeg" and $_FILES["profile_image"]["type"] != "image/pjpeg" ) {		   
		    	$gd_var='gd2';
		   }
		   
             
			$this->image_lib->clear();
			
			 $this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/user_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/user/'.$picture['file_name'],
				'maintain_ratio' => FALSE,
				'quality' => '100%',
				'width' => 300,
				'height' => 300
			 ));
			
			
			if(!$this->image_lib->resize())
			{
				$error = $this->image_lib->display_errors();
			}
			
			$new_image = $this->image_lib->new_image;
			 
		
			$bucket = $this->config->item('bucket_name');
		
          	$name = $_FILES['profile_image']['name'];
			$size = $_FILES['profile_image']['size'];
			$tmp = $_FILES['profile_image']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			if(in_array($ext,$valid_formats)){
				
				/* load s3 library and upload image through s3  class method on server */
				$s3_user_image = 'user'.$rand.'.'.$ext;
			    $actual_image_name = "upload/user_orig/".$s3_user_image;
				$new_actual_image_name = "upload/user/".$s3_user_image;
	 			
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, CI_S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, CI_S3::ACL_PUBLIC_READ)){
						if(file_exists(base_path().'upload/user/'.$picture['file_name']))
						{
							$link=base_path().'upload/user/'.$picture['file_name'];
							unlink($link);
						}
					}
					if(file_exists(base_path().'upload/user_orig/'.$picture['file_name']))
					{
						$link=base_path().'upload/user_orig/'.$picture['file_name'];
						unlink($link);
					}
					if($this->input->post('prev_profile_image')!='')
					{
						$delete_image_name = "upload/user_orig/".$this->input->post('prev_profile_image');
						$delete_image_name1 = 'upload/user/'.$this->input->post('prev_profile_image');
						if($this->s3->getObjectInfo($bucket,$delete_image_name)){
							$this->s3->deleteObject($bucket,$delete_image_name);
						}
						if($this->s3->getObjectInfo($bucket,$delete_image_name1)){
							$this->s3->deleteObject($bucket,$delete_image_name1);
						}
					}
					$msg = "success";
				} else {
					$msg = "fail";
	
				}
			} else {
				$msg = "invalid";
			}
			} else {
				if($this->input->post('prev_profile_image')!='')
				{
					$s3_user_image=$this->input->post('prev_profile_image');
				}
			}
			//echo $profile_image;die;
			return $s3_user_image;
	}
	
	/**
         * This function returns only one user details from DB.
         * @param int $id
         * @returns array
         */
	function get_one_user($id)
	{
		/*$query = $this->db->get_where('users',array('user_id'=>$id));
		return $query->row_array();*/
		$this->db->select('users.*');
		$this->db->from('users');
		$this->db->where('users.is_deleted !=','1');
		$this->db->where('users.user_id',$id);
		$query = $this->db->get();
		return $query->row_array();		
	}	
	/**
         * This function returns list of total user in DB.
         * @returns int
         */
	function get_total_user_count()
	{
		$this->db->order_by('user_id','asc');
		$this->db->where('is_deleted !=','1');
		$query = $this->db->get('users');
		
		return $query->num_rows();
		
	}
	
	function get_user_result($offset,$limit)
	{
		$this->db->select('users.*,company.*');
		$this->db->from('users');
		$this->db->join('company','users.company_id = company.company_id','left');
                $this->db->where('users.is_deleted !=','1')
		->order_by('users.user_id','desc');
		$query = $this->db->limit($limit, $offset);
		$query = $this->db->get();
		
		
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
		
	}
	
	/**
         * This function returns match user details from DB.
         * @param string $option
         * @param string $keyword
         * @returns int
         */
	
	function get_total_search_user_count($option,$keyword)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('users.*,company.*');
		$this->db->from('users');
		$this->db->join('company','users.company_id = company.company_id','left');
		//$this->db->join('country_master','users.country = country_master.country_id','left');
		
		$this->db->where('users.is_deleted !=','1');
		
		if($option=='first_name' && $keyword!='1V1')
		{
			$this->db->like('users.first_name',$keyword);
		}
		if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('users.last_name',$keyword);
		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->where('users.email',$keyword);
		}
		if($option=='company_name' && $keyword!='1V1')
		{
			$this->db->where('company.company_name',$keyword);
		}
		$query = $this->db->get();		
		return $query->num_rows();
	}
	
	
	/**
         * It returns user data from DB with limit and offset.
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param int $limit
         * @returns string
         */
	function get_search_user_result($option,$keyword,$offset,$limit)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('users.*,company.*');
		$this->db->from('users');
		$this->db->join('company','users.company_id = company.company_id','left');
		//$this->db->join('country_master','users.country = country_master.country_id','left');
		
		$this->db->where('users.is_deleted !=','1');
		
		if($option=='first_name' && $keyword!='1V1')
		{
			$this->db->like('users.first_name',$keyword);
		}
		if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('users.last_name',$keyword);
		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->where('users.email',$keyword);
		}
		if($option=='company_name' && $keyword!='1V1')
		{
			$this->db->like('company.company_name',$keyword);
		}
		$this->db->order_by('users.user_id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}

	/**
         * This function get user deatils from DB and send link for forget password.
         * @param int $id
         * @returns int|string
         */
	function forgot_password($id)
	{
		$result= $this->get_one_user($id);
		$email = $result['email'];
		$rnd=randomCode();
		
		$query = $this->db->get_where('users',array('email'=>$email));
		
		if($query->num_rows()>0)
		{
			
			$row = $query->row();
			//echo $row->email;die;
			if($row->email != "")
			{
				$rpass= randomCode();
					$ud=array('forget_password_code'=>$rnd,	'password' => md5($rpass));
					
					$this->db->where('user_id',$row->user_id);
					$this->db->update('users',$ud);
						
					//$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='Reset Password Link'");
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='change password'");
	
					$email_temp=$email_template->row();
											
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
											
					$email_subject=$email_temp->subject;				
					$email_message=$email_temp->message;
					
					$username =$row->first_name."".$row->last_name;
					//$password = $row->password;
					$password=$rpass;
					$email = $row->email;
					$email_to=$email;
					
					//$login_link=base_url().'home/index';
				//	$login_link=site_url('home/resetPassword/'.$rnd);
					//$login_link=front_base_url().'home/resetPassword/'.$rnd;
					//$login_link=front_base_url().'home/login';
					 $login_link = "<a href='".front_base_url()."/home/login'>here</a>";
					$email_message=str_replace('{break}','<br/><br/>',$email_message);
					$email_message=str_replace('{user_name}',$username,$email_message);
					$email_message=str_replace('{password}',$password,$email_message);
					$email_message=str_replace('{email}',$email,$email_message);
					//$email_message=str_replace('{reset_link}',$login_link,$email_message);
					$email_message=str_replace('{login_link}',$login_link,$email_message);
					
					$str=$email_message;
					//echo $str;die;
					/** custom_helper email function **/
						
					email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
					
						return '1';
						
				}
				else{
					return '0';
				}
			}	
			
			else
			{
				return 2;
			}
			
	}
/**
 * This returns staff name from DB via company id.
 * @param int $id
 * @returns string
 */
function get_staff($id)
{
                $this->db->select('staff_level_title,staff_level_id');
		$this->db->from('staff_levels');
		$this->db->where('company_id',$id);
		$this->db->where('is_deleted','0');
                $query = $this->db->get();
	  // echo $this->db->last_query();die();
                if ($query->num_rows() > 0) {
		    return $query->result();
		}else{
                    return 0;
                }
	
	
}

function getUserCompanyList($email){
	$query = $this->db->select("u.company_id,c.company_name")
						->from("users u")
						->join("company c","c.company_id = u.company_id",'left')
						->where("u.email",$email)
						->where("u.is_deleted","0")
						->where("u.user_status","Active")
						->get();
	if($query->num_rows()>0){
		return $query->result();
	} else {
		return 0;
	}
}

function getCompanyList($email){
	$ids = $this->getUserCompanyList($email);
	$value = '';
	if($ids){
		foreach($ids as $id){
			$value .= $id->company_id.','; 
		}
	}
	$value = substr($value, 0,-1);
	
	$query = $this->db->select('*')->from("company")->where('status','Active')->where('is_deleted','0')->where_not_in("company_id",$value)->get();
	if($query->num_rows() > 0)
	{
		return $query->result();
	}
}
/**
 * Returns user details through email id.
 * @param string $email
 * @returns object
 */
	function getUserDetail($email)
	{
		$query = $this->db->select('email,password')->from("users")->where('email',$email)->limit(1)->get();
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}	
}
?>
