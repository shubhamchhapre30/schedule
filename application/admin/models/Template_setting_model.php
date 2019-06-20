<?php

class Template_setting_model extends CI_Model {
	
    function Template_setting_model()
    {
        parent::__construct();	
    }   
	
	/*** get template list details
	*  return multiple record array
	**/
	function get_all_template()
	{
		$this->db->order_by('template_id','asc');
		$query = $this->db->get('template_manager');
		return $query->result();
	}
	
	
	/** admin template setting update function
	* var integer $is_admin_template
	* var integer $active_template
	* var string $template_logo
	* var string $template_logo_hover
	* var integer $template_id
	**/
	function template_setting_update()
	{
	
		
		$logo_image='';
		$logo_hover_image='';
         
		 
         if($_FILES['template_logo']['name']!='')
         {
             $this->load->library('upload');
             $rand=rand(0,100000); 
			  
             $_FILES['userfile']['name']     =   $_FILES['template_logo']['name'];
             $_FILES['userfile']['type']     =   $_FILES['template_logo']['type'];
             $_FILES['userfile']['tmp_name'] =   $_FILES['template_logo']['tmp_name'];
             $_FILES['userfile']['error']    =   $_FILES['template_logo']['error'];
             $_FILES['userfile']['size']     =   $_FILES['template_logo']['size'];
  
             

			 
		     $config['file_name']     = $rand.'logo';
             $config['upload_path'] = base_path().$this->input->post('template_name').'/images/logo/';
             $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 
             $this->upload->initialize($config);
 
              if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();   
			  } 
			   
			   
           	  $picture = $this->upload->data();

			  $logo_image=$picture['file_name'];

			
			if($this->input->post('prev_logo_image')!='')
				{
					if(file_exists(base_path().$this->input->post('template_name').'/images/logo/'.$this->input->post('prev_logo_image')))
					{
						$link=base_path().$this->input->post('template_name').'/images/logo/'.$this->input->post('prev_logo_image');
						unlink($link);
					}
					
				}
			} else {
				if($this->input->post('prev_logo_image')!='')
				{
					$category_image=$this->input->post('prev_logo_image');
				}
			}
			
		
		 if($_FILES['template_logo_hover']['name']!='')
         {
             $this->load->library('upload');
             $rand=rand(0,100000); 
			  
             $_FILES['userfile']['name']     =   $_FILES['template_logo_hover']['name'];
             $_FILES['userfile']['type']     =   $_FILES['template_logo_hover']['type'];
             $_FILES['userfile']['tmp_name'] =   $_FILES['template_logo_hover']['tmp_name'];
             $_FILES['userfile']['error']    =   $_FILES['template_logo_hover']['error'];
             $_FILES['userfile']['size']     =   $_FILES['template_logo_hover']['size'];
  
             

			 
		     $config['file_name']     = $rand.'logohover';
             $config['upload_path'] = base_path().$this->input->post('template_name').'/images/logo/';
             $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';  
 
             $this->upload->initialize($config);
 
              if (!$this->upload->do_upload())
			  {
				$error =  $this->upload->display_errors();   
			  } 
			   
			   
           	  $picture = $this->upload->data();

			  $logo_hover_image=$picture['file_name'];

			
			if($this->input->post('prev_logo_hover_image')!='')
				{
					if(file_exists(base_path().$this->input->post('template_name').'/images/logo/'.$this->input->post('prev_logo_hover_image')))
					{
						$link=base_path().$this->input->post('template_name').'/images/logo/'.$this->input->post('prev_logo_hover_image');
						unlink($link);
					}
					
				}
			} else {
				if($this->input->post('prev_logo_hover_image')!='')
				{
					$logo_hover_image=$this->input->post('prev_logo_hover_image');
				}
			}
	
	
		$data = array(			
			'template_logo' => $logo_image,
			'template_logo_hover' => $logo_hover_image,
			'active_template' => $this->input->post('active_template'),
			
		);
		$this->db->where('template_id',$this->input->post('template_id'));
		$this->db->update('template_manager',$data);
	}
	
	/*** get Template setting details
	* var integer $id
	*  return single record array
	**/
	function get_one_template_setting($id)
	{
		$query = $this->db->get_where('template_manager',array('template_id'=>$id));
		return $query->row();
	}
}
?>