<?php
class Cron extends SPACULLUS_Controller {
	/*
	 Function name :Cron()
	 */
	function Cron() {
		parent :: __construct ();
                define("OUTLOOK_REDIRECT_URL", base_url()."user/outlook_synchronization");
                $this->load->helper('custom');
                /**
                 * gmail push notification url
                 */
                define('PUSH_NOTIFICATION_URL',   base_url().'cron/get_gmail_notification');
	}

	/*
	 * function name : daily_email_summary
	 * author : upeksha
	 * dec : this cron job runs on daily basis to send email to user for their daily work summary 
	*/	
	function daily_email_summary(){ 
		$query1 = $this->db->select('user_id,first_name,last_name,email,company_id,daily_email_summary')->from('users')->where('is_deleted','0')->where('user_status','Active')->get();
		if($query1->num_rows()>0){
			$users = $query1->result_array();
			if($users){
				foreach($users as $user){
                                        if($user['daily_email_summary'] == "1"){
                                                $timezone = get_UserTimeZone($user['user_id']);
						 date_default_timezone_set($timezone);
						$completed_status = get_task_status_id_by_name_company('Completed', $user['company_id']);
						if(date("H") == "06"){
                                                        $length = base64_encode($user['user_id']);
                                                        $encoded_user_id = "10".str_pad($length,10,"0",STR_PAD_LEFT);
                                                        $start_date = date('Y-m-d');
                                                        $offdays = get_company_offdays($user['company_id']);
                                                        $completed_id = $completed_status;
							//overdue one off
							$overdue_str = '';
                                                        $query2 = $this->db->select('t.task_id,t.task_title,t.task_scheduled_date,t.task_due_date,t.task_time_estimate,t.task_project_id,p.project_title,t.task_owner_id,t.task_allocated_user_id,u.first_name as owner_first_name,u.last_name as owner_last_name')
												->from('tasks t')
												->join('project p','p.project_id = t.task_project_id','left')
												->join('users u','u.user_id = t.task_owner_id','left')
												->where('t.task_allocated_user_id',$user['user_id'])
												->where('t.task_due_date <',$start_date)
												->where('t.frequency_type','one_off')
												->where('t.task_due_date !=','0000-00-00')
                                                                                                ->where('t.task_company_id',$user['company_id'])
                                                                                                ->where('t.task_status_id !=',$completed_status)
												->where('t.is_deleted','0')
												->get();
                                                        $overdue_tasks = array();
                                                        $task_list = array();
                                                        if($query2->num_rows()>0){
                                                            $over = $query2->result_array();
							}else{
                                                            $over = array();
                                                        }
                                                        /**
                                                         * overdue recurring task
                                                         */
                                                        $query6 = $this->db->select('t.*,t.task_title,t.task_scheduled_date,t.task_due_date,t.task_time_estimate,t.task_project_id,p.project_title,t.task_owner_id,t.task_allocated_user_id,u.first_name as owner_first_name,u.last_name as owner_last_name')
												->from('tasks t')
												->join('project p','p.project_id = t.task_project_id','left')
												->join('users u','u.user_id = t.task_owner_id','left')
												->where('t.task_allocated_user_id',$user['user_id'])
												->where('t.task_scheduled_date !=','0000-00-00')
												->where('t.task_scheduled_date <',$start_date)
												->where('t.frequency_type','recurrence')
                                                                                                ->where('t.task_company_id',$user['company_id'])
                                                                                                ->where('t.is_deleted','0')
                                                                                                ->where("(CASE WHEN t.no_end_date =1  THEN '1'
                                                                                                        WHEN t.no_end_date =2 AND t.end_after_recurrence = '1' AND t.start_on_date < '$start_date'  THEN '1'
                                                                                                        WHEN t.no_end_date =2 AND t.end_by_date >= '$start_date' THEN '1'
                                                                                                        WHEN t.no_end_date =3 AND t.end_by_date >= '$start_date' THEN '1'
                                                                                                        ELSE '0' END)='1'")
												->get();
                                                        if($query6->num_rows()>0){
                                                            $res = $query6->result_array();
                                                            if($res){
                                                                $start_date = user_first_task_date($user['user_id']);
                                                                $start_date = date("Y-m-d",strtotime($start_date));
                                                                $end_date = date("Y-m-d");
                                                                foreach($res as $row){
                                                                    $re_data = monthly_recurrence_logic($row,$start_date,$end_date,$offdays);
                                                                    if($re_data){
                                                                        foreach($re_data as $row2){
                                                                            $chk_rec = check_virtual_existance_in_cron($row2['master_task_id'],$row2['task_orig_scheduled_date'],$row2['task_company_id']);
                                                                            if($chk_rec){
                                                                                if($chk_rec['task_due_date']>= $start_date && $chk_rec['task_due_date'] < $end_date && $chk_rec['task_allocated_user_id'] == $user['user_id'] && $chk_rec['task_status_id'] != $completed_id && $chk_rec['is_deleted'] == "0"){
                                                                                   
                                                                                }
                                                                            } else {
                                                                                if($row2['task_due_date']>= $start_date && $row2['task_due_date'] < $end_date && $row2['task_allocated_user_id'] == $user['user_id'] && $row2['task_status_id'] != $completed_id){
                                                                                    array_push($task_list,$row2);
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
							 $overdue_tasks = array_merge($over,$task_list);
                                                         if($overdue_tasks){
							    $overdue_str .= '<ul>';
                                                            foreach($overdue_tasks as $overdue_task){
								$overdue_str .= '<li><p>';
								$overdue_str .= ucfirst($overdue_task['task_title']).' - '.date(default_date_format(),strtotime($overdue_task['task_due_date']));
								if($overdue_task['task_time_estimate']){
								    $overdue_str .= ' - Estimate: '.minutesToTime($overdue_task['task_time_estimate']);
								}
								if($overdue_task['project_title']){
                                                                    $overdue_str .= ' - '.ucfirst($overdue_task['project_title']);
								}
								if($overdue_task['task_owner_id'] != $user['user_id']){
                                                                    $overdue_str .= ' - Assigned by '.ucfirst($overdue_task['owner_first_name'].' '.$overdue_task['owner_last_name']);
								}
								$overdue_str .= '</p></li>';
                                                            }
                                                            $overdue_str .= '</ul>';
							}
                                                        //today one off task
                                                        $start_date = date('Y-m-d');
							$today_str = '';
							$query3 = $this->db->select('t.task_id,t.task_title,t.task_scheduled_date,t.task_due_date,t.task_time_estimate,t.task_project_id,p.project_title,t.task_owner_id,t.task_allocated_user_id,u.first_name as owner_first_name,u.last_name as owner_last_name')
												->from('tasks t')
												->join('project p','p.project_id = t.task_project_id','left')
												->join('users u','u.user_id = t.task_owner_id','left')
												->where('t.task_allocated_user_id',$user['user_id'])
												->where('t.task_scheduled_date',$start_date)
												->where('t.task_scheduled_date !=','0000-00-00')
												->where('t.frequency_type','one_off')
                                                                                                ->where('t.task_company_id',$user['company_id'])
                                                                                                ->where('t.task_status_id !=',$completed_status)
												->where('t.is_deleted','0')
												->get();
                                                        $today_tasks = array();
                                                        $task_list1 = array();
                                                        if($query3->num_rows()>0){
                                                            $res1 = $query3->result_array();
							}else{
                                                            $res1 = array();
                                                        }
							
							/**
                                                         * today recurring task
                                                         */
                                                        
                                                        $query5 = $this->db->select('t.*,t.task_title,t.task_scheduled_date,t.task_due_date,t.task_time_estimate,t.task_project_id,p.project_title,t.task_owner_id,t.task_allocated_user_id,u.first_name as owner_first_name,u.last_name as owner_last_name')
												->from('tasks t')
												->join('project p','p.project_id = t.task_project_id','left')
												->join('users u','u.user_id = t.task_owner_id','left')
												->where('t.task_allocated_user_id',$user['user_id'])
												->where('t.task_scheduled_date !=','0000-00-00')
												->where('t.task_scheduled_date <=',$start_date)
												->where('t.frequency_type','recurrence')
                                                                                                ->where('t.task_company_id',$user['company_id'])
                                                                                                ->where('t.is_deleted','0')
                                                                                                ->where("(CASE WHEN t.no_end_date =1  THEN '1'
                                                                                                        WHEN t.no_end_date =2 AND t.end_after_recurrence = '1' AND t.start_on_date >= '$start_date'  THEN '1'
                                                                                                        WHEN t.no_end_date =2 AND t.end_by_date >= '$start_date' THEN '1'
                                                                                                        WHEN t.no_end_date =3 AND t.end_by_date >= '$start_date' THEN '1'
                                                                                                        ELSE '0' END)='1'")
												->get();
                                                        if($query5->num_rows()>0){
                                                            $res = $query5->result_array();
                                                            if($res){
                                                                $start_date = date('Y-m-d');
                                                                $end_date = date("Y-m-d",strtotime("+1 day", strtotime($start_date)));
                                                                foreach($res as $row){
                                                                    $re_data = monthly_recurrence_logic($row,$start_date,$end_date,$offdays);
                                                                    if($re_data){
                                                                        foreach($re_data as $row2){
                                                                                $chk_rec = check_virtual_existance_in_cron($row2['master_task_id'],$row2['task_orig_scheduled_date'],$row2['task_company_id']);
                                                                                if($chk_rec){
                                                                                    if($chk_rec['task_due_date']>= $start_date && $chk_rec['task_due_date'] < $end_date && $chk_rec['task_allocated_user_id'] == $user['user_id'] && $chk_rec['task_status_id'] != $completed_id && $chk_rec['is_deleted'] == "0"){
                                                                                        
                                                                                    }
                                                                                } else {
                                                                                    if($row2['task_due_date']>= $start_date && $row2['task_due_date'] < $end_date && $row2['task_allocated_user_id'] == $user['user_id'] && $row2['task_status_id'] != $completed_id){
                                                                                        array_push($task_list1,$row2);
                                                                                    }
                                                                                }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $today_tasks = array_merge($res1,$task_list1);
                                                        if($today_tasks){
                                                            $today_str .= '<ul>';
                                                            foreach($today_tasks as $today_task){
                                                                $today_str .= '<li><p>';
                                                                $today_str .= ucfirst($today_task['task_title']).' - '.date(default_date_format(),strtotime($today_task['task_due_date']));
								if($today_task['task_time_estimate']){
                                                                    $today_str .= ' - Estimate: '.minutesToTime($today_task['task_time_estimate']);
								}
								if($today_task['project_title']){
                                                                    $today_str .= ' - '.ucfirst($today_task['project_title']);
								}
								if($today_task['task_owner_id'] != $user['user_id']){
                                                                    $today_str .= ' - Assigned by '.ucfirst($today_task['owner_first_name'].' '.$today_task['owner_last_name']);
								}
								$today_str .= '</p></li>';
                                                            }
                                                            $today_str .= '</ul>';
							}
                                                        //tomorrow one off task 
                                                        $start_date = date('Y-m-d',strtotime('+1 days'));       
							$tomorrow_str = '';
							$query4 = $this->db->select('t.task_id,t.task_title,t.task_scheduled_date,t.task_due_date,t.task_time_estimate,t.task_project_id,p.project_title,t.task_owner_id,t.task_allocated_user_id,u.first_name as owner_first_name,u.last_name as owner_last_name')
												->from('tasks t')
												->join('project p','p.project_id = t.task_project_id','left')
												->join('users u','u.user_id = t.task_owner_id','left')
												->where('t.task_allocated_user_id',$user['user_id'])
												->where('t.task_scheduled_date',$start_date)
                                                                                                ->where('t.frequency_type','one_off')
												->where('t.task_company_id',$user['company_id'])
                                                                                                ->where('t.task_status_id !=',$completed_status)
												->where('t.is_deleted','0')
												->get();
                                                        
                                                        $tomorrow_tasks = array();
                                                        $task_list2 = array();
							if($query4->num_rows()>0){
                                                            $tom = $query4->result_array();
							}else{
                                                            $tom = array();
                                                        } 
                                                        /**
                                                         * tomorrow recurring task
                                                         */
                                                        
                                                        $query7 = $this->db->select('t.*,t.task_title,t.task_scheduled_date,t.task_due_date,t.task_time_estimate,t.task_project_id,p.project_title,t.task_owner_id,t.task_allocated_user_id,u.first_name as owner_first_name,u.last_name as owner_last_name')
												->from('tasks t')
												->join('project p','p.project_id = t.task_project_id','left')
												->join('users u','u.user_id = t.task_owner_id','left')
												->where('t.task_allocated_user_id',$user['user_id'])
												->where('t.task_scheduled_date !=','0000-00-00')
												->where('t.task_scheduled_date <=',$start_date)
												->where('t.frequency_type','recurrence')
                                                                                                ->where('t.task_company_id',$user['company_id'])
                                                                                                ->where('t.is_deleted','0')
                                                                                                ->where("(CASE WHEN t.no_end_date =1  THEN '1'
                                                                                                        WHEN t.no_end_date =2 AND t.end_after_recurrence = '1' AND t.start_on_date >= '$start_date'  THEN '1'
                                                                                                        WHEN t.no_end_date =2 AND t.end_by_date >= '$start_date' THEN '1'
                                                                                                        WHEN t.no_end_date =3 AND t.end_by_date >= '$start_date' THEN '1'
                                                                                                        ELSE '0' END)='1'")
												->get();
                                                        if($query7->num_rows()>0){
                                                            $res = $query7->result_array();
                                                            if($res){
                                                                $end_date = date("Y-m-d",strtotime("+1 day", strtotime($start_date)));
                                                                    foreach($res as $row){
                                                                            $re_data = monthly_recurrence_logic($row,$start_date,$end_date,$offdays);
                                                                            if($re_data){
                                                                                foreach($re_data as $row2){
                                                                                    $chk_rec = check_virtual_existance_in_cron($row2['master_task_id'],$row2['task_orig_scheduled_date'],$row2['task_company_id']);
                                                                                    if($chk_rec){
                                                                                        if($chk_rec['task_due_date']>= $start_date && $chk_rec['task_due_date'] < $end_date && $chk_rec['task_allocated_user_id'] == $user['user_id'] && $chk_rec['task_status_id'] != $completed_id && $chk_rec['is_deleted'] == "0"){
                                                                                            
                                                                                        }
                                                                                    } else {
                                                                                        if($row2['task_due_date']>= $start_date && $row2['task_due_date'] < $end_date && $row2['task_allocated_user_id'] == $user['user_id'] && $row2['task_status_id'] != $completed_id){
                                                                                            array_push($task_list2,$row2);
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                    }
                                                            }
                                                        }
                                                        $tomorrow_tasks = array_merge($tom,$task_list2);
                                                        if($tomorrow_tasks){
                                                            $tomorrow_str .= '<ul>';
                                                            foreach($tomorrow_tasks as $tomorrow_task){
                                                                $tomorrow_str .= '<li><p>';
                                                                $tomorrow_str .= ucfirst($tomorrow_task['task_title']).' - '.date(default_date_format(),strtotime($tomorrow_task['task_due_date']));
								if($tomorrow_task['task_time_estimate']){
                                                                    $tomorrow_str .= ' - Estimate: '.minutesToTime($tomorrow_task['task_time_estimate']);
								}
                                                                if($tomorrow_task['project_title']){
                                                                    $tomorrow_str .= ' - '.ucfirst($tomorrow_task['project_title']);
								}
                                                                if($tomorrow_task['task_owner_id'] != $user['user_id']){
                                                                    $tomorrow_str .= ' - Assigned by '.ucfirst($tomorrow_task['owner_first_name'].' '.$today_task['owner_last_name']);
								}
								$tomorrow_str .= '</p></li>';
                                                            }
                                                            $tomorrow_str .= '</ul>';
							}
                                                        $task_detail = '';
                                                        if($overdue_str == '' && $today_str == '' && $tomorrow_str == '')
                                                        {}else{
                                                            if($overdue_str){
                                                                    $task_detail .= '<p style="line-height:25px;"><b>Overdue Tasks:</b></p>';
                                                                    $task_detail .= $overdue_str;
                                                            }

                                                            if($today_str){
                                                                    $task_detail .= '<p style="line-height:25px;"><b>Today Tasks:</b></p>';
                                                                    $task_detail .= $today_str;
                                                            }

                                                            if($tomorrow_str){
                                                                    $task_detail .= '<p style="line-height:25px;"><b>Tomorrow Tasks:</b></p>';
                                                                    $task_detail .= $tomorrow_str;
                                                            }

                                                            //email
                                                            /*** send email to task owner user  ****/
                                                            $data=array();
                                                            $data['daily_email_summary']=array('overduetask'=>$overdue_str,
                                                                                                'todaytask'=>$today_str,
                                                                                                'tomorrow'=>$tomorrow_str,
                                                                                                'senderName'=>$this->config->item('sendgrid_schedullo_from_name'),
                                                                                                'senderAdd'=>'',
                                                                                                'senderCity'=>'',
                                                                                                'senderState'=>'',
                                                                                                'senderZip'=>'',
                                                                                                'unsubscribe'=>''
//                                                                                                'link'=>$encoded_user_id
                                                                                                );
                                                            $user_name = $user['first_name'].' '.$user['last_name'];
                                                            $email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='daily email summary'");
                                                            $email_temp=$email_template->row();
                                                            $email_address_from=$email_temp->from_address;
                                                            $email_address_reply=$email_temp->reply_address;
                                                            $sandgrid_id=$email_temp->sandgrid_id;
                                                            $email_subject=$email_temp->subject;

                                                            $email_message=$email_temp->message;

                                                            $user_name = $user['first_name'].' '.$user['last_name'];
                                                            $email_to = $user['email'];

                                                            $email_subject=str_replace('{break}','<br/>',$email_subject);
                                                            $email_subject=str_replace('{user_name}',$user_name,$email_subject);
                                                            $email_subject=str_replace('{task_detail}',$task_detail,$email_subject);

                                                            $email_message=str_replace('{break}','<br/>',$email_message);
                                                            $email_message=str_replace('{user_name}',$user_name,$email_message);
                                                            $email_message=str_replace('{task_detail}',$task_detail,$email_message);

                                                            $str=$email_message;
                                                            if($sandgrid_id){
                                                                mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,$user_name,$email_subject,$sandgrid_id, $data);
                                                            }else{
                                                                email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                                                            }
                                                        }
						}
					}
				}
			}
		}
	}
	
     /**
      * This cron job is executed every 3 min for sending task mail to users.
      */
        function send_mail_notification(){
            ini_set('max_execution_time',300);
            $status = array('pending','failed');
            $this->db->select("*");
            $this->db->from('email_queue');
            $this->db->where_in('status',$status);
            $query1 = $this->db->get();
            //echo $this->db->last_query(); die();
            if($query1->num_rows()>0){
                $mail_list = $query1->result_array();
                foreach($mail_list as $list){
                    $email_address_from = $list['email_from'];
                    $email_address_reply = $list['email_reply'];
                    $email_to = $list['email_to'];
                    $email_subject = $list['email_subject'];
                    $str = $list['message'];
                    //echo $email_address_from; 
                    if($list['sandgrid_id'])
                    {
                        
                        $sandgrid_id = $list['sandgrid_id'];
                        $message = $list['message'];
                        $msg_array = json_decode($message,'true');
                        $email_subject = $msg_array['subject'];
                        $mail_data = array();
                        $mail_data['subject'] = $email_subject;
                        foreach($msg_array['data'] as $key=>$val)
                        {
                            $mail_data['data'][$key] =$val; 
                        }
                        mail_by_sendgrid($email_address_from,$email_address_reply,$email_to,'',$email_subject,$sandgrid_id,$mail_data);
                    }
                    else {
                        email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                    }
                    $update =array(
                        "status"=>'sent'
                    );
                    $this->db->where('id',$list['id']);
                    $this->db->update('email_queue',$update);
                }
            }
        }
        function push_notification()
        {
                $token=isset($_GET['validationToken'])?$_GET['validationToken']:'';
  
                if($token)
                {
                    header('Content-Type:text/plain');
                    echo $token;
                }
                else {
                    $f1=json_decode(file_get_contents('php://input'),'true');
                    $data='';
                    foreach ($f1['value'] as $one){
                        $resourse = $one['resource'];
                        if($resourse)
                        {
                            $outlook_user_id = get_string_between($resourse,'Users/','/Events');
                            $outlooktask = explode('Events/',$resourse);
                            $outlook_task_id = $outlooktask[1];
                            $where=array('outlook_user_id'=>$outlook_user_id);
                            $this->db->select('users.browser_token,outlook_detail.*');
                            $this->db->from('outlook_detail');
                            $this->db->join('users','outlook_detail.user_id=users.user_id','inner');
                            $this->db->where($where);
                            $query = $this->db->get();
                            
                            $detail = $query->row_array();
                            $user_id = $detail['user_id'];
                            $browser_token = $detail['browser_token'];
                            $tokens = outlook_refresh_token($detail['user_id']);
                            
                            $app_info=getAppInfoByUserId($user_id);
                            $client_id = $app_info[0]->client_id;
                            $client_secret = $app_info[0]->client_secret;
                            $company_id = $app_info[0]->api_company_id;
                            $fields=array(
                                'grant_type'=>  'client_credentials',
                                'client_id'=> $client_id,
                                'client_secret'=> $client_secret
                            );
                            $post='';
                            $curl = curl_init();
                            foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                            $post = rtrim($post,'&');
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                              CURLOPT_URL => base_url()."OAuth2/token",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => "",
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => "POST",
                              CURLOPT_POSTFIELDS => $post,
                              CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/x-www-form-urlencoded"
                              ),
                            ));

                            $response1 = curl_exec($curl);
                            $err = curl_error($curl);

                            curl_close($curl);


                            $response1 = json_decode($response1);

                            if(isset($response1->access_token))
                            {
                                $api_access_token=$response1->access_token;
                                $header = array(
                                "accept: application/json",
                                "Authorization: Bearer ".$api_access_token,
                                "cache-control: no-cache",
                                "Content-Type: application/x-www-form-urlencoded"
                              );
                                if($one['changeType'] != 'deleted')
                                {
                                    $api_url='https://graph.microsoft.com/v1.0/'.$resourse;
                                    $send=array('User-Agent:Schedullo/1.0',
                                    'client-request-id:'.OUTLOOK_CLIENT_ID,
                                    'return-client-request-id:true',
                                    'authorization:Bearer '.$tokens['access_token']
                                    );
                                    $curl = curl_init($api_url);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER,$send);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
                                    $curl_response = curl_exec($curl);

                                    curl_close($curl);
                                    $response=json_decode($curl_response,'true');
                                    $outlook_task_id = $response['id'];
                            }
                            $swimlane_id=get_default_swimlane($user_id);
                            $exist = is_exist_task($outlook_task_id,$user_id,'outlook');
                            $gmail_task_id = '';
                            $frequency_type = 'one_off';
                            $msg = '';
                            if($one['changeType'] == 'created')
                            {
                                $event_start_date = $response['start']['dateTime'];
                                $event_end_date = $response['end']['dateTime'];
                                $task_time_estimate = (strtotime($event_end_date)-strtotime($event_start_date))/60;
                                $timezone =  get_UserTimeZone($user_id);
                                date_default_timezone_set($timezone);

                                $task_scheduled_date=Date('Y-m-d',(strtotime($event_start_date)+date('Z')));
                                $task_status_id=get_task_status_id_by_name_company('Ready',$company_id);
                                $task_title = $response['subject'];
                                $task_description = $response['bodyPreview'];
                                 if($response['type'] == 'seriesMaster')
                                $frequency_type = 'recurrence';
                                $fields=array(
                                        'user_id'=>  $user_id,
                                        'task_title'=> $task_title,
                                        'task_description'=> $task_description,
                                        'task_due_date' => $task_scheduled_date,
                                        'task_scheduled_date'=> $task_scheduled_date,
                                        'task_status_id'=> $task_status_id,
                                        'task_allocated_user_id'=>  $user_id,
                                        'task_project_id'=> 0,
                                        'task_time_estimate'=>$task_time_estimate,
                                        'outlook_task_id'=>$outlook_task_id,
                                        'gmail_task_id'=>$gmail_task_id
                                    );
                            
                                    $post='';
                                    foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                                    $post = rtrim($post,'&');
                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => base_url()."api/v1/addTask",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => "",
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => "POST",
                                      CURLOPT_POSTFIELDS => $post,
                                      CURLOPT_HTTPHEADER =>$header ,
                                    ));

                                    $response2 = curl_exec($curl);
                                    $task_data = json_decode($response2,true);
                                    
                                    curl_close($curl);
                                    if($response['type'] == 'seriesMaster')
                                        create_integrated_recurring_task($response,'outlook',$task_data['task_id'],'',$user_id.'@'.$company_id);
                                   $msg = array('to'=>$browser_token,
                                     'notification'=>array('body'=>'','title'=>'Task "'.$response['subject'].'" has been created in Schedullo','click_action'=>base_url().'calendar/weekView','icon'=>base_url().'default/assets/img/logo_new.png'),
                                     'data'=>array('user_id'=>$user_id,'task_id'=>$task_data['task_id'],'date'=>strtotime($task_scheduled_date),'task_status_id'=>$task_status_id,'change_type'=>'created','swimlane_id'=>$swimlane_id,'frequency_type'=>$frequency_type));
                                    
                            }
                            else if($one['changeType'] =='updated')
                            {
                                    $event_start_date = $response['start']['dateTime'];
                                    $event_end_date = $response['end']['dateTime'];
                                    $task_time_estimate = (strtotime($event_end_date)-strtotime($event_start_date))/60;
                                    $timezone =  get_UserTimeZone($user_id);
                                    date_default_timezone_set($timezone);

                                    $task_scheduled_date=Date('Y-m-d',(strtotime($event_start_date)+date('Z')));
                                    $task_status_id=get_task_status_id_by_name_company('Ready',$company_id);
                                    $task_title = $response['subject'];
                                    $task_description = $response['bodyPreview'];
                                     if($response['type'] == 'seriesMaster')
                                         $frequency_type = 'recurrence';
                                    if($exist){
                                     $fields=array(
                                        'task_title'=> $task_title,
                                        'task_description'=> $task_description,
                                        'task_due_date' => $task_scheduled_date,
                                        'task_scheduled_date'=> $task_scheduled_date,
                                        'task_time_estimate'=>$task_time_estimate,
                                    );

                                    $this->db->where('task_id',$exist['task_id']);
                                    $this->db->where('outlook_task_id',$outlook_task_id);
                                    $this->db->update('tasks',$fields);
                                    if($response['type'] == 'seriesMaster')
                                        create_integrated_recurring_task($response,'outlook',$exist['task_id'],'',$user_id.'@'.$company_id);
                                    $msg = array('to'=>$browser_token,
                                         'notification'=>array('body'=>'','title'=>'Task "'.$exist['task_title'].'"  has been updated in Schedullo','click_action'=>base_url().'calendar/weekView','icon'=>base_url().'default/assets/img/logo_new.png'),
                                         'data'=>array('user_id'=>$exist['task_owner_id'],'task_id'=>$exist['task_id'],'date'=>strtotime($task_scheduled_date),'task_status_id'=>$exist['task_status_id'],'change_type'=>'updated','swimlane_id'=>$swimlane_id,'frequency_type'=>$frequency_type));
                                }
                                else
                                {
                                    $fields=array(
                                        'user_id'=>  $user_id,
                                        'task_title'=> $task_title,
                                        'task_description'=> $task_description,
                                        'task_due_date' => $task_scheduled_date,
                                        'task_scheduled_date'=> $task_scheduled_date,
                                        'task_status_id'=> $task_status_id,
                                        'task_allocated_user_id'=>  $user_id,
                                        'task_project_id'=> 0,
                                        'task_time_estimate'=>$task_time_estimate,
                                        'outlook_task_id'=>$outlook_task_id,
                                        'gmail_task_id'=>$gmail_task_id
                                    );
                            
                                    $post='';
                                    foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                                    $post = rtrim($post,'&');
                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => base_url()."api/v1/addTask",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => "",
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => "POST",
                                      CURLOPT_POSTFIELDS => $post,
                                      CURLOPT_HTTPHEADER =>$header ,
                                    ));

                                    $response2 = curl_exec($curl);
                                    $task_data = json_decode($response2,true);
                                    
                                    curl_close($curl);
                                    if($response['type'] == 'seriesMaster')
                                        create_integrated_recurring_task($response,'outlook',$task_data['task_id'],'',$user_id.'@'.$company_id);
                                    $msg = array('to'=>$browser_token,
                                     'notification'=>array('body'=>'','title'=>'Task "'.$response['subject'].'" has been created in Schedullo','click_action'=>base_url().'calendar/weekView','icon'=>base_url().'default/assets/img/logo_new.png'),
                                     'data'=>array('user_id'=>$user_id,'task_id'=>$task_data['task_id'],'date'=>strtotime($task_scheduled_date),'task_status_id'=>$task_status_id,'change_type'=>'created','swimlane_id'=>$swimlane_id,'frequency_type'=>$frequency_type));
                                }
                            }
                            else if($one['changeType'] == 'deleted')
                            {
                                if($exist)
                                {
                                    $fields=array(
                                        "user_id"=>$user_id,
                                        "task_id"=>$exist['task_id']
                                    );
                                    $post='';
                                    foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                                    $post = rtrim($post,'&');
                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => base_url()."api/v1/deletetask",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => "",
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => "DELETE",
                                      CURLOPT_POSTFIELDS => $post,
                                      CURLOPT_HTTPHEADER => $header,
                                    ));

                                    $response2 = curl_exec($curl);
                                    $err = curl_error($curl);

                                    curl_close($curl);
                                    $timezone = get_UserTimeZone($user_id);
                                    date_default_timezone_set($timezone);
                                    
                                    $headersn = array(
                                            'Authorization: key=AAAAJzDOV4w:APA91bEOmbs-LtN5SQx1h45R21xAZ1u6bzg5FcjATdDX8m7pYAHUqYIKu-CXFxcPZAMOQ_V_33nRqMUeEdOVq_OS70eWSEV6U_8Jvd3KyBguBzUy3gd9YccPdh2G8Njy8eAnAI6oCpca',
                                            'Content-Type: application/json',
                                       );

                                     $msg = array('to'=>$browser_token,
                                     'notification'=>array('body'=>'','title'=>'Task "'.$exist['task_title'].'" has been deleted from Schedullo','click_action'=>base_url().'calendar/weekView','icon'=>base_url().'default/assets/img/logo_new.png'),
                                     'data'=>array('user_id'=>$exist['task_owner_id'],'task_id'=>$exist['task_id'],'date'=>strtotime($exist['task_scheduled_date']),'task_status_id'=>$exist['task_status_id'],'change_type'=>'deleted','swimlane_id'=>$swimlane_id,'frequency_type'=>$exist['frequency_type']));
                                }
                            }
                            if($msg)
                            {
                                $headersn = array(
                                            'Authorization: key=AAAAJzDOV4w:APA91bEOmbs-LtN5SQx1h45R21xAZ1u6bzg5FcjATdDX8m7pYAHUqYIKu-CXFxcPZAMOQ_V_33nRqMUeEdOVq_OS70eWSEV6U_8Jvd3KyBguBzUy3gd9YccPdh2G8Njy8eAnAI6oCpca',
                                            'Content-Type: application/json',
                                       );
                                     $url = 'https://fcm.googleapis.com/fcm/send';

                                     $ch = curl_init();

                                        // Set the url, number of POST vars, POST data
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersn);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));

                                        $resultn = curl_exec($ch);
                            }
                            }
                            
                        }
                        
                    }
                }
 
     
 }
 
        
        
        function outlook_subscription_all()
        {
            $this->db->select("outlook_detail.*,users.company_id,users.outlook_synchronization_on,users.email");
            $this->db->from('outlook_detail');
            $this->db->join('users','outlook_detail.user_id=users.user_id');
            $this->db->where("DATE_SUB(subscription_expiration,INTERVAL 1 DAY) <", Date('Y-m-d\TH:i:s.000\Z'));
            $this->db->where(array('users.outlook_synchronization_on'=>1,'users.is_deleted'=>0));
            $query=$this->db->get();
            $all = $query->result_array();
            foreach($all as $one)
            {
                $user_id = $one['user_id'];
                $token = outlook_refresh_token($user_id);
                $accesstoken = $token['access_token'];
                $send=array('User-Agent:Schedullo/1.0',
                'cache-control: no-cache',
                'client-request-id:'.OUTLOOK_CLIENT_ID,
                'return-client-request-id:true',
                'Authorization:Bearer '.$accesstoken,
                'Content-Type: application/json'
                );
                $date = Date('Y-m-d');
                $date = Date("Y-m-d\TH:i:s.000\Z",strtotime("+3 days", strtotime($date)));
                
                $curl = curl_init();
                $postfields=array(
                   "expirationDateTime"=>$date,
                );

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://graph.microsoft.com/beta/subscriptions/".$one['subscription_id'],
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "PATCH",
                  CURLOPT_POSTFIELDS => json_encode($postfields),
                  CURLOPT_HTTPHEADER => $send,
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                $this->load->helper('file');

                if ( !write_file('outlook_subscription.txt', $one['user_id'].$one['email'].'token:'.json_encode($token).'currnet_subscrption_id'.$one['subscription_id'].'response:'.$response ."error:".$err. "\r\n\r\n", 'a')){
                     //echo 'Unable to write the file';
                }

                curl_close($curl);

                $resp_array=json_decode($response,true);
                if(isset($resp_array['expirationDateTime']) && $resp_array['expirationDateTime'] !=''){
                $where = array('user_id'=> $user_id);
                $update=array(
                    'subscription_id'=>$resp_array['id'],
                    'subscription_expiration'=>$resp_array['expirationDateTime']
                    );
                $this->db->where($where);
                $this->db->update('outlook_detail',$update);
                }
            }
        }
        /**
         * This function is used for createing,updating and deleting task on the basis of gmail push notification.
         */
        function get_gmail_notification(){ 
            $data = getallheaders();
            
            $notification_id = $data['X-Goog-Channel-ID'];
            
            $this->db->select('*');
            $this->db->from('gmail_integration_details');
            $this->db->where('notification_id',$notification_id);
            $query = $this->db->get();
            if($query->num_rows()>0){
                $data = $query->row_array();
            }else{
                $data = '';
            }
          
            if(isset($data)){
                    $user_id = $data['user_id'];
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => GMAIL_TOKEN_URL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "grant_type=refresh_token&client_id=".GMAIL_CLIENT."&client_secret=".GMAIL_SECRET."&refresh_token=".$data['gmail_refresh_token'],
                        CURLOPT_HTTPHEADER => array(
                          "cache-control: no-cache",
                          "content-type: application/x-www-form-urlencoded",
                        ),
                      ));

                      $response = curl_exec($curl);
                      $array_response = json_decode($response,true);
                      
                    curl_close($curl);
                    $curl1 = curl_init();
                    
                    
                    
                    curl_setopt_array($curl1, array(
                      CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/".$data['gmail_calendar_id']."/events?syncToken=".$data['gmail_nextSyncToken'],
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "GET",
                      CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer ".$array_response['access_token'],
                        "cache-control: no-cache",
                        "content-type: application/json"
                      ),
                    ));
                    
                    $response2 = curl_exec($curl1);
                    
                    $new_data = json_decode($response2,true);
                    curl_close($curl1);
                    //pr($new_data); 
                    /**
                     * update nextsyncToken in db
                     */
                    
                    $this->db->set('gmail_nextSyncToken',$new_data['nextSyncToken']);
                    $this->db->where('notification_id',$notification_id);
                    $this->db->update('gmail_integration_details');
                    
                    
                    /**
                     * get browser token for notification on browser
                     */
                    $this->db->select('browser_token');
                    $this->db->from('users');
                    $this->db->where('user_id',$user_id);
                    $query = $this->db->get();
                    $exist = $query->row_array();
                    
                    
                    /**
                     * check gmail event id & create/update/delete task in db.
                     */
                    if(isset($new_data['items'])){
                        $app_info = getAppInfoByUserId($user_id);
                        $client_id = $app_info[0]->client_id;
                        $client_secret = $app_info[0]->client_secret;
                        $company_id = $app_info[0]->api_company_id;
                        $fields=array(
                            'grant_type'=>  'client_credentials',
                            'client_id'=> $client_id,
                            'client_secret'=> $client_secret
                        );

                        $post='';

                        foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                        $post = rtrim($post,'&');
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                          CURLOPT_URL => base_url()."OAuth2/token",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS => $post,
                          CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/x-www-form-urlencoded"
                          ),
                        ));

                        $response1 = curl_exec($curl);
                        
                        curl_close($curl);


                        $response1 = json_decode($response1);
                        if(isset($response1->access_token)){
                            $timezone =  get_UserTimeZone($user_id);
                            date_default_timezone_set($timezone);
                            $swimlane_id=get_default_swimlane($user_id);
                            $api_access_token=$response1->access_token;
                            $task_status_id = get_task_status_id_by_name_company('Ready',$company_id);
                            $header = array(
                                "accept: application/json",
                                "Authorization: Bearer ".$api_access_token,
                                "cache-control: no-cache",
                                "Content-Type: application/x-www-form-urlencoded"
                              );
                            foreach($new_data['items'] as $task){
                                $status = is_exist_task($task['id'],$user_id);
                                
                                if($status == 0 && $task['status'] == 'confirmed'){
                                    $event_start_date = isset($task['start']['dateTime'])?$task['start']['dateTime']:$task['start']['date'];
                                    $event_end_date = isset($task['end']['dateTime'])?$task['end']['dateTime']:$task['end']['date'];

                                    $task_scheduled_date=Date('Y-m-d',(strtotime($event_start_date)+date("Z")));
                                    $task_time_estimate = (strtotime($event_end_date)-strtotime($event_start_date))/60;
                                    $task_title = $task['summary'];
                                    $task_description = isset($task['description'])?$task['description']:'';
                                    $outlook_task_id = '';
                                    $gmail_task_id = $task['id'];
                                    $fields=array(
                                        'user_id'=>  $user_id,
                                        'task_title'=> $task_title,
                                        'task_description'=> $task_description,
                                        'task_due_date' => $task_scheduled_date,
                                        'task_scheduled_date'=> $task_scheduled_date,
                                        'task_status_id'=> $task_status_id,
                                        'task_allocated_user_id'=>  $user_id,
                                        'task_project_id'=> 0,
                                        'task_time_estimate'=>$task_time_estimate,
                                        'outlook_task_id'=>$outlook_task_id,
                                        'gmail_task_id'=>$gmail_task_id
                                    );
                            
                                    $post='';
                                    foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                                    $post = rtrim($post,'&');
                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => base_url()."api/v1/addTask",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => "",
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => "POST",
                                      CURLOPT_POSTFIELDS => $post,
                                      CURLOPT_HTTPHEADER =>$header ,
                                    ));

                                    $response2 = curl_exec($curl);
                                    $task_data = json_decode($response2,true);
                                    
                                    curl_close($curl);
                                   
                                    if(isset($task['recurrence'])){
                                        $frequency_type = 'recurrence';
                                        create_integrated_recurring_task($task['recurrence'],'gmail',$task_data['task_id'],$task_scheduled_date);
                                    }else{
                                        $frequency_type = 'one_off';
                                    }
                                    
                                    if(isset($exist) && !isset($task['recurringEventId'])){
                                        $headersn = array(
                                            'Authorization: key=AAAAJzDOV4w:APA91bEOmbs-LtN5SQx1h45R21xAZ1u6bzg5FcjATdDX8m7pYAHUqYIKu-CXFxcPZAMOQ_V_33nRqMUeEdOVq_OS70eWSEV6U_8Jvd3KyBguBzUy3gd9YccPdh2G8Njy8eAnAI6oCpca',
                                            'Content-Type: application/json',
                                        );

                                        $msg = array('to'=>$exist['browser_token'],
                                        'notification'=>array('body'=>'','title'=>'Task "'.$task_title.'" has been created in Schedullo','click_action'=>base_url().'calendar/weekView','icon'=>base_url().'default/assets/img/logo_new.png'),
                                        'data'=>array('user_id'=>$user_id,'task_id'=>$task_data['task_id'],'date'=>strtotime($task_scheduled_date),'task_status_id'=>$task_status_id,'change_type'=>'created','swimlane_id'=>$swimlane_id,'frequency_type'=>$frequency_type));
                                        $url = 'https://fcm.googleapis.com/fcm/send';

                                        $ch = curl_init();

                                           // Set the url, number of POST vars, POST data
                                           curl_setopt($ch, CURLOPT_URL, $url);
                                           curl_setopt($ch, CURLOPT_POST, true);
                                           curl_setopt($ch, CURLOPT_HTTPHEADER, $headersn);
                                           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                           curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));

                                           $resultn = curl_exec($ch);
                                          
                                           curl_close($ch);
                                    }
                                    else{
                                        if(isset($task['recurringEventId'])){
                                            $master_task_id = get_master_task_id_by_gmailid($task['recurringEventId'],$user_id);
                                            if($master_task_id != 0){
                                                $this->db->set('master_task_id',$master_task_id);
                                                $this->db->where('task_id',$task_data['task_id']);
                                                $this->db->where('gmail_task_id',$gmail_task_id);
                                                $this->db->update('tasks');
                                            }
                                        }
                                    }
                                }
                                else{
                                    if($task['status'] == 'cancelled' && isset($task['recurringEventId'])){
                                        $recurring_task = is_exist_task($task['recurringEventId'],$user_id);
                                        if(isset($recurring_task)){
                                             
                                            $fields=array(
                                            "user_id"=>$user_id,
                                            "task_id"=>"child_".$recurring_task['task_id']."_1",
                                            "due_date"=> $task['originalStartTime']['date']
                                            );
                                            $post='';
                                            foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                                            $post = rtrim($post,'&');
                                            $curl = curl_init();

                                            curl_setopt_array($curl, array(
                                              CURLOPT_URL => base_url()."api/v1/deletetask",
                                              CURLOPT_RETURNTRANSFER => true,
                                              CURLOPT_ENCODING => "",
                                              CURLOPT_MAXREDIRS => 10,
                                              CURLOPT_TIMEOUT => 30,
                                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                              CURLOPT_CUSTOMREQUEST => "DELETE",
                                              CURLOPT_POSTFIELDS => $post,
                                              CURLOPT_HTTPHEADER => $header,
                                            ));

                                            $response2 = curl_exec($curl);
                                            curl_close($curl);
                                        }
                                        
                                    }else if($task['status'] == 'cancelled'){
                                            $fields=array(
                                            "user_id"=>$user_id,
                                            "task_id"=>$status['task_id'],
                                            );
                                            $post='';
                                            foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
                                            $post = rtrim($post,'&');
                                            $curl = curl_init();

                                            curl_setopt_array($curl, array(
                                              CURLOPT_URL => base_url()."api/v1/deletetask",
                                              CURLOPT_RETURNTRANSFER => true,
                                              CURLOPT_ENCODING => "",
                                              CURLOPT_MAXREDIRS => 10,
                                              CURLOPT_TIMEOUT => 30,
                                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                              CURLOPT_CUSTOMREQUEST => "DELETE",
                                              CURLOPT_POSTFIELDS => $post,
                                              CURLOPT_HTTPHEADER => $header,
                                            ));

                                            $response2 = curl_exec($curl);
                                            curl_close($curl);
                                            
                                            if(isset($exist)){
                                                    $headersn = array(
                                                    'Authorization: key=AAAAJzDOV4w:APA91bEOmbs-LtN5SQx1h45R21xAZ1u6bzg5FcjATdDX8m7pYAHUqYIKu-CXFxcPZAMOQ_V_33nRqMUeEdOVq_OS70eWSEV6U_8Jvd3KyBguBzUy3gd9YccPdh2G8Njy8eAnAI6oCpca',
                                                    'Content-Type: application/json',
                                                    );

                                                    $msg = array('to'=>$exist['browser_token'],
                                                    'notification'=>array('body'=>'','title'=>'Task "'.$status['task_title'].'" has been deleted from Schedullo','click_action'=>base_url().'calendar/weekView','icon'=>base_url().'default/assets/img/logo_new.png'),
                                                    'data'=>array('user_id'=>$status['task_owner_id'],'task_id'=>$status['task_id'],'date'=>strtotime($status['task_scheduled_date']),'task_status_id'=>$status['task_status_id'],'change_type'=>'deleted','swimlane_id'=>$swimlane_id,'frequency_type'=>'one_off'));
                                                    $url = 'https://fcm.googleapis.com/fcm/send';

                                                    $ch = curl_init();

                                                    // Set the url, number of POST vars, POST data
                                                    curl_setopt($ch, CURLOPT_URL, $url);
                                                    curl_setopt($ch, CURLOPT_POST, true);
                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headersn);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));

                                                    $resultn = curl_exec($ch);
                                                    curl_close($ch);
                                            }
                                    }else{
                                            $event_start_date = isset($task['start']['dateTime'])?$task['start']['dateTime']:$task['start']['date'];
                                            $event_end_date = isset($task['end']['dateTime'])?$task['end']['dateTime']:$task['end']['date'];

                                            $task_scheduled_date=Date('Y-m-d',(strtotime($event_start_date)+date("Z")));
                                            $task_time_estimate = (strtotime($event_end_date)-strtotime($event_start_date))/60;
                                            $task_title = $task['summary'];
                                            $task_description = isset($task['description'])?$task['description']:'';
                                            $gmail_task_id = $task['id'];
                                            $fields=array(
                                                'task_title'=> $task_title,
                                                'task_description'=> $task_description,
                                                'task_due_date' => $task_scheduled_date,
                                                'task_scheduled_date'=> $task_scheduled_date,
                                                'task_time_estimate'=>$task_time_estimate,
                                            );

                                            $this->db->where('task_id',$status['task_id']);
                                            $this->db->where('gmail_task_id',$gmail_task_id);
                                            $this->db->update('tasks',$fields);
                                            if(isset($task['recurrence'])){
                                                $frequency_type = 'recurrence';
                                                create_integrated_recurring_task($task['recurrence'],'gmail',$status['task_id'],$task_scheduled_date);
                                            }else{
                                                $frequency_type = 'one_off';
                                            }
                                            if(isset($exist)){
                                                $headersn = array(
                                                'Authorization: key=AAAAJzDOV4w:APA91bEOmbs-LtN5SQx1h45R21xAZ1u6bzg5FcjATdDX8m7pYAHUqYIKu-CXFxcPZAMOQ_V_33nRqMUeEdOVq_OS70eWSEV6U_8Jvd3KyBguBzUy3gd9YccPdh2G8Njy8eAnAI6oCpca',
                                                'Content-Type: application/json',
                                                );

                                                $msg = array('to'=>$exist['browser_token'],
                                                    'notification'=>array('body'=>'','title'=>'Task "'.$task_title.'"  has been updated in Schedullo','click_action'=>base_url().'calendar/weekView','icon'=>base_url().'default/assets/img/logo_new.png'),
                                                    'data'=>array('user_id'=>$status['task_owner_id'],'task_id'=>$status['task_id'],'date'=>strtotime($task_scheduled_date),'task_status_id'=>$status['task_status_id'],'change_type'=>'updated','swimlane_id'=>$swimlane_id,'frequency_type'=>$frequency_type));
                                                $url = 'https://fcm.googleapis.com/fcm/send';

                                                $ch = curl_init();

                                                // Set the url, number of POST vars, POST data
                                                curl_setopt($ch, CURLOPT_URL, $url);
                                                curl_setopt($ch, CURLOPT_POST, true);
                                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headersn);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));

                                                $resultn = curl_exec($ch);
                                                curl_close($ch);
                                            }
                                    }
                                }
                            }
                        }
                    }
        }
    }
        /**
         * This cron job runs eveny minute for renew push notification subscription.
         */
        function gmail_notification_subscription(){
                
                /**
                 * select all user their notification is expired.
                 */
                $this->db->select('*');
                $this->db->from('gmail_integration_details');
                $this->db->where("DATE_SUB(notification_expire_time,INTERVAL 1 DAY) <", Date('Y-m-d H:i:s'));
                $query = $this->db->get();
               
                $result = $query->result_array(); 
                
                /**
                 * gmail push Notification registration
                 */
               if(isset($result)){
                  foreach ($result as $res){
                      
                    /**
                     * generate access token for upadting notification time
                     */  
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => GMAIL_TOKEN_URL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "grant_type=refresh_token&client_id=".GMAIL_CLIENT."&client_secret=".GMAIL_SECRET."&refresh_token=".$res['gmail_refresh_token'],
                        CURLOPT_HTTPHEADER => array(
                          "cache-control: no-cache",
                          "content-type: application/x-www-form-urlencoded",
                        ),
                      ));

                      $response = curl_exec($curl);
                      $array_response = json_decode($response,true);
                      
                    curl_close($curl);
                    
                    /**
                     * stop push notification before new gmail push notification create.
                     */
                    $curl = curl_init();
                    $post = array(
                        "id"=> $res['notification_id'],
                        "resourceId"=> $res['resource_id']
                    );
                    $json_data = json_encode($post);
                     curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://www.googleapis.com/calendar/v3/channels/stop",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => $json_data,
                      CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer ".$array_response['access_token'],
                        "cache-control: no-cache",
                        "content-type: application/json",
                      ),
                    ));

                    $response2 = curl_exec($curl);
                    
                    $new_date =  strtotime(date("Y-m-d H:i:s", strtotime("+5 day")))*1000; 
                    $code = randomCode();
                    
                    $post_data = array(
                            "id"=> $code,
                            "type"=> "web_hook",
                            "address"=> PUSH_NOTIFICATION_URL,
                            "expiration"=>$new_date
                    );
                    $json_post_data = json_encode($post_data);
                    /**
                     * create new push notification 
                     */
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/".$res['gmail_calendar_id']."/events/watch",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS=>$json_post_data,
                      CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer ".$array_response['access_token'],
                        "cache-control: no-cache",
                        "content-type: application/json"
                        ),
                    ));

                    $reg_info = curl_exec($curl);
                    $array_reg_info = json_decode($reg_info,TRUE);

                    $mil = $array_reg_info['expiration'];
                    $seconds = $mil / 1000;
                    $notification_expire_time =  date("Y-m-d H:i:s", $seconds); 
                    $notification_id = $array_reg_info['id'];


                    /**
                     * update push notification details in db
                     */
                    $this->db->set('notification_expire_time',$notification_expire_time);
                    $this->db->set('notification_id',$notification_id);
                    $this->db->set('resource_id',$array_reg_info['resourceId']);
                    $this->db->where('user_id',  $res['user_id']);
                    $this->db->update('gmail_integration_details');

                    curl_close($curl);
                }
               }
        }
        
        
        function set_company_settings_sequence(){
            ini_set('max_execution_time',5000);
            $this->db->select('company_id');
            $this->db->from('company');
            $this->db->where('company_id !=','0');
            $this->db->where('status','Active');
            $this->db->where('is_deleted','0');
            $query = $this->db->get();
            
            if($query->num_rows()>0){
                $result = $query->result_array();
                foreach($result as $id){ 
                    $divisions = get_company_division($id['company_id']);
                    if($divisions){
                        foreach ($divisions as $div){ 
                            // set company department sequence
                            $this->db->query('SET @code=0'); 
                            $this->db->query("UPDATE `company_departments` SET `department_seq` = (SELECT @code:=@code+1 AS code) WHERE `company_id` = ".$id['company_id']." AND `is_deleted` = '0' AND `deivision_id` = ".$div->division_id);
                            
                            //set company division sequence
                            
                            $this->db->query('SET @code=0'); 
                            $this->db->query("UPDATE `company_divisions` SET `seq` = (SELECT @code:=@code+1 AS code) WHERE `company_id` = ".$id['company_id']." AND `is_delete` = '0'");

                        }
                    }
                    // set staff level sequence
                    $this->db->query('SET @code=0'); 
                    $this->db->query("UPDATE `staff_levels` SET `staff_levels_seq` = (SELECT @code:=@code+1 AS code) WHERE `company_id` = ".$id['company_id']." AND `is_deleted` = '0'");
                    
                    // set skills sequence
                    $this->db->query('SET @code=0'); 
                    $this->db->query("UPDATE `skills` SET `skill_seq` = (SELECT @code:=@code+1 AS code) WHERE `company_id` = ".$id['company_id']." AND `is_deleted` = '0'");
                }
                echo "done"; die();
            }
        }
        
}
?>