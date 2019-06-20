<?php

	/**
	 * CodeIgniter Facebook Connect Graph API Library 
	 * 
	 * Author: Graham McCarthy (graham@hitsend.ca) HitSend inc. (http://hitsend.ca)
	 * 
	 * VERSION: 1.0 (2010-09-30)
	 * LICENSE: GNU GENERAL PUBLIC LICENSE - Version 2, June 1991
	 * 
	 **/

	include(APPPATH.'libraries/facebook/facebook.php');

	class Fb_connect {
		//declare private variables
		private $_obj;
		private $_api_key		= NULL;
		private $_secret_key	= NULL;
		
		//declare public variables
		public 	$user 			= NULL;
		public 	$user_id 		= FALSE;
		
		public $fbLoginURL 	= "";
		public $fbLogoutURL = "";
		
		public $fb 			= FALSE;
		public $fbSession	= FALSE;
		public $appkey		= 0;
		
		//constructor method.
		function Fb_connect()
		{
			//Using the CodeIgniter object, rather than creating a copy of it
			$this->_obj =& get_instance();
			
			//loading the config paramters for facebook (where we stored our Facebook API and SECRET keys
			$this->_obj->load->config('facebook');
			//make sure the session library is initiated. may have already done this in another method.
			$this->_obj->load->library('session'); 
			
			$this->_api_key		= $this->_obj->config->item('facebook_api_key');
			$this->_secret_key	= $this->_obj->config->item('facebook_secret_key');

			$this->appkey = $this->_api_key; 
		
			//connect to facebook
			$this->fb = new Facebook(array(
						  'appId'  => $this->_api_key,
						  'secret' => $this->_secret_key,
						  'cookie' => true,
						));
			
			//store the return session from facebook
			$this->fbSession  =$this->fb->getUser(); 
			
			$me = null;
			// If a valid fbSession is returned, try to get the user information contained within.
			if ($this->fbSession) {
				try {
					//get information from the fb object
			    	$uid = $this->fb->getUser(); 
			    	$me = $this->fb->api('/me');
			    	
			    	$this->user = $me;
			    	$this->user_id = $uid;
			    	
			  	} catch (FacebookApiException $e) {
			    	error_log($e);
			  	}
			}			
	  //include_once( APPPATH.'/libraries/MY_Language'.EXT);
      //$my_language=new MY_Language();

     //$slang=strtoupper($my_language->lang());		
	  $loginUrl   = $this->fb->getLoginUrl(
            array(
                'scope'         => 'email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown',
                'redirect_uri'  =>  site_url("/home/facebook/")
            )
    );
				$this->fbLoginURL =  $loginUrl   ;
			// login or logout url will be needed depending on current user state.
			//(if using the javascript api as well, you may not need these.)
			//var_dump($this->fbLoginURL);
			//	var_dump($this->fbLogoutURL);
			if ($me) {
				$this->fbLogoutURL = $this->fb->getLogoutUrl();
			} else {
			 $loginUrl   = $this->fb->getLoginUrl(
            array(
                'scope'         => 'email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown',
                'redirect_uri'  =>  site_url("/home/facebook/")
            )
    );
				$this->fbLoginURL =  $loginUrl   ;
			}
			//var_dump($this->fbLoginURL);exit;			
		} //end Fb_connect() function
		
		function publish($str=array())
		{
			/*	$this->fb = new Facebook(array(
						  'appId'  => $this->_api_key,
						  'secret' => $this->_secret_key,
						  'cookie' => true,
						));*/
				 $user = $this->fb->getUser(); 
				 $publishStream =  $this->fb->api("/$user/feed", 'post',$str);
		}
	} // end class