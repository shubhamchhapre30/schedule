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
class Site_setting_model extends CI_Model {
	/**
         * This is default constructor of this class.
         * @returns void
         */
    function Site_setting_model()
    {/**
             * call parent class methods
             */
        parent::__construct();	
    }
    /**
     * This function used for update setting in DB.
     */
	function site_setting_update()
	{		            
		$data = array(
			'site_setting_id' => $this->input->post('site_setting_id'),
			'site_name' => $this->input->post('site_name'),
			'site_online' => $this->input->post('site_online'),
			//'captcha_enable' => $this->input->post('captcha_enable'),
			//'site_version' => $this->input->post('site_version'),
			'site_language' => $this->input->post('site_language'),
			'currency_symbol' => $this->input->post('currency_symbol'),
			'currency_code' => $this->input->post('currency_code'),
			'date_format' => $this->input->post('date_format'),
			'time_format' => $this->input->post('time_format'),
			'date_time_format' => $this->input->post('date_time_format'),
			//'how_it_works_video' => $this->input->post('how_it_works_video'),
		/*	'zipcode_min' => $this->input->post('zipcode_min'),
			'zipcode_max' => $this->input->post('zipcode_max'),
			'google_map_key' => $this->input->post('google_map_key'),
			'default_longitude' => $this->input->post('default_longitude'),
			'default_latitude' => $this->input->post('default_latitude'),
			'order_close_time' => $this->input->post('order_close_time'),
			'order_cancellation_time' => $this->input->post('order_cancellation_time'),*/
			'admin_email' => $this->input->post('admin_email'),
			'address_data' => $this->input->post('address_data'),
			'site_email' => $this->input->post('site_email'),

			/*'facebook_link'=> $this->input->post('facebook_link'),
			'twitter_link'=> $this->input->post('twitter_link'),
			'instagram_link' =>  $this->input->post('instagram_link'),*/
			'contact_number' =>  $this->input->post('contact_number'),
			
			/*'fullday_buy' =>  $this->input->post('fullday_buy'),
			'skype_id' =>  $this->input->post('skype_id'),
			'shipping_charge' =>  $this->input->post('shipping_charge')*/
			
			
		);
		
		$this->db->where('site_setting_id',$this->input->post('site_setting_id'));
		$this->db->update('site_setting',$data);     
	}
        /**
         * This function returns setting from DB.
         * @returns array
         */
	function get_one_social_data()
	{
		$query = $this->db->get_where('site_setting');
		return $query->row_array();
	}
	function TwilioDataUpdate()
	{
		$data = array(	
			'mode' => $this->input->post('mode'),	
			'account_sid'=> $this->input->post('account_sid'),
			'auth_token' => $this->input->post('auth_token'),
			'api_version' => $this->input->post('api_version'),
			'number'	=>	$this->input->post('number'),
			);
		$this->db->where('twilio_id',$this->input->post('twilio_id'));
		$this->db->update('twilio_setting',$data);
		
	}
	
	
	
	function facebook_setting()
	{
		$query = $this->db->get_where('facebook_setting');
		return $query->row();
	}
	
	function site_facebook_update()
	{
		
		$data = array(	
			'facebook_setting_id' => $this->input->post('facebook_setting_id'),
		    'facebook_application_id'=> $this->input->post('facebook_application_id'),	
			'facebook_login_enable' => $this->input->post('facebook_login_enable'),	
			'facebook_access_token' => $this->input->post('facebook_access_token'),
			'facebook_api_key' => $this->input->post('facebook_api_key'),
		    'facebook_secret_key'=> $this->input->post('facebook_secret_key'),	
			'facebook_user_autopost' => $this->input->post('facebook_user_autopost'),	
			'facebook_wall_post' => $this->input->post('facebook_wall_post'),
			'facebook_url' => $this->input->post('facebook_url'),
		
		);
		//print_r($data); die;
		$this->db->where('facebook_setting_id',$this->input->post('facebook_setting_id'));
		$this->db->update('facebook_setting',$data);
	
	}
	
	
	function google_setting()
	{
		$query = $this->db->get_where('google_setting');
		return $query->row();
	}
	
	
	
	
	function site_google_update()
	{
		
		$data = array(	
			'google_client_id' => $this->input->post('google_client_id'),
		    'google_url'=> $this->input->post('google_url'),	
			'google_login_enable' => $this->input->post('google_login_enable'),	
			'google_client_secret' => $this->input->post('google_client_secret'),
		);
		//print_r($data); die;
		$this->db->where('google_setting_id',$this->input->post('google_setting_id'));
		$this->db->update('google_setting',$data);
	
	}

