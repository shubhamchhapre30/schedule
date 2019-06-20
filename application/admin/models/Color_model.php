<?php
/**
 * This class declares database related functions, this all functions is used for color functionality.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Color_model extends CI_Model {
	/**
         * This is default constructor of this class.
         * @returns void
         */
    function Color_model()
    {
        parent::__construct();	
    }   
	
	/**
         * It check color code is exist or not.
         * @param  $str
         * @returns boolean
         */
	function color_code_unique($str)
	{
		if($this->input->post('color_id'))
		{
			$query = $this->db->query("select color_code from ".$this->db->dbprefix('colors')." where color_code = '$str' and color_id!='".$this->input->post('color_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select color_code from ".$this->db->dbprefix('colors')." where color_code= '$str' and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
         * It check color name in DB.
         * @param  $str
         * @returns boolean
         */
	function color_name_unique($str)
	{
		if($this->input->post('color_id'))
		{
			$query = $this->db->query("select color_name from ".$this->db->dbprefix('colors')." where color_name = '$str' and color_id!='".$this->input->post('color_id')."' and is_deleted = 0");
		}else{
			$query = $this->db->query("select color_name from ".$this->db->dbprefix('colors')." where color_name= '$str' and is_deleted = 0");
		}
		if($query->num_rows()>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**
         * This function returns all active user list.
         * @returns array|int
         */
	function get_active_users(){
		$query = $this->db->select('user_id')->from('users')->where('user_status','Active')->where('is_deleted','0')->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
		
	}
	
	function get_color_last_seq($user_id){
		$query = $this->db->select('max(seq) as seq')->from('user_colors')->where('user_id',$user_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->seq;
		} else {
			return 0;
		}
	}
	
	function color_insert()
	{
		$data["color_name"] = $this->input->post('color_name');
		$data["color_code"] = $this->input->post('color_code');
		$data["outside_color_code"] = $this->input->post('outside_color_code');
		$data["status"] = $this->input->post('status');
			
		$this->db->insert('colors',$data);
		$id = $this->db->insert_id();
		
		$active_users = $this->get_active_users();
		if($active_users){
			foreach($active_users as $user){
				$user_data = array(
					'user_id' => $user->user_id,
					'color_id' => $id,
					'color_name' => $this->input->post('color_name'),
					'color_code' => $this->input->post('color_code'),
					'outside_color_code' => $this->input->post('outside_color_code'),
					'seq' => $this->get_color_last_seq($user->user_id) + 1,
					'status' => $this->input->post('status'),
					'date_added' => date("Y-m-d H:i:s")
				);
				$this->db->insert('user_colors',$user_data);
			}
		}
	}
	/**
         * This function have same funtionality as color_insert.it get data & update color table with new data.
         * @param int $color_id
         * @returns void
         */
	function color_update($color_id)
	{
		$data["color_name"] = $this->input->post('color_name');
		$data["color_code"] = $this->input->post('color_code');
		$data["outside_color_code"] = $this->input->post('outside_color_code');
		$data["status"] = $this->input->post('status');
	
		$this->db->where('color_id',$color_id);
	    $this->db->update('colors',$data);
		
		
		$active_users = $this->get_active_users();
		if($active_users){
			foreach($active_users as $user){
				$user_data = array(
					'color_name' => $this->input->post('color_name'),
					'color_code' => $this->input->post('color_code'),
					'outside_color_code' => $this->input->post('outside_color_code'),
					'seq' => $this->get_color_last_seq($user->user_id) + 1,
					'status' => $this->input->post('status')
				);
				$this->db->where('user_id',$user->user_id);
				$this->db->where('color_id',$color_id);
				$this->db->update('user_colors',$user_data);
			}
		}
		
		
	}
	/**
         * This function returns single color details from DB.
         * @param int $id
         * @returns array
         */
	function get_one_color($id)
	{
		$this->db->select('*');
		$this->db->from('colors');
		$this->db->where('color_id',$id);
		$query = $this->db->get();
		return $query->row_array();		
	}	
	
	/**
         * This function count & returns total number of color from DB. 
        * @returns int
         */
	function get_total_color_count()
	{
		$this->db->order_by('color_id','asc');
		$this->db->where('colors.is_deleted !=','1');
		$query = $this->db->get('colors');
		
		return $query->num_rows();
		
	}
	/**
         * It will return colors detail from DB.
         * @param int $offset
         * @param int $limit
         * @returns Object|Null
         */
	function get_color_result($offset,$limit)
	{
		$this->db->select('colors.*');
		$this->db->from('colors')
		->order_by('colors.color_id','desc');
		$this->db->where('colors.is_deleted !=','1');
		$query = $this->db->limit($limit, $offset);
		$query = $this->db->get();
		
		
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return '';
		
	}
	
	/**
         * Returns search related data from DB and count it for returns.
         * @param string $option
         * @param string $keyword
         * @returns int
         */
	function get_total_search_color_count($option,$keyword)
	{
		//$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('colors.*');
		$this->db->from('colors');
		$this->db->where('colors.is_deleted !=','1');
		
		if($option=='color_name' && $keyword!='1V1')
		{
			$this->db->like('colors.color_name',$keyword);
		}
		if($option=='color_code' && $keyword!='1V1')
		{
			$this->db->like('colors.color_code',$keyword);
		}
		
		$query = $this->db->get();		
		return $query->num_rows();
	}
	
	
	/**
         * This function get search string data from DB.
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param int $limit
         * @returns Array|string
         */
	function get_search_color_result($option,$keyword,$offset,$limit)
	{
		//$keyword=str_replace('"',' ',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/",'-'),' ',trim($keyword)));
		$this->db->select('colors.*');
		$this->db->from('colors');
		$this->db->where('colors.is_deleted !=','1');
		if($option=='color_name' && $keyword!='1V1')
		{
			$this->db->like('colors.color_name',$keyword);
		}
	
		if($option=='color_code' && $keyword!='1V1')
		{
			$this->db->like('colors.color_code',$keyword);
		}
		$this->db->order_by('colors.color_id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			
			return $query->result();
		}
		return '';
	}
	
}
?>
