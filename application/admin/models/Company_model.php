<?php
/**
 * This class declares database related functions, this all functions is used for database interation.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Company_model extends CI_Model {
	/**
         * This is default constructor of this class.
         * @returns void
         */
    function Company_model()
    {
        /**
             * call base class methods
             */
        parent::__construct();	
    }   
	
	/**
         * This function check company name is exist or not,than it returns boolean value.
         * @param string $str
         * @returns boolean
         */
	function company_unique($str)
	{
		if($this->input->post('company_id'))
		{
			$query = $this->db->get_where('company',array('company_id'=>$this->input->post('company_id')));
			$res = $query->row_array();
			$email = $res['company_name'];
			
			$query = $this->db->query("select company_name from ".$this->db->dbprefix('company')." where company_name = '$str' and company_id!='".$this->input->post('company_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select company_name from ".$this->db->dbprefix('company')." where company_name= '$str' and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
         * By using company name check company email is exist or not.
         * @param string $str
         * @returns boolean
         */
	function company_email_unique($str)
	{
		if($this->input->post('company_id'))
		{
			$query = $this->db->get_where('company',array('company_id'=>$this->input->post('company_id')));
			$res = $query->row_array();
			$email = $res['company_email'];
			
			$query = $this->db->query("select company_email from ".$this->db->dbprefix('company')." where company_email = '$str' and company_id!='".$this->input->post('company_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select company_email from ".$this->db->dbprefix('company')." where company_email = '$str' and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
         * This function check user email is exist or not.
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
         * This function is used for insert company & user details in DB,than it send mail for login . 
         * @param int $new_subscription
         * @returns void
         */
	function company_insert($new_subscription)
	{
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
   
			$config['file_name'] = $rand.'Company';
			
            $config['upload_path'] = base_path().'upload/company_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 
             $this->upload->initialize($config);
 
              if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();
			  } 
			   

			   
           	  $picture = $this->upload->data();
		   
              $this->load->library('image_lib');
		   
              $this->image_lib->clear();
		   	
			
					$gd_var='gd2';
				
			list($width, $height, $type, $attr) = getimagesize($_FILES['profile_image']['tmp_name']);
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
			
			$this->load->library('s3');
		
			$this->config->load('s3'); 
		
			$bucket = $this->config->item('bucket_name');
		
          	$name = $_FILES['profile_image']['name'];
			$size = $_FILES['profile_image']['size'];
			$tmp = $_FILES['profile_image']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			if(in_array($ext,$valid_formats)){
				$s3_user_image = $rand."Company." .$ext;
			    $actual_image_name = "upload/company_orig/".$s3_user_image;
				$new_actual_image_name = "upload/company/".$s3_user_image;
				
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, S3::ACL_PUBLIC_READ)){
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
					if($this->input->post('prev_profile_image')!='')
					{
						$delete_image_name = "upload/company_orig/".$this->input->post('prev_profile_image');
						$delete_image_name1 = 'upload/company/'.$this->input->post('prev_profile_image');
						
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

		
		
			$data["company_name"] = $this->input->post('company_name');
			$data["company_email"] = $this->input->post('company_email');
			$data["company_address"] = $this->input->post('company_address');
			//$data["prev_profile_image"] = $this->input->post('prev_profile_image');
			$data["plan_id"] = $this->input->post('plan_id');
			$data["country_id"] = $this->input->post('country_id');
			$data["company_phoneno"] = $this->input->post('company_phoneno');
			$data["company_timezone"] = $this->input->post('company_timezone');
			$data["company_date_format"] = $this->input->post('company_date_format');
			//$data['subscription_date'] = date("Y-m-d",strtotime($this->input->post('subscription_date')));
			//$data['next_subscription_date'] = date("Y-m-d",strtotime($this->input->post('next_subscription_date')));
			$data['subscription_date'] = $new_subscription->current_period_started_at;
			$data['next_subscription_date'] = $new_subscription->next_assessment_at;
			$data["status"] = $this->input->post('status');
			$data["company_register_date"] = date('Y-m-d h:i:s');
			$data['company_register_IP'] = $_SERVER['REMOTE_ADDR'];
			$data['company_logo']=$s3_user_image;
			
			
			$this->db->insert('company',$data);
			
			$rpass= randomCode();
			$code = randomCode();
			
			
			$data1["first_name"] = $this->input->post('first_name');
			$data1["last_name"] = $this->input->post('last_name');
			$data1["email"] = $this->input->post('email');
			$data1["is_administrator"]='1';
			$data1["is_owner"]='1';
			$data1["password"]=md5($rpass);
			$data1["company_id"] = mysql_insert_id();
			$data1['user_status'] = 'inactive';
			$data1['email_verification_code'] = $code;
			$data1['signup_date'] =date('Y-m-d h:i:s');
			$data1['signup_IP'] = $_SERVER['REMOTE_ADDR'];
			
			$data1['chargify_customer_id'] = $new_subscription->customer->id;
			$data1['chargify_subscriptions_ID'] =$new_subscription->id;
			$data1['chargify_transaction_id'] =$new_subscription->signup_payment_id;
			$data1['chargify_transaction_status'] = $new_subscription->state;
			
			
			$this->db->insert('users',$data1);
			
			
			$data_pass = base64_encode(mysql_insert_id()."1@1".$code);
			
				
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
				$password =$rpass;
				$email_message = str_replace('{break}', '<br/>', $email_message);
				$email_message = str_replace('{user_name}', $user_name, $email_message);
				$email_message = str_replace('{email}', $email, $email_message);
				$email_message = str_replace('{password}', $password, $email_message);
				$email_message = str_replace('{login_link}', $login_link, $email_message);
				//$email_message = str_replace('{user_type}', $user_type, $email_message);
				//$email_message = str_replace('{sore_name}', $store_name, $email_message);
				$email_message = str_replace('{activation_link}', $activation_link, $email_message);
				
				$str = $email_message;
	        	/** custom_helper email function **/
                                
                                /**
                                  * Adding mail in mail queue for sending through cronjob.
                                  */
                                $sandgrid_id=$email_temp->sandgrid_id;
                                $sendgriddata = array('subject'=>'Add New User By Admin',
                                'data'=>array('user_name'=>$user_name,'company_name'=>$data["company_name"],'activation_link'=>$activation_link,'email'=>$email));
                                if($sandgrid_id)
                                {
                                    $str = json_encode($sendgriddata);
                                }
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
                                
                                
		       // email_send($email_address_from, $email_address_reply, $email_to, $email_subject, $str);
			
					//
		
		
		//echo $str;die;
		
	
	}
	/**
         * This function is used for update company data in DB.Firstly it upload image on server than it update company related data in DB.
         * @param int $user_id
         * @returns void
         */
	function company_update($user_id)
	{
		
		//echo $this->input->post('prev_profile_image'); echo "hello";die;
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
   
			$config['file_name'] = $rand.'Company';
			
            $config['upload_path'] = base_path().'upload/company_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 
             $this->upload->initialize($config);
             /* initialize config file of image upload*/
              if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();
			  } 
			   

			   
           	  $picture = $this->upload->data();
		   
              $this->load->library('image_lib');
		   
              $this->image_lib->clear();
		   	
			
					$gd_var='gd2';
				
			list($width, $height, $type, $attr) = getimagesize($_FILES['profile_image']['tmp_name']);
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
			
			$this->load->library('s3');
		
			$this->config->load('s3'); 
		
			$bucket = $this->config->item('bucket_name');
		
          	$name = $_FILES['profile_image']['name'];
			$size = $_FILES['profile_image']['size'];
			$tmp = $_FILES['profile_image']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			if(in_array($ext,$valid_formats)){
				$s3_user_image = $rand."Company." .$ext;
			    $actual_image_name = "upload/company_orig/".$s3_user_image;
				$new_actual_image_name = "upload/company/".$s3_user_image;
				
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, S3::ACL_PUBLIC_READ)){
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
					
					if($this->input->post('prev_profile_image')!='')
					{
						$delete_image_name = "upload/company_orig/".$this->input->post('prev_profile_image');
						$delete_image_name1 = 'upload/company/'.$this->input->post('prev_profile_image');
						
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
			
			
		
		
			$data["company_id"] = $this->input->post('company_id');
			$data["company_name"] = $this->input->post('company_name');
			$data["company_email"] = $this->input->post('company_email');
			$data["company_address"] = $this->input->post('company_address');
			$data["company_logo"] = $s3_user_image;
			$data["plan_id"] = $this->input->post('plan_id');
			$data["country_id"] = $this->input->post('country_id');
			$data["company_phoneno"] = $this->input->post('company_phoneno');
			$data["company_timezone"] = $this->input->post('company_timezone');
			$data["company_date_format"] = $this->input->post('company_date_format');
			
			//$data['subscription_date'] = $this->input->post('subscription_date');
			//$data['next_subscription_date'] = $this->input->post('next_subscription_date');
			$data["status"] = $this->input->post('status');
		
	
		$this->db->where('company_id',$user_id);
	    $this->db->update('company',$data);
	
	
			$data1["user_id"] = $this->input->post('user_id');
			$data1["first_name"] = $this->input->post('first_name');
			$data1["last_name"] = $this->input->post('last_name');
			$data1["email"] = $this->input->post('email');
			$data1['chargify_subscriptions_ID'] = $this->input->post("chargify_subscriptions_ID");
			
				$this->db->where('user_id',$this->input->post('user_id'));
	    $this->db->update('users',$data1);
		
	}
	/**
         * This function returns array of company details.
         * @param int $id
         * @returns array
         */
	function get_one_company($id)
	{
		/*$query = $this->db->get_where('user',array('user_id'=>$id));
		return $query->row_array();*/
		$this->db->select('company.*,users.*');
		$this->db->from('company');
		$this->db->join('users','company.company_id = users.company_id','left');
		$this->db->where('company.is_deleted !=','1');
		$this->db->where('company.company_id',$id);
		$query = $this->db->get();
		return $query->row_array();		
	}	
	
	function get_one_company1($id)
	{
		/*$query = $this->db->get_where('user',array('user_id'=>$id));
		return $query->row_array();*/
		$this->db->select('company_address.*');
		$this->db->from('company');
		$this->db->where('company.is_deleted !=','1');
		$this->db->where('company.company_id',$id);
		$query = $this->db->get();
		if($query->num_rows()>0)
			 {
			 	$data['num_rows']=$query->num_rows();
				$data['result']=$query->result();
				return $data;
			 }	
	}
	
	function get_total_company_count()
	{
		$this->db->select('company.*,users.chargify_subscriptions_ID');
		$this->db->from('company');
		$this->db->join('users','users.company_id = company.company_id','left');
		$this->db->order_by('company.company_id','desc');
		$this->db->where('company.is_deleted !=','1');
		$this->db->group_by('company_id');
		$query = $this->db->get();
		
		return $query->num_rows();
		
	}
	/**
         * This function returns company details.
         * @param type $offset
         * @param type $limit
         * @return string
         */
	function get_company_result($offset,$limit)
	{
		$this->db->select('company.*,users.chargify_subscriptions_ID');
		$this->db->from('company');
		$this->db->join('users','users.company_id = company.company_id','left');
		$this->db->order_by('company.company_id','desc');
		$this->db->where('company.is_deleted !=','1');
		$this->db->group_by('company_id');
		$query = $this->db->limit($limit, $offset);
		$query = $this->db->get();
		
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
		
	}
	
	
	/**
         * This function returns customer list via company_id.
         * @param int $id
         * @returns int
         */
	
	function get_total_customer_count($id)
	{
		$this->db->order_by('user_id','asc');
		$this->db->where('users.is_deleted !=','1');
		$this->db->where('users.company_id =',$id);
		$this->db->where('users.user_status =','Active');
		$query = $this->db->get('users');
		
		return $query->num_rows();
		
	}
	/**
         * This function get customer details via company_id from DB.
         * @param int $id
         * @param int $offset
         * @param int $limit
         * @returns array|null
         */
	function get_customer_result($id,$offset,$limit)
	{
		$this->db->select('users.*');
		$this->db->from('users')
		->order_by('users.user_id','desc');
		$this->db->where('users.is_deleted !=','1');
		$this->db->where('users.company_id =',$id);
		$this->db->where('users.user_status =','Active');
		$query = $this->db->limit($limit, $offset);
		$query = $this->db->get();
		
		
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
		
	}
	
	
	/**
         * This function get search details from DB and returns .
         * @returns void 
         */
	
	function get_total_search_company_count($option,$keyword)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('company.*,users.chargify_subscriptions_ID');
		$this->db->from('company');
		$this->db->join('users','users.company_id = company.company_id','left');
		$this->db->where('company.is_deleted !=','1');
		
		if($option=='company_name' && $keyword!='1V1')
		{
			$this->db->like('company.company_name',$keyword);
		}
		if($option=='company_email' && $keyword!='1V1')
		{
			$this->db->like('company.company_email',$keyword);
		}
		if($option=='chargify_subscriptions_ID' && $keyword!='1V1')
		{
			$this->db->like('users.chargify_subscriptions_ID',$keyword);
		}
		$this->db->group_by('company.company_id');
		$query = $this->db->get();		
		return $query->num_rows();
	}
	
	
	
	function get_search_company_result($option,$keyword,$offset,$limit)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('company.*,users.chargify_subscriptions_ID');
		$this->db->from('company');
		$this->db->join('users','users.company_id = company.company_id','left');
		$this->db->where('company.is_deleted !=','1');
		if($option=='company_name' && $keyword!='1V1')
		{
			$this->db->like('company.company_name',$keyword);
		}
	
		if($option=='company_email' && $keyword!='1V1')
		{
			$this->db->like('company.company_email',$keyword);
		}
		if($option=='chargify_subscriptions_ID' && $keyword!='1V1')
		{
			$this->db->like('users.chargify_subscriptions_ID',$keyword);
		}
		$this->db->order_by('company.company_id','DESC');
		$this->db->group_by('company.company_id');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}
        
   function get_company_lists($offset,$limit)
	{
		$this->db->select('c.*,ar.*');
		$this->db->from('company c');
		$this->db->join('app_registration ar','ar.api_company_id = c.company_id','left');
                $this->db->order_by('c.company_id','desc');
		$this->db->where('c.is_deleted =','0');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}else{
                    return '';
                }
		
		
	}
	
}
?>
