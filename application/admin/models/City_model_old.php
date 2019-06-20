<?php

class City_model extends CI_Model {
	
    function City_model()
    {
        parent::__construct();	
    }   
	
	
	function City_insert()
	{
			$data = array(
			'country_id' => $this->input->post('country_id'),
			'city_name' => $this->input->post('city_name'),
			'status' => $this->input->post('status'),
					
		);
		$this->db->insert('city_master',$data);
	}
	
	function City_update()
	{
		$data = array(
			'country_id' => $this->input->post('country_id'),
			'city_name' => $this->input->post('city_name'),
			'status' => $this->input->post('status'),
		
		);		
		$this->db->where('city_id',$this->input->post('city_id'));
		$this->db->update('city_master',$data);
	}
	
	
		
	function get_one_City($id)
	{
		$query = $this->db->get_where('city_master',array('city_id'=>$id));
		return $query->row_array();
	}	
	
	function get_total_City_count()
	{
		$this->db->select('city_id');
		$this->db->from('city_master');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
	
	function get_City_result($offset,$limit)
	{
		
		$this->db->select('city_master.*,country_master.country_name');
		$this->db->from('city_master');
		$this->db->join('country_master','country_master.country_id=city_master.country_id');
		$this->db->order_by('city_name','asc');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
	}
	
	
	
	function get_total_search_City_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		$this->db->select('COUNT(city_name),');
		$this->db->from('city_master ct');
		
		$this->db->join('country_master c','c.country_id ','ct.country_id');
		//echo $keyword;die;
		
		if($option=='city_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('city_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('city_name',$val);
					$c.="city_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="city_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");
		}
		
		if($option=='country_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('city_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('city_name',$val);
					$c.="country_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="country_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");
		}
		
		if($option=='status' && $keyword!='1V1')
		{
			$this->db->where('c.status',$keyword);
		}
		
		
		$this->db->order_by('city_name','asc');
		$query = $this->db->get();
		echo $this->db->last_query();
		pr($query->result());die;
		return $query->num_rows();
	}
	
	/*function get_total_search_City_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		$city = $this->db->preffix('city_master');
		$state = $this->db->preffix('state_master');
		$country = $this->db->preffix('country_master');
		
		$sql = "SELECT COUNT(city_id) FROM (`$city` ct, `$state` s, `$country` c) WHERE `c`.`country_id` = `ct`.`country_id` AND `s`.`state_id` = `ct`.`state_id` ";//ORDER BY `city_name` asc

		if($option=='city_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('city_name',$val);
					$c.="city_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="city_name like ('%".$keyword."%') ";
			}
			
			$sql .= " WHERE( $c )";
		}
		
		if($option=='state_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex = explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$c.=" `s`.`state_name` like ('%".$val."%') OR ";
				}	
				$c = substr($c,0,-3);
			}else{
				$c = " `s`.`state_name` like ('%".$keyword."%') ";
			}
			$sql .= " WHERE( $c )";
		}
		
		if($option=='country_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$c.="`c`.`country_name` like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="`c`.`country_name` like ('%".$keyword."%') ";
			}
			$sql .= " WHERE( $c )";
		}
		
		if($option=='status' && $keyword!='1V1')
		{
			$this->db->where('c.status',$keyword);
		}
		
		
		$this->db->order_by('city_name','asc');
		$query = $this->db->get();
		return $query->num_rows();
	}*/
	
	
	function get_search_City_result($option,$keyword,$offset, $limit)
	{
		//$option='Cityname';
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		$this->db->select('ct.*,c.country_name');
		$this->db->from('city_master ct');
		$this->db->join('country_master c','c.country_id ','ct.country_id');
		
		
		if($option=='city_name' && $keyword!='1V1')
		{
			$c='';
			//$this->db->like('city_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('city_name',$val);
					$c.="city_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="city_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");

		}
		if($option=='country_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('city_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('city_name',$val);
					$c.="country_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="country_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");
		}
		
		if($option=='status' && $keyword!='1V1')
		{
			$this->db->where('city_master.status',$keyword);
		}
		
		$this->db->order_by('city_name','asc');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		if ($query->num_rows() > 0) {
				
			return $query->result();
		}
		return '';
	}

	/*function get_search_City_result($option,$keyword,$offset, $limit)
	{
		//$option='Cityname';
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		$this->db->select('ct.*,c.country_name');
		$this->db->from('city_master ct');
		$this->db->from('country_master c');
		$this->db->where('ct.country_id = c.country_id','');
		
		
		if($option=='city_name' && $keyword!='1V1')
		{
			$c='';
			//$this->db->like('city_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('city_name',$val);
					$c.="city_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="city_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");

		}
		if($option=='country_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('city_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('city_name',$val);
					$c.="c.country_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="c.country_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");
		}
		
		if($option=='status' && $keyword!='1V1')
		{
			$this->db->where('ct.status',$keyword);
		}
		
		$this->db->order_by('ct.city_name','asc');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}*/

}
?>