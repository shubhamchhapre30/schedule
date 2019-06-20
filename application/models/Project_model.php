<?php
/**
 * This class is project database interaction class, there is defined function returns some data to its caller method.  
 * This class is extending the CI_Model 
 * subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 * 
 * @author     admin
 * @since      v0.1 Dev
 * @package    CI_Model
 * @copyright  Copyright 2015 Schedullo Pty Ltd
*/
class Project_model extends CI_Model
{
      /**
        * It default constuctor which is called when Project_model object is initialzied.It load base class methods & variables.
        * @returns void
        */

	function Project_model()
    {
        /**
         * call base class constructor
         */
        parent::__construct();
    }
    /**
     * This function add user project data in db.This function save data in project table,project_history table.
     * @returns int 
     */
	function addProject(){

		$project_start_date = date('Y-m-d',strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_start_date'))));


		$project_end_date = date('Y-m-d', strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_end_date'))));


                /**
                 * insertion in project table
                 */
		$data = array(
			'project_status' => $this->input->post('project_status'),
			'project_start_date' => $project_start_date,
			'project_end_date' => $project_end_date,
			'division_id' => $this->input->post('division_id'),
			'department_id' => $this->input->post('department_id'),
			'project_desc' => $this->input->post('project_desc'),
			'project_added_by' => get_authenticateUserID(),
			'company_id' => $this->session->userdata('company_id'),
			'project_title' => $this->input->post('project_title'),
                        'project_customer_id'=> $this->input->post('project_customer_id')
		);

		$this->db->insert('project',$data);
		$id = $this->db->insert_id();
                /**
                 * insert in project_history
                 */
		$data_history = array(
			'project_history_title' => PROJECT_CREATED,
			'project_history_desc' => $this->input->post('project_title').' named Project created successfully.',
			'project_id' => $id,
			'history_type' =>'Project',
			'project_history_added_by' => get_authenticateUserID(),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);

		$data = array(
					'user_id' => get_authenticateUserID(),
					'project_id' => $id,
					'is_project_owner' => '1',
					'status' => 'Active',
					'project_user_added_date' => date('Y-m-d H:i:s'),
					'is_deleted' => '0'
				);
				$this->db->insert('project_users',$data);

				$user_detail = get_user_info(get_authenticateUserID());

			$data_history = array(
			'project_history_title' => USER_ADDED_PROJECT,
			'project_history_desc' => 'User '.$user_detail->first_name."".$user_detail->last_name.' added to project .',
			'project_id' => $id,
			'history_type' =>'User',
			'project_history_added_by' => get_authenticateUserID(),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);
                /**
                 * update project_section table
                 */
		$data_section = array(
					'section_name' =>'Section 1',
					'main_section' =>'0',    //main section
					'project_id' => $id,
					'added_by' => get_authenticateUserID(),
					'added_date' => date('Y-m-d')
				);
				$this->db->insert('project_section',$data_section);
				$section_id = $this->db->insert_id();

				$section_order = array(

				'section_order'=>get_section_order_by_project($id,$section_id,'0') ,

				);

				$this->db->where('section_id',$section_id);
				$this->db->update('project_section',$section_order);

		return $id;
	}
        /**
         * This function is updated project history.it checks is owner project or user than it update project table.
         * @returns int
         */ 
        
	function updateProject(){

		//pr($_POST);die;
		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$this->input->post('project_id'));

		$pro_array = get_project_info($this->input->post('project_id'));
		//echo $this->input->post('project_status')."====";
		//pr($pro_array);die;
		//echo $pro_array['project_status']."====".$this->input->post('project_status');die;

                /**
                 * check is_owner or not
                 */

		if($data['is_owner']!='0'){

			$project_start_date = date('Y-m-d',strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_start_date'))));


			$project_end_date = date('Y-m-d', strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_end_date'))));



		$data = array(
			'project_status' => $this->input->post('project_status'),
			'project_start_date' => $project_start_date,
			'project_end_date' => $project_end_date,
			'division_id' => $this->input->post('division_id'),
			'department_id' => $this->input->post('department_id'),
			'project_desc' => $this->input->post('project_desc'),
			'project_added_by' => get_authenticateUserID(),
			'company_id' => $this->session->userdata('company_id'),
			'project_title' => $this->input->post('project_title')
		);
		$this->db->where('project_id',$this->input->post('project_id'));
		$this->db->update('project',$data);

		/*
		$data_history = array(
					'project_history_title' => PROJECT_UPDATED,
					'project_history_desc' => $this->input->post('project_title').' named Project updated successfully.',
					'project_id' => $this->input->post('project_id'),
					'history_type' =>'Project',
					'project_history_added_by' => get_authenticateUserID(),
					'project_history_added_date' => date('Y-m-d H:i:s')
				);

				$this->db->insert('project_history',$data_history);
				*/




		}

		if($this->input->post('project_start_date')){
			$project_start_date = change_date_format($this->input->post('project_start_date'));
		}

		if($this->input->post('project_end_date')){
			$project_end_date = change_date_format($this->input->post('project_end_date'));
		}
	if($pro_array['project_status'] != $this->input->post('project_status')){

		$old = str_replace(array("_"," ",","), " ", $pro_array['project_status']);
		$new = str_replace(array("_"," ",","), " ", $this->input->post('project_status'));
		//echo "inside history";die;
			$data_history_tab = array(
				'project_history_title' => "Project Status Updated",
				'project_history_desc' => $this->input->post('project_title').' named Project status updated from "'.$old.'" to "'.$new.'".',
				'project_id' => $this->input->post('project_id'),
				'history_type' =>'Project',
				'project_history_added_by' => get_authenticateUserID(),
				'project_history_added_date' => date('Y-m-d H:i:s')
			);

			$this->db->insert('project_history',$data_history_tab);
			//echo $this->db->last_query();die;
		}

		if($pro_array['project_title'] != $this->input->post('project_title')){

			$data_history_tab = array(
				'project_history_title' => "Project Title Updated",
				'project_history_desc' => $this->input->post('project_title').' named Project title updated from "'.$pro_array['project_title'].'" to "'.$this->input->post('project_title').'".',
				'project_id' => $this->input->post('project_id'),
				'history_type' =>'Project',
				'project_history_added_by' => get_authenticateUserID(),
				'project_history_added_date' => date('Y-m-d H:i:s')
			);

			$this->db->insert('project_history',$data_history_tab);
		}

		if($pro_array['project_start_date'] != $project_start_date){

			$data_history_tab = array(
				'project_history_title' => "Project Start Date Updated",
				'project_history_desc' => $this->input->post('project_title').' named Project start date updated from "'.$pro_array['project_start_date'].'" to "'.$project_start_date.'".',
				'project_id' => $this->input->post('project_id'),
				'history_type' =>'Project',
				'project_history_added_by' => get_authenticateUserID(),
				'project_history_added_date' => date('Y-m-d H:i:s')
			);

			$this->db->insert('project_history',$data_history_tab);
		}

		if($pro_array['project_end_date'] != $project_end_date){

			$data_history_tab = array(
				'project_history_title' => "Project End Date Updated",
				'project_history_desc' => $this->input->post('project_title').' named Project end date updated from "'.$pro_array['project_end_date'].'" to "'.$project_end_date.'".',
				'project_id' => $this->input->post('project_id'),
				'history_type' =>'Project',
				'project_history_added_by' => get_authenticateUserID(),
				'project_history_added_date' => date('Y-m-d H:i:s')
			);

			$this->db->insert('project_history',$data_history_tab);
		}




		return $this->input->post('project_id');
	}
        /**
         * It returns project list 
         * @param  $filter
         * @returns int
         */
	function get_project_list($filter){

		$query = $this->db->query("SELECT c.customer_name,p.company_id,p.project_customer_id,p.project_id,p.project_title,p.project_added_by,project_status,project_start_date,project_end_date,pu.is_project_owner FROM (`project` p) LEFT JOIN `project_users` pu ON `p`.`project_id` = `pu`.`project_id` LEFT JOIN customers c ON p.company_id=c.customer_company_id AND p.project_customer_id=c.customer_id WHERE (`pu`.`user_id` = ".get_authenticateUserID()." OR `p`.`project_added_by` = ".get_authenticateUserID().") AND `p`.`is_deleted` != 1 AND `p`.`project_status` = '".$filter."' GROUP BY `p`.`project_id` order by p.project_id DESC");
		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

        /**
         * It returns only selected project id project details.
         * @param int $project_id
         * @returns int
         */
	function get_one_project($project_id){
		$query = $this->db->get_where('project',array('project_id' => $project_id,'company_id'=>$this->session->userdata('company_id')));
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}

	function get_project_list_By_filter($filter)
	{
		$query = $this->db->query("SELECT *
									FROM (`project` p)
									LEFT JOIN `project_users` pu ON `p`.`project_id` = `pu`.`project_id`
									WHERE (`pu`.`user_id` =  ".get_authenticateUserID()."
									OR `p`.`project_added_by` =  ".get_authenticateUserID().")
									AND (`p`.`project_status` =  '".$filter."')
									AND (`p`.`is_deleted` <> '1')
									GROUP BY `p`.`project_id` ORDER BY p.project_id DESC
									");

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}

	}

	function get_total_project_comments($project_id='')
	{
		$query = $this->db->from('task_and_project_comments')->where('project_id',$project_id)->order_by('task_comment_id','desc')->get();

		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function get_project_comments($project_id='',$limmit='',$offset='')
	{
		$query = $this->db->from('task_and_project_comments')->where(array('project_id'=>$project_id,'project_id <>'=>'0','task_id'=>'0'))->order_by('task_comment_id','desc')->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_project_members($project_id)
	{
		$query = $this->db->select("pu.*,u.first_name,u.last_name,u.profile_image,u.is_customer_user")
						  ->from("project_users pu")
						  ->join("users u","pu.user_id = u.user_id","left")
						  ->where("pu.project_id",$project_id)
						  ->where("pu.is_deleted <>","1")
						  ->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_users_list_By_name($keyword,$division_id,$department_id)
	{
		$this->db->select("u.*");
		$this->db->from("users u");
		$this->db->join("user_department ude",'ude.user_id = u.user_id');
		$this->db->join("user_devision udv","udv.user_id = u.user_id");
		$this->db->where('ude.dept_id',$department_id);
		$this->db->where('udv.devision_id',$division_id);
		$this->db->like('first_name',$keyword);
		$this->db->or_like('last_name',$keyword);
		$this->db->group_by('u.user_id');
		$query = $this->db->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}

	}

	function get_users_list($project_id='',$division_id='0',$department_id='0')
	{
		$sql = "select u.*,ude.dept_id,udv.devision_id from users u left join user_department ude on ude.user_id = u.user_id left join user_devision udv on udv.user_id = u.user_id where u.user_id not in (select user_id from project_users where project_id =".$project_id.") AND ude.dept_id = ".$department_id." AND udv.devision_id = ".$division_id." ";

		$query = $this->db->query($sql);

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}

	}

	function updateUser()
	{
                   $new_owner = $this->input->post('is_owner')?$this->input->post('is_owner'):0;
                   $data = array(
					'user_id' => $this->input->post('user_id'),
					'project_id' => $this->input->post('project_id'),
					'is_project_owner' => $new_owner,
					'status' => 'Active',
					'project_user_added_date' => date('Y-m-d H:i:s'),
					'is_deleted' => '0'
				);
				$this->db->insert('project_users',$data);

			$user_detail = get_user_info($this->input->post('user_id'));
			$data_history = array(
			'project_history_title' => USER_ADDED_PROJECT,
			'project_history_desc' => 'User '.$user_detail->first_name."".$user_detail->last_name.' added to project .',
			'project_id' => $this->input->post('project_id'),
			'history_type' =>'User',
			'project_history_added_by' => $this->input->post('user_id'),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);
		return $this->input->post('project_id');
	}

	function UserTask($user_id,$project_id,$task_status_completed_id)
	{
		$sql = "select task_id,task_project_id,task_owner_id,task_allocated_user_id,task_due_date from tasks where task_owner_id != '0' AND task_allocated_user_id != '0' AND task_project_id = ".$project_id." AND is_deleted = '0' AND task_status_id <> ".$task_status_completed_id." AND (task_owner_id = ".$user_id." OR task_allocated_user_id = ".$user_id.")";

		$query = $this->db->query($sql);

		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function add_project_files(){
		$project_id = $_POST['project_id'];
		$project_files = '';
		$msg = '';
		$bucket = $this->config->item('bucket_name');
		if(isset($_FILES)){
			if(isset($_FILES['project_file']['name']) && $_FILES['project_file']['name']!=''){



         		$rand=rand(0,100000);

				$name = $_FILES['project_file']['name'];
				$size = $_FILES['project_file']['size'];
				$tmp = $_FILES['project_file']['tmp_name'];
				$ext = getExtension($name);

				$project_files = 'project_file_'.$rand.'_'.$_FILES['project_file']['name'];
				//$project_files = $_FILES['project_file']['name'];
			    $actual_image_name = "upload/task_project_files/".$project_files;

				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, CI_S3::ACL_PUBLIC_READ) )
				{
					$msg = "Success";

				} else {
					$msg = "Fail";
				}
			}
		}

		$file_data = array(
			'task_file_name' => $project_files,
			'file_title'=>$_FILES['project_file']['name'],
			'project_id' => $project_id,
			'file_added_by' => $this->session->userdata('user_id'),
			'file_date_added' => date('Y-m-d H:i:s')
		);

		$this->db->insert('task_and_project_files',$file_data);
		$id = $this->db->insert_id();

		$data_history = array(
			'project_history_title' => FILE_ADDED_PROJECT,
			'project_history_desc' => 'File '.$_FILES['project_file']['name'].' added to project successfully.',
			'project_id' => $project_id,
			'history_type' =>'File',
			'project_history_added_by' =>$this->session->userdata('user_id'),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);

		return $id;
	}

	function add_project_LinkFiles(){
		$file_name = isset($_POST['prj_file_name1'])?$_POST['prj_file_name1']:'';
		$file_link = isset($_POST['prj_file_link'])?$_POST['prj_file_link']:'';
		
		if($file_link){
			$project_id = $_POST['project_id'];
			$project_files = '';
			if($file_name){

         		$project_files = $file_name;
				
			}

			$file_data = array(
				'task_file_name' => $project_files,
				'file_link' => $file_link,
				'project_id' => $project_id,
				'file_added_by' => $this->session->userdata('user_id'),
				'file_date_added' => date('Y-m-d H:i:s')
			);
	
			$this->db->insert('task_and_project_files',$file_data);
			$id = $this->db->insert_id();
	
			$data_history = array(
				'project_history_title' => FILE_ADDED_PROJECT,
				'project_history_desc' => 'File '.$project_files.' added to project successfully.',
				'project_id' => $project_id,
				'history_type' =>'File',
				'project_history_added_by' =>$this->session->userdata('user_id'),
				'project_history_added_date' => date('Y-m-d H:i:s')
			);
	
			$this->db->insert('project_history',$data_history);
		}
	}

	function get_project_file_detail($project_file_id){
		$query = $this->db->get_where("task_and_project_files",array('task_file_id'=>$project_file_id));
		if($query->num_rows()>0){
			return $query->row();
		} else {
			return 0;
		}
	}

	function get_project_history_date($project_id)
	{
		$query = $this->db->select('project_id,date(project_history_added_date) as project_history_added_date')->from('project_history')->where(array('project_id'=>$project_id))->order_by('project_history_added_date','DESC')->group_by('date(project_history_added_date)')->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_total_project_history_date($project_id)
	{
		$query = $this->db->select('project_id,date(project_history_added_date) as project_history_added_date')->from('project_history')->where(array('project_id'=>$project_id))->order_by('project_history_added_date','DESC')->get();

		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function get_history_by_date($project_id,$limit,$offset)
	{
		$query = $this->db->query("select u.first_name,u.last_name,u.profile_image,u.user_id,p.* from users as u left outer join (select project_id,project_history_id,project_history_title,project_history_added_by,project_history_added_date,history_type,project_history_desc from project_history where project_id = ".$project_id." AND project_id <> '0' ) as p on p.project_history_added_by = u.user_id where p.project_id = ".$project_id." order by project_history_id DESC LIMIT ".$limit."  OFFSET ".$offset."");
		//group by project_history_added_date

		//echo $this->db->last_query();die;

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return '';
		}
	}

	function get_total_history_by_date($project_id=0)
	{
		$query = $this->db->query("select u.first_name,u.last_name,u.profile_image,u.user_id,p.* from users as u left outer join (select project_id,project_history_id,project_history_title,project_history_added_by,project_history_added_date,history_type,project_history_desc from project_history where project_id = ".$project_id." AND project_id <> '0' group by project_history_added_date) as p on p.project_history_added_by = u.user_id where p.project_id = ".$project_id." order by project_history_id DESC ");


		if($query->num_rows()>0){
			return $query->num_rows();
		} else {
			return '';
		}
	}




	function replace_project_files(){



		$bucket = $this->config->item('bucket_name');

		$project_id = $_POST['project_id'];
		$task_file_id = $_POST['rep_fil'];
		
		$project_file_name = $this->project_model->get_project_file_detail($_POST['rep_fil']);
		if($project_file_name->task_file_name){
			$delete_image_name = "upload/task_project_files/".$project_file_name->task_file_name;
			if($this->s3->getObjectInfo($bucket,$delete_image_name)){

				$this->s3->deleteObject($bucket,$delete_image_name);
			}
		}
		$project_files = '';
		$msg = '';
		if(isset($_FILES)){
			if(isset($_FILES['project_file_replace']['name']) && $_FILES['project_file_replace']['name']!=''){

				$rand=rand(0,100000);

				$name = $_FILES['project_file_replace']['name'];
				$size = $_FILES['project_file_replace']['size'];
				$tmp = $_FILES['project_file_replace']['tmp_name'];
				$ext = getExtension($name);

				$project_files = 'project_file_'.$rand.'_'.$name;
				//$project_files = $name;
                                $actual_image_name = "upload/task_project_files/".$project_files;

				if($this->s3->putObjectFile($tmp, $bucket , $actual_image_name, CI_S3::ACL_PUBLIC_READ) )
				{
					$msg = "Success";

				} else {
					$msg = "Fail";
				}


			}
		}

		$file_data = array(
			'task_file_name' => $project_files,
			'file_title'=>$name,
			'project_id' => $project_id,
			'file_added_by' => $this->session->userdata('user_id'),
			'file_date_added' => date('Y-m-d H:i:s')
		);

		$this->db->where('task_file_id',$task_file_id);
		$this->db->update('task_and_project_files',$file_data);

		$data_history = array(
			'project_history_title' => FILE_REPLACED_PROJECT,
			'project_history_desc' => 'File '.$name.' replaced to project successfully.',
			'project_id' => $project_id,
			'history_type' =>'File',
			'project_history_added_by' =>$this->session->userdata('user_id'),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);

		return $project_id;
	}
	
	function replace_filesUploadLink(){
		
		$project_id = $_POST['project_id'];
		$task_file_id = $_POST['rep_fil'];

		
		$project_files = '';
		
		
		$file_name = isset($_POST['replace_file_name'])?$_POST['replace_file_name']:'';
		$file_link = isset($_POST['replace_file_link'])?$_POST['replace_file_link']:'';
		
		if($file_link){
			if($file_name){
				$project_files = $file_name;
				
				
			}

		}
		$file_data = array(
			'task_file_name' => $project_files,
			'file_link' => $file_link,
			'project_id' => $project_id,
			'file_added_by' => $this->session->userdata('user_id'),
			'file_date_added' => date('Y-m-d H:i:s')
		);

		$this->db->where('task_file_id',$task_file_id);
		$this->db->update('task_and_project_files',$file_data);

		$data_history = array(
			'project_history_title' => FILE_REPLACED_PROJECT,
			'project_history_desc' => 'File '.$project_files.' replaced to project successfully.',
			'project_id' => $project_id,
			'history_type' =>'File',
			'project_history_added_by' =>$this->session->userdata('user_id'),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);

		return $project_id;
	}

	function all_task_by_projectID($project_id,$task_status_completed_id)
	{
		$this->db->select('t.*,p.project_title');
		$this->db->from('tasks t');
		$this->db->join('project p','t.task_project_id = p.project_id','left');
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where(array('t.task_status_id <>'=>$task_status_completed_id,'t.task_project_id'=>$project_id));
		$query = $this->db->get();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}



	// Task functionalities
        /**
         * It returns task of projects.
         * @param $project_id
         * @returns int
         */
	function get_project_section($project_id)
	{
		$this->db->select('ps.*');
		$this->db->from('project_section ps');
		$this->db->join('project p','ps.project_id = p.project_id','left');
		$this->db->where(array('ps.project_id'=>$project_id,'ps.main_section'=>'0'));
		$this->db->order_by('ps.section_order');
		$query = $this->db->get();

		//echo $this->db->last_query();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}

	}
        

	function get_project_section_byfilter($project_id,$user_id)
	{

		$this->db->select('ps.*');
		$this->db->from('project_section ps');
		$this->db->join('project p','ps.project_id = p.project_id','left');
		$this->db->where(array('ps.project_id'=>$project_id,'ps.main_section'=>'0'));
		$this->db->order_by('ps.section_order');
		$query = $this->db->get();

		//echo $this->db->last_query();

		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}

	}

	function get_project_subSection($section_id)
		{
			$this->db->select('ps.*');
			$this->db->from('project_section ps');
			$this->db->where(array('ps.main_section '=>$section_id));
			$this->db->order_by('subsection_order asc,section_order asc');
			$query = $this->db->get();

			//echo $this->db->last_query();
			if($query->num_rows()>0){
				return $query->result();
			} else {
				return 0;
			}
		}

	// project functionalities for mobile site starts here

	function get_AllProjects(){

		$query = $this->db->query("SELECT * FROM (`project` p) LEFT JOIN `project_users` pu ON `p`.`project_id` = `pu`.`project_id` WHERE `p`.`is_deleted` != 1  AND (`pu`.`user_id` = ".get_authenticateUserID()." OR `p`.`project_added_by` = ".get_authenticateUserID().") GROUP BY `p`.`project_id` ORDER BY `p`.`project_status` = 'Complete',p.project_status = 'On_hold',p.project_status='Open' ");

		
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_TasksByID($project_id,$type)
	{
		$task_status_completed_id = $this->config->item('completed_id');
		$off_days = get_company_offdays();

		$this->db->select('t.*');
		$this->db->from('tasks t');
		$this->db->join('project p','t.task_project_id = p.project_id','left');
		//$this->db->join('users u','t.task_owner_id = p.project_added_by','left');
		$this->db->where(array('t.task_project_id'=>$project_id,'t.is_deleted'=>'0','master_task_id'=>'0'));
		if($type!="all"){
			$this->db->where('t.task_allocated_user_id',$type);
		}
		$this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->order_by('t.task_due_date','DESC');
		$this->db->group_by('t.task_id');
		$query = $this->db->get();

		//echo $this->db->last_query();die;

		//$tasks = $query->result();

		if($query->num_rows()>0){
			$res = $query->result();
			$task_list = array();
			if($res){
				foreach($res as $row){
					if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
						$row_pass = (array) $row;
						//pr($row_pass);
						$re_data = kanban_recurrence_logic($row_pass,'',$off_days);
						//pr($re_data);

						if($re_data){
							$chk_rec = chk_virtual_recurrence_exists($re_data['master_task_id'],$re_data['task_orig_scheduled_date']);
							//pr($chk_rec);
							if($chk_rec){
								//echo "if  ";
								//if($chk_rec['task_allocated_user_id'] == get_authenticateUserID()){
									$row1 = (object)$chk_rec;
									array_push($task_list,$row1);
								//}
							} else {
								//echo "else  ";
								//if($re_data['task_allocated_user_id'] == get_authenticateUserID()){
									$row2 = (object)$re_data;
									array_push($task_list,$row2);
								//}
							}

						}
					} else {

						//pr($row);
						//if($row->task_allocated_user_id == get_authenticateUserID()){
							//$row1 = (array)$row;
							//pr($row1);
							array_push($task_list,$row);
						//}
					}
				}

			}
			//pr($chk_rec);die;
			//pr($task_list);die;
			$task_list = (array)$task_list;
			//pr($task_list);die;
			return $task_list;
		} else {
			return 0;
		}

		/*$tasks2 = array();
		if($tasks){
			$i = 0;
			foreach($tasks as $row){
				if($row->frequency_type == 'recurrence' && $row->recurrence_type!='0'){
					$row_pass = (array) $row;

					$virtual_array = kanban_recurrence_logic($row_pass);
					$chk_recu = chk_project_recurrence_exists($row_pass,$virtual_array);

					if($chk_recu){
							$recu_arr = $chk_recu;
					}

					if(isset($recu_arr) && $recu_arr!=''){
						$tasks2 = $recu_arr;
					}
					$task_detail[] = (object) $tasks2;
				} else {

					$task_detail[] = $row;
				}
				$i++;
			}
		}else{
			$task_detail = $query->result();
		}
		//pr($task_detail);die;
		if($query->num_rows()>0){
			return $task_detail;
		} else {
			return 0;
		}*/



		/*if($query->num_rows()>0){
			return $query->result();
		} else {
			return 0;
		}*/
	}

	function insert_Project(){

		//echo pr($_POST);die;
            /**
             * insert into project table
             */
		$data = array(
			'project_status' => 'Open',
			'project_start_date' => date('Y-m-d H:i:s',strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_start_date')))),
			'project_end_date' => date('Y-m-d H:i:s', strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_end_date')))),
			'division_id' => $this->input->post('division_id'),
			'department_id' => $this->input->post('department_id'),
			'project_desc' => $this->input->post('project_desc'),
			'project_added_by' => get_authenticateUserID(),
			'company_id' => $this->session->userdata('company_id'),
			'project_title' => $this->input->post('project_title')
		);

		$this->db->insert('project',$data);
		$id = $this->db->insert_id();
                /**
                 * insert into project_history
                 */
		$data_history = array(
			'project_history_title' => PROJECT_CREATED,
			'project_history_desc' => $this->input->post('project_title').' named Project created successfully.',
			'project_id' => $id,
			'history_type' =>'Project',
			'project_history_added_by' => get_authenticateUserID(),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);

		$data = array(
			'user_id' => get_authenticateUserID(),
			'project_id' => $id,
			'is_project_owner' => '1',
			'status' => 'Active',
			'project_user_added_date' => date('Y-m-d H:i:s'),
			'is_deleted' => '0'
		);
		$this->db->insert('project_users',$data);

		$user_detail = get_user_info(get_authenticateUserID());

		$data_history = array(
			'project_history_title' => USER_ADDED_PROJECT,
			'project_history_desc' => 'User '.$user_detail->first_name."".$user_detail->last_name.' added to project .',
			'project_id' => $id,
			'history_type' =>'User',
			'project_history_added_by' => get_authenticateUserID(),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);
                /**
                 * update project_section table
                 */
		$data_section = array(
					'section_name' =>'Section 1',
					'main_section' =>'0',    //main section
					'project_id' => $id,
					'added_by' => get_authenticateUserID(),
					'added_date' => date('Y-m-d')
				);
				$this->db->insert('project_section',$data_section);
				$section_id = $this->db->insert_id();

				$section_order = array(

				'section_order'=>get_section_order_by_project($id,$section_id,'0') ,

				);

				$this->db->where('section_id',$section_id);
				$this->db->update('project_section',$section_order);

		return $id;
	}

	function edit_Project(){


		$data['is_owner']=is_user_project_owner(get_authenticateUserID(),$this->input->post('project_id'));

		if($data['is_owner']!='0'){

		$data = array(
			//'project_status' => $this->input->post('project_status'),
			'project_start_date' => date('Y-m-d H:i:s',strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_start_date')))),
			'project_end_date' => date('Y-m-d H:i:s', strtotime(str_replace(array("/"," ",","),"-",$this->input->post('project_end_date')))),
			'division_id' => $this->input->post('division_id'),
			'department_id' => $this->input->post('department_id'),
			'project_desc' => $this->input->post('project_desc'),
			'project_added_by' => get_authenticateUserID(),
			'company_id' => $this->session->userdata('company_id'),
			'project_title' => $this->input->post('project_title')
		);
		$this->db->where('project_id',$this->input->post('project_id'));
		$this->db->update('project',$data);

		$data_history = array(
			'project_history_title' => PROJECT_UPDATED,
			'project_history_desc' => $this->input->post('project_title').' named Project updated successfully.',
			'project_id' => $this->input->post('project_id'),
			'history_type' =>'Project',
			'project_history_added_by' => get_authenticateUserID(),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);

		}

		return $this->input->post('project_id');
	}
        
        function get_all_project_task($project_id){
                $this->db->select('t.*');
                $this->db->from('tasks t');
                $this->db->join('project p','p.project_id = t.task_project_id','left');
                $this->db->where('t.task_company_id',  $this->session->userdata('company_id'));
                $this->db->where('t.task_project_id',$project_id);
                $this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_deleted','0');
		$this->db->group_by('t.task_id');
                $query = $this->db->get();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
        }
        
        function get_all_chargeable_project_task($project_id){
                
                
		$this->db->select('*');
		$this->db->from('task_category');
		$this->db->where('company_id',  $this->session->userdata('company_id'));
		$this->db->where('category_status','Active');
		$this->db->where("is_deleted","0");
                $this->db->where('is_chargeable','0');
                $this->db->where('parent_id','0');
		$this->db->order_by("category_seq","asc");
		$query1 = $this->db->get();
		if($query1->num_rows()>0){
			$result_data = $query1->result();
		} else {
			$result_data = array();
		}
                $result =array();
                if($result_data!='' && isset($result_data)){
                    foreach ($result_data as $data){
                        $result[] = $data->category_id;
                       
                    }
                }
            
            
                $this->db->select('t.*');
                $this->db->from('tasks t');
                $this->db->join('project p','p.project_id = t.task_project_id','left');
                $this->db->where('t.task_company_id',  $this->session->userdata('company_id'));
                if(!empty($result)){
                $this->db->where_not_in('t.task_category_id',$result);
                }
                $this->db->where('t.task_project_id',$project_id);
                $this->db->where('t.task_owner_id != ',"0");
		$this->db->where('t.task_allocated_user_id != ',"0");
		$this->db->where('t.is_deleted','0');
		$this->db->group_by('t.task_id');
                $query = $this->db->get();
               // echo $this->db->last_query(); die();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
        }

      function copy_Project($project_id){

            /**
             * insert into project table
             */
                $one_project = $this->get_one_project($project_id);
		$data = array(
			'project_status' => 'Open',
			'project_start_date' => date('Y-m-d H:i:s'),
			'project_end_date' => date('Y-m-d H:i:s'),
			'division_id' => $one_project->division_id,
			'department_id' => $one_project->department_id,
			'project_desc' => $one_project->project_desc,
			'project_added_by' => get_authenticateUserID(),
			'company_id' => $this->session->userdata('company_id'),
			'project_title' => $one_project->project_title.' - Copy',
                        'project_customer_id' => $one_project->project_customer_id
		);
//print_r($data);
		$this->db->insert('project',$data);
		$id = $this->db->insert_id();
                /**
                 * insert into project_history
                 */
//		$data_history = array(
//			'project_history_title' => PROJECT_CREATED,
//			'project_history_desc' => $this->input->post('project_title').' named Project created successfully.',
//			'project_id' => $id,
//			'history_type' =>'Project',
//			'project_history_added_by' => get_authenticateUserID(),
//			'project_history_added_date' => date('Y-m-d H:i:s')
//		);
//		$this->db->insert('project_history',$data_history);
		$data = array(
			'user_id' => get_authenticateUserID(),
			'project_id' => $id,
			'is_project_owner' => '1',
			'status' => 'Active',
			'project_user_added_date' => date('Y-m-d H:i:s'),
			'is_deleted' => '0'
		);
		$this->db->insert('project_users',$data);
		$user_detail = get_user_info(get_authenticateUserID());

		$data_history = array(
			'project_history_title' => USER_ADDED_PROJECT,
			'project_history_desc' => 'User '.$user_detail->first_name."".$user_detail->last_name.' added to project .',
			'project_id' => $id,
			'history_type' =>'User',
			'project_history_added_by' => get_authenticateUserID(),
			'project_history_added_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert('project_history',$data_history);
               
                $all = array();
                $project_sections = $this->get_project_section_subsection($project_id);
                foreach($project_sections as $sec)
                {
                    if($sec['main_section'] == 0)
                    {
                        $data_section = array(
                                            'section_name' =>$sec['section_name'],
                                            'main_section' =>'0',    //main section
                                            'section_order' =>$sec['section_order'],
                                            'subsection_order' =>$sec['subsection_order'],
                                            'project_id' => $id,
                                            'added_by' => get_authenticateUserID(),
                                            'added_date' => date('Y-m-d')
                                    );
                        $this->db->insert('project_section',$data_section);
                        $section_id = $this->db->insert_id();
                        $sec['replace_section'] = $section_id;
                        $all[] = $sec;
                    }
                    else
                    {
                        $replace_section = search_section_id_in_array($all, $sec['main_section']);
                        $data_section = array(
                                            'section_name' =>$sec['section_name'],
                                            'main_section' =>$replace_section,    //main section
                                            'section_order' =>$sec['section_order'],
                                            'subsection_order' =>$sec['subsection_order'],
                                            'project_id' => $id,
                                            'added_by' => get_authenticateUserID(),
                                            'added_date' => date('Y-m-d')
                                    );
                        $this->db->insert('project_section',$data_section);
                        $section_id = $this->db->insert_id();
                        $sec['replace_section'] = $section_id;
                        $sec['replace_main_section'] = $replace_section;
                        $all[] = $sec;
                    }
                }
                $status_id = get_task_status_id_by_name('Not ready');
                        $task_list = getTaskListFromProjectId($project_id);
                        if($task_list !=0)
                        {
                            foreach ($task_list as $task1)
                            {
                                if($task1['is_personal'] == '0' &&  $task1['master_task_id'] == '0' && $task1['frequency_type'] == 'one_off')
                                {
                                    $dependencies = get_task_dependencies($task1['task_id']);
                                    if($dependencies == '0')
                                    {
                                        if($task1['section_id'] != '0')
                                            $task_section_id = search_section_id_in_array($all, $task1['section_id']);
                                        else 
                                            $task_section_id = $task1['section_id'];
                                        if($task1['subsection_id'] != '0')
                                            $task_subsection_id = search_section_id_in_array($all, $task1['subsection_id']);
                                        else 
                                            $task_subsection_id = $task1['subsection_id'];
                                        $taskdata = array(
                                            'task_company_id' => $task1['task_company_id'],
                                            'task_title' => $task1['task_title'],
                                            'task_description' => $task1['task_description'],
                                            'is_personal' => $task1['is_personal'],
                                            'task_priority' => $task1['task_priority'],
                                            'task_due_date' => '',
                                            'task_scheduled_date' => '',
                                            'task_orig_scheduled_date' => '',
                                            'task_orig_due_date' => '',
                                            'task_time_spent' => '',
                                            'task_time_estimate' => $task1['task_time_estimate'],
                                            'task_owner_id' =>  get_authenticateUserID(),
                                            'task_allocated_user_id' => get_authenticateUserID(),
                                            'task_status_id' => $status_id,
                                            'task_division_id' => $task1['task_division_id'],
                                            'task_department_id' => $task1['task_department_id'],
                                            'task_category_id' => $task1['task_category_id'],
                                            'task_staff_level_id' => $task1['task_staff_level_id'],
                                            'task_sub_category_id' => $task1['task_sub_category_id'],
                                            'task_skill_id' => $task1['task_skill_id'],
                                            'task_project_id' => $id,
                                            'section_id' => $task_section_id,
                                            'subsection_id' =>$task_subsection_id,   
                                            'task_added_date' => date('Y-m-d H:i:s'),
                                            'customer_id' => $task1['customer_id']
                                        );

                                        $this->db->insert('tasks',$taskdata);
                                        $task_id = $this->db->insert_id();
                                        if($task1['task_time_estimate'] !='0')
                                        {
                                            $charge_out_rate = get_charge_out_rate($task_id);
                                            $base_employee_rate = get_user_cost_per_hour(get_authenticateUserID());
                                            $dataupdate = array(
                                                       "cost_per_hour"=>$base_employee_rate,
                                                       "cost"=>round(($base_employee_rate*$task1['task_time_estimate'])/60,2),
                                                       "charge_out_rate"=>$charge_out_rate,
                                                       "estimated_total_charge"=>round(($charge_out_rate*$task1['task_time_estimate'])/60,2),
                                                   );
                                            $this->db->where('task_id',$task_id);
                                            $this->db->update('tasks',$dataupdate);
                                        }
                                        $history_data = array(
                                                'histrory_title' => 'Task created.',
                                                'history_added_by' => get_authenticateUserID(),
                                                'task_id' => $task_id,
                                                'date_added' => date('Y-m-d H:i:s')
                                        );
                                        $this->db->insert('task_history',$history_data);
                                        $chk_exist = chk_swim_exist($task_id,get_authenticateUserID());
                                        if($chk_exist == '0'){
                                                $user_swimlane = array(
                                                        'user_id' => get_authenticateUserID(),
                                                        'task_id' => $task_id,
                                                        'swimlane_id' => get_default_swimlane(get_authenticateUserID()),
                                                        'kanban_order' => 1,
                                                        //'calender_order' => get_user_last_calnder_order($task_allocated_user_id,$old_task_detail['task_scheduled_date']) + 1
                                                );

                                                $this->db->insert('user_task_swimlanes',$user_swimlane);



                                        }
                                    }
                                }
                            }
                        }

		return $id;
	}
        //get project sections and subsections
        
        function get_project_section_subsection($project_id)
	{
		$this->db->select('ps.*,ps1.section_name as main_section_name');
		$this->db->from('project_section ps');
		$this->db->join('project p','ps.project_id = p.project_id','left');
                $this->db->join('project_section ps1','ps.main_section = ps1.section_id','left');
		$this->db->where(array('ps.project_id'=>$project_id));
		$this->db->order_by('ps.main_section');
		$query = $this->db->get();

		//echo $this->db->last_query();

		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}

	}
        
        
	// Project functionalities for mobile site ends here


}

?>
