<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	* Name:  Twilio
	*
	* Author: Ben Edmunds
	*		  ben.edmunds@gmail.com
	*         @benedmunds
	*
	* Location:
	*
	* Created:  03.29.2011
	*
	* Description:  Twilio configuration settings.
	*
	*
	*/

	/**
	 * Mode ("sandbox" or "prod")
	 **/
	 $twilio_setting=twilio_setting();
	 
	$config['mode']   = $twilio_setting->mode;

	/**
	 * Account SID
	 **/
	$config['account_sid']   = $twilio_setting->account_sid;

	/**
	 * Auth Token
	 **/
	$config['auth_token']    = $twilio_setting->auth_token;

	/**
	 * API Version
	 **/
	$config['api_version']   = $twilio_setting->api_version;

	/**
	 * Twilio Phone Number
	 **/
	$config['number']        = $twilio_setting->number;


/* End of file twilio.php */