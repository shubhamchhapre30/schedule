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
class Plan_model extends CI_Model {
	/**
         * This is default constructor of this class.
         * @returns void
         */
    function Plan_model()
    {
        /**
             * call base class methods
             */
        parent::__construct();	
    }   
	
	/**
         * This function check user email is exist or not,than it returns true or false value.
         * @param string $str
         * @returns boolean
         */
	function user_email_unique($str)
	{
		if($this->input->post('plan_id'))
		{
			$query = $this->db->get_where('plans',array('plan_id'=>$this->input->post('plan_id')));
			$res = $query->row_array();
			$email = $res['email'];
			
			$query = $this->db->query("select email from ".$this->db->dbprefix('plans')." where email = '$str' and plan_id!='".$this->input->post('plan_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select email from ".$this->db->dbprefix('plans')." where email= '$str'  and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
         * This function insert plan details in DB.
         * @returns void
         */
	function plan_insert()
	{
		
		$data["plan_title"] = $this->input->post('plan_title');
		$data["plan_description"] = $this->input->post('plan_description');
		$data["plan_currency_code"] = $this->input->post('plan_currency_code');
		$data['plan_duration'] =$this->input->post('plan_duration');
		//$data["password"] = $this->input->post('password');
		$data['plan_price'] = $this->input->post('plan_price');
		$data['plan_status'] = $this->input->post('plan_status');
		$data['plan_date_added'] =date('Y-m-d h:i:s');
		$data['chargify_product_id'] =$this->input->post('chargify_product_id');
		$data["chargify_component_id"] = $this->input->post('chargify_component_id');
		

		$this->db->insert('plans',$data);
		
		
	
	}
	/**
         * This function updates plan details in DB via plan_id.
         * @param int $plan_id
         * @returns void
         */
	function plan_update($plan_id)
	{
		
		$data["plan_title"] = $this->input->post('plan_title');
		$data["plan_description"] = $this->input->post('plan_description');
		$data["plan_currency_code"] = $this->input->post('plan_currency_code');
		$data['plan_duration'] =$this->input->post('plan_duration');
		//$data["password"] = $this->input->post('password');
		$data['plan_price'] = $this->input->post('plan_price');
		$data['plan_status'] = $this->input->post('plan_status');
		$data['chargify_product_id'] =$this->input->post('chargify_product_id');
		$data["chargify_component_id"] = $this->input->post('chargify_component_id');
         
		$this->db->where('plan_id',$plan_id);
	    $this->db->update('plans',$data);
		
			
		//print_r($data); die;	
		//$this->db->where('plan_id',$this->input->post('plan_id'));
		//$this->db->update('plans',$data);
		
		
	}
	

	function upload_profile_review_image($profile_image)
	{
		$image_setting = image_setting();
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
		$image_setting = image_setting();
		//echo '===>>'.$image_setting->skill_icon_width.' '.$image_setting->skill_icon_height;die;
		$profile_image = '';
		if($_FILES['profile_image']['name']!='')
         {
             $this->load->library('upload');
             $rand=rand(0,100000); 
			  
             $_FILES['userfile']['name']     =   $_FILES['profile_image']['name'];
             $_FILES['userfile']['type']     =   $_FILES['profile_image']['type'];
             $_FILES['userfile']['tmp_name'] =   $_FILES['profile_image']['tmp_name'];
             $_FILES['userfile']['error']    =   $_FILES['profile_image']['error'];
             $_FILES['userfile']['size']     =   $_FILES['profile_image']['size'];
   
			$config['file_name'] = 'profile_image'.$rand;
			
            $config['upload_path'] = base_path().'upload/profile_orig/';
			
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
				'source_image' => base_path().'upload/profile_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/profile_thumb/'.$picture['file_name'],
				'maintain_ratio' => FALSE,
				'quality' => '100%',
				'width' => $image_setting->user_width,
				'height' => $image_setting->user_height
			 ));
			
			
			if(!$this->image_lib->resize())
			{
				$error = $this->image_lib->display_errors();
			}
			
			$profile_image=$picture['file_name'];
			
		
			if($this->input->post('prev_profile_image')!='')
				{
					if(file_exists(base_path().'upload/profile_orig/'.$this->input->post('prev_profile_image')))
					{
						$link=base_path().'upload/profile_orig/'.$this->input->post('prev_profile_image');
						unlink($link);
					}
					
					if(file_exists(base_path().'upload/profile_thumb/'.$this->input->post('prev_profile_image')))
					{
						$link2=base_path().'upload/profile_thumb/'.$this->input->post('prev_profile_image');
						unlink($link2);
					}
					
				}
			} else {
				if($this->input->post('prev_profile_image')!='')
				{
					$profile_image=$this->input->post('prev_profile_image');
				}
			}
			
			return $profile_image;
	}
	
	/**
         * This function returns all details of specific plan id.
         * @param int $id
         * @returns array
         */
	function get_one_plan($id)
	{
		/*$query = $this->db->get_where('plans',array('plan_id'=>$id));
		return $query->row_array();*/
		$this->db->select('plans.*');
		$this->db->from('plans');
		$this->db->where('plans.is_deleted !=','1');
		$this->db->where('plans.plan_id',$id);
		$query = $this->db->get();
		return $query->row_array();		
	}	
	/**
         * This function returns total no. of plans in DB.
         * @returns int
         */
	function get_total_plan_count()
	{
		$this->db->order_by('plan_id','asc');
		$this->db->where('is_deleted !=','1');
		$query = $this->db->get('plans');
		
		return $query->num_rows();
		
	}
	/**
         * This function returns plans details with limit .
         * @param int $offset
         * @param int $limit
         * @returns string|object
         */
	function get_plan_result($offset,$limit)
	{
		$this->db->select('plans.*');
		$this->db->from('plans');
		//$this->db->join('country_master','plans.country = country_master.country_id','left');
	    $this->db->where('plans.is_deleted !=','1')
		->order_by('plans.plan_id','desc');
		$query = $this->db->limit($limit, $offset);
		$query = $this->db->get();
		
		
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
		
	}
	
	/**
         * This function first replace spacial character from keyword,than it get data from DB and return no. of rows.
         * @param string $option
         * @param string $keyword
         * @returns int
         */
	function get_total_search_plan_count($option,$keyword)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('plans.*');
		$this->db->from('plans');
		
		$this->db->where('is_deleted !=','1');
		
		if($option=='plan_title' && $keyword!='1V1')
		{
			$this->db->like('plans.plan_title',$keyword);
		}
		/*if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('plans.last_name',$keyword);
		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->where('plans.email',$keyword);
		}
		if($option=='mobile_no' && $keyword!='1V1')
		{
			$this->db->where('plans.mobile_no',$keyword);
		}*/
		$query = $this->db->get();		
		return $query->num_rows();
	}
	
	/**
         * This function get details of plan from DB with four parameters option,search string,limit and offset, than it returns object of details.
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param int $limit
         * @returns string|object
         */
	
	function get_search_plan_result($option,$keyword,$offset,$limit)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('plans.*');
		$this->db->from('plans');
		
		$this->db->where('is_deleted !=','1');
		
		if($option=='plan_title' && $keyword!='1V1')
		{
			$this->db->like('plans.plan_title',$keyword);
		}
		/*if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('plans.last_name',$keyword);
		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->where('plans.email',$keyword);
		}
		if($option=='mobile_no' && $keyword!='1V1')
		{
			$this->db->like('plans.mobile_no',$keyword);
		}*/
		$this->db->order_by('plans.plan_id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}

	
	function forgot_password($id)
	{
		$result= $this->get_one_user($id);
		$email = $result['email'];
		$rnd=randomCode();
		
		$query = $this->db->get_where('plans',array('email'=>$email));
			
		if($query->num_rows()>0)
		{
			
			$row = $query->row();
			
			if($row->email != "")
			{
				$rpass= randomCode();
					$ud=array('forget_password_code'=>$rnd,	'password' => md5($rpass));
					
					$this->db->where('plan_id',$row->plan_id);
					$this->db->update('plans',$ud);
						
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='Reset Password Link'");
	
					$email_temp=$email_template->row();
											
					$email_address_from=$email_temp->from_address;
					$email_address_reply=$email_temp->reply_address;
											
					$email_subject=$email_temp->subject;				
					$email_message=$email_temp->message;
					
					$username =$row->first_name."".$row->last_name;
					$password = $row->password;
					$email = $row->email;
					$email_to=$email;
					
					//$login_link=base_url().'home/index';
					$login_link=site_url('home/resetPassword/'.$rnd);
					
					$email_message=str_replace('{break}','<br/><br/>',$email_message);
					$email_message=str_replace('{user_name}',$username,$email_message);
					$email_message=str_replace('{password}',$password,$email_message);
					$email_message=str_replace('{email}',$email,$email_message);
					$email_message=str_replace('{reset_link}',$login_link,$email_message);
					
					$str=$email_message;
	
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

function get_companyaddress($id)
{
	$this->db->select('address');
		$this->db->from('company_address');
		$this->db->where('company_id',$id);
		//$this->db->where('is_deleted','0');
	   $query = $this->db->get();
	  // echo $this->db->last_query();die();
	   if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
	
	
}
	
}
?>
