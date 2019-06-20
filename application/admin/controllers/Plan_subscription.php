<?php
/**
 * This class is used to create plan subscription page for admin panel.This class function create plan subscriber list.   
 * This class is extending the CI_Controller 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v 0.1 Dev
 * @package    CI_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

*/
class Plan_subscription extends  CI_Controller {
    /**
        * It default constuctor which is called when Plan_subscription class object is initialzied. It loads necessary models,library, and config.
        * @returns void
        */  
	function Plan_subscription()
	{
             /**
             * call base class contructor
             */
		parent::__construct();	
                /*
                 * load database class for plan subscription
                 */
		$this->load->model('plan_subscription_model');	
                /*
                 * load library for create pagination
                 */
		$this->load->library('pagination');
	   
	}
	//use:for redirecting at list user page
	  /**
         * This function is checked admin is loggedin or not,than it redirect on specific page.
         * @returns void
         */
	function index()
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		redirect('plan_subscription/list_plan_subscription');
	}
	
	/* user list
	 * param  : limit,offset,msg
	 * 
	 */
	/**
         * This function is used for create subscriber list page in admin panel.it get subscriber details and render subscriber page.
         * @param int $limit
         * @param int $offset
         * @param int $msg
         * @returns void
         */
	function list_plan_subscription($limit='20',$offset=0,$msg='') {
		
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		$check_rights=get_rights('list_plan_subscription');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'plan_subscription/list_plan_subscription/'.$limit.'/';
                /* get total subscriber*/
		$config['total_rows'] = $this->plan_subscription_model->get_total_plan_subscription_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		/* get subscriber details*/
		$data['result'] = $this->plan_subscription_model->get_plan_subscription_result($offset,$limit);
		$data['msg'] = $msg;
		
		$data['offset'] = $offset;
		$data['error']='';
		if($this->input->post('limit') != '')
		{
			$data['limit']=$this->input->post('limit');
		}
		else
		{
			$data['limit']=$limit;
		}
		$data['option']='1V1';
		$data['keyword']='1V1';
		$data['serach_option']='1V1';
		$data['serach_keyword']='1V1';
		$data['search_type']='normal';
		$data['redirect_page']='list_plan_subscription';
		/*create subscriber page*/
		$data['site_setting'] = site_setting();
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/Plan_subscription/list_plan_subscription',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	
	/* search patitent
	 * param  : doctor id ,limit,option,keyword,offset,msg
	 * 
	 */
	/**
         *  This function check admin authentication and get subscription details and show in list. 
         * @param int $limit
         * @param string $option
         * @param string $keyword
         * @param int $offset
         * @param string $msg
         * @returns void
         */
	function search_list_plan_subscription($limit=20,$option='',$keyword='',$offset=0,$msg='')
	{
		if(!check_admin_authentication())
		{
			redirect('home');
		}
		$theme = getThemeName();
		$this->template->set_master_template($theme .'/template.php');
		$redirect_page = 'search_list_plan_subscription';
		
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		
		//$check_rights=get_rights('search_list_plan_subscription');
		
		//if(	$check_rights==0) {			
		//	redirect('home/dashboard/no_rights');	
		//}
                /* check send method and tril & replace speical character from search string */
		if($_POST)
		{		
			$option=$this->input->post('option');
			$keyword=($this->input->post('keyword')!='')?str_replace(" ", "-",trim($this->input->post('keyword'))):'1V1';
		}
		else
		{
			$option=$option;
			$keyword=str_replace(" ", "-",trim($keyword));	
		}
		
		$keyword=str_replace('"','',str_replace(array("'",",","%","$","&","*","#","(",")",":",";",">","<","/"),'',trim($keyword)));
	
		$config['uri_segment']='6';
		$config['base_url'] = base_url().'plan_subscription/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/';
                /* get total rows of plan subscription*/
		$config['total_rows'] = $this->plan_subscription_model->get_total_search_plan_subscription_count($option,$keyword);
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		$data['all_country']=getActiveCountry();
                /* this function get data of search option */
		$data['result'] = $this->plan_subscription_model->get_search_plan_subscription_result($option,$keyword,$offset, $limit);
		
		$data['msg'] = $msg;
		$data['offset'] = $offset;
		$data['site_setting'] = site_setting();
		
		$data['limit']=$limit;
		$data['option']=$option;
		$data['keyword']=$keyword;
		$data['search_type']='search';
		$data['redirect_page']=$redirect_page;
		
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/Plan_subscription/list_plan_subscription',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
	}
	/**
         * This function is used for delete subscriber from list
         * @param int $id
         * @param string $redirect_page
         * @param string $option
         * @param string $keyword
         * @param int $limit
         * @param int $offset
         * @returns void
         */
	
	
	/*add new plan_subscription also called in plan_subscription update
	 * param  : limit
	 * 
	 */

	/*delete plan_subscription data
	 * param  : plan_subscription id,doctor id ,option,keyword,limit,offset,msg
	 * 
	 */
	function delete($id=0,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_plan_subscription');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		//$this->db->delete('rights_assign',array('store_id'=>$id));
		//$this->db->where('user_id',$id);
		//$this->db->delete('plan_subscription');
		
		$this->db->where("user_id",$id);
		$this->db->update("users",array("is_deleted"=>1));
        
		//$this->db->delete('plan_subscription',array('store_id'=>$id));
		if($redirect_page == 'list_plan_subscription')
		{
			redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
		}
		else
		{
			redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
		}
	}
	
	/* Send customer reset password
	 * param  : plan_subscription id ,option,keyword,limit,offset,msg
	 * 
	 */
	function reset_password_user($id=0,$redirect_page='',$option='',$keyword='',$limit=20,$offset=0)
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_plan_subscription');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		//$this->db->delete('rights_assign',array('store_id'=>$id));
		//$this->db->where('user_id',$id);
		//$this->db->delete('plan_subscription');
		$this->plan_subscription_model->forgot_password($id);
		
		
		$this->session->set_flashdata('msg', "sent");
        
		//$this->db->delete('plan_subscription',array('store_id'=>$id));
		if($redirect_page == 'list_plan_subscription')
		{
			redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$offset.'/sent');
		}
		else
		{
			redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/sent');
		}
	}
	
	/*delete , active , inactive multiple plan_subscription at a time
	 * param  : plan_subscription id,doctor id ,redirect page,search option,search keyword,limit,offset,msg
	 * 
	 */ 
	function action()
	{
		/*
		 * Future enhancement
		 * when assigning rights is used
		*/
		$check_rights=get_rights('list_plan_subscription');
		
		if(	$check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		$offset=$this->input->post('offset');
		
		$limit = $this->input->post('limit');
		$action=$this->input->post('action');
		
		$redirect_page = $this->input->post('redirect_page');
		$option = $this->input->post('serach_option');
		$keyword = $this->input->post('serach_keyword');
		
		$user_id =$this->input->post('chk');
			
		if($action=='delete')
		{
			foreach($user_id as $id)
			{
				$this->db->where("user_id",$id);
		        $this->db->update("users",array("is_deleted"=>1));		
				//$this->db->query("delete from ".$this->db->dbprefix('plan_subscription')." where store_id ='".$id."'");
			}
			$this->session->set_flashdata('msg', "delete");
			if($redirect_page == 'list_plan_subscription')
			{
				redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$offset.'/delete');
			}
			else
			{
				redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/delete');
			}
		}
			
		if($action=='active')
		{
			foreach($user_id as $id)
			{			
				$data = array('user_status'=>'active');
				$this->db->where('user_id',$id);
				$this->db->update('users', $data);
			}
			
			$this->session->set_flashdata('msg', "active");
			if($redirect_page == 'list_plan_subscription')
			{
				redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$offset.'/active');
			}
			else
			{
				redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/active');
			}
		}	
		if($action=='inactive')
		{
			foreach($user_id as $id)
			{			
				$data = array('user_status'=>'inactive');
				$this->db->where('user_id',$id);
				$this->db->update('users', $data);
			}
			
			$this->session->set_flashdata('msg', "inactive");
			if($redirect_page == 'list_plan_subscription')
			{
				redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$offset.'/inactive');
			}
			else
			{
				redirect('plan_subscription/'.$redirect_page.'/'.$limit.'/'.$option.'/'.$keyword.'/'.$offset.'/inactive');
			}
		}	
	}
	function removeimage($user_id,$image,$limit,$offset,$redirect_page,$option,$keyword)
	{
		//echo "sdfsdf";die;
		if($image!='')
		{
			$this->db->where("user_id",$user_id);
	        $this->db->update("plan_subscription",array("profile_image"=>''));	
			if(file_exists(base_path().'upload/user_orig/'.$image))
			{
				$link=base_path().'upload/user_orig/'.$image;
				unlink($link);
			}
			
			if(file_exists(base_path().'upload/plan_subscription/'.$image))
			{
				$link1=base_path().'upload/plan_subscription/'.$image;
				unlink($link1);
			}			
		}
		$msg='image_remove';
		redirect('plan_subscription/edit/'.$user_id.'/'.$redirect_page.'/1V1/1V1/'.$limit.'/'.$offset.'/'.$msg);
	}	
	
	function get_staff($id)
	{
		
		$data=$this->plan_subscription_model->get_staff($id);
		//print_r($data);die();
		// print form_dropdown('product_type',$data['product_type']);
		
		echo json_encode($data);
		 
	
		
		
	}
	
}


?>
