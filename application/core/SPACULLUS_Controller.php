<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
	require_once(APPPATH.'helpers/custom_helper'.EXT);
	require_once(APPPATH.'helpers/task_helper'.EXT);
	
	
class  SPACULLUS_Controller  extends  CI_Controller  {


	
    public function __construct()
	{
		
	 	// get the CI superobject
		$CI =& get_instance();
		parent::__construct();
		
		//echo "IN"; die;
		
		$company_default_format = default_date_format();
		$this->config->set_item('company_default_format', $company_default_format);
		
		$company_flags = get_company_time_flags();
		$this->config->set_item('company_flags', $company_flags);
		
		
		$completed_id = get_task_status_id_by_name("Completed");
		$this->config->set_item('completed_id', $completed_id);
      }
		
	
} 

// END MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */