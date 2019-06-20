<?php

class Pages_model extends CI_Model {
	
    function Pages_model()
    {
        parent::__construct();	
    }   




	
	function get_total_pages_count()
	{
		return $this->db->count_all('pages');
	}
	
	
	
	
	function get_pages_result($offset, $limit)
	{
			$this->db->order_by('pages_id','desc');
		$query = $this->db->get('pages',$limit,$offset);

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return 0;
	}

	function Pages_insert()
	{
		$data = array(
			'pages_title' => $this->input->post('pages_title'),
			'description' => $this->input->post('description'),
			'slug' => $this->get_slug($this->input->post('pages_title')),
			'meta_keyword' => $this->input->post('meta_keyword'),
			'meta_description' => $this->input->post('meta_description'),
			
			//'external_link' => $this->input->post('external_link')
		);		
		$this->db->insert('pages',$data);
	}	

	
	function Pages_update()
	{
		$data = array(			
			'pages_title' => $this->input->post('pages_title'),
			'description' => $this->input->post('description'),
			'slug' => $this->get_slug($this->input->post('pages_title'),$this->input->post('Pages_id')),
			'description' => $this->input->post('description'),
			//'active' => $this->input->post('active'),
			'meta_keyword' => $this->input->post('meta_keyword'),
			'meta_description' => $this->input->post('meta_description'),
			
		);
		
		$this->db->where('Pages_id',$this->input->post('Pages_id'));
		//print_r($data); die;
		$this->db->update('pages',$data);
	}	
	function get_one_Pages($id)
	{
		$query = $this->db->get_where('pages',array('pages_id'=>$id));
		return $query->row_array();
	}	
	
	
	function get_total_search_Pages_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		//$option='username';
		//echo $option;die;
		
		$this->db->select('pages.*');
		$this->db->from('pages');
		
		if($option=='page_name')
		{
			//$this->db->like('pages_title',$keyword);
			$en='';
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('pages_title',$val);
					$en.="pages_title like('%$val%') OR ";
					
				}
				$en=substr($en,0,-3);	
			}else{
				$en="pages_title like('%$keyword%')";
			}
			
	$this->db->where('('.$en.')');
		}
		
		
		$this->db->order_by("Pages_id", "desc"); 
		
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->num_rows();
	}

	
	function get_search_Pages_result($option,$keyword,$offset, $limit)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		//$option='username';
		
		$this->db->select('pages.*');
		$this->db->from('pages');
		
		if($option=='page_name')
		{
			//$this->db->like('pages_title',$keyword);
			$en='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('pages_title',$val);
					$en.="pages_title like('%$val%') OR ";
					
				}
				$en=substr($en,0,-3);	
			}else{
				$en="pages_title like('%$keyword%')";
			}
			
	$this->db->where('('.$en.')');
		}
		
		
	
		
		$this->db->order_by("pages_id", "desc"); 
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return 0;
		
		
	}
	
	function get_slug ($str,$id=0)
    {
		$str=str_replace('"','-',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/","+"),'-',trim($str)));
		//echo $str.$id;
   	  $slug = url_title(trim($str), 'dash', true);
		if($id!=0){
			 $query = $this->db->get_where("pages",array('slug'=>$slug,'Pages_id !='=>$id));
		}else{
       		 $query = $this->db->get_where("pages",array('slug'=>$slug));
		}
			if($query->num_rows()>0){
			return $slug.($query->num_rows()+1);
			}else{
				return $slug;
			}
		
	}
  
	
	
	
}
?>