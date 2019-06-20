<?php

class Seosetting_model extends CI_Model {
	
    function Seosetting_model()
    {
        parent::__construct();	
    }   
	

	function seo_setting_update()
	{
		$data = array(	
			'title' => $this->input->post('title'),	
			'meta_keyword'=> $this->input->post('meta_keyword'),
			'meta_description' => $this->input->post('meta_description'),
			);
		//echo $this->input->post('Seo_setting_id');
		//print_r($data); die();
		
		$this->db->where('Seo_setting_id',$this->input->post('Seo_setting_id'));
		$this->db->update('Seosetting',$data);
		
		
		$supported_cache=check_supported_cache_driver();
		
		if(isset($supported_cache))
		{
			if($supported_cache!='' && $supported_cache!='none')
			{
				////===load cache driver===
				$this->load->driver('cache');				
				
				$query = $this->db->get("site_setting");					
				$this->cache->$supported_cache->save('site_setting', $query->row(),CACHE_VALID_SEC);		
				
			}			
			
		}
		
	
		
		
		
	}
	
	

}
?>