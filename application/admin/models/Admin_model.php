<?php
/**
 * This class contain functions which is used for database intraction, this class is used to access data from database.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Admin_model extends CI_Model {
	/**
         * This is default constructor of class.
         * @returns void
         */
    function Admin_model()
    {
        /* call base class methods*/
        parent::__construct();	
    }   
	
	/**
         * This function is used for check username is unique or not.
         * @param string $str
         * @returns boolean
         */
	function user_unique($str)
	{
		if($this->input->post('admin_id'))
		{
			$query = $this->db->get_where('admin',array('admin_id'=>$this->input->post('admin_id')));
			$res = $query->row_array();
			$email = $res['username'];
			
			$query = $this->db->query("select username from ".$this->db->dbprefix('admin')." where username = '$str' and admin_id!='".$this->input->post('admin_id')."'");
		}else{
			$query = $this->db->query("select username from ".$this->db->dbprefix('admin')." where username = '$str'");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	function user_email_unique($str)
	{
		if($this->input->post('admin_id'))
		{
			
			
			$query = $this->db->query("select email from ".$this->db->dbprefix('admin')." where email = '$str' and admin_id!='".$this->input->post('admin_id')."'");
		}else{
			$query = $this->db->query("select email from ".$this->db->dbprefix('admin')." where email = '$str'");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	/**
         * This function is used for insert new admin in db .it upload image through s3 library than it will insert data in DB.
         * @returns void
         */
	
	function admin_insert()
	{
		$msg = '';
		$user_image='';
		$s3_user_image = '';
         //$image_settings=image_setting();
                /* check image select or not*/
		 if($_FILES['profile_image']['name']!='')
         {
             $this->load->library('upload');
             $rand=rand(0,100000); 
			  
             $_FILES['userfile']['name']     =   $_FILES['profile_image']['name'];
             $_FILES['userfile']['type']     =   $_FILES['profile_image']['type'];
             $_FILES['userfile']['tmp_name'] =   $_FILES['profile_image']['tmp_name'];
             $_FILES['userfile']['error']    =   $_FILES['profile_image']['error'];
             $_FILES['userfile']['size']     =   $_FILES['profile_image']['size'];
   
			$config['file_name'] = $rand.'Admin';
			
            $config['upload_path'] = base_path().'upload/admin_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
            /* initialize config for image upload*/
         	$this->upload->initialize($config);
 
	          if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();
			  } 
			   
			$picture = $this->upload->data();
		 /*load image_lib library for image manipulation*/  
              $this->load->library('image_lib');
		   
              $this->image_lib->clear();
		   	
			$gd_var='gd2';
				
			 $this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/admin_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/admin/'.$picture['file_name'],
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
				
				
				$s3_user_image = $rand."Admin." .$ext;
			    $actual_image_name = "upload/admin_orig/".$s3_user_image;
				$new_actual_image_name = "upload/admin/".$s3_user_image;
	 			/* through s3 put object in server*/
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, S3::ACL_PUBLIC_READ)){
						if(file_exists(base_path().'upload/admin/'.$picture['file_name']))
						{
							$link=base_path().'upload/admin/'.$picture['file_name'];
							unlink($link);
						}
					}
					if(file_exists(base_path().'upload/admin_orig/'.$picture['file_name']))
					{
						$link=base_path().'upload/admin_orig/'.$picture['file_name'];
						unlink($link);
					}
					if($this->input->post('pre_profile_image')!='')
					{
						$delete_image_name = "upload/admin_orig/".$this->input->post('pre_profile_image');
						$delete_image_name1 = 'upload/admin/'.$this->input->post('pre_profile_image');
						
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
			if($this->input->post('pre_profile_image')!='')
			{
				$s3_user_image=$this->input->post('pre_profile_image');
			}
		}	
		/* insert data in admin table*/
		$data = array(
			'email' => $this->input->post('emailField'),
			'password' => md5($this->input->post('password')),
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'image'=>$s3_user_image,
			'login_ip' => $_SERVER['REMOTE_ADDR'],
			'status' => $this->input->post('status'),
			'date_added' => date('Y-m-d'),
			'admin_type'=>"1"
		
		);	
		
		$this->db->insert('admin',$data);
		
	}
	/**
         * This function is used for update admin details in db.this function first upload image through s3 config than update data in db.
         * @returns void
         */
	function admin_update()
	{
		$msg = '';
		$user_image='';
		$s3_user_image = '';
         //$image_settings=image_setting();
                /* check image select or not*/
		 if($_FILES['profile_image']['name']!='')
         {
             $this->load->library('upload');
             $rand=rand(0,100000); 
			  
             $_FILES['userfile']['name']     =   $_FILES['profile_image']['name'];
             $_FILES['userfile']['type']     =   $_FILES['profile_image']['type'];
             $_FILES['userfile']['tmp_name'] =   $_FILES['profile_image']['tmp_name'];
             $_FILES['userfile']['error']    =   $_FILES['profile_image']['error'];
             $_FILES['userfile']['size']     =   $_FILES['profile_image']['size'];
   
			$config['file_name'] = $rand.'Admin';
			
            $config['upload_path'] = base_path().'upload/admin_orig/';
			
            $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 /* initialize config of image upload*/
         	$this->upload->initialize($config);
 
	          if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();
			  } 
			   
			$picture = $this->upload->data();
		   /*load image modify library*/
              $this->load->library('image_lib');
		   
              $this->image_lib->clear();
		   	
			$gd_var='gd2';
				
			 $this->image_lib->initialize(array(
				'image_library' => $gd_var,
				'source_image' => base_path().'upload/admin_orig/'.$picture['file_name'],
				'new_image' => base_path().'upload/admin/'.$picture['file_name'],
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
			
			$this->load->library('s3');
		
			$this->config->load('s3'); 
		
			$bucket = $this->config->item('bucket_name');
		
          	$name = $_FILES['profile_image']['name'];
			$size = $_FILES['profile_image']['size'];
			$tmp = $_FILES['profile_image']['tmp_name'];
			$ext = getExtension($name);
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
			if(in_array($ext,$valid_formats)){
				
				
				$s3_user_image = $rand."Admin." .$ext;
			    $actual_image_name = "upload/admin_orig/".$s3_user_image;
				$new_actual_image_name = "upload/admin/".$s3_user_image;
	 			
				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
				{
					if($this->s3->putObjectFile($this->image_lib->full_dst_path, $bucket , $new_actual_image_name, S3::ACL_PUBLIC_READ)){
						if(file_exists(base_path().'upload/admin/'.$picture['file_name']))
						{
							$link=base_path().'upload/admin/'.$picture['file_name'];
							unlink($link);
						}
					}
					if(file_exists(base_path().'upload/admin_orig/'.$picture['file_name']))
					{
						$link=base_path().'upload/admin_orig/'.$picture['file_name'];
						unlink($link);
					}
					if($this->input->post('pre_profile_image')!='')
					{
						$delete_image_name = "upload/admin_orig/".$this->input->post('pre_profile_image');
						$delete_image_name1 = 'upload/admin/'.$this->input->post('pre_profile_image');
						
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
			if($this->input->post('pre_profile_image')!='')
			{
				$s3_user_image=$this->input->post('pre_profile_image');
			}
		}
			
		//print_r($_POST);die;
		$data = array(
			'email' => $this->input->post('emailField'),
			//'username' => $this->input->post('username'),
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'admin_type'=>"1",
			'image'=>$s3_user_image,
			'login_ip' => $_SERVER['REMOTE_ADDR'],
			'status' => $this->input->post('status'));	
		//print_r($data); die;	
		$this->db->where('admin_id',$this->input->post('admin_id'));
		$this->db->update('admin',$data);
		
		
	}
	

	function admin_insert_ip()
	{
		$data = array(
			'email' => $this->input->post('emailField'),
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
			'admin_type' => $this->input->post('admin_type'),
			'login_ip' => $this->input->post('login_ip'),
			'active' => $this->input->post('active'),
			'date_added' => date('Y-m-d'),
		
		);		
		$this->db->insert('admin',$data);
		
		
		
		
		$CI =& get_instance();	
		$base_url = $CI->config->slash_item('base_url_site');
		$base_path = $CI->config->slash_item('base_path');
		
		$file=$base_path.'admin/.htaccess';
		
		$put_content ='allow from '.$this->input->post('login_ip');
		
	
		
		
		$fh = fopen($file, 'r');
		
		$content='';
		
		while(!feof($fh))
		{	
			$content.=fgets($fh)."<br/>";
		}
		
		
		
		$content = $content.' '.$put_content;
				
		$content = str_replace("<br/>",'',$content);
		
		$new_content = $content;	
		
		
		
		$fw = fopen($file, 'w');
		fwrite($fw,'');
		fwrite($fw,$new_content);
		
		fclose($fw);
		fclose($fh);
		
				
	}
	
	function admin_update_ip()
	{
		
		$get_details = $this->db->query("select * from admin where admin_id='".$this->input->post('admin_id')."'");
		$user_detail=$get_details->row();
		
		$orig_login_ip=$user_detail->login_ip;
		
		$content_original ='allow from '.$orig_login_ip;
		
		
		$CI =& get_instance();	
		$base_url = $CI->config->slash_item('base_url_site');
		$base_path = $CI->config->slash_item('base_path');
		
		$file=$base_path.'admin/.htaccess';
		
		$content_replace ='allow from '.$this->input->post('login_ip');
				
		
		
		$fh = fopen($file, 'r');
		
		$content='';
		
		while(!feof($fh))
		{	
			$content.=fgets($fh)."<br/>";
		}
		
		
		
		$content = str_replace($content_original,$content_replace,$content);
				
		$content = str_replace("<br/>",'',$content);
		
		$fw = fopen($file, 'w');
		fwrite($fw,'');
		fwrite($fw,$content);
				
		fclose($read_file);
		
		
		
		$data = array(
			'email' => $this->input->post('emailField'),
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
			'admin_type' => $this->input->post('admin_type'),
			'login_ip' => $this->input->post('login_ip'),
			'active' => $this->input->post('active'),
			'date_added' => date('Y-m-d'),
		
		);		
		$this->db->where('admin_id',$this->input->post('admin_id'));
		$this->db->update('admin',$data);
		
		
	}
	
	function get_one_admin($id)
	{
		$query = $this->db->get_where('admin',array('admin_id'=>$id));
		return $query->row_array();
	}	
	/**
         * This function fetch data from db where admin_type is 1 and returns no. of rows.
         * @returns int
         */
	function get_total_admin_count()
	{
		$this->db->order_by('admin_id','asc');
		$this->db->where('admin_type','1');
		$query = $this->db->get('admin');
		

			return $query->num_rows();
		
	}
	
	function get_admin_result($offset,$limit)
	{
		
		
		$this->db->order_by('admin_id','asc');
		$this->db->where('admin_type','1');
		$query = $this->db->get('admin',$limit,$offset);
		

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
	}
	
	function get_total_superadmin_count()
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('admin')." where admin_type = '1'");
		return $query->num_rows();
	}
	function get_superadmin_result()
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('admin')." where admin_type = '1'");
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return 0;
	}
	
	
	function get_total_adninistrator_admin_count()
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('admin')." where admin_type = '1'");
		return $query->num_rows();
	}
	function get_adninistrator_result()
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('admin')." where admin_type = '1'");
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return 0;
	}

	
	function get_total_adminlogin_count()
	{
			$query = $this->db->query("select a.username,a.password,a.admin_type,a.email,ad.* from ".$this->db->dbprefix('admin_login')." ad left join ".$this->db->dbprefix('admin')." a on ad.admin_id=a.admin_id order by ad.login_id desc");


		return $query->num_rows();
	}
	
	function get_adminlogin_result($offset, $limit)
	{
			
		$query = $this->db->query("select a.username,a.password,a.admin_type,a.email,ad.* from ".$this->db->dbprefix('admin_login')." ad left join ".$this->db->dbprefix('admin')." a on ad.admin_id=a.admin_id order by ad.login_id desc LIMIT ".$limit." Offset ".$offset);


		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return 0;
	}
	
	
	function get_total_search_admin_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		
		
		//$option='username';
		
		$this->db->select('admin.*');
		$this->db->from('admin');
		$this->db->where('admin_type','1');
		
		if($option=='first_name' && $keyword!='1V1')
		{
			$this->db->like('first_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('first_name',$val);
				}	
			}

		}
		if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('last_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('last_name',$val);
				}	
			}

		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->like('email',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('email',$val);
				}	
			}

		}
		if($option=='admintype' && $keyword!='1V1')
		{
			$this->db->like('admintype',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('admintype',$val);
				}	
			}

		}
		
		$this->db->order_by('admin_id','asc');
		
		$query = $this->db->get();
		
		
		return $query->num_rows();
	}
	
	
	
	function get_search_admin_result($option,$keyword,$offset,$limit)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$this->db->select('admin.*');
		$this->db->from('admin');
		$this->db->where('admin_type','1');
		
		if($option=='first_name' && $keyword!='1V1')
		{
			$this->db->like('first_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('first_name',$val);
				}	
			}

		}
		if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('last_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('last_name',$val);
				}	
			}

		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->like('email',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('email',$val);
				}	
			}

		}
		if($option=='admintype' && $keyword!='1V1')
		{
			$this->db->like('admintype',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('admintype',$val);
				}	
			}

		}
		$this->db->order_by('admin_id','asc');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}

	function get_all_rights()
	{
		$query=$this->db->get('rights');
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}
	function get_admin_rights($admin_id){
		$query=$this->db->get_where('rights_assign',array('admin_id'=>$admin_id));
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}
	function assigin_rights(){
	//echo '<pre>';
	///print_r($_POST);
		$rights_id=$this->input->post('right_name');
		
		$add=$this->input->post('add');
		$update=$this->input->post('update');
		$delete=$this->input->post('delete');
		$view=$this->input->post('view');
		$admin_id=$this->input->post('admin_id');
	//echo '<pre>';
	//print_r($rights_id);
	if($rights_id!='' && is_array($rights_id)){
		foreach($rights_id as $key => $val){
			$chk=$this->db->get_where('rights_assign',array('rights_id'=>$val,'admin_id'=>$admin_id));
			$ra=array(
			'add'=>(isset($add[$val]) && $add[$val]!='')?$add[$val]:'0',
			'update'=>(isset($update[$val]) && $update[$val]!='')?$update[$val]:'0',
			'delete'=>(isset($delete[$val]) && $delete[$val]!='')?$delete[$val]:'0',
			'view'=>(isset($view[$val]) && $view[$val]!='')?$view[$val]:'0',
			);
			//print_r($chk->row());
			//print_r($ra);die;
			if($chk->num_rows()>0){
			
			$this->db->where('assign_id',$chk->row()->assign_id);
			$this->db->update('rights_assign',$ra);
			//echo $this->db->last_query();die;
			}else{
			$ra['admin_id']=$admin_id;
			$ra['rights_id']=$val;
			$this->db->insert('rights_assign',$ra);
			//echo $this->db->last_query();
			}
			
		}
	}
	
	}
        /**
         * This function is used for forget password.Firstly it get admin email via admin id and generate a random code for update admin password field and send mail .
         * @param int $id
         * @returns int|string
         */
	function forgot_password($id)
	{
		$result= $this->get_one_admin($id);
		$email = $result['email'];
		/*
                 * via email get all details of admin
                 */
		$query = $this->db->get_where('admin',array('email'=>$email));
			
		if($query->num_rows()>0)
		{
			
			$row = $query->row();
			
			if($row->email != "")
			{
                            /*
                             * this function generate random code for forget password and update admin table with this password
                             */
				$rpass= randomCode();
				$ud=array(
					'password' => md5($rpass)
				);
					
				$this->db->where('admin_id',$row->admin_id);
				$this->db->update('admin',$ud);
				
				//password sent in email		
				$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='Reset Password Link'");

				$email_temp=$email_template->row();
										
				$email_address_from=$email_temp->from_address;
				$email_address_reply=$email_temp->reply_address;
										
				$email_subject=$email_temp->subject;				
				$email_message=$email_temp->message;
				
				
				$username =$row->first_name.' '.$row->last_name;
				$password = $rpass;
				$email = $row->email;
				$email_to=$email;
					
				$email_message=str_replace('{break}','<br/><br/>',$email_message);
				$email_message=str_replace('{user_name}',$username,$email_message);
				$email_message=str_replace('{password}',$password,$email_message);
				$email_message=str_replace('{email}',$email,$email_message);
					
				$str=$email_message;
				
				email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
					
				return '1';
						
			} else {
				return '0';
			}
		} else {
			return 2;
		}
			
	}
}
?>
