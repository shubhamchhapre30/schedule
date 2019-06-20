<?php
/**
 * This class declares database related functions for interaction with DB, this class is used to access data from database.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Home_model extends CI_Model 
{

	/**
         * This is default constructor of this class.
         * @returns void
         */
	function Home_model()
    {
            /**
             * call base class methods
             */
        parent::__construct();	
    } 
    /**
     * This function is used for check whether this admin is authenticated or not.and if admin is authenticated than it set data in session and insert it's histroy in admin_histroy table.
     * @returns string
     */
	function check_login(){
	
		$username = $this->input->post('username');
		$password =  md5($this->input->post('password'));
		
		$query = $this->db->get_where('admin',array('email'=>$username,'password'=>$password,'status'=>'Active'));
		
		
		if($query->num_rows() > 0)
		{
			$admin = $query->row_array();
			$admin_id = $admin['admin_id'];
			$admin_type = $admin['admin_type'];
			$data = array(
					'admin_id' => $admin_id,
					'username' => $username,
					'admin_type'=>$admin_type
					);	
				
			$this->session->set_userdata($data);
			
			
			$data1=array(
					'admin_id'=>$admin_id,
					'login_date'=> date('Y-m-d H:i:s'),
					'login_ip'=>$_SERVER['REMOTE_ADDR']
					); 
			$this->db->insert('admin_login_history',$data1);
			
			
			return "1";
		}
		else
		{
			return "0";
		}
	
	}
	/**
         * This function is used for get email from input and with the help of this email get admin info and send mail for forget password.
         * @returns int|string
         */
	
	
	function forgot_email()
	{
		$email = $this->input->post('email');
		$rnd=randomCode();
		
		
		/* get data from DB*/
			$query = $this->db->get_where('admin',array('email'=>$email));
			
			if($query->num_rows()>0)
			{
			
				$row = $query->row();
			
				if($row->email != "")
				{
					$rpass= randomCode();
					$ud=array('password_reset_code'=>$rnd,
							'password' => md5($rpass)
						);
				
				$this->db->where('admin_id',$row->admin_id);
				$this->db->update('admin',$ud);
					
					
					
					$email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='Forgot Password Admin'");
										$email_temp=$email_template->row();
										
										
										
										$email_address_from=$email_temp->from_address;
										$email_address_reply=$email_temp->reply_address;
										
										$email_subject=$email_temp->subject;				
										$email_message=$email_temp->message;
										
										$username =$row->first_name.' '.$row->last_name;
										$password = $rpass;
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
	/**
   * This function returns all site setting on admin request.
   * @returns array
   */
  
  function select_site_setting()
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('site_setting')."");
		return $query->row_array();
	}

        /**
         * This function get image setting from db on request.
         * @returns array
         */
	function image_setting()
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('image_setting')."");
		return $query->row_array();
	}
	/**
         * This function check reset code than it will return admin_id.
         * @param string $code
         * @returns string
         */
	function checkResetCode($code='')
	{
		$query=$this->db->get_where('admin',array('password_reset_code'=>$code));
		if($query->num_rows()>0)
		{
			return $query->row()->admin_id; 
			
		}else{
			return '';
		}
	}
	/**
         * It updates password column with new password in DB with decode format.
         * @returns int
         */
	function updatePassword(){
		//echo '<pre>';
		//print_r($_POST);die;
		//$data=array('password'=>md5($this->input->post('password')),'password_reset_code'=>'');
		$data=array('password'=>$this->input->post('password'),'password_reset_code'=>'');
		$this->db->where(array('admin_id'=>base64_decode($this->input->post('admin_id')),'password_reset_code'=>$this->input->post('code')));
		$d=$this->db->update('admin',$data);
		return $d;
	}
	/**
         * This function get all data  and update table with new data in DB.
         * @returns void
         */
	function updateProfile()
	{
		
		$data = array(
			'email' => $this->input->post('emailField'),
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name')
			
		);	
		//print_r($data); die;	
		$this->db->where('admin_id',get_authenticateadminID());
		$this->db->update('admin',$data);
	}
 	
	function updateUserPassword1()
	{
		$data = array('password' =>  md5($this->input->post('password')));		
		$query=$this->db->where(array('admin_id'=>get_authenticateadminID(),'password'=>md5($this->input->post('oldpassword'))))->get_where('admin');
		if($query->num_rows()>0){
		$this->db->where(array('admin_id'=>get_authenticateadminID(),'password'=>md5($this->input->post('oldpassword'))));
		$this->db->update('admin',$data);
		return true;
		}else{
			return false;
		}
	}
	
	/**
         * This function get old and new password.Using authentication id update admin password in DB.
         * @returns boolean
         */
	function updateUserPassword()
	{
		$data = array('password' =>  md5($this->input->post('password')));		
		$query=$this->db->where(array('admin_id'=>get_authenticateadminID(),'password'=>md5($this->input->post('oldpassword'))))->get_where('admin');
		if($query->num_rows()>0){
		$this->db->where(array('admin_id'=>get_authenticateadminID(),'password'=>md5($this->input->post('oldpassword'))));
		$this->db->update('admin',$data);
		return true;
		}else{
			return false;
		}
	}


}

?>
