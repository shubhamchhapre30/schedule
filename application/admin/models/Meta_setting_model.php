<?php

class Meta_setting_model extends CI_Model {
	
    function Meta_setting_model()
    {
         parent::__construct();	
    }   
	
	/** admin meta setting update function
	* var integer $meta_setting_id
	* var string $title
	* var string $meta_keyword
	* var string $meta_description		
	**/
	function meta_setting_update()
	{
		$data = array(			
			'title' => $this->input->post('title'),
			'meta_keyword' => $this->input->post('meta_keyword'),
			'meta_description' => $this->input->post('meta_description'),
		);
		
		
		$this->db->where('meta_setting_id',$this->input->post('meta_setting_id'));
		$this->db->update('meta_setting',$data);
		
		
		
	$supported_cache=check_supported_cache_driver();
		
		if(isset($supported_cache))
		{
			if($supported_cache!='' && $supported_cache!='none')
			{
			
				////===load cache driver===
				$this->load->driver('cache');				
				
				$query = $this->db->get("meta_setting");
									
				$this->cache->$supported_cache->save('meta_setting', $query->row(),CACHE_VALID_SEC);		
				
			}			
			
		}
		
	
	
	
	}
	
	/*** get meta setting details
	*  return single record array
	**/
	function get_one_meta_setting()
	{
		$query = $this->db->get_where('meta_setting');
		return $query->row();
	}
}
?>