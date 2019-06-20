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
class Plan_subscription_model extends CI_Model {
	/**
         * This is default constructor of this class.
         * @returns void
         */
    function Plan_subscription_model()
    {
        /**
             * call base class methods
             */
        parent::__construct();	
    }   
    /**
     * This function returns details of specific subscriber.
     * @param int $id
     * @returns array
     */
	function get_one_user($id)
	{
		/*$query = $this->db->get_where('users',array('plan_subscription_id'=>$id));
		return $query->row_array();*/
		$this->db->select('plan_subscription.*');
		$this->db->from('plan_subscription');
		$this->db->where('plan_subscription.is_deleted !=','1');
		$this->db->where('plan_subscription.plan_subscription_id',$id);
		$query = $this->db->get();
		return $query->row_array();		
	}	
	/**
         * This function returns total number of subscriber user in DB.
         * @returns int
         */
	function get_total_plan_subscription_count()
	{
		$this->db->order_by('plan_subscription_id','asc');
		
		$query = $this->db->get('plan_subscription');
		
		return $query->num_rows();
		
	}
	/**
         * This function returns all subscriber details from DB.
         * @param int $offset
         * @param int $limit
         * @returns object|null
         */
	function get_plan_subscription_result($offset,$limit)
	{
		$this->db->select('plan_subscription.*,company.*,users.*,plans.*');
		$this->db->from('plan_subscription');
		$this->db->join('company','plan_subscription.company_id = company.company_id','left');
		$this->db->join('users','plan_subscription.user_id = users.user_id','left');
		$this->db->join('plans','plan_subscription.plan_id = plans.plan_id','left');
	  //  $this->db->where('plan_subscription.is_deleted !=','1')
		$this->db->order_by('plan_subscription.plan_subscription_id','desc');
		$query = $this->db->limit($limit, $offset);
		$query = $this->db->get();
		
		
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
		
	}
	
	/**
         * This function returns total match subscription plans from DB for search option.
         * @param string $option
         * @param string $keyword
         * @returns int
         */
	function get_total_search_plan_subscription_count($option,$keyword)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('plan_subscription.*,company.*,users.*,plans.*');
		$this->db->from('plan_subscription');
		$this->db->join('company','plan_subscription.company_id = company.company_id','left');
		$this->db->join('users','plan_subscription.user_id = users.user_id','left');
		$this->db->join('plans','plan_subscription.plan_id = plans.plan_id','left');
		//$this->db->join('country_master','plan_subscription.country = country_master.country_id','left');
		
		//$this->db->where('plan_subscription.is_deleted !=','1');
		
		if($option=='user_name' && $keyword!='1V1')
		{
			$this->db->like('users.first_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->or_like('users.first_name',$val);
				}	
			}

			$this->db->or_like('users.last_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->or_like('users.last_name',$val);
				}	
			}

		}

		/*if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('plan_subscription.last_name',$keyword);
		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->where('plan_subscription.email',$keyword);
		}*/
		if($option=='company_name' && $keyword!='1V1')
		{
			$this->db->where('company.company_name',$keyword);
		}
		$query = $this->db->get();		
		return $query->num_rows();
	}
	
	
	/**
         * This function get match subscription plans details from DB and it returns object. 
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param int $limit
         * @returns Object|Null
         */
	function get_search_plan_subscription_result($option,$keyword,$offset,$limit)
	{
		$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('plan_subscription.*,company.*,users.*,plans.*');
		$this->db->from('plan_subscription');
		$this->db->join('company','plan_subscription.company_id = company.company_id','left');
		$this->db->join('users','plan_subscription.user_id = users.user_id','left');
		$this->db->join('plans','plan_subscription.plan_id = plans.plan_id','left');
		//$this->db->join('country_master','plan_subscription.country = country_master.country_id','left');
		
		//$this->db->where('plan_subscription.is_deleted !=','1');
		
		if($option=='user_name' && $keyword!='1V1')
		{
			$this->db->like('users.first_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->or_like('users.first_name',$val);
				}	
			}

			$this->db->or_like('users.last_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->or_like('users.last_name',$val);
				}	
			}

		}
		/*if($option=='last_name' && $keyword!='1V1')
		{
			$this->db->like('plan_subscription.last_name',$keyword);
		}
		if($option=='email' && $keyword!='1V1')
		{
			$this->db->where('plan_subscription.email',$keyword);
		}*/
		if($option=='company_name' && $keyword!='1V1')
		{
			$this->db->like('company.company_name',$keyword);
		}
		$this->db->order_by('plan_subscription.plan_subscription_id','DESC');
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
		
		$query = $this->db->get_where('plan_subscription',array('email'=>$email));
		
		if($query->num_rows()>0)
		{
			
			$row = $query->row();
			//echo $row->email;die;
			if($row->email != "")
			{
				$rpass= randomCode();
					$ud=array('forget_password_code'=>$rnd,	'password' => md5($rpass));
					
					$this->db->where('plan_subscription_id',$row->plan_subscription_id);
					$this->db->update('plan_subscription',$ud);
						
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

function get_staff($id)
{
	$this->db->select('staff_level_title,staff_level_id');
		$this->db->from('staff_levels');
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
