<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// --------------------------------------------------------------------
	/**
	 * Site Base Path
	 * @access	public
	 * @param	string	the Base Path string
	 * @return	string
	 */
function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
    $reference_array = array();
	//pr($array);echo $column;die;
    foreach($array as $key => $row) {
        $reference_array[$key] = $row[$column];
    }
	//pr($reference_array);
    array_multisort($reference_array, $direction, $array);
}

function array_sort($array, $on, $order=SORT_ASC){

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}
	 function base_path()
	{
		$CI =& get_instance();

		return $base_path = $CI->config->slash_item('base_path');
	}

	function getUserID()
	{
		$CI =& get_instance();

		if($CI->session->userdata('user_id')!='')
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// --------------------------------------------------------------------

	/**
	 * Site Front Url
	 *
	 * @access	public
	 * @param	string	the Front Url string
	 * @return	string
	 */

	function front_base_url()
	{
		$CI =& get_instance();
		return $base_path = $CI->config->slash_item('base_url_site');
	}

	function base_url_admin()
	{
		$CI =& get_instance();
		return $base_path = $CI->config->slash_item('base_url_admin');
	}
	// --------------------------------------------------------------------
	/**
	 * Site Front ActiveTemplate
	 *
	 * @access	public
	 * @param	string	current theme folder name
	 * @return	string
	 */

	function checkUnreadforUser($id)
	{
		$CI =& get_instance();

		$query=$CI->db->get_where('inquiry',array('read_by_user'=>'0','reply_inquiry_id'=>$id));

		if($query->num_rows()>0)
		{
			return $query->num_rows();
		}else{
			return 0;
		}

	}

	/**
    * function :getExtension
    * return the actrntion of the file
    * Author : Sanjay Amin
    */
   	function getExtension($str)
	{
	         $i = strrpos($str,".");
	         if (!$i) { return ""; }

	         $l = strlen($str) - $i;
	         $ext = substr($str,$i+1,$l);
	         return $ext;
	}


	function getThemeName()
	{
		return $default_theme_name='default';
		die;
		$CI =& get_instance();

		$supported_cache=check_supported_cache_driver();

		if(isset($supported_cache))
		{
			if($supported_cache!='' && $supported_cache!='none')
			{
				////===load cache driver===
				$CI->load->driver('cache');

				if($CI->cache->$supported_cache->get('front_theme_name'))
				{
					$theme_name = $CI->cache->$supported_cache->get('front_theme_name');
				}
				else
				{
					$query = $CI->db->get_where("template_manager",array('active_template'=>1 ,'is_admin_template'=>0));
					$row = $query->row();
					$theme_name=trim($row->template_name);

					$CI->cache->$supported_cache->save('front_theme_name', $theme_name,CACHE_VALID_SEC);
				}

			}

			else
			{
				$query = $CI->db->get_where("template_manager",array('active_template'=>1 ,'is_admin_template'=>0));
				$row = $query->row();
				$theme_name=trim($row->template_name);
			}
		}
		else
		{
			$query = $CI->db->get_where("template_manager",array('active_template'=>1 ,'is_admin_template'=>0));
			$row = $query->row();
			$theme_name=trim($row->template_name);
		}
		//////////====end cache part


		if(is_dir(APPPATH.'views/'.$theme_name))
		{
			return $theme_name;
		}
		else
		{
			return $default_theme_name;
		}
	}
	/**N**/
        /**
         * This function returns timezones list from DB.
         * @returns array
         */
	function get_timezone()
	{
		$CI =& get_instance();
		$query = $CI->db->select("*")->from("timezone")->order_by("timezone_id","asc")->get();
		return $query->result();

	}

	/* Function for get state name */
        /**
         * It get state names by id from DB.
         * @param int $id
         * @returns array
         */
	function get_state_name_by_id($id)
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("state_master",array("state_id"=>$id));
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}
	/* Function for get state name end */

	/* Function for get city name */
        /**
         * This function returns city name by id.
         * @param int $id
         * @returns array
         */
	function get_city_name_by_id($id)
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("city_master",array("city_id"=>$id));
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}


	/**** get dynamic logo of current theme
	* return array
	***/
        /**
         * It get logo of current theme.
         * @returns array|int
         */
	function logo_image()
	{

		$CI =& get_instance();
		$query = $CI->db->get_where("template_manager",array('active_template'=>1 ,'is_admin_template'=>0));

		if($query->num_rows()>0)
		{
			return $query->row();
		}
		return 0;


	}

	// --------------------------------------------------------------------

	/**
	 * Check user login
	 *
	 * @return	boolen
	 */
        /**
         * It check user authentication with input credentials.
         * @return boolean
         */
	function check_user_authentication()
	{
		$CI =& get_instance();

			if($CI->session->userdata('user_id')!='')
			{
				return true;
			}
			else
			{
				return false;
			}

	}

	// --------------------------------------------------------------------

	/**
	 * get login user id
	 *
	 * @return	integer
	 */
	/**
         * It returns authenticatd user id.
         * @returns int
         */
	function get_authenticateUserID()
	{
		$CI =& get_instance();
		return $CI->session->userdata('user_id');
	}
	function get_authenticateType()
	{
		$CI =& get_instance();
		return $CI->session->userdata('user_type');
	}
        /**
         * It get user details from DB.
         * @returns array
         */
	function get_user_inform()
	{

		$CI =& get_instance();
		$CI->db->select('user_id,first_name,last_name,profile_image');
		$CI->db->from('users');
		$CI->db->where('user_id',get_authenticateUserID());
                $query = $CI->db->get();
		//echo $CI->db->last_query();
		return $query->result();

	}

        /**
         * It returns user info from DB.
         * @param int $user_id
         * @returns Array|String
         */
	function get_user_info($user_id='')
	{
		$CI =& get_instance();
		$CI->db->select('u.*');
		$CI->db->from('users u');
		$CI->db->where(array('user_id'=>$user_id));
		$query = $CI->db->get();

		if($query->num_rows()>0)
		{
			return $query->row();
		}
		else {
			return '';
		}
	}

	/**
	 * get site visitor ip address
	 *
	 * @return	integer
	 */



	function email_send_old($email_address_from,$email_address_reply,$email_to,$email_subject,$str,$attach='')
	{

		$CI =& get_instance();
		$query = $CI->db->get_where("email_setting",array('email_setting_id'=>1));
		$email_set=$query->row();


		$CI->load->library(array('email'));

		///////====smtp====

		if($email_set->mailer=='smtp')
		{

			$config['protocol']='smtp';
			$config['smtp_host']=trim($email_set->smtp_host);
			$config['smtp_port']=trim($email_set->smtp_port);
			$config['smtp_timeout']='30';
			$config['smtp_user']=trim($email_set->smtp_email);
			$config['smtp_pass']=trim($email_set->smtp_password);

		}

		/////=====sendmail======

		elseif(	$email_set->mailer=='sendmail')
		{

			$config['protocol'] = 'sendmail';
			$config['mailpath'] = trim($email_set->sendmail_path);

		}

		/////=====php mail default======

		else
		{

		}


		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$config['crlf'] = '\n\n';
		$config['newline'] = '\n\n';
		$emails = array(SCHEDULLO_EMAILDEBUG,$email_to);
		$CI->email->initialize($config);


		$CI->email->from($email_address_from,"Schedullo Team");
		$CI->email->reply_to($email_address_reply);
		//$CI->email->to($email_to);
		$CI->email->to($emails);
		$CI->email->subject($email_subject);
		if($attach!='')
		{
			$CI->email->attach($attach);
		}
		$CI->email->message($str);
		$CI->email->send();

	}
        /**
         * This custom function is used for send mail.It send mail using PHPMailer class.
         * @param  $email_address_from  sender mail id
         * @param  $email_address_reply sender mail id
         * @param  $email_to reciver mail id
         * @param  $email_subject mail subject
         * @param  $str mail message
         * @param  $attach attachment
         * @returns void
         */
	
	function email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str,$attach=''){
		
		$CI =& get_instance();
		$query = $CI->db->get_where("email_setting",array('email_setting_id'=>1));
		$email_set=$query->row();
		
		$CI->load->library('My_PHPMailer');
		
		//SMTP Settings
		$mail = new PHPMailer();
		
		$mail->IsSMTP();
		$mail->SMTPAuth   = true; 
		$mail->SMTPSecure = "tls"; 
		$mail->Host       = trim($email_set->smtp_host);
		$mail->Username   = trim($email_set->smtp_email);
		$mail->Password   = trim($email_set->smtp_password);
		$mail->Port = trim($email_set->smtp_port);
		
		
		$mail->SetFrom($email_address_from, "Schedullo Team"); //from (verified email address)
		$mail->AddReplyTo($email_address_reply,"Schedullo Team");
		$mail->Subject = $email_subject; //subject
		
		//message
		$body = $str;
		$mail->MsgHTML($body);
		
		//recipient
		$mail->AddAddress($email_to);
		$mail->AddAddress(SCHEDULLO_EMAILDEBUG);
		
		if($attach!='')
		{
			$mail->AddAttachment($attach);
		}
		
		//debug
		//$mail->SMTPDebug = 0;
		//Success
		$mail->Send();
	}




	/**
	 * generate random code
	 *
	 * @return	string
	 */

	function randomCode()
	{
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array();

		for ($i = 0; $i < 12; $i++) {
		$n = rand(0, strlen($alphabet)-1); //use strlen instead of count
		$pass[$i] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}


	/*** load site setting
	*  return single record array
	**/

	/*function site_setting()
	{
		$CI =& get_instance();

		$supported_cache=check_supported_cache_driver();

		if(isset($supported_cache))
		{
			if($supported_cache!='' && $supported_cache!='none')
			{
				////===load cache driver===
				$CI->load->driver('cache');

				if($CI->cache->$supported_cache->get('site_setting'))
				{
					return  (object)$CI->cache->$supported_cache->get('site_setting');
				}
				else
				{
					$query = $CI->db->get("site_setting");
					$CI->cache->$supported_cache->save('site_setting', $query->row(),CACHE_VALID_SEC);
					return $query->row();
				}
			}
			else
			{
				$query = $CI->db->get("site_setting");
				return $query->row();
			}
		}
		else
		{
			$query = $CI->db->get("site_setting");
			return $query->row();
		}
		//////////====end cache part
	}*/
        /**
         * It returns site related data fron DB.
         * @returns Array 
         */
    function site_setting()
	{
		$CI =& get_instance();
		$query = $CI->db->get("site_setting");
		return $query->row();

	}


	/*** load meta setting
	*  return single record array
	**/

	function meta_setting()
	{
		$CI =& get_instance();

		//$supported_cache=check_supported_cache_driver();

		if(isset($supported_cache))
		{
			if($supported_cache!='' && $supported_cache!='none')
			{
				////===load cache driver===
				$CI->load->driver('cache');

				if($CI->cache->$supported_cache->get('meta_setting'))
				{
					return (object)$CI->cache->$supported_cache->get('meta_setting');
				}
				else
				{
					$query = $CI->db->get("meta_setting");
					$CI->cache->$supported_cache->save('meta_setting', $query->row(),CACHE_VALID_SEC);
					return $query->row();
				}
			}
			else
			{
				$query = $CI->db->get("meta_setting");
				return $query->row();
			}
		}
		else
		{
			$query = $CI->db->get("meta_setting");
			return $query->row();
		}
		//////////====end cache part
	}

	/*** load image setting
	*  return single record array
	**/

	function image_setting()
	{
		$CI =& get_instance();
		$query = $CI->db->get("image_setting");
		return $query->row();
	}

	/*** load facebook setting
	*  return single record array
	**/

	function facebook_setting()
	{
		$CI =& get_instance();
		$query = $CI->db->get("facebook_setting");
		return $query->row();
	}

	/*** load twitter setting
	*  return single record array
	**/

	function twitter_setting()
	{
		$CI =& get_instance();
		$query = $CI->db->get("twitter_setting");
		return $query->row();
	}


  	/****  create seo friendly url
	* var string $text
	**/

  	function clean_url($text)
	{
		$text=strtolower($text);
		$code_entities_match = array( '&quot;' ,'!' ,'@' ,'#' ,'$' ,'%' ,'^' ,'&' ,'*' ,'(' ,')' ,'+' ,'{' ,'}' ,'|' ,':' ,'"' ,'<' ,'>' ,'?' ,'[' ,']' ,'' ,';' ,"'" ,',' ,'.' ,'_' ,'/' ,'*' ,'+' ,'~' ,'`' ,'=' ,' ' ,'---' ,'--','--','ï¿½');
		$code_entities_replace = array('' ,'-' ,'-' ,'' ,'' ,'' ,'-' ,'-' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'-' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'-' ,'' ,'-' ,'-' ,'' ,'' ,'' ,'' ,'' ,'-' ,'-' ,'-','-');
		$text = str_replace($code_entities_match, $code_entities_replace, $text);
		return $text;
	}

   function getDuration($date)
   {

		$CI =& get_instance();

	   $curdate = date('Y-m-d H:i:s');



           $diff = abs(strtotime(str_replace(array("/"," ",","), "-", $date)) - strtotime($curdate));
           $years = floor($diff / (365*60*60*24));
           $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
           $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
           $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 )/ (60*60));
           $mins = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ (60));

           $ago = '';
           if($years != 0){ if($years > 1) {$ago =  $years.' years';} else { $ago =  $years.' year';}}
           elseif($months != 0){ if($months > 1) {$ago =  $months.' months';} else { $ago =  $months.' month';}}
           elseif($days != 0) { if($days > 1) {$ago =  $days.' days';} else { $ago =  $days.' day';}}
           elseif($hours != 0){ if($hours > 1) {$ago =  $hours.' hours';} else { $ago =  $hours.' hour';}}
           else{ if($mins > 1) {$ago =  $mins.' minutes';} else { $ago =  $mins.' minute';}}
           return $ago.' ago';
   }

	/********offset to timezone
	***  var $offset
	** return string timezone name
	***/

	function tzOffsetToName($offset, $isDst = null)
    {
        if ($isDst === null)
        {
            $isDst = date('I');
        }
        $offset *= 3600;
        $zone    = timezone_name_from_abbr('', $offset, $isDst);

        if ($zone === false)
        {
            foreach (timezone_abbreviations_list() as $abbr)
            {
                foreach ($abbr as $city)
                {
                    if ((bool)$city['dst'] === (bool)$isDst &&
                        strlen($city['timezone_id']) > 0    &&
                        $city['offset'] == $offset)
                    {
                        $zone = $city['timezone_id'];
                        break;
                    }
                }

                if ($zone !== false)
                {
                    break;
                }
            }
        }
        return $zone;
    }

	function get_all_state()
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("state_master");
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}

        /**
         * It get all country name from DB and returns in array form.
         * @returns array
         */

	function get_all_country()
	{
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('country_master');
		$CI->db->where('status','Active');
		$CI->db->order_by('country_name','asc');
		$query = $CI->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}


	/*** get user name
	*  return string username
	**/

	/**
        * It get user name from DB.
	*  returns string username
	*/
        
	function get_user_name($id){
	$CI =& get_instance();

			$CI->db->select('first_name,last_name,profile_image');
			$CI->db->where('user_id',$id);
			$query=$CI->db->get('users');
			if($query->num_rows()>0){
			return  $query->row();
		}else{
			return '';
		}


	}

	function usernameById($id){
		$CI =& get_instance();
		$CI->db->select('first_name,last_name');
		$CI->db->where('user_id',$id);
		$query=$CI->db->get('users');
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->first_name.' '.$res->last_name;
		}else{
			return '';
		}
	}
        /**
         * It returns division list 
         * @param int $user_id
         * @returns string
         */
	function get_user_division($user_id){
		$CI =& get_instance();
		$CI->db->select('cd.devision_title');
		$CI->db->from('user_devision ud');
		$CI->db->join('company_divisions cd','cd.division_id = ud.devision_id');
		$CI->db->where('cd.devision_status','Active');
		$CI->db->where('cd.is_delete','0');
		$CI->db->where('ud.user_id',$user_id);
		$CI->db->where('cd.is_delete','0');
		$CI->db->where('cd.devision_status','Active');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			$array_text ='';
			foreach($res as $arr){
				$array_text .= $arr['devision_title'].',';
			}
			return substr($array_text, 0,-1);
		} else {
			return '';
		}
	}
        /**
         * It returns departments list 
         * @param int $user_id
         * @returns string
         */
	function get_user_department($user_id){
		$CI =& get_instance();
		$CI->db->select('cd.department_title');
		$CI->db->from('user_department ud');
		$CI->db->join('company_departments cd','cd.department_id = ud.dept_id');
		$CI->db->where('ud.user_id',$user_id);
		$CI->db->where('cd.status','Active');
		$CI->db->where('cd.is_deleted','0');

		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			$array_text ='';
			foreach($res as $arr){
				$array_text .= $arr['department_title'].',';
			}
			return substr($array_text, 0,-1);
		} else {
			return '';
		}
	}

	function set_currency($amt=0){
		$site_setting=site_setting();
		return $site_setting->site_currency.' '.number_format($amt,2);

	}


	function pr($x='')
	{
		echo '<pre>';
		print_r($x);
		echo '</pre>';
	}
        /**
         * It get active country list from DB.
         * @returns string
         */
	function getActiveCountry()
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("country_master",array('status'=>'Active'));
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}



	function get_all_city_by_state_id($id=0)
	{
		$CI =& get_instance();
		//$query = $CI->db->get_where("state_master",array('status'=>'active','country_id'=>$id));
		$CI->db->where(array('status'=>'active','state_id'=>$id));
		$CI->db->order_by('city_name','asc');
		$query = $CI->db->get('city_master');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return '';
		}
	}

	function get_single_admin_detail($admin_id){
		$CI =& get_instance();
		$CI->db->where("admin_id",$admin_id);
		$query = $CI->db->get("admin");

		if($query->num_rows() > 0){
			$res = $query->row();
			return $res;
		}else{
			return 0;
		}
	}

	function get_single_admin_byemail($email){
		$CI =& get_instance();
		$CI->db->where("email",$email);
		$query = $CI->db->get("admin");

		if($query->num_rows() > 0){
			$res = $query->row();
			return $res;
		}else{
			return 0;
		}
	}

	function validate_image($image = NULL)
	{
        $file_name      =   $image['name'];
        $allowed_ext    =   array('jpg', 'jpeg', 'png', 'gif', 'bmp');
        $ext                =   strtolower(end(explode('.', $file_name)));
        $allowed_file_types =   array('image/jpeg','image/jpg','image/gif','image/png');
        $file_type              =   $image['type'];
        if(!in_array($ext, $allowed_ext) && !in_array($file_type, $allowed_file_types)) {

            return '<span>This file type is not allowed</span>';
        }else{
        	return '';
        }
    }

	/*
	 * function : check_user_right
	 * check rigths of the user
	 * return true or false
	 * author : Spaculus
	 */
	function check_user_right($right_name = '')
	{
		$CI =& get_instance();

		$qry = $CI->db->get_where("user_rights",array("user_id"=>get_authenticateUserID(),$right_name => "1"));

		if($qry->num_rows()>0)
		{
			return TRUE;
		}

		return FALSE;
	}




