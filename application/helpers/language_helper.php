<?php 

	/*** get site supported language
	** fetch the language from language file.
	**/
	
	/*function get_supported_lang()
	{
		$CI =& get_instance();	
		
		$CI->config->load('language');
		$supported_lang = $CI->config->item('supported_languages');
		
		$arr = array();
		foreach ($supported_lang as $key => $lang)
		{
			$arr[$key] = $lang['folder'];
		}
	
	
		return $arr;
	}*/
	
	
	/*** get site supported language
	** fetch the language from database
	**/
	
	function get_supported_lang()
	{
		$CI =& get_instance();	
		$arr = array();
		
		$query=$CI->db->get_where('language',array('language_active'=>1));
		
		if($query->num_rows()>0)
		{
			$supported_lang = $query->result();			
			
			foreach ($supported_lang as $lang)
			{
				$arr[$lang->language_prefix] = $lang->language_folder;
			}
		}
		else
		{
			$arr['en'] = 'english';
		}
		
		return $arr;
	}
	
	
	
	/*** get site default language
	** fetch the language from language file.
	**/
	
	/*function get_current_language()
	{
		$CI =& get_instance();	
		$CI->config->load('language');
		$default_language = $CI->config->item('default_language');
		
		return $default_language;
	
	}*/
	
	
	/*** get site default language
	** fetch the language from database
	**/
	
	function get_current_language()
	{
		$CI =& get_instance();	
		
		$default_language = 'en';
		
		$query=$CI->db->get_where('language',array('language_active'=>1,'default_language'=>1));
		
		if($query->num_rows()>0)
		{		
			$result=$query->row();
			$default_language = $result->language_prefix;
		}
		
		return $default_language;
	
	}
	
	
	/*** get language switching url	**/

	function get_switch_uri()
	{	
		$lnk_ln = current_url()."?lang=";		
		return $lnk_ln;	
	}



?>