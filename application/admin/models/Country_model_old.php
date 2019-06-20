<?php

class Country_model extends CI_Model {
	
    function Country_model()
    {
        parent::__construct();	
    }   
	
	
	
	function country_insert()
	{
		$data = array(
			'country_name' => $this->input->post('country_name'),
			'country_iso_Code' => $this->input->post('country_iso_Code'),
			'country_code' => $this->input->post('country_code'),
			'minor_age_male' => $this->input->post('minor_age_male'),
			'minor_age_female' => $this->input->post('minor_age_female'),
			'minor_min_age' => $this->input->post('minor_min_age'),
			'minor_max_age' => $this->input->post('minor_max_age'),
			'status' => $this->input->post('status'),
			'date_added'=>date('Y-m-d H:i:s'),
			'ip'=>$_SERVER['REMOTE_ADDR']
		   );		
		$this->db->insert('country_master',$data);
		
	}
	function country_unique($str)
	{
	
		if($this->input->post('country_id'))
		{
			$query = $this->db->get_where('country_master',array('country_id'=>$this->input->post('country_id')));
			$res = $query->row_array();
			$country_name = $res['country_name'];
			
			$query = $this->db->query("select country_name from ".$this->db->dbprefix('country_master')." where country_name= '$str' and country_id!='".$this->input->post('country_id')."'");
		}else{
		
			$query = $this->db->query("select country_name from ".$this->db->dbprefix('country_master')." where country_name = '$str'");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
		
	}
	
	function country_update()
	{
		
		$data = array(
			'country_name' => $this->input->post('country_name'),
			'country_iso_Code' => $this->input->post('country_iso_Code'),
			'country_code' => $this->input->post('country_code'),
			'minor_age_male' => $this->input->post('minor_age_male'),
			'minor_age_female' => $this->input->post('minor_age_female'),
			'minor_min_age' => $this->input->post('minor_min_age'),
			'minor_max_age' => $this->input->post('minor_max_age'),
			'status' => $this->input->post('status'),
			'date_added'=>date('Y-m-d H:i:s'),
			'ip'=>$_SERVER['REMOTE_ADDR']
		   );		
		$this->db->where('country_id',$this->input->post('country_id'));
		$this->db->update('country_master',$data);
		
	}
	
	
	function get_one_country($id)
	{
		$query = $this->db->get_where('country_master',array('country_id'=>$id));
		return $query->row_array();
	}	
	
	function get_total_country_count()
	{
		return $this->db->count_all('country_master');
	}
	
	function get_country_result($offset, $limit)
	{
		$this->db->order_by('country_id','desc');
		$query = $this->db->get('country_master',$limit,$offset);
		

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return 0;
	}
	
	
	
	
	function get_total_search_country_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',$keyword));
		
		
		//$option='username';
		
		$this->db->select('country_master.*');
		$this->db->from('country_master');
		
		if($option=='countryname')
		{
			$this->db->like('country_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('country_name',$val);
				}	
			}

		}
		
		$this->db->order_by("country_id", "desc"); 
		
		$query = $this->db->get();
		
		
		return $query->num_rows();
	}
	
	
	
	function get_search_country_result($option,$keyword,$offset, $limit)
	{
		
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',$keyword));
		
		$this->db->select('country_master.*');
		$this->db->from('country_master');
		
		if($option=='countryname')
		{
			$this->db->like('country_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('country_name',$val);
				}	
			}

		}
		if($option=='countryisocode')
		{
			$this->db->like('country_iso_Code',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('country_iso_Code',$val);
				}	
			}

		}
		if($option=='countrycode')
		{
			$this->db->like('country_code',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('country_code',$val);
				}	
			}

		}
		
		if($option=='minoragemale')
		{
			$this->db->like('minor_age_male',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('minor_age_male',$val);
				}	
			}

		}
		if($option=='minoragefemale')
		{
			$this->db->like('minor_age_female',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('minor_age_female',$val);
				}	
			}

		}
		if($option=='minorminage')
		{
			$this->db->like('minor_min_age',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('minor_min_age',$val);
				}	
			}

		}
		if($option=='minormaxage')
		{
			$this->db->like('minor_max_age',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('minor_max_age',$val);
				}	
			}

		}
		if($option=='status')
		{
			$this->db->like('status',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$this->db->like('status',$val);
				}	
			}

		}
		
		
		
	    $this->db->order_by("country_id", "desc"); 
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return 0;
	}
}
?>