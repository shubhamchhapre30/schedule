<?php

class Country_model extends CI_Model {
	
    function Country_model()
    {
        parent::__construct();	
    }   
	
	
	
	
	function Country_insert()
	{
		 // echo '<pre>';
		 // print_r($_FILES);
		 // print_r($_POST);die;
	
			$data = array(
			'country_name' => $this->input->post('country_name'),
			'status' => $this->input->post('status'),
					
		);
		//print_r($data); die;	
		
		$this->db->insert('country_master',$data);
		
			
			/*End mail send*/	
		
	}
	
	function Country_update()
	{
		//echo "gggg"; die;
		// echo '<pre>';
		// print_r($_FILES);
		// print_r($_POST);die;
		$data = array(
			'country_name' => $this->input->post('country_name'),
			'status' => $this->input->post('status'),
		
		);		
		
		//print_r($this->input->post('country_id')); die;
		$this->db->where('country_id',$this->input->post('country_id'));
		$this->db->update('country_master',$data);
		
		
	}
		
	function get_one_Country($id)
	{
		$query = $this->db->get_where('country_master',array('country_id'=>$id));
		return $query->row_array();
	}	
	
	function get_total_Country_count()
	{
		return $this->db->count_all('country_master');
	}
	
	function get_Country_result($offset,$limit)
	{
		
		
		$this->db->order_by('country_id','asc');
		$query = $this->db->get('country_master',$limit,$offset);
		

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
	}
	
	
	
	
	function get_total_search_Country_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		//$option='Countryname';
		
		$this->db->select('country_master.*');
		$this->db->from('country_master');
		//echo $keyword;die;
		
		if($option=='country_name' && $keyword!='1V1')
		{	$c='';
			//$this->db->like('country_name',$keyword);
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('country_name',$val);
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
			$this->db->where('status',$keyword);
		}
		
		
		$this->db->order_by('country_id','asc');
		
		$query = $this->db->get();
		
		//echo $this->db->last_query();die;
		return $query->num_rows();
	}
	
	
	
	function get_search_Country_result($option,$keyword,$sort_on,$sort_type,$offset,$limit)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		$this->db->select('country_master.*');
		$this->db->from('country_master');

		if($option=='country_name' && $keyword!='1V1')
		{
			$c='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
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
			$this->db->where('status',$keyword);
		}
		
		if($sort_type!='1V1' && $sort_on!='1V1')
		{
			$this->db->order_by($sort_on,$sort_type);
		}
		else
		{
			$this->db->order_by('country_id','asc');
		}
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}
	
	function downloadCountryDate($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		
		
		//$option='ServiceProvidername';
		
		$this->db->select('cou.country_id as "Country Id",cou.Countries_ISO_Code as "Country Iso Code",cou.country_name as "Country Name",cou.status as Status');
		$this->db->from('country_master as cou');
		//$this->db->where(array('user_type'=>'serviceprovider'));
		//$this->db->join('country_master cm','u.country=cm.country_id');
		//$this->db->join('state_master sm','u.state=sm.state_id');
		
		
		if($option=='country_name' && $keyword!='1V1')
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

		/*if($option=='status' && $keyword!='1V1')
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

		}*/
		
		if($option=='status' && $keyword!='1V1')
		{
			$this->db->where('status',$keyword);
		}
		
		
		$this->db->order_by('country_id','asc');
		
		$query = $this->db->get();
		
		//echo $this->db->last_query();die;
		return $query;
	}

	

}
?>