/* End of file custom_helper.php */
/* Location: ./system/application/helpers/custom_helper.php */

	/*========================New Function==========================*/
	function check_front_language()
	{

		$CI =& get_instance();

			if($CI->session->userdata('language')!='')
			{
				return $CI->session->userdata('language');
			}
			else
			{
				$CI->session->set_userdata('language','english');
				return 'english';

			}

	}


	function getlastdate($cat_id=0, $pro_id=0,$today_date='')
	{
		$CI =& get_instance();
		$CI->db->select('mc.date_from');
		$CI->db->from('menucomposition mc');
		$CI->db->where('mc.is_deleted',0);

		if($cat_id > 0){
			$CI->db->where('mc.category_id',$cat_id);
		}

		if($pro_id > 0){
			$CI->db->where('mc.product_type',$pro_id);
		}

		if($today_date !=''){ echo $today_date;
			$CI->db->where('mc.date_from <=',$today_date);
		} else {
			$CI->db->where('mc.date_from <=',date('Y-m-d'));
		}
		$CI->db->order_by('mc.date_from','desc');
		$CI->db->limit(1);
		$query = $CI->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		} else {
			return 0;
		}
	}


	function getOneRecord($table,$id,$val,$where=''){

		$CI =& get_instance();
		if($where != ''){ $CI->db->where($where);}


		if($CI->session->userdata('language')=='english'){
			$query = $CI->db->get_where($table,array($id=>$val));
		}
		else{
			$query = $CI->db->get_where($table."_es",array($id=>$val));
		}

		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return 0;
		}
	}

	function getOneRecordTable($table,$id,$val,$where=''){

		$CI =& get_instance();
		if($where != ''){ $CI->db->where($where);}

		$query = $CI->db->get_where($table,array($id=>$val));


		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return 0;
		}
	}

	////Twitter Feeds/////
	
	function payment_setting()
	{		
		$CI =& get_instance();
		$query = $CI->db->get("payment_setting");
		return $query->row();
	
	}

	function get_email($company_id){
		$CI =& get_instance();
		$CI->db->select('company_email');
		$CI->db->from('company');
		$CI->db->where('company_id',$company_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->company_email;
		}
	}

	function getActiveCompany()
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("company",array('status'=>'Active','is_deleted'=>'0'));
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}

	function count_user_by_company($id)
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(user_id) as TOTAL')->where("company_id",$id)->where("user_status","Active")->where("is_deleted","0")->where('is_customer_user','0')->get('users')->row()->TOTAL;
	}

	function get_default_tasks(){
		$CI =& get_instance();
		$query = $CI->db->get_where("task_default_status",array('task_status_flag'=>'Active'));
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}

	function get_max_task($company_id){
		$CI =& get_instance();
		$CI->db->select('total_task_status');
		$CI->db->from('company');
		$CI->db->where('company_id',$company_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->total_task_status;
		} else {
			return 0;
		}
	}

	function get_inserted_task($id)
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(task_status_id) as TOTAL')->where("company_id",$id)->get('task_status')->row()->TOTAL;
	}
	
	function getUserDivision($user_id){
		$CI =& get_instance();
		$CI->db->select('cd.*');
		$CI->db->from('user_devision ud');
		$CI->db->join('company_divisions cd','cd.division_id = ud.devision_id');
		$CI->db->where('ud.user_id',$user_id);
		$CI->db->where('cd.is_delete','0');
		$CI->db->where('cd.devision_status','Active');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	function getUserDepartment($user_id){
		$CI =& get_instance();
		$CI->db->select('cd.*');
		$CI->db->from('user_department ud');
		$CI->db->join('company_departments cd','cd.department_id = ud.dept_id');
		$CI->db->where('ud.user_id',$user_id);
		$CI->db->where('cd.is_deleted','0');
		$CI->db->where('cd.status','Active');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_company_division($company_id, $type=''){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('company_divisions');
		$CI->db->where('company_id',$company_id);
		$CI->db->where('is_delete','0');
		if($type == 'Inactive'){
			$CI->db->where('devision_status','Inactive');
		} else if($type == 'Active'){
			$CI->db->where('devision_status','Active');
		} else {

		}
                $CI->db->order_by("seq","asc");
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_company_department($company_id, $type='', $division_id=''){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('company_departments');
		$CI->db->where('company_id',$company_id);
		$CI->db->where('is_deleted','0');
		if($type == 'Inactive'){
			$CI->db->where('status','Inactive');
		} else if($type == 'Active'){
			$CI->db->where('status','Active');
		} else {

		}
		if($division_id){
			$CI->db->where('deivision_id',$division_id);
		}
                $CI->db->order_by("department_seq","asc");
		$query = $CI->db->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /**
         * It get company detail via company id.
         * @param int $company_id
         * @returns array|int
         */
	
	function get_one_company($company_id){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('company');
		$CI->db->where('company_id',$company_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}
        /**
         * This function returns calender setting of company.
         * @param int $company_id
         * @returns int
         */
	function get_calender_settings($company_id){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('default_calendar_setting');
		$CI->db->where('comapny_id',$company_id);
		$query = $CI->db->get();
		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}

	function get_calender_settings_by_user($user_id){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('default_calendar_setting');
		$CI->db->where('user_id',$user_id);
		$query = $CI->db->get();

		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}

        /**
         * It returns list of company skills from DB.
         * @param int $company_id
         * @param string $type
         * @returns array|int
         */
	function get_company_skills($company_id,$type=''){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('skills');
		$CI->db->where('company_id',$company_id);
		if($type == 'Active'){
			$CI->db->where('skill_status','Active');
		} elseif($type == 'Inactive'){
			$CI->db->where('skill_status','Inactive');
		} else {

		}
		$CI->db->where('is_deleted','0');
                $CI->db->order_by("skill_seq","asc");
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
 /**
         * It returns list of company stafflevels from DB.
         * @param int $company_id
         * @param string $type
         * @returns array|int
         */
	function get_company_staffLevels($company_id,$type=''){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('staff_levels');
		$CI->db->where('company_id',$company_id);
		if($type == 'Active'){
			$CI->db->where('staff_level_status','Active');
		} elseif($type == 'Inactive'){
			$CI->db->where('staff_level_status','Inactive');
		} else {

		}
		$CI->db->where("is_deleted","0");
                $CI->db->order_by("staff_levels_seq","asc");
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
         /**
         * It returns list of company related category from DB.
         * @param int $company_id
         * @param string $type
          * @param int $parent_id
         * @returns array|int
         */
	function get_company_category($company_id,$type='',$parent_id=''){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('task_category');
		$CI->db->where('company_id',$company_id);
		if($type == 'Active'){
			$CI->db->where('category_status','Active');
		} elseif($type == 'Inactive'){
			$CI->db->where('category_status','Inactive');
		} else {

		}
		if($parent_id){
			$CI->db->where('parent_id',$parent_id);
		} else {
			$CI->db->where('parent_id','0');
		}
		$CI->db->where("is_deleted","0");
		$CI->db->order_by("category_seq","asc");
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
         /**
         * It returns list of company sub-category from DB.
         * @param int $company_id
         * @param string $type
         * @param int $parent_id 
         * @returns array|int
         */
	function get_company_sub_category($company_id,$type='',$parent_id=''){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('task_category');
		$CI->db->where('company_id',$company_id);
		if($type == 'Active'){
			$CI->db->where('category_status','Active');
		} elseif($type == 'Inactive'){
			$CI->db->where('category_status','Inactive');
		} else {

		}
		if($parent_id){
			$CI->db->where('parent_id',$parent_id);
		} else {
			$CI->db->where('parent_id !=','0');
		}
		$CI->db->where("is_deleted","0");
		$CI->db->order_by("category_seq","asc");
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
	
	/*
	 * Function : get_user_swimlanes
	 * Author : Spaculus
	 * para : user_id
	 * Desc : Gives list of swimlanes by user
	 */
	 /**
         * It get company swimlanes list from DB.
         * @param int $user_id
         * @returns array|int
         */
	function get_user_swimlanes($user_id){
                $CI =& get_instance();
		$CI->db->select("s.*,s.swimlane_height");
		$CI->db->from("swimlanes s");
		$CI->db->join("user_task_swimlanes uts","uts.swimlane_id = s.swimlanes_id","left");
                $CI->db->where('s.swimlane_status','active');
		$CI->db->where("s.user_id",$user_id);
		$CI->db->order_by("seq",'asc');
		$CI->db->group_by("s.swimlanes_id");
		
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /*
         * This function is used for getting swimlane of project team members.
         */
        function get_user_swimlanes_team($user_id,$project_id){
                $users=array();
                $ids=array(); 
                $CI =& get_instance();
                if($user_id == '#'){

                        $users=get_user_under_project($project_id);
                        if(!empty($users)){
                            foreach($users as $user){
                                  $ids[]=$user->user_id;
                            }
                        }
                }
                //array_push($ids, $CI->session->userdata('user_id'));
		
		$CI->db->select("s.*,s.swimlane_height");
		$CI->db->from("swimlanes s");
		$CI->db->join("user_task_swimlanes uts","uts.swimlane_id = s.swimlanes_id","left");
		$CI->db->where_in("s.user_id",$ids);
                $CI->db->where('s.swimlane_status','active');
		$CI->db->order_by("seq",'asc');
		$CI->db->group_by("s.swimlanes_id");
		
		$query = $CI->db->get();
                
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
         /**
         * It returns task status.
         * @param int $company_id
         * @param string $type
         * @returns array|int
         */
	function get_taskStatus($company_id,$type=''){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('task_status');
		$CI->db->where('company_id',$company_id);
		if($type == 'Active'){
			$CI->db->where('task_status_flag','Active');
		} elseif($type == 'Inactive'){
			$CI->db->where('task_status_flag','Inactive');
		} else {

		}
		$CI->db->order_by('task_sequence','asc');
		$query = $CI->db->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_total_taskStatus($company_id){
		$CI =& get_instance();
		$query = $CI->db->select('task_status_id')->from('task_status')->where('company_id',$company_id)->get();
		return $query->num_rows();
	}
         /**
         * It returns task sequences.
         * @param int $task_id
         * @returns array|int
         */
	function get_task_sequence($task_id){
		$CI =& get_instance();
		$CI->db->select('task_sequence');
		$CI->db->from('task_status');
		$CI->db->where("task_status_id",$task_id);
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_sequence;
		} else {
			return 0;
		}
	}

	function get_staff_level($id){
		$CI =& get_instance();
		$CI->db->select('staff_level_title');
		$CI->db->from('staff_levels');
		$CI->db->where('staff_level_id',$id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->staff_level_title;
		} else {
			return 0;
		}
	}
        /**
         * It get active color list from DB
         * @returns array|int
         */
	function get_colors(){
		$CI =& get_instance();
		$query = $CI->db->get_where('colors',array('status'=>'Active','is_deleted'=>'0'));
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /**
         * Get color code of user from DB
         * @param int $user_id
         * @returns int
         */
	function get_user_color_codes($user_id){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('user_colors');
		$CI->db->where('status','Active');
		$CI->db->where('user_id',$user_id);
		$CI->db->where('is_deleted','0');
		$CI->db->order_by('seq','asc');
		$query = $CI->db->get();
		if($query->num_rows()>0){
                    	return $query->result();
		} else {
			return 0;
		}
	}
	
	function is_user_color_exist($user_id){
		$CI =& get_instance();
		$query = $CI->db->select('user_color_id')->from('user_colors')->where('user_id',$user_id)->where('is_deleted','0')->get();
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	}

	function swim_task_ids(){
		$CI =& get_instance();
		$CI->db->select('swimlane_id');
		$CI->db->from('user_task_swimlanes');
		$CI->db->where('user_id',$CI->session->userdata('user_id'));
		$query = $CI->db->get();

		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $row){
				$ids[] = $row['swimlane_id'];
			}
			return $ids;
		} else {
			return array();
		}
	}

	function task_status_ids($status_id){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('tasks');
		$CI->db->where('task_company_id',$CI->session->userdata('company_id'));
		$CI->db->where('task_owner_id != ','0');
		$CI->db->where('task_allocated_user_id != ','0');
                $CI->db->where('task_status_id',$status_id);
		$CI->db->where('is_deleted','0');
		$query = $CI->db->get();

		return $query->num_rows();
	}

	function get_default_swimlane($user_id){
		$CI =& get_instance();
		$CI->db->select('swimlanes_id');
		$CI->db->from('swimlanes');
		$CI->db->where('user_id',$user_id);
		$CI->db->where("is_default",'1');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->swimlanes_id;
		} else {
			return 0;
		}
	}
        /**
         * It chcek user id,is it manager or not.
         * @param int $user_id
         * @returns int
         */
	function is_user_manager($user_id){
		$CI =& get_instance();
		$CI->db->select('is_manager');
		$CI->db->from('users');
		$CI->db->where('user_id',$user_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->is_manager;
		} else {
			return 0;
		}
	}
        /**
         * It checks user_id ,is it owner or not.
         * @param int $user_id
         * @return int
         */
	function is_user_owner($user_id){
		$CI =& get_instance();
		$CI->db->select('is_administrator');
		$CI->db->from('users');
		$CI->db->where('user_id',$user_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->is_administrator;
		} else {
			return 0;
		}
	}

	function is_owner($user_id){
		$CI =& get_instance();
		$CI->db->select('is_owner');
		$CI->db->from('users');
		$CI->db->where('user_id',$user_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->is_owner;
		} else {
			return 0;
		}
	}
        /**
         * It get list of company user
         * @returns array
         */
	function get_company_users(){
		$CI =& get_instance();
		$CI->db->select('user_id,first_name,last_name');
		$CI->db->from('users');
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
		//$CI->db->where('user_id !=',$CI->session->userdata('user_id'));
		$CI->db->where('user_status','Active');
		$CI->db->where('is_deleted','0');
		$query = $CI->db->get();

		if($query->num_rows()>0){
			return $query->result();
		}
	}

	function get_company_users_report(){
		$CI =& get_instance();
		$CI->db->select('user_id,first_name,last_name');
		$CI->db->from('users');
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
		$CI->db->where('user_id !=',$CI->session->userdata('user_id'));
		$CI->db->where('user_status','Active');
		$CI->db->where('is_deleted','0');
		$query = $CI->db->get();

		if($query->num_rows()>0){
			return $query->result();
		}
	}

	function get_company_users_project($project_id){
		$CI =& get_instance();
		$CI->db->select('u.user_id,u.first_name,u.last_name');
		$CI->db->from('users u');
		$CI->db->join('project_users pu','pu.user_id = u.user_id','left');
		$CI->db->where('u.company_id',$CI->session->userdata('company_id'));
		$CI->db->where('u.user_id !=',$CI->session->userdata('user_id'));
		$CI->db->where('u.user_status','Active');
		$CI->db->where('pu.project_id',$project_id);
		$CI->db->where('u.is_deleted','0');
		$query = $CI->db->get();

		if($query->num_rows()>0){
			return $query->result();
		}
	}


	function get_user_first_name($user_id){
		$CI =& get_instance();
		$query = $CI->db->select("first_name")->from("users")->where("user_id",$user_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->first_name;
		}
	}

	function get_user_last_name($user_id){
		$CI =& get_instance();
		$query = $CI->db->select("last_name")->from("users")->where("user_id",$user_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->last_name;
		}
	}
        /*        
         * This function is used for get user details using project id 
         */
        function get_user_under_project($project_id){
            $CI=&get_instance();
            $CI->db->select('u.user_id,u.first_name,u.last_name');
				$CI->db->from('users u');
                                $CI->db->join('project_users pu','u.user_id=pu.user_id');
				$CI->db->where('pu.project_id',$project_id);
				$CI->db->where('pu.status','Active');
				$CI->db->where('pu.is_deleted','0');
                               // $CI->db->where('pu.is_project_owner','0');
				$query = $CI->db->get();
                                //echo $CI->db->last_query(); die();
                                if($query->num_rows()>0){
                                        return $query->result();
                                } else {
                                        return 0;
                                }
        }
        
	function get_users_under_managers(){
		$CI =& get_instance();
		
		$CI->db->select('um.user_id,u.first_name,u.last_name,u.profile_image');
				$CI->db->from('user_managers um');
				$CI->db->join('users u','u.user_id = um.user_id');
				$CI->db->where('um.manager_id',$CI->session->userdata('user_id'));
				$CI->db->where('u.user_status','Active');
				$CI->db->where('u.is_deleted','0');
				$query = $CI->db->get();
		//$query = $CI->db->query("CALL user_managers('".$CI->session->userdata('user_id')."')");
		//$query->next_result();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}

	}
	function get_users_under_managers_ids(){
		$CI =& get_instance();
		$CI->db->select('um.user_id');
		$CI->db->from('user_managers um');
		$CI->db->join('users u','u.user_id = um.user_id');
		$CI->db->where('um.manager_id',$CI->session->userdata('user_id'));
		$CI->db->where('u.user_status','Active');
		$CI->db->where('u.is_deleted','0');
		$query = $CI->db->get();
		//echo $CI->db->last_query();die;
		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $row){
				$ids[] = $row['user_id'];
			}
			return $ids;
		} else {
			return 0;
		}

	}

	function get_user_count_under_manager($user_id){
		$CI =& get_instance();

		$query = $CI->db->select("user_id")->from("user_managers")->where("manager_id",$user_id)->get();
		return $query->num_rows();


	}

	function get_category_name($id){
		$CI =& get_instance();
		$query = $CI->db->select('category_name')->from('task_category')->where('category_id',$id)->where('company_id',$CI->session->userdata('company_id'))->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->category_name;
		} else {
			return '';
		}
	}


	function get_total_task($project_id='0',$user_id='0',$task_status_completed_id){

		$CI =& get_instance();
		$off_days = get_company_offdays();
		//date_default_timezone_set("UTC");
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today=date('Y-m-d');
		
		//task_id,task_project_id,task_owner_id,task_allocated_user_id,task_scheduled_date,frequency_type,recurrence_type,start_on_date,end_by_date

		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = '".$project_id."' AND is_deleted <> '1'  AND master_task_id = '0' ";

		

		$query = $CI->db->query($sql);
		//date_default_timezone_set($CI->session->userdata("User_timezone"));
		//echo $CI->db->last_query();die;
		$tasks = $query->result();
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);
					//pr($chk_recu);
					$recu_arr = array();
					if($chk_recu){
						/*if($subsection_id == '0'){
							if($chk_recu['section_id'] == $subsection_id && $chk_recu['subsection_id'] == $section_id){
								$recu_arr = $chk_recu;
							}
						} else {
							if($chk_recu['section_id'] == $subsection_id && $chk_recu['subsection_id'] == $section_id){
								$recu_arr = $chk_recu;
							}
						}*/
						$recu_arr = $chk_recu;
					}
					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['task_status_id'] != $task_status_completed_id){
							if($chk_recu['subsection_id']!='0'){
								$tasks2 = $chk_recu;
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id != "0"){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}


		/*
		if($query->num_rows()>0){
					return $query->num_rows();
				} else {
					return 0;
				}*/

	}

	function get_my_task($project_id,$user_id,$task_status_completed_id){
		//echo $user_id;
		$CI =& get_instance();
		$off_days = get_company_offdays();

		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today=date('Y-m-d');
		
		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = '".$project_id."'  AND master_task_id = '0' AND is_deleted <> '1'  ";
		//AND task_allocated_user_id = ".get_authenticateUserID()."
		//AND `task_status_id` <> ".$task_status_completed_id."
		if($user_id!='all'){
			//echo "if";die;
			$sql .= " AND task_allocated_user_id = '".$user_id."'";
		}else{
			//echo "else";die;
			//$sql.='and task_allocated_user_id = "'.$user_id.'"';
		}

		$query = $CI->db->query($sql);
		//echo $CI->db->last_query();die;
		$tasks = $query->result();
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);

					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['task_status_id'] != $task_status_completed_id){
							if($chk_recu['subsection_id']!='0'){
								if($chk_recu['is_personal'] == '0'){
									$tasks2 = $chk_recu;
								}
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id!='0'){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}

		//date_default_timezone_set("UTC");
		//echo $CI->db->last_query();die;
		/*
		if($query->num_rows()>0){
					return $query->num_rows();
				} else {
					return 0;
				}*/

	}

        /**
         * It get total upcoming task via project_id,user_id and completed_id.
         * @param int $project_id
         * @param int $user_id
         * @param int $task_status_completed_id
         * @returns int
         */

	function get_total_upcoming_task($project_id,$user_id,$task_status_completed_id){

		$CI =& get_instance();
		$off_days = get_company_offdays();
		date_default_timezone_set($CI->session->userdata("User_timezone"));

		$today=date('Y-m-d');
		
		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = ".$project_id." AND is_deleted <> '1'  AND master_task_id = '0' AND `task_scheduled_date` > '".$today."' and `task_scheduled_date` <>'0000-00-00' ";


		 //if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`>'".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`>'".$today."' and `task_due_date` <>'0000-00-00')

		if($user_id!='all'){
			//$sql .= ' AND task_allocated_user_id = "'.$user_id.'"';
		}else{

			//$sql.='and task_allocated_user_id = "'.get_authenticateUserID().'"';
		}
		$query = $CI->db->query($sql);

		$tasks = $query->result();
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);

					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['task_status_id'] != $task_status_completed_id){
							if($chk_recu['subsection_id']!='0'){
								if($chk_recu['is_personal'] == '0'){
									$tasks2 = $chk_recu;
								}
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id!='0'){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}


		//echo $CI->db->last_query();die;
		/*
		if($query->num_rows()>0){
					return $query->num_rows();
				} else {
					return 0;
				}*/

	}

	function get_my_upcoming_task($project_id,$user_id,$task_status_completed_id){

		$CI =& get_instance();
		$off_days = get_company_offdays();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today=date('Y-m-d');
		

		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = ".$project_id." AND is_deleted <> '1' AND master_task_id = '0'   AND `task_scheduled_date` > '".$today."' and `task_scheduled_date` <>'0000-00-00' ";


		if($user_id!='all'){

			$sql .= ' AND task_allocated_user_id = "'.$user_id.'"';
		}else{

			//$sql.='and task_allocated_user_id = "'.get_authenticateUserID().'"';
		}

		$query = $CI->db->query($sql);

		$tasks = $query->result();
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);

					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['task_status_id'] != $task_status_completed_id){
							if($chk_recu['subsection_id']!='0'){
								if($chk_recu['is_personal'] == '0'){
									$tasks2 = $chk_recu;
								}
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id!='0'){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}


	}

	function get_tot_today_task($project_id,$user_id,$task_status_completed_id){

		$CI =& get_instance();
		$off_days = get_company_offdays();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today=date('Y-m-d');
		
		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = ".$project_id." AND is_deleted <> '1' AND  master_task_id = '0' AND `task_scheduled_date` = '".$today."' and `task_scheduled_date` <>'0000-00-00' ";


		// if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`='".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`='".$today."' and `task_due_date` <>'0000-00-00')

		if($user_id!='all'){
			//$sql .= ' AND task_allocated_user_id = "'.$user_id.'"';
		}else{

			//$sql.='and task_allocated_user_id = "'.get_authenticateUserID().'"';
		}

		$query = $CI->db->query($sql);

		$tasks = $query->result();
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);

					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['task_status_id'] != $task_status_completed_id){
							if($chk_recu['subsection_id']!='0'){
								if($chk_recu['is_personal'] == '0'){
									$tasks2 = $chk_recu;
								}
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id!='0'){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}
		//echo $CI->db->last_query();die;

	}

	function get_my_today_task($project_id,$user_id,$task_status_completed_id){

		$CI =& get_instance();
		$off_days = get_company_offdays();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today=date('Y-m-d');
		
		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = ".$project_id." AND is_deleted <> '1' AND master_task_id = '0'  AND `task_scheduled_date`= '".$today."' and `task_scheduled_date` <>'0000-00-00' ";

		// AND `task_status_id` <> ".$task_status_completed_id."
		//if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`='".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`='".$today."' and `task_due_date` <>'0000-00-00')

		if($user_id!='all'){
			$sql .= ' AND task_allocated_user_id = "'.$user_id.'"';
		}else{

			//$sql.='and task_allocated_user_id = "'.get_authenticateUserID().'"';
		}

		$query = $CI->db->query($sql);
		//echo $CI->db->last_query();
		$tasks = $query->result();
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);

					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['subsection_id']!='0'){
							if($chk_recu['is_personal'] == '0'){
								$tasks2 = $chk_recu;
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id!='0'){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}

	}
        /**
         * It returns total overdue task from DB using three parameter
         * @param int $project_id
         * @param int $user_id
         * @param int $task_status_completed_id
         * @return int
         */
	function get_tot_overdue_task($project_id,$user_id,$task_status_completed_id){

		$CI =& get_instance();
		$off_days = get_company_offdays();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today=date('Y-m-d');
		
		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = ".$project_id." AND is_deleted <> '1' AND master_task_id = '0' AND `task_status_id` <> ".$task_status_completed_id." AND `task_due_date` < '".$today."' and `task_due_date` <>'0000-00-00' ";


		//if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`<'".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`<'".$today."' and `task_due_date` <>'0000-00-00')


		if($user_id!='all'){
			//$sql .= ' AND task_allocated_user_id = "'.$user_id.'"';
		}else{

			//$sql.='and task_allocated_user_id = "'.get_authenticateUserID().'"';
		}

		$query = $CI->db->query($sql);

		$tasks = $query->result();
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);

					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['subsection_id']!='0'){
							if($chk_recu['task_status_id'] != $task_status_completed_id){
								if($chk_recu['is_personal'] == '0'){
									$tasks2 = $chk_recu;
								}
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id!='0'){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}


	}

	function get_my_overdue_task($project_id,$user_id,$task_status_completed_id){

		$CI =& get_instance();
		$off_days = get_company_offdays();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today=date('Y-m-d');
		
		$sql = "select * from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = ".$project_id." AND is_deleted <> '1' AND master_task_id = '0'  AND `task_status_id` <> ".$task_status_completed_id." AND `task_due_date` < '".$today."' and `task_due_date` <>'0000-00-00' ";

		//if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`<'".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`<'".$today."' and `task_due_date` <>'0000-00-00')

		if($user_id!='all'){
			$sql .= ' AND task_allocated_user_id = "'.$user_id.'"';
		}else{

			//$sql.='and task_allocated_user_id = "'.get_authenticateUserID().'"';
		}

		$query = $CI->db->query($sql);
		//echo $CI->db->last_query();die;
		$tasks = $query->result();
		//pr($tasks);
		$task_detail = array();
		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);

					if(isset($chk_recu) && !empty($recu_arr)){
						if($chk_recu['task_status_id'] != $task_status_completed_id){
							if($chk_recu['subsection_id']!='0'){
								if($chk_recu['is_personal'] == '0'){
									$tasks2 = $chk_recu;
								}
							}
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->subsection_id!='0'){
					$task_detail[] = $row;
					}
				}
				$i++;
			}
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return count($task_detail);
		} else {
			return 0;
		}


	}
        /**
         * It get project details via project_id
         * @param int $project_id
         * @returns array|int
         */
	function get_project_info($project_id)
	{
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('project');
		$CI->db->where("project_id",$project_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
        /**
         * it get project files from DB according to project id
         * @param int $project_id
         * @returns array|int
         */
	function get_project_files($project_id){
		$CI =& get_instance();
		$query = $CI->db->select('u.first_name,u.last_name,tp.*')->from('task_and_project_files tp')->join('users u','u.user_id = tp.file_added_by','left')->where(array('tp.project_id' => $project_id,'tp.project_id <>'=>'0'))->order_by('tp.file_date_added','DESC')->get();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
        /**
         * It will get user timezone from company and user table .
         * @returns void
         */
	function get_user_timezone(){

		$CI =& get_instance();
		$CI->db->select('c.company_timezone,u.user_time_zone');
		$CI->db->from('company c');
		$CI->db->join('users u','u.company_id = c.company_id','left');
		$CI->db->where('c.company_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			if($res->user_time_zone){
				return $res->user_time_zone;
			} elseif($res->company_timezone){
				return $res->company_timezone;
			} else {
				return date_default_timezone_get();
			}
		} else {
			return date_default_timezone_get();
		}
	}

	function getCompanyTimeZone($company_id){
		$CI =& get_instance();
		$query = $CI->db->select('company_timezone')->from('company')->where('company_id',$company_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->company_timezone;
		} else {
			return 0;
		}
	}

	function get_file_data($file_name){
		$CI =& get_instance();
		$query = $CI->db->get_where('task_and_project_files',array('task_file_name' => $file_name));
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}

	function is_user_project_owner($user_id,$project_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('is_project_owner')->get_where('project_users',array('user_id' => $user_id,'project_id'=>$project_id,'is_deleted'=>'0'));

		if($query->num_rows()>0){
			$res = $query->row();
			return $res->is_project_owner;
		} else {
			return 0;
		}

	}

	function get_managers_of_users($user_id){
		$CI =& get_instance();
		$query = $CI->db->select('um.manager_id,u.first_name,u.last_name,u.email')
						->from('user_managers um')
						->join('users u','u.user_id = um.manager_id')
						->where('um.user_id',$user_id)
						->where('u.user_status','Active')
						->where('u.is_deleted','0')
						->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_default_day_no_of_company(){
		$CI =& get_instance();
		$query = $CI->db->select('fisrt_day_of_week')
						->from('default_calendar_setting')
						->where('comapny_id',$CI->session->userdata('company_id'))
						->get();
		if($query->num_rows()>0){
			$res = $query->row();
			if($res->fisrt_day_of_week){
				if($res->fisrt_day_of_week == 'Monday'){
					return 2;
				} elseif($res->fisrt_day_of_week == 'Tuesday'){
					return 3;
				} elseif($res->fisrt_day_of_week == 'Wednesday'){
					return 4;
				} elseif($res->fisrt_day_of_week == 'Thursday'){
					return 5;
				} elseif($res->fisrt_day_of_week == 'Friday'){
					return 6;
				} elseif($res->fisrt_day_of_week == 'Saturday'){
					return 7;
				} elseif($res->fisrt_day_of_week == 'Sunday'){
					return 1;
				} else {
					return 1;
				}
			} else {
				return 1;
			}
			return $res->fisrt_day_of_week;
		} else {
			return 1;
		}
	}



	function get_project_task_detail($task_id){
                
		$CI =& get_instance();
                $task_status_completed_id = $CI->config->item('completed_id');
		$CI->db->select('t.*,ps.section_name,ps.section_order,ps.subsection_order,u.first_name,u.last_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies');
		
		$CI->db->from('tasks t');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('project_section ps','ps.section_id = t.subsection_id','left');
		//$CI->db->where('uts.user_id',get_authenticateUserID());
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_id',$task_id);
		$query = $CI->db->get();
		//echo $CI->db->last_query();die;
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}

	// recurrence task logic for task listing project module ends here

	function getTaskDetail($section_id,$subsection_id,$project_id,$task_status_completed_id)
	{
		$off_days = get_company_offdays();
		
		$CI =& get_instance();
		$CI->db->select('t.*,p.project_title,ps.section_name,ps.section_order,ps.subsection_order,u.first_name,u.last_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,(SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments, (SELECT COUNT(1) FROM tasks tp  WHERE tp.prerequisite_task_id = t.task_id and tp.is_deleted = 0) AS tpp, (SELECT COUNT(1) FROM tasks tpp  WHERE tpp.prerequisite_task_id = t.task_id AND tpp.is_prerequisite_task ="1" AND tpp.is_deleted = 0 AND tpp.task_status_id != "'.$task_status_completed_id.'" ) AS completed_depencencies, (SELECT COUNT(1) FROM task_steps tsp  WHERE tsp.task_id = t.task_id) AS steps,(SELECT COUNT(1) FROM task_and_project_files tpf  WHERE tpf.task_id = t.task_id and tpf.is_deleted=0) AS files',FALSE);
		$CI->db->from('project_section ps');
		$CI->db->join('tasks t','t.task_project_id = ps.project_id','left');
		$CI->db->join('project p','t.task_project_id = p.project_id','left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('users u','u.user_id = t.task_allocated_user_id','left');
		$CI->db->where(array('t.subsection_id'=>$section_id,'t.section_id'=>$subsection_id,'t.task_project_id'=>$project_id));  //,'task_status_id <>'=>$task_status_completed_id ,,'uts.user_id'=>get_authenticateUserID()
		$CI->db->where('t.is_deleted','0');
		$CI->db->where('t.task_owner_id != ',"0");
		//$CI->db->where('t.master_task_id ',"0");
		$CI->db->where('t.task_allocated_user_id != ',"0");
		$CI->db->where('t.is_personal',"0");
		$CI->db->where('task_company_id',$CI->session->userdata('company_id'));
		$CI->db->group_by('t.task_id');
                $CI->db->order_by('ps.section_order asc,ps.subsection_order asc,t.task_order asc');
		//$CI->db->order_by('ps.section_order asc,ps.subsection_order asc,t.task_order asc');
                //$CI->db->order_by('t.task_due_date desc');
		$query = $CI->db->get();
		
		$tasks = $query->result();

		$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){

				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass,'',$off_days);
					//pr($virtual_array);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array,$task_status_completed_id);
					$recu_arr = array();
					if($chk_recu){
						if($subsection_id == '0'){
							//echo "in if";
							if($chk_recu['section_id'] == $subsection_id && $chk_recu['is_personal'] == '0'  && $chk_recu['subsection_id'] == $section_id){
								$recu_arr = $chk_recu;
							}
						} else {
							//echo "in else";
							if($chk_recu['section_id'] == $subsection_id && $chk_recu['is_personal'] == '0' && $chk_recu['subsection_id'] == $section_id){
								$recu_arr = $chk_recu;
							}
						}
					}
					if(isset($recu_arr) && !empty($recu_arr)){
						if($recu_arr['is_personal'] == '0'){
							$tasks2 = $recu_arr;
						}
					}
					$task_detail[] = (object) $tasks2;
				} else {
					if($row->is_personal == '0'){
						$task_detail[] = $row;
					}
				}
				$i++;
			}
		}else{
			$task_detail = $query->result();
		}
		//pr($task_detail);
		if($query->num_rows()>0){
			return $task_detail;
		} else {
			return 0;
		}
	}

	function chk_project_recurrence_exists($row,$vr_arr,$task_status_completed_id){
		
		$off_days = get_company_offdays();
		$CI =& get_instance();

		$CI->db->select('t.*,p.project_title,ps.section_name,ps.section_order,ps.subsection_order,u.first_name,u.last_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,(SELECT COUNT(1) FROM task_and_project_comments tc  WHERE tc.task_id = t.task_id) AS comments',FALSE);
		$CI->db->from('tasks t');
		$CI->db->join('users u','t.task_owner_id = u.user_id','left');
		$CI->db->join('project_section ps','t.task_project_id = ps.project_id','left');
		$CI->db->join('project p','t.task_project_id = p.project_id','left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->where('t.master_task_id',$vr_arr['master_task_id']);
		$CI->db->where('t.task_orig_scheduled_date',$vr_arr['task_orig_scheduled_date']);
		$CI->db->where('t.task_owner_id != ',"0");
		$CI->db->where('t.task_allocated_user_id != ',"0");
		//$CI->db->where('t.is_deleted','0');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$chk_arr = $query->row_array();
			if($chk_arr['is_deleted'] == "0"){
				// check for completed
				if($chk_arr['task_status_id'] == $task_status_completed_id){
					$arr = kanban_recurrence_logic($row,$chk_arr['task_orig_scheduled_date'],$off_days);
					if($arr){
						return chk_project_recurrence_exists($row,$arr,$task_status_completed_id);
					} else {
						return 0;
					}

				} else {
					return 0;
				}
			} else {
				return 0;
			}
		} else {
			return $vr_arr;
		}
	}



	function get_default_day_of_company($company_id = 0){
		$CI =& get_instance();
                if($CI->session->userdata("company_id") != "" || $CI->session->userdata("company_id") >0)
		{
			$company_id = $CI->session->userdata("company_id");
		}
		$query = $CI->db->select('fisrt_day_of_week')
						->from('default_calendar_setting')
						->where('comapny_id',$company_id)
						->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->fisrt_day_of_week;
		} else {
			return 'Monday';
		}
	}
        /**
         * It returns number of working days from default calender setting.
         * @returns int
         */
	function get_no_of_working_days(){
		$CI =& get_instance();
		$query = $CI->db->select('*')
						->from('default_calendar_setting')
						->where('comapny_id',$CI->session->userdata('company_id'))
						->get();
		if($query->num_rows()>0){
			$res = $query->row();
			//pr($res);die;
			$i = 0;
			if($res->MON_closed == '1'){
				$i++;
			}
			if($res->TUE_closed == '1'){
				$i++;
			}
			if($res->WED_closed == '1'){
				$i++;
			}
			if($res->THU_closed == '1'){
				$i++;
			}
			if($res->FRI_closed == '1'){
				$i++;
			}
			if($res->SAT_closed == '1'){
				$i++;
			}
			if($res->SUN_closed == '1'){
				$i++;
			}
			return $i;
		} else {
			return 0;
		}
	}
        /**
         * It get user default working days form DB
         * @returns int
         */
	function get_user_no_of_working_days(){
		$CI =& get_instance();
		$query = $CI->db->select('*')
						->from('default_calendar_setting')
						->where('user_id',$CI->session->userdata('user_id'))
						->where('comapny_id',0)
						->get();
		if($query->num_rows()>0){
			$res = $query->row();
			$i = 0;
			if($res->MON_closed == '1'){
				$i++;
			}
			if($res->TUE_closed == '1'){
				$i++;
			}
			if($res->WED_closed == '1'){
				$i++;
			}
			if($res->THU_closed == '1'){
				$i++;
			}
			if($res->FRI_closed == '1'){
				$i++;
			}
			if($res->SAT_closed == '1'){
				$i++;
			}
			if($res->SUN_closed == '1'){
				$i++;
			}
			return $i;
		} else {
			return 0;
		}
	}
        /**
         * It returns distinct task_priority from tasks table
         * @returns array|int
         */
	function taskPriority()
	{
		$CI =& get_instance();

		$query = $CI->db->select("distinct(task_priority)")->from('tasks')->where(array('task_priority <>'=>''))->get();


		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /**
         * It returns specific task priority from DB
         * @param int $task_id
         * @returns string
         */
	function get_task_priority($task_id){
		$CI =& get_instance();
		$query = $CI->db->select("task_priority")->from("tasks")->where("task_id",$task_id)->where("is_deleted","0")->get();
		if($query->num_rows()>0){
			$res = $query->row_array();
			return $res['task_priority'];
		}
	}

	function get_user_list_project($project_id='',$division='',$department='',$skills='',$staff_level = ''){
		$CI =& get_instance();
		$CI->db->select('u.first_name, u.last_name, u.user_id');
		$CI->db->from('users u');
		$CI->db->join('project_users pu','pu.user_id = u.user_id','left');
		$CI->db->join('user_devision ud','ud.user_id = u.user_id','left');
		$CI->db->join('user_department udp','udp.user_id = u.user_id','left');
		$CI->db->join('user_skills us','us.user_id = u.user_id', 'left');
		if($division){
			$CI->db->where_in('ud.devision_id',$division);
		}
		if($department){
			$CI->db->where_in('udp.dept_id',$department);
		}
		if($skills){
			$CI->db->where_in('us.skill_id',$skills);
		}
		if($staff_level){
			$CI->db->where('u.staff_level',$staff_level);
		}
		$CI->db->where('u.company_id',$CI->session->userdata('company_id'));
		$CI->db->where('pu.project_id',$project_id);
		$CI->db->where('u.user_status','Active');
		$CI->db->where('u.is_deleted','0');
		$CI->db->group_by('u.user_id');
		$query = $CI->db->get();
		//echo $CI->db->last_query();die;

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}

	}
	function getUserDepartmentByDivision($division='')
	{
		$CI =& get_instance();
		$CI->db->select('cd.*');
		$CI->db->from('user_department ud');
		$CI->db->join('company_departments cd','cd.department_id = ud.dept_id');
		if($division){
			$CI->db->where_in('cd.deivision_id',$division);
		}
		$CI->db->where(array('cd.status'=>'Active','cd.is_deleted <>'=>'1','cd.company_id'=>$CI->session->userdata("company_id")));
		$CI->db->order_by('cd.department_id');
		$CI->db->group_by('cd.department_id');
		$query = $CI->db->get();

		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return '';
		}
	}

	function get_department_by_division($division='')
	{
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('company_departments');
		if($division){
			$CI->db->where_in('deivision_id',$division);
		}
		$CI->db->where(array('status'=>'Active','is_deleted <>'=>'1','company_id'=>$CI->session->userdata("company_id")));
		$CI->db->order_by('department_id');
		$CI->db->group_by('department_id');
		$query = $CI->db->get();

		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return '';
		}
	}

	function get_project_user_list($project_id)
	{
		$CI =& get_instance();

		$query = $CI->db->select('u.first_name,u.last_name,u.user_id')
					->from('project_users pu')
					->join('project p','p.project_added_by = pu.user_id','left')
					->join('users u','u.user_id = pu.user_id','left')
					->where('pu.project_id',$project_id)
					->where('u.is_deleted','0')
					->where('u.user_status ','Active')
					->group_by('u.user_id')
					->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /**
         * It returns member list of project.
         * @param int $project_id
         * @returns array|int
         */
	function get_memberList($project_id)
	{
		$CI =& get_instance();

		$sql = 	$CI->db->query('select user_id from project_users where project_id = '.$project_id);

		if($sql->num_rows()>0){
			$mem =  $sql->result_array();
			foreach($mem as $mem){
				$ids1[] = $mem['user_id'];
			}
			$ids = join(',',$ids1);
			//pr($ids);die;
			$query = $CI->db->query('select user_id,first_name,last_name,is_customer_user,customer_user_id from users where company_id = '.$CI->session->userdata('company_id').' AND user_status = "Active" AND is_deleted !="1" AND user_id not in ('.$ids.')');

			if($query->num_rows()>0){
				return $query->result();
			} else {
				return 0;
			}
		} else {
			return 0;
		}



	}

	function getLastloginrange()
	{
		$CI =& get_instance();

		$query = $CI->db->select('ul.user_login_date')
					->from('user_login_history ul')
					->join('users u','u.user_id = ul.user_id','left')
					->where('ul.user_id',get_authenticateUserID())
					->limit(2)
					->order_by('user_login_history_id','desc')
					->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function is_project($project_id)
	{
		$CI=& get_instance();

		$qry = $CI->db->select('project_id')->get_where("project",array("project_id"=>$project_id,'is_deleted <>' => "1"));

		if($qry->num_rows()>0)
		{
			return true;
		}else{
			return false;
		}


	}
        /**
         * It get task title using task_id
         * @param int $task_id
         * @returns string|int
         */
	function get_task_title($task_id){
		$CI =& get_instance();
		$query = $CI->db->select('task_title')->from('tasks')->where('task_id',$task_id)->where('task_owner_id != ','0')->where('task_allocated_user_id != ','0')->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_title;
		} else {
			return 0;
		}
	}



	function taskByPriority_old($priority)
	{

		//date_default_timezone_set("UTC");
		$today = date('Y-m-d');
		$CI =& get_instance();
		$query = $CI->db->query("SELECT (sum(task_time_spent)/60) as tasktime  FROM `tasks` WHERE if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`='".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`='".$today."' and `task_due_date` <>'0000-00-00') AND `task_priority` = '".$priority."' AND (task_owner_id = ".get_authenticateUserID()." OR task_allocated_user_id = ".get_authenticateUserID().") AND task_owner_id != '0' AND task_allocated_user_id != '0' ");

		//echo $CI->db->last_query();die;
		//date_default_timezone_set($CI->session->userdata("User_timezone"));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->tasktime;
		} else {
			return 0;
		}
	}


	function taskByPriority($priority,$task_status_completed_id='',$offdays='')
	{

		$CI =& get_instance();

		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today = date('Y-m-d');
		if($task_status_completed_id){ $task_status_completed_id = $task_status_completed_id; } else { $task_status_completed_id = $CI->config->item('completed_id'); }
		 
		if($offdays){ $offdays = $offdays; } else { $offdays = get_company_offdays();}

		$task_list = array();

		$query = $CI->db->query("SELECT  * FROM `tasks` WHERE task_owner_id != '0' AND task_allocated_user_id != '0' AND  `task_status_id` <> '".$task_status_completed_id."' AND master_task_id  = 0 AND task_company_id = ".$CI->session->userdata('company_id')." AND is_deleted ='0' ");

		if($query->num_rows()>0){
			$res = $query->result();

			if($res){
				foreach($res as $row){
				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;
					$re_data = monthly_recurrence_logic($row_pass,$today,$today,$offdays);
					if($re_data){
						foreach($re_data as $row2){
							$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
							if($chk_rec){
								if($chk_rec['task_priority'] == $priority && $chk_rec['task_scheduled_date'] == $today  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID()  && $chk_rec['is_deleted'] == "0"){
									array_push($task_list,$chk_rec);
								}
							} else {
								if($row2['task_scheduled_date'] == $today  &&   $row2['task_priority'] == $priority  && $row2['task_allocated_user_id'] == get_authenticateUserID()){
									array_push($task_list,$row2);
								}
							}
						}
					}
				} else {

					if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_priority == $priority && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
						array_push($task_list,(array)$row);
					}
				}
			}
		}

		$task_time = 0;
		foreach ($task_list as $key) {
			$task_time = $task_time + $key['task_time_estimate'];
		}

		//$task_time = (($task_time)/60);

		return $task_time;
	} else {
		return '0';
	}
}


	function getmytask()
	{
		

		$CI =& get_instance();
		$task_status_completed_id = $CI->config->item('completed_id');
		$CI->session->userdata("User_timezone");
		$today = date('Y-m-d');
		$task_list = array();

		$query = $CI->db->select('*')->from('tasks')->where(array('task_status_id <>'=>$task_status_completed_id,'master_task_id'=>'0','task_company_id'=>$CI->session->userdata('company_id'),'is_deleted'=>'0','task_owner_id <>' => '0','task_allocated_user_id <>' => '0'))->get();

		/*if($query->num_rows()>0){
			$res = $query->row();
			return $res->allocationtime;
		} else {
			return 0;
		}*/


		if($query->num_rows()>0){
			$res = $query->result();

			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						$re_data = monthly_recurrence_logic($row_pass,$today,$today);
						//pr($re_data);
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date'] == $today  && $chk_rec['task_allocated_user_id'] == get_authenticateUserID()  && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date'] == $today  &&  $row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row->task_allocated_user_id == get_authenticateUserID() && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
							array_push($task_list,(array)$row);
						}
					}
				}
			}
			$task_time = 0;
			foreach ($task_list as $key) {
				//echo $key['task_time_estimate']."====";
				$task_time = $task_time + $key['task_time_estimate'];
			}
			//echo "mytask".$task_time = (($task_time)/60);
			$task_time = (($task_time)/60);
			//$task_list = (object)$task_list;
			//pr($task_list);
	
			return $task_time;
		} else {
			return '0';
		}
	}

	function getmyteamtask($task_status_completed_id,$offdays)
	{

		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today = date('Y-m-d');
		$task_list = array();

		$data['team_ids'] = get_users_under_managers_ids();
		if($data['team_ids']!='0'){
			$ids = join(',',$data['team_ids']);
		}else{
			$ids = '0';
		}

		$query = $CI->db->query("select * from tasks where task_owner_id !='0' AND task_allocated_user_id !='0' AND task_status_id !='".$task_status_completed_id."' AND master_task_id ='0' AND task_company_id  = '".$CI->session->userdata('company_id')."' AND is_deleted ='0' AND task_allocated_user_id in (".$ids.")");

		if($query->num_rows()>0){
			$res = $query->result();
			//pr($res);die;
			if($res){
				foreach($res as $row){
				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					//echo "inside recurrence";die;
					$row_pass = (array) $row;
					//pr($row_pass);
					$re_data = monthly_recurrence_logic($row_pass,$today,$today,$offdays);
					//pr($re_data);
					if($re_data){
						//echo "main iffffff";
						foreach($re_data as $row2){
							$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
							if($chk_rec){
								if($chk_rec['task_scheduled_date'] == $today && $chk_rec['is_personal'] == "0"  && $chk_rec['task_allocated_user_id'] != get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){

									//echo "for if";
									array_push($task_list,$chk_rec);
								}
							} else {

								if($row2['task_scheduled_date'] == $today  && $row2['is_personal'] == "0"  &&  $row2['task_allocated_user_id'] != get_authenticateUserID()){
									//echo "for else";
									array_push($task_list,$row2);
								}
							}
						}
					}
				} else {

					//echo "main else";
					if($row->task_allocated_user_id != get_authenticateUserID() && $row->is_personal == "0"   && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
						array_push($task_list,(array)$row);
					}
				}
			}
		}

		//pr($task_list);die;
		$task_time = 0;
		foreach ($task_list as $key) {
			$task_time = $task_time + $key['task_time_estimate'];
		}
		//$task_time = (($task_time)/60);

		return $task_time;
	} else {
		return '0';
	}


	}

	function get_task_By_category($task_status_completed_id,$offdays)
	{
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$today = date('Y-m-d');

		$data['team_ids'] = get_users_under_managers_ids();
		if($data['team_ids']!='0'){
			$ids = join(',',$data['team_ids']);
		}else{
			$ids = '0';
		}

		$task_list = array();


		$query = $CI->db->query("SELECT t.*,tc.category_name FROM tasks t left join task_category tc on t.task_category_id = tc.category_id WHERE t.task_owner_id != '0' AND t.task_allocated_user_id != '0'  AND  t.`task_status_id` <> '".$task_status_completed_id."' AND t.master_task_id  = 0 AND t.task_company_id = ".$CI->session->userdata('company_id')." AND t.is_deleted ='0' AND t.task_allocated_user_id in (".$ids.") ");

		// /AND t.task_category_id <> '0'

		//echo $CI->db->last_query();die;
		if($query->num_rows()>0){
			$res = $query->result();
			//pr($res);die;
			if($res){
				foreach($res as $row){
				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					//echo "inside recurrence";die;
					$row_pass = (array) $row;
					//pr($row_pass);
					$re_data = monthly_recurrence_logic($row_pass,$today,$today,$offdays);
					//pr($re_data);
					if($re_data){
						//echo "main iffffff";
						foreach($re_data as $row2){
							$chk_rec = chk_virtual_recurrence_exists($row2['master_task_id'],$row2['task_orig_scheduled_date'],$task_status_completed_id);
							if($chk_rec){
								if($chk_rec['task_scheduled_date'] == $today && $chk_rec['is_personal'] == "0" && $chk_rec['task_allocated_user_id'] != get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){

									//echo "for if";
									array_push($task_list,$chk_rec);
								}
							} else {

								if( $row2['task_scheduled_date'] == $today && $row2['is_personal'] == "0"  &&  $row2['task_allocated_user_id'] != get_authenticateUserID()){
									//echo "for else";
									array_push($task_list,$row2);
								}
							}
						}
					}
				} else {

					//echo "main else";
					if($row->task_allocated_user_id != get_authenticateUserID() && $row->is_personal == "0"   && $row->task_scheduled_date == $today && $row->task_scheduled_date != '0000-00-00'){
						array_push($task_list,(array)$row);
					}
				}
			}
		}


    $groups = array();
    $key = 0;
    foreach ($task_list as $item) {
        $key = $item['task_category_id'];
        if (!array_key_exists($key, $groups)) {
            $groups[$key] = array(
                'task_category_id' => $item['task_category_id'],
                'task_time_estimate' => $item['task_time_estimate'],
            );
        } else {
            $groups[$key]['task_time_estimate'] = $groups[$key]['task_time_estimate'] + $item['task_time_estimate'];
        }
        $key++;
    }


		return (object)$groups;
	} else {
		return '0';
	}


	/*	if($query->num_rows()>0){
			$res = $query->result();
			return $res;
		} else {
			return 0;
		}*/
	}

	function get_task_By_category_count()
	{
		$CI =& get_instance();
		$today = date('Y-m-d');

		$data['team_ids'] = get_users_under_managers_ids();
		if($data['team_ids']!='0'){
			$ids = join(',',$data['team_ids']);
		}else{
			$ids = '0';
		}

		$query = $CI->db->query("SELECT (sum(t.task_time_estimate)/60) as allocationtime,tc.category_name,t.task_id,t.task_category_id FROM tasks t left join task_category tc on t.task_category_id = tc.category_id WHERE if(`task_scheduled_date`!=0000-00-00, `task_scheduled_date`='".$today."' and `task_scheduled_date`<> '0000-00-00',`task_due_date`!='0000-00-00' and `task_due_date`='".$today."' and `task_due_date` <>'0000-00-00') AND task_category_id <> '0'  AND t.task_owner_id != '0' AND t.task_allocated_user_id != '0' AND task_company_id = ".$CI->session->userdata('company_id')." AND t.task_allocated_user_id in (".$ids.")  GROUP BY t.`task_category_id`");

		return $query->num_rows();
	}

	function getDepartmentByDivision($division_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('*')->from('company_departments')->where(array('deivision_id'=>$division_id,'status'=>'Active','is_deleted <>'=>'1'))->order_by('department_id')->get();

		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return '';
		}
	}
        /**
         * It get project name using project_id.
         * @param int $project_id
         * @returns string
         */
	function getProjectName($project_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('project_title')->from('project')->where(array('project_id'=>$project_id))->get();

		if($query->num_rows() > 0)
		{
			return $query->row()->project_title;
		}else{
			return '';
		}
	}
        /**
         * It get section name of task using task_id
         * @param int $task_id
         * @return string
         */
	function getSectionName($task_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('pu.section_name')->from('project_section pu')->join('tasks t','pu.section_id = t.subsection_id','left')->where(array('t.task_id'=>$task_id))->get();

		//echo $CI->db->last_query();die;
		if($query->num_rows() > 0)
		{
			return $query->row()->section_name;
		}else{
			return '';
		}
	}

	function fetchSectionName($subsection_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('pu.section_name')->from('project_section pu')->where(array('pu.section_id'=>$subsection_id))->get();

		//echo $CI->db->last_query();die;
		if($query->num_rows() > 0)
		{
			return $query->row()->section_name;
		}else{
			return '';
		}
	}
        /**
         * It get task color of user task
         * @param int $task_id
         * @param int $user_id
         * @return int
         */
	function get_user_task_color($task_id,$user_id){
		$CI =& get_instance();
		$query = $CI->db->select('color_id')->from('user_task_swimlanes')->where('task_id',$task_id)->where('user_id',$user_id)->get();

		if($query->num_rows()>0){
			$res = $query->row();
			return $res->color_id;
		} else {
			return 0;
		}
	}
        /**
         * It get color name from DB via color_id.
         * @param int $color_id
         * @return string
         */
	function get_color_name($color_id){
		$CI =& get_instance();
		$query = $CI->db->select('name')->from('user_colors')->where('user_color_id',$color_id)->where('status','Active')->where('is_deleted','0')->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->name;
		} else {
			return '';
		}
	}
        /**
         * It get task color code using color_id.
         * @param int $color_id
         * @return string
         */
	function get_task_color_code($color_id){
		$CI =& get_instance();
		$query = $CI->db->select('color_code')->from('user_colors')->where('user_color_id',$color_id)->where('status','Active')->where('is_deleted','0')->get();
		if($query->num_rows()>0){
			$res = $query->row();
			if($res->color_code){
				return $res->color_code;
			} else {
				return '#fff';
			}

		} else {
			return '#fff';
		}
	}
        /**
         * It get outside color code.
         * @param int $color_id
         * @returns string
         */
	function get_outside_color_code($color_id){
		$CI =& get_instance();
		$query = $CI->db->select('outside_color_code')->from('user_colors')->where('user_color_id',$color_id)->where('status','Active')->where('is_deleted','0')->get();
		if($query->num_rows()>0){
			$res = $query->row();
			if($res->outside_color_code){
				return $res->outside_color_code;
			} else {
				return '#e5e9ec';
			}
		} else {
			return '#e5e9ec';
		}
	}

	function get_project_id_from_task_id($task_id){
		$CI =& get_instance();
		$query = $CI->db->select('task_project_id')->from('tasks')->where('task_id',$task_id)->where('task_owner_id != ',"0")->where('task_allocated_user_id != ',"0")->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_project_id;
		} else {
			return 0;
		}
	}
        /**
         * It get company date format from company table.
         * @returns string
         */
	function default_date_format(){
		$CI =& get_instance();

		$query = $CI->db->select('company_date_format')->from('company')->where('company_id',$CI->session->userdata('company_id'))->get();
		$res = $query->row();
		if($res){
			$format = $res->company_date_format;
			if($format){
				return $format;
			} else {
				$site_data = site_setting();
				return $site_data->date_format;
			}
		} else {
			$site_data = site_setting();
			return $site_data->date_format;
		}
	}

	function total_task($section_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('task_id')->from('tasks')->where('task_owner_id != ',"0")->where('task_allocated_user_id != ',"0")->where('section_id',$section_id)->or_where('subsection_id',$section_id)->get();
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function total_sub_task($subsection_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('task_id')->from('tasks')->where('is_deleted','0')->where('subsection_id',$subsection_id)->or_where('section_id',$subsection_id)->get();

		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function checkSectionName($project_id,$section_name,$section_id='')
	{
		$CI =& get_instance();
                
		$CI->db->select('section_id');
                $CI->db->from('project_section');
                $CI->db->where('section_name',$section_name);
                if($section_id!=''){
                    $CI->db->where('main_section',$section_id);
                }
                $CI->db->where('project_id',$project_id);
                $query = $CI->db->get();

		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function notification()
	{
                $start = isset($_POST['start']) &&  $_POST['start']!='' ? $_POST['start'] : 0;
                $limit = isset($_POST['limit']) &&  $_POST['limit']!='' ? $_POST['limit'] : 30;
		$CI =& get_instance();
		$query = $CI->db->select("tn.*,p.project_title,(CASE WHEN tn.notification_from !=0 THEN u.profile_image ELSE '' END ) AS profile_image,(CASE WHEN tn.notification_from !=0 THEN u.first_name ELSE '' END ) AS first_name,(CASE WHEN tn.notification_from !=0 THEN u.last_name ELSE '' END ) AS last_name,t.task_title,(CASE WHEN tn.task_id != 0 THEN t.master_task_id ELSE 0 END) AS master_task_id")
					->from('task_notification tn')
					->join('tasks t','t.task_id = tn.task_id','left')
					->join('project p','p.project_id = tn.project_id','left')
					->join('project_users pu','tn.project_id = pu.project_id','left')
					->join('users u','tn.notification_from = u.user_id','left')
					->where(array('tn.notification_user_id'=>get_authenticateUserID(),'tn.is_deleted <>'=>'1'))
					->where("(CASE WHEN tn.task_id!=0 THEN (CASE WHEN t.task_owner_id != 0 AND t.task_allocated_user_id != 0 THEN '1' ELSE '0' END)  ELSE '1' END)='1'")
					->order_by('tn.task_notification_id','DESC')
					->group_by('tn.task_notification_id')
					->limit($limit,$start)
					->get();
		if($query->num_rows() > 0)
		{
			$result1= $query->result_array();
			$result = $query->result_array();
                        
                        $i =0;
			 foreach($result as $r)
			 {
			 	  $date = $r["date_added"];
				  $result1[$i]["date_added"] = $date;
				  if($result1[$i]['master_task_id']){
					$is_master_deleted = chk_master_task_id_deleted($result1[$i]['master_task_id']);
				} else {
					$is_master_deleted = 0;
				}
				$result1[$i]["is_master_deleted"] = $is_master_deleted;
				$result1[$i]["is_chk"] = chk_task_exists($result1[$i]['task_id']);
				  $i++;
			 }
                         
			return (object) $result1;
		}else{
			return 0;
		}
                
	}
	
	function Letestnotification()
	{
		$main_date = date("Y-m-d H:i:s");
	    $main_date_add15 = date("Y-m-d H:i:s",strtotime("-15 minutes", strtotime($main_date)));

		$CI =& get_instance();
		//$time_cond = "date_added >= '".$main_date."' AND date_added <  NOW() - INTERVAL 15 MINUTE ";
		$time_cond = "date_added BETWEEN '".$main_date_add15."' and '".$main_date."' ";
		$query = $CI->db->select("tn.*,p.project_title,(CASE WHEN tn.notification_from !=0 THEN u.profile_image ELSE '' END ) AS profile_image,(CASE WHEN tn.notification_from !=0 THEN u.first_name ELSE '' END ) AS first_name,(CASE WHEN tn.notification_from !=0 THEN u.last_name ELSE '' END ) AS last_name,t.task_title,(CASE WHEN tn.task_id != 0 THEN t.master_task_id ELSE 0 END) AS master_task_id")
					->from('task_notification tn')
					->join('tasks t','t.task_id = tn.task_id','left')
					->join('project p','p.project_id = tn.project_id','left')
					->join('project_users pu','tn.project_id = pu.project_id','left')
					->join('users u','tn.notification_from = u.user_id','left')
					//->where(array('tn.notification_user_id'=>get_authenticateUserID(),'tn.is_read'=>'0','tn.is_deleted <>'=>'1'))->where($time_cond)
					->where(array('tn.notification_user_id '=>get_authenticateUserID(),'tn.is_read'=>'0','tn.is_deleted <>'=>'1'))
					->where("(CASE WHEN tn.task_id!=0 THEN (CASE WHEN t.task_owner_id != 0 AND t.task_allocated_user_id != 0 THEN '1' ELSE '0' END)  ELSE '1' END)='1'")
					->where($time_cond)
					->order_by('tn.task_notification_id','DESC')
					->group_by('tn.task_notification_id')
				//->limit(2)
					->get();

		if($query->num_rows() > 0)
		{
			$result1= $query->result_array();
			$result = $query->result_array();
                       
			 $i =0;
			 foreach($result as $r)
			 {
			 	 	$result1[$i]["date_added"] = time_ago($r["date_added"]);
				 
				 	$CI->load->library('s3');
					$CI->config->load('s3');
					$bucket = $CI->config->item('bucket_name');
					$name = 'upload/user/'.$r['profile_image'];
					$chk = $CI->s3->getObjectInfo($bucket,$name);
					if($chk)
					{
						$result1[$i]["is_img"]= "1";
					} else {
						$result1[$i]["is_img"] = "0";
					}
					if($result1[$i]['master_task_id']){
						$is_master_deleted = chk_master_task_id_deleted($result1[$i]['master_task_id']);
					} else {
						$is_master_deleted = 0;
					}
					$result1[$i]["is_master_deleted"] = $is_master_deleted;
					$result1[$i]["is_chk"] = chk_task_exists($result1[$i]['task_id']);
					
				 $i++;
			 }
			return (object) $result1;
		}else{
			return 0;
                    }
	}

	function countnotification()
	{
		$CI =& get_instance();
		$query = $CI->db->select('t.task_title')
					->from('task_notification tn')
					->join('tasks t','t.task_id = tn.task_id','left')
					->join('project p','p.project_id = tn.project_id','left')
					->join('project_users pu','tn.project_id = pu.project_id','left')
					->join('users u','tn.notification_from = u.user_id','left')
					->where(array('tn.notification_user_id'=>get_authenticateUserID(),'tn.is_read'=>'0','tn.is_deleted <>'=>'1'))
					->where('t.task_owner_id != ',"0")
					->where('t.task_allocated_user_id != ',"0")
					->order_by('tn.task_notification_id','DESC')
					->group_by('tn.task_notification_id')
					->get();

                $CI->db->select('tn.*,u.profile_image,u.first_name,u.last_name');
                $CI->db->from('task_notification tn');
                $CI->db->where('tn.timesheet_notification','1');
                $CI->db->join('users u','tn.notification_from = u.user_id','left');
                $CI->db->where('tn.notification_user_id',  get_authenticateUserID());
                $CI->db->where('tn.is_deleted','0');
                $CI->db->where('tn.is_read','0');
                $query1 = $CI->db->get();
                if($query1->num_rows()>0){
                    $row = $query1->num_rows();
                }else{
                    $row = 0;
                }
		if($query->num_rows() > 0)
		{
                        $total = $query->num_rows() + $row;
			return $total;
		}else{
			return 0 +$row;
		}
	}

	function gettaskbyid($task_id)
	{
		if (strpos($task_id, 'child_') !== false) {
		   	$task_id = explode('_', $task_id);
			$task_id = $task_id[1];
		}else{
			$task_id = $task_id;
		}
		
		$CI =& get_instance();
		$query = $CI =& get_instance();
		$CI->db->select('t.*,u.first_name,u.last_name,p.project_title,ps.section_name,uts.swimlane_id,uts.color_id,uts.kanban_order,uts.calender_order,uts.task_ex_pos');
		$CI->db->from('tasks t');
		$CI->db->join('users u','u.user_id = t.task_owner_id','left');
		$CI->db->join('user_task_swimlanes uts','uts.task_id = t.task_id and uts.user_id = t.task_allocated_user_id','left');
		$CI->db->join('project p','p.project_id = t.task_project_id','left');
		$CI->db->join('project_section ps','ps.section_id = t.subsection_id','left');
		$CI->db->where('t.task_owner_id != ','0');
		$CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
		$CI->db->where('t.task_allocated_user_id != ','0');
		$CI->db->where('t.task_id',$task_id);
		$query = $CI->db->get();
		
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}else{
			return '';
		}
	}
/**
 * It get returns task comments from DB.
 * @param int $cmt_id
 * @returns string|int
 */
function getComment($cmt_id)
{
	$CI =& get_instance();
		$query = $CI->db->select('task_comment')->from('task_and_project_comments')->where('task_comment_id',$cmt_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_comment;
		} else {
			return 0;
		}
}

function get_notificationdetail($id)
{
		$CI =& get_instance();
		$query = $CI->db->select('tn.*,t.task_title,p.project_title,u.profile_image,u.first_name,u.last_name')
					->from('task_notification tn')
					->join('tasks t','t.task_id = tn.task_id','left')
					->join('project p','p.project_id = tn.project_id','left')
					->join('project_users pu','tn.project_id = pu.project_id','left')
					->join('users u','tn.notification_from = u.user_id','left')
					->where(array('tn.task_notification_id'=>$id,'tn.is_deleted <>'=>'1'))
					->where('t.task_owner_id != ',"0")
					->where('t.task_allocated_user_id != ',"0")
					->get();

		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}else{
			return '';
		}
}
/**
 * This function get company default working name from DB.
 * @returns string
 */
	function get_company_offdays($company_id=0){
		$CI =& get_instance();
                if($CI->session->userdata("company_id") != "" || $CI->session->userdata("company_id") >0)
		{
			$company_id = $CI->session->userdata("company_id");
		}
		$days = '';
		$query = $CI->db->select('*')
						->from('default_calendar_setting')
						->where('comapny_id',$company_id)
						->get();
		/*
		$query = $CI->db->query("CALL company_offday_count('".$CI->session->userdata('company_id')."')");
				pr($res = $query->row());die;*/
		
		if($query->num_rows()>0){
			$res = $query->row();
			$str = array();
			if($res->MON_closed == '0'){
				$str[]= "1";
			}
			if($res->TUE_closed == '0'){
				$str[]= "2";
			}
			if($res->WED_closed == '0'){
				$str[]= "3";
			}
			if($res->THU_closed == '0'){
				$str[]= "4";
			}
			if($res->FRI_closed == '0'){
				$str[]= "5";
			}
			if($res->SAT_closed == '0'){
				$str[]= "6";
			}
			if($res->SUN_closed == '0'){
				$str[]= "0";
			}
			if($str){
				$days = implode(',', $str);
			}
		}
		return $days;
	}

	function chk_company_offday($date,$offdays){
		if($date){
			$offdays_arr = array();
			
			if($offdays!=''){
				$offdays_arr = explode(',', $offdays);
			}
			for($i=0;$i<7;$i++){
				$day = date('w',strtotime(str_replace(array("/"," ",","), "-", $date)));
				if(in_array($day,$offdays_arr)){
					$date = date ("Y-m-d", strtotime("-1 days", strtotime(str_replace(array("/"," ",","), "-", $date))));
				} else {
					return $date;
				}
			}
		} else {
			return '';
		}

	}

	function chk_company_working_day_next($date,$offdays){
		if($date){
			$offdays_arr = array();
			//$offdays = get_company_offdays();

			if($offdays!=''){
				$offdays_arr = explode(',', $offdays);
			}
			for($i=0;$i<7;$i++){
				$day = date('w',strtotime(str_replace(array("/"," ",","), "-", $date)));
				if(in_array($day,$offdays_arr)){
					$date = date ("Y-m-d", strtotime("+1 days", strtotime(str_replace(array("/"," ",","), "-", $date))));
				} else {
					return $date;
				}
			}
		} else {
			return '';
		}

	}

	function chk_company_offday_date($date,$offdays){
		if($date){
			$offdays_arr = array();
			
			if($offdays!=''){
				$offdays_arr = explode(',', $offdays);
			}
			for($i=0;$i<7;$i++){
				$day = date('w',strtotime(str_replace(array("/"," ",","), "-", $date)));
				if(in_array($day,$offdays_arr)){
					return 1;
				} else {
					return 0;
				}
			}
		} else {
			return 0;
		}

	}


	function get_project_allocated($project_id,$allocated_user_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('t.task_id')
						->from('tasks t')
						->join('project p','p.project_id = t.task_project_id','left')
						->where(array('t.task_owner_id <>'=>'0','t.task_allocated_user_id <>'=>'0','t.is_deleted <>'=>'1','t.task_project_id'=>$project_id,'task_allocated_user_id'=>$allocated_user_id))
						->get();
		if($query->num_rows() > 0)
		{
			return $query->num_rows();
		}else{
			return 0;
		}
	}

	function hour_minute_formate($total_estimate, $total_spent){
		$total_task_time_estimate_minute_1 = $total_estimate;
		$estimate_hours_1 = intval($total_task_time_estimate_minute_1/60);
		$estimate_minutes_1 = $total_task_time_estimate_minute_1 - ($estimate_hours_1 * 60);


		$total_task_time_spent_minute_1 = $total_spent;
		$spent_hours_1 = intval($total_task_time_spent_minute_1/60);
		$spent_minutes_1 = $total_task_time_spent_minute_1 - ($spent_hours_1 * 60);

		if($estimate_hours_1 != '0'){	$e_h_1 = $estimate_hours_1.'h'; } else { $e_h_1 = ''; }

		if($estimate_minutes_1 != '0'){ $e_m_1 = $estimate_minutes_1.'m'; }else{ $e_m_1 = ''; }

		if($spent_hours_1 != '0'){	$s_h_1 = $spent_hours_1.'h'; } else { $s_h_1 = ''; }

		if($spent_minutes_1 != '0'){ $s_m_1 = $spent_minutes_1.'m'; }else{ $s_m_1 = ''; }

		if($e_h_1 == '' && $e_m_1 == ''){
			$est_1 = '0m';
		} elseif($e_h_1 !='' && $e_m_1 == ''){
			$est_1 = $e_h_1;
		} elseif($e_h_1 =='' && $e_m_1 != ''){
			$est_1 = $e_m_1;
		} else {
			$est_1 = $e_h_1.''.$e_m_1;
		}

		if($s_h_1 == '' && $s_m_1 == ''){
			$spt_1 = '0m';
		} elseif($s_h_1 !='' && $s_m_1 == ''){
			$spt_1 = $s_h_1;
		} elseif($s_h_1 =='' && $s_m_1 != ''){
			$spt_1 = $s_m_1;
		} else {
			$spt_1 = $s_h_1.''.$s_m_1;
		}

		return $est_1.'/'.$spt_1;

	}

	//  Task order function for section , subsection and task

	function get_section_order_by_project($project_id='0',$section_id='0',$subsection_id='0')
	{


		//if($section_id =='0'){

			$CI =& get_instance();
			$CI->db->select('MAX(t.section_order) as sec');
			$CI->db->from('project_section t');
			$CI->db->where('t.project_id',$project_id);
			$CI->db->where('t.main_section ','0');
			//$CI->db->group_by('t.section_id');
			$query = $CI->db->get();

			//echo $CI->db->last_query();die;

			if($query->num_rows()>0){
				$res = $query->row();
				return $res->sec+1;
			}else{
				$res = $query->row();
				return $res->sec;
			}

	}

	function get_section_order_by_section($project_id,$section_id)
	{
		$CI =& get_instance();
		$CI->db->select('t.section_order as sec');
		$CI->db->from('project_section t');
		$CI->db->where('t.project_id',$project_id);
		$CI->db->where('t.section_id ',$section_id);
		$query = $CI->db->get();

		//echo $CI->db->last_query();die;

		if($query->num_rows()>0){
				$res = $query->row();
				return $res->sec;
			}else{
				$res = $query->row();
				return 1;
			}
	}


	function get_sub_section_order_by_project($project_id='0',$section_id='0',$subsection_id='0')
	{
		$CI =& get_instance();
		$CI->db->select('MAX(t.subsection_order) as sec');
		$CI->db->from('project_section t');
		$CI->db->where('t.project_id',$project_id);
		$CI->db->where('t.main_section',$section_id);
		//$CI->db->where('t.subsection_id ',$subsection_id);
		$query = $CI->db->get();

		//echo $CI->db->last_query();die;

		if($query->num_rows()>0){
			$res = $query->row();
			return $res->sec+1;
		} else {
			$res = $query->row();
			return 1;



		}
	}

	function get_task_order_by_project($project_id='0',$section_id='0',$subsection_id='0')
	{
		$CI =& get_instance();
		$CI->db->select('t.task_order as sec');
		$CI->db->from('tasks t');
		$CI->db->where('t.task_project_id',$project_id);
		$CI->db->where('t.section_id ',$section_id);
		$CI->db->where('t.task_owner_id != ',"0");
		$CI->db->where('t.task_allocated_user_id != ',"0");
		$CI->db->where('t.subsection_id ',$subsection_id);
		$query = $CI->db->get();

		if($query->num_rows()>0){
			$res = $query->row();
			return ($res->sec!='')?($res->sec+1):'1';
		}else{

			if($section_id == '0'){

				$CI =& get_instance();
				$CI->db->select('max(t.task_order) as sec');
				$CI->db->from('tasks t');
				$CI->db->where('t.task_owner_id != ',"0");
				$CI->db->where('t.task_allocated_user_id != ',"0");
				$CI->db->where('t.task_project_id',$project_id);
				$CI->db->where('t.section_id ',$section_id);
				$CI->db->where('t.subsection_id ',$subsection_id);
				$query = $CI->db->get();

				//echo $CI->db->last_query();die;

				if($query->num_rows()>0){
					$res = $query->row();
					return ($res->sec!='')?($res->sec+1):'1';
				}else{
					$res = $query->row();
					return '1';
				}
			}else{
				return '1';
			}
		}


	}

	// Task order function of section , subsection and task finish here



	// Reports
	function get_company_projects($company_id){
		$CI =& get_instance();
		$query = $CI->db->select('project_id,project_title')->from('project')->where('company_id',$company_id)->where('project_status','Open')->where('is_deleted','0')->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_order($task_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('task_id,task_order')->from('tasks')->where('task_owner_id !=','0')->where('task_allocated_user_id !=','0')->where('task_id',$task_id)->get();

		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}

	function get_taskDetail($section_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('task_id,task_order')->from('tasks')->where('task_owner_id !=','0')->where('task_allocated_user_id !=','0')->where('subsection_id',$section_id)->get();

		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
	function get_task_original_order($section_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('section_order')->from('project_section')->where('section_id',$section_id)->get();

		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->row()->section_order;
		} else {
			return 0;
		}
	}

	function get_section_order($section_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('section_order')->from('project_section')->where('main_section',$section_id)->get();

		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->row()->section_order;
		} else {
			return 0;
		}
	}

	function no_of_users_in_division($division_id){
		$CI =& get_instance();
		$query = $CI->db->select('ud.user_id')
						->from('user_devision ud')
						->join('users u','u.user_id = ud.user_id','left')
						->where('ud.devision_id',$division_id)
						->where('u.user_status','Active')
						->where('u.is_deleted','0')
						->get();
		return $query->num_rows();
	}
	function get_division_name($division_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('ud.devision_title')
						->from('user_devision ud')
						->where('ud.devision_id',$division_id)
						->get();
		return $query->row()->devision_title;
	}
	function no_of_users_in_department($dept_id){
		$CI =& get_instance();
		$query = $CI->db->select('ud.user_id')
						->from('user_department ud')
						->join('users u','u.user_id = ud.user_id','left')
						->where('ud.dept_id',$dept_id)
						->where('u.user_status','Active')
						->where('u.is_deleted','0')
						->get();
		return $query->num_rows();
	}

	function get_UserTimeZone($id = 0)
	{
		$CI =& get_instance();
		if($CI->session->userdata("user_id") != "" || $CI->session->userdata("user_id") >0)
		{
			$id = $CI->session->userdata("user_id");
		}

		$CI->db->select("u.user_time_zone,c.company_timezone");
		$CI->db->from("users u");
		$CI->db->join("company c","u.company_id= c.company_id","LEFT");
		$CI->db->where("u.user_id",$id);

		$qry = $CI->db->get();



		if($qry->num_rows()>0)
		{
			 $result = $qry->row();



			 if($result->user_time_zone!= "")
			 {

			 	return $result->user_time_zone;
			 } elseif($result->company_timezone!= ""){
			 	return $result->company_timezone;
			 } else {
			 	return date_default_timezone_get();
			 }

		}


		return date_default_timezone_get();
	}

	function get_TimezoneOffset($name){
		$dtz = new DateTimeZone($name);
		$time_in_sofia = new DateTime('now', $dtz);
		//echo $dtz->getOffset( $time_in_sofia )."====>";
		//echo $dtz->getOffset( $time_in_sofia );die;
		$offset = $dtz->getOffset( $time_in_sofia ) / 3600;

		if($offset<0){
			if(is_float($offset)){
				$offset_arr = explode(".", $offset);
				$offset = $offset_arr[0].":".$offset_arr[1]."0";
			} else {
				$offset = $offset.":00";
			}

		} else {
			if(is_float($offset)){
				$offset_arr = explode(".", $offset);
				$offset = "+".$offset_arr[0].":".$offset_arr[1]."0";
			} else {
				$offset = "+".$offset.":00";
			}

		}

		return $offset;


		//echo "GMT" . ($offset < 0 ? $offset : "+".$offset);die;
	}

	function get_date_withuserTimeZone($date = '')
	{

		  $CI =& get_instance();
		   if($date == '')
		   {
		   	  $date =  date("Y-m-d H:i:s");
		   }

		//echo $CI->session->userdata("User_timezone"); die;

			$dt = new DateTime($date, new DateTimeZone("UTC"));
		    $dt->format('r') . PHP_EOL;
		//	echo "<br>";
			$dt->setTimezone(new DateTimeZone($CI->session->userdata("User_timezone")));
			return  $dt->format('Y-m-d H:i:s');

	}

	function getImageName($user_id)
	{
		$CI =& get_instance();
		$CI->db->select("u.profile_image");
		$CI->db->from("users u");
		$CI->db->where("u.user_id",$user_id);
		$qry = $CI->db->get();
		if($qry->num_rows()>0)
		{
			 $result = $qry->row();
			 return $result->profile_image;
		}
		else{
			return "";
		}

	}

	function get_kanbanTasks($task_status,$limit, $offset)
	{

		
		$CI =& get_instance();
		$CI->db->select("t.*");
		$CI->db->from("tasks t");
		$CI->db->where('t.task_allocated_user_id',$CI->session->userdata('user_id'));
		$CI->db->where('t.task_owner_id != ',"0");
		$CI->db->where('t.task_allocated_user_id != ',"0");
		$CI->db->where('t.task_status_id',$task_status);
		$CI->db->where('t.is_deleted','0');
		$CI->db->limit($limit,$offset);
		$qry = $CI->db->get();
		//	echo $CI->db->last_query();
		if($qry->num_rows()>0)
		{
			 $result = $qry->result();
			 return $result;
		}
		else{
			return "";
		}
	}

	function getOffset($timezone){
		$CI =& get_instance();
		$query = $CI->db->select('standard_offset')->from('timezone')->where('timezone_name',$timezone)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->standard_offset;
		} else {
			return 0;
		}
	}

	function toDateUTC($unixTimestamp){
		$CI =& get_instance();
		$date = new DateTime($unixTimestamp, new DateTimeZone($CI->session->userdata("User_timezone")));
		$date->setTimezone(new DateTimeZone("UTC"));
		$new_date = $date->format("Y-m-d");
		return $new_date;
	}

	function toDateTime($unixTimestamp){
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$date = new DateTime($unixTimestamp." ".date("H:i:s"));
		$date->setTimezone(new DateTimeZone("UTC"));
		$new_date = $date->format("Y-m-d");
		date_default_timezone_set("UTC");
		$time = strtotime($new_date." 00:00:00");
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		return $time;
	}

	function toDate($unixTimestamp){
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$date = new DateTime($unixTimestamp." ".date("H:i:s"));
		$date->setTimezone(new DateTimeZone("UTC"));
		$new_date = $date->format("Y-m-d");
		return $new_date;

	}

	function toDateNewTime($unixTimestamp){
		$CI =& get_instance();
		date_default_timezone_set("UTC");
		$date = new DateTime($unixTimestamp);
		$date->setTimezone(new DateTimeZone($CI->session->userdata("User_timezone")));
		return $date->format("Y-m-d H:i:s");

	}

	function toDateUserTimeStamp($unixTimestamp){
		$CI =& get_instance();
		//echo $unixTimestamp;
		date_default_timezone_set("UTC");
		$date = new DateTime($unixTimestamp." ".date("H:i:s"));
		//pr($date);
		$date->setTimezone(new DateTimeZone($CI->session->userdata("User_timezone")));
		//pr($date);
		$new_date = $date->format("Y-m-d");
		//date_default_timezone_set($CI->session->userdata("User_timezone"));
		//echo $new_date;
		$time = strtotime($new_date." 00:00:00");
		//echo $time;
		//date_default_timezone_set("UTC");

		return $time;

	}

	function toDateUser($unixTimestamp){
		$CI =& get_instance();
		//echo $unixTimestamp;
		date_default_timezone_set("UTC");
		$date = new DateTime($unixTimestamp." ".date("H:i:s"));
		$date->setTimezone(new DateTimeZone($CI->session->userdata("User_timezone")));
		$new_date = $date->format("Y-m-d");
	    return $new_date;

	}

	function TestTimezone($unixTimestamp){
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$date = new DateTime( $unixTimestamp);
		$date->setTimezone(new DateTimeZone("UTC"));
		return $date->getTimestamp();

	}

	function TestTimezone1($unixTimestamp){
		$CI =& get_instance();
		date_default_timezone_set($CI->session->userdata("User_timezone"));
		$date = new DateTime( $unixTimestamp);
		//pr($date);
	//	$date->setTimezone(new DateTimeZone("UTC"));
		//pr($date);

		//new code//
	//	$date->setTimezone(new DateTimeZone("UTC"));
		$dt = new DateTime($unixTimestamp, new DateTimeZone("UTC"));
		    $dt->format('r') . PHP_EOL;
		//	echo "<br>";

		//pr($dt);
			//$dt->setTimezone(new DateTimeZone($CI->session->userdata("User_timezone")));
		var_dump($dt->format('Y-m-d H:i:s')); die;

		return $date->getTimestamp();

	}

	function get_date_withsystemTimeZone($date = '')
	{
		//echo $date; die;

		  $CI =& get_instance();
		   if($date == '')
		   {
		   	  $date =  date("Y-m-d H:i:s");
		   }

		//echo $CI->session->userdata("User_timezone"); die;

			$dt = new DateTime($date, new DateTimeZone($CI->session->userdata("User_timezone")));
		    $dt->format('r') . PHP_EOL;
			//echo "dd";
		//	echo getOffset($CI->session->userdata("User_timezone"));

			//echo $dt->format('Y-m-d H:i:s');
			//pr($dt);
			//die;
		//	echo "<br>";
			$dt->setTimezone(new DateTimeZone("UTC"));

			//echo "dd";
			//pr($dt);
			//die;
		//	echo $dt->format('Y-m-d'); die;


		//	return date('Y-m-d 00:00:00', strtotime($dt->format('Y-m-d H:i:s')) - 60 * 60 * 0);

			return  $dt->format('Y-m-d');

	}

	function user_first_login_date(){
		$user_id = get_authenticateUserID();

		$CI =& get_instance();

		$query = $CI->db->select('user_login_date')->from('user_login_history')->where('user_id',$user_id)->order_by('user_login_history_id','asc')->limit(1)->get();
		//echo $CI->db->last_query();die;
		if($query->num_rows()>0){
			$res = $query->row();
			$date = $res->user_login_date;
			return date("Y-m-d",strtotime($date));
		} else {
			return 0;
		}
	}

	function countoverduetasks($offdays,$completed_id){

		$CI =& get_instance();

		date_default_timezone_set($CI->session->userdata("User_timezone"));

		$end_date = date('Y-m-d');

		$start_date = user_first_login_date();
		$query = $CI->db->select("task_id,task_due_date,task_status_id,task_allocated_user_id,frequency_type,recurrence_type,start_on_date,no_end_date,end_after_recurrence,task_orig_scheduled_date,task_scheduled_date,task_due_date,master_task_id,task_orig_due_date,task_allocated_user_id,Daily_every_weekday,Daily_every_week_day,Daily_every_day,end_by_date,Weekly_week_day,Weekly_every_week_no,Monthly_op1_1,Monthly_op1_2,Monthly_op2_1,Monthly_op2_2,Monthly_op2_3,Monthly_op3_1,Monthly_op3_2,Yearly_op1,Yearly_op2_1,Yearly_op2_2,Yearly_op3_1,Yearly_op3_2,Yearly_op3_3,Yearly_op4_1,Yearly_op4_2,is_deleted")
						->from("tasks")
						->where("master_task_id","0")
						->where('task_company_id',$CI->session->userdata('company_id'))
						->where("task_owner_id != ","0")
						->where("task_allocated_user_id != ","0")
						->where("is_deleted","0")
						->get();

		if($query->num_rows()>0){
			$res = $query->result_array();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){
						$re_data = monthly_recurrence_logic($row,$start_date,$end_date,$offdays);
						//pr($re_data);die;
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists_myoverdue($row2['master_task_id'],$row2['task_orig_scheduled_date']);

								if($chk_rec){
									if($chk_rec['task_due_date']>= $start_date && $chk_rec['task_due_date'] < $end_date && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['task_status_id'] != $completed_id && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_due_date']>= $start_date && $row2['task_due_date'] < $end_date && $row2['task_allocated_user_id'] == get_authenticateUserID() && $row2['task_status_id'] != $completed_id){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row['task_status_id'] != $completed_id && $row['task_allocated_user_id'] == get_authenticateUserID() && $row['task_due_date']>= $start_date && $row['task_due_date'] < $end_date && $row['task_due_date'] != '0000-00-00'){
							array_push($task_list,$row);
						}
					}
				}
			}

			return count($task_list);
		} else {
			return 0;
		}
	}

	function chk_virtual_recurrence_exists_myoverdue($master_task_id,$task_orig_scheduled_date){
		$CI =& get_instance();
		$query = $CI->db->select("task_id,task_due_date,task_status_id,task_allocated_user_id,task_time_estimate,frequency_type,recurrence_type,start_on_date,no_end_date,end_after_recurrence,task_orig_scheduled_date,task_scheduled_date,task_due_date,master_task_id,task_orig_due_date,task_allocated_user_id,Daily_every_weekday,Daily_every_week_day,Daily_every_day,end_by_date,Weekly_week_day,Weekly_every_week_no,Monthly_op1_1,Monthly_op1_2,Monthly_op2_1,Monthly_op2_2,Monthly_op2_3,Monthly_op3_1,Monthly_op3_2,Yearly_op1,Yearly_op2_1,Yearly_op2_2,Yearly_op3_1,Yearly_op3_2,Yearly_op3_3,Yearly_op4_1,Yearly_op4_2,is_deleted")
						->from("tasks")
						->where("master_task_id",$master_task_id)
						->where("task_owner_id != ","0")
						->where("task_allocated_user_id != ","0")
						->where('task_orig_scheduled_date',$task_orig_scheduled_date)
						->where('task_company_id',$CI->session->userdata('company_id'))
						->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	}
	

	function countplannedhours($completed_id,$offdays){

		$CI =& get_instance();

		date_default_timezone_set($CI->session->userdata("User_timezone"));

		$start_date = date("Y-m-d");
		$end_date = date("Y-m-d",strtotime("+4 days"));


		$query = $CI->db->select("task_id,task_time_estimate,frequency_type,recurrence_type,start_on_date,no_end_date,end_after_recurrence,task_orig_scheduled_date,task_scheduled_date,task_due_date,master_task_id,task_orig_due_date,task_allocated_user_id,Daily_every_weekday,Daily_every_week_day,Daily_every_day,end_by_date,Weekly_week_day,Weekly_every_week_no,Monthly_op1_1,Monthly_op1_2,Monthly_op2_1,Monthly_op2_2,Monthly_op2_3,Monthly_op3_1,Monthly_op3_2,Yearly_op1,Yearly_op2_1,Yearly_op2_2,Yearly_op3_1,Yearly_op3_2,Yearly_op3_3,Yearly_op4_1,Yearly_op4_2,is_deleted")
						->from("tasks")
						->where("master_task_id","0")
						->where("task_status_id !=",$completed_id)
						->where('task_company_id',$CI->session->userdata('company_id'))
						->where("task_owner_id != ","0")
						->where("task_allocated_user_id != ","0")
						->where("is_deleted","0")
						->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){
						$re_data = monthly_recurrence_logic($row,$start_date,$end_date,$offdays);
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists_myoverdue($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date']>= $start_date && $chk_rec['task_scheduled_date'] <= $end_date && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date']>= $start_date && $row2['task_scheduled_date'] <= $end_date && $row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row['task_allocated_user_id'] == get_authenticateUserID() && $row['task_scheduled_date']>= $start_date && $row['task_scheduled_date'] <= $end_date && $row['task_scheduled_date'] != '0000-00-00'){
							array_push($task_list,$row);
						}
					}
				}
			}
			$total_time = 0;
			if($task_list)
			{
				foreach($task_list as $t)
				{
					$total_time += $t['task_time_estimate'];
				}
			}
			return intval($total_time/60);
		} else {
			return 0;
		}
	}

	function countbacklogtasks(){
		$CI =& get_instance();

		$completed_id = $CI->config->item('completed_id');
		$ready_id = get_task_status_id_by_name("Ready");

		$query = $CI->db->select("task_id")
						->from("tasks")
						->where("task_status_id !=",$completed_id)
						->where("task_status_id !=",$ready_id)
						->where("task_owner_id != ","0")
						->where("task_allocated_user_id != ","0")
						->where('task_company_id',$CI->session->userdata('company_id'))
						->where("task_allocated_user_id",get_authenticateUserID())
						->where("task_scheduled_date","0000-00-00")
						->where("is_deleted","0")
						->get();
		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}

	}

	function countremaingtask($completed_id,$offdays){

		$CI =& get_instance();

		date_default_timezone_set($CI->session->userdata("User_timezone"));

		$d = strtotime("today");
		$start_week = strtotime("last sunday midnight",$d);
		$end_week = strtotime("next saturday",$d);
		$start_date = date("Y-m-d",$start_week);
		$end_date = date("Y-m-d",$end_week);

		$query = $CI->db->select("task_id,task_time_estimate,frequency_type,recurrence_type,start_on_date,no_end_date,end_after_recurrence,task_orig_scheduled_date,task_scheduled_date,task_due_date,master_task_id,task_orig_due_date,task_allocated_user_id,Daily_every_weekday,Daily_every_week_day,Daily_every_day,end_by_date,Weekly_week_day,Weekly_every_week_no,Monthly_op1_1,Monthly_op1_2,Monthly_op2_1,Monthly_op2_2,Monthly_op2_3,Monthly_op3_1,Monthly_op3_2,Yearly_op1,Yearly_op2_1,Yearly_op2_2,Yearly_op3_1,Yearly_op3_2,Yearly_op3_3,Yearly_op4_1,Yearly_op4_2,is_deleted")
						->from("tasks")
						->where("master_task_id","0")
						->where("task_status_id !=",$completed_id)
						->where("task_owner_id != ","0")
						->where('task_company_id',$CI->session->userdata('company_id'))
						->where("task_allocated_user_id != ","0")
						->where("is_deleted","0")
						->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row['frequency_type'] == 'recurrence' && $row['recurrence_type']!='0'){
						$re_data = monthly_recurrence_logic($row,$start_date,$end_date,$offdays);
						if($re_data){
							foreach($re_data as $row2){
								$chk_rec = chk_virtual_recurrence_exists_myoverdue($row2['master_task_id'],$row2['task_orig_scheduled_date']);
								if($chk_rec){
									if($chk_rec['task_scheduled_date']>= $start_date && $chk_rec['task_scheduled_date'] <= $end_date && $chk_rec['task_allocated_user_id'] == get_authenticateUserID() && $chk_rec['is_deleted'] == "0"){
										array_push($task_list,$chk_rec);
									}
								} else {
									if($row2['task_scheduled_date']>= $start_date && $row2['task_scheduled_date'] <= $end_date && $row2['task_allocated_user_id'] == get_authenticateUserID()){
										array_push($task_list,$row2);
									}
								}
							}
						}
					} else {
						if($row['task_allocated_user_id'] == get_authenticateUserID() && $row['task_scheduled_date']>= $start_date && $row['task_scheduled_date'] <= $end_date && $row['task_scheduled_date'] != '0000-00-00'){
							array_push($task_list,$row);
						}
					}
				}
			}
			$total_time = 0;
			if($task_list)
			{
				foreach($task_list as $t)
				{
					$total_time += $t['task_time_estimate'];
				}
			}
			$pass_data = array();
			$pass_data['tasks'] = count($task_list);
			$pass_data['time'] = intval($total_time/60);
			return $pass_data;
		} else {
			return 0;
		}
	}


	function getTaskListFromProjectId($project_id){
		$CI =& get_instance();
		if($project_id){
			$query = $CI->db->select("t.*,ts.task_status_name")
							->from("tasks t")
							->join("project p","p.project_id = t.task_project_id")
							->join("task_status ts","ts.task_status_id = t.task_status_id")
							->where('t.task_owner_id != ','0')
							->where('t.task_allocated_user_id != ','0')
							->where('t.task_company_id',$CI->session->userdata('company_id'))
							->where('t.task_project_id',$project_id)
							->where('t.is_deleted','0')
							->get();
			if($query->num_rows()>0){
				return $query->result_array();
			} else {
				return 0;
			}
		}
	}
	
	function check_popup()
	{
		$CI =& get_instance();
		
		$query = $CI->db->select('*')
						->from('user_setup')
						->where('us_user_id',$CI->session->userdata('user_id'))
						->get();
						
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	}
	
	function get_user_setup_steps()
	{
		$CI =& get_instance();
		
		$query = $CI->db->select('step_id')
						->from('user_setup')
						->where('us_user_id',$CI->session->userdata('user_id'))
						->get();
						
		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $row){
				$ids[] = $row['step_id'];
			}
			return $ids;
		} else {
			return 0;
		}
	}
	
	function get_admin_setup($type,$ignore)
	{
		
		$CI =& get_instance();
		
		$ids = '0';
		if($ignore!='0'){
			$ids = join(',',$ignore);
		}
		$query = $CI->db->select('ps.*,us.step_id')
						->from('popup_setup ps')
						->join('user_setup us','ps.as_step_id != us.step_id')
						->where(array('ps.as_type'=>$type,'ps.is_deleted'=>'0','ps.as_step_status'=>'Active'))
						->where('ps.as_step_id not in ('.$ids.')') 
						->order_by('ps.as_step_sequence','ASC')
						->group_by('ps.as_step_id')
						->get();
						
								
		if($query->num_rows()>0){
			return $query->result();
		} else {
			$query = $CI->db->select('ps.*')
						->from('popup_setup ps')
						->where(array('ps.as_type'=>$type,'ps.is_deleted'=>'0','ps.as_step_status'=>'Active'))
						->where('ps.as_step_id not in ('.$ids.')')
						->order_by('ps.as_step_sequence','ASC')
						->group_by('ps.as_step_id')
						->get();
						return $query->result();
		}
		
	}
	
	function checkstepExist($user_id,$step_id)
	{
		$CI =& get_instance();
		$query = $CI->db->from('user_setup')->where(array('us_user_id'=>$user_id,'step_id'=>$step_id))->get();
		
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
	}
	
	function getExistSteps($user_id,$type)
	{
		$CI =& get_instance();
		$query = $CI->db->select('step_id')->from('user_setup')->where(array('us_user_id '=>$user_id))->group_by('step_id')->get();
		
		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $row){
				$ids[] = $row['step_id'];
			}
			$steps_ids = join(',',$ids);
			$query_new = $CI->db->select('ps.as_step_id')
						->from('popup_setup ps')
						->where(array('ps.as_type'=>$type,'ps.is_deleted'=>'0','ps.as_step_status'=>'Active'))
						->where('ps.as_step_id not in ('.$steps_ids.')') 
						->get();
						
			return $query_new->result();
		} else {
			return 0;
		}
	}
	/*function get_user_setup()
	{
		$CI =& get_instance();
		$ignore = get_user_setup_steps();
		$ids = '0';
		if($ignore!='0'){
			$ids = join(',',$ignore);
		}
		$query = $CI->db->select('ps.*,us.step_id')
						->from('popup_setup ps')
						->join('user_setup us','ps.as_step_id != us.step_id')
						->where(array('ps.as_type'=>'User','ps.is_deleted'=>'0','ps.as_step_status'=>'Active'))
						->where('ps.as_step_id not in ('.$ids.')')
						->order_by('ps.as_step_sequence','ASC')
						->group_by('ps.as_step_id')
						->get();
								
		if($query->num_rows()>0){
			return $query->result();
		} else {
			$query = $CI->db->select('ps.*')
						->from('popup_setup ps')
						->where(array('ps.as_type'=>'User','ps.is_deleted'=>'0','ps.as_step_status'=>'Active'))
						->where('ps.as_step_id not in ('.$ids.')')
						->order_by('ps.as_step_sequence','ASC')
						->group_by('ps.as_step_id')
						->get();
						return $query->result();
		}
	}*/
	
	function get_maintenance_detail()
	{
		$CI =& get_instance();
		$query = $CI->db->where('status','Active')->get('maintenance_setup');
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
		
	}
	
	function getUserSignupDateTime(){
		date_default_timezone_set("UTC");
		$CI =& get_instance();
		$query = $CI->db->select("signup_date")->from("users")->where("user_id",get_authenticateUserID())->get();
		if($query->num_rows()>0){
			$res = $query->row();
			if($res->signup_date != "0000-00-00 00:00:00"){
				return strtotime($res->signup_date);
			} else {
				return strtotime(date("Y-m-d H:i:s"));
			}
		} else {
			return strtotime(date("Y-m-d H:i:s"));
		}
	}
	
	function addQuotes($string) {
    	return "'". implode("','", explode(",", $string)) ."'";
    }
/**
         * It get company division list
         * @param int $company_id
         * @param string $divisions
         * @returns string
         */
	function get_company_division_list($company_id){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('company_divisions');
		$CI->db->where('company_id',$company_id);
		$CI->db->where('is_delete','0');
		$CI->db->where('devision_status','Active');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			$array_text ='';
			foreach($res as $arr){
				$array_text .= $arr['devision_title'].',';
			}
			return substr($array_text, 0,-1);
		} else {
			return '';
		}
	}
	/**
         * It get company department list
         * @param int $company_id
         * @param string $divisions
         * @returns string
         */
	function get_company_department_list($company_id,$divisions){
		
		$CI =& get_instance();
		
		if($divisions){
			$tags_division = explode(',', $divisions);
			$div_id = array();
			$array_text = array();
			if(isset($tags_division) && $tags_division!=''){
				foreach($tags_division as $row){
					$CI->load->model('user_model');
					$id = $CI->user_model->get_division_id_by_name($row);
					$div_id[] = $id;
				}
			}
		}else{
			$div_id[] = '0';
		}
		
		$CI->db->select('*');
		$CI->db->from('company_departments');
		$CI->db->where('company_id',$company_id);
		$CI->db->where('is_deleted','0');
		$CI->db->where_in('deivision_id',$div_id);
		$CI->db->where('status','Active');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->result_array();
			$array_text ='';
			foreach($res as $arr){
				$array_text .= $arr['department_title'].',';
			}
			return substr($array_text, 0,-1);
		} else {
			return '';
		}
	}
	
	function getColorStep($step_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('seq')
						->from('user_colors')
						->where('user_color_id',$step_id)
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->seq;
		}
	}
	
	function getSwimlaneStep($step_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('seq')
						->from('swimlanes')
						->where('swimlanes_id',$step_id)
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->seq;
		}
	}
	function getTotalTaskByUser()
	{
		$CI =& get_instance();
		$query = $CI->db->select('task_id')
						->from('tasks')
						->where(array('task_owner_id'=>$CI->session->userdata('user_id'),'is_deleted'=>'0','master_task_id'=>'0'))
						->get();
						
		if($query->num_rows()>0){
			return $query->num_rows();
		}else{
			return '0';
		}
	}
        /**
         * It get company name using session data
         * @returns string
         */
	function get_company_name(){
		$CI =& get_instance();
		$CI->db->select('company_name');
		$CI->db->from('company');
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->row()->company_name;
		} else {
			return '';
		}
	}
	/**
         * It also get company name using company_id as parameter
         * @param int $company_id
         * @returns string|int
         */
	function getCompanyName($company_id){
		$CI =& get_instance();
		$query = $CI->db->select('company_name')->from('company')->where('company_id',$company_id)->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->company_name;
		} else {
			return 0;
		}
	}
        /**
         * It get total swimlanes using user_id 
         * @param int $user_id
         * @return int
         */
	function get_total_swimlanes($user_id){
		$CI =& get_instance();
		$CI->db->select("distinct(swimlanes_id)");
		$CI->db->from("swimlanes");
		$CI->db->where(array("user_id"=>$user_id,"is_deleted"=>'0'));
		
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}
	/**
         * It returns total projetcs list 
         * @param int $user_id
         * @returns int
         */
	function get_total_projects($user_id){
		$CI =& get_instance();
		$CI->db->select("distinct(project_id)");
		$CI->db->from("project");
		$CI->db->where(array("project_added_by"=>$user_id,"is_deleted"=>'0'));
		
		$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}
	function check_user_avaibility_by_email($email)
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(user_id) as TOTAL')->where(array("is_deleted"=>"0",'email'=>$email))->get('users')->row()->TOTAL;
	}
	
	function get_default_color($user_id){
		$CI =& get_instance();
		$CI->db->select('default_color');
		$CI->db->from('users');
		$CI->db->where('user_id',$user_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->default_color;
		} else {
			return 0;
		}
	}
	/**
         * It get user password from DB
         * @param string $email
         * @returns array
         */
	function get_user_password($email)
	{
		$CI =& get_instance();
		$query = $CI->db->select('password')
						->from('users')
						->where(array('email'=>$email,'is_deleted'=>'0'))
						->limit(1)
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->password;
		}
	}
	
	function cmp($a, $b) {
		//return strcmp($a->id, $b->id);
		 if($a->updated_at==$b->updated_at) return 0;
    		return $a->updated_at< $b->updated_at?1:-1;
	}
	/**
         * It get tiles order for dashboard.
         * @returns array
         */
	function getTilesOrder()
	{
		$CI =& get_instance();
		$query = $CI->db->select('tiles_order')
						->from('users')
						->where(array('user_id'=>$CI->session->userdata('user_id')))
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->tiles_order;
		}
	}
        /**

         * It get tiles order of teamdashboard from DB.
         * @returns array
         */
	function getActiveplan()
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("plans",array('plan_status'=>'Active','is_deleted'=>'0'));
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	
	function getSubscriptionIDFromCompanyOwner($company_id)
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("users",array('company_id'=>$company_id,'is_deleted'=>'0','is_owner'=>'1'));
		//echo $CI->db->last_query();die;
		if($query->num_rows() > 0)
		{
			return $query->row()->chargify_subscriptions_ID;
		}
	}
	function getTilesOrderTeamDashboard()
	{
		$CI =& get_instance();
		$query = $CI->db->select('team_tiles_order')
						->from('users')
						->where(array('user_id'=>$CI->session->userdata('user_id')))
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->team_tiles_order;
		}
	}
	
	

	function chargifyPaymentDetails(){
			
		$CI =& get_instance();
		
		require_once APPPATH."libraries/chargify_lib/Chargify.php";
		$CI->config->load('chargify');
		
		$username = $CI->config->item('API_key');
		$password=$CI->config->item('API_key_pass');
		
		$test = TRUE;
		$sub = new ChargifySubscription(NULL,$test);
		$query=$CI->db->get_where('users',array('user_id'=>$CI->session->userdata('user_id')));
		$use=$query->row();
		$query1=$CI->db->get_where('users',array('company_id'=>$CI->session->userdata('company_id'),'is_owner'=>'1'));
		$company_user=$query1->row();
		
		$data['billing_portal_url'] = "";
		$data['trial_end_date'] = "";
		$data['subscription_status'] = "";
		$data['payment_method'] = "No";
		if($company_user->chargify_subscriptions_ID != '')
		{
			try{
				$sub_detail = @$sub->getByID($company_user->chargify_subscriptions_ID);
				if($sub_detail){
					
					if($company_user->chargify_customer_id != ''){
                                            if($company_user->management_link != '' && (strtotime($company_user->expires_at)>strtotime(date("Y-m-d")))){
                                                $data['billing_portal_url'] = $company_user->management_link;
                                            }else{ 
                                                $headers = array(
					    	'Accept:application/json',
						);
						$url = 'https://schedullo.chargify.com/portal/customers/'.$company_user->chargify_customer_id.'/management_link.json';
						
						$ch = curl_init();
				              
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
						curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
						curl_setopt($ch, CURLOPT_URL,$url);
						
						$result = curl_exec($ch);
						$result1 = json_decode($result,TRUE);
                                            
                                            if(isset($result1['errors'])){
                                                    $data['billing_portal_url'] = "";
                                            }else{
                                                    $data['billing_portal_url'] = trim($result1['url']);
                                                    $data_link = array(
							'management_link'=>trim($result1['url']),
							'fetch_count'=>$result1['fetch_count'],
							'created_at'=>date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-",$result1['created_at']))),
							'new_link_available_at'=>date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-",$result1['new_link_available_at']))),
							'expires_at'=> date("Y-m-d", strtotime(str_replace(array("/"," ",","), "-",$result1['expires_at']))),
                                                    );
						
						$CI->db->where('user_id',$company_user->user_id);
						$CI->db->update('users',$data_link);
                                            }
                                          }
                                        }
					$data['subscription_status'] = $sub_detail->state;
					$data['trial_end_date'] = date($CI->config->item('company_default_format'),strtotime(str_replace(array("/"," ",","), "-",$sub_detail->trial_ended_at)));
				}
			} catch(ChargifyValidationException $cve) {
				
				$data["error"]=$cve->getMessage();
				
			}catch(ChargifyConnectionException $ex){
                            $data['billing_portal_url'] = $company_user->management_link;
                            $data['subscription_status'] = $company_user->chargify_transaction_status;
                            $data['trial_end_date'] = $company_user->new_link_available_at;
                        }
			if(isset($sub_detail->credit_card)){
				$credit_card = $sub_detail->credit_card->masked_card_number; 
				if($sub_detail->credit_card->expiration_year>=date('Y')){
					if($sub_detail->credit_card->expiration_month>=date('m')){
						$data['payment_method'] = "Yes";
					} else {
						$data['payment_method'] = "No";
					}
				} else {
					$data['payment_method'] = "No";
				}
			}else{
				$data['payment_method'] = "No";
			}
		}
		return $data;
	}
	

	function getTaskMainCategorySeq($step_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('category_seq')
						->from('task_category')
						->where('category_id',$step_id)
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->category_seq;
		}
	}
        function get_task_occurence_date($user_id){
            
		$CI =& get_instance();
		$query = $CI->db->select('start_on_date')
						->from('tasks')
						->where('task_id',$user_id)
						->get();
                if($query->num_rows()>0){
			return $query->row()->start_on_date;
		}
        }
        
        function get_project_subsection_id($project_id){
            $CI =& get_instance();
		$query = $CI->db->select('section_id')
						->from('project_section')
						->where('project_id',$project_id)
                                                ->where('section_order','1')
						->get();
                if($query->num_rows()>0){
			return $query->row()->section_id;
		}
        }
        
        function getCustomerList()
        {
            $CI =& get_instance();
		$query = $CI->db->select('c.*,CONCAT(u.first_name,SPACE(1),u.last_name) as ownername')
				->from('customers c')
                                ->join('users u','u.user_id = c.owner_id','left')
				->where('c.status','active')
                                ->where('c.is_deleted','0')
                                ->where('c.customer_company_id',$CI->session->userdata('company_id'))
                                ->order_by('c.customer_name','asc')
				->get();
                //echo $CI->db->last_query(); die();
                if($query->num_rows()>0){
                     return $query->result();
		}
                else
                {
                    return 0;
                }
        }
        
        function getOneCustomerDetail($id){ 
            $CI =& get_instance();
           
           $CI->db->select('c.*,CONCAT(u.first_name,SPACE(1),u.last_name) as ownername');
           $CI->db->from('customers c');
           $CI->db->join('users u','u.user_id=c.owner_id','left');
           $CI->db->where('c.customer_id',$id);
           $CI->db->where('c.customer_company_id',$CI->session->userdata('company_id'));
           //$CI->db->where('c.status','active');
           $CI->db->where('c.is_deleted','0');
           $query=  $CI->db->get();
           //print_r( $query->row_array()); die();
           if($query->num_rows()>0){
                        $data1= $query->row_array();
                        $data=array();
                        $CI->db->select('customer_name as name');
                        $CI->db->from('customers');
                        $CI->db->where('customer_company_id',$CI->session->userdata('company_id'));
                        $CI->db->where('customer_id' , $data1['parent_customer_id']);
                        $query1=$CI->db->get();
			$data2 =  $query1->row_array();
                        if($query1->num_rows()>0){
                           $data = array_merge($data1,$data2);
                        }else{
                           $data = $data1; 
                        }
                        return $data;
		} else {
			return 0;
		}
       }
       
       function getUserList($company_id){
           $CI =& get_instance();
           $CI->db->select('user_id,first_name,last_name');
           $CI->db->from('users');
           $CI->db->where('company_id',$company_id);
           $CI->db->where('is_customer_user','0');
           $CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           //echo $CI->db->last_query(); die();
           if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
       }
       
       
       function getCustomerCount(){
           $CI =& get_instance();
           $CI->db->select('*');
           $CI->db->from('customers');
           $CI->db->where('customer_company_id',$CI->session->userdata('company_id'));
           //$CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           return $query->num_rows;
		
       }

        
        
        /****** Below custom function is related to mobile API ********/
        
        
        
        
        /**
         * This method is used for get color name using user-id.
         * @param type $user_id
         * @return int|array of color
         */
        function get_user_color_name($user_id){
		$CI =& get_instance();
		$CI->db->select('user_id,color_name,color_code,user_color_id');
		$CI->db->from('user_colors');
		$CI->db->where('status','Active');
		$CI->db->where('user_id',$user_id);
		$CI->db->where('is_deleted','0');
		$CI->db->order_by('seq','asc');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        /**
         * Get company completed id using company id.
         * @param type $company_id
         * @return int
         */
        function get_company_completed_id($company_id){
            $CI=&get_instance();
            $CI->db->select('task_status_id');
            $CI->db->from('task_status');
            $CI->db->where('company_id',$company_id);
            $CI->db->where('task_status_name','Completed');
            $query=$CI->db->get();   
            if($query->num_rows()>0){
			return $query->row()->task_status_id;
		} else {
			return 0;
		}
        }
        /**
         * Get company task status using company id.
         * @param type $company_id
         * @param type $type
         * @return int|array
         */
        function get_task_status($company_id,$type=''){
		$CI =& get_instance();
		$CI->db->select('task_status_id,task_status_name');
		$CI->db->from('task_status');
		$CI->db->where('company_id',$company_id);
		if($type == 'Active'){
			$CI->db->where('task_status_flag','Active');
		} elseif($type == 'Inactive'){
			$CI->db->where('task_status_flag','Inactive');
		} else {

		}
		$CI->db->order_by('task_sequence','asc');
		$query = $CI->db->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        
        
        /**
         * Save new token in db.
         * @param type $token
         * @param type $user_id
         * @return type int
         */
        function saveToken($token,$user_id){
            $CI=&get_instance();
            $data=array(
                        'user_id'=>$user_id,
                        'token'=>$token,
                        'create_date'=>date('Y-m-d H:i:s'),
                        'status' => 'active'
                       );
            $CI->db->insert('access_token',$data);
            $id = $CI->db->insert_id();
            return $id;
        }
        
        /**
         * This method is checked token in db and rerurn time.
         * @param type $token
         * @param type $user_id
         * @return type int
         */
        
        function checkToken($token,$user_id){
            $CI=&get_instance();
            $CI->db->select('create_date');
            $CI->db->from('access_token');
            $CI->db->where('user_id',$user_id);
            $CI->db->where('token',$token);
            //$CI->db->where('status','active');
            $query=$CI->db->get();
            $date=$query->row()->create_date;
            $to_time = strtotime($date);
            $from_time = strtotime(date('Y-m-d H:i:s'));
            return round(abs($to_time - $from_time) / 60,2);
        }
        /**
         * This method is used for updating token time in db.
         * @param type $token
         * @param type $user_id
         */
        function updateToken($token,$user_id){
            $CI=&get_instance();
            $data=array(
                        'create_date'=>date('Y-m-d H:i:s'),
                        );
            $CI->db->where('user_id', $user_id);
            $CI->db->where('token', $token);
            $CI->db->update('access_token',$data);
               
        }
        /**
         * This method is delete token ,when token is expired.
         * @param type $token
         * @param type $user_id
         */
        function deleteToken($token,$user_id){
             $CI=&get_instance();
             $data=array(
                        'status'=>'Inactive'
                        );
            $CI->db->where('user_id', $user_id);
            $CI->db->where('token', $token);
            $CI->db->update('access_token',$data);
        }
        
        /**
         * get user list using user_id.
         * @param type $user_id
         * @return int
         */
        function get_users_list($user_id){
		$CI =& get_instance();
		
		$CI->db->select('u.first_name,u.last_name,um.user_id');
				$CI->db->from('user_managers um');
				$CI->db->join('users u','u.user_id = um.user_id');
				$CI->db->where('um.manager_id',$user_id);
				$CI->db->where('u.user_status','Active');
				$CI->db->where('u.is_deleted','0');
                                $CI->db->order_by('u.first_name','asc');
				$query = $CI->db->get();
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}

	}
        /**
         * Get project list for logged in user.
         * @param type $filter
         * @param type $user_id
         * @return int
         */
        
        function get_user_project_list($filter,$user_id){
                $CI =& get_instance();
		$query = $CI->db->query("SELECT p.project_id,p.project_title,p.project_added_by,project_status,project_start_date,project_end_date FROM (`project` p) LEFT JOIN `project_users` pu ON `p`.`project_id` = `pu`.`project_id` WHERE (`pu`.`user_id` =  ".$user_id." OR `p`.`project_added_by` =  ".$user_id.")  AND  `p`.`is_deleted` != 1 AND `p`.`project_status` =  '".$filter."' GROUP BY `p`.`project_id` order by p.project_title ASC");
		
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
        
        function get_companyDefaultdateFormat($company_id){
            $CI=&get_instance();
            $query = $CI->db->query("SELECT company_date_format FROM company WHERE company_id='".$company_id."' AND is_deleted= '0' ");
            if($query->num_rows()>0){
			return $query->row()->company_date_format;
		} else {
			return 0;
		}
        }
        
        
        function getUserTimezone($user_id,$company_id){

		$CI =& get_instance();
		$CI->db->select('u.user_time_zone');
		$CI->db->from('users u');
                $CI->db->where('u.company_id',$company_id);
                $CI->db->where('u.user_id',$user_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){ 
			$res = $query->row();
                        if($res->user_time_zone != ''){
			return $res->user_time_zone;
                        }else{
                            return date_default_timezone_get();
                        }
			
		} 
	}
        
        
        function toDateNewTimed($unixTimestamp,$timezone){
		$CI =& get_instance();
		date_default_timezone_set("UTC");
		$date = new DateTime($unixTimestamp);
		$date->setTimezone(new DateTimeZone($timezone));
		return $date->format("Y-m-d H:i:s");

	}
        
        
        function getActualTimeStatus($company_id){
            $CI =& get_instance();
            $CI->db->select('actual_time_on');
            $CI->db->from('company');
            $CI->db->where('company_id',$company_id);
            $CI->db->where('is_deleted','0');
            $query = $CI->db->get();
            return $query->row()->actual_time_on;
        }
        
        /******End API related methods*******/
        
        
        
        function getTotalCustomer(){
             $CI =& get_instance();
           $CI->db->select('*');
           $CI->db->from('customers');
           $CI->db->where('customer_company_id',$CI->session->userdata('company_id'));
           $CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           return $query->num_rows;
        }
        
        function get_colors_admin($user_id){
		$CI =& get_instance();
		$query = $CI->db->get_where('user_colors',array('status'=>'Active','is_deleted'=>'0','user_id'=>$user_id));
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        
        function search_section_id_in_array($array, $section_id)
        {
            foreach ($array as $key => $val) {
                if ($val['section_id'] === $section_id) {
                    return $val['replace_section'];
                }
            }
            return 0;
        }
        
        function get_project_section_id($project_id)
	{
                $CI =& get_instance();
		$CI->db->select('ps.section_id');
		$CI->db->from('project_section ps');
		$CI->db->join('project p','ps.project_id = p.project_id','left');
		$CI->db->where(array('ps.project_id'=>$project_id,'ps.main_section'=>'0'));
		//$this->db->order_by('ps.section_order');
		$query = $CI->db->get();

		//echo $this->db->last_query();

		if($query->num_rows()>0){
			return $query->row()->section_id;
		} else {
			return 0;
		}

	}
        function get_swimlanes_name($swimlanes_id){
		$CI =& get_instance();
		$CI->db->select('swimlanes_name');
		$CI->db->from('swimlanes');
		$CI->db->where("swimlanes_id",$swimlanes_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->swimlanes_name;
		} else {
			return 0;
		}
	}
        function get_customer_detail($customer_id,$company_id){
		$CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('customers');
		$CI->db->where("customer_id",$customer_id);
                $CI->db->where("customer_company_id",$company_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res;
		} else {
			return 0;
		}
	}
        
        
        
        function get_currency_list(){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('currency c');
		$CI->db->order_by('c.country_name');
		$query = $CI->db->get();
                return $query->result_array();
        }
        
        function get_customer_category($customer_id){
                $CI =& get_instance();
		$CI->db->select('*,tc.category_name');
		$CI->db->from('customer_category c');
                $CI->db->join('task_category tc','tc.category_id=c.category_id','left');
		$CI->db->where('c.company_id',$CI->session->userdata("company_id"));
                $CI->db->where('c.customer_id',$customer_id);
                $CI->db->where('c.is_deleted','0');
                $CI->db->where('c.parent_category_id','0');
		$query = $CI->db->get();
                return $query->result();
        }
        
        function get_non_chargeable_category($company_id){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('task_category');
		$CI->db->where('company_id',$company_id);
		$CI->db->where('category_status','Active');
		$CI->db->where("is_deleted","0");
                $CI->db->where('is_chargeable','0');
                $CI->db->where('parent_id','0');
		$CI->db->order_by("category_seq","asc");
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	
        }
        function get_customer_category_default($customer_id){
                $CI =& get_instance();
                
                $CI->db->select('category_id');
                $CI->db->from('customer_category');
                $CI->db->where('customer_id',$customer_id);
                $CI->db->where('company_id',$CI->session->userdata('company_id'));
                $CI->db->where('parent_category_id','0');
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();
                if($query->num_rows()>0){
			$result_data = $query->result();
		} else {
			$result_data = array();
		}
                $result =array();
                if($result_data!='' && isset($result_data)){
                    foreach ($result_data as $data){
                        $result[] = $data->category_id;
                       
                    }
                }
                
               
		$CI->db->select('tc.*');
		$CI->db->from('task_category tc');
                if(!empty($result) && $result !=''){
                $CI->db->where_not_in('tc.category_id',$result);
                }
                
		$CI->db->where('tc.company_id',$CI->session->userdata("company_id"));
                $CI->db->where('tc.parent_id','0');
                $CI->db->where('tc.is_deleted','0');
                $CI->db->where('tc.is_chargeable','1');
		$query1 = $CI->db->get();
               
                if($query1->num_rows()>0){
			return $query1->result();
		} else {
			return 0;
		}
        }
        
        function getSubcategoryByCategoryId($category_id,$customer_id){
             $CI =& get_instance();
             
             $CI->db->select('*');
             $CI->db->from('customer_category');
             $CI->db->where('parent_category_id',$category_id);
             $CI->db->where('customer_id',$customer_id);
             $CI->db->where('company_id',$CI->session->userdata('company_id'));
             $CI->db->where('is_deleted','0');
             $query = $CI->db->get();
             if($query->num_rows()>0){
                 return $query->result();
             }else{
                 return 0;
             }
        }
        
        function is_exists_user_under_customer($customer_id,$user_id){
            $CI = &get_instance();
            $CI->db->select('*');
            $CI->db->from('users_under_customer_rate');
            $CI->db->where('customer_id',$customer_id);
            $CI->db->where('user_id',$user_id);
            $CI->db->where('company_id',$CI->session->userdata('company_id'));
            $query = $CI->db->get();
            if($query->num_rows()>0){
                return 1;
            }else{
                return 0;
            }
            
        }
        function get_allocated_user_project_rate($project_id,$user_id){
                 $CI = &get_instance();
            
                $CI->db->select('project_rate');
                $CI->db->from('project_users');
                $CI->db->where('project_id',$project_id);
                $CI->db->where('user_id',$user_id);
                $query = $CI->db->get();  
                return $query->row()->project_rate;
            
        }
        function get_project_rate($project_id){
                $CI = &get_instance();
                $CI->db->select('project_base_rate');
                $CI->db->from('project');
                $CI->db->where('project_id',$project_id);
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                return $query->row()->project_base_rate;
        }
        
        
        function get_user_total_project(){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('project p');
		$CI->db->join('project_users pu','pu.project_id = p.project_id');
		$CI->db->where('pu.user_id',  get_authenticateUserID());
		$CI->db->where('pu.status','Active');
		$CI->db->where('pu.is_deleted','0');
		$CI->db->where('p.project_status','Open');
		$CI->db->where('p.is_deleted','0');
		$query = $CI->db->get();
                //echo $CI->db->last_query(); die();
                return $query->num_rows();
		
        }
		function get_customer_employee_rate($employee_id,$customer_id,$company_id){
                $CI = &get_instance();
                $CI->db->select('base_rate');
                $CI->db->from('users_under_customer_rate');
                $CI->db->where('customer_id',$customer_id);
                $CI->db->where('user_id',$employee_id);
                $CI->db->where('company_id',$company_id);
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->base_rate;
                }else{
                    return 0;
                }
                
        }
        
        function get_customer_category_rate($category_id,$customer_id,$company_id,$subcategory_id = ''){
                $CI = &get_instance();
                $CI->db->select('rate');
                $CI->db->from('customer_category');
                $CI->db->where('customer_id',$customer_id);
                if($subcategory_id!=''){
                    $CI->db->where('category_id',$subcategory_id);
                    $CI->db->where('parent_category_id',$category_id);
                }else{
                    $CI->db->where('category_id',$category_id);
                }
                $CI->db->where('company_id',$company_id);
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->rate;
                }else{
                    return 0;
                }
        }
        
        function get_customer_rate($customer_id,$company_id){
                $CI = &get_instance();
                $CI->db->select('base_rate');
                $CI->db->from('customers');
                $CI->db->where('customer_id',$customer_id);
                $CI->db->where('customer_company_id',$company_id);
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->base_rate;
                }else{
                    return 0;
                }
        }
        
        function get_employee_base_charege_out_rate($user_id,$company_id){
                $CI = &get_instance();
                $CI->db->select('base_charge_rate_per_hour');
                $CI->db->from('users');
                $CI->db->where('user_id',$user_id);
                $CI->db->where('company_id',$company_id);
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->base_charge_rate_per_hour;
                }else{
                    return 0;
                }
        }
        function get_charge_out_rate($task_id,$company_id=''){
           
                if($company_id!=''){
                    $task_details =  get_task_info($task_id,$company_id);
                }else{
                    $task_details =  get_task_detail($task_id);
                }
               
               $rate=0;
               if($task_details['task_project_id']!= 0 && $task_details['task_allocated_user_id']!=''){ 
                    $rate = get_allocated_user_project_rate($task_details['task_project_id'],$task_details['task_allocated_user_id']);
                       if($rate == 0){
                           $rate = get_project_rate($task_details['task_project_id']);
                        }
                }
               if( $rate == 0 && $task_details['customer_id']!='' && $task_details['customer_id'] !='0'){ 
                   $rate = get_customer_employee_rate($task_details['task_allocated_user_id'],$task_details['customer_id'],$task_details['task_company_id']);
                   if($rate == 0 && $task_details['customer_id']!=''){
                        $rate = get_customer_category_rate($task_details['task_category_id'],$task_details['customer_id'],$task_details['task_company_id'],$task_details['task_sub_category_id']);
                        if($rate == 0 && $task_details['customer_id']!='' ){
                            $rate = get_customer_category_rate($task_details['task_category_id'],$task_details['customer_id'],$task_details['task_company_id']);
                            if($rate== 0 && $task_details['customer_id']!=''){
                                $rate = get_customer_rate($task_details['customer_id'],$task_details['task_company_id']);
                            }
                        }
                    }
                }
               if($rate == 0){
                   $rate = get_employee_base_charege_out_rate($task_details['task_allocated_user_id'],$task_details['task_company_id']);
               }
//               $cost = round(($rate*$minute)/60,2);
                return $rate;
        }
        
        function get_task_estimated_time($task_id){
                $CI = &get_instance();
                $CI->db->select('task_time_estimate');
                $CI->db->from('tasks');
                $CI->db->where('task_id',$task_id);
                $CI->db->where('task_company_id',$CI->session->userdata('company_id'));
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                
                    return $query->row()->task_time_estimate;
                
        }
        
        function get_task_actual_time($task_id){
                $CI = &get_instance();
                $CI->db->select('task_time_spent');
                $CI->db->from('tasks');
                $CI->db->where('task_id',$task_id);
                $CI->db->where('task_company_id',$CI->session->userdata('company_id'));
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                
                    return $query->row()->task_time_spent;
        }
        
        function get_user_cost_per_hour($user_id,$company_id = ''){
                $CI = &get_instance();
                $CI->db->select('cost_per_hour');
                $CI->db->from('users');
                $CI->db->where('user_id',$user_id);
                if($company_id!=''){
                    $CI->db->where('company_id',$company_id);
                }else{
                    $CI->db->where('company_id',$CI->session->userdata('company_id'));
                }
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->cost_per_hour;
                }else{
                    return 0;
                }
        }
        
        function get_task_charge_out_rate($task_id,$company_id=''){
                $CI = &get_instance();
                $CI->db->select('charge_out_rate');
                $CI->db->from('tasks');
                $CI->db->where('task_id',$task_id);
                if($company_id!=''){
                    $CI->db->where('task_company_id',$company_id);
                }else{
                    $CI->db->where('task_company_id',$CI->session->userdata('company_id'));
                }
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->charge_out_rate;
                }else{
                    return 0;
                }
        }
        
        function get_project_id_by_section_id($section_id){
                $CI = &get_instance();
                $CI->db->select('project_id');
                $CI->db->from('project_section');
                $CI->db->where('section_id',$section_id);
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->project_id;
                }else{
                    return 0;
                }
            
        }
        
        
        function check_pricing_module_status($company_id){
                $CI = &get_instance();
                $CI->db->select('pricing_module_status');
                $CI->db->from('company');
                $CI->db->where('company_id',$company_id);
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row()->pricing_module_status;
                }else{
                    return 0;
                }
        }
        
        function get_currency_symbol($currency_code){
                $CI = &get_instance();
                $CI->db->select('cr.currency_symbol,cr.currency_code');
                $CI->db->from('currency cr');
                $CI->db->join('company c','c.currency = cr.currency_code','left');
                $CI->db->where('c.company_id',$CI->session->userdata('company_id'));
                $CI->db->where('cr.currency_code',$currency_code);
                $CI->db->where('c.is_deleted','0');
                $query = $CI->db->get();  
                if($query->num_rows()>0){
                    return $query->row();
                }else{
                    return 0;
                }
        }
        
        function is_first_login()
	{
		$CI =& get_instance();
		$CI->db->select('is_first_login');
		$CI->db->from('users');
		$CI->db->where('user_id',get_authenticateUserID());
		$query = $CI->db->get();
		//echo $CI->db->last_query();
		return $query->row()->is_first_login;
	}
          /*
          *  For Sending mail via Sendgrid
         */
        function mail_by_sendgrid($email_address_from,$email_address_reply,$to,$toname,$sub, $template_id,$data=array()){
            
                             require APPPATH.'libraries/sendgrid/vendor/autoload.php';
                              $ci =& get_instance();
                              $sendgrid_apikey = $ci->config->item('sendgrid_api_key');
                              $sendgrid = new SendGrid($sendgrid_apikey);
                              $url =$ci->config->item('sendgrid_api_url'); 
                              
                              if(isset($data) && !empty($data['daily_email_summary'])){
                                 
                                  extract($data['daily_email_summary']);
                                    $js = array('sub' => array('%overduetasks%' =>array($overduetask),
                                                        '%todaystasks%'=>array($todaytask),
                                                        '%plannedtomorrowtasks%'=>array($tomorrow ),
                                                         '[Sender_Name]'=>array($senderName),
                                                         '[Sender_Address]'=>array($senderAdd),
                                                         '[Sender_City]'=>array($senderCity),
                                                         '[Sender_State]'=>array($senderState),
                                                         '[Sender_Zip]'=>array($senderZip),
                                                         '[Unsubscribe]'=>array($unsubscribe),
//                                                                               '%link%'=>array($link)
                                                     ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'New subscription has been verified'){
                                    $js = array(
//                                    'sub' => array(':name' => array('Schedullo') ),
                                    'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                               );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'trial email'){
                                    $js = array('sub' => array('%subscription_link%' =>array($data['data']['subscription_link'])
                                                                           ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'verify email'){
                                    $js = array('sub' => array('%Activation_link%' =>array($data['data']['activation_link'])
                                                                           ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'Add User To New Company'){
                                    $js = array('sub' => array('%user_name%' =>array($data['data']['user_name']),
                                                            '%company_name%' =>array($data['data']['company_name']),
                                                            '%activation_link%'=>array($data['data']['activation_link']),
                                                            '%email%'=>array($data['data']['email'])
                                                                           ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'timesheet approve by manager'){
                                    $js = array('sub' => array('%timesheet_link%' =>array($data['data']['timesheet_link'])
                                                                           ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'timesheet recall'){
                                    $js = array('sub' => array('%timesheet_code%' =>array($data['data']['timesheet_code']),
                                                            '%user_name%' =>array($data['data']['user_name'])
                                                                           ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'Add New User By Admin'){
                                    $js = array('sub' => array('%user_name%' =>array($data['data']['user_name']),
                                                            '%company_name%' =>array($data['data']['company_name']),
                                                            '%activation_link%'=>array($data['data']['activation_link']),
                                                            '%email%'=>array($data['data']['email'])
                                                                           ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                              }else if(isset($data['subject']) && $data['subject'] == 'user email verify'){
                                    $js = array('sub' => array('%subscription_link%' =>array($data['data']['subscription_link'])
                                                                           ),
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                  
                              }else if(isset($data['subject']) && $data['subject'] == 'Customer Invitation'){
                                    $js = array('sub' => array('%user_name%' =>array($data['data']['user_name']),
                                                            '%company_name%' =>array($data['data']['company_name']),
                                                            '%activation_link%'=>array($data['data']['activation_link']),
                                                            '%email%'=>array($data['data']['email'])
                                                                          ) ,
                                                      'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $template_id)))
                                                );
                                    $sub = $data['data']['company_name'];
                              }
                            $params = array(
                                'to'        =>$to, 
                                'toname'    =>$toname,
                                'from'      => $email_address_from,
                                'fromname'  =>$ci->config->item('sendgrid_schedullo_from_name'),
                                'subject'   => $sub, 
                                'text'      => " ",
                                'html'      => " ",
                                'x-smtpapi' => json_encode($js),
                              );
                            $request =  $url.'api/mail.send.json';
                            $session = curl_init($request);
                            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
                            curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $sendgrid_apikey));
                            curl_setopt ($session, CURLOPT_POST, true);
                            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
                            curl_setopt($session, CURLOPT_HEADER, false);
                            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                            $response = curl_exec($session);
                            curl_close($session);
                             // print_r($response);
                            return TRUE;
                         

        }
		
        /**
         * This method will give division id 
         * @param type $name,$company_id
         * @return int
         */
        function get_division_id_by_name($name,$company_id){
                $CI =& get_instance();
		$query = $CI->db->get_where('company_divisions',array('devision_title'=>$name, 'company_id'=>$company_id));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->division_id;
		} else {
			return 0;
		}
        }
       

        /**
         * 
         */
        function get_task_status_id_by_name_company($name,$company_id){
                $CI =& get_instance();
		$CI->db->select('task_status_id');
		$CI->db->from('task_status');
		$CI->db->where('task_status_name',$name);
		$CI->db->where('task_status_flag','Active');
		$CI->db->where('company_id',$company_id);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->task_status_id;
		} else {
			return 0;
		}
	}
        /**
         * This method will give departement id 
         * @param type $name
         * @param type $company_id
         * @return int
         */
       function get_department_id_by_name($name, $company_id){
                $CI =& get_instance();
		$query = $CI->db->get_where('company_departments',array('department_title'=>$name, 'company_id'=>$company_id));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->department_id;
		} else {
			return 0;
		}
	}
        /**
         * this method will give skill id from db
         * @param type $name
         * @param type $company_id
         * @return int
         */
        function get_skill_id_by_name($name,$company_id){
                $CI =& get_instance();
		$query = $CI->db->get_where('skills',array('skill_title'=>$name, 'company_id'=>$company_id));
		if($query->num_rows()>0){
			$res = $query->row();
			return $res->skill_id;
		} else {
			return 0;
		}
		
	}
        /**
         * This method will give list of customers by company id
         * @param type $company_id
         * @return list of customer list
         */
         function get_Customer_List($company_id){
            $CI =& get_instance();
            $CI->db->select('c.*,CONCAT(u.first_name,SPACE(1),u.last_name) as ownername');
            $CI->db->from('customers c');
            $CI->db->join('users u','u.user_id = c.owner_id','left');
	   // $this->db->where('c.status','active');
            $CI->db->where('c.is_deleted','0');
            $CI->db->where('c.customer_company_id',$company_id);
            $CI->db->order_by('c.customer_name','asc');
            $query=  $CI->db->get();
            
                //echo $this->db->last_query();
                if($query->num_rows()>0){
                     return $query->result();
		}
                else
                {
                    return 0;
                }
        }
        /**
         * This method will give customer detail by using customer id and company id
         * @param type $customer_id
         * @param type $company_id
         * @return int
         */
        function getCustomerDetail($customer_id,$company_id){ 
            $CI =& get_instance();
           
           $CI->db->select('c.*,CONCAT(u.first_name,SPACE(1),u.last_name) as ownername');
           $CI->db->from('customers c');
           $CI->db->join('users u','u.user_id=c.owner_id','left');
           $CI->db->where('customer_id',$customer_id);
           $CI->db->where('c.customer_company_id',$company_id);
           //$CI->db->where('c.status','active');
           $CI->db->where('c.is_deleted','0');
           $query=  $CI->db->get();
           //print_r( $query->row_array()); die();
           if($query->num_rows()>0){
                $data1= $query->row_array();
                $data=array();
                $CI->db->select('customer_name as parent_customer');
                $CI->db->from('customers');
                $CI->db->where('customer_company_id',$company_id);
                $CI->db->where('customer_id' , $data1['parent_customer_id']);
                $query1=$CI->db->get();
		$data2 =  $query1->row_array();
                if($query1->num_rows()>0){
                   $data = array_merge($data1,$data2);
                }else{
                   $data = $data1; 
                }
                return $data;
	   } else {
		return 0;
	   }
       }
       
       function countCustomer($customer_id){
           $CI =& get_instance();
           $CI->db->select('*');
           $CI->db->from('customers');
           $CI->db->where('customer_company_id',$customer_id);
           //$CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           return $query->num_rows;
		
       }
        
        
        
        function get_company_id_by_user_id($user_id){
                $CI =& get_instance();
		$CI->db->select('company_id');
		$CI->db->from('users');
		$CI->db->where('user_id',$user_id);
                $CI->db->where('is_deleted','0');
		$query = $CI->db->get();
		//echo $CI->db->last_query();
		return $query->row()->company_id;
        }
        
        function get_app_info($id){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('app_registration');
		$CI->db->where('app_id',$id);
                $query = $CI->db->get();
                
                if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
        }
        
        function get_all_app_info($user_id){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('app_register');
		$CI->db->where('user_id',$user_id);
                $CI->db->where('is_deleted','0');
		$query = $CI->db->get();
                
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
        }
        
        
        function check_user_avaibility_by_id($user_id){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('users');
		$CI->db->where('user_id',$user_id);
                $CI->db->where('is_deleted','0');
		$query = $CI->db->get();
                
		if($query->num_rows()>0){
			return 1;
		} else {
			return 0;
		}
        }
        
        function getAppInfo(){
            $CI =& get_instance();
            $CI->db->select('*');
            $CI->db->from('app_registration');
            $CI->db->where('api_company_id',$CI->session->userdata('company_id'));
            $query = $CI->db->get();
            if($query->num_rows()>0){
                return $query->result();
            }else{
                return 0;
            }
        }
        
        function check_api_access(){
            $CI =& get_instance();
            $CI->db->select('api_access_status');
            $CI->db->from('company');
            $CI->db->where('company_id',$CI->session->userdata('company_id'));
            $CI->db->where('is_deleted','0');
            $query = $CI->db->get();
            if($query->num_rows()>0){
                return $query->row()->api_access_status;
            }else{
                return 0;
            }
        }
        
        function get_company_id(){
		$CI =& get_instance();
		if($CI->session->userdata('company_id')!=''){
			return $CI->session->userdata('company_id');
		} else {
			return 0;
		}
	}
        
        function count_timesheet($user_id){
                $CI =& get_instance();
		$CI->db->select("max(timesheet_code) as code");
		$CI->db->from('timesheets');
		$CI->db->where('timesheet_user_id',$user_id);
                $CI->db->where('timesheet_company_id',$CI->session->userdata('company_id'));
                $query = $CI->db->get();
                if($query->row()->code == NULL){
                    return 0;
                }else{
		   return $query->row()->code;
                }
        }
        
        function get_manager_list(){
                $CI =& get_instance();
		$CI->db->select('um.manager_id,u.first_name,u.last_name');
		$CI->db->from('user_managers um');
		$CI->db->join('users u','u.user_id = um.manager_id');
		$CI->db->where('um.user_id',$CI->session->userdata('user_id'));
		$CI->db->where('u.user_status','Active');
		$CI->db->where('u.is_deleted','0');
		$query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
        }
        
        function max_timesheet_to_date(){
                $CI =& get_instance();
		$CI->db->select("max(to_date) as date");
		$CI->db->from('timesheets');
		$CI->db->where('timesheet_user_id',get_authenticateUserID());
                $CI->db->where('timesheet_company_id',$CI->session->userdata('company_id'));
                $query = $CI->db->get();
                if($query->row()->date == NULL){
                    return date('Y-m-d');
                }else{
		   return $query->row()->date;
                }
        }
        
        
       function total_timesheet(){
            $CI =& get_instance();
            $users =  get_users_under_manager();
            $CI->db->select('*');
            $CI->db->from('timesheets');
            $CI->db->where('timesheet_company_id',$CI->session->userdata('company_id'));
            if($CI->session->userdata('is_manager')=='0' && $CI->session->userdata('is_administrator')=='0'){
                $CI->db->where('timesheet_user_id',  get_authenticateUserID());
            }else if($CI->session->userdata('is_manager')=='1'){
                $arr= array();
                if($users){
                   foreach ($users as $u){
                     $arr[] = $u;
                    }
                }
                array_push($arr, get_authenticateUserID());
                $CI->db->where_in('timesheet_user_id',  $arr);
            }
            $CI->db->where('timesheet_status','draft');
            $query = $CI->db->get();
            
            return $query->num_rows();
            
            
       }
       
       function get_approver_id($timesheet_user_id){
                $CI =& get_instance();
		$CI->db->select("timesheet_approver_id");
		$CI->db->from('users');
		$CI->db->where('user_id',$timesheet_user_id);
                $CI->db->where('company_id',$CI->session->userdata('company_id'));
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->row()->timesheet_approver_id;
                }else{
		   return 0;
                }
       }
       
       
       function get_reporting_manger_list($user_id){
                $CI =& get_instance();
		$CI->db->select('um.manager_id');
		$CI->db->from('user_managers um');
		$CI->db->join('users u','u.user_id = um.user_id');
		$CI->db->where('um.user_id',$user_id);
		$CI->db->where('u.user_status','Active');
		$CI->db->where('u.is_deleted','0');
		$query = $CI->db->get();
		//echo $CI->db->last_query();die;
		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $row){
				$ids[] = $row['manager_id'];
			}
			return $ids;
		} else {
			return 0;
		}
       }
        function get_list_user_report_to_adminstartor(){
                                $CI =& get_instance();
		$CI->db->select('um.user_id');
		$CI->db->from('user_managers um');
		$CI->db->join('users u','u.user_id = um.manager_id');
		$CI->db->where('um.manager_id',$CI->session->userdata('user_id'));
		$CI->db->where('u.user_status','Active');
		$CI->db->where('u.is_deleted','0');
		$query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
       }
       
       /**
        * function to get string between two substrings
        */
       function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }
        /**
         * function to get app info by userid
         */
        function getAppInfoByUserId($user_id){
            $CI =& get_instance();
            $CI->db->select('app_registration.*');
            $CI->db->from('app_registration');
            $CI->db->join('users','app_registration.api_company_id=users.company_id');
            $CI->db->where('users.user_id',$user_id);
            $query = $CI->db->get();
            if($query->num_rows()>0){
                return $query->result();
            }else{
                return 0;
            }
        }
        /**
         * function to refresh outlook token
         */
        function outlook_refresh_token($user_id)
        {
            $CI =& get_instance();
            $where=array(
                'user_id'=>$user_id
                );
            $CI->db->where($where);
            $query = $CI->db->get('outlook_detail');
            $detail = $query->row_array();
            $refresh_token = $detail['refresh_token'];
            
            $fields=array(
                'client_id'=>OUTLOOK_CLIENT_ID,
                'scope'=>'openid%20profile%20offline_access%20User.Read%20Mail.Read%20Calendars.Read%20Contacts.Read',
                'refresh_token'=>$refresh_token,
                'redirect_uri'=>OUTLOOK_REDIRECT_URL,
                'grant_type'=>'refresh_token',
                'client_secret'=>OUTLOOK_SECRET_KEY
            );
            $post='';
                            foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                            $post = rtrim($post,'&');
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://login.microsoftonline.com/common/oauth2/v2.0/token",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $post,
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $new_tokens = json_decode($response,true);
            $update=array('refresh_token'=>$new_tokens['refresh_token']);
            $CI->db->where($where);
            $CI->db->update('outlook_detail',$update);
            
            return $new_tokens;
        }
        
        
        function get_company_default_week_day(){
                $CI =& get_instance();
		$CI->db->select('fisrt_day_of_week');
		$CI->db->from('default_calendar_setting');
		$CI->db->where('comapny_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->row()->fisrt_day_of_week;
		} else {
			return 0;
		}
        }
        function set_end_date_from_occurence($main_arr)
        {
            $CI =& get_instance();
            $default_day = get_default_day_of_company($main_arr['company_id']);
		
            $offdays = get_company_offdays($main_arr['company_id']);

            $recurrence_type = isset($main_arr['recurrence_type'])?$main_arr['recurrence_type']:'1';

            $start_on_date = change_date_format($main_arr['start_on_date']);

            $main_arr['end_by_date'] = isset($main_arr['end_by_date']) && $main_arr['end_by_date']!=''?change_date_format($main_arr['end_by_date']):date("Y-m-d");

            $start_on_date = change_date_format($start_on_date);
            $main_arr['end_by_date'] = change_date_format($main_arr['end_by_date']);
            $start_date1 = array();
            $display = '';
            if($recurrence_type == '1')
            {
            for($i=0;$i<$main_arr['end_after_recurrence'];$i++){

                    if(isset($main_arr['Daily_every_weekday']) && $main_arr['Daily_every_weekday']!='0'){

                            if($i==0){
                                    $display = date('Y-m-d', strtotime($start_on_date . ' +0 days'));
                            } else {
                                    $display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_week_day'].' days'));
                            }

                            if(chk_company_offday_date($display,$offdays)){
                                    $i--;
                                    if($main_arr['Daily_every_week_day']>1){
                                            for($k=1;$k<$main_arr['Daily_every_week_day'];$k++){
                                                    $display = date('Y-m-d', strtotime($display . ' + 1 days'));
                                                    if(chk_company_offday_date($display,$offdays)){
                                                            $display = date('Y-m-d', strtotime($display . ' + 1 days'));
                                                    } else {
                                                            break;
                                                    }
                                            }
                                            $i++;
                                            $start_date1[] = $display;
                                    }
                            } else {
                                    $start_date1[] = $display;
                            }

                    } else if(isset($main_arr['Daily_every_day'])) {

                            if($i==0){
                                    $display = date('Y-m-d', strtotime($start_on_date . ' +0 days')); //gives after 2 days date without including saturday sunday only business days.
                            } else {
                                    $display = date('Y-m-d', strtotime($start_on_date . ' + '.$main_arr['Daily_every_day'].' days')); //gives after 2 days date without including saturday sunday only business days.
                            }


                            $start_date1[$i] = $display;
                    } else {
                            break;
                    }

                    $start_on_date = $display;
            }

            $data['start_date'] = reset($start_date1);
            $data['end_date'] = end($start_date1);
            $data['end_after_recurrence'] = count($start_date1);
            
            }else if($recurrence_type == '2')
            {
                
					if(isset($main_arr['end_after_recurrence'])){
						$end_after_recurrence = $main_arr['end_after_recurrence'];
						$start_date1 = array();
						$display = '';
						$i = 0;
						if(isset($main_arr['Weekly_week_day'])){
							$Weekly_week_day_arr = $main_arr['Weekly_week_day'];
							$i = 0;
							foreach($Weekly_week_day_arr as $week){
								if($week == '1'){
									$dow   = 'Monday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '2'){
									$dow   = 'Tuesday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '3'){
									$dow   = 'Wednesday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '4'){
									$dow   = 'Thursday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '5'){
									$dow   = 'Friday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '6'){
									$dow   = 'Saturday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								if($week == '7'){
									$dow   = 'Sunday';
									$step  = $main_arr['Weekly_every_week_no'];
									$unit  = 'W';

									$start = new DateTime($start_on_date);

									$start->modify($dow); // Move to first occurence

									$occurence = $end_after_recurrence-1;
									$interval = new DateInterval("P{$step}{$unit}");
									$period   = new DatePeriod($start, $interval, $occurence);

									foreach ($period as $date) {
									    $display = $date->format('Y-m-d');
										$start_date1[] = $display;
									}
								}
								$i++;

							}
							sort($start_date1);

						}
						$data['start_date'] = reset($start_date1);
						$data['end_date'] = end($start_date1);
						$data['end_after_recurrence'] = $end_after_recurrence;
					}
            }
            else if($recurrence_type == '3')
            {
                $start_date1 = array();
					$display = '';
					for($i=0;$i<$main_arr['end_after_recurrence'];$i++){

						if((isset($main_arr['Monthly_op1_1']) && $main_arr['Monthly_op1_1'] != '0') && (isset($main_arr['Monthly_op1_2']) && $main_arr['Monthly_op1_2']!='0')){

							$Monthly_op1_1_day = $main_arr['Monthly_op1_1']<10?'0'.$main_arr['Monthly_op1_1']:$main_arr['Monthly_op1_1']; // attach 0 to if day is from 1 to 9

							$day = date("d",strtotime($start_on_date));
							if($Monthly_op1_1_day>=$day){
								if($i == 0){
									$effectiveDate = date('Y-m-d', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
								}
							} else {
								$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op1_2']." months", strtotime($start_on_date))); // gives month date from given date
							}

							if($Monthly_op1_1_day == '30' || $Monthly_op1_1_day == '31'){
								$display = date('Y-m-t', strtotime($effectiveDate));
							} else {
								$display = date('Y-m-'.$Monthly_op1_1_day, strtotime($effectiveDate));// gives no of day date from given date
							}
							$start_date1[] = $display;

						} elseif((isset($main_arr['Monthly_op2_1']) && $main_arr['Monthly_op2_1']!='') && (isset($main_arr['Monthly_op2_2']) && $main_arr['Monthly_op2_2'] !='') && (isset($main_arr['Monthly_op2_3']) && $main_arr['Monthly_op2_3']!='0')){

							$temp_date = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.date('F Y', strtotime("+0 months", strtotime($start_on_date)))));

							if(strtotime($start_on_date)<=strtotime($temp_date)){
								if($i == 0){
									$effectiveDate = date('F Y', strtotime("+0 months", strtotime($start_on_date))); // gives month date from given date
								} else {
									$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
								}
							} else {
								$effectiveDate = date('F Y', strtotime("+".$main_arr['Monthly_op2_3']." months", strtotime($start_on_date))); // gives month date from given date
							}

							$display = date('Y-m-d', strtotime($main_arr['Monthly_op2_1'].' '.$main_arr['Monthly_op2_2'].' of '.$effectiveDate));
							$start_date1[] = $display;


						} elseif((isset($main_arr['Monthly_op3_1']) && $main_arr['Monthly_op3_1'] != '0') && (isset($main_arr['Monthly_op3_2']) && $main_arr['Monthly_op3_2']!='0')){

							if($main_arr['Monthly_op3_1']<0){
								$start_on_date = date("Y-m-01",strtotime($start_on_date));
								if($i ==0){
									$effectiveDate = date('Y-m-t', strtotime("+0 months", strtotime($start_on_date)));
								} else {
									$effectiveDate = date('Y-m-t', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));
								}

								if($main_arr['Monthly_op3_1'] == '-1'){

								} else {
									$temp_date = date("Y-m-d",strtotime($main_arr['Monthly_op3_1']." days",strtotime($effectiveDate)));
									if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
										$effectiveDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($effectiveDate)) . " + 1 month"));
									} else {
										$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
									}
									for($a=-1;$a>=$main_arr['Monthly_op3_1'];$a--){
										$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
										if(chk_company_offday_date($effectiveDate,$offdays)){
											$a++;
										}
									}
								}
								$display = chk_company_offday(date("Y-m-d",strtotime($effectiveDate)),$offdays);

							} else {
								$start_on_date = date("Y-m-01",strtotime($start_on_date));
								$effectiveDate = date('Y-m-d', strtotime("+".$main_arr['Monthly_op3_2']." months", strtotime($start_on_date)));

								if($main_arr['Monthly_op3_1'] == '1'){
									$display = chk_company_working_day_next(date("Y-m-d",strtotime($effectiveDate)),$offdays);
								} else {
									$effectiveDate = date("Y-m-d",strtotime("-1 days",strtotime($effectiveDate)));
									for($a=1;$a<=$main_arr['Monthly_op3_1'];$a++){
										$effectiveDate = date("Y-m-d",strtotime("+1 days",strtotime($effectiveDate)));
										if(chk_company_offday_date($effectiveDate,$offdays)){
											$a--;
										}
									}
									$display = date("Y-m-d",strtotime($effectiveDate));
								}
							}
							$start_date1[] = $display;
						} else {
							break;
						}
						$start_on_date = $display;

					}
					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = count($start_date1);
            }
            else if($recurrence_type == '4')
            {
                $start_date1 = array();
					$display = '';

					for($i=0;$i<$main_arr['end_after_recurrence'];$i++){
						if(isset($main_arr['Yearly_op1']) && $main_arr['Yearly_op1']!='0'){

							if($i==0){
								$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " +0 year"));
							} else {
								$display = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + ".$main_arr['Yearly_op1']." year"));
							}

							$start_date1[$i] = $display;

						} elseif((isset($main_arr['Yearly_op2_1']) && $main_arr['Yearly_op2_1']!='0') && (isset($main_arr['Yearly_op2_2']) && $main_arr['Yearly_op2_2']!='0')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));

							if($i==0){
								if($year >= date('Y')){
									if($main_arr['Yearly_op2_1'] > $month){
										$year = $year;
									} elseif($main_arr['Yearly_op2_1'] = $month){
										if($main_arr['Yearly_op2_2'] >= $day){
											$year = $year;
										} else {
											$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
										}
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}

								} else {
									$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}


							$display = date("Y-m-d",strtotime(date($year."-".$main_arr['Yearly_op2_1']."-".$main_arr['Yearly_op2_2'])));
							$start_date1[$i] = $display;


						} elseif((isset($main_arr['Yearly_op3_1']) && $main_arr['Yearly_op3_1']!='') && (isset($main_arr['Yearly_op3_2']) && $main_arr['Yearly_op3_2']!='') && (isset($main_arr['Yearly_op3_3'])&&$main_arr['Yearly_op3_3']!='')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));
							$temp_date = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));

							if(strtotime($temp_date)>=strtotime(date("Y-m-d"))){
								if($i==0){
									if($year >= date('Y')){
										if(date('m', strtotime($main_arr['Yearly_op3_3'])) >= $month){
											$year = $year;

										} else {
											$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
										}

									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
								} else {
									$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
								}
							} else {
								$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
							}


							$display = date('Y-m-d', strtotime($main_arr['Yearly_op3_1'].' '.$main_arr['Yearly_op3_2'].' of '.$main_arr['Yearly_op3_3'].' '.$year));
							$start_date1[$i] = $display;

						} elseif((isset($main_arr['Yearly_op4_1']) && $main_arr['Yearly_op4_1']!='0') && (isset($main_arr['Yearly_op4_2']) && $main_arr['Yearly_op4_2']!='')){

							$year = date('Y',strtotime($start_on_date));
							$month = date('m',strtotime($start_on_date));
							$day = date('d',strtotime($start_on_date));

							if($main_arr['Yearly_op4_1']<0){
								if($year >= date('Y')){
									if(date('m', strtotime($main_arr['Yearly_op4_2'])) >= $month){
										if($i==0){
											$year = $year;
										} else {
											$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
										}

									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
								} else {
									$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
								}
								$monthyear = date('Y-m-t',strtotime($main_arr['Yearly_op4_2']." ".$year));

								if($main_arr['Yearly_op4_1'] == '-1'){

								} else {
									$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));

									$temp_date = date("Y-m-d",strtotime($main_arr['Yearly_op4_1']." days",strtotime($monthyear)));
									if(strtotime($temp_date)<strtotime(date("Y-m-d"))){
										$monthyear = date("Y-m-d",strtotime(date("Y-m-d", strtotime($monthyear)) . " + 1 year"));
									}
									for($a=-1;$a>$main_arr['Yearly_op4_1'];$a--){
										if(chk_company_offday_date($monthyear,$offdays)){
											$a++;
										}
										$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
									}
								}
								$display = chk_company_offday(date("Y-m-d",strtotime($monthyear)),$offdays);
							} else {
								if($year >= date('Y')){
									if(date('m', strtotime($main_arr['Yearly_op4_2'])) > $month){
										if($i==0){
											$year = $year;
										} else {
											$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
										}
									} else {
										$year = date("Y", strtotime('+1 year',strtotime($start_on_date)));
									}
								} else {
									$year = date("Y", strtotime(date("Y-m-d", strtotime($start_on_date)) . " + 1 year"));
								}
								$monthyear = date('Y-m-01',strtotime($main_arr['Yearly_op4_2']." ".$year));

								if($main_arr['Yearly_op4_1'] == '1'){
									$display = chk_company_working_day_next(date("Y-m-d",strtotime($monthyear)),$offdays);
								} else {
									$monthyear = date("Y-m-d",strtotime("-1 days",strtotime($monthyear)));
									for($a=1;$a<=$main_arr['Yearly_op4_1'];$a++){
										$monthyear = date("Y-m-d",strtotime("+1 days",strtotime($monthyear)));
										if(chk_company_offday_date($monthyear,$offdays)){
											$a--;
										}
									}
									$display = date("Y-m-d",strtotime($monthyear));
								}
							}
							$start_date1[] = $display;

						} else {
							break;
						}

						$start_on_date = $display;//date("Y-m-d", strtotime(date("Y-m-d", strtotime($display)) . " + 1 year"));


					}

					$data['start_date'] = reset($start_date1);
					$data['end_date'] = end($start_date1);
					$data['end_after_recurrence'] = count($start_date1);
            }
            else {
			$data['start_date'] = '';
			$data['end_date'] = '';
			$data['end_after_recurrence'] = '';
		}
            return $data;
        }
        function getTotalCustomerList(){
             $CI =& get_instance();
           $CI->db->select('*');
           $CI->db->from('customers');
           $CI->db->where('customer_company_id',$CI->session->userdata('company_id'));
           $CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           return $query->result();
        }
        function get_project_list(){
            $CI =& get_instance();

		$query = $CI->db->query("SELECT c.customer_name,p.company_id,p.project_customer_id,p.project_id,p.project_title,p.project_added_by,project_status,project_start_date,project_end_date FROM (`project` p) LEFT JOIN `project_users` pu ON `p`.`project_id` = `pu`.`project_id` LEFT JOIN customers c ON p.company_id=c.customer_company_id AND p.project_customer_id=c.customer_id WHERE (`pu`.`user_id` = ".get_authenticateUserID()." OR `p`.`project_added_by` = ".get_authenticateUserID().") AND `p`.`is_deleted` != 1 GROUP BY `p`.`project_id` order by p.project_id DESC");
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}
        
        function get_xero_account_info(){
                $CI =& get_instance();
		$CI->db->select('xero_account_code,xero_tax_type');
		$CI->db->from('company');
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
		$query = $CI->db->get();
		
                if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
        }
        
        function getTotalTaskUser($completed_id){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('tasks');
		$CI->db->where('task_company_id',$CI->session->userdata('company_id'));
                $CI->db->where('task_status_id !=',$completed_id);
                $CI->db->where('task_allocated_user_id',  get_authenticateUserID());
                $CI->db->where('is_deleted','0');
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
        }
        
        function getTotalProjectsUser(){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('project p');
                $CI->db->join('project_users pu','pu.project_id = p.project_id');
		$CI->db->where('pu.user_id',  get_authenticateUserID());
                $CI->db->where('p.project_status','Open');
                $CI->db->where('p.is_deleted','0');
                $CI->db->where('pu.is_deleted','0');
		$query = $CI->db->get();
                if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
        }
        
        function getTotalCustomersUser(){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('customers');
                $CI->db->where('customer_company_id', $CI->session->userdata('company_id'));
                $CI->db->where('owner_id',  get_authenticateUserID());
                $CI->db->where('is_deleted','0');
                $CI->db->where('status','active');
                $query = $CI->db->get();
                if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
        }
        
        function get_country_id_by_code($code){
                $CI =& get_instance();
		$CI->db->select('country_id');
		$CI->db->from('country_master');
		$CI->db->where('Countries_ISO_Code',$code);
		$query = $CI->db->get();
		
                return $query->row()->country_id;
        }
        
        
        function user_first_task_date($user_id){
                $CI =& get_instance();

		$query = $CI->db->select('task_scheduled_date')->from('tasks')->where('task_allocated_user_id',$user_id)->order_by('task_id','asc')->limit(1)->get();
		//echo $CI->db->last_query();die;
		if($query->num_rows()>0){
			$res = $query->row();
			$date = $res->task_scheduled_date;
			return $date;
		} else {
			return 0;
		}
        }
        
        function check_virtual_existance_in_cron($master_task_id,$task_orig_scheduled_date,$company_id){
            
		$CI =& get_instance();
		$query = $CI->db->select("task_id,task_due_date,task_status_id,task_allocated_user_id,task_time_estimate,frequency_type,recurrence_type,start_on_date,no_end_date,end_after_recurrence,task_orig_scheduled_date,task_scheduled_date,task_due_date,master_task_id,task_orig_due_date,task_allocated_user_id,Daily_every_weekday,Daily_every_week_day,Daily_every_day,end_by_date,Weekly_week_day,Weekly_every_week_no,Monthly_op1_1,Monthly_op1_2,Monthly_op2_1,Monthly_op2_2,Monthly_op2_3,Monthly_op3_1,Monthly_op3_2,Yearly_op1,Yearly_op2_1,Yearly_op2_2,Yearly_op3_1,Yearly_op3_2,Yearly_op3_3,Yearly_op4_1,Yearly_op4_2,is_deleted")
						->from("tasks")
						->where("master_task_id",$master_task_id)
						->where("task_owner_id != ","0")
						->where("task_allocated_user_id != ","0")
						->where('task_orig_scheduled_date',$task_orig_scheduled_date)
						->where('task_company_id',$company_id)
						->get();
		if($query->num_rows()>0){
			return $query->row_array();
		} else {
			return 0;
		}
	
        }
        
        function count_total_swimlanes(){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('swimlanes');
		$CI->db->where('user_id',$CI->session->userdata('user_id'));
                $CI->db->where('is_deleted','0');
                $CI->db->where('swimlane_status','active');
		$query = $CI->db->get();
		
                return $query->num_rows();
        }
        
        function change_date_format($date){
            $CI =& get_instance();
            $default_format = $CI->config->item('company_default_format');
            if($default_format == 'm/d/Y'){
                $new_date = date("Y-m-d",strtotime($date));
            }else{
                $new_date = date("Y-m-d",strtotime(str_replace(array("/"," ",","),"-", $date)));
            }
            return $new_date;
        }
        
        function countnotscheduledtask(){
            $CI =& get_instance();
            $completed_id = $CI->config->item('completed_id');
            $query = $CI->db->select("task_id")
                            ->from("tasks")
                            ->where("task_status_id !=",$completed_id)
                            ->where("task_owner_id != ","0")
                            ->where("task_allocated_user_id != ","0")
                            ->where('task_company_id',$CI->session->userdata('company_id'))
                            ->where("task_allocated_user_id",get_authenticateUserID())
                            ->where("task_scheduled_date","0000-00-00")
                            ->where("is_deleted","0")
                            ->get();
		//echo $CI->db->last_query();
		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
        }
        
        function get_company_list(){
            $CI = &get_instance();
            $CI->db->select("u.company_id,c.company_name,u.first_name,u.last_name,u.password");
            $CI->db->from("users u");
            $CI->db->join("company c","c.company_id = u.company_id",'left');
            $CI->db->where("u.email",$CI->session->userdata('email'));
            $CI->db->where("u.is_deleted","0");
            $CI->db->where("c.is_deleted","0");
            $CI->db->where("u.user_status","Active");
            $query = $CI->db->get();
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
        }
        
        function count_total_company_project(){
                $CI =& get_instance();
		$CI->db->select('*');
		$CI->db->from('project');
		$CI->db->where('company_id',$CI->session->userdata('company_id'));
                $CI->db->where('is_deleted','0');
                $CI->db->where('project_status','open');
		$query = $CI->db->get();
		
                return $query->num_rows();
        }
        
        function count_total_company_customers(){
           $CI =& get_instance();
           $CI->db->select('*');
           $CI->db->from('customers');
           $CI->db->where('customer_company_id',$CI->session->userdata('company_id'));
           $CI->db->where('is_deleted','0');
           $CI->db->where('status','active');
           $query=  $CI->db->get();
           return $query->num_rows;
		
       }
       
       function get_company_division_count(){
           $CI =& get_instance();
           $CI->db->select('MAX(seq) as seq');
           $CI->db->from('company_divisions');
           $CI->db->where('company_id',$CI->session->userdata('company_id'));
           $CI->db->where('is_delete','0');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            $res = $query->row();
            return $res->seq;
           } else {
            return 0;
           }
       }
       function get_company_department_count($division_id){
           $CI =& get_instance();
           $CI->db->select('MAX(department_seq) as seq');
           $CI->db->from('company_departments');
           $CI->db->where('company_id',$CI->session->userdata('company_id'));
           $CI->db->where('deivision_id',$division_id);
           $CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            $res = $query->row();
            return $res->seq;
           } else {
            return 0;
           }
       }
       function get_company_staff_levels_count(){
           $CI =& get_instance();
           $CI->db->select('MAX(staff_levels_seq) as seq');
           $CI->db->from('staff_levels');
           $CI->db->where('company_id',$CI->session->userdata('company_id'));
           $CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            $res = $query->row();
            return $res->seq;
           } else {
            return 0;
           }
       }
       function get_company_skills_count(){
           $CI =& get_instance();
           $CI->db->select('MAX(skill_seq) as seq');
           $CI->db->from('skills');
           $CI->db->where('company_id',$CI->session->userdata('company_id'));
           $CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            $res = $query->row();
            return $res->seq;
           } else {
            return 0;
           }
       }
       function chargifyCancelSubscrption(){
            $CI =& get_instance();
            require_once APPPATH."libraries/chargify_lib/Chargify.php";
            $CI->config->load('chargify');
            $username = $CI->config->item('API_key');
            $password=$CI->config->item('API_key_pass');
            $query1=$CI->db->get_where('users',array('company_id'=>$CI->session->userdata('company_id'),'is_owner'=>'1'));
            $company_user=$query1->row();
            if($company_user->chargify_customer_id && $company_user->chargify_subscriptions_ID){
                $headers = array(
                'Accept:application/json',
                );
                $url = 'https://schedullo.chargify.com/subscriptions/'.$company_user->chargify_subscriptions_ID.'.json';
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_URL,$url);

                $result = curl_exec($ch);
                $result1 = json_decode($result,TRUE);
            }
       }
       function get_projectlist_of_customer($customer_id){
           $CI =& get_instance();
           $CI->db->select('task_project_id as project_id');
           $CI->db->from('tasks');
           $CI->db->where('exported','0');
           $CI->db->where('is_deleted','0');
           $CI->db->where('task_project_id !=','0');
           $CI->db->where('task_company_id',$CI->session->userdata('company_id'));
           $CI->db->where('customer_id',$customer_id);
           $CI->db->where('task_scheduled_date !=','0000-00-00');
           $CI->db->group_by('project_id');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            return $query->result();
           } else {
            return 0;
           }
       }
       /**
        * Check timesheet have customer or not.
        * @param type $from_date
        * @param type $to_date
        * @param type $user_id
        * @return int
        */
       
       function checkTimesheetHaveCustomer($from_date,$to_date,$user_id){
                $CI =& get_instance();
                $completed = $CI->config->item('completed_id');
                $CI->db->select('c.*');
                $CI->db->from('tasks t');
                $CI->db->join('customers c','c.customer_id= t.customer_id','left');
                $CI->db->where('c.customer_company_id',$CI->session->userdata('company_id'));
                $CI->db->where('t.task_scheduled_date >=',$from_date);
                $CI->db->where('t.task_scheduled_date <=',$to_date);
                $CI->db->where('t.task_status_id',$completed);
                $CI->db->where('t.task_company_id',$CI->session->userdata('company_id'));
                $CI->db->where('t.is_deleted','0');
                $CI->db->where('t.is_personal','0');
                $CI->db->where('t.task_allocated_user_id',$user_id);
                $CI->db->where('t.customer_id!=','');
                $CI->db->where('t.customer_id!=','0');
                $CI->db->where('t.exported','0');
                $CI->db->group_by('t.customer_id');
                $query = $CI->db->get();
              
                if($query->num_rows()>0){
                    return 1;
                }else{
                    return 0;
                }
       }
       
       function get_user_last_remember_calendar_team_id(){
           $CI =& get_instance();
           $CI->db->select('calender_team_user_id');
           $CI->db->from('last_remember_search');
           $CI->db->where('user_id',$CI->session->userdata('user_id'));
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            return $query->row()->calender_team_user_id;
           } else {
            return 0;
           }
       }
                
       
       function get_customer_user_list($customer_id = ''){
           $CI =& get_instance();
           $CI->db->select('u.*,c.customer_name');
           $CI->db->from('users u');
           $CI->db->join('customers c','c.customer_id = u.customer_user_id','left');
           $CI->db->where('u.company_id',$CI->session->userdata('company_id'));
           $CI->db->where('c.customer_company_id',$CI->session->userdata('company_id'));
           $CI->db->where('u.is_deleted','0');
           if($customer_id != ''){
               $CI->db->where('u.customer_user_id',$customer_id);
           }else{
               $CI->db->where('u.customer_user_id !=','');
           }
           $CI->db->order_by('u.first_name','asc');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            return $query->result();
           } else {
            return 0;
           }
       }
       
       /**
        * count customer user for update on chargify
        */
       function count_customer_user_by_company($id)
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(user_id) as TOTAL')->where("company_id",$id)->where("user_status","Active")->where("is_deleted","0")->where('is_customer_user','1')->get('users')->row()->TOTAL;
	}
        
        /**
         * Get single customer user info
         */
        function get_one_customer_user($id){
           $CI =& get_instance();
           $CI->db->select('u.*,c.customer_name');
           $CI->db->from('users u');
           $CI->db->join('customers c','c.customer_id = u.customer_user_id','left');
           $CI->db->where('u.company_id',$CI->session->userdata('company_id'));
           $CI->db->where('c.customer_company_id',$CI->session->userdata('company_id'));
           $CI->db->where('u.is_deleted','0');
           $CI->db->where('u.user_id ',$id);
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            return $query->row();
           } else {
            return 0;
           }
        }
        
       /**
        * get customer user access module info
        */
        
        function customer_user_access($user_access = ''){
           $CI =& get_instance();
           $CI->db->select('*');
           $CI->db->from('authorization');
           if($user_access){
               $CI->db->where('authorization_type','customer user');
           }else{
               $CI->db->where('authorization_type','admin');
           }
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            return $query->row();
           } else {
            return 0;
           }
        }
       
        
       function get_filters_value($select_data){
           $data = array();
           if(isset($select_data) && !empty($select_data)){
                foreach($select_data as $d){
                    switch($d['name']){
                        case 'projects':
                            $data['projects'][] = $d['value'];
                            break;
                        case 'customers':
                            $data['customers'][] = $d['value'];
                            break;
                        case 'start_date':
                            $data['start_date'] = $d['value'];
                            break;
                        case 'end_date':
                            $data['end_date'] = $d['value'];
                            break;
                        case 'users':
                            $data['users'][] = $d['value'];
                            break;
                        case 'category':
                            $data['category'][] = $d['value'];
                            break;
                        case 'subcategory':
                            $data['subcategory'][] = $d['value'];
                            break;
                        case 'division':
                            $data['division'][] = $d['value'];
                            break;
                        case 'department':
                            $data['department'][] = $d['value'];
                            break;
                        case 'by_date':
                            $data['by_date'][] = $d['value'];
                            break;
                        case 'task_status':
                            $data['task_status'][] = $d['value'];
                            break;
                    }
                }
            }
            return $data;
       }
        
       function getUserFilters(){
           $CI =& get_instance();
           $CI->db->select('*');
           $CI->db->from('user_filters');
           $CI->db->where('user_id',$CI->session->userdata('user_id'));
           $CI->db->where('is_deleted','0');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
                return $query->result();
           } else {
                return 0;
           }
       }
        function customer_user_customer_list(){
            $CI =& get_instance();
            $CI->db->select('customer_user_id');
            $CI->db->from('users');
            $CI->db->where('user_id',get_authenticateUserID());
            $CI->db->where('is_customer_user','1');
            $query=  $CI->db->get();
            if($query->num_rows()>0){
             return $query->row()->customer_user_id;
            } else {
             return 0;
            }
        }
        
        function getUserListFromTask($user_id){
           $CI =& get_instance();
           $CI->db->select('task_allocated_user_id');
           $CI->db->from('tasks');
           $CI->db->where('task_company_id',$CI->session->userdata('company_id'));
           $CI->db->where('is_deleted','0');
           $CI->db->where('task_allocated_user_id !=',$user_id);
           $CI->db->where('task_owner_id',$user_id);
           $CI->db->group_by('task_allocated_user_id');
           $query=  $CI->db->get();
           if($query->num_rows()>0){
            return $query->result();
           } else {
            return 0;
           }
       }
        
        
        function get_project_customer($project_id){
                $CI =& get_instance();
                $CI->db->select('project_customer_id');
                $CI->db->from('project');
                $CI->db->where('project_id',$project_id);
                $CI->db->where('company_id',$CI->session->userdata('company_id'));
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->row()->project_customer_id;
                }else{
                    return 0;
                }
        }
        
        function get_user_last_login_date($user_id){
                $CI =& get_instance();
                $CI->db->select('MAX(user_login_date) as date');
                $CI->db->from('user_login_history');
                $CI->db->where('user_id',$user_id);
                $query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->row()->date;
                }else{
                    return 0;
                }
        }   
        
        function get_user_customer_id($user_id){
                $CI =& get_instance();
                $CI->db->select('customer_user_id');
                $CI->db->from('users');
                $CI->db->where('user_id',$user_id);
                $CI->db->where('is_customer_user','1');
                $CI->db->where('is_deleted','0');
                $query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->row()->customer_user_id;
                }else{
                    return 0;
                }
        }
        
        
        function get_external_user_access(){
                $CI =& get_instance();
                $CI->db->select('addon_access');
                $CI->db->from('addons');
                $CI->db->where('addon_name','External users');
                $query = $CI->db->get();
                if($query->num_rows()>0){
                    return $query->row()->addon_access;
                }else{
                    return 0;
                }
        }
        
        function create_jira_ticket($exception){
            $CI =& get_instance();
            $user_info = get_user_info(get_authenticateUserID());
            $user_details = "User name :".$user_info->first_name." ".$user_info->last_name." , User ID :".$user_info->user_id." , Company ID: ".$user_info->company_id;
            
                $data = array(
                            "fields"=>array(
                                "project"=>array(
                                    "key"=>"MVP"
                                ),
                                "summary"=>$exception['message'],
                                "description"=>'Type: '. $exception['type'] ."
                                                Message: ".$exception['message'] ."
                                                Filename: ". $exception['filepath'] ."
                                                Line Number: ".$exception['lineno']."
                                                Server Name:".$_SERVER['SERVER_NAME']."
                                                User Info: ".$user_details,    
                                "issuetype"=>array(
                                    "name"=>"Bug"
                                ),
                                "priority"=>array(
                                    "name"=>"Low"
                                )
                            )
                        );
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => JIRA_URL."rest/api/2/issue/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode($data),
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".JIRA_ACCESS,
                    "content-type: application/json",
                  ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
        }
    
       function in_array_r($needle, $haystack) {
            foreach ($haystack as $key=>$value) {
                if(is_array($value)){
                    foreach($value as $v){
                        if($v === $needle){
                            return true;
                        }
                    }
                }
            }
            return false;
        }
       
       function in_array_key($needle, $haystack) {
            foreach ($haystack as $key=>$value) {
                if($key === $needle){
                    return true;
                }
            }
            return false;
        }
?>
