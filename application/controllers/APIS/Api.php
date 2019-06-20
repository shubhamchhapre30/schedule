<?php
require(APPPATH.'/libraries/REST_Controller.php');
 
class Api extends REST_Controller 
{
            /**
              * It default constuctor which is called when calender class object is initialzied. It loads necessary models,library, and config.
              * @returns void
              */ 
            function __construct() {
                 parent :: __construct ();
                 $this->load->model('home_model');
                 $this->load->helper('cookie');
                 $this->load->model('task_model');
            } 
            /**
              * This method is used hash technique for check request authentication, then it will send login user info.
              * $returns json
              */
            function login_post(){ 
                $sorting = array();
                $email=$this->post('email');
                $password= $this->post('password');
               
                /**
                 * create salt key using private key
                 */
                $content = json_encode(array(
                                "email"=>$email,
                                "password"=>$password
                                ));
                $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                $finalresult=array();
                $color=array();
                $newPassword=  base64_decode($password);
                $contenthash=getallheaders();
                
               /**
                *  It will  get salt value from header and check, if match then it return response otherwise return error message.
                */
                if($hash==$contenthash["salt"]){
                        
                                     $result= $this->home_model->check_login1($email,$newPassword);
                                     if(!empty($result)){
                                        foreach ($result as $row){
                                            $color = get_user_color_name($row['user_id']);
                                            $token = randomCode();
                                            $insert_id= saveToken($token,$row['user_id']);
                                            $token1['token']=$token;
                                            $finalresult[]=  array_merge($row,$token1);
                                        }
                                     }
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
                                         $this->response([
                                                 'response' => 'error',
                                                 'message' => 'Invalid username & password'
                                                 ], REST_Controller::HTTP_OK);
                                     }
                        
                        
                }else{
                    $this->response([
                                    "response"=>"error",
                                    "message" =>"unauthorized request."
                                    ],REST_Controller::HTTP_NOT_FOUND);
                }

            }

            /**
             * This API is used for generate new token when existing token is expired.
             */

            function token_generate_post(){
                    $email=$this->post('email');
                    $password=$this->post('password');
                    $user_id=$this->post('user_id');
                    $company_id =$this->post('company_id');
                    $content = json_encode(array(
                                "email"=>$email,
                                "password"=>$password,
                                "user_id"=>$user_id,
                                "company_id"=>$company_id
                                ));
                    $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                    $newPassword=  base64_decode($password);
                    $contenthash=getallheaders();
                    /**
                     * It will  get salt value from header and check, if match then it return response otherwise return error message.
                     */
                    if($hash==$contenthash["salt"]){

                                         $result= $this->home_model->check_login1($email,$newPassword);
                                         $token = randomCode();
                                         $insert_id= saveToken($token,$user_id);
                                         if($result){
                                             $this->response(['response'=>'success',
                                                             'message'=>'Token generated sucessfully.',
                                                             'token'=>$token,
                                                             ],REST_Controller::HTTP_OK);
                                         }
                                         else {
                                             $this->response([
                                                     'response' => 'error',
                                                     'message' => 'Invalid username & password'
                                                     ], REST_Controller::HTTP_OK);
                                         }


                    }else{
                        $this->response([
                                        "response"=>"error",
                                        "message" =>"Unauthorized request."
                                        ],REST_Controller::HTTP_NOT_FOUND);
                    }

            }
            /**
              * This method is used for getting kanban task using user-id & company_id.
              * $return json
              */
             function getKanbanTask_post(){
                 $limit=10;
                 $task=array();
                 $task_array=array();
                 $token=  $this->post('token');
                 $user_id=$this->post('user_id');
                 $company_id=  $this->post('company_id');
                 $page_no=  $this->post('page_no');
                 $status_id=  $this->post('status_id');
                 if($page_no>0){
                     $offset=($limit*$page_no);
                 }else{
                     $offset=$page_no;
                 }
                 $date_format=get_companyDefaultdateFormat($company_id);
                 $minute=checkToken($token,$user_id); 
                 $user_timezone = getUserTimezone($user_id,$company_id);
                // $timezone_offset = getOffset($user_timezone);
                 if($this->post('user_color_id')){$user_color_id=$this->post('user_color_id');}else{$user_color_id='0';}
                 $due_task='all';
                 $project_id='all';
                // $data['task_status'] = get_task_status($company_id,'Active');
                 $data['swimlanes'] = get_user_swimlanes($user_id);
                 /**
                  * create salt key using privatekey
                  */
                 
                 $content=  json_encode(array(
                                "user_id"=>$user_id,
                                "company_id"=>$company_id,
                                "user_color_id"=>$user_color_id,
                                "token"=>$token,
                                "page_no"=>$page_no,
                                "status_id"=>$status_id
                                ));
                 $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                 $contenthash=getallheaders();
                /**
                 *  It will  get salt value from header and check, if match then it return response otherwise return error message.
                 */
                 if($hash==$contenthash['salt']){
                    /**
                     * there is checked token deatils 
                     */
                           if($minute<20){
                                   
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
                                             }
                                             if(!empty($kanban_task)){
                                                     $this->response([
                                                                     'response'=>'success',
                                                                     'message'=>'successfully found',
                                                                     'date_format'=>'d/m/Y',
                                                                     'kanban_task' =>  $task_array,
                                                                    ],REST_Controller::HTTP_OK );
                                             }else{
                                                 $this->response([
                                                                  'response' => 'success',
                                                                  'message'=>'successfully found',
                                                                  'date_format'=>'d/m/Y',
                                                                  'kanban_task' =>  $task_array,
                                                                  ], REST_Controller::HTTP_OK);
                                                 }
                                  
                           }else{
                               deleteToken($token,$user_id);
                               $this->response([
                                                          'response' => 'error',
                                                          'message' => 'Token is expired',
                                                          'token_status'=>'Expired'
                                                          ], REST_Controller::HTTP_OK);
                           }
                    }else{
                    $this->response([
                                    "response"=>"error",
                                    "message" =>"unauthorized request."
                                    ],REST_Controller::HTTP_NOT_FOUND);
                }
             }
             /**
              * This method is used for save new task in db.
              * $returns task_id
              */
             function addNewTask_post(){ 
                    $token=  $this->post('token');
                    $company_id=  $this->post('company_id');
                    $owner_id=  $this->post('user_id');
                    $minute=checkToken($token,$owner_id);
                    if($this->post('task_title')!=''){$task_title=  $this->post('task_title');}else{$task_title='';}
                    if($this->post('task_description')!=''){$task_description=$this->post('task_description');}else{$task_description='';}
                    if($this->post('task_due_date')!=''){$task_due_date=$this->post('task_due_date');}else{$task_due_date='0000-00-00';}
                    if($this->post('task_scheduled_date')!=''){$task_scheduled_date=$this->post('task_scheduled_date');}else{$task_scheduled_date='0000-00-00';}
                    if($this->post('task_status_id')!=''){$task_status=  $this->post('task_status_id');}else{$task_status='';}
                    if($this->post('task_priority')!=''){$task_priority=  $this->post('task_priority');}else{$task_priority='';}
                    if($this->post('task_allocated_user_id')!=''){$task_allocated_user_id=  $this->post('task_allocated_user_id');}else{$task_allocated_user_id='';}
                    if($this->post('task_project_id')!=''){$task_project_id=  $this->post('task_project_id');}else{$task_project_id='';}
                    if($this->post('is_watch')!=''){$task_watch_list=  $this->post('is_watch');}else{$task_watch_list='0';}
                    if($this->post('is_personal')!=''){$is_personal=  $this->post('is_personal');}else{$is_personal=0;}
                    if($this->post('task_time_estimate')!=''){$task_time_estimate=  $this->post('task_time_estimate');}else{$task_time_estimate='0';}
                    if($this->post('task_actual_time')!=''){$task_actual_time=  $this->post('task_actual_time');}else{$task_actual_time='0';}
                
                /**
                 * create salt key using private key 
                 */
                    $content=json_encode(array(
                                "user_id"=>$owner_id,
				"company_id"=>$company_id,
				"task_title"=>$task_title,
				"task_description"=>$task_description,
				"task_due_date"=>$task_due_date,
				"task_scheduled_date"=>$task_scheduled_date,
				"task_status_id"=>$task_status,
				"task_priority"=>$task_priority,
				"task_allocated_user_id"=>$task_allocated_user_id,
				"task_project_id"=>$task_project_id,
				"is_watch"=>$task_watch_list,
				"is_personal"=>$is_personal,
				"task_time_estimate"=>$task_time_estimate,
				"task_actual_time"=>$task_actual_time,
                                "token"=>$token
                               ));
                    $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                    $contenthash=getallheaders();
                    /**
                     * It will  get salt value from header and check, if match then it return response otherwise return error message.
                     */
                    if($hash==$contenthash['salt']){
                        /**
                         * There is checked token validation. 
                         */
                                if($minute<20){ 
                                    $id = $this->task_model->saveTask($owner_id,$company_id,rawurldecode($task_title),rawurldecode($task_description),$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time);
                                    $pricing_module_status = check_pricing_module_status($company_id);
                                    if($pricing_module_status == '1'){
                                        $this->update_pricing($id,$company_id);
                                    }
                                    if($id){
                                        $this->response([
                                                        'response'=>'success',
                                                        'message'=>'sucessfully save',
                                                        'task_id'=>$id,
                                                        ],REST_Controller::HTTP_OK);
                                    }else{
                                        $this->response([
                                                        'response' => 'error',
                                                        'message' => 'Task not save'
                                                        ], REST_Controller::HTTP_OK);
                                    }
                                }else{
                                         deleteToken($token,$owner_id);
                                         $this->response([
                                                                 'response' => 'error',
                                                                 'message' => 'Token is expired',
                                                                 'token_status'=>'Expired'
                                                                 ], REST_Controller::HTTP_OK);
                                 }
                    }else{
                            $this->response([
                                        "response"=>"error",
                                        "message" =>"unauthorized request."
                                        ],REST_Controller::HTTP_NOT_FOUND);
                    }
                
             }
             /**
              * This method is used for getting all task for todolist of mobile app using company and user id.
              * $returns json
              */
             
             function getTaskList_post(){
                 
                 $task_capacity=array();
                 $task_array=array();
                 $token=  $this->post('token');
                 $start_date= $this->post('start_date');
                 $end_date= $this->post('end_date');
                 $user_id=  $this->post('user_id');
                 $company_id=  $this->post('company_id');
                 $minute=checkToken($token,$user_id);
                 if($this->post('user_color_id')!=''){$cal_user_color_id=$this->post('user_color_id');}else{$cal_user_color_id='0';}
                 if($this->post('sorting')!=''){$calender_sorting=$this->post('sorting');}else{$calender_sorting='1';}
                 $completed=get_company_completed_id($company_id);
                 $user_timezone = getUserTimezone($user_id,$company_id);
                // $timezone_offset = getOffset($user_timezone);
                 /**
                  * Create salt key using private key
                  */
                 $content =  json_encode(array(
                                "user_id"=>$user_id,
                                "company_id"=>$company_id,
                                "user_color_id"=>$cal_user_color_id,
                                "sorting"=>$calender_sorting,
                                "token"=>$token,
                                "start_date"=>$start_date,
                                "end_date"=>$end_date
                                ));
                 $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                 $contenthash=getallheaders();
                
                /**
                 *  It will  get salt value from header and check, if match then it return response otherwise return error message.
                 */
                if($hash==$contenthash['salt']){
                    /**
                     * This condition will check token validation. 
                     */
                        if($minute<20){
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
                                                    $task_array[]=["date"=>$key,"date_format"=>'d/m/Y',"day"=>$day,"capacity"=>$task_capacity,"total_estimate_time"=>$total_time,"task_list"=>$newvalue];
                                               }
                                            }


                                        //  updateAccessToken($token,$user_id);
                                          if(!empty($alltask)){
                                                $this->response([
                                                                   'response'=>'success',
                                                                   'message'=>'successfully found',
                                                                   'task'=>$task_array
                                                                ],REST_Controller::HTTP_OK);
                                          }else{
                                              $this->response([
                                                              'response'=>'success',
                                                              'message'=>'successfully found',
                                                              'task'=>$task_array
                                                              ],REST_Controller::HTTP_OK);
                                          }
                                  
                       }  else {
                                    deleteToken($token,$user_id);
                                     $this->response([
                                                      'response' => 'error',
                                                      'message' => 'Token is expired',
                                                      'token_status'=>'Expired'
                                                      ], REST_Controller::HTTP_OK);
                       }
                }else{
                                    $this->response([
                                                       'response' => 'error',
                                                       'message' => 'unauthorized request'
                                                       ], REST_Controller::HTTP_NOT_FOUND);
                        }
                }
             
             /**
              * This method is used for update task.
              * return task-id
              */
              function updateTask_post(){
                 $token=  $this->post('token');
                 $company_id=  $this->post('company_id');
                 $owner_id=  $this->post('user_id');
                 $task_id = $this->post('task_id');
                 $task_title=  $this->post('task_title');
                 $task_description=$this->post('task_description');
                 $task_due_date=$this->post('task_due_date');
                 $task_scheduled_date=$this->post('task_scheduled_date');
                 $task_status=  $this->post('task_status_id');
                 $task_priority=  $this->post('task_priority');
                 $task_allocated_user_id=  $this->post('task_allocated_user_id');
                 $task_project_id=  $this->post('task_project_id');
                 $task_watch_list=  $this->post('is_watch');
                 $is_personal=  $this->post('is_personal');
                 $task_time_estimate=  $this->post('task_time_estimate');
                 $task_actual_time=  $this->post('task_actual_time');
                 
                 $minute=checkToken($token,$owner_id);


                 /**
                  * create salt key using private key
                  */
                  $content=json_encode(array(
                                "user_id"=>$owner_id,
                		"company_id"=>$company_id,
                		"task_id"=>$task_id,
                		"task_title"=>$task_title,
                		"task_description"=>$task_description,
                		"task_due_date"=>$task_due_date,
                		"task_scheduled_date"=>$task_scheduled_date,
                		"task_status_id"=>$task_status,
                		"task_priority"=>$task_priority,
                		"task_allocated_user_id"=>$task_allocated_user_id,
                		"task_project_id"=>$task_project_id,
                		"is_watch"=>$task_watch_list,
                		"is_personal"=>$is_personal,
                		"task_time_estimate"=>$task_time_estimate,
                		"task_actual_time"=>$task_actual_time,
                                "token"=>$token
                               ));
                 
                $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                 //print_r($content); die();
                //echo $hash; die();
                $contenthash=getallheaders();
                /**
                 * It will  get salt value from header and check, if match then it return response otherwise return error message.
                 */
                if($hash==$contenthash['salt']){
                    /**
                     * there is checked token validation 
                     */
                 
                            if($minute<20){
                                      $id = $this->task_model->updateTaskInfo($task_id,$owner_id,$company_id,rawurldecode($task_title),rawurldecode($task_description),$task_due_date,$task_scheduled_date,$task_status,$task_priority,$task_allocated_user_id,$task_project_id,$task_watch_list,$is_personal,$task_time_estimate,$task_actual_time);
                                      $pricing_module_status = check_pricing_module_status($company_id);
                                        if($pricing_module_status == '1'){
                                            $this->update_pricing($id,$company_id);
                                        }
                                                         if($id){
                                                             $this->response([
                                                                             'response'=>'success',
                                                                             'message'=>'sucessfully update',
                                                                             'task_id'=>$id,
                                                                             ],REST_Controller::HTTP_OK);

                                                         }else{
                                                             $this->response([
                                                                              'response' => 'error',
                                                                              'message' => 'Task not update'
                                                                              ], REST_Controller::HTTP_OK);
                                                         } 
                            }else{
                                        deleteToken($token,$owner_id);
                                        $this->response([
                                                          'response' => 'error',
                                                          'message' => 'Token is expired',
                                                          'token_status'=>'Expired'
                                                          ], REST_Controller::HTTP_OK);
                           }
                }else{
                            $this->response([
                                            "response"=>"error",
                                            "message" =>"unauthorized request."
                                            ],REST_Controller::HTTP_NOT_FOUND);
                }
                
             }
             /**
              * This method is used for adding new comment. 
              * returns json
              */
              function addComment_post(){
                    $token=  $this->post('token');
                    $task_id = $this->post('task_id');
                    $task_comment = $this->post('task_comment');
                    $task_due_date = $this->post('due_date');
                    $user_id =  $this->post('user_id');
                    $company_id =$this->post('company_id');
                    $minute=checkToken($token,$user_id);
                    $user_timezone = getUserTimezone($user_id,$company_id);
                 //   $timezone_offset = getOffset($user_timezone);
                    /**
                     * create salt key using private key
                     */
                    $content=json_encode(array(
                                    "user_id"=>$user_id,
                                    "task_comment"=>$task_comment,
                                    "task_id"=>$task_id,
                                    "token"=>$token,
                                    "due_date"=>$task_due_date,
                                    "company_id"=>$company_id
                                    ));
                    
                    $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                    $contenthash=getallheaders();
                    /**
                     * It will  get salt value from header and check, if match then it return response otherwise return error message.
                     */
                   if($hash==$contenthash['salt']){
                    /**
                     * There is checked token deatils 
                     */
                           if($minute<20){
                                                $chk_exist = chk_task_exists($task_id);
                                                if($chk_exist=='0'){
                                                        $main_id = explode("_", $task_id);
                                                        $master_task_id = $main_id[1];
                                                        $task = get_task_info($master_task_id,$company_id);
                                                        $task_scheduled_date  = date("Y-m-d",strtotime(str_replace(array("/"," ",","), "-", $task_due_date)));
                                                        $data = array(
                                                                 'task_company_id' => $task['task_company_id'],
                                                                 'master_task_id'=>$master_task_id,
                                                                 'task_title' => $task['task_title'],
                                                                 'task_description' => $task['task_description'],
                                                                 'is_personal' => $task['is_personal'],
                                                                 'task_priority' => $task['task_priority'],
                                                                 'task_due_date' => $task_scheduled_date,
                                                                 'task_scheduled_date' => $task_scheduled_date,
                                                                 'task_orig_scheduled_date' => $task_scheduled_date,
                                                                 'task_orig_due_date' => $task_scheduled_date,
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
                                                         
                                                         $data1 = array(
                                                                    'cost_per_hour'=> get_user_cost_per_hour($task['task_allocated_user_id'], $company_id),
                                                                    'charge_out_rate'=> get_charge_out_rate($task_id,$company_id)
                                                                   );
                                                         $this->db->where('task_id',$task_id);
                                                         $this->db->update('tasks',$data1);
                                                         
                                                         $pricing_module_status = check_pricing_module_status($company_id);
                                                         if($pricing_module_status == '1'){
                                                            $this->update_pricing($task_id,$company_id);
                                                         }
                                                         $swimlane_id1 = get_default_swimlane($task['task_allocated_user_id']);
                                                         $user_swimlane1 = array(
                                                                'user_id' => $task['task_allocated_user_id'],
                                                                'task_id' => $task_id,
                                                                'swimlane_id' => $swimlane_id1,
                                                         );
                                                         $this->db->insert('user_task_swimlanes',$user_swimlane1);
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
                                                                $comment[]= $this->task_model->saveComment($task_id,$user_id,rawurldecode($task_comment),$user_timezone);

                                                    }else{
                                                                $comment[]= $this->task_model->saveComment($task_id,$user_id,rawurldecode($task_comment),$user_timezone);
                                                    }
                                               if($comment){
                                                    $this->response([
                                                                     "response"=>'success',
                                                                     "message"=>'successfully add comment',
                                                                     "task_id"=>$task_id,
                                                                     "comments"=>$comment
                                                                    ],REST_Controller::HTTP_OK);

                                                }else {
                                                    $this->response([
                                                                     'response' => 'error',
                                                                     'message' => 'comment not save'
                                                                    ], REST_Controller::HTTP_OK);
                                                }

                            }else {
                                    deleteToken($token,$user_id);
                                    $this->response([
                                                            'response' => 'error',
                                                            'message' => 'Token is expired',
                                                            'token_status'=>'Expired'
                                                            ], REST_Controller::HTTP_OK);

                            }
                    }else{
                            $this->response([
                                    "response"=>"error",
                                    "message" =>"unauthorized request."
                                    ],REST_Controller::HTTP_NOT_FOUND);
                    }
                } 

              /**
               * This API is returned overall details of logined in user.
               */  
              function getUserdetail_post(){
                    
                    $token   = $this->post('token');
                    $user_id = $this->post('user_id');
                    $company_id = $this->post('company_id');

                    $minute=checkToken($token,$user_id);
                      /**
                       * create salt key using private key
                       */
                    $content=json_encode(array(
                                        "user_id"=>$user_id,
                                        "company_id"=>$company_id,
                                         "token"=>$token
                                    ));
                    
                    $hash = hash_hmac('sha1', $content, PRIVATEKEY);
                    //echo $hash; die();
                    $contenthash=getallheaders();
                    /**
                     * It will  get salt value from header and check, if match then it return response otherwise return error message.
                     */
                    if($hash==$contenthash['salt']){
                    /**
                     * There is checked token validation 
                     */
                       if($minute<20){
                        	$user=array();
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
                                                  "date_format"=>'d/m/Y',
                                                  "is_completed_time"=>$actual_time_status
                                                 ],REST_Controller::HTTP_OK);

                                                
                                
                            }else{
                                deleteToken($token,$user_id);
                                $this->response([
                                                'response' => 'error',
                                                'message' => 'Token is expired',
                                                'token_status'=>'Expired'
                                                 ], REST_Controller::HTTP_OK);
                                
                            }
                            
                    }else{
                               $this->response([
                                                "response"=>"error",
                                                "message" =>"unauthorized request."
                                                ],REST_Controller::HTTP_NOT_FOUND);  
                    }
                  
              }  
             
            
               /**
               * This API is returned open task list i.e (Not completed task)
               */
              
              function openTaskList_post(){
                         $task_capacity=array();
                         $task_array=array();   
                         $token=  $this->post('token');
                         $start_date= $this->post('start_date');
                         $end_date= $this->post('end_date');
                         //echo $start_date; die();
                         $user_id=  $this->post('user_id');
                         $company_id=  $this->post('company_id');
                         $minute=checkToken($token,$user_id);
                         if($this->post('user_color_id')!=''){$cal_user_color_id=$this->post('user_color_id');}else{$cal_user_color_id='0';}
                         if($this->post('sorting')!=''){$calender_sorting=$this->post('sorting');}else{$calender_sorting='1';}
                         $completed=get_company_completed_id($company_id);
                         $user_timezone = getUserTimezone($user_id,$company_id);
                        // $timezone_offset = getOffset($user_timezone);
                         // create salt key with private key
                         $content =  json_encode(array(
                                        "user_id"=>$user_id,
                                        "company_id"=>$company_id,
                                        "user_color_id"=>$cal_user_color_id,
                                        "sorting"=>$calender_sorting,
                                        "token"=>$token,
                                        "start_date"=>$start_date,
                                        "end_date"=>$end_date
                                        ));
                         $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                         $contenthash=getallheaders();
                        
                        /**
                         * It will  get salt value from header and check, if match then it return response otherwise return error message.
                         */
                       if($hash==$contenthash['salt']){
                            /**
                             * There is checked token deatils 
                             */
                                if($minute<20){
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
						   $task_array[]=["date"=>$key,"date_format"=>'d/m/Y',"day"=>$day,"capacity"=>$task_capacity,"task_list"=>$newvalue];
                                                }  
                                            }
                                            if($task_array){
                                                   $this->response([
                                                                      'response'=>'success',
                                                                      'message'=>'successfully found',
                                                                      'open_task'=>$task_array
                                                                   ],REST_Controller::HTTP_OK);
                                             }else{
                                                 $this->response([
                                                                   'response'=>'success',
                                                                   'message'=>'successfully found',
                                                                   'open_task'=>$task_array
                                                                  ],REST_Controller::HTTP_OK);
                                             }
                                         
                                         
                                }else {
                                       deleteToken($token,$user_id);
                                       $this->response([
                                                               'response' => 'error',
                                                               'message' => 'Token is expired',
                                                               'token_status'=>'Expired'
                                                               ], REST_Controller::HTTP_OK);
                                }
                       }else{
                                       $this->response([
                                                              'response' => 'error',
                                                              'message' => 'unauthorized request'
                                                              ], REST_Controller::HTTP_NOT_FOUND);
                               }
                 }
                 
                 /**
                  * This API is returned overdue task i.e (Not completed task in past)
                  */
              function overDueTaskList_post(){
                         $task_capacity=array();
                         $task_array=array();   
                         $token=  $this->post('token');
                         $start_date= $this->post('start_date');
                         $end_date= $this->post('end_date');
                         //echo $start_date; die();
                         $user_id=  $this->post('user_id');
                         $company_id=  $this->post('company_id');
                         $minute=checkToken($token,$user_id);
                         if($this->post('user_color_id')!=''){$cal_user_color_id=$this->post('user_color_id');}else{$cal_user_color_id='0';}
                         if($this->post('sorting')!=''){$calender_sorting=$this->post('sorting');}else{$calender_sorting='1';}
                         $completed=get_company_completed_id($company_id);
                         $user_timezone = getUserTimezone($user_id,$company_id);
                       //  $timezone_offset = getOffset($user_timezone);
                         /**
                          *  create salt key with private key
                          */
                         $content =  json_encode(array(
                                        "user_id"=>$user_id,
                                        "company_id"=>$company_id,
                                        "user_color_id"=>$cal_user_color_id,
                                        "sorting"=>$calender_sorting,
                                        "token"=>$token,
                                        "start_date"=>$start_date,
                                        "end_date"=>$end_date
                                        ));
                         $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                         $contenthash=getallheaders();
                       
                        /**
                         * It will  get salt value from header and check, if match then it return response otherwise return error message.
                         */
                         if($hash==$contenthash['salt']){
                            /*
                             * There is checked token deatils 
                             */
                                if($minute<20){
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
												   
                                                    $task_array[]=["date"=>$key,"date_format"=>'d/m/Y',"day"=>$day,"capacity"=>$task_capacity,"task_list"=>$newvalue];
                                                }  
                                            }
                                            if($task_array){
                                                $this->response([
                                                                   'response'=>'success',
                                                                   'message'=>'successfully found',
                                                                   'overDue_task'=>$task_array
                                                                ],REST_Controller::HTTP_OK);
                                            }else{
                                              $this->response([
                                                              'response'=>'success',
                                                              'message'=>'successfully found',
                                                              'overDue_task'=>$task_array
                                                              ],REST_Controller::HTTP_OK);
                                          }
                                         
                                         
                               }else {
                                      deleteToken($token,$user_id);
                                      $this->response([
                                                              'response' => 'error',
                                                              'message' => 'Token is expired',
                                                              'token_status'=>'Expired'
                                                              ], REST_Controller::HTTP_OK);
                               }
                       }else{
                                       $this->response([
                                                              'response' => 'error',
                                                              'message' => 'unauthorized request'
                                                              ], REST_Controller::HTTP_NOT_FOUND);
                        }
                 }
               /**
                * This API is used for getting user default status list using company id.
                */  
              function getUserStatus_post(){
                  
                        $user_id=  $this->post('user_id');
                        $company_id=  $this->post('company_id');
                        $token=  $this->post('token');
                        $minute=checkToken($token,$user_id);
                        
                      /**
                        * create salt key using privatekey
                        */

                       $content=  json_encode(array(
                                      "user_id"=>$user_id,
                                      "company_id"=>$company_id,
                                      "token"=>$token,
                                      ));
                       $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                       $contenthash=getallheaders();
                       /**
                         * It will  get salt value from header and check, if match then it return response otherwise return error message.
                         */
                       if($hash == $contenthash['salt']){
                            /*
                             * There is checked token deatils 
                             */
                               if($minute<20){
                                            $status = get_task_status($company_id,'Active');
                                            $this->response([
                                                             'response'=>'success',
                                                             'message'=>'successfully found',
                                                             'status'=>$status
                                                            ],REST_Controller::HTTP_OK);
                                            
                                }else {
                                      deleteToken($token,$user_id);
                                      $this->response([
                                                              'response' => 'error',
                                                              'message' => 'Token is expired',
                                                              'token_status'=>'Expired'
                                                              ], REST_Controller::HTTP_OK);
                               }
                        }else{
                            $this->response([
                                             'response' => 'error',
                                             'message' => 'unauthorized request'
                                            ], REST_Controller::HTTP_NOT_FOUND);
                        }


                        
                 }
                 
              /**
               * This API is used for deleting task.
               */   
              function deleteTask_post(){
                   $user_id =  $this->post('user_id');
                   $task_id = $this->post('task_id');
                   $company_id= $this->post('company_id');
                   $due_date = $this->post('due_date');
                   $token = $this->post('token');
                   $minute = checkToken($token,$user_id);
                        
                      /**
                        * create salt key using privatekey
                        */

                       $content=  json_encode(array(
                                      "user_id"=>$user_id,
                                      "task_id"=>$task_id,
                                      "company_id"=>$company_id,
                                      "due_date"=>$due_date,
                                      "token"=>$token,
                                      ));
                       $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                       $contenthash=getallheaders();
                      
                       /**
                         * It will  get salt value from header and check, if match then it return response otherwise return error message.
                         */
                      if($hash == $contenthash['salt']){
                            /*
                             * There is checked token deatils 
                             */
                               if($minute<20){
                                            $status = $this->task_model->deleteTask($task_id,$company_id,$user_id,$due_date);
                                            if($status){
                                                $this->response([
                                                             'response'=>'success',
                                                             'message'=>'successfully deleted',
                                                            ],REST_Controller::HTTP_OK);
                                            
                                            }else{
                                                $this->response([
                                                             'response'=>'success',
                                                             'message'=>'Not deleted',
                                                            ],REST_Controller::HTTP_OK);
                                            }
                                   
                                   }else {
                                      deleteToken($token,$user_id);
                                      $this->response([
                                                              'response' => 'error',
                                                              'message' => 'Token is expired',
                                                              'token_status'=>'Expired'
                                                              ], REST_Controller::HTTP_OK);
                               }
                        }else{
                            $this->response([
                                             'response' => 'error',
                                             'message' => 'unauthorized request'
                                            ], REST_Controller::HTTP_NOT_FOUND);
                        }
              }   
                 /**
                  * This API is used for getting project team .
                  */
              function getProjectTeam_post(){
                  
                        $user_id=  $this->post('user_id');
                        $company_id=  $this->post('company_id');
                        $token=  $this->post('token');
                        $project_id = $this->post('project_id');
                        $minute=checkToken($token,$user_id);
                        
                      /**
                        * create salt key using privatekey
                        */

                       $content =  json_encode(array(
                                      "user_id"=>$user_id,
                                      "company_id"=>$company_id,
                                      "project_id"=>$project_id,
                                      "token"=>$token,
                                    ));
                       $hash = hash_hmac("sha1", $content, PRIVATEKEY);
                       $contenthash=getallheaders();
                       /**
                         * It will  get salt value from header and check, if match then it return response otherwise return error message.
                         */
                       if($hash == $contenthash['salt']){
                            /*
                             * There is checked token deatils 
                             */
                               if($minute<20){
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
                                      deleteToken($token,$user_id);
                                      $this->response([
                                                        'response' => 'error',
                                                        'message' => 'Token is expired',
                                                        'token_status'=>'Expired'
                                                        ], REST_Controller::HTTP_OK);
                               }
                        }else{
                            $this->response([
                                             'response' => 'error',
                                             'message' => 'unauthorized request'
                                            ], REST_Controller::HTTP_NOT_FOUND);
                        }
              }
                
              function update_pricing($task_id,$company_id){
                  $task_details = get_task_info($task_id,$company_id);
                  $estimated_time = $task_details['task_time_estimate'];
                  $actual_time = $task_details['task_time_spent'];
                  $charge_out_rate = get_charge_out_rate($task_id,$company_id);
                  $base_employee_rate = get_user_cost_per_hour($task_details['task_allocated_user_id'],$task_details['task_company_id']);
                  if($actual_time == '0'){
                        $data = array(
                                  "cost_per_hour"=>$base_employee_rate,
                                  "cost"=>round(($base_employee_rate*$estimated_time)/60,2),
                                  "charge_out_rate"=>$charge_out_rate,
                                  "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2),
                                 );
                  }else if($estimated_time == '0'){
                        $data = array(
                                    "cost_per_hour"=>$base_employee_rate,
                                    "charge_out_rate"=>$charge_out_rate,
                                    "estimated_total_charge"=>round(($charge_out_rate*$estimated_time)/60,2),
                                    "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2)
                                 );
                  }else{
                        $data = array(
                                      "charge_out_rate"=>$charge_out_rate,
                                      "estimated_total_charge"=>round($charge_out_rate*$estimated_time/60,2),
                                      "actual_total_charge"=>round(($charge_out_rate*$actual_time)/60,2)
                                      );  
                  }
                  $this->db->where('task_id',$task_id);
                  $this->db->update('tasks',$data);
              }
}
?>
