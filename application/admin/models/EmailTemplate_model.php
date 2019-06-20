<?php
/**
 * This class declares database related functions for emailtemplate, this all functions is used for database interation.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class EmailTemplate_model extends CI_Model {
	/**
         * It is class construtor.it call parent method.
         * @returns void
         */
    function EmailTemplate_model()
    {
        /* call parent method*/
        parent::__construct();	
    }   
    /**
     * This function returns total no. of template .
     * @returns int
     */
	function get_total_EmailTemplate_count()
	{
		return $this->db->count_all('email_template');
	}
	/**
         * This function returns data of EmailTemplate from DB in array form.
         * @param int $offset
         * @param int $limit
         * @returns int|array
         */
	
	function get_EmailTemplate_result($offset, $limit)
	{
			$this->db->order_by('email_template_id','desc');
		$query = $this->db->get('email_template',$limit,$offset);

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return 0;
	}
        /**
         * This function functionality is committed.
         */
	function EmailTemplate_insert()
	{
		$data = array(			
			'from_address' => $this->input->post('from_address'),
			'reply_address' => $this->input->post('reply_address'),
			'subject' => $this->input->post('subject'),
			'message' => $this->input->post('message'),
		);
		$this->db->insert('email_template',$data);
	}	

	 /**
         * This function update EmailTemplate details in DB.
         */
	function EmailTemplate_update()
	{
		$data = array(			
			'from_address' => $this->input->post('from_address'),
			'reply_address' => $this->input->post('reply_address'),
			'subject' => $this->input->post('subject'),
			'message' => $this->input->post('message'),
		);
		$this->db->where('email_template_id',$this->input->post('EmailTemplate_id'));
		$this->db->update('email_template',$data);
	}	
        /**
         * This function returns specific template details from DB.
         * @param int $id
         * @returns array
         */
	function get_one_EmailTemplate($id)
	{
		$query = $this->db->get_where('email_template',array('email_template_id'=>$id));
		return $query->row_array();
	}	
	
	/**
         * This function functionality is committed.
         */
	function get_total_search_EmailTemplate_count($option,$keyword)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		//$option='username';
		//echo $option;die;
		
		$this->db->select('email_template.*');
		$this->db->from('email_template');
		
		if($option=='page_name')
		{
			//$this->db->like('EmailTemplate_title',$keyword);
			$en='';
			
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('EmailTemplate_title',$val);
					$en.="EmailTemplate_title like('%$val%') OR ";
					
				}
				$en=substr($en,0,-3);	
			}else{
				$en="EmailTemplate_title like('%$keyword%')";
			}
			
	$this->db->where('('.$en.')');
		}
		
		
		$this->db->order_by("EmailTemplate_id", "desc"); 
		
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->num_rows();
	}

	/**
         * This function functionality is committed.
         */
	function get_search_EmailTemplate_result($option,$keyword,$offset, $limit)
	{
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		$keyword=str_replace('-',' ',$keyword);
		
		//$option='username';
		
		$this->db->select('email_template.*');
		$this->db->from('email_template');
		
		if($option=='page_name')
		{
			//$this->db->like('EmailTemplate_title',$keyword);
			$en='';
			if(substr_count($keyword,' ')>=1)
			{
				$ex=explode(' ',$keyword);
				
				foreach($ex as $val)
				{
					//$this->db->like('EmailTemplate_title',$val);
					$en.="task like('%$val%') OR ";
					
				}
				$en=substr($en,0,-3);	
			}else{
				$en="task like('%$keyword%')";
			}
			
	$this->db->where('('.$en.')');
		}
		
		
	
		
		$this->db->order_by("email_template_id", "desc"); 
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return 0;
		
		
	}
	
	
	
}
?>
