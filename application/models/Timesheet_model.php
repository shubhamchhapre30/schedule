<?php

class Timesheet_model extends CI_Model{
    function Customer_model()
     {
        parent::__construct();	
     }
     
     function save_timesheet($form_date,$to_date,$timesheet_user_id){
         $userinfo = get_user_info($timesheet_user_id);
         $count = count_timesheet($timesheet_user_id);
         $timesheet_code = $count+1;
         
         $data = array(
             "first_name"=>$userinfo->first_name,
             "last_name" =>$userinfo->last_name,
             "timesheet_code"=>  $timesheet_code,
             "timesheet_status"=>'draft',
             "timesheet_user_id"=> $timesheet_user_id,
             "timesheet_company_id"=>  $this->session->userdata('company_id'),
             "billed_time"=>'0',
             "from_date"=>$form_date,
             "to_date"=>$to_date,
             "timesheet_created_date"=>date('Y-m-d H:i:s')
             );
         
         $this->db->insert('timesheets',$data);
         $timesheet_id = $this->db->insert_id();
         
         $task_list = $this->get_task_list($form_date,$to_date,$timesheet_user_id);
         if($task_list !='0'){
            foreach($task_list as $list){
                $this->db->set('billed_time',$list['task_time_spent']);
                $this->db->where('task_id',$list['task_id']);
                $this->db->update('tasks');
            }
         }
         return $timesheet_id;
     }
     
     function get_timesheet_list($limit,$offset){
         $users =  get_users_under_manager();
         $this->db->select('*');
         $this->db->from('timesheets');
         $this->db->where('timesheet_company_id',$this->session->userdata('company_id'));
         if($this->session->userdata('is_manager')=='0' && $this->session->userdata('is_administrator')=='0'){
             $this->db->where('timesheet_user_id',  get_authenticateUserID());
         }else if($this->session->userdata('is_manager')=='1'){
             $arr= array();
             if($users){
                foreach ($users as $u){
                  $arr[] = $u;
                 }
             }
             array_push($arr, get_authenticateUserID());
             $this->db->where_in('timesheet_user_id',  $arr);
         }
         $this->db->where('timesheet_status','draft');
         $this->db->order_by('first_name','asc');
         $this->db->limit($limit,$offset);
         $query = $this->db->get();
         if($query->num_rows()>0){
             return $query->result_array();
         }else{
             return 0;
         }
     }
     
     function get_one_timesheet_data($timesheet_id){
         $this->db->select('*');
         $this->db->from('timesheets');
         $this->db->where('timesheet_id',$timesheet_id);
         $query = $this->db->get();
         if($query->num_rows()>0){
             return $query->row();
         }else{
             return 0;
         }
     }
     
     function delete_timesheet($id){
         
         $this->db->where('timesheet_id',$id);
         $this->db->where('timesheet_company_id',$this->session->userdata('company_id'));
         $this->db->delete('timesheets');
         return 'done';
         
     }
     
     function get_customer_total_time($customer_id,$date,$user_id){
         $completed = $this->config->item('completed_id');
         $this->db->select('sum(billed_time) as task_billed_time');
         $this->db->from('tasks');
         if($customer_id){
            $this->db->where('customer_id',$customer_id);
         }else{
            $this->db->where_in('customer_id',array(' ','0'));
         }
         $this->db->where('task_scheduled_date',$date);
         $this->db->where('task_status_id',$completed);
         $this->db->where('task_company_id',$this->session->userdata('company_id'));
         $this->db->where('is_deleted','0');
         $this->db->where('is_personal','0');
         $this->db->where('task_allocated_user_id',$user_id);
         $query = $this->db->get();
        // echo $this->db->last_query();
         if($query->num_rows()>0){
             return $query->row()->task_billed_time;
         }else{
             return 0;
         }
         
     }
     
     
     function get_total_timesheet_cost($customer_id,$date,$user_id){
         $completed = $this->config->item('completed_id');
         $this->db->select('*');
         $this->db->from('tasks');
         if($customer_id){
            $this->db->where('customer_id',$customer_id);
         }else{
            $this->db->where_in('customer_id',array(' ','0'));
         }
         $this->db->where('task_scheduled_date',$date);
         $this->db->where('task_status_id',$completed);
         $this->db->where('task_company_id',$this->session->userdata('company_id'));
         $this->db->where('is_deleted','0');
         $this->db->where('is_personal','0');
         $this->db->where('task_allocated_user_id',$user_id);
         $query = $this->db->get();
         if($query->num_rows()>0){
             return $query->result_array();
         }else{
             return 0;
         }
     }
     
     
     function get_total_timesheet_revenue($customer_id,$date,$user_id){
         $completed = $this->config->item('completed_id');
         $this->db->select('*');
         $this->db->from('tasks');
            if($customer_id){
               $this->db->where('customer_id',$customer_id);
            }else{
               $this->db->where_in('customer_id',array(' ','0'));
            }
         $this->db->where('task_scheduled_date',$date);
         $this->db->where('task_status_id',$completed);
         $this->db->where('task_company_id',$this->session->userdata('company_id'));
         $this->db->where('is_deleted','0');
         $this->db->where('is_personal','0');
         $this->db->where('task_allocated_user_id',$user_id);
         $query = $this->db->get();
         //echo $this->db->last_query(); die();
         if($query->num_rows()>0){
             return $query->result_array();
         }else{
             return 0;
         }
     }
     
