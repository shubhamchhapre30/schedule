<?php
ini_set('memory_limit','600M');
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
		$city = $this->db->dbprefix('city_master');
		$country = $this->db->dbprefix('country_master');
		$sql = "SELECT COUNT(`city_id`) as TOTAL FROM (`$city` , `$country` c) WHERE `c`.`status` = 'Active' ";
		$query = $this->db->query($sql)->row();
		//usleep(2000000);
		return $query->TOTAL;
	}
	
	function get_City_result($offset,$limit)
	{
		
		$city = $this->db->dbprefix('city_master');
		$state = $this->db->dbprefix('state_master');
		$country = $this->db->dbprefix('country_master');
		
		$sql = "SELECT `ct`.*, `c`.`country_name`,`s`.`state_name` FROM (`$city` ct, `$state` s, `$country` c) WHERE `c`.`country_id` = `ct`.`country_id` AND `c`.`status` = 'Active' AND `s`.`state_id` = `ct`.`state_id` ORDER BY `ct`.`city_name` asc LIMIT $limit OFFSET $offset ";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
	}
	
	function get_total_search_City_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array(",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword= str_replace('%27',"'",$keyword);
		$keyword= mysql_real_escape_string(str_replace('-',' ',$keyword));
		
		$city = $this->db->dbprefix('city_master');
		$state = $this->db->dbprefix('state_master');
		$country = $this->db->dbprefix('country_master');
		
		$sql = "SELECT COUNT(`ct`.`city_id`) as TOTAL FROM (`$city` ct, `$state` s, `$country` c) WHERE `c`.`country_id` = `ct`.`country_id` AND `s`.`state_id` = `ct`.`state_id` ";

		if($option=='city_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$c.=" `ct`.`city_name` like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c=" `ct`.`city_name` like ('%".$keyword."%') ";
			}
			
			$sql .= " AND ( $c )";
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
			$sql .= " AND ( $c )";
		}
		
		if($option=='country_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$c.=" `c`.`country_name` like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c=" `c`.`country_name` like ('%".$keyword."%') ";
			}
			$sql .= " AND ( $c )";
		}
		
		if($option=='status' && $keyword!='1V1')
		{
			$sql .= "AND `ct`.`status` like ('$keyword')";
		}
		
		$query = $this->db->query($sql)->row();
		return $query->TOTAL;
	}
     function get_search_city_result($option,$keyword,$sort_type,$sort_by,$offset, $limit)
	{
		$keyword=str_replace('"','',str_replace(array(",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword= mysql_real_escape_string(str_replace('-',' ',$keyword));
		$this->db->select('city_master.*,country_master.country_name,state_master.state_name');
		$this->db->from('city_master');
		$this->db->join('country_master','country_master.country_id= city_master.country_id','left');
		$this->db->join('state_master','city_master.state_id= state_master.state_id','left');
		$this->db->where('country_master.status','active');
		
		if($option=='city_name' || $option=='all')
		{
			$this->db->like('city_name',$keyword);
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				foreach($ex as $val)
				{
					$this->db->like('city_name',$val);
				}	
			}
		}
		
		if($option=='state_name' || $option=='all')
		{
			$this->db->like('state_name',$keyword);
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				foreach($ex as $val)
				{
					$this->db->like('state_name',$val);
				}	
			}
		}
		
		if($option=='country_name' || $option=='all')
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
		if($option=='status')
		{
			$this->db->where('city_master.status',$keyword);	
		}
			
		$this->db->order_by('city_master.city_name','asc');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
		    return $query->result();
		}
		
		return 0;
	}

	function get_search_City_result1($option,$keyword,$sort_on,$sort_type,$offset,$limit)
	{

		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		$city = $this->db->dbprefix('city_master');
		$state = $this->db->dbprefix('state_master');
		$country = $this->db->dbprefix('country_master');
		
		$sql = "SELECT `ct`.*, `c`.`country_name`,`s`.`state_name` FROM (`$city` ct, `$state` s, `$country` c) WHERE `c`.`country_id` = `ct`.`country_id` AND `s`.`state_id` = `ct`.`state_id` ";

		if($option=='city_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$c.=" `ct`.`city_name` like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c=" `ct`.`city_name` like ('%".$keyword."%') ";
			}
			
			$sql .= " AND ( $c )";
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
			$sql .= " AND ( $c )";
		}
		
		if($option=='country_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$c.=" `c`.`country_name` like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c=" `c`.`country_name` like ('%".$keyword."%') ";
			}
			$sql .= " AND ( $c )";
		}
		
		if($option=='status' && $keyword!='1V1')
		{
			$sql .= "AND `ct`.`status` like ('$keyword')";
		}
		
		if($sort_type!='1V1' && $sort_on!='1V1')
		{
			$sql .= " ORDER BY $sort_on $sort_type ";
		}
		else
		{
			$sql .= " ORDER BY `ct`.`city_name` asc ";
		}
		$sql .= " LIMIT $limit OFFSET $offset ";
		$query = $this->db->query($sql);
		if($query->num_rows()>0)
		{
			return $query->result(); 
		}
		else
		{
			return '';
		}
	}

}
?>