<?php 

class API extends CI_Controller{
    function API(){
         /**
             * call base class contructor
             */
		parent::__construct();	
                  /* load Amazon s3 library file*/
		$this->load->library('s3');
                /* config file amazon s3 */
		$this->config->load('s3');
                /* databasse of company class */
		$this->load->model('company_model');	
                     /* load pagination library */
		$this->load->library('pagination');
		//$this->load->library('chargify_lib/Chargify');
		
	}
        function index(){
            if(!check_admin_authentication())
		{
			redirect('home');
		}
                redirect('API/api_view');
        }
        function api_view($limit='20',$offset=0,$msg=''){
            
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
		
		$check_rights=get_rights('list_company');
		
		if($check_rights==0) {			
			redirect('home/dashboard/no_rights');	
		}
		
		
		$config['uri_segment']='4';
		$config['base_url'] = base_url().'API/api_view/'.$limit.'/';
		$config['total_rows'] = $this->company_model->get_total_company_count();
	
		$config['per_page'] = $limit;		
		$this->pagination->initialize($config);		
		$data['page_link'] = $this->pagination->create_links();
		
		$data['result'] = $this->company_model->get_company_lists($offset,$limit);
                $data['iphone_info'] = getIphoneAppInfo();
              //  pr($data['result']); die();
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
		$data['redirect_page']='api_company_list';
		
		$data['site_setting'] = site_setting();
		$this->template->write_view('header',$theme .'/layout/common/header',$data,TRUE);
		$this->template->write_view('left',$theme .'/layout/common/sidebar',$data,TRUE);
		$this->template->write_view('center',$theme .'/layout/company/ajax_api_company',$data,TRUE);
		$this->template->write_view('footer',$theme .'/layout/common/footer',$data,TRUE);
		$this->template->render();
        }
        
        
        function change_api_status(){
            $status = isset($_POST['status'])?$_POST['status']:'';
            $company_id = $_POST['company_id'];
            if($status == 'Active'){
                
                $this->db->set('api_access_status',$status);
                $this->db->where('company_id',$company_id);
                $this->db->update('company');
                
                echo "done"; die();
            }else{
                $client_id = $_POST['client_id'];
                
                $this->db->set('api_access_status',$status);
                $this->db->where('company_id',$company_id);
                $this->db->update('company');
                if($client_id){
                    $this->db->where('api_company_id',  $company_id);
                    $this->db->where('client_id',$client_id);
                    $this->db->delete('app_registration');

                    $this->db->where('user_id',  $company_id);
                    $this->db->where('client_id',$client_id);
                    $this->db->delete('oauth_clients');
                }
                
                echo "done"; die();
            }
        }
}

