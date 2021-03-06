<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// --------------------------------------------------------------------

	/**
	 * Site Base Path
	 *
	 * @access	public
	 * @param	string	the Base Path string
	 * @return	string
	 */
	/**
         * This function returns base path url.
         * @returns string
         */
	function base_path()
	{		
		$CI =& get_instance();
		return $base_path = $CI->config->slash_item('base_path');		
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
	
	// --------------------------------------------------------------------

	/**
	 * Site Front ActiveTemplate
	 *
	 * @access	public
	 * @param	string	current theme folder name
	 * @return	string
	 */
	 function get_rights($rights_name)
	{
		return true;
		$CI =& get_instance();
		$right_detail = $CI->db->get_where("rights",array('rights_name'=>trim($rights_name)));
		
		if($right_detail->num_rows()>0)
			{
			
				$right_result=$right_detail->row();
				$rights_id=$right_result->rights_id;

			$query=$CI->db->get_where("rights_assign",array('rights_id'=>$rights_id,'admin_id'=>$CI->session->userdata('admin_id')));
			
			if($query->num_rows()>0)
			{
				$result=$query->row();
				
				if($result->rights_set=='1' || $result->rights_set==1)
				{
					return 1;
				}
				else
				{
					return 0;
				}					
			}
			else
			{
				return 0;
			}	
		}
		else
		{
			return 0;		
		}
	
	}
	/**
         * This function get theme name from DB and returns.
         * @returns string
         */
	function getThemeName()
	{
		
		$default_theme_name='default';
		
		$CI =& get_instance();
		$query = $CI->db->get_where("template_manager",array('active_template'=>1 ,'is_admin_template'=>1));
		$row = $query->row();
		
		$theme_name=trim($row->template_name);
		
		if(is_dir(APPPATH.'views/'.$theme_name))
		{
			return $theme_name;
		}
		else
		{
			return $default_theme_name;	
		}
		
	}
	
		
	
	// --------------------------------------------------------------------

	/**
	 * Check user login
	 *
	 * @return	boolen
	 */
	 	/**
         * It check admin_id in DB for authentication.
         * @returns boolean
         */
	function check_admin_authentication()
	{		
		$CI =& get_instance();
		
			if($CI->session->userdata('admin_id')!='')
			{
				return true;
			}
			else
			{
				return false;
			}
	
	}
        /**
         * It returns payment setting from DB
         * @returns
         */
	  function payment_setting()
	{		
		$CI =& get_instance();
		$query = $CI->db->get("payment_setting");
		return $query->row();
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * get login user id
	 *
	 * @return	integer
	 */
	/**
         * This function returns loggedin user id from session.
         * @returns int
         */
	function get_authenticateadminID()
	{		
		$CI =& get_instance();
		return $CI->session->userdata('admin_id');
	}
	
	function checkSuperAdmin()
	{
		$CI =& get_instance();
		
			if($CI->session->userdata('admin_type')=='1')
			{
				return true;
			}
			else
			{
				return false;
			}
	}
	
	function get_timezone()
	{		
		$CI =& get_instance();
		$query = $CI->db->select("*")->from("timezone")->order_by("timezone_id","asc")->get();
		return $query->result();
	
	}
	/**
         * This function return first day of week by using date.
         * @param  $date
         * @return string
         */
	function get_first_day_of_week($date) 
	{
		 $day_of_week = date('N', strtotime($date)); 
		 $week_first_day = date('Y-m-d', strtotime($date . " - " . ($day_of_week - 1) . " days")); 
		 return $week_first_day;
	}

	/**
         * It rerurns last day of week
         * @param  $date
         * @returns string
         */
	function get_last_day_of_week($date)
	{
		 $day_of_week = date('N', strtotime($date)); 
		 $week_last_day = date('Y-m-d', strtotime($date . " + " . (7 - $day_of_week) . " days"));   
    	 return $week_last_day;
	}
	
	/************************************************report end****************************/
	
	/** send email
	 * @return	integer
	 */
	/**
         * This function is used for email send.
         * @param  $email_address_from
         * @param  $email_address_reply
         * @param  $email_to
         * @param  $email_subject
         * @param  $str
         * @returns void
         */
	 
	function email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str)
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
		
		$CI->email->initialize($config);	
				
		
		$CI->email->from($email_address_from,"Schedullo Team");
		$CI->email->reply_to($email_address_reply);
		$CI->email->to($email_to);
		$CI->email->subject($email_subject);
		$CI->email->message($str);
		$CI->email->send();

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
	/**
         * It creates random code with special characters.
         * @returns string
         */
	function randomSpecialCode()
	{
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@!#$%^&*";
		$pass = array(); 
		
		for ($i = 0; $i < 12; $i++) {
		$n = rand(0, strlen($alphabet)-1); //use strlen instead of count
		$pass[$i] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	
	/**
         * It create random numeric code.
         * @returns string
         */
	function randomnumericCODE()
	{
		$alphabet = "0123456789";
		$pass = array(); 
		
		for ($i = 0; $i < 5; $i++) {
		$n = rand(0, strlen($alphabet)-1); //use strlen instead of count
		$pass[$i] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	/**
	 * generate random code
	 *
	 * @return	string
	 */
	
	function randomCodeNew()
	{
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ";
		$number = "0123456789";
		$pass = array(); 
		$q=rand(1,strlen($alphabet)-1);
		$pass[0] = $alphabet[$q];
		for ($i = 1; $i < 6; $i++) {
		$n = rand(0, strlen($number)-1); //use strlen instead of count
		$pass[$i] = $number[$n];
		}
		return implode($pass); //turn the array into a string
	}
	
	/*** load site setting
	*  return single record array
	**/
	/**
         * It returns site related sitting from db.
         * @returns array
         */
	
	function site_setting()
	{		
		$CI =& get_instance();
		$query = $CI->db->get("site_setting");
		return $query->row();
	
	}
	
	function seo_setting()
	{		
		$CI =& get_instance();
		$query = $CI->db->get("Seosetting");
		return $query->row();
	
	}
	
	function image_setting()
	{	
	
		$CI =& get_instance();
		$query = $CI->db->get("image_setting");
		return $query->row();
	
	}
		
	/*** get all languages details
	*  return all record array
	**/
	
	function get_languages()
	{		
		$CI =& get_instance();
		$query = $CI->db->get('language');
		return $query->result();
	
	}

	
	/*** get user name
	*  return string username
	**/
	
        /**
         * This function get user info from DB via user id.
         * @param int $user_id
         * @returns array
         */
	
	function get_user_info($user_id='')
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("user",array('user_id'=>$user_id));
		return $query->row();
	}
	
        /**
         * This function returns admin via using id.
         * @param int $id
         * @returns string
         */
	function get_admin_name($id){
	
		$CI =& get_instance();
			$CI->db->select('first_name,last_name');
			$CI->db->where('admin_id',$id);
			$query=$CI->db->get('admin');
			if($query->num_rows()>0){
			return  ucwords($query->row()->first_name.' '.$query->row()->last_name);
		}else{
			return '';
		}
			
		
	}
	
	/*** get user name
	*  return string username
	**/
	/**
         * This function get user name via id.
         * @param int $id
         * @returns array|string
         */
	
	function get_user_name($id){
	$CI =& get_instance();
		
			$CI->db->select('first_name,last_name,profile_image');
			$CI->db->where('user_id',$id);
			$query=$CI->db->get('user');
			if($query->num_rows()>0){
			return  $query->row();
		}else{
			return '';
		}
			
		
	}
		
	/****  create seo friendly url 
	* var string $text
	**/ 	  
  
  	function clean_url($text) 
	{ 
	
		$text=strtolower($text); 
		$code_entities_match = array( '&quot;' ,'!' ,'@' ,'#' ,'$' ,'%' ,'^' ,'&' ,'*' ,'(' ,')' ,'+' ,'{' ,'}' ,'|' ,':' ,'"' ,'<' ,'>' ,'?' ,'[' ,']' ,'' ,';' ,"'" ,',' ,'.' ,'_' ,'/' ,'*' ,'+' ,'~' ,'`' ,'=' ,' ' ,'---' ,'--','--','�'); 
		$code_entities_replace = array('' ,'-' ,'-' ,'' ,'' ,'' ,'-' ,'-' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'-' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'-' ,'' ,'-' ,'-' ,'' ,'' ,'' ,'' ,'' ,'-' ,'-' ,'-','-'); 
		$text = str_replace($code_entities_match, $code_entities_replace, $text); 
		return $text; 
	} 
	
	
	
	
	function get_currency()
	{		
		$CI =& get_instance();
		$query = $CI->db->get('currency_code');
		return $query->result();
	
	}
	
	 function get_all_country()
	 {
	 	$CI =& get_instance();
		$query = $CI->db->get('country_master');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	 }

	function getDuration($date)
	{
	
	$CI =& get_instance();

		$curdate = date('Y-m-d H:i:s');
		/*echo 'curdate'.$curdate.'<br>';
		echo 'date'.$date;die;*/
		
		$diff = abs(strtotime($date) - strtotime($curdate));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 )/ (60*60));
		$mins = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ (60));
		
		$ago = '';
		if($years != 0){ if($years > 1) {$ago = $years.' years';} else { $ago = $years.' year';}}
		elseif($months != 0){ if($months > 1) {$ago = $months.' months';} else { $ago = $months.' month';}}
		elseif($days != 0) { if($days > 1) {$ago = $days.' days';} else { $ago = $days.' day';}}
		elseif($hours != 0){ if($hours > 1) {$ago = $hours.' hours';} else { $ago = $hours.' hour';}}
		else{ if($mins > 1) {$ago = $mins.' minutes';} else { $ago = $mins.' minute';}}
		return $ago.' ago';
}

	function get_all_countries()
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("country_master");
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	
	function getActiveCountry()
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("country_master",array('status'=>'Active'));
		if($query->num_rows() > 0)
		{
			return $query->result();
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
	
	function getActiveplan()
	{
		$CI =& get_instance();
		$query = $CI->db->get_where("plans",array('plan_status'=>'Active','is_deleted'=>'0'));
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	/**
         * According to admin id, it will returns admin detail .
         * @param int $admin_id
         * @returns Array
         */
	function getOneAdmin($admin_id=0)
	{
		$CI =& get_instance();
		$query=$CI->db->get_where('admin',array('admin_id'=>$admin_id));
		if($query->num_rows() > 0)
		{
			return $query->row();
		}else{
			return '';
		}
	}
	
	
	function getadminRights()
	{
		$CI =& get_instance();
		$CI->db->select('r.rights_name,ra.*');
		$CI->db->from('rights_assign ra');
		$CI->db->join('rights r','ra.rights_id=r.rights_id');
		$CI->db->where('ra.admin_id',get_authenticateadminID());
		$query=$CI->db->get();
		$r=array();
		if($query->num_rows() > 0)
		{
			$r=array();
			foreach ($query->result() as $value) {
				$r[$value->rights_name]=$value;
			}
			return $r;
		}else{
			return $r;
		}
	}
	
	function get_all_data($tablename,$columnname,$id){
        $CI =& get_instance();      
        $CI->db->select('*');
        $CI->db->from($tablename);
        $CI->db->where($columnname,$id);
        $query = $CI->db->get();
        if($query->num_rows()>0){
            return  $query->row_array();
        }else{
            return 0;
        }
            
        
    }

	
	
	
	function pr($x='')
	{
		echo '<pre>';
		print_r($x);
		echo '</pre>';
	}
	
	/* Function for get lattitude and longitude of location */
	function getCoordinatesFromAddress($addr='',$city='',$state='',$country='' )
	{
		//$sQuery=$addr.'+'.$city.'+'.$state.'+'.$country;
		
		//$sURL = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($sQuery).'&sensor=false';
		$sQuery=$addr.'+'.$city;
		$sURL = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($sQuery).'&sensor=false&region='.$country;
		
		$sData = file_get_contents($sURL);
		$data=json_decode($sData);
		$result=$data->results;
		
		
		if(isset($result[0]->geometry->location) && $result[0]->geometry->location!='')
		{
			$res=array('lat'=>$result[0]->geometry->location->lat,'lng'=>$result[0]->geometry->location->lng);
			return $res;
		}
		else
		{
			$res=array('lat'=>'','lng'=>'');
			return $res;
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
	
	/* Sate And City Code */
	function get_all_state_by_country_id($id=0)
		{
		$CI =& get_instance();
		//$query = $CI->db->get_where("state_master",array('status'=>'active','country_id'=>$id));
		$CI->db->where(array('status'=>'active','country_id'=>$id));
		$CI->db->order_by('state_name','asc');
		$query = $CI->db->get('state_master');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return '';
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
	
	
	function check_IdExit($table,$id,$val,$where='',$sp_table=''){
		
		$CI =& get_instance();
		if($where != ''){ $CI->db->where($where);}
		
		if($CI->session->userdata('language')=='english'){
			$query = $CI->db->get_where($table,array($id=>$val));
		}
		else{
			if($sp_table == ''){
				$sp_table = $table.'_es';
			}
			$query = $CI->db->get_where($sp_table,array($id=>$val));
		}
		//echo $CI->db->last_query();
		
		if($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	function getAllCategory()
	{
		$CI =& get_instance();

		$query = $CI->db->select('pce.category_id, pce.category_name, pcs.category_id as es_category_id, pcs.category_name as es_category_name')
			 ->from('product_category pce')
			 ->join('product_category_es pcs','pce.category_id=pcs.category_id')
			 ->where(array('pce.category_status'=>'active','pce.is_deleted'=>0))
			 ->where(array('pcs.category_status'=>'active','pcs.is_deleted'=>0))
			 ->order_by('pce.category_id','asc')
			 ->get();
	 
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		
		return '';
	}
	
	function getAllProductType($id=0)
	{
		$CI =& get_instance();
		
		if($id > 0){
			 $query = $CI->db->select('pte.product_category_id,pte.product_type_id, pte.product_type, pts.product_type_id as es_product_type_id, pts.product_type as es_product_type')
				 ->from('product_type pte')
				 ->join('product_type_es pts','pte.product_type_id=pts.product_type_id')
				 ->where(array('pts.product_category_id'=>$id))
				 ->where(array('pte.status'=>'Active','pte.is_deleted'=>0))
				 ->where(array('pts.status'=>'Active','pts.is_deleted'=>0))
				 ->order_by('pte.product_type_id','asc')
				 ->get();
		} else {
			$query = $CI->db->select('pte.product_category_id,pte.product_type_id, pte.product_type, pts.product_type_id as es_product_type_id, pts.product_type as es_product_type')
				 ->from('product_type pte')
				 ->join('product_type_es pts','pte.product_type_id=pts.product_type_id')
				 ->where(array('pte.status'=>'Active','pte.is_deleted'=>0))
				 ->where(array('pts.status'=>'Active','pts.is_deleted'=>0))
				 ->order_by('pte.product_type_id','asc')
				 ->get();
		}
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		
		return '';
	}
	
	
	function getStates()
	{
		
		$CI =& get_instance();
		$map[] = array('name'=> "Naco",'title'=> "Naco, Santo Domingo, Dominican Republic",'latitude'=>18.4715691,'longitude'=>-69.92823390000001);
		$map[] = array('name'=> "Zona Universitaria",'title'=> "Zona Universitaria, Santo Domingo, Dominican Republic",'latitude'=>18.4605125,'longitude'=>-69.9178114);
		$map[] = array('name'=> "Bella Vista",'title'=> "Bella Vista, Santo Domingo, Dominican Republic",'latitude'=>18.4689033,'longitude'=>-69.93613619999998);
		$map[] = array('name'=> "Piantini",'title'=> "Piantini, Santo Domingo, Dominican Republic",'latitude'=>18.4558831,'longitude'=>-69.9401451);
		$map[] = array('name'=> "Mirador Norte",'title'=> "Mirador Norte, Santo Domingo, Dominican Republic",'latitude'=>18.4524769,'longitude'=>-69.9542894);
		$map[] = array('name'=> "Mirador Sur",'title'=> "Mirador Sur, Santo Domingo, Dominican Republic",'latitude'=>18.4449326,'longitude'=>-69.9565227);
		$map[] = array('name'=> "Ensanche Quisqueya",'title'=> "Ensanche Quisqueya, Santo Domingo, Dominican Republic",'latitude'=>18.4634535,'longitude'=>-69.94461180000002);
		$map[] = array('name'=> "The Million",'title'=> "El Millon, Santo Domingo, Dominican Republic",'latitude'=>18.4578413,'longitude'=>-69.9580115);
		$map[] = array('name'=> "The Cacicazgos",'title'=> "Chiefdoms, Santo Domingo, Dominican Republic",'latitude'=>18.440571500000004,'longitude'=>-69.96843339999998);
		$map[] = array('name'=> "Gazcue",'title'=> "Chiefdoms, Santo Domingo, Dominican Republic",'latitude'=>18.4646967,'longitude'=>-69.90158840000004);
		$map[] = array('name'=> "The Grove",'title'=> "La Arboleda, Santo Domingo, Dominican Republic",'latitude'=>18.475953,'longitude'=>-69.92302269999999);
		$map[] = array('name'=> "Paradise Ensanche",'title'=> "Ensanche Paraíso, Santo Domingo, Dominican Republic",'latitude'=>18.480121,'longitude'=>-69.94163400000002);
		$map[] = array('name'=> "Julieta Morales",'title'=> "Julieta Morales, Santo Domingo, Dominican Republic",'latitude'=>18.4751097,'longitude'=>-69.94758949999999);
		$map[] = array('name'=> "Los Prados",'title'=> "Los Prados, Santo Domingo, Dominican Republic",'latitude'=>18.4732569,'longitude'=>-69.9565227);
		$map[] = array('name'=> "The Esperilla",'title'=> "La Esperilla, Santo Domingo, Dominican Republic",'latitude'=>18.4693355,'longitude'=>-69.92078930000002);
		$map[] = array('name'=> "Mata Hunger",'title'=> "Mata Hambre, Santo Domingo, Dominican Republic",'latitude'=>18.4552029,'longitude'=>-69.92748940000001);
		$map[] = array('name'=> "Arroyo Hondo Viejo",'title'=> "Viejo Arroyo Hondo, Santo Domingo, Dominican Republic",'latitude'=>18.4944624,'longitude'=>-69.94547);
		$map[] = array('name'=> "San Geronimo",'title'=> "San Gerónimo, Santo Domingo, Dominican Republic",'latitude'=>18.469824,'longitude'=>-69.9639669);
		$map[] = array('name'=> "Renaissance",'title'=> "Renacimiento, Santo Domingo, Dominican Republic",'latitude'=>18.4462364,'longitude'=>-69.96843339999998);
		$map[] = array('name'=> "The Julia",'title'=> "La Julia, Santo Domingo, Dominican Republic",'latitude'=>18.4614933,'longitude'=>-69.92674499999998);
		$map[] = array('name'=> "The Renovators",'title'=>"Los Restauradores, Santo Domingo, Dominican Republic",'latitude'=>18.4572405,'longitude'=>-69.9654557);
		$map[] = array('name'=> "Widening Carmelita",'title'=>"Ensanche Carmelita, Santo Domingo, Dominican Republic",'latitude'=>18.4803116,'longitude'=>-69.94982279999999);
		
		return $map;
	
	}


	function get_recipe_by_number($number=0)
	{
		$CI =& get_instance();
		$CI->db->where(array('recipi_number'=>$number));
		$query = $CI->db->get('recipi');
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			return $result->recipi_id;
		}else{
			return '0';
		}
	}

	function count_user_by_company($id)
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(user_id) as TOTAL')->where("company_id",$id)->where("user_status","Active")->where('is_customer_user','0')->where("is_deleted","0")->get('users')->row()->TOTAL;
	}
	function count_customer_user_by_company($id)
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(user_id) as TOTAL')->where("company_id",$id)->where("user_status","Active")->where('is_customer_user','1')->where("is_deleted","0")->get('users')->row()->TOTAL;
	}
	function getAllCustomer_count()
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(user_id) as TOTAL')->where("is_deleted","0")->get('users')->row()->TOTAL;
	}
	
	function check_user_avaibility_by_email($email)
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(user_id) as TOTAL')->where(array("is_deleted"=>"0",'email'=>$email))->get('users')->row()->TOTAL;
	}

	function getAllCompany_count()
	{
		$CI =& get_instance();
		return $CI->db->select('COUNT(company_id) as TOTAL')->where("is_deleted","0")->get('company')->row()->TOTAL;
	}
	/**
         * Returns company name via company_id.
         * @param  $company_id
         * @returns string
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
         * It will return company timezone via company_id.
         * @param int $company_id
         * @returns string
         */
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
	/**
         * Returns default calender setting of company.
         * @param int $company_id
         * @returns string
         */
	function getCompanyCalendar($company_id){
		$CI =& get_instance();
		$query = $CI->db->select('*')->from('default_calendar_setting')->where('comapny_id',$company_id)->get();
		if($query->num_rows()>0){
			$res = $query->row_array();
			return $res;
		} else {
			return 0;
		}
	}
	/**
         * Returns color list from DB.
         * @returns array
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
	
	
	function get_user_count_under_manager($user_id){
		$CI =& get_instance();
		
		$query = $CI->db->select("user_id")->from("user_managers")->where("manager_id",$user_id)->get();
		return $query->num_rows();
		
		
	}
	
	function getNextStep($type)
	{
		$CI =& get_instance();
		$query = $CI->db->select('as_step_id')
						->from('popup_setup')
						->where('as_type',$type)
						->get();
						
		if($query->num_rows()>0){
			return $query->num_rows()+1;
		} else {
			return 1;
		}
	}
	function getStep($step_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('as_step_sequence')
						->from('popup_setup')
						->where('as_step_id',$step_id)
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->as_step_sequence;
		}
	}
	
	function getStepDetail($step_id)
	{
		$CI =& get_instance();
		$query = $CI->db->select('as_step_detail')
						->from('popup_setup')
						->where('as_step_id',$step_id)
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->as_step_detail;
		}
	}
	
	function get_user_password($email)
	{
		$CI =& get_instance();
		$query = $CI->db->select('password')
						->from('users')
						->where('email',$email)
						->limit(1)
						->get();
						
		if($query->num_rows()>0){
			return $query->row()->password;
		}
	}
        
        function getIphoneAppInfo(){
            $CI =& get_instance();
            $CI->db->select('*');
            $CI->db->from('app_registration');
            $CI->db->where('api_company_id',0);
            $query = $CI->db->get();
            if($query->num_rows()>0){
                return $query->row();
            }else{
                return 0;
            }
        }
        
        
        function get_staff_levels($id){
                $CI =& get_instance();
                $CI->db->select('staff_level_title,staff_level_id');
		$CI->db->from('staff_levels');
		$CI->db->where('company_id',$id);
		$CI->db->where('is_deleted','0');
                $query = $CI->db->get();
                if ($query->num_rows() > 0) {
		    return $query->result_array();
		}else{
                    return 0;
                }
        }


/* End of file custom_helper.php */
/* Location: ./system/application/helpers/custom_helper.php */

?>