     function get_task_list($from_date,$to_date,$user_id){
         $this->db->select('t.*');
         $this->db->from('tasks t');
         $this->db->where('t.task_scheduled_date >=',$from_date);
         $this->db->where('t.task_scheduled_date <=',$to_date);
         $this->db->where('t.task_allocated_user_id', $user_id);
         $this->db->where('t.task_company_id',  $this->session->userdata('company_id'));
         $this->db->where('t.is_deleted','0');
         $this->db->where('t.is_personal','0');
         $query = $this->db->get();
         //echo $this->db->last_query(); die();
         if($query->num_rows()> 0){
             return $query->result_array();
         }else{
            return 0;
         }
     }
     
     function get_exceptional_task($from_date,$to_date,$user_id){
         $completed = $this->config->item('completed_id');
         $this->db->select('*');
         $this->db->from('tasks');
         $this->db->where('task_scheduled_date >=',$from_date);
         $this->db->where('task_scheduled_date <=',$to_date);
         $this->db->where('task_time_spent !=','0');
         $this->db->where('task_status_id !=',$completed);
         $this->db->where('task_company_id',$this->session->userdata('company_id'));
         $this->db->where('is_deleted','0');
         $this->db->where('is_personal','0');
         $this->db->where('task_allocated_user_id',$user_id);
         $query = $this->db->get();
        // echo $this->db->last_query(); die();
         if($query->num_rows()>0){
             return $query->num_rows();
         }else{
             return 0;
         }
     }
     
     function check_exception_task($customer_id,$date,$user_id){
         $completed = $this->config->item('completed_id');
         $this->db->select('*');
         $this->db->from('tasks');
         $this->db->where('task_scheduled_date',$date);
         if($customer_id){
            $this->db->where('customer_id',$customer_id);
         }else{
            $this->db->where_in('customer_id',array(' ','0'));
         }
         $this->db->where('task_time_spent !=','0');
         $this->db->where('task_status_id !=',$completed);
         $this->db->where('task_company_id',$this->session->userdata('company_id'));
         $this->db->where('is_deleted','0');
         $this->db->where('is_personal','0');
         $this->db->where('task_allocated_user_id',$user_id);
         $query = $this->db->get();
        // echo $this->db->last_query(); die();
         if($query->num_rows()>0){
             return 1;
         }else{
             return 0;
         }
     }
     
     function get_one_date_task($timsheet_user,$customer_id,$date){
         $completed = $this->config->item('completed_id');
         $this->db->select('t.*,p.project_title');
         $this->db->from('tasks t');
         $this->db->join('project p','p.project_id = t.task_project_id','left');
         $this->db->where('t.task_scheduled_date',$date);
         if($customer_id){ 
            $this->db->where('t.customer_id',$customer_id);
         }else{ 
             $this->db->where_in('customer_id',array(' ','0'));
         }
        // $this->db->where('t.task_time_spent !=','0');
         $this->db->where('t.task_status_id',$completed);
         $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
         $this->db->where('t.is_deleted','0');
         $this->db->where('t.is_personal','0');
         $this->db->where('t.task_allocated_user_id',$timsheet_user);
         $query = $this->db->get();
       // echo $this->db->last_query(); die();
         if($query->num_rows()>0){
             return $query->result();
         }else{
             return 0;
         }
     }
     
