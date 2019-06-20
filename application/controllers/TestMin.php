<?php
class TestMin extends SPACULLUS_Controller {

	function __construct()
	{
		parent::__construct();
                ini_set('max_execution_time', 0);
        }

        

public function index()
	{
            $data=array('dashboard.js','calendar-weekview.js','calender-weekview-common.js','common.js','customer.js','footer2.js','main_kanban.js','mykanban.js','settings-general.js','task-general.js','user-settings.js','timesheet.js','maintain.js');
		$this->load->library('minify');
		$this->load->helper('url');
                foreach($data as $one)
                {
                    
                    $r=$this->minify->js($one);
                    $this->minify->deploy_js(FALSE, $one);
                }
				echo 'success';
		//$this->load->view('default/welcome_min');
	}
}