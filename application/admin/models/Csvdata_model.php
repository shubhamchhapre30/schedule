<?php

class Csvdata_model extends CI_Model {
	
    function Csvdata_model()
    {
        parent::__construct();	
    }   
	
	
	function get_total_csvdata_count()
	{
		$qry = $this->db->query("select count(enroll_id) from ".$this->db->dbprefix("enroll_csvdata")." where 1=1");
		
		return $qry->num_rows();
	}
	
	function get_csvdata_result($offset = 0, $limit = 0)
	{
		 $qry = $this->db->query("select * from ".$this->db->dbprefix("enroll_csvdata")." where 1=1 limit ".$limit." offset ".$offset."");
		 
		 if($qry->num_rows()>0)
		 {
		 	return $qry->result();
		 }
		 
		 return 0;
	}
	
	
	function get_total_search_csvdata_count($option,$keyword)
	{
		$file_cond = ' 1=1';	
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		if($option=='file_name' && $keyword!='1V1')
		{
			 $file_cond =  " file_name like '".$keyword."%'";
		}	
		$qry = $this->db->query("select count(enroll_id) from ".$this->db->dbprefix("enroll_csvdata")." where 1=1 and ".$file_cond."");
		
		return $qry->num_rows();
	}
	
	
	function get_search_csvdata_result($option,$keyword,$offset, $limit)
	{
		$file_cond = ' 1=1';	
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
		
		if($option=='file_name' && $keyword!='1V1')
		{
			 $file_cond =  " file_name like '".$keyword."%'";
		}	
		
		$qry = $this->db->query("select * from ".$this->db->dbprefix("enroll_csvdata")." where 1=1 and ".$file_cond." limit ".$limit." offset ".$offset."");
		 
		
		 if($qry->num_rows()>0)
		 {
		 	return $qry->result();
		 }
		 
		 return 0;
	}
}	