     function update_task_details($data,$timesheet_id){
        $total_billed_time =0;
         foreach($data as $key=>$value){
            if(is_numeric($key)){
                if(strpos($value, 'h') == false){
                    $h = 0;
                    $m = str_replace("m","",$value);
                }else{
                    $d = explode('h', $value); 
                    $h = $d[0];
                    $m = str_replace("m","",$d[1]);
                }

                 $bill_time = $h*60 + $m;
                 $total_billed_time += $bill_time;
                 $this->db->set('billed_time',$bill_time);
                 $this->db->where('task_id',$key);
                 $this->db->update('tasks');
             }
         }
         $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
         $this->db->where('timesheet_id',$timesheet_id);
         $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
         $this->db->update('timesheets');
         return $total_billed_time; 
     }
     
     function count_days_changed_task($from_date,$to_date,$user_id){
         $completed = $this->config->item('completed_id');
         $this->db->select('*');
         $this->db->from('tasks');
         $this->db->where('task_scheduled_date >=',$from_date);
         $this->db->where('task_scheduled_date <=',$to_date);
         $this->db->where('task_time_spent != billed_time');
         $this->db->where('task_status_id ',$completed);
         $this->db->where('task_company_id',$this->session->userdata('company_id'));
         $this->db->where('is_deleted','0');
         $this->db->where('is_personal','0');
         $this->db->where('task_allocated_user_id',$user_id);
         $query = $this->db->get();
        // echo $this->db->last_query(); die();
         if($query->num_rows()>0){
             return $query->num_rows();
         }else{
             return 0;
         }
     }
     
     function check_days_changed_task($customer_id,$date,$user_id){
         $completed = $this->config->item('completed_id');
         $this->db->select('*');
         $this->db->from('tasks');
         $this->db->where('task_scheduled_date',$date);
         if($customer_id){
            $this->db->where('customer_id',$customer_id);
         }else{
            $this->db->where_in('customer_id',array(' ','0'));
         }
         $this->db->where('task_time_spent != billed_time');
         $this->db->where('task_status_id ',$completed);
         $this->db->where('task_company_id',$this->session->userdata('company_id'));
         $this->db->where('is_deleted','0');
         $this->db->where('is_personal','0');
         $this->db->where('task_allocated_user_id',$user_id);
         $query = $this->db->get();
        // echo $this->db->last_query(); die();
         if($query->num_rows()>0){
             return 1;
         }else{
             return 0;
         }
     }
     
