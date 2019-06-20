<?php
/**
 * This class is used for generating timesheet.
 * This class is extending the SPACULLUS_Controller subclasses are instantiated, and are significantly easier to create when 
 * they extend this class.
 *  @author     admin
 * @since      v 0.1 Dev
 * @package    SPACULLUS_Controller
 * @copyright  Copyright 2015 Schedullo Pty Ltd

 */
class Timesheet extends SPACULLUS_Controller{
    /**
      * This is a default construtor of timesheet class.It's used for initilized parent class construtor & methods.
      * @returns void
      */
    function Timesheet(){
                /**
                 *  call base class constructor 
                 */
                parent :: __construct ();
                /**
                 * Amazon S3  Configuration file
                 */
		$this->load->library('s3');
                /**
                 * Amazon S3 server Configuration file
                 */
		$this->config->load('s3');
                /**
                 * load timesheet model
                 */
                $this->load->model('timesheet_model');
                /*
                 * set default timezone 
                 */
		date_default_timezone_set("UTC");
    }
    
    function index($limit='10',$offset='0'){
               /**
                 * check user authentication
                 */
                if(!check_user_authentication()){
                        redirect('home');
                }
                $theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
                
		$data = array();
             
                $data['total_records ']=  total_timesheet();  
                $data['total_pages']= ceil($data['total_records '] / $limit);
                
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu']='from_timesheet';
		$data['theme'] = $theme;
		$data['error'] = '';
                $data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['cal_user_color_id'] = '0';
		}
                
                $data['status'] = get_taskStatus($this->session->userdata('company_id'),'Active');
                $data['userslist'] = getUserList($this->session->userdata('company_id')); 
                $data['max_date'] = max_timesheet_to_date();
                $data['timesheets_list'] = $this->timesheet_model->get_timesheet_list($limit,$offset);
                
