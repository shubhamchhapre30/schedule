<?php
class Image_setting_model extends CI_Model {
	
    function Image_setting_model()
    {
        parent::__construct();	
    }   
	

	function image_setting_update()
	{
				
		
		$data = array(	
			'user_width'=> $this->input->post('user_width'),
			'user_height'=>$this->input->post('user_height'),
			'image_width'=> $this->input->post('image_width'),
			'image_height'=> $this->input->post('image_height'),
			'album_image_width'=> $this->input->post('album_image_width'),
			'album_image_height'=> $this->input->post('album_image_height'),	
		);
		//print_r($data);// die();
		
		$this->db->where('image_setting_id',$this->input->post('image_setting_id'));
		$this->db->update('image_setting',$data);
	
	}
	
	

}
?>