     function save_timesheet_comments($comment,$timesheet_id,$comment_id,$timesheet_user_id){
         if($comment_id !='0'){
             $data = array(
                'timesheet_comments'=> $comment,
             );
             $this->db->where('comment_id',$comment_id);
             $this->db->where('timesheet_id',$timesheet_id);
             $this->db->update('timesheet_comments',$data);
         }
         else{
            $data = array(
                'commented_user_id'=>  $timesheet_user_id,
                'timesheet_comments'=> $comment,
                'timesheet_id'=>$timesheet_id,
                'company_id'=>  $this->session->userdata('company_id'),
                'comment_added_date'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('timesheet_comments',$data);
            $comment_id = $this->db->insert_id();
         }
         $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
         $this->db->where('timesheet_id',$timesheet_id);
         $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
         $this->db->update('timesheets');
         return $comment_id;
     }
     
     function save_timesheet_approver_comments($comment,$timesheet_id,$comment_id,$timesheet_user_id){
         if($comment_id !='0'){
             $data = array(
                'timesheet_comments'=> $comment,
             );
             $this->db->where('comment_id',$comment_id);
             $this->db->where('timesheet_id',$timesheet_id);
             $this->db->update('timesheet_comments',$data);
         }
         else{
            $data = array(
                'timesheet_comments'=> $comment,
                'timesheet_id'=>$timesheet_id,
                'company_id'=>  $this->session->userdata('company_id'),
                'comment_added_date'=>date('Y-m-d H:i:s'),
                'timesheet_approver_id'=> get_authenticateUserID()
            );
            $this->db->insert('timesheet_comments',$data);
            $comment_id = $this->db->insert_id();
         }
         $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
         $this->db->where('timesheet_id',$timesheet_id);
         $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
         $this->db->update('timesheets');
         return $comment_id;
     }
     
     
     function get_timesheet_comments_details($timesheet_id,$timesheet_user_id){
         $this->db->select('*');
         $this->db->from('timesheet_comments');
         $this->db->where('commented_user_id',$timesheet_user_id);
         $this->db->where('timesheet_id',$timesheet_id);
         $this->db->where('company_id',$this->session->userdata('company_id'));
         $query = $this->db->get();
         
         if($query->num_rows()>0){
             return $query->row();
         }else{
             return 0;
         }
     }
     function update_timsheet_status($timesheet_id,$approver_id){
         
            $this->db->set('timesheet_status','submitted');
            $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
            $this->db->where('timesheet_id',$timesheet_id);
            $this->db->where('timesheet_company_id',$this->session->userdata('company_id'));
            $this->db->where('timesheet_user_id',  get_authenticateUserID());
            $this->db->update('timesheets');
            
            //notification
	    $notification_text = $this->session->userdata('username').' has submitted a timesheet for you to review.';
	    $notification_data = array(
					'task_id' => '',
					'project_id' => '',
					'notification_text' => $notification_text,
					'notification_user_id' => $this->session->userdata('approver_id'),
					'notification_from' =>get_authenticateUserID(),
					'is_read' => '0',
					'date_added' => date("Y-m-d H:i:s"),
                                        'timesheet_notification'=>'1',
                                        'timesheet_id'=>$timesheet_id
				);
            $this->db->insert('task_notification',$notification_data);
            
            $user_info = get_user_info($approver_id);
            
            $timesheet_link = '<a href="'.site_url('timesheet/showtimesheet/'.base64_encode($timesheet_id)).'" target="_blank">Here</a>';
            $email_template = $this->db->query("select * from " . $this->db->dbprefix('email_template') . " where task='timesheet approve by manager'");
	    $email_temp = $email_template->row();

	    $email_address_from = $email_temp->from_address;
	    $email_address_reply = $email_temp->reply_address;

	    $email_subject = $email_temp->subject;
            $email_message = $email_temp->message;

	    $email_to = $user_info->email;		

	    $email_message = str_replace('{break}', '<br/>', $email_message);
	    $email_message = str_replace('{timesheet_link}', $timesheet_link, $email_message);

	    $str = $email_message;
            $sandgrid_id=$email_temp->sandgrid_id;
                $sendgriddata = array('subject'=>'timesheet approve by manager',
                'data'=>array('timesheet_link'=>$timesheet_link));
                if($sandgrid_id)
                {
                    $str = json_encode($sendgriddata);
                }
//            email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
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
            return  "success"; 
     }
     
     
     function sort_timesheet_data($data){
         $users =  get_users_under_manager();
                if($data['timesheet_start_date']!=''){
                    $from_date = change_date_format($data['timesheet_start_date']);
                } else {
			$from_date = '';
		}
		if($data['timesheet_end_date']!=''){
                    $to_date = change_date_format($data['timesheet_end_date']);
                } else {
			$to_date = '';
		}
                $today_date = date("Y-m-d");
                $this->db->select('*');
                $this->db->from('timesheets');
                $this->db->where('timesheet_company_id',$this->session->userdata('company_id'));
                if($from_date!='' && $to_date !=''){
                    $this->db->where('from_date>=',$from_date);
                    $this->db->where('to_date<=',$to_date);
                }else if($from_date !=''){
                    $this->db->where('from_date>=',$from_date);
                    $this->db->where('to_date <=',$today_date);
                }else if($to_date){
                    $this->db->where('from_date>=',$today_date);
                    $this->db->where('to_date<=',$to_date);
                }else{
                    
                }
                
                $this->db->where('timesheet_status',$data['timesheet_status_id']);
                if($data['timesheet_employee_id'] == 'all'){
                    if($this->session->userdata('is_manager')=='0' && $this->session->userdata('is_administrator')=='0'){
                        $this->db->where('timesheet_user_id',  get_authenticateUserID());
                    }else if($this->session->userdata('is_manager')=='1'){
                        $arr= array();
                        if($users){
                           foreach ($users as $u){
                             $arr[] = $u;
                            }
                        }
                        array_push($arr, get_authenticateUserID());
                        $this->db->where_in('timesheet_user_id',  $arr);
                    }
                }else{
                    $this->db->where('timesheet_user_id',$data['timesheet_employee_id']);
                }
                $query = $this->db->get();
               //echo $this->db->last_query(); die();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
     }
     
     function get_timesheet_approver_comments($timesheet_id,$timesheet_user_id){
         $approver_details = get_user_info($timesheet_user_id);
         if($approver_details->timesheet_approver_id !='0'){
            $this->db->select('*');
            $this->db->from('timesheet_comments');
            $this->db->where('timesheet_id',$timesheet_id);
            $this->db->where('timesheet_approver_id', $approver_details->timesheet_approver_id);
            $this->db->where('company_id',$this->session->userdata('company_id'));
            $query = $this->db->get();

            if($query->num_rows()>0){
                return $query->row();
            }else{
                return 0;
            }
         }else{
             return 0;
         }
     }
     
     function export_timesheet($ids){
         $timesheet_data = $this->get_one_timesheet_data($ids);
         
         $this->db->set('timesheet_status','exported');
         $this->db->set('timesheet_updated_date',date('Y-m-d H:i:s'));
         $this->db->where('timesheet_id',$ids);
         $this->db->where('timesheet_company_id',  $this->session->userdata('company_id'));
         $this->db->update('timesheets');
           
         $result = array();
         $word1 = ucfirst(substr($timesheet_data->first_name,0,1));
         $word2 = ucfirst(substr($timesheet_data->last_name,0,1));
         $timesheet_code = $word1.$word2.'-'.$timesheet_data->timesheet_code;
         $completed = $this->config->item('completed_id');
         
         $this->db->select('t.*');
         $this->db->from('tasks t');
         //$this->db->where_in('customer_id',$arr);
         $this->db->where('t.task_scheduled_date >=',$timesheet_data->from_date);
         $this->db->where('t.task_scheduled_date <=',$timesheet_data->to_date);
         $this->db->where('t.task_status_id',$completed);
         $this->db->where('t.task_allocated_user_id',$timesheet_data->timesheet_user_id);
         $this->db->where('t.task_company_id',  $this->session->userdata('company_id'));
         $this->db->where('t.is_deleted','0'); 
         $this->db->where('t.is_personal','0');
         $this->db->where('t.exported','0');
         $query = $this->db->get();        
         if($query->num_rows()>0){
             $data =  $query->result_array();
             foreach($data as $d){
                 
                 $d['first_name']=$timesheet_data->first_name;
                 $d['last_name'] = $timesheet_data->last_name;
                 $d['period_from'] = $timesheet_data->from_date;
                 $d['period_to'] = $timesheet_data->to_date;
                 $d['timesheet_code'] = $timesheet_code;
                 $result[] = $d;
                 
                 $this->db->set('exported','1');
                 $this->db->where('task_id',$d['task_id']);
                 $this->db->update('tasks');
             }
             return $result;
             
         }else{
             return 0;
         }     
    }
    
     function get_customerlist_for_timesheets($timesheet_user_id,$form_date,$to_date){
                
		$completed = $this->config->item('completed_id');
                $this->db->select('c.*');
                $this->db->from('tasks t');
                $this->db->join('customers c','c.customer_id= t.customer_id','left');
                $this->db->where('c.customer_company_id',$this->session->userdata('company_id'));
                $this->db->where('t.task_scheduled_date >=',$form_date);
                $this->db->where('t.task_scheduled_date <=',$to_date);
                $this->db->where('t.task_status_id',$completed);
                $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
                $this->db->where('t.is_deleted','0');
                $this->db->where('t.is_personal','0');
                $this->db->where('t.task_allocated_user_id',$timesheet_user_id);
                $this->db->where('t.customer_id!=','');
                $this->db->where('t.customer_id!=','0');
                $this->db->group_by('t.customer_id');
                $query = $this->db->get();
               // echo $this->db->last_query(); die();
                if($query->num_rows()>0){
                    return $query->result();
                }else{
                    return 0;
                }
       }
       
     function get_overall_timesheet_time($user_id,$from_date,$to_date){
            $completed = $this->config->item('completed_id');
            $this->db->select('sum(billed_time) as task_billed_time');
            $this->db->from('tasks');
            $this->db->where('task_scheduled_date >=',$from_date);
            $this->db->where('task_scheduled_date <=',$to_date);
            $this->db->where('task_status_id',$completed);
            $this->db->where('task_company_id',$this->session->userdata('company_id'));
            $this->db->where('is_deleted','0');
            $this->db->where('is_personal','0');
            $this->db->where('task_allocated_user_id',$user_id);
            $query = $this->db->get();
           // echo $this->db->last_query();
            if($query->num_rows()>0){
                return $query->row()->task_billed_time;
            }else{
                return 0;
            }
       }
     /**
      * Get customer list & task form strat date to end date of timesheet to export on xero. 
      * @param type $from_date
      * @param type $to_date
      * @param type $users
      * @return int
      */
     function xero_export($from_date,$to_date,$users){
         
         $customers = $this->get_list_of_customers($from_date,$to_date,$users);
        
         if($customers){
             foreach($customers as $cus){
                 $result[] = $cus->customer_id;
             }
         }else{
             $result[] = '';
         }
         
            $completed = $this->config->item('completed_id');

            $this->db->select('t.task_id,t.billed_time,t.task_scheduled_date,t.task_title,t.charge_out_rate,t.customer_id,t.task_project_id,p.project_title,CONCAT(u.first_name," ",u.last_name) as allocated_user_name');
            $this->db->from('tasks t');
            $this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
            $this->db->join('project p','p.project_id = t.task_project_id','left');
            $this->db->where_in('t.customer_id',$result);
            $this->db->where('t.task_scheduled_date >=',$from_date);
            $this->db->where('t.task_scheduled_date <=',$to_date);
            $this->db->where('t.task_status_id',$completed);
            $this->db->where_in('t.task_allocated_user_id',$users);
            $this->db->where('t.task_company_id',  $this->session->userdata('company_id'));
            $this->db->where('t.is_deleted','0'); 
            $this->db->where('t.is_personal','0');
            $this->db->where('t.exported','0');
            $this->db->order_by('t.task_scheduled_date','asc');
            $query = $this->db->get();        

            if($query->num_rows()>0){
                $data['result'] = $query->result_array();
                $data['customers'] = $customers;
                return $data;
            }else{
                return 0;
            } 
    }
    /**
     * Getting customer list from task table for spacific period.
     * @param type $from_date
     * @param type $to_date
     * @param type $users
     * @return int
     */
    function get_list_of_customers($from_date,$to_date,$users){
               
		$completed = $this->config->item('completed_id');
                $this->db->select('c.*');
                $this->db->from('tasks t');
                $this->db->join('customers c','c.customer_id= t.customer_id','left');
                $this->db->where('c.customer_company_id',$this->session->userdata('company_id'));
                $this->db->where('t.task_scheduled_date >=',$from_date);
                $this->db->where('t.task_scheduled_date <=',$to_date);
                $this->db->where('t.task_status_id',$completed);
                $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
                $this->db->where('t.is_deleted','0');
                $this->db->where('t.is_personal','0');
                $this->db->where_in('t.task_allocated_user_id',$users);
                $this->db->where('t.customer_id!=','');
                $this->db->where('t.customer_id!=','0');
                $this->db->where('t.exported','0');
                $this->db->group_by('t.customer_id');
                $query = $this->db->get();
              
                if($query->num_rows()>0){
                    return $query->result();
                }else{
                    return 0;
                }
    }
    /**
     * Reset exported flag of task on cancelation of timesheet.
     */ 
    function set_task_to_again_export($task_list){
        if($task_list !='0'){
            foreach($task_list as $list){
                $this->db->set('exported','0');
                $this->db->where('task_id',$list['task_id']);
                $this->db->update('tasks');
            }
         }
    }
    
}