   function image_setting()
	{
		$query = $this->db->get_where('image_setting');
		return $query->row();
	}
	
	function image_setting_update()
	{
		$data["user_width"] = $this->input->post('user_width');
		$data["user_height"] = $this->input->post('user_height');
		$data["product_width"] = $this->input->post('product_width');
		$data["product_height"] = $this->input->post('product_height');
		$data["gift_card_width"] = $this->input->post('gift_card_width');
		$data["gift_card_height"] = $this->input->post('gift_card_height');	
		
		$this->db->where('image_setting_id',$this->input->post('image_setting_id'));
		$this->db->update('image_setting',$data);
	
	}
	
	function get_admin_setup()
	{
		$query = $this->db->from('popup_setup')->where(array('as_type'=>'Admin','is_deleted'=>'0'))->order_by('as_step_sequence','ASC')->get();
		return $query->result();
	}
	function get_user_setup()
	{
		$query = $this->db->from('popup_setup')->where(array('as_type'=>'User','is_deleted'=>'0'))->order_by('as_step_sequence','ASC')->get();
		return $query->result();
	}
	
	function update_step($step_id)
	{
		$query = $this->db->from('popup_setup')->where(array('as_step_id'=>$step_id))->get();
		$old_step = $query->row();
		
		if($query->num_rows() > 0){
			
			if($old_step->as_step_detail != $this->input->post('detail')){
				$this->db->where('step_id', $step_id);
				$this->db->delete('user_setup');
			}
			
			if($old_step->as_step_status != $this->input->post('status') && $this->input->post('status') !='Inactive'){
				$this->db->where('step_id', $step_id);
				$this->db->delete('user_setup');
			}
			
			$data["as_step_detail"] = $this->input->post('detail');
			$data["as_type"] = $this->input->post('type');
			$data["as_step_status"] = $this->input->post('status');
			
			$this->db->where('as_step_id',$step_id);
			$this->db->update('popup_setup',$data);
			$step_id = $step_id;
			
		}else{
			
			
			$data["as_step_detail"] = $this->input->post('detail');
			$data["as_type"] = $this->input->post('type');
			$data["as_step_status"] = $this->input->post('status');
			$data["as_step_added_date"] = date("Y-m-d");
			$data["is_deleted"] = '0';
			$data["as_step_sequence"] = getNextStep($data["as_type"]);
			
			$this->db->empty_table('user_setup'); 
			
			$this->db->insert('popup_setup',$data);
			$step_id = $this->db->insert_id();
		}
		return $step_id;
	}
	
	function add_step()
	{
		$data["as_step_detail"] = $this->input->post('detail');
		$data["as_type"] = $this->input->post('type');
		$data["as_step_status"] = $this->input->post('status');
		$data["as_step_added_date"] = date("Y-m-d");
		$data["is_deleted"] = '0';
		$data["as_step_sequence"] = getNextStep($data["as_type"]);
		
		$this->db->insert('popup_setup',$data);
		$step_id = $this->db->insert_id();
		
		return $step_id;
	}

function get_one_step($step_id)
	{
		$query = $this->db->where('as_step_id',$step_id)->get('popup_setup');
		return $query->row();
	}

	function get_maintenance_detail()
	{
		$query = $this->db->get('maintenance_setup');
		return $query->row_array();
	}
	
	function update_maintenance($id)
	{
		$data["detail"] = $this->input->post('detail');
		$data["status"] = $this->input->post('status');
		$data["start_date"] = $this->input->post('start_date');
		$data["end_date"] = $this->input->post('end_date');
		$data["duration"] = $this->input->post('duration');
		
		$this->db->where('id',$id);
		$this->db->update('maintenance_setup',$data);
		return $id;
	}
	
	function get_tot_steps()
	{
		$query = $this->db->get('popup_setup');
		return $query->num_rows();
	}

}
?>
