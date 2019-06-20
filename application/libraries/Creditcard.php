<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Code Igniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, pMachine, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://www.codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * PayPal_Lib Controller Class (Paypal IPN Class)
 *
 * This CI library is based on the Paypal PHP class by Micah Carrick
 * See www.micahcarrick.com for the most recent version of this class
 * along with any applicable sample files and other documentaion.
 *
 * This file provides a neat and simple method to interface with paypal and
 * The paypal Instant Payment Notification (IPN) interface.  This file is
 * NOT intended to make the paypal integration "plug 'n' play". It still
 * requires the developer (that should be you) to understand the paypal
 * process and know the variables you want/need to pass to paypal to
 * achieve what you want.  
 *
 * This class handles the submission of an order to paypal as well as the
 * processing an Instant Payment Notification.
 * This class enables you to mark points and calculate the time difference
 * between them.  Memory consumption can also be displayed.
 *
 * The class requires the use of the PayPal_Lib config file.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Commerce
 * @author      Ran Aroussi <ran@aroussi.com>
 * @copyright   Copyright (c) 2006, http://aroussi.com/ci/
 *
 */

// ------------------------------------------------------------------------

class Creditcard {

	var $API_UserName;			// holds the last error encountered
	var $API_Password;			// bool: log IPN results to text file?
	var $API_Signature;			// filename of the IPN log
	var $API_Endpoint;			// holds the IPN response from paypal
	var $version;			// holds the IPN response from paypal
	var $subject;			// holds the IPN response from paypal
	var $AUTH_token;			// holds the IPN response from paypal
	var $AUTH_signature;			// holds the IPN response from paypal
	var $AUTH_timestamp;	
	var $AuthMode;		
	var $nvpHeaderStr;		// holds the IPN response from paypal
	var $USE_PROXY;		// holds the IPN response from paypal
	var $PROXY_HOST;		// holds the IPN response from paypal
	var $PROXY_PORT;		// holds the IPN response from paypal			
	
	function Creditcard()
	{
	}
	function config($config=array())
	{
		//var_dump($config);
		$this->API_UserName=$config['API_USERNAME'];
		$this->API_Password=$config['API_PASSWORD'];
		$this->API_Signature=$config['API_SIGNATURE'];
		$this->API_Endpoint=$config['API_ENDPOINT'];
		$this->version=$config['VERSION'];
		$this->subject=$config['SUBJECT'];
		if(isset($config['AUTH_TOKEN']))$this->AUTH_token=$config['AUTH_TOKEN'];
		if(isset($config['AUTH_TIMESTAMP']))$this->AUTH_timestamp=$config['AUTH_TIMESTAMP'];
		if(isset($config['AUTH_SIGNATURE']))$this->AUTH_signature=$config['AUTH_SIGNATURE'];
		
		if(isset($config['USE_PROXY']))$this->USE_PROXY=$config['USE_PROXY'];
		if(isset($config['PROXY_HOST']))$this->PROXY_HOST=$config['PROXY_HOST'];
		if(isset($config['PROXY_PORT']))$this->PROXY_PORT=$config['PROXY_PORT'];
	}
	function nvpHeader()
	{
	//global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
	//$nvpHeaderStr = "";
	
	if(defined('AUTH_MODE')) {
		//$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
		//$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
		//$AuthMode = "THIRDPARTY";Partner's API Credential and Merchant Email as Subject are required.
		$this->AuthMode = "AUTH_MODE"; 
	} 
	else {
		//echo $API_UserName."==".$API_Password."==".$API_Signature;
		if((!empty($this->API_UserName)) && (!empty($this->API_Password)) && (!empty($this->API_Signature)) && (!empty($this->subject))) {
			$this->AuthMode = "THIRDPARTY";
		}
		
		else if((!empty($this->API_UserName)) && (!empty($this->API_Password)) && (!empty($this->API_Signature))) {
			$this->AuthMode = "3TOKEN";
		}
		
		elseif (!empty($this->AUTH_token) && !empty($this->AUTH_signature) && !empty($this->AUTH_timestamp)) {
			$this->AuthMode = "PERMISSION";
		}
		elseif(!empty($this->subject)) {
			$this->AuthMode = "FIRSTPARTY";
		}
	}
	switch($this->AuthMode) {
		
		case "3TOKEN" : 
				$this->nvpHeaderStr = "&PWD=".urlencode($this->API_Password)."&USER=".urlencode($this->API_UserName)."&SIGNATURE=".urlencode($this->API_Signature);
				break;
		case "FIRSTPARTY" :
				$this->nvpHeaderStr = "&SUBJECT=".urlencode($this->subject);
				break;
		case "THIRDPARTY" :
				$this->nvpHeaderStr = "&PWD=".urlencode($this->API_Password)."&USER=".urlencode($this->API_UserName)."&SIGNATURE=".urlencode($this->API_Signature)."&SUBJECT=".urlencode($this->subject);
				break;		
		case "PERMISSION" :
				$this->nvpHeaderStr = $this->formAutorization($this->AUTH_token,$this->AUTH_signature,$this->AUTH_timestamp);
				break;
	}
		return $this->nvpHeaderStr;
	}
	
	/**
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	*/
	
	
	function hash_call($methodName,$nvpStr)
	{
		//declaring of global variables
		//global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
		// form header string
		$nvpheader=$this->nvpHeader();
		//echo $this->API_Endpoint;
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		//in case of permission APIs send headers as HTTPheders
		if(!empty($this->AUTH_token) && !empty($this->AUTH_signature) && !empty($this->AUTH_timestamp))
		 {
			$headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
	  
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
		curl_setopt($ch, CURLOPT_HEADER, false);
		}
		else 
		{
			$nvpStr=$nvpheader.$nvpStr;
		}
		//if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		//if($this->USE_PROXY)
		//curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST.":".$this->PROXY_PORT); 
	
		//check if version is included in $nvpStr else include the version.
		if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
			$nvpStr = "&VERSION=" . urlencode($this->version) . $nvpStr;	
		}
		
		$nvpreq="METHOD=".urlencode($methodName).$nvpStr;
		
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
	
		//getting response from server
		$response = curl_exec($ch);
	//var_dump($response);exit;
		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;
	
		curl_close($ch);
	
	return $nvpResArray;
	}
	
	/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	  */
	
	function deformatNVP($nvpstr)
	{
	
		$intial=0;
		$nvpArray = array();
	
	
		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
	
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		 }
		return $nvpArray;
	}
	function formAutorization($auth_token,$auth_signature,$auth_timestamp)
	{
		$authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
		return $authString;
	}
	

}

?>