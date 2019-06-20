<?php

/**
 * This class is used to create login page for admin panel and it's checking authentication of admin, and perform admin forget password and reset password functionality.  
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Home extends CI_Controller {
	 /**
        * It default constuctor which is called when home object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	function Home()
	{
		/*
		* call base class constructor 
		*/
		parent::__construct();	
		/* load database class for home page */
		$this->load->model('home_model');	
	}
	
	 /**
        * It checks whether the user is authenticated or not. if it's authenticated, it will redirect to list_admin page otherwise it will redirect to login page with error message.it take message parameter .
        * @param  $msg 
        * @returns void
        */  
	public function index($msg = '')
	{
		// $str=$_SERVER['SERVER_NAME'];
		// $site_url=site_url();
// 		
		// if (substr($str, 0, 10) != substr($site_url, 0, 10) && substr($str, 0, 11) != substr($site_url, 0, 11))
		// {
			// redirect($site_url);
		// }
		/* check user authentication */
		if(check_admin_authentication())
		{
			redirect('admin/list_Admin');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		$data=array();
		$data['msg'] = $msg; //login fail message

		//$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/common/login',$data,TRUE);
		//this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	//////////==========function check userername and password====
        /**
         * This function is used for set validation rules and check this rules are valided or not than it will show list_admin page.
         * @returns void
         */
	function login()
	{	$data["redirect"]='';
		$data["error"] = "";
                /*
                 * set rules and check it's valided or not.
                 */
			if($_POST)
		{
			$this->form_validation->set_rules('username', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["status"]='fail';
				
		}else{
                    /* check login for admin */
			$login =$this->home_model->check_login();
                        /* this is true means user is authenticated and it will show list_admin page otherwise it show invalid message*/
			if($login == '1')
			{
				//$data["redirect"]=site_url('admin/list_Admin');
				$data["status"]='success';
				redirect('admin/list_Admin');
			}else{
				$data["error"] = '<span>'.INVALID_USERNAME.'</span>';
				$data["status"]='fail';
				redirect("home/index/invalid");
			}
		}
	echo json_encode($data);die;		
		}else{
			redirect("home/index");
		}
	}
	
	/**
         * This function is used for forget password .this function set validation rules and check it true or false, if it true than send mail.
         * @returns void
         */
	function forgotPassword()
	{
		$data["redirect"]='';
		$data["error"] = "";
		$data["status"]='';
		$data['success']='';
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["status"]='fail';
		}
		else
		{
			$chk_mail=$this->home_model->forgot_email();
                        /* check mail send or not*/
			if($chk_mail==0)
			{
				$data['error']='<span>'.EMAIL_NOT_FOUND.'</span>';
				$data["status"]='fail';
			}
			elseif($chk_mail==2)
			{
				$data['error']='<span>'.EMAIL_NOT_FOUND.'</span>';
				$data["status"]='fail';	
			}
			else
			{
				$data['error']='success';	
				$data['success']='<span>'.FORGET_SUCCESS.'</span>';
				$data["status"]='success';
			}
		}
		echo json_encode($data);die;
/*
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
	

		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/common/forgot_password',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		*/
	}
        /**
         * This function is used for create dashboard for admin panel.
         * @param string $msg
         * @returns void
         */
	function dashboard($msg='')
    {
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$data = array();
        $theme = getThemeName();
        $this->template->set_master_template($theme .'/template.php');
		$data['adminRights']=(object)getadminRights();
       
		$data['msg'] = $msg; //login success message
		$offset = 0; $limit =10;
      
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/common/dashboard',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}

	/**
         * This function is used for logout admin.
         * @returns void
         */
	function logout()
	{
				
		$this->session->sess_destroy();
		redirect("home/index/valid");
	}
        /**
         * This function is used for reset password after send mail for forget password.That mail contain resetcode with reset link.And with the help of two function of home_model change password.
         * @param  $code
         * @returns void
         */
	function resetPassword($code='')
	{//echo $code;
		$data["redirect"]='';
		$data["error"] = "";
		$data["status"]='';
		$data['success']='';
		/* check reset code*/
		$admin_id=$this->home_model->checkResetCode($code);
		
		$data['errorfail']=($code=='' || $admin_id=='')?'fail':'';
		$data['admin_id']=$admin_id;
		$data['code']=$code;
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		//print_r($_POST);die;
		if($_POST){
		
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[15]');
		$this->form_validation->set_rules('rpassword', 'Re-type Password', 'required|matches[password]');
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			$data["status"]='fail';
			
		}
		
		else
		{
			$up=$this->home_model->updatePassword();
			
			if($up>0){
				$data["redirect"]=site_url('home/index/ResetSuccess');
				$data["status"]='success';
			}else{
				$data["status"]='fail';
				$data["error"] = '<span>'.PASS_RESET_FAIL.'</span>';
			}
		}
		echo json_encode($data);die;
		}
		//$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/common/resetPassword',$data,TRUE);
		//$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
        /**
         * This function update profile data of login admin in DB.
         * @returns void
         */
	function profile()
	{
            /* check authentication */
		(!check_admin_authentication())? redirect('home/index'):'';
		
		$data['adminRights']=(object)getadminRights();
                /* load form validation library and set validation rules*/
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('emailField', 'Email', 'required|valid_email|callback_adminmail_check');
		//$this->form_validation->set_rules('username', 'User Name', 'required|alpha_numeric|callback_username_check');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');		
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			if($_POST){
			
			$data["email"] = $this->input->post('emailField');
				$data["first_name"] = $this->input->post('first_name');
				$data["last_name"] = $this->input->post('last_name');
			
			}else{
			$oneAdmin=getOneAdmin(get_authenticateadminID());
			//print_r($oneAdmin);die;
			$data["email"] = $oneAdmin->email;
			//$data["username"] = $oneAdmin->username;
			$data["first_name"] = $oneAdmin->first_name;
			$data["last_name"] = $oneAdmin->last_name;
			//$data["address"] = $oneAdmin->address;
			//$data["state"] = $oneAdmin->state;
			//$data["city"] = $oneAdmin->city;
			//$data["zip"] = $oneAdmin->zip;
			//$data["phone_no"] = $oneAdmin->phone_no;
			
			//$data['allState']=get_all_state_by_country_id(231);
			
			}
			
			
		}else{
			
			$res=$this->home_model->updateProfile();
			redirect('home/dashboard/profileUpdateSuccess');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/common/profile',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
		
	}
        /**
         * This function is used for change password of admin by admin authentication.
         * @returns void
         */

	function changePassword()
	{
            /* check admin authentication*/
		(!check_admin_authentication())? redirect('home/index'):'';
		
		$data['adminRights']=(object)getadminRights();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('oldpassword', 'Old Password', 'required|');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[15]');
		$this->form_validation->set_rules('cpassword', 'Confirm password', 'required|matches[password]');
		
		if($this->form_validation->run() == FALSE){			
			if(validation_errors())
			{
				$data["error"] = validation_errors();
			}else{
				$data["error"] = "";
			}
			
			
			
		}else{
			/* call update password method of home_model for change password.*/
			$res=$this->home_model->updateUserPassword();
			if($res){
			redirect('home/dashboard/passwordUpdateSuccess');
			}else{
				$data["error"] = "<p>Please enter valid old password.</p>";
			}
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/common/changePassword',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	function adminmail_check($emailField)
	{
			
			$query = $this->db->query("select email from ".$this->db->dbprefix('admin')." where email = '$emailField' and admin_id!='".get_authenticateadminID()."'");
		
		
		if($query->num_rows() == 0)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('adminmail_check', 'There is an existing account associated with this Email');
			return FALSE;
		}
	}
	
	/*
	* function : GetStateAjax 
    * param:country id
    * des :It is used for get state by ajax.
    */
	function GetStateAjax($con_id=0)
	{
		$state=get_all_state_by_country_id($con_id);
		$str='';
		if($state!=''){
			$str='<option value="">Select State</option>';
			foreach ($state as $s) {
				$str.='<option value="'.$s->state_id.'">'.$s->state_name.'</option>';
			}
		}else{
			$str='<option value="">No State Available</option>';
		}
		echo $str;die;
	}
	
	/*
	* function : GetCityAjax 
    * param:stae id
    * des :It is used for get city by ajax.
    */
	
	function GetCityAjax($con_id=0)
	{
		$state=get_all_city_by_state_id($con_id);
		$str='';
		if($state!=''){
			$str='<option value="">Select City</option>';
			foreach ($state as $s) {
				$str.='<option value="'.$s->city_id.'">'.$s->city_name.'</option>';
			}
		}else{
			$str='<option value="">No City Available</option>';
		}
		echo $str;die;
	}
	
	public function excelRead()
	{
		//load library phpExcel
	//	include_once ( APPPATH."libraries/excel_reader2.php");
   	
		//load excel file
		//$file=base_path()."upload/ingredients/10077user.xls";
		//$data = new Spreadsheet_Excel_Reader($file);
		//load model
		//loop from first data until last data
		/*for($i=2; $i<=77; $i++){
			$name = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
			$address = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
			$data_user = array(
			"name" => $name,
			"username" => $address );
			$this->home_model->add_data($data_user);
		}*/
		
		
		///new code for test//
	$this->load->library('Excel_Reader');
	$file=base_path()."upload/ingredients/10077user.xls";
	$this->excel_reader->read($file);


$worksheet = $this->excel_reader->worksheets[0];

echo "<pre>";
var_dump($worksheet); die;
		// end //
		
		$j = -1;
		echo $data->sheets[0]["numCols"];//we can check number of columns from here
		
		//echo "<pre>";
	//	var_dump($data->sheets[0]["numCols"]);
		
		//die;
		//to check the column names 
		 $data->sheets[0]['cells'][1][1];
		echo $data->sheets[0]['cells'][1][2];
		
	
	    for ($i=2; $i <= ($data->rowcount($sheet_index=0)); $i++){
	      	$j++;
			 $nama[$j]   = $data->val($i, 1);
		      $nim[$j]    = $data->val($i, 2);
			   $nim1[$j] = $data->val($i, 3);
			    $nim2[$j] = $data->val($i, 4);
   		 }
     
	    $xdata['name']  = $nama;
	    $xdata['address']  = $nim;
		$xdata['address1']  = $nim1;
		$xdata['address2']  = $nim2;
		echo "<pre>";print_r($xdata['address2']);exit;
	}
	
	
	function new_test()
	{
		$this->load->library('excel_reader');
		$file=base_path()."upload/ingredients/10077user.xls";
		// Read the spreadsheet via a relative path to the document
		// for example $this->excel_reader->read('./uploads/file.xls');
		$this->excel_reader->read($file);
		
		// Get the contents of the first worksheet
		$worksheet = $this->excel_reader->sheets[0];
		echo "<pre>";print_r($worksheet);exit;
		$numRows = $worksheet['numRows']; // ex: 14
		$numCols = $worksheet['numCols']; // ex: 4
		$cells = $worksheet['cells'];
	}
   
}

?>
