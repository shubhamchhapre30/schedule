<?php
	
	
	/*** check for server supported cache driver
	** driver type (APC, File, MEMCHACHE)
	***/

	function check_supported_cache_driver()
	{
		
		$CI =& get_instance();
		
		
		////===load cache driver===
		$CI->load->driver('cache');
		
		
		///====check for APC support on server====
		if ($CI->cache->apc->is_supported())
		{
			return 'apc';
		}
		
		///====check for memcached support on server====
		elseif($CI->cache->memcached->is_supported())
		{
			return 'memcached';
		}
		
		///====check for file support on server====
		elseif($CI->cache->file->is_supported())
		{
			return 'file';
		}
		
		else
		{
			return 'none';
		}
		
	}


?>