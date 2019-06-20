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
class Payment_setting_model extends CI_Model {
	/**
         * This is default constructor of class.
         * @returns void
         */
    function Payment_setting_model()
    {
        /* call parents method*/
        parent::__construct();	
    }
	
	
    /**
     * This function is used for update payment setting.
     * @returns void
     */
	function payment_setting_update()
	{
$data = array(
			'payment_title' => $this->input->post('payment_title'),
			'Login_username' => $this->input->post('Login_username'),
			'login_password' => $this->input->post('login_password'),
			'API_key' => $this->input->post('API_key'),
			'subdomain' => $this->input->post('subdomain'),
			'payment_mode' => $this->input->post('payment_mode'),
			'payment_status' => $this->input->post('payment_status'),
			
			
	);
		
		$this->db->where('payment_id',$this->input->post('payment_id'));
		$this->db->update('payment_setting',$data); 
	}
	/**
         * This function returns payment setting from DB.
         * @returns array
         */
	function get_payment_data()
	{
		$query = $this->db->get_where('payment_setting');
		return $query->row();
	
	}


}
?>
