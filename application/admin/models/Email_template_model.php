<?php
class Email_template_model extends CI_Model {
	
    function Email_template_model()
    {
         parent::__construct();	
    }   
	
	
	function email_template_update()
	{
		$data = array(			
			'from_address' => $this->input->post('from_address'),
			'reply_address' => $this->input->post('reply_address'),
			'subject' => $this->input->post('subject'),
			'message' => $this->input->post('message'),
		);
		$this->db->where('email_template_id',$this->input->post('email_template_id'));
		$this->db->update('email_template',$data);
	}		
	
	function get_one_email_template($id)
	{
		if($id==0)
		{
			$id = 1;
		}
		$query = $this->db->get_where('email_template',array('email_template_id'=>$id));
		return $query->row();
	}
	
	function get_email_template($offset, $limit)
	{
		$this->db->order_by('task','asc');
		$query = $this->db->get('email_template',$limit,$offset);
		return $query->result();
	}
	
	function get_email_template_count()
	{
		return $this->db->count_all('email_template');
	}
}
?>