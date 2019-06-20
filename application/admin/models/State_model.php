<?php

class State_model extends CI_Model {
	
    function State_model()
    {
        parent::__construct();	
    }

	function State_insert()
	{
			$data = array(
			'country_id' => $this->input->post('country_id'),
			'state_name' => $this->input->post('state_name'),
			'status' => $this->input->post('status'),
					
		);
		$this->db->insert('state_master',$data);
	}

	function State_update()
	{
		$data = array(
			'country_id' => $this->input->post('country_id'),
			'state_name' => $this->input->post('state_name'),
			'status' => $this->input->post('status'),
		
		);		
		$this->db->where('state_id',$this->input->post('state_id'));
		$this->db->update('state_master',$data);
	}
		
	function get_one_State($id)
	{
		$query = $this->db->get_where('state_master',array('state_id'=>$id));
		return $query->row_array();
	}	
	
	function get_total_State_count()
	{
		$this->db->select('state_master.*,country_master.country_name');
		$this->db->from('state_master');
		$this->db->join('country_master','country_master.country_id=state_master.country_id');
		$this->db->where(array('country_master.status'=>"Active"));
		$this->db->order_by('state_name','asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
	
	function get_State_result($offset,$limit)
	{
		
		$this->db->select('state_master.*,country_master.country_name');
		$this->db->from('state_master');
		$this->db->join('country_master','country_master.country_id=state_master.country_id');
		$this->db->where(array('country_master.status'=>"Active"));
		$this->db->order_by('state_name','asc');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
	}
	
	
	
	
	function get_total_search_State_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		//$option='Statename';
		
		$this->db->select('state_master.*,country_master.country_name');
		$this->db->from('state_master');
		$this->db->join('country_master','country_master.country_id=state_master.country_id');
		$this->db->where(array('country_master.status'=>"Active"));
		//echo $keyword;die;
		
		if($option=='state_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('state_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('state_name',$val);
					$c.="state_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="state_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");
		}
		
		if($option=='country_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('state_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('state_name',$val);
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
			$this->db->where('state_master.status',$keyword);
		}
		
		
		$this->db->order_by('state_name','asc');
		
		$query = $this->db->get();
		
		//echo $this->db->last_query();die;
		return $query->num_rows();
	}
	
	
	
	function get_search_State_result($option,$keyword,$sort_on,$sort_type,$offset, $limit)
	{
		//$option='Statename';
		
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		$this->db->select('state_master.*,country_master.country_name');
		$this->db->from('state_master');
		$this->db->join('country_master','country_master.country_id=state_master.country_id');
		$this->db->where(array('country_master.status'=>"Active"));

		if($option=='state_name' && $keyword!='1V1')
		{
			$c='';

			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					$c.="state_name like ('%".$val."%') OR ";
				}	
				$c=substr($c,0,-3);
			}else{
				$c="state_name like ('%".$keyword."%') ";
			}
		$this->db->where("(".$c.")");

		}
		
		if($option=='country_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('state_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('state_name',$val);
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
			$this->db->where('state_master.status',$keyword);
		}

		if($sort_type!='1V1' && $sort_on!='1V1')
		{
			if($sort_on=='status')
			{
				$this->db->order_by('state_master.status',$sort_type);
			}
			else
			{
				$this->db->order_by($sort_on,$sort_type);
			}
		}
		else
		{
			$this->db->order_by('state_name','asc');
		}

		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}

	

}
?>