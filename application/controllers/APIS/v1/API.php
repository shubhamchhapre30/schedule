<?php
require(APPPATH.'/libraries/REST_Controller.php');
require_once APPPATH."libraries/chargify_lib/Chargify.php"; 
class API extends REST_Controller 
{
            /**
              * It default constuctor which is called when calender class object is initialzied. It loads necessary models,library, and config.
              * @returns void
              */ 
            function __construct() {
                 parent :: __construct ();
                 $this->load->model('api_model');
                 $this->load->helper('cookie');
            } 
            /**
              * This method is used hash technique for check request authentication, then it will send login user info.
              * $returns json
              */
            function login_post(){  
                $header = getallheaders();
                $header_status = $this->check_header($header['Content-Type']);
                $sorting = array();
                $email=$this->post('email');
                $password= $this->post('password');
                if(false !== $find = array_search(1, $header_status)){
                    $finalresult=array();
                    $color=array();
                    $newPassword=  base64_decode($password);
                    $result= $this->api_model->check_login1($email,$newPassword);
                    $ch = curl_init();
                    $headers = array(
                                      'Accept:application/json',
                                     );
                    $url = base_url().'OAuth2/token';
                    if(!empty($result)){
                        foreach ($result as $row){
                            $color = get_user_color_name($row['user_id']);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS,'grant_type=password&username='.$row['company_id'].'&password='.$newPassword);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                            curl_setopt($ch, CURLOPT_USERPWD,"539ef299c0696d84f28d40453ad4b190fa573c5b45cff504593fdddfe85f98f4:71d606766edd681b48c7b49700ae2cf7eebf835040226d9623dd355909286a08");
                            curl_setopt($ch, CURLOPT_URL,$url);

                            $result1 = json_decode(curl_exec($ch));
                            //print_r($result1); die();
                            $token1['token']=$result1->access_token;
                            $token1['refresh_token'] = $result1->refresh_token;
                            $finalresult[]=  array_merge($row,$token1);
                        }
                    }
                    curl_close($ch);
                    $sorting[] = ["Manual Sorting"=>"1"];
                    array_push($sorting,["By Priority"=>"2"]);    
                    array_push($sorting,["By Due Date"=>"3"]); 
                    if($result){
                            $this->response(['response'=>'success',
                                             'message'=>'sucessfully login',
                                             'company_list'=>$finalresult,
                                             'colors'=>$color,
                                             'sorting'=>$sorting 
                                             ],REST_Controller::HTTP_OK);
                    }
                    else {
                            $this->response(['response' => 'error',
                                             'message' => 'Invalid username & password'
                                            ], REST_Controller::HTTP_OK);
                    }
                }else{
                        $this->response(['response' => 'error',
                                         'error'=>$header_status['error'],
                                         'message' => $header_status['error_description']
                                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }

            /**
              * This method is used for getting kanban task using user-id & company_id.
              * $return json
              */
             function getKanbanTask_get(){
                 $header=getallheaders();
                 $header_status = $this->check_header($header['Content-Type']);
                 $limit=10;
                 $task=array();
                 $task_array=array();
                 $user_id=$this->get('user_id');
                 $page_no=  $this->get('page_no');
                 $status_id=  $this->get('status_id');
                 if($page_no>0){
                     $offset=($limit*$page_no);
                 }else{
                     $offset=$page_no;
                 }
                 
                 
                 if($this->get('user_color_id')){$user_color_id=$this->get('user_color_id');}else{$user_color_id='0';}
                 $due_task='all';
                 $project_id='all';
                
                 $data['swimlanes'] = get_user_swimlanes($user_id);
                 if(false !== $find = array_search(1, $header_status)){    
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        $date_format=get_companyDefaultdateFormat($company_id);
                        $user_timezone = getUserTimezone($user_id,$company_id);
                        $kanban_task = get_kanban_tasks_data($status_id,$data['swimlanes'],$due_task,$user_id,$project_id,$user_color_id,$company_id,$limit,$offset,$user_timezone);
                        if(!empty($kanban_task)){
                           foreach($kanban_task as $key=>$value){
                               $task['swimlane_name']=$key; 
                               $newvalue=array();
                               foreach($value['status_task'] as $v1){
                                   $v1['task_title']=rawurlencode($v1['task_title']);
                                   $v1['task_description']=rawurlencode($v1['task_description']);
                                   if(!empty($v1['comments'])){
                                       $comments=array();
                                       foreach($v1['comments'] as $c1){
                                         $c1['task_comment']=rawurlencode($c1['task_comment']);
                                         $comments[]=$c1;
                                         }
                                       $v1['comments']=$comments;
                                   }
                                   $newvalue[]=$v1;
                               }
                               $newarray['status_task']=$newvalue;
                               $task1=$newarray;
                               $task_array[]=  array_merge($task,$task1);
                           }
                           $this->response(['response'=>'success',
                                            'message'=>'successfully found',
                                            'date_format'=>$date_format,
                                            'kanban_task' =>  $task_array,
                                            ],REST_Controller::HTTP_OK );
                        }else{
                           $this->response(['response' => 'success',
                                            'message'=>'successfully found',
                                            'date_format'=>$date_format,
                                            'kanban_task' =>  $task_array,
                                            ], REST_Controller::HTTP_OK);
                        }
                    }else{
                       $this->response(['response'=>'error',
                                        'error'=>$access_token['error'],
                                        'message'=>$access_token['error_description'],
                                        ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                 }else{
                    $this->response(['response' => 'error',
                                     'error'=>$header_status['error'],
                                     'message' => $header_status['error_description']
                                    ], REST_Controller::HTTP_BAD_REQUEST);
                 }
             }
             /**
              * This method is used for save new task in db.
              * $returns task_id
              */
             function addTask_post(){
                    
                    $header=getallheaders();
                    $header_status = $this->check_header($header['Content-Type']);
                    $owner_id=  $this->post('user_id');
                    
                    if($this->post('task_title')!=''){$task_title=  $this->post('task_title');}else{$task_title='';}
                    if($this->post('task_description')!=''){$task_description=$this->post('task_description');}else{$task_description='';}
                    if($this->post('task_due_date')!=''){$task_due_date=$this->post('task_due_date');}else{$task_due_date='0000-00-00';}
                    if($this->post('task_scheduled_date')!=''){$task_scheduled_date=$this->post('task_scheduled_date');}else{$task_scheduled_date='0000-00-00';}
                    if($this->post('task_status_id')!=''){$task_status =  $this->post('task_status_id');}else{$task_status='';}
                    if($this->post('task_priority')!=''){$task_priority =  $this->post('task_priority');}else{$task_priority='None';}
                    if($this->post('task_allocated_user_id')!=''){$task_allocated_user_id=  $this->post('task_allocated_user_id');}else{$task_allocated_user_id='';}
                    if($this->post('task_project_id')!=''){$task_project_id=  $this->post('task_project_id');}else{$task_project_id='';}
                    if($this->post('is_watch')!=''){$task_watch_list=  $this->post('is_watch');}else{$task_watch_list='0';}
                    if($this->post('is_personal')!=''){$is_personal=  $this->post('is_personal');}else{$is_personal=0;}
                    if($this->post('task_time_estimate')!=''){$task_time_estimate=  $this->post('task_time_estimate');}else{$task_time_estimate='0';}
                    if($this->post('task_actual_time')!=''){$task_actual_time=  $this->post('task_actual_time');}else{$task_actual_time='0';}
                    if($this->post('outlook_task_id')!=''){$outlook_task_id=  $this->post('outlook_task_id');}else{$outlook_task_id='';}
                    if($this->post('gmail_task_id')!=''){$gmail_task_id=  $this->post('gmail_task_id');}else{$gmail_task_id='';}
                
                    /**
                     * check header request
                     */
                    if(false !== $find = array_search(1, $header_status)){
                        /**
                         * check access token
                         */
                        $access_token = $this->check_authorization_type($header['Authorization']);
                    
                        if(false !== $find = array_search(1, $access_token)){
                            $company_id = $access_token['token_info']['user_id'];
                            $id = $this->api_model->saveTask($owner_id,$company_id,rawurldecode($task_title),rawurldecode($task_description),$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time,$outlook_task_id,$gmail_task_id);
                            if($id){
                                $this->response(['response'=>'success',
                                                 'message'=>'sucessfully save',
                                                 'task_id'=>$id,
                                                 ],REST_Controller::HTTP_CREATED);
                            }else{
                                $this->response(['response' => 'error',
                                                 'message' => 'Task not save'
                                                ], REST_Controller::HTTP_OK);
                            }
                        }else{
                                $this->response(['response'=>'error',
                                                 'error'=>$access_token['error'],
                                                 'message'=>$access_token['error_description'],
                                                ],REST_Controller::HTTP_UNAUTHORIZED);
                        }
                    }else{
                            $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                
             }
             /**
              * This method is used for getting all task for todolist of mobile app using company and user id.
              * $returns json
              */
             
             function getTaskList_get(){
                 $header=getallheaders();
                 $header_status = $this->check_header($header['Content-Type']);
                 $task_capacity=array();
                 $task_array=array();
                 $start_date= $this->get('start_date');
                 $end_date= $this->get('end_date');
                 $user_id=  $this->get('user_id');
                 
                 if($this->get('user_color_id')!=''){$cal_user_color_id=$this->get('user_color_id');}else{$cal_user_color_id='0';}
                 if($this->get('sorting')!=''){$calender_sorting=$this->get('sorting');}else{$calender_sorting='1';}
                 
                 if(false !== $find = array_search(1, $header_status)){
                        $access_token = $this->check_authorization_type($header['Authorization']);
                        //print_r($access_token); die();
                        if(false !== $find = array_search(1, $access_token)){
                            $company_id = $access_token['token_info']['user_id'];
                            $completed=get_company_completed_id($company_id);
                            $user_timezone = getUserTimezone($user_id,$company_id);
                            $start_date= date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $start_date)));
                            $end_date= date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $end_date)));
                            $alltask= get_all_tasks($start_date,$end_date,'all','all',$user_id,'0000-00-00',$cal_user_color_id,$calender_sorting,$completed,$company_id,'all',$user_timezone); 
                            $capacity = getUserCapacity_id($user_id);
                            $date_format=get_companyDefaultdateFormat($company_id);
                            if(!empty($alltask)){
                               foreach ($alltask as $key => $value){
                                 $total_estimate = '0';
                                   foreach($alltask[$key] as $task_time){
                                     if($task_time){
                                       $total_estimate += $task_time['task_time_estimate'];
                                       //$total_spent += $week_task_time['task_time_spent'];
                                     }
                                   }

                                   $total_task_time_estimate_minute_1 = $total_estimate;
                                   $estimate_hours_1 = intval($total_task_time_estimate_minute_1/60);
                                   $estimate_minutes_1 = $total_task_time_estimate_minute_1 - ($estimate_hours_1 * 60);
                                   $e_h_1 = $estimate_hours_1.'h';
                                   $e_m_1 = $estimate_minutes_1.'m';
                                   $total_time= $e_h_1.''.$e_m_1;
                                   $timestamp = strtotime($key);
                                   $day = date('l', $timestamp);
                                   foreach($capacity as $key1=>$value1){
                                       $days=  substr($day, 0,3);
                                       if(strtoupper($days)."_hours"==$key1){
                                         $task_capacity = $value1/60;
                                       }   
                                   }
                                   //for urlencode task title and description 
                                   $newvalue=array();
                                   foreach($value as $v1){
                                       $v1['task_title']=rawurlencode($v1['task_title']);
                                       $v1['task_description']=rawurlencode($v1['task_description']);
                                       if(!empty($v1['comments'])){
                                           $comments=array();
                                           foreach($v1['comments'] as $c1){
                                             $c1['task_comment']=rawurlencode($c1['task_comment']);
                                             $comments[]=$c1;
                                           }
                                           $v1['comments']=$comments;
                                       }
                                       $newvalue[]=$v1;
                                   }
                                   $task_array[]=["date"=>$key,"date_format"=>$date_format,"day"=>$day,"capacity"=>$task_capacity,"total_estimate_time"=>$total_time,"task_list"=>$newvalue];
                               }
                            }

                               $this->response(['response'=>'success',
                                                'message'=>'successfully found',
                                                'task'=>$task_array
                                                ],REST_Controller::HTTP_OK);
                        }else{
                               $this->response(['response'=>'error',
                                                'error'=>$access_token['error'],
                                                'message'=>$access_token['error_description'],
                                                ],REST_Controller::HTTP_OK);
                        }
                 }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                 }
             }
             
             /**
              * This method is used for update task.
              * return task-id
              */
              function updateTask_put(){
                 $header=getallheaders();
                 $header_status = $this->check_header($header['Content-Type']);
                 
                 $owner_id=  $this->put('user_id');
                 $task_id = $this->put('task_id');
                 $task_title=  $this->put('task_title');
                 $task_description=$this->put('task_description');
                 $task_due_date=$this->put('task_due_date');
                 $task_scheduled_date=$this->put('task_scheduled_date');
                 $task_status=  $this->put('task_status_id');
                 $task_priority=  $this->put('task_priority');
                 $task_allocated_user_id=  $this->put('task_allocated_user_id');
                 $task_project_id=  $this->put('task_project_id');
                 $task_watch_list=  $this->put('is_watch');
                 $is_personal=  $this->put('is_personal');
                 $task_time_estimate=  $this->put('task_time_estimate');
                 $task_actual_time=  $this->put('task_actual_time');
                 
                /**
                 * check request header
                 */
                if(false !== $find = array_search(1, $header_status)){
                    /**
                     * there is checked token validation 
                     */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        $id = $this->api_model->updateTaskInfo($task_id,$owner_id,$company_id,rawurldecode($task_title),rawurldecode($task_description),$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time);
                        if($id){
                            $this->response(['response'=>'success',
                                             'message'=>'sucessfully update',
                                             'task_id'=>$id,
                                            ],REST_Controller::HTTP_OK);
                        }else{
                            $this->response(['response' => 'error',
                                             'message' => 'Task not update'
                                             ], REST_Controller::HTTP_OK);
                        } 
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                            ],REST_Controller::HTTP_OK);
                    }
                }else{
                        $this->response(['response' => 'error',
                                         'error'=>$header_status['error'],
                                         'message' => $header_status['error_description']
                                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                
             }
             /**
              * This method is used for adding new comment. 
              * returns json
              */
              function addComment_post(){
                    
                    $task_id = $this->post('task_id');
                    $task_comment = $this->post('task_comment');
                    $task_due_date = $this->post('due_date');
                    $user_id =  $this->post('user_id');
                    $header=getallheaders();
                    $header_status = $this->check_header($header['Content-Type']);
                    
                    
                    /**
                     * check header request
                     */
                   if(false !== $find = array_search(1, $header_status)){
                    /**
                     * check access token
                     */
                      $access_token = $this->check_authorization_type($header['Authorization']);
                       // print_r($access_token); die();
                        if(false !== $find = array_search(1, $access_token)){
                            $company_id = $access_token['token_info']['user_id'];
                            $user_timezone = getUserTimezone($user_id,$company_id);
                            $chk_exist = chk_task_exists($task_id);
                            if($chk_exist=='0'){
                                $main_id = explode("_", $task_id);
                                $master_task_id = $main_id[1];
                                $task = get_task_info($master_task_id,$company_id);
                                $data = array(
                                              'task_company_id' => $task['task_company_id'],
                                              'master_task_id'=>$master_task_id,
                                              'task_title' => $task['task_title'],
                                              'task_description' => $task['task_description'],
                                              'is_personal' => $task['is_personal'],
                                              'task_priority' => $task['task_priority'],
                                              'task_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
                                              'task_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
                                              'task_orig_scheduled_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
                                              'task_orig_due_date' => date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date))),
                                              'task_time_spent' => $task['task_time_spent'],
                                              'task_time_estimate' => $task['task_time_estimate'],
                                              'task_owner_id' =>$task['task_owner_id'],
                                              'task_allocated_user_id' => $task['task_allocated_user_id'],
                                              'task_status_id' => $task['task_status_id'],
                                              'task_project_id' => $task['task_project_id'],
                                              'subsection_id' =>$task['subsection_id'],   
                                              'task_added_date' => date('Y-m-d H:i:s')
                                            );
                                $this->db->insert('tasks',$data);
                                $task_id = $this->db->insert_id();
                                
                                $steps = get_task_steps($master_task_id);
                                if($steps){
                                    $i = 1;
                                    foreach($steps as $step){
                                        $step_data = array(
                                                        'task_id' => $task_id,
                                                        'step_title' => $step['step_title'],
                                                        'step_added_by' => $step['step_added_by'],
                                                        'is_completed' => $step['is_completed'],
                                                        'step_sequence' => $i,
                                                        'step_added_date' => date('Y-m-d H:i:s')
                                                    );
                                        $this->db->insert('task_steps',$step_data);
                                        $i++;
                                    }
                                }

                                /**
                                  * insert task history in db.
                                  */
                                $history_data = array(
                                                    'histrory_title' => 'Task created.',
                                                    'history_added_by' => $task['task_owner_id'],
                                                    'task_id' => $task_id,
                                                    'date_added' => date('Y-m-d H:i:s')
                                                );
                                $this->db->insert('task_history',$history_data); 
                                $comments_task= get_task_comments_info($master_task_id);    
                                if($comments_task){
                                    foreach($comments_task as $com){
                                        $data = array(
                                                    'task_comment' => $com['task_comment'],
                                                    'task_id' => $task_id,
                                                    'project_id' => $com['project_id'],
                                                    'comment_addeby' => $com['comment_addeby'],
                                                    'comment_added_date' => date('Y-m-d H:i:s')
                                                    );
                                        $this->db->insert('task_and_project_comments',$data);
                                    }
                                }
                                $comment[]= $this->api_model->saveComment($task_id,$user_id,rawurldecode($task_comment),$user_timezone);
                            }else{
                                $comment[]= $this->api_model->saveComment($task_id,$user_id,rawurldecode($task_comment),$user_timezone);
                            }
                            if($comment){
                                $this->response(["response"=>'success',
                                                 "message"=>'successfully add comment',
                                                 "task_id"=>$task_id,
                                                 "comments"=>$comment
                                                 ],REST_Controller::HTTP_CREATED);
                            }else {
                                $this->response(['response' => 'error',
                                                 'message' => 'comment not save'
                                                 ], REST_Controller::HTTP_NOT_MODIFIED);
                            }
                        }else {
                                $this->response(['response'=>'error',
                                                'error'=>$access_token['error'],
                                                'message'=>$access_token['error_description'],
                                                ],REST_Controller::HTTP_OK);
                        }
                    }else{
                            $this->response(['response' => 'error',
                                             'error'=>$header_status['error'],
                                             'message' => $header_status['error_description']
                                            ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } 

              /**
               * This API is returned overall details of logined in user.
               */  
              function getUserdetail_get(){
                    
                    
                    $user_id = $this->get('user');
                    $header=getallheaders();
                    $header_status = $this->check_header($header['Content-Type']);
                    /**
                     * check header request
                     */
                    if(false !== $find = array_search(1, $header_status)){
                    /**
                     * check access token
                     */
                        $access_token = $this->check_authorization_type($header['Authorization']);
                        if(false !== $find = array_search(1, $access_token)){
                        	$user=array();
                                $company_id = $access_token['token_info']['user_id'];
                        	$this->db->select('first_name,last_name,user_id');
                        	$this->db->from('users');
                        	$this->db->where('user_id',$user_id);
                        	$query=$this->db->get();
                        	$data=$query->result_array(); 
                                $actual_time_status = getActualTimeStatus($company_id);
                                $projects = get_user_project_list('open',$user_id);
                                $status = get_task_status($company_id,'Active');
                                $users = get_users_list($user_id);
                                $date_format=get_companyDefaultdateFormat($company_id);
                                $priority = array();
                                $priority[] = ["task_priority"=>"None"];
                                array_push($priority,["task_priority"=>"Low"]);    
                                array_push($priority,["task_priority"=>"Medium"]); 
                                array_push($priority,["task_priority"=>"High"]); 
                                if(!empty($users)){
                                $user= array_merge($users,$data);
                                sort($user);
                                }else{
                                  $user= $data;
                                }
                                 $this->response([
                                                  "response"=>'success',
                                                  "message"=>'successfully found',
                                                  "projects"=>$projects,
                                                  "status"=>$status,
                                                  "user"=>$user,
                                                  "priority"=>$priority,
                                                  "date_format"=>$date_format,
                                                  "is_completed_time"=>$actual_time_status
                                                 ],REST_Controller::HTTP_OK);
                        }else{
                               $this->response(['response'=>'error',
                                                'error'=>$access_token['error'],
                                                'message'=>$access_token['error_description'],
                                                ],REST_Controller::HTTP_UNAUTHORIZED);
                        }
                    }else{
                              $this->response(['response' => 'error',
                                               'error'=>$header_status['error'],
                                               'message' => $header_status['error_description']
                                              ], REST_Controller::HTTP_BAD_REQUEST);  
                    }
                  
              }  
             
              /**
               * This API is returned open task list i.e (Not completed task)
               */
              
              function openTaskList_get(){
                         $task_capacity=array();
                         $task_array=array();   
                         $header=getallheaders();
                         $header_status = $this->check_header($header['Content-Type']);
                         $start_date= $this->get('start_date');
                         $end_date= $this->get('end_date');
                         
                         $user_id=  $this->get('user_id');
                         
                         
                         if($this->get('user_color_id')!=''){$cal_user_color_id=$this->get('user_color_id');}else{$cal_user_color_id='0';}
                         if($this->get('sorting')!=''){$calender_sorting=$this->get('sorting');}else{$calender_sorting='1';}
                         /**
                          * check header request
                          */
                         if(false !== $find = array_search(1, $header_status)){ 
                            $access_token = $this->check_authorization_type($header['Authorization']);
                            /**
                             * check access token
                             */
                            if(false !== $find = array_search(1, $access_token)){
                                if($user_id !='' && $start_date !='' && $end_date!=''){
                                    $company_id = $access_token['token_info']['user_id'];
                                    $completed=get_company_completed_id($company_id);
                                    $user_timezone = getUserTimezone($user_id,$company_id);
                                    $start_date= date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $start_date)));
                                    $end_date= date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $end_date)));
                                    $openTask= get_all_tasks($start_date,$end_date,'all','all',$user_id,'0000-00-00',$cal_user_color_id,$calender_sorting,$completed,$company_id,'open',$user_timezone);     
                                    $capacity = getUserCapacity_id($user_id);
                                    $date_format=get_companyDefaultdateFormat($company_id);
                                    if(!empty($openTask)){
                                       foreach ($openTask as $key => $value){
                                          $timestamp = strtotime($key);
                                          $day = date('l', $timestamp);
                                          foreach($capacity as $key1=>$value1){
                                            $days=  substr($day, 0,3);
                                            if(strtoupper($days)."_hours"==$key1){
                                               $task_capacity = $value1/60;
                                            }   
                                          }
                                          //encoding  
                                          $newvalue=array();
                                          foreach($value as $v1){
                                           $v1['task_title']=rawurlencode($v1['task_title']);
                                           $v1['task_description']=rawurlencode($v1['task_description']);
                                           $comments=array();
                                           if(!empty($v1['comments'])){
                                               foreach($v1['comments'] as $c1){
                                                   $c1['task_comment']=rawurlencode($c1['task_comment']);
                                                   $comments[]=$c1;
                                               }
                                           }
                                           $v1['comments']=$comments;
                                           $newvalue[]=$v1;
                                           }
                                           $task_array[]=["date"=>$key,"date_format"=>$date_format,"day"=>$day,"capacity"=>$task_capacity,"task_list"=>$newvalue];
                                       }  
                                       $this->response(['response'=>'success',
                                                            'message'=>'successfully found',
                                                            'open_task'=>$task_array
                                                            ],REST_Controller::HTTP_OK);
                                    }else{
                                           $this->response(['response'=>'success',
                                                            'message'=>'successfully found',
                                                            'open_task'=>$task_array
                                                            ],REST_Controller::HTTP_OK);
                                    }
                                }else{
                                    $this->response(['response'=>'error',
                                                     'message'=>'insufficient parameters.',
                                                     ],REST_Controller::HTTP_OK);
                                }
                            }else{
                                   $this->response(['response'=>'error',
                                                    'error'=>$access_token['error'],
                                                    'message'=>$access_token['error_description'],
                                                   ],REST_Controller::HTTP_UNAUTHORIZED);
                            }
                         }else{
                                $this->response(['response' => 'error',
                                                'error'=>$header_status['error'],
                                                'message' => $header_status['error_description']
                                               ], REST_Controller::HTTP_BAD_REQUEST);
                         }
                    }
                 /**
                  * This API is returned overdue task i.e (Not completed task in past)
                  */
              function overDueTaskList_get(){
                         $task_capacity=array();
                         $task_array=array();   
                         $header=getallheaders();
                         $header_status = $this->check_header($header['Content-Type']);
                         $start_date= $this->get('start_date');
                         $end_date= $this->get('end_date');
                        
                         $user_id=  $this->get('user_id');
                         if($this->get('user_color_id')!=''){$cal_user_color_id=$this->get('user_color_id');}else{$cal_user_color_id='0';}
                         if($this->get('sorting')!=''){$calender_sorting=$this->get('sorting');}else{$calender_sorting='1';}
                        /**
                         * Condition for checking header request.
                         */
                         if(false !== $find = array_search(1, $header_status)){
                            /*
                             * Check access token
                             */
                                $access_token = $this->check_authorization_type($header['Authorization']);
                                if(false !== $find = array_search(1, $access_token)){
                                    if($user_id !='' && $start_date !='' && $end_date !=''){
                                        $company_id = $access_token['token_info']['user_id'];
                                        $completed=get_company_completed_id($company_id);
                                        $user_timezone = getUserTimezone($user_id,$company_id);
                                        $start_date= date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $start_date)));
                                        $end_date= date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $end_date)));
                                        $OverDueTask= get_all_tasks($start_date,$end_date,'all','all',$user_id,'0000-00-00',$cal_user_color_id,$calender_sorting,$completed,$company_id,'overdue',$user_timezone);     
                                        $capacity = getUserCapacity_id($user_id);
                                        $date_format=get_companyDefaultdateFormat($company_id);
                                        if(!empty($OverDueTask)){
                                            foreach ($OverDueTask as $key => $value){
                                                $timestamp = strtotime($key);
                                                $day = date('l', $timestamp);
                                                foreach($capacity as $key1=>$value1){
                                                    $days=  substr($day, 0,3);
                                                    if(strtoupper($days)."_hours"==$key1){
                                                        $task_capacity = $value1/60;
                                                    }   
                                                }
                                                //encoding  
                                                $newvalue=array();
                                                foreach($value as $v1){
                                                    $v1['task_title']=rawurlencode($v1['task_title']);
                                                    $v1['task_description']=rawurlencode($v1['task_description']);
                                                    if(!empty($v1['comments'])){
                                                        $comments=array();
                                                        foreach($v1['comments'] as $c1){
                                                            $c1['task_comment']=rawurlencode($c1['task_comment']);
                                                            $comments[]=$c1;
                                                        }
                                                        $v1['comments']=$comments;
                                                    }
                                                    $newvalue[]=$v1;
                                                }
                                                $task_array[]=["date"=>$key,"date_format"=>$date_format,"day"=>$day,"capacity"=>$task_capacity,"task_list"=>$newvalue];
                                            } 
                                            $this->response(['response'=>'success',
                                                             'message'=>'successfully found',
                                                             'overDue_task'=>$task_array
                                                             ],REST_Controller::HTTP_OK);
                                        }else{
                                            $this->response(['response'=>'success',
                                                             'message'=>'successfully found',
                                                             'overDue_task'=>$task_array
                                                             ],REST_Controller::HTTP_OK);
                                        }
                                    }else{
                                            $this->response(['response'=>'error',
                                                             'message'=>'insufficient parameters.',
                                                             ],REST_Controller::HTTP_OK);
                                    }
                                }else {
                                      $this->response(['response'=>'error',
                                                    'error'=>$access_token['error'],
                                                    'message'=>$access_token['error_description'],
                                                   ],REST_Controller::HTTP_UNAUTHORIZED);
                                }
                        }else{
                               $this->response(['response' => 'error',
                                                'error'=>$header_status['error'],
                                                'message' => $header_status['error_description']
                                               ], REST_Controller::HTTP_BAD_REQUEST);
                        }
                 }
               /**
                * This API is used for getting user default status list using company id.
                */  
              function getUserStatus_get(){
                  
                        $user_id=  $this->get('user');
                        $header=getallheaders();
                        $header_status = $this->check_header($header['Content-Type']);
                      /**
                        * check header request
                        */

                       if(false !== $find = array_search(1, $header_status)){
                            /*
                             * check access token
                             */
                                $access_token = $this->check_authorization_type($header['Authorization']);
                                if(false !== $find = array_search(1, $access_token)){
                                            $company_id = $access_token['token_info']['user_id'];
                                            $status = get_task_status($company_id,'Active');
                                            $this->response([
                                                             'response'=>'success',
                                                             'message'=>'successfully found',
                                                             'status'=>$status
                                                            ],REST_Controller::HTTP_OK);
                                            
                                }else {
                                      $this->response(['response'=>'error',
                                                       'error'=>$access_token['error'],
                                                       'message'=>$access_token['error_description'],
                                                      ],REST_Controller::HTTP_UNAUTHORIZED);
                               }
                        }else{
                            $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                        }


                        
                 }
                 
              /**
               * This API is used for deleting task.
               */   
              function deleteTask_delete(){
                    $user_id = $this->delete('user_id');
                    $task_id = $this->delete('task_id');
                    if($this->delete('due_date')!='') 
                        $due_date=$this->delete('due_date');
                    else
                        $due_date = '0000-00-00';
                   // echo $user_id; die();
                    $header=getallheaders();
                    $header_status = $this->check_header($header['Content-Type']);
                      
                       /**
                         * check header request
                         */
                      if(false !== $find = array_search(1, $header_status)){
                            /*
                             * check access token
                             */
                               $access_token = $this->check_authorization_type($header['Authorization']);
                                if(false !== $find = array_search(1, $access_token)){
                                            $company_id = $access_token['token_info']['user_id'];
                                            $status = $this->api_model->deleteTask($task_id,$company_id,$user_id,$due_date);
                                            if($status){
                                                $this->response([
                                                             'response'=>'success',
                                                             'message'=>'successfully deleted',
                                                            ],REST_Controller::HTTP_OK);
                                            
                                            }else{
                                                $this->response([
                                                             'response'=>'error',
                                                             'message'=>'Not deleted',
                                                            ],REST_Controller::HTTP_OK);
                                            }
                                   
                                   }else {
                                      $this->response(['response'=>'error',
                                                       'error'=>$access_token['error'],
                                                       'message'=>$access_token['error_description'],
                                                      ],REST_Controller::HTTP_UNAUTHORIZED);
                               }
                        }else{
                            $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                        }
              }   
                 /**
                  * This API is used for getting project team .
                  */
              function getProjectTeam_get(){
                  
                        $user_id=  $this->get('user_id');
                        $project_id = $this->get('project_id');
                        $header=getallheaders();
                        $header_status = $this->check_header($header['Content-Type']);
                        
                       /**
                         * check header request
                         */
                       if(false !== $find = array_search(1, $header_status)){
                            /*
                             * check access token 
                             */
                               $access_token = $this->check_authorization_type($header['Authorization']);
                                if(false !== $find = array_search(1, $access_token)){
                                            if($project_id !='0'){
                                                    $user = get_project_user_list($project_id);
                                            } else {
                                                    $user=array();
                                                    $this->db->select('first_name,last_name,user_id');
                                                    $this->db->from('users');
                                                    $this->db->where('user_id',$user_id);
                                                    $query=$this->db->get();
                                                    $data=$query->result_array();
                                                    $users = get_users_list($user_id);
                                                    if(!empty($users)){
                                                        $user= array_merge($users,$data);
                                                        sort($user);
                                                    }else{
                                                        $user= $data;
                                                    }
                                            }
                                            $this->response([
                                                             'response'=>'success',
                                                             'message'=>'successfully found',
                                                             'users'=>$user
                                                            ],REST_Controller::HTTP_OK);
                                            
                                }else {
                                      $this->response(['response'=>'error',
                                                       'error'=>$access_token['error'],
                                                       'message'=>$access_token['error_description'],
                                                      ],REST_Controller::HTTP_UNAUTHORIZED);
                               }
                        }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                        }
              }
              /**
               * This method is used for checking authorization and return response.
               * @param type $authorization_code
               * @return string
               */
              function check_authorization_type($authorization_code){
              //   echo $authorization_code; die();
                 if (preg_match("/\bBearer\b/i", $authorization_code)){ 
                     $token = str_replace('Bearer ','',$authorization_code);
                     $ch = curl_init();
                     $headers = array(
                      'Accept:application/json',
		     );
		     $url = base_url().'OAuth2/resource';
				
		     curl_setopt($ch, CURLOPT_POST, 1);
                     curl_setopt($ch, CURLOPT_POSTFIELDS,"access_token=".$token);
		     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		     curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		     curl_setopt($ch, CURLOPT_URL,$url);
		     $result = curl_exec($ch);
                     curl_close($ch);
                     $result1 = json_decode($result,TRUE);
                     return $result1;
                 }else{
                     $result['error']='invalid authorization type';
                     $result['error_description'] = 'Authorization type is invalid.';
                     return $result;
                 }
                 
             }
             /**
              * Check api header request.
              * @param type $content_type
              * @return int
              */
             function check_header($content_type){
                    if ($content_type !== null && $content_type == 'application/x-www-form-urlencoded') {
                        $result['sucess']='1';
                        return $result;
                    }else{
                        
                        $result['error']='invalid_request';
                        $result['error_description'] = "The content type must be 'application/x-www-form-urlencoded'";
                        return $result;
                    }
              }
             
             /**
              * This api is used for getting userlist using company ID.
              * $returns json
              */
             
             function getUserList_get(){
                 
                 $header=getallheaders();
                 $header_status = $this->check_header($header['Content-Type']);
                        
                 /**
                   * check header request
                   */
                 if(false !== $find = array_search(1, $header_status)){
                    /*
                     * check access token 
                     */
                     $access_token = $this->check_authorization_type($header['Authorization']);
                     if(false !== $find = array_search(1, $access_token)){
                            $company_id = $access_token['token_info']['user_id'];
                            $companyusers = $this->api_model->get_user_list($company_id); 
                               if($companyusers){
                                   $this->response(['response'=>'success',
                                                    'message'=>'successfully found',
                                                    'users'=>$companyusers
                                                    ],REST_Controller::HTTP_OK);
                               }else {
                                   $this->response(['response'=>'success',
                                                   'message'=>'not found',
                                                   ],REST_Controller::HTTP_NOT_FOUND);
                               }
                            
                     }else {
                                      $this->response(['response'=>'error',
                                                       'error'=>$access_token['error'],
                                                       'message'=>$access_token['error_description'],
                                                      ],REST_Controller::HTTP_UNAUTHORIZED);
                      }
                 }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                  }
             }
               
             /**
              * This api is used for getting userinformation using user & company ID.
              * $returns json
              */
             
             function getUserInfo_get(){
                $user_id = $this->uri->segment(4);
                $header=getallheaders();
                $header_status = $this->check_header($header['Content-Type']);
                        
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        
                        $data = array();
                        if($user_id != ''){ 
                           $company_id = $access_token['token_info']['user_id'];
                           $data = $this->api_model->get_user_details($user_id,$company_id);
                           $data->division = $this->api_model->get_division_list($user_id);
                           $data->department = $this->api_model->get_department_list($user_id);
                           $data->skills = $this->api_model->get_skills_list($user_id);
                           $data->count = get_user_count_under_manager($user_id);
                           if(!empty($data)){
                                  $this->response([
                                                     'response'=>'success',
                                                     'message'=>'successfully found',
                                                     'user'=>$data
                                                  ],REST_Controller::HTTP_OK);
                            }else{
                                $this->response([
                                                'response'=>'success',
                                                'message'=>'not found',
                                                ],REST_Controller::HTTP_NOT_FOUND);
                            }
                        }
                        else{
                             $this->response(['response'=>'error',
                                              'message'=>'insufficient parameters.',
                                              ],REST_Controller::HTTP_BAD_REQUEST);
                         }
                    }else {
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }
   
            }
             /**
              * delete user using user & company ID.
              * $returns json
              */
             
             function deleteUser_delete(){
                $user_id = $this->uri->segment(4);
                $header=getallheaders();
                $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        if($user_id != ''){
                            $id = $user_id;
                            $company_id = $access_token['token_info']['user_id'];
                            $query=$this->db->get_where('users',array('user_id'=>$id,"company_id"=>$company_id));
                            $use=$query->row();
                            
                            if($use->is_administrator == '1'){
                                $this->response([
                                                 'response'=>'error',
                                                 'message'=>'cann\'t deleted admin',
                                                ],REST_Controller::HTTP_OK);
                            }else{
                                   $this->db->where("user_id",$id);
                                   $this->db->update("users",array("is_deleted"=>1));
                            

                                    $query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
                                    $company=$query1->row();

                                    $query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
                                    $company_plan=$query_plan->row();

                                    if($company_plan){
                                            $component_id = $company_plan->chargify_component_id;
                                    } else {
                                            $component_id = 0;
                                    }

                                    $test = TRUE;
                                    $Qty = new ChargifyQuantityBasedComponent(NULL, $test);

                                    if($company->chargify_subscriptions_ID != '')
                                    {
                                            try{	
                                                    $new_qty=count_user_by_company($use->company_id);
                                                    $Qty->allocated_quantity = $new_qty;
                                                    $Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
                                            }catch (ChargifyValidationException $cve) { 

                                            }
                                    }
                                    $this->response([
                                                       'response'=>'success',
                                                       'message'=>'successfully deleted',
                                                    ],REST_Controller::HTTP_OK);
                            }
                        }else{
                                $this->response(['response'=>'error',
                                                 'message'=>'insufficient parameters.',
                                                ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                    }else {
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }
   
            }
             /**
              * This api is used for updating userinformation using user ID.
              * $returns json
              */
             
            function updateUserDetail_put(){ 
                $user_id = $this->put('user_id');
                
                $header=getallheaders();
                
                $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        $is_user_exist = check_user_avaibility_by_id($user_id);
                        if($is_user_exist !='0'){
                            $user_info = $this->api_model->get_user_details($user_id,$company_id);
                            if($this->put('first_name')){$first_name = $this->put('first_name');}else{$first_name = $user_info->first_name;}
                            if($this->put('last_name')){$last_name = $this->put('last_name');}else{$last_name = $user_info->last_name;}
                            if($this->put('timezone')){$timezone = $this->put('timezone');}else{$timezone = $user_info->user_time_zone;}
                            $data = array(
                                    'first_name' => $first_name,
                                    'last_name' => $last_name,
                                    'user_time_zone' => $timezone,
                            );
                            $this->db->where('user_id',$user_id);
                            $this->db->where('company_id',$company_id);
                            $this->db->update('users',$data);

                            $this->response(['response'=>'success',
                                             'message'=>'successfully updated',
                                             'user'=>$user_id
                                             ],REST_Controller::HTTP_OK);
                        }
                        else{
                             $this->response(['response'=>'error',
                                              'message'=>'user doesn\'t exist.',
                                             ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }     
   
            }
            
             /**
              * This api is used for adding user using company ID.
              * $returns json
              */
            function addUser_post()
            { 
                $first_name = $this->post('firstname');
                $last_name = $this->post('lastname');
                $email = $this->post('email');
                $header=getallheaders();
                $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        if($first_name != '' && $last_name != '' && $email !=''){
                            $no_of_company_by_user = check_user_avaibility_by_email($email);

                            if($no_of_company_by_user < 1){
                                    $randomcode = randomCode();
                                    $password = md5($randomcode);
                            }else{
                                    $password = get_user_password($email);
                            }
                            $code = randomCode();
                            if($this->post('timezone')){$user_timezone = $this->post('timezone');}else{$user_timezone='';}
                            
                            $data = array(
                                    'first_name' => $first_name,
                                    'last_name' => $last_name,
                                    'email' => $email,
                                    'user_time_zone' => $user_timezone,
                                    'user_status' => 'active',
                                    'company_id' => $company_id,
                                    'password' => $password,
                                    'email_verification_code' => $code,
                                    'signup_date' => date('Y-m-d H:i:s'),
                                    'signup_IP' => $_SERVER['REMOTE_ADDR'],
                                    'user_default_page' =>'weekly_calendar'
                            );
                            $this->db->insert('users',$data);
                            $user_id = $this->db->insert_id();

                            $swimlane_data = array(
                                    'user_id' => $user_id,
                                    'swimlanes_name' => 'default',
                                    'swimlanes_desc' => 'default',
                                    'seq' => '1',
                                    'date_added' => date('Y-m-d H:i:s')
                            );
                            $this->db->insert('swimlanes',$swimlane_data);
                            $default_swimlanes_id = $this->db->insert_id(); 
                            /**
                             * insert data in last_remember_search
                             */
                            $last_remember_data = array(
                                    'user_id' => $user_id,
                                    'sidbar_collapsed'=>'0',
                                    'kanban_project_id' => 'all',
                                    'calender_project_id' => 'all',
                                    'task_status_id' => 'all',
                                    'due_task' => 'all',
                                    'kanban_team_user_id' => $user_id,
                                    'calender_team_user_id' =>$user_id,
                                    'show_cal_view' => '1',
                                    'calender_sorting' => '1',
                                    'last_calender_view' => '1',
                                    'user_color_id'=>'0'
                            );
                            $this->db->insert('last_remember_search',$last_remember_data);
                            
                            
                            $calender_data = array(
                                    'user_id' => $user_id,
                                    'MON_hours' => '480',
                                    'TUE_hours' => '480',
                                    'WED_hours' => '480',
                                    'THU_hours' => '480',
                                    'FRI_hours' => '480',
                                    'SAT_hours' => '0',
                                    'SUN_hours' => '0',
                                    'MON_closed' => '1',
                                    'TUE_closed' => '1',
                                    'WED_closed' => '1',
                                    'THU_closed' => '1',
                                    'FRI_closed' => '1',
                                    'SAT_closed' => '0',
                                    'SUN_closed' => '0'
                            );
                            $this->db->insert('default_calendar_setting',$calender_data);

                            $colors = get_colors();
                            if($colors){
                                    $i = 1;
                                    foreach($colors as $col){
                                            $color_data = array(
                                                    'color_id' => $col->color_id,
                                                    'user_id' => $user_id,
                                                    'color_name' => $col->color_name,
                                                    'name' => $col->color_name,
                                                    'color_code' => $col->color_code,
                                                    'outside_color_code' => $col->outside_color_code,
                                                    'seq' => $i,
                                                    'status' => 'Active',
                                                    'date_added' => date('Y-m-d H:i:s')
                                            );
                                            $this->db->insert('user_colors',$color_data);
                                            $i++;
                                    }
                            }

                            $query=$this->db->get_where('users',array('user_id'=>$user_id));
			    $use=$query->row();
				
			    $query1=$this->db->get_where('users',array('company_id'=>$use->company_id,'is_owner'=>'1'));
				
			    $company=$query1->row();
				
			    $query_plan = $this->db->select("p.chargify_component_id")->from("plans p")->join("company c","c.plan_id = p.plan_id")->where("c.company_id",$use->company_id)->where("c.is_deleted","0")->get();
			    $company_plan=$query_plan->row();
				
			    if($company_plan){
				$component_id = $company_plan->chargify_component_id;
			    } else {
				$component_id = 0;
			    }
				
			    $test = TRUE;
			    $Qty = new ChargifyQuantityBasedComponent(NULL, $test);
				
			    if($company->chargify_subscriptions_ID != '')
			    {
				try{	
				    $new_qty=count_user_by_company($use->company_id);
				    $Qty->allocated_quantity = $new_qty;
			
				    $Qt = $Qty->update($company->chargify_subscriptions_ID,$component_id);
			
				}catch (ChargifyValidationException $cve) { 
						 
				}catch(ChargifyConnectionException $d){
                                                    
                                }
		            }
                            
                            $json_path = base_url().'default/json/By_default_task.json';
                            $file = file_get_contents($json_path);
                            $default_task = json_decode($file);
                            $task_status = get_taskStatus($company_id,'Active');   
                            $admin_color = get_colors_admin($user_id);
                            $default_steps = array(
                                            "1" => "Tick the box to complete the step",
                                            "2" => "Step 2",
                                            "3" => "Step 3"
                            );
                            $i = 1;
                            foreach ($default_task as $task){

                                foreach ($task_status as $status){
                                        if($task->task_status == $status->task_status_name){
                                            $task->task_status_id =$status->task_status_id;
                                        }
                                }

                                $monday= date("Y-m-d",strtotime('monday this week'));
                                $tuesday= date("Y-m-d",strtotime($monday . "+1 days"));
                                $wednesday = date("Y-m-d",strtotime($monday . "+2 days"));
                                if($task->task_due_date == 'Monday'){
                                    $task->task_due_date = $monday;
                                    $task->task_schedule_date = $monday;
                                }elseif($task->task_due_date == 'Tuesday'){
                                    $task->task_due_date = $tuesday;
                                    $task->task_schedule_date = $tuesday;
                                }else{
                                    $task->task_due_date = $wednesday;
                                    $task->task_schedule_date = $wednesday;
                                }
                                $data = array(
                                            'task_company_id' => $company_id,
                                            'master_task_id' => '0',
                                            'task_title' => $task->task_title,
                                            'task_description' => $task->task_description,
                                            'task_priority' => $task->task_priority,
                                            'task_due_date' => $task->task_due_date,
                                            'task_scheduled_date' => $task->task_schedule_date,
                                            'task_orig_scheduled_date' => '0000-00-00',
                                            'task_orig_due_date' => '0000-00-00',
                                            'task_owner_id' => $user_id,
                                            'task_allocated_user_id' => $user_id,
                                            'task_status_id' => $task->task_status_id,
                                            'subsection_id' => '0',
                                            'section_id' => '0',
                                            'task_project_id' => '0',
                                            'task_time_estimate'=>$task->task_estimate_time,
                                            'task_time_spent' => $task->task_spent_time,
                                            'task_added_date' => date('Y-m-d H:i:s'),
                                            );

                                $this->db->insert('tasks',$data);
                                $task_id = $this->db->insert_id();
                                foreach($admin_color as $color){
                                    if($task->task_color == $color->name){
                                        $task->task_color = $color->user_color_id;
                                     }
                                 }

                                $data1 = array(
                                                "user_id"=>$user_id,
                                                "task_id"=>$task_id,
                                                "swimlane_id"=>$default_swimlanes_id,
                                                "color_id"=>$task->task_color,
                                                "calender_order"=> $i,
                                                "kanban_order"=>$i,
                                                "task_ex_pos"=>'1'

                                );
                                $this->db->insert('user_task_swimlanes',$data1); 

                                if($task->steps == 'TRUE'){
                                    foreach($default_steps as $key => $value){
                                        $step_data = array(
                                                        'task_id' => $task_id,
                                                        'step_title' => $value,
                                                        'step_added_by' => $user_id,
                                                        'is_completed' => '0',
                                                        'step_sequence' => $key,
                                                        'step_added_date' => date('Y-m-d H:i:s')
                                                        );
                                        $this->db->insert('task_steps',$step_data);
                                    }
                                }
                                if($task->comment == 'TRUE'){
                                    $data2 = array(
                                                'task_comment' => "First comment",
                                                'task_id' => $task_id,
                                                'project_id' => '0',
                                                'comment_addeby' => $user_id,
                                                'comment_added_date' => date('Y-m-d H:i:s')
                                                );

                                    $this->db->insert('task_and_project_comments',$data2);
                                }


                                $i++;
                            }
                            
                            //hard delete for soft deleted user again register
                            $query = $this->db->query("Delete From ".$this->db->dbprefix('users')." where email= '".$this->put('email')."' and is_deleted = 1 ");

                            if($no_of_company_by_user < 1){
                            //email
                                    $email_template=$this->db->query("select * from ".$this->db->dbprefix('email_template')." where task='Add New User By Admin'");
                                    $email_temp=$email_template->row();	
                                    $email_address_from=$email_temp->from_address;
                                    $email_address_reply=$email_temp->reply_address;

                                    $email_subject=$email_temp->subject;				
                                    $email_message=$email_temp->message;

                                    $data_pass = base64_encode($user_id."1@1".$code);

                                    $activation_link = "<a href='".base_url()."home/activation/".$data_pass."' target='_blank'>Activation link</a>";

                                    $user_name = $first_name.' '.$last_name;

                                    $email_to = $email;
                                    
                                    
                                    $email_message=str_replace('{break}','<br/>',$email_message);
                                    $email_message=str_replace('{user_name}',$user_name,$email_message);
                                    $email_message=str_replace('{email}',$email_to,$email_message);
                                    $email_message=str_replace('{password}',$randomcode,$email_message);
                                    $email_message=str_replace('{activation_link}',$activation_link,$email_message);
                                    
                                    
                                    $company_name = getCompanyName($company_id);
                                    $str=$email_message;
                                    $sandgrid_id=$email_temp->sandgrid_id;
                                    $sendgriddata = array('subject'=>'Add New User By Admin',
                                    'data'=>array('user_name'=>$user_name,'company_name'=>$company_name,'activation_link'=>$activation_link,'email'=>$email));
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

                                    //echo $str;die;
                                    //email_send($email_address_from,$email_address_reply,$email_to,$email_subject,$str);
                            }else{

                                            $email_template = $this->db->query("select * from " . $this->db->dbprefix('email_template') . " where task='Add User To New Company'");
                                            $email_temp = $email_template->row();

                                            $email_address_from = $email_temp->from_address;
                                            $email_address_reply = $email_temp->reply_address;

                                            $email_subject = $email_temp->subject;
                                            $email_message = $email_temp->message;

                                            

                                            $user_name = $first_name.' '.$last_name;
                                            $company_name = getCompanyName($company_id);
                                            $email_to = $email;

                                            $email_message = str_replace('{break}', '<br/>', $email_message);
                                            $email_message = str_replace('{user_name}', $user_name, $email_message);
                                            $email_message = str_replace('{company_name}', $company_name, $email_message);
                                            //$email_message = str_replace('{sore_name}', $store_name, $email_message);
                                            $data_pass = base64_encode($user_id."1@1".$code);
                                            $activation_link = "<a href='".base_url()."home/activation_email/".$data_pass."' target='_blank'>Activation link</a>";

                                            $str = $email_message;
                                            $sandgrid_id=$email_temp->sandgrid_id;
                                            $sendgriddata = array('subject'=>'Add User To New Company',
                                                'data'=>array('user_name'=>$user_name,'company_name'=>$company_name,'activation_link'=>$activation_link,'email'=>$email));
                                            if($sandgrid_id)
                                            {
                                                $str = json_encode($sendgriddata);
                                            }
                                            //echo $str;die;

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
                                    /** custom_helper email function **/

                                    //email_send($email_address_from, $email_address_reply, $email_to, $email_subject, $str);
                                    }
                                    
                                    
                                    $this->response([
                                                   'response'=>'success',
                                                   'message'=>'successfully created new user',
                                                   'user_id'=>$user_id
                                                ],REST_Controller::HTTP_CREATED);
                        }
                        else{
                             $this->response(['response'=>'error',
                                              'message'=>'Insufficient parameters.',
                                             ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }     
            }
            
             /**
              * This api is used for getting customer list using company ID.
              * $returns json
              */
             
             function getCustomerList_get(){
                 $customerlist = array();
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                 if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                           $company_id = $access_token['token_info']['user_id'];
                        
                           $customerlist = get_Customer_List($company_id); 
                           if($customerlist){
                               $this->response(['response'=>'success',
                                                'message'=>'successfully found',
                                                'customers'=>$customerlist
                                                ],REST_Controller::HTTP_OK);
                          }else {
                               $this->response(['response'=>'success',
                                               'message'=>'not found',
                                               ],REST_Controller::HTTP_NOT_FOUND);
                           }
                        
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }     
             }
             /**
              * This api is used for getting specific customer details using company & customer ID.
              * $returns json
              */
             
             function getCustomerDetail_get(){
                 $customer = array();
                 $customer_id = $this->uri->segment(4);
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                 if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                           $company_id = $access_token['token_info']['user_id'];
                           $customer = getCustomerDetail($customer_id,$company_id); 
                           if($customer){
                               $this->response(['response'=>'success',
                                                'message'=>'successfully found',
                                                'customer'=>$customer
                                                ],REST_Controller::HTTP_OK);
                          }else {
                               $this->response(['response'=>'success',
                                                'message'=>'not found',
                                               ],REST_Controller::HTTP_NOT_FOUND);
                           }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }         
             }
             /**
              * This api is used for deleting customer form company.
              * $returns json
              */
             
             function deleteCustomer_delete(){
                 $customer_id = $this->uri->segment(4);
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        if($customer_id!=''){
                           $data=array(
                                       "is_deleted"=>"1"
                           );
                           $this->db->where('customer_company_id',$company_id);
                           $this->db->where('customer_id',$customer_id);
                           $this->db->update('customers',$data);
                           
                           $this->response(['response'=>'success',
                                            'message'=>'successfully deleted',
                                            'customer_id'=>$customer_id
                                            ],REST_Controller::HTTP_OK);
                        }
                        else
                        {
                            $this->response(['response'=>'error',
                                             'message'=>'Insufficient parameters.',
                                             ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }             
             }
             /**
              * This api is used adding new customer in company.
              * $returns json
              */
             
             function addCustomer_post(){
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                           $company_id = $access_token['token_info']['user_id'];
                           if($this->post("external_code")){$external_code = $this->post("external_code");}else{$external_code='';}
                           if($this->post('contact_name')){
                               $contact_name = $this->post('contact_name');
                               $contact = explode(' ', $contact_name);
                               $first_name = $contact[0];
                               $last_name = $contact[1];
                           }else{
                               $first_name='';
                               $last_name='';
                           }
                           if($this->post('email')){ $email = $this->post('email');}else{$email='';}
                           if($this->post('phone')){ $phone = $this->post('phone');}else{$phone='';}
                           $total_customer= countCustomer($company_id);
                           $customer_id="CU-".($total_customer+1);
                           $data=array(
                                       "customer_id"=>$customer_id,
                                       "external_id"=>$external_code,
                                       "customer_name"=>$this->post("customer_name"),
                                       "customer_company_id"=>$company_id,
                                       "email"=>  $email,
                                       "phone"=>  $phone,
                                       "first_name"=>$first_name,
                                       "last_name"=>$last_name,
                                       "status"=>"active",
                                       "is_deleted"=>'0',
                                       "create_date"=>date('Y-m-d H:i:s'),
                            );
                            $this->db->insert('customers',$data);
                            
                            $this->response(['response'=>'success',
                                             'message'=>'successfully created new customer',
                                             'customer_id'=>$customer_id
                                             ],REST_Controller::HTTP_CREATED);
                        
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                          $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }              
             }
             /**
              * This api is used for updating customer details.
              * $returns json
              */
             
             function updateCustomer_put(){
                 $customer_id = $this->put('customer_id');
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        if($customer_id !=''){
                            $customer = getCustomerDetail($customer_id,$company_id); 
                            if($this->put('customer_name')){$customer_name = $this->put('customer_name');}else{$customer_name = $customer['customer_name'];}
                            if($this->put("external_code")){$external_code = $this->put("external_code");}else{$external_code=$customer['external_id'];}
                            if($this->put('contact_name')){
                               $contact_name = $this->put('contact_name');
                               $contact = explode(' ', $contact_name);
                               $first_name = $contact[0];
                               $last_name = $contact[1];
                            }else{
                               $first_name=$customer['first_name'];
                               $last_name=$customer['last_name'];
                            }
                            if($this->put('email')){ $email = $this->put('email');}else{$email=$customer['email'];}
                            if($this->put('phone')){ $phone = $this->put('phone');}else{$phone=$customer['phone'];}
                            $data=array(
                                      "first_name" => $first_name,
                                      "last_name"=>$last_name,
                                      "email"=>$email,
                                      "phone"=>$phone,
                                      "external_id"=>$external_code,
                                      "customer_name"=>$customer_name,
                            );
                           $this->db->where('customer_company_id',$company_id);
                           $this->db->where('customer_id',$customer_id);
                           $this->db->update('customers',$data);
                           
                                $this->response(['response'=>'success',
                                                 'message'=>'successfully upadated',
                                                 'customer_id'=>$customer_id
                                                ],REST_Controller::HTTP_OK);
                           
                        }
                        else
                        {
                            $this->response(['response'=>'error',
                                             'message'=>'Insufficient parameters.',
                                             ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }          
             }
             
             /**
              * get project list using company ID.
              * return json
              */
             
             function getProjectList_get(){
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                            $company_id = $access_token['token_info']['user_id'];
                            $projectlist = $this->api_model->get_project_list($company_id); 
                            if($projectlist){
                               $this->response(['response'=>'success',
                                                'message'=>'successfully found',
                                                'customers'=>$projectlist
                                                ],REST_Controller::HTTP_OK);
                            }else {
                               $this->response(['response'=>'success',
                                               'message'=>'not found',
                                               ],REST_Controller::HTTP_NOT_FOUND);
                            }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }          
             }
             /**
              * get specific project details using company  & project ID.
              * $return json
              */
             function getprojectinfo_get(){
                 $project_id = $this->uri->segment(4);
                 
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        if($project_id !=''){
                            $project_info = $this->api_model->get_project_info($company_id,$project_id);
                            if($project_info){
                                $this->response(['response'=>'success',
                                                 'message'=>'successfully found.',
                                                 'project_info'=>$project_info,
                                            ],REST_Controller::HTTP_OK);
                            
                            }else{
                                $this->response(['response'=>'success',
                                             'message'=>'not found.',
                                            ],REST_Controller::HTTP_OK);
                            }
                        }else{
                            $this->response(['response'=>'error',
                                             'message'=>'Insufficient parameters.',
                                             ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                        
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }          
             }
             /**
              * create new project using comapny & user ID.
              * return json
              */
             function addProject_post(){
                 $project_title = $this->post('project_name');
                 $user_id = $this->post('project_owner');
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        if($project_title !='' && $user_id !=''){
                                    if($this->post('start_date')){$start_date = date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $this->post('start_date')))); }else{ $start_date = date('Y-m-d'); }
                                    if($this->post('end_date')){$end_date = date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $this->post('end_date')))); }else{ $end_date = date('Y-m-d'); }
                                    //create new project
                                    $data = array(
                                        "project_title"=>$project_title,
                                        "project_start_date" => $start_date,
                                        "project_end_date"=> $end_date,
                                        "project_status"=>'open',
                                        "project_added_by"=>$user_id,
                                        "company_id"=>$company_id,
                                        "is_deleted"=>'0'
                                    );
                                    $this->db->insert('project',$data);
                                    $project_id = $this->db->insert_id();
                                    
                                    //create project team
                                    
                                    $data = array(
                                                'user_id' => $user_id,
                                                'project_id' => $project_id,
                                                'is_project_owner' => '1',
                                                'status' => 'Active',
                                                'project_user_added_date' => date('Y-m-d H:i:s'),
                                                'is_deleted' => '0'
                                             );
                                    $this->db->insert('project_users',$data);
                                    
                                    //create default project section
                                    
                                    $data_section = array(
                                            'section_name' =>'Section 1',
                                            'main_section' =>'0',    //main section
                                            'project_id' => $project_id,
                                            'added_by' => $user_id,
                                            'added_date' => date('Y-m-d')
                                    );
                                    $this->db->insert('project_section',$data_section);
                                    $section_id = $this->db->insert_id();
                                    
                                    // update section order
                                    
                                    $section_order = array(

                                    'section_order'=>get_section_order_by_project($project_id,$section_id,'0') ,

                                    );

                                    $this->db->where('section_id',$section_id);
                                    $this->db->update('project_section',$section_order);
                                    
                                    
                                    $this->response(['response'=>'success',
                                                     'message'=>'successfully created project.',
                                                     'project_id'=>$project_id,
                                                    ],REST_Controller::HTTP_CREATED);
                        }else{
                            $this->response(['response'=>'error',
                                             'message'=>'Insufficient parameters.',
                                             ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                        
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                          $this->response(['response' => 'error',
                                         'error'=>$header_status['error'],
                                         'message' => $header_status['error_description']
                                        ], REST_Controller::HTTP_BAD_REQUEST);
                }      
             }
             /**
              * update project info
              * return json
              */
             function updateProject_put(){
                 $project_id = $this->put('project_id');
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        $is_project_exist = $this->api_model->check_project_existance($company_id,$project_id);
                        if($is_project_exist !='0' ){
                            $project_info = $this->api_model->get_project_info($company_id,$project_id);
                            if($this->put('project_name')){$project_title = $this->put('project_name');}else{$project_title=$project_info[0]->project_title;}
                            if($this->put('start_date')){$start_date = date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $this->put('start_date'))));}else{$start_date = $project_info[0]->project_start_date;}
                            if($this->put('end_date')){$end_date = date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $this->put('end_date'))));}else{$end_date = $project_info[0]->project_end_date;}
                            
                            $data = array(
                                    "project_title"=>$project_title,
                                    "project_start_date"=>$start_date,
                                    "project_end_date"=>$end_date
                            );
                            $this->db->where('project_id',$project_id);
                            $this->db->update('project',$data);
                            
                                $this->response(['response'=>'success',
                                                 'message'=>'successfully updated',
                                                 'project_id'=>$project_id
                                                ],REST_Controller::HTTP_OK);
                            
                        }else{
                            $this->response(['response'=>'error',
                                             'message'=>'project doesn\'t exist.',
                                             ],REST_Controller::HTTP_NOT_FOUND);
                        }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }      
             }
             /**
              * delete project using company & project ID.
              * return json
              */
             
             function deleteProject_delete(){
                 $project_id = $this->uri->segment(4);
                 $header=getallheaders();
                
                 $header_status = $this->check_header($header['Content-Type']);
                 /**
                   * check header request
                   */
                if(false !== $find = array_search(1, $header_status)){
                   /**
                    * check access token 
                    */
                    $access_token = $this->check_authorization_type($header['Authorization']);
                    if(false !== $find = array_search(1, $access_token)){
                        $company_id = $access_token['token_info']['user_id'];
                        $is_project_exist = $this->api_model->check_project_existance($company_id,$project_id);
                        if($is_project_exist !='0'){
                            $this->db->set('is_deleted','1');
                            $this->db->where('project_id',$project_id);
                            $this->db->where('company_id',$company_id);
                            $this->db->update('project');
                            if($this->db->affected_rows()>0){
                                $this->response(['response'=>'success',
                                                 'message'=>'successfully deleted',
                                                 'project_id'=>$project_id
                                                ],REST_Controller::HTTP_OK);
                            }else{
                               $this->response(['response'=>'error',
                                                'message'=>'project not deleted',
                                                'project_id'=>$project_id
                                               ],REST_Controller::HTTP_OK);
                            }
                        }else{
                            $this->response(['response'=>'error',
                                             'message'=>'project doesn\'t exist.',
                                             ],REST_Controller::HTTP_NOT_FOUND);
                        }
                    }else{
                            $this->response(['response'=>'error',
                                             'error'=>$access_token['error'],
                                             'message'=>$access_token['error_description'],
                                             ],REST_Controller::HTTP_UNAUTHORIZED);
                    }
                }else{
                           $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                }      
             }
             /**
              * this Api will create recurring task
              */
             
             function addRecurringTask_post(){
                    $header=getallheaders();
                    $header_status = $this->check_header($header['Content-Type']);
                    $owner_id=  $this->post('user_id');
                    if($this->post('task_id')!=''){$task_id=  $this->post('task_id');}else{$task_id='';}
                    if($this->post('task_title')!=''){$task_title=  $this->post('task_title');}else{$task_title='';}
                    if($this->post('task_description')!=''){$task_description=$this->post('task_description');}else{$task_description='';}
                    if($this->post('task_due_date')!=''){$task_due_date=$this->post('task_due_date');}else{$task_due_date='0000-00-00';}
                    if($this->post('task_scheduled_date')!=''){$task_scheduled_date=$this->post('task_scheduled_date');}else{$task_scheduled_date='0000-00-00';}
                    if($this->post('task_status_id')!=''){$task_status_id =  $this->post('task_status_id');}else{$task_status_id='';}
                    if($this->post('task_priority')!=''){$task_priority =  $this->post('task_priority');}else{$task_priority='None';}
                    if($this->post('task_allocated_user_id')!=''){$task_allocated_user_id=  $this->post('task_allocated_user_id');}else{$task_allocated_user_id='';}
                    if($this->post('task_project_id')!=''){$task_project_id=  $this->post('task_project_id');}else{$task_project_id='';}
                    if($this->post('is_watch')!=''){$task_watch_list=  $this->post('is_watch');}else{$task_watch_list='0';}
                    if($this->post('is_personal')!=''){$is_personal=  $this->post('is_personal');}else{$is_personal=0;}
                    if($this->post('task_time_estimate')!=''){$task_time_estimate=  $this->post('task_time_estimate');}else{$task_time_estimate='0';}
                    if($this->post('task_time_spent')!=''){$task_time_spent=  $this->post('task_time_spent');}else{$task_time_spent='0';}
                    if($this->post('outlook_task_id')!=''){$outlook_task_id=  $this->post('outlook_task_id');}else{$outlook_task_id='';}
                    if($this->post('frequency_type')!=''){$frequency_type=  $this->post('frequency_type');}else{$frequency_type='';}
                    if($this->post('recurrence_type')!=''){$recurrence_type=  $this->post('recurrence_type');}else{$recurrence_type='';}
                    if($this->post('Daily_every_day')!=''){$Daily_every_day=  $this->post('Daily_every_day');}else{$Daily_every_day='';}
                    if($this->post('Daily_every_weekday')!=''){$Daily_every_weekday=  $this->post('Daily_every_weekday');}else{$Daily_every_weekday='';}
                    if($this->post('Weekly_every_week_no')!=''){$Weekly_every_week_no=  $this->post('Weekly_every_week_no');}else{$Weekly_every_week_no='';}
                    if($this->post('Weekly_week_day')!=''){$Weekly_week_day=  $this->post('Weekly_week_day');}else{$Weekly_week_day='';}
                    if($this->post('monthly_radios')!=''){$monthly_radios=  $this->post('monthly_radios');}else{$monthly_radios='';}
                    if($this->post('Monthly_op1_1')!=''){$Monthly_op1_1=  $this->post('Monthly_op1_1');}else{$Monthly_op1_1='';}
                    if($this->post('Monthly_op1_2')!=''){$Monthly_op1_2=  $this->post('Monthly_op1_2');}else{$Monthly_op1_2='';}
                    if($this->post('Monthly_op2_1')!=''){$Monthly_op2_1=  $this->post('Monthly_op2_1');}else{$Monthly_op2_1='';}
                    if($this->post('Monthly_op2_2')!=''){$Monthly_op2_2=  $this->post('Monthly_op2_2');}else{$Monthly_op2_2='';}
                    if($this->post('Monthly_op2_3')!=''){$Monthly_op2_3=  $this->post('Monthly_op2_3');}else{$Monthly_op2_3='';}
                    if($this->post('Monthly_op3_1')!=''){$Monthly_op3_1=  $this->post('Monthly_op3_1');}else{$Monthly_op3_1='';}
                    if($this->post('Monthly_op3_2')!=''){$Monthly_op3_2=  $this->post('Monthly_op3_2');}else{$Monthly_op3_2='';}
                    if($this->post('yearly_radios')!=''){$yearly_radios=  $this->post('yearly_radios');}else{$yearly_radios='';}
                    if($this->post('Yearly_op1')!=''){$Yearly_op1=  $this->post('Yearly_op1');}else{$Yearly_op1='';}
                    if($this->post('Yearly_op2_1')!=''){$Yearly_op2_1=  $this->post('Yearly_op2_1');}else{$Yearly_op2_1='';}
                    if($this->post('Yearly_op2_2')!=''){$Yearly_op2_2=  $this->post('Yearly_op2_2');}else{$Yearly_op2_2='';}
                    if($this->post('Yearly_op3_1')!=''){$Yearly_op3_1=  $this->post('Yearly_op3_1');}else{$Yearly_op3_1='';}
                    if($this->post('Yearly_op3_2')!=''){$Yearly_op3_2=  $this->post('Yearly_op3_2');}else{$Yearly_op3_2='';}
                    if($this->post('Yearly_op3_3')!=''){$Yearly_op3_3=  $this->post('Yearly_op3_3');}else{$Yearly_op3_3='';}
                    if($this->post('Yearly_op4_1')!=''){$Yearly_op4_1=  $this->post('Yearly_op4_1');}else{$Yearly_op4_1='';}
                    if($this->post('Yearly_op4_2')!=''){$Yearly_op4_2=  $this->post('Yearly_op4_2');}else{$Yearly_op4_2='';}
                    if($this->post('start_on_date')!=''){$start_on_date=  $this->post('start_on_date');}else{$start_on_date='';}
                    if($this->post('no_end_date')!=''){$no_end_date=  $this->post('no_end_date');}else{$no_end_date='';}
                    if($this->post('end_by_date')!=''){$end_by_date=  $this->post('end_by_date');}else{$end_by_date='';}
                    if($this->post('end_after_recurrence')!=''){$end_after_recurrence=  $this->post('end_after_recurrence');}else{$end_after_recurrence='';}
                    $id=$task_id;
                                        
                    /**
                     * check header request
                     */
                    if(false !== $find = array_search(1, $header_status)){
                        /**
                         * check access token
                         */
                        $access_token = $this->check_authorization_type($header['Authorization']);
                    
                        if(false !== $find = array_search(1, $access_token)){
                            $company_id = $access_token['token_info']['user_id'];
                            if($task_project_id){
                                    $subsection_id=get_project_subsection_id($task_project_id);
                            }else
                            {
                             $task_project_id='0';   
                             $subsection_id='0';   
                            }
                            $insert_array=array(
                            'task_title'=>rawurldecode($task_title),
                            'task_description'=>rawurldecode($task_description),
                            'task_due_date'=>$task_due_date,
                            'task_scheduled_date'=>$task_scheduled_date,
                            'task_status_id'=>$task_status_id,
                            'task_priority'=>$task_priority,
                            'task_allocated_user_id'=>$task_allocated_user_id,
                            'task_project_id'=>$task_project_id,
                            'is_personal'=>$is_personal,
                            'task_time_estimate'=>$task_time_estimate,
                            'task_time_spent'=>$task_time_spent,
                            'frequency_type'=>$frequency_type,
                            'recurrence_type'=>$recurrence_type,
                            'Daily_every_day'=>$Daily_every_day,
                            'Daily_every_weekday'=>$Daily_every_weekday,
                            'Weekly_every_week_no'=>$Weekly_every_week_no,
                            'Weekly_week_day'=>$Weekly_week_day,
                            'monthly_radios'=>$monthly_radios,
                            'Monthly_op1_1'=>$Monthly_op1_1,
                            'Monthly_op1_2'=>$Monthly_op1_2,
                            'Monthly_op2_1'=>$Monthly_op2_1,
                            'Monthly_op2_2'=>$Monthly_op2_2,
                            'Monthly_op2_3'=>$Monthly_op2_3,
                            'Monthly_op3_1'=>$Monthly_op3_1,
                            'Monthly_op3_2'=>$Monthly_op3_2,
                            'yearly_radios'=>$yearly_radios,
                            'Yearly_op1'=>$Yearly_op1,
                            'Yearly_op2_1'=>$Yearly_op2_1,
                            'Yearly_op2_2'=>$Yearly_op2_2,
                            'Yearly_op3_1'=>$Yearly_op3_1,
                            'Yearly_op3_2'=>$Yearly_op3_2,
                            'Yearly_op3_3'=>$Yearly_op3_3,
                            'Yearly_op4_1'=>$Yearly_op4_1,
                            'Yearly_op4_2'=>$Yearly_op4_2,
                            'start_on_date'=>$start_on_date,
                            'no_end_date'=>$no_end_date,
                            'end_after_recurrence'=>$end_after_recurrence,
                            'end_by_date'=>$end_by_date,
                            'subsection_id' =>$subsection_id 
                            );
							
							$chk_exist = chk_task_exists($task_id);
                                                       
							if($chk_exist=='0'){
								$insert_array['task_company_id']=$company_id;
								$insert_array['task_owner_id']=$owner_id;
                                                                $insert_array['task_added_date']=date('Y-m-d H:i:s');
                                                                $insert_array['outlook_task_id']=$outlook_task_id;
                                                                
								$this->db->insert('tasks',$insert_array);
                                                                
								$task_id = $this->db->insert_id();
								$id=$task_id;
								/**
								 * insert task history in db.
								 */
								$history_data = array(
									'histrory_title' => 'Task created.',
									'history_added_by' => $owner_id,
									'task_id' => $task_id,
									'date_added' => date('Y-m-d H:i:s')
								);
								$this->db->insert('task_history',$history_data);
								$chk_exist = chk_swim_exist($task_id,$task_allocated_user_id);
								if($chk_exist == '0'){
									$user_swimlane = array(
										'user_id' => $task_allocated_user_id,
										'task_id' => $task_id,
										'swimlane_id' => get_default_swimlane($task_allocated_user_id),
										'kanban_order' => 1,
										//'calender_order' => get_user_last_calnder_order($task_allocated_user_id,$old_task_detail['task_scheduled_date']) + 1
									);
									$this->db->insert('user_task_swimlanes',$user_swimlane);
								}
							}
							else
							{
								$where=array('task_id'=>$task_id,'task_owner_id'=>$owner_id);
								$this->db->where($where);
								$this->db->update('tasks',$insert_array);
								
							}
                                                        
                            //$id = $this->api_model->saveTask($owner_id,$company_id,rawurldecode($task_title),rawurldecode($task_description),$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time,$outlook_task_id);
                            if($id){
                                $this->response(['response'=>'success',
                                                 'message'=>'sucessfully save',
                                                 'task_id'=>$id,
                                                 ],REST_Controller::HTTP_CREATED);
                            }else{
                                $this->response(['response' => 'error',
                                                 'message' => 'Task not save'
                                                ], REST_Controller::HTTP_OK);
                            }
                        }else{
                                $this->response(['response'=>'error',
                                                 'error'=>$access_token['error'],
                                                 'message'=>$access_token['error_description'],
                                                ],REST_Controller::HTTP_UNAUTHORIZED);
                        }
                    }else{
                            $this->response(['response' => 'error',
                                            'error'=>$header_status['error'],
                                            'message' => $header_status['error_description']
                                           ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                
             }
             
             
}
?>


