<?php


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
	
	function clear_cache()
	{
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0',false);
	header('Pragma: no-cache');
	}



?>