                $this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);

		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);

		$this->template->write_view('content_side', $theme.'/layout/timesheet/timesheet_list', $data, TRUE);

		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
    }
    
    
    function create_timesheet(){
       
        if($_POST){
            $default_format = $this->config->item('company_default_format');
            $form_date = change_date_format($_POST['timesheet_fromdate']);
            $to_date = change_date_format($_POST['timesheet_todate']);
            $timesheet_user_id = $this->input->post('timesheet_to_another');
            if(strtotime($to_date) < strtotime($form_date)){
                    $data['error_code'] = '1';
                    $data['error_msg'] = '<span id="to_date-error" class="help-inline">Must be greater than or equal to from date.</span>';
                    echo json_encode($data); die();
            }
            
            $timesheet_id = $this->timesheet_model->save_timesheet($form_date,$to_date,$timesheet_user_id);
            $timesheet_info = $this->timesheet_model->get_one_timesheet_data($timesheet_id);
            $data['timesheet'] = $timesheet_info;
            $total_timesheet_time = $this->timesheet_model->get_overall_timesheet_time($timesheet_user_id,$form_date,$to_date);
            $hours = intval($total_timesheet_time/60);
            $minutes = $total_timesheet_time - ($hours * 60);
            $data['total_hours'] = $hours.':'.(strlen($minutes) == 1 ? '0'.$minutes : $minutes);
            $data['from_date'] = date($default_format,  strtotime($form_date));
            $data['to_date'] = date($default_format,  strtotime($to_date));
            echo json_encode($data); die();
        }       
        
    }
    
    function delete_timesheet(){
        if($_POST){
            $deleted_id = isset($_POST['id'])?$_POST['id']:'';
            $status = $this->timesheet_model->delete_timesheet($deleted_id);
            echo $status; die();
        }
    }
    
    function showtimesheet($timesheet_id = 0){
        
                $theme = getThemeName();
		$this->template->set_master_template($theme.'/template2.php');
           
		$data = array();
             
            
		$data['site_setting_date'] = $this->config->item('company_default_format');
		$data['active_menu']='from_timesheet';
		$data['theme'] = $theme;
		$data['error'] = '';
                $data['last_rember_values'] = $last_rember_values = get_user_last_rember_values();
		if($last_rember_values){
			$data['calender_project_id'] = $last_rember_values->calender_project_id;
			$data['left_task_status_id'] = $last_rember_values->task_status_id;
			$data['calender_team_user_id'] = $last_rember_values->calender_team_user_id;
			$data['calender_date'] = $last_rember_values->calender_date;
			$data['cal_user_color_id'] = $last_rember_values->cal_user_color_id;
		} else {
			$data['calender_project_id'] = '';
			$data['left_task_status_id'] = '';
			$data['calender_team_user_id'] = '';
			$data['calender_date'] = '';
			$data['cal_user_color_id'] = '0';
		}
                
                $timesheet_id = isset($_POST['timesheet_id'])?$_POST['timesheet_id']:base64_decode($timesheet_id);
               
                if($timesheet_id ==''){
                     redirect('timesheet/index');
                }
                $timesheet_data =  $this->timesheet_model->get_one_timesheet_data($timesheet_id);
                $total_timesheet_time = $this->timesheet_model->get_overall_timesheet_time($timesheet_data->timesheet_user_id,$timesheet_data->from_date,$timesheet_data->to_date);
                $hours = intval($total_timesheet_time/60);
                $minutes = $total_timesheet_time - ($hours * 60);
                $data['timesheet_id'] = $timesheet_data->timesheet_id;
                $data['first_name'] = $timesheet_data->first_name;
                $data['last_name'] = $timesheet_data->last_name;
                $data['timesheet_status'] = $timesheet_data->timesheet_status;
                $data['from_date'] = date('d M Y',  strtotime($timesheet_data->from_date));
                $data['to_date'] = date('d M Y',  strtotime($timesheet_data->to_date));
                $data['total_hours'] = $hours.'h'.(strlen($minutes) == 1 ? '0'.$minutes : $minutes).'m';
                $data['timesheet_user_id'] = $timesheet_data->timesheet_user_id;
                $word1 = ucfirst(substr($timesheet_data->first_name,0,1));
                $word2 = ucfirst(substr($timesheet_data->last_name,0,1));
                $data['timesheet_code'] =  $word1.$word2.'-'.$timesheet_data->timesheet_code;
                $date1=date_create($data['from_date']);
                $date2=date_create($data['to_date']);
                $diff=date_diff($date1,$date2);
                $days = $diff->days;
                $date_arr = array();
                for($i=0;$i<=$days;$i++){
                    $to = date("Y-m-d",strtotime("+".$i." day", strtotime($data['from_date'])));   //Returns the date of sunday in week
                    array_push($date_arr,$to);
                }
                $data['date_range'] = $date_arr;
                $data['customers']= $this->timesheet_model->get_customerlist_for_timesheets($timesheet_data->timesheet_user_id,$timesheet_data->from_date,$timesheet_data->to_date);
                
                $data['total_days'] = $days+1;

                $data['exception_task']  = $this->timesheet_model->get_exceptional_task($timesheet_data->from_date,$timesheet_data->to_date,$timesheet_data->timesheet_user_id);
                $data['days_changed'] = $this->timesheet_model->count_days_changed_task($timesheet_data->from_date,$timesheet_data->to_date,$timesheet_data->timesheet_user_id);
                $data['timesheet_comments'] = $this->timesheet_model->get_timesheet_comments_details($timesheet_data->timesheet_id,$timesheet_data->timesheet_user_id);
                $data['approver_comment'] = $this->timesheet_model->get_timesheet_approver_comments($timesheet_data->timesheet_id,$timesheet_data->timesheet_user_id);
                
                $data['manager_list'] = get_reporting_manger_list($timesheet_data->timesheet_user_id);
                $data['approver_details'] = get_user_info($timesheet_data->timesheet_user_id);
                
                $this->template->write_view('header',$theme.'/layout/common/header2',$data,TRUE);

		$this->template->write_view('content_left',$theme.'/layout/common/leftsidebar', $data, TRUE);

		$this->template->write_view('content_side', $theme.'/layout/timesheet/timesheet_detail_page', $data, TRUE);

		$this->template->write_view('footer', $theme.'/layout/common/footer2', $data, TRUE);
		$this->template->render();
    }
    
    function timesheet_filter(){
        if($_POST){
            $theme = getThemeName();
	    $this->template->set_master_template($theme.'/template2.php');
            $data = array();
            $data['site_setting_date'] = $this->config->item('company_default_format');
	    
            $data['theme'] = $theme;
            $view = $_POST['view'];
            $timesheet_id = $_POST['timesheet_id'];
            $timesheet_data =  $this->timesheet_model->get_one_timesheet_data($timesheet_id);
            $data['timesheet_user_id'] = $timesheet_data->timesheet_user_id;
            $data['customers']=  $this->timesheet_model->get_customerlist_for_timesheets($timesheet_data->timesheet_user_id,$timesheet_data->from_date,$timesheet_data->to_date);
            $date1=date_create($timesheet_data->from_date);
            $date2=date_create($timesheet_data->to_date);
            $diff=date_diff($date1,$date2);
            $days = $diff->days;
            $date_arr = array();
            for($i=0;$i<=$days;$i++){
                $to = date("Y-m-d",strtotime("+".$i." day", strtotime($timesheet_data->from_date)));   //Returns the date of sunday in week
                array_push($date_arr,$to);
            }
            $data['date_range'] = $date_arr;
            $data['view'] = $view;
            $data['total_days'] = $days;
            $this->load->view($theme.'/layout/timesheet/timesheet_filter_page', $data);
        }
    }
    
    function get_task_popup_data(){
        if($_POST){
            $customer_id = $_POST['customer_id'];
            $date = $_POST['date'];
            $timesheet_user = $_POST['timesheet_user_id'];
            $data['tasks'] = $this->timesheet_model->get_one_date_task($timesheet_user,$customer_id,$date);
            $data['date'] = date('l d F Y',  strtotime($date));
            $data['now_date'] = $date;
            echo json_encode($data); die();
        }
    }
    
    function update_task_data(){
        if($_POST){
            $form_data = isset($_POST['form'])?$_POST['form']:'';
            $date = $_POST['date'];
            $customer_id = $_POST['customer_id'];
            $timesheet_id = $_POST['timesheet_id'];
            $timesheet_data =  $this->timesheet_model->get_one_timesheet_data($timesheet_id);
            $unserializedData = array();
	    parse_str($form_data,$unserializedData);
            $now_total_time = $this->timesheet_model->update_task_details($unserializedData,$timesheet_id);
            $hours = intval($now_total_time/60);
            $minutes = $now_total_time - ($hours * 60);
            $data['current_time'] = $now_total_time;
            $data['total_time'] = $hours.":".($minutes == 0 ? '00' : $minutes);
            $data['total_changed_days']= $this->timesheet_model->count_days_changed_task($timesheet_data->from_date,$timesheet_data->to_date,$timesheet_data->timesheet_user_id);
            $data['exception_flag'] = $this->timesheet_model->check_exception_task($customer_id,$date,$timesheet_data->timesheet_user_id);
            $data['day_change_flag'] = $this->timesheet_model->check_days_changed_task($customer_id,$date,$timesheet_data->timesheet_user_id);
            echo json_encode($data); die();
        }
    }
    
    function add_timesheet_comment(){
        if($_POST){
            $comment_id = isset($_POST['comment_id'])?$_POST['comment_id']:'';
            $comment = isset($_POST['comment'])?$_POST['comment']:'';
            $timesheet_id = isset($_POST['timesheet_id'])?$_POST['timesheet_id']:'';
            $timesheet_user_id  = isset($_POST['timesheet_user_id'])?$_POST['timesheet_user_id']:'';
            $id = $this->timesheet_model->save_timesheet_comments($comment,$timesheet_id,$comment_id,$timesheet_user_id);
            echo $id; die();
        }
    }
    
    function add_approver_comment(){
        if($_POST){
            $comment_id = isset($_POST['comment_id'])?$_POST['comment_id']:'0';
            $comment = isset($_POST['comment'])?$_POST['comment']:'';
            $timesheet_id = isset($_POST['timesheet_id'])?$_POST['timesheet_id']:'';
            $timesheet_user_id = $_POST['timesheet_user_id'];
            $id = $this->timesheet_model->save_timesheet_approver_comments($comment,$timesheet_id,$comment_id,$timesheet_user_id);
            echo $id; die();
        }
    }
    
    function submit_timesheet(){
        if($_POST){
            $timesheet_id =  isset($_POST['timesheet_id'])?$_POST['timesheet_id']:'';
            $approver_id =  $this->session->userdata('approver_id');
            $comment_id = $_POST['comment_id'];
            $comment = $_POST['comment'];
            $timesheet_user_id = $_POST['timesheet_user_id'];
            $this->timesheet_model->update_timsheet_status($timesheet_id,$approver_id);
            $id = $this->timesheet_model->save_timesheet_comments($comment,$timesheet_id,$comment_id,$timesheet_user_id);
            echo $id; die();
        }
    }
    
    function approve_timesheet(){
        $timesheet_id = $_POST['timesheet_id'];
        
        $this->db->set('timesheet_status','approved');
        $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
        $this->db->where('timesheet_id',$timesheet_id);
        $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
        $this->db->update('timesheets');
        echo "done"; die();
    }
    
    function return_to_draft(){
        $timesheet_id = $_POST['timesheet_id'];
        
        $this->db->set('timesheet_status','draft');
        $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
        $this->db->where('timesheet_id',$timesheet_id);
        $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
        $this->db->update('timesheets');
        echo "done"; die();
    }
    
    function sort_timesheets(){
        if($_POST){
            $theme = getThemeName();
	    $this->template->set_master_template($theme.'/template2.php');
            $data = array();
            $data['site_setting_date'] = $this->config->item('company_default_format');
	    
            $data['theme'] = $theme;
            $form_data = isset($_POST['form'])?$_POST['form']:'';
            $unserializedData = array();
	    parse_str($form_data,$unserializedData);
            $start_date ='';
            $end_date = '';
            if($unserializedData['timesheet_end_date'] !=''){
                $end_date = change_date_format($unserializedData['timesheet_end_date']);
            }
            if($unserializedData['timesheet_start_date'] !=''){
                $start_date = change_date_format($unserializedData['timesheet_start_date']);
            }
            
            if($start_date !='' && $end_date !=''){
                if($end_date < $start_date){
                    echo "1";die;
                }
            }
            $data['timesheets_list'] = $this->timesheet_model->sort_timesheet_data($unserializedData);
            $data['timesheet_status_id'] = $unserializedData['timesheet_status_id'];
            $this->load->view($theme.'/layout/timesheet/filter_by_view', $data);
        }
    }
       
    
    function export_timesheets(){
        
                if(!check_user_authentication()){
			redirect('home');
		}
		/**
                 * for generate excelsheet this is loaded
                 */
		$this->load->library('excel');
		 
		
		$timeheets_id = $_GET['timesheet_ids'];
                $timeheets_id = explode(',', $timeheets_id);
		
		
		if($timeheets_id){
                    $j=0;
                    foreach($timeheets_id as $id){
                       $this->excel->createSheet();
                       /**
                        * set activate worksheet number 
                        */
                       $this->excel->setActiveSheetIndex($j);
                       /**
                        * set name the worksheet
                        */
                       $this->excel->getActiveSheet()->setTitle('Timesheet-'.($j+1));
                    
                        date_default_timezone_set($this->session->userdata("User_timezone")); 

                        //set cell A1 content with some text
                        $tables=array('First Name', 'Last Name', 'Period From','Period To','Timesheet Code','Task Name','Task Estimated Time','Tasl Actual Time','Task Timesheet Time','Base Cost','Total Estimated Cost','Total Actual Cost','Base Charge Rate','Estimated Charge','Actual Charge Rate','Timesheet Charge Out rate','Task Scheduled Date');
                        $key=array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1','Q1');
                        $title=array_combine($key, $tables);
                        foreach ($title as $key=>$value)
                         {
                            $this->excel->getActiveSheet()->setCellValue($key, $value);
                            //change the font size
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(12);
                                //make the font become bold
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(true);
                         }
                        $order_result=  $this->timesheet_model->export_timesheet($id);
        //               / pr($order_result); die();
                        $result = array();
                        $res = array();
                        if($order_result){
                            foreach($order_result as $row){
                                $res['first_name'] = $row['first_name'];
                                $res['last_name'] = $row['last_name'];
                                $res['period_from'] = $row['period_from'];
                                $res['period_to'] = $row['period_to'];
                                $res['timesheet_code'] = $row['timesheet_code'];
                                $res['task_name'] = $row['task_title'];
                                $res['task_estimated_time'] = $row['task_time_estimate'];
                                $res['task_actual_time'] = $row['task_time_spent'];
                                $res['task_timesheet_time'] = $row['billed_time'];
                                $res['base_cost'] = $row['cost'];
                                $res['total_estimated_cost'] = round(($row['task_time_estimate']*$row['cost_per_hour'])/60,2);
                                $res['total_actual_cost'] = round(($row['task_time_spent']*$row['cost_per_hour'])/60,2);
                                $res['base_charge_rate'] = $row['charge_out_rate'];
                                $res['estimated_charge'] = $row['estimated_total_charge'];
                                $res['actual_charge_rate'] = $row['actual_total_charge'];
                                $res['timesheet_charge_out_rate'] = round(($row['billed_time']*$row['charge_out_rate'])/60,2);
                                $res['task_scheduled_date'] = $row['task_scheduled_date'];
                                $result[] = $res; 
                            }

                        }
                        $key1=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q');
                        $i=2;
                        //pr($result); die();
                        foreach($result as $a)
                        {
                            $result=array_combine($key1,$a);
                            foreach($result as $key=>$value)
                            {
                                $this->excel->getActiveSheet()->setCellValue($key.$i, $value);
                                //change the font size
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setSize(11);
                                //make the font become bold
                                $this->excel->getActiveSheet()->getStyle($key)->getFont()->setBold(FALSE);
                            }
                            $i++;
                        }
                        $j++;
                    }
                }
                //merge cell A1 until D1
                //$this->excel->getActiveSheet()->mergeCells('A1:D1');
                    /*
                     * set aligment to center for that merged cell (A1 to D1)
                     */
                    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $filename='Export_timesheet'.date("Y:m:d h:i:s") .'.xlsx'; //save our workbook as this file name

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
                    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                    header('Cache-Control: max-age=0'); //no cache
                    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                    //if you want to save it as .XLSX Excel 2007 format
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
                    //force user to download the Excel file without writing it to server's HD
    //	ob_end_clean();
                   $objWriter->save('php://output');
                  
    }
    
    function pagination($limit='10',$offset='0'){
        if($_POST){
            $page=isset($_POST['page_no'])?$_POST['page_no']:0; 
                if($page){
                    $offset=$page*$limit;
                    $limit=$limit;
                }
                
                $theme = getThemeName();
                $data['theme'] = $theme;
                $data['timesheets_list'] = $this->timesheet_model->get_timesheet_list($limit,$offset);
                $this->load->view($theme.'/layout/timesheet/filter_by_view', $data);
        }
    }
    
    function timesheet_recall(){
        
            $timesheet_id =isset($_POST['timesheet_id'])?$_POST['timesheet_id']:'';
            $timesheet_user_id = isset($_POST['timesheet_user_id'])?$_POST['timesheet_user_id']:'';
            if($timesheet_id!='' && $timesheet_user_id !=''){
                $this->db->set('timesheet_status','draft');
                $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
                $this->db->where('timesheet_id',$timesheet_id);
                $this->db->where('timesheet_user_id',$timesheet_user_id);
                $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
                $this->db->update('timesheets');
                $approver_id = get_approver_id($timesheet_user_id);
                $approver_details = get_user_info($approver_id);
                $timesheet_details = $this->timesheet_model->get_one_timesheet_data($timesheet_id);
                $word1 = ucfirst(substr($timesheet_details->first_name,0,1));
                $word2 = ucfirst(substr($timesheet_details->last_name,0,1));
                $timesheet_code =  $word1.$word2.'-'.$timesheet_details->timesheet_code;
                
                $email_template = $this->db->query("select * from " . $this->db->dbprefix('email_template') . " where task='timesheet recall'");
                $email_temp = $email_template->row();

                $email_address_from = $email_temp->from_address;
                $email_address_reply = $email_temp->reply_address;

                $email_subject = $email_temp->subject;
                $email_message = $email_temp->message;

                $email_to = $approver_details->email;		

                $email_message = str_replace('{break}', '<br/>', $email_message);
                $email_message = str_replace('{approver_name}', $approver_details->first_name.' '.$approver_details->last_name, $email_message);
                $email_message = str_replace('{timesheet_code}', $timesheet_code, $email_message);
                $email_message = str_replace('{user_name}', $this->session->userdata('username'), $email_message);

                $str = $email_message;
                $sandgrid_id=$email_temp->sandgrid_id;
                $sendgriddata = array('subject'=>'timesheet recall',
                'data'=>array('timesheet_code'=>$timesheet_code,'user_name'=>$this->session->userdata('username')));
                if($sandgrid_id)
                {
                    $str = json_encode($sendgriddata);
                }
                /**
                  * Adding mail in mail queue for sending through cronjob.
                  */
                                        
                $mail_data = array(
                                   "email_to"=>$email_to,
                                   "email_from"=>$email_address_from,
                                   "email_reply"=>$email_address_reply,
                                   "email_subject"=>$email_subject,
                                   "message"=>$str,
                                   "attach"=>'',
                                   "status"=>'pending',
                                   "date"=>date('Y-m-d H:i:s'),
                                   "sandgrid_id"=>$sandgrid_id
                                   );
                $this->db->insert('email_queue',$mail_data);
                echo "done"; die();
            }
    }
    
    function max_date(){
            $default_format = $this->config->item('company_default_format');
            $data['max_date'] = date($default_format,strtotime('+1 day',  strtotime(max_timesheet_to_date())));
            if($this->session->userdata('is_administrator') == 1){
                $data['user_access'] = 'admin';
                $data['user_list'] = get_user_list();
            }else if($this->session->userdata('is_manager')== 1){
                $data['user_access'] = 'manager';
                $user_under_manager = get_users_under_managers();
                if($user_under_manager != '0'){
                    $data['user_list'] = array_merge($user_under_manager,get_user_inform());
                }else{
                    $data['user_list'] = get_user_inform();
                }
            }else{
                $data['user_access'] = 'user';
                $data['user_list'] = get_user_inform();
            }
            $data['login_user'] = $this->session->userdata('user_id');
            echo json_encode($data); die();
    }
    
    function xancel_timesheet_export(){
        $timesheet_id = $_POST['timesheet_id'];
        $timesheet_info = $this->timesheet_model->get_one_timesheet_data($timesheet_id);
        
        $task_list = $this->timesheet_model->get_task_list($timesheet_info->from_date,$timesheet_info->to_date,$timesheet_info->timesheet_user_id);
        $this->timesheet_model->set_task_to_again_export($task_list);
        $this->db->set('timesheet_status','approved');
        $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
        $this->db->set('export_to_xero','0');
        $this->db->where('timesheet_id',$timesheet_id);
        $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
        $this->db->update('timesheets');
        echo "done"; die();
    }
    /**
     * Getting customer list when user export timesheet to xero
     */
    function get_timesheet_customer_list(){
        if($_GET){
            $timesheet_id = $_GET['timesheet_ids'];
            $timesheet_ids = explode(',', $timesheet_id);
            
            if ($timesheet_ids) {
                foreach ($timesheet_ids as $id) {
                    $timesheet_data = $this->timesheet_model->get_one_timesheet_data($id);
                    if($timesheet_data->export_to_xero == '0') {
                        $form_date[$timesheet_data->from_date] = $timesheet_data->from_date;
                        $to_date[$timesheet_data->to_date] = $timesheet_data->to_date;
                        $users[] = $timesheet_data->timesheet_user_id;
                    }
                }
            }
            
            if (!empty($form_date) && !empty($to_date) && !empty($users)) {
                $new_from_date = array_search(min($form_date), $form_date);
                $new_to_date = array_search(max($to_date), $to_date);
                $customer_list = $this->timesheet_model->get_list_of_customers($new_from_date, $new_to_date, $users);
                $data['customerlist'] = $customer_list; 
            }else{
                $data['customerlist'] = '1';
            }
            echo json_encode($data); die();
        }
    }
    
    function chk_timesheet_exported(){
        $timesheet_ids = $this->input->get('timesheet_ids');
        $timesheets = explode(',', $timesheet_ids);
        foreach ($timesheets as $id){
            $timesheet_data = $this->timesheet_model->get_one_timesheet_data($id);
            $timesheetstatus = checkTimesheetHaveCustomer($timesheet_data->from_date, $timesheet_data->to_date, $timesheet_data->timesheet_user_id);
            if($timesheetstatus == '0'){
                $this->db->set('export_to_xero','1');
                $this->db->set('timesheet_status','exported');
                $this->db->where('timesheet_id',$id);
                $this->db->update('timesheets');
            }else{
                $this->db->set('timesheet_status','partially_exported');
                $this->db->where('timesheet_id',$id);
                $this->db->update('timesheets');
            }
            $status[$id] = $timesheetstatus;
        }
        echo json_encode($status); die();
    }
}

