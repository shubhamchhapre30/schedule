<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


	
	class SPACULLUS_Loader extends CI_Loader  
	{
	
		/****
		** Constructor  CI_loader
		**/
		
		
		 function __construct()
		 {
			parent::__construct();
			$CI =& get_instance();			
			$this->load_lang();
	
		 }
		  
	
		/****
		** Maintain Language ?lang=language_prefix
		**/
		
		
		public function load_lang($lang = '')	
		{
	
			$CI =& get_instance();	
				
				
			
			 session_start();

			// Lang set in URL via ?lang=something
			if(!empty($_GET['lang']))
			{
				// Turn en-gb into en
				$lang = substr($_GET['lang'], 0, 2);
				$_SESSION['lang_code'] = $lang;
			}


			// Lang has already been set and is stored in a session
			elseif( !empty($_SESSION['lang_code']) )
			{
				$lang = $_SESSION['lang_code'];
			}



			// Lang has is picked by a user.
			// Set it to a session variable so we are only checking one place most of the time
			elseif( !empty($_COOKIE['lang_code']) )
			{
				$lang = $_SESSION['lang_code'] = $_COOKIE['lang_code'];
			}

			// Still no Lang. Lets try some browser detection then
		   /* else if (!empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ))
			{
				// explode languages into array
				$accept_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		
				log_message('debug', 'Checking browser languages: '.implode(', ', $accept_langs));
		
				// Check them all, until we find a match
				foreach ($accept_langs as $lang)
				{
					// Turn en-gb into en
					$lang = substr($lang, 0, 2);
		
					// Check its in the array. If so, break the loop, we have one!
					if(in_array($lang, array_keys($config['supported_languages'])))
					{
						break;
					}
				}
			}*/
			
			
			
			
			// Whatever we decided the lang was, save it for next time to avoid working it out again
		   $_SESSION['lang_code'] = $lang;
	
		
					
		}